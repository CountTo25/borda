import type {Model} from "./Model";

export function buildRequest(relations: string[], params: FilterParameters[], target: typeof Model): object
{
    let filters = [];
    params.forEach(p => {
        console.warn('???');
        console.log(p);
        filters.push({
            field: p.where,
            value: p.is,
        })
    })
    return {
        with: relations,
        filters,
    };
}


export type FilterParameters = {
    where: string;
    operator?: ComparisionOperator;
    is: string;
}

type ComparisionOperator = '<'|'>'|'<='|'>='|'='|'!=';