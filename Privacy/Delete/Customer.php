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

namespace Flurrybox\EnhancedPrivacy\Privacy\Delete;

use Flurrybox\EnhancedPrivacy\Api\Data\CustomerManagementInterface;
use Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Math\Random;
use Magento\Framework\Registry;

/**
 * Process customer data.
 */
class Customer implements DataDeleteInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * CustomerData constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param EncryptorInterface $encryptor
     * @param Random $random
     * @param Registry $registry
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        EncryptorInterface $encryptor,
        Random $random,
        Registry $registry
    ) {
        $this->customerRepository = $customerRepository;
        $this->encryptor = $encryptor;
        $this->random = $random;
        $this->registry = $registry;
    }

    /**
     * Executed upon customer data deletion.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(CustomerInterface $customer)
    {
        /** @see \Magento\Framework\Model\ActionValidator\RemoveAction::isAllowed() **/
        $this->registry->register('isSecureArea', true);

        $this->customerRepository->deleteById($customer->getId());
    }

    /**
     * Executed upon customer data anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function anonymize(CustomerInterface $customer)
    {
        $customer = $this->customerRepository->getById($customer->getId());

        $customer
            ->setPrefix(PrivacyHelper::ANONYMOUS_STR)
            ->setFirstname(PrivacyHelper::ANONYMOUS_STR)
            ->setMiddlename(PrivacyHelper::ANONYMOUS_STR)
            ->setLastname(PrivacyHelper::ANONYMOUS_STR)
            ->setSuffix(PrivacyHelper::ANONYMOUS_STR)
            ->setCreatedAt(PrivacyHelper::ANONYMOUS_DATE)
            ->setUpdatedAt(PrivacyHelper::ANONYMOUS_DATE)
            ->setEmail($this->getAnonymousEmail((int) $customer->getId()))
            ->setDob(PrivacyHelper::ANONYMOUS_DATE)
            ->setTaxvat(PrivacyHelper::ANONYMOUS_STR)
            ->setGender(0)
            ->setCustomAttribute(CustomerManagementInterface::ATTRIBUTE_IS_ANONYMIZED, Boolean::VALUE_YES)
            ->setGroupId(GroupInterface::NOT_LOGGED_IN_ID);

        $this->customerRepository
            ->save($customer, $this->encryptor->getHash($this->generateString(64), true));
    }

    /**
     * Generate anonymized email.
     *
     * @param int $customerId
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getAnonymousEmail(int $customerId)
    {
        return $customerId . $this->generateString() . '@' . PrivacyHelper::ANONYMOUS_STR . '.com';
    }

    /**
     * Retrieve random string.
     *
     * @param int $length
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function generateString(int $length = 10)
    {
        return $this->random->getRandomString(
            $length,
            Random::CHARS_LOWERS . Random::CHARS_UPPERS . Random::CHARS_DIGITS
        );
    }
}
