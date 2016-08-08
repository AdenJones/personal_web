<?php

function getNoMeetingReasons()
{
	global $dbh;
	
	try {
		$reasons = $dbh->query('
								SELECT * FROM tbl_no_meeting_reasons;
								');
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $reasons;
}

function getGenders()
{
	global $dbh;
	
	try {
		$genders = $dbh->query('
								SELECT * FROM tbl_genders;
								');
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $genders;
}


function getStates()
{
	global $dbh;
	
	try {
		$states = $dbh->query('
								SELECT * FROM tbl_states;
								');
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $states;
}

function getSecurityLevels()
{
	global $dbh;
	
	try {
		$security_levels = $dbh->query("
								SELECT fld_user_type,fld_security_level FROM tbl_user_types
								ORDER BY fld_security_level DESC;
								
								");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $security_levels;
}

function getImportances()
{
	global $dbh;
	
	try {
		$importances = $dbh->query("
								SELECT * FROM tbl_importance_values
								ORDER BY fld_order ASC;
								
								");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $importances;
}

function getVolunteerRoles()
{
	global $dbh;
	
	try {
		$roles = $dbh->query("
								SELECT * FROM tbl_staff_roles
								WHERE fld_staff_vol = 'volunteer';
								
								");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $roles;
}

function getStaffRoles()
{
	global $dbh;
	
	try {
		$roles = $dbh->query('
								SELECT * FROM tbl_staff_roles;
								');
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $roles;
}

function get_branches_state_user($user_id)
{
	global $dbh;
	
	try {
		$branches = $dbh->prepare('
								SELECT * FROM tbl_branches
								WHERE id_branch IN(
													SELECT fld_branch_id
													FROM tbl_state_users_state_activity_dates
													WHERE fld_deleted = 0
													AND fld_user_id = :user_id
													AND fld_start_date <= DATE(NOW())
													AND ( fld_end_date IS NULL
														OR fld_end_date >= DATE(NOW())
														)
													)
								');
		$branches->execute(array(	
								':user_id' => $user_id
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $branches;
}

function getBranch($BranchAbr)
{
	global $dbh;
	
	try {
		$branches = $dbh->prepare('
								SELECT * FROM tbl_branches
								WHERE fld_branch_abbreviation = :BranchAbr
								');
		$branches->execute(array(	
								':BranchAbr' => $BranchAbr
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $branches;
}

function getBranchByID($BranchID)
{
	global $dbh;
	
	$BranchID = intval($BranchID);
	
	try {
		$branch = $dbh->prepare('
								SELECT * FROM tbl_branches
								WHERE id_branch = :BranchID
								');
		$branch->execute(array(	
								':BranchID' => $BranchID
								));
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $branch;
}

function getBranches()
{
	global $dbh;
	
	try {
		$branches = $dbh->query('
								SELECT * FROM tbl_branches;
								');
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $branches;
}

function getClientNoteHoursTotal($dte_start,$dte_end,$id_code)
{
	global $dbh;
	global $false;
	
	try {
		$seconds = $dbh->prepare('
									SELECT SUM(TIME_TO_SEC(tbl_daily_contacts.fld_time_spent)) AS total_seconds
									FROM tbl_daily_contacts
									WHERE fld_deleted = '.$false.'
									AND fld_dated BETWEEN :dteStart AND :dteEnd
									AND fld_code_id = :idCode
									');
		$seconds->execute(array(	
								':dteStart' => $dte_start,
								':dteEnd' => $dte_end,
								':idCode' => $id_code
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_seconds = $seconds->fetch();
	
	
	return $arr_seconds['total_seconds'];
}

function getClientNoteHoursByRange($id_person,$dte_start,$dte_end,$id_code)
{
	global $dbh;
	global $false;
	
	try {
		$seconds = $dbh->prepare('
									SELECT SUM(TIME_TO_SEC(tbl_daily_contacts.fld_time_spent)) AS total_seconds
									FROM tbl_daily_contacts
									WHERE fld_deleted = '.$false.'
									AND fld_member_id = :idPers
									AND fld_dated BETWEEN :dteStart AND :dteEnd
									AND fld_code_id = :idCode
									GROUP BY fld_member_id
									');
		$seconds->execute(array(	
								':idPers' => $id_person,
								':dteStart' => $dte_start,
								':dteEnd' => $dte_end,
								':idCode' => $id_code
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_seconds = $seconds->fetch();
	
	
	return $arr_seconds['total_seconds'];
}

function getClientNoteHoursByYearMonthCode($id_person,$int_year,$int_month,$id_code)
{
	global $dbh;
	global $false;
	
	try {
		$hours = $dbh->prepare('
									SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_daily_contacts.fld_time_spent))) AS total_hours
									FROM tbl_daily_contacts
									WHERE fld_deleted = '.$false.'
									AND fld_member_id = :idPers
									AND YEAR(fld_dated) = :intYr
									AND MONTH(fld_dated) = :intMt
									AND fld_code_id = :idCode
									GROUP BY fld_member_id
									');
		$hours->execute(array(	
								':idPers' => $id_person,
								':intYr' => $int_year,
								':intMt' => $int_month,
								':idCode' => $id_code
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_hours = $hours->fetch();
	
	
	return $arr_hours['total_hours'];
}

function getClientNoteHoursByYearMonthCodeOld($id_person,$int_year,$int_month,$id_code)
{
	global $dbh;
	global $false;
	
	try {
		$hours = $dbh->prepare('
									SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_daily_contacts.fld_end_time) - TIME_TO_SEC(tbl_daily_contacts.fld_start_time))) AS total_hours
									FROM tbl_daily_contacts
									WHERE fld_deleted = '.$false.'
									AND fld_member_id = :idPers
									AND YEAR(fld_dated) = :intYr
									AND MONTH(fld_dated) = :intMt
									AND fld_code_id = :idCode
									GROUP BY fld_member_id
									');
		$hours->execute(array(	
								':idPers' => $id_person,
								':intYr' => $int_year,
								':intMt' => $int_month,
								':idCode' => $id_code
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_hours = $hours->fetch();
	
	
	return $arr_hours['total_hours'];
}

function getClientsWithNotesByRange($dte_start,$dte_end)
{
	global $dbh;
	global $false;
	
	try {
		$clients = $dbh->prepare('
									SELECT tbl_members.*
									FROM tbl_members
									WHERE fld_deleted = '.$false.'
									AND id_person IN(
													SELECT fld_member_id
													FROM tbl_daily_contacts
													WHERE fld_deleted = '.$false.'
													AND fld_dated BETWEEN :dteStart AND :dteEnd
													)
									ORDER BY fld_first_name, fld_last_name
									');
		$clients->execute(array(	
								':dteStart' => $dte_start,
								':dteEnd' => $dte_end
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $clients;
}

function getClientsWithNotesByMonthYear($int_month,$int_year)
{
	global $dbh;
	global $false;
	
	try {
		$clients = $dbh->prepare('
									SELECT tbl_members.*
									FROM tbl_members
									WHERE fld_deleted = '.$false.'
									AND id_person IN(
													SELECT fld_member_id
													FROM tbl_daily_contacts
													WHERE fld_deleted = '.$false.'
													AND YEAR(fld_dated) = :intYr
													AND MONTH(fld_dated) = :intMt
													)
									ORDER BY fld_first_name, fld_last_name
									');
		$clients->execute(array(	
								':intYr' => $int_year,
								':intMt' => $int_month
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $clients;
}

function chkAttGroup($id_attendance,$id_group)
{
	global $dbh;
	global $false;
	
	try {
		$chk_att = $dbh->prepare('
									SELECT *
									FROM tbl_attendance_groups
									WHERE fld_attendance_id = :idAtt
									AND fld_group_id = :idGrp
									AND fld_deleted = '.$false.'
									');
		$chk_att->execute(array(	
								':idAtt' => $id_attendance,
								':idGrp' => $id_group
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	if($chk_att->rowCount() > 0)
	{
		return true;
	} else {
		return false;
	}
	
}

function delAttGroup($id_attendance,$id_group)
{
	global $dbh;
	global $true;
	
	try {
		$del_att = $dbh->prepare('
									UPDATE tbl_attendance_groups
									SET fld_deleted = '.$true.'
									WHERE fld_attendance_id = :idAtt
									AND fld_group_id = :idGrp
									');
		$del_att->execute(array(	
								':idAtt' => $id_attendance,
								':idGrp' => $id_group
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function getAttendanceAttendeesByService($str_start_date,$str_end_date,$int_service_id)
{
	global $dbh;
	global $false;
	
	try {
		$attendance = $dbh->prepare('
								SELECT tbl_attendance.*,
								tbl_members.*,
								SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time))) AS total_hours,
								SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time)) AS total_seconds,
								COUNT(*) as total_attendances
								FROM tbl_attendance
								JOIN tbl_members
								ON tbl_attendance.fld_member_id = tbl_members.id_person
								WHERE tbl_attendance.fld_date_attended BETWEEN :strStartDte AND :strEndDte
								AND tbl_attendance.fld_service_id = :intSvcID
								AND tbl_attendance.fld_deleted = '.$false.'
								GROUP BY tbl_members.id_person
								ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name
							');
		$attendance->execute(array(	
								':strStartDte' => $str_start_date,
								':strEndDte' => $str_end_date,
								':intSvcID' => $int_service_id
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $attendance;
}

function getAttendanceAttendees($str_start_date,$str_end_date)
{
	global $dbh;
	global $false;
	
	try {
		$attendance = $dbh->prepare('
								SELECT tbl_attendance.*,
								tbl_members.*,
								SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time))) AS total_hours,
								COUNT(*) as total_attendances
								FROM tbl_attendance
								JOIN tbl_members
								ON tbl_attendance.fld_member_id = tbl_members.id_person
								WHERE tbl_attendance.fld_date_attended BETWEEN :strStartDte AND :strEndDte
								AND tbl_attendance.fld_deleted = '.$false.'
								GROUP BY tbl_members.id_person
								ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name
							');
		$attendance->execute(array(	
								':strStartDte' => $str_start_date,
								':strEndDte' => $str_end_date
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $attendance;
}

function getAttendancesByService($str_start_date,$str_end_date,$int_service_id)
{
	global $dbh;
	global $false;
	
	try {
		$attendance = $dbh->prepare('
								SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time))) AS total_hours,
								SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time)) AS total_seconds,
								COUNT(DISTINCT tbl_attendance.fld_member_id) AS total_members
								FROM tbl_attendance
								WHERE fld_date_attended BETWEEN :strStartDte AND :strEndDte
								AND fld_service_id = :intSvcID
								AND fld_deleted = '.$false.'
							');
		$attendance->execute(array(	
								':strStartDte' => $str_start_date,
								':strEndDte' => $str_end_date,
								':intSvcID' => $int_service_id
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $attendance;
}

function getAttendances($str_start_date,$str_end_date)
{
	global $dbh;
	global $false;
	
	try {
		$attendance = $dbh->prepare('
								SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tbl_attendance.fld_end_time) - TIME_TO_SEC(tbl_attendance.fld_start_time))) AS total_hours,
								COUNT(DISTINCT tbl_attendance.fld_member_id) AS total_members
								FROM tbl_attendance
								WHERE fld_date_attended BETWEEN :strStartDte AND :strEndDte
								AND fld_deleted = '.$false.'
							');
		$attendance->execute(array(	
								':strStartDte' => $str_start_date,
								':strEndDte' => $str_end_date
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $attendance;
}

function getMemberRoomBookings($id_member)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT tbl_room_bookings.*,
								DATEDIFF(fld_end_date,fld_start_date) AS nights_stayed,
								tbl_room_bookings_status.fld_room_booking_status
								FROM tbl_room_bookings
								JOIN tbl_room_bookings_status
								ON tbl_room_bookings.fld_room_booking_status_id = tbl_room_bookings_status.id_room_booking_status
								WHERE tbl_room_bookings.fld_member_id = :idMem
								AND tbl_room_bookings.fld_deleted = '.$false.'
								ORDER BY tbl_room_bookings.fld_start_date DESC
							');
		$bookings->execute(array(	
								':idMem' => $id_member
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

function getMemberRoomBookingStats($id_member)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT COUNT(*) AS total_bookings,
								SUM(IF(fld_cancelled = 0,DATEDIFF(fld_end_date,fld_start_date),NULL)) AS total_nights,
								AVG(IF(fld_cancelled = 0,DATEDIFF(fld_end_date,fld_start_date),NULL)) AS average_stay,
								COUNT(IF(fld_cancelled = 1,1,NULL)) AS cancelled_bookings,
								COUNT(IF(fld_cancelled = 0,1,NULL)) AS confirmed_bookings
								FROM tbl_room_bookings
								WHERE fld_member_id = :idMem
								AND fld_deleted = '.$false.'
							');
		$bookings->execute(array(	
								':idMem' => $id_member
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

/*Master Query Functions file */

function getAverageNightsStayed($str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT AVG(DATEDIFF(fld_end_date,fld_start_date)) AS average_stay
								FROM tbl_room_bookings
								WHERE fld_start_date < :strEDte1
								AND fld_end_date > :strSDte1
								AND fld_cancelled = '.$false.'
								AND fld_deleted = '.$false.'
							');
		$bookings->execute(array(	
								':strEDte1' => $str_safe_e_date,
								':strSDte1' => $str_safe_s_date
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

function getCountTotalBookings($str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT COUNT(*) AS total_bookings
								FROM tbl_room_bookings
								WHERE fld_start_date < :strEDte1
								AND fld_end_date > :strSDte1
								AND fld_cancelled = '.$false.'
								AND fld_deleted = '.$false.'
							');
		$bookings->execute(array(	
								':strEDte1' => $str_safe_e_date,
								':strSDte1' => $str_safe_s_date
								
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

function getCountDistinctMemberRoomBookings($str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT COUNT(DISTINCT fld_member_id) AS total_distinct_members
								FROM tbl_room_bookings
								WHERE fld_start_date < :strEDte1
								AND fld_end_date > :strSDte1
								AND fld_cancelled = '.$false.'
								AND fld_deleted = '.$false.'
							');
		$bookings->execute(array(	
								':strEDte1' => $str_safe_e_date,
								':strSDte1' => $str_safe_s_date
								
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

function getRoomBookingNightsInRange($str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	global $false;
	
	try {
		$bookings = $dbh->prepare('
								SELECT SUM(DATEDIFF(IF(fld_end_date > :strEDte1,:strEDte2,fld_end_date),IF(fld_start_date < :strSDte1,:strSDte2,fld_start_date))) AS nights_stayed_in_period
								FROM tbl_room_bookings
								WHERE fld_start_date < :strEDte3
								AND fld_end_date > :strSDte3
								AND fld_cancelled = '.$false.'
								AND fld_deleted = '.$false.'
							');
		$bookings->execute(array(	
								':strEDte1' => $str_safe_e_date,
								':strEDte2' => $str_safe_e_date,
								':strSDte1' => $str_safe_s_date,
								':strSDte2' => $str_safe_s_date,
								':strEDte3' => $str_safe_e_date,
								':strSDte3' => $str_safe_s_date,
								
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $bookings;
}

function updRmBkCancel($id_room_booking)
{
	global $dbh;
	global $true;
	
	try {
		$booking = $dbh->prepare('
								UPDATE tbl_room_bookings
								SET fld_cancelled = '.$true.'
								WHERE id_room_booking = :idRmBk
							');
		$booking->execute(array(	
								':idRmBk' => $id_room_booking
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function updRmBkTentConf($id_room_booking,$str_ten_conf)
{
	global $dbh;
	
	$int_ten_conf = getRoomBookingStatusID($str_ten_conf);
	
	try {
		$booking = $dbh->prepare('
								UPDATE tbl_room_bookings
								SET fld_room_booking_status_id = :intTenConf
								WHERE id_room_booking = :idRmBk
							');
		$booking->execute(array(	
								':intTenConf' => $int_ten_conf,
								':idRmBk' => $id_room_booking
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function getRoomBookingStatusID($str_ten_conf)
{
	global $dbh;
	
	try {
		$status_id = $dbh->prepare('
								SELECT id_room_booking_status
								FROM tbl_room_bookings_status
								WHERE fld_room_booking_status = :strStatus
							');
		$status_id->execute(array(	
								':strStatus' => $str_ten_conf
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_status_id = $status_id->fetch();
	return $arr_status_id['id_room_booking_status'];
	
}

function chkValRoomBookingID($id_room_booking)
{
	global $dbh;
	
	try {
		$booking = $dbh->prepare('
								SELECT *
								FROM tbl_room_bookings
								WHERE id_room_booking = :idRmBk
							');
		$booking->execute(array(	
								':idRmBk' => $id_room_booking
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $booking;
}

function updRoomBookingsDomain($id_room_bookings_domain,$str_member_rating)
{
	global $dbh;
	
	try {
		$domain = $dbh->prepare('
								UPDATE tbl_room_bookings_domain
								SET fld_member_rating = :strMemRt
								WHERE id_room_bookings_domain = :idRmBkDm
							');
		$domain->execute(array(	
								':strMemRt' => $str_member_rating,
								':idRmBkDm' => $id_room_bookings_domain
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

function getRoomBookingsDomains($id_room_booking)
{
	global $dbh;
	
	try {
		$domains = $dbh->prepare('
							SELECT tbl_room_bookings_domain.*,
							tbl_room_booking_domains.fld_room_booking_domain
							FROM tbl_room_bookings_domain
							JOIN tbl_room_booking_domains
							ON tbl_room_bookings_domain.fld_room_booking_domain_id = tbl_room_booking_domains.id_room_booking_domain
							WHERE tbl_room_bookings_domain.fld_room_booking_id = :idRmBk
							');
		$domains->execute(array(	
										':idRmBk' => $id_room_booking
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $domains;
}

function getRoomBooking($id_room_booking)
{
	global $dbh;
	global $false;
	
	try {
			$room_booking = $dbh->prepare('
											SELECT tbl_room_bookings.*,
											tbl_members.fld_first_name,
											tbl_members.fld_middle_name,
											tbl_members.fld_last_name,
											tbl_room_bookings_status.fld_room_booking_status,
											tbl_rooms.fld_room_name
											FROM tbl_room_bookings
											JOIN tbl_members
											ON tbl_room_bookings.fld_member_id = tbl_members.id_person
											JOIN tbl_room_bookings_status
											ON tbl_room_bookings.fld_room_booking_status_id = tbl_room_bookings_status.id_room_booking_status
											JOIN tbl_rooms
											ON tbl_room_bookings.fld_room_id = tbl_rooms.id_room
											WHERE id_room_booking = :idRmBk
										');
			$room_booking->execute(array(	
										':idRmBk' => $id_room_booking
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $room_booking;
}

function getRoomBookings($str_s_date,$str_e_date)
{
	global $dbh;
	global $false;
	
	try {
			$room_bookings = $dbh->prepare('
										SELECT tbl_room_bookings.*,
										tbl_members.fld_first_name,
										tbl_members.fld_middle_name,
										tbl_members.fld_last_name,
										tbl_room_bookings_status.fld_room_booking_status,
										tbl_rooms.fld_room_name,
										DATEDIFF(tbl_room_bookings.fld_end_date,tbl_room_bookings.fld_start_date) AS nights_stayed
										FROM tbl_room_bookings
										JOIN tbl_members
										ON tbl_room_bookings.fld_member_id = tbl_members.id_person
										JOIN tbl_room_bookings_status
										ON tbl_room_bookings.fld_room_booking_status_id = tbl_room_bookings_status.id_room_booking_status
										JOIN tbl_rooms
										ON tbl_room_bookings.fld_room_id = tbl_rooms.id_room
										WHERE tbl_room_bookings.fld_start_date <= :strEDte
										AND tbl_room_bookings.fld_end_date >= :strSDte
										AND tbl_room_bookings.fld_cancelled = '.$false.'
										AND tbl_room_bookings.fld_deleted = '.$false.'
										ORDER BY tbl_rooms.fld_room_name, tbl_room_bookings.fld_start_date
										
										');
			$room_bookings->execute(array(	
										':strEDte' => $str_e_date,
										':strSDte' => $str_s_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $room_bookings;
}

function chkRoomBookingDomainExists($int_booking_id,$int_domain_id)
{
	global $dbh;
	
	try {
			$add_edit_domain = $dbh->prepare('
											SELECT * FROM tbl_room_bookings_domain
											WHERE fld_room_booking_id = :idRmBk
											AND fld_room_booking_domain_id = :intDmId
											');
			$add_edit_domain->execute(array(	
											':idRmBk' => $int_booking_id,
											':intDmId' => $int_domain_id
											));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	if(count($add_edit_domain->fetchAll()) == 1)
	{
		return true;
	} else {
		return false;
	}
	
}

function addEditRoomBookingDomain($int_booking_id,$int_domain_id)
{
	global $dbh;
	
	if(chkRoomBookingDomainExists($int_booking_id,$int_domain_id))
	{
		//do nothing
	} else {
	
	try {
			$add_edit_domain = $dbh->prepare('
									INSERT INTO tbl_room_bookings_domain(
									fld_room_booking_id,
									fld_room_booking_domain_id)
									values(	:intRmBkID,
											:intDmID
										   )');
			$add_edit_domain->execute(array(	
										':intRmBkID' => $int_booking_id,
										':intDmID' => $int_domain_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}
}

function addRoomBookingDomain($int_booking_id,$int_domain_id)
{
	global $dbh;
	
	try {
			$add_domain = $dbh->prepare('
									INSERT INTO tbl_room_bookings_domain(
									fld_room_booking_id,
									fld_room_booking_domain_id)
									values(	:intRmBkID,
											:intDmID
										   )');
			$add_domain->execute(array(	':intRmBkID' => $int_booking_id,
										':intDmID' => $int_domain_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function funChkRoomStillAvailable($int_room_id,$str_start_date,$str_end_date)
{
	global $dbh;
	global $false;
	
	try {
			$get_room = $dbh->prepare('
										SELECT tbl_rooms.*, tbl_services.fld_service_name
										FROM tbl_rooms
										JOIN tbl_services
										ON tbl_rooms.fld_service_id = tbl_services.id_service
										WHERE tbl_rooms.fld_deleted = '.$false.'
										AND id_room = :intRmID
										AND NOT EXISTS(
														SELECT *
														FROM tbl_room_bookings
														WHERE tbl_rooms.id_room = tbl_room_bookings.fld_room_id
														AND fld_end_date >= :strStDte
														AND fld_start_date <= :strEdDte
														AND fld_cancelled = '.$false.'
														AND fld_deleted = '.$false.'
														)
										');
			$get_room->execute(array(	':intRmID' => $int_room_id,
										':strStDte' => $str_start_date,
										':strEdDte' => $str_end_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $get_room;
}

function funChkRoomStillAvailableNotSelf($int_room_id,$str_start_date,$str_end_date,$id_room_booking)
{
	global $dbh;
	global $false;
	
	try {
			$get_room = $dbh->prepare('
										SELECT tbl_rooms.*, tbl_services.fld_service_name
										FROM tbl_rooms
										JOIN tbl_services
										ON tbl_rooms.fld_service_id = tbl_services.id_service
										WHERE tbl_rooms.fld_deleted = '.$false.'
										AND id_room = :intRmID
										AND NOT EXISTS(
														SELECT *
														FROM tbl_room_bookings
														WHERE tbl_rooms.id_room = tbl_room_bookings.fld_room_id
														AND id_room_booking != :idRmBk
														AND fld_end_date >= :strStDte
														AND fld_start_date <= :strEdDte
														AND fld_cancelled = '.$false.'
														AND fld_deleted = '.$false.'
														)
										');
			$get_room->execute(array(	':intRmID' => $int_room_id,
										':idRmBk' => $id_room_booking,
										':strStDte' => $str_start_date,
										':strEdDte' => $str_end_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $get_room;
}

function addRoomBooking($str_start_date,$str_end_date,$int_room_id,$int_member_id,$int_booking_status,$bln_in_house,$str_referred_from,$str_reflections)
{
	global $dbh;
	global $false;
	
	try {
			$add_room = $dbh->prepare('
									INSERT INTO tbl_room_bookings(
									fld_start_date,
									fld_end_date,
									fld_room_id,
									fld_member_id,
									fld_room_booking_status_id,
									fld_in_house,
									fld_referred_from,
									fld_reflections,
									fld_cancelled,
									fld_deleted)
									values(	:dteStart,
											:dteEnd,
											:intRmID,
											:intMemID,
											:intBSID,
											:blnInHs,
											:strRfFr,
											:strRef,
											'.$false.',
											'.$false.'
										   )');
			$add_room->execute(array(	':dteStart' => $str_start_date,
										':dteEnd' => $str_end_date,
										':intRmID' => $int_room_id,
										':intMemID' => $int_member_id,
										':intBSID' => $int_booking_status,
										':blnInHs' => $bln_in_house,
										':strRfFr' => $str_referred_from,
										':strRef' => $str_reflections
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function updRoomBooking($id_room_booking,$str_start_date,$str_end_date,$int_room_id,$int_member_id,$int_booking_status,$bln_in_house,$str_referred_from,$str_reflections)
{
	global $dbh;
	global $false;
	
	try {
			$add_room = $dbh->prepare('
									UPDATE tbl_room_bookings
									SET fld_start_date = :dteStart,
										fld_end_date = :dteEnd,
										fld_room_id = :intRmID,
										fld_member_id = :intMemID,
										fld_room_booking_status_id = :intBSID,
										fld_in_house = :blnInHs,
										fld_referred_from = :strRfFr,
										fld_reflections = :strRef
									WHERE id_room_booking = :idRmBk
									');
			$add_room->execute(array(	':dteStart' => $str_start_date,
										':dteEnd' => $str_end_date,
										':intRmID' => $int_room_id,
										':intMemID' => $int_member_id,
										':intBSID' => $int_booking_status,
										':blnInHs' => $bln_in_house,
										':strRfFr' => $str_referred_from,
										':strRef' => $str_reflections,
										':idRmBk' => $id_room_booking
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function delUnselectedDomains($id_room_booking,$arr_selected_domains)
{
	global $dbh;
	
	$str_selected_domains = '';
	
	$counter = 1;
	foreach($arr_selected_domains as $domain)
	{
		$str_selected_domains .= $domain['id_room_booking_domain'];
		
		if( $counter != count($arr_selected_domains) )
		{
			$str_selected_domains .= ',';	
		}
		
		$counter++;
	}
	
	try {
		$domains = $dbh->prepare('
								DELETE FROM tbl_room_bookings_domain
								WHERE fld_room_booking_id = :idRmBk
								AND fld_room_booking_domain_id NOT IN('.$str_selected_domains.')
								');
		$domains->execute(array(	
								':idRmBk' => $id_room_booking
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $domains;
}

function getSelectedRoomBookingDomains($id_room_booking)
{
	global $dbh;
	
	try {
		$domains = $dbh->prepare('
							SELECT *
							FROM tbl_room_booking_domains
							WHERE id_room_booking_domain IN(
															SELECT fld_room_booking_domain_id
															FROM tbl_room_bookings_domain
															WHERE fld_room_booking_id = :idRmBk
															)
							');
		$domains->execute(array(	
								':idRmBk' => $id_room_booking
								));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $domains;
}

function getRoomBookingDomains()
{
	global $dbh;
	
	try {
		$domains = $dbh->query("
							SELECT *
							FROM tbl_room_booking_domains
							");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $domains;
}

function getAvailableRoomsNotSelf($str_start_date,$str_end_date,$id_room_booking)
{
	global $dbh;
	global $false;
	
	try {
			$get_rooms = $dbh->prepare('
										SELECT tbl_rooms.*, tbl_services.fld_service_name
										FROM tbl_rooms
										JOIN tbl_services
										ON tbl_rooms.fld_service_id = tbl_services.id_service
										WHERE tbl_rooms.fld_deleted = '.$false.'
										AND NOT EXISTS(
														SELECT *
														FROM tbl_room_bookings
														WHERE tbl_rooms.id_room = tbl_room_bookings.fld_room_id
														AND id_room_booking != :idRmBk
														AND fld_end_date >= :strStDte
														AND fld_start_date <= :strEdDte
														AND fld_cancelled = '.$false.'
														AND fld_deleted = '.$false.'
														)
										');
			$get_rooms->execute(array(	
										':idRmBk' => $id_room_booking,
										':strStDte' => $str_start_date,
										':strEdDte' => $str_end_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $get_rooms;
}

function getAvailableRooms($str_start_date,$str_end_date)
{
	global $dbh;
	global $false;
	
	try {
			$get_rooms = $dbh->prepare('
										SELECT tbl_rooms.*, tbl_services.fld_service_name
										FROM tbl_rooms
										JOIN tbl_services
										ON tbl_rooms.fld_service_id = tbl_services.id_service
										WHERE tbl_rooms.fld_deleted = '.$false.'
										AND NOT EXISTS(
														SELECT *
														FROM tbl_room_bookings
														WHERE tbl_rooms.id_room = tbl_room_bookings.fld_room_id
														AND fld_end_date >= :strStDte
														AND fld_start_date <= :strEdDte
														AND fld_cancelled = '.$false.'
														AND fld_deleted = '.$false.'
														)
										');
			$get_rooms->execute(array(	':strStDte' => $str_start_date,
										':strEdDte' => $str_end_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $get_rooms;
}

function getRoomBookingStatus($int_status)
{
	global $dbh;
	
	try {
			$get_status = $dbh->prepare('
									SELECT *
									FROM tbl_room_bookings_status
									WHERE id_room_booking_status = :intSts
										');
			$get_status->execute(array(	':intSts' => $int_status
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return $get_status;
}

function getRoomBookingStatuses()
{
	global $dbh;
	
	try {
		$statuses = $dbh->query("
							SELECT *
							FROM tbl_room_bookings_status
							");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $statuses;
}

function getRooms($view)
{
	global $dbh;
	
	try {
		$rooms = $dbh->query("
							SELECT tbl_rooms.*, tbl_services.fld_service_name
							FROM tbl_rooms
							JOIN tbl_services
							ON tbl_rooms.fld_service_id = tbl_services.id_service
							WHERE ".$view."
							");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $rooms;
}

function addRoom($str_room_name,$int_service_id)
{
	global $dbh;
	global $false;
	
	try {
			$add_room = $dbh->prepare('
									INSERT INTO tbl_rooms(
									fld_room_name,
									fld_service_id,
									fld_deleted)
									values(	:strRmNm,
											:intSvcID,
											'.$false.'
										   )');
			$add_room->execute(array(	':strRmNm' => $str_room_name,
										':intSvcID' => $int_service_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function updRoom($int_room_id,$str_room_name,$int_service_id)
{
	global $dbh;
	global $false;
	
	try {
			$upd_room = $dbh->prepare('
									UPDATE tbl_rooms
									SET 
									fld_service_id = :intSvcID,
									fld_room_name = :strRmNm
									WHERE id_room = :idRm
										   ');
			$upd_room->execute(array(	
											':intSvcID' => $int_service_id,
											':strRmNm' => $str_room_name,
											':idRm' => $int_room_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
}

function getRoomDetails($id_room)
{
	global $dbh;
	global $false;
	
	try {
		$room = $dbh->prepare("
								SELECT tbl_rooms.*, tbl_services.fld_service_name
								FROM tbl_rooms
								JOIN tbl_services
								ON tbl_rooms.fld_service_id = tbl_services.id_service
								WHERE id_room = :idRm
								AND fld_deleted = ".$false."
								");
		$room->execute(array(
							':idRm' => $id_room   
							));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $room;
}

function getRoom($id_room)
{
	global $dbh;
	global $false;
	
	try {
		$room = $dbh->prepare("
								SELECT *
								FROM tbl_rooms
								WHERE id_room = :idRm
								AND fld_deleted = ".$false."
								");
		$room->execute(array(
							':idRm' => $id_room   
							));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $room;
}

function getMembersDailyContactByCode($int_code,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		$daily_contacts = $dbh->prepare("
										SELECT
										tbl_members.*,
										COUNT(*) AS member_contacts
										FROM tbl_daily_contacts
										JOIN tbl_members
										ON tbl_daily_contacts.fld_member_id = tbl_members.id_person
										WHERE tbl_daily_contacts.fld_code_id = :intCde
										AND tbl_daily_contacts.fld_dated BETWEEN :strSDte AND :strEDte
										GROUP BY tbl_daily_contacts.fld_member_id
										ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name
										");
		$daily_contacts->execute(array(
									':intCde' => $int_code,
									':strSDte' => $str_s_date,
									':strEDte' => $str_e_date   
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $daily_contacts;
}

function getCountMembersDailyContactByCode($int_code,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		$daily_contacts = $dbh->prepare("
										SELECT
										COUNT(DISTINCT fld_member_id) AS total_members
										FROM tbl_daily_contacts
										WHERE fld_code_id = :intCde
										AND fld_dated BETWEEN :strSDte AND :strEDte
										
										");
		$daily_contacts->execute(array(
									':intCde' => $int_code,
									':strSDte' => $str_s_date,
									':strEDte' => $str_e_date   
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $daily_contacts;
}

function getStaffReminders($str_rem_type)
{
	global $dbh;
	global $false;
	
	try {
		$staff_reminders = $dbh->query("
										SELECT tbl_staff.*, TIMESTAMPDIFF(MONTH,CURDATE(),".$str_rem_type.") AS Till_Due
										FROM tbl_staff
										WHERE TIMESTAMPDIFF(MONTH,CURDATE(),".$str_rem_type.") < 2
										ORDER BY fld_first_name, fld_last_name
										");
		
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $staff_reminders;
}

function getAllMembers()
{
	global $dbh;
	global $false;
	
		try {
			$all_mem = $dbh->query('	
									SELECT tbl_members.*
									FROM tbl_members
									WHERE tbl_members.fld_deleted = '.$false.'
									ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name 
									');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $all_mem;
}

function getMembersOutreachSchedule()
{
	global $dbh;
	global $false;
	
		try {
			$mem_out_sch = $dbh->query('	
									SELECT tbl_members.*, MAX(tbl_attendance.fld_date_attended) AS LastAtt, MAX(tbl_daily_contacts.fld_dated) As LastCont
									FROM tbl_members
									LEFT OUTER JOIN tbl_attendance
									ON tbl_members.id_person = tbl_attendance.fld_member_id
									LEFT OUTER JOIN tbl_daily_contacts
									ON tbl_members.id_person = tbl_daily_contacts.fld_member_id
									WHERE tbl_members.fld_deleted = '.$false.'
									AND (tbl_attendance.fld_deleted IS NULL OR tbl_attendance.fld_deleted = '.$false.' )
									AND (tbl_daily_contacts.fld_deleted IS NULL OR tbl_daily_contacts.fld_deleted = '.$false.' )
									AND (tbl_members.fld_end_date IS NULL OR tbl_members.fld_end_date > CURDATE() )
									GROUP BY tbl_members.id_person
									ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name 
									');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $mem_out_sch;
}

function getGroupSearch($int_service_id,$str_group_name)
{
	global $dbh;
	
	try {
		$groups = $dbh->prepare("
										SELECT tbl_groups.*, tbl_services.fld_service_name
										FROM tbl_groups
										JOIN tbl_services
										ON tbl_groups.fld_service_id = tbl_services.id_service
										WHERE tbl_groups.fld_service_id = :intSvcID
										AND tbl_groups.fld_group_name LIKE concat('%', :strGrpNme, '%')
										");
		$groups->execute(array(
									':intSvcID' => $int_service_id,
									':strGrpNme' => $str_group_name 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $groups->fetchAll();
}

function getDailyContactNotesByMemberOnly($id_person)
{
	global $dbh;
	
	try {
		$daily_contacts = $dbh->prepare("
										SELECT
										tbl_daily_contacts.*,
										tbl_staff.fld_first_name,
										tbl_staff.fld_middle_name,
										tbl_staff.fld_last_name,
										tbl_daily_contact_codes.fld_code
										FROM tbl_daily_contacts
										JOIN tbl_staff
										ON tbl_daily_contacts.fld_staff_id = tbl_staff.id_person
										JOIN tbl_daily_contact_codes
										ON tbl_daily_contacts.fld_code_id = tbl_daily_contact_codes.id_code
										WHERE tbl_daily_contacts.fld_member_id = :idMem
										ORDER BY tbl_daily_contacts.fld_dated DESC
										");
		$daily_contacts->execute(array(
									':idMem' => $id_person 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $daily_contacts;
}

function getDailyContactNotesByMember($int_member_id,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		$daily_contacts = $dbh->prepare("
										SELECT
										tbl_daily_contacts.*,
										tbl_staff.fld_first_name,
										tbl_staff.fld_middle_name,
										tbl_staff.fld_last_name,
										tbl_daily_contact_codes.fld_code
										FROM tbl_daily_contacts
										JOIN tbl_staff
										ON tbl_daily_contacts.fld_staff_id = tbl_staff.id_person
										JOIN tbl_daily_contact_codes
										ON tbl_daily_contacts.fld_code_id = tbl_daily_contact_codes.id_code
										WHERE tbl_daily_contacts.fld_member_id = :intMemID
										AND tbl_daily_contacts.fld_dated BETWEEN :strSDte AND :strEDte
										");
		$daily_contacts->execute(array(
									':intMemID' => $int_member_id,
									':strSDte' => $str_s_date,
									':strEDte' => $str_e_date   
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $daily_contacts;
}

function getDailyContactNotesByStaff($int_staff_id,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		$daily_contacts = $dbh->prepare("
										SELECT
										tbl_daily_contacts.*,
										tbl_members.fld_first_name,
										tbl_members.fld_middle_name,
										tbl_members.fld_last_name,
										tbl_daily_contact_codes.fld_code
										FROM tbl_daily_contacts
										JOIN tbl_members
										ON tbl_daily_contacts.fld_member_id = tbl_members.id_person
										JOIN tbl_daily_contact_codes
										ON tbl_daily_contacts.fld_code_id = tbl_daily_contact_codes.id_code
										WHERE tbl_daily_contacts.fld_staff_id = :intStfID
										AND tbl_daily_contacts.fld_dated BETWEEN :strSDte AND :strEDte
										");
		$daily_contacts->execute(array(
									':intStfID' => $int_staff_id,
									':strSDte' => $str_s_date,
									':strEDte' => $str_e_date   
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $daily_contacts;
}

function getGroupAttendanceChart($str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		$grp_att_chart = $dbh->prepare("
										SELECT COUNT(tbl_attendance.id_attendance) AS Total_Attendances, 
										tbl_services.fld_service_name,
										tbl_groups.fld_group_name
										FROM tbl_services
										JOIN tbl_groups 
										ON tbl_services.id_service = tbl_groups.fld_service_id
										JOIN tbl_attendance_groups
										ON tbl_groups.id_group = tbl_attendance_groups.fld_group_id
										JOIN tbl_attendance
										ON tbl_attendance_groups.fld_attendance_id =  tbl_attendance.id_attendance
										WHERE tbl_attendance.fld_deleted = 0
										AND tbl_attendance.fld_date_attended BETWEEN :dteSDte AND :dteEDte
										GROUP BY tbl_services.fld_service_name, tbl_groups.fld_group_name
										");
		$grp_att_chart->execute(array(
									':dteSDte' => $str_s_date,
									':dteEDte' => $str_e_date 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $grp_att_chart;
}

function getServiceStatsByDate($str_service,$str_date)
{
	global $dbh;
	
	try {
		$att_stats = $dbh->prepare("
								SELECT COUNT(fld_date_attended) AS Total
								FROM tbl_attendance
								LEFT OUTER JOIN tbl_services
								ON tbl_attendance.fld_service_id = tbl_services.id_service
								WHERE fld_date_attended = :dteDte
								AND tbl_services.fld_service_name = :strSvc
								AND tbl_attendance.fld_deleted = 0
								GROUP BY tbl_attendance.fld_date_attended
								");
		$att_stats->execute(array(
									':dteDte' => $str_date,
									':strSvc' => $str_service 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arr_stats = $att_stats->fetch();
	
	if( $arr_stats['Total'] == '')
	{
		return 0;
	} else {
		
		return $arr_stats['Total'];
	}
	
}

function getServiceAtt($str_service,$str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	
	try {
		$att_stats = $dbh->prepare("
								SELECT COUNT(fld_date_attended),tbl_attendance.fld_date_attended
								FROM tbl_attendance
								LEFT OUTER JOIN tbl_services
								ON tbl_attendance.fld_service_id = tbl_services.id_service
								WHERE fld_date_attended BETWEEN :dteSDte AND :dteEDte
								AND tbl_services.fld_service_name = :strSvc
								GROUP BY tbl_services.fld_service_name, tbl_attendance.fld_date_attended
								");
		$att_stats->execute(array(
									':dteSDte' => $str_safe_s_date,
									':dteEDte' => $str_safe_e_date,
									':strSvc' => $str_service 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $att_stats;
}

function getAttForStats($str_safe_s_date,$str_safe_e_date)
{
	global $dbh;
	
	try {
		$att_stats = $dbh->prepare("
								SELECT COUNT(fld_date_attended),tbl_attendance.fld_date_attended,tbl_services.fld_service_name
								FROM tbl_attendance
								LEFT OUTER JOIN tbl_services
								ON tbl_attendance.fld_service_id = tbl_services.id_service
								WHERE fld_date_attended BETWEEN :dteSDte AND :dteEDte
								GROUP BY tbl_services.fld_service_name, tbl_attendance.fld_date_attended
								");
		$att_stats->execute(array(
									':dteSDte' => $str_safe_s_date,
									':dteEDte' => $str_safe_e_date 
									));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $att_stats;
}

function getMemberBirthdays($days_till_birthday)
{
	global $dbh;
	
	try {
		$mem_bday = $dbh->prepare("
								SELECT 
								id_person,
								fld_first_name, 
								fld_middle_name, 
								fld_last_name,
								STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y') AS NEXT_BIRTHDAY, 
								DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) AS DAYS_TILL_BIRTHDAY
								FROM tbl_members
								WHERE DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) >= 0 AND DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) <= :intDTB
								ORDER BY DAYS_TILL_BIRTHDAY ASC
								");
		$mem_bday->execute(array(':intDTB' => $days_till_birthday ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $mem_bday;
}

function getStaffBirthdays($days_till_birthday)
{
	global $dbh;
	
	try {
		$stf_bday = $dbh->prepare("
								SELECT 
								fld_first_name, 
								fld_middle_name, 
								fld_last_name,
								STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y') AS NEXT_BIRTHDAY, 
								DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) AS DAYS_TILL_BIRTHDAY
								FROM tbl_staff
								WHERE DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) >= 0 AND DATEDIFF(STR_TO_DATE(CONCAT(DAY(fld_birth_date),',',MONTH(fld_birth_date),',',YEAR(NOW())),'%d,%m,%Y'),DATE(NOW())) <= :intDTB
								ORDER BY DAYS_TILL_BIRTHDAY ASC
								");
		$stf_bday->execute(array(':intDTB' => $days_till_birthday ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $stf_bday;
}

function delDailyContact($int_dc_id)
{
	global $dbh;
	global $true;
	
	try {
		$del_dc = $dbh->prepare('
								UPDATE tbl_daily_contacts
								SET fld_deleted = '.$true.'
								WHERE id_contact = :intDCID'
								);
		$del_dc->execute(array(':intDCID' => $int_dc_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
}

//grabs Global Variables by Name // returns a string
function getGlobalVariable($id_variable_name)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_variable_value FROM tbl_global_variables WHERE id_variable_name = :idVName');
		$Value->execute(array(':idVName' => $id_variable_name ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_variable_value'];
}

function getDailyContactCodes()
{
	global $dbh;
	
		try {
			$codes = $dbh->query('	
									SELECT *
									FROM tbl_daily_contact_codes
									');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $codes;
}

function getDailyContactCodeName($int_code)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_code
								FROM tbl_daily_contact_codes
								WHERE id_code = :intCde');
		$Value->execute(array(':intCde' => $int_code ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	
	return $Value;
}

function getStaffReminderTypes()
{
	global $dbh;
	
		try {
			$reminder_types = $dbh->query('	
									SELECT *
									FROM tbl_staff_reminder_options
									');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $reminder_types;
}

//function getDefaultPage
function getDefaultPage($user_id)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_default_page
								FROM tbl_users JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.id_user = :user_id');
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_default_page'];
}

//returns the user type based upon user id
function getUserType($user_id)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_user_type
								FROM tbl_users JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.id_user = :user_id');
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_user_type'];
}

//grabs table name for current user
function getUserTableDetails($user_id)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_table_name, fld_column_name
								FROM tbl_users JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_users.id_user = :user_id');
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array;
	
}

//grabs user name of currently logged in user
function getUserName($user_id)
{
	global $dbh;
	
	//grab the table name
	$this_user_details = getUserTableDetails($user_id);
	$this_user_table = $this_user_details['fld_table_name'];
	$this_user_column = $this_user_details['fld_column_name'];
	
	//this function depends upon all person types
	//having consistent name fields and id_person
	
	$strQuery = 'SELECT fld_first_name, fld_middle_name, fld_last_name
						FROM '.$this_user_table.' 
						JOIN tbl_users
						ON '.$this_user_table.'.id_person = tbl_users.'.$this_user_column.'
						WHERE tbl_users.id_user = :user_id';
	
	try {
		$Value = $dbh->prepare($strQuery);
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_first_name'].' '.$this_array['fld_middle_name'].' '.$this_array['fld_last_name'];
	
	
}

//gets the page name by page_id
function getPageName($page_id)
{
	
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_page_name
								FROM tbl_pages
								WHERE id_page = :page_id');
		$Value->execute(array(':page_id' => $page_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_page_name'];
}

//gets the page name by page_id
function getPageEditName($page_id)
{
	
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT fld_page_edit_name
								FROM tbl_pages
								WHERE id_page = :page_id');
		$Value->execute(array(':page_id' => $page_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$this_array = $Value->fetch();
	
	return $this_array['fld_page_edit_name'];
}

function getPageForSecurity($this_page,$this_user)
{
	global $dbh;
	
	try {
		$Value = $dbh->prepare('SELECT tbl_user_type_pages.fld_page_id
								FROM tbl_users
								JOIN tbl_user_type_pages
								ON tbl_users.fld_user_type_id = tbl_user_type_pages.fld_user_type_id
								WHERE tbl_users.id_user = :this_user
								AND tbl_user_type_pages.fld_page_id = :this_page');
		$Value->execute(array(':this_user' => $this_user,
								':this_page' => $this_page
								 ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Value;
}



//gets the navigation details
function getNav($user_id)
{
	global $admin;
	global $dbh;
	
	//grab the current user
	$strUserType = getUserType($user_id);
	
	//admins can access all, other users are limited
	if( $strUserType == $admin )
	{
		$strQuery = 'select id_page, fld_page_name, fld_menu_category_name
					from tbl_menu_categories 
					join tbl_pages
					on tbl_menu_categories.id_menu_category = tbl_pages.fld_menu_category_id
					order by tbl_menu_categories.fld_menu_order, tbl_pages.fld_menu_item_order;';
		try {
		
			$Nav = $dbh->query($strQuery);
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	} else {
		$strQuery = 'select id_page, fld_page_name, fld_menu_category_name
					from tbl_menu_categories 
					join tbl_pages
					on tbl_menu_categories.id_menu_category = tbl_pages.fld_menu_category_id
					join tbl_user_type_pages
					on tbl_pages.id_page = tbl_user_type_pages.fld_page_id
					join tbl_user_types
					on tbl_user_type_pages.fld_user_type_id = tbl_user_types.id_user_type
					where tbl_user_types.fld_user_type = :usrType
					order by tbl_menu_categories.fld_menu_order, tbl_pages.fld_menu_item_order;';
	
		try {
		
			$Nav = $dbh->prepare($strQuery);
			$Nav->execute(array(':usrType' => $strUserType
								 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	}

	
		
	return $Nav;
}

function getStaffFiltered($view,$current = true)
{
	global $dbh;
	
	$str_filter = ($current) ? 'fld_end_date IS NULL OR fld_end_date > CURDATE()' : 'fld_end_date <= CURDATE()';
	
	try {
		
			$staff = $dbh->query('
								SELECT tbl_staff.*,
								tbl_user_types.fld_user_type
								FROM tbl_staff
								LEFT OUTER JOIN tbl_users
								ON tbl_staff.id_person = tbl_users.fld_staff_id
								LEFT OUTER JOIN tbl_user_types
								ON tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE '.$str_filter.'
								'.$view.' 
								ORDER BY fld_first_name, fld_last_name;');
		
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $staff;
}

function getStaff($view,$staff_not_in = '0')
{
	global $dbh;
	
	//convert the list into something that pdo can work with
	$arr_staff = explode(',', $staff_not_in);
	$placeholders = rtrim(str_repeat('?, ', count($arr_staff)), ', ') ;
	
	try {
		
			$staff = $dbh->prepare('SELECT tbl_staff.id_person, tbl_staff.fld_first_name, tbl_staff.fld_middle_name, tbl_staff.fld_last_name, tbl_staff.fld_birth_date, tbl_staff.fld_work_mobile, tbl_staff.fld_start_date, tbl_staff.fld_end_date, tbl_staff.fld_deleted, tbl_user_types.fld_user_type
								from tbl_staff
								left outer join tbl_users
								on tbl_staff.id_person = tbl_users.fld_staff_id
								left outer join tbl_user_types
								on tbl_users.fld_user_type_id = tbl_user_types.id_user_type
								WHERE tbl_staff.id_person NOT IN('.$placeholders.') '.$view.' 
								order by fld_first_name, fld_last_name;');
			$staff->execute($arr_staff);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $staff;
}

function getGroupsById($int_service_id,$view)
{
	global $dbh;
		
	try {
		
			$groups = $dbh->prepare('SELECT 
								tbl_groups.*,
								tbl_services.fld_service_name
								FROM tbl_groups
								LEFT OUTER JOIN tbl_services
								ON tbl_groups.fld_service_id = tbl_services.id_service
								WHERE tbl_groups.fld_service_id = :idSrv '.$view.' 
								ORDER BY fld_start_date DESC;');
			$groups->execute(array(':idSrv' => $int_service_id
								 ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $groups;
}

function getGroups($view,$group_not_in = '0')
{
	global $dbh;
	
	//convert the list into something that pdo can work with
	$arr_groups = explode(',', $group_not_in);
	$placeholders = rtrim(str_repeat('?, ', count($arr_groups)), ', ') ;
	
	try {
		
			$groups = $dbh->prepare('SELECT 
								tbl_groups.*,
								tbl_services.fld_service_name
								FROM tbl_groups
								LEFT OUTER JOIN tbl_services
								ON tbl_groups.fld_service_id = tbl_services.id_service
								WHERE tbl_groups.id_group NOT IN('.$placeholders.') '.$view.' 
								ORDER BY fld_service_id, fld_group_name ;');
			$groups->execute($arr_groups);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $groups;
}

/*  This function selects all members who have atteneded a group in the period but is distinct  */
function getGroupAttendeesRangeDistinct($int_group_id,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		
			$attendees = $dbh->prepare('
								SELECT
								DISTINCT tbl_members.*
								FROM tbl_groups
								JOIN tbl_attendance_groups
								ON tbl_groups.id_group = tbl_attendance_groups.fld_group_id
								JOIN tbl_attendance
								ON tbl_attendance_groups.fld_attendance_id = tbl_attendance.id_attendance
								JOIN tbl_members
								ON tbl_attendance.fld_member_id = tbl_members.id_person
								WHERE tbl_attendance.fld_deleted = 0
								AND tbl_groups.id_group = :intGrp
								AND tbl_attendance.fld_date_attended BETWEEN :dteSDte AND :dteEDte
								ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name
								');
			$attendees->execute(array(
									':intGrp' => $int_group_id,
									':dteSDte' => $str_s_date,
									':dteEDte' => $str_e_date
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $attendees;
}


/*  This function selects all attendance records, can have duplicate members */
function getGroupAttendeesRange($int_group_id,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		
			$attendees = $dbh->prepare('
								SELECT
								tbl_members.*
								FROM tbl_groups
								JOIN tbl_attendance_groups
								ON tbl_groups.id_group = tbl_attendance_groups.fld_group_id
								JOIN tbl_attendance
								ON tbl_attendance_groups.fld_attendance_id = tbl_attendance.id_attendance
								JOIN tbl_members
								ON tbl_attendance.fld_member_id = tbl_members.id_person
								WHERE tbl_attendance.fld_deleted = 0
								AND tbl_groups.id_group = :intGrp
								AND tbl_attendance.fld_date_attended BETWEEN :dteSDte AND :dteEDte
								');
			$attendees->execute(array(
									':intGrp' => $int_group_id,
									':dteSDte' => $str_s_date,
									':dteEDte' => $str_e_date
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $attendees;
}

function getGroupExpenditure($int_group_id,$str_s_date,$str_e_date)
{
	global $dbh;
	
	try {
		
			$grp_exp = $dbh->prepare('
								SELECT
								SUM(fld_money_spent) AS Expenditure,
								AVG(fld_money_spent) As Avg_Exp
								FROM tbl_groups
								JOIN tbl_group_reflections
								ON tbl_groups.id_group = tbl_group_reflections.fld_group_id
								WHERE tbl_groups.id_group = :intGrp
								AND tbl_group_reflections.fld_dated BETWEEN :dteSDte AND :dteEDte
								GROUP BY tbl_groups.id_group
								');
			$grp_exp->execute(array(
									':intGrp' => $int_group_id,
									':dteSDte' => $str_s_date,
									':dteEDte' => $str_e_date
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $grp_exp;
}

function getGroupAttendees($int_group_id,$dte_dated)
{
	global $dbh;
	
	try {
		
			$attendees = $dbh->prepare('
								SELECT
								tbl_members.id_person,
								tbl_members.fld_first_name,
								tbl_members.fld_middle_name,
								tbl_members.fld_last_name
								FROM tbl_groups
								JOIN tbl_attendance_groups
								ON tbl_groups.id_group = tbl_attendance_groups.fld_group_id
								JOIN tbl_attendance
								ON tbl_attendance_groups.fld_attendance_id = tbl_attendance.id_attendance
								JOIN tbl_members
								ON tbl_attendance.fld_member_id = tbl_members.id_person
								WHERE tbl_attendance.fld_deleted = 0
								AND tbl_groups.id_group = :intGrp
								AND tbl_attendance.fld_date_attended = :dteDated
								');
			$attendees->execute(array(
									':intGrp' => $int_group_id,
									':dteDated' => $dte_dated
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $attendees;
}

//gets group by id
function getGroupReflection($int_group_reflection_id)
{
	global $dbh;
	
	try {
			$group_ref = $dbh->prepare('
										SELECT *
										FROM tbl_group_reflections
										WHERE id_group_reflection = :intGrID
										AND fld_deleted = 0
										');
			$group_ref->execute(array(	':intGrID' => $int_group_reflection_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $group_ref;
}

//gets group reflections by group
function getGroupReflections($id_group)
{
	global $dbh;
	
	try {
			$group_refs = $dbh->prepare('
										SELECT *
										FROM tbl_group_reflections
										WHERE fld_group_id = :intGrID
										AND fld_deleted = 0
										');
			$group_refs->execute(array(	':intGrID' => $id_group
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $group_refs;
}

function updGroupReflection($int_group_reflection_id,$str_guest_speaker,$str_reflections,$dec_money_spent)
{
	global $dbh;
	global $false;
	
	$dec_money_spent = trim($dec_money_spent);
	
	if( $dec_money_spent == '')
	{
		$dec_money_spent = 0;
	}
	
	try {
			$add_group_ref = $dbh->prepare('
									UPDATE tbl_group_reflections
									SET 
									fld_guest_speaker = :strGSP,
									fld_reflections = :strRef,
									fld_money_spent = :decMS
									WHERE id_group_reflection = :intGrpRef
										   ');
			$add_group_ref->execute(array(	
											':strGSP' => $str_guest_speaker,
											':strRef' => $str_reflections,
											':decMS' => $dec_money_spent,
											':intGrpRef' => $int_group_reflection_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
}

function addGroupReflection($int_group_id,$dte_dated,$str_guest_speaker,$str_reflections,$dec_money_spent)
{
	global $dbh;
	global $false;
	
	$dec_money_spent = trim($dec_money_spent);
	
	if( $dec_money_spent == '')
	{
		$dec_money_spent = 0;
	}
	
	try {
			$add_group_ref = $dbh->prepare('
									INSERT INTO tbl_group_reflections(
									fld_group_id,
									fld_dated,
									fld_guest_speaker,
									fld_reflections,
									fld_money_spent,
									fld_deleted)
									values(	:idGrp,
											:dteDated,
											:strGSP,
											:strRef,
											:decMS,
											'.$false.'
										   )');
			$add_group_ref->execute(array(	':idGrp' => $int_group_id,
											':dteDated' => $dte_dated,
											':strGSP' => $str_guest_speaker,
											':strRef' => $str_reflections,
											':decMS' => $dec_money_spent
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function getGroup($int_group)
{
	global $dbh;
	
	try {
		
			$group = $dbh->prepare('
								SELECT tbl_groups.*,
								tbl_services.fld_service_name
								FROM tbl_groups
								LEFT OUTER JOIN tbl_services
								ON tbl_groups.fld_service_id = tbl_services.id_service
								WHERE id_group = :intGrp
								');
			$group->execute(array(':intGrp' => $int_group
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $group;
}

function editAttendance($int_att_id,$tme_start_time,$tme_end_time)
{
	global $dbh;
	global $false;
	
	try {
			$edit_attendance = $dbh->prepare('
									UPDATE tbl_attendance
									SET fld_start_time = :tmeStart,
									fld_end_time = :tmeEnd
									WHERE id_attendance = :intAttID
									');
			$edit_attendance->execute(array(	
											':tmeStart' => $tme_start_time,
											':tmeEnd' => $tme_end_time,
											':intAttID' => $int_att_id
											
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}

function addAttendance($dte_date_attended,$int_service_id,$int_member_id,$tme_start_time,$tme_end_time)
{
	global $dbh;
	global $false;
	
	try {
			$add_attendance = $dbh->prepare('
									INSERT INTO tbl_attendance(
									fld_date_attended,
									fld_service_id,
									fld_member_id,
									fld_start_time,
									fld_end_time,
									fld_deleted)
									values(	:dteAtt,
											:intSvcID,
											:intMemID,
											:tmeStart,
											:tmeEnd,
											'.$false.'
										   )');
			$add_attendance->execute(array(	':dteAtt' => $dte_date_attended,
											':intSvcID' => $int_service_id,
											':intMemID' => $int_member_id,
											':tmeStart' => $tme_start_time,
											':tmeEnd' => $tme_end_time
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function addAttendanceGroup($int_this_attendance,$this_group)
{
	global $dbh;
	global $false;
	
	try {
			$add_attendance = $dbh->prepare('
									INSERT INTO tbl_attendance_groups(
									fld_attendance_id,
									fld_group_id,
									fld_deleted)
									values(	:intAttID,
											:intGrpID,
											'.$false.'
										   )');
			$add_attendance->execute(array(	':intAttID' => $int_this_attendance,
											':intGrpID' => $this_group
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function getAttendanceGroups($view,$int_attendance_id)
{
	global $dbh;
	
	try {
			$groups = $dbh->prepare('
									SELECT tbl_groups.fld_group_name, tbl_groups.id_group
									FROM tbl_attendance_groups
									JOIN tbl_groups
									ON tbl_attendance_groups.fld_group_id = tbl_groups.id_group
									WHERE tbl_attendance_groups.fld_attendance_id = :intAttID
									'.$view);
			$groups->execute(array(	
									':intAttID' => $int_attendance_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $groups;
}

function getMemberAttendance($id_person)
{
	global $dbh;
	global $false;
	
	try {
			$attendance = $dbh->prepare('
									SELECT tbl_attendance.*,
									tbl_services.fld_service_name,
									TIMEDIFF(tbl_attendance.fld_end_time,tbl_attendance.fld_start_time) AS time_attended
									FROM tbl_attendance
									JOIN tbl_services
									ON tbl_attendance.fld_service_id = tbl_services.id_service
									WHERE tbl_attendance.fld_member_id = :idMem
									AND tbl_attendance.fld_deleted = '.$false.'
									ORDER BY tbl_attendance.fld_date_attended DESC, tbl_attendance.fld_end_time DESC
									');
			$attendance->execute(array(	
										':idMem' => $id_person
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $attendance;
}

function getAttendanceOld($view,$int_service_id,$dte_safe_att_date)
{
	global $dbh;
	
	try {
			$attendance = $dbh->prepare('
									SELECT tbl_attendance.*, tbl_members.id_person, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name, TIMEDIFF(tbl_attendance.fld_end_time,tbl_attendance.fld_start_time) AS tme_attended
									FROM tbl_attendance
									LEFT OUTER JOIN tbl_members
									ON tbl_attendance.fld_member_id = tbl_members.id_person
									WHERE tbl_attendance.fld_service_id = :intSvcID
									AND tbl_attendance.fld_date_attended = :dteAtt
									'.$view.' 
									ORDER BY tbl_members.fld_first_name, tbl_members.fld_last_name, tbl_attendance.fld_start_time
									');
			$attendance->execute(array(	
										':intSvcID' => $int_service_id,
										':dteAtt' => $dte_safe_att_date
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $attendance;
}

function getAttendanceSingular($id_attendance)
{
	global $dbh;
	global $false;
	
	try {
			$attendance = $dbh->prepare('
									SELECT tbl_attendance.*, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name, TIMEDIFF(tbl_attendance.fld_end_time,tbl_attendance.fld_start_time) AS tme_attended
									FROM tbl_attendance
									LEFT OUTER JOIN tbl_members
									ON tbl_attendance.fld_member_id = tbl_members.id_person
									WHERE tbl_attendance.id_attendance = :idAtt
									AND tbl_attendance.fld_deleted = '.$false.'
									');
			$attendance->execute(array(	
										':idAtt' => $id_attendance
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $attendance;
}

function delAttendance($int_del_att_id)
{
	global $dbh;
	
	try {
			$del_att = $dbh->prepare('
									UPDATE tbl_attendance
									SET fld_deleted = 1
									WHERE id_attendance = :intAttID
									');
			$del_att->execute(array(	
										':intAttID' => $int_del_att_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
}

function getGroupStaffFacilitatorsByCSV($csv_staff_facilitators)
{
	global $dbh;
	
	//convert the list into something that pdo can work with
	$arr_staff = explode(',', $csv_staff_facilitators);
	$placeholders = rtrim(str_repeat('?, ', count($arr_staff)), ', ') ;
	
	try {
		
			$staff = $dbh->prepare('SELECT tbl_staff.id_person, tbl_staff.fld_first_name, tbl_staff.fld_middle_name, tbl_staff.fld_last_name
									FROM tbl_staff
									WHERE tbl_staff.id_person IN('.$placeholders.') 
								');
			$staff->execute($arr_staff);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $staff;
}

function getGroupStaffFacilitators($int_group)
{
	global $dbh;
	
	try {
		
			$stf_fac = $dbh->prepare('SELECT tbl_group_facilitators.*, tbl_staff.id_person, tbl_staff.fld_first_name, tbl_staff.fld_middle_name, tbl_staff.fld_last_name
								FROM tbl_group_facilitators
								JOIN tbl_staff
								ON tbl_group_facilitators.fld_staff_id = tbl_staff.id_person
								WHERE tbl_group_facilitators.fld_group_id = :intGrp
								AND tbl_group_facilitators.fld_staff_id IS NOT NULL
								');
			$stf_fac->execute(array(':intGrp' => $int_group
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $stf_fac;
}

function getGroupMemberFacilitatorsByCSV($csv_member_ids)
{
	global $dbh;
	
	//convert the list into something that pdo can work with
	$arr_members = explode(',', $csv_member_ids);
	$placeholders = rtrim(str_repeat('?, ', count($arr_members)), ', ') ;
	
	try {
		
			$members = $dbh->prepare('SELECT tbl_members.id_person, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name
									FROM tbl_members
									WHERE tbl_members.id_person IN('.$placeholders.') 
								');
			$members->execute($arr_members);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}

//Need to add get name functionality
function getGroupMemberFacilitators($int_group)
{
	global $dbh;
	
	try {
		
			$mem_fac = $dbh->prepare('SELECT tbl_group_facilitators.*, tbl_members.id_person, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name
								FROM tbl_group_facilitators
								JOIN tbl_members
								ON tbl_group_facilitators.fld_member_id = tbl_members.id_person
								WHERE tbl_group_facilitators.fld_group_id = :intGrp
								AND tbl_group_facilitators.fld_member_id IS NOT NULL
								');
			$mem_fac->execute(array(':intGrp' => $int_group
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $mem_fac;
}

function getStaffByNameNew($str_text,$view,$staff_not_in = '0')
{
	global $dbh;
	
	$arr_values = array($str_text,$str_text);
	
	$arr_not_in = explode(',', $staff_not_in);
	
	$placeholders = rtrim(str_repeat('?, ', count($arr_not_in)), ', ') ;
	
	foreach( $arr_not_in as $not_in )
	{
		$arr_values[] = $not_in;
	}
	
	
	try {
		
			$members = $dbh->prepare("SELECT id_person, fld_first_name, fld_middle_name, fld_last_name, fld_birth_date
									FROM tbl_staff
									WHERE fld_first_name LIKE concat('%', ?, '%') OR fld_last_name LIKE concat('%', ?, '%')
									".$view.' AND id_person NOT IN('.$placeholders.') 
									ORDER BY fld_first_name, fld_last_name;');
			$members->execute($arr_values);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}

function getStaffByName($strFName,$strLName,$view,$staff_not_in = '0')
{
	global $dbh;
	
	$arr_values = array($strFName,$strLName,$strLName,$strFName);
	
	$arr_not_in = explode(',', $staff_not_in);
	
	$placeholders = rtrim(str_repeat('?, ', count($arr_not_in)), ', ') ;
	
	foreach( $arr_not_in as $not_in )
	{
		$arr_values[] = $not_in;
	}
	
	
	try {
		
			$members = $dbh->prepare("SELECT id_person, fld_first_name, fld_middle_name, fld_last_name, fld_birth_date
									FROM tbl_staff
									WHERE (fld_first_name LIKE concat('%', ?, '%') AND fld_last_name LIKE concat('%', ?, '%') 
									OR fld_first_name LIKE concat('%', ?, '%') AND fld_last_name LIKE concat('%', ?, '%')) 
									".$view.' AND id_person NOT IN('.$placeholders.') 
									ORDER BY fld_last_name, fld_first_name;');
			$members->execute($arr_values);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}

function getMembersNew($str_text,$view,$member_not_in = '0')
{
	global $dbh;
	
	$arr_values = array($str_text,$str_text);
	
	$arr_not_in = explode(',', $member_not_in);
	
	$placeholders = rtrim(str_repeat('?, ', count($arr_not_in)), ', ') ;
	
	foreach( $arr_not_in as $not_in )
	{
		$arr_values[] = $not_in;
	}
	
	
	try {
		
			$members = $dbh->prepare("SELECT id_person, fld_first_name, fld_middle_name, fld_last_name, fld_birth_date
									FROM tbl_members
									WHERE fld_first_name LIKE concat('%', ?, '%') OR fld_last_name LIKE concat('%', ?, '%') 
									".$view.' AND id_person NOT IN('.$placeholders.') 
									ORDER BY fld_first_name, fld_last_name ;');
			$members->execute($arr_values);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}

function getMembers($strFName,$strLName,$view,$member_not_in = '0')
{
	global $dbh;
	
	$arr_values = array($strFName,$strLName,$strLName,$strFName);
	
	$arr_not_in = explode(',', $member_not_in);
	
	$placeholders = rtrim(str_repeat('?, ', count($arr_not_in)), ', ') ;
	
	foreach( $arr_not_in as $not_in )
	{
		$arr_values[] = $not_in;
	}
	
	
	try {
		
			$members = $dbh->prepare("SELECT id_person, fld_first_name, fld_middle_name, fld_last_name, fld_birth_date
									FROM tbl_members
									WHERE (fld_first_name LIKE concat('%', ?, '%') AND fld_last_name LIKE concat('%', ?, '%') 
									OR fld_first_name LIKE concat('%', ?, '%') AND fld_last_name LIKE concat('%', ?, '%')) 
									".$view.' AND id_person NOT IN('.$placeholders.') 
									ORDER BY fld_first_name, fld_last_name ;');
			$members->execute($arr_values);
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}

function getMembersOld($strFName,$strLName,$view,$member_not_in = '0')
{
	global $dbh;
	
	$arr_values = array($strFName,$strLName,$strLName,$strFName);
	
	$arr_not_in = explode(',', $member_not_in);
	
	foreach( $arr_not_in as $not_in )
	{
		$arr_values[] = $not_in;
	}
	
	
	try {
		
			$members = $dbh->prepare("SELECT id_person, fld_first_name, fld_middle_name, fld_last_name, fld_birth_date
									FROM tbl_members
									WHERE (fld_first_name LIKE concat('%', :FName, '%') AND fld_last_name LIKE concat('%', :LName, '%') 
									OR fld_first_name LIKE concat('%', :LName2, '%') AND fld_last_name LIKE concat('%', :FName2, '%')) 
									".$view.' AND id_person NOT IN(:members) 
									ORDER BY fld_last_name, fld_first_name;');
			$members->execute(array(':FName' => $strFName,
									':LName' => $strLName,
									':LName2' => $strLName,
									':FName2' => $strFName,
									':members' => $member_not_in
								 	));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $members;
}


/* Begin get User Login details functions */

function getStaffLogin($intStaffID)
{
	global $dbh;
	
		
	try {
		$qryUser = $dbh->prepare('SELECT tbl_users.id_user, tbl_users.fld_staff_id, tbl_users.fld_username, tbl_users.fld_user_type_id, tbl_users.fld_password, tbl_users.fld_salt
									FROM tbl_users
									WHERE tbl_users.fld_staff_id = :id_staff
									');
		$qryUser->execute(array(':id_staff' => $intStaffID
								 ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $qryUser;
	
}


function getStaffMember($intPerson)
{
	global $dbh;
		
		try {
			$staff = $dbh->prepare('SELECT *
									FROM tbl_staff
									WHERE id_person = :id_person');
			$staff->execute(array(':id_person' => $intPerson));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $staff;
}

function getMember($intPerson)
{
	global $dbh;
		
		try {
			$member = $dbh->prepare('SELECT tbl_members.*
									FROM tbl_members
									WHERE fld_user_id = :intPerson');
			$member->execute(array(':intPerson' => $intPerson));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $member;
}

function getDailyContact($int_daily_contact_id)
{
	global $dbh;
		
		try {
			$daily_contact = $dbh->prepare('
										SELECT tbl_daily_contacts.*, tbl_members.id_person, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name
										FROM tbl_daily_contacts
										LEFT OUTER JOIN tbl_members
										ON tbl_daily_contacts.fld_member_id = tbl_members.id_person
										WHERE id_contact = :intCID
											');
			$daily_contact->execute(array(':intCID' => $int_daily_contact_id));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $daily_contact;
}

function getStaffUserIDByPersonID($staff_id)
{
	global $dbh;
	
	$strQuery = 'SELECT tbl_users.id_user
				FROM tbl_staff
				JOIN tbl_users
				ON tbl_staff.id_person = tbl_users.fld_staff_id
				WHERE tbl_staff.id_person =  :person_id';
	
	try {
		$Value = $dbh->prepare($strQuery);
		$Value->execute(array(':person_id' => $staff_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arrUserID = $Value->fetch();
	
	return $arrUserID['id_user'];
}

function getStaffIDByUserID($user_id)
{
	global $dbh;
	
	$strQuery = 'SELECT fld_staff_id
				FROM tbl_users 
				WHERE tbl_users.id_user =  :user_id';
	
	try {
		$Value = $dbh->prepare($strQuery);
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	$arrUserID = $Value->fetch();
	
	return $arrUserID['fld_staff_id'];
	
	
}

//checks if the current user is a staff member and returns their end date
function chkIsStaff($user_id)
{
	global $dbh;
	
	//no need to check usertypes here due to safety of id_user
	$strQueryIsStaff = 'SELECT fld_end_date
				FROM tbl_staff
				JOIN tbl_users
				ON tbl_staff.id_person = tbl_users.fld_staff_id
				AND tbl_users.id_user = :user_id';
	
	try {
		$Value = $dbh->prepare($strQueryIsStaff);
		$Value->execute(array(':user_id' => $user_id ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $Value;
}

function addStaffMember($strFN,$strMN,$strLN,$dteDOB,$dteSD,$dteED,$str_wk_phone,$int_job_class,$strMedNo,$dteBCExp,$dteDLExp,$dteCPRExp,$dteFAExp,$strAddr,$strSubr,$strPCde,$str_allergies,$str_em_con_name,$str_em_con_rel,$str_em_con_p,$str_em_con_add,$str_em_con_sbr,$str_em_con_pcd,$str_email,$str_ph_one,$str_ph_two,$str_training_details,$str_em_con_two_name,$str_em_con_two_rel,$str_em_con_two_p,$str_em_con_two_add,$str_em_con_two_sbr,$str_em_con_two_pcd)
{
	global $dbh;
	global $false;
	
		try {
			$staff = $dbh->prepare('INSERT INTO tbl_staff(
									fld_first_name,
									fld_middle_name,
									fld_last_name,
									fld_birth_date,
									fld_work_mobile,
									fld_start_date,
									fld_end_date,
									fld_job_class_id,
									fld_medicare_no,
									fld_blue_card_exp_dte,
									fld_drivers_lic_exp_dte,
									fld_cpr_exp_dte,
									fld_frst_aid_exp_dte,
									fld_address,
									fld_suburb,
									fld_postcode,
									fld_allergies,
									fld_em_con_name,
									fld_em_com_rel,
									fld_em_con_address,
									fld_em_con_suburb,
									fld_em_con_postcode,
									fld_em_con_phone,
									fld_email,
									fld_personal_phone_1,
									fld_personal_phone_2,
									fld_training_details,
									fld_em_con_two_name,
									fld_em_con_two_rel,
									fld_em_con_two_address,
									fld_em_con_two_suburb,
									fld_em_con_two_postcode,
									fld_em_con_two_phone,
									fld_deleted)
									VALUES(	:fname,
											:mname,
											:lname,
											:bdate,
											:strWkPh,
											:sdate,
											:edate,
											:intJbClass,
											:strMedNo,
											:dteBCExp,
											:dteDLExp,
											:dteCPRExp,
											:dteFAExp,
											:strAddr,
											:strSubr,
											:strPCode,
											:strAll,
											:strEmCnNm,
											:strEmCnRl,
											:strEmCnAdd,
											:strEmCnSbr,
											:strEmCnPC,
											:strEmCnPh,
											:str_email,
											:str_pers_ph_one,
											:str_pers_ph_two,
											:str_training,
											:str_em_con_two_name,
											:str_em_con_two_rel,
											:str_em_con_two_address,
											:str_em_con_two_suburb,
											:str_em_con_two_postcode,
											:str_em_con_two_phone,
											'.$false.'
										   )');
			$staff->execute(array(	':fname' => $strFN,
									':mname' => $strMN,
									':lname' => $strLN,
									':bdate' => $dteDOB,
									':strWkPh' => $str_wk_phone,
									':sdate' => $dteSD,
									':edate' => ($dteED == 'null') ? null : $dteED,
									':intJbClass' => $int_job_class,
									':strMedNo' => $strMedNo,
									':dteBCExp' => ($dteBCExp == 'null') ? null : $dteBCExp,
									':dteDLExp' => ($dteDLExp == 'null') ? null :$dteDLExp,
									':dteCPRExp' => ($dteCPRExp == 'null') ? null :$dteCPRExp,
									':dteFAExp' => ($dteFAExp == 'null') ? null :$dteFAExp,
									':strAddr' => $strAddr,
									':strSubr' => $strSubr,
									':strPCode' => $strPCde,
									':strAll' => $str_allergies,
									':strEmCnNm' => $str_em_con_name,
									':strEmCnRl' => $str_em_con_rel,
									':strEmCnAdd' => $str_em_con_add,
									':strEmCnSbr' => $str_em_con_sbr,
									':strEmCnPC' => $str_em_con_pcd,
									':strEmCnPh' => $str_em_con_p,
									':str_email' => $str_email,
									':str_pers_ph_one' => $str_ph_one,
									':str_pers_ph_two' => $str_ph_two,
									':str_training' => $str_training_details,
									':str_em_con_two_name' => $str_em_con_two_name,
									':str_em_con_two_rel' => $str_em_con_two_rel,
									':str_em_con_two_address' => $str_em_con_two_add,
									':str_em_con_two_suburb' => $str_em_con_two_sbr,
									':str_em_con_two_postcode' => $str_em_con_two_pcd,
									':str_em_con_two_phone' => $str_em_con_two_p
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
}

function updStaffMember($intPerson,$strFN,$strMN,$strLN,$dteDOB,$dteSD,$dteED,$str_wk_phone,$int_job_class,$strMedNo,$dteBCExp,$dteDLExp,$dteCPRExp,$dteFAExp,$strAddr,$strSubr,$strPCde,$str_allergies,$str_em_con_name,$str_em_con_rel,$str_em_con_p,$str_em_con_add,$str_em_con_sbr,$str_em_con_pcd,$str_email,$str_ph_one,$str_ph_two,$str_training_details,$str_em_con_two_name,$str_em_con_two_rel,$str_em_con_two_p,$str_em_con_two_add,$str_em_con_two_sbr,$str_em_con_two_pcd)
{
	global $dbh;
		
		try {
			$staff = $dbh->prepare('UPDATE tbl_staff
									SET fld_first_name = :fname,
									fld_middle_name = :mname,
									fld_last_name = :lname,
									fld_birth_date = :bdate,
									fld_work_mobile = :strWkPh,
									fld_start_date = :sdate,
									fld_end_date = :edate,
									fld_job_class_id = :intJbClass,
									fld_medicare_no = :strMedNo,
									fld_blue_card_exp_dte = :dteBCExp,
									fld_drivers_lic_exp_dte = :dteDLExp,
									fld_cpr_exp_dte = :dteCPRExp,
									fld_frst_aid_exp_dte = :dteFAExp,
									fld_address = :strAddr,
									fld_suburb = :strSubr,
									fld_postcode = :strPCode,
									fld_allergies = :strAll,
									fld_em_con_name = :strEmCnNm,
									fld_em_com_rel = :strEmCnRl,
									fld_em_con_address = :strEmCnAdd,
									fld_em_con_suburb = :strEmCnSbr,
									fld_em_con_postcode = :strEmCnPC,
									fld_em_con_phone = :strEmCnPh,
									fld_email = :str_email, 
									fld_personal_phone_1 = :str_pers_ph_one,
									fld_personal_phone_2 = :str_pers_ph_two,
									fld_training_details = :str_training,
									fld_em_con_two_name = :str_em_con_two_name,
									fld_em_con_two_rel = :str_em_con_two_rel,
									fld_em_con_two_address = :str_em_con_two_address,
									fld_em_con_two_suburb = :str_em_con_two_suburb,
									fld_em_con_two_postcode = :str_em_con_two_postcode,
									fld_em_con_two_phone = :str_em_con_two_phone
									WHERE id_person = :id_person');
			$staff->execute(array(	':fname' => $strFN,
									':mname' => $strMN,
									':lname' => $strLN,
									':bdate' => $dteDOB,
									':strWkPh' => $str_wk_phone,
									':sdate' => $dteSD,
									':edate' => ($dteED == 'null') ? null : $dteED,
									':intJbClass' => $int_job_class,
									':strMedNo' => $strMedNo,
									':dteBCExp' => ($dteBCExp == 'null') ? null : $dteBCExp,
									':dteDLExp' => ($dteDLExp == 'null') ? null :$dteDLExp,
									':dteCPRExp' => ($dteCPRExp == 'null') ? null :$dteCPRExp,
									':dteFAExp' => ($dteFAExp == 'null') ? null :$dteFAExp,
									':strAddr' => $strAddr,
									':strSubr' => $strSubr,
									':strPCode' => $strPCde,
									':strAll' => $str_allergies,
									':strEmCnNm' => $str_em_con_name,
									':strEmCnRl' => $str_em_con_rel,
									':strEmCnAdd' => $str_em_con_add,
									':strEmCnSbr' => $str_em_con_sbr,
									':strEmCnPC' => $str_em_con_pcd,
									':strEmCnPh' => $str_em_con_p,
									':str_email' => $str_email,
									':str_pers_ph_one' => $str_ph_one,
									':str_pers_ph_two' => $str_ph_two,
									':str_training' => $str_training_details,
									':str_em_con_two_name' => $str_em_con_two_name,
									':str_em_con_two_rel' => $str_em_con_two_rel,
									':str_em_con_two_address' => $str_em_con_two_add,
									':str_em_con_two_suburb' => $str_em_con_two_sbr,
									':str_em_con_two_postcode' => $str_em_con_two_pcd,
									':str_em_con_two_phone' => $str_em_con_two_p,
									':id_person' => $intPerson
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
}



//adds a staff user account for log in
function addStaffUserAccount($intStaffUserID,$intUserType,$strUserName,$strPassword,$strSalt)
{
	global $dbh;
	
		try {
			$add_staff = $dbh->prepare('insert into tbl_users(
									fld_staff_id,
									fld_user_type_id,
									fld_username,
									fld_password,
									fld_salt)
									values(	:uSId,
											:uTId,
											:uName,
											:uPWord,
											:uSalt
										   )');
			$add_staff->execute(array(	
									':uSId' => $intStaffUserID,
									':uTId' => $intUserType,
									':uName' => $strUserName,
									':uPWord' => $strPassword,
									':uSalt' => $strSalt
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}

//update staff user account for log in
function updStaffUserAccount($intUserId,$strUserName,$strPassword,$intUserType)
{
	global $dbh;
	
		try {
			$upd_staff = $dbh->prepare('update tbl_users set 
									fld_user_type_id = :uTId,
									fld_username = :uName,
									fld_password = :uPWord 
									where id_user = :uId
										   ');
			$upd_staff->execute(array(
									':uTId' => $intUserType,
									':uName' => $strUserName,
									':uPWord' => $strPassword,
									':uId' => $intUserId
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}

function updStaffUserType($int_user_id,$str_u_name,$int_user_type)
{
	global $dbh;
	
		try {
			$upd_staff = $dbh->prepare('update tbl_users set 
									fld_user_type_id = :uTId,
									fld_username = :uName
									where id_user = :uId
										   ');
			$upd_staff->execute(array(
									':uTId' => $int_user_type,
									':uName' => $str_u_name,
									':uId' => $int_user_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}


function addMember($strFN,$strMN,$strLN,$dteDOB,$intGender,$dteSD,$dteED,$dteCDLU,$strAddr,$strSbr,$strPC,$strHP,$strMb,$strEmail,$bl_ab,$bl_tsi,$bl_ni,$bl_bos,$str_cob,$int_ref_src,$str_ref_src_info,$str_em_con_1_name,$str_em_con_1_rel,$str_em_con_1_hp,$str_em_con_1_mb,$str_em_con_1_add,$str_em_con_1_sbr,$str_em_con_1_pcd,$str_em_con_2_name,$str_em_con_2_rel,$str_em_con_2_hp,$str_em_con_2_mb,$str_em_con_2_add,$str_em_con_2_sbr,$str_em_con_2_pcd,$str_med_inf,$str_med_act_req,$str_add_inf,$str_why_br_red,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$bl_aod,$int_diag_1,$int_diag_2,$int_diag_3,$str_diag,$bl_phy_hlth_cond,$str_phy_hlth_cond_info,$bl_in_cust,$bl_in_em_men_health,$str_no_tms_in_em_men_health,$bl_in_hosp,$str_no_tms_in_hosp) 
{
	global $dbh;
	global $false;
	
		try {
			$staff = $dbh->prepare('INSERT INTO tbl_members(
									fld_first_name,
									fld_middle_name,
									fld_last_name,
									fld_birth_date,
									fld_gender_id,
									fld_start_date,
									fld_end_date,
									fld_contact_details_last_updated,
									fld_address,
									fld_suburb,
									fld_postcode,
									fld_home_phone,
									fld_mobile_phone,
									fld_email,
									fld_aboriginal,
									fld_torres_strait_islander,
									fld_ethnicity_neither,
									fld_born_overseas,
									fld_country_of_birth,
									fld_referral_source_id,
									fld_referral_source_info,
									fld_em_con_one_name,
									fld_em_con_one_relationship,
									fld_em_con_one_home_phone,
									fld_em_con_one_mob_phone,
									fld_em_con_one_address,
									fld_em_con_one_suburb,
									fld_em_con_one_postcode,
									fld_em_con_two_name,
									fld_em_con_two_relationship,
									fld_em_con_two_home_phone,
									fld_em_con_two_mob_phone,
									fld_em_con_two_address,
									fld_em_con_two_suburb,
									fld_em_con_two_postcode,
									fld_medical_details,
									fld_medical_details_act_req,
									fld_other_important_info,
									fld_reasons_for_attending_br,
									fld_outreach_str,
									fld_outreach_int,
									fld_deleted,
									fld_aod,
									fld_diagnosis_1,
									fld_diagnosis_2,
									fld_diagnosis_3,
									fld_diagnosis_other,
									fld_physical_health_condition,
									fld_text_physical_health_condition,
									fld_in_custody_last_two_years,
									fld_in_eme_men_hea_last_two_years,
									fld_no_tms_in_em_men_hea_last_two_years,
									fld_in_hos_last_year,
									fld_no_tms_in_hos_last_year)
									VALUES(	:strFN,
											:strMN,
											:strLN,
											:dteDOB,
											:intGender,
											:dteSD,
											:dteED,
											:dteCDLU,
											:strAddr,
											:strSbr,
											:strPC,
											:strHP,
											:strMb,
											:strEmail,
											:blAB,
											:blTSI,
											:blNI,
											:blBOS,
											:strCOB,
											:intRSI,
											:strRSINF,
											:strEMCON1Name,
											:strEMCON1Rel,
											:strEMCON1HP,
											:strEMCON1MP,
											:strEMCON1Add,
											:strEMCON1Sub,
											:strEMCON1PC,
											:strEMCON2Name,
											:strEMCON2Rel,
											:strEMCON2HP,
											:strEMCON2MP,
											:strEMCON2Add,
											:strEMCON2Sub,
											:strEMCON2PC,
											:strMedInf,
											:strMedActRq,
											:strAddInf,
											:strWhyBr,
											:strOutRch,
											:intOutRch,
											'.$false.',
											:bl_aod,
											:int_diag_1,
											:int_diag_2,
											:int_diag_3,
											:str_diag,
											:bl_phy_hlth_cond,
											:str_phy_hlth_cond_info,
											:bl_in_cust,
											:bl_in_em_men_health,
											:str_no_tms_in_em_men_health,
											:bl_in_hosp,
											:str_no_tms_in_hosp
										   )');
			$staff->execute(array(	':strFN' => $strFN,
									':strMN' => $strMN,
									':strLN' => $strLN,
									':dteDOB' => $dteDOB,
									':intGender' => $intGender,
									':dteSD' => $dteSD,
									':dteED' => ($dteED == 'null') ? null : $dteED,
									':dteCDLU' => ($dteCDLU == 'null') ? null : $dteCDLU,
									':strAddr' => $strAddr,
									':strSbr' => $strSbr,
									':strPC' => $strPC,
									':strHP' => $strHP,
									':strMb' => $strMb,
									':strEmail' => $strEmail,
									':blAB' => $bl_ab,
									':blTSI' => $bl_tsi,
									':blNI' => $bl_ni,
									':blBOS' => $bl_bos,
									':strCOB' => $str_cob,
									':intRSI' => $int_ref_src,
									':strRSINF' => $str_ref_src_info,
									':strEMCON1Name' => $str_em_con_1_name,
									':strEMCON1Rel' => $str_em_con_1_rel,
									':strEMCON1HP' => $str_em_con_1_hp,
									':strEMCON1MP' => $str_em_con_1_mb,
									':strEMCON1Add' => $str_em_con_1_add,
									':strEMCON1Sub' => $str_em_con_1_sbr,
									':strEMCON1PC' => $str_em_con_1_pcd,
									':strEMCON2Name' => $str_em_con_2_name,
									':strEMCON2Rel' => $str_em_con_2_rel,
									':strEMCON2HP' => $str_em_con_2_mb,
									':strEMCON2MP' => $str_em_con_2_sbr,
									':strEMCON2Add' => $str_em_con_2_add,
									':strEMCON2Sub' => $str_em_con_2_sbr,
									':strEMCON2PC' => $str_em_con_2_pcd,
									':strMedInf' => $str_med_inf,
									':strMedActRq' => $str_med_act_req,
									':strAddInf' => $str_add_inf,
									':strWhyBr' => $str_why_br_red,
									':strOutRch' => $mxd_recurrencestr_period_div,
									':intOutRch' => $mxd_recurrenceint_period_div,
									':bl_aod' => $bl_aod,
									':int_diag_1' => $int_diag_1,
									':int_diag_2' => $int_diag_2,
									':int_diag_3' => $int_diag_3,
									':str_diag' => $str_diag,
									':bl_phy_hlth_cond' => $bl_phy_hlth_cond,
									':str_phy_hlth_cond_info' => $str_phy_hlth_cond_info,
									':bl_in_cust' => $bl_in_cust,
									':bl_in_em_men_health' => $bl_in_em_men_health,
									':str_no_tms_in_em_men_health' => $str_no_tms_in_em_men_health,
									':bl_in_hosp' => $bl_in_hosp,
									':str_no_tms_in_hosp' => $str_no_tms_in_hosp
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
	
}


function updMember($intPerson,$strFN,$strMN,$strLN,$dteDOB,$intGender,$dteSD,$dteED,$dteCDLU,$strAddr,$strSbr,$strPC,$strHP,$strMb,$strEmail,$bl_ab,$bl_tsi,$bl_ni,$bl_bos,$str_cob,$int_ref_src,$str_ref_src_info,$str_em_con_1_name,$str_em_con_1_rel,$str_em_con_1_hp,$str_em_con_1_mb,$str_em_con_1_add,$str_em_con_1_sbr,$str_em_con_1_pcd,$str_em_con_2_name,$str_em_con_2_rel,$str_em_con_2_hp,$str_em_con_2_mb,$str_em_con_2_add,$str_em_con_2_sbr,$str_em_con_2_pcd,$str_med_inf,$str_med_act_req,$str_add_inf,$str_why_br_red,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$bl_aod,$int_diag_1,$int_diag_2,$int_diag_3,$str_diag,$bl_phy_hlth_cond,$str_phy_hlth_cond_info,$bl_in_cust,$bl_in_em_men_health,$str_no_tms_in_em_men_health,$bl_in_hosp,$str_no_tms_in_hosp) 
{
	global $dbh;
		
		try {
			$staff = $dbh->prepare('update tbl_members
									SET fld_first_name = :strFN,
									fld_middle_name = :strMN,
									fld_last_name = :strLN,
									fld_birth_date = :dteDOB,
									fld_gender_id = :intGender,
									fld_start_date = :dteSD,
									fld_end_date = :dteED,
									fld_contact_details_last_updated = :dteCDLU,
									fld_address = :strAddr,
									fld_suburb = :strSbr,
									fld_postcode = :strPC,
									fld_home_phone = :strHP,
									fld_mobile_phone = :strMb,
									fld_email = :strEmail,
									fld_aboriginal = :blAB,
									fld_torres_strait_islander = :blTSI,
									fld_ethnicity_neither = :blNi,
									fld_born_overseas = :blBOS,
									fld_country_of_birth = :strCOB,
									fld_referral_source_id = :intRSI,
									fld_referral_source_info = :strRSINF,
									fld_em_con_one_name = :strEMCON1Name,
									fld_em_con_one_relationship = :strEMCON1Rel,
									fld_em_con_one_home_phone = :strEMCON1HP,
									fld_em_con_one_mob_phone = :strEMCON1MP,
									fld_em_con_one_address = :strEMCON1Add,
									fld_em_con_one_suburb = :strEMCON1Sub,
									fld_em_con_one_postcode = :strEMCON1PC,
									fld_em_con_two_name = :strEMCON2Name,
									fld_em_con_two_relationship = :strEMCON2Rel,
									fld_em_con_two_home_phone = :strEMCON2HP,
									fld_em_con_two_mob_phone = :strEMCON2MP,
									fld_em_con_two_address = :strEMCON2Add,
									fld_em_con_two_suburb = :strEMCON2Sub,
									fld_em_con_two_postcode = :strEMCON2PC,
									fld_medical_details = :strMedInf,
									fld_medical_details_act_req = :strMedActRq,
									fld_other_important_info = :strAddInf,
									fld_reasons_for_attending_br = :strWhyBr,
									fld_outreach_str = :strOutRch,
									fld_outreach_int = :intOutRch,
									fld_aod = :bl_aod,
									fld_diagnosis_1 = :int_diag_1,
									fld_diagnosis_2 = :int_diag_2,
									fld_diagnosis_3 = :int_diag_3,
									fld_diagnosis_other = :str_diag,
									fld_physical_health_condition = :bl_phy_hlth_cond,
									fld_text_physical_health_condition = :str_phy_hlth_cond_info,
									fld_in_custody_last_two_years = :bl_in_cust,
									fld_in_eme_men_hea_last_two_years = :bl_in_em_men_health,
									fld_no_tms_in_em_men_hea_last_two_years = :str_no_tms_in_em_men_health,
									fld_in_hos_last_year = :bl_in_hosp,
									fld_no_tms_in_hos_last_year = :str_no_tms_in_hosp
									WHERE id_person = :id_person');
			$staff->execute(array(	':strFN' => $strFN,
									':strMN' => $strMN,
									':strLN' => $strLN,
									':dteDOB' => $dteDOB,
									':intGender' => $intGender,
									':dteSD' => $dteSD,
									':dteED' => ($dteED == 'null') ? null : $dteED,
									':dteCDLU' => ($dteCDLU == 'null') ? null : $dteCDLU,
									':strAddr' => $strAddr,
									':strSbr' => $strSbr,
									':strPC' => $strPC,
									':strHP' => $strHP,
									':strMb' => $strMb,
									':strEmail' => $strEmail,
									':blAB' => $bl_ab, 
									':blTSI' => $bl_tsi, 
									':blNi' => $bl_ni, 
									':blBOS' => $bl_bos, 
									':strCOB' => $str_cob,
									':intRSI' => $int_ref_src,
									':strRSINF' => $str_ref_src_info,
									':strEMCON1Name' => $str_em_con_1_name,
									':strEMCON1Rel' => $str_em_con_1_rel,
									':strEMCON1HP' => $str_em_con_1_hp,
									':strEMCON1MP' => $str_em_con_1_mb,
									':strEMCON1Add' => $str_em_con_1_add,
									':strEMCON1Sub' => $str_em_con_1_sbr,
									':strEMCON1PC' => $str_em_con_1_pcd,
									':strEMCON2Name' => $str_em_con_2_name,
									':strEMCON2Rel' => $str_em_con_2_rel,
									':strEMCON2HP' => $str_em_con_2_mb,
									':strEMCON2MP' => $str_em_con_2_sbr,
									':strEMCON2Add' => $str_em_con_2_add,
									':strEMCON2Sub' => $str_em_con_2_sbr,
									':strEMCON2PC' => $str_em_con_2_pcd,
									':strMedInf' => $str_med_inf,
									':strMedActRq' => $str_med_act_req,
									':strAddInf' => $str_add_inf,
									':strWhyBr' => $str_why_br_red,
									':strOutRch' => $mxd_recurrencestr_period_div,
									':intOutRch' => $mxd_recurrenceint_period_div,
									':bl_aod' => $bl_aod,
									':int_diag_1' => $int_diag_1,
									':int_diag_2' => $int_diag_2,
									':int_diag_3' => $int_diag_3,
									':str_diag' => $str_diag,
									':bl_phy_hlth_cond' => $bl_phy_hlth_cond,
									':str_phy_hlth_cond_info' => $str_phy_hlth_cond_info,
									':bl_in_cust' => $bl_in_cust,
									':bl_in_em_men_health' => $bl_in_em_men_health,
									':str_no_tms_in_em_men_health' => $str_no_tms_in_em_men_health,
									':bl_in_hosp' => $bl_in_hosp,
									':str_no_tms_in_hosp' => $str_no_tms_in_hosp,
									':id_person' => $intPerson
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
}
//get list of user types
function getUserTypes($strTypes = '') //defaults to empty string to view all user types
{
	global $dbh;
	
	if( $strTypes == '')
	{
		$strIn = '';
	} else {
		$strIn = 'where fld_user_type in('.$strTypes.')';
	}
		
		try {
			$userTypes = $dbh->query('select id_user_type, fld_user_type
									from tbl_user_types
									'.$strIn);
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
	return $userTypes;
}

function getDailyContacts($view,$int_staff_id)
{
	global $dbh;
	
	try {
			$get_dc = $dbh->prepare('
									SELECT tbl_daily_contacts.*, tbl_daily_contact_codes.fld_code, tbl_members.fld_first_name, tbl_members.fld_middle_name, tbl_members.fld_last_name, TIMEDIFF(tbl_daily_contacts.fld_end_time,tbl_daily_contacts.fld_start_time) AS tme_supported
									FROM tbl_daily_contacts
									LEFT OUTER JOIN tbl_daily_contact_codes
									ON tbl_daily_contacts.fld_code_id = tbl_daily_contact_codes.id_code
									LEFT OUTER JOIN tbl_members
									ON tbl_daily_contacts.fld_member_id = tbl_members.id_person
									WHERE tbl_daily_contacts.fld_staff_id = :intSID 
									'.$view.'
									ORDER BY tbl_daily_contacts.fld_dated DESC
									');
			$get_dc->execute(array(
									':intSID' => $int_staff_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $get_dc;
}

function updDailyContact($int_dc_id,$int_member_id,$dte_dated,$inv_time_spent,$int_code,$str_reflections)
{
	global $dbh;
	
	try {
			$upd_dc = $dbh->prepare('
									UPDATE tbl_daily_contacts
									SET
									fld_member_id = :intMID, 
									fld_dated = :dteDate,
									fld_time_spent = :invTS,
									fld_code_id = :intCdID,
									fld_reflections = :strRef
									WHERE id_contact = :intDCID
									');
			$upd_dc->execute(array(
									':intMID' => $int_member_id,
									':dteDate' => $dte_dated,
									':invTS' => $inv_time_spent,
									':intCdID' => $int_code,
									':strRef' => $str_reflections,
									':intDCID' => $int_dc_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}

function addDailyContact($int_staff_id,$int_member_id,$dte_dated,$inv_time_spent,$int_code,$str_reflections)
{
	global $dbh;
	global $false;
	
		try {
			$add_dc = $dbh->prepare('
									INSERT INTO tbl_daily_contacts
									(fld_staff_id,
									fld_member_id,
									fld_dated,
									fld_time_spent,
									fld_code_id,
									fld_reflections,
									fld_deleted)
									VALUES
									(
									:intSID,
									:intMID,
									:dteDate,
									:invTS,
									:intCdID,
									:strRef,
									'.$false.'
									)
										   ');
			$add_dc->execute(array(
									':intSID' => $int_staff_id,
									':intMID' => $int_member_id,
									':dteDate' => $dte_dated,
									':invTS' => $inv_time_spent,
									':intCdID' => $int_code,
									':strRef' => $str_reflections
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $dbh->lastInsertId();
}

function getStfJobClass()
{
	global $dbh;
	
	try {
		$job_class = $dbh->query('select *
								from tbl_staff_job_class
								');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $job_class;
}

function getReferralSources()
{
	global $dbh;
	
	try {
		$ref_src = $dbh->query('select *
								from tbl_referral_sources
								');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $ref_src;
}

function getDiagnosiss()
{
	global $dbh;
	
	try {
		$ref_src = $dbh->query('SELECT *
								FROM tbl_diagnosis
								');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $ref_src;
}

function getServiceName($int_service_id)
{
	global $dbh;
	
	try {
			$service = $dbh->prepare('	SELECT *
										FROM tbl_services
										WHERE id_service = :intSvcID
										');
			$service->execute(array(':intSvcID' => $int_service_id ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	$arr_service = $service->fetch();
	
	return $arr_service['fld_service_name'];
}

function getServices()
{
	global $dbh;
	
	try {
		$services = $dbh->query('select *
								from tbl_services
								');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $services;
}

function getPeriods()
{
	global $dbh;
	
	try {
		$periods = $dbh->query('select *
								from tbl_periods
								order by fld_order');
			
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $periods;
}

function chkValService($int_id)
{
	global $dbh;
	
	try {
			$Service = $dbh->prepare('SELECT *
										FROM tbl_services
										WHERE id_service = :intID
										');
			$Service->execute(array(':intID' => $int_id ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Service;
}

function chkValDCCode($int_id)
{
	global $dbh;
	
	try {
			$obj_code = $dbh->prepare('
										SELECT *
										FROM tbl_daily_contact_codes
										WHERE id_code = :intID
										');
			$obj_code->execute(array(':intID' => $int_id ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $obj_code;
}

function chkValStaffJobClass($intID)
{
	global $dbh;
	
	try {
			$JobClass = $dbh->prepare('SELECT *
										FROM tbl_staff_roles
										WHERE id_staff_role = :intID
										');
			$JobClass->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $JobClass;
}

function chkValStaffJobClassVolunteer($intID)
{
	global $dbh;
	
	try {
			$JobClass = $dbh->prepare("SELECT *
										FROM tbl_staff_roles
										WHERE id_staff_role = :intID
										AND fld_staff_vol = 'volunteer'
										");
			$JobClass->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $JobClass;
}

function chkValAllUserType($UserTypeID)
{
	global $dbh;
	
	try {
			$UserType = $dbh->prepare("SELECT *
										FROM tbl_user_types
										WHERE id_user_type = :UserTypeID
										");
			$UserType->execute(array(':UserTypeID' => $UserTypeID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserType;
}

function chkValStaffUserType($UserTypeID)
{
	global $dbh;
	
	try {
			$UserType = $dbh->prepare("SELECT *
										FROM tbl_user_types
										WHERE id_user_type = :UserTypeID
										AND fld_type_category = 'staff'
										");
			$UserType->execute(array(':UserTypeID' => $UserTypeID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserType;
}

function chkValVolunteerUserType($UserTypeID)
{
	global $dbh;
	
	try {
			$UserType = $dbh->prepare("SELECT *
										FROM tbl_user_types
										WHERE id_user_type = :UserTypeID
										AND fld_user_type = 'Group User'
										");
			$UserType->execute(array(':UserTypeID' => $UserTypeID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserType;
}

function chkValReason($ReasonID)
{
	global $dbh;
	
	try {
			$Reason = $dbh->prepare("SELECT *
										FROM tbl_no_meeting_reasons
										WHERE id_reason = :ReasonID
										");
			$Reason->execute(array(':ReasonID' => $ReasonID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Reason;
}

function chkValSecurityLevel($SecurityLevelID)
{
	global $dbh;
	
	try {
			$SecurityLevel = $dbh->prepare("SELECT fld_security_level
											FROM tbl_user_types
											WHERE fld_security_level = :SecurityLevelID
										");
			$SecurityLevel->execute(array(':SecurityLevelID' => $SecurityLevelID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $SecurityLevel;
}

function chkValImportance($ImportanceID)
{
	global $dbh;
	
	try {
			$Importance = $dbh->prepare("SELECT *
										FROM tbl_importance_values
										WHERE id_importance = :ImportanceID
										");
			$Importance->execute(array(':ImportanceID' => $ImportanceID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Importance;
}


function chkValGender($GenderID)
{
	global $dbh;
	
	try {
			$Gender = $dbh->prepare("SELECT *
										FROM tbl_genders
										WHERE id_gender = :GenderID
										");
			$Gender->execute(array(':GenderID' => $GenderID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Gender;
}

function chkValBranch($intID)
{
	global $dbh;
	
	try {
			$Branch = $dbh->prepare('SELECT *
										FROM tbl_branches
										WHERE id_branch = :intID
										');
			$Branch->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Branch;
}

function chkValRegion($intID)
{
	global $dbh;
	
	try {
			$Region = $dbh->prepare('SELECT *
										FROM tbl_regions
										WHERE id_region = :intID
										');
			$Region->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Region;
}

function chkValGroupRole($intID)
{
	global $dbh;
	
	try {
			$GroupRole = $dbh->prepare('SELECT *
										FROM tbl_group_roles
										WHERE id_group_role = :intID
										');
			$GroupRole->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $GroupRole;
}

function chkValVolStaffUser($intID)
{
	global $dbh;
	
	try {
			$VolStaffUser = $dbh->prepare("SELECT *
										FROM tbl_staff
										WHERE EXISTS(	
													SELECT *
													FROM tbl_user_activity_dates
													JOIN tbl_staff_roles
													ON tbl_user_activity_dates.fld_staff_type_id = tbl_staff_roles.id_staff_role
													WHERE tbl_user_activity_dates.fld_user_id = tbl_staff.fld_user_id
													AND tbl_staff_roles.fld_staff_vol = 'volunteer'
													)
										AND tbl_staff.fld_user_id = :intID 
										");
			$VolStaffUser->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $VolStaffUser;
}

function chkValMemberUser($intID)
{
	global $dbh;
	
	try {
			$MemberUser = $dbh->prepare("SELECT *
										FROM tbl_members
										WHERE fld_user_id = :intID 
										");
			$MemberUser->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $MemberUser;
}

function chkValStaffUser($intID)
{
	global $dbh;
	
	try {
			$StaffUser = $dbh->prepare("SELECT *
										FROM tbl_staff
										WHERE fld_user_id = :intID 
										");
			$StaffUser->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $StaffUser;
}

function chkValVenue($intID)
{
	global $dbh;
	
	try {
			$Venue = $dbh->prepare('SELECT *
										FROM tbl_venues
										WHERE id_venue = :intID
										');
			$Venue->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Venue;
}

function chkValGroupType($intID)
{
	global $dbh;
	
	try {
			$GroupType = $dbh->prepare('SELECT *
										FROM tbl_group_types
										WHERE id_group_type = :intID
										');
			$GroupType->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $GroupType;
}

function chkValState($intID)
{
	global $dbh;
	
	try {
			$State = $dbh->prepare('SELECT *
										FROM tbl_states
										WHERE id_state = :intID
										');
			$State->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $State;
}

function chkValStfRemType($strID)
{
	global $dbh;
	
	try {
			$StfRemType = $dbh->prepare('SELECT *
										FROM tbl_staff_reminder_options
										WHERE id_staff_reminder = :strID
										');
			$StfRemType->execute(array(':strID' => $strID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $StfRemType;
}

function chkValStatus($intID)
{
	global $dbh;
	
	try {
			$Status = $dbh->prepare('SELECT *
										FROM tbl_room_bookings_status
										WHERE id_room_booking_status = :intID
										');
			$Status->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Status;
}

function chkValReferralSource($intID)
{
	global $dbh;
	
	try {
			$obj_ref_src = $dbh->prepare('SELECT *
										FROM tbl_referral_sources
										WHERE id_referral_source = :intID
										');
			$obj_ref_src->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $obj_ref_src;
}

function chkValDiagnosis($intID)
{
	global $dbh;
	
	try {
			$obj_diag = $dbh->prepare('SELECT *
										FROM tbl_diagnosis
										WHERE id_diagnosis = :intID
										');
			$obj_diag->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $obj_diag;
}

//check that a submitted user type is allowed for a given control
function chkValUserType($intID,$strTypes)
{
	global $dbh;
	
	try {
			$UserType = $dbh->prepare('SELECT fld_user_type 
										FROM tbl_user_types 
										WHERE id_user_type = :intID
										AND fld_user_type in('.$strTypes.')
										');
			$UserType->execute(array(':intID' => $intID ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $UserType;
}

function chkValPeriod($str_recurrence_id,$int_recurrence)
{
	global $dbh;
	
	try {
			$Period = $dbh->prepare('SELECT *
										FROM tbl_periods 
										WHERE id_period = :strID
										AND :intRe > 0 
										AND fld_limit >= :intRe2
										');
			$Period->execute(array(':strID' => $str_recurrence_id,
									':intRe' => $int_recurrence, 
									':intRe2' => $int_recurrence ));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	return $Period;
}



//collect salt based upon UserID
function getSaltUID($intUserID)
{
	global $dbh;
	try {
		$Salt = $dbh->prepare('SELECT fld_salt FROM tbl_users WHERE id_user = :idUser');
		$Salt->execute(array(':idUser' => $intUserID ));
			
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
		
	return $Salt;
}

//check salt
function chkSalt($strOldHash,$arrRowSalt)
{
	global $dbh;
	
	try {
		
		$CheckSalt = $dbh->prepare('SELECT id_member FROM tbl_members WHERE fld_password = :PWord AND fld_user_name = :UName');
		$CheckSalt->execute(array(':PWord' => $strOldHash,
						 	  ':UName' => $arrRowSalt ));
	} catch(PDOException $exp) {
		echo $exp->getMessage();
	}
	
	return $CheckSalt;
}

//update password
function updPassword($strHashed)
{
	global $dbh;
	
	try {
			$qryUpdtPWord = $dbh->prepare('UPDATE tbl_members SET fld_password = :PWord WHERE id_member = :idMember');
			$qryUpdtPWord->execute(array(':PWord' => $strHashed, 
									 ':idMember' => $_SESSION['idMember']
									 ));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
}


function addGroup($str_group_name,$int_service_id,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$str_safe_sd,$str_safe_ed,$tme_start_time,$tme_end_time,$str_group_description)
{
	global $dbh;
	global $false;
	
		try {
			$group = $dbh->prepare('insert into tbl_groups(
									fld_group_name,
									fld_service_id,
									fld_recurrency_string,
									fld_recurrency_int,
									fld_start_date,
									fld_end_date,
									fld_start_time,
									fld_end_time,
									fld_group_description,
									fld_deleted)
									values(	:strGN,
											:intSI,
											:strRS,
											:intRI,
											:dteSD,
											:dteED,
											:tmeST,
											:tmeET,
											:strDSC,
											'.$false.'
										   )');
			$group->execute(array(	':strGN' => $str_group_name,
									':intSI' => $int_service_id,
									':strRS' => $mxd_recurrencestr_period_div,
									':intRI' => $mxd_recurrenceint_period_div,
									':dteSD' => $str_safe_sd,
									':dteED' =>($str_safe_ed == 'null') ? null : $str_safe_ed,
									':tmeST' => $tme_start_time,
									':tmeET' => $tme_end_time,
									':strDSC' => $str_group_description
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function updGroup($int_group_id,$str_group_name,$int_service_id,$mxd_recurrencestr_period_div,$mxd_recurrenceint_period_div,$str_safe_sd,$str_safe_ed,$tme_start_time,$tme_end_time,$str_group_description)
{
	global $dbh;
	global $false;
	
		try {
			$group = $dbh->prepare('UPDATE tbl_groups
									SET 
									fld_group_name = :strGN,
									fld_service_id = :intSI,
									fld_recurrency_string = :strRS,
									fld_recurrency_int = :intRI,
									fld_start_date = :dteSD,
									fld_end_date = :dteED,
									fld_start_time = :tmeST,
									fld_end_time = :tmeET,
									fld_group_description = :strDSC
									WHERE id_group = :intGID
									');
			$group->execute(array(	':strGN' => $str_group_name,
									':intSI' => $int_service_id,
									':strRS' => $mxd_recurrencestr_period_div,
									':intRI' => $mxd_recurrenceint_period_div,
									':dteSD' => $str_safe_sd,
									':dteED' =>($str_safe_ed == 'null') ? null : $str_safe_ed,
									':tmeST' => $tme_start_time,
									':tmeET' => $tme_end_time,
									':strDSC' => $str_group_description,
									':intGID' => $int_group_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function delOldFacilitators($int_group_id)
{
	global $dbh;
	
	try {
			$del_fac = $dbh->prepare(' 	DELETE FROM tbl_group_facilitators
										WHERE fld_group_id = :intGID
									');
			$del_fac->execute(array(':intGID' => $int_group_id));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
}


//returns true if a record already exists for the given staff mmember for the given group
function chkGroupStaff($int_this_group,$staff_id)
{
	global $dbh;
	
	try {
			$group_staff = $dbh->prepare('SELECT * 
									FROM tbl_group_facilitators
									WHERE fld_group_id = :intGID
									AND fld_staff_id = :intSID');
			$group_staff->execute(array(':intGID' => $int_this_group,
								   		':intSID' => $staff_id ));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return ($group_staff->rowCount() == 1);
}

function chkGroupMember($int_this_group,$member_id)
{
	global $dbh;
	
	try {
			$group_member = $dbh->prepare('SELECT * 
									FROM tbl_group_facilitators
									WHERE fld_group_id = :intGID
									AND fld_member_id = :intMID');
			$group_member->execute(array(':intGID' => $int_this_group,
								   		':intMID' => $member_id ));
				
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
	
	return ($group_member->rowCount() == 1);
}

function addGroupStaffFacilitator($int_this_group,$staff_id)
{
	global $dbh;
	
		try {
			$group_staff_facilitator = $dbh->prepare('insert into tbl_group_facilitators(
									fld_group_id,
									fld_staff_id
									)
									values(	:intGID,
											:intSID
										   )');
			$group_staff_facilitator->execute(array(	
									':intGID' => $int_this_group,
									':intSID' => $staff_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

function addGroupMemberFacilitator($int_this_group,$member_id)
{
	global $dbh;
	
		try {
			$group_member_facilitator = $dbh->prepare('insert into tbl_group_facilitators(
									fld_group_id,
									fld_member_id
									)
									values(	:intGID,
											:intMID
										   )');
			$group_member_facilitator->execute(array(	
									':intGID' => $int_this_group,
									':intMID' => $member_id
									));
		} catch(PDOException $exp) {
			echo $exp->getMessage();
		}
		
		return $dbh->lastInsertId();
}

?>