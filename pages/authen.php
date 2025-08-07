<?php 
    /**
     * Authentication Service
     * 
     * @link https://appzstory.dev
     * @author Yothin Sapsamran (Jame AppzStory Studio)
     */
    require_once '../../service/connect.php' ; 
    if( !isset($_SESSION['AD_ID'] ) ){
        header('Location: ../../login.php');  
    }
?>