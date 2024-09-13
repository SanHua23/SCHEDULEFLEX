//========AJAX SCHEDULING SETUP HANDLING Start=========//
$(document).ready(function() {
        // jQuery code for form submission and table update
       $('#add_schedule_setup').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = $(this).serialize();

            // Submit AJAX request
            // Submit AJAX request
            $.ajax({
                url: '../controller/add_schedule_setup.php', // Adjust URL as per your file structure
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    
                    if (response.status === 'success') {
                        // Disable select inputs
                        $('#subject_id_select, #section_id_select, #year_level_select, #faculty_id_select').prop('disabled', true);

                        // Construct new row HTML
                        var newRow = '<tr data-id="' + response.schedule_setup_id + '">' +
                                        '<td>' + response.row_count + '</td>' +
                                        '<td>' + response.ys + '</td>' +
                                        '<td>' + response.subject_title + '</td>' +
                                        '<td>' + response.faculty + '</td>' +
                                        '<td>' + response.semester + '</td>' +
                                        '<td>' + response.ay + '</td>' +
                                        '<td>' +
                                            '<div class="dropdown">' +
                                                '<div type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">' +
                                                    '<i class="fa-solid fa-ellipsis-vertical"></i>' +
                                                '</div>' +
                                                '<small class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                                                    '<li>' +
                                                        '<a class="dropdown-item d-flex align-items-center gap-3 btn-f2f" ' +
                                                            'href="#f2f-modal" ' +
                                                            'data-bs-toggle="modal" ' +
                                                            'data-id="' + response.schedule_setup_id + '">' +
                                                            '<i class="fa-regular fa-calendar"></i>' +
                                                            '<span>F2F Schedule</span>' +
                                                        '</a>' +
                                                    '</li>' +
                                                    '<li>' +
                                                        '<a class="dropdown-item d-flex align-items-center gap-3 btn-online" ' +
                                                            'href="#online-modal" ' +
                                                            'data-bs-toggle="modal" ' +
                                                            'data-id="' + response.schedule_setup_id + '">' +
                                                            '<i class="fa-solid fa-calendar"></i>' +
                                                            '<span>Online Schedule</span>' +
                                                        '</a>' +
                                                    '</li>' +
                                                    '<li>' +
                                                        '<a class="dropdown-item text-danger d-flex align-items-center gap-3 delete_data" ' +
                                                            'href="#" ' +
                                                            'data-id="' + response.schedule_setup_id + '" ' +
                                                            'data-table="schedule_setup_tbl" ' +
                                                            'data-type="schedule_setup_id">' +
                                                            '<i class="fa-solid fa-trash"></i>' +
                                                            '<span>Delete</span>' +
                                                        '</a>' +
                                                    '</li>' +
                                                '</small>' +
                                            '</div>' +
                                        '</td>' +
                                    '</tr>';

                        $('#schedule_setup_table tbody').append(newRow);

                        // Optionally, show a success message
                        Swal.fire({
                            position: "top-center",
                            icon: "success",
                            html: "<b>Schedule Added Successfully!</b>",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#add_schedule_setup')[0].reset();
                    } else {
                        // Show error message
                        Swal.fire({
                            position: "top-center",
                            icon: "error",
                            html: "<b>" + response.message + "</b>",
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Show AJAX error message
                    Swal.fire({
                        position: "top-center",
                        icon: "error",
                        html: "<b>AJAX Error: " + error + "</b>",
                        showConfirmButton: true
                    });
                }
            });

        });

        $('#course_id_select').change(function() {
            var courseId = $(this).val();
            $('#year_level_select, #subject_id_select').attr('data-coursetext', courseId).removeAttr('disabled');
        });

        // Fetch subjects and sections
        $('#year_level_select').change(function() {
            var year_level = $(this).val();
            var courseId = $(this).data('coursetext');

            $('#subject_id_select, #section_id_select').removeAttr('disabled');
            
            $.ajax({
                url: 'fetch_subjects_and_sections.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    course_id: courseId,
                    year_level: year_level
                },
                success: function(data) {
                    $('#subject_id_select').html('<option hidden selected value="">Choose Option Below</option>');
                    $('#section_id_select').html('<option hidden selected value="">Choose Option Below</option>');

                    if (data.subjects && data.subjects.length > 0) {
                        $.each(data.subjects, function(index, subject) {
                            $('#subject_id_select').append('<option value="' + subject.subject_id + '">' + subject.subject_title + '</option>');
                        });
                    }

                    if (data.sections && data.sections.length > 0) {
                        $.each(data.sections, function(index, section) {
                            $('#section_id_select').append('<option value="' + section.section_id + '">' + section.year_level + '-' + section.section + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching subjects and sections:', error);
                }
            });
        });


        /// Fetch faculty
        $('#subject_id_select').change(function() {
            var subject_id = $(this).val();
            var courseId = $(this).data('coursetext');

            $('#faculty_id_select').removeAttr('disabled');
            
            $.ajax({
                url: 'fetch_subjects_and_sections.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    course_id: courseId,
                    subject_id: subject_id
                },
                success: function(data) {
                    $('#faculty_id_select').html('<option hidden selected value="">Choose Option Below</option>');

                    if (data.facultys && data.facultys.length > 0) {
                        $.each(data.facultys, function(index, faculty) {
                            $('#faculty_id_select').append('<option value="' + faculty.faculty_id + '">' + faculty.first_name + ' ' + faculty.last_name + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching faculty:', error);
                }
            });
        });
});
//========AJAX SCHEDULING SETUP HANDLING End=========//



//========AJAX DELETING ALL DATA EACH TABLE HANDLING Start=========//
$(document).on('click', '.delete_data', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var tablename = $(this).data('table');
    var id_type = $(this).data('type');
    var preferred = $(this).data('preferred');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            
            $.ajax({
                url: '../controller/delete_data.php',
                type: 'POST',
                data: { id: id, tablename: tablename, id_type: id_type },
                dataType: 'json',
                success: function(output) {
                    $('#event-details-modal').modal('hide');
                    if (preferred === "preferred") {
                        $('#ps-container div[data-index="' + id + '"]').remove();
                        if ($('#ps-container').is(':empty')) {
                            $('#ps-container').append('<small class="col-12">Please choose a faculty teacher to view</small>');
                        }
                    }else{
                        $('#ps-container').remove().append('<small class="col-12">Please choose faculty teacher to view</small>');
                    }
                    $('#faculty_id_select option[data-index="' + id + '"]').remove();
                    $('#faculty_id_select').append('<option hidden selected>Choose option below</option>');
                    $('#preferred-subject').empty().append('<div class="col-12">Please choose faculty teacher!</div>');

                    console.log('AJAX response:', output); // Add this line
                    if (output.status === 'success') {
                        // Remove the <tr> from the table
                        var deletedRow = $('.delete_data[data-id="' + id + '"]').closest('tr');
                        deletedRow.remove();

                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Data deleted successfully.",
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error!",
                            text: "Failed to delete data.",
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true
                        });
                    }
                }
            });
        }
    });
});
//========AJAX DELETING ALL DATA EACH TABLE HANDLING End=========//



//========AJAX SCHEDULING ONLINE HANDLING Start=========//
$('#add_schedule_ol').submit(function(e) {
    e.preventDefault(); 

    var formData = $(this).serialize();

    $.ajax({
        url: '../controller/add_schedule_ol.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {

                $('#online-modal').modal('hide');
                // show a success message
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    html: "<b>Online Schedule Added Successfully!</b>",
                    sshowConfirmButton: true
                });

            } else {

                // Show error message
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    html: "<b>" + response.message + "</b>",
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            // Show AJAX error message
            Swal.fire({
                position: "top-center",
                icon: "error",
                html: "<b>AJAX Error: " + error + "</b>",
                showConfirmButton: true
            });
        }
    });
});
//========AJAX SCHEDULING ONLINE HANDLING End=========//



