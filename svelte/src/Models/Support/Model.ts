import api from "./API"
import { UpdateManyWorker } from "./Workers";

export class Model {
    //there is lot of scary code (namely, constructors with empty data)
    //to make end model (Orders for example) as clean and straightforward as possible


    public static route: string;
    public static exposes: string[] = [];
    public static filterable: string[];
    public static fillable: string[];
    public static with: string[];
    public static relations: {[key: string]: {model: typeof Model, many?: boolean}} = {};
    public static transformations: {[key: string]: (value) => any} = {};
    public static computed: {[key: string]: () => any} = {};

    public id: string|number;
    public with: string[];
    public underlying: typeof Model;


    public update(): void {
        api.patch(this.underlying.route, this.toObject()).then((r: any) => {
            this.hydrate(this.underlying, r.data);
        });
    }

    public static updateMany(id: Number[]): UpdateManyWorker {
        let self = new this();
        return new UpdateManyWorker(self, id);
    }

    public static create(type: typeof Model, props): Promise<Model>
    {
        let n: Model = new type();
        return new Promise((resolve, reject) => {
            api.put(type.route, [props]).then(r => {
                n.hydrate(type, r.data[0]);
                resolve(n);
            }).catch(error => {throw(error)});
        });   
    }

    public delete(): Promise<boolean> {
        return new Promise((resolve, reject) => 
        {api.post(this.underlying.route+'/delete', {delete: [this.id]}).then(r => resolve(true)).catch(e =>reject(false))});
    }

    public static deleteMany(id: Number[]): Promise<boolean> 
    {
        return new Promise((resolve, reject) => {
            api.post(this.route+'/delete', {delete: id}).then(r => resolve(true)).catch(r => resolve(false));
        });
    }

    public boot(): void {
        api.get(this.underlying.route+'/'+this.id)
    }

    public toObject(): object {
        let compiled = {};
        this.underlying.exposes.forEach((field: string) => {
            compiled[field] = this[field];
        })
        return compiled;
    }

    public hydrate(model: typeof Model, data) {
        this.underlying = model;
        model.exposes.forEach((field: string) => {
            let fieldValue = data[field];
            if (field in model.transformations) {
                fieldValue = model.transformations[field](fieldValue);
            }
            this[field] = fieldValue;
        })

        Object.keys(model.relations).forEach((relation: string) => {
            if (!(relation in data)) {return}
            if ('many' in model.relations[relation] && model.relations[relation].many) {
                this[relation] = [];
                data[relation].forEach(element => {
                    let m = new model.relations[relation].model();
                    m.hydrate(model.relations[relation].model, element);
                    this[relation].push(m);
                });
            } else {
                this[relation] = new model.relations[relation].model()
                this[relation].hydrate(model.relations[relation].model, data[relation]);
            }
            if (relation in model.transformations) {
                this[relation] = model.transformations[relation](this[relation]);
            }
        })

        Object.keys(model.computed).forEach((computed: string) => {
            this[computed] = model.computed[computed]();
        });
    }
}

type FieldValuePair = {
    field: string,
    value: any,
}