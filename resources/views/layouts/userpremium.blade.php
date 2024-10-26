<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>New Age - Start Bootstrap Theme</title>
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="{{ asset('admin/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('premium/sb-admin-2.min.css') }}" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        


    </head>
    <body id="page-top" class="bg-slate-100">

        <div  id="content-wrapper" class="d-flex flex-column">
            @include('layouts.main.navbar')
        </div>
        
        <div id="layoutSidenav">
    
            @include('layouts.main.sidebar')
        
            <div id="layoutSidenav_content">
                <main>
                    
                    <div class="container-fluid px-4">
                        @yield('content premium')
                    </div>
                </main>
            </div>
            
        </div>
        
    
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="premium/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="premium/demo/chart-area-demo.js"></script>
    <script src="premium/demo/chart-pie-demo.js"></script>
    <script src="{{asset('premium/scripts.js')}}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="softwares[]"]');

            
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });

           
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('input[name="softwares[]"]:checked').length;

                    if (checkedCount > 3) {
                        alert('Você só pode selecionar no máximo 3 softwares.');
                        checkbox.checked = false;  
                    }
                });
            });
        });

        
        function toggleFillAndDetails(id, description) {
            const checkedCount = document.querySelectorAll('input[name="softwares[]"]:checked').length;

            
            const checkbox = document.getElementById(`software${id}`);
            if (!checkbox.checked && checkedCount >= 3) {
                alert('Você só pode selecionar no máximo 3 softwares.');
                return;  
            }

            
            toggleFillCard(id);

            
            toggleDetails(id, description);

            
            toggleCheckbox(id);
        }

        function toggleFillCard(id) {
            const card = document.getElementById(`checkboxDiv${id}`);
            card.classList.toggle('filled-card');
        }

        function toggleDetails(id, description) {
            const details = document.getElementById('details-' + id);
            if (details.style.display === 'none') {
                details.style.display = 'block';
                typeWriterEffect(`description-${id}`, description);
            } else {
                details.style.display = 'none';
            }
        }

        
        function toggleCheckbox(id) {
            const checkbox = document.getElementById(`software${id}`);
            checkbox.checked = !checkbox.checked;  
        }

       
        function typeWriterEffect(elementId, text, speed = 50) {
            const element = document.getElementById(elementId);
            element.innerHTML = '';  
            let i = 0;

            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);  
                }
            }

            type();  
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>