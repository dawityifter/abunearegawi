<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Abune Teklehymanot we Abune Aregawi EOTC</title>
        <style type="text/css">
@import url(style.css);
@import url(css/loginJqueryStyle.css);
</style>
       <link rel="icon" href="images/icon.ico" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
        <script type="text/javascript" src="js/loginJquery.js"/>
        <script type="text/javascript" src="js/font.js"/>
        
        
        <script type="text/javascript">
    
            $(document).ready(function(){ 
                var fontname = 'EthioJiret';
                 var detective = new Detector();
                 alert("Is EthioJiret installed :" +detective.detect(fontname));
               // font.setup(); // run setup when the DOM is ready
                // chrome does not permit addToFavorites() function by design 
                if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) { 
                    $('.addbookmark').attr({ 
                        title: 'This function is not available in Google Chrome. Click the star symbol at the end of the address-bar or hit Ctrl-D to create a bookmark.', 
                        href: 'javascript:return false' 
                    }) 
                    .css({opacity: .25});       // dim the button/link 
                    setTimeout(function(){
                        alert("Checking fonts...");
                        if (font.isInstalled(fontname)){
                            alert("true");
                        }else{
                            alert("false");
                        }
                    },100);
            
                } 
            }); 
   
            function addBookmark()
            {
                var bookmark_url= document.URL;
                var text_description="Abune Teklehymanot we Abune Aregawi EOTC";
 
                title='Abune Teklehymanot we Abune Aregawi EOTC, Garland, Texas'; // for example, not really generated this way... 

   
                addToFavorites(bookmark_url,text_description)
            }
               

        </script>
    </head>

    <body>
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
                            <div id="lefttxt"><span class="leftboldtxt">You may not seem to have the necessary Ethiopic fonts installed in your machine.</span>  In order to view Ethiopic fonts, you only need a unicode Ethiopic font in your computer's font folder.
                                In Windows systems, this folder is normally  <span class="lefttxt02">C:\Windows\Fonts. </span>On Macintosh computers, you need only drag and drop the font onto the closed System folder. (The system will automatically sort it into the correct subfolder.)<span class="lefttxt">Once the font is installed, </span> you may need to restart your web browser in order to make it recognize the newly installed fonts.
</div>
                            <div id="buttonbg"><a href="downloadfonts.php" class="more">readmore</a></div>
                        </div>
                    </div>
                    <div id="contentright">
                        <div id="search">
                            <div id="searchblank">
                                <div id="searchinput">
                                    <label>
                                        <input name="textfield" type="text" class="searchinput" id="textfield" value="Enter Keyword" />
                                    </label>
                                </div>
                                <div id="searchbutton"><a href="#" class="search">Search</a></div>
                                <div id="advancesearch"><a href="#" class="advancesearch">Advance Search</a></div>
                            </div>
                        </div>
                        <div id="rightnav">
                           <ul>
                                
                        <li><a href="churchinfo/login.php" class="login"></a></li>
                                <li><a href="#" class="check"></a></li>
                                <li><a href="#" class="chat"></a></li>
                                <li><a href="#" class="idea"></a></li>
                                <li><a href="#" class="privecy"></a></li>
                            </ul>
                        </div>
                        <div id="morelinksheading">
                            <h5>More Newslinks</h5>
                        </div>
                        <div id="morelinks">
                            <div class="date">08.11.12</div>
                            <div class="linkblank"><a href="#" class="link">R.I.P Abune Paulos</a></div>
                            <div class="date">08.07.12</div>
                            <div class="linkblank02"><a href="#" class="link">Praesent tincidunt mattis ipsum cras </a></div>
                            <div class="linkblank03"><a href="#" class="link">Varius tempor dolor In sit amet lectu</a></div>
                            <div class="linkblank03"><a href="#" class="link">Lorem ipsum dolo</a></div>
                            <div class="linkblank04"><a href="#" class="link">Consectetuer adipiscing elitverdiet</a></div>
                            <div class="date">08.01.12</div>
                            <div class="linkblank02"><a href="#" class="link">Praesent tincidunt mattis ipsum cras</a></div>
                            <div class="linkblank04"><a href="#" class="link">Varius tempor dol</a></div>
                            <div class="date">07.16.12</div>
                            <div class="linkblank02"><a href="#" class="link">Hendrerit fringilla urna donec a felis</a></div>
                        </div>
                        <div id="morelinksbot"></div>
                    </div>
                </div>
            </div>
            <div id="footerbg">
                <div id="footerlinks"><a href="#" class="footerlinks">Home</a> | <a href="#" class="footerlinks">About Us</a> | <a href="#" class="footerlinks">Solutions</a> | <a href="#" class="footerlinks">Services</a> |<a href="#" class="footerlinks"> Blog</a> | <a href="#" class="footerlinks">Our Client</a> | <a href="#" class="footerlinks">Sponsors Link</a> | <a href="#" class="footerlinks">Testimonials</a> | <a href="#" class="footerlinks">Contact Us</a></div>
                <div id="copyrights">© Copyright Information Goes Here. All Rights Reserved.</div>

            </div>
        </div>
    </body>
</html>
