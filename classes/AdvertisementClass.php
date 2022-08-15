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

            $sql = $pdo->prepare("SELECT id,url FROM advertisements_images WHERE advertisement_id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
            if($sql->rowCount() > 0)
                $item['photos'] = $sql->fetchAll();
            else
                $item['photos'] = null;

            return $item;
        }else
            return null;
    }

    public function editAdvertisement($id, $category, $title, $description, $price, $status, $photos){
        global $pdo;
        $sql = $pdo->prepare("UPDATE advertisements SET category_id = :category_id, title = :title, description = :description, price = :price, status = :status WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->bindValue(':category_id', $category);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':description', $description);
        $sql->bindValue(':price', $price);
        $sql->bindValue(':status', $status);
        $sql->execute();

        if(isset($photos)){
            for($qt=0; $qt<count($photos['name']); $qt++){
                if(in_array($photos['type'][$qt], array('image/png', 'image/jpeg',))){
                    $tmpName = md5(time().rand(0, 9000)).$photos['name'][$qt].'.png';
                    move_uploaded_file($photos['tmp_name'][$qt], 'assets/images/advertisements/'.$tmpName);

                    list($width_orig, $height_orig) = getimagesize('assets/images/advertisements/'.$tmpName);
                    $ratio = $width_orig / $height_orig;
                    $width = 500;
                    $height = 500;

                    if($width/$height > $ratio)
                        $width = $height*$ratio;
                    else
                        $height = $width*$ratio;

                    $img = imagecreatetruecolor($width, $height);
                    if($photos['type'][$qt] == 'image/jpeg'){
                        $ogirin = imagecreatefromjpeg('assets/images/advertisements/'.$tmpName);
                    }else if($photos['type'][$qt] == 'image/png'){
                        $ogirin = imagecreatefrompng('assets/images/advertisements/'.$tmpName);
                    }

                    imagecopyresampled($img, $ogirin, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    imagejpeg($img, 'assets/images/advertisements/'.$tmpName, 80);

                    $sql = $pdo->prepare("INSERT INTO advertisements_images SET advertisement_id = :id, url = :url");
                    $sql->bindValue(':id', $id);
                    $sql->bindValue(':url', $tmpName);
                    $sql->execute();
                }
            }
        }
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

    public function deletePhoto($id){
        $newId = 0;

        global $pdo;
        $sql = $pdo->prepare("SELECT advertisement_id FROM advertisements_images WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
        if ($sql->rowCount() > 0){
            $data = $sql->fetch();
            $newId = $data['advertisement_id'];
        }

        $sql = $pdo->prepare("DELETE FROM advertisements_images WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        return $newId;
    }
}