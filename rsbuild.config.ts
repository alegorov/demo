import {defineConfig} from '@rsbuild/core'
import {pluginVue} from '@rsbuild/plugin-vue'
import {pluginEslint} from '@rsbuild/plugin-eslint'
import {pluginSass} from '@rsbuild/plugin-sass'

export default defineConfig({
    source: {
        entry: {
            index: './frontend/index.ts',
        },
    },
    server: {
        proxy: {
            context: ['/api'],
            target: 'http://127.0.0.1:8000',
            ws: false,
        },
        publicDir: false,
    },
    dev: {
        progressBar: true,
    },
    plugins: [
        pluginVue(),
        pluginEslint({
            eslintPluginOptions: {
                extensions: ['.js', '.jsx', '.ts', '.tsx', '.vue'],
                fix: false,
            },
        }),
        pluginSass(),
    ],
})
