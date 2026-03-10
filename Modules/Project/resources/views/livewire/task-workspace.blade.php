<div class="tw-w-full tw-h-full tw-flex tw-font-sans" style="height: calc(100vh - 64px);">

    {{-- ══════════════════════════════════════════════════════════════════
         LEFT PANE — 3D Viewer / Context Area (2/3 width)
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="tw-w-2/3 tw-h-full tw-bg-gray-100 tw-border-r tw-border-gray-200 tw-relative tw-flex tw-items-center tw-justify-center">
        @if($this->viewerUrn)
            <div id="forgeViewer" class="tw-w-full tw-h-full tw-relative tw-z-0"></div>

            {{-- Loading Spinner --}}
            <div id="viewerLoader" class="tw-absolute tw-inset-0 tw-flex tw-flex-col tw-items-center tw-justify-center tw-bg-white tw-z-10">
                <div class="tw-mb-6">
                    <div class="tw-w-14 tw-h-14 tw-rounded-full tw-border-4 tw-border-gray-200 tw-animate-spin" style="border-top-color: #174D9D;"></div>
                </div>
                <p class="tw-text-sm tw-font-medium tw-text-gray-600">Loading 3D Model...</p>
                <p class="tw-text-xs tw-text-gray-400 tw-mt-1">This may take a moment</p>
            </div>
        @else
            {{-- Empty State / Placeholder --}}
            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center tw-px-8">
                <div class="tw-w-20 tw-h-20 tw-rounded-2xl tw-bg-gray-200/80 tw-flex tw-items-center tw-justify-center tw-mb-5">
                    <svg class="tw-w-10 tw-h-10 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="tw-text-base tw-font-semibold tw-text-gray-600 tw-mb-1">3D Viewer Context</p>
                <p class="tw-text-sm tw-text-gray-400 tw-max-w-xs">No model linked to this task. Link an ACC file from the WBS Manager to visualize the 3D model here.</p>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         RIGHT PANE — Task Details Sidebar (1/3 width)
    ══════════════════════════════════════════════════════════════════ --}}
    <div class="tw-w-1/3 tw-bg-white tw-p-6 tw-overflow-y-auto tw-flex tw-flex-col">

        {{-- Task Identity --}}
        <div class="tw-pb-5 tw-border-b tw-border-gray-200 tw-mb-5">
            <div class="tw-flex tw-items-start tw-justify-between tw-mb-3">
                <div class="tw-flex-1 tw-min-w-0 tw-mr-3">
                    <h2 class="tw-text-lg tw-font-bold tw-text-gray-900 tw-leading-snug">{{ $task->name }}</h2>
                    <p class="tw-text-xs tw-text-gray-500 tw-mt-1 tw-flex tw-items-center tw-gap-1.5">
                        <svg class="tw-w-3.5 tw-h-3.5 tw-text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @if($task->users->count() > 0)
                            {{ $task->users->pluck('name')->join(', ') }}
                        @else
                            Unassigned
                        @endif
                    </p>
                </div>
                <span class="tw-flex-shrink-0 tw-px-2.5 tw-py-1 tw-rounded-lg tw-text-xs tw-font-bold
                    {{ $task->total_progress >= 100 ? 'tw-bg-green-50 tw-text-green-700 tw-ring-1 tw-ring-green-200' : 'tw-text-white' }}"
                    @if($task->total_progress < 100) style="background-color: #174D9D;" @endif>
                    {{ $task->total_progress }}%
                </span>
            </div>

            {{-- Progress Bar --}}
            <div class="tw-w-full tw-bg-gray-100 tw-rounded-full tw-h-2 tw-overflow-hidden">
                <div class="tw-h-2 tw-rounded-full tw-transition-all tw-duration-500" style="width: {{ $task->total_progress }}%; background-color: #174D9D;"></div>
            </div>
        </div>

        {{-- Task Stats --}}
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-mb-5">
            {{-- Weight --}}
            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-3 tw-border tw-border-gray-100">
                <p class="tw-text-[10px] tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-1">Weight</p>
                <p class="tw-text-lg tw-font-bold tw-text-gray-900">{{ $task->weight ?? '—' }}</p>
            </div>
            {{-- Total Work Time --}}
            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-3 tw-border tw-border-gray-100">
                <p class="tw-text-[10px] tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-1">Work Time</p>
                <p class="tw-text-lg tw-font-bold tw-text-gray-900">{{ $this->totalManHours }} <span class="tw-text-xs tw-font-normal tw-text-gray-400">hrs</span></p>
            </div>
        </div>

        {{-- Daily Progress Input --}}
        <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-border tw-border-gray-100 tw-mb-5">
            <p class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-mb-3">Daily Progress</p>
            <div class="tw-flex tw-items-center tw-gap-2">
                <div class="tw-flex-1 tw-text-center">
                    <p class="tw-text-2xl tw-font-bold tw-text-gray-900">{{ $task->total_progress }}<span class="tw-text-sm tw-text-gray-400">%</span></p>
                    <p class="tw-text-[10px] tw-text-gray-400 tw-mt-0.5">Current</p>
                </div>
                <div class="tw-w-px tw-h-10 tw-bg-gray-200"></div>
                <div class="tw-flex-1 tw-text-center">
                    <p class="tw-text-2xl tw-font-bold" style="color: #174D9D;">{{ $this->logs->count() }}</p>
                    <p class="tw-text-[10px] tw-text-gray-400 tw-mt-0.5">Log Entries</p>
                </div>
            </div>
        </div>

        {{-- Work History Timeline --}}
        <div class="tw-flex-1 tw-overflow-y-auto">
            <h3 class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-4">Work History</h3>

            <div class="tw-space-y-0 tw-relative">
                @forelse($this->logs as $index => $log)
                    <div class="tw-relative tw-pl-7 tw-pb-5 {{ !$loop->last ? 'tw-border-l-2 tw-border-gray-100' : '' }} tw-ml-1.5">
                        {{-- Timeline Dot --}}
                        <div class="tw-absolute -tw-left-[7px] tw-top-0.5">
                            <div class="tw-w-3 tw-h-3 tw-rounded-full tw-ring-4 tw-ring-white" style="background-color: #174D9D;"></div>
                        </div>

                        {{-- Content --}}
                        <div class="tw-ml-1">
                            <div class="tw-flex tw-items-center tw-justify-between tw-mb-0.5">
                                <span class="tw-text-xs tw-font-semibold tw-text-gray-800">{{ $log->user->name }}</span>
                                <span class="tw-text-[10px] tw-text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-2.5 tw-border tw-border-gray-100 hover:tw-shadow-sm tw-transition-shadow">
                                <p class="tw-text-xs tw-text-gray-600 tw-leading-relaxed">
                                    {{ $log->notes ?: 'No description provided.' }}
                                </p>
                                @if($log->progress_increment > 0)
                                    <span class="tw-inline-flex tw-items-center tw-mt-1.5 tw-px-1.5 tw-py-0.5 tw-rounded tw-text-[10px] tw-font-medium tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-100">
                                        +{{ $log->progress_increment }}% Progress
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tw-text-center tw-py-10">
                        <svg class="tw-mx-auto tw-h-8 tw-w-8 tw-text-gray-200 tw-mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="tw-text-xs tw-text-gray-400">No daily logs recorded yet.</p>
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
                    window.viewer = viewer;

                    const startedCode = viewer.start();
                    if (startedCode > 0) {
                        console.error('Failed to create viewer: WebGL not supported. Code:', startedCode);
                        return;
                    }

                    const documentId = 'urn:' + burn;
                    Autodesk.Viewing.Document.load(documentId, onDocumentLoadSuccess, onDocumentLoadFailure);

                    function onDocumentLoadSuccess(doc) {
                        const defaultModel = doc.getRoot().getDefaultGeometry();
                        viewer.loadDocumentNode(doc, defaultModel);

                        viewer.addEventListener(Autodesk.Viewing.GEOMETRY_LOADED_EVENT, function() {
                            const loader = document.getElementById('viewerLoader');
                            if (loader) loader.style.display = 'none';
                        });
                    }

                    function onDocumentLoadFailure(viewerErrorCode, viewerErrorMsg) {
                        console.error('onDocumentLoadFailure() - errorCode:' + viewerErrorCode + '\n- errorMessage:' + viewerErrorMsg);
                        const loader = document.getElementById('viewerLoader');
                        if (loader) {
                            loader.innerHTML = '<div class="tw-text-center tw-p-4"><p class="tw-text-red-600 tw-font-semibold">Failed to load model.</p><p class="tw-text-xs tw-text-gray-400 tw-mt-1">Error: ' + viewerErrorMsg + '</p></div>';
                        }
                    }
                });
            }
        </script>
    @endpush
</div>
