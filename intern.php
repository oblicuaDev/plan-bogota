<?php $bodyClass="intern"; include "includes/head.php"; $plan = $pb->getPlans($_GET["planid"]); $company =  $pb->getCompany($plan->field_pb_oferta_empresa); ?>
<body class="intern">
  <main>
    <img src="<?=$plan->field_img != "" ? $plan->field_img : $plan->field_pb_oferta_img_listado?>" alt="Imagen Banner" id="mi-imagen">
    <div
    id="container"
      class="intern-banner"
      style="
        background-image: url(<?=$plan->field_img != "" ? $plan->field_img : $plan->field_pb_oferta_img_listado?>);
      "
    >
      <div class="content">
        <a href="/<?=$_GET['lang']?><?=$project_base?>encuentra-tu-plan" class="ms900 uppercase btn-back btn"
          ><img src="<?=$project_base?>images/arrow_back.svg" alt="arrow_back" /> Volver
        </a>
        <div class="info">
         
          <strong class="ms900"
            ><?=$plan->title?></strong
          >
          <div class="prices">
            <p class="prices-discount ms500">$<?=number_format($plan->field_pa,0,",",".")?></p>
            <p class="prices-total ms900">$<?=number_format($plan->field_pd,0,",",".")?></p>
          </div>
          <a href="#moreInfo" class="ms900 uppercase"> Más información </a>
        </div>
      </div>
    </div>
    }
    <div class="container">
      <a href="/<?=$_GET['lang']?><?=$project_base?>encuentra-tu-plan" class="ms900 uppercase btn-back btn"
        ><img src="<?=$project_base?>images/arrow_back_green.svg" alt="arrow_back" /> Volver
      </a>
      <div class="all-info" id="moreInfo">
        <div class="gallery">
          <img
            loading="lazy"
            src="https://picsum.photos/20/20"
            data-src="<?=$plan->field_gal_1?>"
            alt="gallery"
            class="lazyload"
            id="principal_img"
          />
          <ul class="gallery_dot">
            <?php for ($i=1; $i < 6; $i++) { if($plan->{"field_gal_". $i} != ""){ ?>
              <li class="active">
                <img
                  loading="lazy"
                  src="https://picsum.photos/20/20"
                  data-src="<?=$plan->{"field_gal_". $i}?>"
                  alt="gallery"
                  class="lazyload"
                />
              </li>
              <?php }} ?>
          </ul>
        </div>
        <div class="description">
          <h3 class="categorie ms700"><?=$plan->field_categoria_comercial_1?></h3>
          <h2 class="ms900">
          <?=$plan->title?>
          </h2>
          <div class="companyCont">
            <?=$company->field_pb_empresa_logo != "" ? '<img src="'.$company->field_pb_empresa_logo.'" alt="'.$company->field_pb_empresa_titulo.'" class="logoCompany"/>' : "" ?>
            <div class="fxcol">
              <span class="company ms500"><?=$company->field_pb_empresa_titulo ?></span>
              <small>Empresa que te prestara el servicio</small>
            </div>

          </div>
         
          <!-- <p class="title-certificates ms900">Certiﬁcaciones</p> -->
          <!-- <ul class="certificates">
            <li>
              <img src="<?=$project_base?>images/cer1.png" alt="cer1" />
            </li>
            <li>
              <img src="<?=$project_base?>images/cer2.png" alt="cer2" />
            </li>
            <li>
              <img src="<?=$project_base?>images/cer3.png" alt="cer3" />
            </li>
            <li>
              <img src="<?=$project_base?>images/cer4.png" alt="cer4" />
            </li>
          </ul> -->
          <p class="address ms900">
            <img src="<?=$project_base?>images/map.svg" alt="map" /><?=$plan->field_pb_oferta_direccion?>
          </p>
          <div class="ranking">
            <span>Esta oferta está muy bien caliﬁcada por los usuarios</span>
            <ul>
              <li class="ranking-val1"></li>
              <li class="ranking-val2"></li>
              <li class="ranking-val3"></li>
              <li class="ranking-val4"></li>
              <li class="ranking-val5 active"></li>
            </ul>
          </div>
          <div class="texto">
          <?=$plan->body?>

        </div>
        <button class="leer-mas ms500">Leer más</button>
          <p class="persons">578 personas han reservado</p>
          
          <div class="reserva ms900">
            Solo quedan 2 reservas con este precio ¡Apúrate a reservar!
          </div>
          <div class="prices">
            <p class="prices-discount ms700">$<?=number_format($plan->field_pa,0,",",".")?></p>
            <p class="prices-total ms900">$<?=number_format($plan->field_pd,0,",",".")?></p>
          </div>
          <div class="flex">
            <p>Personas incluidas en la reserva:</p>
            <div class="c-select">
              <select name="plan" id="plan" class="ms700" onchange="priceSet(this.value, <?=$plan->field_pd?>)">
              <?php 
              for ($i=isset($plan->field_min_people) && $plan->field_min_people != "" ? intval($plan->field_min_people) : 1; $i <= intval($plan->field_maxpeople); $i++) { ?>
                <option class="ms700" value="<?=$i?>"><?=$i?></option>
              <?php } ?>
              </select>
              <div class="c-arrow">
                <img src="<?=$project_base?>images/arrow-select.svg" alt="arrow-select" />
              </div>
            </div>
          </div>
          <a href="#reservar" class="btn btn-reserva ms900 uppercase">Reserva gratis ahora</a>
        </div>
      </div>
    </div>
    <div class="boxes form" id="reservar">
      <div
      class="right"
      style="
      background-image: url(<?=$plan->field_img != "" ? $plan->field_img : $plan->field_pb_oferta_img_listado?>);
    "
  >