//========AJAX SCHEDULING F2F HANDLING Start=========//
$('#add_schedule_f2f').submit(function(e) {
    e.preventDefault(); 

    var formData = $(this).serialize();

    $.ajax({
        url: '../controller/add_schedule_f2f.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {

                $('#f2f-modal').modal('hide');
                // show a success message
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    html: "<b>Online Schedule Added Successfully!</b>",
                    sshowConfirmButton: true
                });

            } else {

                // Show error message
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    html: "<b>" + response.message + "</b>",
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            // Show AJAX error message
            Swal.fire({
                position: "top-center",
                icon: "error",
                html: "<b>AJAX Error: " + error + "</b>",
                showConfirmButton: true
            });
        }
    });
});
//========AJAX SCHEDULING F2F HANDLING End=========//



//========AJAX view-preferred subject HANDLING Start=========//
$(document).on('click', '.view-preferred', function(e) { 
        var faculty_id = $(this).data('faculty_id');

        // AJAX request to fetch subjects
        $.ajax({
            url: 'faculty-pref-subject.php',
            type: 'GET',
            dataType: 'json',
            data: {
                faculty_id: faculty_id
            },
            success: function(data) {
                
                // Clear existing subjects
                $('#ps-container').empty();

                // Check if subjects array exists and is not empty
                if (data.subjects && data.subjects.length > 0) {
                    $.each(data.subjects, function(index, subject) {
                        $('#ps-container').append(
                            '<div class="form-group col-auto d-flex align-items-center column-gap-1" data-index="' + subject.ps_id + '">' +
                            '<small role="button" class="text-danger delete_data" data-preferred="preferred"  data-id="' + subject.ps_id + '" data-table="preferred_subject_tbl" data-type="ps_id" ><i class="fa-solid fa-trash"></i></small>' +
                            '<small' + subject.subject_id + '">' + subject.subject_title + '</small>' +
                            '</div>'
                        );
                    });
                } else {
                    // Handle case where no subjects are available
                    $('#ps-container').append('<small class="col-12">No subjects available for this faculty member.</small>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subjects:', error);
                // Display error message or handle error case
                $('#ps-container').empty().append('<div class="col-12">Error loading subjects. Please try again.</div>');
            }
        });
});
$(document).on('change', '#faculty_id_select', function(e) { 
        var faculty_id = $(this).val();
        // AJAX request to fetch subjects
        $.ajax({
            url: 'faculty-pref-subject.php',
            type: 'POST',
            dataType: 'json',
            data: {
                faculty_id: faculty_id
            },
            success: function(data) {
                console.log(data)
                // fetch subject list based on teacher course
                $('#preferred-subject').empty();

                // Populate subjects
                if (data.subjects && data.subjects.length > 0) {
                    $.each(data.subjects, function(index, subject) {
                        $('#preferred-subject').append(
                            '<div class="form-group col-auto">' +
                            '<input type="checkbox" name="subject_ids[]" value="' + subject.subject_id + '" id="' + subject.subject_title + '" class="form-check-input border-2 border-warning me-1">' +
                            '<label class="form-label" for="' + subject.subject_title + '">' + subject.subject_title + '</label>' +
                            '</div>'
                        );
                    });
                } else {
                    $('#preferred-subject').append('<div class="col-12">No subjects available for this course.</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subjects:', error);
                // Display error message or handle error case
                $('#ps-container').empty().append('<div class="col-12">Error loading subjects. Please try again.</div>');
            }
        });
});
//========AJAX view-preferred subject HANDLING End=========//



//========BUTTON FOR RESET FORM EDITING HANDLING End=========//
$(document).ready(function() {
  //clear form data
  $('.clear').click(function(e) {
     $('#add_course, #add_section, .add_faculty, #add_time, #add_subject, #add_room, #add_ay').each(function() {
          this.reset();
          $('.clear').addClass('d-none')
      });
});
//========BUTTON FOR RESET FORM EDITING HANDLING End=========//



//========AJAX fetch subjects and sections Start=========//
$('#course_id_select').change(function() {
    var courseId = $(this).val();

    // AJAX request to fetch subjects and sections
    $.ajax({
        url: 'fetch_subjects_and_sections.php',
        type: 'GET',
        dataType: 'json',
        data: {
            course_id: courseId
        },
        success: function(data) {
            console.log('Received data:', data);

            // Clear existing options
            $('#subject_id_select').html('<option hidden selected value="">Choose Option Below</option>');
            $('#section_id_select').html('<option hidden selected value="">Choose Option Below</option>');

            // Populate subjects
            if (data.subjects && data.subjects.length > 0) {
                $.each(data.subjects, function(index, subject) {
                    $('#subject_id_select').append('<option value="' + subject.subject_id + '">' + subject.subject_title + '</option>');
                });
            }

            // Populate sections
            if (data.sections && data.sections.length > 0) {
                $.each(data.sections, function(index, section) {
                    $('#section_id_select').append('<option value="' + section.section_id + '">' + section.year_level + '-' + section.section + '</option>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching subjects and sections:', error);
            // Display error message or handle error case
        }
    });
});
//========AJAX fetch subjects and sections End=========//



//========AJAX SUBJECT HANDLING START=========//
$('#add_subject').submit(function(e) {
    e.preventDefault();

    var subjectData = $(this).serialize();

    $.ajax({
        url: '../controller/add_subject.php',
        type: 'post',
        data: subjectData,
        dataType: 'json',
        success: function(output) {
            if (output.status === 'success') {
                // Clear form fields or reset form as needed
                $('#add_subject')[0].reset();

                if (output.operation === 'insert') {
                    // Create a new row for the inserted subject
                    var newRowNumber = $('#subject_table tbody tr').length + 1;
                    var newRow = '<tr data-id="' + output.subject_id + '">' +
                        '<td width="50">' + newRowNumber + '</td>' +
                        '<td data-course_id="' + output.course_id + '">' + output.course_name + '</td>' +
                        '<td>' + output.year_level + '</td>' +
                        '<td>' + output.subject_title + '</td>' +
                        '<td>' + output.subject_code + '</td>' +
                        '<td width="150">' +
                        '<div class="d-flex align-items-center column-gap-2">' +
                        '<div role="button" class="text-warning update_data" data-id="' + output.subject_id + '" data-course_id="' + output.course_id + '" data-course_name="' + output.course_name + '" data-subject_title="' + output.subject_title + '" data-subject_code="' + output.subject_code + '"><i class="fa-solid fa-edit"></i></div>' +
                        '<div role="button" class="text-danger delete_data" data-id="' + output.subject_id + '" data-table="subject_tbl" data-type="subject_id"><i class="fa-solid fa-trash"></i></div>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';

                    // Append the new row to the table body
                    $('#subject_table tbody').append(newRow);

                    // Show success message for insert
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        html: "<b>Subject added successfully!</b>",
                        showConfirmButton: false
                    });
                } else if (output.operation === 'update') {
                    // Update the existing row
                    var row = $('#subject_table tbody tr[data-id="' + output.subject_id + '"]');
                    row.find('td:nth-child(2)').attr('data-course_id', output.course_id).text(output.course_name);
                    row.find('td:nth-child(3)').text(output.year_level);
                    row.find('td:nth-child(4)').text(output.subject_title);
                    row.find('td:nth-child(5)').text(output.subject_code);

                    // Show success message for update
                    Swal.fire({
                        position: "top-center",
                        icon: "success",
                        html: "<b>Subject updated successfully!</b>",
                        showConfirmButton: false
                    });
                }
            } else {
                // Show error message
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    html: "<b>" + output.message + "</b>",
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            // Show error message for AJAX failure
            Swal.fire({
                position: "top-center",
                icon: "error",
                html: "<b>AJAX Error: " + error + "</b>",
                showConfirmButton: true
            });
        }
    });
});

// Handle click event for updating a subject
$('#subject_table').on('click', '.update_data', function(event) {
var tr = $(this).closest('tr');

var courseId = tr.find('td:nth-child(2)').data('course_id');
var courseName = tr.find('td:nth-child(2)').text().trim();
var yearLevel = tr.find('td:nth-child(3)').text().trim();
var subjectCode = tr.find('td:nth-child(4)').text().trim();
var subjectTitle = tr.find('td:nth-child(5)').text().trim();
var subjectId = tr.data('id');

$('#course_id').val(courseId);
$('#course_id option:selected').text(courseName);
$('#year_level').val(yearLevel);
$('#subject_code').val(subjectCode);
$('#subject_title').val(subjectTitle);
$('#section_id').val(subjectId);

$('.form-title').text('Update Subject Form');
$('.save-button').text('Update');
$('.clear').removeClass('d-none');

event.preventDefault();
});
//========AJAX SUBJECT HANDLING END=========//


      
//========AJAX TIME AND DAY HANDLING START=========//
$('#add_time').submit(function(e) {
    e.preventDefault();

    var timeData = $(this).serialize();

    $.ajax({
        url: '../controller/add_time.php',
        type: 'post',
        data: timeData,
        dataType: 'json',
        success: function(output) {
            if (output.status === 'success') {
                // Clear form fields or reset form as needed
                $('#add_time')[0].reset();

                // Iterate over the returned data to create new rows for inserted times
                $.each(output.inserted, function(index, timeData) {
                    var newRowNumber = $('#time_table tbody tr').length + 1;
                    var newRow = '<tr data-id="' + timeData.time_id + '">' +
                        '<td width="50">' + newRowNumber + '</td>' +
                        '<td>' + timeData.start_time + '</td>' +
                        '<td>' + timeData.end_time + '</td>' +
                        '<td>' + getDayName(timeData.day) + '</td>' +
                        '<td width="150">' +
                        '<div class="d-flex align-items-center column-gap-2">' +
                        '<div role="button" class="text-danger delete_data" data-id="' + timeData.time_id + '" data-table="time_tbl" data-type="time_id"><i class="fa-solid fa-trash"></i></div>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';

                    // Append the new row to the table body
                    $('#time_table tbody').append(newRow);
                });

                // Show success message for insert
                Swal.fire({
                    position: "top-center",
                    icon: "success",
                    html: "<b>Adding New Time Slots Successfully!</b>",
                    showConfirmButton: false
                });

                if (output.failed.length > 0) {
                    var failedDays = output.failed.map(getDayName).join(', ');
                    Swal.fire({
                        position: "top-center",
                        icon: "warning",
                        html: "<b>These days were not inserted due to overlaps: " + failedDays + "</b>",
                        showConfirmButton: true
                    });
                }
            } else {
                // Show error message
                Swal.fire({
                    position: "top-center",
                    icon: "error",
                    html: "<b>" + output.message + "</b>",
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            // Show error message for AJAX failure
            Swal.fire({
                position: "top-center",
                icon: "error",
                html: "<b>AJAX Error: " + error + "</b>",
                showConfirmButton: true
            });
        }
    });
});

// Function to get day name from day code
function getDayName(dayCode) {
    switch(dayCode) {
        case 'M': return 'Monday';
        case 'T': return 'Tuesday';
        case 'W': return 'Wednesday';
        case 'TH': return 'Thursday';
        case 'F': return 'Friday';
        case 'S': return 'Saturday';
        default: return '';
    }
}
//========AJAX TIME AND DAY HANDLING END=========//



//========AJAX FACULTY HANDLING START=========//
$('.add_faculty').submit(function(e) {
      e.preventDefault();
      var courseData = $(this).serialize();
      $.ajax({
          url: '../controller/add_faculty.php',
          type: 'post',
          data: courseData,
          dataType: 'json',
          success: function(output) {
            console.log(output)
              if (output.status === 'success') {

                  // Clear form fields or reset form as needed
                  $('.add_faculty')[0].reset();

                  // Check if we need to update or insert
                  var existingRow = $('#faculty_table tbody tr[data-id="' + output.faculty_id + '"]');
                  if (existingRow.length > 0) {
                      // Update existing row
                      existingRow.find('td:eq(1)').text(output.course_name).attr('data-course_id', output.course_id);;
                      existingRow.find('td:eq(2)').text(output.full_name);
                      existingRow.find('td:eq(3)').text(output.full_time);
                      existingRow.find('td:eq(4)').text(output.description);

                      // Show success message for update
                      Swal.fire({
                          position: "top-center",
                          icon: "success",
                          html: "<b>Section Updated Successfully!</b>",
                          showConfirmButton: false
                      });
                  } else {
                        // Insert new row
                        var newRowNumber = $('#faculty_table tbody tr').length + 1;
                        var newRow = '<tr data-id="' + output.faculty_id + '">' +
                          '<td width="50">' + newRowNumber + '</td>' +
                          '<td data-course_id="'+output.course_id+'">' + output.course_name + '</td>' +
                          '<td>' + output.full_name + '</td>' +
                          '<td>' + output.full_time + '</td>' +
                          '<td>' + output.description + '</td>' +
                          '<td width="150">' +
                          '<div class="d-flex align-items-center column-gap-2">' +
                          '<div role="button" class="text-warning update_data"><i class="fa-solid fa-pen-to-square"></i></div>' +
                          '<div role="button" class="text-danger delete_data" data-id="' + output.faculty_id + '" data-table="faculty_tbl" data-type="faculty_id"><i class="fa-solid fa-trash"></i></div>' +
                          '<div role="button" class="text-primary view-preferred" data-faculty_id="' + output.faculty_id + '"><i class="fa-regular fa-eye"></i></div>' +
                          '</div>' +
                          '</td>' +
                          '</tr>';

                        $('#faculty_id_select').append('<option data-index="' + output.faculty_id + '" value="' + output.faculty_id + '">' + output.full_name + '</option>');

                        // Append the new row to the table body
                        $('#faculty_table tbody').append(newRow);

                        // Show success message for insert
                        Swal.fire({
                          position: "top-center",
                          icon: "success",
                          html: "<b>Adding New Teacher Successfully!</b>",
                          showConfirmButton: false
                        });
                  }
              }else if(output.status === 'update') {
                    Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Preferred Subject Updated!</b>",
                      showConfirmButton: true
                  });
              } else {
                  // Show error message
                  Swal.fire({
                      position: "top-center",
                      icon: "error",
                      html: "<b>" + output.message + "</b>",
                      showConfirmButton: true
                  });
              }
          },
          error: function(xhr, status, error) {
              // Show error message for AJAX failure
              Swal.fire({
                  position: "top-center",
                  icon: "error",
                  html: "<b>AJAX Error: " + error + "</b>",
                  showConfirmButton: true
              });
          }
      });
});
$('#faculty_table').on('click', '.update_data', function(event) {
    var tr = $(this).closest('tr');

    var courseId = tr.find('td:nth-child(2)').data('course_id');
    var courseName = tr.find('td:nth-child(2)').text().trim();
    var fullName = tr.find('td:nth-child(3)').text().trim();
    var description = tr.find('td:nth-child(5)').text().trim();
    var sectionId = tr.data('id');

    // Split full name into first name and last name
    var nameParts = fullName.split(' ');
    var firstName = nameParts[0];
    var lastName = nameParts.slice(1).join(' ');

    $('#course_id_select2').val(courseId);
    $('#course_id_select2 option:selected').text(courseName);
    $('#first_name').val(firstName);
    $('#last_name').val(lastName);
    $('#description').val(description);
    $('#faculty_id').val(sectionId);

    $('.form-title').text('Update Faculty Form');
    $('.save-button').text('Update');
    $('.clear').removeClass('d-none');

    event.preventDefault();
});
//========AJAX FACULTY HANDLING END=========//



//========AJAX SECTION HANDLING START=========//
$('#add_section').submit(function(e) {
  e.preventDefault();
  var courseData = $(this).serialize();
  $.ajax({
      url: '../controller/add_section.php',
      type: 'post',
      data: courseData,
      dataType: 'json',
      success: function(output) {
        console.log(output)
          if (output.status === 'success') {
              // Clear form fields or reset form as needed
              $('#add_section')[0].reset();

              // Check if we need to update or insert
              var existingRow = $('#section_table tbody tr[data-id="' + output.section_id + '"]');
              if (existingRow.length > 0) {
                  // Update existing row
                  existingRow.find('td:eq(1)').text(output.course_name).attr('data-course_id', output.course_id);;
                  existingRow.find('td:eq(2)').text(output.year_level);
                  existingRow.find('td:eq(3)').text(output.section);

                  // Show success message for update
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Section Updated Successfully!</b>",
                      showConfirmButton: false
                  });
              } else {
                  // Insert new row
                  var newRowNumber = $('#section_table tbody tr').length + 1;
                  var newRow = '<tr data-id="' + output.section_id + '">' +
                      '<td width="50">' + newRowNumber + '</td>' +
                      '<td data-course_id="' + output.course_id + '">' + output.course_name + '</td>' +
                      '<td>' + output.year_level + '</td>' +
                      '<td>' + output.section + '</td>' +
                      '<td width="150">' +
                      '<div class="d-flex align-items-center column-gap-2">' +
                      '<div role="button" class="text-warning update_data"><i class="fa-solid fa-pen-to-square"></i></div>' +
                      '<div role="button" class="text-danger delete_data" data-id="' + output.section_id + '" data-table="section_tbl" data-type="section_id"><i class="fa-solid fa-trash"></i></div>' +
                      '</div>' +
                      '</td>' +
                      '</tr>';


                  // Append the new row to the table body
                  $('#section_table tbody').append(newRow);

                  // Show success message for insert
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Adding New Section Successfully!</b>",
                      showConfirmButton: false
                  });
              }
          } else {
              // Show error message
              Swal.fire({
                  position: "top-center",
                  icon: "error",
                  html: "<b>" + output.message + "</b>",
                  showConfirmButton: true
              });
          }
      },
      error: function(xhr, status, error) {
          // Show error message for AJAX failure
          Swal.fire({
              position: "top-center",
              icon: "error",
              html: "<b>AJAX Error: " + error + "</b>",
              showConfirmButton: true
          });
      }
  });
});
$('#section_table').on('click', '.update_data', function(event) {
var tr = $(this).closest('tr');

var courseId = tr.find('td:nth-child(2)').data('course_id');
var courseName = tr.find('td:nth-child(2)').text().trim();
var yearLevel = tr.find('td:nth-child(3)').text().trim();
var sectionName = tr.find('td:nth-child(4)').text().trim();
var sectionId = tr.data('id');

$('#course_id').val(courseId);
// Optionally, if you want to change the displayed text of the selected option
$('#course_id option:selected').text(courseName);
$('#section').val(sectionName);
$('#year_level').val(yearLevel);
$('#section_id').val(sectionId);

$('.form-title').text('Update Section Form');
$('.save-button').text('Update');
$('.clear').removeClass('d-none');

event.preventDefault();
});
//========AJAX SECTION HANDLING END=========//



//========AJAX ROOM HANDLING START=========//
$('#add_room').submit(function(e) {
  e.preventDefault();
  var roomData = $(this).serialize();
  $.ajax({
      url: '../controller/add_room.php',
      type: 'post',
      data: roomData,
      dataType: 'json',
      success: function(output) {
        console.log(output)
          if (output.status === 'insert' || output.status === 'update') {
              // Clear form fields or reset form as needed
              $('#add_room')[0].reset();

              // Check if we need to update or insert
              var existingRow = $('#room_table tbody tr[data-id="' + output.room_id + '"]');
              if (existingRow.length > 0) {
                  // Update existing row
                  existingRow.find('td:eq(1)').text(output.room_number);
                  existingRow.find('td:eq(2)').text(output.room_description);

                  // Show success message for update
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Room Updated Successfully!</b>",
                      showConfirmButton: false
                  });
              } else {
                  // Insert new row
                  var newRowNumber = $('#room_table tbody tr').length + 1;
                  var newRow = '<tr data-id="' + output.room_id + '">' +
                      '<td width="50">' + newRowNumber + '</td>' +
                      '<td>' + output.room_number + '</td>' +
                      '<td>' + output.room_description + '</td>' +
                      '<td width="150">' +
                      '<div class="d-flex align-items-center column-gap-2">' +
                      '<div role="button" class="text-warning update_data"><i class="fa-solid fa-pen-to-square"></i></div>' +
                      '<div role="button" class="text-danger delete_data" data-id="' + output.room_id + '" data-table="room_tbl" data-type="room_id"><i class="fa-solid fa-trash"></i></div>' +
                      '</div>' +
                      '</td>' +
                      '</tr>';


                  // Append the new row to the table body
                  $('#room_table tbody').append(newRow);

                  // Show success message for insert
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Room Inserted Successfully!</b>",
                      showConfirmButton: false
                  });
              }
          } else if (output.status === 'error') {
              // Show error message
              Swal.fire({
                  position: "top-center",
                  icon: "error",
                  html: "<b>" + output.message + "</b>",
                  showConfirmButton: true
              });
          }
      }
  });
});
$('#room_table').on('click', '.update_data', function(event) {
var tr = $(this).closest('tr');

var room_number = tr.find('td:nth-child(2)').text().trim();
var room_description = tr.find('td:nth-child(3)').text().trim();
var room_id = tr.data('id');


$('#room_number').val(room_number);
$('#room_description').val(room_description);
$('#room_id').val(room_id);

$('.form-title').text('Update Room Form');
$('.save-button').text('Update');
$('.clear').removeClass('d-none');

event.preventDefault();
});
//========AJAX ROOM HANDLING END=========//


