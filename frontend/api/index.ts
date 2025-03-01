import axios from 'axios'
import qs from 'qs'

const api = axios.create({
    baseURL: '/api/',
    paramsSerializer: params => qs.stringify(params, {charset: 'utf-8', format: 'RFC3986'}),
})

export default api
