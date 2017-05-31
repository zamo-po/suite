<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\NavigationNode;

use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributesQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Pyz\Zed\DataImport\Business\Exception\NavigationNodeByKeyNotFoundException;
use Pyz\Zed\DataImport\Business\Model\Navigation\NavigationKeyToIdNavigationStep;
use Pyz\Zed\DataImport\Business\Model\Product\LocalizedAttributesExtractorStep;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\TouchAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class NavigationNodeWriterStep extends TouchAwareStep implements DataImportStepInterface
{

    const DATA_SET_KEY_NAVIGATION_KEY = 'navigation_key';
    const DATA_SET_KEY_NODE_KEY = 'node_key';
    const DATA_SET_KEY_PARENT_NODE_KEY = 'parent_node_key';
    const DATA_SET_KEY_POSITION = 'position';
    const DATA_SET_KEY_NODE_TYPE = 'node_type';
    const DATA_SET_KEY_TITLE = 'title';
    const DATA_SET_KEY_URL = 'url';
    const DATA_SET_KEY_CSS_CLASS = 'css_class';

    const DEFAULT_IS_ACTIVE = true;

    const BULK_SIZE = 50;

    const TOUCH_ITEM_TYPE_KEY = 'touchItemType';
    const TOUCH_ITEM_ID_KEY = 'touchItemId';

    const NODE_TYPE_LINK = 'link';
    const NODE_TYPE_EXTERNAL_URL = 'external_url';
    const NODE_TYPE_CATEGORY = 'category';
    const NODE_TYPE_CMS_PAGE = 'cms_page';
    const DATA_SET_KEY_IS_ACTIVE = 'is_active';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $query = SpyNavigationNodeQuery::create();
        $navigationNodeEntity = $query
            ->filterByFkNavigation($dataSet[NavigationKeyToIdNavigationStep::KEY_TARGET])
            ->filterByNodeKey($dataSet[static::DATA_SET_KEY_NODE_KEY])
            ->findOneOrCreate();

        $navigationNodeEntity->setPosition($this->getPosition($navigationNodeEntity, $dataSet));
        $navigationNodeEntity->setIsActive($this->getIsActive($navigationNodeEntity, $dataSet));
        $navigationNodeEntity->setNodeType($this->getNodeType($navigationNodeEntity, $dataSet));

        if (!empty($dataSet[static::DATA_SET_KEY_PARENT_NODE_KEY])) {
            $navigationNodeEntity->setFkParentNavigationNode(
                $this->getFkParentNavigationNode($dataSet[static::DATA_SET_KEY_PARENT_NODE_KEY])
            );
        }

        foreach ($dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $query = SpyNavigationNodeLocalizedAttributesQuery::create();
            $navigationNodeLocalizedAttributesEntity = $query
                ->filterByFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $navigationNodeLocalizedAttributesEntity->setTitle($this->getTitle($navigationNodeLocalizedAttributesEntity, $localizedAttributes));

            if ($navigationNodeEntity->getNodeType() === static::NODE_TYPE_LINK) {
                $navigationNodeLocalizedAttributesEntity->setLink($this->getLink($navigationNodeLocalizedAttributesEntity, $localizedAttributes));
            }

            if ($navigationNodeEntity->getNodeType() === static::NODE_TYPE_EXTERNAL_URL) {
                $navigationNodeLocalizedAttributesEntity->setExternalUrl($this->getExternalUrl($navigationNodeLocalizedAttributesEntity, $localizedAttributes));
            }

            if ($navigationNodeEntity->getNodeType() === static::NODE_TYPE_CATEGORY || $navigationNodeEntity->getNodeType() === static::NODE_TYPE_CMS_PAGE) {
                $navigationNodeLocalizedAttributesEntity->setFkUrl($this->getFkUrl($navigationNodeLocalizedAttributesEntity, $localizedAttributes, $idLocale));
            }

            $navigationNodeLocalizedAttributesEntity->setCssClass($this->getCssClass($navigationNodeLocalizedAttributesEntity, $localizedAttributes));

            $navigationNodeEntity->addSpyNavigationNodeLocalizedAttributes($navigationNodeLocalizedAttributesEntity);
        }

        $navigationNodeEntity->save();

        $this->addMainTouchable(NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU, $navigationNodeEntity->getFkNavigation());
    }

    /**
     * @param string $nodeKey
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\NavigationNodeByKeyNotFoundException
     *
     * @return int
     */
    protected function getFkParentNavigationNode($nodeKey)
    {
        $query = SpyNavigationNodeQuery::create();
        $parentNavigationNodeEntity = $query->findOneByNodeKey($nodeKey);

        if (!$parentNavigationNodeEntity) {
            throw new NavigationNodeByKeyNotFoundException(sprintf(
                'NavigationNode with key "%s" not found',
                $nodeKey
            ));
        }

        return $parentNavigationNodeEntity->getIdNavigationNode();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getPosition(SpyNavigationNode $navigationNodeEntity, DataSetInterface $dataSet)
    {
        if (isset($dataSet[static::DATA_SET_KEY_POSITION]) && !empty($dataSet[static::DATA_SET_KEY_POSITION])) {
            return (int)$dataSet[static::DATA_SET_KEY_POSITION];
        }

        return $navigationNodeEntity->getPosition();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getIsActive(SpyNavigationNode $navigationNodeEntity, DataSetInterface $dataSet)
    {
        if (isset($dataSet[static::DATA_SET_KEY_IS_ACTIVE]) && !empty($dataSet[static::DATA_SET_KEY_IS_ACTIVE])) {
            return (bool)$dataSet[static::DATA_SET_KEY_IS_ACTIVE];
        }

        if ($navigationNodeEntity->getIsActive() !== null) {
            return $navigationNodeEntity->getIsActive();
        }

        return static::DEFAULT_IS_ACTIVE;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getNodeType(SpyNavigationNode $navigationNodeEntity, DataSetInterface $dataSet)
    {
        if (isset($dataSet[static::DATA_SET_KEY_NODE_TYPE]) && !empty($dataSet[static::DATA_SET_KEY_NODE_TYPE])) {
            return $dataSet[static::DATA_SET_KEY_NODE_TYPE];
        }

        return $navigationNodeEntity->getNodeType();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     * @param array $localizedAttributes
     *
     * @return string
     */
    protected function getTitle(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes, array $localizedAttributes)
    {
        if (isset($localizedAttributes[static::DATA_SET_KEY_TITLE]) && !empty($localizedAttributes[static::DATA_SET_KEY_TITLE])) {
            return $localizedAttributes[static::DATA_SET_KEY_TITLE];
        }

        return $navigationNodeLocalizedAttributes->getTitle();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     * @param array $localizedAttributes
     *
     * @return string
     */
    protected function getLink(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes, array $localizedAttributes)
    {
        if (isset($localizedAttributes[static::DATA_SET_KEY_URL]) && !empty($localizedAttributes[static::DATA_SET_KEY_URL])) {
            return $localizedAttributes[static::DATA_SET_KEY_URL];
        }

        return $navigationNodeLocalizedAttributes->getLink();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     * @param array $localizedAttributes
     *
     * @return string
     */
    protected function getExternalUrl(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes, array $localizedAttributes)
    {
        if (isset($localizedAttributes[static::DATA_SET_KEY_URL]) && !empty($localizedAttributes[static::DATA_SET_KEY_URL])) {
            return $localizedAttributes[static::DATA_SET_KEY_URL];
        }

        return $navigationNodeLocalizedAttributes->getExternalUrl();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     * @param array $localizedAttributes
     * @param int $idLocale
     *
     * @return int
     */
    protected function getFkUrl(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes, array $localizedAttributes, $idLocale)
    {
        if (isset($localizedAttributes[static::DATA_SET_KEY_URL]) && !empty($localizedAttributes[static::DATA_SET_KEY_URL])) {
            $query = SpyUrlQuery::create();
            $urlEntity = $query
                ->filterByFkLocale($idLocale)
                ->filterByUrl($localizedAttributes[static::DATA_SET_KEY_URL])
                ->findOne();

            if ($urlEntity) {
                return $urlEntity->getIdUrl();
            }
        }

        return $navigationNodeLocalizedAttributes->getFkUrl();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     * @param array $localizedAttributes
     *
     * @return string
     */
    protected function getCssClass(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes, array $localizedAttributes)
    {
        if (isset($localizedAttributes[static::DATA_SET_KEY_CSS_CLASS]) && !empty($localizedAttributes[static::DATA_SET_KEY_CSS_CLASS])) {
            return $localizedAttributes[static::DATA_SET_KEY_CSS_CLASS];
        }

        return $navigationNodeLocalizedAttributes->getCssClass();
    }

}
