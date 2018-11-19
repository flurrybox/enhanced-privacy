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

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ScheduleRepository.
 *
 * @api
 * @since 2.0.0
 */
interface ScheduleRepositoryInterface
{
    /**
     * Get schedule.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * Get scheduled items that match specific criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Save schedule object.
     *
     * @param \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface $schedule
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ScheduleInterface $schedule);

    /**
     * Delete schedule object.
     *
     * @param \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface $schedule
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ScheduleInterface $schedule);

    /**
     * Delete schedule object by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id);
}
