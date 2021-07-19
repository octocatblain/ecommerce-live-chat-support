var tokenID = 0;
var newIndex = 0;
var lastMsg = {};
var nowTime = new Date();
var toggleStats = 0;
var disabledBox = [];
var response = true;
var lastNewMsg = 0;
var chatDiv;
var smilesWin = false;
var callBackID = 0;
var guestChatInt;
var checkNewMsgInt;
var uploads ={};
var pendingUploadsInt = false;
var pendingUploadsTimer;
var linkifySys = true;
var remBeforeSet = '';
var fullscreen,inline,isMobile = false;
var chatClosed = false;
var iframebottom = '-354px';

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

function loadChat(){
    if(!chatOpen) {
        $('.chatCloseBtn > span').removeClass('glyphicons-chevron-down').addClass('glyphicons-chevron-up');
        $('.chatSetBtn').addClass('hide');
    }
    if(activePage !== 'login' && activePage !== 'contact') {
        $('#chat-history').html($('#default-messages').html());
        $.get(axPath + '/load-messages-html', function (data) {
            if(data.success) {
                callBackID = data.callBackID;
                $(chatDiv).append(data.chatHTML);
                lastMsg = data.lastMsg;
                $.extend(users, data.users);
                tokenID = data.token;
                timeago.cancel();
                timeago().render($('.caltime'));
            }
            startChat();
        });
    }else{
        startChat();
    }
}

function guestChat(){
    if(chatID === 0){
        $.get(axPath + '/guest', function (data) {
            if(data.success) {
                clearInterval(guestChatInt);
                guestChatInt = false;
                chatID = data.chatID;
                userID = data.userID;
                $('#uname').val(data.name);
                $('#uemail').val(data.email);

                if(data.email !== 'no-email@guest.com')
                    $('#uemail').attr('disabled', 'disabled');

                users['user'+userID] = {id: userID, name:data.name, avatarLink: data.avatarLink};
                $('#chat-history').html($('#default-messages').html());
                if($('.input-group-right').hasClass('inDisabled')) {
                    $('#chatTxt').prop('disabled', false);
                    $('.input-group-right').removeClass('inDisabled');
                    chatClosed = false;
                }
                $('#updateUserBtn').removeClass('disabled');
                $('#closeChatBtn').removeClass('disabled');
                $('#printChat').removeClass('disabled');
                activatePage('chat');
            }
        });
    }
}

function pendingUploads(){
        var pendingData = JSON.stringify(Object.keys(uploads));

        $.post(axPath+'/upload/pending',{'data':pendingData} ,function(data) {
        if (data.success) {
            if (!data.pendingUploads) {
                sendToHome({action: "uploads", pending: false});
                pendingUploadsInt = false;
                clearInterval(pendingUploadsTimer);
            }

            $.each(data.upload, function(uploadID, value) {
                if(value.stats) {
                    $('#uploadRes'+uploadID).html('<span class="uploading"><span class="icon-upload2"></span> '+uploadingTxt+' <img class="loader" src="' + previewImgLoader + '" /></span>');
                    uploads[uploadID].doUpload(uploadID, value.uploadAuth);
                }else
                    $('#uploadRes'+uploadID).html('<span class="upFail"> <span class="icon-cross"></span> '+ value.error +'</span>');

                delete uploads[uploadID];
            });
        }
    });
}

function checkNewMsg(){
    if(response){
        response = false;
        $.get(axPath + '/check/new-messages/'+callBackID, function (data) {
            response = true;
            if(data.newMessages){
                callBackID = data.callBackID;
                var output = data.chatHTML;
                $.extend(users, data.users);
                for (var j = 0; j < output.length; ++j) {
                    if(output[j].notify === '1') {
                        notifyMsg(output[j].msg,false);
                    }else{
                        if(output[j].upload)
                            linkifySys = false;

                        addMsg(output[j].msg, output[j].user_id, output[j].staff_id, false, output[j].date, false);
                        playTone();
                    }
                }
            }
            if(data.chatStatus === 0)
                endChat(true);
        });
    }
}

function chatRate(el, score){
    $('.chatRate > .chatAct').removeClass('chatAct');
    $(el).addClass('chatAct');
    $('#chat-history .chatRateMsg').css({visibility: "visible"});
    $.get(axPath + '/rating/' + chatID + '/' + score, function (data) { });
}

function endChat(admin){
    chatClosed = true;
    $('#chatTxt').prop('disabled', true);
    $('.input-group-right').addClass('inDisabled');
    $('#chat-history').append($('#chatRateBox').html() + '<br>');
    $('#updateUserBtn').addClass('disabled');
    $('#closeChatBtn').addClass('disabled');
    $('#printChat').addClass('disabled');
    if(admin){
        $.get(axPath + '/close/0', function (data) { });
    }else{
        notifyMsg(endTxt, true);
        $.get(axPath + '/close/1', function (data) {} );
    }
    clearInterval(checkNewMsgInt);
    checkNewMsgInt = false;
}

function userEndChat(opt){
    if(opt === 1) {
        endChat(false);
        $('.chatSetBtn').removeClass('chatSetBtnAactive');
        $('.triangle').css('display','none');
        activatePage('chat');
        $('#endChat').hide();
        $('#options').show();
        $(chatDiv).animate({
            scrollTop: chatDiv.scrollHeight
        }, 'fast');
    }else if(opt === 0){
        $('#endChat').hide();
        $('#options').fadeIn();
    }else{
        $('#options').hide();
        $('#endChat').removeClass('hide').fadeIn();
    }
}

