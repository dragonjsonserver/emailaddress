<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

namespace DragonJsonServerEmailaddress\Service;

/**
 * Serviceklasse zur Verwaltung einer E-Mail Adressvalidierung
 */
class Validationrequest
{
    use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServer\EventManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
	/**
	 * Gibt die E-Mail Adressvalidierung zur übergebenen EmailaddressID zurück
	 * @param integer $emailaddress_id
	 * @param boolean $throwException
	 * @return \DragonJsonServerEmailaddress\Entity\Validationrequest
     * @throws \DragonJsonServer\Exception
	 */
	public function getValidationrequestByEmailaddressId($emailaddress_id, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['emailaddress_id' => $emailaddress_id];
		$validationrequest = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Validationrequest')
			->findOneBy($conditions);
		if (null === $validationrequest && $throwException) {
			throw new \DragonJsonServer\Exception('invalid emailaddress_id', $conditions);
		}
		return $validationrequest;
	}
	
	/**
	 * Sendet die E-Mail Adressvalidierung
	 * @param \DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress
	 * @param \DragonJsonServerEmailaddress\Entity\Validationrequest $validationrequest
	 */
	public function sendValidationrequest(\DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress,
										  \DragonJsonServerEmailaddress\Entity\Validationrequest $validationrequest)
	{
		$configEmailaddress = $this->getServiceManager()->get('Config')['emailaddress'];
		$message = (new \Zend\Mail\Message())
			->addTo($emailaddress->getEmailaddress())
			->addFrom($configEmailaddress['from'])
			->setSubject($configEmailaddress['validationrequest']['subject'])
			->setBody(str_replace(
					'%validationrequesthash%',
					$validationrequest->getValidationrequesthash(),
					$configEmailaddress['validationrequest']['body']
			));
		(new \Zend\Mail\Transport\Sendmail())->send($message);
	}
	
	/**
	 * Erstellt eine Anfrage für eine E-Mail Adressvalidierung
	 * @param \DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress
	 */
	public function createValidationrequest(\DragonJsonServerEmailaddress\Entity\Emailaddress $emailaddress)
	{
		$entityManager = $this->getEntityManager();

		$validationrequest = $serviceManager->get('Validationrequest')->getValidationrequestByEmailaddressId($emailaddress_id, false);
		if (null === $validationrequest) {
			$validationrequest = (new \DragonJsonServerEmailaddress\Entity\Validationrequest())
				->setEmailaddressId($emailaddress->getEmailaddressId())
				->setValidationrequesthash(md5($emailaddress->getEmailaddressId() . microtime(true)));
			$entityManager->persist($validationrequest);
			$entityManager->flush();
		}
		$this->sendValidationrequest($emailaddress, $validationrequest);
	}
	
	/**
	 * Validiert die E-Mail Adresse der E-Mail Adressverknüpfung
	 * @param string $validationrequesthash
	 * @throws \DragonJsonServer\Exception
	 */
	public function validateEmailaddress($validationrequesthash)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['validationrequesthash' => $validationrequesthash];
		$validationrequest = $entityManager
			->getRepository('\DragonJsonServerEmailaddress\Entity\Validationrequest')
			->findOneBy($conditions);
		if (null === $validationrequest) {
			throw new \DragonJsonServer\Exception('invalid validationrequesthash', $conditions);
		}
		$this->getEventManager()->trigger(
			(new \DragonJsonServerEmailaddress\Event\ValidateEmailaddress())
				->setTarget($this)
				->setValidationrequest($validationrequest)
		);
		$entityManager->remove($validationrequest);
		$entityManager->flush();
	}
}
