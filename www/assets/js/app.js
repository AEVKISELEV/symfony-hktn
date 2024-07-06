import {createApp} from 'vue';
import {createRouter, createWebHistory} from 'vue-router'
import App from './components/App.vue';
import Main from './components/Main.vue';
import Auth from './components/auth/Auth.vue'
import PostPage from './components/post/PostPage.vue';

const router = createRouter({
	routes: [
		{
			path: '/auth',
			component: Auth
		},
		{
			path: '/post/:groupId/:postId',
			component: Main
		},
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