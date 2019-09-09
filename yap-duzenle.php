<?php require 'header.php'; 

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

$sorgu=$db->prepare("SELECT * FROM yapilacaklar WHERE yap_id=:yap_id");
$sorgu->execute(array(
	'yap_id' => guvenlik($_POST['yap_id'])
));
$yapilacak=$sorgu->fetch(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="font-weight-bold text-primary">Yapılacak Düzenleme Alanı</h5>
				</div>
				<div class="card-body">
					<form action="islemler/ajax.php" method="POST" accept-charset="utf-8">
						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-6 form-group">
								<label>Başlık</label>
								<input required="" type="text" name="yap_baslik" placeholder="Yapılacak İşlem Başlığı" class="form-control" value="<?php echo $yapilacak['yap_baslik'] ?>">
							</div>
						</div>

						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-8 form-group">
								<label>Yapılacak İşlem Tarihi</label>
								
								<div class="mb-2">
									<small>(Eğer Daha Önce Mail Gönderilmişse İşlem Tarihini İleri Alsanız Bile Tekrar Mail Gönderilmez)</small>
								</div>
								<input type="datetime-local" name="yap_islem_tarih" class="form-control" 
								value="<?php echo str_replace(" ","T",$yapilacak['yap_islem_tarih']) ?>">
							</div>
						</div>


						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-6 form-group">
								<div class="custom-control custom-checkbox mr-sm-2">
									<input <?php if($yapilacak['yap_mail_onay']==1){echo "checked";} ?> type="checkbox" class="custom-control-input" id="mailgondermeonay" name="yap_mail_onay" value="1">
									<label class="custom-control-label" for="mailgondermeonay">İşlem Tarihi Gelince Mail Gönderilsin</label>
								</div>
							</div>
						</div>


						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-11 form-group">
								<label>Yapılacak İşlem Detayı</label>
								<textarea id="editor" required="" name="yap_detay" class="form-control" style="height: 20rem" placeholder="Yapılacak İşlem Detayını Yazın"><?php echo $yapilacak['yap_detay'] ?></textarea>
							</div>
						</div>
						<input type="hidden" name="yap_id" value="<?php echo $_POST['yap_id'] ?>">

						<div class="text-center">
							<button type="submit" class="btn btn-primary" name="yapguncelle">Kaydet</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php require 'footer.php'; ?>
<script src="vendor/ckeditor/ckeditor.js"></script>
<script>
	CKEDITOR.replace("editor");
</script>