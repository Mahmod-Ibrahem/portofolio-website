<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ isEdit ? 'تعديل المقالة' : 'إضافة مقالة جديدة' }}</h1>
            <RouterLink :to="{ name: 'articles' }" class="btn btn-secondary">← العودة</RouterLink>
        </div>

        <div class="card p-6">
            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">العنوان *</label>
                    <input v-model="form.title" type="text" class="input" placeholder="عنوان المقالة" />
                    <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title[0] }}</p>
                </div>

                <!-- Link -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الرابط
                        (اختياري)</label>
                    <input v-model="form.link" type="url" class="input" placeholder="https://example.com" />
                    <p v-if="errors.link" class="text-red-500 text-sm mt-1">{{ errors.link[0] }}</p>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">المحتوى *</label>
                    <textarea v-model="form.content" rows="12" class="input"
                        placeholder="اكتب محتوى المقالة هنا..."></textarea>
                    <p v-if="errors.content" class="text-red-500 text-sm mt-1">{{ errors.content[0] }}</p>
                </div>

                <!-- Cover Image -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">صورة الغلاف</label>
                    <input type="file" @change="onFileChange" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                    <p v-if="errors.cover_image" class="text-red-500 text-sm mt-1">{{ errors.cover_image[0] }}</p>
                    <!-- Preview -->
                    <div v-if="imagePreview || (isEdit && form.existingImage)" class="mt-3">
                        <img :src="imagePreview || form.existingImage" alt="معاينة"
                            class="w-48 h-32 rounded-lg object-cover border" />
                    </div>
                </div>

                <!-- Views -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">عدد المشاهدات
                        (اختياري)</label>
                    <input v-model="form.views" type="number" min="0" class="input" placeholder="0" />
                    <p v-if="errors.views" class="text-red-500 text-sm mt-1">{{ errors.views[0] }}</p>
                </div>

                <!-- Published -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_published" class="sr-only peer" />
                        <div
                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sky-300 dark:peer-focus:ring-sky-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-slate-600 peer-checked:bg-sky-600">
                        </div>
                    </label>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">نشر المقالة</span>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="btn btn-primary" :disabled="store.loading">
                        {{ store.loading ? 'جاري الحفظ...' : (isEdit ? 'تحديث' : 'إنشاء') }}
                    </button>
                    <RouterLink :to="{ name: 'articles' }" class="btn btn-secondary">إلغاء</RouterLink>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useArticlesStore } from '../../stores/articles'

const route = useRoute()
const router = useRouter()
const store = useArticlesStore()

const isEdit = computed(() => !!route.params.id)

const form = ref({
    title: '',
    content: '',
    link: '',
    views: 0,
    is_published: false,
    existingImage: null,
})
const coverFile = ref(null)
const imagePreview = ref(null)
const errors = ref({})

function onFileChange(e) {
    const file = e.target.files[0]
    if (file) {
        coverFile.value = file
        imagePreview.value = URL.createObjectURL(file)
    }
}

async function submitForm() {
    errors.value = {}
    const fd = new FormData()
    fd.append('title', form.value.title)
    fd.append('content', form.value.content)
    if (form.value.link) fd.append('link', form.value.link)
    fd.append('views', form.value.views || 0)
    fd.append('is_published', form.value.is_published ? '1' : '0')
    if (coverFile.value) fd.append('cover_image', coverFile.value)

    let result
    if (isEdit.value) {
        result = await store.updateArticle(route.params.id, fd)
    } else {
        result = await store.createArticle(fd)
    }

    if (result.success) {
        router.push({ name: 'articles' })
    } else if (result.errors) {
        errors.value = result.errors
    }
}

onMounted(async () => {
    if (isEdit.value) {
        const res = await store.fetchArticle(route.params.id)
        if (res.success) {
            form.value.title = res.data.title
            form.value.content = res.data.content
            form.value.link = res.data.link || ''
            form.value.views = res.data.views || 0
            form.value.is_published = res.data.is_published
            form.value.existingImage = res.data.cover_image
        }
    }
})
</script>
