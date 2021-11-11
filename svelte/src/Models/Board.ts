import {Model} from "./Support/Model";
import Thread from "./Thread";

export default class Board extends Model {
    public static route: string = 'boards';
    public static exposes: string[] = [
        'short_name',
        'name', 
        'id',
        'default_username',
    ];
    public static relations = {
        'threads': {model: Thread, many: true}
    }

    public id: number;
    public short_name: string;
    public name: string;
    public threads: Thread[];
    public default_username: string;
}