<?php

namespace RRoute\Interfaces;

use RRoute\Routing\Request;

interface PiraRouteMiddleware
{
    public static function handler(Request $request);
}