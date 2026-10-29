<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DimensaoFixture
 */
class DimensaoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'dimensao';
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
                'titCompleto' => 'Lorem ipsum dolor sit amet',
                'titParcial' => 'Lorem ipsum dolor sit amet',
                'pergunta_id' => 1,
                'resposta_id' => 1,
                'created' => '2026-08-14 14:03:19',
                'modified' => '2026-08-14 14:03:19',
            ],
        ];
        parent::init();
    }
}
