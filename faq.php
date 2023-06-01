<?php $bodyClass="faqbody"; include "includes/head.php"; $faqs = $pb->getFaqPB(); ?>
<body class="faqbody">
  <main>
    <div class="container">
      <a href="/<?=$_GET['lang']?><?=$project_base?>encuentra-tu-plan" class="ms900 uppercase btn-back btn"
        ><img src="<?=$project_base?>images/arrow_back_green.svg" alt="arrow_back" /> Volver
      </a>
      <section class="faqs">
        <h3>Preguntas Frecuentes</h3>
        <div class="accordion green">
            <?php for ($i=0; $i < count($faqs); $i++) { $faq = $faqs[$i];  ?>
                <h4><?=$faq->title?></h4>
                <div><?=$faq->body?></div>
            <?php } ?>
        </div>
        </div>
    </section>
      </div>
  </main>

  <?php include 'includes/imports.php'?>

</body>
