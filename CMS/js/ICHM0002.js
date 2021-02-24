function getCountry() {
	$.ajax({
		type: "POST",
		url: "Control/getCountry.php",
		async: false,  ///非同步執行
		dataType: "json",
		success: function (data) {
			if (data.isSuccess) {
				$("#ichm_country").append(data.str);
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

function getSession() {
	$.ajax({
		type: "POST",
		url: "Control/getSession.php",
		async: false,  ///非同步執行
		dataType: "json",
		success: function (data) {
			if (data.isSuccess) {
				$("#ichm_session").append(data.str);
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

function getTopic() {
	$.ajax({
		type: "POST",
		url: "Control/getTopic.php",
		async: false,  ///非同步執行
		dataType: "json",
		success: function (data) {
			if (data.isSuccess) {
				$("#ichm_topic").append(data.str);
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