<?php

require_once(WCR_BASIC_PLUGIN_DIR . '/admin/wcr-menu.php');
require_once(WCR_BASIC_PLUGIN_DIR . '/admin/wcr-options.php');

/**
* 顶级菜单
*/
function wcr_options_page() {
    // add top level menu page
    add_menu_page(
        '戊辰人性能优化',
        'WCR-Optimizer',
        'manage_options', // 权限
        'wcr_optimizer', // menu slug
        'wcr_optimizer_top_page', // 回调函数
        'dashicons-dashboard' // 图标
    );
    add_submenu_page(
        'wcr_optimizer', // 父级菜单的 menu slug
        '其它默认优化说明',
        '插件说明',
        'manage_options',
        'wcr_optimizer_about',
        'wcr_optimizer_about_page'
    );
}
/**
* 注册顶级菜单
*/
add_action('admin_menu', 'wcr_options_page');

/**
 * 自定义选项和设置
 */
function wcr_settings_init() {
    // 为 wporg 页面注册一个设置项
    register_setting('wcr_optimizer', 'wcr_optimizer_settings');
    
    // 在 wporg 页面中添加一个区域（SECTION）
    add_settings_section(
        'wcr_section_basic', // SECTION 的唯一 ID，$args['id']
        __('区域（SECTION）标题', 'wcr_optimizer'), // 标题
        'wcr_section_basic_callback', // 回调函数
        'wcr_optimizer' // 属于哪个页面
    );
    
    // register a new field in the "wporg_section_developers" section, inside the "wporg" page
    add_settings_field(
        'remove_more_jump_link', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( 'more 跳转链接', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'remove_more_jump_link',
            'class' => 'wcr_row',
            'wcr_custom_data' => '去除 more 的位置跳转链接',
        ]
    );
    add_settings_field(
        'wcr_remove_jscss_version', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( '去除js/css版本', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'wcr_remove_jscss_version',
            'class' => 'wcr_row',
            'wcr_custom_data' => '移除js和css的版本参数',
        ]
    );
    add_settings_field(
        'wcr_remove_out_p_for_img', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( '文章内容优化', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'wcr_remove_out_p_for_img',
            'class' => 'wcr_row',
            'wcr_custom_data' => '移除包裹在<img>标签上的<p>标签',
        ]
    );
    add_settings_field(
        'disable_comment_auto_url', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( '禁用评论链接', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'disable_comment_auto_url',
            'class' => 'wcr_row',
            'wcr_custom_data' => '禁用评论中自动将url转换为链接',
        ]
    );
    add_settings_field(
        'remove_recent_comment_style', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( '评论样式优化', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'remove_recent_comment_style',
            'class' => 'wcr_row',
            'wcr_custom_data' => '移除系统自动添加的 .recentcomments 评论样式',
        ]
    );
    add_settings_field(
        'remove_width_height_attribute', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( '文章图片属性优化', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'remove_width_height_attribute',
            'class' => 'wcr_row',
            'wcr_custom_data' => '移除文章图片 width 和 height 属性',
        ]
    );
    add_settings_field(
        'add_next_page_button', // 添加字段ID
        // use $args' label_for to populate the id inside the callback
        __( 'MCE编辑器优化', 'wcr_optimizer' ), // 标题 字段显示名
        'wcr_optimizer_options_custom_callback', // 回调函数
        'wcr_optimizer', // 属于哪个页面
        'wcr_section_basic', // 对应 add_settings_section 中的 ID
        [
            'label_for' => 'add_next_page_button',
            'class' => 'wcr_row',
            'wcr_custom_data' => 'MCE 界面增加分页按钮',
        ]
    );
}
    
/**
* 在 admin_init 中注入 wcr_settings_init 函数
*/
add_action('admin_init', 'wcr_settings_init');
