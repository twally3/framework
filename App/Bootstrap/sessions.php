<?php

/*
*	Where the kernel checks if a session exists and if not sets the correct value
*/

return [

	'csrf_token' => base64_encode(openssl_random_pseudo_bytes(32)),
	'flash' => []

];