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
 * Class AvisotaRecipientSourceCSVFile
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class CSVFile implements RecipientSourceInterface
{
	private $config;

	public function __construct($configData)
	{
		$this->config = $configData;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipientOptions()
	{
		if ($this->config->csvFileSrc) {
			return array('*' => basename($this->config->csvFileSrc));
		}
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients($options)
	{
		throw new \Exception('Not implemented yet');
	}
}
