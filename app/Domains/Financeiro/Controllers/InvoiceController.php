<?php

namespace App\Domains\Financeiro\Controllers;

use App\Domains\Cliente\Models\Cliente;
use App\Domains\Financeiro\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with('client')->latest()->paginate(20);
        $totals = [
            'aberto' => Invoice::where('status', '!=', 'paid')->sum('total'),
            'pago' => Invoice::where('status', 'paid')->sum('total'),
        ];

        return view('financeiro.index', compact('invoices', 'totals'));
    }

    public function create(): View
    {
        $clientes = Cliente::all();

        return view('financeiro.create', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clientes,id'],
            'number' => ['required', 'string', 'unique:invoices,number'],
            'status' => ['nullable', 'string'],
            'issued_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'subtotal' => ['nullable', 'numeric'],
            'tax' => ['nullable', 'numeric'],
            'total' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        Invoice::create($data);

        return redirect()->route('financeiro.index')->with('status', 'Fatura criada.');
    }
}
