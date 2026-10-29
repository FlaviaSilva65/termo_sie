<?php

namespace App\Policy;

use App\Model\Entity\Usuario;
use Authorization\IdentityInterface;

class UsuarioPolicy
{
    public function canEdit(IdentityInterface $identity, Usuario $usuario)
    {
        // return $identity->id == $usuario->id;
        $usuario = $identity->getOriginalData();

        $admin = ($usuario->isDev);

        if ($admin) return true;
    }

    public function canBuscaunids(IdentityInterface $identity, Usuario $usuario)
    {
        $usuario = $identity->getOriginalData();

        $admin = ($usuario->isDev);

        if ($admin) return true;
    }

    public function  canCarregarRf(IdentityInterface $identity, Usuario $usuario)
    {
        $usuario = $identity->getOriginalData();

        $admin = ($usuario->isDev);

        if ($admin) return true;
    }

    public function canAssumir(
        IdentityInterface $identity,
        Usuario $usuario
    ): bool {
        $usuarioLogado = $identity->getOriginalData();

        if(!$usuarioLogado){
            return false;
        }

        if (!$usuarioLogado->tp_usuario){
            return false;
        }
        return (bool)$usuarioLogado->tp_usuario->ic_master;
    }
}
