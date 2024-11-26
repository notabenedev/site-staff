<!-- Modal Staff Employee -->
<div class="modal fade" id="staffEmployeeModal" tabindex="-1" aria-labelledby="staffEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffEmployeeModalLabel">
                    {{ ! empty(config("site-staff.employeeBtnName")) ? config("site-staff.employeeBtnName") : "" }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                @if (config("site-staff.employeeModalAbout"))
                    <p class="text-secondary">
                        {{ config("site-staff.employeeModalAbout") }}
                    </p>
                @endif
                @include("site-staff::site.employees.includes.form")
            </div>
        </div>
    </div>
</div>

