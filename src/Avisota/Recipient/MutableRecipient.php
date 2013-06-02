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

class MutableRecipient implements RecipientInterface
{
	/**
	 * @var array
	 */
	protected $data = array();

	public function __construct($email, array $details = array())
	{
		$this->setEmail($email);
		$this->setDetails($details);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEmail()
	{
		return $this->data['email'];
	}

	/**
	 * Set the email address.
	 *
	 * @param $email
	 *
	 * @throws RecipientDataException
	 */
	public function setEmail($email)
	{
		if (empty($email)) {
			throw new RecipientDataException('Email is required');
		}

		$this->data['email'] = (string) $email;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasDetails()
	{
		return count($this->data) > 1;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name)
	{
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		return null;
	}

	/**
	 * Set a personal data field.
	 *
	 * @param string $name  The name of the field.
	 * @param mixed  $value The value of the field. A value of <code>null</code> delete the field.
	 */
	public function set($name, $value)
	{
		if ($name == 'email') {
			$this->setEmail($name);
		}
		else if ($value === null) {
			unset($this->data[$name]);
		}
		else {
			$this->data[$name] = $value;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDetails()
	{
		return $this->data;
	}

	/**
	 * Set multiple personal data fields.
	 *
	 * @param array $details
	 */
	public function setDetails(array $details)
	{
		foreach ($details as $key => $value) {
			$this->set($key, $value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getKeys()
	{
		return array_keys($this->data);
	}
}