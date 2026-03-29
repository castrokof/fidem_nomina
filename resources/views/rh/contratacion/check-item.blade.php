{{-- resources/views/rh/contratacion/check-item.blade.php --}}
{{-- Variables esperadas: $item (array con key, label, desc, tag), $fase (int) --}}
{{-- Compatible PHP 7.1+ / Laravel 5.7 --}}

@php
    $ciItem      = isset($checklist) ? $checklist->where('item_key', $item['key'])->first() : null;
    $estaCheck   = $ciItem && $ciItem->completado ? true : false;
    $porQuien    = ($ciItem && $ciItem->completado) ? ($ciItem->completado_por ?? 'RRHH') : null;
    $cuandoCheck = ($ciItem && $ciItem->completado && $ciItem->completado_at)
                        ? $ciItem->completado_at->format('d/m/Y H:i') : null;

    if ($item['tag'] === 'bloqueante')      $tagTexto = '⛔ BLOQUEANTE';
    elseif ($item['tag'] === 'obligatorio') $tagTexto = '⚠ Obligatorio';
    elseif ($item['tag'] === 'segun_cargo') $tagTexto = '🔹 Según cargo';
    else                                    $tagTexto = 'Opcional';
@endphp

<div class="check-item" id="item-{{ $item['key'] }}">
    <div class="check-box {{ $estaCheck ? 'checked' : '' }}"
         id="box-{{ $item['key'] }}"
         onclick="toggleItem('{{ $item['key'] }}', {{ $fase }})">
        {{ $estaCheck ? '✓' : '' }}
    </div>
    <div class="check-content">
        <span class="check-label {{ $estaCheck ? 'tachado' : '' }}"
              id="label-{{ $item['key'] }}"
              onclick="toggleItem('{{ $item['key'] }}', {{ $fase }})">
            {{ $item['label'] }}
        </span>
        <p class="check-desc">{{ $item['desc'] }}</p>
        <span class="tag tag-{{ $item['tag'] }}">{{ $tagTexto }}</span>
    </div>
    <div class="check-meta">
        @if($porQuien)
            <span class="check-responsable">{{ $porQuien }}</span>
        @endif
        @if($cuandoCheck)
            <span class="check-fecha">{{ $cuandoCheck }}</span>
        @endif
    </div>
</div>
