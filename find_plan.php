<?php $bodyClass="find_plan"; include "includes/head.php";

if(isset($_GET["search"])){
  $plans = $pb->searchPlans($_GET["search"], $_GET["plan"]);
}else{
  $plans = $pb->getPlans();
}

function calc_attribute_in_array($array, $prop, $func) {
  $result = array_map(function($o) use($prop) {
                          return $o->$prop;
                      },
                      $array);

  if(function_exists($func)) {
      return $func($result);
  }
  return false;
}

$max = calc_attribute_in_array($plans, 'field_pd', 'max');
$min = calc_attribute_in_array($plans, 'field_pd', 'min');

?>
<script>
  let searchValue = '<?php echo isset($_GET["search"]) ? $_GET["search"] : "null"; ?>';
  let maxValue = '<?php echo isset($_GET["plan"]) ? $_GET["plan"] : "null"; ?>';

</script>

<body class="find_plan">
  <div class="part">
    <div class="left">
      <small class="ms500">Resultados de la búsqueda para</small>
      <?php if(isset($_GET["search"])){
          echo '<h1 class="ms900">'.$_GET["search"].'</h1>';
        }else{ ?>
      <h1 class="ms900">Encuentra tu plan en Bogotá</h1>
      <?php } ?>
    </div>
    <img src="<?=$project_base?>images/logo_pb.svg" alt="logopb" />
  </div>
  <main>
    <aside class="left filters">
      <button class="fw500" id="resetfilters">Limpiar filtros</button>
      <h2 class="ms900">Personaliza tu busqueda</h2>
      <details open id="persons">
        <summary><img src="<?=$project_base?>images/arrowDown.svg" alt="ArrowDown" />
          <h3 class="ms700">Cantidad de personas</h3>
        </summary>
        <ul>
          <li>
            <input type="checkbox" onChange="filterPlans(null, 1, null, null, '<?php echo isset($_GET[" search"]) ?
              $_GET["search"] : "null" ; ?>',
            <?=isset($_GET["plan"]) && $_GET["plan"] != "" ? $_GET["plan"] : 'null' ?>)" value="1" name="persons-1"
            id="persons-1" />
            <label class="filtercheck" for="persons-1" class="ms500">
              1 persona
            </label>
          </li>
          <li>
            <input type="checkbox" onChange="filterPlans(null, 1, null, null, '<?php echo isset($_GET[" search"]) ?
              $_GET["search"] : "null" ; ?>',
            <?=isset($_GET["plan"]) && $_GET["plan"] != "" ? $_GET["plan"] : 'null' ?>)" value="2" name="persons-2"
            id="persons-2" />
            <label class="filtercheck" for="persons-2" class="ms500">
              2 personas
            </label>
          </li>
          <li>
            <input type="checkbox" onChange="filterPlans(null, 1, null, null, '<?php echo isset($_GET[" search"]) ?
              $_GET["search"] : "null" ; ?>',
            <?=isset($_GET["plan"]) && $_GET["plan"] != "" ? $_GET["plan"] : 'null' ?>)" value="3" name="persons-3"
            id="persons-3" />
            <label class="filtercheck" for="persons-3" class="ms500">
              3 personas
            </label>
          </li>
          <li>
            <input type="checkbox" onChange="filterPlans(null, 1, null, null, '<?php echo isset($_GET[" search"]) ?
              $_GET["search"] : "null" ; ?>',
            <?=isset($_GET["plan"]) && $_GET["plan"] != "" ? $_GET["plan"] : 'null' ?>)" value="4" name="persons-4"
            id="persons-4" />
            <label class="filtercheck" for="persons-4" class="ms500">
              4 personas
            </label>
          </li>
          <li>
            <input type="checkbox" onChange="filterPlans(null, 1, null, null, '<?php echo isset($_GET[" search"]) ?
              $_GET["search"] : "null" ; ?>',
            <?=isset($_GET["plan"]) && $_GET["plan"] != "" ? $_GET["plan"] : 'null' ?>)" value="all" name="persons-all"
            id="persons-all" />
            <label class="filtercheck" for="persons-all" class="ms500">
              Grupos
            </label>
          </li>
        </ul>
      </details>
      <details open id="categories">
        <summary><img src="<?=$project_base?>images/arrowDown.svg" alt="ArrowDown" />
          <h3 class="ms700">Por categoría</h3>
        </summary>
        <ul>

        </ul>
      </details>
      <details open id="zones">
        <summary><img src="<?=$project_base?>images/arrowDown.svg" alt="ArrowDown" />
          <h3 class="ms700">Por Zona</h3>
        </summary>
        <ul>

        </ul>
      </details>
      <details open id="prices">
        <summary><img src="<?=$project_base?>images/arrowDown.svg" alt="ArrowDown" />
          <h3 class="ms700">Por precio no superior a</h3>
        </summary>
        <div class="cont">

          <div id="slider-range-max">
            <input type="range" aria-label="price-slider" id="price-slider" min="<?=$min?>" max="<?=$max?>" step="1000" value="<?=$min?>">
          </div>
          <div class="flex-values">
            <div class="min-value">$
              <?= number_format($min,0,",",".")?>
            </div>
            <input type="text" aria-label="amount" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
            <div class="max-value">$
              <?= number_format($max,0,",",".")?>
            </div>
          </div>
        </div>
      </details>
      <details open id="search">
        <summary><img src="<?=$project_base?>images/arrowDown.svg" alt="ArrowDown" />
          <h3 class="ms700">Buscar</h3>
        </summary>
        <div class="cont">
          <form action="/<?=$lang?>/plan-bogota/encuentra-tu-plan" method="GET" onsubmit="return validateForm(event)"
            id="searchForm" autocomplete="off">
            <span>
              <div class="input">
                <input onfocus="this.value=''" type="search" name="search" id="searchInput" value="<?=$_GET[" search"]?>"
                placeholder="<?=$pb->generalInfo->field_ui_2?>"
                />
              </div>
            </span>
          </form>
        </div>
      </details>
    </aside>
    <div class="find_plan-grid">

      <!-- <div class="find_plan-grid__promo">
          <img
            src="https://images.pexels.com/photos/210199/pexels-photo-210199.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
            alt="plan"
          />
          <div class="info">
            <div class="txt">
              <strong>LOREM IPSUM</strong>
              <small class="ms100">Dolor sit amet</small>
            </div>
            <div class="logo">
              <img src="<?=$project_base?>images/avianca.svg" alt="avianca" />
            </div>
          </div>
        </div>
        <div class="find_plan-grid__big_promo">
          <img
            src="https://images.pexels.com/photos/210199/pexels-photo-210199.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
            alt="plan"
          />
          <div class="info">
            <div class="txt">
              <strong>LOREM IPSUM</strong>
              <small class="ms100">Dolor sit amet</small>
            </div>
            <div class="logo">
              <img src="<?=$project_base?>images/avianca.svg" alt="avianca" />
            </div>
          </div>
        </div> -->
    </div>
  </main>
  <?php include 'includes/imports.php'?>
</body>

</html>