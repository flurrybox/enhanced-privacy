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

use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ReasonRepositoryInterface.
 *
 * @api
 * @since 2.0.0
 */
interface ReasonRepositoryInterface
{
    /**
     * Get reason.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * Get reasons that match specific criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Save reason object.
     *
     * @param ReasonInterface $reason
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ReasonInterface $reason);

    /**
     * Delete reason object.
     *
     * @param ReasonInterface $reason
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ReasonInterface $reason);

    /**
     * Delete reason by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id);
}
