import moment from "moment";
import Image from "./Image";
import {Model} from "./Support/Model";

export default class Post extends Model {
    public static route: string = 'service_task';
    public static exposes: string[] = [
        'created_at',
        'id', 
        'content',
        'user_name',
    ];

    public static transformations = {
        'created_at': (created_at) => moment(created_at)
    }

    public static relations = {
        'images': {model: Image, many: true}
    }

    public id: number;
    public content: string;
    public user_name: string;
    public created_at: moment.Moment;
    public images: Image[];
}