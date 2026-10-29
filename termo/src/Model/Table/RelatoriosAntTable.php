<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RelatoriosAnt Model
 *
 * @method \App\Model\Entity\RelatoriosAnt newEmptyEntity()
 * @method \App\Model\Entity\RelatoriosAnt newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\RelatoriosAnt> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RelatoriosAnt get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\RelatoriosAnt findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\RelatoriosAnt patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\RelatoriosAnt> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\RelatoriosAnt|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\RelatoriosAnt saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\RelatoriosAnt>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RelatoriosAnt>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RelatoriosAnt>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RelatoriosAnt> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RelatoriosAnt>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RelatoriosAnt>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\RelatoriosAnt>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\RelatoriosAnt> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RelatoriosAntTable extends Table
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

        $this->setTable('relatorios_ant');

        $this->addBehavior('Timestamp');

        $this->belongsTo('UnidEscolares', [
            'foreignKey' => 'cd_unidade',
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
            ->nonNegativeInteger('id')
            ->allowEmptyString('id');

        $validator
            ->scalar('ds_organizacao')
            ->allowEmptyString('ds_organizacao');

        $validator
            ->nonNegativeInteger('cd_usuario')
            ->allowEmptyString('cd_usuario');

        $validator
            ->nonNegativeInteger('cd_unidade')
            ->allowEmptyString('cd_unidade');

        $validator
            ->scalar('ds_atividade')
            ->allowEmptyString('ds_atividade');

        $validator
            ->scalar('cd_assinatura_supervisor')
            ->maxLength('cd_assinatura_supervisor', 45)
            ->allowEmptyString('cd_assinatura_supervisor');

        $validator
            ->scalar('cd_assinatura_diretor')
            ->maxLength('cd_assinatura_diretor', 45)
            ->allowEmptyString('cd_assinatura_diretor');

        $validator
            ->scalar('cd_assinatura_admin')
            ->maxLength('cd_assinatura_admin', 45)
            ->allowEmptyString('cd_assinatura_admin');

        $validator
            ->nonNegativeInteger('re_usuario')
            ->allowEmptyString('re_usuario');

        $validator
            ->scalar('ds_alimentacao')
            ->allowEmptyString('ds_alimentacao');

        $validator
            ->scalar('ds_manutencao')
            ->allowEmptyString('ds_manutencao');

        $validator
            ->scalar('ds_capacidade')
            ->allowEmptyString('ds_capacidade');

        $validator
            ->scalar('ds_legislacao')
            ->allowEmptyString('ds_legislacao');

        $validator
            ->scalar('ds_ouvidoria')
            ->allowEmptyString('ds_ouvidoria');

        $validator
            ->scalar('ds_rh')
            ->allowEmptyString('ds_rh');

        $validator
            ->scalar('ds_patrimonio')
            ->allowEmptyString('ds_patrimonio');

        $validator
            ->scalar('ds_obs')
            ->allowEmptyString('ds_obs');

        $validator
            ->scalar('nm_recibo')
            ->maxLength('nm_recibo', 45)
            ->allowEmptyString('nm_recibo');

        $validator
            ->scalar('nm_cargo_recibo')
            ->maxLength('nm_cargo_recibo', 45)
            ->allowEmptyString('nm_cargo_recibo');

        $validator
            ->nonNegativeInteger('frequencia_id')
            ->allowEmptyString('frequencia_id');

        $validator
            ->nonNegativeInteger('cd_termo')
            ->allowEmptyString('cd_termo');

        $validator
            ->scalar('dt_ass_diretor')
            ->maxLength('dt_ass_diretor', 10)
            ->allowEmptyString('dt_ass_diretor');

        $validator
            ->scalar('dt_ass_adm')
            ->maxLength('dt_ass_adm', 10)
            ->allowEmptyString('dt_ass_adm');

        $validator
            ->scalar('ic_fundamental')
            ->maxLength('ic_fundamental', 10)
            ->allowEmptyString('ic_fundamental');

        $validator
            ->scalar('dt_relatorio')
            ->maxLength('dt_relatorio', 10)
            ->allowEmptyString('dt_relatorio');

        $validator
            ->scalar('obs_ass')
            ->allowEmptyString('obs_ass');

        $validator
            ->boolean('ic_urgente')
            ->allowEmptyString('ic_urgente');

        $validator
            ->allowEmptyString('ic_subs');

        return $validator;
    }
}
