'ucss strict';

var gSnsp1 = '';
var gIsWork1=false;

// console.log('主站台的網址_gMweb1:'+gMweb1);
// console.log('站台目前網址_gNweb1:'+gNweb1);
// console.log('站台目前網址路徑_gPweb1:'+gPweb1);
// console.log('API程序-主機1:_gAweb1'+gAweb1);
// console.log('上傳檔案-主機1_gUweb1:'+gUweb1);
// console.log('資源-主機1_gRweb1:'+gRweb1);

var gCHtml = [];
var gCJs = [];
var gCCss = [];
var gCJson = [];
var gWorker = [];

var gLang = [];

var gUser1 = {
    'account1': 'none',
    'name1': '不明',
    'lang1': 'tw'
};

//語系表
const gLf1 = 'tw';

if (localStorage.getItem('lang') !== null)
{
    gUser1.lang1 = localStorage.getItem('lang');
}
else
{
    // 瀏覽器的語系

    var pLang1 = window.navigator.userLanguage || window.navigator.language;
    pLang1 = pLang1.toLowerCase();

    if (pLang1 === "zh-tw")
    {
        gUser1.lang1 = 'tw';
    }
    else if (pLang1 === "zh-cn")
    {
        gUser1.lang1 = "cn";
    }
    else
    {
        gUser1.lang1 = "en";
    }

}
//語系表

Array.prototype.aryUnique = function () {
    var a = this.concat(); //使用concat()再複製一份陣列，避免影響原陣列
    for (var i = 0; i < a.length; ++i) {
        for (var j = i + 1; j < a.length; ++j) {
            if (a[i] === a[j])
                a.splice(j, 1);
        }
    }

    return a;
};

