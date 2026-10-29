<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Funcoes Model
 *
 * @method \App\Model\Entity\Funco newEmptyEntity()
 * @method \App\Model\Entity\Funco newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Funco> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Funco get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Funco findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Funco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Funco> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Funco|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Funco saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Funco>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Funco>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Funco>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Funco> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Funco>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Funco>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Funco>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Funco> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FuncoesTable extends Table
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

        $this->setTable('funcoes');
        $this->setDisplayField('nm_funcao');

        $this->addBehavior('Timestamp');
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
            ->integer('id')
            ->allowEmptyString('id');

        $validator
            ->scalar('nm_funcao')
            ->maxLength('nm_funcao', 50)
            ->allowEmptyString('nm_funcao');

        return $validator;
    }
}
