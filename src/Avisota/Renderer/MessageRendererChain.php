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
class MessageRendererChain implements MessageRendererInterface
{
    /**
     * @var MessageRendererInterface[][]
     */
    protected $chain = array();

    /**
     * Add a renderer to this chain.
     *
     * @param MessageRendererInterface $renderer The renderer to add.
     * @param int                      $priority The priority of the renderer,
     *                                           higher value means higher priority.
     *
     * @return void
     */
    public function addRenderer(MessageRendererInterface $renderer, $priority = 0)
    {
        $this->removeRenderer($renderer);

        $hash = spl_object_hash($renderer);
        if (!isset($this->chain[$priority])) {
            $this->chain[$priority] = array($hash => $renderer);
            krsort($this->chain);
        } else {
            $this->chain[$priority][$hash] = $renderer;
        }
    }

    /**
     * Remove a renderer from this chain.
     *
     * @param MessageRendererInterface $renderer
     *
     * @return void
     */
    public function removeRenderer(MessageRendererInterface $renderer)
    {
        $hash = spl_object_hash($renderer);
        foreach ($this->chain as &$renderers) {
            unset($renderers[$hash]);
        }
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
        foreach ($this->chain as $renderers) {
            foreach ($renderers as $renderer) {
                if ($renderer->canRender($message)) {
                    return $renderer->renderMessage($message);
                }
            }
        }

        throw new \RuntimeException('Could not render message ' . $message->getSubject());
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
        foreach ($this->chain as $renderers) {
            foreach ($renderers as $renderer) {
                if ($renderer->canRender($message)) {
                    return true;
                }
            }
        }

        return false;
    }
}
