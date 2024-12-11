import $ from 'jquery'

$(document).ready(function () {
    const countrySelect = $('.country-select');
    const stateField = $('.state-province-field');

    console.log(countrySelect)
    if (countrySelect && stateField) {
        const countriesWithStates = stateField.data('countries-with-states');
        const stateContainer = stateField.parent('div');

        function toggleStateField() {
            const isStateRequired = countriesWithStates.includes(countrySelect.val());
            isStateRequired ? stateContainer.show() : stateContainer.hide();
            stateField.required = isStateRequired;
            if (!isStateRequired) {
                stateField.value = '';
            }
        }

        toggleStateField();
        countrySelect.on('change', toggleStateField);
    }

})
