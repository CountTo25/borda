<script lang='ts'>
    import Collection from "../Models/Support/Collection";
    import Board from "../Models/Board";

    import ThreadPreview from "../Components/ThreadPreview.svelte";
    import NewThreadForm from "../Components/NewThreadForm.svelte";
    import BoardTitle from "../Components/BoardTitle.svelte";
    import ThreadReply from "../Components/ThreadReply.svelte";

    import type ThreadModel from "../Models/Thread";
    import type Post from "../Models/Post";
    import RouterLink from "../Router/RouterLink.svelte";

    export let boardName: string;

    let board: Board|null = null;
    let loading: boolean = true;

    let showSubmit: boolean = false;


    document.title = `/${boardName}/`;
    //@ts-ignore
    if ('__prefetched' in window && 'board' in window.__prefetched) {
        console.log('has prefetch');
        board = new Board();
        //@ts-ignore
        board.hydrate(Board, window.__prefetched.board);
        //@ts-ignore
        delete window.__prefetched.board;
    } else {
        console.log('should fetch, boards seems to be null');
        Collection.get(
            Board,
            [{where: 'short_name', is: boardName}],
            ['threads.latestPosts.images', 'threads.firstPost.images', 'threads.firstPost.mentions', 'threads.latestPosts.mentions']
        ).then(r => board = r.first());
        console.log('tried to fetch');
    }

    $: if (board !== null) console.log(board)

    

    function getLatestPosts(thread: ThreadModel): Post[]
    {
        return thread.latest_posts
            .filter(post => post.id !== thread.first_post.id)
            .reverse();
    }

</script>

{#if board === null}
    ...
{:else}
<div class='row'>
    <BoardTitle {boardName} prefetched={board}/>
    {#if showSubmit}
        <div class='col-0 col-lg-4'/>
        <div class='col-12 col-lg-4'>
            <NewThreadForm {board} on:close={()=>showSubmit = false}/>
        </div>
    {:else}
        <div class='col-12 text-center'>
            <button on:click={()=>showSubmit = true}>Создать тред</button>       
        </div>     
    {/if}
    <div class='col-12 col-lg-7 mt-2'>
        <div class='row'>
        {#each board.threads as thread}
            <div class='col-12 ps-0'>
            <RouterLink href='{board.short_name}/{thread.first_post.id}/'>
                <ThreadPreview {thread}/>
            </RouterLink>
            </div>
            <div class='col-1'/>
            <div class='col-11 ps-0'>
                {#each getLatestPosts(thread) as post}
                    <ThreadReply {post}/>
                {/each}
            </div>
        {/each}
        </div>
    </div>
</div>
{/if}

