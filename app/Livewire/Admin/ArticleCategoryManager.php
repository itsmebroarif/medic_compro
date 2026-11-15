<?php

namespace App\Livewire\Admin;

use App\Domains\Content\Models\ArticleCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Kategori Artikel'])]
class ArticleCategoryManager extends Component
{
    use WithPagination;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $flashMessage = '';

    protected function rules(): array
    {
        return ['name' => ['required', 'string', 'min:2', 'max:100']];
    }

    public function create(): void
    {
        $this->reset(['editingId', 'name']);
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $category = ArticleCategory::query()->findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            ArticleCategory::query()->findOrFail($this->editingId)->update($validated);
            $this->flashMessage = 'Kategori berhasil diperbarui.';
        } else {
            ArticleCategory::query()->create($validated);
            $this->flashMessage = 'Kategori berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->reset(['editingId', 'name']);
    }

    public function delete(int $id): void
    {
        $category = ArticleCategory::query()->withCount('articles')->findOrFail($id);

        if ($category->articles_count > 0) {
            $this->flashMessage = 'Kategori tidak dapat dihapus karena masih dipakai '.$category->articles_count.' artikel.';

            return;
        }

        $category->delete();
        $this->flashMessage = 'Kategori berhasil dihapus.';
    }

    public function render()
    {
        return view('livewire.admin.article-category-manager', [
            'categories' => ArticleCategory::query()->withCount('articles')->orderBy('name')->paginate(10),
        ]);
    }
}
