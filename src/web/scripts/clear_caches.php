<?php
// clear_caches.php
if (function_exists('opcache_reset')) {
  opcache_reset();
}
if (function_exists('apcu_clear_cache')) {
  apcu_clear_cache();
}
echo "Caches PHP vidés.";
