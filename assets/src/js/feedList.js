import { sendAjax } from './ajax';

class PrisjaktFeedFeedList {
	constructor() {
		this.checkboxes = document.querySelectorAll(
			"#posts-filter input[type='checkbox'].prisjakt-switch"
		);

		this.setupActions();
	}

	setupFormData(postId) {
		const { ajax_action, ajax_nonce } = prisjakt_ajax_object;

		this.ajaxFormData = new FormData();
		this.ajaxFormData.append('action', ajax_action);
		this.ajaxFormData.append('security', ajax_nonce);
		this.ajaxFormData.append('post_id', postId);
	}

	setupActions() {
		this.checkboxes.forEach((checkbox) => {
			checkbox.addEventListener('click', (event) =>
				this.updateCheckbox(event)
			);
		});
	}

	updateCheckbox(event) {
		const { target } = event;
		const { ajax_url } = prisjakt_ajax_object;

		const feedStatus = target.checked;
		const postId = target.getAttribute('data-post-id');

		this.setupFormData(postId);

		this.ajaxFormData.append('is_active', feedStatus);

		sendAjax(ajax_url, this.ajaxFormData, function (data) {});
	}
}

new PrisjaktFeedFeedList();
