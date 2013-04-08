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
 * Eventklasse für die Verknüpfung eines Accounts mit einer E-Mail Adresse
 */
class CreateEmailaddress extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'createemailaddress';

    /**
     * Setzt den Account der mit der E-Mail Adresse verknüpft wurde
     * @param \DragonJsonServerAccount\Entity\Account $account
     * @return CreateEmailaddress
     */
    public function setAccount(\DragonJsonServerAccount\Entity\Account $account)
    {
        $this->setParam('account', $account);
        return $this;
    }

    /**
     * Gibt den Account der mit der E-Mail Adresse verknüpft wurde zurück
     * @return \DragonJsonServerAccount\Entity\Account
     */
    public function getAccount()
    {
        return $this->getParam('account');
    }

    /**
     * Setzt die E-Mail Adresse die mit dem Account verknüpft wurde
     * @param \DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress
     * @return CreateEmailaddress
     */
    public function setEmailaddress(\DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress)
    {
        $this->setParam('emailaddress', $emailaddress);
        return $this;
    }

    /**
     * Gibt die E-Mail Adresse die mit dem Account verknüpft wurde zurück
     * @return \DragonJsonServerEmailaddress\Entity\Emailaddress
     */
    public function getEmailaddress()
    {
        return $this->getParam('emailaddress');
    }
}
