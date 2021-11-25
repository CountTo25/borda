<script lang='ts'>
    import type Post from "../Models/Post";
    import { setHighlightPost, rendering } from "../Storage/Rendering";
    import PlainText from "./PlainText.svelte";
    import ReferenceText from "./ReferenceText.svelte";
    export let post: Post;
    //@ts-ignore
    import lang from "../lang.json";
    let highlight: boolean;
    $:highlight = $rendering.highlight == post.id;
    let hasImages: boolean = post.images.length > 0;

    console.log(post);

    const componentMap = {
        'reference': ReferenceText,
        'plain': PlainText,
    };
</script>

<div class='thread-body mb-2' class:highlight>
    <div class='row'>
        <div class='col-12'>
            <span class='me-1'>#{post.id}</span>
            <span class='me-1 fw-100'>{post.created_at.format('DD/MM/YY HH:mm')}</span>
            <span class='me-1'>{post.user_name}</span>
            {#if post.own}
                <span class='me-1 text-faded'>({lang.markings.yourPost})</span>
            {/if}
        </div>
        <div class='col-12 mb-1'>
            {#each post.mentions as mention}
                <span 
                    class='me-1 mention' 
                    on:click={()=>{setHighlightPost(mention.mentioned_at_id)}}
                    >>{mention.mentioned_at_id}</span>
            {/each}
        </div>
        <div class='col-12'>
        {#if hasImages}
            <div class='img-wrap'>
                {#each post.images as image}
                    <img 
                        class='post-img'
                        src={image.url}
                        style={'max-width: calc('+100/post.images.length+'% - 10px)'}
                        alt={image.getFilename()}
                    >
                {/each}
            </div>
        {/if}
            <div class='thread-content px-0 col'>
                {#each post.content as content}
                    <svelte:component 
                        on:reference={e => {console.log(e.detail.id); setHighlightPost(e.detail.id)}} 
                        this={componentMap[content.mode]} 
                        text={content.text}
                    />
                {/each}
            </div>
        </div>
    </div>
</div>