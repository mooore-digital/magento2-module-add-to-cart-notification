<?php

declare(strict_types=1);

namespace Mooore\AddToCartNotification\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const CONFIG_PATH_ENABLED = 'checkout/add_to_cart_notification/enabled';
    const CONFIG_PATH_DISABLE_DEFAULT_NOTIFICATION = 'checkout/add_to_cart_notification/disable_default_notification';
    const CONFIG_PATH_NOTIFICATION_LIFETIME = 'checkout/add_to_cart_notification/notification_lifetime';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns true if the functionality is enabled.
     *
     * @param null|string $scopeCode
     * @return bool
     */
    public function isEnabled($scopeCode = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::CONFIG_PATH_ENABLED,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Returns true if default add to cart notification is disabled.
     *
     * @param null|string $scopeCode
     * @return bool
     */
    public function isDefaultNotificationDisabled($scopeCode = null): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::CONFIG_PATH_DISABLE_DEFAULT_NOTIFICATION,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * Returns the lifetime of notification in milliseconds.
     *
     * @param null|string $scopeCode
     * @return int
     */
    public function getNotificationLifetime($scopeCode = null): int
    {
        return (int) $this->scopeConfig->getValue(
            self::CONFIG_PATH_NOTIFICATION_LIFETIME,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }
}
