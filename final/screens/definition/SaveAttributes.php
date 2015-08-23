<?php
//error_reporting(0);

include_once '../common/DatabaseConnection.php';
session_start();
$dataList = explode(',', $_GET['dataArray']);
$dataLength = sizeof($dataList);
$artefactName = $_GET['artefactCode'];
//print_r($dataList);
$db = new DatabaseConnection();
$conn = $db->createConnection();
$artefactCode = $_GET['artefactCode'];

$nameList = explode(',', $_GET['elem']);
$type = $_GET['type'];

//Query to Check wheather to update a old record or new Record by checking the datas
$attributeValuePK = array();

$user = $_SESSION['userPK'];
//argument
/**
 * http://localhost:81/final/SaveAttributes.php?
 *
 * dataArray=North%20American%20gathering,NULL,10,1986,Italian,120,NULL,People%20may%20say,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL&
 * artefactCode=8783&
 * artefactName=NVHN-1986-007-1-1&
 * type=VHS
 *
 * */
$artefactDatasQuery = "Select * from attributevalue where artefactCode='$artefactCode' and artefactTypeCode='$type';";
$resultData = $db->setQuery($artefactDatasQuery);
//echo $artefactDatasQuery;

//$resultData->num_rows==0. if there is no data so insert else update
if ($resultData->num_rows > 0) {

    //Query to fetch a attributeValuePK to update data
    $attributeValuePKQuery = "Select attributeValuePK,AttributeCode from attributevalue where artefactCode='$artefactCode';";

    //echo $attributeValuePKQuery;

    $attributeCode = array();
    $resultSelect = $db->setQuery($attributeValuePKQuery);
    if ($resultSelect->num_rows > 0) {
        while ($rowData = $resultSelect->fetch_assoc()) {
            $attributeValuePK[] = $rowData['attributeValuePK'];
            $attributeCode[] = $rowData['AttributeCode'];
        }
    }

    //Query to update data

    //print_r($attributeValuePK);
    $indexCount = 0;
    for ($index = 0; $index < sizeof($attributeValuePK); $index++) {
        if (isset($dataList[$index]) && isset($attributeValuePK[$index])) {
            $updateQuery = "update attributeValue set attributeValue='$dataList[$index]' where AttributeValuePK='$attributeValuePK[$index]';";
            //echo $updateQuery;
            $updateResult = $db->setQuery($updateQuery);

            $sql = "call updateData('$type','$artefactCode','$attributeCode[$index]','$dataList[$index]')";
            $sql1 = "call UpdateUserAndTime('$attributeValuePK[$index]','$user')";
            //echo $sql;
            $db->setQuery($sql);
            $db->setQuery($sql1);
        }

// 		$acode = $db->getArtefactCode($attributeValuePK[$index]);
// 		$atitle = $db->getTitle($attributeValuePK[$index]);
// 		$result2 = $db->setQuery("call updateAttributeValues('$acode','$type','$atitle','$dataList[$index]')");
        if ($updateResult)
            $indexCount++;
    }
    //echo $index.' '.$indexCount.'</br>';
    if ($index == $indexCount)
        echo "<div class='alert alert-success btn-success' role='alert'>Updated Succesfully</div>";
    else
        echo "<div class='alert alert-danger' role='alert'>Failed to update</div>";

} else {

    //Query to get PID of the artefact
    $artefactParentID = '';
    $artefactPIDQuery = "select ArtefactPID from artefact where artefactCode='$artefactCode';";
    //echo $artefactPIDQuery;
    $result = $db->setQuery($artefactPIDQuery);
    //echo $result;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $artefactParentID = $row['ArtefactPID'];
        }
    } else
        $artefactParentID = '';

    //Query to Insert Data
    if (!isset($_SESSION['attributeValuePK']))
        $_SESSION['attributeValuePK'] = $db->getMaxAttributeValue();
    $count = 0;
    for ($index = 0; $index < $dataLength; $index++) {
        //$attCode=('A').(1001+$index);
        $attCode = $nameList[$index];
        $insertQuery = "insert into attributevalue (attributeValuePK,ArtefactTypeCode,ArtefactCode,AttributeCode,ArtefactParentID,AttributeValue,CreatedBy,CreatedDate)
							values($_SESSION[attributeValuePK],'$type','$artefactCode','$attCode','$artefactParentID','$dataList[$index]','$user',CURRENT_TIMESTAMP);";

        $sql2 = "call UpdateUserAndTime('$_SESSION[attributeValuePK]','$user')";
        $_SESSION['attributeValuePK'] += 1;
        //echo $insertQuery;
        $result = $db->setQuery($insertQuery);

        //$result2 = $db->setQuery("call updateAttributeValues('$artefactCode','$type','$db->getTitle($_SESSION[attributeValuePK])','$dataList[$index]')");
        $sql1 = "call updateData('$type','$artefactCode','$attCode','$dataList[$index]')";

        //echo $sql;
        $db->setQuery($sql1);
        $db->setQuery($sql2);


        if ($result)
            $count++;
    }

    unset($_SESSION['attributeValuePK']);
    //echo $count."<br/>";
    //echo $dataLength;
    if ($count == $dataLength)
        echo 'Attributes Inserted SuccessFully';
    else
        echo 'Problem Occured while inserting';

}

//Calling Procedure based on the Category to create a new Table
//$temptable="call AttributesList('$type');";
//$procedure=$db->setQuery($temptable);
//echo $temptable;
?>