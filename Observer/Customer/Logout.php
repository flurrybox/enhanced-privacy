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

namespace Flurrybox\EnhancedPrivacy\Observer\Customer;

use Flurrybox\EnhancedPrivacy\Api\Data\CustomerManagementInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Customer logout observer.
 */
class Logout implements ObserverInterface
{
    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * Logout constructor.
     *
     * @param HttpContext $httpContext
     * @param CustomerSession $customerSession
     * @param RedirectInterface $redirect
     */
    public function __construct(
        HttpContext $httpContext,
        CustomerSession $customerSession,
        RedirectInterface $redirect
    ) {
        $this->httpContext = $httpContext;
        $this->customerSession = $customerSession;
        $this->redirect = $redirect;
    }

    /**
     * Execute observer.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $loggedIn = $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);

        if (!$loggedIn) {
            return;
        }

        $customer = $this->customerSession->getCustomerData();

        if (!$isAnonymized = $customer->getCustomAttribute(CustomerManagementInterface::ATTRIBUTE_IS_ANONYMIZED)) {
            return;
        }

        if ($isAnonymized->getValue() === Boolean::VALUE_YES) {
            $this->customerSession
                ->logout()
                ->setBeforeAuthUrl($this->redirect->getRefererUrl());
        }
    }
}
