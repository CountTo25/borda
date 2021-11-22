<script lang='ts'>
    import api from "./API";
    import RouterLink from "./Router/RouterLink.svelte";
    import {storeBoard} from "./Storage/Boards";
    import {fade} from "svelte/transition";

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

    function toggleAuth(): void
    {
        authPopup = !authPopup;
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
        <span class='button px-1' on:click={toggleAuth}>
            Войти
        </span>
    </div>
    <div class='col-2 text-center py-1'>
        <span class='button px-1' on:click={toggleAuth}>
            Подписки
        </span>
    </div>
</div>

{#if authPopup}
<div class='popup-wrap' on:click={toggleAuth}>
    <div class='popup' in:fade={{duration: 100}} on:click|stopPropagation>
        <div class='row'>
            <div class='col-12 text-center'>
                Войти
            </div>
            <div class='col-12'>
                Вы можете сгенерировать токен: он позволит отслеживать треды и посты на разных девайсах
            </div>
            <div class='col-12'>
                Если вы потеряете токен, восстановить его не получится. Не теряйте его
            </div>
            <div class='col-12'>
                Токен это не регистрация. Никто не будет знать, что вы пользуетесь токеном; никто не будет знать, кто является автором поста
            </div>
        </div>
    </div>
</div>
{/if}