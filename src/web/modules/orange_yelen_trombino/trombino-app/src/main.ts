import { createApp } from 'vue'
import './style.css'
import 'leaflet/dist/leaflet.css';
import '@/assets/scss/main.scss'
// import { Tooltip, Toast, Popover } from 'boosted';

import App from './App.vue'

const trombinoApp = createApp(App)

// trombinoApp.use(Tooltip)

//Vue Router

import { createMemoryHistory, createRouter } from 'vue-router'

import Homepage from './views/home.vue'

const routes = [
  { path: '/', component: Homepage }
]

const router = createRouter({
  history: createMemoryHistory(),
  routes,
})

trombinoApp.use(router)

trombinoApp.mount('#app')
