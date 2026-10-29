<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SetorSupervisorTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SetorSupervisorTable Test Case
 */
class SetorSupervisorTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SetorSupervisorTable
     */
    protected $SetorSupervisor;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.SetorSupervisor',
        'app.Setores',
        'app.Usuarios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SetorSupervisor') ? [] : ['className' => SetorSupervisorTable::class];
        $this->SetorSupervisor = $this->getTableLocator()->get('SetorSupervisor', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SetorSupervisor);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SetorSupervisorTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SetorSupervisorTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
