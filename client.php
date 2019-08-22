<?php
if (file_exists('.dev')) {
    define('DDOJO_DEV', 1);
} else {
    define('DDOJO_DEV', 0);
}
define('DDOJO_CLIENT_VERSION', '1');
if (DDOJO_DEV) {
    define('DDOJO_BASE_URL', 'http://localhost:8000/client/v' . DDOJO_CLIENT_VERSION . '/');
} else {
    define('DDOJO_BASE_URL', 'https://www.displaydojo.com/client/v' . DDOJO_CLIENT_VERSION . '/');
}
define('DDOJO_CLIENT_CONFIG_DIR', '/etc/ddojo/');

if (DDOJO_DEV) {
    $config = ['display_id' => 'display_test_001'];
} else {
    $config_file = DDOJO_CLIENT_CONFIG_DIR . 'client.json';
    if (!file_exists($config_file)) {
        die('fail - missing client config ' . $config_file);
    }

    $config = json_decode(file_get_contents($config_file), true);
    if (!issary($config)) {
        die('invalid config file format');
    }
}

if (empty($config['display_id'])) {
    die('missing display_id from config file');
}

$config_check = json_decode(file_get_contents(DDOJO_BASE_URL . 'config/' . $config['display_id']), true);
if ($config_check === false || !is_array($config_check)) {
    die('invalid config config_check file format');
}

$expected_keys = ['display_id', 'background_image', 'display_url'];
foreach ($expected_keys as $key) {
    if (empty($config_check[$key])) {
        die('missing config check key = ' . $key);
    }
}

chdir(dirname(__FILE__));

if (file_exists('launch_custom.sh')) {
  exec('./launch.local.sh ' . $config_check['display_url']);
} else {
  exec('./launch.sh ' . $config_check['display_url']);
}
