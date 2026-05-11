<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Models\Campeonato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimeController extends Controller
{

    public function index()
    {
        $times = Time::all();
        return view('times.index', compact('times'));
    }

    public function create()
    {
        $campeonatos = Campeonato::all();
        return view('times.create', compact('campeonatos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
        ]);

        $data = $request->all();

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

        return redirect()->route('times.edit', $time->id);
    }

    public function edit(Time $time)
    {
        $campeonatos = Campeonato::all();
        $time->load('jogadores');
        return view('times.edit', compact('time', 'campeonatos'));
    }

    public function update(Request $request, Time $time)
    {
        foreach (['logo', 'uniforme', 'estadio'] as $campo) {
            if ($request->hasFile($campo)) {
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
                    'path' => $path
                ]);
            }
        }

        if ($request->filled('nome')) {
            $time->update([
                'nome' => $request->nome
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
