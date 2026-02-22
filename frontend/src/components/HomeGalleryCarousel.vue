<template>
    <section class="gallery-section" id="gallery" aria-labelledby="gallery-heading">
        <div class="gallery-bg-accent" aria-hidden="true"></div>

        <div class="gallery-container">
            <!-- Section Header -->
            <div class="gallery-section-header reveal">
                <span class="gallery-section-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                    </svg>
                </span>
                <h2 id="gallery-heading" class="gallery-section-title">معرض الصور</h2>
                <span class="gallery-section-line" aria-hidden="true"></span>
            </div>
            <p class="gallery-section-subtitle reveal">لقطات مختارة من الأنشطة والفعاليات</p>

            <!-- Skeleton Loader -->
            <div v-if="loading" class="gallery-skeleton-row">
                <div v-for="n in 4" :key="n" class="gallery-skeleton-card">
                    <div class="skeleton-shimmer"></div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="gallery-state">
                <div class="gallery-state-icon gallery-state-icon--error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <p class="gallery-state-text">فشل في تحميل الصور</p>
                <button class="gallery-retry-btn" @click="fetchGallery">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                    </svg>
                    إعادة المحاولة
                </button>
            </div>

            <!-- Empty State -->
            <div v-else-if="!images.length" class="gallery-state">
                <div class="gallery-state-icon gallery-state-icon--empty">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                    </svg>
                </div>
                <p class="gallery-state-text">لا توجد صور بعد</p>
            </div>
            <!-- Swiper Carousel -->
            <div v-else ref="swiperWrapRef" class="gallery-swiper-wrap gallery-fade-in">
                <Swiper :modules="swiperModules" :slides-per-view="1" :space-between="20" :loop="images.length > 3"
                    :autoplay="{ delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true }"
                    :pagination="{ clickable: true, el: '.gallery-pagination-dots' }"
                    :navigation="{ nextEl: '.gallery-nav-next', prevEl: '.gallery-nav-prev' }" :breakpoints="{
                        640: { slidesPerView: 2, spaceBetween: 20 },
                        1024: { slidesPerView: 3, spaceBetween: 24 },
                        1280: { slidesPerView: 4, spaceBetween: 24 },
                    }" :grab-cursor="true" class="gallery-swiper">
                    <SwiperSlide v-for="gallery in images" :key="gallery.id">

                        <div class="gallery-slide" @click="openLightbox(gallery)">
                            <div class="gallery-slide-img-wrap">
                                <img :src="gallery.image_url"
                                    :alt="gallery.alt_text || gallery.title || 'صورة من المعرض'" loading="lazy"
                                    class="gallery-slide-img" />
                                <div class="swiper-lazy-preloader"></div>
                            </div>

                            <!-- Hover overlay -->
                            <div class="gallery-slide-overlay">
                                <span class="gallery-slide-view-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </span>
                                <span v-if="gallery.title" class="gallery-slide-title">{{ gallery.title }}</span>
                            </div>
                        </div>
                    </SwiperSlide>
                </Swiper>

                <!-- Custom navigation -->
                <button class="gallery-nav gallery-nav-prev" aria-label="السابق">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
                <button class="gallery-nav gallery-nav-next" aria-label="التالي">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <!-- Pagination dots -->
                <div class="gallery-pagination-dots"></div>
            </div>
        </div>

        <!-- ════════ LIGHTBOX ════════ -->
        <Teleport to="body">
            <Transition name="lb">
                <div v-if="lightbox.open" class="lb-overlay" @click.self="closeLightbox" @keydown.esc="closeLightbox"
                    tabindex="0" ref="lbRef">
                    <!-- Close -->
                    <button class="lb-close" @click="closeLightbox" aria-label="إغلاق">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Prev -->
                    <button v-if="images.length > 1" class="lb-arrow lb-arrow--prev" @click.stop="lbPrev"
                        aria-label="السابق">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>

                    <!-- Image -->
                    <Transition :name="lbDirection" mode="out-in">
                        <img :key="lightbox.index" :src="images[lightbox.index]?.image_url"
                            :alt="images[lightbox.index]?.alt_text || images[lightbox.index]?.title || ''"
                            class="lb-img" />
                    </Transition>

                    <!-- Next -->
                    <button v-if="images.length > 1" class="lb-arrow lb-arrow--next" @click.stop="lbNext"
                        aria-label="التالي">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <!-- Title -->
                    <div v-if="images[lightbox.index]?.title" class="lb-caption">
                        {{ images[lightbox.index].title }}
                    </div>

                    <!-- Counter -->
                    <div class="lb-counter" dir="ltr">
                        {{ lightbox.index + 1 }} / {{ images.length }}
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, nextTick, watch } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import api from '../services/api'

