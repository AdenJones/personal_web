<?php
	namespace Auditing;
	
	class ChangeLog {
		private $ChangeLogID;
		private $ChangedBy;
		private $PageID;
		private $PrimaryKey;
		private $Dated;
		private $ChangeType;
		private $TableName;
		
		public function GetChangeLogID()
		{
			return $this->ChangeLogID;
		}
		
		public function SetChangeLogID($ChangeLogID)
		{
			$this->ChangeLogID = $ChangeLogID;
		}
		
		public function GetChangedBy()
		{
			return $this->ChangedBy;
		}
		
		public function SetChangedBy($ChangedBy)
		{
			$this->ChangedBy = $ChangedBy;
		}
		
		public function GetPageID()
		{
			return $this->PageID;
		}
		
		public function SetPageID($PageID)
		{
			$this->PageID = $PageID;
		}
		
		public function GetPrimaryKey()
		{
			return $this->PrimaryKey;
		}
		
		public function SetPrimaryKey($PrimaryKey)
		{
			$this->PrimaryKey = $PrimaryKey;
		}
		
		public function GetDated()
		{
			return $this->Dated;
		}
		
		public function SetDated($Dated)
		{
			$this->Dated = $Dated;
		}
		
		public function GetChangeType()
		{
			return $this->ChangeType;
		}
		
		public function SetChangeType($ChangeType)
		{
			$this->ChangeType = $ChangeType;
		}
		
		public function GetTableName()
		{
			return $this->TableName;
		}
		
		public function SetTableName($TableName)
		{
			$this->TableName = $TableName;
		}
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public static function LoadLogByDates($StartDate,$EndDate)
		{
			$ChangeLog = array();
			
			$arrChangeLog = getChangeLogByDates($StartDate,$EndDate)->fetchAll();
			
			foreach( $arrChangeLog as $Log )
			{
				$ChangeLog[] = ChangeLog::ArrToChangeLog($Log);
			}
			
			return $ChangeLog;
		}
		
		public static function CreateChangeLog($ChangedBy,$PageID,$PrimaryKey,$ChangeType,$TableName)
		{
			$ChangeLogID = addChangeLog($ChangedBy,$PageID,$PrimaryKey,$ChangeType,$TableName);
						
			return ChangeLog::LoadChangeLog($ChangeLogID);
		}
		
		public static function LoadChangeLog($ChangeLogID)
		{
			$ChangeLogID = intval($ChangeLogID);
			
			$pdoChangeLog = getChangeLog($ChangeLogID);
			
			if($pdoChangeLog->rowCount() != 1 )
			{
				return NULL;
			}
			
			return ChangeLog::ArrToChangeLog($pdoChangeLog->fetch());
		}
		
		public static function ArrToChangeLog($Item)
		{
			$thisChangeLog = new ChangeLog();
			
			$thisChangeLog->SetChangeLogID($Item['id_change_log']);
			$thisChangeLog->SetChangedBy($Item['fld_changed_by']);
			$thisChangeLog->SetPageID($Item['fld_page_id']);
			$thisChangeLog->SetPrimaryKey($Item['fld_primary_key']);
			$thisChangeLog->SetDated($Item['fld_dated']);
			$thisChangeLog->SetChangeType($Item['fld_change_type']);
			$thisChangeLog->SetTableName($Item['fld_table_name']);
			
			
			return $thisChangeLog;
		}
		
	} // END CHANGE LOG
	
	
	function getChangeLog($ChangeLogID)
	{
		global $dbh;
		
		try {
			
			$ChangeLog = $dbh->prepare('	
									SELECT *
									FROM tbl_change_log
									WHERE id_change_log = :ChangeLogID
									');
			$ChangeLog->execute(array(':ChangeLogID' => $ChangeLogID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $ChangeLog;
	}
	
	
	function addChangeLog($ChangedBy,$PageID,$PrimaryKey,$ChangeType,$TableName)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_change_log(fld_changed_by,fld_page_id,fld_primary_key,fld_change_type,fld_table_name) 
									  VALUES (:ChangedBy,:PageID,:PrimaryKey,:ChangeType,:TableName) ');
			$qryInsert->execute(array(	
										':ChangedBy' => $ChangedBy,
										':PageID' => $PageID,
										':PrimaryKey' => $PrimaryKey,
										':ChangeType' => $ChangeType,
										':TableName' => $TableName
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getChangeLogByDates($StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$ChangeLog = $dbh->prepare('	
									SELECT *
									FROM tbl_change_log
									WHERE fld_dated BETWEEN :StartDate AND :EndDate
									ORDER BY fld_dated DESC
									');
			$ChangeLog->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
										':EndDate' => ($EndDate == 'null') ? null : $EndDate
										 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $ChangeLog;
	}
	
?>
