<?php

namespace Notabenedev\SiteStaff\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Meta;
use App\StaffDepartment;
use App\StaffEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffEmployeeController extends Controller
{

    const PAGER = 20;

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(StaffEmployee::class, "employee");
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
        $query = $request->query;
        $employees = StaffEmployee::query();

        if ($query->get('title')) {
            $title = trim($query->get('title'));
            $employees->where('title', 'LIKE', "%$title%");
        }
        $employees->orderBy('created_at', 'desc');
        return view("site-staff::admin.employees.index", [
            'employeesList' => $employees->paginate(self::PAGER)->appends($request->input()),
            'query' => $query,
            'per' => self::PAGER,
            'page' => $query->get('page', 1) - 1
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view("site-staff::admin.employees.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());
        $employee = StaffEmployee::create($request->all());
        $employee->updateBtnEnabled($request->get("check-btn") ? 1:0);
        $employee->uploadImage($request, "employees/main");
        $employee->updateDepartments($request->all(), true);
        $employee->publishIfPublicDepartment();
        return redirect()
            ->route("admin.employees.show", ['employee' => $employee])
            ->with('success', 'Успешно создано');
    }

    /**
     * Валидация сохранения.
     *
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "min:2", "max:100", "unique:staff_employees,title"],
            "slug" => ["nullable", "min:2", "max:100", "unique:staff_employees,slug"],
            "image" => ["nullable", "image"],
        ], [], [
            "title" => config("site-staff.employeeTitleName"),
            "slug" => "Адресная строка",
            "image" => "Главное изображение",
        ])->validate();
    }

    /**
     * Display the specified resource.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(StaffEmployee $employee)
    {
        return view("site-staff::admin.employees.show", [
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     *
     */
    public function edit(StaffEmployee $employee)
    {
        return view("site-staff::admin.employees.edit", [
            'employee' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StaffEmployee $employee
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function update(Request $request, StaffEmployee $employee)
    {
        $this->updateValidator($request->all(), $employee);
        $employee->update($request->all());
        $employee->updateBtnEnabled($request->get("check-btn") ? 1:0);
        $employee->uploadImage($request, "employees/main");
        $employee->updateDepartments($request->all(), true);

        return redirect()
            ->route('admin.employees.show', ['employee' => $employee])
            ->with('success', 'Успешно обновленно');
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     * @param StaffEmployee $employee
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function updateValidator($data, StaffEmployee $employee)
    {
        $id = $employee->id;
        Validator::make($data, [
            "title" => ["required", "min:2", "max:100", "unique:staff_employees,title,{$id}"],
            "slug" => ["nullable", "min:2", "max:100", "unique:staff_employees,slug,{$id}"],
            "image" => ["nullable", "image"],
        ], [], [
            'title' => config("site-staff.employeeTitleName"),
            "slug" => "Адресная строка",
            'main_image' => 'Главное изображение',
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(StaffEmployee $employee)
    {
        $employee->delete();
        return redirect()
            ->route("admin.employees.index")
            ->with('success', 'Успешно удалено');
    }

    /**
     * Страница метатегов.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function metas(StaffEmployee $employee)
    {
        $this->authorize("update", $employee);
        $this->authorize("viewAny", Meta::class);
        return view('site-staff::admin.employees.metas', [
            'employee' => $employee,
        ]);
    }

    /**
     * Страница галлереи.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function gallery(StaffEmployee $employee)
    {
        $this->authorize("update", $employee);
        return view("site-staff::admin.employees.gallery", [
            'employee' => $employee,
        ]);
    }

    /**
     * Удалить главное изображение.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteImage(StaffEmployee $employee)
    {
        $this->authorize("update", $employee);
        $employee->clearImage();
        return redirect()
            ->back()
            ->with('success', 'Изображение удалено');
    }

    /**
     * Изменить статус публикации.
     *
     * @param StaffEmployee $employee
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish(StaffEmployee $employee)
    {
        if (! $employee->published_at){
            if (! $employee->publishIfPublicDepartment())
                return redirect()
                    ->back()
                    ->with('danger',"Статус публикации не может быть изменен! Хотя бы один родитель должен быть опубликован.");
        }
        else{
            $employee->published_at =  null;
            $employee->save();
        }
        return redirect()
            ->back()
            ->with('success',"Статус публикации изменен");

    }


}
