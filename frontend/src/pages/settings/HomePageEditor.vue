<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">إعدادات الصفحة الرئيسية</h1>
                <p class="text-slate-600 dark:text-slate-400 mt-1">تعديل محتوى الصفحة الرئيسية للموقع</p>
            </div>
            <button @click="handleSave" :disabled="saving" class="btn btn-primary">
                {{ saving ? 'جاري الحفظ...' : 'حفظ التعديلات' }}
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-12">
            <div class="text-slate-500">جاري تحميل البيانات...</div>
        </div>

        <template v-else>

            <!-- Success Message -->
            <div v-if="successMsg"
                class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ successMsg }}
            </div>

            <!-- ═══════════ HERO SECTION ═══════════ -->
            <div class="card p-6 space-y-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white border-b pb-3">🏠 القسم الرئيسي (Hero)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">شارة الترحيب</label>
                        <input v-model="form.hero_badge" type="text" class="input" placeholder="مرحباً بك..." />
                        <p v-if="errors.hero_badge" class="form-error">{{ errors.hero_badge[0] }}</p>
                    </div>
                    <div>
                        <label class="form-label">الاسم الكامل</label>
                        <input v-model="form.hero_name" type="text" class="input" />
                        <p v-if="errors.hero_name" class="form-error">{{ errors.hero_name[0] }}</p>
                    </div>
                </div>

                <div>
                    <label class="form-label">الوصف الفرعي</label>
                    <textarea v-model="form.hero_subtitle" rows="3" class="input"
                        placeholder="طالب بالمرحلة الثانوية..."></textarea>
                    <p class="text-xs text-slate-400 mt-1">استخدم سطر جديد للفصل بين الأسطر</p>
                    <p v-if="errors.hero_subtitle" class="form-error">{{ errors.hero_subtitle[0] }}</p>
                </div>

                <div>
                    <label class="form-label">الاقتباس</label>
                    <textarea v-model="form.hero_quote" rows="2" class="input"
                        placeholder="طموحي لا سقف له..."></textarea>
                    <p v-if="errors.hero_quote" class="form-error">{{ errors.hero_quote[0] }}</p>
                </div>

                <!-- <div>
                    <label class="form-label">النص العائم</label>
                    <textarea v-model="form.hero_floating_text" rows="2" class="input"
                        placeholder="لتقديم استشارات..."></textarea>
                    <p v-if="errors.hero_floating_text" class="form-error">{{ errors.hero_floating_text[0] }}</p>
                </div> -->

                <div>
                    <label class="form-label">صورة البطل</label>
                    <input type="file" @change="onHeroImageChange" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100" />
                    <p v-if="errors.hero_image" class="form-error">{{ errors.hero_image[0] }}</p>
                    <div v-if="heroPreview || form.hero_image_url" class="mt-3">
                        <img :src="heroPreview || form.hero_image_url" alt="معاينة"
                            class="w-48 h-32 rounded-lg object-cover border" />
                    </div>
                </div>
            </div>

            <!-- ═══════════ EXPERTISE CARDS ═══════════ -->
            <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">⚡ بطاقات الخبرات</h2>
                    <button type="button" @click="addExpertise" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>

                <div v-for="(card, idx) in form.expertise_cards" :key="idx"
                    class="p-4 border rounded-lg space-y-3 relative bg-slate-50 dark:bg-slate-800/50">
                    <button type="button" @click="removeExpertise(idx)"
                        class="absolute top-2 left-2 text-red-500 hover:text-red-700 text-sm font-bold">✕</button>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">الأيقونة</label>
                            <select v-model="card.icon" class="input">
                                <option value="shield">🛡️ درع</option>
                                <option value="bolt">⚡ برق</option>
                                <option value="code">💻 كود</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">العنوان</label>
                            <input v-model="card.title" type="text" class="input" />
                        </div>
                        <div>
                            <label class="form-label">الوصف</label>
                            <input v-model="card.desc" type="text" class="input" />
                        </div>
                    </div>
                </div>
                <div v-if="!form.expertise_cards?.length" class="text-center py-4 text-slate-400">لا توجد بطاقات</div>
            </div>

            <!-- ═══════════ TIP BANNER ═══════════ -->
            <div class="card p-6 space-y-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white border-b pb-3">📌 شريط المعلومة</h2>
                <div>
                    <label class="form-label">النص (يدعم HTML)</label>
                    <textarea v-model="form.tip_banner" rows="4" class="input"></textarea>
                    <p v-if="errors.tip_banner" class="form-error">{{ errors.tip_banner[0] }}</p>
                </div>
            </div>

            <!-- ═══════════ ACHIEVEMENTS ═══════════ -->
            <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">🏆 الإنجازات</h2>
                    <button type="button" @click="addAchievement" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>

                <div v-for="(a, idx) in form.achievements" :key="idx"
                    class="flex items-center gap-4 p-3 border rounded-lg bg-slate-50 dark:bg-slate-800/50">
                    <div class="w-24">
                        <label class="form-label">الرقم</label>
                        <input v-model="a.num" type="text" class="input text-center font-bold" />
                    </div>
                    <div class="flex-1">
                        <label class="form-label">الوصف</label>
                        <input v-model="a.label" type="text" class="input" />
                    </div>
                    <button type="button" @click="removeAchievement(idx)"
                        class="text-red-500 hover:text-red-700 mt-6">✕</button>
                </div>
                <div v-if="!form.achievements?.length" class="text-center py-4 text-slate-400">لا توجد إنجازات</div>
            </div>

            <!-- ═══════════ CONSULTATIONS ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white border-b pb-3">💼 الاستشارات</h2>
                <div>
                    <label class="form-label">نص الاستشارات</label>
                    <textarea v-model="form.consultations_text" rows="5" class="input"></textarea>
                    <p v-if="errors.consultations_text" class="form-error">{{ errors.consultations_text[0] }}</p>
                </div>
            </div> -->

            <!-- ═══════════ VOLUNTEERING ═══════════ -->
            <div class="card p-6 space-y-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white border-b pb-3">🤝 التطوع</h2>

                <div>
                    <label class="form-label">المقدمة</label>
                    <textarea v-model="form.volunteering_intro" rows="4" class="input"></textarea>
                    <p class="text-xs text-slate-400 mt-1">استخدم سطر جديد للفصل بين الفقرات</p>
                    <p v-if="errors.volunteering_intro" class="form-error">{{ errors.volunteering_intro[0] }}</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">النقاط</label>
                        <button type="button" @click="addBullet" class="btn btn-secondary btn-sm">+ إضافة نقطة</button>
                    </div>
                    <div v-for="(_, idx) in form.volunteering_bullets" :key="idx" class="flex items-center gap-3 mb-2">
                        <span class="text-slate-400 font-bold">•</span>
                        <input v-model="form.volunteering_bullets[idx]" type="text" class="input flex-1" />
                        <button type="button" @click="removeBullet(idx)"
                            class="text-red-500 hover:text-red-700">✕</button>
                    </div>
                    <div v-if="!form.volunteering_bullets?.length" class="text-center py-3 text-slate-400">لا توجد نقاط
                    </div>
                </div>

                <div>
                    <label class="form-label">الخاتمة</label>
                    <textarea v-model="form.volunteering_closing" rows="3" class="input"></textarea>
                    <p v-if="errors.volunteering_closing" class="form-error">{{ errors.volunteering_closing[0] }}</p>
                </div>
            </div>

            <!-- ═══════════ CONTACT & SOCIAL ═══════════ -->
            <div class="card p-6 space-y-5">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white border-b pb-3">📞 التواصل والحسابات</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">رقم الهاتف</label>
                        <input v-model="form.phone" type="text" dir="ltr" class="input" placeholder="+966..." />
                        <p v-if="errors.phone" class="form-error">{{ errors.phone[0] }}</p>
                    </div>
                    <div>
                        <label class="form-label">البريد الإلكتروني</label>
                        <input v-model="form.email" type="email" dir="ltr" class="input" />
                        <p v-if="errors.email" class="form-error">{{ errors.email[0] }}</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">حسابات التواصل الاجتماعي</label>
                        <button type="button" @click="addSocial" class="btn btn-secondary btn-sm">+ إضافة</button>
                    </div>
                    <div v-for="(s, idx) in form.social_links" :key="idx"
                        class="flex items-center gap-3 mb-3 p-3 border rounded-lg bg-slate-50 dark:bg-slate-800/50">
                        <div class="w-36">
                            <select v-model="s.platform" class="input">
                                <option value="x">X / Twitter</option>
                                <option value="youtube">YouTube</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="snapchat">Snapchat</option>
                                <option value="tiktok">TikTok</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input v-model="s.url" type="url" dir="ltr" class="input" placeholder="https://..." />
                        </div>
                        <button type="button" @click="removeSocial(idx)"
                            class="text-red-500 hover:text-red-700">✕</button>
                    </div>
                    <div v-if="!form.social_links?.length" class="text-center py-3 text-slate-400">لا توجد حسابات</div>
                </div>
            </div>

            <!-- ═══════════ SKILLS ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">🎯 المهارات</h2>
                    <button type="button" @click="addSkill" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>
                <div v-for="(_, idx) in form.skills" :key="idx" class="flex items-center gap-3 mb-2">
                    <span class="text-slate-400 font-bold">•</span>
                    <input v-model="form.skills[idx]" type="text" class="input flex-1" placeholder="اسم المهارة" />
                    <button type="button" @click="removeSkill(idx)" class="text-red-500 hover:text-red-700">✕</button>
                </div>
                <div v-if="!form.skills?.length" class="text-center py-3 text-slate-400">لا توجد مهارات</div>
            </div> -->

            <!-- ═══════════ LANGUAGES ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">🌐 اللغات</h2>
                    <button type="button" @click="addLanguage" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>
                <div v-for="(_, idx) in form.languages" :key="idx" class="flex items-center gap-3 mb-2">
                    <span class="text-slate-400 font-bold">•</span>
                    <input v-model="form.languages[idx]" type="text" class="input flex-1" placeholder="اسم اللغة" />
                    <button type="button" @click="removeLanguage(idx)"
                        class="text-red-500 hover:text-red-700">✕</button>
                </div>
                <div v-if="!form.languages?.length" class="text-center py-3 text-slate-400">لا توجد لغات</div>
            </div> -->

            <!-- ═══════════ CERTIFICATIONS ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">📜 الشهادات</h2>
                    <button type="button" @click="addCertification" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>
                <div v-for="(c, idx) in form.certifications" :key="idx"
                    class="flex items-center gap-4 p-3 border rounded-lg bg-slate-50 dark:bg-slate-800/50">
                    <div class="w-36">
                        <label class="form-label">النوع</label>
                        <input v-model="c.type" type="text" class="input" placeholder="نوع الشهادة" />
                    </div>
                    <div class="flex-1">
                        <label class="form-label">الوصف</label>
                        <input v-model="c.description" type="text" class="input" placeholder="وصف الشهادة" />
                    </div>
                    <button type="button" @click="removeCertification(idx)"
                        class="text-red-500 hover:text-red-700 mt-6">✕</button>
                </div>
                <div v-if="!form.certifications?.length" class="text-center py-4 text-slate-400">لا توجد شهادات</div>
            </div> -->

            <!-- ═══════════ EXPERIENCES ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">💼 الخبرات</h2>
                    <button type="button" @click="addExperience" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>
                <div v-for="(_, idx) in form.experiences" :key="idx" class="flex items-center gap-3 mb-2">
                    <span class="text-slate-400 font-bold">•</span>
                    <input v-model="form.experiences[idx]" type="text" class="input flex-1" placeholder="وصف الخبرة" />
                    <button type="button" @click="removeExperience(idx)"
                        class="text-red-500 hover:text-red-700">✕</button>
                </div>
                <div v-if="!form.experiences?.length" class="text-center py-3 text-slate-400">لا توجد خبرات</div>
            </div> -->

            <!-- ═══════════ MEDIA APPEARANCES ═══════════ -->
            <!-- <div class="card p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">🎥 الظهور الإعلامي</h2>
                    <button type="button" @click="addMediaAppearance" class="btn btn-secondary btn-sm">+ إضافة</button>
                </div>
                <div v-for="(_, idx) in form.media_appearances" :key="idx" class="flex items-center gap-3 mb-2">
                    <span class="text-slate-400 font-bold">•</span>
                    <input v-model="form.media_appearances[idx]" type="text" class="input flex-1"
                        placeholder="وصف الظهور الإعلامي" />
                    <button type="button" @click="removeMediaAppearance(idx)"
                        class="text-red-500 hover:text-red-700">✕</button>
                </div>
                <div v-if="!form.media_appearances?.length" class="text-center py-3 text-slate-400">لا يوجد ظهور إعلامي
                </div>
            </div> -->

            <!-- ═══════════ SAVE ═══════════ -->
            <div class="flex items-center justify-end gap-4 pb-8">
                <button @click="handleSave" :disabled="saving" class="btn btn-primary px-8 py-3">
                    {{ saving ? 'جاري الحفظ...' : '💾 حفظ جميع التعديلات' }}
                </button>
            </div>

        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useHomePageStore } from '../../stores/homePage'

