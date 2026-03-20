<?php


class User {

    private $id, $name, $balance;

    public function __construct($name, $balance, $id = 0) {
        $this->set_id($id);
        $this->set_name($name);
        $this->set_balance($balance);
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_balance() {
        return $this->balance;
    }


    public function set_name($name) {
        $this->name = $name;
    }

    public function set_balance($balance) {
        $this->balance = $balance;
    }

}


function get_user($id){
    global $database;

    $query = 'SELECT name, balance, id FROM users WHERE id = :id';

    // prepare the query please
    $statement = $database->prepare($query);
    
    $statement->bindValue(":id", $id);

    // run the query please
    $statement->execute();

    // this might be risky if you have HUGE amounts of data
    $user = $statement->fetch();
    
    $statement->closeCursor();
    
    return new User($user['name'], $user['balance'], $user['id']);
    
}

function list_users() {
    global $database;

    $query = 'SELECT name, balance, id FROM users';

    // prepare the query please
    $statement = $database->prepare($query);

    // run the query please
    $statement->execute();

    // this might be risky if you have HUGE amounts of data
    $users = $statement->fetchAll();
    
    $statement->closeCursor();
    
    $users_array = array();

    foreach ($users as $user) {
        $users_array[] = new User($user['name'], $user['balance'], $user['id']);
    }

    return $users_array;
}

function insert_user($user) {
    global $database;

    $query = "INSERT INTO users (name, balance) "
            . "VALUES (:name, :balance)";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":name", $user->get_name());
    $statement->bindValue(":balance", $user->get_balance());

    $statement->execute();

    $statement->closeCursor();
}

function update_user($user) {
    global $database;

    $query = "update users set name = :name, balance = :balance "
            . " where id = :id";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":name", $user->get_name());
    $statement->bindValue(":balance", $user->get_balance());
    $statement->bindValue(":id", $user->get_id());

    $statement->execute();

    $statement->closeCursor();
}

function delete_user($user_id) {
    global $database;

    $query = "delete from users "
            . " where id = :id";

    // value binding in PDO protects against sql injection
    $statement = $database->prepare($query);
    $statement->bindValue(":id", $user_id);

    $statement->execute();

    $statement->closeCursor();
}

