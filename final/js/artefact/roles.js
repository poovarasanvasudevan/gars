/**
 * Created by poovarasan on 22/8/15.
 */
$(function () {

    $('#RoleTable').DataTable({
        "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
        "pageLength": 5,
        "language": {
            search: '<button type="button" class="btn-searchside" id="newRole" style="margin-right: 8px;" title="click to add new role"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i>&nbsp;New </span></button> <div class="input-group ip"> _INPUT_ <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'
        }
    });

    $('#newRole').on('click', function (e) {
        e.preventDefault();
        $('#myModal').bPopup({
            modalClose: false, closeClass: 'closep', onClose: function () {
                $('#status').html("");
                location.reload();
            }
        });

        $('#newRoleForm').submit(function () {

            var data = 'rolecode=' + $('#rolecode').val() + '&roledesc=' + $('#roleName').val();


            $.ajax({
                url: "saveRole.php",
                method: 'GET',
                data: data,
                success: function (data) {
                    if (data == 'success') {
                        $('#status').html("<div class='alert alert-success alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Role Created Succesfully" +
                        "</div>");
                    } else {
                        $('#status').html("<div class='alert alert-danger alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Failed to Create Role" +
                        "</div>");
                    }
                }
            });

            $("#newRoleForm").trigger('reset');
            $("#RoleTable").load();
            return false;
        });
    });

    $('#RoleTable').on('click', '.editRole', function (e) {
        e.preventDefault();

        var value = $(this).attr("name");

        var rolecode;
        var roleName;

        $.getJSON('getRoleDetail.php?id=' + value, function (data) {
            for (var i = 0, len = data.length; i < len; i++) {

                rolecode = data[i].code;
                roleName = data[i].description;
            }

            $("#erolecode").val(rolecode);
            $("#eroleName").val(roleName);

            $('#editModel').bPopup({
                modalClose: false,
                position: [300, 200],
                closeClass: 'closep',
                onClose: function () {
                    $('#estatus').html("");
                    location.reload();
                }
            });
        });


        $('#editModelForm').submit(function () {

            var data = 'rolecode=' + $('#erolecode').val() + '&roledesc=' + $('#eroleName').val() + '&id=' + value;


            $.ajax({
                url: " updateRole.php",
                method: 'GET',
                data: data,
                success: function (data) {
                    if (data == 'success') {
                        $('#estatus').html("<div class='alert alert-success alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Role Updated Succesfully" +
                        "</div>");
                    } else {
                        $('#estatus').html("<div class='alert alert-danger alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Failed to Update Role" +
                        "</div>");
                    }
                }
            });

            $("#editModelForm").trigger('reset');
            $("#RoleTable").load();
            return false;
        });
    });


    $('#RoleTable').on('click', '.deleteRole', function () {
        var value = $(this).attr("name");
        var data = "id=" + value;


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
                            url: "deleteRole.php",
                            method: 'GET',
                            data: data,
                            success: function (data) {
                                if (data == 'success') {
                                    location.reload();
                                    bootbox.alert("Deleted Succesfully..");
                                } else {
                                    bootbox.alert("Unable to delete this Role.");
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
    });
});