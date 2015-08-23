/**
 * Created by poovarasan on 22/8/15.
 */
var artefactCodeSelected;
var units, freq;
var nextdate;
var aCode, aTitle, aType;
var pop, pop1, pop2, pop3;
$(document).ready(function () {

    $('#headerMaintain').hide();

    $('#fperiodic').on('click', function () {

        var artefactType = $("#categoryList").val();

        if (artefactType != "") {
            pop2 = $('#fullModel').bPopup({
                modalClose: false,
                position: [300, 20],
                closeClass: 'closep'
            });

            $('#newfullEndDate').datetimepicker({
                lang: 'en',
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'd-m-Y',
                minDate: $('#newfullCurrentDate').val() // yesterday is minimum date
            });


        } else {
            $.growl.error({message: "Please select the artefact Type..!", size: 'large'});
        }

    });


    $('#fsperiodic').on('click', function () {

        var artefactType = $("#categoryList").val();

        if (artefactType != "") {
            pop3 = $('#fullSperodicModel').bPopup({
                modalClose: false,
                position: [300, 20],
                closeClass: 'closep'
            });

            $('#newfullEndDate1').datetimepicker({
                lang: 'en',
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'd-m-Y',
                minDate: $('#newfullCurrentDate').val() // yesterday is minimum date
            });


        } else {
            $.growl.error({message: "Please select the artefact Type..!", size: 'large'});
        }
    });

});

/*
 * Get the tree
 *
 * */
function getTree(str) {
    /*
     * If Invalid Artefact type
     *
     * */
    if (str == '') {
        $("#tree").html("<div style='color: grey'>Select Category Type</div>");
        $('#AttributeListDiv').html("<div style='color: grey'>Select Your Option</div>");
        return 0;
    }
    else {
        /*
         * Valid Artefact type
         * */
        $('#duration').html("<div style='color: grey'>Please select any Node</div>");

        /*
         * what artefact is selected
         * */
        aType = $("#categoryList").val();

        /*
         * FOrm the tree
         * */
        $.ajax({
            url: "../common/treeFormation.php",
            data: "type=" + $("#categoryList").val(),
            success: function (data) {
                $("#tree").fancytree("destroy");
                $("#tree").html(data);
                $('#headerMaintain').hide();


                $("#tree").fancytree({
                    autoActivate: true,
                    autoScroll: true,
                    clickFolderMode: 3,
                    keyboard: true,
                    extensions: ['filter'],
                    filter: {
                        autoApply: true,
                        counter: true,
                        hideExpandedCounter: true,
                        mode: "hide"
                    },
                    quicksearch: true,
                    activate: function (event, data) {
                        /*
                         * artefact is selected from the tree
                         * artefactcode
                         * title
                         *
                         * */
                        artefactCodeSelected = data.node.key;

                        $('#aCode').text(data.node.title);
                        $('#headerMaintain').show();

                        aTitle = data.node.title;
                        aCode = data.node.key;

                        /*
                         *
                         * based on the artefact selected load the data
                         *
                         *
                         *
                         * */
                        $.ajax({
                            url: "artefactMaintain.php",
                            data: 'type=' + $("#categoryList").val() + '&page=AddArtefact&artefactCode=' + data.node.key + '&artefactTitle=' + data.node.title + '&full=NO',
                            success: function (data) {
                                $('#duration').html(data);
                            }
                        });

                    }
                });

            }
        });

        /*
         *
         * for maintaining search
         * */
        $("input[name=search]").keyup(function (e) {
            //$("#tree").fancytree("destroy");
            var match = $(this).val();

            if (match == '') {
                $("#tree").fancytree("getTree").clearFilter();
            } else {
                $("#tree").fancytree("getTree").filterNodes(match, {autoExpand: true, leavesOnly: true});
            }

        }).focus();


        /*
         *
         * On clicking new schedule button
         * popup is opens for new schedule
         *
         * */


        $('#periodic').on('click', function () {

            /*
             * open periodic single task dialog
             * */


            pop = $('#singleModel').bPopup({
                modalClose: false,
                position: [300, 20],
                closeClass: 'closep'
            });

            $('#newSingleEndDate').datetimepicker({
                lang: 'en',
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'd-m-Y',
                minDate: $('#newSingleCurrentDate').val() // yesterday is minimum date
            });


        });

        $('#speriodic').on('click', function () {

            pop1 = $('#singleSperodicModel').bPopup({
                modalClose: false,
                position: [300, 20],
                closeClass: 'closep'
            });

            $('#newSingleEndDate1').datetimepicker({
                lang: 'en',
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'd-m-Y',
                minDate: $('#newSingleCurrentDate1').val() // yesterday is minimum date
            });


        });


        $('#newSchedule').on('click', function () {

            /*
             * open the popup
             *
             * */


            if ($('#categoryList').val() != '') {


                $('#fillDate').datepicker({format: "dd-mm-yyyy"});
                $('#endDate1').datepicker({format: "dd-mm-yyyy"});
                $('#fullModelForm').submit(function () {

                    var endDate = $('#fillDate').val();
                    var url = "fullSchedule.php?type=" + type + "&endDate=" + endDate;


                    $.get(url, function (data) {

                        if (data != 'No') {
                            pop.close();
                            $.growl.notice({message: "Schedule Created Succesfully..!", size: 'large'});
                        } else {
                            $.growl.error({message: "failed to Create Schedule..!", size: 'large'});
                        }
                    });

                    return false;
                });

            } else {
                $.growl.error({message: "Please select artefact Type..!", size: 'large'});
            }


        });

        $('.editButton').on('click', function () {

            var selectedArtefact = $(this).attr('id');

        });


    }
}

