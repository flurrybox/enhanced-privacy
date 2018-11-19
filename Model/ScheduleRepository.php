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

use Exception;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleSearchResultsInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Model\ResourceModel\Schedule\CollectionFactory as ScheduleCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class ScheduleRepository.
 *
 * @api
 * @since 2.0.0
 */
class ScheduleRepository implements ScheduleRepositoryInterface
{
    /**
     * @var ScheduleInterfaceFactory
     */
    protected $scheduleFactory;

    /**
     * @var ResourceModel\Schedule
     */
    protected $scheduleResource;

    /**
     * @var ScheduleCollectionFactory
     */
    protected $scheduleCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ScheduleSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * ScheduleRepository constructor.
     *
     * @param ScheduleInterfaceFactory $scheduleFactory
     * @param ResourceModel\Schedule $scheduleResource
     * @param ScheduleCollectionFactory $scheduleCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ScheduleSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ScheduleInterfaceFactory $scheduleFactory,
        ResourceModel\Schedule $scheduleResource,
        ScheduleCollectionFactory $scheduleCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ScheduleSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleResource = $scheduleResource;
        $this->scheduleCollectionFactory = $scheduleCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get schedule.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id)
    {
        $schedule = $this->scheduleFactory->create();
        $this->scheduleResource->load($schedule, $id);

        if (!$schedule->getId()) {
            throw new NoSuchEntityException(__('Requested schedule doesn\'t exist!'));
        }

        return $schedule;
    }

    /**
     * Get schedules that match specific criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->scheduleCollectionFactory->create();
        $collection->addFieldToSelect('*');

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult
            ->setSearchCriteria($searchCriteria)
            ->setItems($collection->getItems())
            ->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Save schedule object.
     *
     * @param ScheduleInterface $schedule
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ScheduleInterface $schedule)
    {
        try {
            $this->scheduleResource->save($schedule);
        } catch (AlreadyExistsException $e) {
            throw new StateException(__('Schedule could not be saved!'));
        } catch (Exception $e) {
            throw new StateException(__('Schedule could not be saved!'));
        }

        return $schedule;
    }

    /**
     * Delete schedule object.
     *
     * @param ScheduleInterface $schedule
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ScheduleInterface $schedule)
    {
        try {
            $this->scheduleResource->delete($schedule);
        } catch (Exception $e) {
            throw new StateException(__('Schedule could not be deleted!'));
        }

        return true;
    }

    /**
     * Delete schedule object by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->get($id));
    }
}
