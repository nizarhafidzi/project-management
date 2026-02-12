<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\System\Models\Setting;
use Modules\Operations\Models\DailyLog;
use App\Models\User;
use Modules\Project\Models\Project;
use Modules\Project\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class AutoCheckoutTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('module:migrate');

        $this->user = User::factory()->create();
        $project = Project::factory()->create();
        $this->task = Task::factory()->create(['project_id' => $project->id]);
        
        // Clear cache to ensure settings are fresh
        Cache::flush();
    }

    public function test_command_does_nothing_if_disabled()
    {
        Setting::set('auto_checkout_enabled', false);
        
        // Create an open log
        DailyLog::create([
            'user_id' => $this->user->id,
            'task_id' => $this->task->id,
            'log_date' => Carbon::now('Asia/Jakarta')->toDateString(),
            'clock_in' => '08:00',
            'clock_out' => null,
            'status' => 'open',
        ]);

        Artisan::call('app:auto-checkout-daily-logs');

        $this->assertDatabaseHas('daily_logs', [
            'status' => 'open',
            'clock_out' => null,
        ]);
    }

    public function test_command_closes_open_logs_when_enabled()
    {
        Setting::set('auto_checkout_enabled', true);
        Setting::set('default_clock_out_time', '18:00');
        
        // Create an open log for today
        DailyLog::create([
            'user_id' => $this->user->id,
            'task_id' => $this->task->id,
            'log_date' => Carbon::now('Asia/Jakarta')->toDateString(),
            'clock_in' => '08:00',
            'clock_out' => null,
            'progress_increment' => 0,
            'status' => 'open',
        ]);

        Artisan::call('app:auto-checkout-daily-logs');

        $this->assertDatabaseHas('daily_logs', [
            'status' => 'system_checkout',
            'clock_out' => '18:00:00', // Time column format
        ]);
        
        $log = DailyLog::first();
        $this->assertStringContainsString('Auto-closed by system', $log->description ?? '');
    }
}
