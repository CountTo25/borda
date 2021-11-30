<script lang='ts'>
    import api from "./API";
    import RouterLink from "./Router/RouterLink.svelte";
    import {fade, slide} from "svelte/transition";
    import { popup, setPopup, closePopup } from "./Storage/Popup";
    import Auth from "./Components/Popups/Auth.svelte";
    import { toolbar, toggleToolbar } from "./Storage/Toolbar";
    import Toolbar from "./Components/Toolbar.svelte";

    //here app will prefetch all the needed information for initial render
    //but if app is run from laravel view, it will try to grab injected stuff from it
    //consider this an entrypoint into app

    let boards: string[] = [];
    let authPopup = false;

    if (!('__prefetched' in window)) { //refer to app/Services/Prefetcher.php
        api.listBoards().then(r => boards = r.data);
    } else {
        //@ts-ignore
        boards = window.__prefetched.boards;
        
    }

    function popupAuth(): void
    {
        setPopup(Auth);
    }

    function showToolbar(): void 
    {

    }
</script>

<div class='row header'>
    <div class='col-12 py-1'>
        <RouterLink>
        <span class='px-1 board-nav'>/</span>
        </RouterLink>
        {#each boards as board}
            <RouterLink href='/{board}'><span class='px-1 board-nav'>/{board}/</span></RouterLink>
        {/each}
    </div>
</div>
<div class='row view-body'>
    <div class='col-12'>
        <slot/>
    </div>
</div>
<div class='row footer'>
    <div class='col-2'/>
    <div class='col-8 text-center py-1'>
        <span class='button px-1' on:click={popupAuth}>
            Войти
        </span>
    </div>
    <div class='col-2 text-end py-1'>
        <span class='button px-1' on:click={toggleToolbar}>
            Подписки
            {#if $toolbar.hasNotifications}
                {$toolbar.getUnreadCount()}
            {/if}
        </span>
    </div>
</div>

{#if $popup.open}
    <div class='popup-wrap' on:click={closePopup}>
        <div class='popup' in:fade={{duration: 100}} on:click|stopPropagation>
            <svelte:component this={$popup.component}/>
        </div>
    </div>
{/if}

{#if $toolbar.open}
    <div class='toolbar col-2' in:slide>
        <Toolbar/>
    </div>
{/if}