import { writable, Writable } from "svelte/store";
import type Board from "../Models/Board";

export const boards: Writable<Board[]> = writable([]);
export const storeBoard: (Board) => void = (board: Board) => boards.update(b => b = [...b, board]);