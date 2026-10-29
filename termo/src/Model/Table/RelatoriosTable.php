<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Relatorios Model
 *
 * @property \App\Model\Table\UsuariosTable&\Cake\ORM\Association\BelongsTo $Usuarios
 * @property \App\Model\Table\FuncoesTable&\Cake\ORM\Association\BelongsTo $Funcoes
 * @property \App\Model\Table\OcorrenciaRelatoriosTable&\Cake\ORM\Association\HasMany $OcorrenciaRelatorios
 *
 * @method \App\Model\Entity\Relatorio newEmptyEntity()
 * @method \App\Model\Entity\Relatorio newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Relatorio> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Relatorio get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Relatorio findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Relatorio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Relatorio> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Relatorio|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Relatorio saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Relatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Relatorio>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Relatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Relatorio> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Relatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Relatorio>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Relatorio>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Relatorio> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RelatoriosTable extends Table
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

        $this->setTable('relatorios');
        $this->setDisplayField('nm_atendido');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usuarios', [
            'foreignKey' => 'usuario_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Funcoes', [
            'foreignKey' => 'funcoes_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('UnidEscolares', [
            'foreignKey' => 'unid_escolar_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('OcorrenciaRelatorios', [
            'foreignKey' => 'relatorio_id',
        ]);

        $this->hasMany('Respostas', [
            'foreignKey' => 'relatorio_id',
            // 'saveStrategy' => 'replace'
        ]);

        $this->hasMany('Providencias', [
            'foreignKey' => 'relatorio_id',
        ]);

        // $this->hasOne('UsuarioUnidEscolares', [

        // ])
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
            ->integer('usuario_id')
            ->notEmptyString('usuario_id');

        // $validator
        //     ->scalar('nm_atendido')
        //     ->maxLength('nm_atendido', 100)
        //     ->notEmptyString('nm_atendido');

        // $validator
        //     ->integer('funcoes_id')
        //     ->notEmptyString('funcoes_id');

        $validator
            ->integer('place_id');
            // ->requirePresence('unid_escolar_id', 'create')
            // ->notEmptyString('place_id');

        $validator
            ->date('data')
            // ->requirePresence('data', 'create')
            ->allowEmptyString('data');

        $validator
            ->notEmptyString('ic_acompanhado', 'Por favor, selecione sim ou não.');
        // ->inList('ic_acompanhado', [1, 0], 'Resposta inválida.');

        $seForSim = function (array $context): bool {

            // dd($context);
            return isset($context['data']['ic_acompanhado']) && $context['data']['ic_acompanhado'] == 1;
        };

        $seForNao = function (array $context): bool {
            dd($context);
            return isset($context['data']['ic_acompanhado']) && $context['data']['ic_acompanhado'] == 0;
        };

        $validator
            // ->requirePresence('nm_atendido', $seForSim, 'Este campo é obrigatório quando a resposta é sim.')
            ->allowEmptyString('nm_atendido', null, $seForNao)
            ->notEmptyString('nm_atendido', 'Obrigatório o preenchimento.', $seForSim);

        $validator
            // ->requirePresence('funcoes_id', $seForSim, 'Este campo é obrigatório quando a resposta é sim.')
            ->allowEmptyString('funcoes_id', null, $seForNao)
            ->notEmptyString('funcoes_id', 'Obrigatório o preenchimento.', $seForSim);

        // $validator
        //     ->requirePresence('ic_acompanhado', 'create')
        //     ->notEmptyString('ic_acompanhado');

        // $validator
        //     ->scalar('ds_acompanhado')
        //     ->allowEmptyString('ds_acompanhado');

        // $validator
        //     ->scalar('tipo_organizacao')
        //     ->allowEmptyString('tipo_organizacao');

        // $validator
        //     ->scalar('tipo_higiene')
        //     ->allowEmptyString('tipo_higiene');

        // $validator
        //     ->scalar('ds_organizacao')
        //     ->allowEmptyString('ds_organizacao');

        // $validator
        //     ->scalar('ds_higiene')
        //     ->allowEmptyString('ds_higiene');

        // $validator
        //     ->scalar('ds_manutencao')
        //     ->allowEmptyString('ds_manutencao');

        // $validator
        //     ->scalar('ds_legislacao_documentos')
        //     ->allowEmptyString('ds_legislacao_documentos');

        $validator
            ->scalar('ds_obs_geral')
            ->allowEmptyString('ds_obs_geral');

        $validator
            ->allowEmptyString('ic_rascunho');

        $validator
            ->allowEmptyString('ic_cancelado');

        // $validator
        //     ->allowEmptyString('ic_prioridade');
        // $validator
        //     ->requirePresence('ic_rascunho', 'update')
        //     ->notEmptyString('ic_rascunho');
        // ->requirePresence('ic_responsavel', 'update');
        // ->requirePresence('responsavel_id', 'update');
        // ->add('ic_rascunho', [
        //     'inList' => [
        //         'rule' => ['inList', [1, 0]],
        //         'message' => 'Deve ser respondido se o Responsável estava presente.',
        //     ],
        // ]);
        // ->add('ic_rascunho', 'validateIcResponsavel', [
        //     'rule' => function ($value, $context) {
        //         if ($value == 0 ) {

        //             // debug($value);
        //             return $context['data']['ic_responsavel'] ?? false ? true : false;
        //         }
        //         return true;
        //     },
        //     // 'message' => 'Deve respondido se o Responsável estava presente!. ' , 'errorField' => 'ic_responsavel'
        // ]);


        // $validator
        //     ->allowEmptyString('ic_responsavel');

        $validator
            ->integer('responsavel_id')
            ->allowEmptyString('responsavel_id');

        $validator
            ->integer('id_ass_super')
            ->allowEmptyString('id_ass_super');

        $validator
            ->date('data_ass_super')
            ->allowEmptyString('data_ass_super');

        $validator
            ->integer('id_ass_dir')
            ->allowEmptyString('id_ass_dir');

        $validator
            ->date('data_ass_dir')
            ->allowEmptyString('data_ass_dir');

        $validator
            ->integer('id_ass_sub')
            ->allowEmptyString('id_ass_sub');

        $validator
            ->date('data_ass_sub')
            ->allowEmptyString('data_ass_sub');

        $validator
            ->integer('id_ass_assis')
            ->allowEmptyString('id_ass_assis');

        $validator
            ->date('data_ass_assis')
            ->allowEmptyString('data_ass_assis');

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
        $rules->add($rules->existsIn(['usuario_id'], 'Usuarios'), ['errorField' => 'usuario_id']);
        // $rules->add($rules->existsIn(['funcoes_id'], 'Funcoes'), ['errorField' => 'funcoes_id']);
        // $rules->add(function ($entity, $options) {

        //     if ($entity->ic_rascunho == 0) {
        //         if (!$entity->ic_responsavel) {
        //             return 'Deve ser respondido se o Responsável estava presente.';
        //         }
        //         if (!$entity->responsavel_id) {
        //             return 'Deve ser escolhido o Responsável.';
        //         }
        //     }
        //     return true;
        // }, 'validateIcResponsavel', [
        //     'errorField' => 'ic_responsavel',
        // ]);

        return $rules;
    }
}
