export const filterSelectsOptions = () => {
    let selectedOptions = [];
    const selects = document.querySelectorAll(
        '#prisjakt_feed_field_mapping table tr:not(.hidden-rows) td:first-child select'
    );

    selects.forEach((select) => {
        const value = select.value;

        if (value) {
            selectedOptions.push(value);
        }
    })


    selects.forEach((select) => {
        const value = select.value;

        Object.values(select.options).forEach((option) => {
            option.removeAttribute('hidden')

            if (selectedOptions.includes(option.value) && option.value !== value) {
                option.setAttribute('hidden', 'hidden')
            }
        });
    });
}

export const setupFieldMappingSelects = () => {
    const selects = document.querySelectorAll(
        '#prisjakt_feed_field_mapping tr:not(.hidden-rows) td:first-child select'
    );

    selects.forEach((select) => {
        select.removeEventListener('change', filterSelectsOptions, true);
        select.addEventListener('change', filterSelectsOptions, true);
    });

    filterSelectsOptions();
};

