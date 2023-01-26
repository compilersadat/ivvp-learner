  <!-- Main Sidebar Container -->
   <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="">
                        <a href="{{ route('home')}}"><i class="menu-icon fa fa-laptop"></i>Dashboard </a>
                    </li>
                    <li>
                        <a href="{{ route('upload.index')}}"> <i class="menu-icon fa fa-file"></i>File Upload</a>
                    </li>
                    <li>
                        <a href="{{ route('slider.index')}}"> <i class="menu-icon fa fa-file"></i>Sliders</a>
                    </li>
                    <li>
                        <a href="{{ route('content.index')}}"> <i class="menu-icon fa fa-database"></i>Contents</a>
                    </li>
                    <li>
                        <a href="{{ route('packages.index')}}"> <i class="menu-icon fa fa-database"></i>Subscription Plans</a>
                    </li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
