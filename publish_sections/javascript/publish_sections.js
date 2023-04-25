/* Publish Sections - position outside of fieldset */
$('.publish-sections-field').each(function() {
	var $field_control = $(this).closest('.field-control');
	var $fieldset = $field_control.closest('fieldset');
	$(this).insertBefore($fieldset);
	$($fieldset, $field_control).remove();
	$(this).show();
});
