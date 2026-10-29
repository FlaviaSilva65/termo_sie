<?php foreach ($escola_users as $escola_user) : ?>
                        <?= $this->Form->select('responsavel_id', ['value' => $escola_user->nome], ['label' => 'Responsável :']); ?>
                    <?php endforeach; ?>