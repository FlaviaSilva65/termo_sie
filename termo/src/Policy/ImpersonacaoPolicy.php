<?php

namespace App\Policy;

use App\Model\Entity\Usuario;
use Authorization\IdentityInterface;


class ImpersonacaoPolicy
{
    /**
     * Verifica se o usuário pode iniciar uma impersonação
     */
    public function canAssumir(
        IdentityInterface $identity,
        Usuario $usuario
    ): bool {

        // Usuário atual precisa ser MASTER
        if (!$identity->tp_usuario) {
            return false;
        }

        return (bool)$identity->getOriginalData()
            ->tp_usuario
            ?->ic_master;
    }

    public function canPararImpersonacao(
        IdentityInterface $identity,
        Usuario $usuario
    ): bool {
        if (!$identity->tp_usuario) {
            return false;
        }

        return (bool)$identity->tp_usuario->ic_master;
    }
}
