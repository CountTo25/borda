<script lang="ts">
    import API, { PostData, ThreadData } from "../API";
    import {createEventDispatcher} from "svelte";
    import type Board from "../Models/Board";
    import Routing from "../Router/Support/Routing";
    import type Thread from "../Models/Thread";

    export let thread: Thread;

    const dispatch = createEventDispatcher();
    const formData: FormData = new FormData();

    let postData: PostData = {
        content: null,
        user_name: null,
        thread_id: thread.id,
        images: [],
    }
    
    function post(): void 
    {
        Object.keys(postData).forEach(
            key => {
                if (postData[key] === null || (Array.isArray(postData[key]) && postData[key].length === 0)) {console.log(key); return;}
                if (Array.isArray(postData[key])) {
                    postData[key].forEach(file => {
                        formData.append(key+'[]', file[0]);
                    });
                    return;
                }
                formData.append(key, postData[key])}
        );
        
        API.createPost(formData).then(r => dispatch('sent'));
    }

    $:imageCount = postData.images.length < 4 ? postData.images.length + 1 : 4;
    
</script>

<form on:submit|preventDefault={post}>
    <div class='row thread-form-wrap'>
        <div class='col-12 text-center mb-2'>
            Ответ в тред @{thread.id}
        </div>
        <div class='col-4 my-auto'>
            Имя
        </div>
        <div class='col-8 my-auto mb-1'>
            <input class='w-100' placeholder='Аноним' name='user_name' bind:value={postData.user_name}>
        </div>
        <div class='col-4 my-auto'>
            Контент
        </div>
        <div class='col-8 my-auto mb-1'>
            <textarea class='w-100' placeholder='' name='content' bind:value={postData.content} />
        </div>
        
        <div class='col-12 my-auto mb-1'>
            Пикчи (максимум: 3)
        </div>
        {#each Array(imageCount) as image, index}    
        <div class='col-4 my-auto mb-1'/>
        <div class='col-8 my-auto mb-1'>
            <input type='file' class='w-100' bind:files={postData.images[index]}>
        </div>
        {/each}
        <div class='col-4'>
            <button on:click={()=>dispatch('close')}>Закрыть</button>
        </div>
        <div class='col-8 text-center'>
            <input type='submit' value='Отправить'>
        </div>
    </div>
</form>
