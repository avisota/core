<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\RecipientSource;

use Avisota\Recipient\RecipientInterface;

/**
 * A recipient source provide a list of recipients.
 *
 * @package avisota-core
 */
interface RecipientSourceInterface
{
    /**
     * Count the recipients.
     *
     * @return int
     */
    public function countRecipients();

    /**
     * Get all recipients.
     *
     * @param int $limit  Limit result to given count.
     * @param int $offset Skip certain count of recipients.
     *
     * @return RecipientInterface[]
     */
    public function getRecipients($limit = null, $offset = null);
}
