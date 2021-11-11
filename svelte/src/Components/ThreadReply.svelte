<script lang='ts'>
    import type Post from "../Models/Post";
    export let post: Post;
</script>

<div class='thread-body mb-2'>
    <div class='row'>
        <div class='col-12'>
            <span class='me-1'>#{post.id}</span>
            <span class='me-1 fw-100'>{post.created_at.format('DD/MM/YY HH:mm')}</span>
            <span class='me-1'>{post.user_name}</span>
        </div>
        <div class='col-12 thread-content'>
            {post.content}
        </div>
    </div>
</div>