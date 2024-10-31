import {sendAjax} from './ajax';
import {setupSelects} from './feedFilters';
import {setupFieldMappingSelects} from "./fieldMapping";

class PrisjaktFeedActions {
    constructor() {
        const {form_id, steps, step} = prisjakt_ajax_object;

        this.ajaxFormData = new FormData();

        this.actions = [
            'continue',
            'save_continue',
            'back',
            'generate',
            'add_field_mapping',
            'add_custom_field',
            'add_filter',
        ];

        this.rowActions = ['delete_row'];

        this.validates = ['field_mapping_text_validate'];

        this.form = document.querySelector(`#${form_id}`);

        /*
         * Set default step
         */
        if (steps) {
            this.setStep(step);
        }

        this.setupActions();
        this.setupRowActions();
    }

    getCamelizeActionCallbackName(action) {
        const camelizeAction = this.camelize(action.replaceAll('_', ' '));
        return `${this.camelize(camelizeAction)}Callback`;
    }

    setupActions() {
        this.actions.forEach((action) => {
            const buttons = document.querySelectorAll(
                'input[data-action="' + action + '"]'
            );

            if (buttons) {
                buttons.forEach((button) => {
                    button.addEventListener('click', (event) => {
                        this.ajaxFormData = new FormData();

                        event.preventDefault();

                        const callback =
                            this.getCamelizeActionCallbackName(action);
                        const {target} = event;

                        const data = {
                            target,
                            ajaxAction: target.getAttribute('data-ajax-action'),
                            prefix: target.getAttribute('data-prefix'),
                            action: target.getAttribute('data-action'),
                            step: target.getAttribute('data-step'),
                            event,
                        };

                        this[callback](data);
                    });
                });
            }
        });
    }

    setupRowActions() {
        this.rowActions.forEach((action) => {
            const actionButtons = document.querySelectorAll(
                'span[data-action="' + action + '"]'
            );

            if (actionButtons) {
                actionButtons.forEach((button) => {
                    const callback =
                        this[this.getCamelizeActionCallbackName(action)];

                    button.removeEventListener('click', callback, true);
                    button.addEventListener('click', callback, true);
                });
            }
        });

        this.validates.forEach((classValidation) => {
            const elements = document.querySelectorAll('.' + classValidation);

            if (elements) {
                elements.forEach((element) => {
                    const callback =
                        this[
                            this.getCamelizeActionCallbackName(classValidation)
                            ];

                    element.removeEventListener('change', callback, true);
                    element.addEventListener('change', callback, true);
                });
            }
        });
    }

    camelize(str) {
        return str
            .replace(/(?:^\w|[A-Z]|\b\w)/g, function (word, index) {
                return index === 0 ? word.toLowerCase() : word.toUpperCase();
            })
            .replace(/\s+/g, '');
    }

    isHidden(el) {
        return el.offsetParent === null;
    }

    setStep(step) {
        const toggleStepClass = 'show-step';
        const {steps, prefix} = prisjakt_ajax_object;

        steps.forEach((stepItem) => {
            const stepElement = document.querySelector('#' + prefix + stepItem);

            if (step === stepItem) {
                stepElement.classList.add(toggleStepClass);
                this.ajaxFormData.append('feed_step', stepItem);
            } else {
                stepElement.classList.remove(toggleStepClass);
            }
        });
    }