/*
 *
 * Saving new Artefact maintainence periodic
 * Single artefact
 * */

function saveSingleArtefactPeriodicSchedule() {

    var currentDate = $("#newSingleCurrentDate").val();
    var nextDate = nextdate;
    var freq = $("#newSingleNextFrequency").val();
    var units = $("#newSingleUnits").val();
    var endDate = $("#newSingleEndDate").val();
    var type = $('#categoryList').val();

    if (endDate == '' || endDate == null) {
        $.growl.error({message: "Please enter end Date..!", size: 'large'});
        return;
    }

    // curernt and enddate valisdation

    var from = new Date();
    var to = new Date();

    var str = endDate;
    var str2 = currentDate;
    var fromDateArray = str2.split("-")

    from.setFullYear(parseInt(fromDateArray[2]));
    from.setMonth(parseInt(fromDateArray[1]) - 1);  // months indexed as 0-11, substract 1
    from.setDate(parseInt(fromDateArray[0]));

    var dateArray = str.split("-")
    to.setFullYear(parseInt(dateArray[2]));
    to.setMonth(parseInt(dateArray[1]) - 1);  // months indexed as 0-11, substract 1
    to.setDate(parseInt(dateArray[0]));     // setDate sets the month of day

    var today = new Date();
    var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);


    var day;

    if (units == 'Week')
        day = $('#weekday').val();

    if (units == 'Month')
        day = $('#monthDate').val();

    if (units != 'Year') {
        if (day == null || day == '') {
            $.growl.error({message: "Please select occurance Date..!", size: 'large'});
            return;
        }
    }

    $.ajax({
        url: "saveSinglePeriodicSchedule.php",
        method: 'GET',
        data: "artefactCode=" + artefactCodeSelected + "&type=" + type + "&currentDate=" + currentDate + "&nextDate=" + nextDate + "&freq=" + freq + "&units=" + units + "&day=" + day + "&endDate=" + endDate,
        success: function (data) {
            if (data == 'Succesfully Updated') {
                clearValue();
                //$('#saveStatus').html("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Succesfully Updated</div>");
                $.growl.notice({message: "Updated Succesfully...!", size: 'large'});
                pop.close();
            } else {
                //$('#saveStatus').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+data+"</div>");
                $.growl.error({message: "Updation Failed..!", size: 'large'});
            }

        }
    });

}


function saveSingleArtefactSperiodicSchedule() {
    var currentDate = $("#newSingleCurrentDate1").val();
    var endDate = $("#newSingleEndDate1").val();
    var artefactCode = artefactCodeSelected;
    var artefactType = $('#categoryList').val();

    if (endDate == '' || endDate == null) {
        $.growl.notice({message: "Please enter end Date...!", size: 'large'});
        return;
    }

    $.ajax({
        url: "saveSingleMaintainance.php",
        method: 'GET',
        data: "currentDate=" + currentDate + "&endDate=" + endDate + "&type=" + artefactType + "&code=" + artefactCode,
        success: function (data) {
            if (data == 'Succesfully Updated') {
                clearValue();
                pop1.close();
                //$('#saveStatus').html("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Succesfully Updated</div>");
                $.growl.notice({
                    message: "Updated Succesfully...!",
                    size: 'large'
                });
            } else {
                //$('#saveStatus').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+data+"</div>");
                $.growl.error({message: "Updation Failed..!", size: 'large'});
            }

        }
    });


}
function deleteSchedule(id) {


    var selectedArtefact = id;


    bootbox.dialog({
        message: "Are you sure?",
        title: "Confirmation",
        closeButton: true,
        buttons: {
            success: {
                label: "Yes",
                className: "btn-primary",
                callback: function () {

                    $.ajax({
                        url: "deleteSchedule.php",
                        data: 'id=' + selectedArtefact,
                        success: function (data) {
                            if (data == 'success') {
                                $(this).modal('hide');

                                bootbox.alert("Deleted Succesfully..");
                            } else {

                                $(this).modal('hide');
                                bootbox.alert("Unable to delete this Schedule.")

                            }
                        }
                    });

                }
            },
            danger: {
                label: "No",
                className: "btn",
                callback: function () {
                    $(this).modal('hide');
                }
            }

        }
    });


}


