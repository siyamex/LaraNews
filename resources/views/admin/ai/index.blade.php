@extends('layouts.admin')
@section('title', 'AI Writer')
@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">AI Article Generator</h1>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6" x-data="aiWriter()">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Article Topic / Headline</label>
                <input type="text" x-model="topic" placeholder="e.g. Maldives General Election 2024 Results"
                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language</label>
                    <select x-model="locale" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        <option value="en">English</option>
                        <option value="dv">Dhivehi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tone</label>
                    <select x-model="tone" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        <option value="neutral">Neutral</option>
                        <option value="formal">Formal</option>
                        <option value="conversational">Conversational</option>
                    </select>
                </div>
            </div>

            <button @click="generate()" :disabled="loading"
                    class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium flex items-center gap-2 disabled:opacity-50">
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span x-text="loading ? 'Generating...' : 'Generate Article'"></span>
            </button>
        </div>

        <div x-show="result" class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <pre class="whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300" x-text="result"></pre>
            <div class="mt-4 flex gap-3">
                <button @click="createPost()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
                    Create Post from This
                </button>
                <button @click="result=''" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">
                    Clear
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function aiWriter() {
    return {
        topic: '',
        locale: 'en',
        tone: 'neutral',
        loading: false,
        result: '',
        async generate() {
            if (!this.topic) return;
            this.loading = true;
            try {
                const res = await fetch('{{ route("admin.ai.generate-article") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({topic: this.topic, locale: this.locale, tone: this.tone})
                });
                const data = await res.json();
                this.result = data.content || data.error || 'No content generated.';
            } catch(e) {
                this.result = 'Error: ' + e.message;
            }
            this.loading = false;
        },
        createPost() {
            window.location.href = '{{ route("admin.posts.create") }}';
        }
    }
}
</script>
@endpush
@endsection
