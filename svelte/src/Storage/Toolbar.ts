import Cookies from "js-cookie";
import type { SvelteComponent } from "svelte";
import { writable, Writable } from "svelte/store";
import Thread from "../Models/Thread";


class Toolbar {
    public open: boolean = false;
    public threads: Thread[] = [];
    public token: string|null = Cookies.get('LARABA-TOKEN') !== undefined? Cookies.get('LARABA-TOKEN') : null;
}

export const toolbar: Writable<Toolbar> = writable(new Toolbar());
export const toggleToolbar: ()=>void = () => toolbar.update(t => {t.open = !t.open; return t});
export const consumePrepender = 
    (prefetched: any[], token: string) => toolbar.update(r => {
        prefetched.forEach(thread => {
            const newThread = new Thread();
            newThread.hydrate(Thread, thread);
            r.threads.push(newThread)
        })
        r.token = token;
        r.threads = prefetched;
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
