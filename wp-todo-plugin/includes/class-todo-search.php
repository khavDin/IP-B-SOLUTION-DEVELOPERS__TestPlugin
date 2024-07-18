<?php
class Todo_Search {

    public static function init() {
        add_shortcode('todo_search', array(__CLASS__, 'render_search_form'));
        add_action('wp_ajax_todo_search', array(__CLASS__, 'handle_search_request'));
        add_action('wp_ajax_nopriv_todo_search', array(__CLASS__, 'handle_search_request'));
    }

    public static function render_search_form() {
        ob_start();
        ?>
        <form id="todo-search-form">
            <input type="text" name="title" placeholder="Search Todos...">
            <button type="submit">Search</button>
        </form>
        <div id="todo-search-results"></div>
        <script>
            jQuery(document).ready(function($) {
                $('#todo-search-form').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'todo_search',
                            title: $('input[name="title"]').val()
                        },
                        success: function(response) {
                            $('#todo-search-results').html(response);
                        }
                    });
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public static function handle_search_request() {
        global $wpdb;
        $title = sanitize_text_field($_POST['title']);
        $table_name = $wpdb->prefix . 'todos';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE title LIKE %s",
            '%' . $wpdb->esc_like($title) . '%'
        ));

        if ($results) {
            foreach ($results as $result) {
                echo '<p>' . esc_html($result->title) . '</p>';
            }
        } else {
            echo '<p>No todos found.</p>';
        }

        wp_die();
    }
}