var response = true;
var linkifySys = true;
var activeChat = '';
var chats = {};
var chatUsers = {};
var chatIDs = {};
var inviteOpData = {};
var detailsRefresh = {};
var detailsRefreshInt;
var chatDiv = '';
var chatTabs, tabs;
var popOverOptions = {
    placement: function (context, source) {

        if ($(window).width() < 751) {
            return "top";
        }

        return "right";
    },
    trigger: "hover"
};
function widthMe(){
    alert($(document).width());
}
function disabledDept(){
    swal(oopsTxt, noDepTxt, "info");
    return false;
}
function loadMobilePage(page, me){
    $('#onlineDataBox').css('display', 'none');
    $('#onlineDataOpBox').css('display', 'none');
    $('#chatDataBox').css('display', 'none');
    $('#userDetails').css('display', 'none');
    $('#userPages').css('display', 'none');
    $('#scroll-nav > .active').removeClass('active');
    if(page === 'chat'){
        $('#chatDataBox').css('display', 'block');
        $(me).parent().addClass('active');
    }else if(page === 'users'){
        $('#onlineDataBox').css('display', 'block');
        $(me).parent().addClass('active');
    }else if(page === 'details'){
        $('#userDetails').css('display', 'block');
        $('#userPages').css('display', 'block');
        $(me).parent().addClass('active');
    }else if(page === 'operators'){
        $('#onlineDataOpBox').css('display', 'block');
        $(me).parent().addClass('active');
    }
}
function htmlspecialchars(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": "'",
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}
function inc(){
    return chats[activeChat].tokenID++;
}
function playTone(chatID) {
    if(tone) {
        var toneC = $('#tone' + chatID).val();
        if(toneC == 1)
            myTone.play();
    }
}
function muteSound(chatID) {
    var toneC = $('#tone' + chatID).val();
    if(toneC == 1){
        $('#tone' + chatID).val(0);
        $('#toneK' + chatID).removeClass('fa-volume-up');
        $('#toneK' + chatID).addClass('fa-volume-off ');
    }else{
        $('#tone' + chatID).val(1);
        $('#toneK' + chatID).addClass('fa-volume-up');
        $('#toneK' + chatID).removeClass('fa-volume-off ');
    }
}
function showSmiles(userChatID) {
    hideCannedMsg(userChatID);
    activeChat = userChatID;
    if (chats[activeChat].smilesWin) {
        hideSmiles(false, userChatID);
    } else {
        chats[activeChat].smilesWin = true;
        $('#' + activeChat + ' > .chat-page > .chat-smiles').fadeIn();
    }
}

function selSmile(code, userChatID) {
    activeChat = userChatID;
    var chatTxt = $('#' + activeChat + ' > .chat-page > .chatForm > .input-group > .input-group-area > .chatTxt');
    $(chatTxt).val($(chatTxt).val() + " " + code + " ");
    var chatEl = $(chatTxt).get(0);
    var elemLen = chatEl.value.length;
    chatEl.selectionStart = elemLen;
    chatEl.selectionEnd = elemLen;
    chatEl.focus();
    hideSmiles(true, userChatID);
}

function hideSmiles(justHide, userChatID) {
    activeChat = userChatID;
    var chatSmiles = $('#' + activeChat + ' > .chat-page > .chat-smiles');
    chats[activeChat].smilesWin = false;
    if (typeof(justHide) === 'undefined') justHide = false;
    if (justHide)
        $(chatSmiles).hide();
    else
        $(chatSmiles).fadeOut();
}

function refreshData(){
    //return;
    if(response){
        response = false;
        $.get(axPath + '/chats/refresh', function (data) {
            response = true;
            if (data.success) {
                $('#onlineCount').html('(' + data.onlineUsersCount + ')');
                var scroll = $('#onlineData > .ulscroll').scrollTop();
                $('#onlineData').html(data.onlineData);
                $('#onlineData > .ulscroll').scrollTop(scroll);
                $('#adminCount').html('(' + data.onlineAdminCount + ')');
                $('#adminData').html(data.adminData);
                $('#inviteData').html(data.inviteData);
                $('[data-toggle="popover"]').popover(popOverOptions);
                if(data.inviteChatBol){
                    Object.keys(data.inviteChat).forEach(function (id) {
                        if(data.inviteChat[id].admin)
                            genAdminChatWindow(data.inviteChat[id].userID, data.inviteChat[id].userName, data.inviteChat[id].chatID, true, null, data.inviteChat[id].type, data.inviteChat[id].ownerID);
                        else
                            genChatWindow(data.inviteChat[id].userID, data.inviteChat[id].userName, data.inviteChat[id].chatID, true, null, data.inviteChat[id].type, data.inviteChat[id].ownerID);
                    });
                }
                if(data.updateChatHTML){
                    Object.keys(data.chatHTML).forEach(function (chatID) {
                        $('#userPages' + data.chatHTML[chatID].chatID + ' .pagesBody').html(data.chatHTML[chatID].pagesData);
                        var output = data.chatHTML[chatID].chatHTML;
                        $.extend(users, data.chatHTML[chatID].users);
                        for (var j = 0; j < output.length; ++j) {
                            if(output[j].notify === '1') {
                                notifyMsg(output[j].msg,false, chatID);
                            }else{
                                if(output[j].upload)
                                    linkifySys = false;
                                addMsg(output[j].msg, output[j].user_id, output[j].staff_id, false, output[j].date, chatID, false, false);
                                playTone(data.chatHTML[chatID].chatID);
                            }
                        }
                        //Chat Closed
                        if(data.chatHTML[chatID].chatStatus === 0){
                            $('#chat'+ data.chatHTML[chatID].chatID + ' .chat-page').append('<input type="hidden" class="closed" value="1" />');
                            $('#chat'+ data.chatHTML[chatID].chatID + ' .chatTxt').prop('disabled', true);
                            $('#chat'+ data.chatHTML[chatID].chatID + ' .input-group-right').addClass('inDisabled');
                            $('#chat'+ data.chatHTML[chatID].chatID + ' .chatTxtIcon').prop("onclick", null).off("click");
                            $('#chat'+ data.chatHTML[chatID].chatID + ' .send-file-btn').removeClass('send-file-btn');
                        }
                    });
                }
            }else{
                swal(oopsTxt, errSomTxt, "info");
            }
        });
    }
}

