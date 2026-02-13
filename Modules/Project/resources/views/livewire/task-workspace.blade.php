<div class="tw-flex tw-h-[calc(100vh-64px)] tw-overflow-hidden">
    {{-- Left Side: 3D Viewer (70%) --}}
    <div class="tw-relative tw-w-[70%] tw-h-full tw-bg-gray-100 tw-border-r tw-border-gray-200">
        @if($this->viewerUrn)
            <div id="forgeViewer" class="tw-w-full tw-h-full tw-relative tw-z-0"></div>
            
            {{-- Loading Spinner --}}
            <div id="viewerLoader" class="tw-absolute tw-inset-0 tw-flex tw-flex-col tw-items-center tw-justify-center tw-bg-white tw-z-10">
                <div class="tw-animate-spin tw-rounded-full tw-h-12 tw-w-12 tw-border-b-2 tw-border-indigo-600 tw-mb-4"></div>
                <p class="tw-text-gray-500 tw-font-medium">Loading 3D Model...</p>
            </div>
        @else
            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-h-full tw-text-gray-400">
                <svg class="tw-w-16 tw-h-16 tw-mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <p class="tw-text-lg tw-font-medium">No 3D Model Available</p>
                <p class="tw-text-sm">This task does not have a linked model.</p>
            </div>
        @endif
    </div>

    {{-- Right Side: Information Sidebar (30%) --}}
    <div class="tw-w-[30%] tw-h-full tw-bg-white tw-flex tw-flex-col tw-shadow-xl tw-z-20">
        {{-- Section 1: Task Identity --}}
        <div class="tw-p-6 tw-border-b tw-border-gray-100 tw-bg-white">
            <div class="tw-flex tw-justify-between tw-items-start tw-mb-2">
                <div>
                    <h2 class="tw-text-xl tw-font-bold tw-text-gray-900 tw-leading-tight">{{ $task->name }}</h2>
                    <p class="tw-text-sm tw-text-gray-500 tw-mt-1 tw-flex tw-items-center tw-gap-1">
                        <svg class="tw-w-4 tw-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $task->user->name ?? 'Unassigned' }}
                    </p>
                </div>
                <span class="tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-medium 
                    {{ $task->total_progress >= 100 ? 'tw-bg-green-100 tw-text-green-800' : 'tw-bg-blue-100 tw-text-blue-800' }}">
                    {{ $task->total_progress }}%
                </span>
            </div>
            
            {{-- Progress Bar --}}
            <div class="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-2 tw-mt-3">
                <div class="tw-bg-blue-600 tw-h-2 tw-rounded-full tw-transition-all tw-duration-500" style="width: {{ $task->total_progress }}%"></div>
            </div>
        </div>

        {{-- Section 2: Analytics --}}
        <div class="tw-px-6 tw-py-4 tw-bg-gray-50 tw-border-b tw-border-gray-100">
            <div class="tw-flex tw-items-center tw-justify-between">
                <span class="tw-text-sm tw-font-medium tw-text-gray-600">Total Work Time</span>
                <span class="tw-text-lg tw-font-bold tw-text-gray-900">{{ $this->totalManHours }} <span class="tw-text-xs tw-font-normal tw-text-gray-500">Hours</span></span>
            </div>
        </div>

        {{-- Section 3: Daily Log History (Timeline) --}}
        <div class="tw-flex-1 tw-overflow-y-auto tw-p-6 tw-bg-white custom-scrollbar">
            <h3 class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-6">Work History</h3>
            
            <div class="tw-space-y-6 tw-relative">
                 @forelse($this->logs as $index => $log)
                    <div class="tw-relative tw-pl-8 tw-pb-6 {{ !$loop->last ? 'tw-border-l-2 tw-border-gray-200' : '' }} last:tw-pb-0">
                        {{-- Timeline Dot/Avatar --}}
                        <div class="tw-absolute -tw-left-[9px] tw-top-0 tw-bg-white">
                            <div class="tw-h-5 tw-w-5 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-[10px] tw-font-bold tw-text-white tw-shadow-sm tw-ring-2 tw-ring-white" 
                                style="background-color: {{ '#' . substr(md5($log->user->name), 0, 6) }}">
                                {{ substr($log->user->name, 0, 1) }}
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="tw-flex tw-flex-col">
                            <div class="tw-flex tw-justify-between tw-items-center tw-mb-1">
                                <span class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $log->user->name }}</span>
                                <span class="tw-text-xs tw-text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            
                            {{-- Bubble --}}
                            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-3 tw-border tw-border-gray-100 tw-relative group hover:tw-shadow-sm tw-transition-shadow">
                                <p class="tw-text-sm tw-text-gray-600 tw-leading-relaxed">
                                    {{ $log->notes ?: 'No description provided.' }}
                                </p>
                                
                                @if($log->progress_increment > 0)
                                    <div class="tw-mt-2 tw-flex tw-items-center tw-gap-2">
                                        <span class="tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-medium tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-100">
                                            +{{ $log->progress_increment }}% Progress
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tw-text-center tw-py-10">
                        <p class="tw-text-gray-400 tw-text-sm">No daily logs recorded for this task yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Autodesk Viewer Styles & Scripts --}}
    @push('styles')
        <link rel="stylesheet" href="https://developer.api.autodesk.com/modelderivative/v2/viewers/7.*/style.min.css" type="text/css">
    @endpush

    @push('scripts')
        <script src="https://developer.api.autodesk.com/modelderivative/v2/viewers/7.*/viewer3D.min.js"></script>
        <script>
            document.addEventListener('livewire:navigated', function () {
                initViewer();
            });

            // Initialize on first load if not navigated
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                initViewer();
            } else {
                document.addEventListener('DOMContentLoaded', initViewer);
            }

            function initViewer() {
                if (typeof Autodesk === 'undefined') {
                     console.error('Error: Autodesk Global not found. Script not loaded?');
                     return;
                }

                const token = '{{ $token }}';
                const burn = '{{ $this->viewerUrn }}'; 
                
                if (!token) {
                    console.error('Viewer Error: Missing Access Token');
                    document.getElementById('viewerLoader').innerHTML = 
                        '<div class="tw-text-center tw-p-4"><p class="tw-text-red-600 tw-font-bold">Error: Missing Autodesk Access Token.</p><p class="tw-text-sm tw-text-gray-500">Please check your system settings connection.</p></div>';
                    return;
                }

                if (!burn) {
                    console.error('Viewer Error: Missing URN');
                    return;
                }

                const options = {
                    env: 'AutodeskProduction',
                    accessToken: token,
                    api: '{{ $this->viewerApi }}'
                };

                Autodesk.Viewing.Initializer(options, function() {
                    const htmlDiv = document.getElementById('forgeViewer');
                    if (!htmlDiv) return;

                    // Clean up existing viewer if any (for SPA navigation)
                    if (window.viewer) {
                        try {
                             window.viewer.finish(); 
                        } catch(e) {
                            console.warn('Viewer finish error:', e);
                        }
                        window.viewer = null;
                        htmlDiv.innerHTML = '';
                    }

                    const viewer = new Autodesk.Viewing.GuiViewer3D(htmlDiv);
                    window.viewer = viewer; // Global for debugging

                    const startedCode = viewer.start();
                    if (startedCode > 0) {
                        console.error('Failed to create viewer: WebGL not supported. Code:', startedCode);
                        return;
                    }

                    const documentId = 'urn:' + burn;
                    Autodesk.Viewing.Document.load(documentId, onDocumentLoadSuccess, onDocumentLoadFailure);

                    function onDocumentLoadSuccess(doc) {
                        // Load the default viewable geometry into the viewer
                        const defaultModel = doc.getRoot().getDefaultGeometry();
                        viewer.loadDocumentNode(doc, defaultModel);
                        
                        // Hide loader when geometry is loaded
                        viewer.addEventListener(Autodesk.Viewing.GEOMETRY_LOADED_EVENT, function() {
                            const loader = document.getElementById('viewerLoader');
                            if (loader) loader.style.display = 'none';
                        });
                    }

                    function onDocumentLoadFailure(viewerErrorCode, viewerErrorMsg) {
                        console.error('onDocumentLoadFailure() - errorCode:' + viewerErrorCode + '\n- errorMessage:' + viewerErrorMsg);
                        const loader = document.getElementById('viewerLoader');
                        if (loader) {
                            loader.innerHTML = '<p class="tw-text-red-500 tw-font-medium">Failed to load model.</p>';
                        }
                    }
                });
            }
        </script>
    @endpush
</div>
