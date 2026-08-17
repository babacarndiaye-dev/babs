<?php

namespace App\Livewire\Admin\Finance\FeeTypes;

use App\Models\FeeType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public ?FeeType $editing = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public function edit(FeeType $feeType): void
    {
        $this->editing = $feeType;
        $this->name = $feeType->name;
        $this->code = $feeType->code;
        $this->description = $feeType->description ?? '';
    }

    public function cancel(): void
    {
        $this->reset(['editing', 'name', 'code', 'description']);
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50', 'unique:fee_types,code,'.$this->editing?->id],
            'description' => ['nullable', 'string'],
        ]);

        if ($this->editing) {
            $this->editing->update($data);
        } else {
            FeeType::create($data);
        }

        $this->cancel();
        session()->flash('status', 'Type de frais enregistré.');
    }

    public function delete(FeeType $feeType): void
    {
        $feeType->delete();

        session()->flash('status', 'Type de frais supprimé.');
    }

    public function render()
    {
        return view('livewire.admin.finance.fee-types.index', [
            'feeTypes' => FeeType::orderBy('name')->get(),
        ]);
    }
}
