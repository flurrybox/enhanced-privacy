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

use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Settings controller.
 */
class Index extends AbstractAccount
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
     * Index constructor.
     *
     * @param Context $context
     * @param PrivacyHelper $privacyHelper
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        PrivacyHelper $privacyHelper,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);

        $this->privacyHelper = $privacyHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * Dispatch controller.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$this->privacyHelper->isModuleEnabled()){
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
