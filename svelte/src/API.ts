import axios from "axios";
import env from "./env.js";

const config = {
    baseURL: env.basepath,
    headers: {
      'Content-Type': 'application/json',
    },
  };
  
const api = axios.create(config);

export default {
    listBoards: () => api.get('/api/internal/v1/list/boards'),
    createThread: (data: FormData) => fetch('/api/internal/v1/create/thread', {
        method: 'POST',
        headers: {accept: 'application/json'},
        body: data
    }),

    createPost: (data: FormData) => fetch('/api/internal/v1/create/post', {
        method: 'POST',
        headers: {accept: 'application/json'},
        body: data
    }),
}


export type ThreadData = {
    thread: {
        title?: string,
        board_id: number,
    },
    post: {
        content: string,
        user_name?: string,
        images?: Array<File|any>,
    }
}

export type PostData = {
    content: string,
    thread_id: number,
    user_name?: string,
    images?: Array<File|any>,
}