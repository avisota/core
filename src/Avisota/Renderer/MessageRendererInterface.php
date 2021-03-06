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

namespace Avisota\Renderer;

use Avisota\Message\MessageInterface;

/**
 * The basic message interface.
 *
 * @package avisota-core
 */
interface MessageRendererInterface
{
    /**
     * Render a message and create a Swift_Message.
     *
     * @param MessageInterface $message
     *
     * @return \Swift_Message
     */
    public function renderMessage(MessageInterface $message);

    /**
     * Check if this renderer can render the message.
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function canRender(MessageInterface $message);
}
