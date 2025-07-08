<footer class="footer py-4 bg-white shadow-sm mt-auto" style="font-size: 0.875rem; border-top: 1px solid #e5e5e5;">
  <style>
    .footer a {
      color: #6c757d;
      transition: color 0.2s ease;
    }

    .footer a:hover {
      text-decoration: underline;
      color: #4c8d3d !important;
    }

    @media (max-width: 768px) {
      .footer .text-md-start {
        text-align: center !important;
      }

      .footer .justify-content-md-end {
        justify-content: center !important;
      }
    }
  </style>

  <div class="container-fluid">
    <div class="row align-items-center justify-content-between">
      <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
        <span class="text-muted small">
          ©
          <script>document.write(new Date().getFullYear())</script>
          — <strong>{{ config('app.name', 'Padi Kita Dashboard') }}</strong>. All rights reserved.
        </span>
      </div>

      <div class="col-md-6">
        <ul class="nav justify-content-center justify-content-md-end">
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-muted small">Tentang Kami</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-muted small">Kontak</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link px-2 text-muted small">Kebijakan Privasi</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</footer>