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
            {post.content ?? ''}
        </div>
    </div>
</div>