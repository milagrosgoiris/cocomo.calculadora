<div class="space-y-6">
  <div>
    <h1 class="text-2xl font-semibold">Calculadora COCOMO</h1>
    <p class="text-sm text-slate-500">Constructive Cost Model · Estimación de proyectos de software</p>
  </div>

  @error('form')
    <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-3 py-2 text-sm">
      {{ $message }}
    </div>
  @enderror

  <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    {{-- IZQUIERDA --}}
    <div class="space-y-6">
      {{-- Parámetros del proyecto --}}
      <div class="card">
        <h2 class="text-lg font-semibold">Parámetros del Proyecto</h2>
        <p class="text-xs text-slate-500 mb-3">Ingresa los datos básicos de tu proyecto de software</p>

        <div class="grid gap-4 sm:grid-cols-2">
          <label>
            <span class="field-label">KLOC (Miles de líneas de código)</span>
            <input type="number" min="0" step="0.01" wire:model.live="kloc" class="field-control" />
          </label>

          <label>
            <span class="field-label">Salario Medio Mensual ($)</span>
            <input type="number" min="0" step="0.01" wire:model.live="salary" class="field-control" />
          </label>

          <label class="sm:col-span-2">
            <span class="field-label">Modo del Proyecto</span>
            <select wire:model.live="mode" class="field-control">
              @foreach($modes as $key => $p)
                <option value="{{ $key }}">{{ $p['label'] }}</option>
              @endforeach
            </select>
            <span class="field-hint">Proyectos pequeños (Orgánico), mixtos (Semiacoplado) o con altas restricciones (Empotrado)</span>
          </label>
        </div>
      </div>

      {{-- Factores de Costo con tabs --}}
      <div class="card">
        <h2 class="text-lg font-semibold">Factores de Costo (Cost Drivers)</h2>
        <p class="text-xs text-slate-500 mb-4">Ajusta los 15 multiplicadores según las características de tu proyecto</p>

        {{-- Tabs (Alpine) --}}
        <div x-data="{tab:'Producto'}">
          <div class="flex gap-2 rounded-xl bg-slate-100 p-1 mb-4">
            @foreach(array_keys($groups) as $g)
              <button
                class="flex-1 rounded-lg px-3 py-2 text-sm transition
                       data-[active=true]:bg-white data-[active=true]:shadow data-[active=true]:text-slate-900
                       data-[active=false]:text-slate-600"
                :data-active="tab==='{{ $g }}'"
                @click="tab='{{ $g }}'">
                {{ $g }}
              </button>
            @endforeach
          </div>

          {{-- Panels --}}
          @foreach($groups as $groupName => $keys)
            <div x-show="tab==='{{ $groupName }}'" x-cloak class="space-y-4">
              @foreach($keys as $k)
                <label>
                  <span class="field-label">{{ $labels[$k] ?? $k }}</span>
                  <select wire:model.live="ratings.{{ $k }}" class="field-control">
                    @foreach(($drivers[$k] ?? []) as $label=>$mult)
                      @if(!is_null($mult))
                        <option value="{{ $label }}">{{ $label }}</option>
                      @endif
                    @endforeach
                  </select>
                </label>
              @endforeach
            </div>
          @endforeach
        </div>

        <div class="mt-5">
          <button wire:click="calculate"
                  class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-pink-500 px-4 py-2.5 font-medium text-white hover:bg-pink-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 3h14v2H5V3zm4 16h6v2H9v-2zM3 7h18v10H3V7zm2 2v6h14V9H5z"/></svg>
            Calcular Estimación
          </button>
        </div>
      </div>
    </div>

    {{-- DERECHA: Resultados + EAF --}}
    <aside class="space-y-6">
      <div class="card">
        <h2 class="text-lg font-semibold mb-2">Resultados</h2>

        @php $has = ($result['pm'] ?? 0) > 0; @endphp

        @if(!$has)
          <div class="flex h-40 items-center justify-center text-slate-500 text-sm">
            Completa los datos y presiona <b class="ml-1">“Calcular Estimación”</b>
          </div>
        @else
          <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl border border-slate-300 p-3">
              <div class="text-xs text-slate-500">Esfuerzo (PM)</div>
              <div class="text-2xl font-semibold">{{ number_format($result['pm'],1) }}</div>
              <div class="text-xs text-slate-500">personas-mes</div>
            </div>
            <div class="rounded-xl border border-slate-300 p-3">
              <div class="text-xs text-slate-500">Duración (TDEV)</div>
              <div class="text-2xl font-semibold">{{ number_format($result['tdev'],2) }}</div>
              <div class="text-xs text-slate-500">meses</div>
            </div>
            <div class="rounded-xl border border-slate-300 p-3">
              <div class="text-xs text-slate-500">Personal promedio (P)</div>
              <div class="text-2xl font-semibold">{{ number_format($result['p'],2) }}</div>
              <div class="text-xs text-slate-500">personas</div>
            </div>
            <div class="rounded-xl border border-slate-300 p-3">
              <div class="text-xs text-slate-500">Costo total (C)</div>
              <div class="text-2xl font-semibold">$ {{ number_format($result['c'],2,',','.') }}</div>
              <div class="text-xs text-slate-500">C = PM × salario</div>
            </div>
          </div>

          <p class="mt-2 text-xs text-slate-600">
            EAF = <span class="font-mono text-slate-900">{{ number_format($result['eaf'],3) }}</span>
          </p>
        @endif
      </div>

      <div class="card">
        <h2 class="text-lg font-semibold mb-2">Desglose del Factor de Ajuste (EAF)</h2>
        <div class="max-h-72 overflow-auto space-y-2">
          @forelse(($result['detail'] ?? []) as $key => $row)
            <div class="flex items-center justify-between rounded-lg border border-slate-300 px-3 py-2">
              <div class="text-sm">
                {{ $labels[$key] ?? $key }}
                <span class="text-xs text-slate-500">({{ $row['rating'] }})</span>
              </div>
              <div class="font-mono">{{ number_format($row['mult'],2) }}</div>
            </div>
          @empty
            <div class="text-sm text-slate-500">Sin datos aún</div>
          @endforelse
        </div>
      </div>
    </aside>
  </div>
</div>
