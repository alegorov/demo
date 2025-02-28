<template>
    <v-navigation-drawer
        v-model="drawer"
        :mobile="xs"
        location="left"
        sticky
    >
        <v-list>
            <v-list-item
                v-for="(route, routeIndex) in allowedRoutes"
                :key="'A' + String(routeIndex)"
                :title="route.title"
                :prepend-icon="route.icon ?? defaultIcon"
                :exact="route.exact || false"
                :to="route.to"
                link
            />
        </v-list>
    </v-navigation-drawer>
</template>

<script setup lang="ts">
    import {IModelInfo, MODEL_INFO} from '@/utils/model-info'
    import {RouteLocationRaw, useRoute} from 'vue-router'
    import {computed, ModelRef, watch} from 'vue'
    import {useDisplay} from 'vuetify'

    const currentRoute = useRoute()
    const {lgAndUp, xs} = useDisplay()

    const drawer: ModelRef<boolean> = defineModel<boolean>({required: true})
    const defaultIcon = 'mdi-selection-ellipse'

    interface INavigationRoute {
        title: string
        icon?: string
        to?: RouteLocationRaw
        exact?: boolean
        info?: IModelInfo
    }

    const catalogsRoutes = computed<Array<INavigationRoute>>(() => makeIndexTableLinks([
        {
            ...MODEL_INFO.DEMO,
            title: 'Demos',
        },
    ]))


    const allowedRoutes = computed<Array<INavigationRoute>>(() => <Array<INavigationRoute>>[
        {
            title: 'Dashboard',
            to: {name: 'start'},
            exact: true,
            icon: 'mdi-view-dashboard',
        },
        ...catalogsRoutes.value,
    ])

    function makeIndexTableLinks(info: Array<IModelInfo & { title: string }>): Array<INavigationRoute> {
        return info.map(modelInfo => <INavigationRoute>({
            title: modelInfo.title,
            to: {name: `${modelInfo.NAME}-list`},
            info: modelInfo,
            icon: modelInfo.icon,
        }))
    }

    watch(
        (): string => currentRoute.path,
        () => {
            if (!lgAndUp.value) {
                drawer.value = false
            }
        },
    )
</script>
