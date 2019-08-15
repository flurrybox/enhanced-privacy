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

namespace Flurrybox\EnhancedPrivacy\Cron;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface;

/**
 * Class DeletionSchedule.
 */
class Schedule
{
    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * DeletionSchedule constructor.
     *
     * @param PrivacyHelper $privacyHelper
     * @param DateTime $dateTime
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param SearchCriteriaBuilder $criteriaBuilder
     * @param CustomerManagementInterface $customerManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param ResourceConnection $resourceConnection
     * @param LoggerInterface $logger
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        PrivacyHelper $privacyHelper,
        DateTime $dateTime,
        ScheduleRepositoryInterface $scheduleRepository,
        SearchCriteriaBuilder $criteriaBuilder,
        CustomerManagementInterface $customerManagement,
        CustomerRepositoryInterface $customerRepository,
        ResourceConnection $resourceConnection,
        LoggerInterface $logger,
        ManagerInterface $eventManager
    ) {
        $this->privacyHelper = $privacyHelper;
        $this->dateTime = $dateTime;
        $this->scheduleRepository = $scheduleRepository;
        $this->criteriaBuilder = $criteriaBuilder;
        $this->customerManagement = $customerManagement;
        $this->customerRepository = $customerRepository;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->eventManager = $eventManager;
    }

    /**
     * Process scheduled customer deletion and anonymization.
     *
     * @return void
     */
    public function execute()
    {
        if (!($this->privacyHelper->isModuleEnabled() && $this->privacyHelper->isAccountDeletionEnabled())) {
            return;
        }

        $criteria = $this->criteriaBuilder
            ->addFilter(ScheduleInterface::SCHEDULED_AT, date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp()), 'lteq')
            ->create();
        $collection = $this->scheduleRepository->getList($criteria);

        $this->eventManager->dispatch('enhancedprivacy_load_schedule_list', ['collection' => $collection]);

        if (!$collection->getTotalCount()) {
            return;
        }

        foreach ($collection->getItems() as $schedule) {
            try {
                $this->resourceConnection->getConnection()->beginTransaction();

                $customer = $this->customerRepository->getById($schedule->getCustomerId());

                $this->processCustomer($schedule, $customer);

                $this->scheduleRepository->delete($schedule);

                $this->resourceConnection->getConnection()->commit();
            } catch (Exception $e) {
                $this->resourceConnection->getConnection()->rollBack();
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * Process customer deletion or anonymization.
     *
     * @param ScheduleInterface $schedule
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws StateException
     */
    protected function processCustomer(ScheduleInterface $schedule, CustomerInterface $customer)
    {
        switch ($schedule->getType()) {
            case PrivacyHelper::SCHEDULE_TYPE_DELETE:
                $this->customerManagement->deleteCustomer($customer);
                break;

            case PrivacyHelper::SCHEDULE_TYPE_ANONYMIZE:
                $this->customerManagement->anonymizeCustomer($customer);
                break;

            default:
                throw new StateException(__('Unknown schedule type!'));
        }
    }
}