function calculateValue(str) {

    units = $("#newSingleUnits").val();
    var days = 0;
    var formattedDate;
    var d = new Date();

    if (units != '') {
        freq = $("#newSingleNextFrequency").val();
        var endDate = $("#newSingleEndDate").val();

        if (freq == "" || freq == 0 || endDate == '00-00-0000') {
            alert("please enter frequency");
            return;
        }

        if (units == "Week") {
            nextdate = new Date(new Date().getTime() + (freq * 7 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate.getDate() + '-' + nextdate.getMonth() + '-' + nextdate.getFullYear();
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }
        if (units == "Year") {
            nextdate = new Date(new Date().getTime() + (freq * 365 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate.getDate() + '-' + nextdate.getMonth() + '-' + nextdate.getFullYear();
            $("#nextDate").val(formattedDate);
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }
        if (units == "Month") {
            //insert the new field..
            nextdate = new Date(new Date().getTime() + (freq * 30 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate.getDate() + '-' + nextdate.getMonth() + '-' + nextdate.getFullYear();
            $("#nextDate").val(formattedDate);
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }

        $.ajax({

            url: 'week.php',
            data: 'unit=' + units,
            success: function (data) {

                $('#WeekMonth').html(data);


            }
        });


    } else {
        $.growl.error({message: "Please select Valid unit..!", size: 'large'});
    }
}

var nextdate1;
function calculateValueFull(str) {

    units = $("#newfullUnits").val();
    var days = 0;
    var formattedDate;
    var d = new Date();

    if (units != '') {
        freq = $("#newfullNextFrequency").val();
        var endDate = $("#newfullEndDate").val();

        if (freq == "" || freq == 0 || endDate == '00-00-0000') {
            alert("please enter frequency");
            return;
        }

        if (units == "Week") {
            nextdate1 = new Date(new Date().getTime() + (freq * 7 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate1.getDate() + '-' + nextdate1.getMonth() + '-' + nextdate1.getFullYear();
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }
        if (units == "Year") {
            nextdate1 = new Date(new Date().getTime() + (freq * 365 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate1.getDate() + '-' + nextdate1.getMonth() + '-' + nextdate1.getFullYear();
            $("#nextDate").val(formattedDate);
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }
        if (units == "Month") {
            //insert the new field..
            nextdate1 = new Date(new Date().getTime() + (freq * 30 * 24 * 60 * 60 * 1000));
            formattedDate = nextdate1.getDate() + '-' + nextdate1.getMonth() + '-' + nextdate1.getFullYear();
            $("#nextDate").val(formattedDate);
            var description = 'occurs every ' + units + ' on ' + formattedDate;
            $("#description").val(description);
        }

        $.ajax({

            url: 'week.php',
            data: 'unit=' + units,
            success: function (data) {

                $('#FWeekMonth').html(data);


            }
        });


    } else {
        $.growl.error({message: "Please select Valid unit..!", size: 'large'});
    }
}


function clearValue() {
    $("#newSingleNextFrequency").val("");
    $("#newSingleEndDate").val("");
    $("#description").val("");
}

function clearValue1() {
    $("#nextFrequency1").val("");
    $("#nextDat1e").val("");
    $("#endDate1").val("");
    $("#description").val("");
}


function saveData(id) {

    var currentDate = $("#currentDate").val();
    var nextDate = $("#nextDate").val();
    var freq = $("#nextFrequency").val();
    var units = $("#units").val();
    var endDate = $("#endDate").val();
    var maintainKey = id;

    //alert(id);

    if (maintainKey == "") {
        maintainKey = 'NO';
    }

    //return false;

    if (endDate == '' || endDate == null) {
        alert('Please enter end Date');
        return;
    }

    // curernt and enddate valisdation

    var from = new Date();


    var to = new Date();
    var str = endDate;
    var str2 = currentDate;
    var fromDateArray = str2.split("-")
    from.setFullYear(parseInt(fromDateArray[2]));
    from.setMonth(parseInt(fromDateArray[1]) - 1);  // months indexed as 0-11, substract 1
    from.setDate(parseInt(fromDateArray[0]));

    var dateArray = str.split("-")
    to.setFullYear(parseInt(dateArray[2]));
    to.setMonth(parseInt(dateArray[1]) - 1);  // months indexed as 0-11, substract 1
    to.setDate(parseInt(dateArray[0]));     // setDate sets the month of day

    var today = new Date();
    var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);

    if (endAfterStart(from, to)) {
        var day;

        if (units == 'Week')
            day = $('#weekday').val();

        if (units == 'Month')
            day = $('#monthDate').val();

        if (units != 'Year') {
            if (day == null || day == '') {
                alert('Please select occurance Date');
                return;
            }
        }

        $.ajax({
            url: "saveMaintainance.php",
            method: 'POST',
            data: "currentDate=" + currentDate + "&nextDate=" + nextDate + "&freq=" + freq + "&units=" + units + "&day=" + day + "&endDate=" + endDate + "&maintain=" + maintainKey,
            success: function (data) {
                if (data == 'Succesfully Updated') {
                    clearValue();
                    //$('#saveStatus').html("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Succesfully Updated</div>");
                    $.growl.notice({message: "Updated Succesfully...!", size: 'large'});
                } else {
                    //$('#saveStatus').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+data+"</div>");
                    $.growl.error({message: "Updation Failed..!", size: 'large'});
                }

            }
        });
    } else {
        alert('Enter Valid end Date');
    }
}


function saveFullData() {
    var currentDate = $("#newfullCurrentDate").val();
    var nextDate = nextdate1;
    var freq = $("#newfullNextFrequency").val();
    var units = $("#newfullUnits").val();
    var endDate = $("#newfullEndDate").val();
    var type = $("#categoryList").val();

    //alert(id);

    //return false;

    if (endDate == '' || endDate == null) {
        alert('Please enter end Date');
        return;
    }

    // curernt and enddate valisdation

    var from = new Date();


    var to = new Date();
    var str = endDate;
    var str2 = currentDate;
    var fromDateArray = str2.split("-")
    from.setFullYear(parseInt(fromDateArray[2]));
    from.setMonth(parseInt(fromDateArray[1]) - 1);  // months indexed as 0-11, substract 1
    from.setDate(parseInt(fromDateArray[0]));

    var dateArray = str.split("-")
    to.setFullYear(parseInt(dateArray[2]));
    to.setMonth(parseInt(dateArray[1]) - 1);  // months indexed as 0-11, substract 1
    to.setDate(parseInt(dateArray[0]));     // setDate sets the month of day

    var today = new Date();
    var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);


    var day;

    if (units == 'Week')
        day = $('#weekday').val();

    if (units == 'Month')
        day = $('#monthDate').val();

    if (units != 'Year') {
        if (day == null || day == '') {
            alert('Please select occurance Date');
            return;
        }
    }

    $.ajax({
        url: "saveFullPeriodicMaintainance.php",
        method: 'GET',
        data: "artefactCode=" + artefactCodeSelected + "&currentDate=" + currentDate + "&nextDate=" + nextDate + "&freq=" + freq + "&units=" + units + "&day=" + day + "&endDate=" + endDate + "&type=" + type,
        success: function (data) {
            if (data == 'Succesfully Updated') {
                clearValue();
                pop.close();
                //$('#saveStatus').html("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Succesfully Updated</div>");
                $.growl.notice({message: "Updated Succesfully...!", size: 'large'});
            } else if (data == 'NoArtefactHere') {
                //$('#saveStatus').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+data+"</div>");
                $.growl.error({message: "Updation Failed..No Artefact Here!", size: 'large'});
            } else {
                $.growl.error({message: "Updation Failed..!", size: 'large'});
            }


        }
    });

}

function saveSingleData() {

    var endDate = $("#newfullEndDate1").val();
    var currentDate = $("#newfullCurrentDate1").val();

    if (endDate == '' || endDate == null) {
        alert('Please enter end Date');
        return;
    }


    var from = new Date();
    var to = new Date();

    var str = endDate;
    var str2 = currentDate;

    var fromDateArray = str2.split("-")

    from.setFullYear(parseInt(fromDateArray[2]));
    from.setMonth(parseInt(fromDateArray[1]) - 1);  // months indexed as 0-11, substract 1
    from.setDate(parseInt(fromDateArray[0]));

    var dateArray = str.split("-")

    to.setFullYear(parseInt(dateArray[2]));
    to.setMonth(parseInt(dateArray[1]) - 1);  // months indexed as 0-11, substract 1
    to.setDate(parseInt(dateArray[0]));     // setDate sets the month of day


    $.ajax({
        url: "saveFullSPeriodicSchedule.php",
        method: 'GET',
        data: "currentDate=" + currentDate + "&endDate=" + endDate +"&type="+ $("#categoryList").val(),
        success: function (data) {
            if (data == 'Succesfully Updated') {
                clearValue();
                //$('#saveStatus').html("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Succesfully Updated</div>");
                $.growl.notice({
                    message: "Updated Succesfully...!",
                    size: 'large'
                });
                pop3.close();
            } else {
                //$('#saveStatus').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+data+"</div>");
                $.growl.error({message: "Updation Failed..!", size: 'large'});
            }

        }


    });
}

function getFull(Type, Code, Title) {

    $.ajax({
        url: "artefactMaintain.php",
        data: 'type=' + Type + '&page=AddArtefact&artefactCode=' + Code + '&artefactTitle=' + Title + '&full=NO',
        success: function (data) {
            $('#duration').html(data);
            $('.endDate').datepicker({format: "dd-mm-yyyy"});
        }
    });
}


function getFull1(Type) {


    $.ajax({
        url: "artefactMaintain.php",
        data: 'type=' + Type + '&full=YES',
        success: function (data) {
            $('#full-body').html(data);
            $('#endDate1').datepicker({format: "dd-mm-yyyy"});
        }
    });
}


function getSingle(Type, Code, Title) {

    $.ajax({
        url: "singleMaintain.php",
        data: 'type=' + Type + '&page=AddArtefact&artefactCode=' + Code + '&artefactTitle=' + Title,
        success: function (data) {
            $('#duration').html(data);
            $('.sendDate').datepicker({
                format: "dd-mm-yyyy",
                onSelect: function (selected) {
                    alert("hello");
                }
            });

            $('#sendDate').on('input', function () {
                var value = $(this).val();
                if ($(this).data("lastval") != value) {
                    $(this).data("lastval", value);


                    clearTimeout(timerid);
                    timerid = setTimeout(function () {

                        //change action
                        alert(value);

                    }, 500);

                }
                ;
            });
        }
    });
}

function updateDescMonth(str) {

    var description;
    description = 'occurs every ' + freq + " " + units + ' on ' + str;
    $("#desctext").val(description);
}

function updateDescWeek(str) {

    var description;
    description = 'occurs every ' + freq + " " + units + ' on ' + $('#weekday>option:selected').html();
    $("#desctext").val(description);
}

function fullSchedule() {

    if ($('#categoryList').val() != '') {

        var type = $('#categoryList').val();
        var conf = "You want to sure to create schedule for all artefact in " + type;


        pop = $('#fullModel').bPopup({
            modalClose: false,
            position: [300, 20],
            closeClass: 'closep'
        });

        $('#fullType').bootstrapSwitch({
            onText: 'Periodic',
            offText: 'Sporadic'
        });


        $('#fullType').on('switchChange.bootstrapSwitch', function (event, state) {

            if ($('#typeSelect').val() != '') {

                if (state == true) {
                    getFull1($("#categoryList").val());
                } else {

                    //getSingle(aType,aCode,aTitle);

                }
            } else {
                $.growl.error({message: "Please select artefact Type..!", size: 'large'});
            }

        });
        $('#fillDate').datepicker({format: "dd-mm-yyyy"});
        $('#endDate1').datepicker({format: "dd-mm-yyyy"});
        $('#fullModelForm').submit(function () {

            var endDate = $('#fillDate').val();
            var url = "fullSchedule.php?type=" + type + "&endDate=" + endDate;


            $.get(url, function (data) {

                if (data != 'No') {
                    pop.close();
                    $.growl.notice({message: "Schedule Created Succesfully..!", size: 'large'});
                } else {
                    $.growl.error({message: "failed to Create Schedule..!", size: 'large'});
                }
            });

            return false;
        });

    } else {
        $.growl.error({message: "Please select artefact Type..!", size: 'large'});
    }
}
