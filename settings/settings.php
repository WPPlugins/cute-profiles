<?php // Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'cute_profiles_settings');
  add_action( 'admin_init', 'register_cute_profiles_settings' ); 
} 
function cute_profiles_settings() {
    // Add a new submenu under Options:
    //add_options_page('Cute Profiles', 'Cute Profiles', 9, 'cute-profiles', 'cute_profiles_settings_page');
	add_menu_page( 'Cute Profiles', 'Cute Profiles', 'manage_options','cute-profiles', 'cute_profiles_settings_page' );
	add_submenu_page('cute-profiles', 'Cute Profiles', 'Social Profiles', 'manage_options', 'cute-profiles', 'cute_profiles_settings_page');
	add_submenu_page('cute-profiles', 'Cute Profiles Sharing', 'Social Sharing', 'manage_options', 'cute-profiles-sharing', 'cute_profiles_sharing_page');
}
//admin settings
function cute_profiles_admin_scripts() {
global $cute_profiles_options,$cute_profiles;
$nonce= wp_create_nonce('cute-profiles');
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page'])) {
		wp_enqueue_script( 'formtips', cute_profiles_plugin_url( 'js/formtips.js' ),array('jquery'), CUTE_PROFILES_VER, false);
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-form' );
		/*wp_enqueue_script( 'farbtastic' );
		wp_print_styles( 'farbtastic' ); */
		wp_enqueue_style( 'wp-color-picker' );
   		wp_enqueue_script( 'wp-color-picker' );	
		wp_enqueue_style( 'cute_profiles_admin_head_css', cute_profiles_plugin_url( 'css/admin.css' ),
			false, CUTE_PROFILES_VER, 'all');
	}
  }
}
add_action( 'admin_init', 'cute_profiles_admin_scripts' );
//add_action( 'admin_init', 'cute_profiles_enqueue_styles' );
if (( is_admin() ) && ( isset($_GET['page']))){
add_action( 'admin_enqueue_scripts', 'cute_profiles_css' );
add_action( 'admin_enqueue_scripts', 'cute_sharing_css' );
add_action( 'admin_enqueue_scripts', 'cute_profiles_enqueue_styles' );
add_action( 'admin_enqueue_scripts', 'cute_sharing_enqueue_styles' );
}
function cute_profiles_admin_icon()
{
	echo '
		<style> 
			#adminmenu #toplevel_page_cute-profiles div.wp-menu-image:before { content: "\f237"; }
		</style>
	';	
}
add_action( 'admin_head', 'cute_profiles_admin_icon' );
//add_action('wp', 'cute_profiles_css');