function notifyMsg(txt, sendTo, userChatID){
    var out,chatData;
    if(typeof(sendTo)==='undefined') sendTo = true;
    activeChat = userChatID;
    chatDiv = $('#' + activeChat + ' > .chat-page > .chat-history');
    out = '<div class="notify">' + txt + '....</div>';
    var myNewMes = $(out).hide();
    $(chatDiv).append(myNewMes);
    myNewMes.fadeIn();
    $(chatDiv).animate({
        scrollTop: $(chatDiv)[0].scrollHeight
    }, 'fast');
    chats[activeChat].lastMsg = {}
    if(sendTo) {
        chatData = {json:'1', msg:txt, chatID:chatID};
        $.post(axPath + '/add-notify-msg', chatData, function (data) { });
    }
}

function generateMsg(data, subMsg, specialchars){
    var user = {};
    var myTemplate = '';

    if(specialchars)
        data.msg = emoticons(htmlspecialchars(data.msg).linkify(),emoticonsData);
    else
        data.msg = emoticons(data.msg.linkify(),emoticonsData);

    if (typeof users[data.user] !== 'undefined')
        user = users[data.user];
    else
        user = {name: unknownTxt, avatarLink: defaultAvatar};

    if (typeof(subMsg)==='undefined') subMsg = false;

    if(subMsg === true)
        myTemplate = subTemplate;
    else
        myTemplate = template;

    myTemplate = myTemplate.replace(/{{messageId}}/g, "msg_" + data.id);
    myTemplate = myTemplate.replace(/{{contentId}}/g, "con_" + data.id);
    myTemplate = myTemplate.replace(/{{nowTime}}/g, data.nowTime);
    myTemplate = myTemplate.replace(/{{message}}/g, data.msg);
    myTemplate = myTemplate.replace(/{{msgAdd}}/g, '');
    myTemplate = myTemplate.replace(/{{userName}}/g, user.name);
    myTemplate = myTemplate.replace(/{{avatarLink}}/g, user.avatarLink);
    return myTemplate;
}
function emoticons(string, from) {
    var translations;
    translations = from;
    return Object.keys(translations)
        .reduce(function (acc, from) {
            var fromRx = new RegExp(escapeRegExp(from), 'g');
            var to = String(translations[from][1]).replace(/\$/g, '$$$$');
            return acc.replace(fromRx, to);
        }, string);
}
function addLastMsg(msgID,user,nowTime, userChatID){
    if (typeof(userChatID)==='undefined') userChatID = activeChat;
    chats[userChatID].lastMsg = {id: msgID, msgid:'msg_' + msgID, conid:'con_' + msgID, user: user, time:nowTime};
}

function addMsg(txt, msgUserId, msgStaffId, sendTo, msgTime, userChatID, specialchars, activeChatfalse){

    var out,appTO,msgID,chatData,activeUser,activeUserId,lastMsgUser,dataUsers, activeChatX;
    nowTime = new Date();
    if (typeof(sendTo)==='undefined') sendTo = true;
    if (typeof(specialchars)==='undefined') specialchars = true;
    if (typeof(activeChatfalse)==='undefined') activeChatfalse = true;
    if (typeof(msgTime)==='undefined') msgTime = nowTime;

    if(activeChatfalse)
        activeChat = userChatID;

    activeChatX = userChatID;
    chatDiv = $('#' + activeChatX + ' > .chat-page > .chat-history');

    if(msgUserId === -1) {
        activeUserId = msgStaffId;
        activeUser = 'staff' + msgStaffId;
    }else {
        activeUserId = msgUserId;
        activeUser = 'user' + msgUserId;
    }


    if (typeof(chats[activeChatX].lastMsg.user) === 'undefined'){
        newIndex = 0;
    }else{
        if(chats[activeChatX].lastMsg.user !== activeUser)
            newIndex = 0;
        else
            newIndex = 1;
    }

    if(newIndex === 0) {
        newIndex = 1;
        msgID = chats[activeChatX].chatID + '_' + inc();
        chatData = {json:'1', msg:txt, chatID:chats[activeChatX].chatID};
        addLastMsg(msgID,activeUser,msgTime,userChatID);
        out = generateMsg({id: msgID, nowTime: msgTime, msg: txt, userId: activeUserId, user: activeUser},false, specialchars);
        appTO = chatDiv;
    }else{
        chatData = {json:'1', msg:txt, chatID:chats[activeChatX].chatID, subMsg: true};
        var diff = Date.parse(msgTime) - Date.parse(chats[activeChatX].lastMsg.time);
        if(diff > 60000){
            msgID = chats[activeChatX].chatID + '_' + inc();
            appTO = document.getElementById(chats[activeChatX].lastMsg.conid);
            addLastMsg(msgID,activeUser,msgTime,userChatID);
            out = generateMsg({id: msgID, nowTime: msgTime, msg: txt, userId: activeUserId, user: activeUser},true, specialchars);
        }else {
            chats[activeChatX].lastMsg.time = msgTime;
            if(specialchars)
                out = '<div>' + emoticons(htmlspecialchars(txt).linkify(),emoticonsData) + '</div>';
            else
                out = '<div>' + emoticons(txt.linkify(),emoticonsData) + '</div>';
            appTO = document.getElementById(chats[activeChatX].lastMsg.msgid);
        }
    }

    var myNewMes = $(out).hide();
    $(appTO).append(myNewMes);
    myNewMes.fadeIn();
    $(chatDiv).animate({
        scrollTop: $(chatDiv)[0].scrollHeight
    }, 'fast');

    timeago.cancel();
    timeago().render($('.caltime'));

    var activeTabId = $('.tab-content').find('div.active').attr('id').substr(3);

    if(activeTabId != chats[activeChatX].chatID)
        flashIt(chats[activeChatX].chatID, 1);

    if(sendTo) {
        $.post(axPath + '/add', chatData, function (data) { });
    }
}

