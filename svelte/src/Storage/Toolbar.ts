import type { SvelteComponent } from "svelte";
import { writable, Writable } from "svelte/store";
import Thread from "../Models/Thread";


class Toolbar {
    public open: boolean = false;
    public threads: Thread[] = [];
}

export const toolbar: Writable<Toolbar> = writable(new Toolbar());
export const toggleToolbar: ()=>void = () => toolbar.update(t => {t.open = !t.open; return t});
export const consumePrepender = 
    (prefetched: any[]) => toolbar.update(r => {
        prefetched.forEach(thread => {
            const newThread = new Thread();
            newThread.hydrate(Thread, thread);
            r.threads.push(newThread)
        })
        r.threads = prefetched;
        return r; 
    })
