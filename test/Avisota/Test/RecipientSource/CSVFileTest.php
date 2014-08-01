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

namespace Avisota\Test\RecipientSource;

use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\CSVFile;

class CSVFileTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param array $columnAssignment
	 * @param null  $delimiter
	 * @param null  $enclosure
	 * @param null  $escape
	 *
	 * @return CSVFile
	 */
	protected function getRecipientSource(
		array $columnAssignment = null,
		$delimiter = null,
		$enclosure = null,
		$escape = null
	) {
		if ($columnAssignment == null) {
			$columnAssignment = array(
				'email',
				'forename',
				'surname',
			);
		}

		$args = array(__DIR__ . DIRECTORY_SEPARATOR . 'emails.csv', $columnAssignment);

		if ($delimiter !== null) {
			$args[] = $delimiter;

			if ($enclosure !== null) {
				$args[] = $enclosure;

				if ($escape !== null) {
					$args[] = $escape;
				}
			}
		}

		$class = new \ReflectionClass('Avisota\RecipientSource\CSVFile');

		return $class->newInstanceArgs($args);
	}

	protected function getRecipients()
	{
		$recipients = array();

		$recipients[] = new MutableRecipient('martin@example.com', array('forename' => 'Martin', 'surname' => 'Example'));
		$recipients[] = new MutableRecipient('maria@example.com', array('forename' => 'Maria'));

		return $recipients;
	}

	/**
	 * @covers Avisota\RecipientSource\CSVFile::countRecipients
	 */
	public function testCountRecipients()
	{
		$recipientSource = $this->getRecipientSource();

		$this->assertEquals(2, $recipientSource->countRecipients());
	}

	/**
	 * @covers Avisota\RecipientSource\CSVFile::getRecipients
	 */
	public function testGetRecipients()
	{
		$recipientSource = $this->getRecipientSource();
		$recipients = $this->getRecipients();

		// assert complete list
		$this->assertEquals($recipients, $recipientSource->getRecipients());

		// assert limited list
		$this->assertEquals(array_slice($recipients, 0, 1), $recipientSource->getRecipients(1));

		// assert offset list
		$this->assertEquals(array_slice($recipients, 1), $recipientSource->getRecipients(1000, 1));
	}
}
