window.QuestZenit = {};

QuestZenit.Carousel = function() {
    $(".js-scrollableQuests").carousel();
};

QuestZenit.LightBox = function() {
    var docElem = window.document.documentElement, didScroll, scrollPosition;
    function noScrollFn() {
        window.scrollTo(scrollPosition ? scrollPosition.x : 0, scrollPosition ? scrollPosition.y : 0);
    }
    function noScroll() {
        window.removeEventListener("scroll", scrollHandler);
        window.addEventListener("scroll", noScrollFn);
    }
    function scrollFn() {
        window.addEventListener("scroll", scrollHandler);
    }
    function canScroll() {
        window.removeEventListener("scroll", noScrollFn);
        scrollFn();
    }
    function scrollHandler() {
        if (!didScroll) {
            didScroll = true;
            setTimeout(function() {
                scrollPage();
            }, 60);
        }
    }
    function scrollPage() {
        scrollPosition = {
            x: window.pageXOffset || docElem.scrollLeft,
            y: window.pageYOffset || docElem.scrollTop
        };
        didScroll = false;
    }
    scrollFn();
    var el = document.querySelector(".morph-button");
    new UIMorphingButton(el, {
        closeEl: ".icon-close",
        onBeforeOpen: function() {
            noScroll();
        },
        onAfterOpen: function() {
            canScroll();
            classie.addClass(document.body, "noscroll");
            classie.addClass(el, "scroll");
        },
        onBeforeClose: function() {
            classie.removeClass(document.body, "noscroll");
            classie.removeClass(el, "scroll");
            noScroll();
        },
        onAfterClose: function() {
            canScroll();
        }
    });
};

QuestZenit.Render = function() {
    var renderData = {
        players: [ {
            cash: 5e4,
            "class": "green",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "lightblue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 2e3,
            "class": "blue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e3,
            "class": "torquous",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "orange",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 47e3,
            "class": "red",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 5e4,
            "class": "green",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "lightblue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 2e3,
            "class": "blue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e3,
            "class": "torquous",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "orange",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 47e3,
            "class": "red",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 5e4,
            "class": "green",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "lightblue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 2e3,
            "class": "blue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e3,
            "class": "torquous",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "orange",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 47e3,
            "class": "red",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 5e4,
            "class": "green",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "lightblue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 2e3,
            "class": "blue",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e3,
            "class": "torquous",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 1e5,
            "class": "orange",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        }, {
            cash: 47e3,
            "class": "red",
            date: "12.07.2014",
            name: "Черемушкин Иван"
        } ],
        quests: [ {
            title: "Победоносный Кришито",
            image: "img/quests/quest_1.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_2.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_3.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_1.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_2.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_3.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_1.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_2.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_3.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_1.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_2.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        }, {
            title: "Победоносный Кришито",
            image: "img/quests/quest_3.jpg",
            price: "400000",
            description: 'Нападающий сборной Бразилии  и ФК "Зенит" Xалк одевается в костюм талисмана Зенита и фотографируется с прохожими на Невском',
            link: "#"
        } ]
    };
    var render = function(data, destination) {
        var template = $(destination).html();
        Mustache.parse(template);
        return Mustache.render(template, data);
    };
    $("#main-render").html(render(renderData, "#mainTpl"));
    $("#latestQuests").html(render(renderData, "#otherTpl"));
};

QuestZenit.Social = {
    getUrl: {
        twitter: function(o) {
            var ar = [];
            ar.push("https://twitter.com/intent/tweet?");
            if (o.title) {
                ar.push("text=", encodeURIComponent(o.title), "&");
            }
            ar.push("url=", encodeURIComponent(o.url));
            return ar.join("");
        },
        vk: function(o) {
            var ar = [];
            ar.push("http://vk.com/share.php?");
            ar.push("url=", encodeURIComponent(o.url));
            if (o.title) {
                ar.push("&title=", encodeURIComponent(o.title));
            }
            if (o.description) {
                ar.push("&description=", encodeURIComponent(o.description));
            }
            if (o.image) {
                ar.push("&image=", encodeURIComponent(o.image));
            }
            ar.push("&noparse=true");
            return ar.join("");
        },
        fb: function(o) {
            var ar = [];
            ar.push("http://www.facebook.com/sharer.php?s=100");
            ar.push("&p[url]=", encodeURIComponent(o.url));
            if (o.title) {
                ar.push("&p[title]=", encodeURIComponent(o.title));
            }
            if (o.description) {
                ar.push("&p[summary]=", encodeURIComponent(o.description));
            }
            if (o.image) {
                ar.push("&p[images][0]=", encodeURIComponent(o.image));
            }
            return ar.join("");
        },
        ok: function(o) {
            var ar = [];
            ar.push("http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1");
            ar.push("&st._surl=", encodeURIComponent(o.url));
            ar.push("&st.comments=", encodeURIComponent(o.description));
            return ar.join("");
        },
        gp: function(o) {
            var ar = [];
            ar.push("https://plus.google.com/share");
            ar.push("?url=", encodeURIComponent(o.url));
            return ar.join("");
        }
    },
    updateBlock: function($block, url) {
        url = url || $block.data("url");
    },
    prepareLinks: function($block, params) {
        $block.on("click", ".js-shareButton", function() {
            window.open(QuestZenit.Social.getUrl[this.getAttribute("data-type")](params), "", "toolbar=0,status=0,width=626,height=436");
        });
    },
    updateStatic: function() {
        $(".js-shareBlock").each(function() {
            QuestZenit.Social.updateBlock($(this));
        });
    }
};

