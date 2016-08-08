// JavaScript Document

arrListIds = Array();

function jsFindStr(strItem)
{
	for(var i = 0; i < arrListIds.length; i++)
	{
		if(arrListIds[i] == strItem)
		{
			return true;
		}
	}
	
	return false;
}

function jsHideHoverError(strId)
{
	var div = document.getElementById(strId+"_popup");
	div.className = 'popup_error hidden';
	
}

function jsHoverError(strId,strMsg)
{
	
	var this_id = strId+"_popup";
	var div = document.getElementById(strId);
	
	//console.log(div);
	
	var str_class = 'popup_error visible';
	
	if(jsFindStr(this_id))
	{
		var div_error = document.getElementById(this_id);
		div_error.className = str_class;
	}
	else
	{
		var newText = document.createElement('div');
		
		var att = document.createAttribute('id');
		att.value = this_id;
		var att_class = document.createAttribute('class');
		att_class.value = str_class;
		
		newText.setAttributeNode(att);
		newText.setAttributeNode(att_class);
		newText.innerHTML = strMsg;
		div.appendChild(newText);
		
		arrListIds.push(this_id);
	}
}

function jsStringMatch(strOne,strTwo,strMsgTgt,strName)
{
	//grab values
	var strFirst = document.getElementById(strOne).value;
	var strSecond = document.getElementById(strTwo).value;
	
		//output a string
		if(strFirst == strSecond)
		{
			document.getElementById(strMsgTgt).innerHTML = strName + ' match!';
			document.getElementById(strMsgTgt).style.visibility = 'visible';
		}
		else
		{
			document.getElementById(strMsgTgt).innerHTML = strName + 's don\'t match!';
			document.getElementById(strMsgTgt).style.visibility = 'visible';
		}
}

/*	Function for analysing password
	strength.  */
function jsPWordStrength(strPword,strMsgTgt)
{
	
	//array of strength values
	var arStrength = new Array();
	arStrength[0] = 'Strength: Very Weak';
	arStrength[1] = 'Strength: Weak';
	arStrength[2] = 'Strength: Better';
	arStrength[3] = 'Strength: Medium';
	arStrength[4] = 'Strength: Strong';
	arStrength[5] = 'Strength: Strongest';
	
	//initialise strength to 0
	var intStrength = 0;
	//grab password
	var strPassWord = document.getElementById(strPword).value;
	
	//if password bigger than 5 give 1 point
 	if (strPassWord.length > 6) intStrength++;

	//if password has both lower and uppercase characters give 1 point 
	if ( ( strPassWord.match(/[a-z]/) ) && ( strPassWord.match(/[A-Z]/) ) ) intStrength++;
	
	//if password has at least one number give 1 point
 	if (strPassWord.match(/\d+/)) intStrength++;
	
	//if password has at least one special caracther give 1 point
 	if ( strPassWord.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) intStrength++;
	
	//if password bigger than 10 give another 1 point
 	if (strPassWord.length > 10) intStrength++;

	//output the password strength string to target
	if (strPassWord.length < 6)
	{
		document.getElementById(strMsgTgt).style.visibility = 'visible';
		document.getElementById(strMsgTgt).innerHTML = 'Password is too short!';
	} else {
		document.getElementById(strMsgTgt).style.visibility = 'visible';
		document.getElementById(strMsgTgt).innerHTML = arStrength[intStrength];
	}
}

