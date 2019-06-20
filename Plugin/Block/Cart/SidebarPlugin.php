<?php

declare(strict_types=1);

namespace Marissen\AddToCartNotification\Plugin\Block\Cart;

use Marissen\AddToCartNotification\Model\Checkout\ConfigProvider;

class SidebarPlugin
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    /**
     * @param \Magento\Checkout\Block\Cart\Sidebar $subject
     * @param array $result
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetConfig(\Magento\Checkout\Block\Cart\Sidebar $subject, array $result): array
    {
        return array_merge_recursive($result, $this->configProvider->getConfig());
    }
}
