<?php
/*
	Options page
*/

// ------------------------------------------------------------------------
// PLUGIN PREFIX:                                                          
// ------------------------------------------------------------------------
// A PREFIX IS USED TO AVOID CONFLICTS WITH EXISTING PLUGIN FUNCTION NAMES.
// WHEN CREATING A NEW PLUGIN, CHANGE THE PREFIX AND USE YOUR TEXT EDITORS 
// SEARCH/REPLACE FUNCTION TO RENAME THEM ALL QUICKLY.
// ------------------------------------------------------------------------

// 'wpar_' prefix is derived from [wp]wordpress [author]ptions [r]reviews

// ------------------------------------------------------------------------
// REGISTER HOOKS & CALLBACK FUNCTIONS:
// ------------------------------------------------------------------------
// HOOKS TO SETUP DEFAULT PLUGIN OPTIONS, HANDLE CLEAN-UP OF OPTIONS WHEN
// PLUGIN IS DEACTIVATED AND DELETED, INITIALISE PLUGIN, ADD OPTIONS PAGE.
// ------------------------------------------------------------------------

// Set-up Action and Filter Hooks
//register_activation_hook(__FILE__, 'wpar_add_defaults');
//register_uninstall_hook(__FILE__, 'wpar_delete_plugin_options');
add_action('admin_init', 'wpar_init' );
add_action('admin_menu', 'wpar_add_options_page');
add_filter( 'plugin_action_links', 'wpar_plugin_action_links', 10, 2 );

// --------------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_uninstall_hook(__FILE__, 'wpar_delete_plugin_options')
// --------------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE USER DEACTIVATES AND DELETES THE PLUGIN. IT SIMPLY DELETES
// THE PLUGIN OPTIONS DB ENTRY (WHICH IS AN ARRAY STORING ALL THE PLUGIN OPTIONS).
// --------------------------------------------------------------------------------------

