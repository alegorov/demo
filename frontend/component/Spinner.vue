<template>
    <v-row
        v-if="block"
        class="align-center justify-center"
    >
        <v-progress-circular indeterminate/>
    </v-row>

    <v-progress-circular
        v-else
        indeterminate
    />
</template>

<script setup lang="ts">
    import {PropType} from 'vue'

    defineProps({
        block: {
            type: Boolean as PropType<boolean>,
            default: false,
        },
    })
</script>
