<script lang="ts">
    import { createEventDispatcher } from "svelte"
    export let text: string;
    const dispatch = createEventDispatcher();
</script>

<span class='reference mention' on:click={()=>dispatch('reference', {id: text.replace('>', '')})}>
    {text}
</span>