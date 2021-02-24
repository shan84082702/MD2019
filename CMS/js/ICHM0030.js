var token = GetCookie("CMS_token");
var domain = window.location.hostname;
var txtDateString = "2019/09/19";

function getProres() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getProres',
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("needs").value = data.out["O_Dietary"];
                if (data.out["O_Detailed"] != null)
                    document.getElementById("cms_needs").value = data.out["O_Detailed"];
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

function getProres_1() {
    var currDate = Date.parse((new Date()).toDateString());
    var txtDate = Date.parse(txtDateString);
    if (currDate > txtDate) {
        $("input[name='Fee'][value=1]").prop("disabled", true);
        $("input[name='Fee'][value=2]").prop("disabled", true);
        $("input[name='Fee'][value=3]").prop("disabled", true);
        $("input[name='Fee'][value=4]").prop("disabled", true);
        $("input[name='Fee'][value=5]").prop("disabled", true);
        $("input[name='Fee'][value=6]").prop("disabled", true);
    }
    if (GetCookie("O_Type") != "") {
        $("input[name='Fee'][value=" + GetCookie('O_Type') + "]").prop("checked", true);
    }
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getSubmissionOption',
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                for (i = 0; i < data.out.length; i++) {
                    var o = new Option("(Paper ID:" + data.out[i].Pid + ") " + data.out[i].P_Title, data.out[i].Pid)
                    $("#submission_option").append(o);
                }
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
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getProres_1',
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                if (data.out["O_Pid"] != null)
                    document.getElementById("submission_option").value = data.out["O_Pid"];
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

function getProres_2() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getProres_2',
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                if (data.out["O_Name"].trim() != "") {
                    document.getElementById("order_name").value = data.out["O_Name"];
                    document.getElementById("order_phone").value = data.out["O_Phone"];
                    document.getElementById("order_org").value = data.out["O_Organization"];
                    document.getElementById("order_street").value = data.out["O_Stree"];
                    document.getElementById("ichm_country").value = data.out["O_Country"];
                    document.getElementById("order_area").value = data.out["O_Area"];
                    document.getElementById("order_city").value = data.out["O_City"];
                    document.getElementById("order_zipcode").value = data.out["O_Zipcode"];
                }
                else
                    getOrderSelfInfo();
                if (data.out["O_Pid"] != 0 && data.out["O_Pid"] != null)
                    getOrderSubmissionTopic(data.out["O_Pid"]);
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

$("#bnnext").click(function () {
    var needs = $("#needs").val();
    var detail = $("#cms_needs").val();
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: { token: token, needs: needs, detail: detail, Oid: GetCookie("OrderId"), action: "prores" },
        success: function (data) {
            if (data.isSuccess) {
                document.location.href = 'proregister_1.html';
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

function getSelfInfo() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getself',
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("FNAME").innerHTML = data.FName;
                document.getElementById("LNAME").innerHTML = data.LName;
                document.getElementById("EMAIL").innerHTML = data.Email;
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

function getOrderSubmissionTopic($pid) {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getPaperInfo',
            Pid: $pid
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("order_submission").innerHTML = "<a href=" + data.Path + ">" + data.Title + "</a>";
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

function getOrderSelfInfo() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getOrderSelfInfo',
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("order_name").value = data.out["A_Fname"] + " " + data.out["A_Lname"];
                document.getElementById("order_phone").value = data.out["A_Phone"];
                document.getElementById("order_org").value = data.out["A_Organization"];
                document.getElementById("order_street").value = data.out["A_Street"];
                document.getElementById("ichm_country").value = data.out["A_Country"];
                document.getElementById("order_area").value = data.out["A_Area"];
                document.getElementById("order_city").value = data.out["A_City"];
                document.getElementById("order_zipcode").value = data.out["A_Zipcode"];
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

$("#btncancel").click(function () {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: { token: token, action: "cancel", Oid: GetCookie("OrderId") },
        success: function (data) {
            if (data.isSuccess) {
                DelCookie("OrderId", domain);
                DelCookie("O_Type", domain);
                DelCookie("O_Money", domain);
                document.location.href = 'registration.html';
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

$("#p1_btnext").click(function () {
    var type = $("input[name='Fee']:checked").val();
    var submission_option = $("#submission_option").val();
    if (type != 1 && type != 2 && type != 3 && type != 4 && type != 5 && type != 6 && type != 7 && type != 8 && type != 9 && type != 10 && type != 11 && type != 12 && type != 13 && type != 14 && type != 15 && type != 16 && type != 17) {
        alert("Please select Conference Fee");
        return false;
    }
    if (submission_option == "null") {
        alert("Please select a submission option");
        eval("document.getElementById('submission_option').focus()");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: { token: token, type: type, submission_option: submission_option, action: "prores_1", Oid: GetCookie("OrderId") },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("O_Type", type, 1, domain);
                SetCookie("O_Money", data.money, 1, domain);
                document.location.href = 'proregister_2.html';
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
$("#p1_btnpre").click(function () {
    document.location.href = 'proregister.html';
})

$("#p2_btnnext").click(function () {
    var name = $("#order_name").val();
    var phone = $("#order_phone").val();
    var org = $("#order_org").val();
    var street = $("#order_street").val();
    var country = $("#ichm_country").val();
    var area = $("#order_area").val();
    var city = $("#order_city").val();
    var zipcode = $("#order_zipcode").val();
    if (name.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_name').focus()");
        return false;
    }
    if (phone.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_phone').focus()");
        return false;
    }
    if (org.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_org').focus()");
        return false;
    }
    if (street.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_street').focus()");
        return false;
    }
    if (country == 0) {
        alert("Please enter all the information.");
        eval("document.getElementById('ichm_country').focus()");
        return false;
    }
    if (city.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_city').focus()");
        return false;
    }
    if (zipcode.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_zipcode').focus()");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            name: name,
            phone: phone,
            org: org,
            street: street,
            country: country,
            area: area,
            city: city,
            zipcode: zipcode,
            action: "prores_2",
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                document.location.href = 'proregister_3.html';
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
$("#p2_btnpre").click(function () {
    document.location.href = 'proregister_1.html';
})

function getOrderInfo() {
    var table = document.getElementById("author");
    var newRow = table.insertRow(table.rows.length);
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var detail = "";
    if (GetCookie("O_Type") == 1)
        detail = "Early bird-International-Delegate";
    else if (GetCookie("O_Type") == 2)
        detail = "Early bird-International-Student";
    else if (GetCookie("O_Type") == 3)
        detail = "Early bird-International-Non Presenter";
    else if (GetCookie("O_Type") == 4)
        detail = "Early bird-Taiwan-Delegate";
    else if (GetCookie("O_Type") == 5)
        detail = "Early bird-Taiwan-Student";
    else if (GetCookie("O_Type") == 6)
        detail = "Early bird-Taiwan-Non Presenter";
    else if (GetCookie("O_Type") == 7)
        detail = "Regular-International-Delegate";
    else if (GetCookie("O_Type") == 8)
        detail = "Regular-International-Student";
    else if (GetCookie("O_Type") == 9)
        detail = "Regular-International-Non Presenter";
    else if (GetCookie("O_Type") == 10)
        detail = "Regular-Taiwan-Delegate";
    else if (GetCookie("O_Type") == 11)
        detail = "Regular-Taiwan-Student";
    else if (GetCookie("O_Type") == 12)
        detail = "Regular-Taiwan-Non Presenter";
    else if (GetCookie("O_Type") == 13)
        detail = "Regular-Taiwan-Medical Design Members or Employees in Formosa Plastics Group";
    else if (GetCookie("O_Type") == 14)
        detail = "Regular-International Partical Workshop-Delegate";
    else if (GetCookie("O_Type") == 15)
        detail = "Regular-International Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";
    else if (GetCookie("O_Type") == 16)
        detail = "Regular-Each Local Partical Workshop-Delegate";
    else if (GetCookie("O_Type") == 17)
        detail = "Regular-Each Local Partical Workshop-Medical Design Members or Employees in Formosa Plastics Group";

    cell1.innerHTML = "1";
    cell2.innerHTML = detail;
    cell3.innerHTML = "1";
    cell4.innerHTML = "NT$" + GetCookie("O_Money");
    cell5.innerHTML = "NT$" + GetCookie("O_Money");

}

$("#pay").click(function () {
    //alert("The payment system isn't ready. You can go to Registration->Status to view or edit or pay your order in the future. Thank You!");
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            OrderId: GetCookie("OrderId"),
            action: "getOrderCanPay"
        },
        success: function (data) {
            if (data.O_canPay == 1) {
                window.open("../CMS/ecpay/sample_Credit_CreateOrder.php?oid=" + GetCookie("OrderId") +"","_blank");
            }
            else if (data.O_canPay == 0) {
                if (data.O_feeType == 2) {
                    alert("*The registration fee you selected is only available for a student(including internatinal student and Taiwanese student).\n*If you are a student, please go to the IDENTITY VALIDATION page to upload realated information to proof your identity.\n*After we confirm your identity, you can receive the discounted fee.\n*If you want to modeify the registraiton fee you selected, you can edit your registration order to choose correct registration fee and then pay.\nThank You.");
                }
                else if (data.O_feeType == 3) {
                    alert("*The registration fee you selected is only available for a Taiwanese participant(including Taiwanese student and Medical Design Member and Employee in Formosa Plastics Group).\n*If you are a Taiwanese participant, please go to the IDENTITY VALIDATION page to upload realated information to proof your identity.\n*After we confirm your identity, you can receive the discounted fee.\n*If you want to modeify the registraiton fee, you can edit your registration order to choose correct registration fee and then pay.\nThank You.")
                }
                else if (data.O_feeType == 4) {
                    alert("*The registration fee you selected is only available for a Taiwanese student.\n*If you are a Taiwanese student, please go to the IDENTITY VALIDATION page to upload realated information to proof your identity.\n*After we confirm your identity, you can receive the discounted fee.\n*If you want to modeify the registraiton fee, you can edit your registration order to choose correct registration fee and then pay.\nThank You.")
                }
                else if (data.O_feeType == 5) {
                    alert("*The registration fee you selected is only available for a Medical Design Member or an Employee in Formosa Plastics Group.\n*If you are a Medical Design Member or an Employee in Formosa Plastics Group, please go to the IDENTITY VALIDATION page to upload realated information to proof your identity.\n*After we confirm your identity, you can receive the discounted fee.\n*If you want to modeify the registraiton fee, you can edit your registration order to choose correct registration fee and then pay.\nThank You.")
                }
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
});

$("#not_pay").click(function () {
    document.location.href = 'ordersummary.html';
});

function getOrderSummaryInfo() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: "getOrderSummaryInfo"
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("OS_author").innerHTML = data.paper_table;
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

function proregisteredit(Oid) {
    SetCookie("OrderId", Oid, 1, domain);
    document.location.href = "proregisteredit.html";
}

function proregisterview(Oid) {
    SetCookie("OrderId", Oid, 1, domain);
    document.location.href = "proregisterview.html";
}

$("#PV_btnback").click(function () {
    DelCookie("OrderId", domain);
    document.location.href = 'ordersummary.html';
});

function getPEInfo() {
    var currDate = Date.parse((new Date()).toDateString());
    var txtDate = Date.parse(txtDateString);
    if (currDate > txtDate) {
        $("input[name='Fee'][value=1]").prop("disabled", true);
        $("input[name='Fee'][value=2]").prop("disabled", true);
        $("input[name='Fee'][value=3]").prop("disabled", true);
        $("input[name='Fee'][value=4]").prop("disabled", true);
        $("input[name='Fee'][value=5]").prop("disabled", true);
        $("input[name='Fee'][value=6]").prop("disabled", true);
    }
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: "getPEInfo",
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("needs").value = data.O_Dietary;
                document.getElementById("cms_needs").value = data.O_Detailed;
                if (currDate > txtDate) {
                    if (data.O_type < 7) {
                        $change_type = parseInt(data.O_type) + 6;
                        $("input[name='Fee'][value='" + $change_type + "']").attr("checked", true);
                    }
                    else
                        $("input[name='Fee'][value='" + data.O_type + "']").attr("checked", true);
                }
                else
                    $("input[name='Fee'][value='" + data.O_type + "']").attr("checked", true);
                document.getElementById("order_name").value = data.O_Name;
                document.getElementById("order_phone").value = data.O_Phone;
                document.getElementById("order_org").value = data.O_Organization;
                document.getElementById("order_street").value = data.O_Stree;
                document.getElementById("ichm_country").value = data.O_Country;
                document.getElementById("order_area").value = data.O_Area;
                document.getElementById("order_city").value = data.O_City;
                document.getElementById("order_zipcode").value = data.O_Zipcode;
                if (data.O_Pid != 0 && data.O_Pid != null) {
                    var o = new Option("(Paper ID:" + data.O_Pid + ") " + data.P_Title, data.O_Pid)
                    $("#submission_option").append(o);
                }
                $("#submission_option").val(data.O_Pid);
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
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getSubmissionOption',
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                for (i = 0; i < data.out.length; i++) {
                    var o = new Option("(Paper ID:" + data.out[i].Pid + ") " + data.out[i].P_Title, data.out[i].Pid)
                    $("#submission_option").append(o);
                }
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

function getPVInfo() {
    for (var type = 1; type < 18; type++) {
        $("input[name='Fee'][value=" + type + "]").prop("disabled", true);
    }
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: "getPEInfo",
            Oid: GetCookie("OrderId")
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("needs").value = data.O_Dietary;
                document.getElementById("cms_needs").value = data.O_Detailed;
                $("input[name='Fee'][value='" + data.O_type + "']").attr("checked", true);
                document.getElementById("order_name").value = data.O_Name;
                document.getElementById("order_org").value = data.O_Organization;
                document.getElementById("order_street").value = data.O_Stree;
                document.getElementById("ichm_country").value = data.O_Country;
                document.getElementById("order_area").value = data.O_Area;
                document.getElementById("order_city").value = data.O_City;
                document.getElementById("order_phone").value = data.O_Phone;
                document.getElementById("order_zipcode").value = data.O_Zipcode;
            }
            if (data.O_Pid != 0 && data.O_Pid != null)
                getOrderSubmissionTopic(data.O_Pid);
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


$("#PE_btnext").click(function () {
    var needs = $("#needs").val();
    var detail = $("#cms_needs").val();
    var name = $("#order_name").val();
    var org = $("#order_org").val();
    var street = $("#order_street").val();
    var country = $("#ichm_country").val();
    var area = $("#order_area").val();
    var city = $("#order_city").val();
    var zipcode = $("#order_zipcode").val();
    var phone = $("#order_phone").val();
    var type = $("input[name='Fee']:checked").val();
    var submission_option = $("#submission_option").val();
    if (submission_option == "null") {
        alert("Please select a submission option");
        eval("document.getElementById('submission_option').focus()");
        return false;
    }
    if (type != 1 && type != 2 && type != 3 && type != 4 && type != 5 && type != 6 && type != 7 && type != 8 && type != 9 && type != 10 && type != 11 && type != 12 && type != 13 && type != 14 && type != 15 && type != 16 && type != 17) {
        alert("Please select Conference Fee");
    }
    if (name.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_name').focus()");
        return false;
    }
    if (org.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_org').focus()");
        return false;
    }
    if (street.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_street').focus()");
        return false;
    }
    if (country == 0) {
        alert("Please enter all the information.");
        eval("document.getElementById('ichm_country').focus()");
        return false;
    }
    if (city.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_city').focus()");
        return false;
    }
    if (zipcode.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_zipcode').focus()");
        return false;
    }
    if (phone.trim() == "") {
        alert("Please enter all the information.");
        eval("document.getElementById('order_phone').focus()");
        return false;
    }

    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            needs: needs,
            phone: phone,
            detail: detail,
            type: type,
            name: name,
            org: org,
            street: street,
            country: country,
            area: area,
            city: city,
            zipcode: zipcode,
            submission_option: submission_option,
            Oid: GetCookie("OrderId"),
            action: "prores_edit"
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("OrderId", data.OrderId, 1, domain);
                $.ajax({
                    type: "POST",
                    url: "Control/ICHM0010.php",
                    async: false,  ///非同步執行
                    dataType: "json",
                    data: {
                        token: token,
                        type: type,
                        action: "changetype"
                    },
                    success: function (data) {
                        if (data.isSuccess) {
                            document.location.href = 'proregister_3.html';
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                    }
                })
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
});

function checkproregister() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0010.php",
        dataType: "json",
        data: {
            token: token,
            action: "gettype"
        },
        success: function (data) {
            if (data.isSuccess) {
                if (data.type != 0) {
                    $.ajax({
                        type: "POST",
                        url: "Control/ICHM0030.php",
                        dataType: "json",
                        data: {
                            token: token,
                            Oid: GetCookie("OrderId"),
                            action: "cancel"
                        },
                        success: function (data) {
                            if (data.isSuccess) {
                                if (data.type != 0) {
                                    DelCookie("OrderId", domain);
                                    alert('You have been registered. Please go to Registration->Status to view or edit or pay your order. Thank You!');
                                    window.location.href = 'ordersummary.html';
                                }
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                        }
                    })
                }
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

function DelOrder(id) {
    var Oid = id;
    if (confirm('Are you sure to delete this order?')) {
        $.ajax({
            type: "POST",
            url: "Control/ICHM0030.php",
            dataType: "json",
            data: {
                token: token,
                Oid: Oid,
                action: 'delorder'
            },
            success: function (data) {
                if (data.isSuccess) {
                    window.location.reload("ordersummary.html");
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
}

