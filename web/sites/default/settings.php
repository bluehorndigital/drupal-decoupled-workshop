<?php declare(strict_types=1);

$settings['config_sync_directory'] = '../config';
$settings['update_free_access'] = FALSE;
$settings['class_loader_auto_detect'] = FALSE;
$settings['allow_authorize_operations'] = FALSE;
$settings['file_chmod_directory'] = 0775;
$settings['file_chmod_file'] = 0664;
$settings['file_private_path'] = '../private';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['entity_update_batch_size'] = 50;
$settings['entity_update_backup'] = TRUE;

// Include dev settings.
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$config['system.logging']['error_level'] = 'verbose';
$settings['skip_permissions_hardening'] = TRUE;

$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
$settings['cache']['bins']['jsonapi_normalizations']= 'cache.backend.null';


$settings['hash_salt'] = '384SXF14RJjfKzgXfwXW-2ls5gbpKtgNIzMWQAS3tpSScMs-ThV4kJoCfCATQDcIT7X8r0H_RA';

// Include settings for Lando.
$lando_settings = __DIR__ . '/settings.lando.php';
if (is_readable($lando_settings) && getenv('LANDO') === 'ON') {
  require $lando_settings;
}

// Automatically generated include for settings managed by ddev.
$ddev_settings = __DIR__ . '/settings.ddev.php';
if (is_readable($ddev_settings) && getenv('IS_DDEV_PROJECT') === 'true') {
  require $ddev_settings;
}

if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