function cute_profiles_admin_head() {
global $cute_profiles;
$cute_profiles_curr = get_option('cute_profiles_options');
$cute_profiles_curr['profiles']=array_values($cute_profiles_curr['profiles']);
$cute_profiles_curr['sharings']=array_values($cute_profiles_curr['sharings']);
update_option('cute_profiles_options',$cute_profiles_curr);
//print_r ($cute_profiles_curr);
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( !empty($_GET['page'])){
	if ( isset($_GET['page']) && ('cute-profiles' == $_GET['page'] ) or ('cute-profiles-sharing' == $_GET['page'] )) { ?>
		<script type="text/javascript">
			function cute_profiles_delete_js(del_id){	
					pid_arr = del_id.split('-');
					var pid=pid_arr[0];
					jQuery('#'+pid+'_flag').val('0');
					jQuery('#'+pid+'_section').fadeOut();
			}
			var cute_profiles_array=<?php echo json_encode($cute_profiles_curr['profiles']); ?>;
			var cute_sharing_array=<?php echo json_encode($cute_profiles_curr['sharings']); ?>;
			var cute_profiles_count=0;
			function cute_profiles_add_js(add_id){
				if(cute_profiles_count==0){cute_profiles_count=add_id}var count=cute_profiles_count;cute_profiles_count++;var j;
				var sec_id='cp_sec_'+count;
				var paddhtml='<tr valign="top" id="'+sec_id+'" style="width:70%;"><td><select id="cute_profiles_others" name="cute_profiles_dropdown" onchange="ShowData(this.options[this.selectedIndex],\''+sec_id+'\')"><option value="Other" selected>Other</option>';
				for (j = 0;j < cute_profiles_array.length; j++){
					paddhtml+='<option value="'+cute_profiles_array[j].p_name+'" c-index="cute_profiles_options[profiles]['+j+']" p-name="'+cute_profiles_array[j].p_name+'" icon-url="'+cute_profiles_array[j].icon_url+'" p-url="'+cute_profiles_array[j].p_url+'" p-num="'+cute_profiles_array[j].p_num+'">'+cute_profiles_array[j].p_name+'</option>';
				}
				paddhtml+='</select></td><td style="width:177px;"><input required class="p-name-input" placeholder="Platform Name" type="text" size="20" name="cute_profiles_options[profiles]['+count+'][p_name]" value="" title="Platform Name" autocomplete="off" /></td> &nbsp; <td style="width:177px;"><input class="icon-url-input" placeholder="Icon Image URL" type="text" size="20" name="cute_profiles_options[profiles]['+count+'][icon_url]"  value="" title="Icon Image URL" autocomplete="off" /></td>&nbsp;<td style="width:177px;"><input required class="p-url-input" placeholder="Profile URL"  type="text" size="20" name="cute_profiles_options[profiles]['+count+'][p_url]" value="" title="Complete Profile URL" autocomplete="off" /><input type="hidden" class="p-flag-input" id="cute_profiles" name="cute_profiles_options[profiles]['+count+'][p_flag]" value="1" /><input type="hidden" class="p-num-input" id="cute_profiles" name="cute_profiles_options[profiles]['+count+'][p_num]" value="'+count+'" /></td></tr>';
				jQuery(paddhtml).hide().appendTo("#cute_profiles_tbody").fadeIn();
			}
			var cute_sharing_count=0;
			function cute_sharing_add_js(add_id){
				if(cute_sharing_count==0){cute_sharing_count=add_id}var count=cute_sharing_count;cute_sharing_count++;var j;
				var sec_id_s='cp_sharing_sec_'+count;
				var paddhtml='<tr valign="top" id="'+sec_id_s+'" style="width:70%;"><td><select id="cute_profiles_others" name="cute_profiles_dropdown" onchange="ShowDataSharing(this.options[this.selectedIndex],\''+sec_id_s+'\')">';
				for (j = 0;j < cute_sharing_array.length; j++){
					paddhtml+='<option value="'+cute_sharing_array[j].p_name+'" c-index-s="cute_profiles_options[sharings]['+j+']" p-name-s="'+cute_sharing_array[j].p_name+'" icon-url-s="'+cute_sharing_array[j].icon_url+'" p-url-s="'+cute_sharing_array[j].p_url+'" p-num-s="'+cute_sharing_array[j].p_num+'">'+cute_sharing_array[j].p_name+'</option>';
				}
				paddhtml+='</select></td><td style="width:177px;"><input required class="p-name-input-s" placeholder="Platform Name" type="text" size="20" name="cute_profiles_options[sharings]['+count+'][p_name]" value="" title="Platform Name" autocomplete="off" /></td> &nbsp; <td style="width:177px;"><input type="hidden" class="icon-url-input-s" placeholder="Icon Image URL" size="20" name="cute_profiles_options[sharings]['+count+'][icon_url]"  value="" title="Icon Image URL" autocomplete="off" /></td>&nbsp;<td style="width:177px;"><input type="hidden" class="p-url-input-s" placeholder="Profile URL"  size="20" name="cute_profiles_options[sharings]['+count+'][p_url]" value="" title="Complete Profile URL" autocomplete="off" /><input type="hidden" class="p-flag-input-s" id="cute_profiles" name="cute_profiles_options[sharings]['+count+'][p_flag]" value="1" /><input type="hidden" class="p-num-input-s" id="cute_profiles" name="cute_profiles_options[sharings]['+count+'][p_num]" value="'+count+'" /></td></tr>';
				jQuery(paddhtml).hide().appendTo("#cute_profiles_tbody").fadeIn();
			}
			function ShowData(obj,sec_id) {
				jQuery("#"+sec_id+" .p-name-input").val(obj.getAttribute('p-name'));
				jQuery("#"+sec_id+" .icon-url-input").val(obj.getAttribute('icon-url'));
				jQuery("#"+sec_id+" .p-url-input").val(obj.getAttribute('p-url'));
				jQuery("#"+sec_id+" .p-num-input").val(obj.getAttribute('p-num'));
				/*var c_index_name=jQuery("#"+sec_id+" .p-name-input").val(obj.getAttribute('c-index')); */
				var c_index_name=obj.getAttribute('c-index');
				/*alert (c_index_name); */
				var c_index_name_pname=c_index_name+'[p_name]';
				var c_index_name_iurl=c_index_name+'[icon_url]';
				var c_index_name_purl=c_index_name+'[p_url]';
				var c_index_name_flag=c_index_name+'[p_flag]';
				var c_index_name_pnum=c_index_name+'[p_num]';
				jQuery("#"+sec_id+" .p-name-input").attr('name',c_index_name_pname);
				jQuery("#"+sec_id+" .icon-url-input").attr('name',c_index_name_iurl);
				jQuery("#"+sec_id+" .p-url-input").attr('name',c_index_name_purl);
				jQuery("#"+sec_id+" .p-flag-input").attr('name',c_index_name_flag);
				jQuery("#"+sec_id+" .p-num-input").attr('name',c_index_name_pnum);
			}
			function ShowDataSharing(obj,sec_id_s) {
				/*For Sharing Options */
				jQuery("#"+sec_id_s+" .p-name-input-s").val(obj.getAttribute('p-name-s'));
				jQuery("#"+sec_id_s+" .icon-url-input-s").val(obj.getAttribute('icon-url-s'));
				jQuery("#"+sec_id_s+" .p-url-input-s").val(obj.getAttribute('p-url-s'));
				jQuery("#"+sec_id_s+" .p-num-input-s").val(obj.getAttribute('p-num-s'));
				/*var c_index_name=jQuery("#"+sec_id+" .p-name-input").val(obj.getAttribute('c-index')); */
				var c_index_name=obj.getAttribute('c-index-s');
				/*alert (c_index_name); */
				var c_index_name_pname_s=c_index_name+'[p_name]';
				var c_index_name_iurl_s=c_index_name+'[icon_url]';
				var c_index_name_purl_s=c_index_name+'[p_url]';
				var c_index_name_flag_s=c_index_name+'[p_flag]';
				var c_index_name_pnum_s=c_index_name+'[p_num]';
				jQuery("#"+sec_id_s+" .p-name-input-s").attr('name',c_index_name_pname_s);
				jQuery("#"+sec_id_s+" .icon-url-input-s").attr('name',c_index_name_iurl_s);
				jQuery("#"+sec_id_s+" .p-url-input-s").attr('name',c_index_name_purl_s);
				jQuery("#"+sec_id_s+" .p-flag-input-s").attr('name',c_index_name_flag_s);
				jQuery("#"+sec_id_s+" .p-num-input-s").attr('name',c_index_name_pnum_s);
			}
		</script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#cute_profiles_table tbody").sortable({ axis: 'y', items: "tr.ui-sortable" });
			 });
		</script>
		<script type="text/javascript">
			// <![CDATA[
		jQuery(document).ready(function() { 
			//for WP-color-picker
			jQuery('.wp-color-picker-field').wpColorPicker();
		});
		/*jQuery(document).ready(function() {
				jQuery('#colorbox_1').farbtastic('#color_value_1');
				jQuery('#color_picker_1').click(function () {
				   if (jQuery('#colorbox_1').css('display') == "block") {
					  jQuery('#colorbox_1').fadeOut("slow"); }
				   else {
					  jQuery('#colorbox_1').fadeIn("slow"); }
				});
				var colorpick_1 = false;
				jQuery(document).mousedown(function(){
					if (colorpick_1 == true) {
						return; }
						jQuery('#colorbox_1').fadeOut("slow");
				});
				jQuery(document).mouseup(function(){
					colorpick_1 = false;
				});
				});*/
		</script>
		<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow|Pacifico' rel='stylesheet' type='text/css'>
<?php	}
  }
}
}
add_action('admin_head', 'cute_profiles_admin_head');


