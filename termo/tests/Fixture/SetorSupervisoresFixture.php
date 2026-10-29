<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SetorSupervisoresFixture
 */
class SetorSupervisoresFixture extends TestFixture
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
                'setores_id' => 1,
                'usuarios_id' => 1,
                'created' => '2025-03-17 17:19:27',
                'modified' => '2025-03-17 17:19:27',
            ],
        ];
        parent::init();
    }
}
