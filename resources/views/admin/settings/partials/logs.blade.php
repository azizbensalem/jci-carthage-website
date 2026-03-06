<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Logs Système</h2>
    <p class="text-gray-600 mb-6">Consulter les logs de l'application pour le débogage et le suivi</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Log Files List -->
        <div class="lg:col-span-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Fichiers de logs</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($logFiles as $logFile)
                <a href="{{ route('admin.settings.index', ['tab' => 'logs', 'log' => $logFile['name']]) }}" 
                   class="block p-3 rounded-lg border {{ $selectedLog === $logFile['name'] ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }} transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $logFile['name'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ number_format($logFile['size'] / 1024, 2) }} KB
                            </p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <p>Aucun fichier de log trouvé</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Log Content -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $selectedLog ? 'Contenu: ' . $selectedLog : 'Sélectionnez un fichier de log' }}
                </h3>
                @if($selectedLog)
                <a href="{{ route('admin.settings.index', ['tab' => 'logs']) }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Fermer
                </a>
                @endif
            </div>
            
            @if($logContent)
            <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs text-green-400 font-mono whitespace-pre-wrap">{{ $logContent }}</pre>
            </div>
            @else
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">Sélectionnez un fichier de log pour voir son contenu</p>
            </div>
            @endif
        </div>
    </div>
</div>

