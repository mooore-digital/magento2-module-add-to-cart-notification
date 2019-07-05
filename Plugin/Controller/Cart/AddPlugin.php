<?php

declare(strict_types=1);

namespace Marissen\AddToCartNotification\Plugin\Controller\Cart;

use Magento\Checkout\Controller\Cart\Add as Subject;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Serialize\Serializer\Json;

class AddPlugin
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var Json
     */
    private $jsonSerializer;

    public function __construct(ManagerInterface $messageManager, Json $jsonSerializer)
    {
        $this->messageManager = $messageManager;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function afterExecute(Subject $subject, $result)
    {
        /** @var HttpRequest $request */
        $request = $subject->getRequest();
        /** @var HttpResponse $response */
        $response = $subject->getResponse();

        if (!$request->isAjax()) {
            return $result;
        }

        $data = $this->jsonSerializer->unserialize(
            $response->getBody()
        );

        if (isset($data['backUrl'])) {
            unset($data['backUrl']);
            foreach ($this->getMessages() as $message) {
                $data['error_messages'][] = [
                    'type' => $message->getType(),
                    'text' => $message->getText()
                ];
            }
        }

        $response->setBody(
            $this->jsonSerializer->serialize($data)
        );

        return $result;
    }

    /**
     * @return MessageInterface[]
     */
    private function getMessages(): array
    {
        return $this->messageManager->getMessages(true)->getItems();
    }
}
