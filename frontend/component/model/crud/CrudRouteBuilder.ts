import {RawRouteComponent, RouteRecordRaw} from 'vue-router'
import {IModelInfo} from '@/utils/model-info'

export default class CrudRouteBuilder {
    modelInfo!: IModelInfo
    children: Array<RouteRecordRaw> = []

    constructor(modelInfo: IModelInfo) {
        this.modelInfo = modelInfo
    }

    public static for(modelInfo: IModelInfo) {
        return new CrudRouteBuilder(modelInfo)
    }

    public view(component: RawRouteComponent) {
        this.children.push({
            path: ':id',
            component: component,
            name: `${this.modelInfo.NAME}-view`,
        })

        return this
    }

    public edit(component: RawRouteComponent) {
        this.children.push({
            path: 'edit/:id',
            component: component,
            name: `${this.modelInfo.NAME}-edit`,
        })

        return this
    }

    public create(component: RawRouteComponent) {
        this.children.push({
            path: 'add',
            component: component,
            name: `${this.modelInfo.NAME}-create`,
        })

        return this
    }

    public featureTable(component: RawRouteComponent) {
        this.children.push({
            path: '',
            component: component,
            name: `${this.modelInfo.NAME}-list`,
        })

        return this
    }

    public add(child: RouteRecordRaw) {
        this.children.push(child)
        return this
    }

    public build(): RouteRecordRaw {
        return {
            path: `${this.modelInfo.PLURAL}/`,
            component: () => import('@/component/Nested.vue'),
            children: this.children,
        }
    }
}
