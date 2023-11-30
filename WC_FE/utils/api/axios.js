import Axios from 'axios'
import { localStorageClient } from '../helper/localStorage'
const config = useRuntimeConfig()
// const BE_URL = config.public.BE_URL

const BE_URL = 'http://127.0.0.1:8000/api'

function authRequestInterceptor(config) {
  const token = localStorageClient.getItem('accessToken')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  config.headers.Accept = 'application/json'
  return config
}

export const axios = Axios.create({
  baseURL: BE_URL,
})

axios.interceptors.request.use(authRequestInterceptor)
