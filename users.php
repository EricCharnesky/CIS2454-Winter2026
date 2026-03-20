<?php

try {
    require_once 'models/database.php';
    require_once 'models/users.php';

    $action = htmlspecialchars(filter_input(INPUT_POST, "action"));

    $name = htmlspecialchars(filter_input(INPUT_POST, "name"));
    $balance = filter_input(INPUT_POST, "balance", FILTER_VALIDATE_FLOAT);
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($action == "insert_or_update" && $name != "" && $balance != 0) {
        $insert_or_update = filter_input(INPUT_POST, 'insert_or_update');
        $user = new User($name, $balance, $id);
        if ($insert_or_update == "insert") {
            insert_user($user);
        } else if ($insert_or_update == "update") {
            update_user($user);
        }

        header("Location: users.php");
    } else if ($action == "delete" && $id != 0) {
        delete_user($id);
        header("Location: users.php");
    } else if ($action != "") {
        $error_message = "Missing name, or cash balance";
        include('views/error.php');
    }


    $users = list_users();

    include('views/users.php');
} catch (Exception $e) {
    $error_message = $e->getMessage();
    include('views/error.php');
}
?>