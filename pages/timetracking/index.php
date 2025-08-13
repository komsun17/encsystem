<?php
require_once('../authen.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Time Tracking</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/favicon.ico">
    <!-- stylesheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">


</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php') ?>
        <div class="content-wrapper pt-3">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0 pt-4">
                                    <h4><i class="fas fa-clock"></i> Time Tracking</h4>
                                    <a href="#" id="btnAddTimeLog" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i> เพิ่มบันทึกเวลา
                                    </a>
                                </div>
                                <div class="card-body">
                                    <table id="timeLogsTable" class="display" style="width:100%">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Time Tracking -->
        <div class="modal fade" id="modalTimeTracking" tabindex="-1" aria-labelledby="modalTimeTrackingLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content rounded shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title d-flex align-items-center" id="modalTimeTrackingLabel">
                            <i class="fas fa-clock mr-2"></i> บันทึกเวลา
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="ปิด">
                            <span aria-hidden="true" class="h4">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        <div class="form-group">
                            <label for="selectProject" class="font-weight-bold text-secondary">โปรเจกต์ <span class="text-danger">*</span></label>
                            <select id="selectProject" class="form-control form-control-lg rounded" required>
                                <option value="">กำลังโหลดข้อมูล...</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="selectDrawing" class="font-weight-bold text-secondary">Drawing <span class="text-danger">*</span></label>
                            <select id="selectDrawing" class="form-control form-control-lg rounded" required>
                                <option value="">เลือกโปรเจกต์ก่อน</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="inputNote" class="font-weight-bold text-secondary">หมายเหตุ (Note)</label>
                            <textarea id="inputNote" class="form-control form-control-lg rounded" rows="3" placeholder="ใส่หมายเหตุเพิ่มเติม"></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <div id="timerDisplay" class="h3 font-weight-bold text-primary mb-3" style="letter-spacing: 0.15em;">00:00:00</div>
                            <small class="text-muted">เวลาที่ใช้งาน (hh:mm:ss)</small>
                        </div>

                        <div class="d-flex justify-content-center btn-group-circle mt-4">
                            <button id="btnStart" class="btn btn-success btn-circle shadow" title="เริ่ม">
                                <i class="fas fa-play"></i>
                            </button>
                            <button id="btnPause" class="btn btn-warning btn-circle shadow" disabled title="พัก">
                                <i class="fas fa-pause"></i>
                            </button>
                            <button id="btnResume" class="btn btn-info btn-circle shadow" disabled title="ทำงานต่อ">
                                <i class="fas fa-play"></i>
                            </button>
                            <button id="btnStop" class="btn btn-danger btn-circle shadow" disabled title="หยุด">
                                <i class="fas fa-stop"></i>
                            </button>
                        </div>

                        <div class="mt-4 text-center">
                            <small>สถานะ: <span id="statusText" class="font-weight-bold text-secondary">ยังไม่เริ่ม</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script src="../../plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="../../plugins/toastr/toastr.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // โหลด DataTable
            $('#timeLogsTable').DataTable({
                ajax: {
                    url: "../../service/timetracking/get_timelog.php",
                    dataSrc: function(json) {
                        if (json.status === "success") {
                            return json.response;
                        } else {
                            console.error(json.message || "ไม่สามารถดึงข้อมูลได้");
                            return [];
                        }
                    },
                    error: function(xhr, error, thrown) {
                        console.error("Ajax error:", error, thrown);
                        alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
                    }
                },
                columns: [{
                        data: "username",
                        title: "User Name"
                    },
                    {
                        data: "project_name",
                        title: "Project"
                    },
                    {
                        data: "drawing_name",
                        title: "Drawing"
                    },
                    {
                        data: "start_time",
                        title: "Start Time"
                    },
                    {
                        data: "end_time",
                        title: "End Time"
                    },
                    {
                        data: "duration_minutes",
                        title: "Duration (min)",
                        render: function(data) {
                            return data ? data + " นาที" : "-";
                        }
                    },
                    {
                        data: "note",
                        title: "Note"
                    }
                ],
                language: {
                    url: "../../assets/datatables/i18n/th.json"
                }
            });

            // ตัวแปรเก็บสถานะจับเวลา
            let currentTimelogId = null;
            let timerInterval = null;
            let elapsedSeconds = 0;
            let timerRunning = false;

            function updateTimerDisplay() {
                let hrs = Math.floor(elapsedSeconds / 3600);
                let mins = Math.floor((elapsedSeconds % 3600) / 60);
                let secs = elapsedSeconds % 60;
                let display =
                    (hrs < 10 ? '0' : '') + hrs + ':' +
                    (mins < 10 ? '0' : '') + mins + ':' +
                    (secs < 10 ? '0' : '') + secs;
                $('#timerDisplay').text(display);
            }

            function startTimer() {
                if (timerRunning) return;
                timerRunning = true;
                timerInterval = setInterval(() => {
                    elapsedSeconds++;
                    updateTimerDisplay();
                }, 1000);
            }

            function stopTimer() {
                timerRunning = false;
                clearInterval(timerInterval);
            }

            function resetModal() {
                $('#statusText').text('ยังไม่เริ่ม').removeClass('text-success text-warning text-info text-danger').addClass('text-secondary');
                $('#btnStart').prop('disabled', false);
                $('#btnPause, #btnResume, #btnStop').prop('disabled', true);
                $('#selectProject, #selectDrawing').prop('disabled', false).val('');
                $('#inputNote').val('');
                $('#timerDisplay').text('00:00:00');
            }

            function loadProjects() {
                $('#selectProject').html('<option value="">กำลังโหลดข้อมูล...</option>');
                $.ajax({
                    url: '../../service/timetracking/get_projects.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            let options = '<option value="">-- เลือกโปรเจกต์ --</option>';
                            res.response.forEach(p => {
                                options += `<option value="${p.id}">${p.project_name}</option>`;
                            });
                            $('#selectProject').html(options);
                            $('#selectDrawing').html('<option value="">เลือกโปรเจกต์ก่อน</option>');
                        } else {
                            alert('ไม่สามารถโหลดข้อมูลโปรเจกต์ได้');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดขณะโหลดโปรเจกต์');
                    }
                });
            }

            $('#selectProject').change(function() {
                const projectId = $(this).val();
                if (!projectId) {
                    $('#selectDrawing').html('<option value="">เลือกโปรเจกต์ก่อน</option>');
                    return;
                }
                $('#selectDrawing').html('<option value="">กำลังโหลดข้อมูล...</option>');
                $.ajax({
                    url: '../../service/timetracking/get_drawings.php',
                    method: 'GET',
                    data: {
                        project_id: projectId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            let options = '<option value="">-- เลือก Drawing --</option>';
                            res.response.forEach(d => {
                                options += `<option value="${d.id}">${d.drawing_name}</option>`;
                            });
                            $('#selectDrawing').html(options);
                        } else {
                            alert('ไม่สามารถโหลดข้อมูล Drawing ได้');
                            $('#selectDrawing').html('<option value="">เลือกโปรเจกต์ก่อน</option>');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดขณะโหลด Drawing');
                        $('#selectDrawing').html('<option value="">เลือกโปรเจกต์ก่อน</option>');
                    }
                });
            });

            // เปิด modal + โหลดข้อมูลโปรเจกต์
            $('#btnAddTimeLog').click(function(e) {
                e.preventDefault();
                currentTimelogId = null;
                elapsedSeconds = 0;
                stopTimer();
                resetModal();
                loadProjects();
                $('#modalTimeTracking').modal('show');
            });

            // ปุ่ม Start
            $('#btnStart').click(function() {
                const projectId = $('#selectProject').val();
                const drawingId = $('#selectDrawing').val();
                const note = $('#inputNote').val();

                if (!projectId || !drawingId) {
                    alert('โปรดเลือก Project และ Drawing ก่อนเริ่ม');
                    return;
                }

                $.post('../../service/timetracking/start.php', {
                    project_id: projectId,
                    drawing_id: drawingId,
                    note: note
                }, function(res) {
                    if (res.status === 'success') {
                        currentTimelogId = res.timelog_id;
                        $('#statusText').text('กำลังทำงาน').removeClass().addClass('text-success font-weight-bold');
                        $('#btnStart').prop('disabled', true);
                        $('#btnPause, #btnStop').prop('disabled', false);
                        $('#btnResume').prop('disabled', true);
                        $('#selectProject, #selectDrawing').prop('disabled', true);
                        elapsedSeconds = 0;
                        updateTimerDisplay();
                        startTimer();
                    } else {
                        alert(res.message || 'เกิดข้อผิดพลาด');
                    }
                }, 'json');
            });

            // ปุ่ม Pause
            $('#btnPause').click(function() {
                if (!currentTimelogId) return;
                $.post('../../service/timetracking/pause.php', {
                    timelog_id: currentTimelogId
                }, function(res) {
                    if (res.status === 'success') {
                        $('#statusText').text('พักงาน').removeClass().addClass('text-warning font-weight-bold');
                        $('#btnPause').prop('disabled', true);
                        $('#btnResume').prop('disabled', false);
                        stopTimer();
                    } else {
                        alert(res.message || 'เกิดข้อผิดพลาด');
                    }
                }, 'json');
            });

            // ปุ่ม Resume
            $('#btnResume').click(function() {
                if (!currentTimelogId) return;
                $.post('../../service/timetracking/resume.php', {
                    timelog_id: currentTimelogId
                }, function(res) {
                    if (res.status === 'success') {
                        $('#statusText').text('กำลังทำงานต่อ').removeClass().addClass('text-info font-weight-bold');
                        $('#btnResume').prop('disabled', true);
                        $('#btnPause').prop('disabled', false);
                        startTimer();
                    } else {
                        alert(res.message || 'เกิดข้อผิดพลาด');
                    }
                }, 'json');
            });

            // ปุ่ม Stop
            $('#btnStop').click(function() {
                if (!currentTimelogId) return;
                $.post('../../service/timetracking/stop.php', {
                    timelog_id: currentTimelogId,
                    note: $('#inputNote').val()
                }, function(res) {
                    if (res.status === 'success') {
                        alert('บันทึกเวลาสำเร็จ');
                        currentTimelogId = null;
                        resetModal();
                        stopTimer();
                        $('#modalTimeTracking').modal('hide');
                        $('#timeLogsTable').DataTable().ajax.reload();
                    } else {
                        alert(res.message || 'เกิดข้อผิดพลาด');
                    }
                }, 'json');
            });
        });
    </script>
</body>

</html>