<script lang="ts">
    import { boards, storeBoard } from "../Storage/Boards";
    import Board from "../Models/Board";
    import Collection from "../Models/Support/Collection";

    export let boardName: string;
    export let prefetched: Board = null;

    let boardStored: boolean = $boards.filter(b => b.short_name === boardName).length !== 0;
    let board: Board = prefetched === null ? null : prefetched;

    if (!boardStored && board === null) {
        Collection.get(
            Board, 
            [{where: 'short_name', is: boardName}],
            ['threads.latestPosts.images', 'threads.firstPost.images']
        ).then(r => {storeBoard(r.first()); grabBoard() });
    } else {
        grabBoard();
    }

    function grabBoard(): void
    {
        if (board !== null) {return;}
        board = $boards.filter(b => b.short_name === boardName)[0];
    }
</script>

{#if board !== null}
    <div class='col-12 board-title text-center mb-2'>
        <span>/{board.short_name}/ — {board.name}</span>
    </div>
{/if}