</div>
<div class="left">
  <h2 class="ms500">Reservando</h2>
  <h1 class="ms900"><?=$plan->title?></h1>
  <p class="ms700">
    Solo quedan dos reservas con este precio ¡Apúrate a reservar!
  </p>
  <div class="prices">
    <p class="prices-discount ms500">$<?=number_format($plan->field_pa,0,",",".")?></p>
    <p class="prices-total ms900">$<?=number_format($plan->field_pd,0,",",".")?></p>
    <div class="discount ms900">- <?=$plan->field_percent?>%</div>
  </div>
  <form action="/plan-bogota/set/restPost.php" method="POST" id="planForm">
    <input type="text" placeholder="Nombre" class="ms500" id="uname" name="uname"  />
    <input type="email" placeholder="Correo" class="ms500" id="uemail" name="uemail" />
    <input type="tel" placeholder="Celular" class="ms500" id="uphone" name="uphone" />
    <input type="hidden" class="ms500" id="uprice" name="uprice" value="<?=$plan->field_pd?>" />
    <input type="hidden" class="ms500" id="uofertaid" name="uofertaid" value="<?=$plan->nid?>" />
    <input type="hidden" class="ms500" id="uoferta" name="uoferta" value="<?=$plan->title?>" />
    <input type="hidden" class="ms500" id="ucompanyid" name="ucompanyid" value="<?=$plan->field_pb_oferta_empresa?>" />
    <input type="hidden" class="ms500" id="ucompanyname" name="ucompanyname" value="<?=$company->field_pb_empresa_titulo?>" />

    <input type="hidden" class="ms500" id="ucompanyemail" name="ucompanyemail" value="<?=$company->field_pb_empresa_email?>" />
    <input type="hidden" class="ms500" id="ucompanyphone" name="ucompanyphone" value="<?=$company->field_pb_empresa_telefono?>" />
    <input type="hidden" class="ms500" id="ucompanylink" name="ucompanylink" value="<?=$company->field_pb_empresa_direccion?>" />

    <input type="hidden" class="ms500" id="ocategoryid" name="ocategoryid" value="<?=$plan->field_segment != "" ? $plan->field_segment : 0?>" />
    <input type="hidden" class="ms500" id="ocategory" name="ocategory" value="<?=$plan->field_segment_1?>" />
    <input type="hidden" class="ms500" id="ccategoryid" name="ccategoryid" value="<?=$company->field_segment != "" ? $company->field_segment : 0 ?>" />
    <input type="hidden" class="ms500" id="ccategory" name="ccategory" value="<?=$company->field_segment_1?>" />
    <input type="hidden" class="ms500" id="numberPersons" name="numberPersons" value="1" />
    <div class="politics_checkbox">
      <input type="checkbox" name="politics" id="politics" checked />
      <span class="politics_checkbox_mark"></span>
      <label for="politics"
        >Acepto los
        <a href="/<?=$lang?>/plan-bogota/politica-tratamiento-datos-personales" target="_blank">términos y condiciones.</a></label
      >
    </div>
    <button type="submit" class="ms900">RESERVA GRATIS AHORA</button>
  </form>
