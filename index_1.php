<?php
define('INCLUDE_CHECK',true);

require 'loginX/connect.php';
require 'loginX/functions.php';
// Those two files can be included only if INCLUDE_CHECK is defined


session_name('tzLogin');
// Starting the session

session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();

if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the tzRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}


if(isset($_GET['logoff']))
{
	$_SESSION = array();
	session_destroy();
	
	header("Location: index.php");
	exit;
}

if($_POST['submit']=='Login')
{
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		$_POST['password'] = mysql_real_escape_string($_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		
		// Escaping all input data

		$row = mysql_fetch_assoc(mysql_query("SELECT id,usr FROM tz_members WHERE usr='{$_POST['username']}' AND pass='".md5($_POST['password'])."'"));

		if($row['usr'])
		{
			// If everything is OK login
			
			$_SESSION['usr']=$row['usr'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			
			// Store some data in the session
			
			setcookie('tzRemember',$_POST['rememberMe']);
		}
		else $err[]='Wrong username and/or password!';
	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header("Location: index.php");
	exit;
}
else if($_POST['submit']=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>16)
	{
		$err[]='Your username must be between 3 and 16 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}
	
	if(!checkEmail($_POST['email']))
	{
		$err[]='Your email is not valid!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		$pass = substr(md5($_SERVER['REMOTE_ADDR'].microtime().rand(1,100000)),0,6);
		// Generate a random password
		
		$_POST['email'] = mysql_real_escape_string($_POST['email']);
		$_POST['username'] = mysql_real_escape_string($_POST['username']);
		// Escape the input data
		
		
		mysql_query("	INSERT INTO tz_members(usr,pass,email,regIP,dt)
						VALUES(
						
							'".$_POST['username']."',
							'".md5($pass)."',
							'".$_POST['email']."',
							'".$_SERVER['REMOTE_ADDR']."',
							NOW()
							
						)");
		
		if(mysql_affected_rows($link)==1)
		{
			send_mail(	'dawityifter@gmail.com',
						$_POST['email'],
						'Registration to Debretsehay.org - Your New Password',
						'Your temporary password is: '.$pass);

			$_SESSION['msg']['reg-success']='We sent you an email with your temporary password!';
		}
		else $err[]='This username is already taken!';
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: /index.php");
	exit;
}

$script = '';

if($_SESSION['msg'])
{
	// The script below shows the sliding panel on page load
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
		});
	
	</script>';
	
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>አቡነ ተክለ ሐይማኖት ወ አቡነ ወልደ አረጋዊ ቤ/ክርስቲያን</title>
        <link rel="stylesheet" type="text/css" href="loginX/demo.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="loginX/login_panel/css/slide.css" media="screen" />
    
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
 
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="loginX/login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <script src="loginX/login_panel/js/slide.js" type="text/javascript"></script>
    
   
        <style type="text/css">
            @import url(style.css);
        </style>
        <link rel="icon" href="images/icon.ico" />
        <script type="text/javascript" src="js/font.js"></script>
<script type="text/javascript">
    var os = (function() {
    var ua = navigator.userAgent.toLowerCase();
    return {
        isWin2K: /windows nt 5.0/.test(ua),
        isXP: /windows nt 5.1/.test(ua),
        isVista: /windows nt 6.0/.test(ua),
        isWin7: /windows nt 6.1/.test(ua)
    };
}());


            
window.onload = function() {
    var found = false;
    //if win7 or vista do assume the amharic font exists.
    if(os.isWin7 || os.isVista) {
        found = true;
      return;
      
    }
    var knownEthiopicFonts=new Array("Ethiopia Jiret","Ethiopic WashRa Bold","Ethiopic Wookianos",
"Ethiopic Yebse", "Visual Geez Unicode");  //For some reason, this always return true "Ge'ez 1",
   var detective = new Detector();
    var ethiopicSize = knownEthiopicFonts.length;
    
    for(var i=0; i < ethiopicSize; i++){
     if(detective.detect(knownEthiopicFonts[i])){
         found = true;
         break;
     }   
    }
    if(!found){
        window.location ="index_en.php";
    }
};
        </script>
         <?php echo $script; ?>
    </head>

    <body>
        <!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Welcome to DebreTsehay EOTC</h1>
				<h2>Please login/register </h2>		
				<p class="grey">If have not registered already, Please do so now and We will send your credential via email</p>
				<h2>Thank You</h2>
				<!--<p class="grey">This tutorial was built on top of 
                                    <a href="http://web-kreation.com/index.php/tutorials/nice-clean-sliding-login-panel-built-with-jquery" 
                                       title="Go to site">Web-Kreation</a>'s amazing sliding panel.</p>  -->
			</div>
            
            
            <?php
			
			if(!$_SESSION['id']):
			
			?>
            
			<div class="left">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if($_SESSION['msg']['login-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<form action="" method="post">
					<h1>Not a member yet? Sign Up!</h1>		
                    
                    <?php
						
						if($_SESSION['msg']['reg-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if($_SESSION['msg']['reg-success'])
						{
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                    		
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
					<label>A password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>You can put member-only data here</p>
            <a href="churchinfo/Menu.php">View a special member page</a>
            <p>- or -</p>
            <a href="/?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $_SESSION['id']?'Open Panel':'Log In | Register';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->

        <div id="main">
            <div id="contentbg">
                <div id="contenttxtblank">
                    <div id="menu">
                        <?php include 'menu.php'; ?>

                    </div>
                    <div id="contentleft">
                        <div id="topbuttonsblank">
                            <!--    <div id="register"><a href="#" class="register" onclick="javascript:testFont1();return false">ይመዝገቡ</a></div>
                                <div id="bookmark"><a href="#" class="bookmark" onclick="addBookmark()">Bookmark</a></div>
                            -->
                        </div>
                        <div id="callus">
                            <h3>Call Us<span class="callus">(214)-555-2424</span></h3>
                        </div>
                        <div id="leftheading">
                            <h2>ቤተ ክርስቲያናችን</h2>
                        </div>
                        <div id="lefttxtblank">
                            <div id="lefttxt"><span class="leftboldtxt">
                                    ወላጆች ለልጆቻቸው ሊያደርጉላቸው ስለ ሚገባቸው የሚናገረው አንቀጽ
  </span>
                                <div class="code_pre">

<span class="lefttxt02">መጽሐፈ ዲዲስቅልያ አንቀጽ ፳፪ </span>
“ንሕነ ሐዋርያት ተጋቢዓነ ውስተ ኢየሩሳሌም አንበርነ ዘንተ ትምህርተ”
“እኛ ሐዋርያት በኢየሩሳሌም ተሰብስበን ይህን ትምህርት ጽፈን አኖርን” (ዲዲስቅልያ አንቀጽ ፩)
በመላእክት አምሳል የቤተ ክርስቲያንን ሥርዓት ሁሉ በያገባቡ ሁሉ ጽፈን አኖርን። ግብ ሐዋ ፲፮፡፬። 
ቤተ ክርስቲያን ደግሞ እንደዚሁ ትኑር በዚሁ ትተዳደር። ሁሉ እያንዳንዱ ከእግዚአብሔር በተሰጠው ጸጋ 
(መዓረግ) ጸንቶ ይኑር። ፩ቆሮ ፯፡፯። 
<span class="lefttxt02">
ፍትህ መንፈሳዊ (ፍትሐ ነገሥት) አንቀጽ ፲፩ ገጽ ፻፹፱
</span>
“ኦ አበው መሀርዎሙ ለውሉድክሙ ቃለ እግዚአብሔር”  አባቶች በመጀመርያ ልጆቻችሁን የእግዚአብሔርን 
ቃል አስተምሩዋቸው። ልጆቻችሁን አስቀድማችሁ መዝሙረ ዳዊት ፣መጻሕፍተ ሰሎሞን ፣ መጻሐፈ ሲራክ 
አስተምሯቸው። በተግሣጽ ፣ በምክር ሃይማኖታቸውን በማሳወቅ አሳድጓቸው። ከዚህ በኋላ ሊማሩት የሚገባ 
ሥጋዊ ጥበብንም አስተምሯቸው ሥራ ፈት ሆነው እንዳይኖሩ እናት አባታቸውን አንረዳም እንዳይሉ። 
ሃይማኖት ፣ ምግባር ፣ ግብረ ገብነት ሳታሰተምሩ ብታሳድጓቸው ባትገሥጹዋቸው ለአካለ መጠን ሲደርሱ 
ሰውንና  እግዚአብሔርን የሚያሳዝን ክፋ ሥራ ይሠራሉ። 
ስለዚህ ልጆቻችሁን ከመገሠጽ ከመቅጣት ሃይማኖትን ከማስተማር አትፍሩ ፣ 
ቸል አትበሉ። ብታስተምሯቸው ብትመክሯቸው ታድኑዋቸዋላችሁ እንጂ አትገድሉዋቸውምና።
በከመ ይቤ ሰሎሞን በጥበቡ “ገሥጾ ለወልድከ ከመ ያዕርፍከ ወይርባሕከ”
ሰሎሞን በጥበቡ “ይረባህ ፣ ይጠቅምህ ፣ ከረሃብ ፣ 
ከጥም ያሳርፍህ ዘንድ ልጅህን ምከረው አስተምረው”ብሎ እንደተናገረ።
 (ምሳሌ  ፳፱ ፡ ፲፯።/ ፳፫ ፡፲፫-፲፭። /፲፱ ፡ ፲፰።) (ሲራክ ፴ ፡፲፪-፲፬)።
  
                            <div id="buttonbg"><a href="docs/lejoch.htm" class="more">ተጨማሪ</a></div>
                        </div>
                    </div>
                    </div>
                   </div> 
                    <?php  include 'rightSideBar.php'; ?>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </body>
</html>
<?php
// Turn OFF output buffering
ob_end_flush();
?>