// CSS縮小
function minifyCSS(vStr1) {
	var pStr1 = vStr1;
	pStr1 = pStr1.replace(/([^0-9a-zA-Z\.#])\s+/g, "$1");
	pStr1 = pStr1.replace(/\s([^0-9a-zA-Z\.#]+)/g, "$1");
	pStr1 = pStr1.replace(/;}/g, "}");
	pStr1 = pStr1.replace(/\/\*.*?\*\//g, "");

	return (pStr1);
}

// 建立CSS
function createCss(vStr1, vStr2) {
    if ($('style#' + vStr1).length == 0)
    {
        //console.log('create css:'+vStr1);

        let pStr1s=vStr1.split(/_/g);
        let pStr1='.agiis';

        for (let i=0; i<(pStr1s.length-1); i++){
            if (pStr1s[i]!=''){
                pStr1+='.'+pStr1s[i];
            }            
        }
        //console.log(pStr1s, pStr1);

        let pObj1 = document.createElement('style');
        pObj1.setAttribute('type', 'text/css');
        pObj1.setAttribute('id', vStr1);
        pObj1.innerHTML = vStr2.replace(/\.nameSpace1/g, pStr1);
        document.getElementsByTagName("head")[0].appendChild(pObj1);
    }
}

// 建立js
function createJs(vStr1, vStr2) {
    if ($('script#' + vStr1).length == 0) {

        let pStr1s=vStr1.split(/_/g);
        let pStr1='';

        for (let i=0; i<(pStr1s.length-1); i++){
            if (pStr1s[i]!=''){
                if (pStr1==''){
                    pStr1=pStr1s[i];
                }else{
                    pStr1+='_'+pStr1s[i];
                }
                
            }            
        }

        //console.log(pStr1);

        let pObj1 = document.createElement('script');
        pObj1.setAttribute('type', 'text/javascript');
        pObj1.setAttribute('id', vStr1);
        pObj1.innerHTML = vStr2.replace(/nameSpace1/g, pStr1);;
        document.getElementsByTagName("head")[0].appendChild(pObj1);
    }
}

function createCssAll(vAry1){
    for (let vkey1 in gCCss) {
        let pVar1=gCCss[vkey1];


        if (!$.isFunction(pVar1) && $.inArray( vkey1, vAry1 )<0){
            createCss(vkey1, pVar1);
        }
    }
}

function createJsAll(vAry1) {
    for (var vkey1 in gCJs) {
        var pVar1=gCJs[vkey1];
        if (!$.isFunction(pVar1) && $.inArray( vkey1, vAry1 )<0){
            createJs(vkey1, pVar1);
        }
    }
}

// 建立js Obfuscator
function obfuscateJs(vStr1) {
}

// 連結引入 //
function rLink(vStr1, vStr2) {
    var pDefe1 = $.Deferred();   //宣告Deferred物件
    var pReg1 = new RegExp(gMweb1);

    if (vStr1 == '') {
        pDefe1.resolve({ 'id': 'error', 'type': 'none', 'description': 'no vStr1' });
        return pDefe1.promise();
    }

    //console.log(vStr1);

    var pName1 = '';

    var pPath1s = vStr1.split(/\?/g)[0].replace(pReg1, '').split(/\//g);
    var pFile1 = pPath1s[(pPath1s.length - 1)];
    var pType1 = pFile1.split(/\./g);
    pType1 = pType1[(pType1.length - 1)].toLowerCase();

    if (vStr2!=undefined && vStr2!=null && vStr2 != '') {
        pName1=vStr2;
    }

    if (pType1 == 'css') {
        if (vStr2==undefined || vStr2==null || vStr2 == '') {
            for (var i = 0; i < (pPath1s.length); i++) {
                if (i == 0) {
                    pName1 = pPath1s[i];
                }
                else {
                    pName1 += '_' + pPath1s[i];
                }
            }
    
            pName1 = pName1.replace(/\.css/g, '');
            pName1 = pName1.replace(/\./g, '');

            pName1 = ''+pName1;
        }

        if ($('link #' + pName1).length == 0) {
            $('head').append('<link rel="preload" id="' + pName1 + '" href="' + vStr1 + '"  as="style" onload="this.rel=\'stylesheet\'" />');
            $('head').append('<link id="' + pName1 + '" rel="stylesheet" href="' + vStr1 + '" />');
        }

    }
    else if (pType1 == 'js') {
        if (vStr2==undefined || vStr2==null || vStr2 == '') {
            for (var i = 0; i < (pPath1s.length - 1); i++) {
                if (i == 0) {
                    pName1 = pPath1s[i];
                }
                else {
                    pName1 += '_' + pPath1s[i];
                }
            }
    
            pName1 = pName1.replace(/\.js/g, '');
            pName1 = pName1.replace(/\./g, '');

            pName1 = ''+pName1;            
        }

        if ($('script #' + pName1).length == 0) {
            var head = document.getElementsByTagName('head')[0];
            var script = document.createElement('script');
            script.id = pName1;
            script.src = vStr1;
            script.defer = true;
            head.appendChild(script);
        }

    }

    pDefe1.resolve({ 'id': pName1, 'type': pType1 });

    return pDefe1.promise();
}

// 載入txt 傳回值 //
function rLoadRet(vStr1, vStr2, vStr3) {
    var pRest1 = vStr1
    var pType1 = vStr2;
    var pName1 = vStr3;

    if (pType1 == 'html') {
        pRest1 = pRest1.split(/<segment><\/segment>/g);

        if (pRest1.length > 0) {
            pRest1 = pRest1[1];
            pRest1 = $.trim(pRest1.replace(/\n/g, '').replace(/\r/g, '').replace(/\t/g, ''));
            pRest1s = pRest1.split(/>/g);

            for (var i = 0; i < pRest1s.length; i++) {
                if (i == 0) {
                    pRest1 = $.trim(pRest1s[i]);
                } else {
                    pRest1 += '>' + $.trim(pRest1s[i]);
                }
            }

        } else {
            pRest1 = '';
        }

        gCHtml[pName1] = pRest1;

        return (gCHtml[pName1]);
    }
    else if (pType1 == 'css') {

        gCCss[pName1] = pRest1.replace(/\$1/g, gMweb1);

        return (gCCss[pName1]);
    }
    else if (pType1 == 'json')
    {

        if (pName1.indexOf('i18n') == -1) {
            gCJson[pName1] = $.parseJSON(pRest1);
            return (gCJson[pName1]);
        } else {
            gLang[pName1] = $.parseJSON(pRest1);
            return (gLang[pName1]);
        }

        
    } else if (pType1 == 'js') {
        gCJs[pName1] = pRest1;

        return (gCJs[pName1]);
    }
}

// 載入txt //
function rLoad(vStr1, VBln1) {
    var pDefe1 = $.Deferred();   //宣告Deferred物件
    var pReg1 = new RegExp(gMweb1);

    if (!VBln1) {
        VBln1 = false;
    }

    if (vStr1 == '') {
        pDefe1.resolve({ 'id': 'error', 'type': 'none', 'description': 'no vStr1' });
        return pDefe1.promise();
    }

    //console.log(vStr1);

    var pPath1s = vStr1.split(/\?/g)[0].replace(pReg1, '').split(/\//g);
    var pFile1 = pPath1s[(pPath1s.length - 1)];
    var pType1 = pFile1.split(/\./g);

    pType1 = pType1[(pType1.length - 1)].toLowerCase();
    var pName1 = '';

    if (pType1 == 'html')
    {
        for (var i = 0; i < (pPath1s.length - 0); i++) {
            if (i == 0) {
                pName1 = pPath1s[i];
            }
            else {
                pName1 += '_' + pPath1s[i];
            }
        }

        pName1 = pName1.replace(/\.html/g, '');
        pName1 = pName1.replace(/\./g, '');
        pName1 = ''+pName1;

        //console.log(pName1);

        if (gCHtml[pName1] || typeof (gCHtml[pName1]) != "undefined") {
            pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': gCHtml[pName1] });

            return pDefe1.promise();
        }

    }
    else if (pType1 == 'json')
    {

        for (var i = 0; i < (pPath1s.length-0); i++) {
            if (i == 0) {
                pName1 = pPath1s[i];
            }
            else {
                pName1 += '_' + pPath1s[i];
            }
        }

        pName1 = pName1.replace(/\.json/g, '');
        pName1 = pName1.replace(/\./g, '');
        pName1 = ''+pName1;

        //console.log('json:'+pName1);

        if (pName1.indexOf('i18n') == -1) {
            if (gCJson[pName1] || typeof (gCJson[pName1]) != "undefined") {
                pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': gCJson[pName1] });

                return pDefe1.promise();
            }
        } else {
            if (gLang[pName1] || typeof (gLang[pName1]) != "undefined") {
                pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': gLang[pName1] });

                return pDefe1.promise();
            }
        }

    }
    else if (pType1 == 'css') {
        for (var i = 0; i < (pPath1s.length - 0); i++) {
            if (i == 0) {
                pName1 = pPath1s[i];
            }
            else {
                pName1 += '_' + pPath1s[i];
            }
        }
        pName1 = pName1.replace(/\.css/g, '');
        pName1 = pName1.replace(/\./g, '');
        pName1 = ''+pName1;
        //console.log(pName1);

        if (gCCss[pName1] || typeof (gCCss[pName1]) != "undefined") {
            pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': gCCss[pName1] });

            return pDefe1.promise();
        }


    }
    else if (pType1 == 'js') {
        for (var i = 0; i < (pPath1s.length - 0); i++) {
            if (i == 0) {
                pName1 = pPath1s[i];
            }
            else {
                pName1 += '_' + pPath1s[i];
            }
        }

        pName1 = pName1.replace(/\.js/g, '');
        pName1 = pName1.replace(/\./g, '');
        pName1 = ''+pName1;
        //console.log('js:'+pName1);

        if (gCJs[pName1] || typeof (gCJs[pName1]) != "undefined") {
            pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': gCJs[pName1] });

            return pDefe1.promise();
        }
    }

    if (window.fetch === undefined) {
        if (window.Worker && VBln1) {
            //console.log('window Worker:' + pName1)

            var pWorker = new Worker(gMweb1+'/jss/rLoadAjax.js');
            pWorker.onmessage = function (e) {
                //console.log(e.data);
                pRest1 = rLoadRet(e.data, pType1, pName1);
                pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': pRest1 });

                pWorker.terminate();
            };

            pWorker.postMessage(vStr1);


        } else {
            //console.log('ajax:' + vStr1)

            $.ajax({
                cache: true, ifModified: false,
                beforeSend: function (vXhr1) { },
                url: vStr1,
                dataType: "text",
                success: function (vXhr1) {
                    //console.log(vXhr1);

                    pRest1 = rLoadRet(vXhr1, pType1, pName1);

                    pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': pRest1 });
                }
            });
        }
    }
    else 
    {
        if (window.Worker && VBln1) {
            //console.log('window Worker:' + pName1)

            var pWorker = new Worker(gMweb1+'/jss/rLoadFetch.js');
            pWorker.onmessage = function (e) {
                pRest1 = rLoadRet(e.data, pType1, pName1);
                pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': pRest1 });

                pWorker.terminate();
                //delete gWorker[pName1];
            };

            //console.log(vStr1);

            pWorker.postMessage(vStr1);

        } else {

            fetch(vStr1, { cache: "default" }).then(function (vXhr1) {
                return vXhr1.text();
            }).then(function (vXhr1) {
                pRest1 = rLoadRet(vXhr1, pType1, pName1);

                pDefe1.resolve({ 'id': pName1, 'type': pType1, 'content': pRest1 });
            });
        }
    }

    return pDefe1.promise();
}

// 防止氣泡觸發 //
function rCancelBubble(vEvent){
    var event = e || window.event;
    event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
}

(function (factory) {
    //+
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS style for Browserify
        module.exports = factory;
    } else {
        // Browser globals
        factory(jQuery);
    }
    //-
}(
    function ($) {

        (function ($) {
            $.fn.agiLang = function (vStr1) {
                //var pPath1=window.location.pathname.replace(/\//g, '_')+'i18n_'+gUser1.lang1;
                var pPath1=vStr1+'_i18n_'+gUser1.lang1;

                //console.log(pPath1);

                $(this).find('*[r-lang-class][r-lang-key]').each(function () {
                    var pQThis = $(this);
                    
                    if ($(this).attr("r-lang-key") != gUser1.lang1)
                    {
                        //console.log(gLang);
                        //console.log(gSnsp1+'_jss_i18n_'+gUser1.lang1);

                        $('title').html(gLang[pPath1]['page-title']);

                        pQThis.attr("r-lang-key", gUser1.lang1);

                        if (pQThis.attr("r-lang-active")) {
                            var pActive1s = $(this).attr("r-lang-active").split(/\[,\]/g);

                            pActive1s.forEach(function (vItem1) {
                                vItem1 = $.trim(vItem1);

                                var pItem1s = vItem1.split(/\[:\]/g);

                                if (pQThis.attr("r-lang-class") == '') {
                                    var Plang2 = gLang[pPath1];

                                    if (Plang2) {
                                        if (Plang2[pItem1s[1]]) {
                                            if (pItem1s[0] == "html") {
                                                pQThis.html(Plang2[pItem1s[1]]);
                                            } else {
                                                pQThis.attr(pItem1s[0], Plang2[pItem1s[1]]);
                                            }
                                        }
                                    }

                                } else {


                                    var pJssi18n2='_'+ gSnsp1 + pQThis.attr("r-lang-class") + '_i18n_'+gUser1.lang1;
                                    
                                    //console.log(pJssi18n2);

                                    var Plang1 = gLang[pJssi18n2];

                                    if (Plang1) {
                                        if (Plang1[pItem1s[1]]) {
                                            if (pItem1s[0] == "html") {
                                                pQThis.html(Plang1[pItem1s[1]]);
                                            } else {
                                                pQThis.attr(pItem1s[0], Plang1[pItem1s[1]]);
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            }

            $.fn.agiOn = function (vInt1) {
                if (vInt1 == null) {
                    vInt1 = 0;
                }

                $(this).each(function () {
                    let pFunc1s = $(this).attr('class').split(/\s/g);    //函數

                    //console.log(pFunc1s);

                    let pFstr1 = '';

                    for (let i=1; i<(pFunc1s.length-1); i++){
                        if (pFstr1==''){
                            pFstr1=pFunc1s[i];
                        }else{
                            pFstr1+='_'+pFunc1s[i];
                        }
                        
                    }

                    //console.log(pFstr1);
    
                    if (pFstr1 != null && pFstr1 != "") {
                        pFstr1 += '_On(pWObj1, pWInt1)';
                        window.pWObj1 = $(this);
                        window.pWInt1 = vInt1;
    
                        let pRun1 = Function(pFstr1);

                        try {
                            pRun1();
                        } catch (vErr1) {
                            if (vErr1.name=='ReferenceError'){
                                if (vErr1.message.indexOf('is not defined')<0){
                                    console.log(vErr1.name);
                                    console.log(vErr1.message);
                                    return(false);
                                }
                            }else{
                                console.log(vErr1.name);
                                console.log(vErr1.message);
                                return(false);
                            }

                            
                        } finally {
                        }
                        //let pIsFunc1 = $.isFunction(pRun1);

                        //console.log(pFstr1, pIsFunc1);

                        //if (pIsFunc1){
                        //    console.log(pRun1);
                        
                            //pRun1();
                        //}

                    }
                });
            }

            $.fn.agiDropdownClick = function (vObj1, vEvent1) {
                if (vObj1 == null) {
                    return(false);
                }

                var pFunc1 = $(this).attr('r-func');    //函數

                var pFstr1 = pFunc1;

                if (pFstr1 != null && pFstr1 != "") {
                    pFstr1 += '_DropdownClick(pWObj1, pWObj2, pWObj3)';
                    window.pWObj1 = $(this);
                    window.pWObj2 = vObj1;
                    window.pWObj3 = vEvent1;

                    var pRun1 = Function(pFstr1);

                    pRun1();
                }

            }

            $.fn.agiNeed = function (vInt1) {
                if (vInt1 == null) {
                    vInt1 = 0;
                }

                var pFunc1 = $(this).attr('r-func-note');    //函數

                var pFstr1 = pFunc1

                if (pFstr1 != null && pFstr1 != "") {
                    pFstr1 += '_Empty(pWObj1, pWInt1)';
                    window.pWObj1 = $(this);
                    window.pWInt1 = vInt1;

                    var pRun1 = Function('return(' + pFstr1 + ')');

                    return (pRun1());
                }

                return (false);
            }

            $.fn.agiVal = function (vStr1) {
                if (vStr1 == null) {
                    vStr1 = '';
                }

                var pFunc1 = $(this).attr('r-func');    //函數

                var pFstr1 = pFunc1

                if (pFstr1 != null && pFstr1 != "") {
                    pFstr1 += '_Val(pWObj1, pWStr1)';
                    window.pWObj1 = $(this);
                    window.pWStr1 = vStr1;

                    var pRun1 = Function(pFstr1);

                    pRun1();
                }
            }

            $.fn.agiClMask = function (vInt1) {
                if (vInt1 == null) {
                    vInt1 = 0;
                }

                //console.log(parseInt($(this).attr('r-is-mask')));

                var pFunc1 = $(this).attr('r-func');    //函數

                var pFstr1 = pFunc1

                if (pFstr1 != null && pFstr1 != "") {
                    pFstr1 += '_clmask(pWObj1, pWInt1)';
                    window.pWObj1 = $(this);
                    window.pWInt1 = vInt1;

                    var pRun1 = Function(pFstr1);

                    pRun1();
                }
            }
        })(jQuery);

        $(document).ready(
            function () {
                //公用載入
                var pRuns = [];

                pRuns.push(rLink('https://fonts.googleapis.com/css?family=IBM+Plex+Mono&display=swap', 'googleafont'));
                pRuns.push(rLink('https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css?display=swap', 'remixicon'));

                pRuns.push(rLoad(gMweb1 + '/jss/thirds/OverlayScrollbars-master/css/OverlayScrollbars.min.css?=1.12.0.1', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/zTree_v3-master/css/demo.css?=3.5.19', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/zTree_v3-master/css/zTreeStyle/zTreeStyle.css?=3.5.19', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/csss/agiis.css?=1.0.1.1', gIsWork1));

                pRuns.push(rLoad(gMweb1 + '/jss/thirds/OverlayScrollbars-master/js/jquery.overlayScrollbars.min.js?=1.12.0.1', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/jquery/jquery.cookie.min.js?=3.5.1.1', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/cleave.js-master/dist/cleave.min.js?=2.0.1.1', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/zTree_v3-master/js/jquery.ztree.core.min.js?=3.5.19', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/zTree_v3-master/js/jquery.ztree.excheck.min.js?=3.5.19', gIsWork1));
                pRuns.push(rLoad(gMweb1 + '/jss/thirds/zTree_v3-master/js/jquery.ztree.exedit.min.js?=3.5.19', gIsWork1));
                pRuns.push(rLoad(gNweb1 + '/index.js?=1.0.1.2', gIsWork1));

                $.when.apply($, pRuns).done(
                    function ()
                    {
                        //console.log(arguments);
                        //console.log(gCJs, gCCss)

                        // 建立所有的CSS
                        var pCss1s=[];
                        createCssAll(pCss1s);
                        // 建立所有的CSS

                        // 建立所有的JS
                        var pJs1s=[];
                        pJs1s.push(gPweb1.replace(/\//g,'_')+'_index');
                        createJsAll(pJs1s);
                        // 建立所有的JS

                        //$('body').overlayScrollbars({
                        //     'className': 'os-theme-light',
                        //     'scrollbars': {
                        //         'autoHide': 'm'
                        //     }
                        // });

                        $('segment').remove();

                        if (localStorage.getItem('lang') === null) {
                            localStorage.setItem('lang', gUser1.lang1);
                        } else {
                            gUser1.lang1 = localStorage.getItem('lang');
                        }

                        $('body').on('click', function(){
                            $('*[r-auto-hidden="1"]').hide();
                        });

                        $('*:not([r-cotrol-hidden])').on('focus', function(){
                            $('*[r-auto-hidden="1"]').hide();
                        });

                        $('*[r-cancel-bubble="1"]').on('click', function(e){
                            var event = e || window.event;
                            event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);
                        });

                        //執行未執行的JS
                        $.each(pJs1s, function(i, vVal1){    
                            createJs(vVal1, gCJs[vVal1]);
                        });
                        //執行未執行的JS
                        
                    }
                );
                //公用載入
            }
        );

        $(window).resize(function () {
            // 公用
            $('*[r-auto-hidden="1"]').hide();

        });
    }
));

