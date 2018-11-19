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

use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Cron schedule model.
 *
 * @api
 * @since 2.0.0
 */
class Schedule extends AbstractExtensibleModel implements ScheduleInterface
{
    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Schedule::class);
    }

    /**
     * Get schedule at time.
     *
     * @return string
     */
    public function getScheduledAt()
    {
        return $this->getData(self::SCHEDULED_AT);
    }

    /**
     * Get customer id.
     *
     * @return int
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Get schedule type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Get deletion reason.
     *
     * @return int
     */
    public function getReasonId()
    {
        return $this->getData(self::REASON_ID);
    }

    /**
     * Set scheduled at time.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setScheduledAt(string $time)
    {
        $this->setData(self::SCHEDULED_AT, $time);

        return $this;
    }

    /**
     * Set customer id.
     *
     * @param int $customerId
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setCustomerId(int $customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);

        return $this;
    }

    /**
     * Set schedule type.
     *
     * @param string $type
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE, $type);

        return $this;
    }

    /**
     * Set deletion reason.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setReasonId(int $id)
    {
        $this->setData(self::REASON_ID, $id);

        return $this;
    }

    /**
     * Get extension attributes.
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();

        if ($extensionAttributes === null) {
            /** @var \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface $extensionAttributes */
            $extensionAttributes = $this->extensionAttributesFactory->create(ScheduleInterface::class);
            $this->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }

    /**
     * Set extension attributes.
     *
     * @param \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleExtensionInterface $extensionAttributes
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface
     */
    public function setExtensionAttributes(ScheduleExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
