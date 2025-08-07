<?php 
    /**
     * Page Manager Edit Admin
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
  <title>จัดการผู้ดูแลระบบ | AppzStory</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
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
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <h4>
                                    <i class="fas fa-user-cog"></i> 
                                    แก้ไขข้อมูลผู้ดูแล
                                </h4>
                                <a href="./" class="btn btn-info my-3 ">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <form id="formData">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 px-1 px-md-5">

                                            <div class="form-group">
                                                <label for="first_name">ชื่อจริง</label>
                                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="ชื่อจริง" value="<?php echo $_SESSION['AD_FIRSTNAME'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="last_name">นามสกุล</label>
                                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="นามสกุล" value="<?php echo $_SESSION['AD_LASTNAME'] ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="username">ชื่อผู้ใช้งาน</label>
                                                <p class="bg-light p-2 shadow-sm"><?php echo $_SESSION['AD_USERNAME'] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">รหัสผ่าน</label>
                                                <p class="bg-light p-2 shadow-sm">******</p>
                                            </div>

                                        </div>
                                        <div class="col-md-6 px-1 px-md-5">

                                            <div class="form-group">
                                                <label for="permission">สิทธิ์การใช้งาน</label>
                                                <select class="form-control" name="status" id="permission" required>
                                                    <option value disabled >กำหนดสิทธิ์</option>
                                                    <option value="superadmin" selected>Super Admin</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="customFile">รูปโปรไฟล์</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile" accept="image/*">
                                                    <label class="custom-file-label" for="customFile">เลือกรูปภาพใหม่</label>
                                                </div>
                                                <img src="../../assets/images/avatar5.png" alt="Image Profile" class="img-fluid p-3">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
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
<!-- SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>

<script>
     $(function() {
        $('#formData').submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../../service/manager/update.php',
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