function cute_profiles_settings_page() { 
global $cute_profiles,$default_cute_profiles_settings,$cute_profiles_options;
$cute_profiles_curr = get_option('cute_profiles_options');
// displaying plugin version info
	require_once(ABSPATH.'/wp-admin/includes/plugin-install.php');
	$plug_api = plugins_api('plugin_information', array('slug' => sanitize_title('Cute Profiles') ));
		if ( is_wp_error($plug_api) ) {
			wp_die($plug_api);
		}

$curr=''; ?>
<div class="wrap" style="clear:both;">
	<h2 class="top_heading"><?php _e('Cute Profiles ','cute-profiles'); echo '<span>'.$curr.'</span>'; ?> </h2>
</div>
<?php 
$new_settings_msg='';
echo $new_settings_msg;?>
<div id="cute_profiles_settings" style="float:left;width:70%;">
	<?php //print_r ($cute_profiles); ?>
	<form method="post" action="options.php" id="cute_profiles_form">
		<?php  settings_fields('cute-profiles-group');
		//$cute_profiles = get_option('cute_profiles_options');
		?>
		<table class="form-table" id="cute_profiles_table">
			<tbody id="cute_profiles_tbody">
			<?php $cute_profiles_network = $cute_profiles['profiles'];
			//print_r ($cute_profiles);
			$i=0;
			foreach ($cute_profiles_network as $key=>$value) { 
				//print_r ($key);
				//print_r ($value);
				if (!empty ($value['p_url'])) $cute_profiles_nw = str_replace(" ","",$value['p_url']);
				//print_r ($cute_profiles_nw);			
				$display='';
				if ($value['p_flag'] == '0') {
					$display=' style="display:none;"';
				}
				?>
				<tr valign="top" <?php echo $display; ?> id="cute_profiles_<?php echo $key;?>_section" class="ui-sortable" cursor="move">
				<th scope="row"><label for="cute_profiles_options[profiles][<?php echo $key; ?>]"> <?php echo $value['p_name'];?><input type="hidden"  name="cute_profiles_options[profiles][<?php echo $key;?>][p_name]" value="<?php echo $value['p_name'];?>" /></label></th><td style="width:390px;"><div class="cute_profiles_style" style="position:inherit;background:transparent !important;float:left;padding-top:0px !important;"><a class="cute_profiles_<?php echo $value['p_num'];?> cute_profiles_i" href="<?php echo $value['p_url'];?>"></a></div>
				<input type="text" name="cute_profiles_options[profiles][<?php echo $key;?>][p_url]" id="cute_profiles_<?php echo $key;?>" class="medium-text prepopulate" value="<?php echo $value['p_url']; ?>"/>
				
				<input style="text-indent:-99999px;" type="button" id="cute_profiles_<?php echo $key;?>-delete" value="" class="deleteRow" onclick="cute_profiles_delete_js(this.id);" /> <input type="hidden" value="" name="qid[]" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_flag" name="cute_profiles_options[profiles][<?php echo $key;?>][p_flag]" value="<?php echo $value['p_flag'];?>" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_pnum" name="cute_profiles_options[profiles][<?php echo $key;?>][p_num]" value="<?php echo $value['p_num'];?>" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_icon" name="cute_profiles_options[profiles][<?php echo $key;?>][icon_url]" value="<?php echo $value['icon_url'];?>" /></td><td></td>
				</tr>
				<?php $i++;
			}
			?>
		</tbody></table>
		
		<?php if ( function_exists( 'put_cute_profiles' ) ) { put_cute_profiles(); } ?>
<p><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  &nbsp;  <input type="button" class="button" onclick="cute_profiles_add_js(<?php echo $i;?>);" value="Add More Profiles" /> &nbsp; </p>
		<h2 class="sub-heading"><?php _e('Basic Options','cute-profiles'); ?></h2> 
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Skin','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[theme]">
			<option value="default" <?php if ($cute_profiles['theme'] == "default"){ echo "selected";}?> >Default</option>
			<option value="grayscale" <?php if ($cute_profiles['theme'] == "grayscale"){ echo "selected";}?> >Grayscale</option>
			<option value="circle" <?php if ($cute_profiles['theme'] == "circle"){ echo "selected";}?> >Circle</option>
			<option value="black-white" <?php if ($cute_profiles['theme'] == "black-white"){ echo "selected";}?> >Darkness</option>
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Icon Alignment','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[align]">
			<option value="left" <?php if ($cute_profiles['align'] == "left"){ echo "selected";}?> >Left</option>
			<option value="right" <?php if ($cute_profiles['align'] == "right"){ echo "selected";}?> >Right</option>
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Icon Size','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[size]">
			<option value="16" <?php if ($cute_profiles['size'] == "16"){ echo "selected";}?> >16 x 16</option>
			<option value="24" <?php if ($cute_profiles['size'] == "24"){ echo "selected";}?> >24 x 24</option>
			<option value="32" <?php if ($cute_profiles['size'] == "32"){ echo "selected";}?> >32 x 32</option>	
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Distance From Top','indexo-slider'); ?></th>
			<td><input type="text" name="cute_profiles_options[top]" class="small-text" value="<?php echo $cute_profiles['top']; ?>" />&nbsp;px</td>
			</tr>
		</table>

		<h2 class="sub-heading"><?php _e('Advance Options','cute-profiles'); ?></h2>
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Insert Cute Profiles','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[insert]">
			<option value="auto" <?php if ($cute_profiles['insert'] == "auto"){ echo "selected";}?> >Automatic</option>
			<option value="manual" <?php if ($cute_profiles['insert'] == "manual"){ echo "selected";}?> >Manual</option>
			</select></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Show Cute Profiles on','indexo-slider'); ?></th>
			<td>
			<input type="checkbox" name="cute_profiles_options[show_posts]" value="1" <?php checked('1', $cute_profiles['show_posts']); ?> /> <?php _e('Posts', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[show_pages]" value="1" <?php checked('1', $cute_profiles['show_pages']); ?> /> <?php _e('Pages', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[show_frontpage]" value="1" <?php checked('1', $cute_profiles['show_frontpage']); ?> /> <?php _e('Front page', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[show_category]" value="1" <?php checked('1', $cute_profiles['show_category']); ?> /> <?php _e('Archive pages (categories, tags, dates, authors)', 'cute_profiles'); ?><br/>				
			<input type="checkbox" name="cute_profiles_options[show_search]" value="1" <?php checked('1', $cute_profiles['show_search']); ?> /> <?php _e('Search pages', 'cute_profiles'); ?><br/>				
			<br/>	
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Profile Link rel Attribute','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[follow]">
			<option value="dofollow" <?php if ($cute_profiles['follow'] == "dofollow"){ echo "selected";}?> >Dofollow</option>
			<option value="nofollow" <?php if ($cute_profiles['follow'] == "nofollow"){ echo "selected";}?> >No Follow</option>
			</select></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Profiles Background Color','cute-profiles'); ?></th>
			<!--<td><input type="text" name="cute_profiles_options[bg_color]" id="color_value_1" value="<?php echo $cute_profiles['bg_color']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo cute_profiles_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cute-profiles'); ?>" /><div class="color-picker-wrap" id="colorbox_1"></div> &nbsp; &nbsp; &nbsp; -->
			<td><input type="text" name="cute_profiles_options[bg_color]" id="color_value_1" value="<?php echo $cute_profiles['bg_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" /> </br>
			<?php $bg=isset($cute_profiles['bg'])?$cute_profiles['bg']:'0';?>
			<label for="cute_profiles_bg"><input name="cute_profiles_options[bg]" type="checkbox" id="cute_profiles_bg" value="1" <?php checked('1', $bg); ?>  /><?php _e(' Use Transparent Background','cute-profiles'); ?></label> </td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Enable Preview','indexo-slider'); ?></th>
			<td>
			<input type="checkbox" name="cute_profiles_options[admin_preview]" value="1" <?php checked('1', $cute_profiles['admin_preview']); ?> />
			</td>
			</tr>
		</table>
			<?php $cute_sharing_network = $cute_profiles['sharings'];
			//print_r ($cute_profiles);
			$i=0;
			foreach ($cute_sharing_network as $key=>$value) { ?>
			<input type="hidden"  id="cute_sharings_<?php echo $key;?>_pname" name="cute_profiles_options[sharings][<?php echo $key;?>][p_name]" value="<?php echo $value['p_name'];?>" />
			<input type="hidden"  id="cute_sharings_<?php echo $key;?>_icon" name="cute_profiles_options[sharings][<?php echo $key;?>][icon_url]" value="<?php echo $value['icon_url'];?>" />
			<input type="hidden"  id="cute_sharings_<?php echo $key;?>_purl" name="cute_profiles_options[sharings][<?php echo $key;?>][p_url]" value="<?php echo $value['p_url'];?>" /> 
			<input type="hidden"  id="cute_sharings_<?php echo $key;?>_pnum" name="cute_profiles_options[sharings][<?php echo $key;?>][p_num]" value="<?php echo $value['p_num'];?>" />
			<input type="hidden"  id="cute_sharings_<?php echo $key;?>_pflag" name="cute_profiles_options[sharings][<?php echo $key;?>][p_flag]" value="<?php echo $value['p_flag'];?>" />
			<?php } ?>
			<input type="hidden"  name="cute_profiles_options[s_insert]" value="<?php echo $cute_profiles['s_insert'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_follow]" value="<?php echo $cute_profiles['s_follow'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_bg_color]" value="<?php echo $cute_profiles['s_bg_color'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_bg]" value="<?php echo $cute_profiles['s_bg'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_show_posts]" value="<?php echo $cute_profiles['s_show_posts'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_show_pages]" value="<?php echo $cute_profiles['s_show_pages'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_show_frontpage]" value="<?php echo $cute_profiles['s_show_frontpage'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_show_category]" value="<?php echo $cute_profiles['s_show_category'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_show_search]" value="<?php echo $cute_profiles['s_show_search'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_align]" value="<?php echo $cute_profiles['s_align'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_size]" value="<?php echo $cute_profiles['s_size'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_theme]" value="<?php echo $cute_profiles['s_theme'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_top]" value="<?php echo $cute_profiles['s_top'];?>" />
			<input type="hidden"  name="cute_profiles_options[s_admin_preview]" value="<?php echo $cute_profiles['s_admin_preview'];?>" />

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>	 
</div>

<div id="side-info-column" style="float:left;width:25%;max-width:200px;padding:0 10px;"> 
			<div class="postbox"> 
			  <h3 style="padding:10px;" class="hndle"><span>About this Plugin:</span></h3> 
			  <div class="inside">
                <ul>
				<li>Current Version &nbsp;<?php echo  CUTE_PROFILES_VER ?></li>
                <li><a href="http://keencodes.com/support" title="Support Forum for Cute Profiles" >Support Forum</a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Cute Profiles Author Page" >About the Author</a></li>
                <li style="padding: 10px 0;"><strong style="font-family: impact;font-size: 24px;margin-top: 20px;"><?php echo $plug_api->downloaded; ?></strong> Downloads and counting...</li>
                </ul> 
              </div> 
			</div> 
			<div class="postbox"> 
			  <h3 class="hndle" style="padding:10px;" ><span></span>Search &amp; Compare Themes</h3> 
			  <div class="inside">
                     <div style="margin:10px auto">
                        <a href="https://purposethemes.com/" title="Search and Compare WordPress Themes" target="_blank"><img style="max-width: 100%;" src="<?php echo cute_profiles_plugin_url('images/purposethemes.png');?>" alt="Search and Compare WordPress Themes" /></a>
                        <p><a href="https://purposethemes.com/" title="Find best WordPress theme for your niche website" target="_blank">On PurposeThemes,</a> find best WordPress theme for your niche website. Compare between free and premium WordPress themes from more than 25 WordPress theme clubs.</p>
                        <p><strong>Subscribe to find best offers on WordPress Themes</strong></p>
                        <p><a href="https://purposethemes.com/" title="Search and Compare WordPress Themes" target="_blank">For more info visit PurposeThemes</a></p>
                     </div>
            </div></div>
</div>

<?php
}

function cute_profiles_sharing_page() { 
global $cute_profiles,$default_cute_profiles_settings,$cute_profiles_options;
$cute_profiles_curr = get_option('cute_profiles_options');
// displaying plugin version info
	require_once(ABSPATH.'/wp-admin/includes/plugin-install.php');
	$plug_api = plugins_api('plugin_information', array('slug' => sanitize_title('Cute Profiles') ));
		if ( is_wp_error($plug_api) ) {
			wp_die($plug_api);
		}

$curr=''; ?>
<div class="wrap" style="clear:both;">
	<h2 class="top_heading"><?php _e('Cute Social Sharing','cute-profiles'); echo '<span>'.$curr.'</span>'; ?> </h2>
</div>
<?php 
$new_settings_msg='';
echo $new_settings_msg;?>
<div id="cute_profiles_settings" style="float:left;width:70%;">
	<?php //print_r ($cute_profiles); ?>
	<form method="post" action="options.php" id="cute_profiles_form">
		<?php  settings_fields('cute-profiles-group');
		$cute_profiles = get_option('cute_profiles_options');
		?>
		<table class="form-table" id="cute_profiles_table">
			<tbody id="cute_profiles_tbody">
			<?php $cute_profiles_network = $cute_profiles['sharings'];
			//print_r ($cute_profiles);
			$i=0;
			foreach ($cute_profiles_network as $key=>$value) { 
				//print_r ($key);
				//print_r ($value);
				//if (!empty ($value['p_url'])) $cute_profiles_nw = str_replace(" ","",$value['p_url']);
				//print_r ($cute_profiles_nw);			
				$display='';
				if ($value['p_flag'] == '0') {
					$display=' style="display:none;"';
				}
				?>
				<tr valign="top" <?php echo $display; ?> id="cute_profiles_<?php echo $key;?>_section" class="ui-sortable" cursor="move">
				<th scope="row"><label for="cute_profiles_options[sharings][<?php echo $key; ?>]"> <?php echo $value['p_name'];?><input type="hidden"  name="cute_profiles_options[sharings][<?php echo $key;?>][p_name]" value="<?php echo $value['p_name'];?>" /></label></th><td style="width:390px;"><div class="cute_sharing_style" style="position:inherit;background:transparent !important;float:left;padding-top:0px !important;"><a class="cute_sharing_<?php echo $value['p_num'];?> cute_sharing_i" href="<?php echo $value['p_url'];?>"></a></div>
				
				<input style="text-indent:-99999px;" type="button" id="cute_profiles_<?php echo $key;?>-delete" value="" class="deleteRow" onclick="cute_profiles_delete_js(this.id);" /> <input type="hidden" value="" name="qid[]" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_flag" name="cute_profiles_options[sharings][<?php echo $key;?>][p_flag]" value="<?php echo $value['p_flag'];?>" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_pnum" name="cute_profiles_options[sharings][<?php echo $key;?>][p_num]" value="<?php echo $value['p_num'];?>" /><input type="hidden"  id="cute_profiles_<?php echo $key;?>_icon" name="cute_profiles_options[sharings][<?php echo $key;?>][icon_url]" value="<?php echo $value['icon_url'];?>" /></td><td></td>
				</tr>
				<?php $i++;
			}
			?>
		</tbody></table>
		
		<?php if ( function_exists( 'put_cute_profiles_sharing' ) ) { put_cute_profiles_sharing('1'); } ?>
<p><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  &nbsp;  <input type="button" class="button" onclick="cute_sharing_add_js(<?php echo $i;?>);" value="Add More Profiles" /> &nbsp; </p>
		<h2 class="sub-heading"><?php _e('Basic Options','cute-profiles-sharing'); ?></h2> 
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Skin','cute-profiles-sharing'); ?></th>
			<td><select name="cute_profiles_options[s_theme]">
			<option value="default" <?php if ($cute_profiles['s_theme'] == "default"){ echo "selected";}?> >Default</option>
			<option value="grayscale" <?php if ($cute_profiles['s_theme'] == "grayscale"){ echo "selected";}?> >Grayscale</option>
			<option value="circle" <?php if ($cute_profiles['s_theme'] == "circle"){ echo "selected";}?> >Circle</option>
			<option value="black-white" <?php if ($cute_profiles['s_theme'] == "black-white"){ echo "selected";}?> >Darkness</option>
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Icon Alignment','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[s_align]">
			<option value="left" <?php if ($cute_profiles['s_align'] == "left"){ echo "selected";}?> >Left</option>
			<option value="right" <?php if ($cute_profiles['s_align'] == "right"){ echo "selected";}?> >Right</option>
			<option value="afterpost" <?php if ($cute_profiles['s_align'] == "afterpost"){ echo "selected";}?> >After Post Content</option>
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Icon Size','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[s_size]">
			<option value="16" <?php if ($cute_profiles['s_size'] == "16"){ echo "selected";}?> >16 x 16</option>
			<option value="24" <?php if ($cute_profiles['s_size'] == "24"){ echo "selected";}?> >24 x 24</option>
			<option value="32" <?php if ($cute_profiles['s_size'] == "32"){ echo "selected";}?> >32 x 32</option>	
			</select>
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Distance From Top','indexo-slider'); ?></th>
			<td><input type="text" name="cute_profiles_options[s_top]" class="small-text" value="<?php echo $cute_profiles['s_top']; ?>" />&nbsp;px</td>
			</tr>
		</table>

		<h2 class="sub-heading"><?php _e('Advance Options','cute-profiles'); ?></h2>
		<table class="form-table">
			<tr valign="top">
			<th scope="row"><?php _e('Insert Cute Profiles','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[s_insert]">
			<option value="auto" <?php if ($cute_profiles['s_insert'] == "auto"){ echo "selected";}?> >Automatic</option>
			<option value="manual" <?php if ($cute_profiles['s_insert'] == "manual"){ echo "selected";}?> >Manual</option>
			</select></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Show Cute Profiles on','indexo-slider'); ?></th>
			<td>
			<input type="checkbox" name="cute_profiles_options[s_show_posts]" value="1" <?php checked('1', $cute_profiles['s_show_posts']); ?> /> <?php _e('Posts', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[s_show_pages]" value="1" <?php checked('1', $cute_profiles['s_show_pages']); ?> /> <?php _e('Pages', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[s_show_frontpage]" value="1" <?php checked('1', $cute_profiles['s_show_frontpage']); ?> /> <?php _e('Front page', 'cute_profiles'); ?><br/>
			<input type="checkbox" name="cute_profiles_options[s_show_category]" value="1" <?php checked('1', $cute_profiles['s_show_category']); ?> /> <?php _e('Archive pages (categories, tags, dates, authors)', 'cute_profiles'); ?><br/>				
			<input type="checkbox" name="cute_profiles_options[show_search]" value="1" <?php checked('1', $cute_profiles['s_show_search']); ?> /> <?php _e('Search pages', 'cute_profiles'); ?><br/>				
			<br/>	
			</td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Profile Link rel Attribute','indexo-slider'); ?></th>
			<td><select name="cute_profiles_options[s_follow]">
			<option value="dofollow" <?php if ($cute_profiles['s_follow'] == "dofollow"){ echo "selected";}?> >Dofollow</option>
			<option value="nofollow" <?php if ($cute_profiles['s_follow'] == "nofollow"){ echo "selected";}?> >No Follow</option>
			</select></td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Profiles Background Color','cute-profiles'); ?></th>
			<!--<td><input type="text" name="cute_profiles_options[s_bg_color]" id="color_value_1" value="<?php echo $cute_profiles['s_bg_color']; ?>" />&nbsp; <img id="color_picker_1" src="<?php echo cute_profiles_plugin_url( 'images/color_picker.png' ); ?>" alt="<?php _e('Pick the color of your choice','cute-profiles'); ?>" /><div class="color-picker-wrap" id="colorbox_1"></div> &nbsp; &nbsp; &nbsp; -->
			<td><input type="text" name="cute_profiles_options[s_bg_color]" id="color_value_1" value="<?php echo $cute_profiles['s_bg_color']; ?>" class="wp-color-picker-field" data-default-color="#ffffff" /> </br>

			<label for="cute_profiles_bg"><input name="cute_profiles_options[bg]" type="checkbox" id="cute_profiles_bg" value="1" <?php checked('1', $cute_profiles['s_bg']); ?>  /><?php _e(' Use Transparent Background','cute-profiles'); ?></label> </td>
			</tr>
			<tr valign="top">
			<th scope="row"><?php _e('Enable Preview','indexo-slider'); ?></th>
			<td>
			<input type="checkbox" name="cute_profiles_options[s_admin_preview]" value="1" <?php checked('1', $cute_profiles['s_admin_preview']); ?> />
			</td>
			</tr>
		</table>
		
			<?php $cute_profiles_network = $cute_profiles['profiles'];
			//print_r ($cute_profiles);
			$i=0;
			foreach ($cute_profiles_network as $key=>$value) { ?>
			<input type="hidden"  id="cute_profiles_<?php echo $key;?>_pname" name="cute_profiles_options[profiles][<?php echo $key;?>][p_name]" value="<?php echo $value['p_name'];?>" />
			<input type="hidden"  id="cute_profiles_<?php echo $key;?>_icon" name="cute_profiles_options[profiles][<?php echo $key;?>][icon_url]" value="<?php echo $value['icon_url'];?>" />
			<input type="hidden"  id="cute_profiles_<?php echo $key;?>_purl" name="cute_profiles_options[profiles][<?php echo $key;?>][p_url]" value="<?php echo $value['p_url'];?>" />
			<input type="hidden"  id="cute_profiles_<?php echo $key;?>_pnum" name="cute_profiles_options[profiles][<?php echo $key;?>][p_num]" value="<?php echo $value['p_num'];?>" />
			<input type="hidden"  id="cute_profiles_<?php echo $key;?>_pflag" name="cute_profiles_options[profiles][<?php echo $key;?>][p_flag]" value="<?php echo $value['p_flag'];?>" />
			<?php } ?>
			<input type="hidden"  name="cute_profiles_options[insert]" value="<?php echo $cute_profiles['insert'];?>" />
			<input type="hidden"  name="cute_profiles_options[follow]" value="<?php echo $cute_profiles['follow'];?>" />
			<input type="hidden"  name="cute_profiles_options[bg_color]" value="<?php echo $cute_profiles['bg_color'];?>" />
			<?php $bg=isset($cute_profiles['bg'])?$cute_profiles['bg']:'0';?>
			<input type="hidden"  name="cute_profiles_options[bg]" value="<?php echo $bg;?>" />
			<input type="hidden"  name="cute_profiles_options[show_posts]" value="<?php echo $cute_profiles['show_posts'];?>" />
			<input type="hidden"  name="cute_profiles_options[show_pages]" value="<?php echo $cute_profiles['show_pages'];?>" />
			<input type="hidden"  name="cute_profiles_options[show_frontpage]" value="<?php echo $cute_profiles['show_frontpage'];?>" />
			<input type="hidden"  name="cute_profiles_options[show_category]" value="<?php echo $cute_profiles['show_category'];?>" />
			<input type="hidden"  name="cute_profiles_options[show_search]" value="<?php echo $cute_profiles['show_search'];?>" />
			<input type="hidden"  name="cute_profiles_options[align]" value="<?php echo $cute_profiles['align'];?>" />
			<input type="hidden"  name="cute_profiles_options[size]" value="<?php echo $cute_profiles['size'];?>" />
			<input type="hidden"  name="cute_profiles_options[theme]" value="<?php echo $cute_profiles['theme'];?>" />
			<input type="hidden"  name="cute_profiles_options[top]" value="<?php echo $cute_profiles['top'];?>" />
			<input type="hidden"  name="cute_profiles_options[admin_preview]" value="<?php echo $cute_profiles['admin_preview'];?>" />
		
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>	 
</div>

<div id="side-info-column" style="float:left;width:25%;max-width:200px;padding:0 10px;"> 
			<div class="postbox"> 
			  <h3 style="padding:10px;" class="hndle"><span>About this Plugin:</span></h3> 
			  <div class="inside">
                <ul>
				<li>Current Version &nbsp;<?php echo  CUTE_PROFILES_VER ?></li>
                <li><a href="http://keencodes.com/support" title="Support Forum for Cute Profiles" >Support Forum</a></li>
                <li><a href="http://www.clickonf5.org/about/tejaswini" title="Cute Profiles Author Page" >About the Author</a></li>
                <li style="padding: 10px 0;"><strong style="font-family: impact;font-size: 24px;margin-top: 20px;"><?php echo $plug_api->downloaded; ?></strong> Downloads and counting...</li>
                </ul> 
              </div> 
			</div> 
			<div class="postbox"> 
			  <h3 class="hndle" style="padding:10px;" ><span></span>Search &amp; Compare Themes</h3> 
			  <div class="inside">
                     <div style="margin:10px auto">
                        <a href="https://purposethemes.com/" title="Search and Compare WordPress Themes" target="_blank"><img style="max-width: 100%;" src="<?php echo cute_profiles_plugin_url('images/purposethemes.png');?>" alt="Search and Compare WordPress Themes" /></a>
                        <p><a href="https://purposethemes.com/" title="Find best WordPress theme for your niche website" target="_blank">On PurposeThemes,</a> find best WordPress theme for your niche website. Compare between free and premium WordPress themes from more than 25 WordPress theme clubs.</p>
                        <p><strong>Subscribe to find best offers on WordPress Themes</strong></p>
                        <p><a href="https://purposethemes.com/" title="Search and Compare WordPress Themes" target="_blank">For more info visit PurposeThemes</a></p>
                     </div>
            </div></div>
</div>

<?php
}

function register_cute_profiles_settings() { // whitelist options
  register_setting( 'cute-profiles-group', 'cute_profiles_options' );
} ?>
