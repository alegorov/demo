module.exports = {
    root: true,
    env: {browser: true, es2021: true},
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:vue/vue3-recommended',
    ],
    'rules': {
        '@typescript-eslint/no-explicit-any': 'off',
        'vue/multi-word-component-names': 'off',
        'vue/html-indent': 'off',
        'vue/html-closing-bracket-spacing': 'off',
    },
    ignorePatterns: ['dist', '.eslintrc.cjs'],
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        sourceType: 'module',
    },
}
