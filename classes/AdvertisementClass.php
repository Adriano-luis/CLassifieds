<?php

class Advertisement {

    public function getAll($filters){
        global $pdo;

        $list = array();

        $queryFilters = array('1=1');
        if(isset($filters['category'])  && !empty($filters['category']))
            $queryFilters[] = 'category_id = :category_id';
        if((isset($filters['max-price']) && !empty($filters['max-price'])) && (isset($filters['min-price']) && (!empty($filters['min-price']) || $filters['min-price'] == "0")))
            $queryFilters[] = 'price BETWEEN :min_price AND :max_price ';
        if(isset($filters['title']) && !empty($filters['title']))
            $queryFilters[] = 'title like :title';
        if(isset($filters['status']) && !empty($filters['status']))
            $queryFilters[] = 'status = :status';

        $sql = $pdo->prepare("SELECT *,
         (SELECT advertisements_images.url FROM advertisements_images
          WHERE advertisements_images.advertisement_id = advertisements.id limit 1 ) as url 
          FROM advertisements WHERE user_id = :user_id AND ".implode(' AND ', $queryFilters)."");
        $sql->bindValue(':user_id', $_SESSION['user_id']);

        if(isset($filters['category'])  && !empty($filters['category']))
            $sql->bindValue(':category_id',$filters['category']);
        if((isset($filters['max-price']) && !empty($filters['max-price'])) && (isset($filters['min-price']) && (!empty($filters['min-price']) || $filters['min-price'] == 0))){
            $sql->bindValue(':max_price',$filters['max-price']);
            $sql->bindValue(':min_price',$filters['min-price']);
        }
        if(isset($filters['title']) && !empty($filters['title']))
            $sql->bindValue(':title',$filters['title']);
        if(isset($filters['status']) && !empty($filters['status']))
            $sql->bindValue(':status',$filters['status']);

        $sql->execute();

        if($sql->rowCount() > 0) {
            $list = $sql->fetchAll(PDO::FETCH_ASSOC);

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
        $sql = $pdo->prepare("SELECT *,
            (SELECT categories.name FROM categories
            WHERE categories.id = advertisements.category_id) as category,
            (SELECT users.phone FROM users
            WHERE users.id = advertisements.user_id) as phone
        FROM advertisements WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $item = $sql->fetch(PDO::FETCH_ASSOC);

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

    public function getLatested($page, $perPage = 2, $filters){
        global $pdo;

        $offset = ($page - 1) * $perPage;

        $list = array();

        $queryFilters = array('1=1');
        if(isset($filters['category'])  && !empty($filters['category']))
            $queryFilters[] = 'advertisements.category_id = :category_id';
        if((isset($filters['max-price']) && !empty($filters['max-price'])) && (isset($filters['min-price']) && !empty($filters['min-price'])))
            $queryFilters[] = 'advertisements.price BETWEEN :min_price AND :max_price';
        if(isset($filters['title']) && !empty($filters['title']))
            $queryFilters[] = 'advertisements.title like :title';
        if(isset($filters['status']) && !empty($filters['status']))
            $queryFilters[] = 'advertisements.status = :status';

        $sql = $pdo->prepare("SELECT *,
         (SELECT advertisements_images.url FROM advertisements_images
            WHERE advertisements_images.advertisement_id = advertisements.id LIMIT 1) as url,
        (SELECT categories.name FROM categories
            WHERE categories.id = advertisements.category_id) as category
        FROM advertisements WHERE ".implode(' AND ', $queryFilters)." ORDER BY id DESC LIMIT $offset, 2");

        if(isset($filters['category'])  && !empty($filters['category']))
            $sql->bindValue(':category_id',$filters['category']);
        if((isset($filters['max-price']) && !empty($filters['max-price'])) && (isset($filters['min-price']) && !empty($filters['min-price']))){
            $sql->bindValue(':max_price',$filters['max-price']);
            $sql->bindValue(':min_price',$filters['min-price']);
        }
        if(isset($filters['title']) && !empty($filters['title']))
            $sql->bindValue(':title',$filters['title']);
        if(isset($filters['status']) && !empty($filters['status']))
            $sql->bindValue(':status',$filters['status']);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $list = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        return $list;
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
        $sql = $pdo->prepare("SELECT advertisement_id, url FROM advertisements_images WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
        if ($sql->rowCount() > 0){
            $data = $sql->fetch(PDO::FETCH_ASSOC);
            $newId = $data['advertisement_id'];
        }

        $sql = $pdo->prepare("DELETE FROM advertisements_images WHERE id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if(unlink('assets/images/advertisements/'.$data['url']))
            return $newId;
        else
            echo 'Error';
    }
}