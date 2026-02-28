<template>
    <div class="books-page">
        <!-- Header -->
        <div class="books-header">
            <div>
                <h1 class="books-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 inline-block" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    الكتب
                </h1>
                <p class="books-subtitle">
                    إدارة الكتب المعروضة
                    <span v-if="meta.total" class="books-count">{{ meta.total }} كتاب</span>
                </p>
            </div>
            <button class="add-btn" @click="openAdd">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>إضافة كتاب</span>
            </button>
        </div>

        <!-- Toast -->
        <Teleport to="body">
            <Transition name="toast">
                <div v-if="toast.show" :class="['toast-notification', toast.type]">
                    <svg v-if="toast.type === 'success'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ toast.message }}</span>
                </div>
            </Transition>
        </Teleport>

        <!-- Loading Skeletons -->
        <div v-if="loading && !books.length" class="books-grid">
            <div v-for="n in 6" :key="n" class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-bar"></div>
            </div>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="state-container">
            <div class="state-icon error">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 class="state-title">حدث خطأ</h3>
            <p class="state-text">{{ error }}</p>
            <button class="retry-btn" @click="fetchBooks()">إعادة المحاولة</button>
        </div>

        <!-- Empty -->
        <div v-else-if="!books.length" class="state-container">
            <div class="state-icon empty">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="state-title">لا توجد كتب بعد</h3>
            <p class="state-text">أضف أول كتاب</p>
            <button class="add-btn" @click="openAdd">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                إضافة كتاب
            </button>
        </div>

        <!-- Books Grid -->
        <div v-else class="books-grid">
            <TransitionGroup name="card">
                <div v-for="book in books" :key="book.id" class="book-card">
                    <div class="book-card__img-wrap">
                        <img v-if="book.image" :src="book.image" alt="" class="book-card__img" />
                        <div v-else class="book-card__placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="book-card__body">
                        <a :href="book.url" target="_blank" rel="noopener" class="book-card__url">{{ book.url }}</a>
                        <div class="book-card__actions">
                            <button class="action-btn edit" @click="openEdit(book)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                </svg>
                            </button>
                            <button class="action-btn delete" @click="openDelete(book)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </TransitionGroup>
        </div>

        <!-- Pagination -->
        <div v-if="meta.last_page > 1 && !loading" class="books-pagination">
            <p class="pagination-info">
                صفحة {{ meta.current_page }} من {{ meta.last_page }} — إجمالي {{ meta.total }}
            </p>
            <div class="pagination-btns">
                <button class="page-btn" :disabled="meta.current_page <= 1" @click="fetchBooks(meta.current_page - 1)">
                    التالي
                </button>
                <button class="page-btn" :disabled="meta.current_page >= meta.last_page"
                    @click="fetchBooks(meta.current_page + 1)">
                    السابق
                </button>
            </div>
        </div>

        <!-- Modal (Add/Edit) -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="modal.open" class="modal-overlay" @click.self="modal.open = false">
                    <div class="modal-card">
                        <div class="modal-header">
                            <h2>{{ modal.editId ? 'تعديل الكتاب' : 'إضافة كتاب جديد' }}</h2>
                            <button class="modal-close" @click="modal.open = false">&times;</button>
                        </div>
                        <form @submit.prevent="submitForm" class="modal-body">
                            <!-- Image Upload -->
                            <div class="form-group">
                                <label class="form-label">صورة الكتاب {{ modal.editId ? '' : '*' }}</label>
                                <div class="upload-area" @click="$refs.fileInput.click()" @dragover.prevent
                                    @drop.prevent="onDrop">
                                    <img v-if="modal.previewUrl" :src="modal.previewUrl" class="upload-preview" />
                                    <div v-else class="upload-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                        </svg>
                                        <span>اضغط أو اسحب الصورة هنا</span>
                                    </div>
                                    <input ref="fileInput" type="file" accept="image/*" class="hidden"
                                        @change="onFileChange" />
                                </div>
                            </div>

                            <!-- URL -->
                            <div class="form-group">
                                <label class="form-label">رابط الكتاب *</label>
                                <input v-model="modal.form.url" type="url" dir="ltr" class="form-input"
                                    placeholder="https://example.com/book" required />
                            </div>

                            <!-- Errors -->
                            <div v-if="modal.errors.length" class="form-errors">
                                <p v-for="(err, i) in modal.errors" :key="i">{{ err }}</p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="cancel-btn" @click="modal.open = false">إلغاء</button>
                                <button type="submit" class="submit-btn" :disabled="modal.saving">
                                    <span v-if="modal.saving" class="btn-spinner"></span>
                                    {{ modal.editId ? 'حفظ التعديلات' : 'إضافة' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Delete Confirm -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="deleteModal.open" class="modal-overlay" @click.self="deleteModal.open = false">
                    <div class="modal-card modal-card--sm">
                        <div class="modal-header">
                            <h2>حذف الكتاب؟</h2>
                            <button class="modal-close" @click="deleteModal.open = false">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p style="margin-bottom:1.5rem;color:#E0D6CC;">
                                هل أنت متأكد من أنك تريد حذف هذا الكتاب؟ لا يمكن التراجع عن هذا الإجراء.
                            </p>
                            <div class="modal-footer">
                                <button class="cancel-btn" @click="deleteModal.open = false">إلغاء</button>
                                <button class="delete-confirm-btn" @click="confirmDelete">حذف</button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '../../services/api'

/* ─── State ─── */
const books = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0 })
const loading = ref(false)
const error = ref('')