function sendTranscript(){
    $('#transcriptBtn').addClass('disabled');
    $('#transcriptIcon').removeClass('glyphicons-envelope').addClass('glyphicons-repeat spin');
    $.get(axPath + '/transcript/' + chatID, function (data) {
        if (data.success)
            notifyMsg(transSus, false);
        else {
            notifyMsg(transFail, false);
            $('#transcriptBtn').removeClass('disabled');
        }
        $('#transcriptIcon').removeClass('glyphicons-repeat spin').addClass('glyphicons-envelope');
    });
}

function showSmiles(){
    if(chatClosed) return;
    if(smilesWin) {
        hideSmiles();
    }else{
        smilesWin = true;
        $('#chat-smiles').fadeIn();
    }
}

function selSmile(code){
    $("#chatTxt").val($("#chatTxt").val() + " " + code + " ");
    var chatEl = $('#chatTxt').get(0);
    var elemLen = chatEl.value.length;
    chatEl.selectionStart = elemLen;
    chatEl.selectionEnd = elemLen;
    chatEl.focus();
    hideSmiles(true);
}

function hideSmiles(justHide){
    smilesWin = false;
    if (typeof(justHide)==='undefined') justHide = false;
    if(justHide)
        $('#chat-smiles').hide();
    else
        $('#chat-smiles').fadeOut();
}

function startChat(){
    activatePage(activePage);
    sendToHome({action:"css", arr:['display:block']});
    if(chatOpen === 1) toggleChat();
}

function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
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

function playTone() {
    if(tone) {
        var toneC = $('#tone').val();
        if(toneC == 1)
            myTone.play();
    }
}
function muteSound() {
    var toneC = $('#tone').val();
    if(toneC == 1){
        $('#tone').val(0);
        $('#toneK').removeClass('glyphicons-volume-up');
        $('#toneK').addClass('glyphicons-volume-down');
        $('#toneTxt').css('text-decoration','line-through');
    }else{
        $('#tone').val(1);
        $('#toneK').addClass('glyphicons-volume-up');
        $('#toneK').removeClass('glyphicons-volume-down');
        $('#toneTxt').css('text-decoration','none');
    }
}
function analyticsUpdate(){
    $.get(analyticsUpdateLink,function(data){});
}

