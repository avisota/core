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

namespace Avisota;

class Recipient
{
	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var array
	 */
	protected $details;

	function __construct($email, array $details = array())
	{
		$this->email   = (string) $email;
		$this->details = $details;
	}

	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = (string) $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param array $details
	 */
	public function setDetails(array $details)
	{
		$this->details = $details;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getDetails()
	{
		return $this->details;
	}
}
