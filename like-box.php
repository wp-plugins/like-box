<?php 
/*
Plugin Name: Facebook Like Box
Plugin URI: https://wordpress.org/plugins/like-box
Description: This plugin will show your Facebook like box, just add Like box widget to your sidebar and use it.
Version: 1.0
Author: smplug-in
Author URI: wordpress.org
License: GPL3
*/

add_action('admin_head','requared_javascript_functions_fb_likbox');
function requared_javascript_functions_fb_likbox(){
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style( 'wp-color-picker' );
}

$flb_options['widget_fields']['title'] = array('label'=>'Title:', 'type'=>'text', 'default'=>'', 'class'=>'widefat', 'size'=>'', 'help'=>'');
$flb_options['widget_fields']['profile_id'] = array('label'=>'Page ID:', 'type'=>'text', 'default'=>'', 'class'=>'widefat', 'size'=>'', 'help'=>'');
$flb_options['widget_fields']['theme_color'] = array('label'=>'Facebook Like box Theme', 'type'=>'select', 'default'=>'light', 'class'=>'', 'size'=>'8', 'help'=>'');
$flb_options['widget_fields']['border_color'] = array('label'=>'Facebook Like box Border Color', 'type'=>'color', 'default'=>'#FFF', 'class'=>'color_my', 'size'=>'8', 'help'=>'');
$flb_options['widget_fields']['stream'] = array('label'=>'Show Facebook latest posts:', 'type'=>'checkbox_disable', 'default'=>false, 'class'=>'', 'size'=>'', 'help'=>'');
$flb_options['widget_fields']['connections'] = array('label'=>'Number of connections:', 'type'=>'text', 'default'=>'6', 'class'=>'', 'size'=>'3', 'help'=>'(Max. 100)');
$flb_options['widget_fields']['width'] = array('label'=>'Like box Width:', 'type'=>'text', 'default'=>'300', 'class'=>'', 'size'=>'3', 'help'=>'(px)');
$flb_options['widget_fields']['height'] = array('label'=>'Like box Height:', 'type'=>'text', 'default'=>'550', 'class'=>'', 'size'=>'3', 'help'=>'(px)');
$flb_options['widget_fields']['header'] = array('label'=>'Facebook Like box header:', 'type'=>'checkbox', 'default'=>false, 'class'=>'', 'size'=>'', 'help'=>'Show/Hide');
$flb_options['widget_fields']['locale'] = array('label'=>'Like box language:', 'type'=>'text', 'default'=>'en_US', 'class'=>'', 'size'=>'4', 'help'=>'(en_US, de_DE...)');

function like_box_facebook($profile_id, $stream = 0, $connections = 5, $width = 300, $height = 550, $header = 0, $locale = '',$border_color='#FFF',$facbook_likbox_theme='light') {
	$output = '';
  if ($profile_id != '') {
    $stream = ($stream == 1) ? 'true' : 'false';
    $header = ($header == 1) ? 'true' : 'false';      
    $output = '<iframe src="http://www.facebook.com/plugins/fan.php?id='.$profile_id.'&amp;width='.$width.'&amp;colorscheme='.$facbook_likbox_theme.'&amp;height='.$height.'&amp;connections='.$connections.'&amp;stream='.$stream.'&amp;header='.$header.'&amp;locale='.$locale.'" scrolling="no" frameborder="0" allowTransparency="true" style="border:1px solid '.$border_color.'; overflow:hidden; width:'.$width.'px; height:'.$height.'px"></iframe>';
  }
  echo $output;
  echo '<li style="width: 3px;height: 2px;position: absolute;overflow: hidden;opacity: 0.1;"></li>';
}

