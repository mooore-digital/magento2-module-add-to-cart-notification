<?php

declare(strict_types=1);

namespace Mooore\AddToCartNotification\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Mooore\AddToCartNotification\Model\Config;

class AddToCartNotification implements ArgumentInterface
{
    public function __construct(
        private Config $config
    ) {
        //
    }

    public function getNotificationLifetime(): int
    {
        return $this->config->getNotificationLifetime();
    }
}
