<?php
declare(strict_types=1);


namespace SwooleGin\Utils;

/**
 * @see golang net/http status.go
 */
class HTTPStatus
{
    const StatusContinue = 100; // RFC 7231, 6.2.1
    const StatusSwitchingProtocols = 101; // RFC 7231, 6.2.2
    const StatusProcessing = 102; // RFC 2518, 10.1
    const StatusEarlyHints = 103; // RFC 8297

    const StatusOK = 200; // RFC 7231, 6.3.1
    const StatusCreated = 201; // RFC 7231, 6.3.2
    const StatusAccepted = 202; // RFC 7231, 6.3.3
    const StatusNonAuthoritativeInfo = 203; // RFC 7231, 6.3.4
    const StatusNoContent = 204; // RFC 7231, 6.3.5
    const StatusResetContent = 205; // RFC 7231, 6.3.6
    const StatusPartialContent = 206; // RFC 7233, 4.1
    const StatusMultiStatus = 207; // RFC 4918, 11.1
    const StatusAlreadyReported = 208; // RFC 5842, 7.1
    const StatusIMUsed = 226; // RFC 3229, 10.4.1

    const StatusMultipleChoices = 300; // RFC 7231, 6.4.1
    const StatusMovedPermanently = 301; // RFC 7231, 6.4.2
    const StatusFound = 302; // RFC 7231, 6.4.3
    const StatusSeeOther = 303; // RFC 7231, 6.4.4
    const StatusNotModified = 304; // RFC 7232, 4.1
    const StatusUseProxy = 305; // RFC 7231, 6.4.5
    const _ = 306; // RFC 7231, 6.4.6 (Unused)
    const StatusTemporaryRedirect = 307; // RFC 7231, 6.4.7
    const StatusPermanentRedirect = 308; // RFC 7538, 3

    const StatusBadRequest = 400; // RFC 7231, 6.5.1
    const StatusUnauthorized = 401; // RFC 7235, 3.1
    const StatusPaymentRequired = 402; // RFC 7231, 6.5.2
    const StatusForbidden = 403; // RFC 7231, 6.5.3
    const StatusNotFound = 404; // RFC 7231, 6.5.4
    const StatusMethodNotAllowed = 405; // RFC 7231, 6.5.5
    const StatusNotAcceptable = 406; // RFC 7231, 6.5.6
    const StatusProxyAuthRequired = 407; // RFC 7235, 3.2
    const StatusRequestTimeout = 408; // RFC 7231, 6.5.7
    const StatusConflict = 409; // RFC 7231, 6.5.8
    const StatusGone = 410; // RFC 7231, 6.5.9
    const StatusLengthRequired = 411; // RFC 7231, 6.5.10
    const StatusPreconditionFailed = 412; // RFC 7232, 4.2
    const StatusRequestEntityTooLarge = 413; // RFC 7231, 6.5.11
    const StatusRequestURITooLong = 414; // RFC 7231, 6.5.12
    const StatusUnsupportedMediaType = 415; // RFC 7231, 6.5.13
    const StatusRequestedRangeNotSatisfiable = 416; // RFC 7233, 4.4
    const StatusExpectationFailed = 417; // RFC 7231, 6.5.14
    const StatusTeapot = 418; // RFC 7168, 2.3.3
    const StatusMisdirectedRequest = 421; // RFC 7540, 9.1.2
    const StatusUnprocessableEntity = 422; // RFC 4918, 11.2
    const StatusLocked = 423; // RFC 4918, 11.3
    const StatusFailedDependency = 424; // RFC 4918, 11.4
    const StatusTooEarly = 425; // RFC 8470, 5.2.
    const StatusUpgradeRequired = 426; // RFC 7231, 6.5.15
    const StatusPreconditionRequired = 428; // RFC 6585, 3
    const StatusTooManyRequests = 429; // RFC 6585, 4
    const StatusRequestHeaderFieldsTooLarge = 431; // RFC 6585, 5
    const StatusUnavailableForLegalReasons = 451; // RFC 7725, 3

