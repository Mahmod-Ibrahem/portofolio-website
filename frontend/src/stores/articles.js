import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useArticlesStore = defineStore('articles', () => {
    const articles = ref([])
    const article = ref(null)
    const loading = ref(false)
    const error = ref(null)
    const meta = ref({})

    function fetchArticles(params = {}) {
        loading.value = true
        error.value = null
        return api.get('/v1/articles', { params })
            .then((response) => {
                articles.value = response.data.data
                meta.value = response.data.meta || {}
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء جلب المقالات'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function fetchArticle(id) {
        loading.value = true
        error.value = null
        return api.get(`/v1/articles/${id}`)
            .then((response) => {
                article.value = response.data.data
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء جلب المقالة'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function createArticle(formData) {
        loading.value = true
        error.value = null
        return api.post('/articles', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then((response) => {
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                if (err.response?.status === 422) {
                    return { success: false, errors: err.response.data.errors }
                }
                error.value = err.response?.data?.message || 'حدث خطأ أثناء إنشاء المقالة'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function updateArticle(id, formData) {
        loading.value = true
        error.value = null
        return api.post(`/articles/${id}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
            .then((response) => {
                return { success: true, data: response.data.data }
            })
            .catch((err) => {
                if (err.response?.status === 422) {
                    return { success: false, errors: err.response.data.errors }
                }
                error.value = err.response?.data?.message || 'حدث خطأ أثناء تحديث المقالة'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    function deleteArticle(id) {
        loading.value = true
        error.value = null
        return api.delete(`/articles/${id}`)
            .then((response) => {
                articles.value = articles.value.filter(a => a.id !== id)
                return { success: true, message: response.data.message }
            })
            .catch((err) => {
                error.value = err.response?.data?.message || 'حدث خطأ أثناء حذف المقالة'
                return { success: false, message: error.value }
            })
            .finally(() => { loading.value = false })
    }

    return {
        articles, article, loading, error, meta,
        fetchArticles, fetchArticle, createArticle, updateArticle, deleteArticle,
    }
})
