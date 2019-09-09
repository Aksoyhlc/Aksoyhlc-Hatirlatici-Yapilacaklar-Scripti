<?php 
include'header.php' 
?>

<link rel="stylesheet" type="text/css" href="vendor/datatables/dataTables.bootstrap4.min.css">
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- DataTales Giriş -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Yapılacaklar</h6>
    </div>
    <div class="card-body">

      <div class="dropdown">
        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dışa Aktar
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="#">
            <button type="button" class="btn btn-dark icon-split btn-sm" onclick="tiklama('kopyala');">
             <span class="icon text-white-60">
              <i class="far fa-clipboard"></i>
            </span> 
            <span class="text">Kopyala</span>
          </button>
        </a>
        <a class="dropdown-item" href="#">
          <button type="button" class="btn btn-success icon-split btn-sm" onclick="tiklama('excel');">
           <span class="icon text-white-60">
            <i class="far fa-file-excel"></i>
          </span> 
          <span class="text">Excel</span>
        </button>
      </a>
      <a class="dropdown-item" href="#">
        <button type="button" class="btn btn-danger icon-split btn-sm" onclick="tiklama('pdf');">
         <span class="icon text-white-60">
          <i class="far fa-file-pdf"></i>
        </span> 
        <span class="text">PDF</span>
      </button>
    </a>
    <a class="dropdown-item" href="#">
      <button type="button" class="btn btn-info icon-split btn-sm" onclick="tiklama('csv');">
       <span class="icon text-white-60">
        <i class="fas fa-file-csv"></i>
      </span> 
      <span class="text">CSV</span>
    </button>
  </a>
</div>
</div>

<div class="table-responsive mt-3">
  <table class="table table-bordered" id="yapilacaklartablosu">
    <thead>
      <tr>
        <th>No</th>
        <?php 
        if (yetkikontrol()) { ?>
          <th>Ekleyen Kişi</th>
        <?php }  ?>
        <th>Başlık</th>
        <th>İşlem Tarihi</th>
        <th>İşlem Detayı</th>
        <th>Eklenme Tarihi</th>
        <th>Mail Durum</th>
        <th>İşlemler</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $sayi=0;
      if (yetkikontrol()) {
        $sorgu=$db->prepare("SELECT * FROM yapilacaklar LEFT JOIN kullanicilar ON yapilacaklar.yap_kul=kullanicilar.kul_id ORDER BY yap_id DESC");
      } else {
       $sorgu=$db->prepare("SELECT * FROM yapilacaklar WHERE yap_kul={$_SESSION['kul_id']} ORDER BY yap_id DESC");
     }
  
     $sorgu->execute();
     while ($yapilacaklar=$sorgu->fetch(PDO::FETCH_ASSOC)) { 
      $sayi++;
      ?>
      <tr>
        <td><?php echo $sayi; ?></td>
        <?php 
        if (yetkikontrol()) { ?>
         <td><?php echo $yapilacaklar['kul_isim']; ?></td>
       <?php } ?>
       <td><?php echo $yapilacaklar['yap_baslik']; ?></td>
       <td><?php echo $yapilacaklar['yap_islem_tarih']; ?></td>
       <td><?php echo mb_substr($yapilacaklar['yap_detay'], 0,50); ?></td>
       <td><?php echo $yapilacaklar['yap_eklenme_tarih']; ?></td>
       <td><?php 
       if ($yapilacaklar['yap_mail_durum']==0) {
        echo "<span class='text-danger'>Gönderilmedi</span>";
      } else {
       echo "<span class='text-success'>Gönderildi</span>";
     }
     ?></td>
     <td>
      <div class="row justify-content-center">
        <form action="yap-duzenle.php" method="POST" accept-charset="utf-8">
          <input type="hidden" name="yap_id" value="<?php echo $yapilacaklar['yap_id']?>">
          <button type="submit" name="duzenleme" class="btn btn-success btn-sm btn-icon-split">
            <span class="icon text-white-60">
              <i class="fas fa-edit"></i>
            </span>
          </button>
        </form>
        <form class="mx-1" action="islemler/ajax.php" method="POST" accept-charset="utf-8">
          <input type="hidden" name="yap_id" value="<?php echo $yapilacaklar['yap_id']?>">
          <button type="submit" name="yapsilme" class="btn btn-danger btn-sm btn-icon-split">
            <span class="icon text-white-60">
              <i class="fas fa-trash"></i>
            </span>
          </button>
        </form>
        <form action="yapilacak.php" method="POST" accept-charset="utf-8">
          <input type="hidden" name="yap_id" value="<?php echo $yapilacaklar['yap_id']?>">
          <button type="submit" name="duzenleme" class="btn btn-primary btn-sm btn-icon-split">
            <span class="icon text-white-60">
              <i class="fas fa-eye"></i>
            </span>
          </button>
        </form>


      </div>
    </td>
  </tr>
<?php } ?>            
</tbody>
</table>
</div>
</div>
</div>
<!--Datatables çıkış-->
</div>


