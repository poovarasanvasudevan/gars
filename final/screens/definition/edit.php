<?php
define("PAGENAME", "Definition");

session_start();
if (!isset($_SESSION['artefactUser'])) {
    header("Location: index.php");
}

include '../common/DatabaseConnection.php';

$db = new DatabaseConnection();
$db->createConnection();

$pages = $db->getPages($_SESSION['userPK']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="icon"
          type="image/png"
          href="images/logo.png"/>

    <LINK REL="SHORTCUT ICON"
          HREF="images/logo.png">

    <title>Global Archive</title>
    <!-- Bootstrap -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/custom.css" rel="stylesheet"/>
    <link href="../../css/jquery.dataTables.min.css"/>


    <script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="../../js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.js"></script>
    <link href="../../tree/skin-lion/ui.fancytree.css" rel="stylesheet" type="text/css">
    <!-- <link href="ArtefactStyles.css" rel="stylesheet" type="text/css"> -->
    <script src="../../tree/src/jquery.fancytree.js" type="text/javascript"></script>
    <script src="../../tree/src/jquery.fancytree.edit.js" type="text/javascript"></script>
    <script src="../../tree/src/jquery.fancytree.dnd.js" type="text/javascript"></script>


    <script type="text/javascript" src="../../js/jquery.blockUI.js"></script>
    <link rel="stylesheet" href="../../js/jquery-ui.css">
    <link rel="stylesheet" href="../../css/pace.css">
    <script data-pace-options='{ "ajax": true }' src="../../js/pace.js"></script>
    <script src="../../js/layout.js"></script>
    <script src="../../js/jquery.bpopup.min.js"></script>

    <!-- Fancy tree Search -->
    <script src="../../tree/src/jquery.fancytree.filter.js" type="text/javascript"></script>


    <!-- For Context Menu -->
    <script src="../../tree/src/jquery.contextMenu-1.6.5.js"></script>
    <script src="../../tree/src/jquery.fancytree.contextMenu.js"></script>
    <link rel="stylesheet" href="../../css/jquery.contextMenu.css">


    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/jquery.growl.css">
    <script src="../../js/jquery.growl.js"></script>

    <script src="../../js/bootbox.min.js"></script>
    <style>
        #tree {
            height: 550px;
            overflow: auto;
        }

        #AttributeListDiv {
            height: 650px;
        }

        .ui-menu {
            width: 100px;
            font-size: 63%;
            z-index: 3; /* over ext-wide titles */
        }
    </style>
    <script>

        var artefactName;
        var level;
        var parentNode;
        var parentNodeCode;
        var d1, d2;
        $(document).ready(function () {

            $.ajax({
                url: "../common/categoryTypes.php",
                context: document.body,
                success: function (data) {
                    $("#ArtefactTypeinAdd").html(data);
                }
            });


            $("#dialog1").dialog({
                autoOpen: false,
                show: {
                    effect: "blind",
                    duration: 300
                },
                hide: {
                    effect: "explode",
                    duration: 300
                },
                position: ['center', 'center']
            });

            $("#dialog2").dialog({
                autoOpen: false,
                show: {
                    effect: "blind",
                    duration: 300
                },
                hide: {
                    effect: "explode",
                    duration: 300
                },
                position: ['center', 'center']
            });


            $("#NewArtefact").click(function () {
                if ($('#typeSelect').val() != '')
                    d1 = $('#dialog1').bPopup({
                        position: [300, 200],
                        closeClass: 'closep',
                        modalClose: true
                    });

                else
                    $.growl.error({message: "Please Select one Category..!", size: 'large'});
            });


            $("#subItem").click(function () {
                var node;
                if (node = $("#tree").fancytree("getActiveNode")) {
                    level = node.getLevel();
                    if (level < 3) {

                        if ($('#typeSelect').val() != '') {
                            var currentNode = node.title;
                            //var parentNodeCode=node.getParent().key;
                            $('#parentName').val(currentNode);
                            d2 = $('#dialog2').bPopup({
                                position: [300, 200],
                                closeClass: 'closep',
                                modalClose: true
                            });
                            $('#newSubItem').focus();
                            $('#newSubItem').val('');

                            var countChild = getChildCount(node.key);
                            //alert(node.key);
                            $('#newSubItem').val(currentNode + '-' + countChild);

                        }

                    }
                    else
                        $.growl.error({message: "Sorry you Cant add more than 2 level in Tree..!", size: 'large'});
                }
                else
                    $.growl.error({message: "Please Select One Artefact to add Sub Node..!", size: 'large'});

            });


            var tree = $("#tree").fancytree("getTree");

            $("input[name=search]").keyup(function (e) {
                //$("#tree").fancytree("destroy");
                var match = $(this).val();

                if (match == '') {
                    $("#tree").fancytree("getTree").clearFilter();
                } else {
                    $("#tree").fancytree("getTree").filterNodes(match, {autoExpand: true, leavesOnly: true});
                }

            }).focus();

        });


        function getChildCount(parentid) {
            var idConstruct = "#" + parentid;

            var finalid = idConstruct + " ul li";
            var count = $(finalid).length;
            return count + 1;
        }


        function getTree(str) {

            if (str == '') {
                $("#tree").html("<div style='color: grey'>Select Category Type</div>");
                $('#AttributeListDiv').html("<div style='color: grey'>Select Your Option</div>");
                return 0;
            }
            else {
                $('#AttributeListDiv').html("<div style='color: grey'>Please select any Node</div>");
                $.ajax({
                    url: "../common/treeFormation.php",
                    data: "type=" + $("#typeSelect").val(),
                    success: function (data) {
                        $("#tree").fancytree("destroy");
                        $("#tree").html(data);
                        //$("#tree").fancytree();


                        $("#tree").fancytree({
                            autoActivate: true,
                            autoScroll: true,
                            extensions: ['contextMenu', 'filter'],
                            clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
                            keyboard: true,
                            filter: {
                                autoApply: true, // Re-apply last filter if lazy data is loaded
                                counter: true, // Show a badge with number of matching child nodes near parent icons
                                hideExpandedCounter: true, // Hide counter badge, when parent is expanded
                                mode: "hide"  // "dimm": Grayout unmatched nodes, "hide": remove unmatched nodes
                            },
                            quicksearch: true,
                            iconclass: 'definition',
                            activate: function (event, data) {

                                //alert(data.node.key);
                                //This is to Get Complete Details of the Child Node
                                $.ajax({
                                    url: "editAttributeList.php",
                                    data: 'type=' + $("#typeSelect").val() + '&page=AddArtefact&artefactCode=' + data.node.key + '&artefactTitle=' + data.node.title,
                                    success: function (data) {
                                        $('#AttributeListDiv').html(data);
                                    }
                                });


                            },
                            contextMenu: {
                                menu: {
                                    'edit': {'name': 'Add Sub Item', 'icon': 'edit'},
                                    'rename': {'name': 'Rename', 'icon': 'rename'},
                                    'delete': {'name': 'Delete', 'icon': 'cut'}

                                },
                                actions: function (node, action, options) {
                                    //$('#selected-action').text('Selected action "' + action + '" on node ' + node);
                                    //alert(action +" -->"+node.key);

                                    if (action == 'delete') {

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
                                                            url: "deleteArtefact.php",
                                                            data: 'artefactType=' + $("#typeSelect").val() + '&artefactCode=' + node.key,
                                                            success: function (data) {
                                                                if (data == 'success') {
                                                                    $(this).modal('hide');
                                                                    refreshTree();
                                                                    bootbox.alert("Deleted Succesfully..");

                                                                } else {

                                                                    $(this).modal('hide');
                                                                    bootbox.alert("Unable to delete this artefact.")

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

                                    } else if (action == 'edit') {

                                        var node;
                                        if (node = $("#tree").fancytree("getActiveNode")) {
                                            level = node.getLevel();
                                            if (level < 3) {

                                                if ($('#typeSelect').val() != '') {
                                                    var currentNode = node.title;
                                                    //var parentNodeCode=node.getParent().key;
                                                    $('#parentName').val(currentNode);
                                                    d2 = $('#dialog2').bPopup({
                                                        position: [300, 200],
                                                        closeClass: 'closep',
                                                        modalClose: true
                                                    });
                                                    $('#newSubItem').focus();
                                                    $('#newSubItem').val('');
                                                    var countChild = getChildCount(node.key);
                                                    //alert(node.key);
                                                    $('#newSubItem').val(currentNode + '-' + countChild);

                                                }

                                            }
                                            else
                                                $.growl.error({
                                                    message: "Sorry you Cant add more than 2 level in Tree..!",
                                                    size: 'large'
                                                });
                                        }
                                        else
                                            $.growl.error({
                                                message: "Please Select One Artefact to add Sub Node..!",
                                                size: 'large'
                                            });

                                    } else if (action == 'rename') {
                                        var oldname = node.title;
                                        $('#renamedArtefactName').val(oldname);

                                        d3 = $('#renameDialog').bPopup({
                                            position: [300, 200],
                                            closeClass: 'closep',
                                            modalClose: true
                                        });


                                        $('#renameButton').on('click', function () {
                                            var newName = $('#renamedArtefactName').val();
                                            var oldname1 = node.title;
                                            renameArtefact(oldname1, newName);


                                        });


                                    }
                                }
                            }
                        });
                    }
                });
            }
        }


        function refreshTree() {

            $.ajax({
                url: "../common/treeFormation.php",
                data: "type=" + $("#typeSelect").val(),
                success: function (data) {
                    $("#tree").fancytree("destroy");
                    $("#tree").html(data);
                    //$("#tree").fancytree();


                    $("#tree").fancytree({
                        autoActivate: true,
                        autoScroll: true,
                        extensions: ['contextMenu'],
                        clickFolderMode: 3, // 1:activate, 2:expand, 3:activate and expand, 4:activate (dblclick expands)
                        keyboard: true,
                        quicksearch: true,
                        iconclass: 'definition',
                        activate: function (event, data) {

                            //alert(data.node.key);
                            //This is to Get Complete Details of the Child Node
                            $.ajax({
                                url: "editAttributeList.php",
                                data: 'type=' + $("#typeSelect").val() + '&page=AddArtefact&artefactCode=' + data.node.key + '&artefactTitle=' + data.node.title,
                                success: function (data) {
                                    $('#AttributeListDiv').html(data);
                                }
                            });


                        },
                        contextMenu: {
                            menu: {
                                'edit': {'name': 'Add Sub Item', 'icon': 'edit'},
                                'rename': {'name': 'Rename', 'icon': 'rename'},
                                'delete': {'name': 'Delete', 'icon': 'cut'}

                            },
                            actions: function (node, action, options) {
                                //$('#selected-action').text('Selected action "' + action + '" on node ' + node);
                                //alert(action +" -->"+node.key);

                                if (action == 'delete') {
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
                                                        url: "deleteArtefact.php",
                                                        data: 'artefactType=' + $("#typeSelect").val() + '&artefactCode=' + node.key,
                                                        success: function (data) {
                                                            if (data == 'success') {
                                                                $(this).modal('hide');
                                                                refreshTree();
                                                                bootbox.alert("Deleted Succesfully..");

                                                            } else {

                                                                $(this).modal('hide');
                                                                bootbox.alert("Unable to delete this artefact.")

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
                                } else if (action == 'edit') {

                                    var node;
                                    if (node = $("#tree").fancytree("getActiveNode")) {
                                        level = node.getLevel();
                                        if (level < 3) {

                                            if ($('#typeSelect').val() != '') {
                                                var currentNode = node.title;
                                                //var parentNodeCode=node.getParent().key;
                                                $('#parentName').val(currentNode);
                                                d2 = $('#dialog2').bPopup({
                                                    position: [300, 200],
                                                    closeClass: 'closep',
                                                    modalClose: true
                                                });
                                                $('#newSubItem').focus();
                                                $('#newSubItem').val('');
                                                var countChild = getChildCount(node.key);
                                                //alert(node.key);
                                                $('#newSubItem').val(currentNode + '-' + countChild);

                                            }

                                        }
                                        else
                                            $.growl.error({
                                                message: "Sorry you Cant add more than 2 level in Tree..",
                                                size: 'large'
                                            });
                                    }
                                    else
                                        $.growl.error({
                                            message: "Please Select One Artefact to add Sub Node..",
                                            size: 'large'
                                        });
                                } else if (action == 'rename') {
                                    var oldname = node.title;
                                    $('#renamedArtefactName').text(oldname);
                                }
                            }
                        }
                    });
                }
            });
        }
        function actionPerformed(str) {
            if (str == 'Clear') {
                $("#tree").html("<div style='color: grey'>Select Category Type</div>");
                $('#AttributeListDiv').html("<div style='color: grey'> Select Your Option </div>");
            }
            else if (str == 'Save') {
                var DataArray = new Array();
                var nameArray = new Array();
                var length = document.forms.length;
                for (var index = 0; index < length; index++) {
                    if (document.forms[index].id == 'columnValuesForm') {
                        var formData = document.forms[index];
                        var filledCount = 0;
                        for (i = 0; i < formData.length; i++) {
                            if (formData.elements[i].value != '') {
                                filledCount++;
                                DataArray[i] = formData.elements[i].value;
                                nameArray[i] = formData.elements[i].id;
                            }
                            else {
                                DataArray[i] = 'NULL';
                                nameArray[i] = formData.elements[i].id;
                            }
                        }
                        if (filledCount < 2)
                            $.growl.error({message: "Please fill, even known datas..", size: 'large'});
                        else {
                            var activeNodeKey = '';
                            var activeNodeTitle = '';
                            if (node = $("#tree").fancytree("getActiveNode")) {
                                activeNodeKey = node.key;
                                activeNodeTitle = node.title;
                            }

                            if (activeNodeKey) {
                                //alert(node);

                                //alert(nameArray[2]);
                                $.ajax({
                                    url: "SaveAttributes.php",
                                    data: "elem=" + nameArray + "&dataArray=" + DataArray + '&artefactCode=' + activeNodeKey + '&artefactName=' + activeNodeTitle + '&type=' + $("#typeSelect").val(),
                                    success: function (data) {
                                        //$('#AttributeListDiv').html(data);
                                        $.growl.notice({message: "Artefact Succesfully Updated...", size: 'large'});
                                    }
                                })
                            }
                            else
                                $.growl.error({message: "Please Select the artefact in Tree..", size: 'large'});
                        }
                    }
                }

            }
        }

        function getArtefactName() {
            if ($('#newArtefactName').val() != '') {
                //alert('Artefact:'+$('#newArtefactName').val()+' is added');
                //node=$("#tree").fancytree("getRootNode");//.addChildren(obj);
                artefactName = $('#newArtefactName').val();
                var frameData = artefactName + "  Added Succesfully..";
                $.ajax({
                    url: "SaveNewArtefact.php",
                    data: 'artefactName=' + artefactName + '&level=' + (level - 1),
                    success: function (data) {
                        $.growl.notice({message: frameData, size: 'large'});
                        $('#AttributeListDiv').html(data);
                        refreshTree();

                    }
                });
            }

            /* if(node=$("#tree").fancytree("getActiveNode")){
             alert(node.getParent().title);
             } */
            d1.close();
            //$( "#dialog1" ).dialog( "close" );
        }

        function getSubItem() {

            if ($('#newSubItem').val() != '') {
                var activeNode;

                artefactName = $('#newSubItem').val();
                activeNode = $('#parentName').val();

                //alert(activeNode);
                var frameData = artefactName + "  Added Succesfully..";
                $.ajax({
                    url: "addSubItems.php",
                    data: 'ItemName=' + artefactName + '&level=' + level + '&parentNode=' + activeNode,
                    success: function (data) {
                        $.growl.notice({message: frameData, size: 'large'});
                        $('#AttributeListDiv').html(data);
                        refreshTree();
                    }
                });
            }

            d2.close();
            //$( "#dialog2" ).dialog( "close" );

        }


        function renameArtefact(oldName, newName) {
            var urlR = "renameArtefact.php?oldName=" + oldName + "&newName=" + newName;
            var result;
            $.get(urlR, function (data) {
                if (data == "success") {
                    $.growl.notice({message: "Rename Succesful..", size: 'large'});
                    d3.close();
                    refreshTree();
                }
                else {
                    $.growl.error({message: "Fail to rename..", size: 'large'});
                }
            });


        }

    </script>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <!--<div class="col-sm-1 col-xs-2" style="padding-right: 0px;">
                    <img width="89" height="89" src="images/logo-ga.png" alt="Global Arichve">
                </div>-->
                <div class="col-md-12">
                    <div class="logintxt">
                        Global Archives
                    </div>
                </div>
            </div>
            <div class="col-md-5 padding0">
                <div class="col-md-12 text-right logout ">
                    <a href="logout.php">Logout</a>
                </div>
                <div class="col-md-12 tp-login-info">
                    <div class="col-md-7 padding0">
                        <span>Welcome </span><span class="tp-username"><?php echo $_SESSION['fullName']; ?></span>
                    </div>
                    <div class="col-md-5 text-right padding0">
                        <span>Location : </span><span><?php echo $_SESSION['userLocationDesc'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <?php for ($i = 0; $i < sizeof($pages); $i++) {
                        if ($pages[$i]['menutitle'] != '') {
                            if ($pages[$i]['menutitle'] == PAGENAME) {
                                echo "<li class='active'><a href='" . PAGE_DIR . "" . $pages[$i]['dir'] . "/" . $pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            } else {

                                echo "<li><a href='" . PAGE_DIR . "" . $pages[$i]['dir'] . "/" . $pages[$i]['url'] . "' title='" . $pages[$i]['menutitle'] . "'>" . $pages[$i]['menutitle'] . "</a></li>";
                            }
                        }
                    } ?>
                </ul>


                <div class="tab-content">

                    <div class="col-md-12 tile" id="tile">

                        <div class='col-md-12 border-low '>

                            <div class="col-md-3 text-left heading-Bg">
                                <i class="fa fa-pencil fa-2x heading-Bg"></i> <span style="font-size: 22px;">&nbsp;Artefact Definition</span><br>
                            </div>

                            <div class="col-md-9">
                                <div class="col-md-6">
                                    <div id='ArtefactTypeinAdd'>
                                    </div>
                                </div>
                                <div class="col-md-6">


                                    <p class="text-center">
                                        <button type="button" id='saveButton' name='Save' class="btn btn-olive margin5"
                                                onClick='actionPerformed(this.value)' value='Save'>Save
                                        </button>
                                        <button type="button" id='clearButton' name='Clear'
                                                class="btn btn-orange margin5" onClick='actionPerformed(this.value)'
                                                value='Clear'>Clear
                                        </button>
                                    </p>
                                </div>
                            </div>
                            <div class='col-md-12'>

                            </div>

                        </div>


                        <div class="col-md-12">
                            <div class="col-md-3 container1 marginT10" style="height : 55%;">
                                <p class="">
                                    <button type="button" class="btn btn-olive" id='NewArtefact'>Add Artefact</button>
                                    <button type="button" class="btn btn-orange" id='subItem'>Add Sub Item</button>
                                </p>
                                <input type="text" name='search' placeholder="search for artefact.."
                                       class='form-control'>

                                <div id='tree' class='scrollbar style-1 container2'>

                                </div>
                            </div>

                            <div class='col-md-9' style="height: 50%;">
                                <div class="col-md-12 attribute1 scrollbar style-1 marginT10" id='AttributeListDiv'
                                     style="background: none;">
                                </div>
                                <div class="col-md-12">

                                </div>
                            </div>


                        </div>


                    </div>


                    <div class="modal-dialog" id='renameDialog' style="display: none;">

                        <div class="modal-content">
                            <div class="modal-header header-color">
                                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                                style="color: white;"></span>&nbsp;&nbsp;Rename
                                    Artefact</h4>
                            </div>
                            <div class="modal-body">
                                New ID:
                                <input type='text' id='renamedArtefactName' required class='form-control'/>
                            </div>
                            <div class="modal-footer">
                                <button class='btn btn-primary' id='renameButton'> Rename</button>
                                <button class='btn btn-warning closep closeDialog'> Close</button>
                            </div>
                        </div>
                    </div>


                    <div class="modal-dialog" id='dialog1' style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header header-color">
                                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                                style="color: white;"></span>&nbsp;&nbsp;Add
                                    New Artefact</h4>
                            </div>
                            <div class="modal-body">
                                Artefact ID:
                                <input type='text' id='newArtefactName' required class='form-control'/>
                            </div>
                            <div class="modal-footer">
                                <button id='addArtefactButton' onclick='getArtefactName()' class='btn btn-primary'>
                                    Add
                                </button>
                                <button onClick='closeDialog(this.value)' value='dialog1'
                                        class='btn btn-warning closep closeDialog'> Close
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="modal-dialog" id='dialog2' style="display: none;">
                        <div class="modal-content">
                            <div class="modal-header header-color">
                                <h4 class="modal-title header-text-color"><span class='glyphicon glyphicon-pencil'
                                                                                style="color: white;"></span>&nbsp;&nbsp;Add
                                    New Sub Artefact</h4>
                            </div>

                            <div class="modal-body">
                                <table style='width:100%;'>
                                    <tr>
                                        <td>Parent Name</td>
                                        <td><input type='text' id='parentName' style='color:grey' value='' readonly
                                                   class='form-control'/></td>
                                    </tr>

                                    <tr>
                                        <td>Sub Item:</td>
                                        <td><input type='text' id='newSubItem' value='' required class='form-control'/>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <div class="modal-footer">
                                <button id='addSubItem' onclick='getSubItem()' class='btn btn-success'> Add</button>
                                <button class='closeDialog btn closep btn-warning' onClick='closeDialog(this.value)'
                                        value='dialog2'> Close
                                </button>
                            </div>

                        </div>

                    </div>


                </div>


                <div class="col-sm-12" id="pagefooter">
                    <p>&copy; 2015 SRCM. All Rights Reserved.</p>
                </div>


            </div>
        </div>
    </div>
</div>

</body>
</html>