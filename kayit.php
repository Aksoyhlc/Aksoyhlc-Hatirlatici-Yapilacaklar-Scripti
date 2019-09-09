<?php include 'islemler/baglan.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $ayarcek['site_baslik']; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-md-8 mt-5">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-md-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Hoşgeldiniz</h1>
                  </div>
                  <form onsubmit="return false" id="kayitformu">
                    <div class="form-row d-flex justify-content-around">
                      <div class="col-md-6 form-group">
                        <label>İsminiz</label>
                        <input type="text" name="kul_isim" placeholder="İsminizi Girin" class="form-control">
                      </div>
                      <div class="col-md-6 form-group">
                        <label>Mail Adresiniz</label>
                        <input type="text" name="kul_mail" placeholder="Mail Adresiniz" class="form-control">
                      </div>
                    </div>
                    <div class="form-row d-flex justify-content-around">
                      <div class="col-md-6 form-group">
                        <label>Şifreniz</label>
                        <input type="password" name="kul_sifre" placeholder="Şifrenizi Girin" class="form-control not-sifresi">
                      </div>                
                      <div class="col-md-6 form-group">
                        <label>Şifreniz Tekrar Girin</label>
                        <input type="password" placeholder="Şifreniz Tekrar Girin" class="form-control not-sifresi-tekrar">
                      </div>    
                    </div>
                    <input class="d-none" type="submit" name="kayitol" value="gönder" id="gondermebutonu">
                    <div class="text-center">
                      <button type="button" class="btn btn-primary" id="kontrolbuton">Kayıt Ol</button>
                    </div>
                  </form>
                  <div class="text-center mt-3">
                    <h5><?php echo $ayarcek['site_baslik']; ?></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      $("#kontrolbuton").click(function () {
        metin1 = $(".not-sifresi").val();
        metin2 = $(".not-sifresi-tekrar").val();
        if (metin1!=metin2) {
          alert("Şifreler Aynı Değil")
        } else {
          $.ajax({
            url: 'islemler/ajax.php',
            type: 'POST',
            data: $("#kayitformu").serialize()+"&kayitol=kayitol",
            success:function (donenveri) {
              var gelen=JSON.parse(donenveri);
              var deger=gelen.sonuc;
              if (deger=="ok") {
                window.location="login.php";
              } else if (deger="mailalindi") {
                alert("Bu Mail Adresi Önceden ALınmış")
              } else {
                alert("Kayıt Başarısız")
              }
            }
          })
        }
      });

    });
  </script>

</body>

</html>
