<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Providencia Entity
 *
 * @property int $id
 * @property int $relatorio_id
 * @property int $pergunta_id
 * @property int $resposta_id
 * @property string $descricao
 * @property int $status
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Relatorio $relatorio
 * @property \App\Model\Entity\Pergunta $pergunta
 * @property \App\Model\Entity\Resposta $resposta
 */
class Providencia extends Entity
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
        'relatorio_id' => true,
        'place_id' => true,
        'pergunta_id' => true,
        'resposta_id' => true,
        'observacao' => true,
        'status' => true,
        'usuario_id' => true,
        'resolvido_em' => true,
        'created' => true,
        'modified' => true,
        'relatorio' => true,
        'unidEscolare' => true,
        'pergunta' => true,
        'resposta' => true,
    ];
}
