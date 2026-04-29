<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(title: "DigiMontir Mobile API", version: "1.0.0", description: "Interactive OpenAPI documentation serving the technician endpoints.")]
#[OA\Server(url: "/api", description: "Primary API server instance")]
#[OA\SecurityScheme(securityScheme: "sanctum", type: "http", scheme: "bearer", bearerFormat: "JWT")]
abstract class Controller
{
    //
}
