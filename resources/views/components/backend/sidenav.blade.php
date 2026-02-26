<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link {{ request()->routeIs('backend.dashboard') ?
'active' : '' }}" href="{{ route('backend.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometeralt"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">Interface</div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
               data-bs-target="#collapseLayouts" aria-expanded="false" ariacontrols="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas facolumns"></i></div>
                Layouts
                <div class="sb-sidenav-collapse-arrow"><i class="fas faangle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" arialabelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="#">Static Navigation</a>
                    <a class="nav-link" href="#">Light Sidenav</a>
                </nav>
            </div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
               data-bs-target="#collapsePages" aria-expanded="false" ariacontrols="collapsePages">
                <div class="sb-nav-link-icon"><i class="fas fa-bookopen"></i></div>
                Pages
                <div class="sb-sidenav-collapse-arrow"><i class="fas faangle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" arialabelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion"
                     id="sidenavAccordionPages">
                    <a class="nav-link collapsed" href="#" data-bstoggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                       aria-controls="pagesCollapseAuth">
                        Authentication
                        <div class="sb-sidenav-collapse-arrow"><i class="fas
fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseAuth" arialabelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="#">Login</a>
                            <a class="nav-link" href="#">Register</a>
                            <a class="nav-link" href="#">Forgot Password</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="#" data-bstoggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false"
                       aria-controls="pagesCollapseError">
                        Error
                        <div class="sb-sidenav-collapse-arrow"><i class="fas
fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseError" arialabelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="#">401 Page</a>
                            <a class="nav-link" href="#">404 Page</a>
                            <a class="nav-link" href="#">500 Page</a>
                        </nav>
                    </div>
                </nav>
            </div>
            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fa-chartarea"></i></div>
                Charts
            </a>
            <a class="nav-link" href="#">
                <div class="sb-nav-link-icon"><i class="fas fatable"></i></div>
                Tables
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ auth()->user()->name ?? 'Start Bootstrap' }}
    </div>
</nav>
