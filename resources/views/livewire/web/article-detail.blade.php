<div>
    <section style="padding: 60px 0 80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @php $fallback = 'generated/articles/article-' . (($article->id % 6) + 1) . '.jpg'; @endphp
                    <img src="{{ $article->thumbnailUrl() ?? asset($fallback) }}" alt="{{ $article->title }}" style="width: 100%; aspect-ratio: 16/10; object-fit: cover; border-radius: 16px; margin-bottom: 24px;">

                    <ul class="list-unstyled d-flex flex-wrap gap-3 text-muted small mb-2">
                        <li><span class="fas fa-tag text-primary"></span> {{ $article->category?->name ?? 'Artikel' }}</li>
                        <li><span class="fas fa-user text-primary"></span> {{ $article->author?->name ?? 'Redaksi' }}</li>
                        <li><span class="fas fa-calendar-alt text-primary"></span> {{ \App\Core\Helpers\DateHelper::formatDate($article->published_at) }}</li>
                    </ul>
                    <h1 style="font-size: 2.2rem; font-weight: 800; line-height: 1.25;">{{ $article->title }}</h1>
                    <hr>
                    <div class="article-content" style="font-size: 1.02rem; line-height: 1.85; color: #343a40;">
                        {!! $article->content !!}
                    </div>
                    <hr>
                    <a href="{{ route('articles.index') }}" wire:navigate class="btn btn-outline-primary"><span class="fas fa-arrow-left"></span> Kembali ke Artikel</a>
                </div>

                <aside class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 22px; margin-bottom: 20px;">
                        <h5 style="font-weight: 700;">Tentang Kami</h5>
                        <p class="text-muted small">{{ \App\Domains\Content\Models\SiteSetting::get('about_short', 'Rumah sakit dengan layanan rawat jalan, rawat inap, dan IGD 24 jam.') }}</p>
                        <a href="{{ route('appointment') }}" wire:navigate class="thm-btn w-100"><span class="fas fa-calendar-check"></span> Janji Temu Online</a>
                    </div>

                    @if ($related->isNotEmpty())
                        <div class="card border-0 shadow-sm" style="border-radius: 16px; padding: 22px;">
                            <h5 style="font-weight: 700;">Artikel Terkait</h5>
                            <ul class="list-unstyled" style="margin-bottom: 0;">
                                @foreach ($related as $item)
                                    @php $relFallback = 'generated/articles/article-' . (($item->id % 6) + 1) . '.jpg'; @endphp
                                    <li class="d-flex gap-3 align-items-center" style="padding: 10px 0; border-bottom: 1px solid #eef0f3;">
                                        <img src="{{ $item->thumbnailUrl() ?? asset($relFallback) }}" alt="{{ $item->title }}" style="width: 84px; aspect-ratio: 16/10; object-fit: cover; border-radius: 10px;">
                                        <a href="{{ route('articles.show', $item->slug) }}" wire:navigate style="text-decoration: none; color: inherit; font-weight: 600; line-height: 1.35;">{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
</div>
