import {Model} from "./Support/Model";

export default class Mention extends Model {
    public static route: string = '';
    public static exposes: string[] = [
        'post_id',
        'mentioned_at_id',
    ];

    public post_id: number;
    public mentioned_at_id: number;
}