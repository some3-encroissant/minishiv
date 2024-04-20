<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="commands_style.css" />
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

<!-- Traitement du formulaire pour les commandes -->
                  <?php            
                    if(isset($_POST['Device']) && isset($_POST['Mode']))
                    {
                      if ($_POST['Mode']=='0')
                      {
                        $req = $bdd->prepare('UPDATE commandes SET Mode= :mode, ComManu= :action WHERE Equipement= :device');
                        $req->execute(array('mode'=>$_POST['Mode'],'action' => '0' , 'device'=> $_POST['Device']));
                      
                        $req->closeCursor(); // Termine le traitement de la requête
                        


                      }
                      else 
                      {
                        $req = $bdd->prepare('UPDATE commandes SET Mode= :mode WHERE Equipement= :device');
                        $req->execute(array('mode'=>$_POST['Mode'], 'device'=> $_POST['Device']));
                        $req->closeCursor(); // Termine le traitement de la requête
            
                        
                      }
                    }

                    
                    if(isset($_POST['Device']) && isset($_POST['Action']))
                    {
                      $req = $bdd->prepare('UPDATE commandes SET ComManu= :action WHERE Equipement= :device');
                      $req->execute(array('action'=>$_POST['Action'], 'device'=> $_POST['Device']));

                      $req->closeCursor(); // Termine le traitement de la requête
                  
                      sleep(7);
                    }

                   
                    
                    if(isset($_POST['IntTempSP'])  && is_numeric($_POST['IntTempSP']) && $_POST['IntTempSP'] >= 0 )
                    {
                       $req = $bdd->prepare('UPDATE types SET  Consigne=:NewSP WHERE Type="Temperature_int" ');
                      
                      $req->execute(array('NewSP'=>$_POST['IntTempSP'], ));

                      $req->closeCursor(); // Termine le traitement de la requête
              
                    }


                    
                    if(isset($_POST['Delta'])  && is_numeric($_POST['Delta'])  && $_POST['Delta'] >= 0  )
                    {
                      $req = $bdd->prepare('UPDATE types SET  DeltaT=:NewDT WHERE Type="Temperature_int" ');
                      
                      $req->execute(array('NewDT'=>$_POST['Delta'], ));

                      $req->closeCursor(); // Termine le traitement de la requête
             
                    }

                    if(isset($_POST['LT'])  && is_numeric($_POST['LT'])  && $_POST['LT'] >= 0  && $_POST['LT'] <= 100  && isset($_POST['HT_live']) && $_POST['LT'] <= $_POST['HT_live'] )
                    {
                      $req = $bdd->prepare('UPDATE types SET  SeuilBas=:NewLT WHERE Type="Humidite" ');
                      
                      $req->execute(array('NewLT'=>$_POST['LT'], ));

                      $req->closeCursor(); // Termine le traitement de la requête
            
                    }

                    if(isset($_POST['HT'])  && is_numeric($_POST['HT'])  && $_POST['HT'] >= 0  && $_POST['HT'] <= 100  && isset($_POST['LT_live']) && $_POST['HT'] >= $_POST['LT_live'] )
                    {
                      $req = $bdd->prepare('UPDATE types SET  SeuilHaut=:NewHT WHERE Type="Humidite" ');
                      
                      $req->execute(array('NewHT'=>$_POST['HT'], ));

                      $req->closeCursor(); // Termine le traitement de la requête
            
                    }

                  ?>

                

            </header>

            <section>
              <?php

                $reponse = $bdd->query('SELECT Equipement , Mode, Etat from commandes');
                $i=0;
                while ($donnees = $reponse->fetch())
                  {
                    $DeviceMode[$i]=$donnees['Mode'];
                    $Device[$i]=$donnees['Equipement'];
                    $DeviceState[$i]= $donnees['Etat'];
                    $i=$i+1;
                    
                  }
                $reponse->closeCursor(); // Termine le traitement de la requête 
                $req2 = $bdd->prepare('SELECT Mode from commandes where Equipement= :device');
                $req3 = $bdd->prepare('SELECT Etat from commandes where Equipement= :device');
              ?>


              <div id="Commande">
