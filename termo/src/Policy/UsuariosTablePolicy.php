<?php

namespace App\Policy;

use App\Model\Entity\Usuario;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforeScopeInterface;
use Authorization\Policy\ResultInterface;
// use App\Model\Entity\Usuario;
// use Authentication\IdentityInterface as AuthenticationIdentityInterface;


class UsuariosTablePolicy implements BeforeScopeInterface
{
    public function beforeScope(?IdentityInterface $identity, mixed $resource, string $action): ResultInterface|bool|null
    {
        if ($identity?->isDev) {
            return  true;
        } else {
            $resource->where('0 = 1');
        }
        return null;
    }

    public function scopeIndex($identity, $resource)
    {
        return $resource;
    }
}
