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


use Avisota\RecipientSource\Union;

/**
 * Class UnionTest
 *
 * @package Avisota\Test\RecipientSource
 */
class UnionTest extends CSVFileTest
{
    /**
     * @param $clean
     *
     * @return Union
     */
    protected function getUnionRecipientSource($clean)
    {
        $unionRecipientSource = new Union();
        $unionRecipientSource->setClean($clean);

        $unionRecipientSource->addRecipientSource($this->getRecipientSource());
        $unionRecipientSource->addRecipientSource($this->getRecipientSource());
        $unionRecipientSource->addRecipientSource($this->getRecipientSource());

        return $unionRecipientSource;
    }

    /**
     * @param $clean
     *
     * @return array
     */
    protected function getUnionRecipients($clean)
    {
        $recipients = $this->getRecipients();

        if (!$clean) {
            $recipients = array_merge($recipients, $this->getRecipients(), $this->getRecipients());
        }

        return $recipients;
    }

    /**
     * @covers Avisota\RecipientSource\CSVFile::countRecipients
     */
    public function testCountRecipients()
    {
        foreach (array(true, false) as $clean) {
            $recipientSource = $this->getUnionRecipientSource($clean);
            $recipients      = $this->getUnionRecipients($clean);

            $this->assertEquals(count($recipients), $recipientSource->countRecipients());
        }
    }

    /**
     * @covers Avisota\RecipientSource\CSVFile::getRecipients
     */
    public function testGetRecipients()
    {
        foreach (array(true, false) as $clean) {
            $recipientSource = $this->getUnionRecipientSource($clean);
            $recipients      = $this->getUnionRecipients($clean);

            // assert complete list
            $this->assertEquals($recipients, $recipientSource->getRecipients());

            // assert limited list
            $this->assertEquals(array_slice($recipients, 0, 1), $recipientSource->getRecipients(1));

            // assert offset list
            $this->assertEquals(array_slice($recipients, 1), $recipientSource->getRecipients(1000, 1));

            // assert go through count
            $count = $recipientSource->countRecipients();

            for ($offset = 0; $offset < $count; $offset += 3) {
                $this->assertEquals(
                    array_slice($recipients, $offset, 3),
                    $recipientSource->getRecipients(3, $offset),
                    'Failed to get recipient at position ' . $offset . ', clean mode is ' . ($clean ? 'on' : 'off')
                );
            }
        }
    }
}
