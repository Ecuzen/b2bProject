
</div><!-- container -->

            <footer class="footer text-center text-sm-start p-2">
                &copy;
                <script>
                    document.write(new Date().getFullYear())
                </script> {{$dName}} <span class="text-muted d-none d-sm-inline-block float-end">Crafted with <i
                        class="mdi mdi-heart text-danger"></i> by Ecuzen </span>
            </footer><!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->
    <script>
        
        var currentDate = new Date();
        var maxDate = new Date(currentDate);
        maxDate.setMonth(currentDate.getMonth() );
        var maxDateString = maxDate.toISOString().slice(0, 10);
        $("input[type='date']").attr('max', maxDateString);
        
        function error(text)
        {
            Swal.fire({
                title: 'Error!',
                text: text,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    icon: 'swal2-icon swal2-error swal2-animate-error-icon',
                }
            });
        }
        
        function info(text)
        {
            Swal.fire({
                title: 'Info!',
                text: text,
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    icon: 'swal2-icon swal2-error swal2-animate-error-icon',
                }
            });
        }
        
        function success(text)
        {
            Swal.fire({
                title: 'SUCCESS!',
                text: text,
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    icon: 'swal2-icon swal2-error swal2-animate-error-icon',
                }
            });
        }
        function successRedir(text,url)
        {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'success',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(url, "_blank");
                }
            });
        }
        function successReload(text)
        {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'success',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
        function errorReload(text)
        {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'error',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
        function infoReload(text)
        {
            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'info',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
    </script>



    <!-- jQuery  -->
    <script src="{{url('assets')}}/dashboard/assets/js/jquery.min.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/metismenu.min.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/waves.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/feather.min.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/simplebar.min.js"></script>
    <script src="{{url('assets')}}/dashboard/assets/js/moment.js"></script>
    <script src="{{url('assets')}}/dashboard/plugins/daterangepicker/daterangepicker.js"></script>

    <script src="{{url('assets')}}/dashboard/plugins/apex-charts/apexcharts.min.js"></script>
    <script src="{{url('assets')}}/dashboard/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{url('assets')}}/dashboard/plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>
    <!--<script src="{{url('assets')}}/dashboard/assets/pages/jquery.analytics_dashboard.init.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- App js -->
    <script src="{{url('assets')}}/dashboard/assets/js/app.js"></script>
    
        <!--Datatables-->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bulma.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bulma.min.js"></script>
        <!-- Buttons examples -->
        <script src="{{url('assets')}}/dashboard/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.bootstrap5.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/jszip.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/pdfmake.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/vfs_fonts.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.html5.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.print.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="{{url('assets')}}/dashboard/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/pages/jquery.datatable.init.js"></script>
        
        

</body>

</html>