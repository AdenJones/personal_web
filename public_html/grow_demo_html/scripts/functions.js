// JavaScript Document


function jsFollowTheMouse(event,target_id)
{
	target = document.getElementById(target_id);
	
	if( target.style.visibility = 'hidden' )
	{
	jsShow(target_id);
	}
	//this function is just a demo. See dsp_add_edit_group_attendance_content for inline calls
	target.style.top = (event.clientY + 5)+ 'px';
	target.style.left = (event.clientX + 5)+ 'px';
	
	//document.getElementById(target_id).innerHTML = 'y= '+event.clientY+'; x='+event.clientX;
}

function jsMatchColour(base_value,target_value,style_target)
{
	comp_value = document.getElementById(target_value).value;
	target_class = document.getElementById(style_target);
	
	if(base_value == comp_value)
	{
		if(target_class.classList.contains("error"))
		{
			target_class.classList.remove("error");
		}
		
		if(!target_class.classList.contains("edit"))
		{
			target_class.classList.add("edit");
		}
	} else {
		if(target_class.classList.contains("edit"))
		{
			target_class.classList.remove("edit");
		}
		
		if(!target_class.classList.contains("error"))
		{
			target_class.classList.add("error");
		}
	}
	
}

function jsEditAtt(int_att_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=enter_attendance&int_att_id='+int_att_id;
}

function jsEditDC(int_dc_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=add_edit_daily_contact&view=self&int_dc_id='+int_dc_id;
}


function jsEditRB(int_rb_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=add_room_booking&id_room_booking='+int_rb_id;
}

function jsDelUser(user_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_user&id_user='+user_id+'&del_user='+1;
}

function jsDelGRPRGN(group_id,int_del_grp_rgn_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_groups_regions&access=secure&id_group='+group_id+'&int_del_grp_rgn_id='+int_del_grp_rgn_id;
}

function jsDeleteRGN(int_del_rgn_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_regions&access=secure&int_del_rgn_id='+int_del_rgn_id;
}

function jsDeleteGroupImproved(int_del_grp_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_group&id_group='+int_del_grp_id+'&del_group=1';
}

function jsDeleteGRP(int_del_grp_id,target_page)
{
	window.location.href = target_page+'&int_del_grp_id='+int_del_grp_id;
}

function jsDeleteDC(int_dc_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=delete_daily_contact&int_dc_id='+int_dc_id;
}

function jsDeleteMemComDteImproved(id_user,id_committed,int_submitted)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_user&id_user='+id_user+'&id_committed='+id_committed+'&form_submitted='+int_submitted;
}

function jsDeleteMemComDte(id_user,id_committed,int_submitted,return_to)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_member_dates&id_user='+id_user+'&delete=committed&id_committed='+id_committed+'&form_submitted='+int_submitted+'&return_to='+return_to;
}

function jsDeleteAttendance(id_attendance,id_group,int_submitted,date)
{
	window.location.href = '/grow_demo_html/index.php?page_id=add_edit_group_attendance&id_attendance='+id_attendance+'&id_group='+id_group+'&form_submitted='+int_submitted+'&date='+date;
}

function jsDeleteActivity(id_activity,id_user,int_submitted)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_user&id_activity='+id_activity+'&id_user='+id_user+'&form_submitted='+int_submitted;
}


function jsCancelBooking(int_cancel_id)
{
	window.location.href = '/grow_demo_html/index.php?page_id=view_room_bookings&int_cancel_id='+int_cancel_id;
}

function jsShow(str_id)
{
	//document.getElementById(str_id).style.display = 'block';
	$('#'+str_id).css('display','block');
	
}

function jsHide(str_id)
{
	//document.getElementById(str_id).style.display = 'none';
	$('#'+str_id).css('display','none');
}