$(document).ready(function() {
    chatDiv = document.getElementById("chat-history");

    //Ajax Call Settings
    $.ajaxSetup({
        timeout: 20000,
        error: function(xhr){
            unlockBtn(disabledBox);
            if(xhr.statusText === 'timeout')
                console.log('Connection Timeout: Please check your internet connection and try again!');
            else if(xhr.statusText === 'error')
                console.log('Network Error!');
        }
    });

    //Start listening services
    listen(window, 'message', recFromHome);
    listen(window, 'resize', chatResize);
    setInterval(analyticsUpdate, analyticsUpdateTime);
    timeago.register('en', locale);
    timeago().render($('.caltime'));
    sendToHome({action: "iframebottom"});

    //Is Chat Open?
    if(getRam('chatOpen') !== null)
        chatOpen = parseInt(getRam('chatOpen'));

    //Load Chat
    loadChat();

    //Notification Tone
    if(tone){
        var myTone = document.getElementById("myTone");
        myTone.src= tonePath;
    }

    //Mobile Page
    $("#rainbow-mobile-chat").click(function(e){
        $('#rainbow-mobile-chat').addClass('hide');
        $('#rainbow-chat').removeClass('hide');
        toggleChat();
        mobileLayout();
    });

    $("#chatMobileBtn").click(function(e){
        fullscreen = false;
        sendToHome({action: "fullscreen"});
        $('#rainbow-chat').css("border-radius",'8px 8px 0 0').addClass('hide');
        $('#rainbow-chat header').css("border-radius",'5px 5px 0 0');
        $('#rainbow-chat .chat-smiles').css({'width':'315px','height':'68px'});
        $('.chatCloseBtn').show();
        $('#chatMobileBtn').css("display",'none');
        $('#rainbow-mobile-chat').removeClass('hide');
        sendToHome({action: "resize"});
    });

    //Options Page
    $(".chatSetBtn").click(function(e){
        e.stopImmediatePropagation();

        if(activePage !== 'settings') {
            remBeforeSet = activePage;
            activatePage('settings');
            $('.chatSetBtn').addClass('chatSetBtnAactive');
            $('.triangle').css('display','inline');
        }else{
            activatePage(remBeforeSet);
            $('.chatSetBtn').removeClass('chatSetBtnAactive');
            $('.triangle').css('display','none');
        }
    });

    //Fullscreen Page
    $("#chatMinBtn").click(function(e){
        e.stopImmediatePropagation();
        fullScreen();
    });

    //Login Page
    $("#chatLogin").submit(function(e){
        e.preventDefault();
        var isSuccess = true;
        var lname = $('#lname').val().trim();
        var lemail = $('#lemail').val().trim();
        var lhelp = $('#lhelp option:selected').val();
        if(lname === '') {
            $('#lname-err').fadeIn();
            $('#lname').addClass('login-err');
            isSuccess = false;
        }
        if(lemail === '') {
            $('#lemail-err').fadeIn();
            $('#lemail').addClass('login-err');
            isSuccess = false;
        }else{
            if(!validateEmail(lemail)){
                $('#lemail-err').fadeIn();
                $('#lemail').addClass('login-err');
                isSuccess = false;
            }
        }
        if(lhelp === '0') {
            $('#lhelp-err').fadeIn();
            $('#lhelp').addClass('login-err');
            isSuccess = false;
        }

        if(isSuccess){
            lockBtn('loginBtn');
            $.post(axPath+'/new',{json:'1',name:lname,email:lemail,help:lhelp,image:availableAvatars[arrowCount].id},function(output){
                unlockBtn('loginBtn');
                if (typeof output.success !== 'undefined') {
                    clearInterval(guestChatInt);
                    guestChatInt = false;
                    chatID = output.chatID;
                    userID = output.userID;
                    $('#uname').val(lname);
                    $('#uemail').val(lemail);
                    $('#uemail').attr('disabled', 'disabled');
                    users['user'+userID] = {id: userID, name:lname, avatarLink: availableAvatars[arrowCount].path};
                    $('#chat-history').html($('#default-messages').html());
                    if($('.input-group-right').hasClass('inDisabled')) {
                        $('#chatTxt').prop('disabled', false);
                        $('.input-group-right').removeClass('inDisabled');
                        chatClosed = false;
                    }
                    $('#updateUserBtn').removeClass('disabled');
                    $('#closeChatBtn').removeClass('disabled');
                    $('#printChat').removeClass('disabled');
                    activatePage('chat');
                }else{
                    alert(output.error);
                }
            });
        }
    });

    $("#avatarLeft").click(function(){
        arrowCount--;
        if(arrowCount === -1)
            arrowCount = availableAvatars.length - 1;

        if(typeof availableAvatars[arrowCount] === 'undefined')
            arrowCount = 0;
        updateUserAvatar(availableAvatars[arrowCount].path);
    });

    $("#avatarRight").click(function(){
        arrowCount++;
        if(arrowCount === availableAvatars.length)
            arrowCount = 0;

        if(typeof availableAvatars[arrowCount] === 'undefined')
            arrowCount = 0;
        updateUserAvatar(availableAvatars[arrowCount].path);
    });

    $('#lhelp').change(function () {
        $('#lhelp').addClass("select-txt");
    });

    $('#lhelp').focus(function() {
        $('#lhelp-err').fadeOut();
        $('#lhelp').removeClass('login-err');
    });

    $('#lname').focus(function() {
        $('#lname-err').fadeOut();
        $('#lname').removeClass('login-err');
    });

    $('#lemail').focus(function() {
        $('#lemail-err').fadeOut();
        $('#lemail').removeClass('login-err');
    });

    $('#uname').focus(function() {
        $('#uname-err').fadeOut();
        $('#uname').removeClass('login-err');
    });

    $('#uemail').focus(function() {
        $('#uemail-err').fadeOut();
        $('#uemail').removeClass('login-err');
    });

    //Chat Page
    $("#chaForm").submit(function(e){
        e.preventDefault();
        var msg = $('#chatTxt').val().trim();
        if(msg !== '') {
            $('#chatTxt').val('');
            addMsg(msg, userID, '-1',true);
        }
    });

    $('#chatTxt')
        .focus(function() {
            $('.input-group').addClass('focused');
        })
        .blur(function() {
            $('.input-group').removeClass('focused');
        });

    $('#rainbow-chat header').on('click', function() {
        toggleChat();
    });

    $("#chatTxt").click(function(e){
        hideSmiles();
    });
    $("#chat-history").click(function(e){
        hideSmiles();
    });

    $('#send-file-btn').click(function() {
        if(chatClosed) return;
        $('#send-file').click();
    });

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
            '    <div class="upInfo">File: '+filename+'</div>\n' +
            '    <div class="upInfo">Size: '+size+'</div>\n' +
            '    <hr>\n' +
            '    <div class="uploadBoxRes" id="uploadRes'+uploadID+'">\n' +
            '      <span class="uploading"><span class="icon-upload2"></span> '+txt+' <img class="loader" src="' + previewImgLoader + '" /></span>\n' +
            '    </div>\n' +
            '    </div>';

    }

    $("#send-file").on("change", function (e) {
        var file = $(this)[0].files[0];
        var upload = new Upload(file);
        var uploadMsg;
        var isSubMsg = false;

        if (typeof(lastMsg.user) === 'undefined'){
            isSubMsg = false;
        }else{
            if(lastMsg.user !== 'user'+userID)
                isSubMsg = false;
            else
                isSubMsg = true;
        }

        $.post(axPath+'/upload/new',{name:upload.getName(),size:upload.getSize(),subMsg:isSubMsg},function(data){
            if(data.success) {
                linkifySys = false;
                if(data.upload) {
                    uploadMsg = genUploadBox(data.uploadID,upload.getName(), upload.getSize(),data.type, uploadingTxt);
                    addMsg(uploadMsg, userID, -1, false, undefined, false);
                    playTone();
                    upload.doUpload(data.uploadID,data.uploadAuth,file);
                }else{
                    uploadMsg = genUploadBox(data.uploadID,upload.getName(), upload.getSize(),data.type, waitApTxt);
                    addMsg(uploadMsg, userID, -1, false, undefined, false);
                    playTone();
                    uploads[data.uploadID] = upload;
                    if (!pendingUploadsInt) {
                        sendToHome({action:"uploads", pending:true});
                        pendingUploadsInt = true;
                        pendingUploadsTimer = setInterval(pendingUploads, chatRefreshTime);
                    }
                }
            }else{
                alert(data.error);
            }
        });
    });

    //Print Chat
    $("#printChat").on("click", function (e) {
        window.open(axPath + '/print-chat', "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=600,top="+(screen.height-400)+",left="+(screen.width-840));
    });

    //Contact Page
    $('#cmsg').focus(function() {
        $('#cmsg-err').fadeOut();
        $('#cmsg').removeClass('login-err');
    });

    $('#cname').focus(function() {
        $('#cname-err').fadeOut();
        $('#cname').removeClass('login-err');
    });

    $('#cemail').focus(function() {
        $('#cemail-err').fadeOut();
        $('#cemail').removeClass('login-err');
    });

    $("#tryContactBtn").on("click", function (e) {
        $('.cSuccess').addClass('hide');
        $('.cFailed').addClass('hide');
        $('#contactStep3').css('display', 'none');
        $('#contactStep1').css('display', 'block');
    });
});
function contactFn(noCapPage){
    var isSuccess = true;
    var cname = $('#cname').val().trim();
    var cemail = $('#cemail').val().trim();
    var cmsg = $('#cmsg').val();

    if(cname === '') {
        $('#cname-err').fadeIn();
        $('#cname').addClass('login-err');
        isSuccess = false;
    }
    if(cemail === '') {
        $('#cemail-err').fadeIn();
        $('#cemail').addClass('login-err');
        isSuccess = false;
    }else{
        if(!validateEmail(cemail)){
            $('#cemail-err').fadeIn();
            $('#cemail').addClass('login-err');
            isSuccess = false;
        }
    }
    if(cmsg === '') {
        $('#cmsg-err').fadeIn();
        $('#cmsg').addClass('login-err');
        isSuccess = false;
    }

    if(isSuccess) {
        if($('#contactStep2').length > 0) {
            if (noCapPage) {
                isSuccess = false;
                $('#contactStep1').css('display', 'none');
                $('#contactStep2').css('display', 'block');
            }
        }
    }
    if(isSuccess)
        validateCaptcha('contact', {name:cname,email:cemail,msg:cmsg});
}

