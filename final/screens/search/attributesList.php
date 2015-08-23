<?php

include_once '../common/DatabaseConnection.php';
session_start();
$_SESSION['type'] = $_GET['type'];
$db = new DatabaseConnection();
$db->createConnection();
$dropdownArray = array();
$AttributeComaparison = array();
$dropdownResultArray = array();
$inputType = array();
$columnArray = array();
$aListCode = array();


$checkbox = true;
$search = true;
$isSearchable = "isSearchable='y'	and ";


//Query to List all the Attributes of the Selected Category. Selected Category is in Session

/**
 * Example Query Formation
 *
 * Select distinct AttributeCode,DataType,Attributes
 * from Attributes, ArtefactType
 * where Attributes.ArtefactTypeCode = ArtefactType.ArtefactTypeCode
 * and
 * isSearchable ='y' and
 * ArtefactType.ArtefactTypeCode='BOK'
 *
 * */
$dropdownArrayListCodeQuery = "Select distinct AttributeCode,DataType,Attributes
							from attributes, artefacttype
							where attributes.ArtefactTypeCode = artefactType.ArtefactTypeCode
							and
							$isSearchable
							artefactType.ArtefactTypeCode = '$_SESSION[type]';";


$dropdownArrayListCode = $db->setQuery($dropdownArrayListCodeQuery);
/**
 * Detetmine which column of which type
 *
 * */


/**
 *
 * example Result of above query
 *
 *  AttributeCode,    DataType,    Attributes
 *  -----------------------------------------
 *     'A1050',        'varchar',    'BookTitle'
 *     'A1053',        'Varchar',    'Language'
 *     'A1054',        'varchar',    'Author'
 *     'A1055',        'varchar',    'Bind'
 *
 */

if ($dropdownArrayListCode->num_rows > 0) {
    while ($row = $dropdownArrayListCode->fetch_assoc()) {
        $dropdownArray[] = $row['AttributeCode'];
        $columnArray[] = $row['Attributes'];
        if ($row['DataType'] == 'varchar') {
            $inputType[] = 'text';
        } else if ($row['DataType'] == 'Int') {
            $inputType[] = 'text';
            //$numberPattern[$numberCount++]="pattern='^[0-9]{1,20}'";
        } else if ($row['DataType'] == 'date') {
            $inputType[] = 'date';
        } else {
            $inputType[] = 'text';
        }
    }
} else
    echo "Sorry No Attributes for this Category";

//Query to Find lists of datas based on Category, to make it as a dropdown 

/**
 * Query to check whether which column in the artefact type is droup down
 * Example Query
 *
 * Select AttributeCode,AListCode
 * from Attributes, ArtefactType
 * where Attributes.ArtefactTypeCode = ArtefactType.ArtefactTypeCode
 * and
 * PickFlag='y'
 * and
 * ArtefactType.ArtefactTypeCode ='BOK'
 *
 *
 * */
$selectSelectionQuery = "Select AttributeCode,AListCode
							from Attributes, ArtefactType
							where Attributes.ArtefactTypeCode = ArtefactType.ArtefactTypeCode
							and 
							PickFlag='y'
							and
							IsSearchable='y'
							and
							ArtefactType.ArtefactTypeCode = '$_SESSION[type]';";
$selectSelection = $db->setQuery($selectSelectionQuery);

/**
 *
 * Example Result for above query
 *
 * # AttributeCode,    AListCode
 * ------------------------------------------
 * A1053,            Language
 * A1054,             Author
 * A1055,             Bind
 *
 * */


if ($selectSelection->num_rows > 0) {
    while ($row = $selectSelection->fetch_assoc()) {
        $AttributeComaparison[] = $row['AttributeCode'];  //store attribute code to compare with column
        $aListCode[] = $row['AListCode'];                    // stores list name which has attribute for droup down
    }
}


//Query to get a dropdown values like authors list, type of Bind.
/**
 * Looping on to the select box list
 * Based on the attribute code
 * this will gives values for eash droup down
 *
 * */
for ($index = 0; $index < sizeof($AttributeComaparison); $index++) {

    /**
     *
     * Example Formation of this Query
     *
     * select distinct AttributeList.AlistValue
     * from Attributes, AttributeList
     * where attributes.AListCode= attributelist.alistcode
     * and Attributes.AListCode =  'Language'
     *
     * select distinct AttributeList.AlistValue
     * from Attributes, AttributeList
     * where attributes.AListCode= attributelist.alistcode
     * and Attributes.AListCode =  'Author'
     *
     * This query will loop through each item in the    $aListCode[]
     *
     * */
    $dropdownResultQuery = "select distinct AttributeList.AlistValue
							from Attributes, AttributeList
							where attributes.AListCode= attributelist.alistcode
							and Attributes.AListCode = '$aListCode[$index]';";
    $dropdownResult = $db->setQuery($dropdownResultQuery);


    /**
     * Demo Result for above Query
     *
     *
     * # AlistValue
     * Common
     * English
     * Hindi
     * Italian
     * Tamil
     *
     * */
    if ($dropdownResult->num_rows > 0) {
        while ($row = $dropdownResult->fetch_assoc()) {
            $dropdownResultArray[$index][] = $row['AlistValue'];  //Each value of particular droup down is saved un this array

        }
    }

}


