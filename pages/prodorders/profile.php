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
    <title>Prod. Order Detail | Thai Sinto</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <!-- stylesheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
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
        <?php $cid = $_GET['cid']; ?>
        <?php $pjc = $_GET['pjc']; ?>
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
                                        Prod. Order Detail
                                    </h4>
                                    <a href="./" class="btn btn-info mt-2">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>

                                </div>
                                <div class="card-body" id="card-body">
                                    <div class="px-2">
                                        <div class="row mb-1">
                                            <p class="col-xl-2 text-muted" style="font-size:20px;">Project Code :</p>
                                            <div class="col-xl-2 text-left">
                                                <p id="project_code" style="font-size:20px"><?php echo $_GET['pjc']; ?></p>
                                            </div>
                                            <p class="col-xl-2 text-muted " style="font-size:20px;">Kago No. :</p>
                                            <div class="col-xl-2 text-left">
                                                <p style="font-size:20px;"><?php echo $_GET['cid']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="logs" class="table table-hover" width="100%"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Modal Image -->
                <div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <img src="" alt="" id="modalImage" width="100%" height="100%">
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">

                        <form class="tagForm" id="tagForm" action="" method="POST" enctype="multipart/form-data">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <!-- <button type="button" class="close" data-dismiss="modal"></button> -->
                                    <h4 class="modal-title">Inspection&nbsp;&nbsp;</h4>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col">
                                                <h6 class="w-80">Kago No.:</h6>
                                            </div>
                                            <div class="col">
                                                <h6 class="w-100">Item No.:</h6>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <h5 id="txt_kago" class="w-80"></h5>
                                            </div>
                                            <div class="col">
                                                <h5 id="txt_item" class="w-100"></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="form-row justify-content-md-center">
                                        <div class="form-group col-md-6">
                                            <label for="status">Change Status</label>

                                            <select class="custom-select mb-3" name="status" id="status" onchange="getComboA(this)" required>
                                                <option value="">Select Status Types</option>
                                                <!-- <option value="0">Waiting</option> -->
                                                <option value="0">Waiting</option> 
                                                <option value="1">Pass</option>
                                                <option value="2">NG</option>
                                            </select>
                                            <label for="course">Cause</label>

                                            <select class="custom-select mb-3" name="cause" id="cause">
                                                <option value="">Select Cause Types</option>
                                                <option value="Dimension">Dimension</option>
                                                <option value="Hold and Thread">Hold and Thread</option>
                                                <option value="Material">Material</option>
                                                <option value="Other">Other</option>
                                                <option value="Surface">Surface</option>
                                                <option value="Welding">Welding</option>
                                            </select>

                                            <div id="imgControl" class="d-none">
                                                <img id="imgUpload" src="" class="img-fluid my-3">
                                            </div>
                                            <label for="file" class="col-form-label">Image Upload</label>
                                            <div>
                                                <input type="file" class="form-control" id="file" name="file" onchange="readURL(this)">
                                            </div>
                                            <input type="text" class="form-control" name="line_no" id="line_no" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="pol_id" id="pol_id" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="item_no" id="item_no" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="kago_no" id="kago_no" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="item_des" id="item_des" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="item_qty" id="item_qty" style="display: none;" readonly="readonly">
                                            <input type="text" class="form-control" name="project_code_txt" id="project_code_txt" style="display: none;" readonly="readonly">
                                            
                                            <!-- <span id="pol_id" style="display: none;"></span>
                                            <span id="item_no" style="display: none;"></span> -->
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="submit">Save changes</button>
                                </div>


                            </div>
                        </form>

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
            let cid = "<?php echo $cid ?>"
            $.ajax({
                type: "GET",
                url: "../../service/prodorders/profile.php",
                ataType: "json",
                data: {
                    cid: cid
                },
            }).done(function(data) {

                let tableData = []
                data.response.forEach(function(item, index) {
                    tableData.push([
                        ++index,
                        `${item.Line_No}`,
                        `${item.Item_No}`,
                        `${item.Description}`,
                        `${item.Qty}`,
                        `${item.Posting_Date}`,
                        `${item.Line_Status}`,
                        `${item.pol_id}`,
                        `${item.Ng_Cause}`,
                        `<a id="myLink" href="#" onclick="ModalShow('${item.image}')"><img id="myImg" class="category-icon" width="100" id="myImg" src="../../assets/images/items/${item.image}"></a>`
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
                $('#logs').DataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    data: tableData,
                    columns: [{
                            title: "#",
                            className: "align-middle"
                        },
                        {
                            title: "Line No.",
                            className: "align-middle"
                        },
                        {
                            title: "Item No.",
                            responsivePriority: 1,
                            className: "align-middle"
                        },
                        {
                            title: "Description",
                            className: "align-middle"
                        },
                        {
                            title: "Q'ty",
                            className: "align-middle",
                            render: $.fn.dataTable.render.number(',', '.', 0, '')
                        },
                        {
                            title: "Posting Date",
                            className: "align-middle"
                        },
                        {
                            title: "Status",
                            responsivePriority: 2,
                            className: "align-middle"
                        },
                        {
                            title: "POL ID",
                            visible: false,
                            className: "align-middle"
                        },
                        {
                            title: "NG Cause",
                            className: "align-middle"
                        },
                        {
                            title: "Image",
                            className: "align-middle"
                        }
                    ],
                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        let cid = "<?php echo $cid ?>"
                        let pid = (aData[1]);
                        let tid = (aData[2]);
                        let item_des = (aData[3]);
                        let item_qty = (aData[4]);
                        let project_code = document.getElementById('project_code').innerText;
                        let status_id = (aData[6]);
                        let pol_id = (aData[7]);
                        let cause_id = (aData[8]);
                        
                        switch (aData[6]) {

                            case "0":

                                $('td', nRow).eq(6).css('color', 'blue');
                                $('td', nRow).eq(6).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("WAITING");
                                $('td', nRow).eq(6).html(`<a class="btn btn-primary" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${item_des}','${item_qty}','${project_code}','${cause_id}','${status_id}'); return false;" role="button">WAITING</a>`)
                                break;
                            case "1":

                                $('td', nRow).eq(6).css('color', '#1EAC76');
                                $('td', nRow).eq(6).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("PASS");                      
                                $('td', nRow).eq(6).html(`<a class="btn btn-success" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${item_des}','${item_qty}','${project_code}','${cause_id}','${status_id}'); return false;" role="button">&nbsp;&nbsp;PASS&nbsp;&nbsp;</a>`)
                                break;
                            case "2":

                                $('td', nRow).eq(6).css('color', 'red');
                                $('td', nRow).eq(6).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("NG");
                                $('td', nRow).eq(6).html(`<a class="btn btn-danger" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${item_des}','${item_qty}','${project_code}','${cause_id}','${status_id}'); return false;" role="button">&nbsp;&nbsp;&nbsp;&nbsp;NG&nbsp;&nbsp;&nbsp;&nbsp;</a>`)
                                break;
                        }
                    },

                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data()
                                    return 'ผู้ใช้งาน: ' + data[1]
                                }
                            }),
                            renderer: $.fn.dataTable.Responsive.renderer.tableAll({
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

        function myFunction(pid, cid, tid, pol_id, item_des, item_qty, project_code, cause_id, status_id) {
            // console.log(pid);
            // console.log(cid);
            //console.log(pid);

            $("#line_no").val(pid); //assign value
            $("#txt_kago").text(cid); //assign value
            $("#txt_item").text(tid); //assign value
            $("#pol_id").val(pol_id); //assign value
            $("#item_no").val(tid); //assign value
            $("#kago_no").val(cid); //assign value
            $("#item_des").val(item_des); //assign value
            $("#item_qty").val(item_qty); //assign value
            $("#project_code_txt").val(project_code); //assign value
            $("#cause").val(cause_id); //assign value
            $("#status").val(status_id); //assign value
            $("#myModal").modal();
        }
        //Review รูปภาพ
        function readURL(input) {
            if (input.files[0]) {
                let reader = new FileReader();
                document.querySelector('#imgControl').classList.replace("d-none", "d-block");
                reader.onload = function(e) {
                    let element = document.querySelector('#imgUpload');
                    element.setAttribute("src", e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
                $("#imgUploadPic").hide();
                $("#imgName").hide();
            }
        }

        $("#tagForm").on('submit', function(e) {
            e.preventDefault();
            var data = new FormData($("#tagForm")[0]);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: "../../service/prodorders/line-update.php",
                // data: $('#tagForm').serialize(),
                dataType: "json",
                processData: false,
                contentType: false,
                data:data
                // data: {
                //     pol_id: pol_id,
                //     status: status,
                //     item_no: item_no,
                //     cause: cause,  
                //     file: file
                // },
            }).done(function(resp) {
                Swal.fire({
                    text: 'อัพเดทข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    $('#myModal').modal('hide');
                   
                });
            })
        });
        $('#myModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            location.reload();
        })

        // Change Combobox
        function getComboA(selectObject) {
            var value = selectObject.value;
            // console.log(value);
            if (value == 0 || value == 1) {
                document.getElementById("cause").disabled = true;
            } else {
                document.getElementById("cause").disabled = false;
            }
        }

        function ModalShow(image) {

            console.log(image);
            var imageShow = '../../assets/images/items/' + image;
            console.log(imageShow);

            $("#modalImage").attr("src", imageShow);
            $('#imgModal').appendTo("body")
            $('#imgModal').modal('show');
        }
        $('#imgModal').on('hidden.bs.modal', function() {
            $("#modalImage").attr("src", '')
            location.reload();
        })
    </script>
</body>

</html>