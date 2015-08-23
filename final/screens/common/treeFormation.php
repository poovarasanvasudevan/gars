<?php

include_once 'DatabaseConnection.php';
$type = $_GET['type'];
session_start();
$_SESSION['type'] = $type;
$db = new DatabaseConnection();
$conn = $db->createConnection();
$parentArray = array();//Associate Array with ArtefactCode and ArtefactPID

$single = array('Photos');
$NoChild = array();
//Query to Get Parent elements
$singleflag = 0;
for ($i = 0; $i < sizeof($single); $i++) {
    if ($type == $single[$i]) {
        $singleflag = 1;
        break;
    }
}

if ($singleflag) {
    $parentQuery = "select artefactname,artefactcode from artefact
	where artefactTypeCode='$type' and visiblestatus='on' order by CreatedDate DESC";


} else {
    $parentQuery = "select artefactname,artefactcode from artefact
				where artefactPID is null and artefactTypeCode='$type' and visiblestatus='on' order by CreatedDate DESC";

    //echo $parentQuery;
}

$result = $db->setQuery($parentQuery);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $parentArray[$row['artefactcode']] = $row['artefactname'];
        //echo '>>'.$row['artefactcode'].'>>'.$row['artefactname'].'</br>';
    }
} else {
    echo "Sorry No Data for this Artefact...";
}

$childrenArray = array();//Associate Array with ParentArtefactCode and array(Child: ArtefactCode and ArtefactName)

//Query to Get Children
$childChild = array();//to store parent->child[This Name... Code is in associate array]->child
foreach ($parentArray as $parentCode => $parentValue) {

    $childrenQuery = "select ArtefactCode,ArtefactName from Artefact where ArtefactPID=";
    $childrenQuery .= '\'' . $parentArray[$parentCode] . '\'  and visiblestatus="on" order by SequenceNumber;';
    //echo $childrenQuery.'</br>';
    $result = $db->setQuery($childrenQuery);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            //Query to check if any child has a child
            $childrenCheckQuery = "select artefactCode,artefactName from artefact where artefactPID='$row[ArtefactName]' and visiblestatus='on' order by CreatedDate DESC;";
            //echo $childrenCheckQuery.'</br>';
            $result1 = $db->setQuery($childrenCheckQuery);
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    $childrenArray[$parentCode][$row['ArtefactCode']][$row1['artefactCode']] = $row1['artefactName'];
                    $childChild[] = $row['ArtefactName'];
                }

            } else
                $childrenArray[$parentCode][$row['ArtefactCode']] = $row['ArtefactName'];
        }
    } else
        $NoChild[$parentCode] = $parentArray[$parentCode];
}


//Generating a Tree
$count = 0;
echo '<ul>';
foreach ($NoChild as $pCode => $pvalue)
    echo "<li id='$pCode'> $pvalue";

foreach ($childrenArray as $parentCode => $childArray) {
    echo "<li id='$parentCode'> $parentArray[$parentCode] ";
    echo '<ul>';
    foreach ($childArray as $childCode => $childValue) { //if a child have a another child then use is_array($childCode)

        if (is_array($childValue)) {
            if (isset($childChild[$count])) {
                echo '<li id=' . $childCode . '>' . $childChild[$count++];
                echo '<ul>';
                foreach ($childValue as $childCode1 => $childValue1)
                    echo '<li id=' . $childCode1 . '>' . $childValue1;
                echo '</ul>';
            } else
                echo '<li id=' . $childCode . '>' . $childValue;
        } else
            echo '<li id=' . $childCode . '>' . $childValue;


    }
    echo '</ul>';

}

//print which has no children


echo "</ul>";

?> 