<?php 
    /**
     * AppzStory Back Office Management System Template
     *
     * @link https://appzstory.dev
     * @author Yothin Sapsamran (Jame AppzStory Studio)
     */
    session_start(); // ประกาศ การใช้งาน session
    session_destroy(); // ลบตัวแปร session ทั้งหมด
    header('Location: ../login.php');
?>