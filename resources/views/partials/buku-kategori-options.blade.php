@foreach (\App\Models\Buku::kategoriList() as $kategori)
<option value="{{ $kategori }}">{{ $kategori }}</option>
@endforeach
