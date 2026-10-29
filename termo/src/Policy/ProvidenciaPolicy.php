<?php

namespace App\Policy;

use App\Model\Entity\Providencia;
use App\Model\Entity\UnidEscolare;
use Authentication\Identity;
use Authentication\IdentityInterface as AuthenticationIdentityInterface;
use Authorization\IdentityInterface;

class ProvidenciaPolicy
{
    public function canIndex(IdentityInterface $user, Providencia $providencia, UnidEscolare $unid_escolare)
    {
        return true;
    }

    // public function canSalvarProvidencias(IdentityInterface $user, Providencia $providencia)
    // {
    //     return true;
    // }

    public function update(IdentityInterface $user, Providencia $providencia): bool
    {
        // Exemplo 1: Permite se o usuário for Administrador
        // if ($user->hasRole('admin')) {
            return true;
        // }

        // Exemplo 2: Permite apenas se o usuário pertencer à mesma escola do relatório
        return $user->escola_id === $providencia->unid_escolare_id;
    }
}
