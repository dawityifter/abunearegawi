<?php
/*******************************************************************************
 *
 *  filename    : Default.php
 *  last change : 2005-03-19
 *  description : login page that checks for correct username and password
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2002 Phillip Hullquist, Deane Barker,
 *
 *	Updated 2005-03-19 by Everette L Mills: Removed dropdown login box and
 *	added user entered login box
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Show disable message if register_globals are turned on.
if (ini_get('register_globals'))
{
	echo "<h3>ChurchInfo will not operate with PHP's register_globals option turned on.<br>";
	echo 'This is for your own protection as the use of this setting could entirely undermine <br>';
	echo 'all security.  You need to either turn off register_globals in your php.ini or else<br>';
	echo 'configure your web server to turn off register_globals for the ChurchInfo directory.</h3>';
	exit;
}

// Include the function library
require 'Include/Config.php';
$bSuppressSessionTests = true;
require 'Include/Functions.php';
// Initialize the variables
$sErrorText = '';

$_SESSION['bSecureServer'] = isAffirmative($_SERVER['HTTPS']);

// Check if https is required, if so, check if we're using https.
// Redirect back this page using https if https is required.
// This prevents someone from accessing via http by typing in the URL
if ($bHTTPSOnly)
{
    if(!$_SESSION['bSecureServer'])
    {
        $_SESSION['bSecureServer'] = TRUE;
        Redirect('Default.php');
        exit;
    }
}
// Is the user requesting to logoff or timed out?
if (isset($_GET["Logoff"]) || isset($_GET['timeout'])) {
    if ($_SESSION['sshowPledges'] == '')
		$_SESSION['sshowPledges'] = 0;
	if ($_SESSION['sshowPayments'] == '')
		$_SESSION['sshowPayments'] = 0;
	if ($_SESSION['bSearchFamily'] == '')
		$_SESSION['bSearchFamily'] = 0;

   if ($_SESSION['iUserID']) {
	   $sSQL = "UPDATE user_usr SET usr_showPledges = " . $_SESSION['sshowPledges'] .
	                 ", usr_showPayments = " . $_SESSION['sshowPayments'] .
				     ", usr_showSince = '" . $_SESSION['sshowSince'] . "'" .
				     ", usr_defaultFY = '" . $_SESSION['idefaultFY'] . "'" .
				     ", usr_currentDeposit = '" . $_SESSION['iCurrentDeposit'] . "'";
		if ($_SESSION['dCalStart'] != '')
			$sSQL .= ", usr_CalStart = '" . $_SESSION['dCalStart'] . "'";
		if ($_SESSION['dCalEnd'] != '')
			$sSQL .= ", usr_CalEnd = '" . $_SESSION['dCalEnd'] . "'";
		if ($_SESSION['dCalNoSchool1'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool1'] . "'";
		if ($_SESSION['dCalNoSchool2'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool2'] . "'";
		if ($_SESSION['dCalNoSchool3'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool3'] . "'";
		if ($_SESSION['dCalNoSchool4'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool4'] . "'";
		if ($_SESSION['dCalNoSchool5'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool5'] . "'";
		if ($_SESSION['dCalNoSchool6'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool6'] . "'";
		if ($_SESSION['dCalNoSchool7'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool7'] . "'";
		if ($_SESSION['dCalNoSchool8'] != '')
			$sSQL .= ", usr_CalNoSchool1 = '" . $_SESSION['dCalNoSchool8'] . "'";
		$sSQL .= ", usr_SearchFamily = '" . $_SESSION['bSearchFamily'] . "'" .
				     " WHERE usr_per_ID = " . $_SESSION['iUserID'];
	   RunQuery($sSQL);
   }

	$_COOKIE = array();
	$_SESSION = array();
	session_destroy();
}

// Get the UserID out of user name submitted in form results
if (isset($_POST['User']) && $sErrorText == '') {

	// Get the information for the selected user
	$UserName = FilterInput($_POST['User'],'string',32);
	$uSQL = "SELECT usr_per_id FROM user_usr WHERE usr_UserName like '$UserName'";
	$usQueryResult = RunQuery($uSQL);
	$usQueryResultSet = mysql_fetch_array($usQueryResult);
	If ($usQueryResultSet == Null){
		// Set the error text
		$sErrorText = '&nbsp;' . gettext('Invalid login or password');
	}else{
		//Set user Id based on login name provided
		$iUserID = $usQueryResultSet['usr_per_id'];
	}
}else{
	//User ID was not submitted with form
	$iUserID = 0;
}


// Has the form been submitted?
if ($iUserID > 0)
{
	// Get the information for the selected user
	$sSQL = "SELECT * FROM user_usr WHERE usr_per_ID ='$iUserID'";
	extract(mysql_fetch_array(RunQuery($sSQL)));

	$sSQL = "SELECT * FROM person_per WHERE per_ID ='$iUserID'";
	extract(mysql_fetch_array(RunQuery($sSQL)));

    $bPasswordMatch = FALSE;
    // Check the user password
    if (strlen($usr_Password) == 40) {
        // The password was stored using the new 40 character sha1 hash
        $tmp = strtolower($_POST['Password'].$iUserID);
        $sPasswordHash = sha1(sha1($tmp).$tmp);
        $bPasswordMatch = ($usr_Password == $sPasswordHash);
    } else {
        // The password was stored using the old 32 character md5 hash
        $tmp = strtolower($_POST['Password']);
        $sPasswordHash = md5($tmp);
        $bPasswordMatch = ($usr_Password == $sPasswordHash);

        if ($bPasswordMatch) {
            // If the password matches update from 32 character hash
            // to 40 character hash
            $tmp = strtolower($_POST['Password'].$iUserID);
            $sPasswordHash = sha1(sha1($tmp).$tmp);
            $sSQL = "UPDATE user_usr SET usr_Password='".$sPasswordHash."' ".
                    "WHERE usr_per_ID ='".$iUserID."'";
            RunQuery($sSQL);
        }
    }

	// Block the login if a maximum login failure count has been reached
	if ($iMaxFailedLogins > 0 && $usr_FailedLogins >= $iMaxFailedLogins) {

		$sErrorText = '<br>' . gettext('Too many failed logins: your account has been locked.  Please contact an administrator.');
	}

	// Does the password match?
	elseif (!$bPasswordMatch) {

		// Increment the FailedLogins
		$sSQL = 'UPDATE user_usr SET usr_FailedLogins = usr_FailedLogins + 1 '.
                "WHERE usr_per_ID ='$iUserID'";
		RunQuery($sSQL);

		// Set the error text
		$sErrorText = '&nbsp;' . gettext('Invalid login or password');

	} else {

		// Set the LastLogin and Increment the LoginCount
		$sSQL = "UPDATE user_usr SET usr_LastLogin = NOW(), usr_LoginCount = usr_LoginCount + 1, usr_FailedLogins = 0 WHERE usr_per_ID ='$iUserID'";
		RunQuery($sSQL);

		// Set the User's family id in case EditSelf is enabled
		$_SESSION['iFamID'] = $per_fam_ID;

		// Set the UserID
		$_SESSION['iUserID'] = $usr_per_ID;

		// Set the Root Path ... used in basic security check
		$_SESSION['sRootPath'] = $sRootPath;

		// Set the Actual Name for use in the sidebar
		$_SESSION['UserFirstName'] = $per_FirstName;

		// Set the Actual Name for use in the sidebar
		$_SESSION['UserLastName'] = $per_LastName;

		// Set the pagination Search Limit
		$_SESSION['SearchLimit'] = $usr_SearchLimit;

		// Set the User's email address
		$_SESSION['sEmailAddress'] = $per_Email;
/* -----------------------------------MRBS Bridging adds --------------------------------------------*/
		// Set the User's email address
		$_SESSION["UserName"] = $usr_UserName;
