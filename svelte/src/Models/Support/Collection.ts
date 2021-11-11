import type {Model} from "./Model";
import api from "./API"
import {buildRequest, FilterParameters} from "./QueryBuilder";

export default class Collection<T extends Model> {
    public source: typeof Model;
    public all: T[];

    public pagination: Pagination;

    constructor(model: typeof Model, data: any[], pagination: Pagination) {
        this.all = data;
        this.pagination = pagination;
        this.source = model;
    }
    
    //@ts-ignore
    public static get(source: typeof Model, filters: FilterParameters[] = [], relations: string[] = []): Promise<Collection> {
        let data =  buildRequest(relations, filters, source);
        return new Promise((resolve, reject) => {
            api.post(source.route+'/list', data).then(response => {
                let data: any = response.data;
                let pagination: Pagination = {
                    current: data.current_page,
                    last: data.last_page,
                    total: data.total,
                };
                let converted: Model[] = [];
                data.data.forEach((element: object) => {
                    let t: Model = new source();
                    t.hydrate(source, element)
                    converted.push(t)
                });

                resolve(new Collection(source, converted, pagination))
            });
        });
    }

    public firstWhere(field: string, value: any): T|null
    {
        let where = this.where(field,value);
        if (where !== null) {return where[0];}
        return null;
    }

    public first(): T|null
    {
        return this.all[0] ?? null 
    }

    public where(field: string, value: any): T[]|null 
    {
        if (!this.source.exposes.includes(field)) {throw ('there is no prop named '+field+' in '+this.source.name+'. Available props: '+this.source.exposes.join(', '))}
        let filtered: T[] = this.all.filter((r: T) => {return r[field] === value});
        if (filtered.length === 0) {return null;}
        return filtered
    }

    public nextPage(): void
    {

    }

    public goToPage(page: Number): void
    {

    }

    private openPage(page: Number): void 
    {

    }

}

type Pagination = {
        current: Number,
        last: Number,
        total: Number,
}

