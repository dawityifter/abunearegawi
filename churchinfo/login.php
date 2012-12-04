<?php
/*******************************************************************************
 *
 *  filename    : login.php
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
        Redirect('login.php');
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
if (isset($_POST['username']) && $sErrorText == '') {

	// Get the information for the selected user
	$UserName = FilterInput($_POST['username'],'string',32);
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
        $tmp = strtolower($_POST['password'].$iUserID);
        $sPasswordHash = sha1(sha1($tmp).$tmp);
        $bPasswordMatch = ($usr_Password == $sPasswordHash);
    } else {
        // The password was stored using the old 32 character md5 hash
        $tmp = strtolower($_POST['password']);
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
        <meta name="GENERATOR" content="Evrsoft First Page">
<style type="text/css">
@import url(../style.css);
</style>
        <title>Abune Tekle Hymanot we Abune Aregawi EOTC</title>
        <meta name="description" content="" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js" type="text/javascript"></script> 
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/base/jquery-ui.css" type="text/css" media="all" /> 
        <script>
            jQuery(function($){
                // simple jQuery validation script
                $('#login').submit(function(){
                    var valid = true;
                    var errormsg = 'This field is required!';
                    var errorcn = 'error';
                    $('.' + errorcn, this).remove();			
                    $('.required', this).each(function(){
                        var parent = $(this).parent();
                        if( $(this).val() == '' ){
                            var msg = $(this).attr('title');
                            msg = (msg != '') ? msg : errormsg;
                            $('<span class="'+ errorcn +'">'+ msg +'</span>')
                            .appendTo(parent)
                            .fadeIn('fast')
                            .click(function(){ $(this).remove(); })
                            valid = false;
                        };
                    });
                    return valid;
                });
            })
        </script>
        <script>
            
            $("#PasswordError").html("<p class='error'>Invalid username and/or password.</p>"); 
            $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $( "#dialog:ui-dialog" ).dialog( "destroy" );
                var name = $( "#name" ),
                email = $( "#email" ),
                password = $( "#password" ),
                allFields = $( [] ).add( name ).add( email ).add( password ),
                tips = $( ".validateTips" );
                function updateTips( t ) {
                    tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                    setTimeout(function() {
                        tips.removeClass( "ui-state-highlight", 1500 );
                    }, 500 );
                }

                function checkLength( o, n, min, max ) {
                    if ( o.val().length > max || o.val().length < min ) {
                        o.addClass( "ui-state-error" );
                        updateTips( "Length of " + n + " must be between " +
                            min + " and " + max + "." );
                        return false;
                    } else {
                        return true;
                    }
                }
                function checkRegexp( o, regexp, n ) {
                    if ( !( regexp.test( o.val() ) ) ) {
                        o.addClass( "ui-state-error" );
                        updateTips( n );
                        return false;
                    } else {
                        return true;
                    }



                }

                $( "#dialog-form" ).dialog({
                    autoOpen: false,
                    height: 400,
                    width: 350,
                    modal: true,
                    buttons: {
                        "Create an account": function() {
                            var bValid = true;
                            allFields.removeClass( "ui-state-error" );
                            bValid = bValid && checkLength( name, "username", 3, 16 );
                            bValid = bValid && checkLength( email, "email", 6, 80 );
                            bValid = bValid && checkLength( password, "password", 5, 16 );
                            bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
                            // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                            bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
                           bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
                          if ( bValid ) {
                               $( "#users tbody" ).append( "<tr>" +
                                   "<td>" + name.val() + "</td>" + 
                                  "<td>" + email.val() + "</td>" + 
                                  "<td>" + password.val() + "</td>" +
                                   "</tr>" ); 
                                $( this ).dialog( "close" );

                            }

                        },
                       Cancel: function() {
                           $( this ).dialog( "close" );
                       }
                   },



                    close: function() {
                       allFields.val( "" ).removeClass( "ui-state-error" );
                   }
                });

                $( "#create-user" )
               .button()
                .click(function() {
                    $( "#dialog-form" ).dialog( "open" );
               });
           });



        </script>
        <script>



            $(function() {
               // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $( "#dialog:ui-dialog" ).dialog( "destroy" );

               var name = $( "#name" ),
               email = $( "#email" ),
               password = $( "#password" ),
               allFields = $( [] ).add( name ).add( email ).add( password ),
               tips = $( ".validateTips" );
               function updateTips( t ) {
                   tips
                   .text( t )
                   .addClass( "ui-state-highlight" );
                   setTimeout(function() {
                       tips.removeClass( "ui-state-highlight", 1500 );
                   }, 500 );
                }

              function checkLength( o, n, min, max ) {
                  if ( o.val().length > max || o.val().length < min ) {

                       o.addClass( "ui-state-error" );
                       updateTips( "Length of " + n + " must be between " +
                           min + " and " + max + "." );
                       return false;
                   } else {

                       return true;
                   }
               }
                function checkRegexp( o, regexp, n ) {
                  if ( !( regexp.test( o.val() ) ) ) {

                       o.addClass( "ui-state-error" );
                       updateTips( n );
                       return false;
                   } else {
                       return true;
                   }
              }

              $( "#dialog-form1" ).dialog({
                  autoOpen: false,
                  height: 300,
                  width: 350,
                  modal: true,
                   buttons: {
                       "Forgot Password": function() {
                           var bValid = true;
                           allFields.removeClass( "ui-state-error" );

                            bValid = bValid && checkLength( email, "email", 6, 80 );

                            bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
                           // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
                           bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
                           bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );

                            if ( bValid ) {
                               $( "#users tbody" ).append( "<tr>" +
                                   "<td>" + name.val() + "</td>" + 
                                   "<td>" + email.val() + "</td>" + 
                                  "<td>" + password.val() + "</td>" +
                                   "</tr>" ); 

                                $( this ).dialog( "close" );

                            }
                        },
                        Cancel: function() {
                            $( this ).dialog( "close" );
                        }
                    },
                    close: function() {
                        allFields.val( "" ).removeClass( "ui-state-error" );
                    }
                });
                $( "#forgotpass" )
                .button()
                .click(function() {
                    $( "#dialog-form1" ).dialog( "open" );
			
                });
            });
        </script>

        <style type="text/css">
@import url(css/loginStyle.css);
</style>

    </head>

    <body>
        <div id="main">
            <div id="contentbg">
        <form id="login" method="post" action="login.php"> 
            <h1>Log in to your <strong>Abune Teklehymanot Church</strong> account!</h1>
            <p class="register">Not a member? <a href="#" id="create-user">Register here!</a></p>
            <?php if (isset($sErrorText) <> '') { ?>
		<span style="color:red;" id="PasswordError" align="center"><?php echo $sErrorText; ?></span>
		<?php } ?>
            <div>
                <label for="login_username">Username</label> 
                <input type="text" name="username" id="login_username" class="field required" title="Please provide your username" />
            </div>			
            <div>
                <label for="login_password">Password</label>
                <input type="password" name="password" id="login_password" class="field required" title="Password is required" />
            </div>			
            <p class="forgot"><a href="#" id="forgotpass">Forgot your password?</a></p>
            <div class="submit">
                <button type="submit">Log in</button>   
                <label>
                    <input type="checkbox" name="remember" id="login_remember" value="yes" />Remember my login on this computer

                </label>   
            </div>
        </form>	
        <div id="dialog-form" title="Create new user">
            <p class="validateTips">Please complete the following form and we will send you access information. <p/>
            All form fields are required.</p>
            <form>
                <fieldset>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
                    <label for="email">Email</label>

                    <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
        </div>
        <div id="dialog-form1" title="Forgote Password">
            <p class="validateTips">Please Enter Valid Email</p>
            <form>

                <fieldset>
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
                </fieldset>
            </form>
        </div>
            </div>
        </div>
    </body>
</html>
<?php
// Turn OFF output buffering
ob_end_flush();
?>

