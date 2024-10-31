import './actions';
import { sendAjax } from './ajax';

class PrisjaktFeedForm {
	constructor() {
		this.form = document.querySelector(
			`form#${prisjakt_ajax_object.form_id}`
		);

		if (!this.form) return;

		this.checkboxes = document.querySelectorAll(
			`form#${prisjakt_ajax_object.form_id} input[type='checkbox']`
		);

		this.setupListeners();
	}

	setupListeners() {
		this.checkboxes.forEach((checkbox) => {
			checkbox.addEventListener('click', (event) => {
				const name = event.target.getAttribute('name');

				this.sendFormAjax({
					form_elements: new URLSearchParams(
						new FormData(this.form)
					).toString(),
					updated_option: name,
				});
			});
		});
	}

	sendFormAjax(formData) {
		const ajaxFormData = new FormData();

		ajaxFormData.append('action', prisjakt_ajax_object.ajax_action);
		ajaxFormData.append('security', prisjakt_ajax_object.ajax_nonce);
		ajaxFormData.append('data', JSON.stringify(formData));

		/*
		 * Extra fields for ajax callback
		 */

		for (const [key, value] of Object.entries(
			prisjakt_ajax_object.extra_data
		)) {
			ajaxFormData.append(key, value);
		}

		sendAjax(prisjakt_ajax_object.ajax_url, ajaxFormData);
	}
}

new PrisjaktFeedForm();