/* ─── Toast ─── */
const toast = reactive({ show: false, message: '', type: 'success' })
let toastTimer = null
function showToast(msg, type = 'success') {
    clearTimeout(toastTimer)
    toast.show = true
    toast.message = msg
    toast.type = type
    toastTimer = setTimeout(() => { toast.show = false }, 3500)
}

/* ─── Fetch ─── */
async function fetchBooks(page = 1) {
    loading.value = true
    error.value = ''
    try {
        const res = await api.get('/v1/books', { params: { page, per_page: 12 } })
        books.value = res.data.data
        meta.value = res.data.meta
    } catch {
        error.value = 'فشل في تحميل الكتب'
    } finally {
        loading.value = false
    }
}

/* ─── Modal ─── */
const modal = reactive({
    open: false, editId: null, saving: false,
    form: { url: '' },
    file: null, previewUrl: '', errors: []
})

function openAdd() {
    modal.editId = null
    modal.form.url = ''
    modal.file = null
    modal.previewUrl = ''
    modal.errors = []
    modal.open = true
}

function openEdit(book) {
    modal.editId = book.id
    modal.form.url = book.url
    modal.file = null
    modal.previewUrl = book.image || ''
    modal.errors = []
    modal.open = true
}

function onFileChange(e) {
    const f = e.target.files[0]
    if (!f) return
    modal.file = f
    modal.previewUrl = URL.createObjectURL(f)
}

function onDrop(e) {
    const f = e.dataTransfer.files[0]
    if (!f || !f.type.startsWith('image/')) return
    modal.file = f
    modal.previewUrl = URL.createObjectURL(f)
}

