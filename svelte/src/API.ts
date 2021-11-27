import axios from "axios";
import Cookies from "js-cookie";
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

    applyToken: (token: string) => api.post('/api/internal/v1/token/apply', {token}),
    thread: {
        subscribe: (thread_id: number, token: string) => api.post('/api/internal/v1/thread/subscribe/', 
            {thread_id, token: token ?? Cookies.get('LARABA-TOKEN')}
        ),
        unsubscribe: (thread_id: number, token: string) => api.post('/api/internal/v1/thread/unsubscribe/', 
            {thread_id, token: token ?? Cookies.get('LARABA-TOKEN')}
        ),
    }
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