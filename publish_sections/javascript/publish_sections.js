/* Publish Sections js */
(function($) {

	const localStorageKey = 'publish-sections-fieldset';

	$('.publish-sections-field').each(function() {

		$fieldset = $(this).closest('fieldset');
		$fieldset.addClass('publish-sections-fieldset');
		
		if ($(this).hasClass('collapsible')) {
			$fieldset.addClass('collapsible');
		}
		if ($(this).hasClass('collapsed')) {
			$fieldset.addClass('collapsed');
		}

	}).promise().done( function() { 
		let collapsedItems = {};

		if (window.localStorage) {
			let localStorageItems = window.localStorage ? window.localStorage[localStorageKey] : null;

			if (localStorageItems) {
				collapsedItems = JSON.parse(localStorageItems);
			}

			$.each(collapsedItems, function(id) {
				let state = collapsedItems[id],
					$section = $('.publish-sections-fieldset[data-field_id="' + id + '"]');

				if (state == 'collapsed') {
					$section.removeClass('expanded').addClass('collapsed');
				} else {
					$section.removeClass('collapsed');
				}
			});
		}

		$('.publish-sections-fieldset.collapsible.collapsed').each(function() {
			$(this).nextUntil('.publish-sections-fieldset').slideUp('fast');
		});
	});

	$('.publish-sections-fieldset').on('click', '.expand-btn', function(e) {
		let collapsedItems = {},
			$fieldset = $(e.delegateTarget),
			fieldId = $fieldset.data('field_id'),
			$sections = $fieldset.nextUntil('.publish-sections-fieldset');

		if (window.localStorage) {
			let localStorageItems = window.localStorage ? window.localStorage[localStorageKey] : null;
			if (localStorageItems) {
				collapsedItems = JSON.parse(localStorageItems);
			}
		}

		if ($fieldset.hasClass('collapsed')) {
			$sections.slideDown();
			$fieldset.removeClass('collapsed');
			collapsedItems[fieldId] = 'expanded';
		} else {
			$sections.slideUp();
			$fieldset.addClass('collapsed');
			collapsedItems[fieldId] = 'collapsed';
		}

		if (window.localStorage) {
			localStorage.setItem(localStorageKey, JSON.stringify(collapsedItems));
		}
	});

})(jQuery);
