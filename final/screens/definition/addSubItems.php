<?php
include_once '../common/DatabaseConnection.php';
session_start();
$itemName = $_GET['ItemName'];
$level = $_GET['level'];
$parentNode = $_GET['parentNode'];
$columnLength = sizeof($_SESSION['ColumnArray']);
//create Connection
$db = new DatabaseConnection();
$conn = $db->createConnection();


//Get the max artefactPK
$artefactPK = 0;
$artefactPKfromNameQuery = 'Select artefactCode,(artefactPK*1) as artefactPK from artefact;';
$result = $db->setQuery($artefactPKfromNameQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $artefactPK = $row['artefactPK'];
    }
}
//$artefactPK++;

//Getting ArtefactCode of the parent
$artefactCode = 0;
$artefactCodefromNameQuery = 'Select artefactCode from artefact where artefactName=\'' . $parentNode . '\';';
$result = $db->setQuery($artefactCodefromNameQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $artefactCode = $row['artefactCode'];
    }
}

$artefactPK = $db->getMax();
$artefactCode = $db->getMaxArtefactCode();
$artefactValueQuery = "insert into artefact (ArtefactPK,ArtefactCode,ArtefactTypeCode,ArtefactPID,LevelNumber,ArtefactName)
						values($artefactPK,'$artefactCode','$_SESSION[type]','$parentNode',$level,'$itemName');";
//$_SESSION['artefactPK']+=1;
//echo $artefactValueQuery;
$result = $db->setQuery($artefactValueQuery);
//$result1=$db->setQuery("call insertAttribute('$artefactPK','$_SESSION[type]')");
if ($result)
    echo 'Child Added';
else
    echo $artefactValueQuery;



?>