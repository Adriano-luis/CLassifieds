<?php 
    require 'config.php';
    
    if(!isset($_SESSION['user_id'])){
        header("Location: myAdvertisements.php");
        exit;
    }
    
    require 'classes/AdvertisementClass.php';

    $advertisement = new Advertisement();
    if(isset($_GET['id']))
        $list = $advertisement->delete($_GET['id']);
    header("Location: myAdvertisements.php");
?>