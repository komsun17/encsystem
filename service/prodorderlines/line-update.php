<?php
/**
 **** AppzStory Back Office Management System Template ****
 * Update Admin
 * 
 * @link https://appzstory.dev
 * @author Yothin Sapsamran (Jame AppzStory Studio)
 */
header('Content-Type: application/json');
require_once '../connectmssql.php';
/**
 |--------------------------------------------------------------------------
 | เขียนโค้ด Update Admin SQL ตัวอย่าง
 | 'UPDATE admin SET field1 = :var1, field2= :var2 WHERE admin_id = :id "'
 |--------------------------------------------------------------------------
*/
//$file = $_FILES['file']['tmp_name']; 

// ฟังก์ชั่นการ  Upload รูปภาพ
if(isset($_FILES['file']) && !$_FILES['file']['error']) {
    $file = $_FILES['file']['tmp_name']; 
    $sourceProperties = getimagesize($file);
    $fileNewName = $_POST['item_no']. "_" .time();
    $folderPath = "../../assets/images/items/";
    $ext = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
    $imageType = $sourceProperties[2];

    //ตรวจสอบไฟด์ Type png,gif,jpeg
    switch ($imageType) {

        case IMAGETYPE_PNG:
            $imageResourceId = imagecreatefrompng($file);
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagepng($targetLayer,$folderPath. $fileNewName.".". $ext);
            $fileUpload = $fileNewName. ".". $ext;
            break;
        
        case IMAGETYPE_GIF:
            $imageResourceId = imagecreatefromgif($file);
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagegif($targetLayer,$folderPath. $fileNewName. ".". $ext);
            $fileUpload = $fileNewName. ".". $ext;
            break;
        
        case IMAGETYPE_JPEG:
            $imageResourceId = imagecreatefromjpeg($file); 
            $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
            imagejpeg($targetLayer,$folderPath. $fileNewName. ".". $ext);
            $fileUpload = $fileNewName. ".". $ext;
            break;

        default:
            echo "Invalid Image type.";
            exit;
            break;
    }
    
    //move_uploaded_file($file, $folderPath. $fileNewName. "_origin.". $ext);

} else {
    // $fileUpload =  $_POST['imgName'];
    $fileUpload =  "";
}

// ฟังก์ชั่น Resize รูปภาพ
function imageResize($imageResourceId,$width,$height) {
    $targetWidth = $width < 1280 ? $width : 1280 ;
    $targetHeight = ($height/$width)* $targetWidth;
    $targetLayer = imagecreatetruecolor($targetWidth,$targetHeight);
    imagecopyresampled($targetLayer, $imageResourceId, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
    return $targetLayer;
}


if ($_SERVER['REQUEST_METHOD'] ==="POST") {
    
    $strKagoNo = $_POST['kago_no'];
    //$strKagoNo = "23-115"; 
    $pol_id = $_POST['pol_id']; 
    $status = $_POST['status'];
    $Qty = (int)($_POST['item_qty']);
    $line_no = $_POST['line_no'];
    $item_no = $_POST['item_no'];
    $item_des = $_POST['item_des'];
    $project_code = $_POST['project_code'];
    if (isset($_POST['cause'])) {
        $cause = $_POST['cause'];
    } else {
        $cause = "";
    }
    

    if ($status == 2 && $fileUpload <> "")  {

        //$stmt = $conn->prepare("UPDATE items SET th_description = :th_description, machinery = :machinery WHERE item_no = :item_no");
        $stmt = $connmssql->prepare("UPDATE tbl_prod_order_line SET Line_Status = :Line_Status, Ng_Cause = :cause, image= :image, Posting_Date = '".date("Y-m-d H:i:s")."' WHERE pol_id = :pol_id");
        //brndParam ข้อความทั่วไป = PARAM_STR, ตัวเลข = PARAM_INT
        $stmt->bindParam(':Line_Status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':pol_id', $pol_id, PDO::PARAM_INT);
        $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);
        $stmt->bindParam(':image', $fileUpload, PDO::PARAM_STR);
    
        $stmt->execute();
       
    } elseif ($status == 2 && $fileUpload = "") {

        //$stmt = $conn->prepare("UPDATE items SET th_description = :th_description, machinery = :machinery WHERE item_no = :item_no");
        $stmt = $connmssql->prepare("UPDATE tbl_prod_order_line SET Line_Status = :Line_Status, Ng_Cause = :cause, Posting_Date = '".date("Y-m-d H:i:s")."' WHERE pol_id = :pol_id");
        //brndParam ข้อความทั่วไป = PARAM_STR, ตัวเลข = PARAM_INT
        $stmt->bindParam(':Line_Status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':pol_id', $pol_id, PDO::PARAM_INT);
        $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);

        $stmt->execute();

    } else {
        //$stmt = $conn->prepare("UPDATE items SET th_description = :th_description, machinery = :machinery WHERE item_no = :item_no");
        $stmt = $connmssql->prepare("UPDATE tbl_prod_order_line SET Line_Status = :Line_Status ,Posting_Date = '".date("Y-m-d H:i:s")."' WHERE pol_id = :pol_id");
        //brndParam ข้อความทั่วไป = PARAM_STR, ตัวเลข = PARAM_INT
        $stmt->bindParam(':Line_Status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':pol_id', $pol_id, PDO::PARAM_INT);  
        //$stmt->bindParam(':cause', $cause, PDO::PARAM_STR);
        
        $stmt->execute();
        
    }

    
    $stmt2 = $connmssql->prepare(" UpdateKagoStatus '".$strKagoNo."'");
    $stmt2->execute();

    //--------------------------------- NG List ----------------------------------------
    //'INSERT INTO admin (field1, field2, field3) VALUES (:var1, :var2, :var3)'
    
    if ($status == 2 && $fileUpload <> "") {

        $stmt3 = $connmssql->prepare("INSERT INTO tbl_ng_history (Prod_Order_No, Line_No, Item_No, Description, Qty, Project_Code, Line_Status, Posting_Date, Ng_Cause) 
        VALUES (:KagoNo, :Line_no, :item_no, :item_des, :Qty, :project_code, :Line_Status, '".date("Y-m-d H:i:s")."', :cause )");
        $stmt3->bindParam(':KagoNo', $strKagoNo, PDO::PARAM_STR);
        $stmt3->bindParam(':Line_no', $line_no, PDO::PARAM_INT);
        $stmt3->bindParam(':item_no', $item_no, PDO::PARAM_STR);
        $stmt3->bindParam(':item_des', $item_des, PDO::PARAM_STR);
        $stmt3->bindParam(':Qty', $Qty, PDO::PARAM_INT);
        $stmt3->bindParam(':project_code', $project_code, PDO::PARAM_STR);
        $stmt3->bindParam(':Line_Status', $status, PDO::PARAM_INT);
        $stmt3->bindParam(':cause', $cause, PDO::PARAM_STR);
        
                
        $stmt3->execute();

    }

    
    $response = [
        'status' => true,
        'message' => 'Update Success'
    ];
    http_response_code(200);
    echo json_encode($response);
    
    } else {
    http_response_code(405);
    echo json_encode(array('ststus' => false, 'message' => 'Method Not Allowed!'));
    }

?>