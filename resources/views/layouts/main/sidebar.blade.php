<div id="wrapper">
    <ul class="navbar-nav bg-purple-600 sidebar sidebar-dark accordion h-full" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-crown"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Usuário premium</div>
        </a>

        <hr class="sidebar-divider my-0 bg-white">

        <li class="nav-item active">
            <a class="nav-link" href="{{url('dashboard')}}">
                <i class="fas fa-fw fa-home"></i>
                <span>Home</span>
            </a>
        </li>

        <hr class="sidebar-divider bg-white">

        <div class="sidebar-heading text-white">
            Opções
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-desktop"></i>

                <span>Setups gerados</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white  collapse-inner rounded">
                    <h6 class="collapse-header">Opções:</h6>
                    <a class="collapse-item" href="historico-conjuntos">Meus Setups</a>
                    <a class="collapse-item" href="cards.html">Dicas 4monkeys</a>
                </div>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Serviços</span>
            </a>
            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Opções:</h6>
                    <a class="collapse-item" href="utilities-color.html">Pergunta Técnica</a>

                </div>
            </div>
        </li>

        <hr class="sidebar-divider bg-white">

        <div class="sidebar-heading text-white">
            complemento
        </div>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Lojas</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Lojas</h6>
                    <a class="collapse-item" href="https://www.kabum.com.br/">Kabum</a>
                    <a class="collapse-item" href="https://patoloco.com.br/">Pato Loco</a>
                    <a class="collapse-item" href="premium/404.html">Terabyte</a>
                    <hr>
                    <div class="collapse-divider"></div>
                    <a class="collapse-header" href="404.html">De uma sugestão</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <div class="sidebar-card d-none d-lg-flex">
            <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
            <p class="text-center mb-2"><strong>Você ja é um parceiro</strong> aproveite todas as funcionalidades</p>
            <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Avaliar</a>
        </div>
    </ul>
</div>
