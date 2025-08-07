<?php
/**
 **** AppzStory Back Office Management System Template ****
 * Index Get ALL Products
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
header('Content-Type: application/json');
require_once '../connectmssql.php';

/**
 |--------------------------------------------------------------------------
 | ดึงข้อมูล Members ทั้งหมด
 | 'SELECT * FROM products'
 |--------------------------------------------------------------------------
*/
/** 
 * กำหนดข้อมูลสำหรับการ Response ไปยังฝั่ง Client
 * 
 * @return array 
 */
$stmt = $connmssql->prepare(" GetProdOrders ");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
   'status' => true,
   'response' => $result,
   'message' => 'Get Data Manager Success'
];

http_response_code(200);
echo json_encode($response);
