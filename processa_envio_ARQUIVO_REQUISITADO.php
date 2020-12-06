<?php 

require "../app_send_mail/bibliotecas/PHPMailer/Exception.php";
require "../app_send_mail/bibliotecas/PHPMailer/OAuth.php";
require "../app_send_mail/bibliotecas/PHPMailer/PHPMailer.php";
require "../app_send_mail/bibliotecas/PHPMailer/POP3.php";
require "../app_send_mail/bibliotecas/PHPMailer/SMTP.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//print_r($_POST); //Recebe informações do script index.php por via de método post. Para receber as informações corretamente, deve-se usar a função nativa "name=""" onde foi definido o método post

class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public $status = array('codigo_status'=>null,'descricao_status'=>'');

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo=$valor;
    }

    public function mensagemValida() {
        if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) { //Checa se as variaveis/inputs são vazias, se sim, recebe false, senão, recebe true e o script continua
            return false;
        }
        return true;
    }
}

$mensagem = new Mensagem();
$mensagem->__set('para',$_POST['para']); //Setando as variáveis privadas, nomeadas com os nomes setados nos inputs no frontend
$mensagem->__set('assunto',$_POST['assunto']);
$mensagem->__set('mensagem',$_POST['mensagem']);

//print_r($mensagem); 
if(!$mensagem->mensagemValida()) { 
    //die(); //a aplicação morre aqui caso a msg não seja enviada //não um método seguro, apenas para estudos
    header('Location:index.php');
    
}
$mail = new PHPMailer(true);

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'your email here';                     // SMTP username
    $mail->Password   = 'your password here';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('diobrand123@gmail.com', 'Dio remetente');
    $mail->addAddress($mensagem->__get('para'));     // Add a recipient (Adiciona um destinatário, setado para usar o destinatário recebido no formulário no frontend)
    //$mail->addReplyTo('diobrand123@gmail.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');
    $mail->AltBody = 'É necessário utilizar um client que suporte HTML';

    $mail->send();


    $mensagem->status['codigo_status']=1;
    $mensagem->status['descricao_status']= 'Mensagem enviada com sucesso!';


    //echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    $mensagem->status['codigo_status']=2;
    $mensagem->status['descricao_status']= "Mensagem não enviada. Mailer Error: {$mail->ErrorInfo}";

} //aqui pode ser aplicada alguma lógica para registrar um possível erro
?>

<html>
<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<head></head>
<body>
   <div class="container">
   <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular! :)</p>
            </div>
            <div class="row" style="padding-left:430px;">
                <div class="col-md-12">
                    <? if($mensagem->status['codigo_status']==1){ ?>
                        <div class="container">
                            <h1 class="display-4 text-success"> Sucesso!  </h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mb-5 text-white">Voltar</a>
                        </div>

                    <? } ?>
<!-- --------------------------------------------------------------------------->
                    <? if($mensagem->status['codigo_status']==2){ ?>
                        <div class="container">
                            <h1 class="display-4 text-danger"> Ops!  </h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mb-5 text-white">Voltar</a>
                        </div>

                    <? } ?>
                </div>
            </div>
   </div> 
</body>
</html>