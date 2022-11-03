<?php

namespace Notabenedev\SiteStaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PortedCheese\BaseSettings\Traits\ShouldGallery;
use PortedCheese\BaseSettings\Traits\ShouldImage;
use PortedCheese\BaseSettings\Traits\ShouldSlug;
use PortedCheese\SeoIntegration\Traits\ShouldMetas;

class StaffEmployee extends Model
{
    use HasFactory;
    use ShouldMetas, ShouldSlug, ShouldImage, ShouldGallery;

    protected $fillable = [
        "title",
        "slug",
        "short",
        "description",
        "comment",
    ];

    protected $metaKey = "employees";
    protected $imageKey = "main_image";

    protected static function booting() {

        parent::booting();

    }

    /**
     * Отделы сотрудника.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments()
    {
        return $this->belongsToMany(\App\StaffDepartment::class)
            ->withTimestamps();
    }


    /**
     * Change publish status
     *
     */
    public function publish()
    {
        $this->published_at = $this->published_at  ? null : now();
        $this->save();
    }

    /**
     * Изменить дату создания.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }

    /**
     * Изменить дату публикации.
     *
     * @param $value
     * @return string
     */
    public function getPublishedAtAttribute($value)
    {
        return datehelper()->changeTz($value);
    }
}
