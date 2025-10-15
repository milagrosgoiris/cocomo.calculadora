<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CocomoService;

class CocomoCalculator extends Component
{
    public float $kloc = 10;
    public float $salary = 5000;
    public string $mode = 'organic';

    /** ratings por driver (valoración elegida) */
    public array $ratings = [];

    /** resultado de cálculo */
    public array $result = ['pm'=>0,'tdev'=>0,'p'=>0,'c'=>0,'eaf'=>1,'detail'=>[]];

    /** etiquetas en español */
    public array $labels = [
        // Producto
        'RELY' => 'Confiabilidad requerida del software',
        'DATA' => 'Tamaño de la base de datos',
        'CPLX' => 'Complejidad del producto',
        // Hardware
        'TIME' => 'Restricciones de tiempo de ejecución',
        'STOR' => 'Restricciones de memoria',
        'VIRT' => 'Volatilidad de la máquina virtual',
        'TURN' => 'Tiempo de respuesta del computador',
        // Personal
        'ACAP' => 'Capacidad del analista',
        'AEXP' => 'Experiencia en la aplicación',
        'PCAP' => 'Capacidad del programador',
        'VEXP' => 'Experiencia en la máquina virtual',
        'LEXP' => 'Experiencia en el lenguaje',
        // Proyecto
        'MODP' => 'Uso de prácticas modernas de programación',
        'TOOL' => 'Uso de herramientas de software',
        'SCED' => 'Restricciones de tiempo de desarrollo',
    ];

    /** agrupación para tabs */
    public array $groups = [
        'Producto' => ['RELY','DATA','CPLX'],
        'Hardware' => ['TIME','STOR','VIRT','TURN'],
        'Personal' => ['ACAP','AEXP','PCAP','VEXP','LEXP'],
        'Proyecto' => ['MODP','TOOL','SCED'],
    ];

    public function mount(): void
    {
        $drivers = config('cocomo.drivers', []);
        foreach (array_keys($drivers) as $k) {
            $this->ratings[$k] = 'Nominal';
        }
        $this->calculate();
    }

    public function updated(): void
    {
        // cálculo en vivo; si preferís solo con botón, comentá esta línea
        $this->calculate();
    }

    public function calculate(): void
    {
        $errors = CocomoService::validate($this->kloc, $this->salary, $this->mode, $this->ratings);
        if ($errors) {
            $this->addError('form', implode(' • ', $errors));
            return;
        }
        $this->resetErrorBag('form');

        $this->result = CocomoService::calculate($this->kloc, $this->salary, $this->mode, $this->ratings);
    }

    public function render()
    {
        return view('livewire.cocomo-calculator', [
            'modes'   => config('cocomo.modes'),
            'drivers' => config('cocomo.drivers'),
        ]);
    }
}
