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

use Avisota\Recipient\MutableRecipient;

/**
 * A recipient source that read the recipients from a csv file.
 *
 * @package avisota-core
 */
class CSVFile implements RecipientSourceInterface
{
	private $file;

	private $columnAssignment;

	private $delimiter;

	private $enclosure;

	private $escape;

	/**
	 * @param string $fileData
	 */
	public function __construct($file, array $columnAssignment, $delimiter = ',', $enclosure = '"', $escape = '\\')
	{
		$this->file             = (string) $file;
		$this->columnAssignment = $columnAssignment;
		$this->delimiter        = $delimiter;
		$this->enclosure        = $enclosure;
		$this->escape           = $escape;
	}

	/**
	 * Count the recipients.
	 *
	 * @return int
	 */
	public function countRecipients()
	{
		$in = fopen($this->file, 'r');

		if (!$in) {
			return 0;
		}

		$recipients = 0;

		$index = array_search('email', $this->columnAssignment);

		while ($row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)) {
			if (!empty($row[$index])) {
				$recipients ++;
			}
		}

		fclose($in);

		return $recipients;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients($limit = null, $offset = null)
	{
		$in = fopen($this->file, 'r');

		if (!$in) {
			return null;
		}

		$recipients = array();

		// skip offset lines
		for (; $offset > 0; $offset--) {
			fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape);
		}

		// read lines
		while ($row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)) {
			$details = array();

			foreach ($this->columnAssignment as $index => $field) {
				if (isset($row[$index])) {
					$details[$field] = $row[$index];
				}
			}

			if (empty($details['email'])) {
				continue;
			}

			$recipients[] = new MutableRecipient($details['email'], $details);

			// break reading if limit is reached
			if (count($recipients) >= $limit) {
				break;
			}
		}

		fclose($in);

		return $recipients;
	}
}