const store = useHomePageStore()
const loading = ref(true)
const saving = ref(false)
const errors = ref({})
const successMsg = ref('')

const heroFile = ref(null)
const heroPreview = ref(null)

const form = ref({
    hero_badge: '',
    hero_name: '',
    hero_subtitle: '',
    hero_quote: '',
    hero_floating_text: '',
    hero_image_url: null,
    expertise_cards: [],
    tip_banner: '',
    achievements: [],
    consultations_text: '',
    volunteering_intro: '',
    volunteering_bullets: [],
    volunteering_closing: '',
    phone: '',
    email: '',
    social_links: [],
    skills: [],
    languages: [],
    certifications: [],
    experiences: [],
    media_appearances: [],
})

function onHeroImageChange(e) {
    const file = e.target.files[0]
    if (file) {
        heroFile.value = file
        heroPreview.value = URL.createObjectURL(file)
    }
}

// Expertise
function addExpertise() { form.value.expertise_cards.push({ icon: 'shield', title: '', desc: '' }) }
function removeExpertise(i) { form.value.expertise_cards.splice(i, 1) }

// Achievements
function addAchievement() { form.value.achievements.push({ num: '', label: '' }) }
function removeAchievement(i) { form.value.achievements.splice(i, 1) }

// Volunteering bullets
function addBullet() { form.value.volunteering_bullets.push('') }
function removeBullet(i) { form.value.volunteering_bullets.splice(i, 1) }

