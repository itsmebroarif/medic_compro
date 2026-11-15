<?php

namespace App\Livewire\Admin;

use App\Domains\Content\Models\Page;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Halaman Web'])]
class PageManager extends Component
{
    use WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public string $content = '';

    public string $meta_description = '';

    public bool $is_published = true;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/'],
            'content' => ['required', 'string', 'min:20'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'Judul halaman wajib diisi.',
            'content.required' => 'Isi halaman wajib diisi.',
            'content.min' => 'Isi halaman minimal 20 karakter.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $page = Page::query()->findOrFail($id);

        $this->editingId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->meta_description = (string) $page->meta_description;
        $this->is_published = $page->is_published;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'] ?: null,
            'is_published' => (bool) $validated['is_published'],
        ];

        if ($validated['slug'] !== '') {
            $data['slug'] = $validated['slug'];
        }

        if ($this->editingId) {
            Page::query()->findOrFail($this->editingId)->update($data);
            $this->flashMessage = 'Halaman berhasil diperbarui.';
        } else {
            if (! isset($data['slug'])) {
                unset($data['slug']);
            }
            Page::query()->create($data);
            $this->flashMessage = 'Halaman berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $page = Page::query()->findOrFail($id);
        $page->update(['is_published' => ! $page->is_published]);
        $this->flashMessage = $page->is_published ? 'Halaman diterbitkan.' : 'Halaman di-draft (tidak tampil di web).';
    }

    public function delete(int $id): void
    {
        Page::query()->findOrFail($id)->delete();
        $this->flashMessage = 'Halaman berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->meta_description = '';
        $this->is_published = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.page-manager', [
            'pages' => Page::query()->latest()->paginate(10),
        ]);
    }
}
