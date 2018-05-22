<?php 

namespace Hcode;

use Rain\Tpl;

class Mailer {

	const USERNAME = "dev.emso.exe@gmail.com";
	const PASSWORD = "";
	const NAME_FROM = "Hcode Store";

	private $mail;

	public function __construct($toAddress, $toName, $subject, $tplName, $data = array()) {

		$config = array(
			"tpl_dir"	=> $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"	=> $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"		=> false
		);

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		//Create a new PHPMailer instance
		$this->mail = new \PHPMailer;

		// código retirado do perguntas e respostas do curso
		    $this->mail->isSMTP();
		    $this->mail->SMTPOptions = array(
		        'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		    );
		// se persistir o erro é necessário desativar a verificação de 2 etapas do gmail
		// e acessar o link https://support.google.com/accounts/answer/6010255

		$this->mail->isSMTP();

		$this->mail->SMTPDebug = 0;

		$this->mail->Host = 'smtp.gmail.com'; // COLOQUE O SEU SERVIDOR SMTP <------------------------------

		$this->mail->Port = 587; // COLOQUE A PORTA DO E-MAIL <---------------------------

		$this->mail->SMTPSecure = 'tls'; 

		$this->mail->SMTPAuth = true;

		$this->mail->Username = Mailer::USERNAME; // COLOQUE O SEU E-MAIL <---------------------------

		$this->mail->Password = Mailer::PASSWORD; // COLOQUE A SENHA DO E-MAIL <------------------------

		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

		$this->mail->addAddress($toAddress, $toName);

		$this->mail->Subject = $subject;

		$this->mail->msgHTML($html);

		$this->mail->AltBody = 'This is a plain-text message body';

		/*if (!$mail->send()) {
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    echo "Message sent!";
		}*/

		/*function save_mail($mail)
		{
		    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";
		    $imapStream = imap_open($path, $mail->Username, $mail->Password);
		    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		    imap_close($imapStream);
		    return $result;
		}*/
	}

	public function send(){

		return $this->mail->send();
	}

}

 ?>
