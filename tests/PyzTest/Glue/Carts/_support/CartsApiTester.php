<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts;

use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class CartsApiTester extends ApiEndToEndTester
{
    use _generated\CartsApiTesterActions;

    /**
     * @param int $quantity
     * @param string $resourceName
     * @param string $itemSku
     *
     * @return void
     */
    public function seeCartItemQuantityEqualsToQuantityInRequest(int $quantity, string $resourceName, string $itemSku): void
    {
        $includedByTypeAndId = $this->grabIncludedByTypeAndId($resourceName, $itemSku);

        $this->assertArrayHasKey('quantity', $includedByTypeAndId);
        $this->assertEquals($quantity, $includedByTypeAndId['quantity']);
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return sprintf('?%s=%s', RequestConstantsInterface::QUERY_INCLUDE, implode(',', $includes));
    }

    /**
     * @param string $productConcreteSku
     * @param string[] $includes
     *
     * @return string
     */
    public function buildProductConcreteUrl(string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceConcreteProducts}/{productConcreteSku}' . $this->formatQueryInclude($includes),
            [
                'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildCartsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
            ]
        );
    }

    /**
     * @param string $cartUuid
     * @param string[] $includes
     *
     * @return string
     */
    public function buildCartUrl(string $cartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}/{cartUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                'cartUuid' => $cartUuid,
            ]
        );
    }

    /**
     * @param string $cartUuid
     * @param string $cartItemGroupKey
     * @param string[] $includes
     *
     * @return string
     */
    public function buildCartItemUrl(string $cartUuid, string $cartItemGroupKey, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}/{cartUuid}/{resourceCartItems}/{cartItemGroupKey}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                'cartUuid' => $cartUuid,
                'resourceCartItems' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                'cartItemGroupKey' => $cartItemGroupKey,
            ]
        );
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildGuestCartsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            ]
        );
    }

    /**
     * @param string $guestCartUuid
     * @param string[] $includes
     *
     * @return string
     */
    public function buildGuestCartUrl(string $guestCartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}/{guestCartUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
            ]
        );
    }

    /**
     * @param string $guestCartUuid
     * @param string $guestCartItemGroupKey
     * @param string[] $includes
     *
     * @return string
     */
    public function buildGuestCartItemUrl(string $guestCartUuid, string $guestCartItemGroupKey, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}/{guestCartUuid}/{resourceGuestCartItems}/{guestCartItemGroupKey}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
                'resourceGuestCartItems' => CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                'guestCartItemGroupKey' => $guestCartItemGroupKey,
            ]
        );
    }
}
