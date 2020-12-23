<?php

namespace Abhij89\UTMReferer;

use Illuminate\Http\Request;

interface UTMSource
{
    /**
     * Retrieve utm_source from a request. If no utm_source was found, return an empty string.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function getUTMSource(Request $request): string;
    
    /**
     * Retrieve utm_campaign from a request. If no utm_campaign was found, return an empty string.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function getUTMCampaign(Request $request): string;
    
    /**
     * Retrieve utm_medium from a request. If no utm_medium was found, return an empty string.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function getUTMMedium(Request $request): string;
    
    /**
     * Retrieve utm_term from a request. If no utm_term was found, return an empty string.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function getUTMTerm(Request $request): string;
    
    /**
     * Retrieve utm_content from a request. If no utm_content was found, return an empty string.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    public function getUTMContent(Request $request): string;
}
