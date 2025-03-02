<template>
    <v-container>
        <v-row>
            <v-text-field
                v-model="googleSheet"
                :disabled="loading"
                class="ml-4"
                label="Google Sheet"
                density="compact"
                variant="outlined"
                autofocus
            />

            <v-btn
                :loading="loading"
                class="ml-4 mr-4"
                prepend-icon="mdi-content-save"
                @click="setGoogleSheet"
            >
                Save
            </v-btn>
        </v-row>

        <v-row class="mt-4">
            <v-btn
                :loading="loading"
                class="ml-4"
                @click="generate"
            >
                Generate 1000
            </v-btn>

            <v-btn
                :loading="loading"
                class="ml-4"
                @click="clear"
            >
                Clear
            </v-btn>
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
    import {onMounted, ref, Ref} from 'vue'
    import api from '@/api'
    import {MODEL_INFO} from '@/utils/model-info'
    import router from '@/router'

    const modelInfo = MODEL_INFO.DEMO
    const loading: Ref<boolean> = ref(true)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')
    const googleSheet: Ref<string> = ref('')

    async function generate() {
        if (loading.value) {
            return
        }

        loading.value = true
        try {
            await api.post(`${modelInfo.ENDPOINT}/generate`)
            await router.push({name: `${modelInfo.NAME}-list`})
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

    async function clear() {
        if (loading.value) {
            return
        }

        loading.value = true
        try {
            await api.post(`${modelInfo.ENDPOINT}/clear`)
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

    async function setGoogleSheet() {
        if (loading.value) {
            return
        }

        loading.value = true
        try {
            await api.post(`${modelInfo.ENDPOINT}/google-sheet`, {
                url: googleSheet.value,
            })
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

    onMounted(async () => {
        try {
            const {data} = await api.get(`${modelInfo.ENDPOINT}/google-sheet`)
            googleSheet.value = data.url ?? ''
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
