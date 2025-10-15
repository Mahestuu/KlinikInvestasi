<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Daftar Pbumku') }}</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            line-height: 1.6;
            margin: 40px;
            color: #222;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        h1 {
            margin-bottom: 0;
        }

        h2 {
            margin-top: 5px;
            color: #444;
        }

        h3 {
            margin-top: 5px;
            color: #666;
            font-size: 1.1em;
        }

        ol,
        ul {
            margin-left: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #b30000;
            margin-top: 20px;
        }

        .sub-level-1 {
            margin-left: 20px;
        }

        .sub-level-2 {
            margin-left: 40px;
            list-style-type: none;
        }

        .sub-level-2 li:before {
            content: "- ";
            margin-right: 5px;
        }

        .empty-space {
            margin-top: 20px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @foreach ($pbumkus as $pbumku)
        <h1>{{ $pbumku->dinas->nama ?? 'Tidak ada dinas' }}</h1>
        <h2>{{ $pbumku->nama }}</h2>
        <h3>{{ $pbumku->kbli->pluck('kode')->implode(', ') ?: 'Tidak ada KBLI terkait' }}</h3>

        <p class="section-title">{{ __('Persyaratan Pbumku') }}:</p>

        <ol>
            @if ($pbumku->persyaratanPbumku->isNotEmpty())
                @foreach ($pbumku->persyaratanPbumku as $persyaratan)
                    @php
                        // Bersihkan nomor ganda dari persyaratan->nama
                        $cleanedNama = preg_replace('/^\d+\.\s/', '', trim($persyaratan->nama));
                    @endphp
                    <li>{{ $loop->iteration }}. {{ $cleanedNama }}
                        @if ($persyaratan->subpoinPbumku->isNotEmpty())
                            <ol type="a">
                                @foreach ($persyaratan->subpoinPbumku as $subpoin)
                                    <li>{{ $subpoin->item }}</li>
                                @endforeach
                            </ol>
                        @endif
                    </li>
                @endforeach
            @else
                <div class="empty-space"></div>
            @endif
        </ol>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
