import axios from 'axios'
import JsonFormData from 'json-form-data'
import qs from 'qs'

export type HttpMethodToken = 'get' | 'post' | 'patch' | 'delete'

export type ResponseData = {
    data: {
        errors?: Record<string, Array<string>>
        message?: string
        token?: string
        any?: any
    }
}

export interface LaravelResponseInterface {
    response: ResponseData
}

export function isLaravelResponse(e: any): e is LaravelResponseInterface {
    return (
        typeof e === 'object' &&
        e?.response?.data &&
        typeof e?.response?.data === 'object'
    )
}

const client = axios.create({
    baseURL: '/api/',
    paramsSerializer: params => qs.stringify(params, {charset: 'utf-8', format: 'RFC3986'}),
})

export default client

export function formData(data: any, httpMethodToken?: HttpMethodToken): FormData {
    const postData = httpMethodToken ? {...data, _method: httpMethodToken} : data
    return JsonFormData(postData, {includeNullValues: true, showLeafArrayIndexes: true})
}
