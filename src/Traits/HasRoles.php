<?php
namespace Zhulei\Permission\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasRoles{

    public function hasRole($roles): bool
    {
        //如果存在管道符的情况下执行
        if(is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        if(is_string($roles)) {

        }
    }

    protected function convertPipeToArray(string $pipeString) {
        $pipeString = trim($pipeString);
        if(strlen($pipeString) <= 2) {
            return $pipeString;
        }
        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);
        if($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }
        if(! in_array($quoteCharacter, ["'", ''])) {
            return explode('|', $pipeString);
        }
        return explode('|', trim($pipeString, $quoteCharacter));
    }

    public function roles(): MorphToMany
    {

    }
}