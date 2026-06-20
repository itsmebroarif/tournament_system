<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; padding: 0; }
        body {
            font-family: 'figtree', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        .certificate {
            width: 800px;
            height: 565px;
            position: relative;
            border: 20px solid
                @if($rankKey === 'juara_1') #D4AF37
                @elseif($rankKey === 'juara_2') #A0A0A0
                @elseif($rankKey === 'juara_3') #CD7F32
                @else #1E40AF
                @endif;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }
        .certificate-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            opacity: 0.05;
            background-image: radial-gradient(circle, #000 1px, transparent 1px);
            background-size: 20px 20px;
        }
        .content { position: relative; z-index: 1; }
        h1 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #6B7280;
            margin-bottom: 20px;
        }
        .title {
            @if($rankKey === 'juara_1') font-size: 52px; color: #D4AF37;
            @elseif($rankKey === 'juara_2') font-size: 48px; color: #A0A0A0;
            @elseif($rankKey === 'juara_3') font-size: 48px; color: #CD7F32;
            @else font-size: 36px; color: #1E40AF;
            @endif
            font-weight: 900;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 18px;
            color: #4B5563;
            margin-bottom: 30px;
        }
        .name {
            font-size: 42px;
            font-weight: 700;
            @if($rankKey === 'juara_1') color: #D4AF37;
            @elseif($rankKey === 'juara_2') color: #4B5563;
            @elseif($rankKey === 'juara_3') color: #CD7F32;
            @else color: #1E40AF;
            @endif
            margin-bottom: 15px;
        }
        .competition-name {
            font-size: 24px;
            color: #374151;
            margin-bottom: 40px;
        }
        .date {
            font-size: 14px;
            color: #9CA3AF;
        }
        .seal {
            margin-top: 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            @if($rankKey === 'juara_1') border: 3px solid #D4AF37;
            @elseif($rankKey === 'juara_2') border: 3px solid #A0A0A0;
            @elseif($rankKey === 'juara_3') border: 3px solid #CD7F32;
            @else border: 3px solid #1E40AF;
            @endif
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }
        .seal-inner {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            @if($rankKey === 'juara_1') color: #D4AF37;
            @elseif($rankKey === 'juara_2') color: #A0A0A0;
            @elseif($rankKey === 'juara_3') color: #CD7F32;
            @else color: #1E40AF;
            @endif
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-bg"></div>
        <div class="content">
            <h1>Kafeinarts Management Tools</h1>
            <div class="title">{{ $rank }}</div>
            <div class="subtitle">Sertifikat ini diberikan kepada</div>
            <div class="name">{{ $name }}</div>
            <div class="competition-name">Atas partisipasinya dalam perlombaan <strong>{{ $competition }}</strong></div>
            <div class="date">{{ $date }}</div>
            <div class="seal">
                <div class="seal-inner">Kafeinarts<br>Management<br>Tools</div>
            </div>
        </div>
    </div>
</body>
</html>
