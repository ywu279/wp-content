/**
 * Handles theme general events
 * 
 * @package LocalNews
 * @since 1.0.0
 */
jQuery(document).ready(function($) {
    "use strict"
    var ajaxUrl = localNewsObject.ajaxUrl, _wpnonce = localNewsObject._wpnonce, sttOption = localNewsObject.stt, stickeyHeader = localNewsObject.stickey_header;
    
    setTimeout(function() {
        $('body .ln_loading_box').hide();
    }, 3000);

    var lnrtl = false
    var lndir = "left"
    if ($('body').hasClass("rtl")) {
        lnrtl = true;
        lndir = "right";
    };
    
    // theme trigger modal close
    function lnclosemodal( elm, callback ) {
        $(document).mouseup(function (e) {
            var container = $(elm);
            if (!container.is(e.target) && container.has(e.target).length === 0) callback();
        });
    }

    // ticker news slider events
    var tc = $( ".ticker-news-wrap" );
    if( tc.length ) {
        var tcM = tc.find( ".ticker-item-wrap" ).marquee({
            duration: 15000,
            gap: 0,
            delayBeforeStart: 0,
            direction: lndir,
            duplicated: true,
            startVisible: true,
            pauseOnHover: true,
        });
        tc.on( "click", ".local-news-ticker-pause", function() {
            $(this).find( "i" ).toggleClass( "fa-pause fa-play" )
            tcM.marquee( "toggle" );
        })
    }
javascript: void(0);
    // search form and sidebar toggle trigger
    $( "#masthead" ).on( "click", ".search-trigger, .sidebar-toggle-trigger", function() {
        $(this).next().slideDown('slow');
        $(this).addClass('slideshow');
        //$('body').addClass('bodynoscroll');
    });
    $( "#masthead" ).on( "click", ".search-trigger.slideshow, .sidebar-toggle-trigger.slideshow", function() {
        $(this).next().slideUp('slow');
        $(this).removeClass('slideshow');
        //$('body').removeClass('bodynoscroll');
    });
    lnclosemodal( $( ".search-wrap" ), function () {
        $( ".search-wrap .search-trigger" ).removeClass( "slideshow" );
        $( ".search-form-wrap" ).slideUp();
    }); // trigger search close
    lnclosemodal( $( ".sidebar-toggle-wrap" ), function () {
        $( ".sidebar-toggle-wrap .sidebar-toggle-trigger" ).removeClass( "slideshow" );
        $( ".sidebar-toggle-wrap .sidebar-toggle" ).slideUp();
    }); // trigger htsidebar close

    // main banner slider events
    var bc = $( "#main-banner-section" );
    if( bc.length ) {
        var bic = bc.find( ".main-banner-slider" )
        var bAuto = bic.data( "auto" )
        var bArrows = bic.data( "arrows" )
        var bDots = bic.data( "dots" )
        bic.slick({
            dots: bDots,
            infinite: true,
            rtl: lnrtl,
            arrows: bArrows,
            autoplay: bAuto,
            nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
            prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
        });
    }

    // banner-tabs
    var bptc = bc.find( ".main-banner-tabs" )
    bptc.on( "click", ".banner-tabs li.banner-tab", function() {
        var _this = $(this), tabItem = _this.attr( "tab-item" );
        _this.addClass( "active" ).siblings().removeClass( "active" );
        bptc.find( '.banner-tabs-content div[tab-content="' + tabItem + '"]' ).addClass( "active" ).siblings().removeClass( "active" );
    })

    // main banner popular posts slider events
    var bpc = bc.find( ".popular-posts-wrap" );
    if( bpc.length ) {
        var bpcAuto = bpc.data( "auto" )
        var bpcArrows = bpc.data( "arrows" )
        var bpcVertical = bpc.data( "vertical" );
        if( bpcVertical) {
            bpc.slick({
                vertical: bpcVertical,
                slidesToShow: 4,
                dots: false,
                infinite: true,
                arrows: bpcArrows,
                autoplay: bpcAuto,
                nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
            })
        } else {
            bpc.slick({
                dots: false,
                infinite: true,
                arrows: bpcArrows,
                rtl: lnrtl,
                draggable: true,
                autoplay: bpcAuto,
                nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
            })
        }  
    }

    // news carousel events
    var nc = $( ".local-news-section .news-carousel .news-carousel-post-wrap" );
    if( nc.length ) {
        nc.each(function() {
            var _this = $(this)
            var ncDots= _this.data("dots") == '1'
            var ncLoop= _this.data("loop") == '1'
            var ncArrows= _this.data("arrows") == '1'
            var ncAuto  = _this.data("auto") == '1'
            var ncColumns  = _this.data("columns")
            _this.slick({
                dots: ncDots,
                infinite: ncLoop,
                arrows: ncArrows,
                autoplay: ncAuto,
                rtl: lnrtl,
                slidesToShow: ncColumns,
                nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
                responsive: [
                  {
                    breakpoint: 1100,
                    settings: {
                      slidesToShow: 3,
                    },
                  },
                  {
                    breakpoint: 768,
                    settings: {
                      slidesToShow: 2,
                    },
                  },
                  {
                    breakpoint: 640,
                    settings: {
                      slidesToShow: 1,
                    },
                  }
                ]
            });
        })
    }

    // Filter posts
     $( ".local-news-section .news-filter" ).each(function() {
        var $scope = $(this), $scopeOptions = $scope.data("args"), newTabs = $scope.find( ".filter-tab-wrapper" ), newTabsContent = $scope.find( ".filter-tab-content-wrapper" );
        newTabs.on( "click", ".tab-title", function() {
          var a = $(this), aT = a.data("tab")
          a.addClass( "isActive" ).siblings().removeClass( "isActive" );
          if( newTabsContent.find( ".tab-content.content-" + aT ).length < 1 ) {
            $scopeOptions.category_name = aT
            $.ajax({
                method: 'get',
                url: ajaxUrl,
                data: {
                    action: 'local_news_filter_posts_load_tab_content',
                    options : JSON.stringify( $scopeOptions ),
                    _wpnonce: _wpnonce
                },
                beforeSend: function() {
                    $scope.addClass( 'retrieving-posts' );
                },
                success : function(res) {
                    var parsedRes = JSON.parse(res)
                    if( parsedRes.loaded ) {
                        newTabsContent.append(parsedRes.posts)
                        $scope.removeClass( 'retrieving-posts' );
                    }
                },
                complete: function() {
                    newTabsContent.find( ".tab-content.content-" + aT ).show().siblings().hide()
                }
            })
          } else {
            newTabsContent.find( ".tab-content.content-" + aT ).show().siblings().hide()
          }
        })
    })

    // popular posts widgets
    var ppWidgets = $( ".local-news-widget-popular-posts" )
    ppWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_local_news_popular_posts_widget" ).attr( "id" ), parentWidgetContainer = $( "#" + parentWidgetContainerId )
        var ppWidget = parentWidgetContainer.find( ".popular-posts-wrap" );
        if( ppWidget.length > 0 ) {
            var ppWidgetAuto = ppWidget.data( "auto" )
            var ppWidgetArrows = ppWidget.data( "arrows" )
            var ppWidgetLoop = ppWidget.data( "loop" )
            var ppWidgetVertical = ppWidget.data( "vertical" )
            if( ppWidgetVertical == 'vertical' ) {
                ppWidget.slick({
                    vertical: true,
                    slidesToShow: 4,
                    dots: false,
                    infinite: ppWidgetLoop,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            } else {
                ppWidget.slick({
                    dots: false,
                    infinite: ppWidgetLoop,
                    rtl: lnrtl,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            }  
        }
    })

    // carousel posts widgets
    var cpWidgets = $( ".local-news-widget-carousel-posts" )
    cpWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_local_news_carousel_widget" ).attr( "id" ), parentWidgetContainer
        if( typeof parentWidgetContainerId != 'undefined' ) {
            parentWidgetContainer = $( "#" + parentWidgetContainerId )
            var ppWidget = parentWidgetContainer.find( ".carousel-posts-wrap" );
        } else {
            var ppWidget = _this.find( ".carousel-posts-wrap" );
        }
        if( ppWidget.length > 0 ) {
            var ppWidgetAuto = ppWidget.data( "auto" )
            var ppWidgetArrows = ppWidget.data( "arrows" )
            var ppWidgetLoop = ppWidget.data( "loop" )
            var ppWidgetVertical = ppWidget.data( "vertical" )
            if( ppWidgetVertical == 'vertical' ) {
                ppWidget.slick({
                    vertical: true,
                    dots: false,
                    infinite: ppWidgetLoop,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            } else {
                ppWidget.slick({
                    dots: false,
                    infinite: ppWidgetLoop,
                    rtl: lnrtl,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            }  
        }
    })

    // tabbed posts
    var tabpWidgets = $( ".local-news-tabbed-widget-tabs-wrap" )
    tabpWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_local_news_tabbed_posts_widget" ).attr( "id" ), parentWidgetContainer
        if( typeof parentWidgetContainerId != 'undefined' ) {
            parentWidgetContainer = $( "#" + parentWidgetContainerId )
            var tabpWidget = parentWidgetContainer.find( ".local-news-tabbed-widget-tabs-wrap" );
        } else {
            var tabpWidget = _this;
        }
        if( tabpWidget.length > 0 ) {
            tabpWidget.on( "click", ".tabbed-widget-tabs li.tabbed-widget", function() {
                var _this = $(this), tabItem = _this.attr( "tab-item" );
                _this.addClass( "active" ).siblings().removeClass( "active" );
                tabpWidget.find( '.widget-tabs-content div[tab-content="' + tabItem + '"]' ).addClass( "active" ).siblings().removeClass( "active" );
            })
        }
    })

    // stickey related posts
    if( '.single-related-posts-section-wrap' ) {
      $('.single .entry-footer').waypoint(function(direction) {  
            $('.single-related-posts-section-wrap.related_posts_popup').addClass('related_posts_popup_sidebar');
        }, { offset: + 50 });
    }
    $('.related_post_close').on('click',function(){
      $('.single .single-related-posts-section-wrap.related_posts_popup').removeClass('related_posts_popup_sidebar related_posts_popup');
    });

    // check for dark mode drafts
    if( localStorage.getItem( "themeMode" ) && localStorage.getItem( "themeMode" ) == "dark" ) {
        $( ".mode_toggle_wrap input" ).attr( "checked", true );
        if( ! $( "body" ).hasClass( "local_news_dark_mode" ) ) {
            $( "body" ).addClass( "local_news_dark_mode" );
            $( "body" ).removeClass( "ln_main_body" );
        }
    }
    // toggle theme mode
    $( ".mode_toggle_wrap" ).on( "click", function() {
        var _this = $(this)
        $( "body" ).toggleClass( "local_news_dark_mode" )
        if( _this.find( "input:checked" ).length > 0 && $( "body" ).hasClass( "local_news_dark_mode" ) ) {
            localStorage.setItem("themeMode", "dark");
            $("body").removeClass("ln_main_body");
        } else {
            localStorage.setItem( "themeMode", "light" );
            $("body").addClass("ln_main_body");
        }
    });


    // header sticky
    if( stickeyHeader ) {
        var lastScroll = 0;
        $(window).on('scroll',function() {  
            var scroll = $(window).scrollTop();
            if(scroll > 50){        
                if(lastScroll - scroll > 0) {
                    $(".main-header .menu-section").addClass("fixed_header");
                } else {
                    $(".main-header .menu-section").removeClass("fixed_header");
                }
                lastScroll = scroll;
            }else{
                $(".main-header .menu-section").removeClass("fixed_header");
            }
        });
    }

    // back to top script
    if( sttOption && $( "#ln-scroll-to-top" ).length ) {
        var scrollContainer = $( "#ln-scroll-to-top" );
        $(window).scroll(function() {
            if ( $(this).scrollTop() > 800 ) {
                scrollContainer.addClass('show');
            } else {
                scrollContainer.removeClass('show');
            }
        });
        scrollContainer.click(function(event) {
            event.preventDefault();
            // Animate the scrolling motion.
            $("html, body").animate({scrollTop:0},"slow");
        });
    }
})