<?php

namespace Modules\System\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Modules\Reporting\Models\Holiday;

class HolidayManager extends Component
{
    use WithPagination;

    public $date = '';
    public $description = '';
    public $holidayId = null;
    public bool $isModalOpen = false;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        abort_if(!auth()->user()->hasRole('Superadmin'), 403);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->date = '';
        $this->description = '';
        $this->holidayId = null;
    }

    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        $this->holidayId = $holiday->id;
        $this->date = $holiday->date->format('Y-m-d');
        $this->description = $holiday->description;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $rules = [
            'date' => 'required|date',
            'description' => 'required|string|max:255',
        ];

        // Unique date validation (except when updating the same record)
        if ($this->holidayId) {
            $rules['date'] .= '|unique:holidays,date,' . $this->holidayId;
        } else {
            $rules['date'] .= '|unique:holidays,date';
        }

        $this->validate($rules);

        Holiday::updateOrCreate(
            ['id' => $this->holidayId],
            [
                'date' => $this->date,
                'description' => $this->description,
            ]
        );

        $this->closeModal();
        session()->flash('message', $this->holidayId ? 'Holiday updated successfully.' : 'Holiday added successfully.');
    }

    public function delete($id)
    {
        Holiday::findOrFail($id)->delete();
        session()->flash('message', 'Holiday deleted successfully.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('system::livewire.holiday-manager', [
            'holidays' => Holiday::orderBy('date', 'desc')->paginate(10),
        ]);
    }
}
