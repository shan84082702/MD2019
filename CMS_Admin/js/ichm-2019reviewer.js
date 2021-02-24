var domain = window.location.hostname;
reviewercheckToken();
function change() {
    var token = GetCookie("REVIEWER_token");
    var Psw = $('#inputPassword').val();
    var NewPsw = $('#inputNewPassword').val();
    var NewPswAgain = $('#inputNewPasswordAgain').val();
    if (Psw.trim() == "") {
        alert("Please enter your Password.");
        eval("document.getElementById('inputPassword').focus()");
        return false;
    }
    if (NewPsw.trim() == "") {
        alert("Please enter your New Password.");
        eval("document.getElementById('inputNewPassword').focus()");
        return false;
    }
    if (NewPswAgain.trim() == "") {
        alert("Please enter your New Password Again.");
        eval("document.getElementById('inputNewPasswordAgain').focus()");
        return false;
    }
    if (NewPsw.trim() != NewPswAgain.trim()) {
        alert("Please check your New Password.");
        eval("document.getElementById('inputNewPassword').focus()");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0010.php",
        dataType: "json",
        data: {
            action: 'check',
            token: token,
            Psw: Psw
        },
        success: function (data) {
            if (data.isSuccess) {
                $.ajax({
                    type: "POST",
                    url: "Control/CMSreviewer0010.php",
                    async: false,
                    dataType: "json",
                    data: {
                        action: 'change',
                        token: token,
                        NewPsw: NewPsw
                    },
                    success: function (data) {
                        if (data.isSuccess) {
                            SetCookie("REVIEWER_token", data.token, 1, domain);
                            alert("修改成功!");
                            $('#inputPassword').val("");
                            $('#inputNewPassword').val("");
                            $('#inputNewPasswordAgain').val("");
                        }
                        else {
                            alert(data.msg);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
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
function logout() {
    delete_all_cookie();
    document.location.href = 'reviewerlogin.html';
}
function getMainReviewrPaperList() {
    var token = GetCookie("REVIEWER_token");
    var Rid = GetCookie("REVIEWER_Rid");
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            action: 'getMainReviewrPaperList',
            Rid: Rid,
            token: token
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#maindataTable").DataTable();
                console.log(data.datatable);
                for (var i = 0; i < data.datatable.length; i++) {
                    table.row.add([
                        data.datatable[i]["Id"],
                        data.datatable[i]["Paper"],
                        data.datatable[i]["Author"],
                        data.datatable[i]["Orangization"],
						data.datatable[i]["Session"],
						data.datatable[i]["Topic"],
						data.datatable[i]["Presentation"],
                        data.datatable[i]["Reviewer"],
                        data.datatable[i]["Action"]]
                    ).draw(false);
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
function getReviewrPaperList() {
    var token = GetCookie("REVIEWER_token");
    var Rid = GetCookie("REVIEWER_Rid");
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            action: 'getReviewrPaperList',
            Rid: Rid,
            token: token
        },
        success: function (data) {
            if (data.isSuccess) {
                var table = $("#dataTable").DataTable();
                for (var i = 0; i < data.datatable.length; i++) {
                    table.row.add([
                        data.datatable[i]["Id"],
                        data.datatable[i]["Paper"],
                        data.datatable[i]["Author"],
                        data.datatable[i]["Orangization"],
						data.datatable[i]["Session"],
						data.datatable[i]["Topic"],
						data.datatable[i]["Presentation"],
                        data.datatable[i]["Reviewer"],
                        data.datatable[i]["Action"]]
                    ).draw(false);
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
function setmainpaper(id) {
    SetCookie("REVIEWER_pid", id, 1, domain);
    document.location.href = 'mainreviewpaper.html';
}
function setpaper(id) {
    SetCookie("REVIEWER_pid", id, 1, domain);
    document.location.href = 'reviewpaper.html';
}
function getmainreviewpaper() {
    var token = GetCookie("REVIEWER_token");
    var pid = GetCookie("REVIEWER_pid");
    var Rid = GetCookie("REVIEWER_Rid");
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            token: token,
            Rid: Rid,
            pid: pid,
            action: 'getmainpaper'
        },
        success: function (data) {
            if (data.isSuccess) {
                $("#ptitle").append(data.paper);
                $("#pauthor").append(data.author);
				$("#ptopic").append(data.topic);
				$("#psession").append(data.session);
				$("#ppresentation").append(data.presentation);
                $("#MRP_reviewercommand").append(data.command);
                if (data.isPass != null)
                    $("input[name='recive'][value='" + data.isPass + "']").attr("checked", true);
                $("#maincommand").append(data.maincommand);
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
function getreviewpaper() {
    var token = GetCookie("REVIEWER_token");
    var pid = GetCookie("REVIEWER_pid");
    var Rid = GetCookie("REVIEWER_Rid");
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            token: token,
            Rid: Rid,
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
                $("#command").append(data.command);

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
function mainreviewsubmit() {
    var token = GetCookie("REVIEWER_token");
    var pid = GetCookie("REVIEWER_pid");
    var Rid = GetCookie("REVIEWER_Rid");
    var isPass = $("input[name='recive']:checked").val();
    var command = $('#maincommand').val();
	if(isPass==null){
		alert("請選擇是否接受論文");
        eval("document.getElementByName('recive').focus()");
        return false;
	}
	if(command.trim() == ""){
		alert("Please enter the command.");
        eval("document.getElementById('maincommand').focus()");
        return false;
	}
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            token: token,
            Rid: Rid,
            pid: pid,
            isPass: isPass,
            command: command,
            action: 'mainreviewsubmit'
        },
        success: function (data) {
            if (data.isSuccess) {
                if (isPass == 1) {
                    $.ajax({
                        type: "POST",
                        url: "Mod/send_review_mail.php",
                        async: false,
                        dataType: "json",
                        data: {
                            Pid: pid
                        },
                        success: function (data) {
                            if (data.isSuccess) {
                                alert("評論以及寄信成功");
                                document.location.href = 'reviewlist.html';
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                        }
                    })
                }
                else if (isPass == 0) {
                    alert("評論成功");
                    document.location.href = 'reviewlist.html';
                    /*$.ajax({
                        type: "POST",
                        url: "Mod/send_review_reject_mail.php",
                        async: false,
                        dataType: "json",
                        data: {
                            Pid: pid
                        },
                        success: function (data) {
                            if (data.isSuccess) {
                                alert("評論以及寄信成功");
                                document.location.href = 'reviewlist.html';
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                        }
                    })*/
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

function reviewsubmit() {
    var token = GetCookie("REVIEWER_token");
    var pid = GetCookie("REVIEWER_pid");
    var Rid = GetCookie("REVIEWER_Rid");
    var command = $('#command').val();
	if(command.trim() == ""){
		alert("Please enter the command.");
        eval("document.getElementById('command').focus()");
        return false;
	}
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0020.php",
        dataType: "json",
        data: {
            token: token,
            Rid: Rid,
            pid: pid,
            command: command,
            action: 'reviewsubmit'
        },
        success: function (data) {
            if (data.isSuccess) {
                alert("評論成功");
                document.location.href = 'reviewlist.html';
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

function rc_yes() {
    var token = GetCookie("REVIEWER_token");
    var bool = confirm("Are you sure to become a reviewer of MD2019?");
    if (bool == true) {
        $.ajax({
            type: "POST",
            url: "Control/CMSreviewer0010.php",
            dataType: "json",
            data: {
                token: token,
                result: 1,
                action: 'isAgree'
            },
            success: function (data) {
                if (data.isSuccess) {
                    document.location.href = 'reviewlist.html';
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

function rc_no() {
    var token = GetCookie("REVIEWER_token");
    var bool = confirm("Are you sure to \"reject to\" become a reviewer of MD2019?");
    if (bool == true) {
        $.ajax({
            type: "POST",
            url: "Control/CMSreviewer0010.php",
            dataType: "json",
            data: {
                token: token,
                result: 0,
                action: 'isAgree'
            },
            success: function (data) {
                if (data.isSuccess) {
                    alert("You reject to become a reviewer of MD2019.");
                    logout();
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

function isCheckorNot() {
    var token = GetCookie("REVIEWER_token");
    $.ajax({
        type: "POST",
        url: "Control/CMSreviewer0010.php",
        dataType: "json",
        data: {
            token: token,
            result: 1,
            action: 'checkAgree'
        },
        success: function (data) {
            if (!data.notCheck) {
                if (data.agree)
                    document.location.href = 'reviewlist.html';
                else {
                    alert("You reject to become a reviewer of MD2019.");
                    logout();
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