<?php include'footer.php' ?>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="vendor/datatables/dataTables.buttons.min.js"></script>
<script src="vendor/datatables/buttons.flash.min.js"></script>
<script src="vendor/datatables/buttons.html5.min.js"></script>
<script src="vendor/datatables/buttons.print.min.js"></script>
<script src="vendor/datatables/jszip.min.js"></script>
<script src="vendor/datatables/pdfmake.min.js"></script>
<script src="vendor/datatables/vfs_fonts.js"></script>

<script>
  $("#yapilacaklartablosu").DataTable({
    dom: "<'row '<'col-md-6'l><'col-md-6'f><'col-md-4 d-none d-print-block'B>>rtip",
    buttons: [
    {
      extend: 'copyHtml5', 
      className: 'kopyalama-buton'
    },
    {
      extend: 'excelHtml5', 
      className: 'excel-buton'
    },
    {
     extend: 'pdfHtml5',
     className: 'pdf-buton'
   },
   {
    extend: 'csvHtml5',
    className: 'csv-buton'
  }
  ]
});


  function tiklama(islem){
    switch (islem){
      case "excel":
      $(".excel-buton").trigger("click");
      break;
      case "kopyala":
      $(".kopyalama-buton").trigger("click");
      break;
      case "pdf":
      $(".pdf-buton").trigger("click");
      break;
      case "csv":
      $(".csv-buton").trigger("click");
      break;
    }
  }

</script>

<?php 
if (@$_GET['durum']=="ok") { ?>
  <script type="text/javascript">
   Swal.fire({
    type: 'success',
    title: 'İşlem Başarılı',
    text: 'İşleminiz Başarıyla Gerçekleştirildi',
    confirmButtonText: "Tamam"
  })
</script>
<?php } ?>


<?php 
if (@$_GET['durum']=="no") { ?>
  <script type="text/javascript">
   Swal.fire({
    type: 'error',
    title: 'Hata',
    text: 'İşleminiz Başarısız Oldu Lütfen Tekrar Deneyin',
    confirmButtonText: "Tamam"
  })
</script>
<?php } ?>


<?php 
if (@$_GET['durum']=="ok") { ?>
  <script type="text/javascript">
   Swal.fire({
    type: 'success',
     title: 'İşlem Başarılı',
     text: 'İşleminiz Başarıyla Gerçekleştirildi',
     confirmButtonText: "Tamam"
   })
 </script>
 <?php } ?>


 <?php 
if (@$_GET['durum']=="no") { ?>
  <script type="text/javascript">
   Swal.fire({
    type: 'error',
     title: 'Hata',
     text: 'İşleminiz Başarısız Oldu Lütfen Tekrar Deneyin',
     confirmButtonText: "Tamam"
   })
 </script>
 <?php } ?>