function startTask(task, parm, skey){
    if(task === 'contact'){
        $('.cLoad').show();
        $('#contactStep2').css('display', 'none');
        $('#contactStep3').css('display', 'block');
        $.post(frAxPath+'/contact',{json:'1',name:parm.name,email:parm.email,msg:parm.msg,skey:skey},function(data){
            $('.cLoad').hide();
            if(data.success)
                $('.cSuccess').removeClass('hide');
            else
                $('.cFailed').removeClass('hide');
        });
    }
}

function captchaCodeCheck(){
    if ($(".captchaCode").length > 0){
        var capType = $('#capType').val();
        if(capType == 'phpcap'){
            if($('input[name=scode]').val() == '')
                return true;
        }else if(capType == 'recap'){
            if(grecaptcha.getResponse() == '')
                return true;
        }
    }
    return false;
}

function captchaCodeCheckMsg(){
    if (captchaCodeCheck()){
        alert(imageVr);
        return false;
    }
    return true;
}

function validateCaptcha(task,parm){
    if ($(".captchaCode").length > 0){
        var capCode,capData,authCode;
        if (captchaCodeCheck()){
            alert(imageVr);
            return false;
        }
        capData = {capthca:'1'};
        capType = $('#capType').val();
        if(capType == 'phpcap'){
            capCode = $('input[name=scode]').val();
            capData['scode'] = capCode;
            capData['pageUID'] = $('input[name=pageUID]').val();
        }else if(capType == 'recap'){
            capCode = grecaptcha.getResponse();
            capData['g-recaptcha-response'] = capCode;
        }
        $.post(frAxPath + '/verification/get-auth',capData,function(data){
            authCode = data.split(':::');
            if(authCode[0] == '1')
                startTask(task, parm, authCode[1]);
            else{
                alert(capCodeWrg);
                if(capType == 'phpcap')
                    reloadCap();
                return false;
            }
        });
    }else{
        if ($("#authCode").length > 0)
            authCode = $('#authCode').val();
        else
            authCode = 0;
        startTask(task, parm, authCode);
    }
}

function reloadCap(){
    $('input[name="scode"]').val('');
    $('input[name="scode"]').attr("placeholder", capRefresh);
    $('input[name="scode"]').prop('disabled', true);
    $('#capImg').css("opacity","0.5");
    $.get(baseUrl + 'phpcap/reload',function(data){
        var newCap = $.trim(data).split(':::');
        $('#pageUID').val(newCap[1]);
        $('#capImg').attr('src', newCap[0]);
        $('input[name="scode"]').attr("placeholder", "");
        $('input[name="scode"]').prop('disabled', false);
        $('#capImg').css("opacity","1");
    });
}

function lockBtn(btn){
    if (btn.constructor === Array) {
        for (i = 0; i < btn.length; ++i) {
            $('#' + btn[i]).attr('disabled', '');
            disabledBox.push(btn[i]);
        }
    }else {
        $('#' + btn).attr('disabled', '');
        disabledBox.push(btn);
    }
}
function unlockBtn(btn){
    if (btn.constructor === Array) {
        for (i = 0; i < btn.length; ++i) {
            $('#' + btn[i]).removeAttr('disabled', '');
            disabledBox.pull(btn[i]);
        }
    }else {
        $('#' + btn).removeAttr('disabled', '');
        disabledBox.pull(btn);
    }
}
function htmlentities(raw){
    return raw.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
        return '&#'+i.charCodeAt(0)+';';
    });
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

