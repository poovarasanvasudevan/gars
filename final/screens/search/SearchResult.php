<?php

include_once '../common/DatabaseConnection.php';
session_start();
$typeID = $_GET['ID'];
$attributeValues = array();
$attributeValues = explode(",", $_GET['dataArray']);
$requiredColumnBool = explode(",", $_GET['checkArray']);
$attributeColumns = $_SESSION['ColumnArray'];
$requiredColumns = array();
$ColumnAttributes = array();
$NumberofRows = 0;
$_SESSION['type'] = $_GET['type'];

$supportedType = array('Video', 'VHS');
$isSupported = false;
//array_unshift($attributeColumns, "artefactcode");
if (isset($_SESSION['type'])) {

    $db = new DatabaseConnection();
    $db->createConnection();

    //print_r($attributeColumns);

    for ($i = 0; $i < sizeof($supportedType); $i++) {
        if ($_GET['type'] == $supportedType[$i]) {
            $isSupported = true;
        }
    }


    //Calling Procedure based on the Category to create a new Table
    //$temptable="call AttributeList('$_SESSION[type]')";
    //$procedure=$db->setQuery($temptable);


    //Query to get temp table columns. temp table created via procedure and columns are hardcoded
    $tempTableColumnQuery = 'show columns from ' . $_SESSION['type'] . 'Attributes';

    //echo $tempTableColumnQuery;
    $tempcolumns = $db->setQuery($tempTableColumnQuery);
    $tempTableColumnNames = array();

    echo <<<endl

endl;


    //Columns for the result
    echo "<table id='printTable' class='table table-hover dataTable no-footer clearfix'>";
    echo "<thead><tr role='row'>";
    if (isset($tempcolumns->num_rows)) {
        if ($tempcolumns->num_rows > 0) {
            while ($row = $tempcolumns->fetch_assoc()) {
                $tempTableColumnNames[] = $row['Field'];
            }
        }
    } else
        echo "No Columns for your Search";

    //Get Number of Columns required for output
    $colCount = 0;
    for ($i = 0; $i < sizeof($requiredColumnBool); $i++) {
        if ($requiredColumnBool[$i] == 'true') {
            $colCount++;
        }
    }
    $displayColumns = array();//columns to display in paging page

    //required columns concatination for dynamic query
    if ($colCount > 0) {

        for ($index = 0; $index < sizeof($attributeColumns); $index++) {
            if ($requiredColumnBool[$index] == 'true') {
                echo "<th>" . $attributeColumns[$index] . '</th>';
                $displayColumns[] = $attributeColumns[$index];
            }
        }

        $col = 'Select `artefactCode`, `';
        $colCountCalculate = 0;
        for ($i = 0; $i < sizeof($requiredColumnBool); $i++) {
            if ($colCount == $colCountCalculate) {
                $col = $col . '';
                break;
            }
            if ($requiredColumnBool[$i] == 'true') {
                $col = $col . '' . $attributeColumns[$i];
                $requiredColumns[] = $attributeColumns[$i];
                $colCountCalculate++;
            }
            if ($requiredColumnBool[$i] == 'true' && $colCount > $colCountCalculate)//Not Required && $colCount!=$colCountCalculate
                $col = $col . '`,`';
        }
        $col = $col . '`';
    } else {
        $col = 'Select * ';

        if ($isSupported) {
            echo "<th>Preview</th>";
        }
        for ($index = 0; $index < sizeof($tempTableColumnNames); $index++) {

            echo "<th>" . $tempTableColumnNames[$index] . '</th>';
            $displayColumns[] = $tempTableColumnNames[$index];
        }
    }

    //If Category Id is given or not :Fast search
    if ($typeID == '') {

        //To get the count of entered attributes.
        $count = 0;
        for ($i = 0; $i < sizeof($attributeValues); $i++) {
            if ($attributeValues[$i] != 'NULL') {
                $count++;
            }
        }

        $countCalculate = 0;
        $attributesLength = sizeof($attributeValues);

        //Dynamic Query Generation based on the attributes values
        if ($count > 0) {
            $resultQuery = $col . ' from ' . $_SESSION['type'] . 'Attributes where ';

            //$attributeColumns array contain Column Names and $attributeValues contain attribute values
            for ($index = 0; $index < $attributesLength; $index++) {
                if (isset($attributeColumns[$index])) {
                    if ($attributeValues[$index] != 'NULL') {
                        $resultQuery = $resultQuery . '`' . $attributeColumns[$index] . '` like \'%' . $attributeValues[$index] . '%\'';
                        $countCalculate++;
                    }
                }
                if ($attributeValues[$index] != 'NULL' && $countCalculate < $count) {//Not Required  && $count!=$countCalculate
                    $resultQuery = $resultQuery . ' and ';

                }
            }
            //echo "Final Query : ".$resultQuery;
        } else
            $resultQuery = $col . ' from ' . $_SESSION['type'] . 'Attributes';
        echo '</thead></tr>';
        echo "<tbody class='tb'>";
        $DataAvailable = '';// create a paging if a data is available
        try {
            //echo $resultQuery;
            $finalData = $db->setQuery($resultQuery);

            //echo $finalData;
            if (isset($finalData->num_rows)) {
                $DataAvailable = 'Available';
                if ($finalData->num_rows > 0) {
                    while ($row = $finalData->fetch_assoc()) {
                        $NumberofRows++;
                        echo "<tr role='row'>";
                        try {
                            //If required Columns are not selected
                            if ($colCount == 0) {
                                for ($index = 0; $index < sizeof($tempTableColumnNames); $index++) {
                                    if (isset($row[$tempTableColumnNames[$index]])) {
                                        if ($index == 0) {
                                            if ($isSupported) {
                                                echo "<td><div class='vp'><button class='btn btn-primary vplay' title='Play'  id=" . $db->getFilePath($row[$tempTableColumnNames[$index]], $_SESSION['type']) . "><span class='glyphicon glyphicon-play' style='color:#fff;'></span></button></div></td>";
                                            }
                                            echo "<td><a href='#' class='creport'>" . $row[$tempTableColumnNames[0]] . "</a></td>";
                                            //echo "<td><a href='conditionalReport.php?artefactCode=".$row[$tempTableColumnNames[$index]]."'>".$row[$tempTableColumnNames[$index]]."</a></td>";
                                        } else
                                            echo '<td>' . $row[$tempTableColumnNames[$index]] . '</td>';
                                    } else
                                        echo '<td></td>';
                                }
                            } else {
                                //If required Columns are Selected
                                for ($index = 0; $index < $colCount; $index++) {
                                    if ($index == 0) {

                                        echo "<td><a href=href='#' class='creport'>" . $row[$requiredColumns[$index]] . '</a></td>';
                                    } else
                                        echo '<td>' . $row[$requiredColumns[$index]] . '</td>';
                                }

                            }
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                        echo "</tr>";
                    }
                }
            } else {
                $DataAvailable = 'Not Available';
                echo "</br>Sorry No Result for your Search</br>";
            }

        } catch (Exception $e) {
            echo 'Exception :' . $e->getMessage();
        }


    } else {
        $resultQuery = $col . ' from ' . $_SESSION['type'] . 'Attributes where artefactCode in (
								select artefactCode from artefact where artefactname like \'%' . $typeID . '%\')';
        try {

            $finalDatas = $db->setQuery($resultQuery);
            if ($finalDatas->num_rows > 0) {
                $DataAvailable = 'Available';
                while ($row = $finalDatas->fetch_assoc()) {
                    $NumberofRows++;
                    echo "<tr>";
                    try {
                        //If Required Columns are not Selected
                        if ($colCount == 0) {
                            for ($index = 0; $index < sizeof($tempTableColumnNames); $index++) {
                                if (isset($row[$tempTableColumnNames[$index]]))
                                    echo '<td>' . $row[$tempTableColumnNames[$index]] . '</td>';
                                else
                                    echo '<td> </td>';
                            }
                        } else {
                            //If Required Columns are Selected
                            for ($index = 0; $index < $colCount; $index++)
                                echo '<td>' . $row[$requiredColumns[$index]] . '</td>';
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    echo "</tr>";
                }
            } else {
                $DataAvailable = 'Not Available';
                echo "</br>Sorry No Result for your Search</br>";
            }

        } catch (Exception $e) {
            echo 'Exception :' . $e->getMessage();
        }

    }
    echo "</tbody></table>";
    /* echo <<<endl
               <div class="tab-pane" id="tab-add"> </div>
               <div class="tab-pane" id="tab-edit"></div>					  
               <div class="tab-pane" id="tab-pendingtask"> </div>
              
endl; */
    $_SESSION['DataAvailable'] = $DataAvailable;
    $_SESSION['totalRows'] = $NumberofRows;
    $_SESSION['resultQuery'] = $resultQuery;
    //print_r($displayColumns);
    $_SESSION['columns'] = $displayColumns;
    $db->closeConnection();
    unset($_SESSION['type']);

} else {
    echo 'problem';
    //die("Select the type first");
}
//echo "<p style='color:grey'> Please Select your Category</p>";
//echo "Final Query : ".$resultQuery;

?>