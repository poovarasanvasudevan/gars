/**
 * Created by poovarasan on 22/8/15.
 */
$(document).ready(function () {

    $('.checkFooter').hide();
    $(".res").hide();

    $('#checkInOut').bootstrapSwitch({
        onText: 'Check In',
        offText: 'Check Out',
        size: 'mini'
    });
    var html;
    $.ajax({
        url: "../common/categoryTypes.php",
        context: document.body,
        success: function (data) {
            $("#ArtefactTypeinAdd").html(data);
        }
    });


    $('#checkInOut').on('switchChange.bootstrapSwitch', function (event, state) {

        if ($('#typeSelect').val() != '') {
            html = "<table class='table table-striped table-hover dataTable no-footer clearfix'>";
            if (state == true) {
                checkin();
            } else {
                checkout();
            }
        } else {
            $.growl.error({message: "Please select artefact Type..!", size: 'large'});
        }

    });

});

function checkinItems() {

    //console.log($('input:checkbox:checked').val());
    $("input:checkbox:checked").each(function () {
        console.log($(this).attr("value"));
    });
}


function sendData(values, artefactType) {
    var value1 = values + "&artefactType=" + artefactType;

    $.ajax({
        url: 'savecout.php',
        method: 'GET',
        data: value1,
        success: function (data) {

            if (data == "done") {
                //$('#checkForm').trigger('reset');
                $('#datatable2').dataTable().fnDestroy();
                $.growl.notice({message: "Checkout Succesful...!", size: 'large'});
                checkout();


            }
        }
    });

}

function updateCINData(values, artefactType) {

    var value1 = values + "&artefactType=" + artefactType;

    $.ajax({
        url: 'savecin.php',
        method: 'GET',
        data: value1,
        success: function (data) {

            if (data == "done") {
                //$('#checkForm').trigger('reset');
                $('#datatable2').dataTable().fnDestroy();
                $.growl.notice({message: "Checkin Succesful...!", size: 'large'});
                checkin();

            }
        }
    });

}
function checkout() {
    var urlcout = "cout.php?type=" + $('#typeSelect').val();

    $.ajax({
        url: urlcout,
        method: 'GET',
        success: function (data) {
            if (data != 'no') {
                $('#checkoutinlist').html(data);
                $('#datatable2').DataTable({
                    "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
                    "pageLength": 5,
                    "language": {
                        search: '<div class="input-group ip"> _INPUT_  <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'

                    }
                });
                //$.growl.notice({ message: "Checkout Succesful...!" ,size:'large'});
                $('#checkForm').submit(function () {
                    var values = $(this).serialize();
                    var artefactType = $('#typeSelect').val()
                    sendData(values, artefactType);
                    return false;
                });

            } else {
                $('#checkoutinlist').html("<label>Sorry No items in this list.</label>");
            }
        }
    });
}


function checkin() {

    var urlcin = "cin.php?type=" + $('#typeSelect').val();
    $.ajax({
        url: urlcin,
        method: 'GET',
        success: function (data) {
            if (data != 'no') {
                $('#checkoutinlist').html(data);
                $('#datatable2').DataTable({
                    "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
                    "pageLength": 5,
                    "language": {
                        search: '<div class="input-group ip"> _INPUT_  <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'

                    }
                });
                //$.growl.notice({ message: "Checkin Succesful...!" ,size:'large'});
                $('#checkInForm').submit(function () {
                    var values = $(this).serialize();
                    var artefactType = $('#typeSelect').val()
                    updateCINData(values, artefactType);
                    return false;
                });

            } else {
                $('#checkoutinlist').html("<label>Sorry No items in this list.</label>");
            }
        }
    });
}

function CBChange(obj) {
    if (obj.checked) {
        var clicked = obj.value;

        var purpose = clicked + "_purpose";
        var status = clicked + "_status";
        var remarks = clicked + "_remarks";

        var purposeObj = document.getElementsByName(purpose);
        var statusObj = document.getElementsByName(status);
        var remarksObj = document.getElementsByName(remarks);

        $(purposeObj).attr('disabled');
        $(statusObj).attr('disabled');
        $(remarksObj).attr('disabled');

        console.log(purposeObj);
    } else {

        var clicked = obj.value;

        var purpose = clicked + "_purpose";
        var status = clicked + "_status";
        var remarks = clicked + "_remarks";

        var purposeObj = document.getElementsByName(purpose);
        var statusObj = document.getElementsByName(status);
        var remarksObj = document.getElementsByName(remarks);

        $(purposeObj).removeAttr('required');
        $(statusObj).removeAttr('required');
        $(remarksObj).removeAttr('required');

        console.log("gfdgfdsfhsdfjk");

    }
}

function getTree(str) {
    if (str == '') {
        $.growl.error({message: "Please Select Valid Artefact Type..!", size: 'large'});
    } else {
        if ($('#checkInOut').is(':checked')) {
            checkin();

        } else {
            checkout();
        }

    }

}
