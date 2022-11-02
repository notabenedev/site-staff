<?php

namespace Notabenedev\SiteStaff\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meta;
use App\StaffDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Notabenedev\SiteStaff\Facades\StaffDepartmentActions;

class StaffDepartmentController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(StaffDepartment::class, "department");
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function index(Request $request)
    {
        $view = $request->get("view","default");
        $isTree = $view == "tree";
        if ($isTree) {
            $departments = StaffDepartmentActions::getTree();
        }
        else {
            $collection = StaffDepartment::query()
                ->whereNull("parent_id")
                ->orderBy("priority","asc");
            $departments = $collection->get();
        }
        return view("site-staff::admin.departments.index", compact("departments", "isTree"));
    }

    /**
     * Show the form for creating a new resource
     *
     * @param StaffDepartment|null $department
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function create(StaffDepartment $department = null)
    {
        return view("site-staff::admin.departments.create", [
            "department" => $department,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param StaffDepartment|null $department
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function store(Request $request, StaffDepartment $department = null)
    {
        $this->storeValidator($request->all());
        if (empty($department)) {
            $item = StaffDepartment::create($request->all());
        }
        else {
            $item = $department->children()->create($request->all());
        }

        return redirect()
            ->route("admin.departments.show", ["department" => $item])
            ->with("success", "Добавлено");
    }

    /**
     * Validator
     *
     * @param array $data
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function storeValidator(array $data)
    {
        Validator::make($data, [
            "title" => ["required", "max:150"],
            "slug" => ["nullable", "max:150", "unique:staff_departments,slug"],
            "short" => ["nullable", "max:500"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
            "short" => "Краткое описание",
        ])->validate();
    }

    /**
     * Display the specified resource.
     *
     * @param StaffDepartment $department
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(StaffDepartment $department)
    {
        $childrenCount = $department->children->count();
        if ($childrenCount) {
            $children = $department->children()->orderBy("priority")->get();
        }
        else {
            $children = false;
        }
        return view("site-staff::admin.departments.show", [
            "department" => $department,
            "childrenCount" => $childrenCount,
            "children" => $children
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StaffDepartment $department
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function edit(StaffDepartment $department)
    {
        return view("site-staff::admin.departments.edit", compact("department"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StaffDepartment $department
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, StaffDepartment $department)
    {
        $this->updateValidator($request->all(), $department);
        $department->update($request->all());

        return redirect()
            ->route("admin.departments.show", ["department" => $department])
            ->with("success", "Успешно обновлено");
    }

    /**
     * Update validate
     *
     * @param array $data
     * @param StaffDepartment $department
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function updateValidator(array $data, StaffDepartment $department)
    {
        $id = $department->id;
        Validator::make($data, [
            "title" => ["required", "max:150"],
            "slug" => ["nullable", "max:150", "unique:staff_departments,slug,{$id}"],
            "short" => ["nullable", "max:500"],
        ], [], [
            "title" => "Заголовок",
            "slug" => "Адресная строка",
            "short" => "Краткое описание",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StaffDepartment $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(StaffDepartment $department)
    {
        $parent = $department->parent;

        $department->delete();
        if ($parent) {
            return redirect()
                ->route("admin.departments.show", ["department" => $parent])
                ->with("success", "Успешно удалено");
        }
        else {
            return redirect()
                ->route("admin.departments.index")
                ->with("success", "Успешно удалено");
        }
    }


    /**
     * Add metas to department
     *
     * @param StaffDepartment $department
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function metas(StaffDepartment $department)
    {
        $this->authorize("viewAny", Meta::class);
        $this->authorize("update", $department);

        return view("site-staff::admin.departments.metas", [
            'department' => $department,
        ]);
    }

    /**
     * Publish group
     *
     * @param StaffDepartment $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     */

    public function publish(StaffDepartment $department)
    {
        $this->authorize("update", $department);

        if ($department->publishCascade())
            return
                redirect()
                ->back()
                ->with("success", "Успешно изменено");
        else
            return
            redirect()
                ->back()
                ->with("danger",  "Статус не может быть изменен");
    }
}
