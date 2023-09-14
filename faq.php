<?php $bodyClass="pbfaqs"; include "includes/head.php"; $faqs = $pb->getFaqPB(); ?>
<main>
  <div class="container">
    <a href="/<?=$_GET['lang']?><?=$project_base?>encuentra-tu-plan" class="ms900 uppercase btn-back btn"><img
        src="<?=$project_base?>images/arrow_back_green.svg" alt="arrow_back" /> Volver
    </a>
    <section class="faq">
      <div class="accordion">
        <?php for ($i=0; $i < count($faqs); $i++) { $faq = $faqs[$i];  ?>
        <div class="accordion-item">
          <div class="accordion-header"><?=$faq->title?></div>
          <div class="accordion-content"><?=$faq->body?></div>
        </div>
        <?php } ?>
      </div>
    </section>
  </div>
</main>

<?php include 'includes/imports.php'?>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
      const header = item.querySelector('.accordion-header');
      const content = item.querySelector('.accordion-content');
      header.addEventListener('click', () => {
        let contentActive = document.querySelector('.accordion-content.active');
        if (contentActive) {
          contentActive.classList.remove('active');
          console.log(contentActive);
        }
        content.classList.toggle('active');
        contentActive = content;
      });
    });
  })
</script>