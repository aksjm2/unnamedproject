<?php
$smtp_use = 'smtp.gmail.com';                       
$smtp_mail_id = "drunkenjyan@gmail.com";
$smtp_mail_pw = "smallm13";


$from_name = "보내는사람 이름";
$from_email = "내아이디@gmail.com";


$ArrayTo = array( "ferus_gm@hanmail.net" );
$ArrayCC = array( "ferus_gm@hanmail.net" );
    
$title = "우리는 PHP로 네이버게정 이용하여 메일 발송";   // 메일 제목
$content = "메일내용 <br> html도 가능<br />우리는"; // 메일내용 <br> html도 가능


function SendMail($smtp_use, $smtp_mail_id, $smtp_mail_pw,  $from_name, $from_email, &$ArrayTo, &$ArrayCC, $title, $content ) {


        if ($smtp_use == 'smtp.naver.com') { 
            $from_email = $smtp_mail_id; //네이버메일은 보내는 id로만 전송이가능함
        } else {
            $from_email = $from_email; 
        }
    
        require_once($_SERVER["DOCUMENT_ROOT"] . "/Lib/PHPMailer/class.phpmailer.php");
   
        $mail = new PHPMailer(true);
        $mail->IsSMTP();


        try {
            $mail->CharSet = "UTF-8";  
            $mail->Encoding = "base64";
            $mail->Host = $smtp_use;                    // email 보낼때 사용할 서버를 지정
            $mail->SMTPAuth = true;                     // SMTP 인증을 사용함
            $mail->Port = 465;                          // email 보낼때 사용할 포트를 지정
            $mail->SMTPSecure = "ssl";                  // SSL을 사용함
            $mail->Username   = $smtp_mail_id;          // 계정
            $mail->Password   = $smtp_mail_pw;          // 패스워드
            $mail->SetFrom($from_email, $from_name);    // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
            
            foreach ($ArrayTo as $value) {
                $mail->AddAddress($value);
            }
            
            if (count($ArrayCC) > 0) {
                foreach ($ArrayCC as $value) {
                    $mail->AddCC($value);
                }
            }
            
            $mail->Subject = $title;                    // 메일 제목
            $mail->MsgHTML($content);                   // 메일 내용 (HTML 형식도 되고 그냥 일반 텍스트도 사용 가능함)
            $mail->Send();                              // 실제로 메일을 보냄


            return 1;
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

SendMail($smtp_use, $smtp_mail_id, $smtp_mail_pw,  $from_name, $from_email, &$ArrayTo, &$ArrayCC, $title, $content );
?>