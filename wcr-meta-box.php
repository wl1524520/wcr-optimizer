<?php
/*
 * 默认启用的优化 
 */

//添加额外字段 产品编码
function add_custom_field_pusercode()
{
    add_meta_box('pusercode', '产品编码', 'custom_field_pusercode_html_callback', 'post', 'normal', 'core');
}
function custom_field_pusercode_html_callback($post) {
    wp_nonce_field('pusercode', 'pusercode_nonce');
    $value = get_post_meta($post->ID, '_pusercode', true);
    echo '<label for="pusercode">产品编码：</label>';
    echo '<input type="text" id="pusercode_input" name="pusercode" value="'.$value.'" size="50" />';
}
add_action('add_meta_boxes', 'add_custom_field_pusercode');

function pusercode_save_meta_box($post_id) {     
    // 安全检查     
    // 检查是否发送了一次性隐藏表单内容（判断是否为第三者模拟提交）     
    if ( ! isset($_POST['pusercode_nonce']) ) { return; }
    // 判断隐藏表单的值与之前是否相同     
    if ( ! wp_verify_nonce($_POST['pusercode_nonce'], 'pusercode') ) { return; }
    // 判断该用户是否有权限     
    if ( ! current_user_can('edit_post', $post_id) ) { return; }
    // 判断 Meta Box 是否为空     
    if ( ! isset($_POST['pusercode']) ) { return; }
    $pusercode = sanitize_text_field( $_POST['pusercode']  );
    update_post_meta( $post_id, '_pusercode', $pusercode); 
}
add_action('save_post', 'pusercode_save_meta_box');

//添加额外字段 商品价格
function add_custom_field_price()
{
    add_meta_box('price', '商品价格', 'custom_field_price_html_callback', 'post', 'normal', 'core');
}
function custom_field_price_html_callback($post) {
    wp_nonce_field('price', 'price_nonce');
    $value = get_post_meta($post->ID, '_price', true);
    echo '<label for="price">商品价格：</label>';
    echo '<input type="text" id="price_input" name="price" value="'.$value.'" size="50" />';
}
add_action('add_meta_boxes', 'add_custom_field_price');

function price_save_meta_box($post_id) {     
    // 安全检查     
    // 检查是否发送了一次性隐藏表单内容（判断是否为第三者模拟提交）     
    if ( ! isset($_POST['price_nonce']) ) { return; }
    // 判断隐藏表单的值与之前是否相同     
    if ( ! wp_verify_nonce($_POST['price_nonce'], 'price') ) { return; }
    // 判断该用户是否有权限     
    if ( ! current_user_can('edit_post', $post_id) ) { return; }
    // 判断 Meta Box 是否为空     
    if ( ! isset($_POST['price']) ) { return; }
    $price = sanitize_text_field( $_POST['price']  );
    update_post_meta( $post_id, '_price', $price); 
}
add_action('save_post', 'price_save_meta_box');

//添加额外字段 商品参数
function add_custom_field_ptype_desc()
{
    add_meta_box('ptype_desc', '商品参数', 'custom_field_ptype_desc_html_callback', 'post', 'normal', 'core');
}
function custom_field_ptype_desc_html_callback($post) {
    wp_nonce_field('ptype_desc', 'ptype_desc_nonce');
    $value = get_post_meta($post->ID, '_ptype_desc', true);
    echo '<textarea style="width:100%;" rows="10" id="ptype_desc_input" name="ptype_desc" >'.$value.'</textarea>';
}
add_action('add_meta_boxes', 'add_custom_field_ptype_desc');

function ptype_desc_save_meta_box($post_id) {     
    // 安全检查     
    // 检查是否发送了一次性隐藏表单内容（判断是否为第三者模拟提交）     
    if ( ! isset($_POST['ptype_desc_nonce']) ) { return; }
    // 判断隐藏表单的值与之前是否相同     
    if ( ! wp_verify_nonce($_POST['ptype_desc_nonce'], 'ptype_desc') ) { return; }
    // 判断该用户是否有权限     
    if ( ! current_user_can('edit_post', $post_id) ) { return; }
    // 判断 Meta Box 是否为空     
    if ( ! isset($_POST['ptype_desc']) ) { return; }
    $ptype_desc = sanitize_text_field( $_POST['ptype_desc']  );
    update_post_meta( $post_id, '_ptype_desc', $ptype_desc); 
}
add_action('save_post', 'ptype_desc_save_meta_box');
