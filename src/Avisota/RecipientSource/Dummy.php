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

namespace Avisota\RecipientSource;

use Avisota\Recipient\MutableRecipient;

/**
 * A recipient source that read the recipients from a csv file.
 *
 * @package avisota-core
 */
class Dummy implements RecipientSourceInterface
{
    /**
     * @var int
     */
    protected $minCount;

    /**
     * @var int
     */
    protected $maxCount;

    /**
     * @var array
     */
    protected $set;

    /**
     * @param string $fileData
     */
    public function __construct($minCount, $maxCount)
    {
        $this->minCount = (int) $minCount;
        $this->maxCount = (int) $maxCount;
    }

    /**
     * Count the recipients.
     *
     * @return int
     */
    public function countRecipients()
    {
        return count($this->getRecipients());
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients($limit = null, $offset = null)
    {
        if (empty($this->set)) {
            $this->set = array();

            $count = rand($this->minCount, $this->maxCount);
            for ($i = 0; $i < $count; $i++) {
                list($forename, $surname, $name, $domain) = $this->createName();

                $recipient = new MutableRecipient($name . '@' . $domain);
                $recipient->set('forename', $forename);
                $recipient->set('surname', $surname);
                $this->set[] = $recipient;
            }
        }

        $set = $this->set;

        if ($offset > 0) {
            $set = array_slice($set, $offset);
        }
        if ($limit > 0 && $limit < count($set)) {
            $set = array_slice($set, 0, $limit);
        }

        return $set;
    }

    /**
     * @param int $minCount
     */
    public function setMinCount($minCount)
    {
        $this->minCount = (int) $minCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinCount()
    {
        return $this->minCount;
    }

    /**
     * @param int $maxCount
     */
    public function setMaxCount($maxCount)
    {
        $this->maxCount = (int) $maxCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxCount()
    {
        return $this->maxCount;
    }

    /**
     * @param array $forenames
     */
    public function setForenames($forenames)
    {
        $this->forenames = $forenames;
        return $this;
    }

    /**
     * @return array
     */
    public function getForenames()
    {
        return $this->forenames;
    }

    /**
     * @param array $surnames
     */
    public function setSurnames($surnames)
    {
        $this->surnames = $surnames;
        return $this;
    }

    /**
     * @return array
     */
    public function getSurnames()
    {
        return $this->surnames;
    }

    /**
     * @param array $domains
     */
    public function setDomains($domains)
    {
        $this->domains = $domains;
        return $this;
    }

    /**
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Create a new random name.
     *
     * @return array An array contains forename, surname, name and domain.
     */
    protected function createName()
    {
        $index    = rand(0, count($this->forenames) - 1);
        $forename = $this->forenames[$index];

        $index   = rand(0, count($this->surnames) - 1);
        $surname = $this->surnames[$index];

        $index  = rand(0, count($this->domains) - 1);
        $domain = $this->domains[$index];

        switch (rand(1, 8)) {
            case 1:
                $name = $forename;
                break;
            case 2:
                $name = $surname;
                break;
            case 3:
                $name = $forename . '.' . $surname;
                break;
            case 4:
                $name = $forename . '-' . $surname;
                break;
            case 5:
                $length  = mb_strlen($forename);
                $length2 = floor($length / 2);
                $name    = mb_substr($forename, 0, rand($length2, $length - 1)) . rand(80, 10);
                break;
            case 6:
                $length  = mb_strlen($surname);
                $length2 = floor($length / 2);
                $name    = mb_substr($surname, 0, rand($length2, $length - 1)) . rand(80, 10);
                break;
            case 7:
                $length          = mb_strlen($forename);
                $length2         = floor($length / 2);
                $forenameShorten = mb_substr($forename, 0, rand($length2, $length - 1));
                $length          = mb_strlen($surname);
                $length2         = floor($length / 2);
                $surnameShorten  = mb_substr($surname, 0, rand($length2, $length - 1));
                $name            = $forenameShorten . '.' . $surnameShorten . rand(80, 10);
                break;
            case 8:
                $length          = mb_strlen($forename);
                $length2         = floor($length / 2);
                $forenameShorten = mb_substr($forename, 0, rand($length2, $length - 1));
                $length          = mb_strlen($surname);
                $length2         = floor($length / 2);
                $surnameShorten  = mb_substr($surname, 0, rand($length2, $length - 1));
                $name            = $forenameShorten . '-' . $surnameShorten . rand(80, 10);
                break;
            default:
                continue;
        }

        return array($forename, $surname, $name, $domain);
    }

    protected $forenames = array(
        'Adelheid',
        'Andreas',
        'Anni',
        'Arite',
        'Bernhilde',
        'Bertin',
        'Burchard',
        'Burghild',
        'Catarina',
        'Christamaria',
        'Christophorus',
        'Conny',
        'Dankfried',
        'Dieter',
        'Dietmar',
        'Dorlies',
        'Ekhard',
        'Emmy',
        'Erni',
        'Ernstfried',
        'Felix',
        'Freiwald',
        'Friedegund',
        'Fraenzi',
        'Gerdt',
        'Gerwin',
        'Gitti',
        'Gundel',
        'Hardi',
        'Hartmann',
        'Helma',
        'Helwart',
        'Ingolde',
        'Isabelle',
        'Iselore',
        'Ishild',
        'Jana',
        'Janfried',
        'Jannick',
        'Josepha',
        'Kai',
        'Karina',
        'Katharina',
        'Kathrinchen',
        'Landolf',
        'Lenz',
        'Liane',
        'Loremarie',
        'Marei',
        'Marianne',
        'Mayk',
        'Melitta',
        'Neidhard',
        'Nick',
        'Nordfried',
        'Notburga',
        'Ole',
        'Oslinde',
        'Ottobert',
        'Ottokar',
        'Petra',
        'Philip',
        'Phillippus',
        'Pirmin',
        'Quintus',
        'Quirin',
        'Reinfriede',
        'Roselies',
        'Rudolfina',
        'Rupprecht',
        'Sibyl',
        'Sieglind',
        'Steff',
        'Sylke',
        'Therese',
        'Torben',
        'Traudl',
        'Trautlinde',
        'Udo',
        'Uli',
        'Ulrich',
        'Urban',
        'Viola',
        'Vitus',
        'Volkwart',
        'Vreni',
        'Wendel',
        'Wendeline',
        'Wilgard',
        'Wilhard',
        'Xaver',
        'Xaverius',
        'Yannick',
        'Yannik',
        'Yasmin',
        'Yvonne',
        'Zacharias',
        'Zenzi',
        'Zilli',
        'Zita',
    );

    protected $surnames = array(
        'Mueller',
        'Schmidt',
        'Schneider',
        'Fischer',
        'Weber',
        'Meyer',
        'Wagner',
        'Becker',
        'Schulz',
        'Hoffmann',
        'Schaefer',
        'Koch',
        'Bauer',
        'Richter',
        'Klein',
        'Wolf',
        'Schroeder',
        'Schneider',
        'Neumann',
        'Schwarz',
        'Zimmermann',
        'Braun',
        'Krueger',
        'Hofmann',
        'Hartmann',
        'Lange',
        'Schmitt',
        'Werner',
        'Schmitz',
        'Krause',
        'Meier',
        'Lehmann',
        'Schmid',
        'Schulze',
        'Maier',
        'Koehler',
        'Herrmann',
        'Koenig',
        'Walter',
        'Mayer',
        'Huber',
        'Kaiser',
        'Fuchs',
        'Peters',
        'Lang',
        'Scholz',
        'Moeller',
        'Weiss',
        'Jung',
        'Hahn',
        'Schubert',
        'Vogel',
        'Friedrich',
        'Keller',
        'Guenther',
        'Frank',
        'Berger',
        'Winkler',
        'Roth',
        'Beck',
        'Lorenz',
        'Baumann',
        'Franke',
        'Albrecht',
        'Schuster',
        'Simon',
        'Ludwig',
        'Boehm',
        'Winter',
        'Kraus',
        'Martin',
        'Schumacher',
        'Kraemer',
        'Vogt',
        'Stein',
        'Jaeger',
        'Otto',
        'Sommer',
        'Gross',
        'Seidel',
        'Heinrich',
        'Brandt',
        'Haas',
        'Schreiber',
        'Graf',
        'Schulte',
        'Dietrich',
        'Ziegler',
        'Kuhn',
        'Kuehn',
        'Pohl',
        'Engel',
        'Horn',
        'Busch',
        'Bergmann',
        'Thomas',
        'Voigt',
        'Sauer',
        'Arnold',
        'Wolff',
        'Pfeiffer',
    );

    protected $domains = array(
        'gmail.com',
        'zoho.com',
        'aol.com',
        'shortmail.me',
        'outlook.com',
        'yahoo.com',
        'mail.com',
        'gmx.com',
        'facebook.com',
        'inbox.com',
    );
}
