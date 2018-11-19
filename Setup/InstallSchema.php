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

namespace Flurrybox\EnhancedPrivacy\Setup;

use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ScheduleInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Module install schema.
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module.
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()
            ->newTable($setup->getTable(ReasonInterface::TABLE))
            ->addColumn(
                ReasonInterface::ID,
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Reason Id'
            )->addColumn(
                ReasonInterface::REASON,
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Reason'
            )
            ->addColumn(
                ReasonInterface::CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->setComment('Deletion Reasons');

        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()
            ->newTable($setup->getTable(ScheduleInterface::TABLE))
            ->addColumn(
                ScheduleInterface::ID,
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Schedule Id'
            )
            ->addColumn(
                ScheduleInterface::SCHEDULED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => true],
                'Scheduled At'
            )
            ->addColumn(
                ScheduleInterface::CUSTOMER_ID,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Customer Id'
            )
            ->addColumn(
                ScheduleInterface::TYPE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Type'
            )
            ->addColumn(
                ScheduleInterface::REASON_ID,
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false],
                'Reason Id'
            )
            ->addIndex(
                $setup->getIdxName(
                    ScheduleInterface::TABLE,
                    [ScheduleInterface::CUSTOMER_ID],
                    true
                ),
                [ScheduleInterface::CUSTOMER_ID],
                ['type' => 'unique']
            )
            ->setComment('Account Cleanup Schedule');

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
