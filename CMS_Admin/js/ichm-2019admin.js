var domain = window.location.hostname;
admincheckToken();
function GetpaperList() {
    var token = GetCookie("ADMIN_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0020.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getpaperlist'
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#dataTable").DataTable();
                for (var i = 0; i < data.datatable.length; i++) {
                    table.row.add([
                        data.datatable[i]["Id"],
                        data.datatable[i]["Paper"],
                        data.datatable[i]["Author"],
						data.datatable[i]["Main_Au"],
						data.datatable[i]["Main_Email"],
                        data.datatable[i]["Orangization"],
                        data.datatable[i]["Session"],
                        data.datatable[i]["Topic"],
                        data.datatable[i]["Presentation"],
                        data.datatable[i]["UploadTime"],
                        data.datatable[i]["Reviewer"],
                        data.datatable[i]["Isreview"],
                        data.datatable[i]["Ispass"],
                        data.datatable[i]["orderInfo"],
                        data.datatable[i]["Action"]]
                    ).draw(false);
                }
            }
            else {
                document.getElementById("dataTable").innerHTML = data.datatable;
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
function Getpaper() {
    var token = GetCookie("ADMIN_token");
    var pid = GetCookie("ADMIN_pid");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0020.php",
        dataType: "json",
        data: {
            token: token,
            pid: pid,
            action: 'getpaper'
        },
        success: function (data) {
            if (data.isSuccess) {
                $("#ptitle").append(data.paper);
                $("#pauthor").append(data.author);
                $("#ptopic").append(data.topic);
                $("#psession").append(data.session);
                $("#ppresentation").append(data.presentation);
                $("#mainreviewercommand").append(data.maincommand);
                $("#reviewercommand").append(data.command);
            }
            else {
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
function GetmemberList() {
    var token = GetCookie("ADMIN_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0010.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getmemberlist'
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#dataTable").DataTable();
                for (var i = 0; i < data.datatable.length; i++) {
                    table.row.add([
                        data.datatable[i]["Id"],
                        data.datatable[i]["Fname"] + " " + data.datatable[i]["Lname"],
                        data.datatable[i]["Title"],
                        data.datatable[i]["Email"],
                        data.datatable[i]["Psd"],
                        data.datatable[i]["Phone"],
                        data.datatable[i]["Organization"],
                        data.datatable[i]["Department"],
                        data.datatable[i]["Country"],
                        data.datatable[i]["Text"]
                    ]).draw(false);
                }
            }
            else {
                document.getElementById("dataTable").innerHTML = data.datatable;
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
function validaionPage(id) {
    SetCookie("ADMIN_memberid", id, 1, domain);
    document.location.href = 'identityvalidation.html';
}
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

$(document).on('change', '#nationality', function (event) {
    if ($("#nationality").val() == 0) {
        document.getElementById("idCard_area").style.display = "none";
        document.getElementById("studentCard_area").style.display = "block";
        document.getElementById("group_area1").style.display = "none";
        document.getElementById("group_area2").style.display = "none";
        document.getElementById("card_area1").style.display = "none";
        document.getElementById("card_area2").style.display = "none";
    }
    else if ($("#nationality").val() == 1) {
        document.getElementById("idCard_area").style.display = "block";
        document.getElementById("group_area1").style.display = "block";
        document.getElementById("group_area2").style.display = "block";
        $("#groupType").trigger("change");
    }
})
function getValidationInfo() {
    var token = GetCookie("ADMIN_token");
    var memberid = GetCookie("ADMIN_memberid");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0010.php",
        dataType: "json",
        data: {
            token: token,
            memberid: memberid,
            action: 'getValidationInfo'
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("ADMIN_apply", data.out["A_apply"], 1, domain);
                $("#member_name").val(data.out["A_name"]);
                if (data.out["A_identityImg"] != null)
                    document.getElementById("IDCard").innerHTML = "<img src='../CMS/" + data.out["A_identityImg"] + "' alt='身分證/居留證圖片' width='400px'>";
                if (data.out["A_stuImg"] != null)
                    document.getElementById("studentIDCard").innerHTML = "<img src='../CMS/" + data.out["A_stuImg"] + "' alt='學生證圖片' width='400px'>";
                if (data.out["A_cardNo"] != null)
                    $("#memberNo").val(data.out["A_cardNo"]);
                if (data.out["A_apply"] == 1) {
                    document.getElementById("btn_area").style.display = "none";
                    document.getElementById("back_area").style.display = "block";
                }
                else {
                    document.getElementById("btn_area").style.display = "block";
                    document.getElementById("back_area").style.display = "none";
                }
                if (data.out["A_apply"] == 1) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 3) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 4) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 6) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 7) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 8) {
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                    }
                }
                else if (data.out["A_apply"] == 2) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 3) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 4) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "none";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 5) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(0);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 6) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 3) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(1);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 7) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 2) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 4) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                    else if (data.out["A_checkIdentity"] == 5) {
                        $("#nationality").val(1);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
                        $("#groupType").val(2);
                        $("#groupType").trigger("change");
                    }
                }
                else if (data.out["A_apply"] == 8) {
                    if (data.out["A_checkIdentity"] == 1) {
                        $("#nationality").val(0);
                        $("#nationality").trigger("change");
                        document.getElementById("studentCard_area").style.display = "block";
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
function vi_pass(pass) {
    var token = GetCookie("ADMIN_token");
    var memberid = GetCookie("ADMIN_memberid");
    var apply = GetCookie("ADMIN_apply");
    var checkIdentity = 0;
    if (pass == 1)
        checkIdentity = apply;
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0010.php",
        dataType: "json",
        data: {
            token: token,
            memberid: memberid,
            checkIdentity: checkIdentity,
            action: 'updateValidationInfo'
        },
        success: function (data) {
            if (data.isSuccess) {
                $.ajax({
                    type: "POST",
                    url: "Mod/send_validation_mail.php",
                    async: false,
                    dataType: "json",
                    data: {
                        token: token,
                        memberid: memberid,
                        apply: apply,
                        checkIdentity: checkIdentity
                    },
                    success: function (data) {
                        if (data.isSuccess) {
                            alert(data.msg);
                            DelCookie('ADMIN_memberid', domain);
                            DelCookie('ADMIN_apply', domain);
                            document.location.href = 'managemember.html';
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
}
function GetorderList() {
    var token = GetCookie("ADMIN_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0040.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getorderlist'
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#orderTable").DataTable();
                for (var i = 0; i < data.ordertable.length; i++) {
                    table.row.add([
                        data.ordertable[i]["Id"],
                        data.ordertable[i]["Name"],
                        data.ordertable[i]["Dietary"],
                        data.ordertable[i]["Detailed"],
                        data.ordertable[i]["Type"],
                        data.ordertable[i]["Account"],
                        data.ordertable[i]["isPay"],
                        data.ordertable[i]["Country"],
                        data.ordertable[i]["Organization"],
                        data.ordertable[i]["paper"]
                    ]).draw(false);
                }
				var table2 = $("#orderTable2").DataTable();
                for (var i = 0; i < data.ordertable2.length; i++) {
                    table2.row.add([
                        data.ordertable2[i]["Id"],
                        data.ordertable2[i]["Name"],
                        data.ordertable2[i]["Dietary"],
                        data.ordertable2[i]["Detailed"],
                        data.ordertable2[i]["Type"],
                        data.ordertable2[i]["Account"],
                        data.ordertable2[i]["isPay"],
                        data.ordertable2[i]["Country"],
                        data.ordertable2[i]["Organization"],
                        data.ordertable2[i]["paper"]
                    ]).draw(false);
                }
            }
            else {
                document.getElementById("orderTable").innerHTML = data.ordertable;
				document.getElementById("orderTable2").innerHTML = data.ordertable2;
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
function orderdetail(oid) {
    SetCookie("ADMIN_oid", oid, 1, domain);
    document.location.href = "orderdetail.html";
}
function Getorderdetail() {
    var token = GetCookie("ADMIN_token");
    var orderid = GetCookie("ADMIN_oid");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0040.php",
        dataType: "json",
        data: {
            token: token,
            orderid: orderid,
            action: 'getorderdetail'
        },
        success: function (data) {
            if (data.isSuccess) {
                console.log(data.ordertable);
                $("#name").append("Name：" + data.ordertable[0]["Name"]);
                $("#country").append("Country：" + data.ordertable[0]["Country"]);
                $("#organization").append("Organization：" + data.ordertable[0]["Organization"]);
                $("#email").append("Email：" + data.ordertable[0]["Email"]);
                $("#phone").append("Phone：" + data.ordertable[0]["Phone"]);
                $("#dietary").append("<strong>Dietary：" + data.ordertable[0]["Dietary"]+"</strong>");
                $("#detailed").append("<strong>Detailed：" + data.ordertable[0]["Detailed"]+"</strong>");
                $("#item").append("Item：" + data.ordertable[0]["Type"]);
                $("#amount").append("Amount：NT$" + data.ordertable[0]["Account"]);
                $("#isPay").append("Is Pay：" + data.ordertable[0]["isPay"]);
                $("#paymentTime").append("Payment Time：" + data.ordertable[0]["PaymentTime"]);
                Getpaperinfo(data.ordertable[0]["Pid"]);
            }
            else {
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
function Getpaperinfo(pid) {
    var token = GetCookie("ADMIN_token");
    if (pid == 0)
        $("#ptitle").append("Just attend Conference or Workshop")
    else {
        $.ajax({
            type: "POST",
            url: "Control/CMSadmin0020.php",
            dataType: "json",
            data: {
                token: token,
                pid: pid,
                action: 'getpaper'
            },
            success: function (data) {
                if (data.isSuccess) {
                    $("#ptitle").append(data.paper);
                    $("#pauthor").append(data.author);
                    $("#ptopic").append(data.topic);
                    $("#psession").append(data.session);
                }
                else {
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
function GetReviewerList() {
    var token = GetCookie("ADMIN_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getreviewerlist'
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#dataTable").DataTable();
                for (var i = 0; i < data.datatable.length; i++) {
                    table.row.add([
                        data.datatable[i]["Name"],
                        data.datatable[i]["Mail"],
                        data.datatable[i]["Pwd"],
                        data.datatable[i]["CreatTime"],
                        data.datatable[i]["IsAgree"],
                        data.datatable[i]["Action"]]
                    ).draw(false);
                }
                var re_table = $("#re_dataTable").DataTable();
                for (var i = 0; i < data.re_datatable.length; i++) {
                    re_table.row.add([
                        data.re_datatable[i]["Name"],
                        data.re_datatable[i]["Mail"],
                        data.re_datatable[i]["Pwd"],
                        data.re_datatable[i]["CreatTime"],
                        data.re_datatable[i]["IsAgree"],
                        data.re_datatable[i]["Action"]]
                    ).draw(false);
                }
            }
            else {
                document.getElementById("dataTable").innerHTML = data.datatable;
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
function GetReviewerOptionList() {
    var token = GetCookie("ADMIN_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSadmin0030.php",
        dataType: "json",
        data: {
            token: token,
            action: 'selectlist'
        },
        success: function (data) {
            if (data.isSuccess) {
                $("#mainreviewer").append(data.list);
                $("#reviewer").append(data.re_list);
            }
            else {
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
function AddReviewer(type) {
    if (type == 1) {
        var email = $("#Mail").val();
        var name = $("#Name").val();
    }
    else if (type == 2) {
        var email = $("#re_Mail").val();
        var name = $("#re_Name").val();
    }
    var token = GetCookie("ADMIN_token");
    if (email == "" || name == "") {
        alert("Please Input the Data!");
    }
    else if (email.search(/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/) == -1) {
        alert("Email format error!");
    }
    else {
        $.ajax({
            type: "POST",
            url: "Control/CMSadmin0030.php",
            dataType: "json",
            data: {
                token: token,
                name: name,
                email: email,
                type: type,
                action: 'addreviwer'
            },
            success: function (data) {
                if (data.isSuccess) {
                    if (type == 1) {
                        var table = $("#dataTable").DataTable();
                        table.row.add([
                            data.datatable[0]["Name"],
                            data.datatable[0]["Mail"],
                            data.datatable[0]["Pwd"],
                            data.datatable[0]["CreatTime"],
                            data.datatable[0]["IsAgree"],
                            data.datatable[0]["Action"]]
                        ).draw(false);
                        document.getElementById("Name").value = "";
                        document.getElementById("Mail").value = "";
                    }
                    else if (type == 2) {
                        var table = $("#re_dataTable").DataTable();
                        table.row.add([
                            data.datatable[0]["Name"],
                            data.datatable[0]["Mail"],
                            data.datatable[0]["Pwd"],
                            data.datatable[0]["CreatTime"],
                            data.datatable[0]["IsAgree"],
                            data.datatable[0]["Action"]]
                        ).draw(false);
                        document.getElementById("re_Name").value = "";
                        document.getElementById("re_Mail").value = "";
                    }
                    send_mail(email, name, data.datatable[0]["Pwd"]);
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
}
function DelReviewer(id) {
    var rid = id.split("_")[1];
    var token = GetCookie("ADMIN_token");
    if (confirm('Are you sure you want to delete this reviewer?')) {
        $.ajax({
            type: "POST",
            url: "Control/CMSadmin0030.php",
            dataType: "json",
            data: {
                token: token,
                rid: rid,
                action: 'delreviwer'
            },
            success: function (data) {
                if (data.isSuccess) {
                    var myTable = $('#dataTable').DataTable();
                    myTable.rows().remove().draw(false);
                    GetReviewerList();
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
function setpaper(id) {
    SetCookie("ADMIN_pid", id, 1, domain);
    document.location.href = 'setpaper.html';
}
function addmain() {
    var token = GetCookie("ADMIN_token");
    var pid = GetCookie("ADMIN_pid");
    var rid = $("#mainreviewer").val();
    if (rid != 0) {
        var delete_bool = confirm("確定要指派或修改主審嗎?");
        if (delete_bool == true) {
            $.ajax({
                type: "POST",
                url: "Control/CMSadmin0020.php",
                dataType: "json",
                data: {
                    token: token,
                    pid: pid,
                    rid: rid,
                    action: 'addmain'
                },
                success: function (data) {
                    if (data.isSuccess) {
                        document.getElementById("mainreviewercommand").innerHTML = "";
                        $("#mainreviewercommand").append(data.command);
                        $("#mainreviewer").val(0);
                        send_main_reviewer_mail(pid,rid);
                    }
                    else if (data.isRepeat) {
                        alert("主審和審查人員有重複!同一名人員無法同時成為同篇論文的主審及其他審查人員!請將該人員從其他審查人員名單中刪除後，再指派為主審!");
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
    else {
        alert("請選擇一位主審");
    }
}
function addrtop() {
    var token = GetCookie("ADMIN_token");
    var pid = GetCookie("ADMIN_pid");
    var rid = $("#reviewer").val();
    if (rid != 0) {
        $.ajax({
            type: "POST",
            url: "Control/CMSadmin0020.php",
            dataType: "json",
            data: {
                token: token,
                pid: pid,
                rid: rid,
                action: 'addrtop'
            },
            success: function (data) {
                if (data.isSuccess) {
                    document.getElementById("reviewercommand").innerHTML = "";
                    $("#reviewercommand").append(data.command);
                    $("#reviewer").val(0);
                    send_main_reviewer_mail(pid,rid);
                }
                else if (data.isRepeat) {
                    alert("審查人員和主審有重複!同一名人員無法同時成為同篇論文的主審及其他審查人員!");
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
    else {
        alert("請選取審查人員");
    }
}
function deletepr(id) {
    var pid = GetCookie("ADMIN_pid");
    var token = GetCookie("ADMIN_token");
    if (confirm('Are you sure you want to delete this reviewer?')) {
        $.ajax({
            type: "POST",
            url: "Control/CMSadmin0020.php",
            async: false,
            dataType: "json",
            data: {
                token: token,
                pid: pid,
                prid: id,
                action: 'delpr'
            },
            success: function (data) {
                if (data.isSuccess) {
                    document.getElementById("reviewercommand").innerHTML = "";
                    $("#reviewercommand").append(data.command);
                }
                else {
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
            }
        })
    }
}

function send_mail(email, name, pwd) {
    $.ajax({
        type: "POST",
        url: "Mod/send_mail.php",
        dataType: "json",
        async: false,
        data: {
            email: email,
            name: name,
            pwd: pwd
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("已經寄出EMAIL邀請審查委員!");
            }
            else {
                alert(data.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function send_main_reviewer_mail(pid,rid) {
    $.ajax({
        type: "POST",
        url: "Mod/send_main_reviewer_mail.php",
        dataType: "json",
        async: false,
        data: {
            pid: pid,
            rid: rid
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("Success!");
            }
            else {
                alert("Fail!");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function sendreviewrequestagain() {
    $.ajax({
        type: "POST",
        url: "Mod/send_review_request_again.php",
        dataType: "json",
        data: {
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("已重新寄出EMAIL通知尚未完成審核之主審及Reviewer!");
            }
            else {
                alert("Fail");
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function sendordermail() {
    //alert("缺信件內容，尚未開放催繳功能!");
    $.ajax({
        type: "POST",
        url: "Mod/send_order_mail.php",
        dataType: "json",
        data: {
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("已經寄出EMAIL進行催繳!");
            }
            else {
                alert("Fail");
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function sendregistrationmail() {
    $.ajax({
        type: "POST",
        url: "Mod/send_registration_mail.php",
        dataType: "json",
        data: {
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("已寄出EMAIL進行註冊通知!");
            }
            else {
                alert("Fail");
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function sendreviewermailagain() {
    $.ajax({
        type: "POST",
        url: "Mod/send_reviewer_mail_again.php",
        dataType: "json",
        data: {
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("已重新寄出EMAIL通知尚未確認之主審及Reviewer!");
            }
            else {
                alert("Fail");
            }
        },
        beforeSend: function () {
            $('#loading').show();
        },
        complete: function () {
            $('#loading').hide();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
}
function downloadexcel() {
	document.location.href = 'Mod/download_paperandorder_excel.php';
}
function logout() {
    delete_all_cookie();
    document.location.href = 'login.html';
}
