<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Kost;

class KostIndex extends Component
{
    private const SORTABLE_FIELDS = [
        'id_kost',
        'nama_kost',
        'harga',
        'tipe_kos',
        'sepesifikasi_tipe_kamar',
        'fasilitas_kamar',
        'fasilitas_kamar_mandi',
        'fasilitas_umum',
        'fasilitas_parkir',
        'tempat_terdekat',
    ];

    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $filterTipe = '';
    public string $sortField = 'id_kost';
    public string $sortDirection = 'asc';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showFasilitasModal = false;
    public bool $showImportModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?Kost $selectedKostFasilitas = null;

    public $importFile;

    // Form fields
    public string $nama_kost = '';
    public string $harga = '';
    public string $tipe_kos = 'Putri';
    public string $sepesifikasi_tipe_kamar = '';
    public string $fasilitas_kamar = '';
    public string $fasilitas_kamar_mandi = '';
    public string $fasilitas_umum = '';
    public string $fasilitas_parkir = '';
    public string $tempat_terdekat = '';
    public string $peraturan_kos = '';
    public string $link_original = '';
    public string $latitude = '';
    public string $longitude = '';

    protected function rules(): array
    {
        return [
            'nama_kost' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'tipe_kos' => 'required|string|max:255',
            'sepesifikasi_tipe_kamar' => 'nullable|string|max:255',
            'fasilitas_kamar' => 'nullable|string|max:255',
            'fasilitas_kamar_mandi' => 'nullable|string|max:255',
            'fasilitas_umum' => 'nullable|string|max:255',
            'fasilitas_parkir' => 'nullable|string|max:255',
            'tempat_terdekat' => 'nullable|string|max:255',
            'peraturan_kos' => 'nullable|string',
            'link_original' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterTipe(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if (! in_array($field, self::SORTABLE_FIELDS, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->reset([
            'nama_kost', 'harga', 'tipe_kos', 'sepesifikasi_tipe_kamar', 
            'fasilitas_kamar', 'fasilitas_kamar_mandi', 'fasilitas_umum', 
            'fasilitas_parkir', 'tempat_terdekat', 'peraturan_kos', 'link_original',
            'latitude', 'longitude'
        ]);
        $this->tipe_kos = 'Kos Putri';
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $kost = Kost::findOrFail($id);
        $this->editingId = $id;
        $this->nama_kost = $kost->nama_kost;
        $this->harga = (string) $kost->harga;
        $this->tipe_kos = $kost->tipe_kos;
        $this->sepesifikasi_tipe_kamar = $kost->sepesifikasi_tipe_kamar ?? '';
        $this->fasilitas_kamar = $kost->fasilitas_kamar ?? '';
        $this->fasilitas_kamar_mandi = $kost->fasilitas_kamar_mandi ?? '';
        $this->fasilitas_umum = $kost->fasilitas_umum ?? '';
        $this->fasilitas_parkir = $kost->fasilitas_parkir ?? '';
        $this->tempat_terdekat = $kost->tempat_terdekat ?? '';
        $this->peraturan_kos = $kost->peraturan_kos ?? '';
        $this->link_original = $kost->link_original ?? '';
        $this->latitude = $kost->latitude !== null ? (string) $kost->latitude : '';
        $this->longitude = $kost->longitude !== null ? (string) $kost->longitude : '';
        $this->showModal = true;
    }

    public function openFasilitas(int $id): void
    {
        $this->selectedKostFasilitas = Kost::findOrFail($id);
        $this->showFasilitasModal = true;
    }

    public function closeFasilitas(): void
    {
        $this->showFasilitasModal = false;
        $this->selectedKostFasilitas = null;
    }

    public function openImportModal(): void
    {
        $this->reset('importFile');
        $this->showImportModal = true;
    }

    public function closeImportModal(): void
    {
        $this->showImportModal = false;
        $this->reset('importFile');
    }

    public function importCSV()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $path = $this->importFile->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file); // Read the header row

        $count = 0;
        while ($row = fgetcsv($file)) {
            // Map row according to CSV headers provided:
            // 0: ID_Kost, 1: Nama Kost, 2: Harga, 3: Tipe Kost, 4: Spesifikasi Tipe Kamar
            // 5: Fasilitas Kamar, 6: Fasilitas Kamar Mandi, 7: Fasilitas Umum, 8: Fasilitas Parkir
            // 9: Tempat Terdekat, 10: Peraturan Kos, 11: Link Original, 12-13: Latitude/Longitude opsional
            
            if (count($row) < 12) continue; // Skip incomplete rows
            
            Kost::updateOrCreate(
                ['id_kost' => $row[0]], // Optional: if you want to keep ID_Kost mapping
                [
                    'nama_kost' => $row[1],
                    'harga' => is_numeric($row[2]) ? $row[2] : 0,
                    'tipe_kos' => $row[3],
                    'sepesifikasi_tipe_kamar' => $row[4],
                    'fasilitas_kamar' => $row[5],
                    'fasilitas_kamar_mandi' => $row[6],
                    'fasilitas_umum' => $row[7],
                    'fasilitas_parkir' => $row[8],
                    'tempat_terdekat' => $row[9],
                    'peraturan_kos' => $row[10],
                    'link_original' => $row[11],
                    'latitude' => isset($row[12]) && is_numeric($row[12]) ? $row[12] : null,
                    'longitude' => isset($row[13]) && is_numeric($row[13]) ? $row[13] : null,
                ]
            );
            $count++;
        }

        fclose($file);
        
        $this->closeImportModal();
        session()->flash('success', "Berhasil mengimpor {$count} data kost.");
        $this->resetPage();
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['latitude'] = $data['latitude'] === '' ? null : $data['latitude'];
        $data['longitude'] = $data['longitude'] === '' ? null : $data['longitude'];

        if ($this->editingId) {
            Kost::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Data kost berhasil diperbarui.');
        } else {
            Kost::create($data);
            session()->flash('success', 'Data kost berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Kost::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Data kost berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $sortField = in_array($this->sortField, self::SORTABLE_FIELDS, true)
            ? $this->sortField
            : 'id_kost';
        $sortDirection = $this->sortDirection === 'desc' ? 'desc' : 'asc';

        $kosts = Kost::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_kost', 'like', "%{$this->search}%")
                        ->orWhere('tipe_kos', 'like', "%{$this->search}%")
                        ->orWhere('sepesifikasi_tipe_kamar', 'like', "%{$this->search}%")
                        ->orWhere('fasilitas_kamar', 'like', "%{$this->search}%")
                        ->orWhere('fasilitas_umum', 'like', "%{$this->search}%")
                        ->orWhere('tempat_terdekat', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterTipe, fn($q) => $q->where('tipe_kos', $this->filterTipe))
            ->orderBy($sortField, $sortDirection)
            ->when($sortField !== 'id_kost', fn($q) => $q->orderBy('id_kost', 'asc'))
            ->paginate(5);

        return view('livewire.admin.kost-index', compact('kosts'))
            ->layout('components.layouts.admin', [
                'title' => 'Manajemen Kost',
                'breadcrumb' => ['Data Kost' => route('admin.kost')],
            ]);
    }
}
