<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found - {{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Smooth floating animations for the ambient background blobs */
        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            33% { transform: translateY(-20px) scale(1.05); }
            66% { transform: translateY(15px) scale(0.95); }
            100% { transform: translateY(0px) scale(1); }
        }
        .anim-float {
            animation: float 12s ease-in-out infinite;
        }
        .anim-float-delayed {
            animation: float 14s ease-in-out 4s infinite;
        }
        .anim-float-slow {
            animation: float 16s ease-in-out 2s infinite;
        }
    </style>
</head>
<body class="tw-antialiased tw-bg-slate-50 tw-text-slate-900 tw-overflow-hidden tw-relative tw-min-h-screen">
    
    <!-- Abstract Ambient Background (Blobs) -->
    <div class="tw-absolute tw-inset-0 tw-z-0 tw-overflow-hidden tw-pointer-events-none tw-opacity-60">
        <div class="tw-absolute tw-top-[-10%] tw-left-[-10%] tw-w-[30rem] tw-h-[30rem] tw-bg-blue-400 tw-rounded-full tw-mix-blend-multiply tw-filter tw-blur-[100px] anim-float"></div>
        <div class="tw-absolute tw-top-[20%] tw-right-[-10%] tw-w-[35rem] tw-h-[35rem] tw-bg-indigo-300 tw-rounded-full tw-mix-blend-multiply tw-filter tw-blur-[120px] anim-float-delayed"></div>
        <div class="tw-absolute tw-bottom-[-20%] tw-left-[15%] tw-w-[40rem] tw-h-[40rem] tw-bg-sky-200 tw-rounded-full tw-mix-blend-multiply tw-filter tw-blur-[120px] anim-float-slow"></div>
    </div>

    <!-- Main Content Layout -->
    <div class="tw-relative tw-z-10 tw-min-h-screen tw-flex tw-flex-col tw-items-center tw-justify-center tw-px-6 tw-py-24">
        
        <div class="tw-text-center tw-max-w-3xl tw-mx-auto tw-flex tw-flex-col tw-items-center">
            
            <!-- Massive Gradient 404 Text -->
            <h1 class="tw-text-[8rem] md:tw-text-[12rem] tw-font-extrabold tw-leading-none tw-tracking-tighter tw-bg-clip-text tw-text-transparent tw-bg-gradient-to-br tw-from-blue-600 tw-via-indigo-600 tw-to-purple-600 tw-drop-shadow-lg tw-select-none">
                404
            </h1>
            
            <!-- Glassmorphism Content Card -->
            <div class="tw-mt-8 tw-bg-white/50 tw-backdrop-blur-xl tw-px-8 md:tw-px-12 tw-py-10 tw-rounded-3xl tw-shadow-xl tw-shadow-slate-200/50 tw-ring-1 tw-ring-white/60">
                <h2 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-tracking-tight tw-text-slate-800">
                    Oops! You've lost your way.
                </h2>
                <p class="tw-mt-4 tw-text-base md:tw-text-lg tw-leading-relaxed tw-text-slate-600 tw-max-w-md tw-mx-auto">
                    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>
                
                <div class="tw-mt-10 tw-flex tw-flex-col sm:tw-flex-row tw-items-center tw-justify-center tw-gap-4">
                    
                    <!-- Primary CTA: Back to Dashboard -->
                    <a href="{{ route('dashboard') }}" class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-full tw-bg-blue-600 tw-px-8 tw-py-3.5 tw-text-sm tw-font-semibold tw-text-white tw-shadow-lg tw-shadow-blue-500/30 tw-transition-all tw-duration-300 hover:tw-bg-blue-500 hover:tw-shadow-blue-500/50 hover:tw--translate-y-1 tw-w-full sm:tw-w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-h-5 tw-w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        Back to Dashboard
                    </a>
                    
                    <!-- Secondary CTA: Go Back -->
                    <button onclick="window.history.back()" class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-rounded-full tw-bg-white/80 tw-px-8 tw-py-3.5 tw-text-sm tw-font-semibold tw-text-slate-700 tw-shadow-sm tw-ring-1 tw-ring-inset tw-ring-slate-200/50 tw-transition-all tw-duration-300 hover:tw-bg-white hover:tw-text-slate-900 hover:tw-ring-slate-300 hover:tw--translate-y-1 tw-w-full sm:tw-w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-h-5 tw-w-5 tw-text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Go Back
                    </button>
                    
                </div>
            </div>
            
        </div>
        
    </div>
</body>
</html>
