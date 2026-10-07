<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Admin Dashboard</title>
  @include('layouts.include.css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('layouts.include.navbar')
    @include('layouts.include.admin_sidebar')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">

      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

      </div>
    </div>
  </div>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  @include('layouts.include.footer')
</div>

@include('layouts.include.script')
</body>
</html>
