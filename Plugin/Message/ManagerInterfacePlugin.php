<?php

declare(strict_types=1);

namespace Mooore\AddToCartNotification\Plugin\Message;

use Mooore\AddToCartNotification\Model\Config;

class ManagerInterfacePlugin
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
     * Disable addCartSuccessMessage notification.
     *
     * @param \Magento\Framework\Message\ManagerInterface $subject
     * @param callable $proceed
     * @param string $identifier
     * @param array $data
     * @param null $group
     * @return \Magento\Framework\Message\ManagerInterface
     * @see \Magento\Framework\Message\ManagerInterface::addComplexSuccessMessage()
     */
    public function aroundAddComplexSuccessMessage(
        \Magento\Framework\Message\ManagerInterface $subject,
        callable $proceed,
        $identifier,
        array $data = [],
        $group = null
    ) {
        if ($identifier === 'addCartSuccessMessage' && $this->config->isEnabled()) {
            return $subject;
        }

        return $proceed($identifier, $data, $group);
    }
}
