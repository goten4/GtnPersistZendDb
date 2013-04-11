<?php
/**
 * This file is placed here for compatibility with Zendframework 2's ModuleManager.
 * It allows usage of this module even without composer.
 * The original Module.php is in 'src/ZfPersistenceZendDb' in order to respect PSR-0
 */
define('MODULE_BASE_DIR', __DIR__);
require_once __DIR__ . '/src/ZfPersistenceZendDb/Module.php';