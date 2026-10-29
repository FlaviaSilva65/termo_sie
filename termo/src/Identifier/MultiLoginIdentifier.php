<?php

declare(strict_types=1);

namespace App\Identifier;

use Authentication\Identifier\AbstractIdentifier;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Security;

class MultiLoginIdentifier extends AbstractIdentifier
{
    use LocatorAwareTrait;

    protected array $_defaultConfig = [
        'userModel' => 'Usuarios',
        'passwordField' => 'password',
    ];

    public function identify(array $credentials): \ArrayAccess|array|null
    {
        $username = $credentials['username'] ?? '';
        $password = $credentials['password'] ?? '';

        \Cake\Log\Log::debug('MultiLoginIdentifier - username: ' . $username);

        if (empty($username) || empty($password)) {
            return null;
        }

        $table = $this->fetchTable($this->getConfig('userModel'));

        // Tenta buscar por CPF ou username
        $cpfLimpo = preg_replace('/\D/', '', $username);

        if (strlen($cpfLimpo) === 11 && ctype_digit($cpfLimpo)) {
            // Busca por CPF
            $user = $table->find()
                ->contain(['TpUsuarios'])
                ->where(['cd_cpf' => $cpfLimpo])
                ->first();
        } else {
            // Busca por username (ex: dpid.admin, sub.admin)
            $user = $table->find()
                ->contain(['TpUsuarios'])
                ->where(['username' => $username])
                ->first();
        }

        if (!$user) {
            return null;
        }

        // Verifica a senha (bcrypt)
        if (password_verify($password, $user->password)) {
            return $user;
        }

        // Fallback MD5 (senhas legadas)
        if ($user->password === md5($password)) {
            return $user;
        }

        return null;
    }
}
