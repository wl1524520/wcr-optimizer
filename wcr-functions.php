<?php
/*
 * 默认启用的优化 
 */
// 屏蔽头部加载 s.w.org
add_filter( 'emoji_svg_url', '__return_false' );
// 去除 wp_title 前后的空白
add_filter('wp_title', 'trim');

/*
* 根据后台设置进行优化
*/
$options = get_option('wcr_optimizer_settings');

// 显示页面性能参数
function wcr_performance( $visible = false ) {
    $stat = get_num_queries() . ' queries in ' . timer_stop(0, 6) . ' seconds';
    $stat .= sprintf(', using %.3fMB memory', memory_get_peak_usage() / 1024 / 1024);
    echo $visible ? $stat : "<!-- {$stat} -->\n" ;
}

// 去除more的位置跳转
if (isset($options['remove_more_jump_link']) && $options['remove_more_jump_link']) {
	function remove_more_jump_link($link) {
		$offset = strpos($link, '#more-');
		if ($offset) {
			$end = strpos($link, '"',$offset);
		}
		if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}
	add_filter('the_content_more_link', 'remove_more_jump_link');
}

// 移除js和css的版本参数
if( isset($options['wcr_remove_jscss_version']) && $options['wcr_remove_jscss_version'] ){
	add_filter('script_loader_src', 'wcr_remove_jscss_version', 999);
	add_filter('style_loader_src', 'wcr_remove_jscss_version', 999);
	function wcr_remove_jscss_version($src) {
	    return remove_query_arg(array('ver', 'version'), $src);
	}
}

// MCE 界面增加分页按钮
if (isset($options['add_next_page_button']) && $options['add_next_page_button']) {
	add_filter( 'mce_buttons', 'wcr_add_next_page_button', 1, 2 ); // 1st row
	function wcr_add_next_page_button( $buttons, $id ){
	 
		/* only add this for content editor */
		if ( 'content' != $id )
			return $buttons;
	 
		/* add next page after more tag button */
		array_splice( $buttons, 13, 0, 'wp_page' );
	 
		return $buttons;
	}
}

// 移除包裹在<img>标签上的<p>标签
if( isset($options['wcr_remove_out_p_for_img']) && $options['wcr_remove_out_p_for_img'] ){
	add_filter('the_content', 'wpwcr_content');
	function wpwcr_content($content) {
	    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
}

// 禁用评论中自动将url转换为链接
if (isset($options['disable_comment_auto_url']) && $options['disable_comment_auto_url']) {
	remove_filter('comment_text', 'make_clickable', 9);
}

/* remove br tags */
/*
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

function wpse_wpautop_nobr( $content ) {
    return wpautop( $content, false );
}

add_filter( 'the_content', 'wpse_wpautop_nobr' );
add_filter( 'the_excerpt', 'wpse_wpautop_nobr' );
 */

// 修改上传图片文件名
if( false ){
    add_filter('wp_handle_upload_prefilter', 'wcr_upload_filter');
    function wcr_upload_filter($file) {
        $info   = pathinfo($file['name']);
        $ext    = $info['extension'];
        $file['name'] = date('YmdHis').rand(10,99) . '.' . $ext;
        return $file;
    }
}

// 添加链接管理器
/*
if( true ){
    add_filter('pre_option_link_manager_enabled','__return_true');
}
 */

// 移除系统自动添加的 .recentcomments 样式
if( isset($options['remove_recent_comment_style']) && $options['remove_recent_comment_style'] ){
	function wpwcr_remove_recent_comment_style() {
		global $wp_widget_factory;
		remove_action(
	            'wp_head',
	            array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' )
	        );
	}
	add_action( 'widgets_init', 'wpwcr_remove_recent_comment_style' );
}

// 去掉图片中 width 和 height 属性
if (isset($options['remove_width_height_attribute']) && $options['remove_width_height_attribute']) {
	add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
	add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
	function remove_width_attribute( $html ) {
   		$html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   		return $html;
	}
}

//remove_action( 'admin_init', 'register_admin_color_schemes', 1);
//remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

// 修改 WordPress Admin text
/*
add_filter('admin_footer_text', 'wpwcr_modify_admin_footer_text');
function wpwcr_modify_admin_footer_text ($text) {
	return $text .' | <a href="https://wanglu.info/" title="戊辰人博客" target="_blank">戊辰人博客</a>';
}
 */


// 给页面添加摘要
/*
add_action( 'admin_menu', 'wpwcr_page_excerpt_meta_box' );
function wpwcr_page_excerpt_meta_box() {
	add_meta_box( 'postexcerpt', __('Excerpt'), 'post_excerpt_meta_box', 'page', 'normal', 'core' );
}
*/

// Clean the up the image from wp_get_attachment_image()
add_filter('wp_get_attachment_image_attributes', 'bea_remove_srcset', PHP_INT_MAX, 1);
function bea_remove_srcset( $attr ) {
	if ( class_exists( 'BEA_Images' ) ) {
		return $attr;
	}
	if ( isset( $attr['sizes'] ) ) {
		unset( $attr['sizes'] );
	}
	if ( isset( $attr['srcset'] ) ) {
		unset( $attr['srcset'] );
	}
	return $attr;
}

// Override the calculated image sizes
add_filter('wp_calculate_image_sizes', '__return_false', PHP_INT_MAX);
// Override the calculated image sources
add_filter('wp_calculate_image_srcset', '__return_false', PHP_INT_MAX);
// Remove the reponsive stuff from the content
remove_filter('the_content', 'wp_make_content_images_responsive');


