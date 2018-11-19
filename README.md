# Enhanced Privacy extension for Magento 2

Extension provides easier compliance with GDPR. Allows customers to delete, anonymize, or export their personal data.
View detailed information on [store page](https://flurrybox.com/enhanced-privacy.html).

## Getting Started

### Prerequisites

Magento 2 Open Source (CE) or Commerce edition (EE).
Supported versions: Magento 2.1.6+, 2.2.x

### Installation

#### Composer (recommended)

Commands should be run at the root of your Magento 2 installation.
```
composer require flurrybox/enhanced-privacy
php bin/magento module:enable Flurrybox_EnhancedPrivacy
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

#### Copy package files

- Download repository files as ZIP archive
- Extract files to the `app/code/Flurrybox/EnhancedPrivacy` directory
- Run the following commands in Magento 2 root folder:
    ```
    php bin/magento module:enable Flurrybox_EnhancedPrivacy
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento setup:static-content:deploy
    ```

### Usage and Features

* Configuration for this module is located in 'Stores > Configuration > Customers > Customer Configuration > Privacy (GDPR)'.
* Account deletion, anonymization, and export can be done in 'My Account > Privacy Settings'.
* Customers can export their data in .zip archive containing .csv files with personal, wishlist, quote, and address data.
* Customer can delete or anonymize their account. Current password and reason is required. Account will be deleted within 1 hour (or as specified in configuration), in this time span its possible for customers to undo deletion.
* If customer has made at least one order, they are ineligible to delete their account, instead it will be anonymized.
* When a customer visits your store for the first time, a popup notification about cookie policy will be shown.

### Export data
Besides default export entites its possible to implement your own custom data export.
When customers will make a request for their personal data export, your class instance will be executed by data export processor and will add new file to data archive.

1. Create a new class implementing `Flurrybox\EnhancedPrivacy\Api\DataExportInterface` interface.
    ```php
    <?php

    declare(strict_types=1);

    namespace Vendor\Module\Privacy\Export;
    
    use Flurrybox\EnhancedPrivacy\Api\DataExportInterface;
    use Magento\Customer\Api\Data\CustomerInterface;
    
    class Entity implements DataExportInterface
    {
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
         * @param \Magento\Customer\Api\Data\CustomerInterface $customer
         *
         * @return array
         */
        public function export(CustomerInterface $customer)
        {
            ...
        }
    }
    ```
2. Register export class in `etc/di.xml`
    ```xml
    <?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
        ...
        <type name="Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface">
            <arguments>
                ...
                <argument name="exportProcessors" xsi:type="array">
                    ...
                    <item name="entity" xsi:type="object">Vendor\Module\Privacy\Export\Entity</item>
                    ...
                </argument>
                ...
            </arguments>
        </type>
        ...
    </config>
    ```

### Delete and anonymize data
To delete or anonymize data that's gathered by 3rd party integrations you can implement your own data processor.

1. Create a new class implementing `Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface` interface.
    ```php
    <?php
    
    declare(strict_types=1);
    
    namespace Vendor\Module\Privacy\Delete;
    
    use Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface;
    use Magento\Customer\Api\Data\CustomerInterface;
    
    class Entity implements DataDeleteInterface
    {
        /**
         * Executed upon customer data deletion.
         *
         * @param \Magento\Customer\Api\Data\CustomerInterface $customer
         *
         * @return void
         */
        public function delete(CustomerInterface $customer)
        {
            ...
        }
        
        /**
         * Executed upon customer data anonymization.
         *
         * @param \Magento\Customer\Api\Data\CustomerInterface $customer
         *
         * @return void
         */
        public function anonymize(CustomerInterface $customer)
        {
            ...
        }
    }
    ```
2. Register processor class in `etc/di.xml`
    ```xml
    <?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
        ...
        <type name="Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface">
            <arguments>
                <argument name="deleteProcessors" xsi:type="array">
                    ...
                    <item name="entity" xsi:type="object">Vendor\Module\Privacy\Delete\Entity</item>
                    ...
                </argument>
            </arguments>
        </type>
        ...
    </config>
    ```

## Copyrights and License

Copyright (c) 2018 Flurrybox, Ltd. under GNU General Public License ("GPL") v3.0