const updateFieldValue = (target, source) => {
    const load = source && Object.keys(source).length !== 0;

    const newElement = !load
        ? document
            .querySelector(
                '#prisjakt_feed_filters tr.hidden-rows .prisjakt-filter-value'
            )
            .cloneNode(true)
        : document.createElement('select');

    const elementToReplace =
        target.parentNode.parentNode.parentNode.querySelector(
            '.prisjakt-filter-value'
        );

    const elementClass = elementToReplace.getAttribute('class');
    const elementName = elementToReplace.getAttribute('name');
    const elementValue = elementToReplace.value;

    newElement.setAttribute('class', elementClass);
    newElement.setAttribute('name', elementName);
    newElement.setAttribute('value', elementValue);

    if (load) {
        for (const [key, value] of Object.entries(source)) {
            const opt = document.createElement('option');

            if (elementToReplace.value === key) {
                opt.setAttribute('selected', 'selected');
            }

            opt.value = key;
            opt.innerHTML = value;
            newElement.appendChild(opt);
        }
    }

    elementToReplace.replaceWith(newElement);
};

const setupSelectChange = (event) => {
    const value = event.target.value;
    const source = prisjakt_ajax_object.feed_filters[value];
    updateFieldValue(event.target, source);
};

export const setupSelects = () => {
    const selects = document.querySelectorAll(
        '#prisjakt_feed_filters tr:not(.hidden-rows) .prisjakt-filter-select'
    );

    selects.forEach((select) => {
        select.removeEventListener('change', setupSelectChange, true);
        select.addEventListener('change', setupSelectChange, true);
    });
};

export const updateFields = () => {
    const selects = document.querySelectorAll(
        '#prisjakt_feed_filters tr:not(.hidden-rows) .prisjakt-filter-select'
    );

    selects.forEach((select) => {
        const value = select.value;
        const source = prisjakt_ajax_object.feed_filters[value];

        updateFieldValue(select, source);
    });
};
