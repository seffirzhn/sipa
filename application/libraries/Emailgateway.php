<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


include_once APPPATH."/third_party/PHPMailer/Autoload.php";

class Emailgateway {      
     
    public function __construct() { 
     
    } 

    public function send($config){ 
        
        $mail                   = new PHPMailer\PHPMailer\PHPMailer();
        $mail->addAddress($config['destination']);
        $mail->setFrom($config['email'], $config['instansi'],0);
        $mail->Subject          = $config['subject'];
        $mail->isHTML(true);
        $mail->IsSMTP();
        $mail->Body             = $config['message'];
        if(isset($config["attachment"]) && file_exists(realpath($config["attachment"]))){
            $file               = pathinfo(realpath($config["attachment"]));
            $mail->addAttachment(realpath($config["attachment"]),$file['basename']);
        }
        $mail->SMTPSecure       = false;
        $mail->Host             = $config['hostmail'];
        $mail->Port             = $config['portmail'];
        $mail->SMTPAuth         = true;
        $mail->Username         = $config['email'];
        $mail->Password         = $config['passmail'];
        $mail->SMTPSecure       = "tls";
        $mail->SMTPOptions      = array(
                                        'ssl' => array(
                                            'verify_peer' => false,
                                            'verify_peer_name' => false,
                                            'allow_self_signed' => true
                                        )
                                    );
        $result                 = array();
        if($mail->Send())
        {
            if(isset($config["attachment"]) && file_exists(realpath($config["attachment"])) && isset($config['deleteattach'])){
                @unlink(realpath($config["attachment"]));
            }
            $result["status"]   = "success";
            $result["message"]  = "Berhasil mengirim email";
            
        }
        else
        {
            $result["status"]   = "error";
            $result["message"]  = "Gagal mengirim email ".$mail->ErrorInfo;
        }
        
        return $result;
    } 
}