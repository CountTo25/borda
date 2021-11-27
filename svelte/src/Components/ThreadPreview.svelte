<script lang='ts'>
    import type Thread from "../Models/Thread";
    import { toolbar, pushSubscription, removeSubscription } from "../Storage/Toolbar";
    import PlainText from "./PlainText.svelte";
    import ReferenceText from "./ReferenceText.svelte";
    export let thread: Thread;
    import lang from "../lang.json";
    import FaStar from "./Icons/FAStar.svelte";
    import FaExclamation from "./Icons/FAExclamation.svelte";
    import API from "../API";
    import FaStarFull from "./Icons/FAStarFull.svelte";

    const componentMap = {
        'reference': ReferenceText,
        'plain': PlainText,
    };

    let hasImages: boolean = thread.first_post.images.length > 0;


    let subscribed: boolean;
    $:subscribed = $toolbar.threads.map(t => t.id).includes(thread.id);

    function subscribe() {
        if (subscribed) {
            API.thread.unsubscribe(thread.id, $toolbar.token).then(r => {
                removeSubscription(thread);
            });
        } else {
            API.thread.subscribe(thread.id, $toolbar.token).then( r => {
                pushSubscription(thread);
            });
        }
        
    }

</script>

<div class='thread-body mb-2'>
    <div class='row'>
        <div class='col-12'>
            <span class='me-1'>@{thread.first_post.id}</span>
            <span class='me-1 fw-100'>{thread.created_at.format('DD/MM/YY HH:mm')}</span>
            <span class='me-1'>{thread.first_post.user_name}</span>
            <span class='me-1'>{thread.title}</span>
            <span class='me-1'>ответы: {thread.post_count - 1}</span>
            {#if thread.first_post.own}
                <span class='me-1 text-faded'>({lang.markings.yourPost})</span>
            {/if}
        </div>
        <div class='col-12 mt-1 thread-controls text-start'>
            <span class='mention p-1 me-1' on:click|stopPropagation={subscribe}>
                {#if subscribed}
                    <FaStarFull/>
                {:else}
                    <FaStar/>
                {/if}
            </span>
            <span class='mention p-1 me-1'><FaExclamation/></span>
        </div>
        {#if hasImages}
            <div class='img-wrap'>
                {#each thread.first_post.images as image}
                    <img
                        class='post-img'
                        src={image.url}
                        style={'max-width: calc('+100/thread.first_post.images.length+'% - 10px)'}
                        alt={image.getFilename()}
                    >
                {/each}
            </div>
        {/if}
        <div class='thread-content col'>
            {#each thread.first_post.content as content}
                    <svelte:component this={componentMap[content.mode]} text={content.text}/>
                {/each}
        </div>
    </div>
</div>