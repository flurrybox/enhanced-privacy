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
use Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface;
use Flurrybox\EnhancedPrivacy\Helper\Data as PrivacyHelper;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Archive\Zip;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Export customer data controller.
 */
class Export extends AbstractAccount
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
     * @var ProcessorsInterface
     */
    protected $processors;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Csv
     */
    protected $csvWriter;

    /**
     * @var Zip
     */
    protected $zip;

    /**
     * @var File
     */
    protected $file;

    /**
     * Export constructor.
     *
     * @param Context $context
     * @param PrivacyHelper $privacyHelper
     * @param CustomerSession $customerSession
     * @param ProcessorsInterface $processors
     * @param FileFactory $fileFactory
     * @param DateTime $dateTime
     * @param Csv $csvWriter
     * @param Zip $zip
     * @param File $file
     */
    public function __construct(
        Context $context,
        PrivacyHelper $privacyHelper,
        CustomerSession $customerSession,
        ProcessorsInterface $processors,
        FileFactory $fileFactory,
        DateTime $dateTime,
        Csv $csvWriter,
        Zip $zip,
        File $file
    ) {
        parent::__construct($context);

        $this->privacyHelper = $privacyHelper;
        $this->customerSession = $customerSession;
        $this->processors = $processors;
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->csvWriter = $csvWriter;
        $this->zip = $zip;
        $this->file = $file;
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
        if (
            !$this->privacyHelper->isModuleEnabled() &&
            !$this->privacyHelper->isAccountExportEnabled()
        ) {
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
        try {
            $customer = $this->customerSession->getCustomerData();
            $zipFileName = $this->getArchiveName($customer);

            foreach ($this->processors->getExportProcessors() as $name => $processor) {
                $file = $this->getFileName($customer, $name);

                $this->createCsv($file, $processor->export($customer));
                $this->zip->pack($file, $zipFileName);
                $this->deleteCsv($file);
            }

            return $this->fileFactory->create(
                $zipFileName,
                [
                    'type' => 'filename',
                    'value' => $zipFileName,
                    'rm' => true
                ],
                DirectoryList::PUB,
                'zip',
                null
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, please try again later.'));
        }

        return $this->resultRedirectFactory->create()->setPath('privacy/settings');
    }

    /**
     * Get ZIP archive file name.
     *
     * @param CustomerInterface $customer
     *
     * @return string
     */
    protected function getArchiveName(CustomerInterface $customer)
    {
        return sprintf(
            'customer_data_%d_%s.zip',
            $customer->getId(),
            date('Y-m-d_H-i-s', $this->dateTime->gmtTimestamp())
        );
    }

    /**
     * Get CSV file name.
     *
     * @param CustomerInterface $customer
     * @param string $name
     *
     * @return string
     */
    protected function getFileName(CustomerInterface $customer, string $name)
    {
        return sprintf('%s_%d_%s.csv', $name, $customer->getId(), date('Y-m-d_H-i-s', $this->dateTime->gmtTimestamp()));
    }

    /**
     * Create .csv file.
     *
     * @param string $fileName
     * @param array|string|null $data
     *
     * @return void
     */
    protected function createCsv(string $fileName, $data)
    {
        if (!$data) {
            return null;
        }

        $this->csvWriter
            ->setEnclosure('"')
            ->setDelimiter(',')
            ->saveData($fileName, $data);
    }

    /**
     * Delete .csv file.
     *
     * @param string $fileName
     *
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function deleteCsv(string $fileName)
    {
        if ($this->file->isExists($fileName)) {
            $this->file->deleteFile($fileName);
        }
    }
}
