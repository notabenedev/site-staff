document.addEventListener('DOMContentLoaded', function(){
    // modal whatever fill
    const employeeModal = document.getElementById('staffEmployeeModal')
    const modalBodyInput = document.querySelector('#staffEmployeeTitle')
    const modalBodyHead = document.querySelector('#staffEmployeeModalHeader')
    if (employeeModal) {
        employeeModal.addEventListener('show.bs.modal', event => {

            // Button that triggered the modal
            const button = event.relatedTarget
            // Extract info from data-bs-* attributes
            const recipient = button.getAttribute('data-bs-whatever')
            // If necessary, you could initiate an Ajax request here
            // and then do the updating in a callback.

            // Update the modal's content.
            modalBodyInput.value = recipient
            modalBodyHead.innerHTML = recipient

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
            modalBodyInput.value =  modalBodyHead.innerHTML
        })
    }
});