function jsUpdateSelect(str_period_select,str_period_target,int_recurrence_default)
{
	int_recurrence_default = (int_recurrence_default === undefined ) ? '' : int_recurrence_default;

	var obj_period_id = document.getElementById(str_period_select);
	var obj_period_target = document.getElementById(str_period_target);
	
	var str_index = obj_period_id.value;
	
	var int_index = window.arr_limits[str_index];
	
	var int_selected
	
	if( int_recurrence_default == '')
	{
		int_selected = obj_period_target.value;
	} else {
		int_selected = int_recurrence_default;
	}
	
	
	var str_output = '';
	
	for(var i=1;i<=int_index;i++)
	{
		if(int_selected == i)
		{
			str_selected = ' selected';
		} else {
			str_selected = '';
		}
		str_output += '<option value="' + i + '"' + str_selected + '>' + i + '</option>' + "\n";
	}
	
	obj_period_target.innerHTML = str_output;
}

function jsFacilitatorPopUp(str_container_id,str_popup_id)
{
	var obj_popup = document.getElementById(str_popup_id);
	
	obj_popup.style.visibility = 'visible';
}

function jsHideFacilitatorPopUp(str_popup_id,str_inner_container_id)
{
	var obj_popup = document.getElementById(str_popup_id);
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	obj_popup.style.visibility = 'hidden';
	obj_inner_popup.style.visibility = 'hidden';
}

function jsFacilitatorStaffPopUp(str_inner_container_id,str_outer_container_id,arr_staff_ids)
{
	var obj_pop_up_menu = document.getElementById(window.pop_up_id);
	
	if(obj_pop_up_menu.style.visibility == "hidden")
	{
		return;
	}
	
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_staff_ids = '';
	
	if(arr_staff_ids.length > 0)
	{
		for(i=0;i<arr_staff_ids.length;i++)
		{
			if(i == (arr_staff_ids.length - 1))
			{
				str_staff_ids += arr_staff_ids[i];
			} else {
				str_staff_ids += arr_staff_ids[i] + ',';
			}
		}
	}
	
	//console.log(str_staff_ids);
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_staff.php?target_container="+str_outer_container_id+"&str_inner_container="+str_inner_container_id+"&staff_ids="+str_staff_ids,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsRemoveItem(item_id)
{
	item_id.parentNode.parentNode.removeChild(item_id.parentNode);
}

function jsRemoveStaff(item_id,int_staff_id)
{
	//remove the staff id from the list
	//window.staff_ids.push(int_staff_id);
	var index = window.staff_ids.indexOf(int_staff_id);
	
	if(index > -1)
	{
		window.staff_ids.splice(index,1);
	}
	
	//remove the staff id from the hidden staff input
	var obj_input_staff_ids = document.getElementById('csv_staff_ids');
	var str_input = (obj_input_staff_ids.value).trim();
	var index = str_input.indexOf(int_staff_id)
	
	if( index == 0 )
	{
		additioner = 2;
	} else {
		additioner = 1
	}
	var new_string = str_input.substring(0,index-1) + str_input.substring(index+additioner,str_input.length);
	//alert(new_string);
	
	obj_input_staff_ids.value = new_string;
	
	jsRemoveItem(item_id);
}

function jsRemoveMember(item_id,int_member_id)
{
	//remove the staff id from the list
	//window.staff_ids.push(int_staff_id);
	var index = window.member_ids.indexOf(int_member_id);
	
	if(index > -1)
	{
		window.member_ids.splice(index,1);
	}
	
	var obj_input_member_ids = document.getElementById('csv_member_ids');
	var str_input = (obj_input_member_ids.value).trim();
	var index = str_input.indexOf(int_member_id)
	
	if( index == 0 )
	{
		additioner = 2;
	} else {
		additioner = 1
	}
	var new_string = str_input.substring(0,index-1) + str_input.substring(index+additioner,str_input.length);
	//alert(new_string);
	
	obj_input_member_ids.value = new_string;
	
	jsRemoveItem(item_id);
}


function jsAddStaff(this_item,int_staff_id,str_staff_name,str_inner_container_id,str_outer_container_id)
{
	
	var obj_outer_container = document.getElementById(str_outer_container_id);
	
	//create delete functionality
	var remove = document.createElement("img");
	var src = document.createAttribute("src");
	src.value = '/images/white_close.gif';
	var click_function = document.createAttribute("onclick");
	click_function.value = "jsRemoveStaff(this,"+int_staff_id+");jsFacilitatorStaffPopUp('"+str_inner_container_id+"','"+str_outer_container_id+"',window.staff_ids)";
	var img_class = document.createAttribute("class");
	img_class.value = 'image_right';
	remove.setAttributeNode(src);
	remove.setAttributeNode(click_function);
	remove.setAttributeNode(img_class);
	//still need to add remove item from array funcionality when a staff member is deleted
	
	//create input hidden
	/*var input = document.createElement("input");
	var input_type = document.createAttribute("type");
	input_type.value = 'hidden';
	var input_value = document.createAttribute("value");
	input_value.value = int_staff_id;
	input.setAttributeNode(input_type);
	input.setAttributeNode(input_value);
	*/
	
	//add the staff id to the hidden input for staff
	var obj_input_staff_ids = document.getElementById('csv_staff_ids');
	var str_input_staff_ids = (obj_input_staff_ids.value).trim();
	
	if(str_input_staff_ids.length == 0)
	{
		obj_input_staff_ids.value = int_staff_id;
	} else {
		obj_input_staff_ids.value = str_input_staff_ids + ',' + int_staff_id;
	}
	
	var node=document.createElement("div");
	var div_class = document.createAttribute("class");
	div_class.value = 'block_with_close';
	node.setAttributeNode(div_class);
	node.innerHTML = "Staff Member: " + str_staff_name;
	node.appendChild(remove);
	//node.appendChild(input);
	
	obj_outer_container.appendChild(node);
	
	//remove from list if not ''
	if( this_item != '' )
	{
		jsRemoveItem(this_item)
	}
	
	window.staff_ids.push(int_staff_id); //put the staff id into the array
	
	//console.log(int_staff_id);
	//console.log(window.staff_ids);
}

function jsFacilitatorMemberPopUp(str_inner_container_id,str_outer_container_id,str_f_name,str_l_name,member_ids)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_members.php?target_container="+str_outer_container_id+"&str_inner_container="+str_inner_container_id+"&str_f_name="+str_f_name+"&str_l_name="+str_l_name+"&member_ids="+member_ids,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFacilitatorMemberSearch(str_f_name,str_l_name,str_inner_container_id,str_outer_container_id)
{
	var obj_pop_up_menu = document.getElementById(window.pop_up_id);
	
	if(obj_pop_up_menu.style.visibility == "hidden")
	{
		return;
	}
	
	str_first_name = document.getElementById(str_f_name).value;
	str_last_name = document.getElementById(str_l_name).value;
	
	jsFacilitatorMemberPopUp(str_inner_container_id,str_outer_container_id,str_first_name,str_last_name,window.member_ids);
	
}

function jsFacilitatorStaffPopUpRevised(str_inner_container_id,str_outer_container_id,str_text,staff_ids)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_staff_fac.php?target_container="+str_outer_container_id+"&str_inner_container="+str_inner_container_id+"&str_text="+str_text+"&staff_ids="+staff_ids,true);
	xmlhttp.send();
	
	obj_inner_popup.style.visibility = 'visible';
	
}

