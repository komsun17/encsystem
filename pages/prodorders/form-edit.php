<?php 
    /**
     * Page Manager
     * 
     * @link https://appzstory.dev
     * @author Yothin Sapsamran (Jame AppzStory Studio)
     */
    require_once('../authen.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>จัดการสินค้า | AppzStory</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper pt-3">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0 pt-4">
                                <h4> 
                                    <i class="fas fa-shopping-cart"></i> 
                                    รายการสินค้า
                                </h4>
                                <a href="./" class="btn btn-info mt-3">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <form id="formData">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="cat_name">ประเภทสินค้า</label>
                                            <select class="custom-select mb-3" name="cat_name">
                                                <option disabled>Select Course Types</option>
                                                <option selected value="sClass">StoryClass</option>
                                                <option value="mClass">MiniCourse</option>
                                                <option value="fClass">FreeCourse</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-9">
                                            <label for="p_name">ชื่อสินค้า</label>
                                            <input type="text" class="form-control" name="p_name" id="p_name" placeholder="ชื่อสินค้า" value="sClass2 Weblog Bootsrtap5 + Vuejs CDN + PHP สอนเขียนเว็บไซต์ด้วยตัวเองตั้งแต่ 0 - 100">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="price">ราคา / บาท</label>
                                            <input type="text" class="form-control" name="price" id="price" placeholder="ราคา" value="3,500">
                                        </div>
                                        <div class="form-group col-md-9">
                                            <label for="url">Url สินค้า</label>
                                            <input type="text" class="form-control" name="url" id="url" placeholder="Url สินค้า" value="https://appzstory.dev/c/sclass2-weblog-vuejs-php/">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="customFile">รูปปก</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">เลือกรูปภาพ</label>
                                            </div>
                                            <img src="https://appzstory.dev/_nuxt/img/sclass2.b99e196.jpg" alt="Image Profile" class="img-fluid p-3">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="detail">รายละเอียด</label>
                                            <textarea id="detail" class="textarea" name="detail" placeholder="Place some text here">
                                                <!-- โค้ดตรงส่วนนี้ จะต้องถูกส่งมาจาก Server เพื่อแก้ไข -->
                                                <h2 class="font-weight-bold" style="text-align: center;">
                                                    คอร์สเรียนออนไลน์ Vuejs CDN + PHP OOP (REST API)
                                                </h2>
                                                <div class="embed-responsive embed-responsive-16by9 mt-5 w-75 mx-auto"><iframe src="https://www.youtube.com/embed/C5tg_eFAX4I" class="embed-responsive-item"></iframe></div> <br> <p class="p-2 px-md-5 text-indent">
                                                    สวัสดีครับ คอร์สนี้จะเน้นไปที่การสร้างโปรเจคตั้งแต่เริ่มต้น ในส่วนของการออกแบบหน้าเว็บไซต์จะใช้ Bootstrap5 ที่เพิ่งเปิดตัวในการออกแบบหน้าเว็บ ในส่วนของ Frontend จะใช้ Vuejs แบบ CDN ในการพัฒนาระบบ และส่วนของ Backend จะใช้ PHP OOP หรือการเขียนโปรแกรมเชิงวัตถุ ในการสร้าง REST Api ขึ้นมาใช้งาน
                                                    </p> <section><h3 class="text-center py-5"><strong> Functional Requirement (หน้าที่หลักของระบบ) </strong></h3> <div class="row"><div class="col-md-6 align-self-center"><ul class="d-table mx-auto"><li><b>ขอบเขตส่วนของระบบหน้าบ้าน (ผู้ใช้งานทั่วไป)</b> <ul><li>สามารถดูบทความทั้งหมดได้</li> <li>สามารถเลือกดูบทความได้</li> <li>สามารถค้นหาบทความได้</li> <li>สามารถสมัครสมาชิกได้</li> <li>สามารถเข้าสู่ระบบได้</li></ul></li></ul></div> <div class="col-md-6 align-self-center"><img src="https://appzstory.dev/_nuxt/img/b150a9d.jpg" alt="" class="img-fluid rounded shadow"></div></div> <div class="row py-5"><div class="col-md-6 order-md-1 order-2 align-self-center"><img src="https://appzstory.dev/_nuxt/img/8c68e84.jpg" alt="" class="img-fluid rounded shadow"></div> <div class="col-md-6 order-md-2 order-1 align-self-center"><ul class="d-table mx-auto"><li><b>ขอบเขตส่วนของระบบหน้าบ้าน (ผู้ใช้งานที่เป็นสมาชิก)</b> <ul><li>สามารถแก้ไขข้อมูลส่วนตัวได้</li> <li>สามารถอัพโหลดรูปภาพส่วนตัวได้</li> <li>สามารถเปลี่ยนแปลงรหัสผ่านได้</li> <li>สามารถออกจากระบบได้</li> <li>สามารถแสดงความคิดเห็นหน้าบทความได้</li> <li>สามารถกดให้คะแนนบทความได้ (Star Rating)</li> <li>สามารถบันทึกบทความที่ชอบได้</li></ul></li></ul></div></div> <div class="row py-5"><div class="col-md-6 align-self-center"><ul class="d-table mx-auto"><li><b>ขอบเขตในส่วนของระบบหลังบ้าน (ผู้ดูแลระบบ)</b> <ul><li>สามารถ Login เข้าสู่ระบบได้</li> <li>สามารถแจ้งเวลาการเข้าใช้งานล่าสุด</li> <li>หน้า Dashboard สำหรับแสดงข้อมูลทั้งหมด</li> <li>แสดงรายชื่อ admin ทั้งหมด</li> <li>สามารถเพิ่ม admin คนใหม่ได้</li> <li>สามารถแก้ไขและกำหนดสิทธิ์ข้อมูลของ admin ได้</li> <li>สามารถลบข้อมูลของ admin ได้</li> <li>แสดงรายชื่อบทความทั้งหมด</li> <li>สามารถเพิ่มบทความใหม่ได้</li> <li>สามารถอัพโหลดรูปภาพหน้าปกได้</li> <li>สามารถเขียนบทความโดยใช้ WYSIWYG Editor ได้ </li> <li>สามารถแทรกรูปภาพลงในบทความได้ </li> <li>สามารถแก้ไขข้อมูลบทความได้</li> <li>สามารถกำหนดสิทธิ์ในการเผยแพร่บทความนั้นๆได้</li> <li>สามารถ logout ออกจากระบบได้</li></ul></li></ul></div> <div class="col-md-6 align-self-center"><img src="https://appzstory.dev/_nuxt/img/01f30f2.jpg" alt="" class="img-fluid rounded shadow"></div></div> <h3 class="text-center py-3"><strong> Non-functional Requirement (คุณสมบัติอื่นๆ ของระบบ) </strong></h3> <ul class="d-table mx-auto"><li>ออกแบบโครงสร้างหน้าเว็บด้วย Bootstrap5 เวอร์ชั่นใหม่ล่าสุด</li> <li>รองรับการใช้งานผ่านมือถือ Responsive Web Design</li> <li>รองรับการทำงานสำหรับเบราเซอร์เวอร์ชั่นใหม่ๆ</li> <li>ระบบหลังบ้านจะใช้ Admin Template ที่ออกแบบโครงสร้างไว้ให้</li> <li>มีการ Validate Form เพื่อตรวจสอบการนำเข้าของข้อมูล</li> <li>มีการเข้ารหัสผ่าน Password Hashed</li> <li>เขียนบทความโดยใช้ Summernote Super Simple WYSIWYG editor</li> <li>รูปแบบการเขียน Client side จะใช้ Vuejs + Axios และ PHP(สำหรับเน้น SEO)</li> <li>รูปแบบการเขียน Server side จะใช้ PHP OOP (REST API)</li> <li>เชื่อมต่อฐานข้อมูล ด้วย PHP PDO</li> <li>เชื่อมต่อ Google Analytics สำหรับเก็บสถิติผู้เข้าใช้งานในเว็บไซต์</li> <li>ตั้งค่า Meta Tag และ Debug Sharing Facebook</li></ul></section> <hr> <div class="text-center py-4"><h3 class="pb-2"><strong> Chapter1 เรียนรู้โปรแกรมต่างๆที่จำเป็นต่อการเขียนเว็บไซต์ </strong></h3> <div class="embed-responsive embed-responsive-16by9 w-75 mx-auto"><iframe src="https://www.youtube.com/embed/LwlGIT3Q3H0?rel=0&amp;enablejsapi=1&amp;showinfo=0&amp;modestbranding=1" class="embed-responsive-item"></iframe></div></div> <div class="text-center py-4"><h3 class="pb-2"><strong> Chapter2 เริ่มต้นวิเคราะห์ Project และทำเอกสาร </strong></h3> <div class="embed-responsive embed-responsive-16by9 w-75 mx-auto"><iframe src="https://www.youtube.com/embed/JceE01V0vx8?rel=0&amp;enablejsapi=1&amp;showinfo=0&amp;modestbranding=1" class="embed-responsive-item"></iframe></div></div> <h4 class="text-center py-4"></h4> 
                                                <!-- โค้ดตรงส่วนนี้ จะต้องถูกส่งมาจาก Server เพื่อแก้ไข -->
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block mx-auto w-75" name="submit">บันทึกข้อมูล</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('../includes/footer.php') ?>
</div>

<!-- scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>

<script>
    $(function() {
        $('#detail').summernote({
            height: 500,
        });

        $('#formData').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../../service/products/update.php',
                data: $('#formData').serialize()
            }).done(function(resp) {
                Swal.fire({
                    text: 'อัพเดทข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    location.assign('./');
                });
            })
        });
    });
</script>
</body>
</html>
