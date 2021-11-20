<script lang="ts">
    import API, { ThreadData } from "../API";
    import {createEventDispatcher} from "svelte";
    import type Board from "../Models/Board";
    import Routing from "../Router/Support/Routing";

    export let board: Board;

    const dispatch = createEventDispatcher();
    const formData: FormData = new FormData();

    let threadData: ThreadData = {
        thread: {
            title: null,
            board_id: board.id,
        }, 
        post: {
            user_name: board.default_username,
            content: null,
            images: [],
        }
    };

    threadData.thread.board_id = board.id;

    function post(): void {
        Object.keys(threadData).forEach(
            key => handleSubData(threadData[key]) 
        );
        API.createThread(formData).then(
            r => r.json().then(json => Routing.go('/'+board.short_name+'/'+json.thread_id))
        )
    }

    function handleSubData(data: object): void {
        Object.keys(data).forEach(key => {
            if (data[key] === null || (Array.isArray(data[key]) && data[key].length === 0)) {return;}
                if (Array.isArray(data[key])) {
                    data[key].forEach(file => {
                        formData.append(key+'[]', file[0]);
                    });
                    return;
                }
                formData.append(key, data[key])
        });
    }

    $:imageCount = threadData.post.images.length < 4 ? threadData.post.images.length + 1 : 4;
    
</script>

<form on:submit|preventDefault={post}>
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
            <input type='submit' value='Отправить'>
        </div>
    </div>
</form>