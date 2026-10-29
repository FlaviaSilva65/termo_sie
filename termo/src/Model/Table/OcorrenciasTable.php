<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ocorrencias Model
 *
 * @property \App\Model\Table\CategoriasTable&\Cake\ORM\Association\BelongsTo $Categorias
 * @property \App\Model\Table\OcorrenciaRelatoriosTable&\Cake\ORM\Association\HasMany $OcorrenciaRelatorios
 *
 * @method \App\Model\Entity\Ocorrencia newEmptyEntity()
 * @method \App\Model\Entity\Ocorrencia newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Ocorrencia> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ocorrencia get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Ocorrencia findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Ocorrencia patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Ocorrencia> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ocorrencia|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Ocorrencia saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Ocorrencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ocorrencia>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ocorrencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ocorrencia> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ocorrencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ocorrencia>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Ocorrencia>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Ocorrencia> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OcorrenciasTable extends Table
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

        $this->setTable('ocorrencias_of');
        $this->setDisplayField('nm_tp_ocorrencia');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Categorias', [
            'foreignKey' => 'categorias_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('OcorrenciaRelatorios', [
            'foreignKey' => 'ocorrencia_id',
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
            ->scalar('nm_tp_ocorrencia')
            ->maxLength('nm_tp_ocorrencia', 50)
            ->requirePresence('nm_tp_ocorrencia', 'create')
            ->notEmptyString('nm_tp_ocorrencia');

        $validator
            ->integer('categorias_id')
            ->notEmptyString('categorias_id');

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
        $rules->add($rules->existsIn(['categorias_id'], 'Categorias'), ['errorField' => 'categorias_id']);

        return $rules;
    }
}
