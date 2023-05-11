<?php

namespace App\Listeners;

use App\Events\TransactionProcessed;
use App\Jobs\ProcessDelayedNotificationTransactionReceived;
use App\Models\User;
use App\Notifications\SendNotificationTransactionReceived;
use Illuminate\Support\Facades\Http;

class SendReceivedTransactionNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionProcessed $event): void
    {
        $user = User::where('id', $event->transaction->to_id)->first();
        $thirdService = Http::get('https://run.mocky.io/v3/4ce65eb0-2eda-4d76-8c98-8acd9cfd2d39');

        if (data_get($thirdService, 'message') != 'success') {
            $delay = now()->addSeconds(2);
            ProcessDelayedNotificationTransactionReceived::dispatch($event->transaction)
                ->delay($delay);
        } else {
            $user->notify(new SendNotificationTransactionReceived($event));
        }
    }
}
