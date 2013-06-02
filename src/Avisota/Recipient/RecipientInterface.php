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

namespace Avisota\Recipient;

interface RecipientInterface
{
	/**
	 * Get the recipient email address.
	 *
	 * @return string
	 */
	public function getEmail();

	/**
	 * Check if this recipient has personal data.
	 *
	 * @return bool
	 */
	public function hasDetails();

	/**
	 * Get a single personal data field value.
	 * Return null if the field does not exists.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get($name);

	/**
	 * Get all personal data values as associative array.
	 *
	 * The personal data must have a key 'email', that contains the email address.
	 * <pre>
	 * array (
	 *     'email' => '...',
	 *     ...
	 * )
	 * </pre>
	 *
	 * @return array
	 */
	public function getDetails();

	/**
	 * Get all personal data keys.
	 *
	 * The keys must contain 'email'.
	 * <pre>
	 * array (
	 *     'email',
	 *     ...
	 * )
	 * </pre>
	 *
	 * @return array
	 */
	public function getKeys();
}