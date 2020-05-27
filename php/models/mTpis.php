<?php
require_once("php/inc.all.php");

function getAllTpi()
{
    $database = UserDbConnection();
    $arrTpi = array();

    $query = $database->prepare("SELECT tpiID,tpiStatus,title,cfcDomain,abstract FROM tpidbthh.tpis ;");
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->title = $row[$i]['title'];
            $tpi->cfcDomain = $row[$i]['cfcDomain'];
            $tpi->abstract = $row[$i]['abstract'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
}

function displayTPIAdmin($arrTPI)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 50; repeat: false\">";

    for ($i = 0; $i <= count($arrTPI); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<h3 class=\"uk-card-title\">TPI : " . $arrTPI[$i]->title . "</h3>";
        $html .= "<p>Resumé : " . $arrTPI[$i]->abstract . "</p>";
        $html .= "</div>";
        $html .= "</div>";
    }
    
    $html .= "</div>";
    $html .= "</div>";

    return $html;
}
