export function getValidationErrors(e: any): Record<string, Array<string>> {
    const errors = e.response?.data?.errors
    if (!errors) {
        return {}
    }
    return errors
}