//========AJAX Academic year HANDLING START=========//
$('#add_ay').submit(function(e) {
  e.preventDefault();
  var roomData = $(this).serialize();
  $.ajax({
      url: '../controller/add_ay.php',
      type: 'post',
      data: roomData,
      dataType: 'json',
      success: function(output) {

          if (output.status === 'insert' || output.status === 'update') {

              // Check if we need to update or insert
              var existingRow = $('#ay_table tbody tr[data-id="' + output.ay_id + '"]');
              if (existingRow.length > 0) {
                  // Update existing row
                  existingRow.find('td:eq(1)').text(output.start_year);
                  existingRow.find('td:eq(2)').text(output.end_year);

                  // Show success message for update
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Academic Year Updated Successfully!</b>",
                      showConfirmButton: false
                  });
              } else {
                  // Insert new row
                  var newRowNumber = $('#ay_table tbody tr').length + 1;
                  var newRow = '<tr data-id="' + output.room_id + '">' +
                      '<td width="50">' + newRowNumber + '</td>' +
                      '<td>' + output.start_year + '</td>' +
                      '<td>' + output.end_year + '</td>' +
                      '<td width="150">' +
                      '<div class="d-flex align-items-center column-gap-2">' +
                      '<div role="button" class="text-warning update_data"><i class="fa-solid fa-pen-to-square"></i></div>' +
                      '<div role="button" class="text-danger delete_data" data-id="' + output.ay_id + '" data-table="ay_tbl" data-type="ay_id"><i class="fa-solid fa-trash"></i></div>' +
                      '</div>' +
                      '</td>' +
                      '</tr>';


                  // Append the new row to the table body
                  $('#ay_table tbody').append(newRow);

                  // Show success message for insert
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Academic Year Inserted Successfully!</b>",
                      showConfirmButton: false
                  });
              }
              $('#add_ay')[0].reset();

          } else if (output.status === 'error') {
              // Show error message
              Swal.fire({
                  position: "top-center",
                  icon: "error",
                  html: "<b>" + output.message + "</b>",
                  showConfirmButton: true
              });
          }
      }
  });
});
$('#ay_table').on('click', '.update_data', function(event) {
var tr = $(this).closest('tr');

var start_year = tr.find('td:nth-child(2)').text().trim();
var end_year = tr.find('td:nth-child(3)').text().trim();
var ay_id = tr.data('id');


$('#start_year').val(start_year);
$('#end_year').val(end_year);
$('#ay_id').val(ay_id);

$('.form-title').text('Update Academic Year Form');
$('.save-button').text('Update');
$('.clear').removeClass('d-none');

event.preventDefault();
});
//========AJAX Academic year HANDLING END=========//