function addChatHTML(data){
    if (data.success) {
        if (typeof(data.pagesData) !== 'undefined') {
            $('#userPages' + data.chatID).html(data.pagesData);
            $('#userPages' + data.chatID + ' .userLoad').hide();
            $('#userPages' + data.chatID + ' #tableData').removeClass('hide');
        }
        $(chatDiv).append(data.chatHTML);
        chats[activeChat].lastMsg = data.lastMsg;
        $.extend(users, data.users);
        chats[activeChat].tokenID = data.token;
        timeago.cancel();
        timeago().render($('.caltime'));
        $(chatDiv).animate({
            scrollTop: $(chatDiv)[0].scrollHeight
        }, 'fast');
    }
}

function waitGuestChat(userID, userName, chatID, loadMes, data, status){
    swal(oopsTxt, alreadyCTxt, "info");
}

function selInviteStaff(staffID){
    var chat_id = activeChat.substr(4);
    inviteOpData = {'staffID': staffID, 'chatID': chat_id};
}

function inviteUser(){
    var html = document.createElement("div");
    html.innerHTML = "<style>.swal-modal {width: 280px}.swal-content{text-align:left}.swal-button--confirm{background-color: #98D973}.swal-button--confirm:focus{box-shadow: 0 0 0 1px #fff, 0 0 0 3px rgba(152, 217, 115, 0.29)}</style>";
    html.innerHTML += $('#inviteData').html();
    swal({
        title: inOpTxt,
        content: html,
        buttons: ['Cancel', 'Invite']
    }) .then((yes) => {
        if (yes) {
            if(typeof(inviteOpData.chatID) !== 'undefined') {
                $.post(axPath + '/inviteStaff', inviteOpData, function (data) {
                    if (data.success) {
                        swal("Success!", inTxt, "success");
                    } else {
                        if (typeof(data.error) === 'undefined')
                            swal(oopsTxt, intTxt, "error");
                        else
                            swal(oopsTxt, data.error, "error");
                    }
                    inviteOpData = {};
                });
            }else{
                swal(oopsTxt, selOpTxt, "error");
            }
        }else{
            inviteOpData = {};
        }
    });
}

function guestChatWindow(userID, userName, chatID, loadMes, data, status, ownerID) {
    swal({
        title: inUsTxt,
        text: areInUsTxt,
        buttons: [cancelTxt, inviteTxt],
        dangerMode: true,
    })
        .then((yes) => {
            if (yes) {

                $.get(axPath + '/invite/' + userID + '/' + data ,function(inviteData){
                    if(inviteData.success) {
                        genChatWindow(inviteData.userID, inviteData.userName, inviteData.chatID, true, undefined, undefined, inviteData.ownerID);
                    }else{
                        swal(oopsTxt, unStartTxt, "error");
                    }
                });
            }
        });
}

function joinChatWindow(userID, userName, chatID, loadMes, data, status, ownerID) {
    swal({
        title: joinTxt,
        text: useChatTxt,
        buttons: [cancelTxt, joinTxt1],
        dangerMode: true,
    })
        .then((yes) => {
            if (yes)
                genChatWindow(userID, userName, chatID, loadMes, data, 4, ownerID);
        });
}

function processUserDetails(){
    $.post(axPath + '/userDetailsArr', {chats: detailsRefresh}, function (data) {
        if(data.success){
            if(data.remove)
                clearInterval(detailsRefreshInt);
            Object.keys(data.userDetails).forEach(function (chatID) {
                var udata = data.userDetails[chatID];
                var myUDTemplate = $('#masterUserDetails').html();
                myUDTemplate = myUDTemplate.replace(/{{ip}}/g, udata.ip);
                myUDTemplate = myUDTemplate.replace(/{{username}}/g, udata.name);
                myUDTemplate = myUDTemplate.replace(/{{email}}/g, udata.email);
                myUDTemplate = myUDTemplate.replace(/{{location}}/g, udata.location);
                myUDTemplate = myUDTemplate.replace(/{{browser}}/g, udata.browser);
                myUDTemplate = myUDTemplate.replace(/{{platform}}/g, udata.platform);
                myUDTemplate = myUDTemplate.replace(/{{ua}}/g, udata.ua);
                myUDTemplate = myUDTemplate.replace(/{{screen}}/g, udata.screen);
                myUDTemplate = myUDTemplate.replace(/{{tabID}}/g, 'tab'+chatID);
                myUDTemplate = myUDTemplate.replace(/{{chatID}}/g, chatID);
                $('#userDetails' + chatID).html(myUDTemplate);
                $('#userDetails' + chatID + ' .userLoad').hide();
                $('#userDetails' + chatID + ' .table').fadeIn();
                $('#userDetails' + chatID + ' .btnDis').prop("disabled", false);
                $('#userDetails' + chatID + ' .table').removeClass('hide');
                delete detailsRefresh['chat'+chatID];
            });
        }
    });
}