function formatBytes(bytes,decimals) {
    if(bytes == 0) return '0 Bytes';
    var k = 1024,
        dm = decimals <= 0 ? 0 : decimals || 2,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function inc(){
    return tokenID++;
}

function listen(doc, action, fn) {
    if (doc.addEventListener) {
        doc.addEventListener(action, fn, false);
        return true
    } else {
        if (doc.attachEvent) {
            return doc.attachEvent("on" + action, fn)
        }
        return false;
    }
}

function sendToHome(data){
    window.parent.postMessage(data, '*');
}

function recFromHome(msg){
    if(typeof(msg.data.action) !== 'undefined') {
        if (msg.data.action === 'scroll') {
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }else if (msg.data.action === 'iframebottom') {
            iframebottom = msg.data.val;
        }else if (msg.data.action === 'analytics') {
            $.post(trackLink,msg.data.val,function(data){ });
        }else if (msg.data.action === 'inline') {
            inline = true;
            $('#rainbow-chat').css("border-radius", '0');
            $('#rainbow-chat header').css({"border-radius": '0', "cursor": 'default'});
            $('#rainbow-chat .chat-smiles').css({'width':'98%','height':'auto'});
            $('.chatCloseBtn').hide();
            $('#fullScreenBtn').hide();
            $('#separateBtn').hide();
            $('#chatOptBtn').removeClass('chatSetBtn').addClass('chatMinBtn');
            $('.triangle').css('right','20px');
        }else if (msg.data.action === 'desktop') {
            isMobile = false;
            $('#rainbow-mobile-chat').addClass('hide');
            $('#rainbow-chat').removeClass('hide').fadeIn();
        }else if (msg.data.action === 'mobile') {
            isMobile = true;
            if(toggleStats === 1){
                mobileLayout();
            }else {
                $('#rainbow-chat').addClass('hide');
                $('#rainbow-mobile-chat').removeClass('hide').fadeIn();
            }
        }
    }
}

function updateUserAvatar(url){
    var _img = document.getElementById('userAvatar');
    var newImg = new Image;
    newImg.onload = function() {
        _img.src = this.src;
    }
    newImg.src = url;
}

function activatePage(page){
    activePage = page;
    $('#pageTitle').html(pageTitle);
    $('.login-page').hide();
    $('.chat-page').hide();
    $('.contact-page').hide();
    $('.settings-page').hide();
    if(page === 'login') {
        $('.login-page').fadeIn().show();
        if (!guestChatInt) {
            chatID = 0;
            guestChatInt = setInterval(guestChat, chatRefreshTime);
        }
    }else if(page === 'chat') {
        $('.chat-page').fadeIn().show();
        if (!checkNewMsgInt) {
            if(!chatClosed)
                checkNewMsgInt = setInterval(checkNewMsg, chatRefreshTime);
        }
    }else if(page === 'contact'){
        $('.contact-page').fadeIn().show();
    }else if(page === 'settings') {
        $('.settings-page').fadeIn().show();
    }
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    return re.test(String(email).toLowerCase());
}

function cssAnimate(id, arr){
    var cssData = arr;
    var cssOut = [];
    for(var i = 0; i < cssData.length; i++) {
        var cssVal = cssData[i].split(':');
        cssOut[cssVal[0]] = cssVal[1];
    }
    $(id).animate(cssOut, {duration : 400, queue : false});
}

function toggleChat(){
    if(fullscreen) return;
    if(inline) return;

    if(toggleStats === 0) {
        $('.chatCloseBtn > span').removeClass('glyphicons-chevron-up').addClass('glyphicons-chevron-down');
        $('.chatSetBtn').removeClass('hide');
        toggleStats = 1;
        sendToHome({action: "animate", arr: ['bottom:0'], scroll: '1'});
        chatDiv.scrollTop = chatDiv.scrollHeight;
    }else if(toggleStats === 1){
        toggleStats = 0;
        $('.chatSetBtn').addClass('hide');
        $('.chatCloseBtn > span').removeClass('glyphicons-chevron-down').addClass('glyphicons-chevron-up');
        sendToHome({action:"animate", arr:['bottom:'+iframebottom]});
        if(activePage === 'settings') {
            activatePage(remBeforeSet);
            $('.chatSetBtn').removeClass('chatSetBtnAactive');
            $('.triangle').css('display','none');
        }
    }
    setRam('chatOpen', toggleStats);
}

function chatResize() {
    var windowsize = $(window).height();
    windowsize = windowsize - 100;
    $('#chat-history').css('height', windowsize + 'px');
}

function fullScreen(){
    sendToHome({action: "fullscreen"});
    if(fullscreen) {
        fullscreen = false;
        $('#rainbow-chat').css("border-radius",'8px 8px 0 0');
        $('#rainbow-chat header').css("border-radius",'5px 5px 0 0');
        $('#rainbow-chat .chat-smiles').css({'width':'315px','height':'68px'});
        $('.chatCloseBtn').show();
        $('#chatMinBtn').css("display",'none');
    }else{
        fullscreen = true;
        $('#rainbow-chat').css("border-radius", '0');
        $('#rainbow-chat header').css("border-radius", '0');
        $('#rainbow-chat .chat-smiles').css({'width':'98%','height':'auto'});
        $('.chatCloseBtn').hide();
        $('#chatMinBtn').css("display",'block');
    }
}

function mobileLayout(){
    sendToHome({action: "fullscreen"});
    fullscreen = true;
    $('#rainbow-chat').css("border-radius", '0');
    $('#rainbow-chat header').css("border-radius", '0');
    $('#rainbow-chat .chat-smiles').css({'width':'98%','height':'auto'});
    $('.chatCloseBtn').hide();
    $('#chatMobileBtn').css("display",'block');
    $('#fullScreenBtn').fadeOut();
}

function updateUserInfo(opt){
    if(opt === 0){
        $('#updateDetails').hide();
        $('#options').fadeIn();
    }else if(opt === 1){
        var uName = $('#uname').val().trim();
        var uEmail = $('#uemail').val().trim();


        var isSuccess = true;

        if(uName === '') {
            $('#uname-err').fadeIn();
            $('#uname').addClass('login-err');
            isSuccess = false;
        }
        if(uEmail === '') {
            $('#uemail-err').fadeIn();
            $('#uemail').addClass('login-err');
            isSuccess = false;
        }else{
            if(!validateEmail(uEmail)){
                $('#uemail-err').fadeIn();
                $('#uemail').addClass('login-err');
                isSuccess = false;
            }
        }

        if(isSuccess) {
            $.post(axPath + '/update-user', {name: uName, email: uEmail}, function (data) {
                if (data.success) {
                    users['user' + userID].name = uName;
                    $('#updateDetails').hide();
                    $('#options').fadeIn();
                } else {
                    alert(someErrTxt);
                }
            });
        }
    }else{
        $('#options').hide();
        $('#updateDetails').removeClass('hide').fadeIn();
    }
}

function addLastMsg(msgID,user,nowTime){
    lastMsg = {id: msgID, msgid:'msg_' + msgID, conid:'con_' + msgID, user: user, time:nowTime};
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
            $('#uploadRes'+uploadID).html('<span class="upFail"> <span class="icon-cross"></span> '+upErrTxt+'</span>');
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

function notifyMsg(txt, sendTo){
    var out,chatData;
    if(typeof(sendTo)==='undefined') sendTo = true;
    out = '<div class="notify">' + txt + '....</div>';
    var myNewMes = $(out).hide();
    $(chatDiv).append(myNewMes);
    myNewMes.fadeIn();
    $(chatDiv).animate({
        scrollTop: chatDiv.scrollHeight
    }, 'fast');
    lastMsg = {};
    if(sendTo) {
        chatData = {json:'1', msg:txt, chatID:chatID};
        $.post(axPath + '/add-notify-msg', chatData, function (data) { });
    }
}

function addMsg(txt, msgUserId, msgStaffId, sendTo, msgTime, specialchars){
    var out,appTO,msgID,chatData,activeUser,activeUserId,lastMsgUser,dataUsers;
    nowTime = new Date();
    if (typeof(sendTo)==='undefined') sendTo = true;
    if (typeof(specialchars)==='undefined') specialchars = true;
    if (typeof(msgTime)==='undefined') msgTime = nowTime;

    if(msgUserId === -1) {
        activeUserId = msgStaffId;
        activeUser = 'staff' + msgStaffId;
    }else {
        activeUserId = msgUserId;
        activeUser = 'user' + msgUserId;
    }

    if (typeof(lastMsg.user) === 'undefined'){
        //dataUsers = document.querySelectorAll('[data-user]');
        //lastMsgUser = dataUsers[dataUsers.length - 1];

        //if(lastMsgUser !== activeUser)
            newIndex = 0;
    }else{
        if(lastMsg.user !== activeUser)
            newIndex = 0;
        else
            newIndex = 1;
    }

    if(newIndex === 0) {
        newIndex = 1;
        msgID = chatID + '_' + inc();
        chatData = {json:'1', msg:txt, chatID:chatID};
        addLastMsg(msgID,activeUser,msgTime);
        out = generateMsg({id: msgID, nowTime: msgTime, msg: txt, userId: activeUserId, user: activeUser},false, specialchars);
        appTO = chatDiv;
    }else{
        chatData = {json:'1', msg:txt, chatID:chatID, subMsg: true};
        var diff = Date.parse(msgTime) - Date.parse(lastMsg.time);
        if(diff > 60000){
            msgID = chatID + '_' + inc();
            appTO = document.getElementById(lastMsg.conid);
            addLastMsg(msgID,activeUser,msgTime);
            out = generateMsg({id: msgID, nowTime: msgTime, msg: txt, userId: activeUserId, user: activeUser},true, specialchars);
        }else {
            lastMsg.time = msgTime;
            if(specialchars)
                out = '<div>' + emoticons(htmlspecialchars(txt).linkify(),emoticonsData) + '</div>';
            else
                out = '<div>' + emoticons(txt.linkify(),emoticonsData) + '</div>';
            appTO = document.getElementById(lastMsg.msgid);
        }
    }

    var myNewMes = $(out).hide();
    $(appTO).append(myNewMes);
    myNewMes.fadeIn();
    $(chatDiv).animate({
        scrollTop: chatDiv.scrollHeight
    }, 'fast');

    timeago.cancel();
    timeago().render($('.caltime'));

    if(sendTo) {
        $.post(axPath + '/add', chatData, function (data) { });
    }
}
function setRam(name,value){
    if (typeof(Storage) !== "undefined") {
        localStorage.setItem(name, value);
        return true;
    } else {
        console.log('No Web Storage support..');
    }
    return false;
}
function getRam(name){
    if (typeof(Storage) !== "undefined") {
        return localStorage.getItem(name);
    } else {
        console.log('No Web Storage support..');
    }
    return null;
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
Array.prototype.pull = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] === val) {
            this.splice(i, 1);
            i--;
        }
    }
    return this;
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
                                img.attr("alt","Preview");
                                $('#' + tempPreUID).html(img);
                            }
                        });

                    return '<a target="_blank" href="'+match+'"><div> <div id="'+tempPreUID+'" class="previewBox"><img class="previewImg" src="'+previewImgLoader+'" alt="'+loadTxt+'"></div> </div> '+match+'</a> ';
                    break;
                default:
                    return '<a target="_blank" href="'+match+'">'+match+'</a>';
            }
        })
        .replace(pseudoUrlPattern, '$1<a target="_blank" href="http://$2">$2</a>')
        .replace(emailAddressPattern, '<a target="_blank" href="mailto:$&">$&</a>');
};
}