</div>
</div>
    <!-- <div class="reviews">
      <div class="container">
        <div class="title">
          <h3 class="ms900">¿Qué dicen otras personas sobre esta oferta?</h3>
          <div class="stars">
            <img src="<?=$project_base?>images/star.png" alt="stars" />
            <img src="<?=$project_base?>images/star.png" alt="stars" />
            <img src="<?=$project_base?>images/star.png" alt="stars" />
            <img src="<?=$project_base?>images/star.png" alt="stars" />
            <img src="<?=$project_base?>images/star.png" alt="stars" />
          </div>
        </div>
        <div class="c-select">
          <select name="plan" id="plan" class="ms700">
            <option class="ms700" value="1">Filtrar por puntuación</option>
            <option class="ms700" value="1">1</option>
            <option class="ms700" value="2">2</option>
            <option class="ms700" value="3">3</option>
            <option class="ms700" value="4">4</option>
          </select>
          <div class="c-arrow">
            <img src="<?=$project_base?>images/arrow-select.svg" alt="arrow-select" />
          </div>
        </div>
        <ul class="reviews-grid">
          <li class="reviews-grid__item">
            <div class="stars">
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
            </div>
            <h5 class="ms900">Lorem ipsum dolor sit amet.</h5>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis
              ipsum voluptatibus perspiciatis esse consequuntur commodi iure?
              Nulla possimus alias vel dicta, libero, expedita, nihil assumenda
              minus corporis magni officiis ab. Lorem ipsum, dolor sit amet
              consectetur adipisicing elit. Ipsum, perferendis illum, corrupti
              eveniet vero tempore cumque quis nesciunt soluta temporibus enim.
              Ipsum ullam nulla hic dolor eaque laboriosam aperiam excepturi!
            </p>
          </li>
          <li class="reviews-grid__item">
            <div class="stars">
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
            </div>
            <h5 class="ms900">Lorem ipsum dolor sit amet.</h5>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis
              ipsum voluptatibus perspiciatis esse consequuntur commodi iure?
              Nulla possimus alias vel dicta, libero, expedita, nihil assumenda
              minus corporis magni officiis ab. Lorem ipsum, dolor sit amet
              consectetur adipisicing elit. Ipsum, perferendis illum, corrupti
              eveniet vero tempore cumque quis nesciunt soluta temporibus enim.
              Ipsum ullam nulla hic dolor eaque laboriosam aperiam excepturi!
            </p>
          </li>
          <li class="reviews-grid__item">
            <div class="stars">
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
            </div>
            <h5 class="ms900">Lorem ipsum dolor sit amet.</h5>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis
              ipsum voluptatibus perspiciatis esse consequuntur commodi iure?
              Nulla possimus alias vel dicta, libero, expedita, nihil assumenda
              minus corporis magni officiis ab. Lorem ipsum, dolor sit amet
              consectetur adipisicing elit. Ipsum, perferendis illum, corrupti
              eveniet vero tempore cumque quis nesciunt soluta temporibus enim.
              Ipsum ullam nulla hic dolor eaque laboriosam aperiam excepturi!
            </p>
          </li>
          <li class="reviews-grid__item">
            <div class="stars">
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
              <img src="<?=$project_base?>images/star.png" alt="stars" />
            </div>
            <h5 class="ms900">Lorem ipsum dolor sit amet.</h5>
            <p>
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis
              ipsum voluptatibus perspiciatis esse consequuntur commodi iure?
              Nulla possimus alias vel dicta, libero, expedita, nihil assumenda
              minus corporis magni officiis ab. Lorem ipsum, dolor sit amet
              consectetur adipisicing elit. Ipsum, perferendis illum, corrupti
              eveniet vero tempore cumque quis nesciunt soluta temporibus enim.
              Ipsum ullam nulla hic dolor eaque laboriosam aperiam excepturi!
            </p>
          </li>
        </ul>
        <button id="loadmore" type="button">
          <img src="<?=$project_base?>images/more.svg" alt="more" />
        </button>
      </div>
    </div> -->
  </main>

  <?php include 'includes/imports.php'?>
<script>
  function priceSet(value, pd){
    document.querySelector('#numberPersons').value = value; 
    document.querySelector('#uprice').value = pd * value; 
    document.querySelectorAll('.prices-total').forEach(element => {
      element.innerHTML = `$${number_format(pd * value,0,'.','.')}`
    });
  }
  const container = document.getElementById("container");
  const imagen = document.getElementById("mi-imagen");
  if(imagen.width <1600){
    container.style.backgroundImage = "";
  }

  // Cargar imagen
  imagen.onload = function () {
    // Crear un canvas
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    
    canvas.width = imagen.width;
    canvas.height = imagen.height;

    // Dibujar imagen en el canvas
    ctx.drawImage(imagen, 0, 0);

    // Obtener los datos de píxeles del canvas
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixels = imageData.data;

    // Calcular el color predominante
    let r = 0,
      g = 0,
      b = 0;
    for (let i = 0; i < pixels.length; i += 4) {
      r += pixels[i];
      g += pixels[i + 1];
      b += pixels[i + 2];
    }
    const promedioR = Math.round(r / (pixels.length / 4));
    const promedioG = Math.round(g / (pixels.length / 4));
    const promedioB = Math.round(b / (pixels.length / 4));
    const colorPredominante = `rgb(${promedioR}, ${promedioG}, ${promedioB})`;

    // Establecer el color de fondo
    container.style.backgroundColor = colorPredominante;
  };

  // Cargar la imagen
  imagen.src = "<?=$plan->field_img != "" ? $plan->field_img : $plan->field_pb_oferta_img_listado?>";
</script>
</body>
