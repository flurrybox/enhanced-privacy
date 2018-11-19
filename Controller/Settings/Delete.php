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

namespace Flurrybox\EnhancedPrivacy\Controller\Settings;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Delete verification controller.
 */
class Delete extends AbstractAccount
{
    /**
     * @var PrivacyHelper
     */
    protected $privacyHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var CustomerManagementInterface
     */
    protected $customerManagement;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param PrivacyHelper $privacyHelper
     * @param CustomerSession $customerSession
     * @param CustomerManagementInterface $customerManagement
     */
    public function __construct(
        Context $context,
        PrivacyHelper $privacyHelper,
        CustomerSession $customerSession,
        CustomerManagementInterface $customerManagement
    ) {
        parent::__construct($context);

        $this->privacyHelper = $privacyHelper;
        $this->customerSession = $customerSession;
        $this->customerManagement = $customerManagement;
    }

    /**
     * Dispatch controller.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        try {
            if (
                !$this->privacyHelper->isModuleEnabled() ||
                !$this->privacyHelper->isAccountDeletionEnabled() ||
                $this->customerManagement->isCustomerToBeDeleted($this->customerSession->getCustomerData())
            ) {
                $this->_forward('noroute');
            }
        } catch (Exception $e) {
            $this->_forward('noroute');
        }

        return parent::dispatch($request);
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
