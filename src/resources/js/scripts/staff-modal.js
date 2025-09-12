document.addEventListener('DOMContentLoaded', function(){
    // modal whatever fill
    const employeeModal = document.getElementById('staffEmployeeModal')
    const modalBodyInput = document.querySelector('#staffEmployeeTitle')
    const modalBodyHead = document.querySelector('#staffEmployeeModalHeader')
    if (employeeModal && modalBodyInput) {
        employeeModal.addEventListener('show.bs.modal', event => {

            // Button that triggered the modal
            const button = event.relatedTarget
            // Extract info from data-bs-* attributes
            const recipient = button.getAttribute('data-bs-whatever')
            // Update the modal's content.
            modalBodyInput.value = recipient
            modalBodyHead.innerHTML = recipient

            const address = button.getAttribute('data-bs-whatever-address')
            const modalBodyInputAddress = document.querySelector('#staffEmployeeAddress')
            if (modalBodyInputAddress && address) {
                modalBodyInputAddress.value = address
            }

            // close form alerts
            let alerts = this.querySelectorAll('.alert')
            if (! alerts.length) return;
            for(let el of alerts){
                const alert = bootstrap.Alert.getOrCreateInstance(el);
                alert.close();
            }
        })
    }
    const employeeForm = document.querySelector('#staffEmployeeForm');
    if (employeeForm ){
        employeeForm.addEventListener('reset', event => {
            setTimeout(function() {
                // executes after the form has been reset
                modalBodyInput.value =  modalBodyHead.innerHTML
            }, 1);
        })
    }
});