import type { SvelteComponent } from "svelte";
import { writable, Writable } from "svelte/store";


class Popup {
    public open: boolean = false;
    public mode: PopupMode = 'center';
    public component?: typeof SvelteComponent = null;
}

type PopupMode = 'center'|'side';

export const popup: Writable<Popup> = writable(new Popup());
export const closePopup = () => popup.update(p => {p.open = false; return p})
export const setPopup = (toRender: typeof SvelteComponent, mode: PopupMode = 'center') => popup.update(p => {
    p.component = toRender;
    p.open = true;
    p.mode = mode;
    return p;
});