import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/navigation'

const swiperModules = [Autoplay, Pagination, Navigation]

/* ── Data ── */
const images = ref([])
const loading = ref(true)
const error = ref(false)

async function fetchGallery() {
    loading.value = true
    error.value = false
    try {
        const { data } = await api.get('/v1/galleries', {
            params: { is_active: 1, sort: 'sort_order', per_page: 20 }
        })
        images.value = data.data || []
    } catch {
        error.value = true
    } finally {
        loading.value = false
        // After data loads, set up reveal observer for the swiper wrap
        await nextTick()
        initRevealObserver()
    }
}

/* ── Self-managed Intersection Observer for reveal ── */
const swiperWrapRef = ref(null)
let revealObserver = null

function initRevealObserver() {
    // Observe all .reveal and .gallery-fade-in elements inside this component's root
    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible')
                    revealObserver.unobserve(e.target)
                }
            })
        },
        { threshold: 0.1 }
    )
    // Observe the swiper wrap specifically
    if (swiperWrapRef.value) {
        revealObserver.observe(swiperWrapRef.value)
    }
    // Also observe section header/subtitle reveals
    const sectionEl = swiperWrapRef.value?.closest('.gallery-section')
    if (sectionEl) {
        sectionEl.querySelectorAll('.reveal').forEach(el => {
            if (!el.classList.contains('is-visible')) {
                revealObserver.observe(el)
            }
        })
    }
}

/* ── Lightbox ── */
const lightbox = reactive({ open: false, index: 0 })
const lbDirection = ref('lb-slide-left')
const lbRef = ref(null)

function openLightbox(item) {
    const idx = images.value.findIndex(i => i.id === item.id)
    lightbox.index = idx >= 0 ? idx : 0
    lightbox.open = true
    nextTick(() => lbRef.value?.focus())
}

function closeLightbox() {
    lightbox.open = false
}

function lbNext() {
    lbDirection.value = 'lb-slide-left'
    lightbox.index = (lightbox.index + 1) % images.value.length
}

function lbPrev() {
    lbDirection.value = 'lb-slide-right'
    lightbox.index = (lightbox.index - 1 + images.value.length) % images.value.length
}

function onKeydown(e) {
    if (!lightbox.open) return
    if (e.key === 'Escape') closeLightbox()
    else if (e.key === 'ArrowLeft') lbNext()  // RTL: left = next
    else if (e.key === 'ArrowRight') lbPrev()
}

// Toggle body scroll when lightbox is open
watch(() => lightbox.open, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

onMounted(() => {
    fetchGallery()
    window.addEventListener('keydown', onKeydown)
})

onUnmounted(() => {
    window.removeEventListener('keydown', onKeydown)
    revealObserver?.disconnect()
    document.body.style.overflow = ''
})
</script>

<style scoped>
/* ══════════════════════════════════════ */
/*  SECTION LAYOUT                       */
/* ══════════════════════════════════════ */
.gallery-section {
    position: relative;
    padding: 6rem 0 5rem;
    background: #0d0d0d;
    overflow: hidden;
}

.gallery-bg-accent {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .02) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, .015) 0%, transparent 50%);
    pointer-events: none;
}

.gallery-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 1;
}

/* ══════════════════════════════════════ */
/*  SECTION HEADER                       */
/* ══════════════════════════════════════ */
.gallery-section-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin-bottom: .6rem;
}

.gallery-section-icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #ccc;
}

.gallery-section-icon svg {
    width: 22px;
    height: 22px;
}

.gallery-section-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #f1f1f1;
    letter-spacing: -.02em;
}

