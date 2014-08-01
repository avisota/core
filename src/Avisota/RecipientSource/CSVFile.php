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
	 * @var \Swift_Mime_Grammar
	 */
	private $grammar;

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
	 * @return \Swift_Mime_Grammar
	 */
	public function getGrammar()
	{
		if (!$this->grammar) {
			$this->grammar = new \Swift_Mime_Grammar();
		}
		return $this->grammar;
	}

	/**
	 * @param \Swift_Mime_Grammar $grammar
	 *
	 * @return CSVFile
	 */
	public function setGrammar(\Swift_Mime_Grammar $grammar)
	{
		$this->grammar = $grammar;
		return $this;
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

		$regexp  = '/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D';

		$index = array_search('email', $this->columnAssignment);

		while ($row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)) {
			if (!empty($row[$index]) && preg_match($regexp, $row[$index])) {
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
		$regexp  = '/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D';
		$index = array_search('email', $this->columnAssignment);

		// skip offset lines

		for (; $offset > 0 && !feof($in); $offset--) {
			$row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape);

			// skip invalid lines without counting them
			if (empty($row[$index]) || !preg_match($regexp, $row[$index])) {
				$offset ++;
			}
		}

		// read lines
		while (
			(!$limit || count($recipients) < $limit) &&
			$row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)
		) {
			$details = array();

			foreach ($this->columnAssignment as $index => $field) {
				if (isset($row[$index])) {
					$details[$field] = $row[$index];
				}
			}

			if (!empty($details['email']) && preg_match($regexp, $details['email'])) {
				$recipients[] = new MutableRecipient($details['email'], $details);
			}
		}

		fclose($in);

		return $recipients;
	}
}
