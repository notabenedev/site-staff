<?php

namespace Notabenedev\SiteStaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;
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

        static::created(function (\App\StaffEmployee $model){
            // Забыть кэш.
            $model->forgetCache();
        });

        static::updated(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();
        });

        static::deleting(function (\App\StaffEmployee $model) {
            $model->departments()->sync([]);
        });
        static::deleted(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();
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
     * Publish if one of departments is public
     *
     * @return bool
     */

    public function publishIfPublicDepartment(){
        foreach ($this->departments as $department){
            if ($department->published_at)
            {
                $this->published_at = now();
                $this->save();
                return true;
            }
        }
        return false;
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
            $departments = $model->departments;
            $images = $model->images->sortBy('weight');
            return $model;
        });

        $view = view("site-staff::site.employees.teaser", [
            'employee' => $employee,
            'grid' => $grid,
        ]);
        return $view->render();
    }


    /**
     * Очистить кэш.
     */
    public function forgetCache($full = FALSE)
    {
        foreach ($this->departments as $department){
            StaffDepartmentActions::forgetDepartmentEmployeesIds($department);
        }

        if (!$full) {
            Cache::forget("staff-employee-teaser:{$this->id}-3");
            Cache::forget("staff-employee-teaser:{$this->id}-4");
        }

    }

}
