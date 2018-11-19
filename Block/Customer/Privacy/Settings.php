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

namespace Flurrybox\EnhancedPrivacy\Block\Customer\Privacy;

use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Flurrybox\EnhancedPrivacy\Model\Source\Config\Schema;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

/**
 * Privacy settings block.
 */
class Settings extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Flurrybox_EnhancedPrivacy::customer/privacy/settings.phtml';

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Settings constructor.
     *
     * @param Template\Context $context
     * @param CustomerManagementInterface $customerManagement
     * @param PrivacyHelper $privacyHelper
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CustomerManagementInterface $customerManagement,
        PrivacyHelper $privacyHelper,
        Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerManagement = $customerManagement;
        $this->privacyHelper = $privacyHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * Get privacy helper.
     *
     * @return PrivacyHelper
     */
    public function getPrivacyHelper()
    {
        return $this->privacyHelper;
    }

    /**
     * Get delete page url.
     *
     * @return string
     */
    public function getDeletePageUrl()
    {
        return $this->getUrl('privacy/settings/delete');
    }

    /**
     * Get undo delete page url.
     *
     * @return string
     */
    public function getUndoDeletePageUrl()
    {
        return $this->getUrl('privacy/settings/undodelete');
    }

    /**
     * Get export page url.
     *
     * @return string
     */
    public function getExportPageUrl()
    {
        return $this->getUrl('privacy/settings/export');
    }

    /**
     * Check if account should be anonymized instead of deleted.
     *
     * @return bool
     */
    public function shouldAnonymize()
    {
        return $this->privacyHelper->isAnonymizationMessageEnabled() &&
            ($this->privacyHelper->getDeletionSchema() === Schema::ANONYMIZE || $this->hasOrders());
    }

    /**
     * Check if customer has orders.
     *
     * @return bool
     */
    public function hasOrders()
    {
        if (!$customer = $this->customerSession->getCustomerData()) {
            return false;
        }

        return $this->customerManagement->hasOrders($customer);
    }

    /**
     * Check if account is to be deleted.
     *
     * @return bool
     */
    public function isAccountToBeDeleted()
    {
        if (!$customer = $this->customerSession->getCustomerData()) {
            return false;
        }

        return $this->customerManagement->isCustomerToBeDeleted($customer);
    }
}
