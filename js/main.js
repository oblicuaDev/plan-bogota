/**
 * This function create a cookie
 * @param {string} cname  The name of var
 * @param {mixed} cvalue The values of the var
 * @param {int} exdays Days of expire
 * @param {String} path   The path of cookie
 */
function setCookie(cname, cvalue, exdays, path = "") {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/" + path;
}
/**
 * Return the value of a cookie
 * @param  {string} cname The name of the cookie
 * @return {string}       The value found or null if it not exists
 */
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return null;
}
let zonesArray = [];
let personsArray = [];
let segmentsArray = [];
// Helper function to check if a value is a number
const isNumber = (n) => typeof n === "number" || n instanceof Number;

// Helper function to update URL parameters
const updateUrlParams = (param, values) => {
  const urlParams = new URLSearchParams(window.location.search);
  urlParams.set(param, values.join(","));
  const newURL = `${window.location.pathname}?${decodeURIComponent(
    urlParams.toString()
  )}`;
  history.pushState(null, "", newURL);
  setCookie("prevUrl", newURL);
};

// Helper function to handle adding or removing elements from an array
const handleArrayValue = (array, value) => {
  if (array.includes(value)) {
    array = array.filter((item) => item !== value);
  } else {
    array.push(value);
  }
  return array;
};

function shorter(text, chars_limit = 35) {
  // Cambia al número de caracteres que deseas mostrar
  var chars_text = text.length;
  text = text + " ";
  text = text.substring(0, chars_limit);
  text = text.substring(0, text.lastIndexOf(" "));
  if (chars_text > chars_limit) {
    text = text + "...";
  }
  return text;
}

function applyFiltersFromURLPlan() {
  const urlParams = new URLSearchParams(window.location.search);

  if (urlParams.has("categories")) {
    const filterSetZonas = urlParams.get("categories");
    segmentsArray = filterSetZonas.split(",").map((id) => parseInt(id, 10));
  } else {
    segmentsArray = [];
  }
  if (urlParams.has("zones")) {
    const filterSetZonas = urlParams.get("zones");
    zonesArray = filterSetZonas.split(",").map((id) => parseInt(id, 10));
  } else {
    zonesArray = [];
  }

  // Marcar los checkboxes de cada filtro según los arreglos correspondientes
  const checkboxesCategories = document.querySelectorAll(
    '#categories input[type="checkbox"]'
  );
  checkboxesCategories.forEach((checkbox) => {
    const paramValue = parseInt(checkbox.getAttribute("id").split("-")[1], 10);
    checkbox.checked = segmentsArray.includes(paramValue);
  });
  // Marcar los checkboxes de cada filtro según los arreglos correspondientes
  const checkboxesZones = document.querySelectorAll(
    '#zones input[type="checkbox"]'
  );
  checkboxesZones.forEach((checkbox) => {
    const paramValue = parseInt(checkbox.getAttribute("id").split("-")[1], 10);
    checkbox.checked = zonesArray.includes(paramValue);
  });
}
function shuffleArrayPlanes(array) {
  const highlightedObjects = array.filter(
    (obj) => obj.field_destacar_en_categoria
  );
  const nonHighlightedObjects = array.filter(
    (obj) => !obj.field_destacar_en_categoria
  );

  // Algoritmo de Fisher-Yates solo para los objetos destacados
  for (let i = highlightedObjects.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [highlightedObjects[i], highlightedObjects[j]] = [
      highlightedObjects[j],
      highlightedObjects[i],
    ];
  }

  // Combinar objetos destacados y no destacados
  const shuffledArray = [...highlightedObjects, ...nonHighlightedObjects];

  return shuffledArray;
}

// Main function to filter plans
const filterPlans = (
  zones = null,
  persons = null,
  segments = null,
  price = null,
  search = null,
  max = null,
  el
) => {
  $("#preloader").fadeIn();

  const filters = [];
  let paramValue;
  if (zones !== null) {
    zonesArray = handleArrayValue(zonesArray, zones);
    if (el) {
      updateUrlParams("zones", zonesArray);
      paramValue = el.getAttribute("id");
      filters.push(paramValue);
    }
  }

  if (persons !== null) {
    personsArray = handleArrayValue(personsArray, persons);
  }

  if (segments !== null) {
    segmentsArray = handleArrayValue(segmentsArray, segments);
    if (el) {
      updateUrlParams("categories", segmentsArray);
      paramValue = el.getAttribute("id");
      filters.push(paramValue);
    }
  }

  const grid = document.querySelector(".find_plan-grid");
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

    if (isNumber(price) && price > 0) {
      url += `&price=${price}`;
    }

    if (!!search && search !== "null") {
      url += `&search=${search}`;
    }

    if (max && max !== "null") {
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
            }" data-zone="${
              plan.field_pb_oferta_zona
            }" data-field_destacar_en_categoria="${
              plan.field_destacar_en_categoria
            }">
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

if ($(".gallery_dot").length) {
  $(".gallery_dot li").hover(function (e) {
    $(".gallery_dot li.active").removeClass("active");
    $(e.target).addClass("active");
    var src = $($(e.target).children("img")).data("src");
    $(".gallery #principal_img").attr("src", src);
  });
}

function getRandomNumberBetween200And1000() {
  // Genera un número aleatorio entre 0 y 1
  const random = Math.random();
  // Escala y desplaza el número para que esté entre 200 y 1000
  const randomNumber = Math.floor(random * 801) + 200;
  return randomNumber;
}

if (document.querySelector(".persons")) {
  document.querySelector(".persons span").innerHTML =
    getRandomNumberBetween200And1000();
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
          },this)" /><label class="filtercheck" for="zone-${
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
          },this)" /><label class="filtercheck" for="cat-${
            segment.tid
          }" class="ms500">${segment.name}</label></li>`;
        });
      }
    })
    .then(() => {
      removeNoResponseFilters();
      applyFiltersFromURLPlan();
    });
}

