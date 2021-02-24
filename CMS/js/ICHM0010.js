//Login
$("#login_btn").click(function () {
    var domain = window.location.hostname;
    var stracc = $('#login_username').val();
    var strpsw = $('#password').val();
    var token = GetCookie("CMS_token");
    if (token == "" || token === null || typeof token === "undefined")
        token = "none";

    //Required field cannot be empty
    if (stracc.trim() == "") {
        alert("Please enter your Email.");
        eval("document.getElementById('login_username').focus()");
        return false;
    }
    if (strpsw.trim() == 0) {
        alert("Please enter your password.");
        eval("document.getElementById('password').focus()");
        return false;
    }

    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'login',
            Email: stracc,
            Psw: strpsw
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("CMS_token", data.token, 1, domain);
                SetCookie("Aid", data.Aid, 1, domain);
                SetCookie("FName", data.FName, 1, domain);
                SetCookie("LName", data.LName, 1, domain);
                document.location.href = 'index.html';
            }
            else {
                alert(data.msg);
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })

})
$("#forgotPW").click(function () {
    var domain = window.location.hostname;
    var stracc = $('#forgot_username').val();
    var token = GetCookie("CMS_token");
    if (token == "" || token === null || typeof token === "undefined")
        token = "none";

    ///Required field cannot be empty
    if (stracc.trim() == "") {
        alert("Please enter your Email.");
        eval("document.getElementById('forgot_username').focus()");
        return false;
    }

    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'forgetpsd',
            Email: stracc
        },
        success: function (data) {
            if (data.isSuccess) {
                $.ajax({
                    type: "POST",
                    url: "Mod/mail_pwd.php",
                    async: false,
                    dataType: "json",
                    data: {
                        token: token,
                        Name: data.name,
                        Pwd: data.password,
                        Email: stracc
                    },
                    success: function (data) {
                        if (data.isSuccess) {
                            $('#forgot_username').val("");
                            alert("The mail has be sent, please check the mail box.")
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                    }
                })
            }
            else {
                alert(data.msg);
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })

})
$("#button").click(function () {
    //Get information
    var domain = window.location.hostname;
    var strmail = $('#ichm_email').val();
    var strpsd = $('#ichm_psd').val();
    var strcheckpsd = $('#ichm_checkpsd').val();
    var strtitle = $('#ichm_title').val();
    var strFName = $('#ichm_fname').val();
    var strLName = $('#ichm_lname').val();
    var strcountry = $('#ichm_country').val();
    var strorga = $('#ichm_orga').val();
    var strdepart = $('#ichm_dept').val();
    var strstreet = $('#ichm_street').val();
    var strcity = $('#ichm_city').val();
    var strarea = $('#ichm_area').val();
    var strcode = $('#ichm_code').val();
    var strphone = $('#ichm_phone').val();
    var strfax = $('#ichm_fax').val();
    var isSub = 0;
    var token = GetCookie("CMS_token");

    if (token == "" || token === null || typeof token === "undefined")
        token = "none";
    //Required field cannot be empty
    if (strmail.trim() == "") {
        alert("Please enter your email.");
        eval("document.getElementById('ichm_email').focus()");
        return false;
    }
    if (strpsd.trim() == "") {
        alert("Please enter your password.");
        eval("document.getElementById('ichm_psd').focus()");
        return false;
    }
    if (strtitle.trim() == "") {
        alert("Please enter your title.");
        eval("document.getElementById('ichm_title').focus()");
        return false;
    }
    if (strFName.trim() == "") {
        alert("Please enter your first name.");
        eval("document.getElementById('ichm_fname').focus()");
        return false;
    }
    if (strLName.trim() == "") {
        alert("Please enter your last name.");
        eval("document.getElementById('ichm_lname').focus()");
        return false;
    }
    if (strcountry == 0) {
        alert("Please select your country");
        eval("document.getElementById('ichm_country').focus()");
        return false;
    }
    if (strorga.trim() == "") {
        alert("Please enter your organization.");
        eval("document.getElementById('ichm_orga').focus()");
        return false;
    }
    if (strdepart.trim() == "") {
        alert("Please enter your department.");
        eval("document.getElementById('ichm_dept').focus()");
        return false;
    }
    if (strstreet.trim() == "") {
        alert("Please enter your street.");
        eval("document.getElementById('ichm_street').focus()");
        return false;
    }
    if (strcity.trim() == "") {
        alert("Please enter your city.");
        eval("document.getElementById('ichm_city').focus()");
        return false;
    }
    if (strcode.trim() == "") {
        alert("Please enter your postal code.");
        eval("document.getElementById('ichm_code').focus()");
        return false;
    }
    if (strphone.trim() == "") {
        alert("Please enter your phone.");
        eval("document.getElementById('ichm_phone').focus()");
        return false;
    }

    //check email format is correct or not
    if (strmail.search(/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/) == -1) {
        alert("Email format error!");
        eval("document.getElementById('ichm_email').focus()");
        return false;
    }

    if (strpsd != strcheckpsd) {
        alert("Please check your password again!");
        eval("document.getElementById('ichm_psd').focus()");
        eval("document.getElementById('ichm_checkpsd').focus()");
        return false;
    }

    if (document.getElementById("sub").checked)
        isSub = 1;

    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            Email: strmail,
            Psd: strpsd,
            FName: strFName,
            LName: strLName,
            Title: strtitle,
            Country: strcountry,
            Orga: strorga,
            Depart: strdepart,
            Street: strstreet,
            City: strcity,
            Area: strarea,
            Code: strcode,
            Phone: strphone,
            Fax: strfax,
            isSub: isSub,
            action: 'regist'
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("CMS_token", data.token, 1, domain);
                SetCookie("Aid", data.Aid, 1, domain);
                SetCookie("FName", data.FName, 1, domain);
                SetCookie("LName", data.LName, 1, domain);
                document.location.href = 'index.html';
                alert("SUCCESSFUL!");
            }
            else {
                alert(data.msg);
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
})

