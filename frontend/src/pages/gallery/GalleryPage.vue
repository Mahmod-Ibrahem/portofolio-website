<template>
    <div class="gallery-page">
        <!-- Header -->
        <div class="gallery-header">
            <div>
                <h1 class="gallery-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 inline-block" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                    </svg>
                    معرض الصور
                </h1>
                <p class="gallery-subtitle">
                    إدارة وعرض صور المعرض
                    <span v-if="store.meta.total" class="gallery-count">{{ store.meta.total }} صورة</span>
                </p>
            </div>
            <ImageUploadButton @uploaded="onUploaded" />
        </div>

        <!-- Toast Notifications -->
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

        <!-- Skeleton Loader -->
        <div v-if="store.loading && !store.galleries.length" class="gallery-grid">
            <div v-for="n in 8" :key="n" class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-title"></div>
            </div>
        </div>

        <!-- Error State -->
        <div v-else-if="store.error" class="state-container">
            <div class="state-icon error">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 class="state-title">حدث خطأ</h3>
            <p class="state-text">{{ store.error }}</p>
            <button class="retry-btn" @click="loadData()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                إعادة المحاولة
            </button>
        </div>

        <!-- Empty State -->
        <div v-else-if="!store.galleries.length" class="state-container">
            <div class="state-icon empty">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                </svg>
            </div>
            <h3 class="state-title">لا توجد صور بعد</h3>
            <p class="state-text">أضف أول صورة لمعرض الصور الخاص بك</p>
            <ImageUploadButton @uploaded="onUploaded" />
        </div>

        <!-- Gallery Grid -->
        <div v-else class="gallery-grid">
            <TransitionGroup name="card">
                <GalleryCard v-for="item in store.galleries" :key="item.id" :item="item" @click="onCardClick"
                    @delete="onDeleteClick" />
            </TransitionGroup>
        </div>

        <!-- Pagination -->
        <div v-if="store.meta.last_page > 1 && !store.loading" class="gallery-pagination">
            <p class="pagination-info">
                صفحة {{ store.meta.current_page }} من {{ store.meta.last_page }} — إجمالي {{ store.meta.total }}
            </p>
            <div class="pagination-btns">
                <button class="page-btn" :disabled="store.meta.current_page <= 1"
                    @click="loadData(store.meta.current_page - 1)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                    التالي
                </button>
                <button class="page-btn" :disabled="store.meta.current_page >= store.meta.last_page"
                    @click="loadData(store.meta.current_page + 1)">
                    السابق
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Confirm Delete Modal -->
        <ConfirmModal :open="deleteModal.open" title="حذف الصورة؟"
            message="هل أنت متأكد من أنك تريد حذف هذه الصورة؟ لا يمكن التراجع عن هذا الإجراء." @confirm="confirmDelete"
            @cancel="deleteModal.open = false" />

        <!-- Lightbox -->
        <Teleport to="body">
            <Transition name="lightbox">
                <div v-if="lightbox.open" class="lightbox-overlay" @click.self="lightbox.open = false">
                    <button class="lightbox-close" @click="lightbox.open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <img :src="lightbox.url" :alt="lightbox.alt" class="lightbox-img" />
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useGalleryStore } from '../../stores/gallery'
import GalleryCard from '../../components/gallery/GalleryCard.vue'
import ConfirmModal from '../../components/gallery/ConfirmModal.vue'
import ImageUploadButton from '../../components/gallery/ImageUploadButton.vue'

const store = useGalleryStore()

/* ------------------------------------------------------------------ */
/*  Toast                                                              */
/* ------------------------------------------------------------------ */
const toast = reactive({ show: false, message: '', type: 'success' })
let toastTimer = null

function showToast(message, type = 'success') {
    clearTimeout(toastTimer)
    toast.show = true
    toast.message = message
    toast.type = type
    toastTimer = setTimeout(() => { toast.show = false }, 3500)
}

/* ------------------------------------------------------------------ */
/*  Fetch                                                              */
/* ------------------------------------------------------------------ */
function loadData(page = 1) {
    store.fetchGalleries({ page }).catch(() => { })
}

/* ------------------------------------------------------------------ */
/*  Upload callback                                                    */
/* ------------------------------------------------------------------ */
function onUploaded(newItems) {
    showToast('تم رفع الصور بنجاح ✓')
}

/* ------------------------------------------------------------------ */
/*  Lightbox                                                           */
/* ------------------------------------------------------------------ */
const lightbox = reactive({ open: false, url: '', alt: '' })

function onCardClick(item) {
    lightbox.url = item.image_url
    lightbox.alt = item.alt_text || item.title || ''
    lightbox.open = true
}

/* ------------------------------------------------------------------ */
/*  Delete                                                             */
/* ------------------------------------------------------------------ */
const deleteModal = reactive({ open: false, item: null })

