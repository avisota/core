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

use Avisota\Transport\SmtpTransport;

class NoOpTransportProvider implements TransportProviderInterface
{
    protected $succeededStatus;

    function __construct($succeededStatus)
    {
        $this->succeededStatus = $succeededStatus;
    }

    /**
     * @return \Swift_Transport
     */
    public function createTransport()
    {
        return new NoOpTransport($this->succeededStatus);
    }
}
