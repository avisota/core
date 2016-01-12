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

namespace Avisota\Test\Imap;

class ImapMailboxChecker
{
    /**
     * @param resource $imapConnection
     * @param array    $messages
     * @param bool     $clean
     *
     * @return bool Return <em>true</em> if <strong>all</strong> messages exist in the inbox.
     */
    public function checkMessages($imapConnection, array $messages, $clean = true)
    {
        $bodies = array_map(
            function ($message) {
                return $message->getText() . "\r\n";
            },
            $messages
        );
        $host   = $_ENV['AVISOTA_TEST_IMAP_HOST'] ?: getenv('AVISOTA_TEST_IMAP_HOST');
        $hits   = 0;

        for ($i = 0; $i < 30 && $hits < count($bodies); $i++) {
            // wait for the mail server
            sleep(2);

            imap_gc($imapConnection, IMAP_GC_ENV);
            $status = imap_status($imapConnection, '{' . $host . '}', SA_MESSAGES);

            for ($j = $status->messages; $j > 0; $j--) {
                $body = imap_body($imapConnection, $j);

                if (in_array($body, $bodies)) {
                    $hits++;

                    if ($clean) {
                        imap_delete($imapConnection, $j);
                    }
                }
            }

            imap_expunge($imapConnection);
        }

        return $hits;
    }
}
