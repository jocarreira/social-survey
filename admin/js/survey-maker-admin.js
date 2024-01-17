(function( $ ) {
	'use strict';
    $.fn.serializeFormJSON = function () {
        let o = {},
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

    $.fn.goToTop = function() {
        this.get(0).scrollIntoView({
            block: "center",
            behavior: "smooth"
        });
    }

    $.fn.goTo = function() {
        $('html, body').animate({
            scrollTop: this.offset().top - 200 + 'px'
        }, 'fast');
        return this; // for chaining...
    }

    $.fn.goToNormal = function() {
        $('html, body').animate({
            scrollTop: this.offset().top - 200 + 'px'
        }, 'normal');
        return this; // for chaining...
    }

	$(document).ready(function () {

        setInterval(function () {
            $('div.ays-quiz-maker-wrapper h1 i.ays_fa').toggleClass('pulse');
            $(document).find('.ays_heart_beat i.ays_fa').toggleClass('ays_pulse');
        }, 1000);

        // Mark unreads of requests list table
        if ($('.unread-result-badge.unread-result').length > 0) {
            $('.unread-result-badge.unread-result').each(function () {
                $(this).parent().parent().find('td').css('font-weight', 'bold')
            })
        }
        
        var questionHaveMoreOptions = ['radio', 'select', 'checkbox', 'yesorno'];
        var logicJumpTypes = ['radio', 'select', 'yesorno'];
        var otherLogicJumpTypes = ['checkbox'];

        // Disabling submit when press enter button on inputing
        $(document).on("input", 'input', function(e){
            if(e.keyCode == 13){
                if($(document).find("#ays-survey-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-customer-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }
        });

        $(document).on("keydown", function(e){
            if(e.target.nodeName == "TEXTAREA"){
                return true;
            }
            if(e.keyCode == 13){
                if($(document).find("#ays-survey-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-customer-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }

            if ( ( e.ctrlKey && e.which == 83 ) && !( e.which == 19 ) ){
                var saveButton = $(document).find("input#ays-button-apply, input.ays-survey-gen-settings-save");
                if( saveButton.length > 0 ){
                    e.preventDefault();
                    saveButton.trigger("click");
                }
                return false;
            }

            if(e.keyCode === 27){
                $(document).find('.ays-modal.ays-modal-opened').each(function(){
                    $(this).aysModal('hide');
                });
                return false;
            }
        });

        var answerAddedByEnter = false;
        $(document).on("keyup", ".ays-survey-answer-box input.ays-survey-input", function(e){
            answerAddedByEnter = false;
        });

        $(document).on("focus", ".ays-survey-answer-box input.ays-survey-input", function(e){
            var _this = $(this);
            setTimeout(function(){
                if( _this.attr('type') != 'number' && _this.attr('type') != 'phone'){
                    _this.get(0).setSelectionRange(0, _this.get(0).value.length);
                }else{
                    _this.select();
                }
            }, 10);
        });

        $(document).on("keydown", ".ays-survey-answer-box input.ays-survey-input", function(e){
            if( answerAddedByEnter === false ) {
                answerAddedByEnter = true;
                
                if(e.keyCode === 13){
                    if($(this).hasClass("notAdding")){
                        $(this).parents('.ays-survey-not-adding-enter-box').next().find(".ays-survey-without-enter").focus();
                    }else if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 || 
                              $(this).parents('.ays-survey-question-star_list').length > 0 || 
                              $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 || 
                              $(this).parents('.ays-survey-question-slider_list').length > 0){
                        aysSurveyAddMatrixRowOrColumn( $(this), true );
                    }else{
                        aysSurveyAddAnswer( $(this), true, true, true );
                    }
                }

                if(e.keyCode == 38 && !e.ctrlKey && !e.shiftKey ){
                    var ansCont = $(this).parents('.ays-survey-answer-row');
                    if($(this).parents('.ays-survey-not-adding-enter-box').length > 0 ){
                        $(this).parents('.ays-survey-not-adding-enter-box').prev().find(".ays-survey-without-enter").focus();
                    }

                    if( ansCont.prev().length > 0 ){
                        ansCont.prev().find(".ays-survey-answer-box input.ays-survey-input").trigger('focus');
                    }else{
                        return false;
                    }
                }

                if(e.keyCode === 40 && !e.ctrlKey && !e.shiftKey ){
                    var ansCont = $(this).parents('.ays-survey-answer-row');
                    if( ansCont.next().length > 0 ){
                        ansCont.next().find(".ays-survey-answer-box input.ays-survey-input").trigger('focus');
                    }else{
                        var questCont = $(this).parents('.ays-survey-question-answer-conteiner');
                        if($(this).parents('.ays-survey-not-adding-enter-box').length > 0 ){
                            $(this).parents('.ays-survey-not-adding-enter-box').next().find(".ays-survey-without-enter").focus();
                        }
                        if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 ){
                            if( $(this).parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                                $(this).parents('.ays-survey-question-matrix_scale_row').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                $(this).parents('.ays-survey-question-matrix_scale_column').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }
                        }
                        if( $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 ){
                            if( $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0 ){
                                $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                $(this).parents('.ays-survey-question-matrix_scale_checkbox_column').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }
                        }
                        else if($(this).parents('.ays-survey-question-star_list').length > 0){
                            if($(this).parents('.ays-survey-question-star_list_row').length > 0){
                                $(this).parents('.ays-survey-question-star_list_row').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }
                        }
                        else if($(this).parents('.ays-survey-question-slider_list').length > 0){
                            if($(this).parents('.ays-survey-question-slider_list_row').length > 0){
                                $(this).parents('.ays-survey-question-slider_list_row').find('.ays-survey-action-add-answer-row-and-column').trigger('click');
                            }
                        }
                        else{
                            questCont.find('.ays-survey-action-add-answer').trigger('click');
                        }
                    }
                }

                if(e.keyCode === 8){
                    if( $(this).val() == '' ){
                        var thatNext = $(this).parents('.ays-survey-answer-row').next().find('input.ays-survey-input');
                        var thatPrev = $(this).parents('.ays-survey-answer-row').prev().find('input.ays-survey-input');
                        if( thatPrev.length > 0 ){
                            thatPrev.trigger('focus');
                            if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            if( $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-star_list').length > 0){
                                if($(this).parents('.ays-survey-question-star_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-slider_list').length > 0){
                                if($(this).parents('.ays-survey-question-slider_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else{
                                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                            }
                        }else if( thatNext.length > 0 ){
                            thatNext.trigger('focus');
                            if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            if( $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-star_list').length > 0){
                                if($(this).parents('.ays-survey-question-star_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-slider_list').length > 0){
                                if($(this).parents('.ays-survey-question-slider_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else{
                                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                            }
                        }
                        return false;
                    }
                }

                if(e.keyCode === 46){
                    if( $(this).val() == '' ){
                        var thatNext = $(this).parents('.ays-survey-answer-row').next().find('input.ays-survey-input');
                        var thatPrev = $(this).parents('.ays-survey-answer-row').prev().find('input.ays-survey-input');
                        if( thatNext.length > 0 ){
                            thatNext.trigger('focus');
                            if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            if( $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-star_list').length > 0){
                                if($(this).parents('.ays-survey-question-star_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-slider_list').length > 0){
                                if($(this).parents('.ays-survey-question-slider_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else{
                                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                            }
                        }else if( thatPrev.length > 0 ){
                            thatPrev.trigger('focus');
                            if( $(this).parents('.ays-survey-question-matrix_scale').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            if( $(this).parents('.ays-survey-question-matrix_scale_checkbox').length > 0 ){
                                if( $(this).parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }else if( $(this).parents('.ays-survey-answers-conteiner-column').length > 0 ){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-column').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-star_list').length > 0){
                                if($(this).parents('.ays-survey-question-star_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else if($(this).parents('.ays-survey-question-slider_list').length > 0){
                                if($(this).parents('.ays-survey-question-slider_list_row').length > 0){
                                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete-row').trigger('click');
                                }
                            }
                            else{
                                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                            }
                        }
                        return false;
                    }
                }
            }
        });

        // Notifications dismiss button
        var $html_name_prefix = 'ays_';
        $(document).on('click', '.notice-dismiss', function (e) {
            changeCurrentUrl('status');
        });

        if(location.href.indexOf('del_stat')){
            setTimeout(function(){
                changeCurrentUrl('del_stat');
                changeCurrentUrl('mcount');
            }, 500);
        }

        var aysSurveyTextArea = $(document).find('textarea.ays-survey-question-input-textarea');
        autosize(aysSurveyTextArea);
        var aysSurveyQuestionDescriptionTextArea = $(document).find('textarea.ays-survey-question-description-input-textarea');
        autosize(aysSurveyQuestionDescriptionTextArea);
        var aysSurveyDescriptionTextArea = $(document).find('textarea.ays-survey-section-description');
        autosize(aysSurveyDescriptionTextArea);


        function changeCurrentUrl(key){
            var linkModified = location.href.split('?')[1].split('&');
            for(var i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == key){
                    linkModified.splice(i, 1);
                }
            }
            linkModified = linkModified.join('&');
            window.history.replaceState({}, document.title, '?'+linkModified);
        }

        // Quiz toast close button
        jQuery('.quiz_toast__close').click(function(e){
            e.preventDefault();
            var parent = $(this).parent('.quiz_toast');
            parent.fadeOut("slow", function() { $(this).remove(); } );
        });
        
        var toggle_ddmenu = $(document).find('.toggle_ddmenu');
        toggle_ddmenu.on('click', function () {
            var ddmenu = $(this).next();
            var state = ddmenu.attr('data-expanded');
            switch (state) {
                case 'true':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(0deg)'
                    });
                    ddmenu.attr('data-expanded', 'false');
                    break;
                case 'false':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(90deg)'
                    });
                    ddmenu.attr('data-expanded', 'true');
                    break;
            }
        });

        var ays_results = $(document).find('.ays_result_read');
        for (var i in ays_results) {
            if (typeof ays_results.eq(i).val() != 'undefined') {
                if ( ays_results.eq(i).val() == 0 || ays_results.eq(i).val() == 2 ) {
                    ays_results.eq(i).parents('tr').addClass('ays_read_result');
                }
            }
        }
        
        // $('[data-toggle="popover"]').popover();
        
        $(document).find('.ays-survey-sections-conteiner [data-toggle="popover"]').popover();
        $(document).find('.aysFormeditorViewFatRoot [data-toggle="popover"]').popover();
        $(document).find('.ays_survey_previous_next [data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();

        $(document).find('.ays_survey_aysDropdown').aysDropdown();
        $(document).find('.ays-survey-sections-conteiner .ays-survey-checkbox-condition-select').select2({});

        $(document).find('[data-toggle="dropdown"]').dropdown();
        // $('.dropdown-toggle').dropdown()

        // Disabling submit when press enter button on inputing
        $(document).on("input", 'input', function(e){
            if(e.keyCode == 13){
                if($(document).find("#ays-question-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-customer-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }
        });

        // Modal close
        $(document).find('.ays-close').on('click', function () {
            $(this).parents('.ays-modal').aysModal('hide');
        });

        $(document).find('strong.ays-survey-shortcode-box,.ays-survey-question-id-copy-box,.ays-survey-gen-psw-copy-all,.ays-survey-gen-psw-copy').on('mouseleave', function(){
            var _this = $(this);

            _this.attr( 'data-original-title', SurveyMakerAdmin.clickForCopy );
            _this.attr( 'title', SurveyMakerAdmin.clickForCopy );
        });

        $(document).on('change', '.ays_toggle_checkbox, .ays-switch-checkbox', function (e) {
            var currentElement = $(this);
            var state = $(this).prop('checked');
            var parent = $(this).parents('.ays_toggle_parent');

            if($(this).hasClass('ays_toggle_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').show(250);
                        if(currentElement.hasClass('ays-survey-upload-tpypes-on-off')){
                            if(parent.find('.ays-survey-current-upload-type-file-types:checked').length <= 0){
                                parent.find('.ays-survey-current-upload-type-file-types').prop('checked', true);
                            }
                        }
                        break;
                    case false:
                        parent.find('.ays_toggle_target').hide(250);
                        break;
                }
            }

            if($(this).hasClass('ays-survey-slider-list-calculation-type')){
                var _thisId = $(this).attr('id');
                $(this).parents(".ays-survey-question-range-length-label").find("label").css("font-weight" , 'initial');
                var _thisLabel = $(this).parents(".ays-survey-question-range-length-label").find("label[for="+_thisId+"]");
                _thisLabel.css("font-weight" , "600");
            }
        });

        $(document).on('change' , '.ays-survey-current-upload-type-file-types' , function(e) {
            var _thisMainParent  = $(this).parents(".ays-survey-question-types-box");
            var checkedInpsCount = _thisMainParent.find('.ays-survey-current-upload-type-file-types:checked');
            if(checkedInpsCount.length <= 0){
                _thisMainParent.find(".ays_toggle_target").hide(250);
                _thisMainParent.find(".ays-survey-upload-tpypes-on-off").prop('checked' , false);
            }
        });
        
        $(document).on('change', '.ays_toggle_select', function (e) {
            var state = $(this).val();
            var parent = $(this).parents('.ays_toggle_parent');            
            
            if (state == 'none') {
                parent.find('.ays_toggle_target').hide(150);
            }else{
                parent.find('.ays_toggle_target').show(250);
            }
            
        });

        $(document).on('click', '.ays_toggle_loader_radio', function (e) {
            var dataFlag = $(this).attr('data-flag');
            var dataType = $(this).attr('data-type');
            var state = false;
            if (dataFlag == 'true') {
                state = true;
            }

            var parent = $(this).parents('.ays_toggle_loader_parent');
            if($(this).hasClass('ays_toggle_loader_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_loader_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_loader_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:                        
                        switch( dataType ){
                            case 'text':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').show(250);
                                parent.find('.ays_toggle_loader_target[data-type="gif"]').hide(250);
                            break;
                            case 'gif':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').show(250);
                                parent.find('.ays_toggle_loader_target.ays_gif_loader_width_container[data-type="'+ dataType +'"]').css({
                                    'display': 'flex',
                                    'justify-content': 'center',
                                    'align-items': 'center'
                                });
                                parent.find('.ays_toggle_loader_target[data-type="text"]').hide(250);
                            break;
                            default:
                                parent.find('.ays_toggle_loader_target').show(250);
                            break;
                        }
                        break;
                    case false:                       
                        switch( dataType ){
                            case 'text':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').hide(250);
                            break;
                            case 'gif':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').hide(250);
                            break;
                            default:
                                parent.find('.ays_toggle_loader_target').hide(250);
                            break;
                        }
                        break;
                }
            }
        });

        $(document).find(".add_survey_loader_custom_gif,.ays-edit-survey-loader-custom-gif").on("click" , function(e){
            openMediaUploaderForGifLoader(e, $(this));
        });

        $(document).on('click', '.ays-survey-image-wrapper-delete-wrap', function () {
            var wrap = $(this).parents('.ays-survey-image-wrap');
            wrap.find('.ays-survey-image-container').fadeOut(500);
            setTimeout(function(){
                wrap.find('img.ays_survey_img_loader_custom_gif').attr('src', '');
                wrap.find('input.ays-survey-image-path').val('');
                wrap.find('a.add_survey_loader_custom_gif').show();
            }, 450);
        });

        $(document).on('change', '.ays_survey_send_mail_type', function (e) {
            var _this = $(this);
            var val   = _this.val();
            var disabled_val = _this.val();
            
            if ( val == 'custom' ) {
                $(document).find('.ays_survey_send_mail_type_custom').show(250);
                $(document).find('.ays_survey_send_mail_type_sendgrid').hide(250);
            } else if ( val == 'sendgrid' ) {
                if ( _this.prop('disabled', false) ) {
                    $(document).find('.ays_survey_send_mail_type_custom').hide(250);
                    $(document).find('.ays_survey_send_mail_type_sendgrid').show(250);
                }
            }
        });

        // $(document).on('change', '#ays_survey_enable_start_page', function (e) {
        //     var state = $(this).prop('checked');
        //     var parent = $(this).parents('.ays_toggle_parent');
            
        //     if( state === true ){
        //         $(document).find('a[data-tab="tab6"]').removeClass( 'display_none' );
        //     }else{
        //         $(document).find('a[data-tab="tab6"]').addClass( 'display_none' );
        //     }
        // });


        $(document).find('#ays-category').select2({
            placeholder: 'Select category'
        });

        $(document).find('#ays-status').select2({
            placeholder: 'Select status'
        });

        $(document).find('#ays_survey_limit_country').select2({
            placeholder: 'Select country'
        });

        $(document).find('#ays_add_postcat_for_survey').select2();

        // Tabulation
        $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
            if(! $(this).hasClass('no-js')){
                var elemenetID = $(this).attr('href');
                var active_tab = $(this).attr('data-tab');
                $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
                    if ($(this).hasClass('nav-tab-active')) {
                        $(this).removeClass('nav-tab-active');
                    }
                });
                $(this).addClass('nav-tab-active');
                $(document).find('.ays-survey-tab-content').each(function () {
                    $(this).css('display', 'none');
                });
                $(document).find("[name='ays_survey_tab']").val(active_tab);
                $('.ays-survey-tab-content' + elemenetID).css('display', 'block');
                e.preventDefault();
            }
        });


        // Survey category form submit
        // Checking the issues
        $(document).find('#ays-survey-category-form').on('submit', function(e){
            var submitFlag = true;
            if($(document).find('#ays-title').val() == ''){
                $(document).find('#ays-title').val('Survey category').trigger('input');
                submitFlag = false;
            }
            var $this = $(this)[0];
            if( submitFlag ){
                $this.submit();
            }else{
                // e.preventDefault();
                // $this.submit();
            }
        });

        // Survey Customer form submit
        // Checking the issues
        // Jeferson Carreira
        $(document).find('#ays-survey-customer-form').on('submit', function(e){
            var submitFlag = true;
            if($(document).find('#ays-title').val() == ''){
                $(document).find('#ays-title').val('Cliente novo').trigger('input');
                submitFlag = false;
            }
            var $this = $(this)[0];
            if( submitFlag ){
                $this.submit();
            }else{
                // e.preventDefault();
                // $this.submit();
            }
        });

        // Survey form submit
        // Checking the issues
        $(document).find('#ays-survey-form').on('submit', function(e){
            var $this = $(this)[0];
            var submitFlag = true;
            if($(document).find('#ays-survey-title').val() == ''){
                $(document).find('#ays-survey-title').val('Survey').trigger('input');
                // submitFlag = false;
            }

            // $(document).find('.ays-survey-section-title').each(function(){
            //     if( $(this).val() == ''){
            //         $(this).val('Untitled section').trigger('input');
            //         // submitFlag = false;
            //     }
            // });
            
            if( submitFlag ){
                $this.submit();
            }else{
            //     e.preventDefault();
            //     $this.submit();
            }
        });

        // Submit buttons disableing with loader
        $(document).find('.ays-survey-loader-banner').on('click', function () {        
            var $this = $(this);
            submitOnce($this);
        });

        function submitOnce(subButton){
            var subLoader = subButton.parents('div').find('.ays_survey_loader_box');
            if ( subLoader.hasClass("display_none") ) {
                subLoader.removeClass("display_none");
            }
            subLoader.css({
                "padding-left": "8px",
                "display": "inline-block"
            });

            setTimeout(function() {
                $(document).find('.ays-survey-loader-banner').attr('disabled', true);
            }, 10);

            setTimeout(function() {
                $(document).find('.ays-survey-loader-banner').attr('disabled', false);
                subButton.parents('div').find('.ays_survey_loader_box').css('display', 'none');
            }, 5000);

        }

        // Delete confirmation
        $(document).on('click', '.ays_confirm_del', function(e){            
            e.preventDefault();
            var message = $(this).data('message');
            var confirm = window.confirm('Are you sure you want to delete '+message+'?');
            if(confirm === true){
                window.location.replace($(this).attr('href'));
            }
        });


        // $(document).find('.cat-filter-apply-top').on('click', function(e) {
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterby-top"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterby',
        //         value: catFilter
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.cat-filter-apply-bottom').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterby-bottom"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterby',
        //         value: catFilter
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.user-filter-apply-top').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser-top"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterbyuser',
        //         value: catFilter
        //     });
        //     newLink = catFilterForListTable(newLink, {
        //         what: 'ays_survey_tab',
        //         value: 'poststuff'
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.user-filter-apply-bottom').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser-bottom"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterbyuser',
        //         value: catFilter
        //     });
        //     newLink = catFilterForListTable(newLink, {
        //         what: 'ays_survey_tab',
        //         value: 'poststuff'
        //     });
        //     document.location.href = newLink;
        // });


        // $(document).find('.user-filter-apply').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser"]').val();
        //     var link = location.href;
        //     var linkFisrtPart = link.split('?')[0];
        //     var linkModified = link.split('?')[1].split('&');
        //     alert(catFilter)
        //     for(var i = 0; i < linkModified.length; i++){
        //         if(linkModified[i].split("=")[0] == "wpuser"){
        //             linkModified.splice(i, 1);
        //         }
        //     }
        //     link = linkFisrtPart + "?" + linkModified.join('&');
            
        //     if( catFilter != '' ){
        //         catFilter = "&wpuser="+catFilter;
        //         catFilter = "&ays_survey_tab=poststuff";
        //         document.location.href = link+catFilter;
        //     }else{
        //         document.location.href = link;
        //     }
        // });

        // FILTERS FOR LIST TABEL
        $(document).find('.ays-survey-question-tab-all-filter-button-top, .ays-survey-question-tab-all-filter-button-bottom').on('click', function(e){
            e.preventDefault();
            var $this = $(this);
            var parent = $this.parents('.tablenav');
            var link = location.href;

            var html_name = '';
            var top_or_bottom = 'top';

            if ( parent.hasClass('bottom') ) {
                top_or_bottom = 'bottom';
            }

            var catFilter = $(document).find('select[name="filterby-'+ top_or_bottom +'"]').val();
            var userFilter = $(document).find('select[name="filterbyuser-'+ top_or_bottom +'"]').val();
            var filterbyDescriptionFilter = $(document).find('select[name="filterbyDescription-'+ top_or_bottom +'"]').val();

            if(typeof catFilter != "undefined"){
                link = catFilterForListTable(link, {
                    what: 'filterby',
                    value: catFilter
                });
            }
            if(typeof userFilter != "undefined"){
                link = catFilterForListTable(link, {
                    what: 'filterbyuser',
                    value: userFilter
                });
            }

            if(typeof filterbyDescriptionFilter != "undefined"){
                link = catFilterForListTable(link, {
                    what: 'filterbyDescription',
                    value: filterbyDescriptionFilter
                });
            }

            if($(this).hasClass('ays-survey-question-filter-each-submission')){
                link = catFilterForListTable(link, {
                            what: 'ays_survey_tab',
                            value: 'poststuff'
                        });
            }
            document.location.href = link;
        });
        

        function catFilterForListTable(link, options){
            if( options.value != '' ){
                options.value = "&" + options.what + "=" + options.value;
                var linkModifiedStart = link.split('?')[0];
                var linkModified = link.split('?')[1].split('&');
                for(var i = 0; i < linkModified.length; i++){
                    if(linkModified[i].split("=")[0] == options.what){
                        linkModified.splice(i, 1);
                    }
                }
                linkModified = linkModified.join('&');
                return linkModifiedStart + "?" + linkModified + options.value;
            }else{
                var linkModifiedStart = link.split('?')[0];
                var linkModified = link.split('?')[1].split('&');
                for(var i = 0; i < linkModified.length; i++){
                    if(linkModified[i].split("=")[0] == options.what){
                        linkModified.splice(i, 1);
                    }
                }
                linkModified = linkModified.join('&');
                return linkModifiedStart + "?" + linkModified;
            }
        }

        $(document).on('change', '#import_file', function(e){
            var pattern = /(.csv|.xlsx|.json)$/g;
            if(pattern.test($(this).val())){
                $(this).parents('form').find('input[name="ays_survey_import"]').removeAttr('disabled')
            }
        });

        // Add Image 
        $(document).on('click', '.ays-survey-add-image', function (e) {
            openMediaUploaderForImage(e, $(this));
        });

        $(document).on('click', '.ays-survey-add-logo-image', function (e) {
            openMediaUploaderForLogoImage(e, $(this));
        });

        $(document).on('click', '.ays-survey-logo-remove', function (e) {
            var $this = $(this);
            $this.parents('.ays-survey-image-container').find('.ays-survey-image-body').fadeOut(500);
            $this.parents('.ays-survey-image-container').find('.ays-survey-add-logo-image').text( SurveyMakerAdmin.addImage );
            setTimeout(function(){
                $(document).find('.ays-survey-logo-url-box').addClass('display_none_not_important');
                $(document).find("#ays_survey_logo_enable_image_url").prop("checked" , false);
                $this.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open-close').hide();
                $this.parents('.ays-survey-image-container').find('.ays-survey-img').removeAttr('src');
                $this.parents('.ays-survey-image-container').find('.ays-survey-img-src').val('');
                $this.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open').hide();
                $this.parents('.ays-survey-image-container').find('.ays-survey-logo-url-box').addClass('display_none_not_important');
            }, 500);
        });

        // Remove Image
        $(document).on('click', '.removeImage', function (e) {
            var $this = $(this);
            $this.parents('.ays-survey-image-container').find('.ays-survey-image-body').fadeOut(500);
            $this.parents('.ays-survey-image-container').find('.ays-survey-add-image').text( SurveyMakerAdmin.addImage );
            var thisParent = $this.parents('.ays_survey_cover_image_main');
            thisParent.find('.ays-survey-add-cover-image').html( SurveyMakerAdmin.addImage );
            thisParent.find('.ays-survey-image-body').fadeOut(500);
            thisParent.find('.ays-survey-cover-image-options,.ays-survey-cover-image-options-hr').fadeOut(500);
            setTimeout(function(){
                $this.parents('.ays-survey-image-container').find('.ays-survey-img').removeAttr('src');
                $this.parents('.ays-survey-image-container').find('.ays-survey-img-src').val('');
            }, 500);
        });

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////ARO START///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        function aysSurveyQuestionSortableHelper( event ) {
            var clone = $(event.target).parents('.ays-survey-question-answer-conteiner').clone(true, true);
            clone.find('.ays-survey-question-image-container').remove();
            clone.find('.ays-survey-other-answer-and-actions-row').remove();
            clone.find('.ays-survey-question-matrix_scale').remove();
            clone.find('.ays-survey-question-matrix_scale_checkbox').remove();
            clone.find('.ays-survey-question-star_list').remove();
            clone.find('.ays-survey-question-slider_list').remove();
            clone.find('.ays-survey-question-types_html').remove();
            clone.find('.ays-survey-answers-conteiner').html('<div class="ays-survey-sortable-ect">…</div>');
            return clone;
        }

        var sectionDragHandle = {
            handle: '.ays-survey-section-dlg-dragHandle',
            appendTo: "parent",
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'ays-survey-sortable-placeholder',
            tolerance: "pointer",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            // helper: aysSurveyQuestionSortableHelper,
            sort: function(e, ui){
                ui.placeholder.css('height', ui.helper.height());
            },
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var sections = sortableContainer.find('.ays-survey-section-box');
                sections.each(function(i){
                    $(this).find('.ays-survey-section-ordering').val(i+1);
                    $(this).find('.ays-survey-section-number').text(i+1);
                });
            }
        };

        var questionDragHandle = {
            handle: '.ays-survey-question-dlg-dragHandle',
            appendTo: "parent",
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'ays-survey-sortable-placeholder',
            tolerance: "pointer",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            helper: aysSurveyQuestionSortableHelper,
            connectWith: '.ays-survey-sections-conteiner .ays-survey-section-questions',
            sort: function(e, ui){
                ui.placeholder.css('height', ui.helper.height());
            },
            receive: function( event, ui ){
                var section = $(event.target).parents('.ays-survey-section-box');
                // draggedQuestionUpdate( ui.item, section );
                var oldSection = ui.sender.parents('.ays-survey-section-box');
                draggedQuestionUpdate( ui.item, section, oldSection);

            },
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var oldSection = false;
                if(ui.sender != null){
                    oldSection = ui.sender.parents('.ays-survey-section-box');
                }

                var questions = sortableContainer.find('.ays-survey-question-answer-conteiner');
                if( questions.length == 0 ){
                    swal.fire({
                        type: 'warning',
                        text: SurveyMakerAdmin.minimumCountOfQuestions
                    });
                    setTimeout(function(){
                        // draggedQuestionUpdate( ui.item, sortableContainer.parents('.ays-survey-section-box') );
                        draggedQuestionUpdate( ui.item, sortableContainer.parents('.ays-survey-section-box'), oldSection );
                    }, 1);
                    return false;
                }
                questions.each(function(i){
                    $(this).find('.ays-survey-question-ordering').val(i+1);
                });
            }
        };

        var answerDragHandle = {
            handle: '.ays-survey-answer-dlg-dragHandle',
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'clone',
            tolerance: "pointer",
            helper: "clone",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var answers = sortableContainer.find('.ays-survey-answer-row');
                answers.each(function(i){
                    $(this).find('.ays-survey-answer-ordering').val(i+1);
                });
            }
        }
        // Answers ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner .ays-survey-answers-conteiner').sortable(answerDragHandle);
        $(document).find('.ays-survey-sections-conteiner .ays-survey-answers-conteiner-row').sortable(answerDragHandle);
        $(document).find('.ays-survey-sections-conteiner .ays-survey-answers-conteiner-column').sortable(answerDragHandle);

        // Question ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner .ays-survey-section-questions').sortable(questionDragHandle);
        // Question ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner').sortable(sectionDragHandle);

        // Collapse All
        $(document).on('click', '.ays-survey-collapse-all', function (e) {
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(){
                var section = $(this);
                section.find('.ays-survey-action-collapse-question').each(function(){
                    collapseQuestion( $(this) );
                });
                collapseSection( section.find('.ays-survey-action-collapse-section') );
            });
        });

        // Expand All
        $(document).on('click', '.ays-survey-expand-all', function (e) {
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(){
                var section = $(this);
                section.find('.ays-survey-action-collapse-question').each(function(){
                    expandQuestion( $(this) );
                });
                expandSection( section.find('.ays-survey-action-expand-section') );
            });
        });

        // Collapse Section Questions
        $(document).on('click', '.ays-survey-collapse-sec-quests', function (e) {
            var $this = $(this);
            var section = $this.parents('.ays-survey-section-box');
            section.find('.ays-survey-action-collapse-question').each(function(){
                collapseQuestion( $(this) );
            });
        });

        $(document).on('click', '.ays-survey-section-questions-required', function (e) {
            makeAllQuestionsRequired($(this) , true);
        });

        // Expand Section Questions
        $(document).on('click', '.ays-survey-expand-sec-quests', function (e) {
            var $this = $(this);
            var section = $this.parents('.ays-survey-section-box');
            section.find('.ays-survey-action-collapse-question').each(function(){
                expandQuestion( $(this) );
            });
        });

        // Collapse Question
        $(document).on('click', '.ays-survey-action-collapse-question', function (e) {
            var $this = $(this);
            collapseQuestion( $this );
        });

        // Expand Question
        $(document).on('click', '.ays-survey-action-expand-question, .ays-survey-question-wrap-collapsed-contnet', function (e) {
            var $this = $(this);
            if(!$(e.target).hasClass('dropdown-item')){
                expandQuestion( $this );
            }
        });

        // Collapse Section
        $(document).on('click', '.ays-survey-action-collapse-section', function (e) {
            var $this = $(this);
            collapseSection( $this );
        });

        // Expand Section
        $(document).on('click', '.ays-survey-action-expand-section', function (e) {
            var $this = $(this);
            expandSection( $this );
        });


        function collapseQuestion( _this ){
            var questsCont = _this.parents('.ays-survey-question-answer-conteiner');
            var questsText = questsCont.find('.ays-survey-question-input-textarea').val();
            questsCont.find('.ays-survey-question-wrap-collapsed-contnet-text').text( questsText );
            questsCont.find('.ays-survey-question-wrap-collapsed').removeClass('display_none');
            questsCont.find('.ays-survey-question-wrap-expanded').addClass('display_none');
            questsCont.find('.ays-survey-question-collapsed-input').val('collapsed');
            aysSurveySectionsInitQuestionsCollapse();
        }

        function expandQuestion( _this ){
            var questsCont = _this.parents('.ays-survey-question-answer-conteiner');
            questsCont.find('.ays-survey-question-wrap-collapsed').addClass('display_none');
            questsCont.find('.ays-survey-question-wrap-expanded').removeClass('display_none');
            questsCont.find('.ays-survey-question-collapsed-input').val('expanded');
            aysSurveySectionsInitQuestionsCollapse();
            aysAutosizeUpdate();
        }

        function collapseSection( _this ){
            var sectionCont = _this.parents('.ays-survey-section-box');
            var sectionText = sectionCont.find('.ays-survey-section-title').val();
            sectionCont.find('.ays-survey-section-wrap-collapsed-contnet-text').text( sectionText );
            sectionCont.find('.ays-survey-section-wrap-collapsed').removeClass('display_none');
            sectionCont.find('.ays-survey-section-wrap-expanded').addClass('display_none');
            sectionCont.find('.ays-survey-section-collapsed-input').val('collapsed');
        }

        function expandSection( _this ){
            var sectionCont = _this.parents('.ays-survey-section-box');
            sectionCont.find('.ays-survey-section-wrap-collapsed').addClass('display_none');
            sectionCont.find('.ays-survey-section-wrap-expanded').removeClass('display_none');
            sectionCont.find('.ays-survey-section-collapsed-input').val('expanded');
            aysAutosizeUpdate();
        }

        function aysAutosizeUpdate(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var aysSurveyQuestionTextArea = sectionCont.find('textarea.ays-survey-question-input-textarea');
            var aysSurveyQuestionDescriptionTextArea = sectionCont.find('textarea.ays-survey-question-description-input-textarea');
            var aysSurveySectionDescriptionTextArea = sectionCont.find('textarea.ays-survey-section-description');
            autosize.update(aysSurveyQuestionTextArea);
            autosize.update(aysSurveyQuestionDescriptionTextArea);
            autosize.update(aysSurveySectionDescriptionTextArea);
        }

        // Add Question and Answer Image 
        $(document).on('click', '.ays-survey-add-question-image, .ays-survey-add-answer-image', function (e) {
            var dataType = $(this).data('type');
            openMediaUploader(e, $(this), dataType);
        });

        // Add Answer Button
        $(document).on('click', '.ays-survey-action-add-answer', function(e){
            var $this = $(this);
            aysSurveyAddAnswer( $this, false, true );
        });

        // Delete Answer Button
        $(document).on('click', '.ays-survey-answer-delete', function(e){
            var $this = $(this);
            var itemId = $this.parents('.ays-survey-question-answer-conteiner').data('id');
            var answerId = $this.parents('.ays-survey-answer-row').data('id');
            var length = $this.parents('.ays-survey-answers-conteiner').find('.ays-survey-answer-delete').length - 1;
            var parent = $this.parents('.ays-survey-answers-conteiner');
            var hideDeleteButton = parent.find('.ays-survey-answer-delete');

            if(length == 1){
                hideDeleteButton.css('visibility','hidden');
            }else{
                hideDeleteButton.removeAttr('style');
            }
            if( ! $this.parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ){
                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                $this.parents('form').append( delImp );
            }

            $this.parents('.ays-survey-answer-row').find('[data-toggle="popover"]').popover('hide');
            $this.parents('.ays-survey-answer-row').remove();

            parent.find('.ays-survey-answer-ordering').each(function(i){
                $(this).val( i + 1 );
            });
        });

        // Add "Other" Answer
        $(document).on('click', '.ays-survey-other-answer-add', function(e){
            var $this = $(this);
            var parent = $this.parents('.ays-survey-question-answer-conteiner');
            var checkbox = parent.find('.ays-survey-other-answer-checkbox').attr('checked', true);
            var oterAnswer = parent.find('.ays-survey-other-answer-row');
            oterAnswer.removeAttr('style');
            $this.parents('.ays-survey-other-answer-add-wrap').css('display','none');
        });

        // Delete "Other" Answer
        $(document).on('click', '.ays-survey-other-answer-delete', function(e){
            var $this = $(this);
            var parent = $this.parents('.ays-survey-question-answer-conteiner');
            var checkbox = parent.find('.ays-survey-other-answer-checkbox').attr('checked', false);
            var oterAnswer = parent.find('.ays-survey-other-answer-row');
            oterAnswer.css('display','none');
            parent.find('.ays-survey-other-answer-add-wrap').removeAttr('style');
        });

        // Bulk add answers modal open
        $(document).on('click', '.ays-survey-action-bulk-add-answer', function(e){
            var $this = $(this);
            var modal = $(document).find('#ays-survey-maker-answers-bulk-add-modal');
            var parent = $this.parents('.ays-survey-question-answer-conteiner');
            var questionId = parent.attr('data-id');
            
            modal.find('#ays-survey-answers-bulk-add').attr('data-id', questionId);
            modal.find('#ays-survey-answers-bulk-add').val('');
            modal.aysModal('show');
            modal.find('#ays-survey-answers-bulk-add').focus();
        });

        // Bulk add answers modal close
        $(document).on("click" , '.ays-survey-answers-bulk-add-header span.close', function() {
            var $this = $(this);
            var modal = $this.parents('#ays-survey-maker-answers-bulk-add-modal');
            modal.aysModal("hide");
        });
        
        // Bulk add answers save changes
        $(document).on("click" , '#ays-survey-answers-bulk-save', function() {
            var $this = $(this);
            var modal = $this.parents('#ays-survey-maker-answers-bulk-add-modal');

            var questionId = modal.find('#ays-survey-answers-bulk-add').attr('data-id');
            var questionContainer = $($(document).find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"]')[0]);
            var addAnswerBttn = questionContainer.find('.ays-survey-action-add-answer')[0];
            
            var data = modal.find('#ays-survey-answers-bulk-add').val();

            var answers = data.split('\n').filter(a => a);

            // answersContainersToDelete.remove();
            for (var i = 0; i < answers.length; i++) {
                aysSurveyAddAnswer( $(addAnswerBttn), false, true );
                questionContainer.find('.ays-survey-answers-conteiner .ays-survey-answer-row:last-child input.ays-survey-input').val(answers[i]);
            }

            modal.aysModal("hide");
        });

        // Dublicate Question Button
        $(document).on('click', '.ays-survey-action-duplicate-question', function(e){
            var $this = $(this);
            var sectionId = $this.parents('.ays-survey-section-box').attr('data-id');
            var thisSection = $this.parents('.ays-survey-section-box');
            var sectionName = $this.parents('.ays-survey-section-box').attr('data-name');
            var cloningElement = $this.parents('.ays-survey-question-answer-conteiner');
            var itemId = cloningElement.attr('data-id');
            var question_length = cloningElement.parent().find('.ays-survey-question-answer-conteiner').length + 1;
            var question_type = cloningElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('get value');

            // var questionsCount = questionsLength;
            var questionsCountBox = thisSection.find(".ays-survey-action-questions-count span").text(question_length);

            cloningElement.find('.ays-survey-question-type').aysDropdown('destroy');

            var clonedLimitBy = cloningElement.find('.ays-survey-question-word-limit-by-select select').val();

            var clone = cloningElement.clone(true, false).attr('data-id', question_length).addClass('ays-survey-new-question').insertAfter( cloningElement );
            var newElement = $this.parents('.ays-survey-section-box').find('.ays-survey-question-answer-conteiner.ays-survey-new-question[data-id = '+ question_length +']');

            var linearScaleOldLength = "";
            if(question_type == 'linear_scale'){
                linearScaleOldLength = cloningElement.find(".ays-survey-choose-for-select-lenght").val();
                newElement.find('select.ays-survey-choose-for-select-lenght').val(linearScaleOldLength);
            }

            var starScaleOldLength = "";
            if(question_type == 'linear_scale'){
                starScaleOldLength = cloningElement.find(".ays-survey-choose-for-start-select-lenght").val();
                newElement.find('select.ays-survey-choose-for-start-select-lenght').val(starScaleOldLength);
            }

            newElement.attr('data-id', question_length);
            newElement.attr('data-name', 'questions_add');
            newElement.find('input[name="ays_question_ids[]"]').remove();
            newElement.find('textarea.ays-survey-question-input.ays-survey-input').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'title') );
            newElement.find('textarea.ays-survey-description-input.ays-survey-input').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'description') );
            newElement.find('input.ays-survey-question-img-src').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'image') );
            newElement.find('input.ays-survey-input-required-question').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'required') );
            newElement.find('input.ays-survey-question-ordering').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'ordering') );
            newElement.find('input.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'collapsed'));
            newElement.find('input.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'user_variant') );
            newElement.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_max_selection_count'));
            newElement.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'max_selection_count'));
            newElement.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'min_selection_count'));
            // Text limitation options
            newElement.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, question_length, 'options', 'enable_word_limitation'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_by'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_length'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_counter'));
            // Number limitation options
            newElement.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, question_length, 'options', 'enable_number_limitation'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_min_selection'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_max_selection'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_error_message'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_number_error_message'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_limit_length'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_number_limit_counter'));
            
            newElement.find('.ays-survey-question-is-logic-jump').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'is_logic_jump'));
            newElement.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'with_editor'));
            newElement.find('.ays-survey-question-user-explanation').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'user_explanation'));
            newElement.find('.ays-survey-question-admin-note-saver').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_admin_note'));
            newElement.find('.ays-survey-question-admin-note-label input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'admin_note'));
            newElement.find('.ays-survey-question-url-parameter-saver').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_url_parameter'));
            newElement.find('.ays-survey-question-hide-results-saver').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_hide_results'));
            newElement.find('.ays-survey-question-url-parameter-label input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'url_parameter'));
            // Input types placeholders
            newElement.find('.ays-survey-remove-default-border.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'placeholder'));

            newElement.find('input.ays-survey-question-image-caption').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'image_caption') );
            newElement.find('input.ays-survey-question-img-caption-enable').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'image_caption_enable') );


            if( newElement.find('select.ays-survey-question-type').length ){
                newElement.find('select.ays-survey-question-type').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'type') );
            }else if( newElement.find('.ays-survey-question-type select').length ){
                newElement.find('.ays-survey-question-type select').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'type') );
            }
            var ddElem = newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('get text');
            newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('set text', ddElem);
            newElement.find('.ays-survey-question-conteiner .ays-survey-question-type select option[value="'+question_type+'"]').prop('selected', true);
            // newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('set selected', question_type);
            newElement.find('.ays_question_ids').val(question_length);
            newElement.find('.ays_question_old_ids').remove();

            if( question_type == 'matrix_scale' || question_type == 'matrix_scale_checkbox' || question_type == 'star_list' || question_type == 'slider_list'){
                var matrixRows = newElement.find('.ays-survey-answers-conteiner-row .ays-survey-answer-box input.ays-survey-input');
                var matrixColumns = newElement.find('.ays-survey-answers-conteiner-column .ays-survey-answer-box input.ays-survey-input');
                matrixRows.each(function(i){
                    $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                    $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                    $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                    $(this).attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'title') );
                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'ordering') );
                });
                if(question_type == 'matrix_scale' || question_type == 'matrix_scale_checkbox'){
                    matrixColumns.each(function(i){
                        $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                        $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                        $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                        var thisNameAttr = $(this).attr('name');
                        var gettingColName = thisNameAttr.split('[columns]');
                        var colName = gettingColName[1].slice(1, gettingColName[1].length-1);
                        $(this).attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'columns', colName ) );

                    });
                }
                newElement.find('select.ays-survey-choose-for-select-lenght-star-list').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'star_list_stars_length'));

                newElement.find('.ays-survey-slider-list-input-range-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'slider_list_range_length'));
                newElement.find('.ays-survey-slider-list-input-range-step-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'slider_list_range_step_length'));
                newElement.find('.ays-survey-slider-list-input-min-value').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'slider_list_range_min_value'));
                newElement.find('.ays-survey-slider-list-input-default-value').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'slider_list_range_default_value'));
                newElement.find('.ays-survey-slider-list-calculation-type').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'slider_list_range_calculation_type'));

            }else{
                var answer = newElement.find('.ays-survey-answers-conteiner .ays-survey-answer-box input.ays-survey-input').each(function(i){
                    $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                    $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                    $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                    $(this).attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'title') );
                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'image') );
                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'ordering') );
                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'options', 'go_to_section'));
                    $(this).parents('.ays-survey-question-answer-conteiner').find('.ays-survey-other-answer-row .ays-survey-answer-logic-jump-select-other').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'go_to_section'));

                });
            }

            newElement.find('input.ays-survey-input-linear-scale-1').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'linear_scale_1'));
            newElement.find('input.ays-survey-input-linear-scale-2').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'linear_scale_2'));
            newElement.find('select.ays-survey-choose-for-select-lenght').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'scale_length'));
            newElement.find('input.ays-survey-input-star-1').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'star_1'));
            newElement.find('input.ays-survey-input-star-2').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'star_2'));
            newElement.find('select.ays-survey-choose-for-start-select-lenght').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'star_scale_length'));

            if(question_type != 'slider_list'){
                // Range type
                newElement.find('input.ays-survey-input-range-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'range_length'));
                newElement.find('input.ays-survey-input-range-step-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'range_step_length'));
                newElement.find('input.ays-survey-input-range-min-val').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'range_min_value'));
                newElement.find('input.ays-survey-input-range-default-val').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'range_default_value'));
            }

            // File upload
            newElement.find('.ays-survey-upload-tpypes-on-off').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'toggle_types'));
            newElement.find('.ays-survey-current-upload-type-pdf').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_pdf'));
            newElement.find('.ays-survey-current-upload-type-doc').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_doc'));
            newElement.find('.ays-survey-current-upload-type-png').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_png'));
            newElement.find('.ays-survey-current-upload-type-jpg').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_jpg'));
            newElement.find('.ays-survey-current-upload-type-gif').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_gif'));
            newElement.find('.ays-survey-question-type-upload-max-size-select').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'upload_size'));

            newElement.find('.ays-survey-answers-conteiner').sortable(answerDragHandle);
            newElement.find('.ays-survey-question-word-limit-by-select select option[value="'+clonedLimitBy+'"]').prop('selected', true);
            if( question_type == 'select' ){
                newElement.find('.ays-survey-other-answer-row').hide();
                newElement.find('.ays-survey-other-answer-checkbox').prop('checked', false );
                newElement.find('.ays-survey-other-answer-add-wrap').hide();
            }

            var conditions = newElement.find('.ays-survey-answer-checkbox-logic-jump-condition');
            conditions.each(function (i){
                var conditionIndex = i + 1;
                var checkboxCondSelectName = sectionName + '['+ sectionId +'][questions_add]['+ question_length +'][options][other_logic_jump]['+ conditionIndex +'][selected_options][]';
                $(this).find('select.ays-survey-checkbox-condition-select').attr('name', checkboxCondSelectName);
                var goToSectionSelectName = sectionName + '['+ sectionId +'][questions_add]['+ question_length +'][options][other_logic_jump]['+ conditionIndex +'][go_to_section]';
                $(this).find('select.ays-survey-answer-logic-jump-select').attr('name', goToSectionSelectName);
            });

            newElement.find('.ays-survey-answer-other-logic-jump-wrapper .ays-survey-answer-other-logic-jump-else-wrap select.ays-survey-answer-logic-jump-select').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'other_logic_jump_otherwise'));

            
            newElement.find('.ays-survey-question-type').aysDropdown();
            cloningElement.find('.ays-survey-question-type').aysDropdown();

            var aysSurveyTextArea = newElement.find('textarea.ays-survey-question-input-textarea');
            autosize(aysSurveyTextArea);
            var aysSurveyQuestionDescriptionTextArea = newElement.find('textarea.ays-survey-question-description-input-textarea');
            autosize(aysSurveyQuestionDescriptionTextArea);
            if( newElement.find('.dropdown .divider').length == 0 ){
                newElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');
            }
            if( cloningElement.find('.dropdown .divider').length == 0 ){
                cloningElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');
            }
            $this.parents('.ays-survey-section-box').find('.ays-survey-question-ordering').each(function(i){
                $(this).val(i+1);
            });
            // newElement.find('.ays-survey-question-type').dropdown('refresh');
            // var answerImg = newElement.find('.aysFormeditorAnswerConteiner input.quantumWizTextinputSimpleinputInput').each(function(i){
            //     $(this).attr('name', $html_name_prefix + 'section_add['+ sectionId +'][question]['+ question_length +'][answer]['+ i +'][title]');
            // });
            if(question_type == 'html'){
                var oldTextAreaContent = newElement.find('.ays-survey-question-types_html .ays-survey-question-types-box-body textarea').html();
                newElement.find('.ays-survey-question-types_html .ays-survey-question-types-box-body').html('<textarea id="ays_html-type-editor-section-'+sectionId+'-add-'+question_length+'" class="wp-editor-area ays-survey-html-question-type-for-js">'+oldTextAreaContent+'</textarea>');
                
                var wpEditorOprions = {
                    tinymce: {
                    plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                    toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
                    toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                    height : "100px"
                    },
                    quicktags: {buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'},
                    mediaButtons: true,
                }
                
                wp.editor.initialize('ays_html-type-editor-section-'+sectionId+'-add-'+question_length,  wpEditorOprions);
                
                newElement.find('.ays-survey-question-types_html .ays-survey-question-types-box-body textarea#ays_html-type-editor-section-'+sectionId+'-add-'+question_length).attr("name" , newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'html_type_editor'));
            }

            setTimeout(function(){
                newElement.goToTop();
            }, 100 );

            reInitPopovers( newElement );
            aysSurveySectionsInitQuestionsCollapse();
        });

        // Remove Question Button
        $(document).on('click', '.ays-survey-action-delete-question', function(e){
            var $this = $(this);
            var length = $this.parents('.ays-survey-section-questions').find('.ays-survey-question-answer-conteiner').length;
            var status = true;
            if(length == 1){
                swal.fire({
                    type: 'warning',
                    text: SurveyMakerAdmin.minimumCountOfQuestions
                });
                status = false;
            }else{
                swal({
                    html:"<h4>"+ SurveyMakerAdmin.questionDeleteConfirmation +"</h4>",
                    type: 'error',
                    showCloseButton: true,
                    showCancelButton: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: SurveyMakerAdmin.yes,
                    cancelButtonText: SurveyMakerAdmin.cancel
                }).then( function(result) {
                    
                    if( result.dismiss && result.dismiss == 'close' ){
                        return false;
                    }

                    var deleteQuest = false;
                    if (result.value) deleteQuest = true;

                    if( deleteQuest ){
                        if( ! $this.parents('.ays-survey-question-answer-conteiner').hasClass('ays-survey-new-question') ){
                            var qId = $this.parents('.ays-survey-question-answer-conteiner').data('id');
                            var delImp = '<input type="hidden" name="'+ $html_name_prefix +'questions_delete[]" value="'+ qId +'">';
                            $this.parents('form').append( delImp );
                        }
                        var section = $this.parents('.ays-survey-section-box');
                        section.find(".ays-survey-action-questions-count span").text(length - 1);
                        $this.parents('.ays-survey-question-answer-conteiner').remove();
                    }
                } );
            }

            if (status == false) {
                e.preventDefault();
            }

        });

        // Remove Section Button
        $(document).on('click', '.ays-survey-delete-section', function(e){
            var $this = $(this);

            swal({
                html:"<h4>"+ SurveyMakerAdmin.sectionDeleteConfirmation +"</h4>",
                type: 'error',
                showCloseButton: true,
                showCancelButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: SurveyMakerAdmin.yes,
                cancelButtonText: SurveyMakerAdmin.cancel
            }).then( function(result) {
                
                if( result.dismiss && result.dismiss == 'close' ){
                    return false;
                }

                var deleteQuest = false;
                if (result.value) deleteQuest = true;

                if( deleteQuest ){
                    var parent = $this.parents('.ays-survey-section-box');
                    if( ! parent.hasClass('ays-survey-new-section') ){
                        var sId = parent.data('id');
                        var delImp = '<input type="hidden" name="'+ $html_name_prefix +'sections_delete[]" value="'+ sId +'">';
                        $this.parents('form').append( delImp );
                    }
                    parent.remove();

                    var sectionCont = $(document).find('.ays-survey-sections-conteiner');
                    var sections = sectionCont.find('.ays-survey-section-box');
                    var addQuestionButton = $(document).find('.ays-survey-general-action[data-action="add-question"]');

                    var length = sectionCont.find('.ays-survey-section-box').length;
                    sections.each(function(i, el){
                        $(this).find('.ays-survey-section-number').text(i+1);
                        $(this).find('.ays-survey-sections-count').text( sections.length );
                    });

                    if (length == 1) {
                        addQuestionButton.dropdown('dispose');
                        addQuestionButton.removeAttr('data-toggle');
                        sections.find('.ays-survey-section-head-top').addClass('display_none');
                        sections.find('.ays-survey-section-head').removeClass('ays-survey-section-head-topleft-border-none');
                        sections.find('.ays-survey-section-actions-more .ays-survey-delete-section').addClass('display_none');
                    }

                    aysSurveySectionsInitToAddQuestions();
                }
            } );
        });

        // Remove Answer Image
        $(document).on('click', '.removeAnswerImage', function (e) {
            var $this = $(this);
            $this.parents('.ays-survey-answer-image-container').fadeOut(500);
            setTimeout(function(){
                $this.parents('.ays-survey-answer-image-container').find('.ays-survey-answer-img').removeAttr('src');
                $this.parents('.ays-survey-answer-image-container').find('.ays-survey-answer-img-src').val('');
            }, 500);
        });

        // Survey Question Image actions Buttons
        $(document).on('click', '.ays-survey-question-img-action', function(e){
            var $this = $(this);
            var action = $this.attr('data-action');
            switch( action ){
                case 'edit-image':
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-add-question-image').trigger('click');
                break;
                case 'delete-image':
                    $this.parents('.ays-survey-question-image-container').fadeOut(500);
                    setTimeout(function(){
                        $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img').removeAttr('src');
                        $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-src').val('');
                        $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-image-caption-text-row .ays-survey-question-image-caption').val('');
                    }, 500);
                break;
                case 'add-caption':
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-image-caption-text-row').removeClass('display_none');
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-caption-enable').val('on');
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-input.ays-survey-question-image-caption').focus();
                    $this.attr('data-action' , 'close-caption');
                    $this.text(SurveyMakerAdmin.closeQuestionImageCaption);
                break;
                case 'close-caption':
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-image-caption-text-row').addClass('display_none');                    
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-caption-enable').val('off');
                    $this.attr('data-action' , 'add-caption');
                    $this.text(SurveyMakerAdmin.addQuestionImageCaption);
                break;
            }
        });

        // Survey General actions Buttons
        $(document).on('click', '.ays-survey-general-action', function(e){
            var $this = $(this);
            var action = $this.data('action');
            switch( action ){
                case 'add-question':
                    if(! $this.attr('data-toggle')){
                        var sectionCont = $(document).find('.ays-survey-sections-conteiner');
                        var sections = sectionCont.find('.ays-survey-section-box');
                        aysSurveyAddQuestion( sections.data('id'), false, sections, true );
                    }
                break;
                case 'import-question':
                
                break;
                case 'add-section-header':
                
                break;
                case 'add-image':
                
                break;
                case 'add-video':
                
                break;
                case 'add-section':
                    aysSurveyAddSection( null, null, true );
                    aysSurveySectionsInitToAddQuestions();
                break;
                case 'save-changes':
                    $(document).find('#ays-button-apply-top').trigger('click');
                break;
                case 'open-modal':
                    $(document).find('.ays-survey-open-import-modal').trigger('click');
                break;
                case 'make-questions-required':
                    makeAllQuestionsRequired($this, false);
                break;


            }
        });

        // Survey Question actions Buttons
        $(document).on('click', '.ays-survey-question-action', function(e){
            var $this = $(this);
            var action = $this.attr('data-action');
            var currentSection = $this.parents('.ays-survey-section-box');
            var currentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
            var sectionId = currentSection.attr('data-id');
            var sectionName = currentSection.attr('data-name');
            var questionId = currentQuestion.attr('data-id');
            var questionName = currentQuestion.attr('data-name');
            var $thisEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count,.ays-survey-question-min-selection-count');
            var $thisWordEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations');
            var $thisNumberEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations');
            var enableNumberLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations-checkbox');

            var questionType = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-type').aysDropdown('get value');

            switch( action ){
                case 'max-selection-count-enable':
                    var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
                    enableCheckbox.prop('checked', true);
                    $thisEl.removeClass('display_none');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'max-selection-count-disable');
                break;
                case 'max-selection-count-disable':
                    var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
                    enableCheckbox.prop('checked', false);
                    $thisEl.addClass('display_none');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'max-selection-count-enable');
                break;
                case 'go-to-section-based-on-answers-enable':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-is-logic-jump');
                    if( $.inArray( questionType, otherLogicJumpTypes ) !== -1 ){
                        parentQuestion.find('.ays-survey-answer-other-logic-jump-wrapper').removeClass('display_none');
                    }else{
                        parentQuestion.find('.ays-survey-answer-logic-jump-wrap').removeClass('display_none');
                    }

                    enableCheckbox.val('on');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'go-to-section-based-on-answers-disable');
                break;
                case 'go-to-section-based-on-answers-disable':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-is-logic-jump');
                    
                    if( $.inArray( questionType, otherLogicJumpTypes ) !== -1 ){
                        parentQuestion.find('.ays-survey-answer-other-logic-jump-wrapper').addClass('display_none');
                    }else{
                        parentQuestion.find('.ays-survey-answer-logic-jump-wrap').addClass('display_none');
                    }

                    enableCheckbox.val('off');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'go-to-section-based-on-answers-enable');
                break;
                case 'move-to-section':
                    var popup = $(document).find('#ays-survey-move-to-section');
                    popup.attr('data-section-id', sectionId);
                    popup.attr('data-section-name', sectionName);
                    popup.attr('data-question-id', questionId);
                    popup.attr('data-question-name', questionName);
                    aysSurveySectionsInitToMoveQuestions( currentSection, currentQuestion );
                    popup.aysModal('show');
                break;
                case 'enable-user-explanation':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-user-explanation');
                    parentQuestion.find('.ays-survey-question-user-explanation-wrap').removeClass('display_none');
                    enableCheckbox.val('on');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'disable-user-explanation');
                break;
                case 'disable-user-explanation':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-user-explanation');
                    parentQuestion.find('.ays-survey-question-user-explanation-wrap').addClass('display_none');
                    enableCheckbox.val('off');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'enable-user-explanation');
                break;
                case 'enable-admin-note':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-admin-note-saver');
                    parentQuestion.find('.ays-survey-question-admin-note').removeClass('display_none');
                    enableCheckbox.val('on');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'disable-admin-note');
                break;
                case 'disable-admin-note':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-admin-note-saver');                    
                    parentQuestion.find('.ays-survey-question-admin-note').addClass('display_none');
                    enableCheckbox.val('off');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'enable-admin-note');
                break;
                case 'enable-url-parameter':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-url-parameter-saver');
                    parentQuestion.find('.ays-survey-question-url-parameter').removeClass('display_none');
                    enableCheckbox.val('on');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'disable-url-parameter');
                break;
                case 'disable-url-parameter':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-url-parameter-saver');                    
                    parentQuestion.find('.ays-survey-question-url-parameter').addClass('display_none');
                    enableCheckbox.val('off');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'enable-url-parameter');
                break;
                case 'enable-hide-results':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-hide-results-saver');
                    enableCheckbox.val('on');
                    $this.find('.ays-survey-question-action-icon').removeClass('display_none');
                    $this.attr('data-action', 'disable-hide-results');
                break;
                case 'disable-hide-results':
                    var parentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
                    var enableCheckbox = parentQuestion.find('.ays-survey-question-hide-results-saver');
                    enableCheckbox.val('off');
                    $this.find('.ays-survey-question-action-icon').addClass('display_none');
                    $this.attr('data-action', 'enable-hide-results');
                break;
                case 'word-limitation-enable':
                    var enableTextLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
                    enableTextLimit.prop('checked', true);
                    $thisWordEl.removeClass('display_none');
                    $this.attr('data-action', 'word-limitation-disable');
                break;
                case 'word-limitation-disable':
                    var enableTextLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
                    enableTextLimit.prop('checked', false);
                    $thisWordEl.addClass('display_none');
                    $this.attr('data-action', 'word-limitation-enable');
                break;
                case 'number-word-limitation-enable':
                    enableNumberLimit.prop('checked', true);
                    $thisNumberEl.removeClass('display_none');
                    $this.attr('data-action', 'number-word-limitation-disable');
                break;
                case 'number-word-limitation-disable':
                    enableNumberLimit.prop('checked', false);
                    $thisNumberEl.addClass('display_none');
                    $this.attr('data-action', 'number-word-limitation-enable');
                break;


            }
        });

        $(document).on('show.bs.dropdown', '.ays-survey-question-more-actions', function(e){
            var $this = $(this);
            var question = $this.parents('.ays-survey-question-answer-conteiner');
            var questionType = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-type').aysDropdown( 'get value' );
            var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
            var enableWordLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
            var enableNumberLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations-checkbox');
            if( enableCheckbox.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.disableSelectionCount);
            }else{
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.enableSelectionCount);
            }

            if( questionType == 'checkbox' ){
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').addClass('display_none');
            }
            
            if( $.inArray( questionType, logicJumpTypes ) !== -1 || $.inArray( questionType, otherLogicJumpTypes ) !== -1 ){
                question.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').addClass('display_none');
            }

            if( enableWordLimit.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').text(SurveyMakerAdmin.disableWordLimitation);
            }else{
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').text(SurveyMakerAdmin.enableWordLimitation);
            }
            
            if( questionType == 'short_text' ||  questionType == 'text' ){
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').addClass('display_none');
            }

            if( enableNumberLimit.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').text(SurveyMakerAdmin.disableNumberLimitation);
            }else{
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').text(SurveyMakerAdmin.enableNumberLimitation);
            }
            
            if( questionType == 'number' || questionType == 'phone'){
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').addClass('display_none');
            }
            
            if(questionType == 'hidden') {
                $this.find('.ays-survey-question-action[data-action^="enable-user-explanation"]').addClass('display_none');
                $this.find('.ays-survey-question-action[data-action^="disable-user-explanation"]').addClass('display_none');
            } else {
                $this.find('.ays-survey-question-action[data-action^="enable-user-explanation"]').removeClass('display_none');
                $this.find('.ays-survey-question-action[data-action^="disable-user-explanation"]').removeClass('display_none');
            }

            if(questionType == 'radio' || questionType == 'checkbox' || questionType == 'linear_scale' || questionType == 'star' || questionType == 'select' || questionType == 'short_text' ||  questionType == 'text' || questionType == 'number' || questionType == 'phone' || questionType == 'yesorno' || questionType == 'range' || questionType == 'email' || questionType == 'name' || questionType == 'hidden') {
                $this.find('.ays-survey-question-action[data-action^="enable-url-parameter"]').removeClass('display_none');
                $this.find('.ays-survey-question-action[data-action^="disable-url-parameter"]').removeClass('display_none');
            } else {
                $this.find('.ays-survey-question-action[data-action^="enable-url-parameter"]').addClass('display_none');
                $this.find('.ays-survey-question-action[data-action^="disable-url-parameter"]').addClass('display_none');
            }

        });

        $(document).on('click', '.ays-survey-add-question-to-this-section', function(e){
            var section = $(this).parents('.ays-survey-section-box');
            var sectionId = section.attr('data-id');
            aysSurveyAddQuestion( sectionId, false, section , true );
        });

        $(document).on('click', '.ays-survey-add-new-section-from-bottom', function(e){
            var afterSectionId = $(this).parents('.ays-survey-section-box').attr('data-id');
            var newSection = $(this).parents('.ays-survey-section-box').hasClass('ays-survey-new-section');
            aysSurveyAddSection( afterSectionId, newSection, true );
            aysSurveySectionsInitToAddQuestions();
        });

        $(document).on('click', '.ays-survey-duplicate-section', function(e){
            var afterSectionId = $(this).parents('.ays-survey-section-box').attr('data-id');
            $(this).parents('.ays-survey-section-box').find(".ays-survey-delete-section").removeClass('display_none');
            var newSection = $(this).parents('.ays-survey-section-box').hasClass('ays-survey-new-section');
            aysSurveyDuplicateSection( $(this), afterSectionId, newSection);
        });


        $(document).on('click', '.ays-survey-add-question-into-section', function(e){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+ $(this).attr( 'data-id' ) +'"]:not(.ays-survey-new-section)');
            if( $(this).hasClass('ays-survey-add-new-question-into-section') ){
                section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box.ays-survey-new-section[data-id="'+ $(this).attr( 'data-id' ) +'"]');
            }
            var sectionId = $(this).attr( 'data-id' );
            aysSurveyAddQuestion( sectionId, false, section, true );
        });

        $(document).on('click', '.ays-survey-move-question-into-section', function(e){
            var popup = $(document).find('#ays-survey-move-to-section');
            var sectionId = popup.attr('data-section-id');
            var sectionDataName = popup.attr('data-section-name');
            var questionId = popup.attr('data-question-id');
            var questionDataName = popup.attr('data-question-name');
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+ $(this).data( 'id' ) +'"]:not(.ays-survey-new-section)');
            if( $(this).hasClass('ays-survey-move-new-question-into-section') ){
                section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box.ays-survey-new-section[data-id="'+ $(this).data( 'id' ) +'"]');
            }
            var oldSection = $(document).find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionDataName+'"]');
            var question = oldSection.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var questions = oldSection.find('.ays-survey-question-answer-conteiner');
            
            if( questions.length <= 1 ){
                swal.fire({
                    type: 'warning',
                    text: SurveyMakerAdmin.minimumCountOfQuestions
                });
            }else{
                setTimeout(function(){
                    question.appendTo( section.find('.ays-survey-section-questions') );
                    draggedQuestionUpdate( question, section );
                    popup.aysModal('hide');
                }, 1);
            }
        });

        $(document).on('change', '.ays-survey-question-type', function(e) {
            var $this = $(this);
            var parent = $this.parents('.ays-survey-section-box');
            var bulkAddAnswer = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-actions-answers-bulk-add');
            var sectionId = parent.attr('data-id');
            var questionId = $this.parents('.ays-survey-question-answer-conteiner').attr('data-id');
            var questionDataName = $this.parents('.ays-survey-question-answer-conteiner').attr('data-name');
            var questionType = $this.aysDropdown('get value'); //$this.val();
            var questionTypeBeforeChange = $this.parents('.ays-survey-question-type-box').find('.ays-survey-check-type-before-change').val();
            var answerMatrixRowIds = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answers-conteiner-row .ays-survey-answer-row');
            var answerIds = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answers-conteiner .ays-survey-answer-row');
            $this.parents('.ays-survey-question-type-box').find('.ays-survey-check-type-before-change').val(questionType);

            if( questionType == 'radio' || questionType == 'checkbox' || questionType == 'select' ){
                bulkAddAnswer.removeClass('display_none');
                if ( questionTypeBeforeChange == 'yesorno' ) {
                    if (answerIds != undefined) {
                        answerIds.each(function(e) {
                            var answerId = $(this).attr('data-id');
                            if( ! $(this).hasClass('ays-survey-new-answer') ){
                                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                                $this.parents('form').append( delImp );
                            }
                        });
                    }
                }
            }else{
                bulkAddAnswer.addClass('display_none');
                if ( questionTypeBeforeChange == 'radio' || questionTypeBeforeChange == 'checkbox' || questionTypeBeforeChange == 'select' || questionTypeBeforeChange == 'yesorno' ) {
                    if (answerIds != undefined) {
                        answerIds.each(function(e) {
                            var answerId = $(this).attr('data-id');
                            if( ! $(this).hasClass('ays-survey-new-answer') ){
                                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                                $this.parents('form').append( delImp );
                            }
                        });
                    }
                }
            }

            if(questionType == 'radio' || questionType == 'checkbox' || questionType == 'linear_scale' || questionType == 'star' || questionType == 'select' || questionType == 'short_text' ||  questionType == 'text' || questionType == 'number' || questionType == 'phone' || questionType == 'yesorno' || questionType == 'range' || questionType == 'email' || questionType == 'name') {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-url-parameter').removeClass('display_none');
            } else {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-url-parameter').addClass('display_none');
            }
    
            if(!($this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-action[data-action^="disable-url-parameter"]')[0])) {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-url-parameter').addClass('display_none');
            }

            if(questionType =='hidden') {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-user-explanation-wrap').addClass('display_none');
            } else {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-user-explanation-wrap').removeClass('display_none');
            }
    
            if(!($this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-action[data-action^="disable-user-explanation"]')[0])) {
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-user-explanation-wrap').addClass('display_none');
            }


            if(answerMatrixRowIds != undefined){
                answerMatrixRowIds.each(function(e) {
                    var answerRowId = $(this).attr('data-id');
                    if( ! $(this).hasClass('ays-survey-new-answer') ){
                        var delRowImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerRowId +'">';
                        $this.parents('form').append( delRowImp );
                    }
                });
            }

            var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-is-logic-jump');
            if( enableCheckbox.val() == 'on' ){
                if( $.inArray( questionType, logicJumpTypes ) !== -1 ){
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-logic-jump-wrap').removeClass('display_none');
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-other-logic-jump-wrapper').addClass('display_none');
                }else if( $.inArray( questionType, otherLogicJumpTypes ) !== -1 ){
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-logic-jump-wrap').addClass('display_none');
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-other-logic-jump-wrapper').removeClass('display_none');
                }else{
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-logic-jump-wrap').addClass('display_none');
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-other-logic-jump-wrapper').addClass('display_none');
                }
            }else{
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-logic-jump-wrap').addClass('display_none');
                $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answer-other-logic-jump-wrapper').addClass('display_none');
            }


            switch( questionType ){
                case 'radio':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'checkbox':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'select':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'text':
                case 'hidden':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'short_text':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'number':
                case 'phone':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'yesorno':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'email':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'name':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'linear_scale':
                    aysSurveyQuestionType_LinearScale_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'date':
                    aysSurveyQuestionType_Date_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'time':
                    aysSurveyQuestionType_Time_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'date_time':
                    aysSurveyQuestionType_Date_Time_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'star':
                    aysSurveyQuestionType_Star_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'matrix_scale':
                case 'star_list':
                case 'slider_list':
                case 'matrix_scale_checkbox':
                    aysSurveyQuestionType_MatrixTypes_Html( sectionId , questionId , questionDataName, questionType,  false , parent );
                break;
                case 'range':
                    aysSurveyQuestionType_Range_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent);
                break;
                case 'upload':
                    aysSurveyQuestionType_Upload_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent);
                break;
                case 'html':
                    aysSurveyQuestionType_Html_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent);
                break;
                default:
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
            }
        });

        $(document).on('click', '.ays-survey-question-answer-conteiner .ays-survey-action-add-condition', function(e) {
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-checkbox-logic-jump-condition-to-clone');
            var clonedElement = cloningElement.find('.ays-survey-answer-checkbox-logic-jump-condition').clone(true, false);
            var conditionsCont = $(this).parents('.ays-survey-answer-other-logic-jump-wrapper').find('.ays-survey-answer-checkbox-logic-jump-conditions');
            var conditions = conditionsCont.find('.ays-survey-answer-checkbox-logic-jump-condition');

            var parentQuestion = $(this).parents('.ays-survey-question-answer-conteiner');
            var questionId = parentQuestion.attr('data-id');
            var questionName = parentQuestion.attr('data-name');

            var parentSection = parentQuestion.parents('.ays-survey-section-box');
            var sectionId = parentSection.attr('data-id');
            var sectionName = parentSection.attr('data-name');

            var answers = getCheckboxAnswersAll( $(this).parents('.ays-survey-question-answer-conteiner') );
            var answersHtmlForSelect = generateAnswersHtmlForSelect( answers );
            
            clonedElement.find('.ays-survey-checkbox-condition-select option').remove();
            clonedElement.find('.ays-survey-checkbox-condition-select').append($(answersHtmlForSelect));
            clonedElement.find('.ays-survey-checkbox-condition-select').select2();
            // clonedElement
            conditionsCont.find('.ays-surevy-checkbox-logic-jump-empty-condition').remove();

            var lastId = 0;
            if( conditionsCont.find('.ays-survey-answer-checkbox-logic-jump-condition:last-child').attr('data-condition-id') !== undefined ){
                lastId = parseInt( conditionsCont.find('.ays-survey-answer-checkbox-logic-jump-condition:last-child').attr('data-condition-id') );
            }

            var conditionIndex = lastId + 1;
            var checkboxCondSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][selected_options][]';
            clonedElement.find('select.ays-survey-checkbox-condition-select').attr('name', checkboxCondSelectName);
            var goToSectionSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][go_to_section]';
            clonedElement.find('select.ays-survey-answer-logic-jump-select').attr('name', goToSectionSelectName);
            var otherwiseSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump_otherwise]';
            $(this).parents('.ays-survey-answer-other-logic-jump-wrapper').find('.ays-survey-answer-other-logic-jump-else-wrap select.ays-survey-answer-logic-jump-select').attr('name', otherwiseSelectName);
            $(this).parents('.ays-survey-answer-other-logic-jump-wrapper').find('.ays-survey-answer-other-logic-jump-else-wrap').removeClass('display_none');
            clonedElement.attr('data-condition-id', conditionIndex);
            conditionsCont.append(clonedElement);

            reInitPopovers( clonedElement );
        });

        $(document).on('click', '.ays-survey-question-answer-conteiner .ays-survey-delete-question-condition', function(e) {
            var $this = $(this);
            var parent = $this.parents('.ays-survey-answer-checkbox-logic-jump-condition');
            var conditionsCont = $(this).parents('.ays-survey-answer-other-logic-jump-wrapper').find('.ays-survey-answer-checkbox-logic-jump-conditions');
            var conditions = conditionsCont.find('.ays-survey-answer-checkbox-logic-jump-condition');

            if(conditions.length - 1 < 1){
                var emptyContent = $(document).find('.ays-question-to-clone .ays-survey-checkbox-logic-jump-condition-to-clone .ays-surevy-checkbox-logic-jump-empty-condition');
                $this.parents('.ays-survey-answer-checkbox-logic-jump-conditions').append( emptyContent.clone(false, false) );
                $this.parents('.ays-survey-answer-other-logic-jump-wrapper').find('.ays-survey-answer-other-logic-jump-else-wrap').addClass('display_none');

            }

            // if( ! $this.parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ){
            //     var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
            //     $this.parents('form').append( delImp );
            // }

            $this.parents('.ays-survey-condition-delete-currnet').find('[data-toggle="popover"]').popover('hide');
            $this.parents('.ays-survey-answer-checkbox-logic-jump-condition').remove();

            var parentQuestion = conditions.parents('.ays-survey-question-answer-conteiner');
            var questionId = parentQuestion.attr('data-id');
            var questionName = parentQuestion.attr('data-name');

            var parentSection = parentQuestion.parents('.ays-survey-section-box');
            var sectionId = parentSection.attr('data-id');
            var sectionName = parentSection.attr('data-name');
            conditions.each(function (i, item){
                var checkboxCondSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ i +'][selected_options][]';
                $(item).find('select.ays-survey-checkbox-condition-select').attr('name', checkboxCondSelectName);
                var goToSectionSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ i +'][go_to_section]';
                $(item).find('select.ays-survey-answer-logic-jump-select').attr('name', goToSectionSelectName);
            });
        });

        function generateAnswersHtmlForSelect( answers ){
            var html = '';
            for(var i=0; i < answers.length; i++){
                html += '<option value="'+ answers[i][0] +'" >' + answers[i][1] + '</option>';
            }

            return html;
        }

        function getCheckboxAnswersAll( question ){
            var answers = question.find('.ays-survey-answers-conteiner .ays-survey-answer-row');
            var newArr = [];
            answers.each(function (){
                newArr.push([ $(this).data('id'), $(this).find('.ays-survey-answer-box-input-wrap .ays-survey-input').val() ]);
            });
            if( question.find('.ays-survey-other-answer-checkbox').prop('checked') === true ){
                newArr.push([ 'other', SurveyMakerAdmin.other ]);
            }

            return newArr;
        }


        setTimeout(function(){
            if($(document).find('#ays_survey_custom_css').length > 0){
                if(wp.codeEditor){
                    wp.codeEditor.initialize($(document).find('#ays_survey_custom_css'), cm_settings);
                }
            }
        }, 500);

        $(document).find('a[href="#tab2"]').on('click', function (e) {
            setTimeout(function(){
                if($(document).find('#ays_survey_custom_css').length > 0){
                    var ays_survey_custom_css = $(document).find('#ays_survey_custom_css').html();
                    if(wp.codeEditor){
                        $(document).find('#ays_survey_custom_css').next('.CodeMirror').remove();
                        wp.codeEditor.initialize($(document).find('#ays_survey_custom_css'), cm_settings);
                        $(document).find('#ays_survey_custom_css').html(ays_survey_custom_css);
                    }
                }
            }, 500);
        });

        $(document).find('a[href="#tab1"]').on('click', function (e) {
            var aysSurveyTextArea = $(document).find('textarea.ays-survey-question-input-textarea');
            aysSurveyTextArea.each(function(){
                autosize.update( $(this) );
            });
            var aysSurveyQuestionDescriptionTextArea = $(document).find('textarea.ays-survey-question-description-input-textarea');
            aysSurveyQuestionDescriptionTextArea.each(function(){
                autosize.update( $(this) );
            });

            var aysSurveyDescriptionTextArea = $(document).find('textarea.ays-survey-section-description');
            aysSurveyDescriptionTextArea.each(function(){
                autosize.update( $(this) );
            });
        });

        $(document).find('#ays_survey_schedule_active, #ays_survey_schedule_deactive,#ays_survey_change_creation_date').datetimepicker({
            controlType: 'select',
            oneLine: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm:ss"
        });

        $(document).on('click', '.ays-survey-open-question-editor', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            var question = $(this).parents('.ays-survey-question-answer-conteiner');
            var questionId = question.data('id');
            var questionName = question.data('name');
            var section = question.parents('.ays-survey-section-box');
            var sectionId = section.data('id');
            var sectionName = section.data('name');
            var questionContent = question.find('textarea.ays-survey-question-input-textarea').val();
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', questionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', questionName );
            
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-section-id', sectionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-section-name', sectionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-section-id', sectionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-section-name', sectionName );

            if ( $(document).find("#wp-ays_survey_question_editor-wrap").hasClass("tmce-active") ){
                window.tinyMCE.get('ays_survey_question_editor').setContent( questionContent );
            }else{
                $(document).find('#ays_survey_question_editor').val( questionContent );
            }

            editorPopup.aysModal('open');
        });

        $(document).on('dblclick click', '.ays-survey-question-preview-box', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            var question = $(this).parents('.ays-survey-question-answer-conteiner');
            var questionId = question.data('id');
            var questionName = question.data('name');
            var questionContent = question.find('textarea.ays-survey-question-input-textarea').val();
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', questionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', questionName );

            window.tinyMCE.get('ays_survey_question_editor').setContent( questionContent );
            editorPopup.aysModal('open');
        });

        $(document).on('click', '.ays-survey-back-to-textarea', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            var questionId = $(this).attr('data-question-id');
            var questionName = $(this).attr('data-question-name');
            var question = $(document).find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionName+'"]');
            var aysSurveyQuestionTextArea = question.find('textarea.ays-survey-question-input-textarea');

            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', '' );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', '' );

            question.find('.ays-survey-open-question-editor-flag').val('off');

            question.find('.ays-survey-question-input-box').removeClass('display_none');
            question.find('.ays-survey-question-preview-box').addClass('display_none');

