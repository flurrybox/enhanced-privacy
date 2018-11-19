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

namespace Flurrybox\EnhancedPrivacy\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ReasonSearchResultsInterface.
 *
 * @api
 * @since 2.0.0
 */
interface ReasonSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get reasons list.
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface[]
     */
    public function getItems();

    /**
     * Set reasons.
     *
     * @param \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface[] $items
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonSearchResultsInterface
     */
    public function setItems(array $items);
}
