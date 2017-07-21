<?php
//Function to put Cute Profiles on webpages
function put_cute_profiles () { ?>
<?php 
//set 1
	global $cute_profiles;
	$cute_profiles_network = $cute_profiles['profiles'];
	$cphtml = 'cute_profiles_style';
	if ((is_single()) && ($cute_profiles['show_posts'] != '1'))
	$cphtml.=' cute_profiles_none';
	if ((is_page()) && ($cute_profiles['show_pages'] != '1'))
	$cphtml.=' cute_profiles_none';
	if ((is_home()) && ($cute_profiles['show_frontpage'] != '1'))
	$cphtml.=' cute_profiles_none';
	if ((is_archive()) && ($cute_profiles['show_category'] != '1'))
	$cphtml.=' cute_profiles_none';
	if ((is_search()) && ($cute_profiles['show_search'] != '1'))
	$cphtml.=' cute_profiles_none';
	if ((is_admin()) && ($cute_profiles['admin_preview'] != '1'))
	$cphtml.=' cute_profiles_none';
	if (is_active_widget())
	$cphtml.=' cute_profiles_widget';
	?><div class="<?php echo $cphtml; ?>">
	<?php
	foreach ($cute_profiles_network as $key=>$value) {
	$cute_profiles_nw = str_replace(" ","",$value['p_url']); if ((!empty($cute_profiles_nw)) && ($value['p_flag'] != '0')) { ?>
	<a title="<?php echo ucwords($value['p_name']);?>" class="cute_profiles_<?php echo $value['p_num'];?> cute_profiles_i" href="<?php echo $cute_profiles_nw; ?>" target="_blank" <?php if ($cute_profiles['follow'] == 'nofollow') { ?>rel="nofollow"<?php } ?> ></a>
<?php }} ?>
</div>

<?php
}
//Function for Cute Sharer to show on webpages
function put_cute_profiles_sharing ($echo='0') { ?>
<?php 
//set 1
	global $cute_profiles;
	$html='';
	$no_cute_sharing='0';
	$cute_profiles_network = $cute_profiles['sharings'];
	$cphtml = 'cute_sharing_style';
	//Condition for control on showing Cute Sharer on Posts, Pages, Front Page, Archives
	if (((is_single()) && (($cute_profiles['s_show_posts'] != '1'))) or ((is_page()) && ($cute_profiles['s_show_pages'] != '1')) or ((is_home()) && ($cute_profiles['s_show_frontpage'] != '1')) or ((is_archive()) && ($cute_profiles['s_show_category'] != '1')) or ((is_search()) && ($cute_profiles['s_show_search'] != '1')) or ((is_admin()) && ($cute_profiles['s_admin_preview'] != '1'))) {
		$no_cute_sharing='1';}
	else {
		$no_cute_sharing='0';}
	if (( ! is_admin() ) && ((($cute_profiles['s_insert'] == 'auto') and ($cute_profiles['s_align'] == 'afterpost'))  or (($cute_profiles['s_insert'] == 'manual') && ($cute_profiles['s_align'] != 'left') && ($cute_profiles['s_align'] != 'right')) ))
	$cphtml.=' cute_sharing_afterpost';
	
	if (is_active_widget())	$cphtml.=' cute_sharing_widget';
	if ($no_cute_sharing != '1') {	
		$html.='<div class="'.$cphtml.'">';
		$title = urlencode(get_the_title());
		$url = urlencode(get_permalink());
		$url_d = get_permalink();
		$title_d = get_the_title();
		foreach ($cute_profiles_network as $key=>$value) {
			if ($key == '0') {
			$value['p_url']='http://www.facebook.com/sharer/sharer.php?s=100&p[url]='.$url.'&p[title]='.$title;
			}
			if ($key == '1') {
			$value['p_url']='http://twitter.com/intent/tweet/?text='.$title.'&amp;url='.$url;
			}
			if ($key == '2') {
			$value['p_url']='https://plus.google.com/share?url='.$url;
			}
			if ($key == '3') {
			$value['p_url']='http://digg.com/submit?url='.$url_d;
			}
			if ($key == '4') {
			$value['p_url']='http://reddit.com/submit?url='.$url_d.'&title='.$title;
			}
			$cute_profiles_nw = str_replace(" ","",$value['p_url']); 
			
			if (($value['p_flag'] != '0')) { 
			$html.='<a title="'.ucwords($value['p_name']).'" class="cute_sharing_'.$value['p_num'].' cute_sharing_i" href="'.$cute_profiles_nw.'" target="_blank" '. ( ($cute_profiles['s_follow'] == 'nofollow')? ( 'rel="external nofollow"') : '' ) .' ></a>';
			}
		}	
		$html.='</div>';
	}
	if($echo=='1')echo $html;
	else return $html;
	return '';
}
//Add Cute Sharer after content
add_shortcode('put_cute_profiles_sharing', 'put_cute_profiles_sharing');
function cute_sharing_after_post($content){
	global $cute_profiles;
	$cute_sharing_afterpost = $cute_profiles['s_align'];
	if (($cute_sharing_afterpost == 'afterpost') && ($cute_profiles['s_insert'] == 'auto')) {	
		$content .= put_cute_profiles_sharing('0');
	}
	return $content;
}
add_filter( "the_content", "cute_sharing_after_post" );
//widget for Cute Profiles
class Cute_Profiles_Widget extends WP_Widget {
	function Cute_Profiles_Widget() {
		$widget_options = array('classname' => 'cute_profiles_wclass', 'description' => 'Insert Cute Profiles' );
		parent::__construct('cute_profiles_wid', 'Cute Profiles', $widget_options);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
	    global $cute_profiles;
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
		
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title; 
		 put_cute_profiles();
		echo $after_widget;
	}
	// widget form creation
	function form($instance) {
		$text = '';
		$textarea = '';
		// Check values
		if( $instance) {
			 $title = esc_attr($instance['title']);
			 $text = esc_attr($instance['text']);
			 $textarea = esc_textarea($instance['textarea']);
		} else {
			 $title = '';
			 $text = '';
			 $textarea = '';
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<?php
	}
	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		 return $instance;
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Cute_Profiles_Widget");') );

//two inline styles for the distance of the icons from the top
function cute_profiles_css() {
	?>
	<style type="text/css" media="screen">
	div.cute_profiles_style{
	top:<?php global $cute_profiles; echo $cute_profiles['top'];?>px;
	<?php global $cute_profiles; if (!is_admin()) echo $cute_profiles['align'];?>:0px;
	}
	<?php if ($cute_profiles['bg'] == '1') { $cute_profiles_nav_bg = "transparent";} else { $cute_profiles_nav_bg = $cute_profiles['bg_color']; } ?>
			.cute_profiles_style {background:<?php echo $cute_profiles_nav_bg; ?> !important;}
			
	<?php 
	$cute_profiles_network = $cute_profiles['profiles'];
	foreach ($cute_profiles_network as $key=>$value) { if ($value['p_num']>'26') {?>
	div.cute_profiles_style a.cute_profiles_<?php echo $cute_profiles['profiles'][$key]['p_num']; ?> {background:url(<?php echo $cute_profiles['profiles'][$key]['icon_url']?>) no-repeat 0 0;background-size:<?php echo $cute_profiles['size'] ?>px <?php echo $cute_profiles['size'] ?>px;}
	<?php } else { ?>div.cute_profiles_style a.cute_profiles_<?php echo $cute_profiles['profiles'][$key]['p_num']; ?> {background-size:<?php echo $cute_profiles['size'] ?>px <?php echo $cute_profiles['size'] ?>px;}<?php }}?>
	.cute_profiles_none {display:none;}
	.cute_profiles_wclass .cute_profiles_style {top: inherit;
	right: inherit;
	position: inherit;padding: 5px 0px 5px 5px;}
	.cute_profiles_wclass .cute_profiles_i{margin-right: 5px !important;}
	</style>

	<?php
}
add_action('wp_head', 'cute_profiles_css');

function cute_sharing_css() {
	?>
	<style type="text/css" media="screen">
	div.cute_sharing_style{
	top:<?php global $cute_profiles; echo $cute_profiles['s_top'];?>px;
	<?php global $cute_profiles; if (!is_admin()) echo $cute_profiles['s_align'];?>:0px;
	}
	<?php if ($cute_profiles['s_bg'] == '1') { $cute_profiles_nav_bg = "transparent";} else { $cute_profiles_nav_bg = $cute_profiles['s_bg_color']; } ?>
			.cute_sharing_style {background:<?php echo $cute_profiles_nav_bg; ?> !important;}
			
	<?php 
	//Cute Sharer CSS in HEAD
	$cute_profiles_network = $cute_profiles['sharings'];
	foreach ($cute_profiles_network as $key=>$value) { 
		if ($value['p_num']>'5') {?>
			div.cute_sharing_style a.cute_sharing_<?php echo $cute_profiles['sharings'][$key]['p_num']; ?> {background:url(<?php echo $cute_profiles['sharings'][$key]['icon_url']?>) no-repeat 0 0;background-size:<?php echo $cute_profiles['s_size'] ?>px <?php echo $cute_profiles['s_size'] ?>px;}
			<?php } 
		else { 
			?>div.cute_sharing_style a.cute_sharing_<?php echo $cute_profiles['sharings'][$key]['p_num']; ?> {background-size:<?php echo $cute_profiles['s_size'] ?>px <?php echo $cute_profiles['s_size'] ?>px;}<?php }
	}?>
	.cute_sharing_none {display:none;}
	.cute_sharing_wclass .cute_sharing_style {top: inherit;
	right: inherit;
	position: inherit;padding: 5px 0px 8px 0px;}
	.cute_sharing_wclass .cute_sharing_i{margin-right: 5px !important;}
	.cute_sharing_afterpost .cute_sharing_i {padding:0 4px;}
	</style>

	<?php
}
add_action('wp_head', 'cute_sharing_css');
//Enqueue CSS Stylesheet for Cute Profiles and Sharer
function cute_profiles_enqueue_styles() {
	global $cute_profiles_options,$cute_profiles;
	$cute_profiles_style = $cute_profiles['theme'];
	$cute_profiles_size = $cute_profiles['size'];
	wp_enqueue_style( 'cute_profiles_headcss', cute_profiles_plugin_url( 'css/skins/'.$cute_profiles_style.'/'.$cute_profiles_size.'/style.css' ),
		false, CUTE_PROFILES_VER, 'all');
}
add_action( 'wp', 'cute_profiles_enqueue_styles' );
function cute_sharing_enqueue_styles() {
	global $cute_profiles_options,$cute_profiles;
	$cute_sharing_style = $cute_profiles['s_theme'];
	$cute_sharing_size = $cute_profiles['s_size'];
	wp_enqueue_style( 'cute_sharing_headcss', cute_profiles_plugin_url( 'css/skins/'.$cute_sharing_style.'/sharing/'.$cute_sharing_size.'/style.css' ),
		false, CUTE_PROFILES_VER, 'all');
}
add_action( 'wp', 'cute_sharing_enqueue_styles' );
//insert the profiles automatically with the get_footer action hook
if ($cute_profiles['insert'] == 'auto') {
	add_action( 'get_footer', 'put_cute_profiles' );
}
if (($cute_profiles['s_insert'] == 'auto') && ($cute_profiles['s_align'] != 'afterpost')) {
	add_action( 'get_footer', 'put_cute_profiles_sharing' );
}
?>
