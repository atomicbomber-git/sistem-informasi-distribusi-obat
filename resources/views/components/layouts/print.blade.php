@props([
    "title" => "Default Title",
])

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
    >
    <meta http-equiv="X-UA-Compatible"
          content="ie=edge"
    >
    <title> {{ $title }} </title>
    <link rel="stylesheet"
          href="{{ asset("css/paper.css") }}"
    >
    <style>@page { size: A5 landscape }</style>
    <style>
        * {
            font-size: 8.2pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td.numeric, table th.numeric {
            text-align: end;
        }

        table td, table th {
            text-align: center;
            border: thin solid black;
            padding: 0.2rem;
        }

        header table {
            border: none;
        }

        header table td, header table th {
            text-align: left;
            border: none;
            padding: 0;
        }
    </style>
</head>

<body class="A5 landscape">
    {{ $slot }}
</body>
</html>