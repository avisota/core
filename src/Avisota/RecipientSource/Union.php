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

namespace Avisota\RecipientSource;

/**
 * Combine one or more recipient sources.
 *
 * @package avisota-core
 */
class Union implements RecipientSourceInterface
{

    /**
     * @var RecipientSourceInterface[]
     */
    private $recipientSources;

    /**
     * @var bool
     */
    private $clean = false;

    /**
     * @param array|\Traversable|RecipientSourceInterface[] $fileData
     */
    public function __construct($recipientSources = array(), $clean = false)
    {
        $this->setRecipientSources($recipientSources);
        $this->clean = (bool) $clean;
    }

    /**
     * @return RecipientSourceInterface[]
     */
    public function getRecipientSources()
    {
        return array_values($this->recipientSources);
    }

    /**
     * @param array|\Traversable|RecipientSourceInterface[] $recipientSources
     *
     * @return static
     */
    public function setRecipientSources($recipientSources)
    {
        $this->recipientSources = array();
        $this->addRecipientSources($recipientSources);
        return $this;
    }

    /**
     * @param array|\Traversable|RecipientSourceInterface[] $recipientSources
     *
     * @return static
     */
    public function addRecipientSources($recipientSources)
    {
        foreach ($recipientSources as $recipientSource) {
            $this->addRecipientSource($recipientSource);
        }
        return $this;
    }

    /**
     * @param RecipientSourceInterface $recipientSource
     *
     * @return static
     */
    public function addRecipientSource(RecipientSourceInterface $recipientSource)
    {
        $hash                          = spl_object_hash($recipientSource);
        $this->recipientSources[$hash] = $recipientSource;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isClean()
    {
        return $this->clean;
    }

    /**
     * @param boolean $clean
     *
     * @return static
     */
    public function setClean($clean)
    {
        $this->clean = $clean;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function countRecipients()
    {
        $count = 0;

        if ($this->isClean()) {
            $emails = array();

            foreach ($this->recipientSources as $recipientSource) {
                $offset = 0;

                do {
                    $recipients = $recipientSource->getRecipients(10, $offset);

                    foreach ($recipients as $recipient) {
                        if (!in_array($recipient->getEmail(), $emails)) {
                            $emails[] = $recipient->getEmail();
                        }
                        unset($recipient);
                    }

                    $count = count($recipients);
                    $offset += 10;
                    unset($recipients);
                } while ($count > 0);
            }

            $count = count($emails);
        } else {
            foreach ($this->recipientSources as $recipientSource) {
                $count += $recipientSource->countRecipients();
            }
        }

        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients($limit = null, $offset = null)
    {
        $recipients = array();

        if ($this->isClean()) {
            $emails = array();

            // duplicate array
            /** @var RecipientSourceInterface[] $recipientSources */
            $recipientSources = array_merge($this->recipientSources);

            // we need to walk over all offset and limited recipients
            if ($limit !== null && $offset !== null) {
                $limit += $offset;
            }

            while (
                count($recipientSources)
                && ($limit === null || count($recipients) < $limit)
            ) {
                // get the current recipient source
                /** @var RecipientSourceInterface $recipientSource */
                $recipientSource = array_shift($recipientSources);

                // walk over recipients from the current recipient source
                $localOffset = 0;
                do {
                    $tempRecipients = $recipientSource->getRecipients(10, $localOffset);

                    foreach ($tempRecipients as $recipient) {
                        if (!in_array($recipient->getEmail(), $emails)) {
                            $recipients[] = $recipient;
                            $emails[]     = $recipient->getEmail();
                        }
                        unset($recipient);

                        if ($limit !== null && count($recipients) >= $limit) {
                            break;
                        }
                    }

                    $count = count($tempRecipients);
                    $localOffset += 10;
                    unset($tempRecipients);
                } while ($count > 0 && ($limit === null || count($recipients) < $limit));
            }

            if ($offset !== null) {
                $recipients = array_slice($recipients, $offset);
            }
        } else {
            // duplicate array
            $recipientSources = array_merge($this->recipientSources);

            while (
                count($recipientSources)
                && ($limit === null || count($recipients) < $limit)
            ) {
                // calculate limit for current recipient source
                $tempLimit = $limit === null ? null : $limit - count($recipients);
                // get the current recipient source
                $recipientSource = array_shift($recipientSources);
                // fetch the recipients from current recipient source
                $tempRecipients = $recipientSource->getRecipients($tempLimit, $offset);
                // merge current with previous recipients
                $recipients = array_merge($recipients, $tempRecipients);

                if ($offset > 0) {
                    $total = count($tempRecipients);
                    $max   = $recipientSource->countRecipients();
                    $offset -= ($offset + $total) >= $max ? ($max - $total) : $total;
                }
            }
        }

        return $recipients;
    }
}
