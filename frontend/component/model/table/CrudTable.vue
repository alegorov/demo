<template>
    <v-container>
        <spinner v-if="loading"/>

        <template v-else>
            <v-pagination
                :model-value="currentPage"
                :length="pageCount"
                @update:model-value="onPageChange($event)"
            />

            <v-table
                height="calc(100vh - 170px)"
                fixed-header
                hover
            >
                <thead>
                    <tr>
                        <th style="min-width: 120px;">
                            <v-btn
                                prepend-icon="mdi-plus-box-outline"
                                @click="onCreate"
                            >
                                Add
                            </v-btn>
                        </th>

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
        </template>

        <v-snackbar
            v-model="snackbarError"
            color="red"
        >
            {{ snackbarErrorText }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
    import {computed, inject, onMounted, ref, Ref, watch} from 'vue'
    import {useRoute} from 'vue-router'
    import {FieldInfo} from '@/component/model/table/FieldInfo'
    import {IModelInfo} from '@/utils/model-info'
    import router from '@/router'
    import Spinner from '@/component/Spinner.vue'
    import api from '@/api'

    const route = useRoute()

    const modelInfo = inject<IModelInfo>('modelInfo')!
    const tableFields = inject<Array<FieldInfo>>('tableFields')!

    const loading: Ref<boolean> = ref(true)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')
    const records: Ref<Array<Record<string, any>>> = ref([])


    const queryPage = computed<number | null>(() => route.query.page ? Math.max(Number(route.query.page), 1) : null)
    const currentPage: Ref<number> = ref(queryPage.value ?? 1)
    const pageCount: Ref<number> = ref(currentPage.value > 1 ? currentPage.value : 0)
    const pageSize = 10


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

    function onCreate() {
        router.push({name: `${modelInfo.NAME}-create`})
    }

    async function fetchRecords(updatePageCount = true) {
        try {
            if (updatePageCount) {
                const countResponse = await api.get(`${modelInfo.ENDPOINT}/count`)

                const newPageCount = Math.floor((countResponse.data.count + pageSize - 1) / pageSize)

                if (newPageCount < 1) {
                    currentPage.value = 1
                    if (queryPage.value) {
                        await router.push({name: `${modelInfo.NAME}-list`})
                    }
                } else if (currentPage.value > newPageCount) {
                    currentPage.value = newPageCount
                    await router.push({
                        name: `${modelInfo.NAME}-list`,
                        query: {page: currentPage.value},
                    })
                }

                pageCount.value = newPageCount
            }

            const {data} = await api.get(modelInfo.ENDPOINT, {
                params: {
                    offset: (currentPage.value - 1) * pageSize,
                    limit: pageSize,
                },
            })
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
            await api.delete(`${modelInfo.ENDPOINT}/${record.id}`)
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

    function onPageChange(page: number) {
        currentPage.value = page

        router.push({
            name: `${modelInfo.NAME}-list`,
            query: {page},
        })

        fetchRecords(false)
    }

    watch(queryPage, (page) => {
        if (page && page !== currentPage.value) {
            currentPage.value = page
            fetchRecords()
        }
    })

    onMounted(async () => {
        await fetchRecords()
        loading.value = false
    })
</script>
