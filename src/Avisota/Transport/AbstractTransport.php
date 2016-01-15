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
 * Abstract transport base class.
 *
 * @author     Sven Baumann <baumann.sv@gmail.com>
 */
abstract class AbstractTransport implements TransportInterface
{
    /**
     * @var MessageRendererInterface
     */
    protected $renderer;

    /**
     * @param MessageRendererInterface $renderer
     *
     * @return $this
     */
    public function setRenderer(MessageRendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @return MessageRendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
}
