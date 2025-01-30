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

    static function template($ticket_id = null) 
    {
        // Se não recebeu ticket_id como parâmetro, tenta pegar da requisição
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
    
        $html = '<!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Ordem de Serviço</title>
            <style>
                @page {
                    size: A4;
                    margin: 20mm;
                }
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    color: #333;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #000;
                }
                h1 {
                    text-align: center;
                    font-size: 24px;
                    margin-bottom: 20px;
                }
                .header-table, .signature-table, .material-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                .header-table th, .header-table td, .material-table th, .material-table td, .signature-table td {
                    padding: 10px;
                    border: 1px solid #333;
                    text-align: left;
                }
                .header-table th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                .signature-table {
                    margin-top: 30px;
                }
                .signature-table td {
                    text-align: center;
                    padding-top: 20px;
                }
                .signature-line {
                    display: block;
                    margin-bottom: 5px;
                    border-bottom: 1px solid #000;
                    width: 80%;
                    height: 1px;
                    margin-left: auto;
                    margin-right: auto;
                }
                .section {
                    margin-bottom: 20px;
                }
                .section h2 {
                    font-size: 18px;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #333;
                    padding-bottom: 5px;
                }
                #center {
                    text-align: center;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <h1>Ordem de Serviço</h1>
            
            <table class="header-table">
                <tr>
                    <th>Número do Chamado:</th>
                    <td>' . $dados['numero_chamado'] . '</td>
                    <th>Data de Abertura:</th>
                    <td>' . $dados['data_abertura'] . '</td>
                </tr>
                <tr>
                    <th>Solicitante:</th>
                    <td>' . htmlspecialchars($dados['solicitante']) . '</td>
                    <th>Departamento:</th>
                    <td>' . htmlspecialchars($dados['departamento']) . '</td>
                </tr>
            </table>
            
            <div class="section">
                <h2>Detalhes do Chamado</h2>
                <div><label>Descrição do Problema:</label></div>
                <div>' . nl2br(htmlspecialchars($dados['descricao'])) . '</div>
            </div>
            
            <div class="section">
                <h2>Dados do Técnico</h2>
                <div><strong>Técnico Responsável:</strong> ' . htmlspecialchars($dados['tecnico']) . '</div>
                <div><strong>Data de Início:</strong> ' . $dados['data_inicio'] . '</div>
                <div><strong>Data de Conclusão:</strong> ' . $dados['data_conclusao'] . '</div>
            </div>
            
            <div class="section">
                <h2>Ações Realizadas</h2>
                <div>' . nl2br(htmlspecialchars($dados['solucao'])) . '</div>
            </div>';
    
        if (!empty($dados['itens'])) {
            $html .= '
            <div class="section">
                <h2>Perifericos</h2>
                <table class="material-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Item</th>
                            <th>Número de Serie</th>
                        </tr>
                    </thead>
                    <tbody>';
                    
            foreach ($dados['itens'] as $item) {
                $html .= '
                        <tr>
                            <td>' . htmlspecialchars($item['name']) . '</td>
                            <td>' . htmlspecialchars($item['type']) . '</td>
                            <td>' . htmlspecialchars($item['serial']) . '</td>
                        </tr>';
            }
                    
            $html .= '
                    </tbody>
                </table>
            </div>';
        }
    
        $html .= '
            <div class="section">
                <h2>Assinaturas</h2>
                <table class="signature-table">
                    <tr>
                        <td>
                            <span class="signature-line"></span>
                            Assinatura do Solicitante
                        </td>
                        <td>
                            <span class="signature-line"></span>
                            Assinatura do Técnico
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="footer">
                <p id="center">Ordem de Serviço gerada pelo sistema. Todos os direitos reservados.</p>
            </div>
        </div>
        </body>
        </html>';
    
        // Se estamos gerando PDF, retorna o HTML
        if (defined('GENERATING_PDF')) {
            return $html;
        }
    
        // Se não, exibe normalmente e adiciona o botão
        echo $html;
        echo "<div style='text-align: center; margin-top: 20px;'>
                <button type='button' class='btn btn-primary' onclick='generatePDF()'>Gerar PDF</button>
             </div>";
             echo "<script>
             function generatePDF() {
                 window.location.href = CFG_GLPI.root_doc + '/plugins/orderservice/ajax/config.ajax.php?id=" . $ticket_id . "';
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

