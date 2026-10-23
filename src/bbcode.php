<?php

function bb2html($input)
{
    $search = [
        '/\[b\](.*?)\[\/b\]/is',
        '/\[i\](.*?)\[\/i\]/is',
        '/\[u\](.*?)\[\/u\]/is',
        '/\[s\](.*?)\[\/s\]/is',
        '/\[color=(#[0-9a-fA-F]{3,6})\](.*?)\[\/color\]/is',
        '/\[color=(rgb\s*\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\))\](.*?)\[\/color\]/is'
    ];

    $replace = [
        '<b>$1</b>',
        '<i>$1</i>',
        '<u>$1</u>',
        '<s>$1</s>',
        '<span style="color: $1;">$2</span>',
        '<span style="color: $1;">$2</span>'
    ];

    return preg_replace($search, $replace, $input);
}

function bbct($input)
{
    $input = bb2html(trim($input));
    $input = strip_tags($input, '<b><i><u><s><span>');

    return $input;
}