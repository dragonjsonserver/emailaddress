<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Api;

/**
 * API Klasse zur Verwaltung von E-Mail Adressvalidierungen
 */
class Validationrequest
{
	use \DragonJsonServer\ServiceManagerTrait;
	
	/**
	 * Gibt die E-Mail Adressvalidierung des aktuellen Accounts zurück
	 * @return array|null
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function getValidationrequest()
	{
		$serviceManager = $this->getServiceManager();
	
		$session = $serviceManager->get('Session')->getSession();
		$emailaddress = $serviceManager->get('Emailaddress')->getEmailaddressByAccountId($session->getAccountId());
		$validationrequest = $serviceManager->get('Validationrequest')
			->getValidationrequestByEmailaddressId($emailaddress->getEmailaddressId(), false); 
		if (null !== $validationrequest) {
			return $validationrequest->toArray();
		} 
		return;
	}

	/**
	 * Sendet die E-Mail Adressvalidierung erneut
	 * @DragonJsonServerAccount\Annotation\Session
	 */
	public function resendValidationrequest()
	{
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('Session')->getSession();
		$serviceEmailaddress = $serviceManager->get('Emailaddress');
		$emailaddress = $serviceEmailaddress->getEmailaddressByAccountId($session->getAccountId());
		$serviceValidationrequest = $serviceManager->get('Validationrequest');
		$validationrequest = $serviceValidationrequest->getValidationrequestByEmailaddressId($emailaddress->getEmailaddressId());
		$serviceValidationrequest->sendValidationrequest($emailaddress, $validationrequest);
	}
	
	/**
	 * Validiert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $validationrequesthash
	 */
	public function validateEmailaddress($validationrequesthash)
	{
		$serviceManager = $this->getServiceManager();

		$serviceManager->get('Validationrequest')->validateEmailaddress($validationrequesthash);
	}
}
