<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SetorUnidEscolaresTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SetorUnidEscolaresTable Test Case
 */
class SetorUnidEscolaresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SetorUnidEscolaresTable
     */
    protected $SetorUnidEscolares;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.SetorUnidEscolares',
        'app.UnidEscolares',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SetorUnidEscolares') ? [] : ['className' => SetorUnidEscolaresTable::class];
        $this->SetorUnidEscolares = $this->getTableLocator()->get('SetorUnidEscolares', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SetorUnidEscolares);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SetorUnidEscolaresTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SetorUnidEscolaresTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
