<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CmsBlock;

use Spryker\Zed\CmsBlock\CmsBlockDependencyProvider as CmsBlockCmsBlockDependencyProvider;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CmsBlockCategoryConnectorUpdatePlugin;
use Spryker\Zed\CmsBlockProductConnector\Communication\Plugin\CmsBlockProductAbstractUpdatePlugin;

class CmsBlockDependencyProvider extends CmsBlockCmsBlockDependencyProvider
{
    /**
     * @return \Spryker\Zed\CmsBlock\Communication\Plugin\CmsBlockUpdatePluginInterface[]
     */
    protected function getCmsBlockUpdatePlugins(): array
    {
        $plugins = parent::getCmsBlockUpdatePlugins();

        return array_merge($plugins, [
            new CmsBlockCategoryConnectorUpdatePlugin(),
            new CmsBlockProductAbstractUpdatePlugin(),
        ]);
    }
}
