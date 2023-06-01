<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=export_data.xls");  //File name extension was wrong
include "../includes/sdk_import.php";
include "../includes/plan-bogota.php";
$pb = new planbogota('es');
$filtered = $pb->allOfertasActivas();
$fields = ["Nombre de la oferta", "Nombre de la empresa", "ID de la empresa", "Categoría comercial", "empresa_email",
"empresa_telefono",
"empresa_direccion"];

$string = '<table style="width:100%">';
$valid_ext = array("pdf","doc","docx","jpg","png","jpeg");
$string .= '<tr>';
for ($i=0; $i < count($fields); $i++) { 
    $string .= '<th>'.$fields[$i].'</th>';
}
    $string .= '</tr>';

    for ($i=0; $i < count($filtered); $i++) { 
        $string .= '<tr>';
            $company =  $pb->getCompany($filtered[$i]->field_pb_oferta_empresa_1);
            $string .= '<td>'.$filtered[$i]->title.'</td>';  
            $string .= '<td>'.$filtered[$i]->field_pb_oferta_empresa.'</td>';  
            $string .= '<td>'.$filtered[$i]->field_pb_oferta_empresa_1.'</td>';  
            $string .= '<td>'.$filtered[$i]->field_categoria_comercial.'</td>';  
            $string .= '<td>'.$company->field_pb_empresa_email.'</td>';  
            $string .= '<td>'.$company->field_pb_empresa_telefono.'</td>';  
            $string .= '<td>'.$company->field_pb_empresa_direccion.'</td>';  
            $string .= '</tr>';
        }



$string .= '</thead>';
echo $string;
?>