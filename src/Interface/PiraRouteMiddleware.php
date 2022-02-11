<?php

namespace Interface;

use Routing\Request;

interface PiraRouteMiddleware
{
    public static function handler(Request $request);
}