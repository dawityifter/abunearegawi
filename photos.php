<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <!-- load Galleria -->
        <script src="galleria/galleria-1.2.8.min.js"></script>
        <script>
            var data = [
                {
                    image: 'images/aba-1.jpg',
                    thumb: 'images/aba-1-Thumb.jpg',
                    big: 'images/aba-1.jpg',
                    title: 'Our Priest',
                    description: 'Kesis Tadesse',
                    link: 'http://domain.com'
                },
                {
                    image: 'images/aba-2.jpg',
                    thumb: 'images/aba-2-Thumb.jpg',
                    big: 'images/aba-2.jpg',
                    title: 'my first image',
                    description: 'Aba asdfsd asdfasdfa dfasdfasdfasdf',
                    link: 'http://domain.com'
                },
                {
                    image: 'images/aba-3.jpg',
                    thumb: 'images/aba-3-Thumb.jpg',
                    big: 'images/aba-3.jpg',
                    title: 'my first image',
                    description: 'Aba asdfsd asdfasdfa dfasdfasdfasdf',
                    link: 'http://domain.com'
                },
                {
                    image: 'images/aba-4.jpg',
                    thumb: 'images/aba-4-Thumb.jpg',
                    big: 'images/aba-4.jpg',
                    title: 'my first image',
                    description: 'Aba asdfsd asdfasdfa dfasdfasdfasdf',
                    link: 'http://domain.com'
                },
                {
                    image: 'images/aba-5.jpg',
                    thumb: 'images/aba-5-Thumb.jpg',
                    big: 'images/aba-5.jpg',
                    title: 'my first image',
                    description: 'Aba asdfsd asdfasdfa dfasdfasdfasdf',
                    link: 'http://domain.com'
                },
                {
                    image: 'images/aba-6.jpg',
                    thumb: 'images/aba-6-Thumb.jpg',
                    big: 'images/aba-6.jpg',
                    title: 'my first image',
                    description: 'Aba asdfsd asdfasdfa dfasdfasdfasdf',
                    link: 'http://domain.com'
                },
                {
        video: 'http://www.youtube.com/watch?v=agm-8_Zohe8',
        title: 'Our Church',
        description: 'St. Tekle Haimanot & Abune Aregawi EOTC - Dallas, Tx (April - 2011/ 03) '
    },
                {
        video: 'http://www.youtube.com/watch?v=oVfTdWZPor8',
        title: 'ጥምቀት (2011)',
        description: 'ጥምቀት (2011)'
    },
    {
        video: 'http://www.youtube.com/watch?v=iZUUVuO8f5g',
        title: 'Egziabhern Amesgenu',
        description: 'Egziabhern Amesgenu'
    },
    {
        video: 'http://www.youtube.com/watch?v=v_J0gIrsxcI',
        title: 'ኣቡነ ተክለ ሃይማኖት ጻድቕ',
        description: 'ኣቡነ ተክለ ሃይማኖት ጻድቕ'
    },
    {
        video: 'http://www.youtube.com/watch?v=t3tw76_7Nuk',
        title: 'New Church Building',
        description: 'New Church Building'
    },
    {
        video: 'http://www.youtube.com/watch?v=NP4s-7IKFfI',
        title: 'መስከረም ፳፻፬ ጉባኤ',
        description: 'መስከረም ፳፻፬ ጉባኤ'
    },
    {
        video: 'http://www.youtube.com/watch?v=SXRs_4uI-vo',
        title: 'የስቅለት በዓል (I)2011',
        description: 'የስቅለት በዓል (I)2011'
    },
    {
        video: 'http://www.youtube.com/watch?v=bgw-DkTm_-U',
        title: 'የስቅለት በዓል (II)2011',
        description: 'የስቅለት በዓል (II)2011'
    }
                
            ];
        </script>
        <style>

            /* Demo styles */
            html,body{background:#222;margin:0;}
            body{border-top:4px solid #000;}
            .content{color:#777;font:12px/1.4 "helvetica neue",arial,sans-serif;width:620px;margin:20px auto;}
            h1{font-size:12px;font-weight:normal;color:#ddd;margin:0;}
            p{margin:0 0 20px}
            a {color:#22BCB9;text-decoration:none;}
            .cred{margin-top:20px;font-size:11px;}

            /* This rule is read by Galleria to define the gallery height: */
            #gallery{height:420px}

        </style>
        <link type="text/css" rel="stylesheet" href="style.css" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Photos  and Videos</title>
        <link rel="icon" href="images/icon.ico" />  
    </head>

    <body class="basic">
        <div id="main">
            <div id="contentbg">
                <div id="contenttxtblank">
                    <div id="menu">
                          <?php include 'menu.php'; ?>
                            </div>
                            <div id="contentleft">
                                <div id="topbuttonsblank">
                                    <div id="register"><a href="#" class="register">ይመዝገቡ</a></div>
                                    <div id="bookmark"><a href="#" class="bookmark">Bookmark</a></div>
                                </div>
                                <div id="callus">
                                    <h3>Call Us<span class="callus">214-333-6666</span></h3>
                                </div>
                                <div id="leftheading">
                                    <h2>Our Church</h2>
                                </div>
                                <div id="lefttxtblank">
                                    <div id="centered" >
                                        <div id="gallery">
                                            
                                           <script>
                                                Galleria.loadTheme('galleria/themes/classic/galleria.classic.min.js');
                                                Galleria.run('#gallery', {
                                                    dataSource: data,
                                                    imageCrop: true //,
                                                    //autoplay: 7000 // will move forward every 7 seconds
                                                });
                                            </script>


                                        </div>


                                    </div>
                                </div>

                                
                            </div>
                        </div>
                      
                    </div>
             <?php include 'footer.php'; ?>
            </div>
            </body>
        </html>
