```
<template>
    <nav class="navbar" :class="{ 'navbar--scrolled': isScrolled }" role="navigation" aria-label="القائمة الرئيسية">
        <div class="nav-inner">

            <!-- Desktop Links -->
            <div class="nav-links">
                <button class="nav-link" @click="scrollTo('expertise')">الخبرات</button>
                <button class="nav-link" @click="scrollTo('achievements')">الإنجازات</button>
                <button class="nav-link" @click="scrollTo('volunteer')">التطوع</button>
                <button class="nav-link" @click="scrollTo('articles')">المقالات</button>
                <button class="nav-link" @click="scrollTo('videos')">الظهور الأعلامي</button>
                <button class="nav-link" @click="scrollTo('gallery')">معرض الصور</button>
                <button class="nav-link" @click="scrollTo('contact')">التواصل</button>
            </div>

            <div class="nav-actions">
                <!-- Hamburger Button (Mobile Only) -->
                <button class="hamburger-btn" @click="toggleMenu" :aria-expanded="isMenuOpen"
                    aria-controls="mobile-menu" aria-label="تبديل القائمة">
                    <span class="hamburger-line" :class="{ 'line-top': isMenuOpen }"></span>
                    <span class="hamburger-line" :class="{ 'line-middle': isMenuOpen }"></span>
                    <span class="hamburger-line" :class="{ 'line-bottom': isMenuOpen }"></span>
                </button>
            </div>
        </div>

        <!-- Overlay -->
        <transition name="fade">
            <div v-if="isMenuOpen" class="menu-overlay" @click="closeMenu"></div>
        </transition>

        <!-- Side Drawer Menu -->
        <transition name="slide">
            <div id="mobile-menu" v-if="isMenuOpen" class="menu-drawer" role="dialog" aria-modal="true"
                aria-label="قائمة التصفح" @keydown.esc="closeMenu">
                <div class="drawer-header">
                    <span class="drawer-logo">{{ logoText }}</span>
                    <button class="close-btn" @click="closeMenu" aria-label="إغلاق القائمة">✕</button>
                </div>
                <div class="drawer-links">
                    <button class="drawer-link" @click="scrollTo('expertise')">الخبرات</button>
                    <button class="drawer-link" @click="scrollTo('achievements')">الإنجازات</button>
                    <button class="drawer-link" @click="scrollTo('consult')">الاستشارات</button>
                    <button class="drawer-link" @click="scrollTo('volunteer')">التطوع</button>
                    <button class="drawer-link" @click="scrollTo('articles')">المقالات</button>
                    <button class="drawer-link" @click="scrollTo('videos')">الفيديوهات</button>
                    <button class="drawer-link" @click="scrollTo('contact')">التواصل</button>
                </div>
            </div>
        </transition>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

defineProps({
    logoText: { type: String, default: 'أوس الفوزان' }
})

const router = useRouter()
const authStore = useAuthStore()

const isScrolled = ref(false)
const isMenuOpen = ref(false)

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50
}

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
    if (isMenuOpen.value) {
        document.body.style.overflow = 'hidden' // Prevent bg scrolling
    } else {
        document.body.style.overflow = ''
    }
}

const closeMenu = () => {
    isMenuOpen.value = false
    document.body.style.overflow = ''
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isMenuOpen.value) closeMenu()
    })
    handleScroll()
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    document.body.style.overflow = ''
})

const handleAdminClick = () => {
    if (authStore.isAuthenticated) {
        router.push({ name: 'home-settings' })
    } else {
        router.push({ name: 'login' })
    }
    closeMenu()
}

const scrollTo = (id) => {
    closeMenu() // Auto-close menu
    const el = document.getElementById(id)
    if (el) {
        const headerOffset = 70;
        const elementPosition = el.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    }
}
</script>

<style scoped>
.navbar {
    position: fixed;
    inset-inline: 0;
    top: 0;
    z-index: 1000;
    /* Ensure on top */
    background: transparent;
    border-bottom: 1px solid transparent;
    transition: all 300ms cubic-bezier(.4, 0, .2, 1);
}

.navbar--scrolled {
    background: rgba(255, 255, 255, .82);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-bottom: 1px solid #e0e0e0;
}

.navbar:not(.navbar--scrolled) .nav-logo {
    color: #ffffff;
}

.navbar:not(.navbar--scrolled) .nav-link {
    color: #e2e8f0;
}

.navbar:not(.navbar--scrolled) .nav-link:hover {
    color: #ffffff;
}

.navbar:not(.navbar--scrolled) .nav-btn {
    background: #ffffff;
    color: #0a0a0a;
}

.navbar:not(.navbar--scrolled) .hamburger-line {
    background-color: #ffffff;
}

.nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 14px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo {
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #0a0a0a;
    transition: color 0.3s ease;
}

/* ── Desktop Links ── */
.nav-links {
    display: none;
}

.nav-link {
    background: none;
    border: none;
    font-size: 0.95rem;
    font-weight: 600;
    color: #4b5563;
    cursor: pointer;
    transition: color 0.2s;
    font-family: inherit;
    padding: 0.2rem 0;
}

.nav-link:hover {
    color: #0a0a0a;
}

@media (min-width: 992px) {
    .nav-links {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        flex: 1;
        justify-content: center;
    }
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

/* ── Button ── */
.nav-btn {
    padding: 8px 20px;
    border-radius: 100px;
    border: none;
    background: #0a0a0a;
    color: #fff;
    font-weight: 600;
    font-size: .85rem;
    cursor: pointer;
    transition: transform 250ms cubic-bezier(.4, 0, .2, 1), box-shadow 250ms cubic-bezier(.4, 0, .2, 1);
    display: none;
    /* Hide on very small screens if desired, but we'll show it */
}

@media (min-width: 480px) {
    .nav-btn {
        display: inline-block;
    }
}

.nav-btn:hover {
    transform: scale(1.04);
}

/* ── Hamburger Icon ── */
.hamburger-btn {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 28px;
    height: 20px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    z-index: 1001;
    /* Above overlay */
}

@media (min-width: 992px) {
    .hamburger-btn {
        display: none;
    }
}

.hamburger-line {
    display: block;
    height: 2px;
    width: 100%;
    background-color: #0a0a0a;
    /* Default color */
    border-radius: 2px;
    transition: transform 0.3s ease, opacity 0.3s ease, background-color 0.3s ease;
}

.line-top {
    transform: translateY(9px) rotate(45deg);
}

.line-middle {
    opacity: 0;
}

.line-bottom {
    transform: translateY(-9px) rotate(-45deg);
}

/* ── Overlay ── */
.menu-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 990;
    backdrop-filter: blur(2px);
}

/* ── Side Drawer Menu ── */
.menu-drawer {
    position: fixed;
    top: 0;
    right: 0;
    height: 100vh;
    width: 280px;
    background: #ffffff;
    box-shadow: -4px 0 24px rgba(0, 0, 0, 0.1);
    z-index: 995;
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
}

.drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 1.25rem;
    margin-bottom: 1.5rem;
}

.drawer-logo {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0a0a0a;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #64748b;
    cursor: pointer;
    font-weight: bold;
    padding: 0.25rem;
}

.close-btn:hover {
    color: #0a0a0a;
}

.drawer-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.drawer-link {
    background: none;
    border: none;
    text-align: right;
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    border-radius: 0.5rem;
    transition: background-color 0.2s, color 0.2s;
}

.drawer-link:hover {
    background-color: #f8fafc;
    color: #0a0a0a;
}

/* ── Transitions ── */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(100%);
}
</style>
