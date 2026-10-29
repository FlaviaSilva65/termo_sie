<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dimensao Entity
 *
 * @property int $id
 * @property string $titCompleto
 * @property string $titParcial
 * @property int $pergunta_id
 * @property int|null $resposta_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Pergunta $pergunta
 * @property \App\Model\Entity\Resposta $resposta
 */
class Dimensao extends Entity
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
        'titCompleto' => true,
        'titParcial' => true,
        'pergunta_id' => true,
        'resposta_id' => true,
        'created' => true,
        'modified' => true,
        'pergunta' => true,
        'resposta' => true,
    ];
}
