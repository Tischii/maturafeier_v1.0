<?php
ini_set( "display_errors", 0);
session_start();
if(null==isset($_SESSION['userid'])) {
 header('Location: tryagain.html');

}
 
//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];
$benutzer = $_SESSION['benutzer'];

$nutzername;

$pdo = new PDO('mysql:host=e77773-mysql.services.easyname.eu;dbname=u120219db1', 'u120219db1', 'maturafeier');
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$abfrageName = $pdo->prepare("SELECT vorname, nachname FROM user WHERE benutzer = :benutzer");
$resultName = $abfrageName->execute(array('benutzer' => $benutzer));
while ($rowName = $abfrageName->fetch()) {
    $nutzername = $rowName['vorname'].' '.$rowName['nachname'];
}

if(isset($_GET['anmeldung'])) {
    $anzahl = $_POST['anzahl'];
    $sql = $pdo->prepare("INSERT INTO teilnehmer (id, anzahl) VALUES (:userid, :anzahl)");
    $sql->bindParam(':userid', $userid);
    $sql->bindParam(':anzahl', $anzahl);
    $sql->execute();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Maturafeier | Anmeldung </title>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/foundation.css">
        <link rel="stylesheet" href="icons/foundation-icons.css" />
        <link rel="stylesheet" href="css/app.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Bungee|Pacifico|Sedgwick+Ave+Display" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <style type="text/css">
        *{
        font-family: Roboto;
      }

	  a.button {
		-webkit-appearance: button;
		-moz-appearance: button;
		appearance: button;

		text-decoration: none;
		color: initial;
		}

      .hero-section{
        background-image: url("img/background.jpg");
        background-size: cover;
        display: flex;
        align-items: center;
        justify-content:center;
        background-position:center;
        height: 90vh;
        background-repeat: no-repeat;

      }

      body{
        background-color: #282828;
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

      .button{
        background-color: #3b8093;
        width: 50%;
        margin-top: 20px;
        font-size: 18pt;
        color: #fff;
        
      }

      .button:hover{
        background-color: #52CBD8;
      }

      .form{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 90vh;
        }

        .welcome{
            color: #fff;
            text-align: center;
     
        }

        .text{
          color: #fff;
          text-align: center;
          margin-bottom: 5vh;
        }

        label{
            color: #fff;
            font-size: 18pt;
        }

        #container{
        	display: flex;
        	justify-content: center;
        	align-items: center;
        	flex-direction: column;
        	height: 90vh;
        }

        .info{
        	color: #fff;
        	font-size: 24pt;
        }

        .hallo{
        	color: #fff;
        	font-size: 26pt;
          text-align: center;
        }



    
        </style>
     
    </head>
    <body>
        <div class="fixed">
          <div class="top-bar" data-topbar role="navigation">
            <div class="logo-wrapper">
              <div class="logo">
                <a href="index.html"><img class="logo_img" src="img/logo_white.png" ></a>
              </div>
            </div>

            <div class="title-bar" data-responsive-toggle="resmenu" data-hide-for="medium">
              <button class="menu-icon" type="button" data-toggle="resmenu"></button>
                <div class="title-bar-title">Men&uuml; </div>
            </div>

            <div class="top-bar-right" data-magellan  id="resmenu">
              <ul class="vertical medium-horizontal menu">
                <li><a href="#">WILLKOMMEN, <?php echo"$nutzername"?></a></li>
                <li><a href="logout.php">LOGOUT</a></li>
              </ul>
            </div>
          </div>
        </div>

       <div>
            <?php
                $statement = $pdo->prepare("SELECT * FROM teilnehmer WHERE id = :userid");
                $result = $statement->execute(array(':userid' => $userid));
                $anmeldung = $statement->fetch();
                if ($anmeldung == false) {
                    include("anmeldefeld.html");
                }else{
                    $statement = $pdo->prepare("SELECT anzahl FROM teilnehmer WHERE id = :userid");
                    $result = $statement->execute(array(':userid' => $userid));
                    while($row = $statement->fetch()) {
                    	if($info = 0){
                    		$info = "Es ist niemand angemeldet :(.<br>.Bist du dir sicher?";
                    	}
                    	if($info = 1){
                    		$info=$row['anzahl']." Teilnehmer nimmt an der Feier teil.";
                    	}

                      if($info>1){
                    		$info = $row['anzahl']." Teilnehmer nehmen an der Feier teil.";
                      }
                    	}
                    }

                
                if(isset($info)) {
                	echo '<div id="container">';
                	echo '<p class="hallo">Danke f&uuml;r die Anmeldung, '.$nutzername.'! <br>Wir freuen uns auf Sie!</p>';
                  echo '<p class="info">'.$info.'<br></p>';
                  echo '<p class="info">Zahlungsinformationen finden Sie in der Best&auml;tigung!</p>';
					echo '<a href="./pdf/rechnung.php" class="button">Best&auml;tigung</a>';
					echo '</div>';
                }
            ?>
       </div>


        <script src="js/vendor/jquery.js"></script>
        <script src="js/vendor/what-input.js"></script>
        <script src="js/vendor/foundation.js"></script>
        <script src="js/app.js"></script>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    </body>
</html>