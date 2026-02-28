<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="open" class="gallery-modal-overlay" @click.self="closeModal">
                <div class="gallery-modal-card">
                    <!-- Header -->
                    <div class="gallery-modal-header">
                        <h3 class="gallery-modal-title">
                            {{ isEditing ? 'تعديل الصورة' : 'إضافة صورة جديدة' }}
                        </h3>
                        <button class="gallery-modal-close" @click="closeModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="handleSubmit" class="gallery-modal-form">
                        <!-- Title Input -->
                        <div class="form-group">
                            <label for="gallery-title" class="form-label">العنوان <span
                                    class="required-star">*</span></label>
                            <input id="gallery-title" v-model="form.title" type="text" class="form-input"
                                :class="{ 'input-error': errors.title }" placeholder="أدخل عنوان الصورة"
                                maxlength="255" />
                            <p v-if="errors.title" class="form-error">{{ errors.title }}</p>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label class="form-label">
                                الصورة
                                <span v-if="!isEditing" class="required-star">*</span>
                                <span v-else class="form-hint">(اختياري — لتغيير الصورة)</span>
                            </label>

                            <!-- Image Preview / Drop Zone -->
                            <div class="image-drop-zone"
                                :class="{ 'has-preview': imagePreview, 'drag-over': isDragging }"
                                @click="$refs.fileInput.click()" @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">

                                <!-- Preview -->
                                <template v-if="imagePreview">
                                    <img :src="imagePreview" alt="معاينة الصورة" class="preview-img" />
                                    <div class="preview-overlay">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        <span>تغيير الصورة</span>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <template v-else>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 drop-icon" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <span class="drop-text">اسحب الصورة أو اضغط للاختيار</span>
                                    <span class="drop-hint">JPG, PNG, WEBP — حتى 4MB</span>
                                </template>
                            </div>

                            <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only"
                                @change="handleFileChange" />
                            <p v-if="errors.image" class="form-error">{{ errors.image }}</p>
                        </div>

                        <!-- Progress Bar -->
                        <div v-if="isSubmitting" class="progress-track">
                            <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
                        </div>

                        <!-- Server Error -->
                        <p v-if="serverError" class="form-error server-error">{{ serverError }}</p>

                        <!-- Actions -->
                        <div class="gallery-modal-actions">
                            <button type="button" class="modal-btn cancel" :disabled="isSubmitting" @click="closeModal">
                                إلغاء
                            </button>
                            <button type="submit" class="modal-btn submit" :disabled="isSubmitting">
                                <svg v-if="isSubmitting" class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                </svg>
                                <span>{{ isSubmitting ? 'جاري الحفظ...' : (isEditing ? 'حفظ التعديلات' : 'إضافة الصورة')
                                }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { useGalleryStore } from '../../stores/gallery'

const props = defineProps({
    open: { type: Boolean, default: false },
    editItem: { type: Object, default: null },
})

const emit = defineEmits(['close', 'saved'])

const store = useGalleryStore()

const isEditing = computed(() => !!props.editItem)

const form = reactive({ title: '' })
const selectedFile = ref(null)
const imagePreview = ref(null)
const isDragging = ref(false)
const isSubmitting = ref(false)
const progressPercent = ref(0)
const serverError = ref('')
const errors = reactive({ title: '', image: '' })

// Watch for modal open / editItem changes
watch(() => props.open, (newVal) => {
    if (newVal) {
        resetForm()
        if (props.editItem) {
            form.title = props.editItem.title || ''
            imagePreview.value = props.editItem.image_url || null
        }
    }
})

function resetForm() {
    form.title = ''
    selectedFile.value = null
    imagePreview.value = null
    isDragging.value = false
    isSubmitting.value = false
    progressPercent.value = 0
    serverError.value = ''
    errors.title = ''
    errors.image = ''
}

function closeModal() {
    if (isSubmitting.value) return
    emit('close')
}

function handleFileChange(e) {
    const file = e.target.files?.[0]
    if (file) setFile(file)
}

function handleDrop(e) {
    isDragging.value = false
    const file = e.dataTransfer.files?.[0]
    if (file) setFile(file)
}

function setFile(file) {
    const allowed = ['image/jpeg', 'image/png', 'image/webp']
    if (!allowed.includes(file.type)) {
        errors.image = 'الصيغ المسموحة: JPG, PNG, WEBP'
        return
    }
    if (file.size > 4 * 1024 * 1024) {
        errors.image = 'حجم الصورة لا يجب أن يتجاوز 4 ميجابايت'
        return
    }

    errors.image = ''
    selectedFile.value = file
    imagePreview.value = URL.createObjectURL(file)
}

function validate() {
    let valid = true
    errors.title = ''
    errors.image = ''

    if (!isEditing.value && !selectedFile.value) {
        errors.image = 'الصورة مطلوبة'
        valid = false
    }

    return valid
}

async function handleSubmit() {
    if (!validate()) return

    isSubmitting.value = true
    progressPercent.value = 0
    serverError.value = ''

    const formData = new FormData()
    formData.append('title', form.title.trim())
    if (selectedFile.value) {
        formData.append('image', selectedFile.value)
    }

    const onProgress = (progressEvent) => {
        if (progressEvent.total) {
            progressPercent.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        }
    }

    try {
        if (isEditing.value) {
            await store.updateGallery(props.editItem.id, formData, onProgress)
        } else {
            await store.uploadImage(formData, onProgress)
        }
        emit('saved', isEditing.value ? 'updated' : 'created')
        emit('close')
    } catch (err) {
        const resp = err.response?.data
        if (resp?.errors) {
            const msgs = Object.values(resp.errors).flat()
            serverError.value = msgs.join(' · ')
        } else {
            serverError.value = resp?.message || 'حدث خطأ أثناء الحفظ'
        }
    } finally {
        isSubmitting.value = false
        progressPercent.value = 0
    }
}
</script>

<style scoped>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* ============================================================== */
/*  Overlay                                                        */
/* ============================================================== */
.gallery-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.60);
    backdrop-filter: blur(6px);
    padding: 1rem;
}

