<?php
defined('CHAT_TEM') or die(header('HTTP/1.1 403 Forbidden'));

$template = '<div class="chat-message clearfix">' .
    '<img src="{{avatarLink}}" alt="{{userName}}" width="40" height="40">' .
    '<div class="chat-message-content clearfix" id="{{contentId}}" >' .
    '<span class="chat-time caltime" data-timeago="{{nowTime}}">-</span>' .
    '<h5>{{userName}}</h5>' .
    '<div id="{{messageId}}">{{message}}{{msgAdd}}</div>' .
    '</div>' .
    '</div>';

$subTemplate = '<div id="{{contentId}}"><span class="chat-time caltime" data-timeago="{{nowTime}}">-</span>'.
    '<div id="{{messageId}}">{{message}}{{msgAdd}}</div></div>';