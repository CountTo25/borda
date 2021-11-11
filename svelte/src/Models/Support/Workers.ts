import type { Model } from "./Model";
export class UpdateManyWorker {
    private model: Model;
    private id: Number[];
    constructor(model: Model, id: Number[]) {
        this.model = model;
        this.id = id;
    }

    public with(props: object | object[]) {

    }
}