<template>
    <v-container>
        <spinner
            v-if="loading"
        />

        <v-table
            v-else
            height="calc(100vh - 120px)"
            fixed-header
            hover
        >
            <thead>
                <tr>
                    <th style="min-width: 120px;"/>

                    <th
                        v-for="field in tableFields"
                        :key="'hdr-' + field.name"
                        class="text-left"
                    >
                        {{ field.title }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="record in records"
                    :key="'rc-' + String(record.id)"
                    class="cursor-pointer"
                    @click="goToView(record)"
                >
                    <td>
                        <v-btn
                            icon="mdi-pencil"
                            size="x-small"
                            @click.stop="goToEdit(record)"
                        />

                        <v-btn
                            icon="mdi-trash-can-outline"
                            size="x-small"
                            color="red"
                            class="ml-2"
                            @click.stop="deleteRecord(record)"
                        />
                    </td>

                    <td
                        v-for="field in tableFields"
                        :key="'rc-' + String(record.id) + '-' + field.name"
                    >
                        <slot
                            :name="field.name"
                            :record="record"
                        />
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-snackbar
            v-model="snackbarError"
            color="red"
        >
            {{ snackbarErrorText }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
    import {inject, onMounted, ref, Ref} from 'vue'
    import {FieldInfo} from '@/component/model/table/FieldInfo'
    import {IModelInfo} from '@/utils/model-info'
    import router from '@/router'
    import Spinner from '@/component/Spinner.vue'
    import api from '@/api'

    const modelInfo = inject<IModelInfo>('modelInfo')!
    const tableFields = inject<Array<FieldInfo>>('tableFields')!

    const loading: Ref<boolean> = ref(false)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')
    const records: Ref<Array<Record<string, any>>> = ref([])

    function goToView(record: Record<string, any>) {
        router.push({
            name: `${modelInfo.NAME}-view`,
            params: {id: record.id},
        })
    }

    function goToEdit(record: Record<string, any>) {
        router.push({
            name: `${modelInfo.NAME}-edit`,
            params: {id: record.id},
        })
    }

    async function fetchRecords() {
        try {
            const {data} = await api.get(`/${modelInfo.ENDPOINT}`)
            records.value = data
        } catch (e) {
            console.error(e)
            if (e.response?.data?.message) {
                snackbarErrorText.value = e.response?.data?.message
            } else {
                snackbarErrorText.value = 'Error'
            }
            snackbarError.value = true
        }
    }

    async function deleteRecord(record: Record<string, any>) {
        try {
            await api.delete(`/${modelInfo.ENDPOINT}/${record.id}`)
            await fetchRecords()
        } catch (e) {
            console.error(e)
            if (e.response?.data?.message) {
                snackbarErrorText.value = e.response?.data?.message
            } else {
                snackbarErrorText.value = 'Error'
            }
            snackbarError.value = true
        }
    }

    onMounted(async () => {
        await fetchRecords()
        loading.value = false
    })

</script>
