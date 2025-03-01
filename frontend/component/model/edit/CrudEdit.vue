<template>
    <v-container>
        <spinner v-if="loading"/>

        <v-row
            v-else
            justify="center"
        >
            <v-card
                class="elevation-12"
                :style="formStyle"
            >
                <v-card-text>
                    <slot
                        v-for="slot in slots"
                        :key="slot"
                        :name="slot"
                    />
                </v-card-text>

                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        :loading="saving"
                        prepend-icon="mdi-content-save"
                        @click="save"
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-row>

        <v-snackbar
            v-model="snackbarError"
            color="red"
        >
            {{ snackbarErrorText }}
        </v-snackbar>
    </v-container>
</template>

<script setup lang="ts">
    import {computed, inject, onMounted, Reactive, ref, Ref, useSlots} from 'vue'
    import {useRoute} from 'vue-router'
    import {useDisplay} from 'vuetify'
    import Spinner from '@/component/Spinner.vue'
    import api from '@/api'
    import {getValidationErrors} from '@/api/errors'
    import {IModelInfo} from '@/utils/model-info'
    import router from '@/router'

    const slots = Object.keys(useSlots())
    const route = useRoute()
    const {xs} = useDisplay()

    const modelInfo = inject<IModelInfo>('modelInfo')!
    const record = inject<Reactive<Record<string, any>>>('crudRecord')!
    const errors = inject<Ref<Record<string, Array<string>>>>('crudErrors')!

    const loading: Ref<boolean> = ref(typeof route.params.id === 'string')
    const saving: Ref<boolean> = ref(false)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')

    const formStyle = computed<object>(() => ({
        width: xs.value ? '95%' : '400px',
        margin: '20px',
    }))

    async function save() {
        if (saving.value) {
            return
        }

        errors.value = {}
        saving.value = true

        try {
            let id: number

            if (typeof route.params.id === 'string') {
                const {data} = await api.post(`${modelInfo.ENDPOINT}/${route.params.id}`, {...record, _method: 'patch'})
                id = data.id
            } else {
                const {data} = await api.post(modelInfo.ENDPOINT, record)
                id = data.id
            }

            await router.push({
                name: `${modelInfo.NAME}-view`,
                params: {id},
            })
        } catch (e) {
            console.error(e)
            if (e.response?.data?.message) {
                snackbarErrorText.value = e.response?.data?.message
            } else {
                snackbarErrorText.value = 'Error'
            }
            snackbarError.value = true
            errors.value = getValidationErrors(e)
        } finally {
            saving.value = false
        }
    }

    onMounted(async () => {
        if (loading.value) {
            try {
                const {data} = await api.get(`${modelInfo.ENDPOINT}/${route.params.id}`)
                Object.assign(record, data)
            } catch (e) {
                console.error(e)
                if (e.response?.data?.message) {
                    snackbarErrorText.value = e.response?.data?.message
                } else {
                    snackbarErrorText.value = 'Error'
                }
                snackbarError.value = true
            } finally {
                loading.value = false
            }
        }
    })
</script>
