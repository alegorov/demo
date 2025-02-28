import {RouteRecordRaw} from 'vue-router'
import demosRoutes from './demos/routes'

export default <Array<RouteRecordRaw>>[
    {
        path: '/catalogs',
        component: () => import('@/component/Nested.vue'),
        children: [
            demosRoutes,
        ],
    },
]
