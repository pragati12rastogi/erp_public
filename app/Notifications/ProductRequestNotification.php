<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductRequestNotification extends Notification
{
    use Queueable;
    public $item_name;
    public $item_qty;
    public $distributer_name;
    public $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item_name,$item_qty,$distributer_name,$url)
    {
        $this->item_name = $item_name;
        $this->item_qty = $item_qty;
        $this->distributer_name = $distributer_name;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [CustomDbChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'data' => $this->distributer_name.' requested for '.$this->item_qty.' quantity of Item: '.$this->item_name,
            'n_type' => 'product_request',
            'url' =>  $this->url
        ];
    }
}
