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

namespace Avisota\Transport;

use Avisota\Renderer\MessageRendererInterface;

/**
 * Abstract transport base class.
 *
 * @author     Tristan Lins <tristan.lins@bit3.de>
 */
abstract class AbstractTransport implements TransportInterface
{
	/**
	 * @var MessageRendererInterface
	 */
	protected $renderer;

	/**
	 * @param MessageRendererInterface $renderer
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
