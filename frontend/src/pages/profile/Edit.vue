<script setup>
import { ref, onMounted } from 'vue'
import { useProfileStore } from '../../stores/profile'
import AppSpinner from '../../components/core/AppSpinner.vue'
const store = useProfileStore()
const form = ref({ name: '', email: '', phone: '', job_title: '', professional_summary: '', social_links: {} })
const errors = ref({})
const successMsg = ref('')

function handleSubmit() {
    errors.value = {}; successMsg.value = ''
    store.updateProfile(form.value).then(r => {
        if (r.success) successMsg.value = 'تم حفظ الملف الشخصي بنجاح'
        else if (r.errors) errors.value = r.errors
    })
}

onMounted(() => {
    store.fetchProfile().then(r => {
        if (r.success) {
            form.value = {
                name: r.data.name || '',
                email: r.data.email || '',
                phone: r.data.phone || '',
                job_title: r.data.job_title || '',
                professional_summary: r.data.professional_summary || '',
                social_links: r.data.social_links || {}
            }
        }
    })
})
</script>
<template>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">الملف الشخصي</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">تعديل بيانات الملف الشخصي</p>
        </div>

        <div v-if="store.loading && !form.name" class="flex items-center justify-center py-12">
            <AppSpinner size="lg" text="جاري التحميل..." />
        </div>

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
            <div v-if="successMsg"
                class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 text-green-800 dark:text-green-300">
                {{ successMsg }}</div>

            <div class="card p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">البيانات الأساسية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الاسم</label><input
                            v-model="form.name" type="text" class="input" />
                        <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
                    </div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">البريد
                            الإلكتروني</label><input v-model="form.email" type="email" class="input" dir="ltr" />
                        <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
                    </div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">رقم
                            الهاتف</label><input v-model="form.phone" type="text" class="input" dir="ltr" />
                        <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone[0] }}</p>
                    </div>
                    <div><label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">المسمى
                            الوظيفي</label><input v-model="form.job_title" type="text" class="input" />
                        <p v-if="errors.job_title" class="text-red-500 text-sm mt-1">{{ errors.job_title[0] }}</p>
                    </div>
                    <div class="md:col-span-2"><label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الملخص
                            المهني</label><textarea v-model="form.professional_summary" class="input min-h-[150px]"
                            rows="6"></textarea>
                        <p v-if="errors.professional_summary" class="text-red-500 text-sm mt-1">{{
                            errors.professional_summary[0] }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-4">
                <button type="submit" :disabled="store.loading" class="btn btn-primary flex items-center gap-2">
                    <svg v-if="store.loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    {{ store.loading ? 'جاري الحفظ...' : 'حفظ التغييرات' }}
                </button>
            </div>
        </form>
    </div>
</template>
