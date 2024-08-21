<!-- Modal Staff Employee -->
<div class="modal fade" id="staffEmployeeModal" tabindex="-1" aria-labelledby="staffEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffEmployeeModalLabel">
                    {{ ! empty(config("site-staff.employeeBntName")) ? config("site-staff.employeeBntName") : "" }}
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
@push('more-js')
    <script>
    const employeeModal = document.getElementById('staffEmployeeModal')
    if (employeeModal) {
    employeeModal.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an Ajax request here
    // and then do the updating in a callback.
    alert(recipient)
    // Update the modal's content.
    const modalTitle = employeeModal.querySelector('.modal-title')
    const modalBodyInput = employeeModal.querySelector('#staffEmployeeTitle')

    // modalTitle.textContent = `New message to ${recipient}`
    modalBodyInput.value = recipient
    })
    }
    </script>
    @endpush
