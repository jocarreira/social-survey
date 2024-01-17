(function( $ ) {
	'use strict';
    $.fn.serializeFormJSON = function () {
        var o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    $(document).ready(function () {
	    if(!$.fn.goTo){
	        $.fn.goTo = function() {
	            $('html, body').animate({
	                scrollTop: $(this).offset().top - 100 + 'px'
	            }, 'slow');
	            return this; // for chaining...
	        }
	    }

	    // for details
	    $.fn.aysModal = function(action){
	        var $this = $(this);
	        switch(action){
	            case 'hide':
	                $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
	                setTimeout(function(){
	                    $(document.body).removeClass('modal-open');
	                    $(document).find('.ays-modal-backdrop').remove();
	                    $this.hide();
	                }, 250);
	                break;
	            case 'show':
	            default:
	                $this.show();
	                $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
	                $(document).find('.modal-backdrop').remove();
	                $(document.body).append('<div class="ays-modal-backdrop"></div>');
	                $(document.body).addClass('modal-open');
	                break;
	        }
	    }
	    
	    if (!String.prototype.trim) {
	        (function() {
	            String.prototype.trim = function() {
	                return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
	            };
	        })();
	    }

    });
	

})( jQuery );
