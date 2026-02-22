import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../pages/HomePage.vue'),
    meta: { guest: true, isPublic: true } // isPublic prevents redirect if already logged in but visiting home
  },
  {
    path: '/articles/:id',
    name: 'article.show',
    component: () => import('../pages/articles/Show.vue'),
    meta: { guest: true, isPublic: true }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../pages/Login.vue'),
    meta: { guest: true }
  },
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('../pages/AdminDashboard.vue'),
        meta: { title: 'لوحة التحكم' }
      },
      {
        path: 'home-settings',
        name: 'home-settings',
        component: () => import('../pages/settings/HomePageEditor.vue'),
        meta: { requiresAdmin: true }
      },
      // Portfolio Routes
      {
        path: 'profile',
        name: 'profile',
        component: () => import('../pages/profile/Edit.vue'),
        meta: { title: 'الملف الشخصي' }
      },
      // User Management Routes (Admin Only)
      {
        path: 'users',
        name: 'users',
        component: () => import('../pages/users/List.vue'),
        meta: { requiresAdmin: true }
      },
      {
        path: 'users/create',
        name: 'users.create',
        component: () => import('../pages/users/Form.vue'),
        meta: { requiresAdmin: true }
      },
      {
        path: 'users/:id/edit',
        name: 'users.edit',
        component: () => import('../pages/users/Form.vue'),
        meta: { requiresAdmin: true }
      },
      // Settings
      {
        path: 'settings',
        name: 'settings',
        component: () => import('../pages/Setting/Settings.vue'),
        meta: { requiresAdmin: true, title: 'الإعدادات' }
      },
      {
        path: 'settings/create',
        name: 'settings.create',
        component: () => import('../pages/Setting/SettingForm.vue'),
        meta: { requiresAdmin: true, title: 'إضافة إعداد' }
      },
      {
        path: 'settings/:id/edit',
        name: 'settings.edit',
        component: () => import('../pages/Setting/SettingForm.vue'),
        meta: { requiresAdmin: true, title: 'تعديل إعداد' }
      },
      // RBAC Routes
      {
        path: 'roles',
        name: 'roles',
        component: () => import('../pages/RBAC/Roles.vue'),
        meta: { requiresAdmin: true, title: 'إدارة الأدوار' }
      },
      {
        path: 'permissions',
        name: 'permissions',
        component: () => import('../pages/RBAC/Permissions.vue'),
        meta: { requiresAdmin: true, title: 'إدارة الصلاحيات' }
      },
      // Articles
      {
        path: 'articles',
        name: 'articles',
        component: () => import('../pages/articles/List.vue'),
        meta: { title: 'المقالات' }
      },
      {
        path: 'articles/create',
        name: 'articles.create',
        component: () => import('../pages/articles/Form.vue'),
        meta: { title: 'إضافة مقالة' }
      },
      {
        path: 'articles/:id/edit',
        name: 'articles.edit',
        component: () => import('../pages/articles/Form.vue'),
        meta: { title: 'تعديل مقالة' }
      },
      // Videos
      {
        path: 'videos',
        name: 'videos',
        component: () => import('../pages/videos/List.vue'),
        meta: { title: 'الفيديوهات' }
      },
      {
        path: 'videos/create',
        name: 'videos.create',
        component: () => import('../pages/videos/Form.vue'),
        meta: { title: 'إضافة فيديو' }
      },
      {
        path: 'videos/:id/edit',
        name: 'videos.edit',
        component: () => import('../pages/videos/Form.vue'),
        meta: { title: 'تعديل فيديو' }
      },
      // Home Page Settings
      {
        path: 'home-settings',
        name: 'home-settings',
        component: () => import('../pages/settings/HomePageEditor.vue'),
        meta: { title: 'إعدادات الصفحة الرئيسية' }
      },
      // Gallery
      {
        path: 'gallery',
        name: 'gallery',
        component: () => import('../pages/gallery/GalleryPage.vue'),
        meta: { title: 'معرض الصور' }
      },
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  const isAuthenticated = authStore.isAuthenticated
  const isAdmin = authStore.isAdmin

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' })
  } 
  else if (to.meta.guest && isAuthenticated && !to.meta.isPublic) {
    next({ name: 'home-settings' }) // If already logged in, skip login page
  }
  else if (to.meta.requiresAdmin && !isAdmin) {
    next({ name: 'profile' })
  }
  else {
    next()
  }
})

export default router
