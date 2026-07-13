<?php

namespace App\Http\Controllers;

use App\Core\Models\Favorite;
use App\Core\Support\CompanyContext;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $items = Favorite::query()
            ->with('entity')
            ->where('user_id', auth()->id())
            ->where('company_id', app(CompanyContext::class)->id())
            ->latest()
            ->get();

        return view('favoritos.index', compact('items'));
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'entity_type' => ['required', 'string'],
            'entity_id' => ['required', 'integer'],
        ]);

        $userId = auth()->id();
        $companyId = app(CompanyContext::class)->id();

        $favorite = Favorite::query()
            ->where('user_id', $userId)
            ->where('company_id', $companyId)
            ->where('entity_type', $data['entity_type'])
            ->where('entity_id', $data['entity_id'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            $state = false;
            $message = 'Removido dos favoritos.';
        } else {
            Favorite::create([
                'company_id' => $companyId,
                'user_id' => $userId,
                'entity_type' => $data['entity_type'],
                'entity_id' => $data['entity_id'],
            ]);
            $state = true;
            $message = 'Adicionado aos favoritos.';
        }

        if ($request->wantsJson()) {
            return response()->json(['favorited' => $state]);
        }

        return back()->with('status', $message);
    }
}
