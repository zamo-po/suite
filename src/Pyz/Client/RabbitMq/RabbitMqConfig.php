<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\RabbitMq;

use ArrayObject;
use Generated\Shared\Transfer\RabbitMqOptionTransfer;
use Spryker\Client\RabbitMq\Model\Connection\Connection;
use Spryker\Client\RabbitMq\RabbitMqConfig as SprykerRabbitMqConfig;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\CompanyUserStorage\CompanyUserStorageConfig;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Shared\Event\EventConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Shared\MerchantProfileStorage\MerchantProfileStorageConfig;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Shared\ProductOfferAvailabilityStorage\ProductOfferAvailabilityStorageConfig;
use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\ShoppingListStorage\ShoppingListStorageConfig;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConstants;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RabbitMqConfig extends SprykerRabbitMqConfig
{
    /**
     * @return \ArrayObject
     */
    protected function getQueueOptions()
    {
        $queueOptionCollection = new ArrayObject();
        $queueOptionCollection->append($this->createQueueOption(EventConstants::EVENT_QUEUE, EventConstants::EVENT_QUEUE_RETRY, EventConfig::EVENT_ROUTING_KEY_RETRY));
        $queueOptionCollection->append($this->createQueueOption(EventConstants::EVENT_QUEUE, EventConstants::EVENT_QUEUE_ERROR, EventConfig::EVENT_ROUTING_KEY_ERROR));
        $queueOptionCollection->append($this->createQueueOption(GlossaryStorageConstants::SYNC_STORAGE_QUEUE, GlossaryStorageConstants::SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(UrlStorageConstants::URL_SYNC_STORAGE_QUEUE, UrlStorageConstants::URL_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_QUEUE, AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_QUEUE, CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CategoryStorageConstants::CATEGORY_SYNC_STORAGE_QUEUE, CategoryStorageConstants::CATEGORY_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ProductStorageConstants::PRODUCT_SYNC_STORAGE_QUEUE, ProductStorageConstants::PRODUCT_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(PriceProductStorageConstants::PRICE_SYNC_STORAGE_QUEUE, PriceProductStorageConstants::PRICE_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_SYNC_STORAGE_QUEUE, ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_QUEUE, ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_QUEUE, ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CmsStorageConstants::CMS_SYNC_STORAGE_QUEUE, CmsStorageConstants::CMS_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_QUEUE, CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CmsPageSearchConstants::CMS_SYNC_SEARCH_QUEUE, CmsPageSearchConstants::CMS_SYNC_SEARCH_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ProductPageSearchConstants::PRODUCT_SYNC_SEARCH_QUEUE, ProductPageSearchConstants::PRODUCT_SYNC_SEARCH_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(FileManagerStorageConstants::FILE_SYNC_STORAGE_QUEUE, FileManagerStorageConstants::FILE_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ShoppingListStorageConfig::SHOPPING_LIST_SYNC_STORAGE_QUEUE, ShoppingListStorageConfig::SHOPPING_LIST_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(CompanyUserStorageConfig::COMPANY_USER_SYNC_STORAGE_QUEUE, CompanyUserStorageConfig::COMPANY_USER_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ContentStorageConfig::CONTENT_SYNC_STORAGE_QUEUE, ContentStorageConfig::CONTENT_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_QUEUE, TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(TaxStorageConfig::TAX_SET_SYNC_STORAGE_QUEUE, TaxStorageConfig::TAX_SET_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(MerchantProfileStorageConfig::MERCHANT_PROFILE_SYNC_STORAGE_QUEUE, MerchantProfileStorageConfig::MERCHANT_PROFILE_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(MerchantOpeningHoursStorageConfig::MERCHANT_OPENING_HOURS_SYNC_STORAGE_QUEUE, MerchantOpeningHoursStorageConfig::MERCHANT_OPENING_HOURS_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(MerchantProductOfferStorageConfig::MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_QUEUE, MerchantProductOfferStorageConfig::MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_QUEUE, PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append($this->createQueueOption(ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_QUEUE, ProductOfferAvailabilityStorageConfig::PRODUCT_OFFER_AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE));
        $queueOptionCollection->append(
            $this->createQueueOption(
                $this->get(LogConstants::LOG_QUEUE_NAME),
                $this->get(LogConstants::LOG_ERROR_QUEUE_NAME)
            )
        );

        return $queueOptionCollection;
    }

    /**
     * @param string $queueName
     * @param string $errorQueueName
     * @param string $routingKey
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createQueueOption($queueName, $errorQueueName, $routingKey = 'error')
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($queueName)
            ->setDurable(true)
            ->setType('direct')
            ->setDeclarationType(Connection::RABBIT_MQ_EXCHANGE)
            ->addBindingQueueItem($this->createQueueBinding($queueName))
            ->addBindingQueueItem($this->createErrorQueueBinding($errorQueueName, $routingKey));

        return $queueOptionTransfer;
    }

    /**
     * @param string $queueName
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createQueueBinding($queueName)
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($queueName)
            ->setDurable(true)
            ->setNoWait(false)
            ->addRoutingKey('');

        return $queueOptionTransfer;
    }

    /**
     * @param string $errorQueueName
     * @param string $routingKey
     *
     * @return \Generated\Shared\Transfer\RabbitMqOptionTransfer
     */
    protected function createErrorQueueBinding($errorQueueName, $routingKey)
    {
        $queueOptionTransfer = new RabbitMqOptionTransfer();
        $queueOptionTransfer
            ->setQueueName($errorQueueName)
            ->setDurable(true)
            ->setNoWait(false)
            ->addRoutingKey($routingKey);

        return $queueOptionTransfer;
    }
}
