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

namespace Flurrybox\EnhancedPrivacy\Api;

use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Customer management.
 *
 * @api
 * @since 2.0.0
 */
interface CustomerManagementInterface
{
    /**
     * Check if customer has made any order.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return bool
     */
    public function hasOrders(CustomerInterface $customer);

    /**
     * Check if customer is to be deleted.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return bool
     */
    public function isCustomerToBeDeleted(CustomerInterface $customer);

    /**
     * Process customer delete.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return void
     */
    public function deleteCustomer(CustomerInterface $customer);

    /**
     * Process customer anonymization.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return void
     */
    public function anonymizeCustomer(CustomerInterface $customer);

    /**
     * Cancel customer deletion or anonymization.
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     *
     * @return void
     * @throws \Exception
     */
    public function cancelCustomerDeletion(CustomerInterface $customer);
}
