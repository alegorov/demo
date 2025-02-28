<template>
    <v-container>
        <v-row>
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
    import {ref, Ref} from 'vue'
    import api from '@/api'
    import {MODEL_INFO} from '@/utils/model-info'
    import router from '@/router'

    const loading: Ref<boolean> = ref(false)
    const snackbarError: Ref<boolean> = ref(false)
    const snackbarErrorText: Ref<string> = ref('')

    async function generate() {
        if (loading.value) {
            return
        }

        loading.value = true
        try {
            await api.post(`/${MODEL_INFO.DEMO.ENDPOINT}/generate`)
            router.push({name: `${MODEL_INFO.DEMO.NAME}-list`})
        } catch (e) {
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
            await api.post(`/${MODEL_INFO.DEMO.ENDPOINT}/clear`)
        } catch (e) {
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
</script>
