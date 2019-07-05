<?php

declare(strict_types=1);

namespace Marissen\AddToCartNotification\Controller\Data;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;

class Product extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var ImageFactory
     */
    private $imageFactory;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        JsonFactory $jsonFactory,
        ImageFactory $imageFactory,
        UrlInterface $urlBuilder,
        Context $context
    ) {
        $this->productRepository = $productRepository;
        $this->jsonFactory = $jsonFactory;
        $this->imageFactory = $imageFactory;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $productId = (int) $this->_request->getParam('product_id');

        try {
            /** @var ProductModel $product */
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            throw new NotFoundException(__('Product %1 does not exist.', $productId));
        }



        return $this->jsonFactory->create()->setData([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'image' => $this->getImageUrl($product),
            'cart_url' => $this->urlBuilder->getUrl('checkout/cart')
        ]);
    }

    private function getImageUrl(ProductModel $product): string
    {
        return $this->imageFactory->create()
            ->init($product, 'product_small_image', [])
            ->getUrl();
    }
}
