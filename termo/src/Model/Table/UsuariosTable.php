<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Usuarios Model
 *
 * @property \App\Model\Table\TpUsuariosTable&\Cake\ORM\Association\BelongsTo $TpUsuarios
 *
 * @method \App\Model\Entity\Usuario newEmptyEntity()
 * @method \App\Model\Entity\Usuario newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Usuario> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usuario get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Usuario findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Usuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Usuario> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Usuario saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Usuario>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Usuario> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsuariosTable extends Table
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

        $this->setTable('usuarios');
        $this->setDisplayField('nm_usuario');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TpUsuarios', [
            'foreignKey' => 'tp_usuarios_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'cd_cpf',
            'bindingKey' => 'cpf'
        ]);

        $this->belongsToMany('Escolas', [
            'through' => 'Users',
            'foreignKey' => 'cpf',
            'bindingKey' => 'cd_cpf'
        ]);

        $this->hasMany('UsuarioUnidEscolares', [
            'foreignKey' => 'usuario_id'
        ]);

        $this->belongsToMany('UnidEscolares', [
            'foreignKey' => 'usuario_id',
            'targetForeignKey' => 'unid_escolares_id',
            'joinTable' => 'usuario_unid_escolares'
        ]);

        // $this->belongsTo('Setores');
        $this->hasOne('SetorSupervisores');

        // $this->hasOne('UsuarioUnidEscolares');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $msg = 'Precisa informar o dado.';
        $validator
            ->scalar('cd_rf')
            ->add('cd_rf', 'valid', ['rule' => 'numeric', 'message' => 'Só números'])
            ->maxLength('cd_rf', 10)
            ->notEmptyString('cd_rf', 'Precisa informar o RF.');

        $validator
            ->scalar('nm_usuario')
            ->maxLength('nm_usuario', 100)
            ->notEmptyString('nm_usuario', $msg);

        $validator
            ->email('email')
            ->notEmptyString('email', $msg)
            ->add('email', 'valid-email', ['rule' => 'email', 'message' => 'Insira um e-mail válido.']);

        // $validator
        //     ->scalar('username')
        //     ->maxLength('username', 100)
        //     ->requirePresence('username', 'create')
        //     ->notEmptyString('username', $msg);

        $validator
            ->scalar('cd_cpf')
            // ->maxLength('cd_cpf', 14)
            ->maxLength('cd_cpf', 11)
            ->notEmptyString('cd_cpf', $msg)
            ->add('cd_cpf', [
                'minLength' => [
                    'rule' => ['minLength', 11],
                    'message' => 'Digite apenas números.'
                ],
                'unique' => [
                    'rule' => 'validateUnique',
                    'message' => 'Este CPF já está cadastrado.',
                    'provider' => 'table'
                ]
            ]);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->notEmptyString('password', $msg);

        $validator
            ->integer('tp_usuarios_id')
            ->notEmptyString('tp_usuarios_id', $msg);

        // $validator
        //     ->integer('cd_assinatura')
        //     ->notEmptyString('cd_assinatura', 'Precisa anexar a assinatura.');

        $validator
            ->add('arquivo', 'file', [
                'rule' => ['uploadedFile', ['optional' => false]]
            ])
            ->notEmptyFile('arquivo', 'Insira o arquivo.');

        return $validator;
    }

    public function findAuth(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {

        // Login por username (ex: dpid.admin, sub.admin, secr.admin)
        return $query->where(['Usuarios.ic_ativo' => 1]);
        // ['Usuarios.ic_ativo' => 1]);

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
        $rules->add($rules->isUnique(['cd_cpf']), ['errorField' => 'cd_cpf']);
        $rules->add($rules->isUnique(['cd_rf']), ['errorField' => 'cd_rf']);
        $rules->add($rules->existsIn('tp_usuarios_id', 'TpUsuarios'), ['errorField' => 'tp_usuarios_id']);

        return $rules;
    }
}
