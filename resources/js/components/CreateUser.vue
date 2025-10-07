<template>
	<section class="max-w-[32rem] mx-auto text-xs">
		<h1 class="font-spezia-medium font-medium text-sm mb-20 lg:mb-40">
			Benutzer erstellen
		</h1>

		<div v-if="successMessage" class="font-spezia-medium font-medium text-olverra mb-20">
			{{ successMessage }}
		</div>

		<div v-if="errorMessage" class="font-spezia-medium font-medium text-indian mb-20">
			{{ errorMessage }}
		</div>

		<form @submit.prevent="createUser" class="flex flex-col gap-y-20 text-xs">
			<div class="flex flex-col gap-y-8">
				<label for="name" class="font-spezia-medium font-medium">
					Name
				</label>
				<input
					type="text"
					id="name"
					v-model="form.name"
					class="bg-white border border-olverra w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
					placeholder="Vollständiger Name"
					required
				/>
				<span v-if="errors.name" class="text-indian text-xs mt-4 block">{{ errors.name[0] }}</span>
			</div>

			<div class="flex flex-col gap-y-8">
				<label for="email" class="font-spezia-medium font-medium">E-Mail</label>
				<input
					type="email"
					id="email"
					v-model="form.email"
					class="bg-white border border-olverra w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
					placeholder="E-Mail-Adresse"
					required
				/>
				<span v-if="errors.email" class="text-indian text-xs mt-4 block">{{ errors.email[0] }}</span>
			</div>

			<div class="flex flex-col gap-y-8">
				<label for="password" class="font-spezia-medium font-medium">Passwort</label>
				<input
					type="password"
					id="password"
					v-model="form.password"
					class="bg-white border border-olverra w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
					placeholder="Passwort"
					required
				/>
				<span v-if="errors.password" class="text-indian text-xs mt-4 block">{{ errors.password[0] }}</span>
			</div>

			<div class="mt-8">
				<button
					type="submit"
					:disabled="loading"
					class="bg-olverra font-spezia-medium font-medium text-xs leading-none text-white block w-full text-center px-12 py-11 hover:bg-dravine transition-colors disabled:opacity-50">
					{{ loading ? 'Wird erstellt...' : 'Erstellen' }}
				</button>
			</div>
		</form>
	</section>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';

const form = reactive({
	name: 'Gabriela Morf',
	email: 'gabriela.morf@example.com',
	password: 'TestPass123!',
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const createUser = async () => {
	loading.value = true;
	errors.value = {};
	successMessage.value = '';
	errorMessage.value = '';

	try {
		const response = await axios.post('/api/user', form);

		successMessage.value = 'Benutzer wurde erfolgreich erstellt und eine E-Mail wurde versendet.';

		// Reset form
		form.name = 'Gabriela Morf';
		form.email = 'gabriela.morf@example.com';
		form.password = 'TestPass123!';
	} catch (error) {
		if (error.response && error.response.status === 422) {
			errors.value = error.response.data.errors || {};
		} else {
			errorMessage.value = 'Es ist ein Fehler aufgetreten.';
		}
	} finally {
		loading.value = false;
	}
};
</script>
