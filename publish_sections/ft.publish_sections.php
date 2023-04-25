<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include(PATH_THIRD.'/publish_sections/config.php');

class Publish_sections_ft extends EE_Fieldtype {

	var $info = array(
		'name'		=> PUBLISH_SECTIONS_NAME,
		'version'	=> PUBLISH_SECTIONS_VERSION
	);

	public $disable_frontedit = true;
	
	function __construct() {
		ee()->lang->loadfile('publish_sections');
	}


	function accepts_content_type($name) {
		return ($name == 'channel');
	}


	function display_settings($data) {	
		ee()->load->model('addons_model');

		$format_options = ee()->addons_model->get_plugin_formatting(TRUE);

		$display_styles = array(
			'large' => lang('large'),
			'medium' => lang('medium'),
			'small' => lang('small')
		);
		
		$display_icons = array();

		include PATH_THIRD.'publish_sections/libraries/icons.php';

		foreach ($icons as $icon) {
			$display_icons[$icon] = '<i class="fal fal-fw fa-'.$icon.'"></i> ' . $icon;
		}

		$settings = array(
			'publish_sections' => array(
				'label' => $this->info['name'],
				'group' => 'publish_sections',
				'settings' => array(
					array(
						'title' => 'display_style',
						'desc' => lang('display_style_desc'),
						'fields' => array(
							'display_style' => array(
								'type' => 'select',
								'choices' => $display_styles,
								'value' => (isset($data['display_style'])) ? $data['display_style'] : 'medium'
							)
						)
					),
					array(
						'title' => 'display_icon',
						'desc' => lang('display_icon_desc'),
						'fields' => array(
							'display_icon' => array(
								'type' => 'dropdown',
								'choices' => $display_icons,
								'value' => (isset($data['display_icon'])) ? $data['display_icon'] : 'bookmark'
							)
						)
					),
					array(
						'title' => 'bg_color',
						'desc' => lang('bg_color_desc'),
						'fields' => array(
							'bg_color' => array(
								'type' => 'short-text',
								'value' => (isset($data['bg_color'])) ? $data['bg_color'] : '',
							)
						)
					)
				)
			)
		);
		return $settings;
	}


	function save_settings($data) {
		return array(
			'show_heading' => ee('Request')->post('show_heading'),
			'display_style' => ee('Request')->post('display_style'),
			'display_icon' => ee('Request')->post('display_icon'),
			'bg_color' => ee('Request')->post('bg_color'),
			'field_fmt' => 'none',
			'field_show_fmt' => 'n',
		);
	}


	function save($data) {
		return null;
	}


	function display_field($data) {
		$file = 'publish_sections';
		ee()->cp->load_package_css($file);
		ee()->cp->load_package_js($file);
		
		ee()->load->library('typography');
		ee()->typography->initialize();
		
		$sections = ee()->typography->parse_type(
			$this->settings['field_instructions'],
			array(
				'html_format' => 'all',
				'auto_links' => 'n',
				'allow_img_url' => 'y'
			)
		);
		$icon = '';
		$styles = '';
		$class = '';
		$has_instructions = '';
		if (!empty($this->settings['field_instructions'])) {
			$has_instructions = ' has-instructions';
		}
		$r = '<div class="publish-sections-field alert inline '.$this->settings['display_style'].$has_instructions.'">';
		if(!empty($this->settings['display_icon'])) {
			$icon = '<i class="fal fal-fw fa-'.$this->settings['display_icon'].'"></i> ';
		}
		if(!empty($this->settings['bg_color'])) {
			$styles .= 'background-color:'.$this->settings['bg_color'].';';
			$brightness = $this->get_brightness($this->settings['bg_color']);
			if ($brightness > 130) {
				//$styles .= 'color: var(--ee-text-normal);';
				$class = 'light';
			}
		}
		$label = trim(preg_replace('/^[-]+/', '', $this->settings['field_label']));
		$r .= '<h3 class="'.$class.'" style="'.$styles.'">'.$icon.$label.'</h3>';
		$r .= $sections;
		$r .= '</div>';
		
		return $r;
	}


	function replace_tag($data, $params = array(), $tagdata = FALSE) {
		return $data;
	}


	function update($current = '') {
		if($current == $this->info['version']) {
			return FALSE;
		}
		return TRUE;
	}


	private function get_brightness($hex) {
		// returns brightness value from 0 to 255
		// strip off any leading #
		$hex = str_replace('#', '', $hex);
		
		if (strlen($hex) == 3) {
			$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
		}
		
		$c_r = hexdec(substr($hex, 0, 2));
		$c_g = hexdec(substr($hex, 2, 2));
		$c_b = hexdec(substr($hex, 4, 2));

		return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
	}

}