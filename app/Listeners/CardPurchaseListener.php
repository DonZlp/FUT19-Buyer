<?php

namespace App\Listeners;

use App\Events\CardPurchase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CardPurchaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CardPurchase  $event
     * @return void
     */
    public function handle(CardPurchase $event)
    {
        //
    }
}
