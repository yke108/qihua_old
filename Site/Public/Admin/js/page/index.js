$(function(){
    initSideMenu();
    bindTabEvent();
    bindContextmenuEven();
    reCache();
})

//初始化左侧
function initSideMenu() {

    var menulist = "";
    var trueData = null;

    // /Admin/Auth/AuthUser
    //获取权限列表

    var ajaxData = {
            url: '/Admin/Auth/AuthUser'
        }

    ajax(ajaxData).then(function(rs){
        if(rs.data.isAdmin != true) {
            setSideMenu( filterMenuData(rs.data.rules[0], menuData) );
        } else {
            setSideMenu(menuData)
        }
    })

    function filterMenuData(ruleData, menuData) {
        var result = menuData.filter(function(item) {
            return !!ruleData[item.id] && !!ruleData[item.id].length
        }).map(function(item) {
            //只有一级的菜单
            if(!item.sub.length) {
                return item;
            }
            return {
                icon: item.icon,
                id: item.id,
                name: item.name,
                sub: item.sub.filter(function(x) {
                    return (ruleData[item.id].indexOf(x.id) != -1)
                })
            }
        })

        return result;
    }

    function setSideMenu(data) {
        $.each(data, function(i, n) {
            if(n.sub && n.sub.length) {
                menulist += '<div class="easyui-accordion" data-options="border:false,selected: false">';
                menulist += '<div title="'+n.name+'" icon="'+n.icon+'" >';
                menulist += '<ul>';
                $.each(n.sub, function(j, o) {
                    menulist += '<li><div style="height:30px"><a class="sideLink js_iframeLink" target="mainFrame" style="position: relative; padding-left: 20px;" data-href="/' + o.url + '" ><span class="panel-icon '+o.icon+'" ></span>' + o.name + '</a></div></li> ';
                })
                menulist += '</ul></div></div>';
            } else {
                menulist += '<p class="menu-wrap"><a target="mainFrame" class="js_iframeLink" style="position: relative;" data-href="/' + n.url + '" ><span class="panel-icon '+n.icon+'" ></span>' + n.name + '</a></p> ';
            }
        })

        $(".side-menu").append(menulist);

        $('.js_iframeLink').click(function(){
            var tabTitle = $(this).text(),
                url = $(this).attr("data-href");

            addTab(tabTitle,url);

            $('.easyui-accordion li div').removeClass("selected");
            $(this).parent().addClass("selected");
        }).hover(function(){
            $(this).parent().addClass("hover");
        },function(){
            $(this).parent().removeClass("hover");
        });

        $(".side-menu .easyui-accordion").accordion();

        $('#westWrapper').css('overflow-y', 'auto');


        setTimeout(function(){
            $('.layout-button-right').trigger('click');
        }, 500);
    }
    
}
//创建tab iframe
function addTab(subtitle,url){
    //没有tab的时候添加
    if(!$('#tabs').tabs('exists',subtitle)){
        $('#tabs').tabs('add',{
            title:subtitle,
            content:createFrame(url),
            closable:true,
            width:$('#mainPanle').width()-10,
            height:$('#mainPanle').height()-26
        });
    }else{

        $('#tabs').tabs('select',subtitle);

        setTimeout(function(){
            var currTab = $('#tabs').tabs('getSelected');

            $('#tabs').tabs('update', {
                tab: currTab,
                options: {
                    title: subtitle,
                    content: createFrame(url)
                    //href: url
                }
            })
        }, 300);
        
    }
}
//创建 iframe主体
function createFrame(url) {
    return '<iframe name="mainFrame" scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:100%;"></iframe>';
}
//绑定tab按钮事件
function bindTabEvent() {
    /*双击关闭TAB选项卡*/
    $(document).on('dblclick', '.tabs-inner', function(){
        var subtitle = $(this).children("span").text();
        $('#tabs').tabs('close',subtitle);
    })
    //绑定右键菜单事件
    $(document).on('contextmenu', '.tabs-inner', function(e){
        var subtitle =$(this).children("span").text();

        $('#mm').menu('show', {
            left: e.pageX,
            top: e.pageY
        });

        $('#mm').data("currtab",subtitle);

        return false;
    })
}
//绑定右键菜单各按钮事件
function bindContextmenuEven() {
    //关闭当前
    $('#mm-tabclose').click(function(){
        var currtab_title = $('#mm').data("currtab");
        $('#tabs').tabs('close',currtab_title);
    })
    //全部关闭
    $('#mm-tabcloseall').click(function(){
        $('.tabs-inner span').each(function(i,n){
            var t = $(n).text();
            $('#tabs').tabs('close',t);
        });
    });
    //关闭除当前之外的TAB
    $('#mm-tabcloseother').click(function(){
        var currtab_title = $('#mm').data("currtab");
        $('.tabs-inner span').each(function(i,n){
            var t = $(n).text();
            if(t!=currtab_title)
                $('#tabs').tabs('close',t);
        });
    });
    //关闭当前右侧的TAB
    $('#mm-tabcloseright').click(function(){
        var nextall = $('.tabs-selected').nextAll();
        if(nextall.length==0){
            return false;
        }
        nextall.each(function(i,n){
            var t=$('a:eq(0) span',$(n)).text();
            $('#tabs').tabs('close',t);
        });
        return false;
    });
    //关闭当前左侧的TAB
    $('#mm-tabcloseleft').click(function(){
        var prevall = $('.tabs-selected').prevAll();
        if(prevall.length==0){
            return false;
        }
        prevall.each(function(i,n){
            var t=$('a:eq(0) span',$(n)).text();
            $('#tabs').tabs('close',t);
        });
        return false;
    });

    //退出
    $("#mm-exit").click(function(){
        $('#mm').menu('hide');
    })
}

//弹出信息窗口 title:标题 msgString:提示信息 msgType:信息类型 [error,info,question,warning]
function msgShow(title, msgString, msgType) {
    $.messager.alert(title, msgString, msgType);
}

//刷新缓存按钮
function reCache(){
    $(document).on('click','.js_cache',function(){
        var self = $(this);
        var ajaxOpt = {
            url : '/Admin/ClearCache/cache_clear'
        };
        ajax(ajaxOpt).then(function(rs){
            $.messager.alert('提示', rs.msg, 'warning');
        })
    });
}
