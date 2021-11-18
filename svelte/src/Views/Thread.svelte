<script lang="ts">
    import Collection from "../Models/Support/Collection";
    import Thread from "../Models/Thread";
    import type Board from "../Models/Board";
    import BoardTitle from "../Components/BoardTitle.svelte";
    import ThreadPreview from "../Components/ThreadPreview.svelte";
    import NewPostForm from "../Components/NewPostForm.svelte";
    import ThreadReply from "../Components/ThreadReply.svelte";

    export let id: string;
    export let boardName: string;

    let thread: Thread = null;
    let toInclude: string[] = ['posts', 'firstPost'];
    let showSubmit = true;

    $:renderable = thread !== null && thread.posts.length > 1 ? thread.posts.slice(1) : [];
    
    //@ts-ignore
    if ('__prefetched' in window && 'thread' in window.__prefetched) {
        thread = new Thread();
        //@ts-ignore
        thread.hydrate(Thread, window.__prefetched.thread);
        //@ts-ignore
        delete window.__prefetched.thread;
    } else {
        getPosts();
    }


    function getPosts(): void
    {
        Collection.get(Thread, [{where: 'first_post_id', is: id}], toInclude)
            .then(r => thread = r.first());
    }
</script>

<div>
    <BoardTitle {boardName}/>

    <div class='row'> 
    {#if thread !== null}
        {#if showSubmit}
            <div class='col-0 col-lg-4'/>
            <div class='col-12 col-lg-4 mb-2'>
                <NewPostForm 
                    {thread} 
                    on:close={()=>showSubmit = false} 
                    on:sent={()=>{getPosts(); showSubmit = false}}
                />
            </div>
        {:else}
            <div class='col-12 text-center mb-2'>
                <button on:click={()=>showSubmit = true}>Ответить в тред</button>       
            </div>
        {/if}
        <ThreadPreview {thread}/>
        {#each renderable as post}
            <div class='col-12 col-lg-7 ps-0' >
                <ThreadReply {post}/>
            </div>
        {/each}
    {:else}
            ...
    {/if}
    </div>
</div>