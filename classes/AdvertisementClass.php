<?php

class Advertisement {

    public function getAll(){
        global $pdo;

        $list = array();

        $sql = $pdo->prepare("SELECT *,
         (SELECT advertisements_images.url FROM advertisements_images
          WHERE advertisements_images.advertisements_id = advertisements.id limit 1 ) as url 
          FROM advertisements WHERE user_id = :user_id ");
        $sql->bindValue(':user_id', $_SESSION['user_id']);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $list = $sql->fetchAll();
        }

        return $list;
    }
}