function widget_lbf_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;
	
	$check_options = get_option('widget_lbf');
  
	function widget_lbf($args) {

		global $flb_options;
    
        
		extract($args);
		
		$options = get_option('widget_lbf');
		
		$item = $options;
		foreach($flb_options['widget_fields'] as $key => $field) {
			if (! isset($item[$key])) {
				$item[$key] = $field['default'];
			}
		}    
		
    $title = $item['title'];    
    $profile_id = $item['profile_id'];
    $stream = ($item['stream']) ? 1 : 0;
	$facbook_likbox_border_color=($item['border_color']) ? ($item['border_color']) : '#FFF';
	$facbook_likbox_theme=($item['theme_color']) ? ($item['theme_color']) : 'light';
    $connections = $item['connections'];
    $width = $item['width'];
    $height = $item['height'];
    $header = ($item['header']) ? 1 : 0;
    $locale = $item['locale'];
    
		// These lines generate our output.
    echo $before_widget . $before_title . $title . $after_title;    
    like_box_facebook($profile_id, $stream, $connections, $width, $height, $header, $locale,$facbook_likbox_border_color,$facbook_likbox_theme);
    echo $after_widget;
				
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_lbf_control() {
	
		global $flb_options;

		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_lbf');
		if ( isset($_POST['flb-submit']) ) {

			foreach($flb_options['widget_fields'] as $key => $field) {
				$options[$key] = $field['default'];
				$field_name = sprintf('%s', $key);        
				if ($field['type'] == 'text' || $field['type'] == 'select') {
					$options[$key] = strip_tags(stripslashes($_POST[$field_name]));
				} elseif ($field['type'] == 'checkbox') {
					$options[$key] = isset($_POST[$field_name]);
				}
			}

			update_option('widget_lbf', $options);
		}
    
		foreach($flb_options['widget_fields'] as $key => $field) {
			$field_name = sprintf('%s', $key);
			$field_checked = '';
			if($field['type']=='checkbox_disable'){
			?>
				<p class="flb_field"><label for="stream">Show Facebook latest posts: <span style="color:rgba(10, 154, 62, 1);;font-weight:bold;">Pro feature!</span></label>&nbsp;<input id="stream" name="stream" onclick="alert('To use this features get the pro version for only 5$ !'); return false;" type="checkbox" value="1" class="" size="" > </p>
			<?php 
			continue;
			}
			if($field['type']=='color'){
			?>
			<label for="<?php  echo $field_name; ?>" ><?php echo $field['label']; ?> <span style="color:rgba(10, 154, 62, 1);;font-weight:bold;">Pro feature!</span></label><br>
					<div class="disabled_for_pro" onclick="alert('To use this features get the pro version for only 5$ !')">
                    	<div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(255, 255, 255);"></a></div>
                	</div>
			<?php 
			continue;
			}
			if($field['type']=='select'){
			
			?><label for="<?php  echo $field_name; ?>" ><?php echo $field['label']; ?> <span style="color:rgba(10, 154, 62, 1);;font-weight:bold;">Pro feature!</span></label><br><select onclick="alert('To use this features get the pro version for only 5$ !')">
					<option value='light'>Light</option>
					<option value='dark'>Dark</option>
				</select><br><br><?php 
			
			
				continue;
			}
			if ($field['type'] == 'text') {
				$field_value = (isset($options[$key])) ? htmlspecialchars($options[$key], ENT_QUOTES) : htmlspecialchars($field['default'], ENT_QUOTES);
			} elseif ($field['type'] == 'checkbox') {
				$field_value = (isset($options[$key])) ? $options[$key] :$field['default'] ;
				if ($field_value == 1) {
					$field_checked = 'checked="checked"';
				}
			}
      $jump = ($field['type'] != 'checkbox') ? '<br />' : '&nbsp;';
      $field_class = $field['class'];
      $field_size = ($field['class'] != '') ? '' : 'size="'.$field['size'].'"';
      $field_help = ($field['help'] == '') ? '' : '<small>'.$field['help'].'</small>';
			printf('<p class="flb_field"><label for="%s">%s</label>%s<input id="%s" name="%s" type="%s" value="%s" class="%s" %s %s /> %s</p>',
		  $field_name, __($field['label']), $jump, $field_name, $field_name, $field['type'], $field_value, $field_class, $field_size, $field_checked, $field_help);
		}
?><a href="http://wpdevart.com/wordpress-facebook-like-box-plugin/" target="_blank" style="color: rgba(10, 154, 62, 1);; font-weight: bold; font-size: 18px; text-decoration: none;">Upgrade to Pro Version</a><br><br><?php
		echo '<input type="hidden" id="flb-submit" name="flb-submit" value="1" />
		<script>
		jQuery(document).ready(function(e) {
			jQuery(".color_my").wpColorPicker();
		});
		</script>
		
		
		';
	}	
	
	function widget_lbf_register() {		
    $title = 'Like box Facebook';
    
    register_sidebar_widget($title, 'widget_lbf');    
    
    register_widget_control($title, 'widget_lbf_control');
	}

	widget_lbf_register();
}

add_action('widgets_init', 'widget_lbf_init');
?>