<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Providencia;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Authorization\Exception\ForbiddenException;

use function PHPUnit\Framework\isNull;
use function React\Promise\all;

/**
 * Providencias Controller
 *
 * @property \App\Model\Table\ProvidenciasTable $Providencias
 */
class ProvidenciasController extends AppController
{
    public function index($escola_id)
    {
        $unid_escolares = TableRegistry::getTableLocator()->get('UnidEscolares');
        $escola = $unid_escolares->get($escola_id);
        $identity = $this->Authentication->getIdentity();

        $this->Authorization->skipAuthorization();

        // $this->Authorization->authorize($escola, 'index');
        $ano = date('Y');

        $providencias = $this->Providencias->find('all')
            ->where(['Providencias.unid_escolar_id' => $escola->id, 'Providencias.status' => 0])
            ->contain(['UnidEscolares', 'Relatorios', 'Perguntas', 'Respostas']);

        $this->set(compact('providencias', 'escola'));
    }

    public function salvarProvidencias()
    {
        // $this->authorize('update', $providencia);

        $this->Authorization->skipAuthorization();
        $identity = $this->Authentication->getIdentity();

        if ($this->request->is(['post', 'patch', 'put'])) {

            $ids = array_keys($this->request->getData('status', []));

            if (!empty($ids)) {

                $providencia = $this->Providencias->find()
                    ->select(['id', 'unid_escolar_id'])
                    ->where(['id IN' => $ids])
                    ->first();

                $escolaId = $providencia->unid_escolar_id;

                $this->Providencias
                    ->updateQuery()
                    ->set([
                        'status' => true,
                        'usuario_id' => $identity->id
                    ])
                    ->where([
                        'id IN' => $ids
                    ])
                    ->execute();

                $this->Flash->success('Providencias atualizadas com sucesso.');

                return $this->redirect(['action' => 'index', $escolaId]);
            } else {
                $this->Flash->error('Selecione pelo menos uma providência.');
                return $this->redirect($this->referer());
            }
        }
    }
}
