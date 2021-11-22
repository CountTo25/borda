import { writable, Writable } from "svelte/store";


class Renderable {
    public highlight?: number;
}

export const rendering: Writable<Renderable> = writable(new Renderable());
export function setHighlightPost(id: number) {rendering.update(r => {r.highlight = id; return r})}

