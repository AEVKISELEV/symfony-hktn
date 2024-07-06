import {createApp} from 'vue';
import {createRouter, createWebHistory} from 'vue-router'
import App from './components/App.vue';
import Main from './components/Main.vue';

const router = createRouter({
	routes: [
		{
			path: '/groups',
			component: Main
		},
	],

	history: createWebHistory()
})

const app = createApp(App)
app.use(router)
app.mount('#app')