
/**
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */

window.setTimeout(function() {
    $(".alert-dismissable").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 2000);

var deleteLinks = document.querySelectorAll('.delete');

for (var i = 0; i < deleteLinks.length; i++) {
  deleteLinks[i].addEventListener('click', function(event) {
      event.preventDefault();

      var choice = confirm(this.getAttribute('data-confirm'));

      if (choice) {
        window.location.href = this.getAttribute('href');
      }
  });
}

String.prototype.replaceArray = function(find, replace) {
  var replaceString = this;
  var regex; 
  for (var i = 0; i < find.length; i++) {
    regex = new RegExp(find[i], "g");
    replaceString = replaceString.replace(regex, replace[i]);
  }
  return replaceString;
};

(function($){
    $.unserialize = function(serializedString){
        var str = decodeURI(serializedString);
        var pairs = str.split('&');
        var obj = {}, p, idx;
        for (var i=0, n=pairs.length; i < n; i++) {
            p = pairs[i].split('=');
            idx = p[0];
            if (obj[idx] === undefined) {
                obj[idx] = unescape(p[1]);
            }else{
                if (typeof obj[idx] == "string") {
                    obj[idx]=[obj[idx]];
                }
                obj[idx].push(unescape(p[1]));
            }
        }
        return obj;
    };
})(jQuery);

function uriFix(str){
    var find = ["<", ">", " ", "_", "#", "@", "{", "}"];
    var replaceString = str;
    var regex; 
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], "g");
        replaceString = replaceString.replace(regex, '-');
    }
    return replaceString.toLowerCase();
}

function readURL(input,box){
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(box).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteItem(link,tableID,deleteTxt,deleteSus,deleteErr){
    if (typeof(deleteTxt)==='undefined') deleteTxt = "You will not be able to recover this deleted item!";
    if (typeof(deleteSus)==='undefined') deleteSus = "Selected item has been deleted.";
    if (typeof(deleteErr)==='undefined') deleteErr = "Something went wrong.";
    swal({
        title: "Are you sure?",
        text: deleteTxt,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                jQuery.get(link,function(data){
                    data = $.trim(data);
                    if(data === '1'){
                        $('#'+tableID).remove();
                        swal("Deleted!", deleteSus, "success");
                    }else{
                        swal("Oops!", deleteErr, "error");
                    }
                });
            }
        });
}

function groupDelItem(link,items,deleteTxt,deleteSus,deleteErr){
    if (typeof(deleteTxt)==='undefined') deleteTxt = "You will not be able to recover this deleted items!";
    if (typeof(deleteSus)==='undefined') deleteSus = "Selected items has been deleted.";
    if (typeof(deleteErr)==='undefined') deleteErr = "Something went wrong.";
    swal({
        title: "Are you sure?",
        text: deleteTxt,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                jQuery.post(link,items,function(data){
                    data = $.trim(data);
                    if(data == 1){
                        var myDeletedItems = $.unserialize(items);
                        $.each(myDeletedItems['id[]'], function( index, value ) {
                            $('#myid_'+value).remove();
                        });
                        swal("Deleted!", deleteSus, "success");
                    }else{
                        swal("Oops!", deleteErr, "error");
                    }
                });
            }
        });
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


//Chat App
var userPendingUploads = false;
var userPendingUploadsTimer;
var userPendingUploadsArr = [];

function uploadApproval(uploadID, stats){
    $.get(axPath+'/userUpload/approval/'+  uploadID + '/' + stats,function(data) {
        if (data.success) {
            if(stats === 1) {
                $('#uploadRes' + uploadID).html('<span class="upApp"> <span class="icon-checkmark"></span> ' + data.msg + '</span>');
                if(!userPendingUploads){
                    userPendingUploads = true;
                    userPendingUploadsTimer = setInterval(userPendingUploadsFn, 5000);
                    userPendingUploadsArr[uploadID] = true;
                }
            }else
                $('#uploadRes' + uploadID).html('<span class="upFail"> <span class="icon-cross"></span> '+data.msg+'</span>');
        }else{
            $('#uploadRes' + uploadID).html('<span class="upFail"> <span class="icon-cross"></span> Database Error!</span>');
        }
    });
}

function userPendingUploadsFn(){
    var pendingData = JSON.stringify(Object.keys(userPendingUploadsArr));

    $.post(axPath+'/userUpload/pending',{'data':pendingData} ,function(data) {
        if (data.success) {
            if (!data.pendingUploads) {
                userPendingUploads = false;
                clearInterval(userPendingUploadsTimer);
            }

            $.each(data.upload, function(uploadID, value) {
                $('#uploadRes'+uploadID).html('<a class="uploadBoxHref" target="_blank" href="'+axPath+'/upload/view/'+uploadID+'"><span class="icon-download2"></span> Download</a>');
                delete userPendingUploadsArr[uploadID];
            });
        }
    });
}

$.AdminLTESidebarTweak = {};

$.AdminLTESidebarTweak.options = {
    EnableRemember: true,
    NoTransitionAfterReload: false
    //Removes the transition after page reload.
};

$(function () {
    "use strict";

    $('.sidebar-toggle').on('click', function(e) {
        if($.AdminLTESidebarTweak.options.EnableRemember){
        var cookieState = getCookie("toggleState");
        if(cookieState === 'opened' || cookieState === '')
            document.cookie = "toggleState=closed";
        else
            document.cookie = "toggleState=opened";
        }
    });

    if($.AdminLTESidebarTweak.options.EnableRemember){
        var re = new RegExp('toggleState' + "=([^;]+)");
        var value = re.exec(document.cookie);
        var toggleState = (value != null) ? unescape(value[1]) : null;
        if(toggleState == 'closed'){
            if($.AdminLTESidebarTweak.options.NoTransitionAfterReload){
                $("body").addClass('sidebar-collapse hold-transition').delay(100).queue(function(){
                    $(this).removeClass('hold-transition');
                });
            }else{
                $("body").addClass('sidebar-collapse');
            }
        }
    }
});