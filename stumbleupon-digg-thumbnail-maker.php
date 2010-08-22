<?php

/* 
	Plugin Name: Stumbleupon & Digg Thumbnail Maker
	Plugin URI: http://techmilieu.com/sumbleupon-and-digg-thumbnail-maker
	Description: Stumbleupon & Digg Thumbnail Maker allows you to specifically select a thumbnail for a post when it is submitted to some social media sites.
	Author: Philip Ze 
	Version: 1.1
	Author URI: http://techmilieu.com/
*/

/*	Copyright 2010 Philip Ze [ http://techmilieu.com/sumbleupon-and-digg-thumbnail-maker ]

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/

global $SUD_DA;
$SUD_DA['Name'] = 'Stumbleupon & Digg Thumbnail Maker';
$SUD_DA['Version'] = '1.1';
$SUD_DA['URI'] = 'http://techmilieu.com/sumbleupon-and-digg-thumbnail-maker';


function sud_thumb_add_meta_box() {
	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'sud_thumb_sectionid', __( 'Stumbleupon & Digg Thumbnail Maker', 'sud_thumb_textdomain' ), 'sud_thumb_inner_box', 'post', 'normal' , 'high' );
		add_meta_box( 'sud_thumb_sectionid', __( 'Stumbleupon & Digg Thumbnail Maker', 'sud_thumb_textdomain' ), 'sud_thumb_inner_box', 'page', 'normal' , 'high' );
	} else {
		add_action('dbx_post_advanced', 'sud_thumb_custom_box_old' );
		add_action('dbx_page_advanced', 'sud_thumb_custom_box_old' );
	}
}
function sud_thumb_inner_box() { ?>
	<script type="text/javascript">
		function sud_thumb_preview() {
			thfd = document.getElementById("sud_thumb_new_field").value;
			thfd = 'http://'+thfd.replace('http://','');
			document.getElementById("sud_thumbnail").src=thfd;
		};
	</script> 
	<?php	
	global $post;
	$defimgdr = get_option('Default_ImgDir');
	$thumbimg = get_option('sud_thumb_url_'.$post->ID);
	$thumburl = (empty($thumbimg)) ? $defimgdr : $thumbimg;
	$thumbimg = str_replace("http://", "", $thumbimg);	
	$thumburl = str_replace("http://", "", $thumburl);	
	echo '<input type="hidden" name="sud_thumb_noncename" id="sud_thumb_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<table cellspacing="0" cellpadding="0"><tr>';
	echo '<td><p><img id="sud_thumbnail" src="http://'.$thumbimg.'" alt="No Thumbnail" style="height:86px;width:129px;background:#eee;border:1px solid #999;padding:1px;float:left"/></p></td>';
	echo '<td style="width:100%;vertical-align:top;"><p><label for="sud_thumb_new_field"><b>URL for thumbnail file</b> (best size with 360 x 240px) :<br/></label> ';
	echo '<input type="text" id="sud_thumb_new_field" name="sud_thumb_new_field" value="http://'.$thumburl.'" class="code" style="width:100%"/></p>';
	echo '<p><b>&laquo; <a href="javascript:sud_thumb_preview()">Preview</a></b></p></td></tr></table>';
}
function sud_thumb_custom_box_old() {
	echo '<div class="dbx-b-ox-wrapper">' . "\n";
	echo '<fieldset id="sud_thumb_fieldsetid" class="dbx-box">' . "\n";
	echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . __( 'Stumbleupon & Digg Thumbnail Maker', 'sud_thumb_textdomain' ) . "</h3></div>";   
	echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
	sud_thumb_inner_box();
	echo "</div></div></fieldset></div>\n";
}
function sud_thumb_save_postdata( $post_id ) {
	// verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['sud_thumb_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
	// if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	// Check permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}
	// OK, we're authenticated: we need to find and save the data
	$defimgdr = get_option('Default_ImgDir');
	$thumburl = trim($_POST['sud_thumb_new_field']);
	$defimgdr = str_replace("http://", "", $defimgdr);	
	$thumburl = str_replace("http://", "", $thumburl);
	if( $thumburl!="" && $defimgdr!=$thumburl ) {
		update_option('sud_thumb_url_'.$post_id, $thumburl);
		// add_post_meta() update_post_meta()
	}
	return $thumburl;
}
add_action('admin_menu', 'sud_thumb_add_meta_box');
add_action('save_post', 'sud_thumb_save_postdata');


function sud_admin_page_inc() { 
	include('stumbleupon-digg-thumbnail-maker-admin.php');
}
function sud_admin_page() {  
	add_options_page("Stumbleupon & Digg Thumbnail Maker", "SU+D Thumbnail", 8, basename(__FILE__), "sud_admin_page_inc");  
} 
function register_sud_settings() {
	register_setting( 'sud-options', 'Default_ImgDir');
	register_setting( 'sud-options', 'Default_HasCache');	
}
if(is_admin()) {
	add_action('admin_menu', 'sud_admin_page');
	add_action('admin_init', 'register_sud_settings');
}	


function sud_plugin_links($links, $file) {
	if($file==plugin_basename(__FILE__)) {
		array_unshift($links,'<a href="options-general.php?page='.basename(__FILE__).'">'.__('Setting').'</a>');
	}
	return $links;
}
add_filter('plugin_action_links','sud_plugin_links',10,2);


function sud_wp_head()
{
	global $post;
	$defhasch = get_option('Default_HasCache');		
	$thumburl = get_option('sud_thumb_url_'.$post->ID);
	$thumburl = str_replace("http://", "", $thumburl);	
	echo "<link rel='image_src' href='http://".$thumburl."' />"."\n";
	if ($defhasch) { ?>
	<script type="text/javascript">function do_sud_thumb(thumburl,thumbalt) { docr = decodeURIComponent(document.referrer); if ( docr.indexOf('http://www.stumbleupon.com/refer.php')!=-1 && docr.indexOf('<?php echo get_permalink($post->ID); ?>')!=-1) { document.write('<div><img src="'+thumburl+'" alt="'+thumbalt+'" /></div>'); } } </script>	
	<?php	}		
}
add_action('wp_head', 'sud_wp_head');

$has_thumb = false;
function insert_thumbnail()
{
	global $has_thumb;
	if($has_thumb) { return $content; }
	$c = sud_getimage();
	$has_thumb = true;
	echo $c;
}

function sud_the_content($content)
{
	global $has_thumb;
	if($has_thumb) { return $content; }
	$c = sud_getimage();
	return $c . $content;
}
add_action('the_content', 'sud_the_content'); 

function sud_getimage()
{
	$c = "";
	$defhasch = get_option('Default_HasCache');		
	if ( $defhasch ) {
		$c = sud_getimage_java() ;
	} else {	
		$c = sud_getimage_php() ;
	}	
	return $c;
}

function sud_getimage_php()
{
	global $post;
	$c = "";
	$refe = urldecode(wp_get_referer());
	$chk1 = "http://www.stumbleupon.com/refer.php?url=";
	$chk2 = get_permalink( $post->ID );
	if( strpos($refe,$chk1)!==false && strpos($refe,$chk2)!==false ) {
		$thumburl = get_option('sud_thumb_url_'.$post->ID);
		$thumburl = str_replace("http://", "", $thumburl);		
		$thumbalt = get_the_title();	
		if ( $thumburl != "" ) {
			$c = "<div><img src='http://".$thumburl."' alt='".$thumbalt."' /></div>\n" ;
		}		
	}	
	return $c;
}

function sud_getimage_java()
{
	global $post;
	$thumburl = get_option('sud_thumb_url_'.$post->ID);
	$thumburl = str_replace("http://", "", $thumburl);		
	$thumbalt = get_the_title();		
	$c = '<script type="text/javascript">do_sud_thumb("http://'.$thumburl.'","'.$thumbalt.'")</script>'."\n" ;
	return $c;
}


?>
