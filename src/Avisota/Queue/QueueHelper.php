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

namespace Avisota\Queue;

use Avisota\Event\PostEnqueueEvent;
use Avisota\Event\PreEnqueueEvent;
use Avisota\RecipientSource\RecipientSourceInterface;
use Avisota\Templating\MessageTemplateInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A collection of helper functions.
 *
 * @package avisota-core
 */
class QueueHelper
{
	/**
	 * @var MessageTemplateInterface
	 */
	protected $messageTemplate = null;

	/**
	 * @var RecipientSourceInterface
	 */
	protected $recipientSource = null;

	/**
	 * @var QueueInterface
	 */
	protected $queue = null;

	/**
	 * @var int
	 */
	protected $limit = null;

	/**
	 * @var int
	 */
	protected $offset = null;

	/**
	 * @var array
	 */
	protected $newsletterData = null;

	/**
	 * @var \DateTime
	 */
	protected $deliveryDate = null;

	/**
	 * @var EventDispatcher
	 */
	protected $eventDispatcher = null;

	/**
	 * @param \Avisota\Templating\MessageTemplateInterface $messageTemplate
	 */
	public function setMessageTemplate($messageTemplate)
	{
		$this->messageTemplate = $messageTemplate;
		return $this;
	}

	/**
	 * @return \Avisota\Templating\MessageTemplateInterface
	 */
	public function getMessageTemplate()
	{
		return $this->messageTemplate;
	}

	/**
	 * @param \Avisota\RecipientSource\RecipientSourceInterface $recipientSource
	 */
	public function setRecipientSource($recipientSource)
	{
		$this->recipientSource = $recipientSource;
		return $this;
	}

	/**
	 * @return \Avisota\RecipientSource\RecipientSourceInterface
	 */
	public function getRecipientSource()
	{
		return $this->recipientSource;
	}

	/**
	 * @param \Avisota\Queue\QueueInterface $queue
	 */
	public function setQueue($queue)
	{
		$this->queue = $queue;
		return $this;
	}

	/**
	 * @return \Avisota\Queue\QueueInterface
	 */
	public function getQueue()
	{
		return $this->queue;
	}

	/**
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * @param int $offset
	 */
	public function setOffset($offset)
	{
		$this->offset = $offset;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * @param array $newsletterData
	 */
	public function setNewsletterData($newsletterData)
	{
		$this->newsletterData = $newsletterData;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getNewsletterData()
	{
		return $this->newsletterData;
	}

	/**
	 * @param \DateTime $deliveryDate
	 */
	public function setDeliveryDate($deliveryDate)
	{
		$this->deliveryDate = $deliveryDate;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getDeliveryDate()
	{
		return $this->deliveryDate;
	}

	/**
	 * @param EventDispatcher $eventDispatcher
	 */
	public function setEventDispatcher(EventDispatcher $eventDispatcher = null)
	{
		$this->eventDispatcher = $eventDispatcher;
		return $this;
	}

	/**
	 * @return EventDispatcher
	 */
	public function getEventDispatcher()
	{
		return $this->eventDispatcher;
	}

	/**
	 * Enqueue the message for all recipients into the queue.
	 *
	 * @param mixed $_ All data that is not yet provided.
	 * Please note that $limit is prefered before $offset,
	 * if you want to only set $offset pass 0 for $limit first.
	 *
	 * @return void
	 *
	 * Complexity cannot easily reduced without a huge overhead :-(
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function enqueue()
	{
		$messageTemplate = $this->messageTemplate;
		$recipientSource = $this->recipientSource;
		$queue = $this->queue;
		$limit = $this->limit;
		$offset = $this->offset;
		$newsletterData = $this->newsletterData;
		$deliveryDate = $this->deliveryDate;

		$args = func_get_args();
		foreach ($args as $arg) {
			if ($arg instanceof MessageTemplateInterface) {
				$messageTemplate = $arg;
				continue;
			}
			else if ($arg instanceof RecipientSourceInterface) {
				$recipientSource = $arg;
				continue;
			}
			else if ($arg instanceof QueueInterface) {
				$queue = $arg;
				continue;
			}
			else if (is_int($arg)) {
				if ($limit === null) {
					$limit = $arg;
					continue;
				}
				if ($offset === null) {
					$offset = $arg;
					continue;
				}
			}
			else if (is_array($arg)) {
				$newsletterData = $arg;
				continue;
			}
			else if ($arg instanceof \DateTime) {
				$deliveryDate = $arg;
				continue;
			}

			if (is_object($arg)) {
				$arg = get_class($arg);
			}
			else {
				$arg = gettype($arg) . ' ' . var_export($arg, true);
			}
			throw new \RuntimeException('Unexpected parameter ' . $arg);
		}

		if (!$messageTemplate) {
			throw new \RuntimeException('Missing required message template');
		}
		if (!$recipientSource) {
			throw new \RuntimeException('Missing required recipient source');
		}
		if (!$queue) {
			throw new \RuntimeException('Missing required queue');
		}

		$count = 0;
		$recipients = $recipientSource->getRecipients($limit, $offset);

		foreach ($recipients as $recipient) {
			$message = $messageTemplate->render($recipient, $newsletterData);

			if ($this->eventDispatcher) {
				$event = new PreEnqueueEvent($message, $queue);
				$this->eventDispatcher->dispatch($event::NAME, $event);

				if ($event->isSkip()) {
					continue;
				}
			}

			if ($queue->enqueue($message, $deliveryDate)) {
				$count ++;

				if ($this->eventDispatcher) {
					$event = new PostEnqueueEvent($message, $queue);
					$this->eventDispatcher->dispatch($event::NAME, $event);
				}
			}
		}

		return $count;
	}
}
