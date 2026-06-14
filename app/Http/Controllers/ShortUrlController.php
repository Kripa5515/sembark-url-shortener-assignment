<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;


class ShortUrlController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $urls = ShortUrl::with('company', 'creator')->latest()->get();
        } elseif ($user->isAdmin()) {
            $urls = ShortUrl::where('company_id', $user->company_id)->latest()->get();
        } elseif ($user->isMember()) {
            $urls = ShortUrl::where('company_id', $user->company_id)->where('created_by', $user->id)->latest()->get();
        } else {
            $urls = collect();
        }
        return view('short-urls.index', compact('urls'));
    }

    public function create()
    {
       $user = auth()->user();
               
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        return view('short-urls.create');
    }

    public function store(Request $request)
    {
       $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'original_url' => ['required', 'url']
        ]);

        $companyId = $user->isSuperAdmin() ? null : $user->company_id;

        ShortUrl::create([
            'company_id'   => $companyId,
            'created_by'   => $user->id,
            'original_url' => $request->original_url,
            'short_code'   => Str::random(8)
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL generated successfully.');
    }


    public function redirect($code)
    {
        $url = ShortUrl::where('short_code', $code)->firstOrFail();
        return redirect()->away($url->original_url);
    }
}
