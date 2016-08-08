<?php
namespace Pages;

class Page {
	private $PageID;
	private $PageName;
	private $PageEditName;
	private $PageHelp;
	//not including menu system items here due to unsure of requirement for implementation of DynamiForum
	function __construct() {
		//empty constructor that may need to be altered later
	}
	
	function setPageID($PageID)
	{
		$this->PageID = $PageID;
	}
	
	function getPageID()
	{
		return $this->PageID;
	}
	
	function setPageName($PageName)
	{
		$this->PageName = $PageName;
	}
	
	function getPageName()
	{
		return $this->PageName;
	}
	
	function setPageEditName($PageEditName)
	{
		$this->PageEditName = $PageEditName;
	}
	
	function getPageEditName()
	{
		return $this->PageEditName;
	}
	
	function setPageHelp($PageHelp)
	{
		$this->PageHelp = $PageHelp;
	}
	
	function getPageHelp()
	{
		return $this->PageHelp;
	}
	
	function UpdateHelp($Help)
	{
		$this->PageHelp = $Help;
		
		updHelp($this->PageID,$this->PageHelp);
		
	}
	
	// function for loading pages
	public static function LoadPage($PageID)
	{
		$thisPage = new Page();
		
		$pdoPage = getPage($PageID);
		
		if( $pdoPage->rowCount() == 0 )
		{
			return NULL;
		} else {
		
		$Page = $pdoPage->fetch();
		
		$thisPage->setPageID($Page['id_page']);
		$thisPage->setPageName($Page['fld_page_name']);
		$thisPage->setPageEditName($Page['fld_page_edit_name']);
		$thisPage->setPageHelp($Page['fld_page_help']);
		
		return $thisPage;
		}
		
	}
	
	
}

class NavPage extends Page {
	
	private $MenuCategoryName;
	private $SubMenuCategoryName;
	
	function setMenuCategoryName($MenuCategoryName)
	{
		$this->MenuCategoryName = $MenuCategoryName;
	}
	
	function getMenuCategoryName()
	{
		return $this->MenuCategoryName;
	}
	
	function setSubMenuCategoryName($SubMenuCategoryName)
	{
		$this->SubMenuCategoryName = $SubMenuCategoryName;
	}
	
	function getSubMenuCategoryName()
	{
		return $this->SubMenuCategoryName;
	}
	
	function __construct() {
		//empty constructor that may need to be altered later
		parent::__construct(); 
	}
	
	 
	
}

function updHelp($PageID,$PageHelp)
{
	global $dbh;
	
	//referring to html here to clarify html insertion
	try {
			$Page = $dbh->prepare('	
									UPDATE tbl_pages
									SET fld_page_help = :html
									WHERE id_page = :PageID');
			$Page->bindValue(':html', trim($PageHelp), \PDO::PARAM_STR);
			$Page->bindValue(':PageID', $PageID, \PDO::PARAM_INT);
			$Page->execute();
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
}

function getPage($PageID)
{
	global $dbh;
	try {
			$Page = $dbh->prepare('SELECT tbl_pages.* FROM tbl_pages WHERE id_page = :PageID');
			$Page->execute(array(':PageID' => $PageID ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $Page;
}

?>