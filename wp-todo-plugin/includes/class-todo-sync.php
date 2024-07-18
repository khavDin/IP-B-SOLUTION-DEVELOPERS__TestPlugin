<?php
class Todo_Sync {

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_page'));
    }

    public static function create_todo_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'todos';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id mediumint(9) NOT NULL,
            title text NOT NULL,
            completed tinyint(1) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function sync_todos() {
        $response = wp_remote_get('https://jsonplaceholder.typicode.com/todos');
        if (is_wp_error($response)) {
            Todo_Logger::log('Error fetching data from API: ' . $response->get_error_message());
            return;
        }

        $todos = json_decode(wp_remote_retrieve_body($response));
        if (!$todos) {
            Todo_Logger::log('Error decoding JSON response');
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'todos';

        foreach ($todos as $todo) {
            $wpdb->insert($table_name, array(
                'id' => $todo->id,
                'user_id' => $todo->userId,
                'title' => $todo->title,
                'completed' => $todo->completed
            ));
        }

        Todo_Logger::log('Successfully synced todos.');
    }

    public static function add_admin_page() {
        add_menu_page(
            'Todo Sync',
            'Todo Sync',
            'manage_options',
            'todo-sync',
            array(__CLASS__, 'render_admin_page')
        );
    }

    public static function render_admin_page() {
        if (isset($_POST['sync_todos'])) {
            self::sync_todos();
            echo '<div class="updated"><p>Todos synchronized successfully!</p></div>';
        }
        ?>
        <div class="wrap">
            <h1>Todo Sync</h1>
            <form method="post">
                <input type="hidden" name="sync_todos" value="1">
                <?php submit_button('Sync Todos'); ?>
            </form>
        </div>
        <?php
    }
}