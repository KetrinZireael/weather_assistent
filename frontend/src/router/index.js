import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import ProfileView from '../views/ProfileView.vue'
import DetailsView from '../views/DetailsView.vue'

const routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/profile', name: 'Profile', component: ProfileView },
  { path: '/details', name: 'Details', component: DetailsView },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
