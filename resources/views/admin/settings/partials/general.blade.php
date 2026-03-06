<div>
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Paramètres Généraux</h2>
    <p class="text-gray-600 mb-6">Configuration générale de l'application</p>

    <div class="space-y-6">
        <!-- Application Info -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de l'application</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nom de l'application</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ config('app.name') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Environnement</dt>
                    <dd class="mt-1 text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ config('app.env') === 'production' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ config('app.env') }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Version PHP</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ PHP_VERSION }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Version Laravel</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ app()->version() }}</dd>
                </div>
            </dl>
        </div>

        <!-- System Status -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">État du système</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Stockage</span>
                    <span class="text-sm font-medium text-gray-900">
                        @php
                            $totalSpace = disk_total_space(storage_path());
                            $freeSpace = disk_free_space(storage_path());
                            $usedSpace = $totalSpace - $freeSpace;
                            $usedPercent = ($usedSpace / $totalSpace) * 100;
                        @endphp
                        {{ number_format($usedPercent, 1) }}% utilisé
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $usedPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

