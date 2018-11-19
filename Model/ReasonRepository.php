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

namespace Flurrybox\EnhancedPrivacy\Model;

use Exception;
use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface;
use Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\Data\ReasonSearchResultsInterfaceFactory;
use Flurrybox\EnhancedPrivacy\Api\ReasonRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Class ReasonRepository.
 *
 * @api
 * @since 2.0.0
 */
class ReasonRepository implements ReasonRepositoryInterface
{
    /**
     * @var ReasonInterfaceFactory
     */
    protected $reasonFactory;

    /**
     * @var ResourceModel\Reason
     */
    protected $reasonResource;

    /**
     * @var ResourceModel\Reason\CollectionFactory
     */
    protected $reasonCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ReasonSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * ReasonRepository constructor.
     *
     * @param ReasonInterfaceFactory $reasonFactory
     * @param ResourceModel\Reason $reasonResource
     * @param ResourceModel\Reason\CollectionFactory $reasonCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ReasonSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        ReasonInterfaceFactory $reasonFactory,
        ResourceModel\Reason $reasonResource,
        ResourceModel\Reason\CollectionFactory $reasonCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ReasonSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->reasonFactory = $reasonFactory;
        $this->reasonResource = $reasonResource;
        $this->reasonCollectionFactory = $reasonCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * Get reason.
     *
     * @param int $id
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id)
    {
        $reason = $this->reasonFactory->create();
        $this->reasonResource->load($reason, $id);

        if (!$reason->getReasonId()) {
            throw new NoSuchEntityException(__('Requested reason doesn\'t exist!'));
        }

        return $reason;
    }

    /**
     * Get reasons that match specific criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->reasonCollectionFactory->create();
        $collection->addFieldToSelect('*');

        $this->collectionProcessor->process($searchCriteria, $collection);

        $collection->load();

        $searchResult = $this->searchResultsFactory->create();
        $searchResult
            ->setSearchCriteria($searchCriteria)
            ->setItems($collection->getItems())
            ->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * Save reason object.
     *
     * @param ReasonInterface $reason
     *
     * @return \Flurrybox\EnhancedPrivacy\Api\Data\ReasonInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(ReasonInterface $reason)
    {
        try {
            $this->reasonResource->save($reason);
        } catch (AlreadyExistsException $e) {
            throw new StateException(__('Schedule could not be saved!'));
        } catch (Exception $e) {
            throw new StateException(__('Schedule could not be saved!'));
        }

        return $reason;
    }

    /**
     * Delete reason object.
     *
     * @param ReasonInterface $reason
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(ReasonInterface $reason)
    {
        try {
            $this->reasonResource->delete($reason);
        } catch (Exception $e) {
            throw new StateException(__('Reason could not be deleted!'));
        }

        return true;
    }

    /**
     * Delete reason by id.
     *
     * @param int $id
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->get($id));
    }
}
