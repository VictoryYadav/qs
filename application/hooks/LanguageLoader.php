<?php

class LanguageLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->helper('language');
        $site_lang = $ci->session->userdata('site_langName');
        $ci->lang->load('message',$site_lang);
    }
}