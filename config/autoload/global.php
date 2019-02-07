<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$db = [];
if (getenv('VCAP_SERVICES')) {
    $vcapServices = \Zend\Json\Json::decode($_ENV['VCAP_SERVICES'], \Zend\Json\Json::TYPE_ARRAY);
    $clearDbCreds = $vcapServices['cleardb'][0]['credentials'];
    $dbConfig = [
        'driver'    => 'PdoMysql',
        'hostname'  => $clearDbCreds['hostname'],
        'database'  => $clearDbCreds['name'],
        'username'  => $clearDbCreds['username'],
        'password'  => $clearDbCreds['password'],
    ];
} elseif (getenv('CLEARDB_DATABASE_URL')) {
    $databaseUrlParts = parse_url($_ENV['CLEARDB_DATABASE_URL']);
    $dbConfig = [
        'driver'    => 'PdoMysql',
        'hostname'  => $databaseUrlParts['host'],
        'database'  => substr($databaseUrlParts['path'], 1),
        'username'  => $databaseUrlParts['user'],
        'password'  => $databaseUrlParts['pass'],
    ];
}

return array(
    'db' => $dbConfig,
    'scn-social-auth' => array(
        'facebook_client_id' => $_ENV['facebook_client_id'],
        'facebook_secret' => $_ENV['facebook_secret'],
        'google_client_id' => $_ENV['google_client_id'],
        'google_secret' => $_ENV['google_secret'],
        'linkedIn_client_id' => $_ENV['linkedIn_client_id'],
        'linkedIn_secret' => $_ENV['linkedIn_secret'],
        'twitter_consumer_key' => $_ENV['twitter_consumer_key'],
        'twitter_consumer_secret' => $_ENV['twitter_consumer_secret'],
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'invokables' => array(
            'Zend\Session\SessionManager' => 'Zend\Session\SessionManager',
        ),
    ),
);
