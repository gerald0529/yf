<?php

class Email
{
	public static function send($email_to, $name, $title, $con)
	{
		//其它全局变量
		$email_host = Web_ConfigModel::value("email_host");
		$email_addr = Web_ConfigModel::value("email_addr");
		$email_pass = Web_ConfigModel::value("email_pass");
		$email_id   = Web_ConfigModel::value("email_id");
		$email_port   = Web_ConfigModel::value("email_port");


		include_once(LIB_PATH . "/phpmailer/class.phpmailer.php");


		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet  = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port     = !$email_port ? 25 : $email_port;
			$mail->Host     = $email_host;
			$mail->Username = $email_addr;
			$mail->Password = $email_pass;
//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
			$mail->AddReplyTo($email_addr, $email_id);//回复地址
			$mail->From     = $email_addr;
			$mail->FromName = $email_id;

			$mail->AddAddress($email_to);
			$mail->Subject = $title;
			$mail->Body    = $con;
			//$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
//$mail->AddAttachment("f:/test.png"); //可以添加附件
			$mail->IsHTML(true);
			$re = $mail->Send();
		}
		catch (phpmailerException $e)
		{
			$re  = false;
			$msg = "邮件发送失败：" . $e->errorMessage();
		}


		if ($re)
		{
			$status = 200;
			$msg    = _('邮件发送成功!');
		}
		else
		{
			$msg    = _('邮件发送失败') . $msg;
			$status = 250;
		}

		return array($msg, $status);
	}

}

?>