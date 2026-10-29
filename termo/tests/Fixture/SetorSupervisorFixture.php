<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SetorSupervisorFixture
 */
class SetorSupervisorFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'setor_supervisor';
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
                'created' => '2025-03-17 17:13:00',
                'modified' => '2025-03-17 17:13:00',
            ],
        ];
        parent::init();
    }
}
