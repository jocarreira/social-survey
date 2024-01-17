(function($) {
    'use strict';

    function AysSurveyRecaptchaPlugin(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.surveyObject = undefined;
        this.finishButton = undefined;
        this.dbOptions = undefined;

        this.init();

        return this;
    }

    AysSurveyRecaptchaPlugin.prototype.init = function() {
        var _this = this;

        var uniqueKey = _this.$el.find('.'+ _this.htmlClassPrefix + 'g-recaptcha').data('uniqueKey');

        if( typeof window.aysSurveyRecaptchaObj != 'undefined' ){
            if(typeof window.aysSurveyRecaptchaObj[ uniqueKey ] != 'undefined' ){
                _this.dbOptions = JSON.parse( window.atob( window.aysSurveyRecaptchaObj[ uniqueKey ] ) );
            }
        }

        _this.setEvents();
    };

    AysSurveyRecaptchaPlugin.prototype.setEvents = function(e){
        var _this = this;

        _this.$el.find('form.' + _this.htmlClassPrefix + 'form').on('afterSurveySubmission', function(e){
            _this.surveyObject = e.detail._this;
            _this.finishButton = e.detail.thisButton;
            var form = _this.surveyObject.$el.find('form');

            var surveyRecaptcha = _this.surveyObject.dbOptions.options.enable_recaptcha && _this.surveyObject.dbOptions.options.enable_recaptcha == "on" ? true : false;

            var formCaptchaValidation = false;
            if( surveyRecaptcha ){
                formCaptchaValidation = form.attr('data-recaptcha-validate') && form.attr('data-recaptcha-validate') == 'true' ? true : false;
            }

            if( formCaptchaValidation === false ) {
                _this.initRecaptcha($(this));
            }else{
                e.preventDefault();
            }

            _this.surveyObject.activeStep(form.find('.' + _this.htmlClassPrefix + 'section.active-section .' + _this.htmlClassPrefix + 'finish-button'), 'next', null);

            _this.surveyObject.aysAnimateStep('fade', _this.surveyObject.current_fs, _this.surveyObject.next_fs);

            _this.surveyObject.goTo();
        });
    }

    AysSurveyRecaptchaPlugin.prototype.initRecaptcha = function( section ) {
        var _this = this;

        var captchaContainer = section.find( '.' + _this.htmlClassPrefix + 'recaptcha-wrap' ),
            captcha          = section.find( '.' + _this.htmlClassPrefix + 'g-recaptcha' );
            var captchaSiteKey = _this.dbOptions.siteKey === '' ? null : _this.dbOptions.siteKey,
            captchaID      = _this.htmlClassPrefix + 'recaptcha-' + Date.now(),
            apiVar         = grecaptcha,
            theme          = _this.dbOptions.theme === '' ? null : _this.dbOptions.theme;
            
        captcha.attr('id', captchaID);
        if( captchaSiteKey ) {
            try {
                var options = {
                    'sitekey': captchaSiteKey,
                    'expired-callback': function () {
                        _this.setRecaptchaChecked(false);
                        apiVar.reset(opt_widget_id);
                    },
                    'callback': function (response) {
                        if (!response) {
                            _this.recaptchaErrorCallback($('#' + captchaID));
                        } else {
                            _this.recaptchaSuccessCallback($('#' + captchaID));
                        }
                    },
                    // 'error-callback': function () {
                    //     _this.setRecaptchaChecked(false);
                    //     apiVar.reset(opt_widget_id);
                    // }
                };

                if( theme ) {
                    options.theme = theme;
                }

                var opt_widget_id = apiVar.render( captchaID, options );
                captcha.attr('data-widget-id', opt_widget_id);
            } catch (error) {}
            _this.surveyDispatchEvent(document, "aysSurveyRecaptchaLoaded", true);
        }
    }

    AysSurveyRecaptchaPlugin.prototype.recaptchaErrorCallback = function (el) {
        var _this = this;
        _this.setRecaptchaChecked( false );
        _this.surveyDispatchEvent(document, "aysSurveyRecaptchaError", true);
        var err = el.parents('.' + _this.htmlClassPrefix + 'section').find('.' + _this.htmlClassPrefix + 'g-recaptcha-hidden-error');
        err.show();
        return false;
    };

    AysSurveyRecaptchaPlugin.prototype.recaptchaSuccessCallback = function (el) {
        var _this = this;
        _this.setRecaptchaChecked( true );
        _this.surveyDispatchEvent(document, "aysSurveyRecaptchaSuccess", true);
        var err = el.parents('.' + _this.htmlClassPrefix + 'section').find('.' + _this.htmlClassPrefix + 'g-recaptcha-hidden-error');
        err.hide();
        setTimeout(function (){
            _this.finishButton.trigger('click');
            el.parents('.' + _this.htmlClassPrefix + 'section').remove();
        }, 500);
    };

    AysSurveyRecaptchaPlugin.prototype.setRecaptchaChecked = function ( isChceked ) {
        var _this = this;
        var form = _this.$el.find('form');
        form.attr('data-recaptcha-validate', isChceked ? 'true' : 'false');
    }

    AysSurveyRecaptchaPlugin.prototype.surveyDispatchEvent = function (el, ev, custom) {
        var e = document.createEvent(custom ? "CustomEvent" : "HTMLEvents");
        custom ? e.initCustomEvent(ev, true, true, false) : e.initEvent(ev, true, true);
        el.dispatchEvent(e);
    };

    $.fn.AysSurveyRecaptcha = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyRecaptcha')) {
                $.data(this, 'AysSurveyRecaptcha', new AysSurveyRecaptchaPlugin(this, options));
            } else {
                try {
                    $(this).data('AysSurveyRecaptcha').init();
                } catch (err) {
                    console.error('AysSurveyRecaptcha has not initiated properly');
                }
            }
        });
    };

    $(document).find('.ays-survey-container').AysSurveyRecaptcha();
})(jQuery);
