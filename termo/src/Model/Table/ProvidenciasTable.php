<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Providencias Model
 *
 * @property \App\Model\Table\RelatoriosTable&\Cake\ORM\Association\BelongsTo $Relatorios
 * @property \App\Model\Table\PerguntasTable&\Cake\ORM\Association\BelongsTo $Perguntas
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsTo $Respostas
 *
 * @method \App\Model\Entity\Providencia newEmptyEntity()
 * @method \App\Model\Entity\Providencia newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Providencia> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Providencia get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Providencia findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Providencia patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Providencia> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Providencia|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Providencia saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Providencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Providencia>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Providencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Providencia> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Providencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Providencia>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Providencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Providencia> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProvidenciasTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('providencias');
        $this->setDisplayField('descricao');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Relatorios', [
            'foreignKey' => 'relatorio_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('UnidEscolares', [
            'foreignKey' => 'unid_escolar_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Perguntas', [
            'foreignKey' => 'pergunta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Respostas', [
            'foreignKey' => 'resposta_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('relatorio_id')
            ->notEmptyString('relatorio_id');

        $validator
            ->integer('place_id')
            ->notEmptyString('place_id');

        $validator
            ->integer('pergunta_id')
            ->notEmptyString('pergunta_id');

        $validator
            ->integer('resposta_id')
            ->notEmptyString('resposta_id');

        $validator
            ->scalar('observacao')
            ->requirePresence('observacao', 'create')
            ->notEmptyString('observacao');

        $validator
            ->integer('usuario_id')
            ->notEmptyString('usuario_id');

        $validator
            ->notEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['relatorio_id'], 'Relatorios'), ['errorField' => 'relatorio_id']);
        $rules->add($rules->existsIn(['pergunta_id'], 'Perguntas'), ['errorField' => 'pergunta_id']);
        $rules->add($rules->existsIn(['resposta_id'], 'Respostas'), ['errorField' => 'resposta_id']);

        return $rules;
    }
}
