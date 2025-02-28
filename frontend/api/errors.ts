import {LaravelResponseInterface} from '@/api/index'

export function getValidationErrors(e: LaravelResponseInterface): Record<string, Array<string>> {
    const errors = e.response?.data?.errors
    if (!errors) {
        return {}
    }
    return errors
}
