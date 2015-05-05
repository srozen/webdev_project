<?php

  function login($login, $password, $code)
  {
    if(filled($login) AND filled($password))
    {
      $query = 'SELECT id
                FROM user
                WHERE login = \'' . $login . '\'
                AND password = \'' . encrypt($password) . '\'';

      $result = $GLOBALS['dbsocket']->query($query);

      $user = $result->fetch(PDO::FETCH_ASSOC);

      if(!empty($user))
      {
        if(is_user_status('activating', $user['id']))
        {
          activation($user['id'], $code)
        }
        else
        {
          grant_access($user['id']);
        }
      }
      else
      {
        echo 'Mauvaise combinaison login mdp';
      }
    }
    else
    {
      echo 'Les champs ne sont pas remplis !!! ';
    }
  }

  function activation($userid, $code)
  {
    if(filled($code))
    {
      if(get_activation_code($userid) == $code)
      {
        set_user_value('lastlogin', 'NOW()', $userid);
        set_user_value('currentlogin', 'NOW()', $userid);
        set_user_value('activation', 'NOW()', $userid);

        //remove_user_status($userid, $status_id);
        remove_activation_code($userid);
        grant_cass($userid);
      }
    }
    else
    {
      echo '<div class="error_msg"> Vous devez vous connecter via le mail d\'activation ! </div>';
    }
  }

  function grant_access($userid)
  {
    update_lastlogin($userid);
  }

  function update_lastlogin($userid)
  {
    $lastlogin = get_user_value('currentlogin', 'id', $userid);
    set_user_value('lastlogin', $lastlogin, $userid);
    set_user_value('currentlogin', 'NOW()', $userid);
  }
