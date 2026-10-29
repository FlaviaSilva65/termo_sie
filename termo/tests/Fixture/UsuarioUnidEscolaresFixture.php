<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsuarioUnidEscolaresFixture
 */
class UsuarioUnidEscolaresFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'usuario_id' => 1,
                'unid_escolares_id' => 1,
                'created' => '2025-03-13 19:35:55',
                'modified' => '2025-03-13 19:35:55',
            ],
        ];
        parent::init();
    }
}
