<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SetorSupervisoresTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SetorSupervisoresTable Test Case
 */
class SetorSupervisoresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SetorSupervisoresTable
     */
    protected $SetorSupervisores;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.SetorSupervisores',
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
        $config = $this->getTableLocator()->exists('SetorSupervisores') ? [] : ['className' => SetorSupervisoresTable::class];
        $this->SetorSupervisores = $this->getTableLocator()->get('SetorSupervisores', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SetorSupervisores);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SetorSupervisoresTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SetorSupervisoresTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
