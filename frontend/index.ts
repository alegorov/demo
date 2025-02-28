import {createApp} from 'vue'
import App from '@/App.vue'
import router from '@/router'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import 'roboto-fontface/css/roboto/roboto-fontface.css'

export const vuetify = createVuetify({
    theme: {
        defaultTheme: 'light',
    },
    components,
    directives,
})

createApp(App)
    .use(router)
    .use(vuetify)
    .mount('#root')
