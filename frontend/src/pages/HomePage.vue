<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import api from '../services/api'
import HomeNavbar from '../components/HomeNavbar.vue'
import HomeGalleryCarousel from '../components/HomeGalleryCarousel.vue'

/* ── Home Data ── */
const page = ref(null)
const pageLoading = ref(true)
const books = ref([])

async function loadHomePageData() {
    pageLoading.value = true
    try {
        const res = await api.get('/v1/homepage-data')
        const data = res.data.data
        page.value = data.page
        articles.value = data.articles.data
        articlesMeta.value = data.articles.meta
        videos.value = data.videos.data
        videosMeta.value = data.videos.meta
        books.value = data.books
    } catch (e) { console.error('Error loading homepage data', e) }
    pageLoading.value = false
}

/* SVG path data for social icons */
const socialSvgs = {
    x: 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
    youtube: 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12z',
    linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0z',
    snapchat: 'M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.06c-.012.18-.022.345-.03.51.075.045.203.09.401.09.3-.016.659-.12 1.033-.301.165-.088.344-.104.464-.104.182 0 .359.029.509.09.45.149.734.479.734.838.015.449-.39.839-1.213 1.168-.089.029-.209.075-.344.119-.45.135-1.139.36-1.333.81-.09.224-.061.524.12.868l.015.015c.06.136 1.526 3.475 4.791 4.014.255.044.435.27.42.509 0 .075-.015.149-.045.225-.24.569-1.273.988-3.146 1.271-.059.091-.12.375-.164.57-.029.179-.074.36-.134.553-.076.271-.27.405-.555.405h-.03c-.135 0-.313-.031-.538-.074-.36-.075-.765-.135-1.273-.135-.3 0-.599.015-.913.074-.6.104-1.123.464-1.723.884-.853.599-1.826 1.288-3.294 1.288-.06 0-.119-.015-.18-.015h-.149c-1.468 0-2.427-.675-3.279-1.288-.599-.42-1.107-.779-1.707-.884-.314-.045-.629-.074-.928-.074-.54 0-.958.089-1.272.149-.211.043-.391.074-.54.074-.374 0-.523-.224-.583-.42-.061-.192-.09-.389-.135-.567-.046-.181-.105-.494-.166-.57-1.918-.222-2.95-.642-3.189-1.226-.031-.063-.052-.15-.055-.225-.015-.243.165-.465.42-.509 3.264-.54 4.73-3.879 4.791-4.02l.016-.029c.18-.345.224-.645.119-.869-.195-.434-.884-.658-1.332-.809-.121-.029-.24-.074-.346-.119-1.107-.435-1.257-.93-1.197-1.273.09-.479.674-.793 1.168-.793.146 0 .27.029.383.074.42.194.789.3 1.104.3.234 0 .384-.06.465-.105l-.046-.569c-.098-1.626-.225-3.651.307-4.837C7.392 1.077 10.739.807 11.727.807l.419-.015h.06z',
    tiktok: 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
    whatsapp: 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z',
}

/* ── Articles & Videos (paginated per 6) ── */
const articles = ref([])
const articlesMeta = ref({})
const articlesPage = ref(1)
const articlesLoading = ref(false)

const videos = ref([])
const videosMeta = ref({})
const videosPage = ref(1)
const videosLoading = ref(false)

async function loadArticles(page = 1) {
    articlesLoading.value = true
    try {
        const res = await api.get('/v1/articles', { params: { is_published: 1, per_page: 6, page } })
        articles.value = res.data.data
        articlesMeta.value = res.data.meta || {}
        articlesPage.value = page
    } catch (e) { /* silent */ }
    articlesLoading.value = false
}

async function loadVideos(page = 1) {
    videosLoading.value = true
    try {
        const res = await api.get('/v1/videos', { params: { is_published: 1, per_page: 6, page } })
        videos.value = res.data.data
        videosMeta.value = res.data.meta || {}
        videosPage.value = page
    } catch (e) { /* silent */ }
    videosLoading.value = false
}

function truncate(str, len = 100) {
    if (!str) return ''
    return str.length > len ? str.substring(0, len) + '…' : str
}

/* ── Intersection Observer for scroll-reveal ── */
const observed = ref(new Set())
let observer = null

function initObserver() {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible')
                    observer.unobserve(e.target)
                }
            })
        },
        { threshold: 0.15 }
    )
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el))
}

onMounted(async () => {
    await loadHomePageData()
    // Track visit (fire-and-forget)
    api.post('/v1/track-visit').catch(() => { })
    await nextTick()
    initObserver()
})
onUnmounted(() => observer?.disconnect())
</script>

