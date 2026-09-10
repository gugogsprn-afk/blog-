<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{dict.404}}</title>
    <style>
        @font-face {
            font-family: 'libel';
            src: url('/css/fonts/libel-suit-rg-webfont.woff2') format('woff2');
        }
        body {
            font-family: libel, sans-serif;
            margin: 0;
            padding: 0;
            font-size: clamp(20px, 4vw, 30px);
            letter-spacing: 7px;
            text-align: center;
        }
        .not-found {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 10px;
            height: 100dvh;
            padding: 0 20px;
        }
        .not-found__img {
            display: block;
            max-width: 100%;
        }
        .not-found__link {
            display: block;
            text-decoration: none;
            color: #fff;
            background-color: #f36e24;
            padding: 5px 25px;
            border-radius: 30px;
            text-align: center
        }
    </style>
</head>
<body>
    <div class="not-found">
        <img class="not-found__img" src="/page_files/images/404.webp" alt="page not found" />
        <p class="not-found__text">{{dict.404}}</p>
        <a class="not-found__link" href="/{{curlang}}">{{dict.GOTOHOMEPAGE}}</a>
    </div>
</body>
</html>
