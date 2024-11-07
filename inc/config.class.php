<?php

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


    static function template()
    {
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
        echo '        .header,';
        echo '        .footer {';
        echo '            text-align: center;';
        echo '            margin-bottom: 20px;';
        echo '        }';
        echo '        .section {';
        echo '            margin-bottom: 20px;';
        echo '        }';
        echo '        .section label {';
        echo '            font-weight: bold;';
        echo '        }';
        echo '        .section div {';
        echo '            margin-bottom: 5px;';
        echo '        }';
        echo '        table {';
        echo '            width: 100%;';
        echo '            border-collapse: collapse;';
        echo '            margin-top: 10px;';
        echo '        }';
        echo '        table, th, td {';
        echo '            border: 1px solid #333;';
        echo '        }';
        echo '        th, td {';
        echo '            padding: 8px;';
        echo '            text-align: left;';
        echo '        }';
        echo '    </style>';
        echo '</head>';
        echo '<body>';
        echo '<div class="container">';
        echo '    <h1>Ordem de Serviço</h1>';
        echo '    <div class="header">';
        echo '        <div><strong>Número do Chamado:</strong> #123456</div>';
        echo '        <div><strong>Data de Abertura:</strong> 07/11/2024</div>';
        echo '        <div><strong>Solicitante:</strong> Nome do Solicitante</div>';
        echo '        <div><strong>Departamento:</strong> TI</div>';
        echo '    </div>';
        
        echo '    <div class="section">';
        echo '        <h2>Detalhes do Chamado</h2>';
        echo '        <div><label>Descrição do Problema:</label></div>';
        echo '        <div>Descrição breve do problema relatado pelo usuário.</div>';
        echo '    </div>';
        
        echo '    <div class="section">';
        echo '        <h2>Dados do Técnico</h2>';
        echo '        <div><strong>Técnico Responsável:</strong> Nome do Técnico</div>';
        echo '        <div><strong>Data de Início:</strong> 07/11/2024</div>';
        echo '        <div><strong>Data de Conclusão:</strong> 08/11/2024</div>';
        echo '    </div>';
        
        echo '    <div class="section">';
        echo '        <h2>Ações Realizadas</h2>';
        echo '        <div>Descrição das ações tomadas para resolver o problema.</div>';
        echo '    </div>';
        
        echo '    <div class="section">';
        echo '        <h2>Materiais Utilizados</h2>';
        echo '        <table>';
        echo '            <thead>';
        echo '                <tr>';
        echo '                    <th>Material</th>';
        echo '                    <th>Quantidade</th>';
        echo '                    <th>Observação</th>';
        echo '                </tr>';
        echo '            </thead>';
        echo '            <tbody>';
        echo '                <tr>';
        echo '                    <td>Cabo de Rede</td>';
        echo '                    <td>1</td>';
        echo '                    <td>Troca do cabo</td>';
        echo '                </tr>';
        echo '                <tr>';
        echo '                    <td>Placa de Rede</td>';
        echo '                    <td>1</td>';
        echo '                    <td>Substituição</td>';
        echo '                </tr>';
        echo '            </tbody>';
        echo '        </table>';
        echo '    </div>';
        
        echo '    <div class="section">';
        echo '        <h2>Assinaturas</h2>';
        echo '        <div><label>Assinatura do Solicitante:</label> ________________________________</div>';
        echo '        <div><label>Assinatura do Técnico:</label> ________________________________</div>';
        echo '    </div>';
        
        echo '    <div class="footer">';
        echo '        <p>Ordem de Serviço gerada pelo sistema. Todos os direitos reservados.</p>';
        echo '    </div>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
    }
}