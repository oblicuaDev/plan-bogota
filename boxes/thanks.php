<?php
    $linkDrupal = 'http://bogotaadmin.tiendasantuario.com/drpl/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $linkDrupal."api/v1/infognrl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $infoGnrl = json_decode($output);
    curl_close($ch);
?>
<div class="boxes thanks">
    <div class="boxes__cnt">
        <div class="boxes__header">
            <h2 class="bold covered ms900">¡Gracias por realizar tu reserva con nuestro comercial aliado!</h2>
        </div>
        <div class="boxes__body">
            <div class="txt">
                <div class="msg">
                    <?=$infoGnrl[0]->field_thanksmsg?>
                </div>
                <div class="data">
                    <?=$infoGnrl[0]->field_thnaksinfo?>
                    <a href="es/plan-bogota/encuentra-tu-plan" class="btn btn-reserva ms900">Seguir explorando</a>
                </div>
            </div>
        </div>
    </div>
</div>