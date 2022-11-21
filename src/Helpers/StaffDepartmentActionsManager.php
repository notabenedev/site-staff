<?php

namespace Notabenedev\SiteStaff\Helpers;

use App\StaffDepartment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class StaffDepartmentActionsManager
{

    /**
     * Список всех категорий.
     *
     * @return array
     */
    public function getAllList()
    {
        $departments = [];
        foreach (StaffDepartment::all()->sortBy("title") as $item) {
            $departments[$item->id] = "$item->title ({$item->slug})";
        }
        return $departments;
    }

    /**
     * Получить дерево категорий.
     *
     * @return array
     *
     */
    public function getTree()
    {
        list($tree, $noParent) = $this->makeTreeDataWithNoParent();
        $this->addChildren($tree);
        $this->clearTree($tree, $noParent);
        return $this->sortByPriority($tree);
    }

    /**
     * Сохранить порядок.
     *
     * @param array $data
     * @return bool
     */
    public function saveOrder(array $data)
    {
        try {
            $this->setItemsWeight($data, 0);
        }
        catch (\Exception $exception) {
            return false;
        }
        return true;
    }


    /**
     * Задать порядок.
     *
     * @param array $items
     * @param int $parent
     */
    protected function setItemsWeight(array $items, int $parent)
    {
        foreach ($items as $priority => $item) {
            $id = $item["id"];
            if (! empty($item["children"])) {
                $this->setItemsWeight($item["children"], $id);
            }
            $parentId = ! empty($parent) ? $parent : null;
            // Обновление Категории.
            $department = StaffDepartment::query()
                ->where("id", $id)
                ->first();
            $department->priority = $priority;
            $department->parent_id = $parentId;
            $department->save();
        }
    }

    /**
     * Сортировка результата.
     *
     * @param $tree
     * @return array
     */
    protected function sortByPriority($tree)
    {
        $sorted = array_values(Arr::sort($tree, function ($value) {
            return $value["priority"];
        }));
        foreach ($sorted as &$item) {
            if (! empty($item["children"])) {
                $item["children"] = self::sortByPriority($item["children"]);
            }
        }
        return $sorted;
    }

    /**
     * Очистить дерево от дочерних.
     *
     * @param $tree
     * @param $noParent
     */
    protected function clearTree(&$tree, $noParent)
    {
        foreach ($noParent as $id) {
            $this->removeChildren($tree, $id);
        }
    }

    /**
     * Убрать подкатегории.
     *
     * @param $tree
     * @param $id
     */
    protected function removeChildren(&$tree, $id)
    {
        if (empty($tree[$id])) {
            return;
        }
        $item = $tree[$id];
        foreach ($item["children"] as $key => $child) {
            $this->removeChildren($tree, $key);
            if (! empty($tree[$key])) {
                unset($tree[$key]);
            }
        }
    }

    /**
     * Добавить дочернии элементы.
     *
     * @param $tree
     */
    protected function addChildren(&$tree)
    {
        foreach ($tree as $id => $item) {
            if (empty($item["parent"])) {
                continue;
            }
            $this->addChild($tree, $item, $id);
        }
    }

    /**
     * Добавить дочерний элемент.
     *
     * @param $tree
     * @param $item
     * @param $id
     * @param bool $children
     */
    protected function addChild(&$tree, $item, $id, $children = false)
    {
        // Добавление к дочерним.
        if (! $children) {
            $tree[$item["parent"]]["children"][$id] = $item;
        }
        // Обновление дочерних.
        else {
            $tree[$item["parent"]]["children"][$id]["children"] = $children;
        }

        $parent = $tree[$item["parent"]];
        if (! empty($parent["parent"])) {
            $items = $parent["children"];
            $this->addChild($tree, $parent, $parent["id"], $items);
        }
    }

    /**
     * Получить данные по дереву.
     *
     * @return array
     */
    protected function makeTreeDataWithNoParent()
    {
        $departments = DB::table("staff_departments")
            ->select("id", "title", "slug", "short","description", "parent_id", "published_at","priority")
            ->orderBy("parent_id")
            ->get();

        $tree = [];
        $noParent = [];
        foreach ($departments as $department) {
            $tree[$department->id] = [
                "title" => $department->title,
                'slug' => $department->slug,
                'short' => $department->short,
                'description' => $department->description,
                'parent' => $department->parent_id,
                "priority" => $department->priority,
                "published_at" => $department->published_at,
                "id" => $department->id,
                'children' => [],
                "url" => route("admin.departments.show", ['department' => $department->slug]),
                "siteUrl" => route("site.departments.show", ["department" => $department->slug]),
            ];
            if (empty($department->parent_id)) {
                $noParent[] = $department->id;
            }
        }
        return [$tree, $noParent];
    }

    /**
     * Получить id всех подкатегорий.
     *
     * @param StaffDepartment $department
     * @param bool $includeSelf
     * @return array
     */
    public function getDepartmentChildren(StaffDepartment $department, $includeSelf = false)
    {
        $key = "staff-department-actions-getDepartmentChildren:{$department->id}";
        $children = Cache::rememberForever($key, function () use ($department) {
            $children = [];
            $collection = StaffDepartment::query()
                ->select("id")
                ->where("parent_id", $department->id)
                ->get();
            foreach ($collection as $child) {
                $children[] = $child->id;
                $departments = $this->getDepartmentChildren($child);
                if (! empty($departments)) {
                    foreach ($departments as $id) {
                        $children[] = $id;
                    }
                }
            }
            return $children;
        });
        if ($includeSelf) {
            $children[] = $department->id;
        }
        return $children;
    }

    /**
     * Очистить кэш списка id категорий.
     *
     * @param StaffDepartment $department
     */
    public function forgetDepartmentChildrenIdsCache(StaffDepartment $department)
    {
        Cache::forget("department-actions-getDepartmentChildren:{$department->id}");
        $parent = $department->parent;
        if (! empty($parent)) {
            $this->forgetDepartmentChildrenIdsCache($parent);
        }
    }

    /**
     * Admin breadcrumbs
     *
     * @param StaffDepartment $department
     * @param bool $isEmployeePage
     * @return array
     *
     */
    public function getAdminBreadcrumb(StaffDepartment $department, $isEmployeePage = false)
    {
        $breadcrumb = [];
        if (! empty($department->parent)) {
            $breadcrumb = $this->getAdminBreadcrumb($department->parent);
        }
        else {
            $breadcrumb[] = (object) [
                "title" => config("site-staff.sitePackageName"),
                "url" => route("admin.departments.index"),
                "active" => false,
            ];
        }
        $routeParams = Route::current()->parameters();
        $isEmployeePage = $isEmployeePage && ! empty($routeParams["employee"]);
        $active = ! empty($routeParams["department"]) &&
            $routeParams["department"]->id == $department->id &&
            ! $isEmployeePage;
        $breadcrumb[] = (object) [
            "title" => $department->title,
            "url" => route("admin.departments.show", ["department" => $department]),
            "active" => $active,
        ];
        if ($isEmployeePage) {
            $employee = $routeParams["employee"];
            $breadcrumb[] = (object) [
                "title" => $employee->title,
                "url" => route("admin.employees.show", ["employee" => $employee]),
                "active" => true,
            ];
        }
        return $breadcrumb;
    }

    /**
     * Get root departments
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     *
     */

    public function getRootDepartments(){

        $rootDepartments = StaffDepartment::query()
            ->whereNull("parent_id")
            ->whereNotNull('published_at')
            ->orderBy("priority")
            ->get();

        return $rootDepartments;
    }

    /**
     * Получить дерево подкатегорий.
     *
     * @param StaffDepartment $department
     * @return array
     */
    public function getChildrenTree(StaffDepartment $department)
    {
        list($tree, $noParent) = $this->makeChildrenTreeData($department);
        $this->addChildren($tree);
        $this->clearTree($tree, $noParent);
        return $tree;
    }

    /**
     * Получить данные по категориям.
     *
     * @return array
     */
    protected function makeChildrenTreeData(StaffDepartment $parent)
    {
        $childrenIds = self::getDepartmentChildren($parent);
        $departments = DB::table("staff_departments")
            ->select("id", "title", "slug", "short", "parent_id", "published_at", "priority")
            ->whereIn('id', $childrenIds)
            ->orderBy("parent_id")
            ->orderBy('priority')
            ->get();

        $tree = [];
        $noParent = [];
        foreach ($departments as $department) {
            $tree[$department->id] = [
                "title" => $department->title,
                'slug' => $department->slug,
                'short' => $department->short,
                'parent' => $department->parent_id,
                "priority" => $department->priority,
                "published_at" => $department->published_at,
                "id" => $department->id,
                'children' => [],
                "url" => route("admin.departments.show", ['department' => $department->slug]),
                "siteUrl" => route("site.departments.show", ["department" => $department->slug]),
            ];
            if ($parent->id == $department->parent_id) {
                $noParent[] = $department->id;
            }
        }
        return [$tree, $noParent];
    }

    /**
     * Получить id всех родителей.
     *
     * @param StaffDepartment $department
     * @return array
     */
    public function getDepartmentParents(StaffDepartment $department)
    {
        $key = "staff-department-actions-getDepartmentParents:{$department->id}";
        $parentsIds = Cache::rememberForever($key, function () use ($department) {
            $parentsIds = [];
            $collection = StaffDepartment::query()
                ->select("id")
                ->where("id", $department->parent->id)
                ->get();
            foreach ($collection as $parent) {
                $parentsIds[] = $parent->id;
                $departments = $this->getDepartmentParents($parent);
                if (! empty($departments)) {
                    foreach ($departments as $id) {
                        $parentsIds[] = $id;
                    }
                }
            }
            return $parentsIds;
        });

        return $parentsIds;
    }
    /**
     * Очистить кэш списка id родителей.
     *
     * @param StaffDepartment $department
     */
    public function forgetDepartmentParentsCache(StaffDepartment $department)
    {
        Cache::forget("staff-department-actions-getDepartmentParents:{$department->id}");
        $parent = $department->parent;
        if (! empty($parent)) {
            $this->forgetDepartmentParentsCache($parent);
        }
    }


    /**
     * Получить id позиций отделы, либо  отдела и под-отделов.
     *
     * @param int $departmentId
     * @param $includeSubs
     * @return mixed
     */
    public function getDepartmentEmployeesIds($departmentId)
    {
        $department = StaffDepartment::query()->where("id","=",$departmentId)->first();
        $key = "staff-department-actions-getDepartmentEmployees:{$department->id}";
        return Cache::rememberForever($key, function() use ($department) {
            $employees = $department->employees;
            foreach ($employees as $key => $item) {
                $items[$item->id] = $item;
            }
            return $items;
        });
    }

    /**
     * Очистить кэш идентификаторов позиций.
     *
     * @param StaffDepartment $department
     */
    public function forgetDepartmentEmployeesIds(StaffDepartment $department)
    {
        $keys = ["staff-department-actions-getDepartmentEmployees:{$department->id}"];
        foreach ($keys as $key){
            Cache::forget("$key");
            if (! empty($department->parent_id)) {
                $this->forgetDepartmentEmployeesIds($department->parent);
            }
        }
    }
}