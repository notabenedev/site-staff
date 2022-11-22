<?php

namespace Notabenedev\SiteStaff\Filters;

use Intervention\Image\Facades\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image as File;

class EmployeesGridLg4 implements FilterInterface {

    public function applyFilter(File $image)
    {
        return $image
            ->fit(290, 340);
    }
}