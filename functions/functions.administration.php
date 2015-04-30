<?php
/**************************************************
 * SET OF FUNCTIONS TO MANAGE ADMINISTRATION PAGE *
 **************************************************/

function display_messages()
{

}

function display_users()
{

}

/* Function display config.ini parameters and create a form to change its values */
function display_config()
{
  $ini= parse_ini_file("config.ini", true);

  echo '<h2>Modification de la configuration</h2>';
  echo '<form name ="update_config" method="post" action="index.php?page=administration&manage=config">';
  foreach($ini as $header => $section)
  {
    echo '<h3>' . $header . '</h3>';
    foreach($section as $param => $value)
    {
      $row = '<label>' . $param .' : </label><input type="text" name="'. $param . '" value="' . $value;
      $rowend;
      if($header == "DATABASE")
      {
        $rowend = '" readonly/><br>';
      }
      else
      {
        $rowend = '" required/><br>';
      }
      echo $row.$rowend;
    }
  }

  echo '<p> Validez les changements : </p>';
  echo '<label>Mot de passe : </label>';
  echo '<input type="password" name="password_config" required/><br/>';
  echo '<input type="submit" value="Valider" name="config_submit"/></form>';

}

/* Function receive POST values from display_config() and use it to update config.ini file. */
function update_config($password, $config, $dbsocket)
{
  if(profile_auth($password, $config, $dbsocket))
  {
    $file = fopen("config.ini", "w");
    $ini_array = parse_ini_file("config.ini", true);

    $ini_array['DATABASE']['localhost'] = $_POST['localhost'];
    $ini_array['DATABASE']['remotehost'] = $_POST['remotehost'];
    $ini_array['DATABASE']['dbname'] = $_POST['dbname'];
    $ini_array['DATABASE']['dblogin'] = $_POST['dblogin'];
    $ini_array['DATABASE']['dbpassword'] = $_POST['dbpassword'];
    $ini_array['GLOBAL']['title'] = $_POST['title'];
    $ini_array['GLOBAL']['copyright'] = $_POST['copyright'];
    $ini_array['GLOBAL']['banner'] = $_POST['banner'];
    $ini_array['GLOBAL']['avatar'] = $_POST['avatar'];
    $ini_array['GLOBAL']['defaultavatar'] = $_POST['defaultavatar'];
    $ini_array['ADMIN']['admin_name'] = $_POST['admin_name'];
    $ini_array['ADMIN']['admin_mail'] = $_POST['admin_mail'];
    $ini_array['SESSION']['session_name'] = $_POST['session_name'];
    $ini_array['PASSWORD']['password_crypto'] = $_POST['password_crypto'];
    $ini_array['PASSWORD']['password_minlength'] = $_POST['password_minlength'];
    $ini_array['PASSWORD']['password_maxlength'] = $_POST['password_maxlength'];
    $ini_array['PASSWORD']['password_size'] = $_POST['password_size'];
    $ini_array['LOGIN']['login_minlength'] = $_POST['login_minlength'];
    $ini_array['LOGIN']['login_maxlength'] = $_POST['login_maxlength'];
    $ini_array['LOGIN']['login_size'] = $_POST['login_size'];
    $ini_array['MAIL']['mail_maxlength'] = $_POST['mail_maxlength'];
    $ini_array['MAIL']['mail_size'] = $_POST['mail_size'];


    $config_file = '';
    foreach($ini_array as $header => $section)
    {
      $config_file .= '['.$header.']'."\n";
      foreach($section as $param => $value)
      {
        $config_file .= "$param = $value"."\n";
      }
    }

    fputs($file, $config_file);
    fclose($file);
    echo '<span class="success_msg"> La configuration a été modifiée ! </span>';
  }
  else
  {
    echo '<span class="error_msg"> Mauvais mot de passe ! </span>';
  }
}
?>
