<script lang="ts">
import { toolbar } from "../Storage/Toolbar";
$: hasThreads = $toolbar.threads.length !== 0;

</script>

<div class='text-center title'>
    Треды
</div>
<div class='content' class:text-center={!hasThreads}>
    {#if !hasThreads}
        Нет отслеживаемых тредов
    {:else}
        {#each $toolbar.threads as thread}
            <div class='toolbar-thread'>
                @{thread.id} — {thread.title}: {thread.post_count - 1}
            </div>
        {/each}
    {/if}
</div>