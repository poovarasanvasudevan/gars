/**
 * Created by poovarasan on 22/8/15.
 */
$(function () {

    //$(".loadingUser").load("allUser.php");
    $('#userTable').DataTable({
        "lengthMenu": [3, 5, 10, 20, 40, 60, 80, 100],
        "pageLength": 5,
        "language": {
            search: ' <button type="button" class="btn-searchside" id="new" style="margin-right: 10px;" title="click to add new user"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i>New </span></button><div class="input-group ip"> _INPUT_ <span class="input-group-addon searchbg"><i class="glyphicon glyphicon-search searchIcon"></i></span>'
        }
    });
    $('#userTable').on('click', '.deleteButton', function (e) {

        e.preventDefault();
        var value = $(this).attr("name");

        var action = $(this).attr("value");

        if (action == "Active") {

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
                                url: "activeUser.php",
                                data: 'userpk=' + value,
                                success: function (data) {
                                    if (data == 'success') {
                                        $(this).modal('hide');
                                        //$('#userTable').dataTable().fnDestroy();
                                        //$(".loadingUser").load("allUser.php");
                                        //$('#userTable').DataTable();

                                        bootbox.alert("Updated Succesfully..");
                                        window.location = "userView.php";

                                    } else {

                                        $(this).modal('hide');
                                        bootbox.alert("Unable to update this user.")

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

        } else {
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
                                url: "deleteUser.php",
                                data: 'userpk=' + value,
                                success: function (data) {
                                    if (data == 'success') {
                                        $(this).modal('hide');
                                        //$('#userTable').dataTable().fnDestroy();
                                        //$(".loadingUser").load("allUser.php");
                                        //$('#userTable').DataTable();

                                        bootbox.alert("Deleted Succesfully..");
                                        window.location = "userView.php";
                                    } else {

                                        $(this).modal('hide');
                                        bootbox.alert("Unable to delete this user.")

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


    });
    $('#new').bind('click', function (e) {
        e.preventDefault();
        $('#status').html("");
        $('#myModal').bPopup({
            modalClose: false,
            closeClass: 'closep',
            onClose: function () {
                location.reload();
            }
        });

        $('#newUserForm').submit(function (event) {

            var data = 'abyasiId=' + $('#abyasiId').val() + '&password=' + $('#password').val() + '&email=' + $('#email').val() + '&firstName=' + $('#firstName').val() + '&middleName=' + $('#middleName').val() + '&lastName=' + $('#lastName').val() + '&location=' + $('#location').val() + '&roles=' + $('#roles').val();
            $.ajax({
                url: "saveUser.php",
                method: 'GET',
                data: data,
                success: function (data) {
                    if (data == 'success') {
                        $('#status').html("<div class='alert alert-success alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "User Created Succesfully" +
                        "</div>");
                    } else {
                        $('#status').html("<div class='alert alert-danger alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Failed to Create User" +
                        "</div>");
                    }
                }
            });

            $("#newUserForm").trigger('reset');
            return false;
            //
        });

    });

    $('#userTable').on('click', '.editButton', function (e) {
        e.preventDefault();
        var value = $(this).attr("name");

        var fname;
        var middleName;
        var email;
        var lname;
        var abuasiId;
        var location;
        var role;
        var password;

        $.getJSON('getUserDetail.php?id=' + value, function (data) {
            for (var i = 0, len = data.length; i < len; i++) {
                fname = data[i].FirstName;
                middleName = data[i].MiddleName;
                email = data[i].EmailId;
                lname = data[i].LastName;
                abuasiId = data[i].AbhyasiID;
                location = data[i].LocationFk;
                role = data[i].RoleFk;
                password = data[i].Password;

                console.log(password);
            }
            $('#eemail').val(value);
            $('#efirstName').val(fname);
            $('#elastName').val(lname);
            $('#emiddleName').val(middleName);
            $('#eemail').val(email);
            $('#epassword').val(password);
            $('#eabyasiId').val(abuasiId);

            $('#elocation').val(location);
            $('#eroles').val(role);
            $('#estatus').html("");
            $('#loadem').bPopup({modalClose: false, closeClass: 'closep'});
        });


        $('#updateUser').click(function (event) {

            var data = 'abyasiId=' + $('#eabyasiId').val() + '&password=' + $('#epassword').val() + '&email=' + $('#eemail').val() + '&firstName=' + $('#efirstName').val() + '&middleName=' + $('#emiddleName').val() + '&lastName=' + $('#elastName').val() + '&location=' + $('#elocation').val() + '&roles=' + $('#eroles').val() + '&user=' + value;
            $.ajax({
                url: "updateUser.php",
                method: 'GET',
                data: data,
                success: function (data) {
                    if (data == 'success') {
                        $('#estatus').html("<div class='alert alert-success alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "User Updated Succesfully" +
                        "</div>");
                    } else {
                        $('#estatus').html("<div class='alert alert-danger alert-dismissible' role='alert'>" +
                        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" +
                        "Failed to update User" +
                        "</div>");
                    }
                }
            });

            $("#enewUserForm").trigger('reset');
        });


    });
});