<template>
	<div>
		<!-- Backdrop overlay -->
		<div
			v-show="isOpen"
			@click="close"
			class="fixed left-0 top-0 z-10 bg-white/30 w-full h-full">
		</div>

		<!-- Auth panel -->
		<div
			class="
				bg-olverra
				text-white
				font-spezia-medium
				font-medium
				text-xs
				fixed
				top-0
				lg:top-12
				right-0
				2xl:right-[calc(((100%_-_var(--max-width-container))_/_2))]
				z-30
				pt-12
				lg:pt-0
				px-20
				lg:px-22
				w-full
				lg:max-w-[336px]
				min-h-dvh
				lg:min-h-[calc(100dvh_-_12px)]"
			v-show="isOpen"
			@click.self="close"
			style="transition: opacity 100ms ease-out"
			:style="{ opacity: isOpen ? 1 : 0 }">

			<div class="flex justify-between items-center h-[var(--header-height-sm)] lg:h-[var(--header-height-lg)]">
				<span class="text-sm">
					{{ isLoggedIn ? 'Logout' : 'Login' }}
				</span>
				<span>
					<a
						href="javascript:;"
						@click="close"
						class="flex items-center justify-center lg:block w-26 lg:w-14 h-20">
						<IconCross class="w-20 h-auto text-white lg:hidden" />
						<IconLogin class="w-14 h-auto text-white hidden lg:block" />
					</a>
				</span>
			</div>

			<!-- Logged in view -->
			<Logout
				v-if="isLoggedIn"
				:user-email="userEmail"
				@logout-success="handleLogoutSuccess" />

			<!-- Logged out views -->
			<template v-else>
				<!-- Login form -->
				<Login
					v-if="currentView === 'login'"
					@login-success="handleLoginSuccess"
					@switch-to-password-reset="currentView = 'password-reset'" />

				<!-- Password reset form -->
				<PasswordReset
					v-if="currentView === 'password-reset'"
					@back-to-login="currentView = 'login'" />
			</template>

		</div>
	</div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Login from './Login.vue';
import Logout from './Logout.vue';
import PasswordReset from './PasswordReset.vue';
import IconCross from '../icons/Cross.vue';
import IconLogin from '../icons/Login.vue';

const props = defineProps({
	initialLoggedIn: {
		type: Boolean,
		default: false,
	},
	initialUserEmail: {
		type: String,
		default: '',
	},
});

const isOpen = ref(false);
const isLoggedIn = ref(props.initialLoggedIn);
const userEmail = ref(props.initialUserEmail);
const currentView = ref('login');

const open = () => {
	isOpen.value = true;
	// Notify Alpine.js that the panel is open
	window.dispatchEvent(new CustomEvent('auth-panel-opened'));
};

const close = () => {
	isOpen.value = false;
	// Reset to login view when closing
	currentView.value = 'login';
	// Notify Alpine.js that the panel is closed
	window.dispatchEvent(new CustomEvent('auth-panel-closed'));
};

const toggle = () => {
	if (isOpen.value) {
		close();
	} else {
		open();
	}
};

const handleLoginSuccess = () => {
	// Page will reload, so no need to update state or close panel
};

const handleLogoutSuccess = () => {
	isLoggedIn.value = false;
	userEmail.value = '';
	close();
};

// Listen for toggle events from Alpine.js
const handleToggleAuth = (event) => {
	toggle();
};

onMounted(() => {
	window.addEventListener('toggle-auth', handleToggleAuth);
});

onUnmounted(() => {
	window.removeEventListener('toggle-auth', handleToggleAuth);
});
</script>
