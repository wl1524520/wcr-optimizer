<?php
/*
Plugin Name: WCR-Optimizer
Plugin URI: https://github.com/wl1524520/wcr-basic
Description: 戊辰人博客的一些性能优化设置。
Version: 2019.07.04
Author: Wang Lu
Author URI: https://wanglu.info/about
*/

// define('WCR_BASIC_PLUGIN_URL', plugins_url('', __FILE__));
// define('WCR_BASIC_PLUGIN_DIR', plugin_dir_path(__FILE__));
// define('WCR_BASIC_PLUGIN_FILE', __FILE__);

define('WCR_BASIC_PLUGIN_DIR', dirname( __FILE__ ));

// 后台管理页面
require_once(WCR_BASIC_PLUGIN_DIR . '/admin/wcr-admin.php');

require_once(WCR_BASIC_PLUGIN_DIR . '/wcr-functions.php');    // 默认选项
require_once(WCR_BASIC_PLUGIN_DIR . '/wcr-mail.php');         // 邮件通知

$options = get_option('wcr_optimizer_settings');
if (isset($options['wcr_rest_enabled']) && $options['wcr_rest_enabled'] && !is_admin()) {
    require_once(WCR_BASIC_PLUGIN_DIR . '/wcr-rest.php');     // RESTFull API
}