<?php
/*
Plugin Name: ToTop Link
Version: 1.5.3
Plugin URI: http://www.daobydesign.com/free-plugins/totop-link-for-wordpress
Author: Dao By Design
Author URI: http://www.daobydesign.com
Description: A simple plugin for WordPress that adds an unobtrusive "back to top" link to your site or blog. The link uses WordPress' included jQuery to provide a slick UX, with the link subtly appearing after the page has been scrolled, and disappearing once the user returns to the top of the page. Additionally, a smooth scrolling animation is added when the link is clicked on.
License: GPL2
*/ 

register_activation_hook(__FILE__, 'totop_activation_hook');

function totop_activation_hook() {
	return totop_restore_config(False);
}

// restore built-in defaults, optionally overwriting existing values
function totop_restore_config($force=False) {
	
	// Enabled or Not
	if ($force or !is_string(get_option('totop_enabled')))
		update_option('totop_enabled', 'enabled');

	// Button Position
	if ($force or !is_string(get_option('totop_position')))
		update_option('totop_position', 'br');

	// Button Style
	if ($force or !is_string(get_option('totop_style')))
		update_option('totop_style', 'dark');
}

add_action('admin_menu', 'totop_admin_menu');
function totop_admin_menu() {
	add_submenu_page('options-general.php', 'ToTop Link Options', 'ToTop Link', 8, 'totop', 'totop_menu');
}

