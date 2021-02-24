var menu = '<div class="col-sm-12">\
				<div class="dropdown">\
					<a href="index.html"><button class="dropbtn">Home</button></a>\
					<div class="dropdown-content">\
					</div>\
				</div>\
				<div class="dropdown">\
					<button class="dropbtn">AURTHOR OPTION</button>\
					<div class="dropdown-content">\
						<a href="submitpaper.html">Submit regular paper</a>\
						<a href="proposals.html">Submitted papers</a>\
					</div>\
                </div>\
                <div class="dropdown">\
					<a href="validation.html"><button class="dropbtn">IDENTITY VALIDATION</button></a>\
					<div class="dropdown-content">\
					</div>\
				</div>\
				<div class="dropdown">\
					<button class="dropbtn">REGISTRATION</button>\
					<div class="dropdown-content">\
                        <a href="registration.html">Fee Info</a>\
						<a href="ordersummary.html">Status</a>\
						<a href="#" class="btn_registration">Register</a>\
					</div>\
                </div>\
			</div>';
$("#menu").append(menu);

checkToken();

$("#logout_btn").click(function () {
	delete_all_cookie();
});


$('.btn_registration').click(function () {
	var token = GetCookie("CMS_token");
    $.ajax({
        type: "POST",
        url: "Control/ICHM0030.php",
        dataType: "json",
        data: {
            token: token,
            action: "insert_prores"
        },
        success: function (data) {
            if (data.isSuccess) {
                SetCookie("OrderId", data.OrderId, 1, domain);
				DelCookie("O_Type", domain);
                DelCookie("O_Money", domain);
                document.location.href = 'proregister.html';
            }
		},
		beforeSend: function(){
			$('#loading').show();
		},
		complete: function(){
			$('#loading').hide();
		},
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.readyState + XMLHttpRequest.status + XMLHttpRequest.responseText);
        }
    })
})

