<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\RecipientSource;

/**
 * A recipient source provide a list of recipients.
 *
 * @package avisota-core
 */
interface RecipientSourceInterface
{
	/**
	 * Get all selectable recipient options for this source.
	 * Every option can be an individual ID.
	 *
	 * @return array
	 */
	public function getRecipientOptions();

	/**
	 * Get complete recipients data for a list of options.
	 *
	 * @param array $options
	 *
	 * @return RecipientInterface[]
	 */
	public function getRecipients($options);
}
