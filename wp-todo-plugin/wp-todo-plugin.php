<?php
/*
Plugin Name: WP Todo Plugin
Description: A WordPress plugin to synchronize and display todos from an external API.
Version: 1.0
Author: Diana K.
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include required files
include_once plugin_dir_path(__FILE__) . 'includes/class-todo-sync.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-todo-search.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-todo-shortcode.php';
include_once plugin_dir_path(__FILE__) . 'includes/class-todo-logger.php';
include_once plugin_dir_path(__FILE__) . 'admin/todo-admin-page.php';

// Register activation hook
register_activation_hook(__FILE__, array('Todo_Sync', 'create_todo_table'));

// Initialize the plugin
function wp_todo_plugin_init() {
    Todo_Sync::init();
    Todo_Search::init();
    Todo_Shortcode::init();
}
add_action('plugins_loaded', 'wp_todo_plugin_init');