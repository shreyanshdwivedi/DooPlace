    <script src="js/jssor.slider-27.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript">jssor_1_slider_init();</script>
    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
    <script>
        var val1 = "";
        var val2 = "";
        // use this to allow certain dates only
        // var $disabledDates = "";
        // var $disabledDates = </?php echo $json_array; ?>;
        
        function available(date) {
            ymd = date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2);
            console.log(ymd);
            if ($.inArray(ymd, $disabledDates) == -1) {
                return true;
            } else {
                return false;
            }
        }

        function checkAvailability(mydate){
            var $return=true;
            $checkdate = $.datepicker.formatDate('yy-mm-dd', mydate);
            for(var i = 0; i < $disabledDates.length; i++)
                {    
                   if($disabledDates[i] == $checkdate)
                   {
                        $return = false;
                    }
                }
            return $return;
        }


        $("#dt1").datepicker({
            dateFormat: 'yy-mm-dd',
            autoclose: true,
            startDate: '0d',
            endDate: '+60d',
            beforeShowDay: 
                function(dt)
                { 
                    return available(dt);
                }
        }).on('changeDate', function(){
            val1 = $("#dt1").val();
            console.log(val1 + " " + val2);
            if(!((val1=="") || (val2==""))) {
                if(val1 > val2) {
                    $('#errorBookDiv').show();
                    $("#errorBookForm").html("CheckIn date must be less than CheckOut date");
                    document.getElementById('book').disabled = true;
                } else if(val1 == val2) {
                    $('#errorBookDiv').show();
                    $("#errorBookForm").html("CheckIn date cannot be equal to CheckOut date");
                    document.getElementById('book').disabled = true;
                } else {
                    $('#errorBookDiv').hide();
                    document.getElementById('book').disabled = false;
                }
            } else {
                document.getElementById('book').disabled = true;
            }
        });
        $("#dt2").datepicker({
            dateFormat: 'dd-mm-yy',
            autoclose: true,
            startDate: '0d',
            endDate: '+60d',
            beforeShowDay: 
                function(dt)
                { 
                    return available(dt);
                }
        }).on('changeDate', function(){
            val2 = $("#dt2").val();
            console.log(val1 + " " + val2);
            if(!((val1=="") || (val2==""))) {
                if(val1 > val2) {
                    $('#errorBookDiv').show();
                    $("#errorBookForm").html("CheckIn date must be less than CheckOut date");
                    document.getElementById('book').disabled = true;
                } else if(val1 == val2) {
                    $('#errorBookDiv').show();
                    $("#errorBookForm").html("CheckIn date cannot be equal to CheckOut date");
                    document.getElementById('book').disabled = true;
                } else {
                    $('#errorBookDiv').hide();
                    document.getElementById('book').disabled = false;
                }
            } else {
                document.getElementById('book').disabled = true;
            }
        });
        $("#numGuests").on('input', function(){
            var num = $(this).val();
            if(!((val1=="") || (val2==""))){
                if(num>=1 && num<=10) {
                    function showDays(firstDate,secondDate){
                        var startDay = new Date(firstDate);
                        var endDay = new Date(secondDate);
                        var millisecondsPerDay = 1000 * 60 * 60 * 24;

                        var millisBetween = startDay.getTime() - endDay.getTime();
                        var days = millisBetween / millisecondsPerDay;

                        // Round down.
                        return Math.floor(days);
                    }
                    var date_difference= showDays(val2, val1);
                    var perDayRate = $("#perDayRate").val();
                    var total = (num*date_difference)*perDayRate;
                    $("#bookingSummary").show();
                    $("#bookingSummary").html("<div><p><b>Number of Guests :</b> "+ num +"</p><p><b>Number of Days :</b> "+ date_difference +"</p><p><b>Total :</b> "+ num +" * "+date_difference+" * "+perDayRate+" = Rs. "+total +"</p></div>");
                    $("#amount").val(1.18*total);
                    $("#numDays").val(date_difference);
                    document.getElementById('book').disabled = false;
                } else {
                    $("#bookingSummary").hide();
                    document.getElementById('book').disabled = true;
                }
            } else {
                $("#bookingSummary").hide();
                document.getElementById('book').disabled = true;
            }
        });
    </script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/retina-1.1.0.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="js/lightbox-plus-jquery.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
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
    <script>
        $('.far.fa-heart').on('click', function(){
            $this = $(this);
            var propertyID = $this.attr('data-property-id');
            console.log(propertyID);
            $.ajax({
              url: 'includes/likeunlike.php',
              type: 'post',
              data: 'propertyID='+propertyID,
              error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.log(err);
              },
              success: function(data){      // setting unlikes
                console.log(data);
                  if(data == 1){
                      $this.css("color","#ff6666");
                  } else {
                      $this.css("color","#737373");
                  }
              }
              
          });
        });
    </script>
</body>
</html>
