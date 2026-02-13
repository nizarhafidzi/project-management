<?php

namespace Modules\Project\Livewire;

use Livewire\Component;
use App\Services\AutodeskService;
use Illuminate\Support\Facades\Auth;

class AccFilePicker extends Component
{
    public $projectId; // ACC Project ID
    public $hubId;
    public $currentFolderId = null;
    public $items = []; // List of Folders/Files
    public $breadcrumbs = [];

    public $readyToLoad = false;

    public function mount($projectId, $hubId)
    {
        $this->projectId = $projectId;
        $this->hubId = $hubId;
        $this->currentFolderId = null;
        $this->breadcrumbs = [['id' => null, 'name' => 'Project Root']];
    }

    public function loadData()
    {
        $this->readyToLoad = true;
        $this->loadTopFolders();
    }

    public function loadTopFolders()
    {
        if (!$this->readyToLoad) return;

        try {
            $service = app(AutodeskService::class);
            $this->items = $service->getTopFolders(Auth::user(), $this->hubId, $this->projectId);
        } catch (\Exception $e) {
            $this->items = [];
            $this->dispatch('notify', type: 'error', content: 'Failed to load ACC folders: ' . $e->getMessage());
        }
    }

    public function openFolder($folderId, $folderName)
    {
        $this->currentFolderId = $folderId;
        
        // Push Breadcrumb if not already current
        if (empty($this->breadcrumbs) || end($this->breadcrumbs)['id'] !== $folderId) {
             $this->breadcrumbs[] = ['id' => $folderId, 'name' => $folderName];
        }
        
        try {
            $service = app(AutodeskService::class);
            $this->items = $service->getFolderContents(Auth::user(), $this->projectId, $folderId);
        } catch (\Exception $e) {
            $this->items = [];
            $this->dispatch('notify', type: 'error', content: 'Failed to open folder: ' . $e->getMessage());
        }
    }

    public function navigateBreadcrumb($index)
    {
        // Slice breadcrumbs
        $target = $this->breadcrumbs[$index];
        $this->breadcrumbs = array_slice($this->breadcrumbs, 0, $index + 1);
        
        try {
            $service = app(AutodeskService::class);
            if ($target['id'] === null) {
                $this->items = $service->getTopFolders(Auth::user(), $this->hubId, $this->projectId);
            } else {
                $this->currentFolderId = $target['id'];
                $this->items = $service->getFolderContents(Auth::user(), $this->projectId, $this->currentFolderId);
            }
        } catch (\Exception $e) {
            $this->items = [];
            $this->dispatch('notify', type: 'error', content: 'Failed to navigate: ' . $e->getMessage());
        }
    }

    public function selectFile($urn, $name)
    {
        // Dispatch to parent (WbsManager)
        $this->dispatch('file-selected', urn: $urn, name: $name);
    }

    public function render()
    {
        return view('project::livewire.acc-file-picker');
    }
}
