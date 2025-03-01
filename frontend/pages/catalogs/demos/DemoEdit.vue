<template>
    <crud-edit>
        <template #name>
            <v-text-field
                v-model="record.name"
                :error-messages="errors.name"
                label="Name"
                density="compact"
                variant="outlined"
                autofocus
            />
        </template>

        <template #email>
            <v-text-field
                v-model="record.email"
                :error-messages="errors.email"
                label="Email"
                density="compact"
                variant="outlined"
            />
        </template>

        <template #status>
            <v-select
                v-model="record.status"
                :items="statusItems"
                :error-messages="errors.status"
                label="Status"
                density="compact"
                variant="outlined"
            />
        </template>
    </crud-edit>
</template>

<script setup lang="ts">
    import {computed, provide, Reactive, reactive, ref, Ref} from 'vue'
    import CrudEdit from '@/component/model/edit/CrudEdit.vue'
    import {MODEL_INFO} from '@/utils/model-info'
    import {ModelStatus} from '@/component/model/ModelStatus'

    const record: Reactive<Record<string, any>> = reactive<Record<string, any>>({})
    const errors: Ref<Record<string, Array<string>>> = ref({})

    provide('modelInfo', MODEL_INFO.DEMO)
    provide('crudRecord', record)
    provide('crudErrors', errors)

    const statusItems = computed<Array<{ title: string, value: number }>>(() => {
        return Object.entries(ModelStatus)
            .map(([k, v]) => ({title: k, value: v}))
            .filter(e => typeof e.value === 'number')
    })
</script>
