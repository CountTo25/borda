import Cookies from "js-cookie";
import type { SvelteComponent } from "svelte";
import { writable, Writable } from "svelte/store";
import Thread from "../Models/Thread";
import Echo from "laravel-echo";
import Pusher from "pusher-js";



const echo = new Echo({
    broadcaster: 'pusher',
    key: 'LARABA-KEY',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});


class Toolbar {
    public open: boolean = false;
    public threads: Thread[] = [];
    public token: string|null = Cookies.get('LARABA-TOKEN') !== undefined? Cookies.get('LARABA-TOKEN') : null;
    public hasNotifications: boolean = false;

    public getUnreadCount(): number
    {
        let init = 0;
        console.log(this.threads);
        this.threads.forEach(t => {console.log(t.unreadPostCount); init+=t.unreadPostCount ?? 0});
        console.log(init);
        return init;
    }

    public clearUnread() {
        for (let i: number = 0; i<this.threads.length; i++) {
            this.threads[i].unreadPostCount = 0;
        }
    }
}

export const toolbar: Writable<Toolbar> = writable(new Toolbar());
export const toggleToolbar: ()=>void = () => toolbar.update(t => {if (t.open) {t.clearUnread()}; t.open = !t.open; return t});
export const consumePrepender = 
    (prefetched: any[], token: string) => toolbar.update(r => {
        prefetched.forEach(thread => {
            const newThread = new Thread();
            newThread.hydrate(Thread, thread);
            newThread.unreadPostCount = 0;
            r.threads.push(newThread)
        })
        r.token = token;
        r.threads = prefetched;
        r.threads.forEach(thread => {
            subscribeToChannel(thread.id);
        });
        return r; 
    })
export const pushSubscription = (thread: Thread) => {
    toolbar.update(t => {
        if (t.threads.map(_ => _.id).includes(thread.id)) {return t;}
        t.threads.push(thread);
        return t;
    });
}

export const removeSubscription = (thread: Thread) => {
    toolbar.update(t => {
        t.threads = t.threads.filter(_ => _.id !== thread.id);
        return t;
    })
}

function subscribeToChannel(channelid: number): void
{
    echo.channel('thread.'+channelid).listen('NewReply', function (socketdata) {
        toolbar.update(tb => {
            let target = tb.threads.filter(t => t.id === socketdata.post.thread_id)[0];
            let index = tb.threads.indexOf(target);
            
            if (isNaN(tb.threads[index].unreadPostCount)) { //TODO: find fix
                tb.threads[index].unreadPostCount = 0;
                console.log('fixing nan');
            }
            tb.threads[index].unreadPostCount++;
            tb.threads[index].post_count++;
            if (!tb.open) {
                tb.hasNotifications = true;
            }
            return tb;
        });   
    });
}
