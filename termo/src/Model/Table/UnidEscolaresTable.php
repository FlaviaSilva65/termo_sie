<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UnidEscolares Model
 *
 * @method \App\Model\Entity\UnidEscolare newEmptyEntity()
 * @method \App\Model\Entity\UnidEscolare newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\UnidEscolare> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnidEscolare get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\UnidEscolare findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\UnidEscolare patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\UnidEscolare> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnidEscolare|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\UnidEscolare saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\UnidEscolare>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UnidEscolare>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UnidEscolare>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UnidEscolare> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UnidEscolare>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UnidEscolare>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\UnidEscolare>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\UnidEscolare> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnidEscolaresTable extends Table
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

        $this->setTable('unid_escolares');
        $this->setDisplayField('nm_unid_escolar');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Setores');
        
        $this->belongsTo('Escolas', [
            'foreignKey' => 'email',
            'bindingKey' => 'email'
        ]);

        // $this->hasOne('UsuarioUnidEscolares');
        $this->hasMany('UsuarioUnidEscolares', [
            'foreignKey' => 'unid_escolares_id'
        ]);
        $this->hasMany('Usuarios');
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
            ->scalar('nm_unid_escolar')
            ->maxLength('nm_unid_escolar', 100)
            ->requirePresence('nm_unid_escolar', 'create')
            ->notEmptyString('nm_unid_escolar');

        return $validator;
    }
}
