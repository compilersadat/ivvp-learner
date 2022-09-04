<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Login</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">
 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ asset('css/cs-skin-elastic.css')}}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <style>
        .dropdown:hover .dropdown-menu {
            display: block !important;
        }

        .banner {
            background-image:linear-gradient(rgba(0,0,0,0.1),rgba(0,0,0,0.1)), url('svg/bg1.png');
            background-size: cover;

        }
        .slider {
      
    }

    .slick-slide {
      margin: 0px 20px;
    }

    .slick-slide img {
      width: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
      color: black;
    }


   

    </style>
</head>
<body class="" style="background: #241332">
    <div class="container">
        <div class="text-center mt-5">
            <h3 class="text-white"><strong>IVVP ADMIN LOGIN</strong></h3>
           </div>
        <div class="row justify-content-md-center mt-3 ">
            <div class="col-md-5 mt-3">
                <!-- Default form login -->
            <form class="text-center card p-5" action="{{route('login')}}" method="POST" style="box-shadow:0px 1px 10px rgba(0, 0, 0, 0.1);">
                @csrf
                    <img src="{{ asset('svg/logo.png') }}" class="img m-auto pb-4" width="120">

                    <div class="form-group">

                    <input type="email" id="eamil" name="email" class="form-control mb-1" placeholder="E-mail" required>
                    @if ($errors->has('email'))
                    <span class="p-0 m-0 text-danger text-left mb-4"> <strong>{{ $errors->first('email') }}</strong></span>
                     @endif
        
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control mb-1"
                        placeholder="Password" required>
                    </div>

                    <!-- Sign in button -->
                    <button class="btn btn-custom btn-block " style="background: #241332;color:#fff;" type="submit">Sign in</button>

                   
                   
                </form>
                <!-- Default form login -->
            </div>
        </div>
    </div>

</body>

    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="{{ asset('js/main.js')}}"></script>

</body>
</html>
