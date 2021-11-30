import moment from "moment";
import Post from "./Post";
import {Model} from "./Support/Model";

export default class Thread extends Model {
    public static route: string = 'thread';
    public static exposes: string[] = [
        'created_at',
        'id', 
        'title',
        'post_count',
    ]

    public static transformations = {
        'created_at': (created_at: string) => moment(created_at),
        'latest_posts': (latest_posts: Array<Post>) => latest_posts.reverse(),
    }
    
    public static relations = {
        'posts': {model: Post, many: true},
        'first_post': {model: Post},
        'latest_posts': {model: Post, many: true},
    }

    public posts: Post[];
    public latest_posts: Post[];
    public first_post: Post;  
    public id: number;
    public title: string;
    public created_at: moment.Moment;
    public post_count: number;
    //runtime
    public unreadPostCount: number = 0;
}