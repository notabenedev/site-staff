<?php

namespace Notabenedev\SiteStaff\Events;

use App\StaffDepartment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StaffDepartmentChangePosition
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $department;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StaffDepartment $department)
    {
        $this->department = $department;
    }

}