    validateElements(event) {
        let validated = true;
        const {target} = event;
        const postbox = target.closest('.postbox');
        const table = postbox.querySelector('table');
        const stepFormElements = table.querySelectorAll(
            'input:not([type="button"]), select'
        );

        const {validation_messages} = prisjakt_ajax_object;

        stepFormElements.forEach((element) => {
            const isRequired = element.required;

            if (isRequired && !this.isHidden(element)) {
                const max = element.getAttribute('max');
                const min = element.getAttribute('min');

                const elementValueLength = element.value.length;
                const emptyValue = !element.value;

                element.setCustomValidity('');

                switch (element.type) {
                    case 'text':
                        if (emptyValue) {
                            element.setCustomValidity(
                                validation_messages.text_value_missing
                            );
                            break;
                        }

                        if (elementValueLength > Number(max)) {
                            element.setCustomValidity(
                                validation_messages.max_text_length +
                                ' ' +
                                max +
                                '.'
                            );
                        }

                        if (elementValueLength < Number(min)) {
                            element.setCustomValidity(
                                validation_messages.min_text_length +
                                ' ' +
                                min +
                                '.'
                            );
                        }

                        break;
                    case 'select-one':
                        if (emptyValue) {
                            element.setCustomValidity(
                                validation_messages.select_value_missing
                            );
                        }
                        break;
                    default:
                }

                if (!element.checkValidity()) {
                    element.reportValidity();

                    if (validated) {
                        validated = false;
                    }
                }
            }
        });

        return validated;
    }

    saveData(data, callback = function () {
    }) {
        const {ajaxAction} = data;
        const {ajax_nonce, post_id, ajax_url} = prisjakt_ajax_object;

        const formData = {
            form_elements: new URLSearchParams(
                new FormData(this.form)
            ).toString(),
        };

        this.ajaxFormData.append('action', ajaxAction);
        this.ajaxFormData.append('security', ajax_nonce);
        this.ajaxFormData.append('post_id', post_id);
        this.ajaxFormData.append('data', JSON.stringify(formData));

        sendAjax(ajax_url, this.ajaxFormData, callback);
    }

    fieldMappingTextValidateCallback(event) {
        const {target} = event;
        target.value = target.value.replace(/\s+/g, '_').toLowerCase();
    }

    deleteRowCallback(event) {
        const {target} = event;
        const row = target.closest('tr');

        row.remove();

        setupFieldMappingSelects();
    }

    prepareDefaultRow(row) {
        const clonedDefaultRow = row.cloneNode(true);
        clonedDefaultRow.classList.remove(...clonedDefaultRow.classList);

        return clonedDefaultRow;
    }

    continueCallback(data) {
        const {step, event} = data;
        const {steps} = prisjakt_ajax_object;

        const validated = this.validateElements(event);

        if (validated && steps) {
            this.setStep(step);
        }

        this.saveData(data);
    }

    insertRow(event) {
        const {target} = event;

        const actionIndex = target.getAttribute('data-row-action');
        const tableTbody = target.closest('tbody');
        const defaultRow = tableTbody.querySelector(
            `.action-index-${actionIndex}`
        );
        const firstHiddenRow = tableTbody.querySelectorAll('tr.hidden-rows')[0];
        const clonedDefaultRow = this.prepareDefaultRow(defaultRow);

        firstHiddenRow.parentNode.insertBefore(
            clonedDefaultRow,
            firstHiddenRow
        );

        this.setupRowActions();
    }

    addFieldMappingCallback(event) {
        this.insertRow(event);

        setupFieldMappingSelects()
    }

    addCustomFieldCallback(event) {
        this.insertRow(event);
    }

    addFilterCallback(event) {
        this.insertRow(event);

        setupSelects();
    }

    saveContinueCallback(data) {
        this.continueCallback(data);
    }

    backCallback(data) {
        const {steps} = prisjakt_ajax_object;
        const {step, event} = data;

        const validated = this.validateElements(event);

        if (validated && steps) {
            this.setStep(step);
        }

        this.saveData(data);
    }

    generateCallback(data) {
        const {steps} = prisjakt_ajax_object;

        /*
         * Set default step on save feed
         */
        this.ajaxFormData.append('feed_step', steps[0]);

        /*
         * Enable feed for cron and manual generate
         */
        this.ajaxFormData.append('is_active', true);

        this.saveData(data, function (result) {
            const {form_id} = prisjakt_ajax_object;

            document.querySelector(`#${form_id}`).submit();
        });
    }
}

new PrisjaktFeedActions();