// Delete options table entries ONLY when plugin deactivated AND deleted
function wpar_delete_plugin_options() {
	delete_option('wpar_options');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: register_activation_hook(__FILE__, 'wpar_add_defaults')
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE PLUGIN IS ACTIVATED. IF THERE ARE NO THEME OPTIONS
// CURRENTLY SET, OR THE USER HAS SELECTED THE CHECKBOX TO RESET OPTIONS TO THEIR
// DEFAULTS THEN THE OPTIONS ARE SET/RESET.
//
// OTHERWISE, THE PLUGIN OPTIONS REMAIN UNCHANGED.
// ------------------------------------------------------------------------------

// Define default option settings
function wpar_add_defaults() {
	$tmp = get_option('wpar_options');
    if(($tmp['wpar_chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('wpar_options'); // so we don't have to reset all the 'off' checkboxes too! ( I don't think this is needed but leave for now)
		$arr = array(	"wpar_chk_rating_home_display" => "1",
						"wpar_chk_rating_mini_post_display" => "1",
						"wpar_chk_rating_after_post_display" => "1",
						"wpar_box_width" => "300",
						"wpar_drp_box_align" => "right",
						"wpar_drp_button_color" => "orange",
						"wpar_chk_default_options_db" => ""
		);
		update_option('wpar_options', $arr);
	}
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_init', 'wpar_init' )
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_init' HOOK FIRES, AND REGISTERS YOUR PLUGIN
// SETTING WITH THE WORDPRESS SETTINGS API. YOU WON'T BE ABLE TO USE THE SETTINGS
// API UNTIL YOU DO.
// ------------------------------------------------------------------------------

// Init plugin options to white list our options
function wpar_init(){
	register_setting( 'wpar_plugin_options', 'wpar_options', 'wpar_validate_options' );
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION FOR: add_action('admin_menu', 'wpar_add_options_page');
// ------------------------------------------------------------------------------
// THIS FUNCTION RUNS WHEN THE 'admin_menu' HOOK FIRES, AND ADDS A NEW OPTIONS
// PAGE FOR YOUR PLUGIN TO THE SETTINGS MENU.
// ------------------------------------------------------------------------------

// Add menu page
function wpar_add_options_page() {
	add_options_page('Reviews Settings', 'Reviews', 'manage_options', __FILE__, 'wpar_render_form');
}

// ------------------------------------------------------------------------------
// CALLBACK FUNCTION SPECIFIED IN: add_options_page()
// ------------------------------------------------------------------------------
// THIS FUNCTION IS SPECIFIED IN add_options_page() AS THE CALLBACK FUNCTION THAT
// ACTUALLY RENDER THE PLUGIN OPTIONS FORM AS A SUB-MENU UNDER THE EXISTING
// SETTINGS ADMIN MENU.
// ------------------------------------------------------------------------------

// Render the Plugin options form
function wpar_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Author hReview Settings</h2>
		<p>Get more control over reviews.</p>

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('wpar_plugin_options'); ?>
			<?php $options = get_option('wpar_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">

				<!-- Checkbox Buttons One -->
				<tr valign="top">
					<th scope="row">Display settings</th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_home_display]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_home_display'])) { checked('1', $options['wpar_chk_rating_home_display']); } ?> /> Display rating stars on home page?</label><br />


					</td>
				</tr>
                	<!-- Checkbox Buttons two -->
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_after_post_display]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_after_post_display'])) { checked('1', $options['wpar_chk_rating_after_post_display']); } ?> /> Display rating below single post?</label><br />


					</td>
				</tr>
                </tr>
                   	<!-- Checkbox Buttons three -->
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<!-- First checkbox button -->
						<label><input name="wpar_options[wpar_chk_rating_mini_post_display]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_rating_mini_post_display'])) { checked('1', $options['wpar_chk_rating_mini_post_display']); } ?> /> Switch to mini rating box?</label><br />


					</td>
				</tr>
                
                 <!-- Textbox Control -->
				<tr>
					<th scope="row">Review box width (default: 300px)</th>
					<td>
						<input type="text" size="57" name="wpar_options[wpar_box_width]" value="<?php echo $options['wpar_box_width']; ?>" />
					</td>
				</tr>
           
           			<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Review box alignment (default: right)</th>
					<td>
						<select name='wpar_options[wpar_drp_box_align]'>
							<option value='right' <?php selected('right', $options['wpar_drp_box_align']); ?>>right</option>
							<option value='left' <?php selected('left', $options['wpar_drp_box_align']); ?>>left</option>
							<option value='none' <?php selected('none', $options['wpar_drp_box_align']); ?>>none</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select alignment type.</span>
					</td>
				</tr>
                
				<!-- Select Drop-Down Control -->
				<tr>
					<th scope="row">Button color</th>
					<td>
						<select name='wpar_options[wpar_drp_button_color]'>
							<option value='blue' <?php selected('blue', $options['wpar_drp_button_color']); ?>>blue</option>
							<option value='orange' <?php selected('orange', $options['wpar_drp_button_color']); ?>>orange</option>
							<option value='green' <?php selected('green', $options['wpar_drp_button_color']); ?>>green</option>
							<option value='red' <?php selected('red', $options['wpar_drp_button_color']); ?>>red</option>
							<option value='yellow' <?php selected('yellow', $options['wpar_drp_button_color']); ?>>yellow</option>
							<option value='white' <?php selected('white', $options['wpar_drp_button_color']); ?>>White</option>
							<option value='purple' <?php selected('purple', $options['wpar_drp_button_color']); ?>>Purple</option>
							<option value='gray' <?php selected('gray', $options['wpar_drp_button_color']); ?>>Gray</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Select button color.</span>
					</td>
				</tr>

				<!--
                <tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="wpar_options[wpar_chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['wpar_chk_default_options_db'])) { checked('1', $options['wpar_chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
                -->
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

		<p style="margin-top:15px;">
			
		<div style="float:right">
			<a href="https://twitter.com/FamousBloggers"
            class="twitter-follow-button"
            data-show-count="true"
            data-lang="en"
            data-size="large">Follow @FamousBloggers</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>            
            
            <p style="font-style: italic;font-weight: bold;color: #26779a;">
            	If you have found this plugin at all useful, please consider making a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=44ALNG28WPN76" target="_blank" style="color:#72a1c6;">donation</a>. Thanks.
			</p>

		<div style="clear:both;"></div>
        </p>
        
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wpar_validate_options($input) {
	 // strip html from textboxes
	$input['wpar_box_width'] =  wp_filter_nohtml_kses($input['wpar_box_width']); // Sanitize textarea input (strip html tags, and escape characters)
	//$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
function wpar_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$posk_links = '<a href="'.get_admin_url().'options-general.php?page=author-hreview.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $posk_links );
	}

	return $links;
}
?>