<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Notifications\UserCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Statamic\Facades\User;

class UserController extends Controller
{
  /**
   * Store a newly created user.
   */
  public function store(CreateUserRequest $request): JsonResponse
  {
    $user = User::make()
      ->email($request->email)
      ->password(Hash::make($request->password))
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
}
