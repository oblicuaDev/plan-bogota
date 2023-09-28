<?php $bodyClass="home"; include "includes/head.php"; $plans = $pb->getRecommendPlans($pb->generalInfo->field_ofertas_recomendadas); ?>
  <body class="home">
    <main>
      <div
        class="home-banner"
        style="
          background-image: url(<?=$pb->absoluteURL($pb->generalInfo->field_home_img)?>);
        "
      >
        <div class="container">
          <form action="/<?=$lang?>/plan-bogota/encuentra-tu-plan" method="GET" onsubmit="return validateForm()" id="searchForm" autocomplete="off">
            <img src="images/logo_pb.svg" alt="plan_bogota" />
            <h2 class="ms900"><?=$pb->generalInfo->field_ui_1?></h2>
            <span>
              <div class="left">
                <div class="input">
                  <img src="images/lupa.svg" alt="lupa" />
                  <label for="search">
                    <input
                      type="search"
                      aria-labelledby="search"
                      name="search"
                      id="search"
                      placeholder="<?=$pb->generalInfo->field_ui_2?>"
                    />
                    </label>
                  <small>Este campo no puede estar vacio*</small>
                </div>
              </div>
              <div class="right">
                <div class="c-select">
                  <select name="plan" aria-label="plan" id="plan" class="ms700">
                    <option class="ms700" value="">¿Para cuántos?</option>
                    <option class="ms700" value="1">1 persona</option>
                    <option class="ms700" value="2">2 personas</option>
                    <option class="ms700" value="3">3 personas</option>
                    <option class="ms700" value="4">4 personas</option>
                  </select>
                  <div class="c-arrow">
                    <img src="images/arrow-select.svg" alt="arrow-select" />
                  </div>
                </div>

                <button type="submit" id="search-btn" class="ms900 uppercase">
                  <?=$pb->generalInfo->field_ui_2?>
                </button>
              </div>
            </span>
          </form>
        </div>
      </div>
      <section class="categoriessection">
        <div class="categories">
        </div>
      </section>
      <div class="recommendation">
        <div class="container">
          <h3 class="ms900"><?=$pb->generalInfo->field_ui_3?></h3>
          <div class="recommendation-grid">
            <?php for ($i=0; $i < count($plans); $i++) { $plan = $plans[$i]; ?>
              <a href="/<?=$lang?><?=$project_base?>plan/<?=$pb->get_alias($plan->title)?>-<?=$plan->nid?>" class="recommendation-grid__item">
              <div class="image">
                <img
                  src="<?= $plan->field_pb_oferta_img_listado?>"
                  alt="<?= $plan->title?>"
                />
              </div>
                <div class="info">
                  <div class="discount ms900">
                  <?= $plan->field_percent?>% <small class="ms500">DCTO</small>
                  </div>
                  <div class="prices">
                    <p class="prices-discount ms500">$<?= number_format($plan->field_pa,0,",",".")?></p>
                    <p class="prices-total ms900">$<?= number_format($plan->field_pd,0,",",".")?></p>
                  </div>
                  <strong class="ms900"
                    ><?= $plan->title?></strong
                  >
                  <p class="ms100">
                  <?= $plan->field_pb_oferta_desc_corta?>
                  
                  </p>
                  <small class="ms900 uppercase link"> <?=$pb->generalInfo->field_ui_7?> </small>
                </div>
              </a>
            <?php } ?>
          </div>
          <a href="/<?=$lang?><?=$project_base?>encuentra-tu-plan" class="btn alloffers uppercase ms900"
            ><?=$pb->generalInfo->field_ui_4?></a
          >
        </div>
      </div>
      <section class="stepssection">
        <h3 class="ms900">¿Cómo reservar?</h3>
        <div class="steps">
        <?php 
        $textos = explode('|', $pb->generalInfo->field_textos_como_reservar);
        for ($i=0; $i < count($textos); $i++) { 
          $texto = $textos[$i];
          ?>
          <article><?=$texto?></article>
          <?php
        }
        ?>

        </div>
      </section>
    </main>
    <?php include 'includes/imports.php'?>
  </body>
</html>
