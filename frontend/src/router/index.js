import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DetailsView from '../views/DetailsView.vue'
import ProfileView from '../views/ProfileView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'

// ✅ створюємо маршрути
const routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/details', name: 'Details', component: DetailsView },
  { path: '/login', name: 'Login', component: LoginView },
  { path: '/register', name: 'Register', component: RegisterView },
  {
    path: '/profile',
    name: 'Profile',
    component: ProfileView,
    meta: { requiresAuth: true }, // 🔒 ця сторінка вимагає авторизації
  },
]

// ✅ створюємо router
const router = createRouter({
  history: createWebHistory(),
  routes,
})

// 🔐 middleware — перевіряємо токен у localStorage
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login') // перенаправлення, якщо не авторизований
  } else {
    next()
  }
})

export default router
