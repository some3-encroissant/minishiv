<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="main_style.css" />
        <title>BASILIC Project - Main page</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 



    </head>

    
    <body onload='refresh_div();'>
        <div id="bloc_page">
            
            <header>
                <div id="titre_principal">
                    <div id="logoHelha">
                        <img src="image/Logo_Helha.png" alt="Logo Helha" />
                         
                      </div>
                      <h1>B.A.S.I.L.I.C Project - Automated greenhouse </h1>
                        
                    <div id="logoIsicht">
                     <img src="image/Logo_Isicht.png" alt="Logo Isicht" >  
                    </div>
                </div>
                
                <nav>
                    <ul>
                        <li><a href="main.php">Main</a></li>
                      
                        <li><a href="commands.php">Commands and parameters</a></li>
                        
                        <li><a href="AboutUs.html">About us</a></li>
                    </ul>
                </nav>
                <?php
                  try
                  {
                    //$bdd = new PDO('mysql:host=192.168.1.12;dbname=db_basilic;charset=utf8','root','root');
                    $bdd = new PDO('mysql:host=localhost;dbname=db_basilic;charset=utf8','root','root');
                  }
                  catch (Exception $e)
                  {
                      die('Erreur : ' . $e->getMessage());
                  }
                  ?>
            </header>

            


            <section>
                <div id="Mesures">
<!-- Affichage des mesures  -->
                   <!--<?php
                    $reponse = $bdd->query('SELECT * from commandes');
      
                    while ($donnees = $reponse->fetch())
                      {
                        echo $donnees['Equipement'].'<br />';
                      }
                    $reponse->closeCursor(); // Termine le traitement de la requête 
                   ?> -->

                  <h1><img src="image/tn_icone_mesures.png" alt="Measurements" class="ico_categorie" />Measurements</h1>

                  <?php
                    $reponse = $bdd->query('SELECT Date from mesures where IdMesure= (select max( IdMesure) from mesures where IdType= 3)');
                     
                    while ($donnees = $reponse->fetch())
                    {
                      $LastUpdate=$donnees['Date'];
                    }
                    $reponse->closeCursor(); // Termine le traitement de la requête
                  ?>

                  <p>(Last update : <?php echo $LastUpdate?>) </p>                  
                
                  <?php

                    $req = $bdd->prepare('SELECT Valeur from mesures where IdMesure= (select max( IdMesure) from mesures where IdType= :type)');
                  ?>

                  <p>External temperature : <span class = "valeur_ext_temp"> <strong> <?php echo GetMeasure('1',$req); ?> </strong></span> °C
                  </p>

                  <p>Internal temperature : <span class = "valeur_int_temp"> <strong><?php echo GetMeasure('2',$req); ?></strong></span> °C
                  </p>
 
                  <p>Soil moisture level : <span class = "valeur_soil_moist"> <strong><?php echo GetMeasure('4',$req); ?></strong></span> %
                  </p>

                  <p>Luminosity : <span class = "valeur_lum"> <strong><?php echo GetMeasure('3',$req); ?></strong></span> lumen
                  </p>
<!-- Affichage des nombres de démarrages  -->
                  <?php

                    $req = $bdd->prepare('SELECT NombreDem from commandes where Equipement= :device');

                  ?>

                  <p>Number of pump start : <span class ="counter"><strong><?php echo GetStartCount('Pompe',$req); ?> </strong></span> start(s) <!-- voir pour faire une fonction pour le s -->
                  <?php $device='Pompe' ?>
                  </p>

                  <p>Number of fan start : <span class ="counter"><strong><?php echo GetStartCount('Ventilateur',$req); ?></strong></span> start(s) <!-- voir pour faire une fonction pour le s -->
                  </p>

                  <p>Number of lamp start : <span class ="counter"><strong><?php echo GetStartCount('Lampe_infrarouge',$req); ?></strong></span> start(s) <!-- voir pour faire une fonction pour le s -->
                  </p>
                   
                  <p>Number of valve opening : <span class ="counter"><strong><?php echo GetStartCount('Vanne',$req); ?></strong></span> opening(s) <!-- voir pour faire une fonction pour le s -->
                  </p>

                  <p>Number of window opening : <span class ="counter"><strong><?php echo GetStartCount('Servomoteur',$req); ?></strong></span> opening(s) <!-- voir pour faire une fonction pour le s -->
                  </p>
                </div>
            
              
                <div id="Etat">
