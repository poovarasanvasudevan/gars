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
$isSearchable = "";


//Query to List all the Attributes of the Selected Category. Selected Category is in Session
$dropdownArrayListCodeQuery = "Select distinct AttributeCode,DataType,Attributes
							from Attributes, ArtefactType
							where Attributes.ArtefactTypeCode = ArtefactType.ArtefactTypeCode 
							and
							$isSearchable
							ArtefactType.ArtefactTypeCode = '$_SESSION[type]';";


$dropdownArrayListCode = $db->setQuery($dropdownArrayListCodeQuery);

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
$selectSelectionQuery = "Select AttributeCode,AListCode
							from Attributes, ArtefactType
							where Attributes.ArtefactTypeCode = ArtefactType.ArtefactTypeCode
							and 
							PickFlag='y'
							and
							ArtefactType.ArtefactTypeCode = '$_SESSION[type]';";
$selectSelection = $db->setQuery($selectSelectionQuery);
if ($selectSelection->num_rows > 0) {
    while ($row = $selectSelection->fetch_assoc()) {
        $AttributeComaparison[] = $row['AttributeCode'];
        $aListCode[] = $row['AListCode'];
    }
}


//Query to get a dropdown values like authors list, type of Bind.
for ($index = 0; $index < sizeof($AttributeComaparison); $index++) {
    $dropdownResultQuery = "select distinct AttributeList.AlistValue
							from Attributes, AttributeList
							where attributes.AListCode= attributelist.alistcode
							and Attributes.AListCode = '$aListCode[$index]';";
    $dropdownResult = $db->setQuery($dropdownResultQuery);
    if ($dropdownResult->num_rows > 0) {
        while ($row = $dropdownResult->fetch_assoc()) {
            $dropdownResultArray[$index][] = $row['AlistValue'];

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

echo "<form id='columnValuesForm'>";
echo "<div id='attributesTable' class='col-md-12'>";
echo "<div class='col-md-12 maintenance'>";
$index = 0;
for ($index1 = 0; $index1 < sizeof($columnArray); $index1++) {
    $selectionFlag = false;
    echo "<div class='text-left col-md-12' style='padding:10px;'>";

    if ($checkbox) { //checkbox is required or not, checking for Add Artefact page
        echo "<td> <input type='checkbox' name='AttributeCK' value='$columnArray[$index1]' class='pull-right' /> </td>";
    }
    echo "<div class='col-md-6'>" . $columnArray[$index1] . "</div>";
    for ($index2 = 0; $index2 < sizeof($AttributeComaparison); $index2++) {

        if ($columnArray[$index1] == $aListCode[$index2]) {
            //echo $columnArray[$index1]. '=='.$aListCode[$index2].'</br>';
            $selectionFlag = true;
            echo "<div class='col-md-6'>";
            echo "<select name='" . $db->getAttributeCode($_SESSION['type'], $aListCode[$index2]) . "' id='" . $db->getAttributeCode($_SESSION['type'], $aListCode[$index2]) . "' class='form-control'>";
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
            echo "</select> </div>";
        }

    }

    //$value is for AddArtefact Page. in Dropdown selected	Database value
    $value = '';
    if (!$selectionFlag && isset($_GET['artefactTitle']) && $dataAvailable == true)
        if (isset($attributeValue[$index1]))
            $value = '' . $attributeValue[$index1];


    if (!$selectionFlag)
        echo "<div class='col-md-6'> <input type='$inputType[$index1]' class='form-control' id='" . $db->getAttributeCode($_SESSION['type'], $columnArray[$index1]) . "'  name='" . $db->getAttributeCode($_SESSION['type'], $columnArray[$index1]) . "'  value='$value'> </div>";
    echo "</div>";

}
echo "</div>";
echo "</div>";
echo "</form>";
//storing a columnArray in session to make use of it in SearchResult Page
$_SESSION['ColumnArray'] = $columnArray;

//$db->closeConnection();

?>