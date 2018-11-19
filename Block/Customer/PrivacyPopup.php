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

namespace Flurrybox\EnhancedPrivacy\Block\Customer;

use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Template;

/**
 * Privacy policy popup block.
 */
class PrivacyPopup extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Flurrybox_EnhancedPrivacy::customer/privacy_popup.phtml';

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * PrivacyMessagePopup constructor.
     *
     * @param Template\Context $context
     * @param CookieManagerInterface $cookieManager
     * @param PrivacyHelper $privacyHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CookieManagerInterface $cookieManager,
        PrivacyHelper $privacyHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->cookieManager = $cookieManager;
        $this->privacyHelper = $privacyHelper;
    }

    /**
     * Check if popup should be rendered before loading block.
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (
            !$this->privacyHelper->isModuleEnabled() &&
            !$this->privacyHelper->isPopupNotificationEnabled()
        ) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Get JS layout configuration.
     *
     * @return string
     */
    public function getJsLayout()
    {
        $this->jsLayout['components']['enhancedprivacy-cookie-policy']['config'] = [
            'cookieName' => PrivacyHelper::COOKIE_COOKIES_POLICY,
            'learnMore' => $this->getUrl($this->privacyHelper->getInformationPage()),
            'notificationText' => $this->privacyHelper->getPopupNotificationText()
        ];

        return json_encode($this->jsLayout);
    }
}
