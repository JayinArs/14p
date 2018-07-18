<?php

return [
	'navigations' => [
		'administrator' => [
			[ 'label' => 'Main', 'item_type' => 'heading' ],
			[
				'label'     => 'Dashboard',
				'action'    => 'dashboard',
				'icon'      => 'icon-speedometer',
				'item_type' => 'item'
			],
			[
				'label'     => 'Members',
				'action'    => 'member',
				'icon'      => 'icon-user',
				'item_type' => 'item'
			],
			[
				'label'     => 'Quizzes',
				'action'    => 'quiz',
				'icon'      => 'icon-question',
				'item_type' => 'item'
			]
		]
	],
	"HTTP_CODES"  => [
		"UNAUTHORIZED"   => 401,
		"NOT_FOUND"      => 404,
		"INTERNAL_ERROR" => 500,
		"SUCCESS"        => 200,
		"FAILED"         => 403
	],
	'quiz'        => [
		'status' => [
			'Inactive',
			'Active'
		],
		'type'   => [
			'Reading',
			'Listening',
			'Viewing'
		]
	],
	'question'    => [
		'type' => [
			'Single Choice',
			'Multiple Choice',
			'Descriptive'
		]
	],
	'member'      => [
		'age_groups' => [
			'5-15',
			'16-25',
			'26-40',
			'41-60',
			'60+'
		],
		'genders'    => [
			'male'   => 'Male',
			'female' => 'Female'
		]
	],
	'countries'   => [
		'US' => [
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AS' => 'American Samoa',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'DC' => 'District Of Columbia',
			'FM' => 'Federated States Of Micronesia',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'GU' => 'Guam',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MH' => 'Marshall Islands',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'MP' => 'Northern Mariana Islands',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PW' => 'Palau',
			'PA' => 'Pennsylvania',
			'PR' => 'Puerto Rico',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VI' => 'Virgin Islands',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming',
		],
	],
];
