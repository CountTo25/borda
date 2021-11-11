<script lang="ts">
    import API, { PostData, ThreadData } from "../API";
    import {createEventDispatcher} from "svelte";
    import type Board from "../Models/Board";
    import Routing from "../Router/Support/Routing";
import type Thread from "../Models/Thread";

    export let thread: Thread;

    const dispatch = createEventDispatcher();

    let postData: PostData = {
        content: null,
        user_name: null,
        thread_id: thread.id,
    }
    
    function post(): void {
        API.createPost(postData).then(r => dispatch('sent'));
    }
    
</script>

<div class='row thread-form-wrap'>
    <div class='col-12 text-center mb-2'>
        Ответ в тред @{thread.id}
    </div>
    <div class='col-4 my-auto'>
        Имя
    </div>
    <div class='col-8 my-auto mb-1'>
        <input class='w-100' placeholder='Аноним' bind:value={postData.user_name}>
    </div>
    <div class='col-4 my-auto'>
        Контент
    </div>
    <div class='col-8 my-auto mb-1'>
        <textarea class='w-100' placeholder='' bind:value={postData.content} />
    </div>
    <div class='col-4'>
        <button on:click={()=>dispatch('close')}>Закрыть</button>
    </div>
    <div class='col-8 text-center'>
        <button on:click={post}>Отправить</button>
    </div>
</div>