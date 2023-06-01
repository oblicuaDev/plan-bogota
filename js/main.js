if ($(".gallery_dot").length) {
  $(".gallery_dot li").hover(function (e) {
    $(".gallery_dot li.active").removeClass("active");
    $(e.target).addClass("active");
    var src = $($(e.target).children("img")).data("src");
    $(".gallery #principal_img").attr("src", src);
  });
}

// LAZY LOADING IMAGES
function lazyImages() {
  if ("loading" in HTMLImageElement.prototype) {
    const images = document.querySelectorAll("img.lazyload");
    images.forEach((img) => {
      img.src = img.dataset.src;
    });
  } else {
    // Importamos dinámicamente la libreria `lazysizes`
    let script = document.createElement("script");
    script.async = true;
    script.src = "js/lazysizes.min.js";
    document.body.appendChild(script);
  }
}

lazyImages();

const fetchFiltersData = () => {
  const urls = [
    "get/tax.php?taxName=test_zona",
    "get/tax.php?taxName=categorias_comerciales_pb",
  ];
  const allRequests = urls.map(async (url) => {
    let plansResponse = await fetch(url);
    return plansResponse.json();
  });
  return Promise.all(allRequests);
};
filtersAll();
function filtersAll() {
  fetchFiltersData()
    .then((arrayOfResponses) => {
      //   ZONE ELEMENTS HOME
      const zones = arrayOfResponses[0];
      const pb_segments = arrayOfResponses[1];

      let listZones = document.querySelector("#zones ul");
      if (listZones) {
        listZones.innerHTML = "";
        zones.map((zone) => {
          listZones.innerHTML += `<li><input type="checkbox" value="${
            zone.tid
          }" name="zone-${zone.tid}" id="zone-${
            zone.tid
          }" onChange="filterPlans(${zone.tid}, null, null,${parseInt(
            $("#amount")
              .val()
              .replace(/\$|\.|,/g, "")
          )}, ${searchValue != "null" ? `'${searchValue}'` : null}, ${
            maxValue != "" ? maxValue : null
          })" /><label class="filtercheck" for="zone-${
            zone.tid
          }" class="ms500">${zone.name}</label></li>`;
        });
      }
      let listCategories = document.querySelector("#categories ul");
      if (listCategories) {
        listCategories.innerHTML = "";
        pb_segments.map((segment) => {
          listCategories.innerHTML += `<li><input type="checkbox" value="${
            segment.tid
          }" name="cat-${segment.tid}" id="cat-${
            segment.tid
          }" onChange="filterPlans(null, null, ${segment.tid},${parseInt(
            $("#amount")
              .val()
              .replace(/\$|\.|,/g, "")
          )},  ${searchValue != "null" ? `'${searchValue}'` : null},${
            maxValue != "" ? maxValue : null
          })" /><label class="filtercheck" for="cat-${
            segment.tid
          }" class="ms500">${segment.name}</label></li>`;
        });
      }
    })
    .then(() => {
      removeNoResponseFilters();
    });
}

let zonesArray = [];
let personsArray = [];
let segmentsArray = [];

function without(array, what) {
  return array.filter(function (element) {
    return element !== what;
  });
}

$("#resetfilters").click(function () {
  const urlSearchParams = new URLSearchParams(window.location.search);
  const searchParam = urlSearchParams.get("search");
  document
    .querySelectorAll("input:checked")
    .forEach((inp) => (inp.checked = false));
  zonesArray = [];
  personsArray = [];
  segmentsArray = [];
  if (searchParam) {
    urlSearchParams.set("search", searchParam);
    const newUrl =
      window.location.origin +
      window.location.pathname +
      "?" +
      urlSearchParams.toString();
    window.history.replaceState({ path: newUrl }, "", newUrl);
  }
  filterPlans(
    zonesArray,
    personsArray,
    segmentsArray,
    null,
    searchValue != "null" ? `'${searchValue}'` : null,
    null
  );
});

