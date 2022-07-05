@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}
    name="{{ $name }}"
>

@error($name)
<span class="text-red-400 text-xs" role="alert">
    {{ $message }}
</span>
@enderror
