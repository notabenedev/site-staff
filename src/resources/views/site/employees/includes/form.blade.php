<form class="sending-form-custom position-relative" name="staff-employee-form" id="staffEmployeeForm">
    @hiddenCaptcha
    <div class="form-row">
        @if (config("site-staff.employeeTitleInputName"))
            <div class="col-12">
                <div class="mb-3">
                    <span class="d-none" id="staffEmployeeModalHeader"></span>
                    <label for="staffEmployeeTitle">{{ config("site-staff.employeeTitleInputName") }} <span class="text-danger">*</span></label>
                    <input id="staffEmployeeTitle"
                           name="title"
                           @isset($title)
                           value="{{ $title }}"
                           @else
                           placeholder="{{ config("site-staff.employeeTitleInputName") }}"
                           @endisset
                           required
                           class="form-control">
                </div>
            </div>
            @includeIf("staff-types::site.staff-offers.includes.input-address")
        @endif
        <div class="col-12">
            <div class="mb-3">
                <label for="staffEmployeeName">Имя <span class="text-danger">*</span></label>
                <input type="text"
                       id="staffEmployeeName"
                       name="name"
                       required
                       class="form-control">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="staffEmployeePhone">Номер телефона <span class="text-danger">*</span></label>
                <input type="text"
                       id="staffEmployeePhone"
                       name="phone"
                       required
                       class="form-control">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="staffEmployeeMessage">Комментарий</label>
                <textarea class="form-control" name="message" id="staffEmployeeMessage" rows="3">
                    {{ old('message') }}
                </textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                @includeIf("ajax-forms::site.includes.policy-input",["postfix" => "staffEmployee" ])
            </div>
        </div>
        <div class="col-12">
            <div class="btn-group"
                 role="group">
                <button type="submit" class="btn btn-primary">{{ config("site-staff.employeeSubmitName") }}</button>
            </div>
        </div>
    </div>
</form>