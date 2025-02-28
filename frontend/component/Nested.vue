<template>
    <router-view v-slot="{Component}">
        <v-fade-transition mode="out-in">
            <component
                :is="Component"
                v-if="keep && !name"
            />

            <component
                :is="Component"
                v-else
                :key="route.fullPath || name"
            />
        </v-fade-transition>
    </router-view>
</template>

<script setup lang="ts">
    import {PropType} from 'vue'
    import {useRoute} from 'vue-router'

    const route = useRoute()

    defineProps({
        keep: {
            type: Boolean as PropType<boolean>,
            default: true,
        },
        name: {
            type: String as PropType<string>,
            default: '',
        },
    })
</script>
