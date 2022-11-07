<?php

namespace Notabenedev\SiteStaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        static::creating(function (\App\StaffEmployee $model) {
            $model->published_at = now();
        });

        static::updated(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();
        });

        static::deleting(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();

            $model->departments()->sync([]);
        });
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
     * Есть ли отдел у сотрудника
     *
     * @param $id
     * @return mixed
     */

    public function hasDepartment($id)
    {
        return $this->departments->where('id',$id)->count();
    }

    /**
     * Обновить секции новости.
     *
     * @param $userInput
     */
    public function updateDepartments($userInput, $new = false)
    {
        $departmentIds = [];
        foreach ($userInput as $key => $value) {
            if (strstr($key, "check-") == false) {
                continue;
            }
            $departmentIds[] = $value;
        }
        $this->departments()->sync($departmentIds);
        $this->forgetCache();
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

    /**
     * Получить тизер
     *
     * @return string
     * @throws \Throwable
     */
    public function getTeaser($grid = 3)
    {
        $key = "staff-employee-teaser:{$this->id}-{$grid}";
        $model = $this;
        $employee = Cache::rememberForever($key, function () use ($model) {
            $image = $model->image;
            return $model;
        });
        $view = view("site-staff::site.employees.teaser", [
            'employee' => $employee,
            'grid' => $grid,
        ]);
        return $view->render();
    }

    /**
     * Получить галлерею.
     * @return object
     */
    public function getFullData()
    {
        $cacheKey = "staff-employee-full:{$this->id}";
        $cached = Cache::get($cacheKey);
        if (!empty($cached)) {
            return $cached;
        }
        $gallery = $this->images->sortBy('weight');
        $image = $this->image;
        $departments = $this->departments;
        $data = (object) [
            'gallery' => $gallery,
            'image' => $image,
            "departments" => $departments,
        ];
        Cache::forever($cacheKey, $data);
        return $data;
    }

    /**
     * Очистить кэш.
     */
    public function forgetCache($full = FALSE)
    {
        if (!$full) {
            Cache::forget("staff-employee-teaser:{$this->id}-3");
            Cache::forget("staff-employee-teaser:{$this->id}-6");
        }
        Cache::forget("staff-employee-full:{$this->id}");
    }

}
