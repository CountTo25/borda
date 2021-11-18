<script lang='ts'>
    import api from "./API";
import RouterLink from "./Router/RouterLink.svelte";
    import {storeBoard} from "./Storage/Boards";

    //here app will prefetch all the needed information for initial render
    //but if app is run from laravel view, it will try to grab injected stuff from it
    //consider this an entrypoint into app

    let boards: string[] = [];

    if (!('__prefetched' in window)) { //refer to app/Services/Prefetcher.php
        api.listBoards().then(r => boards = r.data);
    } else {
        //@ts-ignore
        boards = window.__prefetched.boards;
    }
</script>

<div class='row header'>
    <div class='col-12 py-1'>
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