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
    listBoards: () => api.get('api/internal/v1/list/boards'),
    createThread: (td: ThreadData) => api.post('api/internal/v1/create/thread', {...td}),
    createPost: (data: PostData) => api.post('api/internal/v1/create/post', {...data}),
}


export type ThreadData = {
    thread: {
        title?: string,
        board_id: number,
    },
    post: {
        content: string,
        user_name?: string,
    }
}

export type PostData = {
    content: string,
    thread_id: number,
    user_name?: string,
}