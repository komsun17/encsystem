<?php
/**
 **** AppzStory Back Office Management System Template ****
 * Index Get ALL Member Orders
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
header('Content-Type: application/json');
require_once '../connectmssql.php';

/**
 |--------------------------------------------------------------------------
 | ดึงข้อมูล Member Orders
 | 'SELECT * FROM members'
 |--------------------------------------------------------------------------
*/
/** 
 * กำหนดข้อมูลสำหรับการ Response ไปยังฝั่ง Client
 * 
 * @return array 
 */
if (isset($_GET['cid'])) {

    $strKagoNo = $_GET['cid'];
    //$strKagoNo = "23-115";

$stmt = $connmssql->prepare(" GetSemiByKago '".$strKagoNo."'");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
   'status' => true,
   'response' => $result,
   'message' => 'Get Data Manager Success'
];
http_response_code(200);
echo json_encode($response);
}