function adminChatWindow(chatAdminID, adminName, chatID, loadMes, data, status, ownerID) {

    if(chatAdminID == adminID){
        sweetAlert(oopsTxt, cantTxt , "error");
        return;
    }

    //Create Chat
    if(chatID == 0) {
        $.get(axPath + '/createAdminChat/' + chatAdminID, function (data) {
            if(data.success){
                chatID = data.chatID;
                genAdminChatWindow(chatAdminID, adminName, chatID, loadMes, data, status, ownerID);
            }
        });
    }else{
        genAdminChatWindow(chatAdminID, adminName, chatID, loadMes, data, status, ownerID);
    }
}
function cannedBox(chatID) {
    var cannedC = $('#canned' + chatID).val();
    if(cannedC == 1){
        $('#canned' + chatID).val(0);
        $('#cannedK' + chatID).removeClass('glyphicon-ok-sign');
        $('#cannedK' + chatID).addClass('glyphicon-remove-sign');
    }else{
        $('#canned' + chatID).val(1);
        $('#cannedK' + chatID).addClass('glyphicon-ok-sign');
        $('#cannedK' + chatID).removeClass('fa-remove-off ');
    }
}
function hideCannedMsg(chatID){
    var cannedDiv = $('#'+ chatID + ' #cannedMsg');
    $(cannedDiv).fadeOut();
}

function setCannedMsg(id, me){
    var msgData = $(me).find('.wordData'+ id).html();
    var typedWord = $(me).find('.word'+ id).data('temp');
    var chatID = $(me).find('.word'+ id).data('chat');
    var chatTxt = $('#chat' + chatID + ' .chatTxt').val();
    var cannedDiv = $('#chat'+ chatID + ' #cannedMsg');
    chatTxt = chatTxt.replace(new RegExp(typedWord + '$'), msgData);
    $('#chat' + chatID + ' .chatTxt').val(chatTxt);
    $('#chat' + chatID + ' .chatTxt').focus();
    $(cannedDiv).fadeOut();
}

function processCannedMsg(cannedDiv, chatID, len, arr, word, word2){
    var myCount = 1;
    if (len == 0)
        $(cannedDiv).fadeOut();
    Object.keys(arr).forEach(function (id) {
        var myVal,myKey, typedWord, foundWord, re;
        if(cannedMsgType === 1) {
            myKey = id;
            myVal = arr[id];
        }else {
            myKey = arr[id].code;
            myVal = arr[id].data;
        }

        $(cannedDiv).fadeIn();
        $('#chat' + chatID + ' .wordData' + myCount).html(myVal);

        if (myKey.match(word)) {
            re = new RegExp(word, "gi");
            foundWord = myKey.replace(re, "<strong>" + word + "</strong>");
            typedWord = word;
        }

        if (word2 != '') {
            if (myKey.match(word2 + ' ' + word)) {
                re = new RegExp(word2 + ' ' + word, "gi");
                foundWord = myKey.replace(re, "<strong>" + word2 + ' ' + word + "</strong>");
                typedWord = word2 + ' ' + word;
            }
        }
        $('#chat' + chatID + ' .word' + myCount).data('temp', typedWord);
        $('#chat' + chatID + ' .word' + myCount).data('chat', chatID);
        $('#chat' + chatID + ' .word' + myCount).html(foundWord);

        myCount++;
    });
}

function cannedMsg(txt, chatID) {

    var cannedDiv = $('#chat'+ chatID + ' #cannedMsg');

    var n = txt.split(" ");
    var word = n[n.length - 1];
    var word2 = n[n.length - 2];
    var pass = true;

    if(word != ''){
        if(word.length > 2){
            pass = false;

            if(typeof word2 === 'undefined')
                word2 = '';

            $('#chat'+ chatID + ' .word1').html('');
            $('#chat'+ chatID + ' .word2').html('');
            $('#chat'+ chatID + ' .word3').html('');

            $('#chat'+ chatID + ' .wordData1').html('');
            $('#chat'+ chatID + ' .wordData2').html('');
            $('#chat'+ chatID + ' .wordData3').html('');


            if(cannedMsgType === 1) {
                //AJAX Method
                $.post(axPath + '/search-canned', {word: word, word2: word2, chat_id: chatID}, function (data) {
                    if (data.success)
                        processCannedMsg(cannedDiv, chatID, data.count, data.canned, word, word2, cannedMsgType);
                });
            }else{
                //Offline Method

                var regExp = new RegExp("^" + word, "i");
                var result = $.grep(cannedMsgArr, function(e, i){
                    var match = regExp.test(e.code);
                    return match;
                });
                if(result.length < 3){
                    regExp = new RegExp("^" + word2 + ' ' + word, "i");

                    var result2 = $.grep(cannedMsgArr, function(e, i){
                        var match = regExp.test(e.code);
                        return match;
                    });
                }
                var results = result.concat(result2);
                processCannedMsg(cannedDiv, chatID, results.length, results, word, word2, cannedMsgType);
            }
        }
    }

    if(pass)
        $(cannedDiv).fadeOut();
}

