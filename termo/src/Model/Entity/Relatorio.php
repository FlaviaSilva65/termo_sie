<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Http\Client;
use Cake\ORM\Entity;

/**
 * Relatorio Entity
 *
 * @property int $id
 * @property int $usuario_id
 * @property string $nm_atendido
 * @property int $funcoes_id
 * @property int $unid_escolar_id
 * @property \Cake\I18n\Date $data
 * @property int $ic_acompanhado
 * @property string|null $ds_acompanhado
 * @property string|null $ds_organizacao
 * @property string|null $ds_higiene
 * @property string|null $ds_manutencao
 * @property string|null $ds_legislacao_documentos
 * @property string|null $ds_obs_geral
 * @property int|null $ic_prioridade
 * @property int|null $ic_responsavel
 * @property int|null $responsavel_id
 * @property int|null $ic_rascunho
 * @property int|null $id_ass_super
 * @property int|null $id_ass_dir
 * @property int|null $id_ass_sub
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Usuario $usuario
 * @property \App\Model\Entity\Funco $funco
 * @property \App\Model\Entity\OcorrenciaRelatorio[] $ocorrencia_relatorios
 */
class Relatorio extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        '*' => true
    ];

    protected function _getSituacao()
    {
        if ($this->ic_rascunho == 1) {
            return 1;
        }

        if (
            !empty($this->id_ass_super) &&
            !empty($this->id_ass_dir) &&
            !empty($this->id_ass_sub) &&
            !empty($this->id_ass_assis)
        ) {
            return 2;
        }

        return 3;
    }

    protected function _getSupervisor()
    {
        $supervisor = $this->getPessoa($this->person_id);

        return $supervisor;
    }

    protected function _getDiretor()
    {
        $diretor = $this->getPessoa($this->id_ass_dir);

        // dd($diretor);

        return $diretor;
    }

    protected function _getAssistente()
    {
        $assistente = $this->getPessoa($this->id_ass_assis);

        return $assistente;
    }

    protected function _getAdministrador()
    {
        $administrador = $this->getPessoa($this->id_ass_sub);

        return $administrador;
    }



    protected function getPessoa($id)
    {
        if ($id) {

            $http = new Client();
            $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/systems/get/register/people/' . $id, [
                'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                'contain' => 'Employees.JobTitles',

            ]);
            if ($response->isOk()) {
                $json = (object) $response->getJson();
                if ($json->success) return $json->data;
            }
        }
    }

    protected function _getGestores()
    {
        return $this->getGestores($this->place_id);
    }

    protected function getGestores($place_id)
    {
        if ($place_id) {

            $http = new Client();
            $response = $http->get('https://seduc.cidadaopg.sp.gov.br/sie/api/systems/get/systems/workgroup-policies/', [
                'api' => '1fd7dfc899e51af4332b8fc9c7eeda5401ac1d6b2a8db37b5359814d1262e21765c0ea283b17f',
                'place_id' => $place_id,
                'contain' => 'People',
                'workgroup_id' => 6

            ]);
            if ($response->isOk()) {
                $json = (object) $response->getJson();
                if ($json->success) return array_combine(
                    array_map(fn($workgroup) => $workgroup['person']['id'], $json->data),
                    array_map(fn($workgroup) => $workgroup['person']['name'], $json->data)
                );
            }
        }
    }
}
