<template>
	<div class="flex flex-col gap-y-14 pt-20 lg:pt-0">

		<div>
			<input
				type="email"
				v-model="form.email"
				class="bg-white w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-olverra placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
				placeholder="E-Mail-Adresse"
				@focus="handleFocus"
				required />
		</div>

		<div>
			<input
				type="password"
				v-model="form.password"
				class="bg-white w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-olverra placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
				placeholder="Passwort"
				@focus="handleFocus"
				@keyup.enter="login"
				required />
		</div>

		<button
			type="button"
			@click="login"
			:disabled="loading || !isFormValid"
			class="bg-dravine font-spezia-medium font-medium text-xs leading-none text-olverra block w-full text-left px-12 py-11 transition-colors disabled:opacity-50"
			:class="{ 'hover:bg-white': !loading && isFormValid }">
			{{ loading ? 'Anmelden...' : 'Einloggen' }}
		</button>

		<ul
			v-if="errors.email && !hasFocus"
			class="!mb-0 bg-indian/70 text-white px-12 py-9">
			<li v-for="(error, index) in errors.email" :key="index">{{ error }}</li>
		</ul>

		<div>
			<a
				href="javascript:;"
				@click="$emit('switch-to-password-reset')"
				class="text-xs text-dravine hover:underline underline-offset-2 pl-12">
				Passwort vergessen?
			</a>
		</div>

	</div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import axios from 'axios';

const emit = defineEmits(['login-success', 'switch-to-password-reset']);

const form = reactive({
	email: '',
	password: '',
});

const errors = ref({});
const loading = ref(false);
const hasFocus = ref(false);

const isFormValid = computed(() => {
	return form.email.trim() !== '' && form.password.trim() !== '';
});

const handleFocus = () => {
	hasFocus.value = true;
	errors.value = {};
};

const login = async () => {
	loading.value = true;
	errors.value = {};
	hasFocus.value = false;

	try {
		const response = await axios.post('/api/login', form);

		// Reload the page to show logged-in content
		window.location.reload();
	} catch (error) {
		if (error.response && error.response.status === 422) {
			errors.value = error.response.data.errors || {};
		} else {
			errors.value = {
				email: ['Es ist ein Fehler aufgetreten.'],
			};
		}
		loading.value = false;
	}
};
</script>
