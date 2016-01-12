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
use Avisota\Message\NativeMessage;

/**
 * The basic message interface.
 *
 * @package avisota-core
 */
class NativeMessageRenderer implements MessageRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function renderMessage(MessageInterface $message)
    {
        return $message->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function canRender(MessageInterface $message)
    {
        return $message instanceof NativeMessage;
    }
}
