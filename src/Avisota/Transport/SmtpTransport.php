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

namespace Avisota\Transport;

use Avisota\Renderer\MessageRendererInterface;

/**
 * Transport using swift smtp transport.
 *
 * @package avisota-core
 */
class SmtpTransport extends AbstractSwiftTransport
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var "ssl"|"tls"
     */
    protected $encryption;

    /**
     * @var \Swift_Mailer|null
     */
    protected $swiftMailer;

    /**
     * @param string                   $host
     * @param null                     $port
     * @param null                     $username
     * @param null                     $password
     * @param null                     $encryption
     *
     * @param MessageRendererInterface $renderer
     */
    public function __construct(
        $host = 'localhost',
        $port = null,
        $username = null,
        $password = null,
        $encryption = null,
        MessageRendererInterface $renderer
    ) {
        $this->host       = $host;
        $this->port       = $port;
        $this->username   = $username;
        $this->password   = $password;
        $this->encryption = $encryption;
        $this->setRenderer($renderer);
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        $this->resetMailer();
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param int $port
     *
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;
        $this->resetMailer();
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->resetMailer();
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->resetMailer();
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $encryption
     *
     * @return $this
     */
    public function setEncryption($encryption)
    {
        $encryption = strtolower($encryption);
        if ($encryption != 'ssl' && $encryption != 'tls') {
            $encryption = null;
        }
        $this->encryption = $encryption;
        $this->resetMailer();
        return $this;
    }

    /**
     * @return string
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * @return \Swift_Mailer
     */
    protected function createMailer()
    {
        $transport = new \Swift_SmtpTransport($this->host);
        if ($this->port) {
            $transport->setPort($this->port);
        }
        if ($this->username) {
            $transport->setUsername($this->username);
        }
        if ($this->password) {
            $transport->setPassword($this->password);
        }
        if ($this->encryption) {
            $transport->setEncryption($this->encryption);
        }

        return \Swift_Mailer::newInstance($transport);
    }
}
