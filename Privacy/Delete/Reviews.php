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
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Review\Model\ResourceModel\Review;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory;

/**
 * Process customer reviews.
 */
class Reviews implements DataDeleteInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Review
     */
    protected $reviewResource;

    /**
     * CustomerReviews constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param Review $reviewResource
     */
    public function __construct(CollectionFactory $collectionFactory, Review $reviewResource)
    {
        $this->collectionFactory = $collectionFactory;
        $this->reviewResource = $reviewResource;
    }

    /**
     * Executed upon customer data deletion.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     */
    public function delete(CustomerInterface $customer)
    {
        $this->processReviews((int) $customer->getId());
    }

    /**
     * Executed upon customer data anonymization.
     *
     * @param CustomerInterface $customer
     *
     * @return void
     */
    public function anonymize(CustomerInterface $customer)
    {
        $this->processReviews((int) $customer->getId());
    }

    /**
     * Anonymize review nickname.
     *
     * @param int $customerId
     *
     * @return void
     */
    protected function processReviews(int $customerId)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);

        if (!$collection->getSize()) {
            return;
        }

        foreach ($collection as $review) {
            /** @var \Magento\Review\Model\Review $review */
            $review->setData('nickname', PrivacyHelper::ANONYMOUS_STR);
        }

        $collection->walk([$this->reviewResource, 'save']);
    }
}
