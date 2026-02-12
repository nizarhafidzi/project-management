<?php

namespace Modules\System\Livewire;

use Livewire\Component;
use Modules\System\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;

class AutoCheckoutSettings extends Component
{
    use AuthorizesRequests;

    public $auto_checkout_enabled;
    public $auto_checkout_run_time;
    public $default_clock_out_time;

    public function mount()
    {
        $this->authorize('viewAny', Setting::class);

        $this->auto_checkout_enabled = (bool) Setting::get('auto_checkout_enabled', false);
        $this->auto_checkout_run_time = Setting::get('auto_checkout_run_time', '23:59');
        $this->default_clock_out_time = Setting::get('default_clock_out_time', '17:00');
    }

    public function save()
    {
        $this->authorize('update', Setting::class);

        $this->validate([
            'auto_checkout_enabled' => 'boolean',
            'auto_checkout_run_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
            'default_clock_out_time' => ['required', 'regex:/^([01][0-9]|2[0-3]):[0-5][0-9]$/'],
        ]);

        Setting::set('auto_checkout_enabled', $this->auto_checkout_enabled);
        Setting::set('auto_checkout_run_time', $this->auto_checkout_run_time);
        Setting::set('default_clock_out_time', $this->default_clock_out_time);

        // Update cache for Kernel
        Cache::put('auto_checkout_run_time', $this->auto_checkout_run_time);

        $this->dispatch('notify', message: "Configuration updated: Auto-Checkout " . ($this->auto_checkout_enabled ? 'enabled' : 'disabled') . " at {$this->auto_checkout_run_time}");
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('system::livewire.auto-checkout-settings');
    }
}