function without(array, what) {
  return array.filter(function (element) {
    return element !== what;
  });
}

$("#resetfilters").click(function () {
  history.pushState(null, "", `/${actualLang}/plan-bogota/encuentra-tu-plan`);
  setCookie("prevUrl", `/${actualLang}/plan-bogota/encuentra-tu-plan`);
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
let minPrice = 0;
let maxPrice = 0;
items.forEach((item) => {
  const price = parseFloat(item.dataset.price);
  if (price < minPrice) {
    minPrice = price;
  }
  if (price > maxPrice) {
    maxPrice = price;
  }
});
// Función para dar formato a los números con separadores de miles
function number_format(number, decimals, decPoint, thousandsSep) {
  decimals = decimals || 0;
  number = parseFloat(number);
  if (!decPoint || !thousandsSep) {
    decPoint = ".";
    thousandsSep = ",";
  }
  var roundedNumber = Math.round(Math.abs(number) * ("1e" + decimals)) + "";
  var numbersString = decimals
    ? roundedNumber.slice(0, decimals * -1)
    : roundedNumber;
  var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : "";
  var formattedNumber = "";
  while (numbersString.length > 3) {
    formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
    numbersString = numbersString.slice(0, -3);
  }
  return (
    (number < 0 ? "-" : "") +
    numbersString +
    formattedNumber +
    (decimalsString ? decPoint + decimalsString : "")
  );
}

// Función para actualizar el valor del span "amount"
function updateAmount(value) {
  var amountSpan = document.getElementById("amount");
  if (amountSpan) {
    amountSpan.value = `$${number_format(value, 0, ".", ".")}`;
  }
}
// Obtener los elementos necesarios
var priceSlider = document.getElementById("price-slider");

// Establecer el valor inicial del span "amount"
updateAmount(maxPrice);
if (priceSlider) {
  // Escuchar el evento "input" del slider para actualizar el valor del span en tiempo real
  priceSlider.addEventListener("input", function (event) {
    var value = parseInt(event.target.value);
    updateAmount(value);
  });
  // Escuchar el evento "change" del slider para actualizar el valor del span cuando se establezca un valor final
  priceSlider.addEventListener("change", function (event) {
    var value = parseInt(event.target.value);
    filterPlans(null, null, null, value, null, null);
  });
}

if (document.querySelector(".find_plan-grid")) {
  applyFiltersFromURLPlan();
  filterPlans(
    zonesArray,
    null,
    segmentsArray,
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
  str = str.replace(":", "", str); //Punto
  str = str.replace("?", "", str); //Punto

  //Mayusculas
  str = str.toLowerCase();

  return str;
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
              $('#planForm button[type="submit"]').prop("disabled", false);
              $('#planForm button[type="submit"]').removeClass("loading");
              $('#planForm button[type="submit"]').html("Enviado...");
              $('#planForm button[type="submit"]').html("RESERVA GRATIS AHORA");
              $.fancybox.open({
                src: `boxes/thanks.php?serial=${data.serial}`,
                type: "ajax",
                touch: false,
                afterClose: function (instance, current) {
                  console.log("Cuadro modal cerrado");
                  $("#planForm")[0].reset();
                },
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
  if (document.querySelector(".filters")) {
    document.querySelector(".filters").addEventListener("click", () => {
      document.querySelector(".filters").classList.toggle("active");
      if (document.querySelector(".filters").classList.contains("active")) {
        document.querySelector("html").style.overflow = "hidden";
      } else {
        document.querySelector("html").style.overflowY = "scroll";
      }
    });
  }
}

if (document.querySelector(".faqs .accordion")) {
  $(".faqs .accordion").accordion({
    header: "h4",
    active: false,
    heightStyle: "content",
    collapsible: true,
  });
}

function absoluteURL(str) {
  if (str.indexOf("https") == -1) {
    return "https://bogotadc.travel" + str.replace(/\s/g, "");
  } else {
    return str;
  }
}
if (document.querySelector(".intern .btn-back")) {
  document
    .querySelectorAll(".intern .btn-back")
    .forEach((el) => (el.href = "/es/plan-bogota/encuentra-tu-plan"));
}
if (document.querySelector(".categoriessection .categories")) {
  fetch(`../mice/get/filter.php?lang=${actualLang}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ filter: "categorias_comerciales_pb" }),
  })
    .then((response) => response.json())
    .then((data) => {
      data
        .filter((el) => {
          console.log({ name: el.name, field_home_view: el.field_home_view });
          return el.field_home_view == "1";
        })
        .forEach(({ name, tid, field_format_icon }) => {
          let template = `<a href="https://www.bogotadc.travel/es/plan-bogota/encuentra-tu-plan?categories=${tid}"><img src="${field_format_icon}" alt="${name}"><small>${name}</small></a>`;
          document.querySelector(".categoriessection .categories").innerHTML +=
            template;
        });
    })
    .catch((error) => {
      console.error("Hubo un error:", error);
    });
}
