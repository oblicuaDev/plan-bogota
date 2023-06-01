
<?php if (file_exists("includes/bogota.php")) { ?>
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
  <script src="js/jquery-ui-1.13.1.custom/jquery-ui.min.js?v=<?=time();?>"></script>
  <?php }else{
    include '../includes/footer.php';
  } ?> 

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="<?=$project_base?>js/purify.min.js?v=<?=time();?>"></script>
<script src="<?=$project_base?>js/main.js?v=<?=time();?>"></script>