<?php
	require_once ('thirds/php-jwt-master/src/JWT.php');

    
    //write your configurations :D
    $configargs = array(
      'private_key_bits'=> 2048,
    );

    // Create the keypair
    $res=openssl_pkey_new($configargs);
	openssl_pkey_export($res, $privkey, "PassPhrase number 1" );
	
    printf($privkey);

    echo '<br /><br />';

    $publicKey=openssl_pkey_get_details($res)['key'];

    printf($publicKey);

    use \Firebase\JWT\JWT;

    $key = "example_key";
    $payload = array(
        "iss" => "http://example.org",
        "aud" => "http://example.com",
        "iat" => 1356999524,
        "nbf" => 1357000000
    );

    /**
     * IMPORTANT:
     * You must specify supported algorithms for your application. See
     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
     * for a list of spec-compliant algorithms.
     */
    $jwt = JWT::encode($payload, $key);
    $decoded = JWT::decode($jwt, $key, array('HS256'));

    //print_r($jwt);


?>