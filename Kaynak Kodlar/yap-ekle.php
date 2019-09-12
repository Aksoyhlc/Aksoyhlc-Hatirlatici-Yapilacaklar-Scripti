<?php require 'header.php'; ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h5 class="font-weight-bold text-primary">Yapılacak Ekleme Alanı</h5>
				</div>
				<div class="card-body">
					<form action="islemler/ajax.php" method="POST" accept-charset="utf-8">
						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-6 form-group">
								<label>Başlık</label>
								<input required="" type="text" name="yap_baslik" placeholder="Yapılacak İşlem Başlığı" class="form-control">
							</div>
						</div>

						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-6 form-group">
								<label>Yapılacak İşlem Tarihi</label>
								<input type="datetime-local" name="yap_islem_tarih" class="form-control">
							</div>
						</div>


						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-6 form-group">
								<div class="custom-control custom-checkbox mr-sm-2">
									<input type="checkbox" class="custom-control-input" id="mailgondermeonay" name="yap_mail_onay" value="1">
									<label class="custom-control-label" for="mailgondermeonay">İşlem Tarihi Gelince Mail Gönderilsin</label>
								</div>
							</div>
						</div>


						<div class="form-row d-flex justify-content-center text-center">
							<div class="col-md-11 form-group">
								<label>Yapılacak İşlem Detayı</label>
								<textarea id="editor" required="" name="yap_detay" class="form-control" style="height: 20rem" placeholder="Yapılacak İşlem Detayını Yazın"></textarea>
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-primary" name="yapekle">Kaydet</button>
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