.gallery-section-line {
    flex: 1;
    max-width: 120px;
    height: 1px;
    background: linear-gradient(90deg, rgba(255, 255, 255, .15), transparent);
}

.gallery-section-subtitle {
    text-align: center;
    color: #888;
    font-size: .95rem;
    margin-bottom: 2.5rem;
}

/* ══════════════════════════════════════ */
/*  SKELETON LOADER                      */
/* ══════════════════════════════════════ */
.gallery-skeleton-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 1024px) {
    .gallery-skeleton-row {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    .gallery-skeleton-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

.gallery-skeleton-card {
    aspect-ratio: 4 / 3;
    border-radius: 14px;
    overflow: hidden;
    background: #1a1a1a;
    border: 1px solid rgba(255, 255, 255, .04);
}

.skeleton-shimmer {
    width: 100%;
    height: 100%;
    background: linear-gradient(110deg, #1a1a1a 30%, #252525 50%, #1a1a1a 70%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% {
        background-position: 200% 0;
    }

    100% {
        background-position: -200% 0;
    }
}

/* ══════════════════════════════════════ */
/*  STATES (Empty / Error)               */
/* ══════════════════════════════════════ */
.gallery-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4rem 2rem;
    text-align: center;
}

.gallery-state-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 1.25rem;
}

.gallery-state-icon--empty {
    background: rgba(255, 255, 255, .04);
    color: #555;
}

.gallery-state-icon--error {
    background: rgba(239, 68, 68, .08);
    color: #f87171;
}

.gallery-state-text {
    color: #777;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.gallery-retry-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .55rem 1.2rem;
    border-radius: .6rem;
    font-weight: 600;
    font-size: .88rem;
    color: #ccc;
    background: rgba(255, 255, 255, .07);
    border: 1px solid rgba(255, 255, 255, .1);
    cursor: pointer;
    transition: all .2s;
}

.gallery-retry-btn:hover {
    background: rgba(255, 255, 255, .12);
    color: #fff;
}

/* ══════════════════════════════════════ */
/*  SWIPER WRAPPER                       */
/* ══════════════════════════════════════ */
.gallery-swiper-wrap {
    position: relative;
    padding: 0 0 50px;
}

.gallery-swiper {
    overflow: hidden;
    border-radius: 16px;
}

/* ══════════════════════════════════════ */
/*  SLIDE CARD                           */
/* ══════════════════════════════════════ */
.gallery-slide {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    cursor: pointer;
    background: #181818;
    border: 1px solid rgba(255, 255, 255, .05);
    transition: transform .4s cubic-bezier(.4, 0, .2, 1), box-shadow .4s ease;
}

.gallery-slide:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 40px rgba(0, 0, 0, .4);
}

.gallery-slide-img-wrap {
    position: relative;
    aspect-ratio: 4 / 3;
    overflow: hidden;
}

.gallery-slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .55s cubic-bezier(.4, 0, .2, 1);
}

.gallery-slide:hover .gallery-slide-img {
    transform: scale(1.08);
}

/* Overlay */
.gallery-slide-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    background: linear-gradient(to top,
            rgba(0, 0, 0, .75) 0%,
            rgba(0, 0, 0, .25) 40%,
            rgba(0, 0, 0, 0) 100%);
    opacity: 0;
    transition: opacity .35s ease;
}

.gallery-slide:hover .gallery-slide-overlay {
    opacity: 1;
}

.gallery-slide-view-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .12);
    backdrop-filter: blur(8px);
    color: #fff;
    transition: transform .3s, background .3s;
}

.gallery-slide:hover .gallery-slide-view-icon {
    transform: scale(1.05);
    background: rgba(255, 255, 255, .2);
}

