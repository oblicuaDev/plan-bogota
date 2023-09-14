<?php
function campaignMonitorEmail($email,$subject, $template, $mergeTags){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.createsend.com/api/v3.2/transactional/smartemail/'.$template.'/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "To": ["'.$email.'"],
        "Data":'.$mergeTags.',
        "ConsentToTrack": "yes"
    }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Basic ZUg3czhIdEJXQlhYQlVtS2Q4U2tvc3hLZVZLL3RuVFMyQi9tcDUvWHp0M1dXZDJ0dVJYclVsOGxENnlOa1Zld2t4dnI2RHFLWmtPcS9TbEJJYUlkd1JQejBLU245cTcrcDFyMnVSUStFWXRRZE9Tak85VjdTVmhKYnV2TCtKeVMrMnFrTFR6RlFhRE9zbG9GdjlLcTFnPT06'
    ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}
function sendNotification($emailto, $params)
{
    $curl = curl_init();
    $userID =2;
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"to\":[{\"email\":\"$emailto\",\"name\":\"$emailto\"}],\"params\":".$params,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json",
            "api-key: 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"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}
    function excelBookings()
    {
        global $linkDrupal;
            //echo $linkDrupal."api/v1/slider";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://planbogota.bogotadc.travel/drpl/api/v1/messages/cliente_oferta");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $b = json_decode($output);
            curl_close($ch);
 
         return $b;
    }


    function get_alias($String)
    {
        $String = html_entity_decode($String); // Traduce codificación

        $String = str_replace("¡", "", $String); //Signo de exclamación abierta.&iexcl;
        $String = str_replace("'", "", $String); //Signo de exclamación abierta.&iexcl;
        $String = str_replace("!", "", $String); //Signo de exclamación cerrada.&iexcl;
        $String = str_replace("¢", "-", $String); //Signo de centavo.&cent;
        $String = str_replace("£", "-", $String); //Signo de libra esterlina.&pound;
        $String = str_replace("¤", "-", $String); //Signo monetario.&curren;
        $String = str_replace("¥", "-", $String); //Signo del yen.&yen;
        $String = str_replace("¦", "-", $String); //Barra vertical partida.&brvbar;
        $String = str_replace("§", "-", $String); //Signo de sección.&sect;
        $String = str_replace("¨", "-", $String); //Diéresis.&uml;
        $String = str_replace("©", "-", $String); //Signo de derecho de copia.&copy;
        $String = str_replace("ª", "-", $String); //Indicador ordinal femenino.&ordf;
        $String = str_replace("«", "-", $String); //Signo de comillas francesas de apertura.&laquo;
        $String = str_replace("¬", "-", $String); //Signo de negación.&not;
        $String = str_replace("", "-", $String); //Guión separador de sílabas.&shy;
        $String = str_replace("®", "-", $String); //Signo de marca registrada.&reg;
        $String = str_replace("¯", "&-", $String); //Macrón.&macr;
        $String = str_replace("°", "-", $String); //Signo de grado.&deg;
        $String = str_replace("±", "-", $String); //Signo de más-menos.&plusmn;
        $String = str_replace("²", "-", $String); //Superíndice dos.&sup2;
        $String = str_replace("³", "-", $String); //Superíndice tres.&sup3;
        $String = str_replace("´", "-", $String); //Acento agudo.&acute;
        $String = str_replace("µ", "-", $String); //Signo de micro.&micro;
        $String = str_replace("¶", "-", $String); //Signo de calderón.&para;
        $String = str_replace("·", "-", $String); //Punto centrado.&middot;
        $String = str_replace("¸", "-", $String); //Cedilla.&cedil;
        $String = str_replace("¹", "-", $String); //Superíndice 1.&sup1;
        $String = str_replace("º", "-", $String); //Indicador ordinal masculino.&ordm;
        $String = str_replace("»", "-", $String); //Signo de comillas francesas de cierre.&raquo;
        $String = str_replace("¼", "-", $String); //Fracción vulgar de un cuarto.&frac14;
        $String = str_replace("½", "-", $String); //Fracción vulgar de un medio.&frac12;
        $String = str_replace("¾", "-", $String); //Fracción vulgar de tres cuartos.&frac34;
        $String = str_replace("¿", "-", $String); //Signo de interrogación abierta.&iquest;
        $String = str_replace("×", "-", $String); //Signo de multiplicación.&times;
        $String = str_replace("÷", "-", $String); //Signo de división.&divide;
        $String = str_replace("À", "a", $String); //A mayúscula con acento grave.&Agrave;
        $String = str_replace("Á", "a", $String); //A mayúscula con acento agudo.&Aacute;
        $String = str_replace("Â", "a", $String); //A mayúscula con circunflejo.&Acirc;
        $String = str_replace("Ã", "a", $String); //A mayúscula con tilde.&Atilde;
        $String = str_replace("Ä", "a", $String); //A mayúscula con diéresis.&Auml;
        $String = str_replace("Å", "a", $String); //A mayúscula con círculo encima.&Aring;
        $String = str_replace("Æ", "a", $String); //AE mayúscula.&AElig;
        $String = str_replace("Ç", "c", $String); //C mayúscula con cedilla.&Ccedil;
        $String = str_replace("È", "e", $String); //E mayúscula con acento grave.&Egrave;
        $String = str_replace("É", "e", $String); //E mayúscula con acento agudo.&Eacute;
        $String = str_replace("Ê", "e", $String); //E mayúscula con circunflejo.&Ecirc;
        $String = str_replace("Ë", "e", $String); //E mayúscula con diéresis.&Euml;
        $String = str_replace("Ì", "i", $String); //I mayúscula con acento grave.&Igrave;
        $String = str_replace("Í", "i", $String); //I mayúscula con acento agudo.&Iacute;
        $String = str_replace("Î", "i", $String); //I mayúscula con circunflejo.&Icirc;
        $String = str_replace("Ï", "i", $String); //I mayúscula con diéresis.&Iuml;
        $String = str_replace("Ð", "d", $String); //ETH mayúscula.&ETH;
        $String = str_replace("Ñ", "n", $String); //N mayúscula con tilde.&Ntilde;
        $String = str_replace("Ò", "o", $String); //O mayúscula con acento grave.&Ograve;
        $String = str_replace("Ó", "o", $String); //O mayúscula con acento agudo.&Oacute;
        $String = str_replace("Ô", "o", $String); //O mayúscula con circunflejo.&Ocirc;
        $String = str_replace("Õ", "o", $String); //O mayúscula con tilde.&Otilde;
        $String = str_replace("Ö", "o", $String); //O mayúscula con diéresis.&Ouml;
        $String = str_replace("Ø", "o", $String); //O mayúscula con barra inclinada.&Oslash;
        $String = str_replace("Ù", "u", $String); //U mayúscula con acento grave.&Ugrave;
        $String = str_replace("Ú", "u", $String); //U mayúscula con acento agudo.&Uacute;
        $String = str_replace("Û", "u", $String); //U mayúscula con circunflejo.&Ucirc;
        $String = str_replace("Ü", "u", $String); //U mayúscula con diéresis.&Uuml;
        $String = str_replace("Ý", "y", $String); //Y mayúscula con acento agudo.&Yacute;
        $String = str_replace("Þ", "b", $String); //Thorn mayúscula.&THORN;
        $String = str_replace("ß", "b", $String); //S aguda alemana.&szlig;
        $String = str_replace("à", "a", $String); //a minúscula con acento grave.&agrave;
        $String = str_replace("á", "a", $String); //a minúscula con acento agudo.&aacute;
        $String = str_replace("â", "a", $String); //a minúscula con circunflejo.&acirc;
        $String = str_replace("ã", "a", $String); //a minúscula con tilde.&atilde;
        $String = str_replace("ä", "a", $String); //a minúscula con diéresis.&auml;
        $String = str_replace("å", "a", $String); //a minúscula con círculo encima.&aring;
        $String = str_replace("æ", "a", $String); //ae minúscula.&aelig;
        $String = str_replace("ç", "a", $String); //c minúscula con cedilla.&ccedil;
        $String = str_replace("è", "e", $String); //e minúscula con acento grave.&egrave;
        $String = str_replace("é", "e", $String); //e minúscula con acento agudo.&eacute;
        $String = str_replace("ê", "e", $String); //e minúscula con circunflejo.&ecirc;
        $String = str_replace("ë", "e", $String); //e minúscula con diéresis.&euml;
        $String = str_replace("ì", "i", $String); //i minúscula con acento grave.&igrave;
        $String = str_replace("í", "i", $String); //i minúscula con acento agudo.&iacute;
        $String = str_replace("î", "i", $String); //i minúscula con circunflejo.&icirc;
        $String = str_replace("ï", "i", $String); //i minúscula con diéresis.&iuml;
        $String = str_replace("ð", "i", $String); //eth minúscula.&eth;
        $String = str_replace("ñ", "n", $String); //n minúscula con tilde.&ntilde;
        $String = str_replace("ò", "o", $String); //o minúscula con acento grave.&ograve;
        $String = str_replace("ó", "o", $String); //o minúscula con acento agudo.&oacute;
        $String = str_replace("ô", "o", $String); //o minúscula con circunflejo.&ocirc;
        $String = str_replace("õ", "o", $String); //o minúscula con tilde.&otilde;
        $String = str_replace("ö", "o", $String); //o minúscula con diéresis.&ouml;
        $String = str_replace("ø", "o", $String); //o minúscula con barra inclinada.&oslash;
        $String = str_replace("ù", "o", $String); //u minúscula con acento grave.&ugrave;
        $String = str_replace("ú", "u", $String); //u minúscula con acento agudo.&uacute;
        $String = str_replace("û", "u", $String); //u minúscula con circunflejo.&ucirc;
        $String = str_replace("ü", "u", $String); //u minúscula con diéresis.&uuml;
        $String = str_replace("ý", "y", $String); //y minúscula con acento agudo.&yacute;
        $String = str_replace("þ", "b", $String); //thorn minúscula.&thorn;
        $String = str_replace("ÿ", "y", $String); //y minúscula con diéresis.&yuml;
        $String = str_replace("Œ", "d", $String); //OE Mayúscula.&OElig;
        $String = str_replace("œ", "-", $String); //oe minúscula.&oelig;
        $String = str_replace("Ÿ", "-", $String); //Y mayúscula con diéresis.&Yuml;
        $String = str_replace("ˆ", "", $String); //Acento circunflejo.&circ;
        $String = str_replace("˜", "", $String); //Tilde.&tilde;
        $String = str_replace("–", "", $String); //Guiún corto.&ndash;
        $String = str_replace("—", "", $String); //Guiún largo.&mdash;
        $String = str_replace("'", "", $String); //Comilla simple izquierda.&lsquo;
        $String = str_replace("'", "", $String); //Comilla simple derecha.&rsquo;
        $String = str_replace("‚", "", $String); //Comilla simple inferior.&sbquo;
        $String = str_replace("\"", "", $String); //Comillas doble derecha.&rdquo;
        $String = str_replace("\"", "", $String); //Comillas doble inferior.&bdquo;
        $String = str_replace("†", "-", $String); //Daga.&dagger;
        $String = str_replace("‡", "-", $String); //Daga doble.&Dagger;
        $String = str_replace("…", "-", $String); //Elipsis horizontal.&hellip;
        $String = str_replace("‰", "-", $String); //Signo de por mil.&permil;
        $String = str_replace("‹", "-", $String); //Signo izquierdo de una cita.&lsaquo;
        $String = str_replace("›", "-", $String); //Signo derecho de una cita.&rsaquo;
        $String = str_replace("€", "-", $String); //Euro.&euro;
        $String = str_replace("™", "-", $String); //Marca registrada.&trade;
        $String = str_replace(":", "-", $String); //Marca registrada.&trade;
        $String = str_replace(" & ", "-", $String); //Marca registrada.&trade;
        $String = str_replace("&", "", $String); //Marca registrada.&trade;
        $String = str_replace("(", "-", $String);
        $String = str_replace(")", "-", $String);
        $String = str_replace("?", "-", $String);
        $String = str_replace("¿", "-", $String);
        $String = str_replace(",", "-", $String);
        $String = str_replace(";", "-", $String);
        $String = str_replace("�", "-", $String);
        $String = str_replace("/", "-", $String);
        $String = str_replace(" ", "-", $String); //Espacios
        $String = str_replace(".", "", $String); //Punto
        $String = str_replace("&", "-", $String);

        //Mayusculas
        $String = strtolower($String);

        return ($String);
    }
    function create_metas($linkDrupal,$realLink, $seoId){
        if($seoId == ''){
            $seoId = 1;
        }
        $chSeo = curl_init();
        curl_setopt($chSeo, CURLOPT_URL, $realLink."api/v1/seo/".$seoId);
        curl_setopt($chSeo, CURLOPT_RETURNTRANSFER, 1);
        $outputSeo = curl_exec($chSeo);
        $seo = json_decode($outputSeo);
        curl_close($chSeo);
    
        global $metas,$urlMap;

        $ret = '';
        $metas['title'] = $seo[0]->field_seo_title;
        $metas['desc'] = $seo[0]->field_seo_desc;
        $metas['words'] = $seo[0]->field_seo_keys;
        $metas['img'] = $linkDrupal . $seo[0]->field_seo_img;
        
        list($width, $height, $type, $attr) = getimagesize($realLink . $seo[0]->field_seo_img); 

        $ret = '<meta charset="utf-8">'.PHP_EOL;
        $ret .= '<link rel="canonical" href="'.$_GET['canon'].'">'.PHP_EOL;
        $ret .= '<meta name="keywords" content="'.$metas['words'].'">'.PHP_EOL;
        $ret .= '<meta name="description" content="'.$metas['desc'].'">'.PHP_EOL;
        $ret .= '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1.5">'.PHP_EOL;
        $ret .= '<title>'.$metas['title'].'</title>'.PHP_EOL;
        $ret .= '<meta name="thumbnail" content="'.$metas['img'].'">'.PHP_EOL;
        $ret .= '<meta name="language" content="spanish">'.PHP_EOL;
        $ret .= '<meta name="twitter:card" content="summary_large_image">'.PHP_EOL;
        $ret .= '<meta name="twitter:site" content="@bogotadc.travel">'.PHP_EOL;
        $ret .= '<meta name="twitter:title" content="'.$metas['title'].'">'.PHP_EOL;
        $ret .= '<meta name="twitter:description" content="'.$metas['desc'].'">'.PHP_EOL;
        $ret .= '<meta name="twitter:image" content="'.$metas['img'].'">'.PHP_EOL;
        //$ret .= '<meta property="fb:app_id" content="865245646889167">'.PHP_EOL;
        $ret .= '<meta property="og:url" content="'.$linkDrupal.$_GET['canon'].'">'.PHP_EOL;
        $ret .= '<meta property="og:type" content="website">'.PHP_EOL;
        $ret .= '<meta property="og:title" content="'.$metas['title'].'">'.PHP_EOL;
        $ret .= '<meta property="og:site_name" content="'.$metas['title'].'">'.PHP_EOL;
        $ret .= '<meta property="og:description" content="'.$metas['desc'].'">'.PHP_EOL;
        $ret .= '<meta property="og:image" content="'.$metas['img'].'">'.PHP_EOL;
        $ret .= '<meta property="og:image:width" content="'.$width.'">'.PHP_EOL;
        $ret .= '<meta property="og:image:height" content="'.$height.'">'.PHP_EOL;
        $ret .= PHP_EOL;
        $ret .= "<!--[if IE]>\n";
        $ret .= "<script>\n";
        $ret .= "\n\tdocument.createElement('header');\n\tdocument.createElement('footer');";
        $ret .= "\n\tdocument.createElement('section');\n\tdocument.createElement('figure');\n\tdocument.createElement('aside');";
        $ret .= "\n\tdocument.createElement('nav');\n\tdocument.createElement('article');";
        $ret .= "\n</script>\n";
        $ret .= "\n<![endif]-->\n";
        
        return $ret;
    }
    function getColorByOffer($idPlanRel){
        $chRel = curl_init();
        curl_setopt($chRel, CURLOPT_URL, $linkDrupal."api/v1/planes");
        curl_setopt($chRel, CURLOPT_RETURNTRANSFER, 1);
        $outputRel = curl_exec($chRel);
        $planRel = json_decode($outputRel);
        curl_close($ch);
        if($planRel[$i]->nid === $idPlanRel){
            return $planRel[$i]->field_color;
        }
    }
    function between_last ($first, $that, $inthat){
     return after_last($first, before_last($that, $inthat));
    }
    function string_sanitize($s) {
        $result = preg_replace("/[^a-zA-Z0-9]+/", " ", html_entity_decode($s, ENT_QUOTES));
        return $result;
    }
    function getAltImage($str) {
        preg_match_all('`"([^"]*)"`', $str, $results);
        return string_sanitize($results[0][3]);
    }


    function ofertasByPlan($linkDrupal,$id,$q=0)
    {
        if($q==0)
        {
            $q="all";
        }
        
        //echo $linkDrupal."api/v1/ofertas/".$id."/".$q;
        $chOfertas = curl_init();
        curl_setopt($chOfertas, CURLOPT_URL, $linkDrupal."api/v1/ofertas/".$id."/home");
        curl_setopt($chOfertas, CURLOPT_RETURNTRANSFER, 1);
        $outputOfertas = curl_exec($chOfertas);
        $ofertas = json_decode($outputOfertas);
        curl_close($chOfertas);
        //print_r($ofertas);
        return $ofertas;
    }
    function planes($linkDrupal,$id=0)
    {
        if($id==0)
        {
            //echo $linkDrupal."api/v1/planes";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $linkDrupal."api/v1/planes");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $planes = json_decode($output);
            curl_close($ch);
        }
        
        return $planes;
    }
    function slider()
    {
        global $linkDrupal;
            //echo $linkDrupal."api/v1/slider";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $linkDrupal."api/v1/slider");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $sliders = json_decode($output);
            curl_close($ch);
 
        return $sliders;
    }
    function getfaq()
    {
        global $linkDrupal;
            //echo $linkDrupal."api/v1/slider";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $linkDrupal."api/v1/faq");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            $faq = json_decode($output);
            curl_close($ch);
 
        return $faq;
    }





?>