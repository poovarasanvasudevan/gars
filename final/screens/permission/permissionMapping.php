<?php

include "../common/DatabaseConnection.php";
$db = new DatabaseConnection();
$db->createConnection();
$role = $_GET['role'];

$res1 = "   select p.PagePk from page p inner join
				role_page_mapping rm
				on p.pagepk = rm.pagefk
				where rm.rolefk='$role'";


$res = $db->setQuery($res1);
$pages = array();
if ($res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        $pages[] = $r['PagePk'];
    }
}


$res2 = $db->setQuery("select * from page where iscommon='n' or iscommon is null");
if ($res2->num_rows > 0) {
    ?>
    <form name='rolemapForm' id='rolemapForm'>
    <table class='table table-striped table-hover dataTable no-footer clearfix '>
        <thead>
        <tr>
            <th class='col-md-6'>Module</th>
            <th class='col-md-3'>Page</th>
            <th class='col-md-3'>Allow All <input type="checkbox" id='selectall' onclick="selectAll(this)"></th>
        </tr>
        </thead>

        <tbody>
        <?php
        while ($row = $res2->fetch_assoc()) {
            $checked = '';

            for ($i = 0; $i < sizeof($pages); $i++) {

                if ($row['PagePk'] == $pages[$i]) {
                    $checked = 'checked';
                }
            }


            ?>
            <tr role='row'>

                <td><?php echo $row['PageName'] ?></td>
                <td><?php echo $row['URL'] ?></td>

                <td><input type="checkbox" value='<?php echo $row['PagePk'] ?>' name='pagePermission'
                           id='<?php echo $row['PagePk'] ?>' class='pagePermission' <?php echo $checked; ?>></td>

            </tr>



        <?php
        }


        ?> </tbody>
    </table>
    <div class='modal-footer'>

        <input type='submit' id='save' class='btn btn-success' value='Save'/>
        <input type='reset' class='btn btn-warning' value='Reset'/>
    </div>

    </form><?php
} else {

    echo "Sorry no Pages mapped to this role..";
}

?>