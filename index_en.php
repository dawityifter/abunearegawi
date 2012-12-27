<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
         <meta property="og:title" content="Abune Aregawi we Abune Teklehymanot Ethiopian Orthodox Church" />
<meta property="og:type" content="non_profit" />
<meta property="og:url" content="http://www.debretsehay.org" />
<meta property="og:image" content="" />
<meta property="og:site_name" content="Debretsehay EOTC" />
<meta property="fb:admins" content="100004903656154" />
        <title>Abune Teklehymanot we Abune Aregawi EOTC</title>
        <style type="text/css">
            @import url(style.css);
        </style>
          <link rel="stylesheet" type="text/css" href="content/contentStyles.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="loginX/login_panel/css/slide.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="feedback/styles.css" />
    
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="loginX/login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
   
        <link rel="icon" href="images/icon.ico" />
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
 <?php echo $slidePanelScript; ?> 
    </head>

    <body>
    	<div id="slideToppanel">
	<div id="slidePanel">
		<div class="content clearfix">
			
            
            
            <?php
			
			if(!$_SESSION['id']):
			
			?>
                    <div class="left">
				<h1>Welcome to DebreTsehay EOTC</h1>
				<h2>Please login/register </h2>		
				<p class="grey">If have not registered already, Please do so now and We will send your credential via email</p>
				<h2>Thank You</h2>
				<!--<p class="grey">This tutorial was built on top of 
                                    <a href="http://web-kreation.com/index.php/tutorials/nice-clean-sliding-login-panel-built-with-jquery" 
                                       title="Go to site">Web-Kreation</a>'s amazing sliding panel.</p>  -->
			</div>
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
                    		
					<label class="grey" for="firstName">First Name:</label>
					<input class="field" type="text" name="firstName" id="firstName" value="" size="23" />
					<label class="grey" for="lastName">Last Name:</label>
					<input class="field" type="text" name="lastName" id="lastName" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
                                        <label><input name="isMember" id="isMember" type="checkbox" checked="checked" value="0" /> &nbsp;Member of our church?</label>
                                        <label>Your login credentials will be e-mailed to you shortly.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
                    <div class="left">
				<h1>Welcome to DebreTsehay EOTC</h1>
				<h2>ChurchInfo Software</h2>		
				<p class="grey">ChurchInfo is a free church database program to help churches track members, families, groups, pledges and payments.</p>
				<h2>A Special Thanks</h2>
				<p class="grey">This website was built on top of 
                                    <a href="http://www.churchdb.org/" 
                                       title="Go to site">ChurchInfo</a> visit the site for tutorials and feature list.</p> 
			</div>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>This is member-only content</p>
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
    	<div id="abuneAregawi"></div>
<div id="abuneTekleHaimanot"></div>
        <div id="main">
            <div id="contentbg">
                <div id="contenttxtblank">
                    <div id="menu">
                        <?php include 'menu_en.php'; ?>

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
                            <h3>Welcome to DebreTsehay EOTC</h3>
                        </div>
                        <div id="lefttxtblank">
                            <div id="lefttxt"><span class="leftboldtxt">You may not seem to have the necessary Ethiopic fonts installed in your machine.</span>  In order to view Ethiopic fonts, you only need a unicode Ethiopic font in your computer's font folder.
                                In Windows systems, this folder is normally  <span class="lefttxt02">C:\Windows\Fonts. </span>On Macintosh computers, you need only drag and drop the font onto the closed System folder. (The system will automatically sort it into the correct subfolder.)<span class="lefttxt">Once the font is installed, </span> you may need to restart your web browser in order to make it recognize the newly installed fonts.
                            </div>
                            <div id="buttonbg"><a href="downloadfonts.php" class="more">readmore</a></div>
                        </div>
                    </div>
                    <?php include 'rightSideBar.php'; ?>
                </div>
            </div>
           <?php include 'footer_en.php'; ?>
        </div>
        <div id="feedback">

	<!-- Five color spans, floated to the left of each other -->

	<span class="color color-1"></span>
    <span class="color color-2"></span>
    <span class="color color-3"></span>
    <span class="color color-4"></span>
    <span class="color color-5"></span>
    
    <div class="feedbackSection">
    
    	<!-- The arrow span is floated to the right -->
        <h6><span class="arrow up"></span>Feedback</h6>
        
        <p class="message">Your feedback is always appreciated. Please include your contact information if you'd like to receive a reply.</p>
        
        <textarea></textarea>
        
        <a class="submit" href="">Submit</a>
    </div>
</div>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<script src="loginX/login_panel/js/slide.js" type="text/javascript"></script>
<script type="text/javascript" src="js/font.js"></script>
<script src="feedback/script.js"></script>
<script src="content/script.js"></script>

    </body>
</html>
