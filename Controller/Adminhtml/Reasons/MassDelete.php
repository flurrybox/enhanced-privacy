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

namespace Flurrybox\EnhancedPrivacy\Controller\Adminhtml\Reasons;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\ReasonRepositoryInterface;
use Flurrybox\EnhancedPrivacy\Model\ResourceModel\Reason\CollectionFactory as ReasonCollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Reason mass delete controller.
 */
class MassDelete extends Action
{
    /**
     * ACL resource name.
     */
    const ADMIN_RESOURCE = 'Flurrybox_EnhancedPrivacy::enhancedprivacy_delete_reasons';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var ReasonCollectionFactory
     */
    protected $reasonCollectionFactory;

    /**
     * @var ReasonRepositoryInterface
     */
    protected $reasonRepository;

    /**
     * MassDelete constructor.
     *
     * @param Action\Context $context
     * @param Filter $filter
     * @param ReasonCollectionFactory $reasonCollectionFactory
     * @param ReasonRepositoryInterface $reasonRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        ReasonCollectionFactory $reasonCollectionFactory,
        ReasonRepositoryInterface $reasonRepository
    ) {
        parent::__construct($context);

        $this->filter = $filter;
        $this->reasonCollectionFactory = $reasonCollectionFactory;
        $this->reasonRepository = $reasonRepository;
    }

    /**
     * Execute action.
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->reasonCollectionFactory->create());

            foreach ($collection->getItems() as $item) {
                $this->reasonRepository->delete($item);
            }

            $this->messageManager->addSuccessMessage(__('Deletion reasons successfully deleted!'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
