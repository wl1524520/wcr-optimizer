<?php

/**
* custom option and settings:
* callback functions
*/

// developers section cb

// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function wcr_section_basic_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( '添加的新区域', 'wporg' ); ?></p>
    <?php
}
    
// pill field cb

// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function wcr_optimizer_options_custom_callback($args) {
    // get the value of the setting we've registered with register_setting()
    $options = get_option('wcr_optimizer_settings');
    // output the field
    ?>

    <input type="checkbox" value="1" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>"
        data-custom="<?php echo esc_attr( $args['wcr_custom_data'] ); ?>"
        <?php echo isset( $options[ $args['label_for'] ] ) ? ( 'checked="checked"' ) : ""; ?>
        name="wcr_optimizer_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
        
    /> <?php echo esc_attr( $args['wcr_custom_data'] ); ?>

    <?php
}