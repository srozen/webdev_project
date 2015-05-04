<?php

  /************************************
   * FUNCTIONS USED BY THE INDEX PAGE *
   ************************************

  /* Creates the menu for the <nav> bar in index */
  function create_menu()
  {
    $nav =  '<a href="index.php?page=index"> Accueil </a>';
    $nav .= '<a href="index.php?page=register"> Inscription </a>';
    echo $nav;
  }

  function page_values($page)
  {
    switch($page)
    {
      case 'index' :
        return $values = array('Accueil', 'welcome.php', 'Page d\'accueil');
        break;
      case 'register' :
        return $values = array('Inscription', 'register.php', 'Page d\'inscription');
        break;
      default :
        return $values = array('Accueil', 'welcome.php', 'Page d\'accueil');
        break;
    }
  }

  function create_page($page)
  {
    $values = page_values($page);
    return new Page($values[0], $values[1], $values[2]);
  }

  function database_socket()
  {
    try
    {
      $dbsocket = new PDO('mysql:host=localhost; dbname=' . $GLOBALS['config']['DATABASE']['dbname'] . '; charset=utf8', $GLOBALS['config']['DATABASE']['dblogin'], $GLOBALS['config']['DATABASE']['dbpassword']);
      $dbsocket->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbsocket;
    }
    catch(Exception $e)
    {
      die('Erreur : '.$e->getMessage());
      echo 'Database connexion failed';
    }

  }