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

namespace Avisota\Recipient;

/**
 * The basic recipient interface.
 *
 * @package avisota-core
 */
interface RecipientInterface
{
    /**
     * Get the recipient email address.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Check if this recipient has personal data.
     *
     * @return bool
     */
    public function hasDetails();

    /**
     * Get a single personal data field value.
     * Return null if the field does not exists.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name);

    /**
     * Get all personal data values as associative array.
     *
     * The personal data must have a key 'email', that contains the email address.
     * <pre>
     * array (
     *     'email' => '...',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getDetails();

    /**
     * Get all personal data keys.
     *
     * The keys must contain 'email'.
     * <pre>
     * array (
     *     'email',
     *     ...
     * )
     * </pre>
     *
     * @return array
     */
    public function getKeys();
}
