<?php
// Ativa exibição de erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui os arquivos necessários do GLPI
require_once('../../../inc/includes.php');
$pluginDir = dirname(__DIR__); // Pega o diretório pai do diretório atual (ajax)
require_once($pluginDir . '/vendor/autoload.php');


// Importa as classes necessárias
use Dompdf\Dompdf;
use Dompdf\Options;

// Verifica se é uma requisição GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    die('Método não permitido');
}

try {
    // Verifica e obtém o ID do ticket
    $ticket_id = $_GET['id'] ?? null;
    
    if (!$ticket_id) {
        throw new Exception('ID do ticket não fornecido');
    }
    
    // Verifica se o ticket existe e se o usuário tem permissão
    $ticket = new Ticket();
    if (!$ticket->getFromDB($ticket_id) || !$ticket->canViewItem()) {
        throw new Exception('Ticket não encontrado ou sem permissão de acesso');
    }
    
    // Define constante para controle do fluxo
    define('GENERATING_PDF', true);
    
    // Configura as opções do DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $options->setIsPhpEnabled(true);
    $options->setDebugLayout(true);
    
    // Para debug - definir diretório temporário
    $options->setTempDir(GLPI_TMP_DIR);
    $options->setLogOutputFile(GLPI_LOG_DIR . '/dompdf.log');
    
    // Inicializa o DomPDF com as opções
    $dompdf = new Dompdf($options);
    
    // Obtém o HTML do template
    $html = PluginOrderserviceConfig::template($ticket_id);
    
    // Verifica se o HTML foi gerado corretamente
    if (empty($html)) {
        throw new Exception('Erro ao gerar o HTML do template');
    }
    
    // Para debug - salva o HTML gerado
    file_put_contents(GLPI_TMP_DIR . '/debug_' . $ticket_id . '.html', $html);
    
    // Carrega o HTML no DomPDF
    $dompdf->loadHtml($html);
    
    // Configura o papel
    $dompdf->setPaper('A4', 'portrait');
    
    // Renderiza o PDF
    $dompdf->render();
    
    // Configura os headers para download do PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="ordem_servico_' . $ticket_id . '.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    // Envia o PDF para o navegador
    echo $dompdf->output();
    
} catch (Exception $e) {
    // Log do erro
    Toolbox::logError('Erro ao gerar PDF: ' . $e->getMessage());
    error_log('Erro ao gerar PDF: ' . $e->getMessage());
    
    // Retorna erro para o usuário
    http_response_code(500);
    die('Erro ao gerar PDF: ' . $e->getMessage());
}