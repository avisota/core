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

namespace Avisota\Test\Imap;

/**
 * Interface ImapConnectionProviderInterface
 *
 * @package Avisota\Test\Imap
 */
interface ImapConnectionProviderInterface
{
    /**
     * @return ressource
     */
    public function createImapConnection();
}
