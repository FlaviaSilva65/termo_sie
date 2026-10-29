<?php

declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Cake\ORM\Query\SelectQuery;
use Authorization\Policy\ResultInterface;

/**
 * UnidEscolares policy
 */
class UnidEscolaresTablePolicy
{
    /**
     * Apply user access controls to a query for index actions
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \Cake\ORM\Query\SelectQuery $query The query to apply authorization conditions to.
     * @return bool
     */
    public function beforeScope(?IdentityInterface $identity, mixed $resource, string $action): ResultInterface|bool|null
    {
        if ($identity && ($identity->isDev)) {
            return  true;
        } else {
            return null;
        }
    }
    public function scopeIndex(IdentityInterface $identity, SelectQuery $query): SelectQuery
    {
        if ($identity && ($identity->isSupervisor)) {
            return $query;
        } else {
            $usuarioUnidEscolares = $query->getRepository()->UsuarioUnidEscolares->find()->select('unid_escolares_id')
                ->where(['usuario_id' => $identity->id]);

            return $query->where(['UnidEscolares.id IN' => $usuarioUnidEscolares, 'UnidEscolares.ativo' => 1]);
        }
    }
}
