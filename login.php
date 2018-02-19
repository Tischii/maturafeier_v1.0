<?php
ini_set( "display_errors", 0);
$pdo = new PDO('mysql:host=e77773-mysql.services.easyname.eu;dbname=u120219db1', 'u120219db1', 'maturafeier');
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
if(isset($_GET['login'])) {
 $benutzer = $_POST['benutzer'];
 $passwort = $_POST['passwort'];
 $statement = $pdo->prepare("SELECT * FROM user WHERE benutzer = :benutzer");
 $result = $statement->execute(array('benutzer' => $benutzer));
 $user = $statement->fetch();
 //Überprüfung des Passworts
 if ($user !== false && password_verify($passwort, $user['passwort'])) {
 session_start();
 $_SESSION['userid'] = $user['id'];
 $_SESSION['benutzer'] = $user['benutzer'];
 header('Location: anmeldung.php');
 } else {
	 if ($passwort != null && password_verify($passwort, $user['passwort']) == false){
         $errorMessage = "Passwort ist ungültig";
     }
     elseif($benutzer != null) {
         $errorMessage = "Benutzer ist ungültig";
     }
     
     elseif ($passwort == null || $benutzer == null){
         $errorMessage = "Felder müssen ausgefüllt werden";
     }
 }
}
?>
<!DOCTYPE HTML>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Maturafeier | Login </title>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="css/foundation.css">
      <link rel="stylesheet" href="icons/foundation-icons.css" />
      <link rel="stylesheet" href="css/app.css">
      <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Bungee|Pacifico|Sedgwick+Ave+Display" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Archivo+Black" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

      <style type="text/css">
         *{
            font-family: Roboto;
            color: #282828;
        }
              .top-bar-right{
                    margin-right: 50px;
                }

                .top-bar ul li a{
                    font-size: 16pt;
                    letter-spacing: 4px;
                    color: #fff;

                }

                .top-bar, .top-bar ul li a{
                    background-color: #282828;
                }

                .top-bar ul li a:hover{
                    color: #52CBD8;
                }



                .vertical medium-horizontal menu{
                    background-color: #282828;
                }

                .logo{
                    margin-left: 50px;
                    display: flex;
                    align-items: center;
                }

                .logo_img{
                    height: 8vh;
                }

                .title-bar{
                    background-color: #282828;
                }

                .fixed{
                    height: 10vh;
                    background-color: #282828;
                }

                .form{
                  position: relative;
                  margin-left:auto;
                  margin-right: auto;
                  margin-top: 5vh;
                  z-index: 1;
                  background: #fff;
                  width: 350px;
                  padding: 30px;
                  border-top-left-radius: 3px;
                  border-top-right-radius: 3px;
                  border-bottom-left-radius: 3px;
                  border-bottom-right-radius: 3px;
                  text-align: center;
                }

                .form .thumbnail{
                  background: #3b8093;
                  width: 160px;
                  height: 160px;
                  margin:0 auto 30px;
                  border-top-left-radius: 100%;
                  border-top-right-radius: 100%;
                  border-bottom-left-radius: 100%;
                  border-bottom-right-radius: 100%;
                  box-sizing: border-box;
                }

                .form .thumbnail img{
                  display: block;
                  width: 100%;
                  margin-top: 30px;

                }

                .form input{
                  outline:0;
                  width: 100%;
                  border:0;
                  margin: 0 0 15px;
                  
                  border-top-left-radius: 3px;
                  border-top-right-radius: 3px;
                  border-bottom-left-radius: 3px;
                  border-bottom-right-radius: 3px;
                  box-sizing: border-box;
                  font-size: 14px;
                }

                .button{
                  background-color: #3b8093;
                  color: #fff;
                }

                .button:hover{
                  background-color: #52CBD8;
                }
                
                .form .message {
                  margin: 15px 0 0;
                  color: #b3b3b3;
                  font-size: 12px;
                }
                .form .message a {
                  color: #EF3B3A;
                  text-decoration: none;
                }
                .form .register-form {
                  display: none;
                }

                body {
                background-color: #282828;
                background-repeat: no-repeat;
                background-position: center center;
                background-attachment: fixed;
                background-size: cover;
                font-family: "Roboto", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
              }
              body:before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                display: block;
                background: rgba(255, 255, 255, 0);
                width: 100%;
                height: 100%;
              }

              .ueberschrift{
                color: #fff;
                text-align: center;
                font-size: 3vw;
                font-family: 'Archivo Black', sans-serif;
              }

              .textt{
                color: #fff;
                text-align: center;
                  font-size:2vw;
              }

              .text{
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                height: 80vh;
              }



      </style>
  </head>

  <body>

    <div class="fixed">
      <div class="top-bar" data-topbar role="navigation">
        <div class="logo-wrapper">
          <div class="logo">
            <a href="index.html"><img class="logo_img" src="img/logo_white.png"></a>
          </div>
        </div>

        <div class="title-bar" data-responsive-toggle="resmenu" data-hide-for="medium">
          <button class="menu-icon" type="button" data-toggle="resmenu"></button>
            <div class="title-bar-title">Men&uuml; </div>
        </div>

        <div class="top-bar-right" data-magellan  id="resmenu">
          <ul class="vertical medium-horizontal menu">
            <li><a href="info.html">INFO</a></li>
            <li><a href="comingsoon.html">FOTOS</a></li>
            <li><a href="login.php">LOGIN</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="text">
      <h1 class="ueberschrift">Login</h1>
      <h2 class="textt">Melden Sie sich hier an!<br> Die Anmeldedaten finden Sie auf Ihrer Einladung!</h2>
    

    <div class="form">
      <div class="thumbnail">
        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/169963/hat.svg"/>
      </div>
      <form class="login-form" action="?login=1" method="post">

        <div class="row">
          <div class="large-12 columns">
           
            <input type="text" placeholder="Username" name="benutzer"/>
          </div>
        </div>

        <div class="row">
          <div class="large-12 columns">
            
            <input type="password" placeholder="Password" name="passwort"/>
          <div>
        </div>

        <div class="error">
          <?php
            if(isset($errorMessage)){
              echo '<span>'.$errorMessage.'<br></span>';
            }
          ?>
        </div>

        <input class="button" type="submit" value="Abschicken">
      </form>
    </div>
  </div>



    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  </body>
</html>