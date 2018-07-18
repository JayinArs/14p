<?php

namespace App\Notifications;

use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Liliom\Firebase\FirebaseMessage;

class ImportantDate extends Notification
{
	use Queueable;

	private $event;

	/**
	 * Create a new notification instance.
	 *
	 * @param Event $event
	 */
	public function __construct( Event $event )
	{
		$this->event = $event;
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
		return [ 'firebase' ];
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
			->line( 'The introduction to the notification.' )
			->action( 'Notification Action', 'https://laravel.com' )
			->line( 'Thank you for using our application!' );
	}

	public function toFirebase( $notifiable )
	{
		return ( new FirebaseMessage() )
			->notification( [
				                'title' => 'Important Date',
				                'body'  => $this->event->title
			                ] )
			->setPriority( 'high' );
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
