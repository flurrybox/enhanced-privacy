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

use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Framework\View\Element\Template;

class Delete extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Flurrybox_EnhancedPrivacy::customer/privacy/delete.phtml';

    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * Delete constructor.
     *
     * @param Template\Context $context
     * @param PrivacyHelper $privacyHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        PrivacyHelper $privacyHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->privacyHelper = $privacyHelper;
    }

    /**
     * Get action controller url.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl('privacy/settings/deletePost');
    }

    /**
     * Get privacy settings url.
     *
     * @return string
     */
    public function getSettingsUrl()
    {
        return $this->getUrl('privacy/settings');
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
}
