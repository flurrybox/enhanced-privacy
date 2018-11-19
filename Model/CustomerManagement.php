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

namespace Flurrybox\EnhancedPrivacy\Model;

use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface;
use Flurrybox\EnhancedPrivacy\Model\ResourceModel\Schedule as ScheduleResource;
use Flurrybox\EnhancedPrivacy\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;

/**
 * Customer management.
 *
 * @api
 * @since 2.0.0
 */
class CustomerManagement implements CustomerManagementInterface
{
    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var ScheduleCollectionFactory
     */
    protected $scheduleCollectionFactory;

    /**
     * @var ProcessorsInterface
     */
    protected $processors;

    /**
     * @var ScheduleResource
     */
    protected $scheduleResource;

    /**
     * CustomerManagement constructor.
     *
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     * @param ProcessorsInterface $processors
     * @param ScheduleResource $scheduleResource
     */
    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        ScheduleCollectionFactory $scheduleCollectionFactory,
        ProcessorsInterface $processors,
        ScheduleResource $scheduleResource
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->scheduleCollectionFactory = $scheduleCollectionFactory;
        $this->processors = $processors;
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * Check if customer has made any order.
     *
     * @param CustomerInterface $customer
     *
     * @return bool
     */
    public function hasOrders(CustomerInterface $customer)
    {
        return (bool) $this->orderCollectionFactory->create($customer->getId())->getTotalCount();
    }

    /**
     * Check if customer is to be deleted.
     *
     * @param CustomerInterface $customer
     *
     * @return bool
     */
    public function isCustomerToBeDeleted(CustomerInterface $customer)
    {
        return (bool) $this->scheduleCollectionFactory
            ->create()
            ->getItemByColumnValue(ScheduleInterface::CUSTOMER_ID, $customer->getId());
    }

    /**
     * Process customer delete.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     */
    public function deleteCustomer(CustomerInterface $customer)
    {
        foreach ($this->processors->getDeleteProcessors() as $processor) {
            $processor->delete($customer);
        }
    }

    /**
     * Process customer anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     */
    public function anonymizeCustomer(CustomerInterface $customer)
    {
        foreach ($this->processors->getDeleteProcessors() as $processor) {
            $processor->anonymize($customer);
        }
    }

    /**
     * Cancel customer deletion or anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Exception
     */
    public function cancelCustomerDeletion(CustomerInterface $customer)
    {
        /** @var \Flurrybox\EnhancedPrivacy\Model\CronSchedule $scheduleItem */
        $scheduleItem = $this->scheduleCollectionFactory
            ->create()
            ->getItemByColumnValue(ScheduleInterface::CUSTOMER_ID, $customer->getId());

        if (!$scheduleItem) {
            return;
        }

        $this->scheduleResource->delete($scheduleItem);
    }
}
