<?php

/*
Plugin Name: Cool Coming Soon
Plugin URI: http://www.AtlasGondal.com/
Description: Simple, Super Cool Coming Soon and Maintenance plugin with date and time countdown. It's fully customizable and provides maximum display controls.
Version: 2.2
Author: Atlas Gondal
Author URI: http://www.AtlasGondal.com/
License: GPL v2 or higher
License URI: License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH'))
    exit();

define('CCS_VERSION', '2.2'); // Plugin Version Number

function cool_coming_soon_nav()
{

    add_options_page('Cool Coming Soon', 'Cool Coming Soon', 'manage_options', 'cool-coming-soon-settings', 'include_cool_coming_soon_settings_page');
}


add_action('admin_menu', 'cool_coming_soon_nav');

class cool_coming_soon_default_data
{
    static function install()
    {
        if (!isset($ccs_default_data)) {
            $ccs_default_data = new stdClass();
        }
        $ccs_default_data->name                         = 'Cool Coming Soon';
        $ccs_default_data->maintenance_mode             = 1;
        $ccs_default_data->bg_options                   = 'bg9.jpg';
        $ccs_default_data->background_url               = '';
        $ccs_default_data->logo_url                     = plugins_url('inc/assets/img/logo.png', __FILE__);
        $ccs_default_data->logo_id                      = -1;
        $ccs_default_data->page_title                   = 'Coming Soon';
        $ccs_default_data->heading                      = 'Coming Soon';
        $ccs_default_data->description                  = 'we are getting ready for a big launch!!!!';
        $ccs_default_data->date                         = date("Y-m-d", strtotime("+4 week"));
        $ccs_default_data->time                         = "00:00";

        if (!isset($ccs_default_display_data)) {
            $ccs_default_display_data = new stdClass();
        }
        $ccs_default_display_data->display_background   = 'Yes';
        $ccs_default_display_data->display_logo         = 'Yes';
        $ccs_default_display_data->display_title        = 'Yes';
        $ccs_default_display_data->display_description  = 'Yes';
        $ccs_default_display_data->display_date         = 'Yes';


        add_option('cool_coming_soon_data', $ccs_default_data);
        add_option('cool_coming_soon_display', $ccs_default_display_data);
    }
}
register_activation_hook(__FILE__, array('cool_coming_soon_default_data', 'install'));

class cool_coming_soon_default_data_delete
{
    static function uninstall()
    {
        delete_option('cool_coming_soon_data');
        delete_option('cool_coming_soon_display');
    }
}
register_deactivation_hook(__FILE__, array('cool_coming_soon_default_data_delete', 'uninstall'));

function include_cool_coming_soon_settings_page()
{

    include(plugin_dir_path(__FILE__) . 'cool-coming-soon-settings.php');
}

function cool_coming_soon_on_activate()
{
    set_transient('ccs_cool_coming_soon_activation_redirect', true, 30);
}

register_activation_hook(__FILE__, 'cool_coming_soon_on_activate');

function cool_coming_soon_activation()
{

    if (!get_transient('ccs_cool_coming_soon_activation_redirect')) {
        return;
    }

    delete_transient('ccs_cool_coming_soon_activation_redirect');

    wp_safe_redirect(add_query_arg(array('page' => 'cool-coming-soon-settings'), admin_url('options-general.php')));
}
add_action('admin_init', 'cool_coming_soon_activation');

function ccs_maintenance_template_redirect()
{

    $cool_coming_soon_data = get_option('cool_coming_soon_data');

    if (!is_user_logged_in()) {
        if ($cool_coming_soon_data->maintenance_mode == 1) {

            $auto_launch = $cool_coming_soon_data->auto_launch == 1 ?? 0;

            $launchDateTimeString = $cool_coming_soon_data->date . ' ' . $cool_coming_soon_data->time;
            $launchTimestamp = strtotime($launchDateTimeString);

            $currentTimestamp = time();

            if ($currentTimestamp > $launchTimestamp && $auto_launch) {
                $cool_coming_soon_data->maintenance_mode = 0;
                $cool_coming_soon_data->auto_launch = 0;
                update_option('cool_coming_soon_data', $cool_coming_soon_data);
            } else {
                $coming_soon_file = plugin_dir_path(__FILE__) . '/inc/index.php';
                include($coming_soon_file);
                exit();
            }
        }
    }
}
add_action('template_redirect', 'ccs_maintenance_template_redirect');

function enqueue_ccs_scripts()
{

    $screen = get_current_screen();

    if ($screen->id != 'settings_page_cool-coming-soon-settings') {
        return;
    }

    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }

    wp_enqueue_script('ccs_upload_script', plugin_dir_url(__FILE__) . 'inc/assets/js/ccs_scripts.js', array('jquery'), null, false);
}

add_action('admin_enqueue_scripts', 'enqueue_ccs_scripts');

add_filter('admin_footer_text', 'ccs_admin_footer_text');
function ccs_admin_footer_text($footer_text)
{

    $current_screen = get_current_screen();

    $is_cool_coming_soon_screen = ($current_screen && false !== strpos($current_screen->id, 'cool-coming-soon-settings'));

    if ($is_cool_coming_soon_screen) {
        $footer_text = 'Enjoyed <strong>Cool Coming Soon</strong>? Please leave us a <a href="https://wordpress.org/support/plugin/cool-coming-soon/reviews/?filter=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. We really appreciate your support! ';
    }

    return $footer_text;
}
