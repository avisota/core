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
 * A mutable recipient object.
 *
 * @package avisota-core
 */
class MutableRecipient implements RecipientInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param       $email
     * @param array $details
     */
    public function __construct($email, array $details = array())
    {
        $this->setEmail($email);
        $this->setDetails($details);
    }

    /**
     * Get the recipient email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'];
    }

    /**
     * Set the email address.
     *
     * @param $email
     *
     * @return void
     * @throws MutableRecipientDataException
     */
    public function setEmail($email)
    {
        if (empty($email)) {
            throw new MutableRecipientDataException('Email is required');
        }

        $this->data['email'] = (string) $email;
    }

    /**
     * Check if this recipient has personal data.
     *
     * @return bool
     */
    public function hasDetails()
    {
        return count($this->data) > 1;
    }

    /**
     * Get a single personal data field value.
     * Return null if the field does not exists.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    /**
     * Set a personal data field.
     *
     * @param string $name  The name of the field.
     * @param mixed  $value The value of the field. A value of
     *                      <code>null</code> delete the field.
     *
     * @return void
     */
    public function set($name, $value)
    {
        if ($name == 'email') {
            $this->setEmail($value);
        } else {
            if ($value === null) {
                unset($this->data[$name]);
            } else {
                $this->data[$name] = $value;
            }
        }
    }

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
    public function getDetails()
    {
        return $this->data;
    }

    /**
     * Set multiple personal data fields.
     *
     * @param array $details
     *
     * @return void
     */
    public function setDetails(array $details)
    {
        foreach ($details as $key => $value) {
            $this->set($key, $value);
        }
    }

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
    public function getKeys()
    {
        return array_keys($this->data);
    }
}
