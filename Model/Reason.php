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

use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Deletion or anonymization reason model.
 *
 * @api
 * @since 2.0.0
 */
class Reason extends AbstractModel implements ReasonInterface
{
    /**
     * Initialize resource model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Reason::class);
    }

    /**
     * Get reason.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->getData(self::REASON);
    }

    /**
     * Get created at time.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set reason.
     *
     * @param string $reason
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     */
    public function setReason(string $reason)
    {
        $this->setData(self::REASON, $reason);

        return $this;
    }

    /**
     * Set created at time.
     *
     * @param string $time
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     */
    public function setCreatedAt(string $time)
    {
        $this->setData(self::CREATED_AT, $time);

        return $this;
    }
}
