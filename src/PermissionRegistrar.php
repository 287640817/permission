<?php
namespace Zhulei\Permission;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Collection;
use Zhulei\Permission\Contracts\Role;
use Illuminate\Contracts\Auth\Access\Gate;
use Zhulei\Permission\Contracts\Permission;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Zhulei\Permission\Exceptions\PermissionDoesNotExist;

class PermissionRegistrar{
    protected $gate;

    protected $cacheManager;
    protected $permissionClass;
    protected $roleClass;
    protected $cache;
    protected $permissions;

    public static $cacheExpirationTime;
    public static $cacheKey;
    public static $cacheModelKey;

    public function __construct(Gate $gate, CacheManager $cacheManager)
    {
        $this->gate = $gate;
        $this->cacheManager = $cacheManager;

        $this->permissionClass = config('permission.models.permission');
        $this->roleClass = config('permission.models.role');

        $this->initializeCache();
    }

    protected function initializeCache(){
        self::$cacheExpirationTime = config('permission.cache.expiration_time', config('permission.cache_expiration_time'));

        if(app()->version() <= '5.5'){
            if (self::$cacheExpirationTime instanceof \DateInterval) {
                $interval = self::$cacheExpirationTime;
                self::$cacheExpirationTime = $interval->m * 30 * 60 * 24 + $interval->d * 60 * 24 + $interval->h * 60 + $interval->i;
            }
        }
        self::$cacheKey = config('permission.cache.key');
        self::$cacheModelKey = config('permission.cache.model_key');

        $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig():  \Illuminate\Contracts\Cache\Repository
    {
        $cacheDriver = config('permission.cache.store', 'default');

        if($cacheDriver === 'default'){
            return $this->cacheManager->store();
        }

        if(! \array_key_exists($cacheDriver, config('cache.stores'))){
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    public function registerPermissions(): bool
    {
        $this->gate->before(function (Authorizable $user, string $ability) {
            try {
                if (method_exists($user, 'hasPermissionTo')) {
                    return $user->hasPermissionTo($ability) ?: null;
                }
            } catch (PermissionDoesNotExist $e) {
            }
        });
        return true;
    }

    public function forgetCachedPermissions(){
        $this->permissions = null;
        $this->cache->forget(self::$cacheKey);
    }

    public function getPermissions(array $params = []): Collection{
        if($this->permissions == null){
            $this->permissions = $this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function(){
                return $this->getPermissionClass()
                    ->with('roles')
                    ->get();
            });
        }
        $permissions = clone $this->permissions;
        foreach ($params as $attr  => $value){
            $permissions = $permissions->where($attr, $value);
        }
        return $permissions;
    }

    public function getPermissionClass(): Permission{
        return app($this->permissionClass);
    }

    public function getRoleClass(): Role{
        return app($this->roleClass);
    }

    public function getCacheStore(): Store{
        return $this->cache->getStore();
    }
}