// Social links
function addSocial() { form.value.social_links.push({ platform: 'x', url: '' }) }
function removeSocial(i) { form.value.social_links.splice(i, 1) }

// Skills
function addSkill() { form.value.skills.push('') }
function removeSkill(i) { form.value.skills.splice(i, 1) }

// Languages
function addLanguage() { form.value.languages.push('') }
function removeLanguage(i) { form.value.languages.splice(i, 1) }

// Certifications
function addCertification() { form.value.certifications.push({ type: '', description: '' }) }
function removeCertification(i) { form.value.certifications.splice(i, 1) }

// Experiences
function addExperience() { form.value.experiences.push('') }
function removeExperience(i) { form.value.experiences.splice(i, 1) }

// Media Appearances
function addMediaAppearance() { form.value.media_appearances.push('') }
function removeMediaAppearance(i) { form.value.media_appearances.splice(i, 1) }

async function loadData() {
    loading.value = true
    const res = await store.fetchSingleton()
    if (res.success && res.data) {
        const d = res.data
        form.value = {
            hero_badge: d.hero_badge || '',
            hero_name: d.hero_name || '',
            hero_subtitle: d.hero_subtitle || '',
            hero_quote: d.hero_quote || '',
            hero_floating_text: d.hero_floating_text || '',
            hero_image_url: d.hero_image || null,
            expertise_cards: d.expertise_cards ? JSON.parse(JSON.stringify(d.expertise_cards)) : [],
            tip_banner: d.tip_banner || '',
            achievements: d.achievements ? JSON.parse(JSON.stringify(d.achievements)) : [],
            consultations_text: d.consultations_text || '',
            volunteering_intro: d.volunteering_intro || '',
            volunteering_bullets: d.volunteering_bullets ? [...d.volunteering_bullets] : [],
            volunteering_closing: d.volunteering_closing || '',
            phone: d.phone || '',
            email: d.email || '',
            social_links: d.social_links ? JSON.parse(JSON.stringify(d.social_links)) : [],
            skills: d.skills ? [...d.skills] : [],
            languages: d.languages ? [...d.languages] : [],
            certifications: d.certifications ? JSON.parse(JSON.stringify(d.certifications)) : [],
            experiences: d.experiences ? [...d.experiences] : [],
            media_appearances: d.media_appearances ? [...d.media_appearances] : [],
        }
    }
    loading.value = false
}

