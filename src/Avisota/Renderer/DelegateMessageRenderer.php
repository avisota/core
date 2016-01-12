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
 * Class DelegateMessageRenderer
 *
 * Implementation of a delegate message renderer.
 * Primary used as base class for custom implementations.
 */
class DelegateMessageRenderer implements MessageRendererInterface
{
    /**
     * @var MessageRendererInterface
     */
    protected $delegate;

    /**
     * DelegateMessageRenderer constructor.
     *
     * @param MessageRendererInterface $delegate
     */
    public function __construct(MessageRendererInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param \Avisota\Renderer\MessageRendererInterface $delegate
     *
     * @return $this
     */
    public function setDelegate(MessageRendererInterface $delegate)
    {
        $this->delegate = $delegate;
        return $this;
    }

    /**
     * @return \Avisota\Renderer\MessageRendererInterface
     */
    public function getDelegate()
    {
        return $this->delegate;
    }

    /**
     * Render a message and create a Swift_Message.
     *
     * @param MessageInterface $message
     *
     * @return \Swift_Message
     */
    public function renderMessage(MessageInterface $message)
    {
        return $this->delegate->renderMessage($message);
    }

    /**
     * Check if this renderer can render the message.
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function canRender(MessageInterface $message)
    {
        return $this->delegate->canRender($message);
    }
}
