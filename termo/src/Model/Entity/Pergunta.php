<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pergunta Entity
 *
 * @property int $id
 * @property int $codigo
 * @property string|null $descricao
 * @property string|null $tipo
 * @property array|null $opcoes
 * @property int|null $categoria_id
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Categoria $categoria
 * @property \App\Model\Entity\Resposta[] $respostas
 */
class Pergunta extends Entity
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
        'codigo' => true,
        'descricao' => true,
        'tipo' => true,
        'opcoes' => true,
        'categoria_id' => true,
        'importancia' => true,
        'created' => true,
        'modified' => true,
        'categoria' => true,
        'respostas' => true,
    ];

    // protected function _getOpcoes($value)
    // {
    //     return json_decode($value, true);
    // }
}
