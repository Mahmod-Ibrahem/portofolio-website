<template>
    <div dir="rtl" class="article-page">

        <!-- Loading -->
        <div v-if="loading" class="article-loading">
            <div class="article-loading__spinner"></div>
            <span>جاري تحميل المقالة...</span>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="article-error">
            <div class="article-error__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <h2>حدث خطأ</h2>
            <p>{{ errorMessage }}</p>
            <RouterLink to="/" class="article-btn">← العودة للرئيسية</RouterLink>
        </div>

        <!-- Article Content -->
        <template v-else-if="article">
            <!-- Hero Header -->
            <header class="article-hero">
                <div class="article-hero__glow" aria-hidden="true"></div>
                <div class="article-hero__container">
                    <RouterLink to="/" class="article-back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        العودة للرئيسية
                    </RouterLink>

                    <div class="article-hero__badge">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        مقالة
                    </div>

                    <h1 class="article-hero__title">{{ article.title }}</h1>

                    <div class="article-hero__meta">
                        <span class="article-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            {{ article.created_at }}
                        </span>
                        <span class="article-meta-divider">•</span>
                        <span class="article-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            {{ article.views }} مشاهدة
                        </span>
                    </div>
                </div>
            </header>

            <!-- Cover Image -->
            <div v-if="article.cover_image" class="article-cover">
                <div class="article-cover__container">
                    <img :src="article.cover_image" :alt="article.title" class="article-cover__img" />
                </div>
            </div>

            <!-- Body -->
            <main class="article-body">
                <div class="article-body__container">
                    <div class="article-content" v-html="formattedContent"></div>

                    <!-- External Link -->
                    <div v-if="article.link" class="article-external-link">
                        <a :href="article.link" target="_blank" rel="noopener noreferrer"
                            class="article-external-link__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                            </svg>
                            رابط المقالة الأصلية
                        </a>
                    </div>

                    <!-- Share Section -->
                    <!-- <div class="article-share">
                        <span class="article-share__label">مشاركة المقالة</span>
                        <div class="article-share__links">
                            <a :href="'https://twitter.com/intent/tweet?text=' + encodeURIComponent(article.title) + '&url=' + encodeURIComponent(currentUrl)"
                                target="_blank" rel="noopener noreferrer" class="share-btn" aria-label="مشاركة على X">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                            <a :href="'https://wa.me/?text=' + encodeURIComponent(article.title + ' ' + currentUrl)"
                                target="_blank" rel="noopener noreferrer" class="share-btn"
                                aria-label="مشاركة على واتساب">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </a>
                            <button @click="copyLink" class="share-btn" :class="{ 'share-btn--copied': copied }"
                                aria-label="نسخ الرابط">
                                <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                        </div>
                    </div> -->

                    <!-- Back -->
                    <div class="article-bottom-nav">
                        <RouterLink to="/" class="article-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                            العودة للرئيسية
                        </RouterLink>
                    </div>
                </div>
            </main>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../../services/api'

const route = useRoute()

const article = ref(null)
const loading = ref(true)
const error = ref(false)
const errorMessage = ref('')
const copied = ref(false)

const currentUrl = computed(() => window.location.href)

const formattedContent = computed(() => {
    if (!article.value?.content) return ''
    // Convert newlines to <p> tags for proper rendering
    return article.value.content
        .split(/\n\n+/)
        .map(p => p.trim())
        .filter(p => p)
        .map(p => `<p>${p.replace(/\n/g, '<br/>')}</p>`)
        .join('')
})

async function fetchArticle() {
    loading.value = true
    error.value = false
    try {
        const res = await api.get(`/v1/articles/${route.params.id}`)
        article.value = res.data.data
    } catch (e) {
        error.value = true
        errorMessage.value = e.response?.status === 404
            ? 'المقالة غير موجودة'
            : 'حدث خطأ أثناء تحميل المقالة'
    } finally {
        loading.value = false
    }
}

async function copyLink() {
    try {
        await navigator.clipboard.writeText(window.location.href)
        copied.value = true
        setTimeout(() => { copied.value = false }, 2000)
    } catch { /* silent */ }
}

onMounted(fetchArticle)
</script>

<style scoped>
/* ══════════════════════════════════════ */
/*  BASE                                 */
/* ══════════════════════════════════════ */
.article-page {
    min-height: 100vh;
    background: #0a0a0a;
    color: #e0e0e0;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    line-height: 1.85;
}

/* ══════════════════════════════════════ */
/*  LOADING                              */
/* ══════════════════════════════════════ */
.article-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    gap: 1.5rem;
    color: #888;
    font-size: 1rem;
}

.article-loading__spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255, 255, 255, .08);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* ══════════════════════════════════════ */
/*  ERROR                                */
/* ══════════════════════════════════════ */
.article-error {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    gap: 1rem;
    text-align: center;
    padding: 2rem;
}

.article-error__icon {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(239, 68, 68, .08);
    color: #f87171;
}

.article-error__icon svg {
    width: 32px;
    height: 32px;
}

.article-error h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
}

.article-error p {
    color: #888;
    font-size: .95rem;
}

