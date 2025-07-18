@php
    $gambar = $gambar instanceof \Closure ? $gambar() : $gambar;
    \Illuminate\Support\Facades\Log::info('PREVIEW_GAMBAR', ['gambar' => $gambar]);
@endphp
@if ($gambar ?? false)
    <div style="margin-bottom: 1rem;">
        <img src="{{ $gambar }}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px;">
    </div>
@endif