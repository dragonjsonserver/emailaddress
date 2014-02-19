<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerEmailaddress
 */

/**
 * @return array
 */
return [
	'dragonjsonserveremailaddress' => [
		'from' => '%from%',
		'validationrequest' => [
			'subject' => '%subject%',
			'body' => '%body% with %validationrequesthash%',
		],
		'passwordrequest' => [
			'subject' => '%subject%',
			'body' => '%body% with %passwordrequesthash%',
		],
	],
];
