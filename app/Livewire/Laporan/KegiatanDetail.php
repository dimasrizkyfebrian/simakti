<?php

namespace App\Livewire\Laporan;

use App\Models\Kegiatan;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\Attributes\Layout;

class KegiatanDetail extends Component
{
    public Kegiatan $kegiatan;

    public function mount(Kegiatan $kegiatan): void
    {
        $this->kegiatan = $kegiatan;
    }

    #[Layout('layouts.print')]
    public function render(): View
    {
        return view('livewire.laporan.kegiatan-detail')
            ->title('Laporan: ' . $this->kegiatan->nama_kegiatan);
    }
}
