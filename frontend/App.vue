<template>
    <v-app>
        <v-app-bar elevation="0">
            <v-app-bar-nav-icon
                @click="drawer = !drawer"
            />
            <img
                :src="logo"
                alt="logo"
                class="ml-4"
                style="height: 64%; width: auto"
            >
        </v-app-bar>

        <v-main>
            <navigation-drawer v-model="drawer"/>

            <router-view/>
        </v-main>
    </v-app>
</template>

<script setup lang="ts">
    import logo from '@/logo.png'
    import {ref, Ref} from 'vue'
    import {useDisplay} from 'vuetify'
    import NavigationDrawer from '@/component/NavigationDrawer.vue'

    const {lgAndUp} = useDisplay()
    const drawer: Ref<boolean> = ref(lgAndUp.value)
</script>
