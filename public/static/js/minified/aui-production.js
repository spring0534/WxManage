function progress(a, b) {
    var c = a * b.width() / 100;
    b.find(".progressbar-value").animate({
        width: c
    },
    1200)
}
function SmartWizard(a, b) {
    this.target = a,
    this.options = b,
    this.curStepIdx = b.selected,
    this.steps = $(a).children("ul").children("li").children("a"),
    this.contentWidth = 0,
    this.msgBox = $('<div class="msgBox"><div class="content"></div><a href="#" class="close">X</a></div>'),
    this.elmStepContainer = $("<div></div>").addClass("stepContainer"),
    this.loader = $("<div>Loading</div>").addClass("loader"),
    this.buttons = {
        next: $('<a><span class="button-content">' + b.labelNext + "</span></a>").attr("href", "#").addClass("buttonNext btn medium primary-bg"),
        previous: $('<a><span class="button-content">' + b.labelPrevious + "</span></a>").attr("href", "#").addClass("btn medium ui-state-default buttonPrevious"),
        finish: $('<a><span class="button-content">' + b.labelFinish + "</span></a>").attr("href", "#").addClass("btn medium ui-state-default buttonFinish")
    };
    var c = function(b) {
        var c = $("<div></div>").addClass("actionBar");
        c.append(b.msgBox),
        $(".close", b.msgBox).click(function() {
            return b.msgBox.fadeOut("normal"),
            !1
        });
        var e = b.target.children("div");
        if (0 == b.target.children("ul").length) {
            var g = $("<ul/>");
            a.prepend(g),
            e.each(function(a, b) {
                var c = $(b).first().children(".StepTitle").text(),
                d = $(b).attr("id");
                void 0 == d && (d = "step-" + (a + 1), $(b).attr("id", d));
                var e = $("<span/>").addClass("stepDesc").text(c),
                f = $("<li></li>").append($("<a></a>").attr("href", "#" + d).append($("<label></label>").addClass("stepNumber").text(a + 1)).append(e));
                g.append(f)
            }),
            b.steps = $(a).children("ul").children("li").children("a")
        }
        b.target.children("ul").addClass("anchor"),
        e.addClass("content"),
        b.options.errorSteps && b.options.errorSteps.length > 0 && $.each(b.options.errorSteps,
        function(a, c) {
            b.setError({
                stepnum: c,
                iserror: !0
            })
        }),
        b.elmStepContainer.append(e),
        c.append(b.loader),
        b.target.append(b.elmStepContainer),
        b.options.includeFinishButton && c.append(b.buttons.finish),
        c.append(b.buttons.next).append(b.buttons.previous),
        b.target.append(c),
        this.contentWidth = b.elmStepContainer.width(),
        $(b.buttons.next).click(function() {
            return b.goForward(),
            !1
        }),
        $(b.buttons.previous).click(function() {
            return b.goBackward(),
            !1
        }),
        $(b.buttons.finish).click(function() {
            if (!$(this).hasClass("disabled")) if ($.isFunction(b.options.onFinish)) {
                var a = {
                    fromStep: b.curStepIdx + 1
                };
                if (!b.options.onFinish.call(this, $(b.steps), a)) return ! 1
            } else {
                var c = b.target.parents("form");
                c && c.length && c.submit()
            }
            return ! 1
        }),
        $(b.steps).bind("click",
        function() {
            if (b.steps.index(this) == b.curStepIdx) return ! 1;
            var a = b.steps.index(this),
            c = b.steps.eq(a).attr("isDone") - 0;
            return 1 == c && f(b, a),
            !1
        }),
        b.options.keyNavigation && $(document).keyup(function(a) {
            39 == a.which ? b.goForward() : 37 == a.which && b.goBackward()
        }),
        d(b),
        f(b, b.curStepIdx)
    },
    d = function(a) {
        a.options.enableAllSteps ? ($(a.steps, a.target).removeClass("selected").removeClass("disabled").addClass("selected"), $(a.steps, a.target).attr("isDone", 1)) : ($(a.steps, a.target).removeClass("selected").removeClass("selected").addClass("disabled"), $(a.steps, a.target).attr("isDone", 0)),
        $(a.steps, a.target).each(function(b) {
            $($(this).attr("href").replace(/^.+#/, "#"), a.target).hide(),
            $(this).attr("rel", b + 1)
        })
    },
    e = function(a, b) {
        return $($(b, a.target).attr("href").replace(/^.+#/, "#"), a.target)
    },
    f = function(a, b) {
        var c = a.steps.eq(b),
        d = a.options.contentURL,
        f = a.options.contentURLData,
        h = c.data("hasContent"),
        i = b + 1;
        if (d && d.length > 0) if (a.options.contentCache && h) g(a, b);
        else {
            var j = {
                url: d,
                type: a.options.ajaxType,
                data: {
                    step_number: i
                },
                dataType: "text",
                beforeSend: function() {
                    a.loader.show()
                },
                error: function() {
                    a.loader.hide()
                },
                success: function(d) {
                    a.loader.hide(),
                    d && d.length > 0 && (c.data("hasContent", !0), e(a, c).html(d), g(a, b))
                }
            };
            f && (j = $.extend(j, f(i))),
            $.ajax(j)
        } else g(a, b)
    },
    g = function(a, b) {
        var c = a.steps.eq(b),
        d = a.steps.eq(a.curStepIdx);
        if (b != a.curStepIdx && $.isFunction(a.options.onLeaveStep)) {
            var f = {
                fromStep: a.curStepIdx + 1,
                toStep: b + 1
            };
            if (!a.options.onLeaveStep.call(a, $(d), f)) return ! 1
        }
        a.elmStepContainer.height(e(a, c).outerHeight());
        var g = a.curStepIdx;
        if (a.curStepIdx = b, "slide" == a.options.transitionEffect) e(a, d).slideUp("fast",
        function() {
            e(a, c).slideDown("fast"),
            h(a, d, c)
        });
        else if ("fade" == a.options.transitionEffect) e(a, d).fadeOut("fast",
        function() {
            e(a, c).fadeIn("fast"),
            h(a, d, c)
        });
        else if ("slideleft" == a.options.transitionEffect) {
            var i = null,
            j = 0;
            b > g ? (i = a.elmStepContainer.width() + 10, nextElmLeft2 = 0, j = 0 - e(a, d).outerWidth()) : (i = 0 - e(a, c).outerWidth() + 20, nextElmLeft2 = 0, j = 10 + e(a, d).outerWidth()),
            b == g ? (i = $($(c, a.target).attr("href"), a.target).outerWidth() + 20, nextElmLeft2 = 0, j = 0 - $($(d, a.target).attr("href"), a.target).outerWidth()) : $($(d, a.target).attr("href"), a.target).animate({
                left: j
            },
            "fast",
            function() {
                $($(d, a.target).attr("href"), a.target).hide()
            }),
            e(a, c).css("left", i).show().animate({
                left: nextElmLeft2
            },
            "fast",
            function() {
                h(a, d, c)
            })
        } else e(a, d).hide(),
        e(a, c).show(),
        h(a, d, c);
        return ! 0
    },
    h = function(a, b, c) {
        if ($(b, a.target).removeClass("selected"), $(b, a.target).addClass("selected"), $(c, a.target).removeClass("disabled"), $(c, a.target).removeClass("selected"), $(c, a.target).addClass("selected"), $(c, a.target).attr("isDone", 1), i(a), $.isFunction(a.options.onShowStep)) {
            var d = {
                fromStep: parseInt($(b).attr("rel")),
                toStep: parseInt($(c).attr("rel"))
            };
            if (!a.options.onShowStep.call(this, $(c), d)) return ! 1
        }
        if (a.options.noForwardJumping) for (var e = a.curStepIdx + 2; e <= a.steps.length; e++) a.disableStep(e)
    },
    i = function(a) {
        a.options.cycleSteps || (0 >= a.curStepIdx ? ($(a.buttons.previous).addClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.previous).hide()) : ($(a.buttons.previous).removeClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.previous).show()), a.steps.length - 1 <= a.curStepIdx ? ($(a.buttons.next).addClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.next).hide()) : ($(a.buttons.next).removeClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.next).show())),
        a.options.includeFinishButton && (!a.steps.hasClass("disabled") || a.options.enableFinishButton ? ($(a.buttons.finish).removeClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.finish).show()) : ($(a.buttons.finish).addClass("disabled"), a.options.hideButtonsOnDisabled && $(a.buttons.finish).hide()))
    };
    SmartWizard.prototype.goForward = function() {
        var a = this.curStepIdx + 1;
        if (this.steps.length <= a) {
            if (!this.options.cycleSteps) return ! 1;
            a = 0
        }
        f(this, a)
    },
    SmartWizard.prototype.goBackward = function() {
        var a = this.curStepIdx - 1;
        if (0 > a) {
            if (!this.options.cycleSteps) return ! 1;
            a = this.steps.length - 1
        }
        f(this, a)
    },
    SmartWizard.prototype.goToStep = function(a) {
        var b = a - 1;
        b >= 0 && b < this.steps.length && f(this, b)
    },
    SmartWizard.prototype.enableStep = function(a) {
        var b = a - 1;
        if (b == this.curStepIdx || 0 > b || b >= this.steps.length) return ! 1;
        var c = this.steps.eq(b);
        $(c, this.target).attr("isDone", 1),
        $(c, this.target).removeClass("disabled").removeClass("selected").addClass("selected")
    },
    SmartWizard.prototype.disableStep = function(a) {
        var b = a - 1;
        if (b == this.curStepIdx || 0 > b || b >= this.steps.length) return ! 1;
        var c = this.steps.eq(b);
        $(c, this.target).attr("isDone", 0),
        $(c, this.target).removeClass("selected").removeClass("selected").addClass("disabled")
    },
    SmartWizard.prototype.currentStep = function() {
        return this.curStepIdx + 1
    },
    SmartWizard.prototype.showMessage = function(a) {
        $(".content", this.msgBox).html(a),
        this.msgBox.show()
    },
    SmartWizard.prototype.hideMessage = function() {
        this.msgBox.fadeOut("normal")
    },
    SmartWizard.prototype.showError = function(a) {
        this.setError(a, !0)
    },
    SmartWizard.prototype.hideError = function(a) {
        this.setError(a, !1)
    },
    SmartWizard.prototype.setError = function(a, b) {
        "object" == typeof a && (b = a.iserror, a = a.stepnum),
        b ? $(this.steps.eq(a - 1), this.target).addClass("error") : $(this.steps.eq(a - 1), this.target).removeClass("error")
    },
    SmartWizard.prototype.fixHeight = function() {
        var a = 0,
        b = this.steps.eq(this.curStepIdx),
        c = e(this, b);
        c.children().each(function() {
            $(this).is(":visible") && (a += $(this).outerHeight())
        }),
        c.height(a + 5),
        this.elmStepContainer.height(a + 20)
    },
    c(this)
}
function layoutFormatter() {
    setTimeout(function() {
        var a = $(window).height(),
        b = $("#page-header").height(),
        c = a - b;
        $("body").hasClass("boxed-layout") ? $("#page-sidebar").height(c + 36) : $("#page-sidebar").height(c)
    },
    499)
}
function themefromCookie() {
    var a = $.cookie("set-layout-theme");
    null == a ? $("#layout-theme").attr("href", statics + "/themes/minified/fides/color-schemes/dark-blue.min.css") : $("#layout-theme").attr("href", statics + "/themes/minified/fides/color-schemes/" + a + ".min.css")
}
function bgFromCookie() {
    var a = $.cookie("set-boxed-bg");
    $("body").hasClass("boxed-layout") && $("body").css("background", a)
} !
function(a, b) {
    function c(a) {
        var b = a.length,
        c = kb.type(a);
        return kb.isWindow(a) ? !1 : 1 === a.nodeType && b ? !0 : "array" === c || "function" !== c && (0 === b || "number" == typeof b && b > 0 && b - 1 in a)
    }
    function d(a) {
        var b = zb[a] = {};
        return kb.each(a.match(mb) || [],
        function(a, c) {
            b[c] = !0
        }),
        b
    }
    function e(a, c, d, e) {
        if (kb.acceptData(a)) {
            var f, g, h = kb.expando,
            i = a.nodeType,
            j = i ? kb.cache: a,
            k = i ? a[h] : a[h] && h;
            if (k && j[k] && (e || j[k].data) || d !== b || "string" != typeof c) return k || (k = i ? a[h] = bb.pop() || kb.guid++:h),
            j[k] || (j[k] = i ? {}: {
                toJSON: kb.noop
            }),
            ("object" == typeof c || "function" == typeof c) && (e ? j[k] = kb.extend(j[k], c) : j[k].data = kb.extend(j[k].data, c)),
            g = j[k],
            e || (g.data || (g.data = {}), g = g.data),
            d !== b && (g[kb.camelCase(c)] = d),
            "string" == typeof c ? (f = g[c], null == f && (f = g[kb.camelCase(c)])) : f = g,
            f
        }
    }
    function f(a, b, c) {
        if (kb.acceptData(a)) {
            var d, e, f = a.nodeType,
            g = f ? kb.cache: a,
            i = f ? a[kb.expando] : kb.expando;
            if (g[i]) {
                if (b && (d = c ? g[i] : g[i].data)) {
                    kb.isArray(b) ? b = b.concat(kb.map(b, kb.camelCase)) : b in d ? b = [b] : (b = kb.camelCase(b), b = b in d ? [b] : b.split(" ")),
                    e = b.length;
                    for (; e--;) delete d[b[e]];
                    if (c ? !h(d) : !kb.isEmptyObject(d)) return
                } (c || (delete g[i].data, h(g[i]))) && (f ? kb.cleanData([a], !0) : kb.support.deleteExpando || g != g.window ? delete g[i] : g[i] = null)
            }
        }
    }
    function g(a, c, d) {
        if (d === b && 1 === a.nodeType) {
            var e = "data-" + c.replace(Bb, "-$1").toLowerCase();
            if (d = a.getAttribute(e), "string" == typeof d) {
                try {
                    d = "true" === d ? !0 : "false" === d ? !1 : "null" === d ? null: +d + "" === d ? +d: Ab.test(d) ? kb.parseJSON(d) : d
                } catch(f) {}
                kb.data(a, c, d)
            } else d = b
        }
        return d
    }
    function h(a) {
        var b;
        for (b in a) if (("data" !== b || !kb.isEmptyObject(a[b])) && "toJSON" !== b) return ! 1;
        return ! 0
    }
    function i() {
        return ! 0
    }
    function j() {
        return ! 1
    }
    function k() {
        try {
            return Y.activeElement
        } catch(a) {}
    }
    function l(a, b) {
        do a = a[b];
        while (a && 1 !== a.nodeType);
        return a
    }
    function m(a, b, c) {
        if (kb.isFunction(b)) return kb.grep(a,
        function(a, d) {
            return !! b.call(a, d, a) !== c
        });
        if (b.nodeType) return kb.grep(a,
        function(a) {
            return a === b !== c
        });
        if ("string" == typeof b) {
            if (Qb.test(b)) return kb.filter(b, a, c);
            b = kb.filter(b, a)
        }
        return kb.grep(a,
        function(a) {
            return kb.inArray(a, b) >= 0 !== c
        })
    }
    function n(a) {
        var b = Ub.split("|"),
        c = a.createDocumentFragment();
        if (c.createElement) for (; b.length;) c.createElement(b.pop());
        return c
    }
    function o(a, b) {
        return kb.nodeName(a, "table") && kb.nodeName(1 === b.nodeType ? b: b.firstChild, "tr") ? a.getElementsByTagName("tbody")[0] || a.appendChild(a.ownerDocument.createElement("tbody")) : a
    }
    function p(a) {
        return a.type = (null !== kb.find.attr(a, "type")) + "/" + a.type,
        a
    }
    function q(a) {
        var b = ec.exec(a.type);
        return b ? a.type = b[1] : a.removeAttribute("type"),
        a
    }
    function r(a, b) {
        for (var c, d = 0; null != (c = a[d]); d++) kb._data(c, "globalEval", !b || kb._data(b[d], "globalEval"))
    }
    function s(a, b) {
        if (1 === b.nodeType && kb.hasData(a)) {
            var c, d, e, f = kb._data(a),
            g = kb._data(b, f),
            h = f.events;
            if (h) {
                delete g.handle,
                g.events = {};
                for (c in h) for (d = 0, e = h[c].length; e > d; d++) kb.event.add(b, c, h[c][d])
            }
            g.data && (g.data = kb.extend({},
            g.data))
        }
    }
    function t(a, b) {
        var c, d, e;
        if (1 === b.nodeType) {
            if (c = b.nodeName.toLowerCase(), !kb.support.noCloneEvent && b[kb.expando]) {
                e = kb._data(b);
                for (d in e.events) kb.removeEvent(b, d, e.handle);
                b.removeAttribute(kb.expando)
            }
            "script" === c && b.text !== a.text ? (p(b).text = a.text, q(b)) : "object" === c ? (b.parentNode && (b.outerHTML = a.outerHTML), kb.support.html5Clone && a.innerHTML && !kb.trim(b.innerHTML) && (b.innerHTML = a.innerHTML)) : "input" === c && bc.test(a.type) ? (b.defaultChecked = b.checked = a.checked, b.value !== a.value && (b.value = a.value)) : "option" === c ? b.defaultSelected = b.selected = a.defaultSelected: ("input" === c || "textarea" === c) && (b.defaultValue = a.defaultValue)
        }
    }
    function u(a, c) {
        var d, e, f = 0,
        g = typeof a.getElementsByTagName !== W ? a.getElementsByTagName(c || "*") : typeof a.querySelectorAll !== W ? a.querySelectorAll(c || "*") : b;
        if (!g) for (g = [], d = a.childNodes || a; null != (e = d[f]); f++) ! c || kb.nodeName(e, c) ? g.push(e) : kb.merge(g, u(e, c));
        return c === b || c && kb.nodeName(a, c) ? kb.merge([a], g) : g
    }
    function v(a) {
        bc.test(a.type) && (a.defaultChecked = a.checked)
    }
    function w(a, b) {
        if (b in a) return b;
        for (var c = b.charAt(0).toUpperCase() + b.slice(1), d = b, e = yc.length; e--;) if (b = yc[e] + c, b in a) return b;
        return d
    }
    function x(a, b) {
        return a = b || a,
        "none" === kb.css(a, "display") || !kb.contains(a.ownerDocument, a)
    }
    function y(a, b) {
        for (var c, d, e, f = [], g = 0, h = a.length; h > g; g++) d = a[g],
        d.style && (f[g] = kb._data(d, "olddisplay"), c = d.style.display, b ? (f[g] || "none" !== c || (d.style.display = ""), "" === d.style.display && x(d) && (f[g] = kb._data(d, "olddisplay", C(d.nodeName)))) : f[g] || (e = x(d), (c && "none" !== c || !e) && kb._data(d, "olddisplay", e ? c: kb.css(d, "display"))));
        for (g = 0; h > g; g++) d = a[g],
        d.style && (b && "none" !== d.style.display && "" !== d.style.display || (d.style.display = b ? f[g] || "": "none"));
        return a
    }
    function z(a, b, c) {
        var d = rc.exec(b);
        return d ? Math.max(0, d[1] - (c || 0)) + (d[2] || "px") : b
    }
    function A(a, b, c, d, e) {
        for (var f = c === (d ? "border": "content") ? 4 : "width" === b ? 1 : 0, g = 0; 4 > f; f += 2)"margin" === c && (g += kb.css(a, c + xc[f], !0, e)),
        d ? ("content" === c && (g -= kb.css(a, "padding" + xc[f], !0, e)), "margin" !== c && (g -= kb.css(a, "border" + xc[f] + "Width", !0, e))) : (g += kb.css(a, "padding" + xc[f], !0, e), "padding" !== c && (g += kb.css(a, "border" + xc[f] + "Width", !0, e)));
        return g
    }
    function B(a, b, c) {
        var d = !0,
        e = "width" === b ? a.offsetWidth: a.offsetHeight,
        f = kc(a),
        g = kb.support.boxSizing && "border-box" === kb.css(a, "boxSizing", !1, f);
        if (0 >= e || null == e) {
            if (e = lc(a, b, f), (0 > e || null == e) && (e = a.style[b]), sc.test(e)) return e;
            d = g && (kb.support.boxSizingReliable || e === a.style[b]),
            e = parseFloat(e) || 0
        }
        return e + A(a, b, c || (g ? "border": "content"), d, f) + "px"
    }
    function C(a) {
        var b = Y,
        c = uc[a];
        return c || (c = D(a, b), "none" !== c && c || (jc = (jc || kb("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(b.documentElement), b = (jc[0].contentWindow || jc[0].contentDocument).document, b.write("<!doctype html><html><body>"), b.close(), c = D(a, b), jc.detach()), uc[a] = c),
        c
    }
    function D(a, b) {
        var c = kb(b.createElement(a)).appendTo(b.body),
        d = kb.css(c[0], "display");
        return c.remove(),
        d
    }
    function E(a, b, c, d) {
        var e;
        if (kb.isArray(b)) kb.each(b,
        function(b, e) {
            c || Ac.test(a) ? d(a, e) : E(a + "[" + ("object" == typeof e ? b: "") + "]", e, c, d)
        });
        else if (c || "object" !== kb.type(b)) d(a, b);
        else for (e in b) E(a + "[" + e + "]", b[e], c, d)
    }
    function F(a) {
        return function(b, c) {
            "string" != typeof b && (c = b, b = "*");
            var d, e = 0,
            f = b.toLowerCase().match(mb) || [];
            if (kb.isFunction(c)) for (; d = f[e++];)"+" === d[0] ? (d = d.slice(1) || "*", (a[d] = a[d] || []).unshift(c)) : (a[d] = a[d] || []).push(c)
        }
    }
    function G(a, b, c, d) {
        function e(h) {
            var i;
            return f[h] = !0,
            kb.each(a[h] || [],
            function(a, h) {
                var j = h(b, c, d);
                return "string" != typeof j || g || f[j] ? g ? !(i = j) : void 0 : (b.dataTypes.unshift(j), e(j), !1)
            }),
            i
        }
        var f = {},
        g = a === Rc;
        return e(b.dataTypes[0]) || !f["*"] && e("*")
    }
    function H(a, c) {
        var d, e, f = kb.ajaxSettings.flatOptions || {};
        for (e in c) c[e] !== b && ((f[e] ? a: d || (d = {}))[e] = c[e]);
        return d && kb.extend(!0, a, d),
        a
    }
    function I(a, c, d) {
        for (var e, f, g, h, i = a.contents,
        j = a.dataTypes;
        "*" === j[0];) j.shift(),
        f === b && (f = a.mimeType || c.getResponseHeader("Content-Type"));
        if (f) for (h in i) if (i[h] && i[h].test(f)) {
            j.unshift(h);
            break
        }
        if (j[0] in d) g = j[0];
        else {
            for (h in d) {
                if (!j[0] || a.converters[h + " " + j[0]]) {
                    g = h;
                    break
                }
                e || (e = h)
            }
            g = g || e
        }
        return g ? (g !== j[0] && j.unshift(g), d[g]) : void 0
    }
    function J(a, b, c, d) {
        var e, f, g, h, i, j = {},
        k = a.dataTypes.slice();
        if (k[1]) for (g in a.converters) j[g.toLowerCase()] = a.converters[g];
        for (f = k.shift(); f;) if (a.responseFields[f] && (c[a.responseFields[f]] = b), !i && d && a.dataFilter && (b = a.dataFilter(b, a.dataType)), i = f, f = k.shift()) if ("*" === f) f = i;
        else if ("*" !== i && i !== f) {
            if (g = j[i + " " + f] || j["* " + f], !g) for (e in j) if (h = e.split(" "), h[1] === f && (g = j[i + " " + h[0]] || j["* " + h[0]])) {
                g === !0 ? g = j[e] : j[e] !== !0 && (f = h[0], k.unshift(h[1]));
                break
            }
            if (g !== !0) if (g && a["throws"]) b = g(b);
            else try {
                b = g(b)
            } catch(l) {
                return {
                    state: "parsererror",
                    error: g ? l: "No conversion from " + i + " to " + f
                }
            }
        }
        return {
            state: "success",
            data: b
        }
    }
    function K() {
        try {
            return new a.XMLHttpRequest
        } catch(b) {}
    }
    function L() {
        try {
            return new a.ActiveXObject("Microsoft.XMLHTTP")
        } catch(b) {}
    }
    function M() {
        return setTimeout(function() {
            $c = b
        }),
        $c = kb.now()
    }
    function N(a, b, c) {
        for (var d, e = (ed[b] || []).concat(ed["*"]), f = 0, g = e.length; g > f; f++) if (d = e[f].call(c, b, a)) return d
    }
    function O(a, b, c) {
        var d, e, f = 0,
        g = dd.length,
        h = kb.Deferred().always(function() {
            delete i.elem
        }),
        i = function() {
            if (e) return ! 1;
            for (var b = $c || M(), c = Math.max(0, j.startTime + j.duration - b), d = c / j.duration || 0, f = 1 - d, g = 0, i = j.tweens.length; i > g; g++) j.tweens[g].run(f);
            return h.notifyWith(a, [j, f, c]),
            1 > f && i ? c: (h.resolveWith(a, [j]), !1)
        },
        j = h.promise({
            elem: a,
            props: kb.extend({},
            b),
            opts: kb.extend(!0, {
                specialEasing: {}
            },
            c),
            originalProperties: b,
            originalOptions: c,
            startTime: $c || M(),
            duration: c.duration,
            tweens: [],
            createTween: function(b, c) {
                var d = kb.Tween(a, j.opts, b, c, j.opts.specialEasing[b] || j.opts.easing);
                return j.tweens.push(d),
                d
            },
            stop: function(b) {
                var c = 0,
                d = b ? j.tweens.length: 0;
                if (e) return this;
                for (e = !0; d > c; c++) j.tweens[c].run(1);
                return b ? h.resolveWith(a, [j, b]) : h.rejectWith(a, [j, b]),
                this
            }
        }),
        k = j.props;
        for (P(k, j.opts.specialEasing); g > f; f++) if (d = dd[f].call(j, a, k, j.opts)) return d;
        return kb.map(k, N, j),
        kb.isFunction(j.opts.start) && j.opts.start.call(a, j),
        kb.fx.timer(kb.extend(i, {
            elem: a,
            anim: j,
            queue: j.opts.queue
        })),
        j.progress(j.opts.progress).done(j.opts.done, j.opts.complete).fail(j.opts.fail).always(j.opts.always)
    }
    function P(a, b) {
        var c, d, e, f, g;
        for (c in a) if (d = kb.camelCase(c), e = b[d], f = a[c], kb.isArray(f) && (e = f[1], f = a[c] = f[0]), c !== d && (a[d] = f, delete a[c]), g = kb.cssHooks[d], g && "expand" in g) {
            f = g.expand(f),
            delete a[d];
            for (c in f) c in a || (a[c] = f[c], b[c] = e)
        } else b[d] = e
    }
    function Q(a, b, c) {
        var d, e, f, g, h, i, j = this,
        k = {},
        l = a.style,
        m = a.nodeType && x(a),
        n = kb._data(a, "fxshow");
        c.queue || (h = kb._queueHooks(a, "fx"), null == h.unqueued && (h.unqueued = 0, i = h.empty.fire, h.empty.fire = function() {
            h.unqueued || i()
        }), h.unqueued++, j.always(function() {
            j.always(function() {
                h.unqueued--,
                kb.queue(a, "fx").length || h.empty.fire()
            })
        })),
        1 === a.nodeType && ("height" in b || "width" in b) && (c.overflow = [l.overflow, l.overflowX, l.overflowY], "inline" === kb.css(a, "display") && "none" === kb.css(a, "float") && (kb.support.inlineBlockNeedsLayout && "inline" !== C(a.nodeName) ? l.zoom = 1 : l.display = "inline-block")),
        c.overflow && (l.overflow = "hidden", kb.support.shrinkWrapBlocks || j.always(function() {
            l.overflow = c.overflow[0],
            l.overflowX = c.overflow[1],
            l.overflowY = c.overflow[2]
        }));
        for (d in b) if (e = b[d], ad.exec(e)) {
            if (delete b[d], f = f || "toggle" === e, e === (m ? "hide": "show")) continue;
            k[d] = n && n[d] || kb.style(a, d)
        }
        if (!kb.isEmptyObject(k)) {
            n ? "hidden" in n && (m = n.hidden) : n = kb._data(a, "fxshow", {}),
            f && (n.hidden = !m),
            m ? kb(a).show() : j.done(function() {
                kb(a).hide()
            }),
            j.done(function() {
                var b;
                kb._removeData(a, "fxshow");
                for (b in k) kb.style(a, b, k[b])
            });
            for (d in k) g = N(m ? n[d] : 0, d, j),
            d in n || (n[d] = g.start, m && (g.end = g.start, g.start = "width" === d || "height" === d ? 1 : 0))
        }
    }
    function R(a, b, c, d, e) {
        return new R.prototype.init(a, b, c, d, e)
    }
    function S(a, b) {
        var c, d = {
            height: a
        },
        e = 0;
        for (b = b ? 1 : 0; 4 > e; e += 2 - b) c = xc[e],
        d["margin" + c] = d["padding" + c] = a;
        return b && (d.opacity = d.width = a),
        d
    }
    function T(a) {
        return kb.isWindow(a) ? a: 9 === a.nodeType ? a.defaultView || a.parentWindow: !1
    }
    var U, V, W = typeof b,
    X = a.location,
    Y = a.document,
    Z = Y.documentElement,
    $ = a.jQuery,
    _ = a.$,
    ab = {},
    bb = [],
    cb = "1.10.2",
    db = bb.concat,
    eb = bb.push,
    fb = bb.slice,
    gb = bb.indexOf,
    hb = ab.toString,
    ib = ab.hasOwnProperty,
    jb = cb.trim,
    kb = function(a, b) {
        return new kb.fn.init(a, b, V)
    },
    lb = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
    mb = /\S+/g,
    nb = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
    ob = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
    pb = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
    qb = /^[\],:{}\s]*$/,
    rb = /(?:^|:|,)(?:\s*\[)+/g,
    sb = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
    tb = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g,
    ub = /^-ms-/,
    vb = /-([\da-z])/gi,
    wb = function(a, b) {
        return b.toUpperCase()
    },
    xb = function(a) { (Y.addEventListener || "load" === a.type || "complete" === Y.readyState) && (yb(), kb.ready())
    },
    yb = function() {
        Y.addEventListener ? (Y.removeEventListener("DOMContentLoaded", xb, !1), a.removeEventListener("load", xb, !1)) : (Y.detachEvent("onreadystatechange", xb), a.detachEvent("onload", xb))
    };
    kb.fn = kb.prototype = {
        jquery: cb,
        constructor: kb,
        init: function(a, c, d) {
            var e, f;
            if (!a) return this;
            if ("string" == typeof a) {
                if (e = "<" === a.charAt(0) && ">" === a.charAt(a.length - 1) && a.length >= 3 ? [null, a, null] : ob.exec(a), !e || !e[1] && c) return ! c || c.jquery ? (c || d).find(a) : this.constructor(c).find(a);
                if (e[1]) {
                    if (c = c instanceof kb ? c[0] : c, kb.merge(this, kb.parseHTML(e[1], c && c.nodeType ? c.ownerDocument || c: Y, !0)), pb.test(e[1]) && kb.isPlainObject(c)) for (e in c) kb.isFunction(this[e]) ? this[e](c[e]) : this.attr(e, c[e]);
                    return this
                }
                if (f = Y.getElementById(e[2]), f && f.parentNode) {
                    if (f.id !== e[2]) return d.find(a);
                    this.length = 1,
                    this[0] = f
                }
                return this.context = Y,
                this.selector = a,
                this
            }
            return a.nodeType ? (this.context = this[0] = a, this.length = 1, this) : kb.isFunction(a) ? d.ready(a) : (a.selector !== b && (this.selector = a.selector, this.context = a.context), kb.makeArray(a, this))
        },
        selector: "",
        length: 0,
        toArray: function() {
            return fb.call(this)
        },
        get: function(a) {
            return null == a ? this.toArray() : 0 > a ? this[this.length + a] : this[a]
        },
        pushStack: function(a) {
            var b = kb.merge(this.constructor(), a);
            return b.prevObject = this,
            b.context = this.context,
            b
        },
        each: function(a, b) {
            return kb.each(this, a, b)
        },
        ready: function(a) {
            return kb.ready.promise().done(a),
            this
        },
        slice: function() {
            return this.pushStack(fb.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq( - 1)
        },
        eq: function(a) {
            var b = this.length,
            c = +a + (0 > a ? b: 0);
            return this.pushStack(c >= 0 && b > c ? [this[c]] : [])
        },
        map: function(a) {
            return this.pushStack(kb.map(this,
            function(b, c) {
                return a.call(b, c, b)
            }))
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: eb,
        sort: [].sort,
        splice: [].splice
    },
    kb.fn.init.prototype = kb.fn,
    kb.extend = kb.fn.extend = function() {
        var a, c, d, e, f, g, h = arguments[0] || {},
        i = 1,
        j = arguments.length,
        k = !1;
        for ("boolean" == typeof h && (k = h, h = arguments[1] || {},
        i = 2), "object" == typeof h || kb.isFunction(h) || (h = {}), j === i && (h = this, --i); j > i; i++) if (null != (f = arguments[i])) for (e in f) a = h[e],
        d = f[e],
        h !== d && (k && d && (kb.isPlainObject(d) || (c = kb.isArray(d))) ? (c ? (c = !1, g = a && kb.isArray(a) ? a: []) : g = a && kb.isPlainObject(a) ? a: {},
        h[e] = kb.extend(k, g, d)) : d !== b && (h[e] = d));
        return h
    },
    kb.extend({
        expando: "jQuery" + (cb + Math.random()).replace(/\D/g, ""),
        noConflict: function(b) {
            return a.$ === kb && (a.$ = _),
            b && a.jQuery === kb && (a.jQuery = $),
            kb
        },
        isReady: !1,
        readyWait: 1,
        holdReady: function(a) {
            a ? kb.readyWait++:kb.ready(!0)
        },
        ready: function(a) {
            if (a === !0 ? !--kb.readyWait: !kb.isReady) {
                if (!Y.body) return setTimeout(kb.ready);
                kb.isReady = !0,
                a !== !0 && --kb.readyWait > 0 || (U.resolveWith(Y, [kb]), kb.fn.trigger && kb(Y).trigger("ready").off("ready"))
            }
        },
        isFunction: function(a) {
            return "function" === kb.type(a)
        },
        isArray: Array.isArray ||
        function(a) {
            return "array" === kb.type(a)
        },
        isWindow: function(a) {
            return null != a && a == a.window
        },
        isNumeric: function(a) {
            return ! isNaN(parseFloat(a)) && isFinite(a)
        },
        type: function(a) {
            return null == a ? String(a) : "object" == typeof a || "function" == typeof a ? ab[hb.call(a)] || "object": typeof a
        },
        isPlainObject: function(a) {
            var c;
            if (!a || "object" !== kb.type(a) || a.nodeType || kb.isWindow(a)) return ! 1;
            try {
                if (a.constructor && !ib.call(a, "constructor") && !ib.call(a.constructor.prototype, "isPrototypeOf")) return ! 1
            } catch(d) {
                return ! 1
            }
            if (kb.support.ownLast) for (c in a) return ib.call(a, c);
            for (c in a);
            return c === b || ib.call(a, c)
        },
        isEmptyObject: function(a) {
            var b;
            for (b in a) return ! 1;
            return ! 0
        },
        error: function(a) {
            throw new Error(a)
        },
        parseHTML: function(a, b, c) {
            if (!a || "string" != typeof a) return null;
            "boolean" == typeof b && (c = b, b = !1),
            b = b || Y;
            var d = pb.exec(a),
            e = !c && [];
            return d ? [b.createElement(d[1])] : (d = kb.buildFragment([a], b, e), e && kb(e).remove(), kb.merge([], d.childNodes))
        },
        parseJSON: function(b) {
            return a.JSON && a.JSON.parse ? a.JSON.parse(b) : null === b ? b: "string" == typeof b && (b = kb.trim(b), b && qb.test(b.replace(sb, "@").replace(tb, "]").replace(rb, ""))) ? new Function("return " + b)() : (kb.error("Invalid JSON: " + b), void 0)
        },
        parseXML: function(c) {
            var d, e;
            if (!c || "string" != typeof c) return null;
            try {
                a.DOMParser ? (e = new DOMParser, d = e.parseFromString(c, "text/xml")) : (d = new ActiveXObject("Microsoft.XMLDOM"), d.async = "false", d.loadXML(c))
            } catch(f) {
                d = b
            }
            return d && d.documentElement && !d.getElementsByTagName("parsererror").length || kb.error("Invalid XML: " + c),
            d
        },
        noop: function() {},
        globalEval: function(b) {
            b && kb.trim(b) && (a.execScript ||
            function(b) {
                a.eval.call(a, b)
            })(b)
        },
        camelCase: function(a) {
            return a.replace(ub, "ms-").replace(vb, wb)
        },
        nodeName: function(a, b) {
            return a.nodeName && a.nodeName.toLowerCase() === b.toLowerCase()
        },
        each: function(a, b, d) {
            var e, f = 0,
            g = a.length,
            h = c(a);
            if (d) {
                if (h) for (; g > f && (e = b.apply(a[f], d), e !== !1); f++);
                else for (f in a) if (e = b.apply(a[f], d), e === !1) break
            } else if (h) for (; g > f && (e = b.call(a[f], f, a[f]), e !== !1); f++);
            else for (f in a) if (e = b.call(a[f], f, a[f]), e === !1) break;
            return a
        },
        trim: jb && !jb.call("ï»¿Â ") ?
        function(a) {
            return null == a ? "": jb.call(a)
        }: function(a) {
            return null == a ? "": (a + "").replace(nb, "")
        },
        makeArray: function(a, b) {
            var d = b || [];
            return null != a && (c(Object(a)) ? kb.merge(d, "string" == typeof a ? [a] : a) : eb.call(d, a)),
            d
        },
        inArray: function(a, b, c) {
            var d;
            if (b) {
                if (gb) return gb.call(b, a, c);
                for (d = b.length, c = c ? 0 > c ? Math.max(0, d + c) : c: 0; d > c; c++) if (c in b && b[c] === a) return c
            }
            return - 1
        },
        merge: function(a, c) {
            var d = c.length,
            e = a.length,
            f = 0;
            if ("number" == typeof d) for (; d > f; f++) a[e++] = c[f];
            else for (; c[f] !== b;) a[e++] = c[f++];
            return a.length = e,
            a
        },
        grep: function(a, b, c) {
            var d, e = [],
            f = 0,
            g = a.length;
            for (c = !!c; g > f; f++) d = !!b(a[f], f),
            c !== d && e.push(a[f]);
            return e
        },
        map: function(a, b, d) {
            var e, f = 0,
            g = a.length,
            h = c(a),
            i = [];
            if (h) for (; g > f; f++) e = b(a[f], f, d),
            null != e && (i[i.length] = e);
            else for (f in a) e = b(a[f], f, d),
            null != e && (i[i.length] = e);
            return db.apply([], i)
        },
        guid: 1,
        proxy: function(a, c) {
            var d, e, f;
            return "string" == typeof c && (f = a[c], c = a, a = f),
            kb.isFunction(a) ? (d = fb.call(arguments, 2), e = function() {
                return a.apply(c || this, d.concat(fb.call(arguments)))
            },
            e.guid = a.guid = a.guid || kb.guid++, e) : b
        },
        access: function(a, c, d, e, f, g, h) {
            var i = 0,
            j = a.length,
            k = null == d;
            if ("object" === kb.type(d)) {
                f = !0;
                for (i in d) kb.access(a, c, i, d[i], !0, g, h)
            } else if (e !== b && (f = !0, kb.isFunction(e) || (h = !0), k && (h ? (c.call(a, e), c = null) : (k = c, c = function(a, b, c) {
                return k.call(kb(a), c)
            })), c)) for (; j > i; i++) c(a[i], d, h ? e: e.call(a[i], i, c(a[i], d)));
            return f ? a: k ? c.call(a) : j ? c(a[0], d) : g
        },
        now: function() {
            return (new Date).getTime()
        },
        swap: function(a, b, c, d) {
            var e, f, g = {};
            for (f in b) g[f] = a.style[f],
            a.style[f] = b[f];
            e = c.apply(a, d || []);
            for (f in b) a.style[f] = g[f];
            return e
        }
    }),
    kb.ready.promise = function(b) {
        if (!U) if (U = kb.Deferred(), "complete" === Y.readyState) setTimeout(kb.ready);
        else if (Y.addEventListener) Y.addEventListener("DOMContentLoaded", xb, !1),
        a.addEventListener("load", xb, !1);
        else {
            Y.attachEvent("onreadystatechange", xb),
            a.attachEvent("onload", xb);
            var c = !1;
            try {
                c = null == a.frameElement && Y.documentElement
            } catch(d) {}
            c && c.doScroll &&
            function e() {
                if (!kb.isReady) {
                    try {
                        c.doScroll("left")
                    } catch(a) {
                        return setTimeout(e, 50)
                    }
                    yb(),
                    kb.ready()
                }
            } ()
        }
        return U.promise(b)
    },
    kb.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),
    function(a, b) {
        ab["[object " + b + "]"] = b.toLowerCase()
    }),
    V = kb(Y),
    function(a, b) {
        function c(a, b, c, d) {
            var e, f, g, h, i, j, k, l, o, p;
            if ((b ? b.ownerDocument || b: O) !== G && F(b), b = b || G, c = c || [], !a || "string" != typeof a) return c;
            if (1 !== (h = b.nodeType) && 9 !== h) return [];
            if (I && !d) {
                if (e = tb.exec(a)) if (g = e[1]) {
                    if (9 === h) {
                        if (f = b.getElementById(g), !f || !f.parentNode) return c;
                        if (f.id === g) return c.push(f),
                        c
                    } else if (b.ownerDocument && (f = b.ownerDocument.getElementById(g)) && M(b, f) && f.id === g) return c.push(f),
                    c
                } else {
                    if (e[2]) return ab.apply(c, b.getElementsByTagName(a)),
                    c;
                    if ((g = e[3]) && x.getElementsByClassName && b.getElementsByClassName) return ab.apply(c, b.getElementsByClassName(g)),
                    c
                }
                if (x.qsa && (!J || !J.test(a))) {
                    if (l = k = N, o = b, p = 9 === h && a, 1 === h && "object" !== b.nodeName.toLowerCase()) {
                        for (j = m(a), (k = b.getAttribute("id")) ? l = k.replace(wb, "\\$&") : b.setAttribute("id", l), l = "[id='" + l + "'] ", i = j.length; i--;) j[i] = l + n(j[i]);
                        o = nb.test(a) && b.parentNode || b,
                        p = j.join(",")
                    }
                    if (p) try {
                        return ab.apply(c, o.querySelectorAll(p)),
                        c
                    } catch(q) {} finally {
                        k || b.removeAttribute("id")
                    }
                }
            }
            return v(a.replace(jb, "$1"), b, c, d)
        }
        function d() {
            function a(c, d) {
                return b.push(c += " ") > z.cacheLength && delete a[b.shift()],
                a[c] = d
            }
            var b = [];
            return a
        }
        function e(a) {
            return a[N] = !0,
            a
        }
        function f(a) {
            var b = G.createElement("div");
            try {
                return !! a(b)
            } catch(c) {
                return ! 1
            } finally {
                b.parentNode && b.parentNode.removeChild(b),
                b = null
            }
        }
        function g(a, b) {
            for (var c = a.split("|"), d = a.length; d--;) z.attrHandle[c[d]] = b
        }
        function h(a, b) {
            var c = b && a,
            d = c && 1 === a.nodeType && 1 === b.nodeType && (~b.sourceIndex || X) - (~a.sourceIndex || X);
            if (d) return d;
            if (c) for (; c = c.nextSibling;) if (c === b) return - 1;
            return a ? 1 : -1
        }
        function i(a) {
            return function(b) {
                var c = b.nodeName.toLowerCase();
                return "input" === c && b.type === a
            }
        }
        function j(a) {
            return function(b) {
                var c = b.nodeName.toLowerCase();
                return ("input" === c || "button" === c) && b.type === a
            }
        }
        function k(a) {
            return e(function(b) {
                return b = +b,
                e(function(c, d) {
                    for (var e, f = a([], c.length, b), g = f.length; g--;) c[e = f[g]] && (c[e] = !(d[e] = c[e]))
                })
            })
        }
        function l() {}
        function m(a, b) {
            var d, e, f, g, h, i, j, k = S[a + " "];
            if (k) return b ? 0 : k.slice(0);
            for (h = a, i = [], j = z.preFilter; h;) { (!d || (e = lb.exec(h))) && (e && (h = h.slice(e[0].length) || h), i.push(f = [])),
                d = !1,
                (e = mb.exec(h)) && (d = e.shift(), f.push({
                    value: d,
                    type: e[0].replace(jb, " ")
                }), h = h.slice(d.length));
                for (g in z.filter) ! (e = rb[g].exec(h)) || j[g] && !(e = j[g](e)) || (d = e.shift(), f.push({
                    value: d,
                    type: g,
                    matches: e
                }), h = h.slice(d.length));
                if (!d) break
            }
            return b ? h.length: h ? c.error(a) : S(a, i).slice(0)
        }
        function n(a) {
            for (var b = 0,
            c = a.length,
            d = ""; c > b; b++) d += a[b].value;
            return d
        }
        function o(a, b, c) {
            var d = b.dir,
            e = c && "parentNode" === d,
            f = Q++;
            return b.first ?
            function(b, c, f) {
                for (; b = b[d];) if (1 === b.nodeType || e) return a(b, c, f)
            }: function(b, c, g) {
                var h, i, j, k = P + " " + f;
                if (g) {
                    for (; b = b[d];) if ((1 === b.nodeType || e) && a(b, c, g)) return ! 0
                } else for (; b = b[d];) if (1 === b.nodeType || e) if (j = b[N] || (b[N] = {}), (i = j[d]) && i[0] === k) {
                    if ((h = i[1]) === !0 || h === y) return h === !0
                } else if (i = j[d] = [k], i[1] = a(b, c, g) || y, i[1] === !0) return ! 0
            }
        }
        function p(a) {
            return a.length > 1 ?
            function(b, c, d) {
                for (var e = a.length; e--;) if (!a[e](b, c, d)) return ! 1;
                return ! 0
            }: a[0]
        }
        function q(a, b, c, d, e) {
            for (var f, g = [], h = 0, i = a.length, j = null != b; i > h; h++)(f = a[h]) && (!c || c(f, d, e)) && (g.push(f), j && b.push(h));
            return g
        }
        function r(a, b, c, d, f, g) {
            return d && !d[N] && (d = r(d)),
            f && !f[N] && (f = r(f, g)),
            e(function(e, g, h, i) {
                var j, k, l, m = [],
                n = [],
                o = g.length,
                p = e || u(b || "*", h.nodeType ? [h] : h, []),
                r = !a || !e && b ? p: q(p, m, a, h, i),
                s = c ? f || (e ? a: o || d) ? [] : g: r;
                if (c && c(r, s, h, i), d) for (j = q(s, n), d(j, [], h, i), k = j.length; k--;)(l = j[k]) && (s[n[k]] = !(r[n[k]] = l));
                if (e) {
                    if (f || a) {
                        if (f) {
                            for (j = [], k = s.length; k--;)(l = s[k]) && j.push(r[k] = l);
                            f(null, s = [], j, i)
                        }
                        for (k = s.length; k--;)(l = s[k]) && (j = f ? cb.call(e, l) : m[k]) > -1 && (e[j] = !(g[j] = l))
                    }
                } else s = q(s === g ? s.splice(o, s.length) : s),
                f ? f(null, g, s, i) : ab.apply(g, s)
            })
        }
        function s(a) {
            for (var b, c, d, e = a.length,
            f = z.relative[a[0].type], g = f || z.relative[" "], h = f ? 1 : 0, i = o(function(a) {
                return a === b
            },
            g, !0), j = o(function(a) {
                return cb.call(b, a) > -1
            },
            g, !0), k = [function(a, c, d) {
                return ! f && (d || c !== D) || ((b = c).nodeType ? i(a, c, d) : j(a, c, d))
            }]; e > h; h++) if (c = z.relative[a[h].type]) k = [o(p(k), c)];
            else {
                if (c = z.filter[a[h].type].apply(null, a[h].matches), c[N]) {
                    for (d = ++h; e > d && !z.relative[a[d].type]; d++);
                    return r(h > 1 && p(k), h > 1 && n(a.slice(0, h - 1).concat({
                        value: " " === a[h - 2].type ? "*": ""
                    })).replace(jb, "$1"), c, d > h && s(a.slice(h, d)), e > d && s(a = a.slice(d)), e > d && n(a))
                }
                k.push(c)
            }
            return p(k)
        }
        function t(a, b) {
            var d = 0,
            f = b.length > 0,
            g = a.length > 0,
            h = function(e, h, i, j, k) {
                var l, m, n, o = [],
                p = 0,
                r = "0",
                s = e && [],
                t = null != k,
                u = D,
                v = e || g && z.find.TAG("*", k && h.parentNode || h),
                w = P += null == u ? 1 : Math.random() || .1;
                for (t && (D = h !== G && h, y = d); null != (l = v[r]); r++) {
                    if (g && l) {
                        for (m = 0; n = a[m++];) if (n(l, h, i)) {
                            j.push(l);
                            break
                        }
                        t && (P = w, y = ++d)
                    }
                    f && ((l = !n && l) && p--, e && s.push(l))
                }
                if (p += r, f && r !== p) {
                    for (m = 0; n = b[m++];) n(s, o, h, i);
                    if (e) {
                        if (p > 0) for (; r--;) s[r] || o[r] || (o[r] = $.call(j));
                        o = q(o)
                    }
                    ab.apply(j, o),
                    t && !e && o.length > 0 && p + b.length > 1 && c.uniqueSort(j)
                }
                return t && (P = w, D = u),
                s
            };
            return f ? e(h) : h
        }
        function u(a, b, d) {
            for (var e = 0,
            f = b.length; f > e; e++) c(a, b[e], d);
            return d
        }
        function v(a, b, c, d) {
            var e, f, g, h, i, j = m(a);
            if (!d && 1 === j.length) {
                if (f = j[0] = j[0].slice(0), f.length > 2 && "ID" === (g = f[0]).type && x.getById && 9 === b.nodeType && I && z.relative[f[1].type]) {
                    if (b = (z.find.ID(g.matches[0].replace(xb, yb), b) || [])[0], !b) return c;
                    a = a.slice(f.shift().value.length)
                }
                for (e = rb.needsContext.test(a) ? 0 : f.length; e--&&(g = f[e], !z.relative[h = g.type]);) if ((i = z.find[h]) && (d = i(g.matches[0].replace(xb, yb), nb.test(f[0].type) && b.parentNode || b))) {
                    if (f.splice(e, 1), a = d.length && n(f), !a) return ab.apply(c, d),
                    c;
                    break
                }
            }
            return C(a, j)(d, b, !I, c, nb.test(a)),
            c
        }
        var w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N = "sizzle" + -new Date,
        O = a.document,
        P = 0,
        Q = 0,
        R = d(),
        S = d(),
        T = d(),
        U = !1,
        V = function(a, b) {
            return a === b ? (U = !0, 0) : 0
        },
        W = typeof b,
        X = 1 << 31,
        Y = {}.hasOwnProperty,
        Z = [],
        $ = Z.pop,
        _ = Z.push,
        ab = Z.push,
        bb = Z.slice,
        cb = Z.indexOf ||
        function(a) {
            for (var b = 0,
            c = this.length; c > b; b++) if (this[b] === a) return b;
            return - 1
        },
        db = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
        eb = "[\\x20\\t\\r\\n\\f]",
        fb = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
        gb = fb.replace("w", "w#"),
        hb = "\\[" + eb + "*(" + fb + ")" + eb + "*(?:([*^$|!~]?=)" + eb + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + gb + ")|)|)" + eb + "*\\]",
        ib = ":(" + fb + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + hb.replace(3, 8) + ")*)|.*)\\)|)",
        jb = new RegExp("^" + eb + "+|((?:^|[^\\\\])(?:\\\\.)*)" + eb + "+$", "g"),
        lb = new RegExp("^" + eb + "*," + eb + "*"),
        mb = new RegExp("^" + eb + "*([>+~]|" + eb + ")" + eb + "*"),
        nb = new RegExp(eb + "*[+~]"),
        ob = new RegExp("=" + eb + "*([^\\]'\"]*)" + eb + "*\\]", "g"),
        pb = new RegExp(ib),
        qb = new RegExp("^" + gb + "$"),
        rb = {
            ID: new RegExp("^#(" + fb + ")"),
            CLASS: new RegExp("^\\.(" + fb + ")"),
            TAG: new RegExp("^(" + fb.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + hb),
            PSEUDO: new RegExp("^" + ib),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + eb + "*(even|odd|(([+-]|)(\\d*)n|)" + eb + "*(?:([+-]|)" + eb + "*(\\d+)|))" + eb + "*\\)|)", "i"),
            bool: new RegExp("^(?:" + db + ")$", "i"),
            needsContext: new RegExp("^" + eb + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + eb + "*((?:-\\d)?\\d*)" + eb + "*\\)|)(?=[^-]|$)", "i")
        },
        sb = /^[^{]+\{\s*\[native \w/,
        tb = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
        ub = /^(?:input|select|textarea|button)$/i,
        vb = /^h\d$/i,
        wb = /'|\\/g,
        xb = new RegExp("\\\\([\\da-f]{1,6}" + eb + "?|(" + eb + ")|.)", "ig"),
        yb = function(a, b, c) {
            var d = "0x" + b - 65536;
            return d !== d || c ? b: 0 > d ? String.fromCharCode(d + 65536) : String.fromCharCode(55296 | d >> 10, 56320 | 1023 & d)
        };
        try {
            ab.apply(Z = bb.call(O.childNodes), O.childNodes),
            Z[O.childNodes.length].nodeType
        } catch(zb) {
            ab = {
                apply: Z.length ?
                function(a, b) {
                    _.apply(a, bb.call(b))
                }: function(a, b) {
                    for (var c = a.length,
                    d = 0; a[c++] = b[d++];);
                    a.length = c - 1
                }
            }
        }
        B = c.isXML = function(a) {
            var b = a && (a.ownerDocument || a).documentElement;
            return b ? "HTML" !== b.nodeName: !1
        },
        x = c.support = {},
        F = c.setDocument = function(a) {
            var b = a ? a.ownerDocument || a: O,
            c = b.defaultView;
            return b !== G && 9 === b.nodeType && b.documentElement ? (G = b, H = b.documentElement, I = !B(b), c && c.attachEvent && c !== c.top && c.attachEvent("onbeforeunload",
            function() {
                F()
            }), x.attributes = f(function(a) {
                return a.className = "i",
                !a.getAttribute("className")
            }), x.getElementsByTagName = f(function(a) {
                return a.appendChild(b.createComment("")),
                !a.getElementsByTagName("*").length
            }), x.getElementsByClassName = f(function(a) {
                return a.innerHTML = "<div class='a'></div><div class='a i'></div>",
                a.firstChild.className = "i",
                2 === a.getElementsByClassName("i").length
            }), x.getById = f(function(a) {
                return H.appendChild(a).id = N,
                !b.getElementsByName || !b.getElementsByName(N).length
            }), x.getById ? (z.find.ID = function(a, b) {
                if (typeof b.getElementById !== W && I) {
                    var c = b.getElementById(a);
                    return c && c.parentNode ? [c] : []
                }
            },
            z.filter.ID = function(a) {
                var b = a.replace(xb, yb);
                return function(a) {
                    return a.getAttribute("id") === b
                }
            }) : (delete z.find.ID, z.filter.ID = function(a) {
                var b = a.replace(xb, yb);
                return function(a) {
                    var c = typeof a.getAttributeNode !== W && a.getAttributeNode("id");
                    return c && c.value === b
                }
            }), z.find.TAG = x.getElementsByTagName ?
            function(a, b) {
                return typeof b.getElementsByTagName !== W ? b.getElementsByTagName(a) : void 0
            }: function(a, b) {
                var c, d = [],
                e = 0,
                f = b.getElementsByTagName(a);
                if ("*" === a) {
                    for (; c = f[e++];) 1 === c.nodeType && d.push(c);
                    return d
                }
                return f
            },
            z.find.CLASS = x.getElementsByClassName &&
            function(a, b) {
                return typeof b.getElementsByClassName !== W && I ? b.getElementsByClassName(a) : void 0
            },
            K = [], J = [], (x.qsa = sb.test(b.querySelectorAll)) && (f(function(a) {
                a.innerHTML = "<select><option selected=''></option></select>",
                a.querySelectorAll("[selected]").length || J.push("\\[" + eb + "*(?:value|" + db + ")"),
                a.querySelectorAll(":checked").length || J.push(":checked")
            }), f(function(a) {
                var c = b.createElement("input");
                c.setAttribute("type", "hidden"),
                a.appendChild(c).setAttribute("t", ""),
                a.querySelectorAll("[t^='']").length && J.push("[*^$]=" + eb + "*(?:''|\"\")"),
                a.querySelectorAll(":enabled").length || J.push(":enabled", ":disabled"),
                a.querySelectorAll("*,:x"),
                J.push(",.*:")
            })), (x.matchesSelector = sb.test(L = H.webkitMatchesSelector || H.mozMatchesSelector || H.oMatchesSelector || H.msMatchesSelector)) && f(function(a) {
                x.disconnectedMatch = L.call(a, "div"),
                L.call(a, "[s!='']:x"),
                K.push("!=", ib)
            }), J = J.length && new RegExp(J.join("|")), K = K.length && new RegExp(K.join("|")), M = sb.test(H.contains) || H.compareDocumentPosition ?
            function(a, b) {
                var c = 9 === a.nodeType ? a.documentElement: a,
                d = b && b.parentNode;
                return a === d || !(!d || 1 !== d.nodeType || !(c.contains ? c.contains(d) : a.compareDocumentPosition && 16 & a.compareDocumentPosition(d)))
            }: function(a, b) {
                if (b) for (; b = b.parentNode;) if (b === a) return ! 0;
                return ! 1
            },
            V = H.compareDocumentPosition ?
            function(a, c) {
                if (a === c) return U = !0,
                0;
                var d = c.compareDocumentPosition && a.compareDocumentPosition && a.compareDocumentPosition(c);
                return d ? 1 & d || !x.sortDetached && c.compareDocumentPosition(a) === d ? a === b || M(O, a) ? -1 : c === b || M(O, c) ? 1 : E ? cb.call(E, a) - cb.call(E, c) : 0 : 4 & d ? -1 : 1 : a.compareDocumentPosition ? -1 : 1
            }: function(a, c) {
                var d, e = 0,
                f = a.parentNode,
                g = c.parentNode,
                i = [a],
                j = [c];
                if (a === c) return U = !0,
                0;
                if (!f || !g) return a === b ? -1 : c === b ? 1 : f ? -1 : g ? 1 : E ? cb.call(E, a) - cb.call(E, c) : 0;
                if (f === g) return h(a, c);
                for (d = a; d = d.parentNode;) i.unshift(d);
                for (d = c; d = d.parentNode;) j.unshift(d);
                for (; i[e] === j[e];) e++;
                return e ? h(i[e], j[e]) : i[e] === O ? -1 : j[e] === O ? 1 : 0
            },
            b) : G
        },
        c.matches = function(a, b) {
            return c(a, null, null, b)
        },
        c.matchesSelector = function(a, b) {
            if ((a.ownerDocument || a) !== G && F(a), b = b.replace(ob, "='$1']"), !(!x.matchesSelector || !I || K && K.test(b) || J && J.test(b))) try {
                var d = L.call(a, b);
                if (d || x.disconnectedMatch || a.document && 11 !== a.document.nodeType) return d
            } catch(e) {}
            return c(b, G, null, [a]).length > 0
        },
        c.contains = function(a, b) {
            return (a.ownerDocument || a) !== G && F(a),
            M(a, b)
        },
        c.attr = function(a, c) { (a.ownerDocument || a) !== G && F(a);
            var d = z.attrHandle[c.toLowerCase()],
            e = d && Y.call(z.attrHandle, c.toLowerCase()) ? d(a, c, !I) : b;
            return e === b ? x.attributes || !I ? a.getAttribute(c) : (e = a.getAttributeNode(c)) && e.specified ? e.value: null: e
        },
        c.error = function(a) {
            throw new Error("Syntax error, unrecognized expression: " + a)
        },
        c.uniqueSort = function(a) {
            var b, c = [],
            d = 0,
            e = 0;
            if (U = !x.detectDuplicates, E = !x.sortStable && a.slice(0), a.sort(V), U) {
                for (; b = a[e++];) b === a[e] && (d = c.push(e));
                for (; d--;) a.splice(c[d], 1)
            }
            return a
        },
        A = c.getText = function(a) {
            var b, c = "",
            d = 0,
            e = a.nodeType;
            if (e) {
                if (1 === e || 9 === e || 11 === e) {
                    if ("string" == typeof a.textContent) return a.textContent;
                    for (a = a.firstChild; a; a = a.nextSibling) c += A(a)
                } else if (3 === e || 4 === e) return a.nodeValue
            } else for (; b = a[d]; d++) c += A(b);
            return c
        },
        z = c.selectors = {
            cacheLength: 50,
            createPseudo: e,
            match: rb,
            attrHandle: {},
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(a) {
                    return a[1] = a[1].replace(xb, yb),
                    a[3] = (a[4] || a[5] || "").replace(xb, yb),
                    "~=" === a[2] && (a[3] = " " + a[3] + " "),
                    a.slice(0, 4)
                },
                CHILD: function(a) {
                    return a[1] = a[1].toLowerCase(),
                    "nth" === a[1].slice(0, 3) ? (a[3] || c.error(a[0]), a[4] = +(a[4] ? a[5] + (a[6] || 1) : 2 * ("even" === a[3] || "odd" === a[3])), a[5] = +(a[7] + a[8] || "odd" === a[3])) : a[3] && c.error(a[0]),
                    a
                },
                PSEUDO: function(a) {
                    var c, d = !a[5] && a[2];
                    return rb.CHILD.test(a[0]) ? null: (a[3] && a[4] !== b ? a[2] = a[4] : d && pb.test(d) && (c = m(d, !0)) && (c = d.indexOf(")", d.length - c) - d.length) && (a[0] = a[0].slice(0, c), a[2] = d.slice(0, c)), a.slice(0, 3))
                }
            },
            filter: {
                TAG: function(a) {
                    var b = a.replace(xb, yb).toLowerCase();
                    return "*" === a ?
                    function() {
                        return ! 0
                    }: function(a) {
                        return a.nodeName && a.nodeName.toLowerCase() === b
                    }
                },
                CLASS: function(a) {
                    var b = R[a + " "];
                    return b || (b = new RegExp("(^|" + eb + ")" + a + "(" + eb + "|$)")) && R(a,
                    function(a) {
                        return b.test("string" == typeof a.className && a.className || typeof a.getAttribute !== W && a.getAttribute("class") || "")
                    })
                },
                ATTR: function(a, b, d) {
                    return function(e) {
                        var f = c.attr(e, a);
                        return null == f ? "!=" === b: b ? (f += "", "=" === b ? f === d: "!=" === b ? f !== d: "^=" === b ? d && 0 === f.indexOf(d) : "*=" === b ? d && f.indexOf(d) > -1 : "$=" === b ? d && f.slice( - d.length) === d: "~=" === b ? (" " + f + " ").indexOf(d) > -1 : "|=" === b ? f === d || f.slice(0, d.length + 1) === d + "-": !1) : !0
                    }
                },
                CHILD: function(a, b, c, d, e) {
                    var f = "nth" !== a.slice(0, 3),
                    g = "last" !== a.slice( - 4),
                    h = "of-type" === b;
                    return 1 === d && 0 === e ?
                    function(a) {
                        return !! a.parentNode
                    }: function(b, c, i) {
                        var j, k, l, m, n, o, p = f !== g ? "nextSibling": "previousSibling",
                        q = b.parentNode,
                        r = h && b.nodeName.toLowerCase(),
                        s = !i && !h;
                        if (q) {
                            if (f) {
                                for (; p;) {
                                    for (l = b; l = l[p];) if (h ? l.nodeName.toLowerCase() === r: 1 === l.nodeType) return ! 1;
                                    o = p = "only" === a && !o && "nextSibling"
                                }
                                return ! 0
                            }
                            if (o = [g ? q.firstChild: q.lastChild], g && s) {
                                for (k = q[N] || (q[N] = {}), j = k[a] || [], n = j[0] === P && j[1], m = j[0] === P && j[2], l = n && q.childNodes[n]; l = ++n && l && l[p] || (m = n = 0) || o.pop();) if (1 === l.nodeType && ++m && l === b) {
                                    k[a] = [P, n, m];
                                    break
                                }
                            } else if (s && (j = (b[N] || (b[N] = {}))[a]) && j[0] === P) m = j[1];
                            else for (; (l = ++n && l && l[p] || (m = n = 0) || o.pop()) && ((h ? l.nodeName.toLowerCase() !== r: 1 !== l.nodeType) || !++m || (s && ((l[N] || (l[N] = {}))[a] = [P, m]), l !== b)););
                            return m -= e,
                            m === d || 0 === m % d && m / d >= 0
                        }
                    }
                },
                PSEUDO: function(a, b) {
                    var d, f = z.pseudos[a] || z.setFilters[a.toLowerCase()] || c.error("unsupported pseudo: " + a);
                    return f[N] ? f(b) : f.length > 1 ? (d = [a, a, "", b], z.setFilters.hasOwnProperty(a.toLowerCase()) ? e(function(a, c) {
                        for (var d, e = f(a, b), g = e.length; g--;) d = cb.call(a, e[g]),
                        a[d] = !(c[d] = e[g])
                    }) : function(a) {
                        return f(a, 0, d)
                    }) : f
                }
            },
            pseudos: {
                not: e(function(a) {
                    var b = [],
                    c = [],
                    d = C(a.replace(jb, "$1"));
                    return d[N] ? e(function(a, b, c, e) {
                        for (var f, g = d(a, null, e, []), h = a.length; h--;)(f = g[h]) && (a[h] = !(b[h] = f))
                    }) : function(a, e, f) {
                        return b[0] = a,
                        d(b, null, f, c),
                        !c.pop()
                    }
                }),
                has: e(function(a) {
                    return function(b) {
                        return c(a, b).length > 0
                    }
                }),
                contains: e(function(a) {
                    return function(b) {
                        return (b.textContent || b.innerText || A(b)).indexOf(a) > -1
                    }
                }),
                lang: e(function(a) {
                    return qb.test(a || "") || c.error("unsupported lang: " + a),
                    a = a.replace(xb, yb).toLowerCase(),
                    function(b) {
                        var c;
                        do
                        if (c = I ? b.lang: b.getAttribute("xml:lang") || b.getAttribute("lang")) return c = c.toLowerCase(),
                        c === a || 0 === c.indexOf(a + "-");
                        while ((b = b.parentNode) && 1 === b.nodeType);
                        return ! 1
                    }
                }),
                target: function(b) {
                    var c = a.location && a.location.hash;
                    return c && c.slice(1) === b.id
                },
                root: function(a) {
                    return a === H
                },
                focus: function(a) {
                    return a === G.activeElement && (!G.hasFocus || G.hasFocus()) && !!(a.type || a.href || ~a.tabIndex)
                },
                enabled: function(a) {
                    return a.disabled === !1
                },
                disabled: function(a) {
                    return a.disabled === !0
                },
                checked: function(a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && !!a.checked || "option" === b && !!a.selected
                },
                selected: function(a) {
                    return a.parentNode && a.parentNode.selectedIndex,
                    a.selected === !0
                },
                empty: function(a) {
                    for (a = a.firstChild; a; a = a.nextSibling) if (a.nodeName > "@" || 3 === a.nodeType || 4 === a.nodeType) return ! 1;
                    return ! 0
                },
                parent: function(a) {
                    return ! z.pseudos.empty(a)
                },
                header: function(a) {
                    return vb.test(a.nodeName)
                },
                input: function(a) {
                    return ub.test(a.nodeName)
                },
                button: function(a) {
                    var b = a.nodeName.toLowerCase();
                    return "input" === b && "button" === a.type || "button" === b
                },
                text: function(a) {
                    var b;
                    return "input" === a.nodeName.toLowerCase() && "text" === a.type && (null == (b = a.getAttribute("type")) || b.toLowerCase() === a.type)
                },
                first: k(function() {
                    return [0]
                }),
                last: k(function(a, b) {
                    return [b - 1]
                }),
                eq: k(function(a, b, c) {
                    return [0 > c ? c + b: c]
                }),
                even: k(function(a, b) {
                    for (var c = 0; b > c; c += 2) a.push(c);
                    return a
                }),
                odd: k(function(a, b) {
                    for (var c = 1; b > c; c += 2) a.push(c);
                    return a
                }),
                lt: k(function(a, b, c) {
                    for (var d = 0 > c ? c + b: c; --d >= 0;) a.push(d);
                    return a
                }),
                gt: k(function(a, b, c) {
                    for (var d = 0 > c ? c + b: c; ++d < b;) a.push(d);
                    return a
                })
            }
        },
        z.pseudos.nth = z.pseudos.eq;
        for (w in {
            radio: !0,
            checkbox: !0,
            file: !0,
            password: !0,
            image: !0
        }) z.pseudos[w] = i(w);
        for (w in {
            submit: !0,
            reset: !0
        }) z.pseudos[w] = j(w);
        l.prototype = z.filters = z.pseudos,
        z.setFilters = new l,
        C = c.compile = function(a, b) {
            var c, d = [],
            e = [],
            f = T[a + " "];
            if (!f) {
                for (b || (b = m(a)), c = b.length; c--;) f = s(b[c]),
                f[N] ? d.push(f) : e.push(f);
                f = T(a, t(e, d))
            }
            return f
        },
        x.sortStable = N.split("").sort(V).join("") === N,
        x.detectDuplicates = U,
        F(),
        x.sortDetached = f(function(a) {
            return 1 & a.compareDocumentPosition(G.createElement("div"))
        }),
        f(function(a) {
            return a.innerHTML = "<a href='#'></a>",
            "#" === a.firstChild.getAttribute("href")
        }) || g("type|href|height|width",
        function(a, b, c) {
            return c ? void 0 : a.getAttribute(b, "type" === b.toLowerCase() ? 1 : 2)
        }),
        x.attributes && f(function(a) {
            return a.innerHTML = "<input/>",
            a.firstChild.setAttribute("value", ""),
            "" === a.firstChild.getAttribute("value")
        }) || g("value",
        function(a, b, c) {
            return c || "input" !== a.nodeName.toLowerCase() ? void 0 : a.defaultValue
        }),
        f(function(a) {
            return null == a.getAttribute("disabled")
        }) || g(db,
        function(a, b, c) {
            var d;
            return c ? void 0 : (d = a.getAttributeNode(b)) && d.specified ? d.value: a[b] === !0 ? b.toLowerCase() : null
        }),
        kb.find = c,
        kb.expr = c.selectors,
        kb.expr[":"] = kb.expr.pseudos,
        kb.unique = c.uniqueSort,
        kb.text = c.getText,
        kb.isXMLDoc = c.isXML,
        kb.contains = c.contains
    } (a);
    var zb = {};
    kb.Callbacks = function(a) {
        a = "string" == typeof a ? zb[a] || d(a) : kb.extend({},
        a);
        var c, e, f, g, h, i, j = [],
        k = !a.once && [],
        l = function(b) {
            for (e = a.memory && b, f = !0, h = i || 0, i = 0, g = j.length, c = !0; j && g > h; h++) if (j[h].apply(b[0], b[1]) === !1 && a.stopOnFalse) {
                e = !1;
                break
            }
            c = !1,
            j && (k ? k.length && l(k.shift()) : e ? j = [] : m.disable())
        },
        m = {
            add: function() {
                if (j) {
                    var b = j.length; !
                    function d(b) {
                        kb.each(b,
                        function(b, c) {
                            var e = kb.type(c);
                            "function" === e ? a.unique && m.has(c) || j.push(c) : c && c.length && "string" !== e && d(c)
                        })
                    } (arguments),
                    c ? g = j.length: e && (i = b, l(e))
                }
                return this
            },
            remove: function() {
                return j && kb.each(arguments,
                function(a, b) {
                    for (var d; (d = kb.inArray(b, j, d)) > -1;) j.splice(d, 1),
                    c && (g >= d && g--, h >= d && h--)
                }),
                this
            },
            has: function(a) {
                return a ? kb.inArray(a, j) > -1 : !(!j || !j.length)
            },
            empty: function() {
                return j = [],
                g = 0,
                this
            },
            disable: function() {
                return j = k = e = b,
                this
            },
            disabled: function() {
                return ! j
            },
            lock: function() {
                return k = b,
                e || m.disable(),
                this
            },
            locked: function() {
                return ! k
            },
            fireWith: function(a, b) {
                return ! j || f && !k || (b = b || [], b = [a, b.slice ? b.slice() : b], c ? k.push(b) : l(b)),
                this
            },
            fire: function() {
                return m.fireWith(this, arguments),
                this
            },
            fired: function() {
                return !! f
            }
        };
        return m
    },
    kb.extend({
        Deferred: function(a) {
            var b = [["resolve", "done", kb.Callbacks("once memory"), "resolved"], ["reject", "fail", kb.Callbacks("once memory"), "rejected"], ["notify", "progress", kb.Callbacks("memory")]],
            c = "pending",
            d = {
                state: function() {
                    return c
                },
                always: function() {
                    return e.done(arguments).fail(arguments),
                    this
                },
                then: function() {
                    var a = arguments;
                    return kb.Deferred(function(c) {
                        kb.each(b,
                        function(b, f) {
                            var g = f[0],
                            h = kb.isFunction(a[b]) && a[b];
                            e[f[1]](function() {
                                var a = h && h.apply(this, arguments);
                                a && kb.isFunction(a.promise) ? a.promise().done(c.resolve).fail(c.reject).progress(c.notify) : c[g + "With"](this === d ? c.promise() : this, h ? [a] : arguments)
                            })
                        }),
                        a = null
                    }).promise()
                },
                promise: function(a) {
                    return null != a ? kb.extend(a, d) : d
                }
            },
            e = {};
            return d.pipe = d.then,
            kb.each(b,
            function(a, f) {
                var g = f[2],
                h = f[3];
                d[f[1]] = g.add,
                h && g.add(function() {
                    c = h
                },
                b[1 ^ a][2].disable, b[2][2].lock),
                e[f[0]] = function() {
                    return e[f[0] + "With"](this === e ? d: this, arguments),
                    this
                },
                e[f[0] + "With"] = g.fireWith
            }),
            d.promise(e),
            a && a.call(e, e),
            e
        },
        when: function(a) {
            var b, c, d, e = 0,
            f = fb.call(arguments),
            g = f.length,
            h = 1 !== g || a && kb.isFunction(a.promise) ? g: 0,
            i = 1 === h ? a: kb.Deferred(),
            j = function(a, c, d) {
                return function(e) {
                    c[a] = this,
                    d[a] = arguments.length > 1 ? fb.call(arguments) : e,
                    d === b ? i.notifyWith(c, d) : --h || i.resolveWith(c, d)
                }
            };
            if (g > 1) for (b = new Array(g), c = new Array(g), d = new Array(g); g > e; e++) f[e] && kb.isFunction(f[e].promise) ? f[e].promise().done(j(e, d, f)).fail(i.reject).progress(j(e, c, b)) : --h;
            return h || i.resolveWith(d, f),
            i.promise()
        }
    }),
    kb.support = function(b) {
        var c, d, e, f, g, h, i, j, k, l = Y.createElement("div");
        if (l.setAttribute("className", "t"), l.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", c = l.getElementsByTagName("*") || [], d = l.getElementsByTagName("a")[0], !d || !d.style || !c.length) return b;
        f = Y.createElement("select"),
        h = f.appendChild(Y.createElement("option")),
        e = l.getElementsByTagName("input")[0],
        d.style.cssText = "top:1px;float:left;opacity:.5",
        b.getSetAttribute = "t" !== l.className,
        b.leadingWhitespace = 3 === l.firstChild.nodeType,
        b.tbody = !l.getElementsByTagName("tbody").length,
        b.htmlSerialize = !!l.getElementsByTagName("link").length,
        b.style = /top/.test(d.getAttribute("style")),
        b.hrefNormalized = "/a" === d.getAttribute("href"),
        b.opacity = /^0.5/.test(d.style.opacity),
        b.cssFloat = !!d.style.cssFloat,
        b.checkOn = !!e.value,
        b.optSelected = h.selected,
        b.enctype = !!Y.createElement("form").enctype,
        b.html5Clone = "<:nav></:nav>" !== Y.createElement("nav").cloneNode(!0).outerHTML,
        b.inlineBlockNeedsLayout = !1,
        b.shrinkWrapBlocks = !1,
        b.pixelPosition = !1,
        b.deleteExpando = !0,
        b.noCloneEvent = !0,
        b.reliableMarginRight = !0,
        b.boxSizingReliable = !0,
        e.checked = !0,
        b.noCloneChecked = e.cloneNode(!0).checked,
        f.disabled = !0,
        b.optDisabled = !h.disabled;
        try {
            delete l.test
        } catch(m) {
            b.deleteExpando = !1
        }
        e = Y.createElement("input"),
        e.setAttribute("value", ""),
        b.input = "" === e.getAttribute("value"),
        e.value = "t",
        e.setAttribute("type", "radio"),
        b.radioValue = "t" === e.value,
        e.setAttribute("checked", "t"),
        e.setAttribute("name", "t"),
        g = Y.createDocumentFragment(),
        g.appendChild(e),
        b.appendChecked = e.checked,
        b.checkClone = g.cloneNode(!0).cloneNode(!0).lastChild.checked,
        l.attachEvent && (l.attachEvent("onclick",
        function() {
            b.noCloneEvent = !1
        }), l.cloneNode(!0).click());
        for (k in {
            submit: !0,
            change: !0,
            focusin: !0
        }) l.setAttribute(i = "on" + k, "t"),
        b[k + "Bubbles"] = i in a || l.attributes[i].expando === !1;
        l.style.backgroundClip = "content-box",
        l.cloneNode(!0).style.backgroundClip = "",
        b.clearCloneStyle = "content-box" === l.style.backgroundClip;
        for (k in kb(b)) break;
        return b.ownLast = "0" !== k,
        kb(function() {
            var c, d, e, f = "padding:0;margin:0;border:0;display:block;box-sizing:content-box;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;",
            g = Y.getElementsByTagName("body")[0];
            g && (c = Y.createElement("div"), c.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", g.appendChild(c).appendChild(l), l.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", e = l.getElementsByTagName("td"), e[0].style.cssText = "padding:0;margin:0;border:0;display:none", j = 0 === e[0].offsetHeight, e[0].style.display = "", e[1].style.display = "none", b.reliableHiddenOffsets = j && 0 === e[0].offsetHeight, l.innerHTML = "", l.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;", kb.swap(g, null != g.style.zoom ? {
                zoom: 1
            }: {},
            function() {
                b.boxSizing = 4 === l.offsetWidth
            }), a.getComputedStyle && (b.pixelPosition = "1%" !== (a.getComputedStyle(l, null) || {}).top, b.boxSizingReliable = "4px" === (a.getComputedStyle(l, null) || {
                width: "4px"
            }).width, d = l.appendChild(Y.createElement("div")), d.style.cssText = l.style.cssText = f, d.style.marginRight = d.style.width = "0", l.style.width = "1px", b.reliableMarginRight = !parseFloat((a.getComputedStyle(d, null) || {}).marginRight)), typeof l.style.zoom !== W && (l.innerHTML = "", l.style.cssText = f + "width:1px;padding:1px;display:inline;zoom:1", b.inlineBlockNeedsLayout = 3 === l.offsetWidth, l.style.display = "block", l.innerHTML = "<div></div>", l.firstChild.style.width = "5px", b.shrinkWrapBlocks = 3 !== l.offsetWidth, b.inlineBlockNeedsLayout && (g.style.zoom = 1)), g.removeChild(c), c = l = e = d = null)
        }),
        c = f = g = h = d = e = null,
        b
    } ({});
    var Ab = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/,
    Bb = /([A-Z])/g;
    kb.extend({
        cache: {},
        noData: {
            applet: !0,
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        hasData: function(a) {
            return a = a.nodeType ? kb.cache[a[kb.expando]] : a[kb.expando],
            !!a && !h(a)
        },
        data: function(a, b, c) {
            return e(a, b, c)
        },
        removeData: function(a, b) {
            return f(a, b)
        },
        _data: function(a, b, c) {
            return e(a, b, c, !0)
        },
        _removeData: function(a, b) {
            return f(a, b, !0)
        },
        acceptData: function(a) {
            if (a.nodeType && 1 !== a.nodeType && 9 !== a.nodeType) return ! 1;
            var b = a.nodeName && kb.noData[a.nodeName.toLowerCase()];
            return ! b || b !== !0 && a.getAttribute("classid") === b
        }
    }),
    kb.fn.extend({
        data: function(a, c) {
            var d, e, f = null,
            h = 0,
            i = this[0];
            if (a === b) {
                if (this.length && (f = kb.data(i), 1 === i.nodeType && !kb._data(i, "parsedAttrs"))) {
                    for (d = i.attributes; h < d.length; h++) e = d[h].name,
                    0 === e.indexOf("data-") && (e = kb.camelCase(e.slice(5)), g(i, e, f[e]));
                    kb._data(i, "parsedAttrs", !0)
                }
                return f
            }
            return "object" == typeof a ? this.each(function() {
                kb.data(this, a)
            }) : arguments.length > 1 ? this.each(function() {
                kb.data(this, a, c)
            }) : i ? g(i, a, kb.data(i, a)) : null
        },
        removeData: function(a) {
            return this.each(function() {
                kb.removeData(this, a)
            })
        }
    }),
    kb.extend({
        queue: function(a, b, c) {
            var d;
            return a ? (b = (b || "fx") + "queue", d = kb._data(a, b), c && (!d || kb.isArray(c) ? d = kb._data(a, b, kb.makeArray(c)) : d.push(c)), d || []) : void 0
        },
        dequeue: function(a, b) {
            b = b || "fx";
            var c = kb.queue(a, b),
            d = c.length,
            e = c.shift(),
            f = kb._queueHooks(a, b),
            g = function() {
                kb.dequeue(a, b)
            };
            "inprogress" === e && (e = c.shift(), d--),
            e && ("fx" === b && c.unshift("inprogress"), delete f.stop, e.call(a, g, f)),
            !d && f && f.empty.fire()
        },
        _queueHooks: function(a, b) {
            var c = b + "queueHooks";
            return kb._data(a, c) || kb._data(a, c, {
                empty: kb.Callbacks("once memory").add(function() {
                    kb._removeData(a, b + "queue"),
                    kb._removeData(a, c)
                })
            })
        }
    }),
    kb.fn.extend({
        queue: function(a, c) {
            var d = 2;
            return "string" != typeof a && (c = a, a = "fx", d--),
            arguments.length < d ? kb.queue(this[0], a) : c === b ? this: this.each(function() {
                var b = kb.queue(this, a, c);
                kb._queueHooks(this, a),
                "fx" === a && "inprogress" !== b[0] && kb.dequeue(this, a)
            })
        },
        dequeue: function(a) {
            return this.each(function() {
                kb.dequeue(this, a)
            })
        },
        delay: function(a, b) {
            return a = kb.fx ? kb.fx.speeds[a] || a: a,
            b = b || "fx",
            this.queue(b,
            function(b, c) {
                var d = setTimeout(b, a);
                c.stop = function() {
                    clearTimeout(d)
                }
            })
        },
        clearQueue: function(a) {
            return this.queue(a || "fx", [])
        },
        promise: function(a, c) {
            var d, e = 1,
            f = kb.Deferred(),
            g = this,
            h = this.length,
            i = function() {--e || f.resolveWith(g, [g])
            };
            for ("string" != typeof a && (c = a, a = b), a = a || "fx"; h--;) d = kb._data(g[h], a + "queueHooks"),
            d && d.empty && (e++, d.empty.add(i));
            return i(),
            f.promise(c)
        }
    });
    var Cb, Db, Eb = /[\t\r\n\f]/g,
    Fb = /\r/g,
    Gb = /^(?:input|select|textarea|button|object)$/i,
    Hb = /^(?:a|area)$/i,
    Ib = /^(?:checked|selected)$/i,
    Jb = kb.support.getSetAttribute,
    Kb = kb.support.input;
    kb.fn.extend({
        attr: function(a, b) {
            return kb.access(this, kb.attr, a, b, arguments.length > 1)
        },
        removeAttr: function(a) {
            return this.each(function() {
                kb.removeAttr(this, a)
            })
        },
        prop: function(a, b) {
            return kb.access(this, kb.prop, a, b, arguments.length > 1)
        },
        removeProp: function(a) {
            return a = kb.propFix[a] || a,
            this.each(function() {
                try {
                    this[a] = b,
                    delete this[a]
                } catch(c) {}
            })
        },
        addClass: function(a) {
            var b, c, d, e, f, g = 0,
            h = this.length,
            i = "string" == typeof a && a;
            if (kb.isFunction(a)) return this.each(function(b) {
                kb(this).addClass(a.call(this, b, this.className))
            });
            if (i) for (b = (a || "").match(mb) || []; h > g; g++) if (c = this[g], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(Eb, " ") : " ")) {
                for (f = 0; e = b[f++];) d.indexOf(" " + e + " ") < 0 && (d += e + " ");
                c.className = kb.trim(d)
            }
            return this
        },
        removeClass: function(a) {
            var b, c, d, e, f, g = 0,
            h = this.length,
            i = 0 === arguments.length || "string" == typeof a && a;
            if (kb.isFunction(a)) return this.each(function(b) {
                kb(this).removeClass(a.call(this, b, this.className))
            });
            if (i) for (b = (a || "").match(mb) || []; h > g; g++) if (c = this[g], d = 1 === c.nodeType && (c.className ? (" " + c.className + " ").replace(Eb, " ") : "")) {
                for (f = 0; e = b[f++];) for (; d.indexOf(" " + e + " ") >= 0;) d = d.replace(" " + e + " ", " ");
                c.className = a ? kb.trim(d) : ""
            }
            return this
        },
        toggleClass: function(a, b) {
            var c = typeof a;
            return "boolean" == typeof b && "string" === c ? b ? this.addClass(a) : this.removeClass(a) : kb.isFunction(a) ? this.each(function(c) {
                kb(this).toggleClass(a.call(this, c, this.className, b), b)
            }) : this.each(function() {
                if ("string" === c) for (var b, d = 0,
                e = kb(this), f = a.match(mb) || []; b = f[d++];) e.hasClass(b) ? e.removeClass(b) : e.addClass(b);
                else(c === W || "boolean" === c) && (this.className && kb._data(this, "__className__", this.className), this.className = this.className || a === !1 ? "": kb._data(this, "__className__") || "")
            })
        },
        hasClass: function(a) {
            for (var b = " " + a + " ",
            c = 0,
            d = this.length; d > c; c++) if (1 === this[c].nodeType && (" " + this[c].className + " ").replace(Eb, " ").indexOf(b) >= 0) return ! 0;
            return ! 1
        },
        val: function(a) {
            var c, d, e, f = this[0]; {
                if (arguments.length) return e = kb.isFunction(a),
                this.each(function(c) {
                    var f;
                    1 === this.nodeType && (f = e ? a.call(this, c, kb(this).val()) : a, null == f ? f = "": "number" == typeof f ? f += "": kb.isArray(f) && (f = kb.map(f,
                    function(a) {
                        return null == a ? "": a + ""
                    })), d = kb.valHooks[this.type] || kb.valHooks[this.nodeName.toLowerCase()], d && "set" in d && d.set(this, f, "value") !== b || (this.value = f))
                });
                if (f) return d = kb.valHooks[f.type] || kb.valHooks[f.nodeName.toLowerCase()],
                d && "get" in d && (c = d.get(f, "value")) !== b ? c: (c = f.value, "string" == typeof c ? c.replace(Fb, "") : null == c ? "": c)
            }
        }
    }),
    kb.extend({
        valHooks: {
            option: {
                get: function(a) {
                    var b = kb.find.attr(a, "value");
                    return null != b ? b: a.text
                }
            },
            select: {
                get: function(a) {
                    for (var b, c, d = a.options,
                    e = a.selectedIndex,
                    f = "select-one" === a.type || 0 > e,
                    g = f ? null: [], h = f ? e + 1 : d.length, i = 0 > e ? h: f ? e: 0; h > i; i++) if (c = d[i], !(!c.selected && i !== e || (kb.support.optDisabled ? c.disabled: null !== c.getAttribute("disabled")) || c.parentNode.disabled && kb.nodeName(c.parentNode, "optgroup"))) {
                        if (b = kb(c).val(), f) return b;
                        g.push(b)
                    }
                    return g
                },
                set: function(a, b) {
                    for (var c, d, e = a.options,
                    f = kb.makeArray(b), g = e.length; g--;) d = e[g],
                    (d.selected = kb.inArray(kb(d).val(), f) >= 0) && (c = !0);
                    return c || (a.selectedIndex = -1),
                    f
                }
            }
        },
        attr: function(a, c, d) {
            var e, f, g = a.nodeType;
            if (a && 3 !== g && 8 !== g && 2 !== g) return typeof a.getAttribute === W ? kb.prop(a, c, d) : (1 === g && kb.isXMLDoc(a) || (c = c.toLowerCase(), e = kb.attrHooks[c] || (kb.expr.match.bool.test(c) ? Db: Cb)), d === b ? e && "get" in e && null !== (f = e.get(a, c)) ? f: (f = kb.find.attr(a, c), null == f ? b: f) : null !== d ? e && "set" in e && (f = e.set(a, d, c)) !== b ? f: (a.setAttribute(c, d + ""), d) : (kb.removeAttr(a, c), void 0))
        },
        removeAttr: function(a, b) {
            var c, d, e = 0,
            f = b && b.match(mb);
            if (f && 1 === a.nodeType) for (; c = f[e++];) d = kb.propFix[c] || c,
            kb.expr.match.bool.test(c) ? Kb && Jb || !Ib.test(c) ? a[d] = !1 : a[kb.camelCase("default-" + c)] = a[d] = !1 : kb.attr(a, c, ""),
            a.removeAttribute(Jb ? c: d)
        },
        attrHooks: {
            type: {
                set: function(a, b) {
                    if (!kb.support.radioValue && "radio" === b && kb.nodeName(a, "input")) {
                        var c = a.value;
                        return a.setAttribute("type", b),
                        c && (a.value = c),
                        b
                    }
                }
            }
        },
        propFix: {
            "for": "htmlFor",
            "class": "className"
        },
        prop: function(a, c, d) {
            var e, f, g, h = a.nodeType;
            if (a && 3 !== h && 8 !== h && 2 !== h) return g = 1 !== h || !kb.isXMLDoc(a),
            g && (c = kb.propFix[c] || c, f = kb.propHooks[c]),
            d !== b ? f && "set" in f && (e = f.set(a, d, c)) !== b ? e: a[c] = d: f && "get" in f && null !== (e = f.get(a, c)) ? e: a[c]
        },
        propHooks: {
            tabIndex: {
                get: function(a) {
                    var b = kb.find.attr(a, "tabindex");
                    return b ? parseInt(b, 10) : Gb.test(a.nodeName) || Hb.test(a.nodeName) && a.href ? 0 : -1
                }
            }
        }
    }),
    Db = {
        set: function(a, b, c) {
            return b === !1 ? kb.removeAttr(a, c) : Kb && Jb || !Ib.test(c) ? a.setAttribute(!Jb && kb.propFix[c] || c, c) : a[kb.camelCase("default-" + c)] = a[c] = !0,
            c
        }
    },
    kb.each(kb.expr.match.bool.source.match(/\w+/g),
    function(a, c) {
        var d = kb.expr.attrHandle[c] || kb.find.attr;
        kb.expr.attrHandle[c] = Kb && Jb || !Ib.test(c) ?
        function(a, c, e) {
            var f = kb.expr.attrHandle[c],
            g = e ? b: (kb.expr.attrHandle[c] = b) != d(a, c, e) ? c.toLowerCase() : null;
            return kb.expr.attrHandle[c] = f,
            g
        }: function(a, c, d) {
            return d ? b: a[kb.camelCase("default-" + c)] ? c.toLowerCase() : null
        }
    }),
    Kb && Jb || (kb.attrHooks.value = {
        set: function(a, b, c) {
            return kb.nodeName(a, "input") ? (a.defaultValue = b, void 0) : Cb && Cb.set(a, b, c)
        }
    }),
    Jb || (Cb = {
        set: function(a, c, d) {
            var e = a.getAttributeNode(d);
            return e || a.setAttributeNode(e = a.ownerDocument.createAttribute(d)),
            e.value = c += "",
            "value" === d || c === a.getAttribute(d) ? c: b
        }
    },
    kb.expr.attrHandle.id = kb.expr.attrHandle.name = kb.expr.attrHandle.coords = function(a, c, d) {
        var e;
        return d ? b: (e = a.getAttributeNode(c)) && "" !== e.value ? e.value: null
    },
    kb.valHooks.button = {
        get: function(a, c) {
            var d = a.getAttributeNode(c);
            return d && d.specified ? d.value: b
        },
        set: Cb.set
    },
    kb.attrHooks.contenteditable = {
        set: function(a, b, c) {
            Cb.set(a, "" === b ? !1 : b, c)
        }
    },
    kb.each(["width", "height"],
    function(a, b) {
        kb.attrHooks[b] = {
            set: function(a, c) {
                return "" === c ? (a.setAttribute(b, "auto"), c) : void 0
            }
        }
    })),
    kb.support.hrefNormalized || kb.each(["href", "src"],
    function(a, b) {
        kb.propHooks[b] = {
            get: function(a) {
                return a.getAttribute(b, 4)
            }
        }
    }),
    kb.support.style || (kb.attrHooks.style = {
        get: function(a) {
            return a.style.cssText || b
        },
        set: function(a, b) {
            return a.style.cssText = b + ""
        }
    }),
    kb.support.optSelected || (kb.propHooks.selected = {
        get: function(a) {
            var b = a.parentNode;
            return b && (b.selectedIndex, b.parentNode && b.parentNode.selectedIndex),
            null
        }
    }),
    kb.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"],
    function() {
        kb.propFix[this.toLowerCase()] = this
    }),
    kb.support.enctype || (kb.propFix.enctype = "encoding"),
    kb.each(["radio", "checkbox"],
    function() {
        kb.valHooks[this] = {
            set: function(a, b) {
                return kb.isArray(b) ? a.checked = kb.inArray(kb(a).val(), b) >= 0 : void 0
            }
        },
        kb.support.checkOn || (kb.valHooks[this].get = function(a) {
            return null === a.getAttribute("value") ? "on": a.value
        })
    });
    var Lb = /^(?:input|select|textarea)$/i,
    Mb = /^key/,
    Nb = /^(?:mouse|contextmenu)|click/,
    Ob = /^(?:focusinfocus|focusoutblur)$/,
    Pb = /^([^.]*)(?:\.(.+)|)$/;
    kb.event = {
        global: {},
        add: function(a, c, d, e, f) {
            var g, h, i, j, k, l, m, n, o, p, q, r = kb._data(a);
            if (r) {
                for (d.handler && (j = d, d = j.handler, f = j.selector), d.guid || (d.guid = kb.guid++), (h = r.events) || (h = r.events = {}), (l = r.handle) || (l = r.handle = function(a) {
                    return typeof kb === W || a && kb.event.triggered === a.type ? b: kb.event.dispatch.apply(l.elem, arguments)
                },
                l.elem = a), c = (c || "").match(mb) || [""], i = c.length; i--;) g = Pb.exec(c[i]) || [],
                o = q = g[1],
                p = (g[2] || "").split(".").sort(),
                o && (k = kb.event.special[o] || {},
                o = (f ? k.delegateType: k.bindType) || o, k = kb.event.special[o] || {},
                m = kb.extend({
                    type: o,
                    origType: q,
                    data: e,
                    handler: d,
                    guid: d.guid,
                    selector: f,
                    needsContext: f && kb.expr.match.needsContext.test(f),
                    namespace: p.join(".")
                },
                j), (n = h[o]) || (n = h[o] = [], n.delegateCount = 0, k.setup && k.setup.call(a, e, p, l) !== !1 || (a.addEventListener ? a.addEventListener(o, l, !1) : a.attachEvent && a.attachEvent("on" + o, l))), k.add && (k.add.call(a, m), m.handler.guid || (m.handler.guid = d.guid)), f ? n.splice(n.delegateCount++, 0, m) : n.push(m), kb.event.global[o] = !0);
                a = null
            }
        },
        remove: function(a, b, c, d, e) {
            var f, g, h, i, j, k, l, m, n, o, p, q = kb.hasData(a) && kb._data(a);
            if (q && (k = q.events)) {
                for (b = (b || "").match(mb) || [""], j = b.length; j--;) if (h = Pb.exec(b[j]) || [], n = p = h[1], o = (h[2] || "").split(".").sort(), n) {
                    for (l = kb.event.special[n] || {},
                    n = (d ? l.delegateType: l.bindType) || n, m = k[n] || [], h = h[2] && new RegExp("(^|\\.)" + o.join("\\.(?:.*\\.|)") + "(\\.|$)"), i = f = m.length; f--;) g = m[f],
                    !e && p !== g.origType || c && c.guid !== g.guid || h && !h.test(g.namespace) || d && d !== g.selector && ("**" !== d || !g.selector) || (m.splice(f, 1), g.selector && m.delegateCount--, l.remove && l.remove.call(a, g));
                    i && !m.length && (l.teardown && l.teardown.call(a, o, q.handle) !== !1 || kb.removeEvent(a, n, q.handle), delete k[n])
                } else for (n in k) kb.event.remove(a, n + b[j], c, d, !0);
                kb.isEmptyObject(k) && (delete q.handle, kb._removeData(a, "events"))
            }
        },
        trigger: function(c, d, e, f) {
            var g, h, i, j, k, l, m, n = [e || Y],
            o = ib.call(c, "type") ? c.type: c,
            p = ib.call(c, "namespace") ? c.namespace.split(".") : [];
            if (i = l = e = e || Y, 3 !== e.nodeType && 8 !== e.nodeType && !Ob.test(o + kb.event.triggered) && (o.indexOf(".") >= 0 && (p = o.split("."), o = p.shift(), p.sort()), h = o.indexOf(":") < 0 && "on" + o, c = c[kb.expando] ? c: new kb.Event(o, "object" == typeof c && c), c.isTrigger = f ? 2 : 3, c.namespace = p.join("."), c.namespace_re = c.namespace ? new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, c.result = b, c.target || (c.target = e), d = null == d ? [c] : kb.makeArray(d, [c]), k = kb.event.special[o] || {},
            f || !k.trigger || k.trigger.apply(e, d) !== !1)) {
                if (!f && !k.noBubble && !kb.isWindow(e)) {
                    for (j = k.delegateType || o, Ob.test(j + o) || (i = i.parentNode); i; i = i.parentNode) n.push(i),
                    l = i;
                    l === (e.ownerDocument || Y) && n.push(l.defaultView || l.parentWindow || a)
                }
                for (m = 0; (i = n[m++]) && !c.isPropagationStopped();) c.type = m > 1 ? j: k.bindType || o,
                g = (kb._data(i, "events") || {})[c.type] && kb._data(i, "handle"),
                g && g.apply(i, d),
                g = h && i[h],
                g && kb.acceptData(i) && g.apply && g.apply(i, d) === !1 && c.preventDefault();
                if (c.type = o, !f && !c.isDefaultPrevented() && (!k._default || k._default.apply(n.pop(), d) === !1) && kb.acceptData(e) && h && e[o] && !kb.isWindow(e)) {
                    l = e[h],
                    l && (e[h] = null),
                    kb.event.triggered = o;
                    try {
                        e[o]()
                    } catch(q) {}
                    kb.event.triggered = b,
                    l && (e[h] = l)
                }
                return c.result
            }
        },
        dispatch: function(a) {
            a = kb.event.fix(a);
            var c, d, e, f, g, h = [],
            i = fb.call(arguments),
            j = (kb._data(this, "events") || {})[a.type] || [],
            k = kb.event.special[a.type] || {};
            if (i[0] = a, a.delegateTarget = this, !k.preDispatch || k.preDispatch.call(this, a) !== !1) {
                for (h = kb.event.handlers.call(this, a, j), c = 0; (f = h[c++]) && !a.isPropagationStopped();) for (a.currentTarget = f.elem, g = 0; (e = f.handlers[g++]) && !a.isImmediatePropagationStopped();)(!a.namespace_re || a.namespace_re.test(e.namespace)) && (a.handleObj = e, a.data = e.data, d = ((kb.event.special[e.origType] || {}).handle || e.handler).apply(f.elem, i), d !== b && (a.result = d) === !1 && (a.preventDefault(), a.stopPropagation()));
                return k.postDispatch && k.postDispatch.call(this, a),
                a.result
            }
        },
        handlers: function(a, c) {
            var d, e, f, g, h = [],
            i = c.delegateCount,
            j = a.target;
            if (i && j.nodeType && (!a.button || "click" !== a.type)) for (; j != this; j = j.parentNode || this) if (1 === j.nodeType && (j.disabled !== !0 || "click" !== a.type)) {
                for (f = [], g = 0; i > g; g++) e = c[g],
                d = e.selector + " ",
                f[d] === b && (f[d] = e.needsContext ? kb(d, this).index(j) >= 0 : kb.find(d, this, null, [j]).length),
                f[d] && f.push(e);
                f.length && h.push({
                    elem: j,
                    handlers: f
                })
            }
            return i < c.length && h.push({
                elem: this,
                handlers: c.slice(i)
            }),
            h
        },
        fix: function(a) {
            if (a[kb.expando]) return a;
            var b, c, d, e = a.type,
            f = a,
            g = this.fixHooks[e];
            for (g || (this.fixHooks[e] = g = Nb.test(e) ? this.mouseHooks: Mb.test(e) ? this.keyHooks: {}), d = g.props ? this.props.concat(g.props) : this.props, a = new kb.Event(f), b = d.length; b--;) c = d[b],
            a[c] = f[c];
            return a.target || (a.target = f.srcElement || Y),
            3 === a.target.nodeType && (a.target = a.target.parentNode),
            a.metaKey = !!a.metaKey,
            g.filter ? g.filter(a, f) : a
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(a, b) {
                return null == a.which && (a.which = null != b.charCode ? b.charCode: b.keyCode),
                a
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(a, c) {
                var d, e, f, g = c.button,
                h = c.fromElement;
                return null == a.pageX && null != c.clientX && (e = a.target.ownerDocument || Y, f = e.documentElement, d = e.body, a.pageX = c.clientX + (f && f.scrollLeft || d && d.scrollLeft || 0) - (f && f.clientLeft || d && d.clientLeft || 0), a.pageY = c.clientY + (f && f.scrollTop || d && d.scrollTop || 0) - (f && f.clientTop || d && d.clientTop || 0)),
                !a.relatedTarget && h && (a.relatedTarget = h === a.target ? c.toElement: h),
                a.which || g === b || (a.which = 1 & g ? 1 : 2 & g ? 3 : 4 & g ? 2 : 0),
                a
            }
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function() {
                    if (this !== k() && this.focus) try {
                        return this.focus(),
                        !1
                    } catch(a) {}
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    return this === k() && this.blur ? (this.blur(), !1) : void 0
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    return kb.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                },
                _default: function(a) {
                    return kb.nodeName(a.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function(a) {
                    a.result !== b && (a.originalEvent.returnValue = a.result)
                }
            }
        },
        simulate: function(a, b, c, d) {
            var e = kb.extend(new kb.Event, c, {
                type: a,
                isSimulated: !0,
                originalEvent: {}
            });
            d ? kb.event.trigger(e, null, b) : kb.event.dispatch.call(b, e),
            e.isDefaultPrevented() && c.preventDefault()
        }
    },
    kb.removeEvent = Y.removeEventListener ?
    function(a, b, c) {
        a.removeEventListener && a.removeEventListener(b, c, !1)
    }: function(a, b, c) {
        var d = "on" + b;
        a.detachEvent && (typeof a[d] === W && (a[d] = null), a.detachEvent(d, c))
    },
    kb.Event = function(a, b) {
        return this instanceof kb.Event ? (a && a.type ? (this.originalEvent = a, this.type = a.type, this.isDefaultPrevented = a.defaultPrevented || a.returnValue === !1 || a.getPreventDefault && a.getPreventDefault() ? i: j) : this.type = a, b && kb.extend(this, b), this.timeStamp = a && a.timeStamp || kb.now(), this[kb.expando] = !0, void 0) : new kb.Event(a, b)
    },
    kb.Event.prototype = {
        isDefaultPrevented: j,
        isPropagationStopped: j,
        isImmediatePropagationStopped: j,
        preventDefault: function() {
            var a = this.originalEvent;
            this.isDefaultPrevented = i,
            a && (a.preventDefault ? a.preventDefault() : a.returnValue = !1)
        },
        stopPropagation: function() {
            var a = this.originalEvent;
            this.isPropagationStopped = i,
            a && (a.stopPropagation && a.stopPropagation(), a.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = i,
            this.stopPropagation()
        }
    },
    kb.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    },
    function(a, b) {
        kb.event.special[a] = {
            delegateType: b,
            bindType: b,
            handle: function(a) {
                var c, d = this,
                e = a.relatedTarget,
                f = a.handleObj;
                return (!e || e !== d && !kb.contains(d, e)) && (a.type = f.origType, c = f.handler.apply(this, arguments), a.type = b),
                c
            }
        }
    }),
    kb.support.submitBubbles || (kb.event.special.submit = {
        setup: function() {
            return kb.nodeName(this, "form") ? !1 : (kb.event.add(this, "click._submit keypress._submit",
            function(a) {
                var c = a.target,
                d = kb.nodeName(c, "input") || kb.nodeName(c, "button") ? c.form: b;
                d && !kb._data(d, "submitBubbles") && (kb.event.add(d, "submit._submit",
                function(a) {
                    a._submit_bubble = !0
                }), kb._data(d, "submitBubbles", !0))
            }), void 0)
        },
        postDispatch: function(a) {
            a._submit_bubble && (delete a._submit_bubble, this.parentNode && !a.isTrigger && kb.event.simulate("submit", this.parentNode, a, !0))
        },
        teardown: function() {
            return kb.nodeName(this, "form") ? !1 : (kb.event.remove(this, "._submit"), void 0)
        }
    }),
    kb.support.changeBubbles || (kb.event.special.change = {
        setup: function() {
            return Lb.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (kb.event.add(this, "propertychange._change",
            function(a) {
                "checked" === a.originalEvent.propertyName && (this._just_changed = !0)
            }), kb.event.add(this, "click._change",
            function(a) {
                this._just_changed && !a.isTrigger && (this._just_changed = !1),
                kb.event.simulate("change", this, a, !0)
            })), !1) : (kb.event.add(this, "beforeactivate._change",
            function(a) {
                var b = a.target;
                Lb.test(b.nodeName) && !kb._data(b, "changeBubbles") && (kb.event.add(b, "change._change",
                function(a) { ! this.parentNode || a.isSimulated || a.isTrigger || kb.event.simulate("change", this.parentNode, a, !0)
                }), kb._data(b, "changeBubbles", !0))
            }), void 0)
        },
        handle: function(a) {
            var b = a.target;
            return this !== b || a.isSimulated || a.isTrigger || "radio" !== b.type && "checkbox" !== b.type ? a.handleObj.handler.apply(this, arguments) : void 0
        },
        teardown: function() {
            return kb.event.remove(this, "._change"),
            !Lb.test(this.nodeName)
        }
    }),
    kb.support.focusinBubbles || kb.each({
        focus: "focusin",
        blur: "focusout"
    },
    function(a, b) {
        var c = 0,
        d = function(a) {
            kb.event.simulate(b, a.target, kb.event.fix(a), !0)
        };
        kb.event.special[b] = {
            setup: function() {
                0 === c++&&Y.addEventListener(a, d, !0)
            },
            teardown: function() {
                0 === --c && Y.removeEventListener(a, d, !0)
            }
        }
    }),
    kb.fn.extend({
        on: function(a, c, d, e, f) {
            var g, h;
            if ("object" == typeof a) {
                "string" != typeof c && (d = d || c, c = b);
                for (g in a) this.on(g, c, d, a[g], f);
                return this
            }
            if (null == d && null == e ? (e = c, d = c = b) : null == e && ("string" == typeof c ? (e = d, d = b) : (e = d, d = c, c = b)), e === !1) e = j;
            else if (!e) return this;
            return 1 === f && (h = e, e = function(a) {
                return kb().off(a),
                h.apply(this, arguments)
            },
            e.guid = h.guid || (h.guid = kb.guid++)),
            this.each(function() {
                kb.event.add(this, a, e, d, c)
            })
        },
        one: function(a, b, c, d) {
            return this.on(a, b, c, d, 1)
        },
        off: function(a, c, d) {
            var e, f;
            if (a && a.preventDefault && a.handleObj) return e = a.handleObj,
            kb(a.delegateTarget).off(e.namespace ? e.origType + "." + e.namespace: e.origType, e.selector, e.handler),
            this;
            if ("object" == typeof a) {
                for (f in a) this.off(f, c, a[f]);
                return this
            }
            return (c === !1 || "function" == typeof c) && (d = c, c = b),
            d === !1 && (d = j),
            this.each(function() {
                kb.event.remove(this, a, d, c)
            })
        },
        trigger: function(a, b) {
            return this.each(function() {
                kb.event.trigger(a, b, this)
            })
        },
        triggerHandler: function(a, b) {
            var c = this[0];
            return c ? kb.event.trigger(a, b, c, !0) : void 0
        }
    });
    var Qb = /^.[^:#\[\.,]*$/,
    Rb = /^(?:parents|prev(?:Until|All))/,
    Sb = kb.expr.match.needsContext,
    Tb = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    kb.fn.extend({
        find: function(a) {
            var b, c = [],
            d = this,
            e = d.length;
            if ("string" != typeof a) return this.pushStack(kb(a).filter(function() {
                for (b = 0; e > b; b++) if (kb.contains(d[b], this)) return ! 0
            }));
            for (b = 0; e > b; b++) kb.find(a, d[b], c);
            return c = this.pushStack(e > 1 ? kb.unique(c) : c),
            c.selector = this.selector ? this.selector + " " + a: a,
            c
        },
        has: function(a) {
            var b, c = kb(a, this),
            d = c.length;
            return this.filter(function() {
                for (b = 0; d > b; b++) if (kb.contains(this, c[b])) return ! 0
            })
        },
        not: function(a) {
            return this.pushStack(m(this, a || [], !0))
        },
        filter: function(a) {
            return this.pushStack(m(this, a || [], !1))
        },
        is: function(a) {
            return !! m(this, "string" == typeof a && Sb.test(a) ? kb(a) : a || [], !1).length
        },
        closest: function(a, b) {
            for (var c, d = 0,
            e = this.length,
            f = [], g = Sb.test(a) || "string" != typeof a ? kb(a, b || this.context) : 0; e > d; d++) for (c = this[d]; c && c !== b; c = c.parentNode) if (c.nodeType < 11 && (g ? g.index(c) > -1 : 1 === c.nodeType && kb.find.matchesSelector(c, a))) {
                c = f.push(c);
                break
            }
            return this.pushStack(f.length > 1 ? kb.unique(f) : f)
        },
        index: function(a) {
            return a ? "string" == typeof a ? kb.inArray(this[0], kb(a)) : kb.inArray(a.jquery ? a[0] : a, this) : this[0] && this[0].parentNode ? this.first().prevAll().length: -1
        },
        add: function(a, b) {
            var c = "string" == typeof a ? kb(a, b) : kb.makeArray(a && a.nodeType ? [a] : a),
            d = kb.merge(this.get(), c);
            return this.pushStack(kb.unique(d))
        },
        addBack: function(a) {
            return this.add(null == a ? this.prevObject: this.prevObject.filter(a))
        }
    }),
    kb.each({
        parent: function(a) {
            var b = a.parentNode;
            return b && 11 !== b.nodeType ? b: null
        },
        parents: function(a) {
            return kb.dir(a, "parentNode")
        },
        parentsUntil: function(a, b, c) {
            return kb.dir(a, "parentNode", c)
        },
        next: function(a) {
            return l(a, "nextSibling")
        },
        prev: function(a) {
            return l(a, "previousSibling")
        },
        nextAll: function(a) {
            return kb.dir(a, "nextSibling")
        },
        prevAll: function(a) {
            return kb.dir(a, "previousSibling")
        },
        nextUntil: function(a, b, c) {
            return kb.dir(a, "nextSibling", c)
        },
        prevUntil: function(a, b, c) {
            return kb.dir(a, "previousSibling", c)
        },
        siblings: function(a) {
            return kb.sibling((a.parentNode || {}).firstChild, a)
        },
        children: function(a) {
            return kb.sibling(a.firstChild)
        },
        contents: function(a) {
            return kb.nodeName(a, "iframe") ? a.contentDocument || a.contentWindow.document: kb.merge([], a.childNodes)
        }
    },
    function(a, b) {
        kb.fn[a] = function(c, d) {
            var e = kb.map(this, b, c);
            return "Until" !== a.slice( - 5) && (d = c),
            d && "string" == typeof d && (e = kb.filter(d, e)),
            this.length > 1 && (Tb[a] || (e = kb.unique(e)), Rb.test(a) && (e = e.reverse())),
            this.pushStack(e)
        }
    }),
    kb.extend({
        filter: function(a, b, c) {
            var d = b[0];
            return c && (a = ":not(" + a + ")"),
            1 === b.length && 1 === d.nodeType ? kb.find.matchesSelector(d, a) ? [d] : [] : kb.find.matches(a, kb.grep(b,
            function(a) {
                return 1 === a.nodeType
            }))
        },
        dir: function(a, c, d) {
            for (var e = [], f = a[c]; f && 9 !== f.nodeType && (d === b || 1 !== f.nodeType || !kb(f).is(d));) 1 === f.nodeType && e.push(f),
            f = f[c];
            return e
        },
        sibling: function(a, b) {
            for (var c = []; a; a = a.nextSibling) 1 === a.nodeType && a !== b && c.push(a);
            return c
        }
    });
    var Ub = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
    Vb = / jQuery\d+="(?:null|\d+)"/g,
    Wb = new RegExp("<(?:" + Ub + ")[\\s/>]", "i"),
    Xb = /^\s+/,
    Yb = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
    Zb = /<([\w:]+)/,
    $b = /<tbody/i,
    _b = /<|&#?\w+;/,
    ac = /<(?:script|style|link)/i,
    bc = /^(?:checkbox|radio)$/i,
    cc = /checked\s*(?:[^=]|=\s*.checked.)/i,
    dc = /^$|\/(?:java|ecma)script/i,
    ec = /^true\/(.*)/,
    fc = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
    gc = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        area: [1, "<map>", "</map>"],
        param: [1, "<object>", "</object>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: kb.support.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    },
    hc = n(Y),
    ic = hc.appendChild(Y.createElement("div"));
    gc.optgroup = gc.option,
    gc.tbody = gc.tfoot = gc.colgroup = gc.caption = gc.thead,
    gc.th = gc.td,
    kb.fn.extend({
        text: function(a) {
            return kb.access(this,
            function(a) {
                return a === b ? kb.text(this) : this.empty().append((this[0] && this[0].ownerDocument || Y).createTextNode(a))
            },
            null, a, arguments.length)
        },
        append: function() {
            return this.domManip(arguments,
            function(a) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var b = o(this, a);
                    b.appendChild(a)
                }
            })
        },
        prepend: function() {
            return this.domManip(arguments,
            function(a) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var b = o(this, a);
                    b.insertBefore(a, b.firstChild)
                }
            })
        },
        before: function() {
            return this.domManip(arguments,
            function(a) {
                this.parentNode && this.parentNode.insertBefore(a, this)
            })
        },
        after: function() {
            return this.domManip(arguments,
            function(a) {
                this.parentNode && this.parentNode.insertBefore(a, this.nextSibling)
            })
        },
        remove: function(a, b) {
            for (var c, d = a ? kb.filter(a, this) : this, e = 0; null != (c = d[e]); e++) b || 1 !== c.nodeType || kb.cleanData(u(c)),
            c.parentNode && (b && kb.contains(c.ownerDocument, c) && r(u(c, "script")), c.parentNode.removeChild(c));
            return this
        },
        empty: function() {
            for (var a, b = 0; null != (a = this[b]); b++) {
                for (1 === a.nodeType && kb.cleanData(u(a, !1)); a.firstChild;) a.removeChild(a.firstChild);
                a.options && kb.nodeName(a, "select") && (a.options.length = 0)
            }
            return this
        },
        clone: function(a, b) {
            return a = null == a ? !1 : a,
            b = null == b ? a: b,
            this.map(function() {
                return kb.clone(this, a, b)
            })
        },
        html: function(a) {
            return kb.access(this,
            function(a) {
                var c = this[0] || {},
                d = 0,
                e = this.length;
                if (a === b) return 1 === c.nodeType ? c.innerHTML.replace(Vb, "") : b;
                if (! ("string" != typeof a || ac.test(a) || !kb.support.htmlSerialize && Wb.test(a) || !kb.support.leadingWhitespace && Xb.test(a) || gc[(Zb.exec(a) || ["", ""])[1].toLowerCase()])) {
                    a = a.replace(Yb, "<$1></$2>");
                    try {
                        for (; e > d; d++) c = this[d] || {},
                        1 === c.nodeType && (kb.cleanData(u(c, !1)), c.innerHTML = a);
                        c = 0
                    } catch(f) {}
                }
                c && this.empty().append(a)
            },
            null, a, arguments.length)
        },
        replaceWith: function() {
            var a = kb.map(this,
            function(a) {
                return [a.nextSibling, a.parentNode]
            }),
            b = 0;
            return this.domManip(arguments,
            function(c) {
                var d = a[b++],
                e = a[b++];
                e && (d && d.parentNode !== e && (d = this.nextSibling), kb(this).remove(), e.insertBefore(c, d))
            },
            !0),
            b ? this: this.remove()
        },
        detach: function(a) {
            return this.remove(a, !0)
        },
        domManip: function(a, b, c) {
            a = db.apply([], a);
            var d, e, f, g, h, i, j = 0,
            k = this.length,
            l = this,
            m = k - 1,
            n = a[0],
            o = kb.isFunction(n);
            if (o || !(1 >= k || "string" != typeof n || kb.support.checkClone) && cc.test(n)) return this.each(function(d) {
                var e = l.eq(d);
                o && (a[0] = n.call(this, d, e.html())),
                e.domManip(a, b, c)
            });
            if (k && (i = kb.buildFragment(a, this[0].ownerDocument, !1, !c && this), d = i.firstChild, 1 === i.childNodes.length && (i = d), d)) {
                for (g = kb.map(u(i, "script"), p), f = g.length; k > j; j++) e = i,
                j !== m && (e = kb.clone(e, !0, !0), f && kb.merge(g, u(e, "script"))),
                b.call(this[j], e, j);
                if (f) for (h = g[g.length - 1].ownerDocument, kb.map(g, q), j = 0; f > j; j++) e = g[j],
                dc.test(e.type || "") && !kb._data(e, "globalEval") && kb.contains(h, e) && (e.src ? kb._evalUrl(e.src) : kb.globalEval((e.text || e.textContent || e.innerHTML || "").replace(fc, "")));
                i = d = null
            }
            return this
        }
    }),
    kb.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    },
    function(a, b) {
        kb.fn[a] = function(a) {
            for (var c, d = 0,
            e = [], f = kb(a), g = f.length - 1; g >= d; d++) c = d === g ? this: this.clone(!0),
            kb(f[d])[b](c),
            eb.apply(e, c.get());
            return this.pushStack(e)
        }
    }),
    kb.extend({
        clone: function(a, b, c) {
            var d, e, f, g, h, i = kb.contains(a.ownerDocument, a);
            if (kb.support.html5Clone || kb.isXMLDoc(a) || !Wb.test("<" + a.nodeName + ">") ? f = a.cloneNode(!0) : (ic.innerHTML = a.outerHTML, ic.removeChild(f = ic.firstChild)), !(kb.support.noCloneEvent && kb.support.noCloneChecked || 1 !== a.nodeType && 11 !== a.nodeType || kb.isXMLDoc(a))) for (d = u(f), h = u(a), g = 0; null != (e = h[g]); ++g) d[g] && t(e, d[g]);
            if (b) if (c) for (h = h || u(a), d = d || u(f), g = 0; null != (e = h[g]); g++) s(e, d[g]);
            else s(a, f);
            return d = u(f, "script"),
            d.length > 0 && r(d, !i && u(a, "script")),
            d = h = e = null,
            f
        },
        buildFragment: function(a, b, c, d) {
            for (var e, f, g, h, i, j, k, l = a.length,
            m = n(b), o = [], p = 0; l > p; p++) if (f = a[p], f || 0 === f) if ("object" === kb.type(f)) kb.merge(o, f.nodeType ? [f] : f);
            else if (_b.test(f)) {
                for (h = h || m.appendChild(b.createElement("div")), i = (Zb.exec(f) || ["", ""])[1].toLowerCase(), k = gc[i] || gc._default, h.innerHTML = k[1] + f.replace(Yb, "<$1></$2>") + k[2], e = k[0]; e--;) h = h.lastChild;
                if (!kb.support.leadingWhitespace && Xb.test(f) && o.push(b.createTextNode(Xb.exec(f)[0])), !kb.support.tbody) for (f = "table" !== i || $b.test(f) ? "<table>" !== k[1] || $b.test(f) ? 0 : h: h.firstChild, e = f && f.childNodes.length; e--;) kb.nodeName(j = f.childNodes[e], "tbody") && !j.childNodes.length && f.removeChild(j);
                for (kb.merge(o, h.childNodes), h.textContent = ""; h.firstChild;) h.removeChild(h.firstChild);
                h = m.lastChild
            } else o.push(b.createTextNode(f));
            for (h && m.removeChild(h), kb.support.appendChecked || kb.grep(u(o, "input"), v), p = 0; f = o[p++];) if ((!d || -1 === kb.inArray(f, d)) && (g = kb.contains(f.ownerDocument, f), h = u(m.appendChild(f), "script"), g && r(h), c)) for (e = 0; f = h[e++];) dc.test(f.type || "") && c.push(f);
            return h = null,
            m
        },
        cleanData: function(a, b) {
            for (var c, d, e, f, g = 0,
            h = kb.expando,
            i = kb.cache,
            j = kb.support.deleteExpando,
            k = kb.event.special; null != (c = a[g]); g++) if ((b || kb.acceptData(c)) && (e = c[h], f = e && i[e])) {
                if (f.events) for (d in f.events) k[d] ? kb.event.remove(c, d) : kb.removeEvent(c, d, f.handle);
                i[e] && (delete i[e], j ? delete c[h] : typeof c.removeAttribute !== W ? c.removeAttribute(h) : c[h] = null, bb.push(e))
            }
        },
        _evalUrl: function(a) {
            return kb.ajax({
                url: a,
                type: "GET",
                dataType: "script",
                async: !1,
                global: !1,
                "throws": !0
            })
        }
    }),
    kb.fn.extend({
        wrapAll: function(a) {
            if (kb.isFunction(a)) return this.each(function(b) {
                kb(this).wrapAll(a.call(this, b))
            });
            if (this[0]) {
                var b = kb(a, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && b.insertBefore(this[0]),
                b.map(function() {
                    for (var a = this; a.firstChild && 1 === a.firstChild.nodeType;) a = a.firstChild;
                    return a
                }).append(this)
            }
            return this
        },
        wrapInner: function(a) {
            return kb.isFunction(a) ? this.each(function(b) {
                kb(this).wrapInner(a.call(this, b))
            }) : this.each(function() {
                var b = kb(this),
                c = b.contents();
                c.length ? c.wrapAll(a) : b.append(a)
            })
        },
        wrap: function(a) {
            var b = kb.isFunction(a);
            return this.each(function(c) {
                kb(this).wrapAll(b ? a.call(this, c) : a)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                kb.nodeName(this, "body") || kb(this).replaceWith(this.childNodes)
            }).end()
        }
    });
    var jc, kc, lc, mc = /alpha\([^)]*\)/i,
    nc = /opacity\s*=\s*([^)]*)/,
    oc = /^(top|right|bottom|left)$/,
    pc = /^(none|table(?!-c[ea]).+)/,
    qc = /^margin/,
    rc = new RegExp("^(" + lb + ")(.*)$", "i"),
    sc = new RegExp("^(" + lb + ")(?!px)[a-z%]+$", "i"),
    tc = new RegExp("^([+-])=(" + lb + ")", "i"),
    uc = {
        BODY: "block"
    },
    vc = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    },
    wc = {
        letterSpacing: 0,
        fontWeight: 400
    },
    xc = ["Top", "Right", "Bottom", "Left"],
    yc = ["Webkit", "O", "Moz", "ms"];
    kb.fn.extend({
        css: function(a, c) {
            return kb.access(this,
            function(a, c, d) {
                var e, f, g = {},
                h = 0;
                if (kb.isArray(c)) {
                    for (f = kc(a), e = c.length; e > h; h++) g[c[h]] = kb.css(a, c[h], !1, f);
                    return g
                }
                return d !== b ? kb.style(a, c, d) : kb.css(a, c)
            },
            a, c, arguments.length > 1)
        },
        show: function() {
            return y(this, !0)
        },
        hide: function() {
            return y(this)
        },
        toggle: function(a) {
            return "boolean" == typeof a ? a ? this.show() : this.hide() : this.each(function() {
                x(this) ? kb(this).show() : kb(this).hide()
            })
        }
    }),
    kb.extend({
        cssHooks: {
            opacity: {
                get: function(a, b) {
                    if (b) {
                        var c = lc(a, "opacity");
                        return "" === c ? "1": c
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": kb.support.cssFloat ? "cssFloat": "styleFloat"
        },
        style: function(a, c, d, e) {
            if (a && 3 !== a.nodeType && 8 !== a.nodeType && a.style) {
                var f, g, h, i = kb.camelCase(c),
                j = a.style;
                if (c = kb.cssProps[i] || (kb.cssProps[i] = w(j, i)), h = kb.cssHooks[c] || kb.cssHooks[i], d === b) return h && "get" in h && (f = h.get(a, !1, e)) !== b ? f: j[c];
                if (g = typeof d, "string" === g && (f = tc.exec(d)) && (d = (f[1] + 1) * f[2] + parseFloat(kb.css(a, c)), g = "number"), !(null == d || "number" === g && isNaN(d) || ("number" !== g || kb.cssNumber[i] || (d += "px"), kb.support.clearCloneStyle || "" !== d || 0 !== c.indexOf("background") || (j[c] = "inherit"), h && "set" in h && (d = h.set(a, d, e)) === b))) try {
                    j[c] = d
                } catch(k) {}
            }
        },
        css: function(a, c, d, e) {
            var f, g, h, i = kb.camelCase(c);
            return c = kb.cssProps[i] || (kb.cssProps[i] = w(a.style, i)),
            h = kb.cssHooks[c] || kb.cssHooks[i],
            h && "get" in h && (g = h.get(a, !0, d)),
            g === b && (g = lc(a, c, e)),
            "normal" === g && c in wc && (g = wc[c]),
            "" === d || d ? (f = parseFloat(g), d === !0 || kb.isNumeric(f) ? f || 0 : g) : g
        }
    }),
    a.getComputedStyle ? (kc = function(b) {
        return a.getComputedStyle(b, null)
    },
    lc = function(a, c, d) {
        var e, f, g, h = d || kc(a),
        i = h ? h.getPropertyValue(c) || h[c] : b,
        j = a.style;
        return h && ("" !== i || kb.contains(a.ownerDocument, a) || (i = kb.style(a, c)), sc.test(i) && qc.test(c) && (e = j.width, f = j.minWidth, g = j.maxWidth, j.minWidth = j.maxWidth = j.width = i, i = h.width, j.width = e, j.minWidth = f, j.maxWidth = g)),
        i
    }) : Y.documentElement.currentStyle && (kc = function(a) {
        return a.currentStyle
    },
    lc = function(a, c, d) {
        var e, f, g, h = d || kc(a),
        i = h ? h[c] : b,
        j = a.style;
        return null == i && j && j[c] && (i = j[c]),
        sc.test(i) && !oc.test(c) && (e = j.left, f = a.runtimeStyle, g = f && f.left, g && (f.left = a.currentStyle.left), j.left = "fontSize" === c ? "1em": i, i = j.pixelLeft + "px", j.left = e, g && (f.left = g)),
        "" === i ? "auto": i
    }),
    kb.each(["height", "width"],
    function(a, b) {
        kb.cssHooks[b] = {
            get: function(a, c, d) {
                return c ? 0 === a.offsetWidth && pc.test(kb.css(a, "display")) ? kb.swap(a, vc,
                function() {
                    return B(a, b, d)
                }) : B(a, b, d) : void 0
            },
            set: function(a, c, d) {
                var e = d && kc(a);
                return z(a, c, d ? A(a, b, d, kb.support.boxSizing && "border-box" === kb.css(a, "boxSizing", !1, e), e) : 0)
            }
        }
    }),
    kb.support.opacity || (kb.cssHooks.opacity = {
        get: function(a, b) {
            return nc.test((b && a.currentStyle ? a.currentStyle.filter: a.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "": b ? "1": ""
        },
        set: function(a, b) {
            var c = a.style,
            d = a.currentStyle,
            e = kb.isNumeric(b) ? "alpha(opacity=" + 100 * b + ")": "",
            f = d && d.filter || c.filter || "";
            c.zoom = 1,
            (b >= 1 || "" === b) && "" === kb.trim(f.replace(mc, "")) && c.removeAttribute && (c.removeAttribute("filter"), "" === b || d && !d.filter) || (c.filter = mc.test(f) ? f.replace(mc, e) : f + " " + e)
        }
    }),
    kb(function() {
        kb.support.reliableMarginRight || (kb.cssHooks.marginRight = {
            get: function(a, b) {
                return b ? kb.swap(a, {
                    display: "inline-block"
                },
                lc, [a, "marginRight"]) : void 0
            }
        }),
        !kb.support.pixelPosition && kb.fn.position && kb.each(["top", "left"],
        function(a, b) {
            kb.cssHooks[b] = {
                get: function(a, c) {
                    return c ? (c = lc(a, b), sc.test(c) ? kb(a).position()[b] + "px": c) : void 0
                }
            }
        })
    }),
    kb.expr && kb.expr.filters && (kb.expr.filters.hidden = function(a) {
        return a.offsetWidth <= 0 && a.offsetHeight <= 0 || !kb.support.reliableHiddenOffsets && "none" === (a.style && a.style.display || kb.css(a, "display"))
    },
    kb.expr.filters.visible = function(a) {
        return ! kb.expr.filters.hidden(a)
    }),
    kb.each({
        margin: "",
        padding: "",
        border: "Width"
    },
    function(a, b) {
        kb.cssHooks[a + b] = {
            expand: function(c) {
                for (var d = 0,
                e = {},
                f = "string" == typeof c ? c.split(" ") : [c]; 4 > d; d++) e[a + xc[d] + b] = f[d] || f[d - 2] || f[0];
                return e
            }
        },
        qc.test(a) || (kb.cssHooks[a + b].set = z)
    });
    var zc = /%20/g,
    Ac = /\[\]$/,
    Bc = /\r?\n/g,
    Cc = /^(?:submit|button|image|reset|file)$/i,
    Dc = /^(?:input|select|textarea|keygen)/i;
    kb.fn.extend({
        serialize: function() {
            return kb.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var a = kb.prop(this, "elements");
                return a ? kb.makeArray(a) : this
            }).filter(function() {
                var a = this.type;
                return this.name && !kb(this).is(":disabled") && Dc.test(this.nodeName) && !Cc.test(a) && (this.checked || !bc.test(a))
            }).map(function(a, b) {
                var c = kb(this).val();
                return null == c ? null: kb.isArray(c) ? kb.map(c,
                function(a) {
                    return {
                        name: b.name,
                        value: a.replace(Bc, "\r\n")
                    }
                }) : {
                    name: b.name,
                    value: c.replace(Bc, "\r\n")
                }
            }).get()
        }
    }),
    kb.param = function(a, c) {
        var d, e = [],
        f = function(a, b) {
            b = kb.isFunction(b) ? b() : null == b ? "": b,
            e[e.length] = encodeURIComponent(a) + "=" + encodeURIComponent(b)
        };
        if (c === b && (c = kb.ajaxSettings && kb.ajaxSettings.traditional), kb.isArray(a) || a.jquery && !kb.isPlainObject(a)) kb.each(a,
        function() {
            f(this.name, this.value)
        });
        else for (d in a) E(d, a[d], c, f);
        return e.join("&").replace(zc, "+")
    },
    kb.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),
    function(a, b) {
        kb.fn[b] = function(a, c) {
            return arguments.length > 0 ? this.on(b, null, a, c) : this.trigger(b)
        }
    }),
    kb.fn.extend({
        hover: function(a, b) {
            return this.mouseenter(a).mouseleave(b || a)
        },
        bind: function(a, b, c) {
            return this.on(a, null, b, c)
        },
        unbind: function(a, b) {
            return this.off(a, null, b)
        },
        delegate: function(a, b, c, d) {
            return this.on(b, a, c, d)
        },
        undelegate: function(a, b, c) {
            return 1 === arguments.length ? this.off(a, "**") : this.off(b, a || "**", c)
        }
    });
    var Ec, Fc, Gc = kb.now(),
    Hc = /\?/,
    Ic = /#.*$/,
    Jc = /([?&])_=[^&]*/,
    Kc = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
    Lc = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/,
    Mc = /^(?:GET|HEAD)$/,
    Nc = /^\/\//,
    Oc = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/,
    Pc = kb.fn.load,
    Qc = {},
    Rc = {},
    Sc = "*/".concat("*");
    try {
        Fc = X.href
    } catch(Tc) {
        Fc = Y.createElement("a"),
        Fc.href = "",
        Fc = Fc.href
    }
    Ec = Oc.exec(Fc.toLowerCase()) || [],
    kb.fn.load = function(a, c, d) {
        if ("string" != typeof a && Pc) return Pc.apply(this, arguments);
        var e, f, g, h = this,
        i = a.indexOf(" ");
        return i >= 0 && (e = a.slice(i, a.length), a = a.slice(0, i)),
        kb.isFunction(c) ? (d = c, c = b) : c && "object" == typeof c && (g = "POST"),
        h.length > 0 && kb.ajax({
            url: a,
            type: g,
            dataType: "html",
            data: c
        }).done(function(a) {
            f = arguments,
            h.html(e ? kb("<div>").append(kb.parseHTML(a)).find(e) : a)
        }).complete(d &&
        function(a, b) {
            h.each(d, f || [a.responseText, b, a])
        }),
        this
    },
    kb.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"],
    function(a, b) {
        kb.fn[b] = function(a) {
            return this.on(b, a)
        }
    }),
    kb.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Fc,
            type: "GET",
            isLocal: Lc.test(Ec[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Sc,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": kb.parseJSON,
                "text xml": kb.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(a, b) {
            return b ? H(H(a, kb.ajaxSettings), b) : H(kb.ajaxSettings, a)
        },
        ajaxPrefilter: F(Qc),
        ajaxTransport: F(Rc),
        ajax: function(a, c) {
            function d(a, c, d, e) {
                var f, l, s, t, v, x = c;
                2 !== u && (u = 2, i && clearTimeout(i), k = b, h = e || "", w.readyState = a > 0 ? 4 : 0, f = a >= 200 && 300 > a || 304 === a, d && (t = I(m, w, d)), t = J(m, t, w, f), f ? (m.ifModified && (v = w.getResponseHeader("Last-Modified"), v && (kb.lastModified[g] = v), v = w.getResponseHeader("etag"), v && (kb.etag[g] = v)), 204 === a || "HEAD" === m.type ? x = "nocontent": 304 === a ? x = "notmodified": (x = t.state, l = t.data, s = t.error, f = !s)) : (s = x, (a || !x) && (x = "error", 0 > a && (a = 0))), w.status = a, w.statusText = (c || x) + "", f ? p.resolveWith(n, [l, x, w]) : p.rejectWith(n, [w, x, s]), w.statusCode(r), r = b, j && o.trigger(f ? "ajaxSuccess": "ajaxError", [w, m, f ? l: s]), q.fireWith(n, [w, x]), j && (o.trigger("ajaxComplete", [w, m]), --kb.active || kb.event.trigger("ajaxStop")))
            }
            "object" == typeof a && (c = a, a = b),
            c = c || {};
            var e, f, g, h, i, j, k, l, m = kb.ajaxSetup({},
            c),
            n = m.context || m,
            o = m.context && (n.nodeType || n.jquery) ? kb(n) : kb.event,
            p = kb.Deferred(),
            q = kb.Callbacks("once memory"),
            r = m.statusCode || {},
            s = {},
            t = {},
            u = 0,
            v = "canceled",
            w = {
                readyState: 0,
                getResponseHeader: function(a) {
                    var b;
                    if (2 === u) {
                        if (!l) for (l = {}; b = Kc.exec(h);) l[b[1].toLowerCase()] = b[2];
                        b = l[a.toLowerCase()]
                    }
                    return null == b ? null: b
                },
                getAllResponseHeaders: function() {
                    return 2 === u ? h: null
                },
                setRequestHeader: function(a, b) {
                    var c = a.toLowerCase();
                    return u || (a = t[c] = t[c] || a, s[a] = b),
                    this
                },
                overrideMimeType: function(a) {
                    return u || (m.mimeType = a),
                    this
                },
                statusCode: function(a) {
                    var b;
                    if (a) if (2 > u) for (b in a) r[b] = [r[b], a[b]];
                    else w.always(a[w.status]);
                    return this
                },
                abort: function(a) {
                    var b = a || v;
                    return k && k.abort(b),
                    d(0, b),
                    this
                }
            };
            if (p.promise(w).complete = q.add, w.success = w.done, w.error = w.fail, m.url = ((a || m.url || Fc) + "").replace(Ic, "").replace(Nc, Ec[1] + "//"), m.type = c.method || c.type || m.method || m.type, m.dataTypes = kb.trim(m.dataType || "*").toLowerCase().match(mb) || [""], null == m.crossDomain && (e = Oc.exec(m.url.toLowerCase()), m.crossDomain = !(!e || e[1] === Ec[1] && e[2] === Ec[2] && (e[3] || ("http:" === e[1] ? "80": "443")) === (Ec[3] || ("http:" === Ec[1] ? "80": "443")))), m.data && m.processData && "string" != typeof m.data && (m.data = kb.param(m.data, m.traditional)), G(Qc, m, c, w), 2 === u) return w;
            j = m.global,
            j && 0 === kb.active++&&kb.event.trigger("ajaxStart"),
            m.type = m.type.toUpperCase(),
            m.hasContent = !Mc.test(m.type),
            g = m.url,
            m.hasContent || (m.data && (g = m.url += (Hc.test(g) ? "&": "?") + m.data, delete m.data), m.cache === !1 && (m.url = Jc.test(g) ? g.replace(Jc, "$1_=" + Gc++) : g + (Hc.test(g) ? "&": "?") + "_=" + Gc++)),
            m.ifModified && (kb.lastModified[g] && w.setRequestHeader("If-Modified-Since", kb.lastModified[g]), kb.etag[g] && w.setRequestHeader("If-None-Match", kb.etag[g])),
            (m.data && m.hasContent && m.contentType !== !1 || c.contentType) && w.setRequestHeader("Content-Type", m.contentType),
            w.setRequestHeader("Accept", m.dataTypes[0] && m.accepts[m.dataTypes[0]] ? m.accepts[m.dataTypes[0]] + ("*" !== m.dataTypes[0] ? ", " + Sc + "; q=0.01": "") : m.accepts["*"]);
            for (f in m.headers) w.setRequestHeader(f, m.headers[f]);
            if (m.beforeSend && (m.beforeSend.call(n, w, m) === !1 || 2 === u)) return w.abort();
            v = "abort";
            for (f in {
                success: 1,
                error: 1,
                complete: 1
            }) w[f](m[f]);
            if (k = G(Rc, m, c, w)) {
                w.readyState = 1,
                j && o.trigger("ajaxSend", [w, m]),
                m.async && m.timeout > 0 && (i = setTimeout(function() {
                    w.abort("timeout")
                },
                m.timeout));
                try {
                    u = 1,
                    k.send(s, d)
                } catch(x) {
                    if (! (2 > u)) throw x;
                    d( - 1, x)
                }
            } else d( - 1, "No Transport");
            return w
        },
        getJSON: function(a, b, c) {
            return kb.get(a, b, c, "json")
        },
        getScript: function(a, c) {
            return kb.get(a, b, c, "script")
        }
    }),
    kb.each(["get", "post"],
    function(a, c) {
        kb[c] = function(a, d, e, f) {
            return kb.isFunction(d) && (f = f || e, e = d, d = b),
            kb.ajax({
                url: a,
                type: c,
                dataType: f,
                data: d,
                success: e
            })
        }
    }),
    kb.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(a) {
                return kb.globalEval(a),
                a
            }
        }
    }),
    kb.ajaxPrefilter("script",
    function(a) {
        a.cache === b && (a.cache = !1),
        a.crossDomain && (a.type = "GET", a.global = !1)
    }),
    kb.ajaxTransport("script",
    function(a) {
        if (a.crossDomain) {
            var c, d = Y.head || kb("head")[0] || Y.documentElement;
            return {
                send: function(b, e) {
                    c = Y.createElement("script"),
                    c.async = !0,
                    a.scriptCharset && (c.charset = a.scriptCharset),
                    c.src = a.url,
                    c.onload = c.onreadystatechange = function(a, b) { (b || !c.readyState || /loaded|complete/.test(c.readyState)) && (c.onload = c.onreadystatechange = null, c.parentNode && c.parentNode.removeChild(c), c = null, b || e(200, "success"))
                    },
                    d.insertBefore(c, d.firstChild)
                },
                abort: function() {
                    c && c.onload(b, !0)
                }
            }
        }
    });
    var Uc = [],
    Vc = /(=)\?(?=&|$)|\?\?/;
    kb.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var a = Uc.pop() || kb.expando + "_" + Gc++;
            return this[a] = !0,
            a
        }
    }),
    kb.ajaxPrefilter("json jsonp",
    function(c, d, e) {
        var f, g, h, i = c.jsonp !== !1 && (Vc.test(c.url) ? "url": "string" == typeof c.data && !(c.contentType || "").indexOf("application/x-www-form-urlencoded") && Vc.test(c.data) && "data");
        return i || "jsonp" === c.dataTypes[0] ? (f = c.jsonpCallback = kb.isFunction(c.jsonpCallback) ? c.jsonpCallback() : c.jsonpCallback, i ? c[i] = c[i].replace(Vc, "$1" + f) : c.jsonp !== !1 && (c.url += (Hc.test(c.url) ? "&": "?") + c.jsonp + "=" + f), c.converters["script json"] = function() {
            return h || kb.error(f + " was not called"),
            h[0]
        },
        c.dataTypes[0] = "json", g = a[f], a[f] = function() {
            h = arguments
        },
        e.always(function() {
            a[f] = g,
            c[f] && (c.jsonpCallback = d.jsonpCallback, Uc.push(f)),
            h && kb.isFunction(g) && g(h[0]),
            h = g = b
        }), "script") : void 0
    });
    var Wc, Xc, Yc = 0,
    Zc = a.ActiveXObject &&
    function() {
        var a;
        for (a in Wc) Wc[a](b, !0)
    };
    kb.ajaxSettings.xhr = a.ActiveXObject ?
    function() {
        return ! this.isLocal && K() || L()
    }: K,
    Xc = kb.ajaxSettings.xhr(),
    kb.support.cors = !!Xc && "withCredentials" in Xc,
    Xc = kb.support.ajax = !!Xc,
    Xc && kb.ajaxTransport(function(c) {
        if (!c.crossDomain || kb.support.cors) {
            var d;
            return {
                send: function(e, f) {
                    var g, h, i = c.xhr();
                    if (c.username ? i.open(c.type, c.url, c.async, c.username, c.password) : i.open(c.type, c.url, c.async), c.xhrFields) for (h in c.xhrFields) i[h] = c.xhrFields[h];
                    c.mimeType && i.overrideMimeType && i.overrideMimeType(c.mimeType),
                    c.crossDomain || e["X-Requested-With"] || (e["X-Requested-With"] = "XMLHttpRequest");
                    try {
                        for (h in e) i.setRequestHeader(h, e[h])
                    } catch(j) {}
                    i.send(c.hasContent && c.data || null),
                    d = function(a, e) {
                        var h, j, k, l;
                        try {
                            if (d && (e || 4 === i.readyState)) if (d = b, g && (i.onreadystatechange = kb.noop, Zc && delete Wc[g]), e) 4 !== i.readyState && i.abort();
                            else {
                                l = {},
                                h = i.status,
                                j = i.getAllResponseHeaders(),
                                "string" == typeof i.responseText && (l.text = i.responseText);
                                try {
                                    k = i.statusText
                                } catch(m) {
                                    k = ""
                                }
                                h || !c.isLocal || c.crossDomain ? 1223 === h && (h = 204) : h = l.text ? 200 : 404
                            }
                        } catch(n) {
                            e || f( - 1, n)
                        }
                        l && f(h, k, l, j)
                    },
                    c.async ? 4 === i.readyState ? setTimeout(d) : (g = ++Yc, Zc && (Wc || (Wc = {},
                    kb(a).unload(Zc)), Wc[g] = d), i.onreadystatechange = d) : d()
                },
                abort: function() {
                    d && d(b, !0)
                }
            }
        }
    });
    var $c, _c, ad = /^(?:toggle|show|hide)$/,
    bd = new RegExp("^(?:([+-])=|)(" + lb + ")([a-z%]*)$", "i"),
    cd = /queueHooks$/,
    dd = [Q],
    ed = {
        "*": [function(a, b) {
            var c = this.createTween(a, b),
            d = c.cur(),
            e = bd.exec(b),
            f = e && e[3] || (kb.cssNumber[a] ? "": "px"),
            g = (kb.cssNumber[a] || "px" !== f && +d) && bd.exec(kb.css(c.elem, a)),
            h = 1,
            i = 20;
            if (g && g[3] !== f) {
                f = f || g[3],
                e = e || [],
                g = +d || 1;
                do h = h || ".5",
                g /= h,
                kb.style(c.elem, a, g + f);
                while (h !== (h = c.cur() / d) && 1 !== h && --i)
            }
            return e && (g = c.start = +g || +d || 0, c.unit = f, c.end = e[1] ? g + (e[1] + 1) * e[2] : +e[2]),
            c
        }]
    };
    kb.Animation = kb.extend(O, {
        tweener: function(a, b) {
            kb.isFunction(a) ? (b = a, a = ["*"]) : a = a.split(" ");
            for (var c, d = 0,
            e = a.length; e > d; d++) c = a[d],
            ed[c] = ed[c] || [],
            ed[c].unshift(b)
        },
        prefilter: function(a, b) {
            b ? dd.unshift(a) : dd.push(a)
        }
    }),
    kb.Tween = R,
    R.prototype = {
        constructor: R,
        init: function(a, b, c, d, e, f) {
            this.elem = a,
            this.prop = c,
            this.easing = e || "swing",
            this.options = b,
            this.start = this.now = this.cur(),
            this.end = d,
            this.unit = f || (kb.cssNumber[c] ? "": "px")
        },
        cur: function() {
            var a = R.propHooks[this.prop];
            return a && a.get ? a.get(this) : R.propHooks._default.get(this)
        },
        run: function(a) {
            var b, c = R.propHooks[this.prop];
            return this.pos = b = this.options.duration ? kb.easing[this.easing](a, this.options.duration * a, 0, 1, this.options.duration) : a,
            this.now = (this.end - this.start) * b + this.start,
            this.options.step && this.options.step.call(this.elem, this.now, this),
            c && c.set ? c.set(this) : R.propHooks._default.set(this),
            this
        }
    },
    R.prototype.init.prototype = R.prototype,
    R.propHooks = {
        _default: {
            get: function(a) {
                var b;
                return null == a.elem[a.prop] || a.elem.style && null != a.elem.style[a.prop] ? (b = kb.css(a.elem, a.prop, ""), b && "auto" !== b ? b: 0) : a.elem[a.prop]
            },
            set: function(a) {
                kb.fx.step[a.prop] ? kb.fx.step[a.prop](a) : a.elem.style && (null != a.elem.style[kb.cssProps[a.prop]] || kb.cssHooks[a.prop]) ? kb.style(a.elem, a.prop, a.now + a.unit) : a.elem[a.prop] = a.now
            }
        }
    },
    R.propHooks.scrollTop = R.propHooks.scrollLeft = {
        set: function(a) {
            a.elem.nodeType && a.elem.parentNode && (a.elem[a.prop] = a.now)
        }
    },
    kb.each(["toggle", "show", "hide"],
    function(a, b) {
        var c = kb.fn[b];
        kb.fn[b] = function(a, d, e) {
            return null == a || "boolean" == typeof a ? c.apply(this, arguments) : this.animate(S(b, !0), a, d, e)
        }
    }),
    kb.fn.extend({
        fadeTo: function(a, b, c, d) {
            return this.filter(x).css("opacity", 0).show().end().animate({
                opacity: b
            },
            a, c, d)
        },
        animate: function(a, b, c, d) {
            var e = kb.isEmptyObject(a),
            f = kb.speed(b, c, d),
            g = function() {
                var b = O(this, kb.extend({},
                a), f); (e || kb._data(this, "finish")) && b.stop(!0)
            };
            return g.finish = g,
            e || f.queue === !1 ? this.each(g) : this.queue(f.queue, g)
        },
        stop: function(a, c, d) {
            var e = function(a) {
                var b = a.stop;
                delete a.stop,
                b(d)
            };
            return "string" != typeof a && (d = c, c = a, a = b),
            c && a !== !1 && this.queue(a || "fx", []),
            this.each(function() {
                var b = !0,
                c = null != a && a + "queueHooks",
                f = kb.timers,
                g = kb._data(this);
                if (c) g[c] && g[c].stop && e(g[c]);
                else for (c in g) g[c] && g[c].stop && cd.test(c) && e(g[c]);
                for (c = f.length; c--;) f[c].elem !== this || null != a && f[c].queue !== a || (f[c].anim.stop(d), b = !1, f.splice(c, 1)); (b || !d) && kb.dequeue(this, a)
            })
        },
        finish: function(a) {
            return a !== !1 && (a = a || "fx"),
            this.each(function() {
                var b, c = kb._data(this),
                d = c[a + "queue"],
                e = c[a + "queueHooks"],
                f = kb.timers,
                g = d ? d.length: 0;
                for (c.finish = !0, kb.queue(this, a, []), e && e.stop && e.stop.call(this, !0), b = f.length; b--;) f[b].elem === this && f[b].queue === a && (f[b].anim.stop(!0), f.splice(b, 1));
                for (b = 0; g > b; b++) d[b] && d[b].finish && d[b].finish.call(this);
                delete c.finish
            })
        }
    }),
    kb.each({
        slideDown: S("show"),
        slideUp: S("hide"),
        slideToggle: S("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    },
    function(a, b) {
        kb.fn[a] = function(a, c, d) {
            return this.animate(b, a, c, d)
        }
    }),
    kb.speed = function(a, b, c) {
        var d = a && "object" == typeof a ? kb.extend({},
        a) : {
            complete: c || !c && b || kb.isFunction(a) && a,
            duration: a,
            easing: c && b || b && !kb.isFunction(b) && b
        };
        return d.duration = kb.fx.off ? 0 : "number" == typeof d.duration ? d.duration: d.duration in kb.fx.speeds ? kb.fx.speeds[d.duration] : kb.fx.speeds._default,
        (null == d.queue || d.queue === !0) && (d.queue = "fx"),
        d.old = d.complete,
        d.complete = function() {
            kb.isFunction(d.old) && d.old.call(this),
            d.queue && kb.dequeue(this, d.queue)
        },
        d
    },
    kb.easing = {
        linear: function(a) {
            return a
        },
        swing: function(a) {
            return.5 - Math.cos(a * Math.PI) / 2
        }
    },
    kb.timers = [],
    kb.fx = R.prototype.init,
    kb.fx.tick = function() {
        var a, c = kb.timers,
        d = 0;
        for ($c = kb.now(); d < c.length; d++) a = c[d],
        a() || c[d] !== a || c.splice(d--, 1);
        c.length || kb.fx.stop(),
        $c = b
    },
    kb.fx.timer = function(a) {
        a() && kb.timers.push(a) && kb.fx.start()
    },
    kb.fx.interval = 13,
    kb.fx.start = function() {
        _c || (_c = setInterval(kb.fx.tick, kb.fx.interval))
    },
    kb.fx.stop = function() {
        clearInterval(_c),
        _c = null
    },
    kb.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    },
    kb.fx.step = {},
    kb.expr && kb.expr.filters && (kb.expr.filters.animated = function(a) {
        return kb.grep(kb.timers,
        function(b) {
            return a === b.elem
        }).length
    }),
    kb.fn.offset = function(a) {
        if (arguments.length) return a === b ? this: this.each(function(b) {
            kb.offset.setOffset(this, a, b)
        });
        var c, d, e = {
            top: 0,
            left: 0
        },
        f = this[0],
        g = f && f.ownerDocument;
        if (g) return c = g.documentElement,
        kb.contains(c, f) ? (typeof f.getBoundingClientRect !== W && (e = f.getBoundingClientRect()), d = T(g), {
            top: e.top + (d.pageYOffset || c.scrollTop) - (c.clientTop || 0),
            left: e.left + (d.pageXOffset || c.scrollLeft) - (c.clientLeft || 0)
        }) : e
    },
    kb.offset = {
        setOffset: function(a, b, c) {
            var d = kb.css(a, "position");
            "static" === d && (a.style.position = "relative");
            var e, f, g = kb(a),
            h = g.offset(),
            i = kb.css(a, "top"),
            j = kb.css(a, "left"),
            k = ("absolute" === d || "fixed" === d) && kb.inArray("auto", [i, j]) > -1,
            l = {},
            m = {};
            k ? (m = g.position(), e = m.top, f = m.left) : (e = parseFloat(i) || 0, f = parseFloat(j) || 0),
            kb.isFunction(b) && (b = b.call(a, c, h)),
            null != b.top && (l.top = b.top - h.top + e),
            null != b.left && (l.left = b.left - h.left + f),
            "using" in b ? b.using.call(a, l) : g.css(l)
        }
    },
    kb.fn.extend({
        position: function() {
            if (this[0]) {
                var a, b, c = {
                    top: 0,
                    left: 0
                },
                d = this[0];
                return "fixed" === kb.css(d, "position") ? b = d.getBoundingClientRect() : (a = this.offsetParent(), b = this.offset(), kb.nodeName(a[0], "html") || (c = a.offset()), c.top += kb.css(a[0], "borderTopWidth", !0), c.left += kb.css(a[0], "borderLeftWidth", !0)),
                {
                    top: b.top - c.top - kb.css(d, "marginTop", !0),
                    left: b.left - c.left - kb.css(d, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var a = this.offsetParent || Z; a && !kb.nodeName(a, "html") && "static" === kb.css(a, "position");) a = a.offsetParent;
                return a || Z
            })
        }
    }),
    kb.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    },
    function(a, c) {
        var d = /Y/.test(c);
        kb.fn[a] = function(e) {
            return kb.access(this,
            function(a, e, f) {
                var g = T(a);
                return f === b ? g ? c in g ? g[c] : g.document.documentElement[e] : a[e] : (g ? g.scrollTo(d ? kb(g).scrollLeft() : f, d ? f: kb(g).scrollTop()) : a[e] = f, void 0)
            },
            a, e, arguments.length, null)
        }
    }),
    kb.each({
        Height: "height",
        Width: "width"
    },
    function(a, c) {
        kb.each({
            padding: "inner" + a,
            content: c,
            "": "outer" + a
        },
        function(d, e) {
            kb.fn[e] = function(e, f) {
                var g = arguments.length && (d || "boolean" != typeof e),
                h = d || (e === !0 || f === !0 ? "margin": "border");
                return kb.access(this,
                function(c, d, e) {
                    var f;
                    return kb.isWindow(c) ? c.document.documentElement["client" + a] : 9 === c.nodeType ? (f = c.documentElement, Math.max(c.body["scroll" + a], f["scroll" + a], c.body["offset" + a], f["offset" + a], f["client" + a])) : e === b ? kb.css(c, d, h) : kb.style(c, d, e, h)
                },
                c, g ? e: b, g, null)
            }
        })
    }),
    kb.fn.size = function() {
        return this.length
    },
    kb.fn.andSelf = kb.fn.addBack,
    "object" == typeof module && module && "object" == typeof module.exports ? module.exports = kb: (a.jQuery = a.$ = kb, "function" == typeof define && define.amd && define("jquery", [],
    function() {
        return kb
    }))
} (window),
function(a, b) {
    function c(b, c) {
        var e, f, g, h = b.nodeName.toLowerCase();
        return "area" === h ? (e = b.parentNode, f = e.name, b.href && f && "map" === e.nodeName.toLowerCase() ? (g = a("img[usemap=#" + f + "]")[0], !!g && d(g)) : !1) : (/input|select|textarea|button|object/.test(h) ? !b.disabled: "a" === h ? b.href || c: c) && d(b)
    }
    function d(b) {
        return a.expr.filters.visible(b) && !a(b).parents().addBack().filter(function() {
            return "hidden" === a.css(this, "visibility")
        }).length
    }
    var e = 0,
    f = /^ui-id-\d+$/;
    a.ui = a.ui || {},
    a.extend(a.ui, {
        version: "1.10.3",
        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    }),
    a.fn.extend({
        focus: function(b) {
            return function(c, d) {
                return "number" == typeof c ? this.each(function() {
                    var b = this;
                    setTimeout(function() {
                        a(b).focus(),
                        d && d.call(b)
                    },
                    c)
                }) : b.apply(this, arguments)
            }
        } (a.fn.focus),
        scrollParent: function() {
            var b;
            return b = a.ui.ie && /(static|relative)/.test(this.css("position")) || /absolute/.test(this.css("position")) ? this.parents().filter(function() {
                return /(relative|absolute|fixed)/.test(a.css(this, "position")) && /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0) : this.parents().filter(function() {
                return /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0),
            /fixed/.test(this.css("position")) || !b.length ? a(document) : b
        },
        zIndex: function(c) {
            if (c !== b) return this.css("zIndex", c);
            if (this.length) for (var d, e, f = a(this[0]); f.length && f[0] !== document;) {
                if (d = f.css("position"), ("absolute" === d || "relative" === d || "fixed" === d) && (e = parseInt(f.css("zIndex"), 10), !isNaN(e) && 0 !== e)) return e;
                f = f.parent()
            }
            return 0
        },
        uniqueId: function() {
            return this.each(function() {
                this.id || (this.id = "ui-id-" + ++e)
            })
        },
        removeUniqueId: function() {
            return this.each(function() {
                f.test(this.id) && a(this).removeAttr("id")
            })
        }
    }),
    a.extend(a.expr[":"], {
        data: a.expr.createPseudo ? a.expr.createPseudo(function(b) {
            return function(c) {
                return !! a.data(c, b)
            }
        }) : function(b, c, d) {
            return !! a.data(b, d[3])
        },
        focusable: function(b) {
            return c(b, !isNaN(a.attr(b, "tabindex")))
        },
        tabbable: function(b) {
            var d = a.attr(b, "tabindex"),
            e = isNaN(d);
            return (e || d >= 0) && c(b, !e)
        }
    }),
    a("<a>").outerWidth(1).jquery || a.each(["Width", "Height"],
    function(c, d) {
        function e(b, c, d, e) {
            return a.each(f,
            function() {
                c -= parseFloat(a.css(b, "padding" + this)) || 0,
                d && (c -= parseFloat(a.css(b, "border" + this + "Width")) || 0),
                e && (c -= parseFloat(a.css(b, "margin" + this)) || 0)
            }),
            c
        }
        var f = "Width" === d ? ["Left", "Right"] : ["Top", "Bottom"],
        g = d.toLowerCase(),
        h = {
            innerWidth: a.fn.innerWidth,
            innerHeight: a.fn.innerHeight,
            outerWidth: a.fn.outerWidth,
            outerHeight: a.fn.outerHeight
        };
        a.fn["inner" + d] = function(c) {
            return c === b ? h["inner" + d].call(this) : this.each(function() {
                a(this).css(g, e(this, c) + "px")
            })
        },
        a.fn["outer" + d] = function(b, c) {
            return "number" != typeof b ? h["outer" + d].call(this, b) : this.each(function() {
                a(this).css(g, e(this, b, !0, c) + "px")
            })
        }
    }),
    a.fn.addBack || (a.fn.addBack = function(a) {
        return this.add(null == a ? this.prevObject: this.prevObject.filter(a))
    }),
    a("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (a.fn.removeData = function(b) {
        return function(c) {
            return arguments.length ? b.call(this, a.camelCase(c)) : b.call(this)
        }
    } (a.fn.removeData)),
    a.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()),
    a.support.selectstart = "onselectstart" in document.createElement("div"),
    a.fn.extend({
        disableSelection: function() {
            return this.bind((a.support.selectstart ? "selectstart": "mousedown") + ".ui-disableSelection",
            function(a) {
                a.preventDefault()
            })
        },
        enableSelection: function() {
            return this.unbind(".ui-disableSelection")
        }
    }),
    a.extend(a.ui, {
        plugin: {
            add: function(b, c, d) {
                var e, f = a.ui[b].prototype;
                for (e in d) f.plugins[e] = f.plugins[e] || [],
                f.plugins[e].push([c, d[e]])
            },
            call: function(a, b, c) {
                var d, e = a.plugins[b];
                if (e && a.element[0].parentNode && 11 !== a.element[0].parentNode.nodeType) for (d = 0; d < e.length; d++) a.options[e[d][0]] && e[d][1].apply(a.element, c)
            }
        },
        hasScroll: function(b, c) {
            if ("hidden" === a(b).css("overflow")) return ! 1;
            var d = c && "left" === c ? "scrollLeft": "scrollTop",
            e = !1;
            return b[d] > 0 ? !0 : (b[d] = 1, e = b[d] > 0, b[d] = 0, e)
        }
    })
} (jQuery),
function(a, b) {
    var c = 0,
    d = Array.prototype.slice,
    e = a.cleanData;
    a.cleanData = function(b) {
        for (var c, d = 0; null != (c = b[d]); d++) try {
            a(c).triggerHandler("remove")
        } catch(f) {}
        e(b)
    },
    a.widget = function(b, c, d) {
        var e, f, g, h, i = {},
        j = b.split(".")[0];
        b = b.split(".")[1],
        e = j + "-" + b,
        d || (d = c, c = a.Widget),
        a.expr[":"][e.toLowerCase()] = function(b) {
            return !! a.data(b, e)
        },
        a[j] = a[j] || {},
        f = a[j][b],
        g = a[j][b] = function(a, b) {
            return this._createWidget ? (arguments.length && this._createWidget(a, b), void 0) : new g(a, b)
        },
        a.extend(g, f, {
            version: d.version,
            _proto: a.extend({},
            d),
            _childConstructors: []
        }),
        h = new c,
        h.options = a.widget.extend({},
        h.options),
        a.each(d,
        function(b, d) {
            return a.isFunction(d) ? (i[b] = function() {
                var a = function() {
                    return c.prototype[b].apply(this, arguments)
                },
                e = function(a) {
                    return c.prototype[b].apply(this, a)
                };
                return function() {
                    var b, c = this._super,
                    f = this._superApply;
                    return this._super = a,
                    this._superApply = e,
                    b = d.apply(this, arguments),
                    this._super = c,
                    this._superApply = f,
                    b
                }
            } (), void 0) : (i[b] = d, void 0)
        }),
        g.prototype = a.widget.extend(h, {
            widgetEventPrefix: f ? h.widgetEventPrefix: b
        },
        i, {
            constructor: g,
            namespace: j,
            widgetName: b,
            widgetFullName: e
        }),
        f ? (a.each(f._childConstructors,
        function(b, c) {
            var d = c.prototype;
            a.widget(d.namespace + "." + d.widgetName, g, c._proto)
        }), delete f._childConstructors) : c._childConstructors.push(g),
        a.widget.bridge(b, g)
    },
    a.widget.extend = function(c) {
        for (var e, f, g = d.call(arguments, 1), h = 0, i = g.length; i > h; h++) for (e in g[h]) f = g[h][e],
        g[h].hasOwnProperty(e) && f !== b && (c[e] = a.isPlainObject(f) ? a.isPlainObject(c[e]) ? a.widget.extend({},
        c[e], f) : a.widget.extend({},
        f) : f);
        return c
    },
    a.widget.bridge = function(c, e) {
        var f = e.prototype.widgetFullName || c;
        a.fn[c] = function(g) {
            var h = "string" == typeof g,
            i = d.call(arguments, 1),
            j = this;
            return g = !h && i.length ? a.widget.extend.apply(null, [g].concat(i)) : g,
            h ? this.each(function() {
                var d, e = a.data(this, f);
                return e ? a.isFunction(e[g]) && "_" !== g.charAt(0) ? (d = e[g].apply(e, i), d !== e && d !== b ? (j = d && d.jquery ? j.pushStack(d.get()) : d, !1) : void 0) : a.error("no such method '" + g + "' for " + c + " widget instance") : a.error("cannot call methods on " + c + " prior to initialization; " + "attempted to call method '" + g + "'")
            }) : this.each(function() {
                var b = a.data(this, f);
                b ? b.option(g || {})._init() : a.data(this, f, new e(g, this))
            }),
            j
        }
    },
    a.Widget = function() {},
    a.Widget._childConstructors = [],
    a.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {
            disabled: !1,
            create: null
        },
        _createWidget: function(b, d) {
            d = a(d || this.defaultElement || this)[0],
            this.element = a(d),
            this.uuid = c++,
            this.eventNamespace = "." + this.widgetName + this.uuid,
            this.options = a.widget.extend({},
            this.options, this._getCreateOptions(), b),
            this.bindings = a(),
            this.hoverable = a(),
            this.focusable = a(),
            d !== this && (a.data(d, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function(a) {
                    a.target === d && this.destroy()
                }
            }), this.document = a(d.style ? d.ownerDocument: d.document || d), this.window = a(this.document[0].defaultView || this.document[0].parentWindow)),
            this._create(),
            this._trigger("create", null, this._getCreateEventData()),
            this._init()
        },
        _getCreateOptions: a.noop,
        _getCreateEventData: a.noop,
        _create: a.noop,
        _init: a.noop,
        destroy: function() {
            this._destroy(),
            this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(a.camelCase(this.widgetFullName)),
            this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled " + "ui-state-disabled"),
            this.bindings.unbind(this.eventNamespace),
            this.hoverable.removeClass("ui-state-hover"),
            this.focusable.removeClass("ui-state-focus")
        },
        _destroy: a.noop,
        widget: function() {
            return this.element
        },
        option: function(c, d) {
            var e, f, g, h = c;
            if (0 === arguments.length) return a.widget.extend({},
            this.options);
            if ("string" == typeof c) if (h = {},
            e = c.split("."), c = e.shift(), e.length) {
                for (f = h[c] = a.widget.extend({},
                this.options[c]), g = 0; g < e.length - 1; g++) f[e[g]] = f[e[g]] || {},
                f = f[e[g]];
                if (c = e.pop(), d === b) return f[c] === b ? null: f[c];
                f[c] = d
            } else {
                if (d === b) return this.options[c] === b ? null: this.options[c];
                h[c] = d
            }
            return this._setOptions(h),
            this
        },
        _setOptions: function(a) {
            var b;
            for (b in a) this._setOption(b, a[b]);
            return this
        },
        _setOption: function(a, b) {
            return this.options[a] = b,
            "disabled" === a && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!b).attr("aria-disabled", b), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")),
            this
        },
        enable: function() {
            return this._setOption("disabled", !1)
        },
        disable: function() {
            return this._setOption("disabled", !0)
        },
        _on: function(b, c, d) {
            var e, f = this;
            "boolean" != typeof b && (d = c, c = b, b = !1),
            d ? (c = e = a(c), this.bindings = this.bindings.add(c)) : (d = c, c = this.element, e = this.widget()),
            a.each(d,
            function(d, g) {
                function h() {
                    return b || f.options.disabled !== !0 && !a(this).hasClass("ui-state-disabled") ? ("string" == typeof g ? f[g] : g).apply(f, arguments) : void 0
                }
                "string" != typeof g && (h.guid = g.guid = g.guid || h.guid || a.guid++);
                var i = d.match(/^(\w+)\s*(.*)$/),
                j = i[1] + f.eventNamespace,
                k = i[2];
                k ? e.delegate(k, j, h) : c.bind(j, h)
            })
        },
        _off: function(a, b) {
            b = (b || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace,
            a.unbind(b).undelegate(b)
        },
        _delay: function(a, b) {
            function c() {
                return ("string" == typeof a ? d[a] : a).apply(d, arguments)
            }
            var d = this;
            return setTimeout(c, b || 0)
        },
        _hoverable: function(b) {
            this.hoverable = this.hoverable.add(b),
            this._on(b, {
                mouseenter: function(b) {
                    a(b.currentTarget).addClass("ui-state-hover")
                },
                mouseleave: function(b) {
                    a(b.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function(b) {
            this.focusable = this.focusable.add(b),
            this._on(b, {
                focusin: function(b) {
                    a(b.currentTarget).addClass("ui-state-focus")
                },
                focusout: function(b) {
                    a(b.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function(b, c, d) {
            var e, f, g = this.options[b];
            if (d = d || {},
            c = a.Event(c), c.type = (b === this.widgetEventPrefix ? b: this.widgetEventPrefix + b).toLowerCase(), c.target = this.element[0], f = c.originalEvent) for (e in f) e in c || (c[e] = f[e]);
            return this.element.trigger(c, d),
            !(a.isFunction(g) && g.apply(this.element[0], [c].concat(d)) === !1 || c.isDefaultPrevented())
        }
    },
    a.each({
        show: "fadeIn",
        hide: "fadeOut"
    },
    function(b, c) {
        a.Widget.prototype["_" + b] = function(d, e, f) {
            "string" == typeof e && (e = {
                effect: e
            });
            var g, h = e ? e === !0 || "number" == typeof e ? c: e.effect || c: b;
            e = e || {},
            "number" == typeof e && (e = {
                duration: e
            }),
            g = !a.isEmptyObject(e),
            e.complete = f,
            e.delay && d.delay(e.delay),
            g && a.effects && a.effects.effect[h] ? d[b](e) : h !== b && d[h] ? d[h](e.duration, e.easing, f) : d.queue(function(c) {
                a(this)[b](),
                f && f.call(d[0]),
                c()
            })
        }
    })
} (jQuery),
function(a) {
    var b = !1;
    a(document).mouseup(function() {
        b = !1
    }),
    a.widget("ui.mouse", {
        version: "1.10.3",
        options: {
            cancel: "input,textarea,button,select,option",
            distance: 1,
            delay: 0
        },
        _mouseInit: function() {
            var b = this;
            this.element.bind("mousedown." + this.widgetName,
            function(a) {
                return b._mouseDown(a)
            }).bind("click." + this.widgetName,
            function(c) {
                return ! 0 === a.data(c.target, b.widgetName + ".preventClickEvent") ? (a.removeData(c.target, b.widgetName + ".preventClickEvent"), c.stopImmediatePropagation(), !1) : void 0
            }),
            this.started = !1
        },
        _mouseDestroy: function() {
            this.element.unbind("." + this.widgetName),
            this._mouseMoveDelegate && a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function(c) {
            if (!b) {
                this._mouseStarted && this._mouseUp(c),
                this._mouseDownEvent = c;
                var d = this,
                e = 1 === c.which,
                f = "string" == typeof this.options.cancel && c.target.nodeName ? a(c.target).closest(this.options.cancel).length: !1;
                return e && !f && this._mouseCapture(c) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function() {
                    d.mouseDelayMet = !0
                },
                this.options.delay)), this._mouseDistanceMet(c) && this._mouseDelayMet(c) && (this._mouseStarted = this._mouseStart(c) !== !1, !this._mouseStarted) ? (c.preventDefault(), !0) : (!0 === a.data(c.target, this.widgetName + ".preventClickEvent") && a.removeData(c.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function(a) {
                    return d._mouseMove(a)
                },
                this._mouseUpDelegate = function(a) {
                    return d._mouseUp(a)
                },
                a(document).bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), c.preventDefault(), b = !0, !0)) : !0
            }
        },
        _mouseMove: function(b) {
            return a.ui.ie && (!document.documentMode || document.documentMode < 9) && !b.button ? this._mouseUp(b) : this._mouseStarted ? (this._mouseDrag(b), b.preventDefault()) : (this._mouseDistanceMet(b) && this._mouseDelayMet(b) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, b) !== !1, this._mouseStarted ? this._mouseDrag(b) : this._mouseUp(b)), !this._mouseStarted)
        },
        _mouseUp: function(b) {
            return a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate),
            this._mouseStarted && (this._mouseStarted = !1, b.target === this._mouseDownEvent.target && a.data(b.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(b)),
            !1
        },
        _mouseDistanceMet: function(a) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - a.pageX), Math.abs(this._mouseDownEvent.pageY - a.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function() {
            return this.mouseDelayMet
        },
        _mouseStart: function() {},
        _mouseDrag: function() {},
        _mouseStop: function() {},
        _mouseCapture: function() {
            return ! 0
        }
    })
} (jQuery),
function(a, b) {
    function c(a, b, c) {
        return [parseFloat(a[0]) * (n.test(a[0]) ? b / 100 : 1), parseFloat(a[1]) * (n.test(a[1]) ? c / 100 : 1)]
    }
    function d(b, c) {
        return parseInt(a.css(b, c), 10) || 0
    }
    function e(b) {
        var c = b[0];
        return 9 === c.nodeType ? {
            width: b.width(),
            height: b.height(),
            offset: {
                top: 0,
                left: 0
            }
        }: a.isWindow(c) ? {
            width: b.width(),
            height: b.height(),
            offset: {
                top: b.scrollTop(),
                left: b.scrollLeft()
            }
        }: c.preventDefault ? {
            width: 0,
            height: 0,
            offset: {
                top: c.pageY,
                left: c.pageX
            }
        }: {
            width: b.outerWidth(),
            height: b.outerHeight(),
            offset: b.offset()
        }
    }
    a.ui = a.ui || {};
    var f, g = Math.max,
    h = Math.abs,
    i = Math.round,
    j = /left|center|right/,
    k = /top|center|bottom/,
    l = /[\+\-]\d+(\.[\d]+)?%?/,
    m = /^\w+/,
    n = /%$/,
    o = a.fn.position;
    a.position = {
        scrollbarWidth: function() {
            if (f !== b) return f;
            var c, d, e = a("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
            g = e.children()[0];
            return a("body").append(e),
            c = g.offsetWidth,
            e.css("overflow", "scroll"),
            d = g.offsetWidth,
            c === d && (d = e[0].clientWidth),
            e.remove(),
            f = c - d
        },
        getScrollInfo: function(b) {
            var c = b.isWindow ? "": b.element.css("overflow-x"),
            d = b.isWindow ? "": b.element.css("overflow-y"),
            e = "scroll" === c || "auto" === c && b.width < b.element[0].scrollWidth,
            f = "scroll" === d || "auto" === d && b.height < b.element[0].scrollHeight;
            return {
                width: f ? a.position.scrollbarWidth() : 0,
                height: e ? a.position.scrollbarWidth() : 0
            }
        },
        getWithinInfo: function(b) {
            var c = a(b || window),
            d = a.isWindow(c[0]);
            return {
                element: c,
                isWindow: d,
                offset: c.offset() || {
                    left: 0,
                    top: 0
                },
                scrollLeft: c.scrollLeft(),
                scrollTop: c.scrollTop(),
                width: d ? c.width() : c.outerWidth(),
                height: d ? c.height() : c.outerHeight()
            }
        }
    },
    a.fn.position = function(b) {
        if (!b || !b.of) return o.apply(this, arguments);
        b = a.extend({},
        b);
        var f, n, p, q, r, s, t = a(b.of),
        u = a.position.getWithinInfo(b.within),
        v = a.position.getScrollInfo(u),
        w = (b.collision || "flip").split(" "),
        x = {};
        return s = e(t),
        t[0].preventDefault && (b.at = "left top"),
        n = s.width,
        p = s.height,
        q = s.offset,
        r = a.extend({},
        q),
        a.each(["my", "at"],
        function() {
            var a, c, d = (b[this] || "").split(" ");
            1 === d.length && (d = j.test(d[0]) ? d.concat(["center"]) : k.test(d[0]) ? ["center"].concat(d) : ["center", "center"]),
            d[0] = j.test(d[0]) ? d[0] : "center",
            d[1] = k.test(d[1]) ? d[1] : "center",
            a = l.exec(d[0]),
            c = l.exec(d[1]),
            x[this] = [a ? a[0] : 0, c ? c[0] : 0],
            b[this] = [m.exec(d[0])[0], m.exec(d[1])[0]]
        }),
        1 === w.length && (w[1] = w[0]),
        "right" === b.at[0] ? r.left += n: "center" === b.at[0] && (r.left += n / 2),
        "bottom" === b.at[1] ? r.top += p: "center" === b.at[1] && (r.top += p / 2),
        f = c(x.at, n, p),
        r.left += f[0],
        r.top += f[1],
        this.each(function() {
            var e, j, k = a(this),
            l = k.outerWidth(),
            m = k.outerHeight(),
            o = d(this, "marginLeft"),
            s = d(this, "marginTop"),
            y = l + o + d(this, "marginRight") + v.width,
            z = m + s + d(this, "marginBottom") + v.height,
            A = a.extend({},
            r),
            B = c(x.my, k.outerWidth(), k.outerHeight());
            "right" === b.my[0] ? A.left -= l: "center" === b.my[0] && (A.left -= l / 2),
            "bottom" === b.my[1] ? A.top -= m: "center" === b.my[1] && (A.top -= m / 2),
            A.left += B[0],
            A.top += B[1],
            a.support.offsetFractions || (A.left = i(A.left), A.top = i(A.top)),
            e = {
                marginLeft: o,
                marginTop: s
            },
            a.each(["left", "top"],
            function(c, d) {
                a.ui.position[w[c]] && a.ui.position[w[c]][d](A, {
                    targetWidth: n,
                    targetHeight: p,
                    elemWidth: l,
                    elemHeight: m,
                    collisionPosition: e,
                    collisionWidth: y,
                    collisionHeight: z,
                    offset: [f[0] + B[0], f[1] + B[1]],
                    my: b.my,
                    at: b.at,
                    within: u,
                    elem: k
                })
            }),
            b.using && (j = function(a) {
                var c = q.left - A.left,
                d = c + n - l,
                e = q.top - A.top,
                f = e + p - m,
                i = {
                    target: {
                        element: t,
                        left: q.left,
                        top: q.top,
                        width: n,
                        height: p
                    },
                    element: {
                        element: k,
                        left: A.left,
                        top: A.top,
                        width: l,
                        height: m
                    },
                    horizontal: 0 > d ? "left": c > 0 ? "right": "center",
                    vertical: 0 > f ? "top": e > 0 ? "bottom": "middle"
                };
                l > n && h(c + d) < n && (i.horizontal = "center"),
                m > p && h(e + f) < p && (i.vertical = "middle"),
                i.important = g(h(c), h(d)) > g(h(e), h(f)) ? "horizontal": "vertical",
                b.using.call(this, a, i)
            }),
            k.offset(a.extend(A, {
                using: j
            }))
        })
    },
    a.ui.position = {
        fit: {
            left: function(a, b) {
                var c, d = b.within,
                e = d.isWindow ? d.scrollLeft: d.offset.left,
                f = d.width,
                h = a.left - b.collisionPosition.marginLeft,
                i = e - h,
                j = h + b.collisionWidth - f - e;
                b.collisionWidth > f ? i > 0 && 0 >= j ? (c = a.left + i + b.collisionWidth - f - e, a.left += i - c) : a.left = j > 0 && 0 >= i ? e: i > j ? e + f - b.collisionWidth: e: i > 0 ? a.left += i: j > 0 ? a.left -= j: a.left = g(a.left - h, a.left)
            },
            top: function(a, b) {
                var c, d = b.within,
                e = d.isWindow ? d.scrollTop: d.offset.top,
                f = b.within.height,
                h = a.top - b.collisionPosition.marginTop,
                i = e - h,
                j = h + b.collisionHeight - f - e;
                b.collisionHeight > f ? i > 0 && 0 >= j ? (c = a.top + i + b.collisionHeight - f - e, a.top += i - c) : a.top = j > 0 && 0 >= i ? e: i > j ? e + f - b.collisionHeight: e: i > 0 ? a.top += i: j > 0 ? a.top -= j: a.top = g(a.top - h, a.top)
            }
        },
        flip: {
            left: function(a, b) {
                var c, d, e = b.within,
                f = e.offset.left + e.scrollLeft,
                g = e.width,
                i = e.isWindow ? e.scrollLeft: e.offset.left,
                j = a.left - b.collisionPosition.marginLeft,
                k = j - i,
                l = j + b.collisionWidth - g - i,
                m = "left" === b.my[0] ? -b.elemWidth: "right" === b.my[0] ? b.elemWidth: 0,
                n = "left" === b.at[0] ? b.targetWidth: "right" === b.at[0] ? -b.targetWidth: 0,
                o = -2 * b.offset[0];
                0 > k ? (c = a.left + m + n + o + b.collisionWidth - g - f, (0 > c || c < h(k)) && (a.left += m + n + o)) : l > 0 && (d = a.left - b.collisionPosition.marginLeft + m + n + o - i, (d > 0 || h(d) < l) && (a.left += m + n + o))
            },
            top: function(a, b) {
                var c, d, e = b.within,
                f = e.offset.top + e.scrollTop,
                g = e.height,
                i = e.isWindow ? e.scrollTop: e.offset.top,
                j = a.top - b.collisionPosition.marginTop,
                k = j - i,
                l = j + b.collisionHeight - g - i,
                m = "top" === b.my[1],
                n = m ? -b.elemHeight: "bottom" === b.my[1] ? b.elemHeight: 0,
                o = "top" === b.at[1] ? b.targetHeight: "bottom" === b.at[1] ? -b.targetHeight: 0,
                p = -2 * b.offset[1];
                0 > k ? (d = a.top + n + o + p + b.collisionHeight - g - f, a.top + n + o + p > k && (0 > d || d < h(k)) && (a.top += n + o + p)) : l > 0 && (c = a.top - b.collisionPosition.marginTop + n + o + p - i, a.top + n + o + p > l && (c > 0 || h(c) < l) && (a.top += n + o + p))
            }
        },
        flipfit: {
            left: function() {
                a.ui.position.flip.left.apply(this, arguments),
                a.ui.position.fit.left.apply(this, arguments)
            },
            top: function() {
                a.ui.position.flip.top.apply(this, arguments),
                a.ui.position.fit.top.apply(this, arguments)
            }
        }
    },
    function() {
        var b, c, d, e, f, g = document.getElementsByTagName("body")[0],
        h = document.createElement("div");
        b = document.createElement(g ? "div": "body"),
        d = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        },
        g && a.extend(d, {
            position: "absolute",
            left: "-1000px",
            top: "-1000px"
        });
        for (f in d) b.style[f] = d[f];
        b.appendChild(h),
        c = g || document.documentElement,
        c.insertBefore(b, c.firstChild),
        h.style.cssText = "position: absolute; left: 10.7432222px;",
        e = a(h).offset().left,
        a.support.offsetFractions = e > 10 && 11 > e,
        b.innerHTML = "",
        c.removeChild(b)
    } ()
} (jQuery),
function(a) {
    function b(a, b) {
        if (! (a.originalEvent.touches.length > 1)) {
            a.preventDefault();
            var c = a.originalEvent.changedTouches[0],
            d = document.createEvent("MouseEvents");
            d.initMouseEvent(b, !0, !0, window, 1, c.screenX, c.screenY, c.clientX, c.clientY, !1, !1, !1, !1, 0, null),
            a.target.dispatchEvent(d)
        }
    }
    if (a.support.touch = "ontouchend" in document, a.support.touch) {
        var c, d = a.ui.mouse.prototype,
        e = d._mouseInit;
        d._touchStart = function(a) {
            var d = this; ! c && d._mouseCapture(a.originalEvent.changedTouches[0]) && (c = !0, d._touchMoved = !1, b(a, "mouseover"), b(a, "mousemove"), b(a, "mousedown"))
        },
        d._touchMove = function(a) {
            c && (this._touchMoved = !0, b(a, "mousemove"))
        },
        d._touchEnd = function(a) {
            c && (b(a, "mouseup"), b(a, "mouseout"), this._touchMoved || b(a, "click"), c = !1)
        },
        d._mouseInit = function() {
            var b = this;
            b.element.bind("touchstart", a.proxy(b, "_touchStart")).bind("touchmove", a.proxy(b, "_touchMove")).bind("touchend", a.proxy(b, "_touchEnd")),
            e.call(b)
        }
    }
} (jQuery),
function(a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
} (function(a) {
    function b(a) {
        return a
    }
    function c(a) {
        return decodeURIComponent(a.replace(e, " "))
    }
    function d(a) {
        0 === a.indexOf('"') && (a = a.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
        try {
            return f.json ? JSON.parse(a) : a
        } catch(b) {}
    }
    var e = /\+/g,
    f = a.cookie = function(e, g, h) {
        if (void 0 !== g) {
            if (h = a.extend({},
            f.defaults, h), "number" == typeof h.expires) {
                var i = h.expires,
                j = h.expires = new Date;
                j.setDate(j.getDate() + i)
            }
            return g = f.json ? JSON.stringify(g) : String(g),
            document.cookie = [f.raw ? e: encodeURIComponent(e), "=", f.raw ? g: encodeURIComponent(g), h.expires ? "; expires=" + h.expires.toUTCString() : "", h.path ? "; path=" + h.path: "", h.domain ? "; domain=" + h.domain: "", h.secure ? "; secure": ""].join("")
        }
        for (var k = f.raw ? b: c, l = document.cookie.split("; "), m = e ? void 0 : {},
        n = 0, o = l.length; o > n; n++) {
            var p = l[n].split("="),
            q = k(p.shift()),
            r = k(p.join("="));
            if (e && e === q) {
                m = d(r);
                break
            }
            e || (m[q] = d(r))
        }
        return m
    };
    f.defaults = {},
    a.removeCookie = function(b, c) {
        return void 0 !== a.cookie(b) ? (a.cookie(b, "", a.extend({},
        c, {
            expires: -1
        })), !0) : !1
    }
}),
function(a) {
    a.widget("ui.draggable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1,
            drag: null,
            start: null,
            stop: null
        },
        _create: function() {
            "original" !== this.options.helper || /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative"),
            this.options.addClasses && this.element.addClass("ui-draggable"),
            this.options.disabled && this.element.addClass("ui-draggable-disabled"),
            this._mouseInit()
        },
        _destroy: function() {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"),
            this._mouseDestroy()
        },
        _mouseCapture: function(b) {
            var c = this.options;
            return this.helper || c.disabled || a(b.target).closest(".ui-resizable-handle").length > 0 ? !1 : (this.handle = this._getHandle(b), this.handle ? (a(c.iframeFix === !0 ? "iframe": c.iframeFix).each(function() {
                a("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>").css({
                    width: this.offsetWidth + "px",
                    height: this.offsetHeight + "px",
                    position: "absolute",
                    opacity: "0.001",
                    zIndex: 1e3
                }).css(a(this).offset()).appendTo("body")
            }), !0) : !1)
        },
        _mouseStart: function(b) {
            var c = this.options;
            return this.helper = this._createHelper(b),
            this.helper.addClass("ui-draggable-dragging"),
            this._cacheHelperProportions(),
            a.ui.ddmanager && (a.ui.ddmanager.current = this),
            this._cacheMargins(),
            this.cssPosition = this.helper.css("position"),
            this.scrollParent = this.helper.scrollParent(),
            this.offsetParent = this.helper.offsetParent(),
            this.offsetParentCssPosition = this.offsetParent.css("position"),
            this.offset = this.positionAbs = this.element.offset(),
            this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            },
            this.offset.scroll = !1,
            a.extend(this.offset, {
                click: {
                    left: b.pageX - this.offset.left,
                    top: b.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }),
            this.originalPosition = this.position = this._generatePosition(b),
            this.originalPageX = b.pageX,
            this.originalPageY = b.pageY,
            c.cursorAt && this._adjustOffsetFromHelper(c.cursorAt),
            this._setContainment(),
            this._trigger("start", b) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), a.ui.ddmanager && !c.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this._mouseDrag(b, !0), a.ui.ddmanager && a.ui.ddmanager.dragStart(this, b), !0)
        },
        _mouseDrag: function(b, c) {
            if ("fixed" === this.offsetParentCssPosition && (this.offset.parent = this._getParentOffset()), this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), !c) {
                var d = this._uiHash();
                if (this._trigger("drag", b, d) === !1) return this._mouseUp({}),
                !1;
                this.position = d.position
            }
            return this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"),
            this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"),
            a.ui.ddmanager && a.ui.ddmanager.drag(this, b),
            !1
        },
        _mouseStop: function(b) {
            var c = this,
            d = !1;
            return a.ui.ddmanager && !this.options.dropBehaviour && (d = a.ui.ddmanager.drop(this, b)),
            this.dropped && (d = this.dropped, this.dropped = !1),
            "original" !== this.options.helper || a.contains(this.element[0].ownerDocument, this.element[0]) ? ("invalid" === this.options.revert && !d || "valid" === this.options.revert && d || this.options.revert === !0 || a.isFunction(this.options.revert) && this.options.revert.call(this.element, d) ? a(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10),
            function() {
                c._trigger("stop", b) !== !1 && c._clear()
            }) : this._trigger("stop", b) !== !1 && this._clear(), !1) : !1
        },
        _mouseUp: function(b) {
            return a("div.ui-draggable-iframeFix").each(function() {
                this.parentNode.removeChild(this)
            }),
            a.ui.ddmanager && a.ui.ddmanager.dragStop(this, b),
            a.ui.mouse.prototype._mouseUp.call(this, b)
        },
        cancel: function() {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(),
            this
        },
        _getHandle: function(b) {
            return this.options.handle ? !!a(b.target).closest(this.element.find(this.options.handle)).length: !0
        },
        _createHelper: function(b) {
            var c = this.options,
            d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b])) : "clone" === c.helper ? this.element.clone().removeAttr("id") : this.element;
            return d.parents("body").length || d.appendTo("parent" === c.appendTo ? this.element[0].parentNode: c.appendTo),
            d[0] === this.element[0] || /(fixed|absolute)/.test(d.css("position")) || d.css("position", "absolute"),
            d
        },
        _adjustOffsetFromHelper: function(b) {
            "string" == typeof b && (b = b.split(" ")),
            a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }),
            "left" in b && (this.offset.click.left = b.left + this.margins.left),
            "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left),
            "top" in b && (this.offset.click.top = b.top + this.margins.top),
            "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function() {
            var b = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()),
            (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }),
            {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" === this.cssPosition) {
                var a = this.element.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var b, c, d, e = this.options;
            return e.containment ? "window" === e.containment ? (this.containment = [a(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, a(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, a(window).scrollLeft() + a(window).width() - this.helperProportions.width - this.margins.left, a(window).scrollTop() + (a(window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top], void 0) : "document" === e.containment ? (this.containment = [0, 0, a(document).width() - this.helperProportions.width - this.margins.left, (a(document).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top], void 0) : e.containment.constructor === Array ? (this.containment = e.containment, void 0) : ("parent" === e.containment && (e.containment = this.helper[0].parentNode), c = a(e.containment), d = c[0], d && (b = "hidden" !== c.css("overflow"), this.containment = [(parseInt(c.css("borderLeftWidth"), 10) || 0) + (parseInt(c.css("paddingLeft"), 10) || 0), (parseInt(c.css("borderTopWidth"), 10) || 0) + (parseInt(c.css("paddingTop"), 10) || 0), (b ? Math.max(d.scrollWidth, d.offsetWidth) : d.offsetWidth) - (parseInt(c.css("borderRightWidth"), 10) || 0) - (parseInt(c.css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (b ? Math.max(d.scrollHeight, d.offsetHeight) : d.offsetHeight) - (parseInt(c.css("borderBottomWidth"), 10) || 0) - (parseInt(c.css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relative_container = c), void 0) : (this.containment = null, void 0)
        },
        _convertPositionTo: function(b, c) {
            c || (c = this.position);
            var d = "absolute" === b ? 1 : -1,
            e = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent;
            return this.offset.scroll || (this.offset.scroll = {
                top: e.scrollTop(),
                left: e.scrollLeft()
            }),
            {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left) * d
            }
        },
        _generatePosition: function(b) {
            var c, d, e, f, g = this.options,
            h = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            i = b.pageX,
            j = b.pageY;
            return this.offset.scroll || (this.offset.scroll = {
                top: h.scrollTop(),
                left: h.scrollLeft()
            }),
            this.originalPosition && (this.containment && (this.relative_container ? (d = this.relative_container.offset(), c = [this.containment[0] + d.left, this.containment[1] + d.top, this.containment[2] + d.left, this.containment[3] + d.top]) : c = this.containment, b.pageX - this.offset.click.left < c[0] && (i = c[0] + this.offset.click.left), b.pageY - this.offset.click.top < c[1] && (j = c[1] + this.offset.click.top), b.pageX - this.offset.click.left > c[2] && (i = c[2] + this.offset.click.left), b.pageY - this.offset.click.top > c[3] && (j = c[3] + this.offset.click.top)), g.grid && (e = g.grid[1] ? this.originalPageY + Math.round((j - this.originalPageY) / g.grid[1]) * g.grid[1] : this.originalPageY, j = c ? e - this.offset.click.top >= c[1] || e - this.offset.click.top > c[3] ? e: e - this.offset.click.top >= c[1] ? e - g.grid[1] : e + g.grid[1] : e, f = g.grid[0] ? this.originalPageX + Math.round((i - this.originalPageX) / g.grid[0]) * g.grid[0] : this.originalPageX, i = c ? f - this.offset.click.left >= c[0] || f - this.offset.click.left > c[2] ? f: f - this.offset.click.left >= c[0] ? f - g.grid[0] : f + g.grid[0] : f)),
            {
                top: j - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top),
                left: i - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left)
            }
        },
        _clear: function() {
            this.helper.removeClass("ui-draggable-dragging"),
            this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(),
            this.helper = null,
            this.cancelHelperRemoval = !1
        },
        _trigger: function(b, c, d) {
            return d = d || this._uiHash(),
            a.ui.plugin.call(this, b, [c, d]),
            "drag" === b && (this.positionAbs = this._convertPositionTo("absolute")),
            a.Widget.prototype._trigger.call(this, b, c, d)
        },
        plugins: {},
        _uiHash: function() {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }),
    a.ui.plugin.add("draggable", "connectToSortable", {
        start: function(b, c) {
            var d = a(this).data("ui-draggable"),
            e = d.options,
            f = a.extend({},
            c, {
                item: d.element
            });
            d.sortables = [],
            a(e.connectToSortable).each(function() {
                var c = a.data(this, "ui-sortable");
                c && !c.options.disabled && (d.sortables.push({
                    instance: c,
                    shouldRevert: c.options.revert
                }), c.refreshPositions(), c._trigger("activate", b, f))
            })
        },
        stop: function(b, c) {
            var d = a(this).data("ui-draggable"),
            e = a.extend({},
            c, {
                item: d.element
            });
            a.each(d.sortables,
            function() {
                this.instance.isOver ? (this.instance.isOver = 0, d.cancelHelperRemoval = !0, this.instance.cancelHelperRemoval = !1, this.shouldRevert && (this.instance.options.revert = this.shouldRevert), this.instance._mouseStop(b), this.instance.options.helper = this.instance.options._helper, "original" === d.options.helper && this.instance.currentItem.css({
                    top: "auto",
                    left: "auto"
                })) : (this.instance.cancelHelperRemoval = !1, this.instance._trigger("deactivate", b, e))
            })
        },
        drag: function(b, c) {
            var d = a(this).data("ui-draggable"),
            e = this;
            a.each(d.sortables,
            function() {
                var f = !1,
                g = this;
                this.instance.positionAbs = d.positionAbs,
                this.instance.helperProportions = d.helperProportions,
                this.instance.offset.click = d.offset.click,
                this.instance._intersectsWith(this.instance.containerCache) && (f = !0, a.each(d.sortables,
                function() {
                    return this.instance.positionAbs = d.positionAbs,
                    this.instance.helperProportions = d.helperProportions,
                    this.instance.offset.click = d.offset.click,
                    this !== g && this.instance._intersectsWith(this.instance.containerCache) && a.contains(g.instance.element[0], this.instance.element[0]) && (f = !1),
                    f
                })),
                f ? (this.instance.isOver || (this.instance.isOver = 1, this.instance.currentItem = a(e).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item", !0), this.instance.options._helper = this.instance.options.helper, this.instance.options.helper = function() {
                    return c.helper[0]
                },
                b.target = this.instance.currentItem[0], this.instance._mouseCapture(b, !0), this.instance._mouseStart(b, !0, !0), this.instance.offset.click.top = d.offset.click.top, this.instance.offset.click.left = d.offset.click.left, this.instance.offset.parent.left -= d.offset.parent.left - this.instance.offset.parent.left, this.instance.offset.parent.top -= d.offset.parent.top - this.instance.offset.parent.top, d._trigger("toSortable", b), d.dropped = this.instance.element, d.currentItem = d.element, this.instance.fromOutside = d), this.instance.currentItem && this.instance._mouseDrag(b)) : this.instance.isOver && (this.instance.isOver = 0, this.instance.cancelHelperRemoval = !0, this.instance.options.revert = !1, this.instance._trigger("out", b, this.instance._uiHash(this.instance)), this.instance._mouseStop(b, !0), this.instance.options.helper = this.instance.options._helper, this.instance.currentItem.remove(), this.instance.placeholder && this.instance.placeholder.remove(), d._trigger("fromSortable", b), d.dropped = !1)
            })
        }
    }),
    a.ui.plugin.add("draggable", "cursor", {
        start: function() {
            var b = a("body"),
            c = a(this).data("ui-draggable").options;
            b.css("cursor") && (c._cursor = b.css("cursor")),
            b.css("cursor", c.cursor)
        },
        stop: function() {
            var b = a(this).data("ui-draggable").options;
            b._cursor && a("body").css("cursor", b._cursor)
        }
    }),
    a.ui.plugin.add("draggable", "opacity", {
        start: function(b, c) {
            var d = a(c.helper),
            e = a(this).data("ui-draggable").options;
            d.css("opacity") && (e._opacity = d.css("opacity")),
            d.css("opacity", e.opacity)
        },
        stop: function(b, c) {
            var d = a(this).data("ui-draggable").options;
            d._opacity && a(c.helper).css("opacity", d._opacity)
        }
    }),
    a.ui.plugin.add("draggable", "scroll", {
        start: function() {
            var b = a(this).data("ui-draggable");
            b.scrollParent[0] !== document && "HTML" !== b.scrollParent[0].tagName && (b.overflowOffset = b.scrollParent.offset())
        },
        drag: function(b) {
            var c = a(this).data("ui-draggable"),
            d = c.options,
            e = !1;
            c.scrollParent[0] !== document && "HTML" !== c.scrollParent[0].tagName ? (d.axis && "x" === d.axis || (c.overflowOffset.top + c.scrollParent[0].offsetHeight - b.pageY < d.scrollSensitivity ? c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop + d.scrollSpeed: b.pageY - c.overflowOffset.top < d.scrollSensitivity && (c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop - d.scrollSpeed)), d.axis && "y" === d.axis || (c.overflowOffset.left + c.scrollParent[0].offsetWidth - b.pageX < d.scrollSensitivity ? c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft + d.scrollSpeed: b.pageX - c.overflowOffset.left < d.scrollSensitivity && (c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft - d.scrollSpeed))) : (d.axis && "x" === d.axis || (b.pageY - a(document).scrollTop() < d.scrollSensitivity ? e = a(document).scrollTop(a(document).scrollTop() - d.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < d.scrollSensitivity && (e = a(document).scrollTop(a(document).scrollTop() + d.scrollSpeed))), d.axis && "y" === d.axis || (b.pageX - a(document).scrollLeft() < d.scrollSensitivity ? e = a(document).scrollLeft(a(document).scrollLeft() - d.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < d.scrollSensitivity && (e = a(document).scrollLeft(a(document).scrollLeft() + d.scrollSpeed)))),
            e !== !1 && a.ui.ddmanager && !d.dropBehaviour && a.ui.ddmanager.prepareOffsets(c, b)
        }
    }),
    a.ui.plugin.add("draggable", "snap", {
        start: function() {
            var b = a(this).data("ui-draggable"),
            c = b.options;
            b.snapElements = [],
            a(c.snap.constructor !== String ? c.snap.items || ":data(ui-draggable)": c.snap).each(function() {
                var c = a(this),
                d = c.offset();
                this !== b.element[0] && b.snapElements.push({
                    item: this,
                    width: c.outerWidth(),
                    height: c.outerHeight(),
                    top: d.top,
                    left: d.left
                })
            })
        },
        drag: function(b, c) {
            var d, e, f, g, h, i, j, k, l, m, n = a(this).data("ui-draggable"),
            o = n.options,
            p = o.snapTolerance,
            q = c.offset.left,
            r = q + n.helperProportions.width,
            s = c.offset.top,
            t = s + n.helperProportions.height;
            for (l = n.snapElements.length - 1; l >= 0; l--) h = n.snapElements[l].left,
            i = h + n.snapElements[l].width,
            j = n.snapElements[l].top,
            k = j + n.snapElements[l].height,
            h - p > r || q > i + p || j - p > t || s > k + p || !a.contains(n.snapElements[l].item.ownerDocument, n.snapElements[l].item) ? (n.snapElements[l].snapping && n.options.snap.release && n.options.snap.release.call(n.element, b, a.extend(n._uiHash(), {
                snapItem: n.snapElements[l].item
            })), n.snapElements[l].snapping = !1) : ("inner" !== o.snapMode && (d = Math.abs(j - t) <= p, e = Math.abs(k - s) <= p, f = Math.abs(h - r) <= p, g = Math.abs(i - q) <= p, d && (c.position.top = n._convertPositionTo("relative", {
                top: j - n.helperProportions.height,
                left: 0
            }).top - n.margins.top), e && (c.position.top = n._convertPositionTo("relative", {
                top: k,
                left: 0
            }).top - n.margins.top), f && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: h - n.helperProportions.width
            }).left - n.margins.left), g && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: i
            }).left - n.margins.left)), m = d || e || f || g, "outer" !== o.snapMode && (d = Math.abs(j - s) <= p, e = Math.abs(k - t) <= p, f = Math.abs(h - q) <= p, g = Math.abs(i - r) <= p, d && (c.position.top = n._convertPositionTo("relative", {
                top: j,
                left: 0
            }).top - n.margins.top), e && (c.position.top = n._convertPositionTo("relative", {
                top: k - n.helperProportions.height,
                left: 0
            }).top - n.margins.top), f && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: h
            }).left - n.margins.left), g && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: i - n.helperProportions.width
            }).left - n.margins.left)), !n.snapElements[l].snapping && (d || e || f || g || m) && n.options.snap.snap && n.options.snap.snap.call(n.element, b, a.extend(n._uiHash(), {
                snapItem: n.snapElements[l].item
            })), n.snapElements[l].snapping = d || e || f || g || m)
        }
    }),
    a.ui.plugin.add("draggable", "stack", {
        start: function() {
            var b, c = this.data("ui-draggable").options,
            d = a.makeArray(a(c.stack)).sort(function(b, c) {
                return (parseInt(a(b).css("zIndex"), 10) || 0) - (parseInt(a(c).css("zIndex"), 10) || 0)
            });
            d.length && (b = parseInt(a(d[0]).css("zIndex"), 10) || 0, a(d).each(function(c) {
                a(this).css("zIndex", b + c)
            }), this.css("zIndex", b + d.length))
        }
    }),
    a.ui.plugin.add("draggable", "zIndex", {
        start: function(b, c) {
            var d = a(c.helper),
            e = a(this).data("ui-draggable").options;
            d.css("zIndex") && (e._zIndex = d.css("zIndex")),
            d.css("zIndex", e.zIndex)
        },
        stop: function(b, c) {
            var d = a(this).data("ui-draggable").options;
            d._zIndex && a(c.helper).css("zIndex", d._zIndex)
        }
    })
} (jQuery),
function(a) {
    function b(a, b, c) {
        return a > b && b + c > a
    }
    a.widget("ui.droppable", {
        version: "1.10.3",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            activeClass: !1,
            addClasses: !0,
            greedy: !1,
            hoverClass: !1,
            scope: "default",
            tolerance: "intersect",
            activate: null,
            deactivate: null,
            drop: null,
            out: null,
            over: null
        },
        _create: function() {
            var b = this.options,
            c = b.accept;
            this.isover = !1,
            this.isout = !0,
            this.accept = a.isFunction(c) ? c: function(a) {
                return a.is(c)
            },
            this.proportions = {
                width: this.element[0].offsetWidth,
                height: this.element[0].offsetHeight
            },
            a.ui.ddmanager.droppables[b.scope] = a.ui.ddmanager.droppables[b.scope] || [],
            a.ui.ddmanager.droppables[b.scope].push(this),
            b.addClasses && this.element.addClass("ui-droppable")
        },
        _destroy: function() {
            for (var b = 0,
            c = a.ui.ddmanager.droppables[this.options.scope]; b < c.length; b++) c[b] === this && c.splice(b, 1);
            this.element.removeClass("ui-droppable ui-droppable-disabled")
        },
        _setOption: function(b, c) {
            "accept" === b && (this.accept = a.isFunction(c) ? c: function(a) {
                return a.is(c)
            }),
            a.Widget.prototype._setOption.apply(this, arguments)
        },
        _activate: function(b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.addClass(this.options.activeClass),
            c && this._trigger("activate", b, this.ui(c))
        },
        _deactivate: function(b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.removeClass(this.options.activeClass),
            c && this._trigger("deactivate", b, this.ui(c))
        },
        _over: function(b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] !== this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", b, this.ui(c)))
        },
        _out: function(b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] !== this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", b, this.ui(c)))
        },
        _drop: function(b, c) {
            var d = c || a.ui.ddmanager.current,
            e = !1;
            return d && (d.currentItem || d.element)[0] !== this.element[0] ? (this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function() {
                var b = a.data(this, "ui-droppable");
                return b.options.greedy && !b.options.disabled && b.options.scope === d.options.scope && b.accept.call(b.element[0], d.currentItem || d.element) && a.ui.intersect(d, a.extend(b, {
                    offset: b.element.offset()
                }), b.options.tolerance) ? (e = !0, !1) : void 0
            }), e ? !1 : this.accept.call(this.element[0], d.currentItem || d.element) ? (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", b, this.ui(d)), this.element) : !1) : !1
        },
        ui: function(a) {
            return {
                draggable: a.currentItem || a.element,
                helper: a.helper,
                position: a.position,
                offset: a.positionAbs
            }
        }
    }),
    a.ui.intersect = function(a, c, d) {
        if (!c.offset) return ! 1;
        var e, f, g = (a.positionAbs || a.position.absolute).left,
        h = g + a.helperProportions.width,
        i = (a.positionAbs || a.position.absolute).top,
        j = i + a.helperProportions.height,
        k = c.offset.left,
        l = k + c.proportions.width,
        m = c.offset.top,
        n = m + c.proportions.height;
        switch (d) {
        case "fit":
            return g >= k && l >= h && i >= m && n >= j;
        case "intersect":
            return k < g + a.helperProportions.width / 2 && h - a.helperProportions.width / 2 < l && m < i + a.helperProportions.height / 2 && j - a.helperProportions.height / 2 < n;
        case "pointer":
            return e = (a.positionAbs || a.position.absolute).left + (a.clickOffset || a.offset.click).left,
            f = (a.positionAbs || a.position.absolute).top + (a.clickOffset || a.offset.click).top,
            b(f, m, c.proportions.height) && b(e, k, c.proportions.width);
        case "touch":
            return (i >= m && n >= i || j >= m && n >= j || m > i && j > n) && (g >= k && l >= g || h >= k && l >= h || k > g && h > l);
        default:
            return ! 1
        }
    },
    a.ui.ddmanager = {
        current: null,
        droppables: {
            "default": []
        },
        prepareOffsets: function(b, c) {
            var d, e, f = a.ui.ddmanager.droppables[b.options.scope] || [],
            g = c ? c.type: null,
            h = (b.currentItem || b.element).find(":data(ui-droppable)").addBack();
            a: for (d = 0; d < f.length; d++) if (! (f[d].options.disabled || b && !f[d].accept.call(f[d].element[0], b.currentItem || b.element))) {
                for (e = 0; e < h.length; e++) if (h[e] === f[d].element[0]) {
                    f[d].proportions.height = 0;
                    continue a
                }
                f[d].visible = "none" !== f[d].element.css("display"),
                f[d].visible && ("mousedown" === g && f[d]._activate.call(f[d], c), f[d].offset = f[d].element.offset(), f[d].proportions = {
                    width: f[d].element[0].offsetWidth,
                    height: f[d].element[0].offsetHeight
                })
            }
        },
        drop: function(b, c) {
            var d = !1;
            return a.each((a.ui.ddmanager.droppables[b.options.scope] || []).slice(),
            function() {
                this.options && (!this.options.disabled && this.visible && a.ui.intersect(b, this, this.options.tolerance) && (d = this._drop.call(this, c) || d), !this.options.disabled && this.visible && this.accept.call(this.element[0], b.currentItem || b.element) && (this.isout = !0, this.isover = !1, this._deactivate.call(this, c)))
            }),
            d
        },
        dragStart: function(b, c) {
            b.element.parentsUntil("body").bind("scroll.droppable",
            function() {
                b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
            })
        },
        drag: function(b, c) {
            b.options.refreshPositions && a.ui.ddmanager.prepareOffsets(b, c),
            a.each(a.ui.ddmanager.droppables[b.options.scope] || [],
            function() {
                if (!this.options.disabled && !this.greedyChild && this.visible) {
                    var d, e, f, g = a.ui.intersect(b, this, this.options.tolerance),
                    h = !g && this.isover ? "isout": g && !this.isover ? "isover": null;
                    h && (this.options.greedy && (e = this.options.scope, f = this.element.parents(":data(ui-droppable)").filter(function() {
                        return a.data(this, "ui-droppable").options.scope === e
                    }), f.length && (d = a.data(f[0], "ui-droppable"), d.greedyChild = "isover" === h)), d && "isover" === h && (d.isover = !1, d.isout = !0, d._out.call(d, c)), this[h] = !0, this["isout" === h ? "isover": "isout"] = !1, this["isover" === h ? "_over": "_out"].call(this, c), d && "isout" === h && (d.isout = !1, d.isover = !0, d._over.call(d, c)))
                }
            })
        },
        dragStop: function(b, c) {
            b.element.parentsUntil("body").unbind("scroll.droppable"),
            b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
        }
    }
} (jQuery),
function(a) {
    function b(a) {
        return parseInt(a, 10) || 0
    }
    function c(a) {
        return ! isNaN(parseInt(a, 10))
    }
    a.widget("ui.resizable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 90,
            resize: null,
            start: null,
            stop: null
        },
        _create: function() {
            var b, c, d, e, f, g = this,
            h = this.options;
            if (this.element.addClass("ui-resizable"), a.extend(this, {
                _aspectRatio: !!h.aspectRatio,
                aspectRatio: h.aspectRatio,
                originalElement: this.element,
                _proportionallyResizeElements: [],
                _helper: h.helper || h.ghost || h.animate ? h.helper || "ui-resizable-helper": null
            }), this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i) && (this.element.wrap(a("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({
                position: this.element.css("position"),
                width: this.element.outerWidth(),
                height: this.element.outerHeight(),
                top: this.element.css("top"),
                left: this.element.css("left")
            })), this.element = this.element.parent().data("ui-resizable", this.element.data("ui-resizable")), this.elementIsWrapper = !0, this.element.css({
                marginLeft: this.originalElement.css("marginLeft"),
                marginTop: this.originalElement.css("marginTop"),
                marginRight: this.originalElement.css("marginRight"),
                marginBottom: this.originalElement.css("marginBottom")
            }), this.originalElement.css({
                marginLeft: 0,
                marginTop: 0,
                marginRight: 0,
                marginBottom: 0
            }), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                position: "static",
                zoom: 1,
                display: "block"
            })), this.originalElement.css({
                margin: this.originalElement.css("margin")
            }), this._proportionallyResize()), this.handles = h.handles || (a(".ui-resizable-handle", this.element).length ? {
                n: ".ui-resizable-n",
                e: ".ui-resizable-e",
                s: ".ui-resizable-s",
                w: ".ui-resizable-w",
                se: ".ui-resizable-se",
                sw: ".ui-resizable-sw",
                ne: ".ui-resizable-ne",
                nw: ".ui-resizable-nw"
            }: "e,s,se"), this.handles.constructor === String) for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), b = this.handles.split(","), this.handles = {},
            c = 0; c < b.length; c++) d = a.trim(b[c]),
            f = "ui-resizable-" + d,
            e = a("<div class='ui-resizable-handle " + f + "'></div>"),
            e.css({
                zIndex: h.zIndex
            }),
            "se" === d && e.addClass("ui-icon ui-icon-gripsmall-diagonal-se"),
            this.handles[d] = ".ui-resizable-" + d,
            this.element.append(e);
            this._renderAxis = function(b) {
                var c, d, e, f;
                b = b || this.element;
                for (c in this.handles) this.handles[c].constructor === String && (this.handles[c] = a(this.handles[c], this.element).show()),
                this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i) && (d = a(this.handles[c], this.element), f = /sw|ne|nw|se|n|s/.test(c) ? d.outerHeight() : d.outerWidth(), e = ["padding", /ne|nw|n/.test(c) ? "Top": /se|sw|s/.test(c) ? "Bottom": /^e$/.test(c) ? "Right": "Left"].join(""), b.css(e, f), this._proportionallyResize()),
                a(this.handles[c]).length
            },
            this._renderAxis(this.element),
            this._handles = a(".ui-resizable-handle", this.element).disableSelection(),
            this._handles.mouseover(function() {
                g.resizing || (this.className && (e = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), g.axis = e && e[1] ? e[1] : "se")
            }),
            h.autoHide && (this._handles.hide(), a(this.element).addClass("ui-resizable-autohide").mouseenter(function() {
                h.disabled || (a(this).removeClass("ui-resizable-autohide"), g._handles.show())
            }).mouseleave(function() {
                h.disabled || g.resizing || (a(this).addClass("ui-resizable-autohide"), g._handles.hide())
            })),
            this._mouseInit()
        },
        _destroy: function() {
            this._mouseDestroy();
            var b, c = function(b) {
                a(b).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()
            };
            return this.elementIsWrapper && (c(this.element), b = this.element, this.originalElement.css({
                position: b.css("position"),
                width: b.outerWidth(),
                height: b.outerHeight(),
                top: b.css("top"),
                left: b.css("left")
            }).insertAfter(b), b.remove()),
            this.originalElement.css("resize", this.originalResizeStyle),
            c(this.originalElement),
            this
        },
        _mouseCapture: function(b) {
            var c, d, e = !1;
            for (c in this.handles) d = a(this.handles[c])[0],
            (d === b.target || a.contains(d, b.target)) && (e = !0);
            return ! this.options.disabled && e
        },
        _mouseStart: function(c) {
            var d, e, f, g = this.options,
            h = this.element.position(),
            i = this.element;
            return this.resizing = !0,
            /absolute/.test(i.css("position")) ? i.css({
                position: "absolute",
                top: i.css("top"),
                left: i.css("left")
            }) : i.is(".ui-draggable") && i.css({
                position: "absolute",
                top: h.top,
                left: h.left
            }),
            this._renderProxy(),
            d = b(this.helper.css("left")),
            e = b(this.helper.css("top")),
            g.containment && (d += a(g.containment).scrollLeft() || 0, e += a(g.containment).scrollTop() || 0),
            this.offset = this.helper.offset(),
            this.position = {
                left: d,
                top: e
            },
            this.size = this._helper ? {
                width: i.outerWidth(),
                height: i.outerHeight()
            }: {
                width: i.width(),
                height: i.height()
            },
            this.originalSize = this._helper ? {
                width: i.outerWidth(),
                height: i.outerHeight()
            }: {
                width: i.width(),
                height: i.height()
            },
            this.originalPosition = {
                left: d,
                top: e
            },
            this.sizeDiff = {
                width: i.outerWidth() - i.width(),
                height: i.outerHeight() - i.height()
            },
            this.originalMousePosition = {
                left: c.pageX,
                top: c.pageY
            },
            this.aspectRatio = "number" == typeof g.aspectRatio ? g.aspectRatio: this.originalSize.width / this.originalSize.height || 1,
            f = a(".ui-resizable-" + this.axis).css("cursor"),
            a("body").css("cursor", "auto" === f ? this.axis + "-resize": f),
            i.addClass("ui-resizable-resizing"),
            this._propagate("start", c),
            !0
        },
        _mouseDrag: function(b) {
            var c, d = this.helper,
            e = {},
            f = this.originalMousePosition,
            g = this.axis,
            h = this.position.top,
            i = this.position.left,
            j = this.size.width,
            k = this.size.height,
            l = b.pageX - f.left || 0,
            m = b.pageY - f.top || 0,
            n = this._change[g];
            return n ? (c = n.apply(this, [b, l, m]), this._updateVirtualBoundaries(b.shiftKey), (this._aspectRatio || b.shiftKey) && (c = this._updateRatio(c, b)), c = this._respectSize(c, b), this._updateCache(c), this._propagate("resize", b), this.position.top !== h && (e.top = this.position.top + "px"), this.position.left !== i && (e.left = this.position.left + "px"), this.size.width !== j && (e.width = this.size.width + "px"), this.size.height !== k && (e.height = this.size.height + "px"), d.css(e), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), a.isEmptyObject(e) || this._trigger("resize", b, this.ui()), !1) : !1
        },
        _mouseStop: function(b) {
            this.resizing = !1;
            var c, d, e, f, g, h, i, j = this.options,
            k = this;
            return this._helper && (c = this._proportionallyResizeElements, d = c.length && /textarea/i.test(c[0].nodeName), e = d && a.ui.hasScroll(c[0], "left") ? 0 : k.sizeDiff.height, f = d ? 0 : k.sizeDiff.width, g = {
                width: k.helper.width() - f,
                height: k.helper.height() - e
            },
            h = parseInt(k.element.css("left"), 10) + (k.position.left - k.originalPosition.left) || null, i = parseInt(k.element.css("top"), 10) + (k.position.top - k.originalPosition.top) || null, j.animate || this.element.css(a.extend(g, {
                top: i,
                left: h
            })), k.helper.height(k.size.height), k.helper.width(k.size.width), this._helper && !j.animate && this._proportionallyResize()),
            a("body").css("cursor", "auto"),
            this.element.removeClass("ui-resizable-resizing"),
            this._propagate("stop", b),
            this._helper && this.helper.remove(),
            !1
        },
        _updateVirtualBoundaries: function(a) {
            var b, d, e, f, g, h = this.options;
            g = {
                minWidth: c(h.minWidth) ? h.minWidth: 0,
                maxWidth: c(h.maxWidth) ? h.maxWidth: 1 / 0,
                minHeight: c(h.minHeight) ? h.minHeight: 0,
                maxHeight: c(h.maxHeight) ? h.maxHeight: 1 / 0
            },
            (this._aspectRatio || a) && (b = g.minHeight * this.aspectRatio, e = g.minWidth / this.aspectRatio, d = g.maxHeight * this.aspectRatio, f = g.maxWidth / this.aspectRatio, b > g.minWidth && (g.minWidth = b), e > g.minHeight && (g.minHeight = e), d < g.maxWidth && (g.maxWidth = d), f < g.maxHeight && (g.maxHeight = f)),
            this._vBoundaries = g
        },
        _updateCache: function(a) {
            this.offset = this.helper.offset(),
            c(a.left) && (this.position.left = a.left),
            c(a.top) && (this.position.top = a.top),
            c(a.height) && (this.size.height = a.height),
            c(a.width) && (this.size.width = a.width)
        },
        _updateRatio: function(a) {
            var b = this.position,
            d = this.size,
            e = this.axis;
            return c(a.height) ? a.width = a.height * this.aspectRatio: c(a.width) && (a.height = a.width / this.aspectRatio),
            "sw" === e && (a.left = b.left + (d.width - a.width), a.top = null),
            "nw" === e && (a.top = b.top + (d.height - a.height), a.left = b.left + (d.width - a.width)),
            a
        },
        _respectSize: function(a) {
            var b = this._vBoundaries,
            d = this.axis,
            e = c(a.width) && b.maxWidth && b.maxWidth < a.width,
            f = c(a.height) && b.maxHeight && b.maxHeight < a.height,
            g = c(a.width) && b.minWidth && b.minWidth > a.width,
            h = c(a.height) && b.minHeight && b.minHeight > a.height,
            i = this.originalPosition.left + this.originalSize.width,
            j = this.position.top + this.size.height,
            k = /sw|nw|w/.test(d),
            l = /nw|ne|n/.test(d);
            return g && (a.width = b.minWidth),
            h && (a.height = b.minHeight),
            e && (a.width = b.maxWidth),
            f && (a.height = b.maxHeight),
            g && k && (a.left = i - b.minWidth),
            e && k && (a.left = i - b.maxWidth),
            h && l && (a.top = j - b.minHeight),
            f && l && (a.top = j - b.maxHeight),
            a.width || a.height || a.left || !a.top ? a.width || a.height || a.top || !a.left || (a.left = null) : a.top = null,
            a
        },
        _proportionallyResize: function() {
            if (this._proportionallyResizeElements.length) {
                var a, b, c, d, e, f = this.helper || this.element;
                for (a = 0; a < this._proportionallyResizeElements.length; a++) {
                    if (e = this._proportionallyResizeElements[a], !this.borderDif) for (this.borderDif = [], c = [e.css("borderTopWidth"), e.css("borderRightWidth"), e.css("borderBottomWidth"), e.css("borderLeftWidth")], d = [e.css("paddingTop"), e.css("paddingRight"), e.css("paddingBottom"), e.css("paddingLeft")], b = 0; b < c.length; b++) this.borderDif[b] = (parseInt(c[b], 10) || 0) + (parseInt(d[b], 10) || 0);
                    e.css({
                        height: f.height() - this.borderDif[0] - this.borderDif[2] || 0,
                        width: f.width() - this.borderDif[1] - this.borderDif[3] || 0
                    })
                }
            }
        },
        _renderProxy: function() {
            var b = this.element,
            c = this.options;
            this.elementOffset = b.offset(),
            this._helper ? (this.helper = this.helper || a("<div style='overflow:hidden;'></div>"), this.helper.addClass(this._helper).css({
                width: this.element.outerWidth() - 1,
                height: this.element.outerHeight() - 1,
                position: "absolute",
                left: this.elementOffset.left + "px",
                top: this.elementOffset.top + "px",
                zIndex: ++c.zIndex
            }), this.helper.appendTo("body").disableSelection()) : this.helper = this.element
        },
        _change: {
            e: function(a, b) {
                return {
                    width: this.originalSize.width + b
                }
            },
            w: function(a, b) {
                var c = this.originalSize,
                d = this.originalPosition;
                return {
                    left: d.left + b,
                    width: c.width - b
                }
            },
            n: function(a, b, c) {
                var d = this.originalSize,
                e = this.originalPosition;
                return {
                    top: e.top + c,
                    height: d.height - c
                }
            },
            s: function(a, b, c) {
                return {
                    height: this.originalSize.height + c
                }
            },
            se: function(b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            },
            sw: function(b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            },
            ne: function(b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            },
            nw: function(b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            }
        },
        _propagate: function(b, c) {
            a.ui.plugin.call(this, b, [c, this.ui()]),
            "resize" !== b && this._trigger(b, c, this.ui())
        },
        plugins: {},
        ui: function() {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }),
    a.ui.plugin.add("resizable", "animate", {
        stop: function(b) {
            var c = a(this).data("ui-resizable"),
            d = c.options,
            e = c._proportionallyResizeElements,
            f = e.length && /textarea/i.test(e[0].nodeName),
            g = f && a.ui.hasScroll(e[0], "left") ? 0 : c.sizeDiff.height,
            h = f ? 0 : c.sizeDiff.width,
            i = {
                width: c.size.width - h,
                height: c.size.height - g
            },
            j = parseInt(c.element.css("left"), 10) + (c.position.left - c.originalPosition.left) || null,
            k = parseInt(c.element.css("top"), 10) + (c.position.top - c.originalPosition.top) || null;
            c.element.animate(a.extend(i, k && j ? {
                top: k,
                left: j
            }: {}), {
                duration: d.animateDuration,
                easing: d.animateEasing,
                step: function() {
                    var d = {
                        width: parseInt(c.element.css("width"), 10),
                        height: parseInt(c.element.css("height"), 10),
                        top: parseInt(c.element.css("top"), 10),
                        left: parseInt(c.element.css("left"), 10)
                    };
                    e && e.length && a(e[0]).css({
                        width: d.width,
                        height: d.height
                    }),
                    c._updateCache(d),
                    c._propagate("resize", b)
                }
            })
        }
    }),
    a.ui.plugin.add("resizable", "containment", {
        start: function() {
            var c, d, e, f, g, h, i, j = a(this).data("ui-resizable"),
            k = j.options,
            l = j.element,
            m = k.containment,
            n = m instanceof a ? m.get(0) : /parent/.test(m) ? l.parent().get(0) : m;
            n && (j.containerElement = a(n), /document/.test(m) || m === document ? (j.containerOffset = {
                left: 0,
                top: 0
            },
            j.containerPosition = {
                left: 0,
                top: 0
            },
            j.parentData = {
                element: a(document),
                left: 0,
                top: 0,
                width: a(document).width(),
                height: a(document).height() || document.body.parentNode.scrollHeight
            }) : (c = a(n), d = [], a(["Top", "Right", "Left", "Bottom"]).each(function(a, e) {
                d[a] = b(c.css("padding" + e))
            }), j.containerOffset = c.offset(), j.containerPosition = c.position(), j.containerSize = {
                height: c.innerHeight() - d[3],
                width: c.innerWidth() - d[1]
            },
            e = j.containerOffset, f = j.containerSize.height, g = j.containerSize.width, h = a.ui.hasScroll(n, "left") ? n.scrollWidth: g, i = a.ui.hasScroll(n) ? n.scrollHeight: f, j.parentData = {
                element: n,
                left: e.left,
                top: e.top,
                width: h,
                height: i
            }))
        },
        resize: function(b) {
            var c, d, e, f, g = a(this).data("ui-resizable"),
            h = g.options,
            i = g.containerOffset,
            j = g.position,
            k = g._aspectRatio || b.shiftKey,
            l = {
                top: 0,
                left: 0
            },
            m = g.containerElement;
            m[0] !== document && /static/.test(m.css("position")) && (l = i),
            j.left < (g._helper ? i.left: 0) && (g.size.width = g.size.width + (g._helper ? g.position.left - i.left: g.position.left - l.left), k && (g.size.height = g.size.width / g.aspectRatio), g.position.left = h.helper ? i.left: 0),
            j.top < (g._helper ? i.top: 0) && (g.size.height = g.size.height + (g._helper ? g.position.top - i.top: g.position.top), k && (g.size.width = g.size.height * g.aspectRatio), g.position.top = g._helper ? i.top: 0),
            g.offset.left = g.parentData.left + g.position.left,
            g.offset.top = g.parentData.top + g.position.top,
            c = Math.abs((g._helper ? g.offset.left - l.left: g.offset.left - l.left) + g.sizeDiff.width),
            d = Math.abs((g._helper ? g.offset.top - l.top: g.offset.top - i.top) + g.sizeDiff.height),
            e = g.containerElement.get(0) === g.element.parent().get(0),
            f = /relative|absolute/.test(g.containerElement.css("position")),
            e && f && (c -= g.parentData.left),
            c + g.size.width >= g.parentData.width && (g.size.width = g.parentData.width - c, k && (g.size.height = g.size.width / g.aspectRatio)),
            d + g.size.height >= g.parentData.height && (g.size.height = g.parentData.height - d, k && (g.size.width = g.size.height * g.aspectRatio))
        },
        stop: function() {
            var b = a(this).data("ui-resizable"),
            c = b.options,
            d = b.containerOffset,
            e = b.containerPosition,
            f = b.containerElement,
            g = a(b.helper),
            h = g.offset(),
            i = g.outerWidth() - b.sizeDiff.width,
            j = g.outerHeight() - b.sizeDiff.height;
            b._helper && !c.animate && /relative/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            }),
            b._helper && !c.animate && /static/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            })
        }
    }),
    a.ui.plugin.add("resizable", "alsoResize", {
        start: function() {
            var b = a(this).data("ui-resizable"),
            c = b.options,
            d = function(b) {
                a(b).each(function() {
                    var b = a(this);
                    b.data("ui-resizable-alsoresize", {
                        width: parseInt(b.width(), 10),
                        height: parseInt(b.height(), 10),
                        left: parseInt(b.css("left"), 10),
                        top: parseInt(b.css("top"), 10)
                    })
                })
            };
            "object" != typeof c.alsoResize || c.alsoResize.parentNode ? d(c.alsoResize) : c.alsoResize.length ? (c.alsoResize = c.alsoResize[0], d(c.alsoResize)) : a.each(c.alsoResize,
            function(a) {
                d(a)
            })
        },
        resize: function(b, c) {
            var d = a(this).data("ui-resizable"),
            e = d.options,
            f = d.originalSize,
            g = d.originalPosition,
            h = {
                height: d.size.height - f.height || 0,
                width: d.size.width - f.width || 0,
                top: d.position.top - g.top || 0,
                left: d.position.left - g.left || 0
            },
            i = function(b, d) {
                a(b).each(function() {
                    var b = a(this),
                    e = a(this).data("ui-resizable-alsoresize"),
                    f = {},
                    g = d && d.length ? d: b.parents(c.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                    a.each(g,
                    function(a, b) {
                        var c = (e[b] || 0) + (h[b] || 0);
                        c && c >= 0 && (f[b] = c || null)
                    }),
                    b.css(f)
                })
            };
            "object" != typeof e.alsoResize || e.alsoResize.nodeType ? i(e.alsoResize) : a.each(e.alsoResize,
            function(a, b) {
                i(a, b)
            })
        },
        stop: function() {
            a(this).removeData("resizable-alsoresize")
        }
    }),
    a.ui.plugin.add("resizable", "ghost", {
        start: function() {
            var b = a(this).data("ui-resizable"),
            c = b.options,
            d = b.size;
            b.ghost = b.originalElement.clone(),
            b.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: d.height,
                width: d.width,
                margin: 0,
                left: 0,
                top: 0
            }).addClass("ui-resizable-ghost").addClass("string" == typeof c.ghost ? c.ghost: ""),
            b.ghost.appendTo(b.helper)
        },
        resize: function() {
            var b = a(this).data("ui-resizable");
            b.ghost && b.ghost.css({
                position: "relative",
                height: b.size.height,
                width: b.size.width
            })
        },
        stop: function() {
            var b = a(this).data("ui-resizable");
            b.ghost && b.helper && b.helper.get(0).removeChild(b.ghost.get(0))
        }
    }),
    a.ui.plugin.add("resizable", "grid", {
        resize: function() {
            var b = a(this).data("ui-resizable"),
            c = b.options,
            d = b.size,
            e = b.originalSize,
            f = b.originalPosition,
            g = b.axis,
            h = "number" == typeof c.grid ? [c.grid, c.grid] : c.grid,
            i = h[0] || 1,
            j = h[1] || 1,
            k = Math.round((d.width - e.width) / i) * i,
            l = Math.round((d.height - e.height) / j) * j,
            m = e.width + k,
            n = e.height + l,
            o = c.maxWidth && c.maxWidth < m,
            p = c.maxHeight && c.maxHeight < n,
            q = c.minWidth && c.minWidth > m,
            r = c.minHeight && c.minHeight > n;
            c.grid = h,
            q && (m += i),
            r && (n += j),
            o && (m -= i),
            p && (n -= j),
            /^(se|s|e)$/.test(g) ? (b.size.width = m, b.size.height = n) : /^(ne)$/.test(g) ? (b.size.width = m, b.size.height = n, b.position.top = f.top - l) : /^(sw)$/.test(g) ? (b.size.width = m, b.size.height = n, b.position.left = f.left - k) : (b.size.width = m, b.size.height = n, b.position.top = f.top - l, b.position.left = f.left - k)
        }
    })
} (jQuery),
function(a) {
    a.widget("ui.selectable", a.ui.mouse, {
        version: "1.10.3",
        options: {
            appendTo: "body",
            autoRefresh: !0,
            distance: 0,
            filter: "*",
            tolerance: "touch",
            selected: null,
            selecting: null,
            start: null,
            stop: null,
            unselected: null,
            unselecting: null
        },
        _create: function() {
            var b, c = this;
            this.element.addClass("ui-selectable"),
            this.dragged = !1,
            this.refresh = function() {
                b = a(c.options.filter, c.element[0]),
                b.addClass("ui-selectee"),
                b.each(function() {
                    var b = a(this),
                    c = b.offset();
                    a.data(this, "selectable-item", {
                        element: this,
                        $element: b,
                        left: c.left,
                        top: c.top,
                        right: c.left + b.outerWidth(),
                        bottom: c.top + b.outerHeight(),
                        startselected: !1,
                        selected: b.hasClass("ui-selected"),
                        selecting: b.hasClass("ui-selecting"),
                        unselecting: b.hasClass("ui-unselecting")
                    })
                })
            },
            this.refresh(),
            this.selectees = b.addClass("ui-selectee"),
            this._mouseInit(),
            this.helper = a("<div class='ui-selectable-helper'></div>")
        },
        _destroy: function() {
            this.selectees.removeClass("ui-selectee").removeData("selectable-item"),
            this.element.removeClass("ui-selectable ui-selectable-disabled"),
            this._mouseDestroy()
        },
        _mouseStart: function(b) {
            var c = this,
            d = this.options;
            this.opos = [b.pageX, b.pageY],
            this.options.disabled || (this.selectees = a(d.filter, this.element[0]), this._trigger("start", b), a(d.appendTo).append(this.helper), this.helper.css({
                left: b.pageX,
                top: b.pageY,
                width: 0,
                height: 0
            }), d.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function() {
                var d = a.data(this, "selectable-item");
                d.startselected = !0,
                b.metaKey || b.ctrlKey || (d.$element.removeClass("ui-selected"), d.selected = !1, d.$element.addClass("ui-unselecting"), d.unselecting = !0, c._trigger("unselecting", b, {
                    unselecting: d.element
                }))
            }), a(b.target).parents().addBack().each(function() {
                var d, e = a.data(this, "selectable-item");
                return e ? (d = !b.metaKey && !b.ctrlKey || !e.$element.hasClass("ui-selected"), e.$element.removeClass(d ? "ui-unselecting": "ui-selected").addClass(d ? "ui-selecting": "ui-unselecting"), e.unselecting = !d, e.selecting = d, e.selected = d, d ? c._trigger("selecting", b, {
                    selecting: e.element
                }) : c._trigger("unselecting", b, {
                    unselecting: e.element
                }), !1) : void 0
            }))
        },
        _mouseDrag: function(b) {
            if (this.dragged = !0, !this.options.disabled) {
                var c, d = this,
                e = this.options,
                f = this.opos[0],
                g = this.opos[1],
                h = b.pageX,
                i = b.pageY;
                return f > h && (c = h, h = f, f = c),
                g > i && (c = i, i = g, g = c),
                this.helper.css({
                    left: f,
                    top: g,
                    width: h - f,
                    height: i - g
                }),
                this.selectees.each(function() {
                    var c = a.data(this, "selectable-item"),
                    j = !1;
                    c && c.element !== d.element[0] && ("touch" === e.tolerance ? j = !(c.left > h || c.right < f || c.top > i || c.bottom < g) : "fit" === e.tolerance && (j = c.left > f && c.right < h && c.top > g && c.bottom < i), j ? (c.selected && (c.$element.removeClass("ui-selected"), c.selected = !1), c.unselecting && (c.$element.removeClass("ui-unselecting"), c.unselecting = !1), c.selecting || (c.$element.addClass("ui-selecting"), c.selecting = !0, d._trigger("selecting", b, {
                        selecting: c.element
                    }))) : (c.selecting && ((b.metaKey || b.ctrlKey) && c.startselected ? (c.$element.removeClass("ui-selecting"), c.selecting = !1, c.$element.addClass("ui-selected"), c.selected = !0) : (c.$element.removeClass("ui-selecting"), c.selecting = !1, c.startselected && (c.$element.addClass("ui-unselecting"), c.unselecting = !0), d._trigger("unselecting", b, {
                        unselecting: c.element
                    }))), c.selected && (b.metaKey || b.ctrlKey || c.startselected || (c.$element.removeClass("ui-selected"), c.selected = !1, c.$element.addClass("ui-unselecting"), c.unselecting = !0, d._trigger("unselecting", b, {
                        unselecting: c.element
                    })))))
                }),
                !1
            }
        },
        _mouseStop: function(b) {
            var c = this;
            return this.dragged = !1,
            a(".ui-unselecting", this.element[0]).each(function() {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-unselecting"),
                d.unselecting = !1,
                d.startselected = !1,
                c._trigger("unselected", b, {
                    unselected: d.element
                })
            }),
            a(".ui-selecting", this.element[0]).each(function() {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-selecting").addClass("ui-selected"),
                d.selecting = !1,
                d.selected = !0,
                d.startselected = !0,
                c._trigger("selected", b, {
                    selected: d.element
                })
            }),
            this._trigger("stop", b),
            this.helper.remove(),
            !1
        }
    })
} (jQuery),
function(a) {
    function b(a, b, c) {
        return a > b && b + c > a
    }
    function c(a) {
        return /left|right/.test(a.css("float")) || /inline|table-cell/.test(a.css("display"))
    }
    a.widget("ui.sortable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3,
            activate: null,
            beforeStop: null,
            change: null,
            deactivate: null,
            out: null,
            over: null,
            receive: null,
            remove: null,
            sort: null,
            start: null,
            stop: null,
            update: null
        },
        _create: function() {
            var a = this.options;
            this.containerCache = {},
            this.element.addClass("ui-sortable"),
            this.refresh(),
            this.floating = this.items.length ? "x" === a.axis || c(this.items[0].item) : !1,
            this.offset = this.element.offset(),
            this._mouseInit(),
            this.ready = !0
        },
        _destroy: function() {
            this.element.removeClass("ui-sortable ui-sortable-disabled"),
            this._mouseDestroy();
            for (var a = this.items.length - 1; a >= 0; a--) this.items[a].item.removeData(this.widgetName + "-item");
            return this
        },
        _setOption: function(b, c) {
            "disabled" === b ? (this.options[b] = c, this.widget().toggleClass("ui-sortable-disabled", !!c)) : a.Widget.prototype._setOption.apply(this, arguments)
        },
        _mouseCapture: function(b, c) {
            var d = null,
            e = !1,
            f = this;
            return this.reverting ? !1 : this.options.disabled || "static" === this.options.type ? !1 : (this._refreshItems(b), a(b.target).parents().each(function() {
                return a.data(this, f.widgetName + "-item") === f ? (d = a(this), !1) : void 0
            }), a.data(b.target, f.widgetName + "-item") === f && (d = a(b.target)), d ? !this.options.handle || c || (a(this.options.handle, d).find("*").addBack().each(function() {
                this === b.target && (e = !0)
            }), e) ? (this.currentItem = d, this._removeCurrentsFromItems(), !0) : !1 : !1)
        },
        _mouseStart: function(b, c, d) {
            var e, f, g = this.options;
            if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(b), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            },
            a.extend(this.offset, {
                click: {
                    left: b.pageX - this.offset.left,
                    top: b.pageY - this.offset.top
                },
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, g.cursorAt && this._adjustOffsetFromHelper(g.cursorAt), this.domPosition = {
                prev: this.currentItem.prev()[0],
                parent: this.currentItem.parent()[0]
            },
            this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), g.containment && this._setContainment(), g.cursor && "auto" !== g.cursor && (f = this.document.find("body"), this.storedCursor = f.css("cursor"), f.css("cursor", g.cursor), this.storedStylesheet = a("<style>*{ cursor: " + g.cursor + " !important; }</style>").appendTo(f)), g.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", g.opacity)), g.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", g.zIndex)), this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", b, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !d) for (e = this.containers.length - 1; e >= 0; e--) this.containers[e]._trigger("activate", b, this._uiHash(this));
            return a.ui.ddmanager && (a.ui.ddmanager.current = this),
            a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b),
            this.dragging = !0,
            this.helper.addClass("ui-sortable-helper"),
            this._mouseDrag(b),
            !0
        },
        _mouseDrag: function(b) {
            var c, d, e, f, g = this.options,
            h = !1;
            for (this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll && (this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - b.pageY < g.scrollSensitivity ? this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop + g.scrollSpeed: b.pageY - this.overflowOffset.top < g.scrollSensitivity && (this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop - g.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - b.pageX < g.scrollSensitivity ? this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft + g.scrollSpeed: b.pageX - this.overflowOffset.left < g.scrollSensitivity && (this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft - g.scrollSpeed)) : (b.pageY - a(document).scrollTop() < g.scrollSensitivity ? h = a(document).scrollTop(a(document).scrollTop() - g.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < g.scrollSensitivity && (h = a(document).scrollTop(a(document).scrollTop() + g.scrollSpeed)), b.pageX - a(document).scrollLeft() < g.scrollSensitivity ? h = a(document).scrollLeft(a(document).scrollLeft() - g.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < g.scrollSensitivity && (h = a(document).scrollLeft(a(document).scrollLeft() + g.scrollSpeed))), h !== !1 && a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b)), this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), c = this.items.length - 1; c >= 0; c--) if (d = this.items[c], e = d.item[0], f = this._intersectsWithPointer(d), f && d.instance === this.currentContainer && e !== this.currentItem[0] && this.placeholder[1 === f ? "next": "prev"]()[0] !== e && !a.contains(this.placeholder[0], e) && ("semi-dynamic" === this.options.type ? !a.contains(this.element[0], e) : !0)) {
                if (this.direction = 1 === f ? "down": "up", "pointer" !== this.options.tolerance && !this._intersectsWithSides(d)) break;
                this._rearrange(b, d),
                this._trigger("change", b, this._uiHash());
                break
            }
            return this._contactContainers(b),
            a.ui.ddmanager && a.ui.ddmanager.drag(this, b),
            this._trigger("sort", b, this._uiHash()),
            this.lastPositionAbs = this.positionAbs,
            !1
        },
        _mouseStop: function(b, c) {
            if (b) {
                if (a.ui.ddmanager && !this.options.dropBehaviour && a.ui.ddmanager.drop(this, b), this.options.revert) {
                    var d = this,
                    e = this.placeholder.offset(),
                    f = this.options.axis,
                    g = {};
                    f && "x" !== f || (g.left = e.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollLeft)),
                    f && "y" !== f || (g.top = e.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollTop)),
                    this.reverting = !0,
                    a(this.helper).animate(g, parseInt(this.options.revert, 10) || 500,
                    function() {
                        d._clear(b)
                    })
                } else this._clear(b, c);
                return ! 1
            }
        },
        cancel: function() {
            if (this.dragging) {
                this._mouseUp({
                    target: null
                }),
                "original" === this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
                for (var b = this.containers.length - 1; b >= 0; b--) this.containers[b]._trigger("deactivate", null, this._uiHash(this)),
                this.containers[b].containerCache.over && (this.containers[b]._trigger("out", null, this._uiHash(this)), this.containers[b].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), a.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? a(this.domPosition.prev).after(this.currentItem) : a(this.domPosition.parent).prepend(this.currentItem)),
            this
        },
        serialize: function(b) {
            var c = this._getItemsAsjQuery(b && b.connected),
            d = [];
            return b = b || {},
            a(c).each(function() {
                var c = (a(b.item || this).attr(b.attribute || "id") || "").match(b.expression || /(.+)[\-=_](.+)/);
                c && d.push((b.key || c[1] + "[]") + "=" + (b.key && b.expression ? c[1] : c[2]))
            }),
            !d.length && b.key && d.push(b.key + "="),
            d.join("&")
        },
        toArray: function(b) {
            var c = this._getItemsAsjQuery(b && b.connected),
            d = [];
            return b = b || {},
            c.each(function() {
                d.push(a(b.item || this).attr(b.attribute || "id") || "")
            }),
            d
        },
        _intersectsWith: function(a) {
            var b = this.positionAbs.left,
            c = b + this.helperProportions.width,
            d = this.positionAbs.top,
            e = d + this.helperProportions.height,
            f = a.left,
            g = f + a.width,
            h = a.top,
            i = h + a.height,
            j = this.offset.click.top,
            k = this.offset.click.left,
            l = "x" === this.options.axis || d + j > h && i > d + j,
            m = "y" === this.options.axis || b + k > f && g > b + k,
            n = l && m;
            return "pointer" === this.options.tolerance || this.options.forcePointerForContainers || "pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width": "height"] > a[this.floating ? "width": "height"] ? n: f < b + this.helperProportions.width / 2 && c - this.helperProportions.width / 2 < g && h < d + this.helperProportions.height / 2 && e - this.helperProportions.height / 2 < i
        },
        _intersectsWithPointer: function(a) {
            var c = "x" === this.options.axis || b(this.positionAbs.top + this.offset.click.top, a.top, a.height),
            d = "y" === this.options.axis || b(this.positionAbs.left + this.offset.click.left, a.left, a.width),
            e = c && d,
            f = this._getDragVerticalDirection(),
            g = this._getDragHorizontalDirection();
            return e ? this.floating ? g && "right" === g || "down" === f ? 2 : 1 : f && ("down" === f ? 2 : 1) : !1
        },
        _intersectsWithSides: function(a) {
            var c = b(this.positionAbs.top + this.offset.click.top, a.top + a.height / 2, a.height),
            d = b(this.positionAbs.left + this.offset.click.left, a.left + a.width / 2, a.width),
            e = this._getDragVerticalDirection(),
            f = this._getDragHorizontalDirection();
            return this.floating && f ? "right" === f && d || "left" === f && !d: e && ("down" === e && c || "up" === e && !c)
        },
        _getDragVerticalDirection: function() {
            var a = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 !== a && (a > 0 ? "down": "up")
        },
        _getDragHorizontalDirection: function() {
            var a = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 !== a && (a > 0 ? "right": "left")
        },
        refresh: function(a) {
            return this._refreshItems(a),
            this.refreshPositions(),
            this
        },
        _connectWith: function() {
            var a = this.options;
            return a.connectWith.constructor === String ? [a.connectWith] : a.connectWith
        },
        _getItemsAsjQuery: function(b) {
            var c, d, e, f, g = [],
            h = [],
            i = this._connectWith();
            if (i && b) for (c = i.length - 1; c >= 0; c--) for (e = a(i[c]), d = e.length - 1; d >= 0; d--) f = a.data(e[d], this.widgetFullName),
            f && f !== this && !f.options.disabled && h.push([a.isFunction(f.options.items) ? f.options.items.call(f.element) : a(f.options.items, f.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), f]);
            for (h.push([a.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
                options: this.options,
                item: this.currentItem
            }) : a(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]), c = h.length - 1; c >= 0; c--) h[c][0].each(function() {
                g.push(this)
            });
            return a(g)
        },
        _removeCurrentsFromItems: function() {
            var b = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = a.grep(this.items,
            function(a) {
                for (var c = 0; c < b.length; c++) if (b[c] === a.item[0]) return ! 1;
                return ! 0
            })
        },
        _refreshItems: function(b) {
            this.items = [],
            this.containers = [this];
            var c, d, e, f, g, h, i, j, k = this.items,
            l = [[a.isFunction(this.options.items) ? this.options.items.call(this.element[0], b, {
                item: this.currentItem
            }) : a(this.options.items, this.element), this]],
            m = this._connectWith();
            if (m && this.ready) for (c = m.length - 1; c >= 0; c--) for (e = a(m[c]), d = e.length - 1; d >= 0; d--) f = a.data(e[d], this.widgetFullName),
            f && f !== this && !f.options.disabled && (l.push([a.isFunction(f.options.items) ? f.options.items.call(f.element[0], b, {
                item: this.currentItem
            }) : a(f.options.items, f.element), f]), this.containers.push(f));
            for (c = l.length - 1; c >= 0; c--) for (g = l[c][1], h = l[c][0], d = 0, j = h.length; j > d; d++) i = a(h[d]),
            i.data(this.widgetName + "-item", g),
            k.push({
                item: i,
                instance: g,
                width: 0,
                height: 0,
                left: 0,
                top: 0
            })
        },
        refreshPositions: function(b) {
            this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
            var c, d, e, f;
            for (c = this.items.length - 1; c >= 0; c--) d = this.items[c],
            d.instance !== this.currentContainer && this.currentContainer && d.item[0] !== this.currentItem[0] || (e = this.options.toleranceElement ? a(this.options.toleranceElement, d.item) : d.item, b || (d.width = e.outerWidth(), d.height = e.outerHeight()), f = e.offset(), d.left = f.left, d.top = f.top);
            if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
            else for (c = this.containers.length - 1; c >= 0; c--) f = this.containers[c].element.offset(),
            this.containers[c].containerCache.left = f.left,
            this.containers[c].containerCache.top = f.top,
            this.containers[c].containerCache.width = this.containers[c].element.outerWidth(),
            this.containers[c].containerCache.height = this.containers[c].element.outerHeight();
            return this
        },
        _createPlaceholder: function(b) {
            b = b || this;
            var c, d = b.options;
            d.placeholder && d.placeholder.constructor !== String || (c = d.placeholder, d.placeholder = {
                element: function() {
                    var d = b.currentItem[0].nodeName.toLowerCase(),
                    e = a("<" + d + ">", b.document[0]).addClass(c || b.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper");
                    return "tr" === d ? b.currentItem.children().each(function() {
                        a("<td>&#160;</td>", b.document[0]).attr("colspan", a(this).attr("colspan") || 1).appendTo(e)
                    }) : "img" === d && e.attr("src", b.currentItem.attr("src")),
                    c || e.css("visibility", "hidden"),
                    e
                },
                update: function(a, e) { (!c || d.forcePlaceholderSize) && (e.height() || e.height(b.currentItem.innerHeight() - parseInt(b.currentItem.css("paddingTop") || 0, 10) - parseInt(b.currentItem.css("paddingBottom") || 0, 10)), e.width() || e.width(b.currentItem.innerWidth() - parseInt(b.currentItem.css("paddingLeft") || 0, 10) - parseInt(b.currentItem.css("paddingRight") || 0, 10)))
                }
            }),
            b.placeholder = a(d.placeholder.element.call(b.element, b.currentItem)),
            b.currentItem.after(b.placeholder),
            d.placeholder.update(b, b.placeholder)
        },
        _contactContainers: function(d) {
            var e, f, g, h, i, j, k, l, m, n, o = null,
            p = null;
            for (e = this.containers.length - 1; e >= 0; e--) if (!a.contains(this.currentItem[0], this.containers[e].element[0])) if (this._intersectsWith(this.containers[e].containerCache)) {
                if (o && a.contains(this.containers[e].element[0], o.element[0])) continue;
                o = this.containers[e],
                p = e
            } else this.containers[e].containerCache.over && (this.containers[e]._trigger("out", d, this._uiHash(this)), this.containers[e].containerCache.over = 0);
            if (o) if (1 === this.containers.length) this.containers[p].containerCache.over || (this.containers[p]._trigger("over", d, this._uiHash(this)), this.containers[p].containerCache.over = 1);
            else {
                for (g = 1e4, h = null, n = o.floating || c(this.currentItem), i = n ? "left": "top", j = n ? "width": "height", k = this.positionAbs[i] + this.offset.click[i], f = this.items.length - 1; f >= 0; f--) a.contains(this.containers[p].element[0], this.items[f].item[0]) && this.items[f].item[0] !== this.currentItem[0] && (!n || b(this.positionAbs.top + this.offset.click.top, this.items[f].top, this.items[f].height)) && (l = this.items[f].item.offset()[i], m = !1, Math.abs(l - k) > Math.abs(l + this.items[f][j] - k) && (m = !0, l += this.items[f][j]), Math.abs(l - k) < g && (g = Math.abs(l - k), h = this.items[f], this.direction = m ? "up": "down"));
                if (!h && !this.options.dropOnEmpty) return;
                if (this.currentContainer === this.containers[p]) return;
                h ? this._rearrange(d, h, null, !0) : this._rearrange(d, null, this.containers[p].element, !0),
                this._trigger("change", d, this._uiHash()),
                this.containers[p]._trigger("change", d, this._uiHash(this)),
                this.currentContainer = this.containers[p],
                this.options.placeholder.update(this.currentContainer, this.placeholder),
                this.containers[p]._trigger("over", d, this._uiHash(this)),
                this.containers[p].containerCache.over = 1
            }
        },
        _createHelper: function(b) {
            var c = this.options,
            d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b, this.currentItem])) : "clone" === c.helper ? this.currentItem.clone() : this.currentItem;
            return d.parents("body").length || a("parent" !== c.appendTo ? c.appendTo: this.currentItem[0].parentNode)[0].appendChild(d[0]),
            d[0] === this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }),
            (!d[0].style.width || c.forceHelperSize) && d.width(this.currentItem.width()),
            (!d[0].style.height || c.forceHelperSize) && d.height(this.currentItem.height()),
            d
        },
        _adjustOffsetFromHelper: function(b) {
            "string" == typeof b && (b = b.split(" ")),
            a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }),
            "left" in b && (this.offset.click.left = b.left + this.margins.left),
            "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left),
            "top" in b && (this.offset.click.top = b.top + this.margins.top),
            "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function() {
            this.offsetParent = this.helper.offsetParent();
            var b = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()),
            (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }),
            {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function() {
            if ("relative" === this.cssPosition) {
                var a = this.currentItem.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {
                top: 0,
                left: 0
            }
        },
        _cacheMargins: function() {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function() {
            this.helperProportions = {
                width: this.helper.outerWidth(),
                height: this.helper.outerHeight()
            }
        },
        _setContainment: function() {
            var b, c, d, e = this.options;
            "parent" === e.containment && (e.containment = this.helper[0].parentNode),
            ("document" === e.containment || "window" === e.containment) && (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, a("document" === e.containment ? document: window).width() - this.helperProportions.width - this.margins.left, (a("document" === e.containment ? document: window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]),
            /^(document|window|parent)$/.test(e.containment) || (b = a(e.containment)[0], c = a(e.containment).offset(), d = "hidden" !== a(b).css("overflow"), this.containment = [c.left + (parseInt(a(b).css("borderLeftWidth"), 10) || 0) + (parseInt(a(b).css("paddingLeft"), 10) || 0) - this.margins.left, c.top + (parseInt(a(b).css("borderTopWidth"), 10) || 0) + (parseInt(a(b).css("paddingTop"), 10) || 0) - this.margins.top, c.left + (d ? Math.max(b.scrollWidth, b.offsetWidth) : b.offsetWidth) - (parseInt(a(b).css("borderLeftWidth"), 10) || 0) - (parseInt(a(b).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, c.top + (d ? Math.max(b.scrollHeight, b.offsetHeight) : b.offsetHeight) - (parseInt(a(b).css("borderTopWidth"), 10) || 0) - (parseInt(a(b).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top])
        },
        _convertPositionTo: function(b, c) {
            c || (c = this.position);
            var d = "absolute" === b ? 1 : -1,
            e = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            f = /(html|body)/i.test(e[0].tagName);
            return {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : f ? 0 : e.scrollTop()) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : f ? 0 : e.scrollLeft()) * d
            }
        },
        _generatePosition: function(b) {
            var c, d, e = this.options,
            f = b.pageX,
            g = b.pageY,
            h = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent: this.offsetParent,
            i = /(html|body)/i.test(h[0].tagName);
            return "relative" !== this.cssPosition || this.scrollParent[0] !== document && this.scrollParent[0] !== this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset()),
            this.originalPosition && (this.containment && (b.pageX - this.offset.click.left < this.containment[0] && (f = this.containment[0] + this.offset.click.left), b.pageY - this.offset.click.top < this.containment[1] && (g = this.containment[1] + this.offset.click.top), b.pageX - this.offset.click.left > this.containment[2] && (f = this.containment[2] + this.offset.click.left), b.pageY - this.offset.click.top > this.containment[3] && (g = this.containment[3] + this.offset.click.top)), e.grid && (c = this.originalPageY + Math.round((g - this.originalPageY) / e.grid[1]) * e.grid[1], g = this.containment ? c - this.offset.click.top >= this.containment[1] && c - this.offset.click.top <= this.containment[3] ? c: c - this.offset.click.top >= this.containment[1] ? c - e.grid[1] : c + e.grid[1] : c, d = this.originalPageX + Math.round((f - this.originalPageX) / e.grid[0]) * e.grid[0], f = this.containment ? d - this.offset.click.left >= this.containment[0] && d - this.offset.click.left <= this.containment[2] ? d: d - this.offset.click.left >= this.containment[0] ? d - e.grid[0] : d + e.grid[0] : d)),
            {
                top: g - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : i ? 0 : h.scrollTop()),
                left: f - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : i ? 0 : h.scrollLeft())
            }
        },
        _rearrange: function(a, b, c, d) {
            c ? c[0].appendChild(this.placeholder[0]) : b.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? b.item[0] : b.item[0].nextSibling),
            this.counter = this.counter ? ++this.counter: 1;
            var e = this.counter;
            this._delay(function() {
                e === this.counter && this.refreshPositions(!d)
            })
        },
        _clear: function(a, b) {
            this.reverting = !1;
            var c, d = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] === this.currentItem[0]) {
                for (c in this._storedCSS)("auto" === this._storedCSS[c] || "static" === this._storedCSS[c]) && (this._storedCSS[c] = "");
                this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
            } else this.currentItem.show();
            for (this.fromOutside && !b && d.push(function(a) {
                this._trigger("receive", a, this._uiHash(this.fromOutside))
            }), !this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0] || b || d.push(function(a) {
                this._trigger("update", a, this._uiHash())
            }), this !== this.currentContainer && (b || (d.push(function(a) {
                this._trigger("remove", a, this._uiHash())
            }), d.push(function(a) {
                return function(b) {
                    a._trigger("receive", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer)), d.push(function(a) {
                return function(b) {
                    a._trigger("update", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer)))), c = this.containers.length - 1; c >= 0; c--) b || d.push(function(a) {
                return function(b) {
                    a._trigger("deactivate", b, this._uiHash(this))
                }
            }.call(this, this.containers[c])),
            this.containers[c].containerCache.over && (d.push(function(a) {
                return function(b) {
                    a._trigger("out", b, this._uiHash(this))
                }
            }.call(this, this.containers[c])), this.containers[c].containerCache.over = 0);
            if (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "": this._storedZIndex), this.dragging = !1, this.cancelHelperRemoval) {
                if (!b) {
                    for (this._trigger("beforeStop", a, this._uiHash()), c = 0; c < d.length; c++) d[c].call(this, a);
                    this._trigger("stop", a, this._uiHash())
                }
                return this.fromOutside = !1,
                !1
            }
            if (b || this._trigger("beforeStop", a, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null, !b) {
                for (c = 0; c < d.length; c++) d[c].call(this, a);
                this._trigger("stop", a, this._uiHash())
            }
            return this.fromOutside = !1,
            !0
        },
        _trigger: function() {
            a.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
        },
        _uiHash: function(b) {
            var c = b || this;
            return {
                helper: c.helper,
                placeholder: c.placeholder || a([]),
                position: c.position,
                originalPosition: c.originalPosition,
                offset: c.positionAbs,
                item: c.currentItem,
                sender: b ? b.element: null
            }
        }
    })
} (jQuery),
+
function(a) {
    "use strict";
    function b() {
        a(d).remove(),
        a(e).each(function(b) {
            var d = c(a(this));
            d.hasClass("open") && (d.trigger(b = a.Event("hide.bs.dropdown")), b.isDefaultPrevented() || d.removeClass("open").trigger("hidden.bs.dropdown"))
        })
    }
    function c(b) {
        var c = b.attr("data-target");
        c || (c = b.attr("href"), c = c && /#/.test(c) && c.replace(/.*(?=#[^\s]*$)/, ""));
        var d = c && a(c);
        return d && d.length ? d: b.parent()
    }
    var d = ".dropdown-backdrop",
    e = "[data-toggle=dropdown]",
    f = function(b) {
        a(b).on("click.bs.dropdown", this.toggle)
    };
    f.prototype.toggle = function(d) {
        var e = a(this);
        if (!e.is(".disabled, :disabled")) {
            var f = c(e),
            g = f.hasClass("open");
            if (b(), !g) {
                if ("ontouchstart" in document.documentElement && !f.closest(".navbar-nav").length && a('<div class="dropdown-backdrop"/>').insertAfter(a(this)).on("click", b), f.trigger(d = a.Event("show.bs.dropdown")), d.isDefaultPrevented()) return;
                f.toggleClass("open").trigger("shown.bs.dropdown"),
                e.focus()
            }
            return ! 1
        }
    },
    f.prototype.keydown = function(b) {
        if (/(38|40|27)/.test(b.keyCode)) {
            var d = a(this);
            if (b.preventDefault(), b.stopPropagation(), !d.is(".disabled, :disabled")) {
                var f = c(d),
                g = f.hasClass("open");
                if (!g || g && 27 == b.keyCode) return 27 == b.which && f.find(e).focus(),
                d.click();
                var h = a("[role=menu] li:not(.divider):visible a", f);
                if (h.length) {
                    var i = h.index(h.filter(":focus"));
                    38 == b.keyCode && i > 0 && i--,
                    40 == b.keyCode && i < h.length - 1 && i++,
                    ~i || (i = 0),
                    h.eq(i).focus()
                }
            }
        }
    };
    var g = a.fn.dropdown;
    a.fn.dropdown = function(b) {
        return this.each(function() {
            var c = a(this),
            d = c.data("dropdown");
            d || c.data("dropdown", d = new f(this)),
            "string" == typeof b && d[b].call(c)
        })
    },
    a.fn.dropdown.Constructor = f,
    a.fn.dropdown.noConflict = function() {
        return a.fn.dropdown = g,
        this
    },
    a(document).on("click.bs.dropdown.data-api", b).on("click.bs.dropdown.data-api", ".dropdown form",
    function(a) {
        a.stopPropagation()
    }).on("click.bs.dropdown.data-api", e, f.prototype.toggle).on("keydown.bs.dropdown.data-api", e + ", [role=menu]", f.prototype.keydown)
} (window.jQuery),
function(a, b, c, d) {
    "use strict";
    var e = function(b, c) {
        this.widget = "",
        this.$element = a(b),
        this.defaultTime = c.defaultTime,
        this.disableFocus = c.disableFocus,
        this.isOpen = c.isOpen,
        this.minuteStep = c.minuteStep,
        this.modalBackdrop = c.modalBackdrop,
        this.secondStep = c.secondStep,
        this.showInputs = c.showInputs,
        this.showMeridian = c.showMeridian,
        this.showSeconds = c.showSeconds,
        this.template = c.template,
        this.appendWidgetTo = c.appendWidgetTo,
        this._init()
    };
    e.prototype = {
        constructor: e,
        _init: function() {
            var b = this;
            this.$element.parent().hasClass("input-append") || this.$element.parent().hasClass("input-prepend") ? (this.$element.parent(".input-append, .input-prepend").find(".add-on").on({
                "click.timepicker": a.proxy(this.showWidget, this)
            }), this.$element.on({
                "focus.timepicker": a.proxy(this.highlightUnit, this),
                "click.timepicker": a.proxy(this.highlightUnit, this),
                "keydown.timepicker": a.proxy(this.elementKeydown, this),
                "blur.timepicker": a.proxy(this.blurElement, this)
            })) : this.template ? this.$element.on({
                "focus.timepicker": a.proxy(this.showWidget, this),
                "click.timepicker": a.proxy(this.showWidget, this),
                "blur.timepicker": a.proxy(this.blurElement, this)
            }) : this.$element.on({
                "focus.timepicker": a.proxy(this.highlightUnit, this),
                "click.timepicker": a.proxy(this.highlightUnit, this),
                "keydown.timepicker": a.proxy(this.elementKeydown, this),
                "blur.timepicker": a.proxy(this.blurElement, this)
            }),
            this.$widget = this.template !== !1 ? a(this.getTemplate()).prependTo(this.$element.parents(this.appendWidgetTo)).on("click", a.proxy(this.widgetClick, this)) : !1,
            this.showInputs && this.$widget !== !1 && this.$widget.find("input").each(function() {
                a(this).on({
                    "click.timepicker": function() {
                        a(this).select()
                    },
                    "keydown.timepicker": a.proxy(b.widgetKeydown, b)
                })
            }),
            this.setDefaultTime(this.defaultTime)
        },
        blurElement: function() {
            this.highlightedUnit = d,
            this.updateFromElementVal()
        },
        decrementHour: function() {
            if (this.showMeridian) if (1 === this.hour) this.hour = 12;
            else {
                if (12 === this.hour) return this.hour--,
                this.toggleMeridian();
                if (0 === this.hour) return this.hour = 11,
                this.toggleMeridian();
                this.hour--
            } else 0 === this.hour ? this.hour = 23 : this.hour--;
            this.update()
        },
        decrementMinute: function(a) {
            var b;
            b = a ? this.minute - a: this.minute - this.minuteStep,
            0 > b ? (this.decrementHour(), this.minute = b + 60) : this.minute = b,
            this.update()
        },
        decrementSecond: function() {
            var a = this.second - this.secondStep;
            0 > a ? (this.decrementMinute(!0), this.second = a + 60) : this.second = a,
            this.update()
        },
        elementKeydown: function(a) {
            switch (a.keyCode) {
            case 9:
                switch (this.updateFromElementVal(), this.highlightedUnit) {
                case "hour":
                    a.preventDefault(),
                    this.highlightNextUnit();
                    break;
                case "minute":
                    (this.showMeridian || this.showSeconds) && (a.preventDefault(), this.highlightNextUnit());
                    break;
                case "second":
                    this.showMeridian && (a.preventDefault(), this.highlightNextUnit())
                }
                break;
            case 27:
                this.updateFromElementVal();
                break;
            case 37:
                a.preventDefault(),
                this.highlightPrevUnit(),
                this.updateFromElementVal();
                break;
            case 38:
                switch (a.preventDefault(), this.highlightedUnit) {
                case "hour":
                    this.incrementHour(),
                    this.highlightHour();
                    break;
                case "minute":
                    this.incrementMinute(),
                    this.highlightMinute();
                    break;
                case "second":
                    this.incrementSecond(),
                    this.highlightSecond();
                    break;
                case "meridian":
                    this.toggleMeridian(),
                    this.highlightMeridian()
                }
                break;
            case 39:
                a.preventDefault(),
                this.updateFromElementVal(),
                this.highlightNextUnit();
                break;
            case 40:
                switch (a.preventDefault(), this.highlightedUnit) {
                case "hour":
                    this.decrementHour(),
                    this.highlightHour();
                    break;
                case "minute":
                    this.decrementMinute(),
                    this.highlightMinute();
                    break;
                case "second":
                    this.decrementSecond(),
                    this.highlightSecond();
                    break;
                case "meridian":
                    this.toggleMeridian(),
                    this.highlightMeridian()
                }
            }
        },
        formatTime: function(a, b, c, d) {
            return a = 10 > a ? "0" + a: a,
            b = 10 > b ? "0" + b: b,
            c = 10 > c ? "0" + c: c,
            a + ":" + b + (this.showSeconds ? ":" + c: "") + (this.showMeridian ? " " + d: "")
        },
        getCursorPosition: function() {
            var a = this.$element.get(0);
            if ("selectionStart" in a) return a.selectionStart;
            if (c.selection) {
                a.focus();
                var b = c.selection.createRange(),
                d = c.selection.createRange().text.length;
                return b.moveStart("character", -a.value.length),
                b.text.length - d
            }
        },
        getTemplate: function() {
            var a, b, c, d, e, f;
            switch (this.showInputs ? (b = '<input type="text" name="hour" class="bootstrap-timepicker-hour" maxlength="2"/>', c = '<input type="text" name="minute" class="bootstrap-timepicker-minute" maxlength="2"/>', d = '<input type="text" name="second" class="bootstrap-timepicker-second" maxlength="2"/>', e = '<input type="text" name="meridian" class="bootstrap-timepicker-meridian" maxlength="2"/>') : (b = '<span class="bootstrap-timepicker-hour"></span>', c = '<span class="bootstrap-timepicker-minute"></span>', d = '<span class="bootstrap-timepicker-second"></span>', e = '<span class="bootstrap-timepicker-meridian"></span>'), f = '<table><tr><td><a href="#" data-action="incrementHour"><i class="glyph-icon icon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyph-icon icon-chevron-up"></i></a></td>' + (this.showSeconds ? '<td class="separator">&nbsp;</td><td><a href="#" data-action="incrementSecond"><i class="glyph-icon icon-chevron-up"></i></a></td>': "") + (this.showMeridian ? '<td class="separator">&nbsp;</td><td class="meridian-column"><a href="#" data-action="toggleMeridian"><i class="glyph-icon icon-chevron-up"></i></a></td>': "") + "</tr>" + "<tr>" + "<td>" + b + "</td> " + '<td class="separator">:</td>' + "<td>" + c + "</td> " + (this.showSeconds ? '<td class="separator">:</td><td>' + d + "</td>": "") + (this.showMeridian ? '<td class="separator">&nbsp;</td><td>' + e + "</td>": "") + "</tr>" + "<tr>" + '<td><a href="#" data-action="decrementHour"><i class="glyph-icon icon-chevron-down"></i></a></td>' + '<td class="separator"></td>' + '<td><a href="#" data-action="decrementMinute"><i class="glyph-icon icon-chevron-down"></i></a></td>' + (this.showSeconds ? '<td class="separator">&nbsp;</td><td><a href="#" data-action="decrementSecond"><i class="glyph-icon icon-chevron-down"></i></a></td>': "") + (this.showMeridian ? '<td class="separator">&nbsp;</td><td><a href="#" data-action="toggleMeridian"><i class="glyph-icon icon-chevron-down"></i></a></td>': "") + "</tr>" + "</table>", this.template) {
            case "modal":
                a = '<div class="bootstrap-timepicker-widget modal hide fade in" data-backdrop="' + (this.modalBackdrop ? "true": "false") + '">' + '<div class="modal-header">' + '<a href="#" class="close" data-dismiss="modal">Ã—</a>' + "<h3>Pick a Time</h3>" + "</div>" + '<div class="modal-content">' + f + "</div>" + '<div class="modal-footer">' + '<a href="#" class="btn btn-primary" data-dismiss="modal">OK</a>' + "</div>" + "</div>";
                break;
            case "dropdown":
                a = '<div class="bootstrap-timepicker-widget dropdown-menu">' + f + "</div>"
            }
            return a
        },
        getTime: function() {
            return this.formatTime(this.hour, this.minute, this.second, this.meridian)
        },
        hideWidget: function() {
            this.isOpen !== !1 && (this.showInputs && this.updateFromWidgetInputs(), this.$element.trigger({
                type: "hide.timepicker",
                time: {
                    value: this.getTime(),
                    hours: this.hour,
                    minutes: this.minute,
                    seconds: this.second,
                    meridian: this.meridian
                }
            }), "modal" === this.template && this.$widget.modal ? this.$widget.modal("hide") : this.$widget.removeClass("open"), a(c).off("mousedown.timepicker"), this.isOpen = !1)
        },
        highlightUnit: function() {
            this.position = this.getCursorPosition(),
            this.position >= 0 && this.position <= 2 ? this.highlightHour() : this.position >= 3 && this.position <= 5 ? this.highlightMinute() : this.position >= 6 && this.position <= 8 ? this.showSeconds ? this.highlightSecond() : this.highlightMeridian() : this.position >= 9 && this.position <= 11 && this.highlightMeridian()
        },
        highlightNextUnit: function() {
            switch (this.highlightedUnit) {
            case "hour":
                this.highlightMinute();
                break;
            case "minute":
                this.showSeconds ? this.highlightSecond() : this.showMeridian ? this.highlightMeridian() : this.highlightHour();
                break;
            case "second":
                this.showMeridian ? this.highlightMeridian() : this.highlightHour();
                break;
            case "meridian":
                this.highlightHour()
            }
        },
        highlightPrevUnit: function() {
            switch (this.highlightedUnit) {
            case "hour":
                this.highlightMeridian();
                break;
            case "minute":
                this.highlightHour();
                break;
            case "second":
                this.highlightMinute();
                break;
            case "meridian":
                this.showSeconds ? this.highlightSecond() : this.highlightMinute()
            }
        },
        highlightHour: function() {
            var a = this.$element.get(0);
            this.highlightedUnit = "hour",
            a.setSelectionRange && setTimeout(function() {
                a.setSelectionRange(0, 2)
            },
            0)
        },
        highlightMinute: function() {
            var a = this.$element.get(0);
            this.highlightedUnit = "minute",
            a.setSelectionRange && setTimeout(function() {
                a.setSelectionRange(3, 5)
            },
            0)
        },
        highlightSecond: function() {
            var a = this.$element.get(0);
            this.highlightedUnit = "second",
            a.setSelectionRange && setTimeout(function() {
                a.setSelectionRange(6, 8)
            },
            0)
        },
        highlightMeridian: function() {
            var a = this.$element.get(0);
            this.highlightedUnit = "meridian",
            a.setSelectionRange && (this.showSeconds ? setTimeout(function() {
                a.setSelectionRange(9, 11)
            },
            0) : setTimeout(function() {
                a.setSelectionRange(6, 8)
            },
            0))
        },
        incrementHour: function() {
            if (this.showMeridian) {
                if (11 === this.hour) return this.hour++,
                this.toggleMeridian();
                12 === this.hour && (this.hour = 0)
            }
            return 23 === this.hour ? (this.hour = 0, void 0) : (this.hour++, this.update(), void 0)
        },
        incrementMinute: function(a) {
            var b;
            b = a ? this.minute + a: this.minute + this.minuteStep - this.minute % this.minuteStep,
            b > 59 ? (this.incrementHour(), this.minute = b - 60) : this.minute = b,
            this.update()
        },
        incrementSecond: function() {
            var a = this.second + this.secondStep - this.second % this.secondStep;
            a > 59 ? (this.incrementMinute(!0), this.second = a - 60) : this.second = a,
            this.update()
        },
        remove: function() {
            a("document").off(".timepicker"),
            this.$widget && this.$widget.remove(),
            delete this.$element.data().timepicker
        },
        setDefaultTime: function(a) {
            if (this.$element.val()) this.updateFromElementVal();
            else if ("current" === a) {
                var b = new Date,
                c = b.getHours(),
                d = Math.floor(b.getMinutes() / this.minuteStep) * this.minuteStep,
                e = Math.floor(b.getSeconds() / this.secondStep) * this.secondStep,
                f = "AM";
                this.showMeridian && (0 === c ? c = 12 : c >= 12 ? (c > 12 && (c -= 12), f = "PM") : f = "AM"),
                this.hour = c,
                this.minute = d,
                this.second = e,
                this.meridian = f,
                this.update()
            } else a === !1 ? (this.hour = 0, this.minute = 0, this.second = 0, this.meridian = "AM") : this.setTime(a)
        },
        setTime: function(a) {
            var b, c;
            this.showMeridian ? (b = a.split(" "), c = b[0].split(":"), this.meridian = b[1]) : c = a.split(":"),
            this.hour = parseInt(c[0], 10),
            this.minute = parseInt(c[1], 10),
            this.second = parseInt(c[2], 10),
            isNaN(this.hour) && (this.hour = 0),
            isNaN(this.minute) && (this.minute = 0),
            this.showMeridian ? (this.hour > 12 ? this.hour = 12 : this.hour < 1 && (this.hour = 12), "am" === this.meridian || "a" === this.meridian ? this.meridian = "AM": ("pm" === this.meridian || "p" === this.meridian) && (this.meridian = "PM"), "AM" !== this.meridian && "PM" !== this.meridian && (this.meridian = "AM")) : this.hour >= 24 ? this.hour = 23 : this.hour < 0 && (this.hour = 0),
            this.minute < 0 ? this.minute = 0 : this.minute >= 60 && (this.minute = 59),
            this.showSeconds && (isNaN(this.second) ? this.second = 0 : this.second < 0 ? this.second = 0 : this.second >= 60 && (this.second = 59)),
            this.update()
        },
        showWidget: function() {
            if (!this.isOpen && !this.$element.is(":disabled")) {
                var b = this;
                a(c).on("mousedown.timepicker",
                function(c) {
                    0 === a(c.target).closest(".bootstrap-timepicker-widget").length && b.hideWidget()
                }),
                this.$element.trigger({
                    type: "show.timepicker",
                    time: {
                        value: this.getTime(),
                        hours: this.hour,
                        minutes: this.minute,
                        seconds: this.second,
                        meridian: this.meridian
                    }
                }),
                this.disableFocus && this.$element.blur(),
                this.updateFromElementVal(),
                "modal" === this.template && this.$widget.modal ? this.$widget.modal("show").on("hidden", a.proxy(this.hideWidget, this)) : this.isOpen === !1 && this.$widget.addClass("open"),
                this.isOpen = !0
            }
        },
        toggleMeridian: function() {
            this.meridian = "AM" === this.meridian ? "PM": "AM",
            this.update()
        },
        update: function() {
            this.$element.trigger({
                type: "changeTime.timepicker",
                time: {
                    value: this.getTime(),
                    hours: this.hour,
                    minutes: this.minute,
                    seconds: this.second,
                    meridian: this.meridian
                }
            }),
            this.updateElement(),
            this.updateWidget()
        },
        updateElement: function() {
            this.$element.val(this.getTime()).change()
        },
        updateFromElementVal: function() {
            var a = this.$element.val();
            a && this.setTime(a)
        },
        updateWidget: function() {
            if (this.$widget !== !1) {
                var a = this.hour < 10 ? "0" + this.hour: this.hour,
                b = this.minute < 10 ? "0" + this.minute: this.minute,
                c = this.second < 10 ? "0" + this.second: this.second;
                this.showInputs ? (this.$widget.find("input.bootstrap-timepicker-hour").val(a), this.$widget.find("input.bootstrap-timepicker-minute").val(b), this.showSeconds && this.$widget.find("input.bootstrap-timepicker-second").val(c), this.showMeridian && this.$widget.find("input.bootstrap-timepicker-meridian").val(this.meridian)) : (this.$widget.find("span.bootstrap-timepicker-hour").text(a), this.$widget.find("span.bootstrap-timepicker-minute").text(b), this.showSeconds && this.$widget.find("span.bootstrap-timepicker-second").text(c), this.showMeridian && this.$widget.find("span.bootstrap-timepicker-meridian").text(this.meridian))
            }
        },
        updateFromWidgetInputs: function() {
            if (this.$widget !== !1) {
                var b = a("input.bootstrap-timepicker-hour", this.$widget).val() + ":" + a("input.bootstrap-timepicker-minute", this.$widget).val() + (this.showSeconds ? ":" + a("input.bootstrap-timepicker-second", this.$widget).val() : "") + (this.showMeridian ? " " + a("input.bootstrap-timepicker-meridian", this.$widget).val() : "");
                this.setTime(b)
            }
        },
        widgetClick: function(b) {
            b.stopPropagation(),
            b.preventDefault();
            var c = a(b.target).closest("a").data("action");
            c && this[c]()
        },
        widgetKeydown: function(b) {
            var c = a(b.target).closest("input"),
            d = c.attr("name");
            switch (b.keyCode) {
            case 9:
                if (this.showMeridian) {
                    if ("meridian" === d) return this.hideWidget()
                } else if (this.showSeconds) {
                    if ("second" === d) return this.hideWidget()
                } else if ("minute" === d) return this.hideWidget();
                this.updateFromWidgetInputs();
                break;
            case 27:
                this.hideWidget();
                break;
            case 38:
                switch (b.preventDefault(), d) {
                case "hour":
                    this.incrementHour();
                    break;
                case "minute":
                    this.incrementMinute();
                    break;
                case "second":
                    this.incrementSecond();
                    break;
                case "meridian":
                    this.toggleMeridian()
                }
                break;
            case 40:
                switch (b.preventDefault(), d) {
                case "hour":
                    this.decrementHour();
                    break;
                case "minute":
                    this.decrementMinute();
                    break;
                case "second":
                    this.decrementSecond();
                    break;
                case "meridian":
                    this.toggleMeridian()
                }
            }
        }
    },
    a.fn.timepicker = function(b) {
        var c = Array.apply(null, arguments);
        return c.shift(),
        this.each(function() {
            var d = a(this),
            f = d.data("timepicker"),
            g = "object" == typeof b && b;
            f || d.data("timepicker", f = new e(this, a.extend({},
            a.fn.timepicker.defaults, g, a(this).data()))),
            "string" == typeof b && f[b].apply(f, c)
        })
    },
    a.fn.timepicker.defaults = {
        defaultTime: "current",
        disableFocus: !1,
        isOpen: !1,
        minuteStep: 15,
        modalBackdrop: !1,
        secondStep: 15,
        showSeconds: !1,
        showInputs: !0,
        showMeridian: !0,
        template: "dropdown",
        appendWidgetTo: ".bootstrap-timepicker"
    },
    a.fn.timepicker.Constructor = e
} (jQuery, window, document),
+
function(a) {
    "use strict";
    var b = function(a, b) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null,
        this.init("tooltip", a, b)
    };
    b.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1
    },
    b.prototype.init = function(b, c, d) {
        this.enabled = !0,
        this.type = b,
        this.$element = a(c),
        this.options = this.getOptions(d);
        for (var e = this.options.trigger.split(" "), f = e.length; f--;) {
            var g = e[f];
            if ("click" == g) this.$element.on("click." + this.type, this.options.selector, a.proxy(this.toggle, this));
            else if ("manual" != g) {
                var h = "hover" == g ? "mouseenter": "focus",
                i = "hover" == g ? "mouseleave": "blur";
                this.$element.on(h + "." + this.type, this.options.selector, a.proxy(this.enter, this)),
                this.$element.on(i + "." + this.type, this.options.selector, a.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = a.extend({},
        this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    },
    b.prototype.getDefaults = function() {
        return b.DEFAULTS
    },
    b.prototype.getOptions = function(b) {
        return b = a.extend({},
        this.getDefaults(), this.$element.data(), b),
        b.delay && "number" == typeof b.delay && (b.delay = {
            show: b.delay,
            hide: b.delay
        }),
        b
    },
    b.prototype.getDelegateOptions = function() {
        var b = {},
        c = this.getDefaults();
        return this._options && a.each(this._options,
        function(a, d) {
            c[a] != d && (b[a] = d)
        }),
        b
    },
    b.prototype.enter = function(b) {
        var c = b instanceof this.constructor ? b: a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type);
        return clearTimeout(c.timeout),
        c.hoverState = "in",
        c.options.delay && c.options.delay.show ? (c.timeout = setTimeout(function() {
            "in" == c.hoverState && c.show()
        },
        c.options.delay.show), void 0) : c.show()
    },
    b.prototype.leave = function(b) {
        var c = b instanceof this.constructor ? b: a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type);
        return clearTimeout(c.timeout),
        c.hoverState = "out",
        c.options.delay && c.options.delay.hide ? (c.timeout = setTimeout(function() {
            "out" == c.hoverState && c.hide()
        },
        c.options.delay.hide), void 0) : c.hide()
    },
    b.prototype.show = function() {
        var b = a.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            if (this.$element.trigger(b), b.isDefaultPrevented()) return;
            var c = this.tip();
            this.setContent(),
            this.options.animation && c.addClass("fade");
            var d = "function" == typeof this.options.placement ? this.options.placement.call(this, c[0], this.$element[0]) : this.options.placement,
            e = /\s?auto?\s?/i,
            f = e.test(d);
            f && (d = d.replace(e, "") || "top"),
            c.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(d),
            this.options.container ? c.appendTo(this.options.container) : c.insertAfter(this.$element);
            var g = this.getPosition(),
            h = c[0].offsetWidth,
            i = c[0].offsetHeight;
            if (f) {
                var j = this.$element.parent(),
                k = d,
                l = document.documentElement.scrollTop || document.body.scrollTop,
                m = "body" == this.options.container ? window.innerWidth: j.outerWidth(),
                n = "body" == this.options.container ? window.innerHeight: j.outerHeight(),
                o = "body" == this.options.container ? 0 : j.offset().left;
                d = "bottom" == d && g.top + g.height + i - l > n ? "top": "top" == d && g.top - l - i < 0 ? "bottom": "right" == d && g.right + h > m ? "left": "left" == d && g.left - h < o ? "right": d,
                c.removeClass(k).addClass(d)
            }
            var p = this.getCalculatedOffset(d, g, h, i);
            this.applyPlacement(p, d),
            this.$element.trigger("shown.bs." + this.type)
        }
    },
    b.prototype.applyPlacement = function(a, b) {
        var c, d = this.tip(),
        e = d[0].offsetWidth,
        f = d[0].offsetHeight,
        g = parseInt(d.css("margin-top"), 10),
        h = parseInt(d.css("margin-left"), 10);
        isNaN(g) && (g = 0),
        isNaN(h) && (h = 0),
        a.top = a.top + g,
        a.left = a.left + h,
        d.offset(a).addClass("in");
        var i = d[0].offsetWidth,
        j = d[0].offsetHeight;
        if ("top" == b && j != f && (c = !0, a.top = a.top + f - j), /bottom|top/.test(b)) {
            var k = 0;
            a.left < 0 && (k = -2 * a.left, a.left = 0, d.offset(a), i = d[0].offsetWidth, j = d[0].offsetHeight),
            this.replaceArrow(k - e + i, i, "left")
        } else this.replaceArrow(j - f, j, "top");
        c && d.offset(a)
    },
    b.prototype.replaceArrow = function(a, b, c) {
        this.arrow().css(c, a ? 50 * (1 - a / b) + "%": "")
    },
    b.prototype.setContent = function() {
        var a = this.tip(),
        b = this.getTitle();
        a.find(".tooltip-inner")[this.options.html ? "html": "text"](b),
        a.removeClass("fade in top bottom left right")
    },
    b.prototype.hide = function() {
        function b() {
            "in" != c.hoverState && d.detach()
        }
        var c = this,
        d = this.tip(),
        e = a.Event("hide.bs." + this.type);
        return this.$element.trigger(e),
        e.isDefaultPrevented() ? void 0 : (d.removeClass("in"), a.support.transition && this.$tip.hasClass("fade") ? d.one(a.support.transition.end, b).emulateTransitionEnd(150) : b(), this.$element.trigger("hidden.bs." + this.type), this)
    },
    b.prototype.fixTitle = function() {
        var a = this.$element; (a.attr("title") || "string" != typeof a.attr("data-original-title")) && a.attr("data-original-title", a.attr("title") || "").attr("title", "")
    },
    b.prototype.hasContent = function() {
        return this.getTitle()
    },
    b.prototype.getPosition = function() {
        var b = this.$element[0];
        return a.extend({},
        "function" == typeof b.getBoundingClientRect ? b.getBoundingClientRect() : {
            width: b.offsetWidth,
            height: b.offsetHeight
        },
        this.$element.offset())
    },
    b.prototype.getCalculatedOffset = function(a, b, c, d) {
        return "bottom" == a ? {
            top: b.top + b.height,
            left: b.left + b.width / 2 - c / 2
        }: "top" == a ? {
            top: b.top - d,
            left: b.left + b.width / 2 - c / 2
        }: "left" == a ? {
            top: b.top + b.height / 2 - d / 2,
            left: b.left - c
        }: {
            top: b.top + b.height / 2 - d / 2,
            left: b.left + b.width
        }
    },
    b.prototype.getTitle = function() {
        var a, b = this.$element,
        c = this.options;
        return a = b.attr("data-original-title") || ("function" == typeof c.title ? c.title.call(b[0]) : c.title)
    },
    b.prototype.tip = function() {
        return this.$tip = this.$tip || a(this.options.template)
    },
    b.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    },
    b.prototype.validate = function() {
        this.$element[0].parentNode || (this.hide(), this.$element = null, this.options = null)
    },
    b.prototype.enable = function() {
        this.enabled = !0
    },
    b.prototype.disable = function() {
        this.enabled = !1
    },
    b.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    },
    b.prototype.toggle = function(b) {
        var c = b ? a(b.currentTarget)[this.type](this.getDelegateOptions()).data("bs." + this.type) : this;
        c.tip().hasClass("in") ? c.leave(c) : c.enter(c)
    },
    b.prototype.destroy = function() {
        this.hide().$element.off("." + this.type).removeData("bs." + this.type)
    };
    var c = a.fn.tooltip;
    a.fn.tooltip = function(c) {
        return this.each(function() {
            var d = a(this),
            e = d.data("bs.tooltip"),
            f = "object" == typeof c && c;
            e || d.data("bs.tooltip", e = new b(this, f)),
            "string" == typeof c && e[c]()
        })
    },
    a.fn.tooltip.Constructor = b,
    a.fn.tooltip.noConflict = function() {
        return a.fn.tooltip = c,
        this
    }
} (window.jQuery),
+
function(a) {
    "use strict";
    var b = function(a, b) {
        this.init("popover", a, b)
    };
    if (!a.fn.tooltip) throw new Error("Popover requires tooltip.js");
    b.DEFAULTS = a.extend({},
    a.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }),
    b.prototype = a.extend({},
    a.fn.tooltip.Constructor.prototype),
    b.prototype.constructor = b,
    b.prototype.getDefaults = function() {
        return b.DEFAULTS
    },
    b.prototype.setContent = function() {
        var a = this.tip(),
        b = this.getTitle(),
        c = this.getContent();
        a.find(".popover-title")[this.options.html ? "html": "text"](b),
        a.find(".popover-content")[this.options.html ? "html": "text"](c),
        a.removeClass("fade top bottom left right in"),
        a.find(".popover-title").html() || a.find(".popover-title").hide()
    },
    b.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    },
    b.prototype.getContent = function() {
        var a = this.$element,
        b = this.options;
        return a.attr("data-content") || ("function" == typeof b.content ? b.content.call(a[0]) : b.content)
    },
    b.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    },
    b.prototype.tip = function() {
        return this.$tip || (this.$tip = a(this.options.template)),
        this.$tip
    };
    var c = a.fn.popover;
    a.fn.popover = function(c) {
        return this.each(function() {
            var d = a(this),
            e = d.data("bs.popover"),
            f = "object" == typeof c && c;
            e || d.data("bs.popover", e = new b(this, f)),
            "string" == typeof c && e[c]()
        })
    },
    a.fn.popover.Constructor = b,
    a.fn.popover.noConflict = function() {
        return a.fn.popover = c,
        this
    }
} (window.jQuery),
function(a) {
    return a.easyPieChart = function(b, c) {
        var d, e, f, g, h, i, j, k, l = this;
        return this.el = b,
        this.$el = a(b),
        this.$el.data("easyPieChart", this),
        this.init = function() {
            var b, d;
            return l.options = a.extend({},
            a.easyPieChart.defaultOptions, c),
            b = parseInt(l.$el.data("percent"), 10),
            l.percentage = 0,
            l.canvas = a("<canvas width='" + l.options.size + "' height='" + l.options.size + "'></canvas>").get(0),
            l.$el.append(l.canvas),
            "undefined" != typeof G_vmlCanvasManager && null !== G_vmlCanvasManager && G_vmlCanvasManager.initElement(l.canvas),
            l.ctx = l.canvas.getContext("2d"),
            window.devicePixelRatio > 1 && (d = window.devicePixelRatio, a(l.canvas).css({
                width: l.options.size,
                height: l.options.size
            }), l.canvas.width *= d, l.canvas.height *= d, l.ctx.scale(d, d)),
            l.ctx.translate(l.options.size / 2, l.options.size / 2),
            l.ctx.rotate(l.options.rotate * Math.PI / 180),
            l.$el.addClass("easyPieChart"),
            l.$el.css({
                width: l.options.size,
                height: l.options.size,
                lineHeight: "" + l.options.size + "px"
            }),
            l.update(b),
            l
        },
        this.update = function(a) {
            return a = parseFloat(a) || 0,
            l.options.animate === !1 ? f(a) : e(l.percentage, a),
            l
        },
        j = function() {
            var a, b, c;
            for (l.ctx.fillStyle = l.options.scaleColor, l.ctx.lineWidth = 1, c = [], a = b = 0; 24 >= b; a = ++b) c.push(d(a));
            return c
        },
        d = function(a) {
            var b;
            b = 0 === a % 6 ? 0 : .017 * l.options.size,
            l.ctx.save(),
            l.ctx.rotate(a * Math.PI / 12),
            l.ctx.fillRect(l.options.size / 2 - b, 0, .05 * -l.options.size + b, 1),
            l.ctx.restore()
        },
        k = function() {
            var a;
            a = l.options.size / 2 - l.options.lineWidth / 2,
            l.options.scaleColor !== !1 && (a -= .08 * l.options.size),
            l.ctx.beginPath(),
            l.ctx.arc(0, 0, a, 0, 2 * Math.PI, !0),
            l.ctx.closePath(),
            l.ctx.strokeStyle = l.options.trackColor,
            l.ctx.lineWidth = l.options.lineWidth,
            l.ctx.stroke()
        },
        i = function() {
            l.options.scaleColor !== !1 && j(),
            l.options.trackColor !== !1 && k()
        },
        f = function(b) {
            var c;
            i(),
            l.ctx.strokeStyle = a.isFunction(l.options.barColor) ? l.options.barColor(b) : l.options.barColor,
            l.ctx.lineCap = l.options.lineCap,
            l.ctx.lineWidth = l.options.lineWidth,
            c = l.options.size / 2 - l.options.lineWidth / 2,
            l.options.scaleColor !== !1 && (c -= .08 * l.options.size),
            l.ctx.save(),
            l.ctx.rotate( - Math.PI / 2),
            l.ctx.beginPath(),
            l.ctx.arc(0, 0, c, 0, 2 * Math.PI * b / 100, !1),
            l.ctx.stroke(),
            l.ctx.restore()
        },
        h = function() {
            return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame ||
            function(a) {
                return window.setTimeout(a, 1e3 / 60)
            }
        } (),
        e = function(a, b) {
            var c, d;
            l.options.onStart.call(l),
            l.percentage = b,
            d = Date.now(),
            c = function() {
                var e, j;
                return j = Date.now() - d,
                j < l.options.animate && h(c),
                l.ctx.clearRect( - l.options.size / 2, -l.options.size / 2, l.options.size, l.options.size),
                i.call(l),
                e = [g(j, a, b - a, l.options.animate)],
                l.options.onStep.call(l, e),
                f.call(l, e),
                j >= l.options.animate ? l.options.onStop.call(l) : void 0
            },
            h(c)
        },
        g = function(a, b, c, d) {
            var e, f;
            return e = function(a) {
                return Math.pow(a, 2)
            },
            f = function(a) {
                return 1 > a ? e(a) : 2 - e( - 2 * (a / 2) + 2)
            },
            a /= d / 2,
            c / 2 * f(a) + b
        },
        this.init()
    },
    a.easyPieChart.defaultOptions = {
        barColor: "#ef1e25",
        trackColor: "#f2f2f2",
        scaleColor: "#dfe0e0",
        lineCap: "round",
        rotate: 0,
        size: 110,
        lineWidth: 3,
        animate: !1,
        onStart: a.noop,
        onStop: a.noop,
        onStep: a.noop
    },
    a.fn.easyPieChart = function(b) {
        return a.each(this,
        function(c, d) {
            var e, f;
            return e = a(d),
            e.data("easyPieChart") ? void 0 : (f = a.extend({},
            b, e.data()), e.data("easyPieChart", new a.easyPieChart(d, f)))
        })
    },
    void 0
} (jQuery),
function(a, b, c) { !
    function(a) {
        "function" == typeof define && define.amd ? define(["jquery"], a) : jQuery && !jQuery.fn.sparkline && a(jQuery)
    } (function(d) {
        "use strict";
        var e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K = {},
        L = 0;
        e = function() {
            return {
                common: {
                    type: "line",
                    lineColor: "#00f",
                    fillColor: "#cdf",
                    defaultPixelsPerValue: 3,
                    width: "auto",
                    height: "auto",
                    composite: !1,
                    tagValuesAttribute: "values",
                    tagOptionsPrefix: "spark",
                    enableTagOptions: !1,
                    enableHighlight: !0,
                    highlightLighten: 1.4,
                    tooltipSkipNull: !0,
                    tooltipPrefix: "",
                    tooltipSuffix: "",
                    disableHiddenCheck: !1,
                    numberFormatter: !1,
                    numberDigitGroupCount: 3,
                    numberDigitGroupSep: ",",
                    numberDecimalMark: ".",
                    disableTooltips: !1,
                    disableInteraction: !1
                },
                line: {
                    spotColor: "#f80",
                    highlightSpotColor: "#5f5",
                    highlightLineColor: "#f22",
                    spotRadius: 1.5,
                    minSpotColor: "#f80",
                    maxSpotColor: "#f80",
                    lineWidth: 1,
                    normalRangeMin: c,
                    normalRangeMax: c,
                    normalRangeColor: "#ccc",
                    drawNormalOnTop: !1,
                    chartRangeMin: c,
                    chartRangeMax: c,
                    chartRangeMinX: c,
                    chartRangeMaxX: c,
                    tooltipFormat: new g('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}')
                },
                bar: {
                    barColor: "#3366cc",
                    negBarColor: "#f44",
                    stackedBarColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                    zeroColor: c,
                    nullColor: c,
                    zeroAxis: !0,
                    barWidth: 4,
                    barSpacing: 1,
                    chartRangeMax: c,
                    chartRangeMin: c,
                    chartRangeClip: !1,
                    colorMap: c,
                    tooltipFormat: new g('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{value}}{{suffix}}')
                },
                tristate: {
                    barWidth: 4,
                    barSpacing: 1,
                    posBarColor: "#6f6",
                    negBarColor: "#f44",
                    zeroBarColor: "#999",
                    colorMap: {},
                    tooltipFormat: new g('<span style="color: {{color}}">&#9679;</span> {{value:map}}'),
                    tooltipValueLookups: {
                        map: {
                            "-1": "Loss",
                            0 : "Draw",
                            1 : "Win"
                        }
                    }
                },
                discrete: {
                    lineHeight: "auto",
                    thresholdColor: c,
                    thresholdValue: 0,
                    chartRangeMax: c,
                    chartRangeMin: c,
                    chartRangeClip: !1,
                    tooltipFormat: new g("{{prefix}}{{value}}{{suffix}}")
                },
                bullet: {
                    targetColor: "#f33",
                    targetWidth: 3,
                    performanceColor: "#33f",
                    rangeColors: ["#d3dafe", "#a8b6ff", "#7f94ff"],
                    base: c,
                    tooltipFormat: new g("{{fieldkey:fields}} - {{value}}"),
                    tooltipValueLookups: {
                        fields: {
                            r: "Range",
                            p: "Performance",
                            t: "Target"
                        }
                    }
                },
                pie: {
                    offset: 0,
                    sliceColors: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                    borderWidth: 0,
                    borderColor: "#000",
                    tooltipFormat: new g('<span style="color: {{color}}">&#9679;</span> {{value}} ({{percent.1}}%)')
                },
                box: {
                    raw: !1,
                    boxLineColor: "#000",
                    boxFillColor: "#cdf",
                    whiskerColor: "#000",
                    outlierLineColor: "#333",
                    outlierFillColor: "#fff",
                    medianColor: "#f00",
                    showOutliers: !0,
                    outlierIQR: 1.5,
                    spotRadius: 1.5,
                    target: c,
                    targetColor: "#4a2",
                    chartRangeMax: c,
                    chartRangeMin: c,
                    tooltipFormat: new g("{{field:fields}}: {{value}}"),
                    tooltipFormatFieldlistKey: "field",
                    tooltipValueLookups: {
                        fields: {
                            lq: "Lower Quartile",
                            med: "Median",
                            uq: "Upper Quartile",
                            lo: "Left Outlier",
                            ro: "Right Outlier",
                            lw: "Left Whisker",
                            rw: "Right Whisker"
                        }
                    }
                }
            }
        },
        D = '.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}',
        f = function() {
            var a, b;
            return a = function() {
                this.init.apply(this, arguments)
            },
            arguments.length > 1 ? (arguments[0] ? (a.prototype = d.extend(new arguments[0], arguments[arguments.length - 1]), a._super = arguments[0].prototype) : a.prototype = arguments[arguments.length - 1], arguments.length > 2 && (b = Array.prototype.slice.call(arguments, 1, -1), b.unshift(a.prototype), d.extend.apply(d, b))) : a.prototype = arguments[0],
            a.prototype.cls = a,
            a
        },
        d.SPFormatClass = g = f({
            fre: /\{\{([\w.]+?)(:(.+?))?\}\}/g,
            precre: /(\w+)\.(\d+)/,
            init: function(a, b) {
                this.format = a,
                this.fclass = b
            },
            render: function(a, b, d) {
                var e, f, g, h, i, j = this,
                k = a;
                return this.format.replace(this.fre,
                function() {
                    var a;
                    return f = arguments[1],
                    g = arguments[3],
                    e = j.precre.exec(f),
                    e ? (i = e[2], f = e[1]) : i = !1,
                    h = k[f],
                    h === c ? "": g && b && b[g] ? (a = b[g], a.get ? b[g].get(h) || h: b[g][h] || h) : (m(h) && (h = d.get("numberFormatter") ? d.get("numberFormatter")(h) : r(h, i, d.get("numberDigitGroupCount"), d.get("numberDigitGroupSep"), d.get("numberDecimalMark"))), h)
                })
            }
        }),
        d.spformat = function(a, b) {
            return new g(a, b)
        },
        h = function(a, b, c) {
            return b > a ? b: a > c ? c: a
        },
        i = function(a, c) {
            var d;
            return 2 === c ? (d = b.floor(a.length / 2), a.length % 2 ? a[d] : (a[d - 1] + a[d]) / 2) : a.length % 2 ? (d = (a.length * c + c) / 4, d % 1 ? (a[b.floor(d)] + a[b.floor(d) - 1]) / 2 : a[d - 1]) : (d = (a.length * c + 2) / 4, d % 1 ? (a[b.floor(d)] + a[b.floor(d) - 1]) / 2 : a[d - 1])
        },
        j = function(a) {
            var b;
            switch (a) {
            case "undefined":
                a = c;
                break;
            case "null":
                a = null;
                break;
            case "true":
                a = !0;
                break;
            case "false":
                a = !1;
                break;
            default:
                b = parseFloat(a),
                a == b && (a = b)
            }
            return a
        },
        k = function(a) {
            var b, c = [];
            for (b = a.length; b--;) c[b] = j(a[b]);
            return c
        },
        l = function(a, b) {
            var c, d, e = [];
            for (c = 0, d = a.length; d > c; c++) a[c] !== b && e.push(a[c]);
            return e
        },
        m = function(a) {
            return ! isNaN(parseFloat(a)) && isFinite(a)
        },
        r = function(a, b, c, e, f) {
            var g, h;
            for (a = (b === !1 ? parseFloat(a).toString() : a.toFixed(b)).split(""), g = (g = d.inArray(".", a)) < 0 ? a.length: g, g < a.length && (a[g] = f), h = g - c; h > 0; h -= c) a.splice(h, 0, e);
            return a.join("")
        },
        n = function(a, b, c) {
            var d;
            for (d = b.length; d--;) if ((!c || null !== b[d]) && b[d] !== a) return ! 1;
            return ! 0
        },
        o = function(a) {
            var b, c = 0;
            for (b = a.length; b--;) c += "number" == typeof a[b] ? a[b] : 0;
            return c
        },
        q = function(a) {
            return d.isArray(a) ? a: [a]
        },
        p = function(b) {
            var c;
            a.createStyleSheet ? a.createStyleSheet().cssText = b: (c = a.createElement("style"), c.type = "text/css", a.getElementsByTagName("head")[0].appendChild(c), c["string" == typeof a.body.style.WebkitAppearance ? "innerText": "innerHTML"] = b)
        },
        d.fn.simpledraw = function(b, e, f, g) {
            var h, i;
            if (f && (h = this.data("_jqs_vcanvas"))) return h;
            if (d.fn.sparkline.canvas === !1) return ! 1;
            if (d.fn.sparkline.canvas === c) {
                var j = a.createElement("canvas");
                if (j.getContext && j.getContext("2d")) d.fn.sparkline.canvas = function(a, b, c, d) {
                    return new H(a, b, c, d)
                };
                else {
                    if (!a.namespaces || a.namespaces.v) return d.fn.sparkline.canvas = !1,
                    !1;
                    a.namespaces.add("v", "urn:schemas-microsoft-com:vml", "#default#VML"),
                    d.fn.sparkline.canvas = function(a, b, c) {
                        return new I(a, b, c)
                    }
                }
            }
            return b === c && (b = d(this).innerWidth()),
            e === c && (e = d(this).innerHeight()),
            h = d.fn.sparkline.canvas(b, e, this, g),
            i = d(this).data("_jqs_mhandler"),
            i && i.registerCanvas(h),
            h
        },
        d.fn.cleardraw = function() {
            var a = this.data("_jqs_vcanvas");
            a && a.reset()
        },
        d.RangeMapClass = s = f({
            init: function(a) {
                var b, c, d = [];
                for (b in a) a.hasOwnProperty(b) && "string" == typeof b && b.indexOf(":") > -1 && (c = b.split(":"), c[0] = 0 === c[0].length ? -1 / 0 : parseFloat(c[0]), c[1] = 0 === c[1].length ? 1 / 0 : parseFloat(c[1]), c[2] = a[b], d.push(c));
                this.map = a,
                this.rangelist = d || !1
            },
            get: function(a) {
                var b, d, e, f = this.rangelist;
                if ((e = this.map[a]) !== c) return e;
                if (f) for (b = f.length; b--;) if (d = f[b], d[0] <= a && d[1] >= a) return d[2];
                return c
            }
        }),
        d.range_map = function(a) {
            return new s(a)
        },
        t = f({
            init: function(a, b) {
                var c = d(a);
                this.$el = c,
                this.options = b,
                this.currentPageX = 0,
                this.currentPageY = 0,
                this.el = a,
                this.splist = [],
                this.tooltip = null,
                this.over = !1,
                this.displayTooltips = !b.get("disableTooltips"),
                this.highlightEnabled = !b.get("disableHighlight")
            },
            registerSparkline: function(a) {
                this.splist.push(a),
                this.over && this.updateDisplay()
            },
            registerCanvas: function(a) {
                var b = d(a.canvas);
                this.canvas = a,
                this.$canvas = b,
                b.mouseenter(d.proxy(this.mouseenter, this)),
                b.mouseleave(d.proxy(this.mouseleave, this)),
                b.click(d.proxy(this.mouseclick, this))
            },
            reset: function(a) {
                this.splist = [],
                this.tooltip && a && (this.tooltip.remove(), this.tooltip = c)
            },
            mouseclick: function(a) {
                var b = d.Event("sparklineClick");
                b.originalEvent = a,
                b.sparklines = this.splist,
                this.$el.trigger(b)
            },
            mouseenter: function(b) {
                d(a.body).unbind("mousemove.jqs"),
                d(a.body).bind("mousemove.jqs", d.proxy(this.mousemove, this)),
                this.over = !0,
                this.currentPageX = b.pageX,
                this.currentPageY = b.pageY,
                this.currentEl = b.target,
                !this.tooltip && this.displayTooltips && (this.tooltip = new u(this.options), this.tooltip.updatePosition(b.pageX, b.pageY)),
                this.updateDisplay()
            },
            mouseleave: function() {
                d(a.body).unbind("mousemove.jqs");
                var b, c, e = this.splist,
                f = e.length,
                g = !1;
                for (this.over = !1, this.currentEl = null, this.tooltip && (this.tooltip.remove(), this.tooltip = null), c = 0; f > c; c++) b = e[c],
                b.clearRegionHighlight() && (g = !0);
                g && this.canvas.render()
            },
            mousemove: function(a) {
                this.currentPageX = a.pageX,
                this.currentPageY = a.pageY,
                this.currentEl = a.target,
                this.tooltip && this.tooltip.updatePosition(a.pageX, a.pageY),
                this.updateDisplay()
            },
            updateDisplay: function() {
                var a, b, c, e, f, g = this.splist,
                h = g.length,
                i = !1,
                j = this.$canvas.offset(),
                k = this.currentPageX - j.left,
                l = this.currentPageY - j.top;
                if (this.over) {
                    for (c = 0; h > c; c++) b = g[c],
                    e = b.setRegionHighlight(this.currentEl, k, l),
                    e && (i = !0);
                    if (i) {
                        if (f = d.Event("sparklineRegionChange"), f.sparklines = this.splist, this.$el.trigger(f), this.tooltip) {
                            for (a = "", c = 0; h > c; c++) b = g[c],
                            a += b.getCurrentRegionTooltip();
                            this.tooltip.setContent(a)
                        }
                        this.disableHighlight || this.canvas.render()
                    }
                    null === e && this.mouseleave()
                }
            }
        }),
        u = f({
            sizeStyle: "position: static !important;display: block !important;visibility: hidden !important;float: left !important;",
            init: function(b) {
                var c, e = b.get("tooltipClassname", "jqstooltip"),
                f = this.sizeStyle;
                this.container = b.get("tooltipContainer") || a.body,
                this.tooltipOffsetX = b.get("tooltipOffsetX", 10),
                this.tooltipOffsetY = b.get("tooltipOffsetY", 12),
                d("#jqssizetip").remove(),
                d("#jqstooltip").remove(),
                this.sizetip = d("<div/>", {
                    id: "jqssizetip",
                    style: f,
                    "class": e
                }),
                this.tooltip = d("<div/>", {
                    id: "jqstooltip",
                    "class": e
                }).appendTo(this.container),
                c = this.tooltip.offset(),
                this.offsetLeft = c.left,
                this.offsetTop = c.top,
                this.hidden = !0,
                d(window).unbind("resize.jqs scroll.jqs"),
                d(window).bind("resize.jqs scroll.jqs", d.proxy(this.updateWindowDims, this)),
                this.updateWindowDims()
            },
            updateWindowDims: function() {
                this.scrollTop = d(window).scrollTop(),
                this.scrollLeft = d(window).scrollLeft(),
                this.scrollRight = this.scrollLeft + d(window).width(),
                this.updatePosition()
            },
            getSize: function(a) {
                this.sizetip.html(a).appendTo(this.container),
                this.width = this.sizetip.width() + 1,
                this.height = this.sizetip.height(),
                this.sizetip.remove()
            },
            setContent: function(a) {
                return a ? (this.getSize(a), this.tooltip.html(a).css({
                    width: this.width,
                    height: this.height,
                    visibility: "visible"
                }), this.hidden && (this.hidden = !1, this.updatePosition()), void 0) : (this.tooltip.css("visibility", "hidden"), this.hidden = !0, void 0)
            },
            updatePosition: function(a, b) {
                if (a === c) {
                    if (this.mousex === c) return;
                    a = this.mousex - this.offsetLeft,
                    b = this.mousey - this.offsetTop
                } else this.mousex = a -= this.offsetLeft,
                this.mousey = b -= this.offsetTop;
                this.height && this.width && !this.hidden && (b -= this.height + this.tooltipOffsetY, a += this.tooltipOffsetX, b < this.scrollTop && (b = this.scrollTop), a < this.scrollLeft ? a = this.scrollLeft: a + this.width > this.scrollRight && (a = this.scrollRight - this.width), this.tooltip.css({
                    left: a,
                    top: b
                }))
            },
            remove: function() {
                this.tooltip.remove(),
                this.sizetip.remove(),
                this.sizetip = this.tooltip = c,
                d(window).unbind("resize.jqs scroll.jqs")
            }
        }),
        E = function() {
            p(D)
        },
        d(E),
        J = [],
        d.fn.sparkline = function(b, e) {
            return this.each(function() {
                var f, g, h = new d.fn.sparkline.options(this, e),
                i = d(this);
                if (f = function() {
                    var e, f, g, j, k, l, m;
                    return "html" === b || b === c ? (m = this.getAttribute(h.get("tagValuesAttribute")), (m === c || null === m) && (m = i.html()), e = m.replace(/(^\s*<!--)|(-->\s*$)|\s+/g, "").split(",")) : e = b,
                    f = "auto" === h.get("width") ? e.length * h.get("defaultPixelsPerValue") : h.get("width"),
                    "auto" === h.get("height") ? h.get("composite") && d.data(this, "_jqs_vcanvas") || (j = a.createElement("span"), j.innerHTML = "a", i.html(j), g = d(j).innerHeight() || d(j).height(), d(j).remove(), j = null) : g = h.get("height"),
                    h.get("disableInteraction") ? k = !1 : (k = d.data(this, "_jqs_mhandler"), k ? h.get("composite") || k.reset() : (k = new t(this, h), d.data(this, "_jqs_mhandler", k))),
                    h.get("composite") && !d.data(this, "_jqs_vcanvas") ? (d.data(this, "_jqs_errnotify") || (alert("Attempted to attach a composite sparkline to an element with no existing sparkline"), d.data(this, "_jqs_errnotify", !0)), void 0) : (l = new(d.fn.sparkline[h.get("type")])(this, e, h, f, g), l.render(), k && k.registerSparkline(l), void 0)
                },
                d(this).html() && !h.get("disableHiddenCheck") && d(this).is(":hidden") || !d(this).parents("body").length) {
                    if (!h.get("composite") && d.data(this, "_jqs_pending")) for (g = J.length; g; g--) J[g - 1][0] == this && J.splice(g - 1, 1);
                    J.push([this, f]),
                    d.data(this, "_jqs_pending", !0)
                } else f.call(this)
            })
        },
        d.fn.sparkline.defaults = e(),
        d.sparkline_display_visible = function() {
            var a, b, c, e = [];
            for (b = 0, c = J.length; c > b; b++) a = J[b][0],
            d(a).is(":visible") && !d(a).parents().is(":hidden") ? (J[b][1].call(a), d.data(J[b][0], "_jqs_pending", !1), e.push(b)) : !d(a).closest("html").length && !d.data(a, "_jqs_pending") && (d.data(J[b][0], "_jqs_pending", !1), e.push(b));
            for (b = e.length; b; b--) J.splice(e[b - 1], 1)
        },
        d.fn.sparkline.options = f({
            init: function(a, b) {
                var c, e, f, g;
                this.userOptions = b = b || {},
                this.tag = a,
                this.tagValCache = {},
                e = d.fn.sparkline.defaults,
                f = e.common,
                this.tagOptionsPrefix = b.enableTagOptions && (b.tagOptionsPrefix || f.tagOptionsPrefix),
                g = this.getTagSetting("type"),
                c = g === K ? e[b.type || f.type] : e[g],
                this.mergedOptions = d.extend({},
                f, c, b)
            },
            getTagSetting: function(a) {
                var b, d, e, f, g = this.tagOptionsPrefix;
                if (g === !1 || g === c) return K;
                if (this.tagValCache.hasOwnProperty(a)) b = this.tagValCache.key;
                else {
                    if (b = this.tag.getAttribute(g + a), b === c || null === b) b = K;
                    else if ("[" === b.substr(0, 1)) for (b = b.substr(1, b.length - 2).split(","), d = b.length; d--;) b[d] = j(b[d].replace(/(^\s*)|(\s*$)/g, ""));
                    else if ("{" === b.substr(0, 1)) for (e = b.substr(1, b.length - 2).split(","), b = {},
                    d = e.length; d--;) f = e[d].split(":", 2),
                    b[f[0].replace(/(^\s*)|(\s*$)/g, "")] = j(f[1].replace(/(^\s*)|(\s*$)/g, ""));
                    else b = j(b);
                    this.tagValCache.key = b
                }
                return b
            },
            get: function(a, b) {
                var d, e = this.getTagSetting(a);
                return e !== K ? e: (d = this.mergedOptions[a]) === c ? b: d
            }
        }),
        d.fn.sparkline._base = f({
            disabled: !1,
            init: function(a, b, e, f, g) {
                this.el = a,
                this.$el = d(a),
                this.values = b,
                this.options = e,
                this.width = f,
                this.height = g,
                this.currentRegion = c
            },
            initTarget: function() {
                var a = !this.options.get("disableInteraction"); (this.target = this.$el.simpledraw(this.width, this.height, this.options.get("composite"), a)) ? (this.canvasWidth = this.target.pixelWidth, this.canvasHeight = this.target.pixelHeight) : this.disabled = !0
            },
            render: function() {
                return this.disabled ? (this.el.innerHTML = "", !1) : !0
            },
            getRegion: function() {},
            setRegionHighlight: function(a, b, d) {
                var e, f = this.currentRegion,
                g = !this.options.get("disableHighlight");
                return b > this.canvasWidth || d > this.canvasHeight || 0 > b || 0 > d ? null: (e = this.getRegion(a, b, d), f !== e ? (f !== c && g && this.removeHighlight(), this.currentRegion = e, e !== c && g && this.renderHighlight(), !0) : !1)
            },
            clearRegionHighlight: function() {
                return this.currentRegion !== c ? (this.removeHighlight(), this.currentRegion = c, !0) : !1
            },
            renderHighlight: function() {
                this.changeHighlight(!0)
            },
            removeHighlight: function() {
                this.changeHighlight(!1)
            },
            changeHighlight: function() {},
            getCurrentRegionTooltip: function() {
                var a, b, e, f, h, i, j, k, l, m, n, o, p, q, r = this.options,
                s = "",
                t = [];
                if (this.currentRegion === c) return "";
                if (a = this.getCurrentRegionFields(), n = r.get("tooltipFormatter")) return n(this, r, a);
                if (r.get("tooltipChartTitle") && (s += '<div class="jqs jqstitle">' + r.get("tooltipChartTitle") + "</div>\n"), b = this.options.get("tooltipFormat"), !b) return "";
                if (d.isArray(b) || (b = [b]), d.isArray(a) || (a = [a]), j = this.options.get("tooltipFormatFieldlist"), k = this.options.get("tooltipFormatFieldlistKey"), j && k) {
                    for (l = [], i = a.length; i--;) m = a[i][k],
                    -1 != (q = d.inArray(m, j)) && (l[q] = a[i]);
                    a = l
                }
                for (e = b.length, p = a.length, i = 0; e > i; i++) for (o = b[i], "string" == typeof o && (o = new g(o)), f = o.fclass || "jqsfield", q = 0; p > q; q++) a[q].isNull && r.get("tooltipSkipNull") || (d.extend(a[q], {
                    prefix: r.get("tooltipPrefix"),
                    suffix: r.get("tooltipSuffix")
                }), h = o.render(a[q], r.get("tooltipValueLookups"), r), t.push('<div class="' + f + '">' + h + "</div>"));
                return t.length ? s + t.join("\n") : ""
            },
            getCurrentRegionFields: function() {},
            calcHighlightColor: function(a, c) {
                var d, e, f, g, i = c.get("highlightColor"),
                j = c.get("highlightLighten");
                if (i) return i;
                if (j && (d = /^#([0-9a-f])([0-9a-f])([0-9a-f])$/i.exec(a) || /^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i.exec(a))) {
                    for (f = [], e = 4 === a.length ? 16 : 1, g = 0; 3 > g; g++) f[g] = h(b.round(parseInt(d[g + 1], 16) * e * j), 0, 255);
                    return "rgb(" + f.join(",") + ")"
                }
                return a
            }
        }),
        v = {
            changeHighlight: function(a) {
                var b, c = this.currentRegion,
                e = this.target,
                f = this.regionShapes[c];
                f && (b = this.renderRegion(c, a), d.isArray(b) || d.isArray(f) ? (e.replaceWithShapes(f, b), this.regionShapes[c] = d.map(b,
                function(a) {
                    return a.id
                })) : (e.replaceWithShape(f, b), this.regionShapes[c] = b.id))
            },
            render: function() {
                var a, b, c, e, f = this.values,
                g = this.target,
                h = this.regionShapes;
                if (this.cls._super.render.call(this)) {
                    for (c = f.length; c--;) if (a = this.renderRegion(c)) if (d.isArray(a)) {
                        for (b = [], e = a.length; e--;) a[e].append(),
                        b.push(a[e].id);
                        h[c] = b
                    } else a.append(),
                    h[c] = a.id;
                    else h[c] = null;
                    g.render()
                }
            }
        },
        d.fn.sparkline.line = w = f(d.fn.sparkline._base, {
            type: "line",
            init: function(a, b, c, d, e) {
                w._super.init.call(this, a, b, c, d, e),
                this.vertices = [],
                this.regionMap = [],
                this.xvalues = [],
                this.yvalues = [],
                this.yminmax = [],
                this.hightlightSpotId = null,
                this.lastShapeId = null,
                this.initTarget()
            },
            getRegion: function(a, b) {
                var d, e = this.regionMap;
                for (d = e.length; d--;) if (null !== e[d] && b >= e[d][0] && b <= e[d][1]) return e[d][2];
                return c
            },
            getCurrentRegionFields: function() {
                var a = this.currentRegion;
                return {
                    isNull: null === this.yvalues[a],
                    x: this.xvalues[a],
                    y: this.yvalues[a],
                    color: this.options.get("lineColor"),
                    fillColor: this.options.get("fillColor"),
                    offset: a
                }
            },
            renderHighlight: function() {
                var a, b, d = this.currentRegion,
                e = this.target,
                f = this.vertices[d],
                g = this.options,
                h = g.get("spotRadius"),
                i = g.get("highlightSpotColor"),
                j = g.get("highlightLineColor");
                f && (h && i && (a = e.drawCircle(f[0], f[1], h, c, i), this.highlightSpotId = a.id, e.insertAfterShape(this.lastShapeId, a)), j && (b = e.drawLine(f[0], this.canvasTop, f[0], this.canvasTop + this.canvasHeight, j), this.highlightLineId = b.id, e.insertAfterShape(this.lastShapeId, b)))
            },
            removeHighlight: function() {
                var a = this.target;
                this.highlightSpotId && (a.removeShapeId(this.highlightSpotId), this.highlightSpotId = null),
                this.highlightLineId && (a.removeShapeId(this.highlightLineId), this.highlightLineId = null)
            },
            scanValues: function() {
                var a, c, d, e, f, g = this.values,
                h = g.length,
                i = this.xvalues,
                j = this.yvalues,
                k = this.yminmax;
                for (a = 0; h > a; a++) c = g[a],
                d = "string" == typeof g[a],
                e = "object" == typeof g[a] && g[a] instanceof Array,
                f = d && g[a].split(":"),
                d && 2 === f.length ? (i.push(Number(f[0])), j.push(Number(f[1])), k.push(Number(f[1]))) : e ? (i.push(c[0]), j.push(c[1]), k.push(c[1])) : (i.push(a), null === g[a] || "null" === g[a] ? j.push(null) : (j.push(Number(c)), k.push(Number(c))));
                this.options.get("xvalues") && (i = this.options.get("xvalues")),
                this.maxy = this.maxyorg = b.max.apply(b, k),
                this.miny = this.minyorg = b.min.apply(b, k),
                this.maxx = b.max.apply(b, i),
                this.minx = b.min.apply(b, i),
                this.xvalues = i,
                this.yvalues = j,
                this.yminmax = k
            },
            processRangeOptions: function() {
                var a = this.options,
                b = a.get("normalRangeMin"),
                d = a.get("normalRangeMax");
                b !== c && (b < this.miny && (this.miny = b), d > this.maxy && (this.maxy = d)),
                a.get("chartRangeMin") !== c && (a.get("chartRangeClip") || a.get("chartRangeMin") < this.miny) && (this.miny = a.get("chartRangeMin")),
                a.get("chartRangeMax") !== c && (a.get("chartRangeClip") || a.get("chartRangeMax") > this.maxy) && (this.maxy = a.get("chartRangeMax")),
                a.get("chartRangeMinX") !== c && (a.get("chartRangeClipX") || a.get("chartRangeMinX") < this.minx) && (this.minx = a.get("chartRangeMinX")),
                a.get("chartRangeMaxX") !== c && (a.get("chartRangeClipX") || a.get("chartRangeMaxX") > this.maxx) && (this.maxx = a.get("chartRangeMaxX"))
            },
            drawNormalRange: function(a, d, e, f, g) {
                var h = this.options.get("normalRangeMin"),
                i = this.options.get("normalRangeMax"),
                j = d + b.round(e - e * ((i - this.miny) / g)),
                k = b.round(e * (i - h) / g);
                this.target.drawRect(a, j, f, k, c, this.options.get("normalRangeColor")).append()
            },
            render: function() {
                var a, e, f, g, h, i, j, k, l, m, n, o, p, q, r, t, u, v, x, y, z, A, B, C, D, E = this.options,
                F = this.target,
                G = this.canvasWidth,
                H = this.canvasHeight,
                I = this.vertices,
                J = E.get("spotRadius"),
                K = this.regionMap;
                if (w._super.render.call(this) && (this.scanValues(), this.processRangeOptions(), B = this.xvalues, C = this.yvalues, this.yminmax.length && !(this.yvalues.length < 2))) {
                    for (g = h = 0, a = 0 === this.maxx - this.minx ? 1 : this.maxx - this.minx, e = 0 === this.maxy - this.miny ? 1 : this.maxy - this.miny, f = this.yvalues.length - 1, J && (4 * J > G || 4 * J > H) && (J = 0), J && (z = E.get("highlightSpotColor") && !E.get("disableInteraction"), (z || E.get("minSpotColor") || E.get("spotColor") && C[f] === this.miny) && (H -= b.ceil(J)), (z || E.get("maxSpotColor") || E.get("spotColor") && C[f] === this.maxy) && (H -= b.ceil(J), g += b.ceil(J)), (z || (E.get("minSpotColor") || E.get("maxSpotColor")) && (C[0] === this.miny || C[0] === this.maxy)) && (h += b.ceil(J), G -= b.ceil(J)), (z || E.get("spotColor") || E.get("minSpotColor") || E.get("maxSpotColor") && (C[f] === this.miny || C[f] === this.maxy)) && (G -= b.ceil(J))), H--, E.get("normalRangeMin") !== c && !E.get("drawNormalOnTop") && this.drawNormalRange(h, g, H, G, e), j = [], k = [j], q = r = null, t = C.length, D = 0; t > D; D++) l = B[D],
                    n = B[D + 1],
                    m = C[D],
                    o = h + b.round((l - this.minx) * (G / a)),
                    p = t - 1 > D ? h + b.round((n - this.minx) * (G / a)) : G,
                    r = o + (p - o) / 2,
                    K[D] = [q || 0, r, D],
                    q = r,
                    null === m ? D && (null !== C[D - 1] && (j = [], k.push(j)), I.push(null)) : (m < this.miny && (m = this.miny), m > this.maxy && (m = this.maxy), j.length || j.push([o, g + H]), i = [o, g + b.round(H - H * ((m - this.miny) / e))], j.push(i), I.push(i));
                    for (u = [], v = [], x = k.length, D = 0; x > D; D++) j = k[D],
                    j.length && (E.get("fillColor") && (j.push([j[j.length - 1][0], g + H]), v.push(j.slice(0)), j.pop()), j.length > 2 && (j[0] = [j[0][0], j[1][1]]), u.push(j));
                    for (x = v.length, D = 0; x > D; D++) F.drawShape(v[D], E.get("fillColor"), E.get("fillColor")).append();
                    for (E.get("normalRangeMin") !== c && E.get("drawNormalOnTop") && this.drawNormalRange(h, g, H, G, e), x = u.length, D = 0; x > D; D++) F.drawShape(u[D], E.get("lineColor"), c, E.get("lineWidth")).append();
                    if (J && E.get("valueSpots")) for (y = E.get("valueSpots"), y.get === c && (y = new s(y)), D = 0; t > D; D++) A = y.get(C[D]),
                    A && F.drawCircle(h + b.round((B[D] - this.minx) * (G / a)), g + b.round(H - H * ((C[D] - this.miny) / e)), J, c, A).append();
                    J && E.get("spotColor") && null !== C[f] && F.drawCircle(h + b.round((B[B.length - 1] - this.minx) * (G / a)), g + b.round(H - H * ((C[f] - this.miny) / e)), J, c, E.get("spotColor")).append(),
                    this.maxy !== this.minyorg && (J && E.get("minSpotColor") && (l = B[d.inArray(this.minyorg, C)], F.drawCircle(h + b.round((l - this.minx) * (G / a)), g + b.round(H - H * ((this.minyorg - this.miny) / e)), J, c, E.get("minSpotColor")).append()), J && E.get("maxSpotColor") && (l = B[d.inArray(this.maxyorg, C)], F.drawCircle(h + b.round((l - this.minx) * (G / a)), g + b.round(H - H * ((this.maxyorg - this.miny) / e)), J, c, E.get("maxSpotColor")).append())),
                    this.lastShapeId = F.getLastShapeId(),
                    this.canvasTop = g,
                    F.render()
                }
            }
        }),
        d.fn.sparkline.bar = x = f(d.fn.sparkline._base, v, {
            type: "bar",
            init: function(a, e, f, g, i) {
                var m, n, o, p, q, r, t, u, v, w, y, z, A, B, C, D, E, F, G, H, I, J, K = parseInt(f.get("barWidth"), 10),
                L = parseInt(f.get("barSpacing"), 10),
                M = f.get("chartRangeMin"),
                N = f.get("chartRangeMax"),
                O = f.get("chartRangeClip"),
                P = 1 / 0,
                Q = -1 / 0;
                for (x._super.init.call(this, a, e, f, g, i), r = 0, t = e.length; t > r; r++) H = e[r],
                m = "string" == typeof H && H.indexOf(":") > -1,
                (m || d.isArray(H)) && (C = !0, m && (H = e[r] = k(H.split(":"))), H = l(H, null), n = b.min.apply(b, H), o = b.max.apply(b, H), P > n && (P = n), o > Q && (Q = o));
                this.stacked = C,
                this.regionShapes = {},
                this.barWidth = K,
                this.barSpacing = L,
                this.totalBarWidth = K + L,
                this.width = g = e.length * K + (e.length - 1) * L,
                this.initTarget(),
                O && (A = M === c ? -1 / 0 : M, B = N === c ? 1 / 0 : N),
                q = [],
                p = C ? [] : q;
                var R = [],
                S = [];
                for (r = 0, t = e.length; t > r; r++) if (C) for (D = e[r], e[r] = G = [], R[r] = 0, p[r] = S[r] = 0, E = 0, F = D.length; F > E; E++) H = G[E] = O ? h(D[E], A, B) : D[E],
                null !== H && (H > 0 && (R[r] += H), 0 > P && Q > 0 ? 0 > H ? S[r] += b.abs(H) : p[r] += H: p[r] += b.abs(H - (0 > H ? Q: P)), q.push(H));
                else H = O ? h(e[r], A, B) : e[r],
                H = e[r] = j(H),
                null !== H && q.push(H);
                this.max = z = b.max.apply(b, q),
                this.min = y = b.min.apply(b, q),
                this.stackMax = Q = C ? b.max.apply(b, R) : z,
                this.stackMin = P = C ? b.min.apply(b, q) : y,
                f.get("chartRangeMin") !== c && (f.get("chartRangeClip") || f.get("chartRangeMin") < y) && (y = f.get("chartRangeMin")),
                f.get("chartRangeMax") !== c && (f.get("chartRangeClip") || f.get("chartRangeMax") > z) && (z = f.get("chartRangeMax")),
                this.zeroAxis = v = f.get("zeroAxis", !0),
                w = 0 >= y && z >= 0 && v ? 0 : 0 == v ? y: y > 0 ? y: z,
                this.xaxisOffset = w,
                u = C ? b.max.apply(b, p) + b.max.apply(b, S) : z - y,
                this.canvasHeightEf = v && 0 > y ? this.canvasHeight - 2 : this.canvasHeight - 1,
                w > y ? (J = C && z >= 0 ? Q: z, I = (J - w) / u * this.canvasHeight, I !== b.ceil(I) && (this.canvasHeightEf -= 2, I = b.ceil(I))) : I = this.canvasHeight,
                this.yoffset = I,
                d.isArray(f.get("colorMap")) ? (this.colorMapByIndex = f.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = f.get("colorMap"), this.colorMapByValue && this.colorMapByValue.get === c && (this.colorMapByValue = new s(this.colorMapByValue))),
                this.range = u
            },
            getRegion: function(a, d) {
                var e = b.floor(d / this.totalBarWidth);
                return 0 > e || e >= this.values.length ? c: e
            },
            getCurrentRegionFields: function() {
                var a, b, c = this.currentRegion,
                d = q(this.values[c]),
                e = [];
                for (b = d.length; b--;) a = d[b],
                e.push({
                    isNull: null === a,
                    value: a,
                    color: this.calcColor(b, a, c),
                    offset: c
                });
                return e
            },
            calcColor: function(a, b, e) {
                var f, g, h = this.colorMapByIndex,
                i = this.colorMapByValue,
                j = this.options;
                return f = this.stacked ? j.get("stackedBarColor") : 0 > b ? j.get("negBarColor") : j.get("barColor"),
                0 === b && j.get("zeroColor") !== c && (f = j.get("zeroColor")),
                i && (g = i.get(b)) ? f = g: h && h.length > e && (f = h[e]),
                d.isArray(f) ? f[a % f.length] : f
            },
            renderRegion: function(a, e) {
                var f, g, h, i, j, k, l, m, o, p, q = this.values[a],
                r = this.options,
                s = this.xaxisOffset,
                t = [],
                u = this.range,
                v = this.stacked,
                w = this.target,
                x = a * this.totalBarWidth,
                y = this.canvasHeightEf,
                z = this.yoffset;
                if (q = d.isArray(q) ? q: [q], l = q.length, m = q[0], i = n(null, q), p = n(s, q, !0), i) return r.get("nullColor") ? (h = e ? r.get("nullColor") : this.calcHighlightColor(r.get("nullColor"), r), f = z > 0 ? z - 1 : z, w.drawRect(x, f, this.barWidth - 1, 0, h, h)) : c;
                for (j = z, k = 0; l > k; k++) {
                    if (m = q[k], v && m === s) {
                        if (!p || o) continue;
                        o = !0
                    }
                    g = u > 0 ? b.floor(y * (b.abs(m - s) / u)) + 1 : 1,
                    s > m || m === s && 0 === z ? (f = j, j += g) : (f = z - g, z -= g),
                    h = this.calcColor(k, m, a),
                    e && (h = this.calcHighlightColor(h, r)),
                    t.push(w.drawRect(x, f, this.barWidth - 1, g - 1, h, h))
                }
                return 1 === t.length ? t[0] : t
            }
        }),
        d.fn.sparkline.tristate = y = f(d.fn.sparkline._base, v, {
            type: "tristate",
            init: function(a, b, e, f, g) {
                var h = parseInt(e.get("barWidth"), 10),
                i = parseInt(e.get("barSpacing"), 10);
                y._super.init.call(this, a, b, e, f, g),
                this.regionShapes = {},
                this.barWidth = h,
                this.barSpacing = i,
                this.totalBarWidth = h + i,
                this.values = d.map(b, Number),
                this.width = f = b.length * h + (b.length - 1) * i,
                d.isArray(e.get("colorMap")) ? (this.colorMapByIndex = e.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = e.get("colorMap"), this.colorMapByValue && this.colorMapByValue.get === c && (this.colorMapByValue = new s(this.colorMapByValue))),
                this.initTarget()
            },
            getRegion: function(a, c) {
                return b.floor(c / this.totalBarWidth)
            },
            getCurrentRegionFields: function() {
                var a = this.currentRegion;
                return {
                    isNull: this.values[a] === c,
                    value: this.values[a],
                    color: this.calcColor(this.values[a], a),
                    offset: a
                }
            },
            calcColor: function(a, b) {
                var c, d, e = this.values,
                f = this.options,
                g = this.colorMapByIndex,
                h = this.colorMapByValue;
                return c = h && (d = h.get(a)) ? d: g && g.length > b ? g[b] : e[b] < 0 ? f.get("negBarColor") : e[b] > 0 ? f.get("posBarColor") : f.get("zeroBarColor")
            },
            renderRegion: function(a, c) {
                var d, e, f, g, h, i, j = this.values,
                k = this.options,
                l = this.target;
                return d = l.pixelHeight,
                f = b.round(d / 2),
                g = a * this.totalBarWidth,
                j[a] < 0 ? (h = f, e = f - 1) : j[a] > 0 ? (h = 0, e = f - 1) : (h = f - 1, e = 2),
                i = this.calcColor(j[a], a),
                null !== i ? (c && (i = this.calcHighlightColor(i, k)), l.drawRect(g, h, this.barWidth - 1, e - 1, i, i)) : void 0
            }
        }),
        d.fn.sparkline.discrete = z = f(d.fn.sparkline._base, v, {
            type: "discrete",
            init: function(a, e, f, g, h) {
                z._super.init.call(this, a, e, f, g, h),
                this.regionShapes = {},
                this.values = e = d.map(e, Number),
                this.min = b.min.apply(b, e),
                this.max = b.max.apply(b, e),
                this.range = this.max - this.min,
                this.width = g = "auto" === f.get("width") ? 2 * e.length: this.width,
                this.interval = b.floor(g / e.length),
                this.itemWidth = g / e.length,
                f.get("chartRangeMin") !== c && (f.get("chartRangeClip") || f.get("chartRangeMin") < this.min) && (this.min = f.get("chartRangeMin")),
                f.get("chartRangeMax") !== c && (f.get("chartRangeClip") || f.get("chartRangeMax") > this.max) && (this.max = f.get("chartRangeMax")),
                this.initTarget(),
                this.target && (this.lineHeight = "auto" === f.get("lineHeight") ? b.round(.3 * this.canvasHeight) : f.get("lineHeight"))
            },
            getRegion: function(a, c) {
                return b.floor(c / this.itemWidth)
            },
            getCurrentRegionFields: function() {
                var a = this.currentRegion;
                return {
                    isNull: this.values[a] === c,
                    value: this.values[a],
                    offset: a
                }
            },
            renderRegion: function(a, c) {
                var d, e, f, g, i = this.values,
                j = this.options,
                k = this.min,
                l = this.max,
                m = this.range,
                n = this.interval,
                o = this.target,
                p = this.canvasHeight,
                q = this.lineHeight,
                r = p - q;
                return e = h(i[a], k, l),
                g = a * n,
                d = b.round(r - r * ((e - k) / m)),
                f = j.get("thresholdColor") && e < j.get("thresholdValue") ? j.get("thresholdColor") : j.get("lineColor"),
                c && (f = this.calcHighlightColor(f, j)),
                o.drawLine(g, d, g, d + q, f)
            }
        }),
        d.fn.sparkline.bullet = A = f(d.fn.sparkline._base, {
            type: "bullet",
            init: function(a, d, e, f, g) {
                var h, i, j;
                A._super.init.call(this, a, d, e, f, g),
                this.values = d = k(d),
                j = d.slice(),
                j[0] = null === j[0] ? j[2] : j[0],
                j[1] = null === d[1] ? j[2] : j[1],
                h = b.min.apply(b, d),
                i = b.max.apply(b, d),
                h = e.get("base") === c ? 0 > h ? h: 0 : e.get("base"),
                this.min = h,
                this.max = i,
                this.range = i - h,
                this.shapes = {},
                this.valueShapes = {},
                this.regiondata = {},
                this.width = f = "auto" === e.get("width") ? "4.0em": f,
                this.target = this.$el.simpledraw(f, g, e.get("composite")),
                d.length || (this.disabled = !0),
                this.initTarget()
            },
            getRegion: function(a, b, d) {
                var e = this.target.getShapeAt(a, b, d);
                return e !== c && this.shapes[e] !== c ? this.shapes[e] : c
            },
            getCurrentRegionFields: function() {
                var a = this.currentRegion;
                return {
                    fieldkey: a.substr(0, 1),
                    value: this.values[a.substr(1)],
                    region: a
                }
            },
            changeHighlight: function(a) {
                var b, c = this.currentRegion,
                d = this.valueShapes[c];
                switch (delete this.shapes[d], c.substr(0, 1)) {
                case "r":
                    b = this.renderRange(c.substr(1), a);
                    break;
                case "p":
                    b = this.renderPerformance(a);
                    break;
                case "t":
                    b = this.renderTarget(a)
                }
                this.valueShapes[c] = b.id,
                this.shapes[b.id] = c,
                this.target.replaceWithShape(d, b)
            },
            renderRange: function(a, c) {
                var d = this.values[a],
                e = b.round(this.canvasWidth * ((d - this.min) / this.range)),
                f = this.options.get("rangeColors")[a - 2];
                return c && (f = this.calcHighlightColor(f, this.options)),
                this.target.drawRect(0, 0, e - 1, this.canvasHeight - 1, f, f)
            },
            renderPerformance: function(a) {
                var c = this.values[1],
                d = b.round(this.canvasWidth * ((c - this.min) / this.range)),
                e = this.options.get("performanceColor");
                return a && (e = this.calcHighlightColor(e, this.options)),
                this.target.drawRect(0, b.round(.3 * this.canvasHeight), d - 1, b.round(.4 * this.canvasHeight) - 1, e, e)
            },
            renderTarget: function(a) {
                var c = this.values[0],
                d = b.round(this.canvasWidth * ((c - this.min) / this.range) - this.options.get("targetWidth") / 2),
                e = b.round(.1 * this.canvasHeight),
                f = this.canvasHeight - 2 * e,
                g = this.options.get("targetColor");
                return a && (g = this.calcHighlightColor(g, this.options)),
                this.target.drawRect(d, e, this.options.get("targetWidth") - 1, f - 1, g, g)
            },
            render: function() {
                var a, b, c = this.values.length,
                d = this.target;
                if (A._super.render.call(this)) {
                    for (a = 2; c > a; a++) b = this.renderRange(a).append(),
                    this.shapes[b.id] = "r" + a,
                    this.valueShapes["r" + a] = b.id;
                    null !== this.values[1] && (b = this.renderPerformance().append(), this.shapes[b.id] = "p1", this.valueShapes.p1 = b.id),
                    null !== this.values[0] && (b = this.renderTarget().append(), this.shapes[b.id] = "t0", this.valueShapes.t0 = b.id),
                    d.render()
                }
            }
        }),
        d.fn.sparkline.pie = B = f(d.fn.sparkline._base, {
            type: "pie",
            init: function(a, c, e, f, g) {
                var h, i = 0;
                if (B._super.init.call(this, a, c, e, f, g), this.shapes = {},
                this.valueShapes = {},
                this.values = c = d.map(c, Number), "auto" === e.get("width") && (this.width = this.height), c.length > 0) for (h = c.length; h--;) i += c[h];
                this.total = i,
                this.initTarget(),
                this.radius = b.floor(b.min(this.canvasWidth, this.canvasHeight) / 2)
            },
            getRegion: function(a, b, d) {
                var e = this.target.getShapeAt(a, b, d);
                return e !== c && this.shapes[e] !== c ? this.shapes[e] : c
            },
            getCurrentRegionFields: function() {
                var a = this.currentRegion;
                return {
                    isNull: this.values[a] === c,
                    value: this.values[a],
                    percent: 100 * (this.values[a] / this.total),
                    color: this.options.get("sliceColors")[a % this.options.get("sliceColors").length],
                    offset: a
                }
            },
            changeHighlight: function(a) {
                var b = this.currentRegion,
                c = this.renderSlice(b, a),
                d = this.valueShapes[b];
                delete this.shapes[d],
                this.target.replaceWithShape(d, c),
                this.valueShapes[b] = c.id,
                this.shapes[c.id] = b
            },
            renderSlice: function(a, d) {
                var e, f, g, h, i, j = this.target,
                k = this.options,
                l = this.radius,
                m = k.get("borderWidth"),
                n = k.get("offset"),
                o = 2 * b.PI,
                p = this.values,
                q = this.total,
                r = n ? 2 * b.PI * (n / 360) : 0;
                for (h = p.length, g = 0; h > g; g++) {
                    if (e = r, f = r, q > 0 && (f = r + o * (p[g] / q)), a === g) return i = k.get("sliceColors")[g % k.get("sliceColors").length],
                    d && (i = this.calcHighlightColor(i, k)),
                    j.drawPieSlice(l, l, l - m, e, f, c, i);
                    r = f
                }
            },
            render: function() {
                var a, d, e = this.target,
                f = this.values,
                g = this.options,
                h = this.radius,
                i = g.get("borderWidth");
                if (B._super.render.call(this)) {
                    for (i && e.drawCircle(h, h, b.floor(h - i / 2), g.get("borderColor"), c, i).append(), d = f.length; d--;) f[d] && (a = this.renderSlice(d).append(), this.valueShapes[d] = a.id, this.shapes[a.id] = d);
                    e.render()
                }
            }
        }),
        d.fn.sparkline.box = C = f(d.fn.sparkline._base, {
            type: "box",
            init: function(a, b, c, e, f) {
                C._super.init.call(this, a, b, c, e, f),
                this.values = d.map(b, Number),
                this.width = "auto" === c.get("width") ? "4.0em": e,
                this.initTarget(),
                this.values.length || (this.disabled = 1)
            },
            getRegion: function() {
                return 1
            },
            getCurrentRegionFields: function() {
                var a = [{
                    field: "lq",
                    value: this.quartiles[0]
                },
                {
                    field: "med",
                    value: this.quartiles[1]
                },
                {
                    field: "uq",
                    value: this.quartiles[2]
                }];
                return this.loutlier !== c && a.push({
                    field: "lo",
                    value: this.loutlier
                }),
                this.routlier !== c && a.push({
                    field: "ro",
                    value: this.routlier
                }),
                this.lwhisker !== c && a.push({
                    field: "lw",
                    value: this.lwhisker
                }),
                this.rwhisker !== c && a.push({
                    field: "rw",
                    value: this.rwhisker
                }),
                a
            },
            render: function() {
                var a, d, e, f, g, h, j, k, l, m, n, o = this.target,
                p = this.values,
                q = p.length,
                r = this.options,
                s = this.canvasWidth,
                t = this.canvasHeight,
                u = r.get("chartRangeMin") === c ? b.min.apply(b, p) : r.get("chartRangeMin"),
                v = r.get("chartRangeMax") === c ? b.max.apply(b, p) : r.get("chartRangeMax"),
                w = 0;
                if (C._super.render.call(this)) {
                    if (r.get("raw")) r.get("showOutliers") && p.length > 5 ? (d = p[0], a = p[1], f = p[2], g = p[3], h = p[4], j = p[5], k = p[6]) : (a = p[0], f = p[1], g = p[2], h = p[3], j = p[4]);
                    else if (p.sort(function(a, b) {
                        return a - b
                    }), f = i(p, 1), g = i(p, 2), h = i(p, 3), e = h - f, r.get("showOutliers")) {
                        for (a = j = c, l = 0; q > l; l++) a === c && p[l] > f - e * r.get("outlierIQR") && (a = p[l]),
                        p[l] < h + e * r.get("outlierIQR") && (j = p[l]);
                        d = p[0],
                        k = p[q - 1]
                    } else a = p[0],
                    j = p[q - 1];
                    this.quartiles = [f, g, h],
                    this.lwhisker = a,
                    this.rwhisker = j,
                    this.loutlier = d,
                    this.routlier = k,
                    n = s / (v - u + 1),
                    r.get("showOutliers") && (w = b.ceil(r.get("spotRadius")), s -= 2 * b.ceil(r.get("spotRadius")), n = s / (v - u + 1), a > d && o.drawCircle((d - u) * n + w, t / 2, r.get("spotRadius"), r.get("outlierLineColor"), r.get("outlierFillColor")).append(), k > j && o.drawCircle((k - u) * n + w, t / 2, r.get("spotRadius"), r.get("outlierLineColor"), r.get("outlierFillColor")).append()),
                    o.drawRect(b.round((f - u) * n + w), b.round(.1 * t), b.round((h - f) * n), b.round(.8 * t), r.get("boxLineColor"), r.get("boxFillColor")).append(),
                    o.drawLine(b.round((a - u) * n + w), b.round(t / 2), b.round((f - u) * n + w), b.round(t / 2), r.get("lineColor")).append(),
                    o.drawLine(b.round((a - u) * n + w), b.round(t / 4), b.round((a - u) * n + w), b.round(t - t / 4), r.get("whiskerColor")).append(),
                    o.drawLine(b.round((j - u) * n + w), b.round(t / 2), b.round((h - u) * n + w), b.round(t / 2), r.get("lineColor")).append(),
                    o.drawLine(b.round((j - u) * n + w), b.round(t / 4), b.round((j - u) * n + w), b.round(t - t / 4), r.get("whiskerColor")).append(),
                    o.drawLine(b.round((g - u) * n + w), b.round(.1 * t), b.round((g - u) * n + w), b.round(.9 * t), r.get("medianColor")).append(),
                    r.get("target") && (m = b.ceil(r.get("spotRadius")), o.drawLine(b.round((r.get("target") - u) * n + w), b.round(t / 2 - m), b.round((r.get("target") - u) * n + w), b.round(t / 2 + m), r.get("targetColor")).append(), o.drawLine(b.round((r.get("target") - u) * n + w - m), b.round(t / 2), b.round((r.get("target") - u) * n + w + m), b.round(t / 2), r.get("targetColor")).append()),
                    o.render()
                }
            }
        }),
        F = f({
            init: function(a, b, c, d) {
                this.target = a,
                this.id = b,
                this.type = c,
                this.args = d
            },
            append: function() {
                return this.target.appendShape(this),
                this
            }
        }),
        G = f({
            _pxregex: /(\d+)(px)?\s*$/i,
            init: function(a, b, c) {
                a && (this.width = a, this.height = b, this.target = c, this.lastShapeId = null, c[0] && (c = c[0]), d.data(c, "_jqs_vcanvas", this))
            },
            drawLine: function(a, b, c, d, e, f) {
                return this.drawShape([[a, b], [c, d]], e, f)
            },
            drawShape: function(a, b, c, d) {
                return this._genShape("Shape", [a, b, c, d])
            },
            drawCircle: function(a, b, c, d, e, f) {
                return this._genShape("Circle", [a, b, c, d, e, f])
            },
            drawPieSlice: function(a, b, c, d, e, f, g) {
                return this._genShape("PieSlice", [a, b, c, d, e, f, g])
            },
            drawRect: function(a, b, c, d, e, f) {
                return this._genShape("Rect", [a, b, c, d, e, f])
            },
            getElement: function() {
                return this.canvas
            },
            getLastShapeId: function() {
                return this.lastShapeId
            },
            reset: function() {
                alert("reset not implemented")
            },
            _insert: function(a, b) {
                d(b).html(a)
            },
            _calculatePixelDims: function(a, b, c) {
                var e;
                e = this._pxregex.exec(b),
                this.pixelHeight = e ? e[1] : d(c).height(),
                e = this._pxregex.exec(a),
                this.pixelWidth = e ? e[1] : d(c).width()
            },
            _genShape: function(a, b) {
                var c = L++;
                return b.unshift(c),
                new F(this, c, a, b)
            },
            appendShape: function() {
                alert("appendShape not implemented")
            },
            replaceWithShape: function() {
                alert("replaceWithShape not implemented")
            },
            insertAfterShape: function() {
                alert("insertAfterShape not implemented")
            },
            removeShapeId: function() {
                alert("removeShapeId not implemented")
            },
            getShapeAt: function() {
                alert("getShapeAt not implemented")
            },
            render: function() {
                alert("render not implemented")
            }
        }),
        H = f(G, {
            init: function(b, e, f, g) {
                H._super.init.call(this, b, e, f),
                this.canvas = a.createElement("canvas"),
                f[0] && (f = f[0]),
                d.data(f, "_jqs_vcanvas", this),
                d(this.canvas).css({
                    display: "inline-block",
                    width: b,
                    height: e,
                    verticalAlign: "top"
                }),
                this._insert(this.canvas, f),
                this._calculatePixelDims(b, e, this.canvas),
                this.canvas.width = this.pixelWidth,
                this.canvas.height = this.pixelHeight,
                this.interact = g,
                this.shapes = {},
                this.shapeseq = [],
                this.currentTargetShapeId = c,
                d(this.canvas).css({
                    width: this.pixelWidth,
                    height: this.pixelHeight
                })
            },
            _getContext: function(a, b, d) {
                var e = this.canvas.getContext("2d");
                return a !== c && (e.strokeStyle = a),
                e.lineWidth = d === c ? 1 : d,
                b !== c && (e.fillStyle = b),
                e
            },
            reset: function() {
                var a = this._getContext();
                a.clearRect(0, 0, this.pixelWidth, this.pixelHeight),
                this.shapes = {},
                this.shapeseq = [],
                this.currentTargetShapeId = c
            },
            _drawShape: function(a, b, d, e, f) {
                var g, h, i = this._getContext(d, e, f);
                for (i.beginPath(), i.moveTo(b[0][0] + .5, b[0][1] + .5), g = 1, h = b.length; h > g; g++) i.lineTo(b[g][0] + .5, b[g][1] + .5);
                d !== c && i.stroke(),
                e !== c && i.fill(),
                this.targetX !== c && this.targetY !== c && i.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
            },
            _drawCircle: function(a, d, e, f, g, h, i) {
                var j = this._getContext(g, h, i);
                j.beginPath(),
                j.arc(d, e, f, 0, 2 * b.PI, !1),
                this.targetX !== c && this.targetY !== c && j.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a),
                g !== c && j.stroke(),
                h !== c && j.fill()
            },
            _drawPieSlice: function(a, b, d, e, f, g, h, i) {
                var j = this._getContext(h, i);
                j.beginPath(),
                j.moveTo(b, d),
                j.arc(b, d, e, f, g, !1),
                j.lineTo(b, d),
                j.closePath(),
                h !== c && j.stroke(),
                i && j.fill(),
                this.targetX !== c && this.targetY !== c && j.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
            },
            _drawRect: function(a, b, c, d, e, f, g) {
                return this._drawShape(a, [[b, c], [b + d, c], [b + d, c + e], [b, c + e], [b, c]], f, g)
            },
            appendShape: function(a) {
                return this.shapes[a.id] = a,
                this.shapeseq.push(a.id),
                this.lastShapeId = a.id,
                a.id
            },
            replaceWithShape: function(a, b) {
                var c, d = this.shapeseq;
                for (this.shapes[b.id] = b, c = d.length; c--;) d[c] == a && (d[c] = b.id);
                delete this.shapes[a]
            },
            replaceWithShapes: function(a, b) {
                var c, d, e, f = this.shapeseq,
                g = {};
                for (d = a.length; d--;) g[a[d]] = !0;
                for (d = f.length; d--;) c = f[d],
                g[c] && (f.splice(d, 1), delete this.shapes[c], e = d);
                for (d = b.length; d--;) f.splice(e, 0, b[d].id),
                this.shapes[b[d].id] = b[d]
            },
            insertAfterShape: function(a, b) {
                var c, d = this.shapeseq;
                for (c = d.length; c--;) if (d[c] === a) return d.splice(c + 1, 0, b.id),
                this.shapes[b.id] = b,
                void 0
            },
            removeShapeId: function(a) {
                var b, c = this.shapeseq;
                for (b = c.length; b--;) if (c[b] === a) {
                    c.splice(b, 1);
                    break
                }
                delete this.shapes[a]
            },
            getShapeAt: function(a, b, c) {
                return this.targetX = b,
                this.targetY = c,
                this.render(),
                this.currentTargetShapeId
            },
            render: function() {
                var a, b, c, d = this.shapeseq,
                e = this.shapes,
                f = d.length,
                g = this._getContext();
                for (g.clearRect(0, 0, this.pixelWidth, this.pixelHeight), c = 0; f > c; c++) a = d[c],
                b = e[a],
                this["_draw" + b.type].apply(this, b.args);
                this.interact || (this.shapes = {},
                this.shapeseq = [])
            }
        }),
        I = f(G, {
            init: function(b, c, e) {
                var f;
                I._super.init.call(this, b, c, e),
                e[0] && (e = e[0]),
                d.data(e, "_jqs_vcanvas", this),
                this.canvas = a.createElement("span"),
                d(this.canvas).css({
                    display: "inline-block",
                    position: "relative",
                    overflow: "hidden",
                    width: b,
                    height: c,
                    margin: "0px",
                    padding: "0px",
                    verticalAlign: "top"
                }),
                this._insert(this.canvas, e),
                this._calculatePixelDims(b, c, this.canvas),
                this.canvas.width = this.pixelWidth,
                this.canvas.height = this.pixelHeight,
                f = '<v:group coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '"' + ' style="position:absolute;top:0;left:0;width:' + this.pixelWidth + "px;height=" + this.pixelHeight + 'px;"></v:group>',
                this.canvas.insertAdjacentHTML("beforeEnd", f),
                this.group = d(this.canvas).children()[0],
                this.rendered = !1,
                this.prerender = ""
            },
            _drawShape: function(a, b, d, e, f) {
                var g, h, i, j, k, l, m, n = [];
                for (m = 0, l = b.length; l > m; m++) n[m] = "" + b[m][0] + "," + b[m][1];
                return g = n.splice(0, 1),
                f = f === c ? 1 : f,
                h = d === c ? ' stroked="false" ': ' strokeWeight="' + f + 'px" strokeColor="' + d + '" ',
                i = e === c ? ' filled="false"': ' fillColor="' + e + '" filled="true" ',
                j = n[0] === n[n.length - 1] ? "x ": "",
                k = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '" ' + ' id="jqsshape' + a + '" ' + h + i + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;" ' + ' path="m ' + g + " l " + n.join(", ") + " " + j + 'e">' + " </v:shape>"
            },
            _drawCircle: function(a, b, d, e, f, g, h) {
                var i, j, k;
                return b -= e,
                d -= e,
                i = f === c ? ' stroked="false" ': ' strokeWeight="' + h + 'px" strokeColor="' + f + '" ',
                j = g === c ? ' filled="false"': ' fillColor="' + g + '" filled="true" ',
                k = '<v:oval  id="jqsshape' + a + '" ' + i + j + ' style="position:absolute;top:' + d + "px; left:" + b + "px; width:" + 2 * e + "px; height:" + 2 * e + 'px"></v:oval>'
            },
            _drawPieSlice: function(a, d, e, f, g, h, i, j) {
                var k, l, m, n, o, p, q, r;
                if (g === h) return "";
                if (h - g === 2 * b.PI && (g = 0, h = 2 * b.PI), l = d + b.round(b.cos(g) * f), m = e + b.round(b.sin(g) * f), n = d + b.round(b.cos(h) * f), o = e + b.round(b.sin(h) * f), l === n && m === o) {
                    if (h - g < b.PI) return "";
                    l = n = d + f,
                    m = o = e
                }
                return l === n && m === o && h - g < b.PI ? "": (k = [d - f, e - f, d + f, e + f, l, m, n, o], p = i === c ? ' stroked="false" ': ' strokeWeight="1px" strokeColor="' + i + '" ', q = j === c ? ' filled="false"': ' fillColor="' + j + '" filled="true" ', r = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '" ' + ' id="jqsshape' + a + '" ' + p + q + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;" ' + ' path="m ' + d + "," + e + " wa " + k.join(", ") + ' x e">' + " </v:shape>")
            },
            _drawRect: function(a, b, c, d, e, f, g) {
                return this._drawShape(a, [[b, c], [b, c + e], [b + d, c + e], [b + d, c], [b, c]], f, g)
            },
            reset: function() {
                this.group.innerHTML = ""
            },
            appendShape: function(a) {
                var b = this["_draw" + a.type].apply(this, a.args);
                return this.rendered ? this.group.insertAdjacentHTML("beforeEnd", b) : this.prerender += b,
                this.lastShapeId = a.id,
                a.id
            },
            replaceWithShape: function(a, b) {
                var c = d("#jqsshape" + a),
                e = this["_draw" + b.type].apply(this, b.args);
                c[0].outerHTML = e
            },
            replaceWithShapes: function(a, b) {
                var c, e = d("#jqsshape" + a[0]),
                f = "",
                g = b.length;
                for (c = 0; g > c; c++) f += this["_draw" + b[c].type].apply(this, b[c].args);
                for (e[0].outerHTML = f, c = 1; c < a.length; c++) d("#jqsshape" + a[c]).remove()
            },
            insertAfterShape: function(a, b) {
                var c = d("#jqsshape" + a),
                e = this["_draw" + b.type].apply(this, b.args);
                c[0].insertAdjacentHTML("afterEnd", e)
            },
            removeShapeId: function(a) {
                var b = d("#jqsshape" + a);
                this.group.removeChild(b[0])
            },
            getShapeAt: function(a) {
                var b = a.id.substr(8);
                return b
            },
            render: function() {
                this.rendered || (this.group.innerHTML = this.prerender, this.rendered = !0)
            }
        })
    })
} (document, Math),
function(a) {
    var b = 0,
    c = {},
    d = {};
    c.height = c.paddingTop = c.paddingBottom = c.borderTopWidth = c.borderBottomWidth = "hide",
    d.height = d.paddingTop = d.paddingBottom = d.borderTopWidth = d.borderBottomWidth = "show",
    a.widget("ui.accordion", {
        version: "1.10.3",
        options: {
            active: 0,
            animate: {},
            collapsible: !1,
            event: "click",
            header: "> li > :first-child,> :not(li):even",
            heightStyle: "auto",
            icons: {
                activeHeader: "ui-icon-triangle-1-s",
                header: "ui-icon-triangle-1-e"
            },
            activate: null,
            beforeActivate: null
        },
        _create: function() {
            var b = this.options;
            this.prevShow = this.prevHide = a(),
            this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role", "tablist"),
            b.collapsible || b.active !== !1 && null != b.active || (b.active = 0),
            this._processPanels(),
            b.active < 0 && (b.active += this.headers.length),
            this._refresh()
        },
        _getCreateEventData: function() {
            return {
                header: this.active,
                panel: this.active.length ? this.active.next() : a(),
                content: this.active.length ? this.active.next() : a()
            }
        },
        _createIcons: function() {
            var b = this.options.icons;
            b && (a("<span>").addClass("ui-accordion-header-icon ui-icon " + b.header).prependTo(this.headers), this.active.children(".ui-accordion-header-icon").removeClass(b.header).addClass(b.activeHeader), this.headers.addClass("ui-accordion-icons"))
        },
        _destroyIcons: function() {
            this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()
        },
        _destroy: function() {
            var a;
            this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"),
            this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function() { / ^ui - accordion / .test(this.id) && this.removeAttribute("id")
            }),
            this._destroyIcons(),
            a = this.headers.next().css("display", "").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function() { / ^ui - accordion / .test(this.id) && this.removeAttribute("id")
            }),
            "content" !== this.options.heightStyle && a.css("height", "")
        },
        _setOption: function(a, b) {
            return "active" === a ? (this._activate(b), void 0) : ("event" === a && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(b)), this._super(a, b), "collapsible" !== a || b || this.options.active !== !1 || this._activate(0), "icons" === a && (this._destroyIcons(), b && this._createIcons()), "disabled" === a && this.headers.add(this.headers.next()).toggleClass("ui-state-disabled", !!b), void 0)
        },
        _keydown: function(b) {
            if (!b.altKey && !b.ctrlKey) {
                var c = a.ui.keyCode,
                d = this.headers.length,
                e = this.headers.index(b.target),
                f = !1;
                switch (b.keyCode) {
                case c.RIGHT:
                case c.DOWN:
                    f = this.headers[(e + 1) % d];
                    break;
                case c.LEFT:
                case c.UP:
                    f = this.headers[(e - 1 + d) % d];
                    break;
                case c.SPACE:
                case c.ENTER:
                    this._eventHandler(b);
                    break;
                case c.HOME:
                    f = this.headers[0];
                    break;
                case c.END:
                    f = this.headers[d - 1]
                }
                f && (a(b.target).attr("tabIndex", -1), a(f).attr("tabIndex", 0), f.focus(), b.preventDefault())
            }
        },
        _panelKeyDown: function(b) {
            b.keyCode === a.ui.keyCode.UP && b.ctrlKey && a(b.currentTarget).prev().focus()
        },
        refresh: function() {
            var b = this.options;
            this._processPanels(),
            b.active === !1 && b.collapsible === !0 || !this.headers.length ? (b.active = !1, this.active = a()) : b.active === !1 ? this._activate(0) : this.active.length && !a.contains(this.element[0], this.active[0]) ? this.headers.length === this.headers.find(".ui-state-disabled").length ? (b.active = !1, this.active = a()) : this._activate(Math.max(0, b.active - 1)) : b.active = this.headers.index(this.active),
            this._destroyIcons(),
            this._refresh()
        },
        _processPanels: function() {
            this.headers = this.element.find(this.options.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"),
            this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide()
        },
        _refresh: function() {
            var c, d = this.options,
            e = d.heightStyle,
            f = this.element.parent(),
            g = this.accordionId = "ui-accordion-" + (this.element.attr("id") || ++b);
            this.active = this._findActive(d.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"),
            this.active.next().addClass("ui-accordion-content-active").show(),
            this.headers.attr("role", "tab").each(function(b) {
                var c = a(this),
                d = c.attr("id"),
                e = c.next(),
                f = e.attr("id");
                d || (d = g + "-header-" + b, c.attr("id", d)),
                f || (f = g + "-panel-" + b, e.attr("id", f)),
                c.attr("aria-controls", f),
                e.attr("aria-labelledby", d)
            }).next().attr("role", "tabpanel"),
            this.headers.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }).next().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }).hide(),
            this.active.length ? this.active.attr({
                "aria-selected": "true",
                tabIndex: 0
            }).next().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }) : this.headers.eq(0).attr("tabIndex", 0),
            this._createIcons(),
            this._setupEvents(d.event),
            "fill" === e ? (c = f.height(), this.element.siblings(":visible").each(function() {
                var b = a(this),
                d = b.css("position");
                "absolute" !== d && "fixed" !== d && (c -= b.outerHeight(!0))
            }), this.headers.each(function() {
                c -= a(this).outerHeight(!0)
            }), this.headers.next().each(function() {
                a(this).height(Math.max(0, c - a(this).innerHeight() + a(this).height()))
            }).css("overflow", "auto")) : "auto" === e && (c = 0, this.headers.next().each(function() {
                c = Math.max(c, a(this).css("height", "").height())
            }).height(c))
        },
        _activate: function(b) {
            var c = this._findActive(b)[0];
            c !== this.active[0] && (c = c || this.active[0], this._eventHandler({
                target: c,
                currentTarget: c,
                preventDefault: a.noop
            }))
        },
        _findActive: function(b) {
            return "number" == typeof b ? this.headers.eq(b) : a()
        },
        _setupEvents: function(b) {
            var c = {
                keydown: "_keydown"
            };
            b && a.each(b.split(" "),
            function(a, b) {
                c[b] = "_eventHandler"
            }),
            this._off(this.headers.add(this.headers.next())),
            this._on(this.headers, c),
            this._on(this.headers.next(), {
                keydown: "_panelKeyDown"
            }),
            this._hoverable(this.headers),
            this._focusable(this.headers)
        },
        _eventHandler: function(b) {
            var c = this.options,
            d = this.active,
            e = a(b.currentTarget),
            f = e[0] === d[0],
            g = f && c.collapsible,
            h = g ? a() : e.next(),
            i = d.next(),
            j = {
                oldHeader: d,
                oldPanel: i,
                newHeader: g ? a() : e,
                newPanel: h
            };
            b.preventDefault(),
            f && !c.collapsible || this._trigger("beforeActivate", b, j) === !1 || (c.active = g ? !1 : this.headers.index(e), this.active = f ? a() : e, this._toggle(j), d.removeClass("ui-accordion-header-active ui-state-active"), c.icons && d.children(".ui-accordion-header-icon").removeClass(c.icons.activeHeader).addClass(c.icons.header), f || (e.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"), c.icons && e.children(".ui-accordion-header-icon").removeClass(c.icons.header).addClass(c.icons.activeHeader), e.next().addClass("ui-accordion-content-active")))
        },
        _toggle: function(b) {
            var c = b.newPanel,
            d = this.prevShow.length ? this.prevShow: b.oldPanel;
            this.prevShow.add(this.prevHide).stop(!0, !0),
            this.prevShow = c,
            this.prevHide = d,
            this.options.animate ? this._animate(c, d, b) : (d.hide(), c.show(), this._toggleComplete(b)),
            d.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            d.prev().attr("aria-selected", "false"),
            c.length && d.length ? d.prev().attr("tabIndex", -1) : c.length && this.headers.filter(function() {
                return 0 === a(this).attr("tabIndex")
            }).attr("tabIndex", -1),
            c.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }).prev().attr({
                "aria-selected": "true",
                tabIndex: 0
            })
        },
        _animate: function(a, b, e) {
            var f, g, h, i = this,
            j = 0,
            k = a.length && (!b.length || a.index() < b.index()),
            l = this.options.animate || {},
            m = k && l.down || l,
            n = function() {
                i._toggleComplete(e)
            };
            return "number" == typeof m && (h = m),
            "string" == typeof m && (g = m),
            g = g || m.easing || l.easing,
            h = h || m.duration || l.duration,
            b.length ? a.length ? (f = a.show().outerHeight(), b.animate(c, {
                duration: h,
                easing: g,
                step: function(a, b) {
                    b.now = Math.round(a)
                }
            }), a.hide().animate(d, {
                duration: h,
                easing: g,
                complete: n,
                step: function(a, c) {
                    c.now = Math.round(a),
                    "height" !== c.prop ? j += c.now: "content" !== i.options.heightStyle && (c.now = Math.round(f - b.outerHeight() - j), j = 0)
                }
            }), void 0) : b.animate(c, h, g, n) : a.animate(d, h, g, n)
        },
        _toggleComplete: function(a) {
            var b = a.oldPanel;
            b.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"),
            b.length && (b.parent()[0].className = b.parent()[0].className),
            this._trigger("activate", null, a)
        }
    })
} (jQuery),
function(a) {
    var b = 0;
    a.widget("ui.autocomplete", {
        version: "1.10.3",
        defaultElement: "<input>",
        options: {
            appendTo: null,
            autoFocus: !1,
            delay: 300,
            minLength: 1,
            position: {
                my: "left top",
                at: "left bottom",
                collision: "none"
            },
            source: null,
            change: null,
            close: null,
            focus: null,
            open: null,
            response: null,
            search: null,
            select: null
        },
        pending: 0,
        _create: function() {
            var b, c, d, e = this.element[0].nodeName.toLowerCase(),
            f = "textarea" === e,
            g = "input" === e;
            this.isMultiLine = f ? !0 : g ? !1 : this.element.prop("isContentEditable"),
            this.valueMethod = this.element[f || g ? "val": "text"],
            this.isNewMenu = !0,
            this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off"),
            this._on(this.element, {
                keydown: function(e) {
                    if (this.element.prop("readOnly")) return b = !0,
                    d = !0,
                    c = !0,
                    void 0;
                    b = !1,
                    d = !1,
                    c = !1;
                    var f = a.ui.keyCode;
                    switch (e.keyCode) {
                    case f.PAGE_UP:
                        b = !0,
                        this._move("previousPage", e);
                        break;
                    case f.PAGE_DOWN:
                        b = !0,
                        this._move("nextPage", e);
                        break;
                    case f.UP:
                        b = !0,
                        this._keyEvent("previous", e);
                        break;
                    case f.DOWN:
                        b = !0,
                        this._keyEvent("next", e);
                        break;
                    case f.ENTER:
                    case f.NUMPAD_ENTER:
                        this.menu.active && (b = !0, e.preventDefault(), this.menu.select(e));
                        break;
                    case f.TAB:
                        this.menu.active && this.menu.select(e);
                        break;
                    case f.ESCAPE:
                        this.menu.element.is(":visible") && (this._value(this.term), this.close(e), e.preventDefault());
                        break;
                    default:
                        c = !0,
                        this._searchTimeout(e)
                    }
                },
                keypress: function(d) {
                    if (b) return b = !1,
                    (!this.isMultiLine || this.menu.element.is(":visible")) && d.preventDefault(),
                    void 0;
                    if (!c) {
                        var e = a.ui.keyCode;
                        switch (d.keyCode) {
                        case e.PAGE_UP:
                            this._move("previousPage", d);
                            break;
                        case e.PAGE_DOWN:
                            this._move("nextPage", d);
                            break;
                        case e.UP:
                            this._keyEvent("previous", d);
                            break;
                        case e.DOWN:
                            this._keyEvent("next", d)
                        }
                    }
                },
                input: function(a) {
                    return d ? (d = !1, a.preventDefault(), void 0) : (this._searchTimeout(a), void 0)
                },
                focus: function() {
                    this.selectedItem = null,
                    this.previous = this._value()
                },
                blur: function(a) {
                    return this.cancelBlur ? (delete this.cancelBlur, void 0) : (clearTimeout(this.searching), this.close(a), this._change(a), void 0)
                }
            }),
            this._initSource(),
            this.menu = a("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({
                role: null
            }).hide().data("ui-menu"),
            this._on(this.menu.element, {
                mousedown: function(b) {
                    b.preventDefault(),
                    this.cancelBlur = !0,
                    this._delay(function() {
                        delete this.cancelBlur
                    });
                    var c = this.menu.element[0];
                    a(b.target).closest(".ui-menu-item").length || this._delay(function() {
                        var b = this;
                        this.document.one("mousedown",
                        function(d) {
                            d.target === b.element[0] || d.target === c || a.contains(c, d.target) || b.close()
                        })
                    })
                },
                menufocus: function(b, c) {
                    if (this.isNewMenu && (this.isNewMenu = !1, b.originalEvent && /^mouse/.test(b.originalEvent.type))) return this.menu.blur(),
                    this.document.one("mousemove",
                    function() {
                        a(b.target).trigger(b.originalEvent)
                    }),
                    void 0;
                    var d = c.item.data("ui-autocomplete-item"); ! 1 !== this._trigger("focus", b, {
                        item: d
                    }) ? b.originalEvent && /^key/.test(b.originalEvent.type) && this._value(d.value) : this.liveRegion.text(d.value)
                },
                menuselect: function(a, b) {
                    var c = b.item.data("ui-autocomplete-item"),
                    d = this.previous;
                    this.element[0] !== this.document[0].activeElement && (this.element.focus(), this.previous = d, this._delay(function() {
                        this.previous = d,
                        this.selectedItem = c
                    })),
                    !1 !== this._trigger("select", a, {
                        item: c
                    }) && this._value(c.value),
                    this.term = this._value(),
                    this.close(a),
                    this.selectedItem = c
                }
            }),
            this.liveRegion = a("<span>", {
                role: "status",
                "aria-live": "polite"
            }).addClass("ui-helper-hidden-accessible").insertBefore(this.element),
            this._on(this.window, {
                beforeunload: function() {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _destroy: function() {
            clearTimeout(this.searching),
            this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"),
            this.menu.element.remove(),
            this.liveRegion.remove()
        },
        _setOption: function(a, b) {
            this._super(a, b),
            "source" === a && this._initSource(),
            "appendTo" === a && this.menu.element.appendTo(this._appendTo()),
            "disabled" === a && b && this.xhr && this.xhr.abort()
        },
        _appendTo: function() {
            var b = this.options.appendTo;
            return b && (b = b.jquery || b.nodeType ? a(b) : this.document.find(b).eq(0)),
            b || (b = this.element.closest(".ui-front")),
            b.length || (b = this.document[0].body),
            b
        },
        _initSource: function() {
            var b, c, d = this;
            a.isArray(this.options.source) ? (b = this.options.source, this.source = function(c, d) {
                d(a.ui.autocomplete.filter(b, c.term))
            }) : "string" == typeof this.options.source ? (c = this.options.source, this.source = function(b, e) {
                d.xhr && d.xhr.abort(),
                d.xhr = a.ajax({
                    url: c,
                    data: b,
                    dataType: "json",
                    success: function(a) {
                        e(a)
                    },
                    error: function() {
                        e([])
                    }
                })
            }) : this.source = this.options.source
        },
        _searchTimeout: function(a) {
            clearTimeout(this.searching),
            this.searching = this._delay(function() {
                this.term !== this._value() && (this.selectedItem = null, this.search(null, a))
            },
            this.options.delay)
        },
        search: function(a, b) {
            return a = null != a ? a: this._value(),
            this.term = this._value(),
            a.length < this.options.minLength ? this.close(b) : this._trigger("search", b) !== !1 ? this._search(a) : void 0
        },
        _search: function(a) {
            this.pending++,
            this.element.addClass("ui-autocomplete-loading"),
            this.cancelSearch = !1,
            this.source({
                term: a
            },
            this._response())
        },
        _response: function() {
            var a = this,
            c = ++b;
            return function(d) {
                c === b && a.__response(d),
                a.pending--,
                a.pending || a.element.removeClass("ui-autocomplete-loading")
            }
        },
        __response: function(a) {
            a && (a = this._normalize(a)),
            this._trigger("response", null, {
                content: a
            }),
            !this.options.disabled && a && a.length && !this.cancelSearch ? (this._suggest(a), this._trigger("open")) : this._close()
        },
        close: function(a) {
            this.cancelSearch = !0,
            this._close(a)
        },
        _close: function(a) {
            this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), this.isNewMenu = !0, this._trigger("close", a))
        },
        _change: function(a) {
            this.previous !== this._value() && this._trigger("change", a, {
                item: this.selectedItem
            })
        },
        _normalize: function(b) {
            return b.length && b[0].label && b[0].value ? b: a.map(b,
            function(b) {
                return "string" == typeof b ? {
                    label: b,
                    value: b
                }: a.extend({
                    label: b.label || b.value,
                    value: b.value || b.label
                },
                b)
            })
        },
        _suggest: function(b) {
            var c = this.menu.element.empty();
            this._renderMenu(c, b),
            this.isNewMenu = !0,
            this.menu.refresh(),
            c.show(),
            this._resizeMenu(),
            c.position(a.extend({
                of: this.element
            },
            this.options.position)),
            this.options.autoFocus && this.menu.next()
        },
        _resizeMenu: function() {
            var a = this.menu.element;
            a.outerWidth(Math.max(a.width("").outerWidth() + 1, this.element.outerWidth()))
        },
        _renderMenu: function(b, c) {
            var d = this;
            a.each(c,
            function(a, c) {
                d._renderItemData(b, c)
            })
        },
        _renderItemData: function(a, b) {
            return this._renderItem(a, b).data("ui-autocomplete-item", b)
        },
        _renderItem: function(b, c) {
            return a("<li>").append(a("<a>").text(c.label)).appendTo(b)
        },
        _move: function(a, b) {
            return this.menu.element.is(":visible") ? this.menu.isFirstItem() && /^previous/.test(a) || this.menu.isLastItem() && /^next/.test(a) ? (this._value(this.term), this.menu.blur(), void 0) : (this.menu[a](b), void 0) : (this.search(null, b), void 0)
        },
        widget: function() {
            return this.menu.element
        },
        _value: function() {
            return this.valueMethod.apply(this.element, arguments)
        },
        _keyEvent: function(a, b) { (!this.isMultiLine || this.menu.element.is(":visible")) && (this._move(a, b), b.preventDefault())
        }
    }),
    a.extend(a.ui.autocomplete, {
        escapeRegex: function(a) {
            return a.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
        },
        filter: function(b, c) {
            var d = new RegExp(a.ui.autocomplete.escapeRegex(c), "i");
            return a.grep(b,
            function(a) {
                return d.test(a.label || a.value || a)
            })
        }
    }),
    a.widget("ui.autocomplete", a.ui.autocomplete, {
        options: {
            messages: {
                noResults: "No search results.",
                results: function(a) {
                    return a + (a > 1 ? " results are": " result is") + " available, use up and down arrow keys to navigate."
                }
            }
        },
        __response: function(a) {
            var b;
            this._superApply(arguments),
            this.options.disabled || this.cancelSearch || (b = a && a.length ? this.options.messages.results(a.length) : this.options.messages.noResults, this.liveRegion.text(b))
        }
    })
} (jQuery),
function(a) {
    var b, c, d, e, f = "ui-button ui-widget ui-state-default ui-corner-all",
    g = "ui-state-hover ui-state-active ",
    h = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",
    i = function() {
        var b = a(this);
        setTimeout(function() {
            b.find(":ui-button").button("refresh")
        },
        1)
    },
    j = function(b) {
        var c = b.name,
        d = b.form,
        e = a([]);
        return c && (c = c.replace(/'/g, "\\'"), e = d ? a(d).find("[name='" + c + "']") : a("[name='" + c + "']", b.ownerDocument).filter(function() {
            return ! this.form
        })),
        e
    };
    a.widget("ui.button", {
        version: "1.10.3",
        defaultElement: "<button>",
        options: {
            disabled: null,
            text: !0,
            label: null,
            icons: {
                primary: null,
                secondary: null
            }
        },
        _create: function() {
            this.element.closest("form").unbind("reset" + this.eventNamespace).bind("reset" + this.eventNamespace, i),
            "boolean" != typeof this.options.disabled ? this.options.disabled = !!this.element.prop("disabled") : this.element.prop("disabled", this.options.disabled),
            this._determineButtonType(),
            this.hasTitle = !!this.buttonElement.attr("title");
            var g = this,
            h = this.options,
            k = "checkbox" === this.type || "radio" === this.type,
            l = k ? "": "ui-state-active",
            m = "ui-state-focus";
            null === h.label && (h.label = "input" === this.type ? this.buttonElement.val() : this.buttonElement.html()),
            this._hoverable(this.buttonElement),
            this.buttonElement.addClass(f).attr("role", "button").bind("mouseenter" + this.eventNamespace,
            function() {
                h.disabled || this === b && a(this).addClass("ui-state-active")
            }).bind("mouseleave" + this.eventNamespace,
            function() {
                h.disabled || a(this).removeClass(l)
            }).bind("click" + this.eventNamespace,
            function(a) {
                h.disabled && (a.preventDefault(), a.stopImmediatePropagation())
            }),
            this.element.bind("focus" + this.eventNamespace,
            function() {
                g.buttonElement.addClass(m)
            }).bind("blur" + this.eventNamespace,
            function() {
                g.buttonElement.removeClass(m)
            }),
            k && (this.element.bind("change" + this.eventNamespace,
            function() {
                e || g.refresh()
            }), this.buttonElement.bind("mousedown" + this.eventNamespace,
            function(a) {
                h.disabled || (e = !1, c = a.pageX, d = a.pageY)
            }).bind("mouseup" + this.eventNamespace,
            function(a) {
                h.disabled || (c !== a.pageX || d !== a.pageY) && (e = !0)
            })),
            "checkbox" === this.type ? this.buttonElement.bind("click" + this.eventNamespace,
            function() {
                return h.disabled || e ? !1 : void 0
            }) : "radio" === this.type ? this.buttonElement.bind("click" + this.eventNamespace,
            function() {
                if (h.disabled || e) return ! 1;
                a(this).addClass("ui-state-active"),
                g.buttonElement.attr("aria-pressed", "true");
                var b = g.element[0];
                j(b).not(b).map(function() {
                    return a(this).button("widget")[0]
                }).removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : (this.buttonElement.bind("mousedown" + this.eventNamespace,
            function() {
                return h.disabled ? !1 : (a(this).addClass("ui-state-active"), b = this, g.document.one("mouseup",
                function() {
                    b = null
                }), void 0)
            }).bind("mouseup" + this.eventNamespace,
            function() {
                return h.disabled ? !1 : (a(this).removeClass("ui-state-active"), void 0)
            }).bind("keydown" + this.eventNamespace,
            function(b) {
                return h.disabled ? !1 : ((b.keyCode === a.ui.keyCode.SPACE || b.keyCode === a.ui.keyCode.ENTER) && a(this).addClass("ui-state-active"), void 0)
            }).bind("keyup" + this.eventNamespace + " blur" + this.eventNamespace,
            function() {
                a(this).removeClass("ui-state-active")
            }), this.buttonElement.is("a") && this.buttonElement.keyup(function(b) {
                b.keyCode === a.ui.keyCode.SPACE && a(this).click()
            })),
            this._setOption("disabled", h.disabled),
            this._resetButton()
        },
        _determineButtonType: function() {
            var a, b, c;
            this.type = this.element.is("[type=checkbox]") ? "checkbox": this.element.is("[type=radio]") ? "radio": this.element.is("input") ? "input": "button",
            "checkbox" === this.type || "radio" === this.type ? (a = this.element.parents().last(), b = "label[for='" + this.element.attr("id") + "']", this.buttonElement = a.find(b), this.buttonElement.length || (a = a.length ? a.siblings() : this.element.siblings(), this.buttonElement = a.filter(b), this.buttonElement.length || (this.buttonElement = a.find(b))), this.element.addClass("ui-helper-hidden-accessible"), c = this.element.is(":checked"), c && this.buttonElement.addClass("ui-state-active"), this.buttonElement.prop("aria-pressed", c)) : this.buttonElement = this.element
        },
        widget: function() {
            return this.buttonElement
        },
        _destroy: function() {
            this.element.removeClass("ui-helper-hidden-accessible"),
            this.buttonElement.removeClass(f + " " + g + " " + h).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()),
            this.hasTitle || this.buttonElement.removeAttr("title")
        },
        _setOption: function(a, b) {
            return this._super(a, b),
            "disabled" === a ? (b ? this.element.prop("disabled", !0) : this.element.prop("disabled", !1), void 0) : (this._resetButton(), void 0)
        },
        refresh: function() {
            var b = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");
            b !== this.options.disabled && this._setOption("disabled", b),
            "radio" === this.type ? j(this.element[0]).each(function() {
                a(this).is(":checked") ? a(this).button("widget").addClass("ui-state-active").attr("aria-pressed", "true") : a(this).button("widget").removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : "checkbox" === this.type && (this.element.is(":checked") ? this.buttonElement.addClass("ui-state-active").attr("aria-pressed", "true") : this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", "false"))
        },
        _resetButton: function() {
            if ("input" === this.type) return this.options.label && this.element.val(this.options.label),
            void 0;
            var b = this.buttonElement.removeClass(h),
            c = a("<span></span>", this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(b.empty()).text(),
            d = this.options.icons,
            e = d.primary && d.secondary,
            f = [];
            d.primary || d.secondary ? (this.options.text && f.push("ui-button-text-icon" + (e ? "s": d.primary ? "-primary": "-secondary")), d.primary && b.prepend("<span class='ui-button-icon-primary ui-icon " + d.primary + "'></span>"), d.secondary && b.append("<span class='ui-button-icon-secondary ui-icon " + d.secondary + "'></span>"), this.options.text || (f.push(e ? "ui-button-icons-only": "ui-button-icon-only"), this.hasTitle || b.attr("title", a.trim(c)))) : f.push("ui-button-text-only"),
            b.addClass(f.join(" "))
        }
    }),
    a.widget("ui.buttonset", {
        version: "1.10.3",
        options: {
            items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"
        },
        _create: function() {
            this.element.addClass("ui-buttonset")
        },
        _init: function() {
            this.refresh()
        },
        _setOption: function(a, b) {
            "disabled" === a && this.buttons.button("option", a, b),
            this._super(a, b)
        },
        refresh: function() {
            var b = "rtl" === this.element.css("direction");
            this.buttons = this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function() {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(b ? "ui-corner-right": "ui-corner-left").end().filter(":last").addClass(b ? "ui-corner-left": "ui-corner-right").end().end()
        },
        _destroy: function() {
            this.element.removeClass("ui-buttonset"),
            this.buttons.map(function() {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-left ui-corner-right").end().button("destroy")
        }
    })
} (jQuery),
function(a) {
    a.widget("ui.menu", {
        version: "1.10.3",
        defaultElement: "<ul>",
        delay: 300,
        options: {
            icons: {
                submenu: "ui-icon-carat-1-e"
            },
            menus: "ul",
            position: {
                my: "left top",
                at: "right top"
            },
            role: "menu",
            blur: null,
            focus: null,
            select: null
        },
        _create: function() {
            this.activeMenu = this.element,
            this.mouseHandled = !1,
            this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons", !!this.element.find(".ui-icon").length).attr({
                role: this.options.role,
                tabIndex: 0
            }).bind("click" + this.eventNamespace, a.proxy(function(a) {
                this.options.disabled && a.preventDefault()
            },
            this)),
            this.options.disabled && this.element.addClass("ui-state-disabled").attr("aria-disabled", "true"),
            this._on({
                "mousedown .ui-menu-item > a": function(a) {
                    a.preventDefault()
                },
                "click .ui-state-disabled > a": function(a) {
                    a.preventDefault()
                },
                "click .ui-menu-item:has(a)": function(b) {
                    var c = a(b.target).closest(".ui-menu-item"); ! this.mouseHandled && c.not(".ui-state-disabled").length && (this.mouseHandled = !0, this.select(b), c.has(".ui-menu").length ? this.expand(b) : this.element.is(":focus") || (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)))
                },
                "mouseenter .ui-menu-item": function(b) {
                    var c = a(b.currentTarget);
                    c.siblings().children(".ui-state-active").removeClass("ui-state-active"),
                    this.focus(b, c)
                },
                mouseleave: "collapseAll",
                "mouseleave .ui-menu": "collapseAll",
                focus: function(a, b) {
                    var c = this.active || this.element.children(".ui-menu-item").eq(0);
                    b || this.focus(a, c)
                },
                blur: function(b) {
                    this._delay(function() {
                        a.contains(this.element[0], this.document[0].activeElement) || this.collapseAll(b)
                    })
                },
                keydown: "_keydown"
            }),
            this.refresh(),
            this._on(this.document, {
                click: function(b) {
                    a(b.target).closest(".ui-menu").length || this.collapseAll(b),
                    this.mouseHandled = !1
                }
            })
        },
        _destroy: function() {
            this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(),
            this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function() {
                var b = a(this);
                b.data("ui-menu-submenu-carat") && b.remove()
            }),
            this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")
        },
        _keydown: function(b) {
            function c(a) {
                return a.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
            }
            var d, e, f, g, h, i = !0;
            switch (b.keyCode) {
            case a.ui.keyCode.PAGE_UP:
                this.previousPage(b);
                break;
            case a.ui.keyCode.PAGE_DOWN:
                this.nextPage(b);
                break;
            case a.ui.keyCode.HOME:
                this._move("first", "first", b);
                break;
            case a.ui.keyCode.END:
                this._move("last", "last", b);
                break;
            case a.ui.keyCode.UP:
                this.previous(b);
                break;
            case a.ui.keyCode.DOWN:
                this.next(b);
                break;
            case a.ui.keyCode.LEFT:
                this.collapse(b);
                break;
            case a.ui.keyCode.RIGHT:
                this.active && !this.active.is(".ui-state-disabled") && this.expand(b);
                break;
            case a.ui.keyCode.ENTER:
            case a.ui.keyCode.SPACE:
                this._activate(b);
                break;
            case a.ui.keyCode.ESCAPE:
                this.collapse(b);
                break;
            default:
                i = !1,
                e = this.previousFilter || "",
                f = String.fromCharCode(b.keyCode),
                g = !1,
                clearTimeout(this.filterTimer),
                f === e ? g = !0 : f = e + f,
                h = new RegExp("^" + c(f), "i"),
                d = this.activeMenu.children(".ui-menu-item").filter(function() {
                    return h.test(a(this).children("a").text())
                }),
                d = g && -1 !== d.index(this.active.next()) ? this.active.nextAll(".ui-menu-item") : d,
                d.length || (f = String.fromCharCode(b.keyCode), h = new RegExp("^" + c(f), "i"), d = this.activeMenu.children(".ui-menu-item").filter(function() {
                    return h.test(a(this).children("a").text())
                })),
                d.length ? (this.focus(b, d), d.length > 1 ? (this.previousFilter = f, this.filterTimer = this._delay(function() {
                    delete this.previousFilter
                },
                1e3)) : delete this.previousFilter) : delete this.previousFilter
            }
            i && b.preventDefault()
        },
        _activate: function(a) {
            this.active.is(".ui-state-disabled") || (this.active.children("a[aria-haspopup='true']").length ? this.expand(a) : this.select(a))
        },
        refresh: function() {
            var b, c = this.options.icons.submenu,
            d = this.element.find(this.options.menus);
            d.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({
                role: this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            }).each(function() {
                var b = a(this),
                d = b.prev("a"),
                e = a("<span>").addClass("ui-menu-icon ui-icon " + c).data("ui-menu-submenu-carat", !0);
                d.attr("aria-haspopup", "true").prepend(e),
                b.attr("aria-labelledby", d.attr("id"))
            }),
            b = d.add(this.element),
            b.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role", "presentation").children("a").uniqueId().addClass("ui-corner-all").attr({
                tabIndex: -1,
                role: this._itemRole()
            }),
            b.children(":not(.ui-menu-item)").each(function() {
                var b = a(this);
                /[^\-\u2014\u2013\s]/.test(b.text()) || b.addClass("ui-widget-content ui-menu-divider")
            }),
            b.children(".ui-state-disabled").attr("aria-disabled", "true"),
            this.active && !a.contains(this.element[0], this.active[0]) && this.blur()
        },
        _itemRole: function() {
            return {
                menu: "menuitem",
                listbox: "option"
            } [this.options.role]
        },
        _setOption: function(a, b) {
            "icons" === a && this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(b.submenu),
            this._super(a, b)
        },
        focus: function(a, b) {
            var c, d;
            this.blur(a, a && "focus" === a.type),
            this._scrollIntoView(b),
            this.active = b.first(),
            d = this.active.children("a").addClass("ui-state-focus"),
            this.options.role && this.element.attr("aria-activedescendant", d.attr("id")),
            this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"),
            a && "keydown" === a.type ? this._close() : this.timer = this._delay(function() {
                this._close()
            },
            this.delay),
            c = b.children(".ui-menu"),
            c.length && /^mouse/.test(a.type) && this._startOpening(c),
            this.activeMenu = b.parent(),
            this._trigger("focus", a, {
                item: b
            })
        },
        _scrollIntoView: function(b) {
            var c, d, e, f, g, h;
            this._hasScroll() && (c = parseFloat(a.css(this.activeMenu[0], "borderTopWidth")) || 0, d = parseFloat(a.css(this.activeMenu[0], "paddingTop")) || 0, e = b.offset().top - this.activeMenu.offset().top - c - d, f = this.activeMenu.scrollTop(), g = this.activeMenu.height(), h = b.height(), 0 > e ? this.activeMenu.scrollTop(f + e) : e + h > g && this.activeMenu.scrollTop(f + e - g + h))
        },
        blur: function(a, b) {
            b || clearTimeout(this.timer),
            this.active && (this.active.children("a").removeClass("ui-state-focus"), this.active = null, this._trigger("blur", a, {
                item: this.active
            }))
        },
        _startOpening: function(a) {
            clearTimeout(this.timer),
            "true" === a.attr("aria-hidden") && (this.timer = this._delay(function() {
                this._close(),
                this._open(a)
            },
            this.delay))
        },
        _open: function(b) {
            var c = a.extend({
                of: this.active
            },
            this.options.position);
            clearTimeout(this.timer),
            this.element.find(".ui-menu").not(b.parents(".ui-menu")).hide().attr("aria-hidden", "true"),
            b.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(c)
        },
        collapseAll: function(b, c) {
            clearTimeout(this.timer),
            this.timer = this._delay(function() {
                var d = c ? this.element: a(b && b.target).closest(this.element.find(".ui-menu"));
                d.length || (d = this.element),
                this._close(d),
                this.blur(b),
                this.activeMenu = d
            },
            this.delay)
        },
        _close: function(a) {
            a || (a = this.active ? this.active.parent() : this.element),
            a.find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false").end().find("a.ui-state-active").removeClass("ui-state-active")
        },
        collapse: function(a) {
            var b = this.active && this.active.parent().closest(".ui-menu-item", this.element);
            b && b.length && (this._close(), this.focus(a, b))
        },
        expand: function(a) {
            var b = this.active && this.active.children(".ui-menu ").children(".ui-menu-item").first();
            b && b.length && (this._open(b.parent()), this._delay(function() {
                this.focus(a, b)
            }))
        },
        next: function(a) {
            this._move("next", "first", a)
        },
        previous: function(a) {
            this._move("prev", "last", a)
        },
        isFirstItem: function() {
            return this.active && !this.active.prevAll(".ui-menu-item").length
        },
        isLastItem: function() {
            return this.active && !this.active.nextAll(".ui-menu-item").length
        },
        _move: function(a, b, c) {
            var d;
            this.active && (d = "first" === a || "last" === a ? this.active["first" === a ? "prevAll": "nextAll"](".ui-menu-item").eq( - 1) : this.active[a + "All"](".ui-menu-item").eq(0)),
            d && d.length && this.active || (d = this.activeMenu.children(".ui-menu-item")[b]()),
            this.focus(c, d)
        },
        nextPage: function(b) {
            var c, d, e;
            return this.active ? (this.isLastItem() || (this._hasScroll() ? (d = this.active.offset().top, e = this.element.height(), this.active.nextAll(".ui-menu-item").each(function() {
                return c = a(this),
                c.offset().top - d - e < 0
            }), this.focus(b, c)) : this.focus(b, this.activeMenu.children(".ui-menu-item")[this.active ? "last": "first"]())), void 0) : (this.next(b), void 0)
        },
        previousPage: function(b) {
            var c, d, e;
            return this.active ? (this.isFirstItem() || (this._hasScroll() ? (d = this.active.offset().top, e = this.element.height(), this.active.prevAll(".ui-menu-item").each(function() {
                return c = a(this),
                c.offset().top - d + e > 0
            }), this.focus(b, c)) : this.focus(b, this.activeMenu.children(".ui-menu-item").first())), void 0) : (this.next(b), void 0)
        },
        _hasScroll: function() {
            return this.element.outerHeight() < this.element.prop("scrollHeight")
        },
        select: function(b) {
            this.active = this.active || a(b.target).closest(".ui-menu-item");
            var c = {
                item: this.active
            };
            this.active.has(".ui-menu").length || this.collapseAll(b, !0),
            this._trigger("select", b, c)
        }
    })
} (jQuery),
function(a) {
    var b = 5;
    a.widget("ui.slider", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "slide",
        options: {
            animate: !1,
            distance: 0,
            max: 100,
            min: 0,
            orientation: "horizontal",
            range: !1,
            step: 1,
            value: 0,
            values: null,
            change: null,
            slide: null,
            start: null,
            stop: null
        },
        _create: function() {
            this._keySliding = !1,
            this._mouseSliding = !1,
            this._animateOff = !0,
            this._handleIndex = null,
            this._detectOrientation(),
            this._mouseInit(),
            this.element.addClass("ui-slider ui-slider-" + this.orientation + " ui-widget" + " ui-widget-content" + " ui-corner-all"),
            this._refresh(),
            this._setOption("disabled", this.options.disabled),
            this._animateOff = !1
        },
        _refresh: function() {
            this._createRange(),
            this._createHandles(),
            this._setupEvents(),
            this._refreshValue()
        },
        _createHandles: function() {
            var b, c, d = this.options,
            e = this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),
            f = "<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>",
            g = [];
            for (c = d.values && d.values.length || 1, e.length > c && (e.slice(c).remove(), e = e.slice(0, c)), b = e.length; c > b; b++) g.push(f);
            this.handles = e.add(a(g.join("")).appendTo(this.element)),
            this.handle = this.handles.eq(0),
            this.handles.each(function(b) {
                a(this).data("ui-slider-handle-index", b)
            })
        },
        _createRange: function() {
            var b = this.options,
            c = "";
            b.range ? (b.range === !0 && (b.values ? b.values.length && 2 !== b.values.length ? b.values = [b.values[0], b.values[0]] : a.isArray(b.values) && (b.values = b.values.slice(0)) : b.values = [this._valueMin(), this._valueMin()]), this.range && this.range.length ? this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({
                left: "",
                bottom: ""
            }) : (this.range = a("<div></div>").appendTo(this.element), c = "ui-slider-range ui-widget-header ui-corner-all"), this.range.addClass(c + ("min" === b.range || "max" === b.range ? " ui-slider-range-" + b.range: ""))) : this.range = a([])
        },
        _setupEvents: function() {
            var a = this.handles.add(this.range).filter("a");
            this._off(a),
            this._on(a, this._handleEvents),
            this._hoverable(a),
            this._focusable(a)
        },
        _destroy: function() {
            this.handles.remove(),
            this.range.remove(),
            this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"),
            this._mouseDestroy()
        },
        _mouseCapture: function(b) {
            var c, d, e, f, g, h, i, j, k = this,
            l = this.options;
            return l.disabled ? !1 : (this.elementSize = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            },
            this.elementOffset = this.element.offset(), c = {
                x: b.pageX,
                y: b.pageY
            },
            d = this._normValueFromMouse(c), e = this._valueMax() - this._valueMin() + 1, this.handles.each(function(b) {
                var c = Math.abs(d - k.values(b)); (e > c || e === c && (b === k._lastChangedValue || k.values(b) === l.min)) && (e = c, f = a(this), g = b)
            }), h = this._start(b, g), h === !1 ? !1 : (this._mouseSliding = !0, this._handleIndex = g, f.addClass("ui-state-active").focus(), i = f.offset(), j = !a(b.target).parents().addBack().is(".ui-slider-handle"), this._clickOffset = j ? {
                left: 0,
                top: 0
            }: {
                left: b.pageX - i.left - f.width() / 2,
                top: b.pageY - i.top - f.height() / 2 - (parseInt(f.css("borderTopWidth"), 10) || 0) - (parseInt(f.css("borderBottomWidth"), 10) || 0) + (parseInt(f.css("marginTop"), 10) || 0)
            },
            this.handles.hasClass("ui-state-hover") || this._slide(b, g, d), this._animateOff = !0, !0))
        },
        _mouseStart: function() {
            return ! 0
        },
        _mouseDrag: function(a) {
            var b = {
                x: a.pageX,
                y: a.pageY
            },
            c = this._normValueFromMouse(b);
            return this._slide(a, this._handleIndex, c),
            !1
        },
        _mouseStop: function(a) {
            return this.handles.removeClass("ui-state-active"),
            this._mouseSliding = !1,
            this._stop(a, this._handleIndex),
            this._change(a, this._handleIndex),
            this._handleIndex = null,
            this._clickOffset = null,
            this._animateOff = !1,
            !1
        },
        _detectOrientation: function() {
            this.orientation = "vertical" === this.options.orientation ? "vertical": "horizontal"
        },
        _normValueFromMouse: function(a) {
            var b, c, d, e, f;
            return "horizontal" === this.orientation ? (b = this.elementSize.width, c = a.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left: 0)) : (b = this.elementSize.height, c = a.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top: 0)),
            d = c / b,
            d > 1 && (d = 1),
            0 > d && (d = 0),
            "vertical" === this.orientation && (d = 1 - d),
            e = this._valueMax() - this._valueMin(),
            f = this._valueMin() + d * e,
            this._trimAlignValue(f)
        },
        _start: function(a, b) {
            var c = {
                handle: this.handles[b],
                value: this.value()
            };
            return this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()),
            this._trigger("start", a, c)
        },
        _slide: function(a, b, c) {
            var d, e, f;
            this.options.values && this.options.values.length ? (d = this.values(b ? 0 : 1), 2 === this.options.values.length && this.options.range === !0 && (0 === b && c > d || 1 === b && d > c) && (c = d), c !== this.values(b) && (e = this.values(), e[b] = c, f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c,
                values: e
            }), d = this.values(b ? 0 : 1), f !== !1 && this.values(b, c, !0))) : c !== this.value() && (f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c
            }), f !== !1 && this.value(c))
        },
        _stop: function(a, b) {
            var c = {
                handle: this.handles[b],
                value: this.value()
            };
            this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()),
            this._trigger("stop", a, c)
        },
        _change: function(a, b) {
            if (!this._keySliding && !this._mouseSliding) {
                var c = {
                    handle: this.handles[b],
                    value: this.value()
                };
                this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()),
                this._lastChangedValue = b,
                this._trigger("change", a, c)
            }
        },
        value: function(a) {
            return arguments.length ? (this.options.value = this._trimAlignValue(a), this._refreshValue(), this._change(null, 0), void 0) : this._value()
        },
        values: function(b, c) {
            var d, e, f;
            if (arguments.length > 1) return this.options.values[b] = this._trimAlignValue(c),
            this._refreshValue(),
            this._change(null, b),
            void 0;
            if (!arguments.length) return this._values();
            if (!a.isArray(arguments[0])) return this.options.values && this.options.values.length ? this._values(b) : this.value();
            for (d = this.options.values, e = arguments[0], f = 0; f < d.length; f += 1) d[f] = this._trimAlignValue(e[f]),
            this._change(null, f);
            this._refreshValue()
        },
        _setOption: function(b, c) {
            var d, e = 0;
            switch ("range" === b && this.options.range === !0 && ("min" === c ? (this.options.value = this._values(0), this.options.values = null) : "max" === c && (this.options.value = this._values(this.options.values.length - 1), this.options.values = null)), a.isArray(this.options.values) && (e = this.options.values.length), a.Widget.prototype._setOption.apply(this, arguments), b) {
            case "orientation":
                this._detectOrientation(),
                this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation),
                this._refreshValue();
                break;
            case "value":
                this._animateOff = !0,
                this._refreshValue(),
                this._change(null, 0),
                this._animateOff = !1;
                break;
            case "values":
                for (this._animateOff = !0, this._refreshValue(), d = 0; e > d; d += 1) this._change(null, d);
                this._animateOff = !1;
                break;
            case "min":
            case "max":
                this._animateOff = !0,
                this._refreshValue(),
                this._animateOff = !1;
                break;
            case "range":
                this._animateOff = !0,
                this._refresh(),
                this._animateOff = !1
            }
        },
        _value: function() {
            var a = this.options.value;
            return a = this._trimAlignValue(a)
        },
        _values: function(a) {
            var b, c, d;
            if (arguments.length) return b = this.options.values[a],
            b = this._trimAlignValue(b);
            if (this.options.values && this.options.values.length) {
                for (c = this.options.values.slice(), d = 0; d < c.length; d += 1) c[d] = this._trimAlignValue(c[d]);
                return c
            }
            return []
        },
        _trimAlignValue: function(a) {
            if (a <= this._valueMin()) return this._valueMin();
            if (a >= this._valueMax()) return this._valueMax();
            var b = this.options.step > 0 ? this.options.step: 1,
            c = (a - this._valueMin()) % b,
            d = a - c;
            return 2 * Math.abs(c) >= b && (d += c > 0 ? b: -b),
            parseFloat(d.toFixed(5))
        },
        _valueMin: function() {
            return this.options.min
        },
        _valueMax: function() {
            return this.options.max
        },
        _refreshValue: function() {
            var b, c, d, e, f, g = this.options.range,
            h = this.options,
            i = this,
            j = this._animateOff ? !1 : h.animate,
            k = {};
            this.options.values && this.options.values.length ? this.handles.each(function(d) {
                c = 100 * ((i.values(d) - i._valueMin()) / (i._valueMax() - i._valueMin())),
                k["horizontal" === i.orientation ? "left": "bottom"] = c + "%",
                a(this).stop(1, 1)[j ? "animate": "css"](k, h.animate),
                i.options.range === !0 && ("horizontal" === i.orientation ? (0 === d && i.range.stop(1, 1)[j ? "animate": "css"]({
                    left: c + "%"
                },
                h.animate), 1 === d && i.range[j ? "animate": "css"]({
                    width: c - b + "%"
                },
                {
                    queue: !1,
                    duration: h.animate
                })) : (0 === d && i.range.stop(1, 1)[j ? "animate": "css"]({
                    bottom: c + "%"
                },
                h.animate), 1 === d && i.range[j ? "animate": "css"]({
                    height: c - b + "%"
                },
                {
                    queue: !1,
                    duration: h.animate
                }))),
                b = c
            }) : (d = this.value(), e = this._valueMin(), f = this._valueMax(), c = f !== e ? 100 * ((d - e) / (f - e)) : 0, k["horizontal" === this.orientation ? "left": "bottom"] = c + "%", this.handle.stop(1, 1)[j ? "animate": "css"](k, h.animate), "min" === g && "horizontal" === this.orientation && this.range.stop(1, 1)[j ? "animate": "css"]({
                width: c + "%"
            },
            h.animate), "max" === g && "horizontal" === this.orientation && this.range[j ? "animate": "css"]({
                width: 100 - c + "%"
            },
            {
                queue: !1,
                duration: h.animate
            }), "min" === g && "vertical" === this.orientation && this.range.stop(1, 1)[j ? "animate": "css"]({
                height: c + "%"
            },
            h.animate), "max" === g && "vertical" === this.orientation && this.range[j ? "animate": "css"]({
                height: 100 - c + "%"
            },
            {
                queue: !1,
                duration: h.animate
            }))
        },
        _handleEvents: {
            keydown: function(c) {
                var d, e, f, g, h = a(c.target).data("ui-slider-handle-index");
                switch (c.keyCode) {
                case a.ui.keyCode.HOME:
                case a.ui.keyCode.END:
                case a.ui.keyCode.PAGE_UP:
                case a.ui.keyCode.PAGE_DOWN:
                case a.ui.keyCode.UP:
                case a.ui.keyCode.RIGHT:
                case a.ui.keyCode.DOWN:
                case a.ui.keyCode.LEFT:
                    if (c.preventDefault(), !this._keySliding && (this._keySliding = !0, a(c.target).addClass("ui-state-active"), d = this._start(c, h), d === !1)) return
                }
                switch (g = this.options.step, e = f = this.options.values && this.options.values.length ? this.values(h) : this.value(), c.keyCode) {
                case a.ui.keyCode.HOME:
                    f = this._valueMin();
                    break;
                case a.ui.keyCode.END:
                    f = this._valueMax();
                    break;
                case a.ui.keyCode.PAGE_UP:
                    f = this._trimAlignValue(e + (this._valueMax() - this._valueMin()) / b);
                    break;
                case a.ui.keyCode.PAGE_DOWN:
                    f = this._trimAlignValue(e - (this._valueMax() - this._valueMin()) / b);
                    break;
                case a.ui.keyCode.UP:
                case a.ui.keyCode.RIGHT:
                    if (e === this._valueMax()) return;
                    f = this._trimAlignValue(e + g);
                    break;
                case a.ui.keyCode.DOWN:
                case a.ui.keyCode.LEFT:
                    if (e === this._valueMin()) return;
                    f = this._trimAlignValue(e - g)
                }
                this._slide(c, h, f)
            },
            click: function(a) {
                a.preventDefault()
            },
            keyup: function(b) {
                var c = a(b.target).data("ui-slider-handle-index");
                this._keySliding && (this._keySliding = !1, this._stop(b, c), this._change(b, c), a(b.target).removeClass("ui-state-active"))
            }
        }
    })
} (jQuery),
function(a) {
    function b(a) {
        return function() {
            var b = this.element.val();
            a.apply(this, arguments),
            this._refresh(),
            b !== this.element.val() && this._trigger("change")
        }
    }
    a.widget("ui.spinner", {
        version: "1.10.3",
        defaultElement: "<input>",
        widgetEventPrefix: "spin",
        options: {
            culture: null,
            icons: {
                down: "ui-icon-triangle-1-s",
                up: "ui-icon-triangle-1-n"
            },
            incremental: !0,
            max: null,
            min: null,
            numberFormat: null,
            page: 10,
            step: 1,
            change: null,
            spin: null,
            start: null,
            stop: null
        },
        _create: function() {
            this._setOption("max", this.options.max),
            this._setOption("min", this.options.min),
            this._setOption("step", this.options.step),
            this._value(this.element.val(), !0),
            this._draw(),
            this._on(this._events),
            this._refresh(),
            this._on(this.window, {
                beforeunload: function() {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _getCreateOptions: function() {
            var b = {},
            c = this.element;
            return a.each(["min", "max", "step"],
            function(a, d) {
                var e = c.attr(d);
                void 0 !== e && e.length && (b[d] = e)
            }),
            b
        },
        _events: {
            keydown: function(a) {
                this._start(a) && this._keydown(a) && a.preventDefault()
            },
            keyup: "_stop",
            focus: function() {
                this.previous = this.element.val()
            },
            blur: function(a) {
                return this.cancelBlur ? (delete this.cancelBlur, void 0) : (this._stop(), this._refresh(), this.previous !== this.element.val() && this._trigger("change", a), void 0)
            },
            mousewheel: function(a, b) {
                if (b) {
                    if (!this.spinning && !this._start(a)) return ! 1;
                    this._spin((b > 0 ? 1 : -1) * this.options.step, a),
                    clearTimeout(this.mousewheelTimer),
                    this.mousewheelTimer = this._delay(function() {
                        this.spinning && this._stop(a)
                    },
                    100),
                    a.preventDefault()
                }
            },
            "mousedown .ui-spinner-button": function(b) {
                function c() {
                    var a = this.element[0] === this.document[0].activeElement;
                    a || (this.element.focus(), this.previous = d, this._delay(function() {
                        this.previous = d
                    }))
                }
                var d;
                d = this.element[0] === this.document[0].activeElement ? this.previous: this.element.val(),
                b.preventDefault(),
                c.call(this),
                this.cancelBlur = !0,
                this._delay(function() {
                    delete this.cancelBlur,
                    c.call(this)
                }),
                this._start(b) !== !1 && this._repeat(null, a(b.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, b)
            },
            "mouseup .ui-spinner-button": "_stop",
            "mouseenter .ui-spinner-button": function(b) {
                return a(b.currentTarget).hasClass("ui-state-active") ? this._start(b) === !1 ? !1 : (this._repeat(null, a(b.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, b), void 0) : void 0
            },
            "mouseleave .ui-spinner-button": "_stop"
        },
        _draw: function() {
            var a = this.uiSpinner = this.element.addClass("ui-spinner-input").attr("autocomplete", "off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());
            this.element.attr("role", "spinbutton"),
            this.buttons = a.find(".ui-spinner-button").attr("tabIndex", -1).button().removeClass("ui-corner-all"),
            this.buttons.height() > Math.ceil(.5 * a.height()) && a.height() > 0 && a.height(a.height()),
            this.options.disabled && this.disable()
        },
        _keydown: function(b) {
            var c = this.options,
            d = a.ui.keyCode;
            switch (b.keyCode) {
            case d.UP:
                return this._repeat(null, 1, b),
                !0;
            case d.DOWN:
                return this._repeat(null, -1, b),
                !0;
            case d.PAGE_UP:
                return this._repeat(null, c.page, b),
                !0;
            case d.PAGE_DOWN:
                return this._repeat(null, -c.page, b),
                !0
            }
            return ! 1
        },
        _uiSpinnerHtml: function() {
            return "<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"
        },
        _buttonHtml: function() {
            return "<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon " + this.options.icons.up + "'>&#9650;</span>" + "</a>" + "<a class='ui-spinner-button ui-spinner-down ui-corner-br'>" + "<span class='ui-icon " + this.options.icons.down + "'>&#9660;</span>" + "</a>"
        },
        _start: function(a) {
            return this.spinning || this._trigger("start", a) !== !1 ? (this.counter || (this.counter = 1), this.spinning = !0, !0) : !1
        },
        _repeat: function(a, b, c) {
            a = a || 500,
            clearTimeout(this.timer),
            this.timer = this._delay(function() {
                this._repeat(40, b, c)
            },
            a),
            this._spin(b * this.options.step, c)
        },
        _spin: function(a, b) {
            var c = this.value() || 0;
            this.counter || (this.counter = 1),
            c = this._adjustValue(c + a * this._increment(this.counter)),
            this.spinning && this._trigger("spin", b, {
                value: c
            }) === !1 || (this._value(c), this.counter++)
        },
        _increment: function(b) {
            var c = this.options.incremental;
            return c ? a.isFunction(c) ? c(b) : Math.floor(b * b * b / 5e4 - b * b / 500 + 17 * b / 200 + 1) : 1
        },
        _precision: function() {
            var a = this._precisionOf(this.options.step);
            return null !== this.options.min && (a = Math.max(a, this._precisionOf(this.options.min))),
            a
        },
        _precisionOf: function(a) {
            var b = a.toString(),
            c = b.indexOf(".");
            return - 1 === c ? 0 : b.length - c - 1
        },
        _adjustValue: function(a) {
            var b, c, d = this.options;
            return b = null !== d.min ? d.min: 0,
            c = a - b,
            c = Math.round(c / d.step) * d.step,
            a = b + c,
            a = parseFloat(a.toFixed(this._precision())),
            null !== d.max && a > d.max ? d.max: null !== d.min && a < d.min ? d.min: a
        },
        _stop: function(a) {
            this.spinning && (clearTimeout(this.timer), clearTimeout(this.mousewheelTimer), this.counter = 0, this.spinning = !1, this._trigger("stop", a))
        },
        _setOption: function(a, b) {
            if ("culture" === a || "numberFormat" === a) {
                var c = this._parse(this.element.val());
                return this.options[a] = b,
                this.element.val(this._format(c)),
                void 0
            } ("max" === a || "min" === a || "step" === a) && "string" == typeof b && (b = this._parse(b)),
            "icons" === a && (this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(b.up), this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(b.down)),
            this._super(a, b),
            "disabled" === a && (b ? (this.element.prop("disabled", !0), this.buttons.button("disable")) : (this.element.prop("disabled", !1), this.buttons.button("enable")))
        },
        _setOptions: b(function(a) {
            this._super(a),
            this._value(this.element.val())
        }),
        _parse: function(a) {
            return "string" == typeof a && "" !== a && (a = window.Globalize && this.options.numberFormat ? Globalize.parseFloat(a, 10, this.options.culture) : +a),
            "" === a || isNaN(a) ? null: a
        },
        _format: function(a) {
            return "" === a ? "": window.Globalize && this.options.numberFormat ? Globalize.format(a, this.options.numberFormat, this.options.culture) : a
        },
        _refresh: function() {
            this.element.attr({
                "aria-valuemin": this.options.min,
                "aria-valuemax": this.options.max,
                "aria-valuenow": this._parse(this.element.val())
            })
        },
        _value: function(a, b) {
            var c;
            "" !== a && (c = this._parse(a), null !== c && (b || (c = this._adjustValue(c)), a = this._format(c))),
            this.element.val(a),
            this._refresh()
        },
        _destroy: function() {
            this.element.removeClass("ui-spinner-input").prop("disabled", !1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"),
            this.uiSpinner.replaceWith(this.element)
        },
        stepUp: b(function(a) {
            this._stepUp(a)
        }),
        _stepUp: function(a) {
            this._start() && (this._spin((a || 1) * this.options.step), this._stop())
        },
        stepDown: b(function(a) {
            this._stepDown(a)
        }),
        _stepDown: function(a) {
            this._start() && (this._spin((a || 1) * -this.options.step), this._stop())
        },
        pageUp: b(function(a) {
            this._stepUp((a || 1) * this.options.page)
        }),
        pageDown: b(function(a) {
            this._stepDown((a || 1) * this.options.page)
        }),
        value: function(a) {
            return arguments.length ? (b(this._value).call(this, a), void 0) : this._parse(this.element.val())
        },
        widget: function() {
            return this.uiSpinner
        }
    })
} (jQuery),
function(a, b) {
    function c() {
        return++e
    }
    function d(a) {
        return a.hash.length > 1 && decodeURIComponent(a.href.replace(f, "")) === decodeURIComponent(location.href.replace(f, ""))
    }
    var e = 0,
    f = /#.*$/;
    a.widget("ui.tabs", {
        version: "1.10.3",
        delay: 300,
        options: {
            active: null,
            collapsible: !1,
            event: "click",
            heightStyle: "content",
            hide: null,
            show: null,
            activate: null,
            beforeActivate: null,
            beforeLoad: null,
            load: null
        },
        _create: function() {
            var b = this,
            c = this.options;
            this.running = !1,
            this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible", c.collapsible).delegate(".ui-tabs-nav > li", "mousedown" + this.eventNamespace,
            function(b) {
                a(this).is(".ui-state-disabled") && b.preventDefault()
            }).delegate(".ui-tabs-anchor", "focus" + this.eventNamespace,
            function() {
                a(this).closest("li").is(".ui-state-disabled") && this.blur()
            }),
            this._processTabs(),
            c.active = this._initialActive(),
            a.isArray(c.disabled) && (c.disabled = a.unique(c.disabled.concat(a.map(this.tabs.filter(".ui-state-disabled"),
            function(a) {
                return b.tabs.index(a)
            }))).sort()),
            this.active = this.options.active !== !1 && this.anchors.length ? this._findActive(c.active) : a(),
            this._refresh(),
            this.active.length && this.load(c.active)
        },
        _initialActive: function() {
            var b = this.options.active,
            c = this.options.collapsible,
            d = location.hash.substring(1);
            return null === b && (d && this.tabs.each(function(c, e) {
                return a(e).attr("aria-controls") === d ? (b = c, !1) : void 0
            }), null === b && (b = this.tabs.index(this.tabs.filter(".ui-tabs-active"))), (null === b || -1 === b) && (b = this.tabs.length ? 0 : !1)),
            b !== !1 && (b = this.tabs.index(this.tabs.eq(b)), -1 === b && (b = c ? !1 : 0)),
            !c && b === !1 && this.anchors.length && (b = 0),
            b
        },
        _getCreateEventData: function() {
            return {
                tab: this.active,
                panel: this.active.length ? this._getPanelForTab(this.active) : a()
            }
        },
        _tabKeydown: function(b) {
            var c = a(this.document[0].activeElement).closest("li"),
            d = this.tabs.index(c),
            e = !0;
            if (!this._handlePageNav(b)) {
                switch (b.keyCode) {
                case a.ui.keyCode.RIGHT:
                case a.ui.keyCode.DOWN:
                    d++;
                    break;
                case a.ui.keyCode.UP:
                case a.ui.keyCode.LEFT:
                    e = !1,
                    d--;
                    break;
                case a.ui.keyCode.END:
                    d = this.anchors.length - 1;
                    break;
                case a.ui.keyCode.HOME:
                    d = 0;
                    break;
                case a.ui.keyCode.SPACE:
                    return b.preventDefault(),
                    clearTimeout(this.activating),
                    this._activate(d),
                    void 0;
                case a.ui.keyCode.ENTER:
                    return b.preventDefault(),
                    clearTimeout(this.activating),
                    this._activate(d === this.options.active ? !1 : d),
                    void 0;
                default:
                    return
                }
                b.preventDefault(),
                clearTimeout(this.activating),
                d = this._focusNextTab(d, e),
                b.ctrlKey || (c.attr("aria-selected", "false"), this.tabs.eq(d).attr("aria-selected", "true"), this.activating = this._delay(function() {
                    this.option("active", d)
                },
                this.delay))
            }
        },
        _panelKeydown: function(b) {
            this._handlePageNav(b) || b.ctrlKey && b.keyCode === a.ui.keyCode.UP && (b.preventDefault(), this.active.focus())
        },
        _handlePageNav: function(b) {
            return b.altKey && b.keyCode === a.ui.keyCode.PAGE_UP ? (this._activate(this._focusNextTab(this.options.active - 1, !1)), !0) : b.altKey && b.keyCode === a.ui.keyCode.PAGE_DOWN ? (this._activate(this._focusNextTab(this.options.active + 1, !0)), !0) : void 0
        },
        _findNextTab: function(b, c) {
            function d() {
                return b > e && (b = 0),
                0 > b && (b = e),
                b
            }
            for (var e = this.tabs.length - 1; - 1 !== a.inArray(d(), this.options.disabled);) b = c ? b + 1 : b - 1;
            return b
        },
        _focusNextTab: function(a, b) {
            return a = this._findNextTab(a, b),
            this.tabs.eq(a).focus(),
            a
        },
        _setOption: function(a, b) {
            return "active" === a ? (this._activate(b), void 0) : "disabled" === a ? (this._setupDisabled(b), void 0) : (this._super(a, b), "collapsible" === a && (this.element.toggleClass("ui-tabs-collapsible", b), b || this.options.active !== !1 || this._activate(0)), "event" === a && this._setupEvents(b), "heightStyle" === a && this._setupHeightStyle(b), void 0)
        },
        _tabId: function(a) {
            return a.attr("aria-controls") || "ui-tabs-" + c()
        },
        _sanitizeSelector: function(a) {
            return a ? a.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g, "\\$&") : ""
        },
        refresh: function() {
            var b = this.options,
            c = this.tablist.children(":has(a[href])");
            b.disabled = a.map(c.filter(".ui-state-disabled"),
            function(a) {
                return c.index(a)
            }),
            this._processTabs(),
            b.active !== !1 && this.anchors.length ? this.active.length && !a.contains(this.tablist[0], this.active[0]) ? this.tabs.length === b.disabled.length ? (b.active = !1, this.active = a()) : this._activate(this._findNextTab(Math.max(0, b.active - 1), !1)) : b.active = this.tabs.index(this.active) : (b.active = !1, this.active = a()),
            this._refresh()
        },
        _refresh: function() {
            this._setupDisabled(this.options.disabled),
            this._setupEvents(this.options.event),
            this._setupHeightStyle(this.options.heightStyle),
            this.tabs.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }),
            this.panels.not(this._getPanelForTab(this.active)).hide().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            this.active.length ? (this.active.addClass("ui-tabs-active ui-state-active").attr({
                "aria-selected": "true",
                tabIndex: 0
            }), this._getPanelForTab(this.active).show().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            })) : this.tabs.eq(0).attr("tabIndex", 0)
        },
        _processTabs: function() {
            var b = this;
            this.tablist = this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role", "tablist"),
            this.tabs = this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({
                role: "tab",
                tabIndex: -1
            }),
            this.anchors = this.tabs.map(function() {
                return a("a", this)[0]
            }).addClass("ui-tabs-anchor").attr({
                role: "presentation",
                tabIndex: -1
            }),
            this.panels = a(),
            this.anchors.each(function(c, e) {
                var f, g, h, i = a(e).uniqueId().attr("id"),
                j = a(e).closest("li"),
                k = j.attr("aria-controls");
                d(e) ? (f = e.hash, g = b.element.find(b._sanitizeSelector(f))) : (h = b._tabId(j), f = "#" + h, g = b.element.find(f), g.length || (g = b._createPanel(h), g.insertAfter(b.panels[c - 1] || b.tablist)), g.attr("aria-live", "polite")),
                g.length && (b.panels = b.panels.add(g)),
                k && j.data("ui-tabs-aria-controls", k),
                j.attr({
                    "aria-controls": f.substring(1),
                    "aria-labelledby": i
                }),
                g.attr("aria-labelledby", i)
            }),
            this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role", "tabpanel")
        },
        _getList: function() {
            return this.element.find("ol,ul").eq(0)
        },
        _createPanel: function(b) {
            return a("<div>").attr("id", b).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy", !0)
        },
        _setupDisabled: function(b) {
            a.isArray(b) && (b.length ? b.length === this.anchors.length && (b = !0) : b = !1);
            for (var c, d = 0; c = this.tabs[d]; d++) b === !0 || -1 !== a.inArray(d, b) ? a(c).addClass("ui-state-disabled").attr("aria-disabled", "true") : a(c).removeClass("ui-state-disabled").removeAttr("aria-disabled");
            this.options.disabled = b
        },
        _setupEvents: function(b) {
            var c = {
                click: function(a) {
                    a.preventDefault()
                }
            };
            b && a.each(b.split(" "),
            function(a, b) {
                c[b] = "_eventHandler"
            }),
            this._off(this.anchors.add(this.tabs).add(this.panels)),
            this._on(this.anchors, c),
            this._on(this.tabs, {
                keydown: "_tabKeydown"
            }),
            this._on(this.panels, {
                keydown: "_panelKeydown"
            }),
            this._focusable(this.tabs),
            this._hoverable(this.tabs)
        },
        _setupHeightStyle: function(b) {
            var c, d = this.element.parent();
            "fill" === b ? (c = d.height(), c -= this.element.outerHeight() - this.element.height(), this.element.siblings(":visible").each(function() {
                var b = a(this),
                d = b.css("position");
                "absolute" !== d && "fixed" !== d && (c -= b.outerHeight(!0))
            }), this.element.children().not(this.panels).each(function() {
                c -= a(this).outerHeight(!0)
            }), this.panels.each(function() {
                a(this).height(Math.max(0, c - a(this).innerHeight() + a(this).height()))
            }).css("overflow", "auto")) : "auto" === b && (c = 0, this.panels.each(function() {
                c = Math.max(c, a(this).height("").height())
            }).height(c))
        },
        _eventHandler: function(b) {
            var c = this.options,
            d = this.active,
            e = a(b.currentTarget),
            f = e.closest("li"),
            g = f[0] === d[0],
            h = g && c.collapsible,
            i = h ? a() : this._getPanelForTab(f),
            j = d.length ? this._getPanelForTab(d) : a(),
            k = {
                oldTab: d,
                oldPanel: j,
                newTab: h ? a() : f,
                newPanel: i
            };
            b.preventDefault(),
            f.hasClass("ui-state-disabled") || f.hasClass("ui-tabs-loading") || this.running || g && !c.collapsible || this._trigger("beforeActivate", b, k) === !1 || (c.active = h ? !1 : this.tabs.index(f), this.active = g ? a() : f, this.xhr && this.xhr.abort(), j.length || i.length || a.error("jQuery UI Tabs: Mismatching fragment identifier."), i.length && this.load(this.tabs.index(f), b), this._toggle(b, k))
        },
        _toggle: function(b, c) {
            function d() {
                f.running = !1,
                f._trigger("activate", b, c)
            }
            function e() {
                c.newTab.closest("li").addClass("ui-tabs-active ui-state-active"),
                g.length && f.options.show ? f._show(g, f.options.show, d) : (g.show(), d())
            }
            var f = this,
            g = c.newPanel,
            h = c.oldPanel;
            this.running = !0,
            h.length && this.options.hide ? this._hide(h, this.options.hide,
            function() {
                c.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"),
                e()
            }) : (c.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), h.hide(), e()),
            h.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }),
            c.oldTab.attr("aria-selected", "false"),
            g.length && h.length ? c.oldTab.attr("tabIndex", -1) : g.length && this.tabs.filter(function() {
                return 0 === a(this).attr("tabIndex")
            }).attr("tabIndex", -1),
            g.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }),
            c.newTab.attr({
                "aria-selected": "true",
                tabIndex: 0
            })
        },
        _activate: function(b) {
            var c, d = this._findActive(b);
            d[0] !== this.active[0] && (d.length || (d = this.active), c = d.find(".ui-tabs-anchor")[0], this._eventHandler({
                target: c,
                currentTarget: c,
                preventDefault: a.noop
            }))
        },
        _findActive: function(b) {
            return b === !1 ? a() : this.tabs.eq(b)
        },
        _getIndex: function(a) {
            return "string" == typeof a && (a = this.anchors.index(this.anchors.filter("[href$='" + a + "']"))),
            a
        },
        _destroy: function() {
            this.xhr && this.xhr.abort(),
            this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"),
            this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"),
            this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(),
            this.tabs.add(this.panels).each(function() {
                a.data(this, "ui-tabs-destroy") ? a(this).remove() : a(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")
            }),
            this.tabs.each(function() {
                var b = a(this),
                c = b.data("ui-tabs-aria-controls");
                c ? b.attr("aria-controls", c).removeData("ui-tabs-aria-controls") : b.removeAttr("aria-controls")
            }),
            this.panels.show(),
            "content" !== this.options.heightStyle && this.panels.css("height", "")
        },
        enable: function(c) {
            var d = this.options.disabled;
            d !== !1 && (c === b ? d = !1 : (c = this._getIndex(c), d = a.isArray(d) ? a.map(d,
            function(a) {
                return a !== c ? a: null
            }) : a.map(this.tabs,
            function(a, b) {
                return b !== c ? b: null
            })), this._setupDisabled(d))
        },
        disable: function(c) {
            var d = this.options.disabled;
            if (d !== !0) {
                if (c === b) d = !0;
                else {
                    if (c = this._getIndex(c), -1 !== a.inArray(c, d)) return;
                    d = a.isArray(d) ? a.merge([c], d).sort() : [c]
                }
                this._setupDisabled(d)
            }
        },
        load: function(b, c) {
            b = this._getIndex(b);
            var e = this,
            f = this.tabs.eq(b),
            g = f.find(".ui-tabs-anchor"),
            h = this._getPanelForTab(f),
            i = {
                tab: f,
                panel: h
            };
            d(g[0]) || (this.xhr = a.ajax(this._ajaxSettings(g, c, i)), this.xhr && "canceled" !== this.xhr.statusText && (f.addClass("ui-tabs-loading"), h.attr("aria-busy", "true"), this.xhr.success(function(a) {
                setTimeout(function() {
                    h.html(a),
                    e._trigger("load", c, i)
                },
                1)
            }).complete(function(a, b) {
                setTimeout(function() {
                    "abort" === b && e.panels.stop(!1, !0),
                    f.removeClass("ui-tabs-loading"),
                    h.removeAttr("aria-busy"),
                    a === e.xhr && delete e.xhr
                },
                1)
            })))
        },
        _ajaxSettings: function(b, c, d) {
            var e = this;
            return {
                url: b.attr("href"),
                beforeSend: function(b, f) {
                    return e._trigger("beforeLoad", c, a.extend({
                        jqXHR: b,
                        ajaxSettings: f
                    },
                    d))
                }
            }
        },
        _getPanelForTab: function(b) {
            var c = a(b).attr("aria-controls");
            return this.element.find(this._sanitizeSelector("#" + c))
        }
    })
} (jQuery),
$(document).ready(function() {
    $(function() {
        $(".animation-toggle").click(function() {
            var a = $(this).attr("data-animation"),
            b = $(this).attr("data-animation-target");
            $(b).addClass("animated"),
            $(b).addClass(a),
            $(b).removeClass("hide"),
            window.setTimeout(function() {
                $(b).removeClass("animated"),
                $(b).removeClass(a),
                $(b).addClass("hide")
            },
            1300)
        })
    })
}),
function() {
    var a, b, c, d, e, f = {}.hasOwnProperty,
    g = function(a, b) {
        function c() {
            this.constructor = a
        }
        for (var d in b) f.call(b, d) && (a[d] = b[d]);
        return c.prototype = b.prototype,
        a.prototype = new c,
        a.__super__ = b.prototype,
        a
    };
    d = function() {
        function a() {
            this.options_index = 0,
            this.parsed = []
        }
        return a.prototype.add_node = function(a) {
            return "OPTGROUP" === a.nodeName.toUpperCase() ? this.add_group(a) : this.add_option(a)
        },
        a.prototype.add_group = function(a) {
            var b, c, d, e, f, g;
            for (b = this.parsed.length, this.parsed.push({
                array_index: b,
                group: !0,
                label: this.escapeExpression(a.label),
                children: 0,
                disabled: a.disabled
            }), f = a.childNodes, g = [], d = 0, e = f.length; e > d; d++) c = f[d],
            g.push(this.add_option(c, b, a.disabled));
            return g
        },
        a.prototype.add_option = function(a, b, c) {
            return "OPTION" === a.nodeName.toUpperCase() ? ("" !== a.text ? (null != b && (this.parsed[b].children += 1), this.parsed.push({
                array_index: this.parsed.length,
                options_index: this.options_index,
                value: a.value,
                text: a.text,
                html: a.innerHTML,
                selected: a.selected,
                disabled: c === !0 ? c: a.disabled,
                group_array_index: b,
                classes: a.className,
                style: a.style.cssText
            })) : this.parsed.push({
                array_index: this.parsed.length,
                options_index: this.options_index,
                empty: !0
            }), this.options_index += 1) : void 0
        },
        a.prototype.escapeExpression = function(a) {
            var b, c;
            return null == a || a === !1 ? "": /[\&\<\>\"\'\`]/.test(a) ? (b = {
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            },
            c = /&(?!\w+;)|[\<\>\"\'\`]/g, a.replace(c,
            function(a) {
                return b[a] || "&amp;"
            })) : a
        },
        a
    } (),
    d.select_to_array = function(a) {
        var b, c, e, f, g;
        for (c = new d, g = a.childNodes, e = 0, f = g.length; f > e; e++) b = g[e],
        c.add_node(b);
        return c.parsed
    },
    b = function() {
        function a(b, c) {
            this.form_field = b,
            this.options = null != c ? c: {},
            a.browser_is_supported() && (this.is_multiple = this.form_field.multiple, this.set_default_text(), this.set_default_values(), this.setup(), this.set_up_html(), this.register_observers())
        }
        return a.prototype.set_default_values = function() {
            var a = this;
            return this.click_test_action = function(b) {
                return a.test_active_click(b)
            },
            this.activate_action = function(b) {
                return a.activate_field(b)
            },
            this.active_field = !1,
            this.mouse_on_container = !1,
            this.results_showing = !1,
            this.result_highlighted = null,
            this.result_single_selected = null,
            this.allow_single_deselect = null != this.options.allow_single_deselect && null != this.form_field.options[0] && "" === this.form_field.options[0].text ? this.options.allow_single_deselect: !1,
            this.disable_search_threshold = this.options.disable_search_threshold || 0,
            this.disable_search = this.options.disable_search || !1,
            this.enable_split_word_search = null != this.options.enable_split_word_search ? this.options.enable_split_word_search: !0,
            this.group_search = null != this.options.group_search ? this.options.group_search: !0,
            this.search_contains = this.options.search_contains || !1,
            this.single_backstroke_delete = null != this.options.single_backstroke_delete ? this.options.single_backstroke_delete: !0,
            this.max_selected_options = this.options.max_selected_options || 1 / 0,
            this.inherit_select_classes = this.options.inherit_select_classes || !1,
            this.display_selected_options = null != this.options.display_selected_options ? this.options.display_selected_options: !0,
            this.display_disabled_options = null != this.options.display_disabled_options ? this.options.display_disabled_options: !0
        },
        a.prototype.set_default_text = function() {
            return this.default_text = this.form_field.getAttribute("data-placeholder") ? this.form_field.getAttribute("data-placeholder") : this.is_multiple ? this.options.placeholder_text_multiple || this.options.placeholder_text || a.default_multiple_text: this.options.placeholder_text_single || this.options.placeholder_text || a.default_single_text,
            this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || a.default_no_result_text
        },
        a.prototype.mouse_enter = function() {
            return this.mouse_on_container = !0
        },
        a.prototype.mouse_leave = function() {
            return this.mouse_on_container = !1
        },
        a.prototype.input_focus = function() {
            var a = this;
            if (this.is_multiple) {
                if (!this.active_field) return setTimeout(function() {
                    return a.container_mousedown()
                },
                50)
            } else if (!this.active_field) return this.activate_field()
        },
        a.prototype.input_blur = function() {
            var a = this;
            return this.mouse_on_container ? void 0 : (this.active_field = !1, setTimeout(function() {
                return a.blur_test()
            },
            100))
        },
        a.prototype.results_option_build = function(a) {
            var b, c, d, e, f;
            for (b = "", f = this.results_data, d = 0, e = f.length; e > d; d++) c = f[d],
            b += c.group ? this.result_add_group(c) : this.result_add_option(c),
            (null != a ? a.first: void 0) && (c.selected && this.is_multiple ? this.choice_build(c) : c.selected && !this.is_multiple && this.single_set_selected_text(c.text));
            return b
        },
        a.prototype.result_add_option = function(a) {
            var b, c;
            return a.search_match ? this.include_option_in_results(a) ? (b = [], a.disabled || a.selected && this.is_multiple || b.push("active-result"), !a.disabled || a.selected && this.is_multiple || b.push("disabled-result"), a.selected && b.push("result-selected"), null != a.group_array_index && b.push("group-option"), "" !== a.classes && b.push(a.classes), c = "" !== a.style.cssText ? ' style="' + a.style + '"': "", '<li class="' + b.join(" ") + '"' + c + ' data-option-array-index="' + a.array_index + '">' + a.search_text + "</li>") : "": ""
        },
        a.prototype.result_add_group = function(a) {
            return a.search_match || a.group_match ? a.active_options > 0 ? '<li class="group-result">' + a.search_text + "</li>": "": ""
        },
        a.prototype.results_update_field = function() {
            return this.set_default_text(),
            this.is_multiple || this.results_reset_cleanup(),
            this.result_clear_highlight(),
            this.result_single_selected = null,
            this.results_build(),
            this.results_showing ? this.winnow_results() : void 0
        },
        a.prototype.results_toggle = function() {
            return this.results_showing ? this.results_hide() : this.results_show()
        },
        a.prototype.results_search = function() {
            return this.results_showing ? this.winnow_results() : this.results_show()
        },
        a.prototype.winnow_results = function() {
            var a, b, c, d, e, f, g, h, i, j, k, l, m;
            for (this.no_results_clear(), e = 0, g = this.get_search_text(), a = g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), d = this.search_contains ? "": "^", c = new RegExp(d + a, "i"), j = new RegExp(a, "i"), m = this.results_data, k = 0, l = m.length; l > k; k++) b = m[k],
            b.search_match = !1,
            f = null,
            this.include_option_in_results(b) && (b.group && (b.group_match = !1, b.active_options = 0), null != b.group_array_index && this.results_data[b.group_array_index] && (f = this.results_data[b.group_array_index], 0 === f.active_options && f.search_match && (e += 1), f.active_options += 1), (!b.group || this.group_search) && (b.search_text = b.group ? b.label: b.html, b.search_match = this.search_string_match(b.search_text, c), b.search_match && !b.group && (e += 1), b.search_match ? (g.length && (h = b.search_text.search(j), i = b.search_text.substr(0, h + g.length) + "</em>" + b.search_text.substr(h + g.length), b.search_text = i.substr(0, h) + "<em>" + i.substr(h)), null != f && (f.group_match = !0)) : null != b.group_array_index && this.results_data[b.group_array_index].search_match && (b.search_match = !0)));
            return this.result_clear_highlight(),
            1 > e && g.length ? (this.update_results_content(""), this.no_results(g)) : (this.update_results_content(this.results_option_build()), this.winnow_results_set_highlight())
        },
        a.prototype.search_string_match = function(a, b) {
            var c, d, e, f;
            if (b.test(a)) return ! 0;
            if (this.enable_split_word_search && (a.indexOf(" ") >= 0 || 0 === a.indexOf("[")) && (d = a.replace(/\[|\]/g, "").split(" "), d.length)) for (e = 0, f = d.length; f > e; e++) if (c = d[e], b.test(c)) return ! 0
        },
        a.prototype.choices_count = function() {
            var a, b, c, d;
            if (null != this.selected_option_count) return this.selected_option_count;
            for (this.selected_option_count = 0, d = this.form_field.options, b = 0, c = d.length; c > b; b++) a = d[b],
            a.selected && (this.selected_option_count += 1);
            return this.selected_option_count
        },
        a.prototype.choices_click = function(a) {
            return a.preventDefault(),
            this.results_showing || this.is_disabled ? void 0 : this.results_show()
        },
        a.prototype.keyup_checker = function(a) {
            var b, c;
            switch (b = null != (c = a.which) ? c: a.keyCode, this.search_field_scale(), b) {
            case 8:
                if (this.is_multiple && this.backstroke_length < 1 && this.choices_count() > 0) return this.keydown_backstroke();
                if (!this.pending_backstroke) return this.result_clear_highlight(),
                this.results_search();
                break;
            case 13:
                if (a.preventDefault(), this.results_showing) return this.result_select(a);
                break;
            case 27:
                return this.results_showing && this.results_hide(),
                !0;
            case 9:
            case 38:
            case 40:
            case 16:
            case 91:
            case 17:
                break;
            default:
                return this.results_search()
            }
        },
        a.prototype.container_width = function() {
            return null != this.options.width ? this.options.width: "" + (this.form_field.offsetWidth?this.form_field.offsetWidth:200) + "px"
        },
        a.prototype.include_option_in_results = function(a) {
            return this.is_multiple && !this.display_selected_options && a.selected ? !1 : !this.display_disabled_options && a.disabled ? !1 : a.empty ? !1 : !0
        },
        a.browser_is_supported = function() {
            return "Microsoft Internet Explorer" === window.navigator.appName ? document.documentMode >= 8 : /iP(od|hone)/i.test(window.navigator.userAgent) ? !1 : /Android/i.test(window.navigator.userAgent) && /Mobile/i.test(window.navigator.userAgent) ? !1 : !0
        },
        a.default_multiple_text = "请选择一个选项",
        a.default_single_text = "请选择一个选项",
        a.default_no_result_text = "没有找到匹配选项",
        a
    } (),
    a = jQuery,
    a.fn.extend({
        chosen: function(d) {
            return b.browser_is_supported() ? this.each(function() {
                var b, e;
                b = a(this),
                e = b.data("chosen"),
                "destroy" === d && e ? e.destroy() : e || b.data("chosen", new c(this, d))
            }) : this
        }
    }),
    c = function(b) {
        function c() {
            return e = c.__super__.constructor.apply(this, arguments)
        }
        return g(c, b),
        c.prototype.setup = function() {
            return this.form_field_jq = a(this.form_field),
            this.current_selectedIndex = this.form_field.selectedIndex,
            this.is_rtl = this.form_field_jq.hasClass("chosen-rtl")
        },
        c.prototype.set_up_html = function() {
            var b, c;
            return b = ["chosen-container"],
            b.push("chosen-container-" + (this.is_multiple ? "multi": "single")),
            this.inherit_select_classes && this.form_field.className && b.push(this.form_field.className),
            this.is_rtl && b.push("chosen-rtl"),
            c = {
                "class": b.join(" "),
                style: "width: " + this.container_width() + ";",
                title: this.form_field.title
            },
            this.form_field.id.length && (c.id = this.form_field.id.replace(/[^\w]/g, "_") + "_chosen"),
            this.container = a("<div />", c),
            this.is_multiple ? this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>') : this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),
            this.form_field_jq.hide().after(this.container),
            this.dropdown = this.container.find("div.chosen-drop").first(),
            this.search_field = this.container.find("input").first(),
            this.search_results = this.container.find("ul.chosen-results").first(),
            this.search_field_scale(),
            this.search_no_results = this.container.find("li.no-results").first(),
            this.is_multiple ? (this.search_choices = this.container.find("ul.chosen-choices").first(), this.search_container = this.container.find("li.search-field").first()) : (this.search_container = this.container.find("div.chosen-search").first(), this.selected_item = this.container.find(".chosen-single").first()),
            this.results_build(),
            this.set_tab_index(),
            this.set_label_behavior(),
            this.form_field_jq.trigger("chosen:ready", {
                chosen: this
            })
        },
        c.prototype.register_observers = function() {
            var a = this;
            return this.container.bind("mousedown.chosen",
            function(b) {
                a.container_mousedown(b)
            }),
            this.container.bind("mouseup.chosen",
            function(b) {
                a.container_mouseup(b)
            }),
            this.container.bind("mouseenter.chosen",
            function(b) {
                a.mouse_enter(b)
            }),
            this.container.bind("mouseleave.chosen",
            function(b) {
                a.mouse_leave(b)
            }),
            this.search_results.bind("mouseup.chosen",
            function(b) {
                a.search_results_mouseup(b)
            }),
            this.search_results.bind("mouseover.chosen",
            function(b) {
                a.search_results_mouseover(b)
            }),
            this.search_results.bind("mouseout.chosen",
            function(b) {
                a.search_results_mouseout(b)
            }),
            this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",
            function(b) {
                a.search_results_mousewheel(b)
            }),
            this.form_field_jq.bind("chosen:updated.chosen",
            function(b) {
                a.results_update_field(b)
            }),
            this.form_field_jq.bind("chosen:activate.chosen",
            function(b) {
                a.activate_field(b)
            }),
            this.form_field_jq.bind("chosen:open.chosen",
            function(b) {
                a.container_mousedown(b)
            }),
            this.search_field.bind("blur.chosen",
            function(b) {
                a.input_blur(b)
            }),
            this.search_field.bind("keyup.chosen",
            function(b) {
                a.keyup_checker(b)
            }),
            this.search_field.bind("keydown.chosen",
            function(b) {
                a.keydown_checker(b)
            }),
            this.search_field.bind("focus.chosen",
            function(b) {
                a.input_focus(b)
            }),
            this.is_multiple ? this.search_choices.bind("click.chosen",
            function(b) {
                a.choices_click(b)
            }) : this.container.bind("click.chosen",
            function(a) {
                a.preventDefault()
            })
        },
        c.prototype.destroy = function() {
            return a(document).unbind("click.chosen", this.click_test_action),
            this.search_field[0].tabIndex && (this.form_field_jq[0].tabIndex = this.search_field[0].tabIndex),
            this.container.remove(),
            this.form_field_jq.removeData("chosen"),
            this.form_field_jq.show()
        },
        c.prototype.search_field_disabled = function() {
            return this.is_disabled = this.form_field_jq[0].disabled,
            this.is_disabled ? (this.container.addClass("chosen-disabled"), this.search_field[0].disabled = !0, this.is_multiple || this.selected_item.unbind("focus.chosen", this.activate_action), this.close_field()) : (this.container.removeClass("chosen-disabled"), this.search_field[0].disabled = !1, this.is_multiple ? void 0 : this.selected_item.bind("focus.chosen", this.activate_action))
        },
        c.prototype.container_mousedown = function(b) {
            return this.is_disabled || (b && "mousedown" === b.type && !this.results_showing && b.preventDefault(), null != b && a(b.target).hasClass("search-choice-close")) ? void 0 : (this.active_field ? this.is_multiple || !b || a(b.target)[0] !== this.selected_item[0] && !a(b.target).parents("a.chosen-single").length || (b.preventDefault(), this.results_toggle()) : (this.is_multiple && this.search_field.val(""), a(document).bind("click.chosen", this.click_test_action), this.results_show()), this.activate_field())
        },
        c.prototype.container_mouseup = function(a) {
            return "ABBR" !== a.target.nodeName || this.is_disabled ? void 0 : this.results_reset(a)
        },
        c.prototype.search_results_mousewheel = function(a) {
            var b, c, d;
            return b = -(null != (c = a.originalEvent) ? c.wheelDelta: void 0) || (null != (d = a.originialEvent) ? d.detail: void 0),
            null != b ? (a.preventDefault(), "DOMMouseScroll" === a.type && (b = 40 * b), this.search_results.scrollTop(b + this.search_results.scrollTop())) : void 0
        },
        c.prototype.blur_test = function() {
            return ! this.active_field && this.container.hasClass("chosen-container-active") ? this.close_field() : void 0
        },
        c.prototype.close_field = function() {
            return a(document).unbind("click.chosen", this.click_test_action),
            this.active_field = !1,
            this.results_hide(),
            this.container.removeClass("chosen-container-active"),
            this.clear_backstroke(),
            this.show_search_field_default(),
            this.search_field_scale()
        },
        c.prototype.activate_field = function() {
            return this.container.addClass("chosen-container-active"),
            this.active_field = !0,
            this.search_field.val(this.search_field.val()),
            this.search_field.focus()
        },
        c.prototype.test_active_click = function(b) {
            return this.container.is(a(b.target).closest(".chosen-container")) ? this.active_field = !0 : this.close_field()
        },
        c.prototype.results_build = function() {
            return this.parsing = !0,
            this.selected_option_count = null,
            this.results_data = d.select_to_array(this.form_field),
            this.is_multiple ? this.search_choices.find("li.search-choice").remove() : this.is_multiple || (this.single_set_selected_text(), this.disable_search || this.form_field.options.length <= this.disable_search_threshold ? (this.search_field[0].readOnly = !0, this.container.addClass("chosen-container-single-nosearch")) : (this.search_field[0].readOnly = !1, this.container.removeClass("chosen-container-single-nosearch"))),
            this.update_results_content(this.results_option_build({
                first: !0
            })),
            this.search_field_disabled(),
            this.show_search_field_default(),
            this.search_field_scale(),
            this.parsing = !1
        },
        c.prototype.result_do_highlight = function(a) {
            var b, c, d, e, f;
            if (a.length) {
                if (this.result_clear_highlight(), this.result_highlight = a, this.result_highlight.addClass("highlighted"), d = parseInt(this.search_results.css("maxHeight"), 10), f = this.search_results.scrollTop(), e = d + f, c = this.result_highlight.position().top + this.search_results.scrollTop(), b = c + this.result_highlight.outerHeight(), b >= e) return this.search_results.scrollTop(b - d > 0 ? b - d: 0);
                if (f > c) return this.search_results.scrollTop(c)
            }
        },
        c.prototype.result_clear_highlight = function() {
            return this.result_highlight && this.result_highlight.removeClass("highlighted"),
            this.result_highlight = null
        },
        c.prototype.results_show = function() {
            return this.is_multiple && this.max_selected_options <= this.choices_count() ? (this.form_field_jq.trigger("chosen:maxselected", {
                chosen: this
            }), !1) : (this.container.addClass("chosen-with-drop"), this.form_field_jq.trigger("chosen:showing_dropdown", {
                chosen: this
            }), this.results_showing = !0, this.search_field.focus(), this.search_field.val(this.search_field.val()), this.winnow_results())
        },
        c.prototype.update_results_content = function(a) {
            return this.search_results.html(a)
        },
        c.prototype.results_hide = function() {
            return this.results_showing && (this.result_clear_highlight(), this.container.removeClass("chosen-with-drop"), this.form_field_jq.trigger("chosen:hiding_dropdown", {
                chosen: this
            })),
            this.results_showing = !1
        },
        c.prototype.set_tab_index = function() {
            var a;
            return this.form_field.tabIndex ? (a = this.form_field.tabIndex, this.form_field.tabIndex = -1, this.search_field[0].tabIndex = a) : void 0
        },
        c.prototype.set_label_behavior = function() {
            var b = this;
            return this.form_field_label = this.form_field_jq.parents("label"),
            !this.form_field_label.length && this.form_field.id.length && (this.form_field_label = a("label[for='" + this.form_field.id + "']")),
            this.form_field_label.length > 0 ? this.form_field_label.bind("click.chosen",
            function(a) {
                return b.is_multiple ? b.container_mousedown(a) : b.activate_field()
            }) : void 0
        },
        c.prototype.show_search_field_default = function() {
            return this.is_multiple && this.choices_count() < 1 && !this.active_field ? (this.search_field.val(this.default_text), this.search_field.addClass("default")) : (this.search_field.val(""), this.search_field.removeClass("default"))
        },
        c.prototype.search_results_mouseup = function(b) {
            var c;
            return c = a(b.target).hasClass("active-result") ? a(b.target) : a(b.target).parents(".active-result").first(),
            c.length ? (this.result_highlight = c, this.result_select(b), this.search_field.focus()) : void 0
        },
        c.prototype.search_results_mouseover = function(b) {
            var c;
            return c = a(b.target).hasClass("active-result") ? a(b.target) : a(b.target).parents(".active-result").first(),
            c ? this.result_do_highlight(c) : void 0
        },
        c.prototype.search_results_mouseout = function(b) {
            return a(b.target).hasClass("active-result") ? this.result_clear_highlight() : void 0
        },
        c.prototype.choice_build = function(b) {
            var c, d, e = this;
            return c = a("<li />", {
                "class": "search-choice"
            }).html("<span>" + b.html + "</span>"),
            b.disabled ? c.addClass("search-choice-disabled") : (d = a("<a />", {
                "class": "search-choice-close",
                "data-option-array-index": b.array_index
            }), d.bind("click.chosen",
            function(a) {
                return e.choice_destroy_link_click(a)
            }), c.append(d)),
            this.search_container.before(c)
        },
        c.prototype.choice_destroy_link_click = function(b) {
            return b.preventDefault(),
            b.stopPropagation(),
            this.is_disabled ? void 0 : this.choice_destroy(a(b.target))
        },
        c.prototype.choice_destroy = function(a) {
            return this.result_deselect(a[0].getAttribute("data-option-array-index")) ? (this.show_search_field_default(), this.is_multiple && this.choices_count() > 0 && this.search_field.val().length < 1 && this.results_hide(), a.parents("li").first().remove(), this.search_field_scale()) : void 0
        },
        c.prototype.results_reset = function() {
            return this.form_field.options[0].selected = !0,
            this.selected_option_count = null,
            this.single_set_selected_text(),
            this.show_search_field_default(),
            this.results_reset_cleanup(),
            this.form_field_jq.trigger("change"),
            this.active_field ? this.results_hide() : void 0
        },
        c.prototype.results_reset_cleanup = function() {
            return this.current_selectedIndex = this.form_field.selectedIndex,
            this.selected_item.find("abbr").remove()
        },
        c.prototype.result_select = function(a) {
            var b, c, d;
            return this.result_highlight ? (b = this.result_highlight, this.result_clear_highlight(), this.is_multiple && this.max_selected_options <= this.choices_count() ? (this.form_field_jq.trigger("chosen:maxselected", {
                chosen: this
            }), !1) : (this.is_multiple ? b.removeClass("active-result") : (this.result_single_selected && (this.result_single_selected.removeClass("result-selected"), d = this.result_single_selected[0].getAttribute("data-option-array-index"), this.results_data[d].selected = !1), this.result_single_selected = b), b.addClass("result-selected"), c = this.results_data[b[0].getAttribute("data-option-array-index")], c.selected = !0, this.form_field.options[c.options_index].selected = !0, this.selected_option_count = null, this.is_multiple ? this.choice_build(c) : this.single_set_selected_text(c.text), (a.metaKey || a.ctrlKey) && this.is_multiple || this.results_hide(), this.search_field.val(""), (this.is_multiple || this.form_field.selectedIndex !== this.current_selectedIndex) && this.form_field_jq.trigger("change", {
                selected: this.form_field.options[c.options_index].value
            }), this.current_selectedIndex = this.form_field.selectedIndex, this.search_field_scale())) : void 0
        },
        c.prototype.single_set_selected_text = function(a) {
            return null == a && (a = this.default_text),
            a === this.default_text ? this.selected_item.addClass("chosen-default") : (this.single_deselect_control_build(), this.selected_item.removeClass("chosen-default")),
            this.selected_item.find("span").text(a)
        },
        c.prototype.result_deselect = function(a) {
            var b;
            return b = this.results_data[a],
            this.form_field.options[b.options_index].disabled ? !1 : (b.selected = !1, this.form_field.options[b.options_index].selected = !1, this.selected_option_count = null, this.result_clear_highlight(), this.results_showing && this.winnow_results(), this.form_field_jq.trigger("change", {
                deselected: this.form_field.options[b.options_index].value
            }), this.search_field_scale(), !0)
        },
        c.prototype.single_deselect_control_build = function() {
            return this.allow_single_deselect ? (this.selected_item.find("abbr").length || this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'), this.selected_item.addClass("chosen-single-with-deselect")) : void 0
        },
        c.prototype.get_search_text = function() {
            return this.search_field.val() === this.default_text ? "": a("<div/>").text(a.trim(this.search_field.val())).html()
        },
        c.prototype.winnow_results_set_highlight = function() {
            var a, b;
            return b = this.is_multiple ? [] : this.search_results.find(".result-selected.active-result"),
            a = b.length ? b.first() : this.search_results.find(".active-result").first(),
            null != a ? this.result_do_highlight(a) : void 0
        },
        c.prototype.no_results = function(b) {
            var c;
            return c = a('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>'),
            c.find("span").first().html(b),
            this.search_results.append(c)
        },
        c.prototype.no_results_clear = function() {
            return this.search_results.find(".no-results").remove()
        },
        c.prototype.keydown_arrow = function() {
            var a;
            return this.results_showing && this.result_highlight ? (a = this.result_highlight.nextAll("li.active-result").first()) ? this.result_do_highlight(a) : void 0 : this.results_show()
        },
        c.prototype.keyup_arrow = function() {
            var a;
            return this.results_showing || this.is_multiple ? this.result_highlight ? (a = this.result_highlight.prevAll("li.active-result"), a.length ? this.result_do_highlight(a.first()) : (this.choices_count() > 0 && this.results_hide(), this.result_clear_highlight())) : void 0 : this.results_show()
        },
        c.prototype.keydown_backstroke = function() {
            var a;
            return this.pending_backstroke ? (this.choice_destroy(this.pending_backstroke.find("a").first()), this.clear_backstroke()) : (a = this.search_container.siblings("li.search-choice").last(), a.length && !a.hasClass("search-choice-disabled") ? (this.pending_backstroke = a, this.single_backstroke_delete ? this.keydown_backstroke() : this.pending_backstroke.addClass("search-choice-focus")) : void 0)
        },
        c.prototype.clear_backstroke = function() {
            return this.pending_backstroke && this.pending_backstroke.removeClass("search-choice-focus"),
            this.pending_backstroke = null
        },
        c.prototype.keydown_checker = function(a) {
            var b, c;
            switch (b = null != (c = a.which) ? c: a.keyCode, this.search_field_scale(), 8 !== b && this.pending_backstroke && this.clear_backstroke(), b) {
            case 8:
                this.backstroke_length = this.search_field.val().length;
                break;
            case 9:
                this.results_showing && !this.is_multiple && this.result_select(a),
                this.mouse_on_container = !1;
                break;
            case 13:
                a.preventDefault();
                break;
            case 38:
                a.preventDefault(),
                this.keyup_arrow();
                break;
            case 40:
                a.preventDefault(),
                this.keydown_arrow()
            }
        },
        c.prototype.search_field_scale = function() {
            var b, c, d, e, f, g, h, i, j;
            if (this.is_multiple) {
                for (d = 0, h = 0, f = "position:absolute; left: -1000px; top: -1000px; display:none;", g = ["font-size", "font-style", "font-weight", "font-family", "line-height", "text-transform", "letter-spacing"], i = 0, j = g.length; j > i; i++) e = g[i],
                f += e + ":" + this.search_field.css(e) + ";";
                return b = a("<div />", {
                    style: f
                }),
                b.text(this.search_field.val()),
                a("body").append(b),
                h = b.width() + 25,
                b.remove(),
                c = this.container.outerWidth(),
                h > c - 10 && (h = c - 10),
                this.search_field.css({
                    width: h + "px"
                })
            }
        },
        c
    } (b)
}.call(this),
jQuery &&
function(a) {
    function b(b, c) {
        var d = a('<span class="minicolors" />'),
        e = a.minicolors.defaultSettings;
        b.data("minicolors-initialized") || (c = a.extend(!0, {},
        e, c), d.addClass("minicolors-theme-" + c.theme).addClass("minicolors-swatch-position-" + c.swatchPosition).toggleClass("minicolors-swatch-left", "left" === c.swatchPosition).toggleClass("minicolors-with-opacity", c.opacity), void 0 !== c.position && a.each(c.position.split(" "),
        function() {
            d.addClass("minicolors-position-" + this)
        }), b.addClass("minicolors-input").data("minicolors-initialized", !0).data("minicolors-settings", c).prop("size", 7).prop("maxlength", 7).wrap(d).after('<span class="minicolors-panel minicolors-slider-' + c.control + '">' + '<span class="minicolors-slider">' + '<span class="minicolors-picker"></span>' + "</span>" + '<span class="minicolors-opacity-slider">' + '<span class="minicolors-picker"></span>' + "</span>" + '<span class="minicolors-grid">' + '<span class="minicolors-grid-inner"></span>' + '<span class="minicolors-picker"><span></span></span>' + "</span>" + "</span>"), b.parent().find(".minicolors-panel").on("selectstart",
        function() {
            return ! 1
        }).end(), "left" === c.swatchPosition ? b.before('<span class="minicolors-swatch"><span></span></span>') : b.after('<span class="minicolors-swatch"><span></span></span>'), c.textfield || b.addClass("minicolors-hidden"), c.inline && b.parent().addClass("minicolors-inline"), i(b, !1, !0))
    }
    function c(a) {
        var b = a.parent();
        a.removeData("minicolors-initialized").removeData("minicolors-settings").removeProp("size").prop("maxlength", null).removeClass("minicolors-input"),
        b.before(a).remove()
    }
    function d(a) {
        i(a)
    }
    function e(a) {
        var b = a.parent(),
        c = b.find(".minicolors-panel"),
        d = a.data("minicolors-settings"); ! a.data("minicolors-initialized") || a.prop("disabled") || b.hasClass("minicolors-inline") || b.hasClass("minicolors-focus") || (f(), b.addClass("minicolors-focus"), c.stop(!0, !0).fadeIn(d.showSpeed,
        function() {
            d.show && d.show.call(a.get(0))
        }))
    }
    function f() {
        a(".minicolors-input").each(function() {
            var b = a(this),
            c = b.data("minicolors-settings"),
            d = b.parent();
            c.inline || d.find(".minicolors-panel").fadeOut(c.hideSpeed,
            function() {
                d.hasClass("minicolors-focus") && c.hide && c.hide.call(b.get(0)),
                d.removeClass("minicolors-focus")
            })
        })
    }
    function g(a, b, c) {
        var d, e, f, g, i = a.parents(".minicolors").find(".minicolors-input"),
        j = i.data("minicolors-settings"),
        k = a.find("[class$=-picker]"),
        l = a.offset().left,
        m = a.offset().top,
        n = Math.round(b.pageX - l),
        o = Math.round(b.pageY - m),
        p = c ? j.animationSpeed: 0;
        b.originalEvent.changedTouches && (n = b.originalEvent.changedTouches[0].pageX - l, o = b.originalEvent.changedTouches[0].pageY - m),
        0 > n && (n = 0),
        0 > o && (o = 0),
        n > a.width() && (n = a.width()),
        o > a.height() && (o = a.height()),
        a.parent().is(".minicolors-slider-wheel") && k.parent().is(".minicolors-grid") && (d = 75 - n, e = 75 - o, f = Math.sqrt(d * d + e * e), g = Math.atan2(e, d), 0 > g && (g += 2 * Math.PI), f > 75 && (f = 75, n = 75 - 75 * Math.cos(g), o = 75 - 75 * Math.sin(g)), n = Math.round(n), o = Math.round(o)),
        a.is(".minicolors-grid") ? k.stop(!0).animate({
            top: o + "px",
            left: n + "px"
        },
        p, j.animationEasing,
        function() {
            h(i, a)
        }) : k.stop(!0).animate({
            top: o + "px"
        },
        p, j.animationEasing,
        function() {
            h(i, a)
        })
    }
    function h(a, b) {
        function c(a, b) {
            var c, d;
            return a.length && b ? (c = a.offset().left, d = a.offset().top, {
                x: c - b.offset().left + a.outerWidth() / 2,
                y: d - b.offset().top + a.outerHeight() / 2
            }) : null
        }
        var d, e, f, g, h, i, k, l = a.val(),
        n = a.attr("data-opacity"),
        p = a.parent(),
        q = a.data("minicolors-settings"),
        s = (p.find(".minicolors-panel"), p.find(".minicolors-swatch")),
        t = p.find(".minicolors-grid"),
        u = p.find(".minicolors-slider"),
        v = p.find(".minicolors-opacity-slider"),
        w = t.find("[class$=-picker]"),
        x = u.find("[class$=-picker]"),
        y = v.find("[class$=-picker]"),
        z = c(w, t),
        A = c(x, u),
        B = c(y, v);
        if (b.is(".minicolors-grid, .minicolors-slider")) {
            switch (q.control) {
            case "wheel":
                g = t.width() / 2 - z.x,
                h = t.height() / 2 - z.y,
                i = Math.sqrt(g * g + h * h),
                k = Math.atan2(h, g),
                0 > k && (k += 2 * Math.PI),
                i > 75 && (i = 75, z.x = 69 - 75 * Math.cos(k), z.y = 69 - 75 * Math.sin(k)),
                e = o(i / .75, 0, 100),
                d = o(180 * k / Math.PI, 0, 360),
                f = o(100 - Math.floor(A.y * (100 / u.height())), 0, 100),
                l = r({
                    h: d,
                    s: e,
                    b: f
                }),
                u.css("backgroundColor", r({
                    h: d,
                    s: e,
                    b: 100
                }));
                break;
            case "saturation":
                d = o(parseInt(z.x * (360 / t.width())), 0, 360),
                e = o(100 - Math.floor(A.y * (100 / u.height())), 0, 100),
                f = o(100 - Math.floor(z.y * (100 / t.height())), 0, 100),
                l = r({
                    h: d,
                    s: e,
                    b: f
                }),
                u.css("backgroundColor", r({
                    h: d,
                    s: 100,
                    b: f
                })),
                p.find(".minicolors-grid-inner").css("opacity", e / 100);
                break;
            case "brightness":
                d = o(parseInt(z.x * (360 / t.width())), 0, 360),
                e = o(100 - Math.floor(z.y * (100 / t.height())), 0, 100),
                f = o(100 - Math.floor(A.y * (100 / u.height())), 0, 100),
                l = r({
                    h: d,
                    s: e,
                    b: f
                }),
                u.css("backgroundColor", r({
                    h: d,
                    s: e,
                    b: 100
                })),
                p.find(".minicolors-grid-inner").css("opacity", 1 - f / 100);
                break;
            default:
                d = o(360 - parseInt(A.y * (360 / u.height())), 0, 360),
                e = o(Math.floor(z.x * (100 / t.width())), 0, 100),
                f = o(100 - Math.floor(z.y * (100 / t.height())), 0, 100),
                l = r({
                    h: d,
                    s: e,
                    b: f
                }),
                t.css("backgroundColor", r({
                    h: d,
                    s: 100,
                    b: 100
                }))
            }
            a.val(m(l, q.letterCase))
        }
        b.is(".minicolors-opacity-slider") && (n = q.opacity ? parseFloat(1 - B.y / v.height()).toFixed(2) : 1, q.opacity && a.attr("data-opacity", n)),
        s.find("SPAN").css({
            backgroundColor: l,
            opacity: n
        }),
        j(a, l, n)
    }
    function i(a, b, c) {
        var d, e, f, g, h, i, k, l = a.parent(),
        p = a.data("minicolors-settings"),
        q = l.find(".minicolors-swatch"),
        t = l.find(".minicolors-grid"),
        u = l.find(".minicolors-slider"),
        v = l.find(".minicolors-opacity-slider"),
        w = t.find("[class$=-picker]"),
        x = u.find("[class$=-picker]"),
        y = v.find("[class$=-picker]");
        switch (d = m(n(a.val(), !0), p.letterCase), d || (d = m(n(p.defaultValue, !0))), e = s(d), b || a.val(d), p.opacity && (f = "" === a.attr("data-opacity") ? 1 : o(parseFloat(a.attr("data-opacity")).toFixed(2), 0, 1), isNaN(f) && (f = 1), a.attr("data-opacity", f), q.find("SPAN").css("opacity", f), h = o(v.height() - v.height() * f, 0, v.height()), y.css("top", h + "px")), q.find("SPAN").css("backgroundColor", d), p.control) {
        case "wheel":
            i = o(Math.ceil(.75 * e.s), 0, t.height() / 2),
            k = e.h * Math.PI / 180,
            g = o(75 - Math.cos(k) * i, 0, t.width()),
            h = o(75 - Math.sin(k) * i, 0, t.height()),
            w.css({
                top: h + "px",
                left: g + "px"
            }),
            h = 150 - e.b / (100 / t.height()),
            "" === d && (h = 0),
            x.css("top", h + "px"),
            u.css("backgroundColor", r({
                h: e.h,
                s: e.s,
                b: 100
            }));
            break;
        case "saturation":
            g = o(5 * e.h / 12, 0, 150),
            h = o(t.height() - Math.ceil(e.b / (100 / t.height())), 0, t.height()),
            w.css({
                top: h + "px",
                left: g + "px"
            }),
            h = o(u.height() - e.s * (u.height() / 100), 0, u.height()),
            x.css("top", h + "px"),
            u.css("backgroundColor", r({
                h: e.h,
                s: 100,
                b: e.b
            })),
            l.find(".minicolors-grid-inner").css("opacity", e.s / 100);
            break;
        case "brightness":
            g = o(5 * e.h / 12, 0, 150),
            h = o(t.height() - Math.ceil(e.s / (100 / t.height())), 0, t.height()),
            w.css({
                top: h + "px",
                left: g + "px"
            }),
            h = o(u.height() - e.b * (u.height() / 100), 0, u.height()),
            x.css("top", h + "px"),
            u.css("backgroundColor", r({
                h: e.h,
                s: e.s,
                b: 100
            })),
            l.find(".minicolors-grid-inner").css("opacity", 1 - e.b / 100);
            break;
        default:
            g = o(Math.ceil(e.s / (100 / t.width())), 0, t.width()),
            h = o(t.height() - Math.ceil(e.b / (100 / t.height())), 0, t.height()),
            w.css({
                top: h + "px",
                left: g + "px"
            }),
            h = o(u.height() - e.h / (360 / u.height()), 0, u.height()),
            x.css("top", h + "px"),
            t.css("backgroundColor", r({
                h: e.h,
                s: 100,
                b: 100
            }))
        }
        c || j(a, d, f)
    }
    function j(a, b, c) {
        var d = a.data("minicolors-settings");
        b + c !== a.data("minicolors-lastChange") && (a.data("minicolors-lastChange", b + c), d.change && (d.changeDelay ? (clearTimeout(a.data("minicolors-changeTimeout")), a.data("minicolors-changeTimeout", setTimeout(function() {
            d.change.call(a.get(0), b, c)
        },
        d.changeDelay))) : d.change.call(a.get(0), b, c)))
    }
    function k(b) {
        var c = n(a(b).val(), !0),
        d = u(c),
        e = a(b).attr("data-opacity");
        return d ? (void 0 !== e && a.extend(d, {
            a: parseFloat(e)
        }), d) : null
    }
    function l(b, c) {
        var d = n(a(b).val(), !0),
        e = u(d),
        f = a(b).attr("data-opacity");
        return e ? (void 0 === f && (f = 1), c ? "rgba(" + e.r + ", " + e.g + ", " + e.b + ", " + parseFloat(f) + ")": "rgb(" + e.r + ", " + e.g + ", " + e.b + ")") : null
    }
    function m(a, b) {
        return "uppercase" === b ? a.toUpperCase() : a.toLowerCase()
    }
    function n(a, b) {
        return a = a.replace(/[^A-F0-9]/gi, ""),
        3 !== a.length && 6 !== a.length ? "": (3 === a.length && b && (a = a[0] + a[0] + a[1] + a[1] + a[2] + a[2]), "#" + a)
    }
    function o(a, b, c) {
        return b > a && (a = b),
        a > c && (a = c),
        a
    }
    function p(a) {
        var b = {},
        c = Math.round(a.h),
        d = Math.round(255 * a.s / 100),
        e = Math.round(255 * a.b / 100);
        if (0 === d) b.r = b.g = b.b = e;
        else {
            var f = e,
            g = (255 - d) * e / 255,
            h = (f - g) * (c % 60) / 60;
            360 === c && (c = 0),
            60 > c ? (b.r = f, b.b = g, b.g = g + h) : 120 > c ? (b.g = f, b.b = g, b.r = f - h) : 180 > c ? (b.g = f, b.r = g, b.b = g + h) : 240 > c ? (b.b = f, b.r = g, b.g = f - h) : 300 > c ? (b.b = f, b.g = g, b.r = g + h) : 360 > c ? (b.r = f, b.g = g, b.b = f - h) : (b.r = 0, b.g = 0, b.b = 0)
        }
        return {
            r: Math.round(b.r),
            g: Math.round(b.g),
            b: Math.round(b.b)
        }
    }
    function q(b) {
        var c = [b.r.toString(16), b.g.toString(16), b.b.toString(16)];
        return a.each(c,
        function(a, b) {
            1 === b.length && (c[a] = "0" + b)
        }),
        "#" + c.join("")
    }
    function r(a) {
        return q(p(a))
    }
    function s(a) {
        var b = t(u(a));
        return 0 === b.s && (b.h = 360),
        b
    }
    function t(a) {
        var b = {
            h: 0,
            s: 0,
            b: 0
        },
        c = Math.min(a.r, a.g, a.b),
        d = Math.max(a.r, a.g, a.b),
        e = d - c;
        return b.b = d,
        b.s = 0 !== d ? 255 * e / d: 0,
        b.h = 0 !== b.s ? a.r === d ? (a.g - a.b) / e: a.g === d ? 2 + (a.b - a.r) / e: 4 + (a.r - a.g) / e: -1,
        b.h *= 60,
        b.h < 0 && (b.h += 360),
        b.s *= 100 / 255,
        b.b *= 100 / 255,
        b
    }
    function u(a) {
        return a = parseInt(a.indexOf("#") > -1 ? a.substring(1) : a, 16),
        {
            r: a >> 16,
            g: (65280 & a) >> 8,
            b: 255 & a
        }
    }
    a.minicolors = {
        defaultSettings: {
            animationSpeed: 100,
            animationEasing: "swing",
            change: null,
            changeDelay: 0,
            control: "hue",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !1,
            letterCase: "lowercase",
            opacity: !1,
            position: "default",
            show: null,
            showSpeed: 100,
            swatchPosition: "left",
            textfield: !0,
            theme: "default"
        }
    },
    a.extend(a.fn, {
        minicolors: function(g, h) {
            switch (g) {
            case "destroy":
                return a(this).each(function() {
                    c(a(this))
                }),
                a(this);
            case "hide":
                return f(),
                a(this);
            case "opacity":
                return void 0 === h ? a(this).attr("data-opacity") : (a(this).each(function() {
                    d(a(this).attr("data-opacity", h))
                }), a(this));
            case "rgbObject":
                return k(a(this), "rgbaObject" === g);
            case "rgbString":
            case "rgbaString":
                return l(a(this), "rgbaString" === g);
            case "settings":
                return void 0 === h ? a(this).data("minicolors-settings") : (a(this).each(function() {
                    var b = a(this).data("minicolors-settings") || {};
                    c(a(this)),
                    a(this).minicolors(a.extend(!0, b, h))
                }), a(this));
            case "show":
                return e(a(this).eq(0)),
                a(this);
            case "value":
                return void 0 === h ? a(this).val() : (a(this).each(function() {
                    d(a(this).val(h))
                }), a(this));
            case "create":
            default:
                return "create" !== g && (h = g),
                a(this).each(function() {
                    b(a(this), h)
                }),
                a(this)
            }
        }
    }),
    a(document).on("mousedown.minicolors touchstart.minicolors",
    function(b) {
        a(b.target).parents().add(b.target).hasClass("minicolors") || f()
    }).on("mousedown.minicolors touchstart.minicolors", ".minicolors-grid, .minicolors-slider, .minicolors-opacity-slider",
    function(b) {
        var c = a(this);
        b.preventDefault(),
        a(document).data("minicolors-target", c),
        g(c, b, !0)
    }).on("mousemove.minicolors touchmove.minicolors",
    function(b) {
        var c = a(document).data("minicolors-target");
        c && g(c, b)
    }).on("mouseup.minicolors touchend.minicolors",
    function() {
        a(this).removeData("minicolors-target")
    }).on("mousedown.minicolors touchstart.minicolors", ".minicolors-swatch",
    function(b) {
        b.preventDefault();
        var c = a(this).parent().find(".minicolors-input"),
        d = c.parent();
        d.hasClass("minicolors-focus") ? f(c) : e(c)
    }).on("focus.minicolors", ".minicolors-input",
    function() {
        var b = a(this);
        b.data("minicolors-initialized") && e(b)
    }).on("blur.minicolors", ".minicolors-input",
    function() {
        var b = a(this),
        c = b.data("minicolors-settings");
        b.data("minicolors-initialized") && (b.val(n(b.val(), !0)), "" === b.val() && b.val(n(c.defaultValue, !0)), b.val(m(b.val(), c.letterCase)))
    }).on("keydown.minicolors", ".minicolors-input",
    function(b) {
        var c = a(this);
        if (c.data("minicolors-initialized")) switch (b.keyCode) {
        case 9:
            f();
            break;
        case 13:
        case 27:
            f(),
            c.blur()
        }
    }).on("keyup.minicolors", ".minicolors-input",
    function() {
        var b = a(this);
        b.data("minicolors-initialized") && i(b, !0)
    }).on("paste.minicolors", ".minicolors-input",
    function() {
        var b = a(this);
        b.data("minicolors-initialized") && setTimeout(function() {
            i(b, !0)
        },
        1)
    })
} (jQuery),
function(window, document, undefined) { !
    function(a) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], a) : jQuery && !jQuery.fn.dataTable && a(jQuery)
    } (function($) {
        "use strict";
        var DataTable = function(oInit) {
            function _fnAddColumn(a, b) {
                var c = DataTable.defaults.columns,
                d = a.aoColumns.length,
                e = $.extend({},
                DataTable.models.oColumn, c, {
                    sSortingClass: a.oClasses.sSortable,
                    sSortingClassJUI: a.oClasses.sSortJUI,
                    nTh: b ? b: document.createElement("th"),
                    sTitle: c.sTitle ? c.sTitle: b ? b.innerHTML: "",
                    aDataSort: c.aDataSort ? c.aDataSort: [d],
                    mData: c.mData ? c.oDefaults: d
                });
                if (a.aoColumns.push(e), a.aoPreSearchCols[d] === undefined || null === a.aoPreSearchCols[d]) a.aoPreSearchCols[d] = $.extend({},
                DataTable.models.oSearch);
                else {
                    var f = a.aoPreSearchCols[d];
                    f.bRegex === undefined && (f.bRegex = !0),
                    f.bSmart === undefined && (f.bSmart = !0),
                    f.bCaseInsensitive === undefined && (f.bCaseInsensitive = !0)
                }
                _fnColumnOptions(a, d, null)
            }
            function _fnColumnOptions(a, b, c) {
                var d = a.aoColumns[b];
                c !== undefined && null !== c && (c.mDataProp && !c.mData && (c.mData = c.mDataProp), c.sType !== undefined && (d.sType = c.sType, d._bAutoType = !1), $.extend(d, c), _fnMap(d, c, "sWidth", "sWidthOrig"), c.iDataSort !== undefined && (d.aDataSort = [c.iDataSort]), _fnMap(d, c, "aDataSort"));
                var e = d.mRender ? _fnGetObjectDataFn(d.mRender) : null,
                f = _fnGetObjectDataFn(d.mData);
                d.fnGetData = function(a, b) {
                    var c = f(a, b);
                    return d.mRender && b && "" !== b ? e(c, b, a) : c
                },
                d.fnSetData = _fnSetObjectDataFn(d.mData),
                a.oFeatures.bSort || (d.bSortable = !1),
                !d.bSortable || -1 == $.inArray("asc", d.asSorting) && -1 == $.inArray("desc", d.asSorting) ? (d.sSortingClass = a.oClasses.sSortableNone, d.sSortingClassJUI = "") : -1 == $.inArray("asc", d.asSorting) && -1 == $.inArray("desc", d.asSorting) ? (d.sSortingClass = a.oClasses.sSortable, d.sSortingClassJUI = a.oClasses.sSortJUI) : -1 != $.inArray("asc", d.asSorting) && -1 == $.inArray("desc", d.asSorting) ? (d.sSortingClass = a.oClasses.sSortableAsc, d.sSortingClassJUI = a.oClasses.sSortJUIAscAllowed) : -1 == $.inArray("asc", d.asSorting) && -1 != $.inArray("desc", d.asSorting) && (d.sSortingClass = a.oClasses.sSortableDesc, d.sSortingClassJUI = a.oClasses.sSortJUIDescAllowed)
            }
            function _fnAdjustColumnSizing(a) {
                if (a.oFeatures.bAutoWidth === !1) return ! 1;
                _fnCalculateColumnWidths(a);
                for (var b = 0,
                c = a.aoColumns.length; c > b; b++) a.aoColumns[b].nTh.style.width = a.aoColumns[b].sWidth
            }
            function _fnVisibleToColumnIndex(a, b) {
                var c = _fnGetColumns(a, "bVisible");
                return "number" == typeof c[b] ? c[b] : null
            }
            function _fnColumnIndexToVisible(a, b) {
                var c = _fnGetColumns(a, "bVisible"),
                d = $.inArray(b, c);
                return - 1 !== d ? d: null
            }
            function _fnVisbleColumns(a) {
                return _fnGetColumns(a, "bVisible").length
            }
            function _fnGetColumns(a, b) {
                var c = [];
                return $.map(a.aoColumns,
                function(a, d) {
                    a[b] && c.push(d)
                }),
                c
            }
            function _fnDetectType(a) {
                for (var b = DataTable.ext.aTypes,
                c = b.length,
                d = 0; c > d; d++) {
                    var e = b[d](a);
                    if (null !== e) return e
                }
                return "string"
            }
            function _fnReOrderIndex(a, b) {
                for (var c = b.split(","), d = [], e = 0, f = a.aoColumns.length; f > e; e++) for (var g = 0; f > g; g++) if (a.aoColumns[e].sName == c[g]) {
                    d.push(g);
                    break
                }
                return d
            }
            function _fnColumnOrdering(a) {
                for (var b = "",
                c = 0,
                d = a.aoColumns.length; d > c; c++) b += a.aoColumns[c].sName + ",";
                return b.length == d ? "": b.slice(0, -1)
            }
            function _fnApplyColumnDefs(a, b, c, d) {
                var e, f, g, h, i, j;
                if (b) for (e = b.length - 1; e >= 0; e--) {
                    var k = b[e].aTargets;
                    for ($.isArray(k) || _fnLog(a, 1, "aTargets must be an array of targets, not a " + typeof k), g = 0, h = k.length; h > g; g++) if ("number" == typeof k[g] && k[g] >= 0) {
                        for (; a.aoColumns.length <= k[g];) _fnAddColumn(a);
                        d(k[g], b[e])
                    } else if ("number" == typeof k[g] && k[g] < 0) d(a.aoColumns.length + k[g], b[e]);
                    else if ("string" == typeof k[g]) for (i = 0, j = a.aoColumns.length; j > i; i++)("_all" == k[g] || $(a.aoColumns[i].nTh).hasClass(k[g])) && d(i, b[e])
                }
                if (c) for (e = 0, f = c.length; f > e; e++) d(e, c[e])
            }
            function _fnAddData(a, b) {
                var c, d = $.isArray(b) ? b.slice() : $.extend(!0, {},
                b),
                e = a.aoData.length,
                f = $.extend(!0, {},
                DataTable.models.oRow);
                f._aData = d,
                a.aoData.push(f);
                for (var g, h = 0,
                i = a.aoColumns.length; i > h; h++) if (c = a.aoColumns[h], "function" == typeof c.fnRender && c.bUseRendered && null !== c.mData ? _fnSetCellData(a, e, h, _fnRender(a, e, h)) : _fnSetCellData(a, e, h, _fnGetCellData(a, e, h)), c._bAutoType && "string" != c.sType) {
                    var j = _fnGetCellData(a, e, h, "type");
                    null !== j && "" !== j && (g = _fnDetectType(j), null === c.sType ? c.sType = g: c.sType != g && "html" != c.sType && (c.sType = "string"))
                }
                return a.aiDisplayMaster.push(e),
                a.oFeatures.bDeferRender || _fnCreateTr(a, e),
                e
            }
            function _fnGatherData(a) {
                var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p;
                if (a.bDeferLoading || null === a.sAjaxSource) for (h = a.nTBody.firstChild; h;) {
                    if ("TR" == h.nodeName.toUpperCase()) for (i = a.aoData.length, h._DT_RowIndex = i, a.aoData.push($.extend(!0, {},
                    DataTable.models.oRow, {
                        nTr: h
                    })), a.aiDisplayMaster.push(i), g = h.firstChild, d = 0; g;) n = g.nodeName.toUpperCase(),
                    ("TD" == n || "TH" == n) && (_fnSetCellData(a, i, d, $.trim(g.innerHTML)), d++),
                    g = g.nextSibling;
                    h = h.nextSibling
                }
                for (f = _fnGetTrNodes(a), e = [], b = 0, c = f.length; c > b; b++) for (g = f[b].firstChild; g;) n = g.nodeName.toUpperCase(),
                ("TD" == n || "TH" == n) && e.push(g),
                g = g.nextSibling;
                for (l = 0, m = a.aoColumns.length; m > l; l++) {
                    o = a.aoColumns[l],
                    null === o.sTitle && (o.sTitle = o.nTh.innerHTML);
                    var q, r, s, t, u = o._bAutoType,
                    v = "function" == typeof o.fnRender,
                    w = null !== o.sClass,
                    x = o.bVisible;
                    if (u || v || w || !x) for (j = 0, k = a.aoData.length; k > j; j++) p = a.aoData[j],
                    q = e[j * m + l],
                    u && "string" != o.sType && (t = _fnGetCellData(a, j, l, "type"), "" !== t && (r = _fnDetectType(t), null === o.sType ? o.sType = r: o.sType != r && "html" != o.sType && (o.sType = "string"))),
                    o.mRender ? q.innerHTML = _fnGetCellData(a, j, l, "display") : o.mData !== l && (q.innerHTML = _fnGetCellData(a, j, l, "display")),
                    v && (s = _fnRender(a, j, l), q.innerHTML = s, o.bUseRendered && _fnSetCellData(a, j, l, s)),
                    w && (q.className += " " + o.sClass),
                    x ? p._anHidden[l] = null: (p._anHidden[l] = q, q.parentNode.removeChild(q)),
                    o.fnCreatedCell && o.fnCreatedCell.call(a.oInstance, q, _fnGetCellData(a, j, l, "display"), p._aData, j, l)
                }
                if (0 !== a.aoRowCreatedCallback.length) for (b = 0, c = a.aoData.length; c > b; b++) p = a.aoData[b],
                _fnCallbackFire(a, "aoRowCreatedCallback", null, [p.nTr, p._aData, b])
            }
            function _fnNodeToDataIndex(a, b) {
                return b._DT_RowIndex !== undefined ? b._DT_RowIndex: null
            }
            function _fnNodeToColumnIndex(a, b, c) {
                for (var d = _fnGetTdNodes(a, b), e = 0, f = a.aoColumns.length; f > e; e++) if (d[e] === c) return e;
                return - 1
            }
            function _fnGetRowData(a, b, c, d) {
                for (var e = [], f = 0, g = d.length; g > f; f++) e.push(_fnGetCellData(a, b, d[f], c));
                return e
            }
            function _fnGetCellData(a, b, c, d) {
                var e, f = a.aoColumns[c],
                g = a.aoData[b]._aData;
                if ((e = f.fnGetData(g, d)) === undefined) return a.iDrawError != a.iDraw && null === f.sDefaultContent && (_fnLog(a, 0, "Requested unknown parameter " + ("function" == typeof f.mData ? "{mData function}": "'" + f.mData + "'") + " from the data source for row " + b), a.iDrawError = a.iDraw),
                f.sDefaultContent;
                if (null === e && null !== f.sDefaultContent) e = f.sDefaultContent;
                else if ("function" == typeof e) return e();
                return "display" == d && null === e ? "": e
            }
            function _fnSetCellData(a, b, c, d) {
                var e = a.aoColumns[c],
                f = a.aoData[b]._aData;
                e.fnSetData(f, d)
            }
            function _fnGetObjectDataFn(a) {
                if (null === a) return function() {
                    return null
                };
                if ("function" == typeof a) return function(b, c, d) {
                    return a(b, c, d)
                };
                if ("string" != typeof a || -1 === a.indexOf(".") && -1 === a.indexOf("[")) return function(b) {
                    return b[a]
                };
                var b = function(a, c, d) {
                    var e, f, g, h = d.split(".");
                    if ("" !== d) for (var i = 0,
                    j = h.length; j > i; i++) {
                        if (e = h[i].match(__reArray)) {
                            h[i] = h[i].replace(__reArray, ""),
                            "" !== h[i] && (a = a[h[i]]),
                            f = [],
                            h.splice(0, i + 1),
                            g = h.join(".");
                            for (var k = 0,
                            l = a.length; l > k; k++) f.push(b(a[k], c, g));
                            var m = e[0].substring(1, e[0].length - 1);
                            a = "" === m ? f: f.join(m);
                            break
                        }
                        if (null === a || a[h[i]] === undefined) return undefined;
                        a = a[h[i]]
                    }
                    return a
                };
                return function(c, d) {
                    return b(c, d, a)
                }
            }
            function _fnSetObjectDataFn(a) {
                if (null === a) return function() {};
                if ("function" == typeof a) return function(b, c) {
                    a(b, "set", c)
                };
                if ("string" != typeof a || -1 === a.indexOf(".") && -1 === a.indexOf("[")) return function(b, c) {
                    b[a] = c
                };
                var b = function(a, c, d) {
                    for (var e, f, g, h, i = d.split("."), j = 0, k = i.length - 1; k > j; j++) {
                        if (f = i[j].match(__reArray)) {
                            i[j] = i[j].replace(__reArray, ""),
                            a[i[j]] = [],
                            e = i.slice(),
                            e.splice(0, j + 1),
                            h = e.join(".");
                            for (var l = 0,
                            m = c.length; m > l; l++) g = {},
                            b(g, c[l], h),
                            a[i[j]].push(g);
                            return
                        } (null === a[i[j]] || a[i[j]] === undefined) && (a[i[j]] = {}),
                        a = a[i[j]]
                    }
                    a[i[i.length - 1].replace(__reArray, "")] = c
                };
                return function(c, d) {
                    return b(c, d, a)
                }
            }
            function _fnGetDataMaster(a) {
                for (var b = [], c = a.aoData.length, d = 0; c > d; d++) b.push(a.aoData[d]._aData);
                return b
            }
            function _fnClearTable(a) {
                a.aoData.splice(0, a.aoData.length),
                a.aiDisplayMaster.splice(0, a.aiDisplayMaster.length),
                a.aiDisplay.splice(0, a.aiDisplay.length),
                _fnCalculateEnd(a)
            }
            function _fnDeleteIndex(a, b) {
                for (var c = -1,
                d = 0,
                e = a.length; e > d; d++) a[d] == b ? c = d: a[d] > b && a[d]--; - 1 != c && a.splice(c, 1)
            }
            function _fnRender(a, b, c) {
                var d = a.aoColumns[c];
                return d.fnRender({
                    iDataRow: b,
                    iDataColumn: c,
                    oSettings: a,
                    aData: a.aoData[b]._aData,
                    mDataProp: d.mData
                },
                _fnGetCellData(a, b, c, "display"))
            }
            function _fnCreateTr(a, b) {
                var c, d = a.aoData[b];
                if (null === d.nTr) {
                    d.nTr = document.createElement("tr"),
                    d.nTr._DT_RowIndex = b,
                    d._aData.DT_RowId && (d.nTr.id = d._aData.DT_RowId),
                    d._aData.DT_RowClass && (d.nTr.className = d._aData.DT_RowClass);
                    for (var e = 0,
                    f = a.aoColumns.length; f > e; e++) {
                        var g = a.aoColumns[e];
                        c = document.createElement(g.sCellType),
                        c.innerHTML = "function" != typeof g.fnRender || g.bUseRendered && null !== g.mData ? _fnGetCellData(a, b, e, "display") : _fnRender(a, b, e),
                        null !== g.sClass && (c.className = g.sClass),
                        g.bVisible ? (d.nTr.appendChild(c), d._anHidden[e] = null) : d._anHidden[e] = c,
                        g.fnCreatedCell && g.fnCreatedCell.call(a.oInstance, c, _fnGetCellData(a, b, e, "display"), d._aData, b, e)
                    }
                    _fnCallbackFire(a, "aoRowCreatedCallback", null, [d.nTr, d._aData, b])
                }
            }
            function _fnBuildHead(a) {
                var b, c, d, e = $("th, td", a.nTHead).length;
                if (0 !== e) for (b = 0, d = a.aoColumns.length; d > b; b++) c = a.aoColumns[b].nTh,
                c.setAttribute("role", "columnheader"),
                a.aoColumns[b].bSortable && (c.setAttribute("tabindex", a.iTabIndex), c.setAttribute("aria-controls", a.sTableId)),
                null !== a.aoColumns[b].sClass && $(c).addClass(a.aoColumns[b].sClass),
                a.aoColumns[b].sTitle != c.innerHTML && (c.innerHTML = a.aoColumns[b].sTitle);
                else {
                    var f = document.createElement("tr");
                    for (b = 0, d = a.aoColumns.length; d > b; b++) c = a.aoColumns[b].nTh,
                    c.innerHTML = a.aoColumns[b].sTitle,
                    c.setAttribute("tabindex", "0"),
                    null !== a.aoColumns[b].sClass && $(c).addClass(a.aoColumns[b].sClass),
                    f.appendChild(c);
                    $(a.nTHead).html("")[0].appendChild(f),
                    _fnDetectHeader(a.aoHeader, a.nTHead)
                }
                if ($(a.nTHead).children("tr").attr("role", "row"), a.bJUI) for (b = 0, d = a.aoColumns.length; d > b; b++) {
                    c = a.aoColumns[b].nTh;
                    var g = document.createElement("div");
                    g.className = a.oClasses.sSortJUIWrapper,
                    $(c).contents().appendTo(g);
                    var h = document.createElement("span");
                    h.className = a.oClasses.sSortIcon,
                    g.appendChild(h),
                    c.appendChild(g)
                }
                if (a.oFeatures.bSort) for (b = 0; b < a.aoColumns.length; b++) a.aoColumns[b].bSortable !== !1 ? _fnSortAttachListener(a, a.aoColumns[b].nTh, b) : $(a.aoColumns[b].nTh).addClass(a.oClasses.sSortableNone);
                if ("" !== a.oClasses.sFooterTH && $(a.nTFoot).children("tr").children("th").addClass(a.oClasses.sFooterTH), null !== a.nTFoot) {
                    var i = _fnGetUniqueThs(a, null, a.aoFooter);
                    for (b = 0, d = a.aoColumns.length; d > b; b++) i[b] && (a.aoColumns[b].nTf = i[b], a.aoColumns[b].sClass && $(i[b]).addClass(a.aoColumns[b].sClass))
                }
            }
            function _fnDrawHead(a, b, c) {
                var d, e, f, g, h, i, j, k, l, m = [],
                n = [],
                o = a.aoColumns.length;
                for (c === undefined && (c = !1), d = 0, e = b.length; e > d; d++) {
                    for (m[d] = b[d].slice(), m[d].nTr = b[d].nTr, f = o - 1; f >= 0; f--) a.aoColumns[f].bVisible || c || m[d].splice(f, 1);
                    n.push([])
                }
                for (d = 0, e = m.length; e > d; d++) {
                    if (j = m[d].nTr) for (; i = j.firstChild;) j.removeChild(i);
                    for (f = 0, g = m[d].length; g > f; f++) if (k = 1, l = 1, n[d][f] === undefined) {
                        for (j.appendChild(m[d][f].cell), n[d][f] = 1; m[d + k] !== undefined && m[d][f].cell == m[d + k][f].cell;) n[d + k][f] = 1,
                        k++;
                        for (; m[d][f + l] !== undefined && m[d][f].cell == m[d][f + l].cell;) {
                            for (h = 0; k > h; h++) n[d + h][f + l] = 1;
                            l++
                        }
                        m[d][f].cell.rowSpan = k,
                        m[d][f].cell.colSpan = l
                    }
                }
            }
            function _fnDraw(a) {
                var b = _fnCallbackFire(a, "aoPreDrawCallback", "preDraw", [a]);
                if ( - 1 !== $.inArray(!1, b)) return _fnProcessingDisplay(a, !1),
                void 0;
                var c, d, e, f = [],
                g = 0,
                h = a.asStripeClasses.length,
                i = a.aoOpenRows.length;
                if (a.bDrawing = !0, a.iInitDisplayStart !== undefined && -1 != a.iInitDisplayStart && (a._iDisplayStart = a.oFeatures.bServerSide ? a.iInitDisplayStart: a.iInitDisplayStart >= a.fnRecordsDisplay() ? 0 : a.iInitDisplayStart, a.iInitDisplayStart = -1, _fnCalculateEnd(a)), a.bDeferLoading) a.bDeferLoading = !1,
                a.iDraw++;
                else if (a.oFeatures.bServerSide) {
                    if (!a.bDestroying && !_fnAjaxUpdate(a)) return
                } else a.iDraw++;
                if (0 !== a.aiDisplay.length) {
                    var j = a._iDisplayStart,
                    k = a._iDisplayEnd;
                    a.oFeatures.bServerSide && (j = 0, k = a.aoData.length);
                    for (var l = j; k > l; l++) {
                        var m = a.aoData[a.aiDisplay[l]];
                        null === m.nTr && _fnCreateTr(a, a.aiDisplay[l]);
                        var n = m.nTr;
                        if (0 !== h) {
                            var o = a.asStripeClasses[g % h];
                            m._sRowStripe != o && ($(n).removeClass(m._sRowStripe).addClass(o), m._sRowStripe = o)
                        }
                        if (_fnCallbackFire(a, "aoRowCallback", null, [n, a.aoData[a.aiDisplay[l]]._aData, g, l]), f.push(n), g++, 0 !== i) for (var p = 0; i > p; p++) if (n == a.aoOpenRows[p].nParent) {
                            f.push(a.aoOpenRows[p].nTr);
                            break
                        }
                    }
                } else {
                    f[0] = document.createElement("tr"),
                    a.asStripeClasses[0] && (f[0].className = a.asStripeClasses[0]);
                    var q = a.oLanguage,
                    r = q.sZeroRecords;
                    1 != a.iDraw || null === a.sAjaxSource || a.oFeatures.bServerSide ? q.sEmptyTable && 0 === a.fnRecordsTotal() && (r = q.sEmptyTable) : r = q.sLoadingRecords;
                    var s = document.createElement("td");
                    s.setAttribute("valign", "top"),
                    s.colSpan = _fnVisbleColumns(a),
                    s.className = a.oClasses.sRowEmpty,
                    s.innerHTML = _fnInfoMacros(a, r),
                    f[g].appendChild(s)
                }
                _fnCallbackFire(a, "aoHeaderCallback", "header", [$(a.nTHead).children("tr")[0], _fnGetDataMaster(a), a._iDisplayStart, a.fnDisplayEnd(), a.aiDisplay]),
                _fnCallbackFire(a, "aoFooterCallback", "footer", [$(a.nTFoot).children("tr")[0], _fnGetDataMaster(a), a._iDisplayStart, a.fnDisplayEnd(), a.aiDisplay]);
                var t, u = document.createDocumentFragment(),
                v = document.createDocumentFragment();
                if (a.nTBody) {
                    if (t = a.nTBody.parentNode, v.appendChild(a.nTBody), !a.oScroll.bInfinite || !a._bInitComplete || a.bSorted || a.bFiltered) for (; e = a.nTBody.firstChild;) a.nTBody.removeChild(e);
                    for (c = 0, d = f.length; d > c; c++) u.appendChild(f[c]);
                    a.nTBody.appendChild(u),
                    null !== t && t.appendChild(a.nTBody)
                }
                _fnCallbackFire(a, "aoDrawCallback", "draw", [a]),
                a.bSorted = !1,
                a.bFiltered = !1,
                a.bDrawing = !1,
                a.oFeatures.bServerSide && (_fnProcessingDisplay(a, !1), a._bInitComplete || _fnInitComplete(a))
            }
            function _fnReDraw(a) {
                a.oFeatures.bSort ? _fnSort(a, a.oPreviousSearch) : a.oFeatures.bFilter ? _fnFilterComplete(a, a.oPreviousSearch) : (_fnCalculateEnd(a), _fnDraw(a))
            }
            function _fnAddOptionsHtml(a) {
                var b = $("<div></div>")[0];
                a.nTable.parentNode.insertBefore(b, a.nTable),
                a.nTableWrapper = $('<div id="' + a.sTableId + '_wrapper" class="' + a.oClasses.sWrapper + '" role="grid"></div>')[0],
                a.nTableReinsertBefore = a.nTable.nextSibling;
                for (var c, d, e, f, g, h, i, j = a.nTableWrapper,
                k = a.sDom.split(""), l = 0; l < k.length; l++) {
                    if (d = 0, e = k[l], "<" == e) {
                        if (f = $("<div></div>")[0], g = k[l + 1], "'" == g || '"' == g) {
                            for (h = "", i = 2; k[l + i] != g;) h += k[l + i],
                            i++;
                            if ("H" == h ? h = a.oClasses.sJUIHeader: "F" == h && (h = a.oClasses.sJUIFooter), -1 != h.indexOf(".")) {
                                var m = h.split(".");
                                f.id = m[0].substr(1, m[0].length - 1),
                                f.className = m[1]
                            } else "#" == h.charAt(0) ? f.id = h.substr(1, h.length - 1) : f.className = h;
                            l += i
                        }
                        j.appendChild(f),
                        j = f
                    } else if (">" == e) j = j.parentNode;
                    else if ("l" == e && a.oFeatures.bPaginate && a.oFeatures.bLengthChange) c = _fnFeatureHtmlLength(a),
                    d = 1;
                    else if ("f" == e && a.oFeatures.bFilter) c = _fnFeatureHtmlFilter(a),
                    d = 1;
                    else if ("r" == e && a.oFeatures.bProcessing) c = _fnFeatureHtmlProcessing(a),
                    d = 1;
                    else if ("t" == e) c = _fnFeatureHtmlTable(a),
                    d = 1;
                    else if ("i" == e && a.oFeatures.bInfo) c = _fnFeatureHtmlInfo(a),
                    d = 1;
                    else if ("p" == e && a.oFeatures.bPaginate) c = _fnFeatureHtmlPaginate(a),
                    d = 1;
                    else if (0 !== DataTable.ext.aoFeatures.length) for (var n = DataTable.ext.aoFeatures,
                    o = 0,
                    p = n.length; p > o; o++) if (e == n[o].cFeature) {
                        c = n[o].fnInit(a),
                        c && (d = 1);
                        break
                    }
                    1 == d && null !== c && ("object" != typeof a.aanFeatures[e] && (a.aanFeatures[e] = []), a.aanFeatures[e].push(c), j.appendChild(c))
                }
                b.parentNode.replaceChild(a.nTableWrapper, b)
            }
            function _fnDetectHeader(a, b) {
                var c, d, e, f, g, h, i, j, k, l, m, n = $(b).children("tr"),
                o = function(a, b, c) {
                    for (var d = a[b]; d[c];) c++;
                    return c
                };
                for (a.splice(0, a.length), e = 0, h = n.length; h > e; e++) a.push([]);
                for (e = 0, h = n.length; h > e; e++) for (c = n[e], j = 0, d = c.firstChild; d;) {
                    if ("TD" == d.nodeName.toUpperCase() || "TH" == d.nodeName.toUpperCase()) for (k = 1 * d.getAttribute("colspan"), l = 1 * d.getAttribute("rowspan"), k = k && 0 !== k && 1 !== k ? k: 1, l = l && 0 !== l && 1 !== l ? l: 1, i = o(a, e, j), m = 1 === k ? !0 : !1, g = 0; k > g; g++) for (f = 0; l > f; f++) a[e + f][i + g] = {
                        cell: d,
                        unique: m
                    },
                    a[e + f].nTr = c;
                    d = d.nextSibling
                }
            }
            function _fnGetUniqueThs(a, b, c) {
                var d = [];
                c || (c = a.aoHeader, b && (c = [], _fnDetectHeader(c, b)));
                for (var e = 0,
                f = c.length; f > e; e++) for (var g = 0,
                h = c[e].length; h > g; g++) ! c[e][g].unique || d[g] && a.bSortCellsTop || (d[g] = c[e][g].cell);
                return d
            }
            function _fnAjaxUpdate(a) {
                if (a.bAjaxDataGet) {
                    a.iDraw++,
                    _fnProcessingDisplay(a, !0),
                    a.aoColumns.length;
                    var b = _fnAjaxParameters(a);
                    return _fnServerParams(a, b),
                    a.fnServerData.call(a.oInstance, a.sAjaxSource, b,
                    function(b) {
                        _fnAjaxUpdateDraw(a, b)
                    },
                    a),
                    !1
                }
                return ! 0
            }
            function _fnAjaxParameters(a) {
                var b, c, d, e, f, g = a.aoColumns.length,
                h = [];
                for (h.push({
                    name: "sEcho",
                    value: a.iDraw
                }), h.push({
                    name: "iColumns",
                    value: g
                }), h.push({
                    name: "sColumns",
                    value: _fnColumnOrdering(a)
                }), h.push({
                    name: "iDisplayStart",
                    value: a._iDisplayStart
                }), h.push({
                    name: "iDisplayLength",
                    value: a.oFeatures.bPaginate !== !1 ? a._iDisplayLength: -1
                }), e = 0; g > e; e++) b = a.aoColumns[e].mData,
                h.push({
                    name: "mDataProp_" + e,
                    value: "function" == typeof b ? "function": b
                });
                if (a.oFeatures.bFilter !== !1) for (h.push({
                    name: "sSearch",
                    value: a.oPreviousSearch.sSearch
                }), h.push({
                    name: "bRegex",
                    value: a.oPreviousSearch.bRegex
                }), e = 0; g > e; e++) h.push({
                    name: "sSearch_" + e,
                    value: a.aoPreSearchCols[e].sSearch
                }),
                h.push({
                    name: "bRegex_" + e,
                    value: a.aoPreSearchCols[e].bRegex
                }),
                h.push({
                    name: "bSearchable_" + e,
                    value: a.aoColumns[e].bSearchable
                });
                if (a.oFeatures.bSort !== !1) {
                    var i = 0;
                    for (c = null !== a.aaSortingFixed ? a.aaSortingFixed.concat(a.aaSorting) : a.aaSorting.slice(), e = 0; e < c.length; e++) for (d = a.aoColumns[c[e][0]].aDataSort, f = 0; f < d.length; f++) h.push({
                        name: "iSortCol_" + i,
                        value: d[f]
                    }),
                    h.push({
                        name: "sSortDir_" + i,
                        value: c[e][1]
                    }),
                    i++;
                    for (h.push({
                        name: "iSortingCols",
                        value: i
                    }), e = 0; g > e; e++) h.push({
                        name: "bSortable_" + e,
                        value: a.aoColumns[e].bSortable
                    })
                }
                return h
            }
            function _fnServerParams(a, b) {
                _fnCallbackFire(a, "aoServerParams", "serverParams", [b])
            }
            function _fnAjaxUpdateDraw(a, b) {
                if (b.sEcho !== undefined) {
                    if (1 * b.sEcho < a.iDraw) return;
                    a.iDraw = 1 * b.sEcho
                } (!a.oScroll.bInfinite || a.oScroll.bInfinite && (a.bSorted || a.bFiltered)) && _fnClearTable(a),
                a._iRecordsTotal = parseInt(b.iTotalRecords, 10),
                a._iRecordsDisplay = parseInt(b.iTotalDisplayRecords, 10);
                var c, d = _fnColumnOrdering(a),
                e = b.sColumns !== undefined && "" !== d && b.sColumns != d;
                e && (c = _fnReOrderIndex(a, b.sColumns));
                for (var f = _fnGetObjectDataFn(a.sAjaxDataProp)(b), g = 0, h = f.length; h > g; g++) if (e) {
                    for (var i = [], j = 0, k = a.aoColumns.length; k > j; j++) i.push(f[g][c[j]]);
                    _fnAddData(a, i)
                } else _fnAddData(a, f[g]);
                a.aiDisplay = a.aiDisplayMaster.slice(),
                a.bAjaxDataGet = !1,
                _fnDraw(a),
                a.bAjaxDataGet = !0,
                _fnProcessingDisplay(a, !1)
            }
            function _fnFeatureHtmlFilter(a) {
                var b = a.oPreviousSearch,
                c = a.oLanguage.sSearch;
                c = -1 !== c.indexOf("_INPUT_") ? c.replace("_INPUT_", '<input type="text" />') : "" === c ? '<input type="text" />': c + ' <input type="text" />';
                var d = document.createElement("div");
                d.className = a.oClasses.sFilter,
                d.innerHTML = "<label>" + c + "</label>",
                a.aanFeatures.f || (d.id = a.sTableId + "_filter");
                var e = $('input[type="text"]', d);
                return d._DT_Input = e[0],
                e.val(b.sSearch.replace('"', "&quot;")),
                e.bind("keyup.DT",
                function() {
                    for (var c = a.aanFeatures.f,
                    d = "" === this.value ? "": this.value, e = 0, f = c.length; f > e; e++) c[e] != $(this).parents("div.dataTables_filter")[0] && $(c[e]._DT_Input).val(d);
                    d != b.sSearch && _fnFilterComplete(a, {
                        sSearch: d,
                        bRegex: b.bRegex,
                        bSmart: b.bSmart,
                        bCaseInsensitive: b.bCaseInsensitive
                    })
                }),
                e.attr("aria-controls", a.sTableId).bind("keypress.DT",
                function(a) {
                    return 13 == a.keyCode ? !1 : void 0
                }),
                d
            }
            function _fnFilterComplete(a, b, c) {
                var d = a.oPreviousSearch,
                e = a.aoPreSearchCols,
                f = function(a) {
                    d.sSearch = a.sSearch,
                    d.bRegex = a.bRegex,
                    d.bSmart = a.bSmart,
                    d.bCaseInsensitive = a.bCaseInsensitive
                };
                if (a.oFeatures.bServerSide) f(b);
                else {
                    _fnFilter(a, b.sSearch, c, b.bRegex, b.bSmart, b.bCaseInsensitive),
                    f(b);
                    for (var g = 0; g < a.aoPreSearchCols.length; g++) _fnFilterColumn(a, e[g].sSearch, g, e[g].bRegex, e[g].bSmart, e[g].bCaseInsensitive);
                    _fnFilterCustom(a)
                }
                a.bFiltered = !0,
                $(a.oInstance).trigger("filter", a),
                a._iDisplayStart = 0,
                _fnCalculateEnd(a),
                _fnDraw(a),
                _fnBuildSearchArray(a, 0)
            }
            function _fnFilterCustom(a) {
                for (var b = DataTable.ext.afnFiltering,
                c = _fnGetColumns(a, "bSearchable"), d = 0, e = b.length; e > d; d++) for (var f = 0,
                g = 0,
                h = a.aiDisplay.length; h > g; g++) {
                    var i = a.aiDisplay[g - f],
                    j = b[d](a, _fnGetRowData(a, i, "filter", c), i);
                    j || (a.aiDisplay.splice(g - f, 1), f++)
                }
            }
            function _fnFilterColumn(a, b, c, d, e, f) {
                if ("" !== b) for (var g = 0,
                h = _fnFilterCreateSearch(b, d, e, f), i = a.aiDisplay.length - 1; i >= 0; i--) {
                    var j = _fnDataToSearch(_fnGetCellData(a, a.aiDisplay[i], c, "filter"), a.aoColumns[c].sType);
                    h.test(j) || (a.aiDisplay.splice(i, 1), g++)
                }
            }
            function _fnFilter(a, b, c, d, e, f) {
                var g, h = _fnFilterCreateSearch(b, d, e, f),
                i = a.oPreviousSearch;
                if (c || (c = 0), 0 !== DataTable.ext.afnFiltering.length && (c = 1), b.length <= 0) a.aiDisplay.splice(0, a.aiDisplay.length),
                a.aiDisplay = a.aiDisplayMaster.slice();
                else if (a.aiDisplay.length == a.aiDisplayMaster.length || i.sSearch.length > b.length || 1 == c || 0 !== b.indexOf(i.sSearch)) for (a.aiDisplay.splice(0, a.aiDisplay.length), _fnBuildSearchArray(a, 1), g = 0; g < a.aiDisplayMaster.length; g++) h.test(a.asDataSearch[g]) && a.aiDisplay.push(a.aiDisplayMaster[g]);
                else {
                    var j = 0;
                    for (g = 0; g < a.asDataSearch.length; g++) h.test(a.asDataSearch[g]) || (a.aiDisplay.splice(g - j, 1), j++)
                }
            }
            function _fnBuildSearchArray(a, b) {
                if (!a.oFeatures.bServerSide) {
                    a.asDataSearch = [];
                    for (var c = _fnGetColumns(a, "bSearchable"), d = 1 === b ? a.aiDisplayMaster: a.aiDisplay, e = 0, f = d.length; f > e; e++) a.asDataSearch[e] = _fnBuildSearchRow(a, _fnGetRowData(a, d[e], "filter", c))
                }
            }
            function _fnBuildSearchRow(a, b) {
                var c = b.join("  ");
                return - 1 !== c.indexOf("&") && (c = $("<div>").html(c).text()),
                c.replace(/[\n\r]/g, " ")
            }
            function _fnFilterCreateSearch(a, b, c, d) {
                var e, f;
                return c ? (e = b ? a.split(" ") : _fnEscapeRegex(a).split(" "), f = "^(?=.*?" + e.join(")(?=.*?") + ").*$", new RegExp(f, d ? "i": "")) : (a = b ? a: _fnEscapeRegex(a), new RegExp(a, d ? "i": ""))
            }
            function _fnDataToSearch(a, b) {
                return "function" == typeof DataTable.ext.ofnSearch[b] ? DataTable.ext.ofnSearch[b](a) : null === a ? "": "html" == b ? a.replace(/[\r\n]/g, " ").replace(/<.*?>/g, "") : "string" == typeof a ? a.replace(/[\r\n]/g, " ") : a
            }
            function _fnEscapeRegex(a) {
                var b = ["/", ".", "*", "+", "?", "|", "(", ")", "[", "]", "{", "}", "\\", "$", "^", "-"],
                c = new RegExp("(\\" + b.join("|\\") + ")", "g");
                return a.replace(c, "\\$1")
            }
            function _fnFeatureHtmlInfo(a) {
                var b = document.createElement("div");
                return b.className = a.oClasses.sInfo,
                a.aanFeatures.i || (a.aoDrawCallback.push({
                    fn: _fnUpdateInfo,
                    sName: "information"
                }), b.id = a.sTableId + "_info"),
                a.nTable.setAttribute("aria-describedby", a.sTableId + "_info"),
                b
            }
            function _fnUpdateInfo(a) {
                if (a.oFeatures.bInfo && 0 !== a.aanFeatures.i.length) {
                    var b, c = a.oLanguage,
                    d = a._iDisplayStart + 1,
                    e = a.fnDisplayEnd(),
                    f = a.fnRecordsTotal(),
                    g = a.fnRecordsDisplay();
                    b = 0 === g ? c.sInfoEmpty: c.sInfo,
                    g != f && (b += " " + c.sInfoFiltered),
                    b += c.sInfoPostFix,
                    b = _fnInfoMacros(a, b),
                    null !== c.fnInfoCallback && (b = c.fnInfoCallback.call(a.oInstance, a, d, e, f, g, b));
                    for (var h = a.aanFeatures.i,
                    i = 0,
                    j = h.length; j > i; i++) $(h[i]).html(b)
                }
            }
            function _fnInfoMacros(a, b) {
                var c = a._iDisplayStart + 1,
                d = a.fnFormatNumber(c),
                e = a.fnDisplayEnd(),
                f = a.fnFormatNumber(e),
                g = a.fnRecordsDisplay(),
                h = a.fnFormatNumber(g),
                i = a.fnRecordsTotal(),
                j = a.fnFormatNumber(i);
                return a.oScroll.bInfinite && (d = a.fnFormatNumber(1)),
                b.replace(/_START_/g, d).replace(/_END_/g, f).replace(/_TOTAL_/g, h).replace(/_MAX_/g, j)
            }
            function _fnInitialise(a) {
                var b, c, d = a.iInitDisplayStart;
                if (a.bInitialised === !1) return setTimeout(function() {
                    _fnInitialise(a)
                },
                200),
                void 0;
                for (_fnAddOptionsHtml(a), _fnBuildHead(a), _fnDrawHead(a, a.aoHeader), a.nTFoot && _fnDrawHead(a, a.aoFooter), _fnProcessingDisplay(a, !0), a.oFeatures.bAutoWidth && _fnCalculateColumnWidths(a), b = 0, c = a.aoColumns.length; c > b; b++) null !== a.aoColumns[b].sWidth && (a.aoColumns[b].nTh.style.width = _fnStringToCss(a.aoColumns[b].sWidth));
                if (a.oFeatures.bSort ? _fnSort(a) : a.oFeatures.bFilter ? _fnFilterComplete(a, a.oPreviousSearch) : (a.aiDisplay = a.aiDisplayMaster.slice(), _fnCalculateEnd(a), _fnDraw(a)), null !== a.sAjaxSource && !a.oFeatures.bServerSide) {
                    var e = [];
                    return _fnServerParams(a, e),
                    a.fnServerData.call(a.oInstance, a.sAjaxSource, e,
                    function(c) {
                        var e = "" !== a.sAjaxDataProp ? _fnGetObjectDataFn(a.sAjaxDataProp)(c) : c;
                        for (b = 0; b < e.length; b++) _fnAddData(a, e[b]);
                        a.iInitDisplayStart = d,
                        a.oFeatures.bSort ? _fnSort(a) : (a.aiDisplay = a.aiDisplayMaster.slice(), _fnCalculateEnd(a), _fnDraw(a)),
                        _fnProcessingDisplay(a, !1),
                        _fnInitComplete(a, c)
                    },
                    a),
                    void 0
                }
                a.oFeatures.bServerSide || (_fnProcessingDisplay(a, !1), _fnInitComplete(a))
            }
            function _fnInitComplete(a, b) {
                a._bInitComplete = !0,
                _fnCallbackFire(a, "aoInitComplete", "init", [a, b])
            }
            function _fnLanguageCompat(a) {
                var b = DataTable.defaults.oLanguage; ! a.sEmptyTable && a.sZeroRecords && "No data available in table" === b.sEmptyTable && _fnMap(a, a, "sZeroRecords", "sEmptyTable"),
                !a.sLoadingRecords && a.sZeroRecords && "Loading..." === b.sLoadingRecords && _fnMap(a, a, "sZeroRecords", "sLoadingRecords")
            }
            function _fnFeatureHtmlLength(a) {
                if (a.oScroll.bInfinite) return null;
                var b, c, d = 'name="' + a.sTableId + '_length"',
                e = '<select size="1" ' + d + ">",
                f = a.aLengthMenu;
                if (2 == f.length && "object" == typeof f[0] && "object" == typeof f[1]) for (b = 0, c = f[0].length; c > b; b++) e += '<option value="' + f[0][b] + '">' + f[1][b] + "</option>";
                else for (b = 0, c = f.length; c > b; b++) e += '<option value="' + f[b] + '">' + f[b] + "</option>";
                e += "</select>";
                var g = document.createElement("div");
                return a.aanFeatures.l || (g.id = a.sTableId + "_length"),
                g.className = a.oClasses.sLength,
                g.innerHTML = "<label>" + a.oLanguage.sLengthMenu.replace("_MENU_", e) + "</label>",
                $('select option[value="' + a._iDisplayLength + '"]', g).attr("selected", !0),
                $("select", g).bind("change.DT",
                function() {
                    var d = $(this).val(),
                    e = a.aanFeatures.l;
                    for (b = 0, c = e.length; c > b; b++) e[b] != this.parentNode && $("select", e[b]).val(d);
                    a._iDisplayLength = parseInt(d, 10),
                    _fnCalculateEnd(a),
                    a.fnDisplayEnd() == a.fnRecordsDisplay() && (a._iDisplayStart = a.fnDisplayEnd() - a._iDisplayLength, a._iDisplayStart < 0 && (a._iDisplayStart = 0)),
                    -1 == a._iDisplayLength && (a._iDisplayStart = 0),
                    _fnDraw(a)
                }),
                $("select", g).attr("aria-controls", a.sTableId),
                g
            }
            function _fnCalculateEnd(a) {
                a._iDisplayEnd = a.oFeatures.bPaginate === !1 ? a.aiDisplay.length: a._iDisplayStart + a._iDisplayLength > a.aiDisplay.length || -1 == a._iDisplayLength ? a.aiDisplay.length: a._iDisplayStart + a._iDisplayLength
            }
            function _fnFeatureHtmlPaginate(a) {
                if (a.oScroll.bInfinite) return null;
                var b = document.createElement("div");
                return b.className = a.oClasses.sPaging + a.sPaginationType,
                DataTable.ext.oPagination[a.sPaginationType].fnInit(a, b,
                function(a) {
                    _fnCalculateEnd(a),
                    _fnDraw(a)
                }),
                a.aanFeatures.p || a.aoDrawCallback.push({
                    fn: function(a) {
                        DataTable.ext.oPagination[a.sPaginationType].fnUpdate(a,
                        function(a) {
                            _fnCalculateEnd(a),
                            _fnDraw(a)
                        })
                    },
                    sName: "pagination"
                }),
                b
            }
            function _fnPageChange(a, b) {
                var c = a._iDisplayStart;
                if ("number" == typeof b) a._iDisplayStart = b * a._iDisplayLength,
                a._iDisplayStart > a.fnRecordsDisplay() && (a._iDisplayStart = 0);
                else if ("first" == b) a._iDisplayStart = 0;
                else if ("previous" == b) a._iDisplayStart = a._iDisplayLength >= 0 ? a._iDisplayStart - a._iDisplayLength: 0,
                a._iDisplayStart < 0 && (a._iDisplayStart = 0);
                else if ("next" == b) a._iDisplayLength >= 0 ? a._iDisplayStart + a._iDisplayLength < a.fnRecordsDisplay() && (a._iDisplayStart += a._iDisplayLength) : a._iDisplayStart = 0;
                else if ("last" == b) if (a._iDisplayLength >= 0) {
                    var d = parseInt((a.fnRecordsDisplay() - 1) / a._iDisplayLength, 10) + 1;
                    a._iDisplayStart = (d - 1) * a._iDisplayLength
                } else a._iDisplayStart = 0;
                else _fnLog(a, 0, "Unknown paging action: " + b);
                return $(a.oInstance).trigger("page", a),
                c != a._iDisplayStart
            }
            function _fnFeatureHtmlProcessing(a) {
                var b = document.createElement("div");
                return a.aanFeatures.r || (b.id = a.sTableId + "_processing"),
                b.innerHTML = a.oLanguage.sProcessing,
                b.className = a.oClasses.sProcessing,
                a.nTable.parentNode.insertBefore(b, a.nTable),
                b
            }
            function _fnProcessingDisplay(a, b) {
                if (a.oFeatures.bProcessing) for (var c = a.aanFeatures.r,
                d = 0,
                e = c.length; e > d; d++) c[d].style.visibility = b ? "visible": "hidden";
                $(a.oInstance).trigger("processing", [a, b])
            }
            function _fnFeatureHtmlTable(a) {
                if ("" === a.oScroll.sX && "" === a.oScroll.sY) return a.nTable;
                var b = document.createElement("div"),
                c = document.createElement("div"),
                d = document.createElement("div"),
                e = document.createElement("div"),
                f = document.createElement("div"),
                g = document.createElement("div"),
                h = a.nTable.cloneNode(!1),
                i = a.nTable.cloneNode(!1),
                j = a.nTable.getElementsByTagName("thead")[0],
                k = 0 === a.nTable.getElementsByTagName("tfoot").length ? null: a.nTable.getElementsByTagName("tfoot")[0],
                l = a.oClasses;
                c.appendChild(d),
                f.appendChild(g),
                e.appendChild(a.nTable),
                b.appendChild(c),
                b.appendChild(e),
                d.appendChild(h),
                h.appendChild(j),
                null !== k && (b.appendChild(f), g.appendChild(i), i.appendChild(k)),
                b.className = l.sScrollWrapper,
                c.className = l.sScrollHead,
                d.className = l.sScrollHeadInner,
                e.className = l.sScrollBody,
                f.className = l.sScrollFoot,
                g.className = l.sScrollFootInner,
                a.oScroll.bAutoCss && (c.style.overflow = "hidden", c.style.position = "relative", f.style.overflow = "hidden", e.style.overflow = "auto"),
                c.style.border = "0",
                c.style.width = "100%",
                f.style.border = "0",
                d.style.width = "" !== a.oScroll.sXInner ? a.oScroll.sXInner: "100%",
                h.removeAttribute("id"),
                h.style.marginLeft = "0",
                a.nTable.style.marginLeft = "0",
                null !== k && (i.removeAttribute("id"), i.style.marginLeft = "0");
                var m = $(a.nTable).children("caption");
                return m.length > 0 && (m = m[0], "top" === m._captionSide ? h.appendChild(m) : "bottom" === m._captionSide && k && i.appendChild(m)),
                "" !== a.oScroll.sX && (c.style.width = _fnStringToCss(a.oScroll.sX), e.style.width = _fnStringToCss(a.oScroll.sX), null !== k && (f.style.width = _fnStringToCss(a.oScroll.sX)), $(e).scroll(function() {
                    c.scrollLeft = this.scrollLeft,
                    null !== k && (f.scrollLeft = this.scrollLeft)
                })),
                "" !== a.oScroll.sY && (e.style.height = _fnStringToCss(a.oScroll.sY)),
                a.aoDrawCallback.push({
                    fn: _fnScrollDraw,
                    sName: "scrolling"
                }),
                a.oScroll.bInfinite && $(e).scroll(function() {
                    a.bDrawing || 0 === $(this).scrollTop() || $(this).scrollTop() + $(this).height() > $(a.nTable).height() - a.oScroll.iLoadGap && a.fnDisplayEnd() < a.fnRecordsDisplay() && (_fnPageChange(a, "next"), _fnCalculateEnd(a), _fnDraw(a))
                }),
                a.nScrollHead = c,
                a.nScrollFoot = f,
                b
            }
            function _fnScrollDraw(a) {
                var b, c, d, e, f, g, h, i, j, k, l, m = a.nScrollHead.getElementsByTagName("div")[0],
                n = m.getElementsByTagName("table")[0],
                o = a.nTable.parentNode,
                p = [],
                q = [],
                r = null !== a.nTFoot ? a.nScrollFoot.getElementsByTagName("div")[0] : null,
                s = null !== a.nTFoot ? r.getElementsByTagName("table")[0] : null,
                t = a.oBrowser.bScrollOversize,
                u = function(a) {
                    h = a.style,
                    h.paddingTop = "0",
                    h.paddingBottom = "0",
                    h.borderTopWidth = "0",
                    h.borderBottomWidth = "0",
                    h.height = 0
                };
                $(a.nTable).children("thead, tfoot").remove(),
                j = $(a.nTHead).clone()[0],
                a.nTable.insertBefore(j, a.nTable.childNodes[0]),
                d = a.nTHead.getElementsByTagName("tr"),
                e = j.getElementsByTagName("tr"),
                null !== a.nTFoot && (k = $(a.nTFoot).clone()[0], a.nTable.insertBefore(k, a.nTable.childNodes[1]), g = a.nTFoot.getElementsByTagName("tr"), f = k.getElementsByTagName("tr")),
                "" === a.oScroll.sX && (o.style.width = "100%", m.parentNode.style.width = "100%");
                var v = _fnGetUniqueThs(a, j);
                for (b = 0, c = v.length; c > b; b++) i = _fnVisibleToColumnIndex(a, b),
                v[b].style.width = a.aoColumns[i].sWidth;
                if (null !== a.nTFoot && _fnApplyToChildren(function(a) {
                    a.style.width = ""
                },
                f), a.oScroll.bCollapse && "" !== a.oScroll.sY && (o.style.height = o.offsetHeight + a.nTHead.offsetHeight + "px"), l = $(a.nTable).outerWidth(), "" === a.oScroll.sX ? (a.nTable.style.width = "100%", t && ($("tbody", o).height() > o.offsetHeight || "scroll" == $(o).css("overflow-y")) && (a.nTable.style.width = _fnStringToCss($(a.nTable).outerWidth() - a.oScroll.iBarWidth))) : "" !== a.oScroll.sXInner ? a.nTable.style.width = _fnStringToCss(a.oScroll.sXInner) : l == $(o).width() && $(o).height() < $(a.nTable).height() ? (a.nTable.style.width = _fnStringToCss(l - a.oScroll.iBarWidth), $(a.nTable).outerWidth() > l - a.oScroll.iBarWidth && (a.nTable.style.width = _fnStringToCss(l))) : a.nTable.style.width = _fnStringToCss(l), l = $(a.nTable).outerWidth(), _fnApplyToChildren(u, e), _fnApplyToChildren(function(a) {
                    p.push(_fnStringToCss($(a).width()))
                },
                e), _fnApplyToChildren(function(a, b) {
                    a.style.width = p[b]
                },
                d), $(e).height(0), null !== a.nTFoot && (_fnApplyToChildren(u, f), _fnApplyToChildren(function(a) {
                    q.push(_fnStringToCss($(a).width()))
                },
                f), _fnApplyToChildren(function(a, b) {
                    a.style.width = q[b]
                },
                g), $(f).height(0)), _fnApplyToChildren(function(a, b) {
                    a.innerHTML = "",
                    a.style.width = p[b]
                },
                e), null !== a.nTFoot && _fnApplyToChildren(function(a, b) {
                    a.innerHTML = "",
                    a.style.width = q[b]
                },
                f), $(a.nTable).outerWidth() < l) {
                    var w = o.scrollHeight > o.offsetHeight || "scroll" == $(o).css("overflow-y") ? l + a.oScroll.iBarWidth: l;
                    t && (o.scrollHeight > o.offsetHeight || "scroll" == $(o).css("overflow-y")) && (a.nTable.style.width = _fnStringToCss(w - a.oScroll.iBarWidth)),
                    o.style.width = _fnStringToCss(w),
                    a.nScrollHead.style.width = _fnStringToCss(w),
                    null !== a.nTFoot && (a.nScrollFoot.style.width = _fnStringToCss(w)),
                    "" === a.oScroll.sX ? _fnLog(a, 1, "The table cannot fit into the current element which will cause column misalignment. The table has been drawn at its minimum possible width.") : "" !== a.oScroll.sXInner && _fnLog(a, 1, "The table cannot fit into the current element which will cause column misalignment. Increase the sScrollXInner value or remove it to allow automatic calculation")
                } else o.style.width = _fnStringToCss("100%"),
                a.nScrollHead.style.width = _fnStringToCss("100%"),
                null !== a.nTFoot && (a.nScrollFoot.style.width = _fnStringToCss("100%"));
                if ("" === a.oScroll.sY && t && (o.style.height = _fnStringToCss(a.nTable.offsetHeight + a.oScroll.iBarWidth)), "" !== a.oScroll.sY && a.oScroll.bCollapse) {
                    o.style.height = _fnStringToCss(a.oScroll.sY);
                    var x = "" !== a.oScroll.sX && a.nTable.offsetWidth > o.offsetWidth ? a.oScroll.iBarWidth: 0;
                    a.nTable.offsetHeight < o.offsetHeight && (o.style.height = _fnStringToCss(a.nTable.offsetHeight + x))
                }
                var y = $(a.nTable).outerWidth();
                n.style.width = _fnStringToCss(y),
                m.style.width = _fnStringToCss(y);
                var z = $(a.nTable).height() > o.clientHeight || "scroll" == $(o).css("overflow-y");
                m.style.paddingRight = z ? a.oScroll.iBarWidth + "px": "0px",
                null !== a.nTFoot && (s.style.width = _fnStringToCss(y), r.style.width = _fnStringToCss(y), r.style.paddingRight = z ? a.oScroll.iBarWidth + "px": "0px"),
                $(o).scroll(),
                (a.bSorted || a.bFiltered) && (o.scrollTop = 0)
            }
            function _fnApplyToChildren(a, b, c) {
                for (var d, e, f = 0,
                g = 0,
                h = b.length; h > g;) {
                    for (d = b[g].firstChild, e = c ? c[g].firstChild: null; d;) 1 === d.nodeType && (c ? a(d, e, f) : a(d, f), f++),
                    d = d.nextSibling,
                    e = c ? e.nextSibling: null;
                    g++
                }
            }
            function _fnConvertToWidth(a, b) {
                if (!a || null === a || "" === a) return 0;
                b || (b = document.body);
                var c, d = document.createElement("div");
                return d.style.width = _fnStringToCss(a),
                b.appendChild(d),
                c = d.offsetWidth,
                b.removeChild(d),
                c
            }
            function _fnCalculateColumnWidths(a) {
                a.nTable.offsetWidth;
                var b, c, d, e, f = 0,
                g = 0,
                h = a.aoColumns.length,
                i = $("th", a.nTHead),
                j = a.nTable.getAttribute("width"),
                k = a.nTable.parentNode;
                for (c = 0; h > c; c++) a.aoColumns[c].bVisible && (g++, null !== a.aoColumns[c].sWidth && (b = _fnConvertToWidth(a.aoColumns[c].sWidthOrig, k), null !== b && (a.aoColumns[c].sWidth = _fnStringToCss(b)), f++));
                if (h == i.length && 0 === f && g == h && "" === a.oScroll.sX && "" === a.oScroll.sY) for (c = 0; c < a.aoColumns.length; c++) b = $(i[c]).width(),
                null !== b && (a.aoColumns[c].sWidth = _fnStringToCss(b));
                else {
                    var l = a.nTable.cloneNode(!1),
                    m = a.nTHead.cloneNode(!0),
                    n = document.createElement("tbody"),
                    o = document.createElement("tr");
                    l.removeAttribute("id"),
                    l.appendChild(m),
                    null !== a.nTFoot && (l.appendChild(a.nTFoot.cloneNode(!0)), _fnApplyToChildren(function(a) {
                        a.style.width = ""
                    },
                    l.getElementsByTagName("tr"))),
                    l.appendChild(n),
                    n.appendChild(o);
                    var p = $("thead th", l);
                    0 === p.length && (p = $("tbody tr:eq(0)>td", l));
                    var q = _fnGetUniqueThs(a, m);
                    for (d = 0, c = 0; h > c; c++) {
                        var r = a.aoColumns[c];
                        r.bVisible && null !== r.sWidthOrig && "" !== r.sWidthOrig ? q[c - d].style.width = _fnStringToCss(r.sWidthOrig) : r.bVisible ? q[c - d].style.width = "": d++
                    }
                    for (c = 0; h > c; c++) if (a.aoColumns[c].bVisible) {
                        var s = _fnGetWidestNode(a, c);
                        null !== s && (s = s.cloneNode(!0), "" !== a.aoColumns[c].sContentPadding && (s.innerHTML += a.aoColumns[c].sContentPadding), o.appendChild(s))
                    }
                    k.appendChild(l),
                    "" !== a.oScroll.sX && "" !== a.oScroll.sXInner ? l.style.width = _fnStringToCss(a.oScroll.sXInner) : "" !== a.oScroll.sX ? (l.style.width = "", $(l).width() < k.offsetWidth && (l.style.width = _fnStringToCss(k.offsetWidth))) : "" !== a.oScroll.sY ? l.style.width = _fnStringToCss(k.offsetWidth) : j && (l.style.width = _fnStringToCss(j)),
                    l.style.visibility = "hidden",
                    _fnScrollingWidthAdjust(a, l);
                    var t = $("tbody tr:eq(0)", l).children();
                    if (0 === t.length && (t = _fnGetUniqueThs(a, $("thead", l)[0])), "" !== a.oScroll.sX) {
                        var u = 0;
                        for (d = 0, c = 0; c < a.aoColumns.length; c++) a.aoColumns[c].bVisible && (u += null === a.aoColumns[c].sWidthOrig ? $(t[d]).outerWidth() : parseInt(a.aoColumns[c].sWidth.replace("px", ""), 10) + ($(t[d]).outerWidth() - $(t[d]).width()), d++);
                        l.style.width = _fnStringToCss(u),
                        a.nTable.style.width = _fnStringToCss(u)
                    }
                    for (d = 0, c = 0; c < a.aoColumns.length; c++) a.aoColumns[c].bVisible && (e = $(t[d]).width(), null !== e && e > 0 && (a.aoColumns[c].sWidth = _fnStringToCss(e)), d++);
                    var v = $(l).css("width");
                    a.nTable.style.width = -1 !== v.indexOf("%") ? v: _fnStringToCss($(l).outerWidth()),
                    l.parentNode.removeChild(l)
                }
                j && (a.nTable.style.width = _fnStringToCss(j))
            }
            function _fnScrollingWidthAdjust(a, b) {
                "" === a.oScroll.sX && "" !== a.oScroll.sY ? ($(b).width(), b.style.width = _fnStringToCss($(b).outerWidth() - a.oScroll.iBarWidth)) : "" !== a.oScroll.sX && (b.style.width = _fnStringToCss($(b).outerWidth()))
            }
            function _fnGetWidestNode(a, b) {
                var c = _fnGetMaxLenString(a, b);
                if (0 > c) return null;
                if (null === a.aoData[c].nTr) {
                    var d = document.createElement("td");
                    return d.innerHTML = _fnGetCellData(a, c, b, ""),
                    d
                }
                return _fnGetTdNodes(a, c)[b]
            }
            function _fnGetMaxLenString(a, b) {
                for (var c = -1,
                d = -1,
                e = 0; e < a.aoData.length; e++) {
                    var f = _fnGetCellData(a, e, b, "display") + "";
                    f = f.replace(/<.*?>/g, ""),
                    f.length > c && (c = f.length, d = e)
                }
                return d
            }
            function _fnStringToCss(a) {
                if (null === a) return "0px";
                if ("number" == typeof a) return 0 > a ? "0px": a + "px";
                var b = a.charCodeAt(a.length - 1);
                return 48 > b || b > 57 ? a: a + "px"
            }
            function _fnScrollBarWidth() {
                var a = document.createElement("p"),
                b = a.style;
                b.width = "100%",
                b.height = "200px",
                b.padding = "0px";
                var c = document.createElement("div");
                b = c.style,
                b.position = "absolute",
                b.top = "0px",
                b.left = "0px",
                b.visibility = "hidden",
                b.width = "200px",
                b.height = "150px",
                b.padding = "0px",
                b.overflow = "hidden",
                c.appendChild(a),
                document.body.appendChild(c);
                var d = a.offsetWidth;
                c.style.overflow = "scroll";
                var e = a.offsetWidth;
                return d == e && (e = c.clientWidth),
                document.body.removeChild(c),
                d - e
            }
            function _fnSort(a, b) {
                var c, d, e, f, g, h, i, j, k = [],
                l = [],
                m = DataTable.ext.oSort,
                n = a.aoData,
                o = a.aoColumns,
                p = a.oLanguage.oAria;
                if (!a.oFeatures.bServerSide && (0 !== a.aaSorting.length || null !== a.aaSortingFixed)) {
                    for (k = null !== a.aaSortingFixed ? a.aaSortingFixed.concat(a.aaSorting) : a.aaSorting.slice(), c = 0; c < k.length; c++) {
                        var q = k[c][0],
                        r = _fnColumnIndexToVisible(a, q);
                        if (i = a.aoColumns[q].sSortDataType, DataTable.ext.afnSortData[i]) {
                            var s = DataTable.ext.afnSortData[i].call(a.oInstance, a, q, r);
                            if (s.length === n.length) for (e = 0, f = n.length; f > e; e++) _fnSetCellData(a, e, q, s[e]);
                            else _fnLog(a, 0, "Returned data sort array (col " + q + ") is the wrong length")
                        }
                    }
                    for (c = 0, d = a.aiDisplayMaster.length; d > c; c++) l[a.aiDisplayMaster[c]] = c;
                    var t, u, v = k.length;
                    for (c = 0, d = n.length; d > c; c++) for (e = 0; v > e; e++) for (u = o[k[e][0]].aDataSort, g = 0, h = u.length; h > g; g++) i = o[u[g]].sType,
                    t = m[(i ? i: "string") + "-pre"],
                    n[c]._aSortData[u[g]] = t ? t(_fnGetCellData(a, c, u[g], "sort")) : _fnGetCellData(a, c, u[g], "sort");
                    a.aiDisplayMaster.sort(function(a, b) {
                        var c, d, e, f, g, h;
                        for (c = 0; v > c; c++) for (g = o[k[c][0]].aDataSort, d = 0, e = g.length; e > d; d++) if (h = o[g[d]].sType, f = m[(h ? h: "string") + "-" + k[c][1]](n[a]._aSortData[g[d]], n[b]._aSortData[g[d]]), 0 !== f) return f;
                        return m["numeric-asc"](l[a], l[b])
                    })
                }
                for (b !== undefined && !b || a.oFeatures.bDeferRender || _fnSortingClasses(a), c = 0, d = a.aoColumns.length; d > c; c++) {
                    var w = o[c].sTitle.replace(/<.*?>/g, "");
                    if (j = o[c].nTh, j.removeAttribute("aria-sort"), j.removeAttribute("aria-label"), o[c].bSortable) if (k.length > 0 && k[0][0] == c) {
                        j.setAttribute("aria-sort", "asc" == k[0][1] ? "ascending": "descending");
                        var x = o[c].asSorting[k[0][2] + 1] ? o[c].asSorting[k[0][2] + 1] : o[c].asSorting[0];
                        j.setAttribute("aria-label", w + ("asc" == x ? p.sSortAscending: p.sSortDescending))
                    } else j.setAttribute("aria-label", w + ("asc" == o[c].asSorting[0] ? p.sSortAscending: p.sSortDescending));
                    else j.setAttribute("aria-label", w)
                }
                a.bSorted = !0,
                $(a.oInstance).trigger("sort", a),
                a.oFeatures.bFilter ? _fnFilterComplete(a, a.oPreviousSearch, 1) : (a.aiDisplay = a.aiDisplayMaster.slice(), a._iDisplayStart = 0, _fnCalculateEnd(a), _fnDraw(a))
            }
            function _fnSortAttachListener(a, b, c, d) {
                _fnBindAction(b, {},
                function(b) {
                    if (a.aoColumns[c].bSortable !== !1) {
                        var e = function() {
                            var d, e;
                            if (b.shiftKey) {
                                for (var f = !1,
                                g = 0; g < a.aaSorting.length; g++) if (a.aaSorting[g][0] == c) {
                                    f = !0,
                                    d = a.aaSorting[g][0],
                                    e = a.aaSorting[g][2] + 1,
                                    a.aoColumns[d].asSorting[e] ? (a.aaSorting[g][1] = a.aoColumns[d].asSorting[e], a.aaSorting[g][2] = e) : a.aaSorting.splice(g, 1);
                                    break
                                }
                                f === !1 && a.aaSorting.push([c, a.aoColumns[c].asSorting[0], 0])
                            } else 1 == a.aaSorting.length && a.aaSorting[0][0] == c ? (d = a.aaSorting[0][0], e = a.aaSorting[0][2] + 1, a.aoColumns[d].asSorting[e] || (e = 0), a.aaSorting[0][1] = a.aoColumns[d].asSorting[e], a.aaSorting[0][2] = e) : (a.aaSorting.splice(0, a.aaSorting.length), a.aaSorting.push([c, a.aoColumns[c].asSorting[0], 0]));
                            _fnSort(a)
                        };
                        a.oFeatures.bProcessing ? (_fnProcessingDisplay(a, !0), setTimeout(function() {
                            e(),
                            a.oFeatures.bServerSide || _fnProcessingDisplay(a, !1)
                        },
                        0)) : e(),
                        "function" == typeof d && d(a)
                    }
                })
            }
            function _fnSortingClasses(a) {
                var b, c, d, e, f, g, h = a.aoColumns.length,
                i = a.oClasses;
                for (b = 0; h > b; b++) a.aoColumns[b].bSortable && $(a.aoColumns[b].nTh).removeClass(i.sSortAsc + " " + i.sSortDesc + " " + a.aoColumns[b].sSortingClass);
                for (f = null !== a.aaSortingFixed ? a.aaSortingFixed.concat(a.aaSorting) : a.aaSorting.slice(), b = 0; b < a.aoColumns.length; b++) if (a.aoColumns[b].bSortable) {
                    for (g = a.aoColumns[b].sSortingClass, e = -1, d = 0; d < f.length; d++) if (f[d][0] == b) {
                        g = "asc" == f[d][1] ? i.sSortAsc: i.sSortDesc,
                        e = d;
                        break
                    }
                    if ($(a.aoColumns[b].nTh).addClass(g), a.bJUI) {
                        var j = $("span." + i.sSortIcon, a.aoColumns[b].nTh);
                        j.removeClass(i.sSortJUIAsc + " " + i.sSortJUIDesc + " " + i.sSortJUI + " " + i.sSortJUIAscAllowed + " " + i.sSortJUIDescAllowed);
                        var k;
                        k = -1 == e ? a.aoColumns[b].sSortingClassJUI: "asc" == f[e][1] ? i.sSortJUIAsc: i.sSortJUIDesc,
                        j.addClass(k)
                    }
                } else $(a.aoColumns[b].nTh).addClass(a.aoColumns[b].sSortingClass);
                if (g = i.sSortColumn, a.oFeatures.bSort && a.oFeatures.bSortClasses) {
                    var l, m, n = _fnGetTdNodes(a),
                    o = [];
                    for (b = 0; h > b; b++) o.push("");
                    for (b = 0, l = 1; b < f.length; b++) m = parseInt(f[b][0], 10),
                    o[m] = g + l,
                    3 > l && l++;
                    var p, q, r, s = new RegExp(g + "[123]");
                    for (b = 0, c = n.length; c > b; b++) m = b % h,
                    q = n[b].className,
                    r = o[m],
                    p = q.replace(s, r),
                    p != q ? n[b].className = $.trim(p) : r.length > 0 && -1 == q.indexOf(r) && (n[b].className = q + " " + r)
                }
            }
            function _fnSaveState(a) {
                if (a.oFeatures.bStateSave && !a.bDestroying) {
                    var b, c, d = a.oScroll.bInfinite,
                    e = {
                        iCreate: (new Date).getTime(),
                        iStart: d ? 0 : a._iDisplayStart,
                        iEnd: d ? a._iDisplayLength: a._iDisplayEnd,
                        iLength: a._iDisplayLength,
                        aaSorting: $.extend(!0, [], a.aaSorting),
                        oSearch: $.extend(!0, {},
                        a.oPreviousSearch),
                        aoSearchCols: $.extend(!0, [], a.aoPreSearchCols),
                        abVisCols: []
                    };
                    for (b = 0, c = a.aoColumns.length; c > b; b++) e.abVisCols.push(a.aoColumns[b].bVisible);
                    _fnCallbackFire(a, "aoStateSaveParams", "stateSaveParams", [a, e]),
                    a.fnStateSave.call(a.oInstance, a, e)
                }
            }
            function _fnLoadState(a, b) {
                if (a.oFeatures.bStateSave) {
                    var c = a.fnStateLoad.call(a.oInstance, a);
                    if (c) {
                        var d = _fnCallbackFire(a, "aoStateLoadParams", "stateLoadParams", [a, c]);
                        if ( - 1 === $.inArray(!1, d)) {
                            a.oLoadedState = $.extend(!0, {},
                            c),
                            a._iDisplayStart = c.iStart,
                            a.iInitDisplayStart = c.iStart,
                            a._iDisplayEnd = c.iEnd,
                            a._iDisplayLength = c.iLength,
                            a.aaSorting = c.aaSorting.slice(),
                            a.saved_aaSorting = c.aaSorting.slice(),
                            $.extend(a.oPreviousSearch, c.oSearch),
                            $.extend(!0, a.aoPreSearchCols, c.aoSearchCols),
                            b.saved_aoColumns = [];
                            for (var e = 0; e < c.abVisCols.length; e++) b.saved_aoColumns[e] = {},
                            b.saved_aoColumns[e].bVisible = c.abVisCols[e];
                            _fnCallbackFire(a, "aoStateLoaded", "stateLoaded", [a, c])
                        }
                    }
                }
            }
            function _fnCreateCookie(sName, sValue, iSecs, sBaseName, fnCallback) {
                var date = new Date;
                date.setTime(date.getTime() + 1e3 * iSecs);
                var aParts = window.location.pathname.split("/"),
                sNameFile = sName + "_" + aParts.pop().replace(/[\/:]/g, "").toLowerCase(),
                sFullCookie,
                oData;
                null !== fnCallback ? (oData = "function" == typeof $.parseJSON ? $.parseJSON(sValue) : eval("(" + sValue + ")"), sFullCookie = fnCallback(sNameFile, oData, date.toGMTString(), aParts.join("/") + "/")) : sFullCookie = sNameFile + "=" + encodeURIComponent(sValue) + "; expires=" + date.toGMTString() + "; path=" + aParts.join("/") + "/";
                var aCookies = document.cookie.split(";"),
                iNewCookieLen = sFullCookie.split(";")[0].length,
                aOldCookies = [];
                if (iNewCookieLen + document.cookie.length + 10 > 4096) {
                    for (var i = 0,
                    iLen = aCookies.length; iLen > i; i++) if ( - 1 != aCookies[i].indexOf(sBaseName)) {
                        var aSplitCookie = aCookies[i].split("=");
                        try {
                            oData = eval("(" + decodeURIComponent(aSplitCookie[1]) + ")"),
                            oData && oData.iCreate && aOldCookies.push({
                                name: aSplitCookie[0],
                                time: oData.iCreate
                            })
                        } catch(e) {}
                    }
                    for (aOldCookies.sort(function(a, b) {
                        return b.time - a.time
                    }); iNewCookieLen + document.cookie.length + 10 > 4096;) {
                        if (0 === aOldCookies.length) return;
                        var old = aOldCookies.pop();
                        document.cookie = old.name + "=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=" + aParts.join("/") + "/"
                    }
                }
                document.cookie = sFullCookie
            }
            function _fnReadCookie(a) {
                for (var b = window.location.pathname.split("/"), c = a + "_" + b[b.length - 1].replace(/[\/:]/g, "").toLowerCase() + "=", d = document.cookie.split(";"), e = 0; e < d.length; e++) {
                    for (var f = d[e];
                    " " == f.charAt(0);) f = f.substring(1, f.length);
                    if (0 === f.indexOf(c)) return decodeURIComponent(f.substring(c.length, f.length))
                }
                return null
            }
            function _fnSettingsFromNode(a) {
                for (var b = 0; b < DataTable.settings.length; b++) if (DataTable.settings[b].nTable === a) return DataTable.settings[b];
                return null
            }
            function _fnGetTrNodes(a) {
                for (var b = [], c = a.aoData, d = 0, e = c.length; e > d; d++) null !== c[d].nTr && b.push(c[d].nTr);
                return b
            }
            function _fnGetTdNodes(a, b) {
                var c, d, e, f, g, h, i, j, k = [],
                l = a.aoData.length,
                m = 0,
                n = l;
                for (b !== undefined && (m = b, n = b + 1), f = m; n > f; f++) if (i = a.aoData[f], null !== i.nTr) {
                    for (d = [], e = i.nTr.firstChild; e;) j = e.nodeName.toLowerCase(),
                    ("td" == j || "th" == j) && d.push(e),
                    e = e.nextSibling;
                    for (c = 0, g = 0, h = a.aoColumns.length; h > g; g++) a.aoColumns[g].bVisible ? k.push(d[g - c]) : (k.push(i._anHidden[g]), c++)
                }
                return k
            }
            function _fnLog(a, b, c) {
                var d = null === a ? "DataTables warning: " + c: "DataTables warning (table id = '" + a.sTableId + "'): " + c;
                if (0 === b) {
                    if ("alert" != DataTable.ext.sErrMode) throw new Error(d);
                    return alert(d),
                    void 0
                }
                window.console && console.log && console.log(d)
            }
            function _fnMap(a, b, c, d) {
                d === undefined && (d = c),
                b[c] !== undefined && (a[d] = b[c])
            }
            function _fnExtend(a, b) {
                var c;
                for (var d in b) b.hasOwnProperty(d) && (c = b[d], "object" == typeof oInit[d] && null !== c && $.isArray(c) === !1 ? $.extend(!0, a[d], c) : a[d] = c);
                return a
            }
            function _fnBindAction(a, b, c) {
                $(a).bind("click.DT", b,
                function(b) {
                    a.blur(),
                    c(b)
                }).bind("keypress.DT", b,
                function(a) {
                    13 === a.which && c(a)
                }).bind("selectstart.DT",
                function() {
                    return ! 1
                })
            }
            function _fnCallbackReg(a, b, c, d) {
                c && a[b].push({
                    fn: c,
                    sName: d
                })
            }
            function _fnCallbackFire(a, b, c, d) {
                for (var e = a[b], f = [], g = e.length - 1; g >= 0; g--) f.push(e[g].fn.apply(a.oInstance, d));
                return null !== c && $(a.oInstance).trigger(c, d),
                f
            }
            function _fnBrowserDetect(a) {
                var b = $('<div style="position:absolute; top:0; left:0; height:1px; width:1px; overflow:hidden"><div style="position:absolute; top:1px; left:1px; width:100px; overflow:scroll;"><div id="DT_BrowserTest" style="width:100%; height:10px;"></div></div></div>')[0];
                document.body.appendChild(b),
                a.oBrowser.bScrollOversize = 100 === $("#DT_BrowserTest", b)[0].offsetWidth ? !0 : !1,
                document.body.removeChild(b)
            }
            function _fnExternApiFunc(a) {
                return function() {
                    var b = [_fnSettingsFromNode(this[DataTable.ext.iApiIndex])].concat(Array.prototype.slice.call(arguments));
                    return DataTable.ext.oApi[a].apply(this, b)
                }
            }
            var __reArray = /\[.*?\]$/,
            _fnJsonString = window.JSON ? JSON.stringify: function(a) {
                var b = typeof a;
                if ("object" !== b || null === a) return "string" === b && (a = '"' + a + '"'),
                a + "";
                var c, d, e = [],
                f = $.isArray(a);
                for (c in a) d = a[c],
                b = typeof d,
                "string" === b ? d = '"' + d + '"': "object" === b && null !== d && (d = _fnJsonString(d)),
                e.push((f ? "": '"' + c + '":') + d);
                return (f ? "[": "{") + e + (f ? "]": "}")
            };
            this.$ = function(a, b) {
                var c, d, e, f = [],
                g = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                h = g.aoData,
                i = g.aiDisplay,
                j = g.aiDisplayMaster;
                if (b || (b = {}), b = $.extend({},
                {
                    filter: "none",
                    order: "current",
                    page: "all"
                },
                b), "current" == b.page) for (c = g._iDisplayStart, d = g.fnDisplayEnd(); d > c; c++) e = h[i[c]].nTr,
                e && f.push(e);
                else if ("current" == b.order && "none" == b.filter) for (c = 0, d = j.length; d > c; c++) e = h[j[c]].nTr,
                e && f.push(e);
                else if ("current" == b.order && "applied" == b.filter) for (c = 0, d = i.length; d > c; c++) e = h[i[c]].nTr,
                e && f.push(e);
                else if ("original" == b.order && "none" == b.filter) for (c = 0, d = h.length; d > c; c++) e = h[c].nTr,
                e && f.push(e);
                else if ("original" == b.order && "applied" == b.filter) for (c = 0, d = h.length; d > c; c++) e = h[c].nTr,
                -1 !== $.inArray(c, i) && e && f.push(e);
                else _fnLog(g, 1, "Unknown selection options");
                var k = $(f),
                l = k.filter(a),
                m = k.find(a);
                return $([].concat($.makeArray(l), $.makeArray(m)))
            },
            this._ = function(a, b) {
                var c, d, e = [],
                f = this.$(a, b);
                for (c = 0, d = f.length; d > c; c++) e.push(this.fnGetData(f[c]));
                return e
            },
            this.fnAddData = function(a, b) {
                if (0 === a.length) return [];
                var c, d = [],
                e = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                if ("object" == typeof a[0] && null !== a[0]) for (var f = 0; f < a.length; f++) {
                    if (c = _fnAddData(e, a[f]), -1 == c) return d;
                    d.push(c)
                } else {
                    if (c = _fnAddData(e, a), -1 == c) return d;
                    d.push(c)
                }
                return e.aiDisplay = e.aiDisplayMaster.slice(),
                (b === undefined || b) && _fnReDraw(e),
                d
            },
            this.fnAdjustColumnSizing = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                _fnAdjustColumnSizing(b),
                a === undefined || a ? this.fnDraw(!1) : ("" !== b.oScroll.sX || "" !== b.oScroll.sY) && this.oApi._fnScrollDraw(b)
            },
            this.fnClearTable = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                _fnClearTable(b),
                (a === undefined || a) && _fnDraw(b)
            },
            this.fnClose = function(a) {
                for (var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]), c = 0; c < b.aoOpenRows.length; c++) if (b.aoOpenRows[c].nParent == a) {
                    var d = b.aoOpenRows[c].nTr.parentNode;
                    return d && d.removeChild(b.aoOpenRows[c].nTr),
                    b.aoOpenRows.splice(c, 1),
                    0
                }
                return 1
            },
            this.fnDeleteRow = function(a, b, c) {
                var d, e, f, g = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                f = "object" == typeof a ? _fnNodeToDataIndex(g, a) : a;
                var h = g.aoData.splice(f, 1);
                for (d = 0, e = g.aoData.length; e > d; d++) null !== g.aoData[d].nTr && (g.aoData[d].nTr._DT_RowIndex = d);
                var i = $.inArray(f, g.aiDisplay);
                return g.asDataSearch.splice(i, 1),
                _fnDeleteIndex(g.aiDisplayMaster, f),
                _fnDeleteIndex(g.aiDisplay, f),
                "function" == typeof b && b.call(this, g, h),
                g._iDisplayStart >= g.fnRecordsDisplay() && (g._iDisplayStart -= g._iDisplayLength, g._iDisplayStart < 0 && (g._iDisplayStart = 0)),
                (c === undefined || c) && (_fnCalculateEnd(g), _fnDraw(g)),
                h
            },
            this.fnDestroy = function(a) {
                var b, c, d = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                e = d.nTableWrapper.parentNode,
                f = d.nTBody;
                if (a = a === undefined ? !1 : a, d.bDestroying = !0, _fnCallbackFire(d, "aoDestroyCallback", "destroy", [d]), !a) for (b = 0, c = d.aoColumns.length; c > b; b++) d.aoColumns[b].bVisible === !1 && this.fnSetColumnVis(b, !0);
                for ($(d.nTableWrapper).find("*").andSelf().unbind(".DT"), $("tbody>tr>td." + d.oClasses.sRowEmpty, d.nTable).parent().remove(), d.nTable != d.nTHead.parentNode && ($(d.nTable).children("thead").remove(), d.nTable.appendChild(d.nTHead)), d.nTFoot && d.nTable != d.nTFoot.parentNode && ($(d.nTable).children("tfoot").remove(), d.nTable.appendChild(d.nTFoot)), d.nTable.parentNode.removeChild(d.nTable), $(d.nTableWrapper).remove(), d.aaSorting = [], d.aaSortingFixed = [], _fnSortingClasses(d), $(_fnGetTrNodes(d)).removeClass(d.asStripeClasses.join(" ")), $("th, td", d.nTHead).removeClass([d.oClasses.sSortable, d.oClasses.sSortableAsc, d.oClasses.sSortableDesc, d.oClasses.sSortableNone].join(" ")), d.bJUI && ($("th span." + d.oClasses.sSortIcon + ", td span." + d.oClasses.sSortIcon, d.nTHead).remove(), $("th, td", d.nTHead).each(function() {
                    var a = $("div." + d.oClasses.sSortJUIWrapper, this),
                    b = a.contents();
                    $(this).append(b),
                    a.remove()
                })), !a && d.nTableReinsertBefore ? e.insertBefore(d.nTable, d.nTableReinsertBefore) : a || e.appendChild(d.nTable), b = 0, c = d.aoData.length; c > b; b++) null !== d.aoData[b].nTr && f.appendChild(d.aoData[b].nTr);
                if (d.oFeatures.bAutoWidth === !0 && (d.nTable.style.width = _fnStringToCss(d.sDestroyWidth)), c = d.asDestroyStripes.length) {
                    var g = $(f).children("tr");
                    for (b = 0; c > b; b++) g.filter(":nth-child(" + c + "n + " + b + ")").addClass(d.asDestroyStripes[b])
                }
                for (b = 0, c = DataTable.settings.length; c > b; b++) DataTable.settings[b] == d && DataTable.settings.splice(b, 1);
                d = null,
                oInit = null
            },
            this.fnDraw = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                a === !1 ? (_fnCalculateEnd(b), _fnDraw(b)) : _fnReDraw(b)
            },
            this.fnFilter = function(a, b, c, d, e, f) {
                var g = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                if (g.oFeatures.bFilter) if ((c === undefined || null === c) && (c = !1), (d === undefined || null === d) && (d = !0), (e === undefined || null === e) && (e = !0), (f === undefined || null === f) && (f = !0), b === undefined || null === b) {
                    if (_fnFilterComplete(g, {
                        sSearch: a + "",
                        bRegex: c,
                        bSmart: d,
                        bCaseInsensitive: f
                    },
                    1), e && g.aanFeatures.f) for (var h = g.aanFeatures.f,
                    i = 0,
                    j = h.length; j > i; i++) try {
                        h[i]._DT_Input != document.activeElement && $(h[i]._DT_Input).val(a)
                    } catch(k) {
                        $(h[i]._DT_Input).val(a)
                    }
                } else $.extend(g.aoPreSearchCols[b], {
                    sSearch: a + "",
                    bRegex: c,
                    bSmart: d,
                    bCaseInsensitive: f
                }),
                _fnFilterComplete(g, g.oPreviousSearch, 1)
            },
            this.fnGetData = function(a, b) {
                var c = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                if (a !== undefined) {
                    var d = a;
                    if ("object" == typeof a) {
                        var e = a.nodeName.toLowerCase();
                        "tr" === e ? d = _fnNodeToDataIndex(c, a) : "td" === e && (d = _fnNodeToDataIndex(c, a.parentNode), b = _fnNodeToColumnIndex(c, d, a))
                    }
                    return b !== undefined ? _fnGetCellData(c, d, b, "") : c.aoData[d] !== undefined ? c.aoData[d]._aData: null
                }
                return _fnGetDataMaster(c)
            },
            this.fnGetNodes = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                return a !== undefined ? b.aoData[a] !== undefined ? b.aoData[a].nTr: null: _fnGetTrNodes(b)
            },
            this.fnGetPosition = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                c = a.nodeName.toUpperCase();
                if ("TR" == c) return _fnNodeToDataIndex(b, a);
                if ("TD" == c || "TH" == c) {
                    var d = _fnNodeToDataIndex(b, a.parentNode),
                    e = _fnNodeToColumnIndex(b, d, a);
                    return [d, _fnColumnIndexToVisible(b, e), e]
                }
                return null
            },
            this.fnIsOpen = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                b.aoOpenRows;
                for (var c = 0; c < b.aoOpenRows.length; c++) if (b.aoOpenRows[c].nParent == a) return ! 0;
                return ! 1
            },
            this.fnOpen = function(a, b, c) {
                var d = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                e = _fnGetTrNodes(d);
                if ( - 1 !== $.inArray(a, e)) {
                    this.fnClose(a);
                    var f = document.createElement("tr"),
                    g = document.createElement("td");
                    f.appendChild(g),
                    g.className = c,
                    g.colSpan = _fnVisbleColumns(d),
                    "string" == typeof b ? g.innerHTML = b: $(g).html(b);
                    var h = $("tr", d.nTBody);
                    return - 1 != $.inArray(a, h) && $(f).insertAfter(a),
                    d.aoOpenRows.push({
                        nTr: f,
                        nParent: a
                    }),
                    f
                }
            },
            this.fnPageChange = function(a, b) {
                var c = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                _fnPageChange(c, a),
                _fnCalculateEnd(c),
                (b === undefined || b) && _fnDraw(c)
            },
            this.fnSetColumnVis = function(a, b, c) {
                var d, e, f, g, h, i = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                j = i.aoColumns,
                k = i.aoData;
                if (j[a].bVisible != b) {
                    if (b) {
                        var l = 0;
                        for (d = 0; a > d; d++) j[d].bVisible && l++;
                        if (g = l >= _fnVisbleColumns(i), !g) for (d = a; d < j.length; d++) if (j[d].bVisible) {
                            h = d;
                            break
                        }
                        for (d = 0, e = k.length; e > d; d++) null !== k[d].nTr && (g ? k[d].nTr.appendChild(k[d]._anHidden[a]) : k[d].nTr.insertBefore(k[d]._anHidden[a], _fnGetTdNodes(i, d)[h]))
                    } else for (d = 0, e = k.length; e > d; d++) null !== k[d].nTr && (f = _fnGetTdNodes(i, d)[a], k[d]._anHidden[a] = f, f.parentNode.removeChild(f));
                    for (j[a].bVisible = b, _fnDrawHead(i, i.aoHeader), i.nTFoot && _fnDrawHead(i, i.aoFooter), d = 0, e = i.aoOpenRows.length; e > d; d++) i.aoOpenRows[d].nTr.colSpan = _fnVisbleColumns(i); (c === undefined || c) && (_fnAdjustColumnSizing(i), _fnDraw(i)),
                    _fnSaveState(i)
                }
            },
            this.fnSettings = function() {
                return _fnSettingsFromNode(this[DataTable.ext.iApiIndex])
            },
            this.fnSort = function(a) {
                var b = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]);
                b.aaSorting = a,
                _fnSort(b)
            },
            this.fnSortListener = function(a, b, c) {
                _fnSortAttachListener(_fnSettingsFromNode(this[DataTable.ext.iApiIndex]), a, b, c)
            },
            this.fnUpdate = function(a, b, c, d, e) {
                var f, g, h = _fnSettingsFromNode(this[DataTable.ext.iApiIndex]),
                i = "object" == typeof b ? _fnNodeToDataIndex(h, b) : b;
                if ($.isArray(a) && c === undefined) for (h.aoData[i]._aData = a.slice(), f = 0; f < h.aoColumns.length; f++) this.fnUpdate(_fnGetCellData(h, i, f), i, f, !1, !1);
                else if ($.isPlainObject(a) && c === undefined) for (h.aoData[i]._aData = $.extend(!0, {},
                a), f = 0; f < h.aoColumns.length; f++) this.fnUpdate(_fnGetCellData(h, i, f), i, f, !1, !1);
                else {
                    _fnSetCellData(h, i, c, a),
                    g = _fnGetCellData(h, i, c, "display");
                    var j = h.aoColumns[c];
                    null !== j.fnRender && (g = _fnRender(h, i, c), j.bUseRendered && _fnSetCellData(h, i, c, g)),
                    null !== h.aoData[i].nTr && (_fnGetTdNodes(h, i)[c].innerHTML = g)
                }
                var k = $.inArray(i, h.aiDisplay);
                return h.asDataSearch[k] = _fnBuildSearchRow(h, _fnGetRowData(h, i, "filter", _fnGetColumns(h, "bSearchable"))),
                (e === undefined || e) && _fnAdjustColumnSizing(h),
                (d === undefined || d) && _fnReDraw(h),
                0
            },
            this.fnVersionCheck = DataTable.ext.fnVersionCheck,
            this.oApi = {
                _fnExternApiFunc: _fnExternApiFunc,
                _fnInitialise: _fnInitialise,
                _fnInitComplete: _fnInitComplete,
                _fnLanguageCompat: _fnLanguageCompat,
                _fnAddColumn: _fnAddColumn,
                _fnColumnOptions: _fnColumnOptions,
                _fnAddData: _fnAddData,
                _fnCreateTr: _fnCreateTr,
                _fnGatherData: _fnGatherData,
                _fnBuildHead: _fnBuildHead,
                _fnDrawHead: _fnDrawHead,
                _fnDraw: _fnDraw,
                _fnReDraw: _fnReDraw,
                _fnAjaxUpdate: _fnAjaxUpdate,
                _fnAjaxParameters: _fnAjaxParameters,
                _fnAjaxUpdateDraw: _fnAjaxUpdateDraw,
                _fnServerParams: _fnServerParams,
                _fnAddOptionsHtml: _fnAddOptionsHtml,
                _fnFeatureHtmlTable: _fnFeatureHtmlTable,
                _fnScrollDraw: _fnScrollDraw,
                _fnAdjustColumnSizing: _fnAdjustColumnSizing,
                _fnFeatureHtmlFilter: _fnFeatureHtmlFilter,
                _fnFilterComplete: _fnFilterComplete,
                _fnFilterCustom: _fnFilterCustom,
                _fnFilterColumn: _fnFilterColumn,
                _fnFilter: _fnFilter,
                _fnBuildSearchArray: _fnBuildSearchArray,
                _fnBuildSearchRow: _fnBuildSearchRow,
                _fnFilterCreateSearch: _fnFilterCreateSearch,
                _fnDataToSearch: _fnDataToSearch,
                _fnSort: _fnSort,
                _fnSortAttachListener: _fnSortAttachListener,
                _fnSortingClasses: _fnSortingClasses,
                _fnFeatureHtmlPaginate: _fnFeatureHtmlPaginate,
                _fnPageChange: _fnPageChange,
                _fnFeatureHtmlInfo: _fnFeatureHtmlInfo,
                _fnUpdateInfo: _fnUpdateInfo,
                _fnFeatureHtmlLength: _fnFeatureHtmlLength,
                _fnFeatureHtmlProcessing: _fnFeatureHtmlProcessing,
                _fnProcessingDisplay: _fnProcessingDisplay,
                _fnVisibleToColumnIndex: _fnVisibleToColumnIndex,
                _fnColumnIndexToVisible: _fnColumnIndexToVisible,
                _fnNodeToDataIndex: _fnNodeToDataIndex,
                _fnVisbleColumns: _fnVisbleColumns,
                _fnCalculateEnd: _fnCalculateEnd,
                _fnConvertToWidth: _fnConvertToWidth,
                _fnCalculateColumnWidths: _fnCalculateColumnWidths,
                _fnScrollingWidthAdjust: _fnScrollingWidthAdjust,
                _fnGetWidestNode: _fnGetWidestNode,
                _fnGetMaxLenString: _fnGetMaxLenString,
                _fnStringToCss: _fnStringToCss,
                _fnDetectType: _fnDetectType,
                _fnSettingsFromNode: _fnSettingsFromNode,
                _fnGetDataMaster: _fnGetDataMaster,
                _fnGetTrNodes: _fnGetTrNodes,
                _fnGetTdNodes: _fnGetTdNodes,
                _fnEscapeRegex: _fnEscapeRegex,
                _fnDeleteIndex: _fnDeleteIndex,
                _fnReOrderIndex: _fnReOrderIndex,
                _fnColumnOrdering: _fnColumnOrdering,
                _fnLog: _fnLog,
                _fnClearTable: _fnClearTable,
                _fnSaveState: _fnSaveState,
                _fnLoadState: _fnLoadState,
                _fnCreateCookie: _fnCreateCookie,
                _fnReadCookie: _fnReadCookie,
                _fnDetectHeader: _fnDetectHeader,
                _fnGetUniqueThs: _fnGetUniqueThs,
                _fnScrollBarWidth: _fnScrollBarWidth,
                _fnApplyToChildren: _fnApplyToChildren,
                _fnMap: _fnMap,
                _fnGetRowData: _fnGetRowData,
                _fnGetCellData: _fnGetCellData,
                _fnSetCellData: _fnSetCellData,
                _fnGetObjectDataFn: _fnGetObjectDataFn,
                _fnSetObjectDataFn: _fnSetObjectDataFn,
                _fnApplyColumnDefs: _fnApplyColumnDefs,
                _fnBindAction: _fnBindAction,
                _fnExtend: _fnExtend,
                _fnCallbackReg: _fnCallbackReg,
                _fnCallbackFire: _fnCallbackFire,
                _fnJsonString: _fnJsonString,
                _fnRender: _fnRender,
                _fnNodeToColumnIndex: _fnNodeToColumnIndex,
                _fnInfoMacros: _fnInfoMacros,
                _fnBrowserDetect: _fnBrowserDetect,
                _fnGetColumns: _fnGetColumns
            },
            $.extend(DataTable.ext.oApi, this.oApi);
            for (var sFunc in DataTable.ext.oApi) sFunc && (this[sFunc] = _fnExternApiFunc(sFunc));
            var _that = this;
            return this.each(function() {
                var a, b, c, d = 0,
                e = this.getAttribute("id"),
                f = !1,
                g = !1;
                if ("table" != this.nodeName.toLowerCase()) return _fnLog(null, 0, "Attempted to initialise DataTables on a node which is not a table: " + this.nodeName),
                void 0;
                for (d = 0, a = DataTable.settings.length; a > d; d++) {
                    if (DataTable.settings[d].nTable == this) {
                        if (oInit === undefined || oInit.bRetrieve) return DataTable.settings[d].oInstance;
                        if (oInit.bDestroy) {
                            DataTable.settings[d].oInstance.fnDestroy();
                            break
                        }
                        return _fnLog(DataTable.settings[d], 0, "Cannot reinitialise DataTable.\n\nTo retrieve the DataTables object for this table, pass no arguments or see the docs for bRetrieve and bDestroy"),
                        void 0
                    }
                    if (DataTable.settings[d].sTableId == this.id) {
                        DataTable.settings.splice(d, 1);
                        break
                    }
                } (null === e || "" === e) && (e = "DataTables_Table_" + DataTable.ext._oExternConfig.iNextUnique++, this.id = e);
                var h = $.extend(!0, {},
                DataTable.models.oSettings, {
                    nTable: this,
                    oApi: _that.oApi,
                    oInit: oInit,
                    sDestroyWidth: $(this).width(),
                    sInstance: e,
                    sTableId: e
                });
                if (DataTable.settings.push(h), h.oInstance = 1 === _that.length ? _that: $(this).dataTable(), oInit || (oInit = {}), oInit.oLanguage && _fnLanguageCompat(oInit.oLanguage), oInit = _fnExtend($.extend(!0, {},
                DataTable.defaults), oInit), _fnMap(h.oFeatures, oInit, "bPaginate"), _fnMap(h.oFeatures, oInit, "bLengthChange"), _fnMap(h.oFeatures, oInit, "bFilter"), _fnMap(h.oFeatures, oInit, "bSort"), _fnMap(h.oFeatures, oInit, "bInfo"), _fnMap(h.oFeatures, oInit, "bProcessing"), _fnMap(h.oFeatures, oInit, "bAutoWidth"), _fnMap(h.oFeatures, oInit, "bSortClasses"), _fnMap(h.oFeatures, oInit, "bServerSide"), _fnMap(h.oFeatures, oInit, "bDeferRender"), _fnMap(h.oScroll, oInit, "sScrollX", "sX"), _fnMap(h.oScroll, oInit, "sScrollXInner", "sXInner"), _fnMap(h.oScroll, oInit, "sScrollY", "sY"), _fnMap(h.oScroll, oInit, "bScrollCollapse", "bCollapse"), _fnMap(h.oScroll, oInit, "bScrollInfinite", "bInfinite"), _fnMap(h.oScroll, oInit, "iScrollLoadGap", "iLoadGap"), _fnMap(h.oScroll, oInit, "bScrollAutoCss", "bAutoCss"), _fnMap(h, oInit, "asStripeClasses"), _fnMap(h, oInit, "asStripClasses", "asStripeClasses"), _fnMap(h, oInit, "fnServerData"), _fnMap(h, oInit, "fnFormatNumber"), _fnMap(h, oInit, "sServerMethod"), _fnMap(h, oInit, "aaSorting"), _fnMap(h, oInit, "aaSortingFixed"), _fnMap(h, oInit, "aLengthMenu"), _fnMap(h, oInit, "sPaginationType"), _fnMap(h, oInit, "sAjaxSource"), _fnMap(h, oInit, "sAjaxDataProp"), _fnMap(h, oInit, "iCookieDuration"), _fnMap(h, oInit, "sCookiePrefix"), _fnMap(h, oInit, "sDom"), _fnMap(h, oInit, "bSortCellsTop"), _fnMap(h, oInit, "iTabIndex"), _fnMap(h, oInit, "oSearch", "oPreviousSearch"), _fnMap(h, oInit, "aoSearchCols", "aoPreSearchCols"), _fnMap(h, oInit, "iDisplayLength", "_iDisplayLength"), _fnMap(h, oInit, "bJQueryUI", "bJUI"), _fnMap(h, oInit, "fnCookieCallback"), _fnMap(h, oInit, "fnStateLoad"), _fnMap(h, oInit, "fnStateSave"), _fnMap(h.oLanguage, oInit, "fnInfoCallback"), _fnCallbackReg(h, "aoDrawCallback", oInit.fnDrawCallback, "user"), _fnCallbackReg(h, "aoServerParams", oInit.fnServerParams, "user"), _fnCallbackReg(h, "aoStateSaveParams", oInit.fnStateSaveParams, "user"), _fnCallbackReg(h, "aoStateLoadParams", oInit.fnStateLoadParams, "user"), _fnCallbackReg(h, "aoStateLoaded", oInit.fnStateLoaded, "user"), _fnCallbackReg(h, "aoRowCallback", oInit.fnRowCallback, "user"), _fnCallbackReg(h, "aoRowCreatedCallback", oInit.fnCreatedRow, "user"), _fnCallbackReg(h, "aoHeaderCallback", oInit.fnHeaderCallback, "user"), _fnCallbackReg(h, "aoFooterCallback", oInit.fnFooterCallback, "user"), _fnCallbackReg(h, "aoInitComplete", oInit.fnInitComplete, "user"), _fnCallbackReg(h, "aoPreDrawCallback", oInit.fnPreDrawCallback, "user"), h.oFeatures.bServerSide && h.oFeatures.bSort && h.oFeatures.bSortClasses ? _fnCallbackReg(h, "aoDrawCallback", _fnSortingClasses, "server_side_sort_classes") : h.oFeatures.bDeferRender && _fnCallbackReg(h, "aoDrawCallback", _fnSortingClasses, "defer_sort_classes"), oInit.bJQueryUI ? ($.extend(h.oClasses, DataTable.ext.oJUIClasses), oInit.sDom === DataTable.defaults.sDom && "lfrtip" === DataTable.defaults.sDom && (h.sDom = '<"H"lfr>t<"F"ip>')) : $.extend(h.oClasses, DataTable.ext.oStdClasses), $(this).addClass(h.oClasses.sTable), ("" !== h.oScroll.sX || "" !== h.oScroll.sY) && (h.oScroll.iBarWidth = _fnScrollBarWidth()), h.iInitDisplayStart === undefined && (h.iInitDisplayStart = oInit.iDisplayStart, h._iDisplayStart = oInit.iDisplayStart), oInit.bStateSave && (h.oFeatures.bStateSave = !0, _fnLoadState(h, oInit), _fnCallbackReg(h, "aoDrawCallback", _fnSaveState, "state_save")), null !== oInit.iDeferLoading) {
                    h.bDeferLoading = !0;
                    var i = $.isArray(oInit.iDeferLoading);
                    h._iRecordsDisplay = i ? oInit.iDeferLoading[0] : oInit.iDeferLoading,
                    h._iRecordsTotal = i ? oInit.iDeferLoading[1] : oInit.iDeferLoading
                }
                if (null !== oInit.aaData && (g = !0), "" !== oInit.oLanguage.sUrl ? (h.oLanguage.sUrl = oInit.oLanguage.sUrl, $.getJSON(h.oLanguage.sUrl, null,
                function(a) {
                    _fnLanguageCompat(a),
                    $.extend(!0, h.oLanguage, oInit.oLanguage, a),
                    _fnInitialise(h)
                }), f = !0) : $.extend(!0, h.oLanguage, oInit.oLanguage), null === oInit.asStripeClasses && (h.asStripeClasses = [h.oClasses.sStripeOdd, h.oClasses.sStripeEven]), a = h.asStripeClasses.length, h.asDestroyStripes = [], a) {
                    var j = !1,
                    k = $(this).children("tbody").children("tr:lt(" + a + ")");
                    for (d = 0; a > d; d++) k.hasClass(h.asStripeClasses[d]) && (j = !0, h.asDestroyStripes.push(h.asStripeClasses[d]));
                    j && k.removeClass(h.asStripeClasses.join(" "))
                }
                var l, m = [],
                n = this.getElementsByTagName("thead");
                if (0 !== n.length && (_fnDetectHeader(h.aoHeader, n[0]), m = _fnGetUniqueThs(h)), null === oInit.aoColumns) for (l = [], d = 0, a = m.length; a > d; d++) l.push(null);
                else l = oInit.aoColumns;
                for (d = 0, a = l.length; a > d; d++) oInit.saved_aoColumns !== undefined && oInit.saved_aoColumns.length == a && (null === l[d] && (l[d] = {}), l[d].bVisible = oInit.saved_aoColumns[d].bVisible),
                _fnAddColumn(h, m ? m[d] : null);
                for (_fnApplyColumnDefs(h, oInit.aoColumnDefs, l,
                function(a, b) {
                    _fnColumnOptions(h, a, b)
                }), d = 0, a = h.aaSorting.length; a > d; d++) {
                    h.aaSorting[d][0] >= h.aoColumns.length && (h.aaSorting[d][0] = 0);
                    var o = h.aoColumns[h.aaSorting[d][0]];
                    for (h.aaSorting[d][2] === undefined && (h.aaSorting[d][2] = 0), oInit.aaSorting === undefined && h.saved_aaSorting === undefined && (h.aaSorting[d][1] = o.asSorting[0]), b = 0, c = o.asSorting.length; c > b; b++) if (h.aaSorting[d][1] == o.asSorting[b]) {
                        h.aaSorting[d][2] = b;
                        break
                    }
                }
                _fnSortingClasses(h),
                _fnBrowserDetect(h);
                var p = $(this).children("caption").each(function() {
                    this._captionSide = $(this).css("caption-side")
                }),
                q = $(this).children("thead");
                0 === q.length && (q = [document.createElement("thead")], this.appendChild(q[0])),
                h.nTHead = q[0];
                var r = $(this).children("tbody");
                0 === r.length && (r = [document.createElement("tbody")], this.appendChild(r[0])),
                h.nTBody = r[0],
                h.nTBody.setAttribute("role", "alert"),
                h.nTBody.setAttribute("aria-live", "polite"),
                h.nTBody.setAttribute("aria-relevant", "all");
                var s = $(this).children("tfoot");
                if (0 === s.length && p.length > 0 && ("" !== h.oScroll.sX || "" !== h.oScroll.sY) && (s = [document.createElement("tfoot")], this.appendChild(s[0])), s.length > 0 && (h.nTFoot = s[0], _fnDetectHeader(h.aoFooter, h.nTFoot)), g) for (d = 0; d < oInit.aaData.length; d++) _fnAddData(h, oInit.aaData[d]);
                else _fnGatherData(h);
                h.aiDisplay = h.aiDisplayMaster.slice(),
                h.bInitialised = !0,
                f === !1 && _fnInitialise(h)
            }),
            _that = null,
            this
        };
        DataTable.fnVersionCheck = function(a) {
            for (var b = function(a, b) {
                for (; a.length < b;) a += "0";
                return a
            },
            c = DataTable.ext.sVersion.split("."), d = a.split("."), e = "", f = "", g = 0, h = d.length; h > g; g++) e += b(c[g], 3),
            f += b(d[g], 3);
            return parseInt(e, 10) >= parseInt(f, 10)
        },
        DataTable.fnIsDataTable = function(a) {
            for (var b = DataTable.settings,
            c = 0; c < b.length; c++) if (b[c].nTable === a || b[c].nScrollHead === a || b[c].nScrollFoot === a) return ! 0;
            return ! 1
        },
        DataTable.fnTables = function(a) {
            var b = [];
            return jQuery.each(DataTable.settings,
            function(c, d) { (!a || a === !0 && $(d.nTable).is(":visible")) && b.push(d.nTable)
            }),
            b
        },
        DataTable.version = "1.9.4",
        DataTable.settings = [],
        DataTable.models = {},
        DataTable.models.ext = {
            afnFiltering: [],
            afnSortData: [],
            aoFeatures: [],
            aTypes: [],
            fnVersionCheck: DataTable.fnVersionCheck,
            iApiIndex: 0,
            ofnSearch: {},
            oApi: {},
            oStdClasses: {},
            oJUIClasses: {},
            oPagination: {},
            oSort: {},
            sVersion: DataTable.version,
            sErrMode: "alert",
            _oExternConfig: {
                iNextUnique: 0
            }
        },
        DataTable.models.oSearch = {
            bCaseInsensitive: !0,
            sSearch: "",
            bRegex: !1,
            bSmart: !0
        },
        DataTable.models.oRow = {
            nTr: null,
            _aData: [],
            _aSortData: [],
            _anHidden: [],
            _sRowStripe: ""
        },
        DataTable.models.oColumn = {
            aDataSort: null,
            asSorting: null,
            bSearchable: null,
            bSortable: null,
            bUseRendered: null,
            bVisible: null,
            _bAutoType: !0,
            fnCreatedCell: null,
            fnGetData: null,
            fnRender: null,
            fnSetData: null,
            mData: null,
            mRender: null,
            nTh: null,
            nTf: null,
            sClass: null,
            sContentPadding: null,
            sDefaultContent: null,
            sName: null,
            sSortDataType: "std",
            sSortingClass: null,
            sSortingClassJUI: null,
            sTitle: null,
            sType: null,
            sWidth: null,
            sWidthOrig: null
        },
        DataTable.defaults = {
            aaData: null,
            aaSorting: [[0, "asc"]],
            aaSortingFixed: null,
            aLengthMenu: [10, 25, 50, 100],
            aoColumns: null,
            aoColumnDefs: null,
            aoSearchCols: [],
            asStripeClasses: null,
            bAutoWidth: !0,
            bDeferRender: !1,
            bDestroy: !1,
            bFilter: !0,
            bInfo: !0,
            bJQueryUI: !1,
            bLengthChange: !0,
            bPaginate: !0,
            bProcessing: !1,
            bRetrieve: !1,
            bScrollAutoCss: !0,
            bScrollCollapse: !1,
            bScrollInfinite: !1,
            bServerSide: !1,
            bSort: !0,
            bSortCellsTop: !1,
            bSortClasses: !0,
            bStateSave: !1,
            fnCookieCallback: null,
            fnCreatedRow: null,
            fnDrawCallback: null,
            fnFooterCallback: null,
            fnFormatNumber: function(a) {
                if (1e3 > a) return a;
                for (var b = a + "",
                c = b.split(""), d = "", e = b.length, f = 0; e > f; f++) 0 === f % 3 && 0 !== f && (d = this.oLanguage.sInfoThousands + d),
                d = c[e - f - 1] + d;
                return d
            },
            fnHeaderCallback: null,
            fnInfoCallback: null,
            fnInitComplete: null,
            fnPreDrawCallback: null,
            fnRowCallback: null,
            fnServerData: function(a, b, c, d) {
                d.jqXHR = $.ajax({
                    url: a,
                    data: b,
                    success: function(a) {
                        a.sError && d.oApi._fnLog(d, 0, a.sError),
                        $(d.oInstance).trigger("xhr", [d, a]),
                        c(a)
                    },
                    dataType: "json",
                    cache: !1,
                    type: d.sServerMethod,
                    error: function(a, b) {
                        "parsererror" == b && d.oApi._fnLog(d, 0, "DataTables warning: JSON data from server could not be parsed. This is caused by a JSON formatting error.")
                    }
                })
            },
            fnServerParams: null,
            fnStateLoad: function(oSettings) {
                var sData = this.oApi._fnReadCookie(oSettings.sCookiePrefix + oSettings.sInstance),
                oData;
                try {
                    oData = "function" == typeof $.parseJSON ? $.parseJSON(sData) : eval("(" + sData + ")")
                } catch(e) {
                    oData = null
                }
                return oData
            },
            fnStateLoadParams: null,
            fnStateLoaded: null,
            fnStateSave: function(a, b) {
                this.oApi._fnCreateCookie(a.sCookiePrefix + a.sInstance, this.oApi._fnJsonString(b), a.iCookieDuration, a.sCookiePrefix, a.fnCookieCallback)
            },
            fnStateSaveParams: null,
            iCookieDuration: 7200,
            iDeferLoading: null,
            iDisplayLength: 10,
            iDisplayStart: 0,
            iScrollLoadGap: 100,
            iTabIndex: 0,
            oLanguage: {
                oAria: {
                    sSortAscending: ": activate to sort column ascending",
                    sSortDescending: ": activate to sort column descending"
                },
                oPaginate: {
                    sFirst: "First",
                    sLast: "Last",
                    sNext: "Next",
                    sPrevious: "Previous"
                },
                sEmptyTable: "No data available in table",
                sInfo: "Showing _START_ to _END_ of _TOTAL_ entries",
                sInfoEmpty: "Showing 0 to 0 of 0 entries",
                sInfoFiltered: "(filtered from _MAX_ total entries)",
                sInfoPostFix: "",
                sInfoThousands: ",",
                sLengthMenu: "Show _MENU_ entries",
                sLoadingRecords: "Loading...",
                sProcessing: "Processing...",
                sSearch: "Search:",
                sUrl: "",
                sZeroRecords: "No matching records found"
            },
            oSearch: $.extend({},
            DataTable.models.oSearch),
            sAjaxDataProp: "aaData",
            sAjaxSource: null,
            sCookiePrefix: "SpryMedia_DataTables_",
            sDom: "lfrtip",
            sPaginationType: "two_button",
            sScrollX: "",
            sScrollXInner: "",
            sScrollY: "",
            sServerMethod: "GET"
        },
        DataTable.defaults.columns = {
            aDataSort: null,
            asSorting: ["asc", "desc"],
            bSearchable: !0,
            bSortable: !0,
            bUseRendered: !0,
            bVisible: !0,
            fnCreatedCell: null,
            fnRender: null,
            iDataSort: -1,
            mData: null,
            mRender: null,
            sCellType: "td",
            sClass: "",
            sContentPadding: "",
            sDefaultContent: null,
            sName: "",
            sSortDataType: "std",
            sTitle: null,
            sType: null,
            sWidth: null
        },
        DataTable.models.oSettings = {
            oFeatures: {
                bAutoWidth: null,
                bDeferRender: null,
                bFilter: null,
                bInfo: null,
                bLengthChange: null,
                bPaginate: null,
                bProcessing: null,
                bServerSide: null,
                bSort: null,
                bSortClasses: null,
                bStateSave: null
            },
            oScroll: {
                bAutoCss: null,
                bCollapse: null,
                bInfinite: null,
                iBarWidth: 0,
                iLoadGap: null,
                sX: null,
                sXInner: null,
                sY: null
            },
            oLanguage: {
                fnInfoCallback: null
            },
            oBrowser: {
                bScrollOversize: !1
            },
            aanFeatures: [],
            aoData: [],
            aiDisplay: [],
            aiDisplayMaster: [],
            aoColumns: [],
            aoHeader: [],
            aoFooter: [],
            asDataSearch: [],
            oPreviousSearch: {},
            aoPreSearchCols: [],
            aaSorting: null,
            aaSortingFixed: null,
            asStripeClasses: null,
            asDestroyStripes: [],
            sDestroyWidth: 0,
            aoRowCallback: [],
            aoHeaderCallback: [],
            aoFooterCallback: [],
            aoDrawCallback: [],
            aoRowCreatedCallback: [],
            aoPreDrawCallback: [],
            aoInitComplete: [],
            aoStateSaveParams: [],
            aoStateLoadParams: [],
            aoStateLoaded: [],
            sTableId: "",
            nTable: null,
            nTHead: null,
            nTFoot: null,
            nTBody: null,
            nTableWrapper: null,
            bDeferLoading: !1,
            bInitialised: !1,
            aoOpenRows: [],
            sDom: null,
            sPaginationType: "two_button",
            iCookieDuration: 0,
            sCookiePrefix: "",
            fnCookieCallback: null,
            aoStateSave: [],
            aoStateLoad: [],
            oLoadedState: null,
            sAjaxSource: null,
            sAjaxDataProp: null,
            bAjaxDataGet: !0,
            jqXHR: null,
            fnServerData: null,
            aoServerParams: [],
            sServerMethod: null,
            fnFormatNumber: null,
            aLengthMenu: null,
            iDraw: 0,
            bDrawing: !1,
            iDrawError: -1,
            _iDisplayLength: 10,
            _iDisplayStart: 0,
            _iDisplayEnd: 10,
            _iRecordsTotal: 0,
            _iRecordsDisplay: 0,
            bJUI: null,
            oClasses: {},
            bFiltered: !1,
            bSorted: !1,
            bSortCellsTop: null,
            oInit: null,
            aoDestroyCallback: [],
            fnRecordsTotal: function() {
                return this.oFeatures.bServerSide ? parseInt(this._iRecordsTotal, 10) : this.aiDisplayMaster.length
            },
            fnRecordsDisplay: function() {
                return this.oFeatures.bServerSide ? parseInt(this._iRecordsDisplay, 10) : this.aiDisplay.length
            },
            fnDisplayEnd: function() {
                return this.oFeatures.bServerSide ? this.oFeatures.bPaginate === !1 || -1 == this._iDisplayLength ? this._iDisplayStart + this.aiDisplay.length: Math.min(this._iDisplayStart + this._iDisplayLength, this._iRecordsDisplay) : this._iDisplayEnd
            },
            oInstance: null,
            sInstance: null,
            iTabIndex: 0,
            nScrollHead: null,
            nScrollFoot: null
        },
        DataTable.ext = $.extend(!0, {},
        DataTable.models.ext),
        $.extend(DataTable.ext.oStdClasses, {
            sTable: "dataTable",
            sPagePrevEnabled: "paginate_enabled_previous",
            sPagePrevDisabled: "paginate_disabled_previous",
            sPageNextEnabled: "paginate_enabled_next",
            sPageNextDisabled: "paginate_disabled_next",
            sPageJUINext: "",
            sPageJUIPrev: "",
            sPageButton: "paginate_button",
            sPageButtonActive: "paginate_active",
            sPageButtonStaticDisabled: "paginate_button paginate_button_disabled",
            sPageFirst: "first",
            sPagePrevious: "previous",
            sPageNext: "next",
            sPageLast: "last",
            sStripeOdd: "odd",
            sStripeEven: "even",
            sRowEmpty: "dataTables_empty",
            sWrapper: "dataTables_wrapper",
            sFilter: "dataTables_filter",
            sInfo: "dataTables_info",
            sPaging: "dataTables_paginate paging_",
            sLength: "dataTables_length",
            sProcessing: "dataTables_processing",
            sSortAsc: "sorting_asc",
            sSortDesc: "sorting_desc",
            sSortable: "sorting",
            sSortableAsc: "sorting_asc_disabled",
            sSortableDesc: "sorting_desc_disabled",
            sSortableNone: "sorting_disabled",
            sSortColumn: "sorting_",
            sSortJUIAsc: "",
            sSortJUIDesc: "",
            sSortJUI: "",
            sSortJUIAscAllowed: "",
            sSortJUIDescAllowed: "",
            sSortJUIWrapper: "",
            sSortIcon: "",
            sScrollWrapper: "dataTables_scroll",
            sScrollHead: "dataTables_scrollHead",
            sScrollHeadInner: "dataTables_scrollHeadInner",
            sScrollBody: "dataTables_scrollBody",
            sScrollFoot: "dataTables_scrollFoot",
            sScrollFootInner: "dataTables_scrollFootInner",
            sFooterTH: "",
            sJUIHeader: "",
            sJUIFooter: ""
        }),
        $.extend(DataTable.ext.oJUIClasses, DataTable.ext.oStdClasses, {
            sPagePrevEnabled: "fg-button ui-button ui-state-default ui-corner-left",
            sPagePrevDisabled: "fg-button ui-button ui-state-default ui-corner-left ui-state-disabled",
            sPageNextEnabled: "fg-button ui-button ui-state-default ui-corner-right",
            sPageNextDisabled: "fg-button ui-button ui-state-default ui-corner-right ui-state-disabled",
            sPageJUINext: "ui-icon ui-icon-circle-arrow-e",
            sPageJUIPrev: "ui-icon ui-icon-circle-arrow-w",
            sPageButton: "fg-button ui-button ui-state-default",
            sPageButtonActive: "fg-button ui-button ui-state-default ui-state-disabled",
            sPageButtonStaticDisabled: "fg-button ui-button ui-state-default ui-state-disabled",
            sPageFirst: "first ui-corner-tl ui-corner-bl",
            sPageLast: "last ui-corner-tr ui-corner-br",
            sPaging: "dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_",
            sSortAsc: "ui-state-default",
            sSortDesc: "ui-state-default",
            sSortable: "ui-state-default",
            sSortableAsc: "ui-state-default",
            sSortableDesc: "ui-state-default",
            sSortableNone: "ui-state-default",
            sSortJUIAsc: "css_right ui-icon ui-icon-triangle-1-n",
            sSortJUIDesc: "css_right ui-icon ui-icon-triangle-1-s",
            sSortJUI: "css_right ui-icon ui-icon-carat-2-n-s",
            sSortJUIAscAllowed: "css_right ui-icon ui-icon-carat-1-n",
            sSortJUIDescAllowed: "css_right ui-icon ui-icon-carat-1-s",
            sSortJUIWrapper: "DataTables_sort_wrapper",
            sSortIcon: "DataTables_sort_icon",
            sScrollHead: "dataTables_scrollHead ui-state-default",
            sScrollFoot: "dataTables_scrollFoot ui-state-default",
            sFooterTH: "ui-state-default",
            sJUIHeader: "fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix",
            sJUIFooter: "fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"
        }),
        $.extend(DataTable.ext.oPagination, {
            two_button: {
                fnInit: function(a, b, c) {
                    var d = a.oLanguage.oPaginate;
                    a.oClasses;
                    var e = function(b) {
                        a.oApi._fnPageChange(a, b.data.action) && c(a)
                    },
                    f = a.bJUI ? '<a class="' + a.oClasses.sPagePrevDisabled + '" tabindex="' + a.iTabIndex + '" role="button"><span class="' + a.oClasses.sPageJUIPrev + '"></span></a>' + '<a class="' + a.oClasses.sPageNextDisabled + '" tabindex="' + a.iTabIndex + '" role="button"><span class="' + a.oClasses.sPageJUINext + '"></span></a>': '<a class="' + a.oClasses.sPagePrevDisabled + '" tabindex="' + a.iTabIndex + '" role="button">' + d.sPrevious + "</a>" + '<a class="' + a.oClasses.sPageNextDisabled + '" tabindex="' + a.iTabIndex + '" role="button">' + d.sNext + "</a>";
                    $(b).append(f);
                    var g = $("a", b),
                    h = g[0],
                    i = g[1];
                    a.oApi._fnBindAction(h, {
                        action: "previous"
                    },
                    e),
                    a.oApi._fnBindAction(i, {
                        action: "next"
                    },
                    e),
                    a.aanFeatures.p || (b.id = a.sTableId + "_paginate", h.id = a.sTableId + "_previous", i.id = a.sTableId + "_next", h.setAttribute("aria-controls", a.sTableId), i.setAttribute("aria-controls", a.sTableId))
                },
                fnUpdate: function(a) {
                    if (a.aanFeatures.p) for (var b, c = a.oClasses,
                    d = a.aanFeatures.p,
                    e = 0,
                    f = d.length; f > e; e++) b = d[e].firstChild,
                    b && (b.className = 0 === a._iDisplayStart ? c.sPagePrevDisabled: c.sPagePrevEnabled, b = b.nextSibling, b.className = a.fnDisplayEnd() == a.fnRecordsDisplay() ? c.sPageNextDisabled: c.sPageNextEnabled)
                }
            },
            iFullNumbersShowPages: 5,
            full_numbers: {
                fnInit: function(a, b, c) {
                    var d = a.oLanguage.oPaginate,
                    e = a.oClasses,
                    f = function(b) {
                        a.oApi._fnPageChange(a, b.data.action) && c(a)
                    };
                    $(b).append('<a  tabindex="' + a.iTabIndex + '" class="' + e.sPageButton + " " + e.sPageFirst + '">' + d.sFirst + "</a>" + '<a  tabindex="' + a.iTabIndex + '" class="' + e.sPageButton + " " + e.sPagePrevious + '">' + d.sPrevious + "</a>" + "<span></span>" + '<a tabindex="' + a.iTabIndex + '" class="' + e.sPageButton + " " + e.sPageNext + '">' + d.sNext + "</a>" + '<a tabindex="' + a.iTabIndex + '" class="' + e.sPageButton + " " + e.sPageLast + '">' + d.sLast + "</a>");
                    var g = $("a", b),
                    h = g[0],
                    i = g[1],
                    j = g[2],
                    k = g[3];
                    a.oApi._fnBindAction(h, {
                        action: "first"
                    },
                    f),
                    a.oApi._fnBindAction(i, {
                        action: "previous"
                    },
                    f),
                    a.oApi._fnBindAction(j, {
                        action: "next"
                    },
                    f),
                    a.oApi._fnBindAction(k, {
                        action: "last"
                    },
                    f),
                    a.aanFeatures.p || (b.id = a.sTableId + "_paginate", h.id = a.sTableId + "_first", i.id = a.sTableId + "_previous", j.id = a.sTableId + "_next", k.id = a.sTableId + "_last")
                },
                fnUpdate: function(a, b) {
                    if (a.aanFeatures.p) {
                        var c, d, e, f, g, h, i, j = DataTable.ext.oPagination.iFullNumbersShowPages,
                        k = Math.floor(j / 2),
                        l = Math.ceil(a.fnRecordsDisplay() / a._iDisplayLength),
                        m = Math.ceil(a._iDisplayStart / a._iDisplayLength) + 1,
                        n = "",
                        o = a.oClasses,
                        p = a.aanFeatures.p,
                        q = function(d) {
                            a.oApi._fnBindAction(this, {
                                page: d + c - 1
                            },
                            function(c) {
                                a.oApi._fnPageChange(a, c.data.page),
                                b(a),
                                c.preventDefault()
                            })
                        };
                        for ( - 1 === a._iDisplayLength ? (c = 1, d = 1, m = 1) : j > l ? (c = 1, d = l) : k >= m ? (c = 1, d = j) : m >= l - k ? (c = l - j + 1, d = l) : (c = m - Math.ceil(j / 2) + 1, d = c + j - 1), e = c; d >= e; e++) n += m !== e ? '<a tabindex="' + a.iTabIndex + '" class="' + o.sPageButton + '">' + a.fnFormatNumber(e) + "</a>": '<a tabindex="' + a.iTabIndex + '" class="' + o.sPageButtonActive + '">' + a.fnFormatNumber(e) + "</a>";
                        for (e = 0, f = p.length; f > e; e++) i = p[e],
                        i.hasChildNodes() && ($("span:eq(0)", i).html(n).children("a").each(q), g = i.getElementsByTagName("a"), h = [g[0], g[1], g[g.length - 2], g[g.length - 1]], $(h).removeClass(o.sPageButton + " " + o.sPageButtonActive + " " + o.sPageButtonStaticDisabled), $([h[0], h[1]]).addClass(1 == m ? o.sPageButtonStaticDisabled: o.sPageButton), $([h[2], h[3]]).addClass(0 === l || m === l || -1 === a._iDisplayLength ? o.sPageButtonStaticDisabled: o.sPageButton))
                    }
                }
            }
        }),
        $.extend(DataTable.ext.oSort, {
            "string-pre": function(a) {
                return "string" != typeof a && (a = null !== a && a.toString ? a.toString() : ""),
                a.toLowerCase()
            },
            "string-asc": function(a, b) {
                return b > a ? -1 : a > b ? 1 : 0
            },
            "string-desc": function(a, b) {
                return b > a ? 1 : a > b ? -1 : 0
            },
            "html-pre": function(a) {
                return a.replace(/<.*?>/g, "").toLowerCase()
            },
            "html-asc": function(a, b) {
                return b > a ? -1 : a > b ? 1 : 0
            },
            "html-desc": function(a, b) {
                return b > a ? 1 : a > b ? -1 : 0
            },
            "date-pre": function(a) {
                var b = Date.parse(a);
                return (isNaN(b) || "" === b) && (b = Date.parse("01/01/1970 00:00:00")),
                b
            },
            "date-asc": function(a, b) {
                return a - b
            },
            "date-desc": function(a, b) {
                return b - a
            },
            "numeric-pre": function(a) {
                return "-" == a || "" === a ? 0 : 1 * a
            },
            "numeric-asc": function(a, b) {
                return a - b
            },
            "numeric-desc": function(a, b) {
                return b - a
            }
        }),
        $.extend(DataTable.ext.aTypes, [function(a) {
            if ("number" == typeof a) return "numeric";
            if ("string" != typeof a) return null;
            var b, c = "0123456789-",
            d = "0123456789.",
            e = !1;
            if (b = a.charAt(0), -1 == c.indexOf(b)) return null;
            for (var f = 1; f < a.length; f++) {
                if (b = a.charAt(f), -1 == d.indexOf(b)) return null;
                if ("." == b) {
                    if (e) return null;
                    e = !0
                }
            }
            return "numeric"
        },
        function(a) {
            var b = Date.parse(a);
            return null !== b && !isNaN(b) || "string" == typeof a && 0 === a.length ? "date": null
        },
        function(a) {
            return "string" == typeof a && -1 != a.indexOf("<") && -1 != a.indexOf(">") ? "html": null
        }]),
        $.fn.DataTable = DataTable,
        $.fn.dataTable = DataTable,
        $.fn.dataTableSettings = DataTable.settings,
        $.fn.dataTableExt = DataTable.ext
    })
} (window, document),
function() {
    function a(b, c, d) {
        var e = a.resolve(b);
        if (null == e) {
            d = d || b,
            c = c || "root";
            var f = new Error('Failed to require "' + d + '" from "' + c + '"');
            throw f.path = d,
            f.parent = c,
            f.require = !0,
            f
        }
        var g = a.modules[e];
        return g.exports || (g.exports = {},
        g.client = g.component = !0, g.call(this, g.exports, a.relative(e), g)),
        g.exports
    }
    a.modules = {},
    a.aliases = {},
    a.resolve = function(b) {
        "/" === b.charAt(0) && (b = b.slice(1));
        for (var c = [b, b + ".js", b + ".json", b + "/index.js", b + "/index.json"], d = 0; d < c.length; d++) {
            var b = c[d];
            if (a.modules.hasOwnProperty(b)) return b;
            if (a.aliases.hasOwnProperty(b)) return a.aliases[b]
        }
    },
    a.normalize = function(a, b) {
        var c = [];
        if ("." != b.charAt(0)) return b;
        a = a.split("/"),
        b = b.split("/");
        for (var d = 0; d < b.length; ++d)".." == b[d] ? a.pop() : "." != b[d] && "" != b[d] && c.push(b[d]);
        return a.concat(c).join("/")
    },
    a.register = function(b, c) {
        a.modules[b] = c
    },
    a.alias = function(b, c) {
        if (!a.modules.hasOwnProperty(b)) throw new Error('Failed to alias "' + b + '", it does not exist');
        a.aliases[c] = b
    },
    a.relative = function(b) {
        function c(a, b) {
            for (var c = a.length; c--;) if (a[c] === b) return c;
            return - 1
        }
        function d(c) {
            var e = d.resolve(c);
            return a(e, b, c)
        }
        var e = a.normalize(b, "..");
        return d.resolve = function(d) {
            var f = d.charAt(0);
            if ("/" == f) return d.slice(1);
            if ("." == f) return a.normalize(e, d);
            var g = b.split("/"),
            h = c(g, "deps") + 1;
            return h || (h = 0),
            d = g.slice(0, h + 1).join("/") + "/deps/" + d
        },
        d.exists = function(b) {
            return a.modules.hasOwnProperty(d.resolve(b))
        },
        d
    },
    a.register("component-emitter/index.js",
    function(a, b, c) {
        function d(a) {
            return a ? e(a) : void 0
        }
        function e(a) {
            for (var b in d.prototype) a[b] = d.prototype[b];
            return a
        }
        c.exports = d,
        d.prototype.on = function(a, b) {
            return this._callbacks = this._callbacks || {},
            (this._callbacks[a] = this._callbacks[a] || []).push(b),
            this
        },
        d.prototype.once = function(a, b) {
            function c() {
                d.off(a, c),
                b.apply(this, arguments)
            }
            var d = this;
            return this._callbacks = this._callbacks || {},
            b._off = c,
            this.on(a, c),
            this
        },
        d.prototype.off = d.prototype.removeListener = d.prototype.removeAllListeners = function(a, b) {
            this._callbacks = this._callbacks || {};
            var c = this._callbacks[a];
            if (!c) return this;
            if (1 == arguments.length) return delete this._callbacks[a],
            this;
            var d = c.indexOf(b._off || b);
            return~d && c.splice(d, 1),
            this
        },
        d.prototype.emit = function(a) {
            this._callbacks = this._callbacks || {};
            var b = [].slice.call(arguments, 1),
            c = this._callbacks[a];
            if (c) {
                c = c.slice(0);
                for (var d = 0,
                e = c.length; e > d; ++d) c[d].apply(this, b)
            }
            return this
        },
        d.prototype.listeners = function(a) {
            return this._callbacks = this._callbacks || {},
            this._callbacks[a] || []
        },
        d.prototype.hasListeners = function(a) {
            return !! this.listeners(a).length
        }
    }),
    a.register("dropzone/index.js",
    function(a, b, c) {
        c.exports = b("./lib/dropzone.js")
    }),
    a.register("dropzone/lib/dropzone.js",
    function(a, b, c) { !
        function() {
            var a, d, e, f, g, h, i = {}.hasOwnProperty,
            j = function(a, b) {
                function c() {
                    this.constructor = a
                }
                for (var d in b) i.call(b, d) && (a[d] = b[d]);
                return c.prototype = b.prototype,
                a.prototype = new c,
                a.__super__ = b.prototype,
                a
            },
            k = [].slice;
            d = "undefined" != typeof Emitter && null !== Emitter ? Emitter: b("emitter"),
            g = function() {},
            a = function(a) {
                function b(a, d) {
                    var e, f, g;
                    if (this.element = a, this.version = b.version, this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, ""), this.clickableElements = [], this.listeners = [], this.files = [], "string" == typeof this.element && (this.element = document.querySelector(this.element)), !this.element || null == this.element.nodeType) throw new Error("Invalid dropzone element.");
                    if (this.element.dropzone) throw new Error("Dropzone already attached.");
                    if (b.instances.push(this), a.dropzone = this, e = null != (g = b.optionsForElement(this.element)) ? g: {},
                    this.options = c({},
                    this.defaultOptions, e, null != d ? d: {}), this.options.forceFallback || !b.isBrowserSupported()) return this.options.fallback.call(this);
                    if (null == this.options.url && (this.options.url = this.element.getAttribute("action")), !this.options.url) throw new Error("No URL provided.");
                    if (this.options.acceptedFiles && this.options.acceptedMimeTypes) throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
                    this.options.acceptedMimeTypes && (this.options.acceptedFiles = this.options.acceptedMimeTypes, delete this.options.acceptedMimeTypes),
                    this.options.method = this.options.method.toUpperCase(),
                    (f = this.getExistingFallback()) && f.parentNode && f.parentNode.removeChild(f),
                    this.previewsContainer = this.options.previewsContainer ? b.getElement(this.options.previewsContainer, "previewsContainer") : this.element,
                    this.options.clickable && (this.clickableElements = this.options.clickable === !0 ? [this.element] : b.getElements(this.options.clickable, "clickable")),
                    this.init()
                }
                var c;
                return j(b, a),
                b.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "selectedfiles", "addedfile", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded"],
                b.prototype.defaultOptions = {
                    url: null,
                    method: "post",
                    withCredentials: !1,
                    parallelUploads: 2,
                    uploadMultiple: !1,
                    maxFilesize: 256,
                    paramName: "file",
                    createImageThumbnails: !0,
                    maxThumbnailFilesize: 10,
                    thumbnailWidth: 100,
                    thumbnailHeight: 100,
                    maxFiles: null,
                    params: {},
                    clickable: !0,
                    ignoreHiddenFiles: !0,
                    acceptedFiles: null,
                    acceptedMimeTypes: null,
                    autoProcessQueue: !0,
                    addRemoveLinks: !1,
                    previewsContainer: null,
                    dictDefaultMessage: "Drop files here to upload",
                    dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                    dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
                    dictFileTooBig: "File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.",
                    dictInvalidFileType: "You can't upload files of this type.",
                    dictResponseError: "Server responded with {{statusCode}} code.",
                    dictCancelUpload: "Cancel upload",
                    dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
                    dictRemoveFile: "Remove file",
                    dictRemoveFileConfirmation: null,
                    dictMaxFilesExceeded: "You can only upload {{maxFiles}} files.",
                    accept: function(a, b) {
                        return b()
                    },
                    init: function() {
                        return g
                    },
                    forceFallback: !1,
                    fallback: function() {
                        var a, c, d, e, f, g;
                        for (this.element.className = "" + this.element.className + " dz-browser-not-supported", g = this.element.getElementsByTagName("div"), e = 0, f = g.length; f > e; e++) a = g[e],
                        /(^| )dz-message($| )/.test(a.className) && (c = a, a.className = "dz-message");
                        return c || (c = b.createElement('<div class="dz-message"><span></span></div>'), this.element.appendChild(c)),
                        d = c.getElementsByTagName("span")[0],
                        d && (d.textContent = this.options.dictFallbackMessage),
                        this.element.appendChild(this.getFallbackForm())
                    },
                    resize: function(a) {
                        var b, c, d;
                        return b = {
                            srcX: 0,
                            srcY: 0,
                            srcWidth: a.width,
                            srcHeight: a.height
                        },
                        c = a.width / a.height,
                        d = this.options.thumbnailWidth / this.options.thumbnailHeight,
                        a.height < this.options.thumbnailHeight || a.width < this.options.thumbnailWidth ? (b.trgHeight = b.srcHeight, b.trgWidth = b.srcWidth) : c > d ? (b.srcHeight = a.height, b.srcWidth = b.srcHeight * d) : (b.srcWidth = a.width, b.srcHeight = b.srcWidth / d),
                        b.srcX = (a.width - b.srcWidth) / 2,
                        b.srcY = (a.height - b.srcHeight) / 2,
                        b
                    },
                    drop: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    dragstart: g,
                    dragend: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    dragenter: function() {
                        return this.element.classList.add("dz-drag-hover")
                    },
                    dragover: function() {
                        return this.element.classList.add("dz-drag-hover")
                    },
                    dragleave: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    selectedfiles: function() {
                        return this.element === this.previewsContainer ? this.element.classList.add("dz-started") : void 0
                    },
                    reset: function() {
                        return this.element.classList.remove("dz-started")
                    },
                    addedfile: function(a) {
                        var c = this;
                        return a.previewElement = b.createElement(this.options.previewTemplate),
                        a.previewTemplate = a.previewElement,
                        this.previewsContainer.appendChild(a.previewElement),
                        a.previewElement.querySelector("[data-dz-name]").textContent = a.name,
                        a.previewElement.querySelector("[data-dz-size]").innerHTML = this.filesize(a.size),
                        this.options.addRemoveLinks && (a._removeLink = b.createElement('<a class="dz-remove" href="javascript:undefined;">' + this.options.dictRemoveFile + "</a>"), a._removeLink.addEventListener("click",
                        function(d) {
                            return d.preventDefault(),
                            d.stopPropagation(),
                            a.status === b.UPLOADING ? b.confirm(c.options.dictCancelUploadConfirmation,
                            function() {
                                return c.removeFile(a)
                            }) : c.options.dictRemoveFileConfirmation ? b.confirm(c.options.dictRemoveFileConfirmation,
                            function() {
                                return c.removeFile(a)
                            }) : c.removeFile(a)
                        }), a.previewElement.appendChild(a._removeLink)),
                        this._updateMaxFilesReachedClass()
                    },
                    removedfile: function(a) {
                        var b;
                        return null != (b = a.previewElement) && b.parentNode.removeChild(a.previewElement),
                        this._updateMaxFilesReachedClass()
                    },
                    thumbnail: function(a, b) {
                        var c;
                        return a.previewElement.classList.remove("dz-file-preview"),
                        a.previewElement.classList.add("dz-image-preview"),
                        c = a.previewElement.querySelector("[data-dz-thumbnail]"),
                        c.alt = a.name,
                        c.src = b
                    },
                    error: function(a, b) {
                        return a.previewElement.classList.add("dz-error"),
                        a.previewElement.querySelector("[data-dz-errormessage]").textContent = b
                    },
                    errormultiple: g,
                    processing: function(a) {
                        return a.previewElement.classList.add("dz-processing"),
                        a._removeLink ? a._removeLink.textContent = this.options.dictCancelUpload: void 0
                    },
                    processingmultiple: g,
                    uploadprogress: function(a, b) {
                        return a.previewElement.querySelector("[data-dz-uploadprogress]").style.width = "" + b + "%"
                    },
                    totaluploadprogress: g,
                    sending: g,
                    sendingmultiple: g,
                    success: function(a) {
                        return a.previewElement.classList.add("dz-success")
                    },
                    successmultiple: g,
                    canceled: function(a) {
                        return this.emit("error", a, "Upload canceled.")
                    },
                    canceledmultiple: g,
                    complete: function(a) {
                        return a._removeLink ? a._removeLink.textContent = this.options.dictRemoveFile: void 0
                    },
                    completemultiple: g,
                    maxfilesexceeded: g,
                    previewTemplate: '<div class="dz-preview dz-file-preview">\n  <div class="dz-details">\n    <div class="dz-filename"><span data-dz-name></span></div>\n    <div class="dz-size" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\n  <div class="dz-success-mark"><span>âœ”</span></div>\n  <div class="dz-error-mark"><span>âœ˜</span></div>\n  <div class="dz-error-message"><span data-dz-errormessage></span></div>\n</div>'
                },
                c = function() {
                    var a, b, c, d, e, f, g;
                    for (d = arguments[0], c = 2 <= arguments.length ? k.call(arguments, 1) : [], f = 0, g = c.length; g > f; f++) {
                        b = c[f];
                        for (a in b) e = b[a],
                        d[a] = e
                    }
                    return d
                },
                b.prototype.getAcceptedFiles = function() {
                    var a, b, c, d, e;
                    for (d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b],
                    a.accepted && e.push(a);
                    return e
                },
                b.prototype.getRejectedFiles = function() {
                    var a, b, c, d, e;
                    for (d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b],
                    a.accepted || e.push(a);
                    return e
                },
                b.prototype.getQueuedFiles = function() {
                    var a, c, d, e, f;
                    for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) a = e[c],
                    a.status === b.QUEUED && f.push(a);
                    return f
                },
                b.prototype.getUploadingFiles = function() {
                    var a, c, d, e, f;
                    for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) a = e[c],
                    a.status === b.UPLOADING && f.push(a);
                    return f
                },
                b.prototype.init = function() {
                    var a, c, d, e, f, g, h, i = this;
                    for ("form" === this.element.tagName && this.element.setAttribute("enctype", "multipart/form-data"), this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message") && this.element.appendChild(b.createElement('<div class="dz-default dz-message"><span>' + this.options.dictDefaultMessage + "</span></div>")), this.clickableElements.length && (d = function() {
                        return i.hiddenFileInput && document.body.removeChild(i.hiddenFileInput),
                        i.hiddenFileInput = document.createElement("input"),
                        i.hiddenFileInput.setAttribute("type", "file"),
                        i.hiddenFileInput.setAttribute("multiple", "multiple"),
                        null != i.options.acceptedFiles && i.hiddenFileInput.setAttribute("accept", i.options.acceptedFiles),
                        i.hiddenFileInput.style.visibility = "hidden",
                        i.hiddenFileInput.style.position = "absolute",
                        i.hiddenFileInput.style.top = "0",
                        i.hiddenFileInput.style.left = "0",
                        i.hiddenFileInput.style.height = "0",
                        i.hiddenFileInput.style.width = "0",
                        document.body.appendChild(i.hiddenFileInput),
                        i.hiddenFileInput.addEventListener("change",
                        function() {
                            var a;
                            return a = i.hiddenFileInput.files,
                            a.length && (i.emit("selectedfiles", a), i.handleFiles(a)),
                            d()
                        })
                    },
                    d()), this.URL = null != (g = window.URL) ? g: window.webkitURL, h = this.events, e = 0, f = h.length; f > e; e++) a = h[e],
                    this.on(a, this.options[a]);
                    return this.on("uploadprogress",
                    function() {
                        return i.updateTotalUploadProgress()
                    }),
                    this.on("removedfile",
                    function() {
                        return i.updateTotalUploadProgress()
                    }),
                    this.on("canceled",
                    function(a) {
                        return i.emit("complete", a)
                    }),
                    c = function(a) {
                        return a.stopPropagation(),
                        a.preventDefault ? a.preventDefault() : a.returnValue = !1
                    },
                    this.listeners = [{
                        element: this.element,
                        events: {
                            dragstart: function(a) {
                                return i.emit("dragstart", a)
                            },
                            dragenter: function(a) {
                                return c(a),
                                i.emit("dragenter", a)
                            },
                            dragover: function(a) {
                                return c(a),
                                i.emit("dragover", a)
                            },
                            dragleave: function(a) {
                                return i.emit("dragleave", a)
                            },
                            drop: function(a) {
                                return c(a),
                                i.drop(a)
                            },
                            dragend: function(a) {
                                return i.emit("dragend", a)
                            }
                        }
                    }],
                    this.clickableElements.forEach(function(a) {
                        return i.listeners.push({
                            element: a,
                            events: {
                                click: function(c) {
                                    return a !== i.element || c.target === i.element || b.elementInside(c.target, i.element.querySelector(".dz-message")) ? i.hiddenFileInput.click() : void 0
                                }
                            }
                        })
                    }),
                    this.enable(),
                    this.options.init.call(this)
                },
                b.prototype.destroy = function() {
                    var a;
                    return this.disable(),
                    this.removeAllFiles(!0),
                    (null != (a = this.hiddenFileInput) ? a.parentNode: void 0) && (this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput), this.hiddenFileInput = null),
                    delete this.element.dropzone
                },
                b.prototype.updateTotalUploadProgress = function() {
                    var a, b, c, d, e, f, g, h;
                    if (d = 0, c = 0, a = this.getAcceptedFiles(), a.length) {
                        for (h = this.getAcceptedFiles(), f = 0, g = h.length; g > f; f++) b = h[f],
                        d += b.upload.bytesSent,
                        c += b.upload.total;
                        e = 100 * d / c
                    } else e = 100;
                    return this.emit("totaluploadprogress", e, c, d)
                },
                b.prototype.getFallbackForm = function() {
                    var a, c, d, e;
                    return (a = this.getExistingFallback()) ? a: (d = '<div class="dz-fallback">', this.options.dictFallbackText && (d += "<p>" + this.options.dictFallbackText + "</p>"), d += '<input type="file" name="' + this.options.paramName + (this.options.uploadMultiple ? "[]": "") + '" ' + (this.options.uploadMultiple ? 'multiple="multiple"': void 0) + ' /><button type="submit">Upload!</button></div>', c = b.createElement(d), "FORM" !== this.element.tagName ? (e = b.createElement('<form action="' + this.options.url + '" enctype="multipart/form-data" method="' + this.options.method + '"></form>'), e.appendChild(c)) : (this.element.setAttribute("enctype", "multipart/form-data"), this.element.setAttribute("method", this.options.method)), null != e ? e: c)
                },
                b.prototype.getExistingFallback = function() {
                    var a, b, c, d, e, f;
                    for (b = function(a) {
                        var b, c, d;
                        for (c = 0, d = a.length; d > c; c++) if (b = a[c], /(^| )fallback($| )/.test(b.className)) return b
                    },
                    f = ["div", "form"], d = 0, e = f.length; e > d; d++) if (c = f[d], a = b(this.element.getElementsByTagName(c))) return a
                },
                b.prototype.setupEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d],
                    g.push(function() {
                        var d, e;
                        d = a.events,
                        e = [];
                        for (b in d) c = d[b],
                        e.push(a.element.addEventListener(b, c, !1));
                        return e
                    } ());
                    return g
                },
                b.prototype.removeEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d],
                    g.push(function() {
                        var d, e;
                        d = a.events,
                        e = [];
                        for (b in d) c = d[b],
                        e.push(a.element.removeEventListener(b, c, !1));
                        return e
                    } ());
                    return g
                },
                b.prototype.disable = function() {
                    var a, b, c, d, e;
                    for (this.clickableElements.forEach(function(a) {
                        return a.classList.remove("dz-clickable")
                    }), this.removeEventListeners(), d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b],
                    e.push(this.cancelUpload(a));
                    return e
                },
                b.prototype.enable = function() {
                    return this.clickableElements.forEach(function(a) {
                        return a.classList.add("dz-clickable")
                    }),
                    this.setupEventListeners()
                },
                b.prototype.filesize = function(a) {
                    var b;
                    return a >= 1e11 ? (a /= 1e11, b = "TB") : a >= 1e8 ? (a /= 1e8, b = "GB") : a >= 1e5 ? (a /= 1e5, b = "MB") : a >= 100 ? (a /= 100, b = "KB") : (a = 10 * a, b = "b"),
                    "<strong>" + Math.round(a) / 10 + "</strong> " + b
                },
                b.prototype._updateMaxFilesReachedClass = function() {
                    return this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? this.element.classList.add("dz-max-files-reached") : this.element.classList.remove("dz-max-files-reached")
                },
                b.prototype.drop = function(a) {
                    var b, c;
                    a.dataTransfer && (this.emit("drop", a), b = a.dataTransfer.files, this.emit("selectedfiles", b), b.length && (c = a.dataTransfer.items, c && c.length && (null != c[0].webkitGetAsEntry || null != c[0].getAsEntry) ? this.handleItems(c) : this.handleFiles(b)))
                },
                b.prototype.handleFiles = function(a) {
                    var b, c, d, e;
                    for (e = [], c = 0, d = a.length; d > c; c++) b = a[c],
                    e.push(this.addFile(b));
                    return e
                },
                b.prototype.handleItems = function(a) {
                    var b, c, d, e;
                    for (d = 0, e = a.length; e > d; d++) c = a[d],
                    null != c.webkitGetAsEntry ? (b = c.webkitGetAsEntry(), b.isFile ? this.addFile(c.getAsFile()) : b.isDirectory && this.addDirectory(b, b.name)) : this.addFile(c.getAsFile())
                },
                b.prototype.accept = function(a, c) {
                    return a.size > 1024 * 1024 * this.options.maxFilesize ? c(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(a.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize)) : b.isValidFile(a, this.options.acceptedFiles) ? this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (c(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles)), this.emit("maxfilesexceeded", a)) : this.options.accept.call(this, a, c) : c(this.options.dictInvalidFileType)
                },
                b.prototype.addFile = function(a) {
                    var c = this;
                    return a.upload = {
                        progress: 0,
                        total: a.size,
                        bytesSent: 0
                    },
                    this.files.push(a),
                    a.status = b.ADDED,
                    this.emit("addedfile", a),
                    this.options.createImageThumbnails && a.type.match(/image.*/) && a.size <= 1024 * 1024 * this.options.maxThumbnailFilesize && this.createThumbnail(a),
                    this.accept(a,
                    function(b) {
                        return b ? (a.accepted = !1, c._errorProcessing([a], b)) : c.enqueueFile(a)
                    })
                },
                b.prototype.enqueueFiles = function(a) {
                    var b, c, d;
                    for (c = 0, d = a.length; d > c; c++) b = a[c],
                    this.enqueueFile(b);
                    return null
                },
                b.prototype.enqueueFile = function(a) {
                    var c = this;
                    if (a.accepted = !0, a.status !== b.ADDED) throw new Error("This file can't be queued because it has already been processed or was rejected.");
                    return a.status = b.QUEUED,
                    this.options.autoProcessQueue ? setTimeout(function() {
                        return c.processQueue()
                    },
                    1) : void 0
                },
                b.prototype.addDirectory = function(a, b) {
                    var c, d, e = this;
                    return c = a.createReader(),
                    d = function(c) {
                        var d, f;
                        for (d = 0, f = c.length; f > d; d++) a = c[d],
                        a.isFile ? a.file(function(a) {
                            return e.options.ignoreHiddenFiles && "." === a.name.substring(0, 1) ? void 0 : (a.fullPath = "" + b + "/" + a.name, e.addFile(a))
                        }) : a.isDirectory && e.addDirectory(a, "" + b + "/" + a.name)
                    },
                    c.readEntries(d,
                    function(a) {
                        return "undefined" != typeof console && null !== console ? "function" == typeof console.log ? console.log(a) : void 0 : void 0
                    })
                },
                b.prototype.removeFile = function(a) {
                    return a.status === b.UPLOADING && this.cancelUpload(a),
                    this.files = h(this.files, a),
                    this.emit("removedfile", a),
                    0 === this.files.length ? this.emit("reset") : void 0
                },
                b.prototype.removeAllFiles = function(a) {
                    var c, d, e, f;
                    for (null == a && (a = !1), f = this.files.slice(), d = 0, e = f.length; e > d; d++) c = f[d],
                    (c.status !== b.UPLOADING || a) && this.removeFile(c);
                    return null
                },
                b.prototype.createThumbnail = function(a) {
                    var b, c = this;
                    return b = new FileReader,
                    b.onload = function() {
                        var d;
                        return d = new Image,
                        d.onload = function() {
                            var b, e, f, g, h, i, j, k;
                            return a.width = d.width,
                            a.height = d.height,
                            f = c.options.resize.call(c, a),
                            null == f.trgWidth && (f.trgWidth = c.options.thumbnailWidth),
                            null == f.trgHeight && (f.trgHeight = c.options.thumbnailHeight),
                            b = document.createElement("canvas"),
                            e = b.getContext("2d"),
                            b.width = f.trgWidth,
                            b.height = f.trgHeight,
                            e.drawImage(d, null != (h = f.srcX) ? h: 0, null != (i = f.srcY) ? i: 0, f.srcWidth, f.srcHeight, null != (j = f.trgX) ? j: 0, null != (k = f.trgY) ? k: 0, f.trgWidth, f.trgHeight),
                            g = b.toDataURL("image/png"),
                            c.emit("thumbnail", a, g)
                        },
                        d.src = b.result
                    },
                    b.readAsDataURL(a)
                },
                b.prototype.processQueue = function() {
                    var a, b, c, d;
                    if (b = this.options.parallelUploads, c = this.getUploadingFiles().length, a = c, !(c >= b) && (d = this.getQueuedFiles(), d.length > 0)) {
                        if (this.options.uploadMultiple) return this.processFiles(d.slice(0, b - c));
                        for (; b > a;) {
                            if (!d.length) return;
                            this.processFile(d.shift()),
                            a++
                        }
                    }
                },
                b.prototype.processFile = function(a) {
                    return this.processFiles([a])
                },
                b.prototype.processFiles = function(a) {
                    var c, d, e;
                    for (d = 0, e = a.length; e > d; d++) c = a[d],
                    c.processing = !0,
                    c.status = b.UPLOADING,
                    this.emit("processing", c);
                    return this.options.uploadMultiple && this.emit("processingmultiple", a),
                    this.uploadFiles(a)
                },
                b.prototype._getFilesWithXhr = function(a) {
                    var b, c;
                    return c = function() {
                        var c, d, e, f;
                        for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) b = e[c],
                        b.xhr === a && f.push(b);
                        return f
                    }.call(this)
                },
                b.prototype.cancelUpload = function(a) {
                    var c, d, e, f, g, h, i;
                    if (a.status === b.UPLOADING) {
                        for (d = this._getFilesWithXhr(a.xhr), e = 0, g = d.length; g > e; e++) c = d[e],
                        c.status = b.CANCELED;
                        for (a.xhr.abort(), f = 0, h = d.length; h > f; f++) c = d[f],
                        this.emit("canceled", c);
                        this.options.uploadMultiple && this.emit("canceledmultiple", d)
                    } else((i = a.status) === b.ADDED || i === b.QUEUED) && (a.status = b.CANCELED, this.emit("canceled", a), this.options.uploadMultiple && this.emit("canceledmultiple", [a]));
                    return this.options.autoProcessQueue ? this.processQueue() : void 0
                },
                b.prototype.uploadFile = function(a) {
                    return this.uploadFiles([a])
                },
                b.prototype.uploadFiles = function(a) {
                    var d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E = this;
                    for (r = new XMLHttpRequest, s = 0, w = a.length; w > s; s++) d = a[s],
                    d.xhr = r;
                    r.open(this.options.method, this.options.url, !0),
                    r.withCredentials = !!this.options.withCredentials,
                    o = null,
                    f = function() {
                        var b, c, e;
                        for (e = [], b = 0, c = a.length; c > b; b++) d = a[b],
                        e.push(E._errorProcessing(a, o || E.options.dictResponseError.replace("{{statusCode}}", r.status), r));
                        return e
                    },
                    p = function(b) {
                        var c, e, f, g, h, i, j, k, l;
                        if (null != b) for (e = 100 * b.loaded / b.total, f = 0, i = a.length; i > f; f++) d = a[f],
                        d.upload = {
                            progress: e,
                            total: b.total,
                            bytesSent: b.loaded
                        };
                        else {
                            for (c = !0, e = 100, g = 0, j = a.length; j > g; g++) d = a[g],
                            (100 !== d.upload.progress || d.upload.bytesSent !== d.upload.total) && (c = !1),
                            d.upload.progress = e,
                            d.upload.bytesSent = d.upload.total;
                            if (c) return
                        }
                        for (l = [], h = 0, k = a.length; k > h; h++) d = a[h],
                        l.push(E.emit("uploadprogress", d, e, d.upload.bytesSent));
                        return l
                    },
                    r.onload = function(c) {
                        var d;
                        if (a[0].status !== b.CANCELED && 4 === r.readyState) {
                            if (o = r.responseText, r.getResponseHeader("content-type") && ~r.getResponseHeader("content-type").indexOf("application/json")) try {
                                o = JSON.parse(o)
                            } catch(e) {
                                c = e,
                                o = "Invalid JSON response from server."
                            }
                            return p(),
                            200 <= (d = r.status) && 300 > d ? E._finished(a, o, c) : f()
                        }
                    },
                    r.onerror = function() {
                        return a[0].status !== b.CANCELED ? f() : void 0
                    },
                    n = null != (A = r.upload) ? A: r,
                    n.onprogress = p,
                    i = {
                        Accept: "application/json",
                        "Cache-Control": "no-cache",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    this.options.headers && c(i, this.options.headers);
                    for (g in i) h = i[g],
                    r.setRequestHeader(g, h);
                    if (e = new FormData, this.options.params) {
                        B = this.options.params;
                        for (m in B) q = B[m],
                        e.append(m, q)
                    }
                    for (t = 0, x = a.length; x > t; t++) d = a[t],
                    this.emit("sending", d, r, e);
                    if (this.options.uploadMultiple && this.emit("sendingmultiple", a, r, e), "FORM" === this.element.tagName) for (C = this.element.querySelectorAll("input, textarea, select, button"), u = 0, y = C.length; y > u; u++) j = C[u],
                    k = j.getAttribute("name"),
                    l = j.getAttribute("type"),
                    (!l || "checkbox" !== (D = l.toLowerCase()) && "radio" !== D || j.checked) && e.append(k, j.value);
                    for (v = 0, z = a.length; z > v; v++) d = a[v],
                    e.append("" + this.options.paramName + (this.options.uploadMultiple ? "[]": ""), d, d.name);
                    return r.send(e)
                },
                b.prototype._finished = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f],
                    e.status = b.SUCCESS,
                    this.emit("success", e, c, d),
                    this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("successmultiple", a, c, d), this.emit("completemultiple", a)),
                    this.options.autoProcessQueue ? this.processQueue() : void 0
                },
                b.prototype._errorProcessing = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f],
                    e.status = b.ERROR,
                    this.emit("error", e, c, d),
                    this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("errormultiple", a, c, d), this.emit("completemultiple", a)),
                    this.options.autoProcessQueue ? this.processQueue() : void 0
                },
                b
            } (d),
            a.version = "3.7.1",
            a.options = {},
            a.optionsForElement = function(b) {
                return b.id ? a.options[e(b.id)] : void 0
            },
            a.instances = [],
            a.forElement = function(a) {
                if ("string" == typeof a && (a = document.querySelector(a)), null == (null != a ? a.dropzone: void 0)) throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
                return a.dropzone
            },
            a.autoDiscover = !0,
            a.discover = function() {
                var b, c, d, e, f, g;
                for (document.querySelectorAll ? d = document.querySelectorAll(".dropzone") : (d = [], b = function(a) {
                    var b, c, e, f;
                    for (f = [], c = 0, e = a.length; e > c; c++) b = a[c],
                    /(^| )dropzone($| )/.test(b.className) ? f.push(d.push(b)) : f.push(void 0);
                    return f
                },
                b(document.getElementsByTagName("div")), b(document.getElementsByTagName("form"))), g = [], e = 0, f = d.length; f > e; e++) c = d[e],
                a.optionsForElement(c) !== !1 ? g.push(new a(c)) : g.push(void 0);
                return g
            },
            a.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i],
            a.isBrowserSupported = function() {
                var b, c, d, e, f;
                if (b = !0, window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector) if ("classList" in document.createElement("a")) for (f = a.blacklistedBrowsers, d = 0, e = f.length; e > d; d++) c = f[d],
                c.test(navigator.userAgent) && (b = !1);
                else b = !1;
                else b = !1;
                return b
            },
            h = function(a, b) {
                var c, d, e, f;
                for (f = [], d = 0, e = a.length; e > d; d++) c = a[d],
                c !== b && f.push(c);
                return f
            },
            e = function(a) {
                return a.replace(/[\-_](\w)/g,
                function(a) {
                    return a[1].toUpperCase()
                })
            },
            a.createElement = function(a) {
                var b;
                return b = document.createElement("div"),
                b.innerHTML = a,
                b.childNodes[0]
            },
            a.elementInside = function(a, b) {
                if (a === b) return ! 0;
                for (; a = a.parentNode;) if (a === b) return ! 0;
                return ! 1
            },
            a.getElement = function(a, b) {
                var c;
                if ("string" == typeof a ? c = document.querySelector(a) : null != a.nodeType && (c = a), null == c) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector or a plain HTML element.");
                return c
            },
            a.getElements = function(a, b) {
                var c, d, e, f, g, h, i, j;
                if (a instanceof Array) {
                    e = [];
                    try {
                        for (f = 0, h = a.length; h > f; f++) d = a[f],
                        e.push(this.getElement(d, b))
                    } catch(k) {
                        c = k,
                        e = null
                    }
                } else if ("string" == typeof a) for (e = [], j = document.querySelectorAll(a), g = 0, i = j.length; i > g; g++) d = j[g],
                e.push(d);
                else null != a.nodeType && (e = [a]);
                if (null == e || !e.length) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
                return e
            },
            a.confirm = function(a, b, c) {
                return window.confirm(a) ? b() : null != c ? c() : void 0
            },
            a.isValidFile = function(a, b) {
                var c, d, e, f, g;
                if (!b) return ! 0;
                for (b = b.split(","), d = a.type, c = d.replace(/\/.*$/, ""), f = 0, g = b.length; g > f; f++) if (e = b[f], e = e.trim(), "." === e.charAt(0)) {
                    if ( - 1 !== a.name.indexOf(e, a.name.length - e.length)) return ! 0
                } else if (/\/\*$/.test(e)) {
                    if (c === e.replace(/\/.*$/, "")) return ! 0
                } else if (d === e) return ! 0;
                return ! 1
            },
            "undefined" != typeof jQuery && null !== jQuery && (jQuery.fn.dropzone = function(b) {
                return this.each(function() {
                    return new a(this, b)
                })
            }),
            "undefined" != typeof c && null !== c ? c.exports = a: window.Dropzone = a,
            a.ADDED = "added",
            a.QUEUED = "queued",
            a.ACCEPTED = a.QUEUED,
            a.UPLOADING = "uploading",
            a.PROCESSING = a.UPLOADING,
            a.CANCELED = "canceled",
            a.ERROR = "error",
            a.SUCCESS = "success",
            f = function(a, b) {
                var c, d, e, f, g, h, i, j, k;
                if (e = !1, k = !0, d = a.document, j = d.documentElement, c = d.addEventListener ? "addEventListener": "attachEvent", i = d.addEventListener ? "removeEventListener": "detachEvent", h = d.addEventListener ? "": "on", f = function(c) {
                    return "readystatechange" !== c.type || "complete" === d.readyState ? (("load" === c.type ? a: d)[i](h + c.type, f, !1), !e && (e = !0) ? b.call(a, c.type || c) : void 0) : void 0
                },
                g = function() {
                    var a;
                    try {
                        j.doScroll("left")
                    } catch(b) {
                        return a = b,
                        setTimeout(g, 50),
                        void 0
                    }
                    return f("poll")
                },
                "complete" !== d.readyState) {
                    if (d.createEventObject && j.doScroll) {
                        try {
                            k = !a.frameElement
                        } catch(l) {}
                        k && g()
                    }
                    return d[c](h + "DOMContentLoaded", f, !1),
                    d[c](h + "readystatechange", f, !1),
                    a[c](h + "load", f, !1)
                }
            },
            a._autoDiscoverFunction = function() {
                return a.autoDiscover ? a.discover() : void 0
            },
            f(window, a._autoDiscoverFunction)
        }.call(this)
    }),
    a.alias("component-emitter/index.js", "dropzone/deps/emitter/index.js"),
    a.alias("component-emitter/index.js", "emitter/index.js"),
    "object" == typeof exports ? module.exports = a("dropzone") : "function" == typeof define && define.amd ? define(function() {
        return a("dropzone")
    }) : this.Dropzone = a("dropzone")
} (),
function(a) {
    a.Jcrop = function(b, c) {
        function d(a) {
            return Math.round(a) + "px"
        }
        function e(a) {
            return J.baseClass + "-" + a
        }
        function f() {
            return a.fx.step.hasOwnProperty("backgroundColor")
        }
        function g(b) {
            var c = a(b).offset();
            return [c.left, c.top]
        }
        function h(a) {
            return [a.pageX - I[0], a.pageY - I[1]]
        }
        function i(b) {
            "object" != typeof b && (b = {}),
            J = a.extend(J, b),
            a.each(["onChange", "onSelect", "onRelease", "onDblClick"],
            function(a, b) {
                "function" != typeof J[b] && (J[b] = function() {})
            })
        }
        function j(a, b, c) {
            if (I = g(R), ob.setCursor("move" === a ? a: a + "-resize"), "move" === a) return ob.activateHandlers(l(b), q, c);
            var d = lb.getFixed(),
            e = m(a),
            f = lb.getCorner(m(e));
            lb.setPressed(lb.getCorner(e)),
            lb.setCurrent(f),
            ob.activateHandlers(k(a, d), q, c)
        }
        function k(a, b) {
            return function(c) {
                if (J.aspectRatio) switch (a) {
                case "e":
                    c[1] = b.y + 1;
                    break;
                case "w":
                    c[1] = b.y + 1;
                    break;
                case "n":
                    c[0] = b.x + 1;
                    break;
                case "s":
                    c[0] = b.x + 1
                } else switch (a) {
                case "e":
                    c[1] = b.y2;
                    break;
                case "w":
                    c[1] = b.y2;
                    break;
                case "n":
                    c[0] = b.x2;
                    break;
                case "s":
                    c[0] = b.x2
                }
                lb.setCurrent(c),
                nb.update()
            }
        }
        function l(a) {
            var b = a;
            return pb.watchKeys(),
            function(a) {
                lb.moveOffset([a[0] - b[0], a[1] - b[1]]),
                b = a,
                nb.update()
            }
        }
        function m(a) {
            switch (a) {
            case "n":
                return "sw";
            case "s":
                return "nw";
            case "e":
                return "nw";
            case "w":
                return "ne";
            case "ne":
                return "sw";
            case "nw":
                return "se";
            case "se":
                return "nw";
            case "sw":
                return "ne"
            }
        }
        function n(a) {
            return function(b) {
                return J.disabled ? !1 : "move" !== a || J.allowMove ? (I = g(R), db = !0, j(a, h(b)), b.stopPropagation(), b.preventDefault(), !1) : !1
            }
        }
        function o(a, b, c) {
            var d = a.width(),
            e = a.height();
            d > b && b > 0 && (d = b, e = b / a.width() * a.height()),
            e > c && c > 0 && (e = c, d = c / a.height() * a.width()),
            bb = a.width() / d,
            cb = a.height() / e,
            a.width(d).height(e)
        }
        function p(a) {
            return {
                x: a.x * bb,
                y: a.y * cb,
                x2: a.x2 * bb,
                y2: a.y2 * cb,
                w: a.w * bb,
                h: a.h * cb
            }
        }
        function q() {
            var a = lb.getFixed();
            a.w > J.minSelect[0] && a.h > J.minSelect[1] ? (nb.enableHandles(), nb.done()) : nb.release(),
            ob.setCursor(J.allowSelect ? "crosshair": "default")
        }
        function r(a) {
            if (J.disabled) return ! 1;
            if (!J.allowSelect) return ! 1;
            db = !0,
            I = g(R),
            nb.disableHandles(),
            ob.setCursor("crosshair");
            var b = h(a);
            return lb.setPressed(b),
            nb.update(),
            ob.activateHandlers(s, q, "touch" === a.type.substring(0, 5)),
            pb.watchKeys(),
            a.stopPropagation(),
            a.preventDefault(),
            !1
        }
        function s(a) {
            lb.setCurrent(a),
            nb.update()
        }
        function t() {
            var b = a("<div></div>").addClass(e("tracker"));
            return L && b.css({
                opacity: 0,
                backgroundColor: "white"
            }),
            b
        }
        function u(a) {
            U.removeClass().addClass(e("holder")).addClass(a)
        }
        function v(a, b) {
            function c() {
                window.setTimeout(s, l)
            }
            var d = a[0] / bb,
            e = a[1] / cb,
            f = a[2] / bb,
            g = a[3] / cb;
            if (!eb) {
                var h = lb.flipCoords(d, e, f, g),
                i = lb.getFixed(),
                j = [i.x, i.y, i.x2, i.y2],
                k = j,
                l = J.animationDelay,
                m = h[0] - j[0],
                n = h[1] - j[1],
                o = h[2] - j[2],
                p = h[3] - j[3],
                q = 0,
                r = J.swingSpeed;
                d = k[0],
                e = k[1],
                f = k[2],
                g = k[3],
                nb.animMode(!0);
                var s = function() {
                    return function() {
                        q += (100 - q) / r,
                        k[0] = Math.round(d + q / 100 * m),
                        k[1] = Math.round(e + q / 100 * n),
                        k[2] = Math.round(f + q / 100 * o),
                        k[3] = Math.round(g + q / 100 * p),
                        q >= 99.8 && (q = 100),
                        100 > q ? (x(k), c()) : (nb.done(), nb.animMode(!1), "function" == typeof b && b.call(qb))
                    }
                } ();
                c()
            }
        }
        function w(a) {
            x([a[0] / bb, a[1] / cb, a[2] / bb, a[3] / cb]),
            J.onSelect.call(qb, p(lb.getFixed())),
            nb.enableHandles()
        }
        function x(a) {
            lb.setPressed([a[0], a[1]]),
            lb.setCurrent([a[2], a[3]]),
            nb.update()
        }
        function y() {
            return p(lb.getFixed())
        }
        function z() {
            return lb.getFixed()
        }
        function A(a) {
            i(a),
            H()
        }
        function B() {
            J.disabled = !0,
            nb.disableHandles(),
            nb.setCursor("default"),
            ob.setCursor("default")
        }
        function C() {
            J.disabled = !1,
            H()
        }
        function D() {
            nb.done(),
            ob.activateHandlers(null, null)
        }
        function E() {
            U.remove(),
            O.show(),
            O.css("visibility", "visible"),
            a(b).removeData("Jcrop")
        }
        function F(a, b) {
            nb.release(),
            B();
            var c = new Image;
            c.onload = function() {
                var d = c.width,
                e = c.height,
                f = J.boxWidth,
                g = J.boxHeight;
                R.width(d).height(e),
                R.attr("src", a),
                V.attr("src", a),
                o(R, f, g),
                S = R.width(),
                T = R.height(),
                V.width(S).height(T),
                hb.width(S + 2 * gb).height(T + 2 * gb),
                U.width(S).height(T),
                mb.resize(S, T),
                C(),
                "function" == typeof b && b.call(qb)
            },
            c.src = a
        }
        function G(a, b, c) {
            var d = b || J.bgColor;
            J.bgFade && f() && J.fadeTime && !c ? a.animate({
                backgroundColor: d
            },
            {
                queue: !1,
                duration: J.fadeTime
            }) : a.css("backgroundColor", d)
        }
        function H(a) {
            J.allowResize ? a ? nb.enableOnly() : nb.enableHandles() : nb.disableHandles(),
            ob.setCursor(J.allowSelect ? "crosshair": "default"),
            nb.setCursor(J.allowMove ? "move": "default"),
            J.hasOwnProperty("trueSize") && (bb = J.trueSize[0] / S, cb = J.trueSize[1] / T),
            J.hasOwnProperty("setSelect") && (w(J.setSelect), nb.done(), delete J.setSelect),
            mb.refresh(),
            J.bgColor != ib && (G(J.shade ? mb.getShades() : U, J.shade ? J.shadeColor || J.bgColor: J.bgColor), ib = J.bgColor),
            jb != J.bgOpacity && (jb = J.bgOpacity, J.shade ? mb.refresh() : nb.setBgOpacity(jb)),
            Z = J.maxSize[0] || 0,
            $ = J.maxSize[1] || 0,
            _ = J.minSize[0] || 0,
            ab = J.minSize[1] || 0,
            J.hasOwnProperty("outerImage") && (R.attr("src", J.outerImage), delete J.outerImage),
            nb.refresh()
        }
        var I, J = a.extend({},
        a.Jcrop.defaults),
        K = navigator.userAgent.toLowerCase(),
        L = /msie/.test(K),
        M = /msie [1-6]\./.test(K);
        "object" != typeof b && (b = a(b)[0]),
        "object" != typeof c && (c = {}),
        i(c);
        var N = {
            border: "none",
            visibility: "visible",
            margin: 0,
            padding: 0,
            position: "absolute",
            top: 0,
            left: 0
        },
        O = a(b),
        P = !0;
        if ("IMG" == b.tagName) {
            if (0 != O[0].width && 0 != O[0].height) O.width(O[0].width),
            O.height(O[0].height);
            else {
                var Q = new Image;
                Q.src = O[0].src,
                O.width(Q.width),
                O.height(Q.height)
            }
            var R = O.clone().removeAttr("id").css(N).show();
            R.width(O.width()),
            R.height(O.height()),
            O.after(R).hide()
        } else R = O.css(N).show(),
        P = !1,
        null === J.shade && (J.shade = !0);
        o(R, J.boxWidth, J.boxHeight);
        var S = R.width(),
        T = R.height(),
        U = a("<div />").width(S).height(T).addClass(e("holder")).css({
            position: "relative",
            backgroundColor: J.bgColor
        }).insertAfter(O).append(R);
        J.addClass && U.addClass(J.addClass);
        var V = a("<div />"),
        W = a("<div />").width("100%").height("100%").css({
            zIndex: 310,
            position: "absolute",
            overflow: "hidden"
        }),
        X = a("<div />").width("100%").height("100%").css("zIndex", 320),
        Y = a("<div />").css({
            position: "absolute",
            zIndex: 600
        }).dblclick(function() {
            var a = lb.getFixed();
            J.onDblClick.call(qb, a)
        }).insertBefore(R).append(W, X);
        P && (V = a("<img />").attr("src", R.attr("src")).css(N).width(S).height(T), W.append(V)),
        M && Y.css({
            overflowY: "hidden"
        });
        var Z, $, _, ab, bb, cb, db, eb, fb, gb = J.boundary,
        hb = t().width(S + 2 * gb).height(T + 2 * gb).css({
            position: "absolute",
            top: d( - gb),
            left: d( - gb),
            zIndex: 290
        }).mousedown(r),
        ib = J.bgColor,
        jb = J.bgOpacity;
        I = g(R);
        var kb = function() {
            function a() {
                var a, b = {},
                c = ["touchstart", "touchmove", "touchend"],
                d = document.createElement("div");
                try {
                    for (a = 0; a < c.length; a++) {
                        var e = c[a];
                        e = "on" + e;
                        var f = e in d;
                        f || (d.setAttribute(e, "return;"), f = "function" == typeof d[e]),
                        b[c[a]] = f
                    }
                    return b.touchstart && b.touchend && b.touchmove
                } catch(g) {
                    return ! 1
                }
            }
            function b() {
                return J.touchSupport === !0 || J.touchSupport === !1 ? J.touchSupport: a()
            }
            return {
                createDragger: function(a) {
                    return function(b) {
                        return J.disabled ? !1 : "move" !== a || J.allowMove ? (I = g(R), db = !0, j(a, h(kb.cfilter(b)), !0), b.stopPropagation(), b.preventDefault(), !1) : !1
                    }
                },
                newSelection: function(a) {
                    return r(kb.cfilter(a))
                },
                cfilter: function(a) {
                    return a.pageX = a.originalEvent.changedTouches[0].pageX,
                    a.pageY = a.originalEvent.changedTouches[0].pageY,
                    a
                },
                isSupported: a,
                support: b()
            }
        } (),
        lb = function() {
            function a(a) {
                a = g(a),
                o = m = a[0],
                p = n = a[1]
            }
            function b(a) {
                a = g(a),
                k = a[0] - o,
                l = a[1] - p,
                o = a[0],
                p = a[1]
            }
            function c() {
                return [k, l]
            }
            function d(a) {
                var b = a[0],
                c = a[1];
                0 > m + b && (b -= b + m),
                0 > n + c && (c -= c + n),
                p + c > T && (c += T - (p + c)),
                o + b > S && (b += S - (o + b)),
                m += b,
                o += b,
                n += c,
                p += c
            }
            function e(a) {
                var b = f();
                switch (a) {
                case "ne":
                    return [b.x2, b.y];
                case "nw":
                    return [b.x, b.y];
                case "se":
                    return [b.x2, b.y2];
                case "sw":
                    return [b.x, b.y2]
                }
            }
            function f() {
                if (!J.aspectRatio) return i();
                var a, b, c, d, e = J.aspectRatio,
                f = J.minSize[0] / bb,
                g = J.maxSize[0] / bb,
                k = J.maxSize[1] / cb,
                l = o - m,
                q = p - n,
                r = Math.abs(l),
                s = Math.abs(q),
                t = r / s;
                return 0 === g && (g = 10 * S),
                0 === k && (k = 10 * T),
                e > t ? (b = p, c = s * e, a = 0 > l ? m - c: c + m, 0 > a ? (a = 0, d = Math.abs((a - m) / e), b = 0 > q ? n - d: d + n) : a > S && (a = S, d = Math.abs((a - m) / e), b = 0 > q ? n - d: d + n)) : (a = o, d = r / e, b = 0 > q ? n - d: n + d, 0 > b ? (b = 0, c = Math.abs((b - n) * e), a = 0 > l ? m - c: c + m) : b > T && (b = T, c = Math.abs(b - n) * e, a = 0 > l ? m - c: c + m)),
                a > m ? (f > a - m ? a = m + f: a - m > g && (a = m + g), b = b > n ? n + (a - m) / e: n - (a - m) / e) : m > a && (f > m - a ? a = m - f: m - a > g && (a = m - g), b = b > n ? n + (m - a) / e: n - (m - a) / e),
                0 > a ? (m -= a, a = 0) : a > S && (m -= a - S, a = S),
                0 > b ? (n -= b, b = 0) : b > T && (n -= b - T, b = T),
                j(h(m, n, a, b))
            }
            function g(a) {
                return a[0] < 0 && (a[0] = 0),
                a[1] < 0 && (a[1] = 0),
                a[0] > S && (a[0] = S),
                a[1] > T && (a[1] = T),
                [Math.round(a[0]), Math.round(a[1])]
            }
            function h(a, b, c, d) {
                var e = a,
                f = c,
                g = b,
                h = d;
                return a > c && (e = c, f = a),
                b > d && (g = d, h = b),
                [e, g, f, h]
            }
            function i() {
                var a, b = o - m,
                c = p - n;
                return Z && Math.abs(b) > Z && (o = b > 0 ? m + Z: m - Z),
                $ && Math.abs(c) > $ && (p = c > 0 ? n + $: n - $),
                ab / cb && Math.abs(c) < ab / cb && (p = c > 0 ? n + ab / cb: n - ab / cb),
                _ / bb && Math.abs(b) < _ / bb && (o = b > 0 ? m + _ / bb: m - _ / bb),
                0 > m && (o -= m, m -= m),
                0 > n && (p -= n, n -= n),
                0 > o && (m -= o, o -= o),
                0 > p && (n -= p, p -= p),
                o > S && (a = o - S, m -= a, o -= a),
                p > T && (a = p - T, n -= a, p -= a),
                m > S && (a = m - T, p -= a, n -= a),
                n > T && (a = n - T, p -= a, n -= a),
                j(h(m, n, o, p))
            }
            function j(a) {
                return {
                    x: a[0],
                    y: a[1],
                    x2: a[2],
                    y2: a[3],
                    w: a[2] - a[0],
                    h: a[3] - a[1]
                }
            }
            var k, l, m = 0,
            n = 0,
            o = 0,
            p = 0;
            return {
                flipCoords: h,
                setPressed: a,
                setCurrent: b,
                getOffset: c,
                moveOffset: d,
                getCorner: e,
                getFixed: f
            }
        } (),
        mb = function() {
            function b(a, b) {
                o.left.css({
                    height: d(b)
                }),
                o.right.css({
                    height: d(b)
                })
            }
            function c() {
                return e(lb.getFixed())
            }
            function e(a) {
                o.top.css({
                    left: d(a.x),
                    width: d(a.w),
                    height: d(a.y)
                }),
                o.bottom.css({
                    top: d(a.y2),
                    left: d(a.x),
                    width: d(a.w),
                    height: d(T - a.y2)
                }),
                o.right.css({
                    left: d(a.x2),
                    width: d(S - a.x2)
                }),
                o.left.css({
                    width: d(a.x)
                })
            }
            function f() {
                return a("<div />").css({
                    position: "absolute",
                    backgroundColor: J.shadeColor || J.bgColor
                }).appendTo(n)
            }
            function g() {
                m || (m = !0, n.insertBefore(R), c(), nb.setBgOpacity(1, 0, 1), V.hide(), h(J.shadeColor || J.bgColor, 1), nb.isAwake() ? j(J.bgOpacity, 1) : j(1, 1))
            }
            function h(a, b) {
                G(l(), a, b)
            }
            function i() {
                m && (n.remove(), V.show(), m = !1, nb.isAwake() ? nb.setBgOpacity(J.bgOpacity, 1, 1) : (nb.setBgOpacity(1, 1, 1), nb.disableHandles()), G(U, 0, 1))
            }
            function j(a, b) {
                m && (J.bgFade && !b ? n.animate({
                    opacity: 1 - a
                },
                {
                    queue: !1,
                    duration: J.fadeTime
                }) : n.css({
                    opacity: 1 - a
                }))
            }
            function k() {
                J.shade ? g() : i(),
                nb.isAwake() && j(J.bgOpacity)
            }
            function l() {
                return n.children()
            }
            var m = !1,
            n = a("<div />").css({
                position: "absolute",
                zIndex: 240,
                opacity: 0
            }),
            o = {
                top: f(),
                left: f().height(T),
                right: f().height(T),
                bottom: f()
            };
            return {
                update: c,
                updateRaw: e,
                getShades: l,
                setBgColor: h,
                enable: g,
                disable: i,
                resize: b,
                refresh: k,
                opacity: j
            }
        } (),
        nb = function() {
            function b(b) {
                var c = a("<div />").css({
                    position: "absolute",
                    opacity: J.borderOpacity
                }).addClass(e(b));
                return W.append(c),
                c
            }
            function c(b, c) {
                var d = a("<div />").mousedown(n(b)).css({
                    cursor: b + "-resize",
                    position: "absolute",
                    zIndex: c
                }).addClass("ord-" + b);
                return kb.support && d.bind("touchstart.jcrop", kb.createDragger(b)),
                X.append(d),
                d
            }
            function f(a) {
                var b = J.handleSize,
                d = c(a, B++).css({
                    opacity: J.handleOpacity
                }).addClass(e("handle"));
                return b && d.width(b).height(b),
                d
            }
            function g(a) {
                return c(a, B++).addClass("jcrop-dragbar")
            }
            function h(a) {
                var b;
                for (b = 0; b < a.length; b++) E[a[b]] = g(a[b])
            }
            function i(a) {
                var c, d;
                for (d = 0; d < a.length; d++) {
                    switch (a[d]) {
                    case "n":
                        c = "hline";
                        break;
                    case "s":
                        c = "hline bottom";
                        break;
                    case "e":
                        c = "vline right";
                        break;
                    case "w":
                        c = "vline"
                    }
                    C[a[d]] = b(c)
                }
            }
            function j(a) {
                var b;
                for (b = 0; b < a.length; b++) D[a[b]] = f(a[b])
            }
            function k(a, b) {
                J.shade || V.css({
                    top: d( - b),
                    left: d( - a)
                }),
                Y.css({
                    top: d(b),
                    left: d(a)
                })
            }
            function l(a, b) {
                Y.width(Math.round(a)).height(Math.round(b))
            }
            function m() {
                var a = lb.getFixed();
                lb.setPressed([a.x, a.y]),
                lb.setCurrent([a.x2, a.y2]),
                o()
            }
            function o(a) {
                return A ? q(a) : void 0
            }
            function q(a) {
                var b = lb.getFixed();
                l(b.w, b.h),
                k(b.x, b.y),
                J.shade && mb.updateRaw(b),
                A || s(),
                a ? J.onSelect.call(qb, p(b)) : J.onChange.call(qb, p(b))
            }
            function r(a, b, c) { (A || b) && (J.bgFade && !c ? R.animate({
                    opacity: a
                },
                {
                    queue: !1,
                    duration: J.fadeTime
                }) : R.css("opacity", a))
            }
            function s() {
                Y.show(),
                J.shade ? mb.opacity(jb) : r(jb, !0),
                A = !0
            }
            function u() {
                x(),
                Y.hide(),
                J.shade ? mb.opacity(1) : r(1),
                A = !1,
                J.onRelease.call(qb)
            }
            function v() {
                F && X.show()
            }
            function w() {
                return F = !0,
                J.allowResize ? (X.show(), !0) : void 0
            }
            function x() {
                F = !1,
                X.hide()
            }
            function y(a) {
                a ? (eb = !0, x()) : (eb = !1, w())
            }
            function z() {
                y(!1),
                m()
            }
            var A, B = 370,
            C = {},
            D = {},
            E = {},
            F = !1;
            J.dragEdges && a.isArray(J.createDragbars) && h(J.createDragbars),
            a.isArray(J.createHandles) && j(J.createHandles),
            J.drawBorders && a.isArray(J.createBorders) && i(J.createBorders),
            a(document).bind("touchstart.jcrop-ios",
            function(b) {
                a(b.currentTarget).hasClass("jcrop-tracker") && b.stopPropagation()
            });
            var G = t().mousedown(n("move")).css({
                cursor: "move",
                position: "absolute",
                zIndex: 360
            });
            return kb.support && G.bind("touchstart.jcrop", kb.createDragger("move")),
            W.append(G),
            x(),
            {
                updateVisible: o,
                update: q,
                release: u,
                refresh: m,
                isAwake: function() {
                    return A
                },
                setCursor: function(a) {
                    G.css("cursor", a)
                },
                enableHandles: w,
                enableOnly: function() {
                    F = !0
                },
                showHandles: v,
                disableHandles: x,
                animMode: y,
                setBgOpacity: r,
                done: z
            }
        } (),
        ob = function() {
            function b(b) {
                hb.css({
                    zIndex: 450
                }),
                b ? a(document).bind("touchmove.jcrop", g).bind("touchend.jcrop", i) : m && a(document).bind("mousemove.jcrop", d).bind("mouseup.jcrop", e)
            }
            function c() {
                hb.css({
                    zIndex: 290
                }),
                a(document).unbind(".jcrop")
            }
            function d(a) {
                return k(h(a)),
                !1
            }
            function e(a) {
                return a.preventDefault(),
                a.stopPropagation(),
                db && (db = !1, l(h(a)), nb.isAwake() && J.onSelect.call(qb, p(lb.getFixed())), c(), k = function() {},
                l = function() {}),
                !1
            }
            function f(a, c, d) {
                return db = !0,
                k = a,
                l = c,
                b(d),
                !1
            }
            function g(a) {
                return k(h(kb.cfilter(a))),
                !1
            }
            function i(a) {
                return e(kb.cfilter(a))
            }
            function j(a) {
                hb.css("cursor", a)
            }
            var k = function() {},
            l = function() {},
            m = J.trackDocument;
            return m || hb.mousemove(d).mouseup(e).mouseout(e),
            R.before(hb),
            {
                activateHandlers: f,
                setCursor: j
            }
        } (),
        pb = function() {
            function b() {
                J.keySupport && (f.show(), f.focus())
            }
            function c() {
                f.hide()
            }
            function d(a, b, c) {
                J.allowMove && (lb.moveOffset([b, c]), nb.updateVisible(!0)),
                a.preventDefault(),
                a.stopPropagation()
            }
            function e(a) {
                if (a.ctrlKey || a.metaKey) return ! 0;
                fb = a.shiftKey ? !0 : !1;
                var b = fb ? 10 : 1;
                switch (a.keyCode) {
                case 37:
                    d(a, -b, 0);
                    break;
                case 39:
                    d(a, b, 0);
                    break;
                case 38:
                    d(a, 0, -b);
                    break;
                case 40:
                    d(a, 0, b);
                    break;
                case 27:
                    J.allowSelect && nb.release();
                    break;
                case 9:
                    return ! 0
                }
                return ! 1
            }
            var f = a('<input type="radio" />').css({
                position: "fixed",
                left: "-120px",
                width: "12px"
            }).addClass("jcrop-keymgr"),
            g = a("<div />").css({
                position: "absolute",
                overflow: "hidden"
            }).append(f);
            return J.keySupport && (f.keydown(e).blur(c), M || !J.fixedSupport ? (f.css({
                position: "absolute",
                left: "-20px"
            }), g.append(f).insertBefore(R)) : f.insertBefore(R)),
            {
                watchKeys: b
            }
        } ();
        kb.support && hb.bind("touchstart.jcrop", kb.newSelection),
        X.hide(),
        H(!0);
        var qb = {
            setImage: F,
            animateTo: v,
            setSelect: w,
            setOptions: A,
            tellSelect: y,
            tellScaled: z,
            setClass: u,
            disable: B,
            enable: C,
            cancel: D,
            release: nb.release,
            destroy: E,
            focus: pb.watchKeys,
            getBounds: function() {
                return [S * bb, T * cb]
            },
            getWidgetSize: function() {
                return [S, T]
            },
            getScaleFactor: function() {
                return [bb, cb]
            },
            getOptions: function() {
                return J
            },
            ui: {
                holder: U,
                selection: Y
            }
        };
        return L && U.bind("selectstart",
        function() {
            return ! 1
        }),
        O.data("Jcrop", qb),
        qb
    },
    a.fn.Jcrop = function(b, c) {
        var d;
        return this.each(function() {
            if (a(this).data("Jcrop")) {
                if ("api" === b) return a(this).data("Jcrop");
                a(this).data("Jcrop").setOptions(b)
            } else "IMG" == this.tagName ? a.Jcrop.Loader(this,
            function() {
                a(this).css({
                    display: "block",
                    visibility: "hidden"
                }),
                d = a.Jcrop(this, b),
                a.isFunction(c) && c.call(d)
            }) : (a(this).css({
                display: "block",
                visibility: "hidden"
            }), d = a.Jcrop(this, b), a.isFunction(c) && c.call(d))
        }),
        this
    },
    a.Jcrop.Loader = function(b, c, d) {
        function e() {
            g.complete ? (f.unbind(".jcloader"), a.isFunction(c) && c.call(g)) : window.setTimeout(e, 50)
        }
        var f = a(b),
        g = f[0];
        f.bind("load.jcloader", e).bind("error.jcloader",
        function() {
            f.unbind(".jcloader"),
            a.isFunction(d) && d.call(g)
        }),
        g.complete && a.isFunction(c) && (f.unbind(".jcloader"), c.call(g))
    },
    a.Jcrop.defaults = {
        allowSelect: !0,
        allowMove: !0,
        allowResize: !0,
        trackDocument: !0,
        baseClass: "jcrop",
        addClass: null,
        bgColor: "black",
        bgOpacity: .6,
        bgFade: !1,
        borderOpacity: .4,
        handleOpacity: .5,
        handleSize: null,
        aspectRatio: 0,
        keySupport: !0,
        createHandles: ["n", "s", "e", "w", "nw", "ne", "se", "sw"],
        createDragbars: ["n", "s", "e", "w"],
        createBorders: ["n", "s", "e", "w"],
        drawBorders: !0,
        dragEdges: !0,
        fixedSupport: !0,
        touchSupport: null,
        shade: null,
        boxWidth: 0,
        boxHeight: 0,
        boundary: 2,
        fadeTime: 400,
        animationDelay: 20,
        swingSpeed: 3,
        minSelect: [0, 0],
        maxSize: [0, 0],
        minSize: [0, 0],
        onChange: function() {},
        onSelect: function() {},
        onDblClick: function() {},
        onRelease: function() {}
    }
} (jQuery),
function(a) {
    var b = function() {
        return ! 1 === a.support.boxModel && a.support.objectAll && a.support.leadingWhitespace
    } ();
    a.jGrowl = function(b, c) {
        0 == a("#jGrowl").size() && a('<div id="jGrowl"></div>').addClass(c && c.position ? c.position: a.jGrowl.defaults.position).appendTo("body"),
        a("#jGrowl").jGrowl(b, c)
    },
    a.fn.jGrowl = function(b, c) {
        if (a.isFunction(this.each)) {
            var d = arguments;
            return this.each(function() {
                void 0 == a(this).data("jGrowl.instance") && (a(this).data("jGrowl.instance", a.extend(new a.fn.jGrowl, {
                    notifications: [],
                    element: null,
                    interval: null
                })), a(this).data("jGrowl.instance").startup(this)),
                a.isFunction(a(this).data("jGrowl.instance")[b]) ? a(this).data("jGrowl.instance")[b].apply(a(this).data("jGrowl.instance"), a.makeArray(d).slice(1)) : a(this).data("jGrowl.instance").create(b, c)
            })
        }
    },
    a.extend(a.fn.jGrowl.prototype, {
        defaults: {
            pool: 0,
            header: "",
            group: "",
            sticky: !1,
            position: "top-right",
            glue: "after",
            theme: "default",
            themeState: "highlight",
            corners: "10px",
            check: 250,
            life: 3e3,
            closeDuration: "normal",
            openDuration: "normal",
            easing: "swing",
            closer: !0,
            closeTemplate: "&times;",
            closerTemplate: "<div>[ close all ]</div>",
            log: function() {},
            beforeOpen: function() {},
            afterOpen: function() {},
            open: function() {},
            beforeClose: function() {},
            close: function() {},
            animateOpen: {
                opacity: "show"
            },
            animateClose: {
                opacity: "hide"
            }
        },
        notifications: [],
        element: null,
        interval: null,
        create: function(b, c) {
            var c = a.extend({},
            this.defaults, c);
            "undefined" != typeof c.speed && (c.openDuration = c.speed, c.closeDuration = c.speed),
            this.notifications.push({
                message: b,
                options: c
            }),
            c.log.apply(this.element, [this.element, b, c])
        },
        render: function(b) {
            var c = this,
            d = b.message,
            e = b.options;
            e.themeState = "" == e.themeState ? "": "ui-state-" + e.themeState;
            var b = a("<div/>").addClass("jGrowl-notification " + e.themeState + " ui-corner-all" + (void 0 != e.group && "" != e.group ? " " + e.group: "")).append(a("<div/>").addClass("jGrowl-close").html(e.closeTemplate)).append(a("<div/>").addClass("jGrowl-header").html(e.header)).append(a("<div/>").addClass("jGrowl-message").html(d)).data("jGrowl", e).addClass(e.theme).children("div.jGrowl-close").bind("click.jGrowl",
            function() {
                a(this).parent().trigger("jGrowl.beforeClose")
            }).parent();
            a(b).bind("mouseover.jGrowl",
            function() {
                a("div.jGrowl-notification", c.element).data("jGrowl.pause", !0)
            }).bind("mouseout.jGrowl",
            function() {
                a("div.jGrowl-notification", c.element).data("jGrowl.pause", !1)
            }).bind("jGrowl.beforeOpen",
            function() {
                e.beforeOpen.apply(b, [b, d, e, c.element]) !== !1 && a(this).trigger("jGrowl.open")
            }).bind("jGrowl.open",
            function() {
                e.open.apply(b, [b, d, e, c.element]) !== !1 && ("after" == e.glue ? a("div.jGrowl-notification:last", c.element).after(b) : a("div.jGrowl-notification:first", c.element).before(b), a(this).animate(e.animateOpen, e.openDuration, e.easing,
                function() {
                    a.support.opacity === !1 && this.style.removeAttribute("filter"),
                    null !== a(this).data("jGrowl") && (a(this).data("jGrowl").created = new Date),
                    a(this).trigger("jGrowl.afterOpen")
                }))
            }).bind("jGrowl.afterOpen",
            function() {
                e.afterOpen.apply(b, [b, d, e, c.element])
            }).bind("jGrowl.beforeClose",
            function() {
                e.beforeClose.apply(b, [b, d, e, c.element]) !== !1 && a(this).trigger("jGrowl.close")
            }).bind("jGrowl.close",
            function() {
                a(this).data("jGrowl.pause", !0),
                a(this).animate(e.animateClose, e.closeDuration, e.easing,
                function() {
                    a.isFunction(e.close) ? e.close.apply(b, [b, d, e, c.element]) !== !1 && a(this).remove() : a(this).remove()
                })
            }).trigger("jGrowl.beforeOpen"),
            "" != e.corners && void 0 != a.fn.corner && a(b).corner(e.corners),
            a("div.jGrowl-notification:parent", c.element).size() > 1 && 0 == a("div.jGrowl-closer", c.element).size() && this.defaults.closer !== !1 && a(this.defaults.closerTemplate).addClass("jGrowl-closer " + this.defaults.themeState + " ui-corner-all").addClass(this.defaults.theme).appendTo(c.element).animate(this.defaults.animateOpen, this.defaults.speed, this.defaults.easing).bind("click.jGrowl",
            function() {
                a(this).siblings().trigger("jGrowl.beforeClose"),
                a.isFunction(c.defaults.closer) && c.defaults.closer.apply(a(this).parent()[0], [a(this).parent()[0]])
            })
        },
        update: function() {
            a(this.element).find("div.jGrowl-notification:parent").each(function() {
                void 0 != a(this).data("jGrowl") && void 0 !== a(this).data("jGrowl").created && a(this).data("jGrowl").created.getTime() + parseInt(a(this).data("jGrowl").life) < (new Date).getTime() && a(this).data("jGrowl").sticky !== !0 && (void 0 == a(this).data("jGrowl.pause") || a(this).data("jGrowl.pause") !== !0) && a(this).trigger("jGrowl.beforeClose")
            }),
            this.notifications.length > 0 && (0 == this.defaults.pool || a(this.element).find("div.jGrowl-notification:parent").size() < this.defaults.pool) && this.render(this.notifications.shift()),
            a(this.element).find("div.jGrowl-notification:parent").size() < 2 && a(this.element).find("div.jGrowl-closer").animate(this.defaults.animateClose, this.defaults.speed, this.defaults.easing,
            function() {
                a(this).remove()
            })
        },
        startup: function(c) {
            this.element = a(c).addClass("jGrowl").append('<div class="jGrowl-notification"></div>'),
            this.interval = setInterval(function() {
                a(c).data("jGrowl.instance").update()
            },
            parseInt(this.defaults.check)),
            b && a(this.element).addClass("ie6")
        },
        shutdown: function() {
            a(this.element).removeClass("jGrowl").find("div.jGrowl-notification").trigger("jGrowl.close").parent().empty(),
            clearInterval(this.interval)
        },
        close: function() {
            a(this.element).find("div.jGrowl-notification").each(function() {
                a(this).trigger("jGrowl.beforeClose")
            })
        }
    }),
    a.jGrowl.defaults = a.fn.jGrowl.prototype.defaults
} (jQuery),
"function" != typeof Object.create && (Object.create = function(a) {
    function b() {}
    return b.prototype = a,
    new b
}),
function(a) {
    var b = {
        init: function(b) {
            return this.options = a.extend({},
            a.noty.defaults, b),
            this.options.layout = this.options.custom ? a.noty.layouts.inline: a.noty.layouts[this.options.layout],
            this.options.theme = a.noty.themes[this.options.theme],
            delete b.layout,
            delete b.theme,
            this.options = a.extend({},
            this.options, this.options.layout.options),
            this.options.id = "noty_" + (new Date).getTime() * Math.floor(1e6 * Math.random()),
            this.options = a.extend({},
            this.options, b),
            this._build(),
            this
        },
        _build: function() {
            var b = a('<div class="noty_bar"></div>').attr("id", this.options.id);
            if (b.append(this.options.template).find(".noty_text").html(this.options.text), this.$bar = null !== this.options.layout.parent.object ? a(this.options.layout.parent.object).css(this.options.layout.parent.css).append(b) : b, this.options.buttons) {
                this.options.closeWith = [],
                this.options.timeout = !1;
                var c = a("<div/>").addClass("noty_buttons");
                null !== this.options.layout.parent.object ? this.$bar.find(".noty_bar").append(c) : this.$bar.append(c);
                var d = this;
                a.each(this.options.buttons,
                function(b, c) {
                    var e = a("<button/>").addClass(c.addClass ? c.addClass: "gray").html(c.text).appendTo(d.$bar.find(".noty_buttons")).bind("click",
                    function() {
                        a.isFunction(c.onClick) && c.onClick.call(e, d)
                    })
                })
            }
            this.$message = this.$bar.find(".noty_message"),
            this.$closeButton = this.$bar.find(".noty_close"),
            this.$buttons = this.$bar.find(".noty_buttons"),
            a.noty.store[this.options.id] = this
        },
        show: function() {
            var b = this;
            return a(b.options.layout.container.selector).append(b.$bar),
            b.options.theme.style.apply(b),
            "function" === a.type(b.options.layout.css) ? this.options.layout.css.apply(b.$bar) : b.$bar.css(this.options.layout.css || {}),
            b.$bar.addClass(b.options.layout.addClass),
            b.options.layout.container.style.apply(a(b.options.layout.container.selector)),
            b.options.theme.callback.onShow.apply(this),
            a.inArray("click", b.options.closeWith) > -1 && b.$bar.css("cursor", "pointer").one("click",
            function(a) {
                b.stopPropagation(a),
                b.options.callback.onCloseClick && b.options.callback.onCloseClick.apply(b),
                b.close()
            }),
            a.inArray("hover", b.options.closeWith) > -1 && b.$bar.one("mouseenter",
            function() {
                b.close()
            }),
            a.inArray("button", b.options.closeWith) > -1 && b.$closeButton.one("click",
            function(a) {
                b.stopPropagation(a),
                b.close()
            }),
            -1 == a.inArray("button", b.options.closeWith) && b.$closeButton.remove(),
            b.options.callback.onShow && b.options.callback.onShow.apply(b),
            b.$bar.animate(b.options.animation.open, b.options.animation.speed, b.options.animation.easing,
            function() {
                b.options.callback.afterShow && b.options.callback.afterShow.apply(b),
                b.shown = !0
            }),
            b.options.timeout && b.$bar.delay(b.options.timeout).promise().done(function() {
                b.close()
            }),
            this
        },
        close: function() {
            if (! (this.closed || this.$bar && this.$bar.hasClass("i-am-closing-now"))) {
                var b = this;
                if (!this.shown) {
                    var c = [];
                    return a.each(a.noty.queue,
                    function(a, d) {
                        d.options.id != b.options.id && c.push(d)
                    }),
                    a.noty.queue = c,
                    void 0
                }
                b.$bar.addClass("i-am-closing-now"),
                b.options.callback.onClose && b.options.callback.onClose.apply(b),
                b.$bar.clearQueue().stop().animate(b.options.animation.close, b.options.animation.speed, b.options.animation.easing,
                function() {
                    b.options.callback.afterClose && b.options.callback.afterClose.apply(b)
                }).promise().done(function() {
                    b.options.modal && (a.notyRenderer.setModalCount( - 1), 0 == a.notyRenderer.getModalCount() && a(".noty_modal").fadeOut("fast",
                    function() {
                        a(this).remove()
                    })),
                    a.notyRenderer.setLayoutCountFor(b, -1),
                    0 == a.notyRenderer.getLayoutCountFor(b) && a(b.options.layout.container.selector).remove(),
                    "undefined" != typeof b.$bar && null !== b.$bar && (b.$bar.remove(), b.$bar = null, b.closed = !0),
                    delete a.noty.store[b.options.id],
                    b.options.theme.callback.onClose.apply(b),
                    b.options.dismissQueue || (a.noty.ontap = !0, a.notyRenderer.render()),
                    b.options.maxVisible > 0 && b.options.dismissQueue && a.notyRenderer.render()
                })
            }
        },
        setText: function(a) {
            return this.closed || (this.options.text = a, this.$bar.find(".noty_text").html(a)),
            this
        },
        setType: function(a) {
            return this.closed || (this.options.type = a, this.options.theme.style.apply(this), this.options.theme.callback.onShow.apply(this)),
            this
        },
        setTimeout: function(a) {
            if (!this.closed) {
                var b = this;
                this.options.timeout = a,
                b.$bar.delay(b.options.timeout).promise().done(function() {
                    b.close()
                })
            }
            return this
        },
        stopPropagation: function(a) {
            a = a || window.event,
            "undefined" != typeof a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
        },
        closed: !1,
        shown: !1
    };
    a.notyRenderer = {},
    a.notyRenderer.init = function(c) {
        var d = Object.create(b).init(c);
        return d.options.force ? a.noty.queue.unshift(d) : a.noty.queue.push(d),
        a.notyRenderer.render(),
        "object" == a.noty.returns ? d: d.options.id
    },
    a.notyRenderer.render = function() {
        var b = a.noty.queue[0];
        "object" === a.type(b) ? b.options.dismissQueue ? b.options.maxVisible > 0 ? a(b.options.layout.container.selector + " li").length < b.options.maxVisible && a.notyRenderer.show(a.noty.queue.shift()) : a.notyRenderer.show(a.noty.queue.shift()) : a.noty.ontap && (a.notyRenderer.show(a.noty.queue.shift()), a.noty.ontap = !1) : a.noty.ontap = !0
    },
    a.notyRenderer.show = function(b) {
        b.options.modal && (a.notyRenderer.createModalFor(b), a.notyRenderer.setModalCount(1)),
        0 == a(b.options.layout.container.selector).length ? b.options.custom ? b.options.custom.append(a(b.options.layout.container.object).addClass("i-am-new")) : a("body").append(a(b.options.layout.container.object).addClass("i-am-new")) : a(b.options.layout.container.selector).removeClass("i-am-new"),
        a.notyRenderer.setLayoutCountFor(b, 1),
        b.show()
    },
    a.notyRenderer.createModalFor = function(b) {
        0 == a(".noty_modal").length && a("<div/>").addClass("noty_modal").data("noty_modal_count", 0).css(b.options.theme.modal.css).prependTo(a("body")).fadeIn("fast")
    },
    a.notyRenderer.getLayoutCountFor = function(b) {
        return a(b.options.layout.container.selector).data("noty_layout_count") || 0
    },
    a.notyRenderer.setLayoutCountFor = function(b, c) {
        return a(b.options.layout.container.selector).data("noty_layout_count", a.notyRenderer.getLayoutCountFor(b) + c)
    },
    a.notyRenderer.getModalCount = function() {
        return a(".noty_modal").data("noty_modal_count") || 0
    },
    a.notyRenderer.setModalCount = function(b) {
        return a(".noty_modal").data("noty_modal_count", a.notyRenderer.getModalCount() + b)
    },
    a.fn.noty = function(b) {
        return b.custom = a(this),
        a.notyRenderer.init(b)
    },
    a.noty = {},
    a.noty.queue = [],
    a.noty.ontap = !0,
    a.noty.layouts = {},
    a.noty.themes = {},
    a.noty.returns = "object",
    a.noty.store = {},
    a.noty.get = function(b) {
        return a.noty.store.hasOwnProperty(b) ? a.noty.store[b] : !1
    },
    a.noty.close = function(b) {
        return a.noty.get(b) ? a.noty.get(b).close() : !1
    },
    a.noty.setText = function(b, c) {
        return a.noty.get(b) ? a.noty.get(b).setText(c) : !1
    },
    a.noty.setType = function(b, c) {
        return a.noty.get(b) ? a.noty.get(b).setType(c) : !1
    },
    a.noty.clearQueue = function() {
        a.noty.queue = []
    },
    a.noty.closeAll = function() {
        a.noty.clearQueue(),
        a.each(a.noty.store,
        function(a, b) {
            b.close()
        })
    };
    var c = window.alert;
    a.noty.consumeAlert = function(b) {
        window.alert = function(c) {
            b ? b.text = c: b = {
                text: c
            },
            a.notyRenderer.init(b)
        }
    },
    a.noty.stopConsumeAlert = function() {
        window.alert = c
    },
    a.noty.defaults = {
        layout: "top",
        theme: "agileUI",
        type: "alert",
        text: "",
        dismissQueue: !0,
        template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
        animation: {
            open: {
                height: "toggle"
            },
            close: {
                height: "toggle"
            },
            easing: "swing",
            speed: 500
        },
        timeout: !1,
        force: !1,
        modal: !1,
        maxVisible: 5,
        closeWith: ["click"],
        callback: {
            onShow: function() {},
            afterShow: function() {},
            onClose: function() {},
            afterClose: function() {},
            onCloseClick: function() {}
        },
        buttons: !1
    },
    a(window).resize(function() {
        a.each(a.noty.layouts,
        function(b, c) {
            c.container.style.apply(a(c.container.selector))
        })
    })
} (jQuery),
window.noty = function(a) {
    var b = 0,
    c = {
        animateOpen: "animation.open",
        animateClose: "animation.close",
        easing: "animation.easing",
        speed: "animation.speed",
        onShow: "callback.onShow",
        onShown: "callback.afterShow",
        onClose: "callback.onClose",
        onCloseClick: "callback.onCloseClick",
        onClosed: "callback.afterClose"
    };
    return jQuery.each(a,
    function(d, e) {
        if (c[d]) {
            b++;
            var f = c[d].split(".");
            a[f[0]] || (a[f[0]] = {}),
            a[f[0]][f[1]] = e ? e: function() {},
            delete a[d]
        }
    }),
    a.closeWith || (a.closeWith = jQuery.noty.defaults.closeWith),
    a.hasOwnProperty("closeButton") && (b++, a.closeButton && a.closeWith.push("button"), delete a.closeButton),
    a.hasOwnProperty("closeOnSelfClick") && (b++, a.closeOnSelfClick && a.closeWith.push("click"), delete a.closeOnSelfClick),
    a.hasOwnProperty("closeOnSelfOver") && (b++, a.closeOnSelfOver && a.closeWith.push("hover"), delete a.closeOnSelfOver),
    a.hasOwnProperty("custom") && (b++, "null" != a.custom.container && (a.custom = a.custom.container)),
    a.hasOwnProperty("cssPrefix") && (b++, delete a.cssPrefix),
    "noty_theme_default" == a.theme && (b++, a.theme = "agileUI"),
    a.hasOwnProperty("dismissQueue") || (a.dismissQueue = jQuery.noty.defaults.dismissQueue),
    a.hasOwnProperty("maxVisible") || (a.maxVisible = jQuery.noty.defaults.maxVisible),
    a.buttons && jQuery.each(a.buttons,
    function(a, c) {
        c.click && (b++, c.onClick = c.click, delete c.click),
        c.type && (b++, c.addClass = c.type, delete c.type)
    }),
    b && "undefined" != typeof console && console.warn && console.warn("You are using noty v2 with v1.x.x options. @deprecated until v2.2.0 - Please update your options."),
    jQuery.notyRenderer.init(a)
},
function(a) {
    a.noty.themes.agileUI = {
        name: "agileUI",
        helpers: {
            borderFix: function() {
                this.options.dismissQueue && this.options.layout.container.selector + " " + this.options.layout.parent.selector
            }
        },
        modal: {
            css: {
                position: "fixed",
                width: "100%",
                height: "100%",
                backgroundColor: "#000",
                zIndex: 1e4,
                opacity: .7,
                display: "none",
                left: 0,
                top: 0
            }
        },
        style: function() {
            switch (this.$bar.bind({
                mouseenter: function() {
                    a(this).find(".noty_close").stop().fadeTo("normal", 1)
                },
                mouseleave: function() {
                    a(this).find(".noty_close").stop().fadeTo("normal", 0)
                }
            }), this.options.type) {
            case "alert":
                this.$bar.addClass("bg-orange");
                break;
            case "notification":
                this.$bar.addClass("bg-blue");
                break;
            case "warning":
                this.$bar.addClass("bg-orange");
                break;
            case "error":
                this.$bar.addClass("bg-red");
                break;
            case "information":
                this.$bar.addClass("bg-gray");
                break;
            case "success":
                this.$bar.addClass("bg-green");
                break;
            default:
                this.$bar.addClass("bg-black")
            }
        },
        callback: {
            onShow: function() {
                a.noty.themes.agileUI.helpers.borderFix.apply(this)
            },
            onClose: function() {
                a.noty.themes.agileUI.helpers.borderFix.apply(this)
            }
        }
    }
} (jQuery),
function(a) {
    a.noty.layouts.bottom = {
        name: "bottom",
        options: {},
        container: {
            object: '<ul class="noty-wrapper" id="noty_bottom" />',
            selector: "ul#noty_bottom",
            style: function() {
                a(this).css({})
            }
        },
        parent: {
            object: "<li />",
            selector: "li",
            css: {}
        },
        css: {
            display: "none"
        },
        addClass: ""
    }
} (jQuery),
function(a) {
    a.noty.layouts.top = {
        name: "top",
        options: {},
        container: {
            object: '<ul class="noty-wrapper" id="noty_top" />',
            selector: "ul#noty_top",
            style: function() {}
        },
        parent: {
            object: "<li />",
            selector: "li",
            css: {}
        },
        css: {
            display: "none"
        },
        addClass: ""
    }
} (jQuery),
function(a) {
    a.noty.layouts.center = {
        name: "center",
        options: {
            modal: !0
        },
        container: {
            object: '<ul class="noty-wrapper" id="noty_center" />',
            selector: "ul#noty_center",
            style: function() {
                a("li", this).addClass("radius-all-4 modal-dialog"),
                a(this).css({
                    width: "300px",
                    height: "auto"
                });
                var b = a(this).clone().css({
                    visibility: "hidden",
                    display: "block",
                    position: "absolute",
                    top: 0,
                    left: 0
                }).attr("id", "dupe");
                a("body").append(b),
                b.find(".i-am-closing-now").remove(),
                b.find("li").css("display", "block");
                var c = b.height();
                b.remove(),
                a(this).hasClass("i-am-new") ? a(this).css({
                    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px",
                    top: (a(window).height() - c) / 2 + "px"
                }) : a(this).animate({
                    left: (a(window).width() - a(this).outerWidth(!1)) / 2 + "px",
                    top: (a(window).height() - c) / 2 + "px"
                },
                500)
            }
        },
        parent: {
            object: "<li />",
            selector: "li",
            css: {}
        },
        css: {
            display: "none",
            width: "310px"
        },
        addClass: ""
    }
} (jQuery),
$(document).ready(function() {
    $(".loader-show").click(function() {
        var a = $(this).attr("data-theme"),
        b = $(this).attr("data-opacity"),
        c = $(this).attr("data-style"),
        d = '<div id="loader-overlay" class="ui-front hide loader ui-widget-overlay ' + a + " opacity-" + b + '"><img src="static/images/loader-' + c + '.gif" alt="" /></div>';
        $("#loader-overlay").remove(),
        $("body").append(d),
        $("#loader-overlay").fadeIn("fast"),
        window.setTimeout(function() {
            $("#loader-overlay").fadeOut("fast")
        },
        1500)
    }),
    $(".refresh-button").click(function(a) {
        $(".glyph-icon", this).addClass("icon-spin display-block"),
        a.preventDefault();
        var b = $(this).parent().parent(),
        c = $(this).attr("data-theme"),
        d = $(this).attr("data-opacity"),
        e = $(this).attr("data-style"),
        f = '<div id="refresh-overlay" class="ui-front hide loader ui-widget-overlay ' + c + " opacity-" + d + '"><img src="static/images/loader-' + e + '.gif" alt="" /></div>';
        $("#refresh-overlay").remove(),
        $(b).append(f),
        $("#refresh-overlay").fadeIn("fast"),
        window.setTimeout(function() {
            $(".glyph-icon").removeClass("icon-spin display-block"),
            $("#refresh-overlay").fadeOut("fast")
        },
        1e3)
    })
}),
$(document).on("ready",
function() {
    $(".progressbar").each(function() {
        var a = $(this),
        b = $(this).attr("data-value");
        progress(b, a)
    })
}),
$(function() {
    $("#progress-dropdown").hover(function() {
        $(".progressbar").each(function() {
            var a = $(this),
            b = $(this).attr("data-value");
            progress(b, a)
        })
    })
}),
function(b) {
    function c() {
        var a = document.getElementsByTagName("script"),
        b = a[a.length - 1].src.split("?")[0];
        return b.split("/").length > 0 ? b.split("/").slice(0, -1).join("/") + "/": ""
    }
    function d(a, b, c) {
        for (var d = 0; d < b.length; d++) c(a, b[d])
    }
    var e = !1,
    f = !1,
    g = 5e3,
    h = 2e3,
    i = 0,
    j = b,
    k = c(),
    l = ["ms", "moz", "webkit", "o"],
    m = window.requestAnimationFrame || !1,
    n = window.cancelAnimationFrame || !1;
    if (!m) for (var o in l) {
        var p = l[o];
        m || (m = window[p + "RequestAnimationFrame"]),
        n || (n = window[p + "CancelAnimationFrame"] || window[p + "CancelRequestAnimationFrame"])
    }
    var q = window.MutationObserver || window.WebKitMutationObserver || !1,
    r = {
        zindex: "auto",
        cursoropacitymin: 0,
        cursoropacitymax: 1,
        cursorcolor: "#424242",
        cursorwidth: "5px",
        cursorborder: "1px solid #fff",
        cursorborderradius: "5px",
        scrollspeed: 60,
        mousescrollstep: 24,
        touchbehavior: !1,
        hwacceleration: !0,
        usetransition: !0,
        boxzoom: !1,
        dblclickzoom: !0,
        gesturezoom: !0,
        grabcursorenabled: !0,
        autohidemode: !0,
        background: "",
        iframeautoresize: !0,
        cursorminheight: 32,
        preservenativescrolling: !0,
        railoffset: !1,
        bouncescroll: !0,
        spacebarenabled: !0,
        railpadding: {
            top: 0,
            right: 0,
            left: 0,
            bottom: 0
        },
        disableoutline: !0,
        horizrailenabled: !0,
        railalign: "right",
        railvalign: "bottom",
        enabletranslate3d: !0,
        enablemousewheel: !0,
        enablekeyboard: !0,
        smoothscroll: !0,
        sensitiverail: !0,
        enablemouselockapi: !0,
        cursorfixedheight: !1,
        directionlockdeadzone: 6,
        hidecursordelay: 400,
        nativeparentscrolling: !0,
        enablescrollonselection: !0,
        overflowx: !0,
        overflowy: !0,
        cursordragspeed: .3,
        rtlmode: !1,
        cursordragontouch: !1,
        oneaxismousemode: "auto"
    },
    s = !1,
    t = function() {
        function a() {
            var a = ["-moz-grab", "-webkit-grab", "grab"]; (c.ischrome && !c.ischrome22 || c.isie) && (a = []);
            for (var d = 0; d < a.length; d++) {
                var e = a[d];
                if (b.style.cursor = e, b.style.cursor == e) return e
            }
            return "url(http://www.google.com/intl/en_ALL/mapfiles/openhand.cur),n-resize"
        }
        if (s) return s;
        var b = document.createElement("DIV"),
        c = {};
        c.haspointerlock = "pointerLockElement" in document || "mozPointerLockElement" in document || "webkitPointerLockElement" in document,
        c.isopera = "opera" in window,
        c.isopera12 = c.isopera && "getUserMedia" in navigator,
        c.isoperamini = "[object OperaMini]" === Object.prototype.toString.call(window.operamini),
        c.isie = "all" in document && "attachEvent" in b && !c.isopera,
        c.isieold = c.isie && !("msInterpolationMode" in b.style),
        c.isie7 = !(!c.isie || c.isieold || "documentMode" in document && 7 != document.documentMode),
        c.isie8 = c.isie && "documentMode" in document && 8 == document.documentMode,
        c.isie9 = c.isie && "performance" in window && document.documentMode >= 9,
        c.isie10 = c.isie && "performance" in window && document.documentMode >= 10,
        c.isie9mobile = /iemobile.9/i.test(navigator.userAgent),
        c.isie9mobile && (c.isie9 = !1),
        c.isie7mobile = !c.isie9mobile && c.isie7 && /iemobile/i.test(navigator.userAgent),
        c.ismozilla = "MozAppearance" in b.style,
        c.iswebkit = "WebkitAppearance" in b.style,
        c.ischrome = "chrome" in window,
        c.ischrome22 = c.ischrome && c.haspointerlock,
        c.ischrome26 = c.ischrome && "transition" in b.style,
        c.cantouch = "ontouchstart" in document.documentElement || "ontouchstart" in window,
        c.hasmstouch = window.navigator.msPointerEnabled || !1,
        c.ismac = /^mac$/i.test(navigator.platform),
        c.isios = c.cantouch && /iphone|ipad|ipod/i.test(navigator.platform),
        c.isios4 = c.isios && !("seal" in Object),
        c.isandroid = /android/i.test(navigator.userAgent),
        c.trstyle = !1,
        c.hastransform = !1,
        c.hastranslate3d = !1,
        c.transitionstyle = !1,
        c.hastransition = !1,
        c.transitionend = !1;
        for (var d = ["transform", "msTransform", "webkitTransform", "MozTransform", "OTransform"], e = 0; e < d.length; e++) if ("undefined" != typeof b.style[d[e]]) {
            c.trstyle = d[e];
            break
        }
        c.hastransform = 0 != c.trstyle,
        c.hastransform && (b.style[c.trstyle] = "translate3d(1px,2px,3px)", c.hastranslate3d = /translate3d/.test(b.style[c.trstyle])),
        c.transitionstyle = !1,
        c.prefixstyle = "",
        c.transitionend = !1;
        for (var d = ["transition", "webkitTransition", "MozTransition", "OTransition", "OTransition", "msTransition", "KhtmlTransition"], f = ["", "-webkit-", "-moz-", "-o-", "-o", "-ms-", "-khtml-"], g = ["transitionend", "webkitTransitionEnd", "transitionend", "otransitionend", "oTransitionEnd", "msTransitionEnd", "KhtmlTransitionEnd"], e = 0; e < d.length; e++) if (d[e] in b.style) {
            c.transitionstyle = d[e],
            c.prefixstyle = f[e],
            c.transitionend = g[e];
            break
        }
        return c.ischrome26 && (c.prefixstyle = f[1]),
        c.hastransition = c.transitionstyle,
        c.cursorgrabvalue = a(),
        c.hasmousecapture = "setCapture" in b,
        c.hasMutationObserver = q !== !1,
        b = null,
        s = c,
        c
    },
    u = function(a, b) {
        function c() {
            var a = s.doc.css(w.trstyle);
            return a && "matrix" == a.substr(0, 6) ? a.replace(/^.*\((.*)\)$/g, "$1").replace(/px/g, "").split(/, +/) : !1
        }
        function d() {
            var a = s.win;
            if ("zIndex" in a) return a.zIndex();
            for (; a.length > 0;) {
                if (9 == a[0].nodeType) return ! 1;
                var b = a.css("zIndex");
                if (!isNaN(b) && 0 != b) return parseInt(b);
                a = a.parent()
            }
            return ! 1
        }
        function l(a, b, c) {
            var d = a.css(b),
            e = parseFloat(d);
            if (isNaN(e)) {
                e = x[d] || 0;
                var f = 3 == e ? c ? s.win.outerHeight() - s.win.innerHeight() : s.win.outerWidth() - s.win.innerWidth() : 1;
                return s.isie8 && e && (e += 1),
                f ? e: 0
            }
            return e
        }
        function o(a, b, c, d) {
            s._bind(a, b,
            function(d) {
                var d = d ? d: window.event,
                e = {
                    original: d,
                    target: d.target || d.srcElement,
                    type: "wheel",
                    deltaMode: "MozMousePixelScroll" == d.type ? 0 : 1,
                    deltaX: 0,
                    deltaZ: 0,
                    preventDefault: function() {
                        return d.preventDefault ? d.preventDefault() : d.returnValue = !1,
                        !1
                    },
                    stopImmediatePropagation: function() {
                        d.stopImmediatePropagation ? d.stopImmediatePropagation() : d.cancelBubble = !0
                    }
                };
                return "mousewheel" == b ? (e.deltaY = -1 / 40 * d.wheelDelta, d.wheelDeltaX && (e.deltaX = -1 / 40 * d.wheelDeltaX)) : e.deltaY = d.detail,
                c.call(a, e)
            },
            d)
        }
        function p(a, b, c) {
            var d, e;
            if (0 == a.deltaMode ? (d = -Math.floor(a.deltaX * (s.opt.mousescrollstep / 54)), e = -Math.floor(a.deltaY * (s.opt.mousescrollstep / 54))) : 1 == a.deltaMode && (d = -Math.floor(a.deltaX * s.opt.mousescrollstep), e = -Math.floor(a.deltaY * s.opt.mousescrollstep)), b && s.opt.oneaxismousemode && 0 == d && e && (d = e, e = 0), d && (s.scrollmom && s.scrollmom.stop(), s.lastdeltax += d, s.debounced("mousewheelx",
            function() {
                var a = s.lastdeltax;
                s.lastdeltax = 0,
                s.rail.drag || s.doScrollLeftBy(a)
            },
            120)), e) {
                if (s.opt.nativeparentscrolling && c && !s.ispage && !s.zoomactive) if (0 > e) {
                    if (s.getScrollTop() >= s.page.maxh) return ! 0
                } else if (s.getScrollTop() <= 0) return ! 0;
                s.scrollmom && s.scrollmom.stop(),
                s.lastdeltay += e,
                s.debounced("mousewheely",
                function() {
                    var a = s.lastdeltay;
                    s.lastdeltay = 0,
                    s.rail.drag || s.doScrollBy(a)
                },
                120)
            }
            return a.stopImmediatePropagation(),
            a.preventDefault()
        }
        var s = this;
        if (this.version = "3.5.1", this.name = "nicescroll", this.me = b, this.opt = {
            doc: j("body"),
            win: !1
        },
        j.extend(this.opt, r), this.opt.snapbackspeed = 80, a) for (var u in s.opt)"undefined" != typeof a[u] && (s.opt[u] = a[u]);
        this.doc = s.opt.doc,
        this.iddoc = this.doc && this.doc[0] ? this.doc[0].id || "": "",
        this.ispage = /BODY|HTML/.test(s.opt.win ? s.opt.win[0].nodeName: this.doc[0].nodeName),
        this.haswrapper = s.opt.win !== !1,
        this.win = s.opt.win || (this.ispage ? j(window) : this.doc),
        this.docscroll = this.ispage && !this.haswrapper ? j(window) : this.win,
        this.body = j("body"),
        this.viewport = !1,
        this.isfixed = !1,
        this.iframe = !1,
        this.isiframe = "IFRAME" == this.doc[0].nodeName && "IFRAME" == this.win[0].nodeName,
        this.istextarea = "TEXTAREA" == this.win[0].nodeName,
        this.forcescreen = !1,
        this.canshowonmouseevent = "scroll" != s.opt.autohidemode,
        this.onmousedown = !1,
        this.onmouseup = !1,
        this.onmousemove = !1,
        this.onmousewheel = !1,
        this.onkeypress = !1,
        this.ongesturezoom = !1,
        this.onclick = !1,
        this.onscrollstart = !1,
        this.onscrollend = !1,
        this.onscrollcancel = !1,
        this.onzoomin = !1,
        this.onzoomout = !1,
        this.view = !1,
        this.page = !1,
        this.scroll = {
            x: 0,
            y: 0
        },
        this.scrollratio = {
            x: 0,
            y: 0
        },
        this.cursorheight = 20,
        this.scrollvaluemax = 0,
        this.checkrtlmode = !1,
        this.scrollrunning = !1,
        this.scrollmom = !1,
        this.observer = !1,
        this.observerremover = !1;
        do this.id = "ascrail" + h++;
        while (document.getElementById(this.id));
        this.rail = !1,
        this.cursor = !1,
        this.cursorfreezed = !1,
        this.selectiondrag = !1,
        this.zoom = !1,
        this.zoomactive = !1,
        this.hasfocus = !1,
        this.hasmousefocus = !1,
        this.visibility = !0,
        this.locked = !1,
        this.hidden = !1,
        this.cursoractive = !0,
        this.overflowx = s.opt.overflowx,
        this.overflowy = s.opt.overflowy,
        this.nativescrollingarea = !1,
        this.checkarea = 0,
        this.events = [],
        this.saved = {},
        this.delaylist = {},
        this.synclist = {},
        this.lastdeltax = 0,
        this.lastdeltay = 0,
        this.detected = t();
        var w = j.extend({},
        this.detected);
        this.canhwscroll = w.hastransform && s.opt.hwacceleration,
        this.ishwscroll = this.canhwscroll && s.haswrapper,
        this.istouchcapable = !1,
        w.cantouch && w.ischrome && !w.isios && !w.isandroid && (this.istouchcapable = !0, w.cantouch = !1),
        w.cantouch && w.ismozilla && !w.isios && !w.isandroid && (this.istouchcapable = !0, w.cantouch = !1),
        s.opt.enablemouselockapi || (w.hasmousecapture = !1, w.haspointerlock = !1),
        this.delayed = function(a, b, c, d) {
            var e = s.delaylist[a],
            f = (new Date).getTime();
            return ! d && e && e.tt ? !1 : (e && e.tt && clearTimeout(e.tt), e && e.last + c > f && !e.tt ? s.delaylist[a] = {
                last: f + c,
                tt: setTimeout(function() {
                    s.delaylist[a].tt = 0,
                    b.call()
                },
                c)
            }: e && e.tt || (s.delaylist[a] = {
                last: f,
                tt: 0
            },
            setTimeout(function() {
                b.call()
            },
            0)), void 0)
        },
        this.debounced = function(a, b, c) {
            var d = s.delaylist[a]; (new Date).getTime(),
            s.delaylist[a] = b,
            d || setTimeout(function() {
                var b = s.delaylist[a];
                s.delaylist[a] = !1,
                b.call()
            },
            c)
        },
        this.synched = function(a, b) {
            function c() {
                s.onsync || (m(function() {
                    s.onsync = !1;
                    for (a in s.synclist) {
                        var b = s.synclist[a];
                        b && b.call(s),
                        s.synclist[a] = !1
                    }
                }), s.onsync = !0)
            }
            return s.synclist[a] = b,
            c(),
            a
        },
        this.unsynched = function(a) {
            s.synclist[a] && (s.synclist[a] = !1)
        },
        this.css = function(a, b) {
            for (var c in b) s.saved.css.push([a, c, a.css(c)]),
            a.css(c, b[c])
        },
        this.scrollTop = function(a) {
            return "undefined" == typeof a ? s.getScrollTop() : s.setScrollTop(a)
        },
        this.scrollLeft = function(a) {
            return "undefined" == typeof a ? s.getScrollLeft() : s.setScrollLeft(a)
        },
        BezierClass = function(a, b, c, d, e, f, g) {
            this.st = a,
            this.ed = b,
            this.spd = c,
            this.p1 = d || 0,
            this.p2 = e || 1,
            this.p3 = f || 0,
            this.p4 = g || 1,
            this.ts = (new Date).getTime(),
            this.df = this.ed - this.st
        },
        BezierClass.prototype = {
            B2: function(a) {
                return 3 * a * a * (1 - a)
            },
            B3: function(a) {
                return 3 * a * (1 - a) * (1 - a)
            },
            B4: function(a) {
                return (1 - a) * (1 - a) * (1 - a)
            },
            getNow: function() {
                var a = (new Date).getTime(),
                b = 1 - (a - this.ts) / this.spd,
                c = this.B2(b) + this.B3(b) + this.B4(b);
                return 0 > b ? this.ed: this.st + Math.round(this.df * c)
            },
            update: function(a, b) {
                return this.st = this.getNow(),
                this.ed = a,
                this.spd = b,
                this.ts = (new Date).getTime(),
                this.df = this.ed - this.st,
                this
            }
        },
        this.ishwscroll ? (this.doc.translate = {
            x: 0,
            y: 0,
            tx: "0px",
            ty: "0px"
        },
        w.hastranslate3d && w.isios && this.doc.css("-webkit-backface-visibility", "hidden"), this.getScrollTop = function(a) {
            if (!a) {
                var b = c();
                if (b) return 16 == b.length ? -b[13] : -b[5];
                if (s.timerscroll && s.timerscroll.bz) return s.timerscroll.bz.getNow()
            }
            return s.doc.translate.y
        },
        this.getScrollLeft = function(a) {
            if (!a) {
                var b = c();
                if (b) return 16 == b.length ? -b[12] : -b[4];
                if (s.timerscroll && s.timerscroll.bh) return s.timerscroll.bh.getNow()
            }
            return s.doc.translate.x
        },
        this.notifyScrollEvent = document.createEvent ?
        function(a) {
            var b = document.createEvent("UIEvents");
            b.initUIEvent("scroll", !1, !0, window, 1),
            a.dispatchEvent(b)
        }: document.fireEvent ?
        function(a) {
            var b = document.createEventObject();
            a.fireEvent("onscroll"),
            b.cancelBubble = !0
        }: function() {},
        w.hastranslate3d && s.opt.enabletranslate3d ? (this.setScrollTop = function(a, b) {
            s.doc.translate.y = a,
            s.doc.translate.ty = -1 * a + "px",
            s.doc.css(w.trstyle, "translate3d(" + s.doc.translate.tx + "," + s.doc.translate.ty + ",0px)"),
            b || s.notifyScrollEvent(s.win[0])
        },
        this.setScrollLeft = function(a, b) {
            s.doc.translate.x = a,
            s.doc.translate.tx = -1 * a + "px",
            s.doc.css(w.trstyle, "translate3d(" + s.doc.translate.tx + "," + s.doc.translate.ty + ",0px)"),
            b || s.notifyScrollEvent(s.win[0])
        }) : (this.setScrollTop = function(a, b) {
            s.doc.translate.y = a,
            s.doc.translate.ty = -1 * a + "px",
            s.doc.css(w.trstyle, "translate(" + s.doc.translate.tx + "," + s.doc.translate.ty + ")"),
            b || s.notifyScrollEvent(s.win[0])
        },
        this.setScrollLeft = function(a, b) {
            s.doc.translate.x = a,
            s.doc.translate.tx = -1 * a + "px",
            s.doc.css(w.trstyle, "translate(" + s.doc.translate.tx + "," + s.doc.translate.ty + ")"),
            b || s.notifyScrollEvent(s.win[0])
        })) : (this.getScrollTop = function() {
            return s.docscroll.scrollTop()
        },
        this.setScrollTop = function(a) {
            return s.docscroll.scrollTop(a)
        },
        this.getScrollLeft = function() {
            return s.docscroll.scrollLeft()
        },
        this.setScrollLeft = function(a) {
            return s.docscroll.scrollLeft(a)
        }),
        this.getTarget = function(a) {
            return a ? a.target ? a.target: a.srcElement ? a.srcElement: !1 : !1
        },
        this.hasParent = function(a, b) {
            if (!a) return ! 1;
            for (var c = a.target || a.srcElement || a || !1; c && c.id != b;) c = c.parentNode || !1;
            return c !== !1
        };
        var x = {
            thin: 1,
            medium: 3,
            thick: 5
        };
        this.getOffset = function() {
            if (s.isfixed) return {
                top: parseFloat(s.win.css("top")),
                left: parseFloat(s.win.css("left"))
            };
            if (!s.viewport) return s.win.offset();
            var a = s.win.offset(),
            b = s.viewport.offset();
            return {
                top: a.top - b.top + s.viewport.scrollTop(),
                left: a.left - b.left + s.viewport.scrollLeft()
            }
        },
        this.updateScrollBar = function(a) {
            if (s.ishwscroll) s.rail.css({
                height: s.win.innerHeight()
            }),
            s.railh && s.railh.css({
                width: s.win.innerWidth()
            });
            else {
                var b = s.getOffset(),
                c = {
                    top: b.top,
                    left: b.left
                };
                c.top += l(s.win, "border-top-width", !0),
                (s.win.outerWidth() - s.win.innerWidth()) / 2,
                c.left += s.rail.align ? s.win.outerWidth() - l(s.win, "border-right-width") - s.rail.width: l(s.win, "border-left-width");
                var d = s.opt.railoffset;
                if (d && (d.top && (c.top += d.top), s.rail.align && d.left && (c.left += d.left)), s.locked || s.rail.css({
                    top: c.top,
                    left: c.left,
                    height: a ? a.h: s.win.innerHeight()
                }), s.zoom && s.zoom.css({
                    top: c.top + 1,
                    left: 1 == s.rail.align ? c.left - 20 : c.left + s.rail.width + 4
                }), s.railh && !s.locked) {
                    var c = {
                        top: b.top,
                        left: b.left
                    },
                    e = s.railh.align ? c.top + l(s.win, "border-top-width", !0) + s.win.innerHeight() - s.railh.height: c.top + l(s.win, "border-top-width", !0),
                    f = c.left + l(s.win, "border-left-width");
                    s.railh.css({
                        top: e,
                        left: f,
                        width: s.railh.width
                    })
                }
            }
        },
        this.doRailClick = function(a, b, c) {
            var d, e, f, g;
            s.locked || (s.cancelEvent(a), b ? (d = c ? s.doScrollLeft: s.doScrollTop, f = c ? (a.pageX - s.railh.offset().left - s.cursorwidth / 2) * s.scrollratio.x: (a.pageY - s.rail.offset().top - s.cursorheight / 2) * s.scrollratio.y, d(f)) : (d = c ? s.doScrollLeftBy: s.doScrollBy, f = c ? s.scroll.x: s.scroll.y, g = c ? a.pageX - s.railh.offset().left: a.pageY - s.rail.offset().top, e = c ? s.view.w: s.view.h, f >= g ? d(e) : d( - e)))
        },
        s.hasanimationframe = m,
        s.hascancelanimationframe = n,
        s.hasanimationframe ? s.hascancelanimationframe || (n = function() {
            s.cancelAnimationFrame = !0
        }) : (m = function(a) {
            return setTimeout(a, 15 - Math.floor( + new Date / 1e3) % 16)
        },
        n = clearInterval),
        this.init = function() {
            function a(b) {
                if (s.selectiondrag) {
                    if (b) {
                        var c = s.win.outerHeight(),
                        d = b.pageY - s.selectiondrag.top;
                        d > 0 && c > d && (d = 0),
                        d >= c && (d -= c),
                        s.selectiondrag.df = d
                    }
                    if (0 != s.selectiondrag.df) {
                        var e = 2 * -Math.floor(s.selectiondrag.df / 6);
                        s.doScrollBy(e),
                        s.debounced("doselectionscroll",
                        function() {
                            a()
                        },
                        50)
                    }
                }
            }
            function b() {
                s.iframexd = !1;
                try {
                    var a = "contentDocument" in this ? this.contentDocument: this.contentWindow.document;
                    a.domain
                } catch(b) {
                    s.iframexd = !0,
                    a = !1
                }
                if (s.iframexd) return "console" in window && console.log("NiceScroll error: policy restriced iframe"),
                !0;
                if (s.forcescreen = !0, s.isiframe && (s.iframe = {
                    doc: j(a),
                    html: s.doc.contents().find("html")[0],
                    body: s.doc.contents().find("body")[0]
                },
                s.getContentSize = function() {
                    return {
                        w: Math.max(s.iframe.html.scrollWidth, s.iframe.body.scrollWidth),
                        h: Math.max(s.iframe.html.scrollHeight, s.iframe.body.scrollHeight)
                    }
                },
                s.docscroll = j(s.iframe.body)), !w.isios && s.opt.iframeautoresize && !s.isiframe) {
                    s.win.scrollTop(0),
                    s.doc.height("");
                    var c = Math.max(a.getElementsByTagName("html")[0].scrollHeight, a.body.scrollHeight);
                    s.doc.height(c)
                }
                s.lazyResize(30),
                w.isie7 && s.css(j(s.iframe.html), {
                    "overflow-y": "hidden"
                }),
                s.css(j(s.iframe.body), {
                    "overflow-y": "hidden"
                }),
                w.isios && s.haswrapper && s.css(j(a.body), {
                    "-webkit-transform": "translate3d(0,0,0)"
                }),
                "contentWindow" in this ? s.bind(this.contentWindow, "scroll", s.onscroll) : s.bind(a, "scroll", s.onscroll),
                s.opt.enablemousewheel && s.bind(a, "mousewheel", s.onmousewheel),
                s.opt.enablekeyboard && s.bind(a, w.isopera ? "keypress": "keydown", s.onkeypress),
                (w.cantouch || s.opt.touchbehavior) && (s.bind(a, "mousedown", s.ontouchstart), s.bind(a, "mousemove",
                function(a) {
                    s.ontouchmove(a, !0)
                }), s.opt.grabcursorenabled && w.cursorgrabvalue && s.css(j(a.body), {
                    cursor: w.cursorgrabvalue
                })),
                s.bind(a, "mouseup", s.ontouchend),
                s.zoom && (s.opt.dblclickzoom && s.bind(a, "dblclick", s.doZoom), s.ongesturezoom && s.bind(a, "gestureend", s.ongesturezoom))
            }
            if (s.saved.css = [], w.isie7mobile) return ! 0;
            if (w.isoperamini) return ! 0;
            if (w.hasmstouch && s.css(s.ispage ? j("html") : s.win, {
                "-ms-touch-action": "none"
            }), s.zindex = "auto", s.zindex = s.ispage || "auto" != s.opt.zindex ? s.opt.zindex: d() || "auto", s.ispage || "auto" == s.zindex || s.zindex > i && (i = s.zindex), s.isie && 0 == s.zindex && "auto" == s.opt.zindex && (s.zindex = "auto"), !s.ispage || !w.cantouch && !w.isieold && !w.isie9mobile) {
                var c = s.docscroll;
                s.ispage && (c = s.haswrapper ? s.win: s.doc),
                w.isie9mobile || s.css(c, {
                    "overflow-y": "hidden"
                }),
                s.ispage && w.isie7 && ("BODY" == s.doc[0].nodeName ? s.css(j("html"), {
                    "overflow-y": "hidden"
                }) : "HTML" == s.doc[0].nodeName && s.css(j("body"), {
                    "overflow-y": "hidden"
                })),
                !w.isios || s.ispage || s.haswrapper || s.css(j("body"), {
                    "-webkit-overflow-scrolling": "touch"
                });
                var h = j(document.createElement("div"));
                h.css({
                    position: "relative",
                    top: 0,
                    "float": "right",
                    width: s.opt.cursorwidth,
                    height: "0px",
                    "background-color": s.opt.cursorcolor,
                    border: s.opt.cursorborder,
                    "background-clip": "padding-box",
                    "-webkit-border-radius": s.opt.cursorborderradius,
                    "-moz-border-radius": s.opt.cursorborderradius,
                    "border-radius": s.opt.cursorborderradius
                }),
                h.hborder = parseFloat(h.outerHeight() - h.innerHeight()),
                s.cursor = h;
                var l = j(document.createElement("div"));
                l.attr("id", s.id),
                l.addClass("nicescroll-rails");
                var m, n, o = ["left", "right"];
                for (var p in o) n = o[p],
                m = s.opt.railpadding[n],
                m ? l.css("padding-" + n, m + "px") : s.opt.railpadding[n] = 0;
                l.append(h),
                l.width = Math.max(parseFloat(s.opt.cursorwidth), h.outerWidth()) + s.opt.railpadding.left + s.opt.railpadding.right,
                l.css({
                    width: l.width + "px",
                    zIndex: s.zindex,
                    background: s.opt.background,
                    cursor: "default"
                }),
                l.visibility = !0,
                l.scrollable = !0,
                l.align = "left" == s.opt.railalign ? 0 : 1,
                s.rail = l,
                s.rail.drag = !1;
                var r = !1;
                if (!s.opt.boxzoom || s.ispage || w.isieold || (r = document.createElement("div"), s.bind(r, "click", s.doZoom), s.zoom = j(r), s.zoom.css({
                    cursor: "pointer",
                    "z-index": s.zindex,
                    backgroundImage: "url(" + k + "zoomico.png)",
                    height: 18,
                    width: 18,
                    backgroundPosition: "0px 0px"
                }), s.opt.dblclickzoom && s.bind(s.win, "dblclick", s.doZoom), w.cantouch && s.opt.gesturezoom && (s.ongesturezoom = function(a) {
                    return a.scale > 1.5 && s.doZoomIn(a),
                    a.scale < .8 && s.doZoomOut(a),
                    s.cancelEvent(a)
                },
                s.bind(s.win, "gestureend", s.ongesturezoom))), s.railh = !1, s.opt.horizrailenabled) {
                    s.css(c, {
                        "overflow-x": "hidden"
                    });
                    var h = j(document.createElement("div"));
                    h.css({
                        position: "relative",
                        top: 0,
                        height: s.opt.cursorwidth,
                        width: "0px",
                        "background-color": s.opt.cursorcolor,
                        border: s.opt.cursorborder,
                        "background-clip": "padding-box",
                        "-webkit-border-radius": s.opt.cursorborderradius,
                        "-moz-border-radius": s.opt.cursorborderradius,
                        "border-radius": s.opt.cursorborderradius
                    }),
                    h.wborder = parseFloat(h.outerWidth() - h.innerWidth()),
                    s.cursorh = h;
                    var t = j(document.createElement("div"));
                    t.attr("id", s.id + "-hr"),
                    t.addClass("nicescroll-rails"),
                    t.height = Math.max(parseFloat(s.opt.cursorwidth), h.outerHeight()),
                    t.css({
                        height: t.height + "px",
                        zIndex: s.zindex,
                        background: s.opt.background
                    }),
                    t.append(h),
                    t.visibility = !0,
                    t.scrollable = !0,
                    t.align = "top" == s.opt.railvalign ? 0 : 1,
                    s.railh = t,
                    s.railh.drag = !1
                }
                if (s.ispage) l.css({
                    position: "fixed",
                    top: "0px",
                    height: "100%"
                }),
                l.align ? l.css({
                    right: "0px"
                }) : l.css({
                    left: "0px"
                }),
                s.body.append(l),
                s.railh && (t.css({
                    position: "fixed",
                    left: "0px",
                    width: "100%"
                }), t.align ? t.css({
                    bottom: "0px"
                }) : t.css({
                    top: "0px"
                }), s.body.append(t));
                else {
                    if (s.ishwscroll) {
                        "static" == s.win.css("position") && s.css(s.win, {
                            position: "relative"
                        });
                        var u = "HTML" == s.win[0].nodeName ? s.body: s.win;
                        s.zoom && (s.zoom.css({
                            position: "absolute",
                            top: 1,
                            right: 0,
                            "margin-right": l.width + 4
                        }), u.append(s.zoom)),
                        l.css({
                            position: "absolute",
                            top: 0
                        }),
                        l.align ? l.css({
                            right: 0
                        }) : l.css({
                            left: 0
                        }),
                        u.append(l),
                        t && (t.css({
                            position: "absolute",
                            left: 0,
                            bottom: 0
                        }), t.align ? t.css({
                            bottom: 0
                        }) : t.css({
                            top: 0
                        }), u.append(t))
                    } else {
                        s.isfixed = "fixed" == s.win.css("position");
                        var x = s.isfixed ? "fixed": "absolute";
                        s.isfixed || (s.viewport = s.getViewport(s.win[0])),
                        s.viewport && (s.body = s.viewport, 0 == /fixed|relative|absolute/.test(s.viewport.css("position")) && s.css(s.viewport, {
                            position: "relative"
                        })),
                        l.css({
                            position: x
                        }),
                        s.zoom && s.zoom.css({
                            position: x
                        }),
                        s.updateScrollBar(),
                        s.body.append(l),
                        s.zoom && s.body.append(s.zoom),
                        s.railh && (t.css({
                            position: x
                        }), s.body.append(t))
                    }
                    w.isios && s.css(s.win, {
                        "-webkit-tap-highlight-color": "rgba(0,0,0,0)",
                        "-webkit-touch-callout": "none"
                    }),
                    w.isie && s.opt.disableoutline && s.win.attr("hideFocus", "true"),
                    w.iswebkit && s.opt.disableoutline && s.win.css({
                        outline: "none"
                    })
                }
                if (s.opt.autohidemode === !1 ? (s.autohidedom = !1, s.rail.css({
                    opacity: s.opt.cursoropacitymax
                }), s.railh && s.railh.css({
                    opacity: s.opt.cursoropacitymax
                })) : s.opt.autohidemode === !0 || "leave" === s.opt.autohidemode ? (s.autohidedom = j().add(s.rail), w.isie8 && (s.autohidedom = s.autohidedom.add(s.cursor)), s.railh && (s.autohidedom = s.autohidedom.add(s.railh)), s.railh && w.isie8 && (s.autohidedom = s.autohidedom.add(s.cursorh))) : "scroll" == s.opt.autohidemode ? (s.autohidedom = j().add(s.rail), s.railh && (s.autohidedom = s.autohidedom.add(s.railh))) : "cursor" == s.opt.autohidemode ? (s.autohidedom = j().add(s.cursor), s.railh && (s.autohidedom = s.autohidedom.add(s.cursorh))) : "hidden" == s.opt.autohidemode && (s.autohidedom = !1, s.hide(), s.locked = !1), w.isie9mobile) {
                    s.scrollmom = new v(s),
                    s.onmangotouch = function() {
                        var a = s.getScrollTop(),
                        b = s.getScrollLeft();
                        if (a == s.scrollmom.lastscrolly && b == s.scrollmom.lastscrollx) return ! 0;
                        var c = a - s.mangotouch.sy,
                        d = b - s.mangotouch.sx,
                        e = Math.round(Math.sqrt(Math.pow(d, 2) + Math.pow(c, 2)));
                        if (0 != e) {
                            var f = 0 > c ? -1 : 1,
                            g = 0 > d ? -1 : 1,
                            h = +new Date;
                            if (s.mangotouch.lazy && clearTimeout(s.mangotouch.lazy), h - s.mangotouch.tm > 80 || s.mangotouch.dry != f || s.mangotouch.drx != g) s.scrollmom.stop(),
                            s.scrollmom.reset(b, a),
                            s.mangotouch.sy = a,
                            s.mangotouch.ly = a,
                            s.mangotouch.sx = b,
                            s.mangotouch.lx = b,
                            s.mangotouch.dry = f,
                            s.mangotouch.drx = g,
                            s.mangotouch.tm = h;
                            else {
                                s.scrollmom.stop(),
                                s.scrollmom.update(s.mangotouch.sx - d, s.mangotouch.sy - c),
                                h - s.mangotouch.tm,
                                s.mangotouch.tm = h;
                                var i = Math.max(Math.abs(s.mangotouch.ly - a), Math.abs(s.mangotouch.lx - b));
                                s.mangotouch.ly = a,
                                s.mangotouch.lx = b,
                                i > 2 && (s.mangotouch.lazy = setTimeout(function() {
                                    s.mangotouch.lazy = !1,
                                    s.mangotouch.dry = 0,
                                    s.mangotouch.drx = 0,
                                    s.mangotouch.tm = 0,
                                    s.scrollmom.doMomentum(30)
                                },
                                100))
                            }
                        }
                    };
                    var y = s.getScrollTop(),
                    z = s.getScrollLeft();
                    s.mangotouch = {
                        sy: y,
                        ly: y,
                        dry: 0,
                        sx: z,
                        lx: z,
                        drx: 0,
                        lazy: !1,
                        tm: 0
                    },
                    s.bind(s.docscroll, "scroll", s.onmangotouch)
                } else {
                    if (w.cantouch || s.istouchcapable || s.opt.touchbehavior || w.hasmstouch) {
                        s.scrollmom = new v(s),
                        s.ontouchstart = function(a) {
                            if (a.pointerType && 2 != a.pointerType) return ! 1;
                            if (s.hasmoving = !1, !s.locked) {
                                if (w.hasmstouch) for (var b = a.target ? a.target: !1; b;) {
                                    var c = j(b).getNiceScroll();
                                    if (c.length > 0 && c[0].me == s.me) break;
                                    if (c.length > 0) return ! 1;
                                    if ("DIV" == b.nodeName && b.id == s.id) break;
                                    b = b.parentNode ? b.parentNode: !1
                                }
                                s.cancelScroll();
                                var b = s.getTarget(a);
                                if (b) {
                                    var d = /INPUT/i.test(b.nodeName) && /range/i.test(b.type);
                                    if (d) return s.stopPropagation(a)
                                }
                                if (! ("clientX" in a) && "changedTouches" in a && (a.clientX = a.changedTouches[0].clientX, a.clientY = a.changedTouches[0].clientY), s.forcescreen) {
                                    var e = a,
                                    a = {
                                        original: a.original ? a.original: a
                                    };
                                    a.clientX = e.screenX,
                                    a.clientY = e.screenY
                                }
                                if (s.rail.drag = {
                                    x: a.clientX,
                                    y: a.clientY,
                                    sx: s.scroll.x,
                                    sy: s.scroll.y,
                                    st: s.getScrollTop(),
                                    sl: s.getScrollLeft(),
                                    pt: 2,
                                    dl: !1
                                },
                                s.ispage || !s.opt.directionlockdeadzone) s.rail.drag.dl = "f";
                                else {
                                    var f = {
                                        w: j(window).width(),
                                        h: j(window).height()
                                    },
                                    g = {
                                        w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                                        h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                                    },
                                    h = Math.max(0, g.h - f.h),
                                    i = Math.max(0, g.w - f.w);
                                    s.rail.drag.ck = !s.rail.scrollable && s.railh.scrollable ? h > 0 ? "v": !1 : s.rail.scrollable && !s.railh.scrollable ? i > 0 ? "h": !1 : !1,
                                    s.rail.drag.ck || (s.rail.drag.dl = "f")
                                }
                                if (s.opt.touchbehavior && s.isiframe && w.isie) {
                                    var k = s.win.position();
                                    s.rail.drag.x += k.left,
                                    s.rail.drag.y += k.top
                                }
                                if (s.hasmoving = !1, s.lastmouseup = !1, s.scrollmom.reset(a.clientX, a.clientY), !w.cantouch && !this.istouchcapable && !w.hasmstouch) {
                                    var l = b ? /INPUT|SELECT|TEXTAREA/i.test(b.nodeName) : !1;
                                    if (!l) return ! s.ispage && w.hasmousecapture && b.setCapture(),
                                    s.opt.touchbehavior ? (b.onclick && !b._onclick && (b._onclick = b.onclick, b.onclick = function(a) {
                                        return + new Date - s.scrollmom.lasttime,
                                        s.hasmoving ? !1 : (b._onclick.call(this, a), void 0)
                                    }), s.cancelEvent(a)) : s.stopPropagation(a);
                                    /SUBMIT|CANCEL|BUTTON/i.test(j(b).attr("type")) && (pc = {
                                        tg: b,
                                        click: !1
                                    },
                                    s.preventclick = pc)
                                }
                            }
                        },
                        s.ontouchend = function(a) {
                            return a.pointerType && 2 != a.pointerType ? !1 : s.rail.drag && 2 == s.rail.drag.pt && (s.scrollmom.doMomentum(), s.rail.drag = !1, s.hasmoving && (s.lastmouseup = !0, s.hideCursor(), w.hasmousecapture && document.releaseCapture(), !w.cantouch)) ? s.cancelEvent(a) : void 0
                        };
                        var A = s.opt.touchbehavior && s.isiframe && !w.hasmousecapture;
                        s.ontouchmove = function(a, b) {
                            if (a.pointerType && 2 != a.pointerType) return ! 1;
                            if (s.rail.drag && 2 == s.rail.drag.pt) {
                                if (w.cantouch && "undefined" == typeof a.original) return ! 0;
                                s.hasmoving = !0,
                                s.preventclick && !s.preventclick.click && (s.preventclick.click = s.preventclick.tg.onclick || !1, s.preventclick.tg.onclick = s.onpreventclick);
                                var c = j.extend({
                                    original: a
                                },
                                a);
                                if (a = c, "changedTouches" in a && (a.clientX = a.changedTouches[0].clientX, a.clientY = a.changedTouches[0].clientY), s.forcescreen) {
                                    var d = a,
                                    a = {
                                        original: a.original ? a.original: a
                                    };
                                    a.clientX = d.screenX,
                                    a.clientY = d.screenY
                                }
                                var e = ofy = 0;
                                if (A && !b) {
                                    var f = s.win.position();
                                    e = -f.left,
                                    ofy = -f.top
                                }
                                var g = a.clientY + ofy,
                                h = g - s.rail.drag.y,
                                i = a.clientX + e,
                                k = i - s.rail.drag.x,
                                l = s.rail.drag.st - h;
                                if (s.ishwscroll && s.opt.bouncescroll ? 0 > l ? l = Math.round(l / 2) : l > s.page.maxh && (l = s.page.maxh + Math.round((l - s.page.maxh) / 2)) : (0 > l && (l = 0, g = 0), l > s.page.maxh && (l = s.page.maxh, g = 0)), s.railh && s.railh.scrollable) {
                                    var m = s.rail.drag.sl - k;
                                    s.ishwscroll && s.opt.bouncescroll ? 0 > m ? m = Math.round(m / 2) : m > s.page.maxw && (m = s.page.maxw + Math.round((m - s.page.maxw) / 2)) : (0 > m && (m = 0, i = 0), m > s.page.maxw && (m = s.page.maxw, i = 0))
                                }
                                var n = !1;
                                if (s.rail.drag.dl) n = !0,
                                "v" == s.rail.drag.dl ? m = s.rail.drag.sl: "h" == s.rail.drag.dl && (l = s.rail.drag.st);
                                else {
                                    var o = Math.abs(h),
                                    p = Math.abs(k),
                                    q = s.opt.directionlockdeadzone;
                                    if ("v" == s.rail.drag.ck) {
                                        if (o > q && .3 * o >= p) return s.rail.drag = !1,
                                        !0;
                                        p > q && (s.rail.drag.dl = "f", j("body").scrollTop(j("body").scrollTop()))
                                    } else if ("h" == s.rail.drag.ck) {
                                        if (p > q && .3 * p >= o) return s.rail.drag = !1,
                                        !0;
                                        o > q && (s.rail.drag.dl = "f", j("body").scrollLeft(j("body").scrollLeft()))
                                    }
                                }
                                if (s.synched("touchmove",
                                function() {
                                    s.rail.drag && 2 == s.rail.drag.pt && (s.prepareTransition && s.prepareTransition(0), s.rail.scrollable && s.setScrollTop(l), s.scrollmom.update(i, g), s.railh && s.railh.scrollable ? (s.setScrollLeft(m), s.showCursor(l, m)) : s.showCursor(l), w.isie10 && document.selection.clear())
                                }), w.ischrome && s.istouchcapable && (n = !1), n) return s.cancelEvent(a)
                            }
                        }
                    }
                    s.onmousedown = function(a, b) {
                        if (!s.rail.drag || 1 == s.rail.drag.pt) {
                            if (s.locked) return s.cancelEvent(a);
                            s.cancelScroll(),
                            s.rail.drag = {
                                x: a.clientX,
                                y: a.clientY,
                                sx: s.scroll.x,
                                sy: s.scroll.y,
                                pt: 1,
                                hr: !!b
                            };
                            var c = s.getTarget(a);
                            return ! s.ispage && w.hasmousecapture && c.setCapture(),
                            s.isiframe && !w.hasmousecapture && (s.saved.csspointerevents = s.doc.css("pointer-events"), s.css(s.doc, {
                                "pointer-events": "none"
                            })),
                            s.cancelEvent(a)
                        }
                    },
                    s.onmouseup = function(a) {
                        if (s.rail.drag) {
                            if (w.hasmousecapture && document.releaseCapture(), s.isiframe && !w.hasmousecapture && s.doc.css("pointer-events", s.saved.csspointerevents), 1 != s.rail.drag.pt) return;
                            return s.rail.drag = !1,
                            s.cancelEvent(a)
                        }
                    },
                    s.onmousemove = function(a) {
                        if (s.rail.drag) {
                            if (1 != s.rail.drag.pt) return;
                            if (w.ischrome && 0 == a.which) return s.onmouseup(a);
                            if (s.cursorfreezed = !0, s.rail.drag.hr) {
                                s.scroll.x = s.rail.drag.sx + (a.clientX - s.rail.drag.x),
                                s.scroll.x < 0 && (s.scroll.x = 0);
                                var b = s.scrollvaluemaxw;
                                s.scroll.x > b && (s.scroll.x = b)
                            } else {
                                s.scroll.y = s.rail.drag.sy + (a.clientY - s.rail.drag.y),
                                s.scroll.y < 0 && (s.scroll.y = 0);
                                var c = s.scrollvaluemax;
                                s.scroll.y > c && (s.scroll.y = c)
                            }
                            return s.synched("mousemove",
                            function() {
                                s.rail.drag && 1 == s.rail.drag.pt && (s.showCursor(), s.rail.drag.hr ? s.doScrollLeft(Math.round(s.scroll.x * s.scrollratio.x), s.opt.cursordragspeed) : s.doScrollTop(Math.round(s.scroll.y * s.scrollratio.y), s.opt.cursordragspeed))
                            }),
                            s.cancelEvent(a)
                        }
                    },
                    w.cantouch || s.opt.touchbehavior ? (s.onpreventclick = function(a) {
                        return s.preventclick ? (s.preventclick.tg.onclick = s.preventclick.click, s.preventclick = !1, s.cancelEvent(a)) : void 0
                    },
                    s.bind(s.win, "mousedown", s.ontouchstart), s.onclick = w.isios ? !1 : function(a) {
                        return s.lastmouseup ? (s.lastmouseup = !1, s.cancelEvent(a)) : !0
                    },
                    s.opt.grabcursorenabled && w.cursorgrabvalue && (s.css(s.ispage ? s.doc: s.win, {
                        cursor: w.cursorgrabvalue
                    }), s.css(s.rail, {
                        cursor: w.cursorgrabvalue
                    }))) : (s.hasTextSelected = "getSelection" in document ?
                    function() {
                        return document.getSelection().rangeCount > 0
                    }: "selection" in document ?
                    function() {
                        return "None" != document.selection.type
                    }: function() {
                        return ! 1
                    },
                    s.onselectionstart = function() {
                        s.ispage || (s.selectiondrag = s.win.offset())
                    },
                    s.onselectionend = function() {
                        s.selectiondrag = !1
                    },
                    s.onselectiondrag = function(b) {
                        s.selectiondrag && s.hasTextSelected() && s.debounced("selectionscroll",
                        function() {
                            a(b)
                        },
                        250)
                    }),
                    w.hasmstouch && (s.css(s.rail, {
                        "-ms-touch-action": "none"
                    }), s.css(s.cursor, {
                        "-ms-touch-action": "none"
                    }), s.bind(s.win, "MSPointerDown", s.ontouchstart), s.bind(document, "MSPointerUp", s.ontouchend), s.bind(document, "MSPointerMove", s.ontouchmove), s.bind(s.cursor, "MSGestureHold",
                    function(a) {
                        a.preventDefault()
                    }), s.bind(s.cursor, "contextmenu",
                    function(a) {
                        a.preventDefault()
                    })),
                    this.istouchcapable && (s.bind(s.win, "touchstart", s.ontouchstart), s.bind(document, "touchend", s.ontouchend), s.bind(document, "touchcancel", s.ontouchend), s.bind(document, "touchmove", s.ontouchmove)),
                    s.bind(s.cursor, "mousedown", s.onmousedown),
                    s.bind(s.cursor, "mouseup", s.onmouseup),
                    s.railh && (s.bind(s.cursorh, "mousedown",
                    function(a) {
                        s.onmousedown(a, !0)
                    }), s.bind(s.cursorh, "mouseup",
                    function(a) {
                        return s.rail.drag && 2 == s.rail.drag.pt ? void 0 : (s.rail.drag = !1, s.hasmoving = !1, s.hideCursor(), w.hasmousecapture && document.releaseCapture(), s.cancelEvent(a))
                    })),
                    (s.opt.cursordragontouch || !w.cantouch && !s.opt.touchbehavior) && (s.rail.css({
                        cursor: "default"
                    }), s.railh && s.railh.css({
                        cursor: "default"
                    }), s.jqbind(s.rail, "mouseenter",
                    function() {
                        s.canshowonmouseevent && s.showCursor(),
                        s.rail.active = !0
                    }), s.jqbind(s.rail, "mouseleave",
                    function() {
                        s.rail.active = !1,
                        s.rail.drag || s.hideCursor()
                    }), s.opt.sensitiverail && (s.bind(s.rail, "click",
                    function(a) {
                        s.doRailClick(a, !1, !1)
                    }), s.bind(s.rail, "dblclick",
                    function(a) {
                        s.doRailClick(a, !0, !1)
                    }), s.bind(s.cursor, "click",
                    function(a) {
                        s.cancelEvent(a)
                    }), s.bind(s.cursor, "dblclick",
                    function(a) {
                        s.cancelEvent(a)
                    })), s.railh && (s.jqbind(s.railh, "mouseenter",
                    function() {
                        s.canshowonmouseevent && s.showCursor(),
                        s.rail.active = !0
                    }), s.jqbind(s.railh, "mouseleave",
                    function() {
                        s.rail.active = !1,
                        s.rail.drag || s.hideCursor()
                    }), s.opt.sensitiverail && (s.bind(s.railh, "click",
                    function(a) {
                        s.doRailClick(a, !1, !0)
                    }), s.bind(s.railh, "dblclick",
                    function(a) {
                        s.doRailClick(a, !0, !0)
                    }), s.bind(s.cursorh, "click",
                    function(a) {
                        s.cancelEvent(a)
                    }), s.bind(s.cursorh, "dblclick",
                    function(a) {
                        s.cancelEvent(a)
                    })))),
                    w.cantouch || s.opt.touchbehavior ? (s.bind(w.hasmousecapture ? s.win: document, "mouseup", s.ontouchend), s.bind(document, "mousemove", s.ontouchmove), s.onclick && s.bind(document, "click", s.onclick), s.opt.cursordragontouch && (s.bind(s.cursor, "mousedown", s.onmousedown), s.bind(s.cursor, "mousemove", s.onmousemove), s.cursorh && s.bind(s.cursorh, "mousedown",
                    function(a) {
                        s.onmousedown(a, !0)
                    }), s.cursorh && s.bind(s.cursorh, "mousemove", s.onmousemove))) : (s.bind(w.hasmousecapture ? s.win: document, "mouseup", s.onmouseup), s.bind(document, "mousemove", s.onmousemove), s.onclick && s.bind(document, "click", s.onclick), !s.ispage && s.opt.enablescrollonselection && (s.bind(s.win[0], "mousedown", s.onselectionstart), s.bind(document, "mouseup", s.onselectionend), s.bind(s.cursor, "mouseup", s.onselectionend), s.cursorh && s.bind(s.cursorh, "mouseup", s.onselectionend), s.bind(document, "mousemove", s.onselectiondrag)), s.zoom && (s.jqbind(s.zoom, "mouseenter",
                    function() {
                        s.canshowonmouseevent && s.showCursor(),
                        s.rail.active = !0
                    }), s.jqbind(s.zoom, "mouseleave",
                    function() {
                        s.rail.active = !1,
                        s.rail.drag || s.hideCursor()
                    }))),
                    s.opt.enablemousewheel && (s.isiframe || s.bind(w.isie && s.ispage ? document: s.win, "mousewheel", s.onmousewheel), s.bind(s.rail, "mousewheel", s.onmousewheel), s.railh && s.bind(s.railh, "mousewheel", s.onmousewheelhr)),
                    s.ispage || w.cantouch || /HTML|BODY/.test(s.win[0].nodeName) || (s.win.attr("tabindex") || s.win.attr({
                        tabindex: g++
                    }), s.jqbind(s.win, "focus",
                    function(a) {
                        e = s.getTarget(a).id || !0,
                        s.hasfocus = !0,
                        s.canshowonmouseevent && s.noticeCursor()
                    }), s.jqbind(s.win, "blur",
                    function() {
                        e = !1,
                        s.hasfocus = !1
                    }), s.jqbind(s.win, "mouseenter",
                    function(a) {
                        f = s.getTarget(a).id || !0,
                        s.hasmousefocus = !0,
                        s.canshowonmouseevent && s.noticeCursor()
                    }), s.jqbind(s.win, "mouseleave",
                    function() {
                        f = !1,
                        s.hasmousefocus = !1,
                        s.rail.drag || s.hideCursor()
                    }))
                }
                if (s.onkeypress = function(a) {
                    if (s.locked && 0 == s.page.maxh) return ! 0;
                    a = a ? a: window.e;
                    var b = s.getTarget(a);
                    if (b && /INPUT|TEXTAREA|SELECT|OPTION/.test(b.nodeName)) {
                        var c = b.getAttribute("type") || b.type || !1;
                        if (!c || !/submit|button|cancel/i.tp) return ! 0
                    }
                    if (s.hasfocus || s.hasmousefocus && !e || s.ispage && !e && !f) {
                        var d = a.keyCode;
                        if (s.locked && 27 != d) return s.cancelEvent(a);
                        var g = a.ctrlKey || !1,
                        h = a.shiftKey || !1,
                        i = !1;
                        switch (d) {
                        case 38:
                        case 63233:
                            s.doScrollBy(72),
                            i = !0;
                            break;
                        case 40:
                        case 63235:
                            s.doScrollBy( - 72),
                            i = !0;
                            break;
                        case 37:
                        case 63232:
                            s.railh && (g ? s.doScrollLeft(0) : s.doScrollLeftBy(72), i = !0);
                            break;
                        case 39:
                        case 63234:
                            s.railh && (g ? s.doScrollLeft(s.page.maxw) : s.doScrollLeftBy( - 72), i = !0);
                            break;
                        case 33:
                        case 63276:
                            s.doScrollBy(s.view.h),
                            i = !0;
                            break;
                        case 34:
                        case 63277:
                            s.doScrollBy( - s.view.h),
                            i = !0;
                            break;
                        case 36:
                        case 63273:
                            s.railh && g ? s.doScrollPos(0, 0) : s.doScrollTo(0),
                            i = !0;
                            break;
                        case 35:
                        case 63275:
                            s.railh && g ? s.doScrollPos(s.page.maxw, s.page.maxh) : s.doScrollTo(s.page.maxh),
                            i = !0;
                            break;
                        case 32:
                            s.opt.spacebarenabled && (h ? s.doScrollBy(s.view.h) : s.doScrollBy( - s.view.h), i = !0);
                            break;
                        case 27:
                            s.zoomactive && (s.doZoom(), i = !0)
                        }
                        if (i) return s.cancelEvent(a)
                    }
                },
                s.opt.enablekeyboard && s.bind(document, w.isopera && !w.isopera12 ? "keypress": "keydown", s.onkeypress), s.bind(window, "resize", s.lazyResize), s.bind(window, "orientationchange", s.lazyResize), s.bind(window, "load", s.lazyResize), w.ischrome && !s.ispage && !s.haswrapper) {
                    var B = s.win.attr("style"),
                    C = parseFloat(s.win.css("width")) + 1;
                    s.win.css("width", C),
                    s.synched("chromefix",
                    function() {
                        s.win.attr("style", B)
                    })
                }
                s.onAttributeChange = function() {
                    s.lazyResize(250)
                },
                s.ispage || s.haswrapper || (q !== !1 ? (s.observer = new q(function(a) {
                    a.forEach(s.onAttributeChange)
                }), s.observer.observe(s.win[0], {
                    childList: !0,
                    characterData: !1,
                    attributes: !0,
                    subtree: !1
                }), s.observerremover = new q(function(a) {
                    a.forEach(function(a) {
                        if (a.removedNodes.length > 0) for (var b in a.removedNodes) if (a.removedNodes[b] == s.win[0]) return s.remove()
                    })
                }), s.observerremover.observe(s.win[0].parentNode, {
                    childList: !0,
                    characterData: !1,
                    attributes: !1,
                    subtree: !1
                })) : (s.bind(s.win, w.isie && !w.isie9 ? "propertychange": "DOMAttrModified", s.onAttributeChange), w.isie9 && s.win[0].attachEvent("onpropertychange", s.onAttributeChange), s.bind(s.win, "DOMNodeRemoved",
                function(a) {
                    a.target == s.win[0] && s.remove()
                }))),
                !s.ispage && s.opt.boxzoom && s.bind(window, "resize", s.resizeZoom),
                s.istextarea && s.bind(s.win, "mouseup", s.lazyResize),
                s.checkrtlmode = !0,
                s.lazyResize(30)
            }
            "IFRAME" == this.doc[0].nodeName && (this.doc[0].readyState && "complete" == this.doc[0].readyState && setTimeout(function() {
                b.call(s.doc[0], !1)
            },
            500), s.bind(this.doc, "load", b))
        },
        this.showCursor = function(a, b) {
            s.cursortimeout && (clearTimeout(s.cursortimeout), s.cursortimeout = 0),
            s.rail && (s.autohidedom && (s.autohidedom.stop().css({
                opacity: s.opt.cursoropacitymax
            }), s.cursoractive = !0), s.rail.drag && 1 == s.rail.drag.pt || ("undefined" != typeof a && a !== !1 && (s.scroll.y = Math.round(1 * a / s.scrollratio.y)), "undefined" != typeof b && (s.scroll.x = Math.round(1 * b / s.scrollratio.x))), s.cursor.css({
                height: s.cursorheight,
                top: s.scroll.y
            }), s.cursorh && (!s.rail.align && s.rail.visibility ? s.cursorh.css({
                width: s.cursorwidth,
                left: s.scroll.x + s.rail.width
            }) : s.cursorh.css({
                width: s.cursorwidth,
                left: s.scroll.x
            }), s.cursoractive = !0), s.zoom && s.zoom.stop().css({
                opacity: s.opt.cursoropacitymax
            }))
        },
        this.hideCursor = function(a) {
            s.cursortimeout || s.rail && s.autohidedom && (s.hasmousefocus && "leave" == s.opt.autohidemode || (s.cursortimeout = setTimeout(function() {
                s.rail.active && s.showonmouseevent || (s.autohidedom.stop().animate({
                    opacity: s.opt.cursoropacitymin
                }), s.zoom && s.zoom.stop().animate({
                    opacity: s.opt.cursoropacitymin
                }), s.cursoractive = !1),
                s.cursortimeout = 0
            },
            a || s.opt.hidecursordelay)))
        },
        this.noticeCursor = function(a, b, c) {
            s.showCursor(b, c),
            s.rail.active || s.hideCursor(a)
        },
        this.getContentSize = s.ispage ?
        function() {
            return {
                w: Math.max(document.body.scrollWidth, document.documentElement.scrollWidth),
                h: Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
            }
        }: s.haswrapper ?
        function() {
            return {
                w: s.doc.outerWidth() + parseInt(s.win.css("paddingLeft")) + parseInt(s.win.css("paddingRight")),
                h: s.doc.outerHeight() + parseInt(s.win.css("paddingTop")) + parseInt(s.win.css("paddingBottom"))
            }
        }: function() {
            return {
                w: s.docscroll[0].scrollWidth,
                h: s.docscroll[0].scrollHeight
            }
        },
        this.onResize = function(a, b) {
            if (!s || !s.win) return ! 1;
            if (!s.haswrapper && !s.ispage) {
                if ("none" == s.win.css("display")) return s.visibility && s.hideRail().hideRailHr(),
                !1;
                s.hidden || s.visibility || s.showRail().showRailHr()
            }
            var c = s.page.maxh,
            d = s.page.maxw,
            e = {
                h: s.view.h,
                w: s.view.w
            };
            if (s.view = {
                w: s.ispage ? s.win.width() : parseInt(s.win[0].clientWidth),
                h: s.ispage ? s.win.height() : parseInt(s.win[0].clientHeight)
            },
            s.page = b ? b: s.getContentSize(), s.page.maxh = Math.max(0, s.page.h - s.view.h), s.page.maxw = Math.max(0, s.page.w - s.view.w), s.page.maxh == c && s.page.maxw == d && s.view.w == e.w) {
                if (s.ispage) return s;
                var f = s.win.offset();
                if (s.lastposition) {
                    var g = s.lastposition;
                    if (g.top == f.top && g.left == f.left) return s
                }
                s.lastposition = f
            }
            if (0 == s.page.maxh ? (s.hideRail(), s.scrollvaluemax = 0, s.scroll.y = 0, s.scrollratio.y = 0, s.cursorheight = 0, s.setScrollTop(0), s.rail.scrollable = !1) : s.rail.scrollable = !0, 0 == s.page.maxw ? (s.hideRailHr(), s.scrollvaluemaxw = 0, s.scroll.x = 0, s.scrollratio.x = 0, s.cursorwidth = 0, s.setScrollLeft(0), s.railh.scrollable = !1) : s.railh.scrollable = !0, s.locked = 0 == s.page.maxh && 0 == s.page.maxw, s.locked) return s.ispage || s.updateScrollBar(s.view),
            !1;
            s.hidden || s.visibility ? s.hidden || s.railh.visibility || s.showRailHr() : s.showRail().showRailHr(),
            s.istextarea && s.win.css("resize") && "none" != s.win.css("resize") && (s.view.h -= 20),
            s.cursorheight = Math.min(s.view.h, Math.round(s.view.h * (s.view.h / s.page.h))),
            s.cursorheight = s.opt.cursorfixedheight ? s.opt.cursorfixedheight: Math.max(s.opt.cursorminheight, s.cursorheight),
            s.cursorwidth = Math.min(s.view.w, Math.round(s.view.w * (s.view.w / s.page.w))),
            s.cursorwidth = s.opt.cursorfixedheight ? s.opt.cursorfixedheight: Math.max(s.opt.cursorminheight, s.cursorwidth),
            s.scrollvaluemax = s.view.h - s.cursorheight - s.cursor.hborder,
            s.railh && (s.railh.width = s.page.maxh > 0 ? s.view.w - s.rail.width: s.view.w, s.scrollvaluemaxw = s.railh.width - s.cursorwidth - s.cursorh.wborder),
            s.checkrtlmode && s.railh && (s.checkrtlmode = !1, s.opt.rtlmode && 0 == s.scroll.x && s.setScrollLeft(s.page.maxw)),
            s.ispage || s.updateScrollBar(s.view),
            s.scrollratio = {
                x: s.page.maxw / s.scrollvaluemaxw,
                y: s.page.maxh / s.scrollvaluemax
            };
            var h = s.getScrollTop();
            return h > s.page.maxh ? s.doScrollTop(s.page.maxh) : (s.scroll.y = Math.round(s.getScrollTop() * (1 / s.scrollratio.y)), s.scroll.x = Math.round(s.getScrollLeft() * (1 / s.scrollratio.x)), s.cursoractive && s.noticeCursor()),
            s.scroll.y && 0 == s.getScrollTop() && s.doScrollTo(Math.floor(s.scroll.y * s.scrollratio.y)),
            s
        },
        this.resize = s.onResize,
        this.lazyResize = function(a) {
            return a = isNaN(a) ? 30 : a,
            s.delayed("resize", s.resize, a),
            s
        },
        this._bind = function(a, b, c, d) {
            s.events.push({
                e: a,
                n: b,
                f: c,
                b: d,
                q: !1
            }),
            a.addEventListener ? a.addEventListener(b, c, d || !1) : a.attachEvent ? a.attachEvent("on" + b, c) : a["on" + b] = c
        },
        this.jqbind = function(a, b, c) {
            s.events.push({
                e: a,
                n: b,
                f: c,
                q: !0
            }),
            j(a).bind(b, c)
        },
        this.bind = function(a, b, c, d) {
            var e = "jquery" in a ? a[0] : a;
            if ("mousewheel" == b) if ("onwheel" in s.win) s._bind(e, "wheel", c, d || !1);
            else {
                var f = "undefined" != typeof document.onmousewheel ? "mousewheel": "DOMMouseScroll";
                o(e, f, c, d || !1),
                "DOMMouseScroll" == f && o(e, "MozMousePixelScroll", c, d || !1)
            } else if (e.addEventListener) {
                if (w.cantouch && /mouseup|mousedown|mousemove/.test(b)) {
                    var g = "mousedown" == b ? "touchstart": "mouseup" == b ? "touchend": "touchmove";
                    s._bind(e, g,
                    function(a) {
                        if (a.touches) {
                            if (a.touches.length < 2) {
                                var b = a.touches.length ? a.touches[0] : a;
                                b.original = a,
                                c.call(this, b)
                            }
                        } else if (a.changedTouches) {
                            var b = a.changedTouches[0];
                            b.original = a,
                            c.call(this, b)
                        }
                    },
                    d || !1)
                }
                s._bind(e, b, c, d || !1),
                w.cantouch && "mouseup" == b && s._bind(e, "touchcancel", c, d || !1)
            } else s._bind(e, b,
            function(a) {
                return a = a || window.event || !1,
                a && a.srcElement && (a.target = a.srcElement),
                "pageY" in a || (a.pageX = a.clientX + document.documentElement.scrollLeft, a.pageY = a.clientY + document.documentElement.scrollTop),
                c.call(e, a) === !1 || d === !1 ? s.cancelEvent(a) : !0
            })
        },
        this._unbind = function(a, b, c, d) {
            a.removeEventListener ? a.removeEventListener(b, c, d) : a.detachEvent ? a.detachEvent("on" + b, c) : a["on" + b] = !1
        },
        this.unbindAll = function() {
            for (var a = 0; a < s.events.length; a++) {
                var b = s.events[a];
                b.q ? b.e.unbind(b.n, b.f) : s._unbind(b.e, b.n, b.f, b.b)
            }
        },
        this.cancelEvent = function(a) {
            var a = a.original ? a.original: a ? a: window.event || !1;
            return a ? (a.preventDefault && a.preventDefault(), a.stopPropagation && a.stopPropagation(), a.preventManipulation && a.preventManipulation(), a.cancelBubble = !0, a.cancel = !0, a.returnValue = !1, !1) : !1
        },
        this.stopPropagation = function(a) {
            var a = a.original ? a.original: a ? a: window.event || !1;
            return a ? a.stopPropagation ? a.stopPropagation() : (a.cancelBubble && (a.cancelBubble = !0), !1) : !1
        },
        this.showRail = function() {
            return 0 == s.page.maxh || !s.ispage && "none" == s.win.css("display") || (s.visibility = !0, s.rail.visibility = !0, s.rail.css("display", "block")),
            s
        },
        this.showRailHr = function() {
            return s.railh ? (0 == s.page.maxw || !s.ispage && "none" == s.win.css("display") || (s.railh.visibility = !0, s.railh.css("display", "block")), s) : s
        },
        this.hideRail = function() {
            return s.visibility = !1,
            s.rail.visibility = !1,
            s.rail.css("display", "none"),
            s
        },
        this.hideRailHr = function() {
            return s.railh ? (s.railh.visibility = !1, s.railh.css("display", "none"), s) : s
        },
        this.show = function() {
            return s.hidden = !1,
            s.locked = !1,
            s.showRail().showRailHr()
        },
        this.hide = function() {
            return s.hidden = !0,
            s.locked = !0,
            s.hideRail().hideRailHr()
        },
        this.toggle = function() {
            return s.hidden ? s.show() : s.hide()
        },
        this.remove = function() {
            s.stop(),
            s.cursortimeout && clearTimeout(s.cursortimeout),
            s.doZoomOut(),
            s.unbindAll(),
            w.isie9 && s.win[0].detachEvent("onpropertychange", s.onAttributeChange),
            s.observer !== !1 && s.observer.disconnect(),
            s.observerremover !== !1 && s.observerremover.disconnect(),
            s.events = null,
            s.cursor && s.cursor.remove(),
            s.cursorh && s.cursorh.remove(),
            s.rail && s.rail.remove(),
            s.railh && s.railh.remove(),
            s.zoom && s.zoom.remove();
            for (var a = 0; a < s.saved.css.length; a++) {
                var b = s.saved.css[a];
                b[0].css(b[1], "undefined" == typeof b[2] ? "": b[2])
            }
            s.saved = !1,
            s.me.data("__nicescroll", "");
            var c = j.nicescroll;
            c.each(function(a) {
                if (this && this.id === s.id) {
                    delete c[a];
                    for (var b = ++a; b < c.length; b++, a++) c[a] = c[b];
                    c.length--,
                    c.length && delete c[c.length]
                }
            });
            for (var d in s) s[d] = null,
            delete s[d];
            s = null
        },
        this.scrollstart = function(a) {
            return this.onscrollstart = a,
            s
        },
        this.scrollend = function(a) {
            return this.onscrollend = a,
            s
        },
        this.scrollcancel = function(a) {
            return this.onscrollcancel = a,
            s
        },
        this.zoomin = function(a) {
            return this.onzoomin = a,
            s
        },
        this.zoomout = function(a) {
            return this.onzoomout = a,
            s
        },
        this.isScrollable = function(a) {
            var b = a.target ? a.target: a;
            if ("OPTION" == b.nodeName) return ! 0;
            for (; b && 1 == b.nodeType && !/BODY|HTML/.test(b.nodeName);) {
                var c = j(b),
                d = c.css("overflowY") || c.css("overflowX") || c.css("overflow") || "";
                if (/scroll|auto/.test(d)) return b.clientHeight != b.scrollHeight;
                b = b.parentNode ? b.parentNode: !1
            }
            return ! 1
        },
        this.getViewport = function(a) {
            for (var b = a && a.parentNode ? a.parentNode: !1; b && 1 == b.nodeType && !/BODY|HTML/.test(b.nodeName);) {
                var c = j(b);
                if (/fixed|absolute/.test(c.css("position"))) return c;
                var d = c.css("overflowY") || c.css("overflowX") || c.css("overflow") || "";
                if (/scroll|auto/.test(d) && b.clientHeight != b.scrollHeight) return c;
                if (c.getNiceScroll().length > 0) return c;
                b = b.parentNode ? b.parentNode: !1
            }
            return ! 1
        },
        this.onmousewheel = function(a) {
            if (s.locked) return s.debounced("checkunlock", s.resize, 250),
            !0;
            if (s.rail.drag) return s.cancelEvent(a);
            if ("auto" == s.opt.oneaxismousemode && 0 != a.deltaX && (s.opt.oneaxismousemode = !1), s.opt.oneaxismousemode && 0 == a.deltaX && !s.rail.scrollable) return s.railh && s.railh.scrollable ? s.onmousewheelhr(a) : !0;
            var b = +new Date,
            c = !1;
            if (s.opt.preservenativescrolling && s.checkarea + 600 < b && (s.nativescrollingarea = s.isScrollable(a), c = !0), s.checkarea = b, s.nativescrollingarea) return ! 0;
            var d = p(a, !1, c);
            return d && (s.checkarea = 0),
            d
        },
        this.onmousewheelhr = function(a) {
            if (s.locked || !s.railh.scrollable) return ! 0;
            if (s.rail.drag) return s.cancelEvent(a);
            var b = +new Date,
            c = !1;
            return s.opt.preservenativescrolling && s.checkarea + 600 < b && (s.nativescrollingarea = s.isScrollable(a), c = !0),
            s.checkarea = b,
            s.nativescrollingarea ? !0 : s.locked ? s.cancelEvent(a) : p(a, !0, c)
        },
        this.stop = function() {
            return s.cancelScroll(),
            s.scrollmon && s.scrollmon.stop(),
            s.cursorfreezed = !1,
            s.scroll.y = Math.round(s.getScrollTop() * (1 / s.scrollratio.y)),
            s.noticeCursor(),
            s
        },
        this.getTransitionSpeed = function(a) {
            var b = Math.round(10 * s.opt.scrollspeed),
            c = Math.min(b, Math.round(a / 20 * s.opt.scrollspeed));
            return c > 20 ? c: 0
        },
        s.opt.smoothscroll ? s.ishwscroll && w.hastransition && s.opt.usetransition ? (this.prepareTransition = function(a, b) {
            var c = b ? a > 20 ? a: 0 : s.getTransitionSpeed(a),
            d = c ? w.prefixstyle + "transform " + c + "ms ease-out": "";
            return s.lasttransitionstyle && s.lasttransitionstyle == d || (s.lasttransitionstyle = d, s.doc.css(w.transitionstyle, d)),
            c
        },
        this.doScrollLeft = function(a, b) {
            var c = s.scrollrunning ? s.newscrolly: s.getScrollTop();
            s.doScrollPos(a, c, b)
        },
        this.doScrollTop = function(a, b) {
            var c = s.scrollrunning ? s.newscrollx: s.getScrollLeft();
            s.doScrollPos(c, a, b)
        },
        this.doScrollPos = function(a, b, c) {
            var d = s.getScrollTop(),
            e = s.getScrollLeft();
            return ((s.newscrolly - d) * (b - d) < 0 || (s.newscrollx - e) * (a - e) < 0) && s.cancelScroll(),
            0 == s.opt.bouncescroll && (0 > b ? b = 0 : b > s.page.maxh && (b = s.page.maxh), 0 > a ? a = 0 : a > s.page.maxw && (a = s.page.maxw)),
            s.scrollrunning && a == s.newscrollx && b == s.newscrolly ? !1 : (s.newscrolly = b, s.newscrollx = a, s.newscrollspeed = c || !1, s.timer ? !1 : (s.timer = setTimeout(function() {
                var c = s.getScrollTop(),
                d = s.getScrollLeft(),
                e = {};
                e.x = a - d,
                e.y = b - c,
                e.px = d,
                e.py = c;
                var f = Math.round(Math.sqrt(Math.pow(e.x, 2) + Math.pow(e.y, 2))),
                g = s.newscrollspeed && s.newscrollspeed > 1 ? s.newscrollspeed: s.getTransitionSpeed(f);
                if (s.newscrollspeed && s.newscrollspeed <= 1 && (g *= s.newscrollspeed), s.prepareTransition(g, !0), s.timerscroll && s.timerscroll.tm && clearInterval(s.timerscroll.tm), g > 0) {
                    if (!s.scrollrunning && s.onscrollstart) {
                        var h = {
                            type: "scrollstart",
                            current: {
                                x: d,
                                y: c
                            },
                            request: {
                                x: a,
                                y: b
                            },
                            end: {
                                x: s.newscrollx,
                                y: s.newscrolly
                            },
                            speed: g
                        };
                        s.onscrollstart.call(s, h)
                    }
                    w.transitionend ? s.scrollendtrapped || (s.scrollendtrapped = !0, s.bind(s.doc, w.transitionend, s.onScrollEnd, !1)) : (s.scrollendtrapped && clearTimeout(s.scrollendtrapped), s.scrollendtrapped = setTimeout(s.onScrollEnd, g));
                    var i = c,
                    j = d;
                    s.timerscroll = {
                        bz: new BezierClass(i, s.newscrolly, g, 0, 0, .58, 1),
                        bh: new BezierClass(j, s.newscrollx, g, 0, 0, .58, 1)
                    },
                    s.cursorfreezed || (s.timerscroll.tm = setInterval(function() {
                        s.showCursor(s.getScrollTop(), s.getScrollLeft())
                    },
                    60))
                }
                s.synched("doScroll-set",
                function() {
                    s.timer = 0,
                    s.scrollendtrapped && (s.scrollrunning = !0),
                    s.setScrollTop(s.newscrolly),
                    s.setScrollLeft(s.newscrollx),
                    s.scrollendtrapped || s.onScrollEnd()
                })
            },
            50), void 0))
        },
        this.cancelScroll = function() {
            if (!s.scrollendtrapped) return ! 0;
            var a = s.getScrollTop(),
            b = s.getScrollLeft();
            return s.scrollrunning = !1,
            w.transitionend || clearTimeout(w.transitionend),
            s.scrollendtrapped = !1,
            s._unbind(s.doc, w.transitionend, s.onScrollEnd),
            s.prepareTransition(0),
            s.setScrollTop(a),
            s.railh && s.setScrollLeft(b),
            s.timerscroll && s.timerscroll.tm && clearInterval(s.timerscroll.tm),
            s.timerscroll = !1,
            s.cursorfreezed = !1,
            s.showCursor(a, b),
            s
        },
        this.onScrollEnd = function() {
            s.scrollendtrapped && s._unbind(s.doc, w.transitionend, s.onScrollEnd),
            s.scrollendtrapped = !1,
            s.prepareTransition(0),
            s.timerscroll && s.timerscroll.tm && clearInterval(s.timerscroll.tm),
            s.timerscroll = !1;
            var a = s.getScrollTop(),
            b = s.getScrollLeft();
            if (s.setScrollTop(a), s.railh && s.setScrollLeft(b), s.noticeCursor(!1, a, b), s.cursorfreezed = !1, 0 > a ? a = 0 : a > s.page.maxh && (a = s.page.maxh), 0 > b ? b = 0 : b > s.page.maxw && (b = s.page.maxw), a != s.newscrolly || b != s.newscrollx) return s.doScrollPos(b, a, s.opt.snapbackspeed);
            if (s.onscrollend && s.scrollrunning) {
                var c = {
                    type: "scrollend",
                    current: {
                        x: b,
                        y: a
                    },
                    end: {
                        x: s.newscrollx,
                        y: s.newscrolly
                    }
                };
                s.onscrollend.call(s, c)
            }
            s.scrollrunning = !1
        }) : (this.doScrollLeft = function(a, b) {
            var c = s.scrollrunning ? s.newscrolly: s.getScrollTop();
            s.doScrollPos(a, c, b)
        },
        this.doScrollTop = function(a, b) {
            var c = s.scrollrunning ? s.newscrollx: s.getScrollLeft();
            s.doScrollPos(c, a, b)
        },
        this.doScrollPos = function(a, b, c) {
            function d() {
                if (s.cancelAnimationFrame) return ! 0;
                if (s.scrollrunning = !0, k = 1 - k) return s.timer = m(d) || 1;
                var a = 0,
                b = sy = s.getScrollTop();
                if (s.dst.ay) {
                    b = s.bzscroll ? s.dst.py + s.bzscroll.getNow() * s.dst.ay: s.newscrolly;
                    var c = b - sy; (0 > c && b < s.newscrolly || c > 0 && b > s.newscrolly) && (b = s.newscrolly),
                    s.setScrollTop(b),
                    b == s.newscrolly && (a = 1)
                } else a = 1;
                var e = sx = s.getScrollLeft();
                if (s.dst.ax) {
                    e = s.bzscroll ? s.dst.px + s.bzscroll.getNow() * s.dst.ax: s.newscrollx;
                    var c = e - sx; (0 > c && e < s.newscrollx || c > 0 && e > s.newscrollx) && (e = s.newscrollx),
                    s.setScrollLeft(e),
                    e == s.newscrollx && (a += 1)
                } else a += 1;
                if (2 == a) {
                    if (s.timer = 0, s.cursorfreezed = !1, s.bzscroll = !1, s.scrollrunning = !1, 0 > b ? b = 0 : b > s.page.maxh && (b = s.page.maxh), 0 > e ? e = 0 : e > s.page.maxw && (e = s.page.maxw), e != s.newscrollx || b != s.newscrolly) s.doScrollPos(e, b);
                    else if (s.onscrollend) {
                        var f = {
                            type: "scrollend",
                            current: {
                                x: sx,
                                y: sy
                            },
                            end: {
                                x: s.newscrollx,
                                y: s.newscrolly
                            }
                        };
                        s.onscrollend.call(s, f)
                    }
                } else s.timer = m(d) || 1
            }
            var b = "undefined" == typeof b || b === !1 ? s.getScrollTop(!0) : b;
            if (s.timer && s.newscrolly == b && s.newscrollx == a) return ! 0;
            s.timer && n(s.timer),
            s.timer = 0;
            var e = s.getScrollTop(),
            f = s.getScrollLeft(); ((s.newscrolly - e) * (b - e) < 0 || (s.newscrollx - f) * (a - f) < 0) && s.cancelScroll(),
            s.newscrolly = b,
            s.newscrollx = a,
            s.bouncescroll && s.rail.visibility || (s.newscrolly < 0 ? s.newscrolly = 0 : s.newscrolly > s.page.maxh && (s.newscrolly = s.page.maxh)),
            s.bouncescroll && s.railh.visibility || (s.newscrollx < 0 ? s.newscrollx = 0 : s.newscrollx > s.page.maxw && (s.newscrollx = s.page.maxw)),
            s.dst = {},
            s.dst.x = a - f,
            s.dst.y = b - e,
            s.dst.px = f,
            s.dst.py = e;
            var g = Math.round(Math.sqrt(Math.pow(s.dst.x, 2) + Math.pow(s.dst.y, 2)));
            s.dst.ax = s.dst.x / g,
            s.dst.ay = s.dst.y / g;
            var h = 0,
            i = g;
            0 == s.dst.x ? (h = e, i = b, s.dst.ay = 1, s.dst.py = 0) : 0 == s.dst.y && (h = f, i = a, s.dst.ax = 1, s.dst.px = 0);
            var j = s.getTransitionSpeed(g);
            if (c && 1 >= c && (j *= c), s.bzscroll = j > 0 ? s.bzscroll ? s.bzscroll.update(i, j) : new BezierClass(h, i, j, 0, 1, 0, 1) : !1, !s.timer) { (e == s.page.maxh && b >= s.page.maxh || f == s.page.maxw && a >= s.page.maxw) && s.checkContentSize();
                var k = 1;
                if (s.cancelAnimationFrame = !1, s.timer = 1, s.onscrollstart && !s.scrollrunning) {
                    var l = {
                        type: "scrollstart",
                        current: {
                            x: f,
                            y: e
                        },
                        request: {
                            x: a,
                            y: b
                        },
                        end: {
                            x: s.newscrollx,
                            y: s.newscrolly
                        },
                        speed: j
                    };
                    s.onscrollstart.call(s, l)
                }
                d(),
                (e == s.page.maxh && b >= e || f == s.page.maxw && a >= f) && s.checkContentSize(),
                s.noticeCursor()
            }
        },
        this.cancelScroll = function() {
            return s.timer && n(s.timer),
            s.timer = 0,
            s.bzscroll = !1,
            s.scrollrunning = !1,
            s
        }) : (this.doScrollLeft = function(a, b) {
            var c = s.getScrollTop();
            s.doScrollPos(a, c, b)
        },
        this.doScrollTop = function(a, b) {
            var c = s.getScrollLeft();
            s.doScrollPos(c, a, b)
        },
        this.doScrollPos = function(a, b) {
            var c = a > s.page.maxw ? s.page.maxw: a;
            0 > c && (c = 0);
            var d = b > s.page.maxh ? s.page.maxh: b;
            0 > d && (d = 0),
            s.synched("scroll",
            function() {
                s.setScrollTop(d),
                s.setScrollLeft(c)
            })
        },
        this.cancelScroll = function() {}),
        this.doScrollBy = function(a, b) {
            var c = 0;
            if (b) c = Math.floor((s.scroll.y - a) * s.scrollratio.y);
            else {
                var d = s.timer ? s.newscrolly: s.getScrollTop(!0);
                c = d - a
            }
            if (s.bouncescroll) {
                var e = Math.round(s.view.h / 2); - e > c ? c = -e: c > s.page.maxh + e && (c = s.page.maxh + e)
            }
            return s.cursorfreezed = !1,
            py = s.getScrollTop(!0),
            0 > c && 0 >= py ? s.noticeCursor() : c > s.page.maxh && py >= s.page.maxh ? (s.checkContentSize(), s.noticeCursor()) : (s.doScrollTop(c), void 0)
        },
        this.doScrollLeftBy = function(a, b) {
            var c = 0;
            if (b) c = Math.floor((s.scroll.x - a) * s.scrollratio.x);
            else {
                var d = s.timer ? s.newscrollx: s.getScrollLeft(!0);
                c = d - a
            }
            if (s.bouncescroll) {
                var e = Math.round(s.view.w / 2); - e > c ? c = -e: c > s.page.maxw + e && (c = s.page.maxw + e)
            }
            return s.cursorfreezed = !1,
            px = s.getScrollLeft(!0),
            0 > c && 0 >= px ? s.noticeCursor() : c > s.page.maxw && px >= s.page.maxw ? s.noticeCursor() : (s.doScrollLeft(c), void 0)
        },
        this.doScrollTo = function(a, b) {
            var c = b ? Math.round(a * s.scrollratio.y) : a;
            0 > c ? c = 0 : c > s.page.maxh && (c = s.page.maxh),
            s.cursorfreezed = !1,
            s.doScrollTop(a)
        },
        this.checkContentSize = function() {
            var a = s.getContentSize(); (a.h != s.page.h || a.w != s.page.w) && s.resize(!1, a)
        },
        s.onscroll = function() {
            s.rail.drag || s.cursorfreezed || s.synched("scroll",
            function() {
                s.scroll.y = Math.round(s.getScrollTop() * (1 / s.scrollratio.y)),
                s.railh && (s.scroll.x = Math.round(s.getScrollLeft() * (1 / s.scrollratio.x))),
                s.noticeCursor()
            })
        },
        s.bind(s.docscroll, "scroll", s.onscroll),
        this.doZoomIn = function(a) {
            if (!s.zoomactive) {
                s.zoomactive = !0,
                s.zoomrestore = {
                    style: {}
                };
                var b = ["position", "top", "left", "zIndex", "backgroundColor", "marginTop", "marginBottom", "marginLeft", "marginRight"],
                c = s.win[0].style;
                for (var d in b) {
                    var e = b[d];
                    s.zoomrestore.style[e] = "undefined" != typeof c[e] ? c[e] : ""
                }
                s.zoomrestore.style.width = s.win.css("width"),
                s.zoomrestore.style.height = s.win.css("height"),
                s.zoomrestore.padding = {
                    w: s.win.outerWidth() - s.win.width(),
                    h: s.win.outerHeight() - s.win.height()
                },
                w.isios4 && (s.zoomrestore.scrollTop = j(window).scrollTop(), j(window).scrollTop(0)),
                s.win.css({
                    position: w.isios4 ? "absolute": "fixed",
                    top: 0,
                    left: 0,
                    "z-index": i + 100,
                    margin: "0px"
                });
                var f = s.win.css("backgroundColor");
                return ("" == f || /transparent|rgba\(0, 0, 0, 0\)|rgba\(0,0,0,0\)/.test(f)) && s.win.css("backgroundColor", "#fff"),
                s.rail.css({
                    "z-index": i + 101
                }),
                s.zoom.css({
                    "z-index": i + 102
                }),
                s.zoom.css("backgroundPosition", "0px -18px"),
                s.resizeZoom(),
                s.onzoomin && s.onzoomin.call(s),
                s.cancelEvent(a)
            }
        },
        this.doZoomOut = function(a) {
            return s.zoomactive ? (s.zoomactive = !1, s.win.css("margin", ""), s.win.css(s.zoomrestore.style), w.isios4 && j(window).scrollTop(s.zoomrestore.scrollTop), s.rail.css({
                "z-index": s.zindex
            }), s.zoom.css({
                "z-index": s.zindex
            }), s.zoomrestore = !1, s.zoom.css("backgroundPosition", "0px 0px"), s.onResize(), s.onzoomout && s.onzoomout.call(s), s.cancelEvent(a)) : void 0
        },
        this.doZoom = function(a) {
            return s.zoomactive ? s.doZoomOut(a) : s.doZoomIn(a)
        },
        this.resizeZoom = function() {
            if (s.zoomactive) {
                var a = s.getScrollTop();
                s.win.css({
                    width: j(window).width() - s.zoomrestore.padding.w + "px",
                    height: j(window).height() - s.zoomrestore.padding.h + "px"
                }),
                s.onResize(),
                s.setScrollTop(Math.min(s.page.maxh, a))
            }
        },
        this.init(),
        j.nicescroll.push(this)
    },
    v = function(a) {
        var b = this;
        this.nc = a,
        this.lastx = 0,
        this.lasty = 0,
        this.speedx = 0,
        this.speedy = 0,
        this.lasttime = 0,
        this.steptime = 0,
        this.snapx = !1,
        this.snapy = !1,
        this.demulx = 0,
        this.demuly = 0,
        this.lastscrollx = -1,
        this.lastscrolly = -1,
        this.chkx = 0,
        this.chky = 0,
        this.timer = 0,
        this.time = function() {
            return + new Date
        },
        this.reset = function(a, c) {
            b.stop();
            var d = b.time();
            b.steptime = 0,
            b.lasttime = d,
            b.speedx = 0,
            b.speedy = 0,
            b.lastx = a,
            b.lasty = c,
            b.lastscrollx = -1,
            b.lastscrolly = -1
        },
        this.update = function(a, c) {
            var d = b.time();
            b.steptime = d - b.lasttime,
            b.lasttime = d;
            var e = c - b.lasty,
            f = a - b.lastx,
            g = b.nc.getScrollTop(),
            h = b.nc.getScrollLeft(),
            i = g + e,
            j = h + f;
            b.snapx = 0 > j || j > b.nc.page.maxw,
            b.snapy = 0 > i || i > b.nc.page.maxh,
            b.speedx = f,
            b.speedy = e,
            b.lastx = a,
            b.lasty = c
        },
        this.stop = function() {
            b.nc.unsynched("domomentum2d"),
            b.timer && clearTimeout(b.timer),
            b.timer = 0,
            b.lastscrollx = -1,
            b.lastscrolly = -1
        },
        this.doSnapy = function(a, c) {
            var d = !1;
            0 > c ? (c = 0, d = !0) : c > b.nc.page.maxh && (c = b.nc.page.maxh, d = !0),
            0 > a ? (a = 0, d = !0) : a > b.nc.page.maxw && (a = b.nc.page.maxw, d = !0),
            d && b.nc.doScrollPos(a, c, b.nc.opt.snapbackspeed)
        },
        this.doMomentum = function(a) {
            var c = b.time(),
            d = a ? c + a: b.lasttime,
            e = b.nc.getScrollLeft(),
            f = b.nc.getScrollTop(),
            g = b.nc.page.maxh,
            h = b.nc.page.maxw;
            b.speedx = h > 0 ? Math.min(60, b.speedx) : 0,
            b.speedy = g > 0 ? Math.min(60, b.speedy) : 0;
            var i = d && 60 >= c - d; (0 > f || f > g || 0 > e || e > h) && (i = !1);
            var j = b.speedy && i ? b.speedy: !1,
            k = b.speedx && i ? b.speedx: !1;
            if (j || k) {
                var l = Math.max(16, b.steptime);
                if (l > 50) {
                    var m = l / 50;
                    b.speedx *= m,
                    b.speedy *= m,
                    l = 50
                }
                b.demulxy = 0,
                b.lastscrollx = b.nc.getScrollLeft(),
                b.chkx = b.lastscrollx,
                b.lastscrolly = b.nc.getScrollTop(),
                b.chky = b.lastscrolly;
                var n = b.lastscrollx,
                o = b.lastscrolly,
                p = function() {
                    var a = b.time() - c > 600 ? .04 : .02;
                    b.speedx && (n = Math.floor(b.lastscrollx - b.speedx * (1 - b.demulxy)), b.lastscrollx = n, (0 > n || n > h) && (a = .1)),
                    b.speedy && (o = Math.floor(b.lastscrolly - b.speedy * (1 - b.demulxy)), b.lastscrolly = o, (0 > o || o > g) && (a = .1)),
                    b.demulxy = Math.min(1, b.demulxy + a),
                    b.nc.synched("domomentum2d",
                    function() {
                        if (b.speedx) {
                            var a = b.nc.getScrollLeft();
                            a != b.chkx && b.stop(),
                            b.chkx = n,
                            b.nc.setScrollLeft(n)
                        }
                        if (b.speedy) {
                            var c = b.nc.getScrollTop();
                            c != b.chky && b.stop(),
                            b.chky = o,
                            b.nc.setScrollTop(o)
                        }
                        b.timer || (b.nc.hideCursor(), b.doSnapy(n, o))
                    }),
                    b.demulxy < 1 ? b.timer = setTimeout(p, l) : (b.stop(), b.nc.hideCursor(), b.doSnapy(n, o))
                };
                p()
            } else b.doSnapy(b.nc.getScrollLeft(), b.nc.getScrollTop())
        }
    },
    w = b.fn.scrollTop;
    b.cssHooks.pageYOffset = {
        get: function(a) {
            var b = j.data(a, "__nicescroll") || !1;
            return b && b.ishwscroll ? b.getScrollTop() : w.call(a)
        },
        set: function(a, b) {
            var c = j.data(a, "__nicescroll") || !1;
            return c && c.ishwscroll ? c.setScrollTop(parseInt(b)) : w.call(a, b),
            this
        }
    },
    b.fn.scrollTop = function(a) {
        if ("undefined" == typeof a) {
            var b = this[0] ? j.data(this[0], "__nicescroll") || !1 : !1;
            return b && b.ishwscroll ? b.getScrollTop() : w.call(this)
        }
        return this.each(function() {
            var b = j.data(this, "__nicescroll") || !1;
            b && b.ishwscroll ? b.setScrollTop(parseInt(a)) : w.call(j(this), a)
        })
    };
    var x = b.fn.scrollLeft;
    j.cssHooks.pageXOffset = {
        get: function(a) {
            var b = j.data(a, "__nicescroll") || !1;
            return b && b.ishwscroll ? b.getScrollLeft() : x.call(a)
        },
        set: function(a, b) {
            var c = j.data(a, "__nicescroll") || !1;
            return c && c.ishwscroll ? c.setScrollLeft(parseInt(b)) : x.call(a, b),
            this
        }
    },
    b.fn.scrollLeft = function(a) {
        if ("undefined" == typeof a) {
            var b = this[0] ? j.data(this[0], "__nicescroll") || !1 : !1;
            return b && b.ishwscroll ? b.getScrollLeft() : x.call(this)
        }
        return this.each(function() {
            var b = j.data(this, "__nicescroll") || !1;
            b && b.ishwscroll ? b.setScrollLeft(parseInt(a)) : x.call(j(this), a)
        })
    };
    var y = function(b) {
        var c = this;
        if (this.length = 0, this.name = "nicescrollarray", this.each = function(a) {
            for (var b = 0,
            d = 0; b < c.length; b++) a.call(c[b], d++);
            return c
        },
        this.push = function(a) {
            c[c.length] = a,
            c.length++
        },
        this.eq = function(a) {
            return c[a]
        },
        b) for (a = 0; a < b.length; a++) {
            var d = j.data(b[a], "__nicescroll") || !1;
            d && (this[this.length] = d, this.length++)
        }
        return this
    };
    d(y.prototype, ["show", "hide", "toggle", "onResize", "resize", "remove", "stop", "doScrollPos"],
    function(a, b) {
        a[b] = function() {
            var a = arguments;
            return this.each(function() {
                this[b].apply(this, a)
            })
        }
    }),
    b.fn.getNiceScroll = function(a) {
        if ("undefined" == typeof a) return new y(this);
        var b = this[a] && j.data(this[a], "__nicescroll") || !1;
        return b
    },
    b.extend(b.expr[":"], {
        nicescroll: function(a) {
            return j.data(a, "__nicescroll") ? !0 : !1
        }
    }),
    j.fn.niceScroll = function(a, b) {
        "undefined" == typeof b && ("object" != typeof a || "jquery" in a || (b = a, a = !1));
        var c = new y;
        "undefined" == typeof b && (b = {}),
        a && (b.doc = j(a), b.win = j(this));
        var d = !("doc" in b);
        return d || "win" in b || (b.win = j(this)),
        this.each(function() {
            var a = j(this).data("__nicescroll") || !1;
            a || (b.doc = d ? j(this) : b.doc, a = new u(b, j(this)), j(this).data("__nicescroll", a)),
            c.push(a)
        }),
        1 == c.length ? c[0] : c
    },
    window.NiceScroll = {
        getjQuery: function() {
            return b
        }
    },
    j.nicescroll || (j.nicescroll = new y, j.nicescroll.options = r)
} (jQuery),
function(a) {
    a.fn.smartWizard = function(b) {
        var c = arguments,
        d = void 0,
        e = this.each(function() {
            var e = a(this).data("smartWizard");
            if ("object" != typeof b && b && e) {
                if ("function" == typeof SmartWizard.prototype[b]) return d = SmartWizard.prototype[b].apply(e, Array.prototype.slice.call(c, 1));
                a.error("Method " + b + " does not exist on jQuery.smartWizard")
            } else {
                var f = a.extend({},
                a.fn.smartWizard.defaults, b || {});
                e || (e = new SmartWizard(a(this), f), a(this).data("smartWizard", e))
            }
        });
        return void 0 === d ? e: d
    },
    a.fn.smartWizard.defaults = {
        selected: 0,
        keyNavigation: !0,
        enableAllSteps: !1,
        transitionEffect: "fade",
        contentURL: null,
        contentCache: !0,
        cycleSteps: !1,
        enableFinishButton: !1,
        hideButtonsOnDisabled: !1,
        errorSteps: [],
        labelNext: "Next",
        labelPrevious: "Previous",
        labelFinish: "Finish",
        noForwardJumping: !1,
        ajaxType: "POST",
        onLeaveStep: null,
        onShowStep: null,
        onFinish: null,
        includeFinishButton: !0
    }
} (jQuery),
function(a) {
    var b, c = {
        className: "autosizejs",
        append: "",
        callback: !1,
        resizeDelay: 10
    },
    d = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',
    e = ["fontFamily", "fontSize", "fontWeight", "fontStyle", "letterSpacing", "textTransform", "wordSpacing", "textIndent"],
    f = a(d).data("autosize", !0)[0];
    f.style.lineHeight = "99px",
    "99px" === a(f).css("lineHeight") && e.push("lineHeight"),
    f.style.lineHeight = "",
    a.fn.autosize = function(d) {
        return d = a.extend({},
        c, d || {}),
        f.parentNode !== document.body && a(document.body).append(f),
        this.each(function() {
            function c() {
                var c, g = {};
                if (b = l, f.className = d.className, i = parseInt(m.css("maxHeight"), 10), a.each(e,
                function(a, b) {
                    g[b] = m.css(b)
                }), a(f).css(g), "oninput" in l) {
                    var h = l.style.width;
                    l.style.width = "0px",
                    c = l.offsetWidth,
                    l.style.width = h
                }
            }
            function g() {
                var e, g, h, k;
                b !== l && c(),
                f.value = l.value + d.append,
                f.style.overflowY = l.style.overflowY,
                g = parseInt(l.style.height, 10),
                "getComputedStyle" in window ? (k = window.getComputedStyle(l), h = l.getBoundingClientRect().width, a.each(["paddingLeft", "paddingRight", "borderLeftWidth", "borderRightWidth"],
                function(a, b) {
                    h -= parseInt(k[b], 10)
                }), f.style.width = h + "px") : f.style.width = Math.max(m.width(), 0) + "px",
                f.scrollTop = 0,
                f.scrollTop = 9e4,
                e = f.scrollTop,
                i && e > i ? (l.style.overflowY = "scroll", e = i) : (l.style.overflowY = "hidden", j > e && (e = j)),
                e += n,
                g !== e && (l.style.height = e + "px", o && d.callback.call(l, l))
            }
            function h() {
                clearTimeout(k),
                k = setTimeout(function() {
                    m.width() !== q && g()
                },
                parseInt(d.resizeDelay, 10))
            }
            var i, j, k, l = this,
            m = a(l),
            n = 0,
            o = a.isFunction(d.callback),
            p = {
                height: l.style.height,
                overflow: l.style.overflow,
                overflowY: l.style.overflowY,
                wordWrap: l.style.wordWrap,
                resize: l.style.resize
            },
            q = m.width();
            m.data("autosize") || (m.data("autosize", !0), ("border-box" === m.css("box-sizing") || "border-box" === m.css("-moz-box-sizing") || "border-box" === m.css("-webkit-box-sizing")) && (n = m.outerHeight() - m.height()), j = Math.max(parseInt(m.css("minHeight"), 10) - n || 0, m.height()), m.css({
                overflow: "hidden",
                overflowY: "hidden",
                wordWrap: "break-word",
                resize: "none" === m.css("resize") || "vertical" === m.css("resize") ? "none": "horizontal"
            }), "onpropertychange" in l ? "oninput" in l ? m.on("input.autosize keyup.autosize", g) : m.on("propertychange.autosize",
            function() {
                "value" === event.propertyName && g()
            }) : m.on("input.autosize", g), d.resizeDelay !== !1 && a(window).on("resize.autosize", h), m.on("autosize.resize", g), m.on("autosize.resizeIncludeStyle",
            function() {
                b = null,
                g()
            }), m.on("autosize.destroy",
            function() {
                b = null,
                clearTimeout(k),
                a(window).off("resize", h),
                m.off("autosize").off(".autosize").css(p).removeData("autosize")
            }), g())
        })
    }
} (window.jQuery || window.Zepto),
$(function() {
    $(".character-count").keyup(function() {
        var a = 125,
        b = $(this).val().length;
        if (b >= a) $(".character-remaining").text(" you have reached the limit");
        else {
            var c = a - b;
            $(".character-remaining").text(c + " characters left")
        }
    })
}),
$(document).ready(function() {
    $(".toggle-switch").click(function(a) {
        a.preventDefault();
        var b = $(this).attr("switch-parent"),
        c = $(this).attr("switch-target");
        $(b).slideToggle(),
        $(c).slideToggle()
    }),
    $(".button-toggle").click(function(a) {
        a.preventDefault(),
        $(this).next(".toggle-content").slideToggle()
    }),
    $(".button-toggle").hover(function() {
        $(".content-box-header a.btn", this).fadeIn("fast")
    },
    function() {
        $(".content-box-header a.btn", this).fadeOut("normal")
    }),
    $(".box-toggle .content-box-header .toggle-button").click(function(a) {
        a.preventDefault(),
        $(".icon-toggle", this).toggleClass("icon-chevron-down").toggleClass("icon-chevron-up"),
        $(this).parents(".content-box:first").hasClass("content-box-closed") ? $(this).parents(".content-box:first").removeClass("content-box-closed").find(".content-box-wrapper").slideDown("fast") : $(this).parents(".content-box:first").addClass("content-box-closed").find(".content-box-wrapper").slideUp("fast")
    }),
    $(".remove-button").click(function(a) {
        a.preventDefault();
        var b = $(this).attr("data-animation"),
        c = $(this).parents(".content-box:first");
        $(c).addClass("animated"),
        $(c).addClass(b),
        window.setTimeout(function() {
            $(c).slideUp()
        },
        500),
        window.setTimeout(function() {
            $(c).removeClass(b).fadeIn()
        },
        2500)
    }),
    $(function() {
        $(".infobox-close").click(function(a) {
            a.preventDefault(),
            $(this).parent().fadeOut()
        })
    })
}),
function(a, b) {
    "use strict";
    function c(a) {
        var b = Array.prototype.slice.call(arguments, 1);
        return a.prop ? a.prop.apply(a, b) : a.attr.apply(a, b)
    }
    function d(a, b, c) {
        var d, e;
        for (d in c) c.hasOwnProperty(d) && (e = d.replace(/ |$/g, b.eventNamespace), a.bind(e, c[d]))
    }
    function e(a, b, c) {
        d(a, c, {
            focus: function() {
                b.addClass(c.focusClass)
            },
            blur: function() {
                b.removeClass(c.focusClass),
                b.removeClass(c.activeClass)
            },
            mouseenter: function() {
                b.addClass(c.hoverClass)
            },
            mouseleave: function() {
                b.removeClass(c.hoverClass),
                b.removeClass(c.activeClass)
            },
            "mousedown touchbegin": function() {
                a.is(":disabled") || b.addClass(c.activeClass)
            },
            "mouseup touchend": function() {
                b.removeClass(c.activeClass)
            }
        })
    }
    function f(a, b) {
        a.removeClass(b.hoverClass + " " + b.focusClass + " " + b.activeClass)
    }
    function g(a, b, c) {
        c ? a.addClass(b) : a.removeClass(b)
    }
    function h(a, b, c) {
        var d = "checked",
        e = b.is(":" + d);
        b.prop ? b.prop(d, e) : e ? b.attr(d, d) : b.removeAttr(d),
        g(a, c.checkedClass, e)
    }
    function i(a, b, c) {
        g(a, c.disabledClass, b.is(":disabled"))
    }
    function j(a, b, c) {
        switch (c) {
        case "after":
            return a.after(b),
            a.next();
        case "before":
            return a.before(b),
            a.prev();
        case "wrap":
            return a.wrap(b),
            a.parent()
        }
        return null
    }
    function k(b, d, e) {
        var f, g, h;
        return e || (e = {}),
        e = a.extend({
            bind: {},
            divClass: null,
            divWrap: "wrap",
            spanClass: null,
            spanHtml: null,
            spanWrap: "wrap"
        },
        e),
        f = a("<div />"),
        g = a("<span />"),
        d.autoHide && b.is(":hidden") && "none" === b.css("display") && f.hide(),
        e.divClass && f.addClass(e.divClass),
        d.wrapperClass && f.addClass(d.wrapperClass),
        e.spanClass && g.addClass(e.spanClass),
        h = c(b, "id"),
        d.useID && h && c(f, "id", d.idPrefix + "-" + h),
        e.spanHtml && g.html(e.spanHtml),
        f = j(b, f, e.divWrap),
        g = j(b, g, e.spanWrap),
        i(f, b, d),
        {
            div: f,
            span: g
        }
    }
    function l(b, c) {
        var d;
        return c.wrapperClass ? (d = a("<span />").addClass(c.wrapperClass), d = j(b, d, "wrap")) : null
    }
    function m() {
        var b, c, d, e;
        return e = "rgb(120,2,153)",
        c = a('<div style="width:0;height:0;color:' + e + '">'),
        a("body").append(c),
        d = c.get(0),
        b = window.getComputedStyle ? window.getComputedStyle(d, "").color: (d.currentStyle || d.style || {}).color,
        c.remove(),
        b.replace(/ /g, "") !== e
    }
    function n(b) {
        return b ? a("<span />").text(b).html() : ""
    }
    function o() {
        return navigator.cpuClass && !navigator.product
    }
    function p() {
        return "undefined" != typeof window.XMLHttpRequest ? !0 : !1
    }
    function q(a) {
        var b;
        return a[0].multiple ? !0 : (b = c(a, "size"), !b || 1 >= b ? !1 : !0)
    }
    function r() {
        return ! 1
    }
    function s(a, b) {
        var c = "none";
        d(a, b, {
            "selectstart dragstart mousedown": r
        }),
        a.css({
            MozUserSelect: c,
            msUserSelect: c,
            webkitUserSelect: c,
            userSelect: c
        })
    }
    function t(a, b, c) {
        var d = a.val();
        "" === d ? d = c.fileDefaultHtml: (d = d.split(/[\/\\]+/), d = d[d.length - 1]),
        b.text(d)
    }
    function u(a, b, c) {
        var d, e;
        for (d = [], a.each(function() {
            var a;
            for (a in b) Object.prototype.hasOwnProperty.call(b, a) && (d.push({
                el: this,
                name: a,
                old: this.style[a]
            }), this.style[a] = b[a])
        }), c(); d.length;) e = d.pop(),
        e.el.style[e.name] = e.old
    }
    function v(a, b) {
        var c;
        c = a.parents(),
        c.push(a[0]),
        c = c.not(":visible"),
        u(c, {
            visibility: "hidden",
            display: "block",
            position: "absolute"
        },
        b)
    }
    function w(a, b) {
        return function() {
            a.unwrap().unwrap().unbind(b.eventNamespace)
        }
    }
    var x = !0,
    y = !1,
    z = [{
        match: function(a) {
            return a.is("a, button, :submit, :reset, input[type='button']")
        },
        apply: function(a, b) {
            var g, h, j, l, m;
            return h = b.submitDefaultHtml,
            a.is(":reset") && (h = b.resetDefaultHtml),
            l = a.is("a, button") ?
            function() {
                return a.html() || h
            }: function() {
                return n(c(a, "value")) || h
            },
            j = k(a, b, {
                divClass: b.buttonClass,
                spanHtml: l()
            }),
            g = j.div,
            e(a, g, b),
            m = !1,
            d(g, b, {
                "click touchend": function() {
                    var b, d, e, f;
                    m || a.is(":disabled") || (m = !0, a[0].dispatchEvent ? (b = document.createEvent("MouseEvents"), b.initEvent("click", !0, !0), d = a[0].dispatchEvent(b), a.is("a") && d && (e = c(a, "target"), f = c(a, "href"), e && "_self" !== e ? window.open(f, e) : document.location.href = f)) : a.click(), m = !1)
                }
            }),
            s(g, b),
            {
                remove: function() {
                    return g.after(a),
                    g.remove(),
                    a.unbind(b.eventNamespace),
                    a
                },
                update: function() {
                    f(g, b),
                    i(g, a, b),
                    a.detach(),
                    j.span.html(l()).append(a)
                }
            }
        }
    },
    {
        match: function(a) {
            return a.is(":checkbox")
        },
        apply: function(a, b) {
            var c, g, j;
            return c = k(a, b, {
                divClass: b.checkboxClass
            }),
            g = c.div,
            j = c.span,
            e(a, g, b),
            d(a, b, {
                "click touchend": function() {
                    h(j, a, b)
                }
            }),
            h(j, a, b),
            {
                remove: w(a, b),
                update: function() {
                    f(g, b),
                    j.removeClass(b.checkedClass),
                    h(j, a, b),
                    i(g, a, b)
                }
            }
        }
    },
    {
        match: function(a) {
            return a.is(":file")
        },
        apply: function(b, g) {
            function h() {
                t(b, n, g)
            }
            var l, m, n, p;
            return l = k(b, g, {
                divClass: g.fileClass,
                spanClass: g.fileButtonClass,
                spanHtml: g.fileButtonHtml,
                spanWrap: "after"
            }),
            m = l.div,
            p = l.span,
            n = a("<span />").html(g.fileDefaultHtml),
            n.addClass(g.filenameClass),
            n = j(b, n, "after"),
            c(b, "size") || c(b, "size", m.width() / 10),
            e(b, m, g),
            h(),
            o() ? d(b, g, {
                click: function() {
                    b.trigger("change"),
                    setTimeout(h, 0)
                }
            }) : d(b, g, {
                change: h
            }),
            s(n, g),
            s(p, g),
            {
                remove: function() {
                    return n.remove(),
                    p.remove(),
                    b.unwrap().unbind(g.eventNamespace)
                },
                update: function() {
                    f(m, g),
                    t(b, n, g),
                    i(m, b, g)
                }
            }
        }
    },
    {
        match: function(a) {
            if (a.is("input")) {
                var b = (" " + c(a, "type") + " ").toLowerCase(),
                d = " color date datetime datetime-local email month number password search tel text time url week ";
                return d.indexOf(b) >= 0
            }
            return ! 1
        },
        apply: function(a, b) {
            var d, f;
            return d = c(a, "type"),
            a.addClass(b.inputClass),
            f = l(a, b),
            e(a, a, b),
            b.inputAddTypeAsClass && a.addClass(d),
            {
                remove: function() {
                    a.removeClass(b.inputClass),
                    b.inputAddTypeAsClass && a.removeClass(d),
                    f && a.unwrap()
                },
                update: r
            }
        }
    },
    {
        match: function(a) {
            return a.is(":radio")
        },
        apply: function(b, g) {
            var j, l, m;
            return j = k(b, g, {
                divClass: g.radioClass
            }),
            l = j.div,
            m = j.span,
            e(b, l, g),
            d(b, g, {
                "click touchend": function() {
                    a.uniform.update(a(':radio[name="' + c(b, "name") + '"]'))
                }
            }),
            h(m, b, g),
            {
                remove: w(b, g),
                update: function() {
                    f(l, g),
                    h(m, b, g),
                    i(l, b, g)
                }
            }
        }
    },
    {
        match: function(a) {
            return a.is("select") && !q(a) ? !0 : !1
        },
        apply: function(b, c) {
            var g, h, j, l;
            return c.selectAutoWidth && v(b,
            function() {
                l = b.width()
            }),
            g = k(b, c, {
                divClass: c.selectClass,
                spanHtml: (b.find(":selected:first") || b.find("option:first")).html(),
                spanWrap: "before"
            }),
            h = g.div,
            j = g.span,
            c.selectAutoWidth ? v(b,
            function() {
                u(a([j[0], h[0]]), {
                    display: "block"
                },
                function() {
                    var a;
                    a = j.outerWidth() - j.width(),
                    h.width(l + a),
                    j.width(l)
                })
            }) : h.addClass("fixedWidth"),
            e(b, h, c),
            d(b, c, {
                change: function() {
                    j.html(b.find(":selected").html()),
                    h.removeClass(c.activeClass)
                },
                "click touchend": function() {
                    var a = b.find(":selected").html();
                    j.html() !== a && b.trigger("change")
                },
                keyup: function() {
                    j.html(b.find(":selected").html())
                }
            }),
            s(j, c),
            {
                remove: function() {
                    return j.remove(),
                    b.unwrap().unbind(c.eventNamespace),
                    b
                },
                update: function() {
                    c.selectAutoWidth ? (a.uniform.restore(b), b.uniform(c)) : (f(h, c), j.html(b.find(":selected").html()), i(h, b, c))
                }
            }
        }
    },
    {
        match: function(a) {
            return a.is("select") && q(a) ? !0 : !1
        },
        apply: function(a, b) {
            var c;
            return a.addClass(b.selectMultiClass),
            c = l(a, b),
            e(a, a, b),
            {
                remove: function() {
                    a.removeClass(b.selectMultiClass),
                    c && a.unwrap()
                },
                update: r
            }
        }
    },
    {
        match: function(a) {
            return a.is("textarea")
        },
        apply: function(a, b) {
            var c;
            return a.addClass(b.textareaClass),
            c = l(a, b),
            e(a, a, b),
            {
                remove: function() {
                    a.removeClass(b.textareaClass),
                    c && a.unwrap()
                },
                update: r
            }
        }
    }];
    o() && !p() && (x = !1),
    a.uniform = {
        defaults: {
            activeClass: "active",
            autoHide: !0,
            buttonClass: "button",
            checkboxClass: "checker",
            checkedClass: "checked",
            disabledClass: "disabled",
            eventNamespace: ".uniform",
            fileButtonClass: "action",
            fileButtonHtml: "Choose File",
            fileClass: "uploader",
            fileDefaultHtml: "No file selected",
            filenameClass: "filename",
            focusClass: "focus",
            hoverClass: "hover",
            idPrefix: "uniform",
            inputAddTypeAsClass: !0,
            inputClass: "uniform-input",
            radioClass: "radio",
            resetDefaultHtml: "Reset",
            resetSelector: !1,
            selectAutoWidth: !0,
            selectClass: "selector",
            selectMultiClass: "uniform-multiselect",
            submitDefaultHtml: "Submit",
            textareaClass: "uniform",
            useID: !0,
            wrapperClass: null
        },
        elements: []
    },
    a.fn.uniform = function(b) {
        var c = this;
        return b = a.extend({},
        a.uniform.defaults, b),
        y || (y = !0, m() && (x = !1)),
        x ? (b.resetSelector && a(b.resetSelector).mouseup(function() {
            window.setTimeout(function() {
                a.uniform.update(c)
            },
            10)
        }), this.each(function() {
            var c, d, e, f = a(this);
            if (f.data("uniformed")) return a.uniform.update(f),
            void 0;
            for (c = 0; c < z.length; c += 1) if (d = z[c], d.match(f, b)) return e = d.apply(f, b),
            f.data("uniformed", e),
            a.uniform.elements.push(f.get(0)),
            void 0
        })) : this
    },
    a.uniform.restore = a.fn.uniform.restore = function(c) {
        c === b && (c = a.uniform.elements),
        a(c).each(function() {
            var b, c, d = a(this);
            c = d.data("uniformed"),
            c && (c.remove(), b = a.inArray(this, a.uniform.elements), b >= 0 && a.uniform.elements.splice(b, 1), d.removeData("uniformed"))
        })
    },
    a.uniform.update = a.fn.uniform.update = function(c) {
        c === b && (c = a.uniform.elements),
        a(c).each(function() {
            var b, c = a(this);
            b = c.data("uniformed"),
            b && b.update(c, b.options)
        })
    }
} (jQuery),
!
function(a) {
    "use strict";
    var b = function(a) {
        this.messages = {
            defaultMessage: "这个值似乎是无效的.",
            type: {
                email: "请输入一个有效的电子邮件.",
                url: "请输入一个有效的url.",
                urlstrict: "请输入一个有效的url.",
                number: "请输入一个有效的数字.",
                digits: "请输入一个有效的数字.",
                dateIso: "请输入一个有效的日期,形如1988-04-08.",
                alphanum: "请输入一个有效的由阿拉伯字母组成的字符串.",
                phone: "请输入一个有效的电话号码.",
				intege: "请输入一个有效的整数",
				intege1: "请输入一个有效的正整数",
				intege2: "请输入一个有效的负整数",
				num1: "请输入一个有效的正数（正整数 + 0）",
				num2: "请输入一个有效的负数（负整数 + 0）",
				decmal: "请输入一个有效的浮点数",
				decmal1: "请输入一个有效的正浮点数",
				decmal2: "请输入一个有效的负浮点数",
				decmal3: "请输入一个有效的浮点数",
				decmal4: "请输入一个有效的非负浮点数（正浮点数 + 0）",
				decmal5: "请输入一个有效的非正浮点数（负浮点数 + 0）",
				price: "请输入一个有效的价格型式 可以为正浮点数或正整数",
				color: "请输入一个有效的颜色值 ",
				chinese: "只能输入中文",
				ascii: "只能输入ACSII字符",
				zipcode: "请输入一个有效的邮编",
				username: "请输入一个有效的26个英文字母组成的字符串",
				uname: "请输入一个有效的由数字、26个英文字母或者下划线组成的字符串",
				exuname: "请输入一个有效的中文字符，数字，字母组成的字符串",
				islnum: "必需以数字开头",
				letter:"请输入一个有效的由字母组成的字符串",
				letter_u: "请输入一个有效的由大写字母组成的字符串",
				letter_l: "请输入一个有效的由小写字母组成的字符串",
				idcard: "请输入一个有效的身份证"
            },
            notnull: "此项不能为空.",
            notblank: "此项不允许空格.",
            required: "此项必须填写.",
            regexp: "此项验证失败.",
            min: "值必须大于 %s.",
            max: "值必须小于 %s.",
            range: "此项值介于 %s 和 %s 之间.",
            minlength: "这个值太短了。它应该有%s字符或更多.",
            maxlength: "这个值是太长了。它应该有%s字符或更少.",
            rangelength: "长度在 %s 和 %s 之间.",
            mincheck: "You must select at least %s choices.",
            maxcheck: "You must select %s choices or less.",
            rangecheck: "You must select between %s and %s choices.",
            equalto: "两次输入不匹配."
        },
        this.init(a)
    };
    b.prototype = {
        constructor: b,
        validators: {
            notnull: function(a) {
                return a.length > 0
            },
            notblank: function(a) {
                return "string" == typeof a && "" !== a.replace(/^\s+/g, "").replace(/\s+$/g, "")
            },
            required: function(a) {
                if ("object" == typeof a) {
                    for (var b in a) if (this.required(a[b])) return ! 0;
                    return ! 1
                }
                return this.notnull(a) && this.notblank(a)
            },
            type: function(a, b) {
                var c;
                switch (b) {
                case "number":
                    c = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/;
                    break;
                case "digits":
                    c = /^\d+$/;
                    break;
                case "alphanum":
                    c = /^\w+$/;
                    break;
                case "email":
                    c = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))){2,6}$/i;
                    break;
                case "url":
                    a = new RegExp("(https?|s?ftp|git)", "i").test(a) ? a: "http://" + a;
                case "urlstrict":
                    c = /^(https?|s?ftp|git):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;
                    break;
                case "dateIso":
                    c = /^(\d{4})\D?(0[1-9]|1[0-2])\D?([12]\d|0[1-9]|3[01])$/;
                    break;
                case "phone":
                    c = /^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/;
                    break;
				case "intege":
					 c=/^-?[1-9]\\d*$/;				//整数
					  break;
				case "intege1": 
					c=/^[1-9]\\d*$/;					//正整数
					 break;
				case "intege2": 
					c=/^-[1-9]\\d*$/;					//负整数
					 break;
				case "num1": 
					c=/^[1-9]\\d*|0$/;					//正数（正整数 + 0）
					 break;
				case "num2": 
					c=/^-[1-9]\\d*|0$/;					//负数（负整数 + 0）
					 break;
				case "decmal": 
					c=/^([+-]?)\\d*\\.\\d+$/;			//浮点数
					 break;
				case "decmal1": 
					c=/^[1-9]\\d*.\\d*|0.\\d*[1-9]\\d*$/;　　	//正浮点数
					 break;
				case "decmal2": 
					c=/^-([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*)$/;　 //负浮点数
					 break;
				case "decmal3": 
					c=/^-?([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*|0?.0+|0)$/;　 //浮点数
					 break;
				case "decmal4": 
					c=/^[1-9]\\d*.\\d*|0.\\d*[1-9]\\d*|0?.0+|0$/;　　 //非负浮点数（正浮点数 + 0）
					 break;
				case "decmal5": 
					c=/^(-([1-9]\\d*.\\d*|0.\\d*[1-9]\\d*))|0?.0+|0$/;　　//非正浮点数（负浮点数 + 0）
					 break;
				case "price": 
					c=/^[1-9]\\d*.\\d{1,2}$|^0.\\d{1,2}$|^[1-9]\\d*$|^0$/;//价格型式 可以为正浮点数或正整数
					 break;
				case "color": 
					c=/^[a-fA-F0-9]{6}$/;				//颜色
					 break;
				case "chinese": 
					c=/^[\\u4E00-\\u9FA5\\uF900-\\uFA2D]+$/;					//仅中文
					 break;
				case "ascii": 
					c=/^[\\x00-\\xFF]+$/;				//仅ACSII字符
					 break;
				case "zipcode": 
					c=/^\\d{6}$/;						//邮编
					 break;
				case "username": 
					c=/^\\w+$/;						//用来用户注册。匹配由数字、26个英文字母或者下划线组成的字符串
					 break;
				case "uname": 
					c=/^[a-zA-Z]{1}(\\w+)$/;						//用来用户注册。匹配由数字、26个英文字母或者下划线组成的字符串
					 break;
				case "exuname": 
					c=/^[\\u4E00-\\u9FA5\\uF900-\\uFA2D(a-zA-Z0-9)]+$/;//匹配中文字符，数字，字母的正则表达式
					 break;
				case "letter": 
					c=/^[A-Za-z]+$/;					//字母
					 break;
				case "letter_u": 
					c=/^[A-Z]+$/;					//大写字母
					 break;
				case "letter_l": 
					c=/^[a-z]+$/;					//小写字母
					 break;
				case "idcard": 
					c=/^[1-9]([0-9]{14}|[0-9]{17})$/;	//身份证
					 break;
                default:
                    return ! 1
                }
                return "" !== a ? c.test(a) : !1
            },
            regexp: function(a, b, c) {
                return new RegExp(b, c.options.regexpFlag || "").test(a)
            },
            minlength: function(a, b) {
                return a.length >= b
            },
            maxlength: function(a, b) {
                return a.length <= b
            },
            rangelength: function(a, b) {
                return this.minlength(a, b[0]) && this.maxlength(a, b[1])
            },
            min: function(a, b) {
                return Number(a) >= b
            },
            max: function(a, b) {
                return Number(a) <= b
            },
            range: function(a, b) {
                return a >= b[0] && a <= b[1]
            },
            equalto: function(b, c, d) {
                return d.options.validateIfUnchanged = !0,
                b === a(c).val()
            },
            remote: function(b, c, d) {
                var e = null,
                f = {},
                g = {};
                f[d.$element.attr("name")] = b,
                "undefined" != typeof d.options.remoteDatatype && (g = {
                    dataType: d.options.remoteDatatype
                });
                var h = function(b, c) {
                    "undefined" != typeof c && "undefined" != typeof d.Validator.messages.remote && c !== d.Validator.messages.remote && a(d.ulError + " .remote").remove(),
                    d.updtConstraint({
                        name: "remote",
                        valid: b
                    },
                    c),
                    d.manageValidationResult()
                },
                i = function(b) {
                    if ("object" == typeof b) return b;
                    try {
                        b = a.parseJSON(b)
                    } catch(c) {}
                    return b
                },
                j = function(a) {
                    return "object" == typeof a && null !== a ? "undefined" != typeof a.error ? a.error: "undefined" != typeof a.message ? a.message: null: null
                };
                return a.ajax(a.extend({},
                {
                    url: c,
                    data: f,
                    type: d.options.remoteMethod || "GET",
                    success: function(a) {
                        a = i(a),
                        h(1 === a || !0 === a || "object" == typeof a && null !== a && "undefined" != typeof a.success, j(a))
                    },
                    error: function(a) {
                        a = i(a),
                        h(!1, j(a))
                    }
                },
                g)),
                e
            },
            mincheck: function(a, b) {
                return this.minlength(a, b)
            },
            maxcheck: function(a, b) {
                return this.maxlength(a, b)
            },
            rangecheck: function(a, b) {
                return this.rangelength(a, b)
            }
        },
        init: function(a) {
            var b, c = a.validators,
            d = a.messages;
            for (b in c) this.addValidator(b, c[b]);
            for (b in d) this.addMessage(b, d[b])
        },
        formatMesssage: function(a, b) {
            if ("object" == typeof b) {
                for (var c in b) a = this.formatMesssage(a, b[c]);
                return a
            }
            return "string" == typeof a ? a.replace(new RegExp("%s", "i"), b) : ""
        },
        addValidator: function(a, b) {
            this.validators[a] = b
        },
        addMessage: function(a, b, c) {
            if ("undefined" != typeof c && !0 === c) return this.messages.type[a] = b,
            void 0;
            if ("type" !== a) this.messages[a] = b;
            else for (var d in b) this.messages.type[d] = b[d]
        }
    };
    var c = function(a, c, d) {
        return this.options = c,
        this.Validator = new b(c),
        "ParsleyFieldMultiple" === d ? this: (this.init(a, d || "ParsleyField"), void 0)
    };
    c.prototype = {
        constructor: c,
        init: function(b, c) {
            this.type = c,
            this.valid = !0,
            this.element = b,
            this.validatedOnce = !1,
            this.$element = a(b),
            this.val = this.$element.val(),
            this.isRequired = !1,
            this.constraints = {},
            "undefined" == typeof this.isRadioOrCheckbox && (this.isRadioOrCheckbox = !1, this.hash = this.generateHash(), this.errorClassHandler = this.options.errors.classHandler(b, this.isRadioOrCheckbox) || this.$element),
            this.ulErrorManagement(),
            this.bindHtml5Constraints(),
            this.addConstraints(),
            this.hasConstraints() && this.bindValidationEvents()
        },
        setParent: function(b) {
            this.$parent = a(b)
        },
        getParent: function() {
            return this.$parent
        },
        bindHtml5Constraints: function() { (this.$element.hasClass("required") || this.$element.prop("required")) && (this.options.required = !0),
            "undefined" != typeof this.$element.attr("type") && new RegExp(this.$element.attr("type"), "i").test("email url number range") && (this.options.type = this.$element.attr("type"), new RegExp(this.options.type, "i").test("number range") && (this.options.type = "number", "undefined" != typeof this.$element.attr("min") && this.$element.attr("min").length && (this.options.min = this.$element.attr("min")), "undefined" != typeof this.$element.attr("max") && this.$element.attr("max").length && (this.options.max = this.$element.attr("max")))),
            "string" == typeof this.$element.attr("pattern") && this.$element.attr("pattern").length && (this.options.regexp = this.$element.attr("pattern"))
        },
        addConstraints: function() {
            for (var a in this.options) {
                var b = {};
                b[a] = this.options[a],
                this.addConstraint(b, !0)
            }
        },
        addConstraint: function(a, b) {
            for (var c in a) c = c.toLowerCase(),
            "function" == typeof this.Validator.validators[c] && (this.constraints[c] = {
                name: c,
                requirements: a[c],
                valid: null
            },
            "required" === c && (this.isRequired = !0), this.addCustomConstraintMessage(c));
            "undefined" == typeof b && this.bindValidationEvents()
        },
        updateConstraint: function(a, b) {
            for (var c in a) this.updtConstraint({
                name: c,
                requirements: a[c],
                valid: null
            },
            b)
        },
        updtConstraint: function(b, c) {
            this.constraints[b.name] = a.extend(!0, this.constraints[b.name], b),
            "string" == typeof c && (this.Validator.messages[b.name] = c),
            this.bindValidationEvents()
        },
        removeConstraint: function(a) {
            var a = a.toLowerCase();
            return delete this.constraints[a],
            "required" === a && (this.isRequired = !1),
            this.hasConstraints() ? (this.bindValidationEvents(), void 0) : "ParsleyForm" == typeof this.getParent() ? (this.getParent().removeItem(this.$element), void 0) : (this.destroy(), void 0)
        },
        addCustomConstraintMessage: function(a) {
            var b = a + ("type" === a && "undefined" != typeof this.options[a] ? this.options[a].charAt(0).toUpperCase() + this.options[a].substr(1) : "") + "Message";
            "undefined" != typeof this.options[b] && this.Validator.addMessage("type" === a ? this.options[a] : a, this.options[b], "type" === a)
        },
        bindValidationEvents: function() {
            this.valid = null,
            this.$element.addClass("parsley-validated"),
            this.$element.off("." + this.type),
            this.options.remote && !new RegExp("change", "i").test(this.options.trigger) && (this.options.trigger = this.options.trigger ? " change": "change");
            var b = (this.options.trigger ? this.options.trigger: "") + (new RegExp("key", "i").test(this.options.trigger) ? "": " keyup");
            this.$element.is("select") && (b += new RegExp("change", "i").test(b) ? "": " change"),
            b = b.replace(/^\s+/g, "").replace(/\s+$/g, ""),
            this.$element.on((b + " ").split(" ").join("." + this.type + " "), !1, a.proxy(this.eventValidation, this))
        },
        generateHash: function() {
            return "parsley-" + (Math.random() + "").substring(2)
        },
        getHash: function() {
            return this.hash
        },
        getVal: function() {
            return this.$element.data("value") || this.$element.val()
        },
        eventValidation: function(a) {
            var b = this.getVal();
            return "keyup" !== a.type || /keyup/i.test(this.options.trigger) || this.validatedOnce ? "change" !== a.type || /change/i.test(this.options.trigger) || this.validatedOnce ? !this.isRadioOrCheckbox && this.getLength(b) < this.options.validationMinlength && !this.validatedOnce ? !0 : (this.validate(), void 0) : !0 : !0
        },
        getLength: function(a) {
            return a && a.hasOwnProperty("length") ? a.length: 0
        },
        isValid: function() {
            return this.validate(!1)
        },
        hasConstraints: function() {
            for (var a in this.constraints) return ! 0;
            return ! 1
        },
        validate: function(a) {
            var b = this.getVal(),
            c = null;
            return this.hasConstraints() ? this.options.listeners.onFieldValidate(this.element, this) || "" === b && !this.isRequired ? (this.reset(), null) : this.needsValidation(b) ? (c = this.applyValidators(), ("undefined" != typeof a ? a: this.options.showErrors) && this.manageValidationResult(), c) : this.valid: null
        },
        needsValidation: function(a) {
            return ! this.options.validateIfUnchanged && null !== this.valid && this.val === a && this.validatedOnce ? !1 : (this.val = a, this.validatedOnce = !0)
        },
        applyValidators: function() {
            var a = null;
            for (var b in this.constraints) {
                var c = this.Validator.validators[this.constraints[b].name](this.val, this.constraints[b].requirements, this); ! 1 === c ? (a = !1, this.constraints[b].valid = a, this.options.listeners.onFieldError(this.element, this.constraints, this)) : !0 === c && (this.constraints[b].valid = !0, a = !1 !== a, !1 === this.options.listeners.onFieldSuccess(this.element, this.constraints, this) && (a = !1))
            }
            return a
        },
        manageValidationResult: function() {
            var b = null;
            for (var c in this.constraints) ! 1 === this.constraints[c].valid ? (this.manageError(this.constraints[c]), b = !1) : !0 === this.constraints[c].valid && (this.removeError(this.constraints[c].name), b = !1 !== b);
            return this.valid = b,
            !0 === this.valid ? (this.removeErrors(), this.errorClassHandler.removeClass(this.options.errorClass).addClass(this.options.successClass), !0) : !1 === this.valid ? (this.errorClassHandler.removeClass(this.options.successClass).addClass(this.options.errorClass), !1) : (this.ulError && 0 === a(this.ulError).children().length && this.removeErrors(), b)
        },
        ulErrorManagement: function() {
            this.ulError = "#" + this.hash,
            this.ulTemplate = a(this.options.errors.errorsWrapper).attr("id", this.hash).addClass("parsley-error-list")
        },
        removeError: function(b) {
            var c = this.ulError + " ." + b,
            d = this;
            this.options.animate ? a(c).fadeOut(this.options.animateDuration,
            function() {
                a(this).remove(),
                d.ulError && 0 === a(d.ulError).children().length && d.removeErrors()
            }) : a(c).remove()
        },
        addError: function(b) {
            for (var c in b) {
                var d = a(this.options.errors.errorElem).addClass(c);
                a(this.ulError).append(this.options.animate ? a(d).html(b[c]).hide().fadeIn(this.options.animateDuration) : a(d).html(b[c]))
            }
        },
        removeErrors: function() {
            this.options.animate ? a(this.ulError).fadeOut(this.options.animateDuration,
            function() {
                a(this).remove()
            }) : a(this.ulError).remove()
        },
        reset: function() {
            this.valid = null,
            this.removeErrors(),
            this.validatedOnce = !1,
            this.errorClassHandler.removeClass(this.options.successClass).removeClass(this.options.errorClass);
            for (var a in this.constraints) this.constraints[a].valid = null;
            return this
        },
        manageError: function(b) {
            if (a(this.ulError).length || this.manageErrorContainer(), !("required" === b.name && null !== this.getVal() && this.getVal().length > 0 || this.isRequired && "required" !== b.name && (null === this.getVal() || 0 === this.getVal().length))) {
                var c = b.name,
                d = !1 !== this.options.errorMessage ? "custom-error-message": c,
                e = {},
                f = !1 !== this.options.errorMessage ? this.options.errorMessage: "type" === b.name ? this.Validator.messages[c][b.requirements] : "undefined" == typeof this.Validator.messages[c] ? this.Validator.messages.defaultMessage: this.Validator.formatMesssage(this.Validator.messages[c], b.requirements);
                a(this.ulError + " ." + d).length || (e[d] = f, this.addError(e))
            }
        },
        manageErrorContainer: function() {
            var b = this.options.errorContainer || this.options.errors.container(this.element, this.isRadioOrCheckbox),
            c = this.options.animate ? this.ulTemplate.show() : this.ulTemplate;
            return "undefined" != typeof b ? (a(b).append(c), void 0) : (this.isRadioOrCheckbox ? this.$element.parent().after(c) : this.$element.after(c), void 0)
        },
        addListener: function(a) {
            for (var b in a) this.options.listeners[b] = a[b]
        },
        destroy: function() {
            this.$element.removeClass("parsley-validated"),
            this.reset().$element.off("." + this.type).removeData(this.type)
        }
    };
    var d = function(a, c, d) {
        this.initMultiple(a, c),
        this.inherit(a, c),
        this.Validator = new b(c),
        this.init(a, d || "ParsleyFieldMultiple")
    };
    d.prototype = {
        constructor: d,
        initMultiple: function(b, c) {
            this.element = b,
            this.$element = a(b),
            this.group = c.group || !1,
            this.hash = this.getName(),
            this.siblings = this.group ? '[data-group="' + this.group + '"]': 'input[name="' + this.$element.attr("name") + '"]',
            this.isRadioOrCheckbox = !0,
            this.isRadio = this.$element.is("input[type=radio]"),
            this.isCheckbox = this.$element.is("input[type=checkbox]"),
            this.errorClassHandler = c.errors.classHandler(b, this.isRadioOrCheckbox) || this.$element.parent()
        },
        inherit: function(a, b) {
            var d = new c(a, b, "ParsleyFieldMultiple");
            for (var e in d)"undefined" == typeof this[e] && (this[e] = d[e])
        },
        getName: function() {
            if (this.group) return "parsley-" + this.group;
            if ("undefined" == typeof this.$element.attr("name")) throw "A radio / checkbox input must have a data-group attribute or a name to be Parsley validated !";
            return "parsley-" + this.$element.attr("name").replace(/(:|\.|\[|\])/g, "")
        },
        getVal: function() {
            if (this.isRadio) return a(this.siblings + ":checked").val() || "";
            if (this.isCheckbox) {
                var b = [];
                return a(this.siblings + ":checked").each(function() {
                    b.push(a(this).val())
                }),
                b
            }
        },
        bindValidationEvents: function() {
            this.valid = null,
            this.$element.addClass("parsley-validated"),
            this.$element.off("." + this.type);
            var b = this,
            c = (this.options.trigger ? this.options.trigger: "") + (new RegExp("change", "i").test(this.options.trigger) ? "": " change");
            c = c.replace(/^\s+/g, "").replace(/\s+$/g, ""),
            a(this.siblings).each(function() {
                a(this).on(c.split(" ").join("." + b.type + " "), !1, a.proxy(b.eventValidation, b))
            })
        }
    };
    var e = function(a, b, c) {
        this.init(a, b, c || "parsleyForm")
    };
    e.prototype = {
        constructor: e,
        init: function(b, c, d) {
            this.type = d,
            this.items = [],
            this.$element = a(b),
            this.options = c;
            var e = this;
            this.$element.find(c.inputs).each(function() {
                e.addItem(this)
            }),
            this.$element.on("submit." + this.type, !1, a.proxy(this.validate, this))
        },
        addListener: function(a) {
            for (var b in a) if (new RegExp("Field").test(b)) for (var c = 0; c < this.items.length; c++) this.items[c].addListener(a);
            else this.options.listeners[b] = a[b]
        },
        addItem: function(b) {
            if (a(b).is(this.options.excluded)) return ! 1;
            var c = a(b).parsley(this.options);
            c.setParent(this),
            this.items.push(c)
        },
        removeItem: function(b) {
            for (var c = a(b).parsley(), d = 0; d < this.items.length; d++) if (this.items[d].hash === c.hash) return this.items[d].destroy(),
            this.items.splice(d, 1),
            !0;
            return ! 1
        },
        validate: function(a) {
            var b = !0;
            this.focusedField = !1;
            for (var c = 0; c < this.items.length; c++)"undefined" != typeof this.items[c] && !1 === this.items[c].validate() && (b = !1, (!this.focusedField && "first" === this.options.focus || "last" === this.options.focus) && (this.focusedField = this.items[c].$element));
            this.focusedField && !b && this.focusedField.focus();
            var d = this.options.listeners.onFormSubmit(b, a, this);
            return "undefined" != typeof d ? d: b
        },
        isValid: function() {
            for (var a = 0; a < this.items.length; a++) if (!1 === this.items[a].isValid()) return ! 1;
            return ! 0
        },
        removeErrors: function() {
            for (var a = 0; a < this.items.length; a++) this.items[a].parsley("reset")
        },
        destroy: function() {
            for (var a = 0; a < this.items.length; a++) this.items[a].destroy();
            this.$element.off("." + this.type).removeData(this.type)
        },
        reset: function() {
            for (var a = 0; a < this.items.length; a++) this.items[a].reset()
        }
    },
    a.fn.parsley = function(b, f) {
        function g(g, i) {
            var j = a(g).data(i);
            if (!j) {
                switch (i) {
                case "parsleyForm":
                    j = new e(g, h, "parsleyForm");
                    break;
                case "parsleyField":
                    j = new c(g, h, "parsleyField");
                    break;
                case "parsleyFieldMultiple":
                    j = new d(g, h, "parsleyFieldMultiple");
                    break;
                default:
                    return
                }
                a(g).data(i, j)
            }
            if ("string" == typeof b && "function" == typeof j[b]) {
                var k = j[b](f);
                return "undefined" != typeof k ? k: a(g)
            }
            return j
        }
        var h = a.extend(!0, {},
        a.fn.parsley.defaults, "undefined" != typeof window.ParsleyConfig ? window.ParsleyConfig: {},
        b, this.data()),
        i = null;
        return a(this).is("form") || !0 === a(this).data("bind") ? i = g(a(this), "parsleyForm") : a(this).is(h.inputs) && !a(this).is(h.excluded) && (i = g(a(this), a(this).is("input[type=radio], input[type=checkbox]") ? "parsleyFieldMultiple": "parsleyField")),
        "function" == typeof f ? f() : i
    },
    a.fn.parsley.Constructor = e,
    a.fn.parsley.defaults = {
        inputs: "input, textarea, select",
        excluded: "input[type=hidden], input[type=file], :disabled",
        trigger: !1,
        animate: !0,
        animateDuration: 300,
        focus: "first",
        validationMinlength: 3,
        successClass: "parsley-success",
        errorClass: "parsley-error",
        errorMessage: !1,
        validators: {},
        showErrors: !0,
        messages: {},
        validateIfUnchanged: !1,
        errors: {
            classHandler: function() {},
            container: function() {},
            errorsWrapper: "<ul></ul>",
            errorElem: "<li></li>"
        },
        listeners: {
            onFieldValidate: function() {
                return ! 1
            },
            onFormSubmit: function() {},
            onFieldError: function() {},
            onFieldSuccess: function() {}
        }
    },
    a(window).on("load",
    function() {
        a('[data-validate="parsley"]').each(function() {
            a(this).parsley()
        })
    })
} (window.jQuery || window.Zepto),
$(function() {
    $(".sparkbar").sparkline("html", {
        type: "bar",
        disableHiddenCheck: !1,
        width: "36px",
        height: "36px"
    })
}),
$(function() {
    $(".bar-sparkline-btn").sparkline([[3, 5], [4, 7], [2, 5], [3, 5], [4, 7], [4, 7], [5, 7], [2, 7], [3, 5]], {
        type: "bar",
        height: "53px",
        barWidth: "5px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".bar-sparkline-btn-2").sparkline([[3, 5], [4, 7], [2, 5], [3, 5], [4, 7], [4, 7], [5, 7], [2, 7], [3, 5]], {
        type: "bar",
        height: "40px",
        barWidth: "3px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".bar-sparkline").sparkline([[4, 8], [2, 7], [2, 6], [2, 7], [3, 5], [2, 7], [2, 6], [2, 7], [3, 5], [4, 7], [2, 5], [3, 5], [4, 7], [4, 7], [5, 7], [4, 8], [2, 7], [2, 6], [2, 7], [3, 5]], {
        type: "bar",
        height: "35px",
        barWidth: "5px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".bar-sparkline-2").sparkline("html", {
        type: "bar",
        barColor: "black",
        height: "35px",
        barWidth: "5px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".tristate-sparkline").sparkline("html", {
        type: "tristate",
        barColor: "black",
        height: "35px",
        barWidth: "5px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".discrete-sparkline").sparkline("html", {
        type: "discrete",
        barColor: "black",
        height: "45px",
        barSpacing: "4px"
    })
}),
$(function() {
    $(".pie-sparkline").sparkline("html", {
        type: "pie",
        barColor: "black",
        height: "45px",
        width: "45px"
    })
}),
$(function() {
    $(".pie-sparkline-alt").sparkline("html", {
        type: "pie",
        width: "100",
        height: "100",
        sliceColors: ["#EFEFEF", "#5BCCF6", "#FA7753"],
        borderWidth: 0
    })
}),
$(function() {
    var a = [10, 8, 5, 7, 4, 4, 1];
    $(".dynamic-sparkline").sparkline(a, {
        height: "35px",
        width: "135px"
    })
}),
$(function() {
    var a = [10, 8, 5, 7, 4, 4, 1];
    $(".dynamic-sparkline-5").sparkline(a, {
        height: "57px",
        width: "100px"
    })
}),
$(function() {
    $(".tristate-sparkline-2").sparkline("html", {
        type: "tristate",
        posBarColor: "#ec6a00",
        negBarColor: "#ffc98a",
        zeroBarColor: "#000000",
        height: "35px",
        barWidth: "5px",
        barSpacing: "2px"
    })
}),
$(function() {
    $(".infobox-sparkline").sparkline([[3, 5], [4, 7], [2, 5], [3, 5], [4, 7], [4, 7], [5, 7], [2, 7], [3, 5]], {
        type: "bar",
        height: "53",
        barWidth: 5,
        barSpacing: 2,
        zeroAxis: !1,
        barColor: "#ccc",
        negBarColor: "#ddd",
        zeroColor: "#ccc",
        stackedBarColor: ["#871010", "#ffebeb"]
    })
}),
$(function() {
    $(".infobox-sparkline-2").sparkline([[3, 5], [4, 7], [2, 5], [3, 5], [4, 7], [4, 7], [5, 7], [2, 7], [3, 5]], {
        type: "bar",
        height: "53",
        barWidth: 5,
        barSpacing: 2,
        zeroAxis: !1,
        barColor: "#ccc",
        negBarColor: "#ddd",
        zeroColor: "#ccc",
        stackedBarColor: ["#000000", "#cccccc"]
    })
}),
$(function() {
    $(".infobox-sparkline-pie").sparkline([1.5, 2.5, 2], {
        type: "pie",
        width: "57",
        height: "57",
        sliceColors: ["#0d4f26", "#00712b", "#2eee76"],
        offset: 0,
        borderWidth: 0,
        borderColor: "#000000"
    })
}),
$(function() {
    $(".infobox-sparkline-tri").sparkline([1, 1, 0, 1, -1, -1, 1, -1, 0, 0, 2, 1], {
        type: "tristate",
        height: "53",
        posBarColor: "#1bb1fc",
        negBarColor: "#3d57ed",
        zeroBarColor: "#000000",
        barWidth: 5
    })
}),
$(function() {
    $(".sprk-1").sparkline("html", {
        type: "line",
        width: "50%",
        height: "65",
        lineColor: "#b2b2b2",
        fillColor: "#ffffff",
        lineWidth: 1,
        spotColor: "#0065ff",
        minSpotColor: "#0065ff",
        maxSpotColor: "#0065ff",
        spotRadius: 4
    })
}),
$(function() {
    $(".sparkline-big").sparkline("html", {
        type: "line",
        width: "85%",
        height: "80",
        highlightLineColor: "#ffffff",
        lineColor: "#ffffff",
        fillColor: "transparent",
        lineWidth: 1,
        spotColor: "#ffffff",
        minSpotColor: "#ffffff",
        maxSpotColor: "#ffffff",
        highlightSpotColor: "#000000",
        spotRadius: 4
    })
}),
$(function() {
    $(".sparkline-bar-big").sparkline("html", {
        type: "bar",
        width: "85%",
        height: "90",
        barWidth: 6,
        barSpacing: 2,
        zeroAxis: !1,
        barColor: "#ffffff",
        negBarColor: "#ffffff"
    })
}),
$(function() {
    $(".sparkline-bar-big-color").sparkline("html", {
        type: "bar",
        height: "90",
        width: "85%",
        barWidth: 6,
        barSpacing: 2,
        zeroAxis: !1,
        barColor: "#9CD159",
        negBarColor: "#9CD159"
    })
}),
$(function() {
    $(".sparkline-bar-big-color-2").sparkline([405, 450, 302, 405, 230, 311, 405, 342, 579, 405, 450, 302, 183, 579, 180, 311, 405, 342, 579, 405, 450, 302, 405, 230, 311, 405, 342, 579, 405, 450, 302, 405, 342, 432, 405, 450, 302, 183, 579, 180, 311, 405, 342, 579, 183, 579, 180, 311, 405, 342, 579, 405, 450, 302, 405, 230, 311, 405, 342, 579, 405, 450, 302, 405, 342, 432, 405, 450, 302, 183, 579, 180, 311, 405, 342, 579, 240, 180, 311, 450, 302, 370, 210], {
        type: "bar",
        height: "88",
        width: "85%",
        barWidth: 6,
        barSpacing: 2,
        zeroAxis: !1,
        barColor: "#9CD159",
        negBarColor: "#9CD159"
    })
});
var initPieChart = function() {
    $(".chart").easyPieChart({
        barColor: function(a) {
            return a /= 100,
            "rgb(" + Math.round(254 * (1 - a)) + ", " + Math.round(255 * a) + ", 0)"
        },
        animate: 1e3,
        scaleColor: "#ccc",
        lineWidth: 3,
        size: 100,
        lineCap: "cap",
        onStep: function(a) {
            this.$el.find("span").text(~~a)
        }
    }),
    $(".chart-alt").easyPieChart({
        barColor: function(a) {
            return a /= 100,
            "rgb(" + Math.round(255 * (1 - a)) + ", " + Math.round(255 * a) + ", 0)"
        },
        trackColor: "#333",
        scaleColor: !1,
        lineCap: "butt",
        rotate: -90,
        lineWidth: 20,
        animate: 1500,
        onStep: function(a) {
            this.$el.find("span").text(~~a)
        }
    }),
    $(".chart-alt-1").easyPieChart({
        barColor: function(a) {
            return a /= 100,
            "rgb(" + Math.round(255 * (1 - a)) + ", " + Math.round(255 * a) + ", 0)"
        },
        trackColor: "#e1ecf1",
        scaleColor: "#c4d7e0",
        lineCap: "cap",
        rotate: -90,
        lineWidth: 10,
        size: 80,
        animate: 2500,
        onStep: function(a) {
            this.$el.find("span").text(~~a)
        }
    }),
    $(".chart-alt-2").easyPieChart({
        barColor: function(a) {
            return a /= 100,
            "rgb(" + Math.round(255 * (1 - a)) + ", " + Math.round(255 * a) + ", 0)"
        },
        trackColor: "#fff",
        scaleColor: !1,
        lineCap: "butt",
        rotate: -90,
        lineWidth: 4,
        size: 50,
        animate: 1500,
        onStep: function(a) {
            this.$el.find("span").text(~~a)
        }
    }),
    $(".updateEasyPieChart").on("click",
    function(a) {
        a.preventDefault(),
        $(".chart, .chart-alt, .chart-alt-1, .chart-alt-2").each(function() {
            $(this).data("easyPieChart").update(Math.round(100 * Math.random()))
        })
    })
};
$(document).ready(function() {
    initPieChart()
}),
$(window).resize(function() {
    layoutFormatter()
}),
$(document).on("ready",
function() {
    if ($("body").hasClass("boxed-layout")) {
        var a = $(".boxed-layout #page-sidebar").offset().top;
        $(window).scroll(function() {
            var b = $(window).scrollTop();
            b > a ? $(".boxed-layout #page-sidebar").css({
                position: "fixed",
                top: 10
            }) : $(".boxed-layout #page-sidebar").css("position", "static")
        })
    }
}),
$(document).ready(function() {
    layoutFormatter(),
    $(function() {
        $("#responsive-open-menu").click(function() {
            $("#sidebar-menu").toggle()
        })
    }),
    $(function() {
        $("#sidebar-menu li").click(function() {
            $(this).is(".active") ? ($(this).removeClass("active"), $("div.sidebar-submenu", this).slideUp(200)) : ($("#sidebar-menu li div.sidebar-submenu").slideUp(200), $("div.sidebar-submenu", this).slideDown(200), $("#sidebar-menu li").removeClass("active"), $(this).addClass("active"))
        })
    }),
   /* $(function() {
        var a = window.location;
        $('#sidebar-menu a[href="' + a + '"]').parent("li").addClass("current-page"),
        $("#sidebar-menu a").filter(function() {
            return this.href == a
        }).parent("li").addClass("current-page").parent("ul").slideDown(200).parent().addClass("active")
    }),*/
    $(function() {
        $(".boxed-layout-btn").click(function() {
            $.cookie("boxedLayout", "on"),
            $("body").addClass("boxed-layout"),
            $(".boxed-layout-btn").addClass("hidden"),
            $(".fluid-layout-btn").removeClass("hidden")
        }),
        $(".fluid-layout-btn").click(function() {
            $.cookie("boxedLayout", "off"),
            $("body").removeClass("boxed-layout"),
            $(".fluid-layout-btn").addClass("hidden"),
            $(".boxed-layout-btn").removeClass("hidden"),
            $("#page-sidebar").css("position", "fixed")
        });
        var a = $.cookie("boxedLayout");
        "on" == a && ($("body").addClass("boxed-layout"), $(".boxed-layout-btn").addClass("hidden"), $(".fluid-layout-btn").removeClass("hidden"))
    }),
    $(function() {
        $("#close-sidebar").click(function() {
            $("#page-content-wrapper").animate({
                marginLeft: 0
            },
            300),
            $("body").addClass("close-sidebar"),
            $.cookie("closesidebar", "close"),
            $(this).addClass("hidden"),
            $("#rm-close-sidebar").removeClass("hidden")
        }),
        $("#rm-close-sidebar").click(function() {
            $("#page-content-wrapper").animate({
                marginLeft: 230
            },
            300),
            $("body").removeClass("close-sidebar"),
            $.cookie("closesidebar", "rm-close"),
            $(this).addClass("hidden"),
            $("#close-sidebar").removeClass("hidden")
        });
        var a = $.cookie("closesidebar");
        "close" == a && ($("#close-sidebar").addClass("hidden"), $("#rm-close-sidebar").removeClass("hidden"), $("body").addClass("close-sidebar"))
    })
}),
$(document).ready(function() {
    $(".choose-bg").click(function() {
        var a = $(this).attr("boxed-bg");
        $("body").css("background", a),
        $.cookie("set-boxed-bg", a)
    }),
    $(".change-layout-theme a").click(function() {
        var a = $(this).attr("layout-theme");
        $("#loading").slideDown({
            complete: function() {
                "" != a && ($("#layout-theme").attr("href", statics + "/themes/minified/fides/color-schemes/" + a + ".min.css"), $.cookie("set-layout-theme", a))
            }
        }),
        $("#loading").delay(1500).slideUp()
    }),
    themefromCookie(),
    bgFromCookie()
}),
$(function() {
    $(".change-theme-btn").click(function() {
        $(".theme-customizer").animate({
            right: "0"
        })
    }),
    $(".theme-customizer .theme-wrapper").click(function() {
        $(this).parent().animate({
            right: "-350"
        })
    })
}),
$(document).ready(function() {
    $(function() {
        $("#sidebar-search input").focus(function() {
            $(this).stop().animate({
                width: 200
            },
            "slow")
        }).blur(function() {
            $(this).stop().animate({
                width: 100
            },
            "slow")
        })
    }),
    $(function() {
        $("#form-wizard").smartWizard({
            transitionEffect: "slide"
        })
    }),
    $(function() {
        $(".sortable-elements").sortable()
    }),
    $(function() {
        $(".column-sort").sortable({
            connectWith: ".column-sort"
        })
    }),
    $(function() {
        $(".jcrop-basic").Jcrop()
    }),
    $(function() {
        $(".textarea-autosize").autosize()
    }),
    $(document).on("ready",
    function() {
        $(".scrollable-content").niceScroll({
            cursorborder: "transparent solid 2px",
            cursorwidth: "4",
            cursorcolor: "#363636",
            cursoropacitymax: "0.4",
            cursorborderradius: "2px"
        })
    }),
    $(document).on("ready",
    function() {
        $(".dataTables_scrollBody").niceScroll({
            cursorborder: "transparent solid 2px",
            cursorwidth: "4",
            cursorcolor: "#363636",
            cursoropacitymax: "0.4",
            cursorborderradius: "2px"
        })
    }),
    $(document).on("ready",
    function() {
        $(".scrollable-content").niceScroll({
            cursorborder: "transparent solid 2px",
            cursorwidth: "4",
            cursorcolor: "#363636",
            cursoropacitymax: "0.4",
            cursorborderradius: "2px"
        })
    }),
    $(function() {
        $(".tooltip-button").tooltip({
            container: "body"
        })
    }),
    $(function() {
        $(".popover-button").popover({
            container: "body",
            html: !0,
            animation: !0,
            content: function() {
                var a = $(this).attr("data-id");
                return $(a).html()
            }
        }).click(function(a) {
            a.preventDefault()
        })
    }),
    $(function() {
        $(".popover-button-default").popover({
            container: "body",
            html: !0,
            animation: !0
        }).click(function(a) {
            a.preventDefault()
        })
    }),
    $(function() {
        var a = [];
        a[""] = '<i class="glyph-icon icon-cog mrg5R"></i>This alert needs your attention, but it\'s not super important.',
        a.alert = '<i class="glyph-icon icon-cog mrg5R"></i>Best check yo self, you\'re not looking too good.',
        a.error = '<i class="glyph-icon icon-cog mrg5R"></i>Change a few things up and try submitting again.',
        a.success = '<i class="glyph-icon icon-cog mrg5R"></i>You successfully read this important alert message.',
        a.information = '<i class="glyph-icon icon-cog mrg5R"></i>This alert needs your attention, but it\'s not super important.',
        a.notification = '<i class="glyph-icon icon-cog mrg5R"></i>This alert needs your attention, but it\'s not super important.',
        a.warning = '<i class="glyph-icon icon-cog mrg5R"></i>Best check yo self, you\'re not looking too good.',
        $(".noty").click(function() {
            var b = $(this);
            return noty({
                text: a[b.data("type")],
                type: b.data("type"),
                dismissQueue: !0,
                theme: "agileUI",
                layout: b.data("layout")
            }),
            !1
        })
    }),
    $(function() {
        $(".colorpicker-position-bottom-left").minicolors({
            animationSpeed: 100,
            change: null,
            changeDelay: 0,
            control: "wheel",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !1,
            letterCase: "lowercase",
            opacity: !1,
            position: "bottom left",
            show: null,
            showSpeed: 100,
            textfield: !0,
            theme: "default"
        })
    }),
    $(function() {
        $(".colorpicker-position-bottom-right").minicolors({
            animationSpeed: 100,
            change: null,
            changeDelay: 0,
            control: "hue",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !1,
            letterCase: "lowercase",
            opacity: !1,
            position: "bottom right",
            show: null,
            showSpeed: 100,
            textfield: !0,
            theme: "default"
        })
    }),
    $(function() {
        $(".colorpicker-position-top-left").minicolors({
            animationSpeed: 100,
            change: null,
            changeDelay: 0,
            control: "saturation",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !1,
            letterCase: "lowercase",
            opacity: !0,
            position: "top left",
            show: null,
            showSpeed: 100,
            textfield: !0,
            theme: "default"
        })
    }),
    $(function() {
        $(".colorpicker-position-top-right").minicolors({
            animationSpeed: 100,
            change: null,
            changeDelay: 0,
            control: "brightness",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !1,
            letterCase: "lowercase",
            opacity: !0,
            position: "top right",
            show: null,
            showSpeed: 100,
            textfield: !0,
            theme: "default"
        })
    }),
    $(function() {
        $(".colorpicker-inline").minicolors({
            animationSpeed: 100,
            change: null,
            changeDelay: 0,
            control: "hue",
            defaultValue: "",
            hide: null,
            hideSpeed: 100,
            inline: !0,
            letterCase: "lowercase",
            opacity: !0,
            position: "bottom right",
            show: null,
            showSpeed: 100,
            textfield: !0,
            theme: "default"
        })
    }),
    $(function() {
        $(".growl-top-left").click(function() {
            $.jGrowl("Top left jGrowl notification with shadow and <b>.bg-black</b> background", {
                sticky: !1,
                position: "top-left",
                theme: "bg-black drop-shadow-alt"
            })
        }),
        $(".growl-top-right").click(function() {
            $.jGrowl("Top right jGrowl notification with <b>.bg-azure</b> background", {
                sticky: !1,
                position: "top-right",
                theme: "bg-azure btn text-left"
            })
        }),
        $(".growl-bottom-left").click(function() {
            $.jGrowl("Bottom left jGrowl notification with <b>.bg-red</b> background", {
                sticky: !1,
                position: "bottom-right",
                theme: "bg-red btn text-left"
            })
        }),
        $(".growl-bottom-right").click(function() {
            $.jGrowl("Bottom right jGrowl notification with <b>.bg-blue-alt</b> background", {
                sticky: !1,
                position: "bottom-left",
                theme: "bg-blue-alt btn text-left"
            })
        }),
        $(".growl-error").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "top-right",
                theme: "bg-red"
            })
        }),
        $(".growl-basic").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "bottom-right",
                theme: "primary-bg"
            })
        }),
        $(".growl-basic-secondary").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "bottom-right",
                theme: "ui-state-default"
            })
        }),
        $(".growl-success").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "top-right",
                theme: "bg-green"
            })
        }),
        $(".growl-warning").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "bottom-right",
                theme: "bg-orange"
            })
        }),
        $(".growl-info").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "top-right",
                theme: "bg-gray"
            })
        }),
        $(".growl-notice").click(function() {
            $.jGrowl("This is just a growl example using our custom color helpers for styling.", {
                sticky: !1,
                position: "top-right",
                theme: "bg-black"
            })
        })
    }),
    $(function() {
        $("#slider").slider({})
    }),
    $(function() {
        $("#horizontal-slider").slider({
            value: 40,
            orientation: "horizontal",
            range: "min",
            animate: !0
        })
    }),
    $(function() {
        $("#slider-range-vertical").slider({
            orientation: "vertical",
            range: !0,
            values: [17, 67],
            slide: function(a, b) {
                $("#amount-vertical-range").val("$" + b.values[0] + " - $" + b.values[1])
            }
        }),
        $("#amount-vertical-range").val("$" + $("#slider-range-vertical").slider("values", 0) + " - $" + $("#slider-range-vertical").slider("values", 1))
    }),
    $(function() {
        $("#slider-range").slider({
            range: !0,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function(a, b) {
                $("#amount").val("$" + b.values[0] + " - $" + b.values[1])
            }
        }),
        $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1))
    }),
    $(function() {
        $("#slider-vertical").slider({
            orientation: "vertical",
            range: "min",
            min: 0,
            max: 100,
            value: 60,
            slide: function(a, b) {
                $("#amount3").val(b.value)
            }
        }),
        $("#amount3").val($("#slider-vertical").slider("value"))
    }),
    $(function() {
        $("#master").slider({
            value: 60,
            orientation: "horizontal",
            range: "min",
            animate: !0
        }),
        $("#eq > span").each(function() {
            var a = parseInt($(this).text(), 10);
            $(this).empty().slider({
                value: a,
                range: "min",
                animate: !0,
                orientation: "vertical"
            })
        })
    }),
    $(function() {
        $(".sparkbar").sparkline("html", {
            type: "bar",
            disableHiddenCheck: !1,
            width: "36px",
            height: "36px"
        })
    }),
    $(function() {
        $(".basic-dialog").click(function() {
            $("#basic-dialog").dialog({
                resizable: !0,
                minWidth: 400,
                minHeight: 350,
                modal: !1,
                closeOnEscape: !0,
                buttons: {
                    OK: function() {
                        $(this).dialog("close")
                    }
                },
                position: "center"
            })
        }),
        $(".white-modal-60").click(function() {
            $("#white-modal-60").dialog({
                modal: !0,
                minWidth: 500,
                minHeight: 200,
                dialogClass: "",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-white opacity-60")
        }),
        $(".white-modal-80").click(function() {
            $("#white-modal-80").dialog({
                modal: !0,
                minWidth: 600,
                minHeight: 300,
                dialogClass: "modal-dialog",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-white opacity-80")
        }),
        $(".black-modal-60").click(function() {
            $("#black-modal-60").dialog({
                modal: !0,
                minWidth: 500,
                minHeight: 200,
                dialogClass: "modal-dialog",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-black opacity-60")
        }),
        $(".black-modal-80").click(function() {
            $("#black-modal-80").dialog({
                modal: !0,
                minWidth: 500,
                minHeight: 200,
                dialogClass: "no-shadow",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-black opacity-80")
        }),
        $(".red-modal-60").click(function() {
            $("#red-modal-60").dialog({
                modal: !0,
                minWidth: 500,
                minHeight: 200,
                dialogClass: "modal-dialog",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-red opacity-60")
        }),
        $(".dialog-tabs").click(function() {
            $("#dialog-tabs").dialog({
                modal: !1,
                minWidth: 650,
                minHeight: 200,
                dialogClass: "modal-dialog",
                show: "fadeIn"
            }),
            $(".ui-widget-overlay").addClass("bg-white opacity-60")
        })
    }),
    $(function() {
        $(".tabs").tabs({})
    }),
    $(function() {
        $(".tabs-hover").tabs({
            event: "mouseover"
        })
    }),
    $(function() {
        $(".accordion").accordion({
            heightStyle: "content"
        })
    }),
    $(function() {
        $("#accordion-hover").accordion({
            event: "mouseover",
            heightStyle: "auto"
        })
    }),
    $(function() {
        $("#accordion-with-tabs").accordion({
            active: 1,
            heightStyle: "content"
        })
    }),
    $(function() {
        $(".slider-demo").slider({})
    }),
    $(function() {
        $("#example1").dataTable({
            sScrollY: 300,
            bJQueryUI: !0,
            sPaginationType: "full_numbers"
        }),
        $(".dataTable .ui-icon-carat-2-n").addClass("icon-sort-up"),
        $(".dataTable .ui-icon-carat-2-s").addClass("icon-sort-down"),
        $(".dataTable .ui-icon-carat-2-n-s").addClass("icon-sort"),
        $(".dataTables_paginate a.first").html('<i class="icon-caret-left"></i>'),
        $(".dataTables_paginate a.previous").html('<i class="icon-angle-left"></i>'),
        $(".dataTables_paginate a.last").html('<i class="icon-caret-right"></i>'),
        $(".dataTables_paginate a.next").html('<i class="icon-angle-right"></i>')
    }),
    $(function() {
        var a = ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme"];
        $(".autocomplete-input").autocomplete({
            source: a
        })
    }),
    $(function() {
        function a(a) {
            return a.split(/,\s*/)
        }
        function b(b) {
            return a(b).pop()
        }
        var c = ["ActionScript", "AppleScript", "Asp", "BASIC", "C", "C++", "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran", "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl", "PHP", "Python", "Ruby", "Scala", "Scheme"];
        $("#sidebar-search input").bind("keydown",
        function(a) {
            a.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active && a.preventDefault()
        }).autocomplete({
            minLength: 0,
            source: function(a, d) {
                d($.ui.autocomplete.filter(c, b(a.term)))
            },
            focus: function() {
                return ! 1
            },
            select: function(b, c) {
                var d = a(this.value);
                return d.pop(),
                d.push(c.item.value),
                d.push(""),
                this.value = d.join(", "),
                !1
            },
            messages: {
                noResults: "",
                results: function() {}
            }
        })
    }),
    $(function() {
        $(".spinner-input").spinner()
    }),
    $(function() {
        $(".chosen-select").chosen(),
        $(".chosen-search").append('<i class="glyph-icon icon-search"></i>'),
        $(".chosen-single div").html('<i class="glyph-icon icon-caret-down"></i>')
    }),
    $(function() {
        $('input[type="checkbox"].custom-checkbox').uniform(),
        $('input[type="radio"].custom-radio').uniform(),
        $(".custom-select").uniform(),
        $(".selector").append('<i class="glyph-icon icon-caret-down"></i>'),
        $(".checker span").append('<i class="glyph-icon icon-ok"></i>').addClass("ui-state-default btn radius-all-4"),
        $(".radio span").append('<i class="glyph-icon icon-circle"></i>').addClass("ui-state-default btn radius-all-100")
    }),
    $(function() {
        $(".growl-button").click(function() {
            $.jGrowl("A message with a header", {
                header: "Important",
                sticky: !1,
                theme: "bg-black"
            })
        });
		 $(".icon-class").click(function() {
            $(this).next('input').get(0).click();
        });
		$('.fullscreen-btn').click(function(){
			if($(this).attr('data-original-title')=='全屏'){
				screenfull.request( );
				$(this).attr('data-original-title','退出全屏');
			}else{
				$(this).attr('data-original-title','全屏');
				screenfull.exit();
			}
			screenfull.onchange();
		});
    })
   
});
