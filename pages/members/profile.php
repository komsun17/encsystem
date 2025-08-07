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
  <title>จัดการสมาชิก | AppzStory</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
  <!-- Datatables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper pt-3">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <h4> 
                                    <i class="fas fa-users"></i> 
                                    ข้อมูลส่วนตัว
                                </h4>
                                <a href="./" class="btn btn-info mt-3">
                                    <i class="fas fa-list"></i>
                                    กลับหน้าหลัก
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="px-5">
                                    <div class="row mb-3">
                                        <p class="col-xl-1 text-muted">ชื่อ - นามสกุล :</p>
                                        <div class="col-xl-10">
                                            <a href="../members/profile.php?id=1">Yothin Sapsamran</a>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <p class="col-xl-1 text-muted">อีเมล :</p>
                                        <div class="col-xl-10">
                                            <p>appzstory@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <p class="col-xl-1 text-muted">เบอร์โทรศัพท์ :</p>
                                        <div class="col-xl-10">
                                            <p>0868085595</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-title d-block w-100 py-3"> 
                                    <i class="fas fa-cart-arrow-down"></i>
                                    ประวัติการสั่งซื้อ
                                </div>
                                <table id="logs" class="table table-hover" width="100%"></table>
                            </div>
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
<script src="../../assets/js/adminlte.min.js"></script>

<!-- datatables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function() {
        $.ajax({
            type: "GET",
            url: "../../service/members/profile.php"
        }).done(function(data) {
            let tableData = []
            data.response.forEach(function (item, index){
                tableData.push([    
                    `<a href="../orders/info.php?o_id=${item.o_id}" class="btn btn-outline-primary p-1 "> ${item.o_id} </button>`,
                    `<span class="badge badge-success"> ${item.status ? 'ชำระเงินแล้ว': 'รอชำระเงิน'} </span>`,
                    item.total,
                    `<span class="text-muted small"> 1 ชั่วโมงที่ผ่านมา <br> 10 พ.ค. 64 · 16:56 น.</span>`,
                    `<span class="text-muted"> ${item.ps} </span>  `,
                    `<a href="../orders/info.php?o_id=${item.o_id}" class="btn btn-info">
                        <i class="fas fa-search"></i> ดูข้อมูล
                    </a>`
                ])
            })
            initDataTables(tableData)
        }).fail(function() {
            Swal.fire({ 
                text: 'ไม่สามารถเรียกดูข้อมูลได้', 
                icon: 'error', 
                confirmButtonText: 'ตกลง', 
            }).then(function() {
                location.assign('../dashboard')
            })
        })

        function initDataTables(tableData) {
            $('#logs').DataTable( {
                data: tableData,
                columns: [
                    { title: "ใบสั่งซื้อ", className: "align-middle"},
                    { title: "สถานะ" , className: "align-middle"},
                    { title: "ราคารวม / บาท", className: "align-middle"},
                    { title: "วันที่สั่งซื้อ", className: "align-middle"},
                    { title: "หมายเหตุ", className: "align-middle"},
                    { title: "จัดการ", className: "align-middle"}
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal( {
                            header: function ( row ) {
                                var data = row.data()
                                return 'ผู้ใช้งาน: '+data[1]
                            }
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                            tableClass: 'table'
                        })
                    }
                },
                language: {
                    "lengthMenu": "แสดงข้อมูล _MENU_ แถว",
                    "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                    "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                    "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": 'ค้นหา',
                    "paginate": {
                        "previous": "ก่อนหน้านี้",
                        "next": "หน้าต่อไป"
                    }
                }
            })
        }

    })
</script>
</body>
</html>
