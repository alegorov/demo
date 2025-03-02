<template>
    <crud-table>
        <template #id="{record}">
            {{ record.id }}
        </template>

        <template #name="{record}">
            {{ record.name }}
        </template>

        <template #email="{record}">
            {{ record.email }}
        </template>

        <template #status="{record}">
            {{ ModelStatus[record.status] }}
        </template>

        <template #created_at="{record}">
            {{ record.created_at }}
        </template>

        <template #updated_at="{record}">
            {{ record.updated_at }}
        </template>
    </crud-table>
</template>

<script setup lang="ts">
    import {provide} from 'vue'
    import CrudTable from '@/component/model/table/CrudTable.vue'
    import {MODEL_INFO} from '@/utils/model-info'
    import {FieldInfo} from '@/component/model/table/FieldInfo'
    import {ModelStatus} from '@/api/types/ModelStatus'

    provide('modelInfo', MODEL_INFO.DEMO)

    provide('tableFields', <Array<FieldInfo>>[
        {name: 'id', title: 'ID'},
        {name: 'name', title: 'Name'},
        {name: 'email', title: 'Email'},
        {name: 'status', title: 'Status'},
        {name: 'created_at', title: 'Created At'},
        {name: 'updated_at', title: 'Updated At'},
    ])
</script>
