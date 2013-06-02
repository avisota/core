<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\RecipientSource;

/**
 * Class AvisotaRecipientSource
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
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
