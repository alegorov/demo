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
    import {IModelInfo} from '@/utils/model-info'

    const slots = Object.keys(useSlots())
    const route = useRoute()
    const {xs} = useDisplay()

    const modelInfo = inject<IModelInfo>('modelInfo')!
    const record = inject<Reactive<Record<string, any>>>('crudRecord')!

    const loading: Ref<boolean> = ref(true)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')

    const formStyle = computed<object>(() => ({
        width: xs.value ? '95%' : '400px',
        margin: '20px',
    }))

    onMounted(async () => {
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
    })
</script>