//These are for AddArtefact page
if (isset($_GET['page'])) {
    $search = false;
    $isSearchable = '';
    $checkbox = false;

}

$attributeValue = array();
$dataAvailable = true;
if (isset($_GET['artefactTitle']) || isset($_GET['artefactCode'])) {
    echo '<h3 align=center> Artefact:' . $_GET['artefactTitle'] . '</h3>';
    $artefactAttributesQuery = "select * from $_SESSION[type]" . "Attributes where artefactCode='$_GET[artefactCode]';";
    //echo $artefactAttributesQuery;
    $result = $db->setQuery($artefactAttributesQuery);
    if ($result == '0')
        echo 'Error in Query';
    else {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                for ($index = 0; $index < sizeof($columnArray); $index++) {
                    if (isset($row[$columnArray[$index]]))
                        $attributeValue[$index] = $row[$columnArray[$index]];
                    //echo $row[$columnArray[$index]];
                }

            }

        } else
            $dataAvailable = false;
    }

}

//print_r($attributeValue);
// echo '<a class="btn btn-primary" role="button" data-toggle="collapse" href="#divcollpase" aria-expanded="false" aria-controls="collapseExample">
// 		  Link with href
// 		</a>';
echo '<div class="" id="divcollpase">';
echo "<form id='columnValuesForm'>";
echo "<div id='attributesTable' class='col-md-12' style='height:auto;max-height:280px; overflow-y:scroll;'>";

$index = 0;
for ($index1 = 0; $index1 < sizeof($columnArray); $index1++) {
    $selectionFlag = false;
    echo "<div class='col-md-3 text-left'>";
    echo "<div class='boxes'>";

    if ($checkbox) { //checkbox is required or not, checking for Add Artefact page
        echo "<td> <input type='checkbox' name='AttributeCK' value='$columnArray[$index1]' class='pull-right checkall'/> </td>";
    }
    echo '<td>' . $columnArray[$index1] . '</td>';
    for ($index2 = 0; $index2 < sizeof($AttributeComaparison); $index2++) {

        if ($columnArray[$index1] == $aListCode[$index2]) {
            //echo $columnArray[$index1]. '=='.$aListCode[$index2].'</br>';
            $selectionFlag = true;
            echo '<td>';
            echo "<div class='col-md-12' style='padding: 0px;'><select id='.$aListCode[$index2].' class='selectpicker form-control' >";
            echo '<option value=' . '' . '> Select One </option>';

            if (isset($GET['artefactTitle']) || isset($_GET['artefactCode'])) { //&& $dropdownResultArray[$index][$index3]==$attributeValue[$index1])
                for ($index3 = 0; $index3 < (sizeof($dropdownResultArray[$index])); $index3++) {
                    $selected = '';
                    //echo "<option> $attributeValue[$index1]</option>";
                    if ($dropdownResultArray[$index][$index3] == $attributeValue[$index1]) {
                        $selected = 'Selected';
                        //echo "<option> $attributeValue[$index1]</option>";
                    }
                    //echo .'->'.$selected.'</br>';
                    echo '<option value=' . $dropdownResultArray[$index][$index3] . ' ' . $selected . '>' . $dropdownResultArray[$index][$index3] . '</option>';

                }

                //echo "<option> $index1 </option>";
            } else {
                for ($index3 = 0; $index3 < (sizeof($dropdownResultArray[$index])); $index3++) {
                    echo '<option value=' . $dropdownResultArray[$index][$index3] . '>' . $dropdownResultArray[$index][$index3] . '</option>';
                }
            }
            $index++;
            echo "</select> </div></td>";
        }

    }

    //$value is for AddArtefact Page. in Dropdown selected	Database value
    $value = '';
    if (!$selectionFlag && isset($_GET['artefactTitle']) && $dataAvailable == true)
        if (isset($attributeValue[$index1]))
            $value = '' . $attributeValue[$index1];


    if (!$selectionFlag)
        echo "<td> <input type='$inputType[$index1]' class='form-control' id='$columnArray[$index1]' value='$value'> </td>";

    echo "</div></div>";
}

echo "</div>";
echo "</form></div>";
//storing a columnArray in session to make use of it in SearchResult Page
$_SESSION['ColumnArray'] = $columnArray;

//$db->closeConnection();

?>