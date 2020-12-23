<?php

namespace Abhij89\UTMReferer\Sources;

use Illuminate\Http\Request;
use Abhij89\UTMReferer\UTMSource;

class UTMSource implements UTMSource
{
    public function getUTMSource(Request $request): string
    {
        return $request->get('utm_source') ?? '';
    }
    
    public function getUTMCampaign(Request $request): string
    {
        return $request->get('utm_campaign') ?? '';
    }
    
    public function getUTMMedium(Request $request): string
    {
        return $request->get('utm_medium') ?? '';
    }
    
    public function getUTMTerm(Request $request): string
    {
        return $request->get('utm_term') ?? '';
    }
    
    public function getUTMContent(Request $request): string
    {
        return $request->get('utm_content') ?? '';
    }
}
