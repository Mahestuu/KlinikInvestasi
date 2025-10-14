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
        h2 {
            text-align: center;
        }

        h1 {
            margin-bottom: 0;
        }

        h2 {
            margin-top: 5px;
            color: #444;
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
        <h1>Pbumku {{ $pbumku->pbumku_id }}</h1>
        <h2>{{ $pbumku->nama }} ({{ $pbumku->dinas->nama ?? 'Tidak ada dinas' }})</h2>

        <p class="section-title">{{ __('Persyaratan Pbumku') }}:</p>

        <ol>
            @if ($pbumku->persyaratanPbumku->isNotEmpty())
                @foreach ($pbumku->persyaratanPbumku as $persyaratan)
                    @php
                        // Bersihkan nomor ganda dari persyaratan->nama
                        $cleanedNama = preg_replace('/^\d+\.\s/', '', trim($persyaratan->nama));
                    @endphp
                    <li>{{ $loop->iteration }}. {{ $cleanedNama }}
                        @if ($persyaratan->subpoin->isNotEmpty())
                            <ol type="a">
                                @foreach ($persyaratan->subpoin as $subpoin)
                                    <li>{{ $subpoin->item }}
                                        @if ($subpoin->details->isNotEmpty())
                                            <ul class="sub-level-1">
                                                @foreach ($subpoin->details as $detail)
                                                    @php
                                                        // Identifikasi header (Perorangan, Badan Hukum, dll.)
                                                        $headerPattern =
                                                            '/^(Perorangan|Badan Hukum|Badan Usaha)(?:\s*:)?/';
                                                        $isHeader = preg_match(
                                                            $headerPattern,
                                                            $detail->text,
                                                            $headerMatches,
                                                        );
                                                        $headerText = $isHeader ? $headerMatches[1] . ':' : null;

                                                        // Pisahkan item non-header
                                                        $cleanDetailText = $isHeader
                                                            ? preg_replace($headerPattern, '', trim($detail->text))
                                                            : $detail->text;
                                                        $items = array_filter(
                                                            array_map(
                                                                'trim',
                                                                preg_split('/(?<!\w\.\s)-(?=\s)/', $cleanDetailText),
                                                            ),
                                                            fn($item) => !empty($item),
                                                        );

                                                        $detailFormattedItems = [];
                                                        if ($isHeader) {
                                                            $detailFormattedItems[] = [
                                                                'text' => $headerText,
                                                                'isHeader' => true,
                                                            ];
                                                        }
                                                        foreach ($items as $item) {
                                                            $isLetter = preg_match('/^[a-zA-Z]\.\s/', $item);
                                                            $isBullet = preg_match('/^-\s/', $item);

                                                            $itemText = $isLetter || $isBullet ? $item : "- $item";
                                                            $detailFormattedItems[] = [
                                                                'text' => $itemText,
                                                                'isHeader' => false,
                                                            ];
                                                        }
                                                    @endphp
                                                    @foreach ($detailFormattedItems as $item)
                                                        <li
                                                            class="{{ $item['isHeader'] ? 'sub-level-1' : 'sub-level-2' }}">
                                                            {{ $item['text'] }}
                                                        </li>
                                                    @endforeach
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="empty-space"></div>
                                        @endif
                                    </li>
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
