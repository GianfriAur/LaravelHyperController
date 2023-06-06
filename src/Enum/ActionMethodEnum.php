<?php

namespace Gianfriaur\HyperController\Enum;

enum ActionMethodEnum:string
{
    case CONNECT = 'connect';
    case DELETE = 'delete';
    case GET = 'get';
    case HEAD = 'head';
    case OPTIONS = 'options';
    case POST = 'post';
    case PUT = 'put';
    case PATCH = 'patch';
}
