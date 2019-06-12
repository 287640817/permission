<?php
namespace Zhulei\Permission;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Routing\Route;

class PermissionServiceProvider extends ServiceProvider{
    public function register()
    {
        if(isNotLumen()) {
            $this->mergeConfigFrom(__DIR__.'/../config/permission.php', 'permission');
        }
        $this->registerBladeExtensions();
    }

    public function boot() {

    }

    public function registerBladeExtensions(){
        $this->app->afterResolving('blade.compiler', function(BladeCompiler $bladeCompiler){
            $bladeCompiler->directive('role', function($arguments){
                list($role, $guard) = explode(',', $arguments. ',');
                return "<?php if( auth({$guard})->check() && auth({$guard})->user()->hasRole({$role}) ): ?>";
            });
            $bladeCompiler->directive('elserole', function($arguments){
                list($role, $guard) = explode(',', $arguments. ',');
                return "<?php elseif( auth({$guard})->check() && auth({$guard})->user()->hasRole({$role}) ): ?>";
            });
            $bladeCompiler->directive('endrole', function(){
                return "<?php endif; ?>";
            });

            $bladeCompiler->directive('hasrole', function($arguments){
                list($role, $guard) = explode(',', $arguments. ',');
                return "<?php if( auth({$guard})->check() && auth({$guard})->user()->hasRole({$role}) ): ?>";
            });
            $bladeCompiler->directive('endhasrole', function(){
                return "<?php endif; ?>";
            });

            $bladeCompiler->directive('hasanyrole', function($arguments){
                list($roles, $guard) = explode(',', $arguments. ',');
                return "<?php if( auth({$guard})->check() && auth({$guard})->user()->hasAnyRole({$roles}) ); ?>";
            });

            $bladeCompiler->directive('endhasanyrole', function(){
                return "<?php endif; ?>";
            });

            $bladeCompiler->directive('hasallroles', function($arguments){
                list($roles, $guard) = explode(',', $arguments. ',');
                return "<?php if( auth({$guard})->check() && auth({$guard})->user()->hasAllRoles({$roles}) ); ?>";
            });

            $bladeCompiler->directive('endhasallroles', function(){
                return "<?php endif; ?>";
            });

            $bladeCompiler->directive('unlessrole', function($arguments){
                list($role, $guard) = explode(',', $arguments.',');
                return "<?php if( ! auth({$guard})->check() || ! auth({$guard})->user()->hasRole({$role}) ); ?>";
            });

            $bladeCompiler->directive('endunlessrole', function(){
                return "<?php endif; ?>";
            });
        });
    }

}
