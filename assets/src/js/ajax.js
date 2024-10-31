export const sendAjax = (ajaxUrl, ajaxData, callback = function () {}) => {
	fetch(ajaxUrl, {
		method: 'POST',
		body: ajaxData,
	}) // wrapped
		.then((res) => res.text())
		.then((data) => {
			// eslint-disable-next-line no-console
			return callback(data);
		})
		.catch((err) => {
			// eslint-disable-next-line no-console
			return callback(err);
		});
};

export default sendAjax;
