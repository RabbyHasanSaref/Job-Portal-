<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Job Portal</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{asset_custom('frontend/assets/img/logo.jpg')}}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{asset_custom('frontend/assets/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset_custom('frontend/assets/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{asset_custom('frontend/assets/css/bootstrap.min.css')}}" rel="stylesheet">


    <!-- Template Stylesheet -->
    <link href="{{asset_custom('frontend/assets/css/style.css')}}" rel="stylesheet">
</head>

<body style="margin: 0px">

   <div id="app">
       <app></app>
   </div>

<!-- JavaScript Libraries -->



<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset_custom('frontend/assets/lib/wow/wow.min.js')}}"></script>
<script src="{{asset_custom('frontend/assets/lib/easing/easing.min.js')}}"></script>
<script src="{{asset_custom('frontend/assets/lib/waypoints/waypoints.min.js')}}"></script>
<script src="{{asset_custom('frontend/assets/lib/owlcarousel/owl.carousel.min.js')}}"></script>

<!-- Template Javascript -->
<script src="{{asset_custom('frontend/assets/js/main.js')}}"></script>


   <script>window.baseUrl = '{{url('/')}}';</script>

   <script>
       window.publicPath = '{{ env('PUBLIC_PATH') }}';
       window.uploadPath = '{{ env('UPLOAD_PATH') }}';
   </script>

   <script src="{{asset_custom('frontend/js/app.js')}}"></script>
</body>

</html>
