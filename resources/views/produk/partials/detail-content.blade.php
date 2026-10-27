@push('styles')
    <link rel="stylesheet" href="{{ asset('css/produk-detail.css') }}">
@endpush

<div class="produk-container">

    <div class="produk-card">
        <div class="row g-4">

            {{-- Foto dan galeri produk --}}
            @php
                $daftarFoto = $produk->fotoProduk;
                $jumlahFoto = $daftarFoto->count();
                $sliderId = 'slider-produk-' . $produk->id;
            @endphp

            <div class="col-12 col-md-6">

                @if ($jumlahFoto > 0)

                    <div class="produk-slider" id="{{ $sliderId }}">

                        @foreach ($daftarFoto as $index => $foto)
                            <img
                                src="{{ asset('storage/' . $foto->foto) }}"
                                alt="{{ $produk->nama_produk }} - Foto {{ $index + 1 }}"
                                class="produk-foto produk-slide"
                                data-slide="{{ $index }}"
                                @if ($index !== 0) hidden @endif
                            >
                        @endforeach

                        @if ($jumlahFoto > 1)

                            <button
                                type="button"
                                class="produk-slider-prev"
                                data-arah="-1"
                                aria-label="Foto sebelumnya"
                            >
                                &#10094;
                            </button>

                            <button
                                type="button"
                                class="produk-slider-next"
                                data-arah="1"
                                aria-label="Foto berikutnya"
                            >
                                &#10095;
                            </button>

                            <div class="produk-slider-indikator" aria-live="polite">
                                <span class="produk-slide-aktif">1</span>
                                / {{ $jumlahFoto }}
                            </div>

                        @endif

                    </div>

                @else

                    <div class="produk-placeholder">
                        Foto produk belum tersedia
                    </div>

                @endif

            </div>

            {{-- Informasi produk --}}
            <div class="col-12 col-md-6 produk-info">

                <h1>{{ $produk->nama_produk }}</h1>

                <p class="produk-kategori">
                    Kategori:
                    {{ $produk->kategoriProduk?->nama_kategori ?? '-' }}
                </p>

                {{-- Pilihan harga --}}
                <div class="produk-harga">

                    @forelse ($produk->hargaProduk->sortBy('harga_min') as $pilihan)

                        <div class="produk-harga-item">

                            <div class="produk-harga-nilai">
                                @if ($pilihan->harga_min !== null)

                                    Rp {{ number_format($pilihan->harga_min, 0, ',', '.') }}

                                    @if (
                                        $pilihan->harga_max !== null &&
                                        $pilihan->harga_max > $pilihan->harga_min
                                    )
                                        – Rp {{ number_format($pilihan->harga_max, 0, ',', '.') }}
                                    @endif

                                @else

                                    <span class="produk-harga-kosong">
                                        Hubungi UMKM untuk informasi harga
                                    </span>

                                @endif
                            </div>

                            <div class="produk-harga-satuan">

                                @if ($pilihan->satuan)

                                    Untuk
                                    {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}
                                    {{ $pilihan->satuan }}

                                @else

                                    Jumlah:
                                    {{ rtrim(rtrim(number_format($pilihan->jumlah_satuan, 2, ',', '.'), '0'), ',') }}

                                @endif

                            </div>

                        </div>

                    @empty

                        <span class="produk-harga-kosong">
                            Hubungi UMKM untuk informasi harga
                        </span>

                    @endforelse

                    <p class="produk-harga-catatan">
                        Harga dapat berubah sewaktu-waktu.
                    </p>

                </div>

                {{-- Deskripsi produk --}}
                <section class="produk-section">

                    <h3>Deskripsi Produk</h3>

                    <p class="produk-deskripsi">
                        {{ $produk->deskripsi ?: 'Deskripsi produk belum tersedia.' }}
                    </p>

                </section>

                {{-- UMKM penyedia --}}
                <section class="produk-section">

                    <h3>UMKM Penyedia</h3>

                    @if ($produk->umkm)

                        <p class="produk-umkm-nama">
                            {{ $produk->umkm->nama_umkm }}
                        </p>

                        <a
                            href="{{ ($isAdminView ?? false)
                                ? route('admin.umkm.show', $produk->umkm->id)
                                : route('umkm.show', $produk->umkm->id) }}"
                            class="btn produk-link"
                        >
                            Lihat Profil UMKM
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                    @else

                        <p class="produk-umkm-nama produk-umkm-kosong">
                            Informasi UMKM belum tersedia.
                        </p>

                    @endif

                </section>

            </div>

        </div>
    </div>

</div>

@push('scripts')
    <script src="{{ asset('js/produk-detail.js') }}" defer></script>
@endpush