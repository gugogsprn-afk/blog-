/*	SWFObject v2.2 <http://code.google.com/p/swfobject/> 
 is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
 */
var swfobject = function() {
    var D = "undefined",
        r = "object",
        S = "Shockwave Flash",
        W = "ShockwaveFlash.ShockwaveFlash",
        q = "application/x-shockwave-flash",
        R = "SWFObjectExprInst",
        x = "onreadystatechange",
        O = window,
        j = document,
        t = navigator,
        T = false,
        U = [h],
        o = [],
        N = [],
        I = [],
        l, Q, E, B, J = false,
        a = false,
        n, G, m = true,
        M = function() {
            var aa = typeof j.getElementById != D && typeof j.getElementsByTagName != D && typeof j.createElement != D,
                ah = t.userAgent.toLowerCase(),
                Y = t.platform.toLowerCase(),
                ae = Y ? /win/.test(Y) : /win/.test(ah),
                ac = Y ? /mac/.test(Y) : /mac/.test(ah),
                af = /webkit/.test(ah) ? parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/, "$1")) : false,
                X = !+"\v1",
                ag = [0, 0, 0],
                ab = null;
            if (typeof t.plugins != D && typeof t.plugins[S] == r) {
                ab = t.plugins[S].description;
                if (ab && !(typeof t.mimeTypes != D && t.mimeTypes[q] && !t.mimeTypes[q].enabledPlugin)) {
                    T = true;
                    X = false;
                    ab = ab.replace(/^.*\s+(\S+\s+\S+$)/, "$1");
                    ag[0] = parseInt(ab.replace(/^(.*)\..*$/, "$1"), 10);
                    ag[1] = parseInt(ab.replace(/^.*\.(.*)\s.*$/, "$1"), 10);
                    ag[2] = /[a-zA-Z]/.test(ab) ? parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/, "$1"), 10) : 0
                }
            } else {
                if (typeof O.ActiveXObject != D) {
                    try {
                        var ad = new ActiveXObject(W);
                        if (ad) {
                            ab = ad.GetVariable("$version");
                            if (ab) {
                                X = true;
                                ab = ab.split(" ")[1].split(",");
                                ag = [parseInt(ab[0], 10), parseInt(ab[1], 10), parseInt(ab[2], 10)]
                            }
                        }
                    } catch (Z) {}
                }
            }
            return {
                w3: aa,
                pv: ag,
                wk: af,
                ie: X,
                win: ae,
                mac: ac
            }
        }(),
        k = function() {
            if (!M.w3) {
                return
            }
            if ((typeof j.readyState != D && j.readyState == "complete") || (typeof j.readyState == D && (j.getElementsByTagName("body")[0] || j.body))) {
                f()
            }
            if (!J) {
                if (typeof j.addEventListener != D) {
                    j.addEventListener("DOMContentLoaded", f, false)
                }
                if (M.ie && M.win) {
                    j.attachEvent(x, function() {
                        if (j.readyState == "complete") {
                            j.detachEvent(x, arguments.callee);
                            f()
                        }
                    });
                    if (O == top) {
                        (function() {
                            if (J) {
                                return
                            }
                            try {
                                j.documentElement.doScroll("left")
                            } catch (X) {
                                setTimeout(arguments.callee, 0);
                                return
                            }
                            f()
                        })()
                    }
                }
                if (M.wk) {
                    (function() {
                        if (J) {
                            return
                        }
                        if (!/loaded|complete/.test(j.readyState)) {
                            setTimeout(arguments.callee, 0);
                            return
                        }
                        f()
                    })()
                }
                s(f)
            }
        }();

    function f() {
        if (J) {
            return
        }
        try {
            var Z = j.getElementsByTagName("body")[0].appendChild(C("span"));
            Z.parentNode.removeChild(Z)
        } catch (aa) {
            return
        }
        J = true;
        var X = U.length;
        for (var Y = 0; Y < X; Y++) {
            U[Y]()
        }
    }

    function K(X) {
        if (J) {
            X()
        } else {
            U[U.length] = X
        }
    }

    function s(Y) {
        if (typeof O.addEventListener != D) {
            O.addEventListener("load", Y, false)
        } else {
            if (typeof j.addEventListener != D) {
                j.addEventListener("load", Y, false)
            } else {
                if (typeof O.attachEvent != D) {
                    i(O, "onload", Y)
                } else {
                    if (typeof O.onload == "function") {
                        var X = O.onload;
                        O.onload = function() {
                            X();
                            Y()
                        }
                    } else {
                        O.onload = Y
                    }
                }
            }
        }
    }

    function h() {
        if (T) {
            V()
        } else {
            H()
        }
    }

    function V() {
        var X = j.getElementsByTagName("body")[0];
        var aa = C(r);
        aa.setAttribute("type", q);
        var Z = X.appendChild(aa);
        if (Z) {
            var Y = 0;
            (function() {
                if (typeof Z.GetVariable != D) {
                    var ab = Z.GetVariable("$version");
                    if (ab) {
                        ab = ab.split(" ")[1].split(",");
                        M.pv = [parseInt(ab[0], 10), parseInt(ab[1], 10), parseInt(ab[2], 10)]
                    }
                } else {
                    if (Y < 10) {
                        Y++;
                        setTimeout(arguments.callee, 10);
                        return
                    }
                }
                X.removeChild(aa);
                Z = null;
                H()
            })()
        } else {
            H()
        }
    }

    function H() {
        var ag = o.length;
        if (ag > 0) {
            for (var af = 0; af < ag; af++) {
                var Y = o[af].id;
                var ab = o[af].callbackFn;
                var aa = {
                    success: false,
                    id: Y
                };
                if (M.pv[0] > 0) {
                    var ae = c(Y);
                    if (ae) {
                        if (F(o[af].swfVersion) && !(M.wk && M.wk < 312)) {
                            w(Y, true);
                            if (ab) {
                                aa.success = true;
                                aa.ref = z(Y);
                                ab(aa)
                            }
                        } else {
                            if (o[af].expressInstall && A()) {
                                var ai = {};
                                ai.data = o[af].expressInstall;
                                ai.width = ae.getAttribute("width") || "0";
                                ai.height = ae.getAttribute("height") || "0";
                                if (ae.getAttribute("class")) {
                                    ai.styleclass = ae.getAttribute("class")
                                }
                                if (ae.getAttribute("align")) {
                                    ai.align = ae.getAttribute("align")
                                }
                                var ah = {};
                                var X = ae.getElementsByTagName("param");
                                var ac = X.length;
                                for (var ad = 0; ad < ac; ad++) {
                                    if (X[ad].getAttribute("name").toLowerCase() != "movie") {
                                        ah[X[ad].getAttribute("name")] = X[ad].getAttribute("value")
                                    }
                                }
                                P(ai, ah, Y, ab)
                            } else {
                                p(ae);
                                if (ab) {
                                    ab(aa)
                                }
                            }
                        }
                    }
                } else {
                    w(Y, true);
                    if (ab) {
                        var Z = z(Y);
                        if (Z && typeof Z.SetVariable != D) {
                            aa.success = true;
                            aa.ref = Z
                        }
                        ab(aa)
                    }
                }
            }
        }
    }

    function z(aa) {
        var X = null;
        var Y = c(aa);
        if (Y && Y.nodeName == "OBJECT") {
            if (typeof Y.SetVariable != D) {
                X = Y
            } else {
                var Z = Y.getElementsByTagName(r)[0];
                if (Z) {
                    X = Z
                }
            }
        }
        return X
    }

    function A() {
        return !a && F("6.0.65") && (M.win || M.mac) && !(M.wk && M.wk < 312)
    }

    function P(aa, ab, X, Z) {
        a = true;
        E = Z || null;
        B = {
            success: false,
            id: X
        };
        var ae = c(X);
        if (ae) {
            if (ae.nodeName == "OBJECT") {
                l = g(ae);
                Q = null
            } else {
                l = ae;
                Q = X
            }
            aa.id = R;
            if (typeof aa.width == D || (!/%$/.test(aa.width) && parseInt(aa.width, 10) < 310)) {
                aa.width = "310"
            }
            if (typeof aa.height == D || (!/%$/.test(aa.height) && parseInt(aa.height, 10) < 137)) {
                aa.height = "137"
            }
            j.title = j.title.slice(0, 47) + " - Flash Player Installation";
            var ad = M.ie && M.win ? "ActiveX" : "PlugIn",
                ac = "MMredirectURL=" + O.location.toString().replace(/&/g, "%26") + "&MMplayerType=" + ad + "&MMdoctitle=" + j.title;
            if (typeof ab.flashvars != D) {
                ab.flashvars += "&" + ac
            } else {
                ab.flashvars = ac
            }
            if (M.ie && M.win && ae.readyState != 4) {
                var Y = C("div");
                X += "SWFObjectNew";
                Y.setAttribute("id", X);
                ae.parentNode.insertBefore(Y, ae);
                ae.style.display = "none";
                (function() {
                    if (ae.readyState == 4) {
                        ae.parentNode.removeChild(ae)
                    } else {
                        setTimeout(arguments.callee, 10)
                    }
                })()
            }
            u(aa, ab, X)
        }
    }

    function p(Y) {
        if (M.ie && M.win && Y.readyState != 4) {
            var X = C("div");
            Y.parentNode.insertBefore(X, Y);
            X.parentNode.replaceChild(g(Y), X);
            Y.style.display = "none";
            (function() {
                if (Y.readyState == 4) {
                    Y.parentNode.removeChild(Y)
                } else {
                    setTimeout(arguments.callee, 10)
                }
            })()
        } else {
            Y.parentNode.replaceChild(g(Y), Y)
        }
    }

    function g(ab) {
        var aa = C("div");
        if (M.win && M.ie) {
            aa.innerHTML = ab.innerHTML
        } else {
            var Y = ab.getElementsByTagName(r)[0];
            if (Y) {
                var ad = Y.childNodes;
                if (ad) {
                    var X = ad.length;
                    for (var Z = 0; Z < X; Z++) {
                        if (!(ad[Z].nodeType == 1 && ad[Z].nodeName == "PARAM") && !(ad[Z].nodeType == 8)) {
                            aa.appendChild(ad[Z].cloneNode(true))
                        }
                    }
                }
            }
        }
        return aa
    }

    function u(ai, ag, Y) {
        var X, aa = c(Y);
        if (M.wk && M.wk < 312) {
            return X
        }
        if (aa) {
            if (typeof ai.id == D) {
                ai.id = Y
            }
            if (M.ie && M.win) {
                var ah = "";
                for (var ae in ai) {
                    if (ai[ae] != Object.prototype[ae]) {
                        if (ae.toLowerCase() == "data") {
                            ag.movie = ai[ae]
                        } else {
                            if (ae.toLowerCase() == "styleclass") {
                                ah += ' class="' + ai[ae] + '"'
                            } else {
                                if (ae.toLowerCase() != "classid") {
                                    ah += " " + ae + '="' + ai[ae] + '"'
                                }
                            }
                        }
                    }
                }
                var af = "";
                for (var ad in ag) {
                    if (ag[ad] != Object.prototype[ad]) {
                        af += '<param name="' + ad + '" value="' + ag[ad] + '" />'
                    }
                }
                aa.outerHTML = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"' + ah + ">" + af + "</object>";
                N[N.length] = ai.id;
                X = c(ai.id)
            } else {
                var Z = C(r);
                Z.setAttribute("type", q);
                for (var ac in ai) {
                    if (ai[ac] != Object.prototype[ac]) {
                        if (ac.toLowerCase() == "styleclass") {
                            Z.setAttribute("class", ai[ac])
                        } else {
                            if (ac.toLowerCase() != "classid") {
                                Z.setAttribute(ac, ai[ac])
                            }
                        }
                    }
                }
                for (var ab in ag) {
                    if (ag[ab] != Object.prototype[ab] && ab.toLowerCase() != "movie") {
                        e(Z, ab, ag[ab])
                    }
                }
                aa.parentNode.replaceChild(Z, aa);
                X = Z
            }
        }
        return X
    }

    function e(Z, X, Y) {
        var aa = C("param");
        aa.setAttribute("name", X);
        aa.setAttribute("value", Y);
        Z.appendChild(aa)
    }

    function y(Y) {
        var X = c(Y);
        if (X && X.nodeName == "OBJECT") {
            if (M.ie && M.win) {
                X.style.display = "none";
                (function() {
                    if (X.readyState == 4) {
                        b(Y)
                    } else {
                        setTimeout(arguments.callee, 10)
                    }
                })()
            } else {
                X.parentNode.removeChild(X)
            }
        }
    }

    function b(Z) {
        var Y = c(Z);
        if (Y) {
            for (var X in Y) {
                if (typeof Y[X] == "function") {
                    Y[X] = null
                }
            }
            Y.parentNode.removeChild(Y)
        }
    }

    function c(Z) {
        var X = null;
        try {
            X = j.getElementById(Z)
        } catch (Y) {}
        return X
    }

    function C(X) {
        return j.createElement(X)
    }

    function i(Z, X, Y) {
        Z.attachEvent(X, Y);
        I[I.length] = [Z, X, Y]
    }

    function F(Z) {
        var Y = M.pv,
            X = Z.split(".");
        X[0] = parseInt(X[0], 10);
        X[1] = parseInt(X[1], 10) || 0;
        X[2] = parseInt(X[2], 10) || 0;
        return (Y[0] > X[0] || (Y[0] == X[0] && Y[1] > X[1]) || (Y[0] == X[0] && Y[1] == X[1] && Y[2] >= X[2])) ? true : false
    }

    function v(ac, Y, ad, ab) {
        if (M.ie && M.mac) {
            return
        }
        var aa = j.getElementsByTagName("head")[0];
        if (!aa) {
            return
        }
        var X = (ad && typeof ad == "string") ? ad : "screen";
        if (ab) {
            n = null;
            G = null
        }
        if (!n || G != X) {
            var Z = C("style");
            Z.setAttribute("type", "text/css");
            Z.setAttribute("media", X);
            n = aa.appendChild(Z);
            if (M.ie && M.win && typeof j.styleSheets != D && j.styleSheets.length > 0) {
                n = j.styleSheets[j.styleSheets.length - 1]
            }
            G = X
        }
        if (M.ie && M.win) {
            if (n && typeof n.addRule == r) {
                n.addRule(ac, Y)
            }
        } else {
            if (n && typeof j.createTextNode != D) {
                n.appendChild(j.createTextNode(ac + " {" + Y + "}"))
            }
        }
    }

    function w(Z, X) {
        if (!m) {
            return
        }
        var Y = X ? "visible" : "hidden";
        if (J && c(Z)) {
            c(Z).style.visibility = Y
        } else {
            v("#" + Z, "visibility:" + Y)
        }
    }

    function L(Y) {
        var Z = /[\\\"<>\.;]/;
        var X = Z.exec(Y) != null;
        return X && typeof encodeURIComponent != D ? encodeURIComponent(Y) : Y
    }
    var d = function() {
        if (M.ie && M.win) {
            window.attachEvent("onunload", function() {
                var ac = I.length;
                for (var ab = 0; ab < ac; ab++) {
                    I[ab][0].detachEvent(I[ab][1], I[ab][2])
                }
                var Z = N.length;
                for (var aa = 0; aa < Z; aa++) {
                    y(N[aa])
                }
                for (var Y in M) {
                    M[Y] = null
                }
                M = null;
                for (var X in swfobject) {
                    swfobject[X] = null
                }
                swfobject = null
            })
        }
    }();
    return {
        registerObject: function(ab, X, aa, Z) {
            if (M.w3 && ab && X) {
                var Y = {};
                Y.id = ab;
                Y.swfVersion = X;
                Y.expressInstall = aa;
                Y.callbackFn = Z;
                o[o.length] = Y;
                w(ab, false)
            } else {
                if (Z) {
                    Z({
                        success: false,
                        id: ab
                    })
                }
            }
        },
        getObjectById: function(X) {
            if (M.w3) {
                return z(X)
            }
        },
        embedSWF: function(ab, ah, ae, ag, Y, aa, Z, ad, af, ac) {
            var X = {
                success: false,
                id: ah
            };
            if (M.w3 && !(M.wk && M.wk < 312) && ab && ah && ae && ag && Y) {
                w(ah, false);
                K(function() {
                    ae += "";
                    ag += "";
                    var aj = {};
                    if (af && typeof af === r) {
                        for (var al in af) {
                            aj[al] = af[al]
                        }
                    }
                    aj.data = ab;
                    aj.width = ae;
                    aj.height = ag;
                    var am = {};
                    if (ad && typeof ad === r) {
                        for (var ak in ad) {
                            am[ak] = ad[ak]
                        }
                    }
                    if (Z && typeof Z === r) {
                        for (var ai in Z) {
                            if (typeof am.flashvars != D) {
                                am.flashvars += "&" + ai + "=" + Z[ai]
                            } else {
                                am.flashvars = ai + "=" + Z[ai]
                            }
                        }
                    }
                    if (F(Y)) {
                        var an = u(aj, am, ah);
                        if (aj.id == ah) {
                            w(ah, true)
                        }
                        X.success = true;
                        X.ref = an
                    } else {
                        if (aa && A()) {
                            aj.data = aa;
                            P(aj, am, ah, ac);
                            return
                        } else {
                            w(ah, true)
                        }
                    }
                    if (ac) {
                        ac(X)
                    }
                })
            } else {
                if (ac) {
                    ac(X)
                }
            }
        },
        switchOffAutoHideShow: function() {
            m = false
        },
        ua: M,
        getFlashPlayerVersion: function() {
            return {
                major: M.pv[0],
                minor: M.pv[1],
                release: M.pv[2]
            }
        },
        hasFlashPlayerVersion: F,
        createSWF: function(Z, Y, X) {
            if (M.w3) {
                return u(Z, Y, X)
            } else {
                return undefined
            }
        },
        showExpressInstall: function(Z, aa, X, Y) {
            if (M.w3 && A()) {
                P(Z, aa, X, Y)
            }
        },
        removeSWF: function(X) {
            if (M.w3) {
                y(X)
            }
        },
        createCSS: function(aa, Z, Y, X) {
            if (M.w3) {
                v(aa, Z, Y, X)
            }
        },
        addDomLoadEvent: K,
        addLoadEvent: s,
        getQueryParamValue: function(aa) {
            var Z = j.location.search || j.location.hash;
            if (Z) {
                if (/\?/.test(Z)) {
                    Z = Z.split("?")[1]
                }
                if (aa == null) {
                    return L(Z)
                }
                var Y = Z.split("&");
                for (var X = 0; X < Y.length; X++) {
                    if (Y[X].substring(0, Y[X].indexOf("=")) == aa) {
                        return L(Y[X].substring((Y[X].indexOf("=") + 1)))
                    }
                }
            }
            return ""
        },
        expressInstallCallback: function() {
            if (a) {
                var X = c(R);
                if (X && l) {
                    X.parentNode.replaceChild(l, X);
                    if (Q) {
                        w(Q, true);
                        if (M.ie && M.win) {
                            l.style.display = "block"
                        }
                    }
                    if (E) {
                        E(B)
                    }
                }
                a = false
            }
        }
    }
}();;
try {
    (function(window) {
        'use strict';
        var document = window.document,
            Image = window.Image,
            globalStorage = window.globalStorage,
            swfobject = window.swfobject;
        try {
            var localStore = window.localStorage
        } catch (ex) {}
        try {
            var sessionStorage = window.sessionStorage;
        } catch (e) {}

        function newImage(src) {
            var img = new Image();
            img.style.visibility = "hidden";
            img.style.position = "absolute";
            img.src = src;
        }

        function _ec_replace(str, key, value) {
            if (str.indexOf("&" + key + "=") > -1 || str.indexOf(key + "=") === 0) {
                var idx = str.indexOf("&" + key + "="),
                    end, newstr;
                if (idx === -1) {
                    idx = str.indexOf(key + "=");
                }
                end = str.indexOf("&", idx + 1);
                if (end !== -1) {
                    newstr = str.substr(0, idx) + str.substr(end + (idx ? 0 : 1)) + "&" + key + "=" + value;
                } else {
                    newstr = str.substr(0, idx) + "&" + key + "=" + value;
                }
                return newstr;
            } else {
                return str + "&" + key + "=" + value;
            }
        }

        function idb() {
            if ('indexedDB' in window) {
                return true
            } else if (window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB) {
                return true
            } else {
                return false
            }
        }
        var _global_lso;

        function _evercookie_flash_var(cookie) {
            _global_lso = cookie;
            var swf = document.getElementById("myswf");
            if (swf && swf.parentNode) {
                swf.parentNode.removeChild(swf);
            }
        }
        var _global_isolated;

        function onSilverlightLoad(sender, args) {
            var control = sender.getHost();
            _global_isolated = control.Content.App.getIsolatedStorage();
        }

        function onSilverlightError(sender, args) {
            _global_isolated = "";
        }
        var defaultOptionMap = {
            history: false,
            java: false,
            tests: 1,
            silverlight: false,
            domain: '.' + window.location.host.replace(/:\d+/, ''),
            baseurl: '',
            asseturi: '/assets',
            phpuri: '/plugin/ec/php',
            authPath: false,
            pngCookieName: '_bm_uid_png',
            pngPath: '/evercookie_png.php',
            etagCookieName: '_bm_uid_etag',
            etagPath: '/evercookie_etag.php',
            cacheCookieName: '_bm_uid_cache',
            cachePath: '/evercookie_cache.php'
        };
        var _baseKeyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

        function Evercookie(options) {
            options = options || {};
            var opts = {};
            for (var key in defaultOptionMap) {
                var optValue = options[key];
                if (typeof optValue !== 'undefined') {
                    opts[key] = optValue
                } else {
                    opts[key] = defaultOptionMap[key];
                }
            }
            if (typeof opts.domain === 'function') {
                opts.domain = opts.domain(window);
            }
            var _ec_history = opts.history,
                _ec_java = opts.java,
                _ec_tests = opts.tests,
                _ec_baseurl = opts.baseurl,
                _ec_asseturi = opts.asseturi,
                _ec_phpuri = opts.phpuri,
                _ec_domain = opts.domain;
            var self = this;
            this._ec = {};
            this.get = function(name, cb, dont_reset) {
                self._evercookie(name, cb, undefined, undefined, dont_reset);
            };
            this.set = function(name, value) {
                self._evercookie(name, function() {}, value);
            };
            this._evercookie = function(name, cb, value, i, dont_reset) {
                if (self._evercookie === undefined) {
                    self = this;
                }
                if (i === undefined) {
                    i = 0;
                }
                if (i === 0) {
                    self.evercookie_database_storage(name, value);
                    self.evercookie_png(name, value);
                    self.evercookie_etag(name, value);
                    self.evercookie_cache(name, value);
                    self.evercookie_lso(name, value);
                    if (opts.silverlight) {
                        self.evercookie_silverlight(name, value);
                    }
                    if (opts.authPath) {
                        self.evercookie_auth(name, value);
                    }
                    if (_ec_java) {
                        self.evercookie_java(name, value);
                    }
                    self._ec.userData = self.evercookie_userdata(name, value);
                    self._ec.cookieData = self.evercookie_cookie(name, value);
                    self._ec.localData = self.evercookie_local_storage(name, value);
                    self._ec.globalData = self.evercookie_global_storage(name, value);
                    self._ec.sessionData = self.evercookie_session_storage(name, value);
                    self._ec.windowData = self.evercookie_window(name, value);
                    if (_ec_history) {
                        self._ec.historyData = self.evercookie_history(name, value);
                    }
                }
                if (value !== undefined) {
                    if ((typeof _global_lso === "undefined" || typeof _global_isolated === "undefined") && i++ < _ec_tests) {
                        setTimeout(function() {
                            self._evercookie(name, cb, value, i, dont_reset);
                        }, 300);
                    }
                } else {
                    if (((window.openDatabase && typeof self._ec.dbData === "undefined") || (idb() && (typeof self._ec.idbData === "undefined" || self._ec.idbData === "")) || (typeof _global_lso === "undefined") || (typeof self._ec.etagData === "undefined") || (typeof self._ec.cacheData === "undefined") || (typeof self._ec.javaData === "undefined") || (document.createElement("canvas").getContext && (typeof self._ec.pngData === "undefined" || self._ec.pngData === "")) || (typeof _global_isolated === "undefined")) && i++ < _ec_tests) {
                        setTimeout(function() {
                            self._evercookie(name, cb, value, i, dont_reset);
                        }, 300);
                    } else {
                        self._ec.lsoData = self.getFromStr(name, _global_lso);
                        _global_lso = undefined;
                        self._ec.slData = self.getFromStr(name, _global_isolated);
                        _global_isolated = undefined;
                        var tmpec = self._ec,
                            candidates = [],
                            bestnum = 0,
                            candidate, item;
                        self._ec = {};
                        for (item in tmpec) {
                            if (tmpec[item] && tmpec[item] !== "null" && tmpec[item] !== "undefined") {
                                candidates[tmpec[item]] = candidates[tmpec[item]] === undefined ? 1 : candidates[tmpec[item]] + 1;
                            }
                        }
                        for (item in candidates) {
                            if (candidates[item] > bestnum) {
                                bestnum = candidates[item];
                                candidate = item;
                            }
                        }
                        if (candidate !== undefined && (dont_reset === undefined || dont_reset !== 1)) {
                            self.set(name, candidate);
                        }
                        if (typeof cb === "function") {
                            cb(candidate, tmpec);
                        }
                    }
                }
            };
            this.evercookie_window = function(name, value) {
                try {
                    if (value !== undefined) {
                        window.name = _ec_replace(window.name, name, value);
                    } else {
                        return this.getFromStr(name, window.name);
                    }
                } catch (e) {}
            };
            this.evercookie_userdata = function(name, value) {
                try {
                    var elm = this.createElem("div", "userdata_el", 1);
                    elm.style.behavior = "url(#default#userData)";
                    if (value !== undefined) {
                        elm.setAttribute(name, value);
                        elm.save(name);
                    } else {
                        elm.load(name);
                        return elm.getAttribute(name);
                    }
                } catch (e) {}
            };
            this.ajax = function(settings) {
                var headers, name, transports, transport, i, length;
                headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/javascript, text/html, application/xml, text/xml, */*'
                };
                transports = [function() {
                    return new XMLHttpRequest();
                }, function() {
                    return new ActiveXObject('Msxml2.XMLHTTP');
                }, function() {
                    return new ActiveXObject('Microsoft.XMLHTTP');
                }];
                for (i = 0, length = transports.length; i < length; i++) {
                    transport = transports[i];
                    try {
                        transport = transport();
                        break;
                    } catch (e) {}
                }
                transport.onreadystatechange = function() {
                    if (transport.readyState !== 4) {
                        return;
                    }
                    settings.success(transport.responseText);
                };
                transport.open('get', settings.url, true);
                for (name in headers) {
                    transport.setRequestHeader(name, headers[name]);
                }
                transport.send();
            };
            this.evercookie_cache = function(name, value) {
                if (value !== undefined) {
                    document.cookie = opts.cacheCookieName + "=" + value + "; path=/; domain=" + _ec_domain;
                    self.ajax({
                        url: _ec_baseurl + _ec_phpuri + opts.cachePath + "?name=" + name,
                        success: function(data) {}
                    });
                } else {
                    var origvalue = this.getFromStr(opts.cacheCookieName, document.cookie);
                    self._ec.cacheData = undefined;
                    document.cookie = opts.cacheCookieName + "=; expires=Mon, 20 Sep 2010 00:00:00 UTC; path=/; domain=" + _ec_domain;
                    self.ajax({
                        url: _ec_baseurl + _ec_phpuri + opts.cachePath + "?name=" + name,
                        success: function(data) {
                            document.cookie = opts.cacheCookieName + "=" + origvalue + "; expires=Tue, 31 Dec 2030 00:00:00 UTC; path=/; domain=" + _ec_domain;
                            self._ec.cacheData = data;
                        }
                    });
                }
            };
            this.evercookie_auth = function(name, value) {
                if (value !== undefined) {
                    newImage('//' + value + '@' + location.host + _ec_baseurl + _ec_phpuri + opts.authPath + "?name=" + name);
                } else {
                    self.ajax({
                        url: _ec_baseurl + _ec_phpuri + opts.authPath + "?name=" + name,
                        success: function(data) {
                            self._ec.authData = data;
                        }
                    });
                }
            };
            this.evercookie_etag = function(name, value) {
                if (value !== undefined) {
                    document.cookie = opts.etagCookieName + "=" + value + "; path=/; domain=" + _ec_domain;
                    self.ajax({
                        url: _ec_baseurl + _ec_phpuri + opts.etagPath + "?name=" + name,
                        success: function(data) {}
                    });
                } else {
                    var origvalue = this.getFromStr(opts.etagCookieName, document.cookie);
                    self._ec.etagData = undefined;
                    document.cookie = opts.etagCookieName + "=; expires=Mon, 20 Sep 2010 00:00:00 UTC; path=/; domain=" + _ec_domain;
                    self.ajax({
                        url: _ec_baseurl + _ec_phpuri + opts.etagPath + "?name=" + name,
                        success: function(data) {
                            document.cookie = opts.etagCookieName + "=" + origvalue + "; expires=Tue, 31 Dec 2030 00:00:00 UTC; path=/; domain=" + _ec_domain;
                            self._ec.etagData = data;
                        }
                    });
                }
            };
            this.evercookie_java = function(name, value) {
                var div = document.getElementById("ecAppletContainer");
                if (typeof dtjava === "undefined") {
                    return;
                }
                if (div === null || div === undefined || !div.length) {
                    div = document.createElement("div");
                    div.setAttribute("id", "ecAppletContainer");
                    div.style.position = "absolute";
                    div.style.top = "-3000px";
                    div.style.left = "-3000px";
                    div.style.width = "1px";
                    div.style.height = "1px";
                    document.body.appendChild(div);
                }
                if (typeof ecApplet === "undefined") {
                    dtjava.embed({
                        id: "ecApplet",
                        url: _ec_baseurl + _ec_asseturi + "/evercookie.jnlp",
                        width: "1px",
                        height: "1px",
                        placeholder: "ecAppletContainer"
                    }, {}, {
                        onJavascriptReady: doSetOrGet
                    });
                } else {
                    doSetOrGet("ecApplet");
                }

                function doSetOrGet(appletId) {
                    var applet = document.getElementById(appletId);
                    if (value !== undefined) {
                        applet.set(name, value);
                    } else {
                        self._ec.javaData = applet.get(name);
                    }
                }
            };
            this.evercookie_lso = function(name, value) {
                var div = document.getElementById("swfcontainer"),
                    flashvars = {},
                    params = {},
                    attributes = {};
                if (div === null || div === undefined || !div.length) {
                    div = document.createElement("div");
                    div.setAttribute("id", "swfcontainer");
                    document.body.appendChild(div);
                }
                if (value !== undefined) {
                    flashvars.everdata = name + "=" + value;
                }
                params.swliveconnect = "true";
                attributes.id = "myswf";
                attributes.name = "myswf";
                swfobject.embedSWF(_ec_baseurl + _ec_asseturi + "/evercookie.swf", "swfcontainer", "1", "1", "9.0.0", false, flashvars, params, attributes);
            };
            this.evercookie_png = function(name, value) {
                var canvas = document.createElement("canvas"),
                    img, ctx, origvalue;
                canvas.style.visibility = "hidden";
                canvas.style.position = "absolute";
                canvas.width = 200;
                canvas.height = 1;
                if (canvas && canvas.getContext) {
                    img = new Image();
                    img.style.visibility = "hidden";
                    img.style.position = "absolute";
                    if (value !== undefined) {
                        document.cookie = opts.pngCookieName + "=" + value + "; path=/; domain=" + _ec_domain;
                    } else {
                        self._ec.pngData = undefined;
                        ctx = canvas.getContext("2d");
                        origvalue = this.getFromStr(opts.pngCookieName, document.cookie);
                        document.cookie = opts.pngCookieName + "=; expires=Mon, 20 Sep 2010 00:00:00 UTC; path=/; domain=" + _ec_domain;
                        img.onload = function() {
                            document.cookie = opts.pngCookieName + "=" + origvalue + "; expires=Tue, 31 Dec 2030 00:00:00 UTC; path=/; domain=" + _ec_domain;
                            self._ec.pngData = "";
                            ctx.drawImage(img, 0, 0);
                            var imgd = ctx.getImageData(0, 0, 200, 1),
                                pix = imgd.data,
                                i, n;
                            for (i = 0, n = pix.length; i < n; i += 4) {
                                if (pix[i] === 0) {
                                    break;
                                }
                                self._ec.pngData += String.fromCharCode(pix[i]);
                                if (pix[i + 1] === 0) {
                                    break;
                                }
                                self._ec.pngData += String.fromCharCode(pix[i + 1]);
                                if (pix[i + 2] === 0) {
                                    break;
                                }
                                self._ec.pngData += String.fromCharCode(pix[i + 2]);
                            }
                        };
                    }
                    img.src = _ec_baseurl + _ec_phpuri + opts.pngPath + "?name=" + name;
                }
            };
            this.evercookie_local_storage = function(name, value) {
                try {
                    if (localStore) {
                        if (value !== undefined) {
                            localStore.setItem(name, value);
                        } else {
                            return localStore.getItem(name);
                        }
                    }
                } catch (e) {}
            };
            this.evercookie_database_storage = function(name, value) {
                try {
                    if (window.openDatabase) {
                        var database = window.openDatabase("sqlite_evercookie", "", "evercookie", 1024 * 1024);
                        if (value !== undefined) {
                            database.transaction(function(tx) {
                                tx.executeSql("CREATE TABLE IF NOT EXISTS cache(" + "id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, " + "name TEXT NOT NULL, " + "value TEXT NOT NULL, " + "UNIQUE (name)" + ")", [], function(tx, rs) {}, function(tx, err) {});
                                tx.executeSql("INSERT OR REPLACE INTO cache(name, value) " + "VALUES(?, ?)", [name, value], function(tx, rs) {}, function(tx, err) {});
                            });
                        } else {
                            database.transaction(function(tx) {
                                tx.executeSql("SELECT value FROM cache WHERE name=?", [name], function(tx, result1) {
                                    if (result1.rows.length >= 1) {
                                        self._ec.dbData = result1.rows.item(0).value;
                                    } else {
                                        self._ec.dbData = "";
                                    }
                                }, function(tx, err) {});
                            });
                        }
                    }
                } catch (e) {}
            };
            this.evercookie_indexdb_storage = function(name, value) {
                try {
                    if (!('indexedDB' in window)) {
                        indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
                        IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
                        IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
                    }
                    if (indexedDB) {
                        var ver = 1;
                        var request = indexedDB.open("idb_evercookie", ver);
                        request.onerror = function(e) {;
                        }
                        request.onupgradeneeded = function(event) {
                            var db = event.target.result;
                            var store = db.createObjectStore("evercookie", {
                                keyPath: "name",
                                unique: false
                            })
                        }
                        if (value !== undefined) {
                            request.onsuccess = function(event) {
                                var idb = event.target.result;
                                if (idb.objectStoreNames.contains("evercookie")) {
                                    var tx = idb.transaction(["evercookie"], "readwrite");
                                    var objst = tx.objectStore("evercookie");
                                    var qr = objst.put({
                                        "name": name,
                                        "value": value
                                    })
                                }
                                idb.close();
                            }
                        } else {
                            request.onsuccess = function(event) {
                                var idb = event.target.result;
                                if (!idb.objectStoreNames.contains("evercookie")) {
                                    self._ec.idbData = undefined;
                                } else {
                                    var tx = idb.transaction(["evercookie"]);
                                    var objst = tx.objectStore("evercookie");
                                    var qr = objst.get(name);
                                    qr.onsuccess = function(event) {
                                        if (qr.result === undefined) {
                                            self._ec.idbData = undefined
                                        } else {
                                            self._ec.idbData = qr.result.value;
                                        }
                                    }
                                }
                                idb.close();
                            }
                        }
                    }
                } catch (e) {}
            };
            this.evercookie_session_storage = function(name, value) {
                try {
                    if (sessionStorage) {
                        if (value !== undefined) {
                            sessionStorage.setItem(name, value);
                        } else {
                            return sessionStorage.getItem(name);
                        }
                    }
                } catch (e) {}
            };
            this.evercookie_global_storage = function(name, value) {
                if (globalStorage) {
                    var host = this.getHost();
                    try {
                        if (value !== undefined) {
                            globalStorage[host][name] = value;
                        } else {
                            return globalStorage[host][name];
                        }
                    } catch (e) {}
                }
            };
            this.evercookie_silverlight = function(name, value) {
                var source = _ec_baseurl + _ec_asseturi + "/evercookie.xap",
                    minver = "4.0.50401.0",
                    initParam = "",
                    html;
                if (value !== undefined) {
                    initParam = '<param name="initParams" value="' + name + '=' + value + '" />';
                }
                html = '<object style="position:absolute;left:-500px;top:-500px" data="data:application/x-silverlight-2," type="application/x-silverlight-2" id="mysilverlight" width="0" height="0">' +
                    initParam + '<param name="source" value="' + source + '"/>' + '<param name="onLoad" value="onSilverlightLoad"/>' + '<param name="onError" value="onSilverlightError"/>' + '<param name="background" value="Transparent"/>' + '<param name="windowless" value="true"/>' + '<param name="minRuntimeVersion" value="' + minver + '"/>' + '<param name="autoUpgrade" value="false"/>' + '<a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=' + minver + '" style="display:none">' + 'Get Microsoft Silverlight' + '</a>' + '</object>';
                try {
                    if (typeof jQuery === 'undefined') {
                        document.body.appendChild(html);
                    } else {
                        $('body').append(html);
                    }
                } catch (ex) {}
            };
            this.encode = function(input) {
                var output = "",
                    chr1, chr2, chr3, enc1, enc2, enc3, enc4, i = 0;
                input = this._utf8_encode(input);
                while (i < input.length) {
                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);
                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;
                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }
                    output = output +
                        _baseKeyStr.charAt(enc1) + _baseKeyStr.charAt(enc2) +
                        _baseKeyStr.charAt(enc3) + _baseKeyStr.charAt(enc4);
                }
                return output;
            };
            this.decode = function(input) {
                var output = "",
                    chr1, chr2, chr3, enc1, enc2, enc3, enc4, i = 0;
                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
                while (i < input.length) {
                    enc1 = _baseKeyStr.indexOf(input.charAt(i++));
                    enc2 = _baseKeyStr.indexOf(input.charAt(i++));
                    enc3 = _baseKeyStr.indexOf(input.charAt(i++));
                    enc4 = _baseKeyStr.indexOf(input.charAt(i++));
                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;
                    output = output + String.fromCharCode(chr1);
                    if (enc3 !== 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 !== 64) {
                        output = output + String.fromCharCode(chr3);
                    }
                }
                output = this._utf8_decode(output);
                return output;
            };
            this._utf8_encode = function(str) {
                str = str.replace(/\r\n/g, "\n");
                var utftext = "",
                    i = 0,
                    n = str.length,
                    c;
                for (; i < n; i++) {
                    c = str.charCodeAt(i);
                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    } else if ((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    } else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                }
                return utftext;
            };
            this._utf8_decode = function(utftext) {
                var str = "",
                    i = 0,
                    n = utftext.length,
                    c = 0,
                    c1 = 0,
                    c2 = 0,
                    c3 = 0;
                while (i < n) {
                    c = utftext.charCodeAt(i);
                    if (c < 128) {
                        str += String.fromCharCode(c);
                        i += 1;
                    } else if ((c > 191) && (c < 224)) {
                        c2 = utftext.charCodeAt(i + 1);
                        str += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                        i += 2;
                    } else {
                        c2 = utftext.charCodeAt(i + 1);
                        c3 = utftext.charCodeAt(i + 2);
                        str += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                        i += 3;
                    }
                }
                return str;
            };
            this.evercookie_history = function(name, value) {
                var baseElems = (_baseKeyStr + "-").split(""),
                    url = "http://www.google.com/evercookie/cache/" + this.getHost() + "/" + name,
                    i, base, letter = "",
                    val = "",
                    found = 1;
                if (value !== undefined) {
                    if (this.hasVisited(url)) {
                        return;
                    }
                    this.createIframe(url, "if");
                    url = url + "/";
                    base = this.encode(value).split("");
                    for (i = 0; i < base.length; i++) {
                        url = url + base[i];
                        this.createIframe(url, "if" + i);
                    }
                    url = url + "-";
                    this.createIframe(url, "if_");
                } else {
                    if (this.hasVisited(url)) {
                        url = url + "/";
                        while (letter !== "-" && found === 1) {
                            found = 0;
                            for (i = 0; i < baseElems.length; i++) {
                                if (this.hasVisited(url + baseElems[i])) {
                                    letter = baseElems[i];
                                    if (letter !== "-") {
                                        val = val + letter;
                                    }
                                    url = url + letter;
                                    found = 1;
                                    break;
                                }
                            }
                        }
                        return this.decode(val);
                    }
                }
            };
            this.createElem = function(type, name, append) {
                var el;
                if (name !== undefined && document.getElementById(name)) {
                    el = document.getElementById(name);
                } else {
                    el = document.createElement(type);
                }
                el.style.visibility = "hidden";
                el.style.position = "absolute";
                if (name) {
                    el.setAttribute("id", name);
                }
                if (append) {
                    document.body.appendChild(el);
                }
                return el;
            };
            this.createIframe = function(url, name) {
                var el = this.createElem("iframe", name, 1);
                el.setAttribute("src", url);
                return el;
            };
            var waitForSwf = this.waitForSwf = function(i) {
                if (i === undefined) {
                    i = 0;
                } else {
                    i++;
                }
                if (i < _ec_tests && typeof swfobject === "undefined") {
                    setTimeout(function() {
                        waitForSwf(i);
                    }, 300);
                }
            };
            this.evercookie_cookie = function(name, value) {
                if (value !== undefined) {
                    document.cookie = name + "=; expires=Mon, 20 Sep 2010 00:00:00 UTC; path=/; domain=" + _ec_domain;
                    document.cookie = name + "=" + value + "; expires=Tue, 31 Dec 2030 00:00:00 UTC; path=/; domain=" + _ec_domain;
                } else {
                    return this.getFromStr(name, document.cookie);
                }
            };
            this.getFromStr = function(name, text) {
                if (typeof text !== "string") {
                    return;
                }
                var nameEQ = name + "=",
                    ca = text.split(/[;&]/),
                    i, c;
                for (i = 0; i < ca.length; i++) {
                    c = ca[i];
                    while (c.charAt(0) === " ") {
                        c = c.substring(1, c.length);
                    }
                    if (c.indexOf(nameEQ) === 0) {
                        return c.substring(nameEQ.length, c.length);
                    }
                }
            };
            this.getHost = function() {
                return window.location.host.replace(/:\d+/, '');
            };
            this.toHex = function(str) {
                var r = "",
                    e = str.length,
                    c = 0,
                    h;
                while (c < e) {
                    h = str.charCodeAt(c++).toString(16);
                    while (h.length < 2) {
                        h = "0" + h;
                    }
                    r += h;
                }
                return r;
            };
            this.fromHex = function(str) {
                var r = "",
                    e = str.length,
                    s;
                while (e >= 0) {
                    s = e - 2;
                    r = String.fromCharCode("0x" + str.substring(s, e)) + r;
                    e = s;
                }
                return r;
            };
            this.hasVisited = function(url) {
                if (this.no_color === -1) {
                    var no_style = this._getRGB("http://samy-was-here-this-should-never-be-visited.com", -1);
                    if (no_style === -1) {
                        this.no_color = this._getRGB("http://samy-was-here-" + Math.floor(Math.random() * 9999999) + "rand.com");
                    }
                }
                if (url.indexOf("https:") === 0 || url.indexOf("http:") === 0) {
                    return this._testURL(url, this.no_color);
                }
                return this._testURL("http://" + url, this.no_color) || this._testURL("https://" + url, this.no_color) || this._testURL("http://www." + url, this.no_color) || this._testURL("https://www." + url, this.no_color);
            };
            var _link = this.createElem("a", "_ec_rgb_link"),
                created_style, _cssText = "#_ec_rgb_link:visited{display:none;color:#FF0000}",
                style;
            try {
                created_style = 1;
                style = document.createElement("style");
                if (style.styleSheet) {
                    style.styleSheet.innerHTML = _cssText;
                } else if (style.innerHTML) {
                    style.innerHTML = _cssText;
                } else {
                    style.appendChild(document.createTextNode(_cssText));
                }
            } catch (e) {
                created_style = 0;
            }
            this._getRGB = function(u, test_color) {
                if (test_color && created_style === 0) {
                    return -1;
                }
                _link.href = u;
                _link.innerHTML = u;
                document.body.appendChild(style);
                document.body.appendChild(_link);
                var color;
                if (document.defaultView) {
                    if (document.defaultView.getComputedStyle(_link, null) == null) {
                        return -1;
                    }
                    color = document.defaultView.getComputedStyle(_link, null).getPropertyValue("color");
                } else {
                    color = _link.currentStyle.color;
                }
                return color;
            };
            this._testURL = function(url, no_color) {
                var color = this._getRGB(url);
                if (color === "rgb(255, 0, 0)" || color === "#ff0000") {
                    return 1;
                } else if (no_color && color !== no_color) {
                    return 1;
                }
                return 0;
            };
        };
        window._evercookie_flash_var = _evercookie_flash_var;
        window.evercookie = window.Evercookie = Evercookie;
    }(window));
} catch (ex) {};
var gTMZone = (new Date()).getTimezoneOffset() * 60;
var gTMDelta = 0;
var gstateTM = 0;
var gSiteStateEvt = null;
var checkStateWorking = false;
$(document).ready(function() {
    if ($(window).width() < 1023) {
        $("#social-menu").hide();
    } else {
        $("#social-menu").show();
    }
    window.onresize = function(event) {
        if ($(window).width() < 1023) {
            $("#social-menu").hide();
        } else {
            $("#social-menu").show();
        }
    };
    if (!$("#reviews").is(":visible") || !$(".banner").is(":visible")) {
        $("#filter_panel").after($("#social-menu"));
    }
    $(".banner-show").click(function() {
        if ($("#reviews").is(":visible")) {
            $(".header").not("#regform .header").after($("#social-menu"));
        }
    });
    $(".banner-hide").click(function() {
        $("#filter_panel").after($("#social-menu"));
    });
    $("a[name=closeReview]").click(function() {
        $("#filter_panel").after($("#social-menu"));
    });
    $("#reviewsButton").click(function() {
        if ($(".banner").is(":visible")) {
            $(".header").not("#regform .header").after($("#social-menu"));
        }
    });
    $.post("/ajax.php", {
        action: "utz",
        utz: gTMZone
    });
});
var tmSyncS = function() {
    var t = new Date();
    var t0 = t.getTime();
    var stm = $.ajax({
        url: "/timesync.php",
        async: false
    }).responseText;
    var t = new Date();
    var t1 = t.getTime();
    var dt = parseInt((t1 - t0) / 1);
    gstateTM = ((t1 + gTMZone * 1000) % 1000 - (stm + 20) % 1000);
    if (gstateTM < 0) gstateTM += 1000;
    gTMDelta = Math.round(((t1 + gTMZone * 1000) - (parseInt(stm) + 20)) / 1000);
    var dt = parseInt((t1 - t0) / 2);
    var srvms = (stm % 1000) + dt;
    var gstateTM = 500 - srvms + (srvms > 500 ? 1000 : 0);
    gTMDelta = Math.round(((t1 + gTMZone * 1000) - (parseInt(stm) + dt)) / 1000);
};
var tmSync = function() {
    var t = new Date();
    var t0 = t.getTime();
    $.get("/timesync.php", {}, function(data, textstatus) {
        var t = new Date();
        var t1 = t.getTime();
        var dt = parseInt((t1 - t0) / 2);
        gstateTM = ((t1 + gTMZone * 1000) % 1000 - (data + 20) % 1000);
        if (gstateTM < 0) gstateTM += 1000;
        gTMDelta = Math.round(((t1 + gTMZone * 1000) - (parseInt(data) + dt)) / 1000, 0);
        console.log("delta = " + gTMDelta + " сек (" + (((t1 + gTMZone * 1000) - (parseInt(data) + dt)) / 1000) + ")");
    });
};
var checkSiteState = function(tmout) {
    if (checkStateWorking) return false;
    checkStateWorking = true;
    if (tmout && tmout === true) {
        if (gSiteStateEvt)
            clearTimeout(gSiteStateEvt);
        gSiteStateEvt = null;
    }
    $.get("/checkstate.php", {}, function(data, textstatus) {
        if (gSiteState != data.siteState) {
            if (gSiteStateEvt)
                clearTimeout(gSiteStateEvt);
            gSiteStateEvt = null;
            window.location.reload(true);
        }
        if (data.tmevent != '0' && gSiteStateEvt == null) {
            var n = new Date();
            var nt = n.getTime() + gTMZone * 1000;
            var delta = data.tmevent + gTMDelta * 1000 - nt;
            gSiteStateEvt = setTimeout("checkSiteState(true)", delta + 1000);
        }
        if (data.tmevent != '0' && (gSiteState == '3' || gSiteState == '4')) {
            $("#stateTimer").html(getTimer(data.tmevent / 1000));
        }
        checkStateWorking = false;
    }, "json");
}
var getCookie = function(name) {
    var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
var setCookie = function(name, value, options) {
    options = options || {};
    var expires = options.expires;
    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }
    value = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;
    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }
    document.cookie = updatedCookie;
}
var deleteCookie = function(name) {
    setCookie(name, "", {
        expires: -1
    })
}
var hasLocalStorage = function() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}
tmSyncS();
var picTimeout;
var picAnimating = false;
var oPic = null;
if (window.console && typeof(window.console.time) == "undefined") {
    console.time = function(name, reset) {
        if (!name) {
            return;
        }
        var time = new Date().getTime();
        if (!console.timeCounters) {
            console.timeCounters = {}
        };
        var key = "KEY" + name.toString();
        if (!reset && console.timeCounters[key]) {
            return;
        }
        console.timeCounters[key] = time;
    };
    console.timeEnd = function(name) {
        var time = new Date().getTime();
        if (!console.timeCounters) {
            return;
        }
        var key = "KEY" + name.toString();
        var timeCounter = console.timeCounters[key];
        if (timeCounter) {
            var diff = time - timeCounter;
            var label = name + ": " + diff + "ms";
            console.info(label);
            delete console.timeCounters[key];
        }
        return diff;
    };
}
var getTimer = function(tm, targettimer) {
    tmtest = /[0-9]{2}:[0-9]{2}:[0-9]{2}/;
    if (tmtest.test(tm)) return tm;
    var t = parseInt(tm);
    var ttm = parseInt(targettimer);
    if (t) {
        var n = new Date();
        var nt = n.getTime() / 1000 + gTMZone;
        var nt = nt - (nt % 1);
        var delta = t + gTMDelta - nt;
        if (delta <= 0)
            return "00:00:00";
        else {
            var tmp = delta / (60 * 60);
            var h = tmp - (tmp % 1);
            delta = delta - h * 60 * 60;
            tmp = delta / 60;
            var m = tmp - (tmp % 1);
            delta = delta - m * 60;
            var s = delta;
            if (ttm && ((s > ttm))) s = ttm;
            if (h < 10) h = "0" + h;
            if (m < 10) m = "0" + m;
            if (s < 10) s = "0" + s;
            return h + ":" + m + ":" + s;
        }
    } else
        return "00:00:00";
}
var dateFormat = function(tm) {
    var t = (parseInt(tm) - gTMZone + gTMDelta) * 1000;
    var dt = new Date(t);
    var months = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];
    var h = dt.getHours();
    var m = dt.getMinutes();
    var s = dt.getSeconds();
    return dt.getDate() + ' ' +
        months[dt.getMonth()] + ' ' +
        dt.getFullYear() + ' ' +
        (h < 10 ? '0' + h : h) + ':' +
        (m < 10 ? '0' + m : m) + ':' +
        (s < 10 ? '0' + s : s);
}
$(function() {
    setTimeout("tmSync()", 5000);
    setTimeout("tmSync()", 10000);
    setTimeout("tmSync()", 15000);
    setTimeout("tmSync()", 20000);
    setTimeout("tmSync()", 25000);
    setInterval("tmSync()", 1000 * 60);
    var checkint = 60000;
    checkSiteState(false)
    switch (gSiteState) {
        case -1:
        case 1:
            checkint = 60000;
        case 0:
        case 2:
        case 5:
            checkint = 60000;
            break;
        case 3:
        case 4:
            checkint = 1000;
            break;
    }
    setInterval(function() {
        checkSiteState(false);
    }, checkint);
    $("#bidtuner").click(autoBidChange2);
    $(".data-tm").each(function(ind, elm) {
        var $elm = $(elm);
        $elm.text(convDate($elm.text(), false, false, true));
    });
    $(".data-dt").each(function(ind, elm) {
        var $elm = $(elm);
        $elm.text(convDate($elm.text()));
    });
    $("span[name=timer]").each(function(ind, elm) {
        var $elm = $(elm);
        $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
        $elm.text(getTimer($elm.text()));
    });
    $("#page_locker").click(function() {
        if ($("#email_notice").is(':visible') || $("#promocode_notice").is(':visible')) {
            return true;
        } else {
            $(".modalwin").fadeOut("fast", function() {
                $("#page_locker").fadeOut("fast");
                $("#nj_bidders").remove();
                $("#auc20x7_notice").remove();
                $("#roboform").remove();
            });
        }
    });
    $("#closeactivationform").click(function() {
        $(".modalwin").fadeOut("fast", function() {
            $("#page_locker").fadeOut("fast")
        });
    });
    $("#send_act_email").click(function() {
        var btn = this;
        $.get("/ajax.php", {
            action: "activation_email"
        }, function(data, textstatus) {
            if ($.trim(data) == 1) {
                showBalloon2("Письмо успешно отправлено", btn);
            } else if ($.trim(data) == 0) {
                showBalloon2("При отправке произошла ошибка.", btn);
            }
        });
    });
    $("body").click(function() {
        closeAll(this);
    });
    $("#refill_win").click(function(event) {
        event.stopPropagation();
    });
    getEvents();
    var autoRegistration = getCookie("_bm_ar_");
    if (typeof(autoRegistration) != "undefined" && autoRegistration) {
        if (autoRegistration != "~~~~~~") $("#registrationform input[name=promo]").val(autoRegistration);
        setTimeout(function() {
            $("#top_btn_registration").click()
        }, 200);
        deleteCookie("_bm_ar_");
        var autoRegistrationPage = getCookie("_bm_arp_");
        if (typeof(autoRegistrationPage) != "undefined" && autoRegistrationPage) {
            $("#registrationform").append("<input type='hidden' name='bm_arp' value='" + autoRegistrationPage + "'>");
            deleteCookie("_bm_arp_");
        }
    }
    $("#registry_now").click(function() {
        $("#top_btn_registration").click();
    });
    $("#File1").change(function() {
        var file = this.value;
        var $this = $(this);
        var fileName = $('#FileName');
        reWin = /.*\\(.*)/;
        var fileTitle = file.replace(reWin, "$1");
        reUnix = /.*\/(.*)/;
        fileTitle = fileTitle.replace(reUnix, "$1");
        fileName.html(fileTitle);
        var RegExExt = /.*\.(.*)/;
        var ext = fileTitle.replace(RegExExt, "$1");
        var pos;
        if (ext) {
            switch (ext.toLowerCase()) {
                case 'bmp':
                    pos = '0';
                    break;
                case 'jpg':
                    pos = '16';
                    break;
                case 'jpeg':
                    pos = '16';
                    break;
                case 'png':
                    pos = '32';
                    break;
                case 'gif':
                    pos = '48';
                    break;
                default:
                    pos = '0';
                    break;
            };
            fileName.css({
                "display": "block",
                "background": "url('/img/file_icons.png') no-repeat 0 -" + pos + "px",
                "padding-left": "19px"
            });
        } else
            fileName.css({
                "display": "block",
                "padding-left": "3px"
            });
        var cross = $("<div class='clear-filename'><div class='balloon' name='balloon' style='left:-14px;'>Удалить файл<div class='balloonarrow'>&nbsp;</div></div>");
        cross.click(function() {
            $this.val("");
            fileName.html("").css("background", "none");
            cross.remove();
        });
        fileName.append(cross);
    }).mousedown(function() {
        if ($(this).parents("#addavatar").length == 0) $("#BrowseButton").css("background-position", "0 -34px");
    }).mouseup(function() {
        $("#BrowseButton").css("background-position", "0 0px");
    }).mouseleave(function() {
        $("#BrowseButton").css("background-position", "0 0px");
    });
    $("#File2").change(function() {
        var file = "";
        var fileTitle = "";
        var $this = $(this);
        var fileName = $('#FileName');
        var reWin;
        var reUnix;
        var RegExExt;
        var ext;
        var pos;
        var cssStr;
        var filesHtml = "";
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            var files = this.files;
            var output = [];
            for (var i = 0, f; f = files[i]; i++) {
                file = f.name;
                reWin = /.*\\(.*)/;
                fileTitle = file.replace(reWin, "$1");
                reUnix = /.*\/(.*)/;
                RegExExt = /.*\.(.*)/;
                ext = fileTitle.replace(RegExExt, "$1");
                if (ext) {
                    switch (ext.toLowerCase()) {
                        case 'bmp':
                            pos = '0';
                            break;
                        case 'jpg':
                            pos = '16';
                            break;
                        case 'jpeg':
                            pos = '16';
                            break;
                        case 'png':
                            pos = '32';
                            break;
                        case 'gif':
                            pos = '48';
                            break;
                        default:
                            pos = '0';
                            break;
                    };
                    cssStr = "display: block; min_width: 10px; margin-left: 3px; float:left; background: url(\"/img/file_icons.png\") no-repeat 0 -" + pos + "px; padding-left: 19px;";
                } else
                    cssStr = "display: block; padding-left:3px; min_width: 10px; float:left;";
                filesHtml += "<span style='" + cssStr + "'>" + fileTitle.replace(reUnix, "$1") + "</span>";
            }
            fileName.html(filesHtml);
        } else {
            file = this.value;
            reWin = /.*\\(.*)/;
            fileTitle = file.replace(reWin, "$1");
            reUnix = /.*\/(.*)/;
            fileTitle = fileTitle.replace(reUnix, "$1");
            fileName.html(fileTitle);
            RegExExt = /.*\.(.*)/;
            ext = fileTitle.replace(RegExExt, "$1");
            if (ext) {
                switch (ext.toLowerCase()) {
                    case 'bmp':
                        pos = '0';
                        break;
                    case 'jpg':
                        pos = '16';
                        break;
                    case 'jpeg':
                        pos = '16';
                        break;
                    case 'png':
                        pos = '32';
                        break;
                    case 'gif':
                        pos = '48';
                        break;
                    default:
                        pos = '0';
                        break;
                };
                fileName.css({
                    "display": "block",
                    "background": "url('/img/file_icons.png') no-repeat 0 -" + pos + "px",
                    "padding-left": "19px"
                });
            } else
                fileName.css({
                    "display": "block",
                    "padding-left": "3px"
                });
            var cross = $("<div class='clear-filename'><div class='balloon' name='balloon' style='left:-14px;'>Удалить файл<div class='balloonarrow'>&nbsp;</div></div>");
            cross.click(function() {
                $this.val("");
                fileName.html("").css("background", "none");
                cross.remove();
            });
            fileName.append(cross);
        }
    }).mousedown(function() {
        if ($(this).parents("#addavatar").length == 0) $("#BrowseButton").css("background-position", "0 -34px");
    }).mouseup(function() {
        $("#BrowseButton").css("background-position", "0 0px");
    }).mouseleave(function() {
        $("#BrowseButton").css("background-position", "0 0px");
    });
    $(".picture-prevbtn").click(function() {
        clearTimeout(picTimeout);
        picChange("prev");
        picTimeout = setTimeout("picChanger()", 5000);
    });
    $(".picture-nextbtn").click(function() {
        clearTimeout(picTimeout);
        picChange("next");
        picTimeout = setTimeout("picChanger()", 5000);
    });
    $(".picture-prevbtn, .picture-nextbtn").hover(function() {
        $(this).stop(false, true).fadeTo("fast", 1);
    }, function() {
        $(this).stop(false, true).fadeTo("fast", 0.6);
    });
    $(".picture-view").hover(function() {
        $(".picture-prevbtn, .picture-nextbtn").stop(false, true).fadeTo("fast", 0.6);
    }, function() {
        $(".picture-prevbtn, .picture-nextbtn").stop(false, true).fadeOut("fast");
    });
    oPic = $(".picture-item:first");
    if (oPic.length > 0) {
        oPic.css("z-index", "11");
        if ($(".picture-item").length > 1) picTimeout = setTimeout("picChanger()", 5000);
    }
});
var convDate = function(tmstamp, nosec, timefirst, nodate, notime) {
    if (!nosec)
        var nosec = false;
    if (!timefirst)
        var timefirst = false;
    if (!nodate)
        var nodate = false;
    if (!notime)
        var notime = false;
    var tm = (parseInt(tmstamp) - gTMZone + gTMDelta) * 1000;
    var d = new Date(tm);
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var hour = d.getHours();
    var minute = d.getMinutes();
    var second = d.getSeconds();
    if (String(month).length == 1) month = "0" + month;
    if (String(day).length == 1) day = "0" + day;
    if (String(hour).length == 1) hour = "0" + hour;
    if (String(minute).length == 1) minute = "0" + minute;
    if (String(second).length == 1) second = "0" + second;
    if (timefirst)
        strd = hour + ":" + minute + (nosec ? "" : ":" + second) + (nodate ? "" : (" " + day + "." + month + "." + year));
    else
        strd = (nodate ? "" : (day + "." + month + "." + year)) + (notime ? "" : " " + hour + ":" + minute + (nosec ? "" : ":" + second));
    return $.trim(strd);
};

function getEvents() {
    $.get("/ajax.php", {
        action: "getevents"
    }, function(data, textstatus) {
        $.each(data, function(ind, elm) {
            if (elm.type == 2)
                showBalloon2(elm.msg, $("#balance_value")[0], 5000);
        })
    }, "json");
}

function closeAll(oContext, callback) {
    $("#category_menu").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $(".cat-menu-wrapper span").css("background-position", "0 0");
    $("#auction_menu").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $("#auction_menu").parent().find("span").css("background-position", "0 0");
    $("#auctype_menu").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $("#auctype_menu").parent().find("span").css("background-position", "0 0");
    $("#signinfrm").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $("#sorting_menu").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $(".sorting-menu-wrapper span").css("background-position", "0 0");
    $("#autobidoptions").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $("#autobidoptbtn").removeClass("autobidoptbtnpressed");
    $("#profile_image_menu").stop().animate({
        opacity: "0"
    }, 500, function() {
        $(this).css("left", "-9999px")
    });
    $(".profile-image-menu-wrapper span").css("background-position", "0 0");
    if (callback) callback(oContext);
}
var picChanger = function() {
    picChange("next");
    picTimeout = setTimeout("picChanger()", 5000);
}
var picChange = function(direction) {
    oPic.stop(false, true);
    var pics = $(".picture-item");
    var cnt = pics.length;
    var id = parseInt(oPic.attr("name"));
    var nextid = 0;
    switch (direction) {
        case "prev":
            if (id == 0) nextid = cnt - 1;
            else nextid = id - 1;
            break;
        case "next":
            if (id == (cnt - 1)) nextid = 0;
            else nextid = id + 1;
            break;
    }
    var oNex = $(".picture-item[name=" + nextid + "]");
    oPic.css("z-index", "10");
    oNex.css("z-index", "11").fadeIn("normal", function() {
        oPic.hide();
        oPic = oNex;
    });
}
var showPreOrderInfo = function() {
    $("#page_locker").fadeIn("normal", function() {
        var d = $("<div id='preorder_info' class='modalwin'><p style='text-align:center; font-size:1.2em;'>Вы можете купить данный товар за полную стоимость в течение 3 дней после начала официальных продаж (при наличии на складах официальных дистрибьюторов) и объявления окончательной стоимости. При этом стоимость потраченных на данном аукционе ставок будет вычтена из стоимости товара. Вы получите письмо-уведомление о начале продаж на электронную почту, указанную при регистрации. Также на странице «Моя история» личного кабинета будет доступна функция оплаты данного товара.<br><br><br></p><p style='text-align: center;'><span class='buttongray' onclick='$(\"#page_locker\").click();$(\"#preorder_info\").remove();'>Закрыть</span></p></div>");
        $("body").append(d);
        $("#preorder_info").show();
    });
}
var showActivationWnd = function() {
    $("#page_locker").show();
    $("#need_activation").show();
}
var showPayByMobileWnd = function(pmttype, pstype) {
    if (pstype == "" || pstype == "Robokassa") {
        if (pmttype == "MixplatMTSRIBR" || pmttype == "MixplatBeelineRIBR" || pmttype == "MixplatTele2RIBR") {
            if ($("#buybids_by_mobile").length == 0)
                $("#roboform").submit();
            else {
                $("#page_locker").show();
                $("#buybids_by_mobile").show();
            }
        }
        if (pmttype == "Megafon") {
            if ($("#buybids_by_mobile_megafon").length == 0)
                $("#roboform").submit();
            else {
                $("#page_locker").show();
                $("#buybids_by_mobile_megafon").show();
            }
        }
    }
    if (pstype == "Yandexkassa") {
        if (pmttype == "MTS" || pmttype == "Beeline" || pmttype == "Megafon") {
            if ($("#buybids_by_mobile_yandex").length == 0)
                $("#roboform").submit();
            else {
                $("#page_locker").show();
                $("#buybids_by_mobile_yandex").show();
            }
        }
    }
}
var processCookies = function() {
    var _bm_uid_ss = getCookie("_bm_uid_ss");
    var codeTpl = /^[0-9A-Za-z]{40}$/i;
    var ec = new evercookie({
        baseurl: "/plugin/ec/",
        phpuri: "php"
    });
    var tmp = ec.get("_bm_uid", function(val) {
        if (codeTpl.test(_bm_uid_ss)) {
            if (codeTpl.test(val)) {
                if (val.toLowerCase() != _bm_uid_ss.toLowerCase()) {
                    setCookie("_bm_uid_old", val);
                }
            }
            ec.set("_bm_uid", _bm_uid_ss);
        }
    });
}
var genRandomID = function(min, max) {
    var symbols = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var len = rand(min, max);
    var s = '';
    s += symbols.charAt(rand(0, 51));
    for (var i = 0; i < (len - 1); i++)
        s += symbols.charAt(rand(0, 61));
    return s;
}
var rand = function(min, max) {
    return Math.round((max - min) * Math.random() + min);
}
var addStyleSheet = function() {
    var style = document.createElement('style');
    style.type = 'text/css';
    document.getElementsByTagName('head')[0].appendChild(style);
    return document.styleSheets[document.styleSheets.length - 1];
}
var addStyle = function(ss, sel, rule) {
    if (ss.addRule) {
        ss.addRule(sel, rule);
    } else {
        if (ss.insertRule) {
            ss.insertRule(sel + ' {' + rule + '}', ss.cssRules.length);
        }
    }
}
$(function() {
    try {
        processCookies();
    } catch (e) {
        console.log("Evercookie error: " + e.message);
    }
});
Share = {
    vkontakte: function(purl, ptitle, pimg, text) {
        url = 'http://vkontakte.ru/share.php?';
        url += 'url=' + encodeURIComponent(purl);
        url += '&title=' + encodeURIComponent(ptitle);
        url += '&description=' + encodeURIComponent(text);
        url += '&image=' + encodeURIComponent(pimg);
        url += '&noparse=true';
        Share.popup(url);
    },
    odnoklassniki: function(purl, text) {
        url = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
        url += '&st.comments=' + encodeURIComponent(text);
        url += '&st._surl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    odnoklassniki2: function(purl, text) {
        url = 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview';
        url += '&st.comments=' + encodeURIComponent(text);
        url += '&st.shareUrl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    facebook: function(purl, ptitle, pimg, text) {
        url = 'http://www.facebook.com/sharer.php?s=100';
        url += '&p[title]=' + encodeURIComponent(ptitle);
        url += '&p[summary]=' + encodeURIComponent(text);
        url += '&p[url]=' + encodeURIComponent(purl);
        url += '&p[images][0]=' + encodeURIComponent(pimg);
        url = 'http://www.facebook.com/dialog/feed?' + 'app_id=771552319656390' + '&link=https://' + encodeURIComponent(purl) + '&caption=' + encodeURIComponent(purl) + '&description=' + encodeURIComponent(text) + '&redirect_uri=https://' + encodeURIComponent(purl);
        Share.popup(url);
        return;
    },
    twitter: function(purl, ptitle) {
        url = 'http://twitter.com/share?';
        url += 'text=' + encodeURIComponent(ptitle);
        url += '&url=' + encodeURIComponent(purl);
        url += '&counturl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    mailru: function(purl, ptitle, pimg, text) {
        url = 'http://connect.mail.ru/share?';
        url += 'url=' + encodeURIComponent(purl);
        url += '&title=' + encodeURIComponent(ptitle);
        url += '&description=' + encodeURIComponent(text);
        url += '&imageurl=' + encodeURIComponent(pimg);
        Share.popup(url)
    },
    me: function(el) {
        console.log(el.href);
        Share.popup(el.href);
        return false;
    },
    popup: function(url) {
        window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
    }
};;
var oBan = null;
var bannerInterval = null;
var bannerTM = 30000;
$(function() {
    $('.sel_options').tinyscrollbar({
        wheel: '25',
        sizethumb: '36'
    });
    $(".top-account-info-cover").click(function(event) {
        location.assign("/profile/myauction");
    });
    $("#top_btn_registration").click(function(event) {
        $('#registrationform input[name=frompage]').val('');
        $('#registrationform input[name=subpage]').val('');
        closeAll(this, function() {
            $("#page_locker").stop().fadeIn("fast", function() {
                $(this).css("filter", "alpha(opacity=20)");
                $("#regform").show();
            });
        });
        event.stopPropagation();
    });
    $("#lk_registration").click(function(event) {
        $("#top_btn_registration").click();
    });
    $("#top_btn_signout").click(function() {
        location.assign("/logout");
    });
    $("#top_btn_signin").click(function(event) {
        closeAll(this, function(oContext) {
            if (!($("#signinfrm").css("left") == "275px")) {
                $("#signinfrm").stop().css("left", "275px").animate({
                    opacity: "1"
                }, 300);
            } else {
                $("#signinfrm").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
            }
        });
        event.stopPropagation();
    });
    $("#signinfrm").click(function(event) {
        event.stopPropagation();
    });
    $("#btn_signin").click(function() {
        $("#loginprogress").show();
        var u = $("#userlogin1").val();
        var p = $("#userpassword1").val();
        $.get("/ajax.php", {
            action: "logincheck",
            user: u,
            pass: p
        }, function(data, textstatus) {
            $("#loginprogress").hide();
            if ($.trim(data) == "1") {
                $("#signin_form")[0].submit();
            } else
                showBalloon2("Имя пользователя или пароль введены неверно", $("#userlogin1")[0]);
        });
    });
    $("#signin_form").submit(function() {
        setTimeout(function() {
            $("#loginprogress").show();
        }, 1);
        var u = $("#userlogin1").val();
        var p = $("#userpassword1").val();
        var stm = $.ajax({
            url: "/ajax.php?action=logincheck&user=" + u + "&pass=" + p,
            async: false
        }).responseText;
        if (stm == "1")
            return true
        else
            showBalloon2("Имя пользователя или пароль введены неверно", $("#userlogin1")[0]);
        setTimeout(function() {
            $("#loginprogress").hide();
        }, 10);
        return false;
    });
    $("#loginsubmit1").click(function() {
        $("#loginaltprogress").show();
        var u = $("#userlogin2").val();
        var p = $("#userpassword2").val();
        $.get("/ajax.php", {
            action: "logincheck",
            user: u,
            pass: p
        }, function(data, textstatus) {
            $("#loginaltprogress").hide();
            if ($.trim(data) == "1") {
                $("#authform")[0].submit();
            } else
                showBalloon2("Имя пользователя или пароль введены неверно", $("#userlogin2")[0]);
        });
    });
    $("#authform").submit(function() {
        setTimeout(function() {
            $("#loginaltprogress").show();
        }, 1);
        var u = $("#userlogin2").val();
        var p = $("#userpassword2").val();
        var stm = $.ajax({
            url: "/ajax.php?action=logincheck&user=" + u + "&pass=" + p,
            async: false
        }).responseText;
        if (stm == "1")
            return true
        else
            showBalloon2("Имя пользователя или пароль введены неверно", $("#userlogin2")[0]);
        setTimeout(function() {
            $("#loginaltprogress").hide();
        }, 10);
        return false;
    });
    var oSrch = $('input[name=q]');
    oSrch.each(function() {
        if ($(this).val() == "") $(this).val("Что Вы ищете?");
    })
    oSrch.focus(function() {
        if ($(this).val() == "Что Вы ищете?") $(this).val("");
    }).blur(function() {
        if ($(this).val() == "") $(this).val("Что Вы ищете?");
    });
    $("#startdate").datepicker({
        buttonText: 'Календарь',
        currentText: 'Сегодня',
        closeText: 'Закрыть',
        showAnim: 'fadeIn',
        showButtonPanel: true,
        setDate: $("#startdate").val() ? $("#startdate").val() : null
    });
    $("#enddate").datepicker({
        buttonText: 'Календарь',
        currentText: 'Сегодня',
        closeText: 'Закрыть',
        showAnim: 'fadeIn',
        showButtonPanel: true,
        setDate: $("#enddate").val() ? $("#enddate").val() : null
    });
    oBan = $(".bannerblock:first");
    if (oBan.length > 0) {
        oBan.css("z-index", "11").fadeIn("normal");
        $("#bannerbtn_" + parseInt(oBan[0].id.split("_")[1])).addClass("pressed");
        $(".bannerswitcher div").click(function() {
            var id = parseInt(this.id.split("_")[1]);
            if (oBan[0].id != "banner_" + id) {
                clearTimeout(bannerInterval);
                bannerTM = 10000;
                $(".bannerswitcher .pressed").removeClass("pressed");
                $("#bannerbtn_" + id).addClass("pressed");
                var oNex = $("#banner_" + id);
                oBan.css("z-index", "10");
                oNex.css("z-index", "11").fadeIn("normal", function() {
                    oBan.hide();
                    oBan = oNex;
                    bannerInterval = setTimeout(bannerSwitch, bannerTM);
                });
            }
        });
        if ($(".bannerblock").length > 1) {
            bannerInterval = setTimeout(bannerSwitch, bannerTM);
        }
    }
    $(".banner-hide").click(function() {
        clearInterval(bannerInterval);
        $(".banner").parent().slideUp("fast");
        $(".banner-show").show();
    });
    $(".banner-show").click(function() {
        var id = parseInt(oBan[0].id.split("_")[1]);
        $(".bannerswitcher .pressed").removeClass("pressed");
        $("#bannerbtn_" + id).addClass("pressed");
        if ($(".bannerblock").length > 1) {
            bannerInterval = setTimeout(bannerSwitch, 10000);
        }
        $(".banner").parent().slideDown("fast");
        $(".banner-show").hide();
    });
});

function bannerSwitch() {
    var cnt = $(".bannerblock").length;
    var id = parseInt(oBan[0].id.split("_")[1]);
    if (id == cnt - 1) id = 0;
    else id = id + 1;
    var oNex = $("#banner_" + id);
    $(".bannerswitcher .pressed").removeClass("pressed");
    $("#bannerbtn_" + id).addClass("pressed");
    oBan.css("z-index", "10");
    oNex.css("z-index", "11").fadeIn("normal", function() {
        oBan.hide();
        oBan = oNex;
    });
    bannerTM = 10000;
    bannerInterval = setTimeout(bannerSwitch, bannerTM);
};
var selenter = false;
$(function() {
    setFormEvents();
    $('.datetime-wrapper input[id]').each(function(ind, elm) {
        var $this = $(elm);
        $this.fadeTo(0, 0);
        $this.val(convDate($this.val(), true, false));
        $this.datetimepicker({
            onSelect: function() {
                changeDateTime(this);
            }
        });
        $(".ui-datepicker").click(function(evt) {
            evt.stopPropagation();
        });
        changeDateTime(elm);
    });
    $(document).click(function() {
        if (!selenter) {
            $('.sel_imul').hide();
            $('.sel_img').removeClass('opened');
            $('.sel_selected').removeClass('selopened');
        }
    });
    $('.checkbox input').each(function() {
        if ($(this).attr("checked") == "checked") {
            $(this).parent().children('.checkbox_img').addClass("checkbox_img_checked");
        } else {
            $(this).parent().children('.checkbox_img').removeClass("checkbox_img_checked");
        }
    });
    bindCheckPwd();
});

function setfocus(obj) {
    $("input[name='" + obj + "']").focus();
}
var registrationProcess = false;
$(function() {
    $("#closeregform").click(function() {
        $("#regform").fadeOut("fast", function() {
            $("#page_locker").fadeOut("fast")
        });
    });
    $("#cosesigninform").click(function(event) {
        $("#signinfrm").stop().animate({
            opacity: "0"
        }, 500, function() {
            $(this).css("left", "-9999px")
        });
        event.stopPropagation();
    });

    function getCookie(name) {
        var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"))
        return matches ? decodeURIComponent(matches[1]) : undefined
    }
    $(document).scroll(function() {
        var h = $(this).scrollTop();
        h = parseInt(h);
        var oBtn = $('#to_top_button');
        if (h >= 300) {
            if (oBtn.is(":hidden")) {
                oBtn.stop().fadeIn("fast");
            }
        } else {
            if (!oBtn.is(":hidden")) oBtn.stop().fadeOut("fast");
        }
        var correct = 0;
        if (!($(".block").length > 0)) correct = 275;
        var closeReview = getCookie("closeReview");
        if ((!closeReview) || (closeReview && closeReview == 0))
            correct = correct - 40;
        if (h >= 400 - correct)
            $("#cat_panel").addClass("panelwrapper-fixed").addClass("dropshadow5px");
        else
            $("#cat_panel").removeClass("panelwrapper-fixed").removeClass("dropshadow5px");
    });
    $('#to_top_button').click(function() {
        $(this).stop().fadeOut("fast");
    });
    $('#refreshword').click(function() {
        var dt = new Date();
        $('#captchaimg').attr('src', '/plugin/kcaptcha/index.php?id=' + dt.getMilliseconds());
    });
    $('#webcamshow').click(function() {
        $('#camera').show();
    });
    $("#registrationform").submit(function() {
        $("#regformsubmit").click();
        return false;
    });
    $("#regformsubmit").click(function() {
        if (registrationProcess)
            return;
        registrationProcess = true;
        var oEmail = $('#regemail');
        var oNick = $('#nick');
        var oAd = $('#adrules');
        var oPers = $('#persdata');
        var oPromo = $("#promo");
        var oPwd = $("#registrationform input[name=pwd]");
        var oPromoCode = $("#promocode");
        var email = /.+@.+\.(.{2,})/i;
        var all_ready = true;
        var newVal = oNick.val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if (oNick.val() != newVal) oNick.val(newVal);
        var newVal = oEmail.val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if (oEmail.val() != newVal) oEmail.val(newVal);
        if (oEmail.val() == "") {
            showBalloon2("Поле не должно быть пустым", oEmail[0], 5000);
            all_ready = false;
        } else if (!email.test(oEmail.val())) {
            showBalloon2("Неверное значение электронной почты", oEmail[0], 5000);
            all_ready = false;
        }
        if (!oNick.val() || oNick.val().trim() == '') {
            showBalloon2("Поле не должно быть пустым", oNick[0], 5000);
            all_ready = false;
        }
        if (oAd.attr("checked") != "checked") {
            showBalloon2("Для успешного завершения регистрации Вы должны согласиться с условиями", oAd[0], 5000);
            all_ready = false;
        }
        if (oPers.attr("checked") != "checked") {
            showBalloon2("<nobr>Вы должны согласиться на обработку персональных данных</nobr>", oPers[0], 5000);
            all_ready = false;
        }
        if (oPwd.val() == "") {
            showBalloon2("Поле не должно быть пустым", oPwd[0], 5000);
            all_ready = false;
        } else if (oPwd.val().length < 6) {
            showBalloon2("Пароль слишком короткий", oPwd[0], 5000);
            all_ready = false;
        }
        if (all_ready) {
            $.get("/ajax.php", {
                action: "checkemail",
                email: oEmail.val(),
                nick: oNick.val(),
                promocode: oPromoCode.val()
            }, function(data, textstatus) {
                var ready = true;
                if (data.m != "0") {
                    showBalloon2("Пользователь с таким адресом уже зарегистрирован в системе", oEmail[0], 5000);
                    ready = false;
                }
                if (data.n != "0") {
                    showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", oNick[0], 5000);
                    ready = false;
                }
                if (data.duplicate != "0") {
                    showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", oNick[0], 5000);
                    ready = false;
                }
                if (data.count != "0") {
                    showBalloon2("Пользователь не может написать ник больше 16 знаков", oNick[0], 5000);
                    ready = false;
                }
                if (data.badword != "0") {
                    showBalloon2("Указанный ник не соответствует правилам сайта", oNick[0], 5000);
                    ready = false;
                }
                if (data.badchar != "0") {
                    showBalloon2("Запрещено использовать спецсимволы: ' \" \\ / * < >", oNick[0], 5000);
                    ready = false;
                }
                if (ready && data.pc != "0") {
                    registrationProcess = false;
                    $("#regform").hide();
                    $("#page_locker").fadeIn("normal", function() {
                        var d = $("<div id='promocode_notice' class='modalwin'><p style='text-align:center; font-size:1.2em; margin-bottom: -10px;'>Вы ввели неверный промо-код.<br><br><br></p><p style='text-align: center;'><span class='buttonorange' onclick='closePromoNotice(0);'>Ввести код еще раз</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='buttonorange' onclick='processWithoutPromoCode(0);'>Продолжить без кода</span></p></div>");
                        $("body").append(d);
                        d.show();
                    });
                    ready = false;
                } else {
                    $("#promocode_notice").remove();
                }
                if (ready && data.e != "0") {
                    registrationProcess = false;
                    $("#regform").hide();
                    $("#page_locker").fadeIn("normal", function() {
                        var d = $("<div id='email_notice' class='modalwin'><p style='text-align:center; font-size:1.2em; margin: 0 30px;'>Вы указали адрес электронной почты <strong>" + oEmail.val() + "</strong>. Важно: корректный адрес электронной почты - это единственная возможность восстановить пароль и доступ к личному аккаунту. Если указанный адрес является корректным, нажмите \"Продолжить\". Если указан некорректный адрес, Вы можете его изменить, нажав \"Назад\".<br><br></p><p style='text-align: center; padding-bottom: 30px;'><span class='buttonorange' onclick='closeEmailNotice(0);'>Назад</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='buttonorange' onclick='processWithoutEmail(0);'>Продолжить</span></p></div>");
                        $("body").append(d);
                        d.show();
                    });
                    ready = false;
                } else {
                    $("#email_notice").remove();
                }
                if (ready) {
                    $("#in1 form")[0].submit();
                    $('#regform').hide();
                } else {
                    registrationProcess = false;
                }
            }, "json");
        } else {
            registrationProcess = false;
        }
    });
    $("#category_menu").click(function(e) {
        e.stopPropagation();
    });
    $(".filter-selected").mouseenter(function() {
        var $this = $(this);
        if ($this.height() == 36 && this.scrollHeight > 36)
            $this.addClass("dropshadow3px").animate({
                height: this.scrollHeight + "px"
            }, 400);
    }).mouseleave(function() {
        var $this = $(this);
        if ($this.height() != 36)
            $this.removeClass("dropshadow3px").animate({
                height: "36px"
            }, 400);
    });
    $("input[name=t],input[name=y]").change(function(evt) {
        var $this = $(this);
        var name = $this.attr("name");
        var $chb = $("input[name=" + name + "]");
        var first = true;
        if ($this.val() == "0") {
            if (this.checked) {
                $chb.each(function(ind, elm) {
                    var $elm = $(elm);
                    if ($elm.val() == "0")
                        return true;
                    if (!elm.checked) {
                        elm.checked = true;
                        $elm.parent().find(".checkbox_img").addClass("checkbox_img_checked");
                    }
                });
            } else {
                $chb.each(function(ind, elm) {
                    var $elm = $(elm);
                    if ($elm.val() == "0")
                        return true;
                    if (first) {
                        first = !first;
                        if (elm.checked)
                            return true;
                        else {
                            elm.checked = true;
                            $elm.parent().find(".checkbox_img").addClass("checkbox_img_checked");
                        }
                    }
                    if (elm.checked) {
                        elm.checked = false;
                        $elm.parent().find(".checkbox_img").removeClass("checkbox_img_checked");
                    }
                });
            }
        } else {
            var $all = $("input[name=" + name + "][value=0]");
            var allcheck = true;
            if ($all[0].checked && !this.checked) {
                $chb.each(function(ind, elm) {
                    var $elm = $(elm);
                    if ($elm.val() == $this.val()) {
                        setTimeout(function() {
                            elm.checked = true
                            $elm.parent().find(".checkbox_img").addClass("checkbox_img_checked");
                            setFilterLink();
                        }, 0);
                    } else {
                        elm.checked = false;
                        $elm.parent().find(".checkbox_img").removeClass("checkbox_img_checked");
                    }
                });
                return true;
            }
            $chb.each(function(ind, elm) {
                var $elm = $(elm);
                if ($elm.val() == "0")
                    return true;
                if (!elm.checked)
                    allcheck = false;
            });
            if (allcheck) {
                $all[0].checked = true;
                $all.parent().find(".checkbox_img").addClass("checkbox_img_checked");
            } else {
                $all[0].checked = false;
                $all.parent().find(".checkbox_img").removeClass("checkbox_img_checked");
            }
        }
        setFilterLink();
    });
    $("input[name=c]").change(function() {
        setFilterLink();
    });
    setFilterLink();
    var timer, delay = 1000;
    $('#nick').on('input', function(e) {
        var newVal = $(this).val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($(this).val() != newVal) $(this).val(newVal);
        clearTimeout(timer);
        var $accountname = $(this).val();
        var $e = $("#nick").parent();
        var $r = $e[0];
        timer = setTimeout(function() {
            $.get("/ajax.php", {
                action: "profile_checkprofile",
                accountname: $accountname
            }, function(data, textstatus) {
                switch (data) {
                    case 2:
                        showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", $r);
                        break;
                    case 3:
                        showBalloon2("Ник не может быть более 16 знаков", $r);
                        break;
                    case 4:
                        showBalloon2("Указанный ник не соответствует правилам сайта", $r);
                        break;
                    case 6:
                        break;
                    case 7:
                        showBalloon2("Запрещено использовать спецсимволы: ' \" \\ / * < >", $r);
                        break;
                    case 9:
                        showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", $r);
                        break;
                    case -2000:
                        showActivationWnd();
                        break;
                    default:
                        $(".balloon").hide();
                        break;
                }
            }, "json");
        }, 1000);
    });
    var timer, delay = 1000;
    $('#nick1').on('input', function(e) {
        var newVal = $(this).val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($(this).val() != newVal) $(this).val(newVal);
        clearTimeout(timer);
        var $accountname = $(this).val();
        var $e = $("#nick1").parent();
        var $r = $e[0];
        timer = setTimeout(function() {
            $.get("/ajax.php", {
                action: "profile_checkprofile",
                accountname: $accountname
            }, function(data, textstatus) {
                switch (data) {
                    case 2:
                        showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", $r);
                        break;
                    case 3:
                        showBalloon2("Ник не может быть более 16 знаков", $r);
                        break;
                    case 4:
                        showBalloon2("Указанный ник не соответствует правилам сайта", $r);
                        break;
                    case 6:
                        break;
                    case 7:
                        showBalloon2("Запрещено использовать спецсимволы: ' \" \\ / * < >", $r);
                        break;
                    case 9:
                        showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", $r);
                        break;
                    case -2000:
                        showActivationWnd();
                        break;
                    default:
                        $(".balloon").hide();
                        break;
                }
            }, "json");
        }, 1000);
    });
    var timer_email, delay = 2000;
    $('#regemail').on('input', function(e) {
        var newVal = $(this).val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($(this).val() != newVal) $(this).val(newVal);
        var oEmail = $('#regemail');
        hideBalloon2(oEmail[0]);
    });
    $('#regemail').on('focusout', function(e) {
        clearTimeout(timer_email);
        var oEmail = $('#regemail');
        var email = /.+@.+\.(.{2,})/i;
        var all_ready = true;
        timer_email = setTimeout(function() {
            if (oEmail.val() == "") {
                showBalloon2("Поле не должно быть пустым", oEmail[0], 5000);
                all_ready = false;
            } else if (!email.test(oEmail.val())) {
                showBalloon2("Неверное значение электронной почты", oEmail[0], 5000);
                all_ready = false;
            }
            if (all_ready) {
                $.get("/ajax.php", {
                    action: "checkemail",
                    email: oEmail.val(),
                    nick: ""
                }, function(data, textstatus) {
                    var ready = true;
                    if (data.m != "0") {
                        showBalloon2("Пользователь с таким адресом уже зарегистрирован в системе", oEmail[0], 5000);
                        ready = false;
                    }
                }, "json");
            }
        }, 2000);
    });
    var timer_email, delay = 2000;
    $('#regemail1').on('input', function(e) {
        var newVal = $(this).val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($(this).val() != newVal) $(this).val(newVal);
        var oEmail = $('#regemail1');
        hideBalloon2(oEmail[0]);
    });
    $('#regemail1').on('focusout', function(e) {
        clearTimeout(timer_email);
        var oEmail = $('#regemail1');
        var email = /.+@.+\.(.{2,})/i;
        var all_ready = true;
        timer_email = setTimeout(function() {
            if (oEmail.val() == "") {
                showBalloon2("Поле не должно быть пустым", oEmail[0], 5000);
                all_ready = false;
            } else if (!email.test(oEmail.val())) {
                showBalloon2("Неверное значение электронной почты", oEmail[0], 5000);
                all_ready = false;
            }
            if (all_ready) {
                $.get("/ajax.php", {
                    action: "checkemail",
                    email: oEmail.val(),
                    nick: ""
                }, function(data, textstatus) {
                    var ready = true;
                    if (data.m != "0") {
                        showBalloon2("Пользователь с таким адресом уже зарегистрирован в системе", oEmail[0], 5000);
                        ready = false;
                    }
                }, "json");
            }
        }, 2000);
    });
    $("#callbackform").submit(function() {
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        var oCap = $('#kcaptcha');
        var res = true;
        if (!oCap.val()) {
            showBalloon2("Введите символы с картинки", oCap[0], 5000);
            res = false;
        }
        if (res) $('#callbackform input[type=submit]').attr('disabled', true);
        return res;
    });
    $("#registrationbannerform").submit(function() {
        $("#bannerregformsubmit").click();
        return false;
    });
    $("#bannerregformsubmit").click(function() {
        if (registrationProcess)
            return;
        registrationProcess = true;
        var oEmail = $('#regemail1');
        var oNick = $('#nick1');
        var oAd = $('#adrules1');
        var oPers = $('#persdata1');
        var oPwd = $("#registrationbannerform input[name=pwd1]");
        var oPromoCode = $("#promocode1");
        var email = /.+@.+\.(.{2,})/i;
        var all_ready = true;
        var newVal = oNick.val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if (oNick.val() != newVal) oNick.val(newVal);
        var newVal = oEmail.val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if (oEmail.val() != newVal) oEmail.val(newVal);
        if (oEmail.val() == "") {
            showBalloon2("Поле не должно быть пустым", oEmail[0], 5000);
            all_ready = false;
        } else if (!email.test(oEmail.val())) {
            showBalloon2("Неверное значение электронной почты", oEmail[0], 5000);
            all_ready = false;
        }
        if (!oNick.val()) {
            showBalloon2("Поле не должно быть пустым", oNick[0], 5000);
            all_ready = false;
        }
        if (oAd.attr("checked") != "checked") {
            showBalloon2("Для успешного завершения регистрации Вы должны согласиться с условиями", oAd[0], 5000);
            all_ready = false;
        }
        if (oPers.attr("checked") != "checked") {
            showBalloon2("<nobr>Вы должны согласиться на обработку персональных данных</nobr>", oPers[0], 5000);
            all_ready = false;
        }
        if (oPwd.val() == "") {
            showBalloon2("Поле не должно быть пустым", oPwd[0], 5000);
            all_ready = false;
        } else if (oPwd.val().length < 6) {
            showBalloon2("Пароль слишком короткий", oPwd[0], 5000);
            all_ready = false;
        }
        if (all_ready) {
            $.get("/ajax.php", {
                action: "checkemail",
                email: oEmail.val(),
                nick: oNick.val(),
                promocode: oPromoCode.val()
            }, function(data, textstatus) {
                var ready = true;
                if (data.m != "0") {
                    showBalloon2("Пользователь с таким адресом уже зарегистрирован в системе", oEmail[0], 5000);
                    ready = false;
                }
                if (data.n != "0") {
                    showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", oNick[0], 5000);
                    ready = false;
                }
                if (data.duplicate != "0") {
                    showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", oNick[0], 5000);
                    ready = false;
                }
                if (ready && data.pc != "0") {
                    $("#regform").hide();
                    $("#page_locker").fadeIn("normal", function() {
                        var d = $("<div id='promocode_notice' class='modalwin'><p style='text-align:center; font-size:1.2em; margin-bottom: -10px;'>Вы ввели неверный промо-код.<br><br><br></p><p style='text-align: center;'><span class='buttonorange' onclick='closePromoNotice(1);'>Ввести код еще раз</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='buttonorange' onclick='processWithoutPromoCode(1);'>Продолжить без кода</span></p></div>");
                        $("body").append(d);
                        d.show();
                    });
                    ready = false;
                } else {
                    $("#promocode_notice").remove();
                }
                if (ready && data.e != "0") {
                    $("#regform").hide();
                    $("#page_locker").fadeIn("normal", function() {
                        var d = $("<div id='email_notice' class='modalwin'><p style='text-align:center; font-size:1.2em; margin: 0 30px;'>Вы указали адрес электронной почты <strong>" + oEmail.val() + "</strong>. Важно: корректный адрес электронной почты - это единственная возможность восстановить пароль и доступ к личному аккаунту. Если указанный адрес является корректным, нажмите \"Продолжить\". Если указан некорректный адрес, Вы можете его изменить, нажав \"Назад\".<br><br></p><p style='text-align: center; padding-bottom: 30px;'><span class='buttonorange' onclick='closeEmailNotice(1);'>Назад</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='buttonorange' onclick='processWithoutEmail(1);'>Продолжить</span></p></div>");
                        $("body").append(d);
                        d.show();
                    });
                    ready = false;
                } else {
                    $("#email_notice").remove();
                }
                if (ready) {
                    $("#registrationbannerform")[0].submit();
                } else {
                    registrationProcess = false;
                }
            }, "json");
        } else {
            registrationProcess = false;
        }
    });
    $("#recall").click(function() {
        $('#forgotform form')[0].submit();
    });
    $('#imulated').mouseover(function() {
        $('.im_button').addClass('act');
        $(this).css('cursor', 'pointer');
    });
    $('#imulated').mouseout(function() {
        $('.im_button').removeClass('act');
    });
    $('#imulated').change(function() {
        $('.im_input input').val($(this).val());
    });
    $('.im_input input').click(function() {
        $('#imulated').trigger('click');
    });
    $('#updProgress3').click(function() {
        $(this).hide();
        $("#authform ,#divmymenu").stop().fadeOut("fast", function() {
            $("#loginbtn").removeClass("loginbtnsel");
            $(".loginbtnimg").removeClass("loginbtnimgsel");
        });
    });
    $("#commentbutton").click(function() {
        if ($(".commentblock").is(":hidden"))
            $(".commentblock").slideDown("fast");
        else
            $(".commentblock").slideUp("fast");
    });
});

function openRegForm() {
    $("#page_locker").show();
    $('#regform').show();
}

function ajaxFileUpload(upload_field) {
    var re_text = /\.jpg|\.gif|\.jpeg|\.png/i;
    var filename = upload_field.value;
    if (filename.search(re_text) == -1) {
        alert("File should be either jpg or gif or jpeg");
        upload_field.form.reset();
        return false;
    }
    document.getElementById('profileimg').innerHTML = '<div><img src="/img/cross.png" border="0" /></div>';
    upload_field.form.action = 'upload-picture.php';
    upload_field.form.target = 'upload_iframe';
    upload_field.form.submit();
    upload_field.form.action = '';
    upload_field.form.target = '';
    return true;
}

function $m(theVar) {
    return document.getElementById(theVar)
}

function remove(theVar) {
    var theParent = theVar.parentNode;
    theParent.removeChild(theVar);
}

function addEvent(obj, evType, fn) {
    if (obj.addEventListener)
        obj.addEventListener(evType, fn, true)
    if (obj.attachEvent)
        obj.attachEvent("on" + evType, fn)
}

function removeEvent(obj, type, fn) {
    if (obj.detachEvent) {
        obj.detachEvent('on' + type, fn);
    } else {
        obj.removeEventListener(type, fn, false);
    }
}

function isWebKit() {
    return RegExp(" AppleWebKit/").test(navigator.userAgent);
}

function ajaxUpload(form, url_action, id_element, html_show_loading, html_error_http) {
    var detectWebKit = isWebKit();
    $('#profileimg').innerHTML = '';
    form = typeof(form) == "string" ? $m(form) : form;
    var erro = "";
    if (form == null || typeof(form) == "undefined") {
        erro += "The form of 1st parameter does not exists.\n";
    } else if (form.nodeName.toLowerCase() != "form") {
        erro += "The form of 1st parameter its not a form.\n";
    }
    if ($m(id_element) == null) {
        erro += "The element of 3rd parameter does not exists.\n";
    }
    if (erro.length > 0) {
        alert("Error in call ajaxUpload:\n" + erro);
        return;
    }
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "ajax-temp");
    iframe.setAttribute("name", "ajax-temp");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");
    form.parentNode.appendChild(iframe);
    window.frames['ajax-temp'].name = "ajax-temp";
    var doUpload = function() {
        removeEvent($m('ajax-temp'), "load", doUpload);
        var cross = "javascript: ";
        cross += "window.parent.$m('" + id_element + "').innerHTML = document.body.innerHTML; void(0);";
        $m(id_element).innerHTML = html_error_http;
        $m('ajax-temp').src = cross;
        if (detectWebKit) {
            remove($m('ajax-temp'));
        } else {
            setTimeout(function() {
                remove($m('ajax-temp'))
            }, 250);
        }
    }
    addEvent($m('ajax-temp'), "load", doUpload);
    form.setAttribute("target", "ajax-temp");
    form.setAttribute("action", url_action);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");
    if (html_show_loading.length > 0) {
        $m(id_element).innerHTML = html_show_loading;
    }
    form.submit();
    form.setAttribute("action", "/mypage/save");
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");
    form.setAttribute("target", "");
    $('#profileimg').hide();
    $('#newimg').show();
}

function enter() {
    $('#in2').toggle();
    $('#in1').toggle();
    $('#txt2').toggle();
    $('#txt1').toggle();
}
setCursor = function(obj, l, g) {
    if (obj.setSelectionRange) obj.setSelectionRange(l, l);
    else {
        g = obj.createTextRange();
        g.collapse(true);
        g.moveStart(c, y);
        g.move(c, l);
        g.select();
    }
}
getCursor = function(obj, r, b) {
    if (obj.setSelectionRange) return [obj.selectionStart, obj.selectionEnd];
    else {
        r = d['selection'].createRange();
        b = 0 - r.duplicate().moveStart(c, y)
        return [b, b + r.text.length]
    }
}

function abidVisualOn(sid) {
    var $bidtuner = $("#bidtuner");
    if ($bidtuner.length > 0) {
        var $Switcher = $bidtuner.find(".switcher-img");
        var $Left = $bidtuner.find("div[name=leftlabel]");
        var $Right = $bidtuner.find("div[name=rightlabel]");
        var $lp = $bidtuner.find(".switcheraloader");
        $Switcher.removeClass("left").addClass("right");
        $Left.removeClass("selected").addClass("regular");
        $Right.removeClass("regular").addClass("selected");
        $Switcher.parent().find("input[value='2']")[0].checked = true;
        $Switcher.parent().find("input[value='1']")[0].checked = false;
    }
    $("#abidoptions_submit").hide();
    $("#abidoptions_save").hide();
    $("#abidoptions_stop").show();
    $("#itemdetail" + sid + " .btngreen").addClass("autobid");
    $("#item" + sid + " .btngreen").addClass("autobid");
    $("#itemtablerow" + sid + " .btngreen").addClass("autobid");
    $("#itemdetail" + sid + " .btngray").not('#sale_bidders').addClass("autobid");
    $("#item" + sid + " .btngray").not('#sale_bidders').addClass("autobid");
    $("#itemtablerow" + sid + " .btngray").not('#sale_bidders').addClass("autobid");
}

function abidVisualOff(sid) {
    var $bidtuner = $("#bidtuner");
    if ($bidtuner.length > 0) {
        var $Switcher = $bidtuner.find(".switcher-img");
        var $Left = $bidtuner.find("div[name=leftlabel]");
        var $Right = $bidtuner.find("div[name=rightlabel]");
        var $lp = $bidtuner.find(".switcheraloader");
        $Switcher.removeClass("right").addClass("left");
        $Right.removeClass("selected").addClass("regular");
        $Left.removeClass("regular").addClass("selected");
        $Switcher.parent().find("input[value='2']")[0].checked = false;
        $Switcher.parent().find("input[value='1']")[0].checked = true;
    }
    $("#abidoptions_submit").show();
    $("#abidoptions_save").hide();
    $("#abidoptions_stop").hide();
    $("#itemdetail" + sid + " .btngreen").removeClass("autobid");
    $("#item" + sid + " .btngreen").removeClass("autobid");
    $("#itemtablerow" + sid + " .btngreen").removeClass("autobid");
    $("#itemdetail" + sid + " .btngray").not("#sale_bidders").removeClass("autobid");
    $("#item" + sid + " .btngray").not("#sale_bidders").removeClass("autobid");
    $("#itemtablerow" + sid + " .btngray").not("#sale_bidders").removeClass("autobid");
}

function autoBidChange2(evt) {
    evt.stopPropagation();
    var $bidtuner = $("#bidtuner");
    var $lp = $bidtuner.find(".switcheraloader");
    var $dt = $bidtuner.find("input[value='1']")[0];
    if ($dt.checked) {
        var res = {};
        res["action"] = "autoBidOn";
        res["bidsaleid"] = $("#bidsaleid").val();
        $lp.show();
        $.post("/ajax.php", res, function(data, textstatus) {
            $lp.hide();
            var b = $.trim(data);
            switch (b) {
                case '1':
                    var o = $bidtuner.parents(".item").find(".favorite");
                    if (!o.hasClass("favorite-sel")) o.click();
                    o = $bidtuner.parents(".itemdetail").find(".favorite");
                    if (!o.hasClass("favorite-sel")) o.click();
                    abidVisualOn(res["bidsaleid"]);
                    break;
                case '-9':
                    showBalloon2("В аукционе могут принимать участие только пользователи, которые ни разу не становились победителями аукционов", $bidtuner[0], 4000);
                    break;
                case '-10':
                    showBalloon2("В аукционе могут принимать участие только пользователи, которые уже становились победителями аукционов", $bidtuner()[0], 4000);
                    break;
                case '-11':
                    showBalloon2("Вы сделали максимальное число ставок,  возможное на данном аукционе", $bidtuner[0], 5000);
                    break;
                case '-1':
                    $a = $("#autobidoptbtn");
                    if (!$a.hasClass("autobidoptbtnpressed")) $a.click();
                    showBalloon2("Пожалуйста, настройте параметры автоставки.", $bidtuner[0]);
                    break;
                case '-3':
                    openRegForm();
                    showBalloon2("Вы должны войти в систему или зарегистрироваться", $("#regform")[0]);
                    break;
                case '-4':
                    var btn = null;
                    if ($("#autobidoptions").css("opacity") == 0)
                        btn = $bidtuner;
                    else
                        btn = $("#abidoptions_submit");
                    showBalloon2("Вы начали оформление заказа по данному аукциону. Если Вы хотите продолжить игру на аукционе, отмените свой заказ. <a href='#' style='text-decoration:underline; color: #FFFFFF !important;' onclick='event.preventDefault(); event.stopPropagation(); buyItNow(Number(String($(this).parents(\"[id^=item]\").attr(\"id\")).replace(/\\D+/g,\"\")), this); return true;'>Перейти</a>", btn[0], 10000);
                    break;
                case '-5':
                    var btn = null;
                    if ($("#autobidoptions").css("opacity") == 0)
                        btn = $bidtuner;
                    else
                        btn = $("#abidoptions_submit");
                    showBalloon2("Сегодня Вы уже стали победителем аукциона на этот товар и можете принять участие в следующем аукционе на него только через сутки", btn[0], 7000);
                    break;
                case '-2000':
                    showActivationWnd();
                    break;
            }
        });
    } else {
        var res = {};
        res["action"] = "autoBidOff";
        res["bidsaleid"] = $("#bidsaleid").val();
        $lp.show();
        $.post("/ajax.php", res, function(data, textstatus) {
            $lp.hide();
            var b = $.trim(data);
            switch (b) {
                case '1':
                    if ($("#editflag").val() == "1") {
                        cancelAutobidChange(true);
                        $("#editflag").val("0");
                    }
                    abidVisualOff(res["bidsaleid"])
                    break;
                case '-3':
                    openRegForm();
                    showBalloon2("Вы должны войти в систему или зарегистрироваться", $("#regform")[0]);
                    break;
            }
        });
    }
}
var bindCheckPwd = function() {
    $("input[data-checkpwd=check]").unbind("keyup").keyup(function() {
        var $this = $(this);
        var val = $this.val();
        var $t = $this.parent().parent().find("#check_" + $this.attr("name"));
        var $c = $this.parent().parent().find("#hcheck_" + $this.attr("name"));
        if ((val.length < 6) && (val.length > 0))
            $t.css("color", "red").text("очень короткий");
        else if (val.length >= 6) {
            FS_CHKPWD(val, "hcheck_" + $this.attr("name"));
            switch ($c.text()) {
                case "Простой":
                    $t.css("color", "red").text("простой");
                    break;
                case "Средний":
                    $t.css("color", "#ed7a1b").text("средний");
                    break;
                case "Сложный":
                    $t.css("color", "green").text("сложный");
                    break;
            }
        } else
            $t.text("");
    });
}

function setFormEvents() {
    $('.sel_wrap').unbind("click").click(function() {
        if ($(this).find('.sel_imul').is(':visible')) {
            $(this).find('.sel_imul').hide();
            $(this).parent().find('.sel_img').removeClass('opened');
            $(this).parent().find('.sel_selected').removeClass('selopened');
        } else {
            $('.sel_imul').hide();
            $(this).find('.sel_imul').show();
            $(this).find('.sel_img').addClass('opened');
            $(this).find('.sel_selected').addClass('selopened');
            $(this).find('.sel_options').tinyscrollbar_update();
        }
    });
    $('.sel_wrap .scrollbar').unbind("click").click(function(evt) {
        evt.stopPropagation();
    });
    $('.sel_option').unbind("click").click(function() {
        var $this = $(this);
        var tektext = $(this).html();
        $this.parents(".sel_wrap").find('.selected-text').html(tektext);
        var tekval = $(this).attr('value');
        tekval = typeof(tekval) != 'undefined' ? tekval : tektext;
        $this.parents(".sel_wrap").find('option').removeAttr('selected').each(function() {
            if ($(this).val() == tekval) {
                $(this).attr('selected', 'select');
            }
        });
        $this.parents(".sel_wrap").find('select').change();
    });
    $('.sel_wrap').unbind("mouseover").mouseover(function() {
        selenter = true;
    });
    $('.sel_wrap').unbind("mouseout").mouseout(function() {
        selenter = false;
    });
    $('.checkbox_img').unbind("click").click(function() {
        var oInp = $(this).parent().children('input');
        if ($(this).parent().children('input')[0].checked) {
            oInp[0].checked = false;
            oInp.change();
            $(this).removeClass("checkbox_img_checked");
        } else {
            oInp[0].checked = true;
            oInp.change();
            $(this).addClass("checkbox_img_checked");
        }
    });
    $('.radio-button-wrapper').unbind("click").click(function() {
        var oInp = $(this).children('input');
        if (!oInp[0].checked && !oInp[0].disabled) {
            oInp[0].checked = true;
            oInp.change();
            $("input[name=" + oInp.attr("name") + "]").each(function() {
                if (this.checked)
                    $(this).parent().children('.image').addClass("checked");
                else
                    $(this).parent().children('.image').removeClass("checked");
            });
        }
    });
    $(".spin-edit-wrapper input[type=text]").unbind("keypress").keypress(function() {
        $(this).change();
    }).blur(function() {
        var rgNUM = new RegExp("[0-9]+");
        var $this = $(this);
        var val = parseInt($this.val());
        if (rgNUM.test(val)) {
            var minVal = null;
            var maxVal = null;
            var a = $this.parent().find("input[name=minValue]");
            if (a.length > 0) minVal = parseInt(a.val());
            var a = $this.parent().find("input[name=maxValue]");
            if (a.length > 0) maxVal = parseInt(a.val());
            if (minVal && minVal > val) $this.val(minVal);
            if (maxVal && maxVal < val) $this.val(maxVal);
        }
    });
    $(".spin-edit-wrapper .spinup").unbind("click").click(function() {
        var inc = function(v) {
            if (!isNaN(v = parseInt(v, 10)))
                v++;
            return v;
        }
        var oInp = $(this).parent().children("input[type=text]");
        var val = oInp.val();
        var p;
        var rgTM = new RegExp("[0-9]{2}:[0-9]{2}:[0-9]{2}");
        var rgNUM = new RegExp("[0-9]+");
        if (rgTM.test(val)) {
            tm = val.split(":");
            p = getCursor(oInp[0]);
            if (p[0] < 3) {
                tm[0] = inc(tm[0]);
                if (tm[0] == 24)
                    tm[0] = 0;
                if (tm[0] < 10) tm[0] = "0" + tm[0];
            }
            if (p[0] >= 3 && p[0] < 6) {
                tm[1] = inc(tm[1]);
                if (tm[1] == 60) tm[1] = 0;
                if (tm[1] < 10) tm[1] = "0" + tm[1];
            }
            if (p[0] >= 6) {
                tm[2] = inc(tm[2]);
                if (tm[2] == 60) tm[2] = 0;
                if (tm[2] < 10) tm[2] = "0" + tm[2];
            }
            oInp.val(tm[0] + ":" + tm[1] + ":" + tm[2]);
            setCursor(oInp[0], p[0]);
            oInp.change();
        } else if (rgNUM.test(val)) {
            var maxVal = null;
            var a = $(this).parent().find("input[name=maxValue]");
            if (a.length > 0)
                maxVal = a.val();
            var nextVal = inc(val);
            if (maxVal == null || (maxVal != null && nextVal <= maxVal))
                oInp.val(nextVal);
            oInp.change();
        }
    });
    $(".spin-edit-wrapper .spindown").unbind("click").click(function() {
        var dec = function(v) {
            if (!isNaN(v = parseInt(v, 10)))
                v--;
            if (v < 0) v = 0;
            return v;
        }
        var oInp = $(this).parent().children("input[type=text]");
        var val = oInp.val();
        var p;
        var rgTM = new RegExp("[0-9]{2}:[0-9]{2}:[0-9]{2}");
        var rgNUM = new RegExp("[0-9]+");
        if (rgTM.test(val)) {
            tm = val.split(":");
            p = getCursor(oInp[0]);
            if (p[0] < 3) {
                if (tm[0] == 0)
                    tm[0] = 23;
                else
                    tm[0] = dec(tm[0]);
                if (tm[0] < 10) tm[0] = "0" + tm[0];
            }
            if (p[0] >= 3 && p[0] < 6) {
                if (tm[1] == 0)
                    tm[1] = 59;
                else
                    tm[1] = dec(tm[1]);
                if (tm[1] < 10) tm[1] = "0" + tm[1];
            }
            if (p[0] >= 6) {
                if (tm[2] == 0)
                    tm[2] = 59;
                else
                    tm[2] = dec(tm[2]);
                if (tm[2] < 10) tm[2] = "0" + tm[2];
            }
            oInp.val(tm[0] + ":" + tm[1] + ":" + tm[2]);
            oInp.change();
            setCursor(oInp[0], p[0]);
        } else if (rgNUM.test(val)) {
            var minVal = null;
            var a = $(this).parent().find("input[name=minValue]");
            if (a.length > 0)
                minVal = a.val();
            var nextVal = dec(val);
            if (minVal == null || (minVal != null && nextVal >= minVal))
                oInp.val(nextVal);
            oInp.change();
        }
    });
    $('img[name=passshow]').unbind("click").click(function() {
        var oInp = $(this).parent().find("input");
        var name = oInp.attr("name");
        var id = oInp.attr("id");
        var check = oInp.attr("data-checkpwd");
        var val = oInp.val();
        var w = oInp.css("width");
        var type = oInp.attr("type");
        var ph = oInp.attr("placeholder");
        var tt = oInp.attr("title");
        oInp.replaceWith("<input class='forminput' style='width:" + w + "' name='" + name + "' placeholder='" + ph + "' title='" + tt + "' id='" + id + "' required type='" + (type == "password" ? "text" : "password") + "' maxlength='50' value='" + val + "' " + (check ? "data-checkpwd='check'" : "") + ">");
        $(this).attr('src', (type == "password" ? "/img/eyestay.png" : "/img/eyesleep.png"));
        $(this).attr('title', (type == "password" ? "Скрыть пароль" : "Показать пароль"));
        bindCheckPwd();
    });
    $('#persdata1, #adrules1, #persdata, #adrules').change(function() {
        var $this = $(this);
        $this.parent().find(".balloon").remove();
    });
    $('#20x7SoundsOn').change(function() {
        if (this.checked) {
            var $audio = $('#ascand20x7newplayer');
            if ($audio.length > 0) {
                $audio[0].currentTime = 0;
                $audio[0].play();
            }
        }
    });
}
var changeDateTime = function(elm) {
    var $this = $(elm);
    var dstr = $this.val();
    var a = dstr.split(" ");
    if (a.length == 2)
        $this.parent().children("span").html(a[1] + "&nbsp;&nbsp;<small style='color:#222B35;'>" + a[0] + "</small>");
}
var setFilterLink = function() {
    var $link = $("#setFilter");
    if ($link.length == 0)
        return true;
    var c = "c=";
    var t = "t=";
    var y = "y=";
    var $c = $("input[name=c]");
    var $t = $("input[name=t]");
    var $t0 = $("input[name=t][value=0]");
    var $y = $("input[name=y]");
    var $y0 = $("input[name=y][value=0]");
    var zpt;
    $c.each(function(ind, elm) {
        if (elm.checked)
            c = "c=" + elm.value;
    });
    zpt = "";
    if ($t0[0].checked) {
        t += "0";
    } else {
        $t.each(function(ind, elm) {
            if (elm.checked) {
                t += zpt + elm.value;
                zpt = ",";
            }
        })
    }
    zpt = "";
    if ($y0[0].checked) {
        y += "0";
    } else {
        $y.each(function(ind, elm) {
            if (elm.checked) {
                y += zpt + elm.value;
                zpt = ",";
            }
        })
    }
    $link.attr("href", "/auction/setfilter?" + c + "&" + t + "&" + y);
};
$(function() {
    $(".cat-menu-wrapper").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#category_menu").css("left") == "0px") {
                $("#category_menu").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $(".cat-menu-wrapper span").css("background-position", "0 0");
            } else {
                $("#category_menu").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $(".cat-menu-wrapper span").css("background-position", "-24px 0");
            }
        });
        event.stopPropagation();
    });
    $("#auction_menu").parent().click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#auction_menu").css("left") == "0px") {
                $("#auction_menu").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $("#auction_menu").parent().find("span").css("background-position", "0 0");
            } else {
                $("#auction_menu").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $("#auction_menu").parent().find("span").css("background-position", "-24px 0");
            }
        });
        event.stopPropagation();
    });
    $("#auctype_menu").parent().click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#auctype_menu").css("left") == "0px") {
                $("#auctype_menu").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $("#auctype_menu").parent().find("span").css("background-position", "0 0");
            } else {
                $("#auctype_menu").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $("#auctype_menu").parent().find("span").css("background-position", "-24px 0");
            }
        });
        event.stopPropagation();
    });
    $("#points_btn").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#points_win").css("left") == (oContext.offsetLeft - 70) + "px") {
                $("#points_win").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
            } else {
                $("#points_win").stop().css("left", (oContext.offsetLeft - 70) + "px").animate({
                    opacity: "1"
                }, 300);
            }
        });
        event.stopPropagation();
    });
    $("#refill_btn").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#refill_win").css("left") == "80px") {
                $("#refill_win").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
            } else {
                $("#refill_win").stop().css("left", "80px").animate({
                    opacity: "1"
                }, 300);
            }
        });
        event.stopPropagation();
    });
    $("#refill_submit").click(function() {
        var sum = $("#refill_summ").val();
        if (sum != "")
            $.get("/ajax.php", {
                action: "refill",
                sum: sum
            }, function(data, textstatus) {
                if (data == "autherr")
                    showBalloon2("Вы должны войти в систему или зарегистрироваться", $("#refill_summ")[0], 5000);
                else if (data == "-2000")
                    showActivationWnd();
                else {
                    $("#refill_submit").append(data);
                    $("#roboform").submit();
                }
            });
        else
            showBalloon2("Введите сумму пополнения счета", $("#refill_summ")[0], 5000);
    });
    $("div[name=buybidsbutton]").click(function() {
        var $this = $(this);
        var o = this;
        var s = new String(o.id);
        var id = null;
        var btnid = (this.id ? this.id : "");
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        $("input[name=bidspacket]").each(function(ind, elm) {
            if (elm.checked) id = $(elm).val();
        });
        if (id) {
            var pmttype = "";
            var pstype = "";
            $("input[name=pmttype]").each(function(ind, elm) {
                if (elm.checked) pmttype = $(elm).val();
            });
            $("input[name=pstype]").each(function(ind, elm) {
                if (elm.checked) pstype = $(elm).val();
            });
            var code = $("#ppdc").val();
            var fbpdc = $("#fbpdc").val();
            $.get("/ajax.php", {
                action: "buybids",
                pid: id,
                pmttype: pmttype,
                btnid: btnid,
                pstype: pstype,
                code: code,
                fbpdc: fbpdc
            }, function(data, textstatus) {
                if (data == "autherr") {
                    var regex = /\/([\w\d]*?)\/(\d*)$/,
                        results = regex.exec(location.href);
                    if (results)
                        if (results[1] == 'blackfriday' && results[2] > 0) {
                            window.frompage = 'blackfriday';
                            window.subpage = results[2];
                        }
                    $("#top_btn_registration").click();
                    if (results)
                        if (results[1] == 'blackfriday' && results[2] > 0) {
                            $('#registrationform input[name=frompage]').val('blackfriday');
                            $('#registrationform input[name=subpage]').val(results[2]);
                        }
                    setTimeout('showBalloon2("Для покупки ставок, пожалуйста, зарегистрируйтесь или войдите в свой профиль.", $("#regform")[0], 5000);', 500);
                } else if (data == "paramerr")
                    showBalloon2("Ошибка в параметрах", o, 5000);
                else if (data == "-2000")
                    showActivationWnd();
                else {
                    $(".content-block").append(data);
                    if (pmttype == "MixplatMTSRIBR" || pmttype == "MixplatBeelineRIBR" || pmttype == "MixplatTele2RIBR" || pmttype == "Megafon")
                        showPayByMobileWnd(pmttype, pstype);
                    else {
                        $("#roboform").submit();
                    }
                }
            });
        } else
            showBalloon2("Выберите пакет ставок", o, 5000);
    });
    $(".itempacket").each(function(ind, elm) {
        if ((ind == 0) || (ind) % 4 == 0)
            $(elm).css("padding-left", "0px");
    });
    $("#pwdrecoverlogin").click(function() {
        $("#top_btn_registration").click();
    })
});;
var tg = "";
var ctg = "";
var blockPage = false;
var stateBlocked = false;
var stateWorkType = 0;
var bb = {};
var wsconn;
var autobidder;
var wsEnable;
var tmadd = 1000;
$(function() {
    var auth = getCookie('bm_auth');
    if (getCookie("bm_auth") == 1) tmadd = 0;
    $('div[name=conditions]').filter(":first").css("border-top", "none 0px").end().filter(":last").css("border-bottom", "none 0px");
    $('div[name=description]').filter(":first").css("border-top", "none 0px").end().filter(":last").css("border-bottom", "none 0px");
    if (!(gSiteState == -1 || gSiteState == 0 || gSiteState == 4))
        executeRequester();
    setEvents();
    calcTimers();
});

function stateChanged(elmId, key, eid, data, bgcolor, color) {
    var sale = data.sales[key];
    var evt = data.sales[key].events[eid];
    var $item = $("#" + elmId);
    var nick;
    var text;
    if ("itemtablerow" + key == elmId)
        nick = evt.nickshort;
    else
        nick = evt.nick;
    $item.find(".userinfo div").html((evt.nick == myNick ? "<strong>" + nick + "</strong>" : nick)).css("color", "#d82231").animate({
        color: "#3B4752"
    }, 700);
    if (sale.proctext != "~") {
        if (elmId != ("itemdetail" + key))
            text = sale.proctext;
        else
            text = sale.proctext2;
        $item.find("#buyitnow_" + key).html(text);
    }
    $item.find(".userinfo img").attr("src", evt.avatar);
    if (sale.bidcount != '~')
        $item.find(".bidcount").text(sale.bidcount);
    if (sale.bidslimit != '~')
        $("#" + elmId + " #balance_reserve").text(sale.bidslimit);
    $item.find("span[name=timer]").html(getTimer(evt.time, $item.find("input[name=increment]").val()));
    $item.find("input[name=timer]").val(evt.time);
    $item.find(".realprice .bigspan").html(evt.price);
    $item.find(".realprice, .watch").css({
        "background-color": "#d82231",
        "color": "white"
    }).animate({
        backgroundColor: bgcolor,
        color: color
    }, 700);
    $item.find(".bidloader").hide();
    var $l = $item.find(".countinfo");
    if ($l.length > 0) {
        var $lf = $l.find("li:first td");
        if (!$lf.length > 0 || !(($($lf[1]).text() == evt.nick) && ($($lf[2]).text() == evt.bidprice + " руб."))) {
            var $li = $("<li style='display:none;'>&nbsp;</li>");
            var $tr = $("<table style='display:none;margin:0;border-collapse:collapse;width:100%;'><tr><td style='text-align:left;padding:0;color:#222B35;width:22%;'>" +
                convDate(evt.bidtm, false, false, true) + "</td><td style='text-align:left;padding:0;'>" +
                (evt.nick == myNick ? "<strong>" + evt.nick + "</strong>" : evt.nick) + "</td><td style='text-align:right;padding:0;color:#222B35; width:30%;'>" + evt.bidprice + " руб.</td></tr></table>");
            $l.find("ul").prepend($li);
            $l.find("li[name=nobids]").fadeOut("normal");
            $li.slideDown("normal", function() {
                $li.html("");
                $li.append($tr);
                $tr.fadeIn("normal");
            });
            var $lis = $l.find("li");
            if ($lis.length > 10) $l.find("li:last").remove();
        }
    }
}

function dispatchMessages() {}

function updateSales(data) {
    if (typeof data != 'object') {
        return false;
    }
    $("input[name=changeid]").val(data.changeid);
    if (data.balance != '~') {
        $("#balance_value").text(data.balance);
        $("input[name=bidscount]").val(data.balance);
    }
    var autobidsfinished = data.autobidsfinished;
    if (autobidsfinished.length > 0) {
        var ab = autobidsfinished.split(",");
        for (i = 0; i < ab.length; i++) {
            switchOffAutoBid(ab[i]);
        }
    }
    for (var key in data.sales) {
        if (data.sales[key].autobidstate != 'none') {
            if (data.sales[key].autobidstate == 'on' && !($("#itemdetail" + key + " .btngreen").hasClass("autobid") || $("#item" + key + " .btngreen").hasClass("autobid") || $("#itemtablerow" + key + " .btngreen").hasClass("autobid") || $("#itemdetail" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#item" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#itemtablerow" + key + " .btngray").not('#sale_bidders').hasClass("autobid")))
                abidVisualOn(key);
            if (data.sales[key].autobidstate == 'off' && ($("#itemdetail" + key + " .btngreen").hasClass("autobid") || $("#item" + key + " .btngreen").hasClass("autobid") || $("#itemtablerow" + key + " .btngreen").hasClass("autobid") || $("#itemdetail" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#item" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#itemtablerow" + key + " .btngray").not('#sale_bidders').hasClass("autobid")))
                switchOffAutoBid(key, false);
        }
        if (data.sales[key].autobids != '~')
            $("#itemdetail" + key + " span[name=autobidid]").text(data.sales[key].autobids);
        for (var eid in data.sales[key].events) {
            if (data.sales[key].events[eid].changetype == "1") {
                var can_vis = true;
                var $rows = $(".countinfo li");
                $rows.each(function(ind, elm) {
                    var $elm = $(elm);
                    var tmpdt = convDate(data.sales[key].events[eid].bidtm, false, false, true);
                    var tmpnick = data.sales[key].events[eid].nick;
                    var tmpprice = data.sales[key].events[eid].bidprice;
                    var $td = $elm.find("td");
                    if (($($td[0]).text() == tmpdt) && ($($td[1]).text() == tmpnick) && ($($td[2]).text() == tmpprice))
                        can_vis = false;
                });
                if (can_vis && $("#itemdetail" + key).length > 0 && data.sales[key].autobidsmax != '~') {
                    var $bs = $("#itemdetail" + key + " #bidsummax");
                    var $fl = $("#itemdetail" + key + " #editflag");
                    var $hbs = $("#itemdetail" + key + " #hbidsummax");
                    $hbs.val(data.sales[key].autobidsmax);
                    if ($fl.val() == "0") $bs.val(data.sales[key].autobidsmax);
                }
                stateChanged("item" + key, key, eid, data, "#c5cfd9", "#3B4752");
                $("#item" + key).css("border-color", "#C5CFD9");
                stateChanged("itemdetail" + key, key, eid, data, "#778797", "#FFFFFF");
                stateChanged("itemtablerow" + key, key, eid, data, "#c5cfd9", "#3B4752");
                if (typeof __active_sales == 'object' && __active_sales[key])
                    __active_sales[key].addD(data.sales[key].events[eid]);
            } else if (data.sales[key].events[eid].changetype == "2") {
                if (st == "history")
                    location.assign(location.href);
                else {
                    var elm = $("#item" + key);
                    var elmtr = $("#itemtablerow" + key);
                    var elmcp = $("#itemdetail" + key);
                    if (elm.length && typeof(data.sales[key].events[eid].html.main) != "undefined") {
                        var html = "";
                        var nelm = $(data.sales[key].events[eid].html.main);
                        nelm.find(".data-dt").each(function(ind, e) {
                            var $elm = $(e);
                            $elm.text(convDate($elm.text()));
                        });
                        nelm.find("span[name=timer]").each(function(ind, elm) {
                            var $elm = $(elm);
                            $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                            $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                        });
                        elm.replaceWith(nelm);
                    }
                    if (elm.length && typeof(data.sales[key].events[eid].html.mylots) != "undefined") {
                        var html = "";
                        var nelm = $(data.sales[key].events[eid].html.mylots);
                        nelm.find(".data-dt").each(function(ind, e) {
                            var $elm = $(e);
                            $elm.text(convDate($elm.text()));
                        });
                        nelm.find("span[name=timer]").each(function(ind, elm) {
                            var $elm = $(elm);
                            $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                            $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                        });
                        elm.replaceWith(nelm);
                        var gmboarda = $("#active_lot");
                        var gmboardf = $("#future_lot");
                        if (gmboarda.length > 0) {
                            if (gmboarda.parent().is(":hidden")) gmboarda.parent().show();
                            var btn = gmboarda.find(".card-btn");
                            var cardcls = "";
                            var tablecls = "";
                            if (btn.hasClass("pressed"))
                                tablecls = " hidden";
                            else
                                cardcls = " hidden";
                            if (gmboarda.find(".card-content").length == 0) {
                                gmboarda.find(".static-content").addClass("hidden");
                                gmboarda.append("<div class='card-content" + cardcls + "' style='min-height:auto !important;'><div name='cleared' style='clear:left; height: 0px;'></div></div>");
                                gmboarda.append("<div class='table-content" + tablecls + "'><div class='table-lot'><table><tr><th><div>Лоты</div></th><th><div>Время</div></th><th><div>Цена</div></th><th><div>Игрок</div></th><th><div>Ставки</div></th><th><div>Статус</div></th></tr></table></div></div>");
                            }
                            gmboardf.find(".item[name=lotactive]").insertBefore(gmboarda.find("*[name=cleared]")).each(function() {
                                var id = this.id.replace("item", "");
                                var $tr = gmboarda.find(".table-lot tr.hvr:first");
                                if ($tr.length > 0)
                                    gmboardf.find("#itemtablerow" + id).insertBefore($tr);
                                else
                                    gmboardf.find("#itemtablerow" + id).insertAfter(gmboarda.find(".table-lot tr:first"));
                            });
                            gmboarda.removeClass('hidden');
                            var $fut = $("#future_lot");
                            if ($fut.find(".item").length == 0) {
                                gmboarda.find(".table-content").css("min-height", "auto !important");
                                $fut.parents(".gameboard-wrapper").remove();
                                $(".gameboard-wrapper").removeClass("hidden");
                            }
                        }
                    }
                    if (elmtr.length && typeof(data.sales[key].events[eid].html.tablerow) != "undefined") {
                        $('.datetime-wrapper input[id]').removeClass("hasDatepicker").removeClass("calendarclass").removeAttr('id').unbind().remove();
                        var nelm = $(data.sales[key].events[eid].html.tablerow);
                        nelm.find(".data-dt").each(function(ind, e) {
                            var $elm = $(e);
                            $elm.text(convDate($elm.text()));
                        });
                        nelm.find("span[name=timer]").each(function(ind, elm) {
                            var $elm = $(elm);
                            $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                            $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                        });
                        elmtr.replaceWith(nelm);
                    }
                    if (elmcp.length && typeof(data.sales[key].events[eid].html.controltab) != "undefined") {
                        var nelm = $(data.sales[key].events[eid].html.controltab);
                        nelm.find(".data-dt").each(function(ind, e) {
                            var $elm = $(e);
                            $elm.text(convDate($elm.text()));
                        });
                        nelm.find(".data-tm").each(function(ind, elm) {
                            var $elm = $(elm);
                            $elm.text(convDate($elm.text(), false, false, true));
                        });
                        nelm.find("span[name=timer]").each(function(ind, elm) {
                            var $elm = $(elm);
                            $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                            $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                        });
                        nelm.find('.datetime-wrapper input[id]').each(function(ind, elm) {
                            var $this = $(elm);
                            $this.fadeTo(0, 0);
                            $this.val(convDate($this.val(), true, false));
                            $this.datetimepicker({
                                onSelect: function() {
                                    changeDateTime(this);
                                }
                            });
                            $(".ui-datepicker").click(function(evt) {
                                evt.stopPropagation();
                            });
                            changeDateTime(elm);
                        });
                        elmcp.replaceWith(nelm);
                    }
                    setEvents();
                    setAutobidEvents();
                    setFormEvents();
                    $("#itemdetail" + key + " #bidtuner").unbind("click").click(autoBidChange2);
                }
            }
        }
    }
}

function executeRequester() {
    if (ac == "sale" || ac == "auction" || ac == "welcome" || (ac == "profile" && (st == "myauction" || st == "history")) || ac == "product" || ac == "market") {
        lotState(false);
    }
}

function calcTimers() {
    $("span[name=timer]").each(function() {
        var $this = $(this)
        var $stm = $this.parent().find("input[name=timer]");
        var tm = getTimer($stm.val(), $this.parent().find("input[name=increment]").val());
        if (tm == "00:00:05") {
            var a = $(this).parents(".item");
            if (a.length > 0) {
                changeBgColor(a[0].id, "#778797");
                a.css({
                    "border-color": "#778797"
                });
            }
            var a = $(this).parents(".controlpanel");
            if (a.length > 0) {
                changeBgColor(a[0].id, "#3F4E5D");
            }
            var a = $(this).parents("tr[name=itemrow]");
            if (a.length > 0) {
                changeBgColor(a[0].id, "#3F4E5D");
            }
        }
        if (tm != "00:00:00") $(this).html(tm);
    });
    setTimeout(function() {
        calcTimers();
    }, 500);
}
var changeBgColor = function(elmId, bgcolor) {
    $("#" + elmId + " .realprice, #" + elmId + " .watch").css({
        "background-color": bgcolor,
        "color": "white"
    });
}

function strip_tags(input, allowed) {
    allowed = (((allowed || '') + '').toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}

function lotState(single) {
    var t0 = (new Date()).getTime();
    if (!single) single = false;
    if (stateBlocked) {
        if (!single && stateWorkType == 2)
            setTimeout("lotState(false)", 50);
        return false;
    }
    stateBlocked = true;
    stateWorkType = (single ? 2 : 1);
    var bidVisual = function(elmId, key, eid, data, bgcolor, color) {
        if ("itemtablerow" + key == elmId)
            $("#" + elmId + " .userinfo div").html((data.sales[key].events[eid].nick == myNick ? "<strong>" + data.sales[key].events[eid].nickshort + "</strong>" : data.sales[key].events[eid].nickshort)).css("color", "#d82231").animate({
                color: "#3B4752"
            }, 700);
        else
            $("#" + elmId + " .userinfo div").html((data.sales[key].events[eid].nick == myNick ? "<strong>" + data.sales[key].events[eid].nick + "</strong>" : data.sales[key].events[eid].nick)).css("color", "#d82231").animate({
                color: "#3B4752"
            }, 700);
        if (data.sales[key].proctext != "~") {
            if (elmId != ("itemdetail" + key))
                $("#" + elmId + " #buyitnow_" + key).html(data.sales[key].proctext);
            else
                $("#buyitnowpanel_" + key + " #buyitnow_" + key).html(strip_tags(data.sales[key].proctext2, "<br>,<strong>"));
        }
        $("#" + elmId + " .userinfo img").attr("src", data.sales[key].events[eid].avatar);
        if (data.sales[key].bidcount != '~') {
            $("#" + elmId + " .bidcount").text(data.sales[key].bidcount);
        }
        if (data.sales[key].bidslimit != '~') {
            var elm = $("#" + elmId + " #balance_reserve");
            if (elm.length > 0) {
                $("#" + elmId + " #balance_reserve").text(data.sales[key].bidslimit);
                $('.spin-edit-wrapper input[name=maxValue]').val(data.sales[key].bidslimit);
            }
        }
        $("#" + elmId + " span[name=timer]").html(getTimer(data.sales[key].events[eid].time, $("#" + elmId + " input[name=increment]").val()));
        $("#" + elmId + " input[name=timer]").val(data.sales[key].events[eid].time);
        $("#" + elmId + " .realprice .bigspan").html(data.sales[key].events[eid].price);
        $("#" + elmId + " .realprice, #" + elmId + " .watch").css({
            "background-color": "#d82231",
            "color": "white"
        }).animate({
            backgroundColor: bgcolor,
            color: color
        }, 700);
        $("#" + elmId + " .bidloader").hide();
        var $l = $("#" + elmId + " .countinfo");
        if ($l.length > 0) {
            var $lf = $l.find("li:first td");
            if (!$lf.length > 0 || !(($($lf[1]).text() == data.sales[key].events[eid].nick) && ($($lf[2]).text() == data.sales[key].events[eid].bidprice + " руб."))) {
                var $li = $("<li style='display:none;'>&nbsp;</li>");
                var $tr = $("<table style='display:none;margin:0;border-collapse:collapse;width:100%;'><tr><td style='text-align:left;padding:0;color:#222B35;width:22%;'>" +
                    convDate(data.sales[key].events[eid].bidtm, false, false, true) + "</td><td style='text-align:left;padding:0;'>" +
                    (data.sales[key].events[eid].nick == myNick ? "<strong>" + data.sales[key].events[eid].nick + "</strong>" : data.sales[key].events[eid].nick) + "</td><td style='text-align:right;padding:0;color:#222B35; width:30%;'>" + data.sales[key].events[eid].bidprice + " руб.</td></tr></table>");
                $l.find("ul").prepend($li);
                $l.find("li[name=nobids]").fadeOut("normal");
                $li.slideDown("normal", function() {
                    $li.html("");
                    $li.append($tr);
                    $tr.fadeIn("normal");
                });
                var $lis = $l.find("li");
                if ($lis.length > 10) $l.find("li:last").remove();
            }
        }
    }
    if (ac == "welcome" || ac == "sale" || ac == "auction" || (ac == "profile" && (st == "myauction" || st == "history")) || ac == "product") {
        setTimeout(function() {
            var changeid = $("input[name=changeid]").val();
            var lotlist = [];
            var lock = true;
            $(".item[name!=lotcompleted]").each(function() {
                var id = new String(this.id);
                var ispromo = $(this).parent().parent().hasClass("promo-block");
                var rentype = "main";
                if (ac == "profile" && st == "myauction" && !ispromo)
                    rentype = "mylots";
                lotlist.push({
                    saleid: id.replace("item", ""),
                    rendertype: rentype
                });
            });
            $("tr[name=itemrow]").each(function() {
                var id = new String(this.id);
                lotlist.push({
                    saleid: id.replace("itemtablerow", ""),
                    rendertype: "tablerow"
                });
            });
            $(".controlpanel").each(function() {
                var id = new String(this.id);
                lotlist.push({
                    saleid: id.replace("itemdetail", ""),
                    rendertype: "controltab"
                });
            });
            $.post("/ajax.php?action=salestate&changeid=" + changeid, {
                lotlist: lotlist
            }, function(data, textstatus) {
                $("input[name=changeid]").val(data.changeid);
                if (data.balance != '~') {
                    var balelm = $("#balance_value");
                    if (balelm.length > 0) {
                        balelm.text(data.balance);
                        $('.spin-edit-wrapper input[name=maxValue]').val(data.balance);
                    }
                }
                var autobidsfinished = data.autobidsfinished;
                if (autobidsfinished.length > 0) {
                    var ab = autobidsfinished.split(",");
                    for (i = 0; i < ab.length; i++) {
                        switchOffAutoBid(ab[i]);
                    }
                }
                var messages = data.messages;
                for (var key in messages) {
                    var msgtype = messages[key].msgtype;
                    var msgdata = messages[key].msgdata;
                    if (msgtype == 10) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        if (jsondata.can_buy)
                            var appendix = jsondata.can_buy;
                        else
                            var appendix = "";
                        showNotification("ПОДСКАЗКА!", jsondata.message, appendix);
                    }
                    if (msgtype == 11) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showNotification("ПОДСКАЗКА!", jsondata.message);
                    }
                    if (msgtype == 12) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showNotification("ПОДСКАЗКА!", jsondata.message);
                    }
                    if (msgtype == 13) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showNotification("ПОДСКАЗКА!", jsondata.message);
                    }
                    if (msgtype == 7) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        if (jsondata.color == "red") {
                            $("#balance_value").css("background-color", "rgb(235,65,73)");
                        } else {
                            $("#balance_value").css("background-color", "#778797");
                        }
                    }
                    if (msgtype == 8) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showBalloon2(jsondata.msg, $(jsondata.item)[0], 10000);
                    }
                    if (msgtype == 9) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showNotification("ПОДСКАЗКА!", jsondata.msg);
                    }
                    if (msgtype == 14) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        $.post("/ajax.php?action=renderpbar", jsondata, function(data, textstatus) {
                            showProductBar("РЕКОМЕНДУЕМ ВАМ ПОСМОТРЕТЬ СЛЕДУЮЩИЕ ТОВАРЫ!", jsondata.msg + data);
                        });
                    }
                    if (msgtype == 15) {
                        if (accountSettings && accountSettings.f20x7SoundNotify) {
                            var $audio = $('#ascand20x7newplayer');
                            if ($audio.length > 0) {
                                $audio[0].currentTime = 0;
                                $audio[0].play();
                            }
                        }
                    }
                    if (msgtype == 16) {
                        if (accountSettings && accountSettings.f20x7SoundNotify) {
                            var $audio = $('#ascand20x7start');
                            if ($audio.length > 0) {
                                $audio[0].currentTime = 0;
                                $audio[0].play();
                            }
                        }
                    }
                    if (msgtype == 17) {
                        var jsondata = jQuery.parseJSON(msgdata);
                        showNotificationMini("ПОДСКАЗКА!", jsondata);
                    }
                }
                for (var key in data.sales) {
                    if (data.sales[key].autobidstate != 'none') {
                        if (data.sales[key].autobidstate == 'on' && !($("#itemdetail" + key + " .btngreen").hasClass("autobid") || $("#item" + key + " .btngreen").hasClass("autobid") || $("#itemtablerow" + key + " .btngreen").hasClass("autobid") || $("#itemdetail" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#item" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#itemtablerow" + key + " .btngray").not('#sale_bidders').hasClass("autobid"))) {
                            abidVisualOn(key);
                        }
                        if (data.sales[key].autobidstate == 'off' && ($("#itemdetail" + key + " .btngreen").hasClass("autobid") || $("#item" + key + " .btngreen").hasClass("autobid") || $("#itemtablerow" + key + " .btngreen").hasClass("autobid") || $("#itemdetail" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#item" + key + " .btngray").not('#sale_bidders').hasClass("autobid") || $("#itemtablerow" + key + " .btngray").not('#sale_bidders').hasClass("autobid"))) {
                            switchOffAutoBid(key, false);
                        }
                    }
                    if (data.sales[key].autobids != '~')
                        $("#itemdetail" + key + " span[name=autobidid]").text(data.sales[key].autobids);
                    var $itemdetail = $("#itemdetail" + key);
                    if ($itemdetail.length > 0 && data.sales[key].events)
                        if ($("#nj_bidders").length > 0)
                            updateSaleBidders(key);
                    for (var eid in data.sales[key].events) {
                        if (data.sales[key].events[eid].changetype == "1") {
                            var can_vis = true;
                            var $rows = $(".countinfo li");
                            $rows.each(function(ind, elm) {
                                var $elm = $(elm);
                                var tmpdt = convDate(data.sales[key].events[eid].bidtm, false, false, true);
                                var tmpnick = data.sales[key].events[eid].nick;
                                var tmpprice = data.sales[key].events[eid].bidprice;
                                var $td = $elm.find("td");
                                if (($($td[0]).text() == tmpdt) && ($($td[1]).text() == tmpnick) && ($($td[2]).text() == tmpprice))
                                    can_vis = false;
                            });
                            if (can_vis && $("#itemdetail" + key).length > 0) {
                                var $bs = $("#itemdetail" + key + " #bidsummax");
                                var $fl = $("#itemdetail" + key + " #editflag");
                                var $hbs = $("#itemdetail" + key + " #hbidsummax");
                                if (data.sales[key].autobidsmax != '~') {
                                    $hbs.val(data.sales[key].autobidsmax);
                                    if ($fl.val() == "0") $bs.val(data.sales[key].autobidsmax);
                                }
                            }
                            bidVisual("item" + key, key, eid, data, "#c5cfd9", "#3B4752");
                            $("#item" + key).css("border-color", "#C5CFD9");
                            bidVisual("itemdetail" + key, key, eid, data, "#778797", "#FFFFFF");
                            bidVisual("itemtablerow" + key, key, eid, data, "#c5cfd9", "#3B4752");
                            if (typeof __active_sales == 'object' && __active_sales[key])
                                __active_sales[key].addD(data.sales[key].events[eid]);
                            if (autobidder)
                                autobidder.evtCheck('item' + key, data.sales[key].events[eid]);
                        } else if (data.sales[key].events[eid].changetype == "2") {
                            if (st == "history")
                                location.assign(location.href);
                            else {
                                var elm = $("#item" + key);
                                var elmtr = $("#itemtablerow" + key);
                                var elmcp = $("#itemdetail" + key);
                                if (elm.length && typeof(data.sales[key].events[eid].html.main) != "undefined") {
                                    var html = "";
                                    var nelm = $(data.sales[key].events[eid].html.main);
                                    nelm.find(".data-dt").each(function(ind, e) {
                                        var $elm = $(e);
                                        $elm.text(convDate($elm.text()));
                                    });
                                    nelm.find("span[name=timer]").each(function(ind, elm) {
                                        var $elm = $(elm);
                                        $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                                        $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                                    });
                                    elm.replaceWith(nelm);
                                }
                                if (elm.length && typeof(data.sales[key].events[eid].html.mylots) != "undefined") {
                                    var html = "";
                                    var nelm = $(data.sales[key].events[eid].html.mylots);
                                    nelm.find(".data-dt").each(function(ind, e) {
                                        var $elm = $(e);
                                        $elm.text(convDate($elm.text()));
                                    });
                                    nelm.find("span[name=timer]").each(function(ind, elm) {
                                        var $elm = $(elm);
                                        $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                                        $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                                    });
                                    elm.replaceWith(nelm);
                                    var gmboarda = $("#active_lot");
                                    var gmboardf = $("#future_lot");
                                    if (gmboarda.length > 0) {
                                        if (gmboarda.parent().is(":hidden")) gmboarda.parent().show();
                                        var btn = gmboarda.find(".card-btn");
                                        var cardcls = "";
                                        var tablecls = "";
                                        if (btn.hasClass("pressed"))
                                            tablecls = " hidden";
                                        else
                                            cardcls = " hidden";
                                        if (gmboarda.find(".card-content").length == 0) {
                                            gmboarda.find(".static-content").addClass("hidden");
                                            gmboarda.append("<div class='card-content" + cardcls + "' style='min-height:auto !important;'><div name='cleared' style='clear:left; height: 0px;'></div></div>");
                                            gmboarda.append("<div class='table-content" + tablecls + "'><div class='table-lot'><table><tr><th><div>Лоты</div></th><th><div>Время</div></th><th><div>Цена</div></th><th><div>Игрок</div></th><th><div>Ставки</div></th><th><div>Статус</div></th></tr></table></div></div>");
                                        }
                                        gmboardf.find(".item[name=lotactive]").insertBefore(gmboarda.find("*[name=cleared]")).each(function() {
                                            var id = this.id.replace("item", "");
                                            var $tr = gmboarda.find(".table-lot tr.hvr:first");
                                            if ($tr.length > 0)
                                                gmboardf.find("#itemtablerow" + id).insertBefore($tr);
                                            else
                                                gmboardf.find("#itemtablerow" + id).insertAfter(gmboarda.find(".table-lot tr:first"));
                                        });
                                        gmboarda.removeClass('hidden');
                                        var $fut = $("#future_lot");
                                        if ($fut.find(".item").length == 0) {
                                            gmboarda.find(".table-content").css("min-height", "auto !important");
                                            $fut.parents(".gameboard-wrapper").remove();
                                            $(".gameboard-wrapper").removeClass("hidden");
                                        }
                                    }
                                }
                                if (elmtr.length && typeof(data.sales[key].events[eid].html.tablerow) != "undefined") {
                                    $('.datetime-wrapper input[id]').removeClass("hasDatepicker").removeClass("calendarclass").removeAttr('id').unbind().remove();
                                    var nelm = $(data.sales[key].events[eid].html.tablerow);
                                    nelm.find(".data-dt").each(function(ind, e) {
                                        var $elm = $(e);
                                        $elm.text(convDate($elm.text()));
                                    });
                                    nelm.find("span[name=timer]").each(function(ind, elm) {
                                        var $elm = $(elm);
                                        $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                                        $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                                    });
                                    elmtr.replaceWith(nelm);
                                }
                                if (elmcp.length && typeof(data.sales[key].events[eid].html.controltab) != "undefined") {
                                    var nelm = $(data.sales[key].events[eid].html.controltab);
                                    nelm.find(".data-dt").each(function(ind, e) {
                                        var $elm = $(e);
                                        $elm.text(convDate($elm.text()));
                                    });
                                    nelm.find(".data-tm").each(function(ind, elm) {
                                        var $elm = $(elm);
                                        $elm.text(convDate($elm.text(), false, false, true));
                                    });
                                    nelm.find("span[name=timer]").each(function(ind, elm) {
                                        var $elm = $(elm);
                                        $elm.parent().append("<input type='hidden' name='timer' value='" + $elm.text() + "'>");
                                        $elm.text(getTimer($elm.text(), $elm.parent().find("input[name=increment]").val()));
                                    });
                                    nelm.find('.datetime-wrapper input[id]').each(function(ind, elm) {
                                        var $this = $(elm);
                                        $this.fadeTo(0, 0);
                                        $this.val(convDate($this.val(), true, false));
                                        $this.datetimepicker({
                                            onSelect: function() {
                                                changeDateTime(this);
                                            }
                                        });
                                        $(".ui-datepicker").click(function(evt) {
                                            evt.stopPropagation();
                                        });
                                        changeDateTime(elm);
                                    });
                                    elmcp.replaceWith(nelm);
                                }
                                setEvents();
                                setAutobidEvents();
                                setFormEvents();
                                $("#itemdetail" + key + " #bidtuner").unbind("click").click(autoBidChange2);
                            }
                        }
                    }
                }
                stateBlocked = false;
                stateWorkType = 0;
                var t1 = (new Date()).getTime();
                var t = (t1 - t0) % 1000;
                if (!single) {
                    var tmout = 1000;
                    if (t < 1000 + tmadd) {
                        ms = t1 % 1000;
                        if (ms <= gstateTM)
                            tmout = gstateTM - ms;
                        else
                            tmout = 1000 + gstateTM - ms;
                        setTimeout(function() {
                            lotState(false);
                        }, tmout + tmadd);
                    } else
                        lotState(false);
                }
            }, "json");
        }, 400);
    }
}

function setEvents(id) {
    $(".item .picture").unbind("hover").hover(function() {
        $(this).children("div").stop(false, true).fadeIn();
    }, function() {
        $(this).children("div").stop(false, true).fadeOut();
    });
    $(".item").unbind("mouseover").mouseover(function() {
        $(this).find(".favorite").css('visibility', 'visible');
    });
    $(".item").unbind("mouseout").mouseout(function() {
        $(this).find(".favorite").not('.favorite-sel').css('visibility', 'hidden');
    });
    if (!id) id = "";
    $(id + "div[name=buybutton]").unbind("click").click(function() {
        var id = this.id.split("_")[1];
        buyProduct(id, this);
        event.stopPropagation();
    });
    $(id + "div[name=buyfullbutton]").unbind("click").click(function() {
        var id = this.id.split("_")[1];
        buyItNow(id, this);
        event.stopPropagation();
    });
    $(id + "div[name=betbutton]").unbind("click").click(function(event) {
        var fid = this.id;
        var id = this.id.split("_")[1];
        if (bb[fid] == undefined)
            bb[fid] = "1";
        else
            return false;
        var $this = $(this);
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            delete bb[fid];
            return false;
        }
        var preorder = "";
        preorder = $this.parents("#item" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#itemdetail" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#itemtablerow" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#historyrow" + id);
        var ipreorder = null;
        if (preorder.length != 0)
            ipreorder = preorder.find("input[name=preorder]");
        if (ipreorder && ipreorder.length > 0) {
            var c = getCookie("pre_" + id);
            if (!c && c != id) {
                setCookie("pre_" + id, id, {
                    expires: 259200,
                    path: "/"
                });
                $("#page_locker").fadeIn("normal", function() {
                    var d = $("<div id='preorder_notice' class='modalwin'><p style='text-align:center; font-size:1.2em;'>Данный товар доступен на условиях &#171;Предзаказ&#187;.<br><br><br></p><p style='text-align: center;'><span class='buttongray' onclick='showPreorderDetail();'>Подробнее</span>&nbsp;&nbsp;<span class='buttonorange' onclick='$(\"#bet_" + id + "\").click();$(\"#page_locker\").click();'>Продолжить</span></p></div>");
                    $("body").append(d);
                    $("#preorder_notice").show();
                    delete bb[fid];
                });
                return false;
            }
        }
        var $ldr = $this.find(".bidloader");
        if (!$ldr.length) {
            $ldr = $("<img class='bidloader' src='/img/item/bidloader.gif'>");
            $this.append($ldr);
        }
        $ldr.show();
        $("input[name=bmsaleid]").val(id);
        $.get("/ajax.php", {
            action: "makebet",
            bmsaleid: id
        }, function(data, textstatus) {
            delete bb[fid];
            if (data.code != "1") $ldr.hide();
            switch (data.code) {
                case "1":
                    var o = $this.parents(".item").find(".favorite");
                    if (!o.hasClass("favorite-sel")) o.click();
                    o = $this.parents(".itemdetail").find(".favorite");
                    if (!o.hasClass("favorite-sel")) o.click();
                    lotState(true);
                    break;
                case "0":
                    showBalloon2("Ошибка. Ставка не принята", $this[0]);
                    break;
                case "-1":
                    openRegForm();
                    showBalloon2(data.message, $("#regform")[0]);
                    break;
                case "-9":
                case "-10":
                    showBalloon2(data.message, $this[0], 4000);
                    break;
                case "-6":
                    showBalloon2(data.message, $this[0], 10000);
                    break;
                case "-2":
                case "-7":
                case "-8":
                    showBalloon2(data.message, $this[0], 8000);
                    break;
                case "-2000":
                    showActivationWnd();
                    break;
                default:
                    showBalloon2(data.message, $this[0]);
                    break;
            }
        }, "json");
        event.stopPropagation();
    });
    $("div.makebid").unbind("click").click(function(event) {
        var id = this.id.split("_")[1];
        var $this = $(this);
        var preorder = null;
        $("input[name=bmsaleid]").val(id);
        $.get("/ajax.php", {
            action: "makebet",
            bmsaleid: id
        }, function(data, textstatus) {
            var b = $.trim(data);
            if (b == "0")
                showBalloon2("Ошибка. Ставка не принята", $this[0]);
            else if (b == "-1") {
                openRegForm();
                showBalloon2("Вы должны войти в систему или зарегистрироваться", $("#regform")[0]);
            } else if (b == "-2") {
                showBalloon2("Купите пакет ставок, чтобы принять участие в аукционе", $this[0]);
            } else if (b == "-3") {
                showBalloon2("Ваша ставка уже является последней.", $this[0]);
            } else if (b == "-4") {
                showBalloon2("У вас активна автоставка.", $this[0]);
            } else {
                $this.parents(".item").find(".favorite").click();
                $this.parents(".itemdetail").find(".favorite").click();
                lotState(true);
            }
        });
        event.stopPropagation();
    });
    $(id + "div[name=requestbutton]").unbind("click").click(function(evt) {
        var id = this.id.split("_")[1];
        var $this = $(this);
        $.get("/ajax.php", {
            action: "salerequest",
            bmsaleid: id
        }, function(data, textstatus) {
            var b = $.trim(data);
            if (b == "1") {
                if ($this.attr("data-action") == "add") {
                    $this.html("УДАЛИТЬ ЗАЯВКУ");
                    $this.attr("data-action", "del")
                    showBalloon2("Заявка принята", $this[0]);
                } else {
                    if (ac == "profile" && st == "myauction") {
                        $("#item" + id).remove();
                        $("#itemtablerow" + id).remove();
                        $("#itemdetail" + id + " .btngray").not("#sale_bidders").toggleClass("autobid");
                        $("#item" + id + " .btngray").not("#sale_bidders").toggleClass("autobid");
                        $("#itemtablerow" + id + " .btngray:").not("#sale_bidders").toggleClass("autobid");
                        var $fut = $("#future_lot");
                        if ($fut.find(".item").length == 0) {
                            $fut.parents(".gameboard-wrapper").remove();
                            $(".gameboard-wrapper").removeClass("hidden");
                        }
                    } else {
                        $this.html("ПОДАТЬ ЗАЯВКУ");
                        $this.attr("data-action", "add");
                        if ($this.hasClass("autobid")) switchOffAutoBid(id, false);
                        showBalloon2("Заявка удалена", $this[0]);
                    }
                }
            } else if (b == "-1") {
                openRegForm();
                showBalloon2("Вы должны войти в систему или зарегистрироваться", $("#regform")[0]);
            } else if (b == "-7") {
                showBalloon2("Сегодня Вы уже стали победителем аукциона на этот товар и можете принять участие в следующем аукционе на него только через сутки", $this[0], 7000);
            } else if (b == "-9") {
                showBalloon2("В аукционе могут принимать участие только пользователи, которые ни разу не становились победителями аукционов", $this[0], 4000);
            } else if (b == "-10") {
                showBalloon2("В аукционе могут принимать участие только пользователи, которые уже становились победителями аукционов", $this[0], 4000);
            } else if (b == "-2000") {
                showActivationWnd();
            } else
                showBalloon2("Произошла внутренняя ошибка, попробуйте еще раз.", $("#req_" + id)[0]);
        });
        evt.stopPropagation();
    });
    $(id + "div[name=request20x7button_cancel]").unbind("click").click(function(evt) {
        var $this = $(this);
        var blimit = $this.attr("data-bl");
        var slimit = $this.attr("data-sum");
        var priced = $this.attr("data-priced");
        if (priced == 0)
            showBalloon2("Для того, чтобы подать заявку на участие в этом аукционе, на Вашем счете должно быть не менее " + blimit + " активированных ставок. <a href='/buybids'>Купить ставки</a>", $this[0]);
        else
            showBalloon2("Для того, чтобы подать заявку на участие в этом аукционе, на Вашем счете должно быть не менее " + blimit + " активированных ставок на сумму " + slimit + " руб. <a href='/buybids'>Купить ставки</a>", $this[0]);
    });
    $(id + "div[name=request20x7button]").unbind("click").click(function(evt) {
        var id = this.id.split("_")[1];
        var item_id = this.id;
        var $this = $(this);
        var action = $this.attr("data-action");
        var blimit = $this.attr("data-bl");
        var plimit = $this.attr("data-pl");
        var slimit = $this.attr("data-sum");
        var priced = $this.attr("data-priced");
        var sndchecked = '';
        if (accountSettings.f20x7SoundNotify)
            sndchecked = 'checked = "checked"';
        var win = "<div id='auc20x7_notice' class='modalwin'><p style='text-align:center; font-size:1.2em; height:105px;'>[:text:]</p><br><hr><div class='checkbox' style='width:500px; text-align:left; margin: 5px 0;'><div class='checkbox_img'></div><div class='checkbox_label'>Понятно. Больше не показывать это уведомление</div><input name='20x7NotifyMsgOff' id='20x7NotifyMsgOff' type='checkbox' value='1'></div><div class='checkbox' style='width:500px; text-align: left;'><div class='checkbox_img" + (sndchecked != "" ? " checkbox_img_checked" : "") + "'></div><div class='checkbox_label'>Включить звуковые уведомления о новых участниках и начале аукциона</div><input name='20x7SoundsOn' id='20x7SoundsOn' type='checkbox' " + sndchecked + " value='1'></div><br><p style='text-align: center;'><span class='buttongray' onclick='$(\"#page_locker\").click();'>Отмена</span>&nbsp;&nbsp;<span class='buttonorange' onclick='sendRequest(\"" + item_id + "\", true); $(\"#page_locker\").click();'>Подать заявку</span></p></div>";
        var text = "";
        if (action == "add") {
            if (priced == 0) {
                if (accountSettings && !accountSettings.f20x7RequestMsgOff) {
                    $("#page_locker").fadeIn("normal", function() {
                        var d = $(win.replace("[:text:]", "По условиям аукциона после подачи заявки с Вашего счета в резерв будет списано <strong>" + blimit + "</strong> ставок. Когда аукцион начнется, на нем будут использоваться только ставки из резерва. Если на момент завершения аукциона в резерве останутся неиспользованные ставки, они «сгорят»."));
                        $("body").append(d);
                        setFormEvents();
                        $("#auc20x7_notice").show();
                    });
                } else {
                    sendRequest(item_id, false);
                }
            } else if (priced == 1) {
                if (accountSettings && !accountSettings.f20x7RequestMsgOff) {
                    $.get("/ajax.php", {
                        action: "get20x7bidcount",
                        sid: id
                    }, function(data, textstatus) {
                        data = parseInt(data);
                        if (data > 0) {
                            $("#page_locker").fadeIn("normal", function() {
                                var d = $(win.replace("[:text:]", "По условиям аукциона после подачи заявки с Вашего счета в резерв будет списано <strong>" + data + "</strong> ставок на сумму <strong>" + slimit + "</strong> руб. Ставки большей стоимости списываются первыми. В резерв будет зачислено <strong>" + blimit + "</strong> ставок. Когда аукцион начнется, на нем будут использоваться только ставки из резерва. Если на момент завершения аукциона в резерве останутся неиспользованные ставки, они «сгорят»."));
                                $("body").append(d);
                                setFormEvents();
                                $("#auc20x7_notice").show();
                            });
                        } else {
                            showBalloon2("Для того, чтобы подать заявку на участие в этом аукционе, на Вашем счете должно быть не менее " + blimit + " активированных ставок на сумму " + slimit + " руб. <a href='/buybids'>Купить ставки</a>", $this[0]);
                        }
                    });
                } else {
                    sendRequest(item_id, false);
                }
            }
        }
        if (action == "del") {
            $.post("/ajax.php", {
                action: "auc20x7_del",
                bmsaleid: id
            }, function(data, textstatus) {
                var b = $.trim(data);
                switch (b) {
                    case "1":
                        ;
                        break;
                    case "0":
                    case "-1":
                        showBalloon2("Вы не можете удалить заявку.", $this[0]);
                        break;
                }
            });
        }
        evt.stopPropagation();
    });
    $(".buyitnow").unbind("click").click(function() {
        var sid = this.id.split("_")[1];
        var btn = this
        buyItNow(sid, btn);
        event.stopPropagation();
    });
    $("a.postid").unbind('click').click(function(evt) {
        evt.preventDefault();
        var pr = $(this).parents('div#lot_block');
        var status = $(this).parents('div[name=lotcompleted]');
        var $this = $(this);
        var a = new String($this.parents(".item").attr("id"));
        var sid = a.replace("item", "");
        $("#page_locker").fadeIn("fast", function() {
            $(this).css("filter", "alpha(opacity=20)");
            $("#waitform").show();
        });
        if (status[0] && pr[0]) {
            location.assign($this.attr("href"));
            return;
        }
        $.get("/ajax.php", {
            action: "setsessionsaleid",
            sid: sid
        }, function(data, textstatus) {
            location.assign($this.attr("href"));
        });
    });
    $("div[name=requestbutton_notauth]").unbind('hover').hover(function() {
        var $this = $(this);
        $this.html("ВОЙТИ");
    }, function() {
        var $this = $(this);
        $this.html($this.attr("data-text"));
    });
    $("div[name=betbutton_notauth]").unbind('hover').hover(function() {
        var $this = $(this);
        $this.html("ВОЙТИ");
    }, function() {
        var $this = $(this);
        $this.html($this.attr("data-text"));
    }).unbind('click').click(function() {
        $("#top_btn_registration").click();
    });
    $(".favorite").unbind('click').click(function() {
        var $this = $(this);
        var sid = $this.attr("id").split("_")[1];
        $.get("/ajax.php", {
            action: "togglefavorite",
            sid: sid
        }, function(data, textstatus) {
            if (parseInt(data) == 1)
                $this.addClass("favorite-sel");
            else if (parseInt(data) == 0)
                $this.removeClass("favorite-sel");
            else if (data == "-2000")
                showActivationWnd();
        });
    });
    $(".delfromgameboard").unbind('click').click(function(evt) {
        var $this = $(this);
        var sid = $this.attr("id").split("_")[1];
        if ($("#item" + sid).find(".autobid").length > 0)
            showBalloon2("Нельзя удалить аукцион с включенной автоставкой", $this[0]);
        else
            $.get("/ajax.php", {
                action: "delfromgameboard",
                sid: sid
            }, function(data, textstatus) {
                data = $.trim(data);
                if (data == "1") {
                    $("#item" + sid).remove();
                    $("#itemtablerow" + sid).remove();
                    var $fut = $("#future_lot"),
                        $act = $("#active_lot");
                    if ($fut.find(".item").length == 0) {
                        $fut.parents(".gameboard-wrapper").remove();
                        $(".gameboard-wrapper").removeClass("hidden");
                        $act.find(".table-content").removeAttr('style');
                    }
                    if ($act.find(".item").length == 0) {
                        $act.find(".card-content").remove();
                        $act.find(".table-content").remove();
                        if (!$act.hasClass('hidden')) $act.addClass("hidden");
                        $fut.find(".table-content").removeAttr('style');
                    }
                    if ($fut.find(".item").length == 0 && $act.find(".item").length == 0) {
                        $act.removeClass("hidden");
                        $act.find(".static-content").removeClass("hidden");
                    }
                } else if (data == "0")
                    showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", $this[0]);
                else if (data == "-2000")
                    showActivationWnd();
            });
        evt.stopPropagation();
    });
    $("#sale_bidders").unbind('click').click(function() {
        var $this = $(this);
        var sid = $this.attr("data-sid");
        updateSaleBidders(sid, true);
    });
    $("#notification-close").live("click", function(evt) {
        $("#notification").remove();
    });
    $("#notification-hide").live("click", function(evt) {
        $("#notification-body").toggleClass("body-hide");
        $("#notification .notification-header").toggleClass("header-body-hide");
        $("#notification-hide").toggleClass("body-hide");
    });
    $("#notification-header.header-body-hide").live("click", function(evt) {
        $("#notification-body").toggleClass("body-hide");
        $("#notification .notification-header").toggleClass("header-body-hide");
        $("#notification-hide").toggleClass("body-hide");
    });
    $(".secondbid").click(function(event) {
        var auth = getCookie('bm_auth');
        if (!auth) {
            openRegForm();
            return;
        }
        if ($(this).hasClass('disable')) {
            return;
        }
        showSecondFreeDialog();
    }).mouseleave(function() {
        $(this).children('.balloon').css('visibility', 'visible');
        var secondbid = $(this);
        var balloon = $(this).children('.balloon');
        setTimeout(function() {
            if (!balloon.is(':hover') && !secondbid.is(':hover')) {
                balloon.css('visibility', 'hidden');
            }
        }, 500);
    }).mouseenter(function() {
        $(this).children('.balloon').css('visibility', 'visible');
    });
    $(".secondbid .balloon").click(function() {
        return false;
    });
    $(".secondbid .balloon a").click(function() {
        window.location.href = $(this).attr('href');
        return true;
    });
    if (typeof mobile_setEvents == 'function')
        mobile_setEvents(id);
}

function updateSaleBidders(sid, btn) {
    $.post("/ajax.php", {
        action: "salebidders",
        sid: sid
    }, function(data, textstatus) {
        if (data != '0') {
            var $win = $('#nj_bidders');
            var $content = $('#nj_bidders .nj-content');
            if ($win.length == 0) {
                var $elm;
                var $elm2;
                var mdfunc = function(evt) {
                    $win.attr("data-moving", "1");
                    $win.attr("data-mouseX", evt.pageX);
                    $win.attr("data-mouseY", evt.pageY);
                    evt.stopPropagation();
                    return false;
                };
                $win = $("<div>");
                $win.addClass("modalwin");
                $win.addClass("dropshadow5px");
                $win.attr("id", "nj_bidders");
                $elm = $("<div>");
                $elm.addClass("header");
                $win.append($elm);
                $elm.mousedown(mdfunc);
                document.onmousemove = function(evt) {
                    if ($win.attr("data-moving") == "1") {
                        var deltaX = evt.pageX - $win.attr("data-mouseX");
                        var deltaY = evt.pageY - $win.attr("data-mouseY");
                        if (deltaX != 0 || deltaY != 0) {
                            $win.attr("data-mouseX", evt.pageX);
                            $win.attr("data-mouseY", evt.pageY);
                            var pos = $win[0].getBoundingClientRect();
                            $win.css("margin", "0");
                            $win.css("top", pos.top + deltaY + "px");
                            $win.css("left", pos.left + deltaX + "px");
                        }
                        evt.stopPropagation();
                    }
                };
                $elm.mouseup(function() {
                    $win.attr("data-moving", "0");
                });
                $elm2 = $("<img>");
                $elm2.attr("src", "/img/altindex/modalwin_logo.png");
                $elm2.attr("alt", "Bonusmall::Аллея скидок");
                $elm2.addClass("shopbag");
                $elm2.mousedown(mdfunc);
                $elm.append($elm2);
                $elm2 = $("<div>");
                $elm2.addClass("closeform");
                $elm2.attr("title", "Закрыть");
                $elm2.click(function() {
                    $win.hide();
                });
                $elm.append($elm2);
                $elm = $("<div>");
                $elm.addClass("nj-content");
                $content = $elm;
                $win.append($elm);
                $("body").append($win);
            }
            $content.html(data);
            if (btn && $win.is(":hidden"))
                $win.show();
        }
    });
}

function showBalloon2(msg, elm, showtm) {
    if (!showtm) showtm = 3000;
    var parent = $(elm).parent();
    var parentpos = parent.css("position");
    if (parentpos == "static") parent.css("position", "relative");
    var z = $(elm).css("z-index");
    var ww = $(elm).width() / 2;
    var scroll = 0;
    if ($(elm).css("position") == "fixed")
        scroll = getBodyScrollTop();
    var blid = 0;
    if (elm.id) blid = elm.id;
    parent.find("div[name='balloon_" + blid + "']").remove();
    parent.find(".balloon").css("opacity", "0");
    var oBalloon = $("<div class='balloon' name='balloon_" + blid + "'>" + msg + "<div class='balloonarrow'>&nbsp;</div></div>");
    oBalloon = oBalloon.appendTo(parent);
    var w = oBalloon.width();
    oBalloon.css({
        "text-align": "left",
        "bottom": parent.height() + parseInt(parent.css("padding-top")) + parseInt(parent.css("padding-bottom")) - (elm.offsetTop + scroll) + 8 + "px",
        "top": "auto",
        "height": "auto",
        "left": elm.offsetLeft + ww - 15 + "px",
        "z-index": z + 1,
        "display": "none",
        "visibility": "visible",
        "width": w
    }).fadeIn("fast", function() {
        var tm = setTimeout(function() {
            oBalloon.fadeOut("fast", function() {
                $(this).remove();
                parent.find(".balloon").css("opacity", "1");
            });
        }, showtm);
    })
}

function showBalloon2under(msg, elm, showtm) {
    if (!showtm) showtm = 3000;
    var parent = $(elm).parent();
    var parentpos = parent.css("position");
    if (parentpos == "static") parent.css("position", "relative");
    var z = $(elm).css("z-index");
    var ww = $(elm).width() / 2;
    var scroll = 0;
    if ($(elm).css("position") == "fixed")
        scroll = getBodyScrollTop();
    var blid = 0;
    if (elm.id) blid = elm.id;
    parent.find("div[name='balloon_" + blid + "']").remove();
    parent.find(".balloon").css("opacity", "0");
    var oBalloon = $("<div class='balloon' name='balloon_" + blid + "'>" + msg + "<div class='balloonarrow' style='background: url(/img/balloonarrowtop.png); top: -12px;'>&nbsp;</div></div>");
    oBalloon = oBalloon.appendTo(parent);
    var w = oBalloon.width();
    oBalloon.css({
        "text-align": "left",
        "top": parent.height() + parseInt(parent.css("padding-top")) + parseInt(parent.css("padding-bottom")) - (elm.offsetTop + scroll) + 8 + "px",
        "bottom": "auto",
        "height": "auto",
        "left": elm.offsetLeft + ww - 15 + "px",
        "z-index": z + 1,
        "display": "none",
        "visibility": "visible",
        "width": w
    }).fadeIn("fast", function() {
        var tm = setTimeout(function() {
            oBalloon.fadeOut("fast", function() {
                $(this).remove();
                parent.find(".balloon").css("opacity", "1");
            });
        }, showtm);
    })
}

function hideBalloon2(elm) {
    var parent = $(elm).parent();
    var parentpos = parent.css("position");
    if (parentpos == "static") parent.css("position", "relative");
    var z = $(elm).css("z-index");
    var ww = $(elm).width() / 2;
    var scroll = 0;
    if ($(elm).css("position") == "fixed")
        scroll = getBodyScrollTop();
    var blid = 0;
    if (elm.id) blid = elm.id;
    parent.find("div[name='balloon_" + blid + "']").remove();
}

function getBodyScrollTop() {
    return self.pageYOffset || (document.documentElement && document.documentElement.scrollTop) || (document.body && document.body.scrollTop);
}
var switchOffAutoBid = function(sid, showmsg) {
    abidVisualOff(sid)
    if (showmsg) {
        var a = $("#itemdetail" + sid + " .btngreen");
        if (a.length) showBalloon2("У вас закончились ставки или достигнуто ограничение по автоставке", a[0], 5000);
        a = $("#item" + sid + " .btngreen");
        if (a.length) showBalloon2("У вас закончились ставки или достигнуто ограничение по автоставке", a[0], 5000);
        a = $("#itemtablerow" + sid + " .btngreen");
        if (a.length) showBalloon2("У вас закончились ставки или достигнуто ограничение по автоставке", a[0], 5000);
    }
}
var buyProduct = function(id, oContext) {
    $.get("/ajax.php", {
        action: "buyproduct",
        bmsaleid: id
    }, function(data, textstatus) {
        if (data.result == "1") {
            location.assign(data.redir);
        } else if (data.result == "2") {
            $this.parent.append(data.html);
        } else if (data.result == "-1") {
            openRegForm();
            showBalloon2("Вы должны войти в систему или зарегистрироваться", oContext);
        } else if (data.result == "-2000") {
            showActivationWnd();
        } else
            showBalloon2(data.errtext, oContext);
    }, "json");
}
var buyItNow = function(sid, oContext) {
    $.get("/ajax.php", {
        action: "buyitnow",
        sid: sid
    }, function(data, textstatus) {
        if ($.trim(data) != -1 && $.trim(data) != 0 && $.trim(data) != -2 && $.trim(data) != -3 && $.trim(data) != -2000) {
            location.assign(data);
        } else if ($.trim(data) == -1) {
            showBalloon2("Только зарегистрированные пользователи могут совершать покупки. Зарегистрируйтесь или войдите в систему", oContext);
        } else if ($.trim(data) == -2) {
            showBalloon2("На товары со статусом «Предзаказ» предложение «Купить сейчас» будет доступно в течение 72 часов после официального старта продаж товара в России и объявления окончательной полной стоимости товара.", oContext);
        } else if ($.trim(data) == -3) {
            showBalloon2("У Вас активна автоставка. Чтобы оформить товар по предложению \"Купить сейчас\", отключите, пожалуйста, автоставку", oContext);
        } else if ($.trim(data) == 0) {
            showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", oContext);
        } else if ($.trim(data) == -2000) {
            showActivationWnd();
        }
    });
}
var showPreorderDetail = function() {
    var d = $("<div id='preorder_notice2' class='modalwin'><h2 style='font-size:1.2em;margin-bottom:20px;'>Особенности аукционов на условиях поставки \"Предзаказ\"</h2><p style='text-align:left; font-size:1.2em; margin: 10px 0;'>1. <strong>Для победителей аукциона.</strong> Вы сможете оформить выигранный товар в течение 14 дней после начала продаж и объявления его окончательной стоимости. Товар будет отправлен в течение 30 дней после оплаты аукционной стоимости (при наличии на складах официальных дистрибьюторов).</p><p style='text-align:left; font-size:1.2em; margin: 10px 0;'>2. <strong>По предложению «Купить сейчас».</strong> Если вы не стали победителем аукциона, вы можете воспользоваться предложением «Купить сейчас» и приобрести данный товар за его полную стоимость за вычетом потраченных в аукционе ставок. Предложение «Купить сейчас» доступно в течение 72 часов после начала официальных продаж (при наличии на складах официальных дистрибьюторов) и объявления его окончательной стоимости. Товар будет отправлен в течение 30 дней после оплаты полной стоимости за вычетом потраченных ставок (при наличии на складах официальных дистрибьюторов). До начала продаж товара на сайте указана предварительная стоимость. Окончательная стоимость товара объявляется после его поступления в продажу и может отличаться от предварительной. Информацию о начале продаж вы получите по электронной почте, указанной при регистрации.</p><p>&nbsp;</p><p>&nbsp;</p><p style='text-align: center;'><span class='buttonorange' onclick='$(\"#page_locker\").click();'>Продолжить</span></p></div>");
    $("body").append(d);
    $("#preorder_notice2").show();
    $("#preorder_notice").hide();
}
var showSecondFreeDialog = function(btn) {
    var d = $("<div id='secondfree_notice' class='modalwin'>" + "<h2 style='font-size:1.2em;margin-bottom:20px;'>Внимание!</h2>" + "<p style='text-align: center; font-size:1.2em; margin: 10px 0;'>После включения опции «Каждая 2-ая ставка бесплатно», каждая вторая ставка, которую Вы сделаете в этом аукционе, не будет списываться с Вашего счета. При этом, если Вы решите воспользоваться предложением «Купить прямо сейчас», потраченные в аукционе ставки не будут вычтены из полной стоимости товара - они вернутся на Ваш счет в полном объеме по изначальной стоимости. " + " <br><br><strong>Важно!</strong> Отключить опцию «Каждая 2-ая ставка бесплатно» на данном аукционе Вы уже не сможете." + "</p><p>&nbsp;</p><p>&nbsp;</p>" + "<p style='text-align: center;'>" + "<span class='buttonorange' onclick='switchOffSecondfree(this);'>Не включать</span>" + "<span class='buttonorange' onclick='switchOnSecondfree(this);'>Включить</span>" + "</p></div>");
    $("body").append(d);
    $("#secondfree_notice").show();
    $(document).mouseup(function(e) {
        var container = $("#secondfree_notice");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.remove();
            $(document).off('mouseup');
        }
    });
}
var closePromoNotice = function(i) {
    if (i == 1)
        $("#page_locker").hide();
    $("#promocode_notice").remove();
    if (i == 0)
        $("#regform").show();
}
var closeEmailNotice = function(i) {
    if (i == 1)
        $("#page_locker").hide();
    $("#promocode_notice").remove();
    $("#email_notice").remove();
    if (i == 0)
        $("#regform").show();
}
var processWithoutPromoCode = function(i) {
    if (registrationProcess)
        return;
    registrationProcess = true;
    closePromoNotice();
    if (i == 0)
        $("#in1 form")[0].submit();
    else if (i == 1)
        $("#registrationbannerform")[0].submit();
    $('#regform').hide();
}
var processWithoutEmail = function(i) {
    if (registrationProcess)
        return;
    registrationProcess = true;
    $("#email_notice").remove();
    if ($("#promocode_notice").length == 0) {
        if (i == 0)
            $("#in1 form")[0].submit();
        else if (i == 1)
            $("#registrationbannerform")[0].submit();
        $('#regform').hide();
    }
}
var sendRequest = function(itemid, chboxes) {
    if (!itemid) return false;
    var id = itemid.split("_")[1];
    var $auc = $("#" + itemid);
    if ($auc.length == 0) return false;
    var $this = $auc;
    var fchboxes = (chboxes ? '1' : '0');
    var blimit = $this.attr("data-bl");
    var params = {};
    params.action = "auc20x7_add";
    params.bmsaleid = id;
    params.settings = fchboxes
    if (chboxes) {
        var $fnotify = $("input[name=20x7NotifyMsgOff]");
        if ($fnotify.length > 0)
            params.fnotify = ($fnotify[0].checked ? '1' : '0');
        var $fsounds = $("input[name=20x7SoundsOn]");
        params.fsounds = ($fsounds[0].checked ? '1' : '0');
    }
    $.post("/ajax.php", params, function(data, textstatus) {
        var b = $.trim(data);
        switch (b) {
            case "1":
                if (chboxes && accountSettings) {
                    if (params.fnotify)
                        accountSettings.f20x7RequestMsgOff = (parseInt(params.fnotify) == 1);
                    accountSettings.f20x7SoundNotify = (parseInt(params.fsounds) == 1);
                }
                break;
            case "-1":
                showBalloon2("Ошибка при регистрации игрока", $this[0]);
                break;
            case "-2":
                showBalloon2("Для того, чтобы подать заявку на участие в этом аукционе, на Вашем счете должно быть не менее " + blimit + " активированных ставок. <a href='/buybids'>Купить ставки</a>", $this[0]);
                break;
        }
    });
}

function showNotification(header, text, appendix) {
    var window = "<div id='notification'> \
      <div id='notification-header' class='notification-header'> \
          <div class='header-text'>" + header + "</div> \
          <div class='header-buttons'> \
              <div class='hide' id='notification-hide'></div> \
              <div class='close' id='notification-close'></div> \
          </div> \
      </div> \
      <div class='body' id='notification-body'> \
          <div class='main-text'>" + text + "</div>";
    if (appendix) window += "<p class='appendix-star'>*</p><p class='appendix-text'>" + appendix + "</p>";
    window += "</div> \
    </div>";
    $("#notification").remove();
    $("body").append(window).slideDown("slow");
}

function showProductBar(header, text) {
    $("#productbar").remove();
    var window = "<div class='productbar' id='productbar'> \
      <div id='productbar-header' class='productbar-header'> \
          <div class='header-text'>" + header + "</div> \
          <div class='header-buttons'> \
              <div class='hide' id='productbar-hide'></div> \
              <div class='close' id='productbar-close'></div> \
          </div> \
      </div> \
      <div class='body' id='productbar-body'> \
          <div class='main-text' style='padding: 5px;'>" + text + "</div>";
    window += "</div> \
    </div>";
    $("body").append(window).slideDown("slow");
    $("#productbar-close").live("click", function(evt) {
        $("#productbar").remove();
    });
    $("#productbar-hide").live("click", function(evt) {
        $("#productbar-body").toggleClass("body-hide");
        $("#productbar .productbar-header").toggleClass("header-body-hide");
        $("#productbar-hide").toggleClass("body-hide");
    });
    $("#productbar-header.header-body-hide").live("click", function(evt) {
        $("#productbar-body").toggleClass("body-hide");
        $("#productbar .productbar-header").toggleClass("header-body-hide");
        $("#productbar-hide").toggleClass("body-hide");
    });
    $(".productbar .item .picture").unbind("hover").hover(function() {
        $(this).children("div").stop(false, true).fadeIn();
    }, function() {
        $(this).children("div").stop(false, true).fadeOut();
    });
}

function showNotificationMini(header, data) {
    var BMAccountID = (data.BMAccountID ? data.BMAccountID : "''");
    var TplID = (data.TplID ? data.TplID : "''");
    var Notify = (data.Notify ? data.Notify : 0);
    var window = "<div id='notification'> \
      <div id='notification-header' class='notification-header'> \
          <div class='header-text'>" + header + "</div> \
          <div class='header-buttons'> \
              <div class='close' id='notification-close'></div> \
          </div> \
      </div> \
      <div class='body' id='notification-body'> \
          <div class='main-text'>" + data.msg + "</div>";
    if (Notify) {
        window += '<div class="checkbox " style="width:200px; margin-left: 20px; height: 40px;">\
  <div class="checkbox_img"></div>\
  <div class="checkbox_label">Больше не показывать</div>\
  <input name="Notify" id="Notify" type="checkbox" value="1" onchange="NotificationChange(this, ' + BMAccountID + ',' + TplID + ')"/>\
  </div>';
    }
    window += "</div> \
    </div>";
    $("#notification").remove();
    $("body").append(window).slideDown("slow");
    setFormEvents();
}

function NotificationChange(input, BMAccountID, TplID) {
    $.get("/ajax.php", {
        action: "notifyaction",
        notifyvalue: (input.checked ? '1' : '0'),
        accountid: BMAccountID,
        TplID: TplID
    }, function(data, textstatus) {});
}
var bidsBack = function(id, oContext) {
    $.get("/ajax.php", {
        action: "buyproduct",
        bmsaleid: id
    }, function(data, textstatus) {
        if (data.result == "1") {
            location.assign(data.redir + '/bids/');
        } else if (data.result == "2") {
            $this.parent.append(data.html);
        } else if (data.result == "-1") {
            openRegForm();
            showBalloon2("Вы должны войти в систему или зарегистрироваться", oContext);
        } else if (data.result == "-2000") {
            showActivationWnd();
        } else
            showBalloon2(data.errtext, oContext);
    }, "json");
}
var cashbackMoney = function(id, oContext) {
    $.get("/ajax.php", {
        action: "buyproduct",
        bmsaleid: id
    }, function(data, textstatus) {
        if (data.result == "1") {
            location.assign(data.redir + '/cash/');
        } else if (data.result == "2") {
            $this.parent.append(data.html);
        } else if (data.result == "-1") {
            openRegForm();
            showBalloon2("Вы должны войти в систему или зарегистрироваться", oContext);
        } else if (data.result == "-2000") {
            showActivationWnd();
        } else
            showBalloon2(data.errtext, oContext);
    }, "json");
}

function switchOffSecondfree(me) {
    $("#secondfree_notice").remove();
    $(document).off('mouseup');
}

function switchOnSecondfree(me) {
    var $this = $('.secondbid');
    var bmsaleid = $this[0].id.split("_")[1];
    $.post("/ajax.php", {
        action: "activatesecondbid",
        bmsaleid: bmsaleid
    }, function(data, textstatus) {
        var b = $.trim(data),
            jsondata = jQuery.parseJSON(data);
        if (jsondata.result == 'present') {
            $this.addClass("disable");
            $this.off("click");
        } else if (jsondata.result == 'create') {
            $this.addClass("disable");
            $this.off("click");
            $this.children('.balloon').html(jsondata.balloon + '<span class="balloonarrow">&nbsp;</span>');
            $('.condtbl .text_buynow').html(jsondata.condition);
            $('.switcherpanel .balloon').append('. Количество ставок задается в сумме с бесплатными ставками по опции «Каждая 2-ая ставка бесплатно».');
            $('.switcherpanel .balloon').addClass('sbfree');
            var $item = $("#buyitnow_" + bmsaleid);
            $item.html(jsondata.msg);
        } else if (jsondata.result == 'not acceptable') {
            $this.addClass("disable");
            $this.off("click");
            $this.children('.balloon').html(jsondata.balloon + '<span class="balloonarrow">&nbsp;</span>');
        } else if (jsondata.result == 'show error') {
            showBalloon2(jsondata.balloon, $this[0], 10000);
        }
    });
    $("#secondfree_notice").remove();
    $(document).off('mouseup');
};
var ru;
if (!ru)
    ru = {};
else if (typeof ru != "object")
    throw new Error("Имя ru существует, но не является объектом");
if (!ru.bonusmall)
    ru.bonusmall = {};
else if (typeof ru.bonusmall != "object")
    throw new Error("Имя ru.bonusmall существует, но не является объектом");
(function(document, window, $) {
    ru.bonusmall.SBL = function(s, d) {
        var sale = this;
        this.sid = s;
        this.bl = {};
        this.lastB = 0;
        this.contCss = "height: 91px; margin: 10px; background: #778797; padding:2px 10px; text-align: center; color: white; line-height: normal;overflow: hidden;position:relative;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;";
        this.contCssVis = "display:block;";
        this.contCssUnvis = "display:none;";
        this.tblCount = 5;
        this.tblClass = "";
        this.tblClasses = [];
        this.tblContainers = {};
        this.usrCount = 5;
        this.usrClass = "";
        this.usrClasses = [];
        this.usrContainers = [];
        this.regCSS();
        this.initD(d);
        $(function() {
            sale.render();
        });
    }
    ru.bonusmall.SBL.prototype.initD = function(d) {
        var j;
        if (typeof window.atob == "function") {
            j = window.atob(d);
        } else {
            var dec = new TextDecoderLite();
            j = dec.decode(d);
        }
        this.bl = $.parseJSON(j);
    }
    ru.bonusmall.SBL.prototype.render = function() {
        var a = $('#itemdetail' + this.sid + ' .countinfo');
        if (a.length > 0) {
            this.container = a.parent();
            a.remove();
            var $img;
            var $cont;
            var $elm;
            var $elm2;
            var i;
            var k;
            var j;
            $cont = this.container;
            for (var i = 0; i < this.tblCount; i++) {
                k = rand(1, 3);
                for (j = 0; j < k; j++) {
                    $elm = $("<div></div>");
                    if (rand(0, 1) == 1)
                        $elm.attr("id", genRandomID(11, 20))
                    $cont.append($elm);
                    $cont = $elm;
                }
                $img = $("<img style='position:absolute; left:0px; bottom:0px; z-index: 10;' src='/img/item/bidderlisthide.png'>");
                $elm = $("<div></div>");
                $elm.append($img);
                $elm.attr("id", this.tblClasses[i]);
                $cont.append($elm);
                $elm2 = $("<ul></ul>");
                $elm.append($elm2);
                this.tblContainers[this.tblClasses[i]] = $elm2;
            }
            this.fillTbl();
        }
    }
    ru.bonusmall.SBL.prototype.fillTbl = function() {
        function genListItem(tm, nick, price) {
            return $("<li><table style='margin:0;border-collapse:collapse;width:100%;'><tr><td style='text-align:left;padding:0;color:#222B35;width:22%;'>" +
                convDate(tm, false, false, true) + "</td><td style='text-align:left;padding:0;'>" +
                (nick == myNick ? "<strong>" + nick + "</strong>" : nick) + "</td><td style='text-align:right;padding:0;color:#222B35; width:30%;'>" + price + "</td></tr></table></li>");
        }
        var bcnt = 0;
        for (var b in this.bl) {
            for (var u in this.tblContainers) {
                var nick = u == this.tblClass ? this.bl[b].n : __bm_b[rand(1, __bm_b.length) - 1].n;
                this.tblContainers[u].prepend(genListItem(this.bl[b].t, nick, this.bl[b].p));
            }
            this.lastB = this.bl[b].b;
            bcnt++;
        }
        if (bcnt == 0) {
            for (var u in this.tblContainers) {
                this.tblContainers[u].prepend($("<li name='nobids'>ставок нет</li>"));
            }
        }
    }
    ru.bonusmall.SBL.prototype.addD = function(evt) {
        if (evt.bidseq <= this.lastB)
            return false;
        this.bl[evt.bidseq] = {
            "b": evt.bidseq,
            "t": evt.bidtm,
            "n": evt.nick,
            "a": evt.avatar,
            "p": evt.bidprice
        };
        this.lastB = evt.bidseq;
        var $li;
        var $tr;
        for (var u in this.tblContainers) {
            var nick = u == this.tblClass ? evt.nick : __bm_b[rand(1, __bm_b.length) - 1].n;
            $li = $("<li style='display:none;' data-seq='" + evt.bidseq + "'><span>&nbsp;</span><table style='display:none;margin:0;border-collapse:collapse;width:100%;'><tr><td style='text-align:left;padding:0;color:#222B35;width:22%;'>" +
                convDate(evt.bidtm, false, false, true) + "</td><td style='text-align:left;padding:0;'>" +
                (nick == myNick ? "<strong>" + nick + "</strong>" : nick) + "</td><td style='text-align:right;padding:0;color:#222B35; width:30%;'>" + evt.bidprice + " руб.</td></tr></table></li>");
            this.tblContainers[u].prepend($li);
            this.tblContainers[u].find("li[name=nobids]").fadeOut("normal");
            $li.slideDown("normal", function() {
                var $this = $(this);
                $this.find("span").remove();
                $this.find("table").fadeIn("normal");
            });
            var $lis = this.tblContainers[u].find("li");
            if ($lis.length > 10) {
                var $ll = this.tblContainers[u].find("li:last");
                delete this.bl[$ll.attr("data-seq")];
                $ll.remove();
            }
        }
    }
    ru.bonusmall.SBL.prototype.regCSS = function() {
        var target = rand(1, this.tblCount);
        var i;
        var s;
        for (i = 0; i < this.tblCount; i++)
            this.tblClasses.push(genRandomID(8, 10));
        this.tblClass = this.tblClasses[target - 1];
        var ss = addStyleSheet();
        for (i = 0; i < this.tblCount; i++) {
            if (this.tblClasses[i] == this.tblClass)
                s = this.contCss + this.contCssVis;
            else
                s = this.contCss + this.contCssUnvis;
            addStyle(ss, '#' + this.tblClasses[i], s);
        }
    }
})(window.document, window, jQuery);;
$(function() {
    setAutobidEvents();
    $(".sorting-menu-wrapper").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#sorting_menu").css("left") == "0px") {
                $("#sorting_menu").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $(".sorting-menu-wrapper span").css("background-position", "0 0");
            } else {
                $("#sorting_menu").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $(".sorting-menu-wrapper span").css("background-position", "-22px 0");
            }
        });
        event.stopPropagation();
    });
    $(".itemdetail .picture").mouseenter(function() {
        $(".itemdetail .picture .detailanchor").stop().fadeIn();
    }).mouseleave(function() {
        $(".itemdetail .picture .detailanchor").stop().fadeOut();
    });
    $(".profile-image-menu-wrapper").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#profile_image_menu").css("left") == "0px") {
                $("#profile_image_menu").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $(".profile-image-menu-wrapper span").css("background-position", "0 0");
            } else {
                $("#profile_image_menu").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $(".profile-image-menu-wrapper span").css("background-position", "-22px 0");
            }
            event.stopPropagation();
        });
    });
    $("#shoot_photo").click(function(event) {
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        closeAll(this, function() {
            $("#page_locker").stop().fadeIn("fast", function() {
                $(this).css("filter", "alpha(opacity=20)");
                $("#camera").show();
            });
        });
        event.stopPropagation();
    });
    $("tr[id^=itemtablerow],tr[id^=historyrow]").click(function() {
        var id = $(this).attr("id");
        var sid = id.replace("itemtablerow", "");
        sid = sid.replace("historyrow", "");
        var pid = $(this).attr("data-pid");
        var state = $(this).find("td:nth-last-child(1)").text().toUpperCase();
        var status = $(this).find("td:nth-last-child(2)").text().toUpperCase();
        if (state == "ПРОСРОЧЕН" || (state == "ЗАВЕРШЕН" && status == "ЗАВЕРШЕН") || state == "ОТМЕНЕН") {} else {
            $.get("/ajax.php", {
                action: "setsessionsaleid",
                sid: sid
            }, function(data, textstatus) {
                location.assign('/product/' + pid);
            });
        }
    });
    $(".table-lot tr.historyitemrow").each(function() {
        var state = $(this).find("td:nth-last-child(1)").text().toUpperCase();
        var status = $(this).find("td:nth-last-child(2)").text().toUpperCase();
        if (state == "ПРОСРОЧЕН" || (state == "ЗАВЕРШЕН" && status == "ЗАВЕРШЕН") || state == "ОТМЕНЕН") {
            $(this).removeClass("hvr");
        }
    });
    $("#newaddress").click(function() {
        $("#addr-container").hide();
        $("#form-container").show();
    });
    $(".user-info .form-block .button").click(function(evt) {
        var $this = $(this);
        var $parent = $this.parent().parent();
        var $header = $parent.find(".header");
        var $content = $parent.find(".content");
        if ($content.is(":hidden")) {
            $content.stop().slideDown("fast");
            $this.addClass("button-open");
            $parent.removeClass("form-block-closed");
            $header.removeClass("header-closed");
        } else {
            $content.stop().slideUp("fast");
            $this.removeClass("button-open");
            $parent.addClass("form-block-closed");
            $header.addClass("header-closed");
        }
        evt.stopPropagation();
    });
    $(".user-info .form-block .header").click(function(evt) {
        $(this).parent().find(".button").click();
        evt.stopPropagation();
    });
    $(".gameboard-block .header .card-btn").click(function() {
        var $this = $(this);
        if (!$this.hasClass("pressed")) {
            var $tblbtn = $this.parent().find(".table-btn");
            var $cardblock = $this.parent().parent().find(".card-content");
            var $tableblock = $this.parent().parent().find(".table-content");
            $this.addClass("pressed");
            $tblbtn.removeClass("pressed");
            $cardblock.removeClass("hidden");
            $tableblock.addClass("hidden");
            $.get("/ajax.php", {
                action: "profile_saveaucstate",
                btnid: this.id
            }, function(data, textstatus) {});
        }
    });
    $(".gameboard-block .header .table-btn").click(function() {
        var $this = $(this);
        if (!$this.hasClass("pressed")) {
            var $cardbtn = $this.parent().find(".card-btn");
            var $cardblock = $this.parent().parent().find(".card-content");
            var $tableblock = $this.parent().parent().find(".table-content");
            $this.addClass("pressed");
            $cardbtn.removeClass("pressed");
            $cardblock.addClass("hidden");
            $tableblock.removeClass("hidden");
            $.get("/ajax.php", {
                action: "profile_saveaucstate",
                btnid: this.id
            }, function(data, textstatus) {});
        }
    });
    bindEvents();
});

function bindEvents() {
    var timer, delay = 1000;
    $('input[name=accountname]').on('input', function(e) {
        var newVal = $(this).val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($(this).val() != newVal) $(this).val(newVal);
        clearTimeout(timer);
        var $accountname = $(this).val();
        var $e = $("#accountname").parent();
        var $r = $e[0];
        timer = setTimeout(function() {
            $.get("/ajax.php", {
                action: "profile_checkprofile",
                accountname: $accountname
            }, function(data, textstatus) {
                switch (data) {
                    case 2:
                        showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", $r);
                        break;
                    case 3:
                        showBalloon2("Ник не может быть более 16 знаков", $r);
                        break;
                    case 4:
                        showBalloon2("Указанный ник не соответствует правилам сайта", $r);
                        break;
                    case 6:
                        break;
                    case 7:
                        showBalloon2("Запрещено использовать спецсимволы: ' \" \\ / * < >", $r);
                        break;
                    case 9:
                        showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", $r);
                        break;
                    case -2000:
                        showActivationWnd();
                        break;
                    default:
                        $(".balloon").hide();
                        break;
                }
            }, "json");
        }, 1000);
    });
    $("#save_profile").click(function() {
        var $this = $(this);
        var $accountname = $("input[name=accountname]");
        var newVal = $accountname.val().replace(/ /g, '').replace(/\t/g, '').replace(/\n/g, '').replace(/\r/g, '').replace(/\0/g, '').replace(/\x0B/g, '');
        if ($accountname.val() != newVal) $accountname.val(newVal);
        var $msgtd = $this.parent().parent().find("td[name=form-message]");
        $msgtd.html("");
        $progress = $("<img id='loginprogress' src='/img/ajax-loader2.gif'>");
        $msgtd.append($progress);
        $.get("/ajax.php", {
            action: "profile_saveprofile",
            accountname: $accountname.val()
        }, function(data, textstatus) {
            var $e = $("#accountname").parent()[0];
            $progress.remove();
            switch (data) {
                case 1:
                    $msgtd.html("<span style='color:green'>Сохранено</span>");
                    $("#account_name").html($accountname.val());
                    break;
                case 2:
                    showBalloon2("Пользователь с таким ником уже зарегистрирован в системе", $e);
                    break;
                case 3:
                    showBalloon2("Ник не может быть более 16 знаков", $e);
                    break;
                case 4:
                    showBalloon2("Указанный ник не соответствует правилам сайта", $e);
                    break;
                case 5:
                    showBalloon2("Замена ника происходит не более трех раз в день", $e);
                    break;
                case 6:
                    break;
                case 7:
                    showBalloon2("Запрещено использовать спецсимволы: ' \" \\ / * < >", $e);
                    break;
                case 8:
                    showBalloon2("Изменение ника заблокировано. Обратитесь в службу поддержки Бонумолл.", $e);
                    break;
                case 9:
                    showBalloon2("Пользователь с подобным ником уже зарегистрирован в системе", $e);
                    break;
                case -2000:
                    showActivationWnd();
                    break;
                default:
                    showBalloon2("Ошибка", $("#accountname"));
                    break;
            }
        }, "json");
    });
    $("#save_mydata").click(function() {
        var $this = $(this);
        var $fname = $("input[name=firstname]");
        var $mname = $("input[name=middlename]");
        var $lname = $("input[name=lastname]");
        var $sex = $("select[name=sex]");
        var $age = $("select[name=age]");
        var $msgtd = $this.parent().parent().find("td[name=form-message]");
        $msgtd.html("");
        $progress = $("<img id='loginprogress' src='/img/ajax-loader2.gif'>");
        $msgtd.append($progress);
        $.get("/ajax.php", {
            action: "profile_savemydata",
            fname: $fname.val(),
            mname: $mname.val(),
            lname: $lname.val(),
            sex: $sex.val(),
            age: $age.val()
        }, function(data, textstatus) {
            $progress.remove();
            if (data == "1") {
                $msgtd.html("<span style='color:green'>Сохранено</span>");
            } else if (data == "-2000")
                showActivationWnd();
            else
                $msgtd.html("<span style='color:red'>Ошибка</span>");
        }, "json");
    });
    $("#save_pwd").click(function() {
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        var $this = $(this);
        var $passold = $("input[name=passold]");
        var $pass = $("input[name=pass]");
        var $pass2 = $("input[name=pass2]");
        var $msgtd = $this.parent().parent().find("td[name=form-message]");
        $msgtd.html("");
        var all_ready = true;
        if ($passold.val() == "") {
            showBalloon2("Поле не должно быть пустым", $passold[0], 5000);
            all_ready = false;
        }
        if ($pass.val() == "") {
            showBalloon2("Поле не должно быть пустым", $pass[0], 5000);
            all_ready = false;
        }
        if ($pass2.val() == "") {
            showBalloon2("Поле не должно быть пустым", $pass[0], 5000);
            all_ready = false;
        }
        if ($pass.val() != $pass2.val()) {
            showBalloon2("Пароли не совпадают", $pass2[0], 5000);
            all_ready = false;
        }
        if (all_ready && $pass.val().length < 6) {
            showBalloon2("Пароль слишком короткий", $pass[0], 5000);
            all_ready = false;
        }
        if (all_ready) {
            $progress = $("<img id='loginprogress' src='/img/ajax-loader2.gif'>");
            $msgtd.append($progress);
            $.get("/ajax.php", {
                action: "profile_savepwd",
                passold: $passold.val(),
                pass: $pass.val(),
                pass2: $pass2.val()
            }, function(data, textstatus) {
                $progress.remove();
                if (data == "1")
                    $msgtd.html("<span style='color:green'>Сохранено</span>");
                else if (data == "-2000")
                    showActivationWnd();
                else
                    $msgtd.html("<span style='color:red'>Ошибка</span>");
                if (data == "-1")
                    showBalloon2("Неверный пароль", $passold[0], 5000);
            }, "json");
        }
    });
    $("#ava_loadnew").click(function(event) {
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        closeAll(this, function() {
            $("#page_locker").stop().fadeIn("fast", function() {
                $(this).css("filter", "alpha(opacity=20)");
                $("#addavatar").show();
            });
        });
        event.stopPropagation();
    });
    $("#closeaddavatarform,#closedelavatarform").click(function() {
        $("#page_locker").click();
    });
    $("#ava_delete").click(function(event) {
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        closeAll(this, function() {
            $("#page_locker").stop().fadeIn("fast", function() {
                $(this).css("filter", "alpha(opacity=20)");
                $("#delavatar").show();
            });
        });
        event.stopPropagation();
    });
    $("#delavatarbtn").click(function() {
        $.get("/ajax.php", {
            action: "profile_delavatar"
        }, function(data, textstatus) {
            location.reload();
        });
    });
    $("#save_settingsf").click(function() {
        var $this = $(this);
        var $fnotify = $("input[name=20x7NotifyMsgOff]");
        var fnotify = ($fnotify[0].checked ? '1' : '0');
        var $fsounds = $("input[name=20x7SoundsOn]");
        var fsounds = ($fsounds[0].checked ? '1' : '0');
        var $fmobile = $("input[name=MobilePayNotifyHide]");
        var fmobile = ($fmobile[0].checked ? '1' : '0');
        var $msgtd = $this.parent().parent().find("td[name=form-message]");
        $msgtd.html("");
        $progress = $("<img id='loginprogress' src='/img/ajax-loader2.gif'>");
        $msgtd.append($progress);
        $.get("/ajax.php", {
            action: "profile_savesettingsf",
            fnotify: fnotify,
            fsounds: fsounds,
            fmobile: fmobile
        }, function(data, textstatus) {
            $progress.remove();
            if (data == "1") {
                $msgtd.html("<span style='color:green'>Сохранено</span>");
            } else if (data == "-2000")
                showActivationWnd();
            else
                $msgtd.html("<span style='color:red'>Ошибка</span>");
        }, "json");
    });
}

function setAutobidEvents() {
    $("#autobidoptbtn").unbind("click").click(function(event) {
        closeAll(this, function(oContext) {
            if ($("#autobidoptions").css("left") == "0px") {
                $("#autobidoptions").stop().animate({
                    opacity: "0"
                }, 500, function() {
                    $(this).css("left", "-9999px")
                });
                $(oContext).removeClass("autobidoptbtnpressed");
            } else {
                $("#autobidoptions").stop().css("left", "0px").animate({
                    opacity: "1"
                }, 300);
                $(oContext).addClass("autobidoptbtnpressed");
            }
        });
        event.stopPropagation();
    });
    $("#autobidoptions").unbind("click").click(function(event) {
        event.stopPropagation();
    });
    $("#abidoptions_submit").unbind("click").click(function(event) {
        var $obj = $(this);
        var $this = $obj;
        var $bidtuner = $("#bidtuner");
        var $lp = $bidtuner.find(".switcheraloader");
        $lp.show();
        var sum = $("#bidsummax").val();
        var tm = $("#untiltm").val();
        var tmtpl = /([0-9]{2})\.([0-9]{2})\.([0-9]{4})\s([0-9]{2}):([0-9]{2})/i;
        var tmarr = tmtpl.exec(tm);
        var uw = ($("#untilwin")[0].checked ? "yes" : "");
        var bidtm = $("#bidtime").val();
        var saleid = $("#bidsaleid").val();
        var id = saleid;
        var oTM = new Date(tmarr[3], parseInt(tmarr[2]) - 1, tmarr[1], tmarr[4], tmarr[5], 0, 0);
        var now = new Date();
        if ($("#need_activation").length > 0) {
            showActivationWnd();
            return false;
        }
        var preorder = "";
        preorder = $this.parents("#item" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#itemdetail" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#itemtablerow" + id);
        if (preorder.length == 0)
            preorder = $this.parents("#historyrow" + id);
        var ipreorder = null;
        if (preorder.length != 0)
            ipreorder = preorder.find("input[name=preorder]");
        if (ipreorder && ipreorder.length > 0) {
            var c = getCookie("pre_" + id);
            if (!c && c != id) {
                setCookie("pre_" + id, id, {
                    expires: 259200,
                    path: "/"
                });
                $("#page_locker").fadeIn("normal", function() {
                    var d = $("<div id='preorder_notice' class='modalwin'><p style='text-align:center; font-size:1.2em;'>Данный товар доступен на условиях &#171;Предзаказ&#187;.<br><br><br></p><p style='text-align: center;'><span class='buttongray' onclick='showPreorderDetail();'>Подробнее</span>&nbsp;&nbsp;<span class='buttonorange' onclick='$(\"#abidoptions_submit\").click();$(\"#page_locker\").click();'>Продолжить</span></p></div>");
                    $("body").append(d);
                    $("#preorder_notice").show();
                });
                return false;
            }
        }
        if ((now.getTime() >= oTM.getTime()) && (uw == "")) {
            showBalloon2("Ошибка. Дата указана в прошлом.", $obj[0]);
            $lp.hide();
            return false;
        }
        if (sum <= 0) {
            showBalloon2("Ошибка. Не задано количество ставок", $obj[0]);
            $lp.hide();
            return false;
        }
        var utctm = oTM.getTime() + gTMZone * 1000;
        $.get("/ajax.php", {
            action: "autoBidOptionsSave",
            bidsummax: sum,
            untiltm: tm,
            bidsaleid: saleid,
            untilwin: uw,
            bidtime: bidtm,
            tmzone: gTMZone,
            tmdelta: gTMDelta,
            utctm: utctm
        }, function(data, textstatus) {
            switch (data.code) {
                case "1":
                    $("#editflag").val("0");
                    $bidtuner.click()
                    return true;
                    break;
                case "-1":
                    openRegForm();
                    showBalloon2(data.message, $("#regform")[0]);
                    break;
                case "0":
                case "-2":
                case "-3":
                case "-7":
                case "-8":
                case "-11":
                case "-12":
                case "-16":
                case "-17":
                case "-18":
                    showBalloon2(data.message, $("#abidoptions_submit")[0]);
                    break;
                case "-9":
                case "-10":
                    showBalloon2(data.message, $("#abidoptions_submit")[0], 4000);
                    break;
                case "-2000":
                    showActivationWnd();
                    break;
            }
            $lp.hide();
        }, "json");
        event.stopPropagation();
    });
    $("#abidoptions_stop").unbind("click").click(function(event) {
        $("#bidtuner").click()
        event.stopPropagation();
    });
    $("#abidoptions_save").unbind("click").click(function(event) {
        event.stopPropagation();
        var $bidtuner = $("#bidtuner");
        var $lp = $bidtuner.find(".switcheraloader");
        var sum = $("#bidsummax").val();
        var tm = $("#untiltm").val();
        var tmtpl = /([0-9]{2})\.([0-9]{2})\.([0-9]{4})\s([0-9]{2}):([0-9]{2})/i;
        var tmarr = tmtpl.exec(tm);
        var uw = ($("#untilwin")[0].checked ? "yes" : "");
        var bidtm = $("#bidtime").val();
        var saleid = $("#bidsaleid").val();
        var oTM = new Date(tmarr[3], parseInt(tmarr[2]) - 1, tmarr[1], tmarr[4], tmarr[5], 0, 0);
        var now = new Date();
        $this = $(this);
        if ((now.getTime() >= oTM.getTime()) && (uw == "")) {
            showBalloon2("Ошибка. Дата указана в прошлом.", this);
            cancelAutobidChange();
            return false;
        }
        if (sum <= 0) {
            showBalloon2("Ошибка. Не задано количество ставок", this);
            cancelAutobidChange();
            return false;
        }
        var utctm = oTM.getTime() + gTMZone * 1000;
        $lp.show();
        $.get("/ajax.php", {
            action: "autoBidOptionsSave",
            bidsummax: sum,
            untiltm: tm,
            bidsaleid: saleid,
            untilwin: uw,
            bidtime: bidtm,
            tmzone: gTMZone,
            tmdelta: gTMDelta,
            utctm: utctm
        }, function(data, textstatus) {
            $lp.hide();
            switch (data.code) {
                case "1":
                    $this.hide();
                    $("#abidoptions_stop").show();
                    $("#editflag").val("0");
                    break;
                case "-1":
                    cancelAutobidChange();
                    openRegForm();
                    showBalloon2(data.message, $("#regform")[0]);
                    break;
                case "0":
                case "-2":
                case "-3":
                case "-8":
                case "-11":
                case "-12":
                case "-16":
                case "-17":
                case "-18":
                    cancelAutobidChange();
                    showBalloon2(data.message, $("#abidoptions_submit")[0]);
                    break;
                case "-9":
                case "-10":
                    cancelAutobidChange();
                    showBalloon2(data.message, $("#abidoptions_submit")[0], 4000);
                    break;
                case "-2000":
                    cancelAutobidChange();
                    showActivationWnd();
                    break;
            }
        }, "json");
    });
    $("#autobidoptions input").unbind("change keyup").bind("change keyup", function() {
        var $this = $(this);
        var $sb = $("#abidoptions_save");
        var $cb = $("#abidoptions_stop");
        var $swb = $("#abidoptions_submit");
        var $fl = $("#editflag");
        if (!$cb.is(":hidden")) {
            $cb.hide();
            $sb.show();
            $fl.val("1");
        }
        if (!$swb.is(":hidden")) {
            $fl.val("1");
        }
    });
}
var cancelAutobidChange = function(floff) {
    var saleid = $("#bidsaleid").val();
    $.get("/ajax.php", {
        action: "autoBidData",
        bidsaleid: saleid
    }, function(data, textstatus) {
        if (data.res == "1") {
            var $bs = $("#itemdetail" + saleid + " #bidsummax");
            var $tm = $("#itemdetail" + saleid + " #untiltm");
            var $uw = $("#itemdetail" + saleid + " #untilwin");
            var $pu = $("#itemdetail" + saleid + " #playuntil");
            var $bidtm = $("#itemdetail" + saleid + " #bidtime");
            $bs.val(data.bidsum);
            var dt = convDate(data.tm, true, false);
            $tm.datepicker("setDate", dt);
            $tm.val(dt);
            changeDateTime($tm[0]);
            if (data.uw == "1") $uw.parent().click();
            else $pu.parent().click();
            $bidtm.val(data.bidtm);
            $("#abidoptions_save").hide();
            if (floff)
                $("#abidoptions_submit").show();
            else
                $("#abidoptions_stop").show();
            $("#editflag").val("0");
        }
    }, "json");
};;
$(function() {
    $("#btn_getorderbids").click(function(evt) {
        var oid = $(this).attr("name");
        var btn = this;
        $.get("/ajax.php", {
            action: "getcashbackbids",
            oid: oid
        }, function(data, textstatus) {
            if (data.res == "1") {
                location.assign(location.href);
            } else if ($.trim(data.res) == "0") {
                showBalloon2(data.mes, btn);
            }
        }, "json");
        evt.stopPropagation();
    });
    $("#compensation-form-submit").click(function() {
        var all_ready = true;
        $(".compensation-input:visible").each(function() {
            all_ready = checkCompensationField($(this)) && all_ready;
        });
        if ($("input[name=user_passport_series]").is(":visible"))
            all_ready = checkCompensationField($("#File2")) && all_ready;
        if (all_ready) {
            var formData = $("#cashbak-form").submit();
        }
    });
    $(".compensation-input").focusout(function() {
        checkCompensationField($(this));
    });

    function checkCompensationField(input) {
        var name = input.attr('id');
        switch (name) {
            case "bank_name":
            case "bank_city":
            case "user_last_name":
            case "user_first_name":
            case "user_middle_name":
            case "user_nationality":
            case "user_country":
            case "user_region":
            case "user_city":
            case "user_address":
            case "user_passport_by":
            case "user_tel":
            case "user_last_name1":
            case "user_first_name1":
            case "user_middle_name1":
            case "user_nationality1":
            case "user_country1":
            case "user_region1":
            case "user_city1":
            case "user_address1":
            case "user_passport_by1":
            case "user_tel1":
            case "user_last_name2":
            case "user_first_name2":
            case "user_middle_name2":
            case "user_nationality2":
            case "user_country2":
            case "user_region2":
            case "user_city2":
            case "user_address2":
            case "user_passport_by2":
            case "user_tel2":
            case "user_last_name3":
            case "user_first_name3":
            case "user_middle_name3":
            case "user_nationality3":
            case "user_country3":
            case "user_region3":
            case "user_city3":
            case "user_address3":
            case "user_passport_by3":
            case "user_tel3":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                }
                break;
            case "bank_bik":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                } else if (!$.isNumeric(input.val()) || input.val().length != 9) {
                    showBalloon2("Ошибка! Должно быть 9 цифр", input[0], 5000);
                    return false;
                }
                break;
            case "bank_cor":
            case "user_rs":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                } else if (!$.isNumeric(input.val()) || input.val().length != 20) {
                    showBalloon2("Ошибка! Должно быть 20 цифр", input[0], 5000);
                    return false;
                }
                break;
            case "user_inn":
            case "mobile_phone":
            case "qiwi":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                } else if (!$.isNumeric(input.val()) || input.val().length != 12) {
                    showBalloon2("Ошибка! Должно быть 12 цифр", input[0], 5000);
                    return false;
                }
                break;
            case "yandex":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                }
                break;
            case "user_birthday":
            case "user_passport_create":
            case "user_birthday1":
            case "user_passport_create1":
            case "user_birthday2":
            case "user_passport_create2":
            case "user_birthday3":
            case "user_passport_create3":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                }
                break;
            case "user_passport_series":
            case "user_passport_series1":
            case "user_passport_series2":
            case "user_passport_series3":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Поле не должно быть пустым", input[0], 5000);
                    return false;
                } else if (input.val().replace(/_/g, "").length != 11) {
                    showBalloon2("Ошибка! Должно быть 4 цифры серии и 6 цифр номера разделенные символом «/» косая черта", input[0], 5000);
                    return false;
                }
            case "File2":
                if (!input.val() || input.val().trim() == '') {
                    showBalloon2("Вы должны приложить фото страниц паспорта", $("#BrowseButton")[0], 5000);
                    return false;
                }
        }
        return true;
    }
    $("#btn_acceptorder").click(function(evt) {
        var oid = $(this).attr("name");
        var btn = this;
        var $w = $("input[name=color_warn]");
        var wt = "";
        if ($w.length > 0) wt = $w.val();
        if (wt != "") {
            var $col = $("#color_sel");
            showBalloon2(wt, $col[0], 1000 * 60 * 60);
            if ($.browser.safari) {
                $('body').animate({
                    scrollTop: 0
                }, 500);
            } else {
                $('html').animate({
                    scrollTop: 0
                }, 500);
            }
            return false;
        }
        $w = $("input[name=model_warn]");
        wt = "";
        if ($w.length > 0) wt = $w.val();
        if (wt != "") {
            var $model = $("#model_sel");
            showBalloon2(wt, $model[0], 1000 * 60 * 60);
            if ($.browser.safari) {
                $('body').animate({
                    scrollTop: 0
                }, 500);
            } else {
                $('html').animate({
                    scrollTop: 0
                }, 500);
            }
            return false;
        }
        $.get("/ajax.php", {
            action: "orderaccept",
            oid: oid
        }, function(data, textstatus) {
            if (data.res == "1") {
                location.assign(location.href);
            } else if ($.trim(data.res) == "0") {
                showBalloon2(data.mes, btn);
            }
        }, "json");
        evt.stopPropagation();
    });
    $("#btn_payorder").click(function(evt) {
        var oid = $(this).attr("name");
        var btn = this;
        var pmttype = "";
        var pstype = "";
        var accept = $("input[name=order_of]")[0].checked;
        if (!accept) {
            showBalloon2("Вы должны согласиться с условиями покупки-продажи товаров", $("input[name=order_of]")[0]);
            return false;
        }
        $("input[name=pmttype]").each(function(ind, elm) {
            if (elm.checked) pmttype = $(elm).val();
        });
        $("input[name=pstype]").each(function(ind, elm) {
            if (elm.checked) pstype = $(elm).val();
        });
        var $w = $("input[name=color_warn]");
        var wt = "";
        if ($w.length > 0) wt = $w.val();
        if (wt != "") {
            var $col = $("#color_sel");
            showBalloon2(wt, $col[0], 1000 * 60 * 60);
            if ($.browser.safari) {
                $('body').animate({
                    scrollTop: 0
                }, 500);
            } else {
                $('html').animate({
                    scrollTop: 0
                }, 500);
            }
            return false;
        }
        $.get("/ajax.php", {
            action: "orderpay",
            oid: oid,
            pmttype: pmttype,
            pstype: pstype
        }, function(data, textstatus) {
            if (data.res == 2) {
                location.assign(location.href);
            }
            if (data.res == 1) {
                $(".content-block").append(data.html);
                $("#roboform").submit();
            } else if ($.trim(data) == 0) {
                showBalloon2("Произошла ошибка: " + res.mes + ".<br>Пожалуйста, повторите позже", btn);
            }
        }, "json");
        evt.stopPropagation();
    });
    $("#btn_cancelorder").click(function() {
        var oid = $(this).attr("name");
        var btn = this;
        $.get("/ajax.php", {
            action: "orderdel",
            oid: oid
        }, function(data, textstatus) {
            if ($.trim(data) != -1 && $.trim(data) != 0) {
                location.assign("/");
            } else if ($.trim(data) == -1) {
                showBalloon2("Зарегистрируйтесь или войдите в систему.", btn);
            } else if ($.trim(data) == 0) {
                showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", btn);
            }
        });
    });
    $("#btn_gotoconfirmaddress").click(function() {
        var oid = $(this).attr("name");
        var rb = $("input[name=addrid]");
        var addrid = 0;
        for (key in rb) {
            if (rb[key].checked) addrid = $(rb[key]).val();
        }
        var btn = this;
        var $w = $("input[name=color_warn]");
        var wt = "";
        if ($w.length > 0) wt = $w.val();
        if (wt != "") {
            var $col = $("#color_sel");
            showBalloon2(wt, $col[0], 1000 * 60 * 60);
            if ($.browser.safari) {
                $('body').animate({
                    scrollTop: 0
                }, 500);
            } else {
                $('html').animate({
                    scrollTop: 0
                }, 500);
            }
            return false;
        }
        $.get("/ajax.php", {
            action: "saveaddress",
            oid: oid,
            addrid: addrid
        }, function(data, textstatus) {
            if ($.trim(data) != -1 && $.trim(data) != 0 && $.trim(data) != -2) {
                location.assign(location.href);
            } else if ($.trim(data) == -1) {
                showBalloon2("Зарегистрируйтесь или войдите в систему.", btn);
            } else if ($.trim(data) == -2) {
                showBalloon2("Вы должны заполнить все поля адреса доставки.", btn);
            } else if ($.trim(data) == 0) {
                showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", btn);
            }
        });
    });
    $("#backonestep").click(function() {
        var oid = $(this).attr("name");
        $.get("/ajax.php", {
            action: "backonestep",
            oid: oid
        }, function(data, textstatus) {
            if ($.trim(data) != -1 && $.trim(data) != 0) {
                location.assign(location.href);
            } else if ($.trim(data) == -1) {
                showBalloon2("Зарегистрируйтесь или войдите в систему.", btn);
            } else if ($.trim(data) == 0) {
                showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", btn);
            }
        });
    });
    $("#cancelorder").click(function() {
        var oid = $(this).attr("name");
        $.get("/ajax.php", {
            action: "cancelorder",
            oid: oid
        }, function(data, textstatus) {
            if ($.trim(data) != -1 && $.trim(data) != 0) {
                location.assign("/");
            } else if ($.trim(data) == -1) {
                showBalloon2("Зарегистрируйтесь или войдите в систему.", btn);
            } else if ($.trim(data) == 0) {
                showBalloon2("Произошла ошибка.<br>Пожалуйста, повторите позже", btn);
            }
        });
    });
    $("input[name=shipcode]").change(function() {
        var oid = $("#btn_gotoconfirmaddress").attr("name");
        var val = "";
        $("input[name=shipcode]").each(function(ind, elm) {
            if (elm.checked)
                val = $(elm).val();
        });
        $.get("/ajax.php", {
            action: "updateordershippingcode",
            oid: oid,
            val: val
        }, function(data, textstatus) {;
        });
    });
    $('#color_sel').click(function() {
        var $this = $(this);
        if ($('.color-selector').is(":hidden"))
            $('.color-selector').fadeIn("fast");
        else
            $('.color-selector').fadeOut("fast");
    });
    $('.color-selector li').click(function() {
        var $this = $(this);
        var oid = $("#btn_acceptorder").attr("name");
        if (!oid)
            oid = $("#btn_gotoconfirmaddress").attr("name");
        if (!oid)
            oid = $("#btn_payorder").attr("name");
        $("input[name=color_warn]").remove();
        $('#color_sel').text($this.text());
        $('input[name=sel_color]').val($this.text());
        $('.color-selector').fadeOut("fast");
        $.get("/ajax.php", {
            action: "updateordercolor",
            oid: oid,
            val: $this.text()
        }, function(data, textstatus) {
            $('#color_sel').parent().find(".balloon").hide();
        });
    });
    $('#model_sel').click(function() {
        var $this = $(this);
        if ($('.model-selector').is(":hidden"))
            $('.model-selector').fadeIn("fast");
        else
            $('.model-selector').fadeOut("fast");
    });
    $('.model-selector li').click(function() {
        var $this = $(this);
        var oid = $("#btn_acceptorder").attr("name");
        if (!oid)
            oid = $("#btn_gotoconfirmaddress").attr("name");
        if (!oid)
            oid = $("#btn_payorder").attr("name");
        $("input[name=model_warn]").remove();
        $('#model_sel').text($this.text());
        $('input[name=sel_model]').val($this.text());
        $('.model-selector').fadeOut("fast");
        $.get("/ajax.php", {
            action: "updateordermodel",
            oid: oid,
            val: $this.text()
        }, function(data, textstatus) {
            $('#model_sel').parent().find(".balloon").hide();
        });
    });
    $("#paymentservice .radio-button-wrapper [name=pstype]").on("change", function() {
        var oInp = this;
        if (oInp.checked) {
            if (oInp.value == "Yandexkassa") {
                $(".item_Tele2").prop("hidden", true);
                $(".item_MTS").prop("hidden", true);
                $(".item_Megafon").prop("hidden", true);
                $(".item_Beeline").prop("hidden", true);
                $(".item_Cash").prop("hidden", false);
                $(".item_Webmoney").prop("hidden", false);
            } else if (oInp.value == "Robokassa") {
                $(".item_Tele2").prop("hidden", false);
                $(".item_MTS").prop("hidden", false);
                $(".item_Megafon").prop("hidden", false);
                $(".item_Beeline").prop("hidden", false);
                $(".item_Cash").prop("hidden", true);
                $(".item_Webmoney").prop("hidden", true);
            }
            $(".buybids#paysysselect tr").each(function(ind, elm) {
                if (elm.hidden) {
                    if ($(elm).find('[name=pmttype]')[0].checked) {
                        $("#pmtPayCard").click();
                    }
                }
            });
            $(".order#paysysselect td").each(function(ind, elm) {
                if (elm.hidden && $(elm).find('[name=pmttype]').length) {
                    if ($(elm).find('[name=pmttype]')[0].checked) {
                        $("#pmtPayCard").click();
                    }
                }
            });
        }
    });
});
var addrformsubmit = function() {
    var recipient = $.trim($("input[name=addrrecipient]").val());
    var index = $.trim($("input[name=addrindex]").val());
    var city = $.trim($("input[name=addrcity]").val());
    var addr = $.trim($("input[name=addraddr]").val());
    var phoneumber = $.trim($("input[name=phonenumber]").val());
    if (recipient == "" || index == "" || city == "" || addr == "" || phoneumber == "") {
        showBalloon2("Необходимо заполнить все обязательные поля формы.", $(".buttongreen")[0]);
    } else
        return true;
};;

function FS_CHKPWD(str, id_info) {
    var t = str[0];
    for (var q = 1; q < str.length; ++q) {
        if (str[q] != t[t.length - 1]) t += str[q];
    }
    str = t;
    var i = document.getElementById(id_info);
    if (str.length == 0) i.innerHTML = "";
    var small = "qwertyuiopasdfghjklzxcvbnmйцукенгшщзхъэждлорпавыфячсмитьбю";
    var big = "QWERTYUIOPLKJHGFDSAZXCVBNMЙЦУКЕНГШЩЗХЪЭЖДЛОРПАВЫФЯЧСМИТЬБЮ";
    var num = "0123456789";
    var spec = "~!@#$%^&*()_-+=\|/.,:;\"'[]{}№`";
    var ismall = false,
        ibig = false,
        ispec = false,
        inum = false;
    for (var q = 0; q < str.length; ++q) {
        if (!ismall && small.indexOf(str[q]) != -1) {
            ismall = true;
            continue
        }
        if (!ibig && big.indexOf(str[q]) != -1) {
            ibig = true;
            continue
        }
        if (!ispec && spec.indexOf(str[q]) != -1) {
            ispec = true;
            continue
        }
        if (!inum && num.indexOf(str[q]) != -1) {
            inum = true;
            continue
        }
    }
    var p = 0;
    if (ismall) p++;
    if (ibig) p++;
    if (inum) p++;
    if (ispec) p++;
    if (str.length < 6 && p < 3) i.innerHTML = "Простой";
    else if (str.length < 6 && p >= 3) i.innerHTML = "Средний";
    else if (str.length >= 8 && p < 3) i.innerHTML = "Средний";
    else if (str.length >= 8 && p >= 3) i.innerHTML = "Сложный";
    else if (str.length >= 6 && p == 1) i.innerHTML = "Простой";
    else if (str.length >= 6 && p > 1 && p < 4) i.innerHTML = "Средний";
    else if (str.length >= 6 && p == 4) i.innerHTML = "Сложный";
}