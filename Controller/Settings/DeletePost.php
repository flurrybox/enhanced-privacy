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
use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\ReasonRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Api\ScheduleRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Customer delete controller.
 */
class DeletePost extends AbstractAccount
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
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var AuthenticationInterface
     */
    protected $authentication;

    /**
     * @var ScheduleInterfaceFactory
     */
    protected $scheduleFactory;

    /**
     * @var ScheduleRepositoryInterface
     */
    protected $scheduleRepository;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var ReasonInterfaceFactory
     */
    protected $reasonFactory;

    /**
     * @var ReasonRepositoryInterface
     */
    protected $reasonRepository;

    /**
     * DeletePost constructor.
     *
     * @param Context $context
     * @param PrivacyHelper $privacyHelper
     * @param CustomerSession $customerSession
     * @param CustomerManagementInterface $customerManagement
     * @param Validator $formKeyValidator
     * @param AuthenticationInterface $authentication
     * @param ScheduleInterfaceFactory $scheduleFactory
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param ReasonInterfaceFactory $reasonFactory
     * @param ReasonRepositoryInterface $reasonRepository
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        PrivacyHelper $privacyHelper,
        CustomerSession $customerSession,
        CustomerManagementInterface $customerManagement,
        Validator $formKeyValidator,
        AuthenticationInterface $authentication,
        ScheduleInterfaceFactory $scheduleFactory,
        ScheduleRepositoryInterface $scheduleRepository,
        ReasonInterfaceFactory $reasonFactory,
        ReasonRepositoryInterface $reasonRepository,
        DateTime $dateTime
    ) {
        parent::__construct($context);

        $this->privacyHelper = $privacyHelper;
        $this->customerSession = $customerSession;
        $this->customerManagement = $customerManagement;
        $this->formKeyValidator = $formKeyValidator;
        $this->authentication = $authentication;
        $this->scheduleFactory = $scheduleFactory;
        $this->scheduleRepository = $scheduleRepository;
        $this->reasonFactory = $reasonFactory;
        $this->reasonRepository = $reasonRepository;
        $this->dateTime = $dateTime;
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
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute()
    {
        if (
            !$this->getRequest()->isPost() &&
            $this->formKeyValidator->validate($this->getRequest())
        ) {
            return $this->resultRedirectFactory->create()->setPath('privacy/settings');
        }

        try {
            $customer = $this->customerSession->getCustomerData();

            $this->authentication->authenticate($customer->getId(), $this->getRequest()->getParam('password'));

            $reason = $this->reasonFactory->create();
            $reason->setReason($this->getRequest()->getParam('reason'));
            $this->reasonRepository->save($reason);

            $schedule = $this->scheduleFactory->create();
            $schedule
                ->setScheduledAt(
                    date('Y-m-d H:i:s', $this->dateTime->gmtTimestamp() + $this->privacyHelper->getDeletionTime())
                )
                ->setCustomerId((int) $customer->getId())
                ->setReasonId((int) $reason->getId())
                ->setType($this->privacyHelper->getDeletionType($customer));

            $this->_eventManager->dispatch('enhancedprivacy_submit_delete_request', ['schedule' => $schedule]);

            $this->scheduleRepository->save($schedule);

            $this->messageManager->addWarningMessage($this->privacyHelper->getSuccessMessage());
        } catch (InvalidEmailOrPasswordException $e) {
            $this->messageManager->addErrorMessage(__('Password you typed does not match this account!'));
        } catch (UserLockedException $e) {
            $this->customerSession->logout();
            $this->customerSession->start();

            $this->messageManager
                ->addErrorMessage(__('You did not sign in correctly or your account is temporarily disabled.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, please try again later!'));
        }

        return $this->resultRedirectFactory->create()->setPath('privacy/settings');
    }
}
