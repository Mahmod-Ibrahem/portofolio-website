<script setup>
import { computed } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()
const route = useRoute()

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: true
    },
    darkMode: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['toggle-sidebar', 'toggle-dark-mode', 'logout'])

// Navigation items
const navItems = computed(() => {
    const items = []

    // Dashboard
    items.push({
        name: 'admin-dashboard',
        label: 'لوحة التحكم',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>`
    })

    // Content section
    items.push({ label: 'المحتوى', isHeader: true })
    items.push({
        name: 'books',
        label: 'الكتب',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>`
    })
    items.push({
        name: 'articles',
        label: 'المقالات',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>`
    })
    items.push({
        name: 'videos',
        label: 'الفيديوهات',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>`
    })
    items.push({
        name: 'gallery',
        label: 'معرض الصور',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>`
    })

    // Settings section
    items.push({ label: 'الإعدادات', isHeader: true })

    items.push({
        name: 'home-settings',
        label: 'الصفحة الرئيسية',
        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>`
    })


    return items
})

// Role badge
const roleBadge = computed(() => {
    if (authStore.isAdmin) {
        return { label: 'مسؤول النظام', class: 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' }
    }
    return { label: 'موظف', class: 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' }
})
</script>

<template>
    <aside :class="[
        'fixed top-0 right-0 z-40 h-screen transition-transform duration-300',
        isOpen ? 'translate-x-0' : 'translate-x-full md:translate-x-0 md:w-20'
    ]" :style="{ width: isOpen ? '280px' : '' }">
        <div
            class="h-full flex flex-col bg-white dark:bg-dark-800 border-l border-slate-200 dark:border-dark-700 shadow-lg">
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-dark-700">
                <div v-if="isOpen" class="flex items-center gap-3">
                    <span class="text-lg font-bold text-slate-900 dark:text-white">أداره الكتب</span>
                </div>
                <button @click="emit('toggle-sidebar')"
                    class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-dark-700 text-slate-600 dark:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <template v-for="item in navItems" :key="item.label">
                    <!-- Header -->
                    <div v-if="item.isHeader" class="pt-4 pb-2 px-4">
                        <span v-if="isOpen" class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            {{ item.label }}
                        </span>
                        <div v-else class="h-px bg-slate-200 dark:bg-dark-700 mx-1"></div>
                    </div>

                    <!-- Link -->
                    <RouterLink v-else :to="{ name: item.name }" :class="[
                        'sidebar-link',
                        route.name === item.name || (item.name === 'users' && route.name?.startsWith('users')) ? 'active' : ''
                    ]">
                        <span v-html="item.icon"></span>
                        <span v-if="isOpen">{{ item.label }}</span>
                    </RouterLink>
                </template>
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-slate-200 dark:border-dark-700">
                <div v-if="isOpen" class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center">
                        <span class="text-sky-700 dark:text-sky-300 font-semibold">
                            {{ authStore.user?.name?.charAt(0) || 'U' }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                            {{ authStore.user?.name || 'المستخدم' }}
                        </p>
                        <span :class="['text-xs px-2 py-0.5 rounded-full', roleBadge.class]">
                            {{ roleBadge.label }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <!-- Dark Mode Toggle -->
                    <!-- <button @click="emit('toggle-dark-mode')"
                        class="flex-1 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-dark-700 text-slate-600 dark:text-slate-300 transition-colors"
                        :title="darkMode ? 'الوضع الفاتح' : 'الوضع الداكن'">
                        <svg v-if="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button> -->

                    <!-- Logout -->
                    <button @click="emit('logout')"
                        class="flex-1 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 transition-colors"
                        :class="!isOpen ? 'hidden' : ''" title="تسجيل الخروج">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </aside>
</template>
