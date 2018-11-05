$(document).ready(function(){
    
    // Displays error messages for logging in
    // Logs in if none are found
    $("#login_button").click(function(e){
        e.preventDefault();
        
        var form = $("#login_form");
        
        if (!check_login_fields()){
            $.ajax({
                method: "post",
                url: "ajax/login.php",
                data: form.serialize(),
                success: function(data){
                    if (data === "Valid login details")
                        form.submit();
                    else
                        $("#login_error").html(data).fadeIn("fast");
                }
            });
        }
    });
    
    
    
    // Displays error messages beneath login inputs if they are empty.
    // Returns true if any occured
    function check_login_fields(){
        var errors = false;
        var login_error = $("#login_error");
        
        if (login_error.css("display") !== "none")
            login_error.fadeOut("fast");
        
        $("#login_form input").each(function(){
            var input = $(this);
            var error = input.next().next();
            
            if (input.val() === ""){
                error.slideDown("fast");
                errors = true;
            }else
                error.slideUp("fast");
        });
        
        return errors;
    }
    
    
    
    // Opens register popup
    $("#register_popup_button").click(function(){
        $("#register_popup").show();
    });
    
    
    
    // Closes register popup if user clicks outside of it
    $(window).click(function(e){
        if (e.target.id === "register_popup")
            $("#register_popup").hide();
    });
    
    
    
    // Displays error messages for registering
    $("#register_button").click(function(e){
        e.preventDefault();
        
        var username = $("#reg_username").val();
        var p1 = $("#reg_password_01").val();
        var p2 = $("#reg_password_02").val();
        var email = $("#reg_email").val();
        
        var u_error = $("#reg_username").next().next();
        var p_error = $("#reg_password_02").next().next();
        var e_error = $("#reg_email").next().next();
        var errors = false;
        
        // Password errors
        if (p1 === p2 && p1 !== "" && p2 !== "")
            p_error.slideUp("fast");
        else{
            errors = true;
            var error;
            
            if (p1 !== p2)
                error = "Password do not match";
            else
                error = "Password missing";
            
            p_error.html(error).slideDown("fast");
        }
        
        // Fields that need to be unique
        // [field_name, value, error_slector, error_message]
        var x = [
            ["username", username, u_error, "Username already taken"],
            ["email", email, e_error, "Email already taken"]
        ];
        
        // Checks that all unique fields are not already taken
        for (var i = 0; i < x.length; i++){
            if (x[i][1] === "")
                x[i][2].html("*Required").slideDown("fast");
            else
                $.ajax({
                    method: "post",
                    url: "ajax/user_field_taken.php",
                    async: false,
                    data: {
                        "field": x[i][0],
                        "value": x[i][1]
                    },
                    success: function(data){
                        if (data === "True"){  // Field already taken
                            errors = true;
                            x[i][2].html(x[i][3]).slideDown("fast");
                        }else
                            x[i][2].slideUp("fast");
                    }
                });
        }
        
        var email_regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        
        if (!email_regex.test(email)){
            e_error.html("Valid email required").slideDown("fast");
            errors = true;
        }else
            e_error.slideUp("fast");
        
        if (!errors)
            $("#register_form").submit();
    });
});