async function submitForm() {
    modal.errors = []
    modal.saving = true
    const fd = new FormData()
    fd.append('url', modal.form.url)
    if (modal.file) fd.append('image', modal.file)

    try {
        if (modal.editId) {
            await api.post(`/books/${modal.editId}`, fd, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            showToast('تم تحديث الكتاب بنجاح ✓')
        } else {
            await api.post('/books', fd, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            showToast('تم إضافة الكتاب بنجاح ✓')
        }
        modal.open = false
        fetchBooks(meta.value.current_page)
    } catch (e) {
        if (e.response?.data?.errors) {
            modal.errors = Object.values(e.response.data.errors).flat()
        } else {
            modal.errors = ['حدث خطأ غير متوقع']
        }
    } finally {
        modal.saving = false
    }
}

/* ─── Delete ─── */
const deleteModal = reactive({ open: false, item: null })

function openDelete(book) {
    deleteModal.item = book
    deleteModal.open = true
}

async function confirmDelete() {
    if (!deleteModal.item) return
    deleteModal.open = false
    try {
        await api.delete(`/books/${deleteModal.item.id}`)
        showToast('تم حذف الكتاب بنجاح ✓')
        fetchBooks(meta.value.current_page)
    } catch {
        showToast('فشل في حذف الكتاب', 'error')
    }
}

onMounted(() => fetchBooks())
</script>

<style scoped>
.books-page {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.books-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.books-title {
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: 1.6rem;
    font-weight: 800;
    color: #f1f5f9;
    letter-spacing: -.02em;
}

.books-subtitle {
    color: #94a3b8;
    font-size: .9rem;
    margin-top: .35rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.books-count {
    display: inline-flex;
    padding: .15rem .6rem;
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 600;
    color: #F2ECE6;
    background: rgba(242, 236, 230, .12);
}

.add-btn {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .7rem 1.5rem;
    border-radius: .75rem;
    font-weight: 600;
    font-size: .95rem;
    color: #1B2A3B;
    background: linear-gradient(135deg, #F2ECE6, #E0D6CC);
    border: none;
    cursor: pointer;
    transition: all .25s;
    box-shadow: 0 4px 14px rgba(242, 236, 230, .25);
    font-family: inherit;
}

.add-btn:hover {
    background: linear-gradient(135deg, #E0D6CC, #c9bdb0);
    box-shadow: 0 6px 20px rgba(242, 236, 230, .35);
    transform: translateY(-1px);
}

/* Grid */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
}

/* Book Card */
.book-card {
    border-radius: 1rem;
    overflow: hidden;
    background: #1e2f42;
    border: 1px solid rgba(242, 236, 230, .06);
    transition: all .3s;
}

.book-card:hover {
    border-color: rgba(242, 236, 230, .15);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, .2);
}

.book-card__img-wrap {
    aspect-ratio: 3/4;
    overflow: hidden;
}

.book-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}

.book-card:hover .book-card__img {
    transform: scale(1.05);
}

.book-card__placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: #24354a;
    color: #E0D6CC;
}

.book-card__body {
    padding: .75rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
}

.book-card__url {
    color: #E0D6CC;
    font-size: .78rem;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 140px;
}

.book-card__url:hover {
    color: #FFFDFB;
}

.book-card__actions {
    display: flex;
    gap: .35rem;
    flex-shrink: 0;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid rgba(242, 236, 230, .1);
    background: rgba(242, 236, 230, .04);
    color: #E0D6CC;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s;
}

.action-btn:hover {
    background: rgba(242, 236, 230, .12);
    color: #FFFDFB;
}

.action-btn.delete:hover {
    background: rgba(239, 68, 68, .15);
    color: #f87171;
    border-color: rgba(239, 68, 68, .2);
}

/* Skeleton */
.skeleton-card {
    border-radius: 1rem;
    overflow: hidden;
    background: #1e2f42;
    border: 1px solid rgba(242, 236, 230, .04);
}

.skeleton-image {
    aspect-ratio: 3/4;
    background: linear-gradient(110deg, #1e2f42 30%, #24354a 50%, #1e2f42 70%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

.skeleton-bar {
    height: 14px;
    margin: .75rem;
    border-radius: 6px;
    width: 60%;
    background: linear-gradient(110deg, #1e2f42 30%, #24354a 50%, #1e2f42 70%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite .2s;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
}

/* States */
.state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    border-radius: 1.25rem;
    background: #1e2f42;
    border: 1px dashed rgba(242, 236, 230, .08);
}

.state-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 1.25rem;
}

.state-icon.empty {
    background: rgba(242, 236, 230, .08);
    color: #F2ECE6;
}

.state-icon.error {
    background: rgba(239, 68, 68, .1);
    color: #f87171;
}

.state-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #FFFDFB;
    margin-bottom: .4rem;
}

.state-text {
    color: #E0D6CC;
    font-size: .9rem;
    margin-bottom: 1.5rem;
}

.retry-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .6rem 1.25rem;
    border-radius: .65rem;
    font-weight: 600;
    font-size: .9rem;
    color: #FFFDFB;
    background: rgba(242, 236, 230, .08);
    border: 1px solid rgba(242, 236, 230, .1);
    cursor: pointer;
    transition: all .2s;
    font-family: inherit;
}

.retry-btn:hover {
    background: rgba(242, 236, 230, .14);
}

/* Pagination */
.books-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2rem;
    padding: 1rem 1.25rem;
    border-radius: 1rem;
    background: #1e2f42;
    border: 1px solid rgba(242, 236, 230, .06);
}

.pagination-info {
    color: #E0D6CC;
    font-size: .85rem;
}

.pagination-btns {
    display: flex;
    gap: .5rem;
}

.page-btn {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .5rem 1rem;
    border-radius: .65rem;
    font-weight: 600;
    font-size: .85rem;
    color: #E0D6CC;
    background: rgba(242, 236, 230, .06);
    border: 1px solid rgba(242, 236, 230, .08);
    cursor: pointer;
    transition: all .2s;
    font-family: inherit;
}

.page-btn:hover:not(:disabled) {
    background: rgba(242, 236, 230, .12);
    color: #FFFDFB;
}

.page-btn:disabled {
    opacity: .35;
    cursor: not-allowed;
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, .6);
    backdrop-filter: blur(6px);
    padding: 1rem;
}

