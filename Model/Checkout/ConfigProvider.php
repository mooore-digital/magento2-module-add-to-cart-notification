<?php

declare(strict_types=1);

namespace Marissen\AddToCartNotification\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;
use Marissen\AddToCartNotification\Model\Config;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'add_to_cart_notification' => [
                'enabled' => $this->config->isEnabled(),
                'notificationLifetime' => $this->config->getNotificationLifetime()
            ]
        ];
    }
}
