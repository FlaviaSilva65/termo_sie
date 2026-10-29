<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OcorrenciaRelatorio Entity
 *
 * @property int $id
 * @property int $relatorio_id
 * @property int $ocorrencia_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Relatorio $relatorio
 * @property \App\Model\Entity\Ocorrencia $ocorrencia
 */
class OcorrenciaRelatorio extends Entity
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
        'relatorio_id' => true,
        'ocorrencia_id' => true,
        'created' => true,
        'modified' => true,
        'relatorio' => true,
        'ocorrencia' => true,
    ];
}
