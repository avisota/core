<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\Test\RecipientSource;

use Avisota\Recipient\MutableRecipient;
use Avisota\RecipientSource\CSVFile;

/**
 * Class CSVFileTest
 *
 * @package Avisota\Test\RecipientSource
 */
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

    /**
     * @return array
     */
    protected function getRecipients()
    {
        $recipients = array();

        $recipients[] = new MutableRecipient('martin@example.com', array('forename' => 'Martin', 'surname' => 'Example'));
        $recipients[] = new MutableRecipient('maria@example.com', array('forename' => 'Maria'));
        $recipients[] = new MutableRecipient('matilda@example.com');
        $recipients[] = new MutableRecipient('mario@example.com', array('forename' => 'M.', 'surname' => 'Example'));

        return $recipients;
    }

    /**
     * @covers Avisota\RecipientSource\CSVFile::countRecipients
     */
    public function testCountRecipients()
    {
        $recipientSource = $this->getRecipientSource();
        $recipients      = $this->getRecipients();

        $this->assertEquals(count($recipients), $recipientSource->countRecipients());
    }

    /**
     * @covers Avisota\RecipientSource\CSVFile::getRecipients
     */
    public function testGetRecipients()
    {
        $recipientSource = $this->getRecipientSource();
        $recipients      = $this->getRecipients();

        // assert complete list
        $this->assertEquals($recipients, $recipientSource->getRecipients());

        // assert limited list
        $this->assertEquals(array_slice($recipients, 0, 1), $recipientSource->getRecipients(1));

        // assert offset list
        $this->assertEquals(array_slice($recipients, 1), $recipientSource->getRecipients(1000, 1));

        // assert go through count
        $count = $recipientSource->countRecipients();

        for ($offset = 0; $offset < $count; $offset++) {
            $this->assertEquals(
                array_slice($recipients, $offset, 1),
                $recipientSource->getRecipients(1, $offset),
                'Failed to get recipient at position ' . $offset
            );
        }
    }
}
