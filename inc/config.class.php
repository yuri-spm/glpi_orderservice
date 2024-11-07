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
}