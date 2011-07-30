<?php

/*	Copyright 2009-2010 Philip Ze [ http://techmilieu.com/sumbleupon-and-digg-thumbnail-maker ]

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

?>

<?php
	global $SUD_DA;
?>
	
<div class="wrap">
<h2><?php echo $SUD_DA['Name']; ?> - Setting <span style="font-size:9pt;font-style:italic">( Version <?php echo $SUD_DA['Version']; ?> )</span></h2>

<form method="post" action="options.php">

	<h3 style="font-size:120%">General</h3>
	<p><b>Stumbleupon &amp; Digg Thumbnail Maker</b> is a simple Wordpress plugin that allows you to specifically select a thumbnail for a post when your post is submitted to some social media sites. StumbleUpon for example, it automatically create a thumbnail & attach to users 'favorite' when the web page is submitted. However, it does not always create a image thumbnail as what we want. Sometime, it just capture the whole web page or even takes the image ad on your page & use it as thumbnail.</p>
	<p>Use this plugin to insert some image & html herder tags into the post for the social media sites like Stumbleupon or Digg to pick up. <b>Check out the editing option right under your 'Edit Post' panel</b>.</p>	

	<div style="padding:10px;border:1px solid #999">
		A link from your blog to <b>TechMilieu</b> (http://techmilieu.com/) would be highly appreciated. A tiny button is also available for the link (http://techmilieu.com/images/link-20x20.png). Visit <a href="<?php echo $SUD_DA['URI']; ?>" target="_blank"><?php echo $SUD_DA['URI']; ?></a> for more information & updates about this plugin.
	</div>
	<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8609041" target="_blank" rel="nofollow"><img alt="PayPal" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" /></a></p><br/>
	
	<h3 style="font-size:120%">Options</h3>

	<p><b>Insert thumbnail Manually:</b><br/>By default, the thumbnail will be inserted into the position just before the content. However, Stumbleupon may fail to pick it up if the content is too low below the page. To solve this problem, you need to manually insert <b>&lt;?php insert_thumbnail(); ?&gt;</b> somewhere higher (probably at the bottom of 'header.php' of your theme).</p>
	<p><b>Set your default image thumbnail directory here:</b><br/>(This is the directory text populate into the textbox for new post, it will not affect your old posts if you change it here.)<br/><br/>
	<?php
		$defimgdr = get_option('Default_ImgDir');
		$defimgdr = (empty($defimgdr)) ? "http://" : $defimgdr;
		$defhasch = get_option('Default_HasCache');		
	?>
	<input style="width:80%" id="Default_ImgDir" name="Default_ImgDir" value="<?php echo $defimgdr; ?>" /></p>
	<p><input type="checkbox" id="Default_HasCache" name="Default_HasCache" <?php if($defhasch){echo('checked');} ?>> <b>Use javascript instead of php</b><br/>(Check this only when you have other cache plugin installed, such as 'WP Super Cache', etc.)</p>
	
	<input type="hidden" name="action" value="update" />
	<?php settings_fields('sud-options'); ?>
	
	<p class="submit">
		<input type="submit" value="<?php _e('Save Changes') ?>" />
	</p>
	
</form>

</div> 