// Crear el slider al cargar la página
const items = document.querySelectorAll(".find_plan-grid__item");
let minPrice = Infinity;
let maxPrice = -Infinity;
items.forEach((item) => {
  const price = parseFloat(item.dataset.price);
  if (price < minPrice) {
    minPrice = price;
  }
  if (price > maxPrice) {
    maxPrice = price;
  }
});
$("#slider-range-max").slider({
  range: "max",
  min: minPrice,
  max: maxPrice,
  value: maxPrice,
  step: 1000,
  change: function (event, ui) {
    // filterPlans(null, null, null, ui.value, null, null);
  },
  slide: function (event, ui) {
    $("#amount").val(`$${number_format(ui.value, 0, ".", ".")}`);
  },
});
const filterPlans = (
  zones = null,
  persons = null,
  segments = null,
  price = null,
  search = null,
  max = null
) => {
  $("#preloader").fadeIn();
  if (zones != null) {
    if (zonesArray.includes(zones)) {
      zonesArray = without(zonesArray, zones);
    } else {
      zonesArray.push(zones);
    }
  }
  if (persons != null) {
    if (personsArray.includes(persons)) {
      personsArray = without(personsArray, persons);
    } else {
      personsArray.push(persons);
    }
  }
  if (segments != null) {
    if (segmentsArray.includes(segments)) {
      segmentsArray = without(segmentsArray, segments);
    } else {
      segmentsArray.push(segments);
    }
  }

  const isNumber = (n) => typeof n === "number" || n instanceof Number;

  let grid = document.querySelector(".find_plan-grid");
  if (grid) {
    document.querySelector(".find_plan-grid").classList.add("loading");
    grid.innerHTML = ``;
    let url = `get/filterplans.php?filter=1`;

    if (zonesArray.length > 0) {
      url += `&zones=${zonesArray.join("+")}`;
    }
    if (personsArray.length > 0) {
      url += `&persons=${personsArray.join("+")}`;
    }
    if (segmentsArray.length > 0) {
      url += `&segments=${segmentsArray.join("+")}`;
    }
    if (price > 0) {
      url += `&price=${price}`;
    }
    if (!!search && search != "null") {
      url += `&search=${search}`;
    }
    if (max && max != "null") {
      url += `&max=${max}`;
    }
    fetch(url)
      .then((res) => res.json())
      .then((data) => {
        let template;
        if (data.length > 0) {
          data.forEach((plan) => {
            template = `
         <a href="/${actualLang}/plan-bogota/plan/${get_alias(plan.title)}-${
              plan.nid
            }" class="find_plan-grid__item" 
          data-persons="${plan.field_maxpeople}" data-cat="${
              plan.field_categoria_comercial
            }" data-zone="${plan.field_pb_oferta_zona}">
          <div class="image">
          <img loading="lazy" class="lazyload" data-src="${
            plan.field_pb_oferta_img_listado
          }" src="https://via.placeholder.com/330x240" alt="${plan.title}"/>
          </div>
          
         <div class="info">
           <div class="lines">
             <div class="line1"></div>
             <div class="line2"></div>
           </div>
           ${
             plan.field_percent == 0
               ? ""
               : `<div class="discount ms900">
            ${plan.field_percent}% <small class="ms500">DCTO</small>
            </div>`
           }
           <div class="prices">
           ${
             !!plan.field_pa
               ? `
          <p class="prices-discount ms500">$${number_format(
            plan.field_pa,
            0,
            ".",
            "."
          )}</p>`
               : ""
           }
             <p class="prices-total ms900">$${number_format(
               plan.field_pd,
               0,
               ".",
               "."
             )}</p>
           </div>
           <strong class="ms900">${plan.title}</strong>
           <p class="ms100">${plan.field_pb_oferta_desc_corta}</p>
           <small class="link ms900 uppercase"> Ver oferta </small>
         </div>
       </a>`;
            grid.innerHTML += template;
          });
        } else {
          template = `<h2>No se encontraron resultados de la busqueda</h2>`;
          grid.innerHTML += template;
        }
      })
      .then(() => {
        grid.classList.remove("loading");
        removeNoResponseFilters();
      });
  }
};

