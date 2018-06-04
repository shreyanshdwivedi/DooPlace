    <script src="js/jssor.slider-27.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript">jssor_1_slider_init();</script>
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/retina-1.1.0.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="js/card.js"></script>
    <script>
        $(function () {
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $("#registration").validate({
                // Specify validation rules
                rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                first_name: {
                    required: true,
                    minlength: 3
                },
                last_name: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    equalTo: "#regPassword"
                }
                },
                // Specify validation error messages
                messages: {
                first_name: {
                    required: "Please enter your first name",
                    minlength: "Your name must be at least 3 characters long"
                },
                last_name: {
                    required: "Please enter your last name",
                    minlength: "Your name must be at least 3 characters long"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                phone_number: {
                    required: "Please provide a Phone number",
                    minlength: "Your phone number must be at least 8 characters long",
                    maxlength: "Your phone number must be at least 15 characters long"
                },
                email: "Please enter a valid email address"
                },
                // Make sure the form is submitted to the destination defined
                // in the "action" attribute of the form when valid
                submitHandler: function (form) {
                form.submit();
                }
            });
        });

        $(function () {
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $("#loginForm").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    }
                },
                // Specify validation error messages
                messages: {
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address"
                },
                    // Make sure the form is submitted to the destination defined
                    // in the "action" attribute of the form when valid
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
