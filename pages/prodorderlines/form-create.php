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
  <title> รายการสั่งซื้อใหม่ </title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">

  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit" >
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">

  <!-- Datatables -->
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

  <!-- jquery.Thailand -->
  <link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper pt-4">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <h4> 
                                    <i class="fas fa-cart-arrow-down"></i> 
                                    รายการสั่งซื้อใหม่
                                </h4>
                            </div>
                            <div class="card-body px-1 px-md-5">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card shadow-sm">
                                            <div class="card-header pt-4">
                                                <h3 class="card-title"> 
                                                    <i class="fas fa-user"></i> 
                                                    ข้อมูลลูกค้า
                                                </h3>
                                            </div>
                                            <div class="card-body px-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>ค้นหารายชื่อลูกค้า</label>
                                                            <select class="custom-select selectSearch" data-placeholder="ค้นหาด้วยชื่อ หรือ เบอร์โทร">
                                                                <option selected="selected"></option>                                                                
                                                                <option>AppzStory</option>
                                                                <option>Jame</option>
                                                                <option>Ethan Winters</option>
                                                                <option>Rosemary</option>
                                                                <option>Chris Redfield</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="first_name">ชื่อจริง</label>
                                                            <input type="text" class="form-control form-control-border" name="first_name" id="first_name" placeholder="ชื่อจริง" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="last_name">นามสกุล</label>
                                                            <input type="text" class="form-control form-control-border" name="last_name" id="last_name" placeholder="นามสกุล" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="phone">เบอร์โทรศัพท์</label>
                                                            <input type="text" class="form-control form-control-border" name="phone" id="phone" placeholder="เบอร์โทรศัพท์" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">อีเมล</label>
                                                            <input type="email" class="form-control form-control-border" name="email" id="email" placeholder="อีเมล" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="col-lg-6">
                                        <div class="card shadow-sm">
                                            <div class="card-header pt-4">
                                                <h3 class="card-title"> 
                                                    <i class="fas fa-cart-arrow-down"></i> 
                                                    ที่อยู่การจัดส่ง
                                                </h3>
                                            </div>
                                            <div class="card-body px-5">
                                                <div class="form-group">
                                                    <label for="district">ตำบล / แขวง</label>
                                                    <input type="text" class="form-control form-control-border" name="district" id="district" placeholder="ตำบล / แขวง">
                                                </div>
                                                <div class="form-group">
                                                    <label for="amphoe">อำเภอ / เขต</label>
                                                    <input type="text" class="form-control form-control-border" name="amphoe" id="amphoe" placeholder="อำเภอ / เขต">
                                                </div>
                                                <div class="form-group">
                                                    <label for="province">จังหวัด</label>
                                                    <input type="text" class="form-control form-control-border" name="province" id="province" placeholder="จังหวัด">
                                                </div>
                                                <div class="form-group">
                                                    <label for="zipcode">รหัสไปรษณีย์</label>
                                                    <input type="email" class="form-control form-control-border" name="zipcode" id="zipcode" placeholder="รหัสไปรษณีย์">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header border-0 pt-4">
                                <h3 class="card-title"> 
                                    <i class="fas fa-cart-arrow-down"></i> 
                                    รายการสินค้า
                                </h3>
                                <br>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success mt-3" data-toggle="modal" data-target="#modalAdd">
                                    <i class="fa fa-plus-circle"></i> เลือกสินค้าที่ต้องการ
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">รายการสินค้าทั้งหมด</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive-xl">
                                                    <table class="table table-hover" id="products">
                                                        <thead>
                                                            <tr>
                                                                <th>รายละเอียดสินค้า</th>
                                                                <th>ราคาขาย</th>
                                                                <th>ดำเนินการ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="formData">
                                <div class="card-body">
                                    <div class="table-responsive-xxl">
                                        <table class="table table-hover" id="orders">
                                            <thead>
                                                <tr class="text-center">
                                                    <th style="width: 3%;">รหัสสินค้า</th>
                                                    <th style="width: 15%;">รูปภาพ</th>
                                                    <th style="width: 50%;">รายละเอียด</th>
                                                    <th style="width: 5%;">จำนวน</th>
                                                    <th style="width: 8%;">ราคาขาย</th>
                                                    <th style="width: 8%;">ส่วนลด</th>
                                                    <th style="width: 8%;">รวมเป็นเงิน</th>
                                                    <th style="width: 3%;">จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="8" class="text-center">ข้อมูลว่าง</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-md-6 col-lg-5 col-xl- text-right">
                                            <div class="table-responsive">
                                                <table class="table">
                                                <tr>
                                                    <th>รวมเป็นเงิน</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>ส่วนลดทั้งหมด</th>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <th>จำนวนเงินรวมทั้งสิ้น</th>
                                                    <td>0</td>
                                                </tr>
                                                </table>
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

