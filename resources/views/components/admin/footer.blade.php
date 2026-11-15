{{-- Footer admin --}}
@php
    use App\Domains\Content\Models\SiteSetting;
    $hospitalName = SiteSetting::get('hospital_name', config('app.name'));
@endphp
<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">{{ $hospitalName }}</div>
    <strong>Copyright &copy; {{ date('Y') }} {{ $hospitalName }}.</strong> Dikembangkan oleh <a href="#" class="text-decoration-none">Arif Permana Putrasuryana</a>.
</footer>
