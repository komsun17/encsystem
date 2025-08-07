<?php
/**
 **** AppzStory Back Office Management System Template ****
 * PHP Login API
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
header('Content-Type: application/json');
require_once '../connectmssql.php';

/**
 * $_POST['username']
 * $_POST['password']
 */

 if ($_SERVER['REQUEST_METHOD'] ==="POST") {
    $stmt = $connmssql->prepare("SELECT * FROM tbl_users WHERE userName = :username ");
    $stmt->execute(array(":username" => $_POST['username']));
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    /**
     * FETCH_OBJ
     * $row->u_id
     */
    /**
     * FETCH_ASSOC
     * $row['u_id']
     */

     if( !empty($row) && password_verify($_POST['password'], $row->password)) {

         $_SESSION['AD_ID'] = $row->u_id;
         $_SESSION['AD_FIRSTNAME'] = $row->firstName;
         $_SESSION['AD_LASTNAME'] = $row->lastName;
         $_SESSION['AD_USERNAME'] = $row->userName;
         $_SESSION['AD_IMAGE'] = $row->image;
         $_SESSION['AD_STATUS'] = $row->status;
         $_SESSION['AD_LOGIN'] = $row->updated_at;

         $count = $connmssql->exec("UPDATE [dbo].[tbl_users] SET updated_at = '".date("Y-m-d H:i:s")."'  WHERE u_id = $row->u_id");

         if($count){
            http_response_code(200);
            echo json_encode(array('ststus' => true, 'message' => 'Login Success!'));        
         } else {
            http_response_code(404);
            echo json_encode(array('ststus' => false, 'message' => 'Update Login Failed!'));
         }
            
     } else {

        http_response_code(401);
        echo json_encode(array('ststus' => false, 'message' => 'Unauthorized!'));

     }
    
 } else {
    http_response_code(405);
    echo json_encode(array('ststus' => false, 'message' => 'Method Not Allowed!'));
 }
 