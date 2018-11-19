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

use Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Process customer address data.
 */
class Addresses implements DataDeleteInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * CustomerAddresses constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param AddressRepositoryInterface $addressRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        AddressRepositoryInterface $addressRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Executed upon customer data deletion.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(CustomerInterface $customer)
    {
        if (!$addresses = $customer->getAddresses()) {
            return;
        }

        foreach ($addresses as $address) {
            $this->addressRepository->delete($address);
        }
    }

    /**
     * Executed upon customer data anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function anonymize(CustomerInterface $customer)
    {
        if (!$addresses = $customer->getAddresses()) {
            return;
        }

        foreach ($addresses as $address) {
            $address->setCity(PrivacyHelper::ANONYMOUS_STR);
            $address->setCompany(PrivacyHelper::ANONYMOUS_STR);
            $address->setCountryId('US');
            $address->setFax(PrivacyHelper::ANONYMOUS_STR);
            $address->setPrefix('');
            $address->setFirstname(PrivacyHelper::ANONYMOUS_STR);
            $address->setLastname(PrivacyHelper::ANONYMOUS_STR);
            $address->setMiddlename('');
            $address->setSuffix('');
            $address->setPostcode(PrivacyHelper::ANONYMOUS_STR);
            $address->setRegionId(1);
            $address->setStreet([PrivacyHelper::ANONYMOUS_STR, PrivacyHelper::ANONYMOUS_STR]);
            $address->setTelephone(PrivacyHelper::ANONYMOUS_STR);
        }

        $this->customerRepository->save($customer);
    }
}