function genAdminChatWindow(chatAdminID, adminName, chatID, loadMes, data, status, ownerID){
    ownerID = adminID;
    var user = 'staff' + chatAdminID;
    var activeChatID = 'chat' + chatID;

    flashIt(chatID, 0);

    if(typeof chatIDs[chatID] === 'undefined') {

        var myUDTemplate = $('#masterAdminDetails').html();

        $('#userDetails').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userDetails').append('<div id="userDetails'+ chatID +'" class="nohide">'+myUDTemplate+'</div>');


        chatIDs[chatID] = '';
        var myTemplate = $('#masterChatdata').html();
        myTemplate = myTemplate.replace(/{{userID}}/g, activeChatID);
        myTemplate = myTemplate.replace(/{{ownerID}}/g, ownerID);
        tabs = $('#tabs').bootstrapDynamicTabs();
        activeChat = activeChatID;
        chats[activeChatID] = {smilesWin: false, lastMsg: {}, tokenID: 0, chatID: chatID, disabledBox:[]};
        tabs.addTab({
            id: 'tab' + chatID,
            title: '<span id="title'+chatID+'"><i class="fa fa-star" aria-hidden="true"></i>  ' + adminName + '</span>',
            html: myTemplate
        });

        chatDiv = $('#' + activeChat + ' > .chat-page > .chat-history');
        var chatTxt = $('#' + activeChat + ' > .chat-page > .chatForm > .input-group > .input-group-area > .chatTxt');
        var chatForm = $('#' + activeChat + ' > .chat-page > .chatForm');

        if(loadMes){
            var loadMsgLink = axPath + '/load-messages-html/' + chatID + '/' + user;

            if(typeof status !== 'undefined')
                loadMsgLink = loadMsgLink + '/' + status;

            $.get(loadMsgLink, function (jsonData) {
                addChatHTML(jsonData);
            });
        }else{
            addChatHTML(data);
        }


        $.get(axPath + '/adminDetails/' + chatID + '/' + chatAdminID, function (udata) {
            if(udata.success){
                myUDTemplate = myUDTemplate.replace(/{{ip}}/g, udata.ip);
                myUDTemplate = myUDTemplate.replace(/{{username}}/g, udata.name);
                myUDTemplate = myUDTemplate.replace(/{{email}}/g, udata.email);
                myUDTemplate = myUDTemplate.replace(/{{location}}/g, udata.location);
                myUDTemplate = myUDTemplate.replace(/{{browser}}/g, udata.browser);
                myUDTemplate = myUDTemplate.replace(/{{platform}}/g, udata.platform);
                myUDTemplate = myUDTemplate.replace(/{{ua}}/g, udata.ua);
                myUDTemplate = myUDTemplate.replace(/{{role}}/g, udata.role);
                myUDTemplate = myUDTemplate.replace(/{{access}}/g, udata.access);
                myUDTemplate = myUDTemplate.replace(/{{logo}}/g, udata.logo);
                myUDTemplate = myUDTemplate.replace(/{{chatID}}/g, udata.chatID);
                myUDTemplate = myUDTemplate.replace(/{{tabID}}/g, 'tab'+chatID);
                $('#userDetails' + chatID).html(myUDTemplate);
                $('#userDetails' + chatID + ' .userLoad').hide();
                $('#userDetails' + chatID + ' .myprofile').fadeIn();
                $('#userDetails' + chatID + ' .table').fadeIn();
                $('#userDetails' + chatID + ' .btnDis').prop("disabled", false);
                $('#userDetails' + chatID + ' .table').removeClass('hide');
                $('#userDetails' + chatID + ' .myprofile').removeClass('hide');
            }else{
                // if(Object.keys(detailsRefresh).length === 0)
                //    detailsRefreshInt = setInterval(processUserDetails, chatRefreshTime);
                // detailsRefresh[activeChatID] = chatID;
            }
        });


        $(chatForm).submit(function(e){
            e.preventDefault();
            var msg = $(chatTxt).val().trim();
            if(msg !== '') {
                $(chatTxt).val('');
                addMsg(msg, -1 , adminID ,true, undefined, activeChat);
            }
        });

        $(chatTxt).on('keyup', function (e) {
            if(cannedMsgBol) {
                var cannedC = $('#canned' + chatID).val();
                if(cannedC == 1)
                    cannedMsg(chatTxt.val(), chatID);
            }
        });

        $(chatDiv).click(function(e){
            hideSmiles(false, activeChat);
        });

        $(chatTxt).click(function(e){
            hideSmiles(false, activeChat);
        });

        $(chatTxt).focus(function() {
            $('.input-group').addClass('focused');
        }).blur(function() {
            $('.input-group').removeClass('focused');
        });
        scrollTab();
    }else{
        activeChat = activeChatID;
        $('#userDetails').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userDetails' + chatID).removeClass('hide').addClass('nohide');

        $('#tabs > .nav-tabs').find('li.active').removeClass('active');  //Remove class from previous active tab
        var activeTab = $('#tabs > .nav-tabs').find('li').find('a[href="#tab'+chatID+'"]');
        activeTab.tab('show');
        scrollTab();
    }
    $('#chat'+ chatID + ' .chat-page').append('<input type="hidden" class="chatAdminID" value="'+chatAdminID+'" />');
}

