import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router'
import App from './components/App.vue';
import Auth from './components/auth/Auth.vue'

const router = createRouter({
	routes: [{
		path: '/auth',
		component: Auth
	}],
	history: createWebHistory()
})

const app = createApp(App)
app.use(router)
app.mount('#app')