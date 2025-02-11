<?php
/*
Plugin Name: My React Localization Plugin
Description: A simple WordPress plugin demonstrating React frontend with PHP backend and localization.
Version: 1.0
Author: Your Name
Text Domain: my-react-localization-plugin
Domain Path: /languages
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register Admin Menu
function my_react_plugin_menu() {
    add_menu_page(
        __('React Localization Example', 'my-react-localization-plugin'), 
        'React Localization', 
        'manage_options', 
        'my_react_plugin', 
        'my_react_plugin_page',
        'dashicons-admin-generic', 
        6
    );
}
add_action('admin_menu', 'my_react_plugin_menu');

// Admin Page Content (HTML)
function my_react_plugin_page() {
    ?>
    <div id="my-react-plugin-root"></div>
    <?php
}

// Load plugin's localization files
function my_react_plugin_load_textdomain() {
    load_plugin_textdomain('my-react-localization-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'my_react_plugin_load_textdomain');



// Enqueue React App and Localize Script for Data Passing
function my_react_plugin_enqueue_scripts($hook) {
    // Only enqueue on the plugin's admin page
    if ('toplevel_page_my_react_plugin' !== $hook) {
        return;
    }
    wp_enqueue_script('wp-i18n');


    $plugin_url = plugin_dir_url(__FILE__);

    wp_set_script_translations('my-react-plugin-js', 'my-react-localization-plugin', dirname(plugin_basename(__FILE__)) . '/languages');    

    // Enqueue React JavaScript and CSS
    wp_enqueue_script('my-react-plugin-js', $plugin_url . 'react-src/build/static/js/main.c937dffe.js', array('wp-i18n'), '1.0', true);
    // wp_enqueue_style('my-react-plugin-css', $plugin_url . 'react-src/build/static/css/main.e6c13ad2.css');



    // Localize script to pass data to React
    wp_localize_script('my-react-plugin-js', 'myPluginData', array(
        'admin_message' => __('This is a static message from PHP backend!', 'my-react-localization-plugin'),
        'react_message' => __('This is a static message from React frontend!', 'my-react-localization-plugin'),
    ));
}
add_action('admin_enqueue_scripts', 'my_react_plugin_enqueue_scripts');