function genChatWindow(userID, userName, chatID, loadMes, data, status, ownerID) {
    var user = 'user' + userID;
    var activeChatID = 'chat' + chatID;

    flashIt(chatID, 0);

    ownerID = parseInt(ownerID);
    if(ownerID === -1)
        ownerID = adminID;

    if(typeof chatIDs[chatID] === 'undefined') {

        var myUDTemplate = $('#masterUserDetails').html();
        var myUPTemplate = $('#masterUserPages').html();

        $('#userDetails').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userDetails').append('<div id="userDetails'+ chatID +'" class="nohide">'+myUDTemplate+'</div>');

        $('#userPages').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userPages').append('<div id="userPages'+ chatID +'" class="nohide">'+myUPTemplate+'</div>');

        // chatUsers[user] = '';
        chatIDs[chatID] = '';
        var myTemplate = $('#masterChatdata').html();
        myTemplate = myTemplate.replace(/{{userID}}/g, activeChatID);
        myTemplate = myTemplate.replace(/{{ownerID}}/g, ownerID);
        tabs = $('#tabs').bootstrapDynamicTabs();
        activeChat = activeChatID;
        chats[activeChatID] = {smilesWin: false, lastMsg: {}, tokenID: 0, chatID: chatID, disabledBox:[]};
        tabs.addTab({
            id: 'tab' + chatID,
            title: '<span id="title'+chatID+'">' + userName + '</span>',
            html: myTemplate
        });

        chatDiv = $('#' + activeChat + ' > .chat-page > .chat-history');
        var chatTxt = $('#' + activeChat + ' > .chat-page > .chatForm > .input-group > .input-group-area > .chatTxt');
        var chatForm = $('#' + activeChat + ' > .chat-page > .chatForm');

        if(loadMes){
            var loadMsgLink = axPath + '/load-messages-html/' + chatID + '/' + user;

            if(typeof status !== 'undefined')
                loadMsgLink = loadMsgLink + '/' + status;

            $.get(loadMsgLink, function (jsonData) {
                addChatHTML(jsonData);
            });
        }else{
            data.pagesData = myUPTemplate.replace(/{{tableData}}/g, data.pagesData);
            addChatHTML(data);
        }

        $.get(axPath + '/userDetails/' + chatID, function (udata) {
            if(udata.success){
                myUDTemplate = myUDTemplate.replace(/{{ip}}/g, udata.ip);
                myUDTemplate = myUDTemplate.replace(/{{username}}/g, udata.name);
                myUDTemplate = myUDTemplate.replace(/{{email}}/g, udata.email);
                myUDTemplate = myUDTemplate.replace(/{{location}}/g, udata.location);
                myUDTemplate = myUDTemplate.replace(/{{browser}}/g, udata.browser);
                myUDTemplate = myUDTemplate.replace(/{{platform}}/g, udata.platform);
                myUDTemplate = myUDTemplate.replace(/{{ua}}/g, udata.ua);
                myUDTemplate = myUDTemplate.replace(/{{screen}}/g, udata.screen);
                myUDTemplate = myUDTemplate.replace(/{{tabID}}/g, 'tab'+chatID);
                myUDTemplate = myUDTemplate.replace(/{{chatID}}/g, chatID);
                $('#userDetails' + chatID).html(myUDTemplate);
                $('#userDetails' + chatID + ' .userLoad').hide();
                $('#userDetails' + chatID + ' .table').fadeIn();
                $('#userDetails' + chatID + ' .btnDis').prop("disabled", false);
                $('#userDetails' + chatID + ' .table').removeClass('hide');
            }else{
                if(Object.keys(detailsRefresh).length === 0)
                    detailsRefreshInt = setInterval(processUserDetails, chatRefreshTime);
                detailsRefresh[activeChatID] = chatID;
            }
        });

        $(chatForm).submit(function(e){
            e.preventDefault();
            var msg = $(chatTxt).val().trim();
            if(msg !== '') {
                $(chatTxt).val('');
                addMsg(msg, -1 , adminID ,true, undefined, activeChat);
            }
        });

        $(chatTxt).on('keyup', function (e) {
            if(cannedMsgBol) {
                var cannedC = $('#canned' + chatID).val();
                if(cannedC == 1)
                    cannedMsg(chatTxt.val(), chatID);
            }
        });

        $(chatDiv).click(function(e){
            hideSmiles(false, activeChat);
        });

        $(chatTxt).click(function(e){
            hideSmiles(false, activeChat);
        });

        $(chatTxt).focus(function() {
            $('.input-group').addClass('focused');
        }).blur(function() {
            $('.input-group').removeClass('focused');
        });
        scrollTab();

    }else{
        activeChat = activeChatID;
        $('#userDetails').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userDetails' + chatID).removeClass('hide').addClass('nohide');
        $('#userPages').find('div.nohide').removeClass('nohide').addClass('hide');
        $('#userPages' + chatID).removeClass('hide').addClass('nohide');

        $('#tabs > .nav-tabs').find('li.active').removeClass('active');  //Remove class from previous active tab
        var activeTab = $('#tabs > .nav-tabs').find('li').find('a[href="#tab'+chatID+'"]');
        activeTab.tab('show');
        scrollTab();
    }
}

function scrollTab(){
    try {
        $('#tabs > .nav-tabs').find('li.tab_selected').removeClass('tab_selected');  //Remove class from previous active tab
        $('#tabs > .nav-tabs').find('li.active').addClass('tab_selected');

        var currentScrollPos = $('.scroll_tab_inner').scrollLeft();
        var selectedElementPos = $('#tabs > .nav-tabs').find('li.active').position().left;
        $('.scroll_tab_inner').scrollLeft(currentScrollPos + selectedElementPos);
    }
    catch(err) {
        //  document.getElementById("errorBox").innerHTML = err.message;
    }

}

function activateTab(tabID, e){

    if(typeof e !== 'undefined')
        e.preventDefault();

    tabID = tabID.substr(3);

    flashIt(tabID, 0);

    activeChat = 'chat' + tabID;

    $('#userDetails').find('div.nohide').removeClass('nohide').addClass('hide');
    $('#userDetails' + tabID).removeClass('hide').addClass('nohide');
    $('#userPages').find('div.nohide').removeClass('nohide').addClass('hide');
    $('#userPages' + tabID).removeClass('hide').addClass('nohide');

    $('#tabs > .nav-tabs').find('li.active').removeClass('active');  //Remove class from previous active tab

    var activeTab = $('#tabs > .nav-tabs').find('li').find('a[href="#tab'+tabID+'"]');
    activeTab.tab('show');

    return false;
}

function flashIt(chatID, stats){
    if(stats === 0)
        $('#title' + chatID) .removeClass('flashit');
    else
        $('#title' + chatID).addClass('flashit');
}

