$(document).ready(function () {
    // Add New Course Row
    $('#addCourse').click(function () {
        var newRow = $('.course-row').first().clone();
        newRow.find('input').val(''); // Clear inputs
        newRow.append('<div class="col-md-1"><button type="button" class="btn btn-danger remove-row">&times;</button></div>');
        $('#courses').append(newRow);
    });

    // Remove Course Row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('.course-row').remove();
    });

    // Submit via AJAX
    $('#gpaForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: 'calculate.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#result').hide().html(
                        '<div class="alert alert-white border shadow p-4">' +
                        '<h5>' + res.message + '</h5>' +
                        res.progress + 
                        res.tableHtml +
                        '</div>'
                    ).fadeIn();
                }
            }
        });
    });
});