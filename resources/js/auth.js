import { createApp } from 'vue';
import AuthWrapper from './components/auth/AuthWrapper.vue';
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
	const authAppElement = document.querySelector('#auth-app');
	if (authAppElement) {
		// Get initial state from data attributes
		const initialLoggedIn = authAppElement.dataset.loggedIn === 'true';
		const initialUserEmail = authAppElement.dataset.userEmail || '';

		const app = createApp(AuthWrapper, {
			initialLoggedIn,
			initialUserEmail,
		});

		app.mount('#auth-app');
	}
});