/* ══════════════════════════════════════ */
/*  HERO HEADER                          */
/* ══════════════════════════════════════ */
.article-hero {
    position: relative;
    padding: 3rem 0 3.5rem;
    background: linear-gradient(180deg, #111 0%, #0a0a0a 100%);
    overflow: hidden;
}

.article-hero__glow {
    position: absolute;
    top: -120px;
    left: 50%;
    transform: translateX(-50%);
    width: 700px;
    height: 400px;
    background: radial-gradient(ellipse, rgba(255, 255, 255, .03) 0%, transparent 70%);
    pointer-events: none;
}

.article-hero__container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 1;
}

/* Back link */
.article-back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #888;
    text-decoration: none;
    font-size: .88rem;
    font-weight: 500;
    margin-bottom: 2rem;
    transition: color .25s;
}

.article-back-link svg {
    width: 16px;
    height: 16px;
}

.article-back-link:hover {
    color: #fff;
}

/* Badge */
.article-hero__badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 100px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #aaa;
    font-size: .82rem;
    font-weight: 500;
    margin-bottom: 1.25rem;
}

.article-hero__badge svg {
    width: 16px;
    height: 16px;
}

/* Title */
.article-hero__title {
    font-size: clamp(1.6rem, 4vw, 2.4rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.4;
    letter-spacing: -.02em;
    margin-bottom: 1.5rem;
}

/* Meta */
.article-hero__meta {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.article-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #777;
    font-size: .88rem;
}

.article-meta-item svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.article-meta-divider {
    color: #444;
    font-size: .7rem;
}

/* ══════════════════════════════════════ */
/*  COVER IMAGE                          */
/* ══════════════════════════════════════ */
.article-cover {
    background: #0a0a0a;
}

.article-cover__container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 24px;
}

.article-cover__img {
    width: 100%;
    max-height: 420px;
    object-fit: cover;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, .06);
}

/* ══════════════════════════════════════ */
/*  BODY / CONTENT                       */
/* ══════════════════════════════════════ */
.article-body {
    padding: 3rem 0 4rem;
}

.article-body__container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 24px;
}

/* Rich content styles */
.article-content {
    font-size: 1.08rem;
    line-height: 2;
    color: #ccc;
}

.article-content :deep(p) {
    margin-bottom: 1.5rem;
}

.article-content :deep(h2),
.article-content :deep(h3) {
    color: #fff;
    font-weight: 700;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
}

.article-content :deep(h2) {
    font-size: 1.4rem;
}

.article-content :deep(h3) {
    font-size: 1.2rem;
}

.article-content :deep(strong) {
    color: #fff;
    font-weight: 600;
}

.article-content :deep(ul),
.article-content :deep(ol) {
    padding-right: 1.5rem;
    margin-bottom: 1.5rem;
}

.article-content :deep(li) {
    margin-bottom: .5rem;
}

.article-content :deep(blockquote) {
    border-right: 3px solid rgba(255, 255, 255, .2);
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    background: rgba(255, 255, 255, .03);
    border-radius: 0 12px 12px 0;
    font-style: italic;
    color: #bbb;
}

.article-content :deep(a) {
    color: #8bb4ff;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.article-content :deep(a:hover) {
    color: #bdd4ff;
}

/* ══════════════════════════════════════ */
/*  SHARE                                */
/* ══════════════════════════════════════ */
.article-share {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 1.5rem 0;
    margin-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, .06);
}

.article-share__label {
    font-size: .88rem;
    color: #777;
    font-weight: 500;
    white-space: nowrap;
}

.article-share__links {
    display: flex;
    gap: 10px;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #aaa;
    text-decoration: none;
    cursor: pointer;
    transition: all .3s cubic-bezier(.4, 0, .2, 1);
}

.share-btn svg {
    width: 18px;
    height: 18px;
}

.share-btn:hover {
    background: #fff;
    color: #111;
    border-color: #fff;
    transform: translateY(-2px);
}

.share-btn--copied {
    background: rgba(34, 197, 94, .15);
    border-color: rgba(34, 197, 94, .3);
    color: #4ade80;
}

/* ══════════════════════════════════════ */
/*  EXTERNAL LINK                        */
/* ══════════════════════════════════════ */
.article-external-link {
    margin-top: 2.5rem;
    display: flex;
    justify-content: center;
}

.article-external-link__btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.article-external-link__btn svg {
    width: 20px;
    height: 20px;
}

.article-external-link__btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.6);
}

/* ══════════════════════════════════════ */
/*  BOTTOM NAV                           */
/* ══════════════════════════════════════ */
.article-bottom-nav {
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, .06);
    margin-top: 1rem;
}

.article-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    text-decoration: none;
    font-size: .92rem;
    font-weight: 600;
    transition: all .3s;
}

.article-btn svg {
    width: 18px;
    height: 18px;
}

.article-btn:hover {
    background: rgba(255, 255, 255, .12);
    color: #fff;
    transform: translateY(-2px);
}

/* ══════════════════════════════════════ */
/*  RESPONSIVE                           */
/* ══════════════════════════════════════ */
@media (max-width: 640px) {
    .article-hero {
        padding: 2rem 0 2.5rem;
    }

    .article-hero__title {
        font-size: 1.4rem;
    }

    .article-content {
        font-size: 1rem;
    }

    .article-cover__img {
        border-radius: 12px;
        max-height: 280px;
    }

    .article-share {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
}
</style>
