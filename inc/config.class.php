<?php

use Dompdf\Dompdf;

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
        $itens = true;

        $options = [];

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
        
        echo '    <table class="header-table">';
        echo '        <tr>';
        echo '            <th>Número do Chamado:</th>';
        echo '            <td>123456</td>';
        echo '            <th>Data de Abertura:</th>';
        echo '            <td>07/11/2024</td>';
        echo '        </tr>';
        echo '        <tr>';
        echo '            <th>Solicitante:</th>';
        echo '            <td>Nome do Solicitante</td>';
        echo '            <th>Departamento:</th>';
        echo '            <td>TI</td>';
        echo '        </tr>';
        echo '    </table>';
        
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
        
        if($itens){
            echo '    <div class="section">';
            echo '        <h2>Perifericos</h2>';
            echo '        <table class="material-table">';
            echo '            <thead>';
            echo '                <tr>';
            echo '                    <th>Nome</th>';
            echo '                    <th>Item</th>';
            echo '                    <th>Número de Serie</th>';
            echo '                </tr>';
            echo '            </thead>';
            echo '            <tbody>';
            echo '                <tr>';
            echo '                    <td>PC01</td>';
            echo '                    <td>Computador</td>';
            echo '                    <td>878484515</td>';
            echo '                </tr>';
            echo '                <tr>';
            echo '                    <td>NTB03</td>';
            echo '                    <td>Notebook</td>';
            echo '                    <td>8489454654</td>';
            echo '                </tr>';
            echo '            </tbody>';
            echo '        </table>';
            echo '    </div>';
        }
        
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
        
        echo '    <div class="footer">';
        echo '        <p id="center">Ordem de Serviço gerada pelo sistema. Todos os direitos reservados.</p>';
        echo '    </div>';
        echo '</div>';
        echo '</body>';
        echo '</html><br>';

        echo "<button type='button' class='btn btn-primary' onclick='generatePDF()'>Gerar PDF</button>";
        echo "<script>
            function generatePDF() {
                window.location.href = '../plugins/orderservice/ajax/config.ajax.php';
            }
        </script>";

        
    }


    public static function generatePDF() {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        ob_start();
        self::template();
        $html = ob_get_clean();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();
    }

}

