export interface IModelInfo {
    NAME: string
    PLURAL: string
    ENDPOINT: string
    TABLE: string
    CLASS: string
}

export const MODEL_INFO = {
    DEMO: {
        NAME: 'demo',
        PLURAL: 'demos',
        ENDPOINT: 'demos',
        TABLE: 'demos',
        CLASS: 'App\\Models\\Demo',
    },
} as const

export const modelInfoValues: Array<IModelInfo> = Object.values(MODEL_INFO)

export function getModelInfoByClass(value: string) {
    return modelInfoValues.find(e => e.CLASS === value)
}

export function getModelInfoByName(value: string) {
    return modelInfoValues.find(e => e.NAME === value)
}
