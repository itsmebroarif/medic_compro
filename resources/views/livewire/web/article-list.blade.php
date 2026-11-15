<div>
    <section class="page-header" style="background-image: linear-gradient(rgba(9,39,58,.8), rgba(9,39,58,.8)), url('{{ asset('generated/hero-hospital.jpg') }}'); background-size: cover; background-position: center; padding: 80px 0; text-align: center;">
        <div class="container">
            <h1 style="color: #fff; font-size: 2.4rem;">Artikel & Berita</h1>
            <p style="color: rgba(255,255,255,.8);"><a href="{{ route('home') }}" wire:navigate style="color: #ffc107; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; Artikel</p>
        </div>
    </section>

    <section style="padding: 50px 0 70px;">
        <div class="container">
            <div class="row g-3" style="margin-bottom: 26px;">
                <div class="col-lg-7">
                    <input type="text" class="form-control" placeholder="Cari artikel..." wire:model.live.debounce.300ms="search" aria-label="Cari artikel">
                </div>
                <div class="col-lg-5">
                    <select class="form-select" wire:model.live="category" aria-label="Filter kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                @forelse ($articles as $article)
                    @php $fallback = 'generated/articles/article-' . (($article->id % 6) + 1) . '.jpg'; @endphp
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="blog-one__single">
                            <div class="blog-one__img">
                                <img src="{{ $article->thumbnailUrl() ?? asset($fallback) }}" alt="{{ $article->title }}" loading="lazy" style="width: 100%; aspect-ratio: 16/10; object-fit: cover;">
                            </div>
                            <div class="blog-one__content">
                                <div class="blog-one__content-inner">
                                    <ul class="blog-one__meta list-unstyled">
                                        <li><a href="{{ route('articles.show', $article->slug) }}" wire:navigate><span class="fas fa-tag"></span>{{ $article->category?->name ?? 'Artikel' }}</a></li>
                                        <li><a href="{{ route('articles.show', $article->slug) }}" wire:navigate><span class="fas fa-calendar-alt"></span>{{ \App\Core\Helpers\DateHelper::formatDate($article->published_at) }}</a></li>
                                    </ul>
                                    <h3 class="blog-one__title"><a href="{{ route('articles.show', $article->slug) }}" wire:navigate>{{ $article->title }}</a></h3>
                                    <div class="blog-one__btn">
                                        <a href="{{ route('articles.show', $article->slug) }}" wire:navigate class="thm-btn">
                                            <span class="fas fa-arrow-right"></span> Baca Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-info">Belum ada artikel{{ $search || $category ? ' yang cocok dengan pencarian Anda' : '' }}.</div></div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $articles->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>
</div>
