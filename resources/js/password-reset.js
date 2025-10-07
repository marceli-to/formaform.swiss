import { createApp } from 'vue';
import ResetPassword from './components/ResetPassword.vue';
import axios from 'axios';

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
	const resetAppElement = document.querySelector('#password-reset');
	if (resetAppElement) {
		const app = createApp({
			components: {
				ResetPassword,
			},
		});

		app.mount('#password-reset');
	}
});
