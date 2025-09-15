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
    <title>Project Code Management</title>
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include_once('../includes/sidebar.php') ?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0"><i class="fas fa-project-diagram"></i> Project Code</h1>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <button id="btnSyncProjectCode" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Sync Project Code</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="projectTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Project Code</th>
                                        <th>Customer Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded by JS -->
                                </tbody>
                            </table>
                        </div>
                        <button type="button" id="btnExportExcel" class="btn btn-success mt-2">Export Excel</button>
                        <button type="button" id="btnPrintPDF" class="btn btn-secondary mt-2">Print PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script src="../../assets/js/moment.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <script src="../../assets/js/jspdf.umd.min.js"></script>
    <script src="../../assets/js/jspdf.plugin.autotable.min.js"></script>
    <script>
    $(function() {
        let projectTable = $("#projectTable").DataTable({
            searching: false,
            paging: true,
            pageLength: 25
        });

        function loadProjects() {
            $.getJSON("../../service/report/project_list.php", function(res) {
                if (res.status === "success") {
                    projectTable.clear();
                    res.data.forEach((p, i) => {
                        projectTable.row.add([
                            i+1,
                            p.code || '-',
                            p.client_name || '-'
                        ]);
                    });
                    projectTable.draw();
                }
            });
        }
        loadProjects();

        // Sync Project Code
        $('#btnSyncProjectCode').on('click', function() {
            Swal.fire({
                title: 'Sync Project Code?',
                text: 'This will import/update project code from NAV. Continue?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Sync',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('../../service/projects/sync_project_code.php', function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Success', res.message, 'success');
                            loadProjects();
                        } else {
                            Swal.fire('Error', res.message || 'Sync failed', 'error');
                        }
                    },'json').fail(function(){
                        Swal.fire('Error', 'Sync failed', 'error');
                    });
                }
            });
        });

        // Export Excel
        $("#btnExportExcel").on("click", function() {
            // SheetJS อาจใช้ async load จาก CDN ต้องรอ window.XLSX พร้อมใช้งาน
            function doExport() {
                // SheetJS CDN จะประกาศ window.XLSX เสมอ
                if (typeof window.XLSX === "undefined") {
                    alert('ไม่พบไลบรารี XLSX');
                    return;
                }
                const wb = window.XLSX.utils.table_to_book(document.getElementById('projectTable'), {sheet: "Projects"});
                window.XLSX.writeFile(wb, `projects_${moment().format('YYYYMMDD_HHmm')}.xlsx`);
            }
            // ถ้า window.XLSX ยังไม่พร้อม ให้รอโหลด script ให้เสร็จก่อน
            if (typeof window.XLSX === "undefined") {
                const script = document.querySelector('script[src*="xlsx.full.min.js"]');
                if (script) {
                    script.addEventListener('load', doExport, { once: true });
                } else {
                    alert('ไม่พบไฟล์ xlsx.full.min.js');
                }
            } else {
                doExport();
            }
        });

        // Print PDF
        $("#btnPrintPDF").on("click", function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            doc.autoTable({ html: '#projectTable', startY: 10, styles: { fontSize: 8 } });
            doc.save(`projects_${moment().format('YYYYMMDD_HHmm')}.pdf`);
        });
    });
    </script>
</div>
</body>
</html>