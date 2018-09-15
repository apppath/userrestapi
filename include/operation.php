<?php

class Operation
{
    private $connection;
    function __construct()
    {
        require_once dirname(__FILE__) . '/connection.php';
        $dbconn = new Connection();
        $this->connection = $dbconn->connect();
    }
    //create new users
    function createUser($first_name , $last_name , $email_id , $age)
    {
        if (!$this->isUserExist($email_id)) {
            
            $stmt = $this->connection->prepare("INSERT INTO users(first_name , last_name, email_id , age)
            VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $first_name , $last_name , $email_id , $age);
            
            if ($stmt->execute())
                return CREATED_CODE;
            return FAILED_CODE;
        }
        return EXIST_CODE;
    }
    
    //get all users
    function getUsers(){
        $stmt = $this->connection->prepare("SELECT id , first_name , last_name , email_id , age FROM users");
        $stmt->execute();
        $stmt->bind_result($id, $first_name , $last_name , $email_id , $age);
        $users = array();
        while($stmt->fetch()){
            
            $temp = array();
            $temp['id'] = $id;
            $temp['first_name'] = $first_name;
            $temp['last_name'] = $last_name;
            $temp['email'] = $email_id;
            $temp['age'] = $age;

            array_push($users, $temp);

        }
        
        return $users;
    }
    //check if email exist or not
    function isUserExist($email_id)
    {
        $stmt = $this->connection->prepare("SELECT id FROM users WHERE email_id = ?");
        $stmt->bind_param("s", $email_id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows >0;
    }
  } //end of php class

  ?>