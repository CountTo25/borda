import moment from "moment";
import Image from "./Image";
import Mention from "./Mention";
import {Model} from "./Support/Model";

export default class Post extends Model {
    public static route: string = 'service_task';
    public static exposes: string[] = [
        'created_at',
        'id', 
        'content',
        'user_name',
        'own'
    ];

    public static transformations = {
        'created_at': (created_at) => moment(created_at),
        'content': (content) => this.wrapContent(content),
    }

    public static relations = {
        'images': {model: Image, many: true},
        'mentions': {model: Mention, many: true},
    }

    public id: number;
    public content: WrappedContent[];
    public user_name: string;
    public created_at: moment.Moment;
    public images: Image[];
    public mentions: Mention[];
    public own: boolean;

    private static wrapContent(content: string): WrappedContent[]
    {
        if (content === null) {return []};
        const regex = />[\d]*/gm;
        const exploded = [...content.matchAll(regex)].flat();

        //@ts-ignore
        exploded.forEach(el => {content = content.replace(el, `||${el}||`)})
        let arr = content.split('||');
        let toReturn: WrappedContent[] = arr.map(node => {
            return {
                mode: node.match(regex) === null ? 'plain' : 'reference',
                text: node
            }
        });
    
        return toReturn;
    }


}


type WrappedContent = {
    mode: 'plain'|'reference',
    text: string
}