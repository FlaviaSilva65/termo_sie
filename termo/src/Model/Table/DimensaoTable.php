<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dimensao Model
 *
 * @property \App\Model\Table\PerguntasTable&\Cake\ORM\Association\BelongsTo $Perguntas
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\BelongsTo $Respostas
 *
 * @method \App\Model\Entity\Dimensao newEmptyEntity()
 * @method \App\Model\Entity\Dimensao newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Dimensao> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dimensao get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Dimensao findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Dimensao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Dimensao> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dimensao|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Dimensao saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Dimensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Dimensao>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Dimensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Dimensao> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Dimensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Dimensao>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Dimensao>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Dimensao> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DimensaoTable extends Table
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

        $this->setTable('dimensao');
        $this->setDisplayField('titCompleto');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Perguntas', [
            'foreignKey' => 'pergunta_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Respostas', [
            'foreignKey' => 'resposta_id',
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
            ->scalar('titCompleto')
            ->maxLength('titCompleto', 100)
            ->requirePresence('titCompleto', 'create')
            ->notEmptyString('titCompleto');

        $validator
            ->scalar('titParcial')
            ->maxLength('titParcial', 100)
            ->requirePresence('titParcial', 'create')
            ->notEmptyString('titParcial');

        $validator
            ->integer('pergunta_id')
            ->notEmptyString('pergunta_id');

        $validator
            ->integer('resposta_id')
            ->allowEmptyString('resposta_id');

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
        $rules->add($rules->existsIn(['pergunta_id'], 'Perguntas'), ['errorField' => 'pergunta_id']);
        $rules->add($rules->existsIn(['resposta_id'], 'Respostas'), ['errorField' => 'resposta_id']);

        return $rules;
    }
}