!function (root, factory) {
    if (typeof module === 'object' && module.exports) {
        module.exports = factory(root); // nodejs support
        module.exports['default'] = module.exports; // es6 support
    }
    else
        root.timeago = factory(root);
}(typeof window !== 'undefined' ? window : this,
    function () {
        var indexMapEn = 'second_minute_hour_day_week_month_year'.split('_'),
            // build-in locales: en
            locales = {
                'en': function(number, index) {
                    if (index === 0) return ['just now', 'right now'];
                    var unit = indexMapEn[parseInt(index / 2)];
                    if (number > 1) unit += 's';
                    return [number + ' ' + unit + ' ago', 'in ' + number + ' ' + unit];
                }
            },
            // second, minute, hour, day, week, month, year(365 days)
            SEC_ARRAY = [60, 60, 24, 7, 365/7/12, 12],
            SEC_ARRAY_LEN = 6,
            // ATTR_DATETIME = 'datetime',
            ATTR_DATA_TID = 'data-tid',
            timers = {}; // real-time render timers

        // format Date / string / timestamp to Date instance.
        function toDate(input) {
            if (input instanceof Date) return input;
            if (!isNaN(input)) return new Date(toInt(input));
            if (/^\d+$/.test(input)) return new Date(toInt(input));
            input = (input || '').trim().replace(/\.\d+/, '') // remove milliseconds
                .replace(/-/, '/').replace(/-/, '/')
                .replace(/(\d)T(\d)/, '$1 $2').replace(/Z/, ' UTC') // 2017-2-5T3:57:52Z -> 2017-2-5 3:57:52UTC
                .replace(/([\+\-]\d\d)\:?(\d\d)/, ' $1$2'); // -04:00 -> -0400
            return new Date(input);
        }
        // change f into int, remove decimal. Just for code compression
        function toInt(f) {
            return parseInt(f);
        }
        // format the diff second to *** time ago, with setting locale
        function formatDiff(diff, locale, defaultLocale) {
            // if locale is not exist, use defaultLocale.
            // if defaultLocale is not exist, use build-in `en`.
            // be sure of no error when locale is not exist.
            locale = locales[locale] ? locale : (locales[defaultLocale] ? defaultLocale : 'en');
            // if (! locales[locale]) locale = defaultLocale;
            var i = 0,
                agoin = diff < 0 ? 1 : 0, // timein or timeago
                total_sec = diff = Math.abs(diff);

            for (; diff >= SEC_ARRAY[i] && i < SEC_ARRAY_LEN; i++) {
                diff /= SEC_ARRAY[i];
            }
            diff = toInt(diff);
            i *= 2;

            if (diff > (i === 0 ? 9 : 1)) i += 1;
            return locales[locale](diff, i, total_sec)[agoin].replace('%s', diff);
        }
        // calculate the diff second between date to be formated an now date.
        function diffSec(date, nowDate) {
            nowDate = nowDate ? toDate(nowDate) : new Date();
            return (nowDate - toDate(date)) / 1000;
        }
        /**
         * nextInterval: calculate the next interval time.
         * - diff: the diff sec between now and date to be formated.
         *
         * What's the meaning?
         * diff = 61 then return 59
         * diff = 3601 (an hour + 1 second), then return 3599
         * make the interval with high performace.
         **/
        function nextInterval(diff) {
            var rst = 1, i = 0, d = Math.abs(diff);
            for (; diff >= SEC_ARRAY[i] && i < SEC_ARRAY_LEN; i++) {
                diff /= SEC_ARRAY[i];
                rst *= SEC_ARRAY[i];
            }
            // return leftSec(d, rst);
            d = d % rst;
            d = d ? rst - d : rst;
            return Math.ceil(d);
        }
        // get the datetime attribute, `data-timeagp` / `datetime` are supported.
        function getDateAttr(node) {
            return getAttr(node, 'data-timeago') || getAttr(node, 'datetime');
        }
        // get the node attribute, native DOM and jquery supported.
        function getAttr(node, name) {
            if(node.getAttribute) return node.getAttribute(name); // native
            if(node.attr) return node.attr(name); // jquery
        }
        // set the node attribute, native DOM and jquery supported.
        function setTidAttr(node, val) {
            if(node.setAttribute) return node.setAttribute(ATTR_DATA_TID, val); // native
            if(node.attr) return node.attr(ATTR_DATA_TID, val); // jquery
        }
        // get the timer id of node.
        // remove the function, can save some bytes.
        // function getTidFromNode(node) {
        //   return getAttr(node, ATTR_DATA_TID);
        // }
        /**
         * timeago: the function to get `timeago` instance.
         * - nowDate: the relative date, default is new Date().
         * - defaultLocale: the default locale, default is en. if your set it, then the `locale` parameter of format is not needed of you.
         *
         * How to use it?
         * var timeagoLib = require('timeago.js');
         * var timeago = timeagoLib(); // all use default.
         * var timeago = timeagoLib('2016-09-10'); // the relative date is 2016-09-10, so the 2016-09-11 will be 1 day ago.
         * var timeago = timeagoLib(null, 'zh_CN'); // set default locale is `zh_CN`.
         * var timeago = timeagoLib('2016-09-10', 'zh_CN'); // the relative date is 2016-09-10, and locale is zh_CN, so the 2016-09-11 will be 1天前.
         **/
        function Timeago(nowDate, defaultLocale) {
            this.nowDate = nowDate;
            // if do not set the defaultLocale, set it with `en`
            this.defaultLocale = defaultLocale || 'en'; // use default build-in locale
            // for dev test
            // this.nextInterval = nextInterval;
        }
        // what the timer will do
        Timeago.prototype.doRender = function(node, date, locale) {
            var diff = diffSec(date, this.nowDate),
                self = this,
                tid;
            // delete previously assigned timeout's id to node
            node.innerHTML = formatDiff(diff, locale, this.defaultLocale);
            // waiting %s seconds, do the next render
            timers[tid = setTimeout(function() {
                self.doRender(node, date, locale);
                delete timers[tid];
            }, Math.min(nextInterval(diff) * 1000, 0x7FFFFFFF))] = 0; // there is no need to save node in object.
            //}, SET_INTERVAL)] = 0; // there is no need to save node in object.
            // set attribute date-tid
            setTidAttr(node, tid);
        };
        /**
         * format: format the date to *** time ago, with setting or default locale
         * - date: the date / string / timestamp to be formated
         * - locale: the formated string's locale name, e.g. en / zh_CN
         *
         * How to use it?
         * var timeago = require('timeago.js')();
         * timeago.format(new Date(), 'pl'); // Date instance
         * timeago.format('2016-09-10', 'fr'); // formated date string
         * timeago.format(1473473400269); // timestamp with ms
         **/
        Timeago.prototype.format = function(date, locale) {
            return formatDiff(diffSec(date, this.nowDate), locale, this.defaultLocale);
        };
        /**
         * render: render the DOM real-time.
         * - nodes: which nodes will be rendered.
         * - locale: the locale name used to format date.
         *
         * How to use it?
         * var timeago = require('timeago.js')();
         * // 1. javascript selector
         * timeago.render(document.querySelectorAll('.caltime'));
         * // 2. use jQuery selector
         * timeago.render($('.caltime'), 'pl');
         *
         * Notice: please be sure the dom has attribute `datetime`.
         **/
        Timeago.prototype.render = function(nodes, locale) {
            if (nodes.length === undefined) nodes = [nodes];
            for (var i = 0, len = nodes.length; i < len; i++) {
                this.doRender(nodes[i], getDateAttr(nodes[i]), locale); // render item
            }
        };
        /**
         * setLocale: set the default locale name.
         *
         * How to use it?
         * var timeago = require('timeago.js')();
         * timeago.setLocale('fr');
         **/
        Timeago.prototype.setLocale = function(locale) {
            this.defaultLocale = locale;
        };
        /**
         * timeago: the function to get `timeago` instance.
         * - nowDate: the relative date, default is new Date().
         * - defaultLocale: the default locale, default is en. if your set it, then the `locale` parameter of format is not needed of you.
         *
         * How to use it?
         * var timeagoFactory = require('timeago.js');
         * var timeago = timeagoFactory(); // all use default.
         * var timeago = timeagoFactory('2016-09-10'); // the relative date is 2016-09-10, so the 2016-09-11 will be 1 day ago.
         * var timeago = timeagoFactory(null, 'zh_CN'); // set default locale is `zh_CN`.
         * var timeago = timeagoFactory('2016-09-10', 'zh_CN'); // the relative date is 2016-09-10, and locale is zh_CN, so the 2016-09-11 will be 1天前.
         **/
        function timeagoFactory(nowDate, defaultLocale) {
            return new Timeago(nowDate, defaultLocale);
        }
        /**
         * register: register a new language locale
         * - locale: locale name, e.g. en / zh_CN, notice the standard.
         * - localeFunc: the locale process function
         *
         * How to use it?
         * var timeagoFactory = require('timeago.js');
         *
         * timeagoFactory.register('the locale name', the_locale_func);
         * // or
         * timeagoFactory.register('pl', require('timeago.js/locales/pl'));
         **/
        timeagoFactory.register = function(locale, localeFunc) {
            locales[locale] = localeFunc;
        };

        /**
         * cancel: cancels one or all the timers which are doing real-time render.
         *
         * How to use it?
         * For canceling all the timers:
         * var timeagoFactory = require('timeago.js');
         * var timeago = timeagoFactory();
         * timeago.render(document.querySelectorAll('.caltime'));
         * timeagoFactory.cancel(); // will stop all the timers, stop render in real time.
         *
         * For canceling single timer on specific node:
         * var timeagoFactory = require('timeago.js');
         * var timeago = timeagoFactory();
         * var nodes = document.querySelectorAll('.caltime');
         * timeago.render(nodes);
         * timeagoFactory.cancel(nodes[0]); // will clear a timer attached to the first node, stop render in real time.
         **/
        timeagoFactory.cancel = function(node) {
            var tid;
            // assigning in if statement to save space
            if (node) {
                tid = getAttr(node, ATTR_DATA_TID); // get the timer of DOM node(native / jq).
                if (tid) {
                    clearTimeout(tid);
                    delete timers[tid];
                }
            } else {
                for (tid in timers) clearTimeout(tid);
                timers = {};
            }
        };

        return timeagoFactory;
    });
