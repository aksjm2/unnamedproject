<?php
$smtp_use = 'smtp.gmail.com';                       
$smtp_mail_id = "drunkenjyan@gmail.com";
$smtp_mail_pw = "smallm13";


$from_name = "�����»�� �̸�";
$from_email = "�����̵�@gmail.com";


$ArrayTo = array( "ferus_gm@hanmail.net" );
$ArrayCC = array( "ferus_gm@hanmail.net" );
    
$title = "�츮�� PHP�� ���̹����� �̿��Ͽ� ���� �߼�";   // ���� ����
$content = "���ϳ��� <br> html�� ����<br />�츮��"; // ���ϳ��� <br> html�� ����


function SendMail($smtp_use, $smtp_mail_id, $smtp_mail_pw,  $from_name, $from_email, &$ArrayTo, &$ArrayCC, $title, $content ) {


        if ($smtp_use == 'smtp.naver.com') { 
            $from_email = $smtp_mail_id; //���̹������� ������ id�θ� �����̰�����
        } else {
            $from_email = $from_email; 
        }
    
        require_once($_SERVER["DOCUMENT_ROOT"] . "/Lib/PHPMailer/class.phpmailer.php");
   
        $mail = new PHPMailer(true);
        $mail->IsSMTP();


        try {
            $mail->CharSet = "UTF-8";  
            $mail->Encoding = "base64";
            $mail->Host = $smtp_use;                    // email ������ ����� ������ ����
            $mail->SMTPAuth = true;                     // SMTP ������ �����
            $mail->Port = 465;                          // email ������ ����� ��Ʈ�� ����
            $mail->SMTPSecure = "ssl";                  // SSL�� �����
            $mail->Username   = $smtp_mail_id;          // ����
            $mail->Password   = $smtp_mail_pw;          // �н�����
            $mail->SetFrom($from_email, $from_name);    // ������ ��� email �ּҿ� ǥ�õ� �̸� (ǥ�õ� �̸��� ��������)
            
            foreach ($ArrayTo as $value) {
                $mail->AddAddress($value);
            }
            
            if (count($ArrayCC) > 0) {
                foreach ($ArrayCC as $value) {
                    $mail->AddCC($value);
                }
            }
            
            $mail->Subject = $title;                    // ���� ����
            $mail->MsgHTML($content);                   // ���� ���� (HTML ���ĵ� �ǰ� �׳� �Ϲ� �ؽ�Ʈ�� ��� ������)
            $mail->Send();                              // ������ ������ ����


            return 1;
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

SendMail($smtp_use, $smtp_mail_id, $smtp_mail_pw,  $from_name, $from_email, &$ArrayTo, &$ArrayCC, $title, $content );
?>