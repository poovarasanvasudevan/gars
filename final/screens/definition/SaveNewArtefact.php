<?php
include_once '../common/DatabaseConnection.php';
session_start();
$artefactName = $_GET['artefactName'];
$level = $_GET['level'];
$db = new DatabaseConnection();
$conn = $db->createConnection();

//Query to get Max of artefactPK...
$artefactPK = 0;
$artefactCode = 0;
$maxartefactQuery = "Select max(artefactPK) as MaxPK,max(artefactCode*1) as MaxCode from artefact;";
$result = $db->setQuery($maxartefactQuery);
if ($result->num_rows > 0) {
    while ($rows = $result->fetch_assoc()) {
        $artefactPK = $rows['MaxPK'];
        $artefactCode = $rows['MaxCode'];
    }
}

$artefactCode++;
$artefactPK++;


$artefactNameAddQuery = "insert into artefact (artefactPK,artefactCode,artefactName,artefactTypeCode,LevelNumber,CreatedDate,CreatedBy) values ($artefactPK,$artefactCode,'$artefactName','$_SESSION[type]',0,CURRENT_TIMESTAMP,'$_SESSION[userPK]');";
//echo $artefactNameAddQuery;
$db = new DatabaseConnection();
$conn = $db->createConnection();
$result = $db->setQuery($artefactNameAddQuery);

//$result1=$db->setQuery("call insertAttribute('$artefactCode','$_SESSION[type]')");
if ($result)
    echo "Artefact Added. Now just click your Artefact from Tree and add their attributes";
else
    echo "Error in Query";
?>