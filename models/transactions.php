<?php

class Transaction{
    
    private $id, $user_id, $stock_symbol, $quantity, $price, $timestamp;
    
    // chatgpt: write php class get and set methods for above
    // chatgpt: constructor-based initialization
    public function __construct($id, $user_id, $stock_symbol, $quantity, $price, $timestamp) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->stock_symbol = $stock_symbol;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->timestamp = $timestamp;
    }
    
    public function get_id() {
        return $this->id;
    }

    public function get_user_id() {
        return $this->user_id;
    }

    public function get_stock_symbol() {
        return $this->stock_symbol;
    }

    public function get_quantity() {
        return $this->quantity;
    }

    public function get_price() {
        return $this->price;
    }

    public function get_timestamp() {
        return $this->timestamp;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_user_id($user_id) {
        $this->user_id = $user_id;
    }

    public function set_stock_symbol($stock_symbol) {
        $this->stock_symbol = $stock_symbol;
    }

    public function set_quantity($quantity) {
        $this->quantity = $quantity;
    }

    public function set_price($price) {
        $this->price = $price;
    }

    public function set_timestamp($timestamp) {
        $this->timestamp = $timestamp;
    }
    
}


function get_transaction($id) {
    global $database;

    $query = 'SELECT id, user_id, stock_symbol, quantity, price, timestamp'
            . ' FROM transactions WHERE id = :id';

    // prepare the query please
    $statement = $database->prepare($query);
    $statement->bindValue(":id", $id);

    // run the query please
    $statement->execute();

    $transaction = $statement->fetch();

    $statement->closeCursor();
    
    
    return new Transaction(
                $transaction['id'], 
                $transaction['user_id'],
                $transaction['stock_symbol'],
                $transaction['quantity'],
                $transaction['price'],
                $transaction['timestamp']);

}

function list_transactions() {
    global $database;

    $query = 'SELECT id, user_id, stock_symbol, quantity, price, timestamp'
            . ' FROM transactions';

    // prepare the query please
    $statement = $database->prepare($query);

    // run the query please
    $statement->execute();

    // this might be risky if you have HUGE amounts of data
    $transactions = $statement->fetchAll();

    $statement->closeCursor();
    
    $transactions_array = array();
    
    foreach ($transactions as $transaction){
        $transactions_array[] = new Transaction(
                $transaction['id'], 
                $transaction['user_id'],
                $transaction['stock_symbol'],
                $transaction['quantity'],
                $transaction['price'],
                $transaction['timestamp']);
    }

    return $transactions_array;
}

function insert_transaction($transaction) {
    global $database;

    $query = "INSERT INTO transactions (user_id, stock_symbol, quantity, price ) "
            . "VALUES (:user_id, :stock_symbol, :quantity, :price )";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":user_id",$transaction->get_user_id());
    $statement->bindValue(":stock_symbol",$transaction->get_stock_symbol());
    $statement->bindValue(":quantity", $transaction->get_quantity());
    $statement->bindValue(":price", $transaction->get_price());

    $statement->execute();

    $statement->closeCursor();
}

function update_transaction($transaction) {
    global $database;

    $query = "update transactions set user_id = :user_id, "
            . " stock_symbol = :stock_symbol, "
            . " quantity = :quantity, "
            . " price = :price "
            . " where id = :id";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":user_id", $transaction->get_user_id());
    $statement->bindValue(":stock_symbol", $transaction->get_stock_symbol());
    $statement->bindValue(":quantity", $transaction->get_quantity());
    $statement->bindValue(":price", $transaction->get_price());
    $statement->bindValue(":id", $transaction->get_id());

    $statement->execute();

    $statement->closeCursor();
}

function delete_transaction($id) {
    global $database;

    $query = "delete from transactions "
            . " where id = :id";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":id", $id);

    $statement->execute();

    $statement->closeCursor();
}