async function handleSave() {
    saving.value = true
    errors.value = {}
    successMsg.value = ''

    const fd = new FormData()
    fd.append('hero_badge', form.value.hero_badge)
    fd.append('hero_name', form.value.hero_name)
    fd.append('hero_subtitle', form.value.hero_subtitle)
    fd.append('hero_quote', form.value.hero_quote)
    fd.append('hero_floating_text', form.value.hero_floating_text)
    fd.append('tip_banner', form.value.tip_banner)
    fd.append('consultations_text', form.value.consultations_text)
    fd.append('volunteering_intro', form.value.volunteering_intro)
    fd.append('volunteering_closing', form.value.volunteering_closing)
    fd.append('phone', form.value.phone)
    fd.append('email', form.value.email)

    if (heroFile.value) fd.append('hero_image', heroFile.value)

    // JSON arrays
    fd.append('expertise_cards', JSON.stringify(form.value.expertise_cards))
    fd.append('achievements', JSON.stringify(form.value.achievements))
    fd.append('volunteering_bullets', JSON.stringify(form.value.volunteering_bullets))
    fd.append('social_links', JSON.stringify(form.value.social_links))
    fd.append('skills', JSON.stringify(form.value.skills))
    fd.append('languages', JSON.stringify(form.value.languages))
    fd.append('certifications', JSON.stringify(form.value.certifications))
    fd.append('experiences', JSON.stringify(form.value.experiences))
    fd.append('media_appearances', JSON.stringify(form.value.media_appearances))

    const res = await store.saveSingleton(fd)
    if (res.success) {
        successMsg.value = 'تم حفظ التعديلات بنجاح ✓'
        heroFile.value = null
        heroPreview.value = null
        if (res.data) form.value.hero_image_url = res.data.hero_image
        setTimeout(() => { successMsg.value = '' }, 3000)
    } else if (res.errors) {
        errors.value = res.errors
    }
    saving.value = false
}

onMounted(() => loadData())
</script>

<style scoped>
.form-label {
    display: block;
    font-size: .875rem;
    font-weight: 500;
    color: #475569;
    margin-bottom: .5rem;
}

:root.dark .form-label,
.dark .form-label {
    color: #cbd5e1;
}

.form-error {
    color: #ef4444;
    font-size: .8rem;
    margin-top: .25rem;
}
</style>
