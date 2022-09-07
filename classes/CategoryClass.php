<?php

class Category {
    public function getAll(){
        $list = array();
        global $pdo;

        $sql = $pdo->query("SELECT * FROM categories");
        if($sql->rowCount() > 0){
            $list = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return $list;
    }
}