function onDeleteClick(item) {
    deleteModal.item = item
    deleteModal.open = true
}

async function confirmDelete() {
    if (!deleteModal.item) return
    const id = deleteModal.item.id
    deleteModal.open = false

    try {
        await store.deleteGallery(id)
        showToast('تم حذف الصورة بنجاح ✓')
    } catch {
        showToast('فشل في حذف الصورة', 'error')
        // re-fetch to restore consistency
        loadData(store.meta.current_page)
    }
}

/* ------------------------------------------------------------------ */
/*  Init                                                               */
/* ------------------------------------------------------------------ */
onMounted(() => loadData())
</script>

<style scoped>
/* ============================================================== */
/*  Page Layout                                                    */
/* ============================================================== */
.gallery-page {
    max-width: 1400px;
    margin: 0 auto;
}

/* ============================================================== */
/*  Header                                                         */
/* ============================================================== */
.gallery-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}

.gallery-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.6rem;
    font-weight: 800;
    color: #f1f5f9;
    letter-spacing: -0.02em;
}

.gallery-subtitle {
    color: #94a3b8;
    font-size: 0.9rem;
    margin-top: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.gallery-count {
    display: inline-flex;
    align-items: center;
    padding: 0.15rem 0.6rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #38bdf8;
    background: rgba(14, 165, 233, 0.12);
}

/* ============================================================== */
/*  Grid                                                           */
/* ============================================================== */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.25rem;
}

@media (max-width: 640px) {
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }
}

/* ============================================================== */
/*  Skeleton Loader                                                */
/* ============================================================== */
.skeleton-card {
    border-radius: 1rem;
    overflow: hidden;
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.skeleton-image {
    aspect-ratio: 4 / 3;
    background: linear-gradient(110deg, #1e293b 30%, #334155 50%, #1e293b 70%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

.skeleton-title {
    height: 14px;
    margin: 0.75rem;
    border-radius: 6px;
    width: 60%;
    background: linear-gradient(110deg, #1e293b 30%, #334155 50%, #1e293b 70%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite 0.2s;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
}

/* ============================================================== */
/*  States (Empty / Error)                                         */
/* ============================================================== */
.state-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
    border-radius: 1.25rem;
    background: #1e293b;
    border: 1px dashed rgba(255, 255, 255, 0.08);
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
    background: rgba(14, 165, 233, 0.1);
    color: #38bdf8;
}

.state-icon.error {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
}

.state-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #f1f5f9;
    margin-bottom: 0.4rem;
}

.state-text {
    color: #94a3b8;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.retry-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.25rem;
    border-radius: 0.65rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: #f1f5f9;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    cursor: pointer;
    transition: all 0.2s;
}

.retry-btn:hover {
    background: rgba(255, 255, 255, 0.14);
}

/* ============================================================== */
/*  Pagination                                                     */
/* ============================================================== */
.gallery-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2rem;
    padding: 1rem 1.25rem;
    border-radius: 1rem;
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.06);
}

.pagination-info {
    color: #94a3b8;
    font-size: 0.85rem;
}

.pagination-btns {
    display: flex;
    gap: 0.5rem;
}

.page-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.5rem 1rem;
    border-radius: 0.65rem;
    font-weight: 600;
    font-size: 0.85rem;
    color: #cbd5e1;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    cursor: pointer;
    transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
}

.page-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

/* ============================================================== */
/*  Toast                                                          */
/* ============================================================== */
.toast-notification {
    position: fixed;
    bottom: 1.5rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99999;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(12px);
}

.toast-notification.success {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.toast-notification.error {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(-50%) translateY(20px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(-50%) translateY(20px);
}

/* ============================================================== */
/*  Card TransitionGroup                                           */
/* ============================================================== */
.card-enter-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.card-leave-active {
    transition: all 0.3s ease-in;
    position: absolute;
}

.card-enter-from {
    opacity: 0;
    transform: scale(0.85) translateY(20px);
}

.card-leave-to {
    opacity: 0;
    transform: scale(0.85);
}

.card-move {
    transition: transform 0.4s ease;
}

/* ============================================================== */
/*  Lightbox                                                       */
/* ============================================================== */
.lightbox-overlay {
    position: fixed;
    inset: 0;
    z-index: 99998;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(12px);
    padding: 2rem;
}

.lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    z-index: 2;
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.lightbox-img {
    max-width: 90vw;
    max-height: 85vh;
    border-radius: 0.75rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    object-fit: contain;
}

.lightbox-enter-active,
.lightbox-leave-active {
    transition: opacity 0.3s ease;
}

.lightbox-enter-from,
.lightbox-leave-to {
    opacity: 0;
}
</style>
