<template>
    <div class="gallery-card" @click="$emit('click', item)">
        <!-- Image -->
        <div class="gallery-card-image">
            <img :src="item.image_url" :alt="item.alt_text || item.title || 'Gallery image'" loading="lazy" />

            <!-- Hover overlay -->
            <div class="gallery-card-overlay">
                <button class="gallery-delete-btn" title="حذف الصورة" @click.stop="$emit('delete', item)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Title -->
        <div v-if="item.title" class="gallery-card-title">
            {{ item.title }}
        </div>
    </div>
</template>

<script setup>
defineProps({
    item: { type: Object, required: true },
})

defineEmits(['click', 'delete'])
</script>

<style scoped>
.gallery-card {
    position: relative;
    border-radius: 1rem;
    overflow: hidden;
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.06);
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.gallery-card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35);
    border-color: rgba(14, 165, 233, 0.25);
}

.gallery-card-image {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
}

.gallery-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.gallery-card:hover .gallery-card-image img {
    transform: scale(1.08);
}

/* Hover overlay */
.gallery-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top,
            rgba(0, 0, 0, 0.7) 0%,
            rgba(0, 0, 0, 0.1) 50%,
            rgba(0, 0, 0, 0) 100%);
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 0.75rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-card:hover .gallery-card-overlay {
    opacity: 1;
}

.gallery-delete-btn {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.85);
    color: white;
    border: none;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.2s;
    box-shadow: 0 2px 10px rgba(239, 68, 68, 0.4);
}

.gallery-delete-btn:hover {
    background: #ef4444;
    transform: scale(1.1);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.6);
}

.gallery-card-title {
    padding: 0.65rem 0.85rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: #cbd5e1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}
</style>
