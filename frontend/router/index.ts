import {
    createRouter,
    createWebHistory,
    RouteRecordRaw,
} from 'vue-router'

import catalogsRoutes from '@/pages/catalogs/routes'

const routes: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'start',
        component: () => import('@/pages/dashboard/Dashboard.vue'),
    },
    ...catalogsRoutes,
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