<!-- Partie commandes  -->   
                  <h1><img src="image/tn_icone_commandes.png" alt="Commands" class="ico_categorie" />Commands</h1>
                      <div class="lc">
                        <div class="com">
                          <h3>Lamp : </h3>
                          <p>Actual mode: <span class = "mode"> <strong><?php echo GetDevMode('Lampe_infrarouge',$req2); ?></strong></span> </p>
                          <p>Switch mode to : 
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[0]; ?> >
                              <input type="hidden" name= 'Mode' value= <?php echo GetMode($DeviceMode[0]); ?> >
                              <input type="submit" name='btnModeLamp' value= <?php echo GetLabel($DeviceMode[0]); ?> />
                            </form>  
                          </p>

                          <?php  
                            if ($DeviceMode[0]==0)
                            {
                              $type="hidden";
                            }
                            if ($DeviceMode[0]==1) 
                            {
                              $type="submit";
                            }
                          ?>
                          
                          <p>Actual state: <span class = "etat"> <strong><?php echo GetDevState('Lampe_infrarouge',$req3); ?></strong></span> </p>
                          <p><?php if ($type== 'submit') {echo 'Switch state to :';} ?>
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[0]; ?> >
                              <input type="hidden" name= 'Action' value= <?php echo GetState($DeviceState[0]); ?> >
                              <input type="<?php echo $type; ?>" name='btnComLamp' value= <?php echo GetLabel2($DeviceState[0]); ?> />
                            </form>  
                          </p>
                        </div>

                        <div class="com">
                          <h3>Pump : </h3>
                          <p>Actual mode: <span class = "mode"> <strong><?php echo GetDevMode('Pompe',$req2); ?></strong></span> </p>
                          <p>Switch mode to : 
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[1]; ?> >
                              <input type="hidden" name= 'Mode' value= <?php echo GetMode($DeviceMode[1]); ?> >
                              <input type="submit" name='btnModeLamp' value= <?php echo GetLabel($DeviceMode[1]); ?> />
                            </form>  
                          </p>

                          <?php  
                            if ($DeviceMode[1]==0)
                            {
                              $type="hidden";
                            }
                            if ($DeviceMode[1]==1) 
                            {
                              $type="submit";
                            }
                          ?>
                          
                          <p>Actual state: <span class = "mode"> <strong><?php echo GetDevState('Pompe',$req3); ?></strong></span> </p>
                          <p><?php if ($type== 'submit') {echo 'Switch state to :';} ?>
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[1]; ?> >
                              <input type="hidden" name= 'Action' value= <?php echo GetState($DeviceState[1]); ?> >
                              <input type="<?php echo $type; ?>" name='btnComLamp' value= <?php echo GetLabel2($DeviceState[1]); ?> />
                            </form>  
                          </p>
                        </div>
                      </div>
                      <div class="lc">
                        <div class="com">
                          <h3>Window : </h3>
                          <p>Actual mode: <span class = "mode"> <strong><?php echo GetDevMode('Servomoteur',$req2); ?></strong></span> </p>
                          <p>Switch mode to : 
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[2]; ?> >
                              <input type="hidden" name= 'Mode' value= <?php echo GetMode($DeviceMode[2]); ?> >
                              <input type="submit" name='btnModeLamp' value= <?php echo GetLabel($DeviceMode[2]); ?> />
                            </form>  
                          </p>

                          <?php  
                            if ($DeviceMode[2]==0)
                            {
                              $type="hidden";
                            }
                            if ($DeviceMode[2]==1) 
                            {
                              $type="submit";
                            }
                          ?>
                          
                          <p>Actual state: <span class = "mode"> <strong><?php echo GetDevState('Servomoteur',$req3); ?></strong></span> </p>
                          <p><?php if ($type== 'submit') {echo 'Switch state to :';} ?>
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[2]; ?> >
                              <input type="hidden" name= 'Action' value= <?php echo GetState($DeviceState[2]); ?> >
                              <input type="<?php echo $type; ?>" name='btnComLamp' value= <?php echo GetLabel2($DeviceState[2]); ?> />
                            </form>  
                          </p>
                        </div>


                        <div class="com">
                          <h3>Valve : </h3>
                          <p>Actual mode: <span class = "mode"> <strong><?php echo GetDevMode('Vanne',$req2); ?></strong></span> </p>
                          <p>Switch mode to : 
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[3]; ?> >
                              <input type="hidden" name= 'Mode' value= <?php echo GetMode($DeviceMode[3]); ?> >
                              <input type="submit" name='btnModeLamp' value= <?php echo GetLabel($DeviceMode[3]); ?> />
                            </form>  
                          </p>

                          <?php  
                            if ($DeviceMode[3]==0)
                            {
                              $type="hidden";
                            }
                            if ($DeviceMode[3]==1) 
                            {
                              $type="submit";
                            }
                          ?>
                          
                          <p>Actual state: <span class = "mode"> <strong><?php echo GetDevState('Vanne',$req3); ?></strong></span> </p>
                          <p><?php if ($type== 'submit') {echo 'Switch state to :';} ?>
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[3]; ?> >
                              <input type="hidden" name= 'Action' value= <?php echo GetState($DeviceState[3]); ?> >
                              <input type="<?php echo $type; ?>" name='btnComLamp' value= <?php echo GetLabel2($DeviceState[3]); ?> />
                            </form>  
                          </p>
                        </div>
                      </div>
                      <div class="lc">
                        <div class="com">
                          <h3>Fan : </h3>
                          <p>Actual mode: <span class = "mode"> <strong><?php echo GetDevMode('Ventilateur',$req2); ?></strong></span> </p>
                          <p>Switch mode to : 
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[4]; ?> >
                              <input type="hidden" name= 'Mode' value= <?php echo GetMode($DeviceMode[4]); ?> >
                              <input type="submit" name='btnModeLamp' value= <?php echo GetLabel($DeviceMode[4]); ?> />
                            </form>  
                          </p>

                          <?php  
                            if ($DeviceMode[4]==0)
                            {
                              $type="hidden";
                            }
                            if ($DeviceMode[4]==1) 
                            {
                              $type="submit";
                            }
                          ?>
                          
                          <p>Actual state: <span class = "mode"> <strong><?php echo GetDevState('Ventilateur',$req3); ?></strong></span> </p>
                          <p><?php if ($type== 'submit') {echo 'Switch state to :';} ?>
                            <form method="post" action="commands.php" >
                              <input type="hidden" name= 'Device' value= <?php echo $Device[4]; ?> >
                              <input type="hidden" name= 'Action' value= <?php echo GetState($DeviceState[4]); ?> >
                              <input type="<?php echo $type; ?>" name='btnComLamp' value= <?php echo GetLabel2($DeviceState[4]); ?> />
                            </form>  
                          </p>
                        </div>
                      </div>

                  
              </div>



              <div id="Parametre">
