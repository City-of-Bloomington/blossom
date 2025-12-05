<?php
define('APPLICATION_NAME','blossom');

define('BASE_URI' , '/blossom');
define('BASE_HOST', 'localhost');
define('BASE_URL' , 'https://'.BASE_HOST.BASE_URI);
define('USWDS_URL', '/static/uswds/dist');

/**
 * Database Setup
 * Refer to the PDO documentation for DSN sytnax for your database type
 * http://www.php.net/manual/en/pdo.drivers.php
 */
$DATABASES = [
    'default' => [
        'dsn'  => 'sqlite:'.SITE_HOME.'/blossom_test.sq3',
        'user' => null,
        'pass' => null,
        'opts' => null
    ]
];

$LDAP = [
    'Employee' => [
        'classname'          => 'Site\Employee',
        'server'             => 'ldaps://ldap.example.org:636',
        'base_dn'            => 'DC=ldap,DC=example,DC=org',
        'username_attribute' => 'sAMAccountName',
        'user_binding'       => '{username}@bldap.example.org',
        'admin_binding'      => 'admin@ldap.example.org',
        'admin_pass'         => 'secret password'
    ]
];

$AUTHENTICATION = [
    'oidc' => [
        'server'         => 'https://example.org',
        'client_id'      => '',
        'client_secret'  => '',
        'claims' => [
            // Blossom field => OIDC Claim
            'username'    => 'preferred_username',
            'displayname' => 'commonname',
            'firstname'   => 'given_name',
            'lastname'    => 'family_name',
            'email'       => 'email',
            'groups'      => 'group',
            'groupmap'    => [
                // Blossom Role => OIDC Group
                'Administrator' => 'Blossom Administrators',
                'Supervisor'    => 'Blossom Supervisors',
                'Staff'         => 'Blossom Staff'
            ]
        ],
    ]
];

define('DATE_FORMAT', 'n/j/Y');
define('TIME_FORMAT', 'g:i a');
define('DATETIME_FORMAT', 'n/j/Y g:i a');

define('LOCALE', 'en_US');

define('DEFAULT_CITY',                'Bloomington');
define('DEFAULT_STATE',               'IN');
define('DEFAULT_TERM_END_WARNING',     60);
define('DEFAULT_APPLICATION_LIFETIME', 90);
define('ADMINISTRATOR_EMAIL', 'someone@example.org');
