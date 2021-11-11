<script lang='ts'>
    import api from "./API";
    //here app will prefetch all the needed information for initial render
    //but if app is run from laravel view, it will try to grab injected stuff from it
    let boards: string[] = [];
    if (!('$__borda_prefetch' in window)) {
        api.listBoards().then(r => boards = r.data);
    } else {
        //@ts-ignore
        boards = window.$__borda_prefetch.boards;
    }
</script>

<div class='row header'>
    <div class='col-12 py-1'>
        {#each boards as board}
            <a href='/#/{board}'><span class='px-1 board-nav'>/{board}/</span></a>
        {/each}
    </div>
</div>
<div class='row view-body'>
    <div class='col-12'>
        <slot/>
    </div>
</div>