if (document.querySelector(".find_plan-grid")) {
  filterPlans(
    null,
    null,
    null,
    null,
    searchValue != "null" ? `'${searchValue}'` : null,
    null
  );
}
function get_alias(str) {
  str = str.replace(/¡/g, "", str); //Signo de exclamación abierta.&iexcl;
  str = str.replace(/'/g, "", str); //Signo de exclamación abierta.&iexcl;
  str = str.replace(/!/g, "", str); //Signo de exclamación abierta.&iexcl;
  str = str.replace(/¢/g, "-", str); //Signo de centavo.&cent;
  str = str.replace(/£/g, "-", str); //Signo de libra esterlina.&pound;
  str = str.replace(/¤/g, "-", str); //Signo monetario.&curren;
  str = str.replace(/¥/g, "-", str); //Signo del yen.&yen;
  str = str.replace(/¦/g, "-", str); //Barra vertical partida.&brvbar;
  str = str.replace(/§/g, "-", str); //Signo de sección.&sect;
  str = str.replace("&", "-", str); //Diéresis.&uml;
  str = str.replace("amp;", "-", str); //Diéresis.&uml;
  str = str.replace(/¨/g, "-", str); //Diéresis.&uml;
  str = str.replace(/©/g, "-", str); //Signo de derecho de copia.&copy;
  str = str.replace(/ª/g, "-", str); //Indicador ordinal femenino.&ordf;
  str = str.replace(/«/g, "-", str); //Signo de comillas francesas de apertura.&laquo;
  str = str.replace(/¬/g, "-", str); //Signo de negación.&not;
  str = str.replace(/®/g, "-", str); //Signo de marca registrada.&reg;
  str = str.replace(/¯/g, "&-", str); //Macrón.&macr;
  str = str.replace(/°/g, "-", str); //Signo de grado.&deg;
  str = str.replace(/±/g, "-", str); //Signo de más-menos.&plusmn;
  str = str.replace(/²/g, "-", str); //Superíndice dos.&sup2;
  str = str.replace(/³/g, "-", str); //Superíndice tres.&sup3;
  str = str.replace(/´/g, "-", str); //Acento agudo.&acute;
  str = str.replace(/µ/g, "-", str); //Signo de micro.&micro;
  str = str.replace(/¶/g, "-", str); //Signo de calderón.&para;
  str = str.replace(/·/g, "-", str); //Punto centrado.&middot;
  str = str.replace(/¸/g, "-", str); //Cedilla.&cedil;
  str = str.replace(/¹/g, "-", str); //Superíndice 1.&sup1;
  str = str.replace(/º/g, "-", str); //Indicador ordinal masculino.&ordm;
  str = str.replace(/»/g, "-", str); //Signo de comillas francesas de cierre.&raquo;
  str = str.replace(/¼/g, "-", str); //Fracción vulgar de un cuarto.&frac14;
  str = str.replace(/½/g, "-", str); //Fracción vulgar de un medio.&frac12;
  str = str.replace(/¾/g, "-", str); //Fracción vulgar de tres cuartos.&frac34;
  str = str.replace(/¿/g, "-", str); //Signo de interrogación abierta.&iquest;
  str = str.replace(/×/g, "-", str); //Signo de multiplicación.&times;
  str = str.replace(/÷/g, "-", str); //Signo de división.&divide;
  str = str.replace(/À/g, "a", str); //A mayúscula con acento grave.&Agrave;
  str = str.replace(/Á/g, "a", str); //A mayúscula con acento agudo.&Aacute;
  str = str.replace(/Â/g, "a", str); //A mayúscula con circunflejo.&Acirc;
  str = str.replace(/Ã/g, "a", str); //A mayúscula con tilde.&Atilde;
  str = str.replace(/Ä/g, "a", str); //A mayúscula con diéresis.&Auml;
  str = str.replace(/Å/g, "a", str); //A mayúscula con círculo encima.&Aring;
  str = str.replace(/Æ/g, "a", str); //AE mayúscula.&AElig;
  str = str.replace(/Ç/g, "c", str); //C mayúscula con cedilla.&Ccedil;
  str = str.replace(/È/g, "e", str); //E mayúscula con acento grave.&Egrave;
  str = str.replace(/É/g, "e", str); //E mayúscula con acento agudo.&Eacute;
  str = str.replace(/Ê/g, "e", str); //E mayúscula con circunflejo.&Ecirc;
  str = str.replace(/Ë/g, "e", str); //E mayúscula con diéresis.&Euml;
  str = str.replace(/Ì/g, "i", str); //I mayúscula con acento grave.&Igrave;
  str = str.replace(/Í/g, "i", str); //I mayúscula con acento agudo.&Iacute;
  str = str.replace(/Î/g, "i", str); //I mayúscula con circunflejo.&Icirc;
  str = str.replace(/Ï/g, "i", str); //I mayúscula con diéresis.&Iuml;
  str = str.replace(/Ð/g, "d", str); //ETH mayúscula.&ETH;
  str = str.replace(/Ñ/g, "n", str); //N mayúscula con tilde.&Ntilde;
  str = str.replace(/Ò/g, "o", str); //O mayúscula con acento grave.&Ograve;
  str = str.replace(/Ó/g, "o", str); //O mayúscula con acento agudo.&Oacute;
  str = str.replace(/Ô/g, "o", str); //O mayúscula con circunflejo.&Ocirc;
  str = str.replace(/Õ/g, "o", str); //O mayúscula con tilde.&Otilde;
  str = str.replace(/Ö/g, "o", str); //O mayúscula con diéresis.&Ouml;
  str = str.replace(/Ø/g, "o", str); //O mayúscula con barra inclinada.&Oslash;
  str = str.replace(/Ù/g, "u", str); //U mayúscula con acento grave.&Ugrave;
  str = str.replace(/Ú/g, "u", str); //U mayúscula con acento agudo.&Uacute;
  str = str.replace(/Û/g, "u", str); //U mayúscula con circunflejo.&Ucirc;
  str = str.replace(/Ü/g, "u", str); //U mayúscula con diéresis.&Uuml;
  str = str.replace(/Ý/g, "y", str); //Y mayúscula con acento agudo.&Yacute;
  str = str.replace(/Þ/g, "b", str); //Thorn mayúscula.&THORN;
  str = str.replace(/ß/g, "b", str); //S aguda alemana.&szlig;
  str = str.replace(/à/g, "a", str); //a minúscula con acento grave.&agrave;
  str = str.replace(/á/g, "a", str); //a minúscula con acento agudo.&aacute;
  str = str.replace(/â/g, "a", str); //a minúscula con circunflejo.&acirc;
  str = str.replace(/ã/g, "a", str); //a minúscula con tilde.&atilde;
  str = str.replace(/ä/g, "a", str); //a minúscula con diéresis.&auml;
  str = str.replace(/å/g, "a", str); //a minúscula con círculo encima.&aring;
  str = str.replace(/æ/g, "a", str); //ae minúscula.&aelig;
  str = str.replace(/ç/g, "a", str); //c minúscula con cedilla.&ccedil;
  str = str.replace(/è/g, "e", str); //e minúscula con acento grave.&egrave;
  str = str.replace(/é/g, "e", str); //e minúscula con acento agudo.&eacute;
  str = str.replace(/ê/g, "e", str); //e minúscula con circunflejo.&ecirc;
  str = str.replace(/ë/g, "e", str); //e minúscula con diéresis.&euml;
  str = str.replace(/ì/g, "i", str); //i minúscula con acento grave.&igrave;
  str = str.replace(/í/g, "i", str); //i minúscula con acento agudo.&iacute;
  str = str.replace(/î/g, "i", str); //i minúscula con circunflejo.&icirc;
  str = str.replace(/ï/g, "i", str); //i minúscula con diéresis.&iuml;
  str = str.replace(/ð/g, "i", str); //eth minúscula.&eth;
  str = str.replace(/ñ/g, "n", str); //n minúscula con tilde.&ntilde;
  str = str.replace(/ò/g, "o", str); //o minúscula con acento grave.&ograve;
  str = str.replace(/ó/g, "o", str); //o minúscula con acento agudo.&oacute;
  str = str.replace(/ô/g, "o", str); //o minúscula con circunflejo.&ocirc;
  str = str.replace(/õ/g, "o", str); //o minúscula con tilde.&otilde;
  str = str.replace(/ö/g, "o", str); //o minúscula con diéresis.&ouml;
  str = str.replace(/ø/g, "o", str); //o minúscula con barra inclinada.&oslash;
  str = str.replace(/ù/g, "o", str); //u minúscula con acento grave.&ugrave;
  str = str.replace(/ú/g, "u", str); //u minúscula con acento agudo.&uacute;
  str = str.replace(/û/g, "u", str); //u minúscula con circunflejo.&ucirc;
  str = str.replace(/ü/g, "u", str); //u minúscula con diéresis.&uuml;
  str = str.replace(/ý/g, "y", str); //y minúscula con acento agudo.&yacute;
  str = str.replace(/þ/g, "b", str); //thorn minúscula.&thorn;
  str = str.replace(/ÿ/g, "y", str); //y minúscula con diéresis.&yuml;
  str = str.replace(/Œ/g, "d", str); //OE Mayúscula.&OElig;
  str = str.replace(/œ/g, "-", str); //oe minúscula.&oelig;
  str = str.replace(/Ÿ/g, "-", str); //Y mayúscula con diéresis.&Yuml;
  str = str.replace(/ˆ/g, "", str); //Acento circunflejo.&circ;
  str = str.replace(/˜/g, "", str); //Tilde.&tilde;
  str = str.replace(/–/g, "", str); //Guiún corto.&ndash;
  str = str.replace(/—/g, "", str); //Guiún largo.&mdash;
  str = str.replace(/'/g, "", str); //Comilla simple izquierda.&lsquo;
  str = str.replace(/'/g, "", str); //Comilla simple derecha.&rsquo;
  str = str.replace(/,/g, "", str); //Comilla simple inferior.&sbquo;
  str = str.replace(/"/g, "", str); //Comillas doble derecha.&rdquo;
  str = str.replace(/"/g, "", str); //Comillas doble inferior.&bdquo;
  str = str.replace(/†/g, "-", str); //Daga.&dagger;
  str = str.replace(/‡/g, "-", str); //Daga doble.&Dagger;
  str = str.replace(/…/g, "-", str); //Elipsis horizontal.&hellip;
  str = str.replace(/‰/g, "-", str); //Signo de por mil.&permil;
  str = str.replace(/‹/g, "-", str); //Signo izquierdo de una cita.&lsaquo;
  str = str.replace(/›/g, "-", str); //Signo derecho de una cita.&rsaquo;
  str = str.replace(/€/g, "-", str); //Euro.&euro;
  str = str.replace(/™/g, "-", str); //Marca registrada.&trade;
  str = str.replace(/ & /g, "-", str); //Marca registrada.&trade;
  str = str.replace("&f&", "", str); //Marca registrada.&trade;
  str = str.replace(/\(/g, "-", str);
  str = str.replace(/\)/g, "-", str);
  str = str.replace(/�/g, "-", str);
  str = str.replace(/\//g, "-", str);
  str = str.replace(/ de /g, "-", str); //Espacios
  str = str.replace(/ y /g, "-", str); //Espacios
  str = str.replace(/ a /g, "-", str); //Espacios
  str = str.replace(/ DE /g, "-", str); //Espacios
  str = str.replace(/ A /g, "-", str); //Espacios
  str = str.replace(/ Y /g, "-", str); //Espacios
  str = str.replace(/ /g, "-", str); //Espacios
  str = str.replace(/  /g, "-", str); //Espacios
  str = str.replace(/\./g, "", str); //Punto

  //Mayusculas
  str = str.toLowerCase();

  return str;
}

function number_format(number, decimals, dec_point, thousands_point) {
  if (number == null || !isFinite(number)) {
    throw new TypeError("number is not valid");
  }

  if (!decimals) {
    var len = number.toString().split(".").length;
    decimals = len > 1 ? len : 0;
  }

  if (!dec_point) {
    dec_point = ".";
  }

  if (!thousands_point) {
    thousands_point = ",";
  }

  number = parseFloat(number).toFixed(decimals);

  number = number.replace(".", dec_point);

  var splitNum = number.split(dec_point);
  splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
  number = splitNum.join(dec_point);

  return number;
}
function validateReserva() {
  if ($("#planForm").length > 0) {
    $("#planForm").validate({
      rules: {
        uname: { required: true },
        uemail: { required: true, email: true },
        uphone: { required: true },
      },
      messages: {
        uname: { required: "Escriba su nombre" },
        uemail: {
          required: "Escriba su correo electrónico.",
          email: "El correo no es valido.",
        },
        uphone: { required: "Este campo es obligatorio." },
      },
      submitHandler: function () {
        $('#planForm button[type="submit"]').prop("disabled", true);
        $("#preloader").fadeIn();
        $("#planForm").ajaxSubmit({
          dataType: "json",
          success: function (data) {
            if (data.message == 1) {
              $("#planForm")[0].reset();
              $('#planForm button[type="submit"]').prop("disabled", false);
              $('#planForm button[type="submit"]').removeClass("loading");
              $('#planForm button[type="submit"]').html("Enviado...");
              $('#planForm button[type="submit"]').html("RESERVA GRATIS AHORA");
              $.fancybox.open({
                src: "boxes/thanks.php",
                type: "ajax",
                touch: false,
              });
            } else {
              $('#planForm button[type="submit"]').prop("disabled", false);
              $('#planForm button[type="submit"]').removeClass("loading");
              $('#planForm button[type="submit"]').html("REINTENTAR");
              $.fancybox.open({
                src: "boxes/error.php",
                type: "ajax",
                touch: false,
              });
            }
            $("#preloader").fadeOut();
          },
        });
      },
    });
  }
}

document.addEventListener("DOMContentLoaded", function (event) {
  validateReserva();
});

function validateForm(e) {
  if (document.querySelector(".find_plan-grid")) {
    e.preventDefault();
  }
  // Get the value of the name and email fields
  var search = document.forms["searchForm"]["search"].value;
  // Validate the search field
  if (search == "") {
    if (
      document.querySelector(".home-banner .container form span .input small")
    ) {
      document.querySelector(
        ".home-banner .container form span .input small"
      ).style.opacity = 1;
    }
    return false;
  }
  if (
    document.querySelector(".home-banner .container form span .input small")
  ) {
    document.querySelector(
      ".home-banner .container form span .input small"
    ).style.opacity = 0;
  }
  if (document.querySelector(".find_plan-grid")) {
    filterPlans(zonesArray, personsArray, segmentsArray, null, search, null);
  }
  return true;
}

let btnReserva = document.querySelector(".btn-reserva");

if (btnReserva) {
  btnReserva.addEventListener("click", () => {
    document.querySelector("#reservar").classList.add("active");
  });
}

if (document.querySelector(".texto")) {
  var texto = document.querySelector(".texto");
  var botonLeerMas = document.querySelector("button.leer-mas");
  if (texto.textContent.length > 400) {
    var texto = document.querySelector(".texto");

    botonLeerMas.addEventListener("click", function () {
      botonLeerMas.innerHTML = texto.classList.contains("leer-mas")
        ? "Leer más"
        : "Leer menos";
      texto.classList.toggle("leer-mas");
    });
  } else {
    texto.classList.add("leer-mas");
    botonLeerMas.style.display = "none";
  }
}

let personsIDs = [];
let catIDs = [];
let zoneIDs = [];

function removeNoResponseFilters() {
  if (document.querySelector(".filters")) {
    document.querySelector(".filters").classList.remove("active");
    personsIDs = [];
    catIDs = [];
    zoneIDs = [];
    let elements = document.querySelectorAll(".find_plan-grid__item");

    document
      .querySelectorAll(".find_plan .filters ul li input")
      .forEach((el) => {
        el.parentElement.style.display = "none";
      });

    elements.forEach((element, idx) => {
      const personsValue = element.getAttribute("data-persons");
      const catValue = element.getAttribute("data-cat");
      const zoneValue = element.getAttribute("data-zone");
      if (personsValue != "") {
        personsIDs.push(personsValue > 4 ? "all" : personsValue);
      }
      if (catValue != "") {
        catValue.split(", ").forEach((value) => {
          catIDs.push(value);
        });
      }

      if (zoneValue != "") {
        zoneValue.split(", ").forEach((value) => {
          zoneIDs.push(value);
        });
      }
      if (idx === elements.length - 1) {
        console.log("END");
        personsIDs = [...new Set(personsIDs)];
        catIDs = [...new Set(catIDs)];
        zoneIDs = [...new Set(zoneIDs)];

        personsIDs.forEach((el, index) => {
          if (document.querySelector(`#persons-${el}`)) {
            document.querySelector(
              `#persons-${el}`
            ).parentElement.style.display = "block";
          }
        });
        catIDs.forEach((el, index) => {
          if (document.querySelector(`#cat-${el}`)) {
            document.querySelector(`#cat-${el}`).parentElement.style.display =
              "block";
          }
        });
        zoneIDs.forEach((el, index) => {
          if (document.querySelector(`#zone-${el}`)) {
            document.querySelector(`#zone-${el}`).parentElement.style.display =
              "block";
          }
        });
      }
    });
    $("#preloader").fadeOut();
  }
  lazyImages();
}

if (window.innerWidth < 768) {
  document.querySelector(".filters").addEventListener("click", () => {
    document.querySelector(".filters").classList.toggle("active");
    if (document.querySelector(".filters").classList.contains("active")) {
      document.querySelector("html").style.overflow = "hidden";
    } else {
      document.querySelector("html").style.overflowY = "scroll";
    }
  });
}

if (document.querySelector(".faqs .accordion")) {
  $(".faqs .accordion").accordion({
    header: "h4",
    active: false,
    heightStyle: "content",
    collapsible: true,
  });
}
