$(document).ready(function () {
  // Initialize variables
  let timeLogsTable;
  let currentTimelogId = null;
  let timerInterval = null;
  let elapsedSeconds = 0;
  let timerRunning = false;

  // Initialize Select2
  $("#selectProject").select2({
    width: "100%",
    dropdownParent: $("#modalTimer"),
  });

  // Initialize DataTable
  timeLogsTable = $("#timeLogsTable").DataTable({
    processing: true,
    serverSide: false,
    ajax: {
      url: "../../service/timetracking/get_timelog.php",
      type: "GET",
      dataSrc: "data",
      error: function (xhr, error, thrown) {
        console.error("DataTable error:", error);
        $(".dataTables_processing").hide();
      },
    },
    columns: [
      {
        data: "start_time",
        title: "วันที่",
        render: function (data) {
          return moment(data).format("DD/MM/YYYY HH:mm");
        },
      },
      { data: "project_name", title: "โครงการ" },
      { data: "drawing_no", title: "Drawing No." },
      {
        data: "activity_type",
        title: "กิจกรรม",
        render: function (data) {
          const activities = {
            design: "ออกแบบ",
            review: "ทบทวนแบบ",
            modify: "แก้ไขแบบ",
            meeting: "ประชุม",
            other: "อื่นๆ",
          };
          return activities[data] || data;
        },
      },
      {
        data: "duration_minutes",
        title: "เวลาที่ใช้",
        render: function (data) {
          if (!data) return "-";
          const hrs = Math.floor(data / 60);
          const mins = data % 60;
          return (hrs ? hrs + " ชม. " : "") + (mins ? mins + " นาที" : "");
        },
      },
      {
        data: "note",
        title: "หมายเหตุ",
        render: function (data) {
          return data || "-";
        },
      },
      {
        data: "status",
        title: "สถานะ",
        render: function (data) {
          const statusClass =
            {
              active: "badge-primary",
              completed: "badge-success",
              paused: "badge-warning",
            }[data] || "badge-secondary";

          const statusText =
            {
              active: "กำลังทำงาน",
              completed: "เสร็จสิ้น",
              paused: "พัก",
            }[data] || data;

          return `<span class="badge ${statusClass}">${statusText}</span>`;
        },
      },
    ],
    order: [[0, "desc"]],
    pageLength: 10,
    language: {
      url: "../../plugins/datatables/i18n/th.json",
    },
  });

  // Load Projects when modal opens
  $("#modalTimer").on("shown.bs.modal", function () {
    loadProjects();
  });

  // Load Projects Function
  function loadProjects() {
    console.log("Loading projects...");
    $.ajax({
      url: "../../service/timetracking/get_projects.php",
      type: "GET",
      dataType: "json",
      success: function (res) {
        console.log("Projects response:", res);
        if (res.status === "success") {
          let options = '<option value="">-- เลือกโครงการ --</option>';
          res.data.forEach(function (project) {
            options += `<option value="${project.id}">${project.name}</option>`;
          });
          $("#selectProject").html(options);
        } else {
          console.error("Failed to load projects:", res.message);
          Swal.fire("Error", "ไม่สามารถโหลดข้อมูลโครงการได้", "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Ajax error:", error);
        console.error("Response:", xhr.responseText);
        Swal.fire("Error", "เกิดข้อผิดพลาดในการโหลดข้อมูล", "error");
      },
    });
  }

  // Add debug for project loading
  $("#selectProject").on("change", function () {
    console.log("Selected project:", {
      id: $(this).val(),
      text: $(this).find("option:selected").text(),
    });
  });

  // Timer Functions
  function updateTimerDisplay() {
    const hrs = Math.floor(elapsedSeconds / 3600);
    const mins = Math.floor((elapsedSeconds % 3600) / 60);
    const secs = elapsedSeconds % 60;

    const display =
      (hrs < 10 ? "0" : "") +
      hrs +
      ":" +
      (mins < 10 ? "0" : "") +
      mins +
      ":" +
      (secs < 10 ? "0" : "") +
      secs;

    $("#timerDisplay").text(display);
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

  // UI Update Functions
  function updateUIOnStart() {
    $("#btnStart").prop("disabled", true);
    $("#btnPause, #btnStop").prop("disabled", false);
    $("#selectProject, #inputDrawing, #selectActivity").prop("disabled", true);
    $("#statusBadge")
      .removeClass()
      .addClass("badge badge-success")
      .text("กำลังทำงาน");
  }

  function updateUIOnPause() {
    $("#btnPause").prop("disabled", true);
    $("#btnStart").prop("disabled", false);
    $("#statusBadge").removeClass().addClass("badge badge-warning").text("พัก");
  }

  // Start Timer Button - แก้ไขการส่งข้อมูล
  $("#btnStart").click(function () {
    // Get form data with validation
    const projectId = $("#selectProject").val();
    const drawingNo = $("#inputDrawing").val().trim();
    const activityType = $("#selectActivity").val();
    const note = $("#inputNote").val().trim();

    console.log("Form values before sending:", {
      projectId: projectId,
      drawingNo: drawingNo,
      activityType: activityType,
      note: note,
    });

    // Validation
    if (!projectId) {
      Swal.fire("แจ้งเตือน", "กรุณาเลือกโครงการ", "warning");
      return;
    }

    if (!drawingNo) {
      Swal.fire("แจ้งเตือน", "กรุณาระบุ Drawing No.", "warning");
      return;
    }

    // Send with proper content type
    $.ajax({
      url: "../../service/timetracking/start_timer.php",
      type: "POST",
      contentType: "application/x-www-form-urlencoded; charset=UTF-8",
      data: {
        project_id: projectId,
        drawing_no: drawingNo,
        activity_type: activityType,
        note: note,
      },
      dataType: "json",
      beforeSend: function () {
        console.log("Sending AJAX request...");
      },
      success: function (res) {
        console.log("Success response:", res);
        if (res.status === "success") {
          currentTimelogId = res.data.id;
          startTimer();
          updateUIOnStart();
          timeLogsTable.ajax.reload();
          Swal.fire("สำเร็จ", "เริ่มจับเวลาแล้ว", "success");
        } else {
          Swal.fire("Error", res.message, "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error Details:", {
          status: status,
          error: error,
          responseText: xhr.responseText,
          readyState: xhr.readyState,
          statusText: xhr.statusText,
        });
        Swal.fire("Error", "ไม่สามารถเริ่มจับเวลาได้", "error");
      },
    });
  });

  // Pause Timer Button
  $("#btnPause").click(function () {
    console.log("Pause button clicked");
    if (!currentTimelogId) return;

    $.ajax({
      url: "../../service/timetracking/pause_timer.php",
      type: "POST",
      data: { timelog_id: currentTimelogId },
      dataType: "json",
      success: function (res) {
        console.log("Pause timer response:", res);
        if (res.status === "success") {
          stopTimer();
          updateUIOnPause();
          timeLogsTable.ajax.reload();
          console.log("Timer paused successfully");
        } else {
          console.error("Pause timer failed:", res.message);
          Swal.fire("Error", res.message || "เกิดข้อผิดพลาด", "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Pause timer error:", error);
        Swal.fire("Error", "ไม่สามารถหยุดจับเวลาชั่วคราว", "error");
      },
    });
  });

  // Stop Timer Button
  $("#btnStop").click(function () {
    console.log("Stop button clicked");
    if (!currentTimelogId) return;

    Swal.fire({
      title: "ยืนยันการบันทึก",
      text: "ต้องการบันทึกเวลาการทำงานนี้ใช่หรือไม่?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "บันทึก",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        console.log("Stop confirmed");
        $.ajax({
          url: "../../service/timetracking/stop_timer.php",
          type: "POST",
          data: {
            timelog_id: currentTimelogId,
            note: $("#inputNote").val().trim(),
          },
          dataType: "json",
          success: function (res) {
            console.log("Stop timer response:", res);
            if (res.status === "success") {
              stopTimer();
              resetForm();
              $("#modalTimer").modal("hide");
              timeLogsTable.ajax.reload();
              Swal.fire("สำเร็จ", "บันทึกเวลาเรียบร้อย", "success");
              console.log("Timer stopped successfully");
            } else {
              console.error("Stop timer failed:", res.message);
              Swal.fire("Error", res.message || "เกิดข้อผิดพลาด", "error");
            }
          },
          error: function (xhr, status, error) {
            console.error("Stop timer error:", error);
            Swal.fire("Error", "ไม่สามารถบันทึกเวลาได้", "error");
          },
        });
      }
    });
  });

  // Reset form when modal closes
  $("#modalTimer").on("hidden.bs.modal", function () {
    if (!timerRunning) {
      resetForm();
    }
  });

  // Reset form function
  function resetForm() {
    console.log("Resetting form");
    currentTimelogId = null;
    elapsedSeconds = 0;
    stopTimer();
    $("#selectProject").val("").trigger("change");
    $("#inputDrawing").val("");
    $("#selectActivity").val("design");
    $("#inputNote").val("");
    $("#timerDisplay").text("00:00:00");
    $("#statusBadge")
      .removeClass()
      .addClass("badge badge-secondary")
      .text("ยังไม่เริ่ม");
    $("#btnStart").prop("disabled", false);
    $("#btnPause, #btnStop").prop("disabled", true);
    $("#selectProject, #inputDrawing, #selectActivity").prop("disabled", false);
    console.log("Form reset complete");
  }
});
