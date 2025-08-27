<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Time Tracking</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php') ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0"><i class="fas fa-clock"></i> Time Tracking</h1>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Time Logs</h3>
                            <button id="btnNewTimer" class="btn btn-primary float-right">
                                <i class="fas fa-plus"></i> New Timer
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="timeLogsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>User Name</th>
                                            <th>Date</th>
                                            <th>Project</th>
                                            <th>Drawing No.</th>
                                            <th>Activity</th>
                                            <th>Duration</th>
                                            <th>Note</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Timer -->
        <div class="modal fade" id="modalTimer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-clock"></i> Time Tracking</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Project <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="selectProject">
                                <option value="">-- Select Project --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Drawing No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inputDrawing" placeholder="Enter Drawing No.">
                        </div>
                        <div class="form-group">
                            <label>Activity <span class="text-danger">*</span></label>
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
                            <textarea class="form-control" id="inputNote" rows="2"></textarea>
                        </div>
                        <div class="text-center my-4">
                            <div class="h2" id="timerDisplay">00:00:00</div>
                            <div class="badge badge-secondary" id="statusBadge">Not started</div>
                        </div>
                        <div class="text-center">
                            <button id="btnStart" class="btn btn-success"><i class="fas fa-play"></i> Start</button>
                            <button id="btnPause" class="btn btn-warning" disabled><i class="fas fa-pause"></i> Pause</button>
                            <button id="btnStop" class="btn btn-danger" disabled><i class="fas fa-stop"></i> Stop</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Status -->
        <div class="modal fade" id="modalEditStatus" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formEditStatus">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Status</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editTimelogId">
                            <div class="form-group">
                                <label>Duration Time</label>
                                <div id="editDuration" class="font-weight-bold">-</div>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="editStatus">
                                    <option value="active">Processing</option>
                                    <option value="paused">Pause</option>
                                    <option value="completed">Finished</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" id="editNote" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php /* include('../../includes/footer.php'); */ ?>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/moment/moment.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/timetracking.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
</body>

</html>