<template>
    <div dir="rtl" class="homepage">

        <!-- Loading guard -->
        <div v-if="pageLoading" class="flex items-center justify-center" style="min-height:100vh;background:#0a0a0a">
            <span style="color:#999;font-size:1.1rem">جاري التحميل...</span>
        </div>

        <template v-else-if="page">

            <!-- ═══════════════ NAVBAR ═══════════════ -->
            <HomeNavbar :logoText="page.hero_name?.split(' ').slice(0, 2).join(' ') || 'أوس الفوزان'" />

            <!-- ═══════════════ HERO  100vh ═══════════════ -->
            <header class="hero" id="hero" role="banner">
                <div class="hero-blob hero-blob--1" aria-hidden="true"></div>
                <div class="hero-blob hero-blob--2" aria-hidden="true"></div>

                <div class="hero-inner">
                    <div class="hero-text">
                        <span class="hero-badge anim-stagger" style="--i:0">
                            <span class="pulse-dot"><span></span></span>
                            {{ page.hero_badge }}
                        </span>

                        <h1 class="hero-title anim-stagger" style="--i:1">{{ page.hero_name }}</h1>

                        <p class="hero-sub anim-stagger" style="--i:2"
                            v-html="page.hero_subtitle?.replace(/\n/g, '<br/>')"></p>

                        <blockquote class="hero-quote anim-stagger" style="--i:3">
                            <span class="quote-mark" aria-hidden="true">"</span>
                            {{ page.hero_quote }}
                        </blockquote>
                    </div>

                    <div class="hero-image anim-stagger" style="--i:2">
                        <div class="hero-ring hero-ring--outer" aria-hidden="true"></div>
                        <div class="hero-ring hero-ring--inner" aria-hidden="true"></div>
                        <img :src="page.hero_image || 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800'"
                            alt="الأمن السيبراني" class="hero-img" loading="eager" />
                    </div>
                </div>


            </header>

            <!-- ═══════════════ EXPERTISE CARDS ═══════════════ -->
            <section class="section-cards" id="expertise" aria-labelledby="expertise-heading">
                <div class="cards-bg-grid" aria-hidden="true"></div>
                <div class="container">
                    <div class="cards-grid">
                        <article class="expertise-card reveal" v-for="(card, idx) in (page.expertise_cards || [])"
                            :key="idx" :class="{ 'expertise-card--featured': idx === 1 }">
                            <div class="expertise-card__glow" aria-hidden="true"></div>
                            <div class="expertise-card__inner">
                                <div class="card-icon-wrap">
                                    <svg v-if="card.icon === 'shield'" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <svg v-if="card.icon === 'bolt'" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <svg v-if="card.icon === 'code'" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </div>
                                <h3 class="card-title">{{ card.title }}</h3>
                                <p class="card-desc">{{ card.desc }}</p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ TIP BANNER ═══════════════ -->
            <section class="tip-banner reveal" aria-label="معلومة مختصرة">
                <div class="tip-banner__border-top" aria-hidden="true"></div>
                <div class="tip-banner__border-bottom" aria-hidden="true"></div>
                <div class="container">
                    <div class="tip-banner__icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                        </svg>
                    </div>
                    <p v-html="page.tip_banner"></p>
                </div>
            </section>

            <!-- ═══════════════ ACHIEVEMENTS ═══════════════ -->
            <section class="section-achievements" id="achievements" aria-labelledby="achievements-heading">
                <div class="achievements-bg-dots" aria-hidden="true"></div>
                <div class="container">
                    <h2 id="achievements-heading" class="achievements-heading reveal">
                        <span class="achievements-heading__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.996.178-1.768.65-2.08 1.377-.318.744-.075 1.598.576 2.283a4.5 4.5 0 00-1.054 1.118c-.394.604-.544 1.262-.275 1.883.273.63.867 1.03 1.579 1.247M18.75 4.236c.996.178 1.768.65 2.08 1.377.318.744.075 1.598-.576 2.283a4.5 4.5 0 011.054 1.118c.394.604.544 1.262.275 1.883-.273.63-.867 1.03-1.579 1.247M12 2.25c-2.287 0-4.2 1.584-4.69 3.716a6.75 6.75 0 004.69 8.284 6.75 6.75 0 004.69-8.284C16.2 3.834 14.287 2.25 12 2.25z" />
                            </svg>
                        </span>
                        <span class="achievements-heading__text">الإنجازات والمساهمات</span>
                        <span class="achievements-heading__line" aria-hidden="true"></span>
                    </h2>

                    <div class="stats-grid">
                        <div class="stat-card reveal" v-for="(s, i) in (page.achievements || [])" :key="i">
                            <div class="stat-card__glow" aria-hidden="true"></div>
                            <div class="stat-card__content">
                                <span class="stat-num">{{ s.num }}</span>
                                <span class="stat-divider" aria-hidden="true"></span>
                                <span class="stat-label">{{ s.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ VOLUNTEERING ═══════════════ -->
            <section class="section-volunteer" id="volunteer" aria-labelledby="volunteer-heading">
                <div class="volunteer-bg" aria-hidden="true"></div>
                <div class="container">
                    <h2 id="volunteer-heading" class="volunteer-heading reveal">
                        <span class="volunteer-heading__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </span>
                        <span class="volunteer-heading__text">الاهتمامات والدورات التطوعية</span>
                        <span class="volunteer-heading__line" aria-hidden="true"></span>
                    </h2>

                    <div class="volunteer-card reveal">
                        <div class="volunteer-card__inner">
                            <p v-html="page.volunteering_intro?.replace(/\n/g, '</p><p>')"></p>
                            <ul>
                                <li v-for="(b, i) in (page.volunteering_bullets || [])" :key="i">{{ b }}</li>
                            </ul>
                            <p class="volunteer-closing">{{ page.volunteering_closing }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ LATEST ARTICLES ═══════════════ -->
            <section class="section-content section-content--dark" id="articles" aria-labelledby="articles-heading">
                <div class="container">
                    <h2 id="articles-heading" class="section-heading reveal"> المقالات</h2>

                    <div v-if="articlesLoading" class="text-center py-8 text-slate-400">جاري التحميل...</div>

                    <div v-else-if="articles.length" class="content-grid">
                        <article class="content-card reveal" v-for="a in articles" :key="a.id">
                            <div class="content-card__border"></div>
                            <RouterLink :to="{ name: 'article.show', params: { id: a.id } }"
                                class="content-card__inner content-card__link">
                                <div v-if="a.cover_image" class="content-card__img-wrap">
                                    <img :src="a.cover_image" :alt="a.title" class="content-card__img" loading="lazy" />
                                </div>
                                <div v-else class="content-card__img-wrap content-card__img-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                                <div class="content-card__body">
                                    <h3 class="content-card__title">{{ a.title }}</h3>
                                    <p class="content-card__excerpt">{{ truncate(a.content, 120) }}</p>
                                    <div class="content-card__meta">
                                        <span>{{ a.created_at }}</span>
                                        <span>{{ a.views }} مشاهدة</span>
                                    </div>
                                    <span class="content-card__cta">اقرأ المزيد ←</span>
                                </div>
                            </RouterLink>
                        </article>
                    </div>

                    <div v-else class="text-center py-8 text-slate-400">لا توجد مقالات منشورة حالياً</div>

                    <!-- Pagination -->
                    <div v-if="articlesMeta.last_page > 1" class="content-pagination">
                        <button @click="loadArticles(articlesPage - 1)" :disabled="articlesPage <= 1"
                            class="pg-btn">السابق</button>
                        <span class="pg-info">{{ articlesPage }} / {{ articlesMeta.last_page }}</span>
                        <button @click="loadArticles(articlesPage + 1)"
                            :disabled="articlesPage >= articlesMeta.last_page" class="pg-btn">التالي</button>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ HONORS (التكريمات) ═══════════════ -->
            <section v-if="page.honors?.length" class="section-honors" id="honors">
                <div class="honors-bg" aria-hidden="true"></div>
                <div class="container">
                    <h2 class="honors-heading reveal">
                        <span class="honors-heading__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </span>
                        <span class="honors-heading__text">التكريمات</span>
                        <span class="honors-heading__line" aria-hidden="true"></span>
                    </h2>

                    <div class="honors-grid">
                        <div v-for="(honor, idx) in page.honors" :key="idx" class="reveal honor-card">
                            <div class="honor-card__glow" aria-hidden="true"></div>
                            <div class="honor-card__inner">
                                <div class="honor-card__year">{{ honor.year }}</div>
                                <p class="honor-card__desc">{{ honor.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ STATISTICS (احصائيات) ═══════════════ -->
            <section v-if="page.statistics?.length" class="section-statistics" id="statistics">
                <div class="statistics-bg" aria-hidden="true"></div>
                <div class="container">
                    <h2 class="statistics-heading reveal">
                        <span class="statistics-heading__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </span>
                        <span class="statistics-heading__text">احصائيات البحوث والدورات التدريبية والإستشارات
                            التربوية</span>
                        <span class="statistics-heading__line" aria-hidden="true"></span>
                    </h2>

                    <div class="statistics-grid">
                        <div v-for="(stat, idx) in page.statistics" :key="idx" class="reveal statistics-card">
                            <div class="statistics-card__glow" aria-hidden="true"></div>
                            <div class="statistics-card__inner">
                                <span class="statistics-card__value" dir="ltr">{{ stat.value }}</span>
                                <span class="statistics-card__divider" aria-hidden="true"></span>
                                <span class="statistics-card__label">{{ stat.label }}</span>
                                <span class="statistics-card__desc">{{ stat.description }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ LATEST VIDEOS ═══════════════ -->
            <section class="section-content section-content--dark" id="videos" aria-labelledby="videos-heading">
                <div class="container">
                    <h2 id="videos-heading" class="section-heading reveal">الظهور الأعلامي</h2>

                    <div v-if="videosLoading" class="text-center py-8 text-slate-400">جاري التحميل...</div>

                    <div v-else-if="videos.length" class="content-grid">
                        <article class="content-card reveal" v-for="v in videos" :key="v.id">
                            <div class="content-card__border"></div>
                            <div class="content-card__inner">
                                <div v-if="v.embed_url" class="content-card__video-wrap">
                                    <iframe :src="v.embed_url" :title="v.title" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen loading="lazy" class="content-card__video"></iframe>
                                </div>
                                <div v-else-if="v.thumbnail" class="content-card__img-wrap">
                                    <img :src="v.thumbnail" :alt="v.title" class="content-card__img" loading="lazy" />
                                </div>
                                <div v-else class="content-card__img-wrap content-card__img-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="content-card__body">
                                    <h3 class="content-card__title">{{ v.title }}</h3>
                                    <p class="content-card__excerpt">{{ truncate(v.description, 120) }}</p>
                                    <div class="content-card__meta">
                                        <span>{{ v.created_at }}</span>
                                        <span>{{ v.views }} مشاهدة</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div v-else class="text-center py-8 text-slate-400">لا توجد فيديوهات منشورة حالياً</div>

                    <!-- Pagination -->
                    <div v-if="videosMeta.last_page > 1" class="content-pagination">
                        <button @click="loadVideos(videosPage - 1)" :disabled="videosPage <= 1"
                            class="pg-btn">السابق</button>
                        <span class="pg-info">{{ videosPage }} / {{ videosMeta.last_page }}</span>
                        <button @click="loadVideos(videosPage + 1)" :disabled="videosPage >= videosMeta.last_page"
                            class="pg-btn">التالي</button>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ GALLERY CAROUSEL ═══════════════ -->
            <HomeGalleryCarousel />

            <!-- ═══════════════ BOOKS CAROUSEL ═══════════════ -->
            <section v-if="books.length" class="section-books" id="books">
                <div class="books-bg" aria-hidden="true"></div>
                <div class="homepage-container"
                    style="max-width:1200px;margin:0 auto;padding:0 24px;position:relative;z-index:1;">
                    <!-- Heading -->
                    <div class="books-heading reveal">
                        <span class="books-heading__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </span>
                        <h2 class="books-heading__text">مؤلفاتي</h2>
                        <span class="books-heading__line" aria-hidden="true"></span>
                    </div>

                    <!-- Carousel -->
                    <div class="books-carousel">
                        <div class="books-track">
                            <a v-for="book in books" :key="book.id" :href="book.url" target="_blank"
                                rel="noopener noreferrer" class="book-slide">
                                <div class="book-slide__img-wrap">
                                    <img v-if="book.image" :src="book.image" alt="" class="book-slide__img"
                                        loading="lazy" />
                                    <div v-else class="book-slide__placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ FOOTER / CONTACT ═══════════════ -->
            <footer class="site-footer" id="contact" role="contentinfo">
                <div class="footer-glow" aria-hidden="true"></div>
                <div class="footer-top-border" aria-hidden="true"></div>

                <div class="footer-container">
                    <!-- Heading -->
                    <div class="footer-header reveal">
                        <span class="footer-header__icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <h2 class="footer-heading">تواصل معي</h2>
                        <span class="footer-heading__line" aria-hidden="true"></span>
                    </div>
                    <p class="footer-subtitle reveal">يسعدني تواصلكم عبر القنوات التالية</p>

                    <!-- Contact Cards -->
                    <div class="footer-contact reveal">
                        <a v-if="page.phone" :href="'tel:' + page.phone" class="contact-card" aria-label="اتصال هاتفي">
                            <span class="contact-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                </svg>
                            </span>
                            <span class="contact-card__label">الهاتف</span>
                            <span class="contact-card__value" dir="ltr">{{ page.phone }}</span>
                        </a>
                        <a v-if="page.email" :href="'mailto:' + page.email" class="contact-card"
                            aria-label="بريد إلكتروني">
                            <span class="contact-card__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </span>
                            <span class="contact-card__label">البريد الإلكتروني</span>
                            <span class="contact-card__value" dir="ltr">{{ page.email }}</span>
                        </a>
                    </div>

                    <!-- Social -->
                    <div class="footer-social reveal">
                        <p class="footer-social__label">تابعني على</p>
                        <ul class="social-row" dir="ltr" aria-label="حسابات التواصل الاجتماعي">
                            <li v-for="s in (page.social_links || [])" :key="s.platform">
                                <a :href="s.url" target="_blank" rel="noopener noreferrer" :aria-label="s.platform"
                                    class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path :d="socialSvgs[s.platform] || ''" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="footer-bottom">
                    <div class="footer-container">
                        <p class="footer-tagline">متابعتكم لحساباتي تزيدني شرفاً وتألقاً</p>
                        <p class="footer-copy">© {{ new Date().getFullYear() }} جميع الحقوق محفوظة</p>
                    </div>
                </div>
            </footer>

        </template>

    </div>
</template>

<style scoped>
/* ── Base ── */
.homepage {
    background: #fff;
    color: #0a0a0a;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.75;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ═══════════ HERO ═══════════ */
.hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #0a0a0a;
    color: #fff;
    padding: 100px 24px 80px;
    overflow: hidden;
}

/* Animated blobs */
.hero-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    opacity: .15;
    will-change: transform;
}

.hero-blob--1 {
    width: 420px;
    height: 420px;
    top: -80px;
    right: -60px;
    background: #777;
    animation: blobFloat 8s ease-in-out infinite alternate;
}

.hero-blob--2 {
    width: 500px;
    height: 500px;
    bottom: -100px;
    left: -80px;
    background: #555;
    animation: blobFloat 10s ease-in-out 2s infinite alternate-reverse;
}

@keyframes blobFloat {
    from {
        transform: translate(0, 0) scale(1);
    }

    to {
        transform: translate(30px, -20px) scale(1.08);
    }
}

.hero-inner {
    max-width: 1200px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 60px;
    position: relative;
    z-index: 2;
}

/* ═══════════ STATISTICS ═══════════ */
.section-statistics {
    padding: 7rem 0;
    background: linear-gradient(170deg, #0a0a0a 0%, #141414 50%, #0e0e0e 100%);
    position: relative;
    overflow: hidden;
}

.statistics-bg {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(242, 236, 230, .03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(242, 236, 230, .03) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.statistics-heading {
    text-align: center;
    margin-bottom: 3.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.statistics-heading__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(242, 236, 230, .06);
    border: 1px solid rgba(242, 236, 230, .08);
    color: #E0D6CC;
    margin-bottom: 0.5rem;
}

.statistics-heading__icon svg {
    width: 24px;
    height: 24px;
}

.statistics-heading__text {
    font-size: clamp(1.3rem, 2.5vw, 1.8rem);
    font-weight: 800;
    color: #FFFDFB;
    letter-spacing: -.02em;
}

.statistics-heading__line {
    display: block;
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #E0D6CC, transparent);
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
}

.statistics-card {
    text-align: center;
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    transition: transform 400ms cubic-bezier(.25, .46, .45, .94);
}

.statistics-card:hover {
    transform: translateY(-6px);
}

.statistics-card__glow {
    position: absolute;
    inset: -1px;
    border-radius: 19px;
    background: conic-gradient(from var(--angle, 0deg), transparent 30%, rgba(242, 236, 230, .15) 50%, transparent 70%);
    animation: borderSpin 4s linear infinite;
    z-index: 0;
    opacity: 0;
    transition: opacity 400ms ease;
}

.statistics-card:hover .statistics-card__glow {
    opacity: 1;
}

.statistics-card__inner {
    position: relative;
    z-index: 1;
    padding: 2.5rem 1.5rem;
    border-radius: 18px;
    background: rgba(242, 236, 230, .04);
    border: 1px solid rgba(242, 236, 230, .08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    transition: border-color 400ms ease, background 400ms ease;
}

.statistics-card:hover .statistics-card__inner {
    border-color: rgba(242, 236, 230, .16);
    background: rgba(242, 236, 230, .07);
}

.statistics-card__value {
    display: block;
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    font-weight: 800;
    color: #FFFDFB;
    line-height: 1.1;
    letter-spacing: -.03em;
    transition: transform 300ms ease;
}

.statistics-card:hover .statistics-card__value {
    transform: scale(1.08);
}

.statistics-card__divider {
    display: block;
    width: 32px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #E0D6CC, transparent);
    margin: 14px auto;
    border-radius: 100px;
    transition: width 300ms ease;
}

.statistics-card:hover .statistics-card__divider {
    width: 48px;
    background: linear-gradient(90deg, transparent, #F2ECE6, transparent);
}

.statistics-card__label {
    font-size: .95rem;
    color: #F2ECE6;
    line-height: 1.6;
    font-weight: 600;
    transition: color 300ms ease;
}

.statistics-card:hover .statistics-card__label {
    color: #FFFDFB;
}

.statistics-card__desc {
    font-size: .82rem;
    color: #E0D6CC;
    line-height: 1.5;
    font-weight: 400;
    margin-top: 6px;
    opacity: .8;
    transition: opacity 300ms ease;
}

.statistics-card:hover .statistics-card__desc {
    opacity: 1;
}

.stat-card__content {
    position: relative;
    z-index: 1;
    padding: 2.25rem 1.5rem;
    border-radius: 18px;
    background: rgba(242, 236, 230, .04);
    border: 1px solid rgba(242, 236, 230, .08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: border-color 400ms ease, background 400ms ease;
}

.stat-card:hover .stat-card__content {
    border-color: rgba(242, 236, 230, .16);
    background: rgba(242, 236, 230, .07);
}

.stat-num {
    display: block;
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #FFFDFB;
    line-height: 1.1;
    letter-spacing: -.02em;
    transition: transform 300ms ease;
}

.stat-card:hover .stat-num {
    transform: scale(1.08);
}

.stat-divider {
    display: block;
    width: 32px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #E0D6CC, transparent);
    margin: 14px auto;
    border-radius: 100px;
    transition: width 300ms ease;
}

.stat-card:hover .stat-divider {
    width: 48px;
    background: linear-gradient(90deg, transparent, #F2ECE6, transparent);
}

.stat-label {
    font-size: .92rem;
    color: #E0D6CC;
    line-height: 1.6;
    font-weight: 400;
    transition: color 300ms ease;
}

.stat-card:hover .stat-label {
    color: #F2ECE6;
}


/* ═══════════ HONORS ═══════════ */
.section-honors {
    padding: 7rem 0;
    background: linear-gradient(170deg, #0a0a0a 0%, #131313 50%, #0d0d0d 100%);
    position: relative;
    overflow: hidden;
}

.honors-bg {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(242, 236, 230, .04) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}

.honors-heading {
    text-align: center;
    margin-bottom: 3.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.honors-heading__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(242, 236, 230, .06);
    border: 1px solid rgba(242, 236, 230, .08);
    color: #E0D6CC;
    margin-bottom: 0.5rem;
}

.honors-heading__icon svg {
    width: 24px;
    height: 24px;
}

.honors-heading__text {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #FFFDFB;
    letter-spacing: -.02em;
}

.honors-heading__line {
    display: block;
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #E0D6CC, transparent);
}

.honors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.honor-card {
    position: relative;
    border-radius: 20px;
    text-align: center;
    transition: transform 400ms cubic-bezier(.25, .46, .45, .94);
}

.honor-card:hover {
    transform: translateY(-8px);
}

.honor-card__glow {
    position: absolute;
    inset: -1px;
    border-radius: 21px;
    background: conic-gradient(from var(--angle, 0deg), transparent 30%, rgba(242, 236, 230, .15) 50%, transparent 70%);
    animation: borderSpin 4s linear infinite;
    z-index: 0;
    opacity: 0;
    transition: opacity 400ms ease;
}

.honor-card:hover .honor-card__glow {
    opacity: 1;
}

.honor-card__inner {
    position: relative;
    z-index: 1;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    background: rgba(242, 236, 230, .04);
    border: 1px solid rgba(242, 236, 230, .08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    transition: border-color 400ms ease, background 400ms ease;
}

.honor-card:hover .honor-card__inner {
    border-color: rgba(242, 236, 230, .16);
    background: rgba(242, 236, 230, .07);
}

.honor-card__year {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 20px;
    border-radius: 100px;
    background: rgba(242, 236, 230, .08);
    border: 1px solid rgba(242, 236, 230, .12);
    color: #F2ECE6;
    font-weight: 700;
    font-size: 1.1rem;
    letter-spacing: .04em;
    transition: all 350ms ease;
}

.honor-card:hover .honor-card__year {
    background: #F2ECE6;
    color: #1B2A3B;
    border-color: #F2ECE6;
    box-shadow: 0 0 20px rgba(242, 236, 230, .15);
}

.honor-card__desc {
    font-size: 1rem;
    color: #E0D6CC;
    line-height: 1.9;
    margin: 0;
    transition: color 300ms ease;
}

.honor-card:hover .honor-card__desc {
    color: #FFFDFB;
}

/* Stagger animation */
.anim-stagger {
    opacity: 0;
    transform: translateY(28px);
    animation: staggerUp .7s ease-out forwards;
    animation-delay: calc(var(--i) * .15s + .3s);
}

@keyframes staggerUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (prefers-reduced-motion: reduce) {
    .anim-stagger {
        animation: none;
        opacity: 1;
        transform: none;
    }

    .hero-blob {
        animation: none;
    }

    .hero-ring {
        animation: none !important;
    }

    .reveal {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
}

.hero-text {
    flex: 1;
    text-align: right;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 18px;
    border-radius: 100px;
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .12);
    font-size: .85rem;
    font-weight: 500;
    color: #bbb;
    margin-bottom: 20px;
}

.pulse-dot {
    position: relative;
    display: inline-flex;
    width: 10px;
    height: 10px;
}

.pulse-dot>span {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #bbb;
    animation: pingDot 1.6s cubic-bezier(0, 0, .2, 1) infinite;
}

.pulse-dot::after {
    content: '';
    display: block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #fff;
    position: relative;
    z-index: 1;
}

@keyframes pingDot {

    75%,
    100% {
        transform: scale(2.2);
        opacity: 0;
    }
}

.hero-title {
    font-size: clamp(2rem, 5vw, 3.6rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 18px;
    letter-spacing: -.02em;
}

.hero-sub {
    font-size: clamp(1rem, 2vw, 1.25rem);
    color: #bbb;
    font-weight: 300;
    line-height: 1.9;
    margin-bottom: 24px;
}

.hero-sub strong {
    color: #fff;
    font-weight: 600;
}

.hero-quote {
    position: relative;
    padding: 22px 28px;
    background: rgba(255, 255, 255, .06);
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, .1);
    font-style: italic;
    font-size: 1.05rem;
    color: #e0e0e0;
    line-height: 1.8;
}

.quote-mark {
    position: absolute;
    top: -14px;
    right: 16px;
    font-size: 3.5rem;
    line-height: 1;
    color: rgba(255, 255, 255, .12);
    font-style: normal;
}

/* Hero Image */
.hero-image {
    position: relative;
    flex-shrink: 0;
    width: 45%;
    min-height: 420px;
    align-self: stretch;
}

.hero-ring {
    display: none;
}

.hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    border-radius: 16px;
    -webkit-mask-image: linear-gradient(to left, #000 60%, transparent 100%);
    mask-image: linear-gradient(to left, #000 60%, transparent 100%);
}

/* Floating card */
.hero-floating-card {
    position: relative;
    z-index: 3;
    max-width: 600px;
    margin-top: 48px;
    padding: 28px 36px;
    background: #fff;
    color: #0a0a0a;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, .12);
    font-size: 1rem;
    text-align: center;
    line-height: 1.9;
}

/* ═══════════ SCROLL REVEAL ═══════════ */
.reveal {
    opacity: 1;
    transform: translateY(32px);
    transition: opacity .65s ease-out, transform .65s ease-out;
}

.reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* ═══════════ EXPERTISE CARDS ═══════════ */
.section-cards {
    padding: 7rem 0;
    background: linear-gradient(175deg, #0c0c0c 0%, #151515 50%, #0e0e0e 100%);
    position: relative;
    overflow: hidden;
}

.cards-bg-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .03) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.cards-grid {
    display: flex;
    align-items: stretch;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.expertise-card {
    flex: 0 1 320px;
    position: relative;
    border-radius: 20px;
    text-align: center;
    transition: transform 400ms cubic-bezier(.25, .46, .45, .94);
}

.expertise-card:hover {
    transform: translateY(-8px);
}

.expertise-card__glow {
    position: absolute;
    inset: -1px;
    border-radius: 21px;
    background: conic-gradient(from var(--angle, 0deg), transparent 30%, rgba(255, 255, 255, .12) 50%, transparent 70%);
    animation: borderSpin 4s linear infinite;
    z-index: 0;
    opacity: 0;
    transition: opacity 400ms ease;
}

.expertise-card:hover .expertise-card__glow {
    opacity: 1;
}

.expertise-card__inner {
    position: relative;
    z-index: 1;
    padding: 2.5rem 2rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    height: 100%;
    transition: border-color 400ms ease, background 400ms ease;
}

.expertise-card:hover .expertise-card__inner {
    border-color: rgba(255, 255, 255, .16);
    background: rgba(255, 255, 255, .06);
}

.expertise-card--featured {
    transform: scale(1.06);
    z-index: 1;
}

.expertise-card--featured:hover {
    transform: scale(1.06) translateY(-8px);
}

.card-icon-wrap {
    width: 56px;
    height: 56px;
    margin: 0 auto 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    transition: all 350ms ease;
}

.card-icon-wrap svg {
    width: 26px;
    height: 26px;
}

.expertise-card:hover .card-icon-wrap {
    background: #fff;
    color: #111;
    border-color: #fff;
    box-shadow: 0 0 24px rgba(255, 255, 255, .1);
}

.card-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #fff;
}

.card-desc {
    font-size: .92rem;
    color: #999;
    line-height: 1.8;
}

@property --angle {
    syntax: '<angle>';
    initial-value: 0deg;
    inherits: false;
}

@keyframes borderSpin {
    to {
        --angle: 360deg;
    }
}

/* ═══════════ TIP BANNER ═══════════ */
.tip-banner {
    background: #0a0a0a;
    color: #fff;
    text-align: center;
    padding: 4.5rem 24px;
    position: relative;
    overflow: hidden;
}

.tip-banner__border-top,
.tip-banner__border-bottom {
    position: absolute;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .12), transparent);
}

.tip-banner__border-top {
    top: 0;
}

.tip-banner__border-bottom {
    bottom: 0;
}

.tip-banner__icon {
    width: 44px;
    height: 44px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #777;
}

.tip-banner__icon svg {
    width: 22px;
    height: 22px;
}

.tip-banner p {
    max-width: 800px;
    margin: 0 auto;
    font-size: clamp(1rem, 2vw, 1.2rem);
    line-height: 2.1;
    font-weight: 300;
    color: #ccc;
}

.tip-banner strong {
    font-weight: 700;
    color: #fff;
}

/* ═══════════ SECTION HEADINGS ═══════════ */
.section-heading {
    text-align: center;
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    margin-bottom: 50px;
    letter-spacing: -.02em;
    position: relative;
}

.section-heading::after {
    content: '';
    display: block;
    width: 48px;
    height: 3px;
    background: #333;
    margin: 14px auto 0;
    border-radius: 100px;
}

/* ═══════════ ACHIEVEMENTS ═══════════ */
.section-achievements {
    padding: 7rem 0;
    background: linear-gradient(170deg, #0a0a0a 0%, #141414 50%, #0e0e0e 100%);
    position: relative;
    overflow: hidden;
}

.achievements-bg-dots {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px);
    background-size: 28px 28px;
    pointer-events: none;
}

/* Achievements heading */
.achievements-heading {
    text-align: center;
    margin-bottom: 3.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.achievements-heading__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #888;
    margin-bottom: 0.5rem;
}

.achievements-heading__icon svg {
    width: 24px;
    height: 24px;
}

.achievements-heading__text {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.02em;
}

.achievements-heading__line {
    display: block;
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #555, transparent);
}

/* Stats grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    text-align: center;
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    transition: transform 400ms cubic-bezier(.25, .46, .45, .94);
}

.stat-card:hover {
    transform: translateY(-6px);
}

/* Glow on hover */
.stat-card__glow {
    position: absolute;
    inset: -1px;
    border-radius: 19px;
    background: conic-gradient(from var(--angle, 0deg), transparent 30%, rgba(255, 255, 255, .12) 50%, transparent 70%);
    animation: borderSpin 4s linear infinite;
    z-index: 0;
    opacity: 0;
    transition: opacity 400ms ease;
}

.stat-card:hover .stat-card__glow {
    opacity: 1;
}

.stat-card__content {
    position: relative;
    z-index: 1;
    padding: 2.25rem 1.5rem;
    border-radius: 18px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: border-color 400ms ease, background 400ms ease;
}

.stat-card:hover .stat-card__content {
    border-color: rgba(255, 255, 255, .16);
    background: rgba(255, 255, 255, .06);
}

.stat-num {
    display: block;
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    letter-spacing: -.02em;
    transition: transform 300ms ease;
}

.stat-card:hover .stat-num {
    transform: scale(1.08);
}

.stat-divider {
    display: block;
    width: 32px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #555, transparent);
    margin: 14px auto;
    border-radius: 100px;
    transition: width 300ms ease;
}

.stat-card:hover .stat-divider {
    width: 48px;
    background: linear-gradient(90deg, transparent, #999, transparent);
}

.stat-label {
    font-size: .92rem;
    color: #888;
    line-height: 1.6;
    font-weight: 400;
    transition: color 300ms ease;
}

.stat-card:hover .stat-label {
    color: #ccc;
}

/* ═══════════ CONSULTATIONS ═══════════ */
.section-consult {
    padding: 6rem 0;
    background: linear-gradient(170deg, #111 0%, #181818 50%, #111 100%);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.consult-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, .02) 0%, transparent 60%);
    pointer-events: none;
}

.consult-heading {
    text-align: center;
    margin-bottom: 3rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.consult-heading__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #888;
    margin-bottom: 0.5rem;
}

.consult-heading__icon svg {
    width: 24px;
    height: 24px;
}

.consult-heading__text {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.02em;
}

.consult-heading__line {
    display: block;
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #555, transparent);
}

.consult-card {
    max-width: 800px;
    margin: 0 auto;
    padding: 2.5rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: border-color 400ms ease;
}

.consult-card:hover {
    border-color: rgba(255, 255, 255, .15);
}

.consult-body {
    max-width: 720px;
    margin: 0 auto;
    font-size: 1.05rem;
    color: #bbb;
    line-height: 2.1;
    text-align: center;
}

/* ═══════════ VOLUNTEERING ═══════════ */
.section-volunteer {
    padding: 7rem 0;
    background: linear-gradient(165deg, #0a0a0a 0%, #131313 50%, #0d0d0d 100%);
    position: relative;
    overflow: hidden;
}

.volunteer-bg {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255, 255, 255, .03) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

.volunteer-heading {
    text-align: center;
    margin-bottom: 3.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.volunteer-heading__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #888;
    margin-bottom: 0.5rem;
}

.volunteer-heading__icon svg {
    width: 24px;
    height: 24px;
}

.volunteer-heading__text {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.02em;
}

.volunteer-heading__line {
    display: block;
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #555, transparent);
}

.volunteer-card {
    max-width: 800px;
    margin: 0 auto;
}

.volunteer-card__inner {
    padding: 2.5rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    text-align: right;
    transition: border-color 400ms ease;
}

.volunteer-card__inner:hover {
    border-color: rgba(255, 255, 255, .15);
}

.volunteer-card__inner p {
    font-size: 1.05rem;
    color: #bbb;
    margin-bottom: 16px;
    line-height: 2.1;
}

.volunteer-card__inner ul {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
}

.volunteer-card__inner ul li {
    position: relative;
    padding-right: 24px;
    margin-bottom: 12px;
    font-size: 1.02rem;
    color: #bbb;
    line-height: 1.9;
    transition: color 300ms ease;
}

.volunteer-card__inner ul li:hover {
    color: #fff;
}

.volunteer-card__inner ul li::before {
    content: '';
    position: absolute;
    right: 0;
    top: 12px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #444;
    border: 1.5px solid #666;
    transition: all 300ms ease;
}

.volunteer-card__inner ul li:hover::before {
    background: #fff;
    border-color: #fff;
    box-shadow: 0 0 8px rgba(255, 255, 255, .25);
}

.volunteer-closing {
    font-style: italic;
    color: #888 !important;
}

/* ═══════════════ CV SECTIONS (Premium Grid) ═══════════════ */
.section-cv {
    padding: 7rem 0;
    position: relative;
    z-index: 2;
    background: linear-gradient(165deg, #080808 0%, #111 40%, #0d0d0d 100%);
    overflow: hidden;
}

.cv-bg-pattern {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .02) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 255, 255, .015) 0%, transparent 50%);
    pointer-events: none;
}

/* Section heading */
.cv-section-heading {
    text-align: center;
    margin-bottom: 4rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.cv-section-heading__label {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    letter-spacing: -.02em;
}

.cv-section-heading__line {
    display: block;
    width: 60px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #666, transparent);
}

/* Grid */
.cv-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.75rem;
    width: 100%;
}

/* Card */
.cv-card {
    position: relative;
    border-radius: 20px;
    text-align: right;
    transition: transform 400ms cubic-bezier(.25, .46, .45, .94);
}

.cv-card--half {
    grid-column: span 1;
}

.cv-card--full {
    grid-column: span 2;
}

.cv-card:hover {
    transform: translateY(-6px);
}

/* Animated border glow */
.cv-card__glow {
    position: absolute;
    inset: -1px;
    border-radius: 21px;
    background: conic-gradient(from var(--angle, 0deg), transparent 30%, rgba(255, 255, 255, .12) 50%, transparent 70%);
    animation: borderSpin 4s linear infinite;
    z-index: 0;
    opacity: 0;
    transition: opacity 400ms ease;
}

.cv-card:hover .cv-card__glow {
    opacity: 1;
}

.cv-card__inner {
    position: relative;
    z-index: 1;
    padding: 2.25rem;
    border-radius: 20px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    height: 100%;
    transition: border-color 400ms ease, background 400ms ease;
}

.cv-card:hover .cv-card__inner {
    border-color: rgba(255, 255, 255, .15);
    background: rgba(255, 255, 255, .05);
}

/* Card header */
.cv-header {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, .07);
}

.cv-icon-wrap {
    width: 56px;
    height: 56px;
    min-width: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    transition: all 350ms ease;
}

.cv-icon-wrap svg {
    width: 26px;
    height: 26px;
}

.cv-card:hover .cv-icon-wrap {
    background: #fff;
    color: #111;
    border-color: #fff;
    box-shadow: 0 0 24px rgba(255, 255, 255, .1);
}

.cv-card-num {
    display: block;
    font-size: .75rem;
    font-weight: 600;
    color: #555;
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 2px;
    font-family: 'Courier New', monospace;
}

.cv-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -.01em;
    line-height: 1.3;
}

/* Skill chips */
.skills-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
}

.skill-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1.15rem;
    border-radius: 100px;
    font-size: .92rem;
    font-weight: 500;
    color: #d4d4d4;
    background: rgba(255, 255, 255, .04);
    border: 1px solid rgba(255, 255, 255, .1);
    transition: all 300ms cubic-bezier(.25, .46, .45, .94);
    cursor: default;
}

.skill-chip:hover {
    background: #fff;
    color: #111;
    border-color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, .08);
}

.skill-chip__dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #666;
    transition: background 300ms ease;
}

.skill-chip:hover .skill-chip__dot {
    background: #111;
}

/* Timeline for experiences / certifications / media */
.cv-timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.cv-timeline__item {
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
    position: relative;
}

.cv-timeline__marker {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    padding-top: 6px;
}

.cv-timeline__dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #333;
    border: 2px solid #666;
    flex-shrink: 0;
    transition: all 300ms ease;
    z-index: 1;
}

.cv-timeline__item:hover .cv-timeline__dot {
    background: #fff;
    border-color: #fff;
    box-shadow: 0 0 10px rgba(255, 255, 255, .25);
}

.cv-timeline__line {
    width: 2px;
    flex: 1;
    min-height: 24px;
    background: linear-gradient(180deg, rgba(255, 255, 255, .1), rgba(255, 255, 255, .03));
}

.cv-timeline__item:last-child .cv-timeline__line {
    display: none;
}

.cv-timeline__content {
    padding-bottom: 1.75rem;
    flex: 1;
}

.cv-timeline__item:last-child .cv-timeline__content {
    padding-bottom: 0;
}

.cv-timeline__content p {
    color: #d4d4d4;
    font-size: 1rem;
    line-height: 1.75;
    margin: 0;
    text-align: right;
    font-weight: 400;
    transition: color 300ms ease;
}

.cv-timeline__item:hover .cv-timeline__content p {
    color: #fff;
}

.cv-type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: rgba(255, 255, 255, .08);
    color: #bbb;
    font-weight: 600;
    font-size: .8rem;
    border-radius: 6px;
    border: 1px solid rgba(255, 255, 255, .12);
    margin-bottom: 0.5rem;
    letter-spacing: .02em;
    transition: all 300ms ease;
}

.cv-timeline__item:hover .cv-type-badge {
    background: rgba(255, 255, 255, .14);
    color: #fff;
    border-color: rgba(255, 255, 255, .2);
}

/* CV responsive */
@media screen and (max-width: 768px) {
    .cv-grid {
        grid-template-columns: 1fr;
    }

    .cv-card--half,
    .cv-card--full {
        grid-column: span 1;
    }

    .section-cv {
        padding: 4rem 0;
    }

    .cv-card__inner {
        padding: 1.5rem;
    }

    .cv-section-heading {
        margin-bottom: 2.5rem;
    }
}

/* ═══════════ FOOTER ═══════════ */
.site-footer {
    position: relative;
    background: #0a0a0a;
    color: #fff;
    text-align: center;
    padding: 0;
    overflow: hidden;
}

.footer-glow {
    position: absolute;
    top: -80px;
    left: 50%;
    transform: translateX(-50%);
    width: 600px;
    height: 300px;
    background: radial-gradient(ellipse, rgba(255, 255, 255, .04) 0%, transparent 70%);
    pointer-events: none;
}

.footer-top-border {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .12), transparent);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.site-footer>.footer-container {
    padding-top: 5rem;
    padding-bottom: 3rem;
}

/* Header */
.footer-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin-bottom: .6rem;
}

.footer-header__icon {
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

.footer-header__icon svg {
    width: 22px;
    height: 22px;
}

.footer-heading {
    font-size: 1.8rem;
    font-weight: 800;
    color: #f1f1f1;
    letter-spacing: -.02em;
}

.footer-heading__line {
    flex: 1;
    max-width: 120px;
    height: 1px;
    background: linear-gradient(90deg, rgba(255, 255, 255, .15), transparent);
}

.footer-subtitle {
    text-align: center;
    color: #888;
    font-size: .95rem;
    margin-bottom: 2.5rem;
}

/* Contact Cards */
.footer-contact {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 2.5rem;
}

.contact-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 28px 36px;
    min-width: 220px;
    background: rgba(255, 255, 255, .03);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 16px;
    text-decoration: none;
    color: #e0e0e0;
    transition: all .35s cubic-bezier(.4, 0, .2, 1);
}

.contact-card:hover {
    background: rgba(255, 255, 255, .07);
    border-color: rgba(255, 255, 255, .18);
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0, 0, 0, .3);
}

.contact-card__icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(255, 255, 255, .06);
    border: 1px solid rgba(255, 255, 255, .1);
    color: #ccc;
    transition: all .3s ease;
}

.contact-card__icon svg {
    width: 22px;
    height: 22px;
}

.contact-card:hover .contact-card__icon {
    background: #fff;
    color: #111;
    border-color: #fff;
    box-shadow: 0 0 20px rgba(255, 255, 255, .1);
}

.contact-card__label {
    font-size: .82rem;
    color: #888;
    font-weight: 500;
    letter-spacing: .02em;
}

.contact-card__value {
    font-size: 1rem;
    font-weight: 600;
    color: #f1f1f1;
}

/* Social */
.footer-social {
    margin-bottom: 0;
}

.footer-social__label {
    font-size: .88rem;
    color: #777;
    margin-bottom: 16px;
    font-weight: 400;
}

.social-row {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 14px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    color: #aaa;
    transition: all .3s cubic-bezier(.4, 0, .2, 1);
}

.social-link svg {
    width: 18px;
    height: 18px;
}

.social-link:hover {
    background: #fff;
    color: #111;
    border-color: #fff;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 6px 20px rgba(255, 255, 255, .08);
}

.social-link:focus-visible {
    outline: 2px solid rgba(255, 255, 255, .4);
    outline-offset: 3px;
}

/* Footer Bottom Bar */
.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, .06);
    padding: 1.5rem 0;
}

