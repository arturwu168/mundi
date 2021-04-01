'use strict';

var newCount = 1;
var log, className = "dark";

function beforeDrag(treeId, treeNodes) {
        for (var i=0,l=treeNodes.length; i<l; i++) {
                if (treeNodes[i].drag === false) {
                        return false;
                }
        }
        return true;
}

function beforeDrop(treeId, treeNodes, targetNode, moveType) {
        return targetNode ? targetNode.drop !== false : true;
}

function setCheck(vObj1) {
        var zTree = $.fn.zTree.getZTreeObj(vObj1.attr('id')),
        isCopy = true,
        isMove = true,
        prev = true,
        inner = true,
        next = true;
        zTree.setting.edit.drag.isCopy = isCopy;
        zTree.setting.edit.drag.isMove = isMove;
        showCode(1, ['setting.edit.drag.isCopy = ' + isCopy, 'setting.edit.drag.isMove = ' + isMove]);

        zTree.setting.edit.drag.prev = prev;
        zTree.setting.edit.drag.inner = inner;
        zTree.setting.edit.drag.next = next;
        showCode(2, ['setting.edit.drag.prev = ' + prev, 'setting.edit.drag.inner = ' + inner, 'setting.edit.drag.next = ' + next]);
}

function showCode(id, str) {
        var code = $("#code" + id);
        code.empty();
        for (var i=0, l=str.length; i<l; i++) {
                code.append("<li>"+str[i]+"</li>");
        }
}

