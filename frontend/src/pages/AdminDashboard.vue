<template>
    <div class="space-y-8 p-6" dir="rtl">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">لوحة التحكم</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">إحصائيات الموقع</p>
            </div>
            <button @click="loadStats"
                class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors text-sm font-medium flex items-center gap-2"
                :disabled="loading">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="{ 'animate-spin': loading }" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                </svg>
                تحديث
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading && !stats" class="flex items-center justify-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-3 border-sky-600"></div>
        </div>

        <!-- Stats Cards -->
        <template v-if="stats">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Total Visits -->
                <div class="stat-card stat-card--sky">
                    <div class="stat-card__glow"></div>
                    <div class="stat-card__content">
                        <div class="stat-card__icon-wrap stat-card__icon--sky">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="stat-card__label">إجمالي الزيارات</p>
                            <p class="stat-card__value">{{ animatedTotalVisits }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today Visits -->
                <div class="stat-card stat-card--emerald">
                    <div class="stat-card__glow"></div>
                    <div class="stat-card__content">
                        <div class="stat-card__icon-wrap stat-card__icon--emerald">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="stat-card__label">زيارات اليوم</p>
                            <p class="stat-card__value">{{ animatedTodayVisits }}</p>
                        </div>
                    </div>
                </div>

                <!-- Articles -->
                <div class="stat-card stat-card--violet">
                    <div class="stat-card__glow"></div>
                    <div class="stat-card__content">
                        <div class="stat-card__icon-wrap stat-card__icon--violet">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="stat-card__label">المقالات</p>
                            <p class="stat-card__value">{{ animatedArticles }}
                                <span class="stat-card__sub">/ {{ stats.articles_published }} منشور</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Videos -->
                <div class="stat-card stat-card--amber">
                    <div class="stat-card__glow"></div>
                    <div class="stat-card__content">
                        <div class="stat-card__icon-wrap stat-card__icon--amber">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="stat-card__label">الفيديوهات</p>
                            <p class="stat-card__value">{{ animatedVideos }}
                                <span class="stat-card__sub">/ {{ stats.videos_published }} منشور</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="quick-actions__inner">
                    <div class="quick-actions__text">
                        <h3>إدارة المحتوى</h3>
                        <p>يمكنك إدارة المقالات والفيديوهات ومعرض الصور من خلال القائمة الجانبية</p>
                    </div>
                    <div class="quick-actions__btns">
                        <RouterLink :to="{ name: 'articles' }" class="qa-btn qa-btn--primary">المقالات</RouterLink>
                        <RouterLink :to="{ name: 'videos' }" class="qa-btn qa-btn--secondary">الفيديوهات</RouterLink>
                        <RouterLink :to="{ name: 'gallery' }" class="qa-btn qa-btn--secondary">معرض الصور</RouterLink>
                    </div>
                </div>
            </div>
        </template>

        <!-- Error -->
        <div v-if="error" class="text-center py-12 text-red-500 dark:text-red-400">
            <p>{{ error }}</p>
            <button @click="loadStats"
                class="mt-4 px-6 py-2 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition-colors">
                إعادة المحاولة
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import api from '../services/api'

const stats = ref(null)
const loading = ref(false)
const error = ref(null)

// Animated counter values
const animatedTotalVisits = ref(0)
const animatedTodayVisits = ref(0)
const animatedArticles = ref(0)
const animatedVideos = ref(0)

function animateValue(refVal, target, duration = 1200) {
    const start = refVal.value
    const diff = target - start
    if (diff === 0) return
    const startTime = performance.now()

    function step(currentTime) {
        const elapsed = currentTime - startTime
        const progress = Math.min(elapsed / duration, 1)
        // Ease-out cubic
        const eased = 1 - Math.pow(1 - progress, 3)
        refVal.value = Math.round(start + diff * eased)
        if (progress < 1) requestAnimationFrame(step)
    }
    requestAnimationFrame(step)
}

async function loadStats() {
    loading.value = true
    error.value = null
    try {
        const res = await api.get('/stats/dashboard')
        stats.value = res.data.data
        // Animate counters
        animateValue(animatedTotalVisits, stats.value.total_visits)
        animateValue(animatedTodayVisits, stats.value.today_visits)
        animateValue(animatedArticles, stats.value.articles_count)
        animateValue(animatedVideos, stats.value.videos_count)
    } catch (e) {
        error.value = 'حدث خطأ أثناء تحميل الإحصائيات'
    } finally {
        loading.value = false
    }
}

onMounted(loadStats)
</script>

<style scoped>
/* ═══════════ STAT CARDS ═══════════ */
.stat-card {
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    background: #fff;
    border: 1px solid #e8e8e8;
    padding: 1.75rem;
    transition: all .35s cubic-bezier(.4, 0, .2, 1);
}

.dark .stat-card {
    background: #1e293b;
    border-color: #334155;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, .08);
}

.dark .stat-card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, .3);
}

