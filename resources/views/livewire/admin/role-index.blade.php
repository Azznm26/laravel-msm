<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-display">{{ __('manage.roles.title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('manage.roles.subtitle') }}</p>
        </div>
        <button wire:click="create" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>{{ __('manage.roles.add_button') }}</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-bold">{{ __('manage.roles.col_id') }}</th>
                        <th class="px-6 py-4 font-bold">{{ __('manage.roles.col_name') }}</th>
                        <th class="px-6 py-4 font-bold text-right">{{ __('manage.roles.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($roles as $role)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-slate-100">{{ $role->id }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                {{ $role->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button wire:click="edit({{ $role->id }})" class="p-2 text-amber-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <!-- Hindari menghapus super_admin secara tidak sengaja -->
                            @if($role->name !== 'super_admin')
                            <button wire:click="delete({{ $role->id }})" wire:confirm="{{ __('manage.roles.confirm_delete') }}" class="p-2 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                            {{ __('manage.roles.not_found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Modal Form (Tambah/Edit) -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 dark:bg-slate-950/80 backdrop-blur-sm">
        <div class="relative w-full max-w-md p-4">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        {{ $roleId ? __('manage.roles.modal_title_edit') : __('manage.roles.modal_title_add') }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg p-1.5 ml-auto inline-flex items-center">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <!-- Modal body -->
                <form wire:submit.prevent="store" class="p-4 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-900 dark:text-white">{{ __('manage.roles.field_name') }}</label>
                        <input type="text" wire:model="name" placeholder="{{ __('manage.roles.field_name_placeholder') }}"
                            class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-slate-900 dark:border-slate-600 dark:placeholder-slate-400 dark:text-white transition-colors" required>
                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-colors">
                            {{ __('manage.roles.submit') }}
                        </button>
                        <button type="button" wire:click="closeModal" class="text-slate-700 bg-slate-100 hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-300 rounded-xl border border-slate-200 text-sm font-bold px-5 py-2.5 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-600 transition-colors">
                            {{ __('manage.roles.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>