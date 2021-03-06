<?php
	$connected = false;
?>

<form name="mes_contact" method="post" action="index.php?page=contact">
	Mail : <br/>
	<input type="text" name="mes_mail"/><br/>
	Sujet : <br/>
	<input type="text" name="mes_subject"/><br/>
	Message : <br/>
	<textarea rows="6" cols="50" name="mes_text"></textarea><br/>
	<input type="submit" name="mes_submit"/>
</form>

<?php

	if(isset($_POST['mes_submit'])){
		if(is_filled($_POST['mes_mail'])AND is_filled($_POST['mes_subject']) AND is_filled($_POST['mes_text']))
		{
				try{
					$db_socket = db_connexion();
					if (!$connected)
					{
						$query = 'INSERT INTO contact_message (mes_subject, mes_mail, mes_text, mes_date)
											VALUES (:subject, :mail, :message, NOW())';
						$response = $db_socket->prepare($query);
						$response->execute(array(
							'subject' => $_POST['mes_subject'],
							'mail' => $_POST['mes_mail'],
							'message' => $_POST['mes_text']
						));
					}

					$to = $_POST['mes_mail'];

					$subject = 'Message envoyé avec succès';

					$headers = "From: " . strip_tags('no-reply@wiki.pmm.be') . "\r\n";
					$headers .= "Reply-To: ". strip_tags('no-reply@wiki.pmm.be') . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=utf-8\r\n";

					$message = '<html><body>';
					$message .= '<h2>Votre message a bien été envoyé et sera traité dans les plus brefs délais</h2>';
					$message .= '<h3>Rappel de votre message : <h3>';
					$message .= '<ul>';
					$message .= '<li><b>Email  :</b>'   . $_POST['mes_mail']   . '</li>';
					$message .= '<li><b>Sujet  :</b>'   . $_POST['mes_subject'] . '</li>';
					$message .= '<li><b>Message  :</b>' . $_POST['mes_text']    . '</li>';
					$message .= '</ul></body></html>';

					mail($to, $subject, $message, $headers);

					echo '<h3 class="success_msg">Le mail a été envoyé.</h3>';
				}
				catch(PDOException $e)
		    {
		    	echo $query . "<br>" . $e->getMessage();
		    }
				$db_socket = null;
		}
		else
		{
			echo '<h3 class="error_msg">Vous n\'avez pas complété tout les champs !</h3>';
		}
	}

	/*
	$headers = 'From: Samuel Monroe <admin@kek.com> ' . "\r\n" .
	'Reply-To: admin@kek.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		mail($_POST['cmail'], $_POST['csubject'], $_POST['cmessage'], $headers);
	*/
?>
