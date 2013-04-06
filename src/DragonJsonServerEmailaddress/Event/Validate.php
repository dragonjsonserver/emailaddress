<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Event;

/**
 * Eventklasse für die Validierung einer E-Mail Adresse
 */
class Validate extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'validate';

    /**
     * Setzt die E-Mail Adressvalidierungsanfrage
     * @param \DragonJsonServerEmailaddress\Entity\Emailaddressvalidation $emailaddressvalidation
     * @return Validate
     */
    public function setEmailaddressvalidation(\DragonJsonServerEmailaddress\Entity\Emailaddressvalidation $emailaddressvalidation)
    {
        $this->setParam('emailaddressvalidation', $emailaddressvalidation);
        return $this;
    }

    /**
     * Gibt die E-Mail Adressvalidierungsanfrage zurück
     * @return \DragonJsonServerEmailaddress\Entity\Emailaddressvalidation
     */
    public function getEmailaddressvalidation()
    {
        return $this->getParam('emailaddressvalidation');
    }
}