function jsFacilitatorStaffPopUpRevisedOld(str_inner_container_id,str_outer_container_id,str_f_name,str_l_name,staff_ids)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_staff_fac_revised.php?target_container="+str_outer_container_id+"&str_inner_container="+str_inner_container_id+"&str_f_name="+str_f_name+"&str_l_name="+str_l_name+"&staff_ids="+staff_ids,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFacilitatorStaffSearch(str_text,str_inner_container_id,str_outer_container_id)
{
	var obj_pop_up_menu = document.getElementById(window.pop_up_id);
	
	if(obj_pop_up_menu.style.visibility == "hidden")
	{
		return;
	}
	
	str_text = document.getElementById(str_text).value;
	
	jsFacilitatorStaffPopUpRevised(str_inner_container_id,str_outer_container_id,str_text,window.staff_ids);
	
}

function jsFacilitatorStaffSearchOld(str_f_name,str_l_name,str_inner_container_id,str_outer_container_id)
{
	var obj_pop_up_menu = document.getElementById(window.pop_up_id);
	
	if(obj_pop_up_menu.style.visibility == "hidden")
	{
		return;
	}
	
	str_first_name = document.getElementById(str_f_name).value;
	str_last_name = document.getElementById(str_l_name).value;
	
	jsFacilitatorStaffPopUpRevisedOld(str_inner_container_id,str_outer_container_id,str_first_name,str_last_name,window.staff_ids);
	
}

