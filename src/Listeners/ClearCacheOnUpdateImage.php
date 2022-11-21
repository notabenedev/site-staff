<?php

namespace Notabenedev\SiteStaff\Listeners;

use App\StaffEmployee;

class ClearCacheOnUpdateImage
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $morph = $event->morph;
        if (!empty($morph) && get_class($morph) == StaffEmployee::class) {
            $morph->forgetCache();
        }
    }
}
