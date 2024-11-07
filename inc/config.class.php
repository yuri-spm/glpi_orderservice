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

}