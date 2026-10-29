<?php
/**
 * @var string       $label
 * @var string       $icon      Nome do Bootstrap Icon, sem o prefixo "bi-"
 * @var array|string $url
 * @var string       $position  'start' (padrão) ou 'end'
 * @var string       $class     Classes extras (ex.: btn-pill--fixed)
 * @var array        $options   Atributos extras (title, target...)
 */
$position = $position ?? 'start';
$class    = $class ?? '';
$options  = $options ?? [];

echo $this->Html->link(
    '<span class="btn-pill__icon"><i class="bi bi-' . h($icon) . '"></i></span>'
    . '<span class="btn-pill__label">' . h($label) . '</span>',
    $url,
    array_merge($options, [
        'class'  => trim("btn btn-" .h($cor). " btn-sm btn-pill btn-pill--{$position} {$class}"),
        'escape' => false,
    ])
);