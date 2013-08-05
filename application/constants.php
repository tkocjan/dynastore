<?php
define('ENABLE_INI_CACHE', false);
define('ENABLE_DB_PROFILER', false);
define('ENABLE_PLUGIN_CACHE', false);
define('ENABLE_DB_CACHES', false);

define('TEMP_PATH', realpath(APPLICATION_PATH.'/../data'));
define('SESSION_PATH', TEMP_PATH.'/session');
define('CACHE_PATH', TEMP_PATH.'/cache');

define('CACHE_INI_PATH', CACHE_PATH.'/ini');

define('LOG_PATH', ini_get('error_log'));

define('CACHE_META_PATH', CACHE_PATH.'/meta');
define('CACHE_DB_PATH', CACHE_PATH.'/db');
define('CACHE_PLUGIN_FILE', CACHE_PATH.'/PluginLoaderCache.php');

define('CATEGORY_REPOSITORY', 'Storefront_Model_Catalog_ZendDb_CategoryRepository');
define('PRODUCT_REPOSITORY', 'Storefront_Model_Catalog_ZendDb_ProductRepository');
define('PRODUCTIMAGE_REPOSITORY', 'Storefront_Model_Catalog_ZendDb_ProductImageRepository');
define('USER_REPOSITORY', 'Zstore\Domain\Doctrine\UserRepository');
define('CART_REPOSITORY', 'Storefront_Model_Cart_Session_CartRepository');
define('PAGE_REPOSITORY', 'Cms_Model_Page_ZendDb_PageRepository');

define('CATALOG_SERVICE', 'Zstore\Domain\Catalog\CatalogService');
define('USER_SERVICE', 'Zstore\Domain\User\UserService');
define('CART_SERVICE', 'Storefront_Model_Cart_CartService');
define('AUTH_SERVICE', 'SF_Service_Authentication');

define('PRODUCT_PAGE_SIZE', 5);
define('USER_PAGE_SIZE', 5);