/* ============================================================== */
/*  Card                                                           */
/* ============================================================== */
.gallery-modal-card {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1.25rem;
    max-width: 520px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow:
        0 25px 50px rgba(0, 0, 0, 0.5),
        0 0 0 1px rgba(255, 255, 255, 0.05);
    animation: modalPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes modalPop {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(10px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* ============================================================== */
/*  Header                                                         */
/* ============================================================== */
.gallery-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 1.75rem 0;
}

.gallery-modal-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #f1f5f9;
}

.gallery-modal-close {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.06);
    color: #94a3b8;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.gallery-modal-close:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
}

/* ============================================================== */
/*  Form                                                           */
/* ============================================================== */
.gallery-modal-form {
    padding: 1.5rem 1.75rem 1.75rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.form-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #cbd5e1;
}

.required-star {
    color: #f87171;
    margin-right: 2px;
}

.form-hint {
    font-size: 0.8rem;
    font-weight: 400;
    color: #64748b;
    margin-right: 0.35rem;
}

.form-input {
    width: 100%;
    padding: 0.7rem 1rem;
    border-radius: 0.75rem;
    font-size: 0.95rem;
    color: #f1f5f9;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    outline: none;
    transition: all 0.2s;
    font-family: inherit;
}

.form-input::placeholder {
    color: #64748b;
}

.form-input:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
}

.form-input.input-error {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
}

.form-error {
    font-size: 0.82rem;
    color: #f87171;
    line-height: 1.4;
}

.server-error {
    padding: 0.65rem 1rem;
    border-radius: 0.65rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

/* ============================================================== */
/*  Image Drop Zone                                                */
/* ============================================================== */
.image-drop-zone {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 200px;
    border: 2px dashed rgba(255, 255, 255, 0.12);
    border-radius: 1rem;
    cursor: pointer;
    transition: all 0.25s;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.02);
}

.image-drop-zone:hover {
    border-color: rgba(14, 165, 233, 0.4);
    background: rgba(14, 165, 233, 0.04);
}

.image-drop-zone.drag-over {
    border-color: #0ea5e9;
    background: rgba(14, 165, 233, 0.08);
}

.image-drop-zone.has-preview {
    border-style: solid;
    border-color: rgba(255, 255, 255, 0.1);
}

.drop-icon {
    color: #475569;
}

.drop-text {
    font-size: 0.9rem;
    color: #94a3b8;
    font-weight: 500;
}

.drop-hint {
    font-size: 0.78rem;
    color: #64748b;
}

/* Preview */
.preview-img {
    width: 100%;
    height: 100%;
    min-height: 200px;
    max-height: 280px;
    object-fit: cover;
}

.preview-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    opacity: 0;
    transition: opacity 0.25s;
    color: white;
    font-size: 0.9rem;
    font-weight: 500;
}

.image-drop-zone:hover .preview-overlay {
    opacity: 1;
}

/* ============================================================== */
/*  Progress                                                       */
/* ============================================================== */
.progress-track {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 999px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #0ea5e9, #38bdf8);
    border-radius: 999px;
    transition: width 0.3s ease;
}

/* ============================================================== */
/*  Actions                                                        */
/* ============================================================== */
.gallery-modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    padding-top: 0.5rem;
}

.modal-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    font-family: inherit;
}

.modal-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.modal-btn.cancel {
    background: rgba(255, 255, 255, 0.06);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-btn.cancel:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
}

.modal-btn.submit {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
    box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
}

.modal-btn.submit:hover:not(:disabled) {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
    transform: translateY(-1px);
}

/* ============================================================== */
/*  Spinner                                                        */
/* ============================================================== */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 0.8s linear infinite;
}

/* ============================================================== */
/*  Transition                                                     */
/* ============================================================== */
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

/* ============================================================== */
/*  Utility                                                        */
/* ============================================================== */
.w-5 {
    width: 1.25rem;
}

.h-5 {
    height: 1.25rem;
}

.w-6 {
    width: 1.5rem;
}

.h-6 {
    height: 1.5rem;
}

.w-8 {
    width: 2rem;
}

.h-8 {
    height: 2rem;
}

.opacity-25 {
    opacity: 0.25;
}

.opacity-75 {
    opacity: 0.75;
}
</style>
