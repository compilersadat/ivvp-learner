<!-- Navbar -->
     <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('home')}}">Ivp Learner Admin</a>
                    <a class="navbar-brand hidden" href="{{ route('home')}}">Ivp Learner Admin</a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="{{ asset('svg/admin.jpg')}}" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                            <!-- <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>

                            <a class="nav-link" href="#"><i class="fa fa- user"></i>Notifications <span class="count">13</span></a>

                            <a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a> -->

                            <form method="post" action="{{route('logout')}}">
                    @csrf
                    <button class="dropdown-item"  type="submit"><i class="fa fa-power -off"></i>Logout
                    </button>
                </form>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- /#header -->