//========AJAX COURSES HANDLING START=========//
$('#add_course').submit(function(e) {
  e.preventDefault();
  var sectionData = $(this).serialize();
  $.ajax({
      url: '../controller/add_course.php',
      type: 'post',
      data: sectionData,
      dataType: 'json',
      success: function(output) {
          if (output.status === 'success') {
              // Clear form fields or reset form as needed
              $('#add_course')[0].reset();

              // Check if we need to update or insert
              var existingRow = $('#course_table tbody tr[data-id="' + output.course_id + '"]');
              if (existingRow.length > 0) {
                  // Update existing row
                  existingRow.find('td:eq(1)').text(output.course_name);
                  existingRow.find('td:eq(2)').text(output.course_description);

                  // Show success message for update
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Course Updated Successfully!</b>",
                      showConfirmButton: false
                  });
              } else {
                  // Insert new row
                  var newRowNumber = $('#course_table tbody tr').length + 1;
                  var newRow = '<tr data-id="' + output.course_id + '">' +
                      '<td width="50">' + newRowNumber + '</td>' +
                      '<td>' + output.course_name + '</td>' +
                      '<td>' + output.course_description + '</td>' +
                      '<td width="150">' +
                      '<div class="d-flex align-items-center column-gap-2">' +
                      '<div role="button" class="text-warning update_data"><i class="fa-solid fa-pen-to-square"></i></div>' +
                      '<div role="button" class="text-danger delete_data" data-id="' + output.course_id + '" data-table="course_tbl" data-type="course_id"><i class="fa-solid fa-trash"></i></div>' +
                      '</div>' +
                      '</td>' +
                      '</tr>';

                  // Append the new row to the table body
                  $('#course_table tbody').append(newRow);

                  // Show success message for insert
                  Swal.fire({
                      position: "top-center",
                      icon: "success",
                      html: "<b>Adding New Course Successfully!</b>",
                      showConfirmButton: false
                  });
              }
          } else {
              // Show error message
              Swal.fire({
                  position: "top-center",
                  icon: "error",
                  html: "<b>" + output.message + "</b>",
                  showConfirmButton: true
              });
          }
      },
      error: function(xhr, status, error) {
          // Show error message for AJAX failure
          Swal.fire({
              position: "top-center",
              icon: "error",
              html: "<b>AJAX Error: " + error + "</b>",
              showConfirmButton: true
          });
      }
  });
});

