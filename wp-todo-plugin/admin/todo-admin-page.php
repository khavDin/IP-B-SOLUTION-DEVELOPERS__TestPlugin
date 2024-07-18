<?php
function todo_admin_page() {
    if (isset($_POST['sync_todos'])) {
        Todo_Sync::sync_todos();
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