function totop_menu() {
	if($_REQUEST['clear']) {
		totop_restore_config(True);
		echo '<div id="message" class="error fade"><p>The settings have been reset to their defaults.</p></div>';
	} elseif ($_REQUEST['save']) {
		// update enabled
		update_option('totop_enabled', mysql_escape_string($_REQUEST['totop_enabled']));		
	
		// update button position
		update_option('totop_position', mysql_escape_string($_REQUEST['totop_position']));
		update_option('totop_position_c', $_REQUEST['totop_position_c']);
		// update link styles
		update_option('totop_style', mysql_escape_string($_REQUEST['totop_style']));
		update_option('totop_style_c', mysql_escape_string($_REQUEST['totop_style_c']));
		update_option('totop_style_w', mysql_escape_string($_REQUEST['totop_style_w']));
		update_option('totop_style_h', mysql_escape_string($_REQUEST['totop_style_h']));
		
		update_option('totop_scroll_offset', mysql_escape_string($_REQUEST['totop_scroll_offset']));
		update_option('totop_scroll_speed', mysql_escape_string($_REQUEST['totop_scroll_speed']));
		
		// update text link styles
		update_option('totop_link_text', mysql_escape_string($_REQUEST['totop_link_text']));
		update_option('totop_link_style1', mysql_escape_string($_REQUEST['totop_link_style1']));
		update_option('totop_link_style2', mysql_escape_string($_REQUEST['totop_link_style2']));
	
		echo '<div id="message" class="updated fade"><p>The changes have been saved.</p></div>';
	}

	// Load the options for display in the form.
	$totop_enabled = get_option('totop_enabled');
	$totop_position = get_option('totop_position');
	$totop_position_c = get_option('totop_position_c');
	$totop_style = get_option('totop_style');
	$totop_style_c = get_option('totop_style_c');
	$totop_style_w = get_option('totop_style_w');
	$totop_style_h = get_option('totop_style_h');
	$totop_scroll_offset = get_option('totop_scroll_offset');
	$totop_scroll_speed = get_option('totop_scroll_speed');
	$totop_link_text = get_option('totop_link_text');
	$totop_link_style1 = get_option('totop_link_style1');
	$totop_link_style2 = get_option('totop_link_style2');
	?>
	<style>
		.form-table {border:1px solid #ddd;}
		.form-table td, .form-table th {padding:15px 10px;}
		.form-table th {font-weight:bold;background:#eee;border-right:1px solid #ccc;border-bottom:1px solid #ddd;}
		.form-table td {background:#f3f3f3;border-bottom:1px solid #ddd;}
		.form-table td .description {padding:5px;border:1px solid #eee;background:#f6f6f6;color:#999;}
		.dbd-credit th {font-weight:bold;background:#ffffdd;text-align:right;vertical-align:middle;padding:5px 10px;}
		.dbd-credit a:link, .dbd-credit a:visited {color:#22AFC5;text-decoration:none;}
		.dbd-credit a:hover, .dbd-credit a:active {color:#ff0000;}
		a.buyusacoffee img {vertical-align:middle;}
		#message {clear:both;}
	</style>
    	
	<div class="wrap" id="totop_options">
	    <div class="icon32" id="icon-options-general"><br></div>
		<h2>ToTop Link Settings</h2>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="totop_options_form" name="totop_options_form">
                <table class="form-table">
                	<tbody>
                        <tr valign="middle" class="dbd-credit">
        					<th colspan="2">Like this plugin? <a href="http://www.twitter.com/daobydesign" target="_blank">Follow us on Twitter</a>, <a href="http://www.facebook.com/daobydesign" target="_blank">Like is on Facebook</a> or just <a href="http://www.daobydesign.com/buy-us-a-coffee/" class="buyusacoffee buyusacoffee-top" target="_blank" title="... because we'd sure appreciate it!">buy us a coffee! <img src="<?php echo plugin_dir_url(__FILE__); ?>/images/coffee_mug.png" /></a></th>
                        </tr>

                    	<tr valign="top">
                    		<th>Enable / Disable Button</th>
                           	<td>
                            	<input type="radio" name="totop_enabled" id="totop_enabled" value="enabled" <?php if($totop_enabled=='enabled'){ echo "CHECKED"; } ?>><label for="totop_enabled"> Enabled</label>
                            	<br />
                                <input type="radio" name="totop_enabled" id="totop_disabled" value="disabled" <?php if($totop_enabled=='disabled'){ echo "CHECKED"; } ?>><label for="totop_disabled"> Disabled</label>
                            </td>
                        </tr>
                        
                    	<tr valign="top">
                        	<th>ToTop Link Position</th>
                    		<td>
                    			<select type="select" name="totop_position" id="totop_position">
                                    <option value="bl" id="totop_bl" <?php if($totop_position=='bl'){ echo 'selected="selected"'; } ?>>Bottom Left</option>
                                    <option value="br" id="totop_br" <?php if($totop_position=='br'){ echo 'selected="selected"'; } ?>>Bottom Right</option>
                                    <option value="bm" id="totop_bm" <?php if($totop_position=='bm'){ echo 'selected="selected"'; } ?>>Bottom Middle</option>
                                    <option value="tl" id="totop_tl" <?php if($totop_position=='tl'){ echo 'selected="selected"'; } ?>>Top Left</option>
                                    <option value="tr" id="totop_tr" <?php if($totop_position=='tr'){ echo 'selected="selected"'; } ?>>Top Right</option>
                                    <option value="tm" id="totop_tm" <?php if($totop_position=='tm'){ echo 'selected="selected"'; } ?>>Top Middle</option>
                                    <option value="ml" id="totop_ml" <?php if($totop_position=='ml'){ echo 'selected="selected"'; } ?>>Middle Left</option>
                                    <option value="mr" id="totop_mr" <?php if($totop_position=='mr'){ echo 'selected="selected"'; } ?>>Middle Right</option>
                                    <option value="custom" id="totop_custom" <?php if($totop_position=='custom'){ echo 'selected="selected"'; } ?>>Custom</option>
                                </select>
                                <p class="description">With this setting you can choose where you want the ToTop link to be displayed.</p>
                                <p>&nbsp;</p>
                                <p><strong>Custom Offset Position:</strong>&nbsp;&nbsp;&nbsp;&nbsp;
                                	<label for="totop_position_c[ct]">Top:</label> <input type="text" name="totop_position_c[top]" value="<?php echo $totop_position_c['top']; ?>" id="totop_position_ct" size="2">
                                    <label for="totop_position_c[cl]">Left:</label> <input type="text" name="totop_position_c[left]" value="<?php echo $totop_position_c['left']; ?>" id="totop_position_cl" size="2">
                                    <label for="totop_position_c[cb]">Bottom:</label> <input type="text" name="totop_position_c[bottom]" value="<?php echo $totop_position_c['bottom']; ?>" id="totop_position_cb" size="2">
                                    <label for="totop_position_c[cr]">Right:</label> <input type="text" name="totop_position_c[right]" value="<?php echo $totop_position_c['right']; ?>" id="totop_position_cr" size="2">
                                </p>
                                <p class="description">Use the above fields to set the custom offsets for the ToTop image/link. Be sure to only set one horizontal and one vertical position (ie. Top and Left, not Top and Bottom). <em>Leave unused positions blank</em>.</p>
                            </td>
                        </tr>    
                        
                        <tr valign="top">
		                    <th>ToTop Style</th>
							<td>
                            	<input type="radio" name="totop_style" value="light" id="totop_light" <?php if($totop_style=='light'){ echo "CHECKED"; } ?>><label for="totop_light"> Light</label>
                                <br />
                                <input type="radio" name="totop_style" value="dark" id="totop_dark" <?php if($totop_style=='dark'){ echo "CHECKED"; } ?>><label for="totop_dark"> Dark</label>
                                <br />
                                <input type="radio" name="totop_style" value="text" id="totop_text" <?php if($totop_style=='text'){ echo "CHECKED"; } ?>><label for="totop_text"> Text Link</label>
                                <br />
                                <input type="radio" name="totop_style" value="custom" id="totop_custom" <?php if($totop_style=='custom'){ echo "CHECKED"; } ?>><label for="totop_custom"> Custom Image</label>
			                    <p class="description">Choose "Light" if your Web site's background colour is a dark colour. Choose "Dark" if your background is a light colour. If you prefer to use your own image, select "Custom", and include the URL to the image in the field below.</p>
                            </td>
                        </tr>

                        <tr valign="top">
		                    <th>ToTop Scroll Offset</th>
							<td>
                            	<input type="text" name="totop_scroll_offset" value="<?php echo $totop_scroll_offset; ?>" id="totop_scroll_offset" size="3"><label for="totop_scroll_offset">px</label> 
			                    <p class="description">This setting allows you to set how far down the page a user must scroll before the ToTop link appears. <strong>Do not include "px"</strong></p>
                            </td>
                        </tr>

                        <tr valign="top">
		                    <th>ToTop Scroll Speed</th>
							<td>
                            	<input type="text" name="totop_scroll_speed" value="<?php echo $totop_scroll_speed; ?>" id="totop_scroll_speed" size="4"><label for="totop_scroll_speed">ms</label> 
			                    <p class="description">This setting allows you to set how fast the page will scroll back to the top once the ToTop Link is clicked. <strong>Include an integer in milliseconds. If left blank, the default '800' will be used.</strong></p>
                            </td>
                        </tr>

                        <tr valign="top">
		                    <th>Custom Image</th>
							<td>
                            	<label for="totop_style_c">Custom Image URL:</label><br /><input type="text" name="totop_style_c" value="<?php echo $totop_style_c; ?>" id="totop_style_c" style="width:60%;">
                                <br />
                                <label for="totop_style_w">Custom Image Width:</label> <input type="text" name="totop_style_w" value="<?php echo $totop_style_w; ?>" id="totop_style_w" size="3" >
                                <label for="totop_style_h">Custom Image Height:</label> <input type="text" name="totop_style_h" value="<?php echo $totop_style_h; ?>" id="totop_style_h" size="3" >
			                    <p class="description">The height and width should auto-populate with the size of the image supplied in the "Custom Image URL" field above. If not, or if you'd like to customize the height and width, please add the values above.</p>
                            </td>
                        </tr>


						<tr valign="top">
                    		<th>ToTop Text Link Config.<br /><small>(Optional)</small></th>
                        	<td>
                            	<input type="text" name="totop_link_text" value="<?php echo $totop_link_text; ?>" id="totop_link_text"><label for="totop_link_text"> Text for Link</label>
                                <p class="description">You can customize the link text displayed. Leave blank for default: <strong><?php _e('Return to Top ▲'); ?></strong></p>
                            	<input type="text" name="totop_link_style1" value="<?php echo $totop_link_style1; ?>" id="totop_link_style1"><label for="totop_link_style1"> Regular Link Colour</label>
                        		<br />
                                <input type="text" name="totop_link_style2" value="<?php echo $totop_link_style2; ?>" id="totop_link_style2"><label for="totop_link_style2"> Hover Link Colour</label>
                    			<p class="description">Insert the HEX value with hash symbol (e.g. #336600) for the regular and hover link colours. Leave blank if you wish to use your theme's defaults.</p>
							</td>
                        </tr>

                        <tr valign="middle" class="dbd-credit">
        					<th colspan="2">Like this plugin? <a href="http://www.twitter.com/daobydesign" target="_blank">Follow us on Twitter</a>, <a href="http://www.facebook.com/daobydesign" target="_blank">Like is on Facebook</a> or just <a href="http://www.daobydesign.com/buy-us-a-coffee/" class="buyusacoffee buyusacoffee-top" target="_blank" title="... because we'd sure appreciate it!">buy us a coffee! <img src="<?php echo plugin_dir_url(__FILE__); ?>/images/coffee_mug.png" /></a></th>
                        </tr>

                	</tbody>
          		</table>
                <p class="submit">
                    <input name="save" id="save" class="button button-primary" value="Save Changes" type="submit" />
                    <input name="clear" id="reset" class="button" value="Reset Options" type="submit" />
	                
                </p>
        </form>
	</div>
    <script type="text/javascript" language="javascript">
		jQuery('#totop_style_c').change(function() {
			var $cimg = new Image();
			$cimg.onload = function() {
				if (!jQuery('#totop_style_w').val()) { jQuery('#totop_style_w').val(this.width); }
				if (!jQuery('#totop_style_h').val()) { jQuery('#totop_style_h').val(this.height); }
			}
			$cimg.src = jQuery('#totop_style_c').val();
		});
	</script>
	
<?php
}

// Hook the_content to output html if we should display on any page
$totop_enabled = get_option('totop_enabled');
if ($totop_enabled == 'enabled' && !is_admin()) {
	$totop_position = get_option('totop_position');
	if ($totop_position == 'custom') {
		$totop_css_vars['pos'] = get_option('totop_position_c');
	}

	$totop_style = get_option('totop_style');
	if ($totop_style != 'text') { 
		if ($totop_style == 'custom') {
			$totop_img_src = get_option('totop_style_c');
			$totop_css_vars['width'] = get_option('totop_style_w');
			$totop_css_vars['height'] = get_option('totop_style_h');		
		} else {
			$totop_img_src = plugin_dir_url(__FILE__).'images/totop-'.$totop_style.'.png';
			$totop_css_vars['width'] = '40';	
			$totop_css_vars['height'] = '48';	
		}
	} else {
		$totop_css_vars['text-style'][0] = get_option('totop_link_style1');
		$totop_css_vars['text-style'][1] = get_option('totop_link_style2');
	}
	
	add_action('wp_footer', 'totop_body_hook');
	add_action('init','totop_init_hook');
	function totop_body_hook() {
		global $totop_style; global $totop_position; global $totop_img_src; global $totop_css_vars;
		$totop_class = 'totop-'.$totop_position.' totop-'.$totop_style;

		$totop_link_text = get_option('totop_link_text');
		$totop_scroll_offset = get_option('totop_scroll_offset');

		$totop_link_text = ($totop_link_text) ? $totop_link_text : __('Return to Top ▲');
		$totop_img = ($totop_img_src) ? '<img src="'.$totop_img_src.'" alt="'.$totop_link_text.'" title="'.$totop_link_text.'" width="'.$totop_css_vars['width'].'" height="'.$totop_css_vars['height'].'" />' : '';
		echo '<a id="toTop" title="'.$totop_link_text.'" class="'.$totop_class.'" rel="'.$totop_scroll_offset.'">'.$totop_img.'<span>'.$totop_link_text.'</span></a>';
	}
	
	function totop_init_hook() {
		global $totop_css_vars; $totop_scroll_speed = get_option('totop_scroll_speed');
		$totop_css = '?vars='.base64_encode(serialize($totop_css_vars));
		wp_enqueue_style('totop',  plugin_dir_url(__FILE__).'totop-link.css.php?'.$totop_css);
		wp_enqueue_script('jquery');
		wp_enqueue_script('totop', plugin_dir_url(__FILE__).'totop-link.js.php?speed='.$totop_scroll_speed,'jquery', '', false);
	}
	
}


?>