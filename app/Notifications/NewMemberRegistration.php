<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMemberRegistration extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via( $notifiable )
	{
		return [ 'mail' ];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail( $notifiable )
	{
		return ( new MailMessage )
			->subject( 'Welcome! You\'re now a member of Jaffery Forum' )
			->greeting( 'Welcome ' . $notifiable->first_name )
			->line( 'You\'re now a member of Jaffery Forum, enjoy are services!' )
			->action( 'Member Area', env( 'web_url' ) . '/member' )
			->line( 'Thank you for using our application!' );
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray( $notifiable )
	{
		return [
			//
		];
	}
}