<!-- scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/js/adminlte.min.js"></script>
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<!-- datatables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- jquery.Thailand -->
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>

<script>
    $(function() {
        let products = $('#products tbody')
        let orders = $('#orders tbody')
        let arrOrders = []

        function selectSearch() {
            $('.selectSearch').select2({
                width: '100%'
            })
        }
        
        function renderThailand() {
            $.Thailand({
                $district: $('[name="district"]'),
                $amphoe: $('[name="amphoe"]'),
                $province: $('[name="province"]'),
                $zipcode: $('[name="zipcode"]')
            })
        }

        function getProducts() {
            $.ajax({
                type: "GET",
                url: "../../service/products/"
            }).done(function(data) {
                data.response.forEach(function (item, index){
                    products.append(
                    `<tr>
                        <td> <img src="${item.p_image}" class="img-fluid" width="100px"> ${item.p_name} </td>
                        <td> ${item.price} </td>
                        <td> 
                            <button type="button" class="btn btn-outline-success" id="add${item.p_id}">
                                เลือกสินค้า
                            </button>
                        </td>
                    </tr>`)
                    $(`#add${item.p_id}`).on("click", function(){
                        addOrder(item)
                    })
                })
            }).fail(function() {
                products.append(`<tr><td colspan="5" class="text-center">ข้อมูลว่าง</td></tr>`)
            })
        }

        function addOrder(item) {
            item['p_amount'] = 1
            item['p_total'] = item['price']
            arrOrders.push(item)
            $('#modalAdd').modal('hide')
            renderOrder()
        }

        function deleteOrder(index) {
            arrOrders.splice(index, 1);
            renderOrder()
        }
        
        function renderOrder() {
            orders.empty();
            arrOrders.forEach(function (item, index){
                orders.append(`<tr>
                    <td> <a href="../products/" class="btn btn-outline-primary p-1"> ${item.p_id} </a> </td>
                    <td> <img src="${item.p_image}" class="w-150p d-block mx-auto"> </td>
                    <td> <p class="detail"> ${item.p_name}</p> </td>
                    <td> <input type="text" class="form-control rtl w-60p" value="${item.p_amount}"> </td>
                    <td> <p class="p-2 pl-4 text-right bg-light rounded-lg">${item.p_total}</p> </td>
                    <td> <input type="text" class="form-control rtl w-90p" value="0"> </td>
                    <td> <p class="p-2 pl-4 text-right bg-secondary rounded-lg">${item.price}</p> </td>
                    <td> 
                        <button type="button" class="btn btn-danger" id="delete${index}">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`)
                $(`#delete${index}`).on("click", function(){
                    deleteOrder(index)
                })
            })
        }

        $('#formData').on('submit', function (e) {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: '../../service/orders/create.php',
                dataType: "json",
                data: { arrOrders }
            }).done(function(resp) {
                Swal.fire({
                    text: 'เพิ่มข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    location.assign('./')
                })
            })
        })

        selectSearch()
        renderThailand()
        getProducts()

    })
</script>
</body>
</html>