//            setTimeout( function(){
                autosize.update(aysSurveyQuestionTextArea);
//            }, 100 );

            editorPopup.aysModal('hide');
        });

        $(document).on('change', '.ays-survey-section-title', function(){
            updateLogicJumpSelects();
            updateSectionLogicJumpSelects();
        });

        // Add Matrix Scale Column/Row
        $(document).on("click", ".ays-survey-action-add-answer-row-and-column", function(){
            var $this = $(this);
            aysSurveyAddMatrixRowOrColumn( $this, false );
        });

        // Delete Matrix Row
        $(document).on("click", ".ays-survey-answer-delete-row", function(e){
            var $this = $(this);
            var answerId = $this.parents('.ays-survey-answer-row').data('id');
            var length = $this.parents('.ays-survey-answers-conteiner-row').find('.ays-survey-answer-delete-row').length - 1;
            var parent = $this.parents('.ays-survey-answers-conteiner-row');
            var hideDeleteButton = parent.find('.ays-survey-answer-delete-row');
            if(length == 1){
                hideDeleteButton.css('visibility','hidden');
            }else{
                hideDeleteButton.removeAttr('style');
            }
            if( ! $this.parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ){
                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                $this.parents('form').append( delImp );
            }
            $this.parents('.ays-survey-answer-row').find('[data-toggle="popover"]').popover('hide');
            $this.parents('.ays-survey-answer-row').remove();
        });

        // Delete Matrix Column
        $(document).on("click", ".ays-survey-answer-delete-column", function(e){
            var $this = $(this);
            var answerId = $this.parents('.ays-survey-answer-row').data('id');
            var length = $this.parents('.ays-survey-answers-conteiner-column').find('.ays-survey-answer-delete-column').length - 1;
            var parent = $this.parents('.ays-survey-answers-conteiner-column');
            var hideDeleteButton = parent.find('.ays-survey-answer-delete-column');
            if(length == 1){
                hideDeleteButton.css('visibility','hidden');
            }else{
                hideDeleteButton.removeAttr('style');
            }
            if( ! $this.parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ){
                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                $this.parents('form').append( delImp );
            }
            $this.parents('.ays-survey-answer-row').find('[data-toggle="popover"]').popover('hide');
            $this.parents('.ays-survey-answer-row').remove();
        });
        

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////ARO END/////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        aysSurveySectionsInitToAddQuestions();
        function aysSurveySectionsInitToAddQuestions(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var addQuestionButton = $(document).find('.ays-survey-general-action[data-action="add-question"]');
            var ddmenu = addQuestionButton.parent().find('.dropdown-menu');
            ddmenu.html('');
            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                }

                var newClassToAddQuestion = '';
                if( _this.hasClass('ays-survey-new-section') ){
                    newClassToAddQuestion = 'ays-survey-add-new-question-into-section';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );

                // var logicJumpSelects = _this.find('.ays-survey-answer-logic-jump-select');
                // logicJumpSelects.each(function(j){
                //     $(this).html( aysBuildLogicJumpSelect() );
                // });
                updateLogicJumpSelects();

                var buttonItem = '<button class="dropdown-item ays-survey-add-question-into-section '+ newClassToAddQuestion +'" data-id="'+ $(this).attr('data-id') +'" type="button">';
                buttonItem += SurveyMakerAdmin.addIntoSection + ' ' + (i+1);
                buttonItem += '</button>';
                ddmenu.append(buttonItem);
            });
            if(sections.length > 1){
                addQuestionButton.attr('data-toggle', 'dropdown');
                addQuestionButton.attr('aria-expanded', 'false');
            }else{
                addQuestionButton.dropdown('dispose');
                addQuestionButton.removeAttr('data-toggle');
            }
        }

        function aysSurveySectionsInitToMoveQuestions( currentSection, currentQuestion ){
            var popup = $(document).find('#ays-survey-move-to-section');
            var currentSectionId = popup.attr('data-section-id');
            var currentSectionName = popup.attr('data-section-name');
            var currentQuestionId = popup.attr('data-question-id');
            var currentQuestionName = popup.attr('data-question-name');

            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var moveSectionsCont = popup.find('.ays-survey-move-to-section-sections-wrap');
            moveSectionsCont.html('');

            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                }

                var disabled = '';
                if( currentSectionId == _this.attr('data-id') ){
                    disabled = ' disabled ';
                }

                var newClassToAddQuestion = '';
                if( _this.hasClass('ays-survey-new-section') ){
                    newClassToAddQuestion = 'ays-survey-move-new-question-into-section';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );

                var buttonItem = '<button class="dropdown-item ays-survey-move-question-into-section '+ newClassToAddQuestion +'" ' + disabled + ' data-id="'+ $(this).data('id') +'" type="button">';
                buttonItem += aysCreateSectionName( _this, i+1, SurveyMakerAdmin.moveToSection ); //SurveyMakerAdmin.addIntoSection + ' ' + (i+1);
                buttonItem += '</button>';
                moveSectionsCont.append(buttonItem);
            });
        }

        function aysCreateSectionName( section, index, text ){
            var sectionName = section.find('.ays-survey-section-title').val();
            var name = text + ' ' + index;
            if( sectionName == '' ){
                name +=  ' (Untitled Form) ';
            }else{
                name +=  ' ('+ sectionName +') ';
            }
            return name;
        }

        function aysBuildLogicJumpSelect( selected ){
            var content = '';
            content += '<option ' + ( parseInt( selected ) == -1 ? 'selected' : '' ) + ' value="-1">' + SurveyMakerAdmin.continueToNextSection + '</option>';
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(i){
                var _this = $(this);
                var selectedOption = '';
                var optionValue = _this.data('id');
                var comparableIdValue = _this.data('id');
                if( _this.hasClass('ays-survey-new-section') ){
                    optionValue = 'new_section_' + optionValue;
                    comparableIdValue = 'new_section_' + comparableIdValue;
                }
                if( selected == comparableIdValue ){
                    selectedOption = ' selected ';
                }
                content += '<option ' + selectedOption + ' value="' + optionValue + '">' + aysCreateSectionName( _this, i+1, SurveyMakerAdmin.goToSection ) + '</option>';
            });
            content += '<option ' + ( parseInt( selected ) == -2 ? 'selected' : '' ) + ' value="-2">' + SurveyMakerAdmin.submitForm + '</option>';
            return content;
        }

        function updateLogicJumpSelects(){
            // var _this = $(document).find('.ays-survey-sections-conteiner');
            // var logicJumpSelects = _this.find('.ays-survey-answer-logic-jump-select');
            // logicJumpSelects.each(function(j){
            //     var selected = null;
            //     if( $(this).find('option:selected').length > 0 ){
            //         selected = $(this).find('option:selected').attr('value');
            //     }
            //     $(this).html( aysBuildLogicJumpSelect( selected ) );
            // });
        }

        function updateSectionLogicJumpSelects(){
            // var _this = $(document).find('.ays-survey-sections-conteiner');
            // var logicJumpSelects = _this.find('.ays-survey-section-logic-jump-select');
            // logicJumpSelects.each(function(j){
            //     var selected = null;
            //     if( $(this).find('option:selected').length > 0 ){
            //         selected = $(this).find('option:selected').attr('value');
            //     }
            //     $(this).html( aysBuildLogicJumpSelect( selected ) );
            // });
        }

        aysSurveySectionsInitQuestionsCollapse();
        function aysSurveySectionsInitQuestionsCollapse(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                var collapseButtonAddClass = 'ays-survey-collapse-sec-quests';
                var collapseButtonRemoveClass = 'ays-survey-expand-sec-quests';
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                    collapseButtonAddClass = 'ays-survey-expand-sec-quests';
                    collapseButtonRemoveClass = 'ays-survey-collapse-sec-quests';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );
                _this.find('.ays-survey-collapse-section-questions').addClass( collapseButtonAddClass );
                _this.find('.ays-survey-collapse-section-questions').removeClass( collapseButtonRemoveClass );
            });
        }

        function aysSurveyAddQuestion( sectionId, returnElem = false, sectionElem = null, notToMove, isFromTemplate = false ){
            var section = sectionElem;
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner');
            var clonedElement = cloningElement.clone( true, false );
            // var questionsLength = section.find('.ays-survey-question-answer-conteiner .ays-survey-question-input-box .ays-survey-input[name^="ays_section_add"]').length;
            var questionsLength = section.find('.ays-survey-question-answer-conteiner').length;
            var defaultAnswersCount = $(document).find('input[name="ays_default_answers_count"]').val();
            var defaultQuestionType = $(document).find('input[name="ays_default_question_type"]').val();
            var answers = clonedElement.find('.ays-survey-answers-conteiner .ays-survey-answer-row');

            var questionId = questionsLength + 1;
            if(isFromTemplate){
                var questionTemplateId = questionsLength + 1;
            }

            var questionName = clonedElement.attr('data-name');
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            var questionsCount = questionsLength;
            var questionsCountBox = section.find(".ays-survey-action-questions-count span").text(questionsCount + 1);

            // Answers ordering jQuery UI
            clonedElement.find('.ays-survey-answers-conteiner').sortable(answerDragHandle);


            var aysSurveyTextArea = clonedElement.find('textarea.ays-survey-question-input-textarea');
            setTimeout( function(){
                autosize(aysSurveyTextArea);
            }, 100 );

            var aysSurveyQuestionDescriptionTextArea = clonedElement.find('textarea.ays-survey-question-description-input-textarea');
            setTimeout( function(){
                autosize(aysSurveyQuestionDescriptionTextArea);
            }, 100 );


            clonedElement.addClass('ays-survey-new-question');
            clonedElement.attr('data-id', questionId);
            if(isFromTemplate){                
                clonedElement.attr('data-template-id', questionTemplateId);
            }
            clonedElement.find('textarea.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'title'));
            clonedElement.find('textarea.ays-survey-description-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'description'));
            clonedElement.find('select.ays-survey-question-type').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'type'));
            clonedElement.find('.ays-survey-question-img-src').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'image'));
            clonedElement.find('.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'user_variant'));
            clonedElement.find('.ays-survey-input-required-question').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'required'));
            clonedElement.find('.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'collapsed'));
            clonedElement.find('.ays-survey-question-ordering').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'ordering'));
            clonedElement.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_max_selection_count'));
            clonedElement.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'max_selection_count'));
            clonedElement.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            clonedElement.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, questionId, 'options', 'enable_word_limitation'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_by'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_length'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_counter'));
            // Number limitation options 
            clonedElement.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, questionId, 'options', 'enable_number_limitation'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_min_selection'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_max_selection'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_error_message'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_number_error_message'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_limit_length'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_number_limit_counter'));
            
            clonedElement.find('.ays-survey-question-image-caption').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'image_caption'));
            clonedElement.find('.ays-survey-question-img-caption-enable').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'image_caption_enable'));

            clonedElement.find('.ays-survey-question-is-logic-jump').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'is_logic_jump'));
            clonedElement.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'with_editor'));
            clonedElement.find('.ays-survey-question-user-explanation').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'user_explanation'));
            clonedElement.find('.ays-survey-question-admin-note-saver').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_admin_note'));
            clonedElement.find('.ays-survey-question-admin-note-label input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'admin_note'));
            clonedElement.find('.ays-survey-question-url-parameter-saver').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_url_parameter'));
            clonedElement.find('.ays-survey-question-hide-results-saver').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_hide_results'));
            clonedElement.find('.ays-survey-question-url-parameter-label input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'url_parameter'));
            clonedElement.find('.ays-survey-question-ordering').val(questionId);
            clonedElement.find('.ays-survey-answer-logic-jump-select-other').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'go_to_section'));

            answers.each(function(j){
                var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                $(this).addClass('ays-survey-new-answer');
                $(this).attr('data-id', answerId);
                $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                $(this).find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'image'));
                $(this).find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));
                $(this).find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));

                
                var deleteButton = $(this).find('.ays-survey-answer-delete');
                if( answers.length == 1){
                    deleteButton.css('visibility', 'hidden');
                }else{
                    deleteButton.removeAttr('style');
                }
            });
            
            clonedElement.find('.ays-survey-question-more-actions').removeClass('display_none');
            clonedElement.find('select.ays-survey-question-type').aysDropdown();
            clonedElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');

            var sectionCollapsedInput = section.find('.ays-survey-section-collapsed-input');
            var sectionCollapsed = sectionCollapsedInput.val() == 'collapsed' ? true : false;
            
            if( sectionCollapsed ){
                expandSection( sectionCollapsedInput );
            }

            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-section-questions').append(clonedElement);
                clonedElement = section.find('.ays-survey-question-answer-conteiner.ays-survey-new-question[data-id="'+questionId+'"]');
            }

            clonedElement.find('.ays-survey-question-type').aysDropdown('set selected', defaultQuestionType);
            clonedElement.find('.ays-survey-question-type').aysDropdown('set value', defaultQuestionType);

            if(!isFromTemplate){
                setTimeout(function(){
                    if(notToMove){
                        clonedElement.goToTop();
                    }
                    updateLogicJumpSelects();
                }, 100 );
            }
    
            reInitPopovers( clonedElement );
            aysSurveySectionsInitQuestionsCollapse();
        }

        function createPrompt() {
            var surveyTheme = ($("#ays_survey_ai_survey_theme").val() != '') ? $("#ays_survey_ai_survey_theme").val() : "Customer Satisfaction";
			var questionsCount = ($("#ays_survey_ai_questions_count").val() != '' && $("#ays_survey_ai_questions_count").val() != '' <= 25 ) ? $("#ays_survey_ai_questions_count").val() : 25;
			var answersCount = ($("#ays_survey_ai_answers_count").val() != '' && $("#ays_survey_ai_answers_count").val() != '' <= 4) ? $("#ays_survey_ai_answers_count").val() : 4;

			var prompt = `Generate a survey JSON response without line breaks.For example, {"theme": "survey theme","questions": [{"q_title":"survey question","options":["answer1","answer2","answer3"]}]}.This is a ${surveyTheme} survey which contains ${questionsCount} questions, each with ${answersCount} answer options.`;

			return prompt;
        }


        function generateQuestions(url, key, tries = 0) {
            return fetch(url, {
                method:"POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${key}`
                },
                body: JSON.stringify({
                    model: "text-davinci-003",
                    prompt: createPrompt(),
                    max_tokens: 4022,
                    temperature: 0.8
                })
            })
            .then(response => response.json())
            .then(response => {
                if (response.error && response.error.message) {
                    throw new Error(response.error.message);
                }
                return response;
            })
            .catch(error => {
                if (tries < 3) {
                      return generateQuestions(url, key, tries + 1);
                } else {
                    swal.fire({
                        type: 'error',
                        text: error.message
                    });
                    $(document).find('div.ays-survey-preloader').css('display', 'none');
                    $(document).find('div.ays-survey-preloader').removeClass('ays_survey_ai_modal_overlay');
                    return false;
                }
            });
        }

        function convertToImportFile(data) {
            var dataToImport = {}
			var allQuestions = [];
			var allAnswers = [];

			var questions = data.questions;

			for(var i = 0; i < questions.length; i++) {
				allQuestions.push(questions[i].q_title);
				allAnswers.push(questions[i].options);
			}

			dataToImport.all_questions = allQuestions;
			dataToImport.all_answers = allAnswers;

			return dataToImport;
        }

        function addQuestionsToFirstSection(data) {
            var allQuestions = data.all_questions;
			var allAnswers = data.all_answers;
			var newQuestion = "";
			var parent = $(document).find(".ays-survey-sections-conteiner");
			var firstSection = parent.find(".ays-survey-section-box:first-child");
			var addQuestionButton = firstSection.find(".ays-survey-add-question-to-this-section")

			for( var eachQuestion in allQuestions ){
				aysSurveyAddQuestion(firstSection.attr("data-id"), false, firstSection, false);

				newQuestion = firstSection.find("div.ays-survey-new-question:last-child");

				newQuestion.find('.ays-survey-question-type').aysDropdown('set selected', 'radio' );
				newQuestion.find('.ays-survey-question-type').aysDropdown('set value', 'radio' );
				newQuestion.find('.ays-survey-check-type-before-change').val('radio');
				newQuestion.find(".ays-survey-question-input-textarea").html( allQuestions[eachQuestion] );
				var thisButton = newQuestion.find(".ays-survey-action-add-answer");

				for( var eachAnswer in allAnswers[eachQuestion] ) {
					if(allAnswers[eachQuestion][eachAnswer]) {
						aysSurveyAddAnswer(thisButton, false, false);
						var newAnswer = newQuestion.find("div.ays-survey-new-answer:last-child");
						newAnswer.find(".ays-survey-input").val(allAnswers[eachQuestion][eachAnswer]);
					}
				}

				newQuestion.find(".ays-survey-answer-row.ays-survey-new-answer:first-child").remove();
			}
			$(document).find('div.ays-survey-preloader').css('display', 'none');
			$(document).find('div.ays-survey-preloader').removeClass('ays_survey_ai_modal_overlay');
			swal({
				title: '<strong>Great Job!</strong>',
				type: 'success',
				html: '<p>Questions were successfully added to the first section</p>',
				showCloseButton: true,
				focusConfirm: false,
				confirmButtonText: '<i class="ays_fa ays_fa_thumbs_up"></i> Great!',
				onAfterClose: function() {
					var modal = $(".ays-survey-ai-modal-window");
                        modal.removeClass("show");
				}
			});
        }

        function aysSurveyAddSection( afterSectionId, newSection, notToMove, isFromTemplate = false ){

            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var sectionsAdd = sectionCont.find('.ays-survey-new-section');

            var section = $(document).find('.ays-question-to-clone .ays-survey-section-box');
            var clonedElement = section.clone( true, false );
            var sectionNewId = sectionsAdd.length + 1;

            clonedElement.attr('data-id', sectionNewId);
            clonedElement.attr('data-name', 'ays_section_add');
            clonedElement.addClass('ays-survey-new-section');
            clonedElement.find('.ays-survey-section-title').attr('name', newSectionAttrName( sectionNewId, 'title' ));
            clonedElement.find('.ays-survey-section-description').attr('name', newSectionAttrName( sectionNewId, 'description' ));
            clonedElement.find('.ays-survey-section-ordering').attr('name', newSectionAttrName( sectionNewId, 'ordering' ));
            clonedElement.find('.ays-survey-section-collapsed-input').attr('name', newSectionAttrName( sectionNewId, 'options', 'collapsed' ));
            clonedElement.find('.ays-survey-section-logic-jump-select').attr('name', newSectionAttrName( sectionNewId, 'options', 'go_to_section' ));
            
            if( typeof afterSectionId !== 'undefined' && afterSectionId !== null ){
                if( newSection === true ){
                    clonedElement.insertAfter( sectionCont.find('.ays-survey-section-box.ays-survey-new-section[data-id="' + afterSectionId + '"]') );
                }else{
                    clonedElement.insertAfter( sectionCont.find('.ays-survey-section-box.ays-survey-old-section[data-id="' + afterSectionId + '"]') );
                }
            }else{
                sectionCont.append( clonedElement );
            }
            
            var defaultAnswersCount = $(document).find('input[name="ays_default_answers_count"]').val();
            var defaultQuestionType = $(document).find('input[name="ays_default_question_type"]').val();
            var question = aysSurveyAddQuestion( sectionNewId, true, clonedElement, true );
            clonedElement.find('.ays-survey-section-questions').append( question );

            question.find('.ays-survey-question-type').aysDropdown('set selected', defaultQuestionType);
            question.find('.ays-survey-question-type').aysDropdown('set value', defaultQuestionType);
            
            clonedElement.find('.ays-survey-section-questions').sortable(questionDragHandle);

            sectionCont = $(document).find('.ays-survey-sections-conteiner');
            sections = sectionCont.find('.ays-survey-section-box');

            sections.each(function(i){
                $(this).find('.ays-survey-section-ordering').val( i + 1 );
            });
            
            var aysSurveyDescriptionTextArea = clonedElement.find('textarea.ays-survey-section-description');
            setTimeout( function(){
                autosize(aysSurveyDescriptionTextArea);
            }, 100 );
            
            sectionCont.find('.ays-survey-section-head-top').removeClass('display_none');
            sectionCont.find('.ays-survey-section-head').addClass('ays-survey-section-head-topleft-border-none');

            sectionCont.find('.ays-survey-section-box').each(function( index ){
                $(this).find('.ays-survey-section-number').text( index + 1 );
            });

            sectionCont.find('.ays-survey-sections-count').text( sections.length );

            // sectionCont.find('.invisible').removeClass( 'invisible' );
            sectionCont.find('.ays-survey-section-actions-more .ays-survey-delete-section').removeClass('display_none');
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-survey-answer-dlg-dragHandle .ays-survey-icons').addClass( 'invisible' );
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-question-img-icon-content').parents('.ays-survey-answer-icon-box').addClass( 'invisible' );
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-survey-other-answer-delete-icon').parents('.ays-survey-answer-icon-box').addClass( 'invisible' );

            if(notToMove && !isFromTemplate){
                setTimeout(function(){
                    clonedElement.goToTop();
                }, 100 );
            }

            updateSectionLogicJumpSelects();
            reInitPopovers( clonedElement );
        }

        function aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null ){

            var removeHtml = true; // Remove Html
            if(questionTypeBeforeChange == 'radio' || questionTypeBeforeChange == 'select' || questionTypeBeforeChange == 'checkbox'){
                removeHtml = false;
            }

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner .ays-survey-answers-conteiner');
            var cloningElement_2 = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner .ays-survey-other-answer-and-actions-row');

            if( questionType == 'yesorno' ){
                cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-type-yes-or-no');
                removeHtml = true;
            }
            
            var clonedElement = cloningElement.clone( true, false );
            var clonedElement_2 = cloningElement_2.clone( true, false );
            
            var answers = clonedElement.find('.ays-survey-answer-row');
            clonedElement.sortable(answerDragHandle);

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');


            var questionName = questionDataName;
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            var placeholderVal = '';
            var questionTypeIconClass = '';
            var answer_icon = clonedElement.find('.ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
            var other_answer_icon = clonedElement_2.find('.ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
            switch( questionType ){
                case 'radio':
                case 'yesorno':
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
                break;
                case 'checkbox':
                    questionTypeIconClass = SurveyMakerAdmin.icons.checkboxUnchecked;
                break;
                case 'select':
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
                break;
                default:
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
            }

            other_answer_icon.attr('src', questionTypeIconClass);
            answer_icon.attr('src', questionTypeIconClass);

            clonedElement_2.find('.ays-survey-other-answer-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'user_variant'));
            clonedElement_2.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_max_selection_count'));
            clonedElement_2.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'max_selection_count'));
            clonedElement_2.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'min_selection_count'));
            clonedElement_2.find('.ays-survey-answer-logic-jump-select-other').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'go_to_section'));

            answers.each(function(i){
                var answerId = i+1;
                $(this).addClass('ays-survey-new-answer');
                $(this).attr('data-id', answerId);
                $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                $(this).find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'image'));
                $(this).find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));
                $(this).find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));

            });

            if( questionType == 'select' ){
                clonedElement_2.find('.ays-survey-other-answer-add-wrap').hide();
                clonedElement_2.find('.ays-survey-other-answer-row').hide();
                clonedElement_2.find('.ays-survey-other-answer-checkbox').prop('checked', false );
                question.find('.ays-survey-other-answer-add-wrap').hide();
                question.find('.ays-survey-other-answer-checkbox').prop('checked', false );
            }else{
                clonedElement_2.find('.ays-survey-other-answer-add-wrap').show();
                if( questionTypeBeforeChange == 'select' ){
                    question.find('.ays-survey-other-answer-add-wrap').show();
                }
                // if( questionTypeBeforeChange != 'radio' && questionTypeBeforeChange != 'checkbox' ){
                //     question.find('.ays-survey-other-answer-add-wrap').hide();
                // }else{
                //     if( question.find('.ays-survey-other-answer-checkbox').prop('checked') == true ){
                //         question.find('.ays-survey-other-answer-add-wrap').hide();
                //     }else{
                //         question.find('.ays-survey-other-answer-add-wrap').show();
                //     }
                // }
            }

            var enableMaxSelectionCount = question.find('.ays-survey-question-max-selection-count-checkbox').prop('checked');
            
            if( questionType == "checkbox" && enableMaxSelectionCount ){
                question.find('.ays-survey-question-more-option-wrap').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            }

            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            if ( questionType == "checkbox" || questionType == "select") {
                resetLogicJumpParams( question );
            }
            
            if (( questionTypeBeforeChange == "yesorno" && questionType == "radio" ) || ( questionTypeBeforeChange == "radio" && questionType == "yesorno" )) {
                resetLogicJumpParams( question );
            }

            
            // if( $.inArray( questionType, questionHaveMoreOptions ) !== -1 ){
            //     question.find('.ays-survey-question-more-actions').removeClass('display_none');
            // }else{
            //     question.find('.ays-survey-question-more-actions').addClass('display_none');
            // }

            if( returnElem ){
                clonedElement = $( clonedElement.html() );
                var clonedElementArr = new Array(clonedElement, clonedElement_2);
                return clonedElementArr;
            }else{
                // Remove Html
                if (removeHtml) {
                    clonedElement = $( clonedElement.html() );
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answers-conteiner').html(clonedElement);
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
                }else{
                    var answer_icon_tags = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
                    switch( questionType ){
                        case 'radio':
                        case 'yesorno':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                        break;
                        case 'checkbox':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.checkboxUnchecked);
                        break;
                        case 'select':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                        break;
                        default:
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                    }

                }

                if( questionType == 'select' || questionType == 'checkbox' || questionType == 'radio' || questionType == 'yesorno'){
                    var addAnswerRow = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-other-answer-and-actions-row');
                    clonedElement_2.find('.ays-survey-other-answer-row .ays-survey-answer-icon-box .invisible').removeClass( 'invisible' );
                    
                    if ( removeHtml || questionType == 'select' ) {
                        addAnswerRow.html(clonedElement_2.html());
                    }
                }
            }
        }

        function aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId, questionId, questionDataName, questionType, returnElem = false, sectionElem = null ){
            var section = sectionElem;
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var clonedElement = cloningElement.clone( true, false );

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');
            
            var answers = clonedElement.find('.ays-survey-answer-row');

            var questionName = questionDataName;
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            if(questionType == 'hidden') {
                answers.addClass('display_none');
            }

            // var question_length = section.find('.ays-survey-question-answer-conteiner').length;
            var placeholderVal = '';
            var questionTypeClass = '';
            placeholderVal = SurveyMakerAdmin.inputAnswerText;
            switch( questionType ){
                case 'text':
                    // placeholderVal = SurveyMakerAdmin.longAnswerText;
                    questionTypeClass = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
                break;
                case 'short_text':
                    // placeholderVal = SurveyMakerAdmin.shortAnswerText;
                    questionTypeClass = 'ays-survey-question-type-short-text-box ays-survey-question-type-all-text-types-box';
                break;
                case 'number':
                    // placeholderVal = SurveyMakerAdmin.numberAnswerText;
                    questionTypeClass = 'ays-survey-question-type-number-box ays-survey-question-type-all-text-types-box';
                    question.find(".ays-survey-question-number-min-box,.ays-survey-question-number-max-box").removeClass("display_none");
                break;
                case 'phone':
                    placeholderVal = SurveyMakerAdmin.phoneAnswerText;
                    questionTypeClass = 'ays-survey-question-type-number-box ays-survey-question-type-all-text-types-box';
                    clonedElement.find(".ays-survey-question-types-box-phone-type-note").removeClass("display_none");
                    question.find(".ays-survey-question-number-min-box,.ays-survey-question-number-max-box").addClass("display_none");
                break;
                case 'email':
                    placeholderVal = SurveyMakerAdmin.emailField;
                    questionTypeClass = 'ays-survey-question-type-email-box ays-survey-question-type-all-text-types-box';
                break;
                case 'name':
                    placeholderVal = SurveyMakerAdmin.nameField;
                    questionTypeClass = 'ays-survey-question-type-name-box ays-survey-question-type-all-text-types-box';
                break;
                default:
                    // placeholderVal = SurveyMakerAdmin.shortAnswerText;
                    placeholderVal = SurveyMakerAdmin.inputAnswerText;
                    questionTypeClass = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
            }

            var enableWordLimitation = question.find('.ays-survey-question-word-limitations-checkbox').prop('checked');
            if( (questionType == "short_text" || questionType == "text") && enableWordLimitation ){
                question.find('.ays-survey-question-word-limitations').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-word-limitations').addClass('display_none');
            }

            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            var enableNumberLimitation = question.find('.ays-survey-question-number-limitations-checkbox').prop('checked');
            if( (questionType == "number" || questionType == "phone") && enableNumberLimitation ){
                question.find('.ays-survey-question-number-limitations').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-number-limitations').addClass('display_none');
            }
            
            if( questionType == "short_text" || questionType == "text" || questionType == "number" || questionType == "phone" || questionType == "email" || questionType == "name" || questionType == "hidden"){
                question.find('.ays-survey-question-more-actions').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-more-actions').addClass('display_none');
            }
            


            var answerId = answers.find('input.ays-survey-question-types-input').length;
            answers.attr('data-id', answerId);
            answers.find('input.ays-survey-question-types-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
            // answers.find('input.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'placeholder'));
            answers.find('input.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'placeholder'));
            answers.find('input.ays-survey-question-types-input').attr('placeholder', placeholderVal);
            answers.find('input.ays-survey-question-types-input').val(placeholderVal);
            answers.find('.ays-survey-question-types-box').addClass(questionTypeClass);

            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');

            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }
        }

        function aysSurveyQuestionType_LinearScale_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_linear_scale');
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            clonedElement.find('.ays-survey-input-linear-scale-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'linear_scale_1'));
            clonedElement.find('.ays-survey-input-linear-scale-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'linear_scale_2'));
            clonedElement.find('.ays-survey-choose-for-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'scale_length'));
            clonedElement.find('.ays-survey-input-star-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_1'));
            clonedElement.find('.ays-survey-input-star-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_2'));
            clonedElement.find('.ays-survey-choose-for-start-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_scale_length'));
            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }
        }
        
        function aysSurveyQuestionType_Date_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_date');
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }

        }

        function aysSurveyQuestionType_Time_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_time');
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }

        }

        function aysSurveyQuestionType_Date_Time_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_date_time');
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }

        }
        
        function aysSurveyQuestionType_Star_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_star');
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            clonedElement.find('.ays-survey-input-linear-scale-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'linear_scale_1'));
            clonedElement.find('.ays-survey-input-linear-scale-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'linear_scale_2'));
            clonedElement.find('.ays-survey-choose-for-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'scale_length'));
            clonedElement.find('.ays-survey-input-star-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_1'));
            clonedElement.find('.ays-survey-input-star-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_2'));
            clonedElement.find('.ays-survey-choose-for-start-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'star_scale_length'));
            
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }
        }
        
        function aysSurveyQuestionType_MatrixTypes_Html( sectionId, questionId, questionDataName, questionType, returnElem = false, sectionElem = null ){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-'+questionType);
            
            var clonedElement = cloningElement.clone( true, false );
            
            var sectionName = section.data('name');
            var questionTypeIconClass = "ays_fa_circle_thin";
            var answers = clonedElement.find('.ays-survey-answer-row');
            var answersRowCont = clonedElement.find('.ays-survey-answers-conteiner-row');
            var answersColCont = clonedElement.find('.ays-survey-answers-conteiner-column');
            
            if( sectionElem  ){
                sectionName = sectionElem.data('name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            answers.each(function(i){
                var answerId = i+1;
                $(this).addClass('ays-survey-new-answer');
                $(this).attr('data-id', answerId);
                $(this).parents(".ays-survey-answers-conteiner-row").find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionDataName, questionId, answerId, 'title'));
                if(questionDataName == "questions"){
                    $(this).parents(".ays-survey-answers-conteiner-column").find('.ays-survey-answer-box input.ays-survey-input').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'columns') + '[uid_1]');

                }else{
                    $(this).parents(".ays-survey-answers-conteiner-column").find('.ays-survey-answer-box input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'columns') + '[uid_1]');
                    
                }
                if($(this).data("answer") == "answer_row"){
                    $(this).find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionDataName, questionId, answerId, 'ordering'));
                }
                $(this).find('.ays-survey-answer-icon i').addClass(questionTypeIconClass);
            });

            if(questionDataName == "questions"){
                clonedElement.find('.ays-survey-choose-for-select-lenght-star-list').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'star_list_stars_length'));
                clonedElement.find('.ays-survey-slider-list-input-range-length').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'slider_list_range_length'));
                clonedElement.find('.ays-survey-slider-list-input-range-step-length').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'slider_list_range_step_length'));
                clonedElement.find('.ays-survey-slider-list-input-min-value').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'slider_list_range_min_value'));
                clonedElement.find('.ays-survey-slider-list-input-default-value').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'slider_list_range_default_value'));
                clonedElement.find('.ays-survey-slider-list-calculation-type').attr('name', newQuestionAttrNameEdit( sectionName, sectionId, questionId, 'options', 'slider_list_range_calculation_type'));
            }else{
                clonedElement.find('.ays-survey-choose-for-select-lenght-star-list').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'star_list_stars_length'));
                clonedElement.find('.ays-survey-slider-list-input-range-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'slider_list_range_length'));
                clonedElement.find('.ays-survey-slider-list-input-range-step-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'slider_list_range_step_length'));
                clonedElement.find('.ays-survey-slider-list-input-min-value').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'slider_list_range_min_value'));
                clonedElement.find('.ays-survey-slider-list-input-default-value').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'slider_list_range_default_value'));
                clonedElement.find('.ays-survey-slider-list-calculation-type').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'slider_list_range_calculation_type'));
            }

            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if(returnElem){
                clonedElement = $( clonedElement.html() );
                var clonedElementArr = new Array(clonedElement, clonedElement_2);
                return clonedElementArr;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
                // switch(questionType){
                //     case 'matrix_scale':
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                //     break;
                //     case 'star_list': 
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html('');
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-slider_list').html('');
                //     break;
                //     case 'slider_list': 
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html('');
                //         section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                //     break;
                // }
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-all-matrix-types:not(.ays-survey-question-'+questionType+')').html('');
            }
            answersRowCont.sortable(answerDragHandle);
            answersColCont.sortable(answerDragHandle);
        }

        function aysSurveyQuestionType_Range_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_range');
            var clonedElement = cloningElement.clone( true, false );

            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            clonedElement.find('.ays-survey-input-range-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'range_length'));
            clonedElement.find('.ays-survey-input-range-step-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'range_step_length'));
            clonedElement.find('.ays-survey-input-range-min-val').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'range_min_value'));
            clonedElement.find('.ays-survey-input-range-default-val').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'range_default_value'));

            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');            
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');                
            }
        }

        function aysSurveyQuestionType_Upload_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_upload');
            var clonedElement = cloningElement.clone( true, false );
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','block');
            section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','flex');

            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').show();
            clonedElement.find('.ays-survey-upload-tpypes-on-off').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'toggle_types'));
            clonedElement.find('.ays-survey-current-upload-type-pdf').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_pdf'));
            clonedElement.find('.ays-survey-current-upload-type-doc').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_doc'));
            clonedElement.find('.ays-survey-current-upload-type-png').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_png'));
            clonedElement.find('.ays-survey-current-upload-type-jpg').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_jpg'));
            clonedElement.find('.ays-survey-current-upload-type-gif').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_gif'));
            clonedElement.find('.ays-survey-question-type-upload-max-size-select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'upload_size'));
            clonedElement.find('[data-toggle="tooltip"]').tooltip();
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');            
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-sldier_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_html').html('');
            }
        }

        function aysSurveyQuestionType_Html_Html(sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types_html');
            var clonedElement = cloningElement.clone( true, false );
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            question.find('.ays-survey-question-action[data-action="enable-user-explanation"]').hide();
            clonedElement.find('.ays-survey-page-message-editor-html-question-type').attr('name', updateQuestionAttrName( sectionName, sectionId, questionDataName, questionId, 'options', 'html_type_editor'));
            clonedElement.find('.ays-survey-page-message-editor-html-question-type').attr('id', "ays_html-type-editor-section-"+sectionId+"-add-"+questionId);
            resetLogicJumpParams( question );
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-other-answer-and-actions-row').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_star').html('');            
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale').html(' ');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-matrix_scale_checkbox').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_linear_scale').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_range').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-types_date_time').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-star_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-sldier_list').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-question-upload').html('');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-answer-elem-box').css('display','none');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box').css('display','none');
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"] .ays-survey-description-box .ays-survey-question-description-input-textarea ays-survey-input').attr('name',' ');
            }

            var wpEditorOprions = {
                tinymce: {
                  wpautop: true,
                  plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                  toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
                  toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                  height : "100px"
                },
                quicktags: {buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'},
                mediaButtons: true,
            }
            wp.editor.initialize("ays_html-type-editor-section-"+sectionId+"-add-"+questionId,  wpEditorOprions);
        }

        function aysSurveyAddAnswer( currentAnswer, afterAnswer, notToMove, isFromTemplate = false ){
            var $this = currentAnswer;
            var section = $this.parents('.ays-survey-section-box');
            var sectionId = section.attr('data-id');
            var questsCont = $this.parents('.ays-survey-question-answer-conteiner');
            var itemId = questsCont.attr('data-id');
            var answersCont = questsCont.find('.ays-survey-answers-conteiner');
            var cloningElement = answersCont.find('.ays-survey-answer-row:first-child');
            var id = cloningElement.attr('data-id');
            var answerLength = answersCont.find('.ays-survey-answer-row').length + 1;

            var clone = cloningElement.clone(true, false).attr('data-id', answerLength);

            if( afterAnswer === true ){
                clone.insertAfter(currentAnswer.parents('.ays-survey-answer-row'));
            }else{
                clone.appendTo(answersCont);
            }

            questsCont = clone.parents('.ays-survey-question-answer-conteiner');
            itemId = questsCont.data('id');
            var answerContainer = questsCont.find('.ays-survey-answers-conteiner');
            var length = answerContainer.find('.ays-survey-answer-row').length;
            var clonedElem = clone.find('.ays-survey-input');
            var clonedElemInp = clonedElem.val('Option '+ length).attr('placeholder', 'Option '+ length);

            clonedElem.attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'title'));
            // clonedElem.find('.aysAnswerImgConteiner').html('');
            // clonedElemInp.select();
            if(notToMove && !isFromTemplate){
                clonedElemInp.trigger('focus');
            }

            clone.addClass('ays-survey-new-answer');
            clone.find('.ays-survey-answer-image-container').hide();
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img').removeAttr('src');
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').val('');
            clone.find('.ays-survey-answer-logic-jump-select').val(-1);
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'image'));
            clone.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'ordering'));
            clone.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'options', 'go_to_section'));

            answersCont.find('.ays-survey-answer-ordering').each(function(i){
                $(this).val( i + 1 );
            });

            // clone.find('.ays-survey-answer-ordering').val( length );
            var deleteButton = answerContainer.find('.ays-survey-answer-delete');
            if(length == 1){
                deleteButton.css('visibility', 'hidden');
            }else{
                deleteButton.removeAttr('style');
            }
            answerContainer.sortable(answerDragHandle);
            reInitPopovers( clone );
        }

        function aysSurveyAddMatrixRowOrColumn( currentAnswer, afterAnswer ){
            
            var $this = currentAnswer;
            var rowOrColumn = $this.data("dir");
            var questinoType = "matrix_scale";
            if($this.parents('.ays-survey-question-matrix_scale_checkbox').length > 0){
                questinoType = "matrix_scale_checkbox";
            }
            
            if( afterAnswer === true ){
                if( $this.parents('.ays-survey-question-matrix_scale_row').length > 0 ){
                    questinoType = "matrix_scale";
                    rowOrColumn = $this.parents('.ays-survey-question-matrix_scale_row').find('.ays-survey-action-add-answer-row-and-column').data("dir");
                }else if( $this.parents('.ays-survey-question-matrix_scale_column').length > 0 ){
                    rowOrColumn = $this.parents('.ays-survey-question-matrix_scale_column').find('.ays-survey-action-add-answer-row-and-column').data("dir");
                }
                
            
                if($this.parents('.ays-survey-question-matrix_scale_checkbox_row').length > 0){
                    questinoType = "matrix_scale_checkbox";
                    rowOrColumn = $this.parents('.ays-survey-question-matrix_scale_checkbox_row').find('.ays-survey-action-add-answer-row-and-column').data("dir");
                }else if( $this.parents('.ays-survey-question-matrix_scale_checkbox_column').length > 0 ){
                    rowOrColumn = $this.parents('.ays-survey-question-matrix_scale_checkbox_column').find('.ays-survey-action-add-answer-row-and-column').data("dir");
                }
                
            }
            if($this.parents('.ays-survey-question-star_list_row').length > 0){
                questinoType = "star_list";
                rowOrColumn = "row";
            }

            if($this.parents('.ays-survey-question-slider_list_row').length > 0){
                questinoType = "slider_list_row";
                rowOrColumn = "row";
            }
            
            var section = $this.parents('.ays-survey-section-box');
            var sectionId = section.attr('data-id');
            var questsCont = $this.parents('.ays-survey-question-answer-conteiner');
            var itemId = questsCont.attr('data-id');
            var answersCont = questsCont.find('.ays-survey-question-'+questinoType);
            var cloningElementRow = "";
            var cloningElementCol = "";
            var answerRowLength = 0;
            var answerColLength = 0;
            var questionDataName = questsCont.data('name');

            if(rowOrColumn == "row"){
                cloningElementRow = answersCont.find(".ays-survey-answers-conteiner-row .ays-survey-answer-row:first-child");
                answerRowLength = answersCont.find('.ays-survey-answers-conteiner-row .ays-survey-answer-row').length + 1;
                var clone = cloningElementRow.clone(true,false).attr('data-id', answerRowLength);

                if( afterAnswer === true ){
                    clone.insertAfter(currentAnswer.parents('.ays-survey-answer-row'));
                }else{
                    clone.appendTo(questsCont.find(".ays-survey-answers-conteiner-row"));
                }

                questsCont = clone.parents('.ays-survey-question-answer-conteiner');
                itemId = questsCont.data('id');
                var answerContainer = questsCont.find('.ays-survey-answers-conteiner-row');
                var length = answerContainer.find('.ays-survey-answer-row').length;
                var clonedElem = clone.find('.ays-survey-input');
                var clonedElemInp = clonedElem.val(SurveyMakerAdmin.row + ' '+ length);
                clonedElem.attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'title'));
                clonedElemInp.select();
                clone.addClass('ays-survey-new-answer');
                clone.find('.ays-survey-answer-image-container').hide();
                clone.find('.ays-survey-answer-image-container .ays-survey-answer-img').removeAttr('src');
                clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').val('');
                clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'image'));
                clone.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'ordering'));
                clone.find('.ays-survey-answer-ordering').val( length );
                
                answerContainer.find('.ays-survey-answer-ordering').each(function(i){
                    $(this).val( i + 1 );
                });

                answerContainer.sortable(answerDragHandle);
                var deleteButtonRow = answerContainer.find('.ays-survey-answer-delete-row');
                if(length == 1){
                    deleteButtonRow.css('visibility', 'hidden');
                }else{
                    deleteButtonRow.removeAttr('style');
                }
                reInitPopovers( clone );
            }else if(rowOrColumn == "col"){
                cloningElementCol = answersCont.find(".ays-survey-answers-conteiner-column .ays-survey-answer-row:first-child");
                answerColLength = answersCont.find('.ays-survey-answers-conteiner-column .ays-survey-answer-row').length + 1;
                var cloneCol = cloningElementCol.clone(true, false).attr('data-id', answerColLength);

                if( afterAnswer === true ){
                    cloneCol.insertAfter(currentAnswer.parents('.ays-survey-answer-row'));
                }else{
                    cloneCol.appendTo(questsCont.find(".ays-survey-answers-conteiner-column"));
                }
                questsCont = cloneCol.parents('.ays-survey-question-answer-conteiner');
                itemId = questsCont.data('id');
                var answerContainerCol = questsCont.find('.ays-survey-answers-conteiner-column');
                var lengthCol = answerContainerCol.find('.ays-survey-answer-row').length;
                var clonedElemCol = cloneCol.find('.ays-survey-input');
                var clonedElemInpCol = clonedElemCol.val('Column '+ lengthCol);
                var detectAddOrEdit = cloneCol.parents(".ays-survey-answers-conteiner-column").data("flag");
                if(questionDataName == "questions"){
                    clonedElemCol.attr('name', newQuestionAttrNameEdit( section.data('name'), sectionId, itemId, 'options', 'columns') + '[uid_'+lengthCol+']');

                }else{
                    clonedElemCol.attr('name', newQuestionAttrName( section.data('name'), sectionId, itemId, 'options', 'columns') + '[uid_'+lengthCol+']');

                }
                clonedElemInpCol.select();
                var deleteButtonCol = answerContainerCol.find('.ays-survey-answer-delete-column');
                answerContainerCol.sortable(answerDragHandle);
                if(lengthCol == 1){
                    deleteButtonCol.css('visibility', 'hidden');
                }else{
                    deleteButtonCol.removeAttr('style');
                }
                reInitPopovers( cloneCol );
            }
        }

        function draggedQuestionUpdate( element, section, oldSection ){

            var questionsLength = section.find('.ays-survey-question-answer-conteiner').length;
            var questionsCount = questionsLength;
            var questionsCountBox = section.find(".ays-survey-action-questions-count span").text(questionsCount);

            if(oldSection){
                var oldQuestionsLength = oldSection.find('.ays-survey-question-answer-conteiner').length;
                var oldQuestionsCount = oldQuestionsLength;
                var questionsCountBox = oldSection.find(".ays-survey-action-questions-count span").text(oldQuestionsCount);
            }

            var answers = element.find('.ays-survey-answers-conteiner .ays-survey-answer-row');
            var questionName = element.attr('data-name');
            var questionId = element.attr('data-id');
            if( element.hasClass('ays-survey-new-question') ){
                questionId = section.find('.ays-survey-question-answer-conteiner[data-name="questions_add"]').length;
            }
            
            var sectionName = section.attr('data-name');
            var sectionId = section.attr('data-id');
            
            var question_type = element.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('get value');
            
            if( question_type == 'matrix_scale' || question_type == 'matrix_scale_checkbox' || question_type == 'star_list' || question_type == 'slider_list'){
                var matrixRows = element.find('.ays-survey-answers-conteiner-row .ays-survey-answer-box input.ays-survey-input');
                var matrixColumns = element.find('.ays-survey-answers-conteiner-column .ays-survey-answer-box input.ays-survey-input');
                matrixRows.each(function(i){
                    var answerId = $(this).parents('.ays-survey-answer-row').attr('data-id');
                    var answerName = $(this).parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ? 'answers_add' : 'answers';
                    $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                    $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                    $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                    $(this).attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'title') );
                    $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-ordering').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'ordering') );
                });
                if(question_type == 'matrix_scale' || question_type == 'matrix_scale_checkbox'){
                    matrixColumns.each(function(i){
                        $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                        $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                        $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                        var thisNameAttr = $(this).attr('name');
                        var gettingColName = thisNameAttr.split('[columns]');
                        var colName = gettingColName[1].slice(1, gettingColName[1].length-1);
                        $(this).attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'columns', colName ) );

                    });
                }
                element.find('.ays-survey-choose-for-select-lenght-star-list').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'star_list_stars_length'));
                element.find('.ays-survey-slider-list-input-range-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'slider_list_range_length'));
                element.find('.ays-survey-slider-list-input-range-step-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'slider_list_range_step_length'));
                element.find('.ays-survey-slider-list-input-min-value').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'slider_list_range_min_value'));
                element.find('.ays-survey-slider-list-input-default-value').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'slider_list_range_default_value'));
                element.find('.ays-survey-slider-list-calculation-type').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'slider_list_range_calculation_type'));
            }else{
                answers.each(function(){
                    var answerId = $(this).attr('data-id');
                    var answerName = $(this).hasClass('ays-survey-new-answer') ? 'answers_add' : 'answers';
                    $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'title'));
                    $(this).find('.ays-survey-answer-img-src').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'image'));
                    $(this).find('.ays-survey-answer-ordering').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'ordering'));
                    $(this).find('.ays-survey-answer-logic-jump-select').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'options', 'go_to_section'));
                    $(this).find('.ays-survey-answer-point-box input.ays-survey-add-answer-point-input').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'point'));
                    $(this).parents('.ays-survey-question-answer-conteiner').find('.ays-survey-other-answer-row .ays-survey-answer-logic-jump-select-other').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'go_to_section'));
                });
            }

            element.find('textarea.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'title'));
            element.find('textarea.ays-survey-description-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'description'));
            element.find('.ays-survey-question-type-box select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'type'));
            element.find('.ays-survey-question-img-src').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'image'));
            element.find('.ays-survey-other-answer-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'user_variant'));
            element.find('.ays-survey-input-required-question').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'required'));
            element.find('.ays-survey-question-collapsed-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'collapsed'));
            element.find('.ays-survey-question-ordering').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'ordering'));
            element.find('.ays-survey-question-max-selection-count-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_max_selection_count'));
            element.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'max_selection_count'));
            element.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            element.find('.ays-survey-question-word-limitations-checkbox').attr('name', updateQuestionAttrName(sectionName, sectionId, questionName, questionId, 'options', 'enable_word_limitation'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_by'));
            element.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_length'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_counter'));
            // Number limitation options
            element.find('.ays-survey-question-number-limitations-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_limitation'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_min_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_max_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_error_message'));            
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_limit_length'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_limit_counter'));

            // Input types placeholders
            element.find('.ays-survey-remove-default-border.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'placeholder'));

            element.find('.ays-survey-question-image-caption').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'image_caption'));
            element.find('.ays-survey-question-img-caption-enable').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'image_caption_enable'));
            
            element.find('.ays-survey-question-is-logic-jump').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'is_logic_jump'));
            element.find('.ays-survey-open-question-editor-flag').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'with_editor'));
            element.find('.ays-survey-question-user-explanation').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'user_explanation'));
            element.find('.ays-survey-question-admin-note-saver').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_admin_note'));
            element.find('.ays-survey-question-admin-note-label input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'admin_note'));
            element.find('.ays-survey-question-url-parameter-saver').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_url_parameter'));
            element.find('.ays-survey-question-hide-results-saver').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_hide_results'));
            element.find('.ays-survey-question-url-parameter-label input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'url_parameter'));

            element.find('.ays-survey-input-linear-scale-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'linear_scale_1'));
            element.find('.ays-survey-input-linear-scale-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'linear_scale_2'));
            element.find('.ays-survey-choose-for-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'scale_length'));
            element.find('.ays-survey-input-star-1').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'star_1'));
            element.find('.ays-survey-input-star-2').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'star_2'));
            element.find('.ays-survey-choose-for-start-select-lenght').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'star_scale_length'));

            if(question_type != 'slider_list'){
                // Range Type
                element.find('.ays-survey-input-range-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_length'));
                element.find('.ays-survey-input-range-step-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_step_length'));            
                element.find('.ays-survey-input-range-step-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_step_length'));            
                element.find('.ays-survey-input-range-step-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_step_length'));            
                element.find('.ays-survey-input-range-min-val').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_min_value'));
                element.find('.ays-survey-input-range-default-val').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'range_default_value'));
            }
            // File upload
            element.find('.ays-survey-upload-tpypes-on-off').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'toggle_types'));
            element.find('.ays-survey-current-upload-type-pdf').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_pdf'));
            element.find('.ays-survey-current-upload-type-doc').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_doc'));
            element.find('.ays-survey-current-upload-type-png').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_png'));
            element.find('.ays-survey-current-upload-type-jpg').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_jpg'));
            element.find('.ays-survey-current-upload-type-gif').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_gif'));
            element.find('.ays-survey-question-type-upload-max-size-select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'upload_size'));

            var conditions = element.find('.ays-survey-answer-checkbox-logic-jump-condition');
            conditions.each(function (i){
                var conditionIndex = i + 1;
                var checkboxCondSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][selected_options][]';
                $(this).find('select.ays-survey-checkbox-condition-select').attr('name', checkboxCondSelectName);
                var goToSectionSelectName = sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][go_to_section]';
                $(this).find('select.ays-survey-answer-logic-jump-select').attr('name', goToSectionSelectName);
            });

            element.find('.ays-survey-answer-other-logic-jump-wrapper .ays-survey-answer-other-logic-jump-else-wrap select.ays-survey-answer-logic-jump-select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'other_logic_jump_otherwise'));

            if(question_type == 'html'){
                var oldTextAreaContent = element.find('.ays-survey-question-types_html .ays-survey-question-types-box-body textarea').html();
                element.find('.ays-survey-question-types_html .ays-survey-question-types-box-body').html('<textarea id="ays_html-type-editor-section-'+sectionId+'-add-'+questionId+'" class="wp-editor-area ays-survey-html-question-type-for-js">'+oldTextAreaContent+'</textarea>');
                
                var wpEditorOprions = {
                    tinymce: {
                    plugins : 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                    toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
                    toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                    height : "100px"
                    },
                    quicktags: {buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'},
                    mediaButtons: true,
                }
                
                wp.editor.initialize('ays_html-type-editor-section-'+sectionId+'-add-'+questionId,  wpEditorOprions);
                
                element.find('.ays-survey-question-types_html .ays-survey-question-types-box-body textarea#ays_html-type-editor-section-'+sectionId+'-add-'+questionId).attr("name" , updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'html_type_editor'));
                element.find('.ays-survey-other-answer-and-actions-row').remove();
            }

            setTimeout(function(){
                element.goToTop();
            }, 100 );
        }

        function aysSurveyDuplicateSection( currentButton, afterSectionId, newSection){
            var sectionCont  = currentButton.parents('.ays-survey-sections-conteiner');
            var sections     = sectionCont.find('.ays-survey-section-box');
            var sectionsAdd  = sectionCont.find('.ays-survey-new-section');
            var sectionNewId = sectionsAdd.length + 1;
    
            var currentElement = currentButton.parents(".ays-survey-section-box");
            var clonedElement = currentElement.clone( true, false );
            clonedElement = aysSetNewSectionNames(clonedElement, sectionNewId);
    
            if( typeof afterSectionId !== 'undefined' && afterSectionId !== null ){
                clonedElement.insertAfter( currentElement );
            }else{
                sectionCont.append( clonedElement );
            }
    
            // Get all questions
            var allQuestions = clonedElement.find('.ays-survey-question-answer-conteiner');
            allQuestions.each(function(i){
                
                var questionType = $(this).find('.ays-survey-question-type').aysDropdown('get value');
                var questionElem = this;
                var questionId = i+1;
                var answers = $(this).find('.ays-survey-answers-conteiner .ays-survey-answer-row');
    
                // Set new names for questions
                aysSetNewQuestionNames($(questionElem), sectionNewId, questionId);
                if( questionType == 'matrix_scale' || questionType == 'matrix_scale_checkbox' || questionType == 'star_list' || questionType == 'slider_list'){
                    var matrixRows = $(this).find('.ays-survey-answers-conteiner-row .ays-survey-answer-box ');
                    var matrixColumns = $(this).find('.ays-survey-answers-conteiner-column .ays-survey-answer-box input.ays-survey-input');
                    var answerId = 0;
                    matrixRows.each(function(j){
                        answerId = j;
                        aysSetNewAnswerNames($(this), sectionNewId, questionId, answerId);
                    });

                    if(questionType == 'matrix_scale' || questionType == 'matrix_scale_checkbox'){
                        matrixColumns.each(function(k){
                            $(this).parents('.ays-survey-answer-row').attr( 'data-id', k+1 );
                            $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                            $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                            var thisNameAttr = $(this).attr('name');
                            
                            var gettingColName = thisNameAttr.split('[columns]');
                            var colName = gettingColName[1].slice(1, gettingColName[1].length-1);
                            $(this).attr('name', newQuestionAttrName( 'ays_section_add', sectionNewId, questionId, 'options', 'columns', colName ) );

                        });
                    }    
                }
                else{                
                    answers.each(function(j){
                        var answerId = j+1;
                        // Set new names for answers
                        aysSetNewAnswerNames($(this), sectionNewId, questionId, answerId);
        
                        var deleteButton = $(this).find('.ays-survey-answer-delete');
                        if( answers.length == 1){
                            deleteButton.css('visibility', 'hidden');
                        }else{
                            deleteButton.removeAttr('style');
                        }
                    });
                }
            });
    
    
            clonedElement.find('.ays-survey-section-questions').sortable(questionDragHandle);
    
            sectionCont  = $(document).find('.ays-survey-sections-conteiner');
            sections     = sectionCont.find('.ays-survey-section-box');
            sections.each(function(i){
                $(this).find('.ays-survey-section-ordering').val( i + 1 );
                $(this).find('.ays-survey-section-number').text( i + 1 );
            });
    
            sectionCont.find('.ays-survey-sections-count').text( sections.length );
    
            sectionCont.find('.ays-survey-section-head-top').removeClass('display_none');
            sectionCont.find('.ays-survey-section-head').addClass('ays-survey-section-head-topleft-border-none');
            setTimeout(function(){
                clonedElement.goTo();
            }, 100 );
            reInitPopovers( clonedElement );
        }
    
        function aysSetNewSectionNames(element, sectionId){
            element.attr('data-id', sectionId);
            element.attr('data-name', 'ays_section_add');
            element.addClass('ays-survey-new-section');
            element.find('.ays-survey-section-title').attr('name', newSectionAttrName( sectionId, 'title' ));
            element.find('.ays-survey-section-description').attr('name', newSectionAttrName( sectionId, 'description' ));
            element.find('.ays-survey-section-ordering').attr('name', newSectionAttrName( sectionId, 'ordering' ));
            element.find('.ays-survey-section-collapsed-input').attr('name', newSectionAttrName( sectionId, 'options', 'collapsed' ));
            element.find('.ays-survey-delete-section').removeClass("display_none");
            element.find('.dropdown-menu.dropdown-menu-right').removeClass("show");
            element.find('input[name*="ays_sections_ids"]').remove();
            return element;
        }
    
        function aysSetNewQuestionNames(element, sectionId, questionId){
            element.removeClass('ays-survey-old-question');
            element.addClass('ays-survey-new-question');
            element.attr('data-id', questionId);
            element.attr('data-name', 'questions_add');
            var questionType = element.find('.ays-survey-check-type-before-change').val();

            var linearScaleOldLength = "";
            if(questionType == 'linear_scale'){
                linearScaleOldLength = element.find(".ays-survey-choose-for-select-lenght").val();
                element.find('select.ays-survey-choose-for-select-lenght').val(linearScaleOldLength);
                element.find('input.ays-survey-input-linear-scale-1').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'linear_scale_1'));
                element.find('input.ays-survey-input-linear-scale-2').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'linear_scale_2'));
                element.find('select.ays-survey-choose-for-select-lenght').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'scale_length'));
            }

            var starScaleOldLength = "";
            if(questionType == 'star'){
                starScaleOldLength = element.find(".ays-survey-choose-for-start-select-lenght").val();
                element.find('select.ays-survey-choose-for-start-select-lenght').val(starScaleOldLength);
                element.find('input.ays-survey-input-star-1').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'star_1'));
                element.find('input.ays-survey-input-star-2').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'star_2'));
                element.find('select.ays-survey-choose-for-start-select-lenght').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'star_scale_length'));
            }

            if(questionType == "radio" || questionType == "yesorno") {
                element.find('.ays-survey-answer-logic-jump-select-other').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'go_to_section' ))
            }
            
            element.find('textarea.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'title'));
            element.find('textarea.ays-survey-description-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'description'));
            element.find('.ays-survey-question-type select').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'type'));
            element.find('.ays-survey-question-img-src').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'image'));
            element.find('.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'user_variant'));
            element.find('.ays-survey-input-required-question').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'required'));
            element.find('.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'collapsed'));
            element.find('.ays-survey-question-ordering').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'ordering'));
            element.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_max_selection_count'));
            element.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'max_selection_count'));
            element.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            element.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_word_limitation'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_by'));
            element.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_length'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_counter'));
            // Number limitation options start
            element.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_number_limitation'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_min_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_max_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_limit_length'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_number_limit_counter'));
            
            element.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'with_editor'));

            // Input types placeholders
            element.find('.ays-survey-question-types-input-with-placeholder').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'placeholder'));

            element.find('.ays-survey-question-image-caption').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'image_caption'));
            element.find('.ays-survey-question-img-caption-enable').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'image_caption_enable'));

            element.find('.ays-survey-question-is-logic-jump').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'is_logic_jump'));
            element.find('.ays-survey-question-user-explanation').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'user_explanation'));
            element.find('.ays-survey-question-admin-note-saver').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_admin_note'));
            element.find('.ays-survey-question-admin-note-label input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'admin_note'));
            element.find('.ays-survey-question-url-parameter-saver').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_url_parameter'));
            element.find('.ays-survey-question-hide-results-saver').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_hide_results'));
            element.find('.ays-survey-question-url-parameter-label input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'url_parameter'));

            element.find('select.ays-survey-choose-for-select-lenght-star-list').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'star_list_stars_length'));    
            element.find('.ays-survey-slider-list-input-range-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'slider_list_range_length'));
            element.find('.ays-survey-slider-list-input-range-step-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'slider_list_range_step_length'));
            element.find('.ays-survey-slider-list-input-min-value').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'slider_list_range_min_value'));
            element.find('.ays-survey-slider-list-input-default-value').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'slider_list_range_default_value'));
            element.find('.ays-survey-slider-list-calculation-type').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'slider_list_range_calculation_type'));
            element.find('.ays-survey-question-ordering').val(questionId);

            var conditions = element.find('.ays-survey-answer-checkbox-logic-jump-condition');
            conditions.each(function (i){
                var conditionIndex = i + 1;
                var checkboxCondSelectName = 'ays_section_add['+ sectionId +'][questions_add]['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][selected_options][]';
                $(this).find('select.ays-survey-checkbox-condition-select').attr('name', checkboxCondSelectName);
                var goToSectionSelectName = 'ays_section_add['+ sectionId +'][questions_add]['+ questionId +'][options][other_logic_jump]['+ conditionIndex +'][go_to_section]';
                $(this).find('select.ays-survey-answer-logic-jump-select').attr('name', goToSectionSelectName);
            });

            element.find('.ays-survey-answer-other-logic-jump-wrapper .ays-survey-answer-other-logic-jump-else-wrap select.ays-survey-answer-logic-jump-select').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'other_logic_jump_otherwise'));

            
            element.find('.ays-survey-question-type').aysDropdown('set selected', questionType);
            element.find('.ays-survey-question-type').aysDropdown('set value', questionType);
            element.find('.ays-survey-question-type').aysDropdown();
        }
    
        function aysSetNewAnswerNames(element, sectionId, questionId, answerId){
            element.addClass('ays-survey-new-answer');
            element.attr('data-id', answerId);
            element.find('input.ays-survey-input').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'title'));
            element.find('input.ays-survey-description-input').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'description'));
            element.find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'image'));

        }

        function newSectionAttrName(sectionId, field, field2 = null){
            if(field2 !== null){
                return $html_name_prefix + 'section_add['+ sectionId +']['+ field +']['+ field2 +']';
            }
            return $html_name_prefix + 'section_add['+ sectionId +']['+ field +']';
        }

        function newQuestionAttrName(sectionName, sectionId, questionId, field, field2 = null, field3 = null){
            if(field2 !== null){
                if( field3 !== null ){
                    return sectionName + '['+ sectionId +'][questions_add]['+ questionId +']['+ field +']['+ field2 +']['+ field3 +']';
                }
                return sectionName + '['+ sectionId +'][questions_add]['+ questionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +'][questions_add]['+ questionId +']['+ field +']';
        }

        function newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, field, field2 = null ){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][answers_add]['+ answerId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][answers_add]['+ answerId +']['+ field +']';
        }
        
        function updateSectionAttrName( sectionName, sectionId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ field +']';
        }

        function updateQuestionAttrName( sectionName, sectionId, questionName, questionId, field, field2 = null, field3 = null){
            if(field2 !== null){
                if( field3 !== null ){
                    return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ field +']['+ field2 +']['+ field3 +']';
                }
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ field +']';
        }

        function updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ answerName +']['+ answerId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ answerName +']['+ answerId +']['+ field +']';
        }

        function newQuestionAttrNameEdit(sectionName, sectionId, questionId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +'][questions]['+ questionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +'][questions]['+ questionId +']['+ field +']';
        }

        function reInitPopovers( parentElement ){
            parentElement.find('[data-toggle="popover"]').each(function(){
                $(this).popover();
            });
        }

        function resetLogicJumpParams( question ){
            var enableCheckbox = question.find('.ays-survey-question-is-logic-jump');
            // question.find('.ays-survey-question-more-actions').addClass('display_none');
            question.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').attr('data-action', 'go-to-section-based-on-answers-enable');
            question.find('.ays-survey-answer-logic-jump-wrap').addClass('display_none');
            enableCheckbox.val('off');
            question.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').find('.ays-survey-question-action-icon').addClass('display_none');
        }

        $(document).find('#ays_select_surveys').select2({
            allowClear: true,
            placeholder: false
        });
        
        $(document).find('#ays_user_roles').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectUserRoles
        });
        
        $(document).find('#ays_user_roles_to_change_survey').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectUserRoles
        });
        
        $(document).find('#ays_survey_default_type').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectQuestionDefaultType
        });

        $(document).find('.ays-survey-submission-select').aysDropdown();

        $(document).find('.ays-survey-sections-conteiner .ays-survey-question-type').aysDropdown({
            // allowClear: true,
            // placeholder: false
        });

        $(document).find('.ays-survey-sections-conteiner .dropdown .item[data-value="email"]').before('<div class="divider"></div>');

        // Questions library
        $(document).on('click', '.ays-survey-insert-question-into-section', function(e) {
            
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sectionsPopup = $(document).find('#ays-survey-insert-into-section');
            var popup = $(document).find('#ays-questions-modal');

            popup.find('div.ays-survey-preloader').css('display', 'flex');

            sectionsPopup.aysModal('hide');
            
            var sectionId = $(this).attr('data-id');
            var sectionDataName = $(this).attr('data-name');
            var section = sectionCont.find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionDataName+'"]');

            if(window.aysQuestNewSelected.length > 0){
                $(document).find('td.empty_quiz_td').parent().remove();
                var data = {};
                data.action = 'ays_survey_admin_ajax';
                data.function = 'ays_add_questions_from_library';
                data['ays_questions_ids[]'] = window.aysQuestNewSelected;
                
                $.ajax({
                    url: SurveyMakerAdmin.ajaxUrl,
                    method: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(response){
                        if( response.status === true ) {
                            $.each( response.questions, function(){
                                var quest = this;
                                var newQuestion = aysSurveyAddQuestion( sectionId, true, section, true );
                                
                                var questionsLength = section.find('.ays-survey-question-answer-conteiner').length;
                                var questionId = questionsLength + 1;

                                var questionName = newQuestion.attr('data-name');
                                var sectionName = section.attr('data-name');

                                section.find('.ays-survey-section-questions').append(newQuestion);
                                
                                newQuestion = section.find('.ays-survey-question-answer-conteiner.ays-survey-new-question[data-id="'+questionId+'"]');

                                newQuestion.find('.ays-survey-question-type').aysDropdown('set selected', quest.type );
                                newQuestion.find('.ays-survey-question-type').aysDropdown('set value', quest.type );
                                newQuestion.find('.ays-survey-check-type-before-change').val( quest.type );
                                
                                // Answers ordering jQuery UI
                                newQuestion.find('.ays-survey-answers-conteiner').sortable(answerDragHandle);

                                var aysSurveyTextArea = newQuestion.find('textarea.ays-survey-question-input-textarea');
                                setTimeout( function(){
                                    autosize(aysSurveyTextArea);
                                }, 100 );

                                var aysSurveyQuestionDescriptionTextArea = newQuestion.find('textarea.ays-survey-question-description-input-textarea');
                                setTimeout( function(){
                                    autosize(aysSurveyQuestionDescriptionTextArea);
                                }, 100 );

                                newQuestion.addClass('ays-survey-new-question');
                                newQuestion.attr('data-id', questionId);
                                newQuestion.find('textarea.ays-survey-input').val( quest.question );
                                newQuestion.find('textarea.ays-survey-description-input').val( quest.description );
                                if( quest.image != '' ){
                                    newQuestion.find('.ays-survey-question-img-src').val( quest.image );
                                    newQuestion.find('.ays-survey-question-img').attr( 'src', quest.image );
                                    newQuestion.find('.ays-survey-question-image-container').show();
                                }

                                newQuestion.find('.ays-survey-input-required-question').prop('checked', quest.options.required == 'on' ? true : false );
                                // newQuestion.find('.ays-survey-question-collapsed-input').attr('checked', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'collapsed'));
                                if( quest.type == 'checkbox' ){
                                    var enableMaxSelectionCount = quest.options.enable_max_selection_count && quest.options.enable_max_selection_count == 'on' ? true : false;
                                    if( enableMaxSelectionCount ){
                                        newQuestion.find('.ays-survey-question-max-selection-count-checkbox').prop('checked', enableMaxSelectionCount );
                                        newQuestion.find('.ays-survey-question-max-selection-count input.ays-survey-input').val( quest.options.max_selection_count ? quest.options.max_selection_count : '' );
                                        newQuestion.find('.ays-survey-question-min-selection-count input.ays-survey-input').val( quest.options.min_selection_count ? quest.options.min_selection_count : '' );
                                        newQuestion.find('.ays-survey-question-action[data-action^="max-selection-count"]').attr('data-action', 'max-selection-count-disable');
                                        newQuestion.find('.ays-survey-question-more-option-wrap').removeClass('display_none');
                                    }
                                }

                                var enableUserExplanation = quest.options.user_explanation && quest.options.user_explanation == 'on' ? true : false;
                                if( enableUserExplanation ){
                                    var enableCheckbox = newQuestion.find('.ays-survey-question-user-explanation');
                                    newQuestion.find('.ays-survey-question-user-explanation-wrap').removeClass('display_none');
                                    enableCheckbox.val('on');
                                    newQuestion.find('.ays-survey-question-action[data-action*="user-explanation"]').attr('data-action', 'disable-user-explanation');
                                    newQuestion.find('.ays-survey-question-action[data-action*="user-explanation"] .ays-survey-question-action-icon').removeClass('display_none');
                                }

            
                                var enableAdminNote = quest.options.enable_admin_note && quest.options.enable_admin_note == 'on' ? true : false;
                                if( enableAdminNote ){
                                    var enableCheckbox = newQuestion.find('.ays-survey-question-admin-note-saver');
                                    newQuestion.find('.ays-survey-question-admin-note').removeClass('display_none');
                                    enableCheckbox.val('on');
                                    newQuestion.find('.ays-survey-question-action[data-action*="admin-note"]').attr('data-action', 'disable-admin-note');
                                    newQuestion.find('.ays-survey-question-action[data-action*="admin-note"] .ays-survey-question-action-icon').removeClass('display_none');
                                    newQuestion.find('.ays-survey-question-admin-note input.ays-survey-input').val( quest.options.admin_note ? quest.options.admin_note : '' );
                                }

                                var enableUrlParameter = quest.options.enable_url_parameter && quest.options.enable_url_parameter == 'on' ? true : false;
                                if( enableUrlParameter ){ 
                                    var enableCheckbox = newQuestion.find('.ays-survey-question-url-parameter-saver');
                                    newQuestion.find('.ays-survey-question-url-parameter').removeClass('display_none');
                                    enableCheckbox.val('on');
                                    newQuestion.find('.ays-survey-question-action[data-action*="url-parameter"]').attr('data-action', 'disable-url-parameter');
                                    newQuestion.find('.ays-survey-question-action[data-action*="url-parameter"] .ays-survey-question-action-icon').removeClass('display_none');
                                    newQuestion.find('.ays-survey-question-url-parameter input.ays-survey-input').val( quest.options.url_parameter ? quest.options.url_parameter : '' );
                                }

                                var enableHideResults = quest.options.enable_hide_results && quest.options.enable_hide_results == 'on' ? true : false;
                                if( enableHideResults ){ 
                                    var enableCheckbox = newQuestion.find('.ays-survey-question-hide-results-saver');
                                    enableCheckbox.val('on');
                                    newQuestion.find('.ays-survey-question-action[data-action*="hide-results"]').attr('data-action', 'disable-hide-results');
                                    newQuestion.find('.ays-survey-question-action[data-action*="hide-results"] .ays-survey-question-action-icon').removeClass('display_none');
                                }

                                // with_editor
                                var withEditor = quest.options.with_editor && quest.options.with_editor == 'on' ? true : false;
                                if( withEditor ){
                                    newQuestion.find('.ays-survey-open-question-editor-flag').val('on');

                                    newQuestion.find('.ays-survey-question-preview-box').html( quest.question );
                                    newQuestion.find('.ays-survey-question-input-box').addClass('display_none');
                                    newQuestion.find('.ays-survey-question-preview-box').removeClass('display_none');
                                }

                                newQuestion.find('.ays-survey-question-ordering').val(questionId);

                                if( quest.type == 'radio' || quest.type == 'checkbox' || quest.type == 'select' || quest.type == 'yesorno' ){
                                    var otherAnswer = quest.user_variant == 'on' ? true : false;
                                    if( otherAnswer ){
                                        newQuestion.find('.ays-survey-other-answer-checkbox').prop('checked', otherAnswer );
                                        newQuestion.find('.ays-survey-other-answer-row').show();
                                        newQuestion.find('.ays-survey-other-answer-add-wrap').hide();
                                    }
                                    
                                    var answersCont = newQuestion.find('.ays-survey-answers-conteiner');
                                    
                                    var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner.ays-survey-new-question .ays-survey-answer-row:first-child:not(.ays-survey-other-answer-row)');
                                    var clone = cloningElement.clone(true, false);
                                    
                                    if( quest.answers.length > 0 ){
                                        answersCont.html('');
                                    }

                                    $.each(quest.answers, function(j){
                                        var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                                        var answ = this;
                                        var clonedElem = clone.clone(true, false);
                                        clonedElem.addClass('ays-survey-new-answer');
                                        clonedElem.attr('data-id', answerId);

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                                        clonedElem.find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'image'));
                                        clonedElem.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));
                                        clonedElem.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));
                                        clonedElem.find('.ays-survey-answer-logic-jump-select-other').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'go_to_section'));

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').val( answ.answer );
                                        if( answ.image != '' ){
                                            clonedElem.find('.ays-survey-answer-img-src').val( answ.image );
                                            clonedElem.find('.ays-survey-answer-img').attr( 'src', answ.image );
                                            clonedElem.find('.ays-survey-answer-image-container').show();
                                        }
                                        clonedElem.find('.ays-survey-answer-ordering').val( answerId );
                                        // clonedElem.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));
                                        var deleteButton = clonedElem.find('.ays-survey-answer-delete');
                                        deleteButton.removeAttr('style');
                                        answersCont.append( clonedElem );
                                    });

                                    var answer_icon_tags = newQuestion.find('.ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
                                    switch( quest.type ){
                                        case 'radio':
                                        case 'yesorno':
                                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                                        break;
                                        case 'checkbox':
                                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.checkboxUnchecked);
                                        break;
                                        case 'select':
                                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                                        break;
                                        default:
                                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                                    }
                                }else if( quest.type == 'linear_scale' ){
                                    newQuestion.find('.ays-survey-input-linear-scale-1').val( quest.options.linear_scale_1 ? quest.options.linear_scale_1 : '' );
                                    newQuestion.find('.ays-survey-input-linear-scale-2').val( quest.options.linear_scale_2 ? quest.options.linear_scale_2 : '' );
                                    newQuestion.find('.ays-survey-choose-for-select-lenght').val( quest.options.scale_length ? quest.options.scale_length : '' );
                                }else if( quest.type == 'star' ){
                                    newQuestion.find('.ays-survey-input-star-1').val( quest.options.star_1 ? quest.options.star_1 : '' );
                                    newQuestion.find('.ays-survey-input-star-2').val( quest.options.star_2 ? quest.options.star_2 : '' );
                                    newQuestion.find('.ays-survey-choose-for-start-select-lenght').val( quest.options.star_scale_length ? quest.options.star_scale_length : '' );
                                }else if( quest.type == 'matrix_scale' || quest.type == 'matrix_scale_checkbox'){
                                    var answersContRows = newQuestion.find('.ays-survey-answers-conteiner-row');
                                    var answersContCols = newQuestion.find('.ays-survey-answers-conteiner-column');
                                    
                                    var cloningElementRow = $(document).find('.ays-question-to-clone .ays-survey-question-'+quest.type+' .ays-survey-answers-conteiner-matrix-row .ays-survey-answer-row:first-child');
                                    var cloningElementCol = $(document).find('.ays-question-to-clone .ays-survey-question-'+quest.type+' .ays-survey-answers-conteiner-column .ays-survey-answer-row:first-child');
                                    var cloneRow = cloningElementRow.clone(true, false);
                                    var cloneCol = cloningElementCol.clone(true, false);
                                    
                                    if( quest.answers.length > 0 ){
                                        answersContRows.html('');
                                    }

                                    $.each(quest.answers, function(j){
                                        var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                                        var answ = this;
                                        var clonedElem = cloneRow.clone(true, false);
                                        clonedElem.addClass('ays-survey-new-answer');
                                        clonedElem.attr('data-id', answerId);

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                                        clonedElem.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').val( answ.answer );
                                        clonedElem.find('.ays-survey-answer-ordering').val( answerId );

                                        // clonedElem.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));
                                        var deleteButton = clonedElem.find('.ays-survey-answer-delete');
                                        deleteButton.removeAttr('style');
                                        answersContRows.append( clonedElem );
                                    });
                                    
                                    if( typeof quest.options.matrix_columns != 'undefined' ){
                                        if( quest.options.matrix_columns.length > 0 ){
                                            answersContCols.html('');
                                        }
                                        var key = 1;
                                        $.each(quest.options.matrix_columns, function(j){
                                            var answerId = key; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                                            var answ = this;
                                            var clonedElem = cloneCol.clone(true, false);
                                            clonedElem.addClass('ays-survey-new-answer');
                                            clonedElem.attr('data-id', answerId);
                                            
                                            clonedElem.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'columns') + '['+ j +']');

                                            clonedElem.find('.ays-survey-answer-box input.ays-survey-input').val( answ );

                                            var deleteButton = clonedElem.find('.ays-survey-answer-delete');
                                            deleteButton.removeAttr('style');
                                            answersContCols.append( clonedElem );
                                            key++;
                                        });
                                    }
                                }else if( quest.type == 'star_list' ){
                                    var answersContRows = newQuestion.find('.ays-survey-answers-conteiner-row');
                                    
                                    var cloningElementRow = $(document).find('.ays-question-to-clone .ays-survey-answers-conteiner-star-list-row .ays-survey-answer-row:first-child');
                                    var cloneRow = cloningElementRow.clone(true, false);
                                    
                                    if( quest.answers.length > 0 ){
                                        answersContRows.html('');
                                    }

                                    if( quest.answers.length > 0 ){
                                        answersContRows.html('');
                                    }

                                    $.each(quest.answers, function(j){
                                        var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                                        var answ = this;
                                        var clonedElem = cloneRow.clone(true, false);
                                        clonedElem.addClass('ays-survey-new-answer');
                                        clonedElem.attr('data-id', answerId);

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                                        clonedElem.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').val( answ.answer );
                                        clonedElem.find('.ays-survey-answer-ordering').val( answerId );

                                        clonedElem.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));
                                        var deleteButton = clonedElem.find('.ays-survey-answer-delete');
                                        deleteButton.removeAttr('style');
                                        answersContRows.append( clonedElem );
                                    });

                                    newQuestion.find('.ays-survey-choose-for-select-lenght-star-list').val( quest.options.star_list_stars_length ? quest.options.star_list_stars_length : '' );
                                }
                                else if( quest.type == 'slider_list' ){
                                    var answersContRows = newQuestion.find('.ays-survey-answers-conteiner-row');
                                    
                                    var cloningElementRow = $(document).find('.ays-question-to-clone .ays-survey-answers-conteiner-slider-list-row .ays-survey-answer-row:first-child');
                                    var cloneRow = cloningElementRow.clone(true, false);
                                    
                                    if( quest.answers.length > 0 ){
                                        answersContRows.html('');
                                    }

                                    if( quest.answers.length > 0 ){
                                        answersContRows.html('');
                                    }

                                    $.each(quest.answers, function(j){
                                        var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                                        var answ = this;
                                        var clonedElem = cloneRow.clone(true, false);
                                        clonedElem.addClass('ays-survey-new-answer');
                                        clonedElem.attr('data-id', answerId);

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                                        clonedElem.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));

                                        clonedElem.find('.ays-survey-answer-box input.ays-survey-input').val( answ.answer );
                                        clonedElem.find('.ays-survey-answer-ordering').val( answerId );

                                        clonedElem.find('.ays-survey-answer-logic-jump-select').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'options', 'go_to_section'));
                                        var deleteButton = clonedElem.find('.ays-survey-answer-delete');
                                        deleteButton.removeAttr('style');
                                        answersContRows.append( clonedElem );
                                    });

                                    newQuestion.find('.ays-survey-slider-list-input-range-length').val( quest.options.slider_list_range_length ? quest.options.slider_list_range_length : '' );
                                    newQuestion.find('.ays-survey-slider-list-input-range-step-length').val( quest.options.slider_list_range_step_length ? quest.options.slider_list_range_step_length : '' );
                                    newQuestion.find('.ays-survey-slider-list-input-min-value').val( quest.options.slider_list_range_min_value ? quest.options.slider_list_range_min_value : '' );
                                    newQuestion.find('.ays-survey-slider-list-input-default-value').val( quest.options.slider_list_range_default_value ? quest.options.slider_list_range_default_value : '' );
                                    newQuestion.find('.ays-survey-slider-list-calculation-type').val( quest.options.slider_list_range_calculation_type ? quest.options.slider_list_range_calculation_type : '' );
                                }
                                else if( quest.type == 'range' ){
                                    newQuestion.find('.ays-survey-input-range-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'range_length'));
                                    newQuestion.find('.ays-survey-input-range-step-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'range_step_length'));
                                    newQuestion.find('.ays-survey-input-range-min-val').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'range_min_value'));
                                    newQuestion.find('.ays-survey-input-range-default-val').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'range_default_value'));

                                    newQuestion.find('.ays-survey-input-range-length').val( quest.options.range_length ? quest.options.range_length : '' );
                                    newQuestion.find('.ays-survey-input-range-step-length').val( quest.options.range_step_length ? quest.options.range_step_length : '' );
                                    newQuestion.find('.ays-survey-input-range-min-val').val( quest.options.range_min_value ? quest.options.range_min_value : '' );
                                    newQuestion.find('.ays-survey-input-range-default-val').val( quest.options.range_default_value ? quest.options.range_default_value : '' );
                                }else if( quest.type == 'upload' ){
                                    newQuestion.find('.ays-survey-upload-tpypes-on-off').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'toggle_types'));
                                    newQuestion.find('.ays-survey-current-upload-type-pdf').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_pdf'));
                                    newQuestion.find('.ays-survey-current-upload-type-doc').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_doc'));
                                    newQuestion.find('.ays-survey-current-upload-type-png').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_png'));
                                    newQuestion.find('.ays-survey-current-upload-type-jpg').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_jpg'));
                                    newQuestion.find('.ays-survey-current-upload-type-gif').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_gif'));
                                    newQuestion.find('.ays-survey-question-type-upload-max-size-select').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'upload_size'));
                                    
                                    var uploadToggleChecked = quest.options.file_upload_toggle == "on" ? true : false;
                                    var uploadPdfChecked    = quest.options.file_upload_types_pdf == "on" ? true : false;
                                    var uploadDocChecked    = quest.options.file_upload_types_doc == "on" ? true : false;
                                    var uploadPngChecked    = quest.options.file_upload_types_png == "on" ? true : false;
                                    var uploadJpgChecked    = quest.options.file_upload_types_jpg == "on" ? true : false;
                                    var uploadGifChecked    = quest.options.file_upload_types_gif == "on" ? true : false;
                                    var uploadSize          = quest.options.file_upload_types_size && quest.options.file_upload_types_size != "" ? quest.options.file_upload_types_size : "5";
                                    if(uploadToggleChecked){
                                        newQuestion.find(".ays-survey-question-type-upload-allowed-types").show();
                                    }
                                    newQuestion.find('.ays-survey-upload-tpypes-on-off').attr("checked", uploadToggleChecked);
                                    newQuestion.find('.ays-survey-current-upload-type-pdf').attr("checked", uploadPdfChecked);
                                    newQuestion.find('.ays-survey-current-upload-type-doc').attr("checked", uploadDocChecked);
                                    newQuestion.find('.ays-survey-current-upload-type-png').attr("checked", uploadPngChecked);
                                    newQuestion.find('.ays-survey-current-upload-type-jpg').attr("checked", uploadJpgChecked);
                                    newQuestion.find('.ays-survey-current-upload-type-gif').attr("checked", uploadGifChecked);
                                    newQuestion.find('.ays-survey-question-type-upload-max-size-select').val( uploadSize );
                                }


                                // var enableLogicJump = quest.options.is_logic_jump && quest.options.is_logic_jump == 'on' ? true : false;
                                // if( enableLogicJump ){
                                //     var enableCheckbox = newQuestion.find('.ays-survey-question-is-logic-jump');
                                //     enableCheckbox.val('on');
                                    
                                //     newQuestion.find('.ays-survey-answer-logic-jump-wrap').each(function(){
                                //         $(this).removeClass('display_none');
                                //     });
                                //     newQuestion.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').find('.ays-survey-question-action-icon').removeClass('display_none');
                                //     newQuestion.find('.ays-survey-question-action[data-action^="go-to-section-based-on-answers"]').attr('data-action', 'go-to-section-based-on-answers-disable');
                                // }
                                
                                setTimeout(function(){
                                    newQuestion.goToTop();
                                    updateLogicJumpSelects();
                                }, 100 );
                        
                                reInitPopovers( newQuestion );
                                aysSurveySectionsInitQuestionsCollapse();
                            });
                        }

                        popup.find('div.ays-survey-preloader').css('display', 'none');
                        popup.aysModal('hide');

                        // var table_rows = $('table#ays-questions-table tbody tr');
                        // $(document).find('.questions_count_number').html(table_rows.length);

                        // $(document).find('.ays_refresh_qbank_categories').removeClass('display_none');
                        window.aysQuestNewSelected = [];
                    }
                });
            }else{
                alert('You must select new questions to add to the survey.');
                $(document).find('#ays-question-table-add div.ays-quiz-preloader').css('display', 'none');
            }
            e.preventDefault();
        } );


        // ===============================================================
        // ======================      Ani      ==========================
        // ===============================================================

        var count = 0;
        $(document).find('.ays_survey_previous_next').on('click',function(){
            if($(this).attr('data-name') == 'ays_survey_next'){
                if($(document).find('.ays_number_of_result').val() == $(document).find('.ays_number_of_result').attr('max')){
                    count = $(document).find('.ays_number_of_result').attr('min');
                    $(document).find('.ays_number_of_result').val(count);
                }else{
                    var selectVal = parseInt($(document).find('.ays_number_of_result').val());
                    count = selectVal + 1;
                    $(document).find('.ays_number_of_result').val(count);
                }
            }else if($(this).attr('data-name') == 'ays_survey_previous'){
                if($(document).find('.ays_number_of_result').val() == $(document).find('.ays_number_of_result').attr('min')){
                    count = $(document).find('.ays_number_of_result').attr('max');
                    $(document).find('.ays_number_of_result').val(count);
                }else{
                    var selectVal = parseInt($(document).find('.ays_number_of_result').val());
                    count = selectVal - 1;
                    $(document).find('.ays_number_of_result').val(count);
                }
            }
            $(document).find('.ays_number_of_result').trigger('change');
        });
        
        $(document).find('.ays_number_of_result').on('change', refreshSubmissionData);
        // $(document).find('.ays_number_of_result').on('input', refreshSubmissionData);

        function refreshSubmissionData(){
            var submissionPrevVal;
            var $this  = $(this);

            var parent = $(this).parents('.ays_survey_previous_next_conteiner');

            var mainParent = $this.parents('.ays_survey_container_each_result');
            var submissionInputs = mainParent.find(".ays_number_of_result ");
            var currentNumber = $this.val();
            if(+$this.val() > +$this.attr("max") || $this.val() == "" || +$this.val() == 0){
                currentNumber = $this.attr("max");
            }
            submissionInputs.val(currentNumber);

            var submissionIdStr = parent.find('.ays_submissions_id_str').val();
            var submissionIdArr = submissionIdStr.split(",");
            var submissionElVal = parseInt(parent.find('.ays_number_of_result').val());
            var surveyId = $(document).find('.ays_number_of_result').attr('data-id');
            var submissionId = '';
            var data = {};
            
            if(submissionElVal < 0){
                submissionElVal = 1;
                parseInt(parent.find('.ays_number_of_result').val(1));
            }else if(submissionElVal > parseInt(parent.find('.ays_number_of_result').attr('max'))){
                var maxVal = parseInt(parent.find('.ays_number_of_result').attr('max'));
                parseInt(parent.find('.ays_number_of_result').val(maxVal));
                submissionElVal = maxVal;
            }
            
            if(submissionElVal>submissionPrevVal || submissionElVal+1){
                submissionId = submissionIdArr[submissionElVal-1];
            }else{
                submissionId = submissionIdArr[submissionElVal+1];
            }

            // $(document).find('.ays-survey-single-submission-results-export, .ays-survey-single-submission-pdf-export').attr( 'data-result' , submissionId );
            $(document).find('.ays-survey-single-submission-results-export, .ays-survey-single-submission-results-csv-export, .ays-survey-single-submission-pdf-export').attr( 'data-result' , submissionId );

            data.action = 'ays_survey_admin_ajax';
            data.function = 'ays_survey_submission_report';
            data.submissionId = submissionId;
            data.surveyId = surveyId;
            data.nonce = SurveyMakerAdmin.nonce;
            var preloader = $(this).parents('.ays_survey_container_each_result').find('.question_result_container').find('div.ays_survey_preloader');
            preloader.css({'display':'flex'});
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: data,
                method: 'post',
                success: function(response){
                    if (response.status) {
                        preloader.css({'display':'none'});
                        var questionsData = response.questions;
                        var questionsInHTML = $(document).find('.ays_questions_answers');

                        $(document).find(".ays_survey_each_sub_user_info_name").html(response.user_info.user_name);
                        $(document).find(".ays_survey_each_sub_user_info_email").text(response.user_info.user_email);
                        $(document).find(".ays_survey_each_sub_user_info_user_ip").html(response.user_info.user_ip);
                        $(document).find(".ays_survey_each_sub_user_info_sub_date").html(response.user_info.submission_date);
                        $(document).find(".ays_survey_each_sub_user_info_sub_id").html(response.user_info.id);
                        if(response.user_info.password){
                            $(document).find(".ays_survey_each_sub_user_info_password").parents(".ays_survey_each_sub_user_info_columns").removeClass('display_none')
                            $(document).find(".ays_survey_each_sub_user_info_password").html(response.user_info.password);
                        }
                        else{
                            $(document).find(".ays_survey_each_sub_user_info_password").parents(".ays_survey_each_sub_user_info_columns").addClass('display_none');
                        }
                        $(document).find(".ays_survey_each_sub_user_info_header_button button").attr("data-clipboard-text", response.user_info_for_copy);

                        $.each(questionsInHTML, function(){
                            var question = $(this);
                            var qId = question.data('id');
                            var qType = question.data('type');
                            
                            question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                            question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                            question.find('.ays_each_question_answer .ays-survey-submission-select option').removeAttr('selected');
                            question.find('.ays_text_answer').html('');
                            question.find('.ays_each_question_answer input[type="range"]').val(0);

                            if( typeof questionsData[qId] != 'undefined'){
                                var surveyAnswer = questionsData[qId];
                                question.find('div.ays-survey-individual-user-explanation-text span').text( surveyAnswer.user_explanation );
                                switch( qType ){
                                    case 'radio':
                                    case 'yesorno':
                                        surveyAnswer = questionsData[qId].answer;
                                        var surveyOtherAnswer = questionsData[qId].otherAnswer;
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="radio"]').each(function(){
                                            var thisParent = $(this).parents(".ays_each_question_answer");
                                            if( surveyAnswer == $(this).data('id') ){
                                                if(thisParent.hasClass("ays-survey-answer-label-other") && surveyOtherAnswer != ''){
                                                    $(this).prop('checked', true);
                                                }else if( !thisParent.hasClass("ays-survey-answer-label-other") ){
                                                    $(this).prop('checked', true);
                                                }
                                            }
                                        });
                                        question.find('.ays-survey-answer-other-input').val(surveyOtherAnswer);
                                        break;
                                    case 'checkbox':
                                        surveyAnswer = questionsData[qId].answer;
                                        var surveyOtherAnswer = questionsData[qId].otherAnswer;
                                        question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="checkbox"]').each(function(){
                                            if( Array.isArray(surveyAnswer) ){
                                                for(var i=0; i < surveyAnswer.length; i++){
                                                    if( surveyAnswer[i] == $(this).data('id') ){
                                                        $(this).prop('checked', true);
                                                    }
                                                }
                                            }else{
                                                if( surveyAnswer == $(this).data('id') ){
                                                    $(this).prop('checked', true);
                                                }
                                            }
                                        });
                                        question.find('.ays-survey-answer-other-input').val(surveyOtherAnswer);
                                        break;
                                    case 'select':
                                        if( parseInt( surveyAnswer.answer ) == 0 ){
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('clear');
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', '');
                                        }else{
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set selected', surveyAnswer.answer);
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', surveyAnswer.answer);
                                        }
                                        break;
                                    case 'linear_scale':
                                        surveyAnswer = questionsData[qId];
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="radio"]').each(function(){
                                            if( parseInt( surveyAnswer.answer ) == $(this).data('id') ){
                                                $(this).prop('checked', true);
                                            }
                                        });
                                        break;
                                    case 'star':
                                        surveyAnswer = questionsData[qId];
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays-survey-star-icon').removeClass('ays_fa_star').addClass('ays_fa_star_o');
                                        question.find('.ays-survey-star-icon').css('color', 'rgb(51, 51, 51)');
                                        question.find('.ays_each_question_answer input[type="radio"]').each(function(){
                                            if( parseInt( surveyAnswer.answer ) >= $(this).data('id') ){
                                                $(this).prop('checked', true);
                                                $(this).parents('.ays-survey-answer-star-radio').find('.ays-survey-star-icon').removeClass('ays_fa_star_o').addClass('ays_fa_star');
                                                $(this).parents('.ays-survey-answer-star-radio').find('.ays-survey-star-icon').css('color', 'rgb(255, 87, 34)');
                                            }
                                        });
                                        break;
                                    case 'text':
                                    case 'hidden':
                                        surveyAnswer = questionsData[qId];
                                        if( typeof surveyAnswer === 'string' ){
                                            surveyAnswer = surveyAnswer;
                                        }else{
                                            surveyAnswer = surveyAnswer.answer;
                                        }
                                        surveyAnswer = nl2br( surveyAnswer );
                                    case 'short_text':
                                    case 'number':
                                    case 'phone':
                                    case 'name':
                                    case 'email':
                                    case 'date':
                                    case 'time':
                                    case 'date_time':
                                        surveyAnswer = questionsData[qId];
                                        var elem = question.find('.ays_text_answer');
                                        if( typeof surveyAnswer === 'string' ){
                                            surveyAnswer = surveyAnswer;
                                        }else{
                                            surveyAnswer = surveyAnswer.answer;
                                        }
                                        elem.html( surveyAnswer );
                                        break;
                                    case 'matrix_scale':
                                        surveyAnswer = questionsData[qId].answer_ids;
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="radio"]').each(function(){
                                            if((typeof surveyAnswer[$(this).data("id")] != "undefined") && surveyAnswer[$(this).data("id")] == $(this).data("colId")){
                                                $(this).prop('checked', true);
                                            }
                                        });
                                        question.find('.ays-survey-answer-other-input').val(surveyOtherAnswer);
                                    break;
                                    case 'matrix_scale_checkbox':
                                        surveyAnswer = questionsData[qId].answer_ids;
                                        question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="checkbox"]').each(function(){
                                            if((typeof surveyAnswer[$(this).data("id")] != "undefined") && surveyAnswer[$(this).data("id")].includes($(this).data("colId"))){
                                                $(this).prop('checked', true);
                                            }
                                        });
                                        question.find('.ays-survey-answer-other-input').val(surveyOtherAnswer);
                                    break;
                                    case 'star_list':
                                        surveyAnswer = questionsData[qId].star_list_answer_ids;
                                        if(typeof surveyAnswer != "undefined"){
                                            var answers = question.find('.ays-survey-answer-star-list-column-row-header');
                                            answers.each(function(){
                                                var radios = $(this).parents(".ays-survey-answer-star-list-row-content").find('input.ays-survey-star-list-radios');
                                                radios.removeAttr('checked');
                                                $(this).parents(".ays-survey-answer-star-list-row-content").find('.ays-survey-star-icon').removeClass('ays_fa_star').addClass('ays_fa_star_o');
                                                $(this).parents(".ays-survey-answer-star-list-row-content").find('.ays-survey-star-icon').css('color', 'rgb(51, 51, 51)');
                                                if( typeof surveyAnswer[$(this).data("answerId")] != "undefined" ){
                                                    var answerIdValue = surveyAnswer[$(this).data("answerId")];
                                                    var innerRadios = $(this).parents(".ays-survey-answer-star-list-row-content").find("input.ays-survey-star-list-radios");
                                                    innerRadios.each(function(){
                                                        if(answerIdValue >= $(this).data("id")){
                                                            $(this).prop('checked', true);
                                                            $(this).parents('.ays-survey-answer-star-radio').find('.ays-survey-star-icon').removeClass('ays_fa_star_o').addClass('ays_fa_star');
                                                            $(this).parents('.ays-survey-answer-star-radio').find('.ays-survey-star-icon').css('color', 'rgb(255, 87, 34)');
                                                            
                                                        }
                                                    });
                                                }
                                            });
                                            question.find('.ays-survey-answer-other-input').val(surveyOtherAnswer);
                                        }
                                    break;
                                    case 'slider_list':
                                        surveyAnswer = questionsData[qId].slider_list_answer_ids;
                                        if(typeof surveyAnswer != "undefined"){
                                            var sliders = question.find(".ays-survey-answer-slider-list-row-content").find('input.ays-survey-range-type-input');
                                            sliders.each(function(i, element){
                                                var answerId = $(this).parents(".ays-survey-answer-slider-list-row").find('.ays-survey-answer-slider-list-column-row-header-only-slider').data("answerId");
                                                $(this).val(surveyAnswer[answerId]);
                                                var innerSpan = $(this).parents('.ays-survey-answer-range-type-range').find(".ays-survey-answer-range-type-info-text");
                                                setBubble( element, innerSpan.get(0) );
                                            });
                                        }
                                    break;
                                    case 'range':
                                        var innerSlider = $(this).find(".ays-survey-range-type-input");
                                        var innerSpan = $(this).find(".ays-survey-answer-range-type-info-text");
                                        
                                        surveyAnswer = questionsData[qId].answer;
                                        var sliderAnswer = parseInt( surveyAnswer );

                                        question.find('.ays_each_question_answer input[type="range"]').val(sliderAnswer);
                                        setBubble( innerSlider.get(0), innerSpan.get(0) );
                                    break;
                                    case 'upload':
                                        var currnetFileBox = $(this).find(".ays-survey-answer-upload-ready-link");
                                        currnetFileBox.html(questionsData[qId].answer_name)
                                        currnetFileBox.attr("href" , questionsData[qId].answer);
                                        currnetFileBox.attr("download" , questionsData[qId].answer);
                                        if((questionsData[qId].answer_name == "0" || !questionsData[qId].answer_name) && (questionsData[qId].answer == "0" || !questionsData[qId].answer)){
                                            $(this).find(".ays-survey-answer-upload-ready").hide();
                                            $(this).find(".ays-survey-answer-upload-ready .ays-survey-answer-upload-ready-link").addClass("display_none");
                                        }else{                                            
                                            $(this).find(".ays-survey-answer-upload-ready").show();
                                            $(this).find(".ays-survey-answer-upload-ready .ays-survey-answer-upload-ready-link").removeClass("display_none");
                                        }
                                    break;
                                }
                            }else{
                                question.find('div.ays-survey-individual-user-explanation-text span').html("");
                                switch( qType ){
                                    case 'radio':
                                    case 'yesorno':
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays-survey-answer-other-input').val('');
                                        break;
                                    case 'checkbox':
                                        question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                                        question.find('.ays-survey-answer-other-input').val('');
                                        break;
                                    case 'select':
                                        question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('clear');
                                        question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', '');
                                        break;
                                    case 'linear_scale':
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        break;
                                    case 'star':
                                    case 'star_list':
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays-survey-star-icon').removeClass('ays_fa_star').addClass('ays_fa_star_o');
                                        question.find('.ays-survey-star-icon').css('color', 'rgb(51, 51, 51)');
                                        break;
                                    case 'text':
                                    case 'short_text':
                                    case 'number':
                                    case 'phone':
                                    case 'name':
                                    case 'email':
                                    case 'date':
                                    case 'time':
                                        var elem = question.find('.ays_text_answer');
                                        elem.html( '' );
                                        break;
                                    case 'range':
                                        var innerSlider = $(this).find(".ays-survey-range-type-input");
                                        var innerSpan = $(this).find(".ays-survey-answer-range-type-info-text");

                                        question.find('.ays_each_question_answer input[type="range"]').val( innerSlider.attr('min') );
                                        setBubble( innerSlider.get(0), innerSpan.get(0) );
                                    break;
                                    case 'slider_list':
                                        var innerSlider = $(this).find(".ays-survey-range-type-input");
                                        innerSlider.each(function(i, element){
                                            var innerSpan = $(this).parents('.ays-survey-answer-range-type-range').find(".ays-survey-answer-range-type-info-text");
                                            question.find('input[type="range"]').val( innerSlider.attr('min') );
                                            setBubble( element, innerSpan.get(0) );
                                        })

                                    break;
                                    case 'upload':
                                        $(this).find(".ays-survey-answer-upload-ready").hide();
                                        $(this).find(".ays-survey-answer-upload-ready .ays-survey-answer-upload-ready-link").addClass("display_none");
                                    break;
                                }
                            }
                        });
                    }
                }
            });
            submissionPrevVal = submissionElVal;
        }

        $(document).find('.ays-survey-submission-summary-print').on('click', function(){
            $('html, body').addClass('ays-survey-print-submissions ays-survey-print-submissions-summary');
            window.print();
        });

        $(document).find('.ays-survey-submission-questions-print').on('click', function(){
            $('html, body').addClass('ays-survey-print-submissions ays-survey-print-submissions-questions');
            window.print();
        });

        window.addEventListener('afterprint', function() {
            $('html, body').removeClass('ays-survey-print-submissions');
            $('html, body').removeClass('ays-survey-print-submissions-summary');
            $('html, body').removeClass('ays-survey-print-submissions-questions');
        });

        // ===============================================================
        // ===============   Submission filters START   ==================
        // ===============================================================
         
        $(document).find(".ays-survey-submissions-filter-button").on("click", function () {
            $(this).next('.ays-survey-submissions-filter-container').slideToggle('fast');
        })

        $(document).find("button#ays-survey-submissions-filter").on("click", function (e) {
            e.preventDefault();

            var _this  = $(this);
            var parent = _this.parents('.ays-survey-submissions-filter-container');
            
            var search_input = parent.find('select#ays-survey-submissions-filter-post');
            var input_value  = search_input.val();
            var search_input2 = parent.find('#ays-survey-submissions-filter-start-date');
            var input_value2  = search_input2.val();
            var search_input3 = parent.find('#ays-survey-submissions-filter-end-date');
            var input_value3  = search_input3.val();
            
            var field = 'filterbypost';
            var flag = false;
            var field2 = 'filterbystartdate';
            var flag2 = false;
            var field3 = 'filterbyenddate';
            var flag3 = false;
            var url = window.location.href;
            if (url.indexOf('?' + field + '=') != -1) {
                flag = true;
            } else if (url.indexOf('&' + field + '=') != -1) {
                flag = true;
            }
            if (url.indexOf('?' + field2 + '=') != -1) {
                flag2 = true;
            } else if (url.indexOf('&' + field2 + '=') != -1) {
                flag2 = true;
            }
            if (url.indexOf('?' + field3 + '=') != -1) {
                flag3 = true;
            } else if (url.indexOf('&' + field3 + '=') != -1) {
                flag3 = true;
            }
            
            if (flag) {
                if (typeof input_value != 'undefined' && input_value != "") {
                    url = url.replace(/&filterbypost=([^&]$|[^&]*)/i, "&filterbypost="+input_value);
                } else if (input_value == "") {
                    url = url.replace(/&filterbypost=([^&]$|[^&]*)/i, "");
                }
            } else {
                if (typeof input_value != 'undefined' && input_value != "") {
                    url = url + "&filterbypost=" + input_value;
                }
            }

            if (flag2) {
                if (typeof input_value2 != 'undefined' && input_value2 != "") {
                    url = url.replace(/&filterbystartdate=([^&]$|[^&]*)/i, "&filterbystartdate="+input_value2);
                } else if (input_value2 == "") {
                    url = url.replace(/&filterbystartdate=([^&]$|[^&]*)/i, "");
                }
            } else {
                if (typeof input_value2 != 'undefined' && input_value2 != "") {
                    url = url + "&filterbystartdate=" + input_value2;
                }
            }

            if (flag3) {
                if (typeof input_value3 != 'undefined' && input_value3 != "") {
                    url = url.replace(/&filterbyenddate=([^&]$|[^&]*)/i, "&filterbyenddate="+input_value3);
                } else if (input_value3 == "") {
                    url = url.replace(/&filterbyenddate=([^&]$|[^&]*)/i, "");
                }
            } else {
                if (typeof input_value3 != 'undefined' && input_value3 != "") {
                    url = url + "&filterbyenddate=" + input_value3;
                }
            }

            location.href = url;
        });

        $(document).find("button#ays-survey-submissions-filter-clear").on("click", function (e) {
            e.preventDefault();
            
            var _this  = $(this);
            var url = window.location.href;

            var field = 'filterbypost';
            var flag = false;
            var field2 = 'filterbystartdate';
            var flag2 = false;
            var field3 = 'filterbyenddate';
            var flag3 = false;

            if (url.indexOf('?' + field + '=') != -1) {
                flag = true;
            } else if (url.indexOf('&' + field + '=') != -1) {
                flag = true;
            }

            if (url.indexOf('?' + field2 + '=') != -1) {
                flag2 = true;
            } else if (url.indexOf('&' + field2 + '=') != -1) {
                flag2 = true;
            }

            if (url.indexOf('?' + field3 + '=') != -1) {
                flag3 = true;
            } else if (url.indexOf('&' + field3 + '=') != -1) {
                flag3 = true;
            }

            if (flag) {
                url = url.replace(/&filterbypost=([^&]$|[^&]*)/i, "");
            }
            if (flag2) {
                url = url.replace(/&filterbystartdate=([^&]$|[^&]*)/i, "");
            }
            if (flag3) {
                url = url.replace(/&filterbyenddate=([^&]$|[^&]*)/i, "");
            }

            location.href = url;
        });

        // ===============================================================
        // ================   Submission filters END   ===================
        // ===============================================================
        
        // ===============================================================
        // ======================      Xcho      =========================
        // ===============================================================
        

        $('#ays_slack_client').on('input', function () {
            var clientId = $(this).val();
            if (clientId == '') {
                $("#slackOAuth2").addClass('disabled btn-outline-secondary');
                $("#slackOAuth2").removeClass('btn-secondary');
                return false;
            }
            var scopes = "channels%3Ahistory%20" +
                "channels%3Aread%20" +
                "channels%3Awrite%20" +
                "groups%3Aread%20" +
                "groups%3Awrite%20" +
                "mpim%3Aread%20" +
                "mpim%3Awrite%20" +
                "im%3Awrite%20" +
                "im%3Aread%20" +
                "chat%3Awrite%3Abot%20" +
                "chat%3Awrite%3Auser";
            var url = "https://slack.com/oauth/authorize?client_id=" + clientId + "&scope=" + scopes + "&state=" + clientId;
            $("#slackOAuth2").attr('data-src', url);//.toggleClass('disabled btn-outline-secondary btn-secondary');
            $("#slackOAuth2").removeClass('disabled btn-outline-secondary');
            $("#slackOAuth2").addClass('btn-secondary');
        });
        $("#slackOAuth2").on('click', function () {
            var url = $(this).attr('data-src');
            if (!url) {
                return false;
            }
            location.replace(url)
        });
        $('#ays_slack_secret').on('input', function(e) {
            if($(this).val() == ''){
                $("#slackOAuthGetToken").addClass('disabled btn-outline-secondary');
                $("#slackOAuthGetToken").removeClass('btn-secondary');
                return false;
            }
            
            $("#slackOAuthGetToken").removeClass('disabled btn-outline-secondary');
            $("#slackOAuthGetToken").addClass('btn-secondary');
        });
        



        // ===============================================================
        // ======================   Limitation  ==========================
        // ===============================================================
        $('#ays_survey_users_roles').select2();

        $(document).find('#ays_survey_enable_restriction_pass').on('click', function () {
            if ($(this).prop('checked')) {
                if ($(document).find('#ays_survey_enable_logged_users').prop('checked')){
                    $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
                }else{
                    $(document).find('#ays_survey_enable_logged_users').trigger('click');
                    $(document).find('#ays_survey_enable_logged_users').prop('checked', true);
                    $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
                }
            } else if($(document).find('#ays_survey_enable_restriction_pass_users').prop('checked')) {
                $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
            } else {
                $(document).find('#ays_survey_enable_logged_users').prop('disabled', false);
            }
        });

        $(document).find('#ays_survey_enable_restriction_pass_users').on('click', function () {
            if ($(this).prop('checked')) {
                if ($(document).find('#ays_survey_enable_logged_users').prop('checked')){
                    $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
                }else{
                    $(document).find('#ays_survey_enable_logged_users').trigger('click');
                    $(document).find('#ays_survey_enable_logged_users').prop('checked', true);
                    $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
                }
            } else if($(document).find('#ays_survey_enable_restriction_pass').prop('checked')) {
                $(document).find('#ays_survey_enable_logged_users').prop('disabled', true);
            } else {
                $(document).find('#ays_survey_enable_logged_users').prop('disabled', false);
            }
        });
                
        //Access Only selected users 
        $(document).find('#ays_survey_users_search').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.select_user,
            minimumInputLength: 1,
            ajax: {
                url: ajaxurl,
                dataType: 'json',
                data: function (params) {
                    var checkedUsers = $(document).find('#ays_survey_users_search').val();
                    return {
                        action: 'ays_survey_admin_ajax',
                        function: 'ays_survey_users_search',
                        q: params.term,
                        val: checkedUsers,
                        page: params.page
                    };
                },
            }
        });


        $(document).find("#slackInstructionsPopOver").popover({
            content: $(document).find("#slackInstructions").html(),
            html: true,
        });
        
        $(document).find("#slackInstructionsPopOver").on("click" , function(){
            $(document).find(".popover").addClass("ays-slack-popover");
        });

        $(document).find("#googleInstructionsPopOver").popover({
            content: $(document).find("#googleInstructions").html(),
            html: true,
        });
        
        $(document).find("#googleInstructionsPopOver").on("click" , function(){
            $(document).find(".popover").addClass("ays-google-sheet-popover");
        });

        $(document).on("click", "#surveyChatGPTInstructionsPopOver", function(){
			$(document).find(".popover").addClass("ays-survey-ai-popover");
		});

        $(document).find("#surveyChatGPTInstructionsPopOver").popover({
            content: $(document).find("#surveyChatGPTInstructions").html(),
            html: true,
        });
		

        $(document).find(".ays-survey-popup-show-where").on('change', function(){
            var checked = $(this).val();
            if( checked == 'all' ){
                $(document).find('.ays_survey_view_place_tr').addClass('display_none');
            }else{
                $(document).find('.ays_survey_view_place_tr').removeClass('display_none');
            }
        });

        $(document).find('#ays_survey_posts').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectPage,
            minimumInputLength: 3,
            multiple: true,
            ajax: {
                url: SurveyMakerAdmin.ajaxUrl,
                dataType: 'json',
                data: function (params) {
                    var checkedUsers = $(document).find('#ays_survey_post_types').val();
                    return {
                        action: 'ays_survey_admin_ajax',
                        function: 'get_selected_posts',
                        q: params.term,
                        val: checkedUsers,
                        page: params.page
                    };
                },
            }
        });   
    
        //Get Only selected post types(AV)
        $(document).find('#ays_survey_post_types').select2({
            placeholder: SurveyMakerAdmin.selectPostType,
            allowClear: true,
            minimumInputLength: 3,
            multiple: true,
            ajax: {
                url: SurveyMakerAdmin.ajaxUrl,
                dataType: 'json',
                data: function (params) {
                    var checkedUsers = $(document).find('#ays_survey_post_types').val();
                    return {
                        action: 'ays_survey_admin_ajax',
                        function: 'ays_survey_get_post_type',
                        q: params.term,
                        val: checkedUsers,
                        page: params.page
                    };
                },
            }
        });

        $(document).find('.ays-all-submission-table, .ays-show-user-page-table').sortable({
            cursor: 'move',
            opacity: 0.8,
            tolerance: "pointer",
            helper: "clone",
            placeholder: "ays_user_page_sortable_placeholder",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
        });

        $(document).find('table#ays-survey-popup-position-table tr td').on('click', function(e){
            var val = $(this).data('value');
            $(document).find('.popup_survey_position_block #ays-survey-popup-position-val').val(val);
            aysCheckPopupPosition();
        });

        function aysCheckPopupPosition(){
            var hiddenVal = $(document).find('.popup_survey_position_block #ays-survey-popup-position-val').val();
           
            if (hiddenVal == "") {
                var $this = $(document).find('table#ays-survey-popup-position-table tr td[data-value="center-center"]');
            }else{
                var $this = $(document).find('table#ays-survey-popup-position-table tr td[data-value='+ hiddenVal +']');
            }

            if (hiddenVal == 'center-center' || hiddenVal == ''){
                $(document).find("#popupMargin").addClass('display_none');
                $(document).find(".ays_pb_hr_hide").addClass('display_none');
            }else{
                $(document).find("#popupMargin").removeClass('display_none');
                $(document).find(".ays_pb_hr_hide").removeClass('display_none');
            }

            $(document).find('table#ays-survey-popup-position-table td').removeAttr('style');
            $this.css('background-color','#a2d6e7');
        }

        $(document).find('#ays-select-popup-survey-id').select2({
            placeholder: 'Select Survey',
        });

        $(document).find('#ays_survey_admin_email_session').select2({
            minimumResultsForSearch: -1
        });

        $(document).find(".ays-survey-min-votes-field").on("input" , function(){
            var $this = $(this);
            var minVal = parseInt($(this).val());
            var maxField = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-max-votes-field");
            if(minVal >= maxField.val())
            maxField.val(minVal);
        });

        $(document).find(".ays-survey-max-votes-field").on("change" , function(){
            var $this = $(this);
            var maxVal = parseInt($(this).val());
            var minField = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-min-votes-field");
            var minVal = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-min-votes-field").val();
            if(maxVal == ''){
                return;
            }
            if(maxVal < minVal){
                $this.val(minVal);
            }
        });

        $(document).on("change", ".ays-survey-input-range-step-length" , function(){
            var $this = $(this);
            var stepLength = $this.val(); 
            var maxLength = $this.parents(".ays-survey-question-types-box").find(".ays-survey-input-range-length").val();
            
            if(stepLength >= maxLength/2 && (maxLength/2 > 0)){
                stepLength = maxLength/2;
            }
            $this.val(stepLength);
        });

        $(document).on("change", ".ays-survey-input-range-default-val" ,function(){
            var $this = $(this);
            var defaultVal = +$this.val(); 
            var maxLength  = +$this.parents(".ays-survey-question-types-box").find(".ays-survey-input-range-length").val();
            var minVal = +$this.parents(".ays-survey-question-types-box").find(".ays-survey-input-range-min-val").val();
            if(defaultVal <= minVal){
                defaultVal = minVal;
                $this.val(defaultVal);
            }
            else if(maxLength != 0 && defaultVal > maxLength){
                 $this.val(minVal);
            }
        });

        $(document).on("input", ".ays-survey-input-range-min-val" , function(){
            var $this = $(this);
            var minVal = $this.val();
            var defaultVal = $this.parents(".ays-survey-question-types-box").find(".ays-survey-input-range-default-val");
            var defaultValLength = $this.parents(".ays-survey-question-types-box").find(".ays-survey-input-range-default-val").val();
            if(parseInt(defaultValLength) <= parseInt(minVal)){
                defaultVal.val(minVal);
            }
        });

        $(document).on("input", ".ays-survey-slider-only-numbers" ,function(){
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\.*)/g, '');
        });

        $(document).find("#ays-survey-form .nav-tab").on("click" , function(){
            var $this = $(this);
            if( $this.data("tab") != "undefined" && ( $this.data("tab") == 'tab1' || $this.data("tab") == 'tab9' ) ){
                refreshSurveyColor($this);
            }
        });

        $(document).on("change", ".ays-survey-choose-for-select-lenght, .ays-survey-choose-for-start-select-lenght, .ays-survey-choose-for-select-lenght-star-list, .ays-survey-choose-for-select-lenght-slider-list", function(){
            $(this).parents(".ays-survey-question-types-conteiner").find(".ays_survey_linear_scale_span_changeable").html($(this).val());
        });

        // Generate password
        $(document).find("#ays_survey_general_psw").on('click', function(){
            $('#ays_survey_generate_psw_content').hide(150);
            $('#ays_survey_psw_content').show(500)
        });

        $(document).find("#ays_survey_generated_psw_radio").on('click', function(){
            $('#ays_survey_psw_content').hide(150);
            $('#ays_survey_generate_psw_content').show(500)
        });

        $(document).on('click','#ays_survey_generate_password_submit',function(){
            var count_passwords = $(document).find('#ays_survey_password_count').val();
            var generated_table = $(document).find('.ays-survey-created');
            var psw_symbols     = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$_+?%^&)";
            var psw_count       = 8;
            var password        = "";
            var content         = "";
                for (var i = 0; i < count_passwords; i++) {
                    for(var j = 0; j < psw_count; j++){
                        var psw = Math.floor(Math.random() * psw_symbols.length);
                            password += psw_symbols.substring(psw, psw+1);
                    }
                    content += '<li>';
                        content += '<span class="ays-survey-created-psw">'+password+'</span><a class="ays-survey-gen-psw-copy"><i class="fa fa-clipboard" aria-hidden="true"></i></a>';
                        content += '<input type="hidden" name="ays_survey_generated_psw[]" value="'+password+'" class="ays-survey-generated-psw">';
                    content += '</li>';
                    password = "";
                }
            generated_table.append(content);
        });

        $(document).on('click','.ays-survey-gen-psw-copy',function(){
            var $this = $(this);
            var generated_psw_parent  = $this.parents('#ays_survey_generated_password').find('.ays-survey-active');
            if (generated_psw_parent.find(".ays_survey_active_password_empty_notice").length > 0) {
                generated_psw_parent.empty();
            }

            var copied_psw_value      = $this.next().val();
            var $temp                 = $("<input type='text'>");

            $("body").append($temp);
            $temp.val(copied_psw_value).select();
            document.execCommand("copy");
            $temp.remove();

            var content = '';
                content += '<li><span>'+ copied_psw_value+'</span>';
                    content += '<input type="hidden" name="ays_survey_active_gen_psw[]" value="'+ copied_psw_value +'" class="ays-survey-generated-psw">';
                content += '</li>';

                generated_psw_parent.append(content);
                $this.parent().remove();
            
            if(generated_psw_parent.length > 0){
                generated_psw_parent.find('.ays_survey_active_password_empty_notice').css('display','none');
            }
        });

        $(document).on('click','#ays_survey_gen_psw_copy_all',function(){
            var $this = $(this)
            var copied_passwords_ul_li = $this.parents('#ays_survey_generated_password').find('#ays_survey_generated_psw li');
            var $temp = $("<textarea><textarea>");
            var passwords = [];
            copied_passwords_ul_li.each(function(){
                var copied_passwords_value = $(this);
                var all_passwords = $(this).text();
                    passwords.push('\n'+all_passwords);
                $(document).find('.ays-survey-active').append( copied_passwords_value );
                $(document).find('.ays-survey-active').find('.ays-survey-generated-psw').attr('name', 'ays_survey_active_gen_psw[]');
                $(document).find('.ays-survey-active').find('.ays-survey-gen-psw-copy').remove();
            });
            $("body").append($temp);
            $temp.val(passwords).select();
            document.execCommand("copy");
            $temp.remove();

            $(document).find('#ays_survey_generated_psw li').remove();
            if($this.parents('#ays_survey_generated_password').find('.ays-survey-active').length > 0){
                $this.parents('#ays_survey_generated_password').find('.ays-survey-active').find('.ays_survey_active_password_empty_notice').css('display','none');
            }
        });

        // Survey Templates start
            $(document).find(".ays-survey-open-templates-modal").on("click" , function(e){
                e.preventDefault();
                var $this = $('#ays-survey-templates-modal');
                $this.find('.ays-modal-content-survey-templates .ays-survey-templates-box.ays-survey-templates-blank-box').remove();
                $(document).find('#ays-survey-templates-modal').aysModal('show');
                $(document).find('#ays-survey-templates-modal .ays-modal-content.ays-modal-content-survey-templates').removeClass('no-confirmation');
            
            });

            $(document).find(".ays-survey-templates-box-apply-button-blank").on("click" , function(e){
                e.preventDefault();
                var $thisMainParent = $(this).parents('#ays-survey-templates-modal');
                $thisMainParent.find('.ays-close').trigger('click');
            });

            $(document).find(".ays-survey-templates-box-apply-button").on("click" , function(e){
                e.preventDefault();
                var $this = $(this);
                var $thisParent = $this.parents(".ays-modal-content-survey-templates");
                if(!$thisParent.hasClass('no-confirmation')){
                    var confirm = window.confirm(SurveyMakerAdmin.confirmMessageTemplate);
                }
                else{
                    var confirm = true;
                }
                if(confirm == true){

                    
                    var modal = $('#ays-survey-templates-modal');
                    var otherTypes = new Array(
                        'text',
                        'linear_scale',
                        'star',
                        'short_text',
                        'number',
                        'phone',
                        'name',
                        'email',
                        'date',
                        'date_time',
                        'time',
                    );
                        $thisParent.find(".ays_survey_preloader").show();

                    // var action = 'ays_survey_add_survey_template';
                    var action = 'ays_survey_admin_ajax';
                    var sFunction = 'ays_survey_add_survey_template';
                    
                    var templateName = $this.attr('data-template');

                    $.ajax({
                        url: ajaxurl,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            action: action,
                            function: sFunction,
                            template_file_name: templateName
                        },
                        success: function(response) {
                            if (response.status) {
                                var sections = response.data.sections;
                                $(document).find('#ays-survey-title').val(response.data.title);
                                // For Sections
                                var newQuestion = "";
                                var parent = $(document).find(".ays-survey-sections-conteiner");
                                parent.html("");
                                for(var key in sections){

                                    aysSurveyAddSection( null, null, false, true );
                                    var lastAddedSection = parent.find(".ays-survey-new-section:last-child");
                                    lastAddedSection.find(".ays-survey-section-questions").html("");
                                    lastAddedSection.find(".ays-survey-section-title").val(sections[key].title);
                                    // For Questions
                                    for(var questionKey in sections[key].questions){
                                        var allAddedAnswers = new Array();
                                        var questionType = typeof sections[key].questions[questionKey].type != "undefined" ? sections[key].questions[questionKey].type : "";
                                        if(questionType != ""){
                                            aysSurveyAddQuestion(lastAddedSection.attr("data-id"), false, lastAddedSection , false, true);
                                            newQuestion = lastAddedSection.find("div.ays-survey-new-question[data-template-id='"+(Number(questionKey)+1)+"']");
                                            newQuestion.find('.ays-survey-question-type').aysDropdown('set selected', sections[key].questions[questionKey].type );
                                            newQuestion.find('.ays-survey-question-type').aysDropdown('set value', sections[key].questions[questionKey].type );
                                            newQuestion.find('.ays-survey-check-type-before-change').val( sections[key].questions[questionKey].type );
                                            newQuestion.find(".ays-survey-question-input-textarea").html(sections[key].questions[questionKey].question);
                                            newQuestion.find(".ays-survey-question-description-input-textarea").html(sections[key].questions[questionKey].description);
                                            var thisButton = newQuestion.find(".ays-survey-action-add-answer");
                                            
                                            // For Answers
                                            for(var answerKey in sections[key].questions[questionKey].answers){

                                                aysSurveyAddAnswer( thisButton, false, false, true );
                                                var newAnswer = newQuestion.find("div.ays-survey-new-answer[data-id='"+(Number(answerKey)+1)+"']");
                                                allAddedAnswers.push(newAnswer);
                                                newAnswer.find(".ays-survey-input").val(sections[key].questions[questionKey].answers[answerKey].answer);
                                                
                                            }

                                            if(sections[key].questions[questionKey].user_variant == 'on'){
                                                var checkbox = newQuestion.find('.ays-survey-other-answer-checkbox').attr('checked', 'checked');
                                                var oterAnswer = newQuestion.find('.ays-survey-other-answer-row');
                                                oterAnswer.removeAttr('style');
                                                newQuestion.find('.ays-survey-other-answer-add-wrap').css('display','none');
                                            }

                                            newQuestion.find('.ays-survey-input-required-question').prop('checked', sections[key].questions[questionKey].options[0].required == 'on' ? true : false );

                                            if(otherTypes.indexOf(questionType) == -1 && allAddedAnswers.length > 0){
                                                newQuestion.find("div.ays-survey-answers-conteiner").html(allAddedAnswers);
                                            }
                                        }
                                    }
                                }
                                setTimeout(function(){
                                    $thisParent.find(".ays_survey_preloader").hide();
                                    modal.aysModal('hide');
                                } , 100);

                            }
                            else{
                                $thisParent.find(".ays_survey_preloader").hide();                        
                            }
                        },
                        error: function(){
                            $thisParent.find(".ays_survey_preloader").hide();
                            modal.aysModal('hide');
                        }
                    });
                }  
            });
        // Survey Templates end


        function refreshSurveyColor(element){
            var customCss = $(document).find("#ays-survey-custom-css-additional");
            var parentElement = element.parents("#ays-survey-form");
            var surveyColor = parentElement.find("#ays_survey_color").val();
            var isMinimal = $(document).find('input[name="ays_survey_theme"]:checked').val() == 'minimal' ? true : false;
            var isModern = $(document).find('input[name="ays_survey_theme"]:checked').val() == 'modern' ? true : false;
            var isbusiness = $(document).find('input[name="ays_survey_theme"]:checked').val() == 'business' ? true : false;
            var surveyColorAlpha = aysSurveyRgba2hex( surveyColor, true );
            surveyColor = aysSurveyRgba2hex( surveyColor, false );

            if( isMinimal || isModern || isbusiness){
                if( parentElement.find("#ays_survey_color").val() == 'rgba(0,0,0,0)' ){
                    surveyColor = '#333333';
                    surveyColorAlpha = '#33333329';
                }
            }

            var newCss = '#ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head {'
                    newCss += 'border-top-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head-top .ays-survey-section-counter {'
                    newCss += 'background-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .ays-survey-input:focus ~ .ays-survey-input-underline-animation {'
                    newCss += 'background-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .dropdown-item:hover {'
                    newCss += 'background-color: '+surveyColorAlpha+';';
                newCss += '}';

                newCss += '#ays-survey-form .dropdown-item:focus {'
                    newCss += 'background-color: '+surveyColorAlpha+';';
                newCss += '}';

                newCss += '#ays-survey-form .dropdown-item:active {'
                    newCss += 'background-color: '+surveyColor+';';
                newCss += '}';

                newCss += '#ays-survey-form input.ays-switch-checkbox:checked + .switch-checkbox-wrap .switch-checkbox-circles .switch-checkbox-thumb {'
                    newCss += 'border-color: '+surveyColor;
                newCss += '}';

                newCss += '.ays-survey-answer-label input[type="checkbox"]:checked ~ .ays-survey-answer-label-content .ays-survey-answer-icon-content .ays-survey-answer-icon-content-2, .ays-survey-answer-label input[type="radio"]:checked ~ .ays-survey-answer-label-content .ays-survey-answer-icon-content .ays-survey-answer-icon-content-2{';
                    newCss += 'border-color: '+surveyColor;
                newCss += '}';

                newCss += '.ays-survey-answer-label input[type="checkbox"] ~ .ays-survey-answer-label-content .ays-survey-answer-icon-content .ays-survey-answer-icon-content-3, .ays-survey-answer-label input[type="radio"] ~ .ays-survey-answer-label-content .ays-survey-answer-icon-content .ays-survey-answer-icon-content-3{';
                    newCss += 'border-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .ays-survey-condition-containers-added {'
                    newCss += 'border-top-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .ays-survey-condition-containers-editable {'
                    newCss += 'border-left-color: '+surveyColor;
                newCss += '}';

                newCss += '#ays-survey-form .ays-survey-condition-containers-added .nav-cond-tab-active{'
                    newCss += 'background-color: '+surveyColor+';';
                    newCss += 'color: white;';
                newCss += '}';
                newCss += '#ays-survey-form input.ays-switch-checkbox:checked + .switch-checkbox-wrap .switch-checkbox-track {'
                    newCss += 'border-color: '+surveyColor+'53';
                newCss += '}';

            customCss.html(newCss);
        }

        $(document).find(".ays-survey-open-templates-modal").prop('disabled', false);


        $(document).find(".ays-survey-open-import-modal").prop('disabled', false);
        $(document).find(".ays-survey-open-import-modal").on("click" , function(e){
            e.preventDefault();
            var $this = $('#ays-survey-export-question-modal');
            $this.aysModal('show');
            $this.find(".ays_survey_preloader").hide();
        });

        $(document).find("#ays_survey_import_question_filter").on("change" , function(){
            if($(this).val() != ''){
                $(this).parents("#ays_survey_import_questions_form").find(".ays-survey-questions-import-action").prop("disabled" , false)
            }
            else{
                
                $(this).parents("#ays_survey_import_questions_form").find(".ays-survey-questions-import-action").prop("disabled" , true)
            }
        });

        $(document).find(".ays-survey-questions-import-action").on("click" , function(e){
            e.preventDefault();
            var $this = $(this);
            var modal = $('#ays-survey-export-question-modal');
            var otherTypes = new Array(
                'text',
                'linear_scale',
                'star',
                'short_text',
                'number',
                'phone',
                'name',
                'email',
                'date',
                'time',
                'date_time',
                'upload',
            );
            var $thisParent = $this.parents(".ays-modal-content-question-import");
            var inputFile = $thisParent.find("#ays_survey_import_question_filter").val();
            if(inputFile){
                $thisParent.find(".ays_survey_preloader").show();
                setTimeout(function(){
                    $thisParent.find(".ays_survey_preloader").hide();
                } , 8000);
            }
            var formData = new FormData();
            var questionData = $('#ays_survey_import_question_filter').prop('files')[0];
            var action = 'ays_survey_admin_ajax';
            var sFunction = 'ays_survey_import_questions';
            if(typeof(questionData) != "undefined"){
                formData.append('questions_data', questionData);
                formData.append('function', sFunction);
                formData.append('action', action);
            }
    
            $.ajax({
                url: ajaxurl,
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                data: formData,
                success: function(response) {
                    if (response.status) {
                        // For Sections
                        var newQuestion = "";
                        for(var key in response.data){
                            aysSurveyAddSection( null, null , false );
                            var parent = $(document).find(".ays-survey-sections-conteiner");
                            var lastAddedSection = parent.find(".ays-survey-new-section:last-child");
                            lastAddedSection.find(".ays-survey-section-questions").html("");
                            lastAddedSection.find(".ays-survey-section-title").val(response.survey_titles[key]);
                            // For Questions
                            for(var questionKey in response.data[key]){
                                var allAddedAnswers = new Array();
                                var questionType = typeof response.data[key][questionKey].type != "undefined" ? response.data[key][questionKey].type : "";
                                if(questionType != ""){
                                    aysSurveyAddQuestion(lastAddedSection.data("id"), false, lastAddedSection, false);
                                    newQuestion = lastAddedSection.find("div.ays-survey-new-question[data-id='"+(Number(questionKey)+1)+"']");
                                    newQuestion.find('.ays-survey-question-type').aysDropdown('set selected', response.data[key][questionKey].type );
                                    newQuestion.find('.ays-survey-question-type').aysDropdown('set value', response.data[key][questionKey].type );
                                    newQuestion.find('.ays-survey-check-type-before-change').val( response.data[key][questionKey].type );
                                    newQuestion.find(".ays-survey-question-input-textarea").html(response.data[key][questionKey].question);
                                    newQuestion.find(".ays-survey-question-description-input-textarea").html(response.data[key][questionKey].description);
                                    var thisButton = newQuestion.find(".ays-survey-action-add-answer");
                                    // For Answers
                                    for(var answerKey in response.all_answers[key][questionKey]){
                                        aysSurveyAddAnswer( thisButton, false, false );
                                        var newAnswer = newQuestion.find("div.ays-survey-new-answer[data-id='"+(Number(answerKey)+1)+"']");
                                        allAddedAnswers.push(newAnswer);
                                        newAnswer.find(".ays-survey-input").val(response.all_answers[key][questionKey][answerKey]);
                                        if(questionType == "linear_scale" || questionType == "star"){
                                            newQuestion.find(".ays-survey-input-linear-scale-1").val(response.all_answers[key][questionKey][0]);
                                            newQuestion.find(".ays-survey-input-linear-scale-2").val(response.all_answers[key][questionKey][1]);
                                            newQuestion.find(".ays-survey-input-star-1").val(response.all_answers[key][questionKey][0]);
                                            newQuestion.find(".ays-survey-input-star-2").val(response.all_answers[key][questionKey][1]);
                                        }
                                    }
                                    if(otherTypes.indexOf(questionType) == -1 && allAddedAnswers.length > 0){
                                        newQuestion.find("div.ays-survey-answers-conteiner").html(allAddedAnswers);
                                    }
                                }
                            }
                        }
                        setTimeout(function(){
                            $thisParent.find(".ays_survey_preloader").hide();
                            modal.aysModal('hide');
                            // newQuestion.goToTop();
                            swal.fire({
                                type: 'success',
                                text: SurveyMakerAdmin.importQuestions
                            });
                        } , 100);

                    }
                    else{
                        if(typeof response.data != "undefined" && response.data.length <= 0){
                            var errorBox = $thisParent.find('.ays-survey-question-import-modal-error-message')
                            $thisParent.find(".ays_survey_preloader").hide();
                            errorBox.removeClass("display_none_not_important");
                            errorBox.html("* " + SurveyMakerAdmin.questionImportEmptyError);
                            setTimeout(function(){
                                errorBox.addClass("display_none_not_important");
                                errorBox.html("");
                            } , 5000);
                        }
                    }
                },
                error: function(){
                    modal.aysModal('hide');
                }
            });
            
        });

        var ays_survey_popup_bg_color_picker = {
            defaultColor: '#ffffff',
            change: function (e) {
            }
        };
        
        // Initialize popup survey color picker
        $(document).find('#ays_survey_popup_bg_color').wpColorPicker(ays_survey_popup_bg_color_picker);

        $(document).on("click", ".ays-survey-condition-refresh-data", function(e){
            $(document).find('#ays-button-apply-top').trigger('click');
        });

        //Download charts
        $(document).on('click', '.ays-survey-submission-summary-export-chart-img', function(e){
            var answersContainer = $(this).parents('.ays-survey-submission-summary-question-container').find('.ays-survey-submission-summary-question-content');

            var answersContainerChild = $(this).parents('.ays-survey-submission-summary-question-container').find('.ays-survey-submission-summary-question-content').children();
            var fileName = $(this).parents('.ays-survey-submission-summary-question-container').find('.ays-survey-submission-summary-question-title-item').find('p').text();

            if(answersContainerChild.width() != 0 && answersContainerChild.height() != 0){
                html2canvas(answersContainer[0]).then(function(canvas) {
                    Canvas2Image.saveAsPNG(canvas,null,null,fileName);
                })
                // html2canvas(answersContainer, { 
                //     onrendered: function(canvas) {
                //         return Canvas2Image.saveAsPNG(canvas,null,null,fileName);
                //     }
                // });
                // html2canvas($(answersContainer), {
                //     onrendered: function (canvas) {
                //         var url = canvas.toDataURL();
                //         $("<a>", {
                //             href: url,
                //             download: fileName
                //         })
                //         .on("click", function() {$(this).remove()})
                //         .appendTo("body")[0].click();
                //     }
                // })
            }else{  
                swal.fire({
                    type: 'info',
                    html: "<h6>There is no data yet.</h6>"
                });
            }
        });

        $(document).find('#ays_survey_popup_trigger').on('change', function(){
            var triggerType = $(this).val();
            if(triggerType == 'on_click'){
                $(document).find('.ays-survey-popup-selector').show(250);
                $(document).find('.ays-survey-popup-selector').css('display','flex');
                $(document).find('.ays-survey-popup-selector').prev('hr').show(250);
            }else{
                $(document).find('.ays-survey-popup-selector').hide(250);
                $(document).find('.ays-survey-popup-selector').prev('hr').hide(250);
            }
        });

        // Disable tooltip bottom arrow for export to xlsx on the submissions page
        $(document).find('a[data-hover="ays-survey-export-to-xlsx-tooltip"]').hover(function(){
            $(document).find(".tooltip > .arrow").hide();
        });

        $(document).on('click', '.ays-survey-gen-psw-copy', function () {
            selectElementContentsCopy($(this));
        });


        $(document).on('click', '.ays_survey_each_sub_user_info_header_button button', function () {
            selectElementContentsCopy($(this));
        });

        var surveyResults = $(document).find('.ays_survey_result');
        for (var i in surveyResults) {
            surveyResults.eq(i).parents('tr').addClass('ays_survey_results');
        }

        $(document).on("click", ".ays-survey-view-detailed-button", getEachSubmission);

        $(document).on("change", ".ays-survey-show-grid", function(){
            var answerView = $(this).find('select#ays_survey_answers_view').val();
            if(answerView == 'grid'){
                $(this).parent().find('.ays-survey-grid-view-type').removeClass('display_none');
                $(this).parent().find('.ays-survey-grid-view-type').show(250);
            }else if( answerView == 'list' ){
                $(this).parent().find('.ays-survey-grid-view-type').addClass('display_none');
                $(this).parent().find('.ays-survey-grid-view-type').hide(250);
            }
        });

        var isPasswordLinkClickable = $(document).find(".ays-survey-view-detailed-button[data-sub-password='isTrigger']");
        if(isPasswordLinkClickable){
            isPasswordLinkClickable.trigger('click');
        }

        $(document).find('#ays-survey-title').on('input', function(e){
            var surveyTitleVal = $(this).val();
            var surveyTitle = aysSurveystripHTML( surveyTitleVal );
            $(document).find('.ays_survey_title_in_top').html( surveyTitle );
        });    
        
        // Select message vars surveys page
        $(document).find('.ays-survey-message-vars-icon').on('click', function(e){
            $(this).parents(".ays-survey-message-vars-box").find(".ays-survey-message-vars-data").toggle('fast');
        });
        
        $(document).find('.ays-survey-open-surveys-list').on('click', function(e){
            $(this).parents(".ays-survey-subtitle-main-box").find(".ays-survey-surveys-data").toggle('fast');
        });
        
        $(document).on( "click" , function(e){

            if($(e.target).closest('.ays-survey-message-vars-box,.ays-survey-subtitle-main-box').length != 0){
                
            } 
            else{
                $(document).find(".ays-survey-message-vars-box .ays-survey-message-vars-data,.ays-survey-subtitle-main-box .ays-survey-surveys-data").hide('fast');
            }
            // 
         });

        $(document).find(".ays-survey-go-to-surveys").on("click" , function(e){
            e.preventDefault();
            var confirmRedirect = window.confirm('Are you sure you want to redirect to another survey? Note that the changes made in this survey will not be saved.');
            if(confirmRedirect){
                window.location = $(this).attr("href");
            }
        });

        $(document).find('.ays-survey-message-vars-each-data').on('click', function(e){
            var messageVar = $(this).find(".ays-survey-message-vars-each-var").val();
            var mainParent = $(this).parents('.ays-survey-box-for-mv');
            var dataTMCE   = mainParent.find('.ays-survey-message-vars-data').attr('data-tmce');
            
            if ( mainParent.find("#wp-"+dataTMCE+"-wrap").hasClass("tmce-active") ){
                window.tinyMCE.get(dataTMCE).setContent( window.tinyMCE.get(dataTMCE).getContent() + messageVar + " " );
            }else{
                mainParent.find('#'+dataTMCE).append( " " + messageVar + " ");
            }
        });
        /* */

        $(document).find('.ays-survey-add-new-button-video').on('click', function(e){
            $(document).find(".page-title-action").trigger("click");
        });

        $(document).find('.ays-survey-aysDropdown-answer-view').on('change', function(e){
            var answerViewAligne = $(document).find('.ays-survey-aysDropdown-answer-view-alignment');
            answerViewAligne.aysDropdown('destroy');
            var answerViewType = $(this).aysDropdown('get value');
            
            var dataObj = {
                'list': [
                    {
                        name     : 'Left',
                        value    : 'flex-start',
                        selected : true
                    },
                    {
                        name     : 'Right',
                        value    : 'flex-end',
                    },
                    {
                        name     : 'Center',
                        value    : 'center',
                    }
                ],
                'grid': [
                    {
                        name     : 'Space around',
                        value    : 'space-around',
                        selected : true
                    },
                    {
                        name     : 'Space between',
                        value    : 'space-between',
                    },
                    {
                        name     : 'Left',
                        value    : 'flex-start',
                    },
                    {
                        name     : 'Right',
                        value    : 'flex-end',
                    },
                    {
                        name     : 'Center',
                        value    : 'center',
                    }
                ]
            }
            answerViewAligne.aysDropdown({
                values:  dataObj[answerViewType]
                });
        });

        $(document).on('click' ,'.ays-survey-slider-list-center-toggle', function(){
            var mainParent = $(this).parents('.ays-survey-question-range-length-label');
            var dataToggleType = $(this).attr('data-toggle-type');
            if(dataToggleType == 'seperatly'){
                $(this).attr('data-toggle-type' , 'combined');
            }
            else{
                $(this).attr('data-toggle-type' , 'seperatly');
            }
            dataToggleType = $(this).attr('data-toggle-type');
            mainParent.find('label[for*="ays-survey-slider-list-calculation-'+dataToggleType+'-type"]').trigger('click');
        } );

        $(document).on('change', 'input[name="ays_survey_show_submission_results"]', function (e) {
            var _this = $(this);
            var val   = _this.val();
            
            if ( val == 'summary' ) {
                $(document).find('.ays-survey-show-current-user-results').prev('hr').show(250);
                $(document).find('.ays-survey-show-current-user-results').show(250);
            } else if ( val == 'individual' ) {
                $(document).find('.ays-survey-show-current-user-results').prev('hr').hide(250);
                $(document).find('.ays-survey-show-current-user-results').hide(250);
            }
        });

        /* FRONTEND REQUEST START */

        $(document).on('click', '.ays_survey_approve_button',function(){
			var approvedId = $(this).data('id');
			var $_this = $(this);
            $.ajax({
				url: SurveyMakerAdmin.ajaxUrl,
                method: 'POST',
                dataType: 'json',
                data:{
                    action: 'ays_survey_admin_ajax',
                    function: 'ays_survey_approve_front_requests',
					approved_id : approvedId
				},
                success:function(response){
					if (response.status) {
						var goToLink = '<a href="?page=survey-maker&amp;action=edit&amp;id='+response.survey_id+'" target="_blank">Go to Survey</a>';
						parent = $_this.parent();
						parent.append(goToLink);
                        $_this.parents('tr').find('td.column-status').html('Published');
						$_this.remove();
					}else{
						swal.fire({
							type: 'info',
							html: "<h2>Can't load resource.</h2><br><h6>Maybe something went wrong.</h6>"
						});
					}
                }
            });
		});
        
        $(document).on('click', 'body', function(e){
            if($(e.target).hasClass('ays-modal')){
                $(e.target).find(".ays-close").trigger("click");
            }
        });
        /* FRONTEND REQUEST START */

        // Check new added Survey start
        var createdNewSurvey = getCookie('ays_survey_created_new');
        if(createdNewSurvey && createdNewSurvey > 1){
            var url = new URL(window.location.href);

            // Get a specific GET parameter by name
            var parameterValue = url.searchParams.get("action");
            var htmlContent = parameterValue && parameterValue == 'edit' ? '' : '<p style="margin-top:1rem;">For more detailed configuration visit <a href="admin.php?page=survey-maker&action=edit&id=' + createdNewSurvey + '">edit survey page</a>.</p>';
            swal({
                title: '<strong>Great job</strong>',
                type: 'success',
                html: '<p>You can use this shortcode to show your survey.</p><input type="text" id="ays-survey-create-new" onClick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_survey id=\'' + createdNewSurvey + '\']" />' + htmlContent,
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="ays_fa ays_fa_thumbs_up"></i> Great!',
                confirmButtonAriaLabel: 'Thumbs up, great!',
            });
            deleteCookie('ays_survey_created_new');
        }
        // Check new added Survey end
        

        $(document).find('input[type="submit"]#doaction, input[type="submit"]#doaction2').on('click', function(e) {
            showConfirmationIfDelete(e);
        });

            // Pro features start
            $(document).find(".ays-pro-features-v2-upgrade-button").hover(function() {
                // Code to execute when the mouse enters the element
                var unlockedImg = "Unlocked_24_24.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-upgrade-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Locked_24x24.svg", unlockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
                }, function() {
                
                var lockedImg = "Locked_24x24.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-upgrade-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Unlocked_24_24.svg", lockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
                });

            $(document).find(".ays-pro-features-v2-video-button").hover(function() {
                // Code to execute when the mouse enters the element
                var unlockedImg = "Video_24x24_Hover.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-video-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Video_24x24.svg", unlockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
                }, function() {
                
                var lockedImg = "Video_24x24.svg";
                var imgBox = $(this).find(".ays-pro-features-v2-video-icon");
                var imgUrl = imgBox.attr("data-img-src");
                var newString = imgUrl.replace("Video_24x24_Hover.svg", lockedImg);
                
                imgBox.css("background-image", 'url(' + newString + ')');
                imgBox.attr("data-img-src", newString);
            });

            $(document).on('click', '.ays-pro-features-v2-video-button,.ays-survey-question-actions-pro-button', function(e){
                e.preventDefault();
                if($(this).hasClass('ays-survey-question-actions-pro-button')){
                    var _thisDataParent = $(this);
                }
                else{
                    var _thisDataParent = $(this).parents('.ays-pro-features-v2-main-box').find('.ays-pro-pro-features-popup');
                }
                var popupModal = $(document).find('#pro-features-popup-modal');

                // Get option Data
                var optionTitle = _thisDataParent.attr('data-option-title');
                var optionDesc  =  _thisDataParent.attr('data-option-text');
                var optionVideoUrl =  _thisDataParent.attr('data-video-url');
    
                var popupModalTitleBox        = popupModal.find('.pro-features-popup-modal-right-box-title');
                var popupModalTitleContent    = popupModal.find('.pro-features-popup-modal-right-box-content');
                popupModalTitleBox.html(optionTitle);
                popupModalTitleContent.html(optionDesc);
    
    
                var leftSection  = popupModal.find('.ays-modal-body .pro-features-popup-modal-left-section');
    
                if ( typeof optionVideoUrl != "undefined" && optionVideoUrl != "") {
                    var videoID = ays_youtube_parser(optionVideoUrl);
                    var iframeHTML = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+ videoID +'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>';
    
                    leftSection.html(iframeHTML);
                }
    
                popupModal.aysModal('show_flex');
            });
            
        // Pro features end


    });

    
    function setBubble(range, bubble) {
        var val = range.value;
        var min = range.min ? range.min : 0;
        var max = range.max ? range.max : 100;
        var newVal = Number( ( ( val - min ) * 100 ) / ( max - min ) );
        bubble.innerHTML = val;

        // Sorta magic numbers based on size of the native UI thumb
        bubble.style.left = 'calc( ' +  newVal + '% + ' + ( 9 - newVal * 0.18 ) + 'px )';
    }
    
    var questionsClicked = false;
    function makeAllQuestionsRequired(button, inSection){
        var getButtonDataFlag = button.attr("data-flag");
        var setButtonDataFlag = false;
        var thisCheckbox =  button.find(".make-questions-required-checkbox");
        var requiredInputs = jQuery(document).find("#tab1 .ays-survey-input-required-question.ays-switch-checkbox");
        if(inSection){            
            thisCheckbox = button.parent().find(".make-questions-required-checkbox");
            requiredInputs = button.parents('.ays-survey-section-box').find(".ays-survey-input-required-question.ays-switch-checkbox");
        }

        if(getButtonDataFlag == "off"){
            setButtonDataFlag = false;
            button.attr("data-flag" , "on");
            button.find("img.ays-survey-required-section-img").show();
        }
        else{
            setButtonDataFlag = true;
            button.attr("data-flag" , "off");            
            button.find("img.ays-survey-required-section-img").hide();
        }
        thisCheckbox.prop("checked", !setButtonDataFlag);

        if(thisCheckbox.prop("checked")){
            requiredInputs.prop("checked" , true);
        }
        else{
            requiredInputs.prop("checked" , false);
        }

    }

    function enableAnswerPoints (button) {
        var thisCheckbox =  $(document).find(".enable-answer-points-checkbox");
        var pointContainers = jQuery(document).find("#tab1 .ays-survey-answer-point");

        if(button.attr("data-flag") == "off"){
            button.attr("data-flag" , "on");
            thisCheckbox.prop("checked", true);
        } else {
            button.attr("data-flag" , "off");            
            thisCheckbox.prop("checked", false);
        }

        if (thisCheckbox.prop("checked")) {
            pointContainers.removeClass("display_none");
        } else {
            pointContainers.addClass("display_none");
        }
    }    

    function showConfirmationIfDelete(e) {
        var $el = $(e.target);
        var elParent = $el.parent();
        var actionSelect = elParent.find('select[name="action"]');
        var action = actionSelect.val();

        if (action === 'bulk-delete') {
            e.preventDefault();
            var confirmDelete = confirm(SurveyMakerAdmin.deleteElementFromListTable);

            if (confirmDelete) {
                var form = $el.closest('form');
                form.submit();
            }
        }
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for(let i = 0; i <ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') {
            c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
          }
        }
        return "";
    }

    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }




})( jQuery );
