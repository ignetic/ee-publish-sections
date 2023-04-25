<?php

include(PATH_THIRD.'/publish_sections/config.php');
return array(
	'author' => 'Simon Andersohn',
	'author_url' => 'https://github.com/ignetic',
	'description' => 'Add sections to the publish screen using field label and instructions.',
	'docs_url' => 'https://github.com/ignetic',
	'name' => PUBLISH_SECTIONS_NAME,
	'version' => PUBLISH_SECTIONS_VERSION,
	'fieldtypes' => array(
		'publish_sections' => array(
			'name' => PUBLISH_SECTIONS_NAME,
			'compatibility' => 'text'
		)
	),
	'namespace' => 'Ignetic\PublishSections'
);