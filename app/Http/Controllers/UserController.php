<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Notifications\PasswordReset;
use App\Notifications\UserCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Statamic\Facades\User;

class UserController extends Controller
{
	/**
	 * Handle user login.
	 */
	public function login(LoginRequest $request): JsonResponse
	{
		$user = User::findByEmail($request->email);

		if (! $user || ! Hash::check($request->password, $user->password())) {
			return response()->json([
				'message' => 'Die angegebenen Anmeldedaten sind ungültig.',
				'errors' => [
					'email' => ['Die angegebenen Anmeldedaten sind ungültig.'],
				],
			], 422);
		}

		Auth::login($user);
		$request->session()->regenerate();

		return response()->json([
			'message' => 'Erfolgreich angemeldet.',
			'user' => [
				'email' => $user->email(),
				'name' => $user->get('name'),
			],
		], 200);
	}

	/**
	 * Handle user logout.
	 */
	public function logout(Request $request): JsonResponse
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return response()->json([
			'message' => 'Erfolgreich abgemeldet.',
		], 200);
	}

	/**
	 * Store a newly created user.
	 */
	public function store(CreateUserRequest $request): JsonResponse
	{
		$user = User::make()
			->email($request->email)
			->password($request->password) // Statamic will hash this automatically
			->set('name', $request->name);

		// Assign frontend_user role and group
		$user->assignRole('frontend_user');
		$user->addToGroup('frontend_user');

		$user->save();

		// Send notification email
		$user->notify(new UserCreated($request->password));

		return response()->json([
			'message' => 'Benutzer wurde erfolgreich erstellt.',
			'user' => [
				'id' => $user->id(),
				'name' => $user->get('name'),
				'email' => $user->email(),
			],
		], 201);
	}

	/**
	 * Reset user password and send new password via email.
	 */
	public function password(ResetPasswordRequest $request): JsonResponse
	{
		$request->validate([
			'email' => 'required|email',
		]);

		$user = User::findByEmail($request->email);

		if (! $user) {
			return response()->json([
				'message' => 'Ein neues Passwort wurde an die angegebene E-Mail-Adresse gesendet.',
			], 200);
		}

		// Generate a secure random password
		$newPassword = $this->generateSecurePassword();

		// Update user password (Statamic will hash this automatically)
		$user->password($newPassword);
		$user->save();

		// Send notification email with new password
		$user->notify(new PasswordReset($newPassword));

		return response()->json([
			'message' => 'Ein neues Passwort wurde an Ihre E-Mail-Adresse gesendet.',
		], 200);
	}

	/**
	 * Generate a secure random password.
	 */
	private function generateSecurePassword(int $length = 16): string
	{
		$uppercase = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		$lowercase = 'abcdefghijkmnpqrstuvwxyz';
		$numbers = '123456789';
		$special = '!@#$%&*';
		$allChars = $uppercase.$lowercase.$numbers.$special;

		$password = '';

		// Ensure at least one of each type
		$password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
		$password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
		$password .= $numbers[random_int(0, strlen($numbers) - 1)];
		$password .= $special[random_int(0, strlen($special) - 1)];

		// Fill the rest randomly
		for ($i = strlen($password); $i < $length; $i++) {
			$password .= $allChars[random_int(0, strlen($allChars) - 1)];
		}

		// Shuffle the password using a cryptographically secure algorithm
		$characters = str_split($password);
		for ($i = count($characters) - 1; $i > 0; $i--) {
			$j = random_int(0, $i);
			[$characters[$i], $characters[$j]] = [$characters[$j], $characters[$i]];
		}

		return implode('', $characters);
	}
}
