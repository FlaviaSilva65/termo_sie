<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Perguntas Model
 *
 * @property \App\Model\Table\CategoriasTable&\Cake\ORM\Association\BelongsTo $Categorias
 * @property \App\Model\Table\RespostasTable&\Cake\ORM\Association\HasMany $Respostas
 *
 * @method \App\Model\Entity\Pergunta newEmptyEntity()
 * @method \App\Model\Entity\Pergunta newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Pergunta> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pergunta get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Pergunta findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Pergunta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Pergunta> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pergunta|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Pergunta saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Pergunta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pergunta>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pergunta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pergunta> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pergunta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pergunta>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pergunta>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pergunta> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PerguntasTable extends Table
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

        // $this->setTable('perguntas');
        $this->setTable('perguntas_of'); // Adotando o novo Layout fracionado em Dimensões
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Categorias', [
            'foreignKey' => 'categoria_id',
        ]);
        $this->hasMany('Respostas', [
            'foreignKey' => 'pergunta_id',
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
            ->integer('codigo')
            ->notEmptyString('codigo');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('tipo')
            ->allowEmptyString('tipo');

        $validator
            ->allowEmptyString('opcoes');

        $validator
            ->integer('categoria_id')
            ->allowEmptyString('categoria_id');

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
        $rules->add($rules->existsIn(['categoria_id'], 'Categorias'), ['errorField' => 'categoria_id']);

        return $rules;
    }
}
