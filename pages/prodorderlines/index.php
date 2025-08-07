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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
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
                                        <i class="fas fa fa-search"></i>
                                        Prod. Order Semi
                                    </h4>
                                    <!-- <a href="form-create.php" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus"></i>
                                    เพิ่มข้อมูล
                                </a> -->
                                </div>

                                <div class="card-body">
                                    <!-- <label><input type="checkbox" class="filter" value="WAITING" />&nbsp;&nbsp;WAITING&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" class="filter" value="PASS" />&nbsp;&nbsp;PASS&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" class="filter" value="NG" />&nbsp;&nbsp;NG&nbsp;&nbsp;</label> -->

                                    <table id="logs" class="table table-hover" width="100%">
                                    </table>
                                </div>
                            </div>
                        </div>
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
                                <div class="container ">
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
                                    <div class="form-group col-md-8">
                                        <label for="status">Change Status</label>

                                        <select class="custom-select mb-2" name="status" id="status" onchange="getComboA(this)" required>
                                            <option value="">Select Status Types</option>
                                            <option value="0">Waiting</option>
                                            <option value="1">Pass</option>
                                            <option value="2">NG</option>
                                        </select>
                                        <label for="course">Cause</label>

                                        <select class="custom-select mb-2" name="cause" id="cause">
                                            <option value="">Select Cause Types</option>
                                            <option value="Dimension">Dimension</option>
                                            <option value="Hold and Thread">Hold and Thread</option>
                                            <option value="Material">Material</option>
                                            <option value="Other">Other</option>
                                            <option value="Surface">Surface</option>
                                            <option value="Welding">Welding</option>
                                        </select>

                                        <div class="form-group mb-1">
                                            <img id="imgUploadPic" src="" class="img-fluid my-3">
                                        </div>

                                        <div id="imgControl" class="d-none">
                                            <img id="imgUpload" src="" class="img-fluid my-3">
                                        </div>
                                        <label for="file" class="col-form-label">Image Upload</label>
                                        <div>
                                            <input type="file" class="form-control" id="file" name="file" onchange="readURL(this)">
                                        </div>
                                        <input type="text" class="form-control" name="pol_id" id="pol_id" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="item_no" id="item_no" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="kago_no" id="kago_no" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="item_des" id="item_des" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="item_qty" id="item_qty" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="project_code" id="project_code" style="display: none;" readonly="readonly">
                                        <input type="text" class="form-control" name="line_no" id="line_no" style="display: none;" readonly="readonly">
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
                url: "../../service/prodorderlines/"
            }).done(function(data) {
                let tableData = []
                data.response.forEach(function(item, index) {
                    tableData.push([
                        ++index,
                        `${item.Prod_Order_No}`,
                        `${item.Line_No}`,
                        `${item.Item_No}`,
                        `${item.Description}`,
                        `${item.Qty}`,
                        `${item.Project_Code}`,
                        `${item.Posting_Date}`,
                        `${item.Line_Status}`,
                        `${item.Ng_Cause}`,
                        `${item.pol_id}`,
                        `${item.image}`
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
                    data: tableData,
                    responsive: true,

                    columns: [{
                            title: "#",
                            className: "align-middle"
                        },
                        {
                            title: "Kago No.",
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
                            title: "Qty",
                            className: "align-middle",
                            render: $.fn.dataTable.render.number(',', '.', 0, '')
                        },
                        {
                            title: "Project Code",
                            className: "align-middle"
                        },
                        {
                            title: "Posting Date",
                            className: "align-middle",
                            "render": function(data, type, row) {
                                return data === '1900-01-01 00:00:00.000' ? '' : data; // Handle specific date
                            }
                        },
                        {
                            title: "Status",
                            responsivePriority: 2,
                            className: "align-middle"
                        },
                        {
                            title: "Cause",
                            className: "align-middle"
                        },
                        {
                            title: "POL ID",
                            visible: false,
                            className: "align-middle"
                        },
                        {
                            title: "Image",
                            visible: false,
                            className: "align-middle" 
                        },


                    ],



                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                        let cid = (aData[1]);
                        let pid = (aData[2]);
                        let tid = (aData[3]);
                        let item_des = (aData[4]);
                        let item_qty = (aData[5]);
                        let project_code = (aData[6]);
                        let status_id = (aData[8]);
                        let cause_id = (aData[9]);
                        let pol_id = (aData[10]);
                        let n_image = (aData[11]);

                        switch (aData[8]) {

                            case "0":

                                $('td', nRow).eq(8).css('color', 'blue');
                                $('td', nRow).eq(8).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("WAITING");
                                $('td', nRow).eq(8).html(`<a class="btn btn-primary btn-sm" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${n_image}','${status_id}','${cause_id}','${item_qty}','${item_des}','${project_code}'); return false;" role="button">WAITING</a>`)
                                break;
                            case "1":

                                $('td', nRow).eq(8).css('color', '#1EAC76');
                                $('td', nRow).eq(8).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("PASS");                      
                                $('td', nRow).eq(8).html(`<a class="btn btn-success btn-sm" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${n_image}','${status_id}','${cause_id}','${item_qty}','${item_des}','${project_code}'); return false;" role="button">&nbsp;&nbsp;PASS&nbsp;&nbsp;</a>`)
                                break;
                            case "2":

                                $('td', nRow).eq(8).css('color', 'red');
                                $('td', nRow).eq(8).css('font-weight', 'bold');
                                // $('td',nRow).eq(5).text("NG");
                                $('td', nRow).eq(8).html(`<a class="btn btn-danger btn-sm" onclick="myFunction('${pid}','${cid}','${tid}','${pol_id}','${n_image}','${status_id}','${cause_id}','${item_qty}','${item_des}','${project_code}'); return false;" role="button">&nbsp;&nbsp;&nbsp;&nbsp;NG&nbsp;&nbsp;&nbsp;&nbsp;</a>`)
                                break;
                        }
                    },

                    initComplete: function() {
                        $(document).on('click', '#delete', function() {
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
                                        data: {
                                            id: id
                                        }
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
                        }).on('change', '.toggle-event', function() {
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

            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.filter-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', filterTable);
                });

                function filterTable() {
                    const table = document.getElementById('logs');
                    const rows = table.querySelectorAll('tbody tr');
                    const activeFilters = Array.from(checkboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value);

                    rows.forEach(row => {
                        const cellValue = row.cells[1].textContent;
                        if (activeFilters.length === 0 || activeFilters.includes(cellValue)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            });
        })

        function myFunction(pid, cid, tid, pol_id, n_image, status_id, cause_id, item_qty, item_des, project_code) {
            // console.log(pid);
            //console.log(item_qty);
            //console.log(item_des);
            //console.log(status_id);

            $("#txt_kago").text(cid); //assign value
            $("#txt_item").text(tid); //assign value
            $("#pol_id").val(pol_id); //assign value
            $("#item_no").val(tid); //assign value 
            $("#item_des").val(item_des); //assign value
            $("#kago_no").val(cid); //assign value
            $("#status").val(status_id); //assign value
            $("#cause").val(cause_id); //assign value 
            $("#item_qty").val(item_qty); //assign value 
            $("#project_code").val(project_code); //assign value 
            $("#line_no").val(pid); //assign value 
            if (n_image != "null") {
                //console.log(n_image);                
                $('#imgUploadPic').attr("src", "../../assets/images/items/" + n_image);
            } else {
                //console.log("null");
                $('#imgUploadPic').attr("src", "../../assets/images/items/logo_sinto.jpg");
            }


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
                url: "../../service/prodorderlines/line-update.php",
                // data: $('#tagForm').serialize(),
                dataType: "json",
                processData: false,
                contentType: false,
                data: data
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
                    location.reload();
                });
            })
        });
        $('#myModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');

        })

        // Load Combobox

        $('#myModal').on('show.bs.modal', function() {
            var value = document.getElementById("status").value;
            //console.log(value);
            if (value == 0 || value == 1) {
                document.getElementById("cause").disabled = true;
            } else {
                document.getElementById("cause").disabled = false;
            }
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

        // -----------------------------------------------------------------------
    </script>
</body>

</html>