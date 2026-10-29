<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SetorUnidEscolaresFixture
 */
class SetorUnidEscolaresFixture extends TestFixture
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
                'nm_setor' => '',
                'unid_escolares_id' => 1,
                'created' => '2025-03-14 18:29:18',
                'modified' => '2025-03-14 18:29:18',
            ],
        ];
        parent::init();
    }
}
