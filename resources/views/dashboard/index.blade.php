@extends('layouts.app')
@section('content')
    @auth
        @unless(auth()->user()->hasVerifiedEmail())
            <div class="mb-6 flex items-center justify-between rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                <span>Confirme seu e-mail para garantir o acesso completo. Verifique sua caixa de entrada.</span>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="font-medium underline hover:text-amber-900">Reenviar link</button>
                </form>
            </div>
        @endunless
    @endauth

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <a href="{{ route('dashboard.customize') }}" class="text-sm text-indigo-600 hover:underline">Personalizar</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('leads.index') }}" class="bg-white shadow rounded-lg p-4 hover:shadow-md">
            <div class="text-sm text-gray-500">Leads</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['leads'] }}</div>
        </a>
        <a href="{{ route('clientes.index') }}" class="bg-white shadow rounded-lg p-4 hover:shadow-md">
            <div class="text-sm text-gray-500">Clientes</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['clientes'] }}</div>
        </a>
        <a href="{{ route('projetos.index') }}" class="bg-white shadow rounded-lg p-4 hover:shadow-md">
            <div class="text-sm text-gray-500">Projetos</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['projetos'] }}</div>
        </a>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Tarefas abertas</div>
            <div class="text-2xl font-bold text-gray-800">{{ $stats['tasks'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Minhas tarefas</h3>
                <div class="space-y-2">
                    @forelse($myTasks as $task)
                    <div class="flex justify-between items-center border rounded-md px-3 py-2">
                        <span class="text-sm">{{ $task->title }} <span class="text-xs text-gray-500">- {{ $task->project->name ?? '' }}</span></span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ $task->status }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Nenhuma tarefa atribuída.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Próximos eventos</h3>
                <div class="space-y-2">
                    @forelse($events as $event)
                    <div class="flex justify-between border-b py-2">
                        <span class="text-sm">{{ $event->title }}</span>
                        <span class="text-xs text-gray-500">{{ $event->start_at->format('d/m H:i') }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Nenhum evento.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Financeiro</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">A receber</span><span class="font-medium">R$ {{ number_format($financial['receber'], 2, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Recebido</span><span class="font-medium text-green-600">R$ {{ number_format($financial['recebido'], 2, ',', '.') }}</span></div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-gray-700 mb-3">Atividade recente</h3>
                <ol class="relative border-l border-gray-200 ml-3">
                    @foreach($timeline as $item)
                    <li class="mb-3 ml-4">
                        <div class="absolute -left-1.5 w-3 h-3 bg-indigo-500 rounded-full"></div>
                        <div class="text-sm font-medium text-gray-800">{{ $item->title }}</div>
                        <div class="text-xs text-gray-400">{{ $item->created_at->format('d/m H:i') }}</div>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    @if(! empty($widgets))
    <div class="mt-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Meus Widgets</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($widgets as $key)
                @include('dashboard.widget', ['key' => $key])
            @endforeach
        </div>
    </div>
    @endif
@endsection
