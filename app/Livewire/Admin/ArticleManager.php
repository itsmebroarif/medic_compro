<?php

namespace App\Livewire\Admin;

use App\Core\Services\MediaUploadService;
use App\Domains\Content\Actions\PublishArticleAction;
use App\Domains\Content\Models\Article;
use App\Domains\Content\Models\ArticleCategory;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin', ['title' => 'Artikel / Berita'])]
class ArticleManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public string $status = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public ?int $category_id = null;

    public string $newCategoryName = '';

    public bool $showNewCategory = false;

    public string $content = '';

    public string $meta_description = '';

    public string $statusInput = 'draft';

    public ?string $published_at = null;

    public $thumbnail;

    public ?string $existingThumbnail = null;

    public string $flashMessage = '';

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'category_id' => ['nullable', 'exists:article_categories,id'],
            'content' => ['required', 'string', 'min:20'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'statusInput' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'title.min' => 'Judul minimal 5 karakter.',
            'content.required' => 'Isi artikel wajib diisi.',
            'content.min' => 'Isi artikel minimal 20 karakter.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
        ];
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $article = Article::query()->findOrFail($id);

        $this->editingId = $article->id;
        $this->title = $article->title;
        $this->category_id = $article->category_id;
        $this->content = $article->content;
        $this->meta_description = (string) $article->meta_description;
        $this->statusInput = $article->status;
        $this->published_at = $article->published_at?->format('Y-m-d\TH:i');
        $this->existingThumbnail = $article->thumbnail;
        $this->showModal = true;
    }

    public function addCategory(): void
    {
        $validated = $this->validate([
            'newCategoryName' => ['required', 'string', 'min:2', 'max:100'],
        ], ['newCategoryName.required' => 'Nama kategori wajib diisi.']);

        $category = ArticleCategory::query()->create(['name' => $validated['newCategoryName']]);

        $this->category_id = $category->id;
        $this->newCategoryName = '';
        $this->showNewCategory = false;
        $this->flashMessage = 'Kategori "'.$category->name.'" ditambahkan.';
    }

    public function save(MediaUploadService $media): void
    {
        $validated = $this->validate();

        $data = [
            'category_id' => $validated['category_id'] ?: null,
            'author_id' => $this->editingId ? Article::query()->findOrFail($this->editingId)->author_id : auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'meta_description' => $validated['meta_description'] ?: null,
            'status' => $validated['statusInput'],
            'published_at' => $validated['published_at'] ? Carbon::parse($validated['published_at']) : ($validated['statusInput'] === 'published' ? now() : null),
        ];

        if ($this->editingId) {
            $article = Article::query()->findOrFail($this->editingId);
            if ($this->thumbnail) {
                $media->delete($article->thumbnail);
                $data['thumbnail'] = $media->storeAsWebp($this->thumbnail, 'articles', maxWidth: 1400, quality: 80);
            }
            $article->update($data);
            $this->flashMessage = 'Artikel berhasil diperbarui.';
        } else {
            if ($this->thumbnail) {
                $data['thumbnail'] = $media->storeAsWebp($this->thumbnail, 'articles', maxWidth: 1400, quality: 80);
            }
            Article::query()->create($data);
            $this->flashMessage = 'Artikel berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function togglePublish(int $id, PublishArticleAction $action): void
    {
        $article = Article::query()->findOrFail($id);
        $action->execute($article, $article->status !== 'published');
        $this->flashMessage = $article->status === 'published' ? 'Artikel dikembalikan ke draft.' : 'Artikel diterbitkan.';
    }

    public function delete(int $id, MediaUploadService $media): void
    {
        $article = Article::query()->findOrFail($id);
        $media->delete($article->thumbnail);
        $article->delete();
        $this->flashMessage = 'Artikel berhasil dihapus.';
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->category_id = null;
        $this->newCategoryName = '';
        $this->showNewCategory = false;
        $this->content = '';
        $this->meta_description = '';
        $this->statusInput = 'draft';
        $this->published_at = null;
        $this->thumbnail = null;
        $this->existingThumbnail = null;
        $this->resetValidation();
    }

    public function render()
    {
        $articles = Article::query()
            ->with(['category', 'author'])
            ->when($this->search !== '', fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->status !== '', fn ($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.article-manager', [
            'articles' => $articles,
            'categories' => ArticleCategory::query()->orderBy('name')->get(),
        ]);
    }
}
