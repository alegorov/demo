<template>
    <crud-view>
        <template #id>
            <v-text-field
                :model-value="record.id"
                label="ID"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>

        <template #name>
            <v-text-field
                :model-value="record.name"
                label="Name"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>

        <template #email>
            <v-text-field
                :model-value="record.email"
                label="Email"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>

        <template #status>
            <v-text-field
                :model-value="ModelStatus[record.status]"
                label="Status"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>

        <template #created_at>
            <v-text-field
                :model-value="record.created_at"
                label="Created At"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>

        <template #updated_at>
            <v-text-field
                :model-value="record.updated_at"
                label="Updated At"
                readonly
                density="compact"
                variant="outlined"
            />
        </template>
    </crud-view>
</template>

<script setup lang="ts">
    import {provide, Reactive, reactive} from 'vue'
    import CrudView from '@/component/model/view/CrudView.vue'
    import {MODEL_INFO} from '@/utils/model-info'
    import {ModelStatus} from '@/api/types/ModelStatus'
    import IDemo from '@/api/types/IDemo'

    const modelInfo = MODEL_INFO.DEMO
    const record: Reactive<IDemo> = reactive<Record<string, any>>({})

    provide('modelInfo', modelInfo)
    provide('crudRecord', record)
</script>
