<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>SPBS - Catálogo</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/assets/img/spbs.png" rel="icon">
  <link href="assets/assets/img/spbs.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <main id="main">

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="searching">
        <p>Buscar:</p>
        <input type="text" class=" form-control inputSearch" id="searchInput" placeholder="Buscar por nombre" name="" id="">
      </div>
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <div class="container-fluid container-xl d-flex align-items-center justify-content-lg-between">
            <a href="/inicio" class="book-a-table-btn scrollto d-none d-lg-flex">Volver a la página de inicio</a>
          </div><br><br>
          <h2>Catálogo</h2>
          <p>Catálogo</p>
        </div>

        <!-- Filtros con checkboxes -->
        <div id="filterOptions" class="d-flex justify-content-center">
          <label class="container">Pestañas
            <input type="checkbox" checked="checked" name="filter" value="Pestañas">
            <span class="checkmark"></span>
          </label>
          <label class="container">Pinzas
            <input type="checkbox" checked="checked" name="filter" value="Pinzas">
            <span class="checkmark"></span>
          </label>
          <label class="container">Shampoo
            <input type="checkbox" checked="checked" name="filter" value="Shampoo">
            <span class="checkmark"></span>
          </label>
          <label class="container">Pegamento
            <input type="checkbox" checked="checked" name="filter" value="Pegamento">
            <span class="checkmark"></span>
          </label>
        </div>

        
        <!-- Estilos personalizados -->
        <style>
          .searching {
            background-color: rgb(61, 62, 63);
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto; /* Centrará la barra en la página */
          }

          .searching p {
            color: rgb(226, 226, 226);
            font-size: 16px;
            font-weight: bold;
            margin-right: 10px;
          }

          .inputSearch {
            flex-grow: 1;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s; 
            position: relative;
          }

          .inputSearch:focus {
            border-color: #007bff;
          }

          .inputSearch::placeholder {
            color: #999;
          }

          .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            user-select: none;
          }

          .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
          }

          .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
          }

          .container:hover input ~ .checkmark {
            background-color: #ccc;
          }

          .container input:checked ~ .checkmark {
            background-color: magenta;
          }

          .checkmark:after {
            content: "";
            position: absolute;
            display: none;
          }

          .container input:checked ~ .checkmark:after {
            display: block;
          }

          .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
          }
        </style>

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
          @foreach ($products as $product)
          <div class="col-lg-4 col-md-6 portfolio-item {{$product->type}}">
            <img class="card-img-top" src="{{$product->image_1}}" alt="{{$product->image_1}}" />
            <div class="portfolio-info">
              <h4>{{$product->name}}</h4>
              <p>$ {{$product->price}}</p><br>
              <a class="book-a-table-btn" href="/cat/detail/{{$product->id}}" title="More Details">Ver más</a><br><br>
              <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $product->id }}" name="id">
                <input type="hidden" value="{{ $product->name }}" name="name">
                <input type="hidden" value="{{ $product->price }}" name="price">
                <input type="hidden" value="{{ $product->image_1 }}" name="image_1">
                <input type="hidden" value="1" name="quantity">
                <button class="book-a-table-btn" style="color: magenta">Comprar</button>
              </form>
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </section><!-- End Portfolio Section -->

    <script>
      function filterProducts() {
        const checkboxes = document.querySelectorAll('input[name="filter"]');
        const selectedTypes = Array.from(checkboxes)
          .filter(checkbox => checkbox.checked)
          .map(checkbox => checkbox.value);
          
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
    
        // Oculta todos los elementos
        const portfolioItems = document.querySelectorAll('.portfolio-item');
        portfolioItems.forEach(item => {
          item.style.display = 'none';
        });
    
        // Muestra solo los elementos seleccionados que coinciden con el nombre buscado
        selectedTypes.forEach(type => {
          const selectedItems = document.querySelectorAll(`.portfolio-item.${type}`);
          selectedItems.forEach(item => {
            const productName = item.querySelector('h4').textContent.toLowerCase();
            if (productName.includes(searchInput)) {
              item.style.display = 'block';
            }
          });
        });
      }
    
      // Asocia la función al cambio en los checkboxes y en el campo de búsqueda
      document.querySelectorAll('input[name="filter"]').forEach(checkbox => {
        checkbox.addEventListener('change', filterProducts);
      });
    
      document.getElementById('searchInput').addEventListener('input', filterProducts);
    </script>
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="footer-info">
              <h2>spbs</h2>
              <p>
                C. Independencia 55, Centro <br>
                44100 Guadalajara, Jal<br><br>
                <strong>Phone:</strong> +1 5589 55488 55<br>
                <strong>Email:</strong> info@example.com<br>
              </p>
              <div class="social-links mt-3">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="/inicio">Página principal</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="inicio#about">Nosotros</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="/cat">Productos</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong><span>SPBS</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="#">SebasDesigns</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/assets/vendor/aos/aos.js"></script>
  <script src="assets/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/assets/js/main.js"></script>

</body>

</html>
