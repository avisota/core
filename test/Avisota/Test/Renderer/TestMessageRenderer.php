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

namespace Avisota\Test\Renderer;

use Avisota\Message\MessageInterface;
use Avisota\Renderer\MessageRendererInterface;

class TestMessageRenderer implements MessageRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function renderMessage(MessageInterface $message)
    {
        $swiftMessage = \Swift_Message::newInstance();
        $swiftMessage->setTo($message->getRecipients());
        $swiftMessage->setCc($message->getCopyRecipients());
        $swiftMessage->setBcc($message->getBlindCopyRecipients());
        $swiftMessage->setFrom($message->getFrom());
        $swiftMessage->setSubject($message->getSubject());
        $swiftMessage->setBody($message->getText(), 'text/plain');
        return $swiftMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function canRender(MessageInterface $message)
    {
        return $message instanceof TestMessage;
    }
}
