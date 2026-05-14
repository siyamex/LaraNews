@extends('layouts.admin')
@section('title', 'Media Library')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Library</h1>
    <label class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg cursor-pointer">
        Upload Files
        <input type="file" multiple accept="image/*,video/*,audio/*" class="hidden" id="upload-input">
    </label>
</div>

<div class="flex gap-6">
    {{-- Folder sidebar --}}
    @if(!empty($folders))
    <div class="w-48 shrink-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-3 space-y-1">
            <a href="{{ route('admin.media.index') }}" class="block px-3 py-2 rounded text-sm {{ !$folder ? 'bg-red-50 text-red-700 font-medium' : 'text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">All Files</a>
            @foreach($folders as $f)
            <a href="{{ route('admin.media.index', ['folder' => $f]) }}" class="block px-3 py-2 rounded text-sm {{ $folder === $f ? 'bg-red-50 text-red-700 font-medium' : 'text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' }}">{{ $f }}</a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Media Grid --}}
    <div class="flex-1">
        <div x-data="{ uploading: false, progress: 0 }" class="space-y-4">
            <div x-show="uploading" class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                <p class="text-sm text-blue-700 dark:text-blue-400">Uploading... <span x-text="progress + '%'"></span></p>
                <div class="mt-2 h-2 bg-blue-200 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 transition-all" :style="'width:'+progress+'%'"></div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($files as $item)
                <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    @if(str_starts_with($item->mime_type ?? '', 'image/'))
                        <img src="{{ Storage::url($item->path) }}" alt="{{ $item->filename }}" class="w-full h-24 object-cover">
                    @else
                        <div class="w-full h-24 flex items-center justify-center bg-gray-50 dark:bg-gray-700">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-2">
                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $item->filename }}</p>
                    </div>
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="{{ Storage::url($item->path) }}" target="_blank" class="p-1.5 bg-white rounded text-gray-700 hover:bg-gray-100 text-xs">View</a>
                        <button onclick="deleteMedia({{ $item->id }}, this)" class="p-1.5 bg-red-600 rounded text-white hover:bg-red-700 text-xs">Delete</button>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    <p>No media files yet. Upload your first file.</p>
                </div>
                @endforelse
            </div>

            @if($files instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div>{{ $files->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('upload-input')?.addEventListener('change', function(e) {
    const files = e.target.files;
    if (!files.length) return;
    const formData = new FormData();
    Array.from(files).forEach(f => formData.append('files[]', f));
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fetch('{{ route("admin.media.upload") }}', {method:'POST', body:formData})
        .then(r => r.json())
        .then(() => window.location.reload())
        .catch(console.error);
});

function deleteMedia(id, btn) {
    if (!confirm('Delete this file?')) return;
    fetch(`/admin/media/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
    }).then(() => btn.closest('.group').remove());
}
</script>
@endpush
@endsection
