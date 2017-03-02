function userResetpasswords(reseturl){
	
	var valid = 1;
	var password = $("#user_password_hash").val(); 
	if(password==''){
		$("#cpasswordmsg").text("Please enter password.").css('color','red');
		$("#cpasswordmsg").focus();		
		valid = 0;
	}else{
		$("#cpasswordmsg").empty();
	}
	var userRepassword = $("#user_repassword").val(); 
	if(userRepassword==''){
		$("#confpasswordsmsg").text("Please enter confirm password.").css('color','red');
		$("#confpasswordsmsg").focus();		
		valid = 0;
	}
	if(password!='' && userRepassword!=''){    
		if(password.length < 6) {
			$("#confpasswordsmsg").text("Error: Password must contain at least six characters!").css('color','red');	
			$("#confpasswordsmsg").focus();
			valid = 0;
		}else if(password != userRepassword) {
			$("#confpasswordsmsg").text("Password doesn't match. Please use correct password.").css('color','red');
			$("#confpasswordsmsg").focus();	
			valid = 0;
		}else{
			$("#confpasswordsmsg").empty();
		}
	}
  if(valid==0){
	  return false;
   }else{
		$.ajax({
			 type: "POST",
			 url: reseturl, 
			 data:$("#userChangePassword").serialize(),
				success: function(res){ 
					$("#changePasswords").html(res);   
			 }
		});
   } 
}


function providerResetpasswords(reseturl){
	var valid = 1;
	var password = $("#user_password_hash").val(); 
	if(password==''){
		$("#cpasswordmsg").text("Please enter password.").css('color','red');
		$("#cpasswordmsg").focus();		
		valid = 0;
	}else{
		$("#cpasswordmsg").empty();
	}
	var userRepassword = $("#user_repassword").val(); 
	if(userRepassword==''){
		$("#confpasswordsmsg").text("Please enter confirm password.").css('color','red');
		$("#confpasswordsmsg").focus();		
		valid = 0;
	}
	if(password!='' && userRepassword!=''){
		if(password.length < 6) {
			$("#confpasswordsmsg").text("Error: Password must contain at least six characters!").css('color','red');	
			$("#confpasswordsmsg").focus();
			valid = 0;
		}else if(password != userRepassword) {
			$("#confpasswordsmsg").text("Password doesn't match. Please use correct password.").css('color','red');
			$("#confpasswordsmsg").focus();	
			valid = 0;
		}else{
			$("#confpasswordsmsg").empty();
		}
	}
  if(valid==0){
	  return false;
   }else{
		$.ajax({
			 type: "POST",
			 url: reseturl, 
			 data:$("#providerChangePassword").serialize(),
				success: function(res){ 
					$("#providerchangePasswords").html(res);   
			 }
		});
   } 
}

function providerResetpasswordsstep5(reseturl){
	var valid = 1;
	var password = $("#user_password_hash").val(); 
	if(password==''){
		$("#cpasswordmsg").text("Please enter password.").css('color','red');
		$("#cpasswordmsg").focus();		
		valid = 0;
	}else{
		$("#cpasswordmsg").empty();
	}
	var userRepassword = $("#user_repassword").val(); 
	if(userRepassword==''){
		$("#confpasswordsmsg").text("Please enter confirm password.").css('color','red');
		$("#confpasswordsmsg").focus();		
		valid = 0;
	}
	if(password!='' && userRepassword!=''){
		if(password.length < 6) {
			$("#confpasswordsmsg").text("Error: Password must contain at least six characters!").css('color','red');	
			$("#confpasswordsmsg").focus();
			valid = 0;
		}else if(password != userRepassword) {
			$("#confpasswordsmsg").text("Password doesn't match. Please use correct password.").css('color','red');
			$("#confpasswordsmsg").focus();	
			valid = 0;
		}else{
			$("#confpasswordsmsg").empty();
		}
	}
  if(valid==0){
	  return false;
   }else{
	$("#providerChangePasswordstep5").submit();	
   } 
}

// autocomplet : this function will be executed every time we change the text
function autocomplet(searchUrl) {

	var min_length = 0; // min caracters to display the autocomplete
	var searchtxt = $('#searchtxt').val();
	var category_id = $("#searchTb").find(".active").find("#categoryId").val();

	if(searchtxt.length >= min_length && searchtxt.length!=0){
		$.ajax({
			url: searchUrl,
			type: 'POST',
			data: {searchtxt:searchtxt, category_id:category_id},
			success:function(data){
				$('#searchTextbox').show();
				$('#searchTextbox').html(data);
			}
		});
	}else{
		$('#searchTextbox').hide();
	}
}

// set_item : this function will be executed when we select an item
function set_item(item) {
	var itemKeyArr = item.split(",");
	// change input value
	$('#searchtxt').val(item);
	// hide proposition list
	$('#searchTextbox').hide();
	var sitePath = document.URL;
	if(item!=''){
		window.location.href = sitePath+"search/listing?category="+itemKeyArr[0]+"&pname="+itemKeyArr[1]+"&s="+itemKeyArr[2]+"&c="+itemKeyArr[3]+'&sp='+itemKeyArr[4]+'&z='+itemKeyArr[5];
	}
}

function searchHomepage(){
	var searchHomebtntext = $("#searchtxt").val();
	var categoryName = $("#searchTb").find(".active").find("a").text();
	if(searchHomebtntext==''){
		return false;
	}
	var sitePath = document.URL;
	if(searchHomebtntext!=''){
		window.location.href = sitePath+"search/listing?category="+categoryName+"&q="+searchHomebtntext;
	}
}

function newsLetter(){
	var hostInfo = window.location.hostname;

	var subscriberEmail = $("#subscriberEmailid").val();
	if(subscriberEmail==''){
		$("#subscriberEmailmsg").text("Please enter the email.").css({'color':'red','float':'left','font-weight':'500'}); 
		return false;
	}
	if(subscriberEmail!=''){
		if(!validateEmailid(subscriberEmail)){
			$("#subscriberEmailmsg").text("Please enter valid email.").css({'color':'red','float':'left','font-weight':'500'}); 
			return false;
		}else{
			var mysubscriberUrl ='/YAB01/provider/newslettersubscriber';

			$.ajax({
				 type: "POST",
				 url: mysubscriberUrl, 
				 data:{subscriberEmail:subscriberEmail},
					success: function(result){ 
						if(result==0){
							$("#subscriberEmailmsg").text("You have been subscribed successfully.").css({'color':'red','float':'left','font-weight':'500'}); 
						}else{
							$("#subscriberEmailmsg").text("This email already exist. Please use another.").css({'color':'red','float':'left','font-weight':'500'}); 
						} 
						$("#subscriberEmailid").val('');
				 }
			});
		
		}
    
	}
}

function validateEmailid(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(email);
}

