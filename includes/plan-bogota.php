<?php
/*

Plan Bogota microsite class
Version 1.0
Basic PHP functions for Plan Bogota Microsite


*/
session_start();
class planbogota extends bogota
{
    
    public $domain = "https://www.bogotadc.travel/drpl/es/api/v1";
    public $generalInfo = array();
    public $language = "";
    public $production = true;


    public $planbogotainfo = array();


    function __construct($language, $development = false)
    {
        $this->language = $language;
        if ($development) {
            $this->production = false;
        }
        $this->generalInfo = $this->getInfoGnrl();
    }
    function absoluteURL($str) //Enviar a bogota SDK
    {
     if(strpos($str,"https")==false){ return "https://bogotadc.travel".$str; }else{ return $str; }
    
    }

    function getPlans($id = "all"){
        $result = $this->query("ofertas/".$id);
        if($id == "all"){
            return $result;
            
        }else{
            return $result[0];
            
        }
    }
    function searchPlans($term, $quant){
        $result = $this->query("pbsearch/".$term."?field_maxpeople_value=". $quant);
            return $result;
    }
    
    function getInfoGnrl(){
    if (isset($_SESSION['pbinfo'][$this->language])) {
        $gnrl = $_SESSION['pbinfo'][$this->language];
    } else {
        $result = $this->query("pb_infognrl");
        $gnrl = $result[0];
        $_SESSION['pbinfo'][$this->language] = $gnrl;
    }
    return $gnrl;
    }
    
    function getZonesPB(){
        $result = $this->query("zones/all");
        return $result;
    }
    function getFaqPB(){
        $result = $this->query("faqpb");
        return $result;
    }
    function getTaxs($taxName){
        $result = $this->query("tax/".$taxName);
        return $result;
    }
    function getCompany($id){
        $result = $this->query("rld_operadores/".$id);
        return $result[0];
    }
    
    function filterPlans($zones, $persons, $segments, $price, $term,$quant ){
        $result = $this->query("ofertas_filtradas/" . str_replace(" ","+",$zones) . "/" . str_replace(" ","+",$persons) . "/" . str_replace(" ","+",$segments) . "/" . $term . $price . $quant);
        return $result;
    }

    function getRecommendPlans($ids){
        $plans = explode(", ", $ids);  
        if(count($plans) > 1){
           $arr = array();
           for ($a=0; $a < count($plans); $a++) { 
               array_push($arr, $this->getPlans($plans[$a]));
           }
           return $arr;
           }else{
               $plan = $this->getPlans($ids);
               return $plan;
           }

    }

    function allOfertasActivas(){
        $result = $this->query("ofertas-activas");
        return $result;
    }
   
}