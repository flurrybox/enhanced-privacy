<?php
/**
 * This file is part of the Flurrybox EnhancedPrivacy package.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Flurrybox EnhancedPrivacy
 * to newer versions in the future.
 *
 * @copyright Copyright (c) 2018 Flurrybox, Ltd. (https://flurrybox.com/)
 * @license   GNU General Public License ("GPL") v3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Flurrybox\EnhancedPrivacy\Privacy\Delete;

use Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Wishlist\Model\ResourceModel\Wishlist as WishlistResource;
use Magento\Wishlist\Model\WishlistFactory;

/**
 * Process customer wishlist.
 */
class Wishlist implements DataDeleteInterface
{
    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var WishlistResource
     */
    protected $wishlistResource;

    /**
     * CustomerWishlist constructor.
     *
     * @param WishlistFactory $wishlistFactory
     * @param WishlistResource $wishlistResource
     */
    public function __construct(WishlistFactory $wishlistFactory, WishlistResource $wishlistResource)
    {
        $this->wishlistFactory = $wishlistFactory;
        $this->wishlistResource = $wishlistResource;
    }

    /**
     * Executed upon customer data deletion.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Exception
     */
    public function delete(CustomerInterface $customer)
    {
        $this->processWishlist((int) $customer->getId());
    }

    /**
     * Executed upon customer data anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Exception
     */
    public function anonymize(CustomerInterface $customer)
    {
        $this->processWishlist((int) $customer->getId());
    }

    /**
     * Clear customer wishlist.
     *
     * @param int $customerId
     *
     * @return void
     * @throws \Exception
     */
    protected function processWishlist(int $customerId)
    {
        $wishlist = $this->wishlistFactory
            ->create()
            ->loadByCustomerId($customerId);
        $this->wishlistResource->delete($wishlist);
    }
}
