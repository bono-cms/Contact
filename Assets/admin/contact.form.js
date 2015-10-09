
$(function() {
	
	function add(callback){
		$("form").send({
			url : "/admin/module/contact/add.ajax",
			success : callback
		});
	}
	
	function update(callback){
		$("form").send({
			url : "/admin/module/contact/edit.ajax",
			success : callback
		});
	}
	
	$("[data-button='save']").click(function(){
		update(function(response){
			if (response == "1") {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='save-create']").click(function(){
		update(function(response) {
			
			if (response == "1") {
				window.location = '/admin/module/contact';
			} else {
				$.showErrors(response);
			}
		});
	});
	
	
	$("[data-button='add']").click(function() {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location = '/admin/module/contact/edit/' + response;
			} else {
				$.showErrors(response);
			}
		});
	});
	
	$("[data-button='add-create']").click(function(event) {
		add(function(response) {
			if ($.isNumeric(response)) {
				window.location.reload();
			} else {
				$.showErrors(response);
			}
		});
	});
	
	$("[data-button='cancel']").click(function(event){
		event.preventDefault();
		window.location = '/admin/module/contact';
	});
	
});
