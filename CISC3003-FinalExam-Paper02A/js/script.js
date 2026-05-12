const scenarioAForm = document.querySelector('#scenarioAForm');
const formHint = document.querySelector('#formHint');

if (scenarioAForm) {
    scenarioAForm.addEventListener('submit', (event) => {
        const checkedInterests = scenarioAForm.querySelectorAll('input[name="interests[]"]:checked');
        const message = scenarioAForm.querySelector('#message');

        if (!scenarioAForm.checkValidity() || checkedInterests.length === 0 || message.value.trim().length < 10) {
            event.preventDefault();
            formHint.textContent = 'Please complete all required fields and select at least one interest.';
            scenarioAForm.reportValidity();
            return;
        }

        formHint.textContent = 'Submitting validated data to PHP...';
    });
}
