var token = GetCookie("CMS_token");
var domain = window.location.hostname;
//check deadline of abstract submission
/*var txtDateString = "2019/11/18";
function checkDeadline(){
    var currDate = Date.parse((new Date()).toDateString());
    var txtDate = Date.parse(txtDateString);
    if (currDate > txtDate) {
        alert('You miss the deadline for abstract submission.\nIf you\'ve aleady submitted the abstract and you want to submit the camera-ready version, please go to "submitted paper" page to edit the abstract you uploaded before and upload the camera-ready version.\nThan you.');
        document.location.href = 'proposals.html';
    }
}*/
var Isinsize = false;
var Isinsize2 = false;
$('#upfile').bind('change', function () {
    if (this.files[0].size / 1024 / 1024 > 150) {
        Isinsize = false;
        alert('This file size is: ' + this.files[0].size / 1024 / 1024 + "MB");
    }
    else {
        Isinsize = true;
    }
});
$('#upfile2').bind('change', function () {
    if (this.files[0].size / 1024 / 1024 > 150) {
        Isinsize2 = false;
        alert('This file size is: ' + this.files[0].size / 1024 / 1024 + "MB");
    }
    else {
        Isinsize2 = true;
    }
});
/*show author adding area*/
$("#addauthor").click(function (e) {
    var element1 = document.getElementById("bnarea");
    element1.style.display = 'none';
    var element2 = document.getElementById("addarea");
    element2.style.display = 'inline';
});
/*hide author adding area*/
$("#bncler").click(function (e) {
    var element1 = document.getElementById("bnarea");
    element1.style.display = 'inline';
    var element2 = document.getElementById("addarea");
    element2.style.display = 'none';
});
/*add author*/
$("#bnadd").click(function (e) {
    var fName = document.getElementById("ichm_fname").value;
    var lName = document.getElementById("ichm_lname").value;
    var Mail = document.getElementById("ichm_mail").value;
    var Orga = document.getElementById("ichm_orga").value;
    var Dept = document.getElementById("ichm_dept").value;
    var Country = document.getElementById("ichm_country").value;
    if (Mail.trim() == "") {
        alert("Please enter your email.");
        eval("document.getElementById('ichm_mail').focus()");
        return false;
    }
    if (fName.trim() == "") {
        alert("Please enter your first name.");
        eval("document.getElementById('ichm_fname').focus()");
        return false;
    }
    if (lName.trim() == "") {
        alert("Please enter your last name.");
        eval("document.getElementById('ichm_lname').focus()");
        return false;
    }
    if (Country == 0) {
        alert("Please select your country");
        eval("document.getElementById('ichm_country').focus()");
        return false;
    }
    if (Orga.trim() == "") {
        alert("Please enter your organization.");
        eval("document.getElementById('ichm_orga').focus()");
        return false;
    }
    if (Dept.trim() == "") {
        alert("Please enter your department.");
        eval("document.getElementById('ichm_dept').focus()");
        return false;
    }
    if (Mail.search(/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/) == -1) {
        alert("Email format error!");
        eval("document.getElementById('ichm_mail').focus()");
        return false;
    }
    var Name = fName + " " + lName;
    var table = document.getElementById("author");
    var newRow = table.insertRow(table.rows.length);
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);
    var cell6 = newRow.insertCell(5);
    var cell7 = newRow.insertCell(6);
    var cell8 = newRow.insertCell(7);
    var cell9 = newRow.insertCell(8);
    var rownm;
    if (table.rows.length - 2 != 1 && table.rows.length < 3) {
        rownm = 0;
    }
    else {
        rownm = parseInt(table.rows.length);
    }
    var stroptval = "";
    for (var num = 1; num < parseInt(rownm); num++) {
        stroptval += "<option value='" + num + "'>" + num + "</option>"
    }
    for (var resetrow = 1; resetrow < parseInt(rownm); resetrow++) {
        var resetcell = table.rows[resetrow].cells[0];
        var rowid = parseInt(table.rows.length) - 1;
        if (resetrow == rowid)
            resetcell.innerHTML = "<select id='" + Mail + "'>" + stroptval + "</select>";
        else
            resetcell.innerHTML = "<select id='" + table.rows[resetrow].cells[2].innerHTML + "'>" + stroptval + "</select>";
    }
    cell2.innerHTML = Name;
    cell3.innerHTML = Mail;
    cell4.innerHTML = Orga;
    cell5.innerHTML = "<button id='" + rownm + 1 + "' onclick='rmauthor(this)'>Delete</button>";
    cell6.innerHTML = Country;
    cell6.style = "display:none;";
    cell7.innerHTML = Dept;
    cell7.style = "display:none;";
    cell8.innerHTML = fName;
    cell8.style = "display:none;";
    cell9.innerHTML = lName;
    cell9.style = "display:none;";

    document.getElementById("ichm_fname").value = "";
    document.getElementById("ichm_lname").value = "";
    document.getElementById("ichm_mail").value = "";
    document.getElementById("ichm_orga").value = "";
    document.getElementById("ichm_dept").value = "";
    document.getElementById("ichm_country").value = 0;
});
/*delete author*/
function rmauthor(sel) {
    var table = document.getElementById("author");
    var rownm;
    if (table.rows.length - 2 != 1 && table.rows.length < 3) {
        rownm = 0;
    }
    else {
        rownm = parseInt(table.rows.length - 1);
    }
    var stroptval = "";
    for (var num = 1; num < parseInt(rownm); num++) {
        stroptval += "<option value='" + num + "'>" + num + "</option>"
    }
    for (var resetrow = 1; resetrow < parseInt(rownm) + 1; resetrow++) {
        var resetcell = table.rows[resetrow].cells[0];
        resetcell.innerHTML = "<select id='" + table.rows[resetrow].cells[2].innerHTML + "'>" + stroptval + "</select>";
    }
    $(sel).closest('tr').remove();
};
/*upload*/
$("#btnnext1").click(function (e) {
    var Title = document.getElementById("ichm_title").value;
    var Session = document.getElementById("ichm_session").value;
    var Topic = document.getElementById("ichm_topic").value;
    var Presentation = document.getElementById("ichm_presentation").value;
    if (Title == "") {
        alert("Please enter the paper title.");
        eval("document.getElementById('ichm_title').focus()");
        return false;
    }
    if (Session == 0) {
        alert("Please select a paper category");
        eval("document.getElementById('ichm_session').focus()");
        return false;
    }
    if (Topic == 0) {
        alert("Please select a topic");
        eval("document.getElementById('ichm_topic').focus()");
        return false;
    }
    if (document.getElementById("upfile").value == "") {
        alert("Please upload the paper(PDF)");
        eval("document.getElementById('upfile').focus()");
        return false;
    }
    if (document.getElementById("upfile2").value == "") {
        alert("Please upload the paper(DOC or DOCX)");
        eval("document.getElementById('upfile2').focus()");
        return false;
    }

    //check if the order of author list is repeat 
    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    var repeat = 0;
    var mail_repeat = 0;
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (table.rows[i].cells[2].innerHTML == table.rows[j].cells[2].innerHTML) {
                mail_repeat = 1;
            }
        }
    }
    if (mail_repeat == 1) {
        alert("Author's Email can not be repeated");
        return false;
    }
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (document.getElementById(table.rows[i].cells[2].innerHTML).value == document.getElementById(table.rows[j].cells[2].innerHTML).value) {
                repeat = 1;
            }
        }
    }
    if (repeat == 1) {
        alert("Author order can not be repeated");
        return false;
    }

    //check file size and type
    var input = document.getElementById("upfile");
    var input2 = document.getElementById("upfile2");
    var fileName = input.value;
    var fileName2 = input2.value;
    if (!Isinsize) {
        alert("the file size is over 150MB");
        eval("document.getElementById('upfile').focus()");
        return false;
    }
    if (!Isinsize2) {
        alert("the file size is over 150MB");
        eval("document.getElementById('upfile2').focus()");
        return false;
    }
    if (!validate_fileupload(fileName)) {
        alert("the file type is error");
        eval("document.getElementById('upfile').focus()");
        return false;
    }
    if (!validate_fileupload2(fileName2)) {
        alert("the file type is error");
        eval("document.getElementById('upfile2').focus()");
        return false;
    }

    //if everything is correct, upload paper and all the information
    $('#loading').show();
    var arr = new Array();
    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    for (var i = 1; i <= rownm; i++) {
        var obj = new Object;
        obj.Order = document.getElementById(table.rows[i].cells[2].innerHTML).value;
        obj.Email = table.rows[i].cells[2].innerHTML;
        obj.Organization = table.rows[i].cells[3].innerHTML;
        obj.Country = table.rows[i].cells[5].innerHTML;
        obj.Department = table.rows[i].cells[6].innerHTML;
        obj.FName = table.rows[i].cells[7].innerHTML;
        obj.LName = table.rows[i].cells[8].innerHTML;
        obj.isUsed = 0;
        arr = arr.concat(obj);
    }
    var author_string = JSON.stringify(arr);
    //document.getElementById("Author_string").value=author_string;
    var form = document.forms.namedItem("paperinfo");
    form.addEventListener("submit", function (ev) {
        oData = new FormData(form);
        oData.append("token", token);
        oData.append("Title", Title);
        oData.append("Session", Session);
        oData.append("Topic", Topic);
        oData.append("Presentation", Presentation);
        oData.append("Author_string", author_string);
        oData.append("action", "uploadpaper");
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "Control/ICHM0020.php", true);
        oReq.onload = function (oEvent) {
            if (oReq.status == 200) {
                $('#loading').hide();
                document.location.href = 'previewpaper.html';
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
});

function validate_fileupload(fileName) {
    var allowed_extensions = new Array("pdf");
    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
    for (var i = 0; i <= allowed_extensions.length; i++) {
        if (allowed_extensions[i] == file_extension) {
            return true; // valid file extension
        }
    }
    return false;
}
function validate_fileupload2(fileName) {
    var allowed_extensions = new Array("doc", "docx");
    var file_extension = fileName.split('.').pop().toLowerCase(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.
    for (var i = 0; i <= allowed_extensions.length; i++) {
        if (allowed_extensions[i] == file_extension) {
            return true; // valid file extension
        }
    }
    return false;
}

function getMainAuthor() {
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
                var table = document.getElementById("author");
                var newRow = table.insertRow(table.rows.length);
                var cell1 = newRow.insertCell(0);
                var cell2 = newRow.insertCell(1);
                var cell3 = newRow.insertCell(2);
                var cell4 = newRow.insertCell(3);
                var cell5 = newRow.insertCell(4);
                var cell6 = newRow.insertCell(5);
                var cell7 = newRow.insertCell(6);
                var cell8 = newRow.insertCell(7);
                var cell9 = newRow.insertCell(8);

                cell1.innerHTML = "<select id='" + data.Email + "'><option value='1'>1</option></select>"
                cell2.innerHTML = data.FName + " " + data.LName;
                cell3.innerHTML = data.Email;
                cell4.innerHTML = data.Orga;
                cell5.innerHTML = "";
                cell6.innerHTML = data.Country;
                cell6.style = "display:none;";
                cell7.innerHTML = data.Dept;
                cell7.style = "display:none;";
                cell8.innerHTML = data.FName;
                cell8.style = "display:none;";
                cell9.innerHTML = data.LName;
                cell9.style = "display:none;";
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

$("#btncancel1").click(function (e) {
    document.location.href = 'index.html';
});
//---------------------------previewpaper--------------------------------
/*get paper ID*/
function getPaperID() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        async: false,
        dataType: "json",
        data: {
            token: token,
            action: 'getPid'
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("Pid", data.Pid, 1, domain);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })

}

/*Get paper's file and information*/
function getPreviewPaper(type) {
    var Pid = GetCookie('Pid');
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'getPaperInfo'
        },
        success: function (data) {
            if (data.isSuccess) {
                if (type == 0) {
                    document.getElementById("Title").innerHTML = data.Title;
                    if (data.SessionID == 0)
                        document.getElementById("Session").innerHTML = "(have not selected a category yet)";
                    else
                        document.getElementById("Session").innerHTML = data.Session;
                    if (data.TopicID == 0)
                        document.getElementById("Topic").innerHTML = "(have not selected a topic yet)";
                    else
                        document.getElementById("Topic").innerHTML = data.Topic;
                    if (data.PresentationID == 0)
                        document.getElementById("Presentation").innerHTML = "Oral";
                    else if (data.PresentationID == 1)
                        document.getElementById("Presentation").innerHTML = "Poster";
                    if (data.Path == "")
                        document.getElementById("fileName").innerHTML = "<a>" + data.Title + ".pdf</a>";
                    else
                        document.getElementById("fileName").innerHTML = "<a href=" + data.Path + ">" + data.Title + ".pdf</a>";
                    if (data.Path2 == "")
                        document.getElementById("fileName2").innerHTML = "<a>" + data.Title + ".doc/.docx</a>";
                    else
                        document.getElementById("fileName2").innerHTML = "<a href=" + data.Path2 + ">" + data.Title + ".doc/.docx</a>";
                }
                else {
                    document.getElementById("SP_Pid").innerHTML = Pid;
                    document.getElementById("SP_Title").innerHTML = data.Title;
                    if (data.SessionID == 0)
                        document.getElementById("SP_Session").innerHTML = "(have not selected a category yet)";
                    else
                        document.getElementById("SP_Session").innerHTML = data.Session;
                    if (data.TopicID == 0)
                        document.getElementById("SP_Topic").innerHTML = "(have not selected a topic yet)";
                    else
                        document.getElementById("SP_Topic").innerHTML = data.Topic;
                    if (data.PresentationID == 0)
                        document.getElementById("SP_Presentation").innerHTML = "Oral";
                    else if (data.PresentationID == 1)
                        document.getElementById("SP_Presentation").innerHTML = "Poster";
                    if (data.Path == "")
                        document.getElementById("SP_fileName").innerHTML = "<a>" + data.Title + ".pdf</a>";
                    else
                        document.getElementById("SP_fileName").innerHTML = "<a href=" + data.Path + ">" + data.Title + ".pdf</a>";
                    if (data.Path2 == "")
                        document.getElementById("SP_fileName2").innerHTML = "<a>" + data.Title + ".doc/.docx</a>";
                    else
                        document.getElementById("SP_fileName2").innerHTML = "<a href=" + data.Path2 + ">" + data.Title + ".doc/.docx</a>";
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
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'getPaperAuthor'
        },
        success: function (data) {
            if (data.isSuccess) {
                if (type == 0)
                    document.getElementById("author_table").innerHTML = data.au_table;
                else
                    document.getElementById("SP_author_table").innerHTML = data.au_table;
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

$("#preview_cancel").click(function (e) {
    var Pid = GetCookie('Pid');
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'cancelSubmit'
        },
        success: function (data) {
            if (data.isSuccess) {
                alert(data.msg);
                DelCookie('Pid', domain);
                document.location.href = 'proposals.html';
            }
            else {
                alert(json.msg);
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

$("#preview_submit").click(function (e) {
    var Pid = GetCookie('Pid');
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'submitDone'
        },
        success: function (data) {
            if (data.isSuccess) {
                $.ajax({
                    type: "POST",
                    url: "Mod/mail.php",
                    async: false,
                    dataType: "json",
                    data: {
                        token: token,
                        Pid: Pid,
                        action: 'mail'
                    },
                    success: function (data) {
                        if (data.isSuccess) {
                            DelCookie('Pid', domain);
                            document.location.href = 'proposals.html';
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        //alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
                    }
                })
                alert(data.msg);
            }
            else {
                alert(json.msg);
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
//---------------------------proposals--------------------------------
function getPaperList() {
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            action: 'getSelfPaperList'
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("submit_paper_table").innerHTML = data.paper_table;
                document.getElementById("submit_paper_table_co").innerHTML = data.paper_table_co;
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
function submittedpaper(pid) {
    SetCookie("Pid", pid, 1, domain);
    document.location.href = "submittedproposal.html";
}
function viewpaper(pid) {
    SetCookie("Pid", pid, 1, domain);
    document.location.href = "submittedproposalview.html";
}
function paperreview(pid) {
    SetCookie("Pid", pid, 1, domain);
    document.location.href = "paperreview.html";
}
function getpaperreview() {
    var pid = GetCookie("Pid");
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            pid: pid,
            action: 'getpaperreview'
        },
        success: function (data) {
            if (data.isSuccess) {
                $("#command_area").append(data.command);
                if (data.isPass != null)
                    $("input[name='recive'][value='" + data.isPass + "']").attr("checked", true);
                $("#maincommand").val(data.maincommand);
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
$("#SP_btnback").click(function (e) {
    DelCookie('Pid', domain);
    document.location.href = "proposals.html";
});
$("#SP_btnedit").click(function (e) {
    document.location.href = "editsubmittedpaper.html";
});
function ESP_getPaperInfo() {
    var Pid = GetCookie('Pid');
    $.ajax({
        type: "POST",
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'getPaperInfo'
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("ichm_title").value = data.Title;
                document.getElementById("ichm_session").value = data.SessionID;
                document.getElementById("ichm_topic").value = data.TopicID;
                document.getElementById("ichm_presentation").value = data.PresentationID;
                if (data.Path == "")
                    document.getElementById("fileName").innerHTML = "<a>" + data.Title + ".pdf</a>";
                else
                    document.getElementById("fileName").innerHTML = "<a href=" + data.Path + ">" + data.Title + ".pdf</a>";
                if (data.Path2 == "")
                    document.getElementById("fileName2").innerHTML = "<a>" + data.Title + ".doc/.docx</a>";
                else
                    document.getElementById("fileName2").innerHTML = "<a href=" + data.Path2 + ">" + data.Title + ".doc/.docx</a>";
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
        url: "Control/ICHM0020.php",
        dataType: "json",
        data: {
            token: token,
            Pid: Pid,
            action: 'getESPAuthor'
        },
        success: function (data) {
            if (data.isSuccess) {
                document.getElementById("author").innerHTML = data.au_table;
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

//edit submitted paper
$("#btnnext2").click(function (e) {
    var Title = document.getElementById("ichm_title").value;
    var Session = document.getElementById("ichm_session").value;
    var Topic = document.getElementById("ichm_topic").value;
    var Presentation = document.getElementById("ichm_presentation").value;
    if (Title == "") {
        alert("Please enter the paper title.");
        eval("document.getElementById('ichm_title').focus()");
        return false;
    }
    if (Session == 0) {
        alert("Please select a paper category");
        eval("document.getElementById('ichm_session').focus()");
        return false;
    }
    if (Topic == 0) {
        alert("Please select a topic");
        eval("document.getElementById('ichm_topic').focus()");
        return false;
    }

    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    var repeat = 0;
    var mail_repeat = 0;
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (table.rows[i].cells[2].innerHTML == table.rows[j].cells[2].innerHTML) {
                mail_repeat = 1;
            }
        }
    }
    if (mail_repeat == 1) {
        alert("Author's Email can not be repeated");
        return false;
    }
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (document.getElementById(table.rows[i].cells[2].innerHTML).value == document.getElementById(table.rows[j].cells[2].innerHTML).value) {
                repeat = 1;
            }
        }
    }
    if (repeat == 1) {
        alert("Author order can not be repeated");
        return false;
    }

    var input = document.getElementById("upfile");
    var input2 = document.getElementById("upfile2");
    var fileName = input.value;
    var fileName2 = input2.value;
    if (fileName != "") {
        if (!Isinsize) {
            alert("the file size is over 150MB");
            eval("document.getElementById('upfile').focus()");
            return false;
        }
        if (!validate_fileupload(fileName)) {
            alert("the file type is error");
            eval("document.getElementById('upfile').focus()");
            return false;
        }
    }
    if (fileName2 != "") {
        if (!Isinsize2) {
            alert("the file size is over 150MB");
            eval("document.getElementById('upfile2').focus()");
            return false;
        }
        if (!validate_fileupload2(fileName2)) {
            alert("the file type is error");
            eval("document.getElementById('upfile2').focus()");
            return false;
        }
    }

    $('#loading').show();
    var arr = new Array();
    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    for (var i = 1; i <= rownm; i++) {
        var obj = new Object;
        obj.Order = document.getElementById(table.rows[i].cells[2].innerHTML).value;
        obj.Email = table.rows[i].cells[2].innerHTML;
        obj.Organization = table.rows[i].cells[3].innerHTML;
        obj.Country = table.rows[i].cells[5].innerHTML;
        obj.Department = table.rows[i].cells[6].innerHTML;
        obj.FName = table.rows[i].cells[7].innerHTML;
        obj.LName = table.rows[i].cells[8].innerHTML;
        obj.isUsed = 1;
        arr = arr.concat(obj);
    }
    var author_string = JSON.stringify(arr);
    //document.getElementById("Author_string").value=author_string;

    var form = document.forms.namedItem("editpaperinfo");
    var Pid = GetCookie("Pid");
    form.addEventListener("submit", function (ev) {
        oData = new FormData(form);
        oData.append("token", token);
        oData.append("Title", Title);
        oData.append("Session", Session);
        oData.append("Topic", Topic);
        oData.append("Presentation", Presentation);
        oData.append("Author_string", author_string);
        oData.append("Pid", Pid);
        oData.append("action", "uploadeditpaper");
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "Control/ICHM0020.php", true);
        oReq.onload = function (oEvent) {
            if (oReq.status == 200) {
                $('#loading').hide();
                alert("Successful editing!");
                DelCookie('Pid', domain);
                document.location.href = 'proposals.html';
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
});


$("#edit_next").click(function (e) {
    var Title = document.getElementById("ichm_title").value;
    var Session = document.getElementById("ichm_session").value;
    var Topic = document.getElementById("ichm_topic").value;
    var Presentation = document.getElementById("ichm_presentation").value;
    if (Title == "") {
        alert("Please enter the paper title.");
        eval("document.getElementById('ichm_title').focus()");
        return false;
    }
    if (Session == 0) {
        alert("Please select a paper category");
        eval("document.getElementById('ichm_session').focus()");
        return false;
    }
    if (Topic == 0) {
        alert("Please select a topic");
        eval("document.getElementById('ichm_topic').focus()");
        return false;
    }

    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    var repeat = 0;
    var mail_repeat = 0;
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (table.rows[i].cells[2].innerHTML == table.rows[j].cells[2].innerHTML) {
                mail_repeat = 1;
            }
        }
    }
    if (mail_repeat == 1) {
        alert("Author's Email can not be repeated");
        return false;
    }
    for (var i = 1; i < rownm; i++) {
        for (var j = i + 1; j <= rownm; j++) {
            if (document.getElementById(table.rows[i].cells[2].innerHTML).value == document.getElementById(table.rows[j].cells[2].innerHTML).value) {
                repeat = 1;
            }
        }
    }
    if (repeat == 1) {
        alert("Author order can not be repeated");
        return false;
    }

    var input = document.getElementById("upfile");
    var input2 = document.getElementById("upfile2");
    var fileName = input.value;
    var fileName2 = input2.value;
    if (fileName != "") {
        if (!Isinsize) {
            alert("the file size is over 150MB");
            eval("document.getElementById('upfile').focus()");
            return false;
        }
        if (!validate_fileupload(fileName)) {
            alert("the file type is error");
            eval("document.getElementById('upfile').focus()");
            return false;
        }
    }
    if (fileName2 != "") {
        if (!Isinsize2) {
            alert("the file size is over 150MB");
            eval("document.getElementById('upfile2').focus()");
            return false;
        }
        if (!validate_fileupload2(fileName2)) {
            alert("the file type is error");
            eval("document.getElementById('upfile2').focus()");
            return false;
        }
    }

    $('#loading').show();
    var arr = new Array();
    var table = document.getElementById("author");
    rownm = parseInt(table.rows.length - 1);
    for (var i = 1; i <= rownm; i++) {
        var obj = new Object;
        obj.Order = document.getElementById(table.rows[i].cells[2].innerHTML).value;
        obj.Email = table.rows[i].cells[2].innerHTML;
        obj.Organization = table.rows[i].cells[3].innerHTML;
        obj.Country = table.rows[i].cells[5].innerHTML;
        obj.Department = table.rows[i].cells[6].innerHTML;
        obj.FName = table.rows[i].cells[7].innerHTML;
        obj.LName = table.rows[i].cells[8].innerHTML;
        obj.isUsed = 0;
        arr = arr.concat(obj);
    }
    var author_string = JSON.stringify(arr);
    //document.getElementById("Author_string").value=author_string;
    var form = document.forms.namedItem("editpaperinfo");
    var Pid = GetCookie("Pid");
    form.addEventListener("submit", function (ev) {
        oData = new FormData(form);
        oData.append("token", token);
        oData.append("Title", Title);
        oData.append("Session", Session);
        oData.append("Topic", Topic);
        oData.append("Presentation", Presentation);
        oData.append("Author_string", author_string);
        oData.append("Pid", Pid);
        oData.append("action", "uploadeditpaper");
        var oReq = new XMLHttpRequest();
        oReq.open("POST", "Control/ICHM0020.php", true);
        oReq.onload = function (oEvent) {
            if (oReq.status == 200) {
                $('#loading').hide();
                document.location.href = 'previewpaper.html';
            }
        };
        oReq.send(oData);
        ev.preventDefault();
    }, false);
});

$("#edit_cancel").click(function (e) {
    document.location.href = "previewpaper.html";
});

$("#btncancel2").click(function (e) {
    DelCookie('Pid', domain);
    document.location.href = "proposals.html";
});

$("#preview_edit").click(function (e) {
    document.location.href = "editpaper.html";
});

