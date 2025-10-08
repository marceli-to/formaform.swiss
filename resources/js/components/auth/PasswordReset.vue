<template>
	<div class="flex flex-col gap-y-14 text-xs">

		<div v-if="successMessage" class="font-spezia-medium font-medium text-white">
			{{ successMessage }}
		</div>

		<template v-if="!successMessage">
			<div class="flex flex-col gap-y-14">
				<div>
					<input
						type="email"
						id="reset-email"
						v-model="form.email"
						class="bg-white w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-olverra placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
						placeholder="E-Mail-Adresse"
						required />
				</div>

				<button
					type="button"
					@click="resetPassword"
					:disabled="loading || !isFormValid"
					class="bg-dravine font-spezia-medium font-medium text-xs leading-none text-olverra block w-full text-left px-12 py-11 transition-colors disabled:opacity-50"
					:class="{ 'hover:bg-white': !loading && isFormValid }">
					{{ loading ? 'Wird gesendet...' : 'Neues Passwort senden' }}
				</button>

				<div v-if="errors.email" class="bg-indian/70 text-white px-12 py-9">
					{{ errors.email[0] }}
				</div>
			</div>
		</template>

		<div>
			<a
				href="javascript:;"
				@click="backToLogin"
				class="text-xs text-dravine hover:underline underline-offset-2 pl-12">
				Zurück zum Login
			</a>
		</div>

	</div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue';
import axios from 'axios';

const emit = defineEmits(['back-to-login']);

const form = reactive({
	email: null,
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const isFormValid = computed(() => {
	return form.email && form.email.trim() !== '';
});

const resetPassword = async () => {
	loading.value = true;
	errors.value = {};
	errorMessage.value = '';
	successMessage.value = '';

	try {
		const response = await axios.post('/api/password-reset', form);

		successMessage.value = 'Ein neues Passwort wurde an Ihre E-Mail-Adresse gesendet.';
		form.email = null;
	} catch (error) {
		if (error.response && error.response.status === 422) {
			errors.value = error.response.data.errors || {};
		} else if (error.response && error.response.status === 404) {
			errorMessage.value = 'Kein Benutzer mit dieser E-Mail-Adresse gefunden.';
		} else {
			errorMessage.value = 'Es ist ein Fehler aufgetreten.';
		}
	} finally {
		loading.value = false;
	}
};

const backToLogin = () => {
	// Reset form state
	successMessage.value = '';
	errorMessage.value = '';
	errors.value = {};
	form.email = null;

	// Emit event to switch view
	emit('back-to-login');
};
</script>