function closeTab(tabID, event){

    var chatID = tabID.substr(3);
    var ownerID = $('#chat' + chatID + ' .ownerID').val();
    var notify = 1;
    var chatAdminID = 0;

    if($('#chat'+ chatID + ' .closed').length)
        notify = 0;

    if($('#chat'+ chatID + ' .chatAdminID').length)
        chatAdminID =  $('#chat'+ chatID + ' .chatAdminID').val();

    if (typeof event !== 'undefined') {
        event.preventDefault();
        event.stopPropagation();
    }

    if(notify === 0){
        if(ownerID == adminID)
            closeCall(chatID, tabID, notify, chatAdminID);
        else
            leaveCall(chatID, tabID, notify);
    }else{
        if(ownerID == adminID) {
            swal({
                title: sureTxt,
                text: sureTxt1,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete)
                        closeCall(chatID, tabID, notify, chatAdminID);
                });
        }else{
            swal({
                title: leaveTxt,
                text: leaveTxt1,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete)
                        leaveCall(chatID, tabID, notify);
                });
        }
    }
    return;
}

function closeCall(chatID, tabID, notify, chatAdminID){
    $.get(axPath + '/close/' + chatID + '/' + notify + '/' + chatAdminID, function (data) {
        if (data.success) {
            tabs.closeById(tabID);
            delete chatIDs[chatID];
            $('#userDetails' + chatID).removeClass('nohide').addClass('hide').remove();
            $('#userPages' + chatID).removeClass('nohide').addClass('hide').remove();
        } else {
            swal(oopsTxt, failTxt, "error");
        }
    });
}

function leaveCall(chatID, tabID, notify){
    $.get(axPath + '/leave/' + chatID + '/' + notify, function (data) {
        if (data.success) {
            tabs.closeById(tabID);
            delete chatIDs[chatID];
            $('#userDetails' + chatID).removeClass('nohide').addClass('hide').remove();
            $('#userPages' + chatID).removeClass('nohide').addClass('hide').remove();
        } else {
            swal(oopsTxt, failTxt, "error");
        }
    });
}

function formatBytes(bytes,decimals) {
    if(bytes == 0) return '0 Bytes';
    var k = 1024,
        dm = decimals <= 0 ? 0 : decimals || 2,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function refreshAdminAnalytics(){
    $.get(axPath + '/ping' ,function(data){});
}

function genUploadBox(uploadID,filename,size,type,txt){
    var cssClass = '';
    if(type === 'audio')
        cssClass = 'uploadAudio';
    else if(type === 'video')
        cssClass = 'uploadVideo';
    else if(type === 'image')
        cssClass = 'uploadImg';
    else if(type === 'zip')
        cssClass = 'uploadZip';

    size = formatBytes(size);

    return '<div class="uploadBox '+cssClass+'" id="upload'+uploadID+'">\n' +
        '    <div class="upInfo">'+fileTxt+' '+filename+'</div>\n' +
        '    <div class="upInfo">'+sizeTxt+' '+size+'</div>\n' +
        '    <hr>\n' +
        '    <div class="uploadBoxRes" id="uploadRes'+uploadID+'">\n' +
        '      <span class="uploading"><span class="icon-upload2"></span> '+txt+' <img class="loader" src="' + previewImgLoader + '" /></span>\n' +
        '    </div>\n' +
        '    </div>';

}

if(!String.linkify) {
    String.prototype.linkify = function() {

        if(!linkifySys){
            linkifySys = true;
            return this;
        }

        var urlPattern = /\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim;

        var pseudoUrlPattern = /(^|[^\/])(www\.[\S]+(\b|$))/gim;

        var emailAddressPattern = /[\w.]+@[a-zA-Z_-]+?(?:\.[a-zA-Z]{2,6})+/gim;

        return this
            .replace(urlPattern, function (match) {
                var extension = match.substr( (match.lastIndexOf('.') +1) );
                switch(extension) {
                    case 'jpg':
                    case 'png':
                    case 'jpeg':
                    case 'gif':
                    case 'bmp':

                        var tempPreUID = 'pre' + new Date().getTime().toString() + Math.floor((Math.random() * 1000) + 1).toString();

                        var img = $("<img />").attr('src', match)
                            .on('load error', function(e) {
                                if (e.type === "error") {
                                    img.attr("alt",errLoadTxt);
                                    $('#' + tempPreUID).html('<i style="color: #e74c3c;" class="fa fa-exclamation-triangle"></i>');
                                }else if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                                    img.attr("alt",errLoadTxt);
                                    $('#' + tempPreUID).html('<i style="color: #e74c3c;" class="fa fa-exclamation-triangle"></i>');
                                } else {
                                    img.attr("class","previewImg");
                                    img.attr("alt",previewTxt);
                                    $('#' + tempPreUID).html(img);
                                }
                            });

                        return '<a target="_blank" href="'+match+'"><div> <div id="'+tempPreUID+'" class="previewBox"><img class="previewImg" src="'+previewImgLoader+'" alt="'+loadingTxt+'"></div> </div> '+match+'</a> ';
                        break;
                    default:
                        return '<a target="_blank" href="'+match+'">'+match+'</a>';
                }
            })
            .replace(pseudoUrlPattern, '$1<a target="_blank" href="http://$2">$2</a>')
            .replace(emailAddressPattern, '<a target="_blank" href="mailto:$&">$&</a>');
    };
}

var Upload = function (file) {
    this.file = file;
};

