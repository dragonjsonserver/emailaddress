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
	 * Sendet die E-Mail Adressvalidierung erneut
	 * @authenticate
	 */
	public function resendValidationrequest()
	{
		$serviceManager = $this->getServiceManager();

		$session = $serviceManager->get('Session')->getSession();
		$account = $serviceManager->get('Account')->getAccount($session->getAccountId());
		$serviceEmailaddress = $serviceManager->get('Emailaddress');
		$emailaddress = $serviceEmailaddress->getEmailaddressByAccountId($account->getAccountId());
		$serviceValidationrequest = $serviceManager->get('Validationrequest');
		$serviceValidationrequest->sendValidationrequest(
			$emailaddress, 
			$serviceValidationrequest->getValidationrequestByEmailaddressId($emailaddress->getEmailaddressId()),
			$this->getServiceManager()->get('Config')['emailaddress']
		);
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
