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
  <title>Prod. Order | AppzStory</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0 pt-4">
                                <h4> 
                                    <i class="fas fa fa-cube"></i> 
                                    Release Prod. Orders
                                </h4>
                                <!-- <a href="form-create.php" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus"></i>
                                    เพิ่มข้อมูล
                                </a> -->
                            </div>
                            <div class="card-body">
                                <table  id="logs" 
                                        class="table table-hover" 
                                        width="100%">
                                </table>
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
<script src="../../plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="../../plugins/toastr/toastr.min.js"></script>

<!-- datatables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function() {
        $.ajax({
            type: "GET",
            url: "../../service/prodorders/"
        }).done(function(data) {
            let tableData = []
            data.response.forEach(function (item, index){
                tableData.push([    
                    `${item.pd_id}`,
                    `<a href="profile.php?cid=${item.No_}&pjc=${item.Project_Code}">${item.No_}</a>`,
                    `${item.Description}`,
                    `${item.Description2}`,
                    `${item.Project_Code}`,
                    `${item.Header_Status}`                                                                   
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
                responsive: true,  
                columns: [
                    { title: "#" , className: "align-middle"},
                    { title: "No." , className: "align-middle"},
                    { title: "Description" , className: "align-middle"},
                    { title: "Description 2" , className: "align-middle"},
                    { title: "Project", className: "align-middle"}, 
                    { title: "QC Status", responsivePriority: 1, className: "align-middle"}                                                                          
                ],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    switch (aData[5]) {
                    case "0":
                        $('td',nRow).eq(5).css('color', 'blue');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("WAITING");
                        break;
                    case "1":
                        $('td',nRow).eq(5).css('color', '#1EAC76');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("PASS");
                        break;
                    case "2":
                        $('td',nRow).eq(5).css('color', 'red');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("NG");
                        break;
                    case "3":
                        $('td',nRow).eq(5).css('color', '#FFC300');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("INPROCESS");
                        break;
                    case "4":
                        $('td',nRow).eq(5).css('color', '#680EF3');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("NG + PENDING");
                        break;
                    case "5":
                        $('td',nRow).eq(5).css('color', 'black');
                        $('td',nRow).eq(5).css('font-weight', 'bold');
                        $('td',nRow).eq(5).text("PENDING");
                        break;
                    }
                
                },
                initComplete: function () {
                    $(document).on('click', '#delete', function(){ 
                        let id = $(this).data('id')
                        Swal.fire({
                            text: "คุณแน่ใจหรือไม่...ที่จะลบรายการนี้?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ใช่! ลบเลย',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({  
                                    type: "DELETE",  
                                    url: "../../service/products/delete.php",  
                                    data: { id: id }
                                }).done(function() {
                                    Swal.fire({
                                        text: 'รายการของคุณถูกลบเรียบร้อย',
                                        icon: 'success',
                                        confirmButtonText: 'ตกลง',
                                    }).then((result) => {
                                        location.reload();
                                    });
                                })
                            }
                        })
                    }).on('change', '.toggle-event', function(){
                        toastr.success('อัพเดทข้อมูลเสร็จเรียบร้อย')
                    })
                },
                fnDrawCallback: function() {
                    $('.toggle-event').bootstrapToggle();
                },
                responsive: {
                    details: {
                        // display: $.fn.dataTable.Responsive.display.modal( {
                        //     header: function ( row ) {
                        //         var data = row.data()
                        //         return 'รายการสินค้า'
                        //     }
                        // }),
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
