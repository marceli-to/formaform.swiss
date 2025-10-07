import { createApp } from 'vue';
import CreateUser from './components/CreateUser.vue';
import axios from 'axios';

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

const app = createApp({
	components: {
		CreateUser,
	},
});

app.mount('#user-app');
