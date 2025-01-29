<?php

use Dompdf\Dompdf;
use Glpi\Toolbox\Sanitizer;

class PluginOrderserviceConfig extends CommonDBTM
{
    static protected $notable = true;
    
    public static function getMenuName()
    {
        return __('Ordem de Serviço');
    }

    public static function getMenuContent()
    {
        $menu_name = [
            'title' => self::getMenuName(),
            'icon'  => '',
        ];

    }

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        switch(get_class($item)){
            case 'Ticket':
                return array(1 => ('Ordem de Serviço'));
                default:
        }
    }

    static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
        switch (get_class($item)) {
           case 'Ticket':
              $config = new self();
              $config->showFormDisplay();
              break;
        }
        return true;
    }

    static function showFormDisplay() 
    {
        $instance = new self();
        $instance->template();
    }

    static function template($ticket_id = null) {
        if ($ticket_id === null) {
            $ticket_id = $_REQUEST['id'] ?? null;
        }
    
        if (!$ticket_id) {
            return false;
        }
    
        $dados = self::getTicketData($ticket_id);
    
        if (!$dados) {
            return false;
        }
    

        ob_start();
        echo '<!DOCTYPE html>';
        echo '<html lang="pt-br">';
        echo '<head>';
        echo '    <meta charset="UTF-8">';
        echo '    <title>Ordem de Serviço</title>';
        echo '    <style>';
        echo '        /* Configuração da página para impressão em A4 */';
        echo '        @page {';
        echo '            size: A4;';
        echo '            margin: 20mm;';
        echo '        }';
        echo '        body {';
        echo '            font-family: Arial, sans-serif;';
        echo '            margin: 0;';
        echo '            padding: 0;';
        echo '            color: #333;';
        echo '        }';
        echo '        .container {';
        echo '            width: 100%;';
        echo '            max-width: 800px;';
        echo '            margin: auto;';
        echo '            padding: 20px;';
        echo '            border: 1px solid #000;';
        echo '        }';
        echo '        h1 {';
        echo '            text-align: center;';
        echo '            font-size: 24px;';
        echo '            margin-bottom: 20px;';
        echo '        }';
        echo '        .header-table, .signature-table, .material-table {';
        echo '            width: 100%;';
        echo '            border-collapse: collapse;';
        echo '            margin-bottom: 20px;';
        echo '        }';
        echo '        .header-table th, .header-table td, .material-table th, .material-table td, .signature-table td {';
        echo '            padding: 10px;';
        echo '            border: 1px solid #333;';
        echo '            text-align: left;';
        echo '        }';
        echo '        .header-table th {';
        echo '            background-color: #f2f2f2;';
        echo '            font-weight: bold;';
        echo '        }';
        echo '        .signature-table {';
        echo '            margin-top: 30px;';
        echo '        }';
        echo '        .signature-table td {';
        echo '            text-align: center;';
        echo '            padding-top: 20px;';
        echo '        }';
        echo '        .signature-line {';
        echo '            display: block;';
        echo '            margin-bottom: 5px;';
        echo '            border-bottom: 1px solid #000;';
        echo '            width: 80%;';
        echo '            height: 1px;';
        echo '            margin-left: auto;';
        echo '            margin-right: auto;';
        echo '        }';
        echo '        .section {';
        echo '            margin-bottom: 20px;';
        echo '        }';
        echo '        .section h2 {';
        echo '            font-size: 18px;';
        echo '            margin-bottom: 10px;';
        echo '            border-bottom: 1px solid #333;';
        echo '            padding-bottom: 5px;';
        echo '        }';
        echo '        #center {';
        echo '            text-align: center;';
        echo '        }';
        echo '    </style>';
        echo '</head>';
        echo '<body>';
        echo '<div class="container">';
        echo '    <h1>Ordem de Serviço</h1>';
        
        // Dados do ticket
        echo '    <table class="header-table">';
        echo '        <tr>';
        echo '            <th>Número do Chamado:</th>';
        echo '            <td>' . $dados['numero_chamado'] . '</td>';
        echo '            <th>Data de Abertura:</th>';
        echo '            <td>' . $dados['data_abertura'] . '</td>';
        echo '        </tr>';
        echo '        <tr>';
        echo '            <th>Solicitante:</th>';
        echo '            <td>' . $dados['solicitante'] . '</td>';
        echo '            <th>Departamento:</th>';
        echo '            <td>' . $dados['departamento'] . '</td>';
        echo '        </tr>';
        echo '    </table>';
        
        echo '    <div class="section">';
        echo '        <h2>Detalhes do Chamado</h2>';
        //echo '        <div><label>Descrição do Problema:</label></div>';
        echo '        <div>' . htmlspecialchars_decode($dados['descricao']) . '</div>';
        echo '    </div>';
        
        // Dados do técnico
        echo '    <div class="section">';
        echo '        <h2>Dados do Técnico</h2>';
        echo '        <div><strong>Técnico Responsável:</strong> ' . $dados['tecnico'] . '</div>';
        echo '        <div><strong>Data de Início:</strong> ' . $dados['data_inicio'] . '</div>';
        echo '        <div><strong>Data de Conclusão:</strong> ' . $dados['data_conclusao'] . '</div>';
        echo '    </div>';
        
        // Ações realizadas
        echo '    <div class="section">';
        echo '        <h2>Ações Realizadas</h2>';
        echo '        <div>' . htmlspecialchars_decode($dados['solucao']) . '</div>';
        echo '    </div>';
        
        // Itens relacionados
        if (!empty($dados['itens'])) {
            echo '    <div class="section">';
            echo '        <h2>Itens Relacionados</h2>';
            echo '        <table class="material-table">';
            echo '            <thead>';
            echo '                <tr>';
            echo '                    <th>Nome</th>';
            echo '                    <th>Tipo</th>';
            echo '                    <th>Número de Série</th>';
            echo '                </tr>';
            echo '            </thead>';
            echo '            <tbody>';
            
            foreach ($dados['itens'] as $item) {
                echo '                <tr>';
                echo '                    <td>' . $item['name'] . '</td>';
                echo '                    <td>' . $item['type'] . '</td>';
                echo '                    <td>' . $item['serial'] . '</td>';
                echo '                </tr>';
            }
            
            echo '            </tbody>';
            echo '        </table>';
            echo '    </div>';
        }
        
        // Assinaturas
        echo '    <div class="section">';
        echo '        <h2>Assinaturas</h2>';
        echo '        <table class="signature-table">';
        echo '            <tr>';
        echo '                <td>';
        echo '                    <span class="signature-line"></span>';
        echo '                    Assinatura do Solicitante';
        echo '                </td>';
        echo '                <td>';
        echo '                    <span class="signature-line"></span>';
        echo '                    Assinatura do Técnico';
        echo '                </td>';
        echo '            </tr>';
        echo '        </table>';
        echo '    </div>';
        
        echo '</div>';
        echo '</body>';
        echo '</html>';
        $html = ob_get_clean();
    

        if (defined('GENERATING_PDF')) {
            return $html;
        }
    
        echo $html;
    
        // Gerar o PDF
        echo "<div style='text-align: center; margin-top: 20px;'>
                <button type='button' class='btn btn-primary' onclick='generatePDF()'>Gerar PDF</button>
              </div>";
        echo "<script>
                function generatePDF() {
                    window.location.href = '../plugins/orderservice/ajax/config.ajax.php?id=" . $ticket_id . "';
                }
              </script>";
    }

    
    static function getTicketData($ticket_id) {
        global $DB;
        
        $ticket = new Ticket();
        if (!$ticket->getFromDB($ticket_id)) {
            return false;
        }
        
        $user = new User();
        $user->getFromDB($ticket->fields['users_id_recipient']);
        

        $grupo_usuario = new Group_User();
        $grupos = $grupo_usuario->find(['users_id' => $ticket->fields['users_id_recipient']]);
        $departamento = '';
        
        foreach ($grupos as $grupo) {
            $group = new Group();
            if ($group->getFromDB($grupo['groups_id'])) {
                $departamento = $group->fields['name'];
                break;
            }
        }
        
        $tech = new User();
        $tech->getFromDB($ticket->fields['users_id_lastupdater']);
        
        $solution = new ITILSolution();
        $solucao = $solution->find(['items_id' => $ticket_id, 'itemtype' => 'Ticket'], ['date_creation DESC']);
        $ultima_solucao = reset($solucao);
        
        $item_ticket = new Item_Ticket();
        $items = $item_ticket->find(['tickets_id' => $ticket_id]);
        
        $itens_info = [];
        foreach ($items as $item) {
            $itemtype = $item['itemtype'];
            $item_obj = new $itemtype();
            
            if ($item_obj->getFromDB($item['items_id'])) {
                $itens_info[] = [
                    'name' => $item_obj->fields['name'],
                    'type' => $itemtype,
                    'serial' => isset($item_obj->fields['serial']) ? $item_obj->fields['serial'] : ''
                ];
            }
        }
        
        return [
            'numero_chamado' => $ticket->fields['id'],
            'data_abertura' => date('d/m/Y', strtotime($ticket->fields['date'])),
            'solicitante' => $user->fields['firstname'] . ' ' . $user->fields['realname'],
            'departamento' => $departamento,
            'descricao' => ($ticket->fields['content']),
            'tecnico' => $tech->fields['realname'] . ' ' . $tech->fields['firstname'],
            'data_inicio' => date('d/m/Y', strtotime($ticket->fields['date'])),
            'data_conclusao' => $ultima_solucao ? date('d/m/Y', strtotime($ultima_solucao['date_creation'])) : '',
            'solucao' => $ultima_solucao ? $ultima_solucao['content'] : '',
            'itens' => $itens_info
        ];
    }
        
}