function jsAddMember(this_item,int_member_id,str_member_name,str_inner_container_id,str_outer_container_id)
{
	
	var obj_outer_container = document.getElementById(str_outer_container_id);
	
	//create delete functionality
	var remove = document.createElement("img");
	var src = document.createAttribute("src");
	src.value = '/images/white_close.gif';
	var click_function = document.createAttribute("onclick");
	click_function.value = "jsRemoveMember(this,"+int_member_id+");jsFacilitatorMemberSearch('str_f_name','str_l_name','"+str_inner_container_id+"','"+str_outer_container_id+"');";
	var img_class = document.createAttribute("class");
	img_class.value = 'image_right';
	remove.setAttributeNode(src);
	remove.setAttributeNode(click_function);
	remove.setAttributeNode(img_class);
	
	
	//create input hidden
	/*var input = document.createElement("input");
	var input_type = document.createAttribute("type");
	input_type.value = 'hidden';
	var input_value = document.createAttribute("value");
	input_value.value = int_member_id;
	input.setAttributeNode(input_type);
	input.setAttributeNode(input_value);
	*/
	
	//add the staff id to the hidden input for staff
	var obj_input_member_ids = document.getElementById('csv_member_ids');
	var str_input_member_ids = (obj_input_member_ids.value).trim();
	
	if(str_input_member_ids.length == 0)
	{
		obj_input_member_ids.value = int_member_id;
	} else {
		obj_input_member_ids.value = str_input_member_ids + ',' + int_member_id;
	}
	
	var node=document.createElement("div");
	var div_class = document.createAttribute("class");
	div_class.value = 'block_with_close';
	node.setAttributeNode(div_class);
	node.innerHTML = "Member: " + str_member_name;
	node.appendChild(remove);
	//node.appendChild(input);
	
	obj_outer_container.appendChild(node);
	
	//remove from list
	if(this_item != '')
	{
		jsRemoveItem(this_item);
	}
	
	
	window.member_ids.push(int_member_id); //put the staff id into the array
	
}

function jsCreateEmCont() //this wonderful idea for a function has been postponed to a future time. It was designed for dynamic emergency contacts
{
	//increment the em con counter
	//window.em_con_count++;
	var obj_em_con_counter = document.getElementById('em_con_count');
	var int_count = obj_em_con_counter.value;
	int_count++; //increment the counter
	obj_em_con_counter.value = int_count; //update the counter
	
	var str_inner_container_id = 'emergency_contact_'+int_count; //create the string for the parent container for this em con
	
	//grab outer container for append
	var obj_outer_container = document.getElementById(window.em_con_parent_container);
	
	var node=document.createElement("div");
	var div_class = document.createAttribute("class");
	div_class.value = 'emergency_contact';
	var div_id = document.createAttribute("id");
	div_id.value = str_inner_container_id;
	node.setAttributeNode(div_class);
	node.setAttributeNode(div_id);
	//add the node
	obj_outer_container.appendChild(node);
	
	var obj_inner_container = document.getElementById(str_inner_container_id);
	
	//create the inner html
	var str_item = '';
	str_item += '<input type="hidden" name="em_con_hidden_'+int_count+'" id="em_con_hidden_'+int_count+'" value="1"/>'+"\n"; //create the input that will be detected when inserting and performing validation
	str_item += '<label for="em_con_first_name_'+int_count+'">First Name</label>';
	str_item += '<input type="text" name="em_con_first_name_'+int_count+'" id="em_con_first_name_'+int_count+'" value=""/>';
	
	obj_inner_container.innerHTML = str_item;
	
}

function jsSelectGroupPopUpImproved(str_inner_container_id,str_text_container_id,full_uri)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var record_limit = document.getElementById('record_limit').value;
	var limit_to = document.getElementById('extra').value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET",full_uri+"/index.php?page_id=search_groups_improved&str_text="+str_text+"&record_limit="+record_limit+"&limit_to="+limit_to,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}


function jsSelectGroupPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_groups&str_text="+str_text,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsAddGroupGeneric(int_group_id,str_group_name)
{
	
	//update the values
	document.getElementById(window.grp_sel_hidden_input).value = int_group_id;
	document.getElementById(window.grp_sel_text_input).value = str_group_name;
	//hide the pop up window
	document.getElementById(window.grp_sel_popup_container).style.visibility = 'hidden';
}

function jsSelectGroupSearch(int_service_id,str_group_name,str_inner_container_id,str_outer_container_id)
{
		
	int_service_id = document.getElementById(int_service_id).value;
	str_group_name = document.getElementById(str_group_name).value;
	
	jsSelectGroupPopUp(str_inner_container_id,str_outer_container_id,int_service_id,str_group_name);
	
}

function jsSelectGroupGeneric(int_group_id,str_group_name)
{
	//update the values
	document.getElementById(window.grp_sel_hidden_input).value = int_group_id;
	document.getElementById(window.grp_sel_text_input).value = str_group_name;
	//hide the pop up window
	document.getElementById(window.grp_sel_popup_container).style.visibility = 'hidden';
}

function jsAttendanceMemberPopUp(str_inner_container_id,str_outer_container_id,str_f_name,str_l_name)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_members_attendance.php?str_f_name="+str_f_name+"&str_l_name="+str_l_name,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFindVolunteerPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var record_limit = document.getElementById("record_limit").value;
	var extra = document.getElementById("extra").value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_volunteers_generic&str_text="+str_text+"&record_limit="+record_limit+"&extra="+extra,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsVolunteerPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_volunteers&str_text="+str_text,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsAddVolunteerGeneric(int_member_id,str_member_name)
{
	
	//update the values
	document.getElementById(window.mem_sel_hidden_input).value = int_member_id;
	document.getElementById(window.mem_sel_text_input).value = str_member_name;
	//hide the pop up window
	document.getElementById(window.mem_sel_popup_container).style.visibility = 'hidden';
}

function jsSearchMemberGeneric(int_member_id,str_member_name,container_id)
{
	
	//alert(container_id);
	
	var hidden_input = "window.mem_sel_hidden_input" + container_id;
	var text_input = "window.mem_sel_text_input" + container_id;
	var popup = "window.mem_sel_popup_container" + container_id;
	//alert(hidden_input)
	
	//update the values
	document.getElementById(eval(hidden_input)).value = int_member_id;
	document.getElementById(eval(text_input)).value = str_member_name;
	//hide the pop up window
	document.getElementById(eval(popup)).style.visibility = 'hidden';
	
}

function jsAddMemberGeneric(int_member_id,str_member_name,committed)
{
	//reset the checked value
	document.getElementById('chk_committed').checked = false;
	
	if(committed)
	{
		var str_display = 'none';
	} else {
		var str_display = 'inline-block';
	}
	
	//update the values
	document.getElementById(window.mem_sel_hidden_input).value = int_member_id;
	document.getElementById(window.mem_sel_text_input).value = str_member_name;
	//hide the pop up window
	document.getElementById('bl_committed').style.display = str_display;
	document.getElementById(window.mem_sel_popup_container).style.visibility = 'hidden';
	
}

