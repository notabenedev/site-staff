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
                <div class="custom-control custom-checkbox">
                    <input type="checkbox"
                           class="custom-control-input"
                           id="staffEmployeePrivacy_policy"
                           checked
                           required
                           name="privacy_policy">
                    <label class="custom-control-label" for="staffEmployeePrivacy_policy">
                        <a href="#agreementModal" data-bs-toggle="modal" data-bs-target="#agreementModal">Согласие на обработку персональных данных</a> и принимаю условия <a href="{{ route("policy") }}" target="_blank">Политики по обработке персональных данных</a>
                    </label>
                </div>
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