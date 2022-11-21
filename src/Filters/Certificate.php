<?php

namespace Notabenedev\SiteStaff\Filters;

use Intervention\Image\Facades\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image as File;

class Certificate implements FilterInterface {

    public function applyFilter(File $image)
    {
        $image->orientate();
        $image ->widen(70);

        return $image->resizeCanvas(70, 100, 'center', false, config("site-staff.certificateBgColor"));

    }
}