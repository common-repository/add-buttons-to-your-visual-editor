<?php
/*
Plugin Name: SK Add Visual Editor Buttons
Plugin URI: http://spottedkoi.com/plugins/add-visual-editor-buttons
Description: Adds buttons to the visual editor, which give you more functionality when editing posts.
Version: 1.0
Author: Contact Spotted Koi for WordPress help
Author URI: http://spottedkoi.com/?utm_source=skextendvisualeditor&utm_medium=pluginpage&utm_campaign=skextendvisualeditor
Notes: got the idea from here: http://wp-snippets.com/extend-wordpress-visual-editor/
*/

class sk_add_editor_buttons{
	const fieldId = 'sk_replace_howdy-message';
	const section = 'editor-settings';
	
	function add_more_buttons($buttons) {
		$fonts = $els = array();
		foreach (self::fields() as $field => $label) {
			if (get_option('sk_extra_buttons_'.$field) != false) {
				switch ($field) {
					case 'fontselect':
					case 'fontsizeselect':
					case 'styleselect':
						$fonts[] = $field;
						break;
					case 'cleanup':
						$buttons[] = '|';
						$buttons[] = $field;
						break;
					default:
						$els[] = $field;
						break;
				}
			}
		}
		if (count($fonts) > 0) {
			$fonts[] = '|';
		}
		if (count($els) > 0) {
			$els[] = '|';
		}
		array_splice($buttons, 2, 0, $els);
		array_splice($buttons, 1, 0, $fonts);
	 	return $buttons;
	}
	
	function add_field($args) {
		?>
		<input name="sk_extra_buttons_<?php echo $args['field'];?>" id="sk_extra_buttons_<?php echo $args['field'];?>" type="checkbox" value="1" <?php checked(1, get_option('sk_extra_buttons_'.$args['field'])); ?> class="code" />
		<?php
	}
	
	function add_section() {
		echo 'Check the boxes next to the items you would like to have turned on in your visual editor.';
	}
	
	function admin_init() {
		add_filter("mce_buttons_2", array('sk_add_editor_buttons', "add_more_buttons"));
		
		add_settings_section(sk_add_editor_buttons::section, 
							'Extra Visual Editor Buttons', 
							array('sk_add_editor_buttons', 'add_section'), 
							'writing');
		
		foreach (self::fields() as $field => $label) {
			add_settings_field('sk_extra_buttons_'.$field, 
								$label.': ', 
								array('sk_add_editor_buttons', 'add_field'), 
								'writing', 
								sk_add_editor_buttons::section, 
								array('field'=>$field));
			register_setting('writing', 'sk_extra_buttons_'.$field);
		}
	}
	
	public static function fields()
	{
		return array('hr' 				=> 'Horizontal Rule', 
					'sub'				=> 'Superscript',
					'sup'				=> 'Subscript',
					'fontselect'		=> 'Select Font',
					'fontsizeselect'	=> 'Select Fontsize',
					'cleanup'			=> 'Cleanup Messy Code',
					'styleselect'		=> 'Select Style');
	}
}
add_action('admin_init', array('sk_add_editor_buttons', 'admin_init'));

function remove_menu_items() {
  global $menu;
     unset($menu[15]); // Removes 'Links'.
     unset($menu[25]); // Removes 'Comments'.
}
add_action('admin_menu', 'remove_menu_items');

?>