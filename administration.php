<?php

  if(!logged())
  {
    header("Location: index.php");
    die();
  }

  if(!is_admin($_SESSION['user']))
  {
    header("Location: index.php");
    die();
  }


  echo '<ul> Panneau d\'administration';
  echo '<li><a href=index.php?page=administration&manage=user> Gestion des membres </a></li>';
  echo '<li><a href=index.php?page=administration&manage=mail> Gestion des messages </a></li>';
  echo '<li><a href=index.php?page=administration&manage=config> Gestion de la configuration </a></li>';
  echo '</ul>';

  if(isset($_GET['manage']))
  {
    switch($_GET['manage'])
    {
      case 'user' :
        select_users();
        if(isset($_POST['select_users']))
        {
          display_users($_POST['login'], $_POST['mail'], $_POST['status'], $dbsocket);
        }
        break;
      case 'mail' :
        select_messages();
        if(isset($_POST['answer_message']))
        {
          answer_contact_message($_POST['mail'], $_POST['subject'], $_POST['message'], $_POST['answer'], $_POST['id'], $dbsocket);
          save_contact_message($_SESSION['user']->getMail(), $_POST['subject'], $_POST['answer'], $dbsocket, $_POST['id']);
          echo '<span class="success_msg"> Votre réponse a bien été envoyée !</span>';
        }
        if(isset($_POST['select_message']))
        {
          answer_message_form($_POST['answer_id'], $dbsocket);
        }
        if(isset($_POST['mail_submit']))
        {
          display_messages($_POST['mail_sort'], $dbsocket);
        }
        break;
      case 'config' :
        if(isset($_POST['config_submit']))
        {
          update_config($_POST['password_config'], $config, $dbsocket);
        }
        display_config();
        break;
      default :
        echo 'Veuillez sélectionner une option d\'administration';
        break;
    }
  }

?>
