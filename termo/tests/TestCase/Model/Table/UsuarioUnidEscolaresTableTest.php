<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuarioUnidEscolaresTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsuarioUnidEscolaresTable Test Case
 */
class UsuarioUnidEscolaresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuarioUnidEscolaresTable
     */
    protected $UsuarioUnidEscolares;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.UsuarioUnidEscolares',
        'app.Usuarios',
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
        $config = $this->getTableLocator()->exists('UsuarioUnidEscolares') ? [] : ['className' => UsuarioUnidEscolaresTable::class];
        $this->UsuarioUnidEscolares = $this->getTableLocator()->get('UsuarioUnidEscolares', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsuarioUnidEscolares);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsuarioUnidEscolaresTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\UsuarioUnidEscolaresTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