function validation_init() {
    var token = GetCookie("CMS_token");
    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getValidationInfo'
        },
        success: function (data) {
            if (data.isSuccess) {
                if (data.out["A_checkIdentity"] == 2) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 3) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("groupType").disabled = true;
                    document.getElementById("memberNo").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 4) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("groupType").disabled = true;
                    document.getElementById("memberNo").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 5) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("stu_option").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                    document.getElementById("upload2_area").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 6) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("stu_option").disabled = true;
                    document.getElementById("groupType").disabled = true;
                    document.getElementById("memberNo").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                    document.getElementById("upload2_area").style.display = "none";
                    document.getElementById("identity_btndone").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 7) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("stu_option").disabled = true;
                    document.getElementById("groupType").disabled = true;
                    document.getElementById("memberNo").disabled = true;
                    document.getElementById("upload_area").style.display = "none";
                    document.getElementById("upload2_area").style.display = "none";
                    document.getElementById("identity_btndone").style.display = "none";
                }
                else if (data.out["A_checkIdentity"] == 8) {
                    document.getElementById("nationality").disabled = true;
                    document.getElementById("upload2_area").style.display = "none";
                    document.getElementById("identity_btndone").style.display = "none";
                }
                if (data.out["A_identityImg"] != null)
                    document.getElementById("IDCard").innerHTML = "<a href=" + data.out["A_identityImg"] + ">Selected national identification card (or resident certificate) image</a>";
                if (data.out["A_stuImg"] != null)
                    document.getElementById("studentIDCard").innerHTML = "<a href=" + data.out["A_stuImg"] + ">Selected student ID card image</a>";
                if (data.out["A_cardNo"] != null)
                    $("#memberNo").val(data.out["A_cardNo"]);
                if (data.out["A_apply"] == 1) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "International participant(non student)";
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 3) {
                        document.getElementById("validation_result").innerHTML = "Medical Design Member(non student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 4) {
                        document.getElementById("validation_result").innerHTML = "Employee in Formosa Plastics Group(non student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 6) {
                        document.getElementById("validation_result").innerHTML = "Medical Design Member(student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 7) {
                        document.getElementById("validation_result").innerHTML = "Employee in Formosa Plastics Group(student)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 8) {
                        document.getElementById("validation_result").innerHTML = "International participant(student)";
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 2) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participant(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 3) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 4) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(0);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 5) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 6) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 3) {
                        document.getElementById("validation_result").innerHTML = "Medical Design Member(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 7) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 4) {
                        document.getElementById("validation_result").innerHTML = "Employee in Formosa Plastics Group(non student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        document.getElementById("validation_result").innerHTML = "Taiwanese participant(student)(We've received your new validation application, but the validation process is not finished)";
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        $("#stu_option").val(1);
                        $("#stu_option").trigger("change");
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 8) {
                    if (data.out["A_checkIdentity"] == 1) {
                        document.getElementById("validation_result").innerHTML = "(We've received your informatiln, but the validation process is not finished, so we still regard you as a international participantt(non student).)";
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                    }
                }

            }
            else {
                alert(data.msg);
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })

}

$("#cancel_btn").click(function () {
    document.location.href = 'login.html';
})

$(document).on('change', '#groupType', function (event) {
    var area1 = document.getElementById("card_area1");
    var area2 = document.getElementById("card_area2");
    if ($("#groupType").val() != 0) {
        area1.style.display = "block";
        area2.style.display = "block";
    }
    else if ($("#groupType").val() == 0) {
        area1.style.display = "none";
        area2.style.display = "none";
    }
})

$(document).on('change', '#stu_option', function (event) {
    var area = document.getElementById("studentCard_area");
    if ($("#stu_option").val() == 0) {
        area.style.display = "none";
    }
    else if ($("#stu_option").val() == 1) {
        area.style.display = "block";
    }
})

