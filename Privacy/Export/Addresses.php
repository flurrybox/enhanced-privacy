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

namespace Flurrybox\EnhancedPrivacy\Privacy\Export;

use Flurrybox\EnhancedPrivacy\Api\DataExportInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Directory\Model\CountryFactory;

/**
 * Export customer addresses.
 */
class Addresses implements DataExportInterface
{
    /**
     * @var CountryFactory
     */
    protected $countryFactory;

    /**
     * CustomerAddresses constructor.
     *
     * @param CountryFactory $countryFactory
     */
    public function __construct(CountryFactory $countryFactory)
    {
        $this->countryFactory = $countryFactory;
    }

    /**
     * Executed upon exporting customer data.
     *
     * Expected return structure:
     *      array(
     *          array('HEADER1', 'HEADER2', 'HEADER3', ...),
     *          array('VALUE1', 'VALUE2', 'VALUE3', ...),
     *          ...
     *      )
     *
     * @param CustomerInterface $customer
     *
     * @return array
     */
    public function export(CustomerInterface $customer)
    {
        $addresses = $customer->getAddresses();

        if (!$addresses) {
            return null;
        }

        $data[] = [
            'CITY',
            'COMPANY',
            'COUNTRY',
            'FAX',
            'PREFIX',
            'FIRST NAME',
            'LAST NAME',
            'MIDDLE NAME',
            'SUFFIX',
            'POST CODE',
            'REGION',
            'STREET',
            'TEL'
        ];

        foreach ($addresses as $address) {
            $data[] = [
                $address->getCity(),
                $address->getCompany(),
                $this->countryFactory->create()->loadByCode($address->getCountryId())->getName(),
                $address->getFax(),
                $address->getPrefix(),
                $address->getFirstname(),
                $address->getLastname(),
                $address->getMiddlename(),
                $address->getSuffix(),
                $address->getPostcode(),
                $address->getRegion()->getRegion(),
                $address->getStreet()[0] . PHP_EOL . ($address->getStreet()[1] ?? ''),
                $address->getTelephone()
            ];
        }

        return $data;
    }
}
