<?php
//session_start();
// Database Connection Class
include "Config.php";

class DatabaseConnection {
	var $connection;
	var $db;
	
	function __construct(){
		$this->connection='';
		$this->db='';
	}
	
	/**
	 * It establishes connection to the database
	 * 
	 * 	It Take ipaddress,username,password and database name
	 * @author Poovarasan Vasudevan
	 * */
	function createConnection() {
		//Databae connection string - to be changed at the time of deployment
		//IP address, DBname=artefactcatalog
		//$this->connection=new mysqli('172.16.13.157','root','','artefactcatalog');
		$this->connection=new mysqli(DB_HOST,DB_USER,'',DB_NAME);
		if(!$this->connection->connect_error){
			//mysqli_query($this->connection,"CALL artefactcatalog.AttributesList('BOK')");
			//mysqli_query($this->connection,"CALL artefactcatalog.AttributesList('VHS')");
			return $this->connection;
		}
		else
			echo "Connection Failed";
	}
	
	function autoCommitFalse() {
		$this->connection->autocommit(FALSE);
	}
		
	function commit() {
		$this->connection->commit();
	}
	function setQuery($query){
		if($result=mysqli_query($this->connection,$query)) {
			
			//executing procedure for creating Book and Video Tables
			//mysqli_query($this->connection,"CALL AttributeList('BOK')");
			//mysqli_query($this->connection,"CALL AttributeList('VHS')");
			return $result;
		}
		else
			return 0;
	}
	
	function closeConnection(){
		mysqli_close($this->connection);
	}
	
	function __destruct(){
		$connection=NULL;
		$db=NULL;
	}
	
	function getMax() {
		$max = 0;
		if($result=mysqli_query($this->connection,'Select max(ArtefactPK) as maximum from artefact')) {
			while($r = $result->fetch_assoc()) {
				$max = $r['maximum'];
			}
		}
		$max = $max+1;
		return $max;
	}
	
	function getMaxAttributeValue() {
		$max = 0;
		if($result=mysqli_query($this->connection,'Select max(attributeValuePK) as maximum from attributevalue')) {
			while($r = $result->fetch_assoc()) {
				$max = $r['maximum'];
			}
		}
		$max = $max+1;
		return $max;
	}
	
	function getMaxCICO() {
		$max = 0;
		if($result=mysqli_query($this->connection,'Select max(CICOSK) as maximum from artefactcico')) {
			while($r = $result->fetch_assoc()) {
				$max = $r['maximum'];
			}
		}
		$max = $max+1;
		return $max;
	}
	
	function getMaxArtefactCode() {
		$max = 0;
		if($result=mysqli_query($this->connection,'select max(artefactcode*1) as maximum from artefact;')) {
			while($r = $result->fetch_assoc()) {
				$max = $r['maximum'];
			}
		}
		$max = $max+1;
		return $max;
	}
	
	
	
	/**
	 * These function attributes and table has to change after database migration
	 * 
	 * */
	function getArtefactCount($loc,$artefactType) {
		$max = 0;
		$sql = "SELECT count(*) as maximum FROM ".$artefactType."attributes where visiblestatus='on'";
		if($result=mysqli_query($this->connection,$sql)) {
			while($r = $result->fetch_assoc()) {
				$max = $r['maximum'];
			}
		}
		return $max;
	}
	
	
	
	function getFilePath($artefactCode,$artefactType) {
		$sql = "select `FilePath` as FilePath from ".$artefactType."Attributes where artefactCode = $artefactCode";
		$filePath ='No';
		if($result=mysqli_query($this->connection,$sql)) {
			while ($res = $result->fetch_assoc()) {
				$filePath =$res['FilePath'];
			}
		}
		
		return $filePath;
	}
	
	/**
	 * End of changes after database migration
	 *
	 * */
	
	function getPages($user) {	
		$sql ="		select p.menutitle,p.url,p.dir from page p
					inner join role_page_mapping rp
					on p.pagepk=rp.pagefk
					inner join role r
					on rp.rolefk=r.rolepk
					inner join user u
					on u.rolefk=r.rolepk					
					where u.UserPk='$user' union select menutitle,url,dir from page
					where iscommon='y'";
		
		$pages=array();
		
		if($result=mysqli_query($this->connection,$sql)) {
			if($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$pages[] = $row;
				}
			}
		}
		return $pages;		
	}
	
	function getArtefactCode($attributepk) {
		$code;
		$sql ="select ArtefactCode from attributevalue where AttributeValuePK='$attributepk'";
		if($result=mysqli_query($this->connection,$sql)) {
			while ($res = $result->fetch_assoc()) {
				$code =$res['ArtefactCode'];
			}
		}
		return $code;
	}
	
	function getTitle($code) {
		$code;
		$sql ="select attributes from attributes where AttributeCode=(select AttributeCode from attributevalue where AttributeValuePK='$code')";
		if($result=mysqli_query($this->connection,$sql)) {
			while ($res = $result->fetch_assoc()) {
				$code =$res['attributes'];
			}
		}
		return $code;
		
	}
	
	
	function getAttributeCode($artefactTitle,$attributeTitle) {
		$code;
		$sql="SELECT AttributeCode FROM attributes where Attributes='$attributeTitle' and ArtefactTypeCode='$artefactTitle'";
		if($result=mysqli_query($this->connection,$sql)) {
			while ($res = $result->fetch_assoc()) {
				$code =$res['AttributeCode'];
			}
		}
		return $code;
	}
	
	
	function getArtefactNamePK($artefactCode) {
		$code;
		$sql="select ArtefactName from artefact where ArtefactCode='$artefactCode'";
		if($result=mysqli_query($this->connection,$sql)) {
			while ($res = $result->fetch_assoc()) {
				$code =$res['ArtefactName'];
			}
		}
		return $code;
	}
}

?>