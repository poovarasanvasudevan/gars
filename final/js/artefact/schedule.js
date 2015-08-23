/**
 * Created by poovarasan on 22/8/15.
 */
$(function () {
    var freq = $("#freqSelect").val();

    $.ajax({
        url: "displaySchedule.php",
        method: 'GET',
        data: "freq=" + freq,
        success: function (data) {
            if (data == "") {
                $(".artefactSchedule").html("<h4>Sorry There is No Schedule this " + freq1 + "</h4>");
            } else {
                $(".artefactSchedule").html(data);
                $('#STable').DataTable({
                    "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
                    "pageLength": 5,
                    "language": {
                        search: '<div class="input-group ip"> _INPUT_  <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'
                    }
                });
            }
        }
    });

    $(".dSchedule").on("click", function () {
        var freq1 = $(this).attr('id');
        $.ajax({
            url: "displaySchedule.php",
            method: 'GET',
            data: "freq=" + freq1,
            success: function (data) {
                if (data == "") {
                    $(".artefactSchedule").html("<h4>Sorry There is No Schedule this " + freq1 + "</h4>");
                } else {
                    $(".artefactSchedule").html(data);
                    $('#STable').DataTable({
                        "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
                        "pageLength": 5,
                        "language": {
                            search: '<div class="input-group ip"> _INPUT_  <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'

                        }
                    });
                }
            }
        });
    });


});