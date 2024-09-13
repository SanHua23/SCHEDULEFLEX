<?php include "../connect.php"; ?>

<!DOCTYPE html>
<html>
<!-- header link -->
<?php include "plugins-header.php"; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
<!-- JS for jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- JS for full calender -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<!-- bootstrap css and js -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<body>
    <div class="main-div">
        <!-- sidebar and topbar start -->
        <?php include "sidebar.php"; ?>
        <!-- sidebar and topbar start -->

        <!-- content container start -->
        <div class="content-div p-4">
            <div class="row row-gap-3">
                <div class="col-12 d-flex flex-column row-gap-2">
                    <div class="border bg-white d-flex flex-column row-gap-2 p-3">
                        <div >
                            <strong class="mb-2">List of F2F Schedule</strong>
                            <div class="row my-2 justify-content-between">
                                <form method="GET" action="f2f_print.php" class="col-12 col-lg-6 d-flex flex-column flex-lg-row align-items-center gap-2" target="_blank">
                                    <select required class="form-select form-select-sm rounded-1" name="course_id" id="course_id_filter">
                                        <option hidden selected value="">Choose Course</option>
                                        <?php
                                        // PHP code to fetch courses from database
                                        $sql = "SELECT * FROM course_tbl";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rows) {
                                            foreach ($rows as $row):
                                                ?>
                                                <option value="<?php echo $row['course_id']; ?>"><?php echo $row['course_name']; ?></option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>
                                    <select required class="form-select form-select-sm rounded-1" name="semester" id="semester">
                                        <option hidden selected value="">Choose Semester</option>
                                        <option value="1">1st semester</option>
                                        <option value="2">2nd semester</option>
                                    </select>

                                    <select required class="form-select form-select-sm rounded-1" name="ay_id" id="ay_id">
                                        <option hidden selected value="">Choose Academic Year</option>
                                        <?php
                                        // PHP code to fetch courses from database
                                        $sql = "SELECT * FROM ay_tbl";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if ($rows) {
                                            foreach ($rows as $row):
                                                ?>
                                                <option value="<?php echo $row['ay_id']; ?>"><?php echo $row['start_year'] . " - " . $row['end_year']; ?></option>
                                            <?php
                                            endforeach;
                                        }
                                        ?>
                                    </select>

                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap me-auto me-lg-0"><i class="fa-regular fa-folder"></i> Generate</button>
                                </form>

                                <div class="col-12 col-lg-6 d-flex mt-2 mt-lg-0 px-0">
                                    <form class="col col-lg-5 d-flex align-items-center column-gap-2 ms-auto">
                                        <select required class="form-select form-select-sm rounded-1 me-auto" name="ay_id" id="ay_id">
                                            <option hidden selected value="">Choose Academic Year</option>
                                            <option value="">Select All</option>
                                            <?php
                                            // PHP code to fetch courses from database
                                            $sql = "SELECT * FROM ay_tbl";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->execute();
                                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($rows) {
                                                foreach ($rows as $row):
                                                    ?>
                                                    <option value="<?php echo $row['ay_id']; ?>"><?php echo $row['start_year'] . " - " . $row['end_year']; ?></option>
                                                <?php
                                                endforeach;
                                            }
                                            ?>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary text-nowrap"><i class="fa-solid fa-filter"></i> Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-lg-3 mt-2 px-0">
                                <input type="text" id="search-bar" class="form-control form-control-sm rounded-1 py-2 " placeholder="Search here">
                            </div>
                        </div>
                        
                        <div style="max-height: 70vh; overflow-y: auto;">
                            <table id="schedule_table" class="table table-bordered table-striped">
                                <thead class="position-sticky top-0">
                                    <tr class="py-5">
                                        <th>#</th>
                                        <th>Time & Date</th>
                                        <th>Subject</th>
                                        <th>Yr. & Sec.</th>
                                        <th>Room</th>
                                        <th>Course</th>
                                        <th>Teacher</th>
                                        <th>Sem</th>
                                        <th>AY</th>
                                        <th>Action</th>
                                        <!-- <th class="col-auto text-center">Action</th> -->
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                        $ay_id = isset($_GET['ay_id']) ? $_GET['ay_id'] : null;

                                        $sql = "SELECT * FROM schedule_f2f_tbl sft 
                                                INNER JOIN schedule_setup_tbl sst ON sst.schedule_setup_id = sft.schedule_setup_id
                                                INNER JOIN course_tbl c ON c.course_id = sst.course_id
                                                INNER JOIN section_tbl sec ON sec.section_id = sst.section_id
                                                INNER JOIN room_tbl r ON r.room_id = sft.room_id
                                                INNER JOIN faculty_tbl f ON f.faculty_id = sst.faculty_id
                                                INNER JOIN subject_tbl sub ON sub.subject_id = sst.subject_id
                                                INNER JOIN ay_tbl ay ON ay.ay_id = sst.ay_id";
                                        
                                        if ($ay_id !== null && $ay_id !== "") {
                                            $sql .= " WHERE sst.ay_id = :ay_id";
                                        }

                                        $stmt = $conn->prepare($sql);

                                        if ($ay_id !== null && $ay_id !== "") {
                                            $stmt->bindParam(":ay_id", $ay_id, PDO::PARAM_INT);
                                        }

                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        $row_count = 1;
                                        if ($rows) {
                                            foreach ($rows as $row):
                                    ?>
                                    <tr data-id="<?php echo $row['schedule_f2f_id']; ?>">
                                        <td width="50"><?php echo $row_count++?></td>
                                        <td>
                                            <?php echo date("F d, Y h:i a", strtotime($row['start_datetime'])); ?><br><?php echo date("F d, Y h:i a", strtotime($row['end_datetime'])); ?>
                                            
                                        </td>
                                        <td><?php echo $row['subject_title']; ?></td>
                                        <td><?php echo $row['course_name']."-".$row['year_level']."".$row['section']; ?></td>
                                        <td><?php echo $row['room_number']; ?></td>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                                        <td>
                                            <?php
                                                switch($row['semester']) {
                                                    case '1': echo "1st"; break;
                                                    case '2': echo "2nd"; break;
                                                    case null: echo "none"; break;
                                                }
                                            ?>
                                            semester
                                        </td>
                                        <td><?php echo $row['start_year'] . " - " . $row['end_year']; ?></td>
                                        <td width="50">
                                            <div class="d-flex align-items-center column-gap-2">
                                                <div role="button" class="text-danger delete_data" data-id="<?php echo $row['schedule_f2f_id']; ?>" data-table="schedule_f2f_tbl" data-type="schedule_f2f_id" >
                                                    <i class="fa-solid fa-trash"></i>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; }else{ ?>
                                        <tr>
                                            <td colspan="100" class="text-center">No F2F Schedule Available</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="border bg-white p-3 mt-2">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>


        </div>
        <!-- content container end-->


        <!-- Event Details Modal -->
        <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-0">
                    <div class="modal-header rounded-0">
                        <h5 class="modal-title">Schedule Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body rounded-0">
                        <div class="container-fluid">
                            <div class="d-flex flex-column row-gap-1">
                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Course:</dt>
                                    <span id="modal-course" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Year & Section:</dt>
                                    <span id="modal-ys" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Teacher:</dt>
                                    <span id="modal-fn" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Subject:</dt>
                                    <span id="modal-subject" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Room:</dt>
                                    <span id="modal-room" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted  me-2">Start:</dt>
                                    <span id="modal-start" class="fw-bold"></span>
                                </div>


                                <div class="d-flex align-items-center">
                                    <dt class="text-muted me-2">End:</dt>
                                    <span id="modal-end" class="fw-bold"></span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <dt class="text-muted me-2">Remarks:</dt>
                                    <span id="modal-remarks" class="fw-bold badge"></span>
                                    <div class="text-muted ms-1" type="button" data-bs-dismiss="modal" id="change">change?</div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer rounded-0">
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Event Details Modal -->

    </div>
    <!-- Include plugins-footer.php or necessary JS files directly -->
    <!-- js -->
    <!-- BOOTSTRAP 5 JS -->
    <script type="text/javascript" src="../plugins/bootstrap5/bootstrap.min.js"></script>
    <!-- FONT AWESOME OFFLINE -->
    <script src="../plugins/fontawesome/all.min.js" crossorigin="anonymous"></script>
    <!-- sweetalert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- custom js -->
    <script src="../assets/js/script.js"></script>
    <script>
        $('#change').click(async function() {
            var schedule_f2f_id = $(this).data('id');

            const inputOptions = new Promise((resolve) => {
                setTimeout(() => {
                    resolve({
                        "approved": "Approved",
                        "cancelled": "Cancelled"
                    });
                }, 300);
            });

            const { value: status } = await Swal.fire({
                title: "Select remarks",
                input: "radio",
                inputOptions: await inputOptions,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return "You need to choose something!";
                    }
                }
            });

            if (status) {
                // Make the AJAX call here
                $.ajax({
                    url: '../controller/update_remarks.php', // Replace with your server endpoint
                    type: 'POST',
                    data: {
                        schedule_f2f_id: schedule_f2f_id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: 'Success',
                                text: `You selected: ${status}. The status has been updated.`,
                                icon: 'success'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error',
                            text: `There was an error updating the status: ${error}`,
                            icon: 'error'
                        });
                    }
                });
            }
        });


    $(document).ready(function() {

        display_events(); // Initial display
        
        // Function to fetch and display events
        function display_events() {
            var events = [];
            $.ajax({
                url: 'display_event.php',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    var result = response.data;
                    $.each(result, function(i, item) {
                        events.push({
                            event_id: item.event_id,
                            title: item.subject_title,
                            start: item.start_datetime,
                            end: item.end_datetime,
                            color: item.color,
                            course_name: item.course_name,
                            subject_title: item.subject_title,
                            ys: item.ys,
                            faculty_name: item.faculty_name,
                            remarks: item.remarks,
                            room: item.room
                        });
                    });

                    // Destroy the previous calendar instance if exists
                    $('#calendar').fullCalendar('destroy');

                    // Initialize or reinitialize fullCalendar
                    $('#calendar').fullCalendar({
                        defaultView: 'month',
                        timeZone: 'local',
                        editable: true,
                        selectable: true,
                        selectHelper: true,
                        select: function(start, end) {
                            // Handle select event if needed
                        },
                        events: events,
                        eventRender: function(event, element, view) {
                            element.bind('click', function() {
                                var _details = $('#event-details-modal');
                                var id = event.event_id;

                                if (id) {
                                    // Format start and end times using moment.js (assuming moment.js is included)
                                    var formattedStart = moment(event.start).format('MMMM DD, YYYY h:mm A');
                                    var formattedEnd = moment(event.end).format('MMMM DD, YYYY h:mm A');
                              
                                    _details.find('#change').attr('data-id', id);
                                    _details.find('#modal-remarks').text(event.remarks);
                                    _details.find('#modal-fn').text(event.faculty_name);
                                    _details.find('#modal-ys').text(event.ys);
                                    _details.find('#modal-room').text(event.room);
                                    _details.find('#modal-course').text(event.course_name);
                                    _details.find('#modal-subject').text(event.subject_title);
                                    _details.find('#modal-start').text(formattedStart);
                                    _details.find('#modal-end').text(formattedEnd);
                                    _details.find('.delete_data').attr('data-id', id);
                                    _details.modal('show');

                                    if (event.remarks === "cancelled") {
                                        $('#modal-remarks').addClass('text-bg-danger')
                                    }else if (event.remarks === "approved") {
                                        $('#modal-remarks').addClass('text-bg-success')
                                    }else{
                                        $('#modal-remarks').addClass('text-bg-secondary')
                                    }
                                }
                            });
                        }
                    }); // End fullCalendar initialization

                    // Refresh data every 5 seconds
                    // setTimeout(display_events, 5000); // 5000 milliseconds = 5 seconds
                },
                error: function(xhr, status) {
                    alert('Error fetching events: ' + xhr.statusText);
                }
            }); // End AJAX request
        }
    });
    </script>


</body>
</html>