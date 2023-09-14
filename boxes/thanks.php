<?php
    $linkDrupal = 'http://bogotaadmin.tiendasantuario.com/drpl/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $linkDrupal."api/v1/infognrl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $infoGnrl = json_decode($output);
    curl_close($ch);
?>
<script>
    let uoferta = document.getElementById("uoferta").value;
    let ucompanyname = document.getElementById("ucompanyname").value;
    let ucompanyemail = document.getElementById("ucompanyemail").value;
    let ucompanyphone = document.getElementById("ucompanyphone").value;
    let ucompanylink = document.getElementById("ucompanylink").value;
    let background = document.getElementById("background").value;

    document.querySelector(".uoferta span").innerHTML = uoferta;
    document.querySelector(".ucompanyname span").innerHTML = ucompanyname;
    document.querySelector(".ucompanyemail span").innerHTML = ucompanyemail;
    document.querySelector(".ucompanyphone span").innerHTML = ucompanyphone;
    document.querySelector(".ucompanylink").href = ucompanylink;
    document.querySelector(".ucompanylink").innerHTML = ucompanylink;
</script>
<div class="boxes thanks">
    <div class="boxes__cnt">
        <h2 class="ms900">
            ¡El plan que soñaste es casi una realidad!
        </h2>
        <p>
            Hemos generado una reserva para ti.
        </p>
        <h3 class="ms900">
            Código de tu reserva en Plan Bogotá:
        </h3>
        <h4 class="ms900">
            <?=$_GET["serial"]?>
        </h4>
        <p class="uoferta"><strong>Oferta reservada:</strong><span></span></p>
        <p class="ucompanyname"><strong>Empresa responsable:</strong><span></span></p>
        <p class="ucompanyemail"><strong>Correo de contacto:</strong><span></span></p>
        <p class="ucompanyphone"><strong>Teléfono de contacto:</strong><span></span></p>
        <p>
            <strong>
                Usa el siguiente link para finalizar tu compra:
            </strong>
            <a href="" class="ucompanylink"></a>
        </p>
    </div>
</div>