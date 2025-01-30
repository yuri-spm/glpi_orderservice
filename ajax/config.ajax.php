<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../inc/includes.php');
$pluginDir = dirname(__DIR__); 
require_once($pluginDir . '/vendor/autoload.php');


use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die('Método não permitido');
}

try {
    $ticket_id = $_GET['id'] ?? null;
    
    if (!$ticket_id) {
        throw new Exception('ID do ticket não fornecido');
    }
    
    $ticket = new Ticket();
    if (!$ticket->getFromDB($ticket_id) || !$ticket->canViewItem()) {
        throw new Exception('Ticket não encontrado ou sem permissão de acesso');
    }
    
    define('GENERATING_PDF', true);
    
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    //$options->setIsPhpEnabled(true);
    //$options->setDebugLayout(true);
    
    $options->setTempDir(GLPI_TMP_DIR);
    $options->setLogOutputFile(GLPI_LOG_DIR . '/dompdf.log');
    
    $dompdf = new Dompdf($options);
    
    $html = PluginOrderserviceConfig::template($ticket_id);
    
    if (empty($html)) {
        throw new Exception('Erro ao gerar o HTML do template');
    }
    
    file_put_contents(GLPI_TMP_DIR . '/debug_' . $ticket_id . '.html', $html);
    
    $dompdf->loadHtml($html);
    
    $dompdf->setPaper('A4', 'portrait');
    
    $dompdf->render();
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="ordem_servico_' . $ticket_id . '.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    echo $dompdf->output();
    
} catch (Exception $e) {
    Toolbox::logError('Erro ao gerar PDF: ' . $e->getMessage());
    error_log('Erro ao gerar PDF: ' . $e->getMessage());
    
    http_response_code(500);
    die('Erro ao gerar PDF: ' . $e->getMessage());
}