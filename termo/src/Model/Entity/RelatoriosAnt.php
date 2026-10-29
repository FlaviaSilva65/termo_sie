<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RelatoriosAnt Entity
 *
 * @property int|null $id
 * @property string|null $ds_organizacao
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $cd_usuario
 * @property int|null $cd_unidade
 * @property string|null $ds_atividade
 * @property string|null $cd_assinatura_supervisor
 * @property string|null $cd_assinatura_diretor
 * @property string|null $cd_assinatura_admin
 * @property int|null $re_usuario
 * @property string|null $ds_alimentacao
 * @property string|null $ds_manutencao
 * @property string|null $ds_capacidade
 * @property string|null $ds_legislacao
 * @property string|null $ds_ouvidoria
 * @property string|null $ds_rh
 * @property string|null $ds_patrimonio
 * @property string|null $ds_obs
 * @property string|null $nm_recibo
 * @property string|null $nm_cargo_recibo
 * @property int|null $frequencia_id
 * @property int|null $cd_termo
 * @property string|null $dt_ass_diretor
 * @property string|null $dt_ass_adm
 * @property string|null $ic_fundamental
 * @property string|null $dt_relatorio
 * @property string|null $obs_ass
 * @property bool|null $ic_urgente
 * @property int|null $ic_subs
 */
class RelatoriosAnt extends Entity
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
        'id' => true,
        'ds_organizacao' => true,
        'created' => true,
        'modified' => true,
        'cd_usuario' => true,
        'cd_unidade' => true,
        'ds_atividade' => true,
        'cd_assinatura_supervisor' => true,
        'cd_assinatura_diretor' => true,
        'cd_assinatura_admin' => true,
        're_usuario' => true,
        'ds_alimentacao' => true,
        'ds_manutencao' => true,
        'ds_capacidade' => true,
        'ds_legislacao' => true,
        'ds_ouvidoria' => true,
        'ds_rh' => true,
        'ds_patrimonio' => true,
        'ds_obs' => true,
        'nm_recibo' => true,
        'nm_cargo_recibo' => true,
        'frequencia_id' => true,
        'cd_termo' => true,
        'dt_ass_diretor' => true,
        'dt_ass_adm' => true,
        'ic_fundamental' => true,
        'dt_relatorio' => true,
        'obs_ass' => true,
        'ic_urgente' => true,
        'ic_subs' => true,
    ];
}
