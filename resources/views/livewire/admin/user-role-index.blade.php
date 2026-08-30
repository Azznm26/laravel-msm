<div>
    <!-- Header & Search -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-display">{{ __('manage.assign_role.title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('manage.assign_role.subtitle') }}</p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('manage.assign_role.search_placeholder') }}"
                class="bg-white border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-slate-800 dark:border-slate-700 dark:placeholder-slate-400 dark:text-white transition-colors">
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-bold">{{ __('manage.assign_role.col_user') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('manage.assign_role.col_current_role') }}</th>
                        <th class="px-6 py-4 font-bold text-right">{{ __('manage.assign_role.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 space-x-1">
                            @forelse($user->roles as $role)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                {{ $role->name }}
                            </span>
                            @empty
                            <span class="text-xs text-slate-400 italic">{{ __('manage.assign_role.no_role') }}</span>
                            @endforelse
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="edit({{ $user->id }})" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-bold transition-colors">
                                <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                <span>{{ __('manage.assign_role.manage_role_button') }}</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            {{ __('manage.assign_role.not_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Form (Kelola Role) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 dark:bg-slate-950/80 backdrop-blur-sm">
        <div class="relative w-full max-w-md p-4">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        {{ __('manage.assign_role.modal_title') }} <span class="text-blue-600 dark:text-blue-400">{{ $userName }}</span>
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg p-1.5 ml-auto inline-flex items-center">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <form wire:submit.prevent="updateRoles" class="p-4 space-y-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">{{ __('manage.assign_role.modal_description') }}</p>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach($roles as $role)
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 dark:border-slate-700 dark:hover:bg-slate-700/50 transition-colors">
                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                            <span class="ml-2 text-sm font-bold text-slate-900 dark:text-slate-300">{{ $role->name }}</span>
                        </label>
                        @endforeach
                    </div>

                    @if($roles->isEmpty())
                    <div class="p-3 bg-amber-50 text-amber-800 text-sm rounded-xl border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800">
                        {{ __('manage.assign_role.empty_role_warning') }}
                    </div>
                    @endif

                    <!-- Modal footer -->
                    <div class="flex items-center space-x-3 pt-4 mt-2 border-t border-slate-100 dark:border-slate-700">
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-colors">
                            {{ __('manage.assign_role.submit') }}
                        </button>
                        <button type="button" wire:click="closeModal" class="text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-300 rounded-xl border border-slate-200 text-sm font-bold px-5 py-2.5 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-600 transition-colors">
                            {{ __('manage.assign_role.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>