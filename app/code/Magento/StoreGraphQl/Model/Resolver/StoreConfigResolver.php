<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\StoreGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\StoreGraphQl\Model\Resolver\Store\ExtendedStoreConfigDataProvider;
use Magento\StoreGraphQl\Model\Resolver\Store\StoreConfigDataProvider;

/**
 * StoreConfig page field resolver, used for GraphQL request processing.
 */
class StoreConfigResolver implements ResolverInterface
{
    /**
     * @var StoreConfigDataProvider
     */
    private $storeConfigDataProvider;

    /**
     * @var ExtendedStoreConfigDataProvider
     */
    private $extendedStoreConfigsDataProvider;

    /**
     * @var ValueFactory
     */
    private $valueFactory;

    /**
     * @param StoreConfigDataProvider $storeConfigsDataProvider
     * @param ExtendedStoreConfigDataProvider $extendedStoreConfigsDataProvider
     * @param ValueFactory $valueFactory
     */
    public function __construct(
        StoreConfigDataProvider $storeConfigsDataProvider,
        ExtendedStoreConfigDataProvider $extendedStoreConfigsDataProvider,
        ValueFactory $valueFactory
    ) {
        $this->valueFactory = $valueFactory;
        $this->storeConfigDataProvider = $storeConfigsDataProvider;
        $this->extendedStoreConfigsDataProvider = $extendedStoreConfigsDataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) : Value {

        $storeConfigData = array_merge(
            $this->storeConfigDataProvider->getStoreConfig(),
            $this->extendedStoreConfigsDataProvider->getExtendedConfigs()
        );

        $result = function () use ($storeConfigData) {
            return !empty($storeConfigData) ? $storeConfigData : [];
        };

        return $this->valueFactory->create($result);
    }
}
