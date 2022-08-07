<?php

class Advertisement {

    public function getAll(){
        global $pdo;

        $list = array();

        $sql = $pdo->prepare("SELECT *,
         (SELECT advertisements_images.url FROM advertisements_images
          WHERE advertisements_images.advertisement_id = advertisements.id limit 1 ) as url 
          FROM advertisements WHERE user_id = :user_id ");
        $sql->bindValue(':user_id', $_SESSION['user_id']);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $list = $sql->fetchAll();
        }

        return $list;
    }

    public function newAdvertisement($category, $title, $description, $price, $status){
        global $pdo;
        $sql = "INSERT INTO advertisements SET user_id = :user_id,  ";
        $sql = $pdo->prepare($sql);
        $sql->bindValue(':user_id', $_SESSION['user_id']);
        $sql->bindValue(':category_id', $category);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':price', $price);
        $sql->bindValue(':status', $status);
        $sql->execute();

    }

    public function getAdvertisement($id){
        global $pdo;
        $sql = $pdo->prepare("SELECT * FROM advertisements WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $item = $sql->fetch();
            return $item;
        }else
            return null;
    }

    public function editAdvertisement($id, $category, $title, $description, $price, $status){
        global $pdo;
        $sql = $pdo->prepare("UPDATE advertisements SET category_id = :category_id, title = :title, description = :description, price = :price, status = :status WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->bindValue(':category_id', $category);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':price', $price);
        $sql->bindValue(':status', $status);
        $sql->execute();
    }

    public function delete($id){
        global $pdo;
        $sql = $pdo->prepare("DELETE FROM advertisements_images WHERE advertisement_id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        $sql = $pdo->prepare("DELETE FROM advertisements WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
}