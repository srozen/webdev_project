<?php


  /************************************************************************
   * THIS SET OF FUNCTIONS PROVIDES AN API FOR INTERNAL MESSAGES MANAGING *
   ************************************************************************/


function send_contact_message($mail, $subject, $message, $dbsocket)
{
  if(filled($mail) AND filled($subject) AND filled($message))
  {
    save_contact_message($mail, $subject, $message, $dbsocket);
    confirm_contact_message($mail, $subject, $message);
    echo '<span class="success_msg"> Le message a bien été envoyé ! </span>';
  }
  else
  {
    echo '<span class="error_msg"> Vous n\'avez pas rempli tout les champs ! </span>';
  }
}


function save_contact_message($mail, $subject, $message, $dbsocket)
{
  if(logged())
  {
    $userid = $_SESSION['user']->getId();
  }
  else
  {
    $userid = null;
  }

  $query = 'INSERT INTO contact_message (subject, mail, message, date, user_id)
            VALUES (:subject, :mail, :message, NOW())';
  $response = $db_socket->prepare($query);
  $response->execute(array(
    'subject' => $subject,
    'mail' => $mail,
    'message' => $message,
    'user_id' => $userid
  ));
}

function confirm_contact_message($mail, $subject, $message)
{
  $to = $mail;

  $subject = 'Message envoyé avec succès';

  $headers = "From: " . strip_tags('no-reply@wiki.pmm.be') . "\r\n";
  $headers .= "Reply-To: ". strip_tags('no-reply@wiki.pmm.be') . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=utf-8\r\n";

  $message = '<html><body>';
  $message .= '<h2>Votre message a bien été envoyé et sera traité dans les plus brefs délais</h2>';
  $message .= '<h3>Rappel de votre message : <h3>';
  $message .= '<ul>';
  $message .= '<li><b>Email  :</b>'   . $mail    . '</li>';
  $message .= '<li><b>Sujet  :</b>'   . $subject . '</li>';
  $message .= '<li><b>Message  :</b>' . $message . '</li>';
  $message .= '</ul></body></html>';

  mail($to, $subject, $message, $headers);
}
?>