function beforeEditName(treeId, treeNode) {
        //console.log(treeNode.id, treeNode.pId, treeNode.name);
        className = (className === "dark" ? "":"dark");
        showLog("[ "+getTime()+" beforeEditName ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
        var zTree = $.fn.zTree.getZTreeObj(treeId);
        zTree.selectNode(treeNode);
        setTimeout(function() {
                if (confirm("进入节点 -- " + treeNode.name + " 的编辑状态吗？")) {
                        setTimeout(function() {
                                zTree.editName(treeNode);
                        }, 0);
                }
        }, 0);
        return false;
}

function beforeRemove(treeId, treeNode) {
        className = (className === "dark" ? "":"dark");
        showLog("[ "+getTime()+" beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
        var zTree = $.fn.zTree.getZTreeObj(treeId);
        zTree.selectNode(treeNode);
        return confirm("确认删除 节点 -- " + treeNode.name + " 吗？");
}

function onRemove(e, treeId, treeNode) {
        showLog("[ "+getTime()+" onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
}

function beforeRename(treeId, treeNode, newName, isCancel) {
        className = (className === "dark" ? "":"dark");
        showLog((isCancel ? "<span style='color:red'>":"") + "[ "+getTime()+" beforeRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>":""));
        if (newName.length == 0) {
                setTimeout(function() {
                        var zTree = $.fn.zTree.getZTreeObj(treeId);
                        zTree.cancelEditName();
                        alert("节点名称不能为空.");
                }, 0);
                return false;
        }
        return true;
}

function onRename(e, treeId, treeNode, isCancel) {
        showLog((isCancel ? "<span style='color:red'>":"") + "[ "+getTime()+" onRename ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name + (isCancel ? "</span>":""));
}

function showRemoveBtn(treeId, treeNode) {
        //return !treeNode.isFirstNode;
        return true;
}

function showRenameBtn(treeId, treeNode) {
        //return !treeNode.isLastNode;
        return true;
}

function showLog(str) {
        if (!log) log = $("#log");
        log.append("<li class='"+className+"'>"+str+"</li>");
        if(log.children("li").length > 8) {
                log.get(0).removeChild(log.children("li")[0]);
        }
}

function getTime() {
        var now= new Date(),
        h=now.getHours(),
        m=now.getMinutes(),
        s=now.getSeconds(),
        ms=now.getMilliseconds();
        return (h+":"+m+":"+s+ " " +ms);
}

function addHoverDom(treeId, treeNode) {
        var sObj = $("#" + treeNode.tId + "_span");
        if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
        var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
                + "' title='add node' onfocus='this.blur();'></span>";
        sObj.after(addStr);
        var btn = $("#addBtn_"+treeNode.tId);
        if (btn) btn.bind("click", function(){
                var zTree = $.fn.zTree.getZTreeObj(treeId);
                var treeNewNode=zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
                zTree.editName(treeNewNode[0]);
                return false;
        });
};

function removeHoverDom(treeId, treeNode) {
        $("#addBtn_"+treeNode.tId).unbind().remove();
};

function beforeClick(treeId, treeNode, clickFlag) {
        className = (className === "dark" ? "":"dark");
        showLog("[ "+getTime()+" beforeClick ]&nbsp;&nbsp;" + treeNode.name );
        return (treeNode.click != false);
}

function onClick(event, treeId, treeNode, clickFlag) {
        
        showLog("[ "+getTime()+" onClick ]&nbsp;&nbsp;clickFlag = " + clickFlag + " (" + (clickFlag===1 ? "普通选中": (clickFlag===0 ? "<b>取消选中</b>" : "<b>追加选中</b>")) + ")");
}	

$(function() {
        let pMain=$('div[data-id="_pages_bonusFrame"]');

        //console.log(pMain.attr('class'));
        var curMenu = null, zTree_Menu = null;

        let pLoads=[];

        var setting = {
                edit: {
                        enable: true,
                        editNameSelectAll: true,
                        showRemoveBtn: showRemoveBtn,
                        showRenameBtn: showRenameBtn
                },
                view: {
                        showLine: false,
                        showIcon: false,
                        selectedMulti: false,
                        addHoverDom: addHoverDom,
                        removeHoverDom: removeHoverDom,
                },
                data: {
                        simpleData: {
                                enable: true
                        }
                },
                callback: {
                        beforeDrag: beforeDrag,
                        beforeDrop: beforeDrop,
                        beforeEditName: beforeEditName,
                        beforeRemove: beforeRemove,
                        beforeRename: beforeRename,
                        onRemove: onRemove,
                        onRename: onRename,
                        beforeClick: beforeClick,
                        onClick: onClick
                }
        };;

        var zNodes1 =[
                { id:1, pId:0, name:"總公司", open:true}
                ,{ id:2, pId:1, name:"業務部"}
                ,{ id:7, pId:2, name:"經銷A"}
                ,{ id:18, pId:7, name:"經銷B"}
                ,{ id:20, pId:18, name:"經銷C"}
                ,{ id:3, pId:1, name:"總代理"}
                ,{ id:8, pId:3, name:"經銷A"}
                ,{ id:23, pId:3, name:"經銷B"}
                ,{ id:30, pId:3, name:"經銷C"}
                ,{ id:4, pId:1, name:"區域代理"}
                ,{ id:5, pId:1, name:"總經銷"}
                ,{ id:6, pId:1, name:"合作商"}
        ];

        var zNodes2 =[
                { id:7, pId:0, name:"總公司 > 業務部 > 經銷A", open:true}
                ,{ id:7_1, pId:7, name:"營業額 15%"}
        ];

        var zNodes3 =[
                { id:7, pId:0, name:"總公司 > 業務部 > 經銷A", open:true}
                ,{ id:7_1, pId:7,  name:"501 ~ 1000人 獎金:500元的0%"}
                ,{ id:7_2, pId:7,  name:"1001 ~ 2000人 獎金:600元的1%"}
                ,{ id:7_3, pId:7,  name:"2001 ~ 3000人 獎金:800元的2%"}
                ,{ id:7_4, pId:7,  name:"3001 ~ 4000人 獎金:1000元的3%"}
                ,{ id:7_5, pId:7,  name:"4001 ~ 5000人 獎金:1500元的4%"}
                ,{ id:7_6, pId:7,  name:"5001 ~ 6000人 獎金:1600元的5%"}
                ,{ id:7_7, pId:7,  name:"6001 ~ 7000人 獎金:1800元的6%"}
                ,{ id:7_8, pId:7,  name:"7001 ~ 8000人 獎金:3000元的7%"}
                ,{ id:7_9, pId:7,  name:"8001 ~ 9000人 獎金:5000元的8%"}
                ,{ id:7_10, pId:7, name:"9001 ~ 10000人 獎金:10000元的10%"}
        ];

        var treeObj1 = $("#bframe1");
        $.fn.zTree.init(treeObj1, setting, zNodes1);
        setCheck(treeObj1);

        var treeObj2 = $("#bframe2");
        $.fn.zTree.init(treeObj2, setting, zNodes2);
        setCheck(treeObj2);

        var treeObj3 = $("#bframe3");
        $.fn.zTree.init(treeObj3, setting, zNodes3);
        setCheck(treeObj3);

        $.when.apply($, pLoads).done(
                function ()
                {

                        pMain.css({'visibility':'visible'}).fadeTo('slow', 1,function(){
                                $('div[data-id="fakeLoad"]').remove();
                               
                                // $(this).find('div[data-scroll="1"]').overlayScrollbars({
                                //     'className': 'os-theme-light',
                                //     'scrollbars': {
                                //         'autoHide': 'm'
                                //     }
                                // });
                                                        
                        });
                }
        );
        
});
