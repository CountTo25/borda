<script lang='ts'>
    import type Post from "../Models/Post";
    export let post: Post;
    let hasImages: boolean = post.images.length > 0;
</script>

<div class='thread-body mb-2'>
    <div class='row'>
        <div class='col-12'>
            <span class='me-1'>#{post.id}</span>
            <span class='me-1 fw-100'>{post.created_at.format('DD/MM/YY HH:mm')}</span>
            <span class='me-1'>{post.user_name}</span>
        </div>
        {#if hasImages}
            <div class='col-4'>
                <div>
                    {#each post.images as image}
                        <img 
                            src={image.url}
                            style={'max-width: '+100/post.images.length+'%'}
                            alt={image.getFilename()}
                        >
                    {/each}
                </div>
            </div>
        {/if}
        <div class:col-8={hasImages} class:col-12={!hasImages} class='thread-content'>
            {post.content ?? ''}
        </div>
    </div>
</div>