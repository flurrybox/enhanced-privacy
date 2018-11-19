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

/**
 * Interface ReasonInterface.
 *
 * @api
 * @since 2.0.0
 */
interface ReasonInterface
{
    /**
     * Table.
     */
    const TABLE = 'flurrybox_enhancedprivacy_delete_reasons';

    /**
     * Table fields.
     */
    const ID = 'id';
    const REASON = 'reason';
    const CREATED_AT = 'created_at';

    /**
     * Get reason id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get reason.
     *
     * @return string
     */
    public function getReason();

    /**
     * Get created at time.
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set reason.
     *
     * @param string $reason
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     */
    public function setReason(string $reason);

    /**
     * Set created at time.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     */
    public function setCreatedAt(string $time);
}
