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
		'from' => 'noreply@dragonjsonserver.de',
	],
	'dragonjsonserver' => [
	    'apiclasses' => [
	        '\DragonJsonServerEmailaddress\Api\Emailaddress' => 'Emailaddress',
	        '\DragonJsonServerEmailaddress\Api\Validationrequest' => 'Validationrequest',
	        '\DragonJsonServerEmailaddress\Api\Passwordrequest' => 'Passwordrequest',
	    ],
    ],
	'service_manager' => [
		'invokables' => [
            '\DragonJsonServerEmailaddress\Service\Emailaddress' => '\DragonJsonServerEmailaddress\Service\Emailaddress',
            '\DragonJsonServerEmailaddress\Service\Passwordrequest' => '\DragonJsonServerEmailaddress\Service\Passwordrequest',
            '\DragonJsonServerEmailaddress\Service\Validationrequest' => '\DragonJsonServerEmailaddress\Service\Validationrequest',
		],
	],
	'doctrine' => [
		'driver' => [
			'DragonJsonServerEmailaddress_driver' => [
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => [
					__DIR__ . '/../src/DragonJsonServerEmailaddress/Entity'
				],
			],
			'orm_default' => [
				'drivers' => [
					'DragonJsonServerEmailaddress\Entity' => 'DragonJsonServerEmailaddress_driver'
				],
			],
		],
	],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'phparray',
                'base_dir' => __DIR__ . '/../language/dragonjsonserveremailaddress',
                'pattern' => '%s.php',
                'text_domain' => 'dragonjsonserveremailaddress',
            ],
        ],
    ],
];
