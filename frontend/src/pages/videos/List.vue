<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">إدارة الفيديوهات</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">إضافة وتعديل الفيديوهات</p>
            </div>
            <RouterLink :to="{ name: 'videos.create' }" class="btn btn-primary">
                + إضافة فيديو جديد
            </RouterLink>
        </div>

        <!-- Filters -->
        <div class="card p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">بحث</label>
                    <input v-model="filters.search" type="text" class="input" placeholder="ابحث بالعنوان..." />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الحالة</label>
                    <select v-model="filters.is_published" class="input">
                        <option value="">الكل</option>
                        <option value="1">منشور</option>
                        <option value="0">مسودة</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button @click="applyFilters" class="btn btn-primary flex-1">بحث</button>
                    <button @click="clearFilters" class="btn btn-secondary">مسح</button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="store.loading" class="flex items-center justify-center py-12">
            <div class="text-slate-500">جاري تحميل البيانات...</div>
        </div>

        <!-- Table -->
        <div v-else-if="store.videos.length" class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>الرابط</th>
                            <th>الحالة</th>
                            <th>المشاهدات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="video in store.videos" :key="video.id">
                            <td>
                                <img v-if="video.thumbnail" :src="video.thumbnail" :alt="video.title"
                                    class="w-16 h-10 rounded object-cover" />
                                <div v-else
                                    class="w-16 h-10 rounded bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </td>
                            <td class="font-medium">{{ video.title }}</td>
                            <td>
                                <a :href="video.video_url" target="_blank"
                                    class="text-sky-600 hover:underline text-sm truncate block max-w-[200px]">
                                    {{ video.video_url }}
                                </a>
                            </td>
                            <td>
                                <span :class="['badge', video.is_published
                                    ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300'
                                    : 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300']">
                                    {{ video.is_published ? 'منشور' : 'مسودة' }}
                                </span>
                            </td>
                            <td>{{ video.views }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <RouterLink :to="{ name: 'videos.edit', params: { id: video.id } }"
                                        class="text-sky-600 hover:text-sky-800 dark:text-sky-400 transition-colors"
                                        title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </RouterLink>
                                    <button @click="openDeleteModal(video)"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 transition-colors"
                                        title="حذف">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div v-if="store.meta.last_page > 1" class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        صفحة {{ store.meta.current_page }} من {{ store.meta.last_page }} — إجمالي {{ store.meta.total }}
                    </p>
                    <div class="flex gap-2">
                        <button @click="goToPage(store.meta.current_page - 1)" :disabled="store.meta.current_page <= 1"
                            class="btn btn-secondary btn-sm">السابق</button>
                        <button @click="goToPage(store.meta.current_page + 1)"
                            :disabled="store.meta.current_page >= store.meta.last_page"
                            class="btn btn-primary btn-sm">التالي</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="card p-12 text-center">
            <div
                class="w-16 h-16 mx-auto mb-4 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">لا توجد فيديوهات</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-4">ابدأ بإضافة فيديو جديد</p>
            <RouterLink :to="{ name: 'videos.create' }" class="btn btn-primary inline-block">
                + إضافة فيديو جديد
            </RouterLink>
        </div>

        <!-- Delete Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="card p-6 max-w-sm w-full">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">حذف الفيديو</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    هل أنت متأكد من حذف "{{ selectedItem?.title }}"؟ لا يمكن التراجع.
                </p>
                <div class="flex gap-3 justify-end">
                    <button @click="showDeleteModal = false" class="btn btn-secondary">إلغاء</button>
                    <button @click="confirmDelete" class="btn btn-danger">حذف</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useVideosStore } from '../../stores/videos'

const store = useVideosStore()
const filters = ref({ search: '', is_published: '' })
const showDeleteModal = ref(false)
const selectedItem = ref(null)

function loadData(page = 1) {
    const params = { page }
    if (filters.value.search) params.search = filters.value.search
    if (filters.value.is_published !== '') params.is_published = filters.value.is_published
    store.fetchVideos(params)
}

function applyFilters() { loadData() }
function clearFilters() { filters.value = { search: '', is_published: '' }; loadData() }
function goToPage(p) { loadData(p) }

function openDeleteModal(item) { selectedItem.value = item; showDeleteModal.value = true }
function confirmDelete() {
    if (selectedItem.value) {
        store.deleteVideo(selectedItem.value.id).then(res => {
            if (res.success) { showDeleteModal.value = false; selectedItem.value = null }
        })
    }
}

onMounted(() => loadData())
</script>
