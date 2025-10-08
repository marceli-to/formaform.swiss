<template>
	<div class="flex flex-col gap-y-14 pt-20 lg:pt-0">
		<div>
			<span class="bg-white w-full flex items-center min-h-36 px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-olverra placeholder:text-xs placeholder:text-olverra">
				{{ userEmail }}
			</span>
		</div>
		<div>
			<button
				type="button"
				@click="logout"
				:disabled="loading"
				class="bg-dravine flex items-center gap-x-8 font-spezia-medium font-medium text-xs leading-none text-olverra w-full text-left px-12 py-11 hover:bg-white transition-colors disabled:opacity-50">
				<IconArrow class="w-16 h-auto" />
				{{ loading ? 'Abmelden...' : 'Abmelden' }}
			</button>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import IconArrow from '../icons/Arrow.vue';

const props = defineProps({
	userEmail: {
		type: String,
		required: true,
	},
});

const emit = defineEmits(['logout-success']);

const loading = ref(false);

const logout = async () => {
	loading.value = true;

	try {
		await axios.post('/api/logout');

		// Emit success event
		emit('logout-success');

		// Reload the page to clear all state and get fresh CSRF token
		window.location.reload();
	} catch (error) {
		console.error('Logout failed:', error);
		// Still reload on error as we want to clear client state
		window.location.reload();
	}
};
</script>