Upload.prototype.getType = function() {
    return this.file.type;
};
Upload.prototype.getSize = function() {
    return this.file.size;
};
Upload.prototype.getName = function() {
    return this.file.name;
};
Upload.prototype.doUpload = function (uploadID,uploadAuth) {
    var that = this;
    var formData = new FormData();

    // add assoc key values, this will be posts values
    formData.append("file", this.file, this.getName());
    formData.append("upload_file", true);

    $.ajax({
        type: "POST",
        url: axPath+'/upload/process/'+uploadID+'/'+uploadAuth,
        xhr: function () {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', that.progressHandling, false);
            }
            return myXhr;
        },
        success: function (data) {
            if(data.success){
                if(data.upload){
                    $('#uploadRes'+uploadID).html('<a class="uploadBoxHref" target="_blank" href="'+axPath+'/upload/view/'+uploadID+'"><span class="icon-download2"></span> '+downloadTxt+'</a>');
                }else{
                    $('#uploadRes'+uploadID).html('<span class="upFail"> <span class="icon-cross"></span> '+ data.error +'</span>');
                }
            }
        },
        error: function (error) {
            $('#uploadRes'+uploadID).html('<span class="upFail"> <span class="icon-cross"></span> '+upFailTxt+'</span>');
        },
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        timeout: 60000
    });
};

Upload.prototype.progressHandling = function (event) {
    var percent = 0;
    var position = event.loaded || event.position;
    var total = event.total;
    var progress_bar_id = "#progress-wrp";
    if (event.lengthComputable) {
        percent = Math.ceil(position / total * 100);
    }
    // update progressbars classes so it fits your code
    $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
    $(progress_bar_id + " .status").text(percent + "%");
};

$(document).ready(function() {
    //Ajax Call Settings
    $.ajaxSetup({
        timeout: 20000,
        error: function (xhr) {
            if (xhr.statusText === 'timeout')
                console.log('Connection Timeout: Please check your internet connection and try again!');
            else if (xhr.statusText === 'error')
                console.log('Network Error!');
        }
    });

    setInterval(refreshAdminAnalytics, refreshAnalyticsTime);
    timeago.register('en', locale);
    timeago().render($('.caltime'));

    chatTabs = $('#chatTabs').scrollTabs();
    tabs = $('#tabs').bootstrapDynamicTabs();

    if(tone){
        var myTone = document.getElementById("myTone");
        myTone.src= tonePath;
    }

    $('.tab-content').on('click', '.send-file-btn', function(){
        $('.tab-content ' + '#'+activeChat + ' .send-file').click();

    });

    $('.tab-content').on('change', '.send-file', function(e){
        var uploadMsg;
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        var isSubMsg = false;
        var upChatID = activeChat.substr(4);

        if(chats[activeChat].lastMsg.user === 'staff'+adminID)
            isSubMsg = true;

        $.post(axPath+'/upload/new',{name:upload.getName(),size:upload.getSize(),subMsg:isSubMsg,chat_id:upChatID},function(data) {
            if (data.success) {
                linkifySys = false;
                if (data.upload) {
                    uploadMsg = genUploadBox(data.uploadID,upload.getName(), upload.getSize(),data.type,'Uploading...');
                    addMsg(uploadMsg, -1, adminID, false, undefined, activeChat, false);
                    upload.doUpload(data.uploadID,data.uploadAuth,file);
                }
            }else{
                alert(data.error);
            }
        });
    });

    $.get(axPath + '/chats/load-all', function (data) {
        if (data.success) {
            if(data.updateChatHTML) {
                Object.keys(data.chatHTML).forEach(function (chatID) {
                    var aUser = data.chatHTML[chatID].aUser;
                    if(aUser.substr(0, 5) === 'staff')
                        genAdminChatWindow(data.userList[aUser].id, data.userList[aUser].name, data.chatHTML[chatID].chatID, false, data.chatHTML[chatID], undefined , data.chatHTML[chatID].ownerID);
                    else
                        genChatWindow(data.userList[aUser].id, data.userList[aUser].name, data.chatHTML[chatID].chatID, false, data.chatHTML[chatID], undefined , data.chatHTML[chatID].ownerID);
                    //Chat Closed
                    if(data.chatHTML[chatID].chatStatus === 0){
                        $('#chat'+ data.chatHTML[chatID].chatID + ' .chat-page').append('<input type="hidden" class="closed" value="1" />');
                        $('#chat'+ data.chatHTML[chatID].chatID + ' .chatTxt').prop('disabled', true);
                        $('#chat'+ data.chatHTML[chatID].chatID + ' .input-group-right').addClass('inDisabled');
                        $('#chat'+ data.chatHTML[chatID].chatID + ' .chatTxtIcon').prop("onclick", null).off("click");
                        $('#chat'+ data.chatHTML[chatID].chatID + ' .send-file-btn').removeClass('send-file-btn');
                    }
                });
            }
            if(data.inviteChatBol){
                Object.keys(data.inviteChat).forEach(function (id) {
                    if(data.inviteChat[id].admin)
                        genAdminChatWindow(data.inviteChat[id].userID, data.inviteChat[id].userName, data.inviteChat[id].chatID, true, null, data.inviteChat[id].type, data.inviteChat[id].ownerID);
                    else
                        genChatWindow(data.inviteChat[id].userID, data.inviteChat[id].userName, data.inviteChat[id].chatID, true, null, data.inviteChat[id].type, data.inviteChat[id].ownerID);
                });
            }
            setInterval(refreshData, chatRefreshTime);
            $('#onlineCount').html('(' + data.onlineUsersCount + ')');
            $('#onlineData').html(data.onlineData);
            $('#adminCount').html('(' + data.onlineAdminCount + ')');
            $('#adminData').html(data.adminData);
            $('#inviteData').html(data.inviteData);
            $('[data-toggle="popover"]').popover(popOverOptions);
        }else{
            swal(oopsTxt, errSomTxt, "info");
        }
    });

});