.footer-bottom .footer-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
}

.footer-tagline {
    font-size: .85rem;
    color: #777;
    font-weight: 300;
}

.footer-copy {
    font-size: .82rem;
    color: #555;
    font-weight: 300;
}

@media (max-width: 640px) {
    .footer-bottom .footer-container {
        justify-content: center;
        text-align: center;
    }

    .contact-card {
        min-width: 0;
        flex: 1;
        padding: 20px 16px;
    }

    .footer-heading {
        font-size: 1.4rem;
    }
}

/* ═══════════ RESPONSIVE ═══════════ */
@media screen and (max-width: 1024px) {
    .hero-inner {
        gap: 40px;
    }

    .hero-image {
        width: 40%;
        min-height: 360px;
    }
}

@media screen and (max-width: 768px) {
    .hero-inner {
        flex-direction: column-reverse;
        text-align: center;
    }

    .hero-text {
        text-align: center;
    }

    .hero-image {
        width: 85%;
        max-width: 450px;
        min-height: 320px;
        align-self: center;
        margin: 0 auto;
        -webkit-mask-image: linear-gradient(to bottom, #000 75%, transparent 100%);
        mask-image: linear-gradient(to bottom, #000 75%, transparent 100%);
    }

    .hero-img {
        -webkit-mask-image: none;
        mask-image: none;
    }

    .hero-floating-card {
        display: none;
    }

    .expertise-card--featured {
        transform: none;
    }

    .expertise-card--featured:hover {
        transform: translateY(-8px);
    }

    .cards-grid {
        flex-direction: column;
        align-items: stretch;
    }

    .expertise-card {
        flex: 1 1 auto;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .volunteer-card__inner {
        padding: 1.75rem;
    }

    .consult-card {
        padding: 1.75rem;
    }
}

@media screen and (max-width: 480px) {
    .hero {
        padding: 90px 20px 60px;
    }

    .hero-title {
        font-size: 1.7rem;
    }

    .hero-sub br {
        display: none;
    }

    .hero-image {
        width: 95%;
        min-height: 260px;
        max-width: 320px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .nav-inner {
        padding: 12px 16px;
    }

    .nav-btn {
        padding: 8px 18px;
        font-size: .82rem;
    }

    .footer-contact {
        flex-direction: column;
    }
}

/* ═══════════ CAREEM FONT ═══════════ */
.homepage {
    font-family: 'Careem', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ═══════════ CONTENT SECTIONS (Articles / Videos) ═══════════ */
.section-content {
    padding: 100px 0;
    background: #fff;
}

.section-content--dark {
    background: #0a0a0a;
    color: #fff;
}

.section-content--dark .section-heading {
    color: #fff;
}

.section-content--dark .section-heading::after {
    background: #555;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* ── Animated border card ── */
.content-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
}

.content-card__border {
    position: absolute;
    inset: 0;
    border-radius: 16px;
    padding: 2px;
    background: linear-gradient(135deg, #ccc, #999, #666, #999, #ccc);
    background-size: 300% 300%;
    animation: borderShift 4s ease-in-out infinite;
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    transition: background-size 300ms ease;
}

.content-card:hover .content-card__border {
    background-size: 150% 150%;
    animation-duration: 1.5s;
}

@keyframes borderShift {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.content-card__inner {
    border-radius: 16px;
    overflow: hidden;
    background: #f9f9f9;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 250ms cubic-bezier(.4, 0, .2, 1), box-shadow 250ms cubic-bezier(.4, 0, .2, 1);
}

.content-card__link {
    text-decoration: none;
    color: inherit;
}

.section-content--dark .content-card__inner {
    background: #1a1a1a;
}

.content-card:hover .content-card__inner {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, .12);
}

.content-card__img-wrap {
    height: 180px;
    overflow: hidden;
}

.content-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 400ms ease;
}

.content-card:hover .content-card__img {
    transform: scale(1.06);
}

.content-card__video-wrap {
    height: 180px;
    width: 100%;
    position: relative;
    overflow: hidden;
}

.content-card__video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.content-card__img-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
}

.section-content--dark .content-card__img-placeholder {
    background: #222;
}

.content-card__body {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.content-card__title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.4;
}

.section-content--dark .content-card__title {
    color: #fff;
}

.content-card__excerpt {
    font-size: .92rem;
    color: #777;
    line-height: 1.7;
    flex: 1;
}

.section-content--dark .content-card__excerpt {
    color: #aaa;
}

.content-card__meta {
    display: flex;
    justify-content: space-between;
    font-size: .8rem;
    color: #999;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #eee;
}

.section-content--dark .content-card__meta {
    border-color: #333;
}

.content-card__cta {
    display: inline-block;
    margin-top: 14px;
    padding: 8px 18px;
    border-radius: 100px;
    background: #0a0a0a;
    color: #fff;
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: transform 200ms ease, box-shadow 200ms ease;
}

.section-content--dark .content-card__cta {
    background: #fff;
    color: #0a0a0a;
}

.content-card__cta:hover {
    transform: scale(1.04);
    box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
}

/* ── Pagination ── */
.content-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 40px;
}

.pg-btn {
    padding: 10px 24px;
    border-radius: 100px;
    border: 1px solid #ddd;
    background: #fff;
    color: #0a0a0a;
    font-weight: 600;
    font-size: .9rem;
    cursor: pointer;
    transition: all 200ms ease;
}

.section-content--dark .pg-btn {
    background: #1a1a1a;
    border-color: #333;
    color: #fff;
}

.pg-btn:hover:not(:disabled) {
    background: #0a0a0a;
    color: #fff;
    border-color: #0a0a0a;
}

.section-content--dark .pg-btn:hover:not(:disabled) {
    background: #fff;
    color: #0a0a0a;
}

.pg-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
}

.pg-info {
    font-size: .9rem;
    color: #999;
    font-weight: 500;
}

/* ── Content responsive ── */
@media screen and (max-width: 768px) {
    .content-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media screen and (max-width: 480px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .section-content {
        padding: 60px 0;
    }
}

/* ═══════════ BOOKS CAROUSEL ═══════════ */
.section-books {
    padding: 7rem 0;
    background: linear-gradient(170deg, #080808 0%, #111 40%, #0d0d0d 100%);
    position: relative;
    overflow: hidden;
}

.books-bg {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(242, 236, 230, .03) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

.books-heading {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .5rem;
    margin-bottom: 3rem;
    text-align: center;
}

.books-heading__icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(242, 236, 230, .06);
    border: 1px solid rgba(242, 236, 230, .08);
    color: #E0D6CC;
    margin-bottom: .5rem;
}

.books-heading__icon svg {
    width: 24px;
    height: 24px;
}

.books-heading__text {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #FFFDFB;
    letter-spacing: -.02em;
}

.books-heading__line {
    width: 50px;
    height: 3px;
    border-radius: 100px;
    background: linear-gradient(90deg, transparent, #E0D6CC, transparent);
}

/* Carousel Container */
.books-carousel {
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(242, 236, 230, .15) transparent;
    padding-bottom: 1rem;
}

.books-carousel::-webkit-scrollbar {
    height: 6px;
}

.books-carousel::-webkit-scrollbar-track {
    background: transparent;
}

.books-carousel::-webkit-scrollbar-thumb {
    background: rgba(242, 236, 230, .15);
    border-radius: 100px;
}

.books-track {
    display: flex;
    gap: 1.5rem;
    padding: 1rem .5rem;
    min-width: min-content;
}

/* Book Slide */
.book-slide {
    flex-shrink: 0;
    width: 180px;
    text-decoration: none;
    border-radius: 14px;
    overflow: hidden;
    background: rgba(242, 236, 230, .04);
    border: 1px solid rgba(242, 236, 230, .08);
    transition: transform .4s cubic-bezier(.25, .46, .45, .94),
        box-shadow .4s ease,
        border-color .3s ease;
    cursor: pointer;
}

.book-slide:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 20px 50px rgba(0, 0, 0, .35), 0 0 30px rgba(242, 236, 230, .08);
    border-color: rgba(242, 236, 230, .2);
}

.book-slide__img-wrap {
    aspect-ratio: 3 / 4;
    overflow: hidden;
    border-radius: 13px;
}

.book-slide__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s cubic-bezier(.25, .46, .45, .94);
}

.book-slide:hover .book-slide__img {
    transform: scale(1.06);
}

.book-slide__placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: #1a1a1a;
    color: #E0D6CC;
}

@media (max-width: 640px) {
    .section-books {
        padding: 4rem 0 3rem;
    }

    .book-slide {
        width: 140px;
    }

    .books-track {
        gap: 1rem;
    }
}
</style>
