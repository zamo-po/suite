<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct;

use Spryker\Zed\PriceProduct\PriceProductDependencyProvider as SprykerPriceProductDependencyProvider;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct\MerchantRelationshipPriceDimensionAbstractWriterPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct\MerchantRelationshipPriceDimensionConcreteWriterPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct\MerchantRelationshipPriceProductDimensionExpanderStrategyPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct\MerchantRelationshipPriceQueryCriteriaPlugin;
use Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct\PriceProductOfferPriceProductExternalProviderPlugin;
use Spryker\Zed\PriceProductVolume\Communication\Plugin\PriceProductExtension\PriceProductVolumeExtractorPlugin;

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class PriceProductDependencyProvider extends SprykerPriceProductDependencyProvider
{
    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[]
     */
    protected function getPriceDimensionQueryCriteriaPlugins(): array
    {
        return array_merge(parent::getPriceDimensionQueryCriteriaPlugins(), [
            new MerchantRelationshipPriceQueryCriteriaPlugin(),
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected function getPriceDimensionAbstractSaverPlugins(): array
    {
        return [
            new MerchantRelationshipPriceDimensionAbstractWriterPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected function getPriceDimensionConcreteSaverPlugins(): array
    {
        return [
            new MerchantRelationshipPriceDimensionConcreteWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface[]
     */
    protected function getPriceProductDimensionExpanderStrategyPlugins(): array
    {
        return [
            new MerchantRelationshipPriceProductDimensionExpanderStrategyPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductExternalProviderPluginInterface[]
     */
    public function getPriceProductExternalProviderPlugins(): array
    {
        return [
            new PriceProductOfferPriceProductExternalProviderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductReaderPricesExtractorPluginInterface[]
     */
    protected function getPriceProductPricesExtractorPlugins(): array
    {
        return [
            new PriceProductVolumeExtractorPlugin(),
        ];
    }
}
