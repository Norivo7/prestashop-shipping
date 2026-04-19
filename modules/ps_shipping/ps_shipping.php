<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_Shipping extends Module
{
    public function __construct()
    {
        $this->name = 'ps_shipping';
        $this->version = '1.0.0';
        $this->author = 'Norivo7';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = 'Shipping';
        $this->description = 'Module for displaying the lowest shipping cost.';
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayProductAdditionalInfo');
    }

    public function hookDisplayProductAdditionalInfo(array $params): string
    {
        $productId = $params['product']['id_product'] ?? 'unknown';

        return '<div style="margin-top:10px;color:#333;">
            Lowest delivery cost: TEST, id:' . $productId . '
        </div>';
    }
}