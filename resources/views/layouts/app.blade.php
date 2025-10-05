<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','COCOMO I')</title>
  <style>
    body{font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin:20px;}
    .container{max-width:1100px;margin:0 auto}
    .card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:16px;box-shadow:0 1px 2px rgba(0,0,0,.05)}
    .grid{display:grid;gap:12px}
    .grid-3{grid-template-columns:repeat(3,minmax(0,1fr))}
    label{font-weight:600;display:block;margin-bottom:6px}
    input,select{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px}
    .btn{display:inline-block;background:#111827;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none}
    .btn.secondary{background:#374151}
    table{width:100%;border-collapse:collapse}
    th,td{border-bottom:1px solid #e5e7eb;padding:8px;text-align:left}
    .muted{color:#6b7280}
    .ok{color:#065f46;font-weight:700}
    .err{color:#b91c1c}
  </style>
</head>
<body>
  <div class="container">
    <h1>@yield('title')</h1>
    @yield('content')
  </div>
</body>
</html>
