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
 * @license   Open Software License ('OSL') v. 3.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Flurrybox\EnhancedPrivacy\Setup;

use Flurrybox\EnhancedPrivacy\Api\Data\CustomerManagementInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as AttributeResource;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Module install data schema.
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeResource
     */
    protected $attributeResource;

    /**
     * InstallData constructor.
     *
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeResource $attributeResource
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory, AttributeResource $attributeResource)
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeResource = $attributeResource;
    }

    /**
     * Installs DB schema for a module.
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $customerSetup = $this->customerSetupFactory->create();
        $customerSetup->removeAttribute(Customer::ENTITY, CustomerManagementInterface::ATTRIBUTE_IS_ANONYMIZED);

        $customerSetup->addAttribute(Customer::ENTITY, CustomerManagementInterface::ATTRIBUTE_IS_ANONYMIZED, [
            'type' => 'int',
            'label' => 'Is Anonymized',
            'input' => 'select',
            'source' => Boolean::class,
            'system' => false,
            'user_defined' => false,
            'visible' => false,
            'default' => 0,
            'required' => false
        ]);

        $installer->endSetup();
    }
}
