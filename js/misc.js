function confirm_deletion(which,id,page,label,vars){
	var input_box=confirm("Are you sure you want to delete this "+label+"?");
	if (input_box==true){
		if(vars)
		 	window.location = page+"&d"+which+"="+id;
		else
			window.location = page+"?d"+which+"="+id;
	} else {
		//do nothing
	}
}

function maskEmail(user,site,message){
	document.write('<a href=\"mailto:'+ user + '@' + site + '\">'); 
	document.write(message+'</a>');
}

function forceMaxLength(obj, maxlength){
	if (obj.value.length > maxlength)
		obj.value = obj.value.substring(0,maxlength)
}

function clearInput(obj){
	obj.value = "";
}

function checkInput(obj,value){
	if(obj.value == "")
		obj.value = value;
	else if(obj.value == value)
		obj.value = "";
}



/* Admin: WYSIWYG (tinymce) */
$(function(){
		
	if (typeof tinymce == 'function' || typeof tinymce == 'object') {
		$('.wysiwyg').tinymce({
			script_url : '/js/tinymce/tinymce.min.js',
				
			plugins: [
		          "advlist autolink link image lists charmap print preview hr anchor pagebreak",
		          "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		          "table contextmenu textcolor paste textcolor"
		  ],
		
		  toolbar1: "bold italic | alignleft aligncenter alignright alignjustify | fontsizeselect styleselect | forecolor backcolor | cut copy paste | table | hr removeformat | subscript superscript",
		  toolbar2: "searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media | inserttime preview | code visualchars visualblocks",
		
		  menubar: false, 
		  toolbar_items_size: 'small',
			
			relative_urls: false,
			remove_script_host: true,
						
			/*style_formats : [
		  	{title: 'Headline 1', inline: 'h1', classes: 'page-headline'},
		  	{title: 'Headline 2', inline: 'h2', classes: 'page-sub-headline'},
		  	{title: 'Headline 3', inline: 'h3', classes: 'page-sub-sub-headline'},
		  	{title: 'Magenta', inline: 'span', classes: 'magenta'},
		  	{title: 'Dark Blue', inline: 'span', classes: 'blue-dark'},
		  	{title: 'Light Blue', inline: 'span', classes: 'cerulean'},
		  ],*/
		  
		  //content_css: "/css/wysiwyg.css"
		});
	}
});