import {Model} from "./Support/Model";

export default class Image extends Model {
    public static route: string = '';
    public static exposes: string[] = [
        'id',
        'name',
        'extension',
        'url',
    ];


    public id: number;
    public name: string;
    public extension: string;
    public url: string;

    public getFilename(): string
    {
        return this.name+'.'+this.extension;    
    }
}