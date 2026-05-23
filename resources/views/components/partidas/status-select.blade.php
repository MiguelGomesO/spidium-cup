@props(['selected' => \App\Models\Partida::STATUS_EM_ANDAMENTO, 'name' => 'status', 'id' => 'status'])

<label for="{{ $id }}" class="text-sm font-medium text-brand-ice/80 mb-2 block">Status da partida</label>
<select
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'select-touch']) }}
>
    @foreach (\App\Models\Partida::statuses() as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $selected) === $value)>{{ $label }}</option>
    @endforeach
</select>
@error('status')
    <p class="mt-2 text-sm text-brand-orange">{{ $message }}</p>
@enderror
