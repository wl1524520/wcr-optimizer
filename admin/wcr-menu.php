<?php

/**
* 顶级菜单回调函数
*/
function wcr_optimizer_top_page() {
    // 检查用户权限
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // add error/update messages
    
    // 检查用户是否提交保存设置
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 
            'wcr_optimizer_messages', 
            'wcr_optimizer_message', 
            __( '设置已保存', 'wcr_optimizer' ), 
            'updated' 
        );
    }
    
    // show error/update messages
    settings_errors( 'wcr_optimizer_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields('wcr_optimizer');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('wcr_optimizer');
            // output save settings button
            submit_button('保存设置');
            ?>
        </form>
    </div>
<?php
}

// 子菜单回调函数
function wcr_optimizer_about_page() {
    // 检查用户权限
    if ( ! current_user_can('manage_options') ) {
        return;
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <ol>
            <li>屏蔽头部加载 s.w.org</li>
            <li>去除 wp_title 前后的空白</li>
        </ol>
    </div>
<?php
}




