<?php
/**
 * La Clave del Marketing - Definición de Rutas
 * 
 * Todas las rutas de la aplicación se definen aquí.
 */

// ================================================
// RUTAS PÚBLICAS (Sin autenticación)
// ================================================

// Página de inicio/login
$router->get('/', ['Auth', 'showLogin']);

// Autenticación
$router->get('/login', ['Auth', 'showLogin']);
$router->post('/login', ['Auth', 'login']);
$router->get('/register', ['Auth', 'showRegister']);
$router->post('/register', ['Auth', 'register']);
$router->get('/logout', ['Auth', 'logout']);

// ================================================
// RUTAS PROTEGIDAS (Requieren autenticación)
// ================================================

// Dashboard
$router->get('/dashboard', ['Dashboard', 'index'], ['Auth']);

// Proyectos
$router->get('/projects', ['Project', 'index'], ['Auth']);
$router->get('/projects/create', ['Project', 'create'], ['Auth']);
$router->post('/projects', ['Project', 'store'], ['Auth']);
$router->get('/projects/{id}', ['Project', 'show'], ['Auth']);
$router->get('/projects/{id}/edit', ['Project', 'edit'], ['Auth']);
$router->post('/projects/{id}', ['Project', 'update'], ['Auth']);
$router->post('/projects/{id}/delete', ['Project', 'destroy'], ['Auth']);

// ================================================
// RUTAS API (Para futuras integraciones)
// ================================================

// Placeholder para API REST
// $router->get('/api/projects', ['Api\\Project', 'index'], ['Auth', 'ApiAuth']);
