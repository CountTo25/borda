<script lang='ts'>
    import type Thread from "../Models/Thread";
    export let thread: Thread;
</script>

<div class='thread-body mb-2'>
    <div class='row'>
        <div class='col-12'>
            <span class='me-1'>@{thread.first_post.id}</span>
            <span class='me-1 fw-100'>{thread.created_at.format('DD/MM/YY HH:mm')}</span>
            <span class='me-1'>{thread.first_post.user_name}</span>
            <span class='me-1'>{thread.title}</span>
            <span class='me-1'>ответы: {thread.post_count - 1}</span>
        </div>
        <div class='col-12 thread-content'>
            {thread.first_post.content}
        </div>
    </div>
</div>