function jsStaffPopUpImproved(str_inner_container_id,group_id,date,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_staff_for_attendance&str_text="+str_text+"&group_id="+group_id+"&group_date="+date,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsStaffPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_staff&str_text="+str_text,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFindMemberPopUpForMultiple(str_inner_container_id,str_text_container_id,str_pop_up_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_members_generic&str_text="+str_text+"&container_id="+str_text_container_id+"&str_pop_up_id="+str_pop_up_id,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFindStaffPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var record_limit = document.getElementById("record_limit").value;
	var extra = document.getElementById("extra").value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_staff_generic&str_text="+str_text+"&record_limit="+record_limit+"&extra="+extra,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsFindMemberPopUp(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var record_limit = document.getElementById("record_limit").value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_members_generic&str_text="+str_text+"&record_limit="+record_limit,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsMemberPopUpForAttendance(str_inner_container_id,str_text_container_id,id_group,this_date)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var search_scope = document.getElementById("member_search_group_range").value;
	var record_limit = document.getElementById("record_limit").value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_members_optimised_for_attendance&str_text="+str_text+"&search_scope="+search_scope+"&id_group="+id_group+"&this_date="+this_date+"&record_limit="+record_limit,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}



function jsMemberPopUp(str_inner_container_id,str_text_container_id,id_group,this_date)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	var search_scope = document.getElementById("member_search_group_range").value;
	var record_limit = document.getElementById("record_limit").value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/grow_demo_html/index.php?page_id=search_members_optimised&str_text="+str_text+"&search_scope="+search_scope+"&id_group="+id_group+"&this_date="+this_date+"&record_limit="+record_limit,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsMemberPopUpOld(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_members_new.php?str_text="+str_text,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsMemberSearch(str_inner_container_id,str_outer_container_id)
{
		
	str_first_name = document.getElementById(str_f_name).value;
	str_last_name = document.getElementById(str_l_name).value;
	
	jsMemberPopUp(str_inner_container_id,str_first_name,str_last_name);
	
}

function jsStaffPopUpRevisedNew(str_inner_container_id,str_text_container_id)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	var str_text =  document.getElementById(str_text_container_id).value;
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_staff_revised_new.php?str_text="+str_text,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsStaffPopUpRevised(str_inner_container_id,str_outer_container_id,str_f_name,str_l_name)
{
	var obj_inner_popup = document.getElementById(str_inner_container_id);
	
	
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		obj_inner_popup.innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","/ajax/ajax_get_staff_revised.php?str_f_name="+str_f_name+"&str_l_name="+str_l_name,true);
	xmlhttp.send();
	
	
	obj_inner_popup.style.visibility = 'visible';
}

function jsStaffSearch(str_f_name,str_l_name,str_inner_container_id,str_outer_container_id)
{
	str_first_name = document.getElementById(str_f_name).value;
	str_last_name = document.getElementById(str_l_name).value;
	
	jsStaffPopUpRevised(str_inner_container_id,str_outer_container_id,str_first_name,str_last_name);
}


function jsAttendanceMemberSearch(str_f_name,str_l_name,str_inner_container_id,str_outer_container_id)
{
		
	str_first_name = document.getElementById(str_f_name).value;
	str_last_name = document.getElementById(str_l_name).value;
	
	jsAttendanceMemberPopUp(str_inner_container_id,str_outer_container_id,str_first_name,str_last_name);
	
}

function jsHidePopUp(str_target_container)
{
	document.getElementById(str_target_container).style.visibility = 'hidden';
}

function jsAddStaffGeneric(int_staff_id,str_staff_name)
{
	//update the values
	document.getElementById(window.stf_sel_hidden_input).value = int_staff_id;
	document.getElementById(window.stf_sel_text_input).value = str_staff_name;
	//hide the pop up window
	document.getElementById(window.stf_sel_popup_container).style.visibility = 'hidden';
}

function jsShowHideMemberStaff(self,str_member_div,str_staff_div)
{
	str_selection = self.value;
	obj_member_div = document.getElementById(str_member_div);
	obj_staff_div = document.getElementById(str_staff_div);
	
	if(str_selection == 'by_staff')
	{
		obj_member_div.style.display = 'none';
		obj_staff_div.style.display = 'inline-block';
	} else {
		obj_member_div.style.display = 'inline-block';
		obj_staff_div.style.display = 'none';
	}
}

function jsShowHideBranchesRegions(self,str_branch_div,str_region_div)
{
	str_selection = self.value;
	obj_branch_div = document.getElementById(str_branch_div);
	obj_region_div = document.getElementById(str_region_div);
	
	if(str_selection == 'by_region')
	{
		obj_branch_div.style.display = 'none';
		obj_region_div.style.display = 'inline-block';
	} else {
		obj_branch_div.style.display = 'inline-block';
		obj_region_div.style.display = 'none';
	}
}