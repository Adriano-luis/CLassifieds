<?php

class User {

    /**
     * param @name string 
     * param @email string 
     * param @password hash 
     * param @phone string 
     * 
     * return 
     */
    public function cadastrar($name, $email, $password, $phone) {
        global $pdo;
        $sql = "SELECT id FROM users WHERE email = :email";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(':email', $email);
        $sql->execute();

        if($sql->rowCount() == 0 ){
            $sql = $pdo->prepare("INSERT INTO users SET name = :name, email = :email, password = :password, phone = :phone");
            $sql->bindValue(':name', $name);
            $sql->bindValue(':email', $email);
            $sql->bindValue(':password', $password);
            $sql->bindValue(':phone', $phone);
            $sql->execute();

            return true;
        }else
            return false;
    }
}
?>