$(document).on('change', '#nationality', function (event) {
    if ($("#nationality").val() == 0) {
        document.getElementById("idCard_area").style.display = "none";
        document.getElementById("stu_area1").style.display = "none";
        document.getElementById("stu_area2").style.display = "none";
        document.getElementById("studentCard_area").style.display = "block";
        document.getElementById("group_area1").style.display = "none";
        document.getElementById("group_area2").style.display = "none";
        document.getElementById("card_area1").style.display = "none";
        document.getElementById("card_area2").style.display = "none";
    }
    else if ($("#nationality").val() == 1) {
        document.getElementById("idCard_area").style.display = "block";
        document.getElementById("stu_area1").style.display = "block";
        document.getElementById("stu_area2").style.display = "block";
        $("#stu_option").trigger("change");
        document.getElementById("group_area1").style.display = "block";
        document.getElementById("group_area2").style.display = "block";
        $("#groupType").trigger("change");
    }
})

$("#identity_btnback").click(function () {
    document.location.href = 'index.html';
})

var Isinsize = false;
var Isinsize2 = false;
$('#upimg').bind('change', function () {
    if (this.files[0].size / 1024 / 1024 > 150) {
        Isinsize = false;
        alert('This file size is: ' + this.files[0].size / 1024 / 1024 + "MB");
    }
    else {
        Isinsize = true;
    }
});
$('#upimg2').bind('change', function () {
    if (this.files[0].size / 1024 / 1024 > 150) {
        Isinsize2 = false;
        alert('This file size is: ' + this.files[0].size / 1024 / 1024 + "MB");
    }
    else {
        Isinsize2 = true;
    }
});

$("#identity_btndone").click(function (e) {
    //check title field and upload field is empyt or not(they can't be empty)
    var token = GetCookie("CMS_token");
    var apply = 0;
    var Nationality = document.getElementById("nationality").value;
    var Stu_option = document.getElementById("stu_option").value;
    var Type = document.getElementById("groupType").value;
    var cardNo = document.getElementById("memberNo").value;
    if (Nationality == 0) {
        if (document.getElementById("studentIDCard").innerHTML == "(You haven't upload any image.)") {
            if (document.getElementById("upimg2").value == "") {
                alert("Please upload the student ID card image");
                eval("document.getElementById('upimg2').focus()");
                return false;
            }
        }
    }
    else if (Nationality == 1) {
        if (document.getElementById("IDCard").innerHTML == "(You haven't upload any image.)") {
            if (document.getElementById("upimg").value == "") {
                alert("Please upload the identification card (or alien resident certificate) image");
                eval("document.getElementById('upimg').focus()");
                return false;
            }
        }
        if (Stu_option == 1) {
            if (document.getElementById("studentIDCard").innerHTML == "(You haven't upload any image.)") {
                if (document.getElementById("upimg2").value == "") {
                    alert("Please upload the student ID card image");
                    eval("document.getElementById('upimg2').focus()");
                    return false;
                }
            }
        }
    }
    if (document.getElementById("upimg").value != "") {
        if (!Isinsize) {
            alert("the national identification card (or resident certificate) image size is over 150MB");
            eval("document.getElementById('upimg').focus()");
            return false;
        }
    }
    if (document.getElementById("upimg2").value != "") {
        if (!Isinsize2) {
            alert("the student ID card image size is over 150MB");
            eval("document.getElementById('upimg2').focus()");
            return false;
        }
    }
    if (Nationality == 0) {
        apply = 8;
    }
    else if (Nationality == 1) {
        if (Type == 0) {
            if (Stu_option == 0)
                apply = 2;
            else if (Stu_option == 1)
                apply = 5;
        }
        if (Type == 1) {
            if (cardNo == "") {
                alert("Please enter the membership card number.");
                eval("document.getElementById('memberNo').focus()");
                return false;
            }
            else {
                if (Stu_option == 0)
                    apply = 3;
                else if (Stu_option == 1)
                    apply = 6;
            }
        }
        if (Type == 2) {
            if (cardNo == "") {
                alert("Please enter the employee card number.");
                eval("document.getElementById('memberNo').focus()");
                return false;
            }
            else {
                if (Stu_option == 0)
                    apply = 4;
                else if (Stu_option == 1)
                    apply = 7;
            }
        }
    }
    $('#loading').show();
    var form = document.forms.namedItem("identityinfo");
    form.addEventListener("submit", function (ev) {
        oData = new FormData(form);
        oData.append("token", token);
        oData.append("apply", apply);
        oData.append("cardNo", cardNo);
        oData.append("action", "uploadIdentityInfo");
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "Control/ICHM0010.php", true);
        oReq.onload = function (oEvent) {
            if (oReq.status == 200) {
                $('#loading').hide();
                alert("successful");
                document.location.href = 'validation.html';
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
});
