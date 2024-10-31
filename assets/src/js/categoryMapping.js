import autocomplete from 'autocompleter';

class PrisjaktFeedCategoryMapping {
	constructor() {
		this.setupInputs();
	}

	setupInputs() {
		const inputs = document.querySelectorAll(
			'.category_input_autocomplete'
		);

		inputs.forEach((input) => this.setupInputAutocomplete(input));
	}

	setupInputAutocomplete(input) {
		autocomplete({
			input,
			fetch(text, update) {
				text = text.toLowerCase();

				/*
				 * If text start with numbers change filter type
				 */

				const isNumber = Number.isInteger(Number(text));

				console.log(prisjakt_ajax_object.category_mapping);
				const suggestions =
					prisjakt_ajax_object.category_mapping.filter((n) => {
						if (isNumber) {
							return n.label.toLowerCase().startsWith(text);
						}

						return n.label.toLowerCase().includes(text);
					});

				update(suggestions);
			},
			onSelect(item) {
				input.value = item.label.split(' - ')[1];
			},
			render(item) {
				const div = document.createElement('div');
				div.textContent = item.label;
				return div;
			},
			debounceWaitMs: 400,
			preventSubmit: true,
		});
	}
}

new PrisjaktFeedCategoryMapping();
