<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Time Tracking</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../plugins/moment/moment.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php') ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><i class="fas fa-clock"></i> Time Tracking</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Time Logs</h3>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalTimer">
                                <i class="fas fa-plus"></i> New Timer
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="timeLogsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>โครงการ</th>
                                        <th>Drawing No.</th>
                                        <th>กิจกรรม</th>
                                        <th>เวลาที่ใช้</th>
                                        <th>หมายเหตุ</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timer Modal -->
    <div class="modal fade" id="modalTimer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-clock"></i> Time Tracking
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Project</label>
                        <select class="form-control select2" id="selectProject">
                            <option value="">Select Project</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Drawing</label>
                        <input type="text" class="form-control" id="inputDrawing" placeholder="Input Drawing No.">
                    </div>
                    <div class="form-group">
                        <label>Activity</label>
                        <select class="form-control" id="selectActivity">
                            <option value="design">Design</option>
                            <option value="review">Review</option>
                            <option value="modify">Modify</option>
                            <option value="meeting">Meeting</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea class="form-control" id="inputNote" rows="3"></textarea>
                    </div>
                    <div class="text-center my-4">
                        <div class="h2" id="timerDisplay">00:00:00</div>
                        <div class="badge badge-secondary" id="statusBadge">Not Started</div>
                    </div>
                    <div class="text-center">
                        <button id="btnStart" class="btn btn-success">
                            <i class="fas fa-play"></i> Start
                        </button>
                        <button id="btnPause" class="btn btn-warning" disabled>
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        <button id="btnStop" class="btn btn-danger" disabled>
                            <i class="fas fa-stop"></i> Stop
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/moment/moment.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script src="../../assets/js/timetracking.js"></script>
</body>

</html>