<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserCreated extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct(
		public string $password,
	) {
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('Ihr neuer Zugang zu Formaform AG')
			->greeting('Hallo ' . $notifiable->get('name') . ',')
			->line('Ein Zugang wurde für Sie bei Formaform AG erstellt.')
			->line('Hier sind Ihre Login-Daten:')
			->line('**E-Mail:** ' . $notifiable->email())
			->line('**Passwort:** ' . $this->password)
			->action('Jetzt einloggen', url('/'))
			->line('Bitte ändern Sie Ihr Passwort nach dem ersten Login.')
			->line('Bei Fragen stehen wir Ihnen gerne zur Verfügung.')
			->salutation('Mit freundlichen Grüssen, das Formaform AG Team');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
			//
		];
	}
}