.gallery-slide-title {
    color: #f1f1f1;
    font-size: .9rem;
    font-weight: 600;
    text-shadow: 0 1px 8px rgba(0, 0, 0, .5);
    max-width: 90%;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ══════════════════════════════════════ */
/*  CUSTOM NAVIGATION                    */
/* ══════════════════════════════════════ */
.gallery-nav {
    position: absolute;
    top: calc(50% - 25px);
    z-index: 10;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    cursor: pointer;
    transition: all .25s;
}

.gallery-nav:hover {
    background: rgba(255, 255, 255, .16);
    color: #fff;
    transform: scale(1.08);
}

.gallery-nav-prev {
    right: -18px;
}

.gallery-nav-next {
    left: -18px;
}

@media (max-width: 1024px) {
    .gallery-nav-prev {
        right: 4px;
    }

    .gallery-nav-next {
        left: 4px;
    }
}

/* ══════════════════════════════════════ */
/*  PAGINATION DOTS                      */
/* ══════════════════════════════════════ */
.gallery-pagination-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 1.5rem;
}

/* Override Swiper defaults */
:deep(.swiper-pagination-bullet) {
    width: 8px;
    height: 8px;
    background: rgba(255, 255, 255, .2);
    border-radius: 100px;
    opacity: 1;
    transition: all .3s;
}

:deep(.swiper-pagination-bullet-active) {
    width: 28px;
    background: #fff;
}

/* ══════════════════════════════════════ */
/*  LIGHTBOX                             */
/* ══════════════════════════════════════ */
.lb-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, .92);
    backdrop-filter: blur(20px);
    outline: none;
}

.lb-close {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .08);
    color: #ddd;
    border: none;
    cursor: pointer;
    transition: all .2s;
    z-index: 2;
}

.lb-close:hover {
    background: rgba(255, 255, 255, .16);
    color: #fff;
    transform: rotate(90deg);
}

.lb-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .06);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    cursor: pointer;
    transition: all .25s;
}

.lb-arrow:hover {
    background: rgba(255, 255, 255, .14);
    color: #fff;
    transform: translateY(-50%) scale(1.08);
}

.lb-arrow--prev {
    right: 1.25rem;
}

.lb-arrow--next {
    left: 1.25rem;
}

.lb-img {
    max-width: 88vw;
    max-height: 82vh;
    border-radius: 10px;
    object-fit: contain;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
    user-select: none;
}

.lb-caption {
    position: absolute;
    bottom: 4rem;
    left: 50%;
    transform: translateX(-50%);
    padding: .5rem 1.25rem;
    border-radius: .5rem;
    background: rgba(0, 0, 0, .55);
    backdrop-filter: blur(8px);
    color: #e0e0e0;
    font-size: .9rem;
    font-weight: 500;
    white-space: nowrap;
    max-width: 80vw;
    overflow: hidden;
    text-overflow: ellipsis;
}

.lb-counter {
    position: absolute;
    bottom: 1.25rem;
    left: 50%;
    transform: translateX(-50%);
    font-size: .8rem;
    color: #888;
    font-variant-numeric: tabular-nums;
}

/* Lightbox transitions */
.lb-enter-active,
.lb-leave-active {
    transition: opacity .3s ease;
}

.lb-enter-from,
.lb-leave-to {
    opacity: 0;
}

/* Slide transitions inside lightbox */
.lb-slide-left-enter-active,
.lb-slide-left-leave-active,
.lb-slide-right-enter-active,
.lb-slide-right-leave-active {
    transition: all .25s ease;
}

.lb-slide-left-enter-from {
    opacity: 0;
    transform: translateX(-30px);
}

.lb-slide-left-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.lb-slide-right-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.lb-slide-right-leave-to {
    opacity: 0;
    transform: translateX(-30px);
}

/* ══════════════════════════════════════ */
/*  REVEAL (scroll-in animation)         */
/* ══════════════════════════════════════ */
.reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity .6s ease, transform .6s ease;
}

.reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* Fade-in for the swiper wrap — self-managed observer */
.gallery-fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity .7s cubic-bezier(.4, 0, .2, 1), transform .7s cubic-bezier(.4, 0, .2, 1);
}

.gallery-fade-in.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* ══════════════════════════════════════ */
/*  RESPONSIVE                           */
/* ══════════════════════════════════════ */
@media (max-width: 640px) {
    .gallery-section {
        padding: 4rem 0 3rem;
    }

    .gallery-section-title {
        font-size: 1.4rem;
    }

    .lb-arrow {
        width: 38px;
        height: 38px;
    }

    .lb-arrow--prev {
        right: .5rem;
    }

    .lb-arrow--next {
        left: .5rem;
    }
}
</style>
