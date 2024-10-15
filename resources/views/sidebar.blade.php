<!-- Sidebar -->
<div class="sidebar sidebar-style-2"  data-background-color="dark">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-section">
                                <span class="sidebar-mini-icon">
                                    <i class="fa fa-ellipsis-h"></i>
                                </span>
                    <h4 class="text-section">Opções</h4>
                </li>
                <li class="{{ request()->routeIs('index.*') ? 'nav-item active' : 'nav-item' }}">
                    <a href="/">
                        <i class="fas fa-home"></i>
                        <p>Início</p>
                    </a>
                </li>
                <li class="{{ request()->routeIs('clientes.*') ? 'nav-item active' : 'nav-item' }}">
                    <a href="/clientes">
                        <i class="fas fa-users"></i>
                        <p>Clientes</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