<!-- Partie commandes  -->      
                
                <h1><img src="image/tn_icone_parametres.png" alt="Parameters" class="ico_categorie" />Parameters</h1>
                  <div class ="para">
                    <h2>Internal temperature :</h2>
                    <?php 
                    $req = $bdd->prepare('SELECT Consigne,DeltaT from types where Type= :measure');
                    ?>
                    <h3>Setpoint :</h3>
                    <p>Running value : <span class = "value"> <strong><?php echo GetRunningValue('Temperature_int',$req,0);  ?></strong></span> °C</p>
                    <p>
                      <form method="post" action="commands.php" >
                        <label for='IntTempSP'> New setpoint :  </label>
                        <input type="text" name= 'IntTempSP' id='IntTempSP'>
                        <input type="submit" name='btnSetSp' value= 'Set new value' />
                        </form>  
                      </p>
                    <h3>Delta :</h3>
                    <p>Running value : <span class = "value"> <strong><?php echo GetRunningValue('Temperature_int',$req,1);  ?></strong></span> °C</p>
                    <p>
                      <form method="post" action="commands.php" >
                        <label for='Delta'> New delta :  </label>
                        <input type="text" name= 'Delta' id='Delta'>
                        <input type="submit" name='btnSetDelta' value= 'Set new value' />
                        </form>  
                      </p> 
                  </div>
                  <div class ="para">
                    <h2>Soil moisture :</h2>
                      <?php 
                      $req = $bdd->prepare('SELECT SeuilBas,SeuilHaut from types where Type= :measure');
                      ?>
                      <h3>Low treshold :</h3>
                      <p>Running value : <span class = "value"> <strong><?php echo GetRunningValue('Humidite',$req,0);  ?></strong></span> %</p>
                      <p>
                        <form method="post" action="commands.php" >
                          <label for='LT'> New treshold :  </label>
                          <input type="text" name= 'LT' id='LT'>
                          <input type="hidden" name= 'HT_live' id='HT_live' value=<?php echo GetRunningValue('Humidite',$req,1);  ?>> <!-- Pour comparer et s'assurer de la logique des niveaux -->
                          <input type="submit" name='btnSetLT' value= 'Set new value' />
                          </form>  
                        </p>
                      <h3>High treshold:</h3>
                      <p>Running value : <span class = "value"> <strong><?php echo GetRunningValue('Humidite',$req,1);  ?></strong></span> %</p>
                      <p>
                        <form method="post" action="commands.php" >
                          <label for='HT'> New treshold :  </label>
                          <input type="text" name= 'HT' id='HT'>
                          <input type="hidden" name= 'LT_live' id='LT_live' value=<?php echo GetRunningValue('Humidite',$req,0);  ?>> <!-- Pour comparer et s'assurer de la logique des niveaux -->
                          <input type="submit" name='btnSetHT' value= 'Set new value' />
                          </form>  
                        </p> 
                  </div>
                
              </div>

            </section>
          
            <footer>
                

                <div id="credits">
                  <p>2015-2016 Designed by Delcroix G. - Desmecht M. - Destraix V. - Roland K.</p>
                </div>
                <div id="Liens">
                  <div class="nav2">
                    <ul>
                      <li><a href="https://electroniquehelha.wordpress.com/author/vincentdestraix/">WordPress</a></li>
                      <li><a href="http://www.instructables.com/id/Automated-Greenhouse/">Instructable</a></li>
                      <li><a href="https://www.youtube.com/watch?v=O_e-NyzYSH4">Youtube</a></li>
                  </ul>
                  </div>
                        
                </div>
              </footer>
        </div>
    </body>
