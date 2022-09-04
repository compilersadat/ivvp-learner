<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Metro</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="{{url('cli/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <!-- Material Design Bootstrap -->
    <link href="{{url('cli/css/mdb.min.css')}}" rel="stylesheet">

</head>

<body>
<nav class="navbar navbar-dark info-color-dark ">
    <a class="navbar-brand " href="#">Metro</a>
    <ul class="navbar-nav ml-auto nav-flex-icons">
        <li class="nav-item">
            <a class="nav-link waves-effect waves-light " id="search">
                <i class="fas fa-search"></i>
            </a>
        </li>

        <form method="POST" action="">
                    @csrf
                    <a href="{{ route('client.logout') }}" id="logout_link" class="dropdown-item text-white">Logout<i class="fa fa-sign-out"></i></a>
                </form>


    </ul>

</nav>

<div class="z-depth-2 p-3 white d-none" style="z-index: 1;position: absolute;width: 100%;top:0" id="search-container">
    <p style="float: right" id="exit"><i class="fas fa-times"></i></p>
    <div class="md-form">
        <input type="text" id="query" class="form-control">
        <label for="form1">Enter Station Name</label>
    </div>

    <div class="result">

    </div>
</div>

 
<!-- Start your project here-->
@yield('content')
<!-- Start your project here-->

<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="{{asset('cli/js/jquery-3.4.1.min.js')}}"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="{{asset('cli/js/popper.min.js')}}"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="{{asset('cli/js/bootstrap.min.js')}}"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="{{asset('cli/js/mdb.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

<script>
    $(document).ready(function(){
        $("#search").click(function(){
            $("#search-container").removeClass('d-none');
        });
        $("#exit").click(function(){
            $("#search-container").addClass('d-none');
        });
        $("#query").keyup(function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                /* the route pointing to the post function */
                url: '../public/search',
                type: 'POST',
                /* send the csrf-token and the input to the controller */
                data: {_token: CSRF_TOKEN, search:$("#query").val()},

                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    $(".result").html(data);
                }
            });
        });
        @yield('js')
    });
</script>



</body>

</html>
