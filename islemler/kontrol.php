<?php 
require 'baglan.php';
$host_adresi=$ayarcek['site_mail_host'];
$mail_adresiniz=$ayarcek['site_mail_mail'];
$port_numarasi=$ayarcek['site_mail_port'];
$mail_sifreniz=$ayarcek['site_mail_sifre'];


require_once 'PHPMailer/Exception.php';
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';

$mailbasligi="Yapılacak İşlem Hatırlatma Maili";
$isim=$ayarcek['site_baslik'];


$mail = new PHPMailer\PHPMailer\PHPMailer(); 
$mail->IsSMTP(); 
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl'; 
$mail->Host = $host_adresi;
$mail->Port = $port_numarasi; 
$mail->IsHTML(true);
$mail->Username = $mail_adresiniz;
$mail->Password = $mail_sifreniz; 
$mail->SetFrom($mail->Username, $isim);	
$mail->Subject = $mailbasligi;
$mail->CharSet = 'UTF-8';


$sorgu=$db->prepare("SELECT * FROM yapilacaklar WHERE yap_mail_onay=1 AND yap_mail_durum=0");
$sorgu->execute();

while ($yapcek=$sorgu->fetch(PDO::FETCH_ASSOC)) {
	if (!strstr($yapcek['yap_islem_tarih'],0000)) {
		$tarih1=strtotime(date("d.m.Y"));
		$tarih2=strtotime($yapcek['yap_islem_tarih']);
		$fark=$tarih2-$tarih1;
		$sonuc = floor($fark / 60);

		if ($sonuc<0) {	

			$sorgu=$db->prepare("SELECT * FROM kullanicilar WHERE kul_id=:kul_id");
			$sorgu->execute(array(
				'kul_id' => $yapcek['yap_kul']
			));		
			$kullanici=$sorgu->fetch(PDO::FETCH_ASSOC);
			
			$mailicerigi="Sayın ".$kullanici['kul_isim']." ".$yapcek['yap_baslik']." başlıklı hatırlatıcınızın vakti geldi işleminizi yapmayı unutmayın. <br><br>"."Yapılacak İşlem Detayı: <hr>".$yapcek['yap_detay'];

			$mail->Body = $mailicerigi;
			$mail->AddAddress($kullanici['kul_mail']);

			if ($mail->send()) {
				echo "Başarılı";
				$sorgu=$db->prepare("UPDATE yapilacaklar SET yap_mail_durum=:yap_mail_durum WHERE yap_id=:yap_id");
				$sorgu->execute(array(
					'yap_id' => $yapcek['yap_id'],
					'yap_mail_durum' => 1
				));

			} else {
				echo "Başarısız<br>";
				echo $mail->ErrorInfo;
			}

		}

	}
}

?>