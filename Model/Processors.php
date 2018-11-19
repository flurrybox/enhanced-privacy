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

use Flurrybox\EnhancedPrivacy\Api\DataDeleteInterface;
use Flurrybox\EnhancedPrivacy\Api\DataExportInterface;
use Flurrybox\EnhancedPrivacy\Api\ProcessorsInterface;

/**
 * Deletion and anonymization processors.
 *
 * @api
 * @since 2.0.0
 */
class Processors implements ProcessorsInterface
{
    /**
     * @var array
     */
    protected $deleteProcessors;

    /**
     * @var array
     */
    protected $exportProcessors;

    /**
     * Processors constructor.
     *
     * @param array $deleteProcessors
     * @param array $exportProcessors
     */
    public function __construct(array $deleteProcessors = [], array $exportProcessors = [])
    {
        $this->deleteProcessors = $deleteProcessors;
        $this->exportProcessors = $exportProcessors;
    }

    /**
     * Get delete and anonymization processors.
     *
     * @return DataDeleteInterface[]
     */
    public function getDeleteProcessors()
    {
        foreach ($this->deleteProcessors as $deleteProcessor) {
            if (!$deleteProcessor instanceof DataDeleteInterface) {
                unset($this->deleteProcessors[$deleteProcessor]);
            }
        }

        return $this->deleteProcessors;
    }

    /**
     * Get export processors.
     *
     * @return DataExportInterface[]
     */
    public function getExportProcessors()
    {
        foreach ($this->exportProcessors as $exportProcessor) {
            if (!$exportProcessor instanceof DataExportInterface) {
                unset($this->exportProcessors[$exportProcessor]);
            }
        }

        return $this->exportProcessors;
    }
}
