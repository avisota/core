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

namespace Avisota\RecipientSource;

use Avisota\Recipient\MutableRecipient;
use Avisota\Recipient\RecipientInterface;

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
     * @param        $file
     * @param array  $columnAssignment
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     *
     * @internal param string $fileData
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
        $regexp     = '/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D';
        $index      = array_search('email', $this->columnAssignment);
        $emails     = array();

        while ($row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)) {
            $email = trim($row[$index]);

            if (!empty($email) && preg_match($regexp, $email) && !in_array($email, $emails)) {
                $recipients++;
                $emails[] = $email;
            }
        }

        fclose($in);

        return $recipients;
    }

    /**
     * Get all recipients.
     *
     * @param int $limit  Limit result to given count.
     * @param int $offset Skip certain count of recipients.
     *
     * @return RecipientInterface[]
     */
    public function getRecipients($limit = null, $offset = null)
    {
        $in = fopen($this->file, 'r');

        if (!$in) {
            return null;
        }

        $recipients = array();
        $regexp     = '/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D';
        $index      = array_search('email', $this->columnAssignment);
        $emails     = array();

        // skip offset lines
        for (; $offset > 0 && !feof($in); $offset--) {
            $row   = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape);
            $email = trim($row[$index]);

            // skip invalid lines without counting them
            if (empty($email) || !preg_match($regexp, $email) || in_array($email, $emails)) {
                $offset++;
            } else {
                $emails[] = $email;
            }
        }

        // read lines
        while (
            (!$limit || count($recipients) < $limit)
            && $row = fgetcsv($in, 0, $this->delimiter, $this->enclosure, $this->escape)
        ) {
            $details = array();

            foreach ($this->columnAssignment as $index => $field) {
                if (isset($row[$index])) {
                    $details[$field] = trim($row[$index]);
                }
            }

            if (
                !empty($details['email'])
                && preg_match($regexp, $details['email'])
                && !in_array($details['email'], $emails)
            ) {
                $recipients[] = new MutableRecipient($details['email'], $details);
                $emails[]     = $details['email'];
            }
        }

        fclose($in);

        return $recipients;
    }
}
