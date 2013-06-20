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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;

/**
 * Class AvisotaTransportSwiftTransport
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class SwiftSmtpTransport extends SwiftTransport
{
	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var "ssl"|"tls"
	 */
	protected $encryption;

	/**
	 * @var \Swift_Mailer|null
	 */
	protected $swiftMailer;

	function __construct($host = 'localhost', $port = null, $username = null, $password = null, $encryption = null)
	{
		$this->host       = $host;
		$this->port       = $port;
		$this->username   = $username;
		$this->password   = $password;
		$this->encryption = $encryption;
	}

	/**
	 * @param string $host
	 */
	public function setHost($host)
	{
		$this->host = $host;
		$this->resetMailer();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param int $port
	 */
	public function setPort($port)
	{
		$this->port = $port;
		$this->resetMailer();
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		$this->resetMailer();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		$this->resetMailer();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $encryption
	 */
	public function setEncryption($encryption)
	{
		$encryption = strtolower($encryption);
		if ($encryption != 'ssl' && $encryption != 'tls') {
			$encryption = null;
		}
		$this->encryption = $encryption;
		$this->resetMailer();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEncryption()
	{
		return $this->encryption;
	}

	/**
	 * @return \Swift_Mailer
	 */
	protected function createMailer()
	{
		$transport = new \Swift_SmtpTransport($this->host);
		if ($this->port) {
			$transport->setPort($this->port);
		}
		if ($this->username) {
			$transport->setUsername($this->username);
		}
		if ($this->password) {
			$transport->setPassword($this->password);
		}
		if ($this->encryption) {
			$transport->setEncryption($this->encryption);
		}

		return \Swift_Mailer::newInstance($transport);
	}
}
