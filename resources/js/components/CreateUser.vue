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
				<label 
          for="name" 
          class="font-spezia-medium font-medium" 
          :class="{ 'text-indian': errors.name }">
					<template v-if="errors.name">
            {{ errors.name[0] }}
          </template>
          <template v-else>
            Vollständiger Name
          </template>
				</label>
				<input
					type="text"
					id="name"
					v-model="form.name"
					class="bg-white border border-olverra w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
					placeholder="Vollständiger Name"
					required />
			</div>

			<div class="flex flex-col gap-y-8">
				<label 
          for="email" 
          class="font-spezia-medium font-medium" 
          :class="{ 'text-indian': errors.email }">
          <template v-if="errors.email">
            {{ errors.email[0] }}
          </template>
          <template v-else>
            E-Mail
          </template>
		    </label>
				<input
					type="email"
					id="email"
					v-model="form.email"
					class="bg-white border border-olverra w-full px-12 py-8 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
					placeholder="E-Mail-Adresse"
					required
				/>
			</div>

			<div class="flex flex-col gap-y-8">
				<label 
          for="password" 
          class="font-spezia-medium font-medium" 
          :class="{ 'text-indian': errors.password }">
					<template v-if="errors.password">
						{{ errors.password[0] }}
					</template>
					<template v-else>
						Passwort
					</template>
				</label>
				<div class="relative">
					<input
						:type="showPassword ? 'text' : 'password'"
						id="password"
						v-model="form.password"
						class="bg-white border border-olverra w-full px-12 py-8 pr-100 font-spezia-medium font-medium text-xs leading-none text-black placeholder:text-xs placeholder:text-olverra !outline-none ring-0 focus:ring-0 focus-visible:ring-0"
						placeholder="Passwort"
						required
					/>
					<button
						type="button"
						@click="showPassword = !showPassword"
						class="absolute right-12 top-1/2 -translate-y-1/2 text-olverra hover:text-dravine transition-colors"
						:aria-label="showPassword ? 'Passwort verbergen' : 'Passwort anzeigen'">
						<IconEye :closed="!showPassword" class="w-20 h-20" />
					</button>
				</div>
        <div class="flex justify-end">
          <button
            type="button"
            @click="generatePassword"
            class="text-olverra hover:text-dravine transition-colors">
              Passwort generieren
          </button>
        </div>
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
import IconEye from './icons/Eye.vue';

const form = reactive({
	name: null,
	email: null,
	password: null,
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');
const showPassword = ref(false);

const generatePassword = () => {
	const length = 16;
	const uppercase = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
	const lowercase = 'abcdefghijkmnpqrstuvwxyz';
	const numbers = '123456789';
	const special = '!@#$%&*';
	const allChars = uppercase + lowercase + numbers + special;

	let password = '';

	// Ensure at least one of each type
	password += uppercase[Math.floor(Math.random() * uppercase.length)];
	password += lowercase[Math.floor(Math.random() * lowercase.length)];
	password += numbers[Math.floor(Math.random() * numbers.length)];
	password += special[Math.floor(Math.random() * special.length)];

	// Fill the rest randomly
	for (let i = password.length; i < length; i++) {
		password += allChars[Math.floor(Math.random() * allChars.length)];
	}

	// Shuffle the password
	password = password.split('').sort(() => Math.random() - 0.5).join('');

	form.password = password;
	showPassword.value = true;
};

const createUser = async () => {
	loading.value = true;
	errors.value = {};
	successMessage.value = '';
	errorMessage.value = '';

	try {
		await axios.post('/api/user', form);

		successMessage.value = 'Benutzer wurde erfolgreich erstellt und eine E-Mail wurde versendet.';

		// Reset form
		form.name = null;
		form.email = null;
		form.password = null;
		showPassword.value = false;
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
