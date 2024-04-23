<?php

return [
	'test' => 'Don\'t merge multiples occurences of same @media (no merge)',
	'expectedReturnValue' => true,
	'settings' => [
		'merge_selectors' => 0,
	],
];
