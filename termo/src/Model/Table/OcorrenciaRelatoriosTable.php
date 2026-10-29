<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OcorrenciaRelatorios Model
 *
 * @property \App\Model\Table\RelatoriosTable&\Cake\ORM\Association\BelongsTo $Relatorios
 * @property \App\Model\Table\OcorrenciasTable&\Cake\ORM\Association\BelongsTo $Ocorrencias
 *
 * @method \App\Model\Entity\OcorrenciaRelatorio newEmptyEntity()
 * @method \App\Model\Entity\OcorrenciaRelatorio newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\OcorrenciaRelatorio> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OcorrenciaRelatorio get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\OcorrenciaRelatorio findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\OcorrenciaRelatorio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\OcorrenciaRelatorio> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OcorrenciaRelatorio|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\OcorrenciaRelatorio saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\OcorrenciaRelatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OcorrenciaRelatorio>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OcorrenciaRelatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OcorrenciaRelatorio> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OcorrenciaRelatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OcorrenciaRelatorio>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\OcorrenciaRelatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\OcorrenciaRelatorio> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OcorrenciaRelatoriosTable extends Table
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

        $this->setTable('ocorrencia_relatorios');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Relatorios', [
            'foreignKey' => 'relatorio_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Ocorrencias', [
            'foreignKey' => 'ocorrencia_id',
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
        // $validator
        //     ->integer('id')
        //     ->requirePresence('id', 'create')
        //     ->notEmptyString('id');

        $validator
            ->integer('relatorio_id')
            ->notEmptyString('relatorio_id');

        // $validator
        //     ->integer('ocorrencia_id')
        //     ->notEmptyString('ocorrencia_id');

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
        // $rules->add($rules->existsIn(['ocorrencia_id'], 'Ocorrencias'), ['errorField' => 'ocorrencia_id']);

        return $rules;
    }
}
