(function($){
$.atk4HomePage=function(){
    return $.atk4HomePage;
}
	
$.fn.extend({atk4HomePage:function(){
	var u=new $.atk4HomePage;
	u.jquery=this;
	return u;
}});
	
$.atk4HomePage._import=function(name,fn){
	$.atk4HomePage[name]=function(){
		var ret=fn.apply($.atk4HomePage,arguments);
		return ret?ret:$.atk4HomePage;
	}
}
	
$.each({
	parameters: [],
	alert: function(text){
		alert(text);		
	},
	makeSortable: function(name,items){

        $( "#"+name ).sortable({
            placeholder: "ui-state-highlight",
            items: items,
            cursor: "move"
        });

	},
	saveOrder: function(name,items,attr,url){

        if (url.indexOf('?') === false) {
            url = url + '?';
        } else {
            url = url + '&';
        }

        var ids = '';

        $("#"+name+" "+items).each(function(i,e){
            console.log(i);
            if (i != 0) {
                ids = ids  + ",";
            }
            ids = ids  + $(e).attr(attr);
        });

        url = url + 'action=refresh_order&ids=' + ids;

        console.log(ids);
        $(this).univ().ajaxec(url);

	},
	setEditableMode: function(element_id,api_url) {
		console.log('editable mode for');
		console.log(element_id);
		console.log(api_url);

		var unique_id = this.fguid();

		$('#'+element_id).before(
			'<div id="'+unique_id+'" class="icon-edit" ' +
			'style="color:green; font-size:11px; position:absolute; border:green dotted 1px; ' +
			'background-color:rgba(87, 255, 0, 0.02); cursor:pointer; overflow:hidden" >Click to edit block</div>'
		);
		$('#'+unique_id).position($('#'+element_id).position());
		$('#'+unique_id).offset($('#'+element_id).offset());
		$('#'+unique_id).height($('#'+element_id).outerHeight( true ));
		$('#'+unique_id).width($('#'+element_id).outerWidth( true ));
		//$('#'+unique_id).css('z-index',100000);

		$('#'+unique_id).on('click',function(){
			console.log('click');
			$(this).univ().frameURL('Edit block',api_url);
		});

	},
	fguid: function () {
		function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
				.toString(16)
				.substring(1);
		}
		return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
			s4() + '-' + s4() + s4() + s4();
	}

},$.atk4HomePage._import);

})(jQuery);