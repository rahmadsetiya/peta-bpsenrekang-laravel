<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Peta BPS Enrekang') }}</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/AdminLTE-3.2.0/dist/css/adminlte.min.css">
    <!-- Bootstrap + Select2 -->
    <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="hold-transition sidebar-mini">
    @inertia

    <!-- AdminLTE JS -->
    <script src="/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
    <script src="/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js"></script>
    <script src="/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
