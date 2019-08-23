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
if (empty($_SERVER['HOME'])) {
    die('Unable to find SERVER[HOME]');
}
define('DDOJO_CLIENT_CONFIG_DIR', $_SERVER['HOME'] . '/.config/ddojo/');

if (DDOJO_DEV) {
    $config = ['display_id' => 'display_test_001'];
} else {
    $config_file = DDOJO_CLIENT_CONFIG_DIR . 'client.json';
    if (!file_exists($config_file)) {
        die('fail - missing client config ' . $config_file);
    }

    $config = json_decode(file_get_contents($config_file), true);
    if (!is_array($config)) {
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

$client_dir = dirname(__FILE__);
chdir($client_dir);
$downloads_dir = $client_dir . '/downloads';
if (!file_exists($downloads_dir)) mkdir($downloads_dir);
$background_file = $downloads_dir . '/bg.jpg';
if (!file_exists($background_file)) {
    $get = _get_remote_file($config_check['background_image'], $background_file);
    if ($get) {
        print sprintf('Downloaded background file to %s, setting background', $background_file) . PHP_EOL;
        _set_background($background_file);
    }
} else {
    $sha_local = sha1_file($background_file);
    if ($config_check['background_sha1'] == $sha_local) {
        print 'Background file has not changed.' . PHP_EOL;
    } else {
        print 'Background file has changed, downloading new background' . PHP_EOL;
        $get = _get_remote_file($config_check['background_image'], $background_file);
        if ($get) {
            print sprintf('Downloaded NEW background file to %s, setting background', $background_file) . PHP_EOL;
            _set_background($background_file);
        }
    }
}

if (file_exists('launch.local.sh')) {
  exec('./launch.local.sh ' . $config_check['display_url']);
} else {
  exec('./launch.sh ' . $config_check['display_url']);
}

function _get_remote_file($url, $local_file) {
    $status = file_get_contents($url);
    if ($status !== false) {
        $write = file_put_contents($local_file, $status);
        if ($write !== false) {
            return true;
        }
    }
    return false;
}

function _set_background($local_file) {
}