@extends('layouts.admin')

@section('admin_kicker', 'CMS Home')
@section('admin_title', 'Kelola Konten Home')

@section('admin_content')
    <form method="POST" action="{{ route('admin.home-content.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Konten Halaman Home</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Ubah judul, deskripsi, gambar, link, dan kontak yang tampil di halaman utama website client.
                    </p>
                </div>
                <button class="admin-btn-save px-4 py-2 text-[10px]" type="submit">Simpan Semua</button>
            </div>
        </div>

        @php
            $oldContents = old('contents', []);
            $activeSectionKey = $sections[0]['key'] ?? '';

            foreach ($sections as $candidateSection) {
                foreach ($candidateSection['fields'] as $candidateField) {
                    if ($errors->has('contents.'.$candidateField['key']) || $errors->has('uploads.'.$candidateField['key'])) {
                        $activeSectionKey = $candidateSection['key'];
                        break 2;
                    }
                }
            }
        @endphp

        <div class="sticky top-4 z-20 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-sm backdrop-blur">
            <div class="flex gap-2 overflow-x-auto pb-1" role="tablist" aria-label="Section CMS Home">
                @foreach ($sections as $section)
                    @php
                        $isActiveSection = $section['key'] === $activeSectionKey;
                    @endphp
                    <button
                        type="button"
                        role="tab"
                        aria-selected="{{ $isActiveSection ? 'true' : 'false' }}"
                        aria-controls="cms-section-{{ $section['key'] }}"
                        data-cms-section-tab="{{ $section['key'] }}"
                        class="{{ $isActiveSection ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-400 hover:bg-slate-50' }} shrink-0 rounded-xl border px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] transition"
                    >
                        {{ $section['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        @foreach ($sections as $section)
            @php
                $isActiveSection = $section['key'] === $activeSectionKey;
            @endphp

            <section
                id="cms-section-{{ $section['key'] }}"
                data-cms-section-panel="{{ $section['key'] }}"
                class="{{ $isActiveSection ? '' : 'hidden' }} rounded-2xl border border-slate-200 bg-white p-5"
                role="tabpanel"
            >
                <div class="flex flex-col gap-1 border-b border-slate-100 pb-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Section</p>
                    <h2 class="text-xl font-bold text-slate-900">{{ $section['label'] }}</h2>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ($section['fields'] as $field)
                        @php
                            $key = $field['key'];
                            $value = array_key_exists($key, $oldContents)
                                ? $oldContents[$key]
                                : ($values[$key] ?? ($field['default'] ?? ''));
                            $isTextarea = $field['type'] === \App\Models\HomeContent::TYPE_TEXTAREA;
                            $isImage = $field['type'] === \App\Models\HomeContent::TYPE_IMAGE;
                        @endphp

                        <div class="{{ $isTextarea ? 'md:col-span-2' : '' }}">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.15em] text-slate-500" for="content_{{ $key }}">
                                {{ $field['label'] }}
                            </label>

                            @if ($isTextarea)
                                <textarea
                                    id="content_{{ $key }}"
                                    name="contents[{{ $key }}]"
                                    rows="4"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6"
                                >{{ $value }}</textarea>
                            @else
                                <input
                                    id="content_{{ $key }}"
                                    name="contents[{{ $key }}]"
                                    class="h-11 w-full rounded-xl border border-slate-200 px-4 text-sm"
                                    value="{{ $value }}"
                                >
                            @endif

                            @if ($isImage)
                                <div class="mt-3 grid gap-3 sm:grid-cols-[120px_1fr] sm:items-start">
                                    <div class="h-24 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        @if ($value)
                                            <img class="h-full w-full object-cover" src="{{ $value }}" alt="Preview {{ $field['label'] }}">
                                        @endif
                                    </div>
                                    <div>
                                        <input
                                            type="file"
                                            name="uploads[{{ $key }}]"
                                            accept="image/*"
                                            class="block w-full rounded-xl border border-slate-200 px-3 py-2 text-xs text-slate-600"
                                        >
                                        <p class="mt-2 text-xs leading-5 text-slate-500">
                                            Isi URL/path gambar atau upload file baru. Upload akan mengganti nilai field ini.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky bottom-4 z-10 flex justify-end">
            <button class="admin-btn-save px-5 py-3 text-[10px] shadow-lg" type="submit">Simpan Semua</button>
        </div>
    </form>

    <script>
        (() => {
            const tabs = Array.from(document.querySelectorAll("[data-cms-section-tab]"));
            const panels = Array.from(document.querySelectorAll("[data-cms-section-panel]"));

            if (tabs.length === 0 || panels.length === 0) return;

            const activate = (sectionKey) => {
                tabs.forEach((tab) => {
                    const active = tab.dataset.cmsSectionTab === sectionKey;
                    tab.setAttribute("aria-selected", active ? "true" : "false");
                    tab.classList.toggle("border-slate-900", active);
                    tab.classList.toggle("bg-slate-900", active);
                    tab.classList.toggle("text-white", active);
                    tab.classList.toggle("border-slate-200", !active);
                    tab.classList.toggle("bg-white", !active);
                    tab.classList.toggle("text-slate-600", !active);
                    tab.classList.toggle("hover:border-slate-400", !active);
                    tab.classList.toggle("hover:bg-slate-50", !active);
                });

                panels.forEach((panel) => {
                    panel.classList.toggle("hidden", panel.dataset.cmsSectionPanel !== sectionKey);
                });
            };

            tabs.forEach((tab) => {
                tab.addEventListener("click", () => {
                    activate(tab.dataset.cmsSectionTab);
                });
            });
        })();
    </script>
@endsection
