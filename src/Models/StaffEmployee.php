<?php

namespace Notabenedev\SiteStaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;
use PortedCheese\BaseSettings\Traits\ShouldGallery;
use PortedCheese\BaseSettings\Traits\ShouldImage;
use PortedCheese\BaseSettings\Traits\ShouldSlug;
use PortedCheese\SeoIntegration\Traits\ShouldMetas;

class StaffEmployee extends Model
{
    use HasFactory;
    use ShouldMetas, ShouldSlug, ShouldImage, ShouldGallery;
    // use ShouldParams;

    protected $fillable = [
        "title",
        "slug",
        "short",
        "description",
        "comment"
    ];

    protected $metaKey = "employees";
    protected $imageKey = "main_image";

    protected static function booting() {

        parent::booting();

        static::created(function (\App\StaffEmployee $model){
            // Забыть кэш.
            $model->forgetCache();
        });

        static::updating(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();
        });
        static::updated(function (\App\StaffEmployee $model) {
            // Забыть кэш.
            $model->forgetCache();
        });

        static::deleting(function (\App\StaffEmployee $model) {
            $model->departments()->sync([]);
            foreach ($model->offers as $offer){
                $offer->delete();
            }
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
     * Предложения сотрудника
     *
     *@return HasMany
     */
    public function offers(){
        if (class_exists(\App\StaffOffer::class)) {
            return $this->hasMany(\App\StaffOffer::class)->orderBy('priority');
        }
        else {
            return new HasMany($this->newQuery(), $this, "", "");
        }

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
     * Обновить отделы.
     *
     * @param $userInput
     */
    public function updateDepartments($userInput)
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
     * Change btn enabled status
     *
     * @param $value
     * @return void
     */
    public  function  updateBtnEnabled($value){
        if ($this->btn_enabled !== $value){
            $this->btn_enabled = $value;
            $this->save();
        }
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

        Cache::forget("staff-employees-getAllPublished");

    }

    public static function getAllPublished()
    {
        $key = "staff-employees-getAllPublished";
        return Cache::rememberForever($key, function()  {
            $departments = StaffDepartment::query()
                ->select("id")
                ->whereNotNull("published_at")
                ->orderBy("priority")
                ->get();
            $items = [];
            foreach ($departments as $department){
                $employees = $department->employees;
                foreach ($employees as $item) {
                    if ($item->published_at)
                        $items[$item->id] = $item;
                }
            }
            return $items;
        });
    }

}
