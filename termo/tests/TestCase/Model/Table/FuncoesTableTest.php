<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuncoesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuncoesTable Test Case
 */
class FuncoesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FuncoesTable
     */
    protected $Funcoes;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Funcoes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Funcoes') ? [] : ['className' => FuncoesTable::class];
        $this->Funcoes = $this->getTableLocator()->get('Funcoes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Funcoes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FuncoesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
