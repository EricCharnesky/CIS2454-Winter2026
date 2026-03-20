<?php

class Stock {

    private $symbol, $name, $price;

    public function __construct($symbol, $name, $price) {
        $this->set_symbol($symbol);
        $this->set_name($name);
        $this->set_price($price);
    }

    public function set_symbol($symbol) {
        $this->symbol = $symbol;
    }

    public function get_symbol() {
        return $this->symbol;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_price() {
        return $this->price;
    }


    public function set_name($name) {
        $this->name = $name;
    }

    public function set_price($price) {
        $this->price = $price;
    }

}

function get_stock($symbol){
    global $database;

    $query = 'SELECT symbol, name, price FROM stocks WHERE symbol = :symbol';

    // prepare the query please
    $statement = $database->prepare($query);
    
    $statement->bindValue(":symbol", $symbol);

    // run the query please
    $statement->execute();

    // this might be risky if you have HUGE amounts of data
    $stock = $statement->fetch();

    $statement->closeCursor();
   
    return new Stock($stock['symbol'], $stock['name'], $stock['price']);
}

function list_stocks() {
    global $database;

    $query = 'SELECT symbol, name, price FROM stocks';

    // prepare the query please
    $statement = $database->prepare($query);

    // run the query please
    $statement->execute();

    // this might be risky if you have HUGE amounts of data
    $stocks = $statement->fetchAll();

    $statement->closeCursor();

    $stocks_array = array();

    foreach ($stocks as $stock) {
        $stocks_array[] = new Stock($stock['symbol'], $stock['name'], $stock['price']);
    }

    return $stocks_array;
}

function insert_stock($stock) {
    global $database;

    // DANGER DANGER DANGER - SQL Injection risk
    // Don't ever just plug values into a query!
    //$query = "INSERT INTO stocks (symbol, name, current_price) "
    //        . "VALUES ($symbol, $name, $current_price)";
    // instead, use substitutions
    $query = "INSERT INTO stocks (symbol, name, price) "
            . "VALUES (:symbol, :name, :price)";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":symbol", $stock->get_symbol());
    $statement->bindValue(":name", $stock->get_name());
    $statement->bindValue(":price", $stock->get_price());

    $statement->execute();

    $statement->closeCursor();
}

function update_stock($stock) {
    global $database;

    $query = "update stocks set name = :name, price = :price "
            . " where symbol = :symbol";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":symbol", $stock->get_symbol());
    $statement->bindValue(":name", $stock->get_name());
    $statement->bindValue(":price", $stock->get_price());

    $statement->execute();

    $statement->closeCursor();
}

function delete_stock($stock) {
    global $database;

    $query = "delete from stocks "
            . " where symbol = :symbol";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":symbol", $stock->get_symbol());

    $statement->execute();

    $statement->closeCursor();
}