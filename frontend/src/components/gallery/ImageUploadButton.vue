<template>
    <div class="upload-wrapper">
        <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/webp" multiple class="sr-only"
            @change="handleFiles" />

        <button class="upload-btn" :class="{ 'uploading': isUploading }" :disabled="isUploading"
            @click="fileInput?.click()">
            <!-- Upload Icon -->
            <svg v-if="!isUploading" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>

            <!-- Spinner -->
            <svg v-else class="w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>

            <span>{{ isUploading ? `جاري الرفع ${progressPercent}%` : 'إضافة صور' }}</span>
        </button>

        <!-- Progress bar -->
        <div v-if="isUploading" class="progress-track">
            <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
        </div>

        <!-- Error -->
        <p v-if="errorMsg" class="upload-error">{{ errorMsg }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useGalleryStore } from '../../stores/gallery'

const emit = defineEmits(['uploaded'])

const store = useGalleryStore()
const fileInput = ref(null)
const isUploading = ref(false)
const progressPercent = ref(0)
const errorMsg = ref('')

async function handleFiles(e) {
    const files = e.target.files
    if (!files || files.length === 0) return

    // Validate before sending
    const maxSize = 4 * 1024 * 1024
    for (const f of files) {
        if (f.size > maxSize) {
            errorMsg.value = `الملف "${f.name}" يتجاوز 4 ميجابايت`
            return
        }
    }

    errorMsg.value = ''
    isUploading.value = true
    progressPercent.value = 0

    const formData = new FormData()
    if (files.length === 1) {
        formData.append('image', files[0])
    } else {
        for (const f of files) {
            formData.append('images[]', f)
        }
    }

    try {
        const result = await store.uploadImages(formData, (progressEvent) => {
            if (progressEvent.total) {
                progressPercent.value = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            }
        })

        emit('uploaded', result.data)
    } catch (err) {
        const resp = err.response?.data
        if (resp?.errors) {
            // Format validation errors
            const msgs = Object.values(resp.errors).flat()
            errorMsg.value = msgs.join(' · ')
        } else {
            errorMsg.value = resp?.message || 'فشل في رفع الصور'
        }
    } finally {
        isUploading.value = false
        progressPercent.value = 0
        // Clear file input so the same files can be re-selected
        if (fileInput.value) fileInput.value.value = ''
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

.upload-wrapper {
    display: inline-flex;
    flex-direction: column;
    gap: 0.5rem;
}

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.95rem;
    color: white;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    border: none;
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3);
}

.upload-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
    transform: translateY(-1px);
}

.upload-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.upload-btn.uploading {
    background: linear-gradient(135deg, #475569, #334155);
    box-shadow: 0 4px 14px rgba(71, 85, 105, 0.3);
}

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

/* Progress */
.progress-track {
    width: 100%;
    max-width: 220px;
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

/* Error */
.upload-error {
    font-size: 0.85rem;
    color: #f87171;
    max-width: 360px;
    line-height: 1.5;
}
</style>
