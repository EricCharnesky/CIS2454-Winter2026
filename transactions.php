<?php

try {
    require_once 'models/database.php';
    require_once 'models/transactions.php';
    require_once 'models/users.php';
    require_once 'models/stocks.php';

    $action = htmlspecialchars(filter_input(INPUT_POST, "action"));

    $id = htmlspecialchars(filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT));
    $user_id = htmlspecialchars(filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT));
    $stock_symbol = htmlspecialchars(filter_input(INPUT_POST, "stock_symbol"));
    $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_FLOAT);
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);

    if ($action == "insert" && $user_id != "0" && $stock_symbol != "" && $quantity != 0) {

        $user = get_user($user_id);
        $user_name = $user->get_name();
        $user_balance = $user->get_balance();
       
        $stock = get_stock($stock_symbol);
        $stock_price = $stock->get_price();

        $total_cost = $stock_price * $quantity;

        if ($user_balance >= $total_cost) {
            $transaction = new Transaction(0, $user_id, $stock_symbol, $quantity, $stock_price, "");
            insert_transaction($transaction);
            $new_balance = $user_balance - $total_cost;
            $user->set_balance($new_balance);
            update_user($user);
            header("Location: transactions.php");
        } else {
            echo $error_message = "insufficent funds to purchase stocks";
            include('views/error.php');
        }
    } else if ($action == "update" && $user_id != "0" && $stock_symbol != "" && $quantity != 0 && $price != 0 && $id != 0) {
        $transaction = new Transaction($id, $user_id, $stock_symbol, $quantity, $price, "");
        update_transaction($transaction);
    } else if ($action == "delete" && $id != 0) {


        $transaction = get_transaction($id);

        $stock_symbol = $transaction->get_stock_symbol();
        $user_id = $transaction->get_user_id();
        $quantity = $transaction->get_quantity();
        

        $stock = get_stock($stock_symbol);
        $stock_price = $stock->get_price();
        
        $user = get_user($user_id);
        $user_name = $user->get_name();
        $user_balance = $user->get_balance();
    
        $total_sale = $stock_price * $quantity;
        $new_balance = $user_balance + $total_sale;
        
        $user->set_balance($new_balance);
        update_user($user);

        delete_transaction($id);

        header("Location: transactions.php");
    } else if ($action != "") {
        $error_message = "Missing stock_id, user_id, or quantity";
        include('views/error.php');
    }


    $transactions = list_transactions();

    include('views/transactions.php');
} catch (Exception $e) {
    $error_message = $e->getMessage();
    include('views/error.php');
}
?>