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


	}

},$.atk4HomePage._import);

})(jQuery);