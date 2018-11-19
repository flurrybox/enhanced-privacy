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

namespace Flurrybox\EnhancedPrivacy\Helper;

use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Model\Source\Config\Schema;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Module configuration and utility helper.
 */
class Data extends AbstractHelper
{
    /**
     * Configuration paths.
     */
    const CONFIG_ENABLE = 'customer/enhancedprivacy/general/enable';
    const CONFIG_INFORMATION_PAGE = 'customer/enhancedprivacy/general/information_page';
    const CONFIG_INFORMATION = 'customer/enhancedprivacy/general/information';
    const CONFIG_ACCOUNT_DELETION_ENABLED = 'customer/enhancedprivacy/account/account_deletion_enabled';
    const CONFIG_ACCOUNT_DELETION_SCHEMA = 'customer/enhancedprivacy/account/deletion_schema';
    const CONFIG_ACCOUNT_DELETION_TIME = 'customer/enhancedprivacy/account/deletion_time';
    const CONFIG_ACCOUNT_DELETION_TITLE = 'customer/enhancedprivacy/account/account_deletion_title';
    const CONFIG_ACCOUNT_DELETION_BUTTON_TEXT = 'customer/enhancedprivacy/account/account_deletion_button_text';
    const CONFIG_SUCCESS_MESSAGE = 'customer/enhancedprivacy/account/success_message';
    const CONFIG_ACCOUNT_DELETION_INFO = 'customer/enhancedprivacy/account/account_deletion_info';
    const CONFIG_ACCOUNT_DELETION_REASON_INFO = 'customer/enhancedprivacy/account/account_delete_reason_info';
    const CONFIG_ACCOUNT_ANONYMIZATION = 'customer/enhancedprivacy/account/account_anonymization_message_enabled';
    const CONFIG_ACCOUNT_ANONYMIZATION_MESSAGE = 'customer/enhancedprivacy/account/account_anonymization_message';
    const CONFIG_ACCOUNT_EXPORT_ENABLED = 'customer/enhancedprivacy/export/account_export_enabled';
    const CONFIG_ACCOUNT_EXPORT_INFORMATION = 'customer/enhancedprivacy/export/export_information';
    const CONFIG_ACCOUNT_EXPORT_TITLE = 'customer/enhancedprivacy/export/export_title';
    const CONFIG_ACCOUNT_EXPORT_DATA_BUTTON_TEXT = 'customer/enhancedprivacy/export/export_button_text';
    const CONFIG_ACCOUNT_POPUP_NOTIFICATION_ENABLED = 'customer/enhancedprivacy/cookie/popup_notification_enabled';
    const CONFIG_ACCOUNT_POPUP_TEXT = 'customer/enhancedprivacy/cookie/popup_text';

    /**
     * Schedule types.
     */
    const SCHEDULE_TYPE_DELETE = 'delete';
    const SCHEDULE_TYPE_ANONYMIZE = 'anonymize';

    /**
     * Cookie name.
     */
    const COOKIE_COOKIES_POLICY = 'cookies-policy';

    /**
     * Helper constants.
     */
    const ANONYMOUS_STR = 'Anonymous';
    const ANONYMOUS_DATE = 1;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param CustomerManagementInterface $customerManagement
     */
    public function __construct(
        Context $context,
        CustomerManagementInterface $customerManagement
    ) {
        parent::__construct($context);

        $this->customerManagement = $customerManagement;
    }

    /**
     * Check if module is enabled.
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::CONFIG_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get privacy information page url.
     *
     * @return string|null
     */
    public function getInformationPage()
    {
        return $this->scopeConfig->getValue(self::CONFIG_INFORMATION_PAGE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get brief information about privacy.
     *
     * @return string|null
     */
    public function getInformation()
    {
        return $this->scopeConfig->getValue(self::CONFIG_INFORMATION, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get success message.
     *
     * @return string|null
     */
    public function getSuccessMessage()
    {
        return $this->scopeConfig->getValue(self::CONFIG_SUCCESS_MESSAGE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if account deletion is enabled.
     *
     * @return bool
     */
    public function isAccountDeletionEnabled()
    {
        return (bool) $this->scopeConfig
            ->getValue(self::CONFIG_ACCOUNT_DELETION_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion schema type.
     *
     * @return int
     */
    public function getDeletionSchema()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_SCHEMA, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion time.
     *
     * @return int
     */
    public function getDeletionTime()
    {
        return (int) $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_TIME, ScopeInterface::SCOPE_STORE) * 60;
    }

    /**
     * Get account deletion button text.
     *
     * @return string|null
     */
    public function getDeletionTitle()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion button text.
     *
     * @return string|null
     */
    public function getDeletionButtonText()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_BUTTON_TEXT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion information.
     *
     * @return string|null
     */
    public function getAccountDeletionInfo()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_INFO, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get information about deletion reason.
     *
     * @return string|null
     */
    public function getDeletionReasonInfo()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_DELETION_REASON_INFO, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if anonymization message is enabled.
     *
     * @return bool
     */
    public function isAnonymizationMessageEnabled()
    {
        return (bool) $this->scopeConfig
            ->getValue(self::CONFIG_ACCOUNT_ANONYMIZATION, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get anonymization message.
     *
     * @return string|null
     */
    public function getAnonymizationMessage()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_ANONYMIZATION_MESSAGE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if account data export is enabled.
     *
     * @return bool
     */
    public function isAccountExportEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_EXPORT_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get information about export.
     *
     * @return string|null
     */
    public function getAccountExportInformation()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_EXPORT_INFORMATION, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion button text.
     *
     * @return string|null
     */
    public function getExportTitle()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_EXPORT_TITLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get account deletion button text.
     *
     * @return string|null
     */
    public function getExportButtonText()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_EXPORT_DATA_BUTTON_TEXT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check if popup notification is enabled.
     *
     * @return bool
     */
    public function isPopupNotificationEnabled()
    {
        return (bool) $this->scopeConfig
            ->getValue(self::CONFIG_ACCOUNT_POPUP_NOTIFICATION_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get popup notification text.
     *
     * @return string|null
     */
    public function getPopupNotificationText()
    {
        return $this->scopeConfig->getValue(self::CONFIG_ACCOUNT_POPUP_TEXT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get deletion type.
     *
     * @param CustomerInterface $customer
     *
     * @return string
     */
    public function getDeletionType(CustomerInterface $customer)
    {
        switch ($this->getDeletionSchema()) {
            case Schema::DELETE:
                return self::SCHEDULE_TYPE_DELETE;

            case Schema::ANONYMIZE:
                return self::SCHEDULE_TYPE_ANONYMIZE;

            case Schema::DELETE_ANONYMIZE:
                return $this->customerManagement->hasOrders($customer) ?
                    self::SCHEDULE_TYPE_DELETE :
                    self::SCHEDULE_TYPE_ANONYMIZE;
        }

        return self::SCHEDULE_TYPE_DELETE;
    }
}