</html>





<?php 
  function GetMode($mode)
  {
    if ($mode==1)
    {
      $ret=0;
    }
    else if ($mode==0)
    {
      $ret=1;
    }
    return $ret;
  }
?>

<?php 
  function GetState($state)
  {
    if ($state==1)
    {
      $ret=0;
    }
    else if ($state==0)
    {
      $ret=1;
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
  function GetLabel($mode)
  {
    if ($mode==1)
    {
      $ret='Automatic';
    }
    else if ($mode==0)
    {
      $ret='Manual';
    }
    return $ret;
  }
?>

<?php 
  function GetLabel2($Etat)
  {
    if ($Etat==1)
    {
      $ret='Off';
    }
    else if ($Etat==0)
    {
      $ret='On';
    }
    return $ret;
  }
?>

<?php 
  function GetRunningValue($measure,$req,$i){ 
    $req->execute(array('measure'=> $measure));
    while ($donnees = $req->fetch())
    {
      switch ($measure) {
        case 'Temperature_int':
          $valeur[0]=$donnees['Consigne'];
          $valeur[1]=$donnees['DeltaT'];
          break;
        case 'Humidite':
          $valeur[0]=$donnees['SeuilBas'];
          $valeur[1]=$donnees['SeuilHaut'];
          break;
        default:
          $valeur[0]=999;
          $valeur[1]=999;          
          break;
      }
    }
    $req->closeCursor(); // Termine le traitement de la requête 
      
    return $valeur[$i]; 
      }
    
  
?>


<script type="text/javascript"> 
   function ClicBouton(Equipement){ 
      alert(Equipement);
   } 
</script>

