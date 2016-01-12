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

namespace Avisota\Test\Transport;

use Avisota\Test\Renderer\TestMessageRenderer;
use Avisota\Transport\SmtpTransport;

/**
 * Class SmtpTransportProvider
 *
 * @package Avisota\Test\Transport
 */
class SmtpTransportProvider implements TransportProviderInterface
{
    /**
     * @return \Swift_Transport
     */
    public function createTransport()
    {
        $host       = array_key_exists('AVISOTA_TEST_SMTP_HOST', $_ENV)
            ? $_ENV['AVISOTA_TEST_SMTP_HOST']
            : getenv('AVISOTA_TEST_SMTP_HOST');
        $port       = array_key_exists('AVISOTA_TEST_SMTP_PORT', $_ENV)
            ? $_ENV['AVISOTA_TEST_SMTP_PORT']
            : getenv('AVISOTA_TEST_SMTP_PORT');
        $username   = array_key_exists('AVISOTA_TEST_SMTP_USERNAME', $_ENV)
            ? $_ENV['AVISOTA_TEST_SMTP_USERNAME']
            : getenv('AVISOTA_TEST_SMTP_USERNAME');
        $password   = array_key_exists('AVISOTA_TEST_SMTP_PASSWORD', $_ENV)
            ? $_ENV['AVISOTA_TEST_SMTP_PASSWORD']
            : getenv('AVISOTA_TEST_SMTP_PASSWORD');
        $encryption = array_key_exists('AVISOTA_TEST_SMTP_ENCRYPTION', $_ENV)
            ? $_ENV['AVISOTA_TEST_SMTP_ENCRYPTION']
            : getenv('AVISOTA_TEST_SMTP_ENCRYPTION');

        if ($host && $username && $password) {
            return new SmtpTransport(
                $host,
                $port,
                $username,
                $password,
                $encryption,
                new TestMessageRenderer()
            );
        }

        return false;
    }
}