    const StatusInternalServerError = 500; // RFC 7231, 6.6.1
    const StatusNotImplemented = 501; // RFC 7231, 6.6.2
    const StatusBadGateway = 502; // RFC 7231, 6.6.3
    const StatusServiceUnavailable = 503; // RFC 7231, 6.6.4
    const StatusGatewayTimeout = 504; // RFC 7231, 6.6.5
    const StatusHTTPVersionNotSupported = 505; // RFC 7231, 6.6.6
    const StatusVariantAlsoNegotiates = 506; // RFC 2295, 8.1
    const StatusInsufficientStorage = 507; // RFC 4918, 11.5
    const StatusLoopDetected = 508; // RFC 5842, 7.2
    const StatusNotExtended = 510; // RFC 2774, 7
    const StatusNetworkAuthenticationRequired = 511; // RFC 6585, 6

    public static array $statusText = [
        self::StatusContinue => 'Continue',
        self::StatusSwitchingProtocols => 'Switching Protocols',
        self::StatusProcessing => 'Processing',
        self::StatusEarlyHints => 'Early Hints',

        self::StatusOK => 'OK',
        self::StatusCreated => 'Created',
        self::StatusAccepted => 'Accepted',
        self::StatusNonAuthoritativeInfo => 'Non-Authoritative Information',
        self::StatusNoContent => 'No Content',
        self::StatusResetContent => 'Reset Content',
        self::StatusPartialContent => 'Partial Content',
        self::StatusMultiStatus => 'Multi-Status',
        self::StatusAlreadyReported => 'Already Reported',
        self::StatusIMUsed => 'IM Used',

        self::StatusMultipleChoices => 'Multiple Choices',
        self::StatusMovedPermanently => 'Moved Permanently',
        self::StatusFound => 'Found',
        self::StatusSeeOther => 'See Other',
        self::StatusNotModified => 'Not Modified',
        self::StatusUseProxy => 'Use Proxy',
        self::StatusTemporaryRedirect => 'Temporary Redirect',
        self::StatusPermanentRedirect => 'Permanent Redirect',

        self::StatusBadRequest => 'Bad Request',
        self::StatusUnauthorized => 'Unauthorized',
        self::StatusPaymentRequired => 'Payment Required',
        self::StatusForbidden => 'Forbidden',
        self::StatusNotFound => 'Not Found',
        self::StatusMethodNotAllowed => 'Method Not Allowed',
        self::StatusNotAcceptable => 'Not Acceptable',
        self::StatusProxyAuthRequired => 'Proxy Authentication Required',
        self::StatusRequestTimeout => 'Request Timeout',
        self::StatusConflict => 'Conflict',
        self::StatusGone => 'Gone',
        self::StatusLengthRequired => 'Length Required',
        self::StatusPreconditionFailed => 'Precondition Failed',
        self::StatusRequestEntityTooLarge => 'Request Entity Too Large',
        self::StatusRequestURITooLong => 'Request URI Too Long',
        self::StatusUnsupportedMediaType => 'Unsupported Media Type',
        self::StatusRequestedRangeNotSatisfiable => 'Requested Range Not Satisfiable',
        self::StatusExpectationFailed => 'Expectation Failed',
        self::StatusTeapot => 'I\'m a teapot',
        self::StatusMisdirectedRequest => 'Misdirected Request',
        self::StatusUnprocessableEntity => 'Unprocessable Entity',
        self::StatusLocked => 'Locked',
        self::StatusFailedDependency => 'Failed Dependency',
        self::StatusTooEarly => 'Too Early',
        self::StatusUpgradeRequired => 'Upgrade Required',
        self::StatusPreconditionRequired => 'Precondition Required',
        self::StatusTooManyRequests => 'Too Many Requests',
        self::StatusRequestHeaderFieldsTooLarge => 'Request Header Fields Too Large',
        self::StatusUnavailableForLegalReasons => 'Unavailable For Legal Reasons',

        self::StatusInternalServerError => 'Internal Server Error',
        self::StatusNotImplemented => 'Not Implemented',
        self::StatusBadGateway => 'Bad Gateway',
        self::StatusServiceUnavailable => 'Service Unavailable',
        self::StatusGatewayTimeout => 'Gateway Timeout',
        self::StatusHTTPVersionNotSupported => 'HTTP Version Not Supported',
        self::StatusVariantAlsoNegotiates => 'Variant Also Negotiates',
        self::StatusInsufficientStorage => 'Insufficient Storage',
        self::StatusLoopDetected => 'Loop Detected',
        self::StatusNotExtended => 'Not Extended',
        self::StatusNetworkAuthenticationRequired => 'Network Authentication Required',
    ];

    public static function statusText(int $code): string
    {
        return self::$statusText[$code] ?? 'Server Error';
    }
}