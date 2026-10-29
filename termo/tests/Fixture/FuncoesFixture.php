<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncoesFixture
 */
class FuncoesFixture extends TestFixture
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
                'nm_funcao' => '',
                'created' => '2025-05-07 13:59:27',
                'modified' => '2025-05-07 13:59:27',
            ],
        ];
        parent::init();
    }
}
