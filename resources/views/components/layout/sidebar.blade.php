@php
    $nav = \App\Core\Models\MenuItem::tree();
@endphp

<div class="flex h-full flex-col">
    <!-- Marca -->
    <div class="flex h-16 items-center gap-2.5 border-b border-app px-4">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-glow">
            <i data-lucide="leaf"></i>
        </span>
        <span x-show="!collapsed" class="text-lg font-extrabold tracking-tight text-neutral-900 dark:text-neutral-50">Agency<span class="text-primary-600 dark:text-primary-400">OS</span></span>

        @if(isset($mobile))
            <button @click="mobileOpen = false" class="ml-auto rounded-lg p-1.5 text-muted hover:bg-neutral-100 dark:hover:bg-neutral-800 lg:hidden">
                <i data-lucide="x"></i>
            </button>
        @endif
    </div>

    <!-- Navegação -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        @foreach($nav as $item)
            @if(!empty($item->children) && count($item->children))
                <div x-data="{ open: {{ $item->isActive() ? 'true' : 'false' }} }" class="space-y-1">
                    <button type="button" @click="open = !open"
                        class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        <i data-lucide="{{ $item->icon ?? 'circle' }}" class="shrink-0 text-muted"></i>
                        <span x-show="!collapsed" class="flex-1 truncate text-left">{{ $item->label }}</span>
                        <i data-lucide="chevron-down" x-show="!collapsed" class="text-muted transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open && !collapsed" x-collapse class="space-y-1 pl-9">
                        @foreach($item->children as $child)
                            <a href="{{ $child->href() }}"
                               class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-colors
                               {{ $child->isActive() ? 'bg-primary-100 font-medium text-primary-800 dark:bg-primary-900/30 dark:text-primary-200' : 'text-neutral-500 hover:bg-neutral-100 hover:text-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-200' }}">
                                @if($child->icon)<i data-lucide="{{ $child->icon }}" class="sm opacity-70"></i>@endif
                                <span class="truncate">{{ $child->label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ $item->href() }}"
                   class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors
                   {{ $item->isActive() ? 'bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-200' : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800' }}">
                    @if($item->isActive())
                        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 rounded-r-full bg-primary-600"></span>
                    @endif
                    <i data-lucide="{{ $item->icon ?? 'circle' }}" class="shrink-0 {{ $item->isActive() ? 'text-primary-700 dark:text-primary-300' : 'text-muted' }}"></i>
                    <span x-show="!collapsed" class="truncate">{{ $item->label }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Rodapé: recolher (apenas desktop) -->
    @unless(isset($mobile))
        <div class="border-t border-app p-3">
            <button type="button" @click="collapsed = !collapsed"
                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-500 transition-colors hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800">
                <i data-lucide="panel-left-close" x-show="!collapsed"></i>
                <i data-lucide="panel-left-open" x-show="collapsed" x-cloak></i>
                <span x-show="!collapsed">Recolher</span>
            </button>
        </div>
    @endunless
</div>
