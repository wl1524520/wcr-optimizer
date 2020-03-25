<?php

/**
 * 页面 REST 自定义字段
 */
function wcr_rest_prepare_page($data, $page, $request) {
    $_data = $data->data;
    $params = $request->get_params();

    $pure_page = [];
    $pure_page['id']        = $_data['id'];
    $pure_page['date']      = date('Y-m-d H:i:s', strtotime($_data['date']));
    $pure_page['title']     = $_data['title']['rendered'];
    $pure_page['excerpt']   = str_replace("\n", "", strip_tags($_data['excerpt']['rendered']) );
    if ( isset($params['id']) ) {
        $pure_page['content']   = $_data['content']['rendered'];
    }

    return $pure_page;
}
add_filter('rest_prepare_page', 'wcr_rest_prepare_page', 10, 3);

/**
 * 文章 REST 自定义字段
 */
function wcr_rest_prepare_post($data, $post, $request) {
    $_data = $data->data;
    $params = $request->get_params();

    $show_content = False;

    // 自定义请求字段，控制列表中是否显示文章内容
    if (isset($params['show_content']) && $params['show_content']) {
        $show_content = True;
    }

    $pure_post = [];
    // $pure_post['params']    = $params;
    $pure_post['id']        = $_data['id'];
    $pure_post['date']      = date('Y-m-d H:i:s', strtotime($_data['date']));
    $pure_post['modified']  = date('Y-m-d H:i:s', strtotime($_data['modified']));
    $pure_post['title']     = $_data['title']['rendered'];
    $pure_post['format']    = $_data['format'];
    // $pure_post['excerpt']   = str_replace("\n", "", strip_tags($_data['excerpt']['rendered']));
    $pure_post['author']    = get_the_author_meta('nickname', $_data['author']);

	// 以下是要添加的自定义字段
    // 字段：pusercode 多个产品编码，用英文逗号分隔
	$ptype_code = esc_html(get_post_meta($post->ID, '_pusercode', true));
    if ($ptype_code) {
        // 将中文逗号转换为英文逗号
        $ptype_code = str_replace("，", ",", $ptype_code);
	    $pure_post['ptype_code'] = explode(',', $ptype_code);
    }
	$ptype_price = esc_html(get_post_meta($post->ID, '_price', true));
    $pure_post['ptype_price'] = $ptype_price == '' ? 0 : $ptype_price;
	$pure_post['ptype_desc'] = esc_html(get_post_meta($post->ID, '_ptype_desc', true));

    if ( isset($params['id']) || $show_content ) {
        /*
        $categories = my_rest_get_post_terms( $_data['id'], 'category' );
        if ( ! empty( $categories ) ) {
            $_data['categories'] = $categories;
        }

        $tags = my_rest_get_post_terms( $_data['id'], 'post_tag' );
        if ( ! empty( $tags ) ) {
            $_data['tags'] = $tags;
        }
         */
        if ($pure_post['format'] == 'video') {
            // 视频类文章
            // 字段：url
            $video_url = esc_html(get_post_meta($post->ID, 'url', true));
            if ($video_url) {
                $pure_post['content'] = $video_url;
            } else {
                $pure_post['content'] = '';
            }
        } else {
            $pure_post['content']       = str_replace("\n", "", $_data['content']['rendered']);
        }
    }


    // get featured image id
    $featured_image_id = $_data['featured_media'];
    // get url of the original size
    $featured_image_url = wp_get_attachment_image_src($featured_image_id, 'original');
	if( $featured_image_url ) {
        $pure_post['thumb'] = $featured_image_url[0];
    } else {
        $pure_post['thumb'] = wpjam_get_post_first_image($_data['content']['rendered']);
    }

    $pure_post['categories']    = $_data['categories'];
    // $pure_post['tags']          = $_data['tags'];
    // $pure_post['meta']          = $_data['meta'];

	return $pure_post;
}
add_filter('rest_prepare_post', 'wcr_rest_prepare_post', 10, 3);

/**
 * 自定义评论 REST api
 */
function wcr_rest_prepare_comment($data, $comment, $request) {
    $_data = $data->data;
    $_data['date']      = date('Y-m-d H:i', strtotime($_data['date']));
    // $_data['content']   = str_replace("\n", "", strip_tags($_data['content']['rendered']) );
    $_data['content']   = $_data['content']['rendered'];
    $_data['avatar']    = $_data['author_avatar_urls']['48'];

    // 删除不需要的数据
    unset($_data['date_gmt']);
    unset($_data['author_url']);
    unset($_data['link']);
    unset($_data['status']);
    unset($_data['type']);
    unset($_data['meta']);

    return $_data;
}
add_filter('rest_prepare_comment', 'wcr_rest_prepare_comment', 10, 3);

/*
 增加 endpoint
function my_awesome_func( $data ) {
  $posts = get_posts( array(
    'author' => $data['id'],
  ) );
 
  if ( empty( $posts ) ) {
    return null;
  }
 
  return $posts[0]->post_title;
}
add_action( 'rest_api_init', function () {
  register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'my_awesome_func',
  ) );
} );
 */

