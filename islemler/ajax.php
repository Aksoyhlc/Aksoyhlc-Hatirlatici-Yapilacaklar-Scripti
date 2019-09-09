<?php 
require 'baglan.php';
require '../fonksiyonlar.php';
if (isset($_POST['ayarkaydet'])) {
	$sorgu=$db->prepare("UPDATE ayarlar SET 
		site_baslik=:site_baslik,
		site_aciklama=:site_aciklama,
		site_link=:site_link,
		site_sahip_mail=:site_sahip_mail,
		site_mail_host=:site_mail_host,
		site_mail_mail=:site_mail_mail,
		site_mail_port=:site_mail_port,
		site_mail_sifre=:site_mail_sifre WHERE id=1
		");

	$sonuc=$sorgu->execute(array(
		'site_baslik' => $_POST['site_baslik'],
		'site_aciklama' => $_POST['site_aciklama'],
		'site_link' => $_POST['site_link'],
		'site_sahip_mail' => $_POST['site_sahip_mail'],
		'site_mail_host' => $_POST['site_mail_host'],
		'site_mail_mail' => $_POST['site_mail_mail'],
		'site_mail_port' => $_POST['site_mail_port'],
		'site_mail_sifre' => $_POST['site_mail_sifre']
	));

	if ($_FILES['site_logo']['error']=="0") {
		$gecici_isim=$_FILES['site_logo']['tmp_name'];
		$dosya_ismi=rand(100000,999999).$_FILES['site_logo']['name'];
		move_uploaded_file($gecici_isim,"../dosyalar/$dosya_ismi");

		$sorgu=$db->prepare("UPDATE ayarlar SET 
			site_logo=:site_logo WHERE id=1
			");

		$sonuc=$sorgu->execute(array(
			'site_logo' => $dosya_ismi,

		));
	}

	if ($sonuc) {
		header("location:../ayarlar.php?durum=ok");
	} else {
		header("location:../ayarlar.php?durum=no");
	}
	exit;
}


/********************************************************/

if (isset($_POST['oturumacma'])) {
	$sorgu=$db->prepare("SELECT * FROM kullanicilar WHERE kul_mail=:kul_mail AND kul_sifre=:kul_sifre");
	$sorgu->execute(array(
		'kul_mail' => $_POST['kul_mail'],
		'kul_sifre' => md5($_POST['kul_sifre'])
	));
	$sonuc=$sorgu->rowcount();
	$kullanici=$sorgu->fetch(PDO::FETCH_ASSOC);

	if ($sonuc==0) {
		header("location:../index.php?durum=no");
	} else {
		$_SESSION['kul_isim'] = $kullanici['kul_isim'];
		$_SESSION['kul_mail'] = $kullanici['kul_mail'];
		$_SESSION['kul_id'] = $kullanici['kul_id'];
		$_SESSION['kul_yetki'] = $kullanici['kul_yetki'];
		header("location:../index.php?durum=ok");
	}
	exit;
}


/*****************************************************************/

if (isset($_POST['kayitol'])) {
	$sorgu=$db->prepare("SELECT * FROM kullanicilar WHERE kul_mail=:kul_mail");
	$sorgu->execute(array(
		'kul_mail' => guvenlik($_POST['kul_mail'])
	));
	$sonuc=$sorgu->rowcount();
	if ($sonuc==0) {
		$sorgu=$db->prepare("INSERT INTO kullanicilar SET
			kul_isim=:kul_isim,
			kul_mail=:kul_mail,
			kul_sifre=:kul_sifre
			");
		$sonuc=$sorgu->execute(array(
			'kul_isim' => guvenlik($_POST['kul_isim']),
			'kul_mail' => guvenlik($_POST['kul_mail']),
			'kul_sifre' => md5($_POST['kul_sifre'])
		));


		if ($sonuc) {
			echo '{"sonuc":"ok"}';
		} else {
			echo '{"sonuc":"no"}';
		}
		
	} else {
		echo '{"sonuc":"mailalindi"}';
	}
	exit;

}

/********************************************************/

if (isset($_POST['profilkaydet'])) {
	$sorgu=$db->prepare("UPDATE kullanicilar SET 
		kul_isim=:kul_isim WHERE kul_id=:kul_id
		");

	$sonuc=$sorgu->execute(array(
		'kul_isim' => guvenlik($_POST['kul_isim']),
		'kul_id' => $_SESSION['kul_id']
	));

	if (strlen($_POST['kul_sifre'])>0) {
		$sorgu=$db->prepare("UPDATE kullanicilar SET 
			kul_sifre=:kul_sifre WHERE kul_id=:kul_id
			");

		$sonuc=$sorgu->execute(array(
			'kul_sifre' => md5($_POST['kul_sifre']),
			'kul_id' => $_SESSION['kul_id']
		));
	}

	if ($sonuc) {
		header("location:../profil.php?durum=ok");
	} else {
		header("location:../profil.php?durum=no");
	}

}



/********************************************************/


if (isset($_POST['yapekle'])) {
	
	if (@$_POST['yap_mail_onay']==1) {
		$mailonay=1;
	} else {
		$mailonay=0;
	}

	$sorgu=$db->prepare("INSERT INTO yapilacaklar SET 
		yap_kul=:yap_kul,
		yap_baslik=:yap_baslik,
		yap_islem_tarih=:yap_islem_tarih,
		yap_mail_onay=:yap_mail_onay,
		yap_detay=:yap_detay
		");

	$sonuc=$sorgu->execute(array(
		'yap_kul' => $_SESSION['kul_id'],
		'yap_baslik' => guvenlik($_POST['yap_baslik']),
		'yap_islem_tarih' => guvenlik($_POST['yap_islem_tarih']),
		'yap_mail_onay' => $mailonay,
		'yap_detay' => guvenlik($_POST['yap_detay'])
	));


	if ($sonuc) {
		header("location:../yapilacaklar.php?durum=ok");
	} else {
		header("location:../yapilacaklar.php?durum=no");
	}
	exit;
}




/********************************************************/


if (isset($_POST['yapguncelle'])) {
	
	if ($_SESSION['kul_yetki']!=1) {
		$sorgu=$db->prepare("SELECT * FROM yapilacaklar WHERE yap_id=:yap_id AND yap_kul=:yap_kul");
		$sorgu->execute(array(
			'yap_id' => guvenlik($_POST['yap_id']),
			'yap_kul' => $_SESSION['kul_id']
		));
		$sonuc=$sorgu->rowcount();

		if ($sonuc==0) {
			header("location:../yapilacaklar.php?durum=hata");
			exit;
		}
	}


	if (@$_POST['yap_mail_onay']==1) {
		$mailonay=1;
	} else {
		$mailonay=0;
	}

	$sorgu=$db->prepare("UPDATE yapilacaklar SET 
		yap_kul=:yap_kul,
		yap_baslik=:yap_baslik,
		yap_islem_tarih=:yap_islem_tarih,
		yap_mail_onay=:yap_mail_onay,
		yap_detay=:yap_detay WHERE yap_id=:yap_id
		");

	$sonuc=$sorgu->execute(array(
		'yap_kul' => $_SESSION['kul_id'],
		'yap_baslik' => guvenlik($_POST['yap_baslik']),
		'yap_islem_tarih' => guvenlik(str_replace("T", " ", $_POST['yap_islem_tarih'])),
		'yap_mail_onay' => $mailonay,
		'yap_detay' => guvenlik($_POST['yap_detay']),
		'yap_id' => guvenlik($_POST['yap_id'])
	));


	if ($sonuc) {
		header("location:../yapilacaklar.php?durum=ok");
	} else {
		header("location:../yapilacaklar.php?durum=no");
	}
	exit;
}


/********************************************************/


if (isset($_POST['yapsilme'])) {
	
	if ($_SESSION['kul_yetki']!=1) {
		$sorgu=$db->prepare("SELECT * FROM yapilacaklar WHERE yap_id=:yap_id AND yap_kul=:yap_kul");
		$sorgu->execute(array(
			'yap_id' => guvenlik($_POST['yap_id']),
			'yap_kul' => $_SESSION['kul_id']
		));
		$sonuc=$sorgu->rowcount();

		if ($sonuc==0) {
			header("location:../yapilacaklar.php?durum=hata");
			exit;
		}
	}

	$sorgu=$db->prepare("DELETE FROM yapilacaklar WHERE yap_id=:yap_id");
	$sonuc=$sorgu->execute(array(
		'yap_id' => guvenlik($_POST['yap_id'])
	));

	if ($sonuc) {
		header("location:../yapilacaklar.php?durum=ok");
	} else {
		header("location:../yapilacaklar.php?durum=no");
	}
	exit;

}


?>