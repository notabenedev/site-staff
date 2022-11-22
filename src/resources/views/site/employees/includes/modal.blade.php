<!-- Modal Staff Employee -->
<div class="modal fade" id="staffEmployeeModal" tabindex="-1" aria-labelledby="staffEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffEmployeeModalLabel">
                    {{ ! empty(config("site-staff.employeeBntName")) ? config("site-staff.employeeBntName") : "" }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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