$('#course_table').on('click', '.update_data', function(event) {
  var tr = $(this).closest('tr');

  var courseName = tr.find('td:nth-child(2)').text().trim();
  var courseDescription = tr.find('td:nth-child(3)').text().trim();
  var courseId = tr.find('.delete_data').data('id');

  $('#course_name').val(courseName);
  $('#course_description').val(courseDescription);
  $('#course_id').val(courseId);

  $('.form-title').text('Update Course');
  $('.save-button').text('Update');
  $('.clear').removeClass('d-none');

  event.preventDefault();
});
//========AJAX COURSES HANDLING END=========//



//========OTHER JS HANDLING start=========//

        //=======SIDEBAR FNCTION
        $('#menu-toggle').click(function() {
            
            $('.sidebar').toggleClass('sidebar-toggle');
            $('.topbar-logo').toggleClass('logo-toggle');
            $('.content-div').toggleClass('content-div-toggle');
            
            
            toggleIconClass()
        });


        function toggleIconClass() {
            var icon = $('#menu-toggle svg');
            if (icon.hasClass('fa-toggle-off')) {
                icon.removeClass('fa-toggle-off').addClass('fa-toggle-on');
            } else {
                icon.removeClass('fa-toggle-on').addClass('fa-toggle-off');
            }
        }

        //=======TABLE SEARCHBAR
        $("#search-bar").on("keyup", function() {
              var value = $(this).val().toLowerCase();
              $("table tbody tr").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
              });
        });

        //=======TOOL TIPS FOR BOOTRAP IF HAVE
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

});
//========OTHER JS HANDLING end=========//