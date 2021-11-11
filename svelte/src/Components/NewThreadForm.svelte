<script lang="ts">
    import API, { ThreadData } from "../API";
    import {createEventDispatcher} from "svelte";
    import type Board from "../Models/Board";
    import Routing from "../Router/Support/Routing";

    export let board: Board;

    const dispatch = createEventDispatcher();

    let threadData: ThreadData = {
        thread: {
            title: null,
            board_id: board.id,
        }, 
        post: {
            user_name: board.default_username,
            content: null,
        }
    };

    threadData.thread.board_id = board.id;

    function post(): void {
        API.createThread(threadData).then(r => Routing.go('/'+board.short_name+'/'+r.data.thread_id));
    }
    
</script>

<div class='row thread-form-wrap'>
    <div class='col-12 text-center mb-2'>
        Новый тред
    </div>
    <div class='col-4 my-auto'>
        Имя
    </div>
    <div class='col-8 my-auto mb-1'>
        <input class='w-100' placeholder='Аноним' bind:value={threadData.post.user_name}>
    </div>
    <div class='col-4 my-auto'>
        Тема
    </div>
    <div class='col-8 my-auto mb-1'>
        <input class='w-100' placeholder='' bind:value={threadData.thread.title}>
    </div>
    <div class='col-4 my-auto'>
        Контент
    </div>
    <div class='col-8 my-auto mb-1'>
        <textarea class='w-100' placeholder='' bind:value={threadData.post.content} />
    </div>
    <div class='col-4'>
        <button on:click={()=>dispatch('close')}>Закрыть</button>
    </div>
    <div class='col-8 text-center'>
        <button on:click={post}>Отправить</button>
    </div>
</div>