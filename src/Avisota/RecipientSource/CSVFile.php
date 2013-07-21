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
 * A recipient source that read the recipients from a csv file.
 *
 * @package avisota-core
 */
class CSVFile implements RecipientSourceInterface
{
	private $config;

	/**
	 * @param $configData
	 */
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
