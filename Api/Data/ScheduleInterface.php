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

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ScheduleInterface.
 *
 * @api
 * @since 2.0.0
 */
interface ScheduleInterface extends ExtensibleDataInterface
{
    /**
     * Table name.
     */
    const TABLE = 'flurrybox_enhancedprivacy_cleanup_schedule';

    /**#@+
     * Table column.
     */
    const ID = 'id';
    const SCHEDULED_AT = 'scheduled_at';
    const CUSTOMER_ID = 'customer_id';
    const TYPE = 'type';
    const REASON_ID = 'reason_id';
    /**#@-*/

    /**#@+
     * Schedule type.
     */
    const TYPE_DELETE = 1;
    const TYPE_ANONYMIZE = 2;
    /**#@-*/

    /**
     * Get schedule id.
     *
     * @return int
     */
    public function getId();

    /**
     * Get schedule at time.
     *
     * @return string
     */
    public function getScheduledAt();

    /**
     * Set scheduled at time.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setScheduledAt(string $time);

    /**
     * Get customer id.
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Set customer id.
     *
     * @param int $customerId
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setCustomerId(int $customerId);

    /**
     * Get schedule type.
     *
     * @return string
     */
    public function getType();

    /**
     * Get deletion reason id.
     *
     * @return int
     */
    public function getReasonId();



    /**
     * Set schedule type.
     *
     * @param string $type
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setType(string $type);

    /**
     * Set deletion reason id.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setReasonId(int $id);

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setExtensionAttributes(ScheduleExtensionInterface $extensionAttributes);
}
