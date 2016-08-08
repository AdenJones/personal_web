<?php
	namespace Business;
	
	class Group {
		
		private $GroupID;
		private $GroupName;
		private $GroupTypeID;
		private $StartDate;
		private $EndDate;
		private $NonGroupType;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetGroupName()
		{
			return $this->GroupName;
		}
		
		public function SetGroupName($GroupName)
		{
			$this->GroupName = $GroupName;
		}
				
		public function GetGroupTypeID()
		{
			return $this->GroupTypeID;
		}
		
		public function SetGroupTypeID($GroupTypeID)
		{
			$this->GroupTypeID = $GroupTypeID;
		}
		
		public function GetGroupType()
		{
			return GroupType::LoadGroupType($this->GroupTypeID);
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function GetNonGroupType()
		{
			return $this->NonGroupType;
		}
		
		public function SetNonGroupType($NonGroupType)
		{
			$this->NonGroupType = $NonGroupType;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public function Delete()
		{
			if($_SESSION['User']->GetUserTypeName() == ADMINISTRATOR or $_SESSION['User']->GetUserTypeName() == STAFF_ADMINISTRATOR)
			{
				return delGroup($this->GroupID);
				
				
			} else {
				return false;
			}
			
		}
		
		public function CountNewCommittedGrowersByGroupDates($StartDate,$EndDate)
		{
			
			return Attendance::CountNewCommittedGrowersByGroupDates($this->GroupID,$StartDate,$EndDate);
			
		}
		
		public function CountFirstTimerMultipleAttendancesByGroupDates($StartDate,$EndDate,$NoAttendances)
		{
			
			return Attendance::FirstTimerMultipleAttendancesByGroupDates($this->GroupID,$StartDate,$EndDate,$NoAttendances);
			
		}
		
		public function LoadVolunteerAttendeesBetweenDates($VolRole,$StartDate,$EndDate)
		{
			return \Membership\Staff::LoadVolunteerByGroupAttendance($VolRole,$this->GroupID,$StartDate,$EndDate);
		}
		
		public function CountVolunteerAttendancesBetweenDates($VolRole,$StartDate,$EndDate)
		{
			return \Business\Attendance::CountVolunteerAttendanceByGroup($VolRole,$this->GroupID,$StartDate,$EndDate);
		}
		
		public function CountMemberAttendancesBetweenDates($StartDate,$EndDate)
		{
			return \Business\Attendance::CountMembersAttendanceByGroup($this->GroupID,$StartDate,$EndDate);
		}
		
		public function LoadMemberAttendeesBetweenDates($StartDate,$EndDate)
		{
			return \Membership\Member::LoadMembersByGroupAttendance($this->GroupID,$StartDate,$EndDate);
		}
		
		public function LoadNotesByDateAndSecurityLevel($UserID,$SecurityLevel,$Date)
		{
			return Note::LoadGroupNotesByDate($UserID,$SecurityLevel,$this->GroupID,$Date);
		}
			
		public static function ArrToGroup($Item)
		{
			$thisGroup = new Group();
			
			$thisGroup->SetGroupID($Item['id_group']);
			$thisGroup->SetGroupName($Item['fld_group_name']);
			$thisGroup->SetGroupTypeID($Item['fld_group_type']);
			$thisGroup->SetStartDate($Item['fld_start_date']);
			$thisGroup->SetEndDate($Item['fld_end_date']);
			$thisGroup->SetNonGroupType($Item['fld_non_group_type']);
			$thisGroup->SetDeleted($Item['fld_deleted']);
			
			return $thisGroup;
		}
		
		public static function LoadGroupNotesByUserID($UserID,$show_deleted = false)
		{
			return \Business\Note::LoadGroupNotesByUserID($UserID,$show_deleted);
		}
		
		
		public function TotalAttendeesInPeriod($StartDate,$EndDate)
		{
			//count non community observers
			$AllNonCommunityObservers = $this->CountAllNonCommunityObserverAttendeesInPeriod($StartDate,$EndDate);
			//count community observers
			$AllCommunityObservers = $this->CountCommunityObserversInPeriod($StartDate,$EndDate);
			
			return $AllNonCommunityObservers + $AllCommunityObservers ;
		}
		
		public function CountAllNonCommunityObserverAttendeesInPeriod($StartDate,$EndDate)
		{
			$arrCount = getCountAllNonCommunityObserverAttendeesInPeriodByGroup($this->GroupID,$StartDate,$EndDate)->fetch();
			
			return $arrCount['Total'];
		}
		
		public function TotalAttendancesInPeriod($StartDate,$EndDate)
		{
			$ComObs = $this->CountCommunityObserversInPeriod($StartDate,$EndDate);
			
			$arrGetCount = getCountAllGroupAttendancesInPeriod($this->GroupID,$StartDate,$EndDate)->fetch();
			
			return $arrGetCount['Total'] + $ComObs;
		}
		
		public static function LoadGroupsByBranchAndPeriod($BranchID,$StartDate,$EndDate,$show_deleted = false)
		{
			$arrGroups = getGroupsByBranchPeriod($BranchID,$StartDate,$EndDate,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsWithoutOrganisers($months_without,$show_deleted = false)
		{
			$arrGroups = getGroupsWithoutOrganisers($months_without,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsWithRecessNearEnd($months_till_end, $show_deleted = false)
		{
			$arrGroups = getGroupsWithRecessNearEnd($months_till_end,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public function CountNSinceLastAttended($NoMeetings,$StartDate,$EndDate)
		{
			$AllDates = $this->LoadGroupDates();
			//calculate the date range based upon NoMeetings
						
			$DesignatedStart = '';
			$DesignatedEnd = '';
			$DesignatedLastMeeting = '';
			
			if( $AllDates == NULL )
			{
				return 0;
			}
			
			$IndexOfStart = 0;
			
			foreach( $AllDates as $Date )
			{
				if( $Date <= $StartDate )
				{
					$DesignatedStart = $Date;
					break;
				}
				
				$IndexOfStart++;
			}
			
			if( $DesignatedStart == '' or ($IndexOfStart + $NoMeetings + 1) >= count($AllDates) )
			{
				return 0;
			} else {
				$DesignatedEnd = $AllDates[$IndexOfStart + $NoMeetings];
				$DesignatedLastMeeting = $AllDates[$IndexOfStart + $NoMeetings +1];
			}
			
			//return 'GroupID:'.$this->GroupID.';Start:'.$DesignatedStart.';End:'.$DesignatedEnd.';Last:'.$DesignatedLastMeeting;
			
			//below will be returned
			return count(Attendance::LoadDidntAttendButDidAttend($this->GroupID,$DesignatedStart,$DesignatedEnd,$DesignatedLastMeeting));
		}
		
		public function HasAttendanceOnDateNew($Date)
		{
			$Attendances = Attendance::GetGroupAttendanceByDate($this->GroupID,$Date)->fetchAll();
			
			return count($Attendances) > 0;
		}
		
		public function CountComGrowAttAtEnd($EndDate)
		{
			$AllDates = $this->LoadGroupDates();
			//calculate the date range based upon NoMeetings
					
			$CapturedEnd = '';
			
			if( $AllDates == NULL )
			{
				return 0;
			}
			
			foreach( $AllDates as $Date )
			{
				if($CapturedEnd == '' and $Date <= $EndDate and $this->HasAttendanceOnDateNew($Date))
				{
					$CapturedEnd = $Date;
				}
			}
			
			//UserReportField::Create($ReportID,'Start: '.$DesignatedStart.'; End: '.$CapturedEnd,0);
			
			if( $CapturedEnd == '' )
			{
				return 0;

			} else {
				
				
				//UserReportField::Create($ReportID,'Date:',$CapturedEnd);
				
				$ThisAttendance = Attendance::CountCommittedGrowersByDate($this->GroupID,$CapturedEnd);
						
				return $ThisAttendance['Total_Aees']; 
			}
			
		}
		
		//calculates last attendance date for lapsed growers
		public function CountNSinceLastAttendance($NoMeetings,$StartDate,$EndDate)
		{
			$AllDates = $this->LoadGroupDates();
			//calculate the date range based upon NoMeetings
					
			$DesignatedStart = '';
			$CapturedEnd = '';
			
			if( $AllDates == NULL )
			{
				return 0;
			}
			
			$Index = 0;
			
			$IndexOfStart = 0;
			$IndexOfEnd = 0;
			
			foreach( $AllDates as $Date )
			{
				if( $DesignatedStart == '' and $Date <= $StartDate )
				{
					$DesignatedStart = $Date;
					$IndexOfStart = $Index;
									
				}
				
				if($CapturedEnd == '' and $Date <= $EndDate)
				{
					$CapturedEnd = $Date;
					$IndexOfEnd = $Index;
				}
				
				$Index++;
				
			}
			
			//UserReportField::Create($ReportID,'Start: '.$DesignatedStart.'; End: '.$CapturedEnd,0);
			
			if( $DesignatedStart == '' or $CapturedEnd == '' )
			{
				return 0;

			} else {
				
				if(  $IndexOfEnd - $NoMeetings < 1 )
				{
					return 0;
				} else {
					
					$DesignatedEnd = $AllDates[$IndexOfEnd + $NoMeetings];
					
					//UserReportField::Create($ReportID,'Start: '.$DesignatedStart.'; End: '.$DesignatedEnd,' Idx ='.($IndexOfEnd + $NoMeetings),0);
					
					
					if($DesignatedEnd > $EndDate)
					{
						return 0;
					} else {
						
						$ThisAttendance = Attendance::CountCeasedAttendingGroupOn($this->GroupID,$DesignatedStart,$DesignatedEnd);
						
						return $ThisAttendance['Total_Aees'];
					}					
					
				}
				 
			}
			
		}
		
		public static function LoadGroupsByRegionPeriod($RegionID,$StartDate,$EndDate,$show_deleted = false)
		{
			$arrGroups = getGroupsByRegionPeriod($RegionID,$StartDate,$EndDate,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public function FieldWorkerAttendeesInPeriod($StartDate,$EndDate)
		{
					
			return Attendance::CountFieldWorkerAttendeesInPeriod($this->GroupID,$StartDate,$EndDate);
		}
		
		public function FieldWorkerAttendancesInPeriod($StartDate,$EndDate)
		{
			//return count(Attendance::LoadFieldWorkerAttendancesInPeriod($this->GroupID,$StartDate,$EndDate));
			
			return count(getFieldWorkerAttendancesInPeriod($this->GroupID,$StartDate,$EndDate)->fetchAll());
		}
		
		public function OrganiserAttendancesInPeriod($StartDate,$EndDate)
		{
			//global $Organiser;
			//count(Attendance::LoadStaffAttendancesInPeriod(ORGANISER,$this->GroupID,$StartDate,$EndDate));
			
			return $this->CountVolunteerAttendancesBetweenDates(ORGANISER,$StartDate,$EndDate);
			
		}
		
		public function RecorderAttendancesInPeriod($StartDate,$EndDate)
		{
			//global $Recorder;
			
			//count(Attendance::LoadStaffAttendancesInPeriod(RECORDER,$this->GroupID,$StartDate,$EndDate));
			
			return $this->CountVolunteerAttendancesBetweenDates(RECORDER,$StartDate,$EndDate);
		}
		
		public function NonCommittedGrowerAttendancesInPeriod($StartDate,$EndDate)
		{
			return count(Attendance::LoadNonCommittedAttendancesInPeriod($this->GroupID,$StartDate,$EndDate));
		}
		
		public function CommittedGrowerAttendancesInPeriod($StartDate,$EndDate) //includes organiser and recorder
		{
			//global $Organiser;
			//global $Recorder;
						
			$CountOrganiser = $this->CountVolunteerAttendancesBetweenDates(ORGANISER,$StartDate,$EndDate);
			$CountRecorder = $this->CountVolunteerAttendancesBetweenDates(RECORDER,$StartDate,$EndDate);
			
			$CountCommitted = \Membership\getCountCommittedMembersAttendancesByGroupBetweenDates($this->GroupID,$StartDate,$EndDate);
			
			return $CountOrganiser + $CountRecorder + $CountCommitted;
		}
		
		public function CountNewCommittedGrowersInPeriod($StartDate,$EndDate)
		{
			//global $Organiser;
			//global $Recorder;
			
			$CountNewCommitted = count(\Membership\Member::LoadNewCommittedMembersByGroupAttendanceBetweenDates($this->GroupID,$StartDate,$EndDate)); //boolean for committed
			
			return $CountNewCommitted;
		}
		
		public function CountCommittedGrowersInPeriod($StartDate,$EndDate) //also needs to count organiser and recorder
		{
			//global $Organiser;
			//global $Recorder;
						
			$CountOrganiser = count($this->LoadVolunteerAttendeesBetweenDates(ORGANISER,$StartDate,$EndDate));
			$CountRecorder = count($this->LoadVolunteerAttendeesBetweenDates(RECORDER,$StartDate,$EndDate));
			$CountCommitted = \Membership\getCountCommittedMembersByGroupBetweenDates($this->GroupID,$StartDate,$EndDate);
			
			return $CountOrganiser + $CountRecorder + $CountCommitted;
		}
		
		public function CountCommunityObserversInPeriod($StartDate,$EndDate) //Remember that community observers are not unique
		{
			return getCountCommunityObservers($this->GroupID,$StartDate,$EndDate);
			
			//count(Attendance::LoadAttendanceForCommunityObservers($this->GroupID,$StartDate,$EndDate));
		}
		
		public function CountFirstTimersBetweenDates($StartDate,$EndDate)
		{
			$Records = getFirstTimeAttendancesBetweenDates($this->GroupID,$StartDate,$EndDate)->fetchAll();
			
			return count($Records);
		}
				
		public function LoadFirstTimersBetweenDates($StartDate,$EndDate) //returns attendance objects
		{
			return \Business\Attendance::LoadAttendanceFirstTimers($this->GroupID,$StartDate,$EndDate);
		}
		
		public function LoadFirstTimerStatsBetweenDates($StartDate,$EndDate) //returns attendance objects
		{
			return \Business\Attendance::FirstTimerStatsByGroupDates($this->GroupID,$StartDate,$EndDate);
		}
		
		//this only counts scheduled meetings that actually have attendance.
		public function CountMeetingsAttendedInPeriod($StartDate,$EndDate)
		{
			$arrCountMeetings = getCountMeetingsAttendedInPeriod($this->GroupID,$StartDate,$EndDate)->fetch();
			
			return $arrCountMeetings['total_meetings'];
		}
		
		public function CountMeetingsScheduledInPeriod($StartDate,$EndDate)
		{
			return count($this->GetMeetingsScheduledBetweenDates($StartDate,$EndDate));
		}
		
		public function GetMeetingsScheduledBetweenDates($StartDate,$EndDate)
		{
			$AllDatesToToday = $this->LoadGroupDates();
			
			if($AllDatesToToday == NULL)
			{
				return NULL;
			}
			
			$ReleventDates = array();
			
			foreach($AllDatesToToday as $Date)
			{
				if( $Date >= $StartDate and $Date <= $EndDate )
				{
					$ReleventDates[] = $Date;
				}
			}
			
			return $ReleventDates;
		}
		
		public static function LoadGroup($GroupID,$show_deleted = false)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroup = getGroup($GroupID,$show_deleted);
			
			if($pdoGroup->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Group::ArrToGroup($pdoGroup->fetch());
		}
		
		public static function IsGroupInGroups($GroupID,$Groups)
		{
			foreach( $Groups as $Group)
			{
				if( $GroupID == $Group->GetGroupID() )
				{
					return true;
				}
			}
			
			return false;
		}
		
		public static function LoadAllGroups($show_deleted = false)
		{
			$arrGroups = getAllGroups($show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsBySearchString($SearchString,$show_deleted = false)
		{
			$arrGroups = getGroupsBySearchString($SearchString,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsBySearchStringImproved($SearchString,$LimitTo,$ExtraLimit)
		{
			$arrGroups = getGroupsBySearchStringImproved($SearchString,$LimitTo,$ExtraLimit)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			$arrGroups = getActiveGroupsByRegions($UserID,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByRegionsBySearchString($UserID,$SearchString,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			$arrGroups = getGroupsByRegionsBySearchString($UserID,$SearchString,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByRegionsBySearchStringImproved($UserID,$SearchString,$LimitTo,$ExtraLimit) //this function utilises the regions attached to a staff member
		{
			$arrGroups = getGroupsByRegionsBySearchStringImproved($UserID,$SearchString,$LimitTo,$ExtraLimit)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
				
		public static function LoadGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			$arrGroups = getGroupsByRegions($UserID,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsAndNonGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			$arrGroups = getGroupsAndNonGroupsByRegions($UserID,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByRoles($UserID,$show_deleted = false)
		{
			$arrGroups = getGroupsByRoles($UserID,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		
		public static function LoadGroupsByRolesBySearchString($UserID,$SearchString,$show_deleted = false)
		{
			$arrGroups = getGroupsByRolesBySearchString($UserID,$SearchString,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByRolesBySearchStringImproved($UserID,$SearchString,$LimitTo,$ExtraLimit)
		{
			$arrGroups = getGroupsByRolesBySearchStringImproved($UserID,$SearchString,$LimitTo,$ExtraLimit)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadActiveGroupsByRoles($UserID,$show_deleted = false)
		{
			$arrGroups = getActiveGroupsByRoles($UserID,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function load_archived_groups_by_state($staff_id,$show_deleted = false)
		{
			$arr_groups = get_archived_groups_by_state($staff_id,$show_deleted)->fetchAll();
			
			$groups = array();
						
			foreach( $arr_groups As $group )
			{
				$groups[] = Group::ArrToGroup($group);
			}
			
			return $groups;
		}
		
		public static function load_active_groups_by_state($staff_id,$show_deleted = false)
		{
			$arr_groups = get_active_groups_by_state($staff_id,$show_deleted)->fetchAll();
			
			$groups = array();
						
			foreach( $arr_groups As $group )
			{
				$groups[] = Group::ArrToGroup($group);
			}
			
			return $groups;
		}
		
		public static function LoadGroupsAndNonGroupsByState($StaffID,$showDeleted = false)
		{
			$arrGroups = getGroupsAndNonGroupsByState($StaffID,$showDeleted)->fetchAll();
			
			$Groups = array();
						
			foreach( $arrGroups As $Group )
			{
				$Groups[] = Group::ArrToGroup($Group);
			}
			
			return $Groups;
		}
		
		public static function load_groups_by_state($staff_id,$show_deleted = false)
		{
			$arr_groups = get_groups_by_state($staff_id,$show_deleted)->fetchAll();
			
			$groups = array();
						
			foreach( $arr_groups As $group )
			{
				$groups[] = Group::ArrToGroup($group);
			}
			
			return $groups;
		}
		
		public static function load_groups_by_state_by_searchstring($staff_id,$search_string,$show_deleted = false)
		{
			$arr_groups = get_groups_by_state_by_searchstring($staff_id,$search_string,$show_deleted)->fetchAll();
			
			$groups = array();
						
			foreach( $arr_groups As $group )
			{
				$groups[] = Group::ArrToGroup($group);
			}
			
			return $groups;
		}
		
		public static function loadGroupsByStateBySearchStringImproved($StaffID,$SearchString,$LimitTo,$ExtraLimit)
		{
			$ArrayGroups = getGroupsByStateBySearchStringImproved($StaffID,$SearchString,$LimitTo,$ExtraLimit)->fetchAll();
			
			$Groups = array();
						
			foreach( $ArrayGroups As $Group )
			{
				$Groups[] = Group::ArrToGroup($Group);
			}
			
			return $Groups;
		}
		
		public static function LoadActiveGroupsByState($StateAbr,$show_deleted = false)
		{
			$arrGroups = getActiveGroupsByState($StateAbr,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByState($StateAbr,$show_deleted = false)
		{
			$arrGroups = getGroupsByState($StateAbr,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadGroupsByStateBySearchString($StateAbr,$SearchString,$show_deleted = false)
		{
			$arrGroups = getGroupsByStateBySearchString($StateAbr,$SearchString,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadActiveGroups($show_deleted = false)
		{
			$arrGroups = getActiveGroups($show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedGroupsByState($StateAbr,$show_deleted = false)
		{
			$arrGroups = getArchivedGroupsByState($StateAbr,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedGroups($show_deleted = false)
		{
			$arrGroups = getArchivedGroups($show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Group::ArrToGroup($Group);
			}
			
			return $thisGroups;
		}
		
		public function HadOrganiser($StartDate,$EndDate)
		{
			//global $Organiser;
			
			return count(\Membership\Staff::LoadGroupLeadersByDates(ORGANISER,$this->GroupID,$StartDate,$EndDate)) > 0;
		}
		
		public function HadRecorder($StartDate,$EndDate)
		{
			//global $Recorder;
			
			return count(\Membership\Staff::LoadGroupLeadersByDates(RECORDER,$this->GroupID,$StartDate,$EndDate)) > 0;
		}
		
		public function LoadGroupsRecesses()
		{
			
			return GroupRecess::LoadGroupsRecesses($this->GroupID);
		}
		
		public static function CreateGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED)
		{
			$GroupID = addGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED);
			
			return Group::LoadGroup($GroupID);
		}
		
		public function UpdateGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED)
		{
			$this->GroupName = $GroupName;
			$this->GroupTypeID = $GroupTypeID;
			$this->StartDate = $SafeSD;
			$this->EndDate = $SafeED;
			
			updGroup($this->GroupID,$this->GroupName,$this->GroupTypeID,$this->StartDate,$this->EndDate);
			
		}
		
		public static function LoadMemberLastGroupAttended($UserID,$show_deleted = false)
		{
			$arrGroup = getMemberLastGroupAttended($UserID,$show_deleted)->fetch();
			
			if( $arrGroup['groups_attended'] == 0 ) //due to rows returned by aggregate query using count
			{
				return NULL;
			} else {
				return  Group::ArrToGroup($arrGroup);
			}
			
		}
		
		public function GetMemberLastAttendedGroup($UserID)
		{
			$UserID = intval($UserID);
			
			$pdoDate = getMemberLastAttendedGroup($this->GroupID,$UserID);
			
			if($pdoDate->rowCount() != 1 )
			{
				return NULL;
			}
			
			$arrDate = $pdoDate->fetch();
			
			return funAusDateFormat($arrDate['the_date']);
			
		}
				
		public function LoadGroupsCurrentRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GroupID);
		}
				
		public function LoadGroupsLastRegion() // returns a group region not a region
		{
			return GroupRegion::LoadGroupsLastRegion($this->GroupID);
		}
		
		public function LoadGroupsLastVenue() // returns a group venue not a venue
		{
			return GroupVenue::LoadGroupsLastVenue($this->GroupID);
		}
		
		public function LoadGroupsCurrentVenue() // returns a group venue not a venue
		{
			return GroupVenue::LoadGroupsCurrentVenue($this->GroupID);
		}
		
		public function GroupHasRegion()
		{
			return GroupRegion::GroupHasRegion($this->GroupID);
		}
		
		public function GroupHasVenue()
		{
			return GroupVenue::GroupHasVenue($this->GroupID);
		}
		
		public function LoadGroupsRegions()
		{
			return GroupRegion::LoadGroupsRegions($this->GroupID);
		}
		
		public function LoadGroupsRegionByDate($Date)
		{
			return GroupRegion::LoadGroupsRegionByDate($this->GroupID,$Date);
		}
		
		public function LoadGroupsVenues()
		{
			return GroupVenue::LoadGroupsVenues($this->GroupID);
		}
		
		public function IsMyGroupRegion($GroupRegionID)
		{
			$GroupRegionID = intval($GroupRegionID);
			
			return (isGroupsGroupRegion($this->GroupID,$GroupRegionID)->rowCount() != 1);
			
		}
		
		public function IsMyGroupVenue($GroupVenueID)
		{
			$GroupVenueID = intval($GroupVenueID);
			
			return (isGroupsGroupVenue($this->GroupID,$GroupVenueID)->rowCount() != 1);
			
		}
		
		public function LoadGroupsSchedules($Sort = 'DESC')
		{
			return GroupSchedule::LoadGroupsSchedules($this->GroupID,$Sort);
		}
		
		public function LoadGroupsScheduleDates($Sort = 'DESC')
		{
			return GroupScheduledDate::LoadGroupsScheduledDates($this->GroupID,$Sort);
		}
		
		public function GroupHasDates()
		{
			return GroupScheduledDate::GroupHasDates($this->GroupID);
		}
		
		public function GroupHasSchedule()
		{
			return GroupSchedule::GroupHasSchedule($this->GroupID);
		}
		
		public function GroupHasScheduleDates()
		{
			return GroupScheduledDate::GroupHasScheduledDates($this->GroupID);
		}
		
		public function LoadGroupsLastSchedule() // returns a group region not a region
		{
			return GroupSchedule::LoadGroupsLastSchedule($this->GroupID);
		}
		
		public function LoadGroupsCurrentSchedule() // returns a group region not a region
		{
			return GroupSchedule::LoadGroupsCurrentSchedule($this->GroupID);
		}
		
		public function LoadGroupsLeaders()
		{
			return GroupLeader::LoadGroupsLeaders($this->GroupID);
		}
		
		public function LoadGroupsLeadersByDate($Date)
		{
			return GroupLeader::LoadGroupsLeadersByDate($this->GroupID,$Date);
		}
		
		public function LoadLastGroupAttendances($Date)
		{
			return Attendance::LoadLastGroupAttendancesByGroupAndDate($this->GroupID,$Date,3);
		}
		
		public function LoadStaffRegionsByDate($Date)
		{
			return StaffRegion::LoadStaffRegionsByDate($this->GroupID,$Date);
		}
		
		public function LoadGroupDatesWithNotes($SecurityLevel)
		{
			$arrDates = getAllGroupNoteDates($this->GroupID,$SecurityLevel)->fetchAll();
			
			$arrDatesYMD = array();
			
			foreach($arrDates as $Date)
			{
				$arrDatesYMD[] = date_create($Date['fld_date'])->format('Y-m-d');
			}
			
			return $arrDatesYMD;
			
		}
		
		public function LoadGroupDates() //returns an array of all the dates on which a group occurred.
		{
			//Get dates from schedules
			$Schedules = $this->LoadGroupsSchedules('ASC');
			$Dates = $this->LoadGroupsScheduleDates('ASC');
			
			if( count($Schedules) == 0 and count( $Dates ) == 0)
			{
				return NULL;
			} else {
				
				$arr_dates = array();
				
				if ( count($Schedules) > 0 )
				{
					
					$dte_today = date_create(date('Y-m-d H:i:s'));
				
					$dte_start_date = date_create($Schedules[0]->GetStartDate());
					$str_end_date = $Schedules[count($Schedules) - 1]->GetEndDate();
					
					//choose the end date
					if( $str_end_date == '')
					{
						$dte_end_date = $dte_today;
					} else {
						$dte_end_date = date_create($str_end_date);
					}
					
					$index = 0;
					
					while($dte_start_date < $dte_end_date or $index < count($Schedules))
					{
						
						$str_this_end_date = $Schedules[$index]->GetEndDate(); // choose the most recent end date
						$dte_start_date = date_create($Schedules[$index]->GetStartDate()); //arbitrary start date
						//choose the end date
						if( $str_this_end_date == '')
						{
							$dte_this_end_date = $dte_today;
						} else {
							$dte_this_end_date = date_create($str_this_end_date);
						}
						
						//get the interval
						$period_code = funConvertPeriodToCode($Schedules[$index]->GetRecurrencyString());
						$int_interval = funPeriodInterval($Schedules[$index]->GetRecurrencyString(),$Schedules[$index]->GetRecurrencyInt());
						$ivl_interval = new \DateInterval('P'.$int_interval.$period_code);
						
						while( $dte_start_date < $dte_this_end_date )
						{
							$arr_dates[] = $dte_start_date->format('Y-m-d'); //append the value in a form that is safe for entry into the database
							$dte_start_date = date_add($dte_start_date,$ivl_interval); //increment the date
							
						}
						
						$index++;
					} // End Schedules
					
					if( count( $Dates ) > 0 )
					{
						foreach($Dates as $Date)
						{
							for( $i = 0; $i < count($arr_dates); $i ++ )
							{
								if( $Date->GetDate() <= $arr_dates[$i] )
								{
									if($Date->GetDate() != $arr_dates[$i]) //do nothing if the date already exists 
									{
										$arr_dates = funInsertInArrayPos($arr_dates, $i, date_create($Date->GetDate())->format('Y-m-d'));
									}
									
									break;
								}
							}
						}
					}
					
				} else {
					
					//assume that there must be individual dates
					
					foreach($Dates as $Date)
					{
						$arr_dates[] = date_create($Date->GetDate())->format('Y-m-d');
					}
					
				} // End If Schedules 
								
				arsort($arr_dates); //reverse the order of the array
				
				//remove duplicates
				$arr_dates = array_unique($arr_dates);
				
				//rebuild the index
				$arr_final = array_values($arr_dates);
				
				return $arr_final;
			}
			
		}//END LOAD GROUP DATES
		
		public function IsGroupDate($Date)  // takes Y m d format
		{
			$Dates = $this->LoadGroupDates();
			
			foreach($Dates as $thisDate)
			{
				if($thisDate == $Date)
				{
					return true;
				}
			}
			
			return false;
		}
				
		public function HasAttendanceOnDate($Date) 
		{
			
			return (groupAttendanceOnDate($this->GroupID,$Date) > 0 );
			
		}
		
		public function HasGroupNoteOnDate($Date,$UserSecurityLevel)
		{
			return (groupNoteOnDateBySecurityLevel($this->GroupID,$Date,$UserSecurityLevel) > 0 );
		}
		
		public function LoadNoMeetingReasonRecordOnDate($Date)
		{
			return GroupNoMeeting::LoadGroupNoMeetingByGroupAndDate($this->GroupID,$Date);
		}
		
		public function HasAttendanceReasonRecordOnDate($Date)
		{
			
			return (noAttendanceReasonRecordOnDate($this->GroupID,$Date) > 0 );
		}
		
		public function GetExternalAttendees($Date)
		{
			$GroupDateAttendees = getExternalAttendeesByGroupAndDate($this->GroupID,$Date);
			
			if( $GroupDateAttendees->rowCount() == 0 )
			{
				return 0;
			}
			else
			{
				$arrAttendees = $GroupDateAttendees->fetch();
				
				return intval($arrAttendees['fld_no_attendees']);
			}
		}
		
		public function SetExternalAttendees($Date,$int_attendees)
		{
			$GroupDateAttendees = getExternalAttendeesByGroupAndDate($this->GroupID,$Date);
			
			if($GroupDateAttendees->rowCount() == 0)
			{
				addGroupDateAttendee($this->GroupID,$Date,$int_attendees);
			}
			else
			{
				updGroupDateAttendee($this->GroupID,$Date,$int_attendees);
			}
		}
		
	} //End Group Class 
	
	function delGroup($GroupID)
	{
		global $dbh;
	
		try {
			$qryDel = $dbh->prepare(' 	DELETE FROM tbl_groups WHERE id_group = :GroupID; ');
			$qryDel->execute(array(		
										':GroupID' => $GroupID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return true;
	}
	
	function getCountCommunityObservers($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryGet = $dbh->prepare(' 	SELECT SUM(fld_no_attendees) AS Total
										FROM tbl_group_attendance_other
										WHERE fld_date BETWEEN :StartDate AND :EndDate
										AND fld_group_id = :GroupID ');
			$qryGet->execute(array(		
										':StartDate' => $StartDate,
										':EndDate' => $EndDate,
										':GroupID' => $GroupID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$ThisTotal = $qryGet->fetch();
		
		return $ThisTotal['Total'];
	}
	
	function addGroupDateAttendee($GroupID,$Date,$int_attendees)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_group_attendance_other(fld_group_id,fld_date,fld_no_attendees) 
									  VALUES (:GroupID,:Date,:int_attendees) ');
			$qryInsert->execute(array(	':GroupID' => $GroupID,
										':Date' => ($Date == 'null') ? null : $Date,
										':int_attendees' => $int_attendees
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function updGroupDateAttendee($GroupID,$Date,$int_attendees)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_group_attendance_other
										SET fld_no_attendees = :int_attendees
										WHERE fld_group_id = :GroupID
										AND fld_date = :Date
										');
			$qryUpdate->execute(array(
										':int_attendees' => $int_attendees,
										':GroupID' => $GroupID,
										':Date' => $Date
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function getExternalAttendeesByGroupAndDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$GroupDateAttendees = $dbh->prepare('	
									SELECT fld_no_attendees
									FROM tbl_group_attendance_other
									WHERE fld_group_id = :GroupID
									AND fld_date = :Date
									
									');
			$GroupDateAttendees->execute(array(	
										':GroupID' => $GroupID,
										':Date' => $Date
			
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupDateAttendees;
		
	}
	
	function groupNoteOnDateBySecurityLevel($GroupID,$Date,$SecurityLevel)
	{
		global $dbh;
		
		try {
			
			$NteCount = $dbh->prepare('	
									SELECT COUNT(*) As Notes
									FROM tbl_notes
									WHERE fld_group_id = :GroupID
									AND fld_date = :Date
									AND fld_security_level = :SecurityLevel
									AND fld_deleted = 0
									');
			$NteCount->execute(array(	':GroupID' => $GroupID,
										':Date' => $Date,
										':SecurityLevel' => $SecurityLevel ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$arr_Count = $NteCount->fetch();
		
		return $arr_Count['Notes'];
	}
	
	function getAllGroupNoteDates($GroupID,$SecurityLevel,$show_deleted = false)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );

		try {
			
			$GroupDates = $dbh->prepare('	
									SELECT fld_date
									FROM tbl_notes
									WHERE fld_group_id = :GroupID
									AND fld_security_level >= :SecurityLevel
									'.$show.'
									GROUP BY fld_date
									ORDER BY fld_date DESC;
									
									');
			$GroupDates->execute(array(	
										':GroupID' => $GroupID,
										':SecurityLevel' => $SecurityLevel
			
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupDates;
	}
	
	function setGroupDeleted($GroupID,$Deleted)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_deleted = :Deleted
										WHERE id_group = :GroupID
										');
			$qryUpdate->execute(array(
										':Deleted' => $Deleted,
										':GroupID' => $GroupID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function getGroupsWithRecessNearEnd($months_till_end,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );

		try {
			
			$Groups = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE EXISTS(
												SELECT *
												FROM tbl_group_recess
												WHERE tbl_group_recess.fld_group_id = tbl_groups.id_group
												AND TIMESTAMPDIFF(MONTH,DATE(NOW()),fld_end_date) BETWEEN 0 AND :months_till_end
												'.$show.'
												)
									'.$show.'
									AND fld_non_group_type IS NULL
									ORDER BY fld_group_name ASC;
									
									');
			$Groups->execute(array(	':months_till_end' => $months_till_end
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsWithoutOrganisers($months_without,$show_deleted)
	{
		global $dbh;
		//global $Organiser;
	
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Groups = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE NOT EXISTS(
												SELECT *
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												WHERE tbl_groups_roles.fld_group_id = tbl_groups.id_group
												AND tbl_groups_roles.fld_deleted = 0
												AND tbl_groups_roles.fld_start_date <= CURDATE()
												AND ( tbl_groups_roles.fld_end_date IS NULL
													OR tbl_groups_roles.fld_end_date >= DATE_ADD(CURDATE(),INTERVAL - :months_without2 MONTH) )
												AND tbl_group_roles.fld_group_role = '."'".ORGANISER."'".'
												)
									AND fld_start_date <= DATE_ADD(CURDATE(),INTERVAL - :months_without3 MONTH)
									AND ( fld_end_date IS NULL
										OR fld_end_date >= DATE_ADD(CURDATE(),INTERVAL - :months_without4 MONTH) )
									AND fld_non_group_type IS NULL
									'.$show.'
									ORDER BY fld_group_name ASC;
									
									');
			$Groups->execute(array(	
									':months_without2' => $months_without,
									':months_without3' => $months_without,
									':months_without4' => $months_without
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getCountAllGroupAttendancesInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		try {
			
			$Total = $dbh->prepare('	
									SELECT COUNT(*) AS Total
									FROM tbl_group_attendance
									WHERE fld_deleted = 0
									AND fld_group_id = :GroupID
									AND fld_date BETWEEN :StartDate AND :EndDate									
									');
			$Total->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Total;
	}
	
	function getCountAllNonCommunityObserverAttendeesInPeriodByGroup($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Total = $dbh->prepare('	
									SELECT COUNT(DISTINCT fld_user_id) AS Total
									FROM tbl_group_attendance
									WHERE fld_deleted = 0
									AND fld_group_id = :GroupID
									AND fld_date BETWEEN :StartDate AND :EndDate
									
									');
			$Total->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Total;
	}
	
	function getGroupsByBranchPeriod($BranchID,$StartDate,$EndDate,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_regions.fld_deleted = 0 AND tbl_groups_regions.fld_deleted = 0' );
		$show_group = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		try {
			
			$Groups = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE EXISTS(
												SELECT *
												FROM tbl_groups_regions
												JOIN tbl_regions
												ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_regions.fld_branch_id = :BranchID
												AND tbl_groups_regions.fld_start_date <= :EndDate
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :StartDate)
												'.$show.'
												)
									AND fld_non_group_type IS NULL
									'.$show_group.'
									ORDER BY fld_group_name ASC;
									
									');
			$Groups->execute(array(	':BranchID' => $BranchID,
									':EndDate' => $EndDate,
									':StartDate' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRegionPeriod($RegionID,$StartDate,$EndDate,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Groups = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND fld_region_id = :RegionID
												AND fld_start_date <= :EndDate
												AND (fld_end_date IS NULL OR fld_end_date >= :StartDate)
												AND tbl_groups_regions.fld_deleted = 0
												)
									AND fld_non_group_type IS NULL
									'.$show.'
									ORDER BY fld_group_name ASC;
									
									');
			$Groups->execute(array(	':RegionID' => $RegionID,
									':EndDate' => $EndDate,
									':StartDate' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getCountMeetingsAttendedInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT COUNT(DISTINCT fld_date) AS total_meetings
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_date BETWEEN :StartDate AND :EndDate
									AND fld_deleted = 0
									
									');
			$Count->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getMemberLastGroupAttended($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			
			$Group = $dbh->prepare('	
									SELECT tbl_groups.*, MAX(tbl_group_attendance.fld_date) AS the_date, COUNT(*) AS groups_attended
									FROM tbl_groups
									JOIN tbl_group_attendance
									ON tbl_groups.id_group = tbl_group_attendance.fld_group_id
									WHERE tbl_group_attendance.fld_user_id = :UserID
									AND tbl_group_attendance.fld_deleted = 0
									AND fld_non_group_type IS NULL
									'.$show.'
									');
			$Group->execute(array(':UserID' => $UserID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Group;
	}
	
	function getMemberLastAttendedGroup($GroupID,$UserID)
	{
		global $dbh;
		
		try {
			
			$Group = $dbh->prepare('	
									SELECT MAX(tbl_group_attendance.fld_date) AS the_date
									FROM tbl_group_attendance
									WHERE tbl_group_attendance.fld_user_id = :UserID
									AND tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									');
			$Group->execute(array(	':UserID' => $UserID,
									':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Group;
	}
	
	function noAttendanceReasonRecordOnDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$AttCount = $dbh->prepare('	
									SELECT COUNT(*) As Records
									FROM tbl_no_meetings_info
									WHERE fld_group_id = :GroupID
									AND fld_group_date = :Date
									AND fld_deleted = 0
									');
			$AttCount->execute(array(':GroupID' => $GroupID,
										':Date' => $Date, ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$arr_Count = $AttCount->fetch();
		
		return $arr_Count['Records'];
	}
	
	function groupAttendanceOnDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$AttCount = $dbh->prepare('	
									SELECT COUNT(*) As Attendances
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_date = :Date
									AND fld_deleted = 0
									');
			$AttCount->execute(array(':GroupID' => $GroupID,
										':Date' => $Date ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$arr_Count = $AttCount->fetch();
		
		return $arr_Count['Attendances'];
	}
	
	function isGroupsGroupRegion($GroupID,$GroupRegionID)
	{
		global $dbh;
		
		try {
			
			$GroupRegion = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE fld_group_id = :GroupID
									AND id_group_region = :GroupRegionID
									');
			$GroupRegion->execute(array(':GroupID' => $GroupID,
										':GroupRegionID' => $GroupRegionID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupRegion;
	}
	
	function isGroupsGroupVenue($GroupID,$GroupVenueID)
	{
		global $dbh;
		
		try {
			
			$GroupVenue = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_venues
									WHERE fld_group_id = :GroupID
									AND id_group_venue = :GroupVenueID
									');
			$GroupVenue->execute(array(':GroupID' => $GroupID,
										':GroupVenueID' => $GroupVenueID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupVenue;
	}
	
	function updGroup($GroupID,$GroupName,$GroupTypeID,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :GroupName,
										fld_group_type = :GroupTypeID,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :GroupID
										');
			$qryUpdate->execute(array(
										':GroupName' => $GroupName,
										':GroupTypeID' => $GroupTypeID,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':GroupID' => $GroupID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addGroup($GroupName,$GroupTypeID,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_group_type,fld_start_date,fld_end_date) 
									  VALUES (:GroupName,:GroupTypeID,:SafeSD,:SafeED) ');
			$qryInsert->execute(array(	':GroupName' => $GroupName,
										':GroupTypeID' => $GroupTypeID,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getGroup($GroupID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Group = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :GroupID
									'.$show.'
									');
			$Group->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Group;
	}
	
	function getAllGroups($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->query("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC
									");
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsBySearchStringImproved($SearchString,$RecordLimit,$ExtraLimit)
	{
		global $dbh;
		
		if( $RecordLimit == 'all' )
		{
			$LimitToString = '';
			
			$param_array = array(	
									':SearchString' => $SearchString
									);
			
		} else {
			$LimitToString = 'LIMIT :RecordLimit';
			
			$param_array = array(	
									':SearchString' => $SearchString,
									':RecordLimit' => $RecordLimit
									);
		}
		
		if( $ExtraLimit == 'all' )
		{
			$Extra = '';	
		} elseif( $ExtraLimit == 'current' )
		{
			$Extra = 'AND (fld_end_date IS NULL OR fld_end_date >= DATE(NOW()))';
		}
		elseif( $ExtraLimit == 'archive' )
		{
			$Extra = 'AND fld_end_date < DATE(NOW())';
		} else {
			$Extra = '';
		}
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									AND fld_deleted = 0
									".$Extra."
									ORDER BY fld_group_name ASC
									".$LimitToString."
									
									");
			
		$Groups->execute($param_array);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsBySearchString($SearchString,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC
									");
			
		$Groups->execute(array(':SearchString' => $SearchString ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getArchivedGroups($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->query('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type IS NULL
									'.$show.'
									');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function get_archived_groups_by_state($staff_id,$show_deleted)  //also grabs groups
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE tbl_groups.fld_end_date < DATE(NOW())
									AND (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :staff_id
																		)
									OR tbl_regions.fld_branch_id IS NULL)
									AND fld_non_group_type IS NULL
									'.$show.'
									GROUP BY tbl_groups.id_group
									');
			$Groups->execute(array(':staff_id' => $staff_id ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getArchivedGroupsByState($StateAbr,$show_deleted)  //also grabs groups
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									LEFT OUTER JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE tbl_groups.fld_end_date < DATE(NOW())
									AND (tbl_branches.fld_branch_abbreviation = :StateAbr
									OR tbl_branches.fld_branch_abbreviation IS NULL)
									AND fld_non_group_type IS NULL
									'.$show.'
									GROUP BY tbl_groups.id_group
									');
			$Groups->execute(array(':StateAbr' => $StateAbr ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsAndNonGroupsByState($StaffID,$showDeleted)
	{
		global $dbh;
		
		$show = ( $showDeleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :StaffID
																		)
									OR tbl_groups_regions.fld_branch_id IN(
																			SELECT fld_branch_id
																			FROM tbl_state_users_state_activity_dates
																			WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																			AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																			AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																				OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																				)
																			AND fld_user_id = :StaffID2
																			)
									OR tbl_regions.fld_branch_id IS NULL) 
									".$show."
									ORDER BY fld_group_name ASC;
									");
			$Groups->execute(array(	
									':StaffID' => $StaffID,
									':StaffID2' => $StaffID
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function get_groups_by_state($staff_id,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :staff_id
																		)
									OR tbl_regions.fld_branch_id IS NULL)
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;
									");
			$Groups->execute(array(	':staff_id' => $staff_id
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByState($StateAbr,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									LEFT OUTER JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE (tbl_branches.fld_branch_abbreviation = :StateAbr
									OR tbl_branches.fld_branch_abbreviation IS NULL)
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;
									");
			$Groups->execute(array(	':StateAbr' => $StateAbr
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function GetGroupsByStateBySearchStringImproved($StaffID,$SearchString,$RecordLimit,$ExtraLimit)
	{
		global $dbh;
		
		if( $RecordLimit == 'all' )
		{
			$LimitToString = '';
			
			$param_array = array(	
									':StaffID' => $StaffID,
									':SearchString' => $SearchString
									);
			
		} else {
			$LimitToString = 'LIMIT :RecordLimit';
			
			$param_array = array(	
									':StaffID' => $StaffID,
									':SearchString' => $SearchString,
									':RecordLimit' => $RecordLimit
									);
		}
		
		if( $ExtraLimit == 'all' )
		{
			$Extra = '';	
		} elseif( $ExtraLimit == 'current' )
		{
			$Extra = 'AND (tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW()))';
		}
		elseif( $ExtraLimit == 'archive' )
		{
			$Extra = 'AND tbl_groups.fld_end_date < DATE(NOW())';
		} else {
			$Extra = '';
		}
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :StaffID
																		)
											OR tbl_regions.fld_branch_id IS NULL)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									".$Extra."
									ORDER BY fld_group_name ASC
									".$LimitToString."
									;
									");
			$Groups->execute($param_array
									 );
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function get_groups_by_state_by_searchstring($staff_id,$search_string,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0 AND tbl_groups_regions.fld_deleted = 0 AND tbl_regions.fld_deleted = 0' );
		
		
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :staff_id
																		)
											OR tbl_regions.fld_branch_id IS NULL)
									AND fld_group_name LIKE concat('%', :search_string, '%')
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;
									");
			$Groups->execute(array(	':staff_id' => $staff_id,
									':search_string' => $search_string
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	
	function getGroupsByStateBySearchString($StateAbr,$SearchString,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0 AND tbl_groups_regions.fld_deleted = 0 AND tbl_regions.fld_deleted = 0' );
		
		
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									LEFT OUTER JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE (tbl_branches.fld_branch_abbreviation = :StateAbr
									OR tbl_branches.fld_branch_abbreviation IS NULL)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;
									");
			$Groups->execute(array(	':StateAbr' => $StateAbr,
									':SearchString' => $SearchString
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsAndNonGroupsByRegions($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												)
									".$show."
									ORDER BY fld_group_name ASC;			
									");
			$Groups->execute(array(	':UserID' => $UserID
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRegions($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												)
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;			
									");
			$Groups->execute(array(	':UserID' => $UserID
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRegionsBySearchStringImproved($UserID,$SearchString,$RecordLimit,$ExtraLimit)
	{
		global $dbh;
		
		if( $RecordLimit == 'all' )
		{
			$LimitToString = '';
			
			$param_array = array(	
									':UserID' => $UserID,
									':SearchString' => $SearchString
									);
			
		} else {
			$LimitToString = 'LIMIT :RecordLimit';
			
			$param_array = array(	
									':UserID' => $UserID,
									':SearchString' => $SearchString,
									':RecordLimit' => $RecordLimit
									);
		}
		
		if( $ExtraLimit == 'all' )
		{
			$Extra = '';	
		} elseif( $ExtraLimit == 'current' )
		{
			$Extra = 'AND (tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW()))';
		}
		elseif( $ExtraLimit == 'archive' )
		{
			$Extra = 'AND tbl_groups.fld_end_date < DATE(NOW())';
		} else {
			$Extra = '';
		}
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												AND tbl_groups_regions.fld_deleted = 0
												)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									AND fld_deleted = 0
									".$Extra."
									ORDER BY fld_group_name ASC
									".$LimitToString.";			
									");
			$Groups->execute($param_array
									 );
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRegionsBySearchString($UserID,$SearchString,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		$show_inner = ( $show_deleted ? '' : 'AND tbl_groups_regions.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												".$show_inner."
												)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;			
									");
			$Groups->execute(array(	':UserID' => $UserID,
									':SearchString' => $SearchString
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function get_active_groups_by_state($staff_id,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE ( tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW())  )
									AND (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :staff_id
																		)
									OR tbl_regions.fld_branch_id IS NULL)
									'.$show .'
									AND fld_non_group_type IS NULL
									GROUP BY tbl_groups.id_group
									ORDER BY fld_group_name ASC;
									');
			$Groups->execute(array(':staff_id' => $staff_id ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getActiveGroupsByState($StateAbr,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									LEFT OUTER JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE ( tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW())  )
									AND (tbl_branches.fld_branch_abbreviation = :StateAbr
									OR tbl_branches.fld_branch_abbreviation IS NULL)
									AND fld_non_group_type IS NULL
									'.$show .'
									GROUP BY tbl_groups.id_group
									ORDER BY fld_group_name ASC;
									');
			$Groups->execute(array(':StateAbr' => $StateAbr ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRoles($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_groups_roles.*
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_group_id = tbl_groups.id_group
												AND tbl_groups_roles.fld_user_id = :UserID
												AND tbl_groups_roles.fld_start_date <= CURDATE()
												AND (tbl_groups_roles.fld_end_date IS NULL OR tbl_groups_roles.fld_end_date >= CURDATE())
												AND tbl_groups_roles.fld_deleted = 0
												)
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;			
									");
			$Groups->execute(array(':UserID' => $UserID ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRolesBySearchStringImproved($UserID,$SearchString,$RecordLimit,$ExtraLimit)
	{
		global $dbh;
		
		if( $RecordLimit == 'all' )
		{
			$LimitToString = '';
			
			$param_array = array(	
									':UserID' => $UserID,
									':SearchString' => $SearchString
									);
			
		} else {
			$LimitToString = 'LIMIT :RecordLimit';
			
			$param_array = array(	
									':UserID' => $UserID,
									':SearchString' => $SearchString,
									':RecordLimit' => $RecordLimit
									);
		}
		
		if( $ExtraLimit == 'all' )
		{
			$Extra = '';	
		} elseif( $ExtraLimit == 'current' )
		{
			$Extra = 'AND (tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW()))';
		}
		elseif( $ExtraLimit == 'archive' )
		{
			$Extra = 'AND tbl_groups.fld_end_date < DATE(NOW())';
		} else {
			$Extra = '';
		}
		
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_groups_roles.*
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_group_id = tbl_groups.id_group
												AND tbl_groups_roles.fld_user_id = :UserID
												AND tbl_groups_roles.fld_start_date <= CURDATE()
												AND (tbl_groups_roles.fld_end_date IS NULL OR tbl_groups_roles.fld_end_date >= CURDATE())
												AND tbl_groups_roles.fld_deleted = 0
												AND tbl_groups_roles.fld_deleted = 0
												)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									AND fld_deleted = 0
									".$Extra."
									ORDER BY fld_group_name ASC
									".$LimitToString.";			
									");
			$Groups->execute($param_array);
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getGroupsByRolesBySearchString($UserID,$SearchString,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		$show_inner = ( $show_deleted ? '' : 'AND tbl_groups_roles.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare("SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_groups_roles.*
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_group_id = tbl_groups.id_group
												AND tbl_groups_roles.fld_user_id = :UserID
												AND tbl_groups_roles.fld_start_date <= CURDATE()
												AND (tbl_groups_roles.fld_end_date IS NULL OR tbl_groups_roles.fld_end_date >= CURDATE())
												AND tbl_groups_roles.fld_deleted = 0
												".$show_inner."
												)
									AND fld_group_name LIKE concat('%', :SearchString, '%')
									AND fld_non_group_type IS NULL
									".$show."
									ORDER BY fld_group_name ASC;			
									");
			$Groups->execute(array(':UserID' => $UserID,
									':SearchString' => $SearchString ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getActiveGroupsByRoles($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_groups_roles.*
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_group_id = tbl_groups.id_group
												AND tbl_groups_roles.fld_user_id = :UserID
												AND tbl_groups_roles.fld_start_date <= CURDATE()
												AND (tbl_groups_roles.fld_end_date IS NULL OR tbl_groups_roles.fld_end_date >= CURDATE())
												AND tbl_groups_roles.fld_deleted = 0
												)
									AND fld_non_group_type IS NULL
									'.$show.'
									ORDER BY fld_group_name ASC;			
									');
			$Groups->execute(array(':UserID' => $UserID ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getActiveGroupsByRegions($UserID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												)
												
									AND fld_non_group_type IS NULL
									'.$show.'
									ORDER BY fld_group_name ASC;			
									');
			$Groups->execute(array(':UserID' => $UserID ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getActiveGroups($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->query('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type IS NULL
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	class Note {
		private $NoteID;
		private $GroupID;
		private $RegionID;
		private $BranchID;
		private $Dated;
		private $Note;
		private $IncidentReport;
		private $Importance;
		private $Created;
		private $Creator;
		private $SecurityLevel; // and below - The lower the number the higher the security level
		private $Deleted;
		
		public function GetNoteID()
		{
			return $this->NoteID;
		}
		
		public function SetNoteID($NoteID)
		{
			$this->NoteID = $NoteID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetRegionID()
		{
			return $this->RegionID;
		}
		
		public function SetRegionID($RegionID)
		{
			$this->RegionID = $RegionID;
		}
		
		public function GetBranchID()
		{
			return $this->BranchID;
		}
		
		public function SetBranchID($BranchID)
		{
			$this->BranchID = $BranchID;
		}
		
		public function GetDated()
		{
			return $this->Dated;
		}
		
		public function SetDated($Dated)
		{
			$this->Dated = $Dated;
		}
		
		public function GetNote()
		{
			return $this->Note;
		}
		
		public function SetNote($Note)
		{
			$this->Note = $Note;
		}
		
		public function GetIncidentReport()
		{
			return $this->IncidentReport;
		}
		
		public function SetIncidentReport($IncidentReport)
		{
			$this->IncidentReport = $IncidentReport;
		}
		
		public function GetImportance()
		{
			return $this->Importance;
		}
		
		public function SetImportance($Importance)
		{
			$this->Importance = $Importance;
		}
		
		public function GetImportanceName()
		{
			return getImportanceName($this->Importance);
		}
		
		public function GetCreated()
		{
			return $this->Created;
		}
		
		public function SetCreated($Created)
		{
			$this->Created = $Created;
		}
		
		public function GetCreator()
		{
			return $this->Creator;
		}
		
		public function SetCreator($Creator)
		{
			$this->Creator = $Creator;
		}
		
		public function GetSecurityLevel()
		{
			return $this->SecurityLevel;
		}
		
		public function SetSecurityLevel($SecurityLevel)
		{
			$this->SecurityLevel = $SecurityLevel;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public function GetGroupName()
		{
			return Group::LoadGroup($this->GroupID)->GetGroupName();
		}
		
		public function ViewedByUser($UserID)
		{
			return getNoteViewedByUser($this->NoteID,$UserID);
		}
		
		public function MarkViewedByUser($UserID)
		{
			if($this->ViewedByUser($UserID) == false)
			{
				return addViewedByUser($this->NoteID,$UserID);
			}
		}
		
		public function ViewedByUserToday($UserID)
		{
			$Viewed = getNoteViewedByUser($this->NoteID,$UserID);
			
			if( $Viewed != false )
			{
				$dt = new \DateTime($Viewed);
				return date("Ymd") == $dt->format("Ymd");
			}
		}
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public static function LoadNotesByGroupsArrayAndSecurityLevel($Groups,$SecurityLevel,$show_deleted)
		{
			if(count($Groups) == 0)
			{
				return array();
			} else {
				
				$InClause = '';
				
				for($i = 0; $i < count($Groups); $i++)
				{
					if( $i > 0 and $i < count($Groups) )
					{
						$InClause .= ',';
					}
					
					$InClause .= $Groups[$i]->GetGroupID();
					
				}
				
			}
			
			$arrGroupNotes = getGroupNotesByGroupIDsAndSecLevel($InClause,$SecurityLevel,$show_deleted);
			
			$thisGroupNotes = array();
						
			foreach( $arrGroupNotes As $Note )
			{
				$thisGroupNotes[] = Note::ArrToNote($Note);
			}
			
			return $thisGroupNotes;
		}
		
		public static function LoadGroupNotesByUserID($UserID,$show_deleted)
		{
			$arrGroupNotes = getGroupNotesByUserID($UserID,$show_deleted)->fetchAll();
			
			$thisGroupNotes = array();
						
			foreach( $arrGroupNotes As $Note )
			{
				$thisGroupNotes[] = Note::ArrToNote($Note);
			}
			
			return $thisGroupNotes;
		}
		
		public function Update($SafeDate,$Note,$IncidentReport,$Importance,$SecurityLevel)
		{
			updGroupNote($this->NoteID,$SafeDate,$Note,$IncidentReport,$Importance,$SecurityLevel);
			
			return Note::LoadNote($this->NoteID);
		}
		
		public static function LoadGroupNotesByDate($UserID,$SecurityLevel,$GroupID,$Date,$show_deleted = false)
		{
			$arrGroupNotes = getGroupNotesByDateAndSecurityLevel($UserID,$SecurityLevel,$GroupID,$Date,$show_deleted)->fetchAll();
			
			$thisGroupNotes = array();
						
			foreach( $arrGroupNotes As $Note )
			{
				$thisGroupNotes[] = Note::ArrToNote($Note);
			}
			
			return $thisGroupNotes;
		}
		
		public static function CreateGroupNote($GroupID,$Dated,$Note,$IncidentReport,$Importance,$Creator,$SecurityLevel) // need a different creator for each note type
		{
			$GroupNoteID = addGroupNote($GroupID,$Dated,$Note,$IncidentReport,$Importance,$Creator,$SecurityLevel);
						
			return Note::LoadNote($GroupNoteID);
		}
		
		public static function LoadNote($NoteID,$show_deleted = false)
		{
			$NoteID = intval($NoteID);
			
			$pdoNote = getNote($NoteID,$show_deleted);
			
			if($pdoNote->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Note::ArrToNote($pdoNote->fetch());
		}
		
		public static function ArrToNote($Item)
		{
			$thisNote = new Note();
			
			$thisNote->SetNoteID($Item['id_note']);
			$thisNote->SetGroupID($Item['fld_group_id']);
			$thisNote->SetRegionID($Item['fld_region_id']);
			$thisNote->SetBranchID($Item['fld_branch_id']);
			$thisNote->SetDated($Item['fld_date']);
			$thisNote->SetNote($Item['fld_note']);
			$thisNote->SetIncidentReport($Item['fld_incident_report']);
			$thisNote->SetImportance($Item['fld_importance']);
			$thisNote->SetCreated($Item['fld_created']);
			$thisNote->SetCreator($Item['fld_creator_id']);
			$thisNote->SetSecurityLevel($Item['fld_security_level']);
			$thisNote->SetDeleted($Item['fld_deleted']);
			
			
			return $thisNote;
		}
		
	} // END NOTE
	
	function addViewedByUser($NoteID,$UserID)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_notes_viewed(fld_note_id,fld_user_id) 
									  VALUES (:NoteID,:UserID) ');
			$qryInsert->execute(array(	
										':NoteID' => $NoteID,
										':UserID' => $UserID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getNoteViewedByUser($NoteID,$UserID)
	{
		global $dbh;
		
		try {
			
			$pdoViewed = $dbh->prepare('	
									SELECT fld_viewed_datetime
									FROM tbl_notes_viewed
									WHERE fld_note_id = :NoteID
									AND fld_user_id = :UserID
									');
			$pdoViewed->execute(array(
									':NoteID' => $NoteID,
									':UserID' => $UserID
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		if( $pdoViewed->rowCount() == 0 )
		{
			return false;
		} else {
			$arrViewed = $pdoViewed->fetch();
			
			return $arrViewed['fld_viewed_datetime'];
		}
		
	}
	
	function getImportanceName($ImportanceID)
	{
		global $dbh;
		
		try {
			
			$pdoImp = $dbh->prepare('	
									SELECT fld_importance
									FROM tbl_importance_values
									WHERE id_importance = :ImportanceID
									');
			$pdoImp->execute(array(':ImportanceID' => $ImportanceID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$arrImp = $pdoImp->fetch();
		
		return $arrImp['fld_importance'];
	}
	
	function getGroupNotesByGroupIDsAndSecLevel($InClause,$SecurityLevel,$show_deleted)
	{
		global $dbh;
		global $false;
		$show = ( $show_deleted ? '' : 'AND tbl_notes.fld_deleted = '.$false.' AND tbl_groups.fld_deleted = '.$false );
		
		try {
			
			$GroupNotes = $dbh->prepare('	
									SELECT *
									FROM tbl_notes
									JOIN tbl_groups
									ON tbl_notes.fld_group_id = tbl_groups.id_group
									WHERE tbl_notes.fld_security_level >= :SecurityLevel
									AND tbl_groups.id_group IN('.$InClause.')
									'.$show.'
									ORDER BY fld_date DESC
									');
			$GroupNotes->execute(array(	
										':SecurityLevel' => $SecurityLevel
										 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupNotes;
	}
	
	function getGroupNotesByUserID($UserID,$show_deleted)
	{
		global $dbh;
		global $false;
		$show = ( $show_deleted ? '' : 'AND tbl_notes.fld_deleted = '.$false.' AND tbl_groups.fld_deleted = '.$false );
		
		try {
			
			$GroupNotes = $dbh->prepare('	
									SELECT *
									FROM tbl_notes
									JOIN tbl_groups
									ON tbl_notes.fld_group_id = tbl_groups.id_group
									WHERE fld_creator_id = :UserID
									'.$show.'
									ORDER BY fld_created DESC
									');
			$GroupNotes->execute(array(	
										':UserID' => $UserID
										 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupNotes;
	}
	
	function getGroupNotesByDateAndSecurityLevel($UserID,$SecurityLevel,$GroupID,$Date,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$GroupNotes = $dbh->prepare('	
									SELECT *
									FROM tbl_notes
									WHERE fld_group_id = :GroupID
									AND fld_date = :Date
									AND (fld_security_level >= :SecurityLevel OR fld_creator_id = :UserID)
									'.$show.'
									ORDER BY fld_importance DESC
									');
			$GroupNotes->execute(array(	
										':GroupID' => $GroupID,
										':Date' => $Date,
										':SecurityLevel' => $SecurityLevel,
										':UserID' => $UserID
										 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupNotes;
	}
	
	function getNote($NoteID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Note = $dbh->prepare('	
									SELECT *
									FROM tbl_notes
									WHERE id_note = :NoteID
									'.$show.'
									');
			$Note->execute(array(':NoteID' => $NoteID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Note;
	}
	
	function addGroupNote($GroupID,$Dated,$Note,$IncidentReport,$Importance,$Creator,$SecurityLevel)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_notes(fld_group_id,fld_date,fld_note,fld_incident_report,fld_importance,fld_creator_id,fld_security_level) 
									  VALUES (:GroupID,:Dated,:Note,:IncidentReport,:Importance,:Creator,:SecurityLevel) ');
			$qryInsert->execute(array(	':GroupID' => $GroupID,
										':Dated' => ($Dated == 'null') ? null : $Dated,
										':Note' => $Note,
										':IncidentReport' => $IncidentReport,
										':Importance' => $Importance,
										':Creator' => $Creator,
										':SecurityLevel' => $SecurityLevel
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function updGroupNote($NoteID,$SafeDate,$Note,$IncidentReport,$Importance,$SecurityLevel)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_notes
										SET 
										fld_date = :SafeDate,
										fld_note = :Note,
										fld_incident_report = :IncidentReport,
										fld_importance = :Importance,
										fld_security_level = :SecurityLevel
										WHERE id_note = :NoteID
										');
			$qryUpdate->execute(array(
										
										':SafeDate' => $SafeDate,
								 		':Note' => $Note,
										':IncidentReport' => $IncidentReport,
										':Importance' => $Importance,
										':SecurityLevel' => $SecurityLevel,
										':NoteID' => $NoteID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	
	//END NOTE QUERY FUNCTIONS
	
	class GroupNoMeeting {
		private $GroupNoMeetingID;
		private $GroupID;
		private $Date;
		private $NoMeetingReasonID;
		private $Notes;
		private $Deleted;
		
		public function GetGroupNoMeetingID()
		{
			return $this->GroupNoMeetingID;
		}
		
		public function SetGroupNoMeetingID($GroupNoMeetingID)
		{
			$this->GroupNoMeetingID = $GroupNoMeetingID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetDate()
		{
			return $this->Date;
		}
		
		public function SetDate($Date)
		{
			$this->Date = $Date;
		}
		
		public function GetNoMeetingReasonID()
		{
			return $this->NoMeetingReasonID;
		}
		
		public function SetNoMeetingReasonID($NoMeetingReasonID)
		{
			$this->NoMeetingReasonID = $NoMeetingReasonID;
		}
		
		public function GetNotes()
		{
			return $this->Notes;
		}
		
		public function SetNotes($Notes)
		{
			$this->Notes = $Notes;
		}
		
		public function GeDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		
		public static function CreateGroupNoMeeting($GroupID,$Date,$NoMeetingReasonID,$Notes)
		{
			$GroupNoMeetingID = addGroupNoMeeting($GroupID,$Date,$NoMeetingReasonID,$Notes);
						
			return GroupNoMeeting::LoadGroupNoMeeting($GroupNoMeetingID);
		}
		
		public static function LoadGroupNoMeetingByGroupAndDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroup = getGroupNoMeetingByGroupAndDate($GroupID,$Date);
			
			if($pdoGroup->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupNoMeeting::ArrToGroupNoMeeting($pdoGroup->fetch());
		}
		
		public static function LoadGroupNoMeeting($GroupNoMeetingID)
		{
			$GroupNoMeetingID = intval($GroupNoMeetingID);
			
			$pdoGroup = getGroupNoMeeting($GroupNoMeetingID);
			
			if($pdoGroup->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupNoMeeting::ArrToGroupNoMeeting($pdoGroup->fetch());
		}
		
		public static function ArrToGroupNoMeeting($Item)
		{
			$thisGroupNoMeeting = new GroupNoMeeting();
			
			$thisGroupNoMeeting->SetGroupNoMeetingID($Item['id_no_meeting']);
			$thisGroupNoMeeting->SetGroupID($Item['fld_group_id']);
			$thisGroupNoMeeting->SetDate($Item['fld_group_date']);
			$thisGroupNoMeeting->SetNoMeetingReasonID($Item['fld_id_no_meeting_reason']);
			$thisGroupNoMeeting->SetNotes($Item['fld_notes']);
			$thisGroupNoMeeting->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupNoMeeting;
		}
		
		public function UpdateNoMeetingReason($ReasonID,$Notes)
		{
			$this->NoMeetingReasonID = $ReasonID;
			$this->Notes = $Notes;
			
			updNoMeetingInfo($this->GroupID,$this->Date,$this->NoMeetingReasonID,$this->Notes);
		}
		
		
	} // End Class Group No Meeting
	
	function updNoMeetingInfo($GroupID,$Date,$NoMeetingReasonID,$Notes)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_no_meetings_info
										SET 
										fld_id_no_meeting_reason = :NoMeetingReasonID,
										fld_notes = :Notes
										WHERE fld_group_id = :GroupID
										AND fld_group_date = :Date
										');
			$qryUpdate->execute(array(
										
										':NoMeetingReasonID' => $NoMeetingReasonID,
								 		':Notes' => $Notes,
										':GroupID' => $GroupID,
										':Date' => $Date
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function getGroupNoMeetingByGroupAndDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$GroupNoMeeting = $dbh->prepare('	
									SELECT *
									FROM tbl_no_meetings_info
									WHERE fld_group_id = :GroupID
									AND fld_group_date = :Date
									AND fld_deleted = 0;
									');
			$GroupNoMeeting->execute(array(
											':GroupID' => $GroupID,
											':Date' => ($Date == 'null') ? null : $Date
											
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupNoMeeting;
	}
	
	function getGroupNoMeeting($GroupNoMeetingID)
	{
		global $dbh;
		
		try {
			
			$GroupNoMeeting = $dbh->prepare('	
									SELECT *
									FROM tbl_no_meetings_info
									WHERE id_no_meeting = :GroupNoMeetingID
									');
			$GroupNoMeeting->execute(array(':GroupNoMeetingID' => $GroupNoMeetingID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupNoMeeting;
	}
	
	function addGroupNoMeeting($GroupID,$Date,$NoMeetingReasonID,$Notes,$Deleted)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_no_meetings_info(fld_group_id,fld_group_date,fld_id_no_meeting_reason,fld_notes) 
									  VALUES (:GroupID,:Date,:NoMeetingReasonID,:Notes) ');
			$qryInsert->execute(array(	':GroupID' => $GroupID, 
										':Date' => ($Date == 'null') ? null : $Date,
										':NoMeetingReasonID' => $NoMeetingReasonID,
										':Notes' => $Notes
										
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
		return $dbh->lastInsertId();
	}
	
	class GroupRecess {
		private $GroupRecessID;
		private $GroupID;
		private $StartDate;
		private $EndDate;
		private $Deleted;
		
		public function GetGroupRecessID()
		{
			return $this->GroupRecessID;
		}
		
		public function SetGroupRecessID($GroupRecessID)
		{
			$this->GroupRecessID = $GroupRecessID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function UpdateGroupRecess($StartDate,$EndDate)
		{
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updGroupRecess($this->GroupRecessID,$this->StartDate,$this->EndDate);
		}
		
		public static function CreateGroupRecess($GroupID,$StartDate,$EndDate)
		{
			$GroupRecessID = addGroupRecess($GroupID,$StartDate,$EndDate);
			
			return GroupRecess::LoadGroupRecess($GroupRecessID);
		}
		
		public static function LoadGroupsRecesses($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$thisGroupRecesses = array();
			
			$arrGroupsRecesses = getGroupsRecesses($GroupID)->fetchAll();
			
			foreach( $arrGroupsRecesses as $GroupRecess )
			{
				$thisGroupRecesses[] = GroupRecess::ArrToGroupRecess($GroupRecess);
			}
			
			return $thisGroupRecesses;
			
		}
		
		public static function ArrToGroupRecess($Item)
		{
			$thisGroupRecess = new GroupRecess();
			
			$thisGroupRecess->SetGroupRecessID($Item['id_recess']);
			$thisGroupRecess->SetGroupID($Item['fld_group_id']);
			$thisGroupRecess->SetStartDate($Item['fld_start_date']);
			$thisGroupRecess->SetEndDate($Item['fld_end_date']);
			$thisGroupRecess->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupRecess;
		}
		
		public static function LoadGroupRecess($GroupRecessID)
		{
			$GroupRecessID = intval($GroupRecessID);
			
			$pdoGroupRecess = getGroupRecess($GroupRecessID);
			
			if($pdoGroupRecess->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupRecess::ArrToGroupRecess($pdoGroupRecess->fetch());
		}
		
	} // End Group Recess Class

function updGroupRecess($GroupRecessID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_group_recess
										SET 
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_recess = :GroupRecessID
										');
			$qryUpdate->execute(array(
										
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupRecessID' => $GroupRecessID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	

function addGroupRecess($GroupID,$StartDate,$EndDate)
{
	global $dbh;

	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_group_recess(fld_group_id,fld_start_date,fld_end_date) 
								  VALUES (:GroupID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
									':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}

	return $dbh->lastInsertId();
}

function getGroupsRecesses($GroupID)
{
	global $dbh;
	
	try {
		
		$GroupRecesses = $dbh->prepare('	
								SELECT *
								FROM tbl_group_recess
								WHERE fld_group_id = :GroupID
								ORDER BY fld_start_date DESC;
								');
		$GroupRecesses->execute(array(':GroupID' => $GroupID ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $GroupRecesses;
}

function getGroupRecess($GroupRecessID)
{
	global $dbh;
	
	try {
		
		$GroupRecess = $dbh->prepare('	
								SELECT *
								FROM tbl_group_recess
								WHERE id_recess = :GroupRecessID
								');
		$GroupRecess->execute(array(':GroupRecessID' => $GroupRecessID ));
		
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $GroupRecess;
}
	
	class GroupType {
		
		private $GroupTypeID;
		private $GroupTypeName;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupTypeID()
		{
			return $this->GroupTypeID;
		}
		
		public function SetGroupTypeID($GroupTypeID)
		{
			$this->GroupTypeID = $GroupTypeID;
		}
		
		public function GetGroupTypeName()
		{
			return $this->GroupTypeName;
		}
		
		public function SetGroupTypeName($GroupTypeName)
		{
			$this->GroupTypeName = $GroupTypeName;
		}
		
		public static function ArrToGroupType($Item)
		{
			$thisGroupType = new GroupType();
			
			$thisGroupType->SetGroupTypeID($Item['id_group_type']);
			$thisGroupType->SetGroupTypeName($Item['fld_group_type']);
			
			return $thisGroupType;
		}
		
		public static function LoadGroupTypes()
		{
			$arrGroupTypes = getAllGroupTypes()->fetchAll();
			
			$ThisGroupTypes = array();
						
			foreach( $arrGroupTypes As $GroupType )
			{
				$ThisGroupTypes[] = GroupType::ArrToGroupType($GroupType);
			}
			
			return $ThisGroupTypes;
			
		}
		
		public static function LoadGroupType($GroupTypeID)
		{
			$GroupTypeID = intval($GroupTypeID);
			
			$pdoGroupType = getGroupType($GroupTypeID);
			
			if($pdoGroupType->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupType::ArrToGroupType($pdoGroupType->fetch());
		}
		
		public static function CreateGroupType($GroupTypeName)
		{
			$GroupTypeID = addGroupType($GroupTypeName);
			
			return GroupType::LoadGroupType($GroupTypeID);
		}
		
		public function UpdateGroupType($GroupTypeName)
		{
			$this->GroupTypeName = $GroupTypeName;
			
			updGroupType($this->GroupTypeID,$this->GroupTypeName);
			
		}
		
	} // End Group Type Class
	
	function updGroupType($GroupTypeID,$GroupTypeName)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_group_types
										SET fld_group_type = :GroupTypeName
										WHERE id_group_type = :GroupTypeID
										');
			$qryUpdate->execute(array(
										':GroupTypeName' => $GroupTypeName,
										':GroupTypeID' => $GroupTypeID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addGroupType($GroupTypeName)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_group_types(fld_group_type) 
								  VALUES (:GroupTypeName) ');
		$qryInsert->execute(array(	':GroupTypeName' => $GroupTypeName
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function getGroupType($GroupTypeID)
	{
		global $dbh;
		
		try {
			
			$GroupType = $dbh->prepare('	
									SELECT *
									FROM tbl_group_types
									WHERE id_group_type = :GroupTypeID
									');
			$GroupType->execute(array(':GroupTypeID' => $GroupTypeID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupType;
	}
	
	function getAllGroupTypes()
	{
		global $dbh;
		
		try {
			$GroupTypes = $dbh->query('SELECT tbl_group_types.*
									FROM tbl_group_types
									ORDER BY fld_group_type DESC
									');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupTypes;
	}
	
	class Region {
		
		private $RegionID;
		private $BranchID;
		private $RegionName;
		private $StartDate;
		private $EndDate;
		private $Branch;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetRegionID()
		{
			return $this->RegionID;
		}
		
		public function SetRegionID($RegionID)
		{
			$this->RegionID = $RegionID;
		}
		
		public function GetBranchID()
		{
			return $this->BranchID;
		}
		
		public function SetBranchID($BranchID)
		{
			$this->BranchID = $BranchID;
		}
		
		public function GetRegionName()
		{
			return $this->RegionName;
		}
		
		public function SetRegionName($RegionName)
		{
			$this->RegionName = $RegionName;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function GetBranch()
		{
			return $this->Branch;
		}
		
		public function SetBranch($BranchID)
		{
			$this->Branch = Branch::LoadBranch($BranchID);
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		
		public function LoadTrendStats($StartDate,$EndDate)
		{
			
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$GeneralMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'General');
			$ClosedMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'Closed');
			$SpecialMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'Special');
			
			$OtherMeetings = $this->TotalMeetingsByMonthDatesAndTypeOther($StartDate,$EndDate);
			
			
			$FirstTimers = $this->TotalFirstTimersByMonthDates($StartDate,$EndDate);
			$CommunityObservers = $this->TotalCommunityObserversByMonthDates($StartDate,$EndDate);
			$CommittedGrowers = $this->TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorders = $this->TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate);
			$NewCommitted =$this->TotalNewCommittedByMonthDates($StartDate,$EndDate);
			$CGLapsed = $this->TotalCommittedLapsedByMonthDatesOptimised($StartDate,$EndDate); //Doesn't count Org And Rec yet
			
			$CommittedGrowAtt = $this->TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAtt = $this->TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate);
			
			$CommittedGrowAttAvg = $this->TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAttAvg = $this->TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			
			$GroupsWithOrganiser = $this->TotalGroupsWithOrganiserByMonth($StartDate,$EndDate);
			$GroupsWithRecorder = $this->TotalGroupsWithRecorderByMonth($StartDate,$EndDate);
			
			$GroupsFormed =  $this->TotalFormedGroupsByMonth($StartDate,$EndDate);
			$FieldwWorkerAtt = $this->TotalFieldWorkerAttendanceByRegion($StartDate,$EndDate);
			
			/*
			$CommittedGrowers = 
			$OrgAndRecorders = $this->TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate);
			$NewCommitted = $this->TotalNewCommittedByMonthDates($StartDate,$EndDate);
			$CGLapsed = $this->TotalCommittedLapsedByMonthDatesOptimised($StartDate,$EndDate); //Doesn't count Org And Rec yet
			$CommittedGrowAtt = $this->TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAtt = $this->TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate);
			$CommittedGrowAttAvg = $this->TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAttAvg = $this->TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			$GroupsWithOrganiser = $this->TotalGroupsWithOrganiserByMonth($StartDate,$EndDate);
			$GroupsWithRecorder = $this->TotalGroupsWithRecorderByMonth($StartDate,$EndDate);
			$GroupsFormed = $this->TotalFormedGroupsByMonth($StartDate,$EndDate);
			$FieldwWorkerAtt = $this->TotalFieldWorkerAttendanceByRegion($StartDate,$EndDate);
			*/
			
			$combined_stats = array();
			
			$GMIDX = 0;
			$CLIDX = 0;
			$SPCIDX = 0;
			$OTIDX = 0;
			$FTIDX = 0;
			$COIDX = 0;
			$CGIDX = 0;
			$ORIDX = 0;
			$NCIDX = 0;
			$CGLIDX = 0;
			$CGAIDX = 0;
			$ORAIDX = 0;
			$CGAAIDX = 0;
			$ORAAIDX = 0;
			$GWOIDX = 0;
			$GWRIDX = 0;
			$GFIDX = 0;
			$FWAIDX = 0;
			
			for( $i = 0; $i < count($Dates); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$Date_Code = date_format($Dates[$i], 'Yn');
				
				if( $i < count($GeneralMeetings) and $Date_Code == $GeneralMeetings[$GMIDX]['this_year'].$GeneralMeetings[$GMIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$GMMonthCheck = $GeneralMeetings[$GMIDX]['this_year'].' '.$GeneralMeetings[$GMIDX]['this_month'];
					$GMValue = $GeneralMeetings[$GMIDX]['Total'];
					
					$GMIDX++;
					
				}
				else
				{
					$GMMonthCheck = date_format($Dates[$i], 'Y F');
					$GMValue = 0;
				}
				
				if( $i < count($ClosedMeetings) and $Date_Code == $ClosedMeetings[$CLIDX]['this_year'].$ClosedMeetings[$CLIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$CLMonthCheck = $ClosedMeetings[$CLIDX]['this_year'].' '.$ClosedMeetings[$CLIDX]['this_month'];
					$CLValue = $ClosedMeetings[$CLIDX]['Total'];
					
					$CLIDX++;
					
				}
				else
				{
					$CLMonthCheck = date_format($Dates[$i], 'Y F');
					$CLValue = 0;
				}
				
				if( $i < count($SpecialMeetings) and $Date_Code == $SpecialMeetings[$SPCIDX]['this_year'].$SpecialMeetings[$SPCIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$SPCMonthCheck = $SpecialMeetings[$SPCIDX]['this_year'].' '.$SpecialMeetings[$SPCIDX]['this_month'];
					$SPCValue = $SpecialMeetings[$SPCIDX]['Total'];
					
					$SPCIDX++;
					
				}
				else
				{
					$SPCMonthCheck = date_format($Dates[$i], 'Y F');
					$SPCValue = 0;
				}
				
				if( $i < count($OtherMeetings) and $Date_Code == $OtherMeetings[$OTIDX]['this_year'].$OtherMeetings[$OTIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$OTMonthCheck = $OtherMeetings[$OTIDX]['this_year'].' '.$OtherMeetings[$OTIDX]['this_month'];
					$OTValue = $OtherMeetings[$OTIDX]['Total'];
					
					$OTIDX++;
					
				}
				else
				{
					$OTMonthCheck = date_format($Dates[$i], 'Y F');
					$OTValue = 0;
				}
				
				if( $i < count($FirstTimers) and $Date_Code == $FirstTimers[$FTIDX]['this_year'].$FirstTimers[$FTIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$FTMonthCheck = $FirstTimers[$FTIDX]['this_year'].' '.$FirstTimers[$FTIDX]['this_month'];
					$FTValue = $FirstTimers[$FTIDX]['Total'];
					
					$FTIDX++;
					
				}
				else
				{
					$FTMonthCheck = date_format($Dates[$i], 'Y F');
					$FTValue = 0;
				}
				
				if( $i < count($CommunityObservers) and $Date_Code == $CommunityObservers[$COIDX]['this_year'].$CommunityObservers[$COIDX]['month_no'])
				{
					//'i:'.$i.' GMIDX:'.$GMIDX.' '.
					$COMonthCheck = $CommunityObservers[$COIDX]['this_year'].' '.$CommunityObservers[$COIDX]['this_month'];
					$COValue = $CommunityObservers[$COIDX]['Total'];
					
					$COIDX++;
					
				}
				else
				{
					$COMonthCheck = date_format($Dates[$i], 'Y F');
					$COValue = 0;
				}
				
				if( $i < count($CommittedGrowers) and $Date_Code == $CommittedGrowers[$CGIDX]['this_year'].$CommittedGrowers[$CGIDX]['month_no'])
				{
					
					$CGMonthCheck = $CommittedGrowers[$CGIDX]['this_year'].' '.$CommittedGrowers[$CGIDX]['this_month'];
					$CGValue = $CommittedGrowers[$CGIDX]['Total'];
					
					$CGIDX++;
					
				}
				else
				{
					$CGMonthCheck = date_format($Dates[$i], 'Y F');
					$CGValue = 0;
				}
				
				if( $i < count($OrgAndRecorders) and $Date_Code == $OrgAndRecorders[$ORIDX]['this_year'].$OrgAndRecorders[$ORIDX]['month_no'])
				{
					
					$ORMonthCheck = $OrgAndRecorders[$ORIDX]['this_year'].' '.$OrgAndRecorders[$ORIDX]['this_month'];
					$ORValue = $OrgAndRecorders[$ORIDX]['Total'];
					
					$ORIDX++;
					
				}
				else
				{
					$ORMonthCheck = date_format($Dates[$i], 'Y F');
					$ORValue = 0;
				}
				
				if( $i < count($NewCommitted) and $Date_Code == $NewCommitted[$NCIDX]['this_year'].$NewCommitted[$NCIDX]['month_no'])
				{
					
					$NCMonthCheck = $NewCommitted[$NCIDX]['this_year'].' '.$NewCommitted[$NCIDX]['this_month'];
					$NCValue = $NewCommitted[$NCIDX]['Total'];
					
					$NCIDX++;
					
				}
				else
				{
					$NCMonthCheck = date_format($Dates[$i], 'Y F');
					$NCValue = 0;
				}
				
				if( $i < count($CGLapsed) and $Date_Code == $CGLapsed[$CGLIDX]['this_year'].$CGLapsed[$CGLIDX]['month_no'])
				{
					
					$CGLMonthCheck = $CGLapsed[$CGLIDX]['this_year'].' '.$CGLapsed[$CGLIDX]['this_month'];
					$CGLValue = $CGLapsed[$CGLIDX]['Total'];
					
					$CGLIDX++;
					
				}
				else
				{
					$CGLMonthCheck = date_format($Dates[$i], 'Y F');
					$CGLValue = 0;
				}
				
				if( $i < count($CommittedGrowAtt) and $Date_Code == $CommittedGrowAtt[$CGAIDX]['this_year'].$CommittedGrowAtt[$CGAIDX]['month_no'])
				{
					
					$CGAMonthCheck = $CommittedGrowAtt[$CGAIDX]['this_year'].' '.$CommittedGrowAtt[$CGAIDX]['this_month'];
					$CGAValue = $CommittedGrowAtt[$CGAIDX]['Total'];
					
					$CGAIDX++;
					
				}
				else
				{
					$CGAMonthCheck = date_format($Dates[$i], 'Y F');
					$CGAValue = 0;
				}
				
				if( $i < count($OrgAndRecorderAtt) and $Date_Code == $OrgAndRecorderAtt[$ORAIDX]['this_year'].$OrgAndRecorderAtt[$ORAIDX]['month_no'])
				{
					
					$ORAMonthCheck = $OrgAndRecorderAtt[$ORAIDX]['this_year'].' '.$OrgAndRecorderAtt[$ORAIDX]['this_month'];
					$ORAValue = $OrgAndRecorderAtt[$ORAIDX]['Total'];
					
					$ORAIDX++;
					
				}
				else
				{
					$ORAMonthCheck = date_format($Dates[$i], 'Y F');
					$ORAValue = 0;
				}
				
				if( $i < count($CommittedGrowAttAvg) and $Date_Code == $CommittedGrowAttAvg[$CGAAIDX]['this_year'].$CommittedGrowAttAvg[$CGAAIDX]['month_no'])
				{
					
					$CGAAMonthCheck = $CommittedGrowAttAvg[$CGAAIDX]['this_year'].' '.$CommittedGrowAttAvg[$CGAAIDX]['this_month'];
					$CGAAValue = $CommittedGrowAttAvg[$CGAAIDX]['Total'];
					
					$CGAAIDX++;
					
				}
				else
				{
					$CGAAMonthCheck = date_format($Dates[$i], 'Y F');
					$CGAAValue = 0;
				}
				
				if( $i < count($OrgAndRecorderAttAvg) and $Date_Code == $OrgAndRecorderAttAvg[$ORAAIDX]['this_year'].$OrgAndRecorderAttAvg[$ORAAIDX]['month_no'])
				{
					
					$ORAAMonthCheck = $OrgAndRecorderAttAvg[$ORAAIDX]['this_year'].' '.$OrgAndRecorderAttAvg[$ORAAIDX]['this_month'];
					$ORAAValue = $OrgAndRecorderAttAvg[$ORAAIDX]['Total'];
					
					$ORAAIDX++;
					
				}
				else
				{
					$ORAAMonthCheck = date_format($Dates[$i], 'Y F');
					$ORAAValue = 0;
				}
				
				if( $i < count($GroupsWithOrganiser) and $Date_Code == $GroupsWithOrganiser[$GWOIDX]['this_year'].$GroupsWithOrganiser[$GWOIDX]['month_no'])
				{
					
					$GWOMonthCheck = $GroupsWithOrganiser[$GWOIDX]['this_year'].' '.$GroupsWithOrganiser[$GWOIDX]['this_month'];
					$GWOValue = $GroupsWithOrganiser[$GWOIDX]['Total'];
					
					$GWOIDX++;
					
				}
				else
				{
					$GWOMonthCheck = date_format($Dates[$i], 'Y F');
					$GWOValue = 0;
				}
				
				if( $i < count($GroupsWithRecorder) and $Date_Code == $GroupsWithRecorder[$GWRIDX]['this_year'].$GroupsWithRecorder[$GWRIDX]['month_no'])
				{
					
					$GWRMonthCheck = $GroupsWithRecorder[$GWRIDX]['this_year'].' '.$GroupsWithRecorder[$GWRIDX]['this_month'];
					$GWRValue = $GroupsWithRecorder[$GWRIDX]['Total'];
					
					$GWRIDX++;
					
				}
				else
				{
					$GWRMonthCheck = date_format($Dates[$i], 'Y F');
					$GWRValue = 0;
				}
				
				//GroupsFormed
				
				if( $i < count($GroupsFormed) and $Date_Code == $GroupsFormed[$GFIDX]['this_year'].$GroupsFormed[$GFIDX]['month_no'])
				{
					
					$GFMonthCheck = $GroupsFormed[$GFIDX]['this_year'].' '.$GroupsFormed[$GFIDX]['this_month'];
					$GFValue = $GroupsFormed[$GFIDX]['Total'];
					
					$GFIDX++;
					
				}
				else
				{
					$GFMonthCheck = date_format($Dates[$i], 'Y F');
					$GFValue = 0;
				} //FieldwWorkerAtt
				
				if( $i < count($FieldwWorkerAtt) and $Date_Code == $FieldwWorkerAtt[$FWAIDX]['this_year'].$FieldwWorkerAtt[$FWAIDX]['month_no'])
				{
					
					$FWAMonthCheck = $FieldwWorkerAtt[$FWAIDX]['this_year'].' '.$FieldwWorkerAtt[$FWAIDX]['this_month'];
					$FWAValue = $FieldwWorkerAtt[$FWAIDX]['Total'];
					
					$FWAIDX++;
					
				}
				else
				{
					$FWAMonthCheck = date_format($Dates[$i], 'Y F');
					$FWAValue = 0;
				} 
				
				$combined_stats[$i] = array(
											"Name" => date_format($Dates[$i],"F Y"),
											//"MAName" => $GMMonthCheck,
											"MA" => $GMValue,
											//"CLName" => $CLMonthCheck,
											"CL" => $CLValue,
											//"SPCName" => $SPCMonthCheck,
											"SPC" => $SPCValue,
											//"OTName" => $OTMonthCheck,
											"OT" => $OTValue,
											//"FTName" => $FTMonthCheck,
											"FT" => $FTValue,
											//"COName" => $COMonthCheck,
											"CO" => $COValue,
											//"CGName" => $CGMonthCheck,
											"CG" => $CGValue + $ORValue,
											//"NCName" => $NCMonthCheck,
											"NC" => $NCValue,
											//"CGLName" => $CGLMonthCheck,
											"CGL" => $CGLValue,
											//"CGAName" => $CGAMonthCheck,
											"CGA" => $CGAValue + $ORAValue,
											//"CGAAName" => $CGAAMonthCheck,
											"CGAA" => $CGAAValue + $ORAAValue,
											//"GWOName" => $GWOMonthCheck,
											"GWO" => $GWOValue,
											//"GWRName" => $GWRMonthCheck,
											"GWR" => $GWRValue,
											//"GFName" => $GFMonthCheck,
											"GF" => $GFValue,
											//"FWAName" => $FWAMonthCheck,
											"FWA" => $FWAValue
											);
			}
			
			return $combined_stats;
		}
		
		public function LoadGroupAttendanceStats($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByRegion($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByRegion($StartDate,$EndDate);
			
			$MemberAttendances = $this->CountMemberAttendancesInPeriodByRegion($StartDate,$EndDate);
			$OrganiserAttendances = $this->CountVolunteerAttendancesInPeriodByRegionRole(ORGANISER,$StartDate,$EndDate);
			$RecorderAttendances = $this->CountVolunteerAttendancesInPeriodByRegionRole(RECORDER,$StartDate,$EndDate);
			$SponsorAttendances = $this->CountVolunteerAttendancesInPeriodByRegionRole(SPONSOR,$StartDate,$EndDate);
			
			$CommunityObservers = $this->CountCommunityObserversInPeriodByRegion($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"MemName" => $MemberAttendances[$i]['Name'], 
											"Mem" => $MemberAttendances[$i]['Count'],
											
											"OrgName" => $OrganiserAttendances[$i]['Name'], 
											"Org" => $OrganiserAttendances[$i]['Count'],
											
											"RecName" => $RecorderAttendances[$i]['Name'], 
											"Rec" => $RecorderAttendances[$i]['Count'],
											
											"SpoName" => $SponsorAttendances[$i]['Name'], 
											"Spo" => $SponsorAttendances[$i]['Count'],
											
											"ComName" => $CommunityObservers[$i]['Name'], 
											"Com" => $CommunityObservers[$i]['Count']
											

											);
			}
			
			return $combined_stats;
		}
		
		public function LoadStatisticsByRegion($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByRegion($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByRegion($StartDate,$EndDate);
			
			$FTGrowers = $this->CountFirstTimerGrowersByRegionBetweenDates($StartDate,$EndDate);
			$FTGrowersCont = $this->CountFirstTimerGrowersContByRegionBetweenDates(2,$StartDate,$EndDate);
			$NCGrowers = $this->CountNewCommittedGrowersByRegionBetweenDates($StartDate,$EndDate);
			$LCGrowers = $this->CountLapsedCommittedGrowersByRegionBetweenDates(3,$StartDate,$EndDate);
			$AEPCGrowers = $this->CountComGrowAttendeesAtEndByRegion($StartDate,$EndDate);
			
			$FWAttendees = $this->CountFieldWorkerAttendancesInPeriodByRegion($StartDate,$EndDate);
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"FTGrow" => $FTGrowers[$i]['Name'], 
											"FTG" => $FTGrowers[$i]['Count'],
											
											"FTCGrow" => $FTGrowersCont[$i]['Name'], 
											"FTCG" => $FTGrowersCont[$i]['Count'],
											
											"NCGrow" => $NCGrowers[$i]['Name'], 
											"NCG" => $NCGrowers[$i]['Count'],
											
											"LCGrow" => $LCGrowers[$i]['Name'], 
											"LCG" => $LCGrowers[$i]['Count'],
											
											"AEPCGrow" => $AEPCGrowers[$i]['Name'], 
											"AEPCG" => $AEPCGrowers[$i]['Count'],
											
											"FWAttName" => $FWAttendees[$i]['Name'], 
											"FWAtt" => $FWAttendees[$i]['Count'],
											

											);
			}
			
			return $combined_stats;
		}
		
		public function LoadRegionVolunteerLabels($StartDate,$EndDate)
		{
			return getVolunteerLabelsByRegionDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		
		public function LoadGroupStatsImproved($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByRegion($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByRegion($StartDate,$EndDate);
			$MemberAttendees = $this->CountMemberAttendeesInPeriodByRegion($StartDate,$EndDate);
			$OrganiserAttendees = $this->CountVolunteerAttendeesInPeriodByRegionRole(ORGANISER,$StartDate,$EndDate);
			$RecorderAttendees = $this->CountVolunteerAttendeesInPeriodByRegionRole(RECORDER,$StartDate,$EndDate);
			$SponsorAttendees = $this->CountVolunteerAttendeesInPeriodByRegionRole(SPONSOR,$StartDate,$EndDate);
			$CommunityObservers = $this->CountCommunityObserversInPeriodByRegion($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"MemName" => $MemberAttendees[$i]['Name'], 
											"Mem" => $MemberAttendees[$i]['Count'],
											
											"OrgName" => $OrganiserAttendees[$i]['Name'], 
											"Org" => $OrganiserAttendees[$i]['Count'],
											
											"RecName" => $RecorderAttendees[$i]['Name'], 
											"Rec" => $RecorderAttendees[$i]['Count'],
											
											"SpoName" => $SponsorAttendees[$i]['Name'], 
											"Spo" => $SponsorAttendees[$i]['Count'],
											
											"ComName" => $CommunityObservers[$i]['Name'], 
											"Com" => $CommunityObservers[$i]['Count']
											

											);
			}
			
			return $combined_stats;
		}
		
		public function LoadGroupStats($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByRegion($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByRegion($StartDate,$EndDate);
			
			$FirstTimers = $this->CountFirstTimersInPeriodByRegion($StartDate,$EndDate);
			
			$CommunityObservers = $this->CountCommunityObserversInPeriodByRegion($StartDate,$EndDate);
			$CommittedGrowers = $this->CountCommittedGrowersInPeriodByRegion($StartDate,$EndDate);
			$NewCommittedGrowers = $this->CountNewCommittedGrowersInPeriodByRegion($StartDate,$EndDate);
			$NSinceLastAttended = $this->CountNSinceLastAttendedByRegion(8,$StartDate,$EndDate);
			$CGAttendances = $this->CountCommittedGrowerAttendancesByRegion($StartDate,$EndDate);
			$OrgAttendances = $this->CountOrganiserAttendancesInPeriodByRegion($StartDate,$EndDate);
			$RecAttendances = $this->CountRecorderAttendancesInPeriodByRegion($StartDate,$EndDate);
			$FWAttendances = $this->CountFieldWorkerAttendancesInPeriodByRegion($StartDate,$EndDate);
			
			$TotAttees = $this->CountTotalAttendeesInPeriodByRegion($StartDate,$EndDate);
			$TotAtes = $this->CountTotalAttendancesInPeriodByRegion($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											"TFTName" => $FirstTimers[$i]['Name'], 
											"TFT" => $FirstTimers[$i]['Count'],
											"ComObsName" => $CommunityObservers[$i]['Name'], 
											"ComObs" => $CommunityObservers[$i]['Count'],
											"ComGrowName" => $CommittedGrowers[$i]['Name'], 
											"ComGrow" => $CommittedGrowers[$i]['Count'],
											"NewComGrowName" => $NewCommittedGrowers[$i]['Name'], 
											"NewComGrow" => $NewCommittedGrowers[$i]['Count'],
											"CGLapsedName" => $NSinceLastAttended[$i]['Name'], 
											"CGLapsed" => $NSinceLastAttended[$i]['Count'],
											"CGAttendancesName" => $CGAttendances[$i]['Name'], 
											"CGAttendances" => $CGAttendances[$i]['Count'],
											"OrgAttName" => $OrgAttendances[$i]['Name'], 
											"OrgAtt" => $OrgAttendances[$i]['Count'],
											"RecAttName" => $RecAttendances[$i]['Name'], 
											"RecAtt" => $RecAttendances[$i]['Count'],
											"FWAttName" => $FWAttendances[$i]['Name'], 
											"FWAtt" => $FWAttendances[$i]['Count'],
											"TotAtteesName" => $TotAttees[$i]['Name'], 
											"TotAttees" => $TotAttees[$i]['Count'],
											"TotAtesName" => $TotAtes[$i]['Name'], 
											"TotAtes" => $TotAtes[$i]['Count']

											);
			}
			
			return $combined_stats;
		}
		
		public function CountTotalAttendancesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->TotalAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountTotalAttendeesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->TotalAttendeesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountFieldWorkerAttendeesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->FieldWorkerAttendeesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountFieldWorkerAttendancesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->FieldWorkerAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountRecorderAttendancesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->RecorderAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountOrganiserAttendancesInPeriodByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->OrganiserAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountCommittedGrowerAttendancesByRegion($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CommittedGrowerAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountNSinceLastAttendedByRegion($NoMeetings,$StartDate,$EndDate)
		{
			
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountNSinceLastAttended($NoMeetings,$StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountNewCommittedGrowersInPeriodByRegion($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrNewCom = getCountNewCommittedGrowersInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrNewCom as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['new_com']);
				
				$i++;
			}
			
			return $index_array;
		}
				
		public function CountCommittedGrowersInPeriodByRegion($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrComGrow = getCountCommittedGrowersInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrComGrow as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['com_grow'] + $group['org_rec']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountCommunityObserversInPeriodByRegion($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrComObs = getAttendanceForCommunityObserversByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrComObs as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['com_obs']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountFirstTimersInPeriodByRegion($StartDate,$EndDate)
		{
			$arrFirstTimers = getCountFirstTimersInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['first_timers']);
				
				$i++;
			}
			
			return $index_array;
			
		}
		
		//this only counts scheduled meetings that actually have attendance.
		public function CountMeetingsAttendedInPeriodByRegion($StartDate,$EndDate)
		{
			$arrCountMeetings = getCountMeetingsAttendedInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrCountMeetings as $meeting )
			{
				$index_array[$i] = array("Name" => $meeting['fld_group_name'], "Meetings" => $meeting['total_meetings']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountMemberAttendancesInPeriodByRegion($StartDate,$EndDate)
		{
			return \Membership\Member::CountMemberAttendancesByRegionAttendance($this->RegionID,$StartDate,$EndDate);
			
		}
		
		public function CountMemberAttendeesInPeriodByRegion($StartDate,$EndDate)
		{
			return \Membership\Member::CountMembersByRegionAttendance($this->RegionID,$StartDate,$EndDate);
			
		} 
		
		public function CountVolunteerAttendancesInPeriodByRegionRole($Role,$StartDate,$EndDate)
		{
			return \Membership\Staff::CountVolunteerAttendancesByRegionAttendance($Role,$this->RegionID,$StartDate,$EndDate);
			
		}
		
		public function CountVolunteerAttendeesInPeriodByRegionRole($Role,$StartDate,$EndDate)
		{
			return \Membership\Staff::CountVolunteersByRegionAttendance($Role,$this->RegionID,$StartDate,$EndDate);
			
		}
		
		public function CountFirstTimerGrowersContByRegionBetweenDates($AttendedTimes,$StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				
				$result = $group->CountFirstTimerMultipleAttendancesByGroupDates($StartDate,$EndDate,$AttendedTimes);
				
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $result['Total_Aees']);
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountComGrowAttendeesAtEndByRegion($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountComGrowAttAtEnd($EndDate));
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountLapsedCommittedGrowersByRegionBetweenDates($WksLapsed,$StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountNSinceLastAttendance($WksLapsed,$StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountNewCommittedGrowersByRegionBetweenDates($StartDate,$EndDate)
		{
			$arrFirstTimers = getNewCommittedGrowersByRegionBetweenDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['Total_Aees']);
				
				$i++;
			}
			
			return $index_array;

		}
				
		public function CountFirstTimerGrowersByRegionBetweenDates($StartDate,$EndDate)
		{
			$arrFirstTimers = getCountFirstTimerGrowersInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['first_timers']);
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountMeetingsScheduledInPeriodByRegion($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$MeetingsScheduled = array();
			
			$i = 0;
			
			foreach($Groups as $Group)
			{
				
				$MeetingsScheduled[$i] = array("Name" => $Group->GetGroupName(),"MeSch" => $Group->CountMeetingsScheduledInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $MeetingsScheduled;
		}
		
		public function CountFirstTimeAttendancesBetweenDatesByRegion($StartDate,$EndDate)
		{
			$Groups = $this->LoadsGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $Group )
			{
				$index_array[$i] = array("Name" => $Group->GetGroupName(), "Attendances" => count($Group->LoadFirstTimersBetweenDates($StartDate,$EndDate)));
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function getAllGroupsByDateRange($StartDate,$EndDate)
		{
			return Group::LoadGroupsByRegionPeriod($this->RegionID,$StartDate,$EndDate);
		}
		
		public function TotalFieldWorkerAttendanceByRegion($StartDate,$EndDate)
		{
			return countFieldWorkersByRegionByMonths($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function CountFieldWorkerAttendanceAllGroups($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->getAllGroupsByDateRange($StartDate,$EndDate);
			

			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				$Count += $Group->FieldWorkerAttendancesInPeriod($StartDate,$EndDate);
			}
			
			return $Count;
		}
		
		public function TotalGroupsWithRecorderByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountGroupsWithRecorderByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		public function TotalGroupsWithOrganiserByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountGroupsWithOrganiserByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		public function CountGroupsWithOrganiserByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->getAllGroupsByDateRange($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadOrganiser($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function CountGroupsWithRecorderByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->getAllGroupsByDateRange($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadRecorder($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function TotalFormedGroupsByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountFormedGroupsByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		public function CountFormedGroupsByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->getAllGroupsByDateRange($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadRecorder($StartDate,$EndDate) and $Group->HadOrganiser($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function CountCGLaspsedAttendances($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->getAllGroupsByDateRange($StartDate,$EndDate);
			
			$Total = 0;
			
			foreach( $AllGroups as $Group )
			{
				$Total += $Group->CountNSinceLastAttended(8,$StartDate,$EndDate);
			}
			
			return $Total;
			
		}
		
		public function CountNewCommittedGrowersInPeriod($thisDate)
		{
			//global $Organiser;
			//global $Recorder;
			
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
						
			$CountNewCommitted = count(\Membership\Member::LoadNewCommittedMembersByRegionByGroupAttendanceBetweenDates($this->RegionID,$StartDate,$EndDate)); //boolean for committed
			
			return $CountNewCommitted;
		}
		
		public function AverageCommittedGrowerAttendancesInPeriod($thisDate) //includes organiser and recorder
		{
			//global $Organiser;
			//global $Recorder;
			
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$arrOrganiser = getAvgStaffAttendancesInPeriodByRegion(ORGANISER,$this->RegionID,$StartDate,$EndDate)->fetch();
			$arrRecorder = getAvgStaffAttendancesInPeriodByRegion(RECORDER,$this->RegionID,$StartDate,$EndDate)->fetch();
			$arrCommitted = getAvgCommittedGrowersAttendancesInPeriodByRegion($this->RegionID,$StartDate,$EndDate)->fetch();
			
			$Users = ($arrOrganiser['Users'] + $arrRecorder['Users'] + $arrCommitted['Users']);
			$Attendances = ($arrOrganiser['Attendances'] + $arrRecorder['Attendances'] + $arrCommitted['Attendances']);
			
			if($Users == 0)
			{
				return 0;
			}
			
			return round($Attendances / $Users);
		}
				
		public function CommittedGrowerAttendancesInPeriod($thisDate) //includes organiser and recorder
		{
			//global $Organiser;
			//global $Recorder;
			
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$CountOrganiser = count(Attendance::LoadStaffAttendancesInPeriodByRegion(ORGANISER,$this->RegionID,$StartDate,$EndDate));
			$CountRecorder = count(Attendance::LoadStaffAttendancesInPeriodByRegion(RECORDER,$this->RegionID,$StartDate,$EndDate));
			$CountCommitted = count(Attendance::LoadCommittedGrowersAttendancesInPeriodByRegion($this->RegionID,$StartDate,$EndDate));
			
			return $CountOrganiser + $CountRecorder + $CountCommitted;
		}
		
		public function TotalCommittedLapsedByMonthDatesOptimised($Start,$End)
		{
			return count8SinceLastAttendedByRegion($this->RegionID,$Start,$End);
			
		}
		
		public function TotalCommittedLapsedByMonthDates($Start,$End)
		{
			global $dbh;
		
			$Dates = funGetMonthsBetweenDates($Start,$End);
				
			$Committed = array();
			
			foreach( $Dates as $Date )
			{
				
				$StartDate = date_format($Date,'Y-m-d');
				$EndDate = date_format($Date,'Y-m-t');
				
				$Committed[] = array( "Total" => $this->CountNSinceLastAttendedByRegion(8,$StartDate,$EndDate), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			
			
			return $Committed;
			
		}
		
		public function TotalNewCommittedByMonthDates($StartDate,$EndDate)
		{
			return countNewCommittedByMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecAttAvgInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate);
		}
		
		public function TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecAttInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowerAttAvgInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate);
		}
		
		public function TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowerAttInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowersInPeriodByMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalCommittedGrowersInPeriodByMonthYear($thisDate) //also needs to count organiser and recorder
		{
			//global $Organiser;
			//global $Recorder;
			
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
					
			$CountOrganiser = count(\Membership\Staff::LoadStaffByRegionAttendanceBetweenDates(ORGANISER,$this->RegionID,$StartDate,$EndDate));
			$CountRecorder = count(\Membership\Staff::LoadStaffByRegionAttendanceBetweenDates(RECORDER,$this->RegionID,$StartDate,$EndDate));
			
			$CountCommitted = count(\Membership\Member::LoadMemberByRegionAttendanceBetweenDates($this->RegionID,$StartDate,$EndDate,true)); //boolean for committed
			
			return $CountOrganiser + $CountRecorder + $CountCommitted;
		}
		
		public function TotalMeetingsByMonthYearAndTypeOther($thisDate)
		{
			return $this->TotalMeetingsByMonthYearAndType($thisDate,'Organiser &amp; Recorder') + $this->TotalMeetingsByMonthYearAndType($thisDate,'Leadership');
		}
				
		public function TotalCommunityObserversByMonthYear($thisDate)
		{
			$arrCountComObv = countAttendanceForCommunityObserversByRegionMonthYear($this->RegionID,$thisDate)->fetch();
			
			return $arrCountComObv['Total'];
		}
		
		public function TotalCommunityObserversByMonthDates($StartDate,$EndDate)
		{
			$arrCountComObv = countAttendanceForCommunityObserversByRegionMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountComObv;
		}
		
		public function TotalFirstTimersByMonthYear($thisDate)
		{
			$arrCountFirstTimers = countFirstTimerAttendancesByRegionMonthYear($this->RegionID,$thisDate)->fetch();
			
			return $arrCountFirstTimers['Total'];
		}
		
		public function TotalFirstTimersByMonthDates($StartDate,$EndDate)
		{
			$arrCountFirstTimers = countFirstTimerAttendancesByRegionMonthDates($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountFirstTimers;
		}
		
		public function TotalMeetingsByMonthYearAndType($thisDate,$GroupType)
		{
						
			$arrCountMeetings = countTotalMeetingsByRegionMonthYearAndType($this->RegionID,$thisDate,$GroupType)->fetch();
			
			return $arrCountMeetings['Total'];
		}
		
		public function TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,$GroupType)
		{
			$arrCountMeetings = countTotalMeetingsByMonthsRegionDatesAndType($this->RegionID,$StartDate,$EndDate,$GroupType)->fetchAll();
			
			return $arrCountMeetings;
		}
		
		public function TotalMeetingsByMonthDatesAndTypeOther($StartDate,$EndDate)
		{
			$arrCountMeetings = countTotalMeetingsByMonthsRegionDatesAndTypeOther($this->RegionID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountMeetings;
			
			//return $this->TotalMeetingsByMonthYearAndType($thisDate,'Organiser &amp; Recorder') + $this->TotalMeetingsByMonthYearAndType($thisDate,'Leadership');
		}
		
		public static function LoadRegion($RegionID)
		{
			$RegionID = intval($RegionID);
			
			$pdoRegion = getRegion($RegionID);
			
			if($pdoRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Region::ArrToRegions($pdoRegion->fetch());
		}
		
		public function LoadsGroupsByPeriod($StartDate,$EndDate) //Gets groups that fall into the region over the given period
		{
			return Group::LoadGroupsByRegionPeriod($this->RegionID,$StartDate,$EndDate);
		}
		
		public static function load_regions_by_branches($staff_id, $show_deleted = false)
		{
			$arr_regions = get_regions_by_branches($staff_id,$show_deleted)->fetchAll();
			
			$regions = array();
						
			foreach( $arr_regions As $region )
			{
				$regions[] = Region::ArrToRegions($region);
			}
			
			return $regions;
		}
		
		public static function LoadRegionsByBranchID($BranchID, $show_deleted = false)
		{
			$arrRegions = getRegionsByBranchID($BranchID,$show_deleted)->fetchAll();
			
			$ThisRegions = array();
						
			foreach( $arrRegions As $Region )
			{
				$ThisRegions[] = Region::ArrToRegions($Region);
			}
			
			return $ThisRegions;
		}
		
		public static function LoadRegions($show_deleted = false)
		{
			$arrRegions = getAllRegions($show_deleted)->fetchAll();
			
			$ThisRegions = array();
						
			foreach( $arrRegions As $Region )
			{
				$ThisRegions[] = Region::ArrToRegions($Region);
			}
			
			return $ThisRegions;
			
		}
		
		public static function ArrToRegions($Item)
		{
			$thisRegion = new Region();
			
			$thisRegion->SetRegionID($Item['id_region']);
			$thisRegion->SetBranchID($Item['fld_branch_id']);
			$thisRegion->SetBranch($Item['fld_branch_id']);
			$thisRegion->SetRegionName($Item['fld_region_name']);
			$thisRegion->SetStartDate($Item['fld_start_date']);
			$thisRegion->SetEndDate($Item['fld_end_date']);
			
			return $thisRegion;
		}
		
		public static function CreateRegion($RegionName,$BranchID,$StartDate,$EndDate)
		{
			$RegionID = addRegion($RegionName,$BranchID,$StartDate,$EndDate);
			
			return Region::LoadRegion($RegionID);
		}
		
		public function UpdateRegion($RegionName,$BranchID,$StartDate,$EndDate)
		{
			updRegion($this->RegionID,$RegionName,$BranchID,$StartDate,$EndDate);
			
		}
		
		public function Delete()
		{
			
			global $true;
			
			$this->Deleted = $true;
			
			setRegionDeleted($this->RegionID,$this->Deleted);
		}
				
		
	} // End Region Class
	
	function getVolunteerLabelsByRegionDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
					
		try {
			
			$Labels = $dbh->prepare("	
									SELECT tbl_staff.*, tbl_states.fld_state_name, tbl_states.fld_state_abbreviation
									FROM tbl_staff
									JOIN tbl_user_activity_dates
									ON tbl_staff.fld_user_id = tbl_user_activity_dates.fld_user_id
									JOIN tbl_staff_roles
									ON tbl_staff_roles.id_staff_role = tbl_user_activity_dates.fld_staff_type_id
									JOIN tbl_states
									ON tbl_staff.fld_state_id = tbl_states.id_state
									WHERE tbl_staff.fld_address != ''
									AND tbl_staff.fld_suburb != ''
									AND tbl_staff.fld_postcode != ''
									AND tbl_staff_roles.fld_staff_vol = :volunteer
									AND tbl_user_activity_dates.fld_start_date <= :EndDate
									AND ( tbl_user_activity_dates.fld_end_date IS NULL
										OR tbl_user_activity_dates.fld_end_date >= :StartDate
										)
									AND EXISTS(
												SELECT *
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
												AND tbl_groups_roles.fld_start_date <= :EndDate2
												AND ( tbl_groups_roles.fld_end_date IS NULL
													OR tbl_groups_roles.fld_end_date >= :StartDate2
													) 
												AND tbl_groups_roles.fld_deleted = 0
												AND fld_group_id IN(
																	SELECT fld_group_id
																	FROM tbl_groups_regions
																	WHERE fld_region_id = :RegionID
																	AND tbl_groups_regions.fld_deleted = 0
																	AND tbl_groups_regions.fld_start_date <= :EndDate3
																	AND ( tbl_groups_regions.fld_end_date IS NULL
																		OR tbl_groups_regions.fld_end_date >= :StartDate3
																		) 
																	)
												)
									
									");
			
				$Labels->execute(array(	
											':volunteer' => VOLUNTEER,
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':RegionID' => $RegionID,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Labels;
	}
	
	function countFieldWorkersByBranchByMonths($BranchID,$StartDate,$EndDate)
	{
		
		global $dbh;
					
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND	tbl_staff.fld_user_id IN(
												SELECT tbl_staffs_regions.fld_user_id
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_group_attendance.fld_group_id
												AND tbl_staffs_regions.fld_start_date <= tbl_group_attendance.fld_date
												AND ( tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= tbl_group_attendance.fld_date ) 
												AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
												AND ( tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date ) 
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									
									");
			
				$Attendances->execute(array(	
											':StartDate' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':BranchID' => $BranchID
											
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
		
	}
	
	
	function countFieldWorkersByRegionByMonths($RegionID,$StartDate,$EndDate)
	{
		
		global $dbh;
					
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND	tbl_staff.fld_user_id IN(
												SELECT tbl_staffs_regions.fld_user_id
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_group_attendance.fld_group_id
												AND tbl_staffs_regions.fld_start_date <= tbl_group_attendance.fld_date
												AND ( tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= tbl_group_attendance.fld_date ) 
												AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
												AND ( tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date ) 
												AND tbl_groups_regions.fld_region_id = :RegionID
												)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									
									");
			
				$Attendances->execute(array(	
											':StartDate' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':RegionID' => $RegionID
											
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
		
	}
	
	//assumes 8 meetings
	function count8SinceLastAttendedByBranch($BranchID,$Start,$End)
	{
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$LapsedCount = array();
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(:EndDate) AS this_year, MONTHNAME(:EndDate2) AS this_month, MONTH(:EndDate3) AS month_no
									FROM tbl_members
									WHERE tbl_members.fld_user_id NOT IN(
													SELECT tbl_group_attendance.fld_user_id
													FROM tbl_group_attendance
													JOIN tbl_groups
													ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
													JOIN tbl_groups_schedules
													ON tbl_groups.id_group = tbl_groups_schedules.fld_group_id
													JOIN tbl_groups_regions
													ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
													WHERE tbl_groups_regions.fld_deleted = 0
													AND tbl_groups.fld_non_group_type IS NULL
													AND tbl_groups_regions.fld_region_id IN(
																							SELECT id_region
																							FROM tbl_regions
																							WHERE fld_branch_id = :BranchID
																							AND fld_deleted = 0
																							)
													AND tbl_groups_regions.fld_start_date <= :EndDate3a
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :EndDate3b
														)
													AND tbl_group_attendance.fld_deleted = 0
													AND tbl_groups.fld_deleted = 0
													AND tbl_groups_schedules.fld_deleted = 0
													AND tbl_group_attendance.fld_date BETWEEN 
													CASE tbl_groups_schedules.fld_recurrency_string
													WHEN 'WEEK' THEN date_add(:EndDate4,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 WEEK)
													WHEN 'MONTH' THEN date_add(:EndDate5,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 MONTH)
													WHEN 'DAY' THEN date_add(:EndDate6,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 DAY)
													WHEN 'QUARTER' THEN date_add(:EndDate7,INTERVAL tbl_groups_schedules.fld_recurrency_int * 3 * -8 MONTH)
													WHEN 'YEAR' THEN date_add(:EndDate8,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 YEAR)
													ELSE date_add(:EndDate9,INTERVAL 8 MONTH)
													END AND :EndDate10
													)
									AND tbl_members.fld_user_id IN(
																	SELECT tbl_group_attendance.fld_user_id
																	FROM tbl_group_attendance
																	JOIN tbl_groups
																	ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
																	JOIN tbl_groups_schedules
																	ON tbl_groups.id_group = tbl_groups_schedules.fld_group_id
																	JOIN tbl_groups_regions
																	ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
																	WHERE tbl_groups_regions.fld_deleted = 0
																	AND tbl_groups.fld_non_group_type IS NULL
																	AND tbl_groups_regions.fld_region_id IN(
																											SELECT id_region
																											FROM tbl_regions
																											WHERE fld_branch_id = :BranchID2
																											AND fld_deleted = 0
																											)
																	AND tbl_groups_regions.fld_start_date <= :EndDate10a
																	AND (tbl_groups_regions.fld_end_date IS NULL
																		OR tbl_groups_regions.fld_end_date >= :EndDate10b
																		)
																	AND tbl_group_attendance.fld_deleted = 0
																	AND tbl_groups.fld_deleted = 0
																	AND tbl_groups_schedules.fld_deleted = 0
																	AND tbl_group_attendance.fld_date BETWEEN 
																	CASE tbl_groups_schedules.fld_recurrency_string
																	WHEN 'WEEK' THEN date_add(:EndDate11,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) WEEK)
																	WHEN 'MONTH' THEN date_add(:EndDate12,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) MONTH)
																	WHEN 'DAY' THEN date_add(:EndDate13,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) DAY)
																	WHEN 'QUARTER' THEN date_add(:EndDate14,INTERVAL (tbl_groups_schedules.fld_recurrency_int * 3 * -10) MONTH)
																	WHEN 'YEAR' THEN date_add(:EndDate15,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) YEAR)
																	ELSE date_add(:EndDate16,INTERVAL -10 MONTH)
																	END AND 
																	CASE tbl_groups_schedules.fld_recurrency_string
																	WHEN 'WEEK' THEN date_add(:EndDate17,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) WEEK)
																	WHEN 'MONTH' THEN date_add(:EndDate18,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) MONTH)
																	WHEN 'DAY' THEN date_add(:EndDate19,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) DAY)
																	WHEN 'QUARTER' THEN date_add(:EndDate20,INTERVAL (tbl_groups_schedules.fld_recurrency_int * 3 * -9) MONTH)
																	WHEN 'YEAR' THEN date_add(:EndDate21,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) YEAR)
																	ELSE date_add(:EndDate22,INTERVAL -9 MONTH)
																	END
																	)
									");
			foreach($Dates as $Date)
			{
				
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(	
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3a' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3b' => ($EndDate == 'null') ? null : $EndDate,
											':BranchID' => $BranchID,
											':EndDate4' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate5' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate6' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate7' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate8' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate9' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10a' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10b' => ($EndDate == 'null') ? null : $EndDate,
											':BranchID2' => $BranchID,
											':EndDate11' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate12' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate13' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate14' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate15' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate16' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate17' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate18' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate19' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate20' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate21' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate22' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$LapsedCount[] = $arrAttendances;
				}
			}
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $LapsedCount;
	}
	
	//assumes 8 meetings
	function count8SinceLastAttendedByRegion($RegionID,$Start,$End)
	{
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$LapsedCount = array();
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(:EndDate) AS this_year, MONTHNAME(:EndDate2) AS this_month, MONTH(:EndDate3) AS month_no
									FROM tbl_members
									WHERE tbl_members.fld_user_id NOT IN(
													SELECT tbl_group_attendance.fld_user_id
													FROM tbl_group_attendance
													JOIN tbl_groups
													ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
													JOIN tbl_groups_schedules
													ON tbl_groups.id_group = tbl_groups_schedules.fld_group_id
													JOIN tbl_groups_regions
													ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
													WHERE tbl_groups_regions.fld_deleted = 0
													AND tbl_groups.fld_non_group_type IS NULL
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND tbl_groups_regions.fld_start_date <= :EndDate3a
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :EndDate3b
														)
													AND tbl_group_attendance.fld_deleted = 0
													AND tbl_groups.fld_deleted = 0
													AND tbl_groups_schedules.fld_deleted = 0
													AND tbl_group_attendance.fld_date BETWEEN 
													CASE tbl_groups_schedules.fld_recurrency_string
													WHEN 'WEEK' THEN date_add(:EndDate4,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 WEEK)
													WHEN 'MONTH' THEN date_add(:EndDate5,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 MONTH)
													WHEN 'DAY' THEN date_add(:EndDate6,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 DAY)
													WHEN 'QUARTER' THEN date_add(:EndDate7,INTERVAL tbl_groups_schedules.fld_recurrency_int * 3 * -8 MONTH)
													WHEN 'YEAR' THEN date_add(:EndDate8,INTERVAL tbl_groups_schedules.fld_recurrency_int * -8 YEAR)
													ELSE date_add(:EndDate9,INTERVAL 8 MONTH)
													END AND :EndDate10
													)
									AND tbl_members.fld_user_id IN(
																	SELECT tbl_group_attendance.fld_user_id
																	FROM tbl_group_attendance
																	JOIN tbl_groups
																	ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
																	JOIN tbl_groups_schedules
																	ON tbl_groups.id_group = tbl_groups_schedules.fld_group_id
																	JOIN tbl_groups_regions
																	ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
																	WHERE tbl_groups_regions.fld_deleted = 0
																	AND tbl_groups.fld_non_group_type IS NULL
																	AND tbl_groups_regions.fld_region_id = :RegionID2
																	AND tbl_groups_regions.fld_start_date <= :EndDate10a
																	AND (tbl_groups_regions.fld_end_date IS NULL
																		OR tbl_groups_regions.fld_end_date >= :EndDate10b
																		)
																	AND tbl_group_attendance.fld_deleted = 0
																	AND tbl_groups.fld_deleted = 0
																	AND tbl_groups_schedules.fld_deleted = 0
																	AND tbl_group_attendance.fld_date BETWEEN 
																	CASE tbl_groups_schedules.fld_recurrency_string
																	WHEN 'WEEK' THEN date_add(:EndDate11,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) WEEK)
																	WHEN 'MONTH' THEN date_add(:EndDate12,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) MONTH)
																	WHEN 'DAY' THEN date_add(:EndDate13,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) DAY)
																	WHEN 'QUARTER' THEN date_add(:EndDate14,INTERVAL (tbl_groups_schedules.fld_recurrency_int * 3 * -10) MONTH)
																	WHEN 'YEAR' THEN date_add(:EndDate15,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -10) YEAR)
																	ELSE date_add(:EndDate16,INTERVAL -10 MONTH)
																	END AND 
																	CASE tbl_groups_schedules.fld_recurrency_string
																	WHEN 'WEEK' THEN date_add(:EndDate17,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) WEEK)
																	WHEN 'MONTH' THEN date_add(:EndDate18,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) MONTH)
																	WHEN 'DAY' THEN date_add(:EndDate19,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) DAY)
																	WHEN 'QUARTER' THEN date_add(:EndDate20,INTERVAL (tbl_groups_schedules.fld_recurrency_int * 3 * -9) MONTH)
																	WHEN 'YEAR' THEN date_add(:EndDate21,INTERVAL (tbl_groups_schedules.fld_recurrency_int * -9) YEAR)
																	ELSE date_add(:EndDate22,INTERVAL -9 MONTH)
																	END
																	)
									");
			foreach($Dates as $Date)
			{
				
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(	
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3a' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate3b' => ($EndDate == 'null') ? null : $EndDate,
											':RegionID' => $RegionID,
											':EndDate4' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate5' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate6' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate7' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate8' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate9' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10a' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate10b' => ($EndDate == 'null') ? null : $EndDate,
											':RegionID2' => $RegionID,
											':EndDate11' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate12' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate13' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate14' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate15' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate16' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate17' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate18' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate19' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate20' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate21' => ($EndDate == 'null') ? null : $EndDate,
											':EndDate22' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$LapsedCount[] = $arrAttendances;
				}
			}
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $LapsedCount;
	}
	
	function countNewCommittedByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id IN(
																			SELECT id_region
																			FROM tbl_regions
																			WHERE fld_branch_id = :BranchID
																			AND fld_deleted = 0
																			)
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									AND DATEDIFF(tbl_group_attendance.fld_date, tbl_member_committed_dates.fld_start_date) BETWEEN 0 AND 14
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			
				$Attendances->execute(array(	
											':BranchID' => $BranchID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countNewCommittedByMonthDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id = :RegionID
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									AND DATEDIFF(tbl_group_attendance.fld_date, tbl_member_committed_dates.fld_start_date) BETWEEN 0 AND 14
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			
				$Attendances->execute(array(	
											':RegionID' => $RegionID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countOrgAndRecAttAvgInPeriodByBranchMonthDates($BranchID,$Start,$End)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$AttAvg = array();
		
		
		try {
			
			$Attendances = $dbh->prepare("
									SELECT AVG(this_total.Total) AS Total, 
									this_year, this_month, month_no
									FROM 
										(	
										SELECT COUNT(*) AS Total,
										YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										JOIN tbl_groups_roles
										ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_groups_regions.fld_deleted = 0
										AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
										AND  (tbl_groups_regions.fld_end_date IS NULL 
											OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										AND tbl_groups_regions.fld_region_id IN(
																				SELECT id_region
																				FROM tbl_regions
																				WHERE fld_branch_id = :BranchID
																				AND fld_deleted = 0
																				)
										AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
										AND tbl_groups_roles.fld_deleted = 0
										AND tbl_groups_roles.fld_group_role_id IN(
																					SELECT id_group_role
																					FROM tbl_group_roles
																					WHERE fld_group_role IN(:Organiser,:Recorder)
																					)
										AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
										AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
										AND ( tbl_groups_roles.fld_end_date IS NULL
											OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
											)
									
									GROUP BY tbl_groups_regions.fld_group_id, tbl_group_attendance.fld_date ) AS this_total
									");
									
			foreach($Dates as $Date)
			{
				
				$StartDate = date_format($Date,'Y-m-d');
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(	
											':BranchID' => $BranchID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$AttAvg[] = $arrAttendances;
				}
			}
			
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $AttAvg;
			
	}
	
	function countOrgAndRecAttAvgInPeriodByMonthDates($RegionID,$Start,$End)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$AttAvg = array();
		
		
		try {
			
			$Attendances = $dbh->prepare("
									SELECT AVG(this_total.Total) AS Total, 
									this_year, this_month, month_no
									FROM 
										(	
										SELECT COUNT(*) AS Total,
										YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										JOIN tbl_groups_roles
										ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_groups_regions.fld_deleted = 0
										AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
										AND  (tbl_groups_regions.fld_end_date IS NULL 
											OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										AND tbl_groups_regions.fld_region_id = :RegionID
										AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
										AND tbl_groups_roles.fld_deleted = 0
										AND tbl_groups_roles.fld_group_role_id IN(
																					SELECT id_group_role
																					FROM tbl_group_roles
																					WHERE fld_group_role IN(:Organiser,:Recorder)
																					)
										AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
										AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
										AND ( tbl_groups_roles.fld_end_date IS NULL
											OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
											)
									
									GROUP BY tbl_groups_regions.fld_group_id, tbl_group_attendance.fld_date ) AS this_total
									");
									
			foreach($Dates as $Date)
			{
				
				$StartDate = date_format($Date,'Y-m-d');
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(	
											':RegionID' => $RegionID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$AttAvg[] = $arrAttendances;
				}
			}
			
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $AttAvg;
			
	}
	
	function countOrgAndRecAttInPeriodByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_groups_roles
									ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id IN(
																			SELECT id_region
																			FROM tbl_regions
																			WHERE fld_branch_id = :BranchID
																			AND fld_deleted = 0
																			)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									AND tbl_groups_roles.fld_deleted = 0
									AND tbl_groups_roles.fld_group_role_id IN(
																				SELECT id_group_role
																				FROM tbl_group_roles
																				WHERE fld_group_role IN(:Organiser,:Recorder)
																				)
									AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
									AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
									AND ( tbl_groups_roles.fld_end_date IS NULL
										OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
										)
									
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':BranchID' => $BranchID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
			
	}
	
	function countOrgAndRecAttInPeriodByMonthDates($RegionID,$StartDate,$EndDate)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_groups_roles
									ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id = :RegionID
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									AND tbl_groups_roles.fld_deleted = 0
									AND tbl_groups_roles.fld_group_role_id IN(
																				SELECT id_group_role
																				FROM tbl_group_roles
																				WHERE fld_group_role IN(:Organiser,:Recorder)
																				)
									AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
									AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
									AND ( tbl_groups_roles.fld_end_date IS NULL
										OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
										)
									
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':RegionID' => $RegionID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
			
	}
	
	function countOrgAndRecInPeriodByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_groups_roles
									ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id IN(
																			SELECT id_region
																			FROM tbl_regions
																			WHERE fld_branch_id = :BranchID
																			AND fld_deleted = 0
																			)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									AND tbl_groups_roles.fld_deleted = 0
									AND tbl_groups_roles.fld_group_role_id IN(
																				SELECT id_group_role
																				FROM tbl_group_roles
																				WHERE fld_group_role IN(:Organiser,:Recorder)
																				)
									AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
									AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
									AND ( tbl_groups_roles.fld_end_date IS NULL
										OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
										)
									
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':BranchID' => $BranchID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
			
	}
	
	function countOrgAndRecInPeriodByMonthDates($RegionID,$StartDate,$EndDate)
	{	
		//global $Organiser;
		//global $Recorder;
		global $dbh;
		
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_groups_roles
									ON tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id = :RegionID
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									AND tbl_groups_roles.fld_deleted = 0
									AND tbl_groups_roles.fld_group_role_id IN(
																				SELECT id_group_role
																				FROM tbl_group_roles
																				WHERE fld_group_role IN(:Organiser,:Recorder)
																				)
									AND tbl_groups_roles.fld_group_id = tbl_groups.id_group
									AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
									AND ( tbl_groups_roles.fld_end_date IS NULL
										OR tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date
										)
									
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':RegionID' => $RegionID,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
			
	}
	
	function countCommittedGrowerAttAvgInPeriodByBranchMonthDates($BranchID,$Start,$End)
	{
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$Committed = array();
		
		try {
			
			$Attendances = $dbh->prepare("	
									
									SELECT AVG(this_total.Total) AS Total, 
									this_year, this_month, month_no
									FROM 
										(SELECT COUNT(*) AS Total,
										YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										JOIN tbl_member_committed_dates
										ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_non_group_type IS NULL
										AND tbl_groups.fld_deleted = 0
										AND tbl_groups_regions.fld_deleted = 0
										AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
										AND  (tbl_groups_regions.fld_end_date IS NULL 
											OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										AND tbl_groups_regions.fld_region_id IN(
																				SELECT id_region
																				FROM tbl_regions
																				WHERE fld_branch_id = :BranchID
																				AND fld_deleted = 0
																				)
										AND tbl_member_committed_dates.fld_deleted = 0
										AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
										AND (tbl_member_committed_dates.fld_end_date IS NULL 
											OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
											)
										AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
										GROUP BY tbl_groups_regions.fld_group_id, tbl_group_attendance.fld_date ) AS this_total
									");
			foreach($Dates as $Date)
			{
				
				$StartDate = date_format($Date,'Y-m-d');
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(
											':BranchID' => $BranchID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$Committed[] = $arrAttendances;
				}
			}
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Committed;
	}
	
	function countCommittedGrowerAttAvgInPeriodByMonthDates($RegionID,$Start,$End)
	{
		global $dbh;
		
		$Dates = funGetMonthsBetweenDates($Start,$End);
			
		$Committed = array();
		
		try {
			
			$Attendances = $dbh->prepare("	
									
									SELECT AVG(this_total.Total) AS Total, 
									this_year, this_month, month_no
									FROM 
										(SELECT COUNT(*) AS Total,
										YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_groups_regions
										ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
										JOIN tbl_member_committed_dates
										ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_non_group_type IS NULL
										AND tbl_groups.fld_deleted = 0
										AND tbl_groups_regions.fld_deleted = 0
										AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
										AND  (tbl_groups_regions.fld_end_date IS NULL 
											OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
										AND tbl_groups_regions.fld_region_id = :RegionID
										AND tbl_member_committed_dates.fld_deleted = 0
										AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
										AND (tbl_member_committed_dates.fld_end_date IS NULL 
											OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
											)
										AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
										GROUP BY tbl_groups_regions.fld_group_id, tbl_group_attendance.fld_date ) AS this_total
									");
			foreach($Dates as $Date)
			{
				
				$StartDate = date_format($Date,'Y-m-d');
				$EndDate = date_format($Date,'Y-m-t');
				
				$Attendances->execute(array(
											':RegionID' => $RegionID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				$arrAttendances = $Attendances->fetch();
				
				if($arrAttendances['month_no'] != '')
				{
					$Committed[] = $arrAttendances;
				}
			}
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Committed;
	}
	
	function countCommittedGrowerAttInPeriodByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id IN(
																			SELECT id_region
																			FROM tbl_regions
																			WHERE fld_branch_id = :BranchID
																			AND fld_deleted = 0
																			)
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			
				$Attendances->execute(array(
											':BranchID' => $BranchID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countCommittedGrowerAttInPeriodByMonthDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id = :RegionID
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			
				$Attendances->execute(array(
											':RegionID' => $RegionID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countCommittedGrowersInPeriodByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >=  tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id IN(
																			SELECT id_region
																			FROM tbl_regions
																			WHERE fld_branch_id = :BranchID
																			AND fld_deleted = 0
																			)
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <=  tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >=  tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':BranchID' => $BranchID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
		
	}
	
	function countCommittedGrowersInPeriodByMonthDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare("	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									JOIN tbl_groups_regions
									ON tbl_groups.id_group = tbl_groups_regions.fld_group_id
									JOIN tbl_member_committed_dates
									ON tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_groups_regions.fld_deleted = 0
									AND tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
									AND  (tbl_groups_regions.fld_end_date IS NULL 
										OR tbl_groups_regions.fld_end_date >=  tbl_group_attendance.fld_date)
									AND tbl_groups_regions.fld_region_id = :RegionID
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <=  tbl_group_attendance.fld_date
									AND (tbl_member_committed_dates.fld_end_date IS NULL 
										OR tbl_member_committed_dates.fld_end_date >=  tbl_group_attendance.fld_date
										)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									");
			$Attendances->execute(array(	
											':RegionID' => $RegionID,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate
											
											 ));
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
		
	}
		
	
	function getCountNewCommittedGrowersInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT fld_group_name, ( SELECT COUNT(DISTINCT tbl_members.fld_user_id)
															FROM tbl_group_attendance
															LEFT OUTER JOIN tbl_members
															ON tbl_members.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_group_attendance.fld_deleted = :false
															AND tbl_group_attendance.fld_date BETWEEN :StartDate3 AND :EndDate3
															AND EXISTS(
																		SELECT *
																		FROM tbl_member_committed_dates
																		WHERE tbl_member_committed_dates.fld_user_id = tbl_members.fld_user_id
																		AND fld_start_date BETWEEN :StartDate AND :EndDate
																		AND tbl_member_committed_dates.fld_deleted = :false2
																		)
															GROUP BY tbl_group_attendance.fld_group_id
															)AS new_com
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = :false3			
													)
									
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false4
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	':false' => $false,
											':StartDate3' => $StartDate,
											':EndDate3' => $EndDate,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':false2' => $false,
											':RegionID' => $RegionID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											':false3' => $false,
											':false4' => $false
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getCountCommittedGrowersInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		//global $Organiser;
		//global $Recorder;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									
									
									SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_members.fld_user_id)
																FROM tbl_group_attendance
																JOIN tbl_members
																ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
																WHERE fld_date BETWEEN :StartDate AND :EndDate
																AND fld_deleted = :false
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_members.fld_first_name != :CommunityObserver
																AND EXISTS(
																			SELECT *
																			FROM tbl_member_committed_dates
																			WHERE tbl_member_committed_dates.fld_user_id = tbl_members.fld_user_id
																			AND fld_start_date <= :EndDate2
																			AND (fld_end_date IS NULL 
																				OR fld_end_date >= :StartDate2
																				)
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS com_grow,
																(
																SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
																FROM tbl_group_attendance
																WHERE fld_date BETWEEN :StartDate3 AND :EndDate3
																AND fld_deleted = :false2
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND EXISTS(
																			SELECT * 
																			FROM tbl_groups_roles
																			JOIN tbl_group_roles
																			ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
																			WHERE tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
																			AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
																			AND (tbl_group_roles.fld_group_role = :Organiser OR tbl_group_roles.fld_group_role = :Recorder)
																			AND tbl_groups_roles.fld_start_date <= :EndDate4

																			AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate4 )
																			AND tbl_groups_roles.fld_deleted = 0
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS org_rec
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate5
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate5)
													AND tbl_groups_regions.fld_deleted = :false3					
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false4
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':false' => $false,
											':CommunityObserver' => $CommunityObserver,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											':StartDate3' => $StartDate,
											':EndDate3' => $EndDate,
											':false2' => $false,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER,
											':EndDate4' => $EndDate,
											':StartDate4' => $StartDate,
											':RegionID' => $RegionID,
											':EndDate5' => $EndDate,
											':StartDate5' => $StartDate,
											':false3' => $false,
											':false4' => $false
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getAttendanceForCommunityObserversByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									
									
									SELECT fld_group_name, (
																SELECT SUM(fld_no_attendees)
																FROM tbl_group_attendance_other
																JOIN tbl_groups_regions
																ON tbl_group_attendance_other.fld_group_id = tbl_groups_regions.fld_group_id
																WHERE tbl_group_attendance_other.fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_group_attendance_other.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance_other.fld_date >= tbl_groups_regions.fld_start_date
																AND (
																	tbl_groups_regions.fld_end_date IS NULL 
																	OR tbl_groups_regions.fld_end_date >= tbl_group_attendance_other.fld_date
																	)
																AND tbl_groups_regions.fld_deleted = 0
																GROUP BY tbl_group_attendance_other.fld_group_id
																) AS com_obs
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = :false2					
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false3
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':RegionID' => $RegionID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											':false2' => $false,
											':false3' => $false
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getCountFirstTimerGrowersContInPeriodByRegion($RegionID,$AttendedTimes,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT fld_group_name, (
														SELECT COUNT(Sub_table.Total_Attendances), tbl_groups.id_group
														FROM (	SELECT COUNT( DISTINCT tbl_group_attendance.id_attendance ) AS Total_Attendances
																FROM tbl_group_attendance
																JOIN tbl_user_activity_dates
																ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance.fld_deleted = 0
																AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_user_activity_dates.fld_deleted = 0
																AND tbl_user_activity_dates.fld_user_type_string = :Member
																AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																
																AND ( 
																	tbl_user_activity_dates.fld_end_date IS NULL 
																	OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																	)
																AND tbl_group_attendance.fld_user_id IN(
																					SELECT Att2.fld_user_id
																					FROM tbl_group_attendance AS Att2
																					JOIN tbl_user_activity_dates AS Act2
																					ON Att2.fld_user_id = Act2.fld_user_id
																					WHERE tbl_group_attendance.fld_group_id = Att2.fld_group_id
																					AND Att2.fld_deleted = 0
																					AND Att2.fld_date BETWEEN :StartDate2 AND :EndDate2
																					AND (fld_date,Att2.fld_user_id) IN(
																													SELECT MIN(fld_date),fld_user_id 
																													FROM tbl_group_attendance
																													GROUP BY fld_user_id
																													)
																					AND Act2.fld_deleted = 0
																					AND Act2.fld_user_type_string = :Member2
																					AND Att2.fld_date >= Act2.fld_start_date
																					AND ( 
																						Act2.fld_end_date IS NULL 
																						OR Att2.fld_date <= Act2.fld_end_date
																						)
																					)
																GROUP BY tbl_group_attendance.fld_user_id
																HAVING COUNT( DISTINCT tbl_group_attendance.id_attendance ) >= :Attendances
																) AS Sub_table
															) AS first_timers
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate3
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate3)
													AND tbl_groups_regions.fld_deleted = 0				
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':Member' => MEMBER_STRING,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate,
									':Member2' => MEMBER_STRING,
									':Attendances' => $AttendedTimes,
									':RegionID' => $RegionID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate,
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getNewCommittedGrowersByRegionBetweenDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total_Aees
																FROM tbl_group_attendance
																JOIN tbl_user_activity_dates
																ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance.fld_deleted = 0
																AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_user_activity_dates.fld_deleted = 0
																AND tbl_user_activity_dates.fld_user_type_string = :Member
																AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																AND ( 
																	tbl_user_activity_dates.fld_end_date IS NULL 
																	OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																	)
																AND EXISTS(
																			SELECT *
																			FROM tbl_member_committed_dates
																			WHERE tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
																			AND fld_start_date >= :StartDate2
																			AND fld_start_date <= :EndDate2
																			AND tbl_member_committed_dates.fld_deleted = 0
																			)
																) AS Total_Aees
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate3
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate3)
													AND tbl_groups_regions.fld_deleted = 0				
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':Member' => MEMBER_STRING,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate,
									':RegionID' => $RegionID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate,
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getCountFirstTimerGrowersInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
																FROM tbl_group_attendance
																JOIN tbl_user_activity_dates
																ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance.fld_deleted = 0
																AND fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_group_attendance.fld_user_id IN(
																					SELECT Att2.fld_user_id
																					FROM tbl_group_attendance AS Att2
																					JOIN tbl_user_activity_dates AS Act2
																					ON Att2.fld_user_id = Act2.fld_user_id
																					WHERE tbl_group_attendance.fld_group_id = Att2.fld_group_id
																					AND Att2.fld_deleted = 0
																					AND Att2.fld_date BETWEEN :StartDate2 AND :EndDate2
																					AND (fld_date,Att2.fld_user_id) IN(
																													SELECT MIN(fld_date),fld_user_id 
																													FROM tbl_group_attendance
																													GROUP BY fld_user_id
																													)
																					AND Act2.fld_deleted = 0
																					AND Act2.fld_user_type_string = :Member
																					AND Att2.fld_date >= Act2.fld_start_date
																					AND ( 
																						Act2.fld_end_date IS NULL 
																						OR Att2.fld_date <= Act2.fld_end_date
																						)
																					)
																AND tbl_user_activity_dates.fld_deleted = 0
																AND tbl_user_activity_dates.fld_user_type_string = :Member2
																AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																
																AND ( 
																	tbl_user_activity_dates.fld_end_date IS NULL 
																	OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																	)
																GROUP BY fld_group_id
																) AS first_timers
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate3
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate3)
													AND tbl_groups_regions.fld_deleted = 0				
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate,
									':Member' => MEMBER_STRING,
									':Member2' => MEMBER_STRING,
									':RegionID' => $RegionID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate,
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getCountFirstTimersInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT fld_group_name, (
																SELECT COUNT(*)
																FROM tbl_group_attendance
																WHERE (fld_group_id,fld_date,fld_user_id) IN(
																	SELECT fld_group_id,MIN(fld_date),fld_user_id 
																	FROM tbl_group_attendance
																	WHERE fld_deleted = false
																	GROUP BY fld_user_id
																)
																AND fld_date BETWEEN :StartDate AND :EndDate
																AND fld_deleted = :false
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND EXISTS(
																			SELECT *
																			FROM tbl_user_activity_dates
																			WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
																			AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																			AND (
																				tbl_user_activity_dates.fld_end_date IS NULL 
																				OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																				)
																			AND fld_user_type_string = :MEMBER_STRING
																			AND tbl_user_activity_dates.fld_deleted = 0
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS first_timers
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = :false2				
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false3
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':false' => $false,
									':MEMBER_STRING' => MEMBER_STRING,
									':RegionID' => $RegionID,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':false2' => $false,
									':false3' => $false
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getCountMeetingsAttendedInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('
									SELECT fld_group_name, ( SELECT COUNT(DISTINCT fld_date)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_groups_regions.fld_region_id = :RegionID
															AND fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL
																OR fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															) AS total_meetings
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID2
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = 0
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Count->execute(array(	':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':RegionID' => $RegionID,
									':RegionID2' => $RegionID,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function setRegionDeleted($RegionID,$Deleted)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_regions
										SET fld_deleted = :Deleted
										WHERE id_region = :RegionID
										');
			$qryUpdate->execute(array(
										':Deleted' => $Deleted,
										':RegionID' => $RegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $RegionID;
	}
	
	function countCommittedGrowersByRegionMonthYear($RegionID,$thisDate)
	{
		global $dbh;
		
		
		$StartDate = date_format($thisDate,'Y-m-d');
		$EndDate = date_format($thisDate,'Y-m-t');
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(*) AS Total
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_first_name = :CommunityObserver
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= :EndDate2
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :StartDate2)
													)
											)
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver,
											':RegionID' => $RegionID,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countAttendanceForCommunityObserversByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT SUM(fld_no_attendees) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance_other
									JOIN tbl_groups
									ON tbl_group_attendance_other.fld_group_id = tbl_groups.id_group
									WHERE tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_group_attendance_other.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance_other.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance_other.fld_date)
													)
											)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':BranchID' => $BranchID
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countAttendanceForCommunityObserversByRegionMonthDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT SUM(fld_no_attendees) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance_other
									JOIN tbl_groups
									ON tbl_group_attendance_other.fld_group_id = tbl_groups.id_group
									WHERE tbl_groups.fld_deleted = 0
									AND tbl_groups.fld_non_group_type IS NULL
									AND tbl_group_attendance_other.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance_other.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance_other.fld_date)
													)
											)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':RegionID' => $RegionID
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countAttendanceForCommunityObserversByRegionMonthYear($RegionID,$thisDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		$StartDate = date_format($thisDate,'Y-m-d');
		$EndDate = date_format($thisDate,'Y-m-t');
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(*) AS Total
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_first_name = :CommunityObserver
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= :EndDate2
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :StartDate2)
													)
											)
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver,
											':RegionID' => $RegionID,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countFirstTimerAttendancesByBranchMonthDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_groups.fld_non_group_type IS NULL
									AND (fld_date,fld_user_id) IN(
												SELECT MIN(fld_date),fld_user_id 
												FROM tbl_group_attendance
												GROUP BY fld_user_id
												)
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
													)
												
											)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
												AND (
													tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
													)
												AND fld_user_type_string = :MEMBER_STRING
												AND tbl_user_activity_dates.fld_deleted = 0
												)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									
									');
			$Attendances->execute(array(	
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':BranchID' => $BranchID,
										':MEMBER_STRING' => MEMBER_STRING
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countFirstTimerAttendancesByRegionMonthDates($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(*) AS Total,
									YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_groups.fld_non_group_type IS NULL
									AND (fld_date,fld_user_id) IN(
												SELECT MIN(fld_date),fld_user_id 
												FROM tbl_group_attendance
												GROUP BY fld_user_id
												)
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
													)
												
											)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
												AND (
													tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
													)
												AND fld_user_type_string = :MEMBER_STRING
												AND tbl_user_activity_dates.fld_deleted = 0
												)
									GROUP BY YEAR(fld_date), MONTH(fld_date)
									ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC
									
									');
			$Attendances->execute(array(	
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':RegionID' => $RegionID,
										':MEMBER_STRING' => MEMBER_STRING
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	
	function countFirstTimerAttendancesByRegionMonthYear($RegionID,$thisDate)
	{
		global $dbh;
		
		$StartDate = date_format($thisDate,'Y-m-d');
		$EndDate = date_format($thisDate,'Y-m-t');
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(*) AS Total
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND (fld_date,fld_user_id) IN(
												SELECT MIN(fld_date),fld_user_id 
												FROM tbl_group_attendance
												GROUP BY fld_user_id
												)
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= :EndDate2
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :StartDate2)
													)
											)
									');
			$Attendances->execute(array(	
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':RegionID' => $RegionID,
								 		':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
										':StartDate2' => ($StartDate == 'null') ? null : $StartDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function countTotalMeetingsByMonthsBranchDatesAndTypeOther($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			$qryCount = $dbh->prepare("
										
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total,
											YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type IN('Organiser &amp; Recorder','Leadership')
										AND tbl_groups.fld_non_group_type IS NULL
										AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >=  tbl_group_attendance.fld_date)
													)
											)
										GROUP BY YEAR(fld_date), MONTH(fld_date)
										ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC

										");
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':BranchID' => $BranchID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function countTotalMeetingsByMonthsRegionDatesAndTypeOther($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			$qryCount = $dbh->prepare("
										
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total,
											YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type IN('Organiser &amp; Recorder','Leadership','Staff')
										AND tbl_groups.fld_non_group_type IS NULL
										AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >=  tbl_group_attendance.fld_date)
													)
											)
										GROUP BY YEAR(fld_date), MONTH(fld_date)
										ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC

										");
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':RegionID' => $RegionID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function countTotalMeetingsByMonthsRegionDatesAndTypeOtherBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			$qryCount = $dbh->prepare("
										
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total,
											YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type IN('Organiser &amp; Recorder','Leadership','Staff')
										AND tbl_groups.fld_non_group_type IS NULL
										AND EXISTS(
												
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
													)
											)
										GROUP BY YEAR(fld_date), MONTH(fld_date)
										ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC

										");
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':BranchID' => $BranchID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function countTotalMeetingsByMonthsBranchDatesAndType($BranchID,$StartDate,$EndDate,$GroupType)
	{
		global $dbh;
		
		try {
			$qryCount = $dbh->prepare('
										
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total,
											YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type = :GroupType
										AND tbl_groups.fld_non_group_type IS NULL
										AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id IN(
																						SELECT id_region
																						FROM tbl_regions
																						WHERE fld_branch_id = :BranchID
																						AND fld_deleted = 0
																						)
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
													)
											)
										GROUP BY YEAR(fld_date), MONTH(fld_date)
										ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC

										');
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupType' => $GroupType,
										':BranchID' => $BranchID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function countTotalMeetingsByMonthsRegionDatesAndType($RegionID,$StartDate,$EndDate,$GroupType)
	{
		global $dbh;
		
		try {
			$qryCount = $dbh->prepare('
										
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total,
											YEAR(fld_date) AS this_year, MONTHNAME(fld_date) AS this_month, MONTH(fld_date) AS month_no
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type = :GroupType
										AND tbl_groups.fld_non_group_type IS NULL
										AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= tbl_group_attendance.fld_date)
													)
											)
										GROUP BY YEAR(fld_date), MONTH(fld_date)
										ORDER BY YEAR(fld_date) ASC, MONTH(fld_date) ASC

										');
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupType' => $GroupType,
										':RegionID' => $RegionID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function countTotalMeetingsByRegionMonthYearAndType($RegionID,$thisDate,$GroupType)
	{
		global $dbh;
		
		$StartDate = date_format($thisDate,'Y-m-d');
		$EndDate = date_format($thisDate,'Y-m-t');
		
		try {
			$qryCount = $dbh->prepare('
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_group_id, tbl_group_attendance.fld_date) AS Total
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										JOIN tbl_group_types
										ON tbl_groups.fld_group_type = tbl_group_types.id_group_type
										WHERE tbl_group_attendance.fld_deleted = 0
										AND tbl_groups.fld_deleted = 0
										AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
										AND tbl_group_types.fld_group_type = :GroupType
										AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_groups.id_group = tbl_groups_regions.fld_group_id
												AND tbl_groups_regions.fld_region_id = :RegionID
												AND (tbl_groups_regions.fld_start_date <= :EndDate2
													AND (tbl_groups_regions.fld_end_date IS NULL
														OR tbl_groups_regions.fld_end_date >= :StartDate2)
													)
											)
										');
			$qryCount->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupType' => $GroupType,
										':RegionID' => $RegionID,
								 		':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
										':StartDate2' => ($StartDate == 'null') ? null : $StartDate
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $qryCount;
	}
	
	function get_regions_by_branches($staff_id,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$regions = $dbh->prepare('SELECT tbl_regions.*
									FROM tbl_regions
									WHERE fld_branch_id IN(
															SELECT fld_branch_id
															FROM tbl_state_users_state_activity_dates
															WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
															AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
															AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																)
															AND fld_user_id = :staff_id
															)
									'.$show.'
									ORDER BY fld_region_name DESC');
			$regions->execute(array(':staff_id' => $staff_id ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $regions;
	}
	
	function getRegionsByBranchID($BranchID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Regions = $dbh->prepare('SELECT tbl_regions.*
									FROM tbl_regions
									WHERE fld_branch_id = :BranchID
									'.$show.'
									ORDER BY fld_region_name DESC');
			$Regions->execute(array(':BranchID' => $BranchID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Regions;
	}
	
	function updRegion($RegionID,$RegionName,$BranchID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_regions
										SET fld_region_name = :RegionName,
										fld_branch_id = :BranchID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_region = :RegionID
										');
			$qryUpdate->execute(array(
										':RegionName' => $RegionName,
										':BranchID' => $BranchID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':RegionID' => $RegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addRegion($RegionName,$BranchID,$StartDate,$EndDate)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_regions(fld_region_name,fld_branch_id,fld_start_date,fld_end_date) 
								  VALUES (:RegionName,:BranchID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':RegionName' => $RegionName,
								 	':BranchID' => $BranchID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function getRegion($RegionID)
	{
		global $dbh;
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_regions
									WHERE id_region = :RegionID
									');
			$Region->execute(array(':RegionID' => $RegionID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function getAllRegions($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'WHERE fld_deleted = 0');
		
		try {
			$Regions = $dbh->query('SELECT tbl_regions.*
									FROM tbl_regions
									'.$show.'
									ORDER BY  fld_branch_id DESC, fld_region_name DESC');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Regions;
	}
	
	function getRegionsObjectToArray($Regions)
	{
		
		$ids = array();
		
		foreach($Regions as $Region)
		{
			$ids[] = $Region->GetRegionID();
		}
		
		$qMarks = str_repeat('?,', count($ids) - 1) . '?';
		
		global $dbh;
		
		try {
			$Regions = $dbh->prepare("SELECT tbl_regions.*, tbl_branches.*
									FROM tbl_regions
									JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE tbl_regions.id_region IN($qMarks)
									ORDER BY  tbl_branches.fld_branch_name DESC, tbl_regions.fld_region_name DESC");
			$Regions->execute($ids);
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Regions;
		
	}
	
	function getAllRegionsForDropDown()
	{
		global $dbh;
		
		try {
			$Regions = $dbh->query('SELECT tbl_regions.*, tbl_branches.*
									FROM tbl_regions
									JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE tbl_regions.fld_deleted = 0
									ORDER BY  tbl_branches.fld_branch_name DESC, tbl_regions.fld_region_name DESC');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Regions;
	}
	
	function get_all_regions_for_drop_down_by_state($staff_id)
	{
		global $dbh;
		
		try {
			$regions = $dbh->prepare('SELECT tbl_regions.*, tbl_branches.*
									FROM tbl_regions
									JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									AND tbl_regions.fld_deleted = 0
									AND tbl_regions.fld_branch_id IN(
																	SELECT fld_branch_id
																	FROM tbl_state_users_state_activity_dates
																	WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																	AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																	AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																		OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																		)
																	AND fld_user_id = :staff_id
																	)
									ORDER BY  tbl_branches.fld_branch_name DESC, tbl_regions.fld_region_name DESC
									');
			$regions->execute(array(':staff_id' => $staff_id ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $regions;
	}
	
	function getAllBranchesForDropDownByState($StaffID)
	{
		global $dbh;
		
		try {
			$branches = $dbh->prepare('SELECT *
									FROM tbl_branches
									WHERE id_branch IN(
														SELECT fld_branch_id
														FROM tbl_state_users_state_activity_dates
														WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
														AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
														AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
															OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
															)
														AND fld_user_id = :StaffID
														)
									ORDER BY tbl_branches.fld_branch_name DESC
									');
			$branches->execute(array(':StaffID' => $StaffID ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $branches;
	}
	
	function getAllRegionsForDropDownByState($StateAbr)
	{
		global $dbh;
		
		try {
			$Regions = $dbh->prepare('SELECT tbl_regions.*, tbl_branches.*
									FROM tbl_regions
									JOIN tbl_branches
									ON tbl_regions.fld_branch_id = tbl_branches.id_branch
									WHERE tbl_branches.fld_branch_abbreviation = :StateAbr
									AND tbl_regions.fld_deleted = 0
									ORDER BY  tbl_branches.fld_branch_name DESC, tbl_regions.fld_region_name DESC');
			$Regions->execute(array(':StateAbr' => $StateAbr ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Regions;
	}
	
	class GroupRegion {
		
		private $GroupRegionID;
		private $GroupID;
		private $RegionID;
		private $BranchID;
		private $StartDate;
		private $EndDate;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupRegionID()
		{
			return $this->GroupRegionID;
		}
		
		public function SetGroupRegionID($GroupRegionID)
		{
			$this->GroupRegionID = $GroupRegionID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetRegionID()
		{
			return $this->RegionID;
		}
		
		public function SetRegionID($RegionID)
		{
			$this->RegionID = $RegionID;
		}
		
		public function GetBranchID()
		{
			return $this->BranchID;
		}
		
		public function SetBranchID($BranchID)
		{
			$this->BranchID = $BranchID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public function Delete()
		{
			global $true;
			
			$this->Deleted = $true;
			
			updGroupRegionDeleted($this->GroupRegionID,$this->Deleted);
		}
		
		public function IsBranchOrRegion()
		{
			if( $this->RegionID != '' )
			{
				return GROUP_REGION_REGION;
			}
			elseif( $this->BranchID != '' )
			{
				return GROUP_REGION_BRANCH;
			}
			else
			{
				return GROUP_REGION_UNKNOWN;
			}
		}
		
		public static function LoadGroupsRegionByDate($GroupID,$Date,$show_deleted = false)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupRegion = getGroupRegionByDate($GroupID,$Date,$show_deleted);
			
			if( $pdoGroupRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			GroupRegion::ArrToGroupRegion($pdoGroupRegion->fetch());
		}
		
		public static function LoadGroupsRegions($GroupID,$show_deleted = false)
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsRegions = getGroupsRegions($GroupID,$show_deleted)->fetchAll();
			
			$GroupsRegions = array();
			
			foreach( $arrGroupsRegions as $Group )
			{
				$GroupsRegions[] = GroupRegion::ArrToGroupRegion($Group);
			}
						
			return $GroupsRegions;
		}
		
		public static function LoadGroupRegion($GroupRegionID,$show_deleted = false)
		{
			$GroupRegionID = intval($GroupRegionID);
			
			$pdoGroupRegion = getGroupRegion($GroupRegionID,$show_deleted);
			
			if($pdoGroupRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupRegion::ArrToGroupRegion($pdoGroupRegion->fetch());
		}
		
		public static function LoadGroupsCurrentRegion($GroupID,$show_deleted = false)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupRegion = getCurrentGroupRegion($GroupID,$show_deleted);
			
			if($pdoGroupRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupRegion::ArrToGroupRegion($pdoGroupRegion->fetch());
		}
		
		public static function LoadGroupsLastRegion($GroupID,$show_deleted = false)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupRegion = getLastGroupRegion($GroupID,$show_deleted );
			
			if($pdoGroupRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupRegion::ArrToGroupRegion($pdoGroupRegion->fetch());
		}
		
		public static function GroupHasRegion($GroupID)
		{
			return GroupRegion::LoadGroupsCurrentRegion($GroupID) != NULL;
		}	
		
		public static function ArrToGroupRegion($Item)
		{
			$thisGroupRegion = new GroupRegion();
			
			$thisGroupRegion->SetGroupRegionID($Item['id_group_region']);
			$thisGroupRegion->SetGroupID($Item['fld_group_id']);
			$thisGroupRegion->SetRegionID($Item['fld_region_id']);
			$thisGroupRegion->SetBranchID($Item['fld_branch_id']);
			$thisGroupRegion->SetStartDate($Item['fld_start_date']);
			$thisGroupRegion->SetEndDate($Item['fld_end_date']);
			$thisGroupRegion->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupRegion;
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function GetBranch()
		{
			return Branch::LoadBranch($this->BranchID);
		}
		
		public function GetRegion()
		{
			return Region::LoadRegion($this->RegionID);
			
		}
		
		public static function CreateBranchRegion($GroupID,$RegionID,$BranchID,$StartDate,$EndDate)
		{
			$BranchRegionID = addBranchRegion($GroupID,$RegionID,$BranchID,$StartDate,$EndDate);
			
			return GroupRegion::LoadGroupRegion($BranchRegionID);
		}
		
		public static function CreateGroupRegion($GroupID,$RegionID,$StartDate,$EndDate)
		{
			$GroupRegionID = addGroupRegion($GroupID,$RegionID,$StartDate,$EndDate);
			
			return GroupRegion::LoadGroupRegion($GroupRegionID);
		}
		
		public function UpdateGroupRegion($RegionID,$StartDate,$EndDate)
		{
			$this->RegionID = $RegionID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updGroupRegion($this->GroupRegionID,$this->RegionID,$this->StartDate,$this->EndDate);
			
		}
		
		public function UpdateBranchRegion($BranchID,$RegionID,$StartDate,$EndDate)
		{
			$this->BranchID = $BranchID;
			$this->RegionID = $RegionID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updBranchRegion($this->GroupRegionID,$this->BranchID,$this->RegionID,$this->StartDate,$this->EndDate);
			
		}
		
	} // END GROUP REGION
	
	function updBranchRegion($GroupRegionID,$BranchID,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups_regions
										SET 
										fld_branch_id = :BranchID,
										fld_region_id = :RegionID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_group_region = :GroupRegionID
										');
			$qryUpdate->execute(array(
										':BranchID' => ($BranchID == '') ? null : $BranchID,
										':RegionID' => ($RegionID == '') ? null : $RegionID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupRegionID' => $GroupRegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function updGroupRegionDeleted($GroupRegionID,$Deleted)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_groups_regions SET
										fld_deleted = :Deleted
										WHERE id_group_region = :GroupRegionID
										');
			$qryUpdate->execute(array(
										':Deleted' => $Deleted,
										':GroupRegionID' => $GroupRegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function updGroupRegion($GroupRegionID,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups_regions
										SET fld_region_id = :RegionID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_group_region = :GroupRegionID
										');
			$qryUpdate->execute(array(
										':RegionID' => $RegionID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupRegionID' => $GroupRegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addBranchRegion($GroupID,$RegionID,$BranchID,$StartDate,$EndDate)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_regions(fld_group_id,fld_region_id,fld_branch_id,fld_start_date,fld_end_date) 
								  VALUES (:GroupID,:RegionID,:BranchID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID,
								 	':RegionID' => ($RegionID == '') ? null : $RegionID,
								 	':BranchID' => ($BranchID == '') ? null : $BranchID,
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function addGroupRegion($GroupID,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_regions(fld_group_id,fld_region_id,fld_start_date,fld_end_date) 
								  VALUES (:GroupID,:RegionID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID,
								 	':RegionID' => $RegionID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function getGroupsRegions($GroupID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0');
		
		try {
			
			$GroupsRegions = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE fld_group_id = :GroupID
									'.$show.'
									ORDER BY fld_start_date DESC;
									');
			$GroupsRegions->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsRegions;
	}
	
	function getGroupRegion($GroupRegionID,$show_deleted)
	{
		global $dbh;
		
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0');
		
		
		try {
			
			$GroupRegion = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE id_group_region = :GroupRegionID
									'.$show.'
									
									');
			$GroupRegion->execute(array(':GroupRegionID' => $GroupRegionID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupRegion;
	}
	
	function getGroupRegionByDate($GroupID,$Date,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0');
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE fld_group_id = :GroupID
									AND fld_start_date <= :Date
									AND (fld_end_date IS NULL or fld_end_date >= :Date2 )
									'.$show.'
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(
									':GroupID' => $GroupID,
									':Date' => $Date,
									':Date2' => $Date
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function getCurrentGroupRegion($GroupID,$show_deleted)
	{
		global $dbh;
		
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0');
		
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE fld_group_id = :GroupID
									AND fld_start_date <= DATE(NOW())
									AND (fld_end_date IS NULL or fld_end_date >= DATE(NOW()) )
									'.$show.'
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function getLastGroupRegion($GroupID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0');
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_regions
									WHERE fld_group_id = :GroupID
									'.$show.'
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	class StaffRegion {
		
		private $StaffRegionID;
		private $UserID;
		private $RegionID;
		private $StartDate;
		private $EndDate;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetStaffRegionID()
		{
			return $this->StaffRegionID;
		}
		
		public function SetStaffRegionID($StaffRegionID)
		{
			$this->StaffRegionID = $StaffRegionID;
		}
		
		public function GetUserID()
		{
			return $this->UserID;
		}
		
		public function SetUserID($UserID)
		{
			$this->UserID = $UserID;
		}
		
		public function GetRegionID()
		{
			return $this->RegionID;
		}
		
		public function SetRegionID($RegionID)
		{
			$this->RegionID = $RegionID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function HasAttendanceByDate($GroupID,$Date)
		{
			return Attendance::LoadAttendanceByGroupUserDate($GroupID,$this->UserID,$Date) != NULL;
			
		}
		
		public static function LoadStaffRegionsByDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			$arrStaffsRegions = getStaffRegionsByDate($GroupID,$Date)->fetchAll();
			
			$StaffsRegions = array();
			
			foreach( $arrStaffsRegions as $StaffRegion )
			{
				$StaffsRegions[] = StaffRegion::ArrToStaffRegion($StaffRegion);
			}
						
			return $StaffsRegions;
		}
		
		public static function LoadStaffsRegions($UserID)
		{
			$UserID = intval($UserID);
			
			$arrStaffsRegions = getStaffsRegions($UserID)->fetchAll();
			
			$StaffsRegions = array();
			
			foreach( $arrStaffsRegions as $StaffRegion )
			{
				$StaffsRegions[] = StaffRegion::ArrToStaffRegion($StaffRegion);
			}
						
			return $StaffsRegions;
		}
		
		public static function LoadStaffRegion($StaffRegionID)
		{
			$StaffRegionID = intval($StaffRegionID);
			
			$pdoStaffRegion = getStaffRegion($StaffRegionID);
			
			if($pdoStaffRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return StaffRegion::ArrToStaffRegion($pdoStaffRegion->fetch());
		}
		
		public static function LoadStaffsLastRegion($UserID)
		{
			$UserID = intval($UserID);
			
			$pdoStaffRegion = getLastStaffRegion($UserID);
			
			if($pdoStaffRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return StaffRegion::ArrToStaffRegion($pdoStaffRegion->fetch());
		}
		
		public static function ArrToStaffRegion($Item)
		{
			$thisStaffRegion = new StaffRegion();
			
			$thisStaffRegion->SetStaffRegionID($Item['id_staff_region']);
			$thisStaffRegion->SetUserID($Item['fld_user_id']);
			$thisStaffRegion->SetRegionID($Item['fld_region_id']);
			$thisStaffRegion->SetStartDate($Item['fld_start_date']);
			$thisStaffRegion->SetEndDate($Item['fld_end_date']);
			
			return $thisStaffRegion;
		}
		
		public function GetUser()
		{
			return \Membership\User::UniversalMemberLoader($this->UserID);
		}
		
		public function GetRegion()
		{
			return Region::LoadRegion($this->RegionID);
		}
		
		public static function CreateStaffRegion($UserID,$RegionID,$StartDate,$EndDate)
		{
			$StaffRegionID = addStaffRegion($UserID,$RegionID,$StartDate,$EndDate);
			
			return StaffRegion::LoadStaffRegion($StaffRegionID);
		}
		
		public function UpdateStaffRegion($RegionID,$StartDate,$EndDate)
		{
			$this->RegionID = $RegionID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updStaffRegion($this->StaffRegionID,$this->RegionID,$this->StartDate,$this->EndDate);
			
		}
		
	} // END STAFF REGION
	
	function getStaffRegionsByDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$StaffsRegions = $dbh->prepare('	
									SELECT *
									FROM tbl_staffs_regions
									WHERE fld_start_date <= :Date
									AND ( fld_end_date IS NULL OR fld_end_date >= :Date2 )
									AND EXISTS(
												SELECT *
												FROM tbl_groups_regions
												WHERE tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												AND tbl_groups_regions.fld_group_id = :GroupID
												AND tbl_groups_regions.fld_start_date <= :Date3
												AND ( tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :Date4 )
												);
									');
			$StaffsRegions->execute(array(	':Date' => $Date,
											':Date2' => $Date,
											':GroupID' => $GroupID,
											':Date3' => $Date,
											':Date4' => $Date
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $StaffsRegions;
	}
	
	function updStaffRegion($StaffRegionID,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_staffs_regions
										SET fld_region_id = :RegionID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_staff_region = :StaffRegionID
										');
			$qryUpdate->execute(array(
										':RegionID' => $RegionID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':StaffRegionID' => $StaffRegionID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	
	function addStaffRegion($UserID,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_staffs_regions(fld_user_id,fld_region_id,fld_start_date,fld_end_date) 
								  VALUES (:UserID,:RegionID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':UserID' => $UserID,
								 	':RegionID' => $RegionID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function getLastStaffRegion($UserID)
	{
		global $dbh;
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_staffs_regions
									WHERE fld_user_id = :UserID
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(':UserID' => $UserID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function getStaffRegion($StaffRegionID)
	{
		global $dbh;
		
		try {
			
			$StaffRegion = $dbh->prepare('	
									SELECT *
									FROM tbl_staffs_regions
									WHERE id_staff_region = :StaffRegionID
									
									');
			$StaffRegion->execute(array(':StaffRegionID' => $StaffRegionID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $StaffRegion;
	}
	
	
	function getStaffsRegions($UserID)
	{
		global $dbh;
		
		try {
			
			$StaffsRegions = $dbh->prepare('	
									SELECT *
									FROM tbl_staffs_regions
									WHERE fld_user_id = :UserID
									ORDER BY fld_start_date DESC;
									');
			$StaffsRegions->execute(array(':UserID' => $UserID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $StaffsRegions;
	}
	
	function stats_merge_arrays($date_array,$master_date,$primary_index,&$local_index,$local_array,&$month_check,&$value)
	{
		if( $primary_index < count($local_array) and $master_date == $local_array[$local_index]['this_year'].$local_array[$local_index]['month_no'])
		{
			//'i:'.$i.' GMIDX:'.$GMIDX.' '.
			$month_check = $local_array[$local_index]['this_year'].' '.$local_array[$local_index]['this_month'];
			$value = $local_array[$local_index]['Total'];
			
			$local_index++;
			
		}
		else
		{
			$month_check = date_format($date_array[$primary_index], 'Y F');
			$value = 0;
		}
	}
	
	class Branch {
		
		private $BranchID;
		private $BranchName;
		private $BranchAbbreviation;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetBranchID()
		{
			return $this->BranchID;
		}
		
		public function SetBranchID($BranchID)
		{
			$this->BranchID = $BranchID;
		}
		
		public function GetBranchName()
		{
			return $this->BranchName;
		}
		
		public function SetBranchName($BranchName)
		{
			$this->BranchName = $BranchName;
		}
		
		public function GetBranchAbbreviation()
		{
			return $this->BranchAbbreviation;
		}
		
		public function SetBranchAbbreviation($BranchAbbreviation)
		{
			$this->BranchAbbreviation = $BranchAbbreviation;
		}
		
		public function LoadGroupsByPeriod($str_safe_s_date,$str_safe_e_date)
		{
			return Group::LoadGroupsByBranchAndPeriod($this->BranchID,$str_safe_s_date,$str_safe_e_date);
		}
		
		public static function ArrToBranch($Item)
		{
			$thisBranch = new Branch();
			
			$thisBranch->SetBranchID($Item['id_branch']);
			$thisBranch->SetBranchName($Item['fld_branch_name']);
			$thisBranch->SetBranchAbbreviation($Item['fld_branch_abbreviation']);
			
			return $thisBranch;
		}
		
		public static function LoadBranch($BranchID)
		{
			$BranchID = intval($BranchID);
			
			$pdoBranch = getBranchByID($BranchID);
			
			if($pdoBranch->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Branch::ArrToBranch($pdoBranch->fetch());
			
		}
		
		public function LoadRegions()
		{
			return Region::LoadRegionsByBranchID($this->BranchID);
		}
		
		public function TotalFieldWorkerAttendanceByBranch($StartDate,$EndDate)
		{
			return countFieldWorkersByBranchByMonths($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		
		public function TotalFormedGroupsByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountFormedGroupsByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		public function CountFormedGroupsByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadRecorder($StartDate,$EndDate) and $Group->HadOrganiser($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function CountGroupsWithRecorderByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadRecorder($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function TotalGroupsWithRecorderByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountGroupsWithRecorderByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		
		public function CountGroupsWithOrganiserByMonth($thisDate)
		{
			$StartDate = date_format($thisDate,'Y-m-d');
			$EndDate = date_format($thisDate,'Y-m-t');
			
			$AllGroups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$Count = 0;
			
			foreach($AllGroups as $Group)
			{
				if($Group->HadOrganiser($StartDate,$EndDate))
				{
					$Count++;
				}
			}
			
			return $Count;
		}
		
		public function TotalGroupsWithOrganiserByMonth($StartDate,$EndDate)
		{
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$Results = array();
			
			
			foreach( $Dates as $Date )
			{
				$Results[] = array("Total" => $this->CountGroupsWithOrganiserByMonth($Date), "this_year" => $Date->format("Y"), "this_month" => $Date->format("F"), "month_no" => $Date->format("n") );
			}
			return $Results;
		}
		
		public function TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecAttAvgInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate);
		}
		
		public function TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowerAttAvgInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate);
		}
		
		public function TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecAttInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalCommittedLapsedByMonthDatesOptimised($Start,$End)
		{
			return count8SinceLastAttendedByBranch($this->BranchID,$Start,$End);
			
		}
		
		public function TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowerAttInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalNewCommittedByMonthDates($StartDate,$EndDate)
		{
			return countNewCommittedByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countOrgAndRecInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		
		public function TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate)
		{
			return countCommittedGrowersInPeriodByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
		public function TotalCommunityObserversByMonthDates($StartDate,$EndDate)
		{
			$arrCountComObv = countAttendanceForCommunityObserversByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountComObv;
		}
		
		
		public function TotalFirstTimersByMonthDates($StartDate,$EndDate)
		{
			$arrCountFirstTimers = countFirstTimerAttendancesByBranchMonthDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountFirstTimers;
		}
		
		
		public function TotalMeetingsByMonthDatesAndTypeOther($StartDate,$EndDate)
		{
			$arrCountMeetings = countTotalMeetingsByMonthsRegionDatesAndTypeOtherBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			return $arrCountMeetings;
			
			//return $this->TotalMeetingsByMonthYearAndType($thisDate,'Organiser &amp; Recorder') + $this->TotalMeetingsByMonthYearAndType($thisDate,'Leadership');
		}
		
		public function TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,$GroupType)
		{
			$arrCountMeetings = countTotalMeetingsByMonthsBranchDatesAndType($this->BranchID,$StartDate,$EndDate,$GroupType)->fetchAll();
			
			return $arrCountMeetings;
		}
		
		
		public function LoadTrendStats($StartDate,$EndDate)
		{
			
			$Dates = funGetMonthsBetweenDates($StartDate,$EndDate);
			
			$GeneralMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'General');
			$ClosedMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'Closed');
			$SpecialMeetings = $this->TotalMeetingsByMonthDatesAndType($StartDate,$EndDate,'Special');
			
			$OtherMeetings = $this->TotalMeetingsByMonthDatesAndTypeOther($StartDate,$EndDate);
			$FirstTimers = $this->TotalFirstTimersByMonthDates($StartDate,$EndDate);
			$CommunityObservers =  $this->TotalCommunityObserversByMonthDates($StartDate,$EndDate);
			$CommittedGrowers = $this->TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorders = $this->TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate);
			$NewCommitted = $this->TotalNewCommittedByMonthDates($StartDate,$EndDate);
			$CGLapsed = $this->TotalCommittedLapsedByMonthDatesOptimised($StartDate,$EndDate); //Doesn't count Org And Rec yet
			
			$CommittedGrowAtt = $this->TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAtt = $this->TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate);
			
			$CommittedGrowAttAvg = $this->TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAttAvg = $this->TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			
			$GroupsWithOrganiser = $this->TotalGroupsWithOrganiserByMonth($StartDate,$EndDate);
			$GroupsWithRecorder = $this->TotalGroupsWithRecorderByMonth($StartDate,$EndDate);
			
			$GroupsFormed =  $this->TotalFormedGroupsByMonth($StartDate,$EndDate);
			$FieldwWorkerAtt = $this->TotalFieldWorkerAttendanceByBranch($StartDate,$EndDate);
			
			/*
			$OtherMeetings = $this->TotalMeetingsByMonthDatesAndTypeOther($StartDate,$EndDate);
			$FirstTimers = $this->TotalFirstTimersByMonthDates($StartDate,$EndDate);
			$CommunityObservers = $this->TotalCommunityObserversByMonthDates($StartDate,$EndDate);
			$CommittedGrowers = $this->TotalCommittedGrowersInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorders = $this->TotalOrgAndRecInPeriodByMonthDates($StartDate,$EndDate);
			$NewCommitted =$this->TotalNewCommittedByMonthDates($StartDate,$EndDate);
			$CGLapsed = $this->TotalCommittedLapsedByMonthDatesOptimised($StartDate,$EndDate); //Doesn't count Org And Rec yet
			
			$CommittedGrowAtt = $this->TotalCommittedGrowersAttInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAtt = $this->TotalOrgAndRecAttInPeriodByMonthDates($StartDate,$EndDate);
			
			$CommittedGrowAttAvg = $this->TotalCommittedGrowersAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			$OrgAndRecorderAttAvg = $this->TotalOrgAndRecAttAvgInPeriodByMonthDates($StartDate,$EndDate);
			
			$GroupsWithOrganiser = $this->TotalGroupsWithOrganiserByMonth($StartDate,$EndDate);
			$GroupsWithRecorder = $this->TotalGroupsWithRecorderByMonth($StartDate,$EndDate);
			
			$GroupsFormed =  $this->TotalFormedGroupsByMonth($StartDate,$EndDate);
			$FieldwWorkerAtt = $this->TotalFieldWorkerAttendanceByRegion($StartDate,$EndDate);
			*/
			
			$combined_stats = array();
			
			$GMIDX = 0;
			$GMMonthCheck = '';
			$GMValue = '';
			
			$CLIDX = 0;
			$CLMonthCheck = '';
			$CLValue = '';
			
			$SPCIDX = 0;
			$SPCMonthCheck = '';
			$SPCValue = '';
			
			$OTIDX = 0;
			$OTMonthCheck = '';
			$OTValue = '';
			
			$FTIDX = 0;
			$FTMonthCheck = '';
			$FTValue = '';
			
			$COIDX = 0;
			$COMonthCheck = '';
			$COValue = '';
			
			$CGIDX = 0;
			$CGMonthCheck = '';
			$CGValue = '';
			
			$ORIDX = 0;
			$ORMonthCheck = '';
			$ORValue = '';
			
			$NCIDX = 0;
			$NCMonthCheck = '';
			$NCValue = '';
			
			$CGLIDX = 0;
			$CGLMonthCheck = '';
			$CGLValue = '';
			
			
			$CGAIDX = 0;
			$CGAMonthCheck = '';
			$CGAValue = '';
			
			$ORAIDX = 0;
			$ORAMonthCheck = '';
			$ORAValue = '';
			
			$CGAAIDX = 0;
			$CGAAMonthCheck = '';
			$CGAAValue = '';
			
			$ORAAIDX = 0;
			$ORAAMonthCheck = '';
			$ORAAValue = '';
			
			$GWOIDX = 0;
			$GWOMonthCheck = '';
			$GWOValue = '';
			
			$GWRIDX = 0;
			$GWRMonthCheck = '';
			$GWRValue = '';
			
			$GFIDX = 0;
			$GFMonthCheck = '';
			$GFValue = '';
			
			$FWAIDX = 0;
			$FWAMonthCheck = '';
			$FWAValue = '';
			
			for( $i = 0; $i < count($Dates); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$Date_Code = date_format($Dates[$i], 'Yn');
				
				stats_merge_arrays($Dates,$Date_Code,$i,$GMIDX,$GeneralMeetings,$GMMonthCheck,$GMValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$CLIDX,$ClosedMeetings,$CLMonthCheck,$CLValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$SPCIDX,$SpecialMeetings,$SPCMonthCheck,$SPCValue);
				
				stats_merge_arrays($Dates,$Date_Code,$i,$OTIDX,$OtherMeetings,$OTMonthCheck,$OTValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$FTIDX,$FirstTimers,$FTMonthCheck,$FTValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$COIDX,$CommunityObservers,$COMonthCheck,$COValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$CGIDX,$CommittedGrowers,$CGMonthCheck,$CGValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$ORIDX,$OrgAndRecorders,$ORMonthCheck,$ORValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$NCIDX,$NewCommitted,$NCMonthCheck,$NCValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$CGLIDX,$CGLapsed,$CGLMonthCheck,$CGLValue);
				
				stats_merge_arrays($Dates,$Date_Code,$i,$CGAIDX,$CommittedGrowAtt,$CGAMonthCheck,$CGAValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$ORAIDX,$OrgAndRecorderAtt,$ORAMonthCheck,$ORAValue);
				
				stats_merge_arrays($Dates,$Date_Code,$i,$CGAAIDX,$CommittedGrowAttAvg,$CGAAMonthCheck,$CGAAValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$ORAAIDX,$OrgAndRecorderAttAvg,$ORAAMonthCheck,$ORAAValue);
				
				stats_merge_arrays($Dates,$Date_Code,$i,$GWOIDX,$GroupsWithOrganiser,$GWOMonthCheck,$GWOValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$GWRIDX,$GroupsWithRecorder,$GWRMonthCheck,$GWRValue);
				stats_merge_arrays($Dates,$Date_Code,$i,$GFIDX,$GroupsFormed,$GFMonthCheck,$GFValue);
				
				stats_merge_arrays($Dates,$Date_Code,$i,$FWAIDX,$FieldwWorkerAtt,$FWAMonthCheck,$FWAValue);
				
				$combined_stats[$i] = array(
											"Name" => date_format($Dates[$i],"F Y"),
											//"MAName" => $GMMonthCheck,
											"MA" => $GMValue,
											//"CLName" => $CLMonthCheck,
											"CL" => $CLValue,
											//"SPCName" => $SPCMonthCheck,
											"SPC" => $SPCValue,
											//"OTName" => $OTMonthCheck,
											"OT" => $OTValue,
											//"FTName" => $FTMonthCheck,
											"FT" => $FTValue,
											//"COName" => $COMonthCheck,
											"CO" => $COValue,
											//"CGName" => $CGMonthCheck,
											"CG" => $CGValue + $ORValue,
											//"NCName" => $NCMonthCheck,
											"NC" => $NCValue,
											//"CGLName" => $CGLMonthCheck,
											"CGL" => $CGLValue,
											//"CGAName" => $CGAMonthCheck,
											"CGA" => $CGAValue + $ORAValue,
											//"CGAAName" => $CGAAMonthCheck,
											"CGAA" => $CGAAValue + $ORAAValue,
											//"GWOName" => $GWOMonthCheck,
											"GWO" => $GWOValue,
											//"GWRName" => $GWRMonthCheck,
											"GWR" => $GWRValue,
											//"GFName" => $GFMonthCheck,
											"GF" => $GFValue,
											//"FWAName" => $FWAMonthCheck,
											"FWA" => $FWAValue
											);
			}
			
			return $combined_stats;
		}
		
		public function LoadStatisticsByBranch($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByBranch($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByBranch($StartDate,$EndDate);
			
			$FTGrowers = $this->CountFirstTimerGrowersByBranchBetweenDates($StartDate,$EndDate);
			$FTGrowersCont = $this->CountFirstTimerGrowersContByBranchBetweenDates(2,$StartDate,$EndDate);
			$NCGrowers = $this->CountNewCommittedGrowersByBranchBetweenDates($StartDate,$EndDate);
			$LCGrowers = $this->CountLapsedCommittedGrowersByBranchBetweenDates(3,$StartDate,$EndDate);
			$AEPCGrowers = $this->CountComGrowAttendeesAtEndByBranch($StartDate,$EndDate);
			
			$FWAttendees = $this->CountFieldWorkerAttendancesInPeriodByBranch($StartDate,$EndDate);
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"FTGrow" => $FTGrowers[$i]['Name'], 
											"FTG" => $FTGrowers[$i]['Count'],
											
											"FTCGrow" => $FTGrowersCont[$i]['Name'], 
											"FTCG" => $FTGrowersCont[$i]['Count'],
											
											"NCGrow" => $NCGrowers[$i]['Name'], 
											"NCG" => $NCGrowers[$i]['Count'],
											
											"LCGrow" => $LCGrowers[$i]['Name'], 
											"LCG" => $LCGrowers[$i]['Count'],
											
											"AEPCGrow" => $AEPCGrowers[$i]['Name'], 
											"AEPCG" => $AEPCGrowers[$i]['Count'],
											
											"FWAttName" => $FWAttendees[$i]['Name'], 
											"FWAtt" => $FWAttendees[$i]['Count'],
											

											);
			}
			
			return $combined_stats;
		}
		
		public function CountFieldWorkerAttendeesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->FieldWorkerAttendeesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountComGrowAttendeesAtEndByBranch($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountComGrowAttAtEnd($EndDate));
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountLapsedCommittedGrowersByBranchBetweenDates($WksLapsed,$StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountNSinceLastAttendance($WksLapsed,$StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountNewCommittedGrowersByBranchBetweenDates($StartDate,$EndDate)
		{
			$arrFirstTimers = getNewCommittedGrowersByBranchBetweenDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['Total_Aees']);
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountFirstTimerGrowersContByBranchBetweenDates($AttendedTimes,$StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				
				$result = $group->CountFirstTimerMultipleAttendancesByGroupDates($StartDate,$EndDate,$AttendedTimes);
				
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $result['Total_Aees']);
				
				$i++;
			}
			
			return $index_array;

		}
		
		public function CountFirstTimerGrowersByBranchBetweenDates($StartDate,$EndDate)
		{
			$arrFirstTimers = getCountFirstTimerGrowersInPeriodByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['first_timers']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function LoadGroupAttendanceStatsByBranch($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByBranch($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByBranch($StartDate,$EndDate);
			
			$MemberAttendances = $this->CountMemberAttendancesInPeriodByBranch($StartDate,$EndDate);
			
			$OrganiserAttendances = $this->CountVolunteerAttendancesInPeriodByBranchRole(ORGANISER,$StartDate,$EndDate);
			$RecorderAttendances = $this->CountVolunteerAttendancesInPeriodByBranchRole(RECORDER,$StartDate,$EndDate);
			$SponsorAttendances = $this->CountVolunteerAttendancesInPeriodByBranchRole(SPONSOR,$StartDate,$EndDate);
			
			$CommunityObservers = $this->CountCommunityObserversInPeriodByBranch($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"MemName" => $MemberAttendances[$i]['Name'], 
											"Mem" => $MemberAttendances[$i]['Count'],
											
											"OrgName" => $OrganiserAttendances[$i]['Name'], 
											"Org" => $OrganiserAttendances[$i]['Count'],
											
											"RecName" => $RecorderAttendances[$i]['Name'], 
											"Rec" => $RecorderAttendances[$i]['Count'],
											
											"SpoName" => $SponsorAttendances[$i]['Name'], 
											"Spo" => $SponsorAttendances[$i]['Count'],
											
											"ComName" => $CommunityObservers[$i]['Name'], 
											"Com" => $CommunityObservers[$i]['Count']
											

											);
			}
			
			return $combined_stats;
		}
		
		
		public function LoadGroupAttendeeStatsByBranch($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$Total = 0;
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByBranch($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByBranch($StartDate,$EndDate);
			
			$MemberAttendees = $this->CountMemberAttendeesInPeriodByBranch($StartDate,$EndDate);
			
			$OrganiserAttendees = $this->CountVolunteerAttendeesInPeriodByBranchRole(ORGANISER,$StartDate,$EndDate);
			$RecorderAttendees = $this->CountVolunteerAttendeesInPeriodByBranchRole(RECORDER,$StartDate,$EndDate);
			$SponsorAttendees = $this->CountVolunteerAttendeesInPeriodByBranchRole(SPONSOR,$StartDate,$EndDate);
			
			$CommunityObservers = $this->CountCommunityObserversInPeriodByBranch($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											
											"MemName" => $MemberAttendees[$i]['Name'], 
											"Mem" => $MemberAttendees[$i]['Count'],
											
											"OrgName" => $OrganiserAttendees[$i]['Name'], 
											"Org" => $OrganiserAttendees[$i]['Count'],
											
											"RecName" => $RecorderAttendees[$i]['Name'], 
											"Rec" => $RecorderAttendees[$i]['Count'],
											
											"SpoName" => $SponsorAttendees[$i]['Name'], 
											"Spo" => $SponsorAttendees[$i]['Count'],
											
											"ComName" => $CommunityObservers[$i]['Name'], 
											"Com" => $CommunityObservers[$i]['Count']
											

											);
			}
			
			return $combined_stats;
		}
		
		public function LoadGroupStats($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$MeetingsAttended = $this->CountMeetingsAttendedInPeriodByBranch($StartDate,$EndDate);
			$MeetingsScheduled = $this->CountMeetingsScheduledInPeriodByBranch($StartDate,$EndDate);
			
			$FirstTimers = $this->CountFirstTimersInPeriodByBranch($StartDate,$EndDate);
			$CommunityObservers = $this->CountCommunityObserversInPeriodByBranch($StartDate,$EndDate);
			$CommittedGrowers = $this->CountCommittedGrowersInPeriodByBranch($StartDate,$EndDate);
			
			$NewCommittedGrowers = $this->CountNewCommittedGrowersInPeriodByBranch($StartDate,$EndDate);
			$NSinceLastAttended = $this->CountNSinceLastAttendedByBranch(8,$StartDate,$EndDate);
			
			$CGAttendances = $this->CountCommittedGrowerAttendancesByBranch($StartDate,$EndDate);
			$OrgAttendances = $this->CountOrganiserAttendancesInPeriodByBranch($StartDate,$EndDate);
			$RecAttendances = $this->CountRecorderAttendancesInPeriodByBranch($StartDate,$EndDate);
			$FWAttendances = $this->CountFieldWorkerAttendancesInPeriodByBranch($StartDate,$EndDate);
			$TotAttees = $this->CountTotalAttendeesInPeriodByBranch($StartDate,$EndDate);
			$TotAtes = $this->CountTotalAttendancesInPeriodByBranch($StartDate,$EndDate);
			
			$combined_stats = array();
			
			for( $i = 0; $i < count($Groups); $i++ )
			{
				//$combined_stats[] = array("Name" => $Groups[$i]->GetGroupName(),"MAName" => $MeetingsAttended[$i]['fld_group_name'],"MA" => $MeetingsAttended[$i]['total_meetings']);
				
				$combined_stats[$i] = array(
											"Name" => $Groups[$i]->GetGroupName(),
											"MAName" => $MeetingsAttended[$i]['Name'],
											"MA" => $MeetingsAttended[$i]['Meetings'],
											"MESCHName" => $MeetingsScheduled[$i]['Name'], 
											"MESCH" => $MeetingsScheduled[$i]['MeSch'],
											"TFTName" => $FirstTimers[$i]['Name'], 
											"TFT" => $FirstTimers[$i]['Count'],
											"ComObsName" => $CommunityObservers[$i]['Name'], 
											"ComObs" => $CommunityObservers[$i]['Count'],
											"ComGrowName" => $CommittedGrowers[$i]['Name'], 
											"ComGrow" => $CommittedGrowers[$i]['Count'],
											"NewComGrowName" => $NewCommittedGrowers[$i]['Name'], 
											"NewComGrow" => $NewCommittedGrowers[$i]['Count'],
											"CGLapsedName" => $NSinceLastAttended[$i]['Name'], 
											"CGLapsed" => $NSinceLastAttended[$i]['Count'],
											"CGAttendancesName" => $CGAttendances[$i]['Name'], 
											"CGAttendances" => $CGAttendances[$i]['Count'],
											"OrgAttName" => $OrgAttendances[$i]['Name'], 
											"OrgAtt" => $OrgAttendances[$i]['Count'],
											"RecAttName" => $RecAttendances[$i]['Name'], 
											"RecAtt" => $RecAttendances[$i]['Count'],
											"FWAttName" => $FWAttendances[$i]['Name'], 
											"FWAtt" => $FWAttendances[$i]['Count'],
											"TotAtteesName" => $TotAttees[$i]['Name'], 
											"TotAttees" => $TotAttees[$i]['Count'],
											"TotAtesName" => $TotAtes[$i]['Name'], 
											"TotAtes" => $TotAtes[$i]['Count']

											);
			}
			
			return $combined_stats;
		}
		
		
		public function CountTotalAttendancesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->TotalAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountTotalAttendeesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->TotalAttendeesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountFieldWorkerAttendancesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->FieldWorkerAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountRecorderAttendancesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->RecorderAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountOrganiserAttendancesInPeriodByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->OrganiserAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountCommittedGrowerAttendancesByBranch($StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CommittedGrowerAttendancesInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		public function CountNSinceLastAttendedByBranch($NoMeetings,$StartDate,$EndDate)
		{
			
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $Groups as $group )
			{
				$index_array[$i] = array("Name" => $group->GetGroupName(), "Count" => $group->CountNSinceLastAttended($NoMeetings,$StartDate,$EndDate));
				
				$i++;
			}
			
			return $index_array;
			
			
		}
		
		
		public function CountNewCommittedGrowersInPeriodByBranch($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrNewCom = getCountNewCommittedGrowersInPeriodByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrNewCom as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['new_com']);
				
				$i++;
			}
			
			return $index_array;
		}
			
		
		public function CountCommittedGrowersInPeriodByBranch($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrComGrow = getCountCommittedGrowersInPeriodByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrComGrow as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['com_grow'] + $group['org_rec']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		
		public function CountCommunityObserversInPeriodByBranch($StartDate,$EndDate) //Remember that community observers are not unique
		{
			$arrComObs = getAttendanceForCommunityObserversByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrComObs as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['com_obs']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		
		public function CountFirstTimersInPeriodByBranch($StartDate,$EndDate)
		{
			$arrFirstTimers = getCountFirstTimersInPeriodByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrFirstTimers as $group )
			{
				$index_array[$i] = array("Name" => $group['fld_group_name'], "Count" => $group['first_timers']);
				
				$i++;
			}
			
			return $index_array;
			
		}
		
		public function CountMeetingsScheduledInPeriodByBranch($StartDate,$EndDate)
		{
			$Groups = $this->LoadGroupsByPeriod($StartDate,$EndDate);
			
			$MeetingsScheduled = array();
			
			$i = 0;
			
			foreach($Groups as $Group)
			{
				
				$MeetingsScheduled[$i] = array("Name" => $Group->GetGroupName(),"MeSch" => $Group->CountMeetingsScheduledInPeriod($StartDate,$EndDate));
				
				$i++;
			}
			
			return $MeetingsScheduled;
		}
		
		
		public function CountMeetingsAttendedInPeriodByBranch($StartDate,$EndDate)
		{
			$arrCountMeetings = getCountMeetingsAttendedInPeriodByBranch($this->BranchID,$StartDate,$EndDate)->fetchAll();
			
			$index_array = array();
			
			$i = 0;
			
			foreach( $arrCountMeetings as $meeting )
			{
				$index_array[$i] = array("Name" => $meeting['fld_group_name'], "Meetings" => $meeting['total_meetings']);
				
				$i++;
			}
			
			return $index_array;
		}
		
		public function CountVolunteerAttendancesInPeriodByBranchRole($Role,$StartDate,$EndDate)
		{
			return \Membership\Staff::CountVolunteerAttendancesByBranch($Role,$this->BranchID,$StartDate,$EndDate);
			
		}
			
		public function CountVolunteerAttendeesInPeriodByBranchRole($Role,$StartDate,$EndDate)
		{
			return \Membership\Staff::CountVolunteersByBranchAttendance($Role,$this->BranchID,$StartDate,$EndDate);
			
		}
		
		public function CountMemberAttendeesInPeriodByBranch($StartDate,$EndDate)
		{
			return \Membership\Member::CountMembersByBranchAttendance($this->BranchID,$StartDate,$EndDate);
			
		}
		
		public function CountMemberAttendancesInPeriodByBranch($StartDate,$EndDate)
		{
			return \Membership\Member::CountMemberAttendancesByBranch($this->BranchID,$StartDate,$EndDate);
			
		}
		
		public function LoadBranchVolunteerLabels($StartDate,$EndDate)
		{
			return getVolunteerLabelsByBranchDates($this->BranchID,$StartDate,$EndDate)->fetchAll();
		}
		
	} // End Branch Class
	
	function getVolunteerLabelsByBranchDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
					
		try {
			
			$Labels = $dbh->prepare("	
									SELECT tbl_staff.*, tbl_states.fld_state_name, tbl_states.fld_state_abbreviation
									FROM tbl_staff
									JOIN tbl_user_activity_dates
									ON tbl_staff.fld_user_id = tbl_user_activity_dates.fld_user_id
									JOIN tbl_staff_roles
									ON tbl_staff_roles.id_staff_role = tbl_user_activity_dates.fld_staff_type_id
									JOIN tbl_states
									ON tbl_staff.fld_state_id = tbl_states.id_state
									WHERE tbl_staff.fld_address != ''
									AND tbl_staff.fld_suburb != ''
									AND tbl_staff.fld_postcode != ''
									AND tbl_staff_roles.fld_staff_vol = :volunteer
									AND tbl_user_activity_dates.fld_start_date <= :EndDate
									AND ( tbl_user_activity_dates.fld_end_date IS NULL
										OR tbl_user_activity_dates.fld_end_date >= :StartDate
										)
									AND EXISTS(
												SELECT *
												FROM tbl_groups_roles
												WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
												AND tbl_groups_roles.fld_start_date <= :EndDate2
												AND ( tbl_groups_roles.fld_end_date IS NULL
													OR tbl_groups_roles.fld_end_date >= :StartDate2
													) 
												AND tbl_groups_roles.fld_deleted = 0
												AND fld_group_id IN(
																	SELECT fld_group_id
																	FROM tbl_groups_regions
																	JOIN tbl_regions
																	ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
																	WHERE tbl_regions.fld_branch_id = :BranchID
																	AND tbl_groups_regions.fld_deleted = 0
																	AND tbl_groups_regions.fld_start_date <= :EndDate3
																	AND ( tbl_groups_regions.fld_end_date IS NULL
																		OR tbl_groups_regions.fld_end_date >= :StartDate3
																		) 
																	)
												)
									
									");
			
				$Labels->execute(array(	
											':volunteer' => VOLUNTEER,
											':EndDate' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate' => ($StartDate == 'null') ? null : $StartDate,
											':EndDate2' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate2' => ($StartDate == 'null') ? null : $StartDate,
											':BranchID' => $BranchID,
											':EndDate3' => ($EndDate == 'null') ? null : $EndDate,
											':StartDate3' => ($StartDate == 'null') ? null : $StartDate
											 ));
				
				
			
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Labels;
	}
	
	function getNewCommittedGrowersByBranchBetweenDates($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									
									SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total_Aees
																FROM tbl_group_attendance
																JOIN tbl_user_activity_dates
																ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance.fld_deleted = 0
																AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_user_activity_dates.fld_deleted = 0
																AND tbl_user_activity_dates.fld_user_type_string = :Member
																AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																AND ( 
																	tbl_user_activity_dates.fld_end_date IS NULL 
																	OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																	)
																AND EXISTS(
																			SELECT *
																			FROM tbl_member_committed_dates
																			WHERE tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
																			AND fld_start_date >= :StartDate2
																			AND fld_start_date <= :EndDate2
																			AND tbl_member_committed_dates.fld_deleted = 0
																			)
																) AS Total_Aees
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID
																		AND tbl_regions.fld_deleted = 0
																		)
													AND fld_start_date <= :EndDate3
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate3)
													AND tbl_groups_regions.fld_deleted = 0			
													)
									
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':Member' => MEMBER_STRING,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate,
									':BranchID' => $BranchID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getCountFirstTimerGrowersInPeriodByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id)
																FROM tbl_group_attendance
																JOIN tbl_user_activity_dates
																ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance.fld_deleted = 0
																AND fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_group_attendance.fld_user_id IN(
																					SELECT Att2.fld_user_id
																					FROM tbl_group_attendance AS Att2
																					JOIN tbl_user_activity_dates AS Act2
																					ON Att2.fld_user_id = Act2.fld_user_id
																					WHERE tbl_group_attendance.fld_group_id = Att2.fld_group_id
																					AND Att2.fld_deleted = 0
																					AND Att2.fld_date BETWEEN :StartDate2 AND :EndDate2
																					AND (fld_date,Att2.fld_user_id) IN(
																													SELECT MIN(fld_date),fld_user_id 
																													FROM tbl_group_attendance
																													GROUP BY fld_user_id
																													)
																					AND Act2.fld_deleted = 0
																					AND Act2.fld_user_type_string = :Member
																					AND Att2.fld_date >= Act2.fld_start_date
																					AND ( 
																						Act2.fld_end_date IS NULL 
																						OR Att2.fld_date <= Act2.fld_end_date
																						)
																					)
																AND tbl_user_activity_dates.fld_deleted = 0
																AND tbl_user_activity_dates.fld_user_type_string = :Member2
																AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																
																AND ( 
																	tbl_user_activity_dates.fld_end_date IS NULL 
																	OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																	)
																GROUP BY fld_group_id
																) AS first_timers
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID
																		AND tbl_regions.fld_deleted = 0
																		)
													AND fld_start_date <= :EndDate3
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate3)
													AND tbl_groups_regions.fld_deleted = 0			
													)
									
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate,
									':Member' => MEMBER_STRING,
									':Member2' => MEMBER_STRING,
									':BranchID' => $BranchID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getCountNewCommittedGrowersInPeriodByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT fld_group_name, ( SELECT COUNT(DISTINCT tbl_members.fld_user_id )
															FROM tbl_group_attendance
															LEFT OUTER JOIN tbl_members
															ON tbl_members.fld_user_id = tbl_group_attendance.fld_user_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND EXISTS(
																		SELECT *
																		FROM tbl_member_committed_dates
																		WHERE tbl_member_committed_dates.fld_user_id = tbl_members.fld_user_id
																		AND fld_start_date BETWEEN :StartDate3 AND :EndDate3
																		AND tbl_member_committed_dates.fld_deleted = 0
																		)
															GROUP BY tbl_group_attendance.fld_group_id
															)AS new_com
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID
																		AND tbl_regions.fld_deleted = 0
																		)
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = 0			
													)
									
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':StartDate3' => $StartDate,
											':EndDate3' => $EndDate,
											':BranchID' => $BranchID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	
	function getCountCommittedGrowersInPeriodByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		//global $Organiser;
		//global $Recorder;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									
									
									SELECT fld_group_name, (
																SELECT COUNT(DISTINCT tbl_members.fld_user_id)
																FROM tbl_group_attendance
																JOIN tbl_members
																ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
																WHERE fld_date BETWEEN :StartDate AND :EndDate
																AND fld_deleted = :false
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND tbl_members.fld_first_name != :CommunityObserver
																AND EXISTS(
																			SELECT *
																			FROM tbl_member_committed_dates
																			WHERE tbl_member_committed_dates.fld_user_id = tbl_members.fld_user_id
																			AND fld_start_date <= :EndDate2
																			AND (fld_end_date IS NULL 
																				OR fld_end_date >= :StartDate2
																				)
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS com_grow,
																(
																SELECT COUNT(tbl_group_attendance.fld_user_id)
																FROM tbl_group_attendance
																WHERE fld_date BETWEEN :StartDate3 AND :EndDate3
																AND fld_deleted = :false2
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND EXISTS(
																			SELECT * 
																			FROM tbl_groups_roles
																			JOIN tbl_group_roles
																			ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
																			WHERE tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
																			AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
																			AND (tbl_group_roles.fld_group_role = :Organiser OR tbl_group_roles.fld_group_role = :Recorder)
																			AND tbl_groups_roles.fld_start_date <= :EndDate4

																			AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate4 )
																			AND tbl_groups_roles.fld_deleted = 0
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS org_rec
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID
																		AND tbl_regions.fld_deleted = :false3
																		)
													AND fld_start_date <= :EndDate5
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate5)
													AND tbl_groups_regions.fld_deleted = :false4					
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false5
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':false' => $false,
											':CommunityObserver' => $CommunityObserver,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											':StartDate3' => $StartDate,
											':EndDate3' => $EndDate,
											':false2' => $false,
											':Organiser' => ORGANISER,
											':Recorder' => RECORDER,
											':EndDate4' => $EndDate,
											':StartDate4' => $StartDate,
											':BranchID' => $BranchID,
											':false3' => $false,
											':EndDate5' => $EndDate,
											':StartDate5' => $StartDate,
											':false4' => $false,
											':false5' => $false
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	
	function getAttendanceForCommunityObserversByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									
									
									SELECT fld_group_name, (
																SELECT SUM(fld_no_attendees)
																FROM tbl_group_attendance_other
																JOIN tbl_groups_regions
																ON tbl_group_attendance_other.fld_group_id = tbl_groups_regions.fld_group_id
																WHERE fld_date BETWEEN :StartDate AND :EndDate
																AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
																AND tbl_group_attendance_other.fld_group_id = tbl_groups.id_group
																AND tbl_group_attendance_other.fld_date >= tbl_groups_regions.fld_start_date
																AND (
																	tbl_groups_regions.fld_end_date IS NULL OR
																	tbl_group_attendance_other.fld_date <= tbl_groups_regions.fld_end_date
																	)
																AND tbl_groups_regions.fld_deleted = 0
																GROUP BY tbl_group_attendance_other.fld_group_id
																) AS com_obs
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID2
																		AND tbl_regions.fld_deleted = 0
																		)
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = 0					
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Attendances->execute(array(	':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':BranchID' => $BranchID,
											':BranchID2' => $BranchID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	
	function getCountFirstTimersInPeriodByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('	
									SELECT fld_group_name, (
																SELECT COUNT(*)
																FROM tbl_group_attendance
																WHERE (fld_group_id,fld_date,fld_user_id) IN(
																	SELECT fld_group_id,MIN(fld_date),fld_user_id 
																	FROM tbl_group_attendance
																	WHERE fld_deleted = false
																	GROUP BY fld_user_id
																)
																AND fld_date BETWEEN :StartDate AND :EndDate
																AND fld_deleted = :false
																AND tbl_group_attendance.fld_group_id = tbl_groups.id_group
																AND EXISTS(
																			SELECT *
																			FROM tbl_user_activity_dates
																			WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
																			AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
																			AND (
																				tbl_user_activity_dates.fld_end_date IS NULL 
																				OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
																				)
																			AND fld_user_type_string = :MEMBER_STRING
																			AND tbl_user_activity_dates.fld_deleted = 0
																			)
																GROUP BY tbl_group_attendance.fld_group_id
																) AS first_timers
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID
																		AND tbl_regions.fld_deleted = :false2
																		)
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = :false3				
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = :false4
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									');
			$Count->execute(array(	
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':false' => $false,
									':MEMBER_STRING' => MEMBER_STRING,
									':BranchID' => $BranchID,
									':false2' => $false,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':false3' => $false,
									':false4' => $false
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	
	function getCountMeetingsAttendedInPeriodByBranch($BranchID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Count = $dbh->prepare('
									SELECT fld_group_name, ( SELECT COUNT(DISTINCT fld_date)
															FROM tbl_group_attendance
															JOIN tbl_groups_regions
															ON tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
															WHERE tbl_group_attendance.fld_group_id = tbl_groups.id_group
															AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
															AND tbl_groups_regions.fld_region_id IN(
																									SELECT id_region
																									FROM tbl_regions
																									WHERE fld_branch_id = :BranchID
																									AND tbl_regions.fld_deleted = 0
																									)
															AND fld_date >= tbl_groups_regions.fld_start_date
															AND (
																tbl_groups_regions.fld_end_date IS NULL
																OR fld_date <= tbl_groups_regions.fld_end_date
																)
															AND tbl_group_attendance.fld_deleted = 0
															AND tbl_groups_regions.fld_deleted = 0
															) AS total_meetings
									FROM tbl_groups
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id IN(
																		SELECT id_region
																		FROM tbl_regions
																		WHERE fld_branch_id = :BranchID2
																		AND tbl_regions.fld_deleted = 0
																		)
													AND fld_start_date <= :EndDate2
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate2)
													AND tbl_groups_regions.fld_deleted = 0			
													)
									AND fld_non_group_type IS NULL
									AND tbl_groups.fld_deleted = 0
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Count->execute(array(	':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':BranchID' => $BranchID,
									':BranchID2' => $BranchID,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Count;
	}
	
	function getBranchByID($BranchID)
	{
		global $dbh;
		try {
				$Branch = $dbh->prepare('	SELECT * 
											FROM tbl_branches
											WHERE id_branch = :BranchID');
				$Branch->execute(array(':BranchID' => $BranchID ));
			
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
			
		return $Branch;
	}
	
	class Venue {
		
		private $VenueID;
		private $Name;
		private $Address;
		private $Suburb;
		private $State;
		private $PostCode;
		private $Comments;
		private $Contract;
		private $StartDate;
		private $EndDate;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetVenueID()
		{
			return $this->VenueID;
		}
		
		public function SetVenueID($VenueID)
		{
			$this->VenueID = $VenueID;
		}
		
		public function GetName()
		{
			return $this->Name;
		}
		
		public function SetName($Name)
		{
			$this->Name = $Name;
		}
		
		public function GetAddress()
		{
			return $this->Address;
		}
		
		public function SetAddress($Address)
		{
			$this->Address = $Address;
		}
		
		public function GetSuburb()
		{
			return $this->Suburb;
		}
		
		public function SetSuburb($Suburb)
		{
			$this->Suburb = $Suburb;
		}
		
		public function GetState()
		{
			return $this->State;
		}
		
		public function SetState($State)
		{
			$this->State = $State;
		}
		
		public function GetStateAbbreviation()
		{
			return getStateAbbreviation($this->State);
		}
		
		public function GetPostCode()
		{
			return $this->PostCode;
		}
		
		public function SetPostCode($PostCode)
		{
			$this->PostCode = $PostCode;
		}
		
		public function GetComments()
		{
			return $this->Comments;
		}
		
		public function SetComments($Comments)
		{
			$this->Comments = $Comments;
		}
		
		public function GetContract()
		{
			return $this->Contract;
		}
		
		public function SetContract($Contract)
		{
			$this->Contract = $Contract;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		//END GETTERS AND SETTERS
		
		public function LoadAuditDates()
		{
			return VenueAuditDate::LoadAuditDatesByVenue($this->VenueID);
		}
		
		public function LastAudited()
		{
			return VenueAuditDate::LoadVenuesLastCompleteAudit($this->VenueID);
		}
		
		public function NextAudit()
		{
			return VenueAuditDate::LoadVenueNextAudit($this->VenueID);
		}
		
		public static function LoadVenueAuditsDue($months_till_due)
		{
			$months_till_due = intval($months_till_due);
			
			$arrVenues = getVenueAuditsDue($months_till_due)->fetchAll();
			
			$Venues = array();
			
			foreach( $arrVenues as $Venue )
			{		
				$Venues[] = Venue::ArrToVenue($Venue);
			}
			
			return $Venues;
		}
		
		public static function LoadAllVenues($Active = true)
		{
			$arrVenues = getAllVenues($Active)->fetchAll();
			
			$Venues = array();
			
			foreach( $arrVenues as $Venue )
			{
				$Venues[] = Venue::ArrToVenue($Venue);
			}
			
			return $Venues;
		}
				
		public static function LoadVenue($VenueID)
		{
			$VenueID = intval($VenueID);
			
			$pdoVenue = getVenue($VenueID);
			
			if($pdoVenue->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Venue::ArrToVenue($pdoVenue->fetch());
		}
		
		public static function LoadGroupsLastRegion($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupRegion = getLastGroupRegion($GroupID);
			
			if($pdoGroupRegion->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupRegion::ArrToGroupRegion($pdoGroupRegion->fetch());
		}
		
		public static function GroupHasRegion($GroupID)
		{
			return GroupRegion::LoadGroupsLastRegion($GroupID) != NULL;
		}
		
		public static function ArrToVenue($Item)
		{
			$thisVenue = new Venue();
			
			$thisVenue->SetVenueID($Item['id_venue']);
			$thisVenue->SetName($Item['fld_venue_name']);
			$thisVenue->SetAddress($Item['fld_venue_address']);
			$thisVenue->SetSuburb($Item['fld_venue_suburb']);
			$thisVenue->SetState($Item['fld_venue_state']);
			$thisVenue->SetPostCode($Item['fld_venue_postcode']);
			$thisVenue->SetComments($Item['fld_venue_note']);
			$thisVenue->SetContract($Item['fld_contract']);
			$thisVenue->SetStartDate($Item['fld_start_date']);
			$thisVenue->SetEndDate($Item['fld_end_date']);
			
			return $thisVenue;
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function GetRegion()
		{
			return Region::LoadRegion($this->RegionID);
		}
		
		public static function CreateVenue(
											$Name,
											$Address,
											$Suburb,
											$State,
											$PostCode,
											$Comments,
											$Contract,
											$SafeSD,
											$SafeED
											)
		{
			$VenueID = addVenue(
								$Name,
								$Address,
								$Suburb,
								$State,
								$PostCode,
								$Comments,
								$Contract,
								$SafeSD,
								$SafeED
								);
			
			return Venue::LoadVenue($VenueID);
		}
		
		public function UpdateVenue(
									$Name,
									$Address,
									$Suburb,
									$State,
									$PostCode,
									$Comments,
									$SafeContract,
									$SafeSD,
									$SafeED
									)
		{
			
			$this->Name = $Name;
			$this->Address = $Address;
			$this->Suburb = $Suburb;
			$this->State = $State;
			$this->PostCode = $PostCode;
			$this->Comments = $Comments;
			$this->Contract = $SafeContract;
			$this->StartDate = $SafeSD;
			$this->EndDate = $SafeED;
			
			updVenue(
					$this->VenueID,
					$this->Name,
					$this->Address,
					$this->Suburb,
					$this->State,
					$this->PostCode,
					$this->Comments,
					$this->Contract,
					$this->StartDate,
					$this->EndDate
					);
			
		}
		
	} // END VENUE
	
	function getVenueAuditsDue($months_till_due)
	{
		global $dbh;
		global $false;
		
		try {
			$Venues = $dbh->prepare("
									SELECT tbl_venues.*, MAX(tbl_venue_audit_dates.fld_audit_date) AS next_due
									FROM tbl_venues
									JOIN tbl_venue_audit_dates
									ON tbl_venues.id_venue = tbl_venue_audit_dates.fld_venue_id
									AND tbl_venue_audit_dates.fld_complete = :false
									AND tbl_venue_audit_dates.fld_audit_date <= DATE_ADD(DATE(NOW()), INTERVAL :months_till_due MONTH)
									GROUP BY tbl_venues.id_venue
									ORDER BY tbl_venue_audit_dates.fld_audit_date ASC
									");
			$Venues->execute(array(	
									':false' => $false,
									':months_till_due' => $months_till_due
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Venues;
	}
	
	function getStateAbbreviation($StateID)
	{
		global $dbh;
		
		try {
				$StateAbr = $dbh->prepare('SELECT fld_state_abbreviation 
										FROM tbl_states 
										WHERE id_state = :StateID
										');
				$StateAbr->execute(array(':StateID' => $StateID ));
					
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
			
		
		$arrStateAbr = $StateAbr->fetch();
		
		return $arrStateAbr['fld_state_abbreviation'];
	}
	
	function addVenue(
						$Name,
						$Address,
						$Suburb,
						$State,
						$PostCode,
						$Comments,
						$Contract,
						$SafeSD,
						$SafeED
						)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_venues(
																fld_venue_name,
																fld_venue_address,
																fld_venue_suburb,
																fld_venue_state,
																fld_venue_postcode,
																fld_venue_note,
																fld_contract,
																fld_start_date,
																fld_end_date
																) 
									  VALUES (
									  			:Name,
									  			:Address,
												:Suburb,
												:State,
												:PostCode,
												:Comments,
												:Contract,
												:SafeSD,
												:SafeED
												) 
										');
			$qryInsert->execute(array(
										':Name' => $Name,
										':Address' => $Address, 
										':Suburb' => $Suburb, 
										':State' => $State,
										':PostCode' => $PostCode,
										':Comments' => $Comments,
										':Contract' => $Contract,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function updVenue(
						$VenueID,
						$Name,
						$Address,
						$Suburb,
						$State,
						$PostCode,
						$Comments,
						$Contract,
						$SafeSD,
						$SafeED
						)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_venues SET
										fld_venue_name = :Name,
										fld_venue_address = :Address,
										fld_venue_suburb = :Suburb,
										fld_venue_state = :State,
										fld_venue_postcode = :PostCode,
										fld_venue_note = :Comments,
										fld_contract = :Contract,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_venue = :VenueID
										');
			$qryUpdate->execute(array(
										':Name' => $Name,
										':Address' => $Address, 
										':Suburb' => $Suburb, 
										':State' => $State,
										':PostCode' => $PostCode,
										':Comments' => $Comments,
										':Contract' => $Contract,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':VenueID' => $VenueID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	}
	
	/* Venue Name specific error reporting function */
	function CreateErrorMessageVenueName($VenueID,$Msg,$VenueName,$ErrorName,$SelfCheck)
	{
		global $blnIsGood;
		
		if( $Msg != 'good')
		{
			\Validation\CreateErrorMessage($Msg,$ErrorName);
			
		} else {
			//collect list of usernames
			if($SelfCheck)
			{
				$Venue = chkDuplicateVenueNS($VenueID,$VenueName);
			} else {
				$Venue = chkDuplicateVenue($VenueName);
			}
			
			
			if($Venue->rowCount() > 0)
			{
				\Validation\CreateErrorMessage(' duplicate detected. Please try a different Venue Name!',$ErrorName);
				$blnIsGood = false;
				
			}
			
		}//End Venue Name validation
	}
	
	//check duplicate venue name
	function chkDuplicateVenue($VenueName)
	{
		global $dbh;
		
		try {
				$Venue = $dbh->prepare('SELECT fld_venue_name FROM tbl_venues WHERE fld_venue_name = :VenueName');
				$Venue->execute(array(':VenueName' => $VenueName ));
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		return $Venue;
	}
	
	//check duplicate venue name that is Not Self
	function chkDuplicateVenueNS($VenueID,$VenueName)
	{
		global $dbh;
		
		try {
				$Venue = $dbh->prepare('SELECT fld_venue_name 
										FROM tbl_venues 
										WHERE fld_venue_name = :VenueName
										AND id_venue <> :VenueID');
				$Venue->execute(array(':VenueName' => $VenueName,
									   ':VenueID' => $VenueID ));
					
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		
		return $Venue;
	}

	
	function getVenue($VenueID)
	{
		global $dbh;
		try {
				$Venue = $dbh->prepare('	SELECT * 
											FROM tbl_venues
											WHERE id_venue = :VenueID'
											);
				$Venue->execute(array(':VenueID' => $VenueID ));
			
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
			
		return $Venue;
	}
	
	function getAllVenues($Active)
	{
		global $dbh;
		
		if($Active)
		{
			$ActiveString = '( fld_end_date IS NULL OR fld_end_date >= DATE(NOW()) )';
		} else {
			$ActiveString = ' fld_end_date < DATE(NOW())';
		}
		
		try {
				$Venues = $dbh->query('	SELECT * 
										FROM tbl_venues
										WHERE '.$ActiveString.'
										ORDER BY fld_venue_name ASC');
				
			
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
			
		return $Venues;
	}
	
	function getAllVenuesForDropDown()
	{
		global $dbh;
		
		try {
			$Venues = $dbh->query('SELECT *
									FROM tbl_venues
									ORDER BY fld_venue_name ASC;
									');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Venues;
	}
	
	function get_all_venues_for_drop_down_by_state($staff_id)
	{
		global $dbh;
		
		try {
			$Venues = $dbh->prepare('SELECT *
									FROM tbl_venues
									JOIN tbl_states
									ON tbl_venues.fld_venue_state = tbl_states.id_state
									WHERE tbl_states.fld_state_abbreviation IN(
																				SELECT tbl_branches.fld_branch_abbreviation
																				FROM tbl_state_users_state_activity_dates
																				JOIN tbl_branches
																				ON tbl_state_users_state_activity_dates.fld_branch_id = tbl_branches.id_branch
																				WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																				AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																				AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																					OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																					)
																				AND fld_user_id = :staff_id
																				)
									ORDER BY fld_venue_name ASC;
									');
			$Venues->execute(array(':staff_id' => $staff_id ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Venues;
	}
	
	class VenueAuditDate {
		
		private $VenueAuditID;
		private $VenueID;
		private $AuditDate;
		private $Notes;
		private $Complete;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetVenueAuditID()
		{
			return $this->VenueAuditID;
		}
		
		public function SetVenueAuditID($VenueAuditID)
		{
			$this->VenueAuditID = $VenueAuditID;
		}
		
		public function GetVenueID()
		{
			return $this->VenueID;
		}
		
		public function SetVenueID($VenueID)
		{
			$this->VenueID = $VenueID;
		}
		
		public function GetAuditDate()
		{
			return $this->AuditDate;
		}
		
		public function SetAuditDate($AuditDate)
		{
			$this->AuditDate = $AuditDate;
		}
		
		public function GetNotes()
		{
			return $this->Notes;
		}
		
		public function SetNotes($Notes)
		{
			$this->Notes = $Notes;
		}
		
		public function GetComplete()
		{
			return $this->Complete;
		}
		
		public function SetComplete($Complete)
		{
			$this->Complete = $Complete;
		}
		
		//END GETTERS SETTERS
		
		public static function LoadAuditDatesByVenue($VenueID)
		{
			$VenueID = intval($VenueID);
			
			$arrVenueAuditDates = getAuditDatesByVenue($VenueID)->fetchAll();
			
			$VenueAuditDates = array();
			
			foreach( $arrVenueAuditDates as $AuditDate )
			{
				$VenueAuditDates[] = VenueAuditDate::ArrToVenueAudit($AuditDate);
			}
			
			return $VenueAuditDates;
		}
				
		public static function Load($VenueAuditID)
		{
			$VenueAuditID = intval($VenueAuditID);
			
			$pdoVenueAudit = getVenueAudit($VenueAuditID);
			
			if($pdoVenueAudit->rowCount() != 1 )
			{
				return NULL;
			}
			
			return VenueAuditDate::ArrToVenueAudit($pdoVenueAudit->fetch());
		}
		
		public static function LoadVenueNextAudit($VenueID)
		{
			$VenueID = intval($VenueID);
			
			$pdoNextDue = getVenuesNextDueAudit($VenueID);
			
			if($pdoNextDue->rowCount() != 1 )
			{
				return NULL;
			}
			
			return VenueAuditDate::ArrToVenueAudit($pdoNextDue->fetch());
		}
		
		public static function LoadVenuesLastCompleteAudit($VenueID)
		{
			$VenueID = intval($VenueID);
			
			$pdoLastComplete = getVenuesLastCompleteAudit($VenueID);
			
			if($pdoLastComplete->rowCount() != 1 )
			{
				return NULL;
			}
			
			return VenueAuditDate::ArrToVenueAudit($pdoLastComplete->fetch());
		}
		
		public static function GroupHasRegion($GroupID)
		{
			return GroupRegion::LoadGroupsLastRegion($GroupID) != NULL;
		}
		
		public static function ArrToVenueAudit($Item)
		{
			$thisVenueAudit = new VenueAuditDate();
			
			$thisVenueAudit->SetVenueAuditID($Item['id_venue_audit_date']);
			$thisVenueAudit->SetVenueID($Item['fld_venue_id']);
			$thisVenueAudit->SetAuditDate($Item['fld_audit_date']);
			$thisVenueAudit->SetNotes($Item['fld_notes']);
			$thisVenueAudit->SetComplete($Item['fld_complete']);
			
			return $thisVenueAudit;
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function GetRegion()
		{
			return Region::LoadRegion($this->RegionID);
		}
		
		public static function Create($VenueID,$SafeAD,$Notes,$Complete)
		{
			$VenueAuditID = addVenueAuditDate(
					$VenueID,
					$SafeAD,
					$Notes,
					$Complete
								);
			
			return VenueAuditDate::Load($VenueAuditID);
		}
		
		public function Update($SafeAD,$Notes,$Complete)
		{
			
			$this->AuditDate = $SafeAD;
			$this->Notes = $Notes;
			$this->Complete = $Complete;
			
			updVenueAuditDate(
					$this->VenueAuditID,
					$this->AuditDate,
					$this->Notes,
					$this->Complete
					);
			
		}
		
	} // END VENUE AUDIT DATE
	
	function getVenueAudit($VenueAuditID)
	{
		global $dbh;
		try {
				$VenueAudit = $dbh->prepare('	
											SELECT * 
											FROM tbl_venue_audit_dates
											WHERE id_venue_audit_date = :VenueAuditID
											');
				$VenueAudit->execute(array(':VenueAuditID' => $VenueAuditID ));
			
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
			
		return $VenueAudit;
	}
	
	function addVenueAuditDate($VenueID,$SafeAD,$Notes,$Complete)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_venue_audit_dates(
																fld_venue_id,
																fld_audit_date,
																fld_notes,
																fld_complete
																) 
									  VALUES (
									  			:VenueID,
									  			:SafeAD,
												:Notes,
												:Complete
												) 
										');
			$qryInsert->execute(array(
										':VenueID' => $VenueID,
										':SafeAD' => ($SafeAD == 'null') ? null : $SafeAD,
										':Notes' => $Notes, 
										':Complete' => $Complete
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	function updVenueAuditDate(
						$VenueAuditID,
						$AuditDate,
						$Notes,
						$Complete
						)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_venue_audit_dates SET
										fld_audit_date = :AuditDate,
										fld_notes = :Notes,
										fld_complete = :Complete
										WHERE id_venue_audit_date = :VenueAuditID
										');
			$qryUpdate->execute(array(
										':AuditDate' => $AuditDate,
										':Notes' => $Notes, 
										':Complete' => $Complete, 
										':VenueAuditID' => $VenueAuditID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	}
	
	function getAuditDatesByVenue($VenueID)
	{
		global $dbh;
		
		try {
				$VenueAuditDates = $dbh->prepare('
										SELECT tbl_venue_audit_dates.*
										FROM tbl_venue_audit_dates
										WHERE fld_venue_id = :VenueID
										ORDER BY fld_audit_date DESC
										');
				$VenueAuditDates->execute(array(
										':VenueID' => $VenueID
										));
					
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		
		return $VenueAuditDates;
	}
	
	function getVenuesNextDueAudit($VenueID)
	{
		global $dbh;
		global $false;
		
		try {
				$VenueAuditDate = $dbh->prepare('
										SELECT MAX(fld_audit_date) AS max_audit, tbl_venue_audit_dates.*
										FROM tbl_venue_audit_dates
										WHERE fld_venue_id = :VenueID
										AND fld_complete = :false
										');
				$VenueAuditDate->execute(array(':VenueID' => $VenueID,
									   ':false' => $false ));
					
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		
		return $VenueAuditDate;
	}
	
	function getVenuesLastCompleteAudit($VenueID)
	{
		global $dbh;
		global $true;
		
		try {
				$VenueAuditDate = $dbh->prepare('
										SELECT MAX(fld_audit_date) AS max_audit, tbl_venue_audit_dates.*
										FROM tbl_venue_audit_dates
										WHERE fld_venue_id = :VenueID
										AND fld_complete = :true
										');
				$VenueAuditDate->execute(array(':VenueID' => $VenueID,
									   ':true' => $true ));
					
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		
		return $VenueAuditDate;
	}
	
	class GroupVenue {
		
		private $GroupVenueID;
		private $GroupID;
		private $VenueID;
		private $StartDate;
		private $EndDate;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupVenueID()
		{
			return $this->GroupVenueID;
		}
		
		public function SetGroupVenueID($GroupVenueID)
		{
			$this->GroupVenueID = $GroupVenueID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetVenueID()
		{
			return $this->VenueID;
		}
		
		public function SetVenueID($VenueID)
		{
			$this->VenueID = $VenueID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public static function ArrToGroupVenue($Item)
		{
			$thisGroupVenue = new GroupVenue();
			
			$thisGroupVenue->SetGroupVenueID($Item['id_group_venue']);
			$thisGroupVenue->SetGroupID($Item['fld_group_id']);
			$thisGroupVenue->SetVenueID($Item['fld_venue_id']);
			$thisGroupVenue->SetStartDate($Item['fld_start_date']);
			$thisGroupVenue->SetEndDate($Item['fld_end_date']);
			
			return $thisGroupVenue;
		}
			
		public static function LoadGroupsVenues($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsVenues = getGroupsVenues($GroupID)->fetchAll();
			
			$GroupsVenues = array();
			
			foreach( $arrGroupsVenues as $GroupVenue )
			{
				$GroupsVenues[] = GroupVenue::ArrToGroupVenue($GroupVenue);
			}
						
			return $GroupsVenues;
		}
		
		public static function LoadGroupVenue($GroupVenueID)
		{
			$GroupVenueID = intval($GroupVenueID);
			
			$pdoGroupVenue = getGroupVenue($GroupVenueID);
			
			if($pdoGroupVenue->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupVenue::ArrToGroupVenue($pdoGroupVenue->fetch());
		}
		
		public function GetVenue()
		{
			return Venue::LoadVenue($this->VenueID);
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function UpdateGroupVenue($VenueID,$StartDate,$EndDate)
		{
			$this->VenueID = $VenueID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updGroupVenue($this->GroupVenueID,$this->VenueID,$this->StartDate,$this->EndDate);
			
		}
		
		public static function CreateGroupVenue($GroupID,$VenueID,$StartDate,$EndDate)
		{
			$GroupVenueID = addGroupVenue($GroupID,$VenueID,$StartDate,$EndDate);
			
			return GroupVenue::LoadGroupVenue($GroupVenueID);
		}
		
		public static function GroupHasVenue($GroupID)
		{
			return GroupVenue::LoadGroupsCurrentVenue($GroupID) != NULL;
		}
		
		public static function LoadGroupsLastVenue($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupVenue = getLastGroupVenue($GroupID);
			
			if($pdoGroupVenue->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupVenue::ArrToGroupVenue($pdoGroupVenue->fetch());
		}
		
		public static function LoadGroupsCurrentVenue($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupVenue = getCurrentGroupVenue($GroupID);
			
			if($pdoGroupVenue->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupVenue::ArrToGroupVenue($pdoGroupVenue->fetch());
		}
		
		
		
	}  // END GROUP VENUE
	
	function getCurrentGroupVenue($GroupID)
	{
		global $dbh;
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_venues
									WHERE fld_group_id = :GroupID
									AND fld_start_date <= DATE(NOW())
									AND (fld_end_date IS NULL or fld_end_date >= DATE(NOW()) )
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function getLastGroupVenue($GroupID)
	{
		global $dbh;
		
		try {
			
			$Region = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_venues
									WHERE fld_group_id = :GroupID
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Region->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Region;
	}
	
	function addGroupVenue($GroupID,$VenueID,$StartDate,$EndDate)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_venues(fld_group_id,fld_venue_id,fld_start_date,fld_end_date) 
								  VALUES (:GroupID,:VenueID,:StartDate,:EndDate) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID,
								 	':VenueID' => $VenueID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function updGroupVenue($GroupVenueID,$VenueID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups_venues
										SET fld_venue_id = :VenueID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_group_venue = :GroupVenueID
										');
			$qryUpdate->execute(array(
										':VenueID' => $VenueID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 		':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':GroupVenueID' => $GroupVenueID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function getGroupVenue($GroupVenueID)
	{
		global $dbh;
		
		try {
			
			$GroupVenue = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_venues
									WHERE id_group_venue = :GroupVenueID
									
									');
			$GroupVenue->execute(array(':GroupVenueID' => $GroupVenueID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupVenue;
	}
	
	
	function getGroupsVenues($GroupID)
	{
		global $dbh;
		
		try {
			
			$GroupsVenues = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_venues
									WHERE fld_group_id = :GroupID
									ORDER BY fld_start_date DESC;
									');
			$GroupsVenues->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsVenues;
	}
	
	class GroupSchedule {
		
		private $GroupScheduleID;
		private $GroupID;
		private $StartDate;
		private $EndDate;
		private $RecurrencyString;
		private $RecurrencyInt;
		private $StartTime;
		private $EndTime;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupScheduleID()
		{
			return $this->GroupScheduleID;
		}
		
		public function SetGroupScheduleID($GroupScheduleID)
		{
			$this->GroupScheduleID = $GroupScheduleID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		//
		public function GetRecurrencyString()
		{
			return $this->RecurrencyString;
		}
		
		public function SetRecurrencyString($RecurrencyString)
		{
			$this->RecurrencyString = $RecurrencyString;
		}
		
		public function GetRecurrencyInt()
		{
			return $this->RecurrencyInt;
		}
		
		public function SetRecurrencyInt($RecurrencyInt)
		{
			$this->RecurrencyInt = $RecurrencyInt;
		}
		
		public function GetStartTime()
		{
			return $this->StartTime;
		}
		
		public function SetStartTime($StartTime)
		{
			$this->StartTime = $StartTime;
		}
		
		public function GetEndTime()
		{
			return $this->EndTime;
		}
		
		public function SetEndTime($EndTime)
		{
			$this->EndTime = $EndTime;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public static function GroupHasSchedule($GroupID)
		{
			return GroupSchedule::LoadGroupsCurrentSchedule($GroupID) != NULL;
		}
		
		public static function LoadGroupsCurrentSchedule($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupSchedule = getCurrentGroupSchedule($GroupID);
			
			if($pdoGroupSchedule->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupSchedule::ArrToGroupSchedule($pdoGroupSchedule->fetch());
		}
		
		public static function LoadGroupsLastSchedule($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$pdoGroupSchedule = getLastGroupSchedule($GroupID);
			
			if($pdoGroupSchedule->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupSchedule::ArrToGroupSchedule($pdoGroupSchedule->fetch());
		}
				
		public static function ArrToGroupSchedule($Item)
		{
			$thisGroupSchedule = new GroupSchedule();
			
			$thisGroupSchedule->SetGroupScheduleID($Item['id_group_schedule']);
			$thisGroupSchedule->SetGroupID($Item['fld_group_id']);
			$thisGroupSchedule->SetStartDate($Item['fld_start_date']);
			$thisGroupSchedule->SetEndDate($Item['fld_end_date']);
			$thisGroupSchedule->SetRecurrencyString($Item['fld_recurrency_string']);
			$thisGroupSchedule->SetRecurrencyInt($Item['fld_recurrency_int']);
			$thisGroupSchedule->SetStartTime($Item['fld_start_time']);
			$thisGroupSchedule->SetEndTime($Item['fld_end_time']);
			$thisGroupSchedule->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupSchedule;
		}
			
		public static function LoadGroupsSchedules($GroupID,$Sort = 'DESC')
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsSchedules = getGroupsSchedules($GroupID, $Sort)->fetchAll();
			
			$GroupsSchedules = array();
			
			foreach( $arrGroupsSchedules as $GroupSchedule )
			{
				$GroupsSchedules[] = GroupSchedule::ArrToGroupSchedule($GroupSchedule);
			}
						
			return $GroupsSchedules;
		}
		
		public static function LoadGroupSchedule($GroupScheduleID)
		{
			$GroupScheduleID = intval($GroupScheduleID);
			
			$pdoGroupSchedule = getGroupSchedule($GroupScheduleID);
			
			if($pdoGroupSchedule->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupSchedule::ArrToGroupSchedule($pdoGroupSchedule->fetch());
		}
		
		
		public static function CreateGroupSchedule($GroupID,$SafeSD,$SafeED,$RecString,$RecInt,$StartTime,$EndTime)
		{
			$GroupScheduleID = addGroupSchedule($GroupID,$SafeSD,$SafeED,$RecString,$RecInt,$StartTime,$EndTime);
			
			return GroupSchedule::LoadGroupSchedule($GroupScheduleID);
		}
	
		public function UpdateGroupSchedule(
									$SafeSD,
									$SafeED,
									$RecString,
									$RecInt,
									$StartTime,
									$EndTime
									)
		{
			
			$this->StartDate = $SafeSD;
			$this->EndDate = $SafeED;
			$this->RecurrencyString = $RecString;
			$this->RecurrencyInt = $RecInt;
			$this->StartTime = $StartTime;
			$this->EndTime = $EndTime;
			
			updGroupSchedule(
					$this->GroupScheduleID,
					$this->StartDate,
					$this->EndDate,
					$this->RecurrencyString,
					$this->RecurrencyInt,
					$this->StartTime,
					$this->EndTime
					);
			
		}
		
	} // END GROUP SCHEDULE
	
	function getLastGroupSchedule($GroupID)
	{
		global $dbh;
		
		try {
			
			$Schedule = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_schedules
									WHERE fld_group_id = :GroupID
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Schedule->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Schedule;
	}
	
	function getCurrentGroupSchedule($GroupID)
	{
		global $dbh;
		
		try {
			
			$Schedule = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_schedules
									WHERE fld_group_id = :GroupID
									AND fld_start_date <= DATE(NOW())
									AND (fld_end_date IS NULL or fld_end_date >= DATE(NOW()) )
									ORDER BY fld_start_date DESC
									LIMIT 1;
									');
			$Schedule->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Schedule;
	}
	
	function updGroupSchedule(
						$GroupScheduleID,
						$StartDate,
						$EndDate,
						$RecurrencyString,
						$RecurrencyInt,
						$StartTime,
						$EndTime
						)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_groups_schedules SET
										fld_start_date = :StartDate,
										fld_end_date = :EndDate,
										fld_recurrency_string = :RecurrencyString,
										fld_recurrency_int = :RecurrencyInt,
										fld_start_time = :StartTime,
										fld_end_time = :EndTime
										WHERE id_group_schedule = :GroupScheduleID
										');
			$qryUpdate->execute(array(
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
										':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':RecurrencyString' => $RecurrencyString, 
										':RecurrencyInt' => $RecurrencyInt,
										':StartTime' => $StartTime,
										':EndTime' => $EndTime,
										':GroupScheduleID' => $GroupScheduleID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	}
	
	function addGroupSchedule($GroupID,$SafeSD,$SafeED,$RecString,$RecInt,$StartTime,$EndTime)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_schedules(fld_group_id,fld_start_date,fld_end_date,fld_recurrency_string,fld_recurrency_int,fld_start_time,fld_end_time) 
								  VALUES (:GroupID,:SafeSD,:SafeED,:RecString,:RecInt,:StartTime,:EndTime) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID,
									':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
								 	':SafeED' => ($SafeED == 'null') ? null : $SafeED,
									':RecString' => $RecString,
									':RecInt' => $RecInt,
									':StartTime' => $StartTime,
									':EndTime' => $EndTime
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	function getGroupSchedule($GroupScheduleID)
	{
		global $dbh;
		
		try {
			
			$GroupSchedule = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_schedules
									WHERE id_group_schedule = :GroupScheduleID
									
									');
			$GroupSchedule->execute(array(':GroupScheduleID' => $GroupScheduleID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupSchedule;
	}
	
	function getGroupsSchedules($GroupID,$Sort)
	{
		global $dbh;
		
		try {
			
			$GroupsSchedules = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_schedules
									WHERE fld_group_id = :GroupID
									ORDER BY fld_start_date '.$Sort.';
									');
			$GroupsSchedules->execute(array(':GroupID' => $GroupID
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsSchedules;
	}

	class GroupScheduledDate {
		
		private $GroupScheduleDateID;
		private $GroupID;
		private $Date;
		private $StartTime;
		private $EndTime;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupScheduleDateID()
		{
			return $this->GroupScheduleDateID;
		}
		
		public function SetGroupScheduleDateID($GroupScheduleDateID)
		{
			$this->GroupScheduleDateID = $GroupScheduleDateID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetDate()
		{
			return $this->Date;
		}
		
		public function SetDate($Date)
		{
			$this->Date = $Date;
		}
		
		public function GetStartTime()
		{
			return $this->StartTime;
		}
		
		public function SetStartTime($StartTime)
		{
			$this->StartTime = $StartTime;
		}
		
		public function GetEndTime()
		{
			return $this->EndTime;
		}
		
		public function SetEndTime($EndTime)
		{
			$this->EndTime = $EndTime;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public static function GroupHasScheduledDates($GroupID)
		{
			$Dates = GroupScheduledDate::LoadGroupsScheduledDates($GroupID);
			
			return count($Dates) > 0 ;
		}
		
		public static function ArrToGroupScheduleDate($Item)
		{
			$thisGroupSchedule = new GroupScheduledDate();
			
			$thisGroupSchedule->SetGroupScheduleDateID($Item['id_grp_sch_date']);
			$thisGroupSchedule->SetGroupID($Item['fld_group_id']);
			$thisGroupSchedule->SetDate($Item['fld_date']);
			$thisGroupSchedule->SetStartTime($Item['fld_start_time']);
			$thisGroupSchedule->SetEndTime($Item['fld_end_time']);
			$thisGroupSchedule->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupSchedule;
		}
			
		public static function LoadGroupsScheduledDates($GroupID,$Sort = 'DESC')
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsScheduledDates = getGroupsScheduledDates($GroupID, $Sort)->fetchAll();
			
			$GroupsScheduledDates = array();
			
			foreach( $arrGroupsScheduledDates as $GroupSchedule )
			{
				$GroupsScheduledDates[] = GroupScheduledDate::ArrToGroupScheduleDate($GroupSchedule);
			}
						
			return $GroupsScheduledDates;
		}
		
		public static function LoadGroupScheduledDate($GroupScheduleDateID)
		{
			$GroupScheduleDateID = intval($GroupScheduleDateID);
			
			$pdoGroupScheduledDate = getGroupScheduledDate($GroupScheduleDateID);
			
			if($pdoGroupScheduledDate->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupScheduledDate::ArrToGroupScheduleDate($pdoGroupScheduledDate->fetch());
		}
		
		
		public static function CreateGroupScheduledDate($GroupID,$SafeDate,$StartTime,$EndTime)
		{
			$GroupScheduleDateID = addGroupScheduledDate($GroupID,$SafeDate,$StartTime,$EndTime);
			
			return GroupScheduledDate::LoadGroupScheduledDate($GroupScheduleDateID);
		}
	
		public function UpdateGroupScheduledDate(
									$SafeDate,
									$StartTime,
									$EndTime
									)
		{
			
			$this->Date = $SafeDate;
			$this->StartTime = $StartTime;
			$this->EndTime = $EndTime;
			
			updGroupScheduledDate(
					$this->GroupScheduleDateID,
					$this->Date,
					$this->StartTime,
					$this->EndTime
					);
			
		}
		
	} // END GROUP SCHEDULED DATES

	function updGroupScheduledDate(
						$GroupScheduleDateID,
						$SafeDate,
						$StartTime,
						$EndTime
						)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_groups_scheduled_dates SET
										fld_date = :SafeDate,
										fld_start_time = :StartTime,
										fld_end_time = :EndTime
										WHERE id_grp_sch_date = :GroupScheduleDateID
										');
			$qryUpdate->execute(array(
										':SafeDate' => ($SafeDate == 'null') ? null : $SafeDate,
										':StartTime' => $StartTime,
										':EndTime' => $EndTime,
										':GroupScheduleDateID' => $GroupScheduleDateID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	}
	
	
	function addGroupScheduledDate($GroupID,$SafeDate,$StartTime,$EndTime)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_scheduled_dates(fld_group_id,fld_date,fld_start_time,fld_end_time) 
									  VALUES (:GroupID,:SafeDate,:StartTime,:EndTime) ');
			$qryInsert->execute(array(	':GroupID' => $GroupID,
										':SafeDate' => ($SafeDate == 'null') ? null : $SafeDate,
										':StartTime' => $StartTime,
										':EndTime' => $EndTime
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getGroupScheduledDate($GroupScheduleDateID)
	{
		global $dbh;
		
		try {
			
			$GroupSchedule = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_scheduled_dates
									WHERE id_grp_sch_date = :GroupScheduleDateID
									
									');
			$GroupSchedule->execute(array(':GroupScheduleDateID' => $GroupScheduleDateID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupSchedule;
	}
	
	function getGroupsScheduledDates($GroupID,$Sort)
	{
		global $dbh;
		
		try {
			
			$GroupsSchedules = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_scheduled_dates
									WHERE fld_group_id = :GroupID
									ORDER BY fld_date '.$Sort.';
									');
			$GroupsSchedules->execute(array(':GroupID' => $GroupID
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsSchedules;
	}
	
	
	class GroupLeader {
		
		private $GroupsRolesID;
		private $GroupID;
		private $UserID;
		private $GroupRoleID;
		private $StartDate;
		private $EndDate;
		private $Acting;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetGroupsRolesID()
		{
			return $this->GroupsRolesID;
		}
		
		public function SetGroupsRolesID($GroupsRolesID)
		{
			$this->GroupsRolesID = $GroupsRolesID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetUserID()
		{
			return $this->UserID;
		}
		
		public function SetUserID($UserID)
		{
			$this->UserID = $UserID;
		}
		
		public function GetGroupRoleID()
		{
			return $this->GroupRoleID;
		}
		
		public function SetGroupRoleID($GroupRoleID)
		{
			$this->GroupRoleID = $GroupRoleID;
		}
		
		public function GetStartDate()
		{
			return $this->StartDate;
		}
		
		public function SetStartDate($StartDate)
		{
			$this->StartDate = $StartDate;
		}
		
		public function GetEndDate()
		{
			return $this->EndDate;
		}
		
		public function SetEndDate($EndDate)
		{
			$this->EndDate = $EndDate;
		}
		
		public function GetActing()
		{
			return $this->Acting;
		}
		
		public function SetActing($Acting)
		{
			$this->Acting = $Acting;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public function HasAttendanceByDate($Date)
		{
			return Attendance::LoadAttendanceByGroupUserDate($this->GroupID,$this->UserID,$Date) != NULL;
			
		}
		
		public static function ArrToGroupLeader($Item)
		{
			$thisGroupLeader = new GroupLeader();
			
			$thisGroupLeader->SetGroupsRolesID($Item['id_groups_roles']);
			$thisGroupLeader->SetGroupID($Item['fld_group_id']);
			$thisGroupLeader->SetUserID($Item['fld_user_id']);
			$thisGroupLeader->SetGroupRoleID($Item['fld_group_role_id']);
			$thisGroupLeader->SetStartDate($Item['fld_start_date']);
			$thisGroupLeader->SetEndDate($Item['fld_end_date']);
			$thisGroupLeader->SetActing($Item['fld_acting']);
			$thisGroupLeader->SetDeleted($Item['fld_deleted']);
			
			return $thisGroupLeader;
		}
			
		public static function LoadGroupsLeaders($GroupID)
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsLeaders = getGroupsLeaders($GroupID)->fetchAll();
			
			$GroupsLeaders = array();
			
			foreach( $arrGroupsLeaders as $GroupLeader )
			{
				$GroupsLeaders[] = GroupLeader::ArrToGroupLeader($GroupLeader);
			}
						
			return $GroupsLeaders;
		}
		
		public static function LoadGroupsLeadersByDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			$arrGroupsLeaders = getGroupsLeadersByDate($GroupID,$Date)->fetchAll();
			
			$GroupsLeaders = array();
			
			foreach( $arrGroupsLeaders as $GroupLeader )
			{
				$GroupsLeaders[] = GroupLeader::ArrToGroupLeader($GroupLeader);
			}
						
			return $GroupsLeaders;
		}
		
		public static function LoadGroupLeader($GroupLeaderID)
		{
			$GroupLeaderID = intval($GroupLeaderID);
			
			$pdoGroupLeader = getGroupLeader($GroupLeaderID);
			
			if($pdoGroupLeader->rowCount() != 1 )
			{
				return NULL;
			}
			
			return GroupLeader::ArrToGroupLeader($pdoGroupLeader->fetch());
		}
		
		public function GetLeader() // returns staff object
		{
			return \Membership\Staff::LoadStaff($this->UserID);
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function UpdateGroupLeader($UserID,$GroupRoleID,$StartDate,$EndDate,$Acting)
		{
			$this->UserID = $UserID;
			$this->GroupRoleID = $GroupRoleID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			$this->Acting = $Acting;
			
			updGroupLeader($this->GroupsRolesID,$this->UserID,$this->GroupRoleID,$this->StartDate,$this->EndDate,$this->Acting);
			
		}
		
		public static function CreateGroupLeader($GroupID,$UserID,$GroupRoleID,$StartDate,$EndDate,$Acting)
		{
			$GroupLeaderID = addGroupLeader($GroupID,$UserID,$GroupRoleID,$StartDate,$EndDate,$Acting);
			
			return GroupLeader::LoadGroupLeader($GroupLeaderID);
		}
		
		
		public function GetGroupRoleName()
		{
			return getGroupRoleName($this->GroupRoleID);
		}
		
	} // END GROUP LEADER
	
	function getAttendanceByUserGroupDate($GroupID,$UserID,$Date)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_user_id = :UserID
									AND fld_date = :Date
									AND fld_deleted = 0
									');
			$Attendance->execute(array(
										':GroupID' => $GroupID,
										':UserID' => $UserID,
										':Date' => ($Date == 'null') ? null : $Date 
										));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendance;
	}
	
	function getAllRolesForDropDown()
	{
		global $dbh;
		
		try {
			$Roles = $dbh->query('SELECT *
									FROM tbl_group_roles
									');
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Roles;
	}
	
	
	function getGroupRoleName($GroupRoleID)
	{
		global $dbh;
		
		try {
			
			$GroupRole = $dbh->prepare('	
									SELECT *
									FROM tbl_group_roles
									WHERE id_group_role = :GroupRoleID
									
									');
			$GroupRole->execute(array(':GroupRoleID' => $GroupRoleID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		$arrGroupRole = $GroupRole->fetch();
		
		return $arrGroupRole['fld_group_role'];
	}
	
	function updGroupLeader(
						$GroupsRolesID,
						$UserID,
						$GroupRoleID,
						$StartDate,
						$EndDate,
						$Acting
						)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_groups_roles SET
										fld_user_id = :UserID,
										fld_group_role_id = :GroupRoleID,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate,
										fld_acting = :Acting
										WHERE id_groups_roles = :GroupsRolesID
										');
			$qryUpdate->execute(array(
										':UserID' => $UserID,
										':GroupRoleID' => $GroupRoleID,
										':StartDate' => ($StartDate == 'null') ? null : $StartDate,
										':EndDate' => ($EndDate == 'null') ? null : $EndDate,
										':Acting' => $Acting,
										':GroupsRolesID' => $GroupsRolesID
								 		
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	}
	
	function addGroupLeader($GroupID,$UserID,$GroupRoleID,$StartDate,$EndDate,$Acting)
	{
		global $dbh;
	
	try {
		$qryInsert = $dbh->prepare('INSERT INTO tbl_groups_roles(fld_group_id,fld_user_id,fld_group_role_id,fld_start_date,fld_end_date,fld_acting) 
								  VALUES (:GroupID,:UserID,:GroupRoleID,:StartDate,:EndDate,:Acting) ');
		$qryInsert->execute(array(	':GroupID' => $GroupID,
								 	':UserID' => $UserID, 
								 	':GroupRoleID' => $GroupRoleID, 
									':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 	':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								 	':Acting' => $Acting
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
	}
	
	
	function getGroupLeader($GroupLeaderID) // returns a group leader not a user
	{
		global $dbh;
		
		try {
			
			$GroupLeader = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_roles
									WHERE id_groups_roles = :GroupLeaderID
									
									');
			$GroupLeader->execute(array(':GroupLeaderID' => $GroupLeaderID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupLeader;
	}
	
	function getGroupsLeaders($GroupID)
	{
		global $dbh;
		
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_roles
									WHERE fld_group_id = :GroupID
									ORDER BY fld_start_date DESC;
									');
			$GroupsLeaders->execute(array(':GroupID' => $GroupID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	function getGroupsLeadersByDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_groups_roles
									WHERE fld_group_id = :GroupID
									AND fld_start_date <= :Date1
									AND (fld_end_date IS NULL
										OR fld_end_Date >= :Date2)
									AND tbl_groups_roles.fld_deleted = 0
									ORDER BY fld_start_date DESC;
									');
			$GroupsLeaders->execute(array(	':GroupID' => $GroupID,
											':Date1' => ($Date == 'null') ? null : $Date,
											':Date2' => ($Date == 'null') ? null : $Date
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	class Attendance {
		private $AttendanceID;
		private $GroupID;
		private $UserID;
		private $Date;
		private $EnteredBy;
		private $Deleted;
		
		function __construct() {
		//empty constructor that may need to be altered later
		}
		
		public function GetAttendanceID()
		{
			return $this->AttendanceID;
		}
		
		public function SetAttendanceID($AttendanceID)
		{
			$this->AttendanceID = $AttendanceID;
		}
		
		public function GetGroupID()
		{
			return $this->GroupID;
		}
		
		public function SetGroupID($GroupID)
		{
			$this->GroupID = $GroupID;
		}
		
		public function GetUserID()
		{
			return $this->UserID;
		}
		
		public function SetUserID($UserID)
		{
			$this->UserID = $UserID;
		}
				
		public function GetDate()
		{
			return $this->Date;
		}
		
		public function SetDate($Date)
		{
			$this->Date = $Date;
		}
		
		public function GetEnteredBy()
		{
			return $this->EnteredBy;
		}
		
		public function SetEnteredBy($EnteredBy)
		{
			$this->EnteredBy = $EnteredBy;
		}
		
		public function GetDeleted()
		{
			return $this->Deleted;
		}
		
		public function SetDeleted($Deleted)
		{
			$this->Deleted = $Deleted;
		}
		
		public function Delete()
		{
			global $true;
			
			//$this->Deleted = $true;
			
			//updDeleted($this->AttendanceID,$this->Deleted);
			
			delGroupAttendance($this->AttendanceID);
		}
		
		public static function GetGroupAttendanceByDate($GroupID,$Date)
		{
			return getGroupAttendanceByDate($GroupID,$Date);
		}
		
		public static function CountVolunteerAttendanceByGroup($VolRole,$GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$Attendance = getCountVolunteerAttendanceByGroup($VolRole,$GroupID,$StartDate,$EndDate)->fetch();
			
			return $Attendance['Total'];
		}
		
		public static function CountMembersAttendanceByGroup($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$Attendance = getCountMemberAttendanceByGroup($GroupID,$StartDate,$EndDate)->fetch();
			
			return $Attendance['Total'];
			
		}
		
		public static function LoadUserMostRecentAttendance($UserID)
		{
			$UserID = intval($UserID);
			
			$pdo_Attendance = getLastUserAttendance($UserID);
			
			if( $pdo_Attendance->rowCount() != 1 )
			{
				return NULL;
			} else {
				return Attendance::ArrToAttendance($pdo_Attendance->fetch());
			}
		}
		
		public static function LoadDidntAttendButDidAttend($GroupID,$StartDidnt,$EndDidnt,$DidDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getDidntAttendButDidAttend($GroupID,$StartDidnt,$EndDidnt,$DidDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function CountCommittedGrowersByDate($GroupID,$Date)
		{
			return getCountCommittedGrowersByDate($GroupID,$Date)->fetch();
		}
		
		public static function CountCeasedAttendingGroupOn($GroupID,$StartDid,$EndDid)
		{
			return getCountCeasedAttendingGroupOn($GroupID,$StartDid,$EndDid)->fetch();
		}
		
		public static function CountFieldWorkerAttendeesInPeriod($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getFieldWorkerAttendeesInPeriod($GroupID,$StartDate,$EndDate)->fetch();
			
			return $arrAttendances['Total_Aees'];
		}
		
		public static function LoadFieldWorkerAttendancesInPeriod($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getFieldWorkerAttendancesInPeriod($GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadNonCommittedAttendancesInPeriod($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getNonCommittedGrowersAttendancesInPeriod($GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadCommittedGrowersAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)
		{
			$RegionID = intval($RegionID);
			
			$arrAttendances = getCommittedGrowersAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadCommittedGrowersAttendancesInPeriod($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getCommittedGrowersAttendancesInPeriod($GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadStaffAttendancesInPeriodByRegion($GroupRole,$RegionID,$StartDate,$EndDate)
		{
			$RegionID = intval($RegionID);
			
			$arrAttendances = getStaffAttendancesInPeriodByRegion($GroupRole,$RegionID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadStaffAttendancesInPeriod($GroupRole,$GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getStaffAttendancesInPeriod($GroupRole,$GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadAttendanceFirstTimers($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getFirstTimeAttendancesBetweenDates($GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function FirstTimerStatsByGroupDates($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			return getFirstTimerStatsBetweenDates($GroupID,$StartDate,$EndDate)->fetch();
			
		}  
		
		public static function FirstTimerMultipleAttendancesByGroupDates($GroupID,$StartDate,$EndDate,$NoAttendances)
		{
			$GroupID = intval($GroupID);
			
			return getFirstTimerMultipleAttendancesBetweenDates($GroupID,$StartDate,$EndDate,$NoAttendances)->fetch();
			
		}
		
		public static function CountNewCommittedGrowersByGroupDates($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$Result = getCountNewCommittedGrowersByGroupDates($GroupID,$StartDate,$EndDate)->fetch();
			
			return $Result['Total_Aees'];
			
		}
		
		public static function GetAttendanceUserFirstAttended($UserID)
		{
			//not yet implemented function
			return 'not implemented yet';
		}
		
		public static function LoadAttendanceByGroupUserDate($GroupID,$UserID,$Date)
		{
			$pdoAtt = getAttendanceByUserGroupDate($GroupID,$UserID,$Date);
			
			if($pdoAtt->rowCount() == 0)
			{
				return NULL;
			} else {
				return $pdoAtt->fetchAll(); //fetchAll as there may be more than one
			}
			
		}
		
		public static function CreateAttendance($GroupID,$UserID,$Date)
		{
			$AttBy = $_SESSION['User']->GetUserID();
			
			$AttendanceID = addAttendance($GroupID,$UserID,$Date,$AttBy);
			
			return Attendance::LoadAttendance($AttendanceID);
		}
		
		public static function LoadAttendance($AttendanceID)
		{
			$AttendanceID = intval($AttendanceID);
			
			$pdo_Attendance = getAttendance($AttendanceID);
			
			if( $pdo_Attendance->rowCount() != 1 )
			{
				return NULL;
			} else {
				return Attendance::ArrToAttendance($pdo_Attendance->fetch());
			}
		}
		
		public static function ArrToAttendance($Item)
		{
			$thisAttendance = new Attendance();
			
			$thisAttendance->SetAttendanceID($Item['id_attendance']);
			$thisAttendance->SetGroupID($Item['fld_group_id']);
			$thisAttendance->SetUserID($Item['fld_user_id']);
			$thisAttendance->SetDate($Item['fld_date']);
			$thisAttendance->SetEnteredBy($Item['fld_entered_by']);
			$thisAttendance->SetDeleted($Item['fld_deleted']);
			
			return $thisAttendance;
		}
		
		
		//this is a redundant function
		public static function LoadAttendanceForCommunityObservers($GroupID,$StartDate,$EndDate)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getAttendanceForCommunityObservers($GroupID,$StartDate,$EndDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadLastGroupAttendancesByGroupAndDate($GroupID,$Date,$WeeksBack)
		{
			$GroupID = intval($GroupID);
			
			$Group = Group::LoadGroup($GroupID);
			
			$Dates = $Group->LoadGroupDates();
			
			$LastDate = NULL; //assume NULL
			
			$FoundDate = false;
			$StartDate = NULL;
			$WeeksCounter = 0;
			
			foreach($Dates AS $ThisDate)
			{
				
				if( !$FoundDate and $ThisDate < $Date and $Group->HasAttendanceOnDateNew($ThisDate) )
				{
					$LastDate = $ThisDate;
					$FoundDate = true;
					
				}
				
				if( $FoundDate and $Group->HasAttendanceOnDateNew($ThisDate) and $WeeksCounter < $WeeksBack)
				{
					$StartDate =  $ThisDate;
					$WeeksCounter++;
				}
				
				if( $WeeksCounter >= $WeeksBack )
				{
					break;
				}
			}
			
			if( $LastDate == NULL )
			{
				return false;
			}
			
			//$arrAttendances = getMemberAttendanceByGroupAndDate($GroupID,$LastDate)->fetchAll();
			$arrAttendances = getMemberAttendanceByGroupAndDates($GroupID,$LastDate,$StartDate)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadMemberAttendancesByGroupAndDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getMemberAttendanceByGroupAndDate($GroupID,$Date)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadOtherAttendancesByGroupAndDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			$arrAttendances = getOtherAttendanceByGroupAndDate($GroupID,$Date)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
		
		public static function LoadSpecialAttendancesByGroupAndDate($GroupID,$Date)
		{
			$GroupID = intval($GroupID);
			
			
			$arrAttendances = getSpecialAttendanceByGroupAndDate($GroupID,$Date)->fetchAll();
			
			$Attendances = array();
			
			foreach( $arrAttendances as $Attendance )
			{
				$Attendances[] = Attendance::ArrToAttendance($Attendance);
			}
						
			return $Attendances;
		}
			
		public function GetUser() // use instanceof to detect user type
		{
			return \Membership\User::UniversalMemberLoader($this->UserID);
		}
		
		public function GetGroup()
		{
			return Group::LoadGroup($this->GroupID);
		}
		
		public function UpdateGroupLeader($UserID,$GroupRoleID,$StartDate,$EndDate)
		{
			$this->UserID = $UserID;
			$this->GroupRoleID = $GroupRoleID;
			$this->StartDate = $StartDate;
			$this->EndDate = $EndDate;
			
			updGroupLeader($this->GroupsRolesID,$this->UserID,$this->GroupRoleID,$this->StartDate,$this->EndDate);
			
		}
		
		public static function CreateGroupLeader($GroupID,$UserID,$GroupRoleID,$StartDate,$EndDate)
		{
			$GroupLeaderID = addGroupLeader($GroupID,$UserID,$GroupRoleID,$StartDate,$EndDate);
			
			return GroupLeader::LoadGroupLeader($GroupLeaderID);
		}
		
		public static function TransferAttendance($provider,$receiver)
		{
			
			//returns number of affected rows
			return transferAttendance($provider,$receiver)->rowCount();
		}
		
		
	} //END ATTENDANCE CLASS 
	
	function getGroupAttendanceByDate($GroupID,$Date)
	{
		global $dbh;
	
		try {
			
			$Attendances = $dbh->prepare('
			
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_date = :Date
									AND fld_deleted = 0
										
									');
			$Attendances->execute(array(	
									':GroupID' => $GroupID,
									':Date' => $Date
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getLastGroupAttended($UserID,$BeforeDate)
	{
		global $dbh;
	
		try {
			
			$LastGroup = $dbh->prepare('
			
										SELECT tbl_group_attendance.fld_date AS last_attended, fld_group_name
										FROM tbl_group_attendance
										JOIN tbl_groups
										ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
										WHERE tbl_group_attendance.fld_user_id = :UserID
										AND tbl_group_attendance.fld_date = (
																	SELECT MAX(InnerAtt.fld_date)
																	FROM tbl_group_attendance AS InnerAtt
																	WHERE InnerAtt.fld_user_id = tbl_group_attendance.fld_user_id
																	AND (InnerAtt.fld_deleted IS NULL OR InnerAtt.fld_deleted = 0)
																	AND (InnerAtt.fld_date IS NULL OR InnerAtt.fld_date <= :BeforeDate)
																	GROUP BY InnerAtt.fld_user_id
																	)
																	
									');
			$LastGroup->execute(array(	
									':UserID' => $UserID,
									':BeforeDate' => $BeforeDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $LastGroup;
	}
	
	function getCountVolunteerAttendanceByGroup($VolRole,$GroupID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
			
			$Volunteers = $dbh->prepare('
			
									SELECT COUNT( DISTINCT tbl_group_attendance.id_attendance) AS Total
											FROM tbl_staff
											JOIN tbl_groups_roles
											ON tbl_staff.fld_user_id = tbl_groups_roles.fld_user_id
											JOIN tbl_group_roles
											ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
											JOIN tbl_group_attendance
											ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
											WHERE tbl_groups_roles.fld_start_date <= :EndDate
											AND ( 
															tbl_groups_roles.fld_end_date IS NULL OR
															tbl_groups_roles.fld_end_date >= :StartDate
															)
											AND tbl_group_roles.fld_group_role = :GroupRole
											AND tbl_groups_roles.fld_group_id = tbl_group_attendance.fld_group_id
											AND tbl_groups_roles.fld_deleted = 0
											AND tbl_group_attendance.fld_group_id = :GroupID
											AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
											AND tbl_group_attendance.fld_date >= tbl_groups_roles.fld_start_date 
											AND (tbl_groups_roles.fld_end_date IS NULL OR 
												tbl_group_attendance.fld_date <= tbl_groups_roles.fld_end_date);
										
									');
			$Volunteers->execute(array(	':EndDate' => $EndDate,
									':StartDate' => $StartDate,
									':GroupRole' => $VolRole,
									':GroupID' => $GroupID,
									':StartDate2' => $StartDate,
									':EndDate2' => $EndDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Volunteers;
	}
	
	function getCountMemberAttendanceByGroup($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
	
		try {
				$Attendances = $dbh->prepare(' 	SELECT COUNT(DISTINCT tbl_group_attendance.id_attendance) AS Total
											FROM tbl_members
											JOIN tbl_user_activity_dates
											ON tbl_members.fld_user_id = tbl_user_activity_dates.fld_user_id
											JOIN tbl_group_attendance
											ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
											WHERE tbl_user_activity_dates.fld_start_date <= :EndDate
											AND ( 
															tbl_user_activity_dates.fld_end_date IS NULL OR
															tbl_user_activity_dates.fld_end_date >= :StartDate
															)
											AND tbl_user_activity_dates.fld_user_type_string = :MemberString
											AND tbl_user_activity_dates.fld_deleted = 0
											AND tbl_group_attendance.fld_group_id = :GroupID
											AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
											AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date 
											AND (tbl_user_activity_dates.fld_end_date IS NULL OR 
												tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date);
											');
				$Attendances->execute(array(
											':EndDate' => $EndDate,
											':StartDate' => $StartDate,
											':MemberString' => MEMBER_STRING,
											':GroupID' => $GroupID,
											':StartDate2' => $StartDate,
											':EndDate2' => $EndDate
											 ));
			
			} catch(PDOException $exp) {
				echo $exp->getMessage();
			}
		
		return $Attendances;
	}
	
	function delGroupAttendance($AttendanceID)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
									DELETE FROM tbl_group_attendance 
									WHERE id_attendance = :AttendanceID;
									');
			$Attendance->execute(array(	':AttendanceID' => $AttendanceID
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return true;
	}
	
	function getLastUserAttendance($UserID)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
									SELECT tbl_group_attendance.*
									FROM tbl_group_attendance
									JOIN tbl_groups
									ON tbl_group_attendance.fld_group_id = tbl_groups.id_group
									WHERE tbl_group_attendance.fld_user_id = :UserID
									AND fld_date = (
													SELECT MAX(fld_date)
													FROM tbl_group_attendance As Att2
													WHERE tbl_group_attendance.fld_user_id = Att2.fld_user_id
													AND Att2.fld_deleted = 0
													)
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_groups.fld_deleted = 0
									
									');
			$Attendance->execute(array(	':UserID' => $UserID
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendance;
	}
	
	function transferAttendance($provider,$receiver)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									UPDATE tbl_group_attendance
									SET fld_user_id = :Receiver
									WHERE fld_user_id = :Provider;
									');
			$Attendances->execute(array(	':Receiver' => $receiver,
											':Provider' => $provider
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getCountCommittedGrowersByDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
										SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id ) AS Total_Aees
										FROM tbl_group_attendance
										JOIN tbl_member_committed_dates
										ON tbl_group_attendance.fld_user_id = tbl_member_committed_dates.fld_user_id
										JOIN tbl_user_activity_dates
										ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
										WHERE tbl_group_attendance.fld_group_id = :GroupID
										AND tbl_group_attendance.fld_deleted = 0
										AND tbl_group_attendance.fld_date = :Date
										AND tbl_member_committed_dates.fld_deleted = 0
										AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
										AND ( tbl_member_committed_dates.fld_end_date IS NULL
											OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date)
										AND tbl_user_activity_dates.fld_deleted = 0
										AND tbl_user_activity_dates.fld_user_type_string = :Member
										AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
										AND ( 
											tbl_user_activity_dates.fld_end_date IS NULL 
											OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
											)
										
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':Date' => $Date,
											':Member' => MEMBER_STRING
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getCountCeasedAttendingGroupOn($GroupID,$StartDid,$EndDid)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
										SELECT COUNT(Att_Count.Last_Att) AS Total_Aees
										FROM(
										
											SELECT MAX(tbl_group_attendance.fld_date) AS Last_Att
											FROM tbl_group_attendance
											JOIN tbl_member_committed_dates
											ON tbl_group_attendance.fld_user_id = tbl_member_committed_dates.fld_user_id
											JOIN tbl_user_activity_dates
											ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
											WHERE tbl_group_attendance.fld_group_id = :GroupID
											AND tbl_group_attendance.fld_deleted = 0
											AND tbl_group_attendance.fld_date >= :StartDid
											AND tbl_member_committed_dates.fld_deleted = 0
											AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
											AND ( tbl_member_committed_dates.fld_end_date IS NULL
												OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date)
											AND tbl_user_activity_dates.fld_deleted = 0
											AND tbl_user_activity_dates.fld_user_type_string = :Member
											AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
											
											AND ( 
												tbl_user_activity_dates.fld_end_date IS NULL 
												OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
												)
											GROUP BY tbl_group_attendance.fld_user_id 
											HAVING MAX(tbl_group_attendance.fld_date) BETWEEN :StartDid2 AND :EndDid
										) AS Att_Count
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDid' => $StartDid,
											':Member' => MEMBER_STRING,
											':StartDid2' => $StartDid,
											':EndDid' => $EndDid
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getDidntAttendButDidAttend($GroupID,$StartDidnt,$EndDidnt,$DidDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									JOIN tbl_member_committed_dates
									ON tbl_members.fld_user_id = tbl_member_committed_dates.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date = :DidDate
									AND tbl_member_committed_dates.fld_deleted = 0
									AND tbl_member_committed_dates.fld_start_date <= tbl_group_attendance.fld_date
									AND ( tbl_member_committed_dates.fld_end_date IS NULL
										OR tbl_member_committed_dates.fld_end_date >= tbl_group_attendance.fld_date)
										
									AND tbl_members.fld_first_name != :CommunityObserver
									AND NOT EXISTS	(
													SELECT *
													FROM tbl_group_attendance As Att2
													WHERE Att2.fld_user_id = tbl_members.fld_user_id
													AND (Att2.fld_date BETWEEN :StartDidnt AND :EndDidnt)
													AND Att2.fld_group_id = :GroupID2
													AND Att2.fld_deleted = 0
													)
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':DidDate' => $DidDate,
											':CommunityObserver' => $CommunityObserver,
											':StartDidnt' => $StartDidnt,
											':EndDidnt' => $EndDidnt,
											':GroupID2' => $GroupID
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getFieldWorkerAttendeesInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total_Aees
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT *
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_staffs_regions.fld_user_id = tbl_staff.fld_user_id
												AND tbl_groups_regions.fld_group_id = tbl_group_attendance.fld_group_id
												AND tbl_staffs_regions.fld_start_date <= :EndDate2
												AND ( tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= :StartDate2 ) 
												AND tbl_groups_regions.fld_start_date <= :EndDate3
												AND ( tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :StartDate3 ) 
												)
									');
			$Attendance->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendance;
	} 
		
	function getFieldWorkerAttendancesInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT *
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_staffs_regions.fld_user_id = tbl_staff.fld_user_id
												AND tbl_groups_regions.fld_group_id = tbl_group_attendance.fld_group_id
												AND tbl_staffs_regions.fld_start_date <= :EndDate2
												AND ( tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= :StartDate2 ) 
												AND tbl_groups_regions.fld_start_date <= :EndDate3
												AND ( tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= :StartDate3 ) 
												)
									');
			$Attendance->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendance;
	} 
	
	function getAvgStaffAttendancesInPeriodByRegion($GroupRole,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Staff = $dbh->prepare('	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Users, COUNT(*) AS Attendances
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
												AND tbl_group_roles.fld_group_role = :GroupRole
												AND tbl_groups_roles.fld_start_date <= :EndDate2
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate2 )
												AND tbl_groups_roles.fld_deleted = 0
												AND EXISTS(
														SELECT *
														FROM tbl_groups_regions
														WHERE tbl_groups_roles.fld_group_id = tbl_groups_regions.fld_group_id
														AND tbl_groups_regions.fld_region_id = :RegionID
														AND (tbl_groups_regions.fld_start_date <= :EndDate3
															AND (tbl_groups_regions.fld_end_date IS NULL
																OR tbl_groups_regions.fld_end_date >= :StartDate3)
															)
													)
												)
									');
			$Staff->execute(array(	':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':GroupRole' => $GroupRole,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':RegionID' => $RegionID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Staff;
	}

	
	function getStaffAttendancesInPeriodByRegion($GroupRole,$RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Staff = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
												AND tbl_group_roles.fld_group_role = :GroupRole
												AND tbl_groups_roles.fld_start_date <= :EndDate2
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate2 )
												AND tbl_groups_roles.fld_deleted = 0
												AND EXISTS(
														SELECT *
														FROM tbl_groups_regions
														WHERE tbl_groups_roles.fld_group_id = tbl_groups_regions.fld_group_id
														AND tbl_groups_regions.fld_region_id = :RegionID
														AND (tbl_groups_regions.fld_start_date <= :EndDate3
															AND (tbl_groups_regions.fld_end_date IS NULL
																OR tbl_groups_regions.fld_end_date >= :StartDate3)
															)
													)
												)
									');
			$Staff->execute(array(	':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':GroupRole' => $GroupRole,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate,
									':RegionID' => $RegionID,
									':EndDate3' => $EndDate,
									':StartDate3' => $StartDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Staff;
	}
	
	function getStaffAttendancesInPeriod($GroupRole,$GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Staff = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_staff
									ON tbl_group_attendance.fld_user_id = tbl_staff.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												WHERE tbl_groups_roles.fld_user_id = tbl_staff.fld_user_id
												AND tbl_groups_roles.fld_group_id = :GroupID2
												AND tbl_group_roles.fld_group_role = :GroupRole
												AND tbl_groups_roles.fld_start_date <= :EndDate2
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= :StartDate2 )
												AND tbl_groups_roles.fld_deleted = 0
												)
									');
			$Staff->execute(array(	':GroupID' => $GroupID,
									':StartDate' => $StartDate,
									':EndDate' => $EndDate,
									':GroupID2' => $GroupID,
									':GroupRole' => $GroupRole,
									':EndDate2' => $EndDate,
									':StartDate2' => $StartDate
									 ));
	
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Staff;
	}
	
	function getNonCommittedGrowersAttendancesInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND ( tbl_members.fld_committed_date IS NULL 
									OR tbl_members.fld_committed_date > tbl_group_attendance.fld_date)
									AND tbl_members.fld_first_name != :CommunityObserver
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	} 
	
	function getAvgCommittedGrowersAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Users, COUNT(*) AS Attendances
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_committed_date <= tbl_group_attendance.fld_date
									AND tbl_members.fld_first_name != :CommunityObserver
									AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
									');
			$Attendances->execute(array(	
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver,
											':RegionID' => $RegionID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}	
	
	function getCommittedGrowersAttendancesInPeriodByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									WHERE tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_committed_date <= tbl_group_attendance.fld_date
									AND tbl_members.fld_first_name != :CommunityObserver
									AND EXISTS(
													SELECT *
													FROM tbl_groups_regions
													WHERE tbl_group_attendance.fld_group_id = tbl_groups_regions.fld_group_id
													AND tbl_groups_regions.fld_region_id = :RegionID
													AND (tbl_groups_regions.fld_start_date <= :EndDate2
														AND (tbl_groups_regions.fld_end_date IS NULL
															OR tbl_groups_regions.fld_end_date >= :StartDate2)
														)
												)
									');
			$Attendances->execute(array(	
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver,
											':RegionID' => $RegionID,
											':EndDate2' => $EndDate,
											':StartDate2' => $StartDate,
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getCommittedGrowersAttendancesInPeriod($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_committed_date <= tbl_group_attendance.fld_date
									AND tbl_members.fld_first_name != :CommunityObserver
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	} 
	
	function getAttendanceForCommunityObservers($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		global $CommunityObserver;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									JOIN tbl_members
									ON tbl_group_attendance.fld_user_id = tbl_members.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_members.fld_first_name = :CommunityObserver
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':CommunityObserver' => $CommunityObserver
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getFirstTimeAttendancesBetweenDatesByRegion($RegionID,$StartDate,$EndDate)
	{
		global $dbh;
		global $false;
		
		try {
			
			$Attendances = $dbh->prepare('	
									
									SELECT fld_group_name,  COUNT(*) AS first_time_attendances
									FROM tbl_groups
									LEFT OUTER JOIN tbl_group_attendance
									ON tbl_groups.id_group = tbl_group_attendance.fld_group_id
									WHERE id_group IN(
										
													SELECT fld_group_id
													FROM tbl_groups_regions
													WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
													AND fld_region_id = :RegionID
													AND fld_start_date <= :EndDate
													AND (fld_end_date IS NULL OR fld_end_date >= :StartDate)
																		
													)
									AND tbl_group_attendance.fld_date BETWEEN :StartDate2 AND :EndDate2
									AND (
											(tbl_groups.id_group,tbl_group_attendance.fld_date,tbl_group_attendance.fld_user_id) IN(
											SELECT fld_group_id,MIN(fld_date),fld_user_id 
											FROM tbl_group_attendance
											GROUP BY fld_user_id
											)
											OR
											(
												tbl_group_attendance.fld_date IS NULL
											)
										)
									AND tbl_group_attendance.fld_deleted = :false
									AND fld_non_group_type IS NULL
									GROUP BY id_group
									ORDER BY fld_group_name ASC;
									
									');
			$Attendances->execute(array(	
											
											':RegionID' => $RegionID,
											':EndDate' => $EndDate,
											':StartDate' => $StartDate,
											':StartDate2' => $StartDate,
											':EndDate2' => $EndDate,
											':false' => $false
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	} // getCountNewCommittedGrowersByGroupDates
	
	function getCountNewCommittedGrowersByGroupDates($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total_Aees
									FROM tbl_group_attendance
									JOIN tbl_user_activity_dates
									ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_user_activity_dates.fld_deleted = 0
									AND tbl_user_activity_dates.fld_user_type_string = :Member
									AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
									
									AND ( 
										tbl_user_activity_dates.fld_end_date IS NULL 
										OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
										)
									AND EXISTS(
												SELECT *
												FROM tbl_member_committed_dates
												WHERE tbl_member_committed_dates.fld_user_id = tbl_group_attendance.fld_user_id
												AND fld_start_date >= :StartDate2
												AND fld_start_date <= :EndDate2
												AND tbl_member_committed_dates.fld_deleted = 0
												)
									
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':Member' => MEMBER_STRING,
											':StartDate2' => $StartDate,
											':EndDate2' => $EndDate
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getFirstTimerMultipleAttendancesBetweenDates($GroupID,$StartDate,$EndDate,$AttendancesT)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT SUM(Sub_table.Total_Attendances) AS Total_Att, COUNT(Sub_table.Total_Attendances) AS Total_Aees
									FROM (	SELECT COUNT( DISTINCT tbl_group_attendance.id_attendance ) AS Total_Attendances
											FROM tbl_group_attendance
											JOIN tbl_user_activity_dates
											ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
											WHERE tbl_group_attendance.fld_group_id = :GroupID
											AND tbl_group_attendance.fld_deleted = 0
											AND tbl_group_attendance.fld_date BETWEEN :StartDate AND :EndDate
											AND tbl_user_activity_dates.fld_deleted = 0
											AND tbl_user_activity_dates.fld_user_type_string = :Member
											AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
											
											AND ( 
												tbl_user_activity_dates.fld_end_date IS NULL 
												OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
												)
											AND tbl_group_attendance.fld_user_id IN(
																SELECT Att2.fld_user_id
																FROM tbl_group_attendance AS Att2
																JOIN tbl_user_activity_dates AS Act2
																ON Att2.fld_user_id = Act2.fld_user_id
																WHERE tbl_group_attendance.fld_group_id = Att2.fld_group_id
																AND Att2.fld_deleted = 0
																AND Att2.fld_date BETWEEN :StartDate2 AND :EndDate2
																AND (fld_date,Att2.fld_user_id) IN(
																								SELECT MIN(fld_date),fld_user_id 
																								FROM tbl_group_attendance
																								GROUP BY fld_user_id
																								)
																AND Act2.fld_deleted = 0
																AND Act2.fld_user_type_string = :Member2
																AND Att2.fld_date >= Act2.fld_start_date
																AND ( 
																	Act2.fld_end_date IS NULL 
																	OR Att2.fld_date <= Act2.fld_end_date
																	)
																)
											GROUP BY tbl_group_attendance.fld_user_id
											HAVING COUNT( DISTINCT tbl_group_attendance.id_attendance ) >= :Attendances
										) AS Sub_table 
									
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':Member' => MEMBER_STRING,
											':StartDate2' => $StartDate,
											':EndDate2' => $EndDate,
											':Member2' => MEMBER_STRING,
											':Attendances' => $AttendancesT
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getFirstTimerStatsBetweenDates($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT COUNT(DISTINCT tbl_group_attendance.fld_user_id) AS Total_Attendees, COUNT( DISTINCT tbl_group_attendance.id_attendance ) AS Total_Attendances
									FROM tbl_group_attendance
									JOIN tbl_user_activity_dates
									ON tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id
									WHERE tbl_group_attendance.fld_group_id = :GroupID
									AND tbl_group_attendance.fld_deleted = 0
									AND fld_date BETWEEN :StartDate AND :EndDate
									AND tbl_group_attendance.fld_user_id IN(
														SELECT Att2.fld_user_id
														FROM tbl_group_attendance AS Att2
														JOIN tbl_user_activity_dates AS Act2
														ON Att2.fld_user_id = Act2.fld_user_id
														WHERE tbl_group_attendance.fld_group_id = Att2.fld_group_id
														AND Att2.fld_deleted = 0
														AND Att2.fld_date BETWEEN :StartDate2 AND :EndDate2
														AND (fld_date,Att2.fld_user_id) IN(
																						SELECT MIN(fld_date),fld_user_id 
																						FROM tbl_group_attendance
																						GROUP BY fld_user_id
																						)
														AND Act2.fld_deleted = 0
														AND Act2.fld_user_type_string = :Member
														AND Att2.fld_date >= Act2.fld_start_date
														AND ( 
															Act2.fld_end_date IS NULL 
															OR Att2.fld_date <= Act2.fld_end_date
															)
														)
									
									AND tbl_user_activity_dates.fld_deleted = 0
									AND tbl_user_activity_dates.fld_user_type_string = :Member2
									AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
									
									AND ( 
										tbl_user_activity_dates.fld_end_date IS NULL 
										OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
										)
									GROUP BY fld_group_id
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':StartDate2' => $StartDate,
											':EndDate2' => $EndDate,
											':Member' => MEMBER_STRING,
											':Member2' => MEMBER_STRING
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function getFirstTimeAttendancesBetweenDates($GroupID,$StartDate,$EndDate)
	{
		global $dbh;
		
		try {
			
			$Attendances = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_deleted = 0
									AND fld_date BETWEEN :StartDate AND :EndDate
									AND (fld_date,fld_user_id) IN(
												SELECT MIN(fld_date),fld_user_id 
												FROM tbl_group_attendance
												GROUP BY fld_user_id
												)
									AND EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_group_attendance.fld_date >= tbl_user_activity_dates.fld_start_date
												AND (
													tbl_user_activity_dates.fld_end_date IS NULL 
													OR tbl_group_attendance.fld_date <= tbl_user_activity_dates.fld_end_date
													)
												AND fld_user_type_string = :MEMBER_STRING
												AND tbl_user_activity_dates.fld_deleted = 0
												)
									');
			$Attendances->execute(array(	':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':MEMBER_STRING' => MEMBER_STRING
											 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendances;
	}
	
	function updDeleted($AttendanceID,$Deleted)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('
										UPDATE tbl_group_attendance SET
										fld_deleted = :Deleted
										WHERE id_attendance = :AttendanceID
										');
			$qryUpdate->execute(array(
										':Deleted' => $Deleted,
										':AttendanceID' => $AttendanceID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function getAttendance($AttendanceID)
	{
		global $dbh;
		
		try {
			
			$Attendance = $dbh->prepare('	
										SELECT *
										FROM tbl_group_attendance
										WHERE id_attendance = :AttendanceID
									');
			$Attendance->execute(array(':AttendanceID' => $AttendanceID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Attendance;
	}
	
	function addAttendance($GroupID,$UserID,$Date,$AttBy)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_group_attendance(fld_group_id,fld_date,fld_user_id,fld_entered_by) 
									  VALUES (:GroupID,:Date,:UserID,:AttBy) ');
			$qryInsert->execute(array(	':GroupID' => $GroupID,
										':Date' => ($Date == 'null') ? null : $Date,
										':UserID' => $UserID,
										':AttBy' => $AttBy
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getSpecialAttendanceByGroupAndDate($GroupID,$Date)
	{
		global $dbh;
		
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_deleted = 0
									AND fld_date = :Date
									AND fld_user_id IN(SELECT DISTINCT fld_user_id
														FROM tbl_staff)
									');
			$GroupsLeaders->execute(array(':GroupID' => $GroupID,
											':Date' => $Date ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	function getOtherAttendanceByGroupAndDate($GroupID,$Date)
	{
		global $dbh;
		global $StaffVolunteer;
		
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_deleted = 0
									AND fld_date = :Date
									AND (EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												JOIN tbl_groups
												ON tbl_groups_roles.fld_group_id = tbl_groups.id_group
												WHERE tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_groups.id_group = tbl_group_attendance.fld_group_id
												AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date )
												AND tbl_groups_roles.fld_deleted = 0
												)
											OR EXISTS(
												SELECT *
												FROM tbl_user_activity_dates
												JOIN tbl_staff_roles
												ON tbl_staff_roles.id_staff_role = tbl_user_activity_dates.fld_staff_type_id
												WHERE tbl_user_activity_dates.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_staff_roles.fld_staff_vol = :staff
												AND tbl_user_activity_dates.fld_start_date <= tbl_group_attendance.fld_date
													AND (tbl_user_activity_dates.fld_end_date IS NULL	
														OR tbl_user_activity_dates.fld_end_date >= tbl_group_attendance.fld_date
													)
												
												)
										)
									');
			$GroupsLeaders->execute(array(	
											':GroupID' => $GroupID,
											':Date' => $Date, 
											':staff' => 'staff'
											
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	function getMemberAttendanceByGroupAndDates($GroupID,$EndDate,$StartDate)
	{
		global $dbh;
		global $MemberString;
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_deleted = 0
									AND fld_date BETWEEN :StartDate AND :EndDate
									AND NOT EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												JOIN tbl_groups
												ON tbl_groups_roles.fld_group_id = tbl_groups.id_group
												WHERE tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_groups.id_group = tbl_group_attendance.fld_group_id
												AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date )
												AND tbl_groups_roles.fld_deleted = 0
												)
									AND EXISTS(
												SELECT *
													FROM tbl_user_activity_dates
													WHERE tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id	
													AND tbl_user_activity_dates.fld_deleted = 0
													AND fld_user_type_string = :MemberString
													AND fld_start_date <= tbl_group_attendance.fld_date
													AND (fld_end_date IS NULL	
														OR fld_end_date >= tbl_group_attendance.fld_date
													)
												)
									
									
									');
			$GroupsLeaders->execute(array(	
											':GroupID' => $GroupID,
											':StartDate' => $StartDate,
											':EndDate' => $EndDate,
											':MemberString' => MEMBER_STRING
											
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	function getMemberAttendanceByGroupAndDate($GroupID,$Date)
	{
		global $dbh;
		global $MemberString;
		try {
			
			$GroupsLeaders = $dbh->prepare('	
									SELECT *
									FROM tbl_group_attendance
									WHERE fld_group_id = :GroupID
									AND fld_deleted = 0
									AND fld_date = :Date
									AND NOT EXISTS(
												SELECT * 
												FROM tbl_groups_roles
												JOIN tbl_group_roles
												ON tbl_groups_roles.fld_group_role_id = tbl_group_roles.id_group_role
												JOIN tbl_groups
												ON tbl_groups_roles.fld_group_id = tbl_groups.id_group
												WHERE tbl_groups_roles.fld_user_id = tbl_group_attendance.fld_user_id
												AND tbl_groups.id_group = tbl_group_attendance.fld_group_id
												AND tbl_groups_roles.fld_start_date <= tbl_group_attendance.fld_date
												AND (tbl_groups_roles.fld_end_date IS NULL or tbl_groups_roles.fld_end_date >= tbl_group_attendance.fld_date )
												AND tbl_groups_roles.fld_deleted = 0
												)
									AND EXISTS(
												SELECT *
													FROM tbl_user_activity_dates
													WHERE tbl_group_attendance.fld_user_id = tbl_user_activity_dates.fld_user_id	
													AND tbl_user_activity_dates.fld_deleted = 0
													AND fld_user_type_string = :MemberString
													AND fld_start_date <= tbl_group_attendance.fld_date
													AND (fld_end_date IS NULL	
														OR fld_end_date >= tbl_group_attendance.fld_date
													)
												)
									
									
									');
			$GroupsLeaders->execute(array(	
											':GroupID' => $GroupID,
											':Date' => $Date,
											':MemberString' => MEMBER_STRING
											
											));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $GroupsLeaders;
	}
	
	
class StateUserActivityDates
{
	private $state_activity_date_id;
	private $user_id;
	private $start_date;
	private $end_date;
	private $branch_id;
	private $deleted;
	
	public function set_state_activity_date_id($state_activity_date_id)
	{
		$this->state_activity_date_id = $state_activity_date_id;
	}
	
	public function get_state_activity_date_id()
	{
		return $this->state_activity_date_id;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_start_date($start_date)
	{
		$this->start_date = $start_date;
	}
	
	public function get_start_date()
	{
		return $this->start_date;
	}
	
	public function set_end_date($end_date)
	{
		$this->end_date = $end_date;
	}
	
	public function get_end_date()
	{
		return $this->end_date;
	}
	
	public function set_branch_id($branch_id)
	{
		$this->branch_id = $branch_id;
	}
	
	public function get_branch_id()
	{
		return $this->branch_id;
	}
	
	public function set_deleted($deleted)
	{
		$this->deleted = $deleted;
	}
	
	public function get_deleted()
	{
		return $this->deleted;
	}
	
	public function get_branch()
	{
		return Branch::LoadBranch($this->branch_id);
	}
	
	function __construct() {
		//empty constructor that may need to be altered later
	}
   	
	public static function load_user_state_activity_dates($user_id,$deleted = false)
	{
		$user_id = intval($user_id);
		
		$array_activities = get_activity_dates_by_user_id($user_id,$deleted)->fetchAll();
		
		$all_activities = array();
		
		foreach( $array_activities as $activity )
		{
			$all_activities[] = StateUserActivityDates::array_item_to_state_user_activity($activity);
		}
		
		return $all_activities;
	}
	
	public static function load_state_activity($state_activity_date_id)
	{
		$state_activity_date_id = intval($state_activity_date_id);
		
		// Insert code to retrieve staff member here
		$pdo_activity = get_state_activity_by_id($state_activity_date_id);
		
		if($pdo_activity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arr_activity = $pdo_activity->fetch();
			return StateUserActivityDates::array_item_to_state_user_activity($arr_activity);
		}
		
	}
	
	public static function array_item_to_state_user_activity($item)
	{
		$thisActivity = new StateUserActivityDates();
				
		$thisActivity->set_state_activity_date_id($item['id_state_activity_date']);
		$thisActivity->set_user_id($item['fld_user_id']);
		$thisActivity->set_start_date($item['fld_start_date']);
		$thisActivity->set_end_date($item['fld_end_date']);
		$thisActivity->set_branch_id($item['fld_branch_id']);
		$thisActivity->set_deleted($item['fld_deleted']);
		
		return $thisActivity;
	}
	
	public function update($SafeSD,$SafeED,$state)
	{
		$this->start_date = $SafeSD;
		$this->end_date = $SafeED;
		$this->branch_id = $state;
		
		update_state_user_activity($this->state_activity_date_id,$this->start_date,$this->end_date,$this->branch_id);
	}
	
	public static function create_state_user_activity($user_id,$start_date,$end_date,$branch_id)
	{
		$user_id = intval($user_id);
					
		$new_activity_id = add_state_user_activity($user_id,$start_date,$end_date,$branch_id);
		
		return StateUserActivityDates::load_state_activity($new_activity_id);
	}
   
	
	public static function LoadCurrentUserActivity($UserID,$UserType)
	{
		//if there are two conflicting records this will only collect the first
		$UserID = intval($UserID);
				
		$pdoCurrentActivity = getCurrentActivity($UserID,$UserType);
		
		if($pdoCurrentActivity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrActivity = $pdoCurrentActivity->fetch();
			return UserActivityDates::ArrayItemToUserActivity($arrActivity);
		}
	}
	
	public static function LoadLastUserActivity($UserID,$UserType) //may return future activities
	{
		//if there are two conflicting records this will only collect the first
		$UserID = intval($UserID);
				
		$pdoLastUserActivity = getLastUserActivity($UserID,$UserType);
		
		if($pdoLastUserActivity->rowCount() == 0)
		{
			return NULL;
		} else {
			$arrActivity = $pdoLastUserActivity->fetch();
			return UserActivityDates::ArrayItemToUserActivity($arrActivity);
		}
	}
	
   	
	public function UpdateUserActivityComplete($UserID,$UserTypeString,$StartDate,$EndDate) // will probably never use this
	{
		
		$this->UserID = $UserID;
		$this->UserTypeString = $UserTypeString;
		$this->StartDate = $StartDate;
		$this->EndDate = $EndDate;
		
		
		updUserActivityComplete($this->UserActivityID,$this->UserID,$this->UserTypeString,$this->StartDate,$this->EndDate);
		
	}
} // END STATE USER ACTIVITY CLASS

function get_activity_dates_by_user_id($user_id,$deleted)
{
	global $dbh;
	
	$is_deleted = ($deleted ? '' : 'AND fld_deleted = 0' );
	
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_state_users_state_activity_dates.*
										FROM tbl_state_users_state_activity_dates
										WHERE fld_user_id = :user_id
										'.$is_deleted.'
										ORDER BY fld_start_date DESC;
										');
			$UserActivity->execute(array(
										':user_id' => $user_id
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function get_state_activity_by_id($state_activity_date_id)
{
	global $dbh;
	try {
			$state_activity = $dbh->prepare('
										SELECT tbl_state_users_state_activity_dates.*
										FROM tbl_state_users_state_activity_dates
										WHERE id_state_activity_date = :state_activity_date_id
										
										');
			$state_activity->execute(array(
										':state_activity_date_id' => $state_activity_date_id
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $state_activity;
}

function update_state_user_activity($state_activity_date_id,$start_date,$end_date,$branch_id)
{
	global $dbh;
	try {
		$update = $dbh->prepare('UPDATE tbl_state_users_state_activity_dates
									SET
										fld_start_date = :start_date,
										fld_end_date = :end_date,
										fld_branch_id = :branch_id
										WHERE id_state_activity_date = :state_activity_date_id
									');
		$update->execute(array(
								 ':start_date' => ($start_date == 'null') ? null : $start_date,
								 ':end_date' => ($end_date == 'null') ? null : $end_date,
								 ':branch_id' => $branch_id,
								 ':state_activity_date_id' => $state_activity_date_id
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $state_activity_date_id;
}

function add_state_user_activity($user_id,$start_date,$end_date,$branch_id)
{
	global $dbh;
	
	try {
		$insert = $dbh->prepare('INSERT INTO tbl_state_users_state_activity_dates(fld_user_id,fld_start_date,fld_end_date,fld_branch_id) 
								  VALUES (:user_id,:start_date,:end_date,:branch_id) ');
		$insert->execute(array(
								':user_id' => $user_id,
								':start_date' => ($start_date == 'null') ? null : $start_date,
								':end_date' => ($end_date == 'null') ? null : $end_date,
								':branch_id' => $branch_id
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $dbh->lastInsertId();
}

function getLastUserActivity($UserID,$UserType)
{
	global $dbh;
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.*
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										AND fld_user_type_string = :UserType
										AND fld_start_date = (
																SELECT MAX(fld_start_date) AS StartDate
																FROM tbl_user_activity_dates
																WHERE fld_user_id = :UserID2
																AND fld_user_type_string = :UserType2
																)
										');
			$UserActivity->execute(array(
										':UserID' => $UserID,
										':UserType' => $UserType,
										':UserID2' => $UserID,
										':UserType2' => $UserType
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function getCurrentActivity($UserID,$UserType)
{
	global $dbh;
	try {
			$UserActivity = $dbh->prepare('
										SELECT tbl_user_activity_dates.* 
										FROM tbl_user_activity_dates
										WHERE fld_user_id = :UserID
										AND fld_user_type_string = :UserType
										AND fld_start_date <= DATE(NOW())
										AND (fld_end_date IS NULL 
											OR fld_end_date >= DATE(NOW()))
										');
			$UserActivity->execute(array(
										':UserID' => $UserID,
										':UserType' => $UserType
										 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $UserActivity;
}

function updUserActivity(
				$UserActivityID,$StartDate,$EndDate,$StaffRole
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_user_activity_dates
									SET
										fld_start_date = :StartDate,
										fld_end_date = :EndDate,
										fld_staff_type_id = :StaffRole
										WHERE id_user_activity = :UserActivityID
									');
		$qryUpdate->execute(array(
								 ':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 ':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								 ':StaffRole' => ($StaffRole == 'null') ? null : $StaffRole,
								 ':UserActivityID' => $UserActivityID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updUserActivityComplete(
				$UserActivityID,$UserID,$UserTypeString,$StartDate,$EndDate
				)
{
	global $dbh;
	try {
		$qryUpdate = $dbh->prepare('UPDATE tbl_user_activity_dates
									SET
										fld_user_id = :UserID,
										fld_user_type_string = :UserTypeString,
										fld_start_date = :StartDate,
										fld_end_date = :EndDate
										WHERE id_user_activity = :UserActivityID
									');
		$qryUpdate->execute(array(
								 ':UserID' => $UserID, 
								 ':UserTypeString' => $UserTypeString,
								 ':StartDate' => ($StartDate == 'null') ? null : $StartDate,
								 ':EndDate' => ($EndDate == 'null') ? null : $EndDate,
								 ':UserActivityID' => $UserActivityID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

	class Team extends Group
	{
		
		public function UpdateTeam($TeamName,$SafeSD,$SafeED)
		{
			
			$this->SetGroupName($TeamName);
			$this->SetStartDate($SafeSD);
			$this->SetEndDate($SafeED);
			
			updTeam($this->GetGroupID(),$this->GetGroupName(),$this->GetStartDate(),$this->GetEndDate());
			
		}
		
		public function LoadTeamsCurrentBranchRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GetGroupID());
		}
		
		public function TeamHasBranchRegion()
		{
			return GroupRegion::GroupHasRegion($this->GetGroupID());
		}
					
		function __construct() {
	       parent::__construct();
	       //yet another empty constructor
	   	}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			
			$arrGroups = getActiveNonGroupsByRegions($UserID,NON_GROUP_TEAM,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = Team::ArrToTeam($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getArchivedNonGroupsByStateUserID($UserID,NON_GROUP_TEAM,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getActiveNonGroupsByStateUserID($UserID,NON_GROUP_TEAM,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadActiveTeams($show_deleted = false)
		{
			$arrTeams = getActiveTeams($show_deleted)->fetchAll();
			
			$thisTeams = array();
						
			foreach( $arrTeams As $Team )
			{
				$thisTeams[] = Team::ArrToTeam($Team);
			}
			
			return $thisTeams;
		}
		
		public static function LoadArchivedTeams($show_deleted = false)
		{
			$arrTeams = getArchivedTeams($show_deleted)->fetchAll();
			
			$thisTeams = array();
						
			foreach( $arrTeams As $Team )
			{
				$thisTeams[] = Team::ArrToTeam($Team);
			}
			
			return $thisTeams;
		}
		
		public static function CreateTeam($TeamName,$SafeSD,$SafeED)
		{
			$NewTeamID = addTeam($TeamName,$SafeSD,$SafeED);
			
			return Team::LoadTeam($NewTeamID);
		}
		
		
		public static function LoadTeam($TeamID,$show_deleted = false)
		{
			$TeamID = intval($TeamID);
			
			$pdoTeam = getTeam($TeamID,$show_deleted);
			
			if($pdoTeam->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Team::ArrToTeam($pdoTeam->fetch());
		}
		
		public static function ArrToTeam($Item)
		{
			$thisTeam = new Team();
			
			$thisTeam->SetGroupID($Item['id_group']);
			$thisTeam->SetGroupName($Item['fld_group_name']);
			$thisTeam->SetStartDate($Item['fld_start_date']);
			$thisTeam->SetEndDate($Item['fld_end_date']);
			$thisTeam->SetDeleted($Item['fld_deleted']);
			
			return $thisTeam;
		}
		
	} // END TEAM CLASS
	
	function updTeam($TeamID,$TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :TeamName,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :TeamID
										');
			$qryUpdate->execute(array(
										':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':TeamID' => $TeamID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $TeamID;
	}
	
	function getArchivedTeams($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Teams = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Teams->execute(array(
									':NonGroupType' => NON_GROUP_TEAM
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Teams;
	}
	
	
	function getActiveTeams($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Teams = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			$Teams->execute(array(
									':NonGroupType' => NON_GROUP_TEAM
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Teams;
	}
	
	function getTeam($TeamID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Team = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :TeamID
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Team->execute(array(
									':TeamID' => $TeamID,
									':NonGroupType' => NON_GROUP_TEAM
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Team;
	}
	
	function addTeam($TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_start_date,fld_end_date,fld_non_group_type) 
									  VALUES (:TeamName,:SafeSD,:SafeED,:NonGroupType) ');
			$qryInsert->execute(array(	':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':NonGroupType' => NON_GROUP_TEAM
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	//BEGIN SOCIAL EVENT CLASS
	
	class SocialEvent extends Group
	{
		
		public function Update($TeamName,$SafeSD,$SafeED)
		{
			
			$this->SetGroupName($TeamName);
			$this->SetStartDate($SafeSD);
			$this->SetEndDate($SafeED);
			
			updSocEv($this->GetGroupID(),$this->GetGroupName(),$this->GetStartDate(),$this->GetEndDate());
			
		}
		
		public function LoadTeamsCurrentBranchRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GetGroupID());
		}
		
		public function TeamHasBranchRegion()
		{
			return GroupRegion::GroupHasRegion($this->GetGroupID());
		}
					
		function __construct() {
	       parent::__construct();
	       //yet another empty constructor
	   	}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			
			$arrGroups = getActiveNonGroupsByRegions($UserID,NON_GROUP_SOC_EV,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = SocialEvent::ArrToSocEv($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getArchivedNonGroupsByStateUserID($UserID,NON_GROUP_SOC_EV,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = SocialEvent::ArrToSocEv($team);
			}
			
			return $teams;
		}
		
		public static function LoadTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getActiveNonGroupsByStateUserID($UserID,NON_GROUP_SOC_EV,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = SocialEvent::ArrToSocEv($team);
			}
			
			return $teams;
		}
		
		public static function LoadActive($show_deleted = false)
		{
			$arrSocEvs = getActiveSocEvs($show_deleted)->fetchAll();
			
			$thisSocEvs = array();
						
			foreach( $arrSocEvs As $SocEv )
			{
				$thisSocEvs[] = SocialEvent::ArrToSocEv($SocEv);
			}
			
			return $thisSocEvs;
		}
		
		public static function LoadArchived($show_deleted = false)
		{
			$arrSocEvs = getArchivedSocEvs($show_deleted)->fetchAll();
			
			$thisSocEvs = array();
						
			foreach( $arrSocEvs As $SocEv )
			{
				$thisSocEvs[] = SocialEvent::ArrToSocEv($SocEv);
			}
			
			return $thisSocEvs;
		}
		
		public static function Create($TeamName,$SafeSD,$SafeED)
		{
			$NewTeamID = addSocEv($TeamName,$SafeSD,$SafeED);
			
			return SocialEvent::Load($NewTeamID);
		}
		
		
		public static function Load($TeamID,$show_deleted = false)
		{
			$TeamID = intval($TeamID);
			
			$pdoTeam = getSocEv($TeamID,$show_deleted);
			
			if($pdoTeam->rowCount() != 1 )
			{
				return NULL;
			}
			
			return SocialEvent::ArrToSocEv($pdoTeam->fetch());
		}
		
		public static function ArrToSocEv($Item)
		{
			$thisSocEv = new SocialEvent();
			
			$thisSocEv->SetGroupID($Item['id_group']);
			$thisSocEv->SetGroupName($Item['fld_group_name']);
			$thisSocEv->SetStartDate($Item['fld_start_date']);
			$thisSocEv->SetEndDate($Item['fld_end_date']);
			$thisSocEv->SetDeleted($Item['fld_deleted']);
			
			return $thisSocEv;
		}
		
	} // END SOCIAL EVENT CLASS
	
	function getSocEv($TeamID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Team = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :TeamID
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Team->execute(array(
									':TeamID' => $TeamID,
									':NonGroupType' => NON_GROUP_SOC_EV
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Team;
	}
	
	function addSocEv($TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_start_date,fld_end_date,fld_non_group_type) 
									  VALUES (:TeamName,:SafeSD,:SafeED,:NonGroupType) ');
			$qryInsert->execute(array(	':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':NonGroupType' => NON_GROUP_SOC_EV
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	function updSocEv($TeamID,$TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :TeamName,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :TeamID
										');
			$qryUpdate->execute(array(
										':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':TeamID' => $TeamID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $TeamID;
	}
	
	function getArchivedSocEvs($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$SocEvs = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$SocEvs->execute(array(
									':NonGroupType' => NON_GROUP_SOC_EV
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $SocEvs;
	}
		
	function getActiveSocEvs($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$SocEvs = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			$SocEvs->execute(array(
									':NonGroupType' => NON_GROUP_SOC_EV
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $SocEvs;
	}
	
	//END SOCIAL EVENTS
	
	//BEGIN HOSPITAL ORIENTATIONS CLASS
	
	class HospitalOrientation extends Group
	{
		
		public function Update($TeamName,$SafeSD,$SafeED)
		{
			
			$this->SetGroupName($TeamName);
			$this->SetStartDate($SafeSD);
			$this->SetEndDate($SafeED);
			
			updHosOr($this->GetGroupID(),$this->GetGroupName(),$this->GetStartDate(),$this->GetEndDate());
			
		}
		
		public function LoadTeamsCurrentBranchRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GetGroupID());
		}
		
		public function TeamHasBranchRegion()
		{
			return GroupRegion::GroupHasRegion($this->GetGroupID());
		}
					
		function __construct() {
	       parent::__construct();
	       //yet another empty constructor
	   	}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			
			$arrGroups = getActiveNonGroupsByRegions($UserID,NON_GROUP_HOS_OR,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = SocialEvent::ArrToSocEv($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getArchivedNonGroupsByStateUserID($UserID,NON_GROUP_HOS_OR,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getActiveNonGroupsByStateUserID($UserID,NON_GROUP_HOS_OR,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadActive($show_deleted = false)
		{
			$arrHosOrs = getActiveHosOrs($show_deleted)->fetchAll();
			
			$thisHosOrs = array();
						
			foreach( $arrHosOrs As $HosOr )
			{
				$thisHosOrs[] = HospitalOrientation::ArrToHosOr($HosOr);
			}
			
			return $thisHosOrs;
		}
		
		public static function LoadArchived($show_deleted = false)
		{
			$arrHosOrs = getArchivedHosOrs($show_deleted)->fetchAll();
			
			$thisHosOrs = array();
						
			foreach( $arrHosOrs As $HosOr )
			{
				$thisHosOrs[] = HospitalOrientation::ArrToHosOr($HosOr);
			}
			
			return $thisHosOrs;
		}
		
		public static function Create($TeamName,$SafeSD,$SafeED)
		{
			$NewTeamID = addHosOr($TeamName,$SafeSD,$SafeED);
			
			return HospitalOrientation::Load($NewTeamID);
		}
		
		
		public static function Load($TeamID,$show_deleted = false)
		{
			$TeamID = intval($TeamID);
			
			$pdoTeam = getHosOr($TeamID,$show_deleted);
			
			if($pdoTeam->rowCount() != 1 )
			{
				return NULL;
			}
			
			return HospitalOrientation::ArrToHosOr($pdoTeam->fetch());
		}
		
		public static function ArrToHosOr($Item)
		{
			$thisHosOr = new HospitalOrientation();
			
			$thisHosOr->SetGroupID($Item['id_group']);
			$thisHosOr->SetGroupName($Item['fld_group_name']);
			$thisHosOr->SetStartDate($Item['fld_start_date']);
			$thisHosOr->SetEndDate($Item['fld_end_date']);
			$thisHosOr->SetDeleted($Item['fld_deleted']);
			
			return $thisHosOr;
		}
		
	} // END HOSPITAL ORIENTATIONS CLASS
	
	function getHosOr($TeamID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Team = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :TeamID
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Team->execute(array(
									':TeamID' => $TeamID,
									':NonGroupType' => NON_GROUP_HOS_OR
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Team;
	}
	
	function addHosOr($TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_start_date,fld_end_date,fld_non_group_type) 
									  VALUES (:TeamName,:SafeSD,:SafeED,:NonGroupType) ');
			$qryInsert->execute(array(	':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':NonGroupType' => NON_GROUP_HOS_OR
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	function updHosOr($TeamID,$TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :TeamName,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :TeamID
										');
			$qryUpdate->execute(array(
										':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':TeamID' => $TeamID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $TeamID;
	}
	
	function getArchivedHosOrs($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$HosOrs = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$HosOrs->execute(array(
									':NonGroupType' => NON_GROUP_HOS_OR
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $HosOrs;
	}
		
	function getActiveHosOrs($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$HosOrs = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			$HosOrs->execute(array(
									':NonGroupType' => NON_GROUP_HOS_OR
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $HosOrs;
	}
	
	//BEGIN COMMMUNITY OUTREACH CLASS
	
	class CommunityOutreach extends Group
	{
		
		public function Update($TeamName,$SafeSD,$SafeED)
		{
			
			$this->SetGroupName($TeamName);
			$this->SetStartDate($SafeSD);
			$this->SetEndDate($SafeED);
			
			updComOut($this->GetGroupID(),$this->GetGroupName(),$this->GetStartDate(),$this->GetEndDate());
			
		}
		
		public function LoadTeamsCurrentBranchRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GetGroupID());
		}
		
		public function TeamHasBranchRegion()
		{
			return GroupRegion::GroupHasRegion($this->GetGroupID());
		}
					
		function __construct() {
	       parent::__construct();
	       //yet another empty constructor
	   	}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			
			$arrGroups = getActiveNonGroupsByRegions($UserID,NON_GROUP_COM_OUT,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = SocialEvent::ArrToSocEv($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getArchivedNonGroupsByStateUserID($UserID,NON_GROUP_COM_OUT,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getActiveNonGroupsByStateUserID($UserID,NON_GROUP_COM_OUT,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadActive($show_deleted = false)
		{
			$arrComOut = getActiveComOut($show_deleted)->fetchAll();
			
			$thisComOut = array();
						
			foreach( $arrComOut As $ComOut )
			{
				$thisComOut[] = HospitalOrientation::ArrToHosOr($ComOut);
			}
			
			return $thisComOut;
		}
		
		public static function LoadArchived($show_deleted = false)
		{
			$arrComOut = getArchivedComOut($show_deleted)->fetchAll();
			
			$thisComOut = array();
						
			foreach( $arrComOut As $ComOut )
			{
				$thisComOut[] = HospitalOrientation::ArrToHosOr($ComOut);
			}
			
			return $thisComOut;
		}
		
		public static function Create($TeamName,$SafeSD,$SafeED)
		{
			$NewTeamID = addComOut($TeamName,$SafeSD,$SafeED);
			
			return CommunityOutreach::Load($NewTeamID);
		}
		
		
		public static function Load($TeamID,$show_deleted = false)
		{
			$TeamID = intval($TeamID);
			
			$pdoTeam = getComOut($TeamID,$show_deleted);
			
			if($pdoTeam->rowCount() != 1 )
			{
				return NULL;
			}
			
			return CommunityOutreach::ArrToComOut($pdoTeam->fetch());
		}
		
		public static function ArrToComOut($Item)
		{
			$thisComOut = new CommunityOutreach();
			
			$thisComOut->SetGroupID($Item['id_group']);
			$thisComOut->SetGroupName($Item['fld_group_name']);
			$thisComOut->SetStartDate($Item['fld_start_date']);
			$thisComOut->SetEndDate($Item['fld_end_date']);
			$thisComOut->SetDeleted($Item['fld_deleted']);
			
			return $thisComOut;
		}
		
	} // END COMMMUNITY OUTREACH CLASS
	
	function getComOut($TeamID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Team = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :TeamID
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Team->execute(array(
									':TeamID' => $TeamID,
									':NonGroupType' => NON_GROUP_COM_OUT
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Team;
	}
	
	function addComOut($TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_start_date,fld_end_date,fld_non_group_type) 
									  VALUES (:TeamName,:SafeSD,:SafeED,:NonGroupType) ');
			$qryInsert->execute(array(	':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':NonGroupType' => NON_GROUP_COM_OUT
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	function updComOut($TeamID,$TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :TeamName,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :TeamID
										');
			$qryUpdate->execute(array(
										':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':TeamID' => $TeamID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $TeamID;
	}
	
	function getArchivedComOut($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$ComOut = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$ComOut->execute(array(
									':NonGroupType' => NON_GROUP_COM_OUT
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $ComOut;
	}
		
	function getActiveComOut($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$ComOut = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			$ComOut->execute(array(
									':NonGroupType' => NON_GROUP_COM_OUT
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $ComOut;
	}
	
	class Training extends Group
	{
		
		public function Update($TeamName,$SafeSD,$SafeED)
		{
			
			$this->SetGroupName($TeamName);
			$this->SetStartDate($SafeSD);
			$this->SetEndDate($SafeED);
			
			updTraining($this->GetGroupID(),$this->GetGroupName(),$this->GetStartDate(),$this->GetEndDate());
			
		}
		
		public function LoadTeamsCurrentBranchRegion()
		{
			return GroupRegion::LoadGroupsCurrentRegion($this->GetGroupID());
		}
		
		public function TeamHasBranchRegion()
		{
			return GroupRegion::GroupHasRegion($this->GetGroupID());
		}
					
		function __construct() {
	       parent::__construct();
	       //yet another empty constructor
	   	}
		
		public static function LoadActiveGroupsByRegions($UserID,$show_deleted = false) //this function utilises the regions attached to a staff member
		{
			
			$arrGroups = getActiveNonGroupsByRegions($UserID,NON_GROUP_TRAIN,$show_deleted)->fetchAll();
			
			$thisGroups = array();
						
			foreach( $arrGroups As $Group )
			{
				$thisGroups[] = SocialEvent::ArrToSocEv($Group);
			}
			
			return $thisGroups;
		}
		
		public static function LoadArchivedTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getArchivedNonGroupsByStateUserID($UserID,NON_GROUP_TRAIN,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadTeamsByStateUserID($UserID,$showDeleted = false)
		{
			$arrTeams = getActiveNonGroupsByStateUserID($UserID,NON_GROUP_TRAIN,$showDeleted)->fetchAll();
			
			$teams = array();
						
			foreach( $arrTeams As $team )
			{
				$teams[] = Team::ArrToTeam($team);
			}
			
			return $teams;
		}
		
		public static function LoadActive($show_deleted = false)
		{
			$arrTraining = getActiveTraining($show_deleted)->fetchAll();
			
			$thisTraining = array();
						
			foreach( $arrTraining As $Training )
			{
				$thisTraining[] = Training::ArrToTraining($Training);
			}
			
			return $thisTraining;
		}
		
		public static function LoadArchived($show_deleted = false)
		{
			$arrTraining = getArchivedTraining($show_deleted)->fetchAll();
			
			$thisTraining = array();
						
			foreach( $arrTraining As $Training )
			{
				$thisTraining[] = Training::ArrToTraining($Training);
			}
			
			return $thisTraining;
		}
		
		public static function Create($TeamName,$SafeSD,$SafeED)
		{
			$NewTeamID = addTraining($TeamName,$SafeSD,$SafeED);
			
			return Training::Load($NewTeamID);
		}
		
		
		public static function Load($TeamID,$show_deleted = false)
		{
			$TeamID = intval($TeamID);
			
			$pdoTeam = getTraining($TeamID,$show_deleted);
			
			if($pdoTeam->rowCount() != 1 )
			{
				return NULL;
			}
			
			return Training::ArrToTraining($pdoTeam->fetch());
		}
		
		public static function ArrToTraining($Item)
		{
			$thisTraining = new Training();
			
			$thisTraining->SetGroupID($Item['id_group']);
			$thisTraining->SetGroupName($Item['fld_group_name']);
			$thisTraining->SetStartDate($Item['fld_start_date']);
			$thisTraining->SetEndDate($Item['fld_end_date']);
			$thisTraining->SetDeleted($Item['fld_deleted']);
			
			return $thisTraining;
		}
		
	} // END COMMMUNITY OUTREACH CLASS
	
	function getTraining($TeamID,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			
			$Team = $dbh->prepare('	
									SELECT *
									FROM tbl_groups
									WHERE id_group = :TeamID
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Team->execute(array(
									':TeamID' => $TeamID,
									':NonGroupType' => NON_GROUP_TRAIN
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Team;
	}
	
	function addTraining($TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_groups(fld_group_name,fld_start_date,fld_end_date,fld_non_group_type) 
									  VALUES (:TeamName,:SafeSD,:SafeED,:NonGroupType) ');
			$qryInsert->execute(array(	':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':NonGroupType' => NON_GROUP_TRAIN
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	
	function updTraining($TeamID,$TeamName,$SafeSD,$SafeED)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_groups
										SET fld_group_name = :TeamName,
										fld_start_date = :SafeSD,
										fld_end_date = :SafeED
										WHERE id_group = :TeamID
										');
			$qryUpdate->execute(array(
										':TeamName' => $TeamName,
										':SafeSD' => ($SafeSD == 'null') ? null : $SafeSD,
										':SafeED' => ($SafeED == 'null') ? null : $SafeED,
										':TeamID' => $TeamID
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $TeamID;
	}
	
	function getArchivedTraining($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Training = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE fld_end_date < DATE(NOW())
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									');
			$Training->execute(array(
									':NonGroupType' => NON_GROUP_TRAIN
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Training;
	}
		
	function getActiveTraining($show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Training = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE ( fld_end_date IS NULL OR fld_end_date >= DATE(NOW())  )
									AND fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;
									');
			$Training->execute(array(
									':NonGroupType' => NON_GROUP_TRAIN
									 ));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Training;
	}
	
	//BEGIN SHARED FUNCTIONS
	
	//BEGIN USER LABELS
	
	class UserLabels //USER LABELS CLASS
	{	
		private $IDUserLabels;
		private $IDUser;
		private $LabelsName;
		private $LabelsStatus;
		private $Created;
		private $LabelsType;
		private $LabelsDates;
		
		function GetIDUserLabels()
		{
			return $this->IDUserLabels;
		}
		
		function SetIDUserLabels($IDUserLabels)
		{
			$this->IDUserLabels = $IDUserLabels;
		}
		
		function GetIDUser()
		{
			return $this->IDUser;
		}
		
		function SetIDUser($IDUser)
		{
			$this->IDUser = $IDUser;
		}
		
		function GetLabelsName()
		{
			return $this->LabelsName;
		}
		
		function SetLabelsName($LabelsName)
		{
			$this->LabelsName = $LabelsName;
		}
		
		function GetLabelsType()
		{
			return $this->LabelsType;
		}
		
		function SetLabelsType($LabelsType)
		{
			$this->LabelsType = $LabelsType;
		}
		
		function GetLabelsDates()
		{
			return $this->LabelsDates;
		}
		
		function SetLabelsDates($LabelsDates)
		{
			$this->LabelsDates = $LabelsDates;
		}
		
		function GetLabelsStatus()
		{
			return $this->LabelsStatus;
		}
		
		function SetLabelsStatus($LabelsStatus)
		{
			$this->LabelsStatus = $LabelsStatus;
		}
		
		function GetCreated()
		{
			return $this->Created;
		}
		
		function SetCreated($Created)
		{
			$this->Created = $Created;
		}
		
		function __construct() {
	       
	       //yet another empty constructor
	   	}
		
		public static function Create($LabelsType,$LabelsName,$LabelsDates,$UserID)
		{
			
			return UserLabels::LoadUserLabels(addUserLabels($LabelsType,$LabelsName,$LabelsDates,$UserID));
		}
		
		public static function ArrToUserLabels($Item)
		{
			$thisLabels = new UserLabels();
			
			$thisLabels->SetIDUserLabels($Item['id_user_labels']);
			$thisLabels->SetIDUser($Item['fld_user_id']);
			$thisLabels->SetLabelsType($Item['fld_labels_type']);
			$thisLabels->SetLabelsName($Item['fld_labels_name']);
			$thisLabels->SetLabelsDates($Item['fld_dates']);
			$thisLabels->SetLabelsStatus($Item['fld_status']);
			$thisLabels->SetCreated($Item['fld_created']);
			
			return $thisLabels;
		}
		
		public static function LoadUserLabels($UserLabelsID)
		{
			$UserLabelsID = intval($UserLabelsID);
			
			$pdoUserLabels = getUserLabels($UserLabelsID);
			
			if($pdoUserLabels->rowCount() != 1 )
			{
				return NULL;
			}
			
			return UserLabels::ArrToUserLabels($pdoUserLabels->fetch());
		}
		
		public static function LoadUserLabelsByUserID($IDUser)
		{
			$arrLabels = getUserLabelsByUserID($IDUser)->fetchAll();
			
			$thisLabels = array();
						
			foreach( $arrLabels As $Labels )
			{
				$thisLabels[] = UserLabels::ArrToUserLabels($Labels);
			}
			
			return $thisLabels;
		}
		
		public function SaveMe()
		{
			updUserLabels($this->IDUserLabels,$this->IDUser,$this->LabelsType,$this->LabelsName,$this->LabelsDates,$this->LabelsStatus);
		}
		
		public function LoadUserLabelsFields()
		{
			return UserLabelsField::LoadUserLabelsFieldsByLabelsID($this->IDUserLabels);
		}
		
		
	} //END USER LABELS CLASS
	
	function updUserLabels($IDUserLabels,$IDUser,$LabelstType,$LabelsName,$LabelsDates,$LabelsStatus)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_user_labels
										SET fld_user_id = :IDUser,
										fld_labels_type = :LabelstType,
										fld_labels_name = :LabelsName,
										fld_dates = :LabelsDates,
										fld_status = :LabelsStatus
										WHERE id_user_labels = :IDUserLabels
										');
			$qryUpdate->execute(array(
										':IDUser' => $IDUser,
										':LabelstType' => $LabelstType,
										':LabelsName' => $LabelsName,
										':LabelsDates' => $LabelsDates,
										':LabelsStatus' => $LabelsStatus,
										':IDUserLabels' => $IDUserLabels
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addUserLabels($LabelsType,$LabelsName,$LabelsDates,$UserID)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_user_labels(fld_labels_type,fld_labels_name,fld_dates,fld_user_id,fld_status) 
									  VALUES (:fld_labels_type,:fld_labels_name,:fld_dates,:fld_user_id,:fld_status) ');
			$qryInsert->execute(array(	':fld_labels_type' => $LabelsType,
										':fld_labels_name' => $LabelsName,
										':fld_dates' => $LabelsDates,
										':fld_user_id' => $UserID,
										':fld_status' => STATUS_IN_PROCESS
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getUserLabels($UserLabelsID)
	{
		global $dbh;
		
		try {
			
			$UserLabels = $dbh->prepare('	
									SELECT *
									FROM tbl_user_labels
									WHERE id_user_labels = :UserLabelsID
									');
			$UserLabels->execute(array(':UserLabelsID' => $UserLabelsID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $UserLabels;
	}
	
	function getUserLabelsByUserID($IDUser)
	{
		global $dbh;
		
		try {
			
			$Labels = $dbh->prepare('	
									SELECT *
									FROM tbl_user_labels
									WHERE fld_user_id = :IDUser
									ORDER BY fld_created DESC;
									
									');
			$Labels->execute(array(	
									':IDUser' => $IDUser
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Labels;
	}
	
	class UserLabelsField //USER LABELS FIELDS CLASS
	{	
		private $IDUserLabelsField;
		private $IDUserLabels;
		private $UserID;
		private $Name;
		private $Address;
		private $Suburb;
		private $PostCode;
		private $State;
		
		
		function GetIDUserLabelsField()
		{
			return $this->IDUserLabelsField;
		}
		
		function SetIDUserLabelsField($IDUserLabelsField)
		{
			$this->IDUserLabelsField = $IDUserLabelsField;
		}
				
		function GetIDUserLabels()
		{
			return $this->IDUserLabels;
		}
		
		function SetIDUserLabels($IDUserLabels)
		{
			$this->IDUserLabels = $IDUserLabels;
		}
		
		function GetUserID()
		{
			return $this->UserID;
		}
		
		function SetUserID($UserID)
		{
			$this->UserID = $UserID;
		}
		
		function GetName()
		{
			return $this->Name;
		}
		
		function SetName($Name)
		{
			$this->Name = $Name;
		}
		
		function GetAddress()
		{
			return $this->Address;
		}
		
		function SetAddress($Address)
		{
			$this->Address = $Address;
		}
		
		function GetSuburb()
		{
			return $this->Suburb;
		}
		
		function SetSuburb($Suburb)
		{
			$this->Suburb = $Suburb;
		}
			
		function GetPostCode()
		{
			return $this->PostCode;
		}
		
		function SetPostCode($PostCode)
		{
			$this->PostCode = $PostCode;
		}
		
		function GetState()
		{
			return $this->State;
		}
		
		function SetState($State)
		{
			$this->State = $State;
		}
				
		function __construct() {
	       
	       //yet another empty constructor
	   	}
		
		public static function Create($IDUserLabels,$UserID,$Name,$Address,$Suburb,$PostCode,$State)
		{
			return UserLabelsField::LoadUserLabelsField(addUserLabelsField($IDUserLabels,$UserID,$Name,$Address,$Suburb,$PostCode,$State ));
		}
		
		public static function ArrToUserLabelsField($Item)
		{
			$thisLabelsField = new UserLabelsField();
			
			$thisLabelsField->SetIDUserLabelsField($Item['id_user_labels_field']);
			$thisLabelsField->SetIDUserLabels($Item['fld_user_labels_id']);
			$thisLabelsField->SetUserID($Item['fld_user_id']);
			$thisLabelsField->SetName($Item['fld_name']);
			$thisLabelsField->SetAddress($Item['fld_address']);
			$thisLabelsField->SetSuburb($Item['fld_suburb']);
			$thisLabelsField->SetPostCode($Item['fld_postcode']);
			$thisLabelsField->SetState($Item['fld_state']);
			
			return $thisLabelsField;
		}
		
		public static function LoadUserLabelsField($IDUserLabelsField)
		{
			$IDUserLabelsField = intval($IDUserLabelsField);
			
			$pdoUserLabelsField = getUserLabelsField($IDUserLabelsField);
			
			if($pdoUserLabelsField->rowCount() != 1 )
			{
				return NULL;
			}
			
			return UserLabelsField::ArrToUserLabelsField($pdoUserLabelsField->fetch());
		}
		
		public static function LoadUserLabelsFieldsByLabelsID($IDLabels)
		{
			$arrLabelsFields = getUserLabelsFieldsByLabelsID($IDLabels)->fetchAll();
			
			$thisLabels = array();
						
			foreach( $arrLabelsFields As $Labels )
			{
				$thisLabels[] = UserLabelsField::ArrToUserLabelsField($Labels);
			}
			
			return $thisLabels;
		}
		
	} //END USER LABELS FIELDS CLASS
	
	function getUserLabelsFieldsByLabelsID($LabelsID)
	{
		global $dbh;
		
		try {
			
			$LabelsFields = $dbh->prepare('	
									SELECT *
									FROM tbl_user_labels_fields
									WHERE fld_user_labels_id = :LabelsID
									ORDER BY id_user_labels_field ASC;
									
									');
			$LabelsFields->execute(array(	
									':LabelsID' => $LabelsID
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $LabelsFields;
	}
	
	function addUserLabelsField($IDUserLabels,$UserID,$Name,$Address,$Suburb,$PostCode,$State)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_user_labels_fields(fld_user_labels_id,fld_user_id,fld_name,fld_address,fld_suburb,fld_postcode,fld_state) 
									  VALUES (:IDUserLabels,:UserID,:Name,:Address,:Suburb,:PostCode,:State) ');
			$qryInsert->execute(array(	':IDUserLabels' => $IDUserLabels,
										':UserID' => $UserID,
										':Name' => $Name,
										':Address' => $Address,
										':Suburb' => $Suburb,
										':PostCode' => $PostCode,
										':State' => $State
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getUserLabelsField($IDUserLabelsField)
	{
		global $dbh;
		
		try {
			
			$UserLabelsField = $dbh->prepare('	
									SELECT *
									FROM tbl_user_labels_fields
									WHERE id_user_labels_field = :IDUserLabelsField
									');
			$UserLabelsField->execute(array(':IDUserLabelsField' => $IDUserLabelsField ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $UserLabelsField;
	}
	
	//BEGIN USER REPORTS
	
	class UserReport //USER REPORT CLASS
	{	
		private $IDUserReport;
		private $IDUser;
		private $ReportName;
		private $ReportStatus;
		private $Created;
		private $ReportType;
		private $ReportDates;
		
		function GetIDUserReport()
		{
			return $this->IDUserReport;
		}
		
		function SetIDUserReport($IDUserReport)
		{
			$this->IDUserReport = $IDUserReport;
		}
		
		function GetIDUser()
		{
			return $this->IDUser;
		}
		
		function SetIDUser($IDUser)
		{
			$this->IDUser = $IDUser;
		}
		
		function GetReportName()
		{
			return $this->ReportName;
		}
		
		function SetReportName($ReportName)
		{
			$this->ReportName = $ReportName;
		}
		
		function GetReportType()
		{
			return $this->ReportType;
		}
		
		function SetReportType($ReportType)
		{
			$this->ReportType = $ReportType;
		}
		
		function GetReportDates()
		{
			return $this->ReportDates;
		}
		
		function SetReportDates($ReportDates)
		{
			$this->ReportDates = $ReportDates;
		}
		
		function GetReportStatus()
		{
			return $this->ReportStatus;
		}
		
		function SetReportStatus($ReportStatus)
		{
			$this->ReportStatus = $ReportStatus;
		}
		
		function GetCreated()
		{
			return $this->Created;
		}
		
		function SetCreated($Created)
		{
			$this->Created = $Created;
		}
		
		function __construct() {
	       
	       //yet another empty constructor
	   	}
		
		public static function Create($ReportType,$ReportName,$ReportDates,$UserID)
		{
			
			return UserReport::LoadUserReport(addUserReport($ReportType,$ReportName,$ReportDates,$UserID));
		}
		
		public static function ArrToUserReport($Item)
		{
			$thisReport = new UserReport();
			
			$thisReport->SetIDUserReport($Item['id_user_report']);
			$thisReport->SetIDUser($Item['fld_user_id']);
			$thisReport->SetReportType($Item['fld_report_type']);
			$thisReport->SetReportName($Item['fld_report_name']);
			$thisReport->SetReportDates($Item['fld_dates']);
			$thisReport->SetReportStatus($Item['fld_status']);
			$thisReport->SetCreated($Item['fld_created']);
			
			return $thisReport;
		}
		
		public static function LoadUserReport($UserReportID)
		{
			$UserReportID = intval($UserReportID);
			
			$pdoUserReport = getUserReport($UserReportID);
			
			if($pdoUserReport->rowCount() != 1 )
			{
				return NULL;
			}
			
			return UserReport::ArrToUserReport($pdoUserReport->fetch());
		}
		
		public static function LoadUserReportsByUserID($IDUser)
		{
			$arrReports = getUserReportsByUserID($IDUser)->fetchAll();
			
			$thisReports = array();
						
			foreach( $arrReports As $Report )
			{
				$thisReports[] = UserReport::ArrToUserReport($Report);
			}
			
			return $thisReports;
		}
		
		public function SaveMe()
		{
			updUserReport($this->IDUserReport,$this->IDUser,$this->ReportType,$this->ReportName,$this->ReportDates,$this->ReportStatus);
		}
		
		public function LoadUserReportsFields()
		{
			return UserReportField::LoadUserReportFieldsByReportID($this->IDUserReport);
		}
		
		
	} //END USER REPORT CLASS
	
	function updUserReport($IDUserReport,$IDUser,$ReportType,$ReportName,$ReportDates,$ReportStatus)
	{
		global $dbh;
	
		try {
			$qryUpdate = $dbh->prepare('UPDATE tbl_user_reports
										SET fld_user_id = :IDUser,
										fld_report_type = :ReportType,
										fld_report_name = :ReportName,
										fld_dates = :ReportDates,
										fld_status = :ReportStatus
										WHERE id_user_report = :IDUserReport
										');
			$qryUpdate->execute(array(
										':IDUser' => $IDUser,
										':ReportType' => $ReportType,
										':ReportName' => $ReportName,
										':ReportDates' => $ReportDates,
										':ReportStatus' => $ReportStatus,
										':IDUserReport' => $IDUserReport
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
	
	function addUserReport($ReportType,$ReportName,$ReportDates,$UserID)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_user_reports(fld_report_type,fld_report_name,fld_dates,fld_user_id,fld_status) 
									  VALUES (:fld_report_type,:fld_report_name,:fld_dates,:fld_user_id,:fld_status) ');
			$qryInsert->execute(array(	':fld_report_type' => $ReportType,
										':fld_report_name' => $ReportName,
										':fld_dates' => $ReportDates,
										':fld_user_id' => $UserID,
										':fld_status' => STATUS_IN_PROCESS
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getUserReport($UserReportID)
	{
		global $dbh;
		
		try {
			
			$UserReport = $dbh->prepare('	
									SELECT *
									FROM tbl_user_reports
									WHERE id_user_report = :UserReportID
									');
			$UserReport->execute(array(':UserReportID' => $UserReportID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $UserReport;
	}
	
	function getUserReportsByUserID($IDUser)
	{
		global $dbh;
		
		try {
			
			$Reports = $dbh->prepare('	
									SELECT *
									FROM tbl_user_reports
									WHERE fld_user_id = :IDUser
									ORDER BY fld_created DESC;
									
									');
			$Reports->execute(array(	
									':IDUser' => $IDUser
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Reports;
	}
	
	class UserReportField //USER REPORT FIELDS CLASS
	{	
		private $IDUserReportField;
		private $IDUserReport;
		private $FieldName;
		private $FieldValue;
		private $Column;
		
		function GetIDUserReportField()
		{
			return $this->IDUserReportField;
		}
		
		function SetIDUserReportField($IDUserReportField)
		{
			$this->IDUserReportField = $IDUserReportField;
		}
				
		function GetIDUserReport()
		{
			return $this->IDUserReport;
		}
		
		function SetIDUserReport($IDUserReport)
		{
			$this->IDUserReport = $IDUserReport;
		}
		
		function GetFieldName()
		{
			return $this->FieldName;
		}
		
		function SetFieldName($FieldName)
		{
			$this->FieldName = $FieldName;
		}
		
		function GetFieldValue()
		{
			return $this->FieldValue;
		}
		
		function SetFieldValue($FieldValue)
		{
			$this->FieldValue = $FieldValue;
		}
		
		function GetColumn()
		{
			return $this->Column;
		}
		
		function SetColumn($Column)
		{
			$this->Column = $Column;
		}
				
		function __construct() {
	       
	       //yet another empty constructor
	   	}
		
		public static function CreateWithColumns($IDUserReport,$FieldName,$FieldValue,$Column)
		{
			return UserReportField::LoadUserReportField(addUserReportFieldCol($IDUserReport,$FieldName,$FieldValue,$Column));
		}
		
		public static function Create($IDUserReport,$FieldName,$FieldValue)
		{
			return UserReportField::LoadUserReportField(addUserReportField($IDUserReport,$FieldName,$FieldValue));
		}
		
		public static function ArrToUserReportField($Item)
		{
			$thisReportField = new UserReportField();
			
			$thisReportField->SetIDUserReportField($Item['id_user_report_field']);
			$thisReportField->SetIDUserReport($Item['fld_user_report_id']);
			$thisReportField->SetFieldName($Item['fld_name']);
			$thisReportField->SetFieldValue($Item['fld_value']);
			$thisReportField->SetColumn($Item['fld_column']);
			
			return $thisReportField;
		}
		
		public static function LoadUserReportField($UserReportFieldID)
		{
			$UserReportFieldID = intval($UserReportFieldID);
			
			$pdoUserReportField = getUserReportField($UserReportFieldID);
			
			if($pdoUserReportField->rowCount() != 1 )
			{
				return NULL;
			}
			
			return UserReportField::ArrToUserReportField($pdoUserReportField->fetch());
		}
		
		public static function LoadUserReportFieldsByReportID($IDReport)
		{
			$arrReportFields = getUserReportFieldsByReportID($IDReport)->fetchAll();
			
			$thisReports = array();
						
			foreach( $arrReportFields As $Report )
			{
				$thisReports[] = UserReportField::ArrToUserReportField($Report);
			}
			
			return $thisReports;
		}
		
	} //END USER REPORT FIELDS CLASS
	
	function addUserReportFieldCol($IDUserReport,$FieldName,$FieldValue,$Column)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_user_reports_fields(fld_user_report_id,fld_name,fld_value,fld_column) 
									  VALUES (:fld_user_report_id,:fld_name,:fld_value,:fld_column) ');
			$qryInsert->execute(array(	':fld_user_report_id' => $IDUserReport,
										':fld_name' => $FieldName,
										':fld_value' => $FieldValue,
										':fld_column' => $Column
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function addUserReportField($IDUserReport,$FieldName,$FieldValue)
	{
		global $dbh;
	
		try {
			$qryInsert = $dbh->prepare('INSERT INTO tbl_user_reports_fields(fld_user_report_id,fld_name,fld_value) 
									  VALUES (:fld_user_report_id,:fld_name,:fld_value) ');
			$qryInsert->execute(array(	':fld_user_report_id' => $IDUserReport,
										':fld_name' => $FieldName,
										':fld_value' => $FieldValue
									 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	}
	
	function getUserReportField($UserReportFieldID)
	{
		global $dbh;
		
		try {
			
			$UserReportField = $dbh->prepare('	
									SELECT *
									FROM tbl_user_reports_fields
									WHERE id_user_report_field = :UserReportFieldID
									');
			$UserReportField->execute(array(':UserReportFieldID' => $UserReportFieldID ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $UserReportField;
	}
	
	function getUserReportFieldsByReportID($ReportID)
	{
		global $dbh;
		
		try {
			
			$ReportFields = $dbh->prepare('	
									SELECT *
									FROM tbl_user_reports_fields
									WHERE fld_user_report_id = :ReportID
									ORDER BY id_user_report_field ASC;
									
									');
			$ReportFields->execute(array(	
									':ReportID' => $ReportID
									 ));
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $ReportFields;
	}
	
	
	//END USER REPORTS
	
	function getActiveNonGroupsByRegions($UserID,$NonGroupType,$show_deleted)
	{
		global $dbh;
		
		$show = ( $show_deleted ? '' : 'AND fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*
									FROM tbl_groups
									WHERE EXISTS(
												SELECT tbl_staffs_regions.*,tbl_groups_regions.*
												FROM tbl_staffs_regions
												JOIN tbl_groups_regions
												ON tbl_staffs_regions.fld_region_id = tbl_groups_regions.fld_region_id
												WHERE tbl_groups_regions.fld_group_id = tbl_groups.id_group
												AND tbl_groups_regions.fld_start_date <= CURDATE()
												AND (tbl_groups_regions.fld_end_date IS NULL OR tbl_groups_regions.fld_end_date >= CURDATE())
												AND tbl_staffs_regions.fld_user_id = :UserID
												AND tbl_staffs_regions.fld_start_date <= CURDATE()
												AND (tbl_staffs_regions.fld_end_date IS NULL OR tbl_staffs_regions.fld_end_date >= CURDATE())
												)
												
									AND tbl_groups.fld_non_group_type = :NonGroupType
									'.$show.'
									ORDER BY fld_group_name ASC;			
									');
			$Groups->execute(array(
									':UserID' => $UserID,
									':NonGroupType' => $NonGroupType 
									));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getArchivedNonGroupsByStateUserID($StaffID,$NonGroupType,$showDeleted)  //also grabs groups
	{
		global $dbh;
		
		$show = ( $showDeleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$Groups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE tbl_groups.fld_end_date < DATE(NOW())
									AND (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :StaffID
																		)
									OR tbl_regions.fld_branch_id IS NULL
									OR tbl_groups_regions.fld_branch_id IN(
																			SELECT fld_branch_id
																			FROM tbl_state_users_state_activity_dates
																			WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																			AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																			AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																				OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																				)
																			AND fld_user_id = :StaffID2
																			)
									)
									AND tbl_groups.fld_non_group_type = :NonGroupType
									'.$show .'
									GROUP BY tbl_groups.id_group
									ORDER BY fld_group_name ASC;
									');
			$Groups->execute(array(
									':StaffID2' => $StaffID,
									':StaffID' => $StaffID,
									':NonGroupType' => $NonGroupType
									
									 ));
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $Groups;
	}
	
	function getActiveNonGroupsByStateUserID($StaffID,$NonGroupType,$showDeleted)
	{
		global $dbh;
		
		$show = ( $showDeleted ? '' : 'AND tbl_groups.fld_deleted = 0' );
		
		try {
			$NonGroups = $dbh->prepare('SELECT tbl_groups.*, MAX(tbl_groups_regions.fld_start_date)
									FROM tbl_groups
									LEFT OUTER JOIN tbl_groups_regions
									ON tbl_groups_regions.fld_group_id = tbl_groups.id_group
									LEFT OUTER JOIN tbl_regions 
									ON tbl_groups_regions.fld_region_id = tbl_regions.id_region
									WHERE ( tbl_groups.fld_end_date IS NULL OR tbl_groups.fld_end_date >= DATE(NOW())  )
									AND (tbl_regions.fld_branch_id IN(
																		SELECT fld_branch_id
																		FROM tbl_state_users_state_activity_dates
																		WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																		AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																		AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																			OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																			)
																		AND fld_user_id = :StaffID
																		)
									OR tbl_regions.fld_branch_id IS NULL
									OR tbl_groups_regions.fld_branch_id IN(
																			SELECT fld_branch_id
																			FROM tbl_state_users_state_activity_dates
																			WHERE tbl_state_users_state_activity_dates.fld_deleted = 0
																			AND tbl_state_users_state_activity_dates.fld_start_date <= DATE(NOW())
																			AND (tbl_state_users_state_activity_dates.fld_end_date IS NULL 
																				OR tbl_state_users_state_activity_dates.fld_end_date >= DATE(NOW())
																				)
																			AND fld_user_id = :StaffID2
																			)
									)
									AND tbl_groups.fld_non_group_type = :NonGroupType
									'.$show .'
									GROUP BY tbl_groups.id_group
									ORDER BY fld_group_name ASC;
									');
			$NonGroups->execute(array(
										':StaffID' => $StaffID,
										':StaffID2' => $StaffID,
										':NonGroupType' => $NonGroupType
										));
			
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $NonGroups;
	}
	
	//END SHARED FUNCTIONS

?>