/*------------------------------------MRBS Bridging add ends ---------------------------------------*/
		// If user has administrator privilege, override other settings and enable all permissions.
		if ($usr_Admin) {
			$_SESSION['bAddRecords'] = true;
			$_SESSION['bEditRecords'] = true;
			$_SESSION['bDeleteRecords'] = true;
			$_SESSION['bMenuOptions'] = true;
			$_SESSION['bManageGroups'] = true;
			$_SESSION['bFinance'] = true;
			$_SESSION['bNotes'] = true;
			$_SESSION['bCommunication'] = true;
			$_SESSION['bCanvasser'] = true;
			$_SESSION['bAdmin'] = true;
		}

		// Otherwise, set the individual permissions.
		else {
			// Set the Add permission
			$_SESSION['bAddRecords'] = $usr_AddRecords;

			// Set the Edit permission
			$_SESSION['bEditRecords'] = $usr_EditRecords;

			// Set the Delete permission
			$_SESSION['bDeleteRecords'] = $usr_DeleteRecords;

			// Set the Menu Option permission
			$_SESSION['bMenuOptions'] = $usr_MenuOptions;

			// Set the ManageGroups permission
			$_SESSION['bManageGroups'] = $usr_ManageGroups;

			// Set the Donations and Finance permission
			$_SESSION['bFinance'] = $usr_Finance;

			// Set the Notes permission
			$_SESSION['bNotes'] = $usr_Notes;

			// Set the Communications permission
			$_SESSION['bCommunication'] = $usr_Communication;

			// Set the EditSelf permission
			$_SESSION['bEditSelf'] = $usr_EditSelf;

			// Set the Canvasser permission
			$_SESSION['bCanvasser'] = $usr_Canvasser;

			// Set the Admin permission
			$_SESSION['bAdmin'] = false;
		}

		// Set the FailedLogins
		$_SESSION['iFailedLogins'] = $usr_FailedLogins;

		// Set the LoginCount
		$_SESSION['iLoginCount'] = $usr_LoginCount;

		// Set the Last Login
        $_SESSION['dLastLogin'] = $usr_LastLogin;


		// Set the Workspace Width
		$_SESSION['iWorkspaceWidth'] = $usr_WorkspaceWidth;

		// Set the Base Font Size
		$_SESSION['iBaseFontSize'] = $usr_BaseFontSize;

		// Set the Style Sheet
		$_SESSION['sStyle'] = $usr_Style;

		// Create the Cart
		$_SESSION['aPeopleCart'] = array();

		// Create the variable for the Global Message
		$_SESSION['sGlobalMessage'] = '';

		// Set whether or not we need a password change
		$_SESSION['bNeedPasswordChange'] = $usr_NeedPasswordChange;

		// Initialize the last operation time
		$_SESSION['tLastOperation'] = time();

		// If PHP's magic quotes setting is turned off, we want to use a workaround to ensure security.
		$_SESSION['bHasMagicQuotes'] = get_magic_quotes_gpc();

		// Pledge and payment preferences
		$_SESSION['sshowPledges'] = $usr_showPledges;
		$_SESSION['sshowPayments'] = $usr_showPayments;
		$_SESSION['sshowSince'] = $usr_showSince;
		$_SESSION['idefaultFY'] = $usr_defaultFY;
		$_SESSION['iCurrentDeposit'] = $usr_currentDeposit;

		// Church school calendar preferences
		$_SESSION['dCalStart'] = $usr_CalStart;
		$_SESSION['dCalEnd'] = $usr_CalEnd;
		$_SESSION['dCalNoSchool1'] = $usr_CalNoSchool1;
		$_SESSION['dCalNoSchool2'] = $usr_CalNoSchool2;
		$_SESSION['dCalNoSchool3'] = $usr_CalNoSchool3;
		$_SESSION['dCalNoSchool4'] = $usr_CalNoSchool4;
		$_SESSION['dCalNoSchool5'] = $usr_CalNoSchool5;
		$_SESSION['dCalNoSchool6'] = $usr_CalNoSchool6;
		$_SESSION['dCalNoSchool7'] = $usr_CalNoSchool7;
		$_SESSION['dCalNoSchool8'] = $usr_CalNoSchool8;

		// Search preference
		$_SESSION['bSearchFamily'] = $usr_SearchFamily;

		// Redirect to the Menu
		Redirect('CheckVersion.php');
        exit;
	}
}
// Turn ON output buffering
ob_start();

// Set the page title and include HTML header
?>
<html>
<head>
	<meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="Include/Style.css">
	<title><?php echo gettext('ChurchInfo: Login'); ?></title>

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
<script type="text/javascript">
$(document).ready(function() {
	
	$("#login").click(function() {
	
		var action = $("#form1").attr('action');
		var form_data = {
			username: $("#username").val(),
			password: $("#password").val(),
			is_ajax: 1
		};
		
		$.ajax({
			type: "POST",
			url: action,
			data: form_data,
			success: function(response)
			{
				if(response == 'success')
					$("#form1").slideUp('slow', function() {
						$("#message").html("<p class='success'>You have logged in successfully!</p>");
					});
				else
					$("#message").html("<p class='error'>Invalid username and/or password.</p>");	
			}
		});
		
		return false;
	});
	
});
</script>
</head>
<body>
<div id="content">
  <h1>Login Form</h1>
  <form id="form1" name="form1" action="Login1.php" method="post">
    <p>
      <label for="username">Username: </label>
      <input type="text" name="username" id="username" />
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" />
    </p>
    <p>
      <input type="submit" id="login" name="login" />
    </p>
  </form>
    <div id="message"></div>
</div>
</body>