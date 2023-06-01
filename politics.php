<?php $bodyClass="faqbody"; include "includes/head.php"; $faqs = $pb->getFaqPB(); ?>
<body class="faqbody">
  <main>
    <div class="container">
      <a href="/<?=$_GET['lang']?><?=$project_base?>encuentra-tu-plan" class="ms900 uppercase btn-back btn"
        ><img src="<?=$project_base?>images/arrow_back_green.svg" alt="arrow_back" /> Volver
      </a>
      <section class="faqs">
        <div class="content">
            <?=$pb->generalInfo->field_policy?>

        </div>
    </section>
      </div>
  </main>

  <?php include 'includes/imports.php'?>

</body>
