<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';
$isAdmin = ($role === 'admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Time Tracking Report</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .report-card-btn {
            cursor: pointer;
            transition: box-shadow .2s, border .2s;
            border: 2px solid transparent;
        }

        .report-card-btn.active,
        .report-card-btn:hover {
            box-shadow: 0 0 0 2px #007bff;
            border-color: #007bff !important;
        }

        .small-box {
            border-radius: 0.5rem;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .small-box .inner {
            width: 100%;
        }

        .small-box h4 {
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php') ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0"><i class="fas fa-clock"></i> Time Tracking Report</h1>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-12 text-right">
                            <!-- <button id="btnSyncProjectCode" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Sync Project Code</button> -->
                        </div>
                    </div>
                    <!-- Card Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="small-box bg-info report-card-btn active" id="btnCardProject">
                                <div class="inner text-center">
                                    <i class="fas fa-project-diagram fa-2x mb-2"></i>
                                    <h4>Report By Project Code</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="small-box bg-primary report-card-btn" id="btnCardMonthly">
                                <div class="inner text-center">
                                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                    <h4>Monthly Report</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Card Buttons -->

                    <!-- Project Code Report -->
                    <div id="sectionProjectReport">
                        <div class="card">
                            <div class="card-body">
                                <form id="projectReportForm" class="form-inline mb-3 flex-wrap">
                                    <?php if ($isAdmin): ?>
                                        <label class="mr-2">User:</label>
                                        <select id="filterUserProject" class="form-control mr-3"></select>
                                    <?php else: ?>
                                        <input type="hidden" id="filterUserProject" value="<?php echo htmlspecialchars($userId); ?>">
                                    <?php endif; ?>
                                    <label class="mr-2">Project Code:</label>
                                    <select id="filterProject" class="form-control mr-3"></select>
                                    <button type="submit" class="btn btn-primary mr-2">View</button>
                                    <button type="button" id="btnExportExcelProject" class="btn btn-success mr-2">Export Excel</button>
                                    <button type="button" id="btnPrintPDFProject" class="btn btn-secondary">Print PDF</button>
                                </form>
                                <div id="reportSummaryProject" class="mb-3"></div>
                                <div class="table-responsive">
                                    <table id="reportTableProject" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Project</th>
                                                <th>Drawing No.</th>
                                                <th>Activity</th>
                                                <th>Duration (min)</th>
                                                <th>Note</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded by JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Monthly Report -->
                    <div id="sectionMonthlyReport" style="display:none;">
                        <div class="card">
                            <div class="card-body">
                                <form id="monthlyReportForm" class="form-inline mb-3 flex-wrap">
                                    <?php if ($isAdmin): ?>
                                        <label class="mr-2">User:</label>
                                        <select id="filterUserMonthly" class="form-control mr-3"></select>
                                    <?php else: ?>
                                        <input type="hidden" id="filterUserMonthly" value="<?php echo htmlspecialchars($userId); ?>">
                                    <?php endif; ?>
                                    <label class="mr-2">Month:</label>
                                    <input type="month" id="filterMonth" class="form-control mr-3" value="<?php echo date('Y-m'); ?>">
                                    <button type="submit" class="btn btn-primary mr-2">View</button>
                                    <button type="button" id="btnExportExcelMonthly" class="btn btn-success mr-2">Export Excel</button>
                                    <button type="button" id="btnPrintPDFMonthly" class="btn btn-secondary">Print PDF</button>
                                </form>
                                <div id="reportSummaryMonthly" class="mb-3"></div>
                                <div class="table-responsive">
                                    <table id="reportTableMonthly" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Project</th>
                                                <th>Drawing No.</th>
                                                <th>Activity</th>
                                                <th>Duration (min)</th>
                                                <th>Note</th>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Data will be loaded by JS -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Monthly Report -->
                </div>
            </div>
        </div>

        <script src="../../plugins/jquery/jquery.min.js"></script>
        <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../plugins/moment/moment.min.js"></script>
        <script src="../../plugins/select2/js/select2.full.min.js"></script>
        <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="../../assets/js/adminlte.min.js"></script>
        <!-- เพิ่ม Script สำหรับ SheetJS และ jsPDF -->
        <script src="../../assets/js/xlsx.full.min.js"></script>
        <script src="../../assets/js/jspdf.umd.min.js"></script>
        <script src="../../assets/js/jspdf.plugin.autotable.min.js"></script>
        <script>
            $(function() {
                // Card switch logic
                $("#btnCardProject").on("click", function() {
                    $(this).addClass("active");
                    $("#btnCardMonthly").removeClass("active");
                    $("#sectionProjectReport").show();
                    $("#sectionMonthlyReport").hide();
                });
                $("#btnCardMonthly").on("click", function() {
                    $(this).addClass("active");
                    $("#btnCardProject").removeClass("active");
                    $("#sectionProjectReport").hide();
                    $("#sectionMonthlyReport").show();
                });

                // Project Report Table
                let reportTableProject = $("#reportTableProject").DataTable({
                    searching: false,
                    paging: true,
                    pageLength: 25
                });
                let reportTableMonthly = $("#reportTableMonthly").DataTable({
                    searching: false,
                    paging: true,
                    pageLength: 25
                });

                // Load users/projects for both forms
                function loadUsers(sel) {
                    $.getJSON("../../service/report/user_list.php", function(res) {
                        if (res.status === "success") {
                            let opts = `<option value="">-- All Users --</option>`;
                            res.data.forEach(u => opts += `<option value="${u.id}">${u.name}</option>`);
                            $(sel).html(opts);
                        }
                    });
                }

                function loadProjects() {
                    $.getJSON("../../service/report/project_code_from_time_entries.php", function(res) {
                        if (res.status === "success") {
                            let opts = `<option value="">-- All Projects --</option>`;
                            res.data.forEach(p => opts += `<option value="${p.code}">${p.code}</option>`);
                            $("#filterProject").html(opts);
                        }
                    });
                }
                // Init user/project select
                <?php if ($isAdmin): ?>
                    loadUsers("#filterUserProject");
                    loadUsers("#filterUserMonthly");
                <?php endif; ?>
                loadProjects();

                // Project Report Submit
                $("#projectReportForm").on('submit', function(e) {
                    e.preventDefault();
                    let params = {
                        user_id: <?php if ($isAdmin): ?> $("#filterUserProject").val() <?php else: ?> "<?php echo htmlspecialchars($userId); ?>"
                    <?php endif; ?>,
                    project_code: $("#filterProject").val(),
                    from: $("#filterFrom").val(),
                    to: $("#filterTo").val()
                    };
                    $.getJSON("../../service/report/project.php", params, function(res) {
                        if (res.status === "success") {
                            renderReportTable(reportTableProject, res.data.entries);
                            renderSummary("#reportSummaryProject", res.data.summary);
                        } else {
                            Swal.fire("Error", res.message || "Cannot load report", "error");
                        }
                    });
                });
                // Monthly Report Submit
                $("#monthlyReportForm").on('submit', function(e) {
                    e.preventDefault();
                    let params = {
                        user_id: <?php if ($isAdmin): ?> $("#filterUserMonthly").val() <?php else: ?> "<?php echo htmlspecialchars($userId); ?>"
                    <?php endif; ?>,
                    month: $("#filterMonth").val()
                    };
                    $.getJSON("../../service/report/monthly.php", params, function(res) {
                        if (res.status === "success") {
                            // เปลี่ยนจาก project_stats เป็น entries
                            renderReportTable(reportTableMonthly, res.data.entries);
                            renderSummary("#reportSummaryMonthly", res.data.summary);
                        } else {
                            Swal.fire("Error", res.message || "Cannot load report", "error");
                        }
                    });
                });

                function renderReportTable(table, entries) {
                    table.clear();
                    entries.forEach((r, i) => {
                        // duration เป็นวินาที ให้แปลงเป็นชั่วโมง (ทศนิยม 2 ตำแหน่ง)
                        let durationHour = "-";
                        if (r.duration && !isNaN(r.duration)) {
                            durationHour = (r.duration / 3600).toFixed(2) + " h";
                        }
                        table.row.add([
                            i + 1,
                            r.start_time ? moment.utc(r.start_time).local().format('YYYY-MM-DD HH:mm') : '-',
                            r.end_time ? moment.utc(r.end_time).local().format('YYYY-MM-DD HH:mm') : '-',
                            r.project_code || r.project_name || '-',
                            r.drawing_no || '-',
                            r.activity_type || '-',
                            durationHour,
                            r.note || '-',
                            r.user_name || '-'
                        ]);
                    });
                    table.draw();
                }

                function renderMonthlyTable(table, stats) {
                    table.clear();
                    // ป้องกัน error ถ้า stats เป็น undefined หรือไม่ใช่ array
                    if (!Array.isArray(stats) || stats.length === 0) {
                        table.draw();
                        return;
                    }
                    stats.forEach((r, i) => {
                        // total_minutes เป็นนาที ให้แปลงเป็นชั่วโมง (ทศนิยม 2 ตำแหน่ง)
                        let durationHour = "-";
                        if (r.total_minutes && !isNaN(r.total_minutes)) {
                            durationHour = (r.total_minutes / 60).toFixed(2) + " h";
                        }
                        table.row.add([
                            i + 1,
                            "-", // Start Time (ไม่มีใน project_stats)
                            "-", // End Time (ไม่มีใน project_stats)
                            r.project_name || r.project_code || r.code || "-", // Project
                            "-", // Drawing No. (ไม่มีใน project_stats)
                            "-", // Activity (ไม่มีใน project_stats)
                            durationHour,
                            "-", // Note (ไม่มีใน project_stats)
                            "-" // User (ไม่มีใน project_stats)
                        ]);
                    });
                    table.draw();
                }

                function renderSummary(sel, s) {
                    let html = '';
                    if (s && s.total_minutes !== undefined) {
                        html += `<b>Total:</b> ${formatDurationMinutes(s.total_minutes)} (${s.total_minutes} min)`;
                    } else if (s && s.total_seconds !== undefined) {
                        html += `<b>Total:</b> ${formatDurationSeconds(s.total_seconds)}`;
                    }
                    $(sel).html(html);
                }

                function formatDurationSeconds(sec) {
                    const d = moment.duration(sec, 'seconds');
                    const h = Math.floor(d.asHours());
                    const m = d.minutes();
                    const s = d.seconds();
                    return `${h}h ${m}m ${s}s`;
                }

                function formatDurationMinutes(min) {
                    const h = Math.floor(min / 60);
                    const m = min % 60;
                    return `${h}h ${m}m`;
                }
                // Export Excel/PDF for Project
                $("#btnExportExcelProject").on("click", function() {
                    function doExport() {
                        if (typeof window.XLSX === "undefined") {
                            alert('ไม่พบไลบรารี XLSX');
                            return;
                        }
                        const wb = window.XLSX.utils.table_to_book(document.getElementById('reportTableProject'), {
                            sheet: "Report"
                        });
                        window.XLSX.writeFile(wb, `report_project_${moment().format('YYYYMMDD_HHmm')}.xlsx`);
                    }
                    if (typeof window.XLSX === "undefined") {
                        const script = document.querySelector('script[src*="xlsx.full.min.js"]');
                        if (script) {
                            script.addEventListener('load', doExport, {
                                once: true
                            });
                        } else {
                            alert('ไม่พบไฟล์ xlsx.full.min.js');
                        }
                    } else {
                        doExport();
                    }
                });
                $("#btnPrintPDFProject").on("click", function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF('l', 'mm', 'a4');
                    doc.autoTable({
                        html: '#reportTableProject',
                        startY: 10,
                        styles: {
                            fontSize: 8
                        }
                    });
                    doc.save(`report_project_${moment().format('YYYYMMDD_HHmm')}.pdf`);
                });
                // Export Excel/PDF for Monthly
                $("#btnExportExcelMonthly").on("click", function() {
                    function doExport() {
                        if (typeof window.XLSX === "undefined") {
                            alert('ไม่พบไลบรารี XLSX');
                            return;
                        }
                        const wb = window.XLSX.utils.table_to_book(document.getElementById('reportTableMonthly'), {
                            sheet: "Report"
                        });
                        window.XLSX.writeFile(wb, `report_monthly_${moment().format('YYYYMMDD_HHmm')}.xlsx`);
                    }
                    if (typeof window.XLSX === "undefined") {
                        const script = document.querySelector('script[src*="xlsx.full.min.js"]');
                        if (script) {
                            script.addEventListener('load', doExport, {
                                once: true
                            });
                        } else {
                            alert('ไม่พบไฟล์ xlsx.full.min.js');
                        }
                    } else {
                        doExport();
                    }
                });
                $("#btnPrintPDFMonthly").on("click", function() {
                    const {
                        jsPDF
                    } = window.jspdf;
                    const doc = new jsPDF('l', 'mm', 'a4');
                    doc.autoTable({
                        html: '#reportTableMonthly',
                        startY: 10,
                        styles: {
                            fontSize: 8
                        }
                    });
                    doc.save(`report_monthly_${moment().format('YYYYMMDD_HHmm')}.pdf`);
                });
                // Default: show project report
                $("#projectReportForm").trigger('submit');
            });
        </script>
    </div>
</body>

</html>