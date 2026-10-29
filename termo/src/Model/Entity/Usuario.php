<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * Usuario Entity
 *
 * @property int $id
 * @property string $nm_usuario
 * @property string|null $email
 * @property string $username
 * @property string $password
 * @property int $tp_usuarios_id
 * @property int $ic_ativo
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\TpUsuario $tp_usuario
 */
class Usuario extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        '*' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password): ?string
    {
        // $hasher = new DefaultPasswordHasher();
        // return $hasher->hash($password);
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return null;
    }

    /**
     * Super = 2 => 11 Supervisor
     * Dir/Ass = 1, 5 => 6 Gestor
     * Sub/Adj 4, 8 => 3 Administrador
     * Sec = 7 => 3 Administrador
     * Dev = 6, 9 => 1 Desenvolvedores
     */


    protected function _getIsAssistente()
    {
        return $this->isCargo(10);
    }

    protected function _getIsDiretor()
    {
        return $this->isCargo(29);
    }

    protected function _getIsSupervisor()
    {
        return $this->is(11);
    }

    protected function _getIsGestor()
    {
        return $this->is(6);
    }

    protected function _getIsAdministrador()
    {
        return $this->is(3);
    }

    protected function _getIsDev()
    {
        return $this->is(1);
    }

    protected function _getTipo()
    {
        $workgroups = array_keys($this->workgroups);
        return reset($workgroups);
    }

    protected function _getCargo()
    {
        $employee = reset($this->employees);
        $jobTitles = $employee['job_titles'] ?? [];
        return reset($jobTitles);
    }

    protected function _getCargoReduzido()
    {
        $partes = preg_split('/\s+/', $this->cargo);
        return reset($partes);
    }

    protected function _getFuncao()
    {
        return reset($this->workgroups);
    }

    protected function _getNome()
    {
        return $this->person['name'];
    }

    protected function _getNomeReduzido()
    {
        $partes = preg_split('/\s+/', $this->nome);
        return count($partes) > 1 ? reset($partes) . ' ' . end($partes) : $this->nome;
    }

    public function is($workgroup)
    {
        if ($workgroup === null || $workgroup === '' || !is_array($this->workgroups)) {
            return false;
        }

        return array_key_exists($workgroup, $this->workgroups);
    }

    public function isCargo($job_title)
    {
        return array_reduce($this->employees, fn($is, $employee) => $is ?: array_key_exists($job_title, $employee['job_titles']), false);
    }
}
