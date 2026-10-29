<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Client;

use function PHPUnit\Framework\isNull;

/**
 * Usuarios Controller
 *
 * @property \App\Model\Table\UsuariosTable $Usuarios
 */
class UsuariosController extends AppController
{

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'add', 'loginSie']);
    }

    public function login()
    {
        $this->Authorization->skipAuthorization();
        $username = $this->request->getData('username');
        $password = $this->request->getData('password');
        if ($this->request->is('post')) {
            $http = new Client();
            $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/auth/login', [
                'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                'username' => $username,
                'password' => $password,
                'is_valid' => true,
            ]);
            if ($response->isOk()) {
                $json = (object) $response->getJson();

                if ($json->success) {

                    $user = $this->Usuarios->newEntity($json->data);


                    if ($user->isAdministrador || $user->isDev) {
                        $http = new Client();
                        $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/systems/workgroup', [
                            'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                            'id' => 11
                        ]);

                        if ($response->isOk()) {
                            $json = (object) $response->getJson();
                            if ($json->success) $user->people = $json->data['people'];
                        }
                        // dd($user);
                        $http = new Client();
                        $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/systems/get/register/places', [
                            'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                            'category_id' => 7,
                            'type' => 'list',
                            'limit' => 100
                        ]);
                        if ($response->isOk()) {
                            $json = (object) $response->getJson();
                            if ($json->success) $user->places = $json->data;
                        }
                    }
                    $this->Authentication->setIdentity($user);
                    if ($user->isGestor) {
                        // $http = new Client();
                        // $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/systems/workgroup', [
                        //     'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                        //     'id' => 6
                        // ]);
                        // if ($response->isOk()) {
                        //     $json = (object) $response->getJson();
                        //     if ($json->success) $user->places = $json->data['places'];
                        // }
                        
                        // debug($user);
                        // dd($user->places_workgroups);
                        // foreach ($user->places_workgroups as $escolaId => $workgroupId) {

                        //     if ((int)$workgroupId == 6) {
                        //         $escola_ids[] = (int)$escolaId;
                        //     }
                        // }

                        $escolasIds = array_keys(
                            array_filter(
                                $user->places_workgroups,
                                fn($workgroupId) => (int)$workgroupId === 6
                            )
                        );

                        // dd($escolasIds);

                        // if ($user->places_workgroups == 6){
                        //     $escola_ids = array_keys($user->places);
                        // }

                        // if (count($user->places) == 1) {
                        if (count($escolasIds) == 1) {
                            $escola_ids = array_keys($user->places);
                            $escola_id = reset($escola_ids);
                            return $this->redirect(['controller' => 'Relatorios', 'action' => 'dash_escolas', $escola_id]);
                        } else {
                            return $this->redirect(['controller' => 'Relatorios', 'action' => 'dash_supervisor']);
                        }
                    } elseif ($user->isAdministrador) {
                        return $this->redirect(['controller' => 'Relatorios', 'action' => 'dash_subsecretaria']);
                    } else {
                        return $this->redirect(['controller' => 'Relatorios', 'action' => 'dash_supervisor']);
                    }
                } else {
                    $this->Flash->error($json->message);
                }
            } else {
                $this->Flash->error($response->getReasonPhrase());
            }
        }
    }

    public function logout()
    {

        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {

            $this->Authorization->skipAuthorization();

            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Usuarios', 'action' => 'login']);
        }
    }
}
