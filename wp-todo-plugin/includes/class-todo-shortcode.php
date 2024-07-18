<?php
class Todo_Shortcode {

    public static function init() {
        add_shortcode('random_todos', array(__CLASS__, 'render_random_todos'));
    }

    public static function render_random_todos() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'todos';

        $results = $wpdb->get_results(
            "SELECT * FROM $table_name WHERE completed = 0 ORDER BY RAND() LIMIT 5"
        );

        if ($results) {
            $output = '<ul>';
            foreach ($results as $result) {
                $output .= '<li>' . esc_html($result->title) . '</li>';
            }
            $output .= '</ul>';
            return $output;
        } else {
            return '<p>No todos found.</p>';
        }
    }
}