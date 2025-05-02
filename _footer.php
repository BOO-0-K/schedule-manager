    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(function(){
            $("#participants").select2({
                dropdownParent: $('#modal'),
                placeholder: '선택',
            });

            $('#logoutBtn').on("click", function(event) {
                $.ajax({
                    type: "post",
                    url: "/engine/logout.php",
                    success: function(resp) {
                        if (resp.code == 0) {
                            window.location.href = "/login.php";
                        }
                    }
                });
            });

            $('#delBtn').on("click", function(event) {
                let scheduleIdx = $("#idx").val();
                if (confirm("삭제하시겠습니까?")) {
                    $.ajax({
                        type: "post",
                        url: "/engine/deleteSchedule.php",
                        data: {
                            idx: scheduleIdx,
                        },
                        success: function(resp) {
                            if (resp.code == 0) {
                                alert("삭제되었습니다.");
                                location.reload();
                            } else {
                                let errorMsg = resp.msg.ko;
                                alert(errorMsg);
                            }
                        }
                    });
                }
            });
            
            $('#copyBtn').on("click", function(event) {
                if (confirm("복사하시겠습니까?")) {
                    let scheduleIdx = $("#idx").val();
                    $.ajax({
                        type: "post",
                        url: "/engine/copySchedule.php",
                        data: {
                            idx: scheduleIdx,
                        },
                        success: function(resp) {
                            if (resp.code == 0) {
                                alert("복사되었습니다.");
                                location.reload();
                            } else if (resp.code == -2) {
                                let errorMsg = resp.data;
                                alert(errorMsg);
                            } else {
                                let errorMsg = resp.msg.ko;
                                alert(errorMsg);
                            }
                        }
                    });
                }
            });

            $('#saveBtn').on("click", function(event) {
                let scheduleIdx = $("#idx").val();
                if (scheduleIdx) {
                    $.ajax({
                        type: "post",
                        url: "/engine/updateSchedule.php",
                        data: {
                            idx: scheduleIdx,
                            title: $("#title").val(),
                            location: $("#location").val(),
                            type: $("input[name='type']:checked").val(),
                            start_date: $("#startDate").val(),
                            start_time: $("#startTime").val(),
                            end_date: $("#endDate").val(),
                            end_time: $("#endTime").val(),
                            participants: JSON.stringify($("#participants").val()),
                        },
                        success: function(resp) {
                            if (resp.code == 0) {
                                alert("수정되었습니다.");
                                location.reload();
                            } else if (resp.code == -2) {
                                let errorMsg = resp.data;
                                alert(errorMsg);
                            } else {
                                let errorMsg = resp.msg.ko;
                                alert(errorMsg);
                            }
                        }
                    });
                } else {
                    $.ajax({
                        type: "post",
                        url: "/engine/createSchedule.php",
                        data: {
                            title: $("#title").val(),
                            location: $("#location").val(),
                            type: $("input[name='type']:checked").val(),
                            start_date: $("#startDate").val(),
                            start_time: $("#startTime").val(),
                            end_date: $("#endDate").val(),
                            end_time: $("#endTime").val(),
                            participants: JSON.stringify($("#participants").val()),
                        },
                        success: function(resp) {
                            if (resp.code == 0) {
                                alert("저장되었습니다.");
                                location.reload();
                            } else if (resp.code == -2) {
                                let errorMsg = resp.data;
                                alert(errorMsg);
                            } else {
                                let errorMsg = resp.msg.ko;
                                alert(errorMsg);
                            }
                        }
                    });
                }
            });

            let calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                selectHelper: true,
                editable: false,
                eventLimit: true,
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: '/engine/getSchedules.php',
                        type: 'POST',
                        data: {
                            start: start.format("YYYY-MM-DD"),
                            end: end.format("YYYY-MM-DD"),
                        },
                        success: function(resp) {
                            let events = resp.data;
                            callback(events);
                        }
                    });
                },
                select: function(start, end) {
                    $("#saveBtn").show();
                    $("#delBtn").hide();
                    $("#copyBtn").hide();
                    $("#idx").val("");
                    $("#author").val($("#username").val());
                    $("#title").val("");
                    $("#location").val("");
                    $("#type1").prop("checked", true);
                    $('#participants').val(null).trigger('change');
                    $('#startDate, #endDate').datepicker({
                        dateFormat: 'yy-mm-dd',
                    })
                    $('#startTime, #endTime').timepicker({
                        timeFormat: 'HH:mm',
                        interval: 10,
                        minTime: '10',
                        maxTime: '6:00pm',
                        defaultTime: '10',
                        startTime: '10:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        zindex: 9999,
                    });
                    $('#startDate').datepicker('setDate', start.format("YYYY-MM-DD"));
                    $('#endDate').datepicker('setDate', end.format("YYYY-MM-DD"));
                    $('#startTime').timepicker('setTime', start.format("HH:mm"));
                    $('#endTime').timepicker('setTime', end.format("HH:mm"));
                    const myModal = new bootstrap.Modal('#modal');
                    myModal.show();
                },
                eventClick: function(info) {
                    if (info.role == true) {
                        $("#saveBtn").show();
                        $("#delBtn").show();
                        $("#copyBtn").show();
                    } else {
                        $("#saveBtn").hide();
                        $("#delBtn").hide();
                        $("#copyBtn").show();
                    }
                    $("#idx").val(info.idx);
                    $("#author").val(info.username);
                    $("#title").val(info.title);
                    $("#location").val(info.location);
                    $('input:radio[name=type]:input[value='+info.type +']').attr("checked",true);
                    $('#participants').val(null).trigger('change');
                    $('#startDate, #endDate').datepicker({
                        dateFormat: 'yy-mm-dd',
                    })
                    $('#startTime, #endTime').timepicker({
                        timeFormat: 'HH:mm',
                        interval: 10,
                        minTime: '10',
                        maxTime: '6:00pm',
                        defaultTime: '10',
                        startTime: '10:00',
                        dynamic: false,
                        dropdown: true,
                        scrollbar: true,
                        zindex: 9999,
                    });
                    $('#startDate').datepicker('setDate', info.start.local().toDate());
                    $('#endDate').datepicker('setDate', info.end.local().toDate());
                    $('#startTime').timepicker('setTime', info.start.format("HH:mm"));
                    $('#endTime').timepicker('setTime', info.end.format("HH:mm"));
                    $.ajax({
                        url: '/engine/getSchedule.php',
                        type: 'POST',
                        data: {
                            idx: info.idx,
                        },
                        success: function(resp) {
                            $("#participants").val(Object.values(resp.data)).trigger('change');
                        }
                    });
                    const myModal = new bootstrap.Modal('#modal');
                    myModal.show();
                }
            });

            const ctx = document.getElementById('myChart');
            let myChart = null;
            if (ctx) {
                $('#monthSelect').on('change', function () {
                    const month = $(this).val();
                    const range = getMonthRange(month);

                    $.ajax({
                        url: '/engine/getStats.php',
                        type: 'POST',
                        data: {
                            start: range.start,
                            end: range.end,
                        },
                        success: function(resp) {
                            const counts = { "1": 0, "2": 0, "3": 0, "4": 0 };

                            resp.data.forEach(item => {
                                if (counts.hasOwnProperty(item.type)) {
                                    counts[item.type] = parseInt(item.count);
                                }
                            });

                            const chartData = [
                                counts["1"], // 일반
                                counts["2"], // 교육
                                counts["3"], // 세미나
                                counts["4"]  // 회식
                            ];

                            if (myChart) {
                                myChart.destroy();
                            }
                            
                            myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                labels: ['일반', '교육', '세미나', '회식'],
                                datasets: [{
                                    label: '# of Schedules',
                                    data: chartData,
                                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                                    borderWidth: 1
                                }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                        beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    });
                });
            }

            const defaultMonth = $('#monthSelect').val();
            if (defaultMonth) {
                $('#monthSelect').trigger('change');
            }

            function getMonthRange(monthStr) {
                const startDate = monthStr + '-01';

                const parts = monthStr.split('-');
                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);

                const endDate = new Date(year, month, 0);
                const endDateStr = endDate.toISOString().slice(0, 10);

                return {
                    start: startDate,
                    end: endDateStr
                };
            }
        });
    </script>
</body>
</html>