<!-- Affichage des états  -->    
                    <h1><img src="image/tn_icone_etats.png" alt="States" class="ico_categorie" />Devices state</h1>

                    <?php
                    $req = $bdd->prepare('SELECT Etat,Mode from commandes where Equipement= :device');
                    ?>

                    <?php $PumpMode=GetDevMode('Pompe',$req); ?>
                    <p>Mode of the pump : <span class = "mode"> <strong><?php echo $PumpMode; ?></strong></span>
                    </p>


                    <p>State of the pump : <span class = "dev_sate"> <strong><?php echo GetDevState('Pompe',$req); ?></strong></span>
                    </p>

                    <?php $FanMode=GetDevMode('Ventilateur',$req); ?>
                    <p>Mode of the fan : <span class = "mode"> <strong><?php echo $FanMode; ?></strong></span>
                    </p>

                    <p>State of the fan : <span class ="dev_sate"> <strong><?php echo GetDevState('Ventilateur',$req); ?></strong></span>
                    </p>

                    <?php $LampMode=GetDevMode('Lampe_infrarouge',$req); ?>
                    <p>Mode of the lamp : <span class = "mode"> <strong><?php echo $LampMode; ?></strong></span>
                    </p>

                    <p>State of the lamp : <span class = "dev_sate"> <strong><?php echo GetDevState('Lampe_infrarouge',$req); ?></strong></span> 
                    </p>

                    <?php $WindowMode=GetDevMode('Servomoteur',$req); ?>
                    <p>Mode of the window : <span class = "mode"> <strong><?php echo $WindowMode; ?></strong></span>
                    </p>

                    <p>State of the window : <span class ="dev_sate"><strong><?php echo GetDevState('Servomoteur',$req); ?></strong></span>
                    </p>

                    <?php $ValveMode=GetDevMode('Vanne',$req); ?>
                    <p>Mode of the valve : <span class = "mode"> <strong><?php echo $ValveMode; ?></strong></span>
                    </p>

                    <p>State of the valve : <span class = "dev_sate"><strong><?php echo GetDevState('Vanne',$req); ?></strong></span>
                    </p>
                </div>
                


                     
                
            </section>
            
           
      
            <footer>
                

                <div id="credits">
                  <p>2015-2016 Designed by Delcroix G. - Desmecht M. - Destraix V. - Roland K.</p>
                </div>
                <div id="Liens">
                  <div class="nav2">
                    <ul>
                      <li><a href="https://electroniquehelha.wordpress.com/">WordPress</a></li>
                      <li><a href="http://www.instructables.com/id/Automated-Greenhouse/">Instructable</a></li>
                      <li><a href="https://www.youtube.com/watch?v=O_e-NyzYSH4">Youtube</a></li>
                  </ul>
                  </div>
                        
                </div>
              </footer>
        </div>
    </body>
</html>

<script>
  function refresh_div()

    {

      var xhr_object = null;

      if(window.XMLHttpRequest)

        { // Firefox

          xhr_object = new XMLHttpRequest();

        }

      else if(window.ActiveXObject)

        { // Internet Explorer

          xhr_object = new ActiveXObject('Microsoft.XMLHTTP');

        }

      var method = 'GET';

      var filename = 'main.php';

      xhr_object.open(method, filename, true);

      xhr_object.onreadystatechange = function()

        {

          if(xhr_object.readyState == 4)

            {

              var tmp = xhr_object.responseText;

              document.getElementById('bloc_page').innerHTML = tmp;

            }

        }

      xhr_object.send(null);

      setTimeout('refresh_div()', 1000);

    }

</script>



</script>

<?php 
  function GetMeasure($MeasureType,$req){ 
    $req->execute(array('type'=> $MeasureType));
    while ($donnees = $req->fetch())
    {
      $mesure=$donnees['Valeur'];
    }
    $req->closeCursor(); // Termine le traitement de la requête 
    return $mesure;
  }
?>

<?php 
  function GetStartCount($device,$req){ 
    $req->execute(array('device'=> $device));
    while ($donnees = $req->fetch())
    {
      $counter=$donnees['NombreDem'];
    }
    $req->closeCursor(); // Termine le traitement de la requête 
    return $counter;
  }
?>

<?php 
  function GetDevState($device,$req){ 
    $req->execute(array('device'=> $device));
    while ($donnees = $req->fetch())
    {
      $etat=$donnees['Etat'];
    }
    $req->closeCursor(); // Termine le traitement de la requête 

    $val=$etat;
    
     if ($device == 'Servomoteur')
      {
        if ($val == 1)
        {
          $ret='Opened';
        }
       if ($val == 0)
        {
          $ret='Closed';
        }
      }
    else
      {
        if ($val == 1)
        {
          $ret='On';
        }
        if ($val == 0)
        {
          $ret='Off';
        }
      }
    return $ret; 
  }
?>

<?php 
  function GetDevMode($device,$req){ 
    $req->execute(array('device'=> $device));
    while ($donnees = $req->fetch())
    {
      $mode=$donnees['Mode'];
    }
    $req->closeCursor(); // Termine le traitement de la requête 

    $val=$mode;
    
    if ($val == 1)
      {
        $ret='Manual';
      }
    if ($val == 0)
      {
        $ret='Automatic';
      }
    return $ret; 
  }
?>



