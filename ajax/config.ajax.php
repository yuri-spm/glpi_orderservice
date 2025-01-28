<?php

require_once('../../../inc/includes.php');
require_once('../vendor/autoload.php'); // Para o DomPDF

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="ordem_servico.pdf"');
    
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    
    ob_start();
    PluginOrderserviceConfig::template();
    $html = ob_get_clean();
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    echo $dompdf->output();
    exit;
}