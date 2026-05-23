<?php

namespace App\Http\Controllers;

use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimeController extends Controller
{

    public function index()
    {
        $times = Time::query()
            ->withCount(['jogadores', 'campeonatos'])
            ->orderBy('nome')
            ->get();

        return view('times.index', compact('times'));
    }

    public function create()
    {
        return view('times.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'logo' => 'nullable|image|max:5120',
            'uniforme' => 'nullable|image|max:5120',
            'estadio' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['nome']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('times', 'public');
        }

        if ($request->hasFile('uniforme')) {
            $data['uniforme'] = $request->file('uniforme')->store('times', 'public');
        }

        if ($request->hasFile('estadio')) {
            $data['estadio'] = $request->file('estadio')->store('times', 'public');
        }

        $time = Time::create($data);

        return redirect()
            ->route('times.edit', $time)
            ->with('success', 'Time criado! Agora você pode adicionar jogadores.');
    }

    public function edit(Time $time)
    {
        $time->loadCount(['jogadores', 'campeonatos']);
        $time->load([
            'jogadores' => fn ($q) => $q->comEstatisticas()
                ->orderByRaw('numero IS NULL')
                ->orderBy('numero')
                ->orderBy('nome'),
        ]);

        return view('times.edit', compact('time'));
    }

    public function update(Request $request, Time $time)
    {
        foreach (['logo', 'uniforme', 'estadio'] as $campo) {
            if ($request->hasFile($campo)) {
                $request->validate([
                    $campo => 'image|max:5120',
                ]);
                if ($time->$campo) {
                    Storage::disk('public')->delete($time->$campo);
                }

                $path = $request->file($campo)->store('times', 'public');

                $time->update([
                    $campo => $path,
                ]);

                return response()->json([
                    'success' => true,
                    'campo' => $campo,
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                ]);
            }
        }

        if ($request->filled('nome')) {
            $request->validate([
                'nome' => 'required|string|max:255',
            ]);

            $time->update([
                'nome' => $request->nome,
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy(Time $time)
    {
        $time->delete();
        return back();
    }

    public function escalacao(Time $time)
    {
        $time->load('jogadores');
        return view('times.escalacao', compact('time'));
    }
}
