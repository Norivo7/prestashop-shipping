<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_Shipping extends Module
{
    private const PICKUP_MODULES = [
        'ps_pickupinstore',
    ];

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

    public function install(): bool
    {
        return parent::install()
            && $this->registerHook('displayProductAdditionalInfo');
    }

    public function hookDisplayProductAdditionalInfo(array $params): string
    {
        $productId = ($params['product']['id_product'] ?? 0);

        if ($productId <= 0) {
            return '';
        }

        $lowestCost = $this->getLowestShippingCost($productId);

        if ($lowestCost === null) {
            return $this->renderInfo('Lowest delivery cost: unavailable');
        }

        return $this->renderInfo(
            'Lowest delivery cost: <strong>' . Tools::displayPrice($lowestCost) . '</strong>'
        );
    }

    private function getLowestShippingCost(int $productId): ?float
    {
        $product = $this->loadProduct($productId);

        if ($product === null) {
            return null;
        }

        $carriers = $this->getAvailableCarriers();

        if ($carriers === []) {
            return null;
        }

        $lowestCost = null;

        foreach ($carriers as $carrier) {
            if ($this->shouldSkipCarrier($carrier)) {
                continue;
            }

            if ($this->isFreeCarrier($carrier)) {
                return 0.0;
            }

            $cost = $this->estimateCarrierCost($product, $carrier);

            if ($cost === null) {
                continue;
            }

            if ($lowestCost === null || $cost < $lowestCost) {
                $lowestCost = $cost;
            }
        }

        return $lowestCost;
    }

    private function loadProduct(int $productId): ?Product
    {
        $product = new Product($productId, false, $this->context->language->id);

        if (!Validate::isLoadedObject($product)) {
            return null;
        }

        return $product;
    }

    private function getAvailableCarriers(): array
    {
        $carrierRows = Carrier::getCarriers(
            (int) $this->context->language->id,
            true,
            false,
            false,
            null,
            Carrier::ALL_CARRIERS
        );

        if (empty($carrierRows)) {
            return [];
        }

        $carriers = [];

        foreach ($carrierRows as $carrierRow) {
            $carrierId = $carrierRow['id_carrier'];
            $carrier = new Carrier($carrierId);

            if (!Validate::isLoadedObject($carrier)) {
                continue;
            }

            $carriers[] = $carrier;
        }

        return $carriers;
    }

    private function shouldSkipCarrier(Carrier $carrier): bool
    {
        if (!$carrier->active) {
            return true;
        }

        if (in_array($carrier->external_module_name, self::PICKUP_MODULES, true)) {
            return true;
        }

        return false;
    }

    private function isFreeCarrier(Carrier $carrier): bool
    {
        return $carrier->is_free;
    }

    private function estimateCarrierCost(Product $product, Carrier $carrier): ?float
    {
        $zoneId = $this->getDefaultZoneId();

        if ($zoneId === null) {
            return null;
        }

        $shippingMethod = $carrier->getShippingMethod();

        if ($shippingMethod === Carrier::SHIPPING_METHOD_WEIGHT) {
            return $this->getCarrierCostByWeight($product, $carrier, $zoneId);
        }

        if ($shippingMethod === Carrier::SHIPPING_METHOD_PRICE) {
            return $this->getCarrierCostByPrice($product, $carrier, $zoneId);
        }

        return null;
    }

    private function getCarrierCostByWeight(Product $product, Carrier $carrier, int $zoneId): ?float
    {
        $weight = $product->weight;
        $cost = $carrier->getDeliveryPriceByWeight($weight, $zoneId);

        if ($cost === false) {
            return null;
        }

        return $cost;
    }

    private function getCarrierCostByPrice(Product $product, Carrier $carrier, int $zoneId): ?float
    {
        $price = $product->getPrice();
        $cost = $carrier->getDeliveryPriceByPrice($price, $zoneId);

        if ($cost === false) {
            return null;
        }

        return $cost;
    }

    private function getDefaultZoneId(): ?int
    {
        $defaultCountryId = Configuration::get('PS_COUNTRY_DEFAULT');

        if ($defaultCountryId > 0) {
            $country = new Country($defaultCountryId);

            if (Validate::isLoadedObject($country)) {
                return $country->id_zone;
            }
        }

        $defaultZoneId = Configuration::get('PS_ZONE_DEFAULT');

        if ($defaultZoneId > 0) {
            return $defaultZoneId;
        }

        return null;
    }

    private function renderInfo(string $content): string
    {
        return '<div style="margin-top:10px;color:#333;">' . $content . '</div>';
    }
}