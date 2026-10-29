<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Respostas Model
 *
 * @property \App\Model\Table\RelatoriosTable&\Cake\ORM\Association\BelongsTo $Relatorios
 * @property \App\Model\Table\PerguntasTable&\Cake\ORM\Association\BelongsTo $Perguntas
 *
 * @method \App\Model\Entity\Resposta newEmptyEntity()
 * @method \App\Model\Entity\Resposta newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Resposta> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resposta get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Resposta findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Resposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Resposta> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resposta|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Resposta saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Resposta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Resposta>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Resposta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Resposta> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Resposta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Resposta>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Resposta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Resposta> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RespostasTable extends Table
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

        $this->setTable('respostas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Relatorios', [
            'foreignKey' => 'relatorio_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Perguntas', [
            'foreignKey' => 'pergunta_id',
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
            ->integer('pergunta_id')
            ->notEmptyString('pergunta_id');

        $validator
            ->scalar('resposta')
            ->maxLength('resposta', 10)
            ->allowEmptyString('resposta');

        // $validator
        //     ->requirePresence('resposta', 'create')
        //     ->notEmptyString('resposta', 'Campo obrigatório');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        $validator
            ->allowEmptyString('status');

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

        return $rules;
    }
}
