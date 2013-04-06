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
     * @param \DragonJsonServerEmailaddress\Entity\Validationrequest $validationrequest
     * @return Validate
     */
    public function setValidationrequest(\DragonJsonServerEmailaddress\Entity\Validationrequest $validationrequest)
    {
        $this->setParam('validationrequest', $validationrequest);
        return $this;
    }

    /**
     * Gibt die E-Mail Adressvalidierungsanfrage zurück
     * @return \DragonJsonServerEmailaddress\Entity\Validationrequest
     */
    public function getValidationrequest()
    {
        return $this->getParam('validationrequest');
    }
}
