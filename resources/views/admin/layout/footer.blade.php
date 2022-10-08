        <!-- jQuery  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/popper.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/modernizr.min.js"></script>
        <script src="/assets/js/detect.js"></script>
        <script src="/assets/js/fastclick.js"></script>
        <script src="/assets/js/jquery.slimscroll.js"></script>
        <script src="/assets/js/jquery.blockUI.js"></script>
        <script src="/assets/js/waves.js"></script>
        <script src="/assets/js/jquery.nicescroll.js"></script>
        <script src="/assets/js/jquery.scrollTo.min.js"></script>
        <script src="/assets/js/sweetalert2.min.js"></script>
        <script src="/assets/js/toastr.min.js"></script>

        <script src="/assets/plugins/chart.js/chart.min.js"></script>
        <script src="/assets/pages/dashboard.js"></script>
        
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $('#logout').on('click', function(){
                $.ajax({
                    url: "{{route('admin.post.logout')}}",
                    method: "POST",
                    success: function(response){
                        window.location.href = "{{route('login')}}";
                    }
                });
            });

            // Returns a function, that, as long as it continues to be invoked, will not
            // be triggered. The function will be called after it stops being called for
            // `wait` milliseconds.
            const debounce = (func, wait) => {
            let timeout;

            // This is the function that is returned and will be executed many times
            // We spread (...args) to capture any number of parameters we want to pass
            return function executedFunction(...args) {

                // The callback function to be executed after 
                // the debounce time has elapsed
                const later = () => {
                // null timeout to indicate the debounce ended
                timeout = null;
                
                // Execute the callback
                func(...args);
                };
                // This will reset the waiting every function execution.
                // This is the step that prevents the function from
                // being executed because it will never reach the 
                // inside of the previous setTimeout  
                clearTimeout(timeout);
                
                // Restart the debounce waiting period.
                // setTimeout returns a truthy value (it differs in web vs Node)
                timeout = setTimeout(later, wait);
            };
            };
        </script>
        @yield('js')

        <!-- App js -->
        <script src="/assets/js/app.js"></script>
        

    </body>
</html>