    <head>
        @include('partials.head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @PwaHead <!-- PWA meta tags directive -->


    </head>
{{-- resources/views/components/layouts/offline.blade.php --}}
<div class="offline-layout bg-light text-dark p-4" style="min-height:100vh;">
  <div class="alert alert-danger text-center fw-bold fs-5" role="alert">
    ⚠️ You are currently offline. Limited features available.
  </div>

  <div class="offline-content">
    {{ $slot }}
  </div>

  <footer class="text-center mt-4 text-muted">
    <small>Data is locally cached and will sync once you're back online.</small>
  </footer>
</div>