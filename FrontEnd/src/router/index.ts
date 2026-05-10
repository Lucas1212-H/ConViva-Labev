import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import Cadastro from '../views/RegistroView.vue'
import EspecialistaView from '../views/EspecialistaView.vue'
import Login from '../views/LoginView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/area-especialista',
    name: 'specialist-login',
    component: LoginView
  },
  {
    path: '/especialista',
    name: 'specialist-area',
    component: EspecialistaView
  },
  {
    path: '/cadastro',
    name: 'cadastro',
    component: Cadastro
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router