<?php

namespace Geekbrains\Application1\Models;

class SiteInfo
{
    protected string $webServer;
    protected string $phpVersion;
    protected string $userAgent;

    public function __construct()
    {
        $this->webServer = $_SERVER['SERVER_SOFTWARE'];
        $this->phpVersion = phpversion();
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    }

    public function getWebServer(): string
    {
        return $this->webServer;
    }

    public function getPhpVersion(): string
    {
        return $this->phpVersion;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    // function getInfo(): array
    // {
    //     return [
    //         "server" => $this->webServer,
    //         "phpVersion" => $this->phpVersion,
    //         "userAgent" => $this->userAgent
    //     ];
    // }
}