.stat-card__glow {
    position: absolute;
    top: -24px;
    left: -24px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    filter: blur(40px);
    opacity: .15;
    transition: opacity .3s;
    pointer-events: none;
}

.stat-card:hover .stat-card__glow {
    opacity: .25;
}

.stat-card--sky .stat-card__glow {
    background: #0ea5e9;
}

.stat-card--emerald .stat-card__glow {
    background: #10b981;
}

.stat-card--violet .stat-card__glow {
    background: #8b5cf6;
}

.stat-card--amber .stat-card__glow {
    background: #f59e0b;
}

.stat-card__content {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.stat-card__icon-wrap {
    width: 52px;
    height: 52px;
    flex-shrink: 0;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .3s;
}

.stat-card__icon-wrap svg {
    width: 26px;
    height: 26px;
}

.stat-card__icon--sky {
    background: #e0f2fe;
    color: #0284c7;
}

.dark .stat-card__icon--sky {
    background: rgba(14, 165, 233, .15);
    color: #38bdf8;
}

.stat-card__icon--emerald {
    background: #d1fae5;
    color: #059669;
}

.dark .stat-card__icon--emerald {
    background: rgba(16, 185, 129, .15);
    color: #34d399;
}

.stat-card__icon--violet {
    background: #ede9fe;
    color: #7c3aed;
}

.dark .stat-card__icon--violet {
    background: rgba(139, 92, 246, .15);
    color: #a78bfa;
}

.stat-card__icon--amber {
    background: #fef3c7;
    color: #d97706;
}

.dark .stat-card__icon--amber {
    background: rgba(245, 158, 11, .15);
    color: #fbbf24;
}

.stat-card:hover .stat-card__icon-wrap {
    transform: scale(1.08);
}

.stat-card__label {
    font-size: .82rem;
    font-weight: 600;
    color: #94a3b8;
    letter-spacing: .02em;
    margin-bottom: 4px;
}

.stat-card__value {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}

.dark .stat-card__value {
    color: #f1f5f9;
}

.stat-card__sub {
    font-size: .78rem;
    font-weight: 500;
    color: #94a3b8;
}

/* ═══════════ QUICK ACTIONS ═══════════ */
.quick-actions {
    margin-top: 1rem;
}

.quick-actions__inner {
    background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
    border-radius: 20px;
    padding: 2rem 2.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}

.quick-actions__inner::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 0%, rgba(255, 255, 255, .12), transparent 60%);
    pointer-events: none;
}

.quick-actions__text {
    position: relative;
}

.quick-actions__text h3 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: .35rem;
}

.quick-actions__text p {
    color: rgba(255, 255, 255, .8);
    font-size: .92rem;
}

.quick-actions__btns {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
    position: relative;
}

.qa-btn {
    padding: .65rem 1.5rem;
    border-radius: 12px;
    font-size: .88rem;
    font-weight: 700;
    text-decoration: none;
    transition: all .25s;
    white-space: nowrap;
}

.qa-btn--primary {
    background: #fff;
    color: #4f46e5;
}

.qa-btn--primary:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
}

.qa-btn--secondary {
    background: rgba(255, 255, 255, .18);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, .25);
}

.qa-btn--secondary:hover {
    background: rgba(255, 255, 255, .3);
    transform: translateY(-2px);
}

@media (max-width: 640px) {
    .quick-actions__inner {
        flex-direction: column;
        text-align: center;
        padding: 1.75rem;
    }

    .quick-actions__btns {
        justify-content: center;
    }
}
</style>
