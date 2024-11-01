<?php
/*
 * Plugin Name: shortcode for My Mitsu Estimation Form
 * Plugin URI: https://my-mitsu.com/blog/wordpress-embed
 * Description: You can embed an estimation(calculation) form by filling in a shortcode. An estimation form is provided by a webservice in Japan called My Mitsu.
 * Version: 1.3
 * Author: Fumito MIZUNO
 * Text Domain: shortcode-for-my-mitsu-estimation-form
 * Domain Path: /languages
 * Author URI: https://my-mitsu.com/
 * License: GPL ver.2 or later
 */

function my_mitsu_shortcode_load_plugin_textdomain() {
    load_plugin_textdomain( 'shortcode-for-my-mitsu-estimation-form', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_mitsu_shortcode_load_plugin_textdomain' );

function mymitsu_function( $atts, $content = NULL ) {
    // URL for My Mitsu, a webservice for creating estimation forms.
    $mymitsuurl = 'https://my-mitsu.jp/estimation/';

    $default_atts = apply_filters( 'mymitsu_default_atts', array(
        'id' => 'mymitsu',
        'width' => 640,
        'height' => 480
    ));

    $atts = shortcode_atts( $default_atts, $atts, 'mymitsu' );

    // Default Url which shows a sample form for My Mitsu.
    $url = apply_filters( 'mymitsu_default_url', 'https://my-mitsu.jp/estimation/274' );

    // check if $content is valid url or not.
    if ( filter_var( $content, FILTER_VALIDATE_URL )) {
        $url = $content;
    } elseif (!empty( $content ) && filter_var( $mymitsuurl . ltrim( $content, '/' ), FILTER_VALIDATE_URL )) {
        $url = $mymitsuurl . ltrim($content, '/');
    } else {
        // Do something when $content is either invalid or empty.
        do_action( 'mymitsu_invalid_content', $content );
    }

    // outputs iframe
    $format = '<iframe src="%s" id="%s" width="%d" height="%d"></iframe>';

    return sprintf( $format,
        esc_url( $url ),
        sanitize_html_class( $atts['id'], 'mymitsu' ),
        intval( $atts['width'] ),
        intval( $atts['height'] )
    );
}
add_shortcode( 'mymitsu', 'mymitsu_function' );


function sv_plugin_admin_page() {
    add_submenu_page('plugins.php','Estimation Form', __('Estimation Form', 'shortcode-for-my-mitsu-estimation-form'), 'manage_options', 'shortcode-my-mitsu', 'shortcode_my_mitsu');
}
add_action( 'admin_menu', 'sv_plugin_admin_page' );

function shortcode_my_mitsu() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
?>
    <div class="wrap">
        <h1><?php _e('Estimation Form' ,'shortcode-for-my-mitsu-estimation-form');?></h1>
        <p><?php _e( 'My Mitsu, is a webservice in Japan, allows users to create an estimation(calculation) form.', 'shortcode-for-my-mitsu-estimation-form');?>
           <?php _e( 'A powerful form allows you to create a conditional form with calculation, and outputs a PDF file.', 'shortcode-for-my-mitsu-estimation-form');?>
           <?php _e( 'It is suited for business persons.', 'shortcode-for-my-mitsu-estimation-form');?>
           <a href="https://my-mitsu.com/">https://my-mitsu.com/</a> <?php _e( 'written in Japanese', 'shortcode-for-my-mitsu-estimation-form');?>
        </p>
        <p><?php _e( 'This plugin allows you to output an iframe html tag in a simple way. Simply filling in a shortcode will ouput an iframe html code.', 'shortcode-for-my-mitsu-estimation-form');?></p>
        <p><?php _e( '[mymitsu]274[/mymitsu] will output &lt;iframe src="https://my-mitsu.jp/estimation/274" id="mymitsu" width="640" height="480"&gt;&lt;/iframe&gt;', 'shortcode-for-my-mitsu-estimation-form');?></p>
        <p><?php _e( 'Optionally, a shortcode accepts an ID, width, and height as attributes.', 'shortcode-for-my-mitsu-estimation-form');?></p>
        <p><?php _e( '[mymitsu id="myform" width="800" height="600"]274[/mymitsu] will output &lt;iframe src="https://my-mitsu.jp/estimation/274" id="myform" width="800" height="600"&gt;&lt;/iframe&gt;', 'shortcode-for-my-mitsu-estimation-form');?></p>
        <p><?php _e( '* Note * In order to create an estimation form, you need to register My Mitsu.', 'shortcode-for-my-mitsu-estimation-form');?><a href="https://my-mitsu.jp/">https://my-mitsu.jp/</a></p>
    <h2><?php _e('Estimation Form Example' ,'shortcode-for-my-mitsu-estimation-form');?></h2>
        <iframe src="https://my-mitsu.jp/estimation/274" id="mymitsu" width="800" height="800"></iframe>
        <h2><?php _e('How to set up your Estimation Forms' ,'shortcode-for-my-mitsu-estimation-form');?></h2>
        <p><?php _e('You can find some example forms and settings.' ,'shortcode-for-my-mitsu-estimation-form');?><a href="https://my-mitsu.com/sample/cloud-server"><?php _e('Estimation form example for a cloud-server price' ,'shortcode-for-my-mitsu-estimation-form');?></a></p>
    </div>
<?php
}