QuestZenit.TimeLine = function() {
    var finalPrice = 0;
    var currentPrice = 0;
    var $container = $("#mainQuest"), $list = $container.find(".js-paymentList"), status = $container.attr("data-status"), destination = parseInt($container.find(".js-destination").attr("data-destination")), total = parseInt($container.find(".js-totalCash").attr("data-total")), buttonWidth, $to = $container.find(".js-destinationButton"), x = "", $button = $container.find(".js-totalButton");
    if (destination >= total) {
        buttonWidth = total / destination * 100;
        x = "";
    } else {
        buttonWidth = destination / total * 100;
        x = "yes";
    }
    setTimeout(function() {
        gameStatus();
    }, 3e3);
    var gameStatus = function() {
        if (status === "online") {
            timeline(80);
            $list.css("padding-right", "20%");
        } else {
            timeline(100);
        }
    };
    var timeline = function(persent) {
        if (x === "yes") {
            $button.css({
                left: persent + "%",
                opacity: 1
            });
            $to.css("left", buttonWidth + "%").addClass("m-active");
        } else {
            $button.css({
                left: buttonWidth + "%",
                opacity: 1
            });
        }
        $(".js-showEach").each(function() {
            var $this = $(this), prise = $this.attr("data-size"), width;
            if (destination >= total) {
                width = prise / destination * 100;
            } else {
                width = prise / total * 100;
            }
            $this.css("width", width + "%");
        });
    };
    var b = "### ### ### ###";
    function ConvertNumber(a, b) {
        var tail = format.lastIndexOf(".");
        number = number.toString();
        tail = tail > -1 ? format.substr(tail) : "";
        if (tail.length > 0) {
            if (tail.charAt(1) == "#") {
                tail = number.substr(number.lastIndexOf("."), tail.length);
            }
        }
        number = number.replace(/\..*|[^0-9]/g, "").split("");
        format = format.replace(/\..*/g, "").split("");
        for (var i = format.length - 1; i > -1; i--) {
            if (format[i] == "#") {
                format[i] = number.pop();
            }
        }
        return number.join("") + format.join("") + tail;
    }
};

$(function() {
    $.fn.carousel = function(color) {
        var $container = $(this), $child = $container.children(), $allChildrens = $child.find("li"), elementHeight = $container.height(), scrolledHeight = $child.height(), containerHeight = $child.height(), difference = 0, containerW = 0, elementWidth = 0, time, modulDif, modulMargin, width = 0, margin = 0;
        $allChildrens.each(function() {
            width = $(this).outerWidth() + width;
        });
        var permanentW = width;
        $child.css("width", width + "px");
        $container.append('<span class="icon icon-arrow_left quest-prev js-prev"><span class="icon icon-arrow_left-empty"></span></span><span class="icon quest-next icon-arrow_right js-next"><span class="icon icon-arrow_right-empty"></span></span>');
        var $next = $container.find(".js-next");
        var $prev = $container.find(".js-prev");
        resizedw();
        $next.on("click", function(e) {
            var $this = $(this);
            margin = margin - elementWidth;
            modulMargin = Math.abs(margin);
            $child.css("margin-left", margin + "px");
            if (modulMargin >= modulDif) {
                $this.hide();
            } else {
                $prev.show();
            }
        });
        $prev.on("click", function(e) {
            var $this = $(this);
            margin = margin + elementWidth;
            modulMargin = Math.abs(margin);
            $child.css("margin-left", margin + "px");
            if (modulMargin < elementWidth) {
                $this.hide();
            } else {
                $next.show();
            }
        });
        function resizedw() {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName("body")[0].clientWidth;
            if (x < 2100) {
                width = permanentW / 2;
            } else {
                width = permanentW;
            }
            containerW = $container.outerWidth();
            difference = containerW - width;
            modulDif = Math.abs(difference);
            $child.css("width", width + "px");
            elementWidth = $allChildrens.first().outerWidth();
            $child.css("margin-left", 0);
            $prev.hide();
            $next.show();
        }
        $(window).on("resize", function() {
            clearTimeout(time);
            time = setTimeout(resizedw, 200);
        });
    };
    QuestZenit.Render();
    QuestZenit.TimeLine();
    QuestZenit.Social.updateStatic();
    QuestZenit.Carousel();
});
//# sourceMappingURL=main.map