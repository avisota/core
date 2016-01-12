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

namespace Avisota\Renderer;

use Avisota\Message\MessageInterface;
use Avisota\Message\NativeMessage;

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

    public function __construct(MessageRendererInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @param \Avisota\Renderer\MessageRendererInterface $delegate
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
     * {@inheritdoc}
     */
    public function renderMessage(MessageInterface $message)
    {
        return $this->delegate->renderMessage($message);
    }

    /**
     * {@inheritdoc}
     */
    public function canRender(MessageInterface $message)
    {
        return $this->delegate->canRender($message);
    }
}
