$(document).ready(function () {
  let timeLogsTable;
  let currentTimelogId = null;
  let timerInterval = null;
  let elapsedSeconds = 0;
  let timerRunning = false;
  let durationInterval = null;
  let editDurationInterval = null;

  function formatDurationFromSeconds(seconds) {
    const duration = moment.duration(seconds, 'seconds');
    const hrs = Math.floor(duration.asHours());
    const mins = duration.minutes();
    const secs = duration.seconds();
    return (
      (hrs ? hrs + "h " : "") +
      (mins ? mins + "m " : "") +
      (secs ? secs + "s" : "")
    ).trim() || "0s";
  }

  function formatDuration(start, end) {
    const duration = moment.duration(end.diff(start));
    const hrs = Math.floor(duration.asHours());
    const mins = duration.minutes();
    const secs = duration.seconds();
    return (
      (hrs ? hrs + "h " : "") +
      (mins ? mins + "m " : "") +
      (secs ? secs + "s" : "")
    ).trim() || "0s";
  }

  $("#selectProject").select2({
    width: "100%",
    dropdownParent: $("#modalTimer"),
  });

  timeLogsTable = $("#timeLogsTable").DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    ajax: {
      url: "../../service/timetracking/get_timelog.php",
      type: "GET",
      dataSrc: "data",
    },
    columns: [
      {
        data: null,
        title: "No.",
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
        className: "text-center",
        width: "40px",
      },
      {
        data: "start_time",
        title: "Date",
        render: function (data) {
          return moment(data).format("DD/MM/YYYY HH:mm");
        },
      },
      { data: "project_name", title: "Project" },
      { data: "drawing_no", title: "Drawing No." },
      {
        data: "activity_type",
        title: "Activity",
        render: function (data) {
          const activities = {
            design: "Design",
            review: "Review",
            modify: "Modify",
            meeting: "Meeting",
            other: "Other",
          };
          return activities[data] || data;
        },
      },
      {
        data: null,
        title: "Duration",
        render: function (data, type, row) {
          // 1. If duration field exists, use it (Paused/Finished)
          if (row.duration && row.duration > 0) {
            return formatDurationFromSeconds(row.duration);
          }
          // 2. If end_time exists, calculate from start_time to end_time
          if (row.end_time && row.start_time) {
            const start = moment(row.start_time);
            const end = moment(row.end_time);
            return formatDuration(start, end);
          }
          // 3. If status is active, show real-time
          if (row.status === "active" && row.start_time && !row.end_time) {
            const start = moment(row.start_time);
            const now = moment();
            return formatDuration(start, now) + " (in progress)";
          }
          return "-";
        },
      },
      {
        data: "note",
        title: "Note",
        render: function (data) {
          return data || "-";
        },
      },
      {
        data: "status",
        title: "Status",
        render: function (data) {
          const statusClass =
            {
              active: "badge-primary",
              completed: "badge-success",
              paused: "badge-warning",
            }[data] || "badge-secondary";
          const statusText =
            {
              active: "Processing",
              completed: "Finished",
              paused: "Pause",
            }[data] || data;
          return `<span class="badge ${statusClass}">${statusText}</span>`;
        },
      },
      {
        data: null,
        title: "Action",
        orderable: false,
        render: function (data, type, row, meta) {
          return `<button class="btn btn-sm btn-warning btn-edit" 
            data-id="${row.id}" 
            data-status="${row.status}" 
            data-note="${row.note || ""}">
            <i class="fas fa-edit"></i> Edit</button>`;
        },
        className: "text-center",
        width: "80px",
      }
    ],
    order: [[1, "desc"]],
    pageLength: 10
  });

  // Real-time update Duration in table (only for Processing)
  function startDurationUpdater() {
    if (durationInterval) clearInterval(durationInterval);
    durationInterval = setInterval(function () {
      timeLogsTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
        const row = this.data();
        // Only update if status is active and no end_time/duration
        if (row.status === "active" && row.start_time && !row.end_time && (!row.duration || row.duration == 0)) {
          const start = moment(row.start_time);
          const now = moment();
          const durationText = formatDuration(start, now) + " (in progress)";
          $(timeLogsTable.cell(rowIdx, 5).node()).html(durationText);
        }
      });
    }, 1000);
  }
  timeLogsTable.on('draw', function () {
    startDurationUpdater();
  });
  startDurationUpdater();

  // Responsive fix after modal close
  $("#modalTimer").on("hidden.bs.modal", function () {
    setTimeout(function () {
      timeLogsTable.columns.adjust().responsive.recalc();
    }, 200);
  });

  // Load Projects when modal opens
  $("#modalTimer").on("shown.bs.modal", function () {
    loadProjects();
    $("#timerDisplay").text(moment().format("HH:mm:ss"));
  });

  function loadProjects() {
    $.ajax({
      url: "../../service/timetracking/get_projects.php",
      type: "GET",
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          let options = '<option value="">-- Select Project --</option>';
          res.data.forEach(function (project) {
            options += `<option value="${project.id}">${project.name}</option>`;
          });
          $("#selectProject").html(options);
        } else {
          Swal.fire("Error", "Cannot load project data", "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Error loading project data", "error");
      },
    });
  }

  $("#inputDrawing").on("input", function () {
    if ($(this).val().trim() === "") {
      $(this).addClass("is-invalid");
    } else {
      $(this).removeClass("is-invalid");
    }
  });

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

  function updateUIOnStart() {
    $("#btnStart").prop("disabled", true);
    $("#btnPause, #btnStop").prop("disabled", false);
    $("#selectProject, #inputDrawing, #selectActivity").prop("disabled", true);
    $("#statusBadge")
      .removeClass()
      .addClass("badge badge-success")
      .text("Processing");
  }

  function updateUIOnPause() {
    $("#btnPause").prop("disabled", true);
    $("#btnStart").prop("disabled", false);
    $("#statusBadge").removeClass().addClass("badge badge-warning").text("Pause");
  }

  $("#btnStart").click(function () {
    const projectId = $("#selectProject").val();
    const drawingNo = $("#inputDrawing").val().trim();
    const activityType = $("#selectActivity").val();
    const note = $("#inputNote").val().trim();

    if (!projectId) {
      Swal.fire("Warning", "Please select a project", "warning");
      return;
    }
    if (!drawingNo) {
      $("#inputDrawing").addClass("is-invalid");
      Swal.fire("Warning", "Please enter Drawing No.", "warning");
      return;
    } else {
      $("#inputDrawing").removeClass("is-invalid");
    }

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
      success: function (res) {
        if (res.status === "success") {
          currentTimelogId = res.data.id;
          startTimer();
          updateUIOnStart();
          timeLogsTable.ajax.reload();
          Swal.fire("Success", "Timer started", "success");
        } else {
          Swal.fire("Error", res.message, "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Cannot start timer", "error");
      },
    });
  });

  $("#btnPause").click(function () {
    if (!currentTimelogId) return;
    $.ajax({
      url: "../../service/timetracking/pause_timer.php",
      type: "POST",
      data: { timelog_id: currentTimelogId },
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          stopTimer();
          updateUIOnPause();
          timeLogsTable.ajax.reload();
        } else {
          Swal.fire("Error", res.message || "Error occurred", "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Cannot pause timer", "error");
      },
    });
  });

  $("#btnStop").click(function () {
    if (!currentTimelogId) return;
    Swal.fire({
      title: "Confirm Save",
      text: "Do you want to save this time log?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Save",
      cancelButtonText: "Cancel",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../service/timetracking/stop_timer.php",
          type: "POST",
          data: {
            timelog_id: currentTimelogId,
            note: $("#inputNote").val().trim(),
          },
          dataType: "json",
          success: function (res) {
            if (res.status === "success") {
              stopTimer();
              resetForm();
              $("#modalTimer").modal("hide");
              timeLogsTable.ajax.reload();
              Swal.fire("Success", "Time log saved", "success");
            } else {
              Swal.fire("Error", res.message || "Error occurred", "error");
            }
          },
          error: function () {
            Swal.fire("Error", "Cannot save time log", "error");
          },
        });
      }
    });
  });

  // Edit button: open Edit Status modal and show real-time duration
  $("#timeLogsTable").on("click", ".btn-edit", function () {
    const id = $(this).data("id");
    const status = $(this).data("status");
    const note = $(this).data("note");
    const rowData = timeLogsTable.row($(this).closest('tr')).data();

    $("#editTimelogId").val(id);
    $("#editStatus").val(status);
    $("#editNote").val(note);

    function updateEditDuration() {
      let durationText = "-";
      if (rowData) {
        if (rowData.duration && rowData.duration > 0) {
          durationText = formatDurationFromSeconds(rowData.duration);
        } else if (rowData.end_time && rowData.start_time) {
          const start = moment(rowData.start_time);
          const end = moment(rowData.end_time);
          durationText = formatDuration(start, end);
        } else if (rowData.status === "active" && rowData.start_time) {
          const start = moment(rowData.start_time);
          const now = moment();
          durationText = formatDuration(start, now) + " (in progress)";
        }
      }
      $("#editDuration").text(durationText);
    }

    // Start interval if Processing
    if (rowData.status === "active" && rowData.start_time && !rowData.end_time && (!rowData.duration || rowData.duration == 0)) {
      updateEditDuration();
      if (editDurationInterval) clearInterval(editDurationInterval);
      editDurationInterval = setInterval(updateEditDuration, 1000);
    } else {
      updateEditDuration();
      if (editDurationInterval) clearInterval(editDurationInterval);
    }

    $("#modalEditStatus").modal("show");
  });

  // clear interval when modal closes
  $("#modalEditStatus").on("hidden.bs.modal", function () {
    if (editDurationInterval) clearInterval(editDurationInterval);
  });

  // Save status change
  $("#formEditStatus").submit(function (e) {
    e.preventDefault();
    const id = $("#editTimelogId").val();
    const status = $("#editStatus").val();
    const note = $("#editNote").val();
    $.ajax({
      url: "../../service/timetracking/update_status.php",
      type: "POST",
      data: { timelog_id: id, status: status, note: note },
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          $("#modalEditStatus").modal("hide");
          timeLogsTable.ajax.reload();
          Swal.fire("Success", "Status updated", "success");
        } else {
          Swal.fire("Error", res.message, "error");
        }
      },
      error: function () {
        Swal.fire("Error", "Cannot update status", "error");
      },
    });
  });

  function resetForm() {
    currentTimelogId = null;
    elapsedSeconds = 0;
    stopTimer();
    $("#selectProject").val("").trigger("change");
    $("#inputDrawing").val("").removeClass("is-invalid");
    $("#selectActivity").val("design");
    $("#inputNote").val("");
    $("#timerDisplay").text("00:00:00");
    $("#statusBadge")
      .removeClass()
      .addClass("badge badge-secondary")
      .text("Not started");
    $("#btnStart").prop("disabled", false);
    $("#btnPause, #btnStop").prop("disabled", true);
    $("#selectProject, #inputDrawing, #selectActivity").prop("disabled", false);
  }
});