.modal-card {
    width: 100%;
    max-width: 480px;
    background: #1B2A3B;
    border: 1px solid rgba(242, 236, 230, .1);
    border-radius: 1.25rem;
    overflow: hidden;
}

.modal-card--sm {
    max-width: 380px;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(242, 236, 230, .08);
}

.modal-header h2 {
    font-size: 1.15rem;
    font-weight: 700;
    color: #FFFDFB;
}

.modal-close {
    background: none;
    border: none;
    color: #E0D6CC;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal-close:hover {
    color: #FFFDFB;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    padding-top: .5rem;
}

/* Form */
.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: block;
    font-size: .88rem;
    font-weight: 600;
    color: #E0D6CC;
    margin-bottom: .5rem;
}

.form-input {
    width: 100%;
    padding: .75rem 1rem;
    border-radius: .75rem;
    border: 1px solid rgba(242, 236, 230, .12);
    background: rgba(242, 236, 230, .04);
    color: #FFFDFB;
    font-size: .95rem;
    font-family: inherit;
    transition: border-color .2s;
}

.form-input:focus {
    outline: none;
    border-color: #F2ECE6;
}

.form-input::placeholder {
    color: #9d8d7b;
}

.hidden {
    display: none;
}

.upload-area {
    border: 2px dashed rgba(242, 236, 230, .12);
    border-radius: 1rem;
    overflow: hidden;
    cursor: pointer;
    transition: border-color .2s;
    min-height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: rgba(242, 236, 230, .25);
}

.upload-preview {
    width: 100%;
    max-height: 280px;
    object-fit: contain;
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .5rem;
    color: #E0D6CC;
    font-size: .88rem;
    padding: 2rem;
}

.form-errors {
    background: rgba(239, 68, 68, .1);
    border: 1px solid rgba(239, 68, 68, .2);
    border-radius: .75rem;
    padding: .75rem 1rem;
    margin-bottom: 1rem;
}

.form-errors p {
    color: #f87171;
    font-size: .85rem;
    margin: .15rem 0;
}

.cancel-btn {
    padding: .65rem 1.25rem;
    border-radius: .75rem;
    border: 1px solid rgba(242, 236, 230, .12);
    background: transparent;
    color: #E0D6CC;
    font-weight: 600;
    font-size: .9rem;
    cursor: pointer;
    transition: all .2s;
    font-family: inherit;
}

.cancel-btn:hover {
    background: rgba(242, 236, 230, .08);
    color: #FFFDFB;
}

.submit-btn {
    padding: .65rem 1.5rem;
    border-radius: .75rem;
    border: none;
    background: linear-gradient(135deg, #F2ECE6, #E0D6CC);
    color: #1B2A3B;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-family: inherit;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #E0D6CC, #c9bdb0);
}

.submit-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
}

.delete-confirm-btn {
    padding: .65rem 1.5rem;
    border-radius: .75rem;
    border: none;
    background: #dc2626;
    color: #fff;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: all .2s;
    font-family: inherit;
}

.delete-confirm-btn:hover {
    background: #b91c1c;
}

.btn-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(27, 42, 59, .3);
    border-top-color: #1B2A3B;
    border-radius: 50%;
    animation: spin .6s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Toast */
.toast-notification {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99999;
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .75rem 1.5rem;
    border-radius: .75rem;
    font-weight: 600;
    font-size: .9rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .4);
    backdrop-filter: blur(12px);
}

.toast-notification.success {
    background: rgba(16, 185, 129, .9);
    color: #fff;
}

.toast-notification.error {
    background: rgba(239, 68, 68, .9);
    color: #fff;
}

.toast-enter-active,
.toast-leave-active {
    transition: all .35s cubic-bezier(.4, 0, .2, 1);
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(-50%) translateY(20px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(-50%) translateY(20px);
}

/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
    transition: opacity .3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .modal-card {
    animation: modalIn .3s ease forwards;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(.95) translateY(10px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Card TransitionGroup */
.card-enter-active {
    transition: all .4s cubic-bezier(.34, 1.56, .64, 1);
}

.card-leave-active {
    transition: all .3s ease-in;
    position: absolute;
}

.card-enter-from {
    opacity: 0;
    transform: scale(.85) translateY(20px);
}

.card-leave-to {
    opacity: 0;
    transform: scale(.85);
}

.card-move {
    transition: transform .4s ease;
}

@media (max-width: 640px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: .75rem;
    }
}
</style>
