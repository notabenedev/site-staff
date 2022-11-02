<?php

namespace Notabenedev\SiteStaff\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PortedCheese\BaseSettings\Traits\ShouldSlug;
use PortedCheese\SeoIntegration\Traits\ShouldMetas;

class StaffDepartment extends Model
{
    use HasFactory;
    use ShouldMetas, ShouldSlug;

    protected $fillable = [
        "title",
        "slug",
        "short",
    ];

    protected $metaKey = "departments";

    protected static function booting() {

        parent::booting();
    }

    /**
     * Родительская группа.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(\App\StaffDepartment::class, "parent_id");
    }

    /**
     * Дочерние группы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(\App\StaffDepartment::class, "parent_id")->orderBy("priority");

    }

    /**
     * Уровень вложенности.
     *
     * @return int
     */
    public function getNestingAttribute()
    {
        if (empty($this->parent_id)) {
            return 1;
        }
        return $this->parent->nesting + 1;
    }

    /**
     * Get parent publish status
     *
     * @return \Illuminate\Support\Carbon|mixed
     */

    public function isParentPublished(){

        $parent = $this->parent()->first();
        return $parent ? $parent->published_at : now();

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
     * Change publish status all children
     *
     */

    public function publishCascade()
    {
        $published =  $this->published_at;
        $children = $this->children();
        $collection = $children->get();
        $parentPublished = $this->isParentPublished();

        //published department and child

        if ($parentPublished){
            // change publish
            $this->publish();
            if($published){
                $this->unPublishChildren($collection);
            }
            return true;
        }
        else
        {
            if (!$published){
                return false;
            }
            else {
                $this->publish();
                $this->unPublishChildren($collection);
                return true;
            }
        }

    }

    /**
     * UnPublish child
     *
     * @param $collection
     * @return void
     *
     */
    protected function unPublishChildren($collection){
        if ($collection->count() > 0) {
            foreach ($collection as $child) {
                $child->published_at = null;
                $child->save();
                $this->unPublishChildren($child->children()->get());
            }
        }
    }


    /**
     * Model's tree
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     */
    public static function getTree(){
        $query = self::query();
        return $query
            ->whereNull("parent_id")
            ->whereNotNull('published_at')
            ->orderBy("priority")
            ->with("children")
            ->get();
    }

}
