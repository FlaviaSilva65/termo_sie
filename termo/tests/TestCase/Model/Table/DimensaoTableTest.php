<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DimensaoTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DimensaoTable Test Case
 */
class DimensaoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DimensaoTable
     */
    protected $Dimensao;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Dimensao',
        'app.Perguntas',
        'app.Respostas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Dimensao') ? [] : ['className' => DimensaoTable::class];
        $this->Dimensao = $this->getTableLocator()->get('Dimensao', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Dimensao);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DimensaoTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\DimensaoTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
