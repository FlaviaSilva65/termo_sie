<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RelatoriosFixture
 */
class RelatoriosFixture extends TestFixture
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
                'usuario_id' => 1,
                'nm_atendido' => 'Lorem ipsum dolor sit amet',
                'funcoes_id' => 1,
                'unid_escolar_id' => 1,
                'data' => '2025-05-07',
                'ic_acompanhado' => 1,
                'ic_organizacao' => 1,
                'ds_organizacao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ic_higiene' => 1,
                'ds_higiene' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ic_hidraulica' => 1,
                'ic_eletrica' => 1,
                'ic_pintura' => 1,
                'ic_marcenaria' => 1,
                'ic_serralheria' => 1,
                'ic_alvenaria' => 1,
                'ic_vidracaria' => 1,
                'ic_lousa_digital' => 1,
                'ic_chrome' => 1,
                'ic_informatica' => 1,
                'ic_equipamentos' => 1,
                'ic_mobiliario' => 1,
                'ic_outros' => 1,
                'ds_manutencao' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ic_sed' => 1,
                'ic_pront_prof' => 1,
                'ic_pront_func' => 1,
                'ic_pl_gestao' => 1,
                'ic_reg_vid_escolar' => 1,
                'ic_exerc_domiciliar' => 1,
                'ic_hist_escolar' => 1,
                'ic_balancete_apm' => 1,
                'ic_atend_domiciliar' => 1,
                'ic_ouvidoria' => 1,
                'ic_quadro_pah' => 1,
                'ic_leg_doc' => 1,
                'ic_reclassificacao' => 1,
                'ic_classificacao' => 1,
                'ic_livro_atas' => 1,
                'ic_equivalencia_estudos' => 1,
                'ic_diario_on_line' => 1,
                'ic_consolidado' => 1,
                'ic_conselho_classe' => 1,
                'ic_del_cme_001_18' => 1,
                'ic_ata_result_finais' => 1,
                'ic_ficha_ind' => 1,
                'ds_legislacao_documentos' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ds_obs_geral' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'ic_prioridade' => 1,
                'ic_responsavel' => 1,
                'responsavel_id' => 1,
                'ic_rascunho' => 1,
                'created' => '2025-05-07 13:59:42',
                'modified' => '2025-05-07 13:59:42